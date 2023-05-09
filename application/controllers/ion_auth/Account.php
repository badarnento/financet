<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	public function edit_profile(){
		
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$data['title']           = "Edit Profile";
		$data['template_page']   = "account/profile";
		$data['module']          = "";
		$data['message']         = "";
		$data['message_success'] = "";
		
		$user_id      = $this->session->userdata['user_id'];
		$data['user'] = $this->ion_auth->user($user_id)->row();

		if (isset($_POST) && !empty($_POST))
		{
			$user = $this->ion_auth->user($user_id)->row();

			$this->form_validation->set_rules('display_name', 'Full Name', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

			if ($this->form_validation->run() === TRUE)
			{

				$dataUser = array(
					'DISPLAY_NAME' => $this->input->post('display_name')
				);

				// update the password if it was posted
				if ($this->input->post('password') != "password")
				{

					$password = $this->ion_auth->hash_password($this->input->post('password'));
					$dataUser['PASSWORD'] = $password;
					log_message("INFO", "User changed password: " . $this->session->userdata('identity') );
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->ID, $dataUser))
				{	
					$data['message_success'] = "Profile update successfully";

					$data['user'] = $this->ion_auth->user($user_id)->row();
					$session_data = array(
						'display_name' => $data['user']->DISPLAY_NAME
					);

					$this->session->set_userdata($session_data);
				}
				else
				{
					$data['message'] = "Profil failed to update";
				}
			}
			else{
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
		}

		$this->template->load('main', $data['template_page'], $data);
	}

}

/* End of file Account.php */
/* Location: ./application/controllers/ion_auth/Account.php */