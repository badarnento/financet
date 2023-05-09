<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_fpjp extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("oracle/M_oracle_fpjp");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
        $fb_status           = $this->get("fb_status");//'APPROVED';
        $fb_interface_status = $this->get("fb_interface_status");//'NEW';
        $dataFPJP            = $this->M_oracle_fpjp->get_fpjp($fb_status,$fb_interface_status)->result_array();
	    
        $datas = array();
        $fpjp_doc_ava = array();
	    foreach($dataFPJP as $key => $values){

    		$data  = array();
    		foreach($values as $keyHeader => $headerValues){
    			$data[$keyHeader] = $headerValues;
            }
            $data["TYPE"] = "FILE";
		
    		if($data["FILE_CONTENTS_UPLOAD"] != "" && $data["FILE_CONTENTS_UPLOAD"] != null ){
    			$pdf_base64 = "./uploads/fpjp_attachment/".$data["FILE_CONTENTS_UPLOAD"];
                $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));

                $data["FILE_NAME"] = "DOC_FPJP_UPLOADED.pdf";
                $data["FILE_CONTENTS_ATTACHMENT"] = $b64Doc;
                $data["FILE_CONTENTS_UPLOAD"] = $data["FILE_CONTENTS_UPLOAD"];
    		}

    		$id = encrypt_string($data["FPJP_HEADER_ID"],true);
    		$url = base_url().'api/link/print-fpjp/'.$id ;
    		$file_name = "./uploads/fpjp_attachment/".$id.".pdf";// basename($url);
    		if (!array_key_exists($data["FPJP_HEADER_ID"],$fpjp_doc_ava)){
    			if(file_put_contents( $file_name,file_get_contents($url))) {
    				$pdf_base64 = $file_name;
                	$b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
    				$fpjp_doc_ava[$data["FPJP_HEADER_ID"]] = array("TYPE" => "pdf" ,"FILENAME"=>"DOC_FPJP_GENERATE.pdf" , "FILE_CONTENTS_ATTACHMENT"=> $b64Doc , "FILE_CONTENTS_UPLOAD"=>$id.".pdf");	
    			}
    		}
    		$data["FILE_NAME_FPJP"] = $fpjp_doc_ava[$data["FPJP_HEADER_ID"]]["FILENAME"];
            $data["FILE_CONTENTS_ATTACHMENT_FPJP"] = $fpjp_doc_ava[$data["FPJP_HEADER_ID"]]["FILE_CONTENTS_ATTACHMENT"];
           	$data["FILE_CONTENTS_UPLOAD_FPJP"] = $fpjp_doc_ava[$data["FPJP_HEADER_ID"]]["FILE_CONTENTS_UPLOAD"];
            $data["FPJP_AMOUNT"] = floor($data["FPJP_AMOUNT"]);

            array_push($datas ,$data);
 		
        }
	

        $this->response( array('status' => true,'message' => 'success' , "datas" => $datas ), REST_Controller::HTTP_OK);
    }


    public function update_fpjp_post()
    {
        $result = array("status" => true,"message" => "", "datas" => "");
	    $FPJP_HEADER_ID	    = $this->post("fpjp_header_id");
        $INVOICE_ID         = $this->post("invoice_id");
        $RETURN_FLAG        = $this->post("return_flag");
        $IN_STATUS          = $this->post("intervace_status");
        $ERROR_MSG          = $this->post("error_message");

        if($FPJP_HEADER_ID == "" || $INVOICE_ID == "" || $RETURN_FLAG == "" || $IN_STATUS == "" || $ERROR_MSG == "" ){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataCoa = $this->M_oracle_fpjp->get_fpjp_header_where(array("FPJP_HEADER_ID"=>$FPJP_HEADER_ID))->result_array();
            if(sizeof($dataCoa) > 0){
                $dataUpdate = array(
                    "INVOICE_ID" => $INVOICE_ID, 
                    "RETURN_FLAG" => $RETURN_FLAG,
                    "INTERFACE_STATUS"  => $IN_STATUS,
                    "ERROR_MEASSAGE" =>  $ERROR_MSG
                );
                if(strtoupper($IN_STATUS) == "RETURNED" &&  strtoupper($RETURN_FLAG) == "Y"){
			        $dataUpdate["STATUS"] = "returned"; 
		        }	
                
                $update = $this->M_oracle_fpjp->update_fpjp(array("FPJP_HEADER_ID"=>$FPJP_HEADER_ID),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $result["status"] = false;
                $result["message"] = "error, can't found fpjp !";
            }
        }
        $this->response( $result, REST_Controller::HTTP_OK);
    }
    
    public function getfpjp_get()
    {
        $fb_status           =  $this->get("fb_status");//'APPROVED';
        $fb_interface_status = $this->get("fb_interface_status");//'NEW';
        $dataFPJP            = $this->M_oracle_fpjp->get_fpjp($fb_status,$fb_interface_status)->result_array();
	    
        $datas = array();
        $dataLoop = array();

        $no = array();
	    foreach($dataFPJP as $key => $values){

            if(!array_key_exists($values["FPJP_HEADER_ID"], $dataLoop) ){
                $no[$values["FPJP_HEADER_ID"]] = 1;

                $dataLoop[$values["FPJP_HEADER_ID"]]                          = array();
                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_HEADER_ID"]        = $values["FPJP_HEADER_ID"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["NO_INVOICE"]            = ($values["NO_INVOICE"]) ? $values["NO_INVOICE"] : NULL;
                $dataLoop[$values["FPJP_HEADER_ID"]]["CURRENCY"]              = $values["CURRENCY"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["CURRENCY_RATE"]         = $values["CURRENCY_RATE"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_AMOUNT"]           = floor($values["FPJP_AMOUNT"]);
                $dataLoop[$values["FPJP_HEADER_ID"]]["INVOICE_DATE"]          = $values["INVOICE_DATE"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["SUPPLIER"]              = $values["SUPPLIER"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["SUPPLIER_SITE"]         = $values["SUPPLIER_SITE"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["DESCRIPTION"]           = $values["DESCRIPTION"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["ACCOUNTING_DATE"]       = $values["ACCOUNTING_DATE"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_NUMBER"]           = $values["FPJP_NUMBER"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_NAME"]             = $values["FPJP_NAME"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["JUSTIF_NUMBER"]         = $values["JUSTIF_NUMBER"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_TYPE"]             = $values["FPJP_TYPE"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["SUPPLIER_BANK_ACCOUNT"] = $values["SUPPLIER_BANK_ACCOUNT"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["SUPPLIER_BANK_NAME"]    = $values["SUPPLIER_BANK_NAME"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["SUPPLIER_REKENING"]     = $values["SUPPLIER_REKENING"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_NOTE"]             = $values["FPJP_NOTE"];

                $dataLoop[$values["FPJP_HEADER_ID"]]["FPJP_SUBMITED"]         = $values["FPJP_SUBMITED"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["INVOICE_ID"]            = $values["INVOICE_ID"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["RETURN_FLAG"]           = $values["RETURN_FLAG"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["INTERFACE_STATUS"]      = $values["INTERFACE_STATUS"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["ERROR_MEASSAGE"]        = $values["ERROR_MEASSAGE"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["COA_REVIEW"]            = $values["COA_REVIEW"];
                $dataLoop[$values["FPJP_HEADER_ID"]]["ONE_TIME_SUPPLIER_FLAG"]  = "N";
                
                $dataLoop[$values["FPJP_HEADER_ID"]]["LINE"] = array();
                $dataLoop[$values["FPJP_HEADER_ID"]]["ATTACHMENT"]   = array();

                
                if($values["FILE_CONTENTS_UPLOAD"] != "" && $values["FILE_CONTENTS_UPLOAD"] != null ){
                    $pdf_base64 = "./uploads/fpjp_attachment/".$values["FILE_CONTENTS_UPLOAD"];
                    if (file_exists($pdf_base64)) {
                      $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                      
                      array_push($dataLoop[$values["FPJP_HEADER_ID"]]["ATTACHMENT"], array(
                          "TYPE" => "FILE",
                          "FILE_NAME" => $values["FILE_CONTENTS_UPLOAD"],
                          "FILE_CONTENTS_ATTACHMENT" => "/oracle/api_oracle_fpjp/getdocument?id=".encrypt_string($values["FPJP_HEADER_ID"],true)."&fintools_token=",
                          "FILE_CONTENTS_UPLOAD" => $values["FILE_CONTENTS_UPLOAD"]
                      ));
                    }
                }
                
    
                $id = encrypt_string($values["FPJP_HEADER_ID"],true);
                $url = base_url().'api/link/print-fpjp/'.$id ;
                $file_name = "./uploads/fpjp_attachment/GENERATE_".$id.".pdf";// basename($url);
                if(file_put_contents( $file_name,file_get_contents($url))) {
                    $pdf_base64_generate = $file_name;
                    $b64Doc_generate = chunk_split(base64_encode(file_get_contents($pdf_base64_generate)));

                    array_push($dataLoop[$values["FPJP_HEADER_ID"]]["ATTACHMENT"], array(
                        "TYPE" => "FILE",
                        "FILE_NAME" =>  "GENERATE_".$id.".pdf",
                        "FILE_CONTENTS_ATTACHMENT" => "/oracle/api_oracle_fpjp/getdocgenerate?id=".$id."&fintools_token=" ,
                        "FILE_CONTENTS_UPLOAD" => "GENERATE_".$id.".pdf"
                    ));

                }
            }
            
            $data  = array();
            $data["LINE_NUMBER"]        = $no[$values["FPJP_HEADER_ID"]];//$values["LINE_NUMBER"];
            $data["INCLUDE_TAX"]        = $values["INCLUDE_TAX"];
            $data["FPJP_DETAIL_DESC"]   = $values["FPJP_DETAIL_DESC"];
            $data["FPJP_DETAIL_AMOUNT"] = $values["FPJP_DETAIL_AMOUNT"];
            $data["RKAP_PERIOD"]        = $values["RKAP_PERIOD"];
            $data["SEGMENT1"]           = $values["SEGMENT1"];
            $data["SEGMENT2"]           = $values["SEGMENT2"];
            $data["SEGMENT3"]           = $values["SEGMENT3"];
            $data["SEGMENT4"]           = $values["SEGMENT4"];
            $data["SEGMENT5"]           = $values["SEGMENT5"];
            $data["SEGMENT6"]           = $values["SEGMENT6"];
            $data["SEGMENT7"]           = $values["SEGMENT7"];
            $data["SEGMENT8"]           = $values["SEGMENT8"];
            $data["SEGMENT9"]           = $values["SEGMENT9"];
            $data["SEGMENT10"]          = $values["SEGMENT10"];
            $data["SEGMENT11"]          = $values["SEGMENT11"];
            $data["LINE_DESCRIPTION"]   = $values["LINE_DESCRIPTION"];
            array_push($dataLoop[$values["FPJP_HEADER_ID"]]["LINE"],$data);
            $no[$values["FPJP_HEADER_ID"]] = $no[$values["FPJP_HEADER_ID"]] +1;
 		
        }
        foreach($dataLoop as $keyLoop => $valueLoop){
            array_push($datas, $valueLoop);
        }
        $this->response( array('status' => true,'message' => 'success' , "datas" => $datas ), REST_Controller::HTTP_OK);
    }

    
    public function getdocument_get()
    {

        $id  = $this->get("id");
        $FPJP_HEADER_ID = decrypt_string($id,true);
        $dataFPJP 	= $this->M_oracle_fpjp->get_fpjp_header_where(array("FPJP_HEADER_ID" => $FPJP_HEADER_ID))->result_array();
        $dataDoc = array();
        foreach($dataFPJP as $key => $values){
            $pdf_base64 = "./uploads/fpjp_attachment/".$values["DOCUMENT_UPLOAD"];
            if (file_exists($pdf_base64)) {
                $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                $dataDoc = array(
                    "TYPE" => "FILE",
                    "FILE_NAME" => $values["DOCUMENT_UPLOAD"],
                    "FILE_CONTENTS_ATTACHMENT" => $b64Doc,
                    "FILE_CONTENTS_UPLOAD" => $values["DOCUMENT_UPLOAD"]
                );
            }
        }
        
        $this->response( $dataDoc , REST_Controller::HTTP_OK);
    }

    public function getdocgenerate_get()
    {
        $id  = $this->get("id");
        $url = base_url().'api/link/print-fpjp/'.$id ;
        $file_name = "./uploads/fpjp_attachment/GENERATE_".$id.".pdf";// basename($url);
        $dataDoc = array();
        if(file_put_contents( $file_name,file_get_contents($url))) {
            $pdf_base64_generate = $file_name;
            $b64Doc_generate = chunk_split(base64_encode(file_get_contents($pdf_base64_generate)));
            $dataDoc = array(
                "TYPE" => "FILE",
                "FILE_NAME" =>  "GENERATE_".$id.".pdf",
                "FILE_CONTENTS_ATTACHMENT" => $b64Doc_generate,
                "FILE_CONTENTS_UPLOAD" => "GENERATE_".$id.".pdf"
            );
        }
        $this->response( $dataDoc , REST_Controller::HTTP_OK);
    }

}
