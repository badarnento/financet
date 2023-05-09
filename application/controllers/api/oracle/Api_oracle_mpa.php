<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_mpa extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("oracle/M_oracle_mpa");
    }
        
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
	
	$dataMpa = $this->M_oracle_mpa->get_oracle_mpa()->result_array();
        $this->response( array('status' => true,'message' => 'success' , "datas" => $dataMpa  ), REST_Controller::HTTP_OK);

    }

    publIc function index_post()
    {	
        $result = array("status" => true,"message" => "", "datas" => "");
        $ORACLE_ID 		= $this->post("oracle_id");
        $MPA_NUMBER 		= $this->post("mpa_number");
        $MPA_LINE    		= $this->post("mpa_line");
	$ITEM_CODE		= $this->post("item_code");
	$ITEM_DESCRIPTION	= $this->post("item_description");
	$PRIMARY_UOM		= $this->post("primary_uom");
	$UNIT_PRICE		= $this->post("unit_price");
	$CURRENCY		= $this->post("currency");
	$PERIOD_FORM		= $this->post("period_form");
	$PERIOD_TO		= $this->post("period_to");
	$SUPPLIER_NAME		= $this->post("supplier_name");
	$SUPPLIER_SITE		= $this->post("supplier_site");

        if($ORACLE_ID == "" || $ITEM_CODE == ""  || $ITEM_DESCRIPTION == "" || $PRIMARY_UOM == ""  ){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataMpa = $this->M_oracle_mpa->get_oracle_mpa_where(array("ORACLE_ID"=>$ORACLE_ID))->result_array();
            if(sizeof($dataMpa) > 0){
                $dataUpdate = array(
        		"MPA_NUMBER" 		=> $MPA_NUMBER,
        		"MPA_LINE"    		=> $MPA_LINE,
			"ITEM_CODE"		=> $ITEM_CODE,
			"ITEM_DESCRIPTION"	=> htmlspecialchars_decode($ITEM_DESCRIPTION),
			"PRIMARY_UOM"		=> $PRIMARY_UOM,
			"UNIT_PRICE"		=> $UNIT_PRICE,
			"CURRENCY"		=> $CURRENCY,
			"PERIOD_FORM"		=> $PERIOD_FORM,
			"PERIOD_TO"		=> $PERIOD_TO,
			"SUPPLIER_NAME"		=> $SUPPLIER_NAME,
			"SUPPLIER_SITE"		=> $SUPPLIER_SITE,
                    	"AT_UPDATE" => $this->getCurenttimeMilis()
                );
                $update = $this->M_oracle_mpa->update_oracle_mpa(array("ORACLE_ID"=>$ORACLE_ID),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $dataInsert = array(
			"ORACLE_ID" 		=> $ORACLE_ID,
                    	"MPA_NUMBER" 		=> $MPA_NUMBER,
        		"MPA_LINE"    		=> $MPA_LINE,
			"ITEM_CODE"		=> $ITEM_CODE,
			"ITEM_DESCRIPTION"	=> htmlspecialchars_decode($ITEM_DESCRIPTION),
			"PRIMARY_UOM"		=> $PRIMARY_UOM,
			"UNIT_PRICE"		=> $UNIT_PRICE,
			"CURRENCY"		=> $CURRENCY,
			"PERIOD_FORM"		=> $PERIOD_FORM,
			"PERIOD_TO"		=> $PERIOD_TO,
			"SUPPLIER_NAME"		=> $SUPPLIER_NAME,
			"SUPPLIER_SITE"		=> $SUPPLIER_SITE,
                    	"AT_CREATE" => $this->getCurenttimeMilis(),
                   	 "AT_UPDATE" => $this->getCurenttimeMilis()
                );
                $insert = $this->M_oracle_mpa->create_oracle_mpa($dataInsert);
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
