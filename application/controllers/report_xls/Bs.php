<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bs extends CI_Controller {

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

	public function show_bs(){
		$data['title']          = "Balance Sheet";
		$data['module']         = "datatable";
		$data['template_page']  = "report_xls/bs";
		$data['get_exist_year'] = $this->tb->get_exist_year();
		
		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_bs(){
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

		$excel->getProperties()	->setCreator('BL')
								->setLastModifiedBy('BL')
								->setTitle("BL")
								->setSubject("BL")
								->setDescription("BL")
								->setKeywords("BL");

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

		$style_line = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_double = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_DOUBLE)
		  )
		);

		$style_total = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_end = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_DOUBLE)
		  )
		);

		$fintek = 'assets/img/fintek.jpg';
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$logo = $fintek;
		if(file_exists($logo)){
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('B1');
			$objDrawing->setHeight(80);
			$objDrawing->setWorksheet($excel->getActiveSheet());
		}
		
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PT FINTEK KARYA NUSANTARA");
		$excel->getActiveSheet()->mergeCells('B1:M1');
		$excel->getActiveSheet()->getStyle('B1:M1')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "STATEMENTS OF FINANCIAL POSITION");
		$excel->getActiveSheet()->mergeCells('B2:M2');
		$excel->getActiveSheet()->getStyle('B2:M2')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('B3', $bulan.", ".$year." (UNAUDITED)");
		$excel->getActiveSheet()->mergeCells('B3:M3');
		$excel->getActiveSheet()->getStyle('B3:M3')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('B4', "(In Indonesian Rupiah)");
		$excel->getActiveSheet()->mergeCells('B4:M4');
		$excel->getActiveSheet()->getStyle('B4:M4')->applyFromArray($style_nature);

		$excel->getActiveSheet()->getStyle('B5:M5')->applyFromArray($style_double);
		$excel->setActiveSheetIndex(0)->setCellValue('B7', "ASSETS");
		$excel->getActiveSheet()->getStyle('B7')->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('J7', "LIABILITIES AND EQUITY");
		$excel->getActiveSheet()->getStyle('J7')->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('B8:M8')->applyFromArray($style_line);
		$excel->setActiveSheetIndex(0)->setCellValue('E9', $bulan."-".$year);
		$excel->getActiveSheet()->getStyle('E9')->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('B11', "CURRENT ASSETS");
		$excel->getActiveSheet()->getStyle('B11')->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('H9')->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('D12', "Rp");
		$excel->setActiveSheetIndex(0)->setCellValue('I11', "CURRENT LIABILITIES");
		$excel->getActiveSheet()->getStyle('I11')->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('M9', $bulan."-".$year);
		$excel->getActiveSheet()->getStyle('M9')->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('L12', "Rp");

		$hasil = $this->report_xls->get_bs($year, $month);

		$data = array();
		$category_arr = array(
						"cnc"      	=> array("Cash and cash equivalents"),
						"cfcmd"     => array("Cash from customer and merchant deposits"),
						"ur"        => array("Unbilled revenues"),
						"or"        => array("Other receivables"),
						"pe"        => array("Prepaid expenses"),
						"pt"        => array("Prepaid taxes"),
						"oca_new"   => array("Other current assets"),
						"ltl"       => array("Long-term loan - net of current maturities"),
						"fa"        => array("Fixed assets (net)"),
						"ia"        => array("Intangible assets (net)"),
						"rd"        => array("Refundable deposits"),
						"dta"       => array("Deferred tax asset (net)"),
						"ap"        => array("Accounts payable"),
						"op"        => array("Other payable"),
						"tp"        => array("Taxes payable"),
						"acc"       => array("Accruals"),
						"cd"        => array("Customer deposits"),
						"mdp"       => array("Merchant deposits and payable"),
						"oufl"      => array("Obligation under finance lease - current maturities"),
						"ebl"       => array("Employee benefits liability"),
						"oufnl"      => array("Obligation under finance lease -  non current maturities"),
						"apc"       => array("Additional paid-in capital"),
						"ad"        => array("Accumulated Deficit"),
						"ar"        => array("Accounts receivable"),
						"ll"        => array("Lease liabilities - current portion"),
						"lln"       => array("Lease liabilities - non-current portion"),
						);

		foreach ($hasil->result_array() as $row) {
			$group = $row['GROUP_REPORT'];

			if(in_array($group, $category_arr['cnc'])){
				$data['cnc'][] = $row;
			}elseif(in_array($group, $category_arr['cfcmd'])){
				$data['cfcmd'][] = $row;
			}elseif(in_array($group, $category_arr['ur'])){
				$data['ur'][] = $row;
			}elseif(in_array($group, $category_arr['or'])){
				$data['or'][] = $row;
			}elseif(in_array($group, $category_arr['pe'])){
				$data['pe'][] = $row;
			}elseif(in_array($group, $category_arr['pt'])){
				$data['pt'][] = $row;
			}elseif(in_array($group, $category_arr['oca_new'])){
				$data['oca_new'][] = $row;
			}elseif(in_array($group, $category_arr['ltl'])){
				$data['ltl'][] = $row;
			}elseif(in_array($group, $category_arr['fa'])){
				$data['fa'][] = $row;
			}elseif(in_array($group, $category_arr['ia'])){
				$data['ia'][] = $row;
			}elseif(in_array($group, $category_arr['rd'])){
				$data['rd'][] = $row;
			}elseif(in_array($group, $category_arr['dta'])){
				$data['dta'][] = $row;
			}elseif(in_array($group, $category_arr['ap'])){
				$data['ap'][] = $row;
			}elseif(in_array($group, $category_arr['op'])){
				$data['op'][] = $row;
			}elseif(in_array($group, $category_arr['tp'])){
				$data['tp'][] = $row;
			}elseif(in_array($group, $category_arr['acc'])){
				$data['acc'][] = $row;
			}elseif(in_array($group, $category_arr['cd'])){
				$data['cd'][] = $row;
			}elseif(in_array($group, $category_arr['mdp'])){
				$data['mdp'][] = $row;
			}elseif(in_array($group, $category_arr['oufl'])){
				$data['oufl'][] = $row;
			}elseif(in_array($group, $category_arr['ebl'])){
				$data['ebl'][] = $row;
			}elseif(in_array($group, $category_arr['oufnl'])){
				$data['oufnl'][] = $row;
			}elseif(in_array($group, $category_arr['apc'])){
				$data['apc'][] = $row;
			}elseif(in_array($group, $category_arr['ad'])){
				$data['ad'][] = $row;
			}elseif(in_array($group, $category_arr['ar'])){
				$data['ar'][] = $row;
			}elseif(in_array($group, $category_arr['ll'])){
				$data['ll'][] = $row;
			}elseif(in_array($group, $category_arr['lln'])){
				$data['lln'][] = $row;
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

		$category = "cnc";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$cncTotal = 0;
		$cncGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$cncGroupReport = $value['GROUP_REPORT'];
			$cncTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrow = 12;
		if($cncTotal > 0 || $cncTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $cncGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $cncTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrow.':H'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrow++;

		}

		$category = "cfcmd";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$cfcmdTotal = 0;
		$cfcmdGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$cfcmdGroupReport = $value['GROUP_REPORT'];
			$cfcmdTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowcfcmd = $numrow+=0;
		if($cfcmdTotal > 0 || $cfcmdTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcfcmd, $cfcmdGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcfcmd, $cfcmdTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowcfcmd)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowcfcmd++;

		}

		$category = "ar";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$arTotal = 0;
		$arGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$arGroupReport = $value['GROUP_REPORT'];
			$arTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowar = $numrowcfcmd+=0;
		if($arTotal > 0 || $arTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowar, $arGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowar, $arTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowar)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowar++;

		}else{
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowar, "Accounts Receivable");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowar, "0");
			$excel->getActiveSheet()->getStyle('E'.$numrowar)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowar++;
		}

		$category = "ur";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$urTotal = 0;
		$urGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$urGroupReport = $value['GROUP_REPORT'];
			$urTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowur = $numrowar+=0;
		if($urTotal > 0 || $urTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowur, $urGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowur, $urTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowur)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowur++;

		}

		$category = "or";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$opTotal = 0;
		$opGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$opGroupReport = $value['GROUP_REPORT'];
			$opTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowop = $numrowur+=0;
		if($opTotal > 0 || $opTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowop, $opGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowop, $opTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowop)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowop++;

		}

		$category = "pe";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$peTotal = 0;
		$peGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$peGroupReport = $value['GROUP_REPORT'];
			$peTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowpe = $numrowop+=0;
		if($peTotal > 0 || $peTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpe, $peGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpe, $peTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowpe)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowpe++;

		}

		$category = "pt";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$ptTotal = 0;
		$ptGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$ptGroupReport = $value['GROUP_REPORT'];
			$ptTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowpt = $numrowpe+=0;
		if($ptTotal > 0 || $ptTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpt, $ptGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpt, $ptTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowpt)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowpt++;

		}
		
		$category = "oca_new";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$oca_newTotal = 0;
		$oca_newGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$oca_newGroupReport = $value['GROUP_REPORT'];
			$oca_newTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowoca_new = $numrowpt+=0;
		if($ptTotal > 0 || $ptTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoca_new, $oca_newGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoca_new, $oca_newTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowoca_new)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowoca_new++;

		}

		$numrowtca = $numrowoca_new+=1;
		$ttl 	   = $numrow - 1;
		$ttl1 	   = $numrowoca_new - 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtca, "Total Current Assets");
		$excel->getActiveSheet()->getStyle('C'.$numrowtca)->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtca, '=SUM(E'.$ttl.':E'.$ttl1.')');
		$excel->getActiveSheet()->getStyle('E'.$numrowtca)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$numrowtca.':E'.$numrowtca)->applyFromArray($style_total);

		$category = "ltl";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$ltlTotal = 0;
		$ltlGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$ltlGroupReport = $value['GROUP_REPORT'];
			$ltlTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowltl = $numrowtca+=3;
		if($ltlTotal > 0 || $ltlTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowltl, $ltlGroupReport);
			$excel->getActiveSheet()->getStyle('B'.$numrowltl)->applyFromArray($style_sub);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowltl, $ltlTotal);
			$excel->getActiveSheet()->getStyle('D'.$numrowltl.':E'.$numrowltl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$numrowltl)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowltl++;

		}elseif($ltlTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowltl, "LONG-TERM INVESTMENT");
			$excel->getActiveSheet()->getStyle('B'.$numrowltl)->applyFromArray($style_sub);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowltl, $ltlTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowltl)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');
			$excel->getActiveSheet()->getStyle('D'.$numrowltl.':E'.$numrowltl)->applyFromArray($style_total);

			$numrowltl++;
		}

		$numrownca = $numrowltl+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrownca, "NON CURRENT ASSET");
		$excel->getActiveSheet()->getStyle('B'.$numrownca)->applyFromArray($style_sub);

		$category = "fa";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$faTotal = 0;
		$faGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$faGroupReport = $value['GROUP_REPORT'];
			$faTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowfa = $numrownca+=1;
		if($faTotal > 0 || $faTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowfa, $faGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfa, $faTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowfa)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowfa++;

		}elseif($faTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowfa, "Fixed assets (net)");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfa, $faTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowfa)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowfa++;
		}

		$category = "ia";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$iaTotal = 0;
		$iaGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$iaGroupReport = $value['GROUP_REPORT'];
			$iaTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowia = $numrowfa+=0;
		if($iaTotal > 0 || $iaTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowia, $iaGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowia, $iaTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowia)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowia++;

		}elseif($iaTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowia, "Intangible assets (net)");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowia, $iaTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowia)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowia++;
		}

		$category = "rd";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$rdTotal = 0;
		$rdGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$rdGroupReport = $value['GROUP_REPORT'];
			$rdTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowrd = $numrowia+=0;
		if($rdTotal > 0 || $rdTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrd, $rdGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrd, $rdTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowrd)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowrd++;

		}elseif($rdTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrd, "Refundable deposits");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrd, $rdTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowrd)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowrd++;
		}

		$category = "dta";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$dtaTotal = 0;
		$dtaGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$dtaGroupReport = $value['GROUP_REPORT'];
			$dtaTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowdta = $numrowrd+=0;
		if($dtaTotal > 0 || $dtaTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdta, $dtaGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdta, $dtaTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowdta)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowdta++;

		}elseif($dtaTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdta, "Deferred tax asset (net)");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdta, $dtaTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowdta)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');

			$numrowdta++;
		}

		$numrowtoa = $numrowdta+=1;
		$sumnca = $numrowfa-1;
		$sumnca1 = $numrowdta-2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtoa, "Total Other Assets");
		$excel->getActiveSheet()->getStyle('C'.$numrowtoa)->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtoa, '=SUM(E'.$sumnca.':E'.$sumnca1.')');
		$excel->getActiveSheet()->getStyle('E'.$numrowtoa)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$numrowtoa.':E'.$numrowtoa)->applyFromArray($style_total);

		$numrowta = $numrowtoa+=11;
		$sumnta  = $numrowtca-3;
		$sumnta1 = $numrowltl-2;
		$sumnta2 = $numrowdta+=0;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowta, "TOTAL ASSETS");
		$excel->getActiveSheet()->getStyle('B'.$numrowta)->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowta, '=E'.$sumnta.'+E'.$sumnta1.'+E'.$sumnta2);
		$excel->getActiveSheet()->getStyle('E'.$numrowta)->getNumberFormat()->setFormatCode('_(#,##_);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$numrowta.':E'.$numrowta)->applyFromArray($style_end);

		$category = "ap";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$apTotal = 0;
		$apGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$apGroupReport = $value['GROUP_REPORT'];
			$apTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowap = 12;
		if($apTotal > 0 || $apTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowap, $apGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowap, $apTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowap)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowap++;

		}elseif($apTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowap, "Accounts payable");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowap, $apTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowap)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowap++;
		}

		$category = "op";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$opTotal = 0;
		$opGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$opGroupReport = $value['GROUP_REPORT'];
			$opTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowop = $numrowap+=0;
		if($opTotal > 0 || $opTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowop, $opGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowop, $opTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowop)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowop++;

		}elseif($opTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowop, "Other payable");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowop, $opTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowop)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowop++;
		}

		$category = "tp";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$tpTotal = 0;
		$tpGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$tpGroupReport = $value['GROUP_REPORT'];
			$tpTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowtp = $numrowop+=0;
		if($tpTotal > 0 || $tpTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtp, $tpGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowtp, $tpTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowtp)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowtp++;

		}elseif($tpTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtp, "Taxes payable");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowtp, $tpTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowtp)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowtp++;
		}

		$category = "acc";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$accTotal = 0;
		$accGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$accGroupReport = $value['GROUP_REPORT'];
			$accTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowacc = $numrowtp+=0;
		if($accTotal > 0 || $accTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowacc, $accGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowacc, $accTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowacc)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowacc++;

		}elseif($accTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowacc, "Accruals");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowacc, $accTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowacc)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowacc++;
		}

		$category = "cd";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$cdTotal = 0;
		$cdGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$cdGroupReport = $value['GROUP_REPORT'];
			$cdTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowcd = $numrowacc+=0;
		if($cdTotal > 0 || $cdTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcd, $cdGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowcd, $cdTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowcd)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowcd++;

		}elseif($cdTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcd, "Customer deposits");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowcd, $cdTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowcd)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowcd++;
		}

		$category = "mdp";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$mdpTotal = 0;
		$mdpGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$mdpGroupReport = $value['GROUP_REPORT'];
			$mdpTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowmdp = $numrowcd+=0;
		if($mdpTotal > 0 || $mdpTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowmdp, $mdpGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowmdp, $mdpTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowmdp)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowmdp++;

		}elseif($mdpTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowmdp, "Merchant deposits and payable");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowmdp, $mdpTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowmdp)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowmdp++;
		}
		
		//new oufl
		
		$category = "oufl";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$ouflTotal = 0;
		$ouflGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$ouflGroupReport = $value['GROUP_REPORT'];
			$ouflTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowoufl = $numrowmdp+=0;
		if($ouflTotal > 0 || $ouflTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoufl, $ouflGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowoufl, $ouflTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowoufl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowoufl++;

		}elseif($ouflTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoufl, "Obligation under finance lease - current maturities");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowoufl, $ouflTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowoufl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowoufl++;
		}
		
		//  end oufl

		$category = "ll";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$llTotal = 0;
		$llGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$llGroupReport = $value['GROUP_REPORT'];
			$llTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowll = $numrowoufl+=0;
		if($llTotal > 0 || $llTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowll, $llGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowll, $llTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowll)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowll++;

		}elseif($llTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowll, "Lease Liabilities - current portion");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowll, $llTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowll)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowll++;
		}

		$numrowtcl = $numrowll+=2;
		$ttltcl 	   = $numrowap - 1;
		$ttltcl1 	   = $numrowll - 2;
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtcl, "Total Current Assets");
		$excel->getActiveSheet()->getStyle('J'.$numrowtcl)->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowtcl, '=SUM(M'.$ttltcl.':M'.$ttltcl1.')');
		$excel->getActiveSheet()->getStyle('M'.$numrowtcl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('L'.$numrowtcl.':M'.$numrowtcl)->applyFromArray($style_total);

		$numrowncl = $numrowtcl+=3;
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowncl, "NON-CURRENT LIABILITIES");
		$excel->getActiveSheet()->getStyle('J'.$numrowncl)->applyFromArray($style_sub);

		$category = "lln";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$llnTotal = 0;
		$llnGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$llnGroupReport = $value['GROUP_REPORT'];
			$llnTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowlln = $numrowncl+=1;
		if($llnTotal > 0 || $llnTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowlln, $llnGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowlln, $llnTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowlln)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowlln++;

		}elseif($llnTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowlln, "Lease liabilities - non-current portion");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowlln, $llnTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowlln)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowlln++;
		}

		$category = "ebl";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$eblTotal = 0;
		$eblGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$eblGroupReport = $value['GROUP_REPORT'];
			$eblTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowebl = $numrowlln+=0;
		if($eblTotal > 0 || $eblTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowebl, $eblGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowebl, $eblTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowebl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowebl++;

		}elseif($eblTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowebl, "Employee benefits liability");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowebl, $eblTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowebl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowebl++;
		}
		
		//new oufnl
		
		$category = "oufnl";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$oufnlTotal = 0;
		$oufnlGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$oufnlGroupReport = $value['GROUP_REPORT'];
			$oufnlTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowoufnl = $numrowebl+=0;
		if($oufnlTotal > 0 || $oufnlTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoufnl, $oufnlGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowoufnl, $oufnlTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowoufnl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowoufnl++;

		}elseif($oufnlTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoufnl, "Obligation under finance lease -  non current maturities");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowoufnl, $oufnlTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowoufnl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowoufnl++;
		}
		
		//end oufnl

		/*$category = "oufnl";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$ntclTotal = 0;
		$ntclGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$ntclGroupReport = $value['GROUP_REPORT'];
			$ntclTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowntcl = $numrowoufnl+=2;
		if($ntclTotal > 0 || $ntclTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowntcl, "Non-Total Current Liabilities");
			$excel->getActiveSheet()->getStyle('J'.$numrowntcl)->applyFromArray($style_sub);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowntcl, $ntclTotal);
			$excel->getActiveSheet()->getStyle('L'.$numrowntcl.':M'.$numrowntcl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('M'.$numrowntcl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowntcl++;
			
		}elseif($ntclTotal == 0){
			$ttlntc = $numrowebl -1;
			$ttlntc1 = $numrowoufnl -2;

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowntcl, "Non-Total Current Liabilities");
			$excel->getActiveSheet()->getStyle('J'.$numrowntcl)->applyFromArray($style_sub);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowntcl, '=SUM(M'.$ttlntc.':M'.$ttlntc1.')');
			$excel->getActiveSheet()->getStyle('L'.$numrowntcl.':M'.$numrowntcl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('M'.$numrowntcl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowntcl++;

		}*/

		$numrowntcl = $numrowoufnl+=2;
		$ttlntc1 = $numrowlln-1;
		$ttlntc2 = $numrowebl-1;
		$ttlntc3 = $numrowoufnl-3;

		$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowntcl, "Non-Total Current Liabilities");
		$excel->getActiveSheet()->getStyle('J'.$numrowntcl)->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowntcl, '=M'.$ttlntc1.'+M'.$ttlntc2.'+M'.$ttlntc3);
		$excel->getActiveSheet()->getStyle('L'.$numrowntcl.':M'.$numrowntcl)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('M'.$numrowntcl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

		$numroweqt = $numrowoufnl+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$numroweqt, "EQUITY");
		$excel->getActiveSheet()->getStyle('I'.$numroweqt)->applyFromArray($style_sub);

		$numrowsct = $numroweqt+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowsct, "Share capital - Rp10,000 par value per share");

		$numrowaut = $numrowsct+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrowaut, "Authorized - 320,000 shares");
		$numrowiaf = $numrowaut+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrowiaf, "Issued and fully paid 182,600 A series shares and");
		/*$numrowsheries = $numrowiaf+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrowsheries, "13,632 B series shares");*/

		$category = "cs";
		if(!isset($datacs[$category])){
			$datacs[$category] = array();
		}

		$hasilcs = $this->report_xls->get_she($year, $month, $category);

		$datacs = array();
		$category_arrcs = array(
						"cs"      		=> array("31100001")
						);

		foreach ($hasilcs->result_array() as $row) {
			$nature = $row['NATURE'];

			if(in_array($nature, $category_arrcs['cs'])){
				$datacs['cs'][] = $row;
			}
			
		}

		$ifpTotal = 0;
		$ifpGroupReport = "";

		foreach ($datacs[$category] as $key => $value) {
			$ifpGroupReport = $value['GROUP_REPORT'];
			$ifpTotal       += $value['AMOUNT'];
		}

		$numrowsheries = $numrowiaf+=1;
		if($ifpTotal > 0 || $ifpTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrowsheries, "13,632 B series shares");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowsheries, $ifpTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowsheries)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
			$numrowsheries++;
		}elseif($ifpTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrowsheries, "13,632 B series shares");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowsheries, $ifpTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowsheries)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowsheries++;
		}

		$category = "apc";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$apcTotal = 0;
		$apcGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$apcGroupReport = $value['GROUP_REPORT'];
			$apcTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrowapc = $numrowsheries+=0;
		if($apcTotal > 0 || $apcTotal < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowapc, $apcGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowapc, $apcTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowapc)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowapc++;

		}elseif($apcTotal == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowapc, "Additional paid-in capital");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowapc, $apcTotal);
			$excel->getActiveSheet()->getStyle('M'.$numrowapc)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowapc++;
		}

		$category = "ad";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$adTotal = 0;
		$adGroupReport = "";

		foreach ($data[$category] as $key => $value) {
			$adGroupReport = $value['GROUP_REPORT'];
			$adTotal       += $value['TOTAL_AMOUNT'];
		}

		$ttlacc = $adTotal + $fortp;

		$numrowad = $numrowapc+=0;
		if($ttlacc > 0 || $ttlacc < 0){

			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowad, $adGroupReport);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowad, $ttlacc);
			$excel->getActiveSheet()->getStyle('M'.$numrowad)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowad++;

		}elseif($ttlacc == 0){
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowad, "Accumulated Deficit");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrowad, $ttlacc);
			$excel->getActiveSheet()->getStyle('M'.$numrowad)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowad++;
		}

		$ttleqtrow = $numrowad+=1;
		$ttleqt = $numrowsheries-1;
		$ttleqt1 = $numrowad-2;
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$ttleqtrow, "Total Equity");
		$excel->getActiveSheet()->getStyle('J'.$ttleqtrow)->applyFromArray($style_sub);
		$excel->setActiveSheetIndex(0)->setCellValue('M'.$ttleqtrow, '=SUM(M'.$ttleqt.':M'.$ttleqt1.')');
		$excel->getActiveSheet()->getStyle('M'.$ttleqtrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('L'.$ttleqtrow.':M'.$ttleqtrow)->applyFromArray($style_total);

		$ttlallrow = $ttleqtrow+=2;
		$ttlall = $numrowtcl-3;
		$ttlall1 = $numrowntcl;
		$ttlall2 = $ttleqtrow-2;
		$excel->setActiveSheetIndex(0)->setCellValue('L'.$ttleqtrow, "Rp");
		$excel->setActiveSheetIndex(0)->setCellValue('M'.$ttlallrow, '=M'.$ttlall.'+M'.$ttlall1.'+M'.$ttlall2);
		$excel->getActiveSheet()->getStyle('M'.$ttlallrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('L'.$ttlallrow.':M'.$ttlallrow)->applyFromArray($style_end);


		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(45);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		
		
		
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		
		$excel->getActiveSheet(0)->setTitle("BS");
		$excel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="BS.xls"');
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}