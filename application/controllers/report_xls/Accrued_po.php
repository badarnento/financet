<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accrued_po extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Accrued_mdl', 'accrued');
	}

	public function index()
	{
		
	}

	public function show_accrued_po(){

		    $data['title']          = "Accrued PO";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/accrued_po";
			$data['get_accr']  		= $this->accrued->get_accr_type();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Accrued PO", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_data(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		// $date_from   		= date_db($this->input->post('date_from'));
		// $date_to     		= date_db($this->input->post('date_to'));
		$type     			= $this->input->post('type');
		
		$get_all = $this->accrued->get_accrued_po($type);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'           			=> $number,
					'accounting_date'  		=> $value['ACCOUNTING_DATE'],
					'batch_name'  			=> $value['BATCH_NAME'],
					'journal_name' 			=> $value['JOURNAL_NAME'],
					'journal_description' 	=> $value['JOURNAL_DESCRIPTION'],
					'account_description' 	=> $value['ACCOUNT_DESCRIPTION'],
					'nature' 				=> $value['NATURE'],
					'debit' 				=> number_format($value['DEBIT'],0,',','.'),
					'credit' 				=> number_format($value['CREDIT'],0,',','.')
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

		$excel->getProperties()	->setCreator('Accrued PO')
		->setLastModifiedBy('')
		->setTitle("Accrued PO")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		// $date_from 		= date_db($_REQUEST['date_from']);
		// $date_to 		= date_db($_REQUEST['date_to']);
		$type 			= $_REQUEST['type'];

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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Accounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Debit");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Credit");

		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_header);

		$hasil = $this->accrued->get_accrued_po_cetak($type);

		$numrow    = 2;
		$no=1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['ACCOUNTING_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($row['DEBIT'],0,',','.'));
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($row['CREDIT'],0,',','.'));

			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_amount);

			$numrow++;
			$no++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 9);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Accrued PO");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Accrued PO.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}