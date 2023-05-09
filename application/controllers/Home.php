<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		
		if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
		}

		$is_buyer       = $this->session->userdata('is_buyer');
		$is_procurement = $this->session->userdata('is_procurement');

		if($is_buyer):
			$this->_dashboard_buyer();
		elseif($is_procurement):
			$this->_dashboard_proc_support();
		else:
			$this->_dashboard_general();
		endif;

	}

	private function _dashboard_general(){

		$this->load->model('rkap_mdl', 'rkap');
		$this->load->model('feasibility_study_mdl', 'fs');

		$data['get_exist_year'] = $this->rkap->get_exist_year_master();

		$directorat    = "";
		$division      = "";
		$unit          = "";
		$id_directorat = "";
		$id_division   = "";
		$id_unit       = "";

		$binding = check_binding();
		$data_binding = $binding['data_binding'];

		if($binding['binding'] == "directorat"){
			$directorat    = $data_binding['directorat'][0]['DIRECTORAT_NAME'];
			$id_directorat = $data_binding['directorat'][0]['ID_DIR_CODE'];
			if(strtolower($directorat) == "finance" || strtolower($directorat) == "ceo" || strtolower($directorat) == "coo"){
				$directorat    = "";
				$id_directorat = "";
			}
		}
		elseif($binding['binding'] == "division"){
			$directorat    = $data_binding['directorat'][0]['DIRECTORAT_NAME'];
			$id_directorat = $data_binding['directorat'][0]['ID_DIR_CODE'];

			$get_division = $data_binding['division'];

			if(count($get_division) > 1){
				$id_division = array();
				$division    = array();
				foreach ($get_division as $key => $value) {
					$id_division[] = $value['ID_DIVISION'];
					$division[]    = $value['DIVISION_NAME'];
				}
			}else{
				$id_division = $get_division[0]['ID_DIVISION'];
				$division    = $get_division[0]['DIVISION_NAME'];
			}
		}elseif($binding['binding'] == "unit"){
			$directorat    = $data_binding['directorat'][0]['DIRECTORAT_NAME'];
			$id_directorat = $data_binding['directorat'][0]['ID_DIR_CODE'];

			$get_division  = $data_binding['division'];
			if(count($get_division) > 1){
				$id_division = array();
				$division    = array();
				foreach ($get_division as $key => $value) {
					$id_division[] = $value['ID_DIVISION'];
					$division[]    = $value['DIVISION_NAME'];
				}
			}else{
				$id_division = $get_division[0]['ID_DIVISION'];
				$division    = $get_division[0]['DIVISION_NAME'];
			}
			
			$get_unit = $data_binding['unit'];
			if(count($get_unit) > 1){
				$id_unit = array();
				$unit    = array();
				foreach ($get_unit as $key => $value) {
					$id_unit[] = $value['ID_UNIT'];
					$unit[]    = $value['UNIT_NAME'];
				}
			}else{
				$id_unit = $get_unit[0]['ID_UNIT'];
				$unit    = $get_unit[0]['UNIT_NAME'];
			}
		}

		$year = date("Y");

		$summary_budget =  $this->fs->get_summary_budget($year, $directorat, $division, $unit);
		$total_reloc   = $summary_budget['TOTAL_RELOC_IN']-$summary_budget['TOTAL_RELOC_OUT'];
		$total_rkap    = $summary_budget['TOTAL_RKAP']+$total_reloc;
		$budget_used   = $summary_budget['BUDGET_USED'];
		$budget_remain = $total_rkap - $budget_used;

		$total_fs    =  $this->fs->get_total_justification($year, $id_directorat, $id_division, $id_unit);
		$fs_total    = 0;
		$fs_request  = 0;
		$fs_approved = 0;
		$fs_used     = 0;

		foreach ($total_fs as $key => $value) {
			if(strtolower($value['STATUS']) == "approved"){
				$fs_approved += $value['TOTAL'];
			}
			if(strtolower($value['STATUS']) == "request_approve"){
				$fs_request += $value['TOTAL'];
			}

			if(strtolower($value['STATUS']) == "fs used"){
				$fs_used += $value['TOTAL'];
			}

			$fs_total += $value['TOTAL'];
		}

		$data['directorat']     = $directorat;
		$data['division']       = $division;
		$data['unit']           = $unit;
		$data['id_directorat']  = $id_directorat;
		$data['id_division']    = $id_division;
		$data['id_unit']        = $id_unit;
		$data['fs_total']       = $fs_total;
		$data['fs_approved']    = $fs_approved;
		$data['fs_request']     = $fs_request;
		$data['fs_used']        = $fs_used;
		$data['total_rkap']     = number_format($total_rkap,0,',','.');
		$data['budget_used']    = number_format($budget_used,0,',','.');
		$data['budget_remain']  = number_format($budget_remain,0,',','.');

		$data['title']          = "Dashboard";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/dashboard";
		$this->template->load('main', $data['template_page'], $data);
	}

	private function _dashboard_proc_support(){

		$data['title']          = "Dashboard";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/dashboard";
		$this->template->load('main', $data['template_page'], $data);
	}

	private function _dashboard_buyer(){

		$data['title']          = "Dashboard";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/dashboard_buyer";

		$this->load->model('purchase_mdl', 'purchase');
		$buyer_email = $this->session->userdata('email');

		$get_counter = $this->purchase->get_counter_buyer($buyer_email);

		$data['total_pr_pending'] = $get_counter['PR_PENDING'];
		$data['total_po_pending'] = $get_counter['PO_PENDING'];
		$data['total_po_success'] = $get_counter['PO_SUCCESS'];
		
		$this->template->load('main', $data['template_page'], $data);
	}


	public function get_justification(){

		$this->load->model('feasibility_study_mdl', 'fs');

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year       = $this->input->post('year');
		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');

		$get_all         = $this->fs->get_justification($year, $directorat, $division, $unit);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status = ($value['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($value['STATUS']);

				$time       = date(" H:i", strtotime($value['CREATED_DATE']));
				$date       = date("Y-m-d", strtotime($value['FS_DATE']));
				$fixed_date = $date.$time;

				$row[] = array(
							'no'           => $number,
							'id'           => $value['ID_FS'],
							'id_fs'        => encrypt_string($value['ID_FS'], true),
							'fs_number'    => $value['FS_NUMBER'],
							'fs_name'      => $value['FS_NAME'],
							'total_amount' => number_format($value['NOMINAL_FS'],0,',','.'),
							'fs_date'      => dateFormat($value['FS_DATE'], "fintool", false, "id"),
							'fixed_date'   => dateFormat($fixed_date, "fintool", false, "id"),
							'fs_status'    => strtolower($status)
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

	public function create_user(){

		$query = $this->db->query("SELECT * FROM MASTER_APPROVAL WHERE PIC_EMAIL NOT IN (SELECT EMAIL FROM MASTER_USER) LIMIT 10")->result_array();

		$password = $this->config->item('default_password');

		foreach ($query as $key => $value) {

			$email    = $value['PIC_EMAIL'];
			$identity = str_replace("@linkaja.id", "", $email);

			$additional_data = array(
				'ID_DIR_CODE'  => $value['ID_DIR_CODE'],
				'DISPLAY_NAME' => $value['PIC_NAME'],
				'ACTIVE'       => 1
			);

			$create_user = $this->ion_auth->register($identity, $password, $email, $additional_data);

			if ($create_user) {
				$this->ion_auth->add_to_group(37, $create_user);
				echo $identity. " ok <br>";
			}

		}

	}

	public function download($param)
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
		}

		if($param){
			$this->load->helper('download');

			$file = decrypt_string($param, true);

			force_download('./' . $file, NULL);
		}else{
			redirect('/', 'refresh');
		}

	}

    public function check_folder_uploads(){

    	$get_attachment = $this->db->query("SELECT DOCUMENT_ATTACHMENT FROM FS_BUDGET
    								WHERE DOCUMENT_ATTACHMENT != ''")->result_array();
    	// echo_pre($get_attachment);die;
    	$all_files = array();
    	foreach ($get_attachment as $key => $value) {
    		$all_files[] = $value['DOCUMENT_ATTACHMENT'];
    	}
    	// echo_pre($all_files);die;

		$files       = scandir('./uploads');
		$path_upload = './uploads/';

	    foreach ($files as $key => $value) {
	        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

	        if (!is_dir($path)) {
	        	/*echo_pre($value);*/
	        	if(!in_array($value, $all_files)){
	        		delete_file($path_upload.$value);
	        		echo 'DELETED '. $value .' <br>';
	        	}
	        }
	    }

    }



    public function check_folder_uploads_all($module=""){

    	$all_files = array();

		switch ($module) {
			case 'justif':

				$get_attachment = $this->db->query("SELECT DOCUMENT_ATTACHMENT FROM FS_BUDGET
		    								WHERE DOCUMENT_ATTACHMENT != ''")->result_array();
		    	foreach ($get_attachment as $key => $value) {
		    		$all_files[] = $value['DOCUMENT_ATTACHMENT'];
		    	}
				$files       = scandir('./uploads');
				$path_upload = './uploads/';

			break;
				
			case 'pr':

		    	$get_attachment = $this->db->query("SELECT DOCUMENT_UPLOAD FROM PR_HEADER
		    								WHERE DOCUMENT_UPLOAD IS NOT NULL")->result_array();
		    	foreach ($get_attachment as $key => $value) {
		    		$all_files[] = $value['DOCUMENT_UPLOAD'];
		    	}

				$files       = scandir('./uploads/pr_attachment');
				$path_upload = './uploads/pr_attachment/';
			break;
				
			case 'po':

		    	$get_attachment = $this->db->query("SELECT DOCUMENT_SOURCING FROM PO_HEADER
		    								WHERE DOCUMENT_SOURCING IS NOT NULL")->result_array();
		    	foreach ($get_attachment as $key => $value) {
		    		$all_files[] = $value['DOCUMENT_SOURCING'];
		    	}

		    	$get_attachment = $this->db->query("SELECT DOCUMENT_CLAUSE FROM PO_HEADER
		    								WHERE DOCUMENT_CLAUSE IS NOT NULL")->result_array();

		    	foreach ($get_attachment as $key => $value) {
		    		$all_files[] = $value['DOCUMENT_CLAUSE'];
		    	}

				$files       = scandir('./uploads/po_attachment');
				$path_upload = './uploads/po_attachment/';
			break;
			
				
			case 'fpjp':

		    	$get_attachment = $this->db->query("SELECT DOCUMENT_UPLOAD FROM FPJP_HEADER
		    								WHERE DOCUMENT_UPLOAD IS NOT NULL")->result_array();
		    	foreach ($get_attachment as $key => $value) {
		    		$all_files[] = $value['DOCUMENT_UPLOAD'];
		    	}

				$files       = scandir('./uploads/fpjp_attachment');
				$path_upload = './uploads/fpjp_attachment/';
			break;
			
			default:
				echo 'nothing';
				die;
			break;
		}
		$total_deleted = 0;

		echo '<b>DELETED DOCUMENT '.$module.' :</b><br>';

	    foreach ($files as $key => $value) {
	        // $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
	        // if (!is_dir($path)) {
	        	if(!in_array($value, $all_files)){
	        		delete_file($value);
	        		echo $value .' <br>';
	        	}
	        // }
	    }

		echo '<b>TOTAL DELETED : '.$total_deleted.' :</b>';;

    }


	

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */