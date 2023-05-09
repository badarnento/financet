<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FS_ctl extends CI_Controller {

	protected $authorization,
			  $http_response;

	private $module_name = "fs",
			$module_short_title = "Justif",
			$module_title       = "Justification",
			$module_url         = "feasibility-study",
			$user_active        = "";

	public function __construct()
	{
		
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->user_active = get_user_data($this->session->userdata('user_id'));

		$this->load->model('feasibility_study_mdl', 'feasibility_study');

		$this->authorization = false;

	}

	public function index()
	{
				
		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$data['title']         = $this->module_title;
			$data['module']        = "datatable";
			$data['template_page'] = $this->module_name."/feasibility_study_inquiry";
			$data['directorat']    = get_all_directorat();
			$data['fs_status']     = get_status_fs();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $this->module_title, "link" => "", "class" => "active" );

			$group_name = get_user_group_data();

			$show_createbtn = (in_array("Justif Inquiry", $group_name)) ? false : true;
			$enableEdit     = (in_array("Justif Edit", $group_name)) ? true : false;
			$enableDelete   = (in_array("Justif Delete", $group_name)) ? true : false;
			
		    $directorat = check_is_bod();
		    $binding    = check_binding();

		    if(count($directorat) > 1 && $binding['binding'] != false){
				$directorat = $binding['data_binding']['directorat'];
		    }
			
			$data['su_budget']     = (in_array("SU Budget", $group_name)) ? true : false;
			$data['show_create']   = $show_createbtn;
			$data['enable_edit']   = $enableEdit;
			$data['enable_delete'] = $enableDelete;
			$data['directorat']    = $directorat;
			$data['binding']       = $binding['binding'];
			$data['data_binding']  = $binding['data_binding'];

			$data['group_name']    = $group_name;

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

	public function new_fs(){
				
		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$data['title']         = "Create New Justification";
			$data['module']        = "datatable";
			$data['template_page'] = $this->module_name."/feasibility_study_create";
			// $data['directorat']    = get_all_directorat();

			$group_name = get_user_group_data();

			if(!in_array("SU Budget", $group_name)){
				$this->session->set_flashdata('messages', 'Submission Justifikasi Telah Ditutup');
				redirect($this->module_url);
				exit;
			}

			$data['su_budget'] = (in_array("SU Budget", $group_name)) ? true : false;
			$data['su_budget_js'] = (in_array("SU Budget", $group_name)) ? "true" : "false";

		    $directorat = check_is_bod();
		    $binding    = check_binding();

		    if(count($directorat) > 1 && $binding['binding'] != false){
				$directorat = $binding['data_binding']['directorat'];
		    }

			$data['directorat']   = $directorat;
			$data['binding']      = $binding['binding'];
			$data['data_binding'] = $binding['data_binding'];

			// $hour_now = (int) date('G', time());
			$data['enable_old_rkap'] = (in_array("Justif Old RKAP", $group_name)) ? true : false;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $this->module_title, "link" => base_url($this->module_url), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function edit_fs($id_fs){

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){
    		$user_groups = get_user_group_data();
			$this->authorization = true;
    		if(in_array("Justif Inquiry", $user_groups) || in_array("Justif View Only", $user_groups)){
    			$this->authorization = false;
			}
		}
				
		if($this->authorization == true){
				
			$decrypt = decrypt_string($id_fs, true);
			$id_fs   = (int) $decrypt;

			$check_exist = $this->crud->check_exist("FS_BUDGET", array("ID_FS" => $id_fs));

			if($check_exist > 0){

				$get_fs_budget = $this->crud->read_by_param("FS_BUDGET", array("ID_FS" => $id_fs));

				$data['title']          = "Justification ".$get_fs_budget['FS_NUMBER'];
				$data['module']         = "datatable";
				$data['template_page']  = $this->module_name."/feasibility_study_edit";

				$group_name = get_user_group_data();
				
				$enableEdit     = (in_array("Justif Edit", $group_name)) ? true : false;
				$data['enable_edit']   = $enableEdit;

				$redirect = true;
				if($get_fs_budget['STATUS'] == "returned" || $enableEdit || $this->ion_auth->is_admin()){
					$redirect = false;
				}

				if($redirect){
					redirect($this->module_url);
					exit;
				}

				$data['id_fs']          = $id_fs;
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
				$data['fs_status']      = $get_fs_budget['STATUS'];
				$data['fs_status_desc'] = $get_fs_budget['STATUS_DESCRIPTION'];
				$data['fs_last_update'] = dateFormat($get_fs_budget['UPDATED_DATE'], "with_day", false);

				$get_approval = $this->feasibility_study->get_approval_by_fs($id_fs);

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

				$data['su_budget'] = (in_array("SU Budget", $group_name)) ? true : false;
				$data['su_budget_js'] = (in_array("SU Budget", $group_name)) ? "true" : "false";

				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => $this->module_title, "link" => base_url($this->module_url), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'Justification Not Exist');
				redirect($this->module_url);
				
			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_fs(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_dir_code = $this->input->post('directorat');
		$id_division = $this->input->post('division');
		$id_unit     = $this->input->post('unit');
		$status      = $this->input->post('status');
		$date_from   = "";
		$date_to     = "";

		if($this->input->post('fs_date')){
			$exp_fs_date = explode(" - ", $this->input->post('fs_date'));
			$date_from   = date_db($exp_fs_date[0]);
			$date_to     = date_db($exp_fs_date[1]);
		}

		$get_all = $this->feasibility_study->get_fs_header($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status = ($value['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($value['STATUS']);

				$row[] = array(
							'no'             => $number,
							'id'             => $value['ID_FS'],
							'id_fs'          => encrypt_string($value['ID_FS'], true),
							'directorat'     => $value['DIRECTORAT_NAME'],
							'division'       => $value['DIVISION_NAME'],
							'unit'           => $value['UNIT_NAME'],
							'rkap_name'      => $value['RKAP_DESCRIPTION']."  &ndash; ".date("M-y", strtotime($value['MONTH'])),
							'fs_number'      => $value['FS_NUMBER'],
							'fs_name'        => $value['FS_NAME'],
							'status_desc'    => $status.' <a href="javascript:void(0)" class="text-danger status-info d-none" title="More info!"><i class="fa fa-info-circle"></i> </a>',
							'status'         => $status,
							'fs_date'        => dateFormat($value['FS_DATE'], 5, false),
							'total_amount'   => number_format($value['NOMINAL_FS'],0,',','.'),
							'reloc_in'       => number_format($value['RELOC_IN'],0,',','.'),
							'reloc_out'      => number_format($value['RELOC_OUT'],0,',','.'),
							'fpjp'           => number_format($value['ABS_FPJP'],0,',','.'),
							'pr'             => number_format($value['ABS_PR'],0,',','.'),
							'fa_fs'          => number_format($value['FA_FS'],0,',','.'),
							'submitter'      => $value['SUBMITTER']
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

	public function save_fs(){
		
		$directorat     = $this->input->post('directorat');
		$division       = $this->input->post('division');
		$unit           = $this->input->post('unit');
		$fs_name        = $this->input->post('fs_name');
		$fs_description = $this->input->post('fs_description');
		$fs_date        = $this->input->post('fs_date');
		$amount         = $this->input->post('amount');
		$data_line      = $this->input->post('data_line');
		$submitter      = $this->input->post('submitter');
		$jabatan_sub    = $this->input->post('jabatan_sub');
		$currency       = $this->input->post('currency');
		$rate           = $this->input->post('rate');
		$capex_opex     = $this->input->post('capex_opex');
		$attachment     = $this->input->post('attachment');
		$risk           = $this->input->post('risk');
		$fraud          = $this->input->post('fraud');
		$is_dpl         = $this->input->post('is_dpl');
		$district       = $this->input->post('district');
		$is_cadangan    = $this->input->post('is_cadangan');

		if($fs_date != ""){
			$exp_fs_date = explode("-", $fs_date);
			$fs_date = $exp_fs_date[2]."-".$exp_fs_date[1]."-".$exp_fs_date[0];
		}

		$get_dir        = $this->crud->read_by_param("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $directorat));
		$id_dir_code    = $get_dir['ID_DIR_CODE'];

		$check_fs_exist = $this->crud->check_exist("FS_BUDGET", array("ID_DIR_CODE" => $id_dir_code));

		$month     = date("m");
		$year      = date("Y");
		$number    = sprintf("%'03d", 1);
		$fs_number = $get_dir['DIRECTORAT_CODE']."/".$number."/".date("m")."/".date("Y");

		if($check_fs_exist > 0){
			$last_fs_number = $this->feasibility_study->get_last_fs_number($id_dir_code);
			$exp_fs_number  = explode("/",$last_fs_number);
			if(substr($last_fs_number, 0, 4) == "JUST"){
			    $dir_code = $exp_fs_number[1];
			    $number   = (int) $exp_fs_number[2];
			}else{
			    $dir_code = $exp_fs_number[0];
			    $number   = (int) $exp_fs_number[1];
			}
			$number += 1;
			$number = sprintf("%'03d", $number);
			$fs_number = "JUST/".$dir_code."/".$number."/".$month."/".$year;
		}

		$amount       = str_replace(".", "", $amount);
		$total_amount = (int) $amount;

		$auto_reject = get_auto_reject_date();

		$data = array(
						"ID_DIR_CODE"         => $id_dir_code,
						"ID_DIVISION"         => $division,
						"ID_UNIT"             => $unit,
						"FS_NUMBER"           => $fs_number,
						"FS_NAME"             => $fs_name,
						"FS_DESCRIPTION"      => $fs_description,
						"CURRENCY"            => $currency,
						"CURRENCY_RATE"       => $rate,
						"NOMINAL_FS"          => $total_amount,
						"SUBMITTER"           => $submitter,
						"JABATAN_SUBMITER"    => $jabatan_sub,
						"INTERFACE_STATUS"    => 'NEW',
						"DOCUMENT_ATTACHMENT" => $attachment,
						"STATUS"              => "request_approve",
						"AUTO_REJECT_DATE"    => $auto_reject
					);

		if($district){
			$data['ID_DISTRICT'] = $district;
		}

		if($fs_date != ""){
			$data['FS_DATE'] = $fs_date;
		}

		if($is_dpl == true){
			$data['IS_DPL'] = 1;
		}

		// echo_pre($_POST);die;

		$insert   = $this->crud->create("FS_BUDGET", $data);
		$status   = false;
		$messages = "";

		$sendemail = true;
		$user_groups = get_user_group_data();
		$custom_justif = (in_array("Justif without LoA", $user_groups)) ? true : false;

		if($custom_justif){
			$sendemail = false;
			$dataUpdFs = array("APPROVAL_LEVEL" => 0, "STATUS" => "approved");
		}else{
			$getLoA        = $this->_getLoAJustif($insert, $id_dir_code, $division, $unit, $total_amount, $fraud, $is_cadangan);

			$data_approval = $getLoA['data_approval'];

			$recipient['email'] = $getLoA['email_to'];
			$recipient['name']  = $getLoA['email_name'];

			$dataUpdFs = array("APPROVAL_LEVEL" => count($data_approval), "STATUS_DESCRIPTION" => "Submitted by ".$submitter);

			$insert_approval = $this->crud->create_batch("TRX_APPROVAL", $data_approval);
			$id_approval     = $this->db->insert_id();
		}

		$this->crud->update("FS_BUDGET", $dataUpdFs, array("ID_FS" => $insert));

		if($insert > 0){

			$fs_line_number = 1;

			foreach ($data_line as $key => $value) {

				$exp_period_start = explode("-", $value['period_start']);
				$period_start     = $exp_period_start[2]."-".$exp_period_start[1]."-".$exp_period_start[0];
				$exp_period_end   = explode("-", $value['period_end']);
				$period_end       = $exp_period_end[2]."-".$exp_period_end[1]."-".$exp_period_end[0];

				$data_lines[] = array(
										"ID_FS"                => $insert,
										"FS_LINES_NUMBER"      => $fs_line_number,
										"FS_LINES_NAME"        => $value['line_name'],
										"ID_RKAP_LINE"         => $value['id_rkap'],
										"FS_LINES_AMOUNT"      => $value['nominal'],
										"PROC_TYPE"            => $value['proc_type'],
										"PROC_TYPE_DESC"       => $value['proc_desc'],
										"SERVICE_PERIOD_START" => $period_start,
										"SERVICE_PERIOD_END"   => $period_end,
										"AMOUNT_CURRENCY"      => $value['nominal_currency'],
										"CREATED_BY"           => $this->user_active
									);

				$fs_line_number++;
			}

			$insert_line = $this->crud->create_batch("FS_BUDGET_LINES", $data_lines);

			if($insert_line){
				if($sendemail == true){
					$this->_email_aprove($recipient, $insert, $id_approval);
				}
				$log_info = $insert . " - Justif Created by " . $this->user_active;
				$status    = true;
			}else{
				$messages = "Failed to Create Justification Line";
				$log_info = $insert . " - ". $messages ." by " . $this->user_active;
			}
		}
		else{
			$messages = "Failed to Create Justification";
			$log_info = $insert . " - ". $messages ." by " . $this->user_active;
		}
		
		log_message('info', $this->module_short_title . ":  " . $log_info);

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

    private function _email_aprove($recipient, $id_fs, $id_approval){

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

		$approval_lnk = base_url("budget/approval/").encrypt_string($id_fs."-".$id_approval, true);

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = "A new justification request $fs_number has been submitted for your approval";

		$data['email_body'] = "
								A new justification request $fs_number has been submitted for your approval. You can see all the details about this justification by clicking the link below.
								<br>
								<br>
								Please go through the <a href='$approval_lnk'>link</a> and confirm your approval.
								<br>
								<br>
								The justification details are:
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
								</table>
								";
		$data['approval_link_all'] = base_url("budget/approval");
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "Approval Request - $fs_number - $fs_name";
		$body       = $this->load->view('email/need_approval', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }


     public function save_fs_edit(){

		$id_fs          = $this->input->post('id_fs');
		$directorat     = $this->input->post('directorat');
		$division       = $this->input->post('division');
		$unit           = $this->input->post('unit');
		$fs_name        = $this->input->post('fs_name');
		$fs_description = $this->input->post('fs_description');
		$amount         = $this->input->post('amount');
		$data_line      = $this->input->post('data_line');
		$submitter      = $this->input->post('submitter');
		$jabatan_sub    = $this->input->post('jabatan_sub');
		$currency       = $this->input->post('currency');
		$rate           = $this->input->post('rate');
		$attachment     = $this->input->post('attachment');
		$fraud          = $this->input->post('fraud');
		$risk           = $this->input->post('risk');
		$is_cadangan    = $this->input->post('is_cadangan');

		$amount       = str_replace(".", "", $amount);
		$total_amount = (int) $amount;

		// $auto_reject = get_auto_reject_date();

		$status   = false;
		$messages = "";
		
		$get_status     = $this->crud->read_by_param_specific("FS_BUDGET", array("ID_FS" => $id_fs), "STATUS,APPROVAL_LEVEL");
		$status_fs      = $get_status[0]['STATUS'];
		$approval_level = $get_status[0]['APPROVAL_LEVEL'];
		$sendemail      = false;

		if($status_fs == "returned" && $approval_level > 0):

			$getLoA             = $this->_getLoAJustif($id_fs, $directorat, $division, $unit, $total_amount, $fraud, $is_cadangan);
			$data_approval      = $getLoA['data_approval'];
			$recipient['email'] = $getLoA['email_to'];
			$recipient['name']  = $getLoA['email_name'];

			$dataUpdFs = array("APPROVAL_LEVEL" => count($data_approval), "STATUS_DESCRIPTION" => "Submitted by ".$submitter);

			$this->crud->update("TRX_APPROVAL", array("IS_ACTIVE" => 0), array("ID_FS" => $id_fs));
			$insert_approval = $this->crud->create_batch("TRX_APPROVAL", $data_approval);
			$id_approval     = $this->db->insert_id();
			$sendemail       = true;
		endif;

		$data = array(
						"FS_NAME"             => $fs_name,
						"FS_DESCRIPTION"      => $fs_description,
						"CURRENCY"            => $currency,
						"CURRENCY_RATE"       => $rate,
						"NOMINAL_FS"          => $total_amount,
						"SUBMITTER"           => $submitter,
						"JABATAN_SUBMITER"    => $jabatan_sub
					);

		if($attachment != ""){
			$data['DOCUMENT_ATTACHMENT'] = $attachment;
		}

		if($status_fs == "returned" && $approval_level > 0):
			$data['APPROVAL_LEVEL']     = count($data_approval);
			$data['STATUS']             = "request_approve";
			$data['STATUS_DESCRIPTION'] = "Resubmitted by ".$submitter;
		endif;

		$user_groups = get_user_group_data();
		$custom_justif = (in_array("Justif without LoA", $user_groups)) ? true : false;

		$update = $this->crud->update("FS_BUDGET", $data, array("ID_FS" => $id_fs));

		if($update !== -1){
			$update_line_status = array();

			foreach ($data_line as $key => $value) {

				$exp_period_start = explode("-", $value['period_start']);
				$period_start     = $exp_period_start[2]."-".$exp_period_start[1]."-".$exp_period_start[0];
				$exp_period_end   = explode("-", $value['period_end']);
				$period_end       = $exp_period_end[2]."-".$exp_period_end[1]."-".$exp_period_end[0];

				$data_lines = array(
										"FS_LINES_NAME"        => $value['line_name'],
										"PROC_TYPE"            => $value['proc_type'],
										"PROC_TYPE_DESC"       => $value['proc_desc'],
										"SERVICE_PERIOD_START" => $period_start,
										"SERVICE_PERIOD_END"   => $period_end,
										"AMOUNT_CURRENCY"      => $value['nominal_currency'],
										"FS_LINES_AMOUNT"      => $value['nominal']
									);

				$update_lines = $this->crud->update("FS_BUDGET_LINES", $data_lines, array("FS_LINES_ID" => $value['fs_lines_id']));

				if($update_lines == -1){
					$update_line_status[] = false;
				}
			}

			if(!in_array(FALSE, $update_line_status)){
				if($sendemail){
					$this->_email_aprove($recipient, $id_fs, $id_approval);
				}
				$log_info = $id_fs . " - Updated by " . $this->user_active;
				$status = true;
			}else{
				$messages = "Failed to update lines";
				$log_info = $id_fs . " - ". $messages ." by " . $this->user_active;
			}
		}
		else{
			$messages = "Failed to update";
			$log_info = $id_fs . " - ". $messages ." by " . $this->user_active;

		}
		log_message('info', $this->module_short_title . ":  " . $log_info);

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

    public function view_fs($id_fs_enc){

    	$user_groups = get_user_group_data();
				
		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) || in_array("Justif Inquiry", $user_groups) || in_array("Justif View Only", $user_groups) ){

			$decrypt = decrypt_string($id_fs_enc, true);
			$id_fs   = (int) $decrypt;

			$check_exist = $this->crud->check_exist("FS_BUDGET", array("ID_FS" => $id_fs));

			if($check_exist > 0){

				$get_fs_budget = $this->crud->read_by_param("FS_BUDGET", array("ID_FS" => $id_fs));

				$data['title']          = "Justification ".$get_fs_budget['FS_NUMBER'];
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
				$data['fs_status']      = ($get_fs_budget['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_fs_budget['STATUS']);
				$data['fs_status_desc'] = $get_fs_budget['STATUS_DESCRIPTION'];
				$data['fs_last_update'] = dateFormat($get_fs_budget['UPDATED_DATE'], "with_day", false);

				$id_district = $get_fs_budget['ID_DISTRICT'];

				$district = false;
				if($id_district > 0){
					$district = true;
					$data['district_name'] = get_district_by_id($id_district);
				}

				$data['district'] = $district;

				$pic_email = $this->session->userdata('email');
				$pic_level = "";
				if($this->session->userdata('data_employee')){
					$pic_level = $this->session->userdata('data_employee')['level'];
				}

				$check_approval = $this->crud->read("MASTER_APPROVAL", array("PIC_EMAIL" => $pic_email));

				if($check_approval){
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
					}
				}
				$is_show = false;
				if($pic_level == "HOG BUDGET" || $pic_level == "BUDGET ADMIN" || $pic_level == "FRAUD"){
					$is_show = true;
				}

				$get_approval = $this->feasibility_study->get_approval_by_fs($id_fs, $is_show);

				$approval = array();
				$approval_remark = array();

				$lastCat = "";

				foreach ($get_approval as $key => $value) {

					$catJabatan = $value['CATEGORY'];
					$statusA    = $value['STATUS'];

					if( ($statusA == 'request_approve' || $statusA == '' ) && ($catJabatan == "Risk" || $catJabatan == "Fraud") ){
						$name    = $catJabatan . " Team";
						$jabatan = "Risk and Fraud Management";
						$fraud = true;
					}else{
						$name    = $value['PIC_NAME'];
						$jabatan = ($catJabatan == "Risk" || $catJabatan == "Fraud") ? $catJabatan." Management" : $catJabatan;
					}

					if($lastCat != $catJabatan){
						$approval[] = array("NAME" => $name, "STATUS" => $statusA, "JABATAN" => $jabatan);

						if(!empty($value['REMARK'])){
							$approval_remark[] = $value;
						}
					}


					if($catJabatan != "BOD"){
						$lastCat = $catJabatan;
					}else{
						$lastCat = "";
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
										"UPLOADED_BY"   => $data_file->UPLOADED_BY . " BOC"
										);
				}
				if($get_fs_budget['RISK_ATTACHMENT']){
					$data_file  = json_decode($get_fs_budget['RISK_ATTACHMENT']);
					$attachment[] = array(
										"FILE_NAME"     => $data_file->FILE,
										"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/risk_attachment/".$data_file->FILE, true),
										"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
										"UPLOADED_BY" => $data_file->UPLOADED_BY . " RISK &amp; FM"
										);
				}
				if($get_fs_budget['CFO_ATTACHMENT']){
					$data_file  = json_decode($get_fs_budget['CFO_ATTACHMENT']);
					$attachment[] = array(
										"FILE_NAME"     => $data_file->FILE,
										"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/cfo_attachment/".$data_file->FILE, true),
										"DATE_UPLOADED" => $data_file->DATE_UPLOADED,
										"UPLOADED_BY"   => $data_file->UPLOADED_BY . " CFO"
										);
				}


				$data['fs_attachment'] = $attachment;

				$get_comment = $this->feasibility_study->get_comment_history($id_fs, $is_show);
				$data['comment_history'] = $get_comment;

				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => $this->module_title, "link" => base_url($this->module_url), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'Justification Not Exist');
				redirect($this->module_url);

			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_fs_lines(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_fs = $this->input->post('id_fs');
		
		$get_all = $this->feasibility_study->get_fs_lines($id_fs);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		$get_proc_type = get_proc_type();;
		$group_name = get_user_group_data();

		if($get_proc_type){
			foreach($get_proc_type as $proc) {
				$proctTypeArr[] = $proc;
			}
		}

		if($total > 0){

			$directorat_name = $data[0]['DIRECTORAT'];
			$division_name   = $data[0]['DIVISION'];
			$unit_name       = $data[0]['UNIT'];

			$get_tribe  = $this->budget->get_rkap_from_view("tribe", $directorat_name, $division_name, $unit_name);
			$tribe_arr = array();
			foreach($get_tribe['data'] as $tribe)	{
				$tribe_arr[] = $tribe['TRIBE_USECASE'];
			}

			$nominalDisabled = (in_array("Justif Edit", $group_name)) ? ' readonly' : '';

			foreach($data as $value) {

				$line_name_edit = '<div class="form-group m-b-0"><input id="line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm line_name" value="'.$value['FS_LINES_NAME'].'" ></div>';	
				$fund_av_edit   = '<div class="form-group m-b-0"><input id="fund_av-'.$number.'" data-id="'.$number.'" class="form-control input-sm fund_av text-right" value="'.number_format($value['FA_RKAP']+$value['FS_LINES_AMOUNT'],0,',','.').'" readonly></div>';

				$tribe_opt = "";
				for ($i=0; $i < count($tribe_arr) ; $i++) { 
					$selectedTribe = ($value['TRIBE_USECASE'] == $tribe_arr[$i]) ? ' selected' : '';
					$tribe_opt .= '<option value="'.$tribe_arr[$i].'"'.$selectedTribe.'>'.$tribe_arr[$i].'</option>';
				}
				$tibe_edit = '<div class="form-group m-b-0"><select id="tribe_opt-'.$number.'" data-id="'.$number.'" class="form-control input-sm tribe_opt select-center">'.$tribe_opt.'/<select></div>';
				$rkap_edit = '<div class="form-group m-b-0"><select id="rkap_opt-'.$number.'" data-id="'.$number.'" class="form-control input-sm rkap_opt select-center select2"><option value="0">-- Choose --</option>/<select></div>';

				$nominal_readonly = ($value['CURRENCY'] != "IDR") ? " readonly" : "";

				$nominal_edit   = '<div class="form-group m-b-0"><input id="nominal-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal text-right money-format" value="'.number_format($value['FS_LINES_AMOUNT'],0,',','.').'"'.$nominalDisabled.$nominal_readonly.'></div>';
				$nominal_currency_edit   = '<div class="form-group m-b-0"><input id="nominal_currency-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_currency text-right money-format" value="'.number_format($value['AMOUNT_CURRENCY'],0,',','.').'"></div>';

				$period_start = "";
				if($value['SERVICE_PERIOD_START']){
					$period_start = date("d-m-Y", strtotime($value['SERVICE_PERIOD_START']));
				}
				$period_end = "";
				if($value['SERVICE_PERIOD_END']){
					$period_end = date("d-m-Y", strtotime($value['SERVICE_PERIOD_END']));
				}

				$period_start_edit = '<div class="form-group m-b-0"><input id="period_start-'.$number.'" data-id="'.$number.'" class="form-control input-sm period_start date_period" value="'.$period_start.'"></div>';

				$period_end_edit = '<div class="form-group m-b-0"><input id="period_end-'.$number.'" data-id="'.$number.'" class="form-control input-sm period_end date_period" value="'.$period_end.'"></div>';

				$proc_type_val = $value['PROC_TYPE'];
				$optProcType = '<option value="0">-- Choose --</option>';

				for ($i=0; $i < count($proctTypeArr) ; $i++) {
					$selectedProcType = ($proc_type_val == $proctTypeArr[$i]) ? ' selected' : '';
					$optProcType .= '<option value="'.$proctTypeArr[$i].'"'.$selectedProcType.'>'.$proctTypeArr[$i].'</option>';
				}

				$proc_type_edit  = '<div class="form-group m-b-0"><select id="proc_opt-' .$number. '" data-id="' .$number. '" class="form-control input-sm proc_opt select-center">'.$optProcType.'</select></div>';

				$proc_desc_edit = '<div class="form-group m-b-0"><input id="proc_desc-' .$number. '" data-id="' .$number. '" class="form-control input-sm proc_desc" value="'.$value['PROC_TYPE_DESC'].'"></div>';

				$is_cadangan =  ($value['RKAP_DESCRIPTION'] == "Operational Loss Reserved") ? 1 : 0; 

				$row[] = array(
						'id'                    => $value['FS_LINES_ID'],
						'no'                    => $value['FS_LINES_NUMBER'],
						'tribe'                 => $value['TRIBE_USECASE'],
						'id_rkap_line'          => $value['ID_RKAP_LINE'],
						'tribe_edit'            => $tibe_edit,
						'rkap_name'             => $value['RKAP_DESCRIPTION']." &ndash; ".date("M-y", strtotime($value['MONTH'])),
						'rkap_edit'             => $rkap_edit,
						'program_id'            => $value['ENTRY_OPTIMIZE'],
						'proc_type'             => $value['PROC_TYPE'],
						'proc_desc'             => $value['PROC_TYPE_DESC'],
						'proc_type_edit'        => $proc_type_edit,
						'proc_desc_edit'        => $proc_desc_edit,
						'is_cadangan'           => $is_cadangan,
						'line_name'             => $value['FS_LINES_NAME'],
						'period_start'          => $period_start,
						'period_end'            => $period_end,
						'period_start_edit'     => $period_start_edit,
						'period_end_edit'       => $period_end_edit,
						'fund_available'        => number_format($value['FA_RKAP'],0,',','.'),
						'nominal_currency'      => number_format($value['AMOUNT_CURRENCY'],0,',','.'),
						'nominal'               => number_format($value['FS_LINES_AMOUNT'],0,',','.'),
						'nominal_edit'          => $nominal_edit,
						'nominal_currency_edit' => $nominal_currency_edit,
						'fund_av_edit'          => $fund_av_edit,
						'line_name_edit'        => $line_name_edit
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

	private function _getLoAJustif($id_fs, $directorat, $division, $unit, $total_amount, $fraud, $is_cadangan){


		$level = 1;
		$data_approval = array();

		$directorat_name = strtolower(get_directorat($directorat));
		$division_name   = strtolower(get_division($division));
		$get_by_unit     = ($division_name == "new business") ? $unit : false;

		$get_hog        = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);

		$email_to       = $get_hog['PIC_EMAIL'];
		$email_name     = $get_hog['PIC_NAME'];
		$jabatan_hog    = ($get_by_unit != false) ? $get_hog['JABATAN'] : "HOG User";

		if($is_cadangan === "true" && $division != 21){
			$fraud = false;
			$get_risk = $this->feasibility_study->get_data_approval("Risk");
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "Operational Risk", "ID_APPROVAL" => $get_risk['ID_APPROVAL'], "ID_FS" => $id_fs);

			$email_to       = $get_risk['PIC_EMAIL'];
			$email_name     = $get_risk['PIC_NAME'];

			$get_hog_risk = $this->feasibility_study->get_data_approval("HoG Risk and FM");
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Risk & FM", "ID_APPROVAL" => $get_hog_risk['ID_APPROVAL'], "ID_FS" => $id_fs);
			$get_svp_risk    = $this->feasibility_study->get_data_approval("SVP Risk");
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "SVP Risk & FM", "ID_APPROVAL" => $get_svp_risk['ID_APPROVAL'], "ID_FS" => $id_fs);

			if($division != 52){
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => $jabatan_hog, "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "ID_FS" => $id_fs);
			}

		}else{

			if($division != 52){
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => $jabatan_hog, "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "ID_FS" => $id_fs);
			}

			if($division == 21){
				$get_svp_risk    = $this->feasibility_study->get_data_approval("SVP Risk");
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "SVP Risk & FM", "ID_APPROVAL" => $get_svp_risk['ID_APPROVAL'], "ID_FS" => $id_fs);
			}
		}

		if($fraud === "true"){
			$get_fraud = $this->feasibility_study->get_data_approval("Fraud");
			foreach ($get_fraud as $key => $value) {
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Fraud", "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
			}
		}

		$get_budget_admin = $this->feasibility_study->get_data_approval("Budget Admin");
		foreach ($get_budget_admin as $key => $value) {
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Budget Admin", "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
		}

		if($division == 52){

			$data_approval = array();
			$level = 1;

			$email_to = "";
			$email_name = "";

			if($fraud === "true"){
				$email_to       = "fraud@linkaja.id";
				$email_name     = "Fraud Team";
				$get_fraud = $this->feasibility_study->get_data_approval("Fraud");
				foreach ($get_fraud as $key => $value) {
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "Fraud", "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
				}
			}

			if($email_to != ""){
				$email_to       = "budget@linkaja.id";
				$email_name     = "Budget Team";
			}

			$get_budget_admin = $this->feasibility_study->get_data_approval("Budget Admin");
			foreach ($get_budget_admin as $key => $value) {
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "Budget Admin", "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
			}
			
		}

		$get_hog_budget = $this->feasibility_study->get_data_approval("HOG Budget");

		if($total_amount < 100000000){

			if($division == 52){
				$get_director = $this->feasibility_study->get_data_approval($chief, $directorat);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "ID_FS" => $id_fs);
			}

			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG Budget", "ID_APPROVAL" => $get_hog_budget['ID_APPROVAL'], "ID_FS" => $id_fs);

		}

		if($total_amount >= 100000000):

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
				$get_director = $this->feasibility_study->get_data_approval($chief, $directorat);
			endif;

			// $get_cfo = $this->feasibility_study->get_data_approval("CFO");

			/*if(($directorat_name == "ceo office") && $total_amount < 1000000000){
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG Budget", "ID_APPROVAL" => $get_hog_budget['ID_APPROVAL'], "ID_FS" => $id_fs);
				// $data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "ID_FS" => $id_fs);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "ID_FS" => $id_fs);
			}else{*/
				if($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation"){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "ID_FS" => $id_fs);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG Budget", "ID_APPROVAL" => $get_hog_budget['ID_APPROVAL'], "ID_FS" => $id_fs);
				// $get_cfo = $this->feasibility_study->get_data_approval("CFO", $directorat);

			if($directorat_name == "ceo office" || $directorat_name == "finance"){

				$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
				foreach ($get_bod as $key => $value) {
					if($value['PIC_LEVEL'] == "CMO" || $value['PIC_LEVEL'] == "CTO" || $value['PIC_LEVEL'] == "COO"){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => $value['PIC_LEVEL'], "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
					}
				}
			}
		endif;

		if($total_amount >= 1000000000):
			/*if($directorat_name == "ceo office"){
				$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
			}else{
				$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorat);
			}
			foreach ($get_bod as $key => $value) {
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
			}*/

			if($directorat_name == "technology" || $directorat_name == "operation" || $directorat_name == "marketing"){

				$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorat);
				foreach ($get_bod as $key => $value) {
					if($value['PIC_LEVEL'] == "CMO" || $value['PIC_LEVEL'] == "CTO" || $value['PIC_LEVEL'] == "COO"){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => $value['PIC_LEVEL'], "ID_APPROVAL" => $value['ID_APPROVAL'], "ID_FS" => $id_fs);
					}
				}
			}

		endif;

		$result['data_approval'] = $data_approval;
		$result['email_to']      = $email_to;
		$result['email_name']    = $email_name;

		return $result;

	}

	public function delete_fs(){

		$result['status'] = false;
		$messages = "You don't have permission to delete this ";

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){
    		$user_groups = get_user_group_data();
			$this->authorization = true;
    		if(in_array("Justif Inquiry", $user_groups) || in_array("Justif View Only", $user_groups)){
    			$this->authorization = false;
			}
		}

		if($this->authorization == true){

			$id = $this->input->post('id');
			$messages = "Failed to delete data";

			if($this->crud->delete("FS_BUDGET", array("ID_FS" => $id)) > 0){

				$messages         = "Data successfully deleted";
				$result['status'] = true;
			}

		}
		$result['messages'] = "Data successfully deleted";

		$log_info = $this->input->post('id') . " - ". $messages ." by " . $this->user_active;

		log_message('info', $this->module_short_title . ":  " . $log_info);

		echo json_encode($result);
	}


	public function change_status_fs(){

		$id_fs  = $this->input->post('id_fs');
		$status = $this->input->post('status');
		
		$result['status']   = false;
		$result['messages'] = "Failed to change status";

		$data = array(
						"STATUS" => $status
					);
		
		$changeStatus = $this->crud->update("FS_BUDGET", $data, array("ID_FS" => $id_fs));

		if($changeStatus !== -1){
			$result['status']   = true;
			$result['messages'] = "Status successfully changed";
		}

		echo json_encode($result);
	}

	public function get_status_info(){

		$id_fs  = $this->input->post('id_fs');
		
		$result['status']   = false;
		$result['messages'] = "Failed to change status";

		// $changeStatus = $this->feasibility_study->update("FS_BUDGET", $data, array("ID_FS" => $id_fs));
		$changeStatus = 1;

		if($changeStatus !== -1){
			$result['status']   = true;
			$result['messages'] = "Status successfully changed";
		}

		echo json_encode($result);
	}

	public function download_data_inquiry()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('FINANCE TOOL - SYSTEM')
		->setLastModifiedBy('FINANCE TOOL - SYSTEM')
		->setTitle("Download Data")
		->setSubject("Download Data")
		->setDescription("Download Data")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$arrColumn = array("No", "Directorate", "Division", "Unit", "Tribe", "RKAP Name", "Proc Type", "Justif Number", "Justif Name", "Justif Description", "Justif Date", "Period Program Start", "Period Program End", "Justif Amount", "Reloc In", "Reloc Out", "FPJP", "PR", "Fund Avl Justif", "Submitter", "Status", "Approval Status", "FPJP Number", "PR Number");

		$totalColumn = count($arrColumn);

		$loop_column = horizontal_loop_excel("A", $totalColumn);
		$j=0;
		foreach ($loop_column as $key => $value) {
			$excel->setActiveSheetIndex(0)->setCellValue($value.'1', $arrColumn[$j]);
			$j++;
		}
		
		$vdir      = $this->input->get('vdir');
		$vstat     = $this->input->get('vstat');
		$divisi    = $this->input->get('divisi');
		$unit      = $this->input->get('unit');
		
		$date_from = "";
		$date_to   = "";

		if($this->input->get('fs_date')){
			$exp_fs_date = explode(" - ", $this->input->get('fs_date'));
			$date_from = date_db($exp_fs_date[0]);
			$date_to   = date_db($exp_fs_date[1]);
		}

		$hasil = $this->feasibility_study->get_download_inquiry($date_from, $date_to, $vdir, $vstat, $divisi, $unit);

		$numrow  = 2;
		$number = 1;

		$id_fs_arr = array();
		foreach($hasil->result_array() as $row)	{
			$id_fs_arr[] = $row['ID_FS'];
		}
		$status_approval = array();
		$fpjp_numbers    = array();
		$pr_numbers      = array();

		if(count($id_fs_arr) > 0):
			$id_fs_arr = array_unique($id_fs_arr);
			$get_approver_status = $this->feasibility_study->get_fs_status_approver($id_fs_arr);
			foreach($get_approver_status->result_array() as $row)	{
				$id_fs = $row['ID_FS'];
				if($row['STATUS'] == "request_approve"){
					$status_approval[$id_fs] = "Waiting for approval of ". $row['APPROVER_NAME'];
				}else{
					$status_approval[$id_fs] = ucfirst($row['STATUS']). " by " . $row['APPROVER_NAME'];
				}
			}
			$get_pr_fpjp_by_fs = $this->feasibility_study->get_pr_fpjp_by_fs($id_fs_arr);
			foreach($get_pr_fpjp_by_fs->result_array() as $row)	{
				$id_fs                = $row['ID_FS'];
				$fpjp_numbers[$id_fs] = $row['FPJP_NUMBERS'];
				$pr_numbers[$id_fs]   = $row['PR_NUMBERS'];
			}
		endif;

		foreach($hasil->result_array() as $row)	{

			$rekapconcat = $row['RKAP_DESCRIPTION'].' -'.date('M',strtotime($row['MONTH'])).'-'.date('y',strtotime($row['MONTH']));
			$submitter_concat = $row['SUBMITTER'].' - '.$row['JABATAN_SUBMITER'];

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['TRIBE_USECASE']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $rekapconcat);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['PROC_TYPE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['FS_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['FS_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['FS_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, dateFormat($row['FS_DATE'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, dateFormat($row['SERVICE_PERIOD_START'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, dateFormat($row['SERVICE_PERIOD_END'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['NOMINAL_FS']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['RELOC_IN']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['RELOC_OUT']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['ABS_FPJP']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['ABS_PR']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['FA_FS']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $submitter_concat);

			$status = $row['STATUS'];
			if($status == "request_approve"){
				$statusDesc = "Waiting approval";
			}
			elseif($status == "fs used"){
				$statusDesc = "Used";
			}else{
				$statusDesc = ucfirst($status);
			}
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $statusDesc);

			$id_fs          = $row['ID_FS'];
			$status_approve = "";
			$fpjp_number    = "";
			$pr_number      = "";
	
			if (array_key_exists($id_fs, $status_approval)){
				$status_approve = $status_approval[$id_fs];
			}
			if (array_key_exists($id_fs, $fpjp_numbers)){
				$fpjp_number = $fpjp_numbers[$id_fs];
			}
			if (array_key_exists($id_fs, $pr_numbers)){
				$pr_number = $pr_numbers[$id_fs];
			}
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $status_approve);
			$excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $fpjp_number);
			$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $pr_number);

			$excel->getActiveSheet()->getStyle('N'.$numrow.':S'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$number++;
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", $totalColumn);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Data");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$this->module_title.'.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function auto_reject(){

		$day_arr[] = "2020-04-06";
		$day_arr[] = "2020-04-07";
		$day_arr[] = "2020-04-08";
		$day_arr[] = "2020-04-09";
		$day_arr[] = "2020-04-10";
		$day_arr[] = "2020-04-11";
		$day_arr[] = "2020-04-12";
		$day_arr[] = "2020-04-13";
		$day_arr[] = "2020-04-14";
		$day_arr[] = "2020-04-15";

		$date_to_check = "2020-04-20";

		$now = strtotime("2020-04-24");

		$day_pass = 1;

		foreach ($day_arr as $key => $value) {

			$ts = strtotime($value);

			$ds= date("N", $ts);

			// echo date("N d-m-Y", $ts);

		/*	if($day_pass == 8){
				echo 'aaaa';
				break;
			}

			if($ds < 6){
				echo "Its Weekday ".date("D", $ts);
				if($now)
				$day_pass++;
			}*/

			if( $ts > strtotime('-7 day') ) {

				// echo 'xx';
			}

			// echo "<br>";
			// $time[] = strtotime($value);
		}

		// echo date("d-m-Y", strtotime('-1 day', $now));

		$j = 0;
		$x = 12;
		/*if(date("N", $now) > 5){
			$x++;
		}*/
		for ($i=1; $i <= 14 ; $i++) {
			$ts = strtotime('-'.$i.' day', $now);
			$minus = date("D, d ", $ts);
			

			if(date("N", $ts) < 6){
				$j++;
				echo $j.' ~ '.$minus;
			}
			else{
				echo ' ~ <b>'.$minus.'</b>';
			}

			if($j == 8){
				$minusNya = date("d-m-Y", strtotime('-'.$i.' day', $now));
				break;
			}

			echo "<br>";

		}
			echo "<br>";

		echo $minusNya;

		// $j += $x;

		// echo date("d-m-Y", strtotime('-'.$j.' day', $now));
		// echo $j;
		/*for ($i=8; $i > 0 ; $i--) {
			echo 'x <br>';
		}*/

		// echo_pre($time);

		// echo date('d n m y j f F M Y D J N');

		// $check_fs = $this->feasibility_study->check_fs();

		// echo_pre($check_fs);


	}

	function check_auto_reject(){

		$j = 0;
		for ($i=1; $i <= 14 ; $i++) {
			if(date("N", strtotime('-'.$i.' day', time())) < 6){
				$j++;
			}
			if($j == 8){
				$date_check = date("Y-m-d", strtotime('-'.$i.' day', time()));
				break;
			}
		}

		/*$check_fs = $this->feasibility_study->check_fs($date_check);

		if($check_fs !== -1){
			return true;
		}*/

	}


	public function load_data_submitter(){
		
		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');

		$get_submitter = $this->feasibility_study->get_data_approval("Submitter", $directorat, $division, $unit);
		$dataSubmitter = array();


		if($get_submitter){
			foreach ($get_submitter as $key => $value) {
				$dataSubmitter[] = array("NAMA" => $value['PIC_NAME'], "EMAIL" => $value['PIC_EMAIL'], "JABATAN" => $value['JABATAN']);
			}
		}else{

			$directorat_name = get_directorat($directorat);
			$division_name   = get_division($division);
			$get_submitter   = $this->budget->get_data_submiter($directorat_name, $division_name);

			if($get_submitter){
				foreach ($get_submitter as $key => $value) {
					$dataSubmitter[] = array("NAMA" => $value['NAMA'], "EMAIL" => $value['ALAMAT_EMAIL'], "JABATAN" => $value['JABATAN']);
				}
			}

		}

		if(count($dataSubmitter) > 0){

			$result['status'] = true;

			foreach($dataSubmitter as $row) {

				$data[] = array(
								"nama"    => $row['NAMA'],
								"email"   => $row['EMAIL'],
								"jabatan" => $row['JABATAN']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	function get_attachment($id_fs_enc){

    	$user_groups = get_user_group_data();
				
		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) || in_array("Justif Inquiry", $user_groups) || in_array("Justif View Only", $user_groups) ){

			$decrypt = decrypt_string($id_fs_enc, true);
			$id_fs   = (int) $decrypt;

			$check_exist = $this->crud->check_exist("FS_BUDGET", array("ID_FS" => $id_fs));

			if($check_exist > 0){

				$get_fs_budget = $this->crud->read_by_param_specific("FS_BUDGET", array("ID_FS" => $id_fs),"FS_NUMBER, FS_NAME, DOCUMENT_ATTACHMENT");

				$justif = $get_fs_budget[0];

		        $file = FCPATH . 'uploads/' .$justif['DOCUMENT_ATTACHMENT'];

		        if (file_exists($file)) {

					$mpdf = new \Mpdf\Mpdf();


					$fh = 'uploads/10NodinPembayaranPajakPPh21PeriodeMei2020_KAKKW.pdf';

					// $mpdf->AddPage();
			        // $mpdf->setSourceFile($fh);
			        // $tplId = $mpdf->importPage(1);
					// $mpdf->useTemplate($tplId);


					// $mpdf->SetImportUse();
					$pagecount = $mpdf->SetSourceFile($fh);
				    for ($i=1; $i<=$pagecount; $i++) {
				        $import_page = $mpdf->ImportPage($i);
				        $mpdf->UseTemplate($import_page);

				        if ($i < $pagecount)
				            $mpdf->AddPage();
				    }
					$mpdf->Output();
					$title = "Justifikasi.pdf";

					$mmpdf->Output($title, "I");

					/*$pagecount = $mpdf->SetSourceFile($file);
				    for ($i=1; $i<=$pagecount; $i++) {
				        $import_page = $mpdf->ImportPage($i);
				        $mpdf->UseTemplate($import_page);

				        if ($i < $pagecount)
				            $mpdf->AddPage();
				    }
					$title = $justif['FS_NUMBER'] . " - " . $justif['FS_NAME'];

					$mpdf->Output();*/
					// $mpdf->Output($title, "I");

		        }else{
					$this->session->set_flashdata('messages', 'File does not exist');
					redirect($this->module_url);
		        }
			}
			else{
				$this->session->set_flashdata('messages', 'Justification Not Exist');
				redirect($this->module_url);
			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}




	public function printPDF($id_fs){


		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(600);
		}

		$decrypt = decrypt_string($id_fs, true);
		$id_fs   = (int) $decrypt;

		if($id_fs == 0){
			redirect($module_url,'refresh');
			exit;
		}

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/cetakan_justif.pdf';
        $mpdf->setSourceFile($fh);
        $mpdf->autoPageBreak = true;
		$mpdf->AddPage('L', 'Legal');
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);
		$mpdf->SetTextColor(0,0,0);
		$mpdf->SetFont('Courier New','',9);
		$mpdf->Image('assets/img/fintek.jpg',15,10,35);
		
		$justif_header = $this->feasibility_study->get_cetak($id_fs);
		$justif_lines  = $this->feasibility_study->get_cetak_lines($id_fs);

		$justif_line = $justif_lines->row_array();

		$guideline = 0;
		$titikdua = 50;
		$titikdua2 = 25;

		$height = 20;
		$mpdf->SetXY(130, $height);
		$mpdf->Cell(10, 10, "Justification Approval", $guideline,"C");

		// JUSTIF NUMBER
		$height = 34;
		$height_date = $height;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "Justif Number", $guideline, "L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua+3, $height);
		$mpdf->Cell(10, 10, $justif_header["FS_NUMBER"], $guideline, "L");

		// JUSTIF DATE
		$mpdf->SetXY(165, $height_date);
		$mpdf->Cell(10, 10 ,"Justif Date", $guideline, "L");
		$mpdf->SetXY($titikdua2+170, $height_date);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua2+173, $height_date);
		$mpdf->Cell(10, 10, dateFormat($justif_header['FS_DATE'], 4, false), $guideline, "L");

		// JUSTIF NAME
		$height += 5;
		$height_dir = $height;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "Justif Name", $guideline, "L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":", $guideline, "L");

		$justif_name = $justif_header["FS_NAME"];
		$height_div2 = 0;
		$height_unit2 = 0;

		// WRAP TEXT
		$total_name = strlen($justif_name);
		if ($total_name < 50){
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(10, 10, $justif_name, $guideline, "L");
		}
		else{
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(4,10, substr($justif_name, 0, 50), $guideline, "L");
			$height += 5;
			$height_div2 = $height;
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(10, 10, substr($justif_name, 50, 50), $guideline, "L");
			$height_unit2 -= 5;
		}


		$mpdf->SetXY(165, $height_dir);
		$mpdf->Cell(10, 10, "Directorate", $guideline, "L");
		$mpdf->SetXY($titikdua2+170, $height_dir);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua2+173, $height_dir);
		$mpdf->Cell(10, 10, $justif_header["DIRECTORAT"], $guideline, "L");

		// JUSTIF DESC
		$height += 5;
		$height_div = ($height_div2 > 0) ? $height_div2 : $height;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "Description", $guideline, "L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":", $guideline, "L");

		$justif_description = str_replace("\n"," ", $justif_header["FS_DESCRIPTION"]);
		$justif_description = str_replace("\r"," ", $justif_description);

		// WRAP TEXT
		$total_desc = strlen($justif_description);
		if ($total_desc < 60){
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(10, 10, $justif_description, $guideline, "L");
		}
		else{
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(4,10, substr($justif_description, 0, 60), $guideline, "L");
			$height += 5;
			$height_unit2 += $height;
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(10, 10, substr($justif_description, 60, 60), $guideline, "L");
			$height += 5;
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(10, 10, substr($justif_description, 120, 60), $guideline, "L");
			$height += 5;
			$mpdf->SetXY($titikdua+3, $height);
			$mpdf->Cell(10, 10, substr($justif_description,180, 60), $guideline, "L");

		}

		$mpdf->SetXY(165, $height_div);
		$mpdf->Cell(10, 10, "Division", $guideline, "L");
		$mpdf->SetXY($titikdua2+170, $height_div);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua2+173, $height_div);
		$mpdf->Cell(10, 10, $justif_header["DIVISION"], $guideline, "L");

		$height += 5;
		$height_unit = ($height_unit2 > 0) ? $height_unit2 : $height;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "Currency", $guideline, "L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua+3, $height);
		$mpdf->Cell(10, 10, $justif_header["CURRENCY"], $guideline, "L");

		$mpdf->SetXY(165, $height_unit);
		$mpdf->Cell(10, 10, "Unit", $guideline, "L");
		$mpdf->SetXY($titikdua2+170, $height_unit);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua2+173, $height_unit);
		$mpdf->Cell(10, 10, $justif_header["UNIT"], $guideline, "L");

		$height += 5;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "Total Amount After Tax", $guideline, "L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua+3, $height);
		$mpdf->Cell(10, 10, number_format($justif_header["NOMINAL_FS"],0,',','.'), $guideline, "L");

		$height += 5;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "Period Program", $guideline, "L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":", $guideline, "L");
		$mpdf->SetXY($titikdua+3, $height);
		$period_program = ($justif_line['SERVICE_PERIOD_START'] != NULL && $justif_line['SERVICE_PERIOD_END'] != NULL) ? dateFormat($justif_line['SERVICE_PERIOD_START'], 4, false) ." - " . dateFormat($justif_line['SERVICE_PERIOD_END'], 4, false) : "-";
		$mpdf->Cell(10, 10, $period_program, $guideline, "L");

		// RKAP DETAIL
		$height += 12;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10, 10, "RKAP DETAIL", $guideline, "L");
	    $mpdf->SetFont('Courier New', '', 7);
		
		$noWidth      = 10;
		$tribeWidth   = 30;
		$rkapWidth    = 70;
		$procWidth    = 40;
		$descWidth    = 70;
		$faWidth      = 25;
		$nominalWidth = 25;
		$hRow = 5;

		$height += 10;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell($noWidth, $hRow, 'No', 1, 0, 'C');
	    $mpdf->Cell($tribeWidth, $hRow, 'Tribe/Usacase', 1, 0, 'C');
	    $mpdf->Cell($rkapWidth, $hRow, 'RKAP Name', 1, 0, 'C');
	    $mpdf->Cell($procWidth, $hRow, 'Proc Type', 1, 0, 'C');
	    $mpdf->Cell($descWidth, $hRow, 'Description', 1, 0, 'C');
	    $mpdf->Cell($faWidth, $hRow, 'FA RKAP', 1, 0, 'C');
	    $mpdf->Cell($nominalWidth, $hRow, 'Nominal', 1, 1, 'C');

	    $number_row = 1;

	    $checkHeight = 50;
	    $maxHeight = 230;
		$stopAddpage = false;

		foreach ($justif_lines->result_array() as $line){
			$tribe     = $line["TRIBE_USECASE"];
			$rkap_desc = $line['RKAP_DESCRIPTION']." - ".date("M-y", strtotime($line['MONTH']));
			$fs_desc   = $line["FS_LINES_NAME"];
			$proc_type = $line["PROC_TYPE"];
			$tribe_ln  = strlen($tribe)." ";
			$proc_ln   = strlen($proc_type)." ";
			$rkap_ln   = strlen($rkap_desc)." ";
			$fs_ln     = strlen($fs_desc)." ";

			$proc_type = $line["PROC_TYPE"];
			$fs_desc   = $line["FS_LINES_NAME"];
			$proc_type = $line["PROC_TYPE"];
			$fa_rkap   = number_format($line['FA_RKAP'],0,',','.');
			$nominal   = number_format($line['FS_LINES_AMOUNT'],0,',','.');

			if($tribe_ln > 25 || $proc_ln > 30 || $rkap_ln > 60 || $fs_ln > 60){
				$tribe = str_replace(" ", " |", $tribe);
				$newTribe = explode("|",$tribe);
				$newLine = "";

				$strC = 0;
				$lastTribe = "";
				$tribelinebreak = "";
				$lasttribelinebreak = "";

				for ($i=0; $i < count($newTribe) ; $i++) { 
					$str = strlen($newTribe[$i]);
					$strC += $str;
					if($strC > 25){
						$i_last = $i;
						$lasttribelinebreak .= $newLine.$newTribe[$i];
					}
					else{
						$lastTribe .= $newLine.$newTribe[$i];
					}
				}

				$proc_type = str_replace(" ", " |", $proc_type);
				$newProc = explode("|",$proc_type);
				$newLine = "";

				$strC = 0;
				$lastProc = "";
				$proclinebreak = "";
				$lastproclinebreak = "";

				for ($i=0; $i < count($newProc) ; $i++) { 
					$str = strlen($newProc[$i]);
					$strC += $str;
					if($strC > 30){
						$i_last = $i;
						$lastproclinebreak .= $newLine.$newProc[$i];
					}
					else{
						$lastProc .= $newLine.$newProc[$i];
					}
				}

				$rkap_desc = str_replace(" ", " |", $rkap_desc);
				$newRkap = explode("|",$rkap_desc);
				$newLine = "";

				$strC = 0;
				$lastRkap = "";
				$rkapLineBreak = "";
				$lastrkapLineBreak = "";

				for ($i=0; $i < count($newRkap); $i++) 
				{
					$str = strlen($newRkap[$i]);
					$strC += $str;

					if($strC > 120){
						$i_last = $i;
						$lastrkapLineBreak .= $newLine.$newRkap[$i];
					}
					else if ($strC > 60 && $strC <= 120)
					{
						$i_last = $i;
						$rkapLineBreak .= $newLine.$newRkap[$i];
					}
					else{
						$lastRkap .= $newLine.$newRkap[$i];
					}
				}

				$fs_desc = str_replace(" ", " |", $fs_desc);
				$newFsDesc = explode("|",$fs_desc);
				$newLine = "";

				$strC = 0;
				$lastFsDesc = "";
				$fsLineBreak = "";
				$lastFsLineBreak = "";

				for ($i=0; $i < count($newFsDesc); $i++) 
				{
					$str = strlen($newFsDesc[$i]);
					$strC += $str;
					if($strC > 120){
						$i_last = $i;
						$lastFsLineBreak .= $newLine.$newFsDesc[$i];
					}
					else if ($strC > 60 && $strC <= 120)
					{
						$i_last = $i;
						$fsLineBreak .= $newLine.$newFsDesc[$i];
					}
					else{
						$lastFsDesc .= $newLine.$newFsDesc[$i];
					}
				}
			}

			if($tribe_ln > 25 || $proc_ln > 30 || $rkap_ln > 60 || $fs_ln > 60){

				if($stopAddpage == false && $checkHeight >= $maxHeight){
					$mpdf->AddPage('L', 'Legal');
					$stopAddpage = true;
					$height = 0;
				}
				$mpdf->Cell($noWidth, $hRow, $number_row, "R L", 0, 'C');
			    $mpdf->Cell($tribeWidth, $hRow, $lastTribe, "R L", 0, 'L');
			    $mpdf->Cell($rkapWidth, $hRow, $lastRkap, "R L", 0, 'L');
			    $mpdf->Cell($procWidth, $hRow, $lastProc, "R L", 0, 'L');
			    $mpdf->Cell($descWidth, $hRow, $lastFsDesc, "R L", 0, 'L');
			    $mpdf->Cell($faWidth, $hRow, $fa_rkap, "R L", 0,'R');
			    $mpdf->Cell($nominalWidth, $hRow, $nominal, "R L", 1, 'R');

				$mpdf->Cell($noWidth, $hRow-1, " ", "R L", 0, 'C', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($tribeWidth, $hRow-1, $lasttribelinebreak, "R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($rkapWidth, $hRow-1, $rkapLineBreak, "R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($procWidth, $hRow-1, $lastproclinebreak, "R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($descWidth, $hRow-1, $fsLineBreak, "R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($faWidth, $hRow-1, " ", "R L", 0,'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($nominalWidth, $hRow-1, " ","R L", 1, 'L', 0, '', 0, 0, 0, "T");

				$mpdf->Cell($noWidth, $hRow-1, " ", "B R L", 0, 'C', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($tribeWidth, $hRow-1, " ", "B R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($rkapWidth, $hRow-1, $lastrkapLineBreak, "B R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($procWidth, $hRow-1, " ", "B R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($descWidth, $hRow-1, $lastFsLineBreak, "B R L", 0, 'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($faWidth, $hRow-1, " ", "B R L", 0,'L', 0, '', 0, 0, 0, "T");
			    $mpdf->Cell($nominalWidth, $hRow-1, " ","B R L", 1, 'L', 0, '', 0, 0, 0, "T");

			
				$checkHeight += $hRow-1;
				$checkHeight += $hRow-1;
				$height += $hRow-1;
				$height += $hRow-1;

			}else{

				$mpdf->Cell($noWidth, $hRow, $number_row, 1, 0, 'C');
			    $mpdf->Cell($tribeWidth, $hRow, $tribe, 1, 0, 'L');
			    $mpdf->Cell($rkapWidth, $hRow, $rkap_desc, 1, 0, 'L');
			    $mpdf->Cell($procWidth, $hRow, $proc_type, 1, 0, 'L');
			    $mpdf->Cell($descWidth, $hRow, $fs_desc, 1, 0, 'L');
			    $mpdf->Cell($faWidth, $hRow, $fa_rkap, 1, 0, 'R');
			    $mpdf->Cell($nominalWidth, $hRow, $nominal, 1, 1, 'R');

				if($stopAddpage == false && $checkHeight >= $maxHeight){
					$mpdf->AddPage('L', 'Legal');
					$stopAddpage = true;
					$height = 0;
				}

			}

			$height += $hRow;

			$number_row++;
		}

		$height += 10;
		$mpdf->SetXY(15, $height);
	    $mpdf->SetFont('Courier New', '', 9);
		$checkHeight += $height;

		if($stopAddpage == false && $checkHeight >= $maxHeight){
			$mpdf->AddPage('L', 'Legal');
			$stopAddpage = true;
			$height = 0;
		}

	    $cell_1 = 70;
		$cell_2 = 120;
		$cell_3 = 40;
		$cell_4 = 40;
		$hRow = 10;

		$submitter   = $justif_header['SUBMITTER'];
		$jabatan_sub = $justif_header['JABATAN_SUBMITER'];
		$diketahui1  = $justif_header['DIKETAHUI_1'];
		$diketahui2  = $justif_header['DIKETAHUI_2'];
		$justif_date = dateFormat($justif_header['FS_DATE'], 4, false);

		$mpdf->Cell(10, 10, "Approval List", $guideline, "L");
		$mpdf->Cell(10,8,"",0,1,"L");

		$mpdf->Cell($cell_1, 8,'Nama',1,0,'C');
		$mpdf->Cell($cell_2, 8,'Jabatan',1,0,"C");
		$mpdf->Cell($cell_3, 8,'Status',1,0,'C');
		$mpdf->Cell($cell_4, 8,'Tanggal',1,1,'C');
		$checkHeight += 20;

		if($stopAddpage == false && $checkHeight >= $maxHeight){
			$mpdf->AddPage('L', 'Legal');
			$stopAddpage = true;
		}
		
		$mpdf->Cell($cell_1, $hRow, "1. ".$submitter, 1, 0, 'L');
		$mpdf->Cell($cell_2, $hRow, $jabatan_sub, 1, 0, 'L');
		$mpdf->Cell($cell_3, $hRow, "Approved", 1, 0, 'C');
		$mpdf->Cell($cell_4, $hRow, $justif_date, 1, 1, 'C');

		$checkHeight += $hRow;

		if($stopAddpage == false && $checkHeight >= $maxHeight){
			$mpdf->AddPage('L', 'Legal');
			$stopAddpage = true;
		}

		if($justif_header['STATUS'] == "request_approve"){
			$status = "Waiting for approval";
		}else{
			$status = ucfirst($justif_header['STATUS']);
		}

		$approval = array();

		if($diketahui1 != ""){
			$approval[] = ["NAME" => $diketahui1, "JABATAN" => $justif_header['JABATAN_1'], "STATUS" => ($diketahui1 != "") ? $status : ""];
		}

		if($diketahui2 != ""){
			$approval[] = ["NAME" => $diketahui2, "JABATAN" => $justif_header['JABATAN_2'], "STATUS" => ($diketahui1 != "") ? $status : ""];
		}

		$get_approval = $this->feasibility_study->get_approval_by_fs($id_fs, false);

		if($get_approval){
			$approval = array();
			$lastCat = "";
			foreach ($get_approval as $key => $value) {

				$catJabatan = $value['CATEGORY'];
				$statusA    = $value['STATUS'];
				$jabatan    = $value['JABATAN'];

				if( ($statusA == 'request_approve' || $statusA == '' ) && ($catJabatan == "Risk" || $catJabatan == "Fraud") ){
					$name    = $catJabatan . " Team";
					$jabatan = "Risk and Fraud Management";
				}else{
					$name    = $value['PIC_NAME'];
					$jabatan = $jabatan;
				}

				if($lastCat != $catJabatan){
					$approval[] = array("NAME" => $name, "STATUS" => $statusA, "JABATAN" => $jabatan, "JABATAN" => $jabatan, "TGL_APPROVE" => ($value['STATUS'] !== null && $value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "");
				}

				if($catJabatan != "BOD"){
					$lastCat = $catJabatan;
				}else{
					$lastCat = "";
				}
			}
		}

		$no = 2;

		if(count($approval) > 0):
			foreach ($approval as $key => $value) {
				$checkHeight += $hRow;

				if($stopAddpage == false && $checkHeight >= $maxHeight){
					$mpdf->AddPage('L', 'Legal');
					$stopAddpage = true;
				}
				$pic_name    = $value['NAME'];
				$action_date = $value['TGL_APPROVE'];
				$jabatan     = $value['JABATAN'];
				$jabatan_ln  = strlen($jabatan);

				if($value['STATUS'] == "request_approve"){
					$status = "Waiting for approval";
				}else{
					$status = ucfirst($value['STATUS']);
				}

				if($jabatan_ln > 120){
					$jabatan = str_replace(" ", " |", $jabatan);
					$newjabatan = explode("|",$jabatan);
					$newLine = "";

					$strC = 0;
					$lastjabatan = "";
					$lastjabatanlinebreak = "";

					for ($i=0; $i < count($newjabatan); $i++) 
					{
						$str = strlen($newjabatan[$i]);
						$strC += $str;

						if($strC > 120){
							$i_last = $i;
							$lastjabatanlinebreak .= $newLine.$newjabatan[$i];
						}
						else{
							$lastjabatan .= $newLine.$newjabatan[$i];
						}
					}
				}

				if($jabatan_ln > 120){

					$mpdf->Cell($cell_1, $hRow, $no.". ". $pic_name, "R L",0,'L');
					$mpdf->Cell($cell_2, $hRow, $lastjabatan, "R L",0,'L');
					$mpdf->Cell($cell_3, $hRow, $status, "R L",0,'C');
					$mpdf->Cell($cell_4, $hRow, $action_date, "R L",1,'C');

					$mpdf->Cell($cell_1, $hRow-2, "", "B R L", 0, 'C', 0, '', 0, 0, 0, "T");
					$mpdf->Cell($cell_2, $hRow-2, $lastjabatanlinebreak,"B R L",0,'L');
					$mpdf->Cell($cell_3, $hRow-2, "", "B R L", 0, 'C', 0, '', 0, 0, 0, "T");
					$mpdf->Cell($cell_4, $hRow-2, "", "B R L", 0, 'C', 0, '', 0, 0, 0, "T");

				}
				else{

					$mpdf->Cell($cell_1, $hRow, $no.". ". $pic_name, 1, 0, 'L');
					$mpdf->Cell($cell_2, $hRow, $jabatan, 1, 0, 'L');
					$mpdf->Cell($cell_3, $hRow, $status, 1, 0, 'C');
					$mpdf->Cell($cell_4, $hRow, $action_date, 1, 1, 'C');
				}

				$no++;
			}
		endif;

		$fs_encrypt = $justif_header['FS_NUMBER'] . " - " . number_format($justif_header['NOMINAL_FS'],0,',','.');
		$doc_ref = encrypt_string($fs_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";
		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);

		$title = "Justif ".$justif_header['FS_NUMBER'] ." - " . $justif_header['FS_NAME'];
        $mpdf->SetTitle($title);

		$title = "Justif - ".$justif_header['FS_NUMBER'] .".pdf";

		$mpdf->Output($title, "I");
	}

	

}

/* End of file FS_ctl.php */
/* Location: ./application/controllers/feasibility_study/FS_ctl.php */
