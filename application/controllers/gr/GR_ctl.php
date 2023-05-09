<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GR_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->load->model('GR_mdl', 'gr');
		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');
		
	}

	public function index()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("general-receipt", $this->session->userdata['menu_url']) ){

			$data['title']         = "General Receipt";
			$data['module']        = "datatable";
			$data['template_page'] = "gr/gr_inquiry";
			$data['gr_status']     = get_status_gr();

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

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "General Receipt", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}


	public function load_gr_header(){

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

		if($this->input->post('gr_date')){
			$exp_gr_date = explode(" - ", $this->input->post('gr_date'));

			$date_from = date_db($exp_gr_date[0]);
			$date_to   = date_db($exp_gr_date[1]);

		}

		$get_all         = $this->gr->get_gr_header($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status = ($value['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($value['STATUS']);

				$row[] = array(
						'no'           => $number,
						'directorat'   => $value['DIRECTORAT_NAME'],
						'division'     => $value['DIVISION_NAME'],
						'unit'     	   => $value['UNIT_NAME'],
						'vendor_name'  => $value['VENDOR_NAME'],
						'gr_header_id' => encrypt_string($value['GR_HEADER_ID'], true),
						'id'           => $value['GR_HEADER_ID'],
						'gr_number'    => $value['GR_NUMBER'],
						'status'       => $status,
						'po_number'    => $value['PO_NUMBER'],
						'gr_date'      => dateFormat($value['GR_DATE'], 5, false)
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

	public function view_gr($id_gr_enc){

			$decrypt = decrypt_string($id_gr_enc, true);
			$id_gr   = (int) $decrypt;

			$check_exist = $this->crud->check_exist("GR_HEADER", array("GR_HEADER_ID" => $id_gr));

			if($check_exist > 0){

				$get_gr_header = $this->gr->get_gr_header_by_id($id_gr);

				$data['title']          = "GR ".$get_gr_header['GR_NUMBER'];
				$data['module']         = "datatable";
				$data['template_page']  = "gr/gr_view";
				
				$data['id_gr']          = $id_gr;
				$data['id_gr_enc']      = $id_gr_enc;
				$data['id_fs']          = $get_gr_header['ID_FS'];
				$data['gr_number']      = $get_gr_header['GR_NUMBER'];
				$data['po_number']      = $get_gr_header['PO_NUMBER'];	
				$data['gr_date']        = $get_gr_header['GR_DATE'];	
				$data['contract']       = $get_gr_header['CONTRACT_IDENTIFICATION'];	
				$data['budget_type']    = $get_gr_header['BUDGET_TYPE'];	
				$data['project']        = $get_gr_header['OWNERSHIP_NAME'];
				$data['directorat']  	= $get_gr_header['ID_DIR_CODE'];
				$data['division']    	= $get_gr_header['ID_DIVISION'];
				$data['unit']        	= $get_gr_header['ID_UNIT'];	
				$data['vendor_name']    = $get_gr_header['VENDOR_NAME'];
				$data['gr_submitter']   = $get_gr_header['SUBMITTER'];
				$data['gr_jabatan_sub'] = $get_gr_header['JABATAN_SUBMITTER'];	
				$data['attachment']     = $get_gr_header['GR_DOCUMENT'];
				$data['bast_date']      = $get_gr_header['TGL_BAST'];
				$data['no_bast']        = $get_gr_header['NO_BAST'];
				$data['category']       = $get_gr_header['GR_CATEGORY'] == "quantity" ? "By Quantity" : "By Amount";
				$data['gr_doc_upload']   = ($get_gr_header['GR_DOCUMENT']) ? base_url("download/") . encrypt_string("uploads/gr_attachment/".$get_gr_header['GR_DOCUMENT'], true) : "-";
				$data['gr_status']      = ($get_gr_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_gr_header['STATUS']);
				$data['gr_status_desc'] = $get_gr_header['STATUS_DESCRIPTION'];
				$last_update            = ($get_gr_header['UPDATED_DATE']) ? $get_gr_header['UPDATED_DATE'] : $get_gr_header['CREATED_DATE'];
				$data['gr_last_update'] = dateFormat($last_update, "with_day", false);


				$get_approval = $this->gr->get_approval_by_gr($id_gr);

				$approval = array();
				$approval_remark = array();

				foreach ($get_approval as $key => $value) {
					$approval[] = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "JABATAN" => $value['JABATAN']);
					if(!empty($value['REMARK'])){
						$approval_remark[] = $value;
					}
				}
				$data['gr_approval'] = $approval;


				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => "General Receipt", "link" => base_url("general-receipt"), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

	public function create_gr()
	{
		$data['title']         = "Create New GR";
		$data['module']        = "datatable";
		$data['template_page'] = "gr/gr_create";

		$directorat = check_is_bod();
		$binding    = check_binding();

	    if(count($directorat) > 1 && $binding['binding'] != false){
			$directorat = $binding['data_binding']['directorat'];
	    }
		
		$data['directorat']              = $directorat;
		$data['binding']                 = $binding['binding'];
		$data['contract_identification'] = get_data_ci();
		$data['project_ownership']       = get_data_project_owner();
		$data['data_binding']            = $binding['data_binding'];

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "General Receipt", "link" => base_url('general-receipt'), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_po_line()
	{

		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');
		$category   = $this->input->post('category');
		
		$get_fs = $this->budget->get_fs_header($directorat, $division, $unit, $category);
		$result['status'] = false;

		if($get_fs){
			$get_fs_unique = array_unique($get_fs,SORT_REGULAR);

			foreach($get_fs_unique as $row)	{

				$data[] = array(
								"id_fs"     => $row['ID_FS'],
								"fs_number" => $row['FS_NUMBER'],
								"fs_name"   => $row['FS_NAME'],
								"currency"  => ($row['CURRENCY']) ? $row['CURRENCY'] : "IDR",
								"rate"      => $row['CURRENCY_RATE']
							);

			}
			$result['status'] = true;
			$result['data']   = $data;
		}

		echo json_encode($result);

    }

	public function load_po_number()
	{

		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');
		
		$get_fs = $this->gr->get_po_number($directorat, $division, $unit);
		$result['status'] = false;


		if($get_fs)
		{

			foreach($get_fs as $row)	{

				$data[] = array(
								"po_number"    => $row['PO_NUMBER'],
								"po_header_id" => $row['PO_HEADER_ID'],
								"pr_header_id" => $row['PR_HEADER_ID'],
								"id_fs" 	   => $row['ID_FS'],
								"vendor_name"  => $row['VENDOR_NAME'],
								"po_line_desc" => $row['PO_LINE_DESC'],
								"category" => $row['CATEGORY']
							);

			}

			$result['status'] = true;
			$result['data']   = $data;

		}
		
		echo json_encode($result);

    }


	public function load_data_gr_create(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_fs = $this->input->post('id_fs');
		$directorat = $this->input->post('directorat');
		
		$get_all = $this->gr->get_gr_lines($id_fs);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$lov_asset = '<option value="">-- Choose --</option>';
		$lov_asset .= '<option value="Fixed Asset">Fixed Asset</option>';
		$lov_asset .= '<option value="Expense">Expense</option>';
		$lov_asset .= '<option value="Non Asset">Non Asset</option>';
		$lov_asset .= '<option value="Intangible Asset">Intangible Asset</option>';

		$lov_cip = '<option value="">-- Choose --</option>';
		$lov_cip .= '<option value="Y">Y</option>';
		$lov_cip .= '<option value="N">N</option>';
		$get_all_region = get_all_region();
		$all_project_owner_unit = get_all_project_owner_unit($directorat);

		if($total > 0){

			foreach($data as $value) {

				$datapo_number            = $value['PO_NUMBER'];
				$po_detail_id             = $value['PO_DETAIL_ID'];
				$datavendor_name          = $value['VENDOR_NAME'];
				$dataitem_name            = $value['ITEM_NAME'];
				$dataitem_description     = $value['ITEM_DESCRIPTION'];
				$dataquantity             = $value['QUANTITY'];
				$datauom                  = $value['UOM'];
				$dataitem_price           = number_format($value['ITEM_PRICE'],0,',','.');
				$datatotal_price          = number_format($value['TOTAL_PRICE'],0,',','.');
				$datatotal_price_byamount = number_format($value['TOTAL_PRICE_BY_AMOUNT'],0,',','.');
				$pengurangan              = $value['TOTAL_PRICE'] - $value['TOTAL_PRICE_BY_AMOUNT'];
				$datatotal_sisa           = number_format($pengurangan,0,',','.');
				
				$po_number        = $datapo_number;
				$vendor_name      = $datavendor_name;
				$item_name        = $dataitem_name;
				$item_description = $dataitem_description;
				$uom              = $datauom;
				// $item_price   = $dataitem_price;
				// $total_price   = $datatotal_price;

				$total_price = '<div class="form-group m-b-0"><input id="total_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm total_price text-right money-format" type="text" value="'.$datatotal_sisa.'" readonly></div>';

				// $total_price = '<div class="form-group m-b-0"><input id="total_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm total_price text-right money-format" type="text" value="'.$datatotal_price.'" readonly></div>';

				$unit_price = '<div class="form-group m-b-0"><input id="unit_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm unit_price text-right money-format" type="text" value="'.$dataitem_price.'" readonly></div>';

				$quantity       = '<div class="form-group m-b-0"><input id="quantity-'.$number.'" data-id="'.$number.'" class="form-control input-sm quantity text-center" value="'.$dataquantity.'" min="1" max="99999" type="number"></div>';
				$serial_number   = '<div class="form-group m-b-0"><input id="serial_number-'. $number .'" data-id="'. $number .'" class="form-control input-sm serial_number" autocomplete="off"></div>';
				$merek   = '<div class="form-group m-b-0"><input id="merek-'. $number .'" data-id="'. $number .'" class="form-control input-sm merek" autocomplete="off"></div>';
				$umur_manfaat   = '<div class="form-group m-b-0"><input id="umur_manfaat-'. $number .'" data-id="'. $number .'" class="form-control input-sm umur_manfaat" autocomplete="off"></div>';

				$no_invoice   = '<div class="form-group m-b-0"><input id="no_invoice-'. $number .'" data-id="'. $number .'" class="form-control input-sm no_invoice" autocomplete="off"></div>';

				$invoice_date  = '<div class="form-group m-b-0"><input id="invoice_date-'.$number.'" data-id="'.$number.'" class="form-control input-sm invoice_date date_period" ></div>';
				$receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'.$number.'" data-id="'.$number.'" class="form-control input-sm receipt_date date_period" ></div>';
				$budget_type   = '<div class="form-group m-b-0"><input id="budget_type-'. $number .'" data-id="'. $number .'" class="form-control input-sm budget_type" autocomplete="off"></div>';
				$project_owner   = '<div class="form-group m-b-0"><input id="project_owner-'. $number .'" data-id="'. $number .'" class="form-control input-sm project_owner" autocomplete="off"></div>';
				$asset_type = '<div class="form-group m-b-0"><select id="asset_type-'.$number.'" class="form-control input-sm asset_type select2 select-center">'.$lov_asset.'</select></div>';


				$optValregion = '<option value="" data-name="">-- Choose  --</option>';
				foreach ($get_all_region as $key => $value):
					$optValregion .= '<option value="' . $value['CODE'] . '" data-name="' . $value['REGION'] . '"> ' . $value['REGION'] . ' </option>';
				endforeach;

				$region = '<div class="form-group m-b-0"><select id="region-'.$number.'" class="form-control input-sm region select2 select-center">'.$optValregion.'</select></div>';

				$optVallokasi = '<option value="" data-name="">-- Choose  --</option>';
				$lokasi = '<div class="form-group m-b-0"><select id="lokasi-'.$number.'" class="form-control input-sm lokasi select2 select-center">'.$optVallokasi.'</select></div>';

				$optValprojectownerunit = '<option value="" data-name="">-- Choose  --</option>';
				foreach ($all_project_owner_unit as $key => $value):
					$optValprojectownerunit .= '<option value="' . $value['CODE'] . '" data-name="' . $value['OWNERSHIP_NAME'] . '"> ' . $value['OWNERSHIP_NAME'] . ' </option>';
				endforeach;
				$project_owner_unit = '<div class="form-group m-b-0"><select id="project_owner_unit-'.$number.'" class="form-control input-sm project_owner_unit select2 select-center">'.$optValprojectownerunit.'</select></div>';

				$cip = '<div class="form-group m-b-0"><select id="cip-'.$number.'" class="form-control input-sm cip select2 select-center">'.$lov_cip.'</select></div>';

				$row[] = array(
							'no'                   => $number,
							'po_detail_id'         => $po_detail_id,
							'po_number'            => $po_number,
							'vendor_name'          => $vendor_name,
							'asset_type'           => $asset_type,
							'budget_type'          => $budget_type,
							'project_owner'        => $project_owner,
							'item_name'            => $item_name,
							'item_description'     => $item_description,
							'quantity'             => $quantity,
							'uom'                  => $uom,
							'item_price'           => $unit_price,
							'total_price'          => $total_price,
							'total_price_byamount' => $datatotal_price_byamount,
							'total_price_sisa'     => $datatotal_sisa,
							'serial_number'        => $serial_number,
							'merek'                => $merek,
							// 'major_category'       => $major_category,
							// 'minor_category'       => $minor_category,
							'region'               => $region,
							'lokasi'               => $lokasi,
							'project_owner_unit'   => $project_owner_unit,
							'umur_manfaat'         => $umur_manfaat,
							'cip'                  => $cip,
							'no_invoice'           => $no_invoice,
							'invoice_date'         => $invoice_date,
							'receipt_date'         => $receipt_date
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


	public function load_data_gr_lines(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$gr_header_id = $this->input->post('pr_header_id');
		
		$get_all = $this->gr->get_purchase_gr_lines($gr_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'                 => $number,
						'item_name'          => $value['ITEM_NAME'],
						'item_description'   => $value['ITEM_DESCRIPTION'],
						'quantity'           => $value['QUANTITY'],
						'unit_price'         => number_format( $value['ITEM_PRICE'],0,',','.'),
						'total_price'        => number_format( $value['TOTAL_PRICE'],0,',','.'),
						'asset_type'         => $value['ASSET_TYPE'],
						'serial_number'      => $value['SERIAL_NUMBER'],
						'merek'              => $value['MEREK'],
						'major_category'     => $value['MAJOR_NAME'],
						'minor_category'     => $value['MINOR_NAME'],
						'region'             => $value['REGION_NAME'],
						'lokasi'             => $value['LOCATION_NAME'],
						'project_owner_unit' => $value['OWNERSHIP_NAME'],
						'umur_manfaat'       => $value['UMUR_MANFAAT'],
						'cip'                => $value['CIP'],
						'no_invoice'         =>$value['NO_INVOICE'],
						'invoice_date'       => dateFormat($value['INVOICE_DATE'], 5, false),
						'uom'                => $value['UOM'],
						'receipt_date'       => dateFormat($value['RECEIPT_DATE'], 5, false),
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


	// public function load_data_details(){

	// 	$result['data']            = "";
	// 	$result['draw']            = "";
	// 	$result['recordsTotal']    = 0;
	// 	$result['recordsFiltered'] = 0;

	// 	$pr_lines_id = $this->input->post('pr_lines_id');
	// 	$category    = $this->input->post('category');
		
	// 	$get_all = $this->gr->get_purchase_details($pr_lines_id);
	// 	$data    = $get_all['data'];
	// 	$total   = $get_all['total_data'];
	// 	$start   = $this->input->post('start');
	// 	$number  = $start+1;

	// 	if($total > 0){

	// 		foreach($data as $value) {

	// 			$number = $value['PR_DETAIL_NUMBER'];

	// 			$po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc"></div>';
				
	// 			$nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="0"></div>';

	// 			$row[] = array(
	// 					'pr_lines_id'       => $value['PR_LINES_ID'],
	// 					'pr_detail_id'      => $value['PR_DETAIL_ID'],
	// 					'no'                => $number,
	// 					'detail_desc'       => $value['PR_DETAIL_DESC'],
	// 					'nature'            => $value['NATURE']." - ".$value['DESCRIPTION'],
	// 					'quantity'          => $value['QUANTITY'],
	// 					'price'             => number_format($value['PRICE'],0,',','.'),
	// 					'nominal'           => number_format($value['PR_DETAIL_AMOUNT'],0,',','.'),
	// 					'po_desc'           => $po_desc,
	// 					'nominal_detail_po' => $nominal_detail_po
	// 					);
	// 			$number++;

	// 		}

	// 		$result['data']            = $row;
	// 		$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
	// 		$result['recordsTotal']    = $total;
	// 		$result['recordsFiltered'] = $total;

	// 	}

	// 	echo json_encode($result);
	// }


	public function save_gr(){
		
		$po_number     = $this->input->post('po_number');
		$id_fs         = $this->input->post('id_fs');
		$po_header_id  = $this->input->post('po_header_id');
		$pr_header_id  = $this->input->post('pr_header_id');
		$directorat    = $this->input->post('directorat');
		$division      = $this->input->post('division');
		$unit          = $this->input->post('unit');
		// $gr_date    = $this->input->post('gr_date');
		$attachment    = $this->input->post('attachment');
		$no_bast       = $this->input->post('no_bast');
		$bast_date     = $this->input->post('bast_date');
		$category      = $this->input->post('category');
		$contract      = $this->input->post('contract');
		$budget_type   = $this->input->post('budget_type');
		$project_owner = $this->input->post('project_owner');
		$data_line     = $this->input->post('data_line');
		$submitter     = $this->input->post('submitter');
		$jabatan_sub   = $this->input->post('jabatan_sub');

		// if($gr_date != ""){
		// 	$exp_gr_date = explode("-", $gr_date);
		// 	$gr_date = $exp_gr_date[2]."-".$exp_gr_date[1]."-".$exp_gr_date[0];
		// }

		if($bast_date != ""){
			$exp_bast_date = explode("-", $bast_date);
			$bast_date = $exp_bast_date[2]."-".$exp_bast_date[1]."-".$exp_bast_date[0];
		}

		$get_dir        = $this->crud->read_by_param("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $directorat));
		$id_dir_code    = $get_dir['ID_DIR_CODE'];

		$check_gr_exist = $this->crud->check_exist("GR_HEADER", array("ID_DIR_CODE" => $id_dir_code));

		$month     = date("m");
		$year      = date("Y");
		$number    = sprintf("%'03d", 1);
		$gr_number = "GR/".$get_dir['DIRECTORAT_CODE']."/".$number."/".date("m")."/".date("Y");

		if($check_gr_exist > 0){
			
			$last_gr_number = $this->gr->get_last_gr_number($id_dir_code);
			$exp_gr_number  = explode("/",$last_gr_number);
			if(substr($last_gr_number, 0, 2) == "GR"){
			    $dir_code = $exp_gr_number[1];
			    $number   = (int) $exp_gr_number[2];
			}else{
			    $dir_code = $exp_gr_number[0];
			    $number   = (int) $exp_gr_number[1];
			}
			$number += 1;
			$number = sprintf("%'03d", $number);
			$gr_number = "GR/".$dir_code."/".$number."/".$month."/".$year;

		}

		$status_description = "Submitted by ".get_user_data($this->session->userdata('user_id'));
		
		$data = array(
						"ID_DIR_CODE"        => $id_dir_code,
						"ID_DIVISION"        => $division,
						"ID_UNIT"            => $unit,
						"PO_HEADER_ID"       => $po_header_id,
						"PR_HEADER_ID"       => $pr_header_id,
						"ID_FS"              => $id_fs,
						"GR_NUMBER"          => $gr_number,
						"PO_NUMBER"          => $po_number,
						"BUDGET_TYPE"        => $budget_type,
						"CONTRACT"           => $contract,
						"PROJECT_OWNERSHIP"  => $project_owner,
						"NO_BAST"            => $no_bast,
						"GR_CATEGORY"        => $category,
						"SUBMITTER"          => $submitter,
						"JABATAN_SUBMITTER"  => $jabatan_sub,
						"STATUS"             => 'request_approve',
						"STATUS_DESCRIPTION" => $status_description
					);

		// if($gr_date != ""){
		// 	$data['GR_DATE'] = $gr_date;
		// }

		if($bast_date != ""){
			$data['TGL_BAST'] = $bast_date;
		}

		if($attachment != ""){
			$data['GR_DOCUMENT'] = $attachment;
		}

		// echo_pre($data);die;
		$insert   = $this->crud->create("GR_HEADER", $data);

		$send_email    = false;
		$level         = 1;
		$data_approval = array();

		$get_hog        = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division);

		if($get_hog){
			$send_email = true;
			$recipient['email'] = $get_hog['PIC_EMAIL'];
			$recipient['name']  = $get_hog['PIC_NAME'];
			$data_approval[] = array("LEVEL" => $level, "STATUS" => "request_approve", "CATEGORY" => "HOG User", "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "GR_HEADER_ID" => $insert);
		}

		$insert_approval = $this->crud->create_batch("TRX_APPROVAL_GR", $data_approval);
		$id_approval     = $this->db->insert_id();
	
		$status   = false;
		$messages = "";

		if($insert > 0){

			$pr_line_number = 1;

			foreach ($data_line as $key => $value) {

				$receipt_date = $value['receipt_date'];
				$invoice_date = $value['invoice_date'];

				$receipt_date = ($receipt_date != "") ? date_db($receipt_date) : null ;
				$invoice_date = ($invoice_date != "") ? date_db($invoice_date) : null ;

				$data_lines[] = array(
										"GR_HEADER_ID"                => $insert,
										"ITEM_NAME"                   => $value['item_name'],
										"PO_DETAIL_ID"                => $value['po_detail_id'],
										"ITEM_DESCRIPTION"            => $value['item_description'],
										"QUANTITY"                    => $value['quantity'],
										"UOM"                         => $value['uom'],
										// "ASSET_TYPE"                  => $value['asset_type'],
										// "UMUR_MANFAAT"                => $value['umur_manfaat'],
										// "MEREK"                       => $value['merek'],
										// "SERIAL_NUMBER"               => $value['serial_number'],
										"RECEIPT_DATE"                => $receipt_date,
										"INVOICE_DATE"                => $invoice_date,
										"ITEM_PRICE"                  => $value['item_price'],
										"TOTAL_PRICE"                 => $value['total_price'],
										// "MAJOR_CATEGORY"              => $value['major_category'],
										// "MINOR_CATEGORY"              => $value['minor_category'],
										"REGION"                      => $value['region'],
										"LOCATION"                    => $value['lokasi'],
										"PROJECT_OWNERSHIP_UNIT_CODE" => $value['project_owner_unit'],
										// "CIP"                         => $value['cip'],
										"NO_INVOICE"                  => $value['no_invoice']
									);

				$pr_line_number++;
			}

			$insert_line = $this->crud->create_batch("GR_LINE", $data_lines);

			if($insert_line){
				$status    = true;
			}else{
				$messages = "Failed to Create GR Detail";
			}

		}
		else{
			$messages = "Failed to Create GR";
		}


		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

	public function save_gr_edit(){

		$data_line  = $this->input->post('data_line');
		$id_gr      = $this->input->post('id_gr');
		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');

		$action_name = get_user_data($this->session->userdata('user_id'));

		$messages = "";
		$status    = false;

		foreach ($data_line as $key => $value) {

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

	
				$data_update = array(
										"QUANTITY"         => $value['quantity'],
										"ASSET_TYPE"       => $value['asset_type'],
										"UMUR_MANFAAT"     => $value['umur_manfaat'],
										"MEREK"            => $value['merek'],
										"SERIAL_NUMBER"    => $value['serial_number'],
										"RECEIPT_DATE"     => $rd_date,
										"INVOICE_DATE"     => $id_date,
										"ITEM_PRICE"       => $value['item_price'],
										"TOTAL_PRICE"      => $value['total_price']
									);

				$update_lines = $this->crud->update("GR_LINE", $data_update, array("GR_LINE_ID" => $value['gr_line_id']));

			}

			if($update_lines){

				$send_email    = false;
				$level         = 1;
				$data_approval = array();

				$get_hog        = $this->feasibility_study->get_data_approval("HOG User", $directorat, $division);

				if($get_hog){
					$send_email = true;
					$recipient['email'] = $get_hog['PIC_EMAIL'];
					$recipient['name']  = $get_hog['PIC_NAME'];
					$data_approval[] = array("LEVEL" => $level, "STATUS" => "request_approve", "CATEGORY" => "HOG User", "ID_APPROVAL" => $get_hog['ID_APPROVAL'], "GR_HEADER_ID" => $id_gr);
				}

				$this->crud->update("TRX_APPROVAL_GR", array("IS_ACTIVE" => 0), array("GR_HEADER_ID" => $id_gr));
				$insert_approval = $this->crud->create_batch("TRX_APPROVAL_GR", $data_approval);
				$id_approval     = $this->db->insert_id();

				$data_update_gr = [ "STATUS" => "request_approve", "STATUS_DESCRIPTION" => "Resubmitted by " . $action_name ];
				$this->crud->update("GR_HEADER", $data_update_gr, ["GR_HEADER_ID" => $id_gr]);

				$status    = true;
			}else{
				$messages = "Failed to Update GR Line";
			}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }

    public function edit_gr($gr_header_id){
		
			$decrypt      = decrypt_string($gr_header_id, true);
			$gr_header_id = (int) $decrypt;

			$check_exist = $this->crud->check_exist("GR_HEADER", array("GR_HEADER_ID" => $gr_header_id));

			if($check_exist > 0){

				$get_gr_header = $this->gr->get_gr_header_by_id($gr_header_id);

				$data['title']          = "GR ".$get_gr_header['GR_NUMBER'];
				$data['module']         = "datatable";
				$data['template_page']  = "gr/gr_edit";

				$data['id_gr']          = $gr_header_id;
				$data['id_gr_enc']      = $gr_header_id;
				$data['id_fs']          = $get_gr_header['ID_FS'];
				$data['gr_number']      = $get_gr_header['GR_NUMBER'];
				$data['po_number']      = $get_gr_header['PO_NUMBER'];	
				$data['gr_date']        = $get_gr_header['GR_DATE'];	
				$data['contract']       = $get_gr_header['CONTRACT'];	
				$data['budget_type']    = $get_gr_header['BUDGET_TYPE'];	
				$data['project']        = $get_gr_header['PROJECT_OWNERSHIP'];
				$data['directorat']  	= get_directorat($get_gr_header['ID_DIR_CODE']);
				$data['division']    	= get_division($get_gr_header['ID_DIVISION']);
				$data['unit']        	= get_unit($get_gr_header['ID_UNIT']);	
				$data['vendor_name']    = $get_gr_header['VENDOR_NAME'];

				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => "General Receipt", "link" => base_url('	general-receipt'), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{

				$this->session->set_flashdata('messages', 'GR Not Exist');
				redirect('general-receipt');
				
			}

	}

	public function load_data_gr_edit(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$gr_header_id = $this->input->post('gr_header_id');
		
		$get_all = $this->gr->get_gr_for_edit($gr_header_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {
				$num = $number;
				$asset = $value['ASSET_TYPE'];

				if($asset == 'Fixed Asset')
				{
					$lov_asset  = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option selected="selected" value="Fixed Asset">Fixed Asset</option>';
					$lov_asset .= '<option value="Asset">Asset</option>';
					$lov_asset .= '<option value="Non Asset">Non Asset</option>';
					$lov_asset .= '<option value="Intangible Asset">Intangible Asset</option>';
				}
				else if($asset == 'Asset')
				{
					$lov_asset  = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option value="Fixed Asset">Fixed Asset</option>';
					$lov_asset .= '<option selected="selected" value="Asset">Asset</option>';
					$lov_asset .= '<option value="Non Asset">Non Asset</option>';
					$lov_asset .= '<option value="Intangible Asset">Intangible Asset</option>';
				}
				else if($asset == 'Non Asset')
				{
					$lov_asset  = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option value="Fixed Asset">Fixed Asset</option>';
					$lov_asset .= '<option value="Asset">Asset</option>';
					$lov_asset .= '<option selected="selected" value="Non Asset">Non Asset</option>';
					$lov_asset .= '<option value="Intangible Asset">Intangible Asset</option>';
				}
				else
				{
					$lov_asset  = '<option value="">-- Choose --</option>';
					$lov_asset .= '<option value="Fixed Asset">Fixed Asset</option>';
					$lov_asset .= '<option value="Asset">Asset</option>';
					$lov_asset .= '<option value="Non Asset">Non Asset</option>';
					$lov_asset .= '<option selected="selected" value="Intangible Asset">Intangible Asset</option>';
				}

				$exp_rd_date = explode("-", $value['RECEIPT_DATE']);
				$rd_date = $exp_rd_date[2]."-".$exp_rd_date[1]."-".$exp_rd_date[0];

				$exp_id_date = explode("-", $value['INVOICE_DATE']);
				$id_date = $exp_id_date[2]."-".$exp_id_date[1]."-".$exp_id_date[0];

				$serial_number      = '<div class="form-group m-b-0"><input id="serial_number-'.$num.'" class="form-control input-sm serial_number" value="'.$value['SERIAL_NUMBER'].'"></div>';
				$merek      = '<div class="form-group m-b-0"><input id="merek-'.$num.'" class="form-control input-sm merek" value="'.$value['MEREK'].'"></div>';
				$umur_manfaat      = '<div class="form-group m-b-0"><input id="umur_manfaat-'.$num.'" class="form-control input-sm umur_manfaat" value="'.$value['UMUR_MANFAAT'].'"></div>';
				$invoice_date  = '<div class="form-group m-b-0"><input id="invoice_date-'.$number.'" data-id="'.$number.'" class="form-control input-sm invoice_date date_period" value="'.$id_date.'"></div>';
				$receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'.$number.'" data-id="'.$number.'" class="form-control input-sm receipt_date date_period" value="'.$rd_date.'"></div>';
				$quantity       = '<div class="form-group m-b-0"><input id="quantity-'.$num.'" data-id="'.$num.'" class="form-control input-sm quantity text-center" value="1" min="1" max="99999" type="number" value="'.$value['QUANTITY'].'"></div>';
				$unit_price      = '<div class="form-group m-b-0"><input id="unit_price-'.$num.'" class="form-control input-sm unit_price" value="'.number_format($value['ITEM_PRICE'],0,',','.').'" readonly></div>';
				$total_price      = '<div class="form-group m-b-0"><input id="total_price-'.$num.'" class="form-control input-sm total_price" value="'.number_format($value['TOTAL_PRICE'],0,',','.').'" readonly></div>';
				$asset_type = '<div class="form-group m-b-0"><select id="asset_type-'.$number.'" class="form-control input-sm asset_type select2 select-center">'.$lov_asset.'</select></div>';


				$row[] = array(
						'no'                => $number,
						'gr_line_id'		=> $value['GR_LINE_ID'],
						'item_name'       	=> $value['ITEM_NAME'],
						'item_description'  => $value['ITEM_DESCRIPTION'],
						'quantity'          => $quantity,
						'uom'  				=> $value['UOM'],
						'unit_price'        => $unit_price,
						'total_price'       => $total_price,
						'asset_type'  	    => $asset_type,
						'serial_number'  	=> $serial_number,
						'merek'  			=> $merek,
						'umur_manfaat'      => $umur_manfaat,
						'invoice_date'      => $invoice_date,
						'receipt_date'      => $receipt_date
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


	public function delete_gr(){

		$id       = $this->input->post('id');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->crud->delete("GR_HEADER", array("GR_HEADER_ID" => $id));

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

    private function _email_aprove($recipient, $id_pr, $id_approval){

		$get_pr      = $this->gr->get_pr_for_email($id_pr);

		$directorat    = get_directorat($get_pr['ID_DIR_CODE']);
		$division      = get_division($get_pr['ID_DIVISION']);
		$unit          = get_unit($get_pr['ID_UNIT']);
		$rkap          = $get_pr['RKAP_DESCRIPTION'];
		$currency      = ($get_pr['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_pr['GR_AMOUNT'],0,',','.');
		$pr_name       = $get_pr['GR_NAME'];
		$pr_number     = $get_pr['GR_NUMBER'];
		$attachment    = $get_pr['DOCUMENT_ATTACHMENT'];
		$currency_rate = ($get_pr['CURRENCY'] == "IDR") ? $get_pr['CURRENCY'] : $get_pr['CURRENCY'] ."/". number_format($get_pr['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("pr/approval/").encrypt_string($id_pr."-".$id_approval, true);

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = "A new purchase request $pr_number has been submitted for your approval";

		$data['email_body'] = "
								A new purchase request $pr_number has been submitted for your approval. You can see all the details about this purchase by clicking the link below.
								<br>
								<br>
								Please go through the <a href='$approval_lnk'>link</a> and confirm your approval.
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
		$data['approval_link_all'] = base_url("pr/approval");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "New PR Request - $pr_number - $pr_name";
		$body       = $this->load->view('email/pr_request_approve', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
	}
	
	function download_gr($param="")
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
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "GR Number");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "PO Number");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Status");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "GR Date");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Vendor Name");

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
				$pr_date     = $obj_param->gr_date;

				if($pr_date){
					$exp_gr_date = explode(" - ", $pr_date);
					$date_from = date_db($exp_gr_date[0]);
					$date_to   = date_db($exp_gr_date[1]);
				}
			}
		}
		$hasil = $this->gr->get_download_gr($id_dir_code, $id_division, $id_unit, $status, $date_from, $date_to);

		$numrow  = 2;
		$number = 1;

		foreach($hasil as $row)	{

			$pr_date = date("d-m-Y",strtotime($row['GR_DATE']));

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['GR_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['PO_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['STATUS']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $pr_date);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['VENDOR_NAME']);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

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
		header('Content-Disposition: attachment; filename="General Receipt.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	public function printPDF($gr_header_id){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$decrypt      = decrypt_string($gr_header_id, true);
		$gr_header_id = (int) $decrypt;

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/form_pr.pdf';

		$data = $this->gr->get_cetak($gr_header_id);

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);

		$mpdf->SetTextColor(0,0,0);
		$mpdf->SetFont('Courier New','',18);

		$guideline = 0;

		$height = 46.3;
		$mpdf->SetXY(76, $height);
		$mpdf->Cell(0,16,"✓",$guideline,"L");

		$mpdf->SetFont('Calibri','',10);

		$height = 74.3;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['PR_NUMBER'],$guideline,"L");

		$height = 79.3;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['FS_NAME'],$guideline,"L");

		$height = 84.3;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['SUBMITTER'],$guideline,"L");

		$height = 89;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['UNIT_NAME'],$guideline,"L");

		$height = 93.8;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,number_format($data['PR_AMOUNT'],0,',','.'),$guideline,"L");

		$height = 98.8;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['NATURE'],$guideline,"L");

		$height = 103.8;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['DESCRIPTION'],$guideline,"L");

		$height = 108.5;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,$data['DIRECTORAT_NAME'],$guideline,"L");

		$height = 113.5;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,"--",$guideline,"L");

		$height = 118.3;
		$mpdf->SetXY(74, $height);
		$mpdf->Cell(10,10,"--",$guideline,"L");

		$get_approval = $this->gr->get_approval_by_pr($gr_header_id);
		$approval = "";

		foreach ($get_approval as $key => $value) {
			$approval = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
		}

		if($approval != ""){

			$height = 132.5;
			$mpdf->SetXY(35, $height);
			$mpdf->Cell(10,10, $approval['TGL_APPROVE'],$guideline,"C");

			$height = 144.5;
			if($approval['STATUS'] == "request_approve"){
				$mpdf->SetXY(28, $height);
				$status = "Waiting for approval"; 
			}else{
				$mpdf->SetXY(35, $height);
				$status = ucfirst($approval['STATUS']); 
			}
			$mpdf->Cell(10,10, $status, $guideline,"C");
		}

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

	public function load_ddl_minor()
	{		

		$major_code  = $this->input->post('major_category_value');

		$get_minor	= $this->gr->get_all_minor_category_data($major_code);
		
		if($get_minor['total'] > 0){

			$result['status'] = true;

			foreach($get_minor['data'] as $row)	
			{

					$data[] = array(
									"code" => $row['CODE'],
									"minor_name"    => $row['MINOR_NAME']
							);
			
			}

			$result['data'] = $data;
		}
		else
		{
			$result['status'] = false;
		}


		echo json_encode($result);

	}

	public function load_ddl_location()
	{		

		$region_code  = $this->input->post('region_value');

		$get_location	= $this->gr->get_all_location_data($region_code);
		
		if($get_location['total'] > 0){

			$result['status'] = true;

			foreach($get_location['data'] as $row)	
			{

					$data[] = array(
									"code" => $row['CODE'],
									"lokasi"    => $row['LOCATION']
							);
			
			}

			$result['data'] = $data;
		}
		else
		{
			$result['status'] = false;
		}


		echo json_encode($result);

	}


}

/* End of file GR_ctl.php */
/* Location: ./application/controllers/pr_po/PR_ctl.php */