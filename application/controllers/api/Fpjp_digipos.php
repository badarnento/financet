<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fpjp_digipos extends CI_Controller {

	const URL = 'https://digipos-api.linkaja.com/finance-endpoint/digipos-apis-action/';
	private $token = '';
	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		$get_fpjp = $this->db->query("SELECT DISTINCT NO_FPJP, PAYMENT_STATUS, GL_HEADER_ID FROM GL_HEADERS
														WHERE PAYMENT_STATUS IS NOT NULL AND DIGIPOS_FLAG IS NULL
														AND NO_FPJP LIKE 'BAT-%'")->result_array();

    	if($get_fpjp):

    		$this->_get_token();

    		$data_fpjp   = array();
			$data_paid   = array();
			$data_unpaid = array();

    		foreach ($get_fpjp as $key => $value):

				$payment_status = strtoupper($value['PAYMENT_STATUS']);
				$no_fpjp        = $value['NO_FPJP'];
				$gl_id          = $value['GL_HEADER_ID'];
				$flag           = false;

    			if($payment_status == 'RECONCILED'){
    				$data_paid[] = $no_fpjp;
    				$flag = true;
    			}

    			if($payment_status == 'FAILED'){
    				$data_unpaid[] = $no_fpjp;
    				$flag = true;
    			}

    			if($flag){
    				$data_fpjp[] = [ "DIGIPOS_FLAG" => "Y", "GL_HEADER_ID" => $gl_id];
    			}

    		endforeach;

    		if($data_paid):
    			$this->_call_api("paid-fpjp", $data_paid);
    		endif;

    		if($data_unpaid):
				$this->_call_api("unpaid-fpjp", $data_unpaid);
    		endif;

    		if($data_fpjp):
    			$this->crud->update_batch_data("GL_HEADERS", $data_fpjp, "GL_HEADER_ID");
    		endif;

    		return true;

    	else:
			log_message('info', "No data fpjp digipos to update");
			return true;
    	endif;

	}

	private function _get_token(){
		
		$auth = array( "email" => "oracle@mail.com", "password" => "user1234" );
		$url = self::URL . 'get-token';

		$curl = curl_init();
        curl_setopt_array($curl, array(
			        CURLOPT_URL =>  $url,
			        CURLOPT_RETURNTRANSFER => true,
			        CURLOPT_ENCODING => '',
			        CURLOPT_MAXREDIRS => 10,
			        CURLOPT_TIMEOUT => 0,
			        CURLOPT_FOLLOWLOCATION => true,
			        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			        CURLOPT_CUSTOMREQUEST => 'POST',
			        CURLOPT_HTTPHEADER => [  'Content-Type: application/json; charset=utf-8' ],
			 		CURLOPT_POSTFIELDS => json_encode($auth)
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response , true);

        if(isset($result['error'])){
			log_message('info',"Error api token ==> " . $result['error']['message']);
        	return false;
        }
        if($result['status'] == true){
        	$this->token =  $result['token'];
        	return $result['status'];
        }
        else{
        	return false;
        }

	}


	private function _call_api($category, $fpjp_data){

		$url = self::URL . $category;
		$headers = array(
		   "Content-Type: application/json; charset=utf-8",
		   "Authorization: Bearer " . $this->token ,
		);
		$fpjp = array( "no_fpjp" => $fpjp_data );

		$curl = curl_init();
        curl_setopt_array($curl, array(
			        CURLOPT_URL =>  $url,
			        CURLOPT_RETURNTRANSFER => true,
			        CURLOPT_ENCODING => '',
			        CURLOPT_MAXREDIRS => 10,
			        CURLOPT_TIMEOUT => 0,
			        CURLOPT_FOLLOWLOCATION => true,
			        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			        CURLOPT_CUSTOMREQUEST => 'POST',
			        CURLOPT_HTTPHEADER => $headers,
			 		CURLOPT_POSTFIELDS => json_encode($fpjp)
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response , true);

        if(isset($result['error'])){
			log_message('info',"Error call API ==> " . $result['error']['message']);
        	return false;
        }
        if($result['success'] == true){
        	log_message('info', "FPJP-DIGIPOS successfully update " . $category . " (" . implode(",", $fpjp_data) . ")");
        }
        else{
        	log_message('info', "FPJP-DIGIPOS failed update " . $category . " (" . implode(",", $fpjp_data) . ") ==> " . $result['message']);
        }

    	return true;

	}

}

/* End of file Fpjp_digipos.php */
/* Location: ./application/controllers/api/Fpjp_digipos.php */