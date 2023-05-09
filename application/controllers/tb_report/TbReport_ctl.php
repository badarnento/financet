<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TbReport_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Tb_mdl', 'tb');
	}

	public function index()
	{
		
	}

	public function show_tb(){
				
		if($this->ion_auth->is_admin() == true || in_array("tbreport/report", $this->session->userdata['menu_url']) ){

		    $data['title']          = "TRIAL BALANCE YTD";
			$data['module']         = "datatable";
			$data['template_page']  = "tb_report/tb_report";
			$data['get_exist_year'] = $this->tb->get_exist_year();

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
}

	public function load_tb_ytd(){

		set_time_limit(0);

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('TB')
		->setLastModifiedBy('TB')
		->setTitle("Cetak TB")
		->setSubject("Cetakan")
		->setDescription("Cetak TB")
		->setKeywords("TB");

		$year     = $this->input->get('year');
		$category = $this->input->get('category');

		$tahun = substr($year,2,2);

		$style_header = array(
			'font' 		 => array('bold' => true),
		   	 'alignment' => array(
		   	'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
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
		   	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		  ),
		  'borders' => array(
			  'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		   'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_center = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  ),
		  'borders' => array(
			  'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		   'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_right = array(
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

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PT FINTEK KARYA NUSANTARA");
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "TRIAL BALANCE");
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "TAHUN ".$year);

		$excel->setActiveSheetIndex(0)->setCellValue('B5', "NATURE");
		$excel->getActiveSheet()->getStyle('B5:B6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('B5:B6');
		$excel->setActiveSheetIndex(0)->setCellValue('C5', "DESCRIPTION");
		$excel->getActiveSheet()->getStyle('C5:C6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('C5:C6');
		$excel->setActiveSheetIndex(0)->setCellValue('D5', "GROUP REPORT");
		$excel->getActiveSheet()->getStyle('D5:D6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('D5:D6');
		$excel->setActiveSheetIndex(0)->setCellValue('E5', "SALDO AWAL");
		$excel->getActiveSheet()->getStyle('E5:E6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('E5:E6');
		$excel->setActiveSheetIndex(0)->setCellValue('F5', "JAN-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('F6', "Consolidated");
		$excel->getActiveSheet()->getStyle('F5:F6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('G5', "FEB-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('G6', "Consolidated");
		$excel->getActiveSheet()->getStyle('G5:G6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('H5', "MAR-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('H6', "Consolidated");
		$excel->getActiveSheet()->getStyle('H5:H6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('I5', "APR-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('I6', "Consolidated");
		$excel->getActiveSheet()->getStyle('I5:I6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('J5', "MAY-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('J6', "Consolidated");
		$excel->getActiveSheet()->getStyle('J5:J6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('K5', "JUN-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('K6', "Consolidated");
		$excel->getActiveSheet()->getStyle('K5:K6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('L5', "JUL-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('L6', "Consolidated");
		$excel->getActiveSheet()->getStyle('L5:L6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('M5', "AUG-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('M6', "Consolidated");
		$excel->getActiveSheet()->getStyle('M5:M6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('N5', "SEP-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('N6', "Consolidated");
		$excel->getActiveSheet()->getStyle('N5:N6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('O5', "OCT-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('O6', "Consolidated");
		$excel->getActiveSheet()->getStyle('O5:O6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('P5', "NOV-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('P6', "Consolidated");
		$excel->getActiveSheet()->getStyle('P5:P6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('Q5', "DEC-".$tahun." PTD");
		$excel->setActiveSheetIndex(0)->setCellValue('Q6', "Consolidated");
		$excel->getActiveSheet()->getStyle('Q5:Q6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('R5', "YTD ".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('R6', "Consolidated");
		$excel->getActiveSheet()->getStyle('R5:R6')->applyFromArray($style_header);

		$hasil = $this->tb->get_tb_ytd($year, $category);

		$numrow    = 7;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['SALDO_AWAL']);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['JAN']);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['FEB']);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['MAR']);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['APR']);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['MAY']);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['JUN']);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['JUL']);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['AUG']);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['SEP']);
			$excel->getActiveSheet()->getStyle('N'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['OCT']);
			$excel->getActiveSheet()->getStyle('O'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NOV']);
			$excel->getActiveSheet()->getStyle('P'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['DES']);
			$excel->getActiveSheet()->getStyle('Q'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['YTD']);
			$excel->getActiveSheet()->getStyle('R'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_right);

			$numrow++;

		}

		$numrow +=1;
		$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, "Jakarta, ".date('F d Y'));

		$numrow +=5;
		$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, "_________________");

		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$loop_column = horizontal_loop_excel("E", 20);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(20);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("TB YTD FINARYA ".$tahun);
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="TB YTD FINARYA '.$year.'.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function load_download(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('TB')
		->setLastModifiedBy('TB')
		->setTitle("Cetak TB")
		->setSubject("Cetakan")
		->setDescription("Cetak TB")
		->setKeywords("TB");

		$category = $this->input->get('category');
		$year  	  = $this->input->get();

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "YEAR");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "NATURE");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DESCRIPTION");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "GROUP REPORT");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "JAN");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "FEB");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "MAR");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "APR");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "MAY");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "JUN");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "JUL");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "AUG");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "SEP");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "OCT");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "NOV");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "DES");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "YTD");

		$hasil = $this->tb->get_tb_ytd($year, $category);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['YEAR']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, number_format($row['JAN'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, number_format($row['FEB'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, number_format($row['MAR'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($row['APR'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($row['MAY'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, number_format($row['JUN'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, number_format($row['JUL'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, number_format($row['AUG'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, number_format($row['SEP'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, number_format($row['OCT'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, number_format($row['NOV'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, number_format($row['DES'],0,'.',','));
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, number_format($row['YTD'],0,'.',','));

			$numrow++;

		}

		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$loop_column = horizontal_loop_excel("E", 20);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(20);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("TRIAL BALANCE YTD");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="TRIAL BALANCE YTD.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function show_trial(){
				
		if($this->ion_auth->is_admin() == true || in_array("tbreport/trial_balance", $this->session->userdata['menu_url']) ){

		    $data['title']          = "TRIAL BALANCE";
			$data['module']         = "datatable";
			$data['template_page']  = "tb_report/trial_balance";
			$data['get_exist_year'] = $this->tb->get_exist_tahun();

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_tb(){

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('TB')
		->setLastModifiedBy('TB')
		->setTitle("Cetak TB")
		->setSubject("Cetakan")
		->setDescription("Cetak TB")
		->setKeywords("TB");

		$year     = $this->input->get('year');
		$month    = $this->input->get('month');
		$category = $this->input->get('category');

		$tahun = substr($year,2,2);

		$style_header = array(
			'font' 		 => array('bold' => true),
		   	 'alignment' => array(
		   	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
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
		   	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		  ),
		  'borders' => array(
			  'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		   'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_center = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  ),
		  'borders' => array(
			  'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		   'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_right = array(
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

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PT FINTEK KARYA NUSANTARA");
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "TRIAL BALANCE");
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "TAHUN ".$year);

		$excel->setActiveSheetIndex(0)->setCellValue('B5', "NATURE");
		$excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('C5', "ACCOUNT DESCRIPTION");
		$excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('D5', "SALDO AWAL");
		$excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('E5', "DEBIT");
		$excel->getActiveSheet()->getStyle('E5')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('F5', "CREDIT");
		$excel->getActiveSheet()->getStyle('F5')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('G5', "BALANCE");
		$excel->getActiveSheet()->getStyle('G5')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('H5', "SALDO AKHIR");
		$excel->getActiveSheet()->getStyle('H5')->applyFromArray($style_header);

		$hasil = $this->tb->get_tb($year, $month, $category);

		$numrow    = 6;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['SALDO_AWAL']);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DEBIT']);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['CREDIT']);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['BALANCE']);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['SALDO_AKHIR']);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_right);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_right);

			$numrow++;

		}

		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$loop_column = horizontal_loop_excel("E", 20);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(20);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("TRIAL BALANCE DETAIL ".$tahun);
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="TRIAL BALANCE DETAIL '.$year.'.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function load_data_tbytd(){

		// ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);


		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;
		
		$param   = $this->input->post('param');
		$year    = $this->input->post('tahun');
		$get_all = $this->tb->get_ytd($year);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0 && $param > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'           => $number,
					'nature'       => $value['NATURE'],
					'description'  => $value['DESCRIPTION'],
					'group_report' => $value['GROUP_REPORT'],
					'saldo_awal'   => number_format($value['SALDO_AWAL'],0,'.',','),
					'jan'          => number_format($value['JAN'],0,'.',','),
					'feb'          => number_format($value['FEB'],0,'.',','),
					'mar'          => number_format($value['MAR'],0,'.',','),
					'apr'          => number_format($value['APR'],0,'.',','),
					'may'          => number_format($value['MAY'],0,'.',','),
					'jun'          => number_format($value['JUN'],0,'.',','),
					'jul'          => number_format($value['JUL'],0,'.',','),
					'aug'          => number_format($value['AUG'],0,'.',','),
					'sep'          => number_format($value['SEP'],0,'.',','),
					'oct'          => number_format($value['OCT'],0,'.',','),
					'nov'          => number_format($value['NOV'],0,'.',','),
					'des'          => number_format($value['DES'],0,'.',','),
					'ytd'          => number_format($value['YTD'],0,'.',',')
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

	public function load_data_tbdtl(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year  = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$get_all = $this->tb->get_tbdtl($year, $bulan);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'                  => $number,
					'nature'              => $value['NATURE'],
					'account_description' => $value['DESCRIPTION'],
					'saldo_awal'          => number_format($value['SALDO_AWAL'],0,'.',','),
					'debit'               => number_format($value['DEBIT'],0,'.',','),
					'credit'              => number_format($value['CREDIT'],0,'.',','),
					'balance'             => number_format($value['BALANCE'],0,'.',','),
					'saldo_akhir'         => number_format($value['SALDO_AKHIR'],0,'.',',')
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

}

/* End of file TbReport_ctl.php */
/* Location: ./application/controllers/tb_report/TbReport_ctl.php */