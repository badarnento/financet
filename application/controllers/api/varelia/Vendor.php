<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Vendor extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
	$this->load->model("varelia/M_Varelia");
    }
    
    
    function getCurenttimeMilis(){
        $milliseconds = round(microtime(true) * 1000);
        return $milliseconds;
    }

    public function index_get()
    {
      	$dataVarelia = $this->M_Varelia->get_vendor()->result_array();
        $this->response( array('status' => true,'message' => 'success' , "datas" => $dataVarelia), REST_Controller::HTTP_OK);

    }

    publIc function index_post()
    {	
        $result = array("status" => true,"message" => "", "datas" => array() );

        $KODE_PENDAFTRAN   = $this->post("KODE_PENDAFTRAN");
	    $NAMA_VENDOR	    = $this->post("NAMA_VENDOR");
        $NOMOR_NPWP         = $this->post("NOMOR_NPWP");
        $ALAMAT             = $this->post("ALAMAT");
        $NO_TLP             = $this->post("NO_TLP");
        $ALAMAT_EMAIL       = $this->post("ALAMAT_EMAIL");
        $NAMA_REKENING      = $this->post("NAMA_REKENING");
        $NAMA_BANK          = $this->post("NAMA_BANK");
        $ACCT_NUMBER        = $this->post("ACCT_NUMBER");
        $NO_HP              = $this->post("NO_HP");
        $WEBSITE            = $this->post("WEBSITE");
        $CURRENCY_VENDOR    = $this->post("CURRENCY_VENDOR");
        $CABANG_BANK        = $this->post("CABANG_BANK");
        $JENIS_1            = $this->post("JENIS_1");
        $JENIS_2            = $this->post("JENIS_2");
        $JENIS_3            = $this->post("JENIS_3");
	
	    if($KODE_PENDAFTRAN == null ||  $NAMA_VENDOR == null  || $ALAMAT_EMAIL == null || $KODE_PENDAFTRAN == "" ||  $NAMA_VENDOR == ""  || $ALAMAT_EMAIL == "" ){
            $result["status"] = false;
            $result["message"] = "all field requered !";
	    }else{
        	$dataVendorCode = $this->M_Varelia->get_vendor_where(array("KODE_PENDAFTRAN"=>$KODE_PENDAFTRAN))->row();
        	if($dataVendorCode){
                $dataUpdate = array(
                    "KODE_PENDAFTRAN"    => $KODE_PENDAFTRAN,
                    "NAMA_VENDOR"	     => $NAMA_VENDOR,
                    "NOMOR_NPWP"         => $NOMOR_NPWP,
                    "ALAMAT"             => $ALAMAT,
                    "NO_TLP"             => $NO_TLP,
                    "ALAMAT_EMAIL"       => $ALAMAT_EMAIL,
                    "NAMA_REKENING"      => $NAMA_REKENING,
                    "NAMA_BANK"          => $NAMA_BANK,
                    "ACCT_NUMBER"        => $ACCT_NUMBER,
                    "NO_HP"              => $NO_HP,
                    "WEBSITE"            => $WEBSITE,
                    "CURRENCY_VENDOR"    => $CURRENCY_VENDOR,
                    "CABANG_BANK"        => $CABANG_BANK,
                    "JENIS_1"            => $JENIS_1,
                    "JENIS_2"            => $JENIS_2,
                    "JENIS_3"            => $JENIS_3
                );
                $update = $this->M_Varelia->update_vendor(array("ID_VENDOR"=>$dataVendorCode->ID_VENDOR),$dataUpdate);
                if($update){
                    $vendor = $this->M_Varelia->get_vendor_where(array("ID_VENDOR"=>$dataVendorCode->ID_VENDOR))->row();
                    $result["status"]   = true;
                    $result["message"]  = "update data success !";
                    $result["data"]     = $vendor;
                }else{
                    $result["status"]   = false;
                    $result["message"]  = "update data error !";
                    $result["data"]     = array();
                }
        	}else{
                $dataVendorName = $this->M_Varelia->get_vendor_where(array("NAMA_VENDOR"=>$NAMA_VENDOR))->row();
                if($dataVendorName){
                    $dataUpdate = array(
                        "KODE_PENDAFTRAN"    => $KODE_PENDAFTRAN,
                        "NAMA_VENDOR"	     => $NAMA_VENDOR,
                        "NOMOR_NPWP"         => $NOMOR_NPWP,
                        "ALAMAT"             => $ALAMAT,
                        "NO_TLP"             => $NO_TLP,
                        "ALAMAT_EMAIL"       => $ALAMAT_EMAIL,
                        "NAMA_REKENING"      => $NAMA_REKENING,
                        "NAMA_BANK"          => $NAMA_BANK,
                        "ACCT_NUMBER"        => $ACCT_NUMBER,
                        "NO_HP"              => $NO_HP,
                        "WEBSITE"            => $WEBSITE,
                        "CURRENCY_VENDOR"    => $CURRENCY_VENDOR,
                        "CABANG_BANK"        => $CABANG_BANK,
                        "JENIS_1"            => $JENIS_1,
                        "JENIS_2"            => $JENIS_2,
                        "JENIS_3"            => $JENIS_3
                    );
                    $update = $this->M_Varelia->update_vendor(array("ID_VENDOR"=>$dataVendorName->ID_VENDOR),$dataUpdate);
                    if($update){
                        $vendor = $this->M_Varelia->get_vendor_where(array("ID_VENDOR"=>$dataVendorName->ID_VENDOR))->row();
                        $result["status"]   = true;
                        $result["message"]  = "update data success !";
                        $result["data"]     = $vendor;
                    }else{
                        $result["status"]   = false;
                        $result["message"]  = "update data error !";
                        $result["data"]     = array();
                    }
                }else{
                    $dataInsert = array(
                        "KODE_PENDAFTRAN"    => $KODE_PENDAFTRAN,
                        "NAMA_VENDOR"	     => $NAMA_VENDOR,
                        "NOMOR_NPWP"         => $NOMOR_NPWP,
                        "ALAMAT"             => $ALAMAT,
                        "NO_TLP"             => $NO_TLP,
                        "ALAMAT_EMAIL"       => $ALAMAT_EMAIL,
                        "NAMA_REKENING"      => $NAMA_REKENING,
                        "NAMA_BANK"          => $NAMA_BANK,
                        "ACCT_NUMBER"        => $ACCT_NUMBER,
                        "NO_HP"              => $NO_HP,
                        "WEBSITE"            => $WEBSITE,
                        "CURRENCY_VENDOR"    => $CURRENCY_VENDOR,
                        "CABANG_BANK"        => $CABANG_BANK,
                        "JENIS_1"            => $JENIS_1,
                        "JENIS_2"            => $JENIS_2,
                        "JENIS_3"            => $JENIS_3
                    );
                    $insert = $this->M_Varelia->insert_vendor(array("KODE_PENDAFTRAN"=>$KODE_PENDAFTRAN),$dataInsert);
                    if($insert ){
                        $vendor = $this->M_Varelia->get_vendor_where(array("KODE_PENDAFTRAN"=>$KODE_PENDAFTRAN))->row();
                        $result["status"] = true;
                        $result["message"] = "insert data success!";
                        $result["data"]     = $vendor;
                    }else{
                        $result["status"] = false;
                        $result["message"] = "insert data error !";
                        $result["data"]     = array();
                    }
                }
        	}
        }
        $this->response( $result, REST_Controller::HTTP_OK);
    }
}
