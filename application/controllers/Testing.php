<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function index()
	{
		

		$curl = curl_init();
        curl_setopt_array($curl, array(
			        CURLOPT_URL =>  'https://jsonplaceholder.typicode.com/posts',
			        CURLOPT_RETURNTRANSFER => true,
			        CURLOPT_ENCODING => '',
			        CURLOPT_MAXREDIRS => 10,
			        CURLOPT_TIMEOUT => 0,
			        CURLOPT_FOLLOWLOCATION => true,
			        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			        CURLOPT_CUSTOMREQUEST => 'GET',
			        // CURLOPT_HTTPHEADER => $headers,
			 		// CURLOPT_POSTFIELDS => json_encode($fpjp)
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response );

        // echo "<pre>";

        foreach ($result as $key => $value) {

        	 // print_r($value);

        	 if($value->id % 2){
        	 	$arr_data[] = $value;
        	 }
        }

        echo json_encode($arr_data);
	}

}

/* End of file testing.php */
/* Location: ./application/controllers/testing.php */