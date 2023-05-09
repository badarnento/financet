<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
    }
 
    public function do_upload(){

        if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
        {
            @set_time_limit(300);
        }

        $folder   = $this->input->post('folder');
        $category = $this->input->post('category');

        if($category == "boc"){
            $folder = "boc_attachment";
        }
        if($category == "risk"){
            $folder = "risk_attachment";
        }
        if($category == "cfo"){
            $folder = "cfo_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }
        if($category == "pr"){
            $folder = "pr_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }
        if($category == "fpjp"){
            $folder = "fpjp_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }
        if($category == "po"){
            $folder = "po_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }
        if($category == "gr"){
            $folder = "gr_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }
        if($category == "fpjp_return"){
            $folder = "fpjp_return_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }
         if($category == "justif_mom"){
            $folder = "justif_mom_attachment";
            if (!file_exists(FCPATH.'./uploads/'.$folder)) {
                mkdir(FCPATH.'./uploads/'.$folder, 0740, true);
            }
        }

        $result['status']   = false;
        $result['messages'] = "Failed to upload";

        if(isset($_FILES['file'])){
            if($_FILES['file']['name'] != ""){
                $rand      = generateRandomString(5);
                $path      = $_FILES['file']['name'];
                $name      = explode(".", $path);
                if(count($name) > 1){
                    array_pop($name);
                    $name = implode("-", $name);
                }else{
                    $name = $name[0];
                }
                $fix_name  = (strlen($name) > 100 ) ? substr($name,0, 100) : $name;
                $ext       = pathinfo($path, PATHINFO_EXTENSION);
                if($category == "fpjp"){
                    $disable_char = array("'","\"",".",",","&","[","]","{","}","|","/","~","!","@","#","\$","%","^","*",";","?");
                    $fix_name = str_replace($disable_char,"-", $fix_name);
                    $fix_name = str_replace(" - "," ", $fix_name);
                    $file_name = preg_replace('/\s+/', ' ', $fix_name )."_" . $rand . "." . $ext;
                }else{
                    $file_name = preg_replace("/[^a-zA-Z0-9]+/", "", $fix_name) ."_" . $rand . "." . $ext;
                }
            }
        }

        if ($this->_upload('file', $folder, $file_name)){
            $result['status']   = true;
            $result['messages'] = $file_name;
        }

        echo json_encode($result);

    }

    private function _upload($field_name, $folder="", $file_name){
        
        if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
        {
            @set_time_limit(300);
        }

        //file upload destination
        $config['upload_path']   = './uploads/'.$folder.'/';
        //allowed file types. * means all types
        $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|jpg|jpeg|png|zip|rar';
        //allowed max file size. 0 means unlimited file size
        $config['max_size'] = '0';
        //max file name size
        $config['max_filename'] = '355';
        //whether file name should be encrypted or not
        $config['encrypt_name'] = FALSE;
        $config['remove_spaces'] = FALSE;
        $config['file_name'] = $file_name;
        //store image info once uploaded
        $image_data = array();
        //check for errors
        $is_file_error = FALSE;
        //check if file was selected for upload
        if (!$_FILES) {
            $is_file_error = TRUE;
        }
        //if file was selected then proceed to upload
        if (!$is_file_error) {

            /*if (file_exists(FCPATH.$upload_path.$folder_name."/".$file_name)){
                unlink($upload_path.$folder_name."/".$file_name);
            }*/
            //load the preferences
            $this->load->library('upload', $config);
            //check file successfully uploaded. 'image_name' is the name of the input
            if (!$this->upload->do_upload($field_name)) {
                //if file upload failed then catch the errors
                $is_file_error = TRUE;
            } else {
                //store the file info
                $image_data = $this->upload->data();

                if($image_data){
                    return true;
                }

            }
        }
        return false;
    }
       
    public function delete(){
        $folder = $this->input->post('folder');
        $category = $this->input->post('category');
        if($category == "boc"){
            $folder = "boc_attachment";
        }
        if($category == "risk"){
            $folder = "risk_attachment";
        }
        if($category == "cfo"){
            $folder = "cfo_attachment";
        }
        if($category == "pr"){
            $folder = "pr_attachment";
        }
        if($category == "fpjp"){
            $folder = "fpjp_attachment";
        }
        if($category == "po"){
            $folder = "po_attachment";
        }
        if($category == "gr"){
            $folder = "gr_attachment";
        }
        if($category == "fpjp_return"){
            $folder = "fpjp_return_attachment";
        }
        if($category == "justif_mom"){
            $folder = "justif_mom_attachment";
        }

        $folder = ($folder != "") ? $folder."/" : "";
        $file   = $this->input->post('file');
        $path   = './uploads/'.$folder.$file;

        echo json_encode(delete_file($path));
    }  
}