<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_ctl extends CI_Controller {

	protected $tables,
			  $status_header;

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in())
		{
			redirect('/login', 'refresh');
		}
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->tables = $this->config->item('tables', 'ion_auth');

		$this->lang->load('auth');
		$this->load->helper('user_helper');
		$this->load->model('User_mdl', 'user');

		$this->status_header = 401;

	}

	public function index()
	{

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$data['title']         = "Master User";
			$data['module']        = "datatable";
			$data['template_page'] = "admin/user";
			$data['groups']        = get_all_groups();

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_user(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$result['data']            = "";
			$result['draw']            = "";
			$result['recordsTotal']    = 0;
			$result['recordsFiltered'] = 0;

			$this->status_header = 200;

			$get_all = $this->user->get_user_datatable();
			$data       = $get_all['data'];
			$total      = $get_all['total_data'];
			$start      = $this->input->post('start');
			$number     = $start+1;

			if($total > 0){

				foreach ($data as $k => $user){
					$data[$k]['GROUPS'] = $this->ion_auth->get_users_groups($user['ID'])->result_array();
				}
				
				foreach($data as $value) {

					$userGroupID = array();
					$userGroup   = array();

					foreach($value['GROUPS'] as $group) {
						$userGroupID[] = $group['ID'];
						$userGroup[]   = $group['NAME'];
					}

					$row[] = array(
							'no'           => $number,
							'id'           => $value['ID'],
							'username'     => $value['USERNAME'],
							'display_name' => $value['DISPLAY_NAME'],
							'id_dir_code'  => $value['ID_DIR_CODE'],
							'directorate'  => $value['DIRECTORAT_NAME'],
							'email'        => $value['EMAIL'],
							'group_id'     => implode(", ", $userGroupID),
							'user_group'   => implode(", ", $userGroup),
							'status'       => ($value['IS_ACTIVE'] == 1) ? 'Active' : 'Not Active',
							);
					$number++;
				}

				$result['data']            = $row;
				$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
				$result['recordsTotal']    = $total;
				$result['recordsFiltered'] = $total;
			}

		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}

	public function save_user(){

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$this->form_validation->set_rules('is_new_record', 'Is New Record', 'required|alpha_numeric');
			$is_new_record = $this->input->post('is_new_record');

			if($is_new_record == 1){
				$this->_createUser();
			}
			else{
				$this->_updateUser();
			}

		}
	}

	private function _createUser(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$identity_column = $this->config->item('identity', 'ion_auth');

			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[' . $this->tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('display_name', 'Display Name', 'trim|required');
			$this->form_validation->set_rules('ddlDirectoratName', 'directorate', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('groups[]', 'Assign Groups', 'trim|required');

			$result['status']   = false;
			$result['messages'] = "";
			$this->status_header = 200;

			if ($this->form_validation->run() === TRUE)
			{

				$email    = strtolower($this->input->post('email'));
				$identity = $this->input->post('username');
				$password = $this->config->item('default_password');

				$data = array(
					'DISPLAY_NAME' => $this->input->post('display_name'),
					'ID_DIR_CODE' => $this->input->post('ddlDirectoratName')
				);

				$create_user = $this->ion_auth->register($identity, $password, $email, $data);

				if ($create_user) {
					$groupData = $this->input->post('groups');

					foreach ($groupData as $group)
					{
						if($this->ion_auth->add_to_group($group, $create_user)){
							$status[] = "success";
						}else{
							$status[] = "failed";
						}
					}
					if(in_array("failed", $status)){
						$result['messages'] = "Failed to assign user to groups";
					}
					else{
						$result['status']   = true;
						$result['messages'] = "User successfully created";
					}
				}
				else{
					$result['messages'] = "Failed to add new user";
				}
			}
			else{
				$result['messages'] = validation_errors();
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));

	}

	private function _updateUser(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$id   = $this->input->post('id');
			$this->form_validation->set_rules('id', 'No id Selected', 'required|alpha_numeric');
			$this->form_validation->set_rules('display_name', 'Display Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('ddlDirectoratName', 'directorate', 'trim|required');
			$this->form_validation->set_rules('groups[]', 'Assign Groups', 'trim|required');

			$this->status_header = 200;
			
			if ($this->form_validation->run() === TRUE)
			{

				$data = array(
					'DISPLAY_NAME' => $this->input->post('display_name'),
					'ID_DIR_CODE'  => $this->input->post('ddlDirectoratName'),
					'EMAIL'        => $this->input->post('email')
				);

				if($this->input->post('active')){
					$data['IS_ACTIVE'] = $this->input->post('active');
				}

				if ($this->ion_auth->update($id, $data))
				{

					$groupData = $this->input->post('groups');
					$this->ion_auth->remove_from_group('', $id);

					foreach ($groupData as $grp)
					{
						$oke = false;
						if($this->ion_auth->add_to_group($grp, $id)){
							$oke = true;
						}
					}
					if($this->crud->update_history("MASTER_USER", $id) != -1 && $oke){
						$result['status']   = true;
						$result['messages'] = "User successfully created";
					}
					else{
						$result['messages'] = "Failed to assign user to groups";
					}
				}
				else
				{
					$result['messages'] = "Failed to update user";
				}
			}
			else{
				$result['messages'] = validation_errors();
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}


	public function delete_user(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){


			$id = $this->input->post('id');
			$this->form_validation->set_rules('id', 'No id Selected', 'required|alpha_numeric');

			$result['status']   = false;
			$result['messages'] = "Failed to delete data";
			$this->status_header = 200;

			$delete = $this->crud->delete($this->tables['users'], $id);

			if($delete > 0){
				$result['status']   = true;
				$result['messages'] = "Data successfully deleted";
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));

	}


	public function reset_user_pass()
	{

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$id = $this->input->post('id');

			$this->load->library('form_validation');
			$this->form_validation->set_rules('id', 'No id Selected', 'required|alpha_numeric');

			$result['status']   = false;
			$result['messages'] = "Failed to reset password";
			$this->status_header = 200;

			if ($this->ion_auth->logged_in())
			{	
				$get_identity = $this->crud->read_by_param("MASTER_USER", $id);
				$identity     = $get_identity['USERNAME'];

				$new_password = $this->config->item('default_password');

				if($this->ion_auth->reset_password($identity, $new_password)){
					$result['status']   = true;
					$result['messages'] = "Password successfully reseted";
				}
			}

		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}

}

/* End of file Users_ctl.php */
/* Location: ./application/controllers/administrator/Users_ctl.php */
