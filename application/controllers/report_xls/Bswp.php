<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bswp extends CI_Controller {

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

	public function show_bswp(){
		$data['title']          = "BSWP";
		$data['module']         = "datatable";
		$data['template_page']  = "report_xls/bswp";
		$data['get_exist_year'] = $this->tb->get_exist_year();
		
		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_bswp(){
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(1200);
		}

		ini_set('memory_limit', '-1');

		$year 		= $_REQUEST['year'];
		$month		= $_REQUEST['month'];
		$year1 		= $_REQUEST['year1'];
		$month1		= $_REQUEST['month1'];
		$year2 		= $_REQUEST['year2'];
		$month2		= $_REQUEST['month2'];
		$bulan		= $_REQUEST['bulan'];
		$bulan1		= $_REQUEST['bulan1'];
		$bulan2		= $_REQUEST['bulan2'];
		$date	    = date("Y-m-d H:i:s");
		
		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('BSWP')
								->setLastModifiedBy('BSWP')
								->setTitle("BSWP")
								->setSubject("BSWP")
								->setDescription("BSWP")
								->setKeywords("BSWP");

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

		/*$style_total = array(
		   'alignment' => array(
		 	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		  ),
		  'borders' => array(
		  	  'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
		   'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);*/
		
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PT Finarya Karya Nusantara");
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "FINANCIAL POSITION - LONG FORM DETAIL");
		$excel->setActiveSheetIndex(0)->setCellValue('B3', strtoupper($bulan)." ".$year. " AND ".strtoupper($bulan1)." ".$year1." AND ".strtoupper($bulan2)." ".$year2." (UNAUDITED)");
		
		$excel->setActiveSheetIndex(0)->setCellValue('B5', "CLASS");
		$excel->getActiveSheet()->getStyle('B5:B6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('B5:B6');
		$excel->setActiveSheetIndex(0)->setCellValue('C5', "NATURE");
		$excel->getActiveSheet()->getStyle('C5:C6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('C5:C6');
		$excel->setActiveSheetIndex(0)->setCellValue('D5', "DESCRIPTION");
		$excel->getActiveSheet()->getStyle('D5:D6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('D5:D6');
		$excel->setActiveSheetIndex(0)->setCellValue('E5', $bulan."-".$year);
		$excel->getActiveSheet()->getStyle('E5:E6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('E5:E6');
		$excel->setActiveSheetIndex(0)->setCellValue('F5', $bulan1."-".$year1);
		$excel->getActiveSheet()->getStyle('F5:F6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('F5:F6');
		$excel->setActiveSheetIndex(0)->setCellValue('G5', $bulan2."-".$year2);
		$excel->getActiveSheet()->getStyle('G5:G6')->applyFromArray($style_header);
		$excel->getActiveSheet()->mergeCells('G5:G6');

		$excel->setActiveSheetIndex(0)->setCellValue('C7', "10000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D7', "ASSETS");
		$excel->setActiveSheetIndex(0)->setCellValue('C8', "11000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D8', "CURRENT ASSETS");
		$excel->setActiveSheetIndex(0)->setCellValue('C9', "11100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D9', "CASH AND BANK");
		$excel->setActiveSheetIndex(0)->setCellValue('C10', "11110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D10', "PETTY CASH");
		$excel->getActiveSheet()->getStyle('C7:C10')->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D7:D10')->applyFromArray($style_sub);

		$category = "petty";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		$hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$data = array();



		/*if($category == "petty"){
			$where = " where mc.NATURE in (11110001, 11110002)";
		}elseif($category == "disbur_idr"){
			$where = " where mc.NATURE in (11121001, 11121002)";
		}elseif($category == "disbur_usd"){
			$where = " where mc.NATURE = 11122001";
		}elseif($category == "placement"){
			$where = " where mc.NATURE = 11130001";
		}elseif($category == "merchant"){
			$where = " where mc.NATURE in (11151001, 11151002, 11151003, 11151004, 11151005)";
		}elseif($category == "floating"){
			$where = " where mc.NATURE in (11161001, 11161002, 11161003, 11161004, 11161005, 11161006, 11161007, 11161008)";
		}elseif($category == "deposit"){
			$where = " where mc.NATURE in (11171001, 11171002, 11171003)";
		}elseif($category == "clearing"){
			$where = " where mc.NATURE = 11180001";
		}elseif($category == "ar"){
			$where = " where mc.NATURE in (11211001, 11211002, 11211003, 11211004, 11211005, 11211006, 11211007, 11211008, 11211009)";
		}elseif($category == "ar1"){
			$where = " where mc.NATURE in (11212001, 11212002, 11212003, 11212004, 11212005, 11212006, 11212007, 11212008, 11212009)";
		}elseif($category == "aro"){
			$where = " where mc.NATURE = 11213001";
		}elseif($category == "clrin"){
			$where = " where mc.NATURE in (11123101, 11123102, 11123103, 11123104, 11123105, 11123106, 11123107, 11123108, 11123109)";
		}elseif($category == "clrout"){
			$where = " where mc.NATURE in (11123201, 11123202, 11123203, 11123204, 11123205, 11123206, 11123207, 11123208, 11123209)";
		}elseif($category == "clrsub"){
			$where = " where mc.NATURE in (11123301, 11123302, 11123303, 11123304, 11123305, 11123306, 11123307)";
		}elseif($category == "clrusd"){
			$where = " where mc.NATURE = 11123401";
		}elseif($category == "col"){
			$where = " where mc.NATURE = 11141001";
		}*/

		$category_arr = array(
						// "petty"      => array("11110001", "11110002"),
						"petty"      => array("11110"),
						// "disbur_idr" => array("11121001", "11121002"),
						"disbur_idr" => array("11121"),
						// "disbur_usd" => array("11122001"),
						"disbur_usd" => array("11122"),
						// "placement"  => array("11130001"),
						"placement"  => array("11130"),
						// "merchant"   => array("11151001", "11151002", "11151003", "11151004", "11151005"),
						"merchant"   => array("11151"),
						// "floating"   => array("11161001", "11161002", "11161003", "11161004", "11161005", "11161006", "11161007", "11161008"),
						"floating"   => array("11161"),
						// "deposit"    => array("11171001", "11171002", "11171003"),
						"deposit"    => array("11171"),
						// "clearing"   => array("11180001"),
						"clearing"   => array("11180"),
						// "ar"         => array("11211001", "11211002", "11211003", "11211004", "11211005", "11211006", "11211007", "11211008", "11211009"),
						"ar"         => array("11211"),
						// "ar1"        => array("11212001", "11212002", "11212003", "11212004", "11212005", "11212006", "11212007", "11212008", "11212009"),
						"ar1"        => array("11212"),
						// "aro"        => array("11213001"),
						"aro"        => array("11213"),
						// "clrin"      => array("11123101", "11123102", "11123103", "11123104", "11123105", "11123106", "11123107", "11123108", "11123109"),
						"clrin"      => array("111231"),
						// "clrout"     => array("11123201", "11123202", "11123203", "11123204", "11123205", "11123206", "11123207", "11123208", "11123209"),
						"clrout"     => array("111232"),
						// "clrsub"     => array("11123301", "11123302", "11123303", "11123304", "11123305", "11123306", "11123307"),
						"clrsub"     => array("111233"),
						// "clrusd"     => array("11123401"),
						"clrusd"     => array("111234"),
						// "plcidr"     => array("11131001"),
						"plcidr"     => array("11131"),
						// "col"        => array("11141001"),
						"col"        => array("11141"),
						// "acc"        => array("11310001", "11310002", "11310003", "11310004", "11310005", "11310006", "11310007", "11310008", "11310009"),
						"acc"        => array("11310"),
						// "acct"       => array("11320001", "11320002", "11320003", "11320004", "11320005", "11320006", "11320007", "11320008", "11320009"),
						"acct"       => array("11320"),
						// "accr"       => array("11330001"),
						"accr"       => array("11330"),
						// "acci"       => array("11340001", "11340002", "11340003"),
						"acci"       => array("11340"),
						// "inv"        => array("11410001", "11410002"),
						"inv"        => array("11410"),
						// "invv"       => array("11420001"),
						"invv"       => array("11420"),
						// "invd"       => array("11430001"),
						"invd"       => array("11430"),
						// "all"        => array("11440001", "11440002", "11440003"),
						"all"        => array("11440"),
						// "pre"        => array("11511001", "11511002", "11511003", "11511004", "11511005"),
						"pre"        => array("11511"),
						// "preo"       => array("11513001"),
						"preo"       => array("11513"),
						// "pret"       => array("11512001", "11512002", "11512003", "11512004", "11512005", "11512006", "11512007", "11512008"),
						"pret"       => array("11512"),
						// "adv"        => array("11521001", "11521002"),
						"adv"        => array("11521"),
						// "cla"        => array("11610001", "11610002", "11610003"),
						"cla"        => array("11610"),
						// "sec"        => array("12411001", "12411002"),
						"sec"        => array("12411"),
						// "cip"        => array("12111001", "12111002", "12111003", "12111004", "12111005", "12111006", "12111007", "12111008", "12111009", "12111011"),
						"cip"        => array("12111"),
						// "cost"       => array("12112001", "12112002", "12112003", "12112004", "12112005", "12112006", "12112007", "12112008", "12112009", "12112011"),
						"cost"       => array("12112"),
						// "accum"      => array("12113001", "12113002", "12113003", "12113006", "12113007", "12113008", "12113009", "12113011", "12113012", "12113013"),
						"accum"      => array("12113"),
						// "cipla"      => array("12121001", "12121002", "12121003", "12121004", "12121005"),
						"cipla"      => array("12121"),
						// "costla"     => array("12122001", "12122002", "12122003", "12122004", "12122005"),
						"costla"     => array("12122"),
						// "accde"      => array("12123001", "12123002", "12123003", "12123004", "12123005"),
						"accde"      => array("12123"),
						// "cipso"      => array("12210001"),
						"cipso"      => array("12210"),
						// "costso"     => array("12220001"),
						"costso"     => array("12220"),
						// "accso"      => array("12230001"),
						"accso"      => array("12230"),
						// "prepaid"    => array("12311101", "12311102", "12311103"),
						"prepaid"    => array("123111"),
						"othernon"   => array("0"),
						// "def"        => array("11620001"),
						"def"        => array("11620"),
						// "ap"         => array("21110001", "21110002"),
						"ap"         => array("21110"),
						// "apo"        => array("21121001"),
						"apo"        => array("21121"),
						// "apoo"       => array("21122001", "21122002", "21122003"),
						"apoo"       => array("21122"),
						// "apl"        => array("21124001"),
						"apl"        => array("21124"),
						// "accl"       => array("21211001", "21211002"),
						"accl"       => array("21211"),
						"accls"      => array("21221101", "21221102", "21221103", "21221104"),
						"acs"        => array("21221111", "21221112", "21221113", "21221114", "21221115"),
						// "int"        => array("21223101", "21223102"),
						"int"        => array("212231"),
						// "lbl"        => array("21223201", "21223202"),
						"lbl"        => array("212232"),
						// "vat"        => array("21310001", "21310002", "21310003", "21310004"),
						"vat"        => array("21310"),
						// "wht"        => array("21320001", "21320002", "21320003", "21320004", "21320005", "21320006", "21320007"),
						"wht"        => array("21320"),
						// "cit"        => array("21330001", "21330002"),
						"cit"        => array("21330"),
						// "aut"        => array("21340001"),
						"aut"        => array("21340"),
						// "short"      => array("21411001", "21411002"),
						"short"      => array("21411"),
						// "cp"         => array("21412001", "21412002"),
						"cp"         => array("21412"),
						// "ocl"        => array("21510001"),
						"ocl"        => array("21510"),
						// "lbt"        => array("21520001", "21520002", "21520003"),
						"lbt"        => array("21520"),
						// "bpjs"       => array("21540001"),
						"bpjs"       => array("21540"),
						// "dvd"        => array("21550001"),
						"dvd"        => array("21550"),
						"und"        => array("21560001", "21570001"),
						// "mtl"        => array("22110001", "22110002"),
						"mtl"        => array("22110"),
						// "mtb"        => array("22121001"),
						"mtb"        => array("22121"),
						// "lte"        => array("22130001", "22130002"),
						"lte"        => array("22130"),
						// "ltb"        => array("22140001"),
						"ltb"        => array("22140"),
						// "ncp"        => array("22211001", "22211002", "22211003", "22211004"),
						"ncp"        => array("22211"),
						// "dtl"        => array("21530001"),
						"dtl"        => array("21530"),
						// "equ"        => array("31100001", "31100002", "31100003"),
						"equ"        => array("31100"),
						// "reta"       => array("31200001", "31200002"),
						"reta"       => array("31200"),
						// "agl"        => array("31300001"),
						"agl"        => array("31300"),
						// "acd"        => array("31400001"),
						"acd"        => array("31400"),
						// "oci"        => array("32000001")
						"oci"        => array("32000")
					);

		foreach($hasil->result_array() as $row)	{

			$nature = $row['NATURE'];
			$nature1 = substr($row['NATURE'],0,5);
			$nature2 = substr($row['NATURE'],0,6);

			if(in_array($nature1, $category_arr['petty'])){
				$data['petty'][] = $row;
			}
			elseif(in_array($nature1, $category_arr['disbur_idr'])){
				$data['disbur_idr'][] = $row;
			}
			elseif(in_array($nature1, $category_arr['disbur_usd'])){
				$data['disbur_usd'][] = $row;
			}elseif(in_array($nature1, $category_arr['placement'])){
				$data['placement'][] = $row;
			}elseif(in_array($nature1, $category_arr['merchant'])){
				$data['merchant'][] = $row;
			}elseif(in_array($nature1, $category_arr['floating'])){
				$data['floating'][] = $row;
			}elseif(in_array($nature1, $category_arr['deposit'])){
				$data['deposit'][] = $row;
			}elseif(in_array($nature1, $category_arr['clearing'])){
				$data['clearing'][] = $row;
			}elseif(in_array($nature1, $category_arr['ar'])){
				$data['ar'][] = $row;
			}elseif(in_array($nature1, $category_arr['ar1'])){
				$data['ar1'][] = $row;
			}elseif(in_array($nature1, $category_arr['aro'])){
				$data['aro'][] = $row;
			}elseif(in_array($nature2, $category_arr['clrin'])){
				$data['clrin'][] = $row;
			}elseif(in_array($nature2, $category_arr['clrout'])){
				$data['clrout'][] = $row;
			}elseif(in_array($nature2, $category_arr['clrsub'])){
				$data['clrsub'][] = $row;
			}elseif(in_array($nature2, $category_arr['clrusd'])){
				$data['clrusd'][] = $row;
			}elseif(in_array($nature1, $category_arr['plcidr'])){
				$data['plcidr'][] = $row;
			}elseif(in_array($nature1, $category_arr['col'])){
				$data['col'][] = $row;
			}elseif(in_array($nature1, $category_arr['acc'])){
				$data['acc'][] = $row;
			}elseif(in_array($nature1, $category_arr['acct'])){
				$data['acct'][] = $row;
			}elseif(in_array($nature1, $category_arr['accr'])){
				$data['accr'][] = $row;
			}elseif(in_array($nature1, $category_arr['acci'])){
				$data['acci'][] = $row;
			}elseif(in_array($nature1, $category_arr['inv'])){
				$data['inv'][] = $row;
			}elseif(in_array($nature1, $category_arr['invv'])){
				$data['invv'][] = $row;
			}elseif(in_array($nature1, $category_arr['invd'])){
				$data['invd'][] = $row;
			}elseif(in_array($nature1, $category_arr['all'])){
				$data['all'][] = $row;
			}elseif(in_array($nature1, $category_arr['pre'])){
				$data['pre'][] = $row;
			}elseif(in_array($nature1, $category_arr['preo'])){
				$data['preo'][] = $row;
			}elseif(in_array($nature1, $category_arr['pret'])){
				$data['pret'][] = $row;
			}elseif(in_array($nature1, $category_arr['adv'])){
				$data['adv'][] = $row;
			}elseif(in_array($nature1, $category_arr['cla'])){
				$data['cla'][] = $row;
			}elseif(in_array($nature1, $category_arr['sec'])){
				$data['sec'][] = $row;
			}elseif(in_array($nature1, $category_arr['cip'])){
				$data['cip'][] = $row;
			}elseif(in_array($nature1, $category_arr['cost'])){
				$data['cost'][] = $row;
			}elseif(in_array($nature1, $category_arr['accum'])){
				$data['accum'][] = $row;
			}elseif(in_array($nature1, $category_arr['cipla'])){
				$data['cipla'][] = $row;
			}elseif(in_array($nature1, $category_arr['costla'])){
				$data['costla'][] = $row;
			}elseif(in_array($nature1, $category_arr['accde'])){
				$data['accde'][] = $row;
			}elseif(in_array($nature1, $category_arr['cipso'])){
				$data['cipso'][] = $row;
			}elseif(in_array($nature1, $category_arr['costso'])){
				$data['costso'][] = $row;
			}elseif(in_array($nature1, $category_arr['accso'])){
				$data['accso'][] = $row;
			}elseif(in_array($nature2, $category_arr['prepaid'])){
				$data['prepaid'][] = $row;
			}elseif(in_array($nature, $category_arr['othernon'])){
				$data['othernon'][] = $row;
			}elseif(in_array($nature1, $category_arr['def'])){
				$data['def'][] = $row;
			}elseif(in_array($nature1, $category_arr['ap'])){
				$data['ap'][] = $row;
			}elseif(in_array($nature1, $category_arr['apo'])){
				$data['apo'][] = $row;
			}elseif(in_array($nature1, $category_arr['apoo'])){
				$data['apoo'][] = $row;
			}elseif(in_array($nature1, $category_arr['apl'])){
				$data['apl'][] = $row;
			}elseif(in_array($nature1, $category_arr['accl'])){
				$data['accl'][] = $row;
			}elseif(in_array($nature, $category_arr['accls'])){
				$data['accls'][] = $row;
			}elseif(in_array($nature, $category_arr['acs'])){
				$data['acs'][] = $row;
			}elseif(in_array($nature2, $category_arr['int'])){
				$data['int'][] = $row;
			}elseif(in_array($nature2, $category_arr['lbl'])){
				$data['lbl'][] = $row;
			}elseif(in_array($nature1, $category_arr['vat'])){
				$data['vat'][] = $row;
			}elseif(in_array($nature1, $category_arr['wht'])){
				$data['wht'][] = $row;
			}elseif(in_array($nature1, $category_arr['cit'])){
				$data['cit'][] = $row;
			}elseif(in_array($nature1, $category_arr['aut'])){
				$data['aut'][] = $row;
			}elseif(in_array($nature1, $category_arr['short'])){
				$data['short'][] = $row;
			}elseif(in_array($nature1, $category_arr['cp'])){
				$data['cp'][] = $row;
			}elseif(in_array($nature1, $category_arr['ocl'])){
				$data['ocl'][] = $row;
			}elseif(in_array($nature1, $category_arr['lbt'])){
				$data['lbt'][] = $row;
			}elseif(in_array($nature1, $category_arr['bpjs'])){
				$data['bpjs'][] = $row;
			}elseif(in_array($nature1, $category_arr['dvd'])){
				$data['dvd'][] = $row;
			}elseif(in_array($nature, $category_arr['und'])){
				$data['und'][] = $row;
			}elseif(in_array($nature1, $category_arr['mtl'])){
				$data['mtl'][] = $row;
			}elseif(in_array($nature1, $category_arr['mtb'])){
				$data['mtb'][] = $row;
			}elseif(in_array($nature1, $category_arr['lte'])){
				$data['lte'][] = $row;
			}elseif(in_array($nature1, $category_arr['ltb'])){
				$data['ltb'][] = $row;
			}elseif(in_array($nature1, $category_arr['ncp'])){
				$data['ncp'][] = $row;
			}elseif(in_array($nature1, $category_arr['dtl'])){
				$data['dtl'][] = $row;
			}elseif(in_array($nature1, $category_arr['equ'])){
				$data['equ'][] = $row;
			}elseif(in_array($nature1, $category_arr['reta'])){
				$data['reta'][] = $row;
			}elseif(in_array($nature1, $category_arr['agl'])){
				$data['agl'][] = $row;
			}elseif(in_array($nature1, $category_arr['acd'])){
				$data['acd'][] = $row;
			}elseif(in_array($nature1, $category_arr['oci'])){
				$data['oci'][] = $row;
			}
		}

		$numrow = 11;
		$total = $numrow;
		$total +=2;			
		foreach($data[$category] as $row)	{ // perhatikan yg diambil adalah variabel data dan indeks yang akan di tampilkan

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrow.':G'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$total1 = $total-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$total, "SUBTOTAL PETTY CASH");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$total, '=SUM(E11:E'.$total1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$total, '=SUM(F11:F'.$total1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$total, '=SUM(G11:G'.$total1.')');
			$excel->getActiveSheet()->getStyle('D'.$total.':G'.$total)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$total.':G'.$total)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrow++;
		}

		// lanjutin untuk index setelah pretty

		$numdisbur = $total;
		$numdisbur += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdisbur, "11120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdisbur, "BANK DISBURSEMENT");
		$excel->getActiveSheet()->getStyle('C'.$numdisbur)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdisbur)->applyFromArray($style_sub);

		$numdisburid = $numdisbur;
		$numdisburid += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdisburid, "11121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdisburid, "BANK DISBURSEMENT - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numdisburid)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdisburid)->applyFromArray($style_sub);

		$category = "disbur_idr";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);
		$numdisrow = $numdisburid;
		$numdisrow += 1;
		$totdis = $numdisrow;
		$totdis += 2;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numdisrow, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdisrow, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numdisrow)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdisrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numdisrow, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numdisrow, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numdisrow, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numdisrow.':G'.$numdisrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numdisrow++;
		}

			$sumdisrow  = $numdisburid+=1;
			$sumdisrow1 = $numdisrow-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdis, "SUBTOTAL BANK DISBURSEMENT - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdis, '=SUM(E'.$sumdisrow.':E'.$sumdisrow1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdis, '=SUM(F'.$sumdisrow.':F'.$sumdisrow1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdis, '=SUM(G'.$sumdisrow.':G'.$sumdisrow1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdis.':G'.$totdis)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdis.':G'.$totdis)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdisusd = $totdis;
		$numdisusd += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdisusd, "11122000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdisusd, "BANK DISBURSEMENT -  FOREIGN CURRENCY");
		$excel->getActiveSheet()->getStyle('C'.$numdisusd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdisusd)->applyFromArray($style_sub);

		$category = "disbur_usd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);
		// $data = $hasil->row();

		// $grup = $data->GROUP_REPORT;
		// $nature = $data->NATURE;
		// $desc = $data->DESCRIPTION;
		// $amount = $data->AMOUNT;
		// $amount1 = $data->AMOUNT1;
		// $amount2 = $data->AMOUNT2;

		$numdisrowusd = $numdisusd;
		$numdisrowusd += 1;
		$totusd = $numdisrowusd;
		$totusd += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numdisrowusd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdisrowusd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numdisrowusd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdisrowusd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numdisrowusd, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numdisrowusd, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numdisrowusd, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numdisrowusd.':G'.$numdisrowusd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numdisrowusd++;
		}

			$sumdisrowusd  = $numdisusd+=1;
			$sumdisrowusd1 = $numdisrowusd-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totusd, "Subtotal Bank Disbursement - Foreign Currency");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totusd, '=SUM(E'.$sumdisrowusd.':E'.$sumdisrowusd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totusd, '=SUM(F'.$sumdisrowusd.':F'.$sumdisrowusd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totusd, '=SUM(G'.$sumdisrowusd.':G'.$sumdisrowusd.')');
			$excel->getActiveSheet()->getStyle('D'.$totusd.':G'.$totusd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totusd.':G'.$totusd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totusd1 = $totusd;
			$totusd1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totusd1, "BANK DISBURSEMENT -  FOREIGN CURRENCY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totusd1, '=SUM(E'.$sumdisrowusd.':E'.$numdisrowusd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totusd1, '=SUM(F'.$sumdisrowusd.':F'.$numdisrowusd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totusd1, '=SUM(G'.$sumdisrowusd.':G'.$numdisrowusd.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totusd1.':G'.$totusd1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totusd1.':G'.$totusd1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totusd2 = $totusd1;
			$totusd2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totusd2, "SUBTOTAL BANK DISBURSEMENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totusd2, '=E'.$totusd1.'+E'.$totdis);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totusd2, '=F'.$totusd1.'+F'.$totdis);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totusd2, '=G'.$totusd1.'+G'.$totdis);
			$excel->getActiveSheet()->getStyle('D'.$totusd2.':G'.$totusd2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totusd2.':G'.$totusd2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numplc = $totusd2;
		$numplc += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numplc, "11130000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numplc, "BANK PLACEMENT");
		$excel->getActiveSheet()->getStyle('C'.$numplc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numplc)->applyFromArray($style_sub);

		$numplc1 = $numplc;
		$numplc1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numplc1, "11131000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numplc1, "BANK PLACEMENT - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numplc1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numplc1)->applyFromArray($style_sub);

		$category = "placement";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowplc = $numplc1;
		$numrowplc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowplc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowplc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowplc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowplc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowplc, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowplc, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowplc, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowplc.':G'.$numrowplc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowplc++;
		}

			$totplc  = $numrowplc+=0;
			$sumplc1 = $numrowplc-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totplc, "SUBTOTAL BANK PLACEMENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totplc, '=SUM(E'.$numplc.':E'.$sumplc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totplc, '=SUM(F'.$numplc.':F'.$sumplc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totplc, '=SUM(G'.$numplc.':G'.$sumplc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totplc.':G'.$totplc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totplc.':G'.$totplc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$nummrc = $totplc+=3;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummrc, "11150000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummrc, "BANK DEPOSIT MERCHANT");
		$excel->getActiveSheet()->getStyle('C'.$nummrc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummrc)->applyFromArray($style_sub);

		$nummrc1 = $nummrc;
		$nummrc1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummrc1, "11151000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummrc1, "BANK DEPOSIT MERCHANT");
		$excel->getActiveSheet()->getStyle('C'.$nummrc1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummrc1)->applyFromArray($style_sub);

		$category = "merchant";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowmrc = $nummrc1;
		$numrowmrc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmrc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmrc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmrc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmrc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmrc, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmrc, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmrc, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmrc.':G'.$numrowmrc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmrc++;
		}
			$totmrc = $numrowmrc;
			$totmrc += 0;
			$sumrowmrc1 = $numrowmrc-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmrc, "Subtotal Bank Deposit Merchant - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmrc, '=SUM(E'.$nummrc.':E'.$sumrowmrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmrc, '=SUM(F'.$nummrc.':F'.$sumrowmrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmrc, '=SUM(G'.$nummrc.':G'.$sumrowmrc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totmrc.':G'.$totmrc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmrc.':G'.$totmrc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totmrc1 = $totmrc;
			$totmrc1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmrc1, "Subtotal Bank Deposit Merchant - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmrc1, '=SUM(E'.$nummrc.':E'.$totmrc.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmrc1, '=SUM(F'.$nummrc.':F'.$totmrc.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmrc1, '=SUM(G'.$nummrc.':G'.$totmrc.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totmrc1.':G'.$totmrc1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmrc1.':G'.$totmrc1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numflt = $totmrc1;
		$numflt += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numflt, "11160000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numflt, "BANK FLOATING MONEY ");
		$excel->getActiveSheet()->getStyle('C'.$numflt)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numflt)->applyFromArray($style_sub);

		$numflt1 = $numflt;
		$numflt1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numflt1, "11161000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numflt1, "BANK FLOATING MONEY - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numflt1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numflt1)->applyFromArray($style_sub);

		$category = "floating";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowflt = $numflt1;
		$numrowflt += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowflt, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowflt, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowflt)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowflt, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowflt, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowflt, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowflt, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowflt.':G'.$numrowflt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowflt++;;
		}

			$totflt = $numrowflt;
			$totflt += 0;
			$sumflt = $numflt1+=1;
			$sumflt1 = $numrowflt-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totflt, "SUBTOTAL FLOATING MONEY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totflt, '=SUM(E'.$sumflt.':E'.$sumflt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totflt, '=SUM(F'.$sumflt.':F'.$sumflt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totflt, '=SUM(G'.$sumflt.':G'.$sumflt1.')');
			$excel->getActiveSheet()->getStyle('D'.$totflt.':G'.$totflt)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totflt.':G'.$totflt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totflt1 = $totflt;
			$totflt1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totflt1, "SUBTOTAL BANK FLOATING MONEY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totflt1, '=SUM(E'.$sumflt.':E'.$totflt.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totflt1, '=SUM(F'.$sumflt.':F'.$totflt.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totflt1, '=SUM(G'.$sumflt.':G'.$totflt.')');
			$excel->getActiveSheet()->getStyle('D'.$totflt1.':G'.$totflt1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totflt1.':G'.$totflt1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totflt2 = $totflt1;
			$totflt2 += 1;
			$totplc1 = $numrowplc+0;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totflt2, "SUBTOTAL CASH AND BANK");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totflt2, '=E'.$totflt1.'+E'.$totmrc1.'+E'.$totplc1.'+E'.$totusd2.'+E'.$total);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totflt2, '=F'.$totflt1.'+F'.$totmrc1.'+F'.$totplc1.'+F'.$totusd2.'+F'.$total);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totflt2, '=G'.$totflt1.'+G'.$totmrc1.'+G'.$totplc1.'+G'.$totusd2.'+G'.$total);
			$excel->getActiveSheet()->getStyle('D'.$totflt2.':G'.$totflt2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totflt2.':G'.$totflt2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdps = $totflt2;
		$numdps += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdps, "11170000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdps, "DEPOSITS");
		$excel->getActiveSheet()->getStyle('C'.$numdps)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdps)->applyFromArray($style_sub);

		$numdps1 = $numdps;
		$numdps1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdps1, "11171000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdps1, "TIME DEPOSIT <= 3 MONTH - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numdps1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdps1)->applyFromArray($style_sub);

		$category = "deposit";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowdps = $numdps1;
		$numrowdps += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdps, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdps, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdps)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdps, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdps, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdps, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdps, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdps.':G'.$numrowdps)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdps++;
		}

			$totdps = $numrowdps;
			$totdps += 0;
			$sumdps = $numdps1+=1;
			$sumdps1 = $numrowdps-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdps, "Subtotal Time Deposit <=3 Mth - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdps, '=SUM(E'.$numdps.':E'.$sumdps1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdps, '=SUM(F'.$numdps.':F'.$sumdps1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdps, '=SUM(G'.$numdps.':G'.$sumdps1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdps.':G'.$totdps)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdps.':G'.$totdps)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numclr = $totdps;
		$numclr += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclr, "11180000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclr, "DEPOSIT CLEARING");
		$excel->getActiveSheet()->getStyle('C'.$numclr)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclr)->applyFromArray($style_sub);

		$category = "clearing";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasilclr = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowclr = $numclr;
		$numrowclr += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowclr, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowclr, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowclr)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowclr, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowclr, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowclr, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowclr, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowclr.':G'.$numrowclr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowclr++;
		}

			$totclr = $numrowclr;
			$totclr += 0;
			$sumclr1 = $numrowclr-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclr, "Subtotal Deposit Clearing");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclr, '=SUM(E'.$numclr.':E'.$sumclr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclr, '=SUM(F'.$numclr.':F'.$sumclr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclr, '=SUM(G'.$numclr.':G'.$sumclr1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclr.':G'.$totclr)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclr.':G'.$totclr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totclr1 = $totclr;
			$totclr1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclr1, "SUBTOTAL DEPOSITS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclr1, '=SUM(E'.$numdps.':E'.$totclr.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclr1, '=SUM(F'.$numdps.':F'.$totclr.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclr1, '=SUM(G'.$numdps.':G'.$totclr.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totclr1.':G'.$totclr1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclr1.':G'.$totclr1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numar = $totclr1;
		$numar += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numar, "11200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numar, "ACCOUNT RECEIVABLES (AR)");
		$excel->getActiveSheet()->getStyle('C'.$numar)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numar)->applyFromArray($style_sub);

		$numar1 = $numar;
		$numar1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numar1, "11210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numar1, "AR - RELATED PARTY");
		$excel->getActiveSheet()->getStyle('C'.$numar1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numar1)->applyFromArray($style_sub);

		$category = "ar";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowar = $numar1;
		$numrowar += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowar, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowar, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowar)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowar, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowar, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowar, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowar, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowar.':G'.$numrowar)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowar++;
		}

			$totar = $numrowar;
			$totar += 0;

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$totar, "11212000");
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totar, "AR - THIRD PARTY");
			$excel->getActiveSheet()->getStyle('D'.$totar)->applyFromArray($style_sub);

		$category = "ar1";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowar1 = $totar;
		$numrowar1 += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowar1, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowar1, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowar1)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowar1, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowar1, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowar1, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowar1, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowar1.':G'.$numrowar1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowar1++;
		}

			$totar1 = $numrowar1;
			$totar1 += 0;
			$sumrowar1 = $numrowar1-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totar1, "Subtotal AR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totar1, '=SUM(E'.$numar.':E'.$sumrowar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totar1, '=SUM(F'.$numar.':F'.$sumrowar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totar1, '=SUM(G'.$numar.':G'.$sumrowar1.')');
			$excel->getActiveSheet()->getStyle('D'.$totar1.':G'.$totar1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totar1.':G'.$totar1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totar2 = $totar1;
			$totar2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totar2, "SUBTOTAL AR MERCHANT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totar2, '=SUM(E'.$numar.':E'.$totar1.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totar2, '=SUM(F'.$numar.':F'.$totar1.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totar2, '=SUM(G'.$numar.':G'.$totar1.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totar2.':G'.$totar2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totar2.':G'.$totar2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaro = $totar2;
		$numaro += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaro, "11213000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaro, "AR OTHER");
		$excel->getActiveSheet()->getStyle('C'.$numaro)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaro)->applyFromArray($style_sub);

		$category = "aro";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowaro = $numaro;
		$numrowaro += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaro, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaro, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaro)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaro, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaro, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaro, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaro, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaro.':G'.$numrowaro)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaro++;
		}
			$totaro = $numrowaro;
			$totaro += 0;
			$sumaro = $numrowaro-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaro, "SUBTOTAL AR OTHER");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaro, '=SUM(E'.$numaro.':E'.$sumaro.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaro, '=SUM(F'.$numaro.':F'.$sumaro.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaro, '=SUM(G'.$numaro.':G'.$sumaro.')');
			$excel->getActiveSheet()->getStyle('D'.$totaro.':G'.$totaro)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaro.':G'.$totaro)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numclrin = $totaro;
		$numclrin += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclrin, "11123000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclrin, "BANK  CLEARING");
		$excel->getActiveSheet()->getStyle('C'.$numclrin)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclrin)->applyFromArray($style_sub);

		$numclrin1 = $numclrin;
		$numclrin1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclrin1, "11123100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclrin1, "BANK  CLEARING IN - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numclrin1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclrin1)->applyFromArray($style_sub);

		$category = "clrin";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowclrin = $numclrin1;
		$numrowclrin += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowclrin, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowclrin, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowclrin)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowclrin, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowclrin, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowclrin, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowclrin, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowclrin.':G'.$numrowclrin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowclrin++;
		}

			$totclrin = $numrowclrin;
			$totclrin += 0;
			$sumclrin1 = $numrowclrin-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclrin, "Subtotal Bank Clearing In - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclrin, '=SUM(E'.$numclrin.':E'.$sumclrin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclrin, '=SUM(F'.$numclrin.':F'.$sumclrin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclrin, '=SUM(G'.$numclrin.':G'.$sumclrin1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclrin.':G'.$totclrin)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclrin.':G'.$totclrin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numclrout = $totclrin;
		$numclrout += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclrout, "11123200");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclrout, "BANK  CLEARING OUT - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numclrout)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclrout)->applyFromArray($style_sub);

		$category = "clrout";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowclrout = $numclrout;
		$numrowclrout += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowclrout, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowclrout, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowclrout)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowclrout, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowclrout, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowclrout, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowclrout, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowclrout.':G'.$numrowclrout)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowclrout++;
		}

			$totclrin = $numrowclrout;
			$totclrin += 0;
			$sumclrout1 = $numrowclrout-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclrin, "Subtotal Bank Clearing Out - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclrin, '=SUM(E'.$numclrout.':E'.$sumclrout1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclrin, '=SUM(F'.$numclrout.':F'.$sumclrout1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclrin, '=SUM(G'.$numclrout.':G'.$sumclrout1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclrin.':G'.$totclrin)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclrin.':G'.$totclrin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numclrsub = $totclrin;
		$numclrsub += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclrsub, "11123300");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclrsub, "BANK  CLEARING SUB ACCOUNT - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numclrsub)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclrsub)->applyFromArray($style_sub);

		$category = "clrsub";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowclrsub = $numclrsub;
		$numrowclrsub += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowclrsub, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowclrsub, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowclrsub)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowclrsub, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowclrsub, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowclrsub, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowclrsub, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowclrsub.':G'.$numrowclrsub)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowclrsub++;
		}

			$totclrin = $numrowclrsub;
			$totclrin += 0;
			$sumclrsub1 = $numrowclrsub-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclrin, "Subtotal Bank Clearing Sub Account- IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclrin, '=SUM(E'.$numclrsub.':E'.$sumclrsub1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclrin, '=SUM(F'.$numclrsub.':F'.$sumclrsub1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclrin, '=SUM(G'.$numclrsub.':G'.$sumclrsub1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclrin.':G'.$totclrin)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclrin.':G'.$totclrin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numclrusd = $totclrin;
		$numclrusd += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclrusd, "11123400");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclrusd, "BANK  CLEARING - FOREIGN CURRENCY");
		$excel->getActiveSheet()->getStyle('C'.$numclrusd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclrusd)->applyFromArray($style_sub);

		$category = "clrusd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowclrusd = $numclrusd;
		$numrowclrusd += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowclrusd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowclrusd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowclrusd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowclrusd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowclrusd, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowclrusd, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowclrusd, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowclrusd.':G'.$numrowclrusd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowclrusd++;
		}

			$totclrin = $numrowclrusd;
			$totclrin += 0;
			$sumclrsusd1 = $numrowclrusd-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclrin, "Subtotal Bank Clearing - USD");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclrin, '=SUM(E'.$numclrusd.':E'.$sumclrsusd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclrin, '=SUM(F'.$numclrusd.':F'.$sumclrsusd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclrin, '=SUM(G'.$numclrusd.':G'.$sumclrsusd1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclrin.':G'.$totclrin)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclrin.':G'.$totclrin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numplcidr = $totclrin;
		$numplcidr += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numplcidr, "11123400");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numplcidr, "BANK  CLEARING - FOREIGN CURRENCY");
		$excel->getActiveSheet()->getStyle('C'.$numplcidr)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numplcidr)->applyFromArray($style_sub);

		$category = "plcidr";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		// $hasil = $this->report_xls->get_bswp($year, $month, $category, $year1, $month1, $year2, $month2);

		$numrowplcidr = $numplcidr;
		$numrowplcidr += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowplcidr, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowplcidr, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowplcidr)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowplcidr, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowplcidr, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowplcidr, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowplcidr, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowplcidr.':G'.$numrowplcidr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowplcidr++;
		}

			$totclrin = $numrowplcidr;
			$totclrin += 1;
			$sumplcidr1 = $numrowplcidr-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclrin, "Subtotal Bank Clearing - EUR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclrin, '=SUM(E'.$numplcidr.':E'.$sumplcidr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclrin, '=SUM(F'.$numplcidr.':F'.$sumplcidr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclrin, '=SUM(G'.$numplcidr.':G'.$sumplcidr1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclrin.':G'.$totclrin)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclrin.':G'.$totclrin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcol = $totclrin;
		$numcol += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcol, "11140000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcol, "BANK COLLECTION");
		$excel->getActiveSheet()->getStyle('C'.$numcol)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcol)->applyFromArray($style_sub);

		$numcol1 = $numcol;
		$numcol1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcol1, "11141000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcol1, "BANK COLLECTION - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numcol1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcol1)->applyFromArray($style_sub);

		$category = "col";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcol = $numcol1;
		$numrowcol += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcol, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcol, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcol)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcol, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcol, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcol, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcol, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcol.':G'.$numrowcol)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcol++;
		}

			$totcol = $numrowcol;
			$totcol += 0;
			$sumcol1 = $numrowcol-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcol, "Subtotal Bank Collection - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcol, '=SUM(E'.$numcol.':E'.$sumcol1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcol, '=SUM(F'.$numcol.':F'.$sumcol1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcol, '=SUM(G'.$numcol.':G'.$sumcol1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcol.':G'.$totcol)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcol.':G'.$totcol)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcol1 = $totcol;
			$totcol1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcol1, "SUBTOTAL BANK CLEARING");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcol1, '=SUM(E'.$numclrin.':E'.$totcol.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcol1, '=SUM(F'.$numclrin.':F'.$totcol.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcol1, '=SUM(G'.$numclrin.':G'.$totcol.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totcol1.':G'.$totcol1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcol1.':G'.$totcol1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcol2 = $totcol1;
			$totcol2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcol2, "SUBTOTAL ACCOUNT RECEIVABLES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcol2, '=E'.$totaro.'+E'.$totar2.'+E'.$totcol1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcol2, '=F'.$totaro.'+F'.$totar2.'+F'.$totcol1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcol2, '=G'.$totaro.'+G'.$totar2.'+G'.$totcol1);
			$excel->getActiveSheet()->getStyle('D'.$totcol2.':G'.$totcol2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcol2.':G'.$totcol2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numacc = $totcol2;
		$numacc += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacc, "11300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacc, "ACCRUED RECEIVABLES");
		$excel->getActiveSheet()->getStyle('C'.$numacc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacc)->applyFromArray($style_sub);

		$numacc1 = $numacc;
		$numacc1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacc1, "11310000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacc1, "ACC REV MERCHANT");
		$excel->getActiveSheet()->getStyle('C'.$numacc1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacc1)->applyFromArray($style_sub);

		$numacc2 = $numacc1;
		$numacc2 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacc2, "11511000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacc2, "ACCR. REC. -  RELATED PARTY");
		$excel->getActiveSheet()->getStyle('C'.$numacc2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacc2)->applyFromArray($style_sub);

		$category = "acc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowacc = $numacc2;
		$numrowacc += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowacc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowacc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowacc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowacc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowacc, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowacc, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowacc, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowacc.':G'.$numrowacc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowacc++;
		}

			$totacc = $numrowacc;
			$totacc += 0;
			$sumacc1 = $numrowacc-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacc, "Subtotal Accr. Receivable Merchant");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacc, '=SUM(E'.$numacc.':E'.$sumacc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacc, '=SUM(F'.$numacc.':F'.$sumacc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacc, '=SUM(G'.$numacc.':G'.$sumacc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totacc.':G'.$totacc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacc.':G'.$totacc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numacct = $totacc;
		$numacct += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacct, "11320000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacct, "ACCR. REC. - THIRD PARTY");
		$excel->getActiveSheet()->getStyle('C'.$numacct)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacct)->applyFromArray($style_sub);

		$category = "acct";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowacct = $numacct;
		$numrowacct += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowacct, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowacct, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowacct)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowacct, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowacct, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowacct, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowacct, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowacct.':G'.$numrowacct)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowacct++;
		}

			$totacct = $numrowacct;
			$totacct += 0;
			$sumacct1 = $numrowacct-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacct, "Subtotal Accr. Receivable - 3P");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacct, '=SUM(E'.$numacct.':E'.$sumacct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacct, '=SUM(F'.$numacct.':F'.$sumacct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacct, '=SUM(G'.$numacct.':G'.$sumacct1.')');
			$excel->getActiveSheet()->getStyle('D'.$totacct.':G'.$totacct)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacct.':G'.$totacct)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totacct1 = $totacct;
			$totacct1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacct1, "SUBTOTAL ACCR. RECEIVABLES THIRD PARTY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacct1, '=SUM(E'.$numacc.':E'.$totacct.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacct1, '=SUM(F'.$numacc.':F'.$totacct.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacct1, '=SUM(G'.$numacc.':G'.$totacct.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totacct1.':G'.$totacct1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacct1.':G'.$totacct1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaccr = $totacct1;
		$numaccr += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccr, "11330000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccr, "ACCRUED REVENUE");
		$excel->getActiveSheet()->getStyle('C'.$numaccr)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccr)->applyFromArray($style_sub);

		$category = "accr";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaccr = $numaccr;
		$numrowaccr += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaccr, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaccr, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaccr)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaccr, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaccr, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaccr, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaccr, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaccr.':G'.$numrowaccr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaccr++;
		}

			$totaccr = $numrowaccr;
			$totaccr += 0;
			$sumaccr = $numrowaccr-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccr, "SUBTOTAL ACCRUED REVENUE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccr, '=SUM(E'.$numaccr.':E'.$sumaccr.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccr, '=SUM(F'.$numaccr.':F'.$sumaccr.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccr, '=SUM(G'.$numaccr.':G'.$sumaccr.')');
			$excel->getActiveSheet()->getStyle('D'.$totaccr.':G'.$totaccr)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccr.':G'.$totaccr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numacci = $totaccr;
		$numacci += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacci, "11340000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacci, "ACCRUED INTEREST INCOME");
		$excel->getActiveSheet()->getStyle('C'.$numacci)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacci)->applyFromArray($style_sub);

		$category = "acci";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowacci = $numacci;
		$numrowacci += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowacci, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowacci, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowacci)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowacci, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowacci, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowacci, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowacci, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowacci.':G'.$numrowacci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowacci++;
		}

			$totacci = $numrowacci;
			$totacci += 0;
			$sumacci = $numrowacci-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacci, "SUBTOTAL AcciUED INTEREST INCOME");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacci, '=SUM(E'.$numacci.':E'.$sumacci.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacci, '=SUM(F'.$numacci.':F'.$sumacci.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacci, '=SUM(G'.$numacci.':G'.$sumacci.')');
			$excel->getActiveSheet()->getStyle('D'.$totacci.':G'.$totacci)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacci.':G'.$totacci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totacci1 = $totacci;
			$totacci1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacci1, "SUBTOTAL ACCRUED RECEIVABLES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacci1, '=E'.$totacct1.'+E'.$totaccr.'+E'.$totacci);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacci1, '=F'.$totacct1.'+F'.$totaccr.'+F'.$totacci);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacci1, '=G'.$totacct1.'+G'.$totaccr.'+G'.$totacci);
			$excel->getActiveSheet()->getStyle('D'.$totacci1.':G'.$totacci1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacci1.':G'.$totacci1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numinv = $totacci1;
		$numinv += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numinv, "11400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numinv, "INVENTORY");
		$excel->getActiveSheet()->getStyle('C'.$numinv)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numinv)->applyFromArray($style_sub);

		$numinv1 = $numinv;
		$numinv1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numinv1, "11410000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numinv1, "INVENTORY OF STIKER AND CARD");
		$excel->getActiveSheet()->getStyle('C'.$numinv1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numinv1)->applyFromArray($style_sub);

		$category = "inv";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowinv = $numinv1;
		$numrowinv += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowinv, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowinv, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowinv)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowinv, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowinv, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowinv, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowinv, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowinv.':G'.$numrowinv)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowinv++;
		}

			$totinv = $numrowinv;
			$totinv += 0;
			$suminv = $numrowinv-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totinv, "Subtotal Inventory of Stiker, Voucher & Device");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totinv, '=SUM(E'.$numinv.':E'.$suminv.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totinv, '=SUM(F'.$numinv.':F'.$suminv.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totinv, '=SUM(G'.$numinv.':G'.$suminv.')');
			$excel->getActiveSheet()->getStyle('D'.$totinv.':G'.$totinv)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totinv.':G'.$totinv)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numinvv = $totinv;
		$numinvv += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numinvv, "11420000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numinvv, "INVENTORY VOUCHER");
		$excel->getActiveSheet()->getStyle('C'.$numinvv)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numinvv)->applyFromArray($style_sub);

		$category = "invv";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowinvv = $numinvv;
		$numrowinvv += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowinvv, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowinvv, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowinvv)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowinvv, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowinvv, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowinvv, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowinvv, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowinvv.':G'.$numrowinvv)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowinvv++;
		}

			$totinvv = $numrowinvv;
			$totinvv += 0;
			$suminvv = $numrowinvv-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totinvv, "Subtotal Inventory of Devices");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totinvv, '=SUM(E'.$numinvv.':E'.$suminvv.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totinvv, '=SUM(F'.$numinvv.':F'.$suminvv.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totinvv, '=SUM(G'.$numinvv.':G'.$suminvv.')');
			$excel->getActiveSheet()->getStyle('D'.$totinvv.':G'.$totinvv)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totinvv.':G'.$totinvv)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numinvd = $totinvv;
		$numinvd += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numinvd, "11430000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numinvd, "INVENTORY OF DEVICES");
		$excel->getActiveSheet()->getStyle('C'.$numinvd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numinvd)->applyFromArray($style_sub);

		$category = "invd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowinvd = $numinvd;
		$numrowinvd += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowinvd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowinvd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowinvd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowinvd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowinvd, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowinvd, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowinvd, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowinvd.':G'.$numrowinvd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowinvd++;
		}

			$totinvd = $numrowinvd;
			$totinvd += 0;
			$suminvd = $numrowinvd-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totinvd, "Subtotal Inventory of Devices");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totinvd, '=SUM(E'.$numinvd.':E'.$suminvd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totinvd, '=SUM(F'.$numinvd.':F'.$suminvd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totinvd, '=SUM(G'.$numinvd.':G'.$suminvd.')');
			$excel->getActiveSheet()->getStyle('D'.$totinvd.':G'.$totinvd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totinvd.':G'.$totinvd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numall = $totinvd;
		$numall += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numall, "11440000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numall, "ALLOWANCES FOR INVENT. OBSOLESCENCE");
		$excel->getActiveSheet()->getStyle('C'.$numall)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numall)->applyFromArray($style_sub);

		$category = "all";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowall = $numall;
		$numrowall += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowall, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowall, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowall)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowall, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowall, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowall, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowall, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowall.':G'.$numrowall)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowall++;
		}

			$totall = $numrowall;
			$totall += 0;
			$sumall = $numrowall-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totall, "Subtotal Allowances for Inventory Obsolescence");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totall, '=SUM(E'.$numall.':E'.$sumall.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totall, '=SUM(F'.$numall.':F'.$sumall.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totall, '=SUM(G'.$numall.':G'.$sumall.')');
			$excel->getActiveSheet()->getStyle('D'.$totall.':G'.$totall)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totall.':G'.$totall)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totall1 = $totall;
			$totall1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totall1, "SUBTOTAL INVENTORY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totall1, '=SUM(E'.$numinv.':E'.$totall.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totall1, '=SUM(F'.$numinv.':F'.$totall.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totall1, '=SUM(G'.$numinv.':G'.$totall.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totall1.':G'.$totall1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totall1.':G'.$totall1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpre = $totall1;
		$numpre += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpre, "11500000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpre, "PREPAYMENT & ADVANCES");
		$excel->getActiveSheet()->getStyle('C'.$numpre)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpre)->applyFromArray($style_sub);

		$numpre1 = $numpre;
		$numpre1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpre1, "11510000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpre1, "PREPAYMENT");
		$excel->getActiveSheet()->getStyle('C'.$numpre1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpre1)->applyFromArray($style_sub);

		$numpre2 = $numpre1;
		$numpre2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpre2, "PREPAID RENT - OFFICE BUILDING");
		$excel->getActiveSheet()->getStyle('C'.$numpre2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpre2)->applyFromArray($style_sub);

		$numpre3 = $numpre2;
		$numpre3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpre3, "11511000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpre3, "PREPAID RENT");
		$excel->getActiveSheet()->getStyle('C'.$numpre3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpre3)->applyFromArray($style_sub);

		$category = "pre";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpre = $numpre3;
		$numrowpre += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpre, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpre, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpre)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpre, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpre, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpre, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpre, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpre.':G'.$numrowpre)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpre++;
		}

			$totpre = $numrowpre;
			$totpre += 0;
			$sumpre = $numrowpre-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpre, "Subtotal Prepaid Rent ");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpre, '=SUM(E'.$numpre3.':E'.$sumpre.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpre, '=SUM(F'.$numpre3.':F'.$sumpre.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpre, '=SUM(G'.$numpre3.':G'.$sumpre.')');
			$excel->getActiveSheet()->getStyle('D'.$totpre.':G'.$totpre)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpre.':G'.$totpre)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totpre1 = $totpre;
			$totpre1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpre1, "SUBTOTAL PREPAID RENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpre1, '=SUM(E'.$numpre.':E'.$totpre.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpre1, '=SUM(F'.$numpre.':F'.$totpre.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpre1, '=SUM(G'.$numpre.':G'.$totpre.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totpre1.':G'.$totpre1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpre1.':G'.$totpre1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpreo = $totpre1;
		$numpreo += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpreo, "11513000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpreo, "PREPAID OTHERS");
		$excel->getActiveSheet()->getStyle('C'.$numpreo)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpreo)->applyFromArray($style_sub);

		$category = "preo";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpreo = $numpreo;
		$numrowpreo += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpreo, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpreo, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpreo)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpreo, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpreo, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpreo, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpreo, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpreo.':G'.$numrowpreo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpreo++;
		}

			$totpre = $numrowpreo;
			$totpre += 0;
			$sumpreo = $numpreo+=1;
			$sumpreo1 = $numrowpreo-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpre, "SUBTOTAL PREPAID OTHERS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpre, '=SUM(E'.$sumpreo.':E'.$sumpreo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpre, '=SUM(F'.$sumpreo.':F'.$sumpreo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpre, '=SUM(G'.$sumpreo.':G'.$sumpreo1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpre.':G'.$totpre)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpre.':G'.$totpre)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpret = $totpre;
		$numpret += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpret, "11512000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpret, "PREPAID TAX");
		$excel->getActiveSheet()->getStyle('C'.$numpret)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpret)->applyFromArray($style_sub);

		$category = "pret";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpret = $numpret;
		$numrowpret += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpret, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpret, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpret)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpret, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpret, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpret, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpret, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpret.':G'.$numrowpret)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpret++;
		}

			$totpret = $numrowpret;
			$totpret += 0;
			$sumpret = $numpret+=1;
			$sumpret1 = $numrowpret-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpret, "SUBTOTAL PREPAID TAXES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpret, '=SUM(E'.$sumpret.':E'.$sumpret1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpret, '=SUM(F'.$sumpret.':F'.$sumpret1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpret, '=SUM(G'.$sumpret.':G'.$sumpret1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpret.':G'.$totpret)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpret.':G'.$totpret)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totpret1 = $totpret;
			$totpret1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpret1, "SUBTOTAL PREPAYMENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpret1, '=E'.$totpre.'+E'.$totpre1.'+E'.$totpret);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpret1, '=F'.$totpre.'+F'.$totpre1.'+F'.$totpret);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpret1, '=G'.$totpre.'+G'.$totpre1.'+G'.$totpret);
			$excel->getActiveSheet()->getStyle('D'.$totpret1.':G'.$totpret1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpret1.':G'.$totpret1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numadv = $totpret1;
		$numadv += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadv, "11520000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadv, "ADVANCES");
		$excel->getActiveSheet()->getStyle('C'.$numadv)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadv)->applyFromArray($style_sub);

		$numadv1 = $numadv;
		$numadv1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadv1, "11521000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadv1, "ADVANCES TO VENDORS");
		$excel->getActiveSheet()->getStyle('C'.$numadv1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadv1)->applyFromArray($style_sub);

		$category = "adv";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowadv = $numadv1;
		$numrowadv += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowadv, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowadv, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowadv)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowadv, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowadv, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowadv, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowadv, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowadv.':G'.$numrowadv)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowadv++;
		}

			$totadv = $numrowadv;
			$totadv += 0;
			$sumadv = $numrowadv-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadv, "Subtotal Advances to Vendors");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadv, '=SUM(E'.$numadv.':E'.$sumadv.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadv, '=SUM(F'.$numadv.':F'.$sumadv.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadv, '=SUM(G'.$numadv.':G'.$sumadv.')');
			$excel->getActiveSheet()->getStyle('D'.$totadv.':G'.$totadv)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadv.':G'.$totadv)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totadv1 = $totadv;
			$totadv1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadv1, "SUBTOTAL ADVANCES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadv1, '=SUM(E'.$numadv.':E'.$totadv.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadv1, '=SUM(F'.$numadv.':F'.$totadv.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadv1, '=SUM(G'.$numadv.':G'.$totadv.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totadv1.':G'.$totadv1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadv1.':G'.$totadv1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totadv2 = $totadv1;
			$totadv2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadv2, "SUBTOTAL PREPAYMENT & ADVANCES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadv2, '=E'.$totadv1.'+E'.$totpret1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadv2, '=F'.$totadv1.'+F'.$totpret1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadv2, '=G'.$totadv1.'+G'.$totpret1);
			$excel->getActiveSheet()->getStyle('D'.$totadv2.':G'.$totadv2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadv2.':G'.$totadv2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcla = $totadv2;
		$numcla += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcla, "11600000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcla, "OTHER CURRENT ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numcla)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcla)->applyFromArray($style_sub);

		$numcla1 = $numcla;
		$numcla1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcla1, "11610000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcla1, "CLAIM FOR TAX REFUND");
		$excel->getActiveSheet()->getStyle('C'.$numcla1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcla1)->applyFromArray($style_sub);

		$category = "cla";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcla = $numcla1;
		$numrowcla += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcla, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcla, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcla)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcla, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcla, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcla, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcla, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcla.':G'.$numrowcla)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcla++;
		}

			$totcla = $numrowcla;
			$totcla += 0;
			$sumcla = $numrowcla-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcla, "SUBTOTAL CLAIM FOR TAX REFUND");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcla, '=SUM(E'.$numcla.':E'.$sumcla.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcla, '=SUM(F'.$numcla.':F'.$sumcla.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcla, '=SUM(G'.$numcla.':G'.$sumcla.')');
			$excel->getActiveSheet()->getStyle('D'.$totcla.':G'.$totcla)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcla.':G'.$totcla)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numsec = $totcla;
		$numsec += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsec, "11630000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numsec, "OTHER ASSETS - CURRENT");
		$excel->getActiveSheet()->getStyle('C'.$numsec)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numsec)->applyFromArray($style_sub);

		$numsec1 = $numsec;
		$numsec1 += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsec1, "12410000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numsec1, "SECURITY DEPOSIT");
		$excel->getActiveSheet()->getStyle('C'.$numsec1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numsec1)->applyFromArray($style_sub);

		$numsec2 = $numsec1;
		$numsec2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsec2, "12411000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numsec2, "SECURITY DEPOSIT - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numsec2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numsec2)->applyFromArray($style_sub);

		$category = "sec";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowsec = $numsec2;
		$numrowsec += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowsec, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowsec, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowsec)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowsec, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowsec, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowsec, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowsec, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowsec.':G'.$numrowsec)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowsec++;
		}

			$totsec = $numrowsec;
			$totsec += 0;
			$sumsec = $numrowsec-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsec, "Subtotal Security Deposit - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsec, '=SUM(E'.$numsec1.':E'.$sumsec.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsec, '=SUM(F'.$numsec1.':F'.$sumsec.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsec, '=SUM(G'.$numsec1.':G'.$sumsec.')');
			$excel->getActiveSheet()->getStyle('D'.$totsec.':G'.$totsec)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsec.':G'.$totsec)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totsec1 = $totsec;
			$totsec1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsec1, "SUBTOTAL OTHER ASSETS - CURRENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsec1, '=SUM(E'.$numsec.':E'.$totsec.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsec1, '=SUM(F'.$numsec.':F'.$totsec.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsec1, '=SUM(G'.$numsec.':G'.$totsec.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totsec1.':G'.$totsec1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsec1.':G'.$totsec1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totsec2 = $totsec1;
			$totsec2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsec2, "SUBTOTAL OTHER CURRENT ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsec2, '=E'.$totsec1.'+E'.$totcla);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsec2, '=F'.$totsec1.'+F'.$totcla);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsec2, '=G'.$totsec1.'+G'.$totcla);
			$excel->getActiveSheet()->getStyle('D'.$totsec2.':G'.$totsec2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsec2.':G'.$totsec2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totsec3 = $totsec2;
			$totsec3 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsec3, "SUBTOTAL CURRENT ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsec3, '=E'.$totsec2.'+E'.$totadv2.'+E'.$totall1.'+E'.$totacci1.'+E'.$totcol2.'+E'.$totclr1.'+E'.$totflt2);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsec3, '=F'.$totsec2.'+F'.$totadv2.'+F'.$totall1.'+F'.$totacci1.'+F'.$totcol2.'+F'.$totclr1.'+F'.$totflt2);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsec3, '=G'.$totsec2.'+G'.$totadv2.'+G'.$totall1.'+G'.$totacci1.'+G'.$totcol2.'+G'.$totclr1.'+G'.$totflt2);
			$excel->getActiveSheet()->getStyle('D'.$totsec3.':G'.$totsec3)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsec3.':G'.$totsec3)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcip = $totsec3;
		$numcip += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcip, "12000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcip, "NON CURRENT ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numcip)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcip)->applyFromArray($style_sub);

		$numcip1 = $numcip;
		$numcip1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcip1, "12100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcip1, "FIXED ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numcip1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcip1)->applyFromArray($style_sub);

		$numcip2 = $numcip1;
		$numcip2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcip2, "12110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcip2, "FIXED ASSET DIRECT OWNERSHIP (DO)");
		$excel->getActiveSheet()->getStyle('C'.$numcip2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcip2)->applyFromArray($style_sub);

		$numcip3 = $numcip2;
		$numcip3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcip3, "12111000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcip3, "DO CONSTRUCTION IN PROGRES");
		$excel->getActiveSheet()->getStyle('C'.$numcip3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcip3)->applyFromArray($style_sub);

		$category = "cip";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcip = $numcip3;
		$numrowcip += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcip, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcip, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcip)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcip, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcip, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcip, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcip, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcip.':G'.$numrowcip)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcip++;
		}

			$totcip = $numrowcip;
			$totcip += 0;
			$sumcip = $numrowcip-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcip, "Subtotal DO Construction in Progress - FA");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcip, '=SUM(E'.$numcip.':E'.$sumcip.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcip, '=SUM(F'.$numcip.':F'.$sumcip.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcip, '=SUM(G'.$numcip.':G'.$sumcip.')');
			$excel->getActiveSheet()->getStyle('D'.$totcip.':G'.$totcip)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcip.':G'.$totcip)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcost = $totcip;
		$numcost += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcost, "12112000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcost, "DO COST");
		$excel->getActiveSheet()->getStyle('C'.$numcost)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcost)->applyFromArray($style_sub);

		$category = "cost";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcost = $numcost;
		$numrowcost += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcost, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcost, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcost)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcost, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcost, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcost, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcost, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcost.':G'.$numrowcost)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcost++;
		}

			$totcost = $numrowcost;
			$totcost += 0;
			$sumcost = $numrowcost-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcost, "Subtotal DO Cost - FA");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcost, '=SUM(E'.$numcost.':E'.$sumcost.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcost, '=SUM(F'.$numcost.':F'.$sumcost.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcost, '=SUM(G'.$numcost.':G'.$sumcost.')');
			$excel->getActiveSheet()->getStyle('D'.$totcost.':G'.$totcost)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcost.':G'.$totcost)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaccum = $totcost;
		$numaccum += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccum, "12113000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccum, "DO ACCUMULATED DEPRECIATION");
		$excel->getActiveSheet()->getStyle('C'.$numaccum)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccum)->applyFromArray($style_sub);

		$category = "accum";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaccum = $numaccum;
		$numrowaccum += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaccum, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaccum, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaccum)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaccum, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaccum, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaccum, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaccum, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaccum.':G'.$numrowaccum)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaccum++;
		}

			$totaccum = $numrowaccum;
			$totaccum += 0;
			$sumaccum = $numrowaccum-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccum, "Subtotal DO Accumulated Depreciation - FA");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccum, '=SUM(E'.$numaccum.':E'.$sumaccum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccum, '=SUM(F'.$numaccum.':F'.$sumaccum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccum, '=SUM(G'.$numaccum.':G'.$sumaccum.')');
			$excel->getActiveSheet()->getStyle('D'.$totaccum.':G'.$totaccum)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccum.':G'.$totaccum)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaccum1 = $totaccum;
			$totaccum1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccum1, "SUBTOTAL FIXED ASSET DIRECT OWNERSHIP");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccum1, '=SUM(E'.$numcip.':E'.$totaccum.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccum1, '=SUM(F'.$numcip.':F'.$totaccum.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccum1, '=SUM(G'.$numcip.':G'.$totaccum.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totaccum1.':G'.$totaccum1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccum1.':G'.$totaccum1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcipla = $totaccum1;
		$numcipla += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcipla, "12120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcipla, "LEASED ASSETS  (LA)");
		$excel->getActiveSheet()->getStyle('C'.$numcipla)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcipla)->applyFromArray($style_sub);

		$numcipla1 = $numcipla;
		$numcipla1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcipla1, "12121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcipla1, "LLA CONSTRUCTION IN PROGRES");
		$excel->getActiveSheet()->getStyle('C'.$numcipla1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcipla1)->applyFromArray($style_sub);

		$category = "cipla";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcipla = $numcipla1;
		$numrowcipla += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcipla, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcipla, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcipla)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcipla, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcipla, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcipla, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcipla, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcipla.':G'.$numrowcipla)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcipla++;
		}

			$totcipla = $numrowcipla;
			$totcipla += 0;
			$sumcipla = $numrowcipla-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcipla, "Subtotal Leased Assets Construction in Progress");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcipla, '=SUM(E'.$numcipla.':E'.$sumcipla.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcipla, '=SUM(F'.$numcipla.':F'.$sumcipla.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcipla, '=SUM(G'.$numcipla.':G'.$sumcipla.')');
			$excel->getActiveSheet()->getStyle('D'.$totcipla.':G'.$totcipla)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcipla.':G'.$totcipla)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcostla = $totcipla;
		$numcostla += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcostla, "12122000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcostla, "LA COST");
		$excel->getActiveSheet()->getStyle('C'.$numcostla)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcostla)->applyFromArray($style_sub);

		$category = "costla";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcostla = $numcostla;
		$numrowcostla += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcostla, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcostla, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcostla)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcostla, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcostla, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcostla, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcostla, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcostla.':G'.$numrowcostla)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcostla++;
		}

			$totcostla = $numrowcostla;
			$totcostla += 0;
			$sumcostla = $numrowcostla-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcostla, "Subtotal Leased Assets Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcostla, '=SUM(E'.$numcostla.':E'.$sumcostla.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcostla, '=SUM(F'.$numcostla.':F'.$sumcostla.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcostla, '=SUM(G'.$numcostla.':G'.$sumcostla.')');
			$excel->getActiveSheet()->getStyle('D'.$totcostla.':G'.$totcostla)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcostla.':G'.$totcostla)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaccde = $totcostla;
		$numaccde += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccde, "12123000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccde, "LA ACCUMULATED DEPRECIATION");
		$excel->getActiveSheet()->getStyle('C'.$numaccde)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccde)->applyFromArray($style_sub);

		$category = "accde";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaccde = $numaccde;
		$numrowaccde += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaccde, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaccde, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaccde)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaccde, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaccde, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaccde, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaccde, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaccde.':G'.$numrowaccde)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaccde++;
		}

			$totaccde = $numrowaccde;
			$totaccde += 0;
			$sumaccde = $numrowaccde-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccde, "Subtotal Leased Assets Accumulated Depreciation");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccde, '=SUM(E'.$numaccde.':E'.$sumaccde.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccde, '=SUM(F'.$numaccde.':F'.$sumaccde.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccde, '=SUM(G'.$numaccde.':G'.$sumaccde.')');
			$excel->getActiveSheet()->getStyle('D'.$totaccde.':G'.$totaccde)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccde.':G'.$totaccde)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaccde1 = $totaccde;
			$totaccde1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccde1, "SUBTOTAL LEASED ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccde1, '=SUM(E'.$numcipla.':E'.$totaccde.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccde1, '=SUM(F'.$numcipla.':F'.$totaccde.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccde1, '=SUM(G'.$numcipla.':G'.$totaccde.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totaccde1.':G'.$totaccde1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccde1.':G'.$totaccde1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaccde2 = $totaccde1;
			$totaccde2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccde2, "SUBTOTAL FIXED ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccde2, '=E'.$totaccde1.'+E'.$totaccum1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccde2, '=F'.$totaccde1.'+F'.$totaccum1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccde2, '=G'.$totaccde1.'+G'.$totaccum1);
			$excel->getActiveSheet()->getStyle('D'.$totaccde2.':G'.$totaccde2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccde2.':G'.$totaccde2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcipso = $totaccde2;
		$numcipso += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcipso, "12200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcipso, "INTANGIBLE ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numcipso)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcipso)->applyFromArray($style_sub);

		$numcipso1 = $numcipso;
		$numcipso1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcipso1, "12210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcipso1, "DO CONSTRUCTION IN PROGRES");
		$excel->getActiveSheet()->getStyle('C'.$numcipso1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcipso1)->applyFromArray($style_sub);

		$category = "cipso";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcipso = $numcipso1;
		$numrowcipso += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcipso, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcipso, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcipso)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcipso, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcipso, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcipso, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcipso, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcipso.':G'.$numrowcipso)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcipso++;
		}

			$totcipso = $numrowcipso;
			$totcipso += 0;
			$sumcipso = $numrowcipso-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcipso, "Subtotal DO Construction in Progress - IA");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcipso, '=SUM(E'.$numcipso.':E'.$sumcipso.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcipso, '=SUM(F'.$numcipso.':F'.$sumcipso.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcipso, '=SUM(G'.$numcipso.':G'.$sumcipso.')');
			$excel->getActiveSheet()->getStyle('D'.$totcipso.':G'.$totcipso)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcipso.':G'.$totcipso)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcostso = $totcipso;
		$numcostso += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcostso, "12220000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcostso, "DO COST");
		$excel->getActiveSheet()->getStyle('C'.$numcostso)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcostso)->applyFromArray($style_sub);

		$category = "costso";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcostso = $numcostso;
		$numrowcostso += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcostso, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcostso, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcostso)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcostso, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcostso, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcostso, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcostso, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcostso.':G'.$numrowcostso)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcostso++;
		}

			$totcostso = $numrowcostso;
			$totcostso += 0;
			$sumcostso = $numrowcostso-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcostso, "Subtotal DO Cost - IA");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcostso, '=SUM(E'.$numcostso.':E'.$sumcostso.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcostso, '=SUM(F'.$numcostso.':F'.$sumcostso.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcostso, '=SUM(G'.$numcostso.':G'.$sumcostso.')');
			$excel->getActiveSheet()->getStyle('D'.$totcostso.':G'.$totcostso)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcostso.':G'.$totcostso)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaccso = $totcostso;
		$numaccso += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccso, "12230000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccso, "DO ACCUMULATED AMORTIZATION");
		$excel->getActiveSheet()->getStyle('C'.$numaccso)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccso)->applyFromArray($style_sub);

		$category = "accso";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaccso = $numaccso;
		$numrowaccso += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaccso, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaccso, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaccso)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaccso, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaccso, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaccso, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaccso, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaccso.':G'.$numrowaccso)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaccso++;
		}

			$totaccso = $numrowaccso;
			$totaccso += 0;
			$sumaccso = $numrowaccso-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccso, "Subtotal DO Accumulated Amortization - IA");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccso, '=SUM(E'.$numaccso.':E'.$sumaccso.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccso, '=SUM(F'.$numaccso.':F'.$sumaccso.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccso, '=SUM(G'.$numaccso.':G'.$sumaccso.')');
			$excel->getActiveSheet()->getStyle('D'.$totaccso.':G'.$totaccso)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccso.':G'.$totaccso)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaccso1 = $totaccso;
			$totaccso1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccso1, "SUBTOTAL INTANGIBLE ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccso1, '=SUM(E'.$numcipso.':E'.$totaccso.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccso1, '=SUM(F'.$numcipso.':F'.$totaccso.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccso1, '=SUM(G'.$numcipso.':G'.$totaccso.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totaccso1.':G'.$totaccso1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccso1.':G'.$totaccso1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numprepaid = $totaccso1;
		$numprepaid += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numprepaid, "12300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numprepaid, "PREPAYMENT & ADVANCES NON - CURRENT ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numprepaid)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numprepaid)->applyFromArray($style_sub);

		$numprepaid1 = $numprepaid;
		$numprepaid1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numprepaid1, "12310000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numprepaid1, "PREPAYMENT NON - CURRENT ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numprepaid1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numprepaid1)->applyFromArray($style_sub);

		$numprepaid2 = $numprepaid1;
		$numprepaid2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numprepaid2, "12311000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numprepaid2, "PREPAID RENT");
		$excel->getActiveSheet()->getStyle('C'.$numprepaid2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numprepaid2)->applyFromArray($style_sub);

		$numprepaid3 = $numprepaid2;
		$numprepaid3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numprepaid3, "12311100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numprepaid3, "PREPAID RENT - OFFICE BUILDING");
		$excel->getActiveSheet()->getStyle('C'.$numprepaid3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numprepaid3)->applyFromArray($style_sub);

		$category = "prepaid";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowprepaid = $numprepaid3;
		$numrowprepaid += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowprepaid, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowprepaid, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowprepaid)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowprepaid, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowprepaid, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowprepaid, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowprepaid, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowprepaid.':G'.$numrowprepaid)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowprepaid++;
		}

			$totprepaid = $numrowprepaid;
			$totprepaid += 0;
			$sumprepaid = $numrowprepaid-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totprepaid, "Subtotal Prepaid Rent - Office Building");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totprepaid, '=SUM(E'.$numprepaid.':E'.$sumprepaid.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totprepaid, '=SUM(F'.$numprepaid.':F'.$sumprepaid.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totprepaid, '=SUM(G'.$numprepaid.':G'.$sumprepaid.')');
			$excel->getActiveSheet()->getStyle('D'.$totprepaid.':G'.$totprepaid)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totprepaid.':G'.$totprepaid)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totprepaid1 = $totprepaid;
			$totprepaid1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totprepaid1, "SUBTOTAL PREPAID RENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totprepaid1, '=SUM(E'.$numprepaid.':E'.$totprepaid.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totprepaid1, '=SUM(F'.$numprepaid.':F'.$totprepaid.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totprepaid1, '=SUM(G'.$numprepaid.':G'.$totprepaid.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totprepaid1.':G'.$totprepaid1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totprepaid1.':G'.$totprepaid1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totprepaid2 = $totprepaid1;
			$totprepaid2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totprepaid2, "SUBTOTAL PREPAYMENT & ADVANCES NON - CURRENT ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totprepaid2, '=E'.$totprepaid1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totprepaid2, '=F'.$totprepaid1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totprepaid2, '=G'.$totprepaid1);
			$excel->getActiveSheet()->getStyle('D'.$totprepaid2.':G'.$totprepaid2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totprepaid2.':G'.$totprepaid2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numoth = $totprepaid2;
		$numoth += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoth, "12400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoth, "OTHER NON CURRENT ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numoth)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoth)->applyFromArray($style_sub);

		$category = "othernon";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowoth = $numoth;
		$numrowoth += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowoth, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoth, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowoth)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowoth, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoth, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowoth, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowoth, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowoth.':G'.$numrowoth)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowoth++;
		}

			$tototh = $numrowoth;
			$tototh += 1;
			$sumtoth = $numoth+=1;
			$sumtoth1 = $numrowoth-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototh, "Subtotal Escrow Account - Non Current");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototh, '=SUM(E'.$sumtoth.':E'.$sumtoth1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototh, '=SUM(F'.$sumtoth.':F'.$sumtoth1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototh, '=SUM(G'.$sumtoth.':G'.$sumtoth1.')');
			$excel->getActiveSheet()->getStyle('D'.$tototh.':G'.$tototh)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototh.':G'.$tototh)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tototh1 = $tototh;
			$tototh1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototh1, "SUBTOTAL OTHER NON CURRENT ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototh1, '=SUM(E'.$numoth.':E'.$tototh.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototh1, '=SUM(F'.$numoth.':F'.$tototh.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototh1, '=SUM(G'.$numoth.':G'.$tototh.')/2');
			$excel->getActiveSheet()->getStyle('D'.$tototh1.':G'.$tototh1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototh1.':G'.$tototh1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdef = $tototh1;
		$numdef += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdef, "11620000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdef, "DEFERRED TAX ASSET");
		$excel->getActiveSheet()->getStyle('C'.$numdef)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdef)->applyFromArray($style_sub);

		$category = "def";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdef = $numdef;
		$numrowdef += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdef, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdef, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdef)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdef, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdef, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdef, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdef, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdef.':G'.$numrowdef)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdef++;
		}

			$totdef = $numrowdef;
			$totdef += 0;
			$sumdef = $numrowdef-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdef, "SUBTOTAL DEFERRED TAX ASSET - CURRENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdef, '=SUM(E'.$numdef.':E'.$sumdef.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdef, '=SUM(F'.$numdef.':F'.$sumdef.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdef, '=SUM(G'.$numdef.':G'.$sumdef.')');
			$excel->getActiveSheet()->getStyle('D'.$totdef.':G'.$totdef)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdef.':G'.$totdef)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totdef1 = $totdef;
			$totdef1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdef1, "SUBTOTAL NON CURRENT ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdef1, '=E'.$totprepaid2.'+E'.$totaccso1.'+E'.$totaccde2.'+E'.$tototh1.'+E'.$totdef);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdef1, '=F'.$totprepaid2.'+F'.$totaccso1.'+F'.$totaccde2.'+F'.$tototh1.'+F'.$totdef);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdef1, '=G'.$totprepaid2.'+G'.$totaccso1.'+G'.$totaccde2.'+G'.$tototh1.'+G'.$totdef);
			$excel->getActiveSheet()->getStyle('D'.$totdef1.':G'.$totdef1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdef1.':G'.$totdef1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtot = $totdef1;
		$numtot += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtot, "TOTAL ASSETS");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$numtot, '=E'.$totdef1.'+E'.$totsec3);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$numtot, '=F'.$totdef1.'+F'.$totsec3);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$numtot, '=G'.$totdef1.'+G'.$totsec3);
		$excel->getActiveSheet()->getStyle('D'.$numtot)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('E'.$numtot.':G'.$numtot)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$numtot.':G'.$numtot)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numap = $numtot;
		$numap += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numap, "20000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numap, "LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numap)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numap)->applyFromArray($style_sub);

		$numap1 = $numap;
		$numap1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numap1, "21000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numap1, "CURRENT LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numap1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numap1)->applyFromArray($style_sub);

		$numap2 = $numap1;
		$numap2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numap2, "21100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numap2, "ACCOUNT PAYABLES");
		$excel->getActiveSheet()->getStyle('C'.$numap2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numap2)->applyFromArray($style_sub);

		$numap3 = $numap2;
		$numap3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numap3, "21110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numap3, "AP FOR CAPITAL EXPENDITURES");
		$excel->getActiveSheet()->getStyle('C'.$numap3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numap3)->applyFromArray($style_sub);

		$category = "ap";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowap = $numap3;
		$numrowap += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowap, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowap, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowap)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowap, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowap, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowap, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowap, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowap.':G'.$numrowap)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowap++;
		}

			$totap = $numrowap;
			$totap += 0;
			$sumap = $numrowap-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totap, "SUBTOTAL AP FOR CAPITAL EXPENDITURES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totap, '=SUM(E'.$numap.':E'.$sumap.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totap, '=SUM(F'.$numap.':F'.$sumap.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totap, '=SUM(G'.$numap.':G'.$sumap.')');
			$excel->getActiveSheet()->getStyle('D'.$totap.':G'.$totap)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totap.':G'.$totap)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numapo = $totap;
		$numapo += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numapo, "21120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numapo, "AP FOR OPERATING EXPENDITURES");
		$excel->getActiveSheet()->getStyle('C'.$numapo)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numapo)->applyFromArray($style_sub);

		$numapo1 = $numapo;
		$numapo1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numapo1, "21121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numapo1, "AP Supplier Opex - Related Party");
		$excel->getActiveSheet()->getStyle('C'.$numapo1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numapo1)->applyFromArray($style_sub);

		$category = "apo";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowapo = $numapo1;
		$numrowapo += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowapo, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowapo, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowapo)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowapo, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowapo, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowapo, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowapo, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowapo.':G'.$numrowapo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowapo++;
		}

			$totapo = $numrowapo;
			$totapo += 0;
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$totapo, "21122000");
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totapo, "SAP Supplier Opex - Third Party");
			$excel->getActiveSheet()->getStyle('C'.$totapo)->applyFromArray($style_nature);
			$excel->getActiveSheet()->getStyle('D'.$totapo)->applyFromArray($style_sub);

		$category = "apoo";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowapoo = $totapo;
		$numrowapoo += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowapoo, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowapoo, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowapoo)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowapoo, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowapoo, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowapoo, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowapoo, $row['AMOUNT2']);

			$excel->getActiveSheet()->getStyle('E'.$numrowapoo.':G'.$numrowapoo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowapoo++;
		}

			$totapoo = $numrowapoo;
			$totapoo += 0;
			$sumapoo = $numrowapoo-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totapoo, "Subtotal AP Supplier Opex");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totapoo, '=SUM(E'.$numapo.':E'.$sumapoo.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totapoo, '=SUM(F'.$numapo.':F'.$sumapoo.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totapoo, '=SUM(G'.$numapo.':G'.$sumapoo.')');
			$excel->getActiveSheet()->getStyle('D'.$totapoo.':G'.$totapoo)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totapoo.':G'.$totapoo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totapoo1 = $totapoo;
			$totapoo1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totapoo1, "SUBTOTAL AP FOR OPERATING EXPENDITURES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totapoo1, '=E'.$totapoo);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totapoo1, '=F'.$totapoo);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totapoo1, '=G'.$totapoo);
			$excel->getActiveSheet()->getStyle('D'.$totapoo1.':G'.$totapoo1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totapoo1.':G'.$totapoo1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numapl = $totapoo1;
		$numapl += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numapl, "21124000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numapl, "AP OTHERS - CURRENT");
		$excel->getActiveSheet()->getStyle('C'.$numapl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numapl)->applyFromArray($style_sub);

		$category = "apl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowapl = $numapl;
		$numrowapl += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowapl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowapl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowapl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowapl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowapl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowapl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowapl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowapl.':G'.$numrowapl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowapl++;
		}

			$totaapl = $numrowapl;
			$totaapl += 0;
			$sumapl = $numrowapl-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaapl, "SUBTOTAL AP OTHERS - CURRENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaapl, '=SUM(E'.$numapl.':E'.$sumapl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaapl, '=SUM(F'.$numapl.':F'.$sumapl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaapl, '=SUM(G'.$numapl.':G'.$sumapl.')');
			$excel->getActiveSheet()->getStyle('D'.$totaapl.':G'.$totaapl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaapl.':G'.$totaapl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaapl1 = $totaapl;
			$totaapl1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaapl1, "SUBTOTAL AP FOR OPERATING EXPENDITURES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaapl1, '=E'.$totaapl.'+E'.$totapoo1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaapl1, '=F'.$totaapl.'+F'.$totapoo1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaapl1, '=G'.$totaapl.'+G'.$totapoo1);
			$excel->getActiveSheet()->getStyle('D'.$totaapl1.':G'.$totaapl1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaapl1.':G'.$totaapl1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaapl2 = $totaapl1;
			$totaapl2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaapl2, "SUBTOTAL ACCOUNT PAYABLES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaapl2, '=E'.$totaapl1.'+E'.$totap);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaapl2, '=F'.$totaapl1.'+F'.$totap);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaapl2, '=G'.$totaapl1.'+G'.$totap);
			$excel->getActiveSheet()->getStyle('D'.$totaapl2.':G'.$totaapl2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaapl2.':G'.$totaapl2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaccl = $totaapl2;
		$numaccl += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccl, "21200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccl, "ACCRUED LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numaccl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccl)->applyFromArray($style_sub);

		$numaccl1 = $numaccl;
		$numaccl1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccl1, "21210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccl1, "ACCRUED LIABILITIES FOR CAPITAL EXPENDITURES");
		$excel->getActiveSheet()->getStyle('C'.$numaccl1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccl1)->applyFromArray($style_sub);

		$numaccl2 = $numaccl1;
		$numaccl2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccl2, "21211000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccl2, "ACCRUED LIABILITIES CAPEX ");
		$excel->getActiveSheet()->getStyle('C'.$numaccl2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccl2)->applyFromArray($style_sub);

		$category = "accl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaccl = $numaccl2;
		$numrowaccl += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaccl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaccl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaccl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaccl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaccl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaccl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaccl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaccl.':G'.$numrowaccl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaccl++;
		}

			$totaccl = $numrowaccl;
			$totaccl += 0;
			$sumaccl = $numrowaccl-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccl, "Subtotal Accrued Liabilities Capex - RP");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccl, '=SUM(E'.$numaccl.':E'.$sumaccl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccl, '=SUM(F'.$numaccl.':F'.$sumaccl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccl, '=SUM(G'.$numaccl.':G'.$sumaccl.')');
			$excel->getActiveSheet()->getStyle('D'.$totaccl.':G'.$totaccl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccl.':G'.$totaccl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaccl1 = $totaccl;
			$totaccl1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccl1, "SUBTOTAL ACCRUED LIABILITIES FOR CAPITAL EXPENDITURES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccl1, '=SUM(E'.$numaccl.':E'.$totaccl.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccl1, '=SUM(F'.$numaccl.':F'.$totaccl.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccl1, '=SUM(G'.$numaccl.':G'.$totaccl.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totaccl1.':G'.$totaccl1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccl1.':G'.$totaccl1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaccls = $totaccl1;
		$numaccls += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccls, "21221000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccls, "ACCRUED LIABILITIES FOR OPERATING EXPENDITURE");
		$excel->getActiveSheet()->getStyle('C'.$numaccls)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccls)->applyFromArray($style_sub);

		$numaccls1 = $numaccls;
		$numaccls1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaccls1, "21221100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaccls1, "ACCRUED LIABILITIES SUPPLIER OPEX - RELATED PARTY");
		$excel->getActiveSheet()->getStyle('C'.$numaccls1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaccls1)->applyFromArray($style_sub);

		$category = "accls";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaccls = $numaccls1;
		$numrowaccls += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaccls, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaccls, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaccls)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaccls, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaccls, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaccls, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaccls, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaccls.':G'.$numrowaccls)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaccls++;
		}

			$totaccls = $numrowaccls;
			$totaccls += 0;
			$sumaccls = $numrowaccls-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaccls, "Subtotal Accrued Liabilities Supplier Opex - RP");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaccls, '=SUM(E'.$numaccls.':E'.$sumaccls.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaccls, '=SUM(F'.$numaccls.':F'.$sumaccls.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaccls, '=SUM(G'.$numaccls.':G'.$sumaccls.')');
			$excel->getActiveSheet()->getStyle('D'.$totaccls.':G'.$totaccls)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaccls.':G'.$totaccls)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numacs = $totaccls;
		$numacs += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacs, "21221110");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacs, "ACCRUED LIABILITIES SUPPLIER OPEX - THIRD PARTY");
		$excel->getActiveSheet()->getStyle('C'.$numacs)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacs)->applyFromArray($style_sub);

		$category = "acs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowacs = $numacs;
		$numrowacs += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowacs, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowacs, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowacs)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowacs, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowacs, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowacs, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowacs, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowacs.':G'.$numrowacs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowacs++;
		}

			$totacs = $numrowacs;
			$totacs += 0;
			$sumacs = $numrowacs-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacs, "Subtotal Accrued Liabilities Supplier Opex - 3P");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacs, '=SUM(E'.$numacs.':E'.$sumacs.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacs, '=SUM(F'.$numacs.':F'.$sumacs.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacs, '=SUM(G'.$numacs.':G'.$sumacs.')');
			$excel->getActiveSheet()->getStyle('D'.$totacs.':G'.$totacs)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacs.':G'.$totacs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totacs1 = $totacs;
			$totacs1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacs1, "SUBTOTAL ACCRUED LIABILITIES TO SUPPLIER");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacs1, '=SUM(E'.$numaccls.':E'.$totacs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacs1, '=SUM(F'.$numaccls.':F'.$totacs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacs1, '=SUM(G'.$numaccls.':G'.$totacs.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totacs1.':G'.$totacs1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacs1.':G'.$totacs1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totacs2 = $totacs1;
			$totacs2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacs2, "SUBTOTAL ACCRUED LIABILITIES TO SUPPLIER");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacs2, '=E'.$totacs1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacs2, '=F'.$totacs1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacs2, '=G'.$totacs1);
			$excel->getActiveSheet()->getStyle('D'.$totacs2.':G'.$totacs2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacs2.':G'.$totacs2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numint = $totacs2;
		$numint += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numint, "21223000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numint, "ACCRUED LIABILITIES OTHERS");
		$excel->getActiveSheet()->getStyle('C'.$numint)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numint)->applyFromArray($style_sub);

		$numint1 = $numint;
		$numint1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numint1, "21223100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numint1, "ACCRUED INTEREST");
		$excel->getActiveSheet()->getStyle('C'.$numint1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numint1)->applyFromArray($style_sub);

		$category = "int";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowint = $numint1;
		$numrowint += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowint, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowint, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowint)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowint, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowint, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowint, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowint, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowint.':G'.$numrowint)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowint++;
		}

			$totint = $numrowint;
			$totint += 0;
			$sumint = $numrowint-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totint, "Subtotal Accrued Interest");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totint, '=SUM(E'.$numint.':E'.$sumint.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totint, '=SUM(F'.$numint.':F'.$sumint.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totint, '=SUM(G'.$numint.':G'.$sumint.')');
			$excel->getActiveSheet()->getStyle('D'.$totint.':G'.$totint)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totint.':G'.$totint)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numlbl = $totint;
		$numlbl += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numlbl, "21223200");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numlbl, "ACCR. LIABILITIES OTHERS");
		$excel->getActiveSheet()->getStyle('C'.$numlbl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numlbl)->applyFromArray($style_sub);

		$category = "lbl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowlbl = $numlbl;
		$numrowlbl += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowlbl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowlbl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowlbl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowlbl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowlbl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowlbl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowlbl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowlbl.':G'.$numrowlbl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowlbl++;
		}

			$totlbl = $numrowlbl;
			$totlbl += 0;
			$sumlbl = $numrowlbl-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totlbl, "Subtotal Accrued Liabilities Others");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totlbl, '=SUM(E'.$numlbl.':E'.$sumlbl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totlbl, '=SUM(F'.$numlbl.':F'.$sumlbl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totlbl, '=SUM(G'.$numlbl.':G'.$sumlbl.')');
			$excel->getActiveSheet()->getStyle('D'.$totlbl.':G'.$totlbl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totlbl.':G'.$totlbl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totlbl1 = $totlbl;
			$totlbl1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totlbl1, "SUBTOTAL ACCRUED LIABILITIES OTHERS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totlbl1, '=SUM(E'.$numint.':E'.$totlbl.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totlbl1, '=SUM(F'.$numint.':F'.$totlbl.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totlbl1, '=SUM(G'.$numint.':G'.$totlbl.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totlbl1.':G'.$totlbl1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totlbl1.':G'.$totlbl1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totlbl2 = $totlbl1;
			$totlbl2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totlbl2, "SUBTOTAL ACCRUED LIABILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totlbl2, '=E'.$totlbl1.'+E'.$totacs2.'+E'.$totaccl1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totlbl2, '=F'.$totlbl1.'+F'.$totacs2.'+F'.$totaccl1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totlbl2, '=G'.$totlbl1.'+G'.$totacs2.'+G'.$totaccl1);
			$excel->getActiveSheet()->getStyle('D'.$totlbl2.':G'.$totlbl2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totlbl2.':G'.$totlbl2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numvat = $totlbl2;
		$numvat += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numvat, "21300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numvat, "TAX PAYABLES");
		$excel->getActiveSheet()->getStyle('C'.$numvat)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numvat)->applyFromArray($style_sub);

		$numvat1 = $numvat;
		$numvat1 += 1;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numvat1, "21310000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numvat1, "VAT PAYABLES");
		$excel->getActiveSheet()->getStyle('C'.$numvat1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numvat1)->applyFromArray($style_sub);

		$category = "vat";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowvat = $numvat1;
		$numrowvat += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowvat, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowvat, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowvat)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowvat, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowvat, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowvat, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowvat, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowvat.':G'.$numrowvat)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowvat++;
		}

			$totvat = $numrowvat;
			$totvat += 0;
			$sumvat = $numrowvat-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totvat, "Subtotal VAT Payables");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totvat, '=SUM(E'.$numvat.':E'.$sumvat.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totvat, '=SUM(F'.$numvat.':F'.$sumvat.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totvat, '=SUM(G'.$numvat.':G'.$sumvat.')');
			$excel->getActiveSheet()->getStyle('D'.$totvat.':G'.$totvat)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totvat.':G'.$totvat)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numwht = $totvat;
		$numwht += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numwht, "21320000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numwht, "WHT PAYABLES");
		$excel->getActiveSheet()->getStyle('C'.$numwht)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numwht)->applyFromArray($style_sub);

		$category = "wht";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowwht = $numwht;
		$numrowwht += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowwht, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowwht, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowwht)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowwht, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowwht, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowwht, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowwht, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowwht.':G'.$numrowwht)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowwht++;
		}

			$totwht = $numrowwht;
			$totwht += 0;
			$sumwht = $numrowwht-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totwht, "Subtotal WHT Payables");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totwht, '=SUM(E'.$numwht.':E'.$sumwht.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totwht, '=SUM(F'.$numwht.':F'.$sumwht.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totwht, '=SUM(G'.$numwht.':G'.$sumwht.')');
			$excel->getActiveSheet()->getStyle('D'.$totwht.':G'.$totwht)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totwht.':G'.$totwht)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcit = $totwht;
		$numcit += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcit, "21330000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcit, "CIT PAYABLES");
		$excel->getActiveSheet()->getStyle('C'.$numcit)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcit)->applyFromArray($style_sub);

		$category = "cit";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcit = $numcit;
		$numrowcit += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcit, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcit, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcit)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcit, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcit, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcit, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcit, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcit.':G'.$numrowcit)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcit++;
		}

			$totcit = $numrowcit;
			$totcit += 0;
			$sumcit = $numcit+=1;
			$sumcit1 = $numrowcit-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcit, "Subtotal CIT Payables");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcit, '=SUM(E'.$sumcit.':E'.$sumcit1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcit, '=SUM(F'.$sumcit.':F'.$sumcit1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcit, '=SUM(G'.$sumcit.':G'.$sumcit1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcit.':G'.$totcit)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcit.':G'.$totcit)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numaut = $totcit;
		$numaut += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaut, "21340000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numaut, "AP Tax Authority");
		$excel->getActiveSheet()->getStyle('C'.$numaut)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numaut)->applyFromArray($style_sub);

		$category = "aut";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowaut = $numaut;
		$numrowaut += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowaut, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowaut, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowaut)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowaut, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowaut, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowaut, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowaut, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowaut.':G'.$numrowaut)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowaut++;
		}

			$totaut = $numrowaut;
			$totaut += 0;
			$sumaut = $numrowaut-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaut, "Subtotal WHT Payables");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaut, '=SUM(E'.$numaut.':E'.$sumaut.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaut, '=SUM(F'.$numaut.':F'.$sumaut.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaut, '=SUM(G'.$numaut.':G'.$sumaut.')');
			$excel->getActiveSheet()->getStyle('D'.$totaut.':G'.$totaut)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaut.':G'.$totaut)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaut1 = $totaut;
			$totaut1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaut1, "SUBTOTAL TAX PAYABLES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaut1, '=SUM(E'.$numvat.':E'.$totaut.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaut1, '=SUM(F'.$numvat.':F'.$totaut.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaut1, '=SUM(G'.$numvat.':G'.$totaut.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totaut1.':G'.$totaut1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaut1.':G'.$totaut1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totaut2 = $totaut1;
			$totaut2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totaut2, "SUBTOTAL TAX PAYABLES - NET");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totaut2, '=E'.$totaut1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totaut2, '=F'.$totaut1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totaut2, '=G'.$totaut1);
			$excel->getActiveSheet()->getStyle('D'.$totaut2.':G'.$totaut2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totaut2.':G'.$totaut2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numshort = $totaut2;
		$numshort += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numshort, "21400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numshort, "LOANS");
		$excel->getActiveSheet()->getStyle('C'.$numshort)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numshort)->applyFromArray($style_sub);

		$numshort1 = $numshort;
		$numshort1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numshort1, "21410000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numshort1, "SHORT-TERM LOAN");
		$excel->getActiveSheet()->getStyle('C'.$numshort1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numshort1)->applyFromArray($style_sub);

		$numshort2 = $numshort1;
		$numshort2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numshort2, "21411000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numshort2, "SHORT-TERM LOAN");
		$excel->getActiveSheet()->getStyle('C'.$numshort2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numshort2)->applyFromArray($style_sub);

		$category = "short";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowshort = $numshort2;
		$numrowshort += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowshort, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowshort, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowshort)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowshort, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowshort, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowshort, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowshort, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowshort.':G'.$numrowshort)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowshort++;
		}

			$totshort = $numrowshort;
			$totshort += 0;
			$sumshort = $numrowshort-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totshort, "Subtotal ST Bank Loans - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totshort, '=SUM(E'.$numshort.':E'.$sumshort.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totshort, '=SUM(F'.$numshort.':F'.$sumshort.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totshort, '=SUM(G'.$numshort.':G'.$sumshort.')');
			$excel->getActiveSheet()->getStyle('D'.$totshort.':G'.$totshort)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totshort.':G'.$totshort)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totshort1 = $totshort;
			$totshort1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totshort1, "SUBTOTAL SHORT-TERM LOAN");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totshort1, '=SUM(E'.$numshort.':E'.$totshort.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totshort1, '=SUM(F'.$numshort.':F'.$totshort.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totshort1, '=SUM(G'.$numshort.':G'.$totshort.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totshort1.':G'.$totshort1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totshort1.':G'.$totshort1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcp = $totshort1;
		$numcp += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcp, "21412000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcp, "CURRENT PORTION OF MEDIUM - TERM LOANS");
		$excel->getActiveSheet()->getStyle('C'.$numcp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcp)->applyFromArray($style_sub);

		$category = "cp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcp = $numcp;
		$numrowcp += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcp, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcp, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcp, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcp.':G'.$numrowcp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcp++;
		}

			$totcp = $numrowcp;
			$totcp += 0;
			$sumcp = $numrowcp-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcp, "Subtotal CP of Medium -Term Loans - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcp, '=SUM(E'.$numcp.':E'.$sumcp.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcp, '=SUM(F'.$numcp.':F'.$sumcp.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcp, '=SUM(G'.$numcp.':G'.$sumcp.')');
			$excel->getActiveSheet()->getStyle('D'.$totcp.':G'.$totcp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcp.':G'.$totcp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcp1 = $totcp;
			$totcp1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcp1, "SUBTOTAL CURRENT PORTION OF MEDIUM - TERM LOANS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcp1, '=SUM(E'.$numcp.':E'.$totcp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcp1, '=SUM(F'.$numcp.':F'.$totcp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcp1, '=SUM(G'.$numcp.':G'.$totcp.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totcp1.':G'.$totcp1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcp1.':G'.$totcp1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcp2 = $totcp1;
			$totcp2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcp2, "SUBTOTAL LOANS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcp2, '=E'.$totcp1.'+E'.$totshort1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcp2, '=F'.$totcp1.'+F'.$totshort1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcp2, '=G'.$totcp1.'+G'.$totshort1);
			$excel->getActiveSheet()->getStyle('D'.$totcp2.':G'.$totcp2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcp2.':G'.$totcp2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numocl = $totcp2;
		$numocl += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numocl, "21500000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numocl, "OTHER CURRENT LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numocl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numocl)->applyFromArray($style_sub);

		$numocl1 = $numocl;
		$numocl1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numocl1, "21510000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numocl1, "RELATED-PARTY  LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numocl1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numocl1)->applyFromArray($style_sub);

		$category = "ocl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowocl = $numocl1;
		$numrowocl += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowocl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowocl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowocl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowocl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowocl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowocl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowocl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowocl.':G'.$numrowocl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowocl++;
		}

			$totcp = $numrowocl;
			$totcp += 0;
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$totcp, "21520000");
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcp, "THIRD-PARTY  LIABILITIES");
			$excel->getActiveSheet()->getStyle('C'.$totcp)->applyFromArray($style_nature);
			$excel->getActiveSheet()->getStyle('D'.$totcp)->applyFromArray($style_sub);

			$category = "lbt";
			if(!isset($data[$category])){
				$data[$category] = array();
		}

			$numrowlbt = $totcp;
			$numrowlbt += 1;

			foreach($data[$category] as $row)	{
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowlbt, $row['GROUP_REPORT']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowlbt, $row['NATURE']);
				$excel->getActiveSheet()->getStyle('C'.$numrowlbt)->applyFromArray($style_row);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowlbt, $row['DESCRIPTION']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowlbt, $row['AMOUNT']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowlbt, $row['AMOUNT1']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowlbt, $row['AMOUNT2']);
				$excel->getActiveSheet()->getStyle('E'.$numrowlbt.':G'.$numrowlbt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

				$numrowlbt++;
			}

			$totlbt = $numrowlbt;
			$totlbt += 0;
			$sumlbt = $numrowlbt-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totlbt, "Subtotal Advances from Customer");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totlbt, '=SUM(E'.$numocl.':E'.$sumlbt.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totlbt, '=SUM(F'.$numocl.':F'.$sumlbt.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totlbt, '=SUM(G'.$numocl.':G'.$sumlbt.')');
			$excel->getActiveSheet()->getStyle('D'.$totlbt.':G'.$totlbt)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totlbt.':G'.$totlbt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numbpjs = $totlbt;
		$numbpjs += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numbpjs, "21540000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numbpjs, "BPJS LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numbpjs)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numbpjs)->applyFromArray($style_sub);

		$category = "bpjs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowbpjs = $numbpjs;
		$numrowbpjs += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowbpjs, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowbpjs, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowbpjs)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowbpjs, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowbpjs, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowbpjs, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowbpjs, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowbpjs.':G'.$numrowbpjs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowbpjs++;
		}

			$totbpjs = $numrowbpjs;
			$totbpjs += 0;
			$sumbpjs = $numrowbpjs-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totbpjs, "Subtotal BPJS Liabilities");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totbpjs, '=SUM(E'.$numbpjs.':E'.$sumbpjs.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totbpjs, '=SUM(F'.$numbpjs.':F'.$sumbpjs.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totbpjs, '=SUM(G'.$numbpjs.':G'.$sumbpjs.')');
			$excel->getActiveSheet()->getStyle('D'.$totbpjs.':G'.$totbpjs)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totbpjs.':G'.$totbpjs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdvd = $totbpjs;
		$numdvd += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdvd, "21550000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdvd, "DIVIDENS PAYABLE");
		$excel->getActiveSheet()->getStyle('C'.$numdvd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdvd)->applyFromArray($style_sub);

		$category = "dvd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdvd = $numdvd;
		$numrowdvd += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdvd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdvd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdvd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdvd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdvd, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdvd, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdvd, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdvd.':G'.$numrowdvd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdvd++;
		}

			$totdvd = $numrowdvd;
			$totdvd += 0;
			$sumdvd = $numrowdvd-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdvd, "Subtotal Dividends Payable");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdvd, '=SUM(E'.$numdvd.':E'.$sumdvd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdvd, '=SUM(F'.$numdvd.':F'.$sumdvd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdvd, '=SUM(G'.$numdvd.':G'.$sumdvd.')');
			$excel->getActiveSheet()->getStyle('D'.$totdvd.':G'.$totdvd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdvd.':G'.$totdvd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numund = $totdvd;
		$numund += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numund, "21560000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numund, "OTHERS CURRENT LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numund)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numund)->applyFromArray($style_sub);

		$category = "und";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowund = $numund;
		$numrowund += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowund, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowund, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowund)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowund, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowund, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowund, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowund, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowund.':G'.$numrowund)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowund++;
		}

			$totund = $numrowund;
			$totund += 0;
			$sumund = $numrowund-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totund, "Subtotal Other Current Liabilities");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totund, '=SUM(E'.$numund.':E'.$sumund.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totund, '=SUM(F'.$numund.':F'.$sumund.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totund, '=SUM(G'.$numund.':G'.$sumund.')');
			$excel->getActiveSheet()->getStyle('D'.$totund.':G'.$totund)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totund.':G'.$totund)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totund1 = $totund;
			$totund1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totund1, "SUBTOTAL OTHER CURRENT LIABILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totund1, '=SUM(E'.$numund.':E'.$totund.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totund1, '=SUM(F'.$numund.':F'.$totund.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totund1, '=SUM(G'.$numund.':G'.$totund.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totund1.':G'.$totund1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totund1.':G'.$totund1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totund2 = $totund1;
			$totund2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totund2, "SUBTOTAL CURRENT LIABILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totund2, '=E'.$totund1.'+E'.$totcp2.'+E'.$totaut2.'+E'.$totlbl2.'+E'.$totaapl2);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totund2, '=F'.$totund1.'+F'.$totcp2.'+F'.$totaut2.'+F'.$totlbl2.'+F'.$totaapl2);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totund2, '=G'.$totund1.'+G'.$totcp2.'+G'.$totaut2.'+G'.$totlbl2.'+G'.$totaapl2);
			$excel->getActiveSheet()->getStyle('D'.$totund2.':G'.$totund2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totund2.':G'.$totund2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$nummtl = $totund2;
		$nummtl += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummtl, "22000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummtl, "NON CURRENT LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$nummtl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummtl)->applyFromArray($style_sub);

		$nummtl1 = $nummtl;
		$nummtl1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummtl1, "22100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummtl1, "LOANS");
		$excel->getActiveSheet()->getStyle('C'.$nummtl1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummtl1)->applyFromArray($style_sub);

		$nummtl2 = $nummtl1;
		$nummtl2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummtl2, "22110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummtl2, "MEDIUM - TERM LOANS - IDR");
		$excel->getActiveSheet()->getStyle('C'.$nummtl2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummtl2)->applyFromArray($style_sub);

		$category = "mtl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowmtl = $nummtl2;
		$numrowmtl += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmtl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmtl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmtl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmtl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmtl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmtl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmtl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmtl.':G'.$numrowmtl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmtl++;
		}

			$totmtl = $numrowmtl;
			$totmtl += 0;
			$summtl = $numrowmtl-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmtl, "Subtotal Medium Term Loans - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmtl, '=SUM(E'.$nummtl.':E'.$summtl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmtl, '=SUM(F'.$nummtl.':F'.$summtl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmtl, '=SUM(G'.$nummtl.':G'.$summtl.')');
			$excel->getActiveSheet()->getStyle('D'.$totmtl.':G'.$totmtl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmtl.':G'.$totmtl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$nummtb = $totmtl;
		$nummtb += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummtb, "22120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummtb, "MEDIUM - TERM LOANS - FOREIGN CURRENCY");
		$excel->getActiveSheet()->getStyle('C'.$nummtb)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummtb)->applyFromArray($style_sub);

		$nummtb1 = $nummtb;
		$nummtb1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummtb1, "22121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummtb1, "MEDIUM - TERM BANK LOANS - FOREIGN CURRENCY");
		$excel->getActiveSheet()->getStyle('C'.$nummtb1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummtb1)->applyFromArray($style_sub);

		$category = "mtb";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowmtb = $nummtb1;
		$numrowmtb += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmtb, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmtb, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmtb)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmtb, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmtb, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmtb, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmtb, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmtb.':G'.$numrowmtb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmtb++;
		}

			$totmtb = $numrowmtb;
			$totmtb += 0;
			$summtb = $numrowmtb-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmtb, "Subtotal Medium Term Loans - Foreign Currency");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmtb, '=SUM(E'.$nummtb.':E'.$summtb.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmtb, '=SUM(F'.$nummtb.':F'.$summtb.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmtb, '=SUM(G'.$nummtb.':G'.$summtb.')');
			$excel->getActiveSheet()->getStyle('D'.$totmtb.':G'.$totmtb)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmtb.':G'.$totmtb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totmtb1 = $totmtb;
			$totmtb1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmtb1, "SUBTOTAL MEDIUM TERM LOAN");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmtb1, '=SUM(E'.$nummtl.':E'.$totmtb.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmtb1, '=SUM(F'.$nummtl.':F'.$totmtb.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmtb1, '=SUM(G'.$nummtl.':G'.$totmtb.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totmtb1.':G'.$totmtb1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmtb1.':G'.$totmtb1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numlte = $totmtb1;
		$numlte += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numlte, "22130000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numlte, "LONG - TERM LOANS - IDR");
		$excel->getActiveSheet()->getStyle('C'.$numlte)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numlte)->applyFromArray($style_sub);

		$category = "lte";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowlte = $numlte;
		$numrowlte += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowlte, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowlte, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowlte)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowlte, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowlte, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowlte, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowlte, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowlte.':G'.$numrowlte)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowlte++;
		}

			$totlte = $numrowlte;
			$totlte += 0;
			$sumlte1 = $numrowlte-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totlte, "Subtotal Long Term Loans - IDR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totlte, '=SUM(E'.$numlte.':E'.$sumlte1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totlte, '=SUM(F'.$numlte.':F'.$sumlte1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totlte, '=SUM(G'.$numlte.':G'.$sumlte1.')');
			$excel->getActiveSheet()->getStyle('D'.$totlte.':G'.$totlte)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totlte.':G'.$totlte)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numltb = $totlte;
		$numltb += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numltb, "22140000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numltb, "LONG - TERM LOANS - FOREIGN CURRENCY");
		$excel->getActiveSheet()->getStyle('C'.$numltb)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numltb)->applyFromArray($style_sub);

		$category = "ltb";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowltb = $numltb;
		$numrowltb += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowltb, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowltb, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowltb)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowltb, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowltb, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowltb, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowltb, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowltb.':G'.$numrowltb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowltb++;
		}

			$totltb = $numrowltb;
			$totltb += 0;
			$sumltb = $numrowltb-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totltb, "Subtotal Long Term Loans - Foreign Currency");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totltb, '=SUM(E'.$numltb.':E'.$sumltb.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totltb, '=SUM(F'.$numltb.':F'.$sumltb.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totltb, '=SUM(G'.$numltb.':G'.$sumltb.')');
			$excel->getActiveSheet()->getStyle('D'.$totltb.':G'.$totltb)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totltb.':G'.$totltb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totltb1 = $totltb;
			$totltb1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totltb1, "SUBTOTAL LONG TERM LOAN");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totltb1, '=SUM(E'.$numlte.':E'.$totltb.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totltb1, '=SUM(F'.$numlte.':F'.$totltb.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totltb1, '=SUM(G'.$numlte.':G'.$totltb.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totltb1.':G'.$totltb1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totltb1.':G'.$totltb1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totltb2 = $totltb1;
			$totltb2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totltb2, "SUBTOTAL LOANS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totltb2, '=E'.$totmtb1.'+E'.$totltb1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totltb2, '=F'.$totmtb1.'+F'.$totltb1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totltb2, '=G'.$totmtb1.'+G'.$totltb1);
			$excel->getActiveSheet()->getStyle('D'.$totltb2.':G'.$totltb2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totltb2.':G'.$totltb2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numncp = $totltb2;
		$numncp += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numncp, "22200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numncp, "PROVISION");
		$excel->getActiveSheet()->getStyle('C'.$numncp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numncp)->applyFromArray($style_sub);

		$numncp1 = $numncp;
		$numncp1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numncp1, "22210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numncp1, "PROVISION FOR EMPLOYEE BENEFIT");
		$excel->getActiveSheet()->getStyle('C'.$numncp1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numncp1)->applyFromArray($style_sub);

		$numncp2 = $numncp1;
		$numncp2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numncp2, "22211000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numncp2, "PROVISION FOR EMPLOYEE BENEFIT  - NON - CURRENT");
		$excel->getActiveSheet()->getStyle('C'.$numncp2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numncp2)->applyFromArray($style_sub);

		$category = "ncp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowncp = $numncp2;
		$numrowncp += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowncp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowncp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowncp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowncp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowncp, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowncp, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowncp, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowncp.':G'.$numrowncp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowncp++;
		}

			$totncp = $numrowncp;
			$totncp += 0;
			$sumncp = $numrowncp-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totncp, "SUBTOTAL PROVISION FOR EMPLOYEE BENEFIT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totncp, '=SUM(E'.$numncp.':E'.$sumncp.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totncp, '=SUM(F'.$numncp.':F'.$sumncp.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totncp, '=SUM(G'.$numncp.':G'.$sumncp.')');
			$excel->getActiveSheet()->getStyle('D'.$totncp.':G'.$totncp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totncp.':G'.$totncp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totncp1 = $totncp;
			$totncp1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totncp1, "SUBTOTAL PROVISION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totncp1, '=SUM(E'.$numncp.':E'.$totncp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totncp1, '=SUM(F'.$numncp.':F'.$totncp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totncp1, '=SUM(G'.$numncp.':G'.$totncp.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totncp1.':G'.$totncp1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totncp1.':G'.$totncp1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totncp2 = $totncp1;
			$totncp2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totncp2, "SUBTOTAL NON CURRENT LIABILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totncp2, '=E'.$totncp1.'+E'.$totltb2);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totncp2, '=F'.$totncp1.'+F'.$totltb2);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totncp2, '=G'.$totncp1.'+G'.$totltb2);
			$excel->getActiveSheet()->getStyle('D'.$totncp2.':G'.$totncp2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totncp2.':G'.$totncp2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdtl = $totncp2;
		$numdtl += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdtl, "21530000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdtl, "DEFERRED TAX LIABILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numdtl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdtl)->applyFromArray($style_sub);

		$category = "dtl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdtl = $numdtl;
		$numrowdtl += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdtl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdtl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdtl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdtl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdtl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdtl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdtl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdtl.':G'.$numrowdtl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdtl++;
		}

			$totdtl = $numrowdtl;
			$totdtl += 0;
			$sumdtl = $numrowdtl-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdtl, "SUBTOTAL DEFERRED TAX LIABILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdtl, '=SUM(E'.$numdtl.':E'.$sumdtl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdtl, '=SUM(F'.$numdtl.':F'.$sumdtl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdtl, '=SUM(G'.$numdtl.':G'.$sumdtl.')');
			$excel->getActiveSheet()->getStyle('D'.$totdtl.':G'.$totdtl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdtl.':G'.$totdtl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totdtl1 = $totdtl;
			$totdtl1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdtl1, "SUBTOTAL DEFERRED TAX LIABILITIES - NET");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdtl1, '=E'.$totdtl);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdtl1, '=F'.$totdtl);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdtl1, '=G'.$totdtl);
			$excel->getActiveSheet()->getStyle('D'.$totdtl1.':G'.$totdtl1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdtl1.':G'.$totdtl1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totttl = $totdtl1;
			$totttl += 2;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totttl, "TOTAL LIABILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totttl, '=E'.$totncp2.'+E'.$totdtl1.'+E'.$totund2);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totttl, '=F'.$totncp2.'+F'.$totdtl1.'+F'.$totund2);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totttl, '=G'.$totncp2.'+G'.$totdtl1.'+G'.$totund2);
			$excel->getActiveSheet()->getStyle('D'.$totttl)->applyFromArray($style_sub);
			$excel->getActiveSheet()->getStyle('E'.$totttl.':G'.$totttl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totttl.':G'.$totttl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numequ = $totttl;
		$numequ += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numequ, "30000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numequ, "EQUITY");
		$excel->getActiveSheet()->getStyle('C'.$numequ)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numequ)->applyFromArray($style_sub);

		$numequ1 = $numequ;
		$numequ1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numequ1, "31000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numequ1, "STOCKHOLDER'S EQUITY");
		$excel->getActiveSheet()->getStyle('C'.$numequ1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numequ1)->applyFromArray($style_sub);

		$numequ2 = $numequ1;
		$numequ2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numequ2, "31100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numequ2, "CAPITAL STOCK");
		$excel->getActiveSheet()->getStyle('C'.$numequ2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numequ2)->applyFromArray($style_sub);

		$category = "equ";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowequ = $numequ2;
		$numrowequ += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowequ, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowequ, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowequ)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowequ, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowequ, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowequ, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowequ, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowequ.':G'.$numrowequ)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowequ++;
		}

			$totequ = $numrowequ;
			$totequ += 0;
			$sumequ = $numrowequ-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totequ, "Subtotal Capital Stock");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totequ, '=SUM(E'.$numequ.':E'.$sumequ.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totequ, '=SUM(F'.$numequ.':F'.$sumequ.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totequ, '=SUM(G'.$numequ.':G'.$sumequ.')');
			$excel->getActiveSheet()->getStyle('D'.$totequ.':G'.$totequ)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totequ.':G'.$totequ)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numreta = $totequ;
		$numreta += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numreta, "31200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numreta, "RETAINED EARNINGS");
		$excel->getActiveSheet()->getStyle('C'.$numreta)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numreta)->applyFromArray($style_sub);

		$category = "reta";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowreta = $numreta;
		$numrowreta += 1;

		foreach($data[$category] as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowreta, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowreta, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowreta)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowreta, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowreta, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowreta, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowreta, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowreta.':G'.$numrowreta)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowreta++;
		}

			$totreta = $numrowreta;
			$totreta += 0;
			$sumreta = $numrowreta-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totreta, "Subtotal Retained Earnings");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totreta, '=SUM(E'.$numreta.':E'.$sumreta.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totreta, '=SUM(F'.$numreta.':F'.$sumreta.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totreta, '=SUM(G'.$numreta.':G'.$sumreta.')');
			$excel->getActiveSheet()->getStyle('D'.$totreta.':G'.$totreta)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totreta.':G'.$totreta)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numagl = $totreta;
		$numagl += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numagl, "31300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numagl, "ACTUARIAL GAINS/LOSSES");
		$excel->getActiveSheet()->getStyle('C'.$numagl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numagl)->applyFromArray($style_sub);

		$category = "agl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowagl = $numagl;
		$numrowagl += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowagl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowagl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowagl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowagl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowagl, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowagl, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowagl, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowagl.':G'.$numrowagl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowagl++;
		}

			$totagl = $numrowagl;
			$totagl += 1;
			$sumagl = $numrowagl-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totagl, "Subtotal Actuarial Gains/Losses");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totagl, '=SUM(E'.$numagl.':E'.$sumagl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totagl, '=SUM(F'.$numagl.':F'.$sumagl.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totagl, '=SUM(G'.$numagl.':G'.$sumagl.')');
			$excel->getActiveSheet()->getStyle('D'.$totagl.':G'.$totagl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totagl.':G'.$totagl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numacd = $totagl;
		$numacd += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numacd, "31400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numacd, "ACCUMULATED DEFICIT");
		$excel->getActiveSheet()->getStyle('C'.$numacd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numacd)->applyFromArray($style_sub);

		$category = "acd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowacd = $numacd;
		$numrowacd += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowacd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowacd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowacd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowacd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowacd, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowacd, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowacd, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowacd.':G'.$numrowacd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowacd++;
		}

			$totacd = $numrowacd;
			$totacd += 1;
			$sumacd = $numrowacd-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacd, "Subtotal Accumulated Deficit");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacd, '=SUM(E'.$numacd.':E'.$sumacd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacd, '=SUM(F'.$numacd.':F'.$sumacd.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacd, '=SUM(G'.$numacd.':G'.$sumacd.')');
			$excel->getActiveSheet()->getStyle('D'.$totacd.':G'.$totacd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacd.':G'.$totacd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totacd1 = $totacd;
			$totacd1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totacd1, "SUBTOTAL STOCKHOLDER'S EQUITY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totacd1, '=SUM(E'.$numequ.':E'.$totacd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totacd1, '=SUM(F'.$numequ.':F'.$totacd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totacd1, '=SUM(G'.$numequ.':G'.$totacd.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totacd1.':G'.$totacd1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totacd1.':G'.$totacd1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numoci = $totacd1;
		$numoci += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoci, "32000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoci, "OTHER COMPREHENSIVE INCOME");
		$excel->getActiveSheet()->getStyle('C'.$numoci)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoci)->applyFromArray($style_sub);

		$category = "oci";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowoci = $numoci;
		$numrowoci += 1;

		foreach($data[$category] as $row)	{
			
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowoci, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoci, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowoci)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowoci, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoci, $row['AMOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowoci, $row['AMOUNT1']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowoci, $row['AMOUNT2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowoci.':G'.$numrowoci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowoci++;
		}

			$totoci = $numrowoci;
			$totoci += 0;
			$sumoci = $numrowoci-1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoci, "SUBTOTAL OTHER COMPREHENSIVE INCOME");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoci, '=SUM(E'.$numoci.':E'.$sumoci.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoci, '=SUM(F'.$numoci.':F'.$sumoci.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoci, '=SUM(G'.$numoci.':G'.$sumoci.')');
			$excel->getActiveSheet()->getStyle('E'.$totoci)->applyFromArray($style_sub);
			$excel->getActiveSheet()->getStyle('D'.$totoci.':G'.$totoci)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoci.':G'.$totoci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totequqlity = $totoci;
			$totequqlity += 2;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totequqlity, "TOTAL EQUITY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totequqlity, '=E'.$totoci.'+E'.$totacd1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totequqlity, '=F'.$totoci.'+F'.$totacd1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totequqlity, '=G'.$totoci.'+G'.$totacd1);
			$excel->getActiveSheet()->getStyle('D'.$totequqlity)->applyFromArray($style_sub);
			$excel->getActiveSheet()->getStyle('E'.$totequqlity.':G'.$totequqlity)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totequqlity.':G'.$totequqlity)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tottotal = $totequqlity;
			$tottotal += 2;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottotal, "TOTAL LIABILITIES AND EQUITY");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottotal, '=E'.$totequqlity.'+E'.$totttl);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottotal, '=F'.$totequqlity.'+F'.$totttl);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottotal, '=G'.$totequqlity.'+G'.$totttl);
			$excel->getActiveSheet()->getStyle('D'.$tottotal)->applyFromArray($style_sub);
			$excel->getActiveSheet()->getStyle('E'.$tottotal.':G'.$tottotal)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottotal.':G'.$tottotal)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		
		
		
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		
		$excel->getActiveSheet(0)->setTitle("BSWP");
		$excel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="BSWP.xls"');
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}

/* End of file Bswp.php */
/* Location: ./application/controllers/report_xls/Bswp.php */