<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_pr extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
      parent::__construct();
	    $this->load->model("oracle/M_oracle_pr");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
	    $fb_status			    =  $this->get("fb_status");//'APPROVED';
	    $fb_interface_status	= $this->get("fb_interface_status");//'NEW';
	    $coa_review			    = $this->get("coa_review");//'NEW';
	    $dataFPJP 			    = $this->M_oracle_pr->get_pr($fb_status,$fb_interface_status,$coa_review)->result_array();
	    
        $datas = array();
	    foreach($dataFPJP as $key => $values){

		    $data  = array();
		    foreach($values as $keyHeader => $headerValues){
			    $data[$keyHeader] = $headerValues;
                $dateVal = array("PR_DATE");
                if(in_array($keyHeader,$dateVal)){
                    if($headerValues != "" && $headerValues != null){
                        $dateSplit = explode(" ",$headerValues);
                        $data[$keyHeader] =  $dateSplit[0]; 
                    }
                }
            }
		
            if($data["FILE_CONTENTS_UPLOAD"] != "" && $data["FILE_CONTENTS_UPLOAD"] != null ){
                $pdf_base64 = "./uploads/pr_attachment/".$data["FILE_CONTENTS_UPLOAD"];
                        $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                $data["FILE_CONTENTS_ATTACHMENT"] = $b64Doc;
            }		
            array_push($datas ,$data);
 
        }
	

        $this->response( array('status' => true,'message' => 'success' , "datas" => $datas ), REST_Controller::HTTP_OK);
    }

    public function update_pr_post()
    {
        $result = array("status" => true,"message" => "", "datas" => "");
	    $PR_HEADER_ID	    = $this->post("pr_header_id");
        $PR_ID_ORACLE	    = $this->post("pr_id_oracle");
        $PR_RETURN_STATUS   = $this->post("pr_return_status");
        $IN_STATUS          = $this->post("intervace_status");
        $ERROR_MSG          = $this->post("error_message");

        if($PR_HEADER_ID == "" || $PR_ID_ORACLE == "" || $PR_RETURN_STATUS == "" || $IN_STATUS == "" || $ERROR_MSG == "" ){
            $result["status"] = false;
            $result["message"] = "all field requered !";
        }else{ 
            $dataCoa = $this->M_oracle_pr->get_pr_header_where(array("PR_HEADER_ID"=>$PR_HEADER_ID))->result_array();
            if(sizeof($dataCoa) > 0){
		        $dataUpdate = array(
                    "PR_ID_ORACLE" => $PR_ID_ORACLE, 
                    "PR_RETURN_STATUS" => $PR_RETURN_STATUS,
                    "INTERFACE_STATUS"  => $IN_STATUS,
                    "ERROR_MEASSAGE" =>  $ERROR_MSG
                );

                if(strtoupper($IN_STATUS) == "RETURNED" &&  strtoupper($PR_RETURN_STATUS) == "Y"){
                    $dataUpdate["STATUS"] = "returned"; 
                }	
                
                $update = $this->M_oracle_pr->update_pr(array("PR_HEADER_ID"=>$PR_HEADER_ID),$dataUpdate);
                if($update){
                    $result["status"] = true;
                    $result["message"] = "update data success !";
                }else{
                    $result["status"] = false;
                    $result["message"] = "update data error !";
                }
            }else{
                $result["status"] = false;
                $result["message"] = "error, can't found PR !";
            }
        }
        $this->response( $result, REST_Controller::HTTP_OK);
    }
   
    public function getpr_get()
    {
	    $fb_status			    = $this->get("fb_status");//'APPROVED';
	    $fb_interface_status	= $this->get("fb_interface_status");//'NEW';
	    $coa_review			    = $this->get("coa_review");//'NEW';
	    $dataFPJP 			    = $this->M_oracle_pr->get_pr($fb_status,$fb_interface_status,$coa_review)->result_array();
        

        $datas = array();
        $dataLoop = array();
        $no = array();
	    foreach($dataFPJP as $key => $values){

            if(!array_key_exists($values["attribute3"], $dataLoop) ){
                $no[$values["attribute3"]] = 1;

                $dataLoop[$values["attribute3"]] = array();
                $dataLoop[$values["attribute3"]]["justif_name"] = $values["justif_name"];
                $dateSplit = explode(" ",$values["PR_DATE"]);
                $dataLoop[$values["attribute3"]]["PR_DATE"] = $dateSplit[0];
                $dataLoop[$values["attribute3"]]["approver"] = $values["approver"];
                $dataLoop[$values["attribute3"]]["entered_by"] = $values["entered_by"];
                $dataLoop[$values["attribute3"]]["PO_BUYER"]   = $values["PO_BUYER"];
                $dataLoop[$values["attribute3"]]["attribute1"] = $values["attribute1"];
                $dataLoop[$values["attribute3"]]["attribute2"] = $values["attribute2"];
                $dataLoop[$values["attribute3"]]["attribute3"] = $values["attribute3"];
                $dataLoop[$values["attribute3"]]["attribute4"] = $values["attribute4"];
                $dataLoop[$values["attribute3"]]["attribute5"] = $values["attribute5"];
                $dataLoop[$values["attribute3"]]["attribute6"] = $values["attribute6"];
                $dataLoop[$values["attribute3"]]["attribute7"] = $values["attribute7"];
                $dataLoop[$values["attribute3"]]["description"] = $values["description"];
                $dataLoop[$values["attribute3"]]["DELIVERY_LOCATION"]  = $values["DELIVERY_LOCATION"];
                $dataLoop[$values["attribute3"]]["LINE"] = array();
                $dataLoop[$values["attribute3"]]["date"]        = $values["date"];
                $dataLoop[$values["attribute3"]]["pr_status"]   = $values["pr_status"];
                $dataLoop[$values["attribute3"]]["ATTACHMENT"]   = array();


                //DOCUMENT PR UPLOADED
                if($values["FILE_CONTENTS_UPLOAD"] != "" && $values["FILE_CONTENTS_UPLOAD"] != null ){
                    $pdf_base64 = "./uploads/pr_attachment/".$values["FILE_CONTENTS_UPLOAD"];
                    if (file_exists($pdf_base64)) {
                    	$b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                    	array_push($dataLoop[$values["attribute3"]]["ATTACHMENT"], array(
                        	"TYPE" => $values["TYPE"],
                        	"FILE_NAME" => $values["FILE_CONTENTS_UPLOAD"] ,
                        	"FILE_CONTENTS_ATTACHMENT" => "/oracle/api_oracle_pr/getdocument/?id=".encrypt_string($values["attribute3"],true)."&fintools_token=",
                       	 	"FILE_CONTENTS_UPLOAD" => $values["FILE_CONTENTS_UPLOAD"]
                    	));
                    }
                }	
                
                //DOCUMENT PR GENERATE
                $id = encrypt_string($values["attribute3"],true);
                $url = base_url().'api/link/print-pr/'.$id ;
                $file_name = "./uploads/pr_attachment/GENERATE_".$id.".pdf";// basename($url);
                if(file_put_contents($file_name,file_get_contents($url))) {
                    $pdf_generate = $file_name;
            		    $b64Doc_generate = chunk_split(base64_encode(file_get_contents($pdf_generate)));
                    array_push($dataLoop[$values["attribute3"]]["ATTACHMENT"],array(
                        "TYPE" => "FILE" ,
                        "FILE_NAME"=>"DOC_PR_GENERATE.pdf" , 
                        "FILE_CONTENTS_ATTACHMENT"=> "/oracle/api_oracle_pr/getdocgenerate/?id=" .$id."&fintools_token=", 
                        "FILE_CONTENTS_UPLOAD"=>"GENERATE_".$id.".pdf"
                    ));	
                }
            }

		    $data  = array();
            $data["item_desc"]       = $values["item_desc"];
            $data["item"]            = $values["item"];
            $data["LINES_TYPE"]      = $values["LINES_TYPE"];
            $data["QUANTITY"]        = $values["QUANTITY"];
            $data["CURRENCY"]        = $values["CURRENCY"];
            $data["PRICE"]           = $values["PRICE"];
            $data["conversion_rate"] = $values["conversion_rate"];
            $data["UOM"]             = $values["UOM"];
            $data["RKAP_PERIOD"]     = $values["RKAP_PERIOD"];
            $data["SEGMENT1"]        = $values["SEGMENT1"];
            $data["SEGMENT2"]        = $values["SEGMENT2"];
            $data["SEGMENT3"]        = $values["SEGMENT3"];
            $data["SEGMENT4"]        = $values["SEGMENT4"];
            $data["SEGMENT5"]        = $values["SEGMENT5"];
            $data["SEGMENT6"]        = $values["SEGMENT6"];
            $data["SEGMENT7"]        = $values["SEGMENT7"];
            $data["SEGMENT8"]        = $values["SEGMENT8"];
            $data["SEGMENT9"]        = $values["SEGMENT9"];
            $data["SEGMENT10"]       = $values["SEGMENT10"];
            $data["SEGMENT11"]       = $values["SEGMENT11"];

            array_push($dataLoop[$values["attribute3"]]["LINE"],$data);
            // $no[$values["attribute3"]] = $no[$values["attribute3"]] + 1;

        }

	      foreach($dataLoop as $keyLoop => $valueLoop){
          array_push($datas, $valueLoop);
        }
        $this->response( array('status' => true,'message' => 'success' , "datas" => $datas ), REST_Controller::HTTP_OK);


    }

    public function getdocument_get()
    {
      
      $id  = $this->get("id");
      $PR_HEADER_ID = decrypt_string($id,true);
      $dataFPJP 	= $this->M_oracle_pr->get_pr_header_where(array("PR_HEADER_ID" => $PR_HEADER_ID))->row();
      $dataDoc = array();
      $pdf_base64 = "./uploads/pr_attachment/".$dataFPJP->DOCUMENT_UPLOAD;
      if (file_exists($pdf_base64)) {
        $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
        $dataDoc = array(
            "TYPE" => "FILE",
            "FILE_NAME" => $dataFPJP->DOCUMENT_UPLOAD ,
            "FILE_CONTENTS_ATTACHMENT" => $b64Doc,
            "FILE_CONTENTS_UPLOAD" => $dataFPJP->DOCUMENT_UPLOAD
        );
      }
      $this->response( $dataDoc , REST_Controller::HTTP_OK);
    }

    public function getdocgenerate_get()
    {
      
      $id  = $this->get("id");
      //DOCUMENT PR GENERATE
      $url = base_url().'api/link/print-pr/'.$id ;
      $file_name = "./uploads/pr_attachment/GENERATE_".$id.".pdf";// basename($url);
      $dataDoc = array();
      if(file_put_contents($file_name,file_get_contents($url))) {
          $pdf_generate = $file_name;
          $b64Doc_generate = chunk_split(base64_encode(file_get_contents($pdf_generate)));
          $dataDoc = array(
            "TYPE" => "FILE" ,
            "FILE_NAME"=>"DOC_PR_GENERATE.pdf" , 
            "FILE_CONTENTS_ATTACHMENT"=> $b64Doc_generate , 
            "FILE_CONTENTS_UPLOAD"=>"GENERATE_".$id.".pdf"
          );	
      }
      $this->response( $dataDoc , REST_Controller::HTTP_OK);
    }


}
