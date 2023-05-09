<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->load->model('purchase_mdl', 'purchase');

	}

	public function index()
	{
		
	}


	public function requisition()
	{

		$data['title']         = "Purchase Requisition";
		$data['module']        = "datatable";
		$data['template_page'] = "pages/purchase_requisition";
		$data['directorat']    = get_all_directorat();
		$data['pr_status']     = get_status_pr();

		$group = $this->session->userdata('group_id');

			foreach ($group as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName['NAME'];
			}

		$data['group_name']    = $group_name;

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => "", "class" => "active" );

		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}

	public function create_pr(){

		$insert = 1;

		if($insert > 0){
			$result['status']    = true;
		}
		else{
			$result['status']   = false;
			$result['messages'] = "Failed to Create PR";
		}

		echo json_encode($result);

	}

	public function new_pr(){

		$data['title']         = "Create New PR";
		$data['module']        = "datatable";
		$data['template_page'] = "pages/purchase_requisition_create";
		$data['directorat']    = get_all_directorat();

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => base_url('budget/purchase-requisition'), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}

	public function new_po(){

		$data['title']          = "Purchase Order Create";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/purchase_order_inquiry";
		$data['directorat'] = $this->crud->read("MASTER_DIRECTORAT", FALSE, "DIRECTORAT_NAME");

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Purchase Order", "link" => base_url('budget/purchase-order'), "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}

	public function create_po($pr_header_id){

		$decrypt   = str_replace($this->config->item('encryption_key'), "", base64url_decode($pr_header_id));
		$pr_header_id = $decrypt;

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id));

		if($check_exist > 0){

			$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id));

			$data['title']         = "Create PO for PR ".$get_pr_header['PR_NUMBER'];
			$data['module']        = "datatable";
			$data['template_page'] = "pages/purchase_order_create";

			$data['pr_header_id']  = $get_pr_header['PR_HEADER_ID'];
			$data['pr_number']     = $get_pr_header['PR_NUMBER'];
			$data['pr_name']       = $get_pr_header['PR_NAME'];
			$data['pr_date']       = dateFormat($get_pr_header['PR_DATE'], 5, false);
			$data['pr_amount']     = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
			$data['pr_currency']   = $get_pr_header['CURRENCY'];
			$data['pr_directorat'] = $get_pr_header['ID_DIR_CODE'];
			$data['pr_division']   = $get_pr_header['ID_DIVISION'];
			$data['pr_unit']       = $get_pr_header['ID_UNIT'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Order", "link" => base_url('budget/purchase-order'), "class" => "" );
			$breadcrumb[] = array( "name" => "PO Inquiry", "link" => base_url('budget/purchase-order/create'), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('budget/purchase-order/create');

		}

	}



	public function view_pr($pr_header_id){

		$decrypt      = str_replace($this->config->item('encryption_key'), "", base64url_decode($pr_header_id));
		$pr_header_id = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id));

		if($check_exist > 0){

			$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id));

			$data['title']         = "PR ".$get_pr_header['PR_NUMBER'];
			$data['module']        = "datatable";
			$data['template_page'] = "pages/purchase_requisition_view";
			$data['directorat']    = get_all_directorat();


			$data['pr_header_id']  = $pr_header_id;
			$data['pr_number']     = $get_pr_header['PR_NUMBER'];
			$data['pr_name']       = $get_pr_header['PR_NAME'];
			$data['pr_date']       = dateFormat($get_pr_header['PR_DATE'], 5, false);
			$data['pr_amount']     = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
			$data['pr_currency']   = $get_pr_header['CURRENCY'];
			$data['pr_directorat'] = $get_pr_header['ID_DIR_CODE'];
			$data['pr_division']   = $get_pr_header['ID_DIVISION'];
			$data['pr_unit']       = $get_pr_header['ID_UNIT'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => base_url('budget/purchase-requisition'), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('budget/purchase-requisition');

		}

	}


	public function view_po($po_header_id){

		$decrypt      = str_replace($this->config->item('encryption_key'), "", base64url_decode($po_header_id));
		$po_header_id = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PO_HEADER", array("PO_HEADER_ID" => $po_header_id));

		if($check_exist > 0){

			$get_po_header = $this->purchase->get_po_header_by_id($po_header_id);

			$data['title']         = "PO ".$get_po_header['PR_NUMBER'];
			$data['module']        = "datatable";
			$data['template_page'] = "pages/purchase_order_view";

			$data['po_header_id']  = $po_header_id;
			$data['pr_number']     = $get_po_header['PR_NUMBER'];
			$data['pr_name']       = $get_po_header['PR_NAME'];
			// $data['po_name']       = $get_po_header['PO_NAME'];
			$data['po_type']       = $get_po_header['PO_TYPE'];
			$data['po_reference']  = $get_po_header['PO_REFERENCE'];
			$data['po_date']       = dateFormat($get_po_header['PO_DATE'], 5, false);
			$data['po_amount']     = number_format($get_po_header['PO_AMOUNT'],0,',','.');
			$data['po_currency']   = $get_po_header['CURRENCY'];
			$data['po_directorat'] = $get_po_header['ID_DIR_CODE'];
			$data['po_division']   = $get_po_header['ID_DIVISION'];
			$data['po_unit']       = $get_po_header['ID_UNIT'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Order", "link" => base_url('budget/purchase-order'), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('budget/purchase-order');

		}

	}

	public function edit_pr($pr_header_id){

		$decrypt      = str_replace($this->config->item('encryption_key'), "", base64url_decode($pr_header_id));
		$pr_header_id = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id));

		if($check_exist > 0){

			$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $pr_header_id));

			$data['title']         = "Edit PR ".$get_pr_header['PR_NUMBER'];
			$data['module']        = "datatable";
			$data['template_page'] = "pages/purchase_requisition_edit";

			$data['pr_header_id']  = $pr_header_id;
			$data['pr_number']     = $get_pr_header['PR_NUMBER'];
			$data['pr_name']       = $get_pr_header['PR_NAME'];
			$data['pr_date']       = dateFormat($get_pr_header['PR_DATE'], 5, false);
			$data['pr_amount']     = number_format($get_pr_header['PR_AMOUNT'],0,',','.');
			$data['pr_currency']   = $get_pr_header['CURRENCY'];
			$data['pr_directorat'] = $get_pr_header['ID_DIR_CODE'];
			$data['pr_division']   = $get_pr_header['ID_DIVISION'];
			$data['pr_unit']       = $get_pr_header['ID_UNIT'];
			$data['pr_status']     = $get_pr_header['STATUS'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Requisition", "link" => base_url('budget/purchase-requisition'), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('budget/purchase-requisition');
			
		}

	}

	public function edit_po($po_header_id){

		$decrypt      = str_replace($this->config->item('encryption_key'), "", base64url_decode($po_header_id));
		$po_header_id = (int) $decrypt;

		$check_exist = $this->crud->check_exist("PO_HEADER", array("PO_HEADER_ID" => $po_header_id));

		if($check_exist > 0){

			$get_po_header = $this->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $po_header_id));
			$get_pr_header = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $get_po_header['ID_PR_HEADER_ID']));

			$data['title']         = "Edit PO ".$get_po_header['PO_NUMBER'];
			$data['module']        = "datatable";
			$data['template_page'] = "pages/purchase_order_edit";

			$data['pr_header_id']  = $get_po_header['ID_PR_HEADER_ID'];
			$data['po_header_id']  = $po_header_id;
			$data['pr_number']     = $get_pr_header['PR_NUMBER'];
			$data['pr_name']       = $get_pr_header['PR_NAME'];
			// $data['po_name']       = $get_po_header['PO_NAME'];
			$data['po_type']       = $get_po_header['PO_TYPE'];
			$data['po_reference']  = $get_po_header['PO_REFERENCE'];
			$data['po_date']       = dateFormat($get_po_header['PO_DATE'], 5, false);
			$data['po_amount']     = number_format($get_po_header['PO_AMOUNT'],0,',','.');
			$data['po_currency']   = $get_pr_header['CURRENCY'];
			$data['po_directorat'] = $get_pr_header['ID_DIR_CODE'];
			$data['po_division']   = $get_pr_header['ID_DIVISION'];
			$data['po_unit']       = $get_pr_header['ID_UNIT'];
			$data['po_status']     = $get_po_header['STATUS'];

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Purchase Order", "link" => base_url('budget/purchase-order'), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PO Not Exist');
			redirect('budget/purchase-order');
			
		}

	}

	public function load_data_header(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$id_dir_code = $this->input->post('directorat');
		$status      = $this->input->post('status');
		$date_from   = "";
		$date_to     = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all         = $this->purchase->get_purchase_header($id_dir_code, $status, $date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'           => $number,
						'directorat'   => get_directorat($value['ID_DIR_CODE']),
						'pr_header_id' => base64url_encode($value['PR_HEADER_ID'].$this->config->item('encryption_key')),
						'id'           => $value['PR_HEADER_ID'],
						'pr_number'    => $value['PR_NUMBER'],
						'pr_name'      => $value['PR_NAME'],
						'status'       => $value['STATUS'],
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

	// added by adi baskoro
	function download_data_pr_header()
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
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "PR Date");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Total Amount");

		$directorate = $this->input->get('directorate');
		$status = $this->input->get('status');
		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$hasil = $this->purchase->get_download_data_pr_header($directorate, $status, $date_from,$date_to);

		$numrow  = 2;
		$number = 1;

		foreach($hasil->result_array() as $row)	{

			$pr_date = date("d-m-Y",strtotime($row['PR_DATE']));
			$curr = strtoupper($row['CURRENCY']);

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['PR_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['PR_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['STATUS']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $pr_date);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $curr);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['PR_AMOUNT']);
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
		header('Content-Disposition: attachment; filename="Purchase Requisition.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

		}

		// added by adi baskoro
	function download_data_po_header()
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

		$status = $this->input->get('status');
		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$hasil = $this->purchase->get_download_data_po_header($status, $date_from,$date_to);

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

	public function change_status_pr(){

		$pr_header_id = $this->input->post('pr_header_id');
		$status       = $this->input->post('status');
		
		$result['status']   = false;
		$result['messages'] = "Failed to change status";

		$data = array(
						"STATUS" => $status
					);
		
		$changeStatus = $this->crud->update("PR_HEADER", $data, array("PR_HEADER_ID" => $pr_header_id));

		$getFS = $this->crud->read_by_param_specific("PR_LINES", array("PR_HEADER_ID" => $pr_header_id), "ID_FS", true);
		$changeStatusFS = array();

		/*if($status == "Canceled"){
			$dataFS = array("STATUS" => "approved");
		}
		else{
			$dataFS = array("STATUS" => "fs used");
		}

		foreach ($getFS as $key => $value) {
			if($this->crud->update("FS_BUDGET", $dataFS, array("ID_FS" => $value['ID_FS'])) == -1){
				$changeStatusFS[] = false;
			}
		}*/

		if($status == "canceled"){
			$status_FS = "approved";
		}
		else{
			$status_FS = "fs used";
		}

		$this->budget->update_fs_status($getFS, $status_FS, "PR");

		if($changeStatus !== -1){
			if(!in_array(FALSE, $changeStatusFS)){
				$result['status']   = true;
				$result['messages'] = "Status successfully changed";
			}else{
				$messages = "Failed to update FS";
			}
		}

		echo json_encode($result);
	}

	public function change_status_po(){

		$po_header_id = $this->input->post('po_header_id');
		$pr_header_id = $this->input->post('pr_header_id');
		$status       = $this->input->post('status');

		$result['status']   = false;
		$result['messages'] = "Failed to change status";

		$data = array(
						"STATUS" => $status
					);
		
		$changeStatus = $this->crud->update("PO_HEADER", $data, array("PO_HEADER_ID" => $po_header_id));

		if($status == "Canceled"){
			$dataPR = array("STATUS" => "approved");
		}
		else{
			$dataPR = array("STATUS" => "po created");
		}

		if($changeStatus !== -1 && $this->crud->update("PR_HEADER", $dataPR, array("PR_HEADER_ID" => $pr_header_id)) !== -1 ){

			$result['status']   = true;
			$result['messages'] = "Status successfully changed";
		}

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

	public function delete_po(){

		$id       = $this->input->post('id');
		$category = $this->input->post('category');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		if($category == "header"){
			$get_po_header = $this->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $id));
			$pr_header_id  = $get_po_header['ID_PR_HEADER_ID'];
			$delete        = $this->crud->delete("PO_HEADER", array("PO_HEADER_ID" => $id));

			/*if($delete > 0 && $this->crud->update("PR_HEADER", array("STATUS" => "Approved"), array("PR_HEADER_ID" => $pr_header_id)) !== -1 ){
				$delete = 1;
			}
			else{
				$delete = 0;
			}*/
		}
		elseif($category == "lines"){
			$delete = $this->crud->delete("PO_LINES", array("PO_LINES_ID" => $id));
		}
		else{
			$delete = $this->crud->delete("PO_DETAIL", array("PO_DETAIL_ID" => $id));
		}

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}


	public function load_data_lines(){

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
						'rkap_name'      => $value['RKAP_DESCRIPTION'],
						'fs_name'        => $value['FS_NUMBER'],
						'line_name'      => $value['PR_LINE_NAME'],
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

	public function load_data_line_po(){

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

				$row[] = array(
						'po_line_id'        => $value['PO_LINE_ID'],
						'id_rkap_line'      => $value['ID_RKAP_LINE'],
						'no'                => $number,
						'tribe'             => $value['TRIBE_USECASE'],
						'rkap_name'         => $value['RKAP_DESCRIPTION'],
						'line_name'         => $value['PR_LINE_NAME'],
						'nominal'           => number_format($value['PR_LINE_AMOUNT'],0,',','.'),
						'po_number'         => $value['PO_NUMBER'],
						'po_name'           => $value['PO_LINE_DESC'],
						'nominal_amount'    => number_format($value['PO_LINE_AMOUNT'],0,',','.'),
						"po_period_from"    => dateFormat($value['PO_PERIOD_FROM'], 5, false),
						"po_period_to"      => dateFormat($value['PO_PERIOD_TO'], 5, false),
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
				
				$valDateFrom    = ($value['PO_PERIOD_FROM'] != "") ? dateFormat($value['PO_PERIOD_FROM'], 5, false) : date('d-m-Y');
				$valDateTo      = ($value['PO_PERIOD_TO'] != "") ? dateFormat($value['PO_PERIOD_TO'], 5, false) : date('d-m-Y');
				
				$vendor_name              = $value['VENDOR_NAME'];
				$bank_name_val            = $value['VENDOR_BANK_NAME'];
				$vendor_bank_account_name = $value['VENDOR_BANK_ACCOUNT_NAME'];
				$vendor_bank_account      = $value['VENDOR_BANK_ACCOUNT'];
				
				$po_number       = '<div class="form-group m-b-0"><input id="po_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_number" value="'.$value['PO_NUMBER'].'"></div>';
				$po_line_name    = '<div class="form-group m-b-0"><input id="po_line_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_line_name" value="'.$value['PO_LINE_DESC'].'"></div>';
				$po_amount       = '<div class="form-group m-b-0"><input id="po_amount-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_amount text-right money-format" type="text"  value="'.$value['PO_AMOUNT'].'" readonly></div>';
				$bank_name       = '<div class="form-group m-b-0"><input id="bank_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm bank_name" value="'.$bank_name_val.'" disabled></div>';
				$account_name    = '<div class="form-group m-b-0"><input id="account_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm account_name" value="'.$vendor_bank_account_name.'" disabled></div>';
				$account_number  = '<div class="form-group m-b-0"><input id="account_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm account_number" value="'.$vendor_bank_account.'" disabled></div>';
				$po_period_from  = '<div class="form-group m-b-0"><input id="po_period_from-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_period_from date-picker-po" value="'.$valDateFrom.'"></div>';
				$po_period_to    = '<div class="form-group m-b-0"><input id="po_period_to-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_period_to date-picker-po" value="'.$valDateTo.'"></div>';

				$disabled = '';

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

				$row[] = array(
							'po_line_key'                => "k".strtolower(generateRandomString(5)),
							'pr_lines_id'                => $value['PR_LINES_ID'],
							'no'                         => $value['PR_LINES_NUMBER'],
							'tribe'                      => $value['TRIBE_USECASE'],
							'id_rkap_line'               => $value['ID_RKAP_LINE'],
							'rkap_name'                  => $value['RKAP_DESCRIPTION'],
							'pr_name'                    => $value['PR_LINE_NAME'],
							'pr_amount'                  => number_format($value['PR_LINE_AMOUNT'],0,',','.'),
							'po_number'                  => $po_number,
							'po_line_name'               => $po_line_name,
							'po_amount'                  => $po_amount,
							'po_period_from'             => $po_period_from,
							'po_period_to'               => $po_period_to,
							'vendor_name'                => $vendor_opt,
							'nama_bank'                  => $bank_name_val,
							'vendor_bank_name'           => $bank_name,
							'vendor_bank_account'        => $account_name,
							'vendor_bank_account_number' => $account_number,
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


	public function load_data_pr_for_po_edit(){

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

		$vendor = get_all_vendor();
		foreach ($vendor as $key => $v)
		{
		    $sort[$key] = $v['NAMA_VENDOR'];
		}
		array_multisort($sort, SORT_ASC, $vendor);
		
		$optVendor1 = '<option value="0" data-no_rek="" data-nama_rek="" data-bank="">-- Select Vendor --</option>';
		foreach ($vendor as $key => $val) {

			$vendorArr[] = array(
									"NAMA_VENDOR"   => $val['NAMA_VENDOR'],
									"NAMA_BANK"     => $val['NAMA_BANK'],
									"NAMA_REKENING" => $val['NAMA_REKENING'],
									"ACCT_NUMBER"   => $val['ACCT_NUMBER']
								);
		}

		if($total > 0){

			foreach($data as $value) {

				$valDateFrom = ($value['PO_PERIOD_FROM'] != "") ? dateFormat($value['PO_PERIOD_FROM'], 5, false) : date('d-m-Y');
				$valDateTo   = ($value['PO_PERIOD_TO'] != "") ? dateFormat($value['PO_PERIOD_TO'], 5, false) : date('d-m-Y');

				$vendor_name = $value['VENDOR_NAME'];

				$po_number    = '<div class="form-group m-b-0"><input id="po_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_number" value="'.$value['PO_NUMBER'].'"></div>';
				$po_name      = '<div class="form-group m-b-0"><input id="po_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_name" value="'.$value['PO_LINE_DESC'].'"></div>';
				$po_amount    = '<div class="form-group m-b-0"><input id="po_amount-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_amount text-right money-format" type="text"  value="'.$value['PO_AMOUNT'].'" readonly></div>';
				$bank_name    = '<div class="form-group m-b-0"><input id="bank_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm bank_name" value="'.$value['VENDOR_BANK_NAME'].'"></div>';
				$bank_account = '<div class="form-group m-b-0"><input id="bank_account-'.$number.'" data-id="'.$number.'" class="form-control input-sm bank_account" value="'.$value['VENDOR_BANK_ACCOUNT'].'"></div>';
				$po_period_from  = '<div class="form-group m-b-0"><input id="po_period_from-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_period_from date-picker-po" value="'.$valDateFrom.'"></div>';
				$po_period_to  = '<div class="form-group m-b-0"><input id="po_period_to-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_period_to date-picker-po" value="'.$valDateTo.'"></div>';

				$optVendor = $optVendor1;

				foreach ($vendorArr as $k => $vVendor) {

					$vendor   = $vVendor['NAMA_VENDOR'];
					$bank     = $vVendor['NAMA_BANK'];
					$nama_rek = $vVendor['NAMA_REKENING'];
					$no_rek   = $vVendor['ACCT_NUMBER'];

					$selected = ($vendor == $vendor_name) ? ' selected' : '';

					$optVendor .= '<option value="'.$vendor.'" data-no_rek="'.$no_rek.'" data-nama_rek="'.$nama_rek.'" data-bank="'.$bank.'"'.$selected.'>'.$vendor.'</option>';
				}

				$vendor_opt = '<div class="form-group m-b-0"><input id="vendor_name-'.$number.'" data-id="'.$number.'" class="form-control input-sm vendor_name" value="'.$optVendor.'"></div>';

				$row[] = array(
							'po_line_key'         => "k".strtolower(generateRandomString(5)),
							'pr_lines_id'         => $value['PR_LINES_ID'],
							'no'                  => $value['PR_LINES_NUMBER'],
							'tribe'               => $value['TRIBE_USECASE'],
							'id_rkap_line'        => $value['ID_RKAP_LINE'],
							'rkap_name'           => $value['RKAP_DESCRIPTION'],
							'pr_name'             => $value['PR_LINE_NAME'],
							'pr_amount'           => number_format($value['PR_LINE_AMOUNT'],0,',','.'),
							'po_number'           => $po_number,
							'po_name'             => $po_name,
							'po_amount'           => $po_amount,
							'po_period_from'      => $po_period_from,
							'po_period_to'        => $po_period_to,
							'vendor_name'         => $vendor_opt,
							'vendor_bank_name'    => $bank_name,
							'vendor_bank_account' => $bank_account
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
						'detail_desc'       => $value['PR_DETAIL_DESC'],
						'nature'            => $value['NATURE']." - ".$value['DESCRIPTION'],
						'quantity'          => $value['QUANTITY'],
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

	public function load_data_detail_po(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$po_line_id = $this->input->post('po_line_id');
		$category   = $this->input->post('category');
		
		$get_all = $this->purchase->get_detail_po($po_line_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {
				
				$number = $value['PO_DETAIL_NUMBER'];

				$row[] = array(
						'no'          => $number,
						'detail_desc' => $value['PR_DETAIL_DESC'],
						'quantity'    => $value['QUANTITY'],
						'price'       => number_format($value['PRICE'],0,',','.'),
						'nominal'     => number_format($value['PR_DETAIL_AMOUNT'],0,',','.'),
						'po_desc'     => $value['DESCRIPTION_PO'],
						'nominal_po'  => number_format($value['PO_DETAIL_AMOUNT'],0,',','.')
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

	public function load_pr_detail_for_edit(){

		$pr_lines_id = $this->input->post('pr_lines_id');

		$get_all = $this->purchase->get_purchase_details($pr_lines_id);

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];

		if($total > 0){

			foreach($data as $value) {

				$number      = $value['PR_DETAIL_NUMBER'];
				$price_val   = number_format($value['PRICE'],0,',','.');
				$nominal_val = number_format($value['PR_DETAIL_AMOUNT'],0,',','.');

				$rkap_desc_val  = '<div class="form-group m-b-0"><input id="rkap_desc-'.$number.'" class="form-control input-sm rkap_desc" value="'.$value['PR_DETAIL_DESC'].'"></div>';
				$nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'.$number.'" class="form-control input-sm nature_opt select-center"><option value="'.$value['NATURE'].'">'.$value['NATURE'].'</option></select></div>';
				$quantity       = '<div class="form-group m-b-0"><input id="quantity-'.$number.'" data-id="'.$number.'" class="form-control input-sm quantity text-center" value="'.$value['QUANTITY'].'" min="1" max="99999" type="number"></div>';
				$price          = '<div class="form-group m-b-0"><input id="price-'.$number.'" data-id="'.$number.'" class="form-control input-sm price text-right money-format" value="'.$price_val.'"></div>';
				$nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'.$number.'" class="form-control input-sm nominal_detail text-right" value="'.$nominal_val.'" readonly></div>';

				$row[] = array(
							'pr_detail_id'      => $value['PR_DETAIL_ID'],
							'pr_lines_id'       => $value['PR_LINES_ID'],
							'no'                => $number,
							'rkap_desc'         => $value['PR_DETAIL_DESC'],
							// 'rkap_desc'      => $rkap_desc_val,
							'nature'            => $value['ID_MASTER_COA'],
							'nature_opt'        => $nature_opt,
							'quantity'          => $value['QUANTITY'],
							// 'quantity'       => $quantity,
							// 'price'          => $price,
							'price'             => $value['PRICE'],
							// 'nominal_detail' => $nominal_detail
							'nominal_detail'    => $value['PR_DETAIL_AMOUNT']
						);
			}

		}

		echo json_encode($row);

	}


	public function load_pr_detail_staging(){

		$result['data']            = "";
		$result['draw']            = 1;
		$result['recordsTotal']    = 1;
		$result['recordsFiltered'] = 0;

		$pr_header_id = $this->input->post('pr_header_id');
		$pr_line_id   = $this->input->post('pr_line_id');

		$get_all = $this->purchase->get_pr_detail_staging($pr_header_id, $pr_line_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$number = $value['PR_DETAIL_NUMBER'];

				$rkap_desc = '<div class="form-group m-b-0"><input id="rkap_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm rkap_desc" value="'.$value['PR_DESCRIPTION'].'"></div>';

				$nature_opt = '<div class="form-group m-b-0"><select id="nature_opt-'.$number.'" data-id="'.$number.'" class="form-control input-sm nature_opt select-center"><option value="'.$value['PR_NATURE'].'" data-name="">-- Choose --</option></select></div>';

				$nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail text-right money-format" value="'.$value['PR_NOMINAL'].'"></div>';

				$row[] = array(
						'pr_header_id'   => $value['PR_HEADER_ID'],
						'pr_line_id'     => $value['PR_LINE_ID'],
						'no'             => $value['PR_DETAIL_NUMBER'],
						'rkap_desc'      => $rkap_desc,
						'nature'         => $nature_opt,
						'nominal_detail' => $nominal_detail
						);
				$number++;

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}
		else{

			$rkap_desc      = '<div class="form-group m-b-0"><input id="rkap_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm rkap_desc"></div>';
			$nature_opt     = '<div class="form-group m-b-0"><select id="nature_opt-'.$number.'" data-id="'.$number.'" class="form-control input-sm nature_opt select-center"><option value="0" data-name="">-- Choose --</option></select></div>';
			$nominal_detail = '<div class="form-group m-b-0"><input id="nominal_detail-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail text-right money-format" value="0"></div>';

			$row[] = array(
					'pr_header_id'   => $pr_header_id,
					'pr_line_id'     => $pr_line_id,
					'no'             => $number,
					'rkap_desc'      => $rkap_desc,
					'nature'         => $nature_opt,
					'nominal_detail' => $nominal_detail
					);

			$result['data']            = $row;
			$result['draw']            = 1;
			$result['recordsTotal']    = 1;
			$result['recordsFiltered'] = 1;

		}

		echo json_encode($result);
	}

	public function load_pr_detail_for_po(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$pr_lines_id = $this->input->post('pr_lines_id');
		
		$get_all = $this->purchase->get_pr_detail_for_po($pr_lines_id);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$number = $value['PR_DETAIL_NUMBER'];

				// $po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc" value="'.$value['PO_DESC'].'"></div>';
				$po_desc           = '<div class="form-group m-b-0"><input id="po_desc-'.$number.'" data-id="'.$number.'" class="form-control input-sm po_desc" value="'.$value['DESCRIPTION_PO'].'"></div>';
				
				// $nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="'.$value['PO_AMOUNT'].'"></div>';
				$nominal_detail_po = '<div class="form-group m-b-0"><input id="nominal_detail_po-'.$number.'" data-id="'.$number.'" class="form-control input-sm nominal_detail_po text-right money-format" type="text" value="'.$value['PO_AMOUNT_DETAIL'].'"></div>';

				$row[] = array(
						'pr_lines_id'       => $value['PR_LINES_ID'],
						'pr_detail_id'      => $value['PR_DETAIL_ID'],
						'no'                => $number,
						'detail_desc'       => $value['PR_DETAIL_DESC'],
						'quantity'          => $value['QUANTITY'],
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


	public function order()
	{

		$data['title']         = "Purchase Order";
		$data['module']        = "datatable";
		$data['template_page'] = "pages/purchase_order";
		$data['po_status']     = get_status_po();

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Purchase Order", "link" => "", "class" => "active" );

		$group = $this->session->userdata('group_id');

		foreach ($group as $key => $value) {
			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

		$data['group_name']    = $group_name;

		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);
		
	}

	public function load_division()
	{


		$id_dir_code  = $this->input->post('directorat');
		$get_division = $this->budget->get_division_by_dir_code($id_dir_code);

		foreach($get_division as $row)	{

			$data[] = array(
								"id_division"   => $row['ID_DIVISION'],
								"division_name" => $row['DIVISION_NAME']
						);

		}

		echo json_encode($data);

    }

	public function load_unit()
	{

		
		$id_division = $this->input->post('division');
		$get_unit    = $this->budget->get_unit_by_division($id_division);

		foreach($get_unit as $row)	{

			$data[] = array(
								"id_unit"   => $row['ID_UNIT'],
								"unit_name" => $row['UNIT_NAME']
						);

		}

		echo json_encode($data);

    }

	public function load_tribe()
	{

		$get_tribe    = $this->budget->get_all_tribe();

		foreach($get_tribe as $row)	{

			$data[] = array(
								"id_tribe"   => $row['ID_TRIBE'],
								"tribe_code" => $row['TRIBE_CODE'],
								"tribe_desc" => $row['TRIBE_DESC']
						);

		}

		echo json_encode($data);

    }

	public function load_data_rkap()
	{

		$directorat_name = $this->input->post('directorat');
		$division_name   = $this->input->post('division');
		$unit_name       = $this->input->post('unit');
		$tribe           = $this->input->post('tribe');
		$exclude_rkap    = $this->input->post('exclude_rkap');
		$list_id_rkap    = $this->input->post('list_id_rkap');

		$get_rkap    = $this->budget->get_all_rkap_from_view($directorat_name, $division_name, $unit_name, $tribe, $exclude_rkap, $list_id_rkap);

		if($get_rkap['total'] > 0){

			$result['status'] = true;

			foreach($get_rkap['data'] as $row)	{

				$data[] = array(
									"id_rkap_line"     => $row['ID_RKAP_LINE'],
									"rkap_name"        => $row['RKAP_DESCRIPTION']." - ".date("M-y", strtotime($row['MONTH']))
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_data_nature()
	{

		$id_rkap = $this->input->post('id_rkap');
		$get_nature   = $this->budget->get_nature_by_rkap($id_rkap);

		if($get_nature['total'] > 0){

			$result['status'] = true;

			foreach($get_nature['data'] as $row)	{

				$data[] = array(
									"id_coa"      => $row['ID_MASTER_COA'],
									"nature_desc" => $row['NATURE']." - ".$row['DESCRIPTION']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_data_fund_available()
	{

		$id_rkap          = $this->input->post('id_rkap');
		$get_fund_nominal = $this->budget->get_fund_av_from_view($id_rkap);

		if($get_fund_nominal['total'] > 0){

			$row = $get_fund_nominal['data'];
			$data['fund_av'] = $row['FA_FS'];

			$result['status'] = true;
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_data_fa_rkap()
	{

		$id_rkap          = $this->input->post('id_rkap');
		$get_fund_nominal = $this->budget->get_fa_rkap($id_rkap);

		if($get_fund_nominal['total'] > 0){

			$row = $get_fund_nominal['data'];
			$data['fa_rkap'] = $row['FA_RKAP'];

			$result['status'] = true;
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_data_nominal_from_pr()
	{

		$id_rkap          = $this->input->post('id_rkap');
		$get_fund_nominal = $this->purchase->get_nominal_from_pr($id_rkap);

		if($get_fund_nominal['total'] > 0){

			$row = $get_fund_nominal['data'];
			$data['pr_lines_id'] = $row['PR_LINES_ID'];
			$data['nominal']     = $row['PR_LINE_AMOUNT'];

			$result['status'] = true;
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_data_rkap_view()
	{

		$category        = $this->input->post('category');
		$directorat_name = $this->input->post('directorat');
		$division_name   = $this->input->post('division');
		$unit_name       = $this->input->post('unit');
		$tribe           = $this->input->post('tribe');
		$list_id_rkap    = $this->input->post('list_id_rkap');

		$get_rkap    = $this->budget->get_rkap_from_view($category, $directorat_name, $division_name, $unit_name, $list_id_rkap);

		if($get_rkap['total'] > 0){

			$result['status'] = true;

			foreach($get_rkap['data'] as $row)	{

				if($category == "division"){
					$data[] = array(
									"id_division" => $row['ID_DIVISION'],
									"division"    => $row['DIVISION']
							);
				}
				elseif($category == "unit"){
					$data[] = array(
									"id_unit" => $row['ID_UNIT'],
									"unit"    => $row['UNIT']
							);
				}
				elseif($category == "tribe"){
					$data[] = array(
									"tribe" => $row['TRIBE_USECASE']
							);
				}

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

    public function save_pr(){
		
		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');
		$pr_name    = $this->input->post('pr_name');
		$pr_date    = $this->input->post('pr_date');
		$amount     = $this->input->post('amount');
		$currency   = $this->input->post('currency');
		$data_line  = $this->input->post('data_line');

		if($pr_date != ""){
			$exp_pr_date = explode("-", $pr_date);
			$pr_date = $exp_pr_date[2]."-".$exp_pr_date[1]."-".$exp_pr_date[0];
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

			$dir_code = $exp_pr_number[0];
			$number   = (int) $exp_pr_number[1];;

			$number += 1;
			$number = sprintf("%'03d", $number);
			$pr_number = $get_dir['DIRECTORAT_CODE']."/".$number."/".$month."/".$year;

		}

		$amount = str_replace(".", "", $amount);

		$data = array(
						"ID_DIR_CODE" => $id_dir_code,
						"ID_DIVISION" => $division,
						"ID_UNIT"     => $unit,
						"PR_NUMBER"   => $pr_number,
						"PR_NAME"     => $pr_name,
						"CURRENCY"    => $currency,
						"PR_AMOUNT"   => (int) $amount
					);

		if($pr_date != ""){
			$data['PR_DATE'] = $pr_date;
		}

		$insert   = $this->crud->create("PR_HEADER", $data);
		$status   = false;
		$messages = "";

		if($insert > 0){

			$pr_line_number = 1;

			$status_FS = "fs used";

			foreach ($data_line as $key => $value) {

				$detail_data = $value['detail_data'];
				$id_fs       = $value['id_fs'];
				$data_lines = array(
										"PR_HEADER_ID"    => $insert,
										"PR_LINES_NUMBER" => $pr_line_number,
										"PR_LINE_NAME"    => $value['line_name'],
										"ID_RKAP_LINE"    => $value['id_rkap'],
										"ID_FS"           => $value['id_fs'],
										"PR_LINE_AMOUNT"  => $value['nominal']
									);

				$this->budget->update_fs_status($id_fs, $status_FS, "PR");

				$insert_line = $this->crud->create("PR_LINES", $data_lines);

				if($insert_line > 0){

					$pr_detail_number=1;

					foreach ($detail_data as $key => $value_dtl) {

						$data_details[] = array(
											"PR_HEADER_ID"     => $insert,
											"PR_LINES_ID"      => $insert_line,
											"PR_DETAIL_NUMBER" => $pr_detail_number,
											"PR_DETAIL_DESC"   => $value_dtl['rkap_desc'],
											"ID_MASTER_COA"    => $value_dtl['nature'],
											"QUANTITY"         => $value_dtl['quantity'],
											"PRICE"            => $value_dtl['price'],
											"PR_DETAIL_AMOUNT" => $value_dtl['nominal'],
											"CREATED_BY"       => get_user_data($this->session->userdata('user_id'))
										);
						$pr_detail_number++;
					}
				}

				$pr_line_number++;
			}

			$insert_detail = $this->crud->create_batch("PR_DETAIL", $data_details);

			if($insert_detail){
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

    public function save_pr_edit(){

		$pr_header_id = $this->input->post('pr_header_id');
		$pr_name      = $this->input->post('pr_name');
		$amount       = $this->input->post('amount');
		$currency     = $this->input->post('currency');
		$status       = $this->input->post('status');
		$data_line    = $this->input->post('data_line');

		$amount = str_replace(".", "", $amount);

		$data = array(
						"PR_NAME"    => $pr_name,
						"CURRENCY"   => $currency,
						"STATUS"     => $status,
						"PR_AMOUNT"  => (int) $amount,
						"UPDATED_BY" => get_user_data($this->session->userdata('user_id'))
					);

		$update   = $this->crud->update("PR_HEADER", $data, array("PR_HEADER_ID" => $pr_header_id));

		$getFS = $this->crud->read_by_param_specific("PR_LINES", array("PR_HEADER_ID" => $pr_header_id), "ID_FS", true);

		/*if($status == "Canceled"){
			$dataFS = array("STATUS" => "approved");
		}
		else{
			$dataFS = array("STATUS" => "fs used");
		}*/

		$changeStatusFS = array();
	/*	foreach ($getFS as $key => $value) {
			if($this->crud->update("FS_BUDGET", $dataFS, array("ID_FS" => $value['ID_FS'])) == -1){
				$changeStatusFS[] = false;
			}
		}*/

		if($status == "canceled"){
			$status_FS = "approved";
		}
		else{
			$status_FS = "fs used";
		}

		$this->budget->update_fs_status($getFS, $status_FS, "PR");

		$status   = false;
		$messages = "";

		if($update !== -1 && !in_array(FALSE, $changeStatusFS)){

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
											"ID_MASTER_COA"    => $value_dtl['nature'],
											"QUANTITY"         => $value_dtl['quantity'],
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



    public function save_po(){
		
		$pr_header_id = $this->input->post('pr_header_id');
		$data_po      = $this->input->post('data_po');
		$po_amount    = $this->input->post('po_amount');
		$po_reference = $this->input->post('po_reference');

		$data = array(
						"ID_PR_HEADER_ID" => $pr_header_id,
						"PO_AMOUNT"       => (int) $po_amount
					);

		if($po_reference != 0){
			$data['PO_TYPE']      = "Additional";
			$data['PO_REFERENCE'] = $po_reference;
		}
		else{
			$data['PO_TYPE'] = "Normal";
		}

		$insert   = $this->crud->create("PO_HEADER", $data);

		$status   = false;
		$messages = "";

		if($insert > 0){

			$pr_line_number = 1;
			foreach ($data_po as $key => $value) {

				$data_lines = array(
										"PO_HEADER_ID"             => $insert,
										"ID_RKAP_LINE"             => $value['id_rkap_line'],
										"PR_LINES_ID"              => $value['pr_lines_id'],
										"PO_NUMBER"                => $value['po_number'],
										"PO_LINE_DESC"             => $value['po_line_name'],
										"PO_LINE_AMOUNT"           => $value['po_amount'],
										"po_period_from"           => date("Y-m-d", strtotime($value['po_period_from'])),
										"po_period_to"             => date("Y-m-d", strtotime($value['po_period_to'])),
										"VENDOR_NAME"              => $value['vendor_name'],
										"VENDOR_BANK_NAME"         => $value['bank_name'],
										"VENDOR_BANK_ACCOUNT_NAME" => $value['account_name'],
										"VENDOR_BANK_ACCOUNT"      => $value['account_number']
									);

				$insert_line = $this->crud->create("PO_LINES", $data_lines);

				if($insert_line > 0){
					$detaiL_po = $value['detaiL_po'];

					$po_detail_number = 1;
					foreach ($detaiL_po as $key => $value_dtl) {

						$data_details[] = array(
											"PO_HEADER_ID"     => $insert,
											"PO_LINE_ID"       => $insert_line,
											"PR_DETAIL_ID"     => $value_dtl['pr_detail_id'],
											"Po_DETAIL_NUMBER" => $po_detail_number,
											"DESCRIPTION_PO"   => $value_dtl['po_desc'],
											"PO_DETAIL_AMOUNT" => $value_dtl['nominal'],
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
		$data_po      = $this->input->post('data_po');
		$po_amount    = $this->input->post('po_amount');
		$po_date      = $this->input->post('po_date');
		$status       = $this->input->post('status');

		if($po_date != ""){
			$exp_po_date = explode("-", $po_date);
			$po_date     = $exp_po_date[2]."-".$exp_po_date[1]."-".$exp_po_date[0];
		}

		/*
		if($status == "Canceled"){
			$dataPR = array("STATUS" => "Approved");
		}
		else{
			$dataPR = array("STATUS" => "po created");
		}*/

		$data = array(
						"STATUS"     => $status,
						"PO_AMOUNT"  => (int) $po_amount,
						"UPDATED_BY" => get_user_data($this->session->userdata('user_id'))
					);

		if($po_date != ""){
			$data['PO_DATE'] = $po_date;
		}

		$update   = $this->crud->update("PO_HEADER", $data, array("PO_HEADER_ID" => $po_header_id));

		$status   = false;
		$messages = "";

		if($update !== -1/* && $this->crud->update("PR_HEADER", $dataPR, array("PR_HEADER_ID" => $pr_header_id)) !== -1*/){

			foreach ($data_po as $key => $value) {

				$data_lines = array(
										"PO_LINE_DESC"        => $value['po_line_name'],
										"PO_LINE_AMOUNT"      => $value['po_amount'],
										"PO_PERIOD_FROM"      => date("Y-m-d", strtotime($value['po_period_from'])),
										"PO_PERIOD_TO"        => date("Y-m-d", strtotime($value['po_period_to'])),
										"VENDOR_NAME"         => $value['vendor_name'],
										"VENDOR_BANK_NAME"    => $value['bank_name'],
										"VENDOR_BANK_ACCOUNT" => $value['bank_account'],
										"UPDATED_BY"          => get_user_data($this->session->userdata('user_id'))
									);

				$update_lines = $this->crud->update("PO_LINES", $data_lines, array("PR_LINES_ID" => $value['pr_lines_id']));

				if (isset($value['detaiL_po'])){

					if($update_lines !== -1 ){
						$detaiL_po    = $value['detaiL_po'];
						$data_details = array();

						foreach ($detaiL_po as $key => $value_dtl) {

							$data_details = array(
												"DESCRIPTION_PO"   => $value_dtl['po_desc'],
												"PO_DETAIL_AMOUNT" => $value_dtl['nominal']
											);

							$this->crud->update("PO_DETAIL", $data_details, array("PR_DETAIL_ID" => $value_dtl['pr_detail_id']));
						}
					}
				}
			}

			$status    = true;
		}
		else{
			$messages = "Failed to Create PO";
		}

		
		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }


    public function auto_save_detail(){

		$pr_lines_id    = $this->input->post('pr_lines_id');
		$id_detail      = $this->input->post('id_detail');
		$pr_number      = $this->input->post('pr_number');
		$po_desc        = $this->input->post('po_desc');
		$po_amount = $this->input->post('po_amount');

		$data = array(
						"PR_NUMBER"        => $pr_number,
						"PR_LINES_ID"      => $pr_lines_id,
						"PR_DETAIL_ID"     => $id_detail,
						"DESCRIPTION_PO"   => $po_desc,
						"PO_DETAIL_AMOUNT" => (int) $po_amount
					);

		$insert   = $this->crud->create("PO_DETAIL_STAGING", $data);
		$status   = false;
		$messages = "";

		if($insert > 0){
			$status = true;
		}
		else{
			$messages = "Failed to Create PR";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);
    }



	public function load_data_pr_for_po_inquiry(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$date_from = "";
		$date_to   = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all         = $this->purchase->get_pr_for_po_inquiry($date_from, $date_to);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$pr_header_id[] = $value['PR_HEADER_ID'];
				$id_directorat_arr[] = $value['ID_DIR_CODE'];

				$row[] = array(
						'no'               => $number,
						'pr_number'        => $value['PR_NUMBER'],
						'pr_name'          => $value['PR_NAME'],
						'pr_amount'        => number_format($value['PR_AMOUNT'],0,',','.'),
						'id_directorat'    => $value['ID_DIR_CODE'],
						'directorat'       => get_directorat($value['ID_DIR_CODE']),
						'pr_header_id'     => $value['PR_HEADER_ID'],
						'pr_date'          => dateFormat($value['PR_DATE'], 5, false),
						'currency'         => strtoupper($value['CURRENCY']),
						'pr_header_id_enc' => base64url_encode($value['PR_HEADER_ID'].$this->config->item('encryption_key'))
						);
				$number++;

			}


			$get_rkap = $this->crud->read_by_param_in("PR_LINES", "PR_HEADER_ID", $pr_header_id);

			foreach($get_rkap as $value){
				$list_id_rkap[] = $value['ID_RKAP_LINE'];
			}

			foreach ($row as $key => $value) {
				$row[$key]['id_directorat_arr'] = $id_directorat_arr;
				$row[$key]['list_id_rkap'] = $list_id_rkap;
			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);
	}

    public function load_data_po(){

    	$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$date_from   = "";
		$date_to     = "";
		$status      = $this->input->post('status');

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all         = $this->purchase->get_data_po($status, $date_from, $date_to);
		// $get_all         = $this->purchase->get_po_header($status, $date_from, $date_to);
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

		$group = $this->session->userdata('group_id');

		foreach ($group as $key => $value) {
			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

		$po_inq_group = (in_array("PO Inquiry", $group_name)) ? true : false;

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

				$statusVal  = $value['STATUS'];
				$po_url_enc = base64url_encode($value['PO_HEADER_ID'].$this->config->item('encryption_key'));

				if($statusVal == "approved"){
					$status_opt = '<option value="approved" selected>Approved</option>';
					$status_opt .= '<option value="canceled">Canceled</option>';
				}
				elseif($statusVal == "canceled"){
					$status_opt = '<option value="approved">Approved</option>';
					$status_opt .= '<option value="canceled" selected>Canceled</option>';
				}

				$status = '<div class="form-group m-0"><select class="form-control input-sm action-status"'.$status_disabled.'>';
				$status .= $status_opt;
				$status .= '</select></div>';

				$action = '<a href="javascript:void(0)" class="action-view px-5" title="Click to view PO" data-id="'.$po_url_enc.'"><i class="fa fa-search text-success" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-edit px-5'.$hide_action.'" title="Click to edit PO" data-id="'.$po_url_enc.'"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-delete px-5'.$hide_action.'" title="Click to delete PO"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

				$row[] = array(
						'no'           => $number,
						'id'           => $value['PO_HEADER_ID'],
						'pr_header_id' => $value['PR_HEADER_ID'],
						'po_number'    => $value['PO_NUMBER'],
						'pr_number'    => $value['PR_NUMBER'],
						'po_name'      => $value['PO_LINE_DESC'],
						'status_act'   => $status,
						'status'       => $value['STATUS'],
						'po_date'      => dateFormat($value['PO_DATE'], 5, false),
						'currency'     => strtoupper($value['CURRENCY']),
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

	public function load_po_reference()
	{

		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');

		$get_po_reference = $this->purchase->get_po_reference($directorat, $division, $unit);


		if(count($get_po_reference) > 0){

			$result['status'] = true;

			foreach($get_po_reference as $row)	{
				$data[] = array(
								"po_number"  => $row['PO_NUMBER'],
								"po_name"    => $row['PO_LINE_DESC'],
								"po_line_id" => $row['PO_LINE_ID']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }


}

/* End of file Purchase.php */
/* Location: ./application/controllers/Purchase.php */