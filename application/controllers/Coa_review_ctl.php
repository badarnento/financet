<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa_review_ctl extends CI_Controller {

	private $module_name = "coa_review",
			$module_url  = "coa-review",
			$user_active = "";

	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('fpjp_mdl', 'fpjp');
		$this->load->model('coa_review_mdl', 'coa');
		$this->user_active = get_user_data($this->session->userdata('user_id'));

	}

	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "coa-review");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->coa->check_is_approval($this->session->userdata('email'));

		if($check_is_approval){

			$get_pr_for_approval = $this->purchase->get_pr_for_approval($this->session->userdata('email'));

			$id_pr = array();

			if($get_pr_for_approval){
				foreach ($get_pr_for_approval as $value) {
					$id_pr[] = $value['PR_HEADER_ID'];
				}
			}

			$data['title']          = "All Coa Review";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/coa_review";
			
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


	public function load_fpjp_to_review(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');

		$get_all         = $this->coa->get_fpjp_to_review($status);
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
						'id_fpjp'            => encrypt_string($value['FPJP_HEADER_ID'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'fpjp_number'        => $value['FPJP_NUMBER'],
						'fpjp_name'          => $value['FPJP_NAME'],
						'fpjp_currency'      => $value['CURRENCY'],
						'fpjp_rate'          => $value['CURRENCY_RATE'],
						'status'             => $value['STATUS'],
						'status_description' => $status_description,
						'fpjp_date'          => dateFormat($value['FPJP_DATE'], 5, false),
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

	public function review_fpjp($id_fpjp){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "coa-review/fpjp/".$id_fpjp);
			redirect('login');
		}
		$decrypt = decrypt_string($id_fpjp, true);
		$id_fpjp   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));

		if($check_exist > 0){
			$pic_email     = $this->session->userdata('email');
			$get_fpjp_header = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
			$get_fpjp_lines  = $this->crud->read_by_param("FPJP_LINES", array("FPJP_HEADER_ID" => $id_fpjp));

			$data['title']          = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/review_fpjp";
			
			$data['id_fpjp']          = $id_fpjp;
			$data['id_fs']          = $get_fpjp_header['ID_FS'];
			$data['fs_link']        = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS'], true);
			$data['fpjp_number']      = $get_fpjp_header['FPJP_NUMBER'];
			$data['fpjp_name']        = $get_fpjp_header['FPJP_NAME'];
			$data['fpjp_date']        = dateFormat($get_fpjp_header['FPJP_DATE'], 4, false);
			$data['fpjp_amount']      = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
			$data['fpjp_currency']    = $get_fpjp_header['CURRENCY'];
			$data['fpjp_rate']        = number_format($get_fpjp_header['CURRENCY_RATE'],0,',','.');
			$data['fpjp_directorat']  = $get_fpjp_header['ID_DIR_CODE'];
			$data['fpjp_division']    = $get_fpjp_header['ID_DIVISION'];
			$data['fpjp_unit']        = $get_fpjp_header['ID_UNIT'];
			$data['fpjp_submitter']   = $get_fpjp_header['SUBMITTER'];
			$data['fpjp_jabatan_sub'] = $get_fpjp_header['JABATAN_SUBMITTER'];
			$data['fpjp_document']    = $get_fpjp_header['DOCUMENT_ATTACHMENT'];
			$data['fpjp_justif']      = $get_fpjp_header['JUSTIF_TYPE'];
			$data['fpjp_type']        = get_type($get_fpjp_header['ID_MASTER_FPJP']);
			$data['fpjp_status']      = ($get_fpjp_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_fpjp_header['STATUS']);
			$data['fpjp_status_desc'] = $get_fpjp_header['STATUS_DESCRIPTION'];
			$last_update              = ($get_fpjp_header['UPDATED_DATE']) ? $get_fpjp_header['UPDATED_DATE'] : $get_fpjp_header['CREATED_DATE'];
			$data['review_status']    = $get_fpjp_header['COA_REVIEW'];
			$data['review_date']      = ($get_fpjp_header['COA_REVIEW_DATE']) ? date("d F Y", strtotime($get_fpjp_header['COA_REVIEW_DATE'])) : '';
			$data['fpjp_last_update'] = dateFormat($last_update, "with_day", false);

			$data['fpjp_doc_upload']   = ($get_fpjp_header['DOCUMENT_UPLOAD']) ? base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$get_fpjp_header['DOCUMENT_UPLOAD'], true) : "-";

			$get_history = $this->fpjp->get_comment_history($id_fpjp);
			$fpjp_history[] = array("PIC_NAME" => $get_fpjp_header['SUBMITTER'], "STATUS" => "Submitted", "REMARK" => "", "ACTION_DATE" =>  dateFormat($get_fpjp_header['CREATED_DATE'], 'fintool', false));

			foreach ($get_history as $key => $value) {
				$fpjp_history[] = array("PIC_NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "REMARK" => $value['REMARK'], "ACTION_DATE" => dateFormat($value['UPDATED_DATE'], 'fintool', false));
			}

			if($get_fpjp_header['COA_REVIEW'] == "Y" && $get_fpjp_header['COA_REVIEW_DATE']){
				$fpjp_history[] = array("PIC_NAME" => $get_fpjp_header['PIC_COA'], "STATUS" => "Assigned", "REMARK" => "", "ACTION_DATE" => dateFormat($get_fpjp_header['COA_REVIEW_DATE'], 'fintool', false));
			}

			$fpjp_history_sort = array();
			foreach ($fpjp_history as $key => $row)
			{
			    $fpjp_history_sort[$key] = $row['ACTION_DATE'];
			}
			array_multisort($fpjp_history_sort, SORT_ASC, $fpjp_history);

			$data['fpjp_history'] = $fpjp_history;

			$data['vendor_name']         = ($get_fpjp_header['FPJP_VENDOR_NAME']) ? $get_fpjp_header['FPJP_VENDOR_NAME'] : $get_fpjp_lines['PEMILIK_REKENING'] ;
			$data['bank_name']           = ($get_fpjp_header['FPJP_BANK_NAME']) ? $get_fpjp_header['FPJP_BANK_NAME'] : $get_fpjp_lines['NAMA_BANK'] ;
			$data['bank_account_name']   = ($get_fpjp_header['FPJP_ACC_NAME']) ? $get_fpjp_header['FPJP_ACC_NAME'] : $get_fpjp_lines['PEMILIK_REKENING'] ;
			$data['bank_account_number'] = ($get_fpjp_header['FPJP_ACC_NUMBER']) ? $get_fpjp_header['FPJP_ACC_NUMBER'] : $get_fpjp_lines['NO_REKENING'] ;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All FPJP Request", "link" => base_url("coa-review"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'FPJP Not Exist');
			redirect('fpjp/approval');

		}

	}


	public function load_pr_to_review(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');

		$get_all         = $this->coa->get_pr_to_review($status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];

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
						'pr_date'            => dateFormat($value['PR_DATE'], 5, false),
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


	public function review_pr($id_pr){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "coa-review/pr/".$id_pr);
			redirect('login');
		}
		$decrypt = decrypt_string($id_pr, true);
		$id_pr   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

		if($check_exist > 0){
			$pic_email     = $this->session->userdata('email');
			$get_pr_header = $this->coa->get_pr_to_review_by_id($id_pr);

			$data['title']          = "PR ".$get_pr_header['PR_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/review_pr";

			$data['id_pr']          = $id_pr;
			$data['id_fs']          = $get_pr_header['ID_FS'];
			$data['fs_link']        = base_url("feasibility-study/") . encrypt_string($get_pr_header['ID_FS'], true);
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
			$data['review_status'] = $get_pr_header['COA_REVIEW'];
			$data['review_date'] = $get_pr_header['COA_REVIEW_DATE'];
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

			$pr_history = get_pr_history($id_pr, $get_pr_header);
			$data['pr_history'] = $pr_history;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All PR Request", "link" => base_url("coa-review"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('pr/approval');

		}

	}

	public function review_pr_edit($id_pr){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "coa-review/pr-edit/".$id_pr);
			redirect('login');
		}
		$decrypt = decrypt_string($id_pr, true);
		$id_pr   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

		if($check_exist > 0){
			$pic_email     = $this->session->userdata('email');
			$get_pr_header = $this->coa->get_pr_to_review_by_id($id_pr);

			$data['title']          = "PR ".$get_pr_header['PR_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/review_pr_edit";
			
			$data['id_pr']          = $id_pr;
			$data['id_fs']          = $get_pr_header['ID_FS'];
			$data['fs_link']        = base_url("feasibility-study/") . encrypt_string($get_pr_header['ID_FS'], true);
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
			$data['review_status'] = $get_pr_header['COA_REVIEW'];
			$data['review_date'] = $get_pr_header['COA_REVIEW_DATE'];
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



			$get_history = $this->purchase->get_comment_history($id_pr);
			$pr_history[] = array("PIC_NAME" => $get_pr_header['SUBMITTER'], "STATUS" => "Submitted", "REMARK" => "", "ACTION_DATE" =>  dateFormat($get_pr_header['CREATED_DATE'], 'fintool', false));

			foreach ($get_history as $key => $value) {
				$pr_history[] = array("PIC_NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "REMARK" => $value['REMARK'], "ACTION_DATE" => dateFormat($value['UPDATED_DATE'], 'fintool', false));
			}

			if($get_pr_header['STATUS_ASSIGN'] == "Y" && $get_pr_header['ASSIGN_DATE']){
				$pr_history[] = array("PIC_NAME" => $get_pr_header['PIC_ASSIGN'], "STATUS" => "Assigned", "REMARK" => "", "ACTION_DATE" => dateFormat($get_pr_header['ASSIGN_DATE'], 'fintool', false));
			}

			if($get_pr_header['COA_REVIEW'] == "Y" && $get_pr_header['COA_REVIEW_DATE']){
				$pr_history[] = array("PIC_NAME" => $get_pr_header['PIC_COA'], "STATUS" => "Reviewed", "REMARK" => "", "ACTION_DATE" => dateFormat($get_pr_header['COA_REVIEW_DATE'], 'fintool', false));
			}

			$pr_history_sort = array();
			foreach ($pr_history as $key => $row)
			{
			    $pr_history_sort[$key] = $row['ACTION_DATE'];
			}
			array_multisort($pr_history_sort, SORT_ASC, $pr_history);

			$data['pr_history'] = $pr_history;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All PR Request", "link" => base_url("coa-review"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('pr/approval');

		}

	}


	public function review_fpjp_edit($id_fpjp){

		$decrypt      = str_replace($this->config->item('encryption_key'), "", base64url_decode($id_fpjp));
		$fpjp_header_id = (int) $decrypt;

		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $fpjp_header_id));

		if($check_exist > 0){

			$get_fpjp_header = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $fpjp_header_id));
			$get_fpjp_boq    = $this->crud->read_by_param("FPJP_BOQ", array("FPJP_HEADER_ID" => $fpjp_header_id));
			$check_exist_boq = $this->crud->check_exist("FPJP_BOQ", array("FPJP_HEADER_ID" => $fpjp_header_id));

			$data['title']          = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = "coa_review/review_fpjp_edit";

			$group_name = get_user_group_data();
			$enableEdit = (in_array("FPJP Edit", $group_name)) ? true : false;

			$coa_edit  = false;
			if($get_fpjp_header['STATUS'] != "returned"){
				if(in_array("FPJP COA", $group_name) && $get_fpjp_header['STATUS'] == 'approved'){
					if($get_fpjp_header['COA_REVIEW'] == 'Y'){
						$this->session->set_flashdata('messages', 'CoA has been review in this FPJP');
						redirect("fpjp");
					}else{
						$coa_edit  = true;
					}
				}else{
					if($enableEdit == false){
						$this->session->set_flashdata('messages', 'This FPJP not allowed to edit');
						redirect("fpjp");
					}
				}
			}else{
				if(in_array("FPJP COA", $group_name)){
					$this->session->set_flashdata('messages', 'This FPJP has been returned, not allowed to edit');
					redirect('fpjp');
				}
			}
			
			$data['id_fpjp']           = $fpjp_header_id;
			$data['id_fpjp_boq']       = ($get_fpjp_boq) ? $get_fpjp_boq['FPJP_BOQ_ID'] : false;
			$data['id_fs']             = $get_fpjp_header['ID_FS'];
			$data['fs_link']           = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS'], true);
			$data['fpjp_number']       = $get_fpjp_header['FPJP_NUMBER'];
			$data['fpjp_name']         = $get_fpjp_header['FPJP_NAME'];
			$data['fpjp_type']         = $get_fpjp_header['ID_MASTER_FPJP'];
			$data['notes']             = $get_fpjp_header['NOTES_USER'];
			$data['fpjp_date']         = dateFormat($get_fpjp_header['FPJP_DATE'], 5, false);
			$data['fpjp_amount']       = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
			$data['fpjp_currency']     = $get_fpjp_header['CURRENCY'];
			$data['fpjp_rate']         = number_format($get_fpjp_header['CURRENCY_RATE'],0,',','.');
			$data['fpjp_directorat']   = $get_fpjp_header['ID_DIR_CODE'];
			$data['fpjp_division']     = $get_fpjp_header['ID_DIVISION'];
			$data['fpjp_unit']         = $get_fpjp_header['ID_UNIT'];
			$data['fpjp_submitter']    = $get_fpjp_header['SUBMITTER'];
			$data['fpjp_jabatan_sub']  = $get_fpjp_header['JABATAN_SUBMITTER'];
			$data['fpjp_attachment']   = $get_fpjp_header['DOCUMENT_UPLOAD'];
			$data['fpjp_no_invoice']   = ($get_fpjp_header['NO_INVOICE']) ? $get_fpjp_header['NO_INVOICE'] : "";
			$data['fpjp_invoice_date'] = ($get_fpjp_header['INVOICE_DATE']) ? dateFormat($get_fpjp_header['INVOICE_DATE'], 5, false) : "";
			$data['fpjp_status']       = ($get_fpjp_header['STATUS'] == "request_approve") ? "Waiting for approval" : ucfirst($get_fpjp_header['STATUS']);
			$data['fpjp_status_desc']  = $get_fpjp_header['STATUS_DESCRIPTION'];
			$data['fpjp_last_update']  = dateFormat($get_fpjp_header['UPDATED_DATE'], "with_day", false);
			$data['coa_edit']          = $coa_edit;
			$data['enable_edit']       = $enableEdit;
			$data['fpjp_boq']          = ($check_exist_boq > 0 ) ? true : false;

			$get_all_vendor_bank         = get_all_vendor_bank();
			$data['selected_vendor']     = ($get_fpjp_header['FPJP_VENDOR_NAME']) ? md5(strtolower($get_fpjp_header['FPJP_VENDOR_NAME'])) : "";
			$data['selected_bank_name']  = ($get_fpjp_header['FPJP_BANK_NAME']) ? $get_fpjp_header['FPJP_BANK_NAME'] : "";
			$data['selected_acc_name']   = ($get_fpjp_header['FPJP_ACC_NAME']) ? $get_fpjp_header['FPJP_ACC_NAME'] : "";
			$data['selected_acc_number'] = ($get_fpjp_header['FPJP_ACC_NUMBER']) ? $get_fpjp_header['FPJP_ACC_NUMBER'] : "";
			$data['all_vendor']          = $get_all_vendor_bank['vendor'];
			$data['all_bank']            = $get_all_vendor_bank['bank'];

			$get_approval = $this->fpjp->get_approval_by_fpjp($fpjp_header_id);

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


	public function load_data_details(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_header_id = $this->input->post('pr_header_id');
		
		$get_all = $this->coa->get_pr_detail($pr_header_id);

		$get_nature = $this->budget->get_nature_by_rkap_edit();
		$nature_arr = array();
		$nature_arr_code = array();
		if($get_nature['total'] > 0){
			foreach($get_nature['data'] as $nature)	{
				$nature_arr[] = array(
									"id_coa"      => $nature['ID_MASTER_COA'],
									"nature"      => $nature['NATURE'],
									"nature_desc" => $nature['NATURE']." - ".$nature['DESCRIPTION']
							);
				// $code_nat = encrypt_string($nature['NATURE'], true);
				$code_nat = $nature['NATURE'];
				$nature_arr_code[$code_nat] = $nature['ID_MASTER_COA'];
			}
		}

		$get_category = $this->budget->get_category_item();
		$category_arr = array();
		if($get_category['total'] > 0){
			foreach($get_category['data'] as $cat)	{
				$category_arr[] = array(
									"category_item" => $cat['CATEGORY_NAME'],
									"nature"        => $cat['CATEGORY_COA']
							);
			}
		}

		$capex_arr[] = "Capex";
		$capex_arr[] = "Opex";

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$id_master_coa = $value['ID_MASTER_COA'];
				$optNature = "";
				for ($i=0; $i < count($nature_arr) ; $i++) {

					$data_nature = $nature_arr[$i];
					$id_coa      = $data_nature['id_coa'];
					$nature_desc = $data_nature['nature_desc'];

					$selectedNature = ($id_coa == $id_master_coa) ? ' selected' : '';
					$optNature      .= '<option value="'.$id_coa.'"'.$selectedNature.'>'.$nature_desc.'</option>';
				}

				$nature_opt = '<div class="form-group m-b-0"><select id="nature_opt-'.$number.'" class="form-control input-sm nature_opt select2">'.$optNature.'</select></div>';

				$category_item = $value['CATEGORY_ITEM'];
				$optCategory = "";
				
				for ($i=0; $i < count($category_arr) ; $i++) {

					$data_category = $category_arr[$i];
					$category      = $data_category['category_item'];
					$nature        = $data_category['nature'];
					$coa_selected = (array_key_exists($nature, $nature_arr_code)) ? $nature_arr_code[$nature] : "";
					$selectedCat = ($category == $category_item) ? ' selected' : '';
					$optCategory      .= '<option value="'.$category.'" data-nature="'.$coa_selected.'"'.$selectedCat.'>'.$category.'</option>';
				}
				$category_item_opt = '<div class="form-group m-b-0"><select id="category_item_opt-'.$number.'" data-id="'.$number.'" class="form-control input-sm category_item_opt select2">'.$optCategory.'</select></div>';
				
				$optCapex = '<option value="0" data-name="">-- Choose --</option>';
				$capex_val = ($value['CAPEX']) ? $value['CAPEX'] : $value['CAPEX_OPEX'];
				for ($i=0; $i < count($capex_arr) ; $i++) {
					$data_capex = $capex_arr[$i];
					$selectedCapex   = ($data_capex == $capex_val) ? ' selected' : '';
					$optCapex   .= '<option value="'.$data_capex.'"'.$selectedCapex.'>'.$data_capex.'</option>';
				}
				$capex_opt = '<div class="form-group m-b-0"><select id="capex_opt-'.$number.'" data-id="'.$number.'" class="form-control input-sm capex_opt">'.$optCapex.'</select></div>';

				$row[] = array(
						'pr_lines_id'       => $value['PR_LINES_ID'],
						'pr_detail_code'    => encrypt_string($value['PR_DETAIL_ID'],true),
						'pr_detail_id'      => $value['PR_DETAIL_ID'],
						'pr_lines_number'   => $value['PR_LINES_NUMBER'],
						'no'                => $number,
						'item_desc'         => $value['PR_DETAIL_NAME'],
						'detail_desc'       => $value['PR_DETAIL_DESC'],
						'category_item'     => $value['CATEGORY_ITEM'],
						'nature'            => $value['NATURE']." - ".$value['DESCRIPTION'],
						'nature_opt'        => $nature_opt,
						'category_item_opt' => $category_item_opt,
						'quantity'          => $value['QUANTITY'],
						'uom'               => $value['UOM'],
						'capex'             => $capex_val,
						'capex_opex'        => $capex_opt,
						'price'             => number_format($value['PRICE'],0,',','.'),
						'nominal'           => number_format($value['PR_DETAIL_AMOUNT'],0,',','.')
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


	public function load_data_details_fpjp(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$fpjp_header_id = $this->input->post('fpjp_header_id');
		$justif_type    = $this->input->post('justif_type');
		
		$get_all = $this->coa->get_fpjp_detail($fpjp_header_id);

		$get_nature = $this->budget->get_nature_by_rkap_edit();
		$nature_arr = array();
		$nature_arr_code = array();
		if($get_nature['total'] > 0){
			foreach($get_nature['data'] as $nature)	{
				$nature_arr[] = array(
									"id_coa"      => $nature['ID_MASTER_COA'],
									"nature"      => $nature['NATURE'],
									"nature_desc" => $nature['NATURE']." - ".$nature['DESCRIPTION']
							);
			}
		}

		$get_segment_product = get_segment("SEGMENT6");
		$segment_product_arr = array();
		$product_flex = array();
		foreach ($get_segment_product as $key => $value) {
			if($value['FLEX_VALUE'] != "0000"){
				$flex = $value['FLEX_VALUE'];
				$segment_product_arr[] = array( "FLEX" => $flex, "DESCRIPTION" => $value['VALUE_DESCRIPTION']);
				$product_flex[$flex] = $flex . " - " . $value['VALUE_DESCRIPTION'];
			}
		}
		$get_segment_tribe = get_segment("SEGMENT7");
		$segment_tribe_arr = array();
		$tribe_flex = array();
		foreach ($get_segment_tribe as $key => $value) {
			$flex = $value['FLEX_VALUE'];
			$segment_tribe_arr[] = array( "FLEX" => $flex, "DESCRIPTION" => $value['VALUE_DESCRIPTION']);
			$tribe_flex[$flex] = $flex . " - " . $value['VALUE_DESCRIPTION'];
		}

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$id_master_coa = $value['ID_MASTER_COA'];
				$optNature = "";
				for ($i=0; $i < count($nature_arr) ; $i++) {

					$data_nature = $nature_arr[$i];
					$id_coa      = $data_nature['id_coa'];
					$nature_desc = $data_nature['nature_desc'];

					$selectedNature = ($id_coa == $id_master_coa) ? ' selected' : '';
					$optNature      .= '<option value="'.$id_coa.'"'.$selectedNature.'>'.$nature_desc.'</option>';
				}

				$optTribe = "";
				for ($i=0; $i < count($segment_tribe_arr) ; $i++) {

					$data_tribe    = $segment_tribe_arr[$i];
					$flex_tribe    = $data_tribe['FLEX'];
					$desc_tribe    = $flex_tribe . " - " .$data_tribe['DESCRIPTION'];
					$selectedTribe = "";

					if($justif_type == "non_justif"){
						$selectedTribe = ($flex_tribe == "00008") ? ' selected' : '';
					}else{
						$tribe_rkap = ($value['TRIBE_RKAP'] != "") ? explode(" - ", $value['TRIBE_RKAP']) : false;
						if($tribe_rkap){
							$selectedTribe = ($flex_tribe == $tribe_rkap[0]) ? ' selected' : '';
						}
					}

					$optTribe      .= '<option value="'.$flex_tribe.'"'.$selectedTribe.'>'.$desc_tribe.'</option>';
				}

				$optProduct = "";
				for ($i=0; $i < count($segment_product_arr) ; $i++) {

					$data_product    = $segment_product_arr[$i];
					$flex_product    = $data_product['FLEX'];
					$desc_product    = $flex_product . " - " .$data_product['DESCRIPTION'];
					// $selectedTribe = ($flex_product == "00008") ? ' selected' : '';
					$optProduct      .= '<option value="'.$flex_product.'">'.$desc_product.'</option>';
				}

				$nature_opt = '<div class="form-group m-b-0"><select id="nature_opt-'.$number.'" class="form-control input-sm nature_opt select2">'.$optNature.'</select></div>';
				$product_opt = '<div class="form-group m-b-0"><select id="product_opt-'.$number.'" class="form-control input-sm product_opt select2">'.$optProduct.'</select></div>';
				$tribe_opt = '<div class="form-group m-b-0"><select id="tribe_opt-'.$number.'" class="form-control input-sm tribe_opt select2">'.$optTribe.'</select></div>';

				$tribe_rkap = ($value['TRIBE_RKAP'] != "") ? $value['TRIBE_RKAP'] : "00000 - Default";
				$tribe_val = $tribe_rkap;

				if($value['SEGMENT_TRIBE']){
					$tribe_val =  ( array_key_exists($value['SEGMENT_TRIBE'], $tribe_flex) ) ? $tribe_flex[$value['SEGMENT_TRIBE']] : $tribe_val;
				}

				$product_val = "";

				if($value['SEGMENT_PRODUCT']){
					$product_val =  ( array_key_exists($value['SEGMENT_PRODUCT'], $product_flex) ) ? $product_flex[$value['SEGMENT_PRODUCT']] : $product_val;
				}

				$tax = ($value['TAX'] > 0) ? "PPN ".$value['TAX']."%" : "-";

				$row[] = array(
						'fpjp_lines_id'        => $value['FPJP_LINES_ID'],
						'fpjp_detail_code'     => encrypt_string($value['FPJP_DETAIL_ID'],true),
						'fpjp_detail_id'       => $value['FPJP_DETAIL_ID'],
						'no'                   => $number,
						'detail_desc'          => $value['FPJP_DETAIL_DESC'],
						'nature'               => $value['NATURE']." - ".$value['DESCRIPTION'],
						'nature_opt'           => $nature_opt,
						'tribe'                => $tribe_val,
						'tribe_opt'            => $tribe_opt,
						'product'              => $product_val,
						'product_opt'          => $product_opt,
						'tax_view'             => $tax,
						'quantity'             => $value['QUANTITY'],
						'price'                => number_format($value['PRICE'],0,',','.'),
						'nominal'              => number_format($value['FPJP_DETAIL_AMOUNT'],0,',','.')
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

	public function update_coa(){

		$id_pr      = $this->input->post('id_pr');
		$data_lines = $this->input->post('data_lines');

		$data_update = array();

		if($data_lines):
			foreach ($data_lines as $key => $value) {
				$data_update[] = array(
										"PR_DETAIL_ID"  => decrypt_string($value['pr_detail_code'], true),
										// "CATEGORY_ITEM" => $value['category_item'],
										"CAPEX"         => $value['capex'],
										"ID_MASTER_COA" => $value['nature']
									);
			}
		endif;

		$messages     = "";

		$data = array(
						"PIC_COA"         => $this->user_active,
						"COA_REVIEW"      => "Y",
						"COA_REVIEW_DATE" => date("Y-m-d H:i:s", time())
					);

		$update   = $this->crud->update("PR_HEADER", $data, array("PR_HEADER_ID" => $id_pr));

		if(count($data_update) > 0):
			$this->crud->update_batch_data("PR_DETAIL", $data_update, "PR_DETAIL_ID");
		endif;

		if($update){
			$log_info = 'COA Confirmed ' . $id_pr . ' by ' . $this->user_active;
			log_message('info', $log_info);
			$status = true;
			$messages = "COA Confirmed";
		}
		
		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
	}

	public function update_coa_fpjp(){

		$id_fpjp    = $this->input->post('id_fpjp');
		$data_lines = $this->input->post('data_lines');

		$data_dtl_update = array();

		if($data_lines):
			foreach ($data_lines as $key => $value) {
				$data_update                    = array();
				$data_update["FPJP_DETAIL_ID"]  = decrypt_string($value['fpjp_detail_code'], true);
				$data_update["ID_MASTER_COA"]   = $value['nature'];
				$data_update["SEGMENT_PRODUCT"] = ($value['product'] != 0) ? $value['product'] : NULL;
				$data_update["SEGMENT_TRIBE"]   = ($value['tribe'] != 0) ? $value['tribe'] : NULL;

				$data_dtl_update[] = $data_update;
			}
		endif;

		$messages = "";

		$data = array(
						"PIC_COA"         => $this->user_active,
						"COA_REVIEW"      => "Y",
						"COA_REVIEW_DATE" => date("Y-m-d H:i:s", time())
					);

		$update   = $this->crud->update("FPJP_HEADER", $data, array("FPJP_HEADER_ID" => $id_fpjp));

		if(count($data_dtl_update) > 0):
			$this->crud->update_batch_data("FPJP_DETAIL", $data_dtl_update, "FPJP_DETAIL_ID");
		endif;

		if($update){
			$log_info = 'COA Confirmed ' . $id_fpjp . ' by ' . $this->user_active;
			log_message('info', $log_info);
			$status = true;
			$messages = "COA Confirmed";
		}
		
		$result['status']   = $status;
		$result['messages'] = $messages;

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

/* End of file Coa_review_ctl.php */
/* Location: ./application/controllers/Coa_review_ctl.php */