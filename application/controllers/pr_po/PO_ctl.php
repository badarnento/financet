<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO_ctl extends CI_Controller {

	private $module_name = "po",
			$user_email = "",
			$user_cc  = array(),
			$module_title = "Purchase Order",
			$module_url   = "purchase-order";

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$list_cc['aldji'] = 'aldji_i_kahar@linkaja.id';
		$list_cc['susanto'] = 'susanto_wu@linkaja.id';
		$list_cc['dita'] = 'dita_lestari@linkaja.id';
		$list_cc['wahyu'] = 'wahyu_bijaksana@linkaja.id';

		$this->user_cc = $list_cc;

		$this->user_email = $this->session->userdata('email');
		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');
		
	}

	public function index()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$data['title']         = "Purchase Order";
			$data['module']        = "datatable";
			$data['template_page'] = "pr_po/po_inquiry";
			$data['po_status']     = get_status_po();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Order", "link" => "", "class" => "active" );

			$group_name = get_user_group_data();

		    $directorat = check_is_bod();
		    $binding    = check_binding();

		    if(count($directorat) > 1 && $binding['binding'] != false){
				$directorat = $binding['data_binding']['directorat'];
		    }

			$data['su_budget']    = (in_array("SU Budget", $group_name)) ? true : false;
			$data['directorat']   = $directorat;
			$data['binding']      = $binding['binding'];
			$data['data_binding'] = $binding['data_binding'];

			$data['group_name']    = $group_name;

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
		
	}

	public function create_po($id_pr=""){

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Order", "link" => base_url('purchase-order'), "class" => "");
			$data['module']        = "datatable";

			if($id_pr == ""){
				$data['title']         = "Create PO";
				$data['template_page'] = "pr_po/po_pr_inquiry";

				$buyer_email = $this->user_email;

				$get_pr_category = $this->purchase->get_category_pr($buyer_email);
				$id_pr_category = array();
				foreach ($get_pr_category as $key => $value) {
					$id_pr_category[] = $value['CATEGORY'];
				}

				$directorat            = get_all_directorat();
				$data['directorat']    = $directorat;
				$data['id_pr_category']    = json_encode($id_pr_category);

				$breadcrumb[] = array( "name" => "Create PO", "link" => "", "class" => "active" );

			}else{

				$decrypt = decrypt_string($id_pr, true);
				$id_pr   = (int) $decrypt;

				$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

				if($check_exist > 0){

					$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $id_pr));

					$data['title']         = "Create PO for PR ".$get_pr_header['PR_NUMBER'];
					$data['module']        = "datatable";
					$data['template_page'] = "pr_po/po_create";

					$data['pr_header_id']  = $get_pr_header['PR_HEADER_ID'];
					$data['pr_link']       = base_url("purchase-requisition/") . encrypt_string($get_pr_header['PR_HEADER_ID'], true);
					$data['pr_number']     = $get_pr_header['PR_NUMBER'];
					$data['pr_name']       = $get_pr_header['PR_NAME'];
					$data['pr_date']       = dateFormat($get_pr_header['PR_DATE'], 5, false);
					$data['pr_amount']     = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
					$data['pr_currency']   = strtoupper($get_pr_header['CURRENCY']);
					$data['pr_directorat'] = $get_pr_header['ID_DIR_CODE'];
					$data['pr_division']   = $get_pr_header['ID_DIVISION'];
					$data['pr_unit']       = $get_pr_header['ID_UNIT'];

					$breadcrumb[] = array( "name" => "Create PO", "link" => base_url('purchase-order/create'), "class" => "" );
					$breadcrumb[] = array( "name" => "Create PO ".$get_pr_header['PR_NUMBER'], "link" => "", "class" => "active" );


				}
				else{
					$this->session->set_flashdata('messages', 'PR Not Exist');
					redirect('purchase-order/create');
				}

			}

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_inquiry(){

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

		if($this->input->post('po_date')){
			$exp_po_date = explode(" - ", $this->input->post('po_date'));
			$date_from = date_db($exp_po_date[0]);
			$date_to   = date_db($exp_po_date[1]);
		}

		$get_all         = $this->purchase->get_data_po($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		$get_gl = $this->crud->read_specific("GL_HEADERS", "NO_KONTRAK");
		$no_kontrak = array();

		if($get_gl):
			foreach ($get_gl as $key => $value):
				$no_kontrak[] = $value['NO_KONTRAK'];
			endforeach;
		endif;

		$group_name = get_user_group_data();

		$procurement  = (in_array("Procurement", $group_name)) ? true : false;
		$po_inq_group = (in_array("PO Inquiry", $group_name)) ? true : false;
		$po_user      = (in_array("PO PDF Only", $group_name)) ? true : false;

		if($total > 0){

			$blank    = "";

			foreach($data as $value) {

				$status_disabled = $blank;
				$hide_action     = $blank;

				if($po_inq_group){
					$status_disabled = " disabled";
					$hide_action     = " d-none";

				}else{
					if(in_array($value['PO_NUMBER'], $no_kontrak)){
						$status_disabled = " disabled";
						$hide_action     = " d-none";
					}
				}

				$statusVal  = strtolower($value['STATUS']);
				$po_url_enc = encrypt_string($value['PO_HEADER_ID'], true);

				$statusInfo = ($statusVal == "request_approve") ? "Waiting Approval" : ucfirst($statusVal);
				$status     = ($statusVal == "request_approve") ? "Waiting Approval" : $statusVal;

				$addClosePo = "";
				if($procurement){
					$addClosePo = '<a href="javascript:void(0)" class="action-close px-5" title="Click to close PO"><i class="fa fa-close text-grey" aria-hidden="true"></i></a>';
				}

				$action = '<a href="javascript:void(0)" class="action-view px-5" title="Click to view PO" data-id="'.$po_url_enc.'"><i class="fa fa-search text-success" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-edit px-5'.$hide_action.'" title="Click to edit PO" data-id="'.$po_url_enc.'"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-delete px-5'.$hide_action.'" title="Click to delete PO"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>'.$addClosePo.'<a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="'.$po_url_enc.'"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

				if($po_user){
					$action = '<a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" data-id="'.$po_url_enc.'"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
				}

				$row[] = array(
						'no'           => $number,
						'id'           => $value['PO_HEADER_ID'],
						'pr_header_id' => $value['PR_HEADER_ID'],
						'directorat'   => get_directorat($value['ID_DIR_CODE']),
						'division'     => get_division($value['ID_DIVISION']),
						'unit'         => get_unit($value['ID_UNIT']),
						'po_number'    => $value['PO_NUMBER'],
						'pr_number'    => $value['PR_NUMBER'],
						'po_name'      => $value['PO_LINE_DESC'],
						'status_act'   => $statusInfo,
						'status'       => $status,
						'po_date'      => dateFormat($value['PO_DATE'], 5, false),
						'currency'     => strtoupper($value['CURRENCY_PO']),
						'total_amount' => number_format($value['PO_AMOUNT'],0,',','.'),
						'action'       => $action
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

	public function view_po($id_po_enc){

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$decrypt = decrypt_string($id_po_enc, true);
			$id_po   = (int) $decrypt;

			$check_exist = $this->crud->check_exist("PO_HEADER", array("PO_HEADER_ID" => $id_po));

			if($check_exist > 0){

				$get_po_header = $this->purchase->get_po_header_by_id($id_po);

				$data['title']            = "PO ".$get_po_header['PR_NUMBER'];
				$data['module']           = "datatable";
				$data['template_page']    = "pages/purchase_order_view";
				$data['template_page']    = "pr_po/po_view";
				
				$data['id_po']            = $id_po;
				$data['id_po_enc']        = $id_po_enc;
				$data['pr_link']          = base_url("purchase-requisition/") . encrypt_string($get_po_header['PR_HEADER_ID'], true);
				$data['pr_number']        = $get_po_header['PR_NUMBER'];
				$data['pr_rate']          = $get_po_header['CURRENCY_RATE'];
				$data['pr_name']          = $get_po_header['PR_NAME'];
				$data['po_type']          = $get_po_header['PO_TYPE'];
				$data['po_reference']     = ($get_po_header['PO_REFERENCE']) ? $get_po_header['PO_REFERENCE'] : '';
				$data['po_date']          = dateFormat($get_po_header['PO_DATE'], 5, false);
				$data['po_amount']        = number_format($get_po_header['PO_AMOUNT'],2,'.',',');
				$data['po_currency']      = $get_po_header['CURRENCY_PO'];
				$data['po_rate']          = $get_po_header['CURRENCY_RATE_PO'];
				$data['po_directorat']    = $get_po_header['ID_DIR_CODE'];
				$data['po_division']      = $get_po_header['ID_DIVISION'];
				$data['po_unit']          = $get_po_header['ID_UNIT'];
				$data['po_status']        = $get_po_header['STATUS'];
				$data['po_status_desc']   = $get_po_header['STATUS_DESCRIPTION'];
				$data['po_document']      = $get_po_header['DOCUMENT_SOURCING'];
				$data['mpa_reference']    = $get_po_header['MPA_REFERENCE'];
				$data['top']              = $get_po_header['TOP'];
				$data['est_date']         = $get_po_header['ESTIMATE_DATE'];
				$data['notes']            = $get_po_header['NOTES'];
				$data['po_document_link'] = base_url('download/'). encrypt_string("uploads/po_attachment/".$get_po_header['DOCUMENT_SOURCING'], true);
				$last_update              = ($get_po_header['UPDATED_DATE']) ? $get_po_header['UPDATED_DATE'] : $get_po_header['CREATED_DATE'];
				$data['po_last_update']   = dateFormat($last_update, "with_day", false);
				$data['po_category']      = dateFormat($last_update, "with_day", false);
				$data['po_category']      = ($get_po_header['PO_CATEGORY']) ? $get_po_header['PO_CATEGORY'] : "-";

				$get_approval = $this->purchase->get_approval_by_po($id_po);

				$approval = array();
				$approval_remark = array();

				foreach ($get_approval as $key => $value) {
					$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['JABATAN']);
					if(!empty($value['REMARK'])){
						$approval_remark[] = $value;
					}
				}
				$data['po_approval'] = $approval;

				if(count($approval_remark) > 0){
					foreach ($approval_remark as $key => $v)
					{
				    	$sort[$key] = strtotime($v['UPDATED_DATE']);
					}
					array_multisort($sort, SORT_DESC, $approval_remark);

					$data['po_approval_remark'] = $approval_remark[0]['REMARK'];
				}

				$po_buyer               = ($get_po_header['BUYER']) ? $get_po_header['BUYER'] : '-';
				$data['po_buyer']       = $po_buyer;



				$data['po_document_clauses'] = false;
				if($get_po_header['DOCUMENT_CLAUSE']):
					$data['po_document_clauses'] = true;
					$data['po_document_clauses_link'] = base_url("download/") . encrypt_string("uploads/po_attachment/".$get_po_header['DOCUMENT_CLAUSE'], true);
				endif;

				$po_history = get_po_history($id_po);
				$data['po_history'] = $po_history;


				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => $this->module_title, "link" => base_url($this->module_url), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'PO Not Exist');
				redirect($this->module_url);

			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_lines(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$po_header_id = $this->input->post('po_header_id');
		
		$get_all = $this->purchase->get_po_lines($po_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {


				$nominal_pr = ($value['PR_VERSION'] > 1) ? $value['PR_AMOUNT'] : $value['PR_LINE_AMOUNT'];

				$row[] = array(
						'po_line_id'        => $value['PO_LINE_ID'],
						'id_rkap_line'      => $value['ID_RKAP_LINE'],
						'no'                => $number,
						'line_name'         => $value['PR_LINE_NAME'],
						'nominal'           => number_format($nominal_pr,0,',','.'),
						'po_number'         => $value['PO_NUMBER'],
						'po_name'           => $value['PO_LINE_DESC'],
						'nominal_amount'    => number_format($value['PO_LINE_AMOUNT'],2,'.',','),
						"po_period"         => dateFormat($value['PO_PERIOD_FROM'], 'date', false) .  ' &ndash; ' . dateFormat($value['PO_PERIOD_TO'], 'date', false),
						'vendor_name'       => $value['VENDOR_NAME'],
						'bank_name'         => $value['VENDOR_BANK_NAME'],
						'bank_account_name' => $value['VENDOR_BANK_ACCOUNT_NAME'],
						'bank_account'      => $value['VENDOR_BANK_ACCOUNT']
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


	public function load_data_details(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$po_line_id = $this->input->post('po_line_id');
		
		$get_all = $this->purchase->get_detail_po($po_line_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {
				
				$number = $value['PO_DETAIL_NUMBER'];

				if($value['DESCRIPTION_PO'] != ""){
					$item_desc 	= $value['ITEM_NAME'];
				}else{
					$item_desc 	= $value['PR_DETAIL_NAME'];
				}

				if($value['DESCRIPTION_PO'] != ""){
					$qty_val 	= $value['QTY_PO'];
				}else{
					$qty_val 	= $value['QUANTITY'];
				}

				if($value['DESCRIPTION_PO'] != ""){
					$po_desc = $value['DESCRIPTION_PO'];
				}else{
					$po_desc = $value['PR_DETAIL_DESC'];
				}

				$po_amount = $value['PRICE_PO'];

				$row[] = array(
						'no'            => $number,
						'item_desc'     => $item_desc,
						'detail_desc'   => $po_desc,
						'qty'           => $qty_val,
						'category_item' => $value['CATEGORY_ITEM'],
						'uom'           => $value['UOM'],
						'unit_price'    => number_format($value['PRICE'],0,',','.'),
						'item_price'    => number_format($po_amount,0,',','.'),
						'po_desc'       => $value['DESCRIPTION_PO'],
						'total_price'   => number_format($value['PO_DETAIL_AMOUNT'],0,',','.'),
						'nominal_po'    => number_format($value['PO_DETAIL_AMOUNT'],0,',','.')
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


	public function load_data_detail_edit(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$po_line_id = $this->input->post('po_line_id');
		
		$get_all = $this->purchase->get_detail_po($po_line_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$number = $value['PO_DETAIL_NUMBER'];

				$item_desc = ( $value['ITEM_NAME'] != "") ? $value['ITEM_NAME'] : $value['PR_DETAIL_NAME'];
				$po_desc   = ( $value['DESCRIPTION_PO'] != "") ? $value['DESCRIPTION_PO'] : $value['PR_DETAIL_DESC'];

				$qty_val      = ( $value['QTY_PO'] > 0) ? $value['QTY_PO'] : $value['QUANTITY'];
				$po_price      = ( $value['PRICE_PO'] > 0) ? $value['PRICE_PO'] : $value['PRICE'];
				$po_price      = number_format($po_price,0,',','.');
				$po_amount_dtl = ( $value['PO_AMOUNT_DETAIL'] > 0) ? $value['PO_AMOUNT_DETAIL'] : $value['PR_DETAIL_AMOUNT'];
				$po_amount_dtl = number_format($po_amount_dtl,0,',','.');


				$po_desc_new           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc" value="'.$po_desc.'" readonly></div>';
				$po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc" value="'.$po_desc.'"></div>';
				$item_desc           = '<div class="form-group m-b-0"><input id="item_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm item_desc" value="'.$item_desc.'" readonly></div>';
				$nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="'.$po_amount_dtl.'"></div>';
				$unit_price = '<div class="form-group m-b-0"><input id="unit_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm unit_price text-right money-format" type="text" value="'.$po_price.'"></div>';
				$total_price = '<div class="form-group m-b-0"><input id="total_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm total_price text-right money-format" type="text" value="'.$po_amount_dtl.'" readonly></div>';
				$qty = '<div class="form-group m-b-0"><input id="qty-'.$number.'" data-id="'.$number.'" class="form-control input-sm qty text-center" value="'.$qty_val.'" min="1" max="99999" type="number" readonly></div>';

				$row[] = array(
							'po_detail_id' => $value['PO_DETAIL_ID'],
							'no'           => $number,
							'detail_desc'  => $po_desc_new,
							'item_desc'    => $item_desc,
							'category_item' => $value['CATEGORY_ITEM'],
							'qty'    	   => $qty,
							'unit_price'   => $unit_price,
							'total_price'  => $total_price,
							'quantity'     => $value['QUANTITY'],
							'uom'     	   => $value['UOM'],
							'price'        => number_format($value['PRICE'],0,',','.'),
							'nominal'      => number_format($value['PR_DETAIL_AMOUNT'],0,',','.'),
							'po_desc'      => $po_desc,
							'nominal_po'   => $nominal_detail_po
						);

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);
	}


	public function load_pr_header(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_dir_code = $this->input->post('directorat');
		$pr_category = $this->input->post('pr_category');

		$date_from   = "";
		$date_to     = "";

		if($this->input->post('pr_date')){
			$exp_pr_date = explode(" - ", $this->input->post('pr_date'));

			$date_from = date_db($exp_pr_date[0]);
			$date_to   = date_db($exp_pr_date[1]);

		}
		
		$group_name = get_user_group_data();
		$buyer_email    = (in_array("PO Buyer", $group_name)) ? $this->user_email : false;

		$get_all         = $this->purchase->get_pr_for_po($buyer_email, $id_dir_code, $date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status = ($value['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($value['STATUS']);

				$row[] = array(
						'no'           => $number,
						'directorat'   => get_directorat($value['ID_DIR_CODE']),
						'pr_header_id' => encrypt_string($value['PR_HEADER_ID'], true),
						'id'           => $value['PR_HEADER_ID'],
						'pr_number'    => $value['PR_NUMBER'],
						'pr_name'      => $value['PR_NAME'],
						'status'       => $status,
						'pr_date'      => dateFormat($value['PR_DATE'], 5, false),
						'currency'     => strtoupper($value['CURRENCY']),
						'total_amount' => number_format($value['PR_AMOUNT'],0,',','.')
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

	public function load_pr_header_for_buyer(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$group_name = get_user_group_data();
		$buyer_email    = (in_array("PO Buyer", $group_name)) ? $this->user_email : false;

		$get_all         = $this->purchase->get_pr_for_po($buyer_email);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'           => $number,
						'unit'         => get_unit($value['ID_UNIT']),
						'pr_header_id' => encrypt_string($value['PR_HEADER_ID'], true),
						'id'           => $value['PR_HEADER_ID'],
						'pr_number'    => $value['PR_NUMBER'],
						'pr_name'      => $value['PR_NAME'],
						'submitter'    => $value['SUBMITTER'],
						'pr_date'      => dateFormat($value['PR_DATE'], 5, false),
						'total_amount' => number_format($value['PR_AMOUNT'],0,',','.')
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

	public function load_data_pr_for_po_create(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_header_id = $this->input->post('pr_header_id');
		
		$get_all = $this->purchase->get_pr_for_po_create($pr_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		
		$banks = get_all_bank();
		$optBank = '<option value="0">-- Select Bank --</option>';
		foreach ($banks as $key => $bank) {
			$bank_name_opt = $bank['BANK_NAME'];
			$optBank .= '<option value="'.$bank_name_opt.'">'.$bank_name_opt.'</option>';
		}

		$vendor = get_all_vendor();
		foreach ($vendor as $key => $v)
		{
		    $sort[$key] = $v['NAMA_VENDOR'];
		}
		array_multisort($sort, SORT_ASC, $vendor);
		
		$optVendor = '<option value="0" data-no_rek="" data-nama_rek="" data-bank="">-- Select Vendor --</option>';
		foreach ($vendor as $key => $val) {
			$vendor   = $val['NAMA_VENDOR'];
			$bank     = $val['NAMA_BANK'];
			$nama_rek = $val['NAMA_REKENING'];
			$no_rek   = $val['ACCT_NUMBER'];
			$optVendor .= '<option value="'.$vendor.'" data-no_rek="'.$no_rek.'" data-nama_rek="'.$nama_rek.'" data-bank="'.$bank.'">'.$vendor.'</option>';

			$vendorArr[] = array(
									"NAMA_VENDOR"   => $val['NAMA_VENDOR'],
									"NAMA_BANK"     => $val['NAMA_BANK'],
									"NAMA_REKENING" => $val['NAMA_REKENING'],
									"ACCT_NUMBER"   => $val['ACCT_NUMBER']
								);
		}

		if($total > 0){

			foreach($data as $value) {
				
				$valDate    = date('d/m/Y');
				
				$vendor_name              = $value['VENDOR_NAME'];
				$bank_name_val            = $value['VENDOR_BANK_NAME'];
				$vendor_bank_account_name = $value['VENDOR_BANK_ACCOUNT_NAME'];
				$vendor_bank_account      = $value['VENDOR_BANK_ACCOUNT'];
				
				$po_number       = '<div class="form-group m-b-0"><input id="po_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_number" value="'.$value['PO_NUMBER'].'"></div>';
				$po_line_name    = '<div class="form-group m-b-0"><input id="po_line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_line_name" value="'.$value['PO_LINE_DESC'].'"></div>';

				if($value['PO_AMOUNT'] != 0){
					$po_amount_val = number_format($value['PO_AMOUNT'],0,',','.');
				}else{
					$po_amount_val = number_format($value['PR_LINE_AMOUNT'],0,',','.');
				}

				$po_amount       = '<div class="form-group m-b-0"><input id="po_amount-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_amount text-right money-format" type="text"  value="'.$po_amount_val.'" readonly></div>';

				$currency_rate = $value['CURRENCY_RATE'];

				$bank_name       = '<div class="form-group m-b-0"><input id="bank_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm bank_name" value="'.$bank_name_val.'" readonly></div>';
				$account_name    = '<div class="form-group m-b-0"><input id="account_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm account_name" value="'.$vendor_bank_account_name.'" readonly></div>';
				$account_number  = '<div class="form-group m-b-0"><input id="account_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm account_number" value="'.$vendor_bank_account.'" readonly></div>';

				$po_period    = '<div class="form-group m-b-0"><input class="form-control po_period input-daterange-datepicker input-sm" type="text" id="po_period-'.$number.'" data-id="'.$number.'" value=""/></div>';

				$disabled = '';

				$action_split = '<button id="action_split-'.$number.'" data-id="'.$number.'" class="btn btn-sm btn-info border-radius-5 w-100p action-split" type="button"><i class="fa fa-copy"></i> Split</button>';

				if($vendor_name != ""){

					$disabled = ' disabled';

					$optVendor = '<option value="0" data-no_rek="" data-nama_rek="" data-bank="">-- Select Vendor --</option>';

					foreach ($vendorArr as $k => $vVendor) {

						$vendor   = $vVendor['NAMA_VENDOR'];
						$bank     = $vVendor['NAMA_BANK'];
						$nama_rek = $vVendor['NAMA_REKENING'];
						$no_rek   = $vVendor['ACCT_NUMBER'];

						$selected = ($vendor == $vendor_name) ? ' selected' : '';

						$optVendor .= '<option value="'.$vendor.'" data-no_rek="'.$no_rek.'" data-nama_rek="'.$nama_rek.'" data-bank="'.$bank.'"'.$selected.'>'.$vendor.'</option>';
					}
				}

				$vendor_opt = '<div class="form-group m-b-0"><select id="vendor_name-'.$number.'" class="form-control input-sm vendor_name select2 select-center"'.$disabled.'>'.$optVendor.'</select></div>';

				$pr_amount = ($total == 1) ? $value['PR_AMOUNT'] : $value['PR_LINE_AMOUNT'] ;

				$row[] = array(
							'po_line_key'                => "k".strtolower(generateRandomString(5)),
							'pr_lines_id'                => $value['PR_LINES_ID'],
							'no'                         => $number,
							'tribe'                      => $value['TRIBE_USECASE'],
							'id_rkap_line'               => $value['ID_RKAP_LINE'],
							'rkap_name'                  => $value['RKAP_DESCRIPTION'],
							'pr_name'                    => $value['PR_LINE_NAME'],
							'pr_amount'                  => number_format($pr_amount,0,',','.'),
							'currency_rate'              => number_format($currency_rate,0,',','.'),
							'po_number'                  => $po_number,
							'po_line_name'               => $po_line_name,
							'po_amount'                  => $po_amount,
							'po_period'                  => $po_period,
							'vendor_name'                => $vendor_opt,
							'nama_bank'                  => $bank_name_val,
							'vendor_bank_name'           => $bank_name,
							'vendor_bank_account'        => $account_name,
							'vendor_bank_account_number' => $account_number,
							'action_split'               => $action_split,
							'po_number_edit'             => $value['PO_NUMBER']
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


	public function load_data_po_lines_edit(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$po_header_id = $this->input->post('po_header_id');
		
		$get_all = $this->purchase->get_po_lines($po_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		
		$banks = get_all_bank();
		$optBank = '<option value="0">-- Select Bank --</option>';
		foreach ($banks as $key => $bank) {
			$bank_name_opt = $bank['BANK_NAME'];
			$optBank .= '<option value="'.$bank_name_opt.'">'.$bank_name_opt.'</option>';
		}

		$vendor = get_all_vendor();
		foreach ($vendor as $key => $v)
		{
		    $sort[$key] = $v['NAMA_VENDOR'];
		}
		array_multisort($sort, SORT_ASC, $vendor);
		
		$optVendor = '<option value="0" data-no_rek="" data-nama_rek="" data-bank="">-- Select Vendor --</option>';
		foreach ($vendor as $key => $val) {
			$vendor   = $val['NAMA_VENDOR'];
			$bank     = $val['NAMA_BANK'];
			$nama_rek = $val['NAMA_REKENING'];
			$no_rek   = $val['ACCT_NUMBER'];
			$optVendor .= '<option value="'.$vendor.'" data-no_rek="'.$no_rek.'" data-nama_rek="'.$nama_rek.'" data-bank="'.$bank.'">'.$vendor.'</option>';

			$vendorArr[] = array(
									"NAMA_VENDOR"   => $val['NAMA_VENDOR'],
									"NAMA_BANK"     => $val['NAMA_BANK'],
									"NAMA_REKENING" => $val['NAMA_REKENING'],
									"ACCT_NUMBER"   => $val['ACCT_NUMBER']
								);
		}

		if($total > 0){

			foreach($data as $value) {
				
				$valDate    = date('d/m/Y');
				
				$vendor_name              = $value['VENDOR_NAME'];
				$bank_name_val            = $value['VENDOR_BANK_NAME'];
				$vendor_bank_account_name = $value['VENDOR_BANK_ACCOUNT_NAME'];
				$vendor_bank_account      = $value['VENDOR_BANK_ACCOUNT'];
				
				$po_number       = '<div class="form-group m-b-0"><input id="po_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_number" value="'.$value['PO_NUMBER'].'"></div>';
				$po_line_name    = '<div class="form-group m-b-0"><input id="po_line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_line_name" value="'.$value['PO_LINE_DESC'].'"></div>';

				if($value['PO_LINE_AMOUNT'] != 0){
					$po_amount_val = number_format($value['PO_LINE_AMOUNT'],0,',','.');
				}else{
					$po_amount_val = number_format($value['PR_LINE_AMOUNT'],0,',','.');
				}

				$po_amount_val = number_format($value['PO_LINE_AMOUNT'],0,',','.');
				$po_amount       = '<div class="form-group m-b-0"><input id="po_amount-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_amount text-right money-format" type="text"  value="'.$po_amount_val.'" readonly></div>';
				$bank_name       = '<div class="form-group m-b-0"><input id="bank_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm bank_name" value="'.$bank_name_val.'" readonly></div>';
				$account_name    = '<div class="form-group m-b-0"><input id="account_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm account_name" value="'.$vendor_bank_account_name.'" readonly></div>';
				$account_number  = '<div class="form-group m-b-0"><input id="account_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm account_number" value="'.$vendor_bank_account.'" readonly></div>';

				$po_period_val = ($value['PO_PERIOD_FROM'] != "" && $value['PO_PERIOD_TO'] != "") ? dateFormat($value['PO_PERIOD_FROM'], 'date', false) .  ' - ' . dateFormat($value['PO_PERIOD_TO'], 'date', false) : "";

				$po_period    = '<div class="form-group m-b-0"><input class="form-control po_period input-daterange-datepicker input-sm" type="text" id="po_period-'.$number.'" data-id="'.$number.'" value="'.$po_period_val.'"/></div>';

				$disabled = '';

				$action_split = '<button id="action_split-'.$number.'" data-id="'.$number.'" class="btn btn-sm btn-info border-radius-5 w-100p action-split" type="button"><i class="fa fa-copy"></i> Split</button>';

				if($vendor_name != ""){

					$optVendor = '<option value="0" data-no_rek="" data-nama_rek="" data-bank="">-- Select Vendor --</option>';

					foreach ($vendorArr as $k => $vVendor) {

						$vendor   = $vVendor['NAMA_VENDOR'];
						$bank     = $vVendor['NAMA_BANK'];
						$nama_rek = $vVendor['NAMA_REKENING'];
						$no_rek   = $vVendor['ACCT_NUMBER'];

						$selected = ($vendor == $vendor_name) ? ' selected' : '';

						$optVendor .= '<option value="'.$vendor.'" data-no_rek="'.$no_rek.'" data-nama_rek="'.$nama_rek.'" data-bank="'.$bank.'"'.$selected.'>'.$vendor.'</option>';
					}
				}

				$vendor_opt = '<div class="form-group m-b-0"><select id="vendor_name-'.$number.'" class="form-control input-sm vendor_name select2 select-center"'.$disabled.'>'.$optVendor.'</select></div>';

				
				$nominal_pr = ($value['PR_VERSION'] > 1) ? $value['PR_AMOUNT'] : $value['PR_LINE_AMOUNT'];

				$row[] = array(
							'po_line_key'                => "k".strtolower(generateRandomString(5)),
							'no'                         => $number,
							'po_line_id'                 => $value['PO_LINE_ID'],
							'pr_name'                    => $value['PR_LINE_NAME'],
							'pr_amount'                  => number_format($nominal_pr,0,',','.'),
							'po_number'                  => $po_number,
							'po_line_name'               => $po_line_name,
							'po_amount'                  => $po_amount,
							'po_period'                  => $po_period,
							'vendor_name'                => $vendor_opt,
							'nama_bank'                  => $bank_name_val,
							'vendor_bank_name'           => $bank_name,
							'vendor_bank_account'        => $account_name,
							'vendor_bank_account_number' => $account_number,
							'action_split'               => $action_split,
							'po_number_edit'             => $value['PO_NUMBER']
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

	public function load_pr_detail_for_po(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_lines_id = $this->input->post('pr_lines_id');
		
		$get_all  = $this->purchase->get_pr_detail_for_po($pr_lines_id);
		$data     = $get_all['data'];
		$total    = $get_all['total_data'];
		$start    = $this->input->post('start');
		$dot = $this->input->post('dot');
		$number   = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$number 	= $value['PR_DETAIL_NUMBER'];

				if($value['DESCRIPTION_PO'] != ""){
					$item_desc 	= $value['ITEM_NAME'];
				}else{
					$item_desc 	= $value['PR_DETAIL_NAME'];
				}

				if($value['DESCRIPTION_PO'] != ""){
					$qty_val = $value['QTY_PO'];
				}else{
					$qty_val = $value['QUANTITY'];
				}

				if($value['DESCRIPTION_PO'] != ""){
					$po_desc = $value['DESCRIPTION_PO'];
				}else{
					$po_desc = $value['PR_DETAIL_DESC'];
				}

				if($value['DESCRIPTION_PO'] != ""){
					$po_amount_dtl = number_format($value['PO_AMOUNT_DETAIL'],0,',','.');
					if($dot != "."){
						$po_amount_dtl = number_format($value['PO_AMOUNT_DETAIL'],2,'.',',');
					}
				}else{
					$po_amount_dtl = number_format($value['PR_DETAIL_AMOUNT'],0,',','.');
					if($dot != "."){
						$po_amount_dtl = number_format($value['PR_DETAIL_AMOUNT'],2,'.',',');
					}
				}

				if($value['DESCRIPTION_PO'] != ""){
					$po_price = number_format($value['PRICE_PO'],0,',','.');
					if($dot != "."){
						$po_price = number_format($value['PRICE'],2,'.',',');
					}
				}else{
					$po_price = number_format($value['PRICE'],0,',','.');
					if($dot != "."){
						$po_price = number_format($value['PRICE'],2,'.',',');
					}
				}

				$po_desc_new           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc" value="'.$po_desc.'" readonly></div>';
				$po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc" value="'.$po_desc.'" ></div>';
				$item_desc           = '<div class="form-group m-b-0"><input id="item_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm item_desc" value="'.$item_desc.'" readonly></div>';
				
				$nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="'.$po_amount_dtl.'"></div>';
				$total_price = '<div class="form-group m-b-0"><input id="total_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm total_price text-right" type="text" value="'.$po_amount_dtl.'" readonly></div>';
				$unit_price = '<div class="form-group m-b-0"><input id="unit_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm unit_price text-right" type="text" value="'.$po_price.'"></div>';
				$qty = '<div class="form-group m-b-0"><input id="qty-'.$number.'" data-id="'.$number.'" class="form-control input-sm qty text-center" value="'.$qty_val.'" min="1" max="99999" type="number" readonly></div>';

				$row[] = array(
						'pr_lines_id'       => $value['PR_LINES_ID'],
						'pr_detail_id'      => $value['PR_DETAIL_ID'],
						'no'                => $number,
						'item_desc'         => $item_desc,
						'detail_desc'       => $po_desc_new,
						'quantity'          => $value['QUANTITY'],
						'qty_edit'          => $qty,
						'uom'               => $value['UOM'],
						'category_item'     => $value['CATEGORY_ITEM'],
						'pr_price'          => number_format($value['PR_DETAIL_AMOUNT'],0,',','.'),
						'unit_price'        => $unit_price,
						'nominal'           => number_format($value['PR_DETAIL_AMOUNT'],0,',','.'),
						'po_desc'           => $po_desc,
						'nominal_detail_po' => $nominal_detail_po,
						'total_price'       => $total_price
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


	public function load_po_reference()
	{

		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');

		$get_po_reference = $this->purchase->get_po_reference($directorat, $division, $unit);


		if(count($get_po_reference) > 0){

			$result['status'] = true;
			$po_number = "";

			foreach($get_po_reference as $row)	{

				if($po_number != strtolower($row['PO_NUMBER'])){

					$data[] = array(
								"po_number"  => $row['PO_NUMBER'],
								"po_name"    => $row['PO_LINE_DESC']
							);
				}
				
				$po_number = strtolower($row['PO_NUMBER']);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }


    public function edit_po($po_header_id){

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$decrypt      = decrypt_string($po_header_id, true);
			$po_header_id = (int) $decrypt;

			$check_exist = $this->crud->check_exist("PO_HEADER", array("PO_HEADER_ID" => $po_header_id));


			if($check_exist > 0){

				$get_po_header = $this->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $po_header_id));
				$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $get_po_header['ID_PR_HEADER_ID']));

				$data['title']          = "Edit PO ".$get_po_header['PO_NUMBER'];
				$data['module']         = "datatable";
				$data['template_page']  = "pr_po/po_edit";
				
				$data['pr_header_id']   = $get_po_header['ID_PR_HEADER_ID'];
				$data['po_header_id']   = $po_header_id;
				$data['pr_number']      = $get_pr_header['PR_NUMBER'];
				$data['pr_name']        = $get_pr_header['PR_NAME'];
				// $data['po_name']     = $get_po_header['PO_NAME'];
				$data['pr_link']        = base_url("purchase-requisition/") . encrypt_string($get_pr_header['PR_HEADER_ID'], true);
				$data['pr_amount']      = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
				$data['po_type']        = $get_po_header['PO_TYPE'];
				$data['po_reference']   = $get_po_header['PO_REFERENCE'];
				$data['po_date']        = dateFormat($get_po_header['PO_DATE'], 5, false);
				$data['po_amount']      = number_format($get_po_header['PO_AMOUNT'],0,',','.');
				$data['po_currency']    = $get_po_header['CURRENCY'];
				$data['po_directorat']  = $get_pr_header['ID_DIR_CODE'];
				$data['po_division']    = $get_pr_header['ID_DIVISION'];
				$data['po_unit']        = $get_pr_header['ID_UNIT'];
				$data['po_status']      = $get_po_header['STATUS'];
				$data['mpa_reference']  = $get_po_header['MPA_REFERENCE'];
				$data['po_type']        = $get_po_header['PO_TYPE'];
				$data['po_category']    = $get_po_header['PO_CATEGORY'];
				$data['po_reference']   = $get_po_header['PO_REFERENCE'];
				$data['top']            = $get_po_header['TOP'];
				$data['notes']          = $get_po_header['NOTES'];
				$data['est_date']       = $get_po_header['ESTIMATE_DATE'];
				$last_update            = ($get_po_header['UPDATED_DATE']) ? $get_po_header['UPDATED_DATE'] : $get_po_header['CREATED_DATE'];
				$data['po_last_update'] = dateFormat($last_update, "with_day", false);

				$data['po_attachment']  = $get_po_header['DOCUMENT_SOURCING'];
				$data['po_status_desc'] = $get_po_header['STATUS_DESCRIPTION'];

				$po_buyer               = ($get_po_header['BUYER']) ? $get_po_header['BUYER'] : '-';
				$data['po_buyer']       = $po_buyer;

				$po_history = get_po_history($po_header_id);

				$data['po_history'] = $po_history;

				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => "Purchase Order", "link" => base_url('purchase-order'), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'PO Not Exist');
				redirect('purchase-order');
				
			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

    public function save_po(){
		
		$pr_header_id      = $this->input->post('pr_header_id');
		$data_po           = $this->input->post('data_po');
		$po_amount         = $this->input->post('po_amount');
		$currency          = $this->input->post('currency');
		$rate              = $this->input->post('rate');
		$po_reference      = $this->input->post('po_reference');
		$attachment        = $this->input->post('attachment');
		$attachment_clausa = $this->input->post('attachment_clausa');
		$mpa 			   = $this->input->post('mpa');
		$est_date 		   = $this->input->post('est_date');
		$top 		   	   = $this->input->post('top');
		$notes 		   	   = $this->input->post('notes');
		$po_category       = $this->input->post('po_category');

		// echo_pre($_POST);die;
		
		$submitter = get_user_data($this->session->userdata('user_id'));

		$total_amount = (int) $po_amount;
		$rate_rpl = str_replace(".","",$rate);

		$data = array(
						"ID_PR_HEADER_ID"    => $pr_header_id,
						"PO_AMOUNT"          => $total_amount,
						"STATUS"             => 'request_approve',
						"CURRENCY"           => $currency,
						"CURRENCY_RATE"      => $rate_rpl,
						"BUYER"              => $submitter,
						"BUYER_EMAIL"        => $this->user_email,
						"PO_CATEGORY"        => $po_category,
						"MPA_REFERENCE"      => $mpa,
						"TOP"      			 => $top,
						"NOTES"      		 => $notes,
						"STATUS_DESCRIPTION" => "Created by ".$submitter
					);

		if($est_date != ""){
			$data['ESTIMATE_DATE'] = $est_date;
		}

		if($po_reference != "0"){
			$data['PO_TYPE']      = "Additional";
			$data['PO_REFERENCE'] = $po_reference;
		}
		else{
			$data['PO_TYPE'] = "Normal";
		}

		if($attachment){
			$data['DOCUMENT_SOURCING'] = $attachment;
		}


		if($attachment_clausa){
			$data['DOCUMENT_CLAUSE'] = $attachment_clausa;
		}

		$insert   = $this->crud->create("PO_HEADER", $data);
		$level         = 1;
		$data_approval = array();
		$get_hou_buyer = $this->feasibility_study->get_data_approval("HoU Procurement");
		$get_hog_proc  = $this->feasibility_study->get_data_approval("HoG Procurement");

		if($get_hou_buyer){
			$recipient['email'] = $get_hou_buyer['PIC_EMAIL'];
			$recipient['name']  = $get_hou_buyer['PIC_NAME'];
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "HoU Buyer", "ID_APPROVAL" => $get_hou_buyer['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
		}

		$get_dir_div = $this->crud->read_by_param_specific("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id), "ID_DIR_CODE, ID_DIVISION, ID_UNIT");
		$directorat = $get_dir_div[0]['ID_DIR_CODE'];
		$division   = $get_dir_div[0]['ID_DIVISION'];
		$unit       = $get_dir_div[0]['ID_UNIT'];

		$directorat_name = strtolower(get_directorat($directorat));
		$division_name   = strtolower(get_division($division));

		$get_by_unit  = ($division_name == "new business") ? $unit : false;
		$get_hog_user = false;
		if($division != 17){
			$get_hog_user = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);
		}

		$get_director = false;

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

		$get_cfo = $this->feasibility_study->get_data_approval("CFO");

		if($po_category == "PO Template"):
			if($total_amount <= 300000000):

				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
			elseif($total_amount > 300000000 && $total_amount <= 3000000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
				if($directorat_name == "ceo office"){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}else{
					if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
					}
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
			elseif($total_amount > 3000000000 && $total_amount <= 25000000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}

				if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);

				if($directorat_name == "ceo office"){
					$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
				}else{
					$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorat);
				}
				foreach ($get_bod as $key => $value) {
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $value['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
			endif;

		elseif($po_category == "Non Template"):
			$get_hou_legal = $this->feasibility_study->get_data_approval("Submitter", 6, 51,204);
			$get_hou_legal = $get_hou_legal[0];
			$get_hog_legal = $this->feasibility_study->get_data_approval("HOG User", 6, 51);
			if($total_amount <= 300000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoU Legal", "ID_APPROVAL" => $get_hou_legal['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Legal", "ID_APPROVAL" => $get_hog_legal['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
			else:
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoU Legal", "ID_APPROVAL" => $get_hou_legal['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Legal", "ID_APPROVAL" => $get_hog_legal['ID_APPROVAL'], "PO_HEADER_ID" => $insert);


				if($total_amount <= 3000000000):
					if($directorat_name == "ceo office"){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
					}else{
						if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
							$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
						}
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
					}
				elseif($total_amount > 3000000000 && $total_amount <= 25000000000):
					if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
					}
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);

					if($directorat_name == "ceo office"){
						$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
					}else{
						$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorat);
					}
					foreach ($get_bod as $key => $value) {
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $value['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
					}
				endif;

			endif;


		elseif($po_category == "MPA"):

		$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
		$get_ceo = $this->feasibility_study->get_data_approval("CEO");

			if($total_amount > 300000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
			endif;
			if($total_amount > 3000000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_ceo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
			endif;
			if($total_amount > 25000000000):
				// $data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_ceo['ID_APPROVAL'], "PO_HEADER_ID" => $insert);
			endif;

		else:
			$data_approval = array();
		endif;
		$send_email = false;
		if(count($data_approval) > 0):
			$send_email      = true;
			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_PO", $data_approval);
			$id_approval     = $this->db->insert_id();
			$dataPoUpd       = array("APPROVAL_LEVEL" => count($data_approval));
		endif;

		$status   = false;
		$messages = "";

		if($insert > 0){

			$pr_line_number = 1;
			foreach ($data_po as $key => $value) {

				$exp_po_date = explode(" - ", $value['po_period']);
				$date_from = date_db($exp_po_date[0]);
				$date_to   = date_db($exp_po_date[1]);

				$amount = str_replace(".", "", $value['po_amount']);

				$data_lines = array(
										"PO_HEADER_ID"             => $insert,
										"ID_RKAP_LINE"             => $value['id_rkap_line'],
										"PR_LINES_ID"              => $value['pr_lines_id'],
										"PO_NUMBER"                => $value['po_number'],
										"PO_LINE_DESC"             => $value['po_line_name'],
										"PO_LINE_AMOUNT"           => (int) $amount,
										"PO_PERIOD_FROM"           => $date_from,
										"PO_PERIOD_TO"             => $date_to,
										"VENDOR_NAME"              => $value['vendor_name'],
										"VENDOR_BANK_NAME"         => $value['bank_name'],
										"VENDOR_BANK_ACCOUNT_NAME" => $value['account_name'],
										"VENDOR_BANK_ACCOUNT"      => $value['account_number']
									);

				$insert_line = $this->crud->create("PO_LINES", $data_lines);

				if($insert_line > 0){

					if(isset($value['detail_po'])){
						$detail_po = $value['detail_po'];
					}else{
						$detail_po = $this->crud->read_specific("PR_DETAIL",
										array(
												"PR_DETAIL_ID pr_detail_id",
												"PR_DETAIL_NAME item_desc",
												"PR_DETAIL_DESC po_desc",
												"QUANTITY qty",
												"CATEGORY_ITEM category_item",
												"UOM uom",
												"PRICE unit_price",
												"PR_DETAIL_AMOUNT total_price"
											),
							array("PR_LINES_ID" => $value['pr_lines_id']));
					}

					$po_detail_number = 1;

					$replacement = (strtolower($currency) != "idr") ? "," : ".";
					foreach ($detail_po as $key => $value_dtl) {

						$price_rpl       = str_replace($replacement,"", $value_dtl['unit_price']);
						$total_price_rpl = str_replace($replacement,"", $value_dtl['total_price']);

						$data_details[] = array(
											"PO_HEADER_ID"     => $insert,
											"PO_LINE_ID"       => $insert_line,
											"PR_DETAIL_ID"     => $value_dtl['pr_detail_id'],
											"Po_DETAIL_NUMBER" => $po_detail_number,
											"ITEM_NAME" 	   => $value_dtl['item_desc'],
											"CATEGORY_ITEM"    => $value_dtl['category_item'],
											"DESCRIPTION_PO"   => $value_dtl['po_desc'],
											"UOM"              => $value_dtl['uom'],
											"QUANTITY" 	   	   => $value_dtl['qty'],
											"PRICE" 	   	   => $price_rpl,
											"PO_DETAIL_AMOUNT" => $total_price_rpl,
											"CREATED_BY"       => get_user_data($this->session->userdata('user_id'))
										);
						$po_detail_number++;
					}

				}

			}

			$insert_detail = $this->crud->create_batch("PO_DETAIL", $data_details);

			$data_pr_update = array("STATUS" => "po created", "UPDATED_BY" => get_user_data($this->session->userdata('user_id')));
			$update_pr_status = $this->crud->update("PR_HEADER", $data_pr_update, array("PR_HEADER_ID" => $pr_header_id));

			if($insert_detail > 0 && $update_pr_status !== -1){
				$status    = true;
				if($send_email){
					$cc_email = $this->user_cc;
					$email_cc[] = $cc_email['susanto'];
					$email_cc[] = $cc_email['dita'];
					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}
					$this->_email_aprove($recipient, $insert, $id_approval);
				}
			}else{
				$messages = "Failed to Create PO Detail";
			}

		}
		else{
			$messages = "Failed to Create PO";
		}

		
		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

    public function save_po_edit(){
		
		$po_header_id = $this->input->post('po_header_id');
		$pr_header_id = $this->input->post('pr_header_id');
		$data_po      = $this->input->post('data_po');
		$po_amount    = $this->input->post('po_amount');
		$mpa       	  = $this->input->post('mpa');
		$est_date     = $this->input->post('est_date');
		$top       	  = $this->input->post('top');
		$notes        = $this->input->post('notes');
		$updated_by   = $this->session->userdata('USERNAME');
		$po_category  = $this->input->post('po_category');

		$attachment        = $this->input->post('attachment');
		
		$total_amount = (int) $po_amount;
		$pic_email    = $this->session->userdata('email');
		$submitter = get_user_data($this->session->userdata('user_id'));


		$data = array(
						"PO_AMOUNT"          => $total_amount,
						"RESUBMIT_BY"        => $submitter,
						"RESUBMIT_EMAIL"     => $pic_email,
						"MPA_REFERENCE"      => $mpa,
						"TOP"                => $top,
						"NOTES"              => $notes,
						"STATUS"             => "request_approve",
						"STATUS_DESCRIPTION" => "Resubmitted by " . $submitter,
						"PO_RESUBMIT_DATE"   => date("Y-m-d H:i:s", time())
					);

		if($est_date != ""){
			$data['ESTIMATE_DATE'] = $est_date;
		}

		if($attachment){
			$data['DOCUMENT_SOURCING'] = $attachment;
		}

		// echo_pre($data);die;

		$update   = $this->crud->update("PO_HEADER", $data, array("PO_HEADER_ID" => $po_header_id));
		// $update = 1;

		$level         = 1;
		$data_approval = array();
		$get_hou_buyer = $this->feasibility_study->get_data_approval("HoU Procurement");
		$get_hog_proc  = $this->feasibility_study->get_data_approval("HoG Procurement");

		if($get_hou_buyer){
			$recipient['email'] = $get_hou_buyer['PIC_EMAIL'];
			$recipient['name']  = $get_hou_buyer['PIC_NAME'];
			$data_approval[] = array("LEVEL" => $level++, "STATUS" => "request_approve", "CATEGORY" => "HoU Buyer", "ID_APPROVAL" => $get_hou_buyer['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
		}

		$get_dir_div = $this->crud->read_by_param_specific("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id), "ID_DIR_CODE, ID_DIVISION, ID_UNIT");
		$directorat = $get_dir_div[0]['ID_DIR_CODE'];
		$division   = $get_dir_div[0]['ID_DIVISION'];
		$unit       = $get_dir_div[0]['ID_UNIT'];

		$directorat_name = strtolower(get_directorat($directorat));
		$division_name   = strtolower(get_division($division));

		$get_by_unit  = ($division_name == "new business") ? $unit : false;

		$get_hog_user = false;
		if($division != 17){
			$get_hog_user = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division, $get_by_unit);
		}

		$get_director = false;

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

		$get_cfo = $this->feasibility_study->get_data_approval("CFO");

		if($po_category == "PO Template"):
			if($total_amount <= 300000000):
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
			elseif($total_amount > 300000000 && $total_amount <= 3000000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
				if($directorat_name == "ceo office"){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}else{
					if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
					}
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
			elseif($total_amount > 3000000000 && $total_amount <= 25000000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
				if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);

				if($directorat_name == "ceo office"){
					$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
				}else{
					$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorat);
				}
				foreach ($get_bod as $key => $value) {
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $value['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
			endif;

		elseif($po_category == "Non Template"):
			$get_hou_legal = $this->feasibility_study->get_data_approval("Submitter", 6, 22, 59)[0];
			$get_hog_legal = $this->feasibility_study->get_data_approval("HOG User", 6, 22, 59);
			if($total_amount <= 300000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoU Legal", "ID_APPROVAL" => $get_hou_legal['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Legal", "ID_APPROVAL" => $get_hog_legal['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
			else:
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				if($get_hog_user){
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG User", "ID_APPROVAL" => $get_hog_user['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				}
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoU Legal", "ID_APPROVAL" => $get_hou_legal['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Legal", "ID_APPROVAL" => $get_hog_legal['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);


				if($total_amount <= 3000000000):
					if($directorat_name == "ceo office"){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
					}else{
						if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
							$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
						}
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
					}
				elseif($total_amount > 3000000000 && $total_amount <= 25000000000):
					if($get_director && ($directorat_name == "technology" || $directorat_name == "marketing" || $directorat_name == "operation")){
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "Director", "ID_APPROVAL" => $get_director['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
					}
					$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);

					if($directorat_name == "ceo office"){
						$get_bod = $this->feasibility_study->get_data_approval("BOD","","","");
					}else{
						$get_bod = $this->feasibility_study->get_data_approval("BOD","","","", $directorat);
					}
					foreach ($get_bod as $key => $value) {
						$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "BOD", "ID_APPROVAL" => $value['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
					}
				endif;

			endif;


		elseif($po_category == "MPA"):

		$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "HoG Procurement", "ID_APPROVAL" => $get_hog_proc['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
		$get_ceo = $this->feasibility_study->get_data_approval("CEO");

			if($total_amount > 300000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CFO", "ID_APPROVAL" => $get_cfo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
			endif;
			if($total_amount > 3000000000):
				$data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_ceo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
			endif;
			if($total_amount > 25000000000):
				// $data_approval[] = array("LEVEL" => $level++, "STATUS" => NULL, "CATEGORY" => "CEO", "ID_APPROVAL" => $get_ceo['ID_APPROVAL'], "PO_HEADER_ID" => $po_header_id);
			endif;

		else:
			$data_approval = array();
		endif;

		$send_email = false;
		if(count($data_approval) > 0):
			$this->crud->update("TRX_APPROVAL_PO", array("IS_ACTIVE" => 0), array("PO_HEADER_ID" => $po_header_id));
			$send_email      = true;
			$insert_approval = $this->crud->create_batch("TRX_APPROVAL_PO", $data_approval);
			$id_approval     = $this->db->insert_id();
			$dataPoUpd       = array("APPROVAL_LEVEL" => count($data_approval));
		endif;

		$status   = false;
		$messages = "";

		if($update !== -1){

			foreach ($data_po as $key => $value) {

				$exp_po_date = explode(" - ", $value['po_period']);
				$date_from = date_db($exp_po_date[0]);
				$date_to   = date_db($exp_po_date[1]);
				$amount    = str_replace(",", "", $value['po_amount']);

				$data_lines = array(
										"PO_NUMBER"                => $value['po_number'],
										"PO_LINE_DESC"             => $value['po_line_name'],
										"PO_LINE_AMOUNT"           => $amount,
										"PO_PERIOD_FROM"           => $date_from,
										"PO_PERIOD_TO"             => $date_to,
										"VENDOR_NAME"              => $value['vendor_name'],
										"VENDOR_BANK_NAME"         => $value['bank_name'],
										"VENDOR_BANK_ACCOUNT_NAME" => $value['account_name'],
										"VENDOR_BANK_ACCOUNT"      => $value['account_number'],
										"UPDATED_BY"               => $updated_by
									);

				$update_lines = $this->crud->update("PO_LINES", $data_lines, array("PO_LINE_ID" => $value['po_line_id']));

				if (isset($value['detail_po'])){

					if($update_lines !== -1 ){
						$detail_po    = $value['detail_po'];
						$data_details = array();

						foreach ($detail_po as $key => $value_dtl) {

							$data_details = array(
												"DESCRIPTION_PO"   => $value_dtl['po_desc'],
												"PO_DETAIL_AMOUNT" => $value_dtl['total_price'],
												"ITEM_NAME" 	   => $value_dtl['item_desc'],
												"QUANTITY" 	   	   => $value_dtl['qty'],
												"PRICE" 	   	   => $value_dtl['unit_price'],
												"UPDATED_BY"       => $updated_by
											);

							$this->crud->update("PO_DETAIL", $data_details, array("PO_DETAIL_ID" => $value_dtl['po_detail_id']));
						}
					}
				}
			}


			if($send_email){
				$this->_email_aprove($recipient, $po_header_id, $id_approval);
			}

			$status = true;
		}
		else{
			$messages = "Failed to Edit PO";
		}

		
		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

    public function get_vendor(){

    	$vendor = get_all_vendor();
		foreach ($vendor as $key => $v)
		{
		    $sort[$key] = $v['NAMA_VENDOR'];
		}
		array_multisort($sort, SORT_ASC, $vendor);
		
		foreach ($vendor as $key => $val) {
			$vendorArr[] = array(
									"nama_vendor"   => $val['NAMA_VENDOR'],
									"nama_bank"     => $val['NAMA_BANK'],
									"nama_rekening" => $val['NAMA_REKENING'],
									"acct_number"   => $val['ACCT_NUMBER']
								);
		}

		echo json_encode($vendorArr);
    }


    function download_po($param="")
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
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "PO Number");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "PO Name");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "PO Date");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Total Amount");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Status");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Vendor Name");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Vendor Bank Name");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Vendor Bank Account");


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
				$po_date     = $obj_param->po_date;

				if($po_date){
					$exp_po_date = explode(" - ", $po_date);
					$date_from = date_db($exp_po_date[0]);
					$date_to   = date_db($exp_po_date[1]);
				}
			}
		}

		$hasil = $this->purchase->get_download_po($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);

		$numrow  = 2;
		$number = 1;

		foreach($hasil->result_array() as $row)	{

			$po_date = date("d-m-Y",strtotime($row['PO_DATE']));
			$curr = strtoupper($row['CURRENCY']);

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['PR_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['PO_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['PO_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $po_date);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $curr);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['TOTAL_AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['STATUS']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['VENDOR_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['VENDOR_BANK_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['VENDOR_BANK_ACCOUNT']);

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

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Purchase Order.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}



	public function change_status_po(){

		$po_header_id  = $this->input->post('po_header_id');
		$pr_header_id  = $this->input->post('pr_header_id');
		$status        = $this->input->post('status');
		$cancel_reason = $this->input->post('cancel_reason');
		$attachment    = $this->input->post('attachment');

		$result['status']   = false;
		$result['messages'] = "Failed to change status";

		$data = array(
						"STATUS" => $status
					);

		$send_email = false;
		
		$changeStatus = $this->crud->update("PO_HEADER", $data, array("PO_HEADER_ID" => $po_header_id));

		if($status == "canceled"){

			$cancel_reason = ($cancel_reason != "") ? $cancel_reason : "Rejected by Procurement";
			$dataPR = array("STATUS" => "rejected", "STATUS_DESCRIPTION" => $cancel_reason);

			if($attachment){
				$dataPR['DOCUMENT_ATTACHMENT'] = $attachment;
			}

			$get_submitter   = $this->purchase->get_submitter_by_id_pr($pr_header_id);
			$submitter_email = "";
			$submitter_name  = "";

			if($get_submitter){
				$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
				$submitter_name  = $get_submitter['SUBMITTER'];
			}

			if($submitter_email != "" && $submitter_name != ""){
				$recipient['email'] = $submitter_email;
				$recipient['name']  = $submitter_name;
				$send_email = true;
			}

		}
		else{
			$dataPR = array("STATUS" => "po created");
		}


		if($changeStatus !== -1 && $this->crud->update("PR_HEADER", $dataPR, array("PR_HEADER_ID" => $pr_header_id)) !== -1 ){

			if($send_email){
				$this->_email_cancel_pr($recipient, $pr_header_id, $cancel_reason);
			}
			$result['status']   = true;
			$result['messages'] = "Status successfully changed";
		}

		echo json_encode($result);
	}

	public function return_pr(){

		$pr_header_id = $this->input->post('pr_header_id');
		$notes_user   = $this->input->post('notes_user');
		$attachment   = $this->input->post('attachment');

		$result['status']   = false;
		$result['messages'] = "Failed to return PR";

		$data = array(
						"STATUS"             => "returned",
						"STATUS_DESCRIPTION" => "Returned by Procurement",
						"REMARK_RETURN"      => $notes_user,
						"PIC_RETURN"         => $this->user_email,
						"RETURN_DATE"        => date("Y-m-d h:i:s", time())
					);

		if($attachment){
			$data['DOCUMENT_RETURN'] = $attachment;
		}

		$send_email = false;
		$update = $this->crud->update("PR_HEADER", $data, array("PR_HEADER_ID" => $pr_header_id));

		$get_submitter   = $this->purchase->get_submitter_by_id_pr($pr_header_id);
		$submitter_email = "";
		$submitter_name  = "";

		if($get_submitter){
			$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : $get_submitter['ALAMAT_EMAIL'];
			$submitter_name  = $get_submitter['SUBMITTER'];
		}

		if($submitter_email != "" && $submitter_name != ""){
			$recipient['email'] = $submitter_email;
			$recipient['name']  = $submitter_name;
			$send_email = true;
		}


		if($update){

			if($send_email){
				$this->_email_cancel_pr($recipient, $pr_header_id, $notes_user, true, $attachment);
			}
			$result['status']   = true;
			$result['messages'] = "PR successfully returned";
		}

		echo json_encode($result);
	}


	public function delete_po(){

		$id       = $this->input->post('id');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$get_po_header = $this->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $id));
		$pr_header_id  = $get_po_header['ID_PR_HEADER_ID'];
		$delete        = $this->crud->delete("PO_HEADER", array("PO_HEADER_ID" => $id));

			/*if($delete > 0 && $this->crud->update("PR_HEADER", array("STATUS" => "Approved"), array("PR_HEADER_ID" => $pr_header_id)) !== -1 ){
				$delete = 1;
			}
			else{
				$delete = 0;
			}*/

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}



	private function _email_cancel_pr($recipient, $id_pr, $remark, $return=false, $attachment=""){

		$get_pr = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $id_pr));
		
		$pr_name    = $get_pr['PR_NAME'];
		$pr_number  = $get_pr['PR_NUMBER'];

		$pr_link = base_url("purchase-requisition/").encrypt_string($id_pr, true);

		$data['pr_number']       = $pr_number;
		$data['pr_link']         = $pr_link;
		$data['pr_link_name']    = $pr_number ." - " . $pr_name;
		$data['remark']          = $remark;
		$data['email_recipient'] = $recipient['name'];

		$statusPR = ($return == true) ? "returned" : "canceled/rejected";

		$data['email_body'] = "Your PR <b>$pr_number </b> has been <b>$statusPR</b> by Procurement Team. You can see all the details about this PR by clicking the link below.";
		$data['email_preview'] = "Your PR $pr_number has been $statusPR by Procurement Team";
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$subject    = "PR $statusPR by Procurement ($pr_number - $pr_name)";
		$body       = $this->load->view('email/pr_cancellation', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/po_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }


	private function _email_aprovex($recipient, $id_po, $id_approval){

		$get_pr = $this->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $id_pr));
		
		$pr_name    = $get_pr['PR_NAME'];
		$pr_number  = $get_pr['PR_NUMBER'];
		$attachment = $get_pr['DOCUMENT_ATTACHMENT'];
		$action_name = get_user_data($this->session->userdata('user_id'));


		$data['po_number']       = $po_number;
		$data['po_link']         = base_url("purchase-order");
		$data['po_link_name']    = $po_number ." - " . $po_name;
		$data['remark']          = $remark;
		$data['email_recipient'] = $recipient['name'];
		$data['email_preview']   = "A new PO $po_number has been submitted by $action_name and need your approval";
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "PO $po_number Submitted as a Draft  by Procurement ($pr_number - $pr_name)";
		$body       = $this->load->view('email/po_request_approve', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/pr_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }

    private function _email_aprove($recipient, $id_po, $id_approval){

		$get_po  = $this->purchase->get_po_for_email($id_po);
		$po_head = $get_po[0];
		$po_head = $get_po[0];

		// $currency      = ($get_po['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount     = number_format($po_head['PO_AMOUNT'],0,',','.');
		$po_number  = $po_head['PO_NUMBER'];
		$po_desc    = $po_head['PO_LINE_DESC'];
		$attachment = $po_head['DOCUMENT_SOURCING'];
		$submitter  = $po_head['BUYER'];
		// $currency_rate = ($po_head['CURRENCY'] == "IDR") ? $po_head['CURRENCY'] : $po_head['CURRENCY'] ."/". number_format($po_head['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("po/approval/").encrypt_string($id_po."-".$id_approval, true);

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = "There's new Purchase Order $po_number - $po_desc waiting for your approval.";

		$email_body = "There's new Purchase Order $po_number - $po_desc waiting for your approval.
								<br>
								<br>
								Detail PO:
								<br>
								  <div class='hack1'>
								  	<div class='hack2'>
										<table class='custom_table'>
											<tbody>
												<tr>
														<th>No</th>
														<th>Item Name</th>
														<th>Item Description</th>
														<th>QTY</th>
														<th>UoM</th>
														<th>Unit Price</th>
														<th>Total Price</th>
												</tr>";

		$no = 1;
		foreach ($get_po as $key => $value) {

			$item_name   = $value['ITEM_NAME'];
			$item_desc   = $value['ITEM_DESC'];
			$qty         = $value['QUANTITY'];
			$uom         = $value['UOM'];
			$unit_price  = number_format($value['UNIT_PRICE'],0,'.',',');
			$total_price = number_format($value['TOTAL_PRICE'],0,'.',',');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$item_name."</td>";
				$email_body .= "<td>".$item_desc."</td>";
				$email_body .= "<td>".$qty."</td>";
				$email_body .= "<td>".$uom."</td>";
				$email_body .= "<td>".$unit_price."</td>";
				$email_body .= "<td>".$total_price."</td>";
			$email_body .= "</tr>";
			$no++;
		}

		$email_body .= 				"</tbody>
								</table></div></div>";

		$data['email_body'] = $email_body;
		$data['approval_link_all'] = base_url("po/approval");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "New PO $po_number submitted";
		$body       = $this->load->view('email/po_request_approve', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/po_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
	}

    public function printPDF($po_header_id){

    	ob_start();

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$decrypt      = decrypt_string($po_header_id, true);
		$po_header_id = (int) $decrypt;

		$mpdf = new \Mpdf\Mpdf();

		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'iso-8859-4';

		$fh = 'assets/templates/justification.pdf';

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);
		$mpdf->Image('assets/img/fintek.jpg',15,10,35);

		$mpdf->SetTextColor(0,0,0);
		$mpdf->SetFont('Courier New','B',10);

		$data = $this->purchase->get_header_po($po_header_id);

		$guideline = 0;

		$po_number = $data['PO_NUMBER'];
		$notes 	   = $data['NOTES'];
		$po_template = $data['PO_CATEGORY'];
		$doc_clause  = $data['DOCUMENT_CLAUSE'];

		$mpdf->Cell(0, 9, "Purchase Order", 0, true, 'C');
		$mpdf->Cell(0, 0, $data['PO_NUMBER'], 0, true, 'C');

		$titikdua = 41;

		$mpdf->SetFont('Roboto','',6);
		$height = 40;
		$mpdf->SetXY(18, $height);
		$mpdf->Cell(10, 10, "Company",0,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua+1, $height);
		$mpdf->Cell(10, 10, $data['NAMA_VENDOR'],$guideline,"L");

		$address_ln = strlen($data['ALAMAT']);

		if($address_ln <= 60){
			$height = 43;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Address",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ALAMAT'],0,60),$guideline,"L");
		}else{
			$height = 43;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Address",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ALAMAT'],0,60),$guideline,"L");

			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, "",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ALAMAT'],60,60),$guideline,"L");
		}

		$height = $height+3;
		$mpdf->SetXY(18, $height);
		$mpdf->Cell(10, 10, "Phone & Mobile Phone",0,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua+1, $height);
		$mpdf->Cell(10, 10, $data['NO_TLP'],$guideline,"L");

		$height = $height+3;
		$mpdf->SetXY(18, $height);
		$mpdf->Cell(10, 10, "Contact Name",0,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua+1, $height);
		$mpdf->Cell(10, 10, "",$guideline,"L");

		$mpa_ln = strlen($data['MPA_REFERENCE']);
		if($mpa_ln <=60){
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Contract / MPA No",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['MPA_REFERENCE'],0,60),$guideline,"L");
		}else{
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Contract / MPA No",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['MPA_REFERENCE'],0,60),$guideline,"L");

			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, "",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['MPA_REFERENCE'],60,60),$guideline,"L");
		}

		$est_ln = strlen($data['ESTIMATE_DATE']);
		if($est_ln <=60){
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Estimate Delivery Date",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ESTIMATE_DATE'],0,60),$guideline,"L");
		}else{
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Estimate Delivery Date",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ESTIMATE_DATE'],0,60),$guideline,"L");

			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, "",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ESTIMATE_DATE'],60,60),$guideline,"L");
		}

		$titikdua2 = 128;

		$mpdf->SetFont('Roboto','',6);

		$status_po = strtolower($data['STATUS']);
		$po_date = ($status_po == "approved" || $status_po == "partial paid" || $status_po== "paid") ?  date("F jS, Y", strtotime($data['PO_APPROVED'])) : "";
		$height = 37;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Date",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, $po_date,$guideline,"L");

		$height = 40;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Bill To",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10,"PT. Fintek Karya Nusantara",$guideline,"L");

		$height = 43;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Address",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, "Treasury Tower 31st Floor, District 8, SCBD Lot 28, Jl. Jend. Sudirman Kav. 52-53",$guideline,"L");
		$mpdf->SetXY($titikdua2+1, $height+3);
		$mpdf->Cell(10, 10, "Jakarta Selatan, DKI Jakarta 12190, Indonesia",$guideline,"L");

		$height = 49;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Phone/fax",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, "62-21-50880130",$guideline,"L");

		$top_trim = trim($data['TOP']);
		$term_ln = strlen($top_trim);
		$top_ln_max = 70;
		$height     = 52;
		$str_1      ="";
		$str_2      ="";
		$str_3      ="";
		for ($i=1; $i <= 3 ; $i++) {
			$line_2 = false;
			$line_3 = false;
			if($i == 1){
				$str_1 = substr($top_trim,0,$top_ln_max);
			}
			if($i == 2){
				$rplc =  trim(str_replace($str_1,"", $top_trim));
				$str_2 = substr($rplc,0,$top_ln_max);
				$line_2 = ($str_2 != "") ? true: false;
			}
			if($i == 3){
				$rplc =  trim(str_replace(array($str_1,$str_2),"", $top_trim));
				$str_3 = substr($rplc,0,$top_ln_max);
				$line_3 = ($str_3 != "") ? true: false;
			}

			if($i == 1){
				$mpdf->SetXY(110, $height);
				$mpdf->Cell(10, 10, "Term Of Payment",0,"L");
				$mpdf->SetXY($titikdua2, $height);
				$mpdf->Cell(10, 10, ":",$guideline,"R");
				$mpdf->SetXY($titikdua2+1, $height);
				$mpdf->Cell(10, 10, $str_1,$guideline,"L");
			}

			if( $line_2 ){
				$height = $height+3;
				$mpdf->SetXY(110, $height);
				$mpdf->SetXY($titikdua2, $height);
				$mpdf->Cell(10, 10, "",$guideline,"R");
				$mpdf->SetXY($titikdua2+1, $height);
				$mpdf->Cell(10, 10, $str_2,$guideline,"L");
			}
			
			if( $line_3 ){
				$height = $height+3;
				$mpdf->SetXY(110, $height);
				$mpdf->SetXY($titikdua2, $height);
				$mpdf->Cell(10, 10, "",$guideline,"R");
				$mpdf->SetXY($titikdua2+1, $height);
				$mpdf->Cell(10, 10, $str_3,$guideline,"L");
			}
		}

		$height = $height+3;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Currency",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, $data['CURRENCY'],$guideline,"L");

		$hasil = $this->purchase->get_cetak_po($po_header_id);
		$data = $hasil['data'];

		// $height = $height+8;
		// $mpdf->SetXY(169, $height);
		// $mpdf->Cell(10, 10, "Currency :",0,"L");
		// $mpdf->SetXY(180, $height);
		// $mpdf->Cell(10, 10, "",0,"L");

		$height = $height+4;

		$mpdf->SetFont('Roboto','C',8);
		$mpdf->SetXY(15, $height+8);
		$mpdf->Cell(5,6,'NO',1,0,'C');
		$mpdf->Cell(78,6,'Description',1,0,'C');
		$mpdf->Cell(8,6,'QTY',1,0,'C');
		$mpdf->Cell(23,6,'UoM',1,0,'C');
		$mpdf->Cell(25,6,'Unit Price',1,0,'C');
		$mpdf->Cell(25,6,'Total Amount',1,0,'C');
		$mpdf->Cell(23,6,'Remark',1,1,'C');

		$no = 1;
		$sub_total = 0;
		$vat = 0;
		$total = 0;
		foreach ($data as $key => $value) {
			$price 			= $value['PRICE'];
			$qty 			= $value['QUANTITY'];
			$total_price 	= $price * $qty;
			$sub_total 		+= $total_price;
			// $vat 			= $sub_total * 10/100;
			$total 			= $sub_total;

			if($value['ITEM_NAME'] == $value['DESCRIPTION_PO']){
				$desc_po = $value['ITEM_NAME'];
			}else{
				if($value['ITEM_NAME']){
					$desc_po = $value['ITEM_NAME'];
					$desc_po .= ($value['DESCRIPTION_PO'] == "-" || $value['DESCRIPTION_PO'] == "") ? "" : " - ".$value['DESCRIPTION_PO'];
				}else{
					$desc_po = $value['DESCRIPTION_PO'];
				}
			}

			$desc_ln = strlen($desc_po);
			if($desc_ln <=50){
				$mpdf->Cell(5,5, $no,"B R L",0,'L');
				$mpdf->Cell(78,5, $desc_po,"B R L",0,'L');
				$mpdf->Cell(8,5, $value['QUANTITY'],"B R L",0,'C');
				$mpdf->Cell(23,5, $value['UOM'],"B R L",0,'C');
				$mpdf->Cell(25,5, number_format($value['PRICE'],0,',','.'),"B R L",0,'R');
				$mpdf->Cell(25,5, number_format($total_price,0,',','.'),"B R L",0,'R');
				$mpdf->Cell(23,5, "","B R L",1,'C');
			}
			elseif($desc_ln > 50 && $desc_ln <= 100){
				$mpdf->Cell(5,5, $no,"R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,0,50),"R L",0,'L');
				$mpdf->Cell(8,5, $value['QUANTITY'],"R L",0,'C');
				$mpdf->Cell(23,5, $value['UOM'],"R L",0,'C');
				$mpdf->Cell(25,5, number_format($value['PRICE'],0,',','.'),"R L",0,'R');
				$mpdf->Cell(25,5, number_format($total_price,0,',','.'),"R L",0,'R');
				$mpdf->Cell(23,5, "","R L",1,'C');

				$mpdf->Cell(5,5, "","B R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,50,50),"B R L",0,'L');
				$mpdf->Cell(8,5, "","B R L",0,'C');
				$mpdf->Cell(23,5, "","B R L",0,'C');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(23,5, "","B R L",1,'C');
			}else{
				$mpdf->Cell(5,5, $no,"R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,0,50),"R L",0,'L');
				$mpdf->Cell(8,5, $value['QUANTITY'],"R L",0,'C');
				$mpdf->Cell(23,5, $value['UOM'],"R L",0,'C');
				$mpdf->Cell(25,5, number_format($value['PRICE'],0,',','.'),"R L",0,'R');
				$mpdf->Cell(25,5, number_format($total_price,0,',','.'),"R L",0,'R');
				$mpdf->Cell(23,5, "","R L",1,'C');

				$mpdf->Cell(5,5, "","R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,50,50),"R L",0,'L');
				$mpdf->Cell(8,5, "","R L",0,'C');
				$mpdf->Cell(23,5, "","R L",0,'C');
				$mpdf->Cell(25,5, "","R L",0,'R');
				$mpdf->Cell(25,5, "","R L",0,'R');
				$mpdf->Cell(23,5, "","R L",1,'C');

				$mpdf->Cell(5,5, "","B R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,100,50),"B R L",0,'L');
				$mpdf->Cell(8,5, "","B R L",0,'C');
				$mpdf->Cell(23,5, "","B R L",0,'C');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(23,5, "","B R L",1,'C');
			}
			// $mpdf->Cell(78,6, $value['DESCRIPTION_PO'],"B R L",0,'L');

			$no++;
		}
		/*$mpdf->Cell(5,6, "","",0,'L');
		$mpdf->Cell(78,6, "","",0,'L');
		$mpdf->Cell(8,6, "","",0,'C');
		$mpdf->Cell(23,6, "","",0,'C');
		$mpdf->Cell(25,6, "Sub Total","B R L",0,'L');
		$mpdf->Cell(25,6, number_format($sub_total,0,',','.'),"B R L",0,'R');
		$mpdf->Cell(23,6, "","",1,'C');
*/
		/*$mpdf->Cell(5,6, "","",0,'L');
		$mpdf->Cell(78,6, "","",0,'L');
		$mpdf->Cell(8,6, "","",0,'C');
		$mpdf->Cell(23,6, "","",0,'C');
		$mpdf->Cell(25,6, "VAT 10%","B R L",0,'L');
		$mpdf->Cell(25,6, number_format($vat,0,',','.'),"B R L",0,'R');
		$mpdf->Cell(23,6, "","",1,'C');*/

		$mpdf->Cell(5,6, "","",0,'L');
		$mpdf->Cell(78,6, "","",0,'L');
		$mpdf->Cell(8,6, "","",0,'C');
		$mpdf->Cell(23,6, "","",0,'C');
		$mpdf->Cell(25,6, "Total","B R L",0,'L');
		$mpdf->Cell(25,6, number_format($total,0,',','.'),"B R L",0,'R');
		$mpdf->Cell(23,6, "","",1,'C');

		$mpdf->Cell(5,5, "","",1,'L');

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(5,5, "Delivery Detail",0,1,"L");

		$mpdf->SetFont('Roboto',8);
		$mpdf->SetX(20);
		$mpdf->Cell(5,6,'NO',1,0,'C');
		$mpdf->Cell(50,6,'Description',1,0,'C');
		$mpdf->Cell(50,6,'Deliver to Location',1,0,'C');
		$mpdf->Cell(50,6,'Contact Name & Phone No.',1,1,'C');

		$height = 20;
		$mpdf->SetX($height);
		$mpdf->Cell(5,6,'',1,0,'C');
		$mpdf->Cell(50,6,'',1,0,'C');
		$mpdf->Cell(50,6,'',1,0,'C');
		$mpdf->Cell(50,6,'',1,1,'C');

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(10,10, "NOTE :",0,0,'L');
		$mpdf->SetFont('Roboto',8);
		$mpdf->Cell(15,8, "",0,1,'L');
		// $mpdf->MultiCell(180,4,$notes,0, 'J');
		$mpdf->MultiCell(180,4,iconv('UTF-8', 'UTF-8//IGNORE', $notes),0, 'J');
		// $mpdf->MultiCell(180,4,iconv('UTF-8', 'UTF-8//IGNORE', $notes),0, 'J');


		$mpdf->SetFont('Calibri','',10);

		$po_encrypt = $po_number . " - " . number_format(123456789,0,',','.');
		$doc_ref = encrypt_string($po_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";
		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);
	    
		$title = "Form PO - ".$po_number;
		ob_clean();
        $mpdf->SetTitle($title);
		$title = "Form PO - ".$po_number.".pdf";

		if($status_po == 'request_approve'){
			$dratf_logo = FCPATH . 'assets/img/draft-logo.png';
			$mpdf->SetWatermarkImage( $dratf_logo, -1, 'F', 'F' );
			$mpdf->showWatermarkImage = true;
		}

		if($po_template == "Non Template" && $doc_clause != ""){

			$fh = 'uploads/po_attachment/'. $doc_clause;

	        $pagecount = $mpdf->setSourceFile($fh);
		    for($i=0; $i<$pagecount; $i++){
		        $mpdf->AddPage();  
		        $tplidx = $mpdf->importPage($i+1, '/BleedBox');
		        $mpdf->useTemplate($tplidx); 
		    }

			$mpdf->Output($title, "I");

		}elseif($po_template == "Non Template" && $doc_clause == ""){
			$mpdf->Output($title, "I");

		}else{
			$fh = 'assets/templates/Po_template_new1.pdf';

			$mpdf->AddPage();
	        $mpdf->setSourceFile($fh);
	        $tplId = $mpdf->importPage(1);
			$mpdf->useTemplate($tplId);

			$fh = 'assets/templates/Po_template_new2.pdf';

			$mpdf->AddPage();
	        $mpdf->setSourceFile($fh);
	        $tplId = $mpdf->importPage(1);
			$mpdf->useTemplate($tplId);

			$mpdf->Output($title, "I");

			$mpdf->writeHTML(utf8_encode($html));
		}
	}

}

/* End of file PO_ctl.php */
/* Location: ./application/controllers/pr_po/PO_ctl.php */