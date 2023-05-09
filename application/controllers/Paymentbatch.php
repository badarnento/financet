<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Paymentbatch extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Paymentbatch_mdl', 'payment_batch');

	}

	public function payment_batch()
	{

		$data['title']          = "Payment Batch";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/payment_batch";

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Finance transaction", "link" => "#", "class" => "" );
		$breadcrumb[] = array( "name" => "Payment Batch Inquiry", "link" => base_url('paymentbatch/payment_batch_inquiry'), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}
	
	public function load_data_payment_batch(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('invoice_date_from') != "" && $this->input->post('invoice_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('invoice_date_from'));
			$exp_date_to   = explode("/", $this->input->post('invoice_date_to'));

			$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$vendorname = $this->input->post('vendor_name');
		$bankname = $this->input->post('bank_name');

		$get_all = $this->payment_batch->get_payment_batch_datatable($invoice_date_from, $invoice_date_to, $vendorname, $bankname);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nmjurnal = '';
		$replacejurnal ='';

		if($total > 0){
			
			foreach($data as $value) {

				$nmjurnal =  $value['NO_JOURNAL'];
				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal);

				if($value['BANK_CHARGES'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['BANK_CHARGES'].' checked><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['BANK_CHARGES'].'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}

				$row[] = array(
					'no'             		 => $number,
					'tanggal_invoice'		 => date("d-m-Y",strtotime($value['TGL_INVOICE'])),
					'batch_name'             => $value['BATCH_NAME'],
					'no_journal'       		 => $value['NO_JOURNAL'],
					'nama_vendor'            => $value['NAMA_VENDOR'],
					'no_invoice'   			 => $value['NO_INVOICE'],
					'no_kontrak'      	  	 => $value['NO_KONTRAK'],
					'description' 			 => $value['DESCRIPTION'],
					'dpp'					 => number_format($value['DPP'],0,'.',','),
					'no_fpjp'  				 => $value['NO_FPJP'],
					'nama_rekening'      	 => $value['NAMA_REKENING'],
					'nama_bank'       		 => $value['NAMA_BANK'],
					'acct_number'         	 => $value['ACCT_NUMBER'],
					'rkap_name'       		 => $value['RKAP_NAME'],
					'top'    				 => $value['TOP'],
					'due_date' 				 => date("d-m-Y",strtotime($value['DUE_DATE'])),
					'nature' 				 => $value['NATURE'],
					'bank_charge'			 => $checkbox
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

	public function download_data_payment_batch()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No. Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "No Kontrak/PO/SPK/Nodin");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Nama Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Acct Number");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Due Date");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$vendor_name = $this->input->get('vendor_name');
		$bank_name = $this->input->get('bank_name');

		$hasil = $this->payment_batch->get_download_payment_batch($date_from,$date_to,$vendor_name,$bank_name);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$acctnumber = "";
			$acctnumber = $row['ACCT_NUMBER'];

			$stringaccnum = "'".$acctnumber;

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $stringaccnum);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DUE_DATE']);

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
		header('Content-Disposition: attachment; filename="Payment Batch.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function payment_batch_inquiry()
	{

		$data['title']          = "Payment Batch Inquiry";
		$data['module']         = "datatable";
		$data['template_page']  = "pages/payment_batch_inquiry";

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Finance transaction", "link" => "#", "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
		// $breadcrumb[] = array( "name" => "Payment Batch", "link" => base_url('paymentbatch/payment_batch'), "class" => "" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}

	public function load_data_payment_batch_inquiry(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('batch_date_from') != "" && $this->input->post('batch_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('batch_date_from'));
			$exp_date_to   = explode("/", $this->input->post('batch_date_to'));
			$exp_invoicedate   = explode("/", $this->input->post('invoice_date'));

			$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
			$invoicedate 		 = $exp_invoicedate[2]."-".$exp_invoicedate[1]."-".$exp_invoicedate[0];
		}

		$batchname = $this->input->post('batch_name');
		$batchnumber = $this->input->post('batch_number');
		$jurnalpaymentnumber = $this->input->post('journal_payment_number');
		
		$get_all = $this->payment_batch->get_payment_batch_inquiry_datatable($batch_date_from, $batch_date_to, $batchname, $batchnumber, $jurnalpaymentnumber, $invoicedate);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nmjurnal = '';
		$replacejurnal ='';

		if($total > 0){
			
			foreach($data as $value) {

				$nmjurnal =  $value['NO_JOURNAL'];
				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal);

				if($value['BANK_CHARGES'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['BANK_CHARGES'].' checked disabled><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['BANK_CHARGES'].' disabled><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}

				$row[] = array(
					'no'             		 => $number,
					'batch_date'             => date("d-m-Y",strtotime($value['BATCH_DATE'])),
					'batch_name'             => $value['BATCH_NAME'],
					'batch_number'           => $value['BATCH_NUMBER'],
					'journal_payment_number' => $value['JURNAL_PAYMENT_NUMBER'],
					'tanggal_invoice'		 => date("d-m-Y",strtotime($value['TGL_INVOICE'])),
					'no_journal'       		 => $value['NO_JOURNAL'],
					'nama_vendor'            => $value['NAMA_VENDOR'],
					'no_invoice'   			 => $value['NO_INVOICE'],
					'no_kontrak'      	  	 => $value['NO_KONTRAK'],
					'description' 			 => $value['DESCRIPTION'],
					'dpp'					 => number_format($value['DPP'],0,'.',','),
					'no_fpjp'  				 => $value['NO_FPJP'],
					'nama_rekening'      	 => $value['NAMA_REKENING'],
					'nama_bank'       		 => $value['NAMA_BANK'],
					'acct_number'         	 => $value['ACCT_NUMBER'],
					'rkap_name'       		 => $value['RKAP_NAME'],
					'top'    				 => $value['TOP'],
					'due_date' 				 => date("d-m-Y",strtotime($value['DUE_DATE'])),
					'nature' 				 => $value['NATURE'],
					'bank_charge'			 => $checkbox
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

	public function download_data_payment_batch_inquiry()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No. Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "No Kontrak/PO/SPK/Nodin");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Nama Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Acct Number");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Due Date");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$batchname = $this->input->get('batch_name');
		$batchnumber = $this->input->get('batch_number');
		$jurnalpaymentnumber = $this->input->get('journal_payment_number');
		$invoicedate = $this->input->get('invoice_date');

		// echo json_encode($_REQUEST); die();

		$hasil = $this->payment_batch->get_download_payment_batch_inquiry($date_from,$date_to,$batchname,$batchnumber,$jurnalpaymentnumber,$invoicedate);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$acctnumber = "";
			$acctnumber = $row['ACCT_NUMBER'];

			$stringaccnum = "'".$acctnumber;

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $stringaccnum);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DUE_DATE']);

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
		header('Content-Disposition: attachment; filename="Payment Batch Inquiry.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	public function load_data_journal_payment_batch(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('invoice_date_from') != "" && $this->input->post('invoice_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('invoice_date_from'));
			$exp_date_to   = explode("/", $this->input->post('invoice_date_to'));

			$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$batch_name = $this->input->post('batch_name');
		$batch_number = $this->input->post('batch_number');

		// echo $batch_name; die();

		$get_all = $this->payment_batch->get_journal_payment_batch_datatable($invoice_date_from, $invoice_date_to, $batch_name, $batch_number);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){
			
			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'accounting_date'        => date("d-m-Y",strtotime($value['GL_DATE'])),
					'currency'           	 => $value['CURRENCY'],
					'nature'         		 => $value['NATURE'],
					'account_description' 	 => $value['ACCOUNT_DESCRIPTION'],
					'debet'		 			 => $value['DEBET'],
					'kredit'       		     => $value['CREDIT'],
					'batch_name'             => $value['BATCH_NAME'],
					'batch_desc'   			 => $value['JOURNAL_DESCRIPTION']
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

	public function download_data_journal_payment_batch()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Accounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Kredit");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Batch Desc");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$batch_name = $this->input->get('batch_name');
		$batch_number = $this->input->get('batch_number');

		$hasil = $this->payment_batch->get_download_journal_payment_batch($date_from,$date_to, $batch_name, $batch_number);

		$numrow    = 2;
		$number    = 1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['GL_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['JOURNAL_DESCRIPTION']);

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
		header('Content-Disposition: attachment; filename="Journal Payment Batch.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function load_data_journal_payment_batch_inquiry(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('batch_date_from') != "" && $this->input->post('batch_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('batch_date_from'));
			$exp_date_to   = explode("/", $this->input->post('batch_date_to'));

			$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$batch_name = $this->input->post('batch_name');
		$batch_number = $this->input->post('batch_number');

		// echo $batch_name; die();

		$get_all = $this->payment_batch->get_journal_payment_batch_inquiry_datatable($batch_date_from, $batch_date_to, $batch_name, $batch_number);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){
			
			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'accounting_date'        => date("d-m-Y",strtotime($value['GL_DATE'])),
					'currency'           	 => $value['CURRENCY'],
					'nature'         		 => $value['NATURE'],
					'account_description' 	 => $value['ACCOUNT_DESCRIPTION'],
					'debet'		 			 => $value['DEBET'],
					'kredit'       		     => $value['CREDIT'],
					'batch_name'             => $value['BATCH_NAME'],
					'batch_desc'   			 => $value['JOURNAL_DESCRIPTION']
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

	public function download_data_journal_payment_batch_inquiry()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Accounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Kredit");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Batch Desc");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$batch_name = $this->input->get('batch_name');
		$batch_number = $this->input->get('batch_number');

		$hasil = $this->payment_batch->get_download_journal_payment_batch_inquiry($date_from, $date_to, $batch_name, $batch_number);

		// echo "<pre>";
		// print_r($hasil->result_array() );die;

		$numrow    = 2;
		$number    = 1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['GL_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['JOURNAL_DESCRIPTION']);

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
		header('Content-Disposition: attachment; filename="Journal Payment Batch.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
// echo 'a';die;

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}



	function load_ddl_batch_name()
	{
		if($this->input->post('param_batch_date_from') != "" && $this->input->post('param_batch_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('param_batch_date_from'));
			$exp_date_to   = explode("/", $this->input->post('param_batch_date_to'));

			$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$hasil	= $this->payment_batch->get_master_batch_name($batch_date_from,$batch_date_to);
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Batch Name --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['BATCH_NAME']."' data-name='".$row['BATCH_NAME']."' >".$row['BATCH_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_batch_number()
	{
		if($this->input->post('param_batch_date_from') != "" && $this->input->post('param_batch_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('param_batch_date_from'));
			$exp_date_to   = explode("/", $this->input->post('param_batch_date_to'));

			$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$hasil	= $this->payment_batch->get_master_batch_number($batch_date_from,$batch_date_to);
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Batch Number --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['BATCH_NUMBER']."' data-name='".$row['BATCH_NUMBER']."' >".$row['BATCH_NUMBER']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_pj_number()
	{
		if($this->input->post('param_batch_date_from') != "" && $this->input->post('param_batch_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('param_batch_date_from'));
			$exp_date_to   = explode("/", $this->input->post('param_batch_date_to'));

			$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$hasil	= $this->payment_batch->get_master_pj_number($batch_date_from,$batch_date_to);
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Payment Journal Number --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['JURNAL_PAYMENT_NUMBER']."' data-name='".$row['JURNAL_PAYMENT_NUMBER']."' >".$row['JURNAL_PAYMENT_NUMBER']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function save_payment_batch()
	{
		$data_line = $this->input->post('data_line');
		$batch_name = $this->input->post('batch_name');
		$batch_number = $this->input->post('batch_number');
		$journal_payment_number = $this->input->post('journal_payment_number');
		$jurnal_description = $this->input->post('jurnal_description');
		$vendor_name = $this->input->post('vendor_name');
		$bank_name = $this->input->post('bank_name');

		$exp_date_from 	 = explode("/", $this->input->post('invoice_date_from'));
		$exp_date_to   	 = explode("/", $this->input->post('invoice_date_to'));
		$exp_batch_date  = explode("/", $this->input->post('batch_date'));

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		$batch_date   		   = $exp_batch_date[2]."-".$exp_batch_date[1]."-".$exp_batch_date[0];

		// echo json_encode($_POST);
		// die;

		$this->form_validation->set_rules('batch_name', 'Batch Name', 'trim|required');
		$this->form_validation->set_rules('batch_number', 'Batch Number', 'trim|required');
		$this->form_validation->set_rules('journal_payment_number', 'Journal Payment Number', 'trim|required');
		$this->form_validation->set_rules('vendor_name', 'Vendor Name', 'trim|required');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('jurnal_description', 'Journal Description', 'trim|required');

		if ($this->form_validation->run() == TRUE) 
		{

			foreach ($data_line as $key => $value) {

				$data[] = array(
					"NO_JOURNAL" 				=> $value['no_journal'],
					"BANK_CHARGES" 				=> $value['bank_charge']
				);

			}

			$update   	= $this->crud->update_batch_data("GL_HEADERS", $data, "NO_JOURNAL");
			if($update !== -1)
			{

				$callprocedure =  $this->payment_batch->call_procedure($batch_name,$batch_number,$journal_payment_number,$batch_date,$vendor_name,$bank_name,$invoice_date_from,$invoice_date_to, $jurnal_description);


				if($callprocedure)
				{
					$calljournalpayment =  $this->payment_batch->call_journal_payment();

					if($calljournalpayment)
					{
						echo '1';
					}
					else
					{
						echo '0';
					}
				}
				else
				{
					echo '0';
				}
				
			} else {			
				echo '0';
			}

		}
		else
		{
			echo validation_errors();
		}
		
	}


}



/* End of file Paymentbatch.php */

/* Location: ./application/controllers/Paymentbatch.php */