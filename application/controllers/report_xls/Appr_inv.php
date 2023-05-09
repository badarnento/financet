<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appr_inv extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Appr_mdl', 'appr');
	}

	public function index()
	{
		
	}

	public function show_appr_tracker(){

		    $data['title']          = "Approval Invoice Tracker";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/appr_inv_tracker";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Approval Invoice Tracker", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_appr_inv_tracker(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$date_from   		= date_db($this->input->post('date_from'));
		$date_to     		= date_db($this->input->post('date_to'));
		
		$get_all = $this->appr->get_appr($date_from, $date_to);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				if($value['APPROVED_INVOICE_DATE'] == NULL || $value['APPROVED_INVOICE_DATE'] == '0000-00-00 00:00:00'){
					$approved_invoice_date = "";
				}else{
					$approved_invoice_date = dateFormat($value['APPROVED_INVOICE_DATE'], 5, false);
				}

				if($value['VALIDATE_DATE_TAX'] == NULL || $value['VALIDATE_DATE_TAX'] == '0000-00-00 00:00:00'){
					$validate_date_tax = "";
				}else{
					$validate_date_tax = dateFormat($value['VALIDATE_DATE_TAX'], 5, false);
				}

				if($value['VERIFICATED_DATE'] == NULL || $value['VERIFICATED_DATE'] == '0000-00-00 00:00:00'){
					$verificated_date = "";
				}else{
					$verificated_date = dateFormat($value['VERIFICATED_DATE'], 5, false);
				}

				if($value['APPROVED_DATE'] == NULL || $value['APPROVED_DATE'] == '0000-00-00 00:00:00'){
					$approved_date = "";
				}else{
					$approved_date = dateFormat($value['APPROVED_DATE'], 5, false);
				}

				if($value['APPROVED_HOU_DATE'] == NULL || $value['APPROVED_HOU_DATE'] == '0000-00-00 00:00:00'){
					$approved_hou_date = "";
				}else{
					$approved_hou_date = dateFormat($value['APPROVED_HOU_DATE'], 5, false);
				}

				$validate_status      = "";
				$verificated_status   = "";
				$approval_lead_status = "";
				$approval_hou_status  = "";

				if($value['APPROVED_INVOICE'] == "Approved"){
					$validate_status = ($value['VALIDATED'] == "Y") ? "Validated" : "Not yet validated";
				}

				if($value['APPROVED_INVOICE'] == "Approved" && $value['VALIDATED'] == "Y"){
					$verificated_status = ($value['VERIFICATED'] == "Y") ? "Verified" : "Not yet verified";
				}

				if($value['APPROVED_INVOICE'] == "Approved" && $value['VALIDATED'] == "Y" && $value['VERIFICATED'] == "Y"){
					if($value['APPROVED'] == "Y"){
						$approval_lead_status = "Approved";
					}
					elseif($value['APPROVED'] == "N"){
						$approval_lead_status = "Rejected";
					}else{
						$approval_lead_status = "Not yet approved";
					}
				}

				if($value['APPROVED_INVOICE'] == "Approved" && $value['VALIDATED'] == "Y" && $value['VERIFICATED'] == "Y" && $value['APPROVED'] == "Y"){
					if($value['APPROVED_HOU'] == "Y"){
						$approval_hou_status = "Approved";
					}
					elseif($value['APPROVED_HOU'] == "N"){
						$approval_hou_status = "Rejected";
					}else{
						$approval_hou_status = "Not yet approved";
					}
				}

				$row[] = array(
					'no'                    => $number,
					'receive_date'          => ($value['RECEIVE_DATE']) ? dateFormat($value['RECEIVE_DATE'], 5, false) : "",
					'tanggal_invoice'       => ($value['TANGGAL_INVOICE']) ? dateFormat($value['TANGGAL_INVOICE'], 5, false) : "",
					'due_date'              => ($value['DUE_DATE']) ? dateFormat($value['DUE_DATE'], 5, false) : "",
					'no_journal'            => $value['NO_JOURNAL'],
					'no_invoice'            => $value['NO_INVOICE'],
					'no_kontrak'            => $value['NO_KONTRAK'],
					'no_fpjp'               => $value['NO_FPJP'],
					'nama_vendor'           => $value['NAMA_VENDOR'],
					'approved_invoice'      => $value['APPROVED_INVOICE'],
					'approved_invoice_date' => $approved_invoice_date,
					'validated'             => $validate_status,
					'validate_date_tax'     => $validate_date_tax,
					'verificated'           => $verificated_status,
					'verificated_date'      => $verificated_date,
					'approved'              => $approval_lead_status,
					'approved_date'         => $approved_date,
					'approved_hou'          => $approval_hou_status,
					'approved_hou_date'     => $approved_hou_date
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

	public function cetak_report(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('Approval Invoice Tracker')
		->setLastModifiedBy('')
		->setTitle("Approval Invoice Tracker")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$date_from 		= date_db($_REQUEST['date_from']);
		$date_to 		= date_db($_REQUEST['date_to']);

		$style_header = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			   'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_bold = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
		  )
		);

		$style_center = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			   'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Receive Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Tanggal Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Due Date");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "No Journal");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "No Kontrak");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "NO FPJP");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Approval Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Approval Invoice Date");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Validated Status");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Validated Date Tax");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Verification Status");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Verification Date");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Approval Lead");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Approval Lead Date");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Approval HoU");
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "Approval HoU Date");

		$excel->getActiveSheet()->getStyle('A1:T1')->applyFromArray($style_bold);

		$hasil = $this->appr->get_cetak($date_from, $date_to);

		$numrow    = 2;
		$no=1;

		foreach($hasil->result_array() as $row)	{

			if($row['APPROVED_INVOICE_DATE'] == NULL || $row['APPROVED_INVOICE_DATE'] == '0000-00-00 00:00:00'){
				$approved_invoice_date = "";
			}else{
				$approved_invoice_date = dateFormat($row['APPROVED_INVOICE_DATE'], 5, false);
			}

			if($row['VALIDATE_DATE_TAX'] == NULL || $row['VALIDATE_DATE_TAX'] == '0000-00-00 00:00:00'){
				$validate_date_tax = "";
			}else{
				$validate_date_tax = dateFormat($row['VALIDATE_DATE_TAX'], 5, false);
			}

			if($row['VERIFICATED_DATE'] == NULL || $row['VERIFICATED_DATE'] == '0000-00-00 00:00:00'){
				$verificated_date = "";
			}else{
				$verificated_date = dateFormat($row['VERIFICATED_DATE'], 5, false);
			}

			if($row['APPROVED_DATE'] == NULL || $row['APPROVED_DATE'] == '0000-00-00 00:00:00'){
				$approved_date = "";
			}else{
				$approved_date = dateFormat($row['APPROVED_DATE'], 5, false);
			}

			if($row['APPROVED_HOU_DATE'] == NULL || $row['APPROVED_HOU_DATE'] == '0000-00-00 00:00:00'){
				$approved_hou_date = "";
			}else{
				$approved_hou_date = dateFormat($row['APPROVED_HOU_DATE'], 5, false);
			}

			$validate_status      = "";
			$verificated_status   = "";
			$approval_lead_status = "";
			$approval_hou_status  = "";

			if($row['APPROVED_INVOICE'] == "Approved"){
				$validate_status = ($row['VALIDATED'] == "Y") ? "Validated" : "Not yet validated";
			}

			if($row['APPROVED_INVOICE'] == "Approved" && $row['VALIDATED'] == "Y"){
				$verificated_status = ($row['VERIFICATED'] == "Y") ? "Verified" : "Not yet verified";
			}

			if($row['APPROVED_INVOICE'] == "Approved" && $row['VALIDATED'] == "Y" && $row['VERIFICATED'] == "Y"){
				if($row['APPROVED'] == "Y"){
					$approval_lead_status = "Approved";
				}
				elseif($row['APPROVED'] == "N"){
					$approval_lead_status = "Rejected";
				}else{
					$approval_lead_status = "Not yet approved";
				}
			}

			if($row['APPROVED_INVOICE'] == "Approved" && $row['VALIDATED'] == "Y" && $row['VERIFICATED'] == "Y" && $row['APPROVED'] == "Y"){
				if($row['APPROVED_HOU'] == "Y"){
					$approval_hou_status = "Approved";
				}
				elseif($row['APPROVED_HOU'] == "N"){
					$approval_hou_status = "Rejected";
				}else{
					$approval_hou_status = "Not yet approved";
				}
			}

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ($row['RECEIVE_DATE']) ? dateFormat($row['RECEIVE_DATE'], 5, false) : "");
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, dateFormat($row['TANGGAL_INVOICE'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, ($row['DUE_DATE']) ? dateFormat($row['DUE_DATE'], 5, false) : "");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NO_FPJP']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['APPROVED_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $approved_invoice_date);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $validate_status);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $validate_date_tax);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $verificated_status);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $verificated_date);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $approval_lead_status);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $approved_date);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $approval_hou_status);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $approved_hou_date);

			$excel->getActiveSheet()->getStyle('A'.$numrow.':D'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('K'.$numrow.':T'.$numrow)->applyFromArray($style_center);

			$numrow++;
			$no++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 20);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Approval Invoice Tracker");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Approval Invoice Tracker.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}