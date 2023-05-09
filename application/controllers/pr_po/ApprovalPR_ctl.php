<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalPR_ctl extends CI_Controller {

	private $module_name = "pr_po",
			$user_cc  = array(),
			$module_url  = "purchase-requisition";

	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');

		$list_cc['aldji']   = 'aldji_i_kahar@linkaja.id';
		$list_cc['susanto'] = 'susanto_wu@linkaja.id';
		$list_cc['dita']    = 'dita_lestari@linkaja.id';
		$list_cc['wahyu']   = 'wahyu_bijaksana@linkaja.id';
		$list_cc['ardika']  = 'ardika_widyantoro@linkaja.id';

		$this->user_cc = $list_cc;

	}

	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "pr/approval");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->purchase->check_is_approval($this->session->userdata('email'));

		if($check_is_approval){

			$get_pr_for_approval = $this->purchase->get_pr_for_approval($this->session->userdata('email'));

			$id_pr = array();

			if($get_pr_for_approval){
				foreach ($get_pr_for_approval as $value) {
					$id_pr[] = $value['PR_HEADER_ID'];
				}
			}

			$data['title']          = "All Purchase Requisition";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/pr_approval";
			
			$data['id_pr']  = json_encode($id_pr);

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

	public function assign_pr()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "pr/assign_pr");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->purchase->check_is_pr_assigner($this->session->userdata('email'));


		if($check_is_approval){

			$get_pr_for_assign = $this->purchase->get_pr_for_assign($this->session->userdata('email'));

			$id_pr = array();

			if($get_pr_for_assign){
				foreach ($get_pr_for_assign as $value) {
					$id_pr[] = $value['PR_HEADER_ID'];
				}
			}

			$data['title']          = "All Purchase Requisition";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/assign_pr";
			
			$data['id_pr']  = json_encode($id_pr);

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
			$this->session->set_flashdata("redirect_page", "pr/approval/".$id_approval);
			redirect('login');
		}

		$decrypt = decrypt_string($id_approval, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify PR');
			redirect('/');
		}

		$id_pr       = $exp[0];
		$id_approval = $exp[1];
		$get_trx     = $this->crud->read_by_id("TRX_APPROVAL_PR", array("ID" => $id_approval));
		$get_approval = $this->crud->read_by_id("MASTER_APPROVAL", array("ID_APPROVAL" => $get_trx['ID_APPROVAL']));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->session->userdata('email'))){
			$this->session->set_flashdata('messages', 'Error verify PR Approval');
			redirect('/');
		}

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));
		
		if($check_exist > 0){

			$pic_email     = $this->session->userdata('email');
			$get_pr_header = $this->purchase->get_pr_to_approve_by_id($id_pr, $pic_email);

			$data['title']          = "PR ".$get_pr_header['PR_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/pr_approval_single";
			
			$data['id_pr']          = $id_pr;
			$id_fs = $get_pr_header['ID_FS'];
			$data['id_fs']          = $id_fs;
			$dpl_number = "";
			if($id_fs > 0){
				$get_dpl = $this->crud->read_by_param("DPL", array("ID_FS" => $id_fs));

				if($get_dpl){
					$dpl_number       = $get_dpl['DPL_NUMBER'];
					$data['dpl_link'] = base_url("dpl/") . encrypt_string($get_dpl['ID_DPL'], true);
				}
			}

			$data['dpl_number'] = $dpl_number;
			$data['fs_link']        = base_url("feasibility-study/") . encrypt_string($id_fs, true);
			$data['pr_number']      = $get_pr_header['PR_NUMBER'];
			$data['pr_name']        = $get_pr_header['PR_NAME'];
			$data['pr_date']        = dateFormat($get_pr_header['PR_DATE'], 4, false);
			$data['pr_amount']      = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
			$data['pr_currency']    = $get_pr_header['CURRENCY'];
			$data['pr_rate']        = number_format($get_pr_header['CURRENCY_RATE'],0,',','.');
			$data['pr_directorat']  = $get_pr_header['ID_DIR_CODE'];
			$data['pr_division']    = $get_pr_header['ID_DIVISION'];
			$data['pr_unit']        = $get_pr_header['ID_UNIT'];
			$data['pr_submitter']   = $get_pr_header['SUBMITTER'];
			$data['pr_jabatan_sub'] = $get_pr_header['JABATAN_SUBMITTER'];
			$data['pr_attachment']  = $get_pr_header['DOCUMENT_ATTACHMENT'];
			$data['pr_status']      = ($get_pr_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_pr_header['STATUS']);
			$data['pr_status_desc'] = $get_pr_header['STATUS_DESCRIPTION'];
			$last_update            = ($get_pr_header['UPDATED_DATE']) ? $get_pr_header['UPDATED_DATE'] : $get_pr_header['CREATED_DATE'];
			$data['pr_last_update'] = dateFormat($last_update, "with_day", false);
			$data['trx_status']     = $get_pr_header['TRX_STATUS'];
			$data['trx_date']       = dateFormat($get_pr_header['TRX_DATE'], "with_day", false);

			$data['level']          = $get_pr_header['LEVEL'];
			$data['level']          = $get_pr_header['LEVEL'];

			if($get_pr_header['LEVEL'] > 1):
				// $level_min = $get_pr_header['LEVEL']-1;
				$get_approver_before            = $this->purchase->get_approval_before($id_pr);
				$data['approver_before_name']   = ($get_approver_before) ? $get_approver_before['PIC_NAME'] : false;
				$data['approver_before_remark'] = ($get_approver_before) ? $get_approver_before['REMARK'] : false;
				$data['approver_before_date']   = ($get_approver_before) ? dateFormat($get_approver_before['TRX_DATE'], "with_day", false) : false;
			endif;

			
			$pr_document = false;
			if($get_pr_header['DOCUMENT_UPLOAD']):
				// $data['pr_document_link'] = base_url("download/") . encrypt_string("uploads/pr_attachment/".$get_pr_header['DOCUMENT_UPLOAD'], true);
				$pr_document[] = array(
									"FILE_NAME"     => $get_pr_header['DOCUMENT_UPLOAD'],
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/pr_attachment/".$get_pr_header['DOCUMENT_UPLOAD'], true),
									"DATE_UPLOADED" => strtotime($get_pr_header['CREATED_DATE']),
									"UPLOADED_BY"   => $get_pr_header['SUBMITTER']
									);
			endif;
			$data['pr_document'] = $pr_document;

			$is_procurement_hou = $this->crud->read_by_param_specific("MASTER_APPROVAL", array("PIC_LEVEL" => "HOU Procurement", "PIC_EMAIL" => $this->session->userdata('email')));
			$is_proc_support = $this->crud->read_by_param_specific("MASTER_APPROVAL", array("PIC_LEVEL" => "Procurement", "PIC_EMAIL" => $this->session->userdata('email')));

			$hou_procurement = false;
			if($is_procurement_hou){
				$hou_procurement = true;
				$get_pr_category = $this->purchase->get_category_type_pr();
				$data['pr_category'] = $get_pr_category;
				$data['po_buyer'] = $this->purchase->get_all_buyer();
			}
			$data['hou_procurement'] = $hou_procurement;

			$proc_support = false;
			if($is_proc_support){
				$proc_support = true;
			}
			$data['proc_support'] = $proc_support;
			$doc_checklist = json_decode($get_pr_header['DOCUMENT_CHECKLIST']);
			$arr_check_doc = array();

			if($doc_checklist){
				foreach ($doc_checklist as $key => $value) {
					$arr_check_doc[] = $key;
				}
			}
			$data['doc_checklist'] = $arr_check_doc;

			$pr_history = get_pr_history($id_pr, $get_pr_header);
			$data['pr_history'] = $pr_history;
			$last_update = end($pr_history);
			$data['pr_last_update'] = ucfirst($last_update['STATUS']) . " by " . $last_update['PIC_NAME'] . " at " . $last_update['ACTION_DATE'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All PR Request", "link" => base_url("pr/approval"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('pr/approval');

		}

	}


	public function assign_single($id_pr){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "pr/assign/".$id_pr);
			redirect('login');
		}

		$decrypt = decrypt_string($id_pr, true);
		$id_pr   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

		if($check_exist > 0){
			$pic_email     = $this->session->userdata('email');
			$get_pr_header = $this->purchase->get_pr_to_assign_by_id($id_pr);

			$data['title']          = "PR ".$get_pr_header['PR_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/pr_assign_single";
			
			$data['id_pr']          = $id_pr;
			$data['id_fs']          = $get_pr_header['ID_FS'];

			$id_fs = $get_pr_header['ID_FS'];
			$data['id_fs']          = $id_fs;
			$dpl_number = "";
			if($id_fs > 0){
				$get_dpl = $this->crud->read_by_param("DPL", array("ID_FS" => $id_fs));

				if($get_dpl){
					$dpl_number       = $get_dpl['DPL_NUMBER'];
					$data['dpl_link'] = base_url("dpl/") . encrypt_string($get_dpl['ID_DPL'], true);
				}
			}

			$data['dpl_number'] = $dpl_number;
			$data['fs_link']        = base_url("feasibility-study/") . encrypt_string($id_fs, true);
			$data['pr_number']      = $get_pr_header['PR_NUMBER'];
			$data['pr_name']        = $get_pr_header['PR_NAME'];
			$data['pr_date']        = dateFormat($get_pr_header['PR_DATE'], 4, false);
			$data['pr_amount']      = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
			$data['pr_currency']    = $get_pr_header['CURRENCY'];
			$data['pr_rate']        = number_format($get_pr_header['CURRENCY_RATE'],0,',','.');
			$data['pr_directorat']  = $get_pr_header['ID_DIR_CODE'];
			$data['pr_division']    = $get_pr_header['ID_DIVISION'];
			$data['pr_unit']        = $get_pr_header['ID_UNIT'];
			$data['pr_submitter']   = $get_pr_header['SUBMITTER'];
			$data['pr_jabatan_sub'] = $get_pr_header['JABATAN_SUBMITTER'];
			$data['pr_attachment']  = $get_pr_header['DOCUMENT_ATTACHMENT'];
			$data['pr_status']      = ($get_pr_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_pr_header['STATUS']);
			$data['pr_status_desc'] = $get_pr_header['STATUS_DESCRIPTION'];
			$last_update            = ($get_pr_header['UPDATED_DATE']) ? $get_pr_header['UPDATED_DATE'] : $get_pr_header['CREATED_DATE'];
			$data['assign_status'] = $get_pr_header['STATUS_ASSIGN'];
			$data['assign_date'] = $get_pr_header['ASSIGN_DATE'];
			$data['pr_last_update'] = dateFormat($last_update, "with_day", false);
			
			$pr_document = false;
			if($get_pr_header['DOCUMENT_UPLOAD']):
				// $data['pr_document_link'] = base_url("download/") . encrypt_string("uploads/pr_attachment/".$get_pr_header['DOCUMENT_UPLOAD'], true);
				$pr_document[] = array(
									"FILE_NAME"     => $get_pr_header['DOCUMENT_UPLOAD'],
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/pr_attachment/".$get_pr_header['DOCUMENT_UPLOAD'], true),
									"DATE_UPLOADED" => strtotime($get_pr_header['CREATED_DATE']),
									"UPLOADED_BY"   => $get_pr_header['SUBMITTER']
									);
			endif;
			$data['pr_document'] = $pr_document;

			$is_procurement_hou = $this->crud->read_by_param_specific("MASTER_APPROVAL", array("PIC_LEVEL" => "HOU Procurement", "PIC_EMAIL" => $this->session->userdata('email')));
			$is_proc_support = $this->crud->read_by_param_specific("MASTER_APPROVAL", array("PIC_LEVEL" => "Procurement", "PIC_EMAIL" => $this->session->userdata('email')));

			$hou_procurement = false;
			if($is_procurement_hou){
				$hou_procurement = true;
				$get_pr_category = $this->purchase->get_category_type_pr();
				$data['pr_category'] = $get_pr_category;
				$data['po_buyer'] = $this->purchase->get_all_buyer();
			}
			$data['hou_procurement'] = $hou_procurement;

			$proc_support = false;
			if($is_proc_support){
				$proc_support = true;
			}
			$data['proc_support'] = $proc_support;
			$doc_checklist =json_decode($get_pr_header['DOCUMENT_CHECKLIST']);
			$arr_check_doc = array();
			foreach ($doc_checklist as $key => $value) {
				$arr_check_doc[] = $key;
			}
			$data['doc_checklist'] = $arr_check_doc;

			$status_assign = "-";
			if($get_pr_header['STATUS_ASSIGN'] && ($get_pr_header['STATUS'] == "approved" || $get_pr_header['STATUS'] == "po created") ){

				$buyer_name = ($get_pr_header['PO_BUYER']) ? $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $get_pr_header['PO_BUYER'])) : '';
				$status_assign = ($get_pr_header['STATUS_ASSIGN'] == "N") ? "Waiting for assign" : $buyer_name['PIC_NAME'];
			}

			$buyer_name = ($get_pr_header['PO_BUYER']) ? $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $get_pr_header['PO_BUYER'])) : '';

			$data['status_assign'] = $status_assign;
			
			$pr_history = get_pr_history($id_pr, $get_pr_header);
			$data['pr_history'] = $pr_history;
			$last_update = end($pr_history);
			$data['pr_last_update'] = ucfirst($last_update['STATUS']) . " by " . $last_update['PIC_NAME'] . " at " . $last_update['ACTION_DATE'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All PR Request", "link" => base_url("pr/assign"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('pr/approval');

		}

	}


	public function action_approval(){
		
		$id_pr           = $this->input->post('id_pr');
		$level           = $this->input->post('level');
		$remark          = $this->input->post('remark');
		$approval        = $this->input->post('approval');
		$hou_procurement = $this->input->post('hou_procurement');
		$proc_support    = $this->input->post('proc_support');
		$pr_category     = $this->input->post('pr_category');
		$po_buyer        = $this->input->post('po_buyer');
		$pic_email       = $this->session->userdata('email');

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
		$update = $this->crud->update("TRX_APPROVAL_PR", $data, array("PR_HEADER_ID" => $id_pr, "LEVEL" => $level, "IS_ACTIVE" => 1));

		if($update !== -1){

			$get_submitter   =  $this->purchase->get_submitter_by_id_pr($id_pr);
			$submitter_email = "";
			$submitter_name  = "";

			if($get_submitter){
				$submitter_email = $get_submitter['PIC_EMAIL'];
				$submitter_name  = $get_submitter['PIC_NAME'];
			}
			$email_cc      = array();

			if($level > 1){
				for ($i=1; $i < $level ; $i++) {
					$get_approver_before = $this->purchase->get_approver($id_pr, $i);
					if($get_approver_before){
						if($get_approver_before['CATEGORY'] != "BOD"){
							$email_cc[] = $get_approver_before['PIC_EMAIL'];
						}
					}
				}
			}

			if($proc_support == "1"){
				$get_multi_approval = $this->purchase->get_multi_approval($id_pr, "Procurement");

				foreach ($get_multi_approval as $key => $value) {
					if($value['PIC_EMAIL'] != $pic_email){
						$this->crud->delete("TRX_APPROVAL_PR", $value['ID']);
					}
				}
				$get_list_trx = $this->purchase->get_list_trx($id_pr);
				
				$newLevel = 1;
				$dataNw   = array();
				foreach ($get_list_trx as $key => $value) {
					$dataNw[] = array("LEVEL" => $newLevel, "ID" => $value['ID']);
					if(strtolower($value['CATEGORY']) == "procurement"){
						$level = $newLevel;
					}
					$newLevel++;
				}

				$newApprovalTotal = count($dataNw);

				if($newApprovalTotal > 0){
					$this->crud->update_batch_data("TRX_APPROVAL_PR", $dataNw, "ID");
					$dataPrUpdt['APPROVAL_LEVEL'] = $newApprovalTotal;
				}
			}


			if($status == "approved"):
				$next_level = $level+1;
				$get_next_aprover = $this->purchase->get_approver($id_pr, $next_level);
				if($get_next_aprover){
					$id_approval = $get_next_aprover['ID'];

					if($submitter_email != ""){
						$recipient['email_cc'] = $submitter_email;
					}

					$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
					$recipient['name']  = $get_next_aprover['PIC_NAME'];

					/*if($proc_support == "1" && $doc_list != ""){
						$doc_lists = array();
						foreach ($doc_list as $value) {
							$val = strtolower(str_replace(" ", "_", $value));
							$doc_lists[$val] = true;
						}
						$dataPrUpdt['DOCUMENT_CHECKLIST'] = (is_array($doc_lists)) ? json_encode($doc_lists) : '';
					}*/

					$this->_email_approval($recipient, $id_pr, "request_approve", $remark, $id_approval);
					$this->crud->update("TRX_APPROVAL_PR", array("STATUS" => "request_approve"), $id_approval);

					$dataPrUpdt['STATUS_DESCRIPTION'] = "Verified by ".get_user_data($this->session->userdata('user_id'));
				}else{

					$dataPrUpdt['STATUS'] = $status;

					if($submitter_email != "" && $submitter_name != ""){

						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;

						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}
						$this->_email_approval($recipient, $id_pr, $status, $remark);
					}
					$dataPrUpdt['COA_REVIEW'] = "N";
					$dataPrUpdt['STATUS_ASSIGN'] = "N";
					$dataPrUpdt['INTERFACE_STATUS'] = "NEW";
					$dataPrUpdt['STATUS_DESCRIPTION'] = "Approved by ".get_user_data($this->session->userdata('user_id'));


					$recipient_accounting = array();
					$approver_accounting = get_approval_by_category('Accounting');
					foreach ($approver_accounting as $key => $value):
						$recipient_accounting[]  = array('name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
					endforeach;
					if(count($recipient_accounting) > 0){
						$this->_email_review_coa($recipient_accounting, $id_pr);
					}

					$approver_hou = get_approval_by_category('HOU Procurement');

					if($approver_hou){

						$approver_hou = $approver_hou[0];

						$recipient['email'] = $approver_hou['PIC_EMAIL'];
						$recipient['name']  = $approver_hou['PIC_NAME'];

						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}
						$this->_email_to_hou_proc($recipient, $id_pr, $remark);
					}
				}

				$this->crud->update("PR_HEADER", $dataPrUpdt, array("PR_HEADER_ID" => $id_pr));

			else:
				if($submitter_email != "" && $submitter_name != ""){

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;
					
					$this->_email_approval($recipient, $id_pr, $status, $remark);
				}
				
				// $this->_email_to_hou_buyer($recipient, $id_pr, $status, $remark);
				$this->crud->update("PR_HEADER", array("STATUS" => $status, "STATUS_DESCRIPTION" => ucfirst($status)." by ".get_user_data($this->session->userdata('user_id'))), array("PR_HEADER_ID" => $id_pr));

			endif;

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		echo json_encode($result);

	}
	

	public function action_assign(){
		
		$id_pr           = $this->input->post('id_pr');
		$remark          = $this->input->post('remark');
		$pr_category     = $this->input->post('pr_category');
		$po_buyer        = $this->input->post('po_buyer');

		$result['status'] = false;
		$result['messages'] = "Failed to assign PR";

		$dataPrUpdt = array("STATUS_ASSIGN" => "Y", "PIC_ASSIGN" => get_user_data($this->session->userdata('user_id')), "REMARK_ASSIGN" => $remark, "ASSIGN_DATE" => date("Y-m-d H:i:s", time()));

		if($id_pr){

			$get_submitter   =  $this->purchase->get_submitter_by_id_pr($id_pr);
			$submitter_email = "";
			$submitter_name  = "";

			if($get_submitter){
				$submitter_email = $get_submitter['PIC_EMAIL'];
				$submitter_name  = $get_submitter['PIC_NAME'];
			}
			$cc_email   = $this->user_cc;
			$email_cc[] = $cc_email['aldji'];
			$email_cc[] = $cc_email['wahyu'];
			$email_cc[] = $cc_email['susanto'];
			$email_cc[] = $cc_email['dita'];
			$email_cc[] = $cc_email['ardika'];

			if($submitter_email != ""){
				$email_cc[] = $submitter_email;
			}

			if(count($email_cc) > 0){
				$recipient['email_cc'] = $email_cc;
			}

			if($pr_category){

				$get_buyer                 = $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $po_buyer));
				$recipient['email']        = $get_buyer['PIC_EMAIL'];
				$recipient['name']         = $get_buyer['PIC_NAME'];
				$dataPrUpdt['PR_CATEGORY'] = $pr_category;
				$dataPrUpdt['PO_BUYER']    = $po_buyer;

				$this->_email_to_buyer($recipient, $id_pr, $remark);
			}

			$this->crud->update("PR_HEADER", $dataPrUpdt, array("PR_HEADER_ID" => $id_pr));

			$result['status']   = true;
			$result['messages'] = "PR successfully assign";
		}

		echo json_encode($result);

	}
	
	public function load_pr_to_approve(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$pic_email = $this->session->userdata('email');

		$get_all         = $this->purchase->get_pr_to_approve($pic_email, $status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];

				$status_assign = "-";
				if($value['STATUS_ASSIGN'] && ($value['STATUS'] == "approved" || $value['STATUS'] == "po created") ){

					$buyer_name = ($value['PO_BUYER']) ? $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $value['PO_BUYER'])) : '';
					$status_assign = ($value['STATUS_ASSIGN'] == "N") ? "Waiting for assign" : $buyer_name['PIC_NAME'];
				}

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['PR_HEADER_ID'],
						'id_pr'              => encrypt_string($value['PR_HEADER_ID'], true),
						'id_pr_approval'     => encrypt_string($value['PR_HEADER_ID']."-".$value['ID_PR_APPROVAL'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'pr_number'          => $value['PR_NUMBER'],
						'pr_name'            => $value['PR_NAME'],
						'pr_currency'        => $value['CURRENCY'],
						'pr_rate'            => $value['CURRENCY_RATE'],
						'status'             => $value['STATUS'],
						'level'              => $value['LEVEL'],
						'status_description' => $status_description,
						'status_assign'      => $status_assign,
						'fs_date'            => dateFormat($value['PR_DATE'], 5, false),
						'total_amount'       => number_format($value['PR_AMOUNT'],0,',','.')
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
	
	public function load_pr_to_assign(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');

		$get_all         = $this->purchase->get_pr_to_assign($status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];


				$status_assign = "-";
				if($value['STATUS_ASSIGN'] && ($value['STATUS'] == "approved" || $value['STATUS'] == "po created") ){

					$buyer_name = ($value['PO_BUYER']) ? $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $value['PO_BUYER'])) : '';
					$status_assign = ($value['STATUS_ASSIGN'] == "N") ? "Waiting for assign" : $buyer_name['PIC_NAME'];
				}


				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['PR_HEADER_ID'],
						'id_pr'              => encrypt_string($value['PR_HEADER_ID'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'pr_number'          => $value['PR_NUMBER'],
						'pr_name'            => $value['PR_NAME'],
						'pr_currency'        => $value['CURRENCY'],
						'pr_rate'            => $value['CURRENCY_RATE'],
						'status'             => $value['STATUS'],
						'status_description' => $status_description,
						'status_assign'      => $status_assign,
						'fs_date'            => dateFormat($value['PR_DATE'], 5, false),
						'total_amount'       => number_format($value['PR_AMOUNT'],0,',','.')
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


    private function _email_approval($recipient, $id_pr, $type, $approval_remark="", $id_approval=0){

		$get_pr      = $this->purchase->get_pr_for_email($id_pr);

		$currency      = ($get_pr['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_pr['PR_AMOUNT'],0,',','.');
		$pr_name       = $get_pr['PR_NAME'];
		$pr_number     = $get_pr['PR_NUMBER'];
		$attachment    = $get_pr['DOCUMENT_UPLOAD'];
		$submitter     = $get_pr['SUBMITTER'];
		$currency_rate = ($get_pr['CURRENCY'] == "IDR") ? $get_pr['CURRENCY'] : $get_pr['CURRENCY'] ."/". number_format($get_pr['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("pr/approval/").encrypt_string($id_pr."-".$id_approval, true);

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient'] = $recipient['name'];

		if($approval_remark != ""){
			$data['approval_remark'] = $approval_remark;
		}

		$data['action_name'] = $action_name;
		$data['pr_number'] = $pr_number;

		$data['pr_link'] = base_url("purchase-requisition/").encrypt_string($id_pr, true);

		if($type == "request_approve"){
			$data['email_preview'] = "There's new purchase request $pr_number has been verified and waiting for your approval.";
			$descAdded  = "There's new purchase request $pr_number has been verified and waiting for your approval.
							<br>
							<br>
							Please go through the <a href='$approval_lnk'>link</a> and confirm your approval.";
			$title_first  = "Request Approval PR";				
		}else{
			$title_first  = ucfirst($type);				
			$data['email_preview']  = "Your PR request $pr_number has been <b>$type</b> by ".$action_name;
			$descAdded  = "Your PR request $pr_number has been <b>$type</b> by ".$action_name;
		}

		$data['email_body'] = $descAdded."
							<br>
							<br>
							The Purchase request details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>PR Name</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$pr_name</b></td>
										</tr>
										<tr>
											<td width='29%'>Submitter</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$submitter</b></td>
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

		$data['approval_link'] = base_url("pr/approval/").encrypt_string($id_pr."-".$id_approval, true);
		$data['approval_link_all'] = base_url("pr/approval");

		$file_view  = "pr_".$type;
		$title = "PR ".ucfirst($title_first);
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = $title . " - " . $pr_number . " - " . $pr_name;
		$body       = $this->load->view('email/'.$file_view, $data, TRUE);

		$attachment = ($attachment) ? FCPATH.'/uploads/pr_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }



    private function _email_to_hou_proc($recipient, $id_pr, $approval_remark=""){

		$get_pr      = $this->purchase->get_pr_for_email($id_pr);

		$currency      = ($get_pr['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_pr['PR_AMOUNT'],0,',','.');
		$pr_name       = $get_pr['PR_NAME'];
		$pr_number     = $get_pr['PR_NUMBER'];
		$attachment    = $get_pr['DOCUMENT_UPLOAD'];
		$submitter     = $get_pr['SUBMITTER'];
		$currency_rate = ($get_pr['CURRENCY'] == "IDR") ? $get_pr['CURRENCY'] : $get_pr['CURRENCY'] ."/". number_format($get_pr['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("pr/assign/").encrypt_string($id_pr, true);

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient'] = $recipient['name'];

		if($approval_remark != ""){
			$data['approval_remark'] = $approval_remark;
		}

		$data['action_name'] = $action_name;
		$data['pr_number'] = $pr_number;

		$data['pr_link'] = base_url("purchase-requisition/").encrypt_string($id_pr, true);

		$data['email_preview'] = "There's new Approved purchase request $pr_number waiting for your disposition to buyer.";
	
		$data['email_body'] = "There's new Approved purchase request $pr_number waiting for your disposition to buyer. 
							<br>
							<br>
							The Purchase request details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>PR Name</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$pr_name</b></td>
										</tr>
										<tr>
											<td width='29%'>Submitter</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$submitter</b></td>
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
							<br>
							Please go through the <a href='$approval_lnk'>link</a> and confirm your approval";

		$data['approval_link'] = $approval_lnk;
		$data['approval_link_all'] = base_url("pr/assign");

		$file_view  = "pr_request_approve";
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "Request Disposition of Approved Purchase Request" . " - " . $pr_number . " - " . $pr_name;
		$body       = $this->load->view('email/'.$file_view, $data, TRUE);

		$attachment = ($attachment) ? FCPATH.'/uploads/pr_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }


    private function _email_to_buyer($recipient, $id_pr, $remark){

		$get_pr      = $this->purchase->get_pr_for_email($id_pr);

		$currency      = ($get_pr['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_pr['PR_AMOUNT'],0,',','.');
		$pr_name       = $get_pr['PR_NAME'];
		$pr_number     = $get_pr['PR_NUMBER'];
		$attachment    = $get_pr['DOCUMENT_UPLOAD'];
		$submitter     = $get_pr['SUBMITTER'];
		$currency_rate = ($get_pr['CURRENCY'] == "IDR") ? $get_pr['CURRENCY'] : $get_pr['CURRENCY'] ."/". number_format($get_pr['CURRENCY_RATE'],0,'.',',');

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient'] = $recipient['name'];
	
		$data['action_name'] = $action_name;
		$data['pr_number'] = $pr_number;

		$po_link = base_url("purchase-order/create/").encrypt_string($id_pr, true);
		$data['po_link'] = $po_link;

		$data['email_preview']  = "There's new purchase request $pr_number has been assigned from $action_name to you.";

		$body = "There's new purchase request $pr_number has been assigned from $action_name to you.
								<br>
								<br>
							The Purchase request details are:
								<br>
								<table>
									<tbody>
										<tr>
											<td width='29%'>PR Name</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$pr_name</b></td>
										</tr>
										<tr>
											<td width='29%'>Submitter</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$submitter</b></td>
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
								<br>
								Please login to your Selia Account to submit PO by this PR
								";
		$body .= "<br><br>
					Remark from $action_name:<br>
					$remark";

		$data['email_body'] = $body;

		$file_view  = "po_request";
		$title = "New PO request";
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "New Disposition Purchase Request - " . $pr_number . " - " . $pr_name;
		$body       = $this->load->view('email/'.$file_view, $data, TRUE);

		$attachment = ($attachment) ? FCPATH.'/uploads/pr_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }


    private function _email_review_coa($recipient, $id_pr){

		$get_pr        = $this->purchase->get_pr_for_email_accounting($id_pr);
		$get_pr_header = $get_pr[0];
		$currency      = ($get_pr_header['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
		$pr_name       = $get_pr_header['PR_NAME'];
		$pr_number     = $get_pr_header['PR_NUMBER'];
		$attachment    = $get_pr_header['DOCUMENT_ATTACHMENT'];
		$submitter     = $get_pr_header['SUBMITTER'];
		$currency_rate = ($get_pr_header['CURRENCY'] == "IDR") ? $get_pr_header['CURRENCY'] : $get_pr_header['CURRENCY'] ."/". number_format($get_pr_header['CURRENCY_RATE'],0,'.',',');

		$addedBody = "";
		$addedFpjpType = "";
		$justification = $get_pr_header['FS_NUMBER'] ." - " . $get_pr_header['FS_NAME'];
		$rkap          = $get_pr_header['RKAP_DESCRIPTION'];
		$fs_link = base_url("feasibility-study/") . encrypt_string($get_pr_header['ID_FS'], true);
		$addedBody .= "<tr>
							<td width='29%'>Justification Name</td>
							<td width='1%'>:</td>
							<td width='70%'><b>$justification <a href=".$fs_link.">link</a></b></td>
						</tr>";
		$addedBody .= "<tr>
							<td>RKAP Name</td>
							<td>:</td>
							<td><b>$rkap</b></td>
						</tr>";
		$action_name = get_user_data($this->session->userdata('user_id'));

		$email_preview = "A new PR $pr_number has been <b>approved</b> by $action_name and need for your review of selected CoA in this PR";

		$data['email_preview'] = $email_preview;
		$data['action_name']   = $action_name;
		$data['submitter']     = $submitter;
		$data['pr_number']     = $pr_number;

		$email_body = " A new PR $pr_number has been <b>approved</b> by $action_name and need for your review of selected CoA in this PR.
		<br>
		<br>
		The PR details are:
								<br>
								<table>
									<tbody>
										$addedBody
										<tr>
											<td width='29%'>PR Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$pr_number</b></td>
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
														<th>Amount</th>
												</tr>";

		$no = 1;

		foreach ($get_pr as $key => $value) {

			$pr_desc      = $value['PR_DETAIL_DESC'];
			$nature       = $value['NATURE'] . " - " . $value['DESCRIPTION'];
			$total_amount = number_format($value['PR_DETAIL_AMOUNT'],0,'.',',');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$pr_desc."</td>";
				$email_body .= "<td>".$nature."</td>";
				$email_body .= "<td>".$total_amount."</td>";
			$email_body .= "</tr>";
			$no++;

		}

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$cc = "";
		/*if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}*/
		// $data['email_recipient'] = $recipient['name'];
		$subject    = "PR request CoA Review " .  $pr_number;

		foreach ($recipient as $key => $value) {
			$data['email_recipient']  = $value['name'];
			$encrypted                = encrypt_string($id_pr."-".$value['email'], true);
			$data['link_confirm_coa'] = base_url('coa-review/pr/').$encrypted;

			$to   = $value['email'];
			$body = $this->load->view('email/review_coa_pr', $data, TRUE);
			$send = sendemail($to, $subject, $body, $cc);
		}

		return true;
    }

}

/* End of file ApprovalPR_ctl.php */
/* Location: ./application/controllers/pr_po/ApprovalPR_ctl.php */