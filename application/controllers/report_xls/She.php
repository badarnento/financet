<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class She extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Report_xls_mdl', 'report_xls');
		$this->load->model('Tb_mdl', 'tb');
	}

	public function show_she(){
		$data['title']          = "SHE";
		$data['module']         = "datatable";
		$data['template_page']  = "report_xls/she";
		$data['get_exist_year'] = $this->tb->get_exist_year();
		
		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_she(){
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(1200);
		}

		ini_set('memory_limit', '-1');

		$year 		= $_REQUEST['year'];
		$month		= $_REQUEST['month'];
		$bulan		= $_REQUEST['bulan'];
		$date	    = date("Y-m-d H:i:s");
		
		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('SHE')
								->setLastModifiedBy('SHE')
								->setTitle("SHE")
								->setSubject("SHE")
								->setDescription("SHE")
								->setKeywords("SHE");

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

		$style_sub = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		  )
		);

		$style_nature = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  )
		);

		$style_total = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);
		
		$style_row = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  )
		);

		$style_double = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_DOUBLE)
		  )
		);

		$style_line = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_line_total = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$fintek = 'assets/img/fintek.jpg';
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$logo = $fintek;
		if(file_exists($logo)){
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('A1');
			$objDrawing->setHeight(120);
			$objDrawing->setWorksheet($excel->getActiveSheet());
		}
		
		$excel->setActiveSheetIndex(0)->setCellValue('A2', "PT FINTEK KARYA NUSANTARA");
		$excel->getActiveSheet()->mergeCells('A2:L2');
		$excel->getActiveSheet()->getStyle('A2:L2')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A3', "STATEMENTS OF CHANGES IN EQUITY");
		$excel->getActiveSheet()->mergeCells('A3:L3');
		$excel->getActiveSheet()->getStyle('A3:L3')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A4', "FOR MONTHS PERIOD ENDED ".strtoupper($bulan).", ".$year." (UNAUDITED)");
		$excel->getActiveSheet()->mergeCells('A4:L4');
		$excel->getActiveSheet()->getStyle('A4:L4')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A5', "( In Indonesian Rupiah )");
		$excel->getActiveSheet()->mergeCells('A5:L5');
		$excel->getActiveSheet()->getStyle('A5:L5')->applyFromArray($style_nature);

		$excel->getActiveSheet()->getStyle('A7:L7')->applyFromArray($style_double);

		$excel->setActiveSheetIndex(0)->setCellValue('C10', "Capital Stock");
		$excel->getActiveSheet()->getStyle('C10')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('E9', "Additional");
		$excel->setActiveSheetIndex(0)->setCellValue('E10', "Paid-in Capital");
		$excel->setActiveSheetIndex(0)->setCellValue('E11', "Under Common Control");
		$excel->getActiveSheet()->getStyle('E9:E11')->applyFromArray($style_nature);
		/*$excel->setActiveSheetIndex(0)->setCellValue('G9', "Retained Earnings");
		$excel->getActiveSheet()->mergeCells('G9:H9');
		$excel->getActiveSheet()->getStyle('G9:H9')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('G10', "Appropriated");
		$excel->getActiveSheet()->getStyle('G10')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('H10', "Unappropriated");
		$excel->getActiveSheet()->getStyle('H10')->applyFromArray($style_nature);*/
		$excel->setActiveSheetIndex(0)->setCellValue('J10', "Accumulated");
		$excel->setActiveSheetIndex(0)->setCellValue('J11', "Deficit");
		$excel->getActiveSheet()->getStyle('J10:J11')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('L10', "Net Equity");
		$excel->getActiveSheet()->getStyle('L10')->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('A11:L11')->applyFromArray($style_line);

		$excel->setActiveSheetIndex(0)->setCellValue('A14', "Balance as of January, " .$year);
		$excel->setActiveSheetIndex(0)->setCellValue('A15', "Issuance of share capital");
		$excel->setActiveSheetIndex(0)->setCellValue('A16', "Additional paid in capital");
		$excel->setActiveSheetIndex(0)->setCellValue('A17', "Loss for the period");
		$excel->setActiveSheetIndex(0)->setCellValue('A18', "Balance as of ".$bulan.", ".$year);
		$excel->getActiveSheet()->getStyle('A18')->applyFromArray($style_sub);

		$hasil = $this->report_xls->get_she($year, $month);

		$data = array();

		$category_arr = array(
						"cs"      => array("31100001"),
						"ap"      => array("31100003"),
						"ad"      => array("31400001"),
						"apc"     => array("31100002")
					);

		foreach($hasil->result_array() as $row)	{

			$nature = $row['NATURE'];

			if(in_array($nature, $category_arr['cs'])){
				$data['cs'][] = $row;
			}elseif(in_array($nature, $category_arr['ap'])){
				$data['ap'][] = $row;
			}elseif(in_array($nature, $category_arr['ad'])){
				$data['ad'][] = $row;
			}elseif(in_array($nature, $category_arr['apc'])){
				$data['apc'][] = $row;
			}
		}

		$hasil1 = $this->report_xls->get_pl($year, $month);

		$category_arr1 = array(
						"revenues"      => array("Revenues"),
						"oi"      		=> array("Marketing expenses","Personnel expenses","Operations and maintenance expenses","General and administrative expenses", "Depreciation and amortization expenses","Cost of services", "Bank virtual account expenses", "Foreign exchange loss (net)", "Other operating income (net)"),
						"oi1"      		=> array("Finance income (net of final tax)", "Finance charges"),
						"citben"      	=> array("Current Income Tax Expenses", "Deferred"),
						"rdb"      		=> array("Remeasurement of defined benefit pension plans")
					);

		foreach($hasil1->result_array() as $row)	{

			$group = $row['GROUP_REPORT'];

			if(in_array($group, $category_arr1['revenues'])){
				$dt['revenues'][] = $row;
			}elseif(in_array($group, $category_arr1['oi'])){
				$dt['oi'][] = $row;
			}elseif(in_array($group, $category_arr1['oi1'])){
				$dt['oi1'][] = $row;
			}elseif(in_array($group, $category_arr1['citben'])){
				$dt['citben'][] = $row;
			}elseif(in_array($group, $category_arr1['rdb'])){
				$dt['rdb'][] = $row;
			}
		}

		$ctgr = "revenues";
		if(!isset($dt[$ctgr])){
			$dt[$ctgr] = array();
		}

		$revTotal = 0;

		foreach ($dt[$ctgr] as $key => $value) {
			$revTotal       += $value['TOTAL_AMOUNT'];
		}

		$ctgr = "oi";
		if(!isset($dt[$ctgr])){
			$dt[$ctgr] = array();
		}

		$oiTotal = 0;

		foreach ($dt[$ctgr] as $key => $value) {
			$oiTotal       += $value['TOTAL_AMOUNT'];
		}

		$oprloss = $revTotal + $oiTotal;

		$ctgr = "oi1";
		if(!isset($dt[$ctgr])){
			$dt[$ctgr] = array();
		}

		$oi1Total = 0;

		foreach ($dt[$ctgr] as $key => $value) {
			$oi1Total       += $value['TOTAL_AMOUNT'];
		}

		$lossbci = $oprloss + $oi1Total;

		$ctgr = "citben";
		if(!isset($dt[$ctgr])){
			$dt[$ctgr] = array();
		}

		$citbenTotal = 0;

		foreach ($dt[$ctgr] as $key => $value) {
			$citbenTotal       += $value['TOTAL_AMOUNT'];
		}

		$profitloss = $lossbci + $citbenTotal;

		$ctgr = "rdb";
		if(!isset($dt[$ctgr])){
			$dt[$ctgr] = array();
		}

		$rdbTotal = 0;

		foreach ($dt[$ctgr] as $key => $value) {
			$rdbTotal       += $value['TOTAL_AMOUNT'];
		}

		$fortp = $profitloss + $rdbTotal;

		/*$category = "cs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('C14', $row['AMOUNT_JANUARY']);
		}*/

		$category = "cs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('C14', $row['AMOUNT']);
		}
		$excel->getActiveSheet()->getStyle('C14:C17')->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

		$category = "ap";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('E14', $row['AMOUNT']);
		}

		$category = "apc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('E16', $row['AMOUNT']);
		}
		$excel->getActiveSheet()->getStyle('E14:E17')->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

		$excel->setActiveSheetIndex(0)->setCellValue('L14', '=SUM(C14:J14)');
		$excel->setActiveSheetIndex(0)->setCellValue('L16', '=SUM(C16:J16)');
		$excel->setActiveSheetIndex(0)->setCellValue('L17', $fortp);
		$excel->getActiveSheet()->getStyle('L14:L17')->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

		$category = "ad";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('J14', $row['AMOUNT']);
		}

		$excel->setActiveSheetIndex(0)->setCellValue('J17', $fortp);
		$excel->getActiveSheet()->getStyle('J14:J17')->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

		$excel->getActiveSheet()->getStyle('C17')->applyFromArray($style_line_total);
		$excel->getActiveSheet()->getStyle('E17')->applyFromArray($style_line_total);
		// $excel->getActiveSheet()->getStyle('G17')->applyFromArray($style_line_total);
		// $excel->getActiveSheet()->getStyle('H17')->applyFromArray($style_line_total);
		$excel->getActiveSheet()->getStyle('J17')->applyFromArray($style_line_total);
		$excel->getActiveSheet()->getStyle('L17')->applyFromArray($style_line_total);

		$excel->getActiveSheet()->getStyle('C18')->applyFromArray($style_double);
		$excel->getActiveSheet()->getStyle('E18')->applyFromArray($style_double);
		// $excel->getActiveSheet()->getStyle('G18')->applyFromArray($style_double);
		// $excel->getActiveSheet()->getStyle('H18')->applyFromArray($style_double);
		$excel->getActiveSheet()->getStyle('J18')->applyFromArray($style_double);
		$excel->getActiveSheet()->getStyle('L18')->applyFromArray($style_double);

		$excel->setActiveSheetIndex(0)->setCellValue('C18', '=SUM(C14:C17)');
		$excel->setActiveSheetIndex(0)->setCellValue('E18', '=SUM(E14:E17)');
		// $excel->setActiveSheetIndex(0)->setCellValue('G18', '=SUM(G14:G17)');
		// $excel->setActiveSheetIndex(0)->setCellValue('H18', '=SUM(H14:H17)');
		$excel->setActiveSheetIndex(0)->setCellValue('J18', '=SUM(J14:J17)');
		$excel->setActiveSheetIndex(0)->setCellValue('L18', '=SUM(L14:L17)');
		$excel->getActiveSheet()->getStyle('C18:L18')->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

		$tgl = date("F d, yy",time());
		$excel->setActiveSheetIndex(0)->setCellValue('J22', "Jakarta, ".$tgl);
		$excel->getActiveSheet()->getStyle('J22')->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('J28', "");
		$excel->getActiveSheet()->getStyle('J28')->applyFromArray($style_line);

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(0);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(0);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(0);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		
		
		
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		
		$excel->getActiveSheet(0)->setTitle("SHE");
		$excel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="SHE.xls"');
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}

/* End of file Bswp.php */
/* Location: ./application/controllers/report_xls/Bswp.php */