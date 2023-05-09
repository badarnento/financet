<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PR_ctl extends CI_Controller {

	private $module_url   = "purchase-requisition";

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');
		
	}

	public function index()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("purchase-requisition", $this->session->userdata['menu_url']) ){

			$data['title']         = "Purchase Requisition";
			$data['module']        = "datatable";
			$data['template_page'] = "pr_po/pr_inquiry";
			$data['pr_status']     = get_status_pr();
			
			$group_name = get_user_group_data();

			$data['group_name']    = $group_name;
			
		    $directorat = check_is_bod();
		    $binding    = check_binding();

		    if(count($directorat) > 1 && $binding['binding'] != false){
				$directorat = $binding['data_binding']['directorat'];
		    }

			$data['su_budget']         = (in_array("SU Budget", $group_name)) ? true : false;
			$data['allow_create']      = (in_array("PR Create", $group_name)) ? true : false;
			$data['force_create_form'] = (in_array("FORCE CREATE FORM", $group_name)) ? true : false;
			$data['directorat']        = $directorat;
			$data['binding']           = $binding['binding'];
			$data['data_binding']      = $binding['data_binding'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}


	public function load_pr_header(){

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

		if($this->input->post('pr_date')){
			$exp_pr_date = explode(" - ", $this->input->post('pr_date'));

			$date_from = date_db($exp_pr_date[0]);
			$date_to   = date_db($exp_pr_date[1]);

		}

		$get_all         = $this->purchase->get_purchase_header($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status = ($value['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($value['STATUS']);

				$status_assign = "-";
				if($value['STATUS_ASSIGN'] && ($value['STATUS'] == "approved" || $value['STATUS'] == "po created") ){

					$buyer_name = ($value['PO_BUYER']) ? $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $value['PO_BUYER'])) : '';
					$status_assign = ($value['STATUS_ASSIGN'] == "N") ? "Waiting for assign" : $buyer_name['PIC_NAME'];
				}


				$row[] = array(
						'no'            => $number,
						'directorat'    => get_directorat($value['ID_DIR_CODE']),
						'pr_header_id'  => encrypt_string($value['PR_HEADER_ID'], true),
						'id'            => $value['PR_HEADER_ID'],
						'pr_number'     => $value['PR_NUMBER'],
						'pr_name'       => $value['PR_NAME'],
						'status'        => $status,
						'status_assign' => $status_assign,
						'pr_date'       => dateFormat($value['PR_DATE'], 5, false),
						'currency'      => strtoupper($value['CURRENCY']),
						'total_amount'  => number_format($value['PR_AMOUNT'],0,',','.')
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

	public function view_pr($id_pr_enc){
		
		$group_name = get_user_group_data();

		if($this->ion_auth->is_admin() == true || in_array("purchase-requisition", $this->session->userdata['menu_url']) || in_array("PR View Only", $group_name) ){

			$decrypt = decrypt_string($id_pr_enc, true);
			$id_pr   = (int) $decrypt;

			$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

			if($check_exist > 0){

				$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

				$data['title']          = "PR ".$get_pr_header['PR_NUMBER'];
				$data['module']         = "datatable";
				$data['template_page']  = "pr_po/pr_view";
				
				$data['id_pr']          = $id_pr;
				$data['id_pr_enc']      = $id_pr_enc;
				$id_fs             = $get_pr_header['ID_FS'];
				$data['id_fs']     = $id_fs;
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
				$data['pr_date']        = dateFormat($get_pr_header['PR_DATE'], 5, false);
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
				$last_update            = ($get_pr_header['UPDATED_DATE']) ? $get_pr_header['UPDATED_DATE'] : $get_pr_header['CREATED_DATE'];


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

				$pr_document_return = false;
				if($get_pr_header['DOCUMENT_RETURN'] && $get_pr_header['STATUS'] == "returned"):
					$pr_document_return = base_url("download/") . encrypt_string("uploads/po_attachment/".$get_pr_header['DOCUMENT_RETURN'], true);
				endif;
				$data['pr_document_return'] = $pr_document_return;


				$doc_checklist =json_decode($get_pr_header['DOCUMENT_CHECKLIST']);
				$arr_check_doc = array();
				if($doc_checklist){
					foreach ($doc_checklist as $key => $value) {
						$arr_check_doc[] = $key;
					}
				}
				$data['doc_checklist'] = $arr_check_doc;

				$get_approval = $this->purchase->get_approval_by_pr($id_pr);

				$approval = array();
				$approval_remark = array();

				foreach ($get_approval as $key => $value) {
					$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['JABATAN']);
					if(!empty($value['REMARK'])){
						$approval_remark[] = $value;
					}
				}
				$data['pr_approval'] = $approval;

				if(count($approval_remark) > 0){
					foreach ($approval_remark as $key => $v)
					{
				    	$sort[$key] = strtotime($v['UPDATED_DATE']);
					}
					array_multisort($sort, SORT_DESC, $approval_remark);

					$data['pr_approval_remark'] = $approval_remark[0]['REMARK'];
				}

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
				$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => base_url("purchase-requisition"), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'PR Not Exist');
				redirect($this->module_url);

			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

	public function create_pr()
	{

		$group_name = get_user_group_data();
		if(!in_array("FORCE CREATE FORM", $group_name)){
			$this->session->set_flashdata('messages', 'Submission PR Telah Ditutup');
			redirect($this->module_url);
			exit;
		}

		$data['title']         = "Create New PR";
		$data['module']        = "datatable";
		$data['template_page'] = "pr_po/pr_create";

		$directorat = check_is_bod();
		$binding    = check_binding();

	    if(count($directorat) > 1 && $binding['binding'] != false){
			$directorat = $binding['data_binding']['directorat'];
	    }

		$data['directorat']   = $directorat;
		$data['binding']      = $binding['binding'];
		$data['data_binding'] = $binding['data_binding'];
		$data['get_location']  = get_location();

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => base_url('purchase-requisition'), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}

	public function edit_pr($id_pr){
		
		if($this->ion_auth->is_admin() == true || in_array("purchase-requisition", $this->session->userdata['menu_url']) ){

			$decrypt      = decrypt_string($id_pr, true);
			$id_pr = (int) $decrypt;

			$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

			if($check_exist > 0){

				$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

				$data['title']          = "PR ".$get_pr_header['PR_NUMBER'];
				$data['module']         = "datatable";
				$data['template_page']  = ($get_pr_header['PR_VERSION'] == 1) ? "pr_po/pr_edit" : "pr_po/pr_edit_v2";

				if(strtolower($get_pr_header['STATUS']) != "returned"){
					// redirect("purchase-requisition");
				}
				$data['id_pr']          = $id_pr;
				$data['id_fs']          = $get_pr_header['ID_FS'];
				$data['pr_number']      = $get_pr_header['PR_NUMBER'];
				$data['pr_name']        = $get_pr_header['PR_NAME'];
				$data['pr_date']        = dateFormat($get_pr_header['PR_DATE'], 5, false);
				$data['pr_amount']      = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
				$data['pr_currency']    = $get_pr_header['CURRENCY'];
				$data['pr_rate']        = number_format($get_pr_header['CURRENCY_RATE'],0,',','.');
				$data['pr_directorat']  = $get_pr_header['ID_DIR_CODE'];
				$data['pr_division']    = $get_pr_header['ID_DIVISION'];
				$data['pr_unit']        = $get_pr_header['ID_UNIT'];
				$data['pr_submitter']   = $get_pr_header['SUBMITTER'];
				$data['pr_jabatan_sub'] = $get_pr_header['JABATAN_SUBMITTER'];
				$data['pr_attachment']  = $get_pr_header['DOCUMENT_UPLOAD'];
				$data['pr_status']      = ($get_pr_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_pr_header['STATUS']);
				$data['pr_status_desc'] = $get_pr_header['STATUS_DESCRIPTION'];

				$pr_history = get_pr_history($id_pr, $get_pr_header);
				$data['pr_history'] = $pr_history;
				$last_update = end($pr_history);
				$data['pr_last_update'] = ucfirst($last_update['STATUS']) . " by " . $last_update['PIC_NAME'] . " at " . $last_update['ACTION_DATE'];

				$get_approval = $this->purchase->get_approval_by_pr($id_pr);

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

				$doc_checklist =json_decode($get_pr_header['DOCUMENT_CHECKLIST']);
				$arr_check_doc = array();
				if($doc_checklist){
					foreach ($doc_checklist as $key => $value) {
						$arr_check_doc[] = $key;
					}
				}
				$data['doc_checklist'] = $arr_check_doc;


				// echo_pre($data);die;

				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => base_url('purchase-requisition'), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'PR Not Exist');
				redirect('purchase-requisition');
				
			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_pr_detail_for_edit(){

		$pr_lines_id = $this->input->post('pr_lines_id');
		$get_all = $this->purchase->get_pr_detail_for_edit($pr_lines_id);

		if($get_all > 0){

			foreach($get_all as $value) {

				$number      = $value['PR_DETAIL_NUMBER'];
				$price_val   = number_format($value['PRICE'],0,',','.');
				$nominal_val = number_format($value['PR_DETAIL_AMOUNT'],0,',','.');

				$rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'.$number.'" class="form-control input-sm rkap_desc" value="'.$value['PR_DETAIL_DESC'].'"></div>';
				$rkap_item_val  = '<div class="form-group m-b-0"><input id="rkap_item-'.$number.'" class="form-control input-sm rkap_item" value="'.$value['PR_DETAIL_NAME'].'"></div>';
				$category_item_opt     = '<div class="form-group m-b-0"><select id="category_item_opt-'.$number.'" class="form-control input-sm category_item_opt select-center"><option value="'.$value['CATEGORY_ITEM'].'">'.$value['CATEGORY_ITEM'].'</option></select></div>';
				$quantity       = '<div class="form-group m-b-0"><input id="quantity-'.$number.'" data-id="'.$number.'" class="form-control input-sm quantity text-center" value="'.$value['QUANTITY'].'" min="1" max="99999" type="number"></div>';
				$uom_opt     = '<div class="form-group m-b-0"><select id="uom_opt-'.$number.'" class="form-control input-sm uom_opt select-center"><option value="'.$value['UOM'].'">'.$value['UOM'].'</option></select></div>';
				$price          = '<div class="form-group m-b-0"><input id="price-'.$number.'" data-id="'.$number.'" class="form-control input-sm price text-right money-format" value="'.$price_val.'"></div>';
				$nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'.$number.'" class="form-control input-sm nominal_detail text-right" value="'.$nominal_val.'" readonly></div>';

				$row[] = array(
							'pr_detail_id'      => $value['PR_DETAIL_ID'],
							'pr_lines_id'       => $value['PR_LINES_ID'],
							'no'                => $number,
							'rkap_item'         => $value['PR_DETAIL_NAME'],
							'rkap_desc'         => $value['PR_DETAIL_DESC'],
							'category_item'     => $value['CATEGORY_ITEM'],
							'category_item_opt' => $category_item_opt,
							'quantity'          => $value['QUANTITY'],
							'type'              => ($value['GOODS_SERVICES']) ? $value['GOODS_SERVICES'] : '',
							'uom'               => $value['UOM'],
							'uom_opt'           => $uom_opt,
							'price'             => $value['PRICE'],
							'nominal_detail'    => $value['PR_DETAIL_AMOUNT']
						);
			}

		}

		echo json_encode($row);

	}


	private function _removeDup($array){
	    $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

		foreach ($result as $key => $value){
			if ( is_array($value) ){
			  $result[$key] = $this->_removeDup($value);
			}
		}

		return $result;
	}

	public function load_data_fs(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_fs = $this->input->post('id_fs');
		
		$get_all = $this->feasibility_study->get_fs_lines($id_fs, "pr");
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			$fa_fs = 0;
			$fs_lines_amount = 0;
			$x=1;

			foreach($data as $value) {

				$id_rkap[] =  $value['ID_RKAP_LINE'];
				$fa_fs   += $value['FA_FS'];
				$rkap_data[] = [ 'id_rkap' => $value['ID_RKAP_LINE'], 'fund_av' => $value['FA_FS'] ] ;

				if($total == $number){
				
					$fa_fs = number_format($fa_fs,0,',','.');
					$line_name = '<div class="form-group m-b-0"><input id="line_name-'. $x .'" data-id="'. $x .'" class="form-control input-sm line_name" autocomplete="off"></div>';
					$fund_av   = '<div class="form-group m-b-0"><input id="fund_av-'. $x .'" data-id="'. $x .'" class="form-control input-sm fund_av text-right" autocomplete="off" value="'.$fa_fs.'" readonly></div>';
					$nominal   = '<div class="form-group m-b-0"><input id="nominal-'. $x .'" data-id="'. $x .'" class="form-control input-sm nominal text-right" autocomplete="off" value="0" readonly></div>';
					$rkap_data = $this->_removeDup($rkap_data);

					$row[] = array(
								'pr_line_key'  => "k".strtolower(generateRandomString(5)),
								'id_rkap_line' => $value['ID_RKAP_LINE'],
								'id_rkap'      => $rkap_data,
								'fs_name'      => $value['FS_LINES_NAME'],
								'line_name'    => $line_name,
								'fund_av'      => $fund_av,
								'nominal'      => $nominal,
								);

				}
				$number++;
			}


			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = 1;
			$result['recordsFiltered'] = 1;

		}

		echo json_encode($result);

	}


	public function load_data_lines(){

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_header_id = $this->input->post('pr_header_id');
		
		$get_all = $this->purchase->get_purchase_lines($pr_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

			$line_name_edit = '<div class="form-group m-b-0"><input id="line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm line_name" value="'.$value['PR_LINE_NAME'].'" ></div>';	
			$fund_av_edit   = '<div class="form-group m-b-0"><input id="fund_av-'.$number.'" data-id="'.$number.'" class="form-control input-sm fund_av text-right" value="'.number_format($value['FA_FS']+$value['PR_LINE_AMOUNT'],0,',','.').'" readonly></div>';
			$nominal_edit   = '<div class="form-group m-b-0"><input id="nominal-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal text-right" value="'.number_format($value['PR_LINE_AMOUNT'],0,',','.').'" readonly></div>';

				$row[] = array(
						'pr_line_key'    => "k".strtolower(generateRandomString(5)),
						'pr_lines_id'    => $value['PR_LINES_ID'],
						'id_rkap_line'   => $value['ID_RKAP_LINE'],
						'no'             => $value['PR_LINES_NUMBER'],
						'directorat'     => get_directorat($value['ID_DIR_CODE']),
						'tribe'          => $value['TRIBE_USECASE'],
						'rkap_name'      => $value['RKAP_DESCRIPTION']." &ndash; ".date("M-y", strtotime($value['MONTH'])),
						'line_name'      => $value['PR_LINE_NAME'],
						'is_show'        => $value['IS_SHOW'],
						'fund_available' => number_format($value['FA_FS'],0,',','.'),
						'nominal'        => number_format($value['PR_LINE_AMOUNT'],0,',','.'),
						'nominal_edit'   => $nominal_edit,
						'fund_av_edit'   => $fund_av_edit,
						'line_name_edit' => $line_name_edit
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


	public function load_data_lines_edit(){

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_header_id = $this->input->post('pr_header_id');
		
		$get_all = $this->purchase->get_purchase_lines($pr_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			$total_fa_fs =0;
			$total_nominal =0;
			$is_show=0;

		/*	$get_fa_rkap = $this->purchase->get_fa_fs_by_pr_id($pr_header_id);

			foreach($get_fa_rkap as $value) {
				$total_fa_fs   += $value['FA_FS'];
				$rkap_data[] = [ 'id_rkap' => $value['ID_RKAP_LINE'], 'fund_av' => $value['FA_FS'] ] ;
			}*/

			foreach($data as $value) {
				$total_fa_fs   += $value['PR_LINE_AMOUNT'];
				$total_fa_fs   += $value['FA_FS'];
				$total_nominal += $value['PR_LINE_AMOUNT'];
				$rkap_data[] = [ 'id_rkap' => $value['ID_RKAP_LINE'], 'fund_av' => $value['FA_FS']+$value['PR_LINE_AMOUNT'] ] ;

				if($value['IS_SHOW'] == 1){
					$pr_lines_id[] = $value['PR_LINES_ID'];
					$is_show++;
				}
			}

			foreach($data as $value) {

				if( in_array($value['PR_LINES_ID'], $pr_lines_id) ){

					if($is_show > 1){
						$total_fa_fs   = $value['FA_FS'];
						$total_fa_fs   += $value['PR_LINE_AMOUNT'];
						$total_nominal = $value['PR_LINE_AMOUNT'];
					}

					$line_name = '<div class="form-group m-b-0"><input id="line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm line_name" value="'.$value['PR_LINE_NAME'].'" ></div>';	
					$fund_av   = '<div class="form-group m-b-0"><input id="fund_av-'.$number.'" data-id="'.$number.'" class="form-control input-sm fund_av text-right" value="'.number_format($total_fa_fs,0,',','.').'" readonly></div>';
					$nominal   = '<div class="form-group m-b-0"><input id="nominal-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal text-right" value="'.number_format($total_nominal,0,',','.').'" readonly></div>';
				
					$fa_fs = number_format($total_fa_fs,0,',','.');
					$row[] = array(
								'pr_line_key'  => "k".strtolower(generateRandomString(5)),
								'id_rkap_line' => $value['ID_RKAP_LINE'],
								'pr_lines_id'  => $value['PR_LINES_ID'],
								'id_rkap'      => $rkap_data,
								'no'           => $number,
								'line_name'    => $line_name,
								'fund_av'      => $fund_av,
								'nominal'      => $nominal,
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


	public function load_data_lines_view(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_header_id = $this->input->post('pr_header_id');
		
		$get_all = $this->purchase->get_purchase_lines($pr_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			$total_fa_fs = 0;
			$total_nominal = 0;
			$is_show = 0;

			foreach($data as $value) {
				$total_fa_fs   += $value['FA_FS'];
				$total_nominal += $value['PR_LINE_AMOUNT'];
				if($value['IS_SHOW'] == 1){
					$pr_lines_id[] = $value['PR_LINES_ID'];
					$is_show++;
				}
			}

			foreach($data as $value) {

				if( in_array($value['PR_LINES_ID'], $pr_lines_id) ){

					if($is_show > 1){
						$total_fa_fs = $value['FA_FS'];
						$total_nominal = $value['PR_LINE_AMOUNT'];
					}

					$row[] = array(
								'pr_line_key' => "k".strtolower(generateRandomString(5)),
								'pr_lines_id' => $value['PR_LINES_ID'],
								'no'          => $number,
								'line_name'   => $value['PR_LINE_NAME'],
								'is_show'     => $value['IS_SHOW'],
								'fund_av'     => number_format($total_fa_fs,0,',','.'),
								'nominal'     => number_format($total_nominal,0,',','.')
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

		$pr_lines_id = $this->input->post('pr_lines_id');
		$category    = $this->input->post('category');
		
		$get_all = $this->purchase->get_purchase_details($pr_lines_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$number = $value['PR_DETAIL_NUMBER'];

				$po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc"></div>';
				
				$nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="0"></div>';

				$row[] = array(
						'pr_lines_id'       => $value['PR_LINES_ID'],
						'pr_detail_id'      => $value['PR_DETAIL_ID'],
						'no'                => $number,
						'item_desc'         => $value['PR_DETAIL_NAME'],
						'detail_desc'       => $value['PR_DETAIL_DESC'],
						'category_item'     => $value['CATEGORY_ITEM'],
						'nature'            => $value['NATURE']." - ".$value['DESCRIPTION'],
						'quantity'          => $value['QUANTITY'],
						'goods_services'    => ($value['GOODS_SERVICES']) ? ucfirst($value['GOODS_SERVICES']) : '-',
						'uom'               => $value['UOM'],
						'price'             => number_format($value['PRICE'],0,',','.'),
						'nominal'           => number_format($value['PR_DETAIL_AMOUNT'],0,',','.'),
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


	public function save_pr(){
		
		$directorat        = $this->input->post('directorat');
		$division          = $this->input->post('division');
		$unit              = $this->input->post('unit');
		$pr_name           = $this->input->post('pr_name');
		$pr_date           = $this->input->post('pr_date');
		$amount            = $this->input->post('amount');
		$currency          = $this->input->post('currency');
		$rate              = $this->input->post('rate');
		$submitter         = $this->input->post('submitter');
		$jabatan_sub       = $this->input->post('jabatan_sub');
		$data_line         = $this->input->post('data_line');
		$id_fs             = $this->input->post('id_fs');
		$attachment        = $this->input->post('attachment');
		$doc_list          = $this->input->post('doc_list');
		$delivery_date     = $this->input->post('delivery_date');
		$delivery_location = $this->input->post('delivery_location');

		if($pr_date != ""){
			$exp_pr_date = explode("-", $pr_date);
			$pr_date = $exp_pr_date[2]."-".$exp_pr_date[1]."-".$exp_pr_date[0];
		}

		if($delivery_date != ""){
			$exp_delivery_date = explode("-", $delivery_date);
			$delivery_date = $exp_delivery_date[2]."-".$exp_delivery_date[1]."-".$exp_delivery_date[0];
		}

		$get_dir        = $this->crud->read_by_param("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $directorat));
		$id_dir_code    = $get_dir['ID_DIR_CODE'];

		$check_pr_exist = $this->crud->check_exist("PR_HEADER", array("ID_DIR_CODE" => $id_dir_code));

		$month     = date("m");
		$year      = date("Y");
		$number    = sprintf("%'03d", 1);
		$pr_number = $get_dir['DIRECTORAT_CODE']."/".$number."/".date("m")."/".date("Y");

		if($check_pr_exist > 0){
			
			$last_pr_number = $this->purchase->get_last_pr_number($id_dir_code);
			$exp_pr_number  = explode("/",$last_pr_number);
			if(substr($last_pr_number, 0, 2) == "PR"){
			    $dir_code = $exp_pr_number[1];
			    $number   = (int) $exp_pr_number[2];
			}else{
			    $dir_code = $exp_pr_number[0];
			    $number   = (int) $exp_pr_number[1];
			}
			$number += 1;
			$number = sprintf("%'03d", $number);
			$pr_number = "PR/".$dir_code."/".$number."/".$month."/".$year;

		}
		
		$amount        = str_replace(",", "", $amount);
		$auto_reject   = get_auto_reject_date();

		$data = array(
						"ID_DIR_CODE"        => $id_dir_code,
						"ID_DIVISION"        => $division,
						"ID_UNIT"            => $unit,
						"ID_FS"              => $id_fs,
						"PR_NUMBER"          => $pr_number,
						"PR_NAME"            => $pr_name,
						"CURRENCY"           => $currency,
						"CURRENCY_RATE"      => $rate,
						"SUBMITTER"          => $submitter,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"AUTO_REJECT_DATE"   => $auto_reject,
						"DOCUMENT_UPLOAD"    => $attachment,
						"STATUS_VERIF"       => 'N',
						"COA_REVIEW"         => NULL,
						"INTERFACE_STATUS"   => 'NEW',
						// "STATUS_ASSIGN"   => 'N',
						"DELIVERY_LOCATION"  => $delivery_location,
						"PR_VERSION"         => 2,
						"APPROVAL_LEVEL"     => 1,
						"STATUS_DESCRIPTION" => "Submitted by ".$submitter,
						"PR_AMOUNT"          => (int) $amount
					);

		if($pr_date != ""){
			$data['PR_DATE'] = $pr_date;
		}

		if($delivery_date != ""){
			$data['DELIVERY_DATE'] = $delivery_date;
		}

		if($doc_list != ""){
			$doc_lists = array();
			foreach ($doc_list as $value) {
				$val = strtolower(str_replace(" ", "_", $value));
				$doc_lists[$val] = true;
			}
			$data['DOCUMENT_CHECKLIST'] = (is_array($doc_lists)) ? json_encode($doc_lists) : '';
		}


		$group_name = get_user_group_data();

		if(in_array("PR Direct Approve", $group_name) ){
			$direct_approve = true;
		}else{
			$direct_approve = false;
			$data['STATUS'] = 'request_approve';
		}

		$insert   = $this->crud->create("PR_HEADER", $data);
		// $insert   = 1;

		$send_email    = false;
		$level         = 1;
		$data_approval = array();

		$division_name = strtolower(get_division($division));
		$get_by_unit   = ($division_name == "new business") ? $unit : false;

		$get_hog        = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);

		// $get_proc_supp = $this->feasibility_study->get_data_approval("Procurement");
		// $get_hou_proc  = $this->feasibility_study->get_data_approval("HOU Procurement");


		$get_proc_supp = $this->feasibility_study->get_data_approval("Procurement");
		$recipient = array();
		$send_email = true;
		foreach ($get_proc_supp as $key => $value) {
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "Procurement", "ID_APPROVAL" => $value['ID_APPROVAL'], "PR_HEADER_ID" => $insert);
			$recipient[]  = array('id_approval' => $value['ID_APPROVAL'], 'name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
		}

		$get_hou_proc_supp = $this->feasibility_study->get_data_approval("HOU Proc Support");
		if($get_hou_proc_supp){
			$recipient[]  = array('id_approval' => $get_hou_proc_supp['ID_APPROVAL'], 'name' => $get_hou_proc_supp['PIC_NAME'], 'email' => $get_hou_proc_supp['PIC_EMAIL']);
		}

		if($get_hog){
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG User", "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "PR_HEADER_ID" => $insert);
		}
		if($direct_approve == false){
			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_PR", $data_approval);
			$id_approval     = $this->db->insert_id();
		}else{
			$send_email    = false;
		}
		$status   = false;
		$messages = "";

		if($insert > 0){

			$pr_line_number = 1;
			// $this->budget->update_fs_status($id_fs, "fs used", "PR");

			$data_lines = array();

			foreach ($data_line as $key => $value) {
				$rkap        = $value['id_rkap'];
				$line_name   = $value['line_name'];
				$detail_data = $value['detail_data'];
			}

			foreach ($rkap as $key => $value) {
				if($value['fund_av'] > 0){
					$rkap_data[] = $value;
				}
			}
			// echo_pre($rkap_data);

	        $nominal = $amount;
		    $pr_line_number=1;
			$get_nature = get_nature();

			for ( $i = 0; $i < count($rkap_data); $i++){

				$id_rkap = $rkap_data[$i]['id_rkap'];
				$is_show = ($i == 0) ? 1 : 0;
			    
			    if($rkap_data[$i]['fund_av'] <= $nominal){

			        $nominal -= $rkap_data[$i]['fund_av'];
			        $data_lines[] = array(
										"PR_HEADER_ID"    => $insert,
										"PR_LINES_NUMBER" => $pr_line_number,
										"PR_LINE_NAME"    => $line_name,
										"ID_RKAP_LINE"    => $id_rkap,
										"ID_FS"           => $id_fs,
										"IS_SHOW"         => $is_show,
										"PR_LINE_AMOUNT"  => $rkap_data[$i]['fund_av']
									);

			        $rkap_data[$i]['fund_av'] = 0;
			    }else{
			        $rkap_data[$i]['fund_av'] -= $nominal;

			        $data_lines[] = array(
										"PR_HEADER_ID"    => $insert,
										"PR_LINES_NUMBER" => $pr_line_number,
										"PR_LINE_NAME"    => $line_name,
										"ID_RKAP_LINE"    => $id_rkap,
										"ID_FS"           => $id_fs,
										"IS_SHOW"         => $is_show,
										"PR_LINE_AMOUNT"  => $nominal
									);
			        break;
			    }
			    $pr_line_number++;
			}
			// echo_pre($rkap_data);die;
			$insert_lines = $this->crud->create_batch("PR_LINES", $data_lines);
			$id_lines = $this->db->insert_id();

			if ($insert_lines){

				$pr_detail_number=1;

				$natureArr = array();

				foreach ($get_nature as $key => $value) {
					$nat = $value['NATURE'];
					$natureArr[$nat] = $value['ID_MASTER_COA'];
				}

				foreach ($detail_data as $key => $value_dtl) {

					$id_master_coa = $value_dtl['nature'];
					if($value_dtl['category_item_nature'] !=""){
						$natX = $value_dtl['category_item_nature'];
						$id_master_coa = $natureArr[$natX];
					}

					$data_details[] = array(
										"PR_HEADER_ID"     => $insert,
										"PR_LINES_ID"      => $id_lines,
										"PR_DETAIL_NUMBER" => $pr_detail_number,
										"PR_DETAIL_DESC"   => $value_dtl['rkap_desc'],
										"PR_DETAIL_NAME"   => $value_dtl['rkap_item'],
										"QUANTITY"         => $value_dtl['quantity'],
										"PRICE"            => $value_dtl['price'],
										"CATEGORY_ITEM"    => $value_dtl['category_item'],
										"GOODS_SERVICES"   => $value_dtl['type'],
										"UOM"              => $value_dtl['uom'],
										"PR_DETAIL_AMOUNT" => $value_dtl['nominal'],
										"CREATED_BY"       => get_user_data($this->session->userdata('user_id'))
									);
					$pr_detail_number++;
				}

				$insert_detail = $this->crud->create_batch("PR_DETAIL", $data_details);

				if($insert_detail){
					if($send_email){
						$this->_email_aprove($recipient, $insert);
					}
					$status    = true;
				}else{
					$messages = "Failed to Create PR Detail";
				}

			}

		}
		else{
			$messages = "Failed to Create PR";
		}


		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

	public function save_pr_edit(){
		
		$pr_header_id = $this->input->post('pr_header_id');
		$directorat   = $this->input->post('directorat');
		$division     = $this->input->post('division');
		$pr_name      = $this->input->post('pr_name');
		$pr_date      = $this->input->post('pr_date');
		$amount       = $this->input->post('amount');
		$submitter    = $this->input->post('submitter');
		$jabatan_sub  = $this->input->post('jabatan_sub');
		$data_line    = $this->input->post('data_line');

		$attachment  = $this->input->post('attachment');
		$doc_list    = $this->input->post('doc_list');

		if($pr_date != ""){
			$exp_pr_date = explode("-", $pr_date);
			$pr_date = $exp_pr_date[2]."-".$exp_pr_date[1]."-".$exp_pr_date[0];
		}

		$amount = str_replace(".", "", $amount);
		$auto_reject   = get_auto_reject_date();

		$userdata = get_user_data($this->session->userdata('user_id'));

		$data = array(
						"PR_NAME"            => $pr_name,
						"PR_DATE"            => $pr_date,
						"PR_AMOUNT"          => (int) $amount,
						"SUBMITTER"          => $submitter,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"AUTO_REJECT_DATE"   => $auto_reject,
						"DOCUMENT_UPLOAD"    => $attachment,
						"STATUS"             => 'request_approve',
						"RESUBMIT_BY"        => $userdata,
						"RESUBMIT_DATE"      => date("Y-m-d H:i:s", time()),
						"STATUS_DESCRIPTION" => "Resubmitted by ".$submitter
					);

		if($doc_list != ""){
			$doc_lists = array();
			foreach ($doc_list as $value) {
				$val = strtolower(str_replace(" ", "_", $value));
				$doc_lists[$val] = true;
			}
			$data['DOCUMENT_CHECKLIST'] = (is_array($doc_lists)) ? json_encode($doc_lists) : '';
		}
		$update   = $this->crud->update("PR_HEADER", $data, array("PR_HEADER_ID" => $pr_header_id));

		$send_email    = false;
		$level         = 1;
		$data_approval = array();

		$division_name = strtolower(get_division($division));
		$get_by_unit   = ($division_name == "new business") ? $unit : false;

		$get_hog       = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);
		// $get_proc_supp = $this->feasibility_study->get_data_approval("Procurement");



		$get_proc_supp = $this->feasibility_study->get_data_approval("Procurement");
		$recipient = array();
		$send_email = true;
		foreach ($get_proc_supp as $key => $value) {
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "Procurement", "ID_APPROVAL" => $value['ID_APPROVAL'], "PR_HEADER_ID" => $pr_header_id);
			$recipient[]  = array('id_approval' => $value['ID_APPROVAL'], 'name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
		}

		$get_hou_proc_supp = $this->feasibility_study->get_data_approval("HOU Proc Support");
		if($get_hou_proc_supp){
			$recipient[]  = array('id_approval' => $get_hou_proc_supp['ID_APPROVAL'], 'name' => $get_hou_proc_supp['PIC_NAME'], 'email' => $get_hou_proc_supp['PIC_EMAIL']);
		}

		if($get_hog){
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG User", "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "PR_HEADER_ID" => $pr_header_id);
		}

		// $this->crud->update("TRX_APPROVAL_PR", array("PR_HEADER_ID" => $pr_header_id));
		$this->crud->update("TRX_APPROVAL_PR", array("IS_ACTIVE" => 0), array("PR_HEADER_ID" => $pr_header_id));
		$insert_approval = $this->crud->create_batch("TRX_APPROVAL_PR", $data_approval);
		$id_approval     = $this->db->insert_id();

		$status   = false;
		$messages = "";

		if($update !== -1){

			foreach ($data_line as $key => $value) {

				$detail_data = $value['detail_data'];
				$data_lines = array(
										"PR_LINE_AMOUNT"  => $value['nominal'],
										"UPDATED_BY"      => get_user_data($this->session->userdata('user_id'))
									);
				$update_lines = $this->crud->update("PR_LINES", $data_lines, array("PR_LINES_ID" => $value['pr_lines_id']));

				if($update_lines !== -1){

					$delete_detail = $this->crud->delete("PR_DETAIL", array("PR_LINES_ID" => $value['pr_lines_id']));

					$pr_detail_number = 1;

					foreach ($detail_data as $key => $value_dtl) {

						$data_details[] = array(
											"PR_HEADER_ID"     => $pr_header_id,
											"PR_LINES_ID"      => $value['pr_lines_id'],
											"PR_DETAIL_NUMBER" => $pr_detail_number,
											"PR_DETAIL_DESC"   => $value_dtl['rkap_desc'],
											"PR_DETAIL_NAME"   => $value_dtl['rkap_item'],
											"CATEGORY_ITEM"    => $value_dtl['category_item'],
											"ID_MASTER_COA"    => $value_dtl['nature'],
											"QUANTITY"         => $value_dtl['quantity'],
											"GOODS_SERVICES"   => $value_dtl['type'],
											"UOM"         	   => $value_dtl['uom'],
											"PRICE"            => $value_dtl['price'],
											"PR_DETAIL_AMOUNT" => $value_dtl['nominal_detail'],
											"CREATED_BY"       => get_user_data($this->session->userdata('user_id'))
										);
						$pr_detail_number++;
					}

				}

			}

	
			$insert_detail = $this->crud->create_batch("PR_DETAIL", $data_details);

			if($insert_detail){
				if($send_email){
					$this->_email_aprove($recipient, $pr_header_id, $id_approval);
				}
				$status    = true;
			}else{
				$messages = "Failed to Create PR Detail";
			}

		}
		else{
			$messages = "Failed to Create PR";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }


	public function save_pr_edit_v2(){
		
		$pr_header_id = $this->input->post('pr_header_id');
		$directorat   = $this->input->post('directorat');
		$division     = $this->input->post('division');
		$pr_name      = $this->input->post('pr_name');
		$pr_date      = $this->input->post('pr_date');
		$amount       = $this->input->post('amount');
		$submitter    = $this->input->post('submitter');
		$jabatan_sub  = $this->input->post('jabatan_sub');
		$data_line    = $this->input->post('data_line');

		$attachment  = $this->input->post('attachment');
		$doc_list    = $this->input->post('doc_list');

		if($pr_date != ""){
			$exp_pr_date = explode("-", $pr_date);
			$pr_date = $exp_pr_date[2]."-".$exp_pr_date[1]."-".$exp_pr_date[0];
		}

		$amount = str_replace(".", "", $amount);
		$auto_reject   = get_auto_reject_date();

		$userdata = get_user_data($this->session->userdata('user_id'));

		$data = array(
						"PR_NAME"            => $pr_name,
						"PR_DATE"            => $pr_date,
						"PR_AMOUNT"          => (int) $amount,
						"SUBMITTER"          => $submitter,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"AUTO_REJECT_DATE"   => $auto_reject,
						"DOCUMENT_UPLOAD"    => $attachment,
						"STATUS"             => 'request_approve',
						"RESUBMIT_BY"        => $userdata,
						"RESUBMIT_DATE"      => date("Y-m-d H:i:s", time()),
						"STATUS_DESCRIPTION" => "Resubmitted by ".$submitter
					);

		if($doc_list != ""){
			$doc_lists = array();
			foreach ($doc_list as $value) {
				$val = strtolower(str_replace(" ", "_", $value));
				$doc_lists[$val] = true;
			}
			$data['DOCUMENT_CHECKLIST'] = (is_array($doc_lists)) ? json_encode($doc_lists) : '';
		}
		$update   = $this->crud->update("PR_HEADER", $data, array("PR_HEADER_ID" => $pr_header_id));

		$send_email    = false;
		$level         = 1;
		$data_approval = array();

		$division_name = strtolower(get_division($division));
		$get_by_unit   = ($division_name == "new business") ? $unit : false;

		$get_hog       = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);
		// $get_proc_supp = $this->feasibility_study->get_data_approval("Procurement");

		$get_proc_supp = $this->feasibility_study->get_data_approval("Procurement");
		$recipient = array();
		$send_email = true;
		foreach ($get_proc_supp as $key => $value) {
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "Procurement", "ID_APPROVAL" => $value['ID_APPROVAL'], "PR_HEADER_ID" => $pr_header_id);
			$recipient[]  = array('id_approval' => $value['ID_APPROVAL'], 'name' => $value['PIC_NAME'], 'email' => $value['PIC_EMAIL']);
		}

		$get_hou_proc_supp = $this->feasibility_study->get_data_approval("HOU Proc Support");
		if($get_hou_proc_supp){
			$recipient[]  = array('id_approval' => $get_hou_proc_supp['ID_APPROVAL'], 'name' => $get_hou_proc_supp['PIC_NAME'], 'email' => $get_hou_proc_supp['PIC_EMAIL']);
		}
		if($get_hog){
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HOG User", "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "PR_HEADER_ID" => $pr_header_id);
		}

		// $this->crud->update("TRX_APPROVAL_PR", array("PR_HEADER_ID" => $pr_header_id));
		$this->crud->update("TRX_APPROVAL_PR", array("IS_ACTIVE" => 0), array("PR_HEADER_ID" => $pr_header_id));
		$insert_approval = $this->crud->create_batch("TRX_APPROVAL_PR", $data_approval);
		$id_approval     = $this->db->insert_id();

		$status   = false;
		$messages = "";

		if($update !== -1){

			$data_lines = array();

			foreach ($data_line as $key => $value) {
				$rkap        = $value['id_rkap'];
				$pr_lines_id = $value['pr_lines_id'];
				$line_name   = $value['line_name'];
				$detail_data = $value['detail_data'];
			}

			foreach ($rkap as $key => $value) {
				if($value['fund_av'] > 0){
					$rkap_data[] = $value;
				}
			}
			// echo_pre($rkap_data);

	        $nominal = $amount;
			$get_nature = get_nature();

			$data_lines = $this->crud->read("PR_LINES", [ "PR_HEADER_ID" => $pr_header_id ]);

			// echo_pre($data_lines);

	        $done_rkap = false;
			for ( $i = 0; $i < count($rkap_data); $i++){

				$id_rkap = $rkap_data[$i]['id_rkap'];
				$is_show = ($i == 0) ? 1 : 0;
		        $get_lines = $data_lines[$i];
			    
		    	$pr_lines_id_keep[] = $get_lines['PR_LINES_ID'];
			    if($rkap_data[$i]['fund_av'] <= $nominal){


			        $nominal -= $rkap_data[$i]['fund_av'];
			        $data_lines_update[] = array(
										"PR_LINES_ID"    => $get_lines['PR_LINES_ID'],
										"PR_LINE_NAME"    => $line_name,
										"PR_LINE_AMOUNT"  => $rkap_data[$i]['fund_av']
									);

			        $rkap_data[$i]['fund_av'] = 0;
			    }else{
			        $get_lines = $data_lines[$i];
			        $rkap_data[$i]['fund_av'] -= $nominal;

			        $data_lines_update[] = array(
										"PR_LINES_ID"    => $get_lines['PR_LINES_ID'],
										"PR_LINE_NAME"   => $line_name,
										"PR_LINE_AMOUNT" => ($done_rkap) ? 0 : $nominal
									);

			        $done_rkap = true;

			    }

			}

			// echo_pre($data_lines_update);
// die;
			$this->crud->update_batch_data("PR_LINES", $data_lines_update, "PR_LINES_ID");

			// die;

			foreach ($data_line as $key => $value) {

				$detail_data = $value['detail_data'];

				$this->crud->delete("PR_DETAIL", [ "PR_HEADER_ID" => $pr_header_id] );
				$pr_detail_number = 1;

				foreach ($detail_data as $key => $value_dtl) {

					$data_details[] = array(
										"PR_HEADER_ID"     => $pr_header_id,
										"PR_LINES_ID"      => $value['pr_lines_id'],
										"PR_DETAIL_NUMBER" => $pr_detail_number,
										"PR_DETAIL_DESC"   => $value_dtl['rkap_desc'],
										"PR_DETAIL_NAME"   => $value_dtl['rkap_item'],
										"CATEGORY_ITEM"    => $value_dtl['category_item'],
										"QUANTITY"         => $value_dtl['quantity'],
										"GOODS_SERVICES"   => $value_dtl['type'],
										"UOM"         	   => $value_dtl['uom'],
										"PRICE"            => $value_dtl['price'],
										"PR_DETAIL_AMOUNT" => $value_dtl['nominal_detail'],
										"CREATED_BY"       => get_user_data($this->session->userdata('user_id'))
									);
					$pr_detail_number++;
				}


			}


			$insert_detail = $this->crud->create_batch("PR_DETAIL", $data_details);

			if($insert_detail){
				if($send_email){
					$this->_email_aprove($recipient, $pr_header_id, $id_approval);
				}
				$status    = true;
			}else{
				$messages = "Failed to Create PR Detail";
			}

		}
		else{
			$messages = "Failed to Create PR";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }


	public function delete_pr(){

		$id       = $this->input->post('id');
		$category = $this->input->post('category');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		if($category == "header"){
			$delete = $this->crud->delete("PR_HEADER", array("PR_HEADER_ID" => $id));
		}
		elseif($category == "lines"){
			$delete = $this->crud->delete("PR_LINES", array("PR_LINES_ID" => $id));
		}
		else{
			$delete = $this->crud->delete("PR_DETAIL", array("PR_DETAIL_ID" => $id));
		}

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

    private function _email_aprove($recipient, $id_pr, $id_approval=0){

		$get_pr      = $this->purchase->get_pr_for_email($id_pr);

		$currency      = ($get_pr['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_pr['PR_AMOUNT'],0,',','.');
		$pr_name       = $get_pr['PR_NAME'];
		$pr_number     = $get_pr['PR_NUMBER'];
		$attachment    = $get_pr['DOCUMENT_UPLOAD'];
		$submitter     = $get_pr['SUBMITTER'];
		$currency_rate = ($get_pr['CURRENCY'] == "IDR") ? $get_pr['CURRENCY'] : $get_pr['CURRENCY'] ."/". number_format($get_pr['CURRENCY_RATE'],0,'.',',');

		// $approval_lnk = base_url("pr/approval/").encrypt_string($id_pr."-".$id_approval, true);
		$data['email_preview'] = "There's new purchase request $pr_number waiting for your verification.";

		$email_body = array();

		foreach ($recipient as $key => $value) {
			$get_id = $this->db->query("select ID from TRX_APPROVAL_PR WHERE ID_APPROVAL = ? order by id desc limit 1", $value['id_approval'])->row_array();
			if($get_id){
				$approval_lnk = base_url("pr/approval/").encrypt_string($id_pr."-".$get_id['ID'], true);
			}else{
				$approval_lnk = base_url("purchase-requisition").encrypt_string($id_pr, true);
			}

			$email_body[] = "There's new purchase request $pr_number waiting for your verification.
			<br>
							The Purchase request details are: 
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
									Please go through the <a href='$approval_lnk'>link</a> to see all details, attachment and confirm your approval.
									";
		}
		$data['approval_link_all'] = base_url("pr/approval");

		// $to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$subject    = "Request Verification PR - $pr_number - $pr_name";
		$attachment = ($attachment) ? FCPATH.'/uploads/pr_attachment/'.$attachment : '';

		foreach ($recipient as $key => $value) {

			$data['email_recipient']  = $value['name'];
			$data['email_body'] = $email_body[$key];
			$body       = $this->load->view('email/pr_request_approve', $data, TRUE);
			$to   = $value['email'];
			$send = sendemail($to, $subject, $body, $cc, $attachment);
		}

		return $send;
	}
	
	function download_pr($param="")
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No.");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Directorate");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Division");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Unit");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "PR Number");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "PR Name");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Status");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Assigned to");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "PR Date");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Total Amount");

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
				$pr_date     = $obj_param->pr_date;

				if($pr_date){
					$exp_pr_date = explode(" - ", $pr_date);
					$date_from = date_db($exp_pr_date[0]);
					$date_to   = date_db($exp_pr_date[1]);
				}
			}
		}
		$hasil = $this->purchase->get_download_pr($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);

		$numrow  = 2;
		$number = 1;

		foreach($hasil as $row)	{

			$pr_date = date("d-m-Y",strtotime($row['PR_DATE']));
			$curr = strtoupper($row['CURRENCY']);
			$status = ($row['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($row['STATUS']);

			$status_assign = "-";
			if($row['STATUS_ASSIGN'] && ($row['STATUS'] == "approved" || $row['STATUS'] == "po created") ){
				$buyer_name = ($row['PO_BUYER']) ? $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $row['PO_BUYER'])) : '';
				$status_assign = ($row['STATUS_ASSIGN'] == "N") ? "Waiting for assign" : $buyer_name['PIC_NAME'];
			}

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['PR_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['PR_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $status);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $status_assign);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $pr_date);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $curr);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['PR_AMOUNT']);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$number++;
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 11);
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
		header('Content-Disposition: attachment; filename="Purchase Requisition.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function printPDF($pr_header_id){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$decrypt      = decrypt_string($pr_header_id, true);
		$pr_header_id = (int) $decrypt;

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/form_pr_new2.pdf';

		$data = $this->purchase->get_cetak($pr_header_id);

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);

		$mpdf->SetTextColor(0,0,0);
		$mpdf->SetFont('Courier New','',18);

		$guideline = 0;

		$doc_list = ($data['DOCUMENT_CHECKLIST']) ? $data['DOCUMENT_CHECKLIST'] : '';

		if($doc_list){

			$arrDocList = (array) json_decode($doc_list);

			if (array_key_exists('justifikasi', $arrDocList)) {
				if ($arrDocList['justifikasi'] == true) {
					$height = 56.6;
					$mpdf->SetXY(78.4, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('program_control_review', $arrDocList)) {
				if ($arrDocList['program_control_review'] == true) {
					$height = 61.4;
					$mpdf->SetXY(78.4, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('boq', $arrDocList)) {
				if ($arrDocList['boq'] == true) {
					$height = 66;
					$mpdf->SetXY(78.4, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('dpl_pl', $arrDocList)) {
				if ($arrDocList['dpl_pl'] == true) {
					$height = 56.6;
					$mpdf->SetXY(179.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('rks_tender', $arrDocList)) {
				if ($arrDocList['rks_tender'] == true) {
					$height = 61.4;
					$mpdf->SetXY(168.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('rks_pl', $arrDocList)) {
				if ($arrDocList['rks_pl'] == true) {
					$mpdf->SetXY(179.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('mom_po', $arrDocList)) {
				if ($arrDocList['mom_po'] == true) {
					$height = 66;
					$mpdf->SetXY(157.5, $height+5);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('nodin_amd', $arrDocList)) {
				if ($arrDocList['nodin_amd'] == true) {
					$height = 71;
					$mpdf->SetXY(148.2, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('nodin_tender', $arrDocList)) {
				if ($arrDocList['nodin_tender'] == true) {
					$mpdf->SetXY(168.4, $height);
					$mpdf->Cell(0,18.5,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('nodin_pl', $arrDocList)) {
				if ($arrDocList['nodin_pl'] == true) {
					$mpdf->SetXY(179.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
		}else{
			$height = 56.6;
			$mpdf->SetXY(78.4, $height);
			$mpdf->Cell(10,10,"✓",$guideline,"L");
		}

		$mpdf->SetFont('Calibri','',10);
		$titikdua = 78;
		$height = 79.3;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"No. PR",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$height = 79.3;
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['PR_NUMBER'],$guideline,"L");

		$justif = substr($data['FS_NAME'],0,60);
		$justif2 = substr($data['FS_NAME'],60,120);
		$justif3 = substr($data['FS_NAME'],120,240);

		if(strlen($data['FS_NAME']) < 60){
			$height = 84;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"Judul Justifikasi",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,":",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$data['FS_NAME'],$guideline,"L");
		}elseif(strlen($data['FS_NAME']) > 60 && strlen($data['FS_NAME']) < 120){
			$height = 84;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"Judul Justifikasi",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,":",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif,$guideline,"L");

			$height = $height+5;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,"",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif2,$guideline,"L");
		}elseif(strlen($data['FS_NAME']) > 120){
			$height = 84;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"Judul Justifikasi",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,":",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif,$guideline,"L");

			$height = $height+5;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,"",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif2,$guideline,"L");

			$height = $height+5;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,"",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif3,$guideline,"L");
		}

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"PIC PR",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['SUBMITTER'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Unit",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['UNIT_NAME'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Budget (with Tax )",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,number_format($data['PR_AMOUNT'],0,',','.'),$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"COA",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['NATURE'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"COA Desc.",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['DESCRIPTION'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Direktorat",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['DIRECTORAT_NAME'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Usulan Metode Pengadaan*",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,"--",$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Verification Note*",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,"--",$guideline,"L");
		
		$get_approval      = $this->purchase->get_approval_by_pr($pr_header_id);
		$approval_hog      = "";
		$approval_proc_sup = "";
		$approval_hou_buy  = "";

		foreach ($get_approval as $key => $value) {

			$category = strtolower($value['CATEGORY']);

			$approval = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");

			if($category == "hog user"){
				$approval_hog = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
			}elseif($category == "procurement"){
				$approval_proc_sup = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
			}/*elseif($category == "hou procurement"){
				$approval_hou_buy = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
			}*/
		}

		if($approval_proc_sup != ""){
			$mpdf->SetFont('Calibri','B',10);

			$height = 140.5;
			$mpdf->SetXY(35, $height);
			$mpdf->Cell(10,10, "Verifikator*",$guideline,"C");

			$mpdf->SetFont('Calibri','',10);
			$height = 145.5;
			$mpdf->SetXY(35, $height);
			$mpdf->Cell(10,10, $approval_proc_sup['TGL_APPROVE'],$guideline,"C");

			$height = 155.5;
			if($approval_proc_sup['STATUS'] == "request_approve"){
				$mpdf->SetXY(29, $height);
				$status = "Waiting for review"; 
				// $status = "X"; 
			}else{
				$mpdf->SetXY(29, $height);
				$status = ($approval_proc_sup['STATUS'] == "approved") ? "Verified" : "Rejected";
			}
			$pic = $approval_proc_sup['NAME'];
			$mpdf->Cell(10,10, $status, $guideline,"L");
			$height = 162;
			$mpdf->SetXY(29, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"L");

			$height = $height+5.4;
			$mpdf->SetXY(29, $height);
			$mpdf->Cell(10,10, "Procurement Support", $guideline,"L");
		}

		if($approval_hog != ""){

			$mpdf->SetFont('Calibri','B',10);
			$height = 140.5;
			$mpdf->SetXY(95, $height);
			$mpdf->Cell(10,10, "Disetujui Oleh*",$guideline,"C");

			$mpdf->SetFont('Calibri','',10);
			$height = 145.5;
			$mpdf->SetXY(95, $height);
			$mpdf->Cell(10,10, $approval_hog['TGL_APPROVE'],$guideline,"C");

			$height = 155.5;
			$status = "";
			if($approval_hog['STATUS'] == "request_approve"){
				$mpdf->SetXY(88, $height);
				$status = "Waiting for approval"; 
			}elseif($approval_hog['STATUS'] != NULL){
				$mpdf->SetXY(88, $height);
				$status = ucfirst($approval_hog['STATUS']); 
			}
			$pic = $approval_hog['NAME'];
			$mpdf->Cell(10,10, $status, $guideline,"L");
			$height = 162;
			$mpdf->SetXY(88, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"L");

			$height = $height+5.4;
			$mpdf->SetXY(88, $height);
			$mpdf->Cell(10,10, "Head Of Group User", $guideline,"L");
		}

		$usulan_buyer = $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $data['PO_BUYER']));
		$get_hou_proc  = $this->feasibility_study->get_data_approval("HOU Procurement");
		// if($approval_hou_buy != "" && $usulan_buyer){

			$mpdf->SetFont('Calibri','B',10);
			$height = 140.5;
			$mpdf->SetXY(145, $height);
			$mpdf->Cell(10,10, "Usulan Buyer*",$guideline,"C");

			$mpdf->SetFont('Calibri','',10);
			$height = 145.5;
			$mpdf->SetXY(145, $height);
			if($data['STATUS_ASSIGN'] == "Y" && $data['ASSIGN_DATE'] != NULL){
				$mpdf->Cell(10,10, dateFormat($data['ASSIGN_DATE'], 4, false), $guideline, "C");
			}else{
				$mpdf->Cell(10,10, "-", $guideline, "C");
			}

			$height = 155.5;
			$status = "";
			if($data['STATUS_ASSIGN'] == "Y"){
				$mpdf->SetXY(138, $height);
				$status = "Assigned"; 
			}elseif($approval_hog['STATUS'] != NULL){
				$mpdf->SetXY(138, $height);
				$status = "Waiting for assign"; 
			}
			$pic = ($usulan_buyer) ? $usulan_buyer['PIC_NAME']: "";
			$mpdf->Cell(10,10, $status, $guideline,"L");
			$height = 162;
			$mpdf->SetXY(138, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"L");

			$height = $height+5.4;
			$mpdf->SetXY(138, $height);
			$mpdf->Cell(10,10, "Procurement Buyer", $guideline,"L");
		// }
		/*if($approval_hou_buy != ""){

			$height = 147.5;
			$mpdf->SetXY(145, $height);
			$mpdf->Cell(10,10, $approval_hou_buy['TGL_APPROVE'],$guideline,"C");

			$height = 155.5;
			if($approval_hou_buy['STATUS'] == "request_approve"){
				$mpdf->SetXY(140, $height);
				$status = "Waiting for approval"; 
			}else{
				$mpdf->SetXY(145, $height);
				$status = ucfirst($approval_hou_buy['STATUS']); 
			}
			$pic = $approval_hou_buy['NAME'];
			$mpdf->Cell(10,10, $status, $guideline,"C");
			$height = 163;
			$mpdf->SetXY(140, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"C");
		}*/

		$tracking[] = array('tanggal' => dateFormat($data['CREATED_DATE'], 4, false), 'keterangan' => 'Submitted by ' . $data['SUBMITTER'], 'paraf' => 'Submitted');
		$history =  $this->purchase->get_comment_history($pr_header_id);

		foreach ($history as $key => $value):
			$remarkAdd = ($value['REMARK'] != '') ? ' : "'. substr($value['REMARK'],0,30) .'"' : '';
			$status = $value['STATUS'];
			if(strtolower($value['CATEGORY']) == "procurement"){
				$status = ($status == "approved") ? "verified" : $status;
				$ket    = ucfirst($status) .' by ' . $value['PIC_NAME'] . $remarkAdd;
			}
			/*elseif(strtolower($value['CATEGORY']) == "hou procurement"){
				$status = ($status == "approved") ? "assigned" : $status;
				$ket    = ucfirst($status) .' buyer by ' . $value['PIC_NAME'] . $remarkAdd;
			}*/else{
				$ket = ucfirst($status) .' by ' . $value['PIC_NAME'] . $remarkAdd;
			}
			$paraf = ucfirst($status);

			$tracking[] = array(
								'tanggal'    => dateFormat($value['UPDATED_DATE'], 4, false),
								'keterangan' => $ket,
								'paraf'      => $paraf
							);
		endforeach;

		if($data['STATUS_ASSIGN'] == "Y"):

			$tracking[] = array(
							'tanggal'    => dateFormat($data['ASSIGN_DATE'], 4, false),
							'keterangan' => 'Assigned to buyer by ' . $get_hou_proc['PIC_NAME'],
							'paraf'      => "Assigned"
						);
		endif;


		$height = 186.4;
		foreach ($tracking as $key => $value):

			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10, $value['tanggal'], $guideline,"L");
			$mpdf->SetXY(51, $height);
			$mpdf->Cell(10,10, $value['keterangan'], $guideline,"L");
			$mpdf->SetXY(168, $height);
			$mpdf->Cell(10,10, $value['paraf'], $guideline,"C");
			
			$height += 4.6;
		endforeach;

		$pr_encrypt = $data['PR_NUMBER'] . " - " . number_format($data['PR_AMOUNT'],0,',','.');
		$doc_ref = encrypt_string($pr_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";
		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);
	    
		$title = "Form PR - ".$data['PR_NUMBER'] ." - ". $data['PR_NAME'];
        $mpdf->SetTitle($title);
		$title = "Form PR - ".$data['PR_NUMBER'] .".pdf";

		$mpdf->Output($title, "I");
	}

}

/* End of file PR_ctl.php */
/* Location: ./application/controllers/pr_po/PR_ctl.php */
