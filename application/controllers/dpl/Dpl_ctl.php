<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dpl_ctl extends CI_Controller {

	private $module_name = "dpl",
			$user_email = "",
			$user_cc  = array(),
			$module_title = "Dokumen Penunjukan Langsung",
			$module_url   = "dpl";

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		};

		$list_cc['susanto'] = 'susanto_wu@linkaja.id';
		$list_cc['dita'] = 'dita_lestari@linkaja.id';
		$list_cc['wahyu'] = 'wahyu_bijaksana@linkaja.id';

		$this->user_cc = $list_cc;
		$this->user_email = $this->session->userdata('email');

		$this->load->model('Dpl_mdl', 'dpl');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');
		
	}

	public function index()
	{

		$data['title']         = "DOKUMEN PENUNJUKAN LANGSUNG";
		$data['module']        = "datatable";
		$data['template_page'] = "dpl/dpl_inquiry";
		$data['dpl_status']    = get_status_pr();

		$group = $this->session->userdata('group_id');

			foreach ($group as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName['NAME'];
			}

			$data['group_name']    = $group_name;
			
		    $directorat = check_is_bod();
		    $binding    = check_binding();

		    if(count($directorat) > 1 && $binding['binding'] != false){
				$directorat = $binding['data_binding']['directorat'];
		    }

		$data['directorat']   = $directorat;
		$data['binding']      = $binding['binding'];
		$data['data_binding'] = $binding['data_binding'];

		$breadcrumb[] = array( "name" => "Beranda", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "DPL", "link" => "", "class" => "active" );

		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}

	public function create_dpl($justif=false)
	{

		$group_name = get_user_group_data();
		if(!in_array("SU Budget", $group_name)){
			$this->session->set_flashdata('messages', 'Submission DPL Telah Ditutup');
			redirect($this->module_url);
			exit;
		}
		$data['title']         = "DPL Create";
		$data['module']        = "datatable";
		$data['template_page'] = "dpl/dpl_create";

		$group_name = get_user_group_data();

		$data['group_name']    = $group_name;
		
	    $directorat = check_is_bod();
	    $binding    = check_binding();

	    if(count($directorat) > 1 && $binding['binding'] != false){
			$directorat = $binding['data_binding']['directorat'];
	    }

		$data['directorat']   = $directorat;
		$data['binding']      = $binding['binding'];
		$data['data_binding'] = $binding['data_binding'];
		$data['vendor']       = $this->crud->read("MASTER_VENDOR", "", "NAMA_VENDOR");

		$breadcrumb[] = array( "name" => "Beranda", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "DPL", "link" => base_url('dpl'), "class" => "" );
		$breadcrumb[] = array( "name" => "Create", "link" => "", "class" => "active" );

		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}

	public function create_dpl_justif()
	{
		$data['title']         = "DPL Create";
		$data['module']        = "datatable";
		$data['template_page'] = "dpl/dpl_create_justif";

		$group_name = get_user_group_data();

		$submitter          = $this->session->userdata('identity');
		$get_last_justif    = $this->dpl->get_last_justif($submitter);
		
		$data['justif']     = $get_last_justif;
		$data['group_name'] = $group_name;
		$data['vendor'] = $this->crud->read("MASTER_VENDOR", "", "NAMA_VENDOR");

		$breadcrumb[] = array( "name" => "Beranda", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "DPL", "link" => base_url('dpl'), "class" => "" );
		$breadcrumb[] = array( "name" => "Create", "link" => "", "class" => "active" );

		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}

	public function save_dpl() {

		$directorate        = $this->input->post('directorate');
		$division           = $this->input->post('division');
		$unit               = $this->input->post('unit');
		$id_fs              = $this->input->post('id_fs');
		$uraian             = $this->input->post('uraian');
		$kegiatan_pengadaan = $this->input->post('kegiatan_pengadaan');
		$date_from          = $this->input->post('date_from');
		$date_to            = $this->input->post('date_to');
		$vendor             = $this->input->post('vendor');
		$pic                = $this->input->post('pic');
		$jabatan_sub        = $this->input->post('jabatan_sub');
		$evaluasi           = $this->input->post('evaluasi');
		$alasan             = $this->input->post('alasan');
		$keuntungan         = $this->input->post('keuntungan');
		$resiko             = $this->input->post('resiko');
		$tujuan_pengadaan   = $this->input->post('tujuan_pengadaan');
		$kriteria           = $this->input->post('kriteria');
		$justif_amount      = $this->input->post('justif_amount');
		$is_new             = $this->input->post('is_new');

		if($date_from != ""){
			$exp_date_from = explode("-", $date_from);
			$date_from = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		}

		if($date_to != ""){
			$exp_date_to = explode("-", $date_to);
			$date_to = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_dir        = $this->crud->read_by_param("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $directorate));
		$id_dir_code    = $get_dir['ID_DIR_CODE'];

		$check_dpl_exist = $this->crud->check_exist("DPL", array("ID_DIR_CODE" => $id_dir_code));
		$month     = date("m");
		$year      = date("Y");
		$number    = sprintf("%'03d", 1);
		$dpl_number = "DPL/".$get_dir['DIRECTORAT_CODE']."/".$number."/".date("m")."/".date("Y");

		if($check_dpl_exist > 0){
			$last_dpl_number = $this->dpl->get_last_dpl_number($id_dir_code);
			$exp_dpl_number  = explode("/",$last_dpl_number);
			$dir_code        = $exp_dpl_number[1];
			$number          = (int) $exp_dpl_number[2];
			
			$number += 1;
			$number = sprintf("%'03d", $number);
			$dpl_number = "DPL/".$dir_code."/".$number."/".$month."/".$year;
		}

		$status_desc = "Submitted by " .$pic . " at ". dateFormat(time(), "fintool");

		$data = array(
						"DPL_NUMBER"            => $dpl_number,
						"ID_DIR_CODE"           => $directorate,
						"ID_DIVISION"           => $division,
						"ID_UNIT"               => $unit,
						"ID_FS"                 => $id_fs,
						"URAIAN"                => $uraian,
						"KEGIATAN_PENGADAAN"    => $kegiatan_pengadaan,
						"REKANAN"               => $vendor,
						"PIC_USER"              => $pic,
						"JABATAN"               => $jabatan_sub,
						"EVALUASI_REKANAN"      => $evaluasi,
						"ALASAN_PENUNJUKAN"     => $alasan,
						"KEUNTUNGAN_PENUNJUKAN" => $keuntungan,
						"RESIKO_PENUNJUKAN"     => $resiko,
						"STATUS_DESCRIPTION"    => $status_desc
					);
	

		if($date_from != ""){
			$data['DATE_FROM'] = $date_from;
		}

		if($date_to != ""){
			$data['DATE_TO']   = $date_to;
		}

		if($tujuan_pengadaan != ""){
			$tujuan_arr = array();
			foreach ($tujuan_pengadaan as $value) {
				$val = strtolower(str_replace(" ", "_", $value));
				$tujuan_arr[$val] = true;
			}
			$data['TUJUAN_PENGADAAN'] = (is_array($tujuan_arr)) ? json_encode($tujuan_arr) : '';
		}

		if($kriteria != ""){
			$kriteria_arr = array();
			foreach ($kriteria as $value) {
				$val = strtolower(str_replace(" ", "_", $value));
				$kriteria_arr[$val] = true;
			}
			$data['KRITERIA'] = (is_array($kriteria_arr)) ? json_encode($kriteria_arr) : '';
		}

		$total_amount = (int) $justif_amount;
	
		$data['STATUS']   = "wait_verify";

		$email_to   = "";
		$email_name = "";
		$email_proc = "";
		$email_risk = "";

		$get_hog_proc = $this->feasibility_study->get_data_approval("HoG Procurement");
		$email_to     = $get_hog_proc['PIC_EMAIL'];
		$email_name   = $get_hog_proc['PIC_NAME'];
		$email_proc   = $email_to;

		if($total_amount <= 100000000){
			$get_hog_risk = $this->feasibility_study->get_data_approval("HoG Risk and FM");
			$email_risk = $get_hog_risk['PIC_EMAIL'];
		}else{
			$get_svp_legal = $this->feasibility_study->get_data_approval("SVP Risk");
			$email_risk  = $get_svp_legal['PIC_EMAIL'];
		}

		if($email_proc){
			$data['VERIFIKASI_PROC_EMAIL'] = $email_proc;
		}
		if($email_risk){
			$data['VERIFIKASI_RISK_EMAIL'] = $email_risk;
		}

		$insert = $this->crud->create("DPL", $data);

		if($is_new == "1"){
			$this->crud->update("FS_BUDGET", array("IS_DPL" => 1), array("ID_FS" => $id_fs));
		}
		$level     = 1;
		$data_approval = array();

		$directorat_name = strtolower(get_directorat($directorate));

		if($total_amount <= 100000000):

			$division_name   = strtolower(get_division($division));
			$get_by_unit     = ($division_name == "new business") ? $unit : false;

			$get_hog_user    = $this->feasibility_study->get_data_approval("HOG User", $directorate, $division, $get_by_unit);
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "ID_DPL" => $insert);

			$email_to     = $get_hog_user['PIC_EMAIL'];
			$email_name   = $get_hog_user['PIC_NAME'];

			$get_hog_budget = $this->feasibility_study->get_data_approval("HOG Budget");
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG Budget", "ID_APPROVAL" => $get_hog_budget['ID_APPROVAL'], "ID_DPL" => $insert);
		endif;

		if($total_amount > 100000000):

			$get_director =false;

			if($directorat_name != "finance") :
				if($directorat_name == "marketing"){
					$chief = "CMO";
				}elseif($directorat_name == "technology"){
					$chief = "CTO";
				}elseif($directorat_name == "operation"){
					$chief = "COO";
				}
				else{
					$chief = "CEO";
				}
				$get_director = $this->feasibility_study->get_data_approval($chief, $directorate);
			endif;

			$get_cfo = $this->feasibility_study->get_data_approval("CFO");

			if($directorat_name == "ceo office" && $total_amount <= 1000000000){
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "ID_DPL" => $insert);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "ID_DPL" => $insert);
			}else{
				if($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation"){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "ID_DPL" => $insert);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "ID_DPL" => $insert);
			}

			if($total_amount > 1000000000):

				if($directorat_name == "ceo office"){
					$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
				}else{
					$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorate);
				}
				foreach ($get_bod as $key => $value) {
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_DPL" => $insert);
				}
			endif;

		endif;

		$insert_approval = $this->crud->create_batch("TRX_APPROVAL_DPL", $data_approval);
		$id_approval     = $this->db->insert_id();

		$status   = false;
		$messages = "";
					
		if($insert != -1){

			if($email_to !="" && $email_name !=""){

				$submitter_email = $this->user_email;

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

				$this->_email_request_verification($recipient, $insert);
			}

			$status = true;
			$log_info = "Created ". $insert;
		}else{
			$log_info = "Failed to create DPL";
		}
		log_message('info', "DPL :  " . $log_info);

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
	}

	public function load_dpl(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_dir_code = $this->input->post('directorat');
		$id_division = $this->input->post('division');
		$id_unit     = $this->input->post('unit');
		$status      = $this->input->post('status');

		// $date_from   = "";
		// $date_to     = "";

		// if($this->input->post('date_from') !="" && $this->input->post('date_to') !=""){
		// 	$exp_date_from = explode("-", $this->input->post('date_from'));
		// 	$exp_date_to   = explode("-", $this->input->post('date_to'));

		// 	$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		// 	$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		// }

		$date_from   		= date_db($this->input->post('date_from'));
		$date_to     		= date_db($this->input->post('date_to'));

		$get_all         = $this->dpl->get_dpl($status, $id_dir_code, $id_division, $id_unit, $date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				if($value['STATUS'] == "wait_verify"){
					$status = "Waiting for verification";

				}elseif($value['STATUS'] == "request_approve"){
					$status = "Waiting for approval";
				}else{
					$status = ucfirst($value['STATUS']);
				}

				$row[] = array(
						'no'           => $number,
						'directorat'   => get_directorat($value['ID_DIR_CODE']),
						'divisi'   	   => get_division($value['ID_DIVISION']),
						'unit'   	   => get_unit($value['ID_UNIT']),
						'id_dpl' 	   => encrypt_string($value['ID_DPL'], true),
						'id' 	   	   => $value['ID_DPL'],
						'dpl_number'     => $value['DPL_NUMBER'],
						'no_justif'    => get_fs($value['ID_FS']),
						'status'       => $status
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

	public function view_dpl($id_dpl){
		
		$decrypt = decrypt_string($id_dpl, true);
		$id_dpl   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("DPL", array("ID_DPL" => $id_dpl));

		if($check_exist > 0){

			$get_dpl = $this->crud->read_by_param("DPL", array("ID_DPL" => $id_dpl));

			$data['title']          = "DPL";
			$data['module']         = "datatable";
			$data['template_page']  = "dpl/dpl_view";
			
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

			$approval = array();
			$approval_remark = array();

			foreach ($get_approval as $key => $value) {
				$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['CATEGORY']);
				if(!empty($value['REMARK'])){
					$approval_remark[] = $value;
				}
			}
			$data['dpl_verificator'] = $verificator;
			$data['dpl_approval'] = $approval;
			// echo_pre($data);die;
			$breadcrumb[] = array( "name" => "Beranda", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "DPL", "link" => base_url('dpl'), "class" => "" );
			$breadcrumb[] = array( "name" => $get_dpl['DPL_NUMBER'], "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
	}

	public function edit_dpl($id_dpl){

		if($this->ion_auth->is_admin() == true || in_array("dpl", $this->session->userdata['menu_url']) ){

			$decrypt      = decrypt_string($id_dpl, true);
			$id_dpl = (int) $decrypt;

			$check_exist = $this->crud->check_exist("DPL", array("ID_DPL" => $id_dpl));

			if($check_exist > 0){

				$get_dpl = $this->crud->read_by_param("DPL", array("ID_DPL" => $id_dpl));

				$data['title']          = "DPL";
				$data['module']         = "datatable";
				$data['template_page']  = "dpl/dpl_edit";

				$data['id_dpl']          = $id_dpl;
				$data['dpl_number']        = $get_dpl['DPL_NUMBER'];
				$data['directorate']     = $get_dpl['ID_DIR_CODE'];
				$data['divisi']        	 = $get_dpl['ID_DIVISION'];
				$data['unit']        	 = $get_dpl['ID_UNIT'];
				$data['id_fs']        	 = $get_dpl['ID_FS'];
				$data['uraian']        	 = $get_dpl['URAIAN'];
				$data['kegiatan']        = $get_dpl['KEGIATAN_PENGADAAN'];
				$data['date_from']       = dateFormat($get_dpl['DATE_FROM'], 5, false);
				$data['date_to']       	 = dateFormat($get_dpl['DATE_TO'], 5, false);
				// $data['tujuan_pengadaan'] = $get_dpl['TUJUAN_PENGADAAN'];
				$tujuan       	 		 = json_decode($get_dpl['TUJUAN_PENGADAAN']);
				$tujuan_arr 			 = array();
				if($tujuan){
					foreach ($tujuan as $key => $value) {
						$tujuan_arr[] = $key;
					}
				}
				$data['tujuan_pengadaan'] = $tujuan_arr;
				$data['pic']       	 	 = $get_dpl['PIC_USER'];
				$data['jabatan_sub']     = $get_dpl['JABATAN'];
				$data['vendor']       	 = $get_dpl['REKANAN'];
				$data['evaluasi']     	 = $get_dpl['EVALUASI_REKANAN'];
				$kriteria       	     = json_decode($get_dpl['KRITERIA']);
				$kriteria_arr 			 = array();
				if($kriteria){
					foreach ($kriteria as $key => $value) {
						$kriteria_arr[] = $key;
					}
				}
				$data['kriteriaarr'] 	 = $kriteria_arr;
				$data['alasan']     	 = $get_dpl['ALASAN_PENUNJUKAN'];
				$data['keuntungan']      = $get_dpl['KEUNTUNGAN_PENUNJUKAN'];
				$data['resiko']      	 = $get_dpl['RESIKO_PENUNJUKAN'];

				$breadcrumb[] = array( "name" => "Beranda", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => "DPL", "link" => "", "class" => "active" );

				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);
			}
		}
	}

	public function save_dpl_edit(){

		$id_dpl 				= $this->input->post('id_dpl');
		$kegiatan_pengadaan 	= $this->input->post('kegiatan_pengadaan');
		$uraian 				= $this->input->post('uraian');
		$date_from 				= $this->input->post('date_from');
		$date_to 				= $this->input->post('date_to');
		$vendor 				= $this->input->post('vendor');
		$pic 					= $this->input->post('pic');
		$jabatan_sub 			= $this->input->post('jabatan_sub');
		$evaluasi 				= $this->input->post('evaluasi');
		$kriteria 				= $this->input->post('kriteria');
		$alasan 				= $this->input->post('alasan');
		$keuntungan 			= $this->input->post('keuntungan');
		$resiko 				= $this->input->post('resiko');

		if($date_from != ""){
			$exp_date_from = explode("-", $date_from);
			$date_from = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		}

		if($date_to != ""){
			$exp_date_to = explode("-", $date_to);
			$date_to = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}
		$action_name = get_user_data($this->session->userdata('user_id'));
		
		$data = array(
						"URAIAN"                => $uraian,
						"KEGIATAN_PENGADAAN"    => $kegiatan_pengadaan,
						"REKANAN"               => $vendor,
						"PIC_USER"              => $pic,
						"JABATAN"               => $jabatan_sub,
						"KRITERIA"              => $kriteria,
						"EVALUASI_REKANAN"      => $evaluasi,
						"ALASAN_PENUNJUKAN"     => $alasan,
						"KEUNTUNGAN_PENUNJUKAN" => $keuntungan,
						"STATUS"                => "request_approve",
						"STATUS_DESCRIPTION"    => "Resubmitted by " .$action_name,
						"RESIKO_PENUNJUKAN"     => $resiko
					);

		$update   = $this->crud->update("DPL", $data, array("ID_DPL" => $id_dpl));

		if($update != -1){

			$result['status']    = true;
		}else{
			$result['status']   = false;
		}

		echo json_encode($result);
	}

	public function delete_dpl(){

		$id       = $this->input->post('id');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->crud->delete("DPL", array("ID_DPL" => $id));

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	public function load_vendor_edit(){

		$get_vendor   = $this->dpl->get_vendor();

		if($get_vendor){
			foreach ($get_vendor as $key => $value) {
				$dataVendor[] = array("NAMA_VENDOR" => $value['NAMA_VENDOR']);
			}
		}

		if(count($dataVendor) > 0){

			$result['status'] = true;

			foreach($dataVendor as $row) {

				$data[] = array(
								"vendor_name"    => $row['NAMA_VENDOR']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function load_vendor(){

		$hasil	= $this->dpl->get_vendor_create();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Vendor --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_VENDOR']."' data-name='".$row['NAMA_VENDOR']."' >".$row['NAMA_VENDOR']."</option>";
		}		
		echo $result;
		$query->free_result();
	}


    private function _email_request_verification($recipient, $id_dpl){

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

		$send = sendemail($to, $subject, $body, $cc);

		return $send;
	}

	public function printPDF($id_dpl){

		ob_start();

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$decrypt = decrypt_string($id_dpl, true);
		$id_dpl  = (int) $decrypt;

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/cetakan_justif.pdf';

		$row = $this->dpl->get_cetak($id_dpl);

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);
		$mpdf->Image('assets/img/fintek.jpg',15,10,35);

		$title = "DPL - ".$row['DPL_NUMBER'];
		$mpdf->setTitle($title);

		$mpdf->SetFont('Roboto','B',11);
		$height = 23;
		$mpdf->SetXY(60, $height);
		$mpdf->Cell(10, 10, "Dokumen Penunjukan Langsung (DPL)",0,"");

		$mpdf->SetFont('Roboto','',8);

		$cell_1 = 185;
		$height = 30;
		$mpdf->SetXY(10, $height);
		$mpdf->Cell($cell_1,4,"",0,1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,'A. Uraian rencana pekerjaan / pengadaan barang / jasa :',1,1,'L',1);
		$mpdf->SetFont('Roboto','',8);
		$mpdf->MultiCell($cell_1,4, $row['URAIAN'],"R L",0,'R');
		$mpdf->Cell($cell_1,1,"","B R L",1,'L');

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,'Kegiatan Pengadaan',1,1,'L',1);
		if($row['KEGIATAN_PENGADAAN'] == 1){
			$mpdf->SetFont('Roboto','',8);
			$mpdf->Cell(92.5,4,'(  ) Belum dilaksanakan',"R L",0,'L');
			$mpdf->Cell(92.5,4,'( √ ) Sudah dilaksanakan sebelum proses Pengadaan',"R L",1,'L');
			$mpdf->Cell(92.5,4,"","R L",0,'L');
			$mpdf->Cell(92.5,4,"     dilakukan oleh Procurement","R L",1,'L');
			$mpdf->Cell(92.5,4,"","B R L",0,'L');
			$mpdf->Cell(92.5,4,"*tidak menggunakan DPL, namun menggunakan NODIN KRONOLOGIS.*","B R L",1,'L');
		}elseif($row['KEGIATAN_PENGADAAN'] == 0){
			$mpdf->SetFont('Roboto','',8);
			$mpdf->Cell(92.5,4,'( √ ) Belum dilaksanakan',"R L",0,'L');
			$mpdf->Cell(92.5,4,'(  ) Sudah dilaksanakan sebelum proses Pengadaan',"R L",1,'L');
			$mpdf->Cell(92.5,4,"","R L",0,'L');
			$mpdf->Cell(92.5,4,"     dilakukan oleh Procurement","R L",1,'L');
			$mpdf->Cell(92.5,4,"","B R L",0,'L');
			$mpdf->Cell(92.5,4,"*tidak menggunakan DPL, namun menggunakan NODIN KRONOLOGIS.*","B R L",1,'L');
		}

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell($cell_1,4,'Jadwal Implementasi/Target Penggunaan :',"L R",1,'L');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell($cell_1,4,dateFormat($row['DATE_FROM'], 'month_years', false)." s/d ".dateFormat($row['DATE_TO'], 'month_years', false),"R L",1,'L');
		$mpdf->Cell($cell_1,1,"","B R L",1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,"C. Tujuan Pengadaan: (dapat dipilih lebih dari 1 Tujuan Pengadaan)","B R L",2,'L',1);
		$mpdf->SetFont('Roboto','',8);

		$get_tujuan_pengadaan   = getTujuanDPL();
		$get_kriteria_pengadaan = getKriteriaDPL();

		$tujuan_pengadaan = json_decode($row['TUJUAN_PENGADAAN']);
		$tujuan_arr = array();
		foreach ($tujuan_pengadaan as $key => $value) {
			$tujuan_arr[] = $key;
		}
		$even_odd = 1;
		foreach ($get_tujuan_pengadaan as $key => $value) {
		    $position = ($even_odd % 2 == 1) ? 0 : 1;
		    $checked = (in_array(strtolower( str_replace(" ", "_", $value) ), $tujuan_arr)) ? " √ ": "";
			$mpdf->Cell(92.5,4,"(".$checked.") ".$value,"R L", $position,'L');
			$even_odd++;
		}

		$mpdf->Cell(92.5,2,"","R L",0,'L');
		$mpdf->Cell(92.5,2,"","R L",1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		// $mpdf->SetY(96);

		$mpdf->Cell($cell_1,4,"D. Dasar Pengadaan :","B R L",2,'L',1);
		$mpdf->Cell(92.5,4,"No eJustifikasi ","B R L",0,'L');
		$mpdf->Cell(92.5,4,"Nilai Justifikasi","B R L",1,'L');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell(92.5,4,get_fs($row['ID_FS']),"R L",0,'L');
		$mpdf->Cell(92.5,4,"Rp " . number_format(get_fs($row['ID_FS'], "NOMINAL_FS"),0,',','.'),"R L", 1,'L');
		$mpdf->Cell(92.5,2,"","B R L",0,'L');
		$mpdf->Cell(92.5,2,"","B R L",1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(92.5,4,"Usulan nama Supplier/Rekanan yang ditunjuk:","R L", 0,'L');
		$mpdf->Cell(92.5,4,"PIC USER/FUNGSI PENGGUNA:","R L", 1,'L');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell(92.5,4,$row['REKANAN'],"R L", 0,'L');
		$mpdf->Cell(92.5,4,$row['PIC_USER'],"R L", 1,'L');
		$mpdf->Cell(92.5,4,"","R L", 0,'L');
		$mpdf->Cell(92.5,4,$row['JABATAN'],"R L", 1,'L');
		$mpdf->Cell(92.5,1,"","B R L",0,'L');
		$mpdf->Cell(92.5,1,"","B R L",1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,"E. Evaluasi Rekanan","B R L", 1,'L',1);
		$mpdf->SetFont('Roboto','',8);
		$mpdf->MultiCell($cell_1,4,$row['EVALUASI_REKANAN'],"R L", 0,'R');
		$mpdf->Cell($cell_1,1,"","B R L",1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,"F. Kriteria Penunjukan Langsung: (dapat dipilih lebih dari 1 Kriteria Penunjukan Langsung)","B R L", 1,'L',1);
		$mpdf->SetFont('Roboto','',8);
	/*	$mpdf->Cell($cell_1,4,"(  )  Penyedia/Supplier/Rekanan Tunggal;","R L", 1,'L');
		$mpdf->Cell($cell_1,4,"(  )  Lanjutan dari Pekerjaan sebelumnya yang tidak terelakkan;;","R L", 1,'L');
		$mpdf->Cell($cell_1,4,"(  )  Pembatalan Tender sebanyak dua (2) kali;","R L", 1,'L');
		$mpdf->Cell($cell_1,4,"(  )  Critical (Penting & Mendesak)","R L", 1,'L');*/

		$kriteria_pengadaan = json_decode($row['KRITERIA']);
		$kriteria_arr = array();
		foreach ($kriteria_pengadaan as $key => $value) {
			$kriteria_arr[] = $key;
		}
		foreach ($get_kriteria_pengadaan as $key => $value) {
		    $checked = (in_array(strtolower( str_replace(" ", "_", $value) ), $kriteria_arr)) ? " √ ": "";
			$mpdf->Cell($cell_1,4,"(".$checked.") ".$value.";","R L", 1,'L');
		}

		$mpdf->Cell($cell_1,1,"","R L", 1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell($cell_1,4,"Alasan melakukan Penunjukan Langsung:","R L", 1,'L');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->MultiCell($cell_1,4,$row['ALASAN_PENUNJUKAN'],"B R L", 0,'R');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,"G. Keuntungan menggunakan Penunjukan Langsung:","B R L", 1,'L',1);
		$mpdf->SetFont('Roboto','',8);
		$mpdf->MultiCell($cell_1,4,$row['KEUNTUNGAN_PENUNJUKAN'],"R L", 0,'R');
		$mpdf->Cell($cell_1,1,"","B R L", 1,'L');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->SetFillColor(220,220,220);
		$mpdf->Cell($cell_1,4,"H. Resiko bila tidak menggunakan Penunjukan Langsung:","B R L", 1,'L',1);
		$mpdf->SetFont('Roboto','',8);
		$mpdf->MultiCell($cell_1,4,$row['RESIKO_PENUNJUKAN'],"R L", 0,'R');
		$mpdf->Cell($cell_1,1,"","B R L", 2,'L');

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell($cell_1,6,"CATATAN:",0, 2,'L');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell(10,4,"   1. ",0, 0,'L');
		$mpdf->MultiCell(175,4,"Penunjukan Langsung adalah satu bagian dalam rangkaian proses pengadaan. Jika dalam proses itu Rekanan tidak dapat memenuhi persyaratan baik secara administratif, teknis, legal dan komersial, maka Penunjukan Langsung ini tidak dapat ditindaklanjuti menjadi suatu perikatan.",0, 1,'R');
		$mpdf->Cell(10,4,"   2. ",0, 0,'L');
		$mpdf->Cell(21,4,"Dokumen harus",0, 0,'R');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(25,4,"LENGKAP, JELAS",0, 0,'R');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell(6,4,"dan",0, 0,'R');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(11,4,"BENAR",0, 0,'R');
		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell($cell_1,4,"serta memenuhi kriteria Penunjukan Langsung, Jika tidak memenuhi seperti yang",0, 1,'L');
		$mpdf->Cell($cell_1,4,"           yang dipersyaratkan, maka akan dikembalikan dan/atau tidak akan diproses.",0, 1,'L');
		$mpdf->Cell($cell_1,8,"","B", 1,'R');
		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell($cell_1,20,"OTORISASI PENUNJUKAN LANGSUNG",0, 1,'L');

		$mpdf->SetFont('Roboto','',8);
		$mpdf->Cell(46.25,8,"Nama",1, 0,'C');
		$mpdf->Cell(71.25,8,"Jabatan",1, 0,'C');
		$mpdf->Cell(29.25,8,"Status",1, 0,'C');
		$mpdf->Cell(29.25,8,"Tanggal",1, 1,'C');

		// $dpl_history = get_dpl_history($id_dpl, $row);
		$verificator = array();
		$get_verifier = $this->dpl->get_verifier_list($id_dpl);
		foreach ($get_verifier as $key => $value) {
			$verificator_level = strtolower($value['PIC_LEVEL']);
			if (strpos($verificator_level, 'procurement') !== false) {
				$status = $value['VERIFIKASI_PROC'];
				$action_date = ($value['VERIFIKASI_PROC_DATE']) ? dateFormat($value['VERIFIKASI_PROC_DATE'], 'fintool', false) : '';
			}
			if (strpos($verificator_level, 'risk') !== false) {
				$status = $value['VERIFIKASI_RISK'];
				$action_date = ($value['VERIFIKASI_RISK_DATE']) ? dateFormat($value['VERIFIKASI_RISK_DATE'], 'fintool', false) : '';
			}
			$status_verif = ($status) ? ucfirst($status) : "" ;
			$verificator[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $status_verif, "JABATAN" => $value['JABATAN'], "ACTION_DATE" => $action_date);
		}

		$get_approval = $this->dpl->get_approval_by_dpl($id_dpl);
		$approver = array();
		foreach ($get_approval as $key => $value) {
			$status = "";
			if($value['STATUS']){
				$status = ($value['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($value['STATUS']);
			}
			$action_date = ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 'fintool', false) : '';
			$approver[] = array("NAME" => $value['PIC_NAME'], "STATUS" =>$status, "JABATAN" => $value['CATEGORY'], "ACTION_DATE" => $action_date);
		}
		$list_approver = array_merge($verificator, $approver);;

		$no = 1;
		foreach ($list_approver as $key => $value) {
			$mpdf->Cell(46.25,8,$no.". ".$value['NAME'],1, 0,'L');
			$mpdf->Cell(71.25,8,$value['JABATAN'],1, 0,'C');
			$mpdf->Cell(29.25,8,$value['STATUS'],1, 0,'C');
			$mpdf->Cell(29.25,8,$value['ACTION_DATE'],1, 1,'C');

			$no++;
		}

		$dpl_encrypt = $row['DPL_NUMBER'];
		$doc_ref = encrypt_string($dpl_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";

		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);

		$title = "DPL - ".$row['DPL_NUMBER'] .".pdf";
		ob_clean();

		$mpdf->Output($title, "I");


		// $title = "FPJP - ".$row['FPJP_NAME'] .".pdf";
		// $mpdf->Output($title, "I");
		// ob_end_flush();
	}
	

}