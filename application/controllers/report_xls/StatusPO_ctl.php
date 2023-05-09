<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StatusPO_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('StatusPO_mdl', 'statusPO');
	}

	public function index()
	{
		
	}

	public function show_status_po(){

		    $data['title']          = "Report Status PO";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/status_po";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Report Status", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_data(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;
		
		$get_all = $this->statusPO->get_status_po();
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'           		=> $number,
					'vendor_name'  		=> $value['VENDOR_NAME'],
					'po_number'  		=> $value['PO_NUMBER'],
					'period_from' 		=> $value['PERIOD_FROM'],
					'period_to' 		=> $value['PERIOD_TO'],
					'amount_po'   		=> number_format($value['AMOUNT_PO'],0,'.',','),
					'no_invoice' 		=> $value['NO_INVOICE'],
					'amount_paid'  		=> number_format($value['AMOUNT_PAID'],0,'.',','),
					'amount_remaining'	=> number_format($value['AMOUNT_REMAINING'],0,'.',','),
					'status' 			=> $value['STATUS']
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

		$excel->getProperties()	->setCreator('Report Status PO')
		->setLastModifiedBy('')
		->setTitle("Report_statusPO")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

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

		$style_row = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			   'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Vendor Name");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "PO Number");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Period From");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Period To");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "PO Amount");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Invoice Number");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Paid Amount");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Remaining Amount");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Status");
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('J1')->applyFromArray($style_header);

		$hasil = $this->statusPO->get_report();

		$numrow    = 2;
		$no=1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['VENDOR_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['PO_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['PERIOD_FROM']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['PERIOD_TO']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, number_format($row['AMOUNT_PO'],0,',','.'));
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($row['AMOUNT_PAID'],0,',','.'));
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($row['AMOUNT_REMAINING'],0,',','.'));
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['STATUS']);

			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

			$numrow++;
			$no++;

		}

		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$loop_column = horizontal_loop_excel("E", 10);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(20);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Report Status PO");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Report Status PO.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}