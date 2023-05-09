<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalFPJP_ctl extends CI_Controller {

	private $module_name = "fpjp_new",
			$pic_email   = "",
			$module_url  = "fpjp",
			$user_active = "";


	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('fpjp_mdl', 'fpjp');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');
		$is_delegate    = $this->session->userdata('is_delegate');
		$this->user_active = get_user_data($this->session->userdata('user_id'));
      	$this->pic_email = ($is_delegate !== false) ? $is_delegate : $this->session->userdata('email');

	}

	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "fpjp/approval");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->feasibility_study->check_is_approval($this->pic_email);


		if($check_is_approval){

			$get_fpjp_for_approval = $this->fpjp->get_fpjp_for_approval($this->pic_email);

			$id_fpjp = array();

			if($get_fpjp_for_approval){
				foreach ($get_fpjp_for_approval as $value) {
					$id_fpjp[] = $value['FPJP_HEADER_ID'];
				}
			}

			$data['title']          = "All FPJP Requisition";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/fpjp_approval";
			
			$data['id_fpjp']  = json_encode($id_fpjp);

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
			$this->session->set_flashdata("redirect_page", "fpjp/approval/".$id_approval);
			redirect('login', 'refresh');
		}

		$decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($id_approval));
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify FPJP');
			redirect('/');
		}

		$id_fpjp       = $exp[0];
		$id_approval = $exp[1];
		$get_trx     = $this->crud->read_by_id("TRX_APPROVAL_FPJP", array("ID" => $id_approval));
		$get_approval = $this->crud->read_by_id("MASTER_APPROVAL", array("ID_APPROVAL" => $get_trx['ID_APPROVAL']));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify FPJP Approval');
			redirect('/');
		}

		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
		
		if($check_exist > 0){

			$pic_email     = $this->pic_email;
			$get_fpjp_header = $this->fpjp->get_fpjp_to_approve_by_id($id_fpjp, $pic_email);

			$data['title']            = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']           = "datatable";
			$data['template_page']    = $this->module_name."/fpjp_approval_single";
			
			$data['id_fpjp']          = $id_fpjp;
			$data['id_fs']            = $get_fpjp_header['ID_FS'];
			$data['fpjp_number']      = $get_fpjp_header['FPJP_NUMBER'];
			$data['fpjp_name']        = $get_fpjp_header['FPJP_NAME'];
			$data['fpjp_date']        = dateFormat($get_fpjp_header['FPJP_DATE'], 4, false);
			$data['fpjp_amount']      = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
			$data['fpjp_currency']    = $get_fpjp_header['CURRENCY'];
			$data['fpjp_rate']        = number_format($get_fpjp_header['CURRENCY_RATE'],0,',','.');
			$data['fpjp_directorat']  = $get_fpjp_header['ID_DIR_CODE'];
			$data['fpjp_division']    = $get_fpjp_header['ID_DIVISION'];
			$data['fpjp_unit']        = $get_fpjp_header['ID_UNIT'];
			$data['fpjp_type']        = $get_fpjp_header['ID_MASTER_FPJP'];
			$data['fpjp_submitter']   = $get_fpjp_header['SUBMITTER'];
			$data['fpjp_jabatan_sub'] = $get_fpjp_header['JABATAN_SUBMITTER'];
			$data['fpjp_attachment']  = $get_fpjp_header['DOCUMENT_ATTACHMENT'];
			$data['fpjp_status']      = ($get_fpjp_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_fpjp_header['STATUS']);
			$data['fpjp_status_desc'] = $get_fpjp_header['STATUS_DESCRIPTION'];
			$last_update              = ($get_fpjp_header['UPDATED_DATE']) ? $get_fpjp_header['UPDATED_DATE'] : $get_fpjp_header['CREATED_DATE'];
			$data['fpjp_last_update'] = dateFormat($last_update, "with_day", false);
			$data['trx_status']       = $get_fpjp_header['TRX_STATUS'];
			$data['trx_date']         = dateFormat($get_fpjp_header['TRX_DATE'], "with_day", false);
			$data['disabled_act']     = ($this->pic_email != $this->session->userdata('email') ) ? true : false;

			$attachment = false;
			if($get_fpjp_header['ID_FS'] > 0){
				$get_attachment = $this->crud->read_by_param_specific("FS_BUDGET", array("ID_FS" => $get_fpjp_header['ID_FS']), "DOCUMENT_ATTACHMENT, CREATED_DATE, SUBMITTER");
				if($get_attachment){
					$get_attachment = $get_attachment[0];
					if($get_attachment['DOCUMENT_ATTACHMENT']){
						$attachment = array(
										"FILE_NAME"     => $get_attachment['DOCUMENT_ATTACHMENT'],
										"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/".$get_attachment['DOCUMENT_ATTACHMENT'], true),
										"DATE_UPLOADED" => strtotime($get_attachment['CREATED_DATE']),
										"UPLOADED_BY"   => $get_attachment['SUBMITTER']
										);
						
					}
				}
			}

			$data['fs_attachment'] = $attachment;

			$data['level']          = $get_fpjp_header['LEVEL'];

			if($get_fpjp_header['LEVEL'] > 1):
				// $level_min = $get_fpjp_header['LEVEL']-1;
				$get_approver_before            = $this->fpjp->get_approval_before($id_fpjp);
				$data['approver_before_name']   = $get_approver_before['PIC_NAME'];
				$data['approver_before_remark'] = $get_approver_before['REMARK'];
				$data['approver_before_date']   = dateFormat($get_approver_before['TRX_DATE'], "with_day", false);
			endif;
			
			$data['fpjp_no_invoice']   = ($get_fpjp_header['NO_INVOICE']) ? $get_fpjp_header['NO_INVOICE'] : "-";
			$data['notes']   		   = ($get_fpjp_header['NOTES_USER']) ? $get_fpjp_header['NOTES_USER'] : "-";			$data['fpjp_invoice_date'] = ($get_fpjp_header['INVOICE_DATE']) ? dateFormat($get_fpjp_header['INVOICE_DATE'], 5, false) : "-";
			$data['fpjp_doc_upload']   = ($get_fpjp_header['DOCUMENT_UPLOAD']) ? base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$get_fpjp_header['DOCUMENT_UPLOAD'], true) : "-";

			$get_approval = $this->fpjp->get_approval_by_fpjp($id_fpjp);

			$approval = array();
			$approval_remark = array();

			foreach ($get_approval as $key => $value) {
				$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['CATEGORY']);
				if(!empty($value['REMARK'])){
					$approval_remark[] = $value;
				}
			}
			$data['fpjp_approval'] = $approval;

			if(count($approval_remark) > 0){
				foreach ($approval_remark as $key => $v)
				{
			    	$sort[$key] = strtotime($v['UPDATED_DATE']);
				}
				array_multisort($sort, SORT_DESC, $approval_remark);

				$data['fpjp_approval_remark'] = $approval_remark[0]['REMARK'];
			}


			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All FPJP Request", "link" => base_url("fpjp/approval"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FPJP Not Exist');
			redirect('fpjp/approval');

		}

	}


	public function action_approval(){

		$id_fpjp  = $this->input->post('id_fpjp');
		$level    = $this->input->post('level');
		$remark   = $this->input->post('remark');
		$approval = $this->input->post('approval');

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
		$update = $this->crud->update("TRX_APPROVAL_FPJP", $data, array("FPJP_HEADER_ID" => $id_fpjp, "LEVEL" => $level));

		if($update !== -1){

			$get_submitter   =  $this->fpjp->get_submitter_by_id_fpjp($id_fpjp);
			$submitter_email = "";
			$submitter_name  = "";
			$coa_check = false;

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
				$submitter_name  = $get_submitter['SUBMITTER'];
				$coa_check = ($get_submitter['COA_REVIEW'] == "Y") ? false : true;
			}

			$auto_reject   = get_auto_reject_date();
			$email_cc      = array();

			if($level > 1){
				for ($i=1; $i < $level ; $i++) {
					$get_approver_before = $this->fpjp->get_approver($id_fpjp, $i);
					if($get_approver_before){
						if($get_approver_before['CATEGORY'] != "BOD"){
							$email_cc[] = $get_approver_before['PIC_EMAIL'];
						}
					}
				}
			}
			if($status == "approved"):

				$next_level = $level+1;
				$get_next_aprover = $this->fpjp->get_approver($id_fpjp, $next_level);

				if($get_next_aprover){
					$id_approval = $get_next_aprover['ID'];

					if($submitter_email != ""){
						$recipient['email_cc'] = $submitter_email;
					}

					$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
					$recipient['name']  = $get_next_aprover['PIC_NAME'];

					$this->_email_approval($recipient, $id_fpjp, "request_approve", $remark, $id_approval);
					$this->crud->update("TRX_APPROVAL_FPJP", array("STATUS" => "request_approve"), $id_approval);


				}else{

					if($submitter_email != "" && $submitter_name != ""){

						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;

						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}

						$this->_email_approval($recipient, $id_fpjp, $status, $remark,0,true);
					}

					if($coa_check){
						$approver_accounting = get_approval_by_category('Accounting');
						if($approver_accounting):
							$recipient = array();
							foreach ($approver_accounting as $key => $value):
									$recipient[]  = array('name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
							endforeach;
							$this->_email_review_coa($recipient, $id_fpjp);
						endif;
					}/*else{
						$no_jurnal = $this->_invoicing_to_gl($id_fpjp);
						if( $no_jurnal ){
							$get_user_invoice = get_user_approval_ap("INVOICE_APPROVAL");
							if($get_user_invoice)
							{
								$recipient = array();
								foreach ($get_user_invoice as $key => $value) 
								{
									$recipient[]  = array('name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
								}
								$this->_email_invoice($recipient, $no_jurnal, $submitter_name);
							}
						}
					}*/

					$this->crud->update("FPJP_HEADER", array("STATUS" => $status ), array("FPJP_HEADER_ID" => $id_fpjp));				

				}
				$this->crud->update("FPJP_HEADER", array("INTERFACE_STATUS" => "NEW", "STATUS_DESCRIPTION" => "Approved by ".get_user_data($this->session->userdata('user_id')), "AUTO_REJECT_DATE" => $auto_reject), array("FPJP_HEADER_ID" => $id_fpjp));

			else:

				if($submitter_email != "" && $submitter_name != ""){

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;
					
					$this->_email_approval($recipient, $id_fpjp, $status, $remark);
				}
				$this->crud->update("FPJP_HEADER", array("STATUS" => $status, "STATUS_DESCRIPTION" => ucfirst($status)." by ".get_user_data($this->session->userdata('user_id'))), array("FPJP_HEADER_ID" => $id_fpjp));

			endif;

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		/*if($update !== -1){

			$get_submitter   =  $this->fpjp->get_submitter_by_id_fpjp($id_fpjp);

			$submitter_email = "";
			$submitter_name  = "";

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
				$submitter_name  = $get_submitter['SUBMITTER'];
			}

			$auto_reject = get_auto_reject_date();
			$email_cc    = array();

			if($submitter_email != "" && $submitter_name != ""){

				$recipient['email'] = $submitter_email;
				$recipient['name']  = $submitter_name;

				$this->_email_approval($recipient, $id_fpjp, $status, $remark);
			}

			$this->crud->update("FPJP_HEADER", array("STATUS" => $status, "STATUS_DESCRIPTION" => ucfirst($status)." by ".get_user_data($this->session->userdata('user_id'))), array("FPJP_HEADER_ID" => $id_fpjp));

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}*/

		echo json_encode($result);

	}
	
	public function load_fpjp_to_approve(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$pic_email = $this->pic_email;

		$get_all         = $this->fpjp->get_fpjp_to_approve($pic_email, $status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['FPJP_HEADER_ID'],
						'id_fpjp'            => base64url_encode($value['FPJP_HEADER_ID'].$this->config->item('encryption_key')),
						'id_fpjp_approval'   => base64url_encode($value['FPJP_HEADER_ID']."-".$value['ID_FPJP_APPROVAL'].$this->config->item('encryption_key')),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'fpjp_number'        => $value['FPJP_NUMBER'],
						'fpjp_name'          => $value['FPJP_NAME'],
						'fpjp_currency'      => $value['CURRENCY'],
						'fpjp_rate'          => $value['CURRENCY_RATE'],
						'status'             => $value['STATUS'],
						'level'              => $value['LEVEL'],
						'status_description' => $status_description,
						'fs_date'            => dateFormat($value['FPJP_DATE'], 5, false),
						'total_amount'       => number_format($value['FPJP_AMOUNT'],0,',','.')
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


    private function _invoicing_to_gl($id_fpjp){

		$this->load->model('GL_mdl', 'gl');
		$get_fpjp_to_invoice = $this->gl->get_fpjp_to_invoice($id_fpjp);

		$batch_name_usr = "SYS".date("d/")."AP/".date("my");
		$get_latest_no_journal = $this->gl->get_latest_no_journal_by_batc($batch_name_usr);

    	$start_no_journal = 1;
    	if($get_latest_no_journal):
    		$exp = explode("/", $get_latest_no_journal['NO_JOURNAL']);
    		$start_no_journal = (int) $exp[0];
    		$start_no_journal += 1;
    	endif;

		$no_jurnal = sprintf("%'02d", $start_no_journal)."/".$batch_name_usr;
		$no_urut   = 1;

		$date_now = date("Y-m-d");

    	foreach ($get_fpjp_to_invoice as $key => $value) {

			$tgl_invoice = (empty($value['INVOICE_DATE'])) ? $date_now : $value['INVOICE_DATE'];
			$no_invoice  = (empty($value['NO_INVOICE'])) ? $value['FPJP_NUMBER'] : $value['NO_INVOICE'];
			$description = ($value['FPJP_DETAIL_DESC'] != '') ? $value['FPJP_DETAIL_DESC'] : $value['FPJP_LINE_NAME'];

    		$data[] = array(
						'TGL_INVOICE'          => $date_now,
						'INVOICE_DATE'         => $tgl_invoice,
						'BATCH_NAME'           => $batch_name_usr,
						'NO_JOURNAL'           => $no_jurnal,
						'NAMA_VENDOR'          => $value['PEMILIK_REKENING'],
						'NO_INVOICE'           => $no_invoice,
						'NO_KONTRAK'           => NULL,
						'DESCRIPTION'          => $description,
						'DPP'                  => $value['FPJP_DETAIL_AMOUNT'],
						'ORIGINAL_AMOUNT_FPJP' => $value['ORIGINAL_AMOUNT'],
						'NO_FPJP'              => $value['FPJP_NUMBER'],
						'NAMA_REKENING'        => $value['PEMILIK_REKENING'],
						'NAMA_BANK'            => $value['NAMA_BANK'],
						'ACCT_NUMBER'          => $value['NO_REKENING'],
						'NATURE'               => $value['NATURE'],
						'NO_URUT_JURNAL'       => $no_urut,
						'CURRENCY'             => $value['CURRENCY']
					);

			$no_urut++;

    	}

		$valuetrue = $this->gl->insert_gl_header_import($data);

		if($valuetrue){
			if($this->crud->call_procedure("UPLOAD_BATCH") !== -1){
				if($this->crud->call_procedure("JURNAL_HEADERS") !== -1 && $this->crud->call_procedure("Journal_B_Tax") !== -1){
					return $no_jurnal;
				}
			}
		}else{
			return false;
		}
    }


    private function _email_approval($recipient, $id_fpjp, $type, $approval_remark="", $id_approval=0, $on_review=false){

		$get_fpjp      = $this->fpjp->get_fpjp_for_email($id_fpjp);
		
		$rkap          = $get_fpjp['RKAP_DESCRIPTION'];
		$currency      = ($get_fpjp['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_fpjp['FPJP_AMOUNT'],0,',','.');
		$fpjp_type     = get_type($get_fpjp['ID_MASTER_FPJP']);
		$fpjp_name     = $get_fpjp['FPJP_NAME'];
		$fpjp_number   = $get_fpjp['FPJP_NUMBER'];
		$attachment    = $get_fpjp['DOCUMENT_ATTACHMENT'];
		$submitter    = $get_fpjp['SUBMITTER'];
		$currency_rate = ($get_fpjp['CURRENCY'] == "IDR") ? $get_fpjp['CURRENCY'] : $get_fpjp['CURRENCY'] ."/". number_format($get_fpjp['CURRENCY_RATE'],0,'.',',');

		$justification = $get_fpjp['FS_NUMBER'] ." - " . $get_fpjp['FS_NAME'];

		$approval_lnk = base_url("fpjp/approval/").base64url_encode($id_fpjp."-".$id_approval.$this->config->item('encryption_key'));

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient'] = $recipient['name'];

		if($type == "request_approve"):
			$email_preview = "A new FPJP request $fpjp_number has been <b>approved</b> by $action_name and need for your approval";
		else:
			$email_preview = "Your FPJP request $fpjp_number has been <b>$type</b> by ".$action_name;
		endif;

		$data['email_preview']   = $email_preview;

		if($approval_remark != ""){
			$data['approval_remark'] = $approval_remark;
		}
		
		$data['action_name'] = $action_name;
		$data['submitter']   = $submitter;
		$data['fpjp_number'] = $fpjp_number;

		$data['fpjp_link'] = base_url("fpjp/").base64url_encode($id_fpjp.$this->config->item('encryption_key'));

		$body_email = "The FPJP request details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>Justification Name</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$justification</b></td>
										</tr>
										<tr>
											<td width='29%'>FPJP Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$fpjp_number</b></td>
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
									</tbody>
								</table>
								";


		if($on_review){
			$body_email .= "
							<br>
							<br>
							Your FPJP is currently under review by the accounting team";
		}

		$data['email_body'] = $body_email;

		$data['approval_link'] = base_url("fpjp/approval/").base64url_encode($id_fpjp."-".$id_approval.$this->config->item('encryption_key'));
		$data['approval_link_all'] = base_url("fpjp/approval");

		$file_view  = ($type=="request_approve") ? "fpjp_request_approval_approved" : "fpjp_".$type;
		$tile_first = ($type=="request_approve") ? "FPJP Approval Request" : "FPJP ".ucfirst($type);
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = $tile_first . " - " . $fpjp_number . " - " . $fpjp_name;
		$body       = $this->load->view('email/'.$file_view, $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }



	public function update_coa($encyprted){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "fpjp/update-coa/".$encyprted);
			redirect('login');
		}

		$decrypt = decrypt_string($encyprted, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify FPJP');
			redirect('/');
		}

		$id_fpjp        = $exp[0];
		$email_approval = $exp[1];
		$get_approval   = $this->crud->read_by_id("MASTER_APPROVAL", array("PIC_EMAIL" => $email_approval));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify FPJP Approval');
			redirect('/');
		}


		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
		
		if($check_exist > 0){

			$get_fpjp_header = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
			$get_fpjp_boq = $this->crud->read_by_param("FPJP_BOQ", array("FPJP_HEADER_ID" => $id_fpjp));
			$check_exist_boq = $this->crud->check_exist("FPJP_BOQ", array("FPJP_HEADER_ID" => $id_fpjp));

			$data['title']          = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = "fpjp_new/fpjp_edit";

			$group_name = get_user_group_data();
			$enableEdit = (in_array("FPJP Edit", $group_name)) ? true : false;

			$coa_edit  = false;
			if(in_array("FPJP COA", $group_name) && $get_fpjp_header['STATUS'] == 'approved'){
				$coa_edit  = true;
			}else{
				$this->session->set_flashdata('messages', 'This FPJP not allowed to edit');
				redirect('fpjp');
			}
			
			$data['id_fpjp']         = $id_fpjp;
			$data['id_fpjp_boq']     = $get_fpjp_boq['FPJP_BOQ_ID'];
			$data['id_fs']           = $get_fpjp_header['ID_FS'];
			$data['fs_link']         = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS'], true);
			$data['fpjp_number']     = $get_fpjp_header['FPJP_NUMBER'];
			$data['fpjp_name']       = $get_fpjp_header['FPJP_NAME'];
			$data['fpjp_date']       = dateFormat($get_fpjp_header['FPJP_DATE'], 5, false);
			$data['fpjp_amount']     = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
			$data['fpjp_currency']   = $get_fpjp_header['CURRENCY'];
			$data['fpjp_rate']       = number_format($get_fpjp_header['CURRENCY_RATE'],0,',','.');
			$data['fpjp_directorat'] = $get_fpjp_header['ID_DIR_CODE'];
			$data['fpjp_division']   = $get_fpjp_header['ID_DIVISION'];
			$data['fpjp_unit']       = $get_fpjp_header['ID_UNIT'];
			$data['fpjp_submitter']   = $get_fpjp_header['SUBMITTER'];
			$data['fpjp_jabatan_sub'] = $get_fpjp_header['JABATAN_SUBMITTER'];
			$data['fpjp_attachment']  = $get_fpjp_header['DOCUMENT_ATTACHMENT'];
			$data['fpjp_status']      = ($get_fpjp_header['STATUS'] == "request_approve") ? "Waiting for approval" : ucfirst($get_fpjp_header['STATUS']);
			$data['fpjp_status_desc'] = $get_fpjp_header['STATUS_DESCRIPTION'];
			$data['fpjp_last_update'] = dateFormat($get_fpjp_header['UPDATED_DATE'], "with_day", false);
			$data['coa_edit']    = $coa_edit;
			$data['enable_edit']    = $enableEdit;
			$data['fpjp_boq']      = ($check_exist_boq > 0 ) ? true : false;

			$get_approval = $this->fpjp->get_approval_by_fpjp($id_fpjp);

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

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "FPJP", "link" => base_url('fpjp'), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FPJP Not Exist');
			redirect('fpjp');

		}

	}


	public function confirm_coa($encyprted){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "fpjp/confirm-coa/".$encyprted);
			redirect('login');
		}

		$decrypt = decrypt_string($encyprted, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify FPJP');
			redirect('/');
		}

		$id_fpjp        = $exp[0];
		$email_approval = $exp[1];
		$get_approval   = $this->crud->read_by_id("MASTER_APPROVAL", array("PIC_EMAIL" => $email_approval));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify FPJP Approval');
			redirect('/');
		}

		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
		
		if($check_exist > 0){

			$get_fpjp_header = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
			$get_fpjp_boq = $this->crud->read_by_param("FPJP_BOQ", array("FPJP_HEADER_ID" => $id_fpjp));

			$id_fpjp_enc = base64url_encode($id_fpjp.$this->config->item('encryption_key'));

			$data['title']          = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = "fpjp_new/fpjp_view";

			$coa_review = $get_fpjp_header['COA_REVIEW'];

			$data['coa_review'] = true;
			if($coa_review != 'Y'){
				$dataUpdateFpjp = array(
						"PIC_COA"         => $this->user_active,
						"COA_REVIEW"      => "Y",
						"COA_REVIEW_DATE" => date("Y-m-d H:i:s", time())
					);
				$this->crud->update("FPJP_HEADER", $dataUpdateFpjp, array("FPJP_HEADER_ID" => $id_fpjp));
				/*$no_jurnal = $this->_invoicing_to_gl($id_fpjp);
				if( $no_jurnal ){
					$get_user_invoice = get_user_approval_ap("INVOICE_APPROVAL");
					if($get_user_invoice)
					{
						$recipient = array();
						foreach ($get_user_invoice as $key => $value) 
						{
							$recipient[]  = array('name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
						}
						$this->_email_invoice($recipient, $no_jurnal, $get_fpjp_header['SUBMITTER']);
					}
				}*/
			}
			
			$data['id_fpjp']         = $id_fpjp;
			$data['id_fpjp_enc']     = $id_fpjp_enc;
			$data['id_fs']           = $get_fpjp_header['ID_FS'];
			$data['fs_link']         = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS'], true);
			$data['fpjp_number']     = $get_fpjp_header['FPJP_NUMBER'];
			$data['fpjp_type']       = $get_fpjp_header['ID_MASTER_FPJP'];
			$data['fpjp_name']       = $get_fpjp_header['FPJP_NAME'];
			$data['fpjp_boq']        = $get_fpjp_boq['FPJP_HEADER_ID'];
			$data['fpjp_date']       = dateFormat($get_fpjp_header['FPJP_DATE'], 5, false);
			$data['fpjp_amount']     = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
			$data['fpjp_currency']   = $get_fpjp_header['CURRENCY'];
			$data['fpjp_rate']       = number_format($get_fpjp_header['CURRENCY_RATE'],0,',','.');
			$data['fpjp_directorat'] = $get_fpjp_header['ID_DIR_CODE'];
			$data['fpjp_division']   = $get_fpjp_header['ID_DIVISION'];
			$data['fpjp_unit']        = $get_fpjp_header['ID_UNIT'];
			$data['fpjp_submitter']   = $get_fpjp_header['SUBMITTER'];
			$data['fpjp_jabatan_sub'] = $get_fpjp_header['JABATAN_SUBMITTER'];
			$data['fpjp_attachment']  = $get_fpjp_header['DOCUMENT_ATTACHMENT'];
			$data['fpjp_status']      = ($get_fpjp_header['STATUS'] == "request_approve") ? "Waiting for approval" : ucfirst($get_fpjp_header['STATUS']);
			$data['fpjp_justif_type']      = ($get_fpjp_header['JUSTIF_TYPE'] == "non_justif") ? "Non Justification" : "Justification";
			$data['fpjp_status_desc'] = $get_fpjp_header['STATUS_DESCRIPTION'];
			$last_update = ($get_fpjp_header['UPDATED_DATE']) ? $get_fpjp_header['UPDATED_DATE'] : $get_fpjp_header['CREATED_DATE'];
			$data['fpjp_last_update'] = dateFormat($last_update, "with_day", false);
			$attachment = false;
			if($get_fpjp_header['ID_FS'] > 0){
				$get_attachment = $this->crud->read_by_param_specific("FS_BUDGET", array("ID_FS" => $get_fpjp_header['ID_FS']), "DOCUMENT_ATTACHMENT, CREATED_DATE, SUBMITTER");
				if($get_attachment){
					$get_attachment = $get_attachment[0];
					if($get_attachment['DOCUMENT_ATTACHMENT']){
						$attachment = array(
										"FILE_NAME"     => $get_attachment['DOCUMENT_ATTACHMENT'],
										// "FILE_LINK"     => base_url("uploads/").$get_attachment['DOCUMENT_ATTACHMENT'],
										"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/".$get_attachment['DOCUMENT_ATTACHMENT'], true),
										"DATE_UPLOADED" => strtotime($get_attachment['CREATED_DATE']),
										"UPLOADED_BY"   => $get_attachment['SUBMITTER']
										);
						
					}
				}
			}
			
			$data['fpjp_no_invoice']   = ($get_fpjp_header['NO_INVOICE']) ? $get_fpjp_header['NO_INVOICE'] : "-";
			$data['fpjp_invoice_date'] = ($get_fpjp_header['INVOICE_DATE']) ? dateFormat($get_fpjp_header['INVOICE_DATE'], 5, false) : "-";
			$data['fpjp_top']          = ($get_fpjp_header['TOP']) ? $get_fpjp_header['TOP'] : "-";
			$data['fpjp_due_date']     = ($get_fpjp_header['INVOICE_DUE_DATE']) ? dateFormat($get_fpjp_header['INVOICE_DUE_DATE'], 5, false) : "-";
			$data['fpjp_doc_list']     = ($get_fpjp_header['DOCUMENT_CHECKLIST']) ? json_decode($get_fpjp_header['DOCUMENT_CHECKLIST']) : "-";
			$data['fpjp_doc_upload']   = ($get_fpjp_header['DOCUMENT_UPLOAD']) ? base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$get_fpjp_header['DOCUMENT_UPLOAD'], true) : "-";

			$data['fs_attachment'] = $attachment;

			$get_approval = $this->fpjp->get_approval_by_fpjp($id_fpjp);

			$approval = array();
			$approval_remark = array();

			foreach ($get_approval as $key => $value) {
				$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['CATEGORY']);
				if(!empty($value['REMARK'])){
					$approval_remark[] = $value;
				}
			}
			$data['fpjp_approval'] = $approval;

			if(count($approval_remark) > 0){
				foreach ($approval_remark as $key => $v)
				{
			    	$sort[$key] = strtotime($v['UPDATED_DATE']);
				}
				array_multisort($sort, SORT_DESC, $approval_remark);

				$data['fpjp_approval_remark'] = $approval_remark[0]['REMARK'];
			}


			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "FPJP", "link" => base_url("fpjp"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FPJP Not Exist');
			redirect('fpjp/approval');

		}

	}

    private function _email_review_coa($recipient, $id_fpjp){

		$get_fpjp        = $this->fpjp->get_fpjp_for_email_accounting($id_fpjp);
		$get_fpjp_header = $get_fpjp[0];
		
		$currency        = ($get_fpjp_header['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount          = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
		$fpjp_type       = get_type($get_fpjp_header['ID_MASTER_FPJP']);
		$fpjp_name       = $get_fpjp_header['FPJP_NAME'];
		$fpjp_number     = $get_fpjp_header['FPJP_NUMBER'];
		$attachment      = $get_fpjp_header['DOCUMENT_ATTACHMENT'];
		$submitter       = $get_fpjp_header['SUBMITTER'];
		$currency_rate   = ($get_fpjp_header['CURRENCY'] == "IDR") ? $get_fpjp_header['CURRENCY'] : $get_fpjp_header['CURRENCY'] ."/". number_format($get_fpjp_header['CURRENCY_RATE'],0,'.',',');

		$addedBody = "";
		$addedFpjpType = "";
		if($get_fpjp_header['FS_NUMBER']){
			$justification = $get_fpjp_header['FS_NUMBER'] ." - " . $get_fpjp_header['FS_NAME'];
			$rkap          = $get_fpjp_header['RKAP_DESCRIPTION'];
			$addedBody .= "<tr>
								<td width='29%'>Justification Name</td>
								<td width='1%'>:</td>
								<td width='70%'><b>$justification</b></td>
							</tr>";
			$addedBody .= "<tr>
								<td>RKAP Name</td>
								<td>:</td>
								<td><b>$rkap</b></td>
							</tr>";
		}else{
			$addedFpjpType = " (Non Justif)";
		}

		$action_name = get_user_data($this->session->userdata('user_id'));

		$email_preview = "A new FPJP $fpjp_number has been <b>approved</b> by $action_name and need for your review of selected CoA in this FPJP";

		$data['email_preview']   = $email_preview;

		$data['action_name'] = $action_name;
		$data['submitter']   = $submitter;
		$data['fpjp_number'] = $fpjp_number;

		$email_body = " A new FPJP $fpjp_number has been <b>approved</b> by $action_name and need for your review of selected CoA in this FPJP.
		<br>
		<br>
		The FPJP details are:
								<br>
								<table>
									<tbody>
										$addedBody
										<tr>
											<td width='29%'>FPJP Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$fpjp_number</b></td>
										</tr>
										<tr>
											<td width='29%'>FPJP Type</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$fpjp_type$addedFpjpType</b></td>
										</tr>
										<tr>
											<td>$currency</td>
											<td>:</td>
											<td><b>$currency_rate</b></td>
										</tr>
										<tr>
											<td>Submitter</td>
											<td>:</td>
											<td><b>$submitter</b></td>
										</tr>
										<tr>
											<td>Total Amount</td>
											<td>:</td>
											<td><b>$amount</b></td>
										</tr>
									</tbody>
								</table>
								<br>
								";

		$email_body .= "
								<br>
								  <div class='hack1'>
								  	<div class='hack2'>
										<table class='custom_table'>
											<tbody>
												<tr>
														<th>No</th>
														<th>Description</th>
														<th>Nature</th>
														<th>TAX</th>
														<th>Total AMount</th>
												</tr>";

		$no = 1;

		foreach ($get_fpjp as $key => $value) {

			$fpjp_desc        = $value['FPJP_DETAIL_DESC'];
			$nature           = $value['NATURE'] . " - " . $value['DESCRIPTION'];
			$tax              = $value['TAX'];
			$total_amount     = number_format($value['FPJP_DETAIL_AMOUNT'],0,'.',',');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$fpjp_desc."</td>";
				$email_body .= "<td>".$nature."</td>";
				$email_body .= "<td>".$tax."</td>";
				$email_body .= "<td>".$total_amount."</td>";
			$email_body .= "</tr>";
			$no++;

		}

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$cc = "";
		$subject    = "FPJP request CoA Review " .  $fpjp_number;

		foreach ($recipient as $key => $value) {
			$data['email_recipient']  = $value['name'];
			$encrypted                = encrypt_string($id_fpjp."-".$value['email'], true);
			$data['link_edit_coa']    = base_url('fpjp/update-coa/').$encrypted;
			$data['link_confirm_coa'] = base_url('fpjp/confirm-coa/').$encrypted;

			$to   = $value['email'];
			$body = $this->load->view('email/review_coa', $data, TRUE);
			$send = sendemail($to, $subject, $body, $cc);
		}

		return true;
    }


    private function _email_invoice($recipient, $no_journal, $submitter){

		$this->load->model('GL_mdl', 'gl');
		$get_journal = $this->gl->get_journal_by_no_journal($no_journal);

		$data['email_preview'] = "A new invoice has been submitted for your approval with Batch Approval ".$no_journal;
		
		$action_name = $submitter;

		$email_preview = "A new invoice with No Journal $no_journal has been submitted by $action_name and need to validate";

		$email_body = "A new invoice with No Journal <b>$no_journal</b> has been submitted by <b>$action_name</b> and need to validate. 
					<br>
					<br>
		Invoice List:
								<br>
								  <div class='hack1'>
								  	<div class='hack2'>
										<table class='custom_table'>
											<tbody>
												<tr>
														<th>No</th>
														<th>Transaction Date</th>
														<th>No Invoice</th>
														<th>Batch Name</th>
														<th>No Journal</th>
														<th>DPP</th>
												</tr>";

		$no = 1;

		foreach ($get_journal as $key => $value) {

			$transaction_date = date("d-m-Y",strtotime($value['TGL_INVOICE']));
			$no_invoice       = $value['NO_INVOICE'];
			$batch_name       = $value['BATCH_NAME'];
			$no_journal       = $value['NO_JOURNAL'];
			$dpp              = number_format($value['DPP'],0,'.',',');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$transaction_date."</td>";
				$email_body .= "<td>".$no_invoice."</td>";
				$email_body .= "<td>".$batch_name."</td>";
				$email_body .= "<td>".$no_journal."</td>";
				$email_body .= "<td>".$dpp."</td>";
			$email_body .= "</tr>";
			$no++;

		}

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_name), true);
		$data['link'] = base_url("gl/gl-header");
		$data['link_display'] = "Financetools Invoice Tax ".$no_journal;

		/*$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}*/
		$subject    = "Invoice Journal  - $no_journal";
		// $body       = $this->load->view('email/ap_invoice_tax', $data, TRUE);

		// $send = sendemail($to, $subject, $body, $cc);

		foreach ($recipient as $key => $value) {
			$data['email_recipient']  = $value['name'];
			$to   = $value['email'];
			$body = $this->load->view('email/ap_invoice_tax', $data, TRUE);
			$send = sendemail($to, $subject, $body);
		}

		return true;
	}

}

/* End of file ApprovalFPJP_ctl.php */
/* Location: ./application/controllers/fpjp_new/ApprovalFPJP_ctl.php */