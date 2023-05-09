<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_dpl_ctl extends CI_Controller {

	private $module_name = "dpl",
			$pic_email = "",
			$user_cc  = array(),
			$module_title = "Dokumen Penunjukan Langsung",
			$module_url   = "dpl";

	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Dpl_mdl', 'dpl');

		$list_cc['susanto'] = 'susanto_wu@linkaja.id';
		$list_cc['dita'] = 'dita_lestari@linkaja.id';
		$list_cc['wahyu'] = 'wahyu_bijaksana@linkaja.id';

		$this->user_cc = $list_cc;
		$this->user_email = $this->session->userdata('email');
		
		$is_delegate    = $this->session->userdata('is_delegate');
      	$this->pic_email = ($is_delegate !== false) ? $is_delegate : $this->session->userdata('email');

	}

	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", $this->module_url."/approval");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->dpl->check_is_approval_dpl($this->pic_email);

		if($check_is_approval){
			/*$get_dpl_for_approval = $this->dpl->get_dpl_for_approval($this->session->userdata('email'));

			$id_dpl = array();

			if($get_dpl_for_approval){
				foreach ($get_dpl_for_approval as $value) {
					$id_dpl[] = $value['ID_DPL'];
				}
			}*/

			$data['title']          = "All DPL";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/dpl_approval";
			
			// $data['id_dpl']  = json_encode($id_dpl);

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


    public function approval_dpl(){


		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "dpl/verification");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->dpl->check_is_verifier($this->pic_email);


		if($check_is_approval){

			$data['title']          = "All DPL Verification";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/dpl_verify";

			$level_verifier         = $this->dpl->get_level_verifier($this->pic_email);
			$data['level_verifier'] = $level_verifier;
			
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


	public function approval_single($id_approval){


		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "dpl/approval/".$id_approval);
			redirect('login');
		}

		$decrypt = decrypt_string($id_approval, true);
		
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify DPL');
			redirect('/');
		}

		$id_dpl       = $exp[0];
		$id_approval = $exp[1];
		$get_trx     = $this->crud->read_by_id("TRX_APPROVAL_DPL", array("ID" => $id_approval, "IS_ACTIVE" => 1));
		$get_approval = $this->crud->read_by_id("MASTER_APPROVAL", array("ID_APPROVAL" => $get_trx['ID_APPROVAL']));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify justification');
			redirect('/dpl/approval');
		}

		$check_exist = $this->crud->check_exist("DPL", array("ID_DPL" => $id_dpl));

		if($check_exist > 0){

			$pic_email     = $this->pic_email;
			$get_dpl = $this->dpl->get_dpl_to_approve_by_id($id_dpl, $pic_email);

			$data['title']          = "DPL";
			$data['module']         = "datatable";
			$data['template_page']  = "dpl/dpl_approval_single";
			
			$data['id_dpl']          = $id_dpl;
			$data['dpl_number']        = $get_dpl['DPL_NUMBER'];
			$data['directorate']     = $get_dpl['ID_DIR_CODE'];
			$data['divisi']        	 = $get_dpl['ID_DIVISION'];
			$data['unit']        	 = $get_dpl['ID_UNIT'];
			$data['id_fs']        	 = $get_dpl['ID_FS'];
			$data['uraian']        	 = $get_dpl['URAIAN'];
			if($get_dpl['KEGIATAN_PENGADAAN'] == 1){
				$data['pengadaan']       = "Sudah dilaksanakan sebelum proses Pengadaan dilakukan oleh Procurement";
			}else{
				$data['pengadaan']       = "Belum dilaksanakan";
			}
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
			$data['dpl_status']      = $get_dpl['STATUS'];
			$data['dpl_last_update'] = $get_dpl['STATUS_DESCRIPTION'];
			$data['fs_link']         = base_url("feasibility-study/") . encrypt_string($get_dpl['ID_FS'], true);

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

			$data['dpl_verificator'] = $verificator;
			$data['last_update'] = $get_dpl['STATUS_DESCRIPTION'];
			$data['disabled_act']     = ($this->pic_email != $this->session->userdata('email') ) ? true : false;


			$data['dpl_status']      = ($get_dpl['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_dpl['STATUS']);
			$data['dpl_status_desc'] = $get_dpl['STATUS_DESCRIPTION'];
			$last_update            = ($get_dpl['UPDATED_DATE']) ? $get_dpl['UPDATED_DATE'] : $get_dpl['CREATED_DATE'];
			$data['dpl_last_update'] = dateFormat($last_update, "with_day", false);
			$data['trx_status']     = $get_dpl['TRX_STATUS'];
			$data['trx_date']       = dateFormat($get_dpl['TRX_DATE'], "with_day", false);

			$data['level']          = $get_dpl['LEVEL'];

			$breadcrumb[] = array( "name" => "Beranda", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "DPL", "link" => base_url('dpl'), "class" => "" );
			$breadcrumb[] = array( "name" => $get_dpl['DPL_NUMBER'], "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
	}



    public function verify_single($id_approval){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "dpl/verification/".$id_approval);
			redirect('login');
		}


		$decrypt = decrypt_string($id_approval, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify dpl');
			redirect('/');
		}

		$id_dpl       = $exp[0];
		$email_check = $exp[1];

		if(strtolower($email_check) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify justification');
			redirect('/dpl/verification');
		}

		$check_exist = $this->crud->check_exist("DPL", array("ID_DPL" => $id_dpl));

		if($check_exist > 0){

			$pic_email = $this->pic_email;
			$pic_level = "";
			if($this->session->userdata('data_employee')){
				$pic_level = $this->session->userdata('data_employee')['level'];
			}
			$get_dpl = $this->crud->read_by_param("DPL", array("ID_DPL" => $id_dpl));
			$level_verifier = $this->dpl->get_level_verifier($this->pic_email);

			$data['title']           = "DPL ".$get_dpl['DPL_NUMBER'];
			$data['module']          = "datatable";
			$data['template_page']   = $this->module_name."/dpl_verify_single";
			
			$data['id_dpl']      = $id_dpl;
			$data['fs_link']     = base_url("feasibility-study/") . encrypt_string($get_dpl['ID_FS'], true);
			$data['dpl_number']  = $get_dpl['DPL_NUMBER'];
			$data['directorate'] = $get_dpl['ID_DIR_CODE'];
			$data['divisi']      = $get_dpl['ID_DIVISION'];
			$data['unit']        = $get_dpl['ID_UNIT'];
			$data['id_fs']       = $get_dpl['ID_FS'];
			$data['uraian']      = $get_dpl['URAIAN'];
			if($get_dpl['KEGIATAN_PENGADAAN'] == 1){
				$data['pengadaan']       = "Sudah dilaksanakan sebelum proses Pengadaan dilakukan oleh Procurement";
			}else{
				$data['pengadaan']       = "Belum dilaksanakan";
			}
			$data['date_from']       = dateFormat($get_dpl['DATE_FROM'], 5, false);
			$data['date_to']       	 = dateFormat($get_dpl['DATE_TO'], 5, false);
			$data['vendor']       	 = $get_dpl['REKANAN'];
			$data['pic']       	 	 = $get_dpl['PIC_USER'];
			$data['jabatan_sub']     = $get_dpl['JABATAN'];
			$data['evaluasi']     	 = $get_dpl['EVALUASI_REKANAN'];
			$data['alasan']          = $get_dpl['ALASAN_PENUNJUKAN'];
			$data['keuntungan']      = $get_dpl['KEUNTUNGAN_PENUNJUKAN'];
			$data['resiko']          = $get_dpl['RESIKO_PENUNJUKAN'];
			$data['dpl_submitter']   = $get_dpl['PIC_USER'];
			$data['dpl_jabatan_sub'] = $get_dpl['JABATAN'];

			$tujuanData[] = 'Revenue Generation ';
			$tujuanData[] = 'Increase Subscriber Base ';
			$tujuanData[] = 'Cost Leadership/Cost Saving/Cost Effectiveness';
			$tujuanData[] = 'Brand Awareness';
			$tujuanData[] = 'Network Capacity /Coverage';
			$tujuanData[] = 'Customer Process';
			$tujuanData[] = 'Corporate Images/ Value';
			$tujuanData[] = 'Legal/Regulatory/Compliance/Risk Management';
			$tujuanData[] = 'Skill/Professional/Training/Management/HCM';
			$tujuanData[] = 'Others: Industry Intelligence';

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

			$kriteriaData[] = 'Penyedia/Supplier/Rekanan Tunggal';
			$kriteriaData[] = 'Lanjutan dari Pekerjaan sebelumnya yang tidak terelakkan';
			$kriteriaData[] = 'Critical (Penting & Mendesak)';

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

			/*$get_approval = $this->dpl->get_approval_by_dpl($id_dpl);

			$approval = array();
			$approval_remark = array();

			foreach ($get_approval as $key => $value) {
				$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['CATEGORY']);
				if(!empty($value['REMARK'])){
					$approval_remark[] = $value;
				}
			}*/
			/*if($get_dpl['VERIFIKASI_RISK_DATE']){
				$last_update = "Verified by " . $get_dpl['VERIFIKASI_RISK_NAME'] . " at " . dateFormat($get_dpl['VERIFIKASI_RISK_DATE'], 'fintool', false);
			}elseif($get_dpl['VERIFIKASI_PROC_DATE']){
				$last_update = "Verified by " . $get_dpl['VERIFIKASI_PROC_NAME'] . " at " . dateFormat($get_dpl['VERIFIKASI_PROC_DATE'], 'fintool', false);
			}else{
				$last_update = "Submitted by " . $get_dpl['PIC_USER'] . " at " . dateFormat($get_dpl['CREATED_DATE'], 'fintool', false);
			}*/
			$data['last_update'] = $get_dpl['STATUS_DESCRIPTION'];
			$level_verifier = ($level_verifier == "HoG Procurement") ? 1 : 2;
			$data['level'] = $level_verifier;
			$confirmed = false;

			if($level_verifier == 1){
				if($get_dpl['STATUS_VERIF'] > 0){
					$confirmed = true;
				}else{
					if($get_dpl['VERIFIKASI_PROC'] == "rejected" || $get_dpl['VERIFIKASI_PROC'] == "returned"){
						$confirmed = true;
					}
				}
			}
			if($level_verifier == 2){
				if($get_dpl['STATUS_VERIF'] > 1){
					$confirmed = true;
				}else{
					if($get_dpl['VERIFIKASI_RISK'] == "rejected" || $get_dpl['VERIFIKASI_RISK'] == "returned"){
						$confirmed = true;
					}
				}
			}
			$data['confirmed'] = $confirmed;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All DPL Verification", "link" => base_url("dpl/verification"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FS Not Exist');
			redirect($this->module_url);

		}

	}

	public function load_dpl_to_verify(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$level     = $this->input->post('level');
		$pic_email = $this->pic_email;

		$get_all         = $this->dpl->get_dpl_to_verify($pic_email, $status, $level);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'            => $number,
						'id'            => $value['ID_DPL'],
						'id_dpl'        => encrypt_string($value['ID_DPL'], true),
						'id_dpl_verify' => encrypt_string($value['ID_DPL']."-".$pic_email, true),
						'directorat'    => get_directorat($value['ID_DIR_CODE']),
						'division'      => get_division($value['ID_DIVISION']),
						'unit'          => get_unit($value['ID_UNIT']),
						'justification' => $value['FS_NUMBER'] ." - " . $value['FS_NAME'],
						'dpl_number'    => $value['DPL_NUMBER'],
						'status'        => $value['STATUS']
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



	public function load_dpl_to_approve(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$pic_email = $this->pic_email;

		$get_all         = $this->dpl->get_dpl_to_approve($pic_email, $status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {
/*
				$row[] = array(
						'no'            => $number,
						'id'            => $value['ID_DPL'],
						'id_dpl'        => encrypt_string($value['ID_DPL'], true),
						'id_dpl_verify' => encrypt_string($value['ID_DPL']."-".$pic_email, true),
						'directorat'    => get_directorat($value['ID_DIR_CODE']),
						'division'      => get_division($value['ID_DIVISION']),
						'unit'          => get_unit($value['ID_UNIT']),
						'justification' => $value['FS_NUMBER'] ." - " . $value['FS_NAME'],
						'dpl_number'    => $value['DPL_NUMBER'],
						'status'        => $value['STATUS']
						);*/

				$status_description = $value['STATUS_DESCRIPTION'];

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['ID_DPL'],
						'id_dpl'             => encrypt_string($value['ID_DPL'], true),
						'id_dpl_approval'    => encrypt_string($value['ID_DPL']."-".$value['ID_DPL_APPROVAL'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'dpl_number'         => $value['DPL_NUMBER'],
						'justification'      => $value['FS_NUMBER'] ." - " . $value['FS_NAME'],
						'status'             => $value['STATUS'],
						'level'              => $value['LEVEL'],
						'status_description' => $status_description
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

		$id_dpl          = $this->input->post('id_dpl');
		$level           = $this->input->post('level');
		$remark          = $this->input->post('remark');
		$approval        = $this->input->post('approval');
		$category        = $this->input->post('category');


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
		$update = $this->crud->update("TRX_APPROVAL_DPL", $data, array("ID_DPL" => $id_dpl, "IS_ACTIVE" => 1, "LEVEL" => $level));

		if($update !== -1){

			$get_submitter   =  $this->dpl->get_submitter_by_id_dpl($id_dpl);
			$submitter_email = "";
			$submitter_name  = "";

			$dataFsUpdate = array();

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : "";
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
			$next_step = false;
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

					$next_step = true;

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

							if($next_step){
								$this->_email_approval_dpl($recipient, $id_dpl, $remark, $id_approval, "approved");
								$this->crud->update("TRX_APPROVAL_DPL", array("STATUS" => "request_approve"), $id_approval);
							}
						}
					}
					else{

						if($send_to_next){
							$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
							$recipient['name']  = $get_next_aprover['PIC_NAME'];

							$this->_email_approval_dpl($recipient, $id_dpl, $remark, $id_approval, "approved");
							$this->crud->update("TRX_APPROVAL_DPL", array("STATUS" => "request_approve"), $id_approval);
						}

					}

				}
				else{

					if($submitter_email != "" && $submitter_name != ""){

						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;

						$cc_email = $this->user_cc;
						$email_cc[] = $cc_email['susanto'];
						$email_cc[] = $cc_email['dita'];
						$email_cc[] = $cc_email['wahyu'];
						$email_cc[] = $submitter_email;
						if(count($email_cc) > 0){
							$recipient['email_cc'] = $email_cc;
						}

						if($send_email){
							$this->_email_approval_dpl($recipient, $id_dpl, $remark, 0, "full_approved");
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
					
					$this->_email_dpl_reject($recipient, $id_dpl, $status, $remark);

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


	public function action_verification(){

		$id_dpl = $this->input->post('id_dpl');
		$id_fs  = $this->input->post('id_fs');
		$level  = $this->input->post('level');
		$remark = $this->input->post('remark');
		$verify = $this->input->post('verify');
		$action_name = get_user_data($this->session->userdata('user_id'));

		$result['status'] = false;
		$result['messages'] = "Failed to $verify justification";

		if($verify == "reject"):
			$status = "rejected";
		elseif($verify == "return"):
			$status = "returned";
		else:
			$status = "verified";
		endif;

		if($level == 1){
			if($status == "rejected" || $status == "returned"){
				$data['STATUS'] = $status;
			}
			$data['STATUS_VERIF']          = ($status == "verified") ? 1 : 0;
			$data['VERIFIKASI_PROC']       = $status;
			$data['VERIFIKASI_PROC_NAME']  = $action_name;
			$data['VERIFIKASI_PROC_DATE']  = date("Y-m-d H:i:s");
			$data['VERIFIKASI_PROC_NOTES'] = $remark;

		}else{
			$data['STATUS']                = ($status == "rejected" || $status == "returned") ? $status : "request_approve";
			$data['STATUS_VERIF']          = ($status == "verified") ? 2 : 1;
			$data['VERIFIKASI_RISK']       = $status;
			$data['VERIFIKASI_RISK_NAME']  = $action_name;
			$data['VERIFIKASI_RISK_DATE']  = date("Y-m-d H:i:s");
			$data['VERIFIKASI_RISK_NOTES'] = $remark;

		}

		$status_desc = ucfirst($status) ." by $action_name at ".dateFormat(time(), "fintool");
		$data['STATUS_DESCRIPTION'] = $status_desc;

		$update = $this->crud->update("DPL", $data, array("ID_DPL" => $id_dpl));

		$email_to = "";
		$email_name = "";

		if($update !== -1){

			$get_submitter   =  $this->dpl->get_submitter_by_id_dpl($id_dpl);
			$submitter_email = "";
			$submitter_name  = "";

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : "";
				$submitter_name  = $get_submitter['PIC_USER'];
			}

			if($status == "verified"):

				if($level == 1){

					$get_risk_verifier = $this->dpl->get_risk_verifier($id_dpl);

					if($get_risk_verifier){
						$email_to   = $get_risk_verifier['PIC_EMAIL'];
						$email_name = $get_risk_verifier['PIC_NAME'];
					}
					if($email_to !="" && $email_name !=""){

						$submitter_email = $submitter_email;

						$cc_email = $this->user_cc;
						$email_cc[] = $cc_email['susanto'];
						$email_cc[] = $cc_email['dita'];
						$email_cc[] = $cc_email['wahyu'];
						$email_cc[] = $submitter_email;
						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}

						$recipient['email'] = $email_to;
						$recipient['name']  = $email_name;

						$this->_email_request_verification($recipient, $id_dpl, $remark);
					}
				}else{
					$get_trx_to_approve = $this->dpl->get_approver_first($id_dpl);

					if($get_trx_to_approve){
						$recipient['email'] = $get_trx_to_approve['PIC_EMAIL'];
						$recipient['name'] = $get_trx_to_approve['PIC_NAME'];


						$cc_email = $this->user_cc;
						$email_cc[] = $cc_email['susanto'];
						$email_cc[] = $cc_email['dita'];
						$email_cc[] = $cc_email['wahyu'];
						$email_cc[] = $submitter_email;
						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}

						$this->_email_approval_dpl($recipient, $id_dpl, $remark, $get_trx_to_approve['ID']);
						$dataSet['STATUS'] = 'request_approve';
						$this->crud->update('TRX_APPROVAL_DPL', $dataSet, array("ID" => $get_trx_to_approve['ID']));
					}

					// $justif_approve_next = trigger_approve_justif($id_fs, $get_trx_to_approve['ID_APPROVAL']);
				}

			else:

				if($submitter_email != "" && $submitter_name != ""){
					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;

					if($level == 2){
						$get_proc_verifier = $this->dpl->get_proc_verifier($id_dpl);
						if($get_proc_verifier){
							$email_cc[] = $get_proc_verifier['PIC_EMAIL'];
						}
					}

					$cc_email = $this->user_cc;
					$email_cc[] = $cc_email['susanto'];
					$email_cc[] = $cc_email['dita'];
					$email_cc[] = $cc_email['wahyu'];
					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$this->_email_dpl_reject($recipient, $id_dpl, $status, $remark);
				}

			endif;

			$log_info = $id_dpl . " - " . $status_desc;

			log_message('info', $log_info);

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		echo json_encode($result);
	}


    private function _email_dpl_reject($recipient, $id_dpl, $status="", $remark=""){

		$action_name = get_user_data($this->session->userdata('user_id'));

		$get_dpl      = $this->dpl->get_dpl_for_email($id_dpl);

		$dpl_number     = $get_dpl['DPL_NUMBER'];
		$fs_number      = $get_dpl['FS_NUMBER'];
		$fs_name        = $get_dpl['FS_NAME'];
		$rekanan        = $get_dpl['REKANAN'];
		$submitter      = $get_dpl['PIC_USER'];
		$email_verifier = $recipient['email'];
		$nominal_fs     = number_format($get_dpl['NOMINAL_FS'],0,',','.');
		$kriteriaData   = getKriteriaDPL();

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
		$kriteria = implode(", ", $kriteriaFix);

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = "There's new Dokumen Penunjukan Langsung $dpl_number related to Justification $fs_number - $fs_name has been $status";
		$data['notes'] = $remark;

		$data['email_body'] = "
								There's new Dokumen Penunjukan Langsung <b>$dpl_number</b> related to Justification <b>$fs_number - $fs_name</b> has been $status by $action_name.
								<br>
								<br>
								The Dokumen Penunjukan Langsung details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>DPL Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$dpl_number</b></td>
										</tr>
										<tr>
											<td>Supplier Suggestion </td>
											<td>:</td>
											<td><b>$rekanan</b></td>
										</tr>
									</tbody>
								</table>
								";
		$encrypt_link = encrypt_string($id_dpl, true);
		$data['dpl_link'] = base_url("dpl/".$encrypt_link);

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = ucfirst($status) . " DPL - " . $dpl_number;
		$body       = $this->load->view('email/dpl_reject', $data, TRUE);

		$send = sendemail($to, $subject, $body, $cc);

		return $send;
    }


    private function _email_approval_dpl($recipient, $id_dpl, $remark="", $id_approval=0, $type="request_approve"){

		$get_dpl      = $this->dpl->get_dpl_for_email($id_dpl);

		$action_name = get_user_data($this->session->userdata('user_id'));
		$dpl_number     = $get_dpl['DPL_NUMBER'];
		$fs_number      = $get_dpl['FS_NUMBER'];
		$fs_name        = $get_dpl['FS_NAME'];
		$rekanan        = $get_dpl['REKANAN'];
		$submitter      = $get_dpl['PIC_USER'];
		$email_verifier = $recipient['email'];
		$nominal_fs     = number_format($get_dpl['NOMINAL_FS'],0,',','.');
		$kriteriaData   = getKriteriaDPL();

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
		$kriteria = implode(", ", $kriteriaFix);

		$approval_link = base_url("dpl/approval/").encrypt_string($id_dpl."-".$id_approval, true);

		if($type == "request_approve"):
			$email_preview = "There's new Dokumen Penunjukan Langsung $dpl_number related to Justification $fs_number - $fs_name waiting for your approval";
			$file_view  = "email/dpl_request";
			$tile_first = "Request Approval";
		elseif($type == "approved"):
			$email_preview = "There's new Dokumen Penunjukan Langsung $dpl_number related to Justification $fs_number - $fs_name has been approved by $action_name and waiting for your approval";
			$file_view  = "email/dpl_request";
			$tile_first = "Request Approval";
		else:
			$email_preview = "Your Dokumen Penunjukan Langsung $dpl_number related to Justification $fs_number - $fs_name has been approved by ".$action_name;
			$file_view  = "email/dpl_request";
			$tile_first = "DPL Approved";;
		endif;

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = $email_preview;
		$data['notes'] = $remark;

		$data['email_body'] = $email_preview. "
								<br>
								<br>
								The Dokumen Penunjukan Langsung details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>DPL Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$dpl_number</b></td>
										</tr>
										<tr>
											<td>Supplier Suggestion </td>
											<td>:</td>
											<td><b>$rekanan</b></td>
										</tr>
										<tr>
											<td>Amount</td>
											<td>:</td>
											<td><b>$nominal_fs</b></td>
										</tr>
										<tr>
											<td>Kriteria Penunjukan Langsung</td>
											<td>:</td>
											<td><b>$kriteria</b></td>
										</tr>
									</tbody>
								</table>
								";
		$data['approval_link'] = $approval_link;
		$data['approval_link_all'] = base_url("dpl/approval");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$subject    = $tile_first . " - " . $dpl_number;
		$body       = $this->load->view($file_view, $data, TRUE);
		// $attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc);

		return $send;
    }



    private function _email_request_verification($recipient, $id_dpl, $remark){

		$get_dpl      = $this->dpl->get_dpl_for_email($id_dpl);

		$dpl_number     = $get_dpl['DPL_NUMBER'];
		$fs_number      = $get_dpl['FS_NUMBER'];
		$fs_name        = $get_dpl['FS_NAME'];
		$rekanan        = $get_dpl['REKANAN'];
		$submitter      = $get_dpl['PIC_USER'];
		$email_verifier = $recipient['email'];
		$nominal_fs     = number_format($get_dpl['NOMINAL_FS'],0,',','.');
		$kriteriaData   = getKriteriaDPL();

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
		$kriteria = implode(", ", $kriteriaFix);

		$approval_link = base_url("dpl/verification/").encrypt_string($id_dpl."-".$email_verifier, true);

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = "There's new Dokumen Penunjukan Langsung $dpl_number related to Justification $fs_number - $fs_name waiting for your verification";
		$data['notes'] = $remark;

		$data['email_body'] = "
								There's new Dokumen Penunjukan Langsung <b>$dpl_number</b> related to Justification <b>$fs_number - $fs_name</b> waiting for your verification.
								<br>
								<br>
								The Dokumen Penunjukan Langsung details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>DPL Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$dpl_number</b></td>
										</tr>
										<tr>
											<td>Supplier Suggestion </td>
											<td>:</td>
											<td><b>$rekanan</b></td>
										</tr>
										<tr>
											<td>Amount</td>
											<td>:</td>
											<td><b>$nominal_fs</b></td>
										</tr>
										<tr>
											<td>Kriteria Penunjukan Langsung</td>
											<td>:</td>
											<td><b>$kriteria</b></td>
										</tr>
									</tbody>
								</table>
								";
		$data['approval_link'] = $approval_link;
		$data['approval_link_all'] = base_url("dpl/verification");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$subject    = "New DPL Request for Verification - $dpl_number";
		$body       = $this->load->view('email/dpl_request', $data, TRUE);
		// $attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc);

		return $send;
	}




}

/* End of file Approval_dpl_ctl.php */
/* Location: ./application/controllers/dpl/Approval_dpl_ctl.php */