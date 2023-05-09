<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_ctl extends CI_Controller {

	private $module_name = "fs",
			$pic_email  = "",
			$module_url = "feasibility-study";

	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('feasibility_study_mdl', 'feasibility_study');
		
		$is_delegate    = $this->session->userdata('is_delegate');
      	$this->pic_email = ($is_delegate !== false) ? $is_delegate : $this->session->userdata('email');

	}


    public function approval_fs(){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "budget/approval");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->feasibility_study->check_is_approval($this->pic_email);

		if($check_is_approval){

			$get_fs_from_approval = $this->feasibility_study->get_fs_from_approval($this->pic_email);

			$id_fs = array();

			if($get_fs_from_approval){
				foreach ($get_fs_from_approval as $value) {
					$id_fs[] = $value['ID_FS'];
				}
			}

			$data['title']          = "All Justification";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/feasibility_study_approval";
			
			$data['id_fs']  = json_encode($id_fs);

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'You are not as approval');
			redirect('/');

		}

	}

    public function approval_fs_single($id_approval){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "budget/approval/".$id_approval);
			redirect('login');
		}

		$decrypt = decrypt_string($id_approval, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify justification');
			redirect('/');
		}

		$id_fs       = $exp[0];
		$id_approval = $exp[1];
		$get_trx     = $this->crud->read_by_id("TRX_APPROVAL", array("ID" => $id_approval, "IS_ACTIVE" => 1));
		$get_approval = $this->crud->read_by_id("MASTER_APPROVAL", array("ID_APPROVAL" => $get_trx['ID_APPROVAL']));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify justification');
			redirect('/budget/approval');
		}

		$check_exist = $this->crud->check_exist("FS_BUDGET", array("ID_FS" => $id_fs));

		if($check_exist > 0){

			$pic_email = $this->pic_email;
			$pic_level = "";
			if($this->session->userdata('data_employee')){
				$pic_level = $this->session->userdata('data_employee')['level'];
			}

			$check_approval = $this->crud->read("MASTER_APPROVAL", array("PIC_EMAIL" => $pic_email));

			foreach ($check_approval as $key => $value) {
				if( strtolower($value['PIC_LEVEL']) == "risk"){
					$pic_level = "RISK";
				}
				if( strtolower($value['PIC_LEVEL']) == "fraud"){
					$pic_level = "FRAUD";
				}
				if( strtolower($value['PIC_LEVEL']) == "budget admin"){
					$pic_level = "BUDGET ADMIN";
				}
				if( strtolower($value['PIC_LEVEL']) == "hog budget"){
					$pic_level = "HOG BUDGET";
				}
				if( strtolower($value['PIC_LEVEL']) == "cfo"){
					$pic_level = "CFO";
				}
			}

			$get_fs_budget = $this->feasibility_study->get_fs_to_approve_by_id($id_fs, $id_approval, $pic_email);
			
			$data['title']           = "Justification ".$get_fs_budget['FS_NUMBER'];
			$data['module']          = "datatable";
			$data['template_page']   = $this->module_name."/fs_approval_single";
			
			$data['id_fs']           = $id_fs;
			$data['id_dpl']           = 0;
			$data['fs_number']       = $get_fs_budget['FS_NUMBER'];
			$data['fs_name']         = $get_fs_budget['FS_NAME'];
			$data['fs_description']  = $get_fs_budget['FS_DESCRIPTION'];
			$data['fs_date']         = dateFormat($get_fs_budget['FS_DATE'], 4, false);
			$data['fs_amount']       = number_format($get_fs_budget['NOMINAL_FS'],0,',','.');
			$data['fs_currency']     = $get_fs_budget['CURRENCY'];
			$data['fs_rate']         = number_format($get_fs_budget['CURRENCY_RATE'],0,',','.');
			$data['fs_directorat']   = $get_fs_budget['ID_DIR_CODE'];
			$data['fs_division']     = $get_fs_budget['ID_DIVISION'];
			$data['fs_unit']         = $get_fs_budget['ID_UNIT'];
			$data['fs_submitter']    = $get_fs_budget['SUBMITTER'];
			$data['fs_jabatan_sub']  = $get_fs_budget['JABATAN_SUBMITER'];
			$data['fs_last_update']  = dateFormat($get_fs_budget['UPDATED_DATE'], "with_day", false);
			$data['trx_status']      = $get_fs_budget['TRX_STATUS'];
			$data['trx_date']        = dateFormat($get_fs_budget['TRX_DATE'], "with_day", false);
			$data['level']           = $get_fs_budget['LEVEL'];
			
			$data['pic_level']       = $pic_level;
			$data['boc_attachment']  = ($pic_level == "BOC") ? true : false;
			$data['risk_attachment'] = ($pic_level == "RISK" || $pic_level == "FRAUD") ? true : false;
			$data['cfo_attachment']  = ($pic_level == "CFO") ? true : false;

			$is_show = false;
			if($pic_level == "HOG BUDGET" || $pic_level == "BUDGET ADMIN"){
				$is_show = true;
			}

			if($get_fs_budget['LEVEL'] > 1):
				// $level_min = $get_fs_budget['LEVEL']-1;
				$get_approver_before            = $this->feasibility_study->get_approval_before($id_fs);
				$data['approver_before_name']   = $get_approver_before['PIC_NAME'];
				$data['approver_before_remark'] = $get_approver_before['REMARK'];
				$data['approver_before_date']   = dateFormat($get_approver_before['TRX_DATE'], "with_day", false);
			endif;
			$fs_status = "Waiting approval";

			if($pic_level == "RISK" || $pic_level == "FRAUD" || $pic_level == "BUDGET ADMIN"){
				$fs_status = "Waiting for review";
			}

			$data['fs_status']      = ($get_fs_budget['STATUS'] == "request_approve") ? $fs_status : ucfirst($get_fs_budget['STATUS']);
			$data['fs_status_desc'] = $get_fs_budget['STATUS_DESCRIPTION'];

			$attachment = array();

			if($get_fs_budget['DOCUMENT_ATTACHMENT']){
				$attachment[] = array(
									"FILE_NAME"     => $get_fs_budget['DOCUMENT_ATTACHMENT'],
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/".$get_fs_budget['DOCUMENT_ATTACHMENT'], true),
									"DATE_UPLOADED" => strtotime($get_fs_budget['CREATED_DATE']),
									"UPLOADED_BY"   => $get_fs_budget['SUBMITTER']
									);
			}
			if($get_fs_budget['BOC_ATTACHMENT']){
				$data_file  = json_decode($get_fs_budget['BOC_ATTACHMENT']);
				$attachment[] = array(
									"FILE_NAME"     => $data_file->FILE,
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/boc_attachment/".$data_file->FILE, true),
									"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
									"UPLOADED_BY"   => $data_file->UPLOADED_BY . " BOC"
									);
			}
			if($get_fs_budget['RISK_ATTACHMENT']){
				$data_file  = json_decode($get_fs_budget['RISK_ATTACHMENT']);
				$attachment[] = array(
									"FILE_NAME"     => $data_file->FILE,
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/risk_attachment/".$data_file->FILE, true),
									"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
									"UPLOADED_BY"   => $data_file->UPLOADED_BY . " RISK &amp; FM"
									);
			}
			if($get_fs_budget['CFO_ATTACHMENT']){
				$data_file  = json_decode($get_fs_budget['CFO_ATTACHMENT']);
				$attachment[] = array(
									"FILE_NAME"     => $data_file->FILE,
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/cfo_attachment/".$data_file->FILE, true),
									"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
									"UPLOADED_BY"   => $data_file->UPLOADED_BY . " RISK &amp; FM"
									);
			}


			$id_district = $get_fs_budget['ID_DISTRICT'];

			$district = false;
			if($id_district > 0){
				$district = true;
				$data['district_name'] = get_district_by_id($id_district);
			}

			$data['district'] = $district;


			$id_approver = $get_trx['ID_APPROVAL'];
			$data['enable_approve'] = true;

			$data['doble_approve'] = false;
			$data['level_dpl'] = 0;
			$is_dpl = $get_fs_budget['IS_DPL'];

			if($is_dpl > 0){
				$this->load->model('dpl_mdl','dpl');

				// $is_dpl_approver = $this->crud->check_exist("TRX_APPROVAL_DPL","")
				// $get_dpl = $this->crud->read_by_param("DPL", array("ID_FS" => $id_fs));
				$get_dpl = $this->dpl->get_dpl_to_approve_by_id($id_fs, $pic_email);
				IF($get_dpl == false){
					$get_dpl = $this->crud->read_by_param("DPL", array("ID_FS" => $id_fs));
				}else{
					$data['level_dpl']       = $get_dpl['LEVEL'];
				}

				$data['dpl_number'] = $get_dpl['DPL_NUMBER'];
				$data['uraian']     = $get_dpl['URAIAN'];
				if($get_dpl['KEGIATAN_PENGADAAN'] == 1){
					$data['pengadaan']       = "Sudah dilaksanakan sebelum proses Pengadaan dilakukan oleh Procurement";
				}else{
					$data['pengadaan']       = "Belum dilaksanakan";
				}

				$id_dpl                  = $get_dpl['ID_DPL'];
				$data['id_dpl']          = $id_dpl;
				$data['date_from']       = dateFormat($get_dpl['DATE_FROM'], 5, false);
				$data['date_to']         = dateFormat($get_dpl['DATE_TO'], 5, false);
				$data['vendor']          = $get_dpl['REKANAN'];
				$data['pic']             = $get_dpl['PIC_USER'];
				$data['jabatan_sub']     = $get_dpl['JABATAN'];
				$data['evaluasi']        = $get_dpl['EVALUASI_REKANAN'];
				$data['alasan']          = $get_dpl['ALASAN_PENUNJUKAN'];
				$data['keuntungan']      = $get_dpl['KEUNTUNGAN_PENUNJUKAN'];
				$data['resiko']          = $get_dpl['RESIKO_PENUNJUKAN'];
				$data['dpl_submitter']   = $get_dpl['PIC_USER'];
				$data['dpl_jabatan_sub'] = $get_dpl['JABATAN'];

				$tujuanData = getTujuanDPL();

				$tujuan     = json_decode($get_dpl['TUJUAN_PENGADAAN']);
				$tujuan_arr = array();
				if($tujuan){
					foreach ($tujuan as $key => $value) {
						$tujuan_arr[] = $key;
					}
				}
				$tujuanFix = array();
				foreach ($tujuanData as $key => $value) {
					if(in_array(strtolower(str_replace(" ","_", $value)), $tujuan_arr)){
						$tujuanFix[] = $value;
					}
				}
				$data['tujuan_pgdn'] = $tujuanFix;

				$kriteriaData = getKriteriaDPL();

				$kriteria     = json_decode($get_dpl['KRITERIA']);
				$kriteria_arr = array();
				if($kriteria){
					foreach ($kriteria as $key => $value) {
						$kriteria_arr[] = $key;
					}
				}
				$kriteriaFix = array();
				foreach ($kriteriaData as $key => $value) {
					if(in_array(strtolower(str_replace(" ","_", $value)), $kriteria_arr)){
						$kriteriaFix[] = $value;
					}
				}
				$data['kriteria_pgdn'] = $kriteriaFix;

				$verificator = array();
				$get_verifier = $this->dpl->get_verifier_list($id_dpl);
				foreach ($get_verifier as $key => $value) {
					$verificator_level = strtolower($value['PIC_LEVEL']);
					if (strpos($verificator_level, 'procurement') !== false) {
						$status = $value['VERIFIKASI_PROC'];
					}
					if (strpos($verificator_level, 'risk') !== false) {
						$status = $value['VERIFIKASI_RISK'];
					}
					$status_verif = ($status == null || $status == "") ? $value['STATUS'] : $status;
					$verificator[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $status_verif, "JABATAN" => $value['JABATAN']);
				}

				$get_approval = $this->dpl->get_approval_by_dpl($id_dpl);

				$approval = array();
				$approval_remark = array();

				foreach ($get_approval as $key => $value) {
					if($id_approver == $value['ID_APPROVAL']){
						if($value['STATUS'] == "request_approve"){
							$data['doble_approve'] = true;
						}elseif($value['STATUS'] == NULL || $value['STATUS'] == FALSE){
							$data['enable_approve'] = false;
						}
					}
					$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['CATEGORY']);
					if(!empty($value['REMARK'])){
						$approval_remark[] = $value;
					}
				}
				$data['dpl_verificator'] = $verificator;
				$data['dpl_approval'] = $approval;

				/*if($exist_same){
					$data['doble_approve'] = true;
				}*/

			}

			// echo_pre($data);die;
			$data['is_dpl'] = $is_dpl;
			$data['fs_attachment'] = $attachment;
			$data['disabled_act'] = ($this->pic_email != $this->session->userdata('email') ) ? true : false;

			$get_comment = $this->feasibility_study->get_comment_history($id_fs, $is_show);
			$data['comment_history'] = $get_comment;

			$get_dpl = $this->db->query("select * FROM DPL WHERE ID_FS = ?", $id_fs)->row_array();

			$data['DPL'] = ($get_dpl) ? $get_dpl : FALSE;
			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All Justification", "link" => base_url("budget/approval"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FS Not Exist');
			redirect($this->module_url);

		}

	}

	public function load_fs_to_approv(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$pic_email = $this->pic_email;

		$get_all         = $this->feasibility_study->get_fs_to_approve($pic_email, $status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				/*if($value['LEVEL'] > 1){
					$get_status = $this->feasibility_study->get_status_fs($value['ID_FS'], $value['LEVEL']);
					$status     = ucfirst($get_status['STATUS'])." by ".$get_status['PIC_NAME']." at ".dateFormat($get_status['UPDATED_DATE'], 5, false);
				}else{
					$status = "Submitted by ".$value['SUBMITTER'];
				}*/

				$status_description = $value['STATUS_DESCRIPTION'];

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['ID_FS'],
						'id_fs'              => encrypt_string($value['ID_FS'], true),
						'id_fs_approval'     => encrypt_string($value['ID_FS']."-".$value['ID_FS_APPROVAL'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'fs_number'          => $value['FS_NUMBER'],
						'fs_name'            => $value['FS_NAME'],
						'fs_description'     => $value['FS_DESCRIPTION'],
						'fs_currency'        => $value['CURRENCY'],
						'fs_rate'            => $value['CURRENCY_RATE'],
						'status'             => $value['STATUS'],
						'submitter'          => $value['SUBMITTER'],
						'level'              => $value['LEVEL'],
						'fs_attachment'      => base_url("uploads/").$value['DOCUMENT_ATTACHMENT'],
						'status_description' => $status_description,
						'fs_date'            => dateFormat($value['FS_DATE'], 5, false),
						'total_amount'       => number_format($value['NOMINAL_FS'],0,',','.')
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


	public function action_approval(){

		$id_fs           = $this->input->post('id_fs');
		$id_dpl          = $this->input->post('id_dpl');
		$level           = $this->input->post('level');
		$remark          = $this->input->post('remark');
		$approval        = $this->input->post('approval');
		$category        = $this->input->post('category');
		$attachment_file = $this->input->post('attachment_file');

		// echo_pre($_POST);die;

		$result['status'] = false;
		$result['messages'] = "Failed to $approval justification";

		if($approval == "reject"):
			$status = "rejected";
		elseif($approval == "return"):
			$status = "returned";
		else:
			$status = "approved";
		endif;

		$data = array("STATUS" => $status, "REMARK" => $remark);
		$update = $this->crud->update("TRX_APPROVAL", $data, array("ID_FS" => $id_fs, "IS_ACTIVE" => 1, "LEVEL" => $level));
		$update = 1;

		if($update !== -1){

			$get_submitter   =  $this->feasibility_study->get_submitter_by_id_fs($id_fs);
			$submitter_email = "";
			$submitter_name  = "";

			$dataFsUpdate = array();

			if($attachment_file){
				$data_file = array(
									"FILE"          => $attachment_file,
									"DATE_UPLOADED" => time(),
									"UPLOADED_BY"   => get_user_data($this->session->userdata('user_id'))
									);

				if($category == "boc"){
					$dataFsUpdate = array("BOC_ATTACHMENT" => json_encode($data_file));
				}elseif($category == "risk"){
					$dataFsUpdate = array("RISK_ATTACHMENT" => json_encode($data_file));
				}else{
					$dataFsUpdate = array("CFO_ATTACHMENT" => json_encode($data_file));
				}
			}

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
				$submitter_name  = $get_submitter['SUBMITTER'];
			}

			$auto_reject   = get_auto_reject_date();
			$send_email    = true;
			$update_status = true;
			$send_to_next  = true;
			$is_bod        = false;
			$is_multi      = false;
			$email_cc      = array();

			if($level > 1){
				for ($i=1; $i < $level ; $i++) {
					$get_approver_before = $this->feasibility_study->get_approver($id_fs, $i);
					if($get_approver_before){
						if($get_approver_before['CATEGORY'] != "BOD"){
							$email_cc[] = $get_approver_before['PIC_EMAIL'];
						}
					}
				}
			}

			$get_approver = $this->feasibility_study->get_approver($id_fs, $level);
			$approver_cat = strtolower($get_approver['CATEGORY']);

			if($approver_cat == "bod"){
				$is_bod       = true;
				$email_cc_bod = array();
				$send_to_next = false;
				
				$get_multi_bod = $this->feasibility_study->get_multi_bod($id_fs, "request_approve");

				if(count($get_multi_bod) == 0){
					$send_to_next = true;
					$get_multi_bod = $this->feasibility_study->get_multi_bod($id_fs, "approved");
					foreach ($get_multi_bod as $key => $value) {
						$level = $value['LEVEL'];
					}
				}
				
			}

			$status_descAdd ="";

			if($approver_cat == "budget admin" ||$approver_cat == "risk" ||$approver_cat == "fraud"){
				$get_multi_approval = $this->feasibility_study->get_multi_approval($id_fs, $approver_cat);
				foreach ($get_multi_approval as $key => $value) {
					if($value['PIC_EMAIL'] != $this->pic_email){
						$this->crud->delete("TRX_APPROVAL", $value['ID']);
					}
				}
				$get_list_trx = $this->feasibility_study->get_list_trx($id_fs);
				
				$newLevel = 1;
				$dataNw   = array();
				foreach ($get_list_trx as $key => $value) {
					$dataNw[] = array("LEVEL" => $newLevel, "ID" => $value['ID']);
					if(strtolower($value['CATEGORY']) == $approver_cat){
						$level = $newLevel;
					}
					$newLevel++;
				}

				$newApprovalTotal = count($dataNw);

				if($newApprovalTotal > 0){
					$this->crud->update_batch_data("TRX_APPROVAL", $dataNw, "ID");
					$dataFsUpdate['APPROVAL_LEVEL'] = $newApprovalTotal;
				}

				$status_descAdd = "Reviewed";
			}

			$next_step = false;
			if($status == "approved"):

				$next_level = $level+1;
				$get_next_aprover = $this->feasibility_study->get_approver($id_fs, $next_level);

				if($is_bod){
					if(count($email_cc_bod) > 0){
						$email_cc   = array_merge($email_cc, $email_cc_bod);
						$email_cc   = array_unique($email_cc);
					}
				}
// echo_pre($get_next_aprover);

				if($get_next_aprover){

					$next_step = true;

					if($id_dpl){

						$check_dpl_is_verified = checkIsDPLVerified($id_dpl);
						$check_is_dpl_approval = check_is_dpl_approval($id_dpl, $get_next_aprover['ID_APPROVAL']);
						// echo $this->db->last_query();
// echo_pre($check_is_dpl_approval);

						if($check_dpl_is_verified){
							if(empty($check_is_dpl_approval['STATUS'])){
								$next_step = false;
							}
						}else{
							if(empty($check_is_dpl_approval['STATUS'])){
								$next_step = false;
							}
						}

					}

					// echo ($next_step) ? 'y' : 'n';
 
					$id_approval = $get_next_aprover['ID'];
					$next_approver_cat = strtolower($get_next_aprover['CATEGORY']);
					if($submitter_email != ""){
						$recipient['email_cc'] = $submitter_email;
						if($email_cc > 0){
							$email_cc[] = $submitter_email;
						}
					}

					if($next_approver_cat == "bod" && $is_bod === false){

						$get_bod = $this->feasibility_study->get_approver($id_fs, 0, true);

						foreach ($get_bod as $key => $value) {
							$id_approval = $value['ID'];

							$recipient['email'] = $value['PIC_EMAIL'];
							$recipient['name']  = $value['PIC_NAME'];
							$recipient['email_cc'] = $email_cc;


							if($next_step){
								$this->_email_approval($recipient, $id_fs, "request_approve", $remark, $id_approval);
								$this->crud->update("TRX_APPROVAL", array("STATUS" => "request_approve"), $id_approval);
							}
						}
					}
					elseif($next_approver_cat == "budget admin" || $next_approver_cat == "risk" || $next_approver_cat == "fraud") {


						$get_multi_approval = $this->feasibility_study->get_multi_approval($id_fs, $next_approver_cat);
						$req_review_desc = "request_review";

						if($next_approver_cat == "bod"){
							$req_review_desc = "request_approve";
						}
						foreach ($get_multi_approval as $key => $value) {
							$id_approval = $value['ID'];

							$recipient['email']    = $value['PIC_EMAIL'];
							$recipient['name']     = $value['PIC_NAME'];
							$recipient['email_cc'] = "";

							// if($next_step){
								$this->_email_approval($recipient, $id_fs, "request_review", $remark, $id_approval);
								$this->crud->update("TRX_APPROVAL", array("STATUS" => "request_approve"), $id_approval);
							// }
						}
					}
					else{

						if($send_to_next && $next_step){
							$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
							$recipient['name']  = $get_next_aprover['PIC_NAME'];

							$this->_email_approval($recipient, $id_fs, "request_approve", $remark, $id_approval);
							$this->crud->update("TRX_APPROVAL", array("STATUS" => "request_approve"), $id_approval);
						}

					}

					// die;
				}
				else{


					if($submitter_email != "" && $submitter_name != ""){

						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;

						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}

						if($send_email && $next_step){
							$this->_email_approval($recipient, $id_fs, $status, $remark);
						}
						if($update_status){
							$dataFsUpdate['STATUS'] = $status;
						}
					}
				}
				$status_descAdd = ($status_descAdd != "") ? $status_descAdd : "Approved";
				$dataFsUpdate['STATUS_DESCRIPTION'] = $status_descAdd ." by ".get_user_data($this->session->userdata('user_id'));
				$dataFsUpdate['AUTO_REJECT_DATE'] = $auto_reject;
				$dataFsUpdate['INTERFACE_STATUS'] = "NEW";
				$this->crud->update("FS_BUDGET", $dataFsUpdate, array("ID_FS" => $id_fs));

			else:



				if($submitter_email != "" && $submitter_name != ""){

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;

					$get_multi_bod = $this->feasibility_study->get_multi_bod($id_fs, "request_approve");

					if(count($get_multi_bod) > 0){
						foreach ($get_multi_bod as $key => $value) {
							$id_approval = $value['ID'];
							$this->crud->update("TRX_APPROVAL", array("STATUS" => NULL), $id_approval);
						}
					}
					
					$this->_email_approval($recipient, $id_fs, $status, $remark);
				}
				$dataFsUpdate['STATUS_DESCRIPTION'] = ucfirst($status)." by ".get_user_data($this->session->userdata('user_id'));
				$dataFsUpdate['STATUS'] = $status;

				if($id_dpl){
					$this->crud->update("DPL", $dataFsUpdate , array("ID_DPL" => $id_dpl));
				}
				$this->crud->update("FS_BUDGET", $dataFsUpdate , array("ID_FS" => $id_fs));

			endif;

			$log_info = $id_fs . " - " . $dataFsUpdate['STATUS_DESCRIPTION'];

			log_message('info', $log_info);

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		echo json_encode($result);
	}

	public function action_approval_dpl(){
		
		$id_dpl   = $this->input->post('id_dpl');
		$level    = $this->input->post('level');
		$remark   = $this->input->post('remark');
		$approval = $this->input->post('approval');

		$result['status'] = false;
		$result['messages'] = "Failed to $approval justification";

		if($approval == "reject"):
			$status = "rejected";
		else:
			$status = "approved";
		endif;

		$data = array("STATUS" => $status, "REMARK" => $remark);
		$update = $this->crud->update("TRX_APPROVAL_DPL", $data, array("ID_DPL" => $id_dpl, "IS_ACTIVE" => 1, "LEVEL" => $level));
		$update = 1;

		if($update !== -1){
			$this->load->model('dpl_mdl','dpl');

			$get_submitter   =  $this->dpl->get_submitter_by_id_dpl($id_dpl);
			$submitter_email = "";
			$submitter_name  = "";

			$dataFsUpdate = array();

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
				$submitter_name  = $get_submitter['PIC_USER'];
			}


			$send_email    = true;
			$update_status = true;
			$send_to_next  = true;
			$is_bod        = false;
			$is_multi      = false;
			$email_cc      = array();

			if($level > 1){
				for ($i=1; $i < $level ; $i++) {
					$get_approver_before = $this->dpl->get_approver($id_dpl, $i);
					if($get_approver_before){
						if($get_approver_before['CATEGORY'] != "BOD"){
							$email_cc[] = $get_approver_before['PIC_EMAIL'];
						}
					}
				}
			}

			$get_approver = $this->dpl->get_approver($id_dpl, $level);

			$approver_cat = strtolower($get_approver['CATEGORY']);

			if($approver_cat == "bod"){
				$is_bod       = true;
				$email_cc_bod = array();
				$send_to_next = false;
				
				$get_multi_bod = $this->dpl->get_multi_bod($id_dpl, "request_approve");

				if(count($get_multi_bod) == 0){
					$send_to_next = true;
					$get_multi_bod = $this->dpl->get_multi_bod($id_dpl, "approved");
					foreach ($get_multi_bod as $key => $value) {
						$level = $value['LEVEL'];
					}
				}
				
			}

			$status_descAdd ="";

			if($status == "approved"):

				$next_level = $level+1;
				$get_next_aprover = $this->dpl->get_approver($id_dpl, $next_level);

				if($is_bod){
					if(count($email_cc_bod) > 0){
						$email_cc   = array_merge($email_cc, $email_cc_bod);
						$email_cc   = array_unique($email_cc);
					}
				}

				if($get_next_aprover){

					$id_approval = $get_next_aprover['ID'];
					$next_approver_cat = strtolower($get_next_aprover['CATEGORY']);
					if($submitter_email != ""){
						$recipient['email_cc'] = $submitter_email;
						if($email_cc > 0){
							$email_cc[] = $submitter_email;
						}
					}

					if($next_approver_cat == "bod" && $is_bod === false){

						$get_bod = $this->dpl->get_approver($id_dpl, 0, true);

						foreach ($get_bod as $key => $value) {
							$id_approval = $value['ID'];

							$recipient['email'] = $value['PIC_EMAIL'];
							$recipient['name']  = $value['PIC_NAME'];
							$recipient['email_cc'] = $email_cc;

							// $this->_email_approval($recipient, $id_dpl, "request_approve", $remark, $id_approval);
							$this->crud->update("TRX_APPROVAL_DPL", array("STATUS" => "request_approve"), $id_approval);
						}
					}
					else{

						if($send_to_next){
							$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
							$recipient['name']  = $get_next_aprover['PIC_NAME'];

							// $this->_email_approval($recipient, $id_dpl, "request_approve", $remark, $id_approval);
							$this->crud->update("TRX_APPROVAL_DPL", array("STATUS" => "request_approve"), $id_approval);
						}

					}
				}
				else{
					if($submitter_email != "" && $submitter_name != ""){

						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;

						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}

						if($send_email){
							// $this->_email_approval($recipient, $id_dpl, $status, $remark);
						}
						if($update_status){
							$dataFsUpdate['STATUS'] = $status;
						}
					}
				}
				$status_descAdd = ($status_descAdd != "") ? $status_descAdd : "Approved";
				$dataFsUpdate['STATUS_DESCRIPTION'] = $status_descAdd ." by ".get_user_data($this->session->userdata('user_id'));
				$this->crud->update("DPL", $dataFsUpdate, array("ID_DPL" => $id_dpl));

			else:

				if($submitter_email != "" && $submitter_name != ""){

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;

					$get_multi_bod = $this->dpl->get_multi_bod($id_dpl, "request_approve");

					if(count($get_multi_bod) > 0){
						foreach ($get_multi_bod as $key => $value) {
							$id_approval = $value['ID'];
							$this->crud->update("TRX_APPROVAL_DPL", array("STATUS" => NULL), $id_approval);
						}
					}
					
					// $this->_email_approval($recipient, $id_dpl, $status, $remark);
				}
				$dataFsUpdate['STATUS_DESCRIPTION'] = ucfirst($status)." by ".get_user_data($this->session->userdata('user_id'));
				$dataFsUpdate['STATUS'] = $status;
				$this->crud->update("DPL", $dataFsUpdate , array("ID_DPL" => $id_dpl));

			endif;

			$log_info = $id_dpl . " - " . $dataFsUpdate['STATUS_DESCRIPTION'];

			log_message('info', $log_info);

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		echo json_encode($result);
	}

	public function redirect_to_fs($id_fs_enc){


		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "feasibility-study/detail/".$id_fs_enc);
			redirect('login', 'refresh');
		}

		// $decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($id_fs_enc));
		$decrypt = decrypt_string($id_fs_enc, true);
		$id_fs   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("FS_BUDGET", array("ID_FS" => $id_fs));

		if($check_exist > 0){

			$get_fs_budget = $this->crud->read_by_param("FS_BUDGET", array("ID_FS" => $id_fs));

			$data['title']          = "FS ".$get_fs_budget['FS_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/feasibility_study_view";
			
			$data['id_fs']          = $id_fs;
			$data['id_fs_enc']      = $id_fs_enc;
			$data['fs_number']      = $get_fs_budget['FS_NUMBER'];
			$data['fs_name']        = $get_fs_budget['FS_NAME'];
			$data['fs_description'] = $get_fs_budget['FS_DESCRIPTION'];
			$data['fs_date']        = dateFormat($get_fs_budget['FS_DATE'], 5, false);
			$data['fs_amount']      = number_format($get_fs_budget['NOMINAL_FS'],0,',','.');
			$data['fs_currency']    = $get_fs_budget['CURRENCY'];
			$data['fs_rate']        = number_format($get_fs_budget['CURRENCY_RATE'],0,',','.');
			$data['fs_directorat']  = $get_fs_budget['ID_DIR_CODE'];
			$data['fs_division']    = $get_fs_budget['ID_DIVISION'];
			$data['fs_unit']        = $get_fs_budget['ID_UNIT'];
			$data['fs_submitter']   = $get_fs_budget['SUBMITTER'];
			$data['fs_jabatan_sub'] = $get_fs_budget['JABATAN_SUBMITER'];
			$data['fs_attachment']  = $get_fs_budget['DOCUMENT_ATTACHMENT'];
			$data['fs_status']      = ($get_fs_budget['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_fs_budget['STATUS']);
			$data['fs_status_desc'] = $get_fs_budget['STATUS_DESCRIPTION'];
			$data['fs_last_update'] = dateFormat($get_fs_budget['UPDATED_DATE'], "with_day", false);

			$get_approval = $this->feasibility_study->get_approval_by_fs($id_fs, false);

			$approval = array();
			$approval_remark = array();

			foreach ($get_approval as $key => $value) {
				$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['CATEGORY']);
				if(!empty($value['REMARK'])){
					$approval_remark[] = $value;
				}
			}
			$data['fs_approval'] = $approval;

			if(count($approval_remark) > 0){
				foreach ($approval_remark as $key => $v)
				{
			    	$sort[$key] = strtotime($v['UPDATED_DATE']);
				}
				array_multisort($sort, SORT_DESC, $approval_remark);

				$data['fs_approval_remark'] = $approval_remark[0]['REMARK'];
			}

			$attachment = array();

			if($get_fs_budget['DOCUMENT_ATTACHMENT']){
				$attachment[] = array(
									"FILE_NAME"     => $get_fs_budget['DOCUMENT_ATTACHMENT'],
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/".$get_fs_budget['DOCUMENT_ATTACHMENT'], true),
									"DATE_UPLOADED" => strtotime($get_fs_budget['CREATED_DATE']),
									"UPLOADED_BY"   => $get_fs_budget['SUBMITTER']
									);
			}

			if($get_fs_budget['BOC_ATTACHMENT']){
				$data_file  = json_decode($get_fs_budget['BOC_ATTACHMENT']);
				$attachment[] = array(
									"FILE_NAME"     => $data_file->FILE,
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/boc_attachment/".$data_file->FILE, true),
									"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
									"UPLOADED_BY"   => $data_file->UPLOADED_BY
									);
			}
			if($get_fs_budget['RISK_ATTACHMENT']){
				$data_file  = json_decode($get_fs_budget['RISK_ATTACHMENT']);
				$attachment[] = array(
									"FILE_NAME"     => $data_file->FILE,
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/risk_attachment/".$data_file->FILE, true),
									"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
									"UPLOADED_BY"   => $data_file->UPLOADED_BY
									);
			}
			if($get_fs_budget['CFO_ATTACHMENT']){
				$data_file  = json_decode($get_fs_budget['CFO_ATTACHMENT']);
				$attachment[] = array(
									"FILE_NAME"     => $data_file->FILE,
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/cfo_attachment/".$data_file->FILE, true),
									"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
									"UPLOADED_BY"   => $data_file->UPLOADED_BY
									);
			}

			$data['fs_attachment'] = $attachment;

			$get_comment = $this->feasibility_study->get_comment_history($id_fs, false);
			$data['comment_history'] = $get_comment;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Feasibility Study", "link" => base_url($this->module_url), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FS Not Exist');
			redirect($this->module_url);

		}

	}


    private function _email_approval($recipient, $id_fs, $type, $approval_remark="", $id_approval=0, $is_review=false){

		$get_fs      = $this->feasibility_study->get_fs_for_email($id_fs);

		$directorat    = get_directorat($get_fs['ID_DIR_CODE']);
		$division      = get_division($get_fs['ID_DIVISION']);
		$unit          = get_unit($get_fs['ID_UNIT']);
		$category      = $get_fs['CAPEX_OPEX'];
		$tibe          = $get_fs['TRIBE_USECASE'];
		$rkap          = $get_fs['RKAP_DESCRIPTION'];
		$proc_type     = $get_fs['PROC_TYPE'];
		$currency      = ($get_fs['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_fs['NOMINAL_FS'],0,',','.');
		$fs_name       = $get_fs['FS_NAME'];
		$fs_number     = $get_fs['FS_NUMBER'];
		$description   = $get_fs['FS_DESCRIPTION'];
		$attachment    = $get_fs['DOCUMENT_ATTACHMENT'];	
		$currency_rate = ($get_fs['CURRENCY'] == "IDR") ? $get_fs['CURRENCY'] : $get_fs['CURRENCY'] ."/". number_format($get_fs['CURRENCY_RATE'],0,'.',',');

		$data['email_recipient']  = $recipient['name'];

		$action_name = get_user_data($this->session->userdata('user_id'));

		if($approval_remark != ""){
			$data['approval_remark'] = $approval_remark;
		}

		if($type == "request_approve"):
			$email_preview = "A new justification request $fs_number has been <b>approved</b> by $action_name and need for your approval";
			$file_view  = "request_approval_approved";
			$tile_first = "Approval Request";
		elseif($type == "request_review"):
			$email_preview = "A new justification request $fs_number has been <b>approved</b> by $action_name and need for your review";
			$file_view  = "request_approval_reviewed";
			$tile_first = "Review Request";
		else:
			$email_preview = "Your justification request $fs_number has been <b>$type</b> by ".$action_name;
			$file_view  = $type;
			$tile_first = ucfirst($type);
		endif;
		$data['action_name'] = $action_name;
		$data['email_preview'] = $email_preview;
		$data['fs_number'] = $fs_number;
		$data['fs_link'] = base_url("feasibility-study/detail/").encrypt_string($id_fs, true);
		$data['fs_detail'] = "The justification details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>Justification Name</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$fs_name</b></td>
										</tr>
										<tr>
											<td>Directorate</td>
											<td>:</td>
											<td><b>$directorat</b></td>
										</tr>
										<tr>
											<td>Division</td>
											<td>:</td>
											<td><b>$division</b></td>
										</tr>
										<tr>
											<td>Unit</td>
											<td>:</td>
											<td><b>$unit</b></td>
										</tr>
										<tr>
											<td>Category</td>
											<td>:</td>
											<td><b>$category</b></td>
										</tr>
										<tr>
											<td>Tribe/Use case</td>
											<td>:</td>
											<td><b>$tibe</b></td>
										</tr>
										<tr>
											<td>Type</td>
											<td>:</td>
											<td><b>$proc_type</b></td>
										</tr>
										<tr>
											<td>RKAP Name</td>
											<td>:</td>
											<td><b>$rkap</b></td>
										</tr>
										<tr>
											<td>$currency</td>
											<td>:</td>
											<td><b>$currency_rate</b></td>
										</tr>
										<tr>
											<td>Amount</td>
											<td>:</td>
											<td><b>$amount</b></td>
										</tr>
										<tr>
											<td>Description</td>
											<td>:</td>
											<td><b>$description</b></td>
										</tr>
									</tbody>
								</table>";

		// $data['approval_link'] = base_url("budget/approval/").base64url_encode($id_fs."-".$id_approval.$this->config->item('encryption_key'));
		$data['approval_link'] = base_url("budget/approval/").encrypt_string($id_fs."-".$id_approval, true);
		$data['approval_link_all'] = base_url("budget/approval");
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = $tile_first . " - " . $fs_number . " - " . $fs_name;
		$body       = $this->load->view('email/'.$file_view, $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }



}

/* End of file Approval_ctl.php */
/* Location: ./application/controllers/feasibility_study/Approval_ctl.php */