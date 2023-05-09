<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalGR_ctl extends CI_Controller {

	private $module_name = "gr",
			$module_url  = "gr";

	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('GR_mdl', 'gr');
		// $this->load->model('feasibility_study_mdl', 'feasibility_study');

	}

	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", $this->module_url."/approval");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->gr->check_is_approval($this->session->userdata('email'));

		if($check_is_approval){
			$get_gr_for_approval = $this->gr->get_gr_for_approval($this->session->userdata('email'));

			$id_gr = array();

			if($get_gr_for_approval){
				foreach ($get_gr_for_approval as $value) {
					$id_gr[] = $value['GR_HEADER_ID'];
				}
			}

			$data['title']          = "All General Receipt";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/gr_approval";
			
			$data['id_gr']  = json_encode($id_gr);

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

	public function review_gr()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "gr/review_gr");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->gr->check_is_reviewer($this->session->userdata('email'));

		if($check_is_approval){

			$get_gr_for_review = $this->gr->get_gr_for_review($this->session->userdata('email'));

			$id_gr = array();

			if($get_gr_for_review){
				foreach ($get_gr_for_review as $value) {
					$id_gr[] = $value['GR_HEADER_ID'];
				}
			}

			$data['title']          = "All GR to review";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/review_gr";
			
			$data['id_gr']  = json_encode($id_gr);

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
			$this->session->set_flashdata("redirect_page", "gr/approval/".$id_approval);
			redirect('login');
		}

		$decrypt = decrypt_string($id_approval, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify GR');
			redirect('/');
		}

		$id_gr       = $exp[0];
		$id_approval = $exp[1];
		$get_trx     = $this->crud->read_by_id("TRX_APPROVAL_GR", array("ID" => $id_approval));
		$get_approval = $this->crud->read_by_id("MASTER_APPROVAL", array("ID_APPROVAL" => $get_trx['ID_APPROVAL']));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->session->userdata('email'))){
			$this->session->set_flashdata('messages', 'Error verify GR Approval');
			redirect('/');
		}


		$check_exist = $this->crud->check_exist("GR_HEADER", array("GR_HEADER_ID" => $id_gr));
		
		if($check_exist > 0){

			$pic_email     = $this->session->userdata('email');
			$get_gr_header = $this->gr->get_gr_to_approve_by_id($id_gr, $pic_email);

			$data['title']          = "GR ".$get_gr_header['GR_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/gr_approval_single";
			
			// $get_gr_header = $this->gr->get_gr_header_by_id($id_gr);

			$data['title']          = "GR ".$get_gr_header['GR_NUMBER'];
			$data['module']         = "datatable";
			
			$data['id_gr']          = $id_gr;
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
			$data['trx_status']     = $get_gr_header['TRX_STATUS'];
			$data['trx_date']       = dateFormat($get_gr_header['TRX_DATE'], "with_day", false);

			$data['level']          = $get_gr_header['LEVEL'];
			$data['level']          = $get_gr_header['LEVEL'];

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

			if($get_gr_header['LEVEL'] > 1):
				// $level_min = $get_gr_header['LEVEL']-1;
				$get_approver_before            = $this->gr->get_approval_before($id_gr);
				$data['approver_before_name']   = $get_approver_before['PIC_NAME'];
				$data['approver_before_remark'] = $get_approver_before['REMARK'];
				$data['approver_before_date']   = dateFormat($get_approver_before['TRX_DATE'], "with_day", false);
			endif;

			
			$gr_document = false;
			if($get_gr_header['GR_DOCUMENT']):
				// $data['gr_document_link'] = base_url("download/") . encrypt_string("uploads/gr_attachment/".$get_gr_header['GR_DOCUMENT'], true);
				$gr_document[] = array(
									"FILE_NAME"     => $get_gr_header['GR_DOCUMENT'],
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/gr_attachment/".$get_gr_header['GR_DOCUMENT'], true),
									"DATE_UPLOADED" => strtotime($get_gr_header['CREATED_DATE']),
									"UPLOADED_BY"   => $get_gr_header['SUBMITTER']
									);
			endif;
			$data['gr_document'] = $gr_document;

			$last_update_trx = $this->gr->get_last_update_gr($id_gr);
			$last_update = $get_gr_header['STATUS_DESCRIPTION'] ." at " . dateformat($get_gr_header['UPDATED_DATE'], "with_day", false);

			if($last_update_trx):
				$last_update = ucfirst($last_update_trx['STATUS']) . " by " . $last_update_trx['PIC_NAME'] . " at " . dateFormat($last_update_trx['UPDATED_DATE'], "with_day", false);
			endif;
			$data['gr_last_update'] = $last_update;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All GR Request", "link" => base_url("gr/approval"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('gr/approval');

		}

	}


	public function review_single($id_gr){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "gr/review/".$id_gr);
			redirect('login');
		}

		$decrypt = decrypt_string($id_gr, true);
		$id_gr   = (int) $decrypt;

		$check_exist = $this->crud->check_exist("GR_HEADER", array("GR_HEADER_ID" => $id_gr));
		
		if($check_exist > 0){

			$pic_email     = $this->session->userdata('email');
			$get_gr_header = $this->gr->get_gr_to_review_by_id($id_gr);


			$data['title']          = "GR ".$get_gr_header['GR_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/gr_assets_edit";
			
			// $get_gr_header = $this->gr->get_gr_header_by_id($id_gr);

			$data['title']          = "GR ".$get_gr_header['GR_NUMBER'];
			$data['module']         = "datatable";
			
			$data['id_gr']          = $id_gr;
			$data['gr_number']      = $get_gr_header['GR_NUMBER'];
			$data['id_dir_code']    = $get_gr_header['ID_DIR_CODE'];
			$data['po_number']      = $get_gr_header['PO_NUMBER'];	
			$data['gr_date']        = $get_gr_header['GR_DATE'];	
			$data['contract']       = $get_gr_header['CONTRACT_IDENTIFICATION'];	
			$data['budget_type']    = $get_gr_header['BUDGET_TYPE'];	
			$data['project']        = $get_gr_header['OWNERSHIP_NAME'];
			$data['directorat']     = $get_gr_header['ID_DIR_CODE'];
			$data['division']       = $get_gr_header['ID_DIVISION'];
			$data['unit']           = $get_gr_header['ID_UNIT'];	
			$data['vendor_name']    = $get_gr_header['VENDOR_NAME'];
			$data['gr_submitter']   = $get_gr_header['SUBMITTER'];
			$data['gr_jabatan_sub'] = $get_gr_header['JABATAN_SUBMITTER'];	
			$data['attachment']     = $get_gr_header['GR_DOCUMENT'];
			$data['bast_date']      = $get_gr_header['TGL_BAST'];
			$data['no_bast']        = $get_gr_header['NO_BAST'];
			$data['category']       = $get_gr_header['GR_CATEGORY'] == "quantity" ? "By Quantity" : "By Amount";
			$data['gr_doc_upload']  = ($get_gr_header['GR_DOCUMENT']) ? base_url("download/") . encrypt_string("uploads/gr_attachment/".$get_gr_header['GR_DOCUMENT'], true) : "-";

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

			$gr_document = false;
			if($get_gr_header['GR_DOCUMENT']):
				$gr_document[] = array(
									"FILE_NAME"     => $get_gr_header['GR_DOCUMENT'],
									"FILE_LINK"     => base_url("download/") . encrypt_string("uploads/gr_attachment/".$get_gr_header['GR_DOCUMENT'], true),
									"DATE_UPLOADED" => strtotime($get_gr_header['CREATED_DATE']),
									"UPLOADED_BY"   => $get_gr_header['SUBMITTER']
									);
			endif;
			$data['gr_document'] = $gr_document;


			// $data['contract_identification'] = get_data_ci();
			// $data['project_ownership']       = get_data_project_owner();

			$last_update_trx = $this->gr->get_last_update_gr($id_gr);
			$last_update = $get_gr_header['STATUS_DESCRIPTION'] ." at " . dateformat($get_gr_header['UPDATED_DATE'], "with_day", false);

			if($last_update_trx):
				$last_update = ucfirst($last_update_trx['STATUS']) . " by " . $last_update_trx['PIC_NAME'] . " at " . dateFormat($last_update_trx['UPDATED_DATE'], "with_day", false);
			endif;
			$data['gr_last_update'] = $last_update;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All GR Review", "link" => base_url("gr/review"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('gr/approval');

		}

	}

	public function load_data_gr_lines(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_gr = $this->input->post('id_gr');
		$directorat = $this->input->post('directorat');
		
		$get_all = $this->gr->get_purchase_gr_lines($id_gr);
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

		// $all_project_owner_unit = get_all_project_owner_unit($directorat);
		$all_major_category     = get_all_major_category();
		$all_region             = get_all_region();

		if($total > 0){

			foreach($data as $value) {

				$po_detail_id         = $value['PO_DETAIL_ID'];
				$dataitem_name        = $value['ITEM_NAME'];
				$dataitem_description = $value['ITEM_DESCRIPTION'];
				$dataquantity         = $value['QUANTITY'];
				$datauom              = $value['UOM'];
				$dataitem_price       = number_format($value['ITEM_PRICE'],0,',','.');
				$datatotal_price      = number_format($value['TOTAL_PRICE'],0,',','.');
				$item_name            = $dataitem_name;
				$item_description     = $dataitem_description;
				$uom                  = $datauom;
				$total_price          = $value['TOTAL_PRICE'];

				$total_price = '<div class="form-group m-b-0"><input id="total_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm total_price text-right money-format" type="text" value="'.$total_price.'" readonly></div>';


				$unit_price = '<div class="form-group m-b-0"><input id="unit_price-'.$number.'" data-id="'.$number.'" class="form-control input-sm unit_price text-right money-format" type="text" value="'.$dataitem_price.'" readonly></div>';

				$quantity       = '<div class="form-group m-b-0"><input id="quantity-'.$number.'" data-id="'.$number.'" class="form-control input-sm quantity text-center" value="'.$dataquantity.'" min="1" max="99999" type="number"></div>';
				$serial_number   = '<div class="form-group m-b-0"><input id="serial_number-'. $number .'" data-id="'. $number .'" class="form-control input-sm serial_number" autocomplete="off"></div>';
				$merek   = '<div class="form-group m-b-0"><input id="merek-'. $number .'" data-id="'. $number .'" class="form-control input-sm merek" autocomplete="off"></div>';
				$umur_manfaat   = '<div class="form-group m-b-0"><input id="umur_manfaat-'. $number .'" data-id="'. $number .'" class="form-control input-sm umur_manfaat" autocomplete="off"></div>';

				$no_invoice   = $value['NO_INVOICE'];
				$no_invoice   = $value['NO_INVOICE'];

				$invoice_date  = '<div class="form-group m-b-0"><input id="invoice_date-'.$number.'" data-id="'.$number.'" class="form-control input-sm invoice_date date_period" ></div>';
				$receipt_date = '<div class="form-group m-b-0"><input id="receipt_date-'.$number.'" data-id="'.$number.'" class="form-control input-sm receipt_date date_period" ></div>';
				$budget_type   = '<div class="form-group m-b-0"><input id="budget_type-'. $number .'" data-id="'. $number .'" class="form-control input-sm budget_type" autocomplete="off"></div>';
				$project_owner   = '<div class="form-group m-b-0"><input id="project_owner-'. $number .'" data-id="'. $number .'" class="form-control input-sm project_owner" autocomplete="off"></div>';
				$asset_type = '<div class="form-group m-b-0"><select id="asset_type-'.$number.'" class="form-control input-sm asset_type select2 select-center">'.$lov_asset.'</select></div>';


			$optValmajor = '<option value="" data-name="">-- Choose  --</option>';
			foreach ($all_major_category as $key => $amc):
				$optValmajor .= '<option value="' . $amc['CODE'] . '" data-name="' . $amc['MAJOR_NAME'] . '"> ' . $amc['MAJOR_NAME'] . ' </option>';
			endforeach;

			$optValminor = '<option value="" data-name="">-- Choose  --</option>';


			$optValregion = '<option value="" data-name="">-- Choose  --</option>';
			foreach ($all_region as $key => $ar):
				$optValregion .= '<option value="' . $ar['CODE'] . '" data-name="' . $ar['REGION'] . '"> ' . $ar['REGION'] . ' </option>';
			endforeach;

			$optVallokasi = '<option value="" data-name="">-- Choose  --</option>';

			/*$optValprojectownerunit = '<option value="" data-name="">-- Choose  --</option>';


			$optValprojectownerunit = '<option value="" data-name="">-- Choose  --</option>';
			foreach ($all_project_owner_unit as $key => $apou):
				$optValprojectownerunit .= '<option value="' . $apou['CODE'] . '" data-name="' . $apou['OWNERSHIP_NAME'] . '"> ' . $apou['OWNERSHIP_NAME'] . ' </option>';
			endforeach;
*/

			$major_category = '<div class="form-group m-b-0"><select id="major_category-'.$number.'" class="form-control input-sm major_category select2 select-center">'.$optValmajor.'</select></div>';

			$minor_category = '<div class="form-group m-b-0"><select id="minor_category-'.$number.'" class="form-control input-sm minor_category select2 select-center">'.$optValminor.'</select></div>';

			$region = '<div class="form-group m-b-0"><select id="region-'.$number.'" class="form-control input-sm region select2 select-center">'.$optValregion.'</select></div>';

			$lokasi = '<div class="form-group m-b-0"><select id="lokasi-'.$number.'" class="form-control input-sm lokasi select2 select-center">'.$optVallokasi.'</select></div>';

			// $project_owner_unit = '<div class="form-group m-b-0"><select id="project_owner_unit-'.$number.'" class="form-control input-sm project_owner_unit select2 select-center">'.$optValprojectownerunit.'</select></div>';

			$cip = '<div class="form-group m-b-0"><select id="cip-'.$number.'" class="form-control input-sm cip select2 select-center">'.$lov_cip.'</select></div>';


				$row[] = array(
							'no'                    => $number,
							'gr_line_id'            => $value['GR_LINE_ID'],
							'asset_type'            => $asset_type,
							'budget_type'           => $budget_type,
							'project_owner'         => $project_owner,
							'serial_number'         => $serial_number,
							'merek'                 => $merek,
							'major_category'        => $major_category,
							'minor_category'        => $minor_category,
							// 'project_owner_unit'    => $project_owner_unit,

							'item_name'             => $value['ITEM_NAME'],
							'item_description'      => $value['ITEM_DESCRIPTION'],
							'quantity'              => $value['QUANTITY'],
							'unit_price'            => number_format( $value['ITEM_PRICE'],0,',','.'),
							'total_price'           => number_format( $value['TOTAL_PRICE'],0,',','.'),
							// 'asset_type'         => $value['ASSET_TYPE'],
							// 'serial_number'      => $value['SERIAL_NUMBER'],
							// 'merek'              => $value['MEREK'],
							// 'major_category'     => $value['MAJOR_NAME'],
							// 'minor_category'     => $value['MINOR_NAME'],
							'region'                => $value['REGION_NAME'],
							'lokasi'                => $value['LOCATION_NAME'],
							'project_owner_unit' => $value['OWNERSHIP_NAME'],
							'umur_manfaat'          => $umur_manfaat,
							'cip'                   => $cip,
							'no_invoice'            =>$value['NO_INVOICE'],
							'invoice_date'          => dateFormat($value['INVOICE_DATE'], 5, false),
							'uom'                   => $value['UOM'],
							'receipt_date'          => dateFormat($value['RECEIPT_DATE'], 5, false),
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


	public function action_approval(){


		$id_gr           = $this->input->post('id_gr');
		$level           = $this->input->post('level');
		$remark          = $this->input->post('remark');
		$approval        = $this->input->post('approval');

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
		$update = $this->crud->update("TRX_APPROVAL_GR", $data, array("GR_HEADER_ID" => $id_gr, "LEVEL" => $level));

		if($update !== -1){

			$get_submitter   =  $this->gr->get_submitter_by_id_gr($id_gr);
			$submitter_email = "";
			$submitter_name  = "";

			if($get_submitter){
				$submitter_email = $get_submitter['PIC_EMAIL'];
				$submitter_name  = $get_submitter['PIC_NAME'];
			}
			$email_cc      = array();

			if($status == "approved"):
				
				$dataPrUpdt['STATUS'] = $status;

				if($submitter_email != "" && $submitter_name != ""){

					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}
					// $this->_email_approval($recipient, $id_gr, $status, $remark);
				}
				$dataPrUpdt['STATUS_DESCRIPTION'] = "Approved by ".get_user_data($this->session->userdata('user_id'));

				$this->crud->update("GR_HEADER", $dataPrUpdt, array("GR_HEADER_ID" => $id_gr));
				$this->_invoicing_to_gl($id_gr);

			else:
				if($submitter_email != "" && $submitter_name != ""){

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$recipient['email'] = $submitter_email;
					$recipient['name']  = $submitter_name;
					
					// $this->_email_approval($recipient, $id_gr, $status, $remark);
				}
				
				// $this->_email_to_hou_buyer($recipient, $id_gr, $status, $remark);
				$this->crud->update("GR_HEADER", array("STATUS" => $status, "STATUS_DESCRIPTION" => ucfirst($status)." by ".get_user_data($this->session->userdata('user_id'))), array("GR_HEADER_ID" => $id_gr));

			endif;

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		echo json_encode($result);

	}

	public function update_gr(){

		$id_gr     = $this->input->post('id_gr');
		$data_line = $this->input->post('data_line');
		$messages  = "";
		$status    = false;

		foreach ($data_line as $key => $value) {

			$data_update = array(
									"ASSET_TYPE"                  => $value['asset_type'],
									"SERIAL_NUMBER"               => $value['serial_number'],
									"MEREK"                       => $value['merek'],
									"MAJOR_CATEGORY"              => $value['major_category'],
									"MINOR_CATEGORY"              => $value['minor_category'],
									// "PROJECT_OWNERSHIP_UNIT_CODE" => $value['project_owner_unit'],
									"CIP"                         => $value['cip'],
									"UMUR_MANFAAT"                => $value['umur_manfaat']
								);
			/*echo "GR ID : " . $value['gr_line_id'];
			echo_pre($data_update);*/
			$update_lines = $this->crud->update("GR_LINE", $data_update, array("GR_LINE_ID" => $value['gr_line_id']));

		}

		$this->crud->update("GR_HEADER", array("IS_REVIEW" => 1), array("GR_HEADER_ID" => $id_gr));
		// echo $this->db->last_query();
		// echo 'x';die;

		if($update_lines !== -1){
			$status    = true;
		}else{
			$messages = "Failed to Update GR Line";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }
	

	
	public function load_gr_to_approve(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$pic_email = $this->session->userdata('email');

		$get_all         = $this->gr->get_gr_to_approve($pic_email, $status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['GR_HEADER_ID'],
						'id_gr'              => encrypt_string($value['GR_HEADER_ID'], true),
						'id_gr_approval'     => encrypt_string($value['GR_HEADER_ID']."-".$value['ID_GR_APPROVAL'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'gr_number'          => $value['GR_NUMBER'],
						'no_bast'            => $value['NO_BAST'],
						'status'             => $value['STATUS'],
						'level'              => $value['LEVEL'],
						'status_description' => $status_description,
						'fs_date'            => dateFormat($value['GR_DATE'], 5, false)
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
	
	public function load_gr_to_review(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');

		$get_all         = $this->gr->get_gr_to_review($status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['GR_HEADER_ID'],
						'id_gr'              => encrypt_string($value['GR_HEADER_ID'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'gr_number'          => $value['GR_NUMBER'],
						'no_bast'            => $value['NO_BAST'],
						'status'             => $value['STATUS'],
						'status_description' => $status_description,
						'fs_date'            => dateFormat($value['GR_DATE'], 5, false)
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


    private function _email_approval($recipient, $id_gr, $type, $approval_remark="", $id_approval=0){

		$get_gr      = $this->gr->get_gr_for_email($id_gr);

		$currency      = ($get_gr['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount        = number_format($get_gr['GR_AMOUNT'],0,',','.');
		$gr_name       = $get_gr['GR_NAME'];
		$gr_number     = $get_gr['GR_NUMBER'];
		$attachment    = $get_gr['DOCUMENT_UPLOAD'];
		$submitter     = $get_gr['SUBMITTER'];
		$currency_rate = ($get_gr['CURRENCY'] == "IDR") ? $get_gr['CURRENCY'] : $get_gr['CURRENCY'] ."/". number_format($get_gr['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("gr/approval/").encrypt_string($id_gr."-".$id_approval, true);

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient'] = $recipient['name'];

		if($approval_remark != ""){
			$data['approval_remark'] = $approval_remark;
		}

		$data['action_name'] = $action_name;
		$data['gr_number'] = $gr_number;

		$data['gr_link'] = base_url("gr-requisition/").encrypt_string($id_gr, true);

		if($type == "request_approve"){
			$data['email_greview'] = "There's new gr request $gr_number has been verified and waiting for your approval.";
			$descAdded  = "There's new gr request $gr_number has been verified and waiting for your approval.
							<br>
							<br>
							Please go through the <a href='$approval_lnk'>link</a> and confirm your approval.";
			$title_first  = "Request Approval GR";				
		}else{
			$title_first  = ucfirst($type);				
			$data['email_greview']  = "Your GR request $gr_number has been <b>$type</b> by ".$action_name;
			$descAdded  = "Your GR request $gr_number has been <b>$type</b> by ".$action_name;
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
											<td width='70%'><b>$gr_name</b></td>
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

		$data['approval_link'] = base_url("gr/approval/").encrypt_string($id_gr."-".$id_approval, true);
		$data['approval_link_all'] = base_url("gr/approval");

		$file_view  = "gr_".$type;
		$title = "GR ".ucfirst($title_first);
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = $title . " - " . $gr_number . " - " . $gr_name;
		$body       = $this->load->view('email/'.$file_view, $data, TRUE);

		$attachment = ($attachment) ? FCPATH.'/uploads/gr_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }



    private function _invoicing_to_gl($id_gr){

		$this->load->model('GL_mdl', 'gl');
		$get_gr_to_invoice = $this->gl->get_gr_to_invoice($id_gr);

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

		$data = array();
		$dataCallProc = array();
		$po_number = "";

    	foreach ($get_gr_to_invoice as $key => $value) {

			$tgl_invoice = (empty($value['INVOICE_DATE'])) ? $date_now : $value['INVOICE_DATE'];
			$no_invoice  = (empty($value['NO_INVOICE'])) ? $value['FPJP_NUMBER'] : $value['NO_INVOICE'];
			$description = ($value['ITEM_NAME'] != '') ? $value['ITEM_NAME'] : $value['ITEM_DESCRIPTION'];

			$po_number = $value['PO_NUMBER'];

			$dataCallProc[] = array("PO_NUMBER" => $po_number, "TOTAL_PRICE" => $value['TOTAL_PRICE'], "NATURE" => $value['NATURE']);

    		$data[] = array(
						'TGL_INVOICE'          => $date_now,
						'INVOICE_DATE'         => $tgl_invoice,
						'BATCH_NAME'           => $batch_name_usr,
						'NO_JOURNAL'           => $no_jurnal,
						'NAMA_VENDOR'          => $value['VENDOR_NAME'],
						'NO_INVOICE'           => $no_invoice,
						'NO_KONTRAK'           => $value['PO_NUMBER'],
						'DESCRIPTION'          => $description,
						'DPP'                  => $value['TOTAL_PRICE'],
						// 'ORIGINAL_AMOUNT_FPJP' => $value['ORIGINAL_AMOUNT'],
						'NO_FPJP'              => NULL,
						'NAMA_REKENING'        => $value['VENDOR_BANK_ACCOUNT_NAME'],
						'NAMA_BANK'            => $value['VENDOR_BANK_NAME'],
						'ACCT_NUMBER'          => $value['VENDOR_BANK_ACCOUNT'],
						'NATURE'               => $value['NATURE'],
						'NO_URUT_JURNAL'       => $no_urut,
						'CURRENCY'             => $value['CURRENCY']
					);

			$no_urut++;

    	}

    	if(count($data) > 0){

    		$valuetrue = $this->gl->insert_gl_header_import($data);

			if($valuetrue){
				if($this->crud->call_procedure("UPLOAD_BATCH") !== -1){
					if($this->crud->call_procedure("JURNAL_HEADERS") !== -1 && $this->crud->call_procedure("Journal_B_Tax") !== -1){
					/*	if($po_number):
							// $this->crud->call_procedure('REVERSE_ACCRUAL_PO_NEW', $po_number);
							$param[] = $po_number;
							$param[]   = $total_price_gr;
							$param[]   = $nature;
							$this->crud->call_procedure('REVERSE_ACCRUAL_PO_NEW', $po_number);
							// call REVERSAL_ACCRUED_NEW ('PO-TEST002',70000000,'54221001');
						endif;
*/
						if($dataCallProc){
							foreach ($dataCallProc as $key => $value) {
								$param = array();
								$param[] = $value['PO_NUMBER'];
								$param[] = $value['TOTAL_PRICE'];
								$param[] = $value['NATURE'];
								$this->crud->call_procedure('REVERSAL_ACCRUED_NEW', $param);
								unset($param);
							}
						}

						return $no_jurnal;
					}
				}
			}else{
				return false;
			}

    	}else{
    		return true;
    	}
		
    }

}

/* End of file ApprovalGR_ctl.php */
/* Location: ./application/controllers/gr_po/ApprovalGR_ctl.php */