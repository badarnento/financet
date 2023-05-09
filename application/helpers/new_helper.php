<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('examp_func')){
	
	function examp_func($param=""){
		
		$CI   = get_instance();
		return $data;
		
	}
}

if ( ! function_exists('email_approve_justif')){
	
	function email_approve_justif($recipient, $id_fs, $type, $approval_remark="", $id_approval=0, $is_review=false){
		
		$CI   = get_instance();
		$CI->load->model('feasibility_study_mdl', 'feasibility_study');

		$get_fs      = $CI->feasibility_study->get_fs_for_email($id_fs);

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

		$action_name = get_user_data($CI->session->userdata('user_id'));

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

		// $data['approval_link'] = base_url("budget/approval/").base64url_encode($id_fs."-".$id_approval.$CI->config->item('encryption_key'));
		$data['approval_link'] = base_url("budget/approval/").encrypt_string($id_fs."-".$id_approval, true);
		$data['approval_link_all'] = base_url("budget/approval");
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = $tile_first . " - " . $fs_number . " - " . $fs_name;
		$body       = $CI->load->view('email/'.$file_view, $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return TRUE;
	}
}

if ( ! function_exists('trigger_approve_justif')){
	
	function trigger_approve_justif($id_fs, $id_approval){
		
		$CI   = get_instance();
		$CI->load->model('feasibility_study_mdl', 'feasibility_study');

		$trx_justif = check_is_justif_approval($id_fs, $id_approval);

		$status = $trx_justif['STATUS'];
		if(empty($status)){

			echo 'xxxx';die;

				$get_approval_before = $CI->feasibility_study->get_approval_before($id_fs, $trx_justif['LEVEL']);
				$get_next_aprover = $CI->feasibility_study->get_approver($id_fs, $trx_justif['LEVEL']);

				$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
				$recipient['name']  = $get_next_aprover['PIC_NAME'];

				email_approve_justif($recipient, $id_fs, "request_approve", $get_approval_before['REMARK'], $trx_justif['ID']);
				$CI->crud->update("TRX_APPROVAL", array("STATUS" => "request_approve"), $trx_justif['ID']);
		}
		return TRUE;
		
	}
}