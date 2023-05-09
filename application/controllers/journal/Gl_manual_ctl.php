<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gl_manual_ctl extends CI_Controller {

	private $module_name = "gl",
			$module_url   = "journal-manual",
			$module_title = "Journal Manual";
			
	protected $status_header;

	public function __construct()
	{
		
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->status_header = 401;

	}

	public function index()
	{

		if($this->ion_auth->is_admin() == true || in_array("journal-manual", $this->session->userdata['menu_url']) ){

			$data['title']         = $this->module_title;
			$data['module']        = "datatable";
			$data['template_page'] = $this->module_name."/gl_manual_create";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $this->module_title, "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			$data['batch_name']    = get_shorted_user($this->session->userdata('user_id')).date("d/")."MNL/".date("my");

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}


	public function save_gl(){

		$batch_name   = $this->input->post('batch_name');
		$data_lines   = $this->input->post('data_lines');

		$exp_gl_date = explode("-", $this->input->post('gl_date'));
		$gl_date     = $exp_gl_date[2]."-".$exp_gl_date[1]."-".$exp_gl_date[0];

		foreach ($data_lines as $key => $value) {

			$data[] = array(
						"GL_DATE"             => $gl_date,
						"BATCH_NAME"          => $batch_name,
						"JOURNAL_NAME"        => $value['journal_name'],
						"JOURNAL_DESCRIPTION" => $value['journal_desc'],
						"DEBIT"               => $value['debit'],
						"CREDIT"              => $value['credit'],
						"NATURE"              => $value['nature'],
						"ACCOUNT_DESCRIPTION" => $value['acc_desc']
					);

		}

		$insert   = $this->crud->create_batch("GL_JOURNAL_LINE", $data);
		$status   = false;
		$messages = "";

		if($insert > 0){
			$status = true;
		}
		else{
			$messages = "Failed to Create Journal";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);

	}

}

/* End of file Gl_manual_ctl.php */
/* Location: ./application/controllers/gl/Gl_manual_ctl.php */