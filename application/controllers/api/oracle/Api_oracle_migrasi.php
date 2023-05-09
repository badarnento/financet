<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_oracle_migrasi extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    //GET ATTACHMENT FPJP
    public function GET_FPJP_DATA($INTERFACE_STATUS,$NO=NULL){
        $REPORT_MIGRASI  = array();
        if($NO == NULL){
            $dataLoop = array();
            $SQL = "SELECT 
                REPORT_MIGRASI_LOG.*, 
                FPJP_HEADER.FPJP_NUMBER,
                FPJP_HEADER.DOCUMENT_UPLOAD AS FILE_NAME,
                FPJP_HEADER.DOCUMENT_ATTACHMENT AS FILE_CONTENTS_ATTACHMENT,  
                FPJP_HEADER.DOCUMENT_UPLOAD AS FILE_CONTENTS_UPLOAD, 
                FPJP_HEADER.FPJP_HEADER_ID 
            FROM 
                `REPORT_MIGRASI_LOG` 
            INNER JOIN 
                FPJP_HEADER 
            ON  
                FPJP_HEADER.FPJP_HEADER_ID = REPORT_MIGRASI_LOG.TRANSAKSI_ID WHERE REPORT_MIGRASI_LOG.TYPE = ? AND REPORT_MIGRASI_LOG.INTERFACE_STATUS = ?";
            $REPORT_MIGRASI  =  $this->db->query($SQL,array("FPJP",$INTERFACE_STATUS))->result_array();
        }

        if($NO != NULL){
            $dataLoop = array();
            $SQL = "SELECT 
                REPORT_MIGRASI_LOG.*, 
                FPJP_HEADER.FPJP_NUMBER,
                FPJP_HEADER.DOCUMENT_UPLOAD AS FILE_NAME,
                FPJP_HEADER.DOCUMENT_ATTACHMENT AS FILE_CONTENTS_ATTACHMENT,  
                FPJP_HEADER.DOCUMENT_UPLOAD AS FILE_CONTENTS_UPLOAD, 
                FPJP_HEADER.FPJP_HEADER_ID 
            FROM 
                `REPORT_MIGRASI_LOG` 
            INNER JOIN 
                FPJP_HEADER 
            ON  
                FPJP_HEADER.FPJP_HEADER_ID = REPORT_MIGRASI_LOG.TRANSAKSI_ID WHERE REPORT_MIGRASI_LOG.TYPE = ? AND REPORT_MIGRASI_LOG.NOMOR_TRANSAKSI = ?";
            $REPORT_MIGRASI  =  $this->db->query($SQL,array("FPJP",$NO))->result_array();
        }

	    foreach($REPORT_MIGRASI as $key => $values){
           
            $dataLoop[$values["ID"]]                      = array();
            $dataLoop[$values["ID"]]["ID"]                = $values["ID"];
            $dataLoop[$values["ID"]]["NOMOR_TRANSAKSI"]   = $values["FPJP_NUMBER"];
            $dataLoop[$values["ID"]]["TRANSAKSI_ID"]      = $values["TRANSAKSI_ID"];
            $dataLoop[$values["ID"]]["ORACLE_ID"]         = $values["ORACLE_ID"];
            $dataLoop[$values["ID"]]["AT_CREATE"]         = $values["AT_CREATE"];
            $dataLoop[$values["ID"]]["AT_UPDATE"]         = $values["AT_UPDATE"];
            $dataLoop[$values["ID"]]["INTERFACE_STATUS"]  = $values["INTERFACE_STATUS"];
            $dataLoop[$values["ID"]]["ERROR_MESSAGE"]     = $values["ERROR_MESSAGE"];
            $dataLoop[$values["ID"]]["ATTACHMENT"]        = array();
            
            if($values["FILE_CONTENTS_UPLOAD"] != "" && $values["FILE_CONTENTS_UPLOAD"] != null ){
                $pdf_base64 = "./uploads/fpjp_attachment/".$values["FILE_CONTENTS_UPLOAD"];
                if (file_exists($pdf_base64)) {
                  $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                  
                  array_push($dataLoop[$values["ID"]]["ATTACHMENT"], array(
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
            if (file_exists($file_name)) {
                if(file_put_contents( $file_name,file_get_contents($url))) {
                    $pdf_base64_generate = $file_name;
                    $b64Doc_generate = chunk_split(base64_encode(file_get_contents($pdf_base64_generate)));
                    array_push($dataLoop[$values["ID"]]["ATTACHMENT"], array(
                        "TYPE" => "FILE",
                        "FILE_NAME" =>  "GENERATE_".$id.".pdf",
                        "FILE_CONTENTS_ATTACHMENT" => "/oracle/api_oracle_fpjp/getdocgenerate?id=".$id."&fintools_token=" ,
                        "FILE_CONTENTS_UPLOAD" => "GENERATE_".$id.".pdf"
                    ));
                }
            }
        }

        $data_result = array();
        $data_last = array();
        foreach($dataLoop as $key => $values){
            array_push($data_result,$values);
            $data_last = $values;
        }
        return $NO == NULL ? $data_result : $data_last;
    }

    //GET ATTACHMENT PR
    public function GET_PR_DATA($INTERFACE_STATUS,$NO=NULL){
        $REPORT_MIGRASI  = array();
        if($NO == NULL){
            $dataLoop = array();
            $SQL = "SELECT 
                REPORT_MIGRASI_LOG.*, 
                PR_HEADER.PR_NUMBER , 
                PR_HEADER.DOCUMENT_UPLOAD AS FILE_NAME, 
                PR_HEADER.DOCUMENT_ATTACHMENT AS FILE_CONTENTS_ATTACHMENT,
                PR_HEADER.DOCUMENT_UPLOAD AS FILE_CONTENTS_UPLOAD,
                PR_HEADER.PR_HEADER_ID 
            FROM   
                `REPORT_MIGRASI_LOG` 
            INNER JOIN 
                PR_HEADER 
            ON  
                PR_HEADER.PR_HEADER_ID = REPORT_MIGRASI_LOG.TRANSAKSI_ID WHERE REPORT_MIGRASI_LOG.TYPE = ? AND REPORT_MIGRASI_LOG.INTERFACE_STATUS = ?";
            $REPORT_MIGRASI  =  $this->db->query($SQL,array("PR",$INTERFACE_STATUS))->result_array();
        }

        if($NO != NULL){
            $dataLoop = array();
            $SQL = "SELECT 
                REPORT_MIGRASI_LOG.*, 
                PR_HEADER.PR_NUMBER , 
                PR_HEADER.DOCUMENT_UPLOAD AS FILE_NAME, 
                PR_HEADER.DOCUMENT_ATTACHMENT AS FILE_CONTENTS_ATTACHMENT,
                PR_HEADER.DOCUMENT_UPLOAD AS FILE_CONTENTS_UPLOAD,
                PR_HEADER.PR_HEADER_ID 
            FROM   
                `REPORT_MIGRASI_LOG` 
            INNER JOIN 
                PR_HEADER 
            ON  
                PR_HEADER.PR_HEADER_ID = REPORT_MIGRASI_LOG.TRANSAKSI_ID WHERE REPORT_MIGRASI_LOG.TYPE = ? AND REPORT_MIGRASI_LOG.NOMOR_TRANSAKSI = ?";
            $REPORT_MIGRASI  =  $this->db->query($SQL,array("PR",$NO))->result_array();
        }



	    foreach($REPORT_MIGRASI as $key => $values){
            
            $dataLoop[$values["ID"]] = array();
            $dataLoop[$values["ID"]]["ID"]                = $values["ID"];
            $dataLoop[$values["ID"]]["NOMOR_TRANSAKSI"]   = $values["PR_NUMBER"];
            $dataLoop[$values["ID"]]["TRANSAKSI_ID"]      = $values["TRANSAKSI_ID"];
            $dataLoop[$values["ID"]]["ORACLE_ID"]         = $values["ORACLE_ID"];
            $dataLoop[$values["ID"]]["AT_CREATE"]         = $values["AT_CREATE"];
            $dataLoop[$values["ID"]]["AT_UPDATE"]         = $values["AT_UPDATE"];
            $dataLoop[$values["ID"]]["INTERFACE_STATUS"]  = $values["INTERFACE_STATUS"];
            $dataLoop[$values["ID"]]["ERROR_MESSAGE"]     = $values["ERROR_MESSAGE"];
            $dataLoop[$values["ID"]]["ATTACHMENT"]        = array();

            //DOCUMENT PR UPLOADED
            if($values["FILE_CONTENTS_UPLOAD"] != "" && $values["FILE_CONTENTS_UPLOAD"] != null ){
                $pdf_base64 = "./uploads/pr_attachment/".$values["FILE_CONTENTS_UPLOAD"];
                if (file_exists($pdf_base64)) {
                    $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                    array_push($dataLoop[$values["ID"]]["ATTACHMENT"], array(
                        "TYPE" => 'FILE',
                        "FILE_NAME" => $values["FILE_CONTENTS_UPLOAD"] ,
                        "FILE_CONTENTS_ATTACHMENT" => "/oracle/api_oracle_pr/getdocument/?id=".encrypt_string($values["PR_HEADER_ID"],true)."&fintools_token=",
                        "FILE_CONTENTS_UPLOAD" => $values["FILE_CONTENTS_UPLOAD"]
                    ));
                }
            }	
            
            //DOCUMENT PR GENERATE
            $id = encrypt_string($values["PR_HEADER_ID"],true);
            $url = base_url().'api/link/print-pr/'.$id ;
            $file_name = "./uploads/pr_attachment/GENERATE_".$id.".pdf";// basename($url);
            if (file_exists($file_name)) {
                if (file_exists($file_name)) {
                    if(file_put_contents($file_name,file_get_contents($url))) {
                        $pdf_generate = $file_name;
                            $b64Doc_generate = chunk_split(base64_encode(file_get_contents($pdf_generate)));
                        array_push($dataLoop[$values["ID"]]["ATTACHMENT"],array(
                            "TYPE" => "FILE" ,
                            "FILE_NAME"=>"DOC_PR_GENERATE.pdf" , 
                            "FILE_CONTENTS_ATTACHMENT"=> "/oracle/api_oracle_pr/getdocgenerate/?id=" .$id."&fintools_token=", 
                            "FILE_CONTENTS_UPLOAD"=>"GENERATE_".$id.".pdf"
                        ));	
                    }
                }
            }
        }

        $data_result = array();
        $data_last = array();
        foreach($dataLoop as $key => $values){
            array_push($data_result,$values);
            $data_last = $values;
        }
        return $NO == NULL ? $data_result : $data_last;
    }

    public function GET_JUSTIF_DATA($INTERFACE_STATUS,$NO=null){
        $REPORT_MIGRASI  = array();
        if($NO == NULL){
            $dataLoop = array();
            $SQL = "SELECT 
                REPORT_MIGRASI_LOG.*, 
                FS_BUDGET.FS_NUMBER , 
                FS_BUDGET.DOCUMENT_ATTACHMENT AS FILE_NAME, 
                FS_BUDGET.DOCUMENT_ATTACHMENT AS FILE_CONTENTS_ATTACHMENT,
                FS_BUDGET.ID_FS 
            FROM   
                `REPORT_MIGRASI_LOG` 
            INNER JOIN 
                FS_BUDGET 
            ON  
                FS_BUDGET.ID_FS = REPORT_MIGRASI_LOG.TRANSAKSI_ID WHERE REPORT_MIGRASI_LOG.TYPE = ? AND REPORT_MIGRASI_LOG.INTERFACE_STATUS = ?";
            $REPORT_MIGRASI  =  $this->db->query($SQL,array("JUSTIF",$INTERFACE_STATUS))->result_array();
        }

        if($NO != NULL){
            $dataLoop = array();
            $SQL = "SELECT 
                REPORT_MIGRASI_LOG.*, 
                FS_BUDGET.FS_NUMBER , 
                FS_BUDGET.DOCUMENT_ATTACHMENT AS FILE_NAME, 
                FS_BUDGET.DOCUMENT_ATTACHMENT AS FILE_CONTENTS_ATTACHMENT,
                FS_BUDGET.ID_FS 
            FROM   
                `REPORT_MIGRASI_LOG` 
            INNER JOIN 
                FS_BUDGET 
            ON  
                FS_BUDGET.ID_FS = REPORT_MIGRASI_LOG.TRANSAKSI_ID WHERE REPORT_MIGRASI_LOG.TYPE = ? AND REPORT_MIGRASI_LOG.NOMOR_TRANSAKSI = ?";
            $REPORT_MIGRASI  =  $this->db->query($SQL,array("JUSTIF",$NO))->result_array();
        }

	    foreach($REPORT_MIGRASI as $key => $values){
            
            $dataLoop[$values["ID"]] = array();
            $dataLoop[$values["ID"]]["ID"]                = $values["ID"];
            $dataLoop[$values["ID"]]["NOMOR_TRANSAKSI"]   = $values["FS_NUMBER"];
            $dataLoop[$values["ID"]]["TRANSAKSI_ID"]      = $values["ID_FS"];
            $dataLoop[$values["ID"]]["ORACLE_ID"]         = $values["ORACLE_ID"];
            $dataLoop[$values["ID"]]["AT_CREATE"]         = $values["AT_CREATE"];
            $dataLoop[$values["ID"]]["AT_UPDATE"]         = $values["AT_UPDATE"];
            $dataLoop[$values["ID"]]["INTERFACE_STATUS"]  = $values["INTERFACE_STATUS"];
            $dataLoop[$values["ID"]]["ERROR_MESSAGE"]     = $values["ERROR_MESSAGE"];
            $dataLoop[$values["ID"]]["ATTACHMENT"]        = array();

            //DOCUMENT PR UPLOADED
            if($values["FILE_CONTENTS_ATTACHMENT"] != "" && $values["FILE_CONTENTS_ATTACHMENT"] != null ){
                $pdf_base64 = "./uploads/".$values["FILE_CONTENTS_ATTACHMENT"];
                if (file_exists($pdf_base64)) {
                    $b64Doc = chunk_split(base64_encode(file_get_contents($pdf_base64)));
                    array_push($dataLoop[$values["ID"]]["ATTACHMENT"], array(
                        "TYPE" => 'FILE',
                        "FILE_NAME" => $values["FILE_CONTENTS_ATTACHMENT"] ,
                        "FILE_CONTENTS_ATTACHMENT" => "/oracle/Api_oracle_justifikasi/getdocument/?id=".encrypt_string($values["ID_FS"],true)."&fintools_token=",
                        "FILE_CONTENTS_UPLOAD" => $values["FILE_CONTENTS_ATTACHMENT"]
                    ));
                }
            }
        }

        $data_result = array();
        $data_last = array();
        foreach($dataLoop as $key => $values){
            array_push($data_result,$values);
            $data_last = $values;
        }
        return $NO == NULL ? $data_result : $data_last;
    }

    public function views_migrasi_get()
    {   
        $TYPE_DATA           = $this->get("TYPE_DATA");//'APPROVED';
        $INTERFACE_STATUS    = $this->get("INTERFACE_STATUS");//'NEW';
        
        switch($TYPE_DATA){
            case "FPJP" :
                $this->response( array('status' => true,'message' => 'success' , "datas" => $this->GET_FPJP_DATA($INTERFACE_STATUS) ), REST_Controller::HTTP_OK);
                break;
            case "PR" :
                $this->response( array('status' => true,'message' => 'success' , "datas" => $this->GET_PR_DATA($INTERFACE_STATUS) ), REST_Controller::HTTP_OK);
                break;
            case "JUSTIF" :
                $this->response( array('status' => true,'message' => 'success' , "datas" => $this->GET_JUSTIF_DATA($INTERFACE_STATUS) ), REST_Controller::HTTP_OK);
                break;
            default:
                $this->response( array('STATUS' => true,'message' => 'ERROR, NOT FOUND TYPE TRANSAKSI !' , "datas" => array() ), REST_Controller::HTTP_OK);
                break;
        }
     
    }


    public function view_by_number_get()
    {
        $TYPE_DATA           = $this->get("TYPE_DATA");//'APPROVED';
        $NOMOR_TRANSAKSI     = $this->get("NOMOR_TRANSAKSI");//'APPROVED';
        
        switch($TYPE_DATA){
            case "FPJP" :
                $this->response( array('status' => true,'message' => 'success' , "datas" => $this->GET_FPJP_DATA("NEW",$NOMOR_TRANSAKSI) ), REST_Controller::HTTP_OK);
                break;
            case "PR" :
                $this->response( array('status' => true,'message' => 'success' , "datas" => $this->GET_PR_DATA("NEW",$NOMOR_TRANSAKSI) ), REST_Controller::HTTP_OK);
                break;
            case "JUSTIF" :
                $this->response( array('status' => true,'message' => 'success' , "datas" => $this->GET_JUSTIF_DATA("NEW",$NOMOR_TRANSAKSI) ), REST_Controller::HTTP_OK);
                break;
            default:
                $this->response( array('STATUS' => true,'message' => 'ERROR, NOT FOUND TYPE TRANSAKSI !' , "datas" => array() ), REST_Controller::HTTP_OK);
                break;
        }
    }

    function update_migrasi_fpjp_post()
    {
        $result = array("status" => true,"message" => "", "datas" => "");

	    $FPJP_HEADER_ID	    = $this->post("FPJP_HEADER_ID");
        $ORACLE_ID          = $this->post("ORACLE_ID");
        $ERROR_MESSAGE      = $this->post("ERROR_MESSAGE");
        $INTERFACE_STATUS   = $this->post("INTERFACE_STATUS");

        if($FPJP_HEADER_ID == "" || $INTERFACE_STATUS == "" ){
            $result["status"] = false;
            $result["message"] = "Field Header Id is required !";
        }else{ 
            $FOUND_HEADER_ID  =$this->db->query('SELECT TRANSAKSI_ID, TYPE FROM REPORT_MIGRASI_LOG WHERE TRANSAKSI_ID = ? AND TYPE  = ? ', array($FPJP_HEADER_ID,"FPJP"))->num_rows();
            if($FOUND_HEADER_ID > 0){
                $data_update = array(
                    "ORACLE_ID" => $ORACLE_ID  == "" ? NULL : $ORACLE_ID, 
                    "INTERFACE_STATUS"  => $INTERFACE_STATUS  == "" ? NULL : $INTERFACE_STATUS,
                    "ERROR_MESSAGE" =>  $ERROR_MESSAGE  == "" ? NULL : $ERROR_MESSAGE
                );
                $this->db->update('REPORT_MIGRASI_LOG', $data_update, array("TRANSAKSI_ID" => $FPJP_HEADER_ID,"TYPE" =>"FPJP"));
                $result["status"] = true;
                $result["message"] = "success !";
            }else{
                $result["status"] = false;
                $result["message"] = "error, can't found fpjp !";
            }
        }
        
        $this->response( $result, REST_Controller::HTTP_OK);
    }

    function update_migrasi_pr_post()
    {
        $result = array("status" => true,"message" => "", "datas" => "");

	    $PR_HEADER_ID	    = $this->post("PR_HEADER_ID");
        $ORACLE_ID          = $this->post("ORACLE_ID"); 
        $ERROR_MESSAGE      = $this->post("ERROR_MESSAGE");
        $INTERFACE_STATUS   = $this->post("INTERFACE_STATUS");

        if($PR_HEADER_ID == "" || $INTERFACE_STATUS == "" ){
            $result["status"] = false;
            $result["message"] = "Field Header Id is required !";
        }else{ 
            $FOUND_HEADER_ID  =$this->db->query('SELECT TRANSAKSI_ID, TYPE FROM REPORT_MIGRASI_LOG WHERE TRANSAKSI_ID = ? AND TYPE  = ? ', array($PR_HEADER_ID,"PR"))->num_rows();
            if($FOUND_HEADER_ID > 0){
                $data_update = array(
                    "ORACLE_ID" => $ORACLE_ID  == "" ? NULL : $ORACLE_ID, 
                    "INTERFACE_STATUS"  => $INTERFACE_STATUS == "" ? NULL : $INTERFACE_STATUS,
                    "ERROR_MESSAGE" =>  $ERROR_MESSAGE == "" ? NULL : $ERROR_MESSAGE
                );
                $this->db->update('REPORT_MIGRASI_LOG', $data_update, array("TRANSAKSI_ID" => $PR_HEADER_ID,"TYPE" =>"PR"));
                $result["status"] = true;
                $result["message"] = "success !";
            }else{
                $result["status"] = false;
                $result["message"] = "error, can't found PR !";
            }
        }
        
        $this->response( $result, REST_Controller::HTTP_OK);
    }

    function update_migrasi_justif_post()
    {
        $result = array("status" => true,"message" => "", "datas" => "");

	    $ID_FS	            = $this->post("ID_FS");
        $ORACLE_ID          = $this->post("ORACLE_ID"); 
        $ERROR_MESSAGE      = $this->post("ERROR_MESSAGE");
        $INTERFACE_STATUS   = $this->post("INTERFACE_STATUS");

        if($ID_FS == "" || $INTERFACE_STATUS == "" ){
            $result["status"] = false;
            $result["message"] = "Field Header Id is required !";
        }else{ 
            $FOUND_HEADER_ID  =$this->db->query('SELECT TRANSAKSI_ID, TYPE FROM REPORT_MIGRASI_LOG WHERE TRANSAKSI_ID = ? AND TYPE = ? ', array($ID_FS,"JUSTIF"))->num_rows();
            if($FOUND_HEADER_ID > 0){
                $data_update = array(
                    "ORACLE_ID" => $ORACLE_ID == "" ? NULL : $ORACLE_ID, 
                    "INTERFACE_STATUS"  => $INTERFACE_STATUS == "" ? NULL : $INTERFACE_STATUS,
                    "ERROR_MESSAGE" =>  $ERROR_MESSAGE == "" ? NULL : $ERROR_MESSAGE
                );
                $this->db->update('REPORT_MIGRASI_LOG', $data_update, array("TRANSAKSI_ID" => $ID_FS,"TYPE" =>"JUSTIF"));
                $result["status"] = true;
                $result["message"] = "success !";
            }else{
                $result["status"] = false;
                $result["message"] = "error, can't found JUSTIF !";
            }
        }
        
        $this->response( $result, REST_Controller::HTTP_OK);
    }



}