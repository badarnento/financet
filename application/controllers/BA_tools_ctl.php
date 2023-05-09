<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BA_tools_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

	}

	public function index()
	{
		if($this->ion_auth->is_admin() == true || in_array("upload-csv/settlement", $this->session->userdata['menu_url']) ){

			$data['title']          = "Upload CSV Deposit/Settlement";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/ba_tools";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

	public function non_digipos()
	{
		if($this->ion_auth->is_admin() == true || in_array("upload-csv/nondigipos", $this->session->userdata['menu_url']) ){

			$data['title']          = "Upload CSV Non Digipos";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/non_digipos";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}



	public function load_data_settlement(){

		$this->load->model('BA_tools_mdl', 'ba_tools');

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all = $this->ba_tools->get_all("settlement");
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'          => $number,
						'id'          => $value['ID'],
						'file_name'   => $value['FILE_NAME'],
						'key_upload'  => $value['KEY_UPLOAD'],
						'status'      => ($value['STATUS'] > 0) ? 'Succes' : 'Error',
						'upload_by'   => $value['UPLOAD_BY'],
						'upload_date' => dateFormat($value['UPLOAD_DATE'], 'fintool', false)
						);
				$number++;

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);
	}

	public function load_data_nondigipos(){

		$this->load->model('BA_tools_mdl', 'ba_tools');

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all = $this->ba_tools->get_all("nondigipos");
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'          => $number,
						'id'          => $value['ID'],
						'file_name'   => $value['FILE_NAME'],
						'key_upload'  => $value['KEY_UPLOAD'],
						'status'      => ($value['STATUS'] > 0) ? 'Succes' : 'Error',
						'upload_by'   => $value['UPLOAD_BY'],
						'upload_date' => dateFormat($value['UPLOAD_DATE'], 'fintool', false)
						);
				$number++;

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);
	}

	public function upload()
	{

		$result['status']   = false;
        $result['messages'] = "Failed to upload";

        $category = $this->input->post('category');

        if(isset($_FILES['file'])){
            if($_FILES['file']['name'] != ""){

            	$temp_file = $_FILES['file']['tmp_name'];
                $rand      = generateRandomString(5);
                $path      = $_FILES['file']['name'];
                $name      = explode(".", $path);
                if(count($name) > 1){
                    array_pop($name);
                    $name = implode("-", $name);
                }else{
                    $name = $name[0];
                }
				$fix_name     = (strlen($name) > 150 ) ? substr($name,0, 150) : $name;
				$ext          = pathinfo($path, PATHINFO_EXTENSION);

				if($ext !== "csv"){
        			$result['messages'] = "File is not csv";
       				echo json_encode($result);
       				exit;
				}
				$disable_char = array("'","\"",".",",","&","[","]","{","}","|","/","~","!","@","#","\$","%","^","*",";","?");
				$fix_name     = str_replace($disable_char,"-", $fix_name);
				$fix_name     = str_replace(" - ","_", $fix_name);
				$fix_name     = str_replace(" ","", $fix_name);
				$file_name    = preg_replace('/\s+/', ' ', $fix_name )."_" . $rand . "." . $ext;

                $data['FILE_NAME'] = $file_name;
                $data['KEY_UPLOAD'] = $rand;
                $data['CATEGORY'] = $category;
                $data['UPLOAD_BY'] = get_user_data($this->session->userdata('user_id'));

                if($this->_ftp_upload($temp_file, $file_name, $category)){
            		$result['messages'] = 'File successfully uploaded';
					$result['status']   = true;
                }else{
                	$data['STATUS'] = 0;
                }

                $this->crud->create("TRX_UPLOAD_DIGIPOS", $data, false);
        	}
        }else{
            $result['messages'] = 'No File selected';
        }

        echo json_encode($result);

	}


	private function _ftp_upload($source_file, $file_name, $category){
		$this->load->library('ftp');

		/*$config['hostname'] = 'ftp.finopsfinarya.id';
		$config['username'] = 'BA_TOOLS@finopsfinarya.id';
		$config['password'] = 'B@T00L52021';*/
		$config['hostname'] = '212.1.210.100';
		$config['username'] = 'fss2021@gfourtech.com';
		$config['password'] = 'Fsc*oBoDS5]b';
		/*
		[15:04] Yudhistira Dwi Chahya Putra
FTP Username: fss2021@gfourtech.comFTP server: ftp.gfourtech.comFTP & explicit FTPS port:  21

[15:04] Yudhistira Dwi Chahya Putra
Fsc*oBoDS5]b


*/

		$this->ftp->connect($config);

		$folder = ($category == "settlement") ? "DepositSettlement" : "NonDigipos";

		$destination_file = $folder.'/new/' . $file_name ;

		$upload_ftp = $this->ftp->upload($source_file, $destination_file, 'ascii', 0775);
		$this->ftp->close();

		if($upload_ftp){
			return true;
		}else{
			return false;
		}

	}
}

/* End of file BA_tools_ctl.php */
/* Location: ./application/controllers/BA_tools_ctl.php */