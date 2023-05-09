<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encrypt extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_enc()
	{
		$string           = $this->input->post('string');

		if( is_array($string) ){
			$string = json_encode($string);
		}

		$result['status'] = ($string) ? true : false;
		$result['data']   = encrypt_string($string, true);
		
		echo json_encode($result);
	}

	public function get_dec()
	{
		$string = $this->input->post('string');

		$result['status'] = ($string) ? true : false;
		$result['data'] = decrypt_string($string, true);
		
		echo json_encode($result);
	}

}

/* End of file Encrypt.php */
/* Location: ./application/controllers/api/Encrypt.php */