<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_justifikasi extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("oracle/M_oracle_justifikasi");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
	    $fb_status			    =  $this->get("fb_status");//'APPROVED';
	    $fb_interface_status	= $this->get("fb_interface_status");//'NEW';
	    $fb_ucm			        = $this->get("ucm_id");//'NEW';
	
	    $dataJustifikasi = $this->M_oracle_justifikasi->get_justifikasi($fb_status,$fb_interface_status,$fb_ucm)->result_array();
        
        $datas = array();
	    foreach($dataJustifikasi as $key => $values){
		    $data  = array();
		    foreach($values as $keyHeader => $headerValues){
			    $data[$keyHeader] = $headerValues;
                $dateVal = array("SERVICE_PERIOD_START","SERVICE_PERIOD_END");
                if(in_array($keyHeader,$dateVal)){
                    if($headerValues != "" && $headerValues != null){
                        $dateSplit = explode(" ",$headerValues);
                        $data[$keyHeader] =  $dateSplit[0]; 
                    }
                }
            }	
            array_push($datas ,$data);
        }

        
        $this->response( array('status' => true,'message' => 'success' , "datas" => $datas), REST_Controller::HTTP_OK);
    }

    public function update_justifikasi_post()
    {
        $result = array("status" => true,"message" => "", "datas" => "");
	    $ID_FS	    = $this->post("id_fs");
        $JE_HEADER_ID = $this->post("je_header");
        $ERROR_MESSAGE = $this->post("err_message");
        $UCM_ID = $this->post("ucm_id");
        $FB_STATUS = $this->post("fb_interface_status");

        if($ID_FS == "" || $JE_HEADER_ID == "" || $ERROR_MESSAGE == "" || $UCM_ID == "" || $FB_STATUS == "" ){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataCoa = $this->M_oracle_justifikasi->get_fs_budget_where(array("ID_FS"=>$ID_FS))->result_array();
            if(sizeof($dataCoa) > 0){
                $dataUpdate = array(
                    "UCM_ID" => $UCM_ID, 
                    "JE_HEADER_ID" => $JE_HEADER_ID,
                    "ERROR_MESSAGE"  => $ERROR_MESSAGE,
                    "INTERFACE_STATUS" =>  $FB_STATUS
                );
                $update = $this->M_oracle_justifikasi->update_justifikasi(array("ID_FS"=>$ID_FS),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $result["status"] = false;
                $result["message"] = "error, can't found justifikasi !";
            }
        }
        $this->response( $result, REST_Controller::HTTP_OK);
    }
    

    
    public function getjustif_get()
    {
	    $fb_status			    =  $this->get("fb_status");//'APPROVED';
	    $fb_interface_status	= $this->get("fb_interface_status");//'NEW';
	    $fb_ucm			        = $this->get("ucm_id");//'NEW';
	
	    $dataJustifikasi = $this->M_oracle_justifikasi->get_justifikasi($fb_status,$fb_interface_status,$fb_ucm)->result_array();
        
        $datas = array();
        $dataLoop = array();
	    foreach($dataJustifikasi as $key => $values){
            if(!array_key_exists($values["JUSTIFICATION_ID"], $dataLoop) ){
                $dataLoop[$values["JUSTIFICATION_ID"]] = array();
                $dataLoop[$values["JUSTIFICATION_ID"]]["CURRENCY_CODE"]          = $values["CURRENCY_CODE"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["JOURNAL_ENTRY"]          = $values["JOURNAL_ENTRY"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["REFERENCE4"]             = $values["REFERENCE4"]; 
                $dataLoop[$values["JUSTIFICATION_ID"]]["REFERENCE5"]             = $values["REFERENCE5"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["JUSTIFICATION_ID"]       = $values["JUSTIFICATION_ID"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["PROC_TYPE"]              = $values["PROC_TYPE"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["INTERFACE_STATUS"]       = $values["INTERFACE_STATUS"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["CANCEL_FLAG"]            = $values["CANCEL_FLAG"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["UCM_ID"]                 = $values["UCM_ID"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["JE_HEADER_ID"]           = $values["JE_HEADER_ID"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["ERROR_MESSAGE"]          = $values["ERROR_MESSAGE"];
                $dataLoop[$values["JUSTIFICATION_ID"]]["LINE"]                   = array();
               
            }

		    $data                               = array();
            $data["JUSTIF_LINE_NUMBER"]         = $values["JUSTIF_LINE_NUMBER"];
            $data["SEGMENT1"]                   = $values["SEGMENT1"];
            $data["SEGMENT2"]                   = $values["SEGMENT2"];
            $data["SEGMENT3"]                   = $values["SEGMENT3"];
            $data["SEGMENT4"]                   = $values["SEGMENT4"];
            $data["SEGMENT5"]                   = $values["SEGMENT5"];
            $data["SEGMENT6"]                   = $values["SEGMENT6"];
            $data["SEGMENT7"]                   = $values["SEGMENT7"];
            $data["SEGMENT8"]                   = $values["SEGMENT8"];
            $data["SEGMENT9"]                   = $values["SEGMENT9"];
            $data["SEGMENT10"]                  = $values["SEGMENT10"];
            $data["SEGMENT11"]                  = $values["SEGMENT11"];

            $dateSplitStart = explode(" ",$values["SERVICE_PERIOD_START"]);
            $dateSplitEnd   = explode(" ",$values["SERVICE_PERIOD_END"]);

            $data["ENTERED_DEBIT_AMOUNT"]       = $values["ENTERED_DEBIT_AMOUNT"];
            $data["SERVICE_PERIOD_START"]       = $dateSplitStart[0];
            $data["SERVICE_PERIOD_END"]         = $dateSplitEnd[0];
            
            array_push($dataLoop[$values["JUSTIFICATION_ID"]]["LINE"],$data);

        }
        foreach($dataLoop as $keyLoop => $valueLoop){
            array_push($datas, $valueLoop);
        }
        
        $this->response( array('status' => true,'message' => 'success' , "datas" => $datas), REST_Controller::HTTP_OK);
    }

    public function getdocument_get()
    {
      
      $id  = $this->get("id");
      $ID_FS = decrypt_string($id,true);
      $dataFS 	= $this->M_oracle_justifikasi->get_fs_budget_where(array("ID_FS" => $ID_FS))->result_array();
      $dataDoc = array();
      foreach($dataFS as $key => $values){
          $pdf_base64 = "./uploads/".$values["DOCUMENT_ATTACHMENT"];
          if (file_exists($pdf_base64)) {
              $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
              $dataDoc = array(
                  "TYPE" => "FILE",
                  "FILE_NAME" => $values["DOCUMENT_ATTACHMENT"],
                  "FILE_CONTENTS_ATTACHMENT" => $b64Doc,
                  "FILE_CONTENTS_UPLOAD" => $values["DOCUMENT_ATTACHMENT"]
              );
          }
      }
      $this->response( $dataDoc , REST_Controller::HTTP_OK);
      
    }

}
