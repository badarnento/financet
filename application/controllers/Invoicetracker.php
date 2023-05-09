<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoicetracker extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Invoicetracker_mdl', 'Invoicetracker');

	}

	public function invoice_tracker()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("invoicetracker/invoice_tracker", $this->session->userdata['menu_url']) ){
			
			$data['title']          = "Invoice Tracker";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/invoice_tracker";

			$group = $this->session->userdata('group_id');

			foreach ($group as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName['NAME'];
			}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Invoice Tracker", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_invoice_tracker_inquiry(){

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

		$filterdateby = $this->input->post('filterdateby');
		$vendor_name = $this->input->post('vendor_name');

		$get_all = $this->Invoicetracker->get_invoice_tracker_inquiry_datatable($invoice_date_from, $invoice_date_to,$filterdateby);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nomor   = 1;
		$i=1;

		if($total > 0){
			
			foreach($data as $value) {

				$row[] = array(
					'no'             	=> $number,
					'TANGGAL'			=> $value['TANGGAL'],
					'NO_JOURNAL'       	=> $value['NO_JOURNAL'],
					'NAMA_VENDOR'   	=> $value['NAMA_VENDOR'],
					'NO_INVOICE' 	    => $value['NO_INVOICE'],
					'CURRENCY'			=> $value['CURRENCY'],
					'TOTAL_AMOUNT'		=> $value['TOTAL_AMOUNT'],
					'DESCRIPTION' 		=> $value['DESCRIPTION'],
					'NO_KONTRAK'        => $value['NO_KONTRAK'],
					'NO_FPJP'       	=> $value['NO_FPJP'],
					'DPP'         	    => number_format($value['DPP'],0,'.',','),
					'PPN'       		=> number_format($value['PPN'],0,'.',','),
					'SUB_TOTAL'			=> number_format($value['SUB_TOTAL'],0,'.',','),
					'PPH' 				=> number_format($value['PPH'],0,'.',','),
					'TOTAL'			  	=> number_format($value['TOTAL'],0,'.',','),
					'NAMA_REKENING'	    => $value['NAMA_REKENING'],
					'NAMA_BANK'			=> $value['NAMA_BANK'],
					'ACCT_NUMBER'		=> $value['ACCT_NUMBER'],
					'RKAP_NAME'			=> $value['RKAP_NAME'],
					'TOP'			  			=> $value['TOP'],
					'TAX_VERIFICATION_DATE'		=> $value['TAX_VERIFICATION_DATE'],
					'NO_SERI_FAKTUR_PAJAK'		=> $value['NO_SERI_FAKTUR_PAJAK'],
					'NOMOR_NPWP'			  	=> $value['NOMOR_NPWP'],
					'JOURNAL_AP_BY'			  	=> $value['JOURNAL_AP_BY'],
					'NATURE'			 		=> $value['NATURE'],
					'COA_PARENT'			  	=> $value['COA_PARENT'],
					'DUE_DATE'			  		=> $value['DUE_DATE'],
					'HAND_OVER_TO_TREASURY_BY'	=> $value['HAND_OVER_TO_TREASURY_BY'],
					'PAYMENT_CREATE'			=> $value['PAYMENT_CREATE'],
					'TRANSFER_AMOUNT'			=> number_format($value['TRANSFER_AMOUNT'],0,'.',','),
					'PAYMENT_DATE'			  	=> $value['PAYMENT_DATE'],
					'DIFFERENCE'			  	=> number_format($value['DIFFERENCE'],0,'.',','),
					'STATUS'			  		=> $value['STATUS'],
					'AR_NETTING'			  	=> $value['AR_NETTING'],
					'AR_AMOUNT'			  		=> $value['AR_AMOUNT'],
					'AR_INVOICE_DESCRIPTION'	=> $value['AR_INVOICE_DESCRIPTION']


				);

				$i++;
				$number++;
			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);

	}

	public function load_ddl_filter_date_by()
	{		
		$result  = "";
		$result .= "<option value='' data-name='' > -- Please Choose -- </option>";
		$result .= "<option value='1' data-name='TANGGAL_FILTER' > Invoice Date </option>";
		$result .= "<option value='2' data-name='APPROVED_DATE' > Approved Date </option>";

		echo $result;
	}

	function download_data_invoice_tracker_inquiry()
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "TANGGAL");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "NO_JOURNAL");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "NAMA_VENDOR");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "NO_INVOICE");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "CURRENCY");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "TOTAL_AMOUNT");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "DESCRIPTION");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "NO_KONTRAK");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "NO_FPJP");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "PPN");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "SUB_TOTAL");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "PPH");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "TOTAL");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "NAMA_REKENING");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "NAMA_BANK");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "ACCT_NUMBER");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "RKAP_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "TOP");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "TAX_VERIFICATION_DATE");
		$excel->setActiveSheetIndex(0)->setCellValue('V1', "NO_SERI_FAKTUR_PAJAK");
		$excel->setActiveSheetIndex(0)->setCellValue('W1', "NOMOR_NPWP");
		$excel->setActiveSheetIndex(0)->setCellValue('X1', "TGL_FAKTUR_PAJAK");
		$excel->setActiveSheetIndex(0)->setCellValue('Y1', "AMOUNT_BASE_FEE");
		$excel->setActiveSheetIndex(0)->setCellValue('Z1', "NOTES");
		$excel->setActiveSheetIndex(0)->setCellValue('AA1', "JOURNAL_AP_BY");
		$excel->setActiveSheetIndex(0)->setCellValue('AB1', "NATURE");
		$excel->setActiveSheetIndex(0)->setCellValue('AC1', "COA_PARENT");
		$excel->setActiveSheetIndex(0)->setCellValue('AD1', "DUE_DATE");
		$excel->setActiveSheetIndex(0)->setCellValue('AE1', "HAND_OVER_TO_TREASURY_BY");
		$excel->setActiveSheetIndex(0)->setCellValue('AF1', "PAYMENT_CREATE");
		$excel->setActiveSheetIndex(0)->setCellValue('AG1', "TRANSFER_AMOUNT");
		$excel->setActiveSheetIndex(0)->setCellValue('AH1', "PAYMENT_DATE");
		$excel->setActiveSheetIndex(0)->setCellValue('AI1', "DIFFERENCE");
		$excel->setActiveSheetIndex(0)->setCellValue('AJ1', "STATUS");
		$excel->setActiveSheetIndex(0)->setCellValue('AK1', "AR_NETTING");
		$excel->setActiveSheetIndex(0)->setCellValue('AL1', "AR_AMOUNT");
		$excel->setActiveSheetIndex(0)->setCellValue('AM1', "AR_INVOICE_DESCRIPTION");

		$date_from 		= $this->input->get('date_from');
		$date_to 		= $this->input->get('date_to');
		$filterdateby 	= $this->input->get('filterdateby');

		$hasil = $this->Invoicetracker->get_download_invoice_tracker_inquiry($date_from,$date_to,$filterdateby);

		$numrow  = 2;
		$number = 1;
		$nmjurnal = '';

		$textFormat='0';

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['TANGGAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['TOTAL_AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NO_FPJP']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['PPN']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['SUB_TOTAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['PPH']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['TOTAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['NAMA_BANK']);
		    $excel->getActiveSheet(0)->setCellValueExplicit('R'.(string)$numrow, $row['ACCT_NUMBER'], PHPExcel_Cell_DataType::TYPE_STRING);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['RKAP_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $row['TOP']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $row['TAX_VERIFICATION_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $row['NO_SERI_FAKTUR_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $row['NOMOR_NPWP']);
			$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $row['TGL_FAKTUR_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $row['AMOUNT_BASE_FEE']);
			$excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $row['NOTES']);
			$excel->setActiveSheetIndex(0)->setCellValue('AA'.$numrow, $row['JOURNAL_AP_BY']);
			$excel->setActiveSheetIndex(0)->setCellValue('AB'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('AC'.$numrow, $row['COA_PARENT']);
			$excel->setActiveSheetIndex(0)->setCellValue('AD'.$numrow, $row['DUE_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('AE'.$numrow, $row['HAND_OVER_TO_TREASURY_BY']);
			$excel->setActiveSheetIndex(0)->setCellValue('AF'.$numrow, $row['PAYMENT_CREATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('AG'.$numrow, $row['TRANSFER_AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('AH'.$numrow, $row['PAYMENT_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('AI'.$numrow, $row['DIFFERENCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('AJ'.$numrow, $row['STATUS']);
			$excel->setActiveSheetIndex(0)->setCellValue('AK'.$numrow, $row['AR_NETTING']);
			$excel->setActiveSheetIndex(0)->setCellValue('AL'.$numrow, $row['AR_AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('AM'.$numrow, $row['AR_INVOICE_DESCRIPTION']);

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
		header('Content-Disposition: attachment; filename="Invoice Tracker.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


}



/* End of file Invoicetracker.php */

/* Location: ./application/controllers/Invoicetracker.php */