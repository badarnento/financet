<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fpjp_ctl extends CI_Controller {

	protected $authorization,
			  $http_response;

	private $module_name = "fpjp",
			$module_title       = "FPJP",
			$module_url         = "fpjp",
			$user_active        = "";

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->user_active = get_user_data($this->session->userdata('user_id'));

		$this->load->model('fpjp_mdl', 'fpjp');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');

		$this->authorization = false;
		
	}

	public function index()
	{

		$data['title']         = "FPJP";
		$data['module']        = "datatable";
		$data['template_page'] = "fpjp_new/fpjp_inquiry";
		$data['fpjp_status']   = get_status_fpjp();

		$group_name = get_user_group_data();

		$show_createbtn = (in_array("FPJP Inquiry", $group_name)) ? false : true;
		$enableEdit     = (in_array("FPJP Edit", $group_name)) ? true : false;
		$enableDelete   = (in_array("FPJP Delete", $group_name)) ? true : false;

		$data['show_create']   = $show_createbtn;
		$data['enable_edit']   = $enableEdit;
		$data['enable_delete'] = $enableDelete;

		$data['group_name']    = $group_name;
		
	    $directorat = check_is_bod();
	    $binding    = check_binding();

	    if(count($directorat) > 1 && $binding['binding'] != false){
			$directorat = $binding['data_binding']['directorat'];
	    }

		$data['su_budget']    = (in_array("SU Budget", $group_name)) ? true : false;
		$data['force_create_form'] = (in_array("FORCE CREATE FORM", $group_name)) ? true : false;
		$data['directorat']   = $directorat;
		$data['binding']      = $binding['binding'];
		$data['data_binding'] = $binding['data_binding'];

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "FPJP", "link" => "", "class" => "active" );

		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}


	public function load_fpjp_header(){

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

		if($this->input->post('fpjp_date')){
			$exp_fpjp_date = explode(" - ", $this->input->post('fpjp_date'));

			$date_from = date_db($exp_fpjp_date[0]);
			$date_to   = date_db($exp_fpjp_date[1]);

		}

		$get_all = $this->fpjp->get_fpjp_header($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status = ($value['STATUS'] == "request_approve") ? "Waiting for approval" : ucfirst($value['STATUS']);

				$row[] = array(
						'no'             => $number,
						'directorat'     => get_directorat($value['ID_DIR_CODE']),
						'fpjp_header_id' => base64url_encode($value['FPJP_HEADER_ID'].$this->config->item('encryption_key')),
						'id_fpjp_enc'    => encrypt_string($value['FPJP_HEADER_ID'], true),
						'id'             => $value['FPJP_HEADER_ID'],
						'fpjp_number'    => $value['FPJP_NUMBER'],
						'fpjp_name'      => $value['FPJP_NAME'],
						'status'         => $status,
						'status_coa'     => $value['COA_REVIEW'],
						'fpjp_date'      => dateFormat($value['FPJP_DATE'], 5, false),
						'currency'       => strtoupper($value['CURRENCY']),
						'total_amount'   => number_format($value['FPJP_AMOUNT'],0,',','.')
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

	public function view_fpjp($id_fpjp_enc){

		$decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($id_fpjp_enc));
		$id_fpjp   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));

		if($check_exist > 0){

			$get_fpjp_header = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $id_fpjp));
			$get_fpjp_boq    = $this->crud->read_by_param("FPJP_BOQ", array("FPJP_HEADER_ID" => $id_fpjp));
			$get_fpjp_lines  = $this->crud->read_by_param("FPJP_LINES", array("FPJP_HEADER_ID" => $id_fpjp));

			$data['title']            = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']           = "datatable";
			$data['template_page']    = "fpjp_new/fpjp_view";
			
			$data['id_fpjp']          = $id_fpjp;
			$data['id_fpjp_enc']      = $id_fpjp_enc;
			$data['id_fs']            = $get_fpjp_header['ID_FS'];
			$data['id_fs_2']          = ($get_fpjp_header['ID_FS_ADD_1']) ? $get_fpjp_header['ID_FS_ADD_1'] : "-";
			$data['id_fs_3']          = ($get_fpjp_header['ID_FS_ADD_2']) ? $get_fpjp_header['ID_FS_ADD_2'] : "-";
			$data['fs_link']          = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS'], true);
			$data['fs_link_2']          = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS_ADD_1'], true);
			$data['fs_link_3']          = base_url("feasibility-study/") . encrypt_string($get_fpjp_header['ID_FS_ADD_2'], true);
			$data['fpjp_number']      = $get_fpjp_header['FPJP_NUMBER'];
			$data['fpjp_type']        = $get_fpjp_header['ID_MASTER_FPJP'];
			$data['fpjp_name']        = $get_fpjp_header['FPJP_NAME'];
			$data['fpjp_boq']         = ($get_fpjp_boq) ? $get_fpjp_boq['FPJP_HEADER_ID'] : FALSE;
			$data['fpjp_date']        = dateFormat($get_fpjp_header['FPJP_DATE'], 5, false);
			$data['fpjp_amount']      = number_format($get_fpjp_header['FPJP_AMOUNT'],0,',','.');
			$data['fpjp_currency']    = $get_fpjp_header['CURRENCY'];
			$data['fpjp_rate']        = number_format($get_fpjp_header['CURRENCY_RATE'],0,',','.');
			$data['fpjp_directorat']  = $get_fpjp_header['ID_DIR_CODE'];
			$data['fpjp_division']    = $get_fpjp_header['ID_DIVISION'];
			$data['fpjp_unit']        = $get_fpjp_header['ID_UNIT'];
			$data['fpjp_submitter']   = $get_fpjp_header['SUBMITTER'];
			$data['fpjp_jabatan_sub'] = $get_fpjp_header['JABATAN_SUBMITTER'];
			$data['fpjp_attachment']  = $get_fpjp_header['DOCUMENT_ATTACHMENT'];
			$data['fpjp_status']      = ($get_fpjp_header['STATUS'] == "request_approve") ? "Waiting for approval" : ucfirst($get_fpjp_header['STATUS']);
			$data['fpjp_justif_type'] = ($get_fpjp_header['JUSTIF_TYPE'] == "non_justif") ? "Non Justification" : "Justification";
			$data['fpjp_status_desc'] = $get_fpjp_header['STATUS_DESCRIPTION'];
			$last_update              = ($get_fpjp_header['UPDATED_DATE']) ? $get_fpjp_header['UPDATED_DATE'] : $get_fpjp_header['CREATED_DATE'];
			$data['fpjp_last_update'] = dateFormat($last_update, "with_day", false);
			$attachment               = false;
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
			
			$data['fpjp_no_invoice']   = ($get_fpjp_header['NO_INVOICE']) ? $get_fpjp_header['NO_INVOICE'] : "-";
			$data['notes']   		   = ($get_fpjp_header['NOTES_USER']) ? $get_fpjp_header['NOTES_USER'] : "-";
			$data['fpjp_invoice_date'] = ($get_fpjp_header['INVOICE_DATE']) ? dateFormat($get_fpjp_header['INVOICE_DATE'], 5, false) : "-";
			$data['fpjp_doc_list']     = ($get_fpjp_header['DOCUMENT_CHECKLIST']) ? json_decode($get_fpjp_header['DOCUMENT_CHECKLIST']) : "-";
			$data['fpjp_doc_upload']   = ($get_fpjp_header['DOCUMENT_UPLOAD']) ? base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$get_fpjp_header['DOCUMENT_UPLOAD'], true) : "-";

			$data['fs_attachment']       = $attachment;
			$data['vendor_name']         = ($get_fpjp_header['FPJP_VENDOR_NAME']) ? $get_fpjp_header['FPJP_VENDOR_NAME'] : $get_fpjp_lines['PEMILIK_REKENING'] ;
			$data['bank_name']           = ($get_fpjp_header['FPJP_BANK_NAME']) ? $get_fpjp_header['FPJP_BANK_NAME'] : $get_fpjp_lines['NAMA_BANK'] ;
			$data['bank_account_name']   = ($get_fpjp_header['FPJP_ACC_NAME']) ? $get_fpjp_header['FPJP_ACC_NAME'] : $get_fpjp_lines['PEMILIK_REKENING'] ;
			$data['bank_account_number'] = ($get_fpjp_header['FPJP_ACC_NUMBER']) ? $get_fpjp_header['FPJP_ACC_NUMBER'] : $get_fpjp_lines['NO_REKENING'] ;

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
			redirect($this->module_url);

		}
	}

	public function create_fpjp()
	{

		$group_name = get_user_group_data();
		if(!in_array("FORCE CREATE FORM", $group_name)){
			$this->session->set_flashdata('messages', 'Submission FPJP Telah Ditutup');
			redirect($this->module_url);
			exit;
		}
		$data['title']         = "Create New FPJP";
		$data['module']        = "datatable";

		$data['template_page'] = ($this->session->userdata('fpjp_custom') == 1) ? "fpjp_new/fpjp_create_add_justif": "fpjp_new/fpjp_create";
		$data['type']          = get_all_type();

		$directorat = check_is_bod();
		$binding    = check_binding();

	    if(count($directorat) > 1 && $binding['binding'] != false){
			$directorat = $binding['data_binding']['directorat'];
	    }

	    $fpjp_type = array();

		$fpjp_tax = (in_array("FPJP TAX", $group_name)) ? true : false;

	    foreach($data['type'] as $value):
    		$group = (strtolower($value['CATEGORY']) == "justif") ?  'Justifikasi' : 'Non Justifikasi';
    		$fpjp_type[$group][] = array("ID_MASTER_FPJP" => $value['ID_MASTER_FPJP'], "FPJP_NAME" => $value['FPJP_NAME'], "CATEGORY" => $value['CATEGORY']);
		endforeach;

		$get_all_vendor_bank = get_all_vendor_bank();

		$data['fpjp_tax']     = $fpjp_tax;
		$data['fpjp_type']    = $fpjp_type;
		$data['all_vendor']   = $get_all_vendor_bank['vendor'];
		$data['all_bank']     = $get_all_vendor_bank['bank'];
		$data['directorat']   = $directorat;
		$data['binding']      = $binding['binding'];
		$data['data_binding'] = $binding['data_binding'];

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "FPJP", "link" => base_url('fpjp'), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}

	public function edit_fpjp($fpjp_header_id){

		$decrypt      = str_replace($this->config->item('encryption_key'), "", base64url_decode($fpjp_header_id));
		$fpjp_header_id = (int) $decrypt;

		$check_exist = $this->crud->check_exist("FPJP_HEADER", array("FPJP_HEADER_ID" => $fpjp_header_id));

		if($check_exist > 0){

			$get_fpjp_header = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $fpjp_header_id));
			$get_fpjp_boq    = $this->crud->read_by_param("FPJP_BOQ", array("FPJP_HEADER_ID" => $fpjp_header_id));
			$check_exist_boq = $this->crud->check_exist("FPJP_BOQ", array("FPJP_HEADER_ID" => $fpjp_header_id));

			$data['title']          = "FPJP ".$get_fpjp_header['FPJP_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = "fpjp_new/fpjp_edit";

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

	public function load_data_fs(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_fs = $this->input->post('id_fs');

		$banks = get_all_bank();
		$optBank = '<option value="0">-- Select Bank --</option>';
		foreach ($banks as $key => $bank) {
			$optBank .= '<option value="'.$bank['BANK_NAME'].'">'.$bank['BANK_NAME'].'</option>';
		}

		if($id_fs == "non_justif"){

			$number = 1;
			
			$line_name        = '<div class="form-group m-b-0"><input id="line_name-'. $number .'" data-id="'. $number .'" class="form-control input-sm line_name" autocomplete="off"></div>';
			$nominal          = '<div class="form-group m-b-0"><input id="nominal-'. $number .'" data-id="'. $number .'" class="form-control input-sm nominal text-right" autocomplete="off" value="0" readonly></div>';
			$original_amount  = '<div class="form-group m-b-0"><input id="original_amount-'. $number .'" data-id="'. $number .'" class="form-control input-sm original_amount text-right" autocomplete="off" value="0" ></div>';
			
			$pemilik_rekening = '<div class="form-group m-b-0"><input id="pemilik_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm pemilik_rekening" value=""></div>';
			$no_rekening      = '<div class="form-group m-b-0"><input id="no_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm no_rekening" value=""></div>';
			$nama_bank        = '<div class="form-group m-b-0"><select id="nama_bank-'.$number.'" class="form-control input-sm nama_bank select2">'.$optBank.'</select></div>';

			$row[] = array(
						'fpjp_line_key'    => "k".strtolower(generateRandomString(5)),
						'id_rkap'          => 0,
						'no'               => $number,
						'rkap_name'        => '',
						'fs_name'          => '',
						'line_name'        => $line_name,
						'fund_av'          => '',
						'nominal'          => $nominal,
						'original_amount'  => $original_amount,
						'pemilik_rekening' => $pemilik_rekening,
						'no_rekening'      => $no_rekening,
						'nama_bank'        => $nama_bank
						);

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = 1;
			$result['recordsFiltered'] = 1;
		}else{

			$get_all = $this->feasibility_study->get_fs_lines($id_fs, "fpjp");
			$data    = $get_all['data'];
			$total   = $get_all['total_data'];
			$start   = $this->input->post('start');
			$number  = $start+1;

			if($total > 0){

				foreach($data as $value) {

					$fs_lines_amount = number_format($value['FA_FS'],0,',','.');
					$fs_lines_name   = $value['FS_LINES_NAME'];
					$tribe_name      = $value['TRIBE_USECASE'];
					$rkap_name       = $value['RKAP_DESCRIPTION'];
					$tier_name       = $value['ENTRY_OPTIMIZE'];
					
					$tribe            = '<div class="form-group m-b-0"><input id="tribe-'. $number .'" data-id="'. $number .'" class="form-control input-sm tribe" autocomplete="off" value="'.$tribe_name.'" readonly></div>';
					$rkap             = '<div class="form-group m-b-0"><input id="rkap-'. $number .'" data-id="'. $number .'" value="'.$rkap_name.'" class="form-control input-sm rkap" autocomplete="off" readonly></div>';
					$tier             = '<div class="form-group m-b-0"><input id="tier-'. $number .'" data-id="'. $number .'" value="'.$tier_name.' class="form-control input-sm tier" autocomplete="off" readonly></div>';
					$fs_name          = '<div class="form-group m-b-0"><input id="fs_name-'. $number .'" data-id="'. $number .'" class="form-control input-sm fs_name" autocomplete="off" value="'.$fs_lines_name.'" readonly></div>';
					$line_name        = '<div class="form-group m-b-0"><input id="line_name-'. $number .'" data-id="'. $number .'" class="form-control input-sm line_name" autocomplete="off"></div>';
					$fund_av          = '<div class="form-group m-b-0"><input id="fund_av-'. $number .'" data-id="'. $number .'" class="form-control input-sm fund_av text-right" autocomplete="off" value="'.$fs_lines_amount.'" readonly></div>';

					$fund_av_amount = $fs_lines_amount;
					$nominal          = '<div class="form-group m-b-0"><input id="nominal-'. $number .'" data-id="'. $number .'" class="form-control input-sm nominal text-right" autocomplete="off" value="0" readonly></div>';
					$original_amount  = '<div class="form-group m-b-0"><input id="original_amount-'. $number .'" data-id="'. $number .'" class="form-control input-sm original_amount text-right" autocomplete="off" value="0" ></div>';
					$pemilik_rekening = '<div class="form-group m-b-0"><input id="pemilik_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm pemilik_rekening" value=""></div>';
					$no_rekening      = '<div class="form-group m-b-0"><input id="no_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm no_rekening" value=""></div>';
					$nama_bank        = '<div class="form-group m-b-0"><select id="nama_bank-'.$number.'" class="form-control input-sm nama_bank select2">'.$optBank.'</select></div>';

					$row[] = array(
								'fpjp_line_key'    => "k".strtolower(generateRandomString(5)),
								'id_rkap'          => $value['ID_RKAP_LINE'],
								'no'               => $number,
								'tribe'            => $tribe_name,
								'rkap_name'        => $rkap_name,
								'tier'             => $tier_name,
								'fs_name'          => $fs_lines_name,
								'line_name'        => $line_name,
								'fund_av'          => $fund_av,
								'fund_av_amount'   => $fund_av_amount,
								'nominal'          => $nominal,
								'original_amount'  => $original_amount,
								'pemilik_rekening' => $pemilik_rekening,
								'no_rekening'      => $no_rekening,
								'nama_bank'        => $nama_bank
								);
					$number++;

				}

				$result['data']            = $row;
				$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
				$result['recordsTotal']    = $total;
				$result['recordsFiltered'] = $total;

			}
		}

		echo json_encode($result);

	}

	public function load_data_fs_custom(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_fs = $this->input->post('id_fs');

		/*$banks = get_all_bank();
		$optBank = '<option value="0">-- Select Bank --</option>';
		foreach ($banks as $key => $bank) {
			$optBank .= '<option value="'.$bank['BANK_NAME'].'">'.$bank['BANK_NAME'].'</option>';
		}*/

		if($id_fs == "non_justif"){

			$number = 1;
			
			$line_name        = '<div class="form-group m-b-0"><input id="line_name-'. $number .'" data-id="'. $number .'" class="form-control input-sm line_name" autocomplete="off"></div>';
			$nominal          = '<div class="form-group m-b-0"><input id="nominal-'. $number .'" data-id="'. $number .'" class="form-control input-sm nominal text-right" autocomplete="off" value="0" readonly></div>';
			$original_amount  = '<div class="form-group m-b-0"><input id="original_amount-'. $number .'" data-id="'. $number .'" class="form-control input-sm original_amount text-right" autocomplete="off" value="0" ></div>';
			
			// $pemilik_rekening = '<div class="form-group m-b-0"><input id="pemilik_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm pemilik_rekening" value=""></div>';
			// $no_rekening      = '<div class="form-group m-b-0"><input id="no_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm no_rekening" value=""></div>';
			// $nama_bank        = '<div class="form-group m-b-0"><select id="nama_bank-'.$number.'" class="form-control input-sm nama_bank select2">'.$optBank.'</select></div>';

			$row[] = array(
						'fpjp_line_key'    => "k".strtolower(generateRandomString(5)),
						'id_rkap'          => 0,
						'no'               => $number,
						'rkap_name'        => '',
						'fs_name'          => '',
						'line_name'        => $line_name,
						'fund_av'          => '',
						'nominal'          => $nominal,
						'original_amount'  => $original_amount/*,
						'pemilik_rekening' => $pemilik_rekening,
						'no_rekening'      => $no_rekening,
						'nama_bank'        => $nama_bank*/
						);

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = 1;
			$result['recordsFiltered'] = 1;
		}else{

			$get_all = $this->feasibility_study->get_fs_lines_new($id_fs, "fpjp");
			$data    = $get_all['data'];
			$total   = $get_all['total_data'];
			$start   = $this->input->post('start');
			$number  = $start+1;

			if($total > 0){

				foreach($data as $value) {
					$id_fs_arr[] = $value['ID_FS'];
					$id_rkap_arr[] = $value['ID_RKAP_LINE'];
				}
				$get_absortion_justif = get_absortion_justif($id_fs_arr, $id_rkap_arr);
				$list_key = array_column($get_absortion_justif, 'ENC_KEY');

				foreach($data as $value) {

					$enc_key = encrypt_string($value['ID_FS'].$value['ID_RKAP_LINE'].$value['NOMINAL_FS'], TRUE);

					$abs_key = array_search($enc_key, $list_key);
					if( in_array($enc_key, $list_key) ){

						$absort_data = $get_absortion_justif[$abs_key];

						$rkap_name  = $absort_data['RKAP_DESCRIPTION'] ."  &ndash; ".date("M-y", strtotime($absort_data['MONTH']));
						$fa_fs      = $absort_data['FA_FS'];
						$nominal_fs = $absort_data['NOMINAL_FS'];
						$abs_fpjp   = $absort_data['ABS_FPJP'];
						// $tier_name  = $value['ENTRY_OPTIMIZE'];

					}else{

						$rkap_name  = "-";
						// $tier_name  = "-";
						$fa_fs      = 0;
						$nominal_fs = 0;
						$abs_fpjp   = 0;
					}

					$fs_lines_amount = number_format($fa_fs,0,',','.');
					$fs_lines_name   = $value['FS_LINES_NAME'];
					// $tribe_name      = $value['TRIBE_USECASE'];
					// $rkap_name       = $value['RKAP_DESCRIPTION'];6
					// $tier_name       = $value['ENTRY_OPTIMIZE'];
					
					// $tribe            = '<div class="form-group m-b-0"><input id="tribe-'. $number .'" data-id="'. $number .'" class="form-control input-sm tribe" autocomplete="off" value="'.$tribe_name.'" readonly></div>';
					$rkap             = '<div class="form-group m-b-0"><input id="rkap-'. $number .'" data-id="'. $number .'" value="'.$rkap_name.'" class="form-control input-sm rkap" autocomplete="off" readonly></div>';
					// $tier             = '<div class="form-group m-b-0"><input id="tier-'. $number .'" data-id="'. $number .'" value="'.$tier_name.' class="form-control input-sm tier" autocomplete="off" readonly></div>';
					$fs_name          = '<div class="form-group m-b-0"><input id="fs_name-'. $number .'" data-id="'. $number .'" class="form-control input-sm fs_name" autocomplete="off" value="'.$fs_lines_name.'" readonly></div>';
					$line_name        = '<div class="form-group m-b-0"><input id="line_name-'. $number .'" data-id="'. $number .'" class="form-control input-sm line_name" autocomplete="off"></div>';
					$fund_av          = '<div class="form-group m-b-0"><input id="fund_av-'. $number .'" data-id="'. $number .'" class="form-control input-sm fund_av text-right" autocomplete="off" value="'.$fs_lines_amount.'" readonly></div>';

					$fund_av_amount = $fs_lines_amount;
					$nominal          = '<div class="form-group m-b-0"><input id="nominal-'. $number .'" data-id="'. $number .'" class="form-control input-sm nominal text-right" autocomplete="off" value="0" readonly></div>';
					$original_amount  = '<div class="form-group m-b-0"><input id="original_amount-'. $number .'" data-id="'. $number .'" class="form-control input-sm original_amount text-right" autocomplete="off" value="0" ></div>';

					$row[] = array(
								'fpjp_line_key'   => "k".strtolower(generateRandomString(5)),
								'id_rkap'         => $value['ID_RKAP_LINE'],
								'id_fs'           => $value['ID_FS'],
								'no'              => $number,
								// 'tribe'        => $tribe_name,
								'rkap_name'       => $rkap_name,
								// 'tier'         => $tier_name,
								'fs_name'         => $fs_lines_name,
								'line_name'       => $line_name,
								'fund_av'         => $fund_av,
								'fund_av_amount'  => $fund_av_amount,
								'nominal'         => $nominal,
								'original_amount' => $original_amount
								);
					$number++;

				$result['data']            = $row;
				$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
				$result['recordsTotal']    = $total;
				$result['recordsFiltered'] = $total;

				}
			}
		}

		echo json_encode($result);

	}


	public function load_data_lines(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$fpjp_header_id = $this->input->post('fpjp_header_id');
		
		$get_all = $this->fpjp->get_fpjp_lines($fpjp_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			/*$banks = get_all_bank();
			$bank_arr = array();
			foreach ($banks as $key => $bank) {
				$bank_arr[] = $bank['BANK_NAME'];
			}*/

			$fund_av_oke = 0;
			$fund_av_ieu = false;
			foreach($data as $value) {
				if($value['IS_SHOW'] == 0){
					$fund_av_ieu = true;
				}
				$fund_av_oke += $value['FA_FS'];
			}

			foreach($data as $value) {

				$fund_av = $value['FA_FS'];

				if($fund_av_ieu == true){
					$fund_av = $fund_av_oke;
				}

				$line_name_edit = '<div class="form-group m-b-0"><input id="line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm line_name" value="'.$value['FPJP_LINE_NAME'].'" ></div>';	
				$fund_av_edit   = '<div class="form-group m-b-0"><input id="fund_av-'.$number.'" data-id="'.$number.'" class="form-control input-sm fund_av text-right" value="'.number_format($fund_av+$value['FPJP_LINE_AMOUNT'],0,',','.').'" readonly></div>';
				$nominal_edit   = '<div class="form-group m-b-0"><input id="nominal-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal text-right" value="'.number_format($value['FPJP_LINE_AMOUNT'],0,',','.').'" readonly></div>';
				$original_amount_edit   = '<div class="form-group m-b-0"><input id="original_amount-'.$number.'" data-id="'.$number.'" class="form-control input-sm original_amount text-right" value="'.number_format($value['ORIGINAL_AMOUNT'],2,'.',',').'" ></div>';

				$pemilik_rekening = '<div class="form-group m-b-0"><input id="pemilik_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm pemilik_rekening" value="'.$value['PEMILIK_REKENING'].'"></div>';
				$no_rekening      = '<div class="form-group m-b-0"><input id="no_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm no_rekening" value="'.$value['NO_REKENING'].'"></div>';
				/*$optBank = "";
				for ($i=0; $i < count($bank_arr) ; $i++) { 
					$selectedBank = ($value['NAMA_BANK'] == $bank_arr[$i]) ? ' selected' : '';
					$optBank .= '<option value="'.$bank_arr[$i].'"'.$selectedBank.'>'.$bank_arr[$i].'</option>';
				}*/

				// $nama_bank    = '<div class="form-group m-b-0"><select id="nama_bank-'.$number.'" class="form-control input-sm nama_bank select2">'.$optBank.'</select></div>';

				if($value['IS_SHOW'] == 1){

					$row[] = array(
							'fpjp_line_key'         => "k".strtolower(generateRandomString(5)),
							'fpjp_lines_id'         => $value['FPJP_LINES_ID'],
							'id_rkap_line'          => $value['ID_RKAP_LINE'],
							'fpjp_header_id'        => base64url_encode($value['FPJP_HEADER_ID'].$this->config->item('encryption_key')),
							'no'                    => $value['FPJP_LINES_NUMBER'],
							'rkap_name'             => $value['RKAP_DESCRIPTION']." &ndash; ".date("M-y", strtotime($value['MONTH'])),
							'line_name'             => $value['FPJP_LINE_NAME'],
							'nama_bank'             => $value['NAMA_BANK'],
							'pemilik_rekening'      => $value['PEMILIK_REKENING'],
							'no_rekening'           => $value['NO_REKENING'],
							'fund_available'        => number_format($fund_av,0,',','.'),
							'nominal'               => number_format($value['FPJP_LINE_AMOUNT'],0,',','.'),
							'original_amount'       => number_format($value['ORIGINAL_AMOUNT'],2,'.',','),
							'nominal_edit'          => $nominal_edit,
							'original_amount_edit' 	=> $original_amount_edit,
							'fund_av_edit'          => $fund_av_edit,
							'line_name_edit'        => $line_name_edit
							);
					$number++;
				}

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);
	}


	public function load_data_details(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$fpjp_lines_id = $this->input->post('fpjp_lines_id');
		$category    = $this->input->post('category');
		
		$get_all = $this->fpjp->get_fpjp_details($fpjp_lines_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$number = $value['FPJP_DETAIL_NUMBER'];

				$po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc"></div>';
				
				$nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="0"></div>';

				$tax = ($value['TAX'] > 0) ? "PPN ".$value['TAX']."%" : "-";

				if($value['VATSA'] > 0){
					$tax = "VATSA 10%";
				}
				$pph_26_view = ($value['PPH'] == 20 || $value['PPH'] == 0) ? "<i class='text-default fa fa-times-circle fa-lg'></i>" : "<i class='text-success fa fa-check-circle fa-lg'></i>";
				$pph_26_view = ($value['FPJP_DETAIL_DESC'] == "PPh yang ditanggung") ? "-" : $pph_26_view;

				$row[] = array(
						'fpjp_lines_id'     => $value['FPJP_LINES_ID'],
						'fpjp_detail_id'    => $value['FPJP_DETAIL_ID'],
						'no'                => $number,
						'detail_desc'       => $value['FPJP_DETAIL_DESC'],
						'nature'            => $value['NATURE']." - ".$value['DESCRIPTION'],
						'quantity'          => $value['QUANTITY'],
						'tax_view'          => $tax,
						'cod_dgt'           => $pph_26_view,
						'tax'               => $value['TAX'],
						'price'             => number_format($value['PRICE'],0,',','.'),
						'nominal'           => number_format($value['FPJP_DETAIL_AMOUNT'],0,',','.'),
						'po_desc'           => $po_desc,
						'nominal_detail_po' => $nominal_detail_po
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

	public function load_data_details_boq_edit(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$fpjp_header_id = $this->input->post('fpjp_header_id');
		$category = $this->input->post('category');
		
		$get_all = $this->fpjp->get_fpjp_details_boq($fpjp_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$asset = $value['ASSET_TYPE'];

				if($asset == 'Asset')
				{
					$lov_asset = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option selected="selected" value="Asset">Asset</option>';
					$lov_asset .= '<option value="Non Asset">Non Asset</option>';
				}
				else
				{
					$lov_asset = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option value="Asset">Asset</option>';
					$lov_asset .= '<option selected="selected" value="Non Asset">Non Asset</option>';
				}

				$num = $value['FPJP_DETAIL_NUMBER'];


				$exp_rd_date = explode("-", $value['RECEIPT_DATE']);
				$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];

				$exp_id_date = explode("-", $value['INVOICE_DATE']);
				$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];

				$fpjp_item      = '<div class="form-group m-b-0"><input id="fpjp_item-'.$num.'" class="form-control input-sm fpjp_item" value="'.$value['FPJP_DETAIL_NAME'].'"></div>';
				$fpjp_desc      = '<div class="form-group m-b-0"><input id="fpjp_desc-'.$num.'" class="form-control input-sm fpjp_desc" value="'.$value['FPJP_DETAIL_DESC'].'"></div>';
				$qty_boq       = '<div class="form-group m-b-0"><input id="qty_boq-'.$num.'" data-id="'.$num.'" class="form-control input-sm qty_boq text-center" value="1" min="1" max="99999" type="number"  value="'.$value['QUANTITY'].'"></div>';
				$uom      = '<div class="form-group m-b-0"><input id="uom-'.$num.'" class="form-control input-sm uom" value="'.$value['UOM'].'"></div>';
				$unit_price          = '<div class="form-group m-b-0"><input id="unit_price-'.$num.'" data-id="'.$num.'" class="form-control input-sm unit_price text-right money-format" value="'.number_format($value['PRICE'],0,',','.').'"></div>';
				$total_price = '<div class="form-group m-b-0"><input id="total_price-'.$num.'" class="form-control input-sm total_price text-right" value="'.number_format($value['FPJP_DETAIL_AMOUNT'],0,',','.').'" readonly></div>';
				$serial_number = '<div class="form-group m-b-0"><input id="serial_number-'.$num.'" class="form-control input-sm serial_number text-right" value="'.$value['SERIAL_NUMBER'].'"></div>';
				$merek = '<div class="form-group m-b-0"><input id="merek-'.$num.'" class="form-control input-sm merek text-right" value="'.$value['MEREK'].'"></div>';
				$umur_manfaat = '<div class="form-group m-b-0"><input id="umur_manfaat-'.$num.'" class="form-control input-sm umur_manfaat text-right" value="'.$value['UMUR_MANFAAT'].'"></div>';
				$invoice_date = '<div class="form-group m-b-0"><input id="invoice_date-'.$num.'" class="form-control input-sm invoice_date date_period text-right" value="'.$id_date.'"></div>';
				$receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'.$num.'" class="form-control input-sm receipt_date date_period text-right" value="'.$rd_date.'"></div>';
				$asset_type = '<div class="form-group m-b-0"><select id="asset_type-'.$num.'" class="form-control input-sm asset_type select2 select-center">'.$lov_asset.'</select></div>';

				$row[] = array(
						'no'                => $number,
						'fpjp_item'       	=> $fpjp_item,
						'fpjp_boq_id'       => $value['FPJP_BOQ_ID'],
						'fpjp_desc'       	=> $fpjp_desc,
						'qty_boq'          	=> $qty_boq,
						'uom'          		=> $uom,
						'unit_price'        => $unit_price,
						'total_price'       => $total_price,
						'serial_number'     => $serial_number,
						'merek'       		=> $merek,
						'umur_manfaat'      => $umur_manfaat,
						'invoice_date'      => $invoice_date,
						'receipt_date'      => $receipt_date,
						'asset_type'        => $asset_type
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

	public function load_fpjp_boq_edit(){

		$fpjp_header_id = $this->input->post('fpjp_header_id');
		
		$get_all = $this->fpjp->get_fpjp_details_boq($fpjp_header_id);

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];

		$row = array();

		if($total > 0){

			foreach($data as $value) {

				$number = $value['FPJP_DETAIL_NUMBER'];

				$asset = $value['ASSET_TYPE'];

				if($asset == 'Asset')
				{
					$lov_asset = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option selected="selected" value="Asset">Asset</option>';
					$lov_asset .= '<option value="Non Asset">Non Asset</option>';
				}
				else
				{
					$lov_asset = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option value="Asset">Asset</option>';
					$lov_asset .= '<option selected="selected" value="Non Asset">Non Asset</option>';
				}


				$exp_rd_date = explode("-", $value['RECEIPT_DATE']);
				$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];

				$exp_id_date = explode("-", $value['INVOICE_DATE']);
				$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];

				$fpjp_item      = '<div class="form-group m-b-0"><input id="fpjp_item-'.$number.'" class="form-control input-sm fpjp_item" value="'.$value['FPJP_DETAIL_NAME'].'"></div>';
				$fpjp_desc      = '<div class="form-group m-b-0"><input id="fpjp_desc-'.$number.'" class="form-control input-sm fpjp_desc" value="'.$value['FPJP_DETAIL_DESC'].'"></div>';
				$qty_boq       = '<div class="form-group m-b-0"><input id="qty_boq-'.$number.'" data-id="'.$number.'" class="form-control input-sm qty_boq text-center" value="1" min="1" max="99999" type="number"  value="'.$value['QUANTITY'].'"></div>';
				$uom      = '<div class="form-group m-b-0"><input id="uom-'.$number.'" class="form-control input-sm uom" value="'.$value['UOM'].'"></div>';
				$unit_price          = '<div class="form-group m-b-0"><input id="unit_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm unit_price text-right money-format" value="'.number_format($value['PRICE'],0,',','.').'"></div>';
				$total_price = '<div class="form-group m-b-0"><input id="total_price-'.$number.'" class="form-control input-sm total_price text-right" value="'.number_format($value['FPJP_DETAIL_AMOUNT'],0,',','.').'" readonly></div>';
				$serial_number = '<div class="form-group m-b-0"><input id="serial_number-'.$number.'" class="form-control input-sm serial_number text-right" value="'.$value['SERIAL_NUMBER'].'"></div>';
				$merek = '<div class="form-group m-b-0"><input id="merek-'.$number.'" class="form-control input-sm merek text-right" value="'.$value['MEREK'].'"></div>';
				$umur_manfaat = '<div class="form-group m-b-0"><input id="umur_manfaat-'.$number.'" class="form-control input-sm umur_manfaat text-right" value="'.$value['UMUR_MANFAAT'].'"></div>';
				$invoice_date = '<div class="form-group m-b-0"><input id="invoice_date-'.$number.'" class="form-control input-sm invoice_date date_period text-right" value="'.$id_date.'"></div>';
				$receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'.$number.'" class="form-control input-sm receipt_date date_period text-right" value="'.$rd_date.'"></div>';
				$asset_type = '<div class="form-group m-b-0"><select id="asset_type-'.$number.'" class="form-control input-sm asset_type select2 select-center">'.$lov_asset.'</select></div>';

				$row[] = array(
						'no'                => $number,
						'fpjp_item'       	=> $fpjp_item,
						'fpjp_boq_id'       => $value['FPJP_BOQ_ID'],
						'fpjp_desc'       	=> $fpjp_desc,
						'qty_boq'          	=> $qty_boq,
						'uom'          		=> $uom,
						'unit_price'        => $unit_price,
						'total_price'       => $total_price,
						'serial_number'     => $serial_number,
						'merek'       		=> $merek,
						'umur_manfaat'      => $umur_manfaat,
						'invoice_date'      => $invoice_date,
						'receipt_date'      => $receipt_date,
						'asset_type'        => $asset_type
				);
			}

		}

		echo json_encode($row);

	}

	public function load_data_details_boq(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$fpjp_header_id = $this->input->post('fpjp_header_id');
		$category    = $this->input->post('category');
		
		$get_all = $this->fpjp->get_fpjp_details_boq($fpjp_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'                => $number,
						'fpjp_item'       	=> $value['FPJP_DETAIL_NAME'],
						'fpjp_desc'       	=> $value['FPJP_DETAIL_DESC'],
						'qty_boq'          	=> $value['QUANTITY'],
						'unit_price'        => number_format($value['PRICE'],0,',','.'),
						'total_price'       => number_format($value['FPJP_DETAIL_AMOUNT'],0,',','.'),
						'asset_type'       	=> $value['ASSET_TYPE'],
						'serial_number'     => $value['SERIAL_NUMBER'],
						'merek'     		=> $value['MEREK'],
						'umur_manfaat'     	=> $value['UMUR_MANFAAT'],
						'invoice_date'     	=> dateFormat($value['INVOICE_DATE'],5,false),
						'receipt_date'     	=> dateFormat($value['RECEIPT_DATE'],5,false),
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


	public function save_fpjp(){
		
		$directorat       = $this->input->post('directorat');
		$division         = $this->input->post('division');
		$unit             = $this->input->post('unit');
		$type             = $this->input->post('type');
		$type_name        = $this->input->post('type_name');
		$fpjp_name        = $this->input->post('fpjp_name');
		$fpjp_date        = $this->input->post('fpjp_date');
		$amount           = $this->input->post('amount');
		$currency         = $this->input->post('currency');
		$rate             = $this->input->post('rate');
		$submitter        = $this->input->post('submitter');
		$jabatan_sub      = $this->input->post('jabatan_sub');
		$data_line        = $this->input->post('data_line');
		$justif_type      = $this->input->post('justif_cat');
		$id_fs            = $this->input->post('id_fs');
		$no_invoice       = $this->input->post('no_invoice');
		$invoice_date     = $this->input->post('invoice_date');
		$notes            = $this->input->post('notes');
		$doc_list         = $this->input->post('doc_list');
		$attachment       = $this->input->post('attachment');
		$data_boq         = $this->input->post('data_boq');
		$vendor           = $this->input->post('vendor');
		$bank_name        = $this->input->post('bank_name');
		$pemilik_rekening = $this->input->post('pemilik_rekening');
		$no_rekening      = $this->input->post('no_rekening');
		$site_code        = $this->input->post('site_code');

		if($fpjp_date != ""){
			$exp_fpjp_date = explode("-", $fpjp_date);
			$fpjp_date = $exp_fpjp_date[2]."-".$exp_fpjp_date[1]."-".$exp_fpjp_date[0];
		}

		$get_dir        = $this->crud->read_by_param("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $directorat));
		$id_dir_code    = $get_dir['ID_DIR_CODE'];

		$check_fpjp_exist = $this->crud->check_exist("FPJP_HEADER", array("ID_DIR_CODE" => $id_dir_code));

		$month     = date("m");
		$year      = date("Y");
		$number    = sprintf("%'03d", 1);
		$fpjp_number = $get_dir['DIRECTORAT_CODE']."/".$number."/".date("m")."/".date("Y");

		if($check_fpjp_exist > 0){

			$last_fpjp_number = $this->fpjp->get_last_fpjp_number($id_dir_code);
			$exp_fpjp_number  = explode("/",$last_fpjp_number);
			if(substr($last_fpjp_number, 0, 4) == "FPJP"){
			    $dir_code = $exp_fpjp_number[1];
			    $number   = (int) $exp_fpjp_number[2];
			}else{
			    $dir_code = $exp_fpjp_number[0];
			    $number   = (int) $exp_fpjp_number[1];
			}
			$number += 1;
			$number = sprintf("%'03d", $number);
			$fpjp_number = "FPJP/".$dir_code."/".$number."/".$month."/".$year;

		}
		
		$amount        = str_replace(".", "", $amount);
		$total_amount = (int) $amount;
		$auto_reject   = get_auto_reject_date();

		$data = array(
						"ID_DIR_CODE"        => $id_dir_code,
						"ID_DIVISION"        => $division,
						"ID_UNIT"            => $unit,
						"ID_FS"              => $id_fs,
						"ID_MASTER_FPJP"     => $type,
						"FPJP_NUMBER"        => $fpjp_number,
						"FPJP_NAME"          => $fpjp_name,
						"CURRENCY"           => $currency,
						"CURRENCY_RATE"      => $rate,
						"SUBMITTER"          => $submitter,
						"NOTES_USER"         => $notes,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"AUTO_REJECT_DATE"   => $auto_reject,
						"FPJP_VENDOR_NAME"   => $vendor,
						"FPJP_BANK_NAME"     => $bank_name,
						"FPJP_ACC_NAME"      => $pemilik_rekening,
						"FPJP_ACC_NUMBER"    => $no_rekening,
						"FPJP_SITE_CODE"     => $site_code,
						"APPROVAL_LEVEL"     => 0,
						"COA_REVIEW"         => 'N',
						"INTERFACE_STATUS"   => 'NEW',
						"STATUS_DESCRIPTION" => "Submitted by ".$submitter,
						"FPJP_AMOUNT"        => $total_amount,
						"JUSTIF_TYPE"        => $justif_type
					);

		if($fpjp_date != ""){
			$data['FPJP_DATE'] = $fpjp_date;
		}
		if($no_invoice != ""){
			$data['NO_INVOICE'] = $no_invoice;
		}
		if($invoice_date != ""){
			$exp_invoice_date = explode("-", $invoice_date);
			$invoice_date = $exp_invoice_date[2]."-".$exp_invoice_date[1]."-".$exp_invoice_date[0];
			$data['INVOICE_DATE'] = $invoice_date;
		}
		if($doc_list != ""){
			$data['DOCUMENT_CHECKLIST'] = (is_array($doc_list)) ? json_encode($doc_list) : '';
		}
		if($attachment != ""){
			$data['DOCUMENT_UPLOAD'] = $attachment;
		}

		$insert     = $this->crud->create("FPJP_HEADER", $data);
		$send_email = false;

		$check_em = $this->session->userdata('email');
		$custom_input = false;

		if( strpos(strtolower($type_name), 'paid_invoice') !== false && strpos($vendor, 'SEPULSA') !== false){
			$custom_input = true;
	    }
		if($check_em == "evan_g_pasaribu@linkaja.id" || $check_em == "niga_siregar@linkaja.id" || $check_em == "i-ra_andy@linkaja.id" ){
			$custom_input = true;
		}
		
		if($custom_input){
			$sendemail = false;
			$dataUpdFs = array("APPROVAL_LEVEL" => 0, "STATUS" => "approved");

			$approver_accounting = get_approval_by_category('Accounting');
			if($approver_accounting):
				$recipient = array();
				foreach ($approver_accounting as $key => $value):
						$recipient[]  = array('name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
				endforeach;
				$this->_email_review_coa($recipient, $id_fpjp);
			endif;
		}else{
			$getLoA        = $this->_getLoAFPJP($insert, $id_dir_code, $division, $unit, $total_amount);
			$data_approval = $getLoA['data_approval'];

			$recipient['email'] = $getLoA['email_to'];
			$recipient['name']  = $getLoA['email_name'];
			$send_email = true;

			$dataUpdFs = array("APPROVAL_LEVEL" => count($data_approval), "STATUS_DESCRIPTION" => "Submitted by ".$submitter);

			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_FPJP", $data_approval);
			$id_approval     = $this->db->insert_id();
		}

		$this->crud->update("FPJP_HEADER", $dataUpdFs, array("FPJP_HEADER_ID" => $insert));

		$status   = false;
		$messages = "";

		if($insert > 0){

			$fpjp_line_number = 1;

			if($id_fs > 0){
				$this->budget->update_fs_status($id_fs, "fs used", "FPJP");
			}

			foreach ($data_line as $key => $value) {

				$detail_data = $value['detail_data'];
				$original_amount = str_replace(',','',$value['original_amount']);
				$data_lines = array(
										"FPJP_HEADER_ID"    => $insert,
										"FPJP_LINES_NUMBER" => $fpjp_line_number,
										"FPJP_LINE_NAME"    => $value['line_name'],
										"ID_RKAP_LINE"      => $value['id_rkap'],
										"ID_FS"             => $id_fs,
										"PEMILIK_REKENING"  => $pemilik_rekening,
										"NAMA_BANK"         => $bank_name,
										"NO_REKENING"       => $no_rekening,
										"FPJP_LINE_AMOUNT"  => $value['nominal'],
										"ORIGINAL_AMOUNT"  	=> $original_amount
									);


				$insert_line = $this->crud->create("FPJP_LINES", $data_lines);

				if($insert_line > 0){

					$fpjp_detail_number =1;
					$detaillAdded = "";

					foreach ($detail_data as $key => $value_dtl) {

						$data_details[] = array(
											"FPJP_HEADER_ID"     => $insert,
											"FPJP_LINES_ID"      => $insert_line,
											"FPJP_DETAIL_NUMBER" => $fpjp_detail_number,
											"FPJP_DETAIL_DESC"   => $value_dtl['rkap_desc'],
											"ID_MASTER_COA"      => $value_dtl['nature'],
											"QUANTITY"           => $value_dtl['quantity'],
											"TAX"                => $value_dtl['tax'],
											"VATSA"              => 0,
											"PPH"                => 0,
											"PRICE"              => $value_dtl['price'],
											"FPJP_DETAIL_AMOUNT" => $value_dtl['nominal'],
											"CREATED_BY"         => $this->session->userdata('identity')
										);

						$fpjp_detail_number++;
					}
				}

				$fpjp_line_number++;
			}

			if($detaillAdded){
				$data_details[] = $detaillAdded;
			}

			$insert_detail = $this->crud->create_batch("FPJP_DETAIL", $data_details);

			if($insert_detail){

				if($detaillAdded){
					$this->crud->update("FPJP_HEADER", array("UPDATED_DATE" => NULL, "FPJP_AMOUNT" => $update_nominal['nominal_header']), array("FPJP_HEADER_ID" => $insert));
					$this->crud->update("FPJP_LINES", array("UPDATED_DATE" => NULL, "FPJP_LINE_AMOUNT" => $update_nominal['nominal_line']), array("FPJP_LINES_ID" => $update_nominal['id_line']));
				}
				if($send_email){
					$this->_email_aprove($recipient, $insert, $id_approval);
				}
				$data_detail_boq = array();
				$fpjp_detail_number=1;

				if($data_boq){

				foreach ($data_boq as $key => $value) {

					$receipt_date = $value['receipt_date'];

					if($receipt_date != ""){
						$exp_rd_date = explode("-", $receipt_date);
						$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];
					}

					$invoice_date = $value['invoice_date'];

					if($invoice_date != ""){
						$exp_id_date = explode("-", $invoice_date);
						$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];
					}

				$data_detail_boq[] = array(
										"FPJP_HEADER_ID"    	=> $insert,
										"FPJP_DETAIL_NUMBER" 	=> $fpjp_detail_number,
										"FPJP_DETAIL_NAME"  	=> $value['fpjp_item'],
										"FPJP_DETAIL_DESC"  	=> $value['fpjp_desc'],
										"UOM"    				=> $value['uom'],
										"QUANTiTY"    			=> $value['qty_boq'],
										"PRICE"    				=> $value['unit_price'],
										"FPJP_DETAIL_AMOUNT"  	=> $value['total_price'],
										"UMUR_MANFAAT"  	    => $value['umur_manfaat'],
										"SERIAL_NUMBER"  	    => $value['serial_number'],
										"MEREK"  	    		=> $value['merek'],
										"ASSET_TYPE"  	    	=> $value['asset_type'],
										"INVOICE_DATE"  	    => $id_date,
										"RECEIPT_DATE"  	    => $rd_date
									);
					$fpjp_detail_number++;
					}
					if(count($data_detail_boq) > 0){
						$insert_boq = $this->crud->create_batch("FPJP_BOQ", $data_detail_boq);
					}
				}

				$status    = true;
			}else{
				$messages = "Failed to Create FPJP Detail";
			}

		}
		else{
			$messages = "Failed to Create FPJP";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }




	public function save_fpjp_custom(){
		
		$directorat       = $this->input->post('directorat');
		$division         = $this->input->post('division');
		$unit             = $this->input->post('unit');
		$type             = $this->input->post('type');
		$type_name        = $this->input->post('type_name');
		$fpjp_name        = $this->input->post('fpjp_name');
		$fpjp_date        = $this->input->post('fpjp_date');
		$amount           = $this->input->post('amount');
		$currency         = $this->input->post('currency');
		$rate             = $this->input->post('rate');
		$submitter        = $this->input->post('submitter');
		$jabatan_sub      = $this->input->post('jabatan_sub');
		$data_line        = $this->input->post('data_line');
		$justif_type      = $this->input->post('justif_cat');
		$id_fs            = $this->input->post('id_fs');
		$no_invoice       = $this->input->post('no_invoice');
		$invoice_date     = $this->input->post('invoice_date');
		$notes            = $this->input->post('notes');
		$doc_list         = $this->input->post('doc_list');
		$attachment       = $this->input->post('attachment');
		$data_boq         = $this->input->post('data_boq');
		$vendor           = $this->input->post('vendor');
		$bank_name        = $this->input->post('bank_name');
		$pemilik_rekening = $this->input->post('pemilik_rekening');
		$no_rekening      = $this->input->post('no_rekening');
		$id_fs_new_1      = $this->input->post('id_fs_new_1');
		$id_fs_new_2      = $this->input->post('id_fs_new_2');
		$list_fs_rkap     = $this->input->post('list_fs_rkap');


		if($fpjp_date != ""){
			$exp_fpjp_date = explode("-", $fpjp_date);
			$fpjp_date = $exp_fpjp_date[2]."-".$exp_fpjp_date[1]."-".$exp_fpjp_date[0];
		}

		$get_dir        = $this->crud->read_by_param("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $directorat));
		$id_dir_code    = $get_dir['ID_DIR_CODE'];

		$check_fpjp_exist = $this->crud->check_exist("FPJP_HEADER", array("ID_DIR_CODE" => $id_dir_code));

		$month     = date("m");
		$year      = date("Y");
		$number    = sprintf("%'03d", 1);
		$fpjp_number = $get_dir['DIRECTORAT_CODE']."/".$number."/".date("m")."/".date("Y");

		if($check_fpjp_exist > 0){

			$last_fpjp_number = $this->fpjp->get_last_fpjp_number($id_dir_code);
			$exp_fpjp_number  = explode("/",$last_fpjp_number);
			if(substr($last_fpjp_number, 0, 4) == "FPJP"){
			    $dir_code = $exp_fpjp_number[1];
			    $number   = (int) $exp_fpjp_number[2];
			}else{
			    $dir_code = $exp_fpjp_number[0];
			    $number   = (int) $exp_fpjp_number[1];
			}
			$number += 1;
			$number = sprintf("%'03d", $number);
			$fpjp_number = "FPJP/".$dir_code."/".$number."/".$month."/".$year;

		}
		
		$amount        = str_replace(".", "", $amount);
		$total_amount = (int) $amount;
		$auto_reject   = get_auto_reject_date();

		$data = array(
						"ID_DIR_CODE"        => $id_dir_code,
						"ID_DIVISION"        => $division,
						"ID_UNIT"            => $unit,
						"ID_FS"              => $id_fs,
						"ID_FS_ADD_1"        => $id_fs_new_1,
						"ID_FS_ADD_2"        => $id_fs_new_2,
						"ID_MASTER_FPJP"     => $type,
						"FPJP_NUMBER"        => $fpjp_number,
						"FPJP_NAME"          => $fpjp_name,
						"CURRENCY"           => $currency,
						"CURRENCY_RATE"      => $rate,
						"SUBMITTER"          => $submitter,
						"NOTES_USER"         => $notes,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"AUTO_REJECT_DATE"   => $auto_reject,
						"FPJP_VENDOR_NAME"   => $vendor,
						"FPJP_BANK_NAME"     => $bank_name,
						"FPJP_ACC_NAME"      => $pemilik_rekening,
						"FPJP_ACC_NUMBER"    => $no_rekening,
						"APPROVAL_LEVEL"     => 0,
						"COA_REVIEW"         => 'N',
						"INTERFACE_STATUS"   => 'NEW',
						"STATUS_DESCRIPTION" => "Submitted by ".$submitter,
						"FPJP_AMOUNT"        => $total_amount,
						"JUSTIF_TYPE"        => $justif_type
					);

		if($fpjp_date != ""){
			$data['FPJP_DATE'] = $fpjp_date;
		}
		if($no_invoice != ""){
			$data['NO_INVOICE'] = $no_invoice;
		}
		if($invoice_date != ""){
			$exp_invoice_date = explode("-", $invoice_date);
			$invoice_date = $exp_invoice_date[2]."-".$exp_invoice_date[1]."-".$exp_invoice_date[0];
			$data['INVOICE_DATE'] = $invoice_date;
		}
		if($doc_list != ""){
			$data['DOCUMENT_CHECKLIST'] = (is_array($doc_list)) ? json_encode($doc_list) : '';
		}
		if($attachment != ""){
			$data['DOCUMENT_UPLOAD'] = $attachment;
		}

		$insert     = $this->crud->create("FPJP_HEADER", $data);
		$send_email = false;
		// $insert = 1;

		$check_em = $this->session->userdata('email');
		$custom_input = false;

		if($check_em == "evan_g_pasaribu@linkaja.id" || $check_em == "niga_siregar@linkaja.id" || $check_em == "i-ra_andy@linkaja.id" ){
			$custom_input = true;
		}

			$custom_input = true;
		if($custom_input){
			$sendemail = false;
			$dataUpdFs = array("APPROVAL_LEVEL" => 0, "STATUS" => "approved");
		}else{
			$getLoA        = $this->_getLoAFPJP($insert, $id_dir_code, $division, $unit, $total_amount);

			$data_approval = $getLoA['data_approval'];

			$recipient['email'] = $getLoA['email_to'];
			$recipient['name']  = $getLoA['email_name'];

			$dataUpdFs = array("APPROVAL_LEVEL" => count($data_approval), "STATUS_DESCRIPTION" => "Submitted by ".$submitter);

			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_FPJP", $data_approval);
			$id_approval     = $this->db->insert_id();
		}

		$status   = false;
		$messages = "";

		if($insert > 0){

			$fpjp_line_number = 1;

			if($id_fs > 0){
				$this->budget->update_fs_status($id_fs, "fs used", "FPJP");
			}

			$id_fs_new = false;

			if($id_fs_new_1 != "0" && $id_fs_new_2 != "0"){
				$id_fs_new[] = $id_fs_new_1;
				$id_fs_new[] = $id_fs_new_2;
			}else{
				$id_fs_new[] = $id_fs_new_1;
			}


			// $get_rkap_by_fs = $this->fpjp->get_rkap_by_fs($id_fs_new);

			foreach ($data_line as $key => $value) {
				$nominal = $value['nominal'];
			}
			$absort = 0;
			foreach ($data_line as $key => $value) {

				// $fa_fs_rkap = $this->fpjp->get_fa_fs_by_id_rkap($value['id_rkap']);
				$fa_fs_rkap = get_absortion_justif($id_fs, $value['id_rkap']);

				if($total_amount < $fa_fs_rkap){
					$id_fs_new = false;
				}

				$absort = $total_amount-($value['nominal']-$fa_fs_rkap[0]['FA_FS']);

				$detail_data = $value['detail_data'];
				// echo_pre($detail_data);die;
				$original_amount = str_replace(',','',$value['original_amount']);

				$line_name = $value['line_name'];
				$data_lines = array(
										"FPJP_HEADER_ID"    => $insert,
										"FPJP_LINES_NUMBER" => $fpjp_line_number,
										"FPJP_LINE_NAME"    => $line_name,
										"ID_RKAP_LINE"      => $value['id_rkap'],
										"ID_FS"             => $id_fs,
										"PEMILIK_REKENING"  => $pemilik_rekening,
										"NAMA_BANK"         => $bank_name,
										"NO_REKENING"       => $no_rekening,
										"FPJP_LINE_AMOUNT"  => $absort,
										"ORIGINAL_AMOUNT"  	=> $original_amount
									);

				$data_lines_add = array();


				if($id_fs_new !== false){

					for ($i=0; $i < count($id_fs_new); $i++) {

						foreach ($list_fs_rkap as $x => $v) {
							if($v['id_fs'] == $id_fs_new[$i]){
								$this->budget->update_fs_status($v['id_fs'], "fs used", "FPJP");
								$id_fs_nya = $v['id_fs'];
								$id_rkap_nya = $v['id_rkap'];
								$fa_fs_rkap = get_absortion_justif($id_fs_nya, $id_rkap_nya);
								$absortnya = $fa_fs_rkap[0]['FA_FS']-($total_amount-$absort);
							}
						}

						$data_lines_add[] = array(
												"FPJP_HEADER_ID"    => $insert,
												"FPJP_LINES_NUMBER" => $fpjp_line_number,
												"FPJP_LINE_NAME"    => $line_name,
												"ID_RKAP_LINE"      => $id_rkap_nya,
												"ID_FS"             => $id_fs_nya,
												"PEMILIK_REKENING"  => $pemilik_rekening,
												"NAMA_BANK"         => $bank_name,
												"NO_REKENING"       => $no_rekening,
												"FPJP_LINE_AMOUNT"  => $absortnya,
												"IS_SHOW"  => 0,
												"ORIGINAL_AMOUNT"  	=> $original_amount
											);
				// $insert_line = $this->crud->create("FPJP_LINES", $data_lines);
					}

				}

				$insert_line = $this->crud->create("FPJP_LINES", $data_lines);

				// $insert_line = 1;

				if($data_lines_add){
					$insert_new_line = $this->crud->create("FPJP_LINES", $data_lines);
				}
				if($insert_line > 0){

					$fpjp_detail_number =1;
					$detaillAdded = "";

					foreach ($detail_data as $key => $value_dtl) {

						$data_details[] = array(
											"FPJP_HEADER_ID"     => $insert,
											"FPJP_LINES_ID"      => $insert_line,
											"FPJP_DETAIL_NUMBER" => $fpjp_detail_number,
											"FPJP_DETAIL_DESC"   => $value_dtl['rkap_desc'],
											"QUANTITY"           => $value_dtl['quantity'],
											"TAX"                => $value_dtl['tax'],
											"VATSA"              => 0,
											"PPH"                => 0,
											"PRICE"              => $value_dtl['price'],
											"FPJP_DETAIL_AMOUNT" => $value_dtl['nominal'],
											"CREATED_BY"         => $this->session->userdata('identity')
										);

						$fpjp_detail_number++;
					}

				}

				$fpjp_line_number++;
			}

			$insert_detail = $this->crud->create_batch("FPJP_DETAIL", $data_details);

			if($insert_detail){

				if($detaillAdded){
					$this->crud->update("FPJP_HEADER", array("UPDATED_DATE" => NULL, "FPJP_AMOUNT" => $update_nominal['nominal_header']), array("FPJP_HEADER_ID" => $insert));
					$this->crud->update("FPJP_LINES", array("UPDATED_DATE" => NULL, "FPJP_LINE_AMOUNT" => $update_nominal['nominal_line']), array("FPJP_LINES_ID" => $update_nominal['id_line']));
				}
				if($send_email){
					$this->_email_aprove($recipient, $insert, $id_approval);
				}
				$data_detail_boq = array();
				$fpjp_detail_number=1;

				if($data_boq){

					foreach ($data_boq as $key => $value) {

						$receipt_date = $value['receipt_date'];

						if($receipt_date != ""){
							$exp_rd_date = explode("-", $receipt_date);
							$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];
						}

						$invoice_date = $value['invoice_date'];

						if($invoice_date != ""){
							$exp_id_date = explode("-", $invoice_date);
							$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];
						}

						$data_detail_boq[] = array(
											"FPJP_HEADER_ID"    	=> $insert,
											"FPJP_DETAIL_NUMBER" 	=> $fpjp_detail_number,
											"FPJP_DETAIL_NAME"  	=> $value['fpjp_item'],
											"FPJP_DETAIL_DESC"  	=> $value['fpjp_desc'],
											"UOM"    				=> $value['uom'],
											"QUANTiTY"    			=> $value['qty_boq'],
											"PRICE"    				=> $value['unit_price'],
											"FPJP_DETAIL_AMOUNT"  	=> $value['total_price'],
											"UMUR_MANFAAT"  	    => $value['umur_manfaat'],
											"SERIAL_NUMBER"  	    => $value['serial_number'],
											"MEREK"  	    		=> $value['merek'],
											"ASSET_TYPE"  	    	=> $value['asset_type'],
											"INVOICE_DATE"  	    => $id_date,
											"RECEIPT_DATE"  	    => $rd_date
										);
						$fpjp_detail_number++;
					}
					if(count($data_detail_boq) > 0){
						$insert_boq = $this->crud->create_batch("FPJP_BOQ", $data_detail_boq);
					}
				}

				$status    = true;
			}else{
				$messages = "Failed to Create FPJP Detail";
			}

/*

			if($id_fs_new_1 != "0" && $id_fs_new_2 != "0"){
				$id_fs_new[] = $id_fs_new_1;
				$id_fs_new[] = $id_fs_new_2;
			}else{
				$id_fs_new = $id_fs_new_1;
			}

			$get_rkap_by_fs = $this->fpjp->get_rkap_by_fs($id_fs_new);

			$absort = $total_amount-$absort;

			foreach ($get_rkap_by_fs as $key => $value) {
				$fpjp_detail_number++;

				// $nominal_abs = $value['FA_FS']-($absort;



				$data_lines = array(
										"FPJP_HEADER_ID"    => $insert,
										"FPJP_LINES_NUMBER" => $fpjp_line_number,
										"ID_RKAP_LINE"      => $value['ID_RKAP_LINE'],
										"ID_FS"             => $value['ID_FS'],
										"FPJP_LINE_AMOUNT"  => $absort,
										"FPJP_LINE_NAME"    => $line_name,
										"IS_SHOW"           => 0,
										"PEMILIK_REKENING"  => $pemilik_rekening,
										"NAMA_BANK"         => $bank_name,
										"NO_REKENING"       => $no_rekening,
										"ORIGINAL_AMOUNT"   => $original_amount
									);

				// echo_pre($data_lines);

				$insert_line = $this->crud->create("FPJP_LINES", $data_lines);
			}
				// die;

			if($detaillAdded){
				$data_details[] = $detaillAdded;
			}

			$insert_detail = $this->crud->create_batch("FPJP_DETAIL", $data_details);

			if($insert_detail){

				if($detaillAdded){
					$this->crud->update("FPJP_HEADER", array("UPDATED_DATE" => NULL, "FPJP_AMOUNT" => $update_nominal['nominal_header']), array("FPJP_HEADER_ID" => $insert));
					$this->crud->update("FPJP_LINES", array("UPDATED_DATE" => NULL, "FPJP_LINE_AMOUNT" => $update_nominal['nominal_line']), array("FPJP_LINES_ID" => $update_nominal['id_line']));
				}
				if($send_email){
					$this->_email_aprove($recipient, $insert, $id_approval);
				}
				$data_detail_boq = array();
				$fpjp_detail_number=1;

				if($data_boq){

				foreach ($data_boq as $key => $value) {

					$receipt_date = $value['receipt_date'];

					if($receipt_date != ""){
						$exp_rd_date = explode("-", $receipt_date);
						$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];
					}

					$invoice_date = $value['invoice_date'];

					if($invoice_date != ""){
						$exp_id_date = explode("-", $invoice_date);
						$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];
					}

				$data_detail_boq[] = array(
										"FPJP_HEADER_ID"    	=> $insert,
										"FPJP_DETAIL_NUMBER" 	=> $fpjp_detail_number,
										"FPJP_DETAIL_NAME"  	=> $value['fpjp_item'],
										"FPJP_DETAIL_DESC"  	=> $value['fpjp_desc'],
										"UOM"    				=> $value['uom'],
										"QUANTiTY"    			=> $value['qty_boq'],
										"PRICE"    				=> $value['unit_price'],
										"FPJP_DETAIL_AMOUNT"  	=> $value['total_price'],
										"UMUR_MANFAAT"  	    => $value['umur_manfaat'],
										"SERIAL_NUMBER"  	    => $value['serial_number'],
										"MEREK"  	    		=> $value['merek'],
										"ASSET_TYPE"  	    	=> $value['asset_type'],
										"INVOICE_DATE"  	    => $id_date,
										"RECEIPT_DATE"  	    => $rd_date
									);
					$fpjp_detail_number++;
					}
					if(count($data_detail_boq) > 0){
						$insert_boq = $this->crud->create_batch("FPJP_BOQ", $data_detail_boq);
					}
				}

				$status    = true;
			}else{
				$messages = "Failed to Create FPJP Detail";
			}*/

		}
		else{
			$messages = "Failed to Create FPJP";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }
    

	public function save_fpjp_edit(){
		
		$fpjp_header_id   = $this->input->post('fpjp_header_id');
		$directorat       = $this->input->post('directorat');
		$division         = $this->input->post('division');
		$unit             = $this->input->post('unit');
		$fpjp_name        = $this->input->post('fpjp_name');
		$fpjp_date        = $this->input->post('fpjp_date');
		$amount           = $this->input->post('amount');
		$coa_updated      = $this->input->post('coa_updated');
		$submitter        = $this->input->post('submitter');
		$jabatan_sub      = $this->input->post('jabatan_sub');
		$no_invoice       = $this->input->post('no_invoice');
		$invoice_date     = $this->input->post('invoice_date');
		$notes            = $this->input->post('notes');
		$attachment       = $this->input->post('attachment');
		$data_line        = $this->input->post('data_line');
		$data_boq         = $this->input->post('data_boq');
		$vendor           = $this->input->post('vendor');
		$bank_name        = $this->input->post('bank_name');
		$pemilik_rekening = $this->input->post('pemilik_rekening');
		$no_rekening      = $this->input->post('no_rekening');

		if($fpjp_date != ""){
			$exp_fpjp_date = explode("-", $fpjp_date);
			$fpjp_date = $exp_fpjp_date[2]."-".$exp_fpjp_date[1]."-".$exp_fpjp_date[0];
		}

		$auto_reject   = get_auto_reject_date();
		$amount        = str_replace(".", "", $amount);
		$total_amount  = (int) $amount;
		$send_email    = false;


		$get_status = $this->crud->read_by_param_specific("FPJP_HEADER", array("FPJP_HEADER_ID" => $fpjp_header_id), "STATUS");
		$status_fpjp = $get_status[0]['STATUS'];

		$group_name = get_user_group_data();
		$enableEdit = (in_array("FPJP Edit", $group_name)) ? true : false;

		if($status_fpjp == "returned" && $enableEdit == false):
			$send_email = true;
			$getLoA        = $this->_getLoAFPJP($fpjp_header_id, $directorat, $division, $unit, $total_amount);

			$data_approval = $getLoA['data_approval'];

			$recipient['email'] = $getLoA['email_to'];
			$recipient['name']  = $getLoA['email_name'];

			$dataUpdFs = array("APPROVAL_LEVEL" => count($data_approval), "STATUS_DESCRIPTION" => "Submitted by ".$submitter);

			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_FPJP", $data_approval);
			$id_approval     = $this->db->insert_id();
			
			$this->crud->delete("TRX_APPROVAL_FPJP", array("FPJP_HEADER_ID" => $fpjp_header_id));
			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_FPJP", $data_approval);
			$id_approval     = $this->db->insert_id();
		endif;

		if($enableEdit == true):
			$send_email = false;
		endif;

		$data = array(
						"FPJP_NAME"          => $fpjp_name,
						"FPJP_DATE"          => $fpjp_date,
						"FPJP_AMOUNT"        => $total_amount,
						"SUBMITTER"          => $submitter,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"FPJP_VENDOR_NAME"   => $vendor,
						"FPJP_BANK_NAME"     => $bank_name,
						"FPJP_ACC_NAME"      => $pemilik_rekening,
						"FPJP_ACC_NUMBER"    => $no_rekening,
						"NOTES_USER" 		 => $notes
					);

		if($invoice_date != ""){
			$exp_invoice_date = explode("-", $invoice_date);
			$invoice_date = $exp_invoice_date[2]."-".$exp_invoice_date[1]."-".$exp_invoice_date[0];
			$data['INVOICE_DATE'] = $invoice_date;
		}

		if($no_invoice != ""){
			$data['NO_INVOICE'] = $no_invoice;
		}
		if($attachment != ""){
			$data['DOCUMENT_UPLOAD'] = $attachment;
		}
		
		if($status_fpjp == "returned" && $enableEdit == false):
			$data['APPROVAL_LEVEL']     = count($data_approval);
			$data['STATUS']             = "request_approve";
			$data['STATUS_DESCRIPTION'] = "Resubmitted by ".$submitter;
		endif;
		if($coa_updated == 'true'):
			$send_email = false;
			$data['PIC_COA']         = $this->user_active;
			$data['COA_REVIEW_DATE'] = date("Y-m-d H:i:s", time());
			$data['COA_REVIEW']      = "Y";
		endif;

		$update   = $this->crud->update("FPJP_HEADER", $data, array("FPJP_HEADER_ID" => $fpjp_header_id));

		$status   = false;
		$messages = "";

		if($update !== -1){

			foreach ($data_line as $key => $value) {

				$detail_data = $value['detail_data'];
				$original_amount = str_replace(',','',$value['original_amount']);
				$data_lines = array(
										"FPJP_LINE_NAME"   => $value['line_name'],
										"FPJP_LINE_AMOUNT" => $value['nominal'],
										"ORIGINAL_AMOUNT"  => $original_amount,
										"PEMILIK_REKENING" => $pemilik_rekening,
										"NAMA_BANK"        => $bank_name,
										"NO_REKENING"      => $no_rekening
									);
				$update_lines = $this->crud->update("FPJP_LINES", $data_lines, array("FPJP_LINES_ID" => $value['fpjp_lines_id']));

				if($update_lines !== -1){
					if($detail_data){
						$delete_detail = $this->crud->delete("FPJP_DETAIL", array("FPJP_LINES_ID" => $value['fpjp_lines_id']));
						$fpjp_detail_number = 1;
						foreach ($detail_data as $key => $value_dtl) {

							$data_details[] = array(
												"FPJP_HEADER_ID"     => $fpjp_header_id,
												"FPJP_LINES_ID"      => $value['fpjp_lines_id'],
												"FPJP_DETAIL_NUMBER" => $fpjp_detail_number,
												"FPJP_DETAIL_DESC"   => $value_dtl['rkap_desc'],
												"ID_MASTER_COA"      => $value_dtl['nature'],
												"QUANTITY"           => $value_dtl['quantity'],
												"TAX"                => $value_dtl['tax'],
												"PRICE"              => $value_dtl['price'],
												"FPJP_DETAIL_AMOUNT" => $value_dtl['nominal_detail'],
												"CREATED_BY"         => $this->session->userdata('identity'),
												"UPDATED_BY"         => $this->session->userdata('identity')
											);
							$fpjp_detail_number++;
						}
					}
				}
			}

			if($data_boq){

				$fpjp_detail_number_boq = 1;
				foreach ($data_boq as $key => $value) {

					$receipt_date = $value['receipt_date'];
					if($receipt_date != ""){
						$exp_rd_date = explode("-", $receipt_date);
						$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];
					}

					$invoice_date = $value['invoice_date'];
					if($invoice_date != ""){
						$exp_id_date = explode("-", $invoice_date);
						$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];
					}

					$data_detail_boq[] = array(
										"FPJP_HEADER_ID"  	    => $fpjp_header_id,
										"FPJP_DETAIL_NAME"  	=> $value['fpjp_item'],
										"FPJP_DETAIL_DESC"  	=> $value['fpjp_desc'],
										"UOM"    				=> $value['uom'],
										"FPJP_DETAIL_NUMBER" 	=> $fpjp_detail_number_boq,
										"QUANTITY"    			=> $value['qty_boq'],
										"PRICE"    				=> $value['unit_price'],
										"FPJP_DETAIL_AMOUNT"  	=> $value['total_price'],
										"ASSET_TYPE"  			=> $value['asset_type'],
										"SERIAL_NUMBER"  		=> $value['serial_number'],
										"MEREK"  				=> $value['merek'],
										"UMUR_MANFAAT"  		=> $value['umur_manfaat'],
										"INVOICE_DATE"  		=> $id_date,
										"RECEIPT_DATE"  		=> $rd_date
									);
					$fpjp_detail_number_boq++;

				}
				if($this->crud->delete("FPJP_BOQ", array("FPJP_HEADER_ID" => $fpjp_header_id))){
					$this->crud->create_batch("FPJP_BOQ", $data_detail_boq);
				}
			}

			if(count($data_details) > 0){
				$insert_detail = $this->crud->create_batch("FPJP_DETAIL", $data_details);
				if($insert_detail){
					if($send_email){
						$this->_email_aprove($recipient, $fpjp_header_id, $id_approval);
					}
					$log_info = $fpjp_header_id . " - Updated by " . $this->user_active;
					$status    = true;
				}else{
					$messages = "Failed to Create FPJP Detail";
					$log_info = $fpjp_header_id . " - ". $messages ." by " . $this->user_active;
				}
			}else{
				if($send_email){
					$this->_email_aprove($recipient, $fpjp_header_id, $id_approval);
				}
				$status    = true;
				$log_info = $fpjp_header_id . " - Updated by " . $this->user_active;
			}

			if($coa_updated == 'true'):
				/*$no_jurnal = $this->_invoicing_to_gl($fpjp_header_id);
				if( $no_jurnal ){
					$get_user_invoice = get_user_approval_ap("INVOICE_APPROVAL");
					if($get_user_invoice)
					{
						$recipient = array();
						foreach ($get_user_invoice as $key => $value) 
						{
							$recipient[]  = array('name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
						}
						$this->_email_invoice($recipient, $no_jurnal, $submitter);
					}
				}*/
			endif;
		}
		else{
			$messages = "Failed to Update FPJP";
			$log_info = $fpjp_header_id . " - ". $messages ." by " . $this->user_active;
		}

		log_message('info', $this->module_title . ":  " . $log_info);

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }
    

	public function delete(){

		$id       = $this->input->post('id');
		$category = $this->input->post('category');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$update_fs = false;
		if($category == "header"){
			$delete = $this->crud->delete("FPJP_HEADER", array("FPJP_HEADER_ID" => $id));
			$update_fs = true;
		}
		elseif($category == "lines"){
			$delete = $this->crud->delete("FPJP_LINE", array("FPJP_LINE_ID" => $id));
		}
		else{
			$delete = $this->crud->delete("FPJP_DETAIL", array("FPJP_DETAIL_ID" => $id));
		}

		if($delete > 0){

			/*if($update_fs){
				$get_fs = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $id));
				$id_fs  = $get_fs['ID_FS'];
				$check_fs_exist = $this->budget->check_fs_exist($id_fs);
				if($check_fs_exist == 0){
					$this->crud->update("FS_BUDGET", array("STATUS" => "approved"), array("ID_FS" => $id_fs ));
				}
			}*/
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

    private function _email_aprove($recipient, $id_fpjp, $id_approval){

		$get_fpjp      = $this->fpjp->get_fpjp_for_email($id_fpjp);
		
		$directorat    = get_directorat($get_fpjp['ID_DIR_CODE']);
		$division      = get_division($get_fpjp['ID_DIVISION']);
		$unit          = get_unit($get_fpjp['ID_UNIT']);
		$currency      = ($get_fpjp['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_fpjp['FPJP_AMOUNT'],0,',','.');
		$fpjp_type     = get_type($get_fpjp['ID_MASTER_FPJP']);
		$fpjp_name     = $get_fpjp['FPJP_NAME'];
		$fpjp_number   = $get_fpjp['FPJP_NUMBER'];
		$attachment    = $get_fpjp['DOCUMENT_ATTACHMENT'];
		$submitter     = $get_fpjp['SUBMITTER'];
		$currency_rate = ($get_fpjp['CURRENCY'] == "IDR") ? $get_fpjp['CURRENCY'] : $get_fpjp['CURRENCY'] ."/". number_format($get_fpjp['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("fpjp/approval/").base64url_encode($id_fpjp."-".$id_approval.$this->config->item('encryption_key'));

		$data['email_recipient']  = $recipient['name'];
		$data['email_fpjpeview'] = "A new FPJP request $fpjp_number has been submitted for your approval";


		$addedBody = "";
		$addedFpjpType = "";
		if($get_fpjp['FS_NUMBER']){
			$justification = $get_fpjp['FS_NUMBER'] ." - " . $get_fpjp['FS_NAME'];
			$rkap          = $get_fpjp['RKAP_DESCRIPTION'];
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

		$data['email_body'] = "
								A new FPJP request $fpjp_number has been Submitted by $submitter and need for your approval. You can see all the details about this FPJP by clicking the link below.
								<br>
								<br>
								Please go through the <a href='$approval_lnk'>link</a> and confirm your approval.
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
											<td width='29%'>FPJP Number</td>
											<td width='1%'>:</td>
											<td width='70%'><b>$fpjp_number</b></td>
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
		$data['approval_link_all'] = base_url("fpjp/approval");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "New FPJP Request - $fpjp_number - $fpjp_name";
		$body       = $this->load->view('email/fpjp_request_approve', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
	}


	public function load_fpjp_detail_for_edit(){

		$fpjp_lines_id = $this->input->post('fpjp_lines_id');
		
		$get_all = $this->fpjp->get_fpjp_details($fpjp_lines_id);

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];

		$row = array();

		if($total > 0){

			foreach($data as $value) {

				$number = $value['FPJP_DETAIL_NUMBER'];

				$row[] = array(
					'fpjp_detail_id' => $value['FPJP_DETAIL_ID'],
					'fpjp_lines_id'  => $value['FPJP_LINES_ID'],
					'no'             => $number,
					'rkap_desc'      => $value['FPJP_DETAIL_DESC'],
					'nature'         => $value['ID_MASTER_COA'],
					'quantity'       => $value['QUANTITY'],
					'tax'            => $value['TAX'],
					'price'          => $value['PRICE'],
					'nominal'        => $value['FPJP_DETAIL_AMOUNT']
				);
			}

		}

		echo json_encode($row);

	}


	function download_fpjp($param="")
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}
		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();
		$excel->getProperties()	->setCreator('FINANCE TOOL - SYSTEM')
		->setLastModifiedBy('FINANCE TOOL - SYSTEM')
		->setTitle("Download Data")
		->setSubject("Download Data")
		->setDescription("Download Data")
		->setKeywords("DATA");

		$arr_field = array( "No.", "Directorate", "Division", "Unit", "FPJP Number", "FPJP Name", "FPJP Date", "Currency",/* "Rate",*/ "Total Amount", "Submitter", "Jabatan" );

		$start_column = 'A';
		$numrow  = 1;

		foreach ($arr_field as $value) {
			$excel->setActiveSheetIndex(0)->setCellValue($start_column.$numrow, $value);
			$start_column++;
		}


		$date_from   = "";
		$date_to     = "";

		if($param != ""){

			$decrypt = decrypt_string($param, true);

			if( is_object( json_decode($decrypt))){
				$obj_param   = json_decode($decrypt);
				$id_dir_code = $obj_param->id_dir_code;
				$id_division = $obj_param->id_division;
				$id_unit     = $obj_param->id_unit;
				$status      = $obj_param->status;
				$fpjp_date     = $obj_param->fpjp_date;

				if($fpjp_date){
					$exp_fpjp_date = explode(" - ", $fpjp_date);
					$date_from = date_db($exp_fpjp_date[0]);
					$date_to   = date_db($exp_fpjp_date[1]);
				}
			}
		}

		$hasil = $this->fpjp->get_download_data_fpjp_header($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);

		$numrow++;
		$number = 1;

		foreach($hasil->result_array() as $row)	{

			$start_column = 'A';

			$fpjp_date = date("d-m-Y",strtotime($row['FPJP_DATE']));
			$curr      = strtoupper($row['CURRENCY']);

			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['UNIT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['FPJP_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['FPJP_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $fpjp_date);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $curr);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['FPJP_AMOUNT']);
			$excel->getActiveSheet()->getStyle($start_column.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['SUBMITTER']);
			$excel->setActiveSheetIndex(0)->setCellValue($start_column++.$numrow, $row['JABATAN_SUBMITTER']);

			$number++;
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Data");
		$excel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="FPJP_'.date_unique().'.xls"');
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	public function printPDF($fpjp_header_id){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$decrypt        = decrypt_string($fpjp_header_id, true);
		$fpjp_header_id = (int) $decrypt;

		if($fpjp_header_id == 0){
			redirect('fpjp','refresh');
			exit;
		}

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/cetakan_justif.pdf';

		$hasil = $this->fpjp->get_header($fpjp_header_id);
		$data = $hasil['data'];

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);
		$mpdf->Image('assets/img/fintek.jpg',15,10,35);

		$mpdf->SetFont('Roboto','',11);
		$height = 23;
		$mpdf->SetXY(60, $height);
		$mpdf->Cell(10, 10, "FORMULIR PERTANGGUNG JAWABAN PENGELUARAN",0,"");

		$mpdf->SetFont('Roboto','',6);
		$height = 6;
		$mpdf->SetXY(100, $height);
		$mpdf->Cell(10, 10, "FPJP Report",0,"");

		$id_fs = 0;

		foreach ($data as $row){

			$submitter             = $row['SUBMITTER'];
			$jabatan_sub           = $row['JABATAN_SUBMITTER'];
			$diketahui1            = $row['DIKETAHUI_1'];
			$diketahui2            = $row['DIKETAHUI_2'];
			$pemilik               = $row['PEMILIK_REKENING'];
			$currency              = $row['CURRENCY'];
			$fpjp_amount           = $row['FPJP_AMOUNT'];
			$fpjp_nama_vendor      = $row['FPJP_VENDOR_NAME'];
			$fpjp_pemilik_rekening = $row['FPJP_ACC_NAME'];
			$fpjp_nama_bank        = $row['FPJP_BANK_NAME'];
			$fpjp_account_number   = $row['FPJP_ACC_NUMBER'];

			$id_fs = ($row['ID_FS'] != 0) ? $row['ID_FS'] : $row['ID_FS2'];

			$mpdf->SetTextColor(0,0,0);
			$mpdf->SetFont('Roboto','',5.5);

			$guideline = 0;

			$title = "FPJP " . $row['FPJP_NUMBER'] . " - " . $row['FPJP_NAME'];

		}
		$jabatan_sub = str_replace("&amp;", "&", $jabatan_sub);

        $mpdf->SetTitle($title);

		$mpdf->SetTextColor(0,0,0);
		$guideline = 0;

		$mpdf->SetFont('Roboto','',5.5);
		$height = 39.5;
		$mpdf->SetXY(13.3, $height);
		$mpdf->Cell(11.6,10,"Nomor FPJP :",$guideline,1,"R");

		$height = 39.5;
		$mpdf->SetXY(24, $height);
		$mpdf->Cell(10,10,$row['FPJP_NUMBER'],$guideline,1,"L");

		$height = 42;
		$mpdf->SetXY(13.3, $height);
		$mpdf->Cell(11.6,10,"FPJP Type :",$guideline,1,"R");

		$height = 42;
		$mpdf->SetXY(24, $height);
		$mpdf->Cell(10,10, $row['FPJP_TYPE'],$guideline,1,"L");

		$height = 45;
		$mpdf->SetXY(13.3, $height);
		$mpdf->Cell(11.6,10,"Tanggal :",$guideline,1,"R");

		$height = 45;
		$mpdf->SetXY(24, $height);
		$mpdf->Cell(10,10, dateFormat($row['FPJP_DATE'], 4, false),$guideline,1,"L");

		$height = 39.5;
		$mpdf->SetXY(166, $height);
		$mpdf->Cell(8,10,"Nama/NIK :",$guideline,1,"R");

		$height = 39.5;
		$mpdf->SetXY(173, $height);
		$mpdf->Cell(10,10,$row['SUBMITTER'],$guideline,1,"L");

		$height = 42.5;
		$mpdf->SetXY(166, $height);
		$mpdf->Cell(8,10,"Jabatan :",$guideline,1,"R");

		$jabatan_upln = strlen($row['JABATAN_SUBMITTER']);
		if($jabatan_upln <= 30){
			$height = 42.5;
			$mpdf->SetXY(173, $height);
			$mpdf->Cell(10,10,substr($row['JABATAN_SUBMITTER'],0,30),$guideline,1,"L");
		}else{
			$height = 42.5;
			$mpdf->SetXY(173, $height);
			$mpdf->Cell(10,10,substr($row['JABATAN_SUBMITTER'],0,30),$guideline,1,"L");

			$height = 45;
			$mpdf->SetXY(173, $height);
			$mpdf->Cell(10,10,substr($row['JABATAN_SUBMITTER'],30,60),$guideline,1,"L");
		}

		$heightd = $height+2.5;
		$mpdf->SetXY(166, $heightd);
		$mpdf->Cell(8,10,"Divisi :",$guideline,1,"R");

		$divisi = strlen($row['DIVISION_NAME']);
		if($divisi <= 30){
			$heightstr = $heightd;
			$mpdf->SetXY(173, $heightstr);
			$mpdf->Cell(10,10,substr($row['DIVISION_NAME'],0,30),$guideline,1,"L");
		}else{
			$heightstr = $heightd;
			$mpdf->SetXY(173, $heightstr);
			$mpdf->Cell(10,10,substr($row['DIVISION_NAME'],0,30),$guideline,1,"L");

			$heightstr2 = $heightstr+2.5;
			$mpdf->SetXY(173, $heightstr2);
			$mpdf->Cell(10,10,substr($row['DIVISION_NAME'],30,60),$guideline,1,"L");
		}
		if($id_fs > 0){

			$get_justif = $this->crud->read_by_param("FS_BUDGET", array("ID_FS" => $id_fs));
			$justification = $get_justif['FS_NUMBER'] . " - " .  $get_justif['FS_NAME'];
			$mpdf->SetXY(11.6, 47.8);
			$mpdf->Cell(11.6,10,"Justification :  ".$justification,$guideline,2,"L");

		}else{
			$mpdf->SetXY(11.6, 47.8);
			$mpdf->Cell(11.6,10,"Justification :  Non Justif",$guideline,2,"L");
		}

		$mpdf->SetFont('Courier New','',8);
		$height = 55;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10,10,"Mohon dapat dibayarkan pengeluaran untuk:",$guideline,1,"L");

        $cell_1 = 10;
        $cell_2 = 55;
        $cell_3 = 65;
        $cell_4 = 20;
        $cell_5 = 30;

        $mpdf->Cell($cell_1,6,'No',1,0,'C');
        $mpdf->Cell($cell_2,6,'Keterangan',1,0,'C');
        $mpdf->Cell($cell_3,6,'Kode Akun',1,0,'C');
        $mpdf->Cell($cell_4,6,'Mata Uang',1,0,'C');
        $mpdf->Cell($cell_5,6,'Jumlah',1,1,'C');

        $mpdf->SetFont('Courier New','',8);
        // $hasil_detail = $this->fpjp->get_cetak($fpjp_header_id);
		// $data_detail = $hasil_detail['data'];
        $data_detail = $this->fpjp->get_detail_pdf($fpjp_header_id);
        $no=1;

        $rowHeight = 0;

        $generateNewHeightY = 0;

        $total = count($data_detail);

        if($total < 3){
        	$generateNewHeightY = 15;
        }
        elseif($total < 7){
        	$generateNewHeightY = 5;
        }
        elseif($total < 10){
        	$generateNewHeightY = 0;
        }else{
        	$generateNewHeightY -= 5;
        }

        foreach ($data_detail as $val){
            $rowHeight += 6;

			$heightY   = 6;
			$detail    = $val['FPJP_DETAIL_DESC'];
			$nature    = $val['NATURE'] ." - " .$val['DESCRIPTION'];
			$detail_ln = strlen($detail)." ";
			$nature_ln = strlen($nature)." ";

        	if($detail_ln > 30 || $nature_ln > 30){
        		// $heightY +=6;
				$detail = str_replace(" ", " |", $detail);
				$newDetail = explode("|",$detail);
				$newLine = "";

				$strC = 0;
				$lastDetail = "";
				$detaillinebreak = "";
				$lastdetaillinebreak = "";

				for ($i=0; $i < count($newDetail); $i++) 
				{
					$str = strlen($newDetail[$i]);
					$strC += $str;

					if($strC > 60)
					{
						$i_last = $i;
						$lastdetaillinebreak .= $newLine.$newDetail[$i];
					}
					else if ($strC > 30 && $strC <= 60)
					{
						$i_last = $i;
						$detaillinebreak .= $newLine.$newDetail[$i];
					}
					else
					{
						$lastDetail .= $newLine.$newDetail[$i];
					}

				}

				$nature = str_replace(" ", " |", $nature);
				$newNature = explode("|",$nature);
				$newLine = "";

				$strC = 0;
				$lastNature = "";
				$natureLineBreak = "";
				$lastnatureLineBreak = "";

				for ($i=0; $i < count($newNature); $i++) 
				{
					$str = strlen($newNature[$i]);
					$strC += $str;

					if($strC > 60)
					{
						$i_last = $i;
						$lastnatureLineBreak .= $newLine.$newNature[$i];
					}
					else if ($strC > 30 && $strC <= 60)
					{
						$i_last = $i;
						$natureLineBreak .= $newLine.$newNature[$i];
					}
					else
					{
						$lastNature .= $newLine.$newNature[$i];
					}

				}
			}

			
			if($detail_ln > 60 || $nature_ln > 60)
			{

				$mpdf->Cell($cell_1,$heightY, $no,"R L",0,'C');
				$mpdf->Cell($cell_2,$heightY, $lastDetail,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $lastNature,"R L",0,'L');
				$mpdf->Cell($cell_4,$heightY, $currency, "R L",0,'C');
				$mpdf->Cell($cell_5,$heightY,number_format($val['FPJP_DETAIL_AMOUNT'],0,',','.'),"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY, " ","R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $detaillinebreak,"R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY, $natureLineBreak,"R L",0,'L');
				$mpdf->Cell($cell_4,$newHeightY, " ","R L",0,'L');
				$mpdf->Cell($cell_5,$newHeightY, " ","R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY, " ","B R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $lastdetaillinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY, $lastnatureLineBreak,"B R L",0,'L');
				$mpdf->Cell($cell_4,$newHeightY, " ","B R L",0,'L');
				$mpdf->Cell($cell_5,$newHeightY, " ","B R L",1,'C');

			}
			else if ( ($detail_ln > 30 && $detail_ln <=60) || ($nature_ln > 30 && $nature_ln <=60))
			{

				$mpdf->Cell($cell_1,$heightY, $no,"R L",0,'C');
				$mpdf->Cell($cell_2,$heightY, $lastDetail,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $lastNature,"R L",0,'L');
				$mpdf->Cell($cell_4,$heightY, $currency, "R L",0,'C');
				$mpdf->Cell($cell_5,$heightY,number_format($val['FPJP_DETAIL_AMOUNT'],0,',','.'),"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY, " ","B R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $detaillinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY, $natureLineBreak,"B R L",0,'L');
				$mpdf->Cell($cell_4,$newHeightY, " ","B R L",0,'L');
				$mpdf->Cell($cell_5,$newHeightY, " ","B R L",1,'C');
			}
			else{

				$mpdf->Cell($cell_1,$heightY, $no,1,0,'C');
	            $mpdf->Cell($cell_2,$heightY, $detail,1,0,'L');
	            $mpdf->Cell($cell_3,$heightY, $nature,1,0,'L');
	            $mpdf->Cell($cell_4,$heightY, $currency, 1,0,'C');
	            $mpdf->Cell($cell_5,$heightY, number_format($val['FPJP_DETAIL_AMOUNT'],0,',','.'),1,1,'C');
			}
			$generateNewHeightY += 10;

			$no++;
        }

        $data_ppn = $this->fpjp->get_tax_pdf($fpjp_header_id);

        $amount_ppn = 0;

        if($data_ppn['TOTAL_TAX'] > 0):

        	$amount_ppn += $data_ppn['TOTAL_AMOUNT_TAX'];

	    	$mpdf->Cell($cell_1,6,$no,1,0,'C');
	        $mpdf->Cell($cell_2,6,"PPN ".$data_ppn['TOTAL_TAX'] ."%",1,0,'L');
	        $mpdf->Cell($cell_3,6, "11512001 - Prepaid Taxes VAT In",1,0,'L');
	        $mpdf->Cell($cell_4,6, $currency,1,0,'C');
	        $mpdf->Cell($cell_5,6, number_format($amount_ppn,0,',','.'),1,1,'C');

	        $no++;
    	endif;

		$generateNewHeightY += 15;
        $generateNewHeightY += 9;

        $height += $generateNewHeightY;

        $mpdf->SetFont('Courier New','',8);

		$mpdf->Cell(10,5,"",0,1,"L");

		$jumlah_desc = ($amount_ppn > 0) ? "Jumlah Setelah PPN" : "Jumlah";

        $mpdf->Cell(20,6,'Mata Uang',1,0,'C');
        $mpdf->Cell(30,6, $jumlah_desc,"B T",0,'C');
        $mpdf->Cell(80,6,'Terbilang',"L B T",0,'C');
        $mpdf->Cell(50,6,'Nomor Rekening, Nama Bank & a/n',1,1,'C');

        $mpdf->SetFont('Courier New','',8);
        //$data = $this->db->get('FPJP_HEADER')->result();
        $hasil_detail = $this->fpjp->get_total($fpjp_header_id);
		$data_detail = $hasil_detail['data'];
        $no=1;
        $addedHeight = 20;


        foreach ($data_detail as $val){
			/*$total          = ($val['FPJP_DETAIL_AMOUNT'] * 10/100) + $val['FPJP_DETAIL_AMOUNT'];*/
			// $total            = $val['PPN'];
			$total            = $val['FPJP_DETAIL_AMOUNT']+$amount_ppn;
			//$vatsa 			  = $fpjp_amount * ($val['VATSA']/100);
			$no_rekening      = ($fpjp_account_number) ? $fpjp_account_number : $val['NO_REKENING'];
			$nama_bank        = ($fpjp_nama_bank) ? $fpjp_nama_bank : $val['NAMA_BANK'];
			$pemilik_rekening = ($fpjp_pemilik_rekening) ? $fpjp_pemilik_rekening : $val['PEMILIK_REKENING'];
			$heightY          = 6;
			$nominal          = number_format($total,0,',','.');
			$terbilang        = terbilang($nominal);
			if(strlen($terbilang) > 50){
				$terbilang       = str_replace(" ", " |", $terbilang);
				$newTerbilang    = explode("|",$terbilang);
				$strC            = 0;
				$firstTerbilang  = $secondTerbilang = $thirdTerbilang  = "";
				for ($i=0; $i < count($newTerbilang); $i++) 
				{
					$strC += strlen($newTerbilang[$i]);
					if($strC > 100):
						$thirdTerbilang .= $newTerbilang[$i];
					elseif($strC > 50 && $strC <= 100):
						$secondTerbilang .= $newTerbilang[$i];
					else:
						$firstTerbilang .= $newTerbilang[$i];
					endif;
				}

			}

			
			if(strlen($terbilang) > 100) {

				$mpdf->Cell(20, $heightY, $currency, "R L", 0, "C");
				$mpdf->Cell(30, $heightY, $nominal, "R L", 0, "R");
				$mpdf->Cell(80, $heightY, ucfirst($firstTerbilang), "R L", 0, "L");
				$mpdf->Cell(50, $heightY, $no_rekening, "R L",1, "L");

				if(strlen($nama_bank) > 30){

					$nama_bank      = str_replace(" ", " |", $nama_bank);
					$newNamaBank    = explode("|",$nama_bank);
					$strC           = 0;
					$firstNamaBank  = "";
					$secondNamaBank = "";
					for ($i=0; $i < count($newNamaBank); $i++) 
					{
						$strC += strlen($newNamaBank[$i]);
						if($strC > 30):
							$secondNamaBank .= $newNamaBank[$i];
						else:
							$firstNamaBank .= $newNamaBank[$i];
						endif;
					}

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R L", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang, "R L", 0, "L");
					$mpdf->Cell(50, $heightY, $firstNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R L", 0, "R");
					$mpdf->Cell(80, $heightY, ltrim($thirdTerbilang." rupiah", " "), "R L", 0, "L");
					$mpdf->Cell(50, $heightY, $secondNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "B R L", 0, "R");
					$mpdf->Cell(80, $heightY, "", "B R L", 0, "L");
					$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");
					$addedHeight = 30;
				}
				else{
					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R L", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang, "R L", 0, "L");
					$mpdf->Cell(50, $heightY, $nama_bank, "R L", 1, "L");

					$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "B R L", 0, "R");
					$mpdf->Cell(80, $heightY, ltrim($thirdTerbilang." rupiah", " "), "B R L", 0, "L");
					$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");
				}
			}
			else if (strlen($terbilang) > 50 && strlen($terbilang) <= 100) {

				$mpdf->Cell(20, $heightY, $currency, "R L", 0, "C");
				$mpdf->Cell(30, $heightY, $nominal, "R", 0, "R");
				$mpdf->Cell(80, $heightY, ucfirst($firstTerbilang), "", 0, "L");
				$mpdf->Cell(50, $heightY, $no_rekening, "R L",1, "L");

				if(strlen($nama_bank) > 30){

					$nama_bank      = str_replace(" ", " |", $nama_bank);
					$newNamaBank    = explode("|",$nama_bank);
					$strC           = 0;
					$firstNamaBank  = "";
					$secondNamaBank = "";
					for ($i=0; $i < count($newNamaBank); $i++) 
					{
						$strC += strlen($newNamaBank[$i]);
						if($strC > 30):
							$secondNamaBank .= $newNamaBank[$i];
						else:
							$firstNamaBank .= $newNamaBank[$i];
						endif;
					}
					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang." rupiah", "", 0, "L");
					$mpdf->Cell(50, $heightY, $firstNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $secondNamaBank, "R L",1, "L");
					$addedHeight = 30;
				}
				else{
					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang." rupiah", "", 0, "L");
					$mpdf->Cell(50, $heightY, $nama_bank, "R L",1, "L");
				}

				$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
				$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
				$mpdf->Cell(80, $heightY, "", "B", 0, "L");
				$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");

			}
			else{

				$mpdf->Cell(20, $heightY, $currency, "R L", 0, "C");
				$mpdf->Cell(30, $heightY, $nominal, "R", 0, "R");
				$mpdf->Cell(80, $heightY, ucfirst($terbilang)." rupiah", "", 0, "L");
				$mpdf->Cell(50, $heightY, $no_rekening, "R L",1, "L");

				if(strlen($nama_bank) > 30){

					$nama_bank      = str_replace(" ", " |", $nama_bank);
					$newNamaBank    = explode("|",$nama_bank);
					$strC           = 0;
					$firstNamaBank  = "";
					$secondNamaBank = "";
					for ($i=0; $i < count($newNamaBank); $i++) 
					{
						$strC += strlen($newNamaBank[$i]);
						if($strC > 30):
							$secondNamaBank .= $newNamaBank[$i];
						else:
							$firstNamaBank .= $newNamaBank[$i];
						endif;
					}

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $firstNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $secondNamaBank, "R L",1, "L");
					$addedHeight = 30;
				}
				else{

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $nama_bank, "R L",1, "L");
				}

				$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
				$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
				$mpdf->Cell(80, $heightY, "", "B", 0, "L");
				$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");

			}

			$no++;
		}

		$data_vatsa = $this->fpjp->get_vatsa($fpjp_header_id);

		$vatsa = $data_vatsa['TOTAL_VATSA'];

		if ($data_vatsa['VATSA'] == 10){
			$mpdf->Cell(10, 10, "V.A.T.S.A  dibayarkan kepada kas Negara : ".number_format($vatsa),0,',','.',$guideline,0,"L");
			$mpdf->Cell(10,10,"",0,1,"L");
		}else{

		}

		$height += $heightY;
		
		$height += $addedHeight;
		// $mpdf->SetXY(15, $height);
		//$mpdf->Cell(10,5,"",0,1,"L");


		$nama_vendor = ($fpjp_nama_vendor) ? $fpjp_nama_vendor : $pemilik_rekening;
		$mpdf->Cell(10,10,"Harap dibayarkan kepada: ".$nama_vendor ,$guideline,1,"L");
		
		$mpdf->SetFont('Courier New','',8);

		$cell_1 = 50;
		$cell_2 = 90;
		$cell_3 = 40;
		
		$mpdf->Cell($cell_1,6,'Nama',1,0,'C');
		$mpdf->Cell($cell_2,6,'Jabatan',1,0,"C");
		$mpdf->Cell($cell_3,6,'Status',1,1,'C');
		
		$mpdf->SetFont('Courier New','',8);
		$mpdf->Cell($cell_1,15,"1. ".$submitter,1,0,'L');
		$mpdf->Cell($cell_2,15, $jabatan_sub,1,0,'L');
		$mpdf->Cell($cell_3,15,"Approved",1,1,'C');


		if($row['STATUS'] == "request_approve"){
			$status = "Waiting for approval";
		}else{
			$status = ucfirst($row['STATUS']);
		}

		$approval[] = ["PIC_NAME" => $diketahui1, "JABATAN" => $row['JABATAN_1'], "STATUS" => ($diketahui1 != "") ? $status : ""];
		$approval[] = ["PIC_NAME" => $diketahui2, "JABATAN" => $row['JABATAN_2'], "STATUS" => ($diketahui1 != "") ? $status : ""];

		$get_approval = $this->fpjp->get_approval_by_fpjp($fpjp_header_id);
		if($get_approval){
			$approval = $get_approval;
		}

		$heightY = 8;
		$height +=35;

		foreach ($approval as $key => $value) {
			$pic_name   = $value['PIC_NAME'];
			$jabatan    = $value['JABATAN'];
			$jabatan_ln = strlen($jabatan);

			if($value['STATUS'] == "request_approve"){
				$status = "Waiting for approval";
			}else{
				$status = ucfirst($value['STATUS']);
			}

			if($jabatan_ln > 40){
        		// $heightY +=6;
				$jabatan = str_replace(" ", " |", $jabatan);
				$newjabatan = explode("|",$jabatan);
				$newLine = "";

				$strC = 0;
				$lastjabatan = "";
				$jabatanlinebreak = "";
				$lastjabatanlinebreak = "";

				for ($i=0; $i < count($newjabatan); $i++) 
				{
					$str = strlen($newjabatan[$i]);
					$strC += $str;

					if($strC > 60)
					{
						$i_last = $i;
						$lastjabatanlinebreak .= $newLine.$newjabatan[$i];
					}
					else if ($strC > 40 && $strC <= 60)
					{
						$i_last = $i;
						$jabatanlinebreak .= $newLine.$newjabatan[$i];
					}
					else
					{
						$lastjabatan .= $newLine.$newjabatan[$i];
					}
				}
			}

			if($jabatan_ln > 60)
			{

				$mpdf->Cell($cell_1,15,$no.". ". $pic_name,"R L",0,'L');
				$mpdf->Cell($cell_2,15,$lastjabatan,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $status,"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY,"","R L",0,'L');
				$mpdf->Cell($cell_2,$newHeightY, $jabatanlinebreak,"R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY,"","R L",1,'L');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY,"","B R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $lastjabatanlinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY,"","B R L",1,'L');

			}
			else if ($jabatan_ln > 40 && $jabatan_ln <=60)
			{

				$mpdf->Cell($cell_1,$heightY, $no.". ". $pic_name,"R L",0,'L');
				$mpdf->Cell($cell_2,$heightY, $lastjabatan,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $status,"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY,"","B R L",0,'L');
				$mpdf->Cell($cell_2,$newHeightY, $jabatanlinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY,"","B R L",1,'L');
			}
			else{

				$mpdf->Cell($cell_1,15, $no.". ". $pic_name,1,0,'L');
				$mpdf->Cell($cell_2,15, $jabatan,1,0,'L');
				$mpdf->Cell($cell_3,15, $status,1,1,'C');
			}

			$no++;
			$height += 15;
		}

		$height;

		$mpdf->SetFont('Courier New','',7);
		// $mpdf->SetXY(15, $height);
		$mpdf->Cell(10,5,"",$guideline,1,"L");
		$mpdf->Cell(10,5,"Catatan : 1. Bukti pertanggungjawaban pengeluaran harus disertai dengan bukti tertulis dan bukti pendukung asli",$guideline,1,"L");
		// $mpdf->SetX(29);
		$mpdf->Cell(10,1,"(kecuali hal ini tidak memungkinkan)",$guideline,1,"L");

		$fpjp_encrypt =$row['FPJP_NUMBER'] . " - " . number_format($fpjp_amount,0,',','.');
		// echo md5(json_encode($fpjp_encrypt));die;

		$doc_ref = encrypt_string($fpjp_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";

		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);

		$title = "FPJP - ".$row['FPJP_NAME'] .".pdf";

		$mpdf->Output($title, "I");
		
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


	private function _getLoAFPJP($id_fpjp, $directorat, $division, $unit, $total_amount){


		$level = 1;
		$data_approval = array();

		$directorat_name = strtolower(get_directorat($directorat));
		$division_name   = strtolower(get_division($division));
		$get_by_unit     = ($division_name == "new business") ? $unit : false;

		$get_hog        = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);

		$email_to       = $get_hog['PIC_EMAIL'];
		$email_name     = $get_hog['PIC_NAME'];
		$jabatan_hog    = ($get_by_unit != false) ? $get_hog['JABATAN'] : "HOG User";

		if($get_hog){
			$send_email = true;
			$email_to= $get_hog['PIC_EMAIL'];
			$email_name  = $get_hog['PIC_NAME'];
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => $jabatan_hog, "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "FPJP_HEADER_ID" => $id_fpjp);
		}

		$directorat_name = strtolower(get_directorat($directorat));

		if($total_amount > 200000000):
			if($directorat_name == "marketing"){
				$chief = "CMO";
			}elseif($directorat_name == "operation"){
				$chief = "COO";
			}elseif($directorat_name == "technology"){
				$chief = "CTO";
			}elseif($directorat_name == "finance"){
				$chief = "CFO";
			}
			else{
				$chief = "CEO";
			}

			if($total_amount <= 5000000000){

				if($directorat_name == "ceo office"){

					$get_ceo = $this->feasibility_study->get_data_approval("CEO");
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_ceo['ID_APPROVAL'], "FPJP_HEADER_ID" => $id_fpjp);
				}
				else{

					$get_dir = $this->feasibility_study->get_data_approval($chief, $directorat);
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_dir['ID_APPROVAL'], "FPJP_HEADER_ID" => $id_fpjp);
				}
			}else{

				if($directorat_name != "ceo office"){
					$get_dir = $this->feasibility_study->get_data_approval($chief, $directorat);
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_dir['ID_APPROVAL'], "FPJP_HEADER_ID" => $id_fpjp);

				}

				$get_ceo = $this->feasibility_study->get_data_approval("CEO");
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_ceo['ID_APPROVAL'], "FPJP_HEADER_ID" => $id_fpjp);
			}
		endif;

		$result['data_approval'] = $data_approval;
		$result['email_to']      = $email_to;
		$result['email_name']    = $email_name;

		return $result;

	}

}

/* End of file Fpjp_ctl.php */
/* Location: ./application/controllers/fpjp_new/Fpjp_ctl.php */
