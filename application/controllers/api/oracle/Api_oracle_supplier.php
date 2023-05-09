<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_supplier extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("oracle/M_oracle_supplier");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
	
	$dataSupplier = $this->M_oracle_supplier->get_oracle_supplier()->result_array();
        $this->response( array('status' => true,'message' => 'success' , "datas" => $dataSupplier  ), REST_Controller::HTTP_OK);

    }

    publIc function index_post()
    {	
        $result = array("status" => true,"message" => "", "datas" => "");
        $VENDOR_ID          = $this->post("vendor_id");
        $VENDOR_SITE_ID     = $this->post("vendor_site_id");
        $VENDOR_NAME        = $this->post("vendor_name");
	    $VENDOR_SITE_CODE   = $this->post("vendor_site_code");
	    $BANK_NAME	        = $this->post("bank_name");
        $ACCOUNT_OWNER	    = $this->post("account_owner");
	    
	    $CLEAR_BANK_ACCOUNT_NUMBER = $this->post("bank_account_number");

        if($VENDOR_ID == "" || $VENDOR_SITE_ID == "" || $VENDOR_NAME == "" || $VENDOR_SITE_CODE == "" ||  $ACCOUNT_OWNER == ""){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataSupplier = $this->M_oracle_supplier->get_oracle_supplier_where(array("VENDOR_SITE_ID" => $VENDOR_SITE_ID))->result_array();
            if(sizeof($dataSupplier) > 0){
                $dataUpdate = array(
                        "VENDOR_ID"			            => $VENDOR_ID, 
        		        "VENDOR_NAME"  			        => $VENDOR_NAME,
			            "VENDOR_SITE_CODE"		        => $VENDOR_SITE_CODE,
			            "BANK_NAME"	  		            => $BANK_NAME,
			            "CLEAR_BANK_ACCOUNT_NUMBER" 	=> $CLEAR_BANK_ACCOUNT_NUMBER,
                        "ACCOUNT_OWNER"                 => $ACCOUNT_OWNER,
                    	"AT_UPDATE" 			        => $this->getCurenttimeMilis()
                );
                $update = $this->M_oracle_supplier->update_oracle_supplier(array("VENDOR_SITE_ID" => $VENDOR_SITE_ID),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $dataInsert = array(
                    "VENDOR_ID" 			        => $VENDOR_ID,
                    "VENDOR_SITE_ID"			    => $VENDOR_SITE_ID, 
        	        "VENDOR_NAME"  			        => $VENDOR_NAME,
		            "VENDOR_SITE_CODE"			    => $VENDOR_SITE_CODE,
		            "BANK_NAME"	  			        => $BANK_NAME,
                    "ACCOUNT_OWNER"                 => $ACCOUNT_OWNER,
		            "CLEAR_BANK_ACCOUNT_NUMBER" 	=> $CLEAR_BANK_ACCOUNT_NUMBER,
                    "AT_CREATE" => $this->getCurenttimeMilis(),
                    "AT_UPDATE" => $this->getCurenttimeMilis()
                );
                $insert = $this->M_oracle_supplier->create_oracle_supplier($dataInsert);
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
