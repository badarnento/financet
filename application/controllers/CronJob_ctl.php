<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronJob_ctl extends CI_Controller {

	public function __construct()
	{
		 parent::__construct();
	}

	public function history_budget()
	{

		return ( $this->crud->call_procedure("HISTORY_BUDGET_HEADER") > 0 ) ? true : false;

	}


	function check_auto_reject(){
		return true;

		/*$this->load->model('feasibility_study_mdl', 'feasibility_study');

		$get_fs_auto_reject = $this->feasibility_study->get_fs_auto_reject();

		$id_fs = array();
		$status = "rejected";

		foreach ($get_fs_auto_reject as $key => $value) {
			$id_fs[] = $value['ID_FS'];
			$data[] = array("STATUS" => $status, "ID_FS" => $value['ID_FS']);
		}

		$total_fs = count($id_fs);

		if($total_fs > 0){

			$update = $this->crud->update_batch_data("TRX_APPROVAL", $data, "ID_FS");

			if($update !== -1){
				for ($i=0; $i < $total_fs ; $i++) {

					$get_submitter   =  $this->feasibility_study->get_submitter_by_id_fs($id_fs[$i]);
					$submitter_email = "";
					$submitter_name  = "";

					if($get_submitter){
						$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
						$submitter_name  = $get_submitter['SUBMITTER'];
					}

					if($submitter_email != "" && $submitter_name != ""){

						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;
						
						$this->_email_approval($recipient, $id_fs[$i], $status);
					}
					$this->crud->update("FS_BUDGET", array("STATUS" => $status, "STATUS_DESCRIPTION" => "Rejected by Auto Reject System"), array("ID_FS" => $id_fs[$i]));

				}

				return true;

			}else{
				return false;
			}

		}
		else{
			return true;
		}*/

	}

	private function _email_approval($recipient, $id_fs, $type){
		
		$get_fs        = $this->feasibility_study->get_fs_for_email($id_fs);
		
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

		$action_name = "System";

		$email_preview = "Your justification request $fs_number has been <b>$type</b> by ".$action_name;
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

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}								
		$subject    = "Rejected - " . $fs_number . " - " . $fs_name;
		$body       = $this->load->view('email/rejected', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }

}