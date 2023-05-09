<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_info_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "budget-information");
			redirect('login', 'refresh');
		}
		$this->load->model('rkap_mdl', 'rkap');

	}

	public function index()
	{

		$data['title']          = "E-Budget Tracking";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/budget_information";
		$data['get_exist_year'] = $this->rkap->get_exist_year_master();

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );

		$groups = $this->session->userdata('group_id');

		foreach ($groups as $key => $value) {

			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

	    $directorat = check_is_bod();
	    $binding    = check_binding();

	    $directorat_name = false;

	    if(count($directorat) > 1 && $binding['binding'] != false){
			$directorat = $binding['data_binding']['directorat'];
	    }

		$data['directorat']   = $directorat;
		$data['binding']      = $binding['binding'];
		$data['data_binding'] = $binding['data_binding'];

		if(count($directorat) == 1){
			$directorat_name = $directorat[0]['DIRECTORAT_NAME'];
		}
		$program_id =  $this->budget->get_program_id($directorat_name);

		$data['program_id'] = $program_id;

		$data['group_name']    = $group_name;
		$data['breadcrumb']    = $breadcrumb;
		
		$this->template->load('main', $data['template_page'], $data);

	}

}

/* End of file Budget_info_ctl.php */
/* Location: ./application/controllers/rkap/Budget_info_ctl.php */