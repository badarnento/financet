<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rkap_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('rkap_mdl', 'rkap');

	}

	public function index()
	{

		$data['title']          = "Original RKAP";
		$data['module']         = "datatable";
		$data['template_page']  = "rkap/original_rkap";
		$data['get_exist_year'] = $this->rkap->get_exist_year_master();

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );

		$data['breadcrumb']    = $breadcrumb;
		$this->template->load('main', $data['template_page'], $data);

	}

}

/* End of file Rkap_ctl.php */
/* Location: ./application/controllers/Rkap_ctl.php */