<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_coa extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("oracle/M_oracle_coa");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
	$FLAG       = $this->get("flag");
      	$dataCoa = $this->M_oracle_coa->get_oracle_coa()->result_array();
	if($FLAG != "" && $FLAG  != null ){
		$dataCoa =  $this->M_oracle_coa->get_oracle_coa_where(array("FLAG" => $FLAG))->result_array();
	}

        $this->response( array('status' => true,'message' => 'success' , "datas" => $dataCoa ), REST_Controller::HTTP_OK);

    }

    publIc function index_post()
    {	
        $result = array("status" => true,"message" => "", "datas" => "");
	$CCID	    = $this->post("ccid");
        $FLEX_VALUE = $this->post("flex_value");
        $DESC_VALUE = $this->post("desc_value");
        $SEGMENT    = $this->post("segment");
        $FLAG       = $this->post("flag");
        if($FLEX_VALUE == "" || $DESC_VALUE == "" || $SEGMENT == "" || $CCID == "" || $FLAG == ""){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataCoa = $this->M_oracle_coa->get_oracle_coa_where(array("CCID"=>$CCID))->result_array();
            if(sizeof($dataCoa) > 0){
                $dataUpdate = array(
                    "FLEX_VALUE" => $FLEX_VALUE,
                    "VALUE_DESCRIPTION" => $DESC_VALUE,
                    "SEGMENT" => $SEGMENT,
                    "AT_UPDATE" => $this->getCurenttimeMilis(),
                    "FLAG" =>  $FLAG 

                );
                $update = $this->M_oracle_coa->update_oracle_coa(array("CCID"=>$CCID),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $dataInsert = array(
                    "CCID" => $CCID,
                    "FLEX_VALUE" => $FLEX_VALUE,
                    "VALUE_DESCRIPTION" => $DESC_VALUE,
                    "SEGMENT" => $SEGMENT,
                    "AT_CREATE" => $this->getCurenttimeMilis(),
                    "AT_UPDATE" => $this->getCurenttimeMilis(),
                    "FLAG" =>  $FLAG 
                );
                $insert = $this->M_oracle_coa->create_oracle_coa($dataInsert);
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
