<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_docs_ctl extends CI_Controller {

	public function index()
	{
		echo 'nothing';
	}

	public function vendor()
	{

		$data['title']    = "API Vendor";

		$this->load->view('api_vendor', $data);

	}

}

/* End of file Api_docs_ctl.php */
/* Location: ./application/controllers/api/Api_docs_ctl.php */