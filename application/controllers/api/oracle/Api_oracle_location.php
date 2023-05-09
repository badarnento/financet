<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_location extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("oracle/M_oracle_location");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
	
	$dataLocation = $this->M_oracle_location->get_oracle_location()->result_array();
        $this->response( array('status' => true,'message' => 'success' , "datas" => $dataLocation  ), REST_Controller::HTTP_OK);

    }

    publIc function index_post()
    {	
        $result = array("status" => true,"message" => "", "datas" => "");
        $LOCATION_ID = $this->post("location_id");
        $LOCATION_CODE = $this->post("location_code");
        $SEGMENT    = $this->post("segment");
        if($LOCATION_ID == "" || $LOCATION_CODE == ""){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataLocation = $this->M_oracle_location->get_oracle_location_where(array("LOCATION_ID"=>$LOCATION_ID))->result_array();
            if(sizeof($dataLocation) > 0){
                $dataUpdate = array(
                    "LOCATION_CODE" => $LOCATION_CODE,
                    "AT_UPDATE" => $this->getCurenttimeMilis()
                );
                $update = $this->M_oracle_location->update_oracle_location(array("LOCATION_ID"=>$LOCATION_ID),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $dataInsert = array(
                    "LOCATION_ID" => $LOCATION_ID,
                    "LOCATION_CODE" => $LOCATION_CODE,
                    "AT_CREATE" => $this->getCurenttimeMilis(),
                    "AT_UPDATE" => $this->getCurenttimeMilis()
                );
                $insert = $this->M_oracle_location->create_oracle_location($dataInsert);
                if($insert ){
                    $result["status"] = true;
                    $result["message"] = "insert data success!";
                }else{
                    $result["status"] = false;
                    $result["message"] = "insert data error !";
                }

            }
        }
        $this->response( $result, REST_Controller::HTTP_OK);
    }
}
