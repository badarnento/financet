<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlDetail extends CI_Controller {

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

	public function show_pldetail(){
		$data['title']          = "PL DETAIL";
		$data['module']         = "datatable";
		$data['template_page']  = "report_xls/pl_detail";
		$data['get_exist_year'] = $this->tb->get_exist_year();
		
		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_pldetail(){
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

		$excel->getProperties()	->setCreator('PL DETAIL')
								->setLastModifiedBy('PL DETAIL')
								->setTitle("PL DETAIL")
								->setSubject("PL DETAIL")
								->setDescription("PL DETAIL")
								->setKeywords("PL DETAIL");

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
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "STATEMENTS OF PROFIT OR LOSS AND OTHER COMPREHENSIVE INCOME - LONG FORM DETAIL");
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "FOR THREE MONTHS PERIOD ENDED ".strtoupper($bulan).", ".$year." AND ".strtoupper($bulan1).", ".$year1." AND ".strtoupper($bulan2).", ".$year2." (UNAUDITED)");
		
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
		$excel->setActiveSheetIndex(0)->setCellValue('E6', "PTD");
		$excel->getActiveSheet()->getStyle('E5:E6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('F5', $bulan."-".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('F6', "YTD");
		$excel->getActiveSheet()->getStyle('F5:F6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('G5', $bulan1."-".$year1);
		$excel->setActiveSheetIndex(0)->setCellValue('G6', "PTD");
		$excel->getActiveSheet()->getStyle('G5:G6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('H5', $bulan1."-".$year1);
		$excel->setActiveSheetIndex(0)->setCellValue('H6', "YTD");
		$excel->getActiveSheet()->getStyle('H5:H6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('I5', $bulan2."-".$year2);
		$excel->setActiveSheetIndex(0)->setCellValue('I6', "PTD");
		$excel->getActiveSheet()->getStyle('I5:I6')->applyFromArray($style_header);
		$excel->setActiveSheetIndex(0)->setCellValue('J5', $bulan2."-".$year2);
		$excel->setActiveSheetIndex(0)->setCellValue('J6', "YTD");
		$excel->getActiveSheet()->getStyle('J5:J6')->applyFromArray($style_header);

		$excel->setActiveSheetIndex(0)->setCellValue('C8', "40000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D8', "REVENUE");
		$excel->setActiveSheetIndex(0)->setCellValue('C9', "41000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D9', "MERCHANT (MDR)");
		$excel->getActiveSheet()->getStyle('C8:C9')->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D8:D9')->applyFromArray($style_sub);

		$category = "revenue";
		if(!isset($data[$category])){
				$data[$category] = array();
		}
		$hasil = $this->report_xls->get_pldetail($year, $month, $category, $year1, $month1, $year2, $month2);

		$data = array();
		$category_arr = array(
						// "revenue"      	=> array("41000001", "41000002", "41000003", "41000004", "41000005", "41000006", "41000007", "41000008", "41000009", "41000011", "41000012", "41000013"),
						"revenue"      	=> array("41000"),
						// "ppob"         	=> array("42000001", "42000002", "42000003"),
						"ppob"         	=> array("42000"),
						// "remmitance"   	=> array("43000001", "43000002"),
						"remmitance"   	=> array("43000"),
						// "fsi"   		=> array("44000001", "44000002", "44000003", "44000004", "44000005", "44000006"),
						"fsi"   		=> array("44000"),
						// "tlc"   		=> array("45000001", "45000002", "45000003", "45000004", "45000005", "45000006", "45000007"),
						"tlc"   		=> array("45000"),
						// "trp"   		=> array("46000001", "46000002", "46000003", "46000004"),
						"trp"   		=> array("46000"),
						// "cico"   		=> array("47000001", "47000002"),
						"cico"   		=> array("47000"),
						// "pjld"   		=> array("48100001"),
						"pjld"   		=> array("48100"),
						// "pjls"   		=> array("48200001"),
						"pjls"   		=> array("48200"),
						// "otr"   		=> array("48300001", "48300002", "48300003"),
						"otr"   		=> array("48300"),
						// "rmb"   		=> array("52111001"),
						"rmb"   		=> array("52111"),
						// "rme"   		=> array("52112001"),
						"rme"   		=> array("52112"),
						// "rmf"   		=> array("52113001"),
						"rmf"   		=> array("52113"),
						// "rmo"   		=> array("52114001"),
						"rmo"   		=> array("52114"),
						// "rmm"   		=> array("52211001"),
						"rmm"   		=> array("52211"),
						// "rma"   		=> array("52212001"),
						"rma"   		=> array("52212"),
						// "rmd"   		=> array("52213001"),
						"rmd"   		=> array("52213"),
						// "rmc"   		=> array("52214001"),
						"rmc"   		=> array("52214"),
						// "rmfs"   		=> array("52115001"),
						"rmfs"   		=> array("52115"),
						// "rms"   		=> array("52116001"),
						"rms"   		=> array("52116"),
						// "rmma"   		=> array("52117001"),
						"rmma"   		=> array("52117"),
						// "rmi"   		=> array("52121001"),
						"rmi"   		=> array("52121"),
						// "rmsf"   		=> array("52122001"),
						"rmsf"   		=> array("52122"),
						// "os"   			=> array("52131001", "52131002", "52131003", "52131004", "52131005"),
						"os"   			=> array("52131"),
						// "cicosc"   		=> array("52132001", "52132002"),
						"cicosc"   		=> array("52132"),
						// "ccae"   		=> array("52133001", "52133002"),
						"ccae"   		=> array("52133"),
						// "oh"   			=> array("52134001"),
						"oh"   			=> array("52134"),
						// "orm"   		=> array("52135001"),
						"orm"   		=> array("52135"),
						// "otc"   		=> array("52136001"),
						"otc"   		=> array("52136"),
						// "dir"   		=> array("52311001"),
						"dir"   		=> array("52311"),
						// "pe"   			=> array("53111001", "53111002"),
						"pe"   			=> array("53111"),
						// "icn"   		=> array("53112001", "53112002"),
						"icn"   		=> array("53112"),
						// "pa"   			=> array("53121001", "53121002", "53121003", "53121004", "53121005", "53121006"),
						"pa"   			=> array("53121"),
						// "oa"   			=> array("53122001", "53122002", "53122003"),
						"oa"   			=> array("53122"),
						// "eb"   			=> array("53210001", "53210002", "53210003", "53210004", "53210005"),
						"eb"   			=> array("53210"),
						// "mse"   		=> array("54111101"),
						"mse"   		=> array("541111"),
						// "mct"   		=> array("54111201", "54111202", "54111203", "54111204", "54111205", "54111206", "54111207", "54111208"),
						"mct"   		=> array("541112"),
						// "pt"   			=> array("54111301", "54111302"),
						"pt"   			=> array("541113"),
						// "part"   		=> array("54111401", "54111402"),
						"part"   		=> array("541114"),
						// "rc"   			=> array("54111501", "54111502", "54111503"),
						"rc"   			=> array("541115"),
						// "cicodis"   	=> array("54111601", "54111602", "54111701", "54111702"),
						"cicodis"   	=> array("541116", "541117"),
						// "eas"   		=> array("54121001", "54121002"),
						"eas"   		=> array("54121"),
						// "spo"   		=> array("54122001"),
						"spo"   		=> array("54122"),
						// "copc"   		=> array("54130001", "54130002", "54130003", "54130004", "54130005", "54130006", "54130007", "54130008", "54130009"),
						"copc"   		=> array("54130"),
						// "adver"   		=> array("54211001"),
						"adver"   		=> array("54211"),
						// "nemp"   		=> array("54212001"),
						"nemp"   		=> array("54212"),
						// "advp"   		=> array("54221001"),
						"advp"   		=> array("54221"),
						// "nem"   		=> array("54222001"),
						"nem"   		=> array("54222"),
						// "adva"   		=> array("54230001"),
						"adva"   		=> array("54230"),
						// "csa"   		=> array("54300001", "54300002"),
						"csa"   		=> array("54300"),
						// "clp"   		=> array("54400001"),
						"clp"   		=> array("54400"),
						// "ome"   		=> array("54500001", "54500002", "54500003"),
						"ome"   		=> array("54500"),
						// "gae"   		=> array("55111001", "55111002"),
						"gae"   		=> array("55111"),
						// "rb"   			=> array("55112100", "55112101"),
						"rb"   			=> array("551121"),
						// "rob"   		=> array("55112201"),
						"rob"   		=> array("551122"),
						// "roe"   		=> array("55113101"),
						"roe"   		=> array("551131"),
						// "ref"   		=> array("55113201", "55113201"),
						"ref"   		=> array("551132"),
						// "rd"   			=> array("55114101"),
						"rd"   			=> array("551141"),
						// "tat"   		=> array("55120001", "55120002", "55120003"),
						"tat"   		=> array("55120"),
						// "sac"   		=> array("55131001"),
						"sac"   		=> array("55131"),
						// "sm"   			=> array("55132001"),
						"sm"   			=> array("55132"),
						// "utl"   		=> array("55141001"),
						"utl"   		=> array("55141"),
						// "ue"   			=> array("55142001"),
						"ue"   			=> array("55142"),
						// "uw"   			=> array("55143001"),
						"uw"   			=> array("55143"),
						// "otas"   		=> array("55150001", "55150002", "55150003", "55150004"),
						"otas"   		=> array("55150"),
						// "paf"   		=> array("55160001", "55160002"),
						"paf"   		=> array("55160"),
						// "adme"   		=> array("55211001", "55211002"),
						"adme"   		=> array("55211"),
						// "pfs"   		=> array("55212001"),
						"pfs"   		=> array("55212"),
						// "ins"   		=> array("55220001", "55220002"),
						"ins"   		=> array("55220"),
						// "tar"   		=> array("55231001"),
						"tar"   		=> array("55231"),
						// "tad"   		=> array("55232001"),
						"tad"   		=> array("55232"),
						// "mare"   		=> array("55240001", "55240002", "55240003"),
						"mare"   		=> array("55240"),
						// "dac"   		=> array("55250001", "55250002"),
						"dac"   		=> array("55250"),
						// "mer"   		=> array("55261001", "55261002", "55261003"),
						"mer"   		=> array("55261"),
						// "er"   			=> array("55262001"),
						"er"   			=> array("55262"),
						// "ots"   		=> array("55270001", "55270002", "55270003", "55270004", "55270005"),
						"ots"   		=> array("55270"),
						// "cos"   		=> array("56110001", "56110002"),
						"cos"   		=> array("56110"),
						// "dvs"   		=> array("56120001"),
						"dvs"   		=> array("56120"),
						// "obs"   		=> array("56130001", "56130002", "56130003"),
						"obs"   		=> array("56130"),
						// "cms"   		=> array("56211001", "56211002", "56211003", "56211004", "56211005", "56212001", "56212002"),
						"cms"   		=> array("56211", "56212"),
						// "bd"   			=> array("56311001"),
						"bd"   			=> array("56311"),
						// "pts"   		=> array("56312001"),
						"pts"   		=> array("56312"),
						// "td"   			=> array("56313001"),
						"td"   			=> array("56313"),
						// "otc"   		=> array("56400001"),
						"otc"   		=> array("56400"),
						// "daa"   		=> array("51100001", "51100002", "51100003", "51100004", "51100005", "51100006", "51100007", "51100008", "51100009", "51100011"),
						"daa"   		=> array("51100"),
						// "dola"   		=> array("51210001", "51210002", "51210003", "51210004", "51210005"),
						"dola"   		=> array("51210"),
						// "amo"   		=> array("51300001"),
						"amo"   		=> array("51300"),
						// "oie"   		=> array("61100001", "61100002", "61100003"),
						"oie"   		=> array("61100"),
						// "gloa"   		=> array("61200001", "61200002", "61200003", "61200004", "61200005"),
						"gloa"   		=> array("61200"),
						// "oi"   			=> array("61310001", "61310002"),
						"oi"   			=> array("61310"),
						// "lpdp"   		=> array("61400001"),
						"lpdp"   		=> array("61400"),
						// "ox"   			=> array("61500001"),
						"ox"   			=> array("61500"),
						// "fiac"   		=> array("62100001", "62100002", "62100003"),
						"fiac"   		=> array("62100"),
						// "fc"   			=> array("62200001", "62200002", "62200003", "62200004", "62200005"),
						"fc"   			=> array("62200"),
						// "fco"   		=> array("62300001"),
						"fco"   		=> array("62300"),
						// "itoci"   		=> array("81000001", "81000002"),
						"itoci"   		=> array("81000"),
						// "ocin"   		=> array("82000001")
						"ocin"   		=> array("82000")
						);

		foreach ($hasil->result_array() as $row) {
			$nature = $row['NATURE'];
			$nature1 = substr($row['NATURE'],0,5);
			$nature2 = substr($row['NATURE'],0,6);

			if(in_array($nature1, $category_arr['revenue'])){
				$data['revenue'][] = $row;
			}elseif(in_array($nature1, $category_arr['ppob'])){
				$data['ppob'][] = $row;
			}elseif(in_array($nature1, $category_arr['remmitance'])){
				$data['remmitance'][] = $row;
			}elseif(in_array($nature1, $category_arr['fsi'])){
				$data['fsi'][] = $row;
			}elseif(in_array($nature1, $category_arr['tlc'])){
				$data['tlc'][] = $row;
			}elseif(in_array($nature1, $category_arr['trp'])){
				$data['trp'][] = $row;
			}elseif(in_array($nature1, $category_arr['cico'])){
				$data['cico'][] = $row;
			}elseif(in_array($nature1, $category_arr['pjld'])){
				$data['pjld'][] = $row;
			}elseif(in_array($nature1, $category_arr['pjls'])){
				$data['pjls'][] = $row;
			}elseif(in_array($nature1, $category_arr['otr'])){
				$data['otr'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmb'])){
				$data['rmb'][] = $row;
			}elseif(in_array($nature1, $category_arr['rme'])){
				$data['rme'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmf'])){
				$data['rmf'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmo'])){
				$data['rmo'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmm'])){
				$data['rmm'][] = $row;
			}elseif(in_array($nature1, $category_arr['rma'])){
				$data['rma'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmd'])){
				$data['rmd'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmc'])){
				$data['rmc'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmfs'])){
				$data['rmfs'][] = $row;
			}elseif(in_array($nature1, $category_arr['rms'])){
				$data['rms'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmma'])){
				$data['rmma'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmi'])){
				$data['rmi'][] = $row;
			}elseif(in_array($nature1, $category_arr['rmsf'])){
				$data['rmsf'][] = $row;
			}elseif(in_array($nature1, $category_arr['os'])){
				$data['os'][] = $row;
			}elseif(in_array($nature1, $category_arr['cicosc'])){
				$data['cicosc'][] = $row;
			}elseif(in_array($nature1, $category_arr['ccae'])){
				$data['ccae'][] = $row;
			}elseif(in_array($nature1, $category_arr['oh'])){
				$data['oh'][] = $row;
			}elseif(in_array($nature1, $category_arr['orm'])){
				$data['orm'][] = $row;
			}elseif(in_array($nature1, $category_arr['otc'])){
				$data['otc'][] = $row;
			}elseif(in_array($nature1, $category_arr['dir'])){
				$data['dir'][] = $row;
			}elseif(in_array($nature1, $category_arr['pe'])){
				$data['pe'][] = $row;
			}elseif(in_array($nature1, $category_arr['icn'])){
				$data['icn'][] = $row;
			}elseif(in_array($nature1, $category_arr['pa'])){
				$data['pa'][] = $row;
			}elseif(in_array($nature1, $category_arr['oa'])){
				$data['oa'][] = $row;
			}elseif(in_array($nature1, $category_arr['eb'])){
				$data['eb'][] = $row;
			}elseif(in_array($nature2, $category_arr['mse'])){
				$data['mse'][] = $row;
			}elseif(in_array($nature2, $category_arr['mct'])){
				$data['mct'][] = $row;
			}elseif(in_array($nature2, $category_arr['pt'])){
				$data['pt'][] = $row;
			}elseif(in_array($nature2, $category_arr['part'])){
				$data['part'][] = $row;
			}elseif(in_array($nature2, $category_arr['rc'])){
				$data['rc'][] = $row;
			}elseif(in_array($nature2, $category_arr['cicodis'])){
				$data['cicodis'][] = $row;
			}elseif(in_array($nature1, $category_arr['eas'])){
				$data['eas'][] = $row;
			}elseif(in_array($nature1, $category_arr['spo'])){
				$data['spo'][] = $row;
			}elseif(in_array($nature1, $category_arr['copc'])){
				$data['copc'][] = $row;
			}elseif(in_array($nature1, $category_arr['adver'])){
				$data['adver'][] = $row;
			}elseif(in_array($nature1, $category_arr['nemp'])){
				$data['nemp'][] = $row;
			}elseif(in_array($nature1, $category_arr['advp'])){
				$data['advp'][] = $row;
			}elseif(in_array($nature1, $category_arr['nem'])){
				$data['nem'][] = $row;
			}elseif(in_array($nature1, $category_arr['adva'])){
				$data['adva'][] = $row;
			}elseif(in_array($nature1, $category_arr['csa'])){
				$data['csa'][] = $row;
			}elseif(in_array($nature1, $category_arr['clp'])){
				$data['clp'][] = $row;
			}elseif(in_array($nature1, $category_arr['ome'])){
				$data['ome'][] = $row;
			}elseif(in_array($nature1, $category_arr['gae'])){
				$data['gae'][] = $row;
			}elseif(in_array($nature2, $category_arr['rb'])){
				$data['rb'][] = $row;
			}elseif(in_array($nature2, $category_arr['rob'])){
				$data['rob'][] = $row;
			}elseif(in_array($nature2, $category_arr['roe'])){
				$data['roe'][] = $row;
			}elseif(in_array($nature2, $category_arr['ref'])){
				$data['ref'][] = $row;
			}elseif(in_array($nature2, $category_arr['rd'])){
				$data['rd'][] = $row;
			}elseif(in_array($nature1, $category_arr['tat'])){
				$data['tat'][] = $row;
			}elseif(in_array($nature1, $category_arr['sac'])){
				$data['sac'][] = $row;
			}elseif(in_array($nature1, $category_arr['sm'])){
				$data['sm'][] = $row;
			}elseif(in_array($nature1, $category_arr['utl'])){
				$data['utl'][] = $row;
			}elseif(in_array($nature1, $category_arr['ue'])){
				$data['ue'][] = $row;
			}elseif(in_array($nature1, $category_arr['uw'])){
				$data['uw'][] = $row;
			}elseif(in_array($nature1, $category_arr['otas'])){
				$data['otas'][] = $row;
			}elseif(in_array($nature1, $category_arr['paf'])){
				$data['paf'][] = $row;
			}elseif(in_array($nature1, $category_arr['adme'])){
				$data['adme'][] = $row;
			}elseif(in_array($nature1, $category_arr['pfs'])){
				$data['pfs'][] = $row;
			}elseif(in_array($nature1, $category_arr['ins'])){
				$data['ins'][] = $row;
			}elseif(in_array($nature1, $category_arr['tar'])){
				$data['tar'][] = $row;
			}elseif(in_array($nature1, $category_arr['tad'])){
				$data['tad'][] = $row;
			}elseif(in_array($nature1, $category_arr['mare'])){
				$data['mare'][] = $row;
			}elseif(in_array($nature1, $category_arr['dac'])){
				$data['dac'][] = $row;
			}elseif(in_array($nature1, $category_arr['mer'])){
				$data['mer'][] = $row;
			}elseif(in_array($nature1, $category_arr['er'])){
				$data['er'][] = $row;
			}elseif(in_array($nature1, $category_arr['ots'])){
				$data['ots'][] = $row;
			}elseif(in_array($nature1, $category_arr['cos'])){
				$data['cos'][] = $row;
			}elseif(in_array($nature1, $category_arr['dvs'])){
				$data['dvs'][] = $row;
			}elseif(in_array($nature1, $category_arr['obs'])){
				$data['obs'][] = $row;
			}elseif(in_array($nature1, $category_arr['cms'])){
				$data['cms'][] = $row;
			}elseif(in_array($nature1, $category_arr['bd'])){
				$data['bd'][] = $row;
			}elseif(in_array($nature1, $category_arr['pts'])){
				$data['pts'][] = $row;
			}elseif(in_array($nature1, $category_arr['td'])){
				$data['td'][] = $row;
			}elseif(in_array($nature1, $category_arr['otc'])){
				$data['otc'][] = $row;
			}elseif(in_array($nature1, $category_arr['daa'])){
				$data['daa'][] = $row;
			}elseif(in_array($nature1, $category_arr['dola'])){
				$data['dola'][] = $row;
			}elseif(in_array($nature1, $category_arr['amo'])){
				$data['amo'][] = $row;
			}elseif(in_array($nature1, $category_arr['oie'])){
				$data['oie'][] = $row;
			}elseif(in_array($nature1, $category_arr['gloa'])){
				$data['gloa'][] = $row;
			}elseif(in_array($nature1, $category_arr['oi'])){
				$data['oi'][] = $row;
			}elseif(in_array($nature1, $category_arr['lpdp'])){
				$data['lpdp'][] = $row;
			}elseif(in_array($nature1, $category_arr['ox'])){
				$data['ox'][] = $row;
			}elseif(in_array($nature1, $category_arr['fiac'])){
				$data['fiac'][] = $row;
			}elseif(in_array($nature1, $category_arr['fc'])){
				$data['fc'][] = $row;
			}elseif(in_array($nature1, $category_arr['fco'])){
				$data['fco'][] = $row;
			}elseif(in_array($nature1, $category_arr['itoci'])){
				$data['itoci'][] = $row;
			}elseif(in_array($nature1, $category_arr['ocin'])){
				$data['ocin'][] = $row;
			}
			
		}

		$numrow = 10;
		foreach ($data[$category] as $row) {
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrow.':J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrow++;
		}

			$total = $numrow;
			$total += 0;
			$sum   = $numrow - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$total, "Subtotal Merchant");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$total, '=SUM(E10:E'.$sum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$total, '=SUM(F10:F'.$sum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$total, '=SUM(G10:G'.$sum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$total, '=SUM(G10:H'.$sum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$total, '=SUM(G10:I'.$sum.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$total, '=SUM(G10:J'.$sum.')');
			$excel->getActiveSheet()->getStyle('D'.$total.':J'.$total)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$total.':J'.$total)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$total1 = $total;
			$total1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$total1, "SUBTOTAL MERCHANT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$total1, '=SUM(E10:E'.$numrow.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$total1, '=SUM(F10:F'.$numrow.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$total1, '=SUM(G10:G'.$numrow.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$total1, '=SUM(G10:H'.$numrow.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$total1, '=SUM(G10:I'.$numrow.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$total1, '=SUM(G10:J'.$numrow.')/2');
			$excel->getActiveSheet()->getStyle('D'.$total1.':J'.$total1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$total1.':J'.$total1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numppob = $total1;
		$numppob += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numppob, "42000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numppob, "PPOB");
		$excel->getActiveSheet()->getStyle('C'.$numppob)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numppob)->applyFromArray($style_sub);

		$category = "ppob";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowppob = $numppob;
		$numrowppob += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowppob, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowppob, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowppob)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowppob, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowppob, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowppob, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowppob, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowppob, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowppob, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowppob, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowppob.':J'.$numrowppob)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowppob++;
		}

			$totppob = $numrowppob;
			$totppob += 0;
			$sumppob = $numppob+=1;
			$sumppob1 = $numrowppob - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totppob, "Subtotal PPOB");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totppob, '=SUM(E'.$sumppob.':E'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totppob, '=SUM(F'.$sumppob.':F'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totppob, '=SUM(G'.$sumppob.':G'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totppob, '=SUM(H'.$sumppob.':H'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totppob, '=SUM(I'.$sumppob.':I'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totppob, '=SUM(J'.$sumppob.':J'.$sumppob1.')');
			$excel->getActiveSheet()->getStyle('D'.$totppob.':J'.$totppob)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totppob.':J'.$totppob)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totppob1 = $totppob;
			$totppob1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totppob1, "Subtotal PPOB");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totppob1, '=SUM(E'.$sumppob.':E'.$numrowppob.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totppob1, '=SUM(F'.$sumppob.':F'.$numrowppob.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totppob1, '=SUM(G'.$sumppob.':G'.$numrowppob.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totppob1, '=SUM(H'.$sumppob.':H'.$numrowppob.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totppob1, '=SUM(I'.$sumppob.':I'.$numrowppob.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totppob1, '=SUM(J'.$sumppob.':J'.$numrowppob.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totppob1.':J'.$totppob1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totppob1.':J'.$totppob1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrem = $totppob1;
		$numrem += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrem, "43000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrem, "REMMITANCE");
		$excel->getActiveSheet()->getStyle('C'.$numrem)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrem)->applyFromArray($style_sub);

		$category = "remmitance";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrem = $numrem;
		$numrowrem += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrem, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrem, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrem)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrem, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrem, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrem, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrem, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrem, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrem, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrem, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrem.':J'.$numrowrem)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrem++;
		}

			$totrem = $numrowrem;
			$totrem += 0;
			$sumrem = $numrem+=1;
			$sumrem1 = $numrowrem - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrem, "SUBTOTAL REMMITANCE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrem, '=SUM(E'.$sumrem.':E'.$sumrem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrem, '=SUM(F'.$sumrem.':F'.$sumrem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrem, '=SUM(G'.$sumrem.':G'.$sumrem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrem, '=SUM(H'.$sumrem.':H'.$sumrem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrem, '=SUM(I'.$sumrem.':I'.$sumrem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrem, '=SUM(J'.$sumrem.':J'.$sumrem1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrem.':J'.$totrem)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrem.':J'.$totrem)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numfsi = $totrem;
		$numfsi += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numfsi, "44000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numfsi, "FINANCIAL SERVICE INCLUSION");
		$excel->getActiveSheet()->getStyle('C'.$numfsi)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numfsi)->applyFromArray($style_sub);

		$category = "fsi";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowfsi = $numfsi;
		$numrowfsi += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfsi, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowfsi, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowfsi)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowfsi, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfsi, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowfsi, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowfsi, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowfsi, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowfsi, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowfsi, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowfsi.':J'.$numrowfsi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowfsi++;
		}

			$totfsi = $numrowfsi;
			$totfsi += 0;
			$sumfsi = $numfsi+=1;
			$sumfsi1 = $numrowfsi - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfsi, "Subtotal");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfsi, '=SUM(E'.$sumfsi.':E'.$sumfsi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfsi, '=SUM(F'.$sumfsi.':F'.$sumfsi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfsi, '=SUM(G'.$sumfsi.':G'.$sumfsi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfsi, '=SUM(H'.$sumfsi.':H'.$sumfsi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfsi, '=SUM(I'.$sumfsi.':I'.$sumfsi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfsi, '=SUM(J'.$sumfsi.':J'.$sumfsi1.')');
			$excel->getActiveSheet()->getStyle('D'.$totfsi.':J'.$totfsi)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfsi.':J'.$totfsi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totfsi1 = $totfsi;
			$totfsi1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfsi1, "SUBTOTAL FINANCIAL SERVICE INCLUSION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfsi1, '=SUM(E'.$sumfsi.':E'.$numrowfsi.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfsi1, '=SUM(F'.$sumfsi.':F'.$numrowfsi.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfsi1, '=SUM(G'.$sumfsi.':G'.$numrowfsi.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfsi1, '=SUM(H'.$sumfsi.':H'.$numrowfsi.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfsi1, '=SUM(I'.$sumfsi.':I'.$numrowfsi.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfsi1, '=SUM(J'.$sumfsi.':J'.$numrowfsi.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totfsi1.':J'.$totfsi1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfsi1.':J'.$totfsi1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totfsi2 = $totfsi1;
			$totfsi2 += 1;
			$sumfsi1 = $numfsi+=1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfsi2, "SUBTOTAL CONNECTION & SUBSCRIPTION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfsi2, '=E'.$totfsi1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfsi2, '=F'.$totfsi1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfsi2, '=G'.$totfsi1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfsi2, '=H'.$totfsi1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfsi2, '=I'.$totfsi1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfsi2, '=J'.$totfsi1);
			$excel->getActiveSheet()->getStyle('D'.$totfsi2.':J'.$totfsi2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfsi2.':J'.$totfsi2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtlc = $totfsi2;
		$numtlc += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtlc, "45000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtlc, "TELECOMMUNICATION");
		$excel->getActiveSheet()->getStyle('C'.$numtlc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtlc)->applyFromArray($style_sub);

		$category = "tlc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowtlc = $numtlc;
		$numrowtlc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowtlc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtlc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowtlc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowtlc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtlc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowtlc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowtlc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowtlc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowtlc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtlc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowtlc.':J'.$numrowtlc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowtlc++;
		}

			$tottlc = $numrowtlc;
			$tottlc += 0;
			$sumtlc = $numtlc+=1;
			$sumtlc1 = $numrowtlc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottlc, "SUBTOTAL");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottlc, '=SUM(E'.$sumtlc.':E'.$sumtlc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottlc, '=SUM(F'.$sumtlc.':F'.$sumtlc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottlc, '=SUM(G'.$sumtlc.':G'.$sumtlc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottlc, '=SUM(H'.$sumtlc.':H'.$sumtlc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottlc, '=SUM(I'.$sumtlc.':I'.$sumtlc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottlc, '=SUM(J'.$sumtlc.':J'.$sumtlc1.')');
			$excel->getActiveSheet()->getStyle('D'.$tottlc.':J'.$tottlc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottlc.':J'.$tottlc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tottlc1 = $tottlc;
			$tottlc1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottlc1, "SUBTOTAL TELECOMMUNICATION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottlc1, '=E'.$tottlc);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottlc1, '=F'.$tottlc);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottlc1, '=G'.$tottlc);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottlc1, '=H'.$tottlc);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottlc1, '=I'.$tottlc);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottlc1, '=J'.$tottlc);
			$excel->getActiveSheet()->getStyle('D'.$tottlc1.':J'.$tottlc1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottlc1.':J'.$tottlc1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tottlc2 = $tottlc1;
			$tottlc2 += 1;
			$sumtlc1 = $numtlc+=1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottlc2, "SUBTOTAL TELECOMUNICATION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottlc2, '=E'.$tottlc1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottlc2, '=F'.$tottlc1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottlc2, '=G'.$tottlc1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottlc2, '=H'.$tottlc1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottlc2, '=I'.$tottlc1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottlc2, '=J'.$tottlc1);
			$excel->getActiveSheet()->getStyle('D'.$tottlc2.':J'.$tottlc2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottlc2.':J'.$tottlc2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtrp = $tottlc2;
		$numtrp += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtrp, "46000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtrp, "TRANSPORT");
		$excel->getActiveSheet()->getStyle('C'.$numtrp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtrp)->applyFromArray($style_sub);

		$category = "trp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowtrp = $numtrp;
		$numrowtrp += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowtrp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtrp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowtrp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowtrp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtrp, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowtrp, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowtrp, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowtrp, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowtrp, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtrp, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowtrp.':J'.$numrowtrp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowtrp++;
		}

			$tottrp = $numrowtrp;
			$tottrp += 0;
			$sumtrp = $numtrp+=1;
			$sumtrp1 = $numrowtrp - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottrp, "SUBTOTAL TRANSPORT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottrp, '=SUM(E'.$sumtrp.':E'.$sumtrp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottrp, '=SUM(F'.$sumtrp.':F'.$sumtrp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottrp, '=SUM(G'.$sumtrp.':G'.$sumtrp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottrp, '=SUM(H'.$sumtrp.':H'.$sumtrp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottrp, '=SUM(I'.$sumtrp.':I'.$sumtrp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottrp, '=SUM(J'.$sumtrp.':J'.$sumtrp1.')');
			$excel->getActiveSheet()->getStyle('D'.$tottrp.':J'.$tottrp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottrp.':J'.$tottrp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcico = $tottrp;
		$numcico += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcico, "47000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcico, "CICO");
		$excel->getActiveSheet()->getStyle('C'.$numcico)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcico)->applyFromArray($style_sub);

		$category = "cico";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcico = $numcico;
		$numrowcico += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcico, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcico, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcico)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcico, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcico, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcico, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcico, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcico, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcico, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcico, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcico.':J'.$numrowcico)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcico++;
		}

			$totcico = $numrowcico;
			$totcico += 0;
			$sumcico = $numcico+=1;
			$sumcico1 = $numrowcico - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcico, "SUBTOTAL CICO");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcico, '=SUM(E'.$sumcico.':E'.$sumcico1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcico, '=SUM(F'.$sumcico.':F'.$sumcico1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcico, '=SUM(G'.$sumcico.':G'.$sumcico1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcico, '=SUM(H'.$sumcico.':H'.$sumcico1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcico, '=SUM(I'.$sumcico.':I'.$sumcico1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcico, '=SUM(J'.$sumcico.':J'.$sumcico1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcico.':J'.$totcico)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcico.':J'.$totcico)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpjld = $totcico;
		$numpjld += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpjld, "48000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpjld, "OTHERS Revenue");
		$excel->getActiveSheet()->getStyle('C'.$numpjld)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpjld)->applyFromArray($style_sub);

		$numpjld1 = $numpjld;
		$numpjld1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpjld1, "48000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpjld1, "PENJUALAN DEVICE");
		$excel->getActiveSheet()->getStyle('C'.$numpjld1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpjld1)->applyFromArray($style_sub);

		$category = "pjld";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpjld = $numpjld1;
		$numrowpjld += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpjld, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpjld, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpjld)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpjld, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpjld, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpjld, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpjld, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpjld, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpjld, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpjld, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpjld.':J'.$numrowpjld)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpjld++;
		}

		$numpjls = $numrowpjld;
		$numpjls += 0;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpjls, "48200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpjls, "PENJUALAN STICKER");
		$excel->getActiveSheet()->getStyle('C'.$numpjls)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpjls)->applyFromArray($style_sub);

		$category = "pjls";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpjls = $numpjls;
		$numrowpjls += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpjls, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpjls, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpjls)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpjls, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpjls, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpjls, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpjls, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpjls, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpjls, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpjls, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpjls.':J'.$numrowpjls)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpjls++;
		}

		$numotr = $numrowpjls;
		$numotr += 0;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numotr, "48300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numotr, "OTHER REVENUES");
		$excel->getActiveSheet()->getStyle('C'.$numotr)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numotr)->applyFromArray($style_sub);

		$category = "otr";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowotr = $numotr;
		$numrowotr += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowotr, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowotr, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowotr)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowotr, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowotr, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowotr, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowotr, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowotr, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowotr, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowotr, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowotr.':J'.$numrowotr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowotr++;
		}

			$tototr = $numrowotr;
			$tototr += 0;
			$sumotr = $numpjld1+=1;
			$sumotr1 = $numrowotr - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototr, "SUBTOTAL OTHERS REVENUE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototr, '=SUM(E'.$sumotr.':E'.$sumotr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototr, '=SUM(F'.$sumotr.':F'.$sumotr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototr, '=SUM(G'.$sumotr.':G'.$sumotr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tototr, '=SUM(H'.$sumotr.':H'.$sumotr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tototr, '=SUM(I'.$sumotr.':I'.$sumotr1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tototr, '=SUM(J'.$sumotr.':J'.$sumotr1.')');
			$excel->getActiveSheet()->getStyle('D'.$tototr.':J'.$tototr)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototr.':J'.$tototr)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tottotrev = $tototr;
			$tottotrev += 2;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottotrev, "TOTAL REVENUE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottotrev, '=E'.$tottrp.'+E'.$tottlc2.'+E'.$totfsi2.'+E'.$totrem.'+E'.$totppob1.'+E'.$total1.'+E'.$totcico.'+E'.$tototr);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottotrev, '=F'.$tottrp.'+F'.$tottlc2.'+F'.$totfsi2.'+F'.$totrem.'+F'.$totppob1.'+F'.$total1.'+F'.$totcico.'+F'.$tototr);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottotrev, '=G'.$tottrp.'+G'.$tottlc2.'+G'.$totfsi2.'+G'.$totrem.'+G'.$totppob1.'+G'.$total1.'+G'.$totcico.'+G'.$tototr);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottotrev, '=H'.$tottrp.'+H'.$tottlc2.'+H'.$totfsi2.'+H'.$totrem.'+H'.$totppob1.'+H'.$total1.'+H'.$totcico.'+H'.$tototr);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottotrev, '=I'.$tottrp.'+I'.$tottlc2.'+I'.$totfsi2.'+I'.$totrem.'+I'.$totppob1.'+I'.$total1.'+I'.$totcico.'+I'.$tototr);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottotrev, '=J'.$tottrp.'+J'.$tottlc2.'+J'.$totfsi2.'+J'.$totrem.'+J'.$totppob1.'+J'.$total1.'+J'.$totcico.'+J'.$tototr);
			$excel->getActiveSheet()->getStyle('D'.$tottotrev)->applyFromArray($style_sub);
			$excel->getActiveSheet()->getStyle('E'.$tottotrev.':J'.$tottotrev)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottotrev.':J'.$tottotrev)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmb = $tottotrev;
		$numrmb += 2;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmb, "50000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmb, "EXPENSE");
		$excel->getActiveSheet()->getStyle('C'.$numrmb)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmb)->applyFromArray($style_sub);

		$numrmb1 = $numrmb;
		$numrmb1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmb1, "52000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmb1, "OPERATIONS AND MAINTENANCE EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numrmb1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmb1)->applyFromArray($style_sub);

		$numrmb2 = $numrmb1;
		$numrmb2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmb2, "52100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmb2, "REPAIR AND MAINTENANCE CHARGES");
		$excel->getActiveSheet()->getStyle('C'.$numrmb2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmb2)->applyFromArray($style_sub);

		$numrmb3 = $numrmb2;
		$numrmb3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmb3, "52110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmb3, "OFFICE BUILDING, EQUIPMENT, AND FURNITURE R&M EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numrmb3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmb3)->applyFromArray($style_sub);

		$numrmb4 = $numrmb3;
		$numrmb4 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmb4, "52111000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmb4, "R&M BUILDING");
		$excel->getActiveSheet()->getStyle('C'.$numrmb4)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmb4)->applyFromArray($style_sub);

		$category = "rmb";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmb = $numrmb4;
		$numrowrmb += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmb, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmb, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmb)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmb, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmb, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmb, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmb, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmb, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmb, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmb, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmb.':J'.$numrowrmb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmb++;
		}

			$totrmb = $numrowrmb;
			$totrmb += 0;
			$sumrmb = $numrmb4+=1;
			$sumrmb1 = $numrowrmb - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmb, "Subtotal  R&M Building");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmb, '=SUM(E'.$sumrmb.':E'.$sumrmb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmb, '=SUM(F'.$sumrmb.':F'.$sumrmb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmb, '=SUM(G'.$sumrmb.':G'.$sumrmb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmb, '=SUM(H'.$sumrmb.':H'.$sumrmb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmb, '=SUM(I'.$sumrmb.':I'.$sumrmb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmb, '=SUM(J'.$sumrmb.':J'.$sumrmb1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmb.':J'.$totrmb)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmb.':J'.$totrmb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrme = $totrmb;
		$numrme += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrme, "52112000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrme, "R&M EQUIPMENT");
		$excel->getActiveSheet()->getStyle('C'.$numrme)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrme)->applyFromArray($style_sub);

		$category = "rme";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrme = $numrme;
		$numrowrme += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrme, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrme, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrme)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrme, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrme, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrme, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrme, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrme, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrme, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrme, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrme.':J'.$numrowrme)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrme++;
		}

			$totrme = $numrowrme;
			$totrme += 0;
			$sumrme = $numrme+=1;
			$sumrme1 = $numrowrme - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrme, "Subtotal  R&M Building");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrme, '=SUM(E'.$sumrme.':E'.$sumrme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrme, '=SUM(F'.$sumrme.':F'.$sumrme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrme, '=SUM(G'.$sumrme.':G'.$sumrme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrme, '=SUM(H'.$sumrme.':H'.$sumrme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrme, '=SUM(I'.$sumrme.':I'.$sumrme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrme, '=SUM(J'.$sumrme.':J'.$sumrme1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrme.':J'.$totrme)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrme.':J'.$totrme)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmf = $totrme;
		$numrmf += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmf, "52113000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmf, "R&M FURNITURE & FIXTURE");
		$excel->getActiveSheet()->getStyle('C'.$numrmf)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmf)->applyFromArray($style_sub);

		$category = "rmf";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmf = $numrmf;
		$numrowrmf += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmf, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmf, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmf)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmf, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmf, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmf, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmf, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmf, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmf, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmf, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmf.':J'.$numrowrmf)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmf++;
		}

			$totrmf = $numrowrmf;
			$totrmf += 0;
			$sumrmf = $numrmf+=1;
			$sumrmf1 = $numrowrmf - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmf, "Subtotal  R&M Furniture & Fixture");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmf, '=SUM(E'.$sumrmf.':E'.$sumrmf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmf, '=SUM(F'.$sumrmf.':F'.$sumrmf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmf, '=SUM(G'.$sumrmf.':G'.$sumrmf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmf, '=SUM(H'.$sumrmf.':H'.$sumrmf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmf, '=SUM(I'.$sumrmf.':I'.$sumrmf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmf, '=SUM(J'.$sumrmf.':J'.$sumrmf1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmf.':J'.$totrmf)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmf.':J'.$totrmf)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmo = $totrmf;
		$numrmo += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmo, "52114000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmo, "R&M OPERATIONAL CARS");
		$excel->getActiveSheet()->getStyle('C'.$numrmo)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmo)->applyFromArray($style_sub);

		$category = "rmo";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmo = $numrmo;
		$numrowrmo += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmo, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmo, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmo)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmo, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmo, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmo, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmo, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmo, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmo, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmo, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmo.':J'.$numrowrmo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmo++;
		}

			$totrmo = $numrowrmo;
			$totrmo += 0;
			$sumrmo = $numrmo+=1;
			$sumrmo1 = $numrowrmo - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmo, "Subtotal  R&M Operational Cars");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmo, '=SUM(E'.$sumrmo.':E'.$sumrmo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmo, '=SUM(F'.$sumrmo.':F'.$sumrmo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmo, '=SUM(G'.$sumrmo.':G'.$sumrmo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmo, '=SUM(H'.$sumrmo.':H'.$sumrmo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmo, '=SUM(I'.$sumrmo.':I'.$sumrmo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmo, '=SUM(J'.$sumrmo.':J'.$sumrmo1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmo.':J'.$totrmo)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmo.':J'.$totrmo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totrmo1 = $totrmo;
			$totrmo1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmo1, "SUBTOTAL OFFICE BUILDING, EQUIPMENT, AND FURNITURE R&M EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmo1, '=SUM(E'.$sumrmb.':E'.$totrmo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmo1, '=SUM(F'.$sumrmb.':F'.$totrmo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmo1, '=SUM(G'.$sumrmb.':G'.$totrmo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmo1, '=SUM(H'.$sumrmb.':H'.$totrmo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmo1, '=SUM(I'.$sumrmb.':I'.$totrmo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmo1, '=SUM(J'.$sumrmb.':J'.$totrmo.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totrmo1.':J'.$totrmo1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmo1.':J'.$totrmo1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmm = $totrmo1;
		$numrmm += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmm, "52220000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmm, "IT SYSTEM SUPPORT R&M EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numrmm)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmm)->applyFromArray($style_sub);

		$numrmm1 = $numrmm;
		$numrmm1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmm1, "52211000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmm1, "R&M MANAGEMENT INFORMATION SYSTEM");
		$excel->getActiveSheet()->getStyle('C'.$numrmm1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmm1)->applyFromArray($style_sub);

		$category = "rmm";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmm = $numrmm1;
		$numrowrmm += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmm, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmm, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmm)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmm, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmm, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmm, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmm, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmm, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmm, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmm, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmm.':J'.$numrowrmm)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmm++;
		}

			$totrmm = $numrowrmm;
			$totrmm += 0;
			$sumrmm = $numrmm1+=1;
			$sumrmm1 = $numrowrmm - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmm, "Subtotal  R&M Management Information System");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmm, '=SUM(E'.$sumrmm.':E'.$sumrmm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmm, '=SUM(F'.$sumrmm.':F'.$sumrmm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmm, '=SUM(G'.$sumrmm.':G'.$sumrmm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmm, '=SUM(H'.$sumrmm.':H'.$sumrmm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmm, '=SUM(I'.$sumrmm.':I'.$sumrmm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmm, '=SUM(J'.$sumrmm.':J'.$sumrmm1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmm.':J'.$totrmm)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmm.':J'.$totrmm)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrma = $totrmm;
		$numrma += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrma, "52212000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrma, "R&M OFFICE AUTOMATION (WAN/LAN)");
		$excel->getActiveSheet()->getStyle('C'.$numrma)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrma)->applyFromArray($style_sub);

		$category = "rma";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrma = $numrma;
		$numrowrma += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrma, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrma, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrma)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrma, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrma, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrma, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrma, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrma, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrma, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrma, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrma.':J'.$numrowrma)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrma++;
		}

			$totrma = $numrowrma;
			$totrma += 0;
			$sumrma = $numrma+=1;
			$sumrma1 = $numrowrma - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrma, "Subtotal  R&M Office Automation");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrma, '=SUM(E'.$sumrma.':E'.$sumrma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrma, '=SUM(F'.$sumrma.':F'.$sumrma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrma, '=SUM(G'.$sumrma.':G'.$sumrma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrma, '=SUM(H'.$sumrma.':H'.$sumrma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrma, '=SUM(I'.$sumrma.':I'.$sumrma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrma, '=SUM(J'.$sumrma.':J'.$sumrma1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrma.':J'.$totrma)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrma.':J'.$totrma)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmd = $totrma;
		$numrmd += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmd, "52213000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmd, "R&M DATA COMMUNICATION SYSTEM");
		$excel->getActiveSheet()->getStyle('C'.$numrmd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmd)->applyFromArray($style_sub);

		$category = "rmd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmd = $numrmd;
		$numrowrmd += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmd, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmd, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmd, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmd, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmd, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmd, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmd.':J'.$numrowrmd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmd++;
		}

			$totrmd = $numrowrmd;
			$totrmd += 0;
			$sumrmd = $numrmd+=1;
			$sumrmd1 = $numrowrmd - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmd, "Subtotal R&M Data Communication System");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmd, '=SUM(E'.$sumrmd.':E'.$sumrmd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmd, '=SUM(F'.$sumrmd.':F'.$sumrmd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmd, '=SUM(G'.$sumrmd.':G'.$sumrmd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmd, '=SUM(H'.$sumrmd.':H'.$sumrmd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmd, '=SUM(I'.$sumrmd.':I'.$sumrmd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmd, '=SUM(J'.$sumrmd.':J'.$sumrmd1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmd.':J'.$totrmd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmd.':J'.$totrmd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmc = $totrmd;
		$numrmc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmc, "52214000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmc, "R&M CUSTOMER CARE SYSTEM");
		$excel->getActiveSheet()->getStyle('C'.$numrmc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmc)->applyFromArray($style_sub);

		$category = "rmc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmc = $numrmc;
		$numrowrmc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmc.':J'.$numrowrmc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmc++;
		}

			$totrmc = $numrowrmc;
			$totrmc += 0;
			$sumrmc = $numrmc+=1;
			$sumrmc1 = $numrowrmc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmc, "Subtotal R&M Customer Care System");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmc, '=SUM(E'.$sumrmc.':E'.$sumrmc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmc, '=SUM(F'.$sumrmc.':F'.$sumrmc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmc, '=SUM(G'.$sumrmc.':G'.$sumrmc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmc, '=SUM(H'.$sumrmc.':H'.$sumrmc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmc, '=SUM(I'.$sumrmc.':I'.$sumrmc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmc, '=SUM(J'.$sumrmc.':J'.$sumrmc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmc.':J'.$totrmc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmc.':J'.$totrmc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmfs = $totrmc;
		$numrmfs += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmfs, "52115000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmfs, "R&M FEATURES SYSTEM");
		$excel->getActiveSheet()->getStyle('C'.$numrmfs)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmfs)->applyFromArray($style_sub);

		$category = "rmfs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmfs = $numrmfs;
		$numrowrmfs += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmfs, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmfs, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmfs)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmfs, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmfs, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmfs, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmfs, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmfs, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmfs, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmfs, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmfs.':J'.$numrowrmfs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmfs++;
		}

			$totrmfs = $numrowrmfs;
			$totrmfs += 0;
			$sumrmfs = $numrmfs+=1;
			$sumrmfs1 = $numrowrmfs - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmfs, "Subtotal R&M Features System");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmfs, '=SUM(E'.$sumrmfs.':E'.$sumrmfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmfs, '=SUM(F'.$sumrmfs.':F'.$sumrmfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmfs, '=SUM(G'.$sumrmfs.':G'.$sumrmfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmfs, '=SUM(H'.$sumrmfs.':H'.$sumrmfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmfs, '=SUM(I'.$sumrmfs.':I'.$sumrmfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmfs, '=SUM(J'.$sumrmfs.':J'.$sumrmfs1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmfs.':J'.$totrmfs)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmfs.':J'.$totrmfs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrms = $totrmfs;
		$numrms += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrms, "52115000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrms, "R&M FEATURES SYSTEM");
		$excel->getActiveSheet()->getStyle('C'.$numrms)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrms)->applyFromArray($style_sub);

		$category = "rms";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrms = $numrms;
		$numrowrms += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrms, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrms, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrms)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrms, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrms, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrms, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrms, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrms, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrms, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrms, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrms.':J'.$numrowrms)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrms++;
		}

			$totrms = $numrowrms;
			$totrms += 0;
			$sumrms = $numrms+=1;
			$sumrms1 = $numrowrms - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrms, "Subtotal R&M Financial System");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrms, '=SUM(E'.$sumrms.':E'.$sumrms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrms, '=SUM(F'.$sumrms.':F'.$sumrms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrms, '=SUM(G'.$sumrms.':G'.$sumrms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrms, '=SUM(H'.$sumrms.':H'.$sumrms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrms, '=SUM(I'.$sumrms.':I'.$sumrms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrms, '=SUM(J'.$sumrms.':J'.$sumrms1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrms.':J'.$totrms)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrms.':J'.$totrms)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmma = $totrms;
		$numrmma += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmma, "52117000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmma, "R&M Merchant Apps and System");
		$excel->getActiveSheet()->getStyle('C'.$numrmma)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmma)->applyFromArray($style_sub);

		$category = "rmma";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmma = $numrmma;
		$numrowrmma += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmma, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmma, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmma)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmma, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmma, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmma, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmma, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmma, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmma, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmma, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmma.':J'.$numrowrmma)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmma++;
		}

			$totrmma = $numrowrmma;
			$totrmma += 0;
			$sumrmma = $numrmma+=1;
			$sumrmma1 = $numrowrmma - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmma, "Subtotal R&M R&M Merchant Apps and System");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmma, '=SUM(E'.$sumrmma.':E'.$sumrmma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmma, '=SUM(F'.$sumrmma.':F'.$sumrmma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmma, '=SUM(G'.$sumrmma.':G'.$sumrmma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmma, '=SUM(H'.$sumrmma.':H'.$sumrmma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmma, '=SUM(I'.$sumrmma.':I'.$sumrmma1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmma, '=SUM(J'.$sumrmma.':J'.$sumrmma1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmma.':G'.$totrmma)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmma.':G'.$totrmma)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totrmma1 = $totrmma;
			$totrmma1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmma1, "SUBTOTAL IT SYSTEM SUPPORT R&M EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmma1, '=SUM(E'.$sumrmm.':E'.$totrmma.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmma1, '=SUM(F'.$sumrmm.':F'.$totrmma.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmma1, '=SUM(G'.$sumrmm.':G'.$totrmma.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmma1, '=SUM(H'.$sumrmm.':H'.$totrmma.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmma1, '=SUM(I'.$sumrmm.':I'.$totrmma.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmma1, '=SUM(J'.$sumrmm.':J'.$totrmma.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totrmma1.':J'.$totrmma1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmma1.':J'.$totrmma1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmi = $totrmma1;
		$numrmi += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmi, "52120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmi, "SUPPORTING FACILITIES R&M EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numrmi)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmi)->applyFromArray($style_sub);

		$numrmi1 = $numrmi;
		$numrmi1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmi1, "52121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmi1, "R&M Testing EQUIPMENT");
		$excel->getActiveSheet()->getStyle('C'.$numrmi1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmi1)->applyFromArray($style_sub);

		$category = "rmi";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmi = $numrmi1;
		$numrowrmi += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmi, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmi, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmi)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmi, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmi, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmi, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmi, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmi, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmi, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmi, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmi.':J'.$numrowrmi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmi++;
		}

			$totrmi = $numrowrmi;
			$totrmi += 0;
			$sumrmi = $numrmi1+=1;
			$sumrmi1 = $numrowrmi - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmi, "Subtotal R&M Infrastructure Measurement Equipment");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmi, '=SUM(E'.$sumrmi.':E'.$sumrmi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmi, '=SUM(F'.$sumrmi.':F'.$sumrmi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmi, '=SUM(G'.$sumrmi.':G'.$sumrmi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmi, '=SUM(H'.$sumrmi.':H'.$sumrmi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmi, '=SUM(I'.$sumrmi.':I'.$sumrmi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmi, '=SUM(J'.$sumrmi.':J'.$sumrmi1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmi.':J'.$totrmi)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmi.':J'.$totrmi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrmsf = $totrmi;
		$numrmsf += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrmsf, "52122000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrmsf, "R&M Support EQUIPMENT");
		$excel->getActiveSheet()->getStyle('C'.$numrmsf)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrmsf)->applyFromArray($style_sub);

		$category = "rmsf";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrmsf = $numrmsf;
		$numrowrmsf += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrmsf, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrmsf, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrmsf)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrmsf, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrmsf, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrmsf, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrmsf, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrmsf, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrmsf, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrmsf, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrmsf.':J'.$numrowrmsf)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrmsf++;
		}

			$totrmsf = $numrowrmsf;
			$totrmsf += 0;
			$sumrmsf = $numrmsf+=1;
			$sumrmsf1 = $numrowrmsf - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmsf, "Subtotal R&M Supporting Facilities Measurement Eqp.");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmsf, '=SUM(E'.$sumrmsf.':E'.$sumrmsf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmsf, '=SUM(F'.$sumrmsf.':F'.$sumrmsf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmsf, '=SUM(G'.$sumrmsf.':G'.$sumrmsf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmsf, '=SUM(H'.$sumrmsf.':H'.$sumrmsf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmsf, '=SUM(I'.$sumrmsf.':I'.$sumrmsf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmsf, '=SUM(J'.$sumrmsf.':J'.$sumrmsf1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmsf.':J'.$totrmsf)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmsf.':J'.$totrmsf)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totrmsf1 = $totrmsf;
			$totrmsf1 += 1;
			$sumrmsf = $numrmi1 - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrmsf1, "SUBTOTAL SUPPORTING FACILITIES R&M EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrmsf1, '=SUM(E'.$sumrmsf.':E'.$totrmsf.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrmsf1, '=SUM(F'.$sumrmsf.':F'.$totrmsf.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrmsf1, '=SUM(G'.$sumrmsf.':G'.$totrmsf.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrmsf1, '=SUM(H'.$sumrmsf.':H'.$totrmsf.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrmsf1, '=SUM(I'.$sumrmsf.':I'.$totrmsf.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrmsf1, '=SUM(J'.$sumrmsf.':J'.$totrmsf.')');
			$excel->getActiveSheet()->getStyle('D'.$totrmsf1.':J'.$totrmsf1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrmsf1.':J'.$totrmsf1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numos = $totrmsf1;
		$numos += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numos, "52130000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numos, "Operation Cost");
		$excel->getActiveSheet()->getStyle('C'.$numos)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numos)->applyFromArray($style_sub);

		$numos1 = $numos;
		$numos1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numos1, "52131000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numos1, "Payment System Cost");
		$excel->getActiveSheet()->getStyle('C'.$numos1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numos1)->applyFromArray($style_sub);

		$category = "os";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowos= $numos1;
		$numrowos += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowos, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowos, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowos)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowos, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowos, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowos, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowos, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowos, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowos, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowos, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowos.':J'.$numrowos)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowos++;
		}

			$totos = $numrowos;
			$totos += 0;
			$sumos = $numos1+=1;
			$sumos1 = $numrowos - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totos, "Subtotal Payment System Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totos, '=SUM(E'.$sumos.':E'.$sumos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totos, '=SUM(F'.$sumos.':F'.$sumos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totos, '=SUM(G'.$sumos.':G'.$sumos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totos, '=SUM(H'.$sumos.':H'.$sumos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totos, '=SUM(I'.$sumos.':I'.$sumos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totos, '=SUM(J'.$sumos.':J'.$sumos1.')');
			$excel->getActiveSheet()->getStyle('D'.$totos.':J'.$totos)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totos.':J'.$totos)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcicosc = $totos;
		$numcicosc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcicosc, "52132000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcicosc, "CICO system Cost");
		$excel->getActiveSheet()->getStyle('C'.$numcicosc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcicosc)->applyFromArray($style_sub);

		$category = "cicosc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcicosc= $numcicosc;
		$numrowcicosc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcicosc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcicosc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcicosc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcicosc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcicosc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcicosc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcicosc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcicosc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcicosc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcicosc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcicosc.':J'.$numrowcicosc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcicosc++;
		}

			$totcicosc = $numrowcicosc;
			$totcicosc += 0;
			$sumcicosc = $numcicosc+=1;
			$sumcicosc1 = $numrowcicosc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcicosc, "Subtotal CICO system Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcicosc, '=SUM(E'.$sumcicosc.':E'.$sumcicosc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcicosc, '=SUM(F'.$sumcicosc.':F'.$sumcicosc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcicosc, '=SUM(G'.$sumcicosc.':G'.$sumcicosc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcicosc, '=SUM(H'.$sumcicosc.':H'.$sumcicosc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcicosc, '=SUM(I'.$sumcicosc.':I'.$sumcicosc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcicosc, '=SUM(J'.$sumcicosc.':J'.$sumcicosc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcicosc.':J'.$totcicosc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcicosc.':J'.$totcicosc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numccae = $totcicosc;
		$numccae += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numccae, "52133000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numccae, "CALL CENTER AND EKYC");
		$excel->getActiveSheet()->getStyle('C'.$numccae)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numccae)->applyFromArray($style_sub);

		$category = "ccae";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowccae= $numccae;
		$numrowccae += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowccae, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowccae, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowccae)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowccae, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowccae, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowccae, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowccae, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowccae, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowccae, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowccae, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowccae.':J'.$numrowccae)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowccae++;
		}

			$totccae = $numrowccae;
			$totccae += 0;
			$sumccae = $numccae+=1;
			$sumccae1 = $numrowccae - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totccae, "Subtotal CALL CENTER AND EKYC");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totccae, '=SUM(E'.$sumccae.':E'.$sumccae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totccae, '=SUM(F'.$sumccae.':F'.$sumccae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totccae, '=SUM(G'.$sumccae.':G'.$sumccae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totccae, '=SUM(H'.$sumccae.':H'.$sumccae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totccae, '=SUM(I'.$sumccae.':I'.$sumccae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totccae, '=SUM(J'.$sumccae.':J'.$sumccae1.')');
			$excel->getActiveSheet()->getStyle('D'.$totccae.':J'.$totccae)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totccae.':J'.$totccae)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numoh = $totccae;
		$numoh += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoh, "52134000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoh, "OPERATION HANDLING");
		$excel->getActiveSheet()->getStyle('C'.$numoh)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoh)->applyFromArray($style_sub);

		$category = "oh";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowoh= $numoh;
		$numrowoh += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowoh, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoh, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowoh)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowoh, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoh, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowoh, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowoh, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowoh, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowoh, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoh, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowoh.':J'.$numrowoh)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowoh++;
		}

			$totoh = $numrowoh;
			$totoh += 0;
			$sumoh = $numoh+=1;
			$sumoh1 = $numrowoh - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoh, "Subtotal CALL CENTER AND EKYC");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoh, '=SUM(E'.$sumoh.':E'.$sumoh1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoh, '=SUM(F'.$sumoh.':F'.$sumoh1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoh, '=SUM(G'.$sumoh.':G'.$sumoh1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoh, '=SUM(H'.$sumoh.':H'.$sumoh1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoh, '=SUM(I'.$sumoh.':I'.$sumoh1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoh, '=SUM(J'.$sumoh.':J'.$sumoh1.')');
			$excel->getActiveSheet()->getStyle('D'.$totoh.':J'.$totoh)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoh.':J'.$totoh)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numorm = $totoh;
		$numorm += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numorm, "52135000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numorm, "OPERATION & IT RELATED MANPOWER");
		$excel->getActiveSheet()->getStyle('C'.$numorm)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numorm)->applyFromArray($style_sub);

		$category = "orm";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numroworm= $numorm;
		$numroworm += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numroworm, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numroworm, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numroworm)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numroworm, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numroworm, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numroworm, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numroworm, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numroworm, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numroworm, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numroworm, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numroworm.':J'.$numroworm)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numroworm++;
		}

			$totorm = $numroworm;
			$totorm += 0;
			$sumorm = $numorm+=1;
			$sumorm1 = $numroworm - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totorm, "Subtotal CALL CENTER AND EKYC");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totorm, '=SUM(E'.$sumorm.':E'.$sumorm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totorm, '=SUM(F'.$sumorm.':F'.$sumorm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totorm, '=SUM(G'.$sumorm.':G'.$sumorm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totorm, '=SUM(H'.$sumorm.':H'.$sumorm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totorm, '=SUM(I'.$sumorm.':I'.$sumorm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totorm, '=SUM(J'.$sumorm.':J'.$sumorm1.')');
			$excel->getActiveSheet()->getStyle('D'.$totorm.':J'.$totorm)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totorm.':J'.$totorm)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numotc = $totorm;
		$numotc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numotc, "52135000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numotc, "OPERATION & IT RELATED MANPOWER");
		$excel->getActiveSheet()->getStyle('C'.$numotc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numotc)->applyFromArray($style_sub);

		$category = "otc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowotc= $numotc;
		$numrowotc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowotc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowotc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowotc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowotc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowotc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowotc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowotc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowotc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowotc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowotc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowotc.':J'.$numrowotc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowotc++;
		}

			$tototc = $numrowotc;
			$tototc += 0;
			$sumotc = $numotc+=1;
			$sumotc1 = $numrowotc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototc, "Subtotal CALL CENTER AND EKYC");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototc, '=SUM(E'.$sumotc.':E'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototc, '=SUM(F'.$sumotc.':F'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototc, '=SUM(G'.$sumotc.':G'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tototc, '=SUM(H'.$sumotc.':H'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tototc, '=SUM(I'.$sumotc.':I'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tototc, '=SUM(J'.$sumotc.':J'.$sumotc1.')');
			$excel->getActiveSheet()->getStyle('D'.$tototc.':J'.$tototc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototc.':J'.$tototc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdir = $tototc;
		$numdir += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdir, "52135000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdir, "OPERATION & IT RELATED MANPOWER");
		$excel->getActiveSheet()->getStyle('C'.$numdir)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdir)->applyFromArray($style_sub);

		$category = "dir";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdir= $numdir;
		$numrowdir += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdir, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdir, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdir)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdir, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdir, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdir, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdir, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowdir, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowdir, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowdir, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdir.':J'.$numrowdir)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdir++;
		}

			$totdir = $numrowdir;
			$totdir += 0;
			$sumdir = $numdir+=1;
			$sumdir1 = $numrowdir - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdir, "Subtotal CALL CENTER AND EKYC");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdir, '=SUM(E'.$sumdir.':E'.$sumdir1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdir, '=SUM(F'.$sumdir.':F'.$sumdir1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdir, '=SUM(G'.$sumdir.':G'.$sumdir1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdir, '=SUM(H'.$sumdir.':H'.$sumdir1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdir, '=SUM(I'.$sumdir.':I'.$sumdir1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdir, '=SUM(J'.$sumdir.':J'.$sumdir1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdir.':J'.$totdir)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdir.':J'.$totdir)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totdir1 = $totdir;
			$totdir1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdir1, "SUBTOTAL SUPPORTING FACILITIES R&M EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdir1, '=SUM(E'.$sumos.':E'.$totdir.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdir1, '=SUM(F'.$sumos.':F'.$totdir.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdir1, '=SUM(G'.$sumos.':G'.$totdir.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdir1, '=SUM(H'.$sumos.':H'.$totdir.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdir1, '=SUM(I'.$sumos.':I'.$totdir.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdir1, '=SUM(J'.$sumos.':J'.$totdir.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totdir1.':J'.$totdir1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdir1.':J'.$totdir1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totdir2 = $totdir1;
			$totdir2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdir2, "SUBTOTAL REPAIR AND MAINTENANCE CHARGES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdir2, '=E'.$totrmsf1.'+E'.$totrmma1.'+E'.$totrmo1.'+E'.$totdir1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdir2, '=F'.$totrmsf1.'+F'.$totrmma1.'+F'.$totrmo1.'+F'.$totdir1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdir2, '=G'.$totrmsf1.'+G'.$totrmma1.'+G'.$totrmo1.'+G'.$totdir1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdir2, '=H'.$totrmsf1.'+H'.$totrmma1.'+H'.$totrmo1.'+H'.$totdir1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdir2, '=I'.$totrmsf1.'+I'.$totrmma1.'+I'.$totrmo1.'+I'.$totdir1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdir2, '=J'.$totrmsf1.'+J'.$totrmma1.'+J'.$totrmo1.'+J'.$totdir1);
			$excel->getActiveSheet()->getStyle('D'.$totdir2.':J'.$totdir2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdir2.':J'.$totdir2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totdir3 = $totdir2;
			$totdir3 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdir3, "SUBTOTAL OPERATIONS AND MAINTENANCE EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdir3, '=E'.$totdir2);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdir3, '=F'.$totdir2);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdir3, '=G'.$totdir2);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdir3, '=H'.$totdir2);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdir3, '=I'.$totdir2);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdir3, '=J'.$totdir2);
			$excel->getActiveSheet()->getStyle('D'.$totdir3.':J'.$totdir3)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdir3.':J'.$totdir3)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpe = $totdir3;
		$numpe += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpe, "53000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpe, "PERSONNEL EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numpe)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpe)->applyFromArray($style_sub);

		$numpe1 = $numpe;
		$numpe1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpe1, "53100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpe1, "SALARIES & ALLOWANCES");
		$excel->getActiveSheet()->getStyle('C'.$numpe1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpe1)->applyFromArray($style_sub);

		$numpe2 = $numpe1;
		$numpe2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpe2, "53110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpe2, "SALARIES AND INCENTIVES");
		$excel->getActiveSheet()->getStyle('C'.$numpe2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpe2)->applyFromArray($style_sub);

		$numpe3 = $numpe2;
		$numpe3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpe3, "53111000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpe3, "SALARIES");
		$excel->getActiveSheet()->getStyle('C'.$numpe3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpe3)->applyFromArray($style_sub);

		$category = "pe";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpe= $numpe3;
		$numrowpe += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpe, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpe, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpe)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpe, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpe, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpe, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpe, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpe, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpe, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpe, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpe.':J'.$numrowpe)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpe++;
		}

			$totpe = $numrowpe;
			$totpe += 0;
			$sumpe = $numpe3+=1;
			$sumpe1 = $numrowpe - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpe, "Subtotal Salaries");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpe, '=SUM(E'.$sumpe.':E'.$sumpe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpe, '=SUM(F'.$sumpe.':F'.$sumpe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpe, '=SUM(G'.$sumpe.':G'.$sumpe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpe, '=SUM(H'.$sumpe.':H'.$sumpe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpe, '=SUM(I'.$sumpe.':I'.$sumpe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpe, '=SUM(J'.$sumpe.':J'.$sumpe1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpe.':J'.$totpe)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpe.':J'.$totpe)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numicn = $totpe;
		$numicn += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numicn, "53112000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numicn, "INCENTIVES");
		$excel->getActiveSheet()->getStyle('C'.$numicn)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numicn)->applyFromArray($style_sub);

		$category = "icn";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowicn= $numicn;
		$numrowicn += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowicn, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowicn, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowicn)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowicn, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowicn, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowicn, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowicn, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowicn, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowicn, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowicn, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowicn.':J'.$numrowicn)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowicn++;
		}

			$toticn = $numrowicn;
			$toticn += 0;
			$sumicn = $numicn+=1;
			$sumicn1 = $numrowicn - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toticn, "Subtotal Incentives");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toticn, '=SUM(E'.$sumicn.':E'.$sumicn1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toticn, '=SUM(F'.$sumicn.':F'.$sumicn1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toticn, '=SUM(G'.$sumicn.':G'.$sumicn1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toticn, '=SUM(H'.$sumicn.':H'.$sumicn1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toticn, '=SUM(I'.$sumicn.':I'.$sumicn1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toticn, '=SUM(J'.$sumicn.':J'.$sumicn1.')');
			$excel->getActiveSheet()->getStyle('D'.$toticn.':J'.$toticn)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toticn.':J'.$toticn)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$toticn1 = $toticn;
			$toticn1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toticn1, "SUBTOTAL SALARIES AND INCENTIVES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toticn1, '=SUM(E'.$sumpe.':E'.$toticn.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toticn1, '=SUM(F'.$sumpe.':F'.$toticn.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toticn1, '=SUM(G'.$sumpe.':G'.$toticn.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toticn1, '=SUM(H'.$sumpe.':H'.$toticn.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toticn1, '=SUM(I'.$sumpe.':I'.$toticn.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toticn1, '=SUM(J'.$sumpe.':J'.$toticn.')/2');
			$excel->getActiveSheet()->getStyle('D'.$toticn1.':J'.$toticn1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toticn1.':J'.$toticn1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpa = $toticn1;
		$numpa += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpa, "53120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpa, "PERSONNEL ALLOWANCES");
		$excel->getActiveSheet()->getStyle('C'.$numpa)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpa)->applyFromArray($style_sub);

		$numpa1 = $numpa;
		$numpa1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpa1, "53121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpa1, "PERIODICALLY ALLOWANCES");
		$excel->getActiveSheet()->getStyle('C'.$numpa1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpa1)->applyFromArray($style_sub);

		$category = "pa";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpa= $numpa1;
		$numrowpa += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpa, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpa, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpa)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpa, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpa, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpa, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpa, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpa, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpa, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpa, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpa.':J'.$numrowpa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpa++;
		}

			$totpa = $numrowpa;
			$totpa += 0;
			$sumpa = $numpa1+=1;
			$sumpa1 = $numrowpa - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpa, "Subtotal Incentives");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpa, '=SUM(E'.$sumpa.':E'.$sumpa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpa, '=SUM(F'.$sumpa.':F'.$sumpa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpa, '=SUM(G'.$sumpa.':G'.$sumpa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpa, '=SUM(H'.$sumpa.':H'.$sumpa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpa, '=SUM(I'.$sumpa.':I'.$sumpa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpa, '=SUM(J'.$sumpa.':J'.$sumpa1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpa.':J'.$totpa)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpa.':J'.$totpa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numoa = $totpa;
		$numoa += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoa, "53122000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoa, "OTHERS ALLOWANCES");
		$excel->getActiveSheet()->getStyle('C'.$numoa)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoa)->applyFromArray($style_sub);

		$category = "oa";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowoa= $numoa;
		$numrowoa += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowoa, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoa, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowoa)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowoa, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoa, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowoa, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowoa, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowoa, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowoa, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoa, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowoa.':J'.$numrowoa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowoa++;
		}

			$totoa = $numrowoa;
			$totoa += 0;
			$sumoa = $numoa+=1;
			$sumoa1 = $numrowoa - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoa, "Subtotal Others Allowances");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoa, '=SUM(E'.$sumoa.':E'.$sumoa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoa, '=SUM(F'.$sumoa.':F'.$sumoa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoa, '=SUM(G'.$sumoa.':G'.$sumoa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoa, '=SUM(H'.$sumoa.':H'.$sumoa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoa, '=SUM(I'.$sumoa.':I'.$sumoa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoa, '=SUM(J'.$sumoa.':J'.$sumoa1.')');
			$excel->getActiveSheet()->getStyle('D'.$totoa.':J'.$totoa)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoa.':J'.$totoa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totoa1 = $totoa;
			$totoa1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoa1, "SUBTOTAL PERSONNEL ALLOWANCES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoa1, '=SUM(E'.$sumpa.':E'.$totoa.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoa1, '=SUM(F'.$sumpa.':F'.$totoa.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoa1, '=SUM(G'.$sumpa.':G'.$totoa.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoa1, '=SUM(H'.$sumpa.':H'.$totoa.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoa1, '=SUM(I'.$sumpa.':I'.$totoa.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoa1, '=SUM(J'.$sumpa.':J'.$totoa.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totoa1.':J'.$totoa1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoa1.':J'.$totoa1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totoa2 = $totoa1;
			$totoa2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoa2, "SUBTOTAL SALARIES & ALLOWANCES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoa2, '=E'.$totoa1.'+E'.$toticn1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoa2, '=F'.$totoa1.'+F'.$toticn1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoa2, '=G'.$totoa1.'+G'.$toticn1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoa2, '=H'.$totoa1.'+H'.$toticn1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoa2, '=I'.$totoa1.'+I'.$toticn1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoa2, '=J'.$totoa1.'+J'.$toticn1);
			$excel->getActiveSheet()->getStyle('D'.$totoa2.':J'.$totoa2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoa2.':J'.$totoa2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numeb = $totoa2;
		$numeb += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numeb, "53200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numeb, "EMPLOYEE BENEFIT");
		$excel->getActiveSheet()->getStyle('C'.$numeb)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numeb)->applyFromArray($style_sub);

		$numeb1 = $numeb;
		$numeb1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numeb1, "53210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numeb1, "LONG TERM BENEFITS");
		$excel->getActiveSheet()->getStyle('C'.$numeb1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numeb1)->applyFromArray($style_sub);

		$category = "eb";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numroweb= $numeb1;
		$numroweb += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numroweb, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numroweb, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numroweb)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numroweb, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numroweb, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numroweb, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numroweb, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numroweb, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numroweb, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numroweb, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numroweb.':J'.$numroweb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numroweb++;
		}

			$toteb = $numroweb;
			$toteb += 0;
			$sumeb = $numeb1+=1;
			$sumeb1 = $numroweb - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toteb, "Subtotal Long Term Benefits");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toteb, '=SUM(E'.$sumeb.':E'.$sumeb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toteb, '=SUM(F'.$sumeb.':F'.$sumeb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toteb, '=SUM(G'.$sumeb.':G'.$sumeb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toteb, '=SUM(H'.$sumeb.':H'.$sumeb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toteb, '=SUM(I'.$sumeb.':I'.$sumeb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toteb, '=SUM(J'.$sumeb.':J'.$sumeb1.')');
			$excel->getActiveSheet()->getStyle('D'.$toteb.':J'.$toteb)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toteb.':J'.$toteb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$toteb1 = $toteb;
			$toteb1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toteb1, "SUBTOTAL EMPLOYEE BENEFIT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toteb1, '=E'.$toteb);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toteb1, '=F'.$toteb);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toteb1, '=G'.$toteb);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toteb1, '=H'.$toteb);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toteb1, '=I'.$toteb);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toteb1, '=J'.$toteb);
			$excel->getActiveSheet()->getStyle('D'.$toteb1.':J'.$toteb1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toteb1.':J'.$toteb1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$toteb2 = $toteb1;
			$toteb2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toteb2, "SUBTOTAL PERSONNEL EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toteb2, '=E'.$toteb1.'+E'.$totoa2);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toteb2, '=F'.$toteb1.'+F'.$totoa2);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toteb2, '=G'.$toteb1.'+G'.$totoa2);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toteb2, '=H'.$toteb1.'+H'.$totoa2);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toteb2, '=I'.$toteb1.'+I'.$totoa2);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toteb2, '=J'.$toteb1.'+J'.$totoa2);
			$excel->getActiveSheet()->getStyle('D'.$toteb2.':J'.$toteb2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toteb2.':J'.$toteb2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$nummse = $toteb2;
		$nummse += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummse, "54000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummse, "MARKETING & SALES EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$nummse)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummse)->applyFromArray($style_sub);

		$nummse1 = $nummse;
		$nummse1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummse1, "54100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummse1, "SALES SUPPORT");
		$excel->getActiveSheet()->getStyle('C'.$nummse1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummse1)->applyFromArray($style_sub);

		$nummse2 = $nummse1;
		$nummse2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummse2, "54110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummse2, "ECOSYSTEM COST");
		$excel->getActiveSheet()->getStyle('C'.$nummse2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummse2)->applyFromArray($style_sub);

		$nummse3 = $nummse2;
		$nummse3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummse3, "54111000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummse3, "MERCHANT COST");
		$excel->getActiveSheet()->getStyle('C'.$nummse3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummse3)->applyFromArray($style_sub);

		$nummse4 = $nummse3;
		$nummse4 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummse4, "54111100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummse4, "MERCHANT COST - RELATED PARTY");
		$excel->getActiveSheet()->getStyle('C'.$nummse4)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummse4)->applyFromArray($style_sub);

		$category = "mse";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowmse= $nummse4;
		$numrowmse += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmse, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmse, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmse)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmse, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmse, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmse, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmse, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowmse, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowmse, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowmse, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmse.':J'.$numrowmse)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmse++;
		}

		$nummct = $numrowmse;
		$nummct += 0;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummct, "54111200");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummct, "MERCHANT COST - THIRD PARTY");
		$excel->getActiveSheet()->getStyle('C'.$nummct)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummct)->applyFromArray($style_sub);

		$category = "mct";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowmct= $nummct;
		$numrowmct += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmct, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmct, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmct)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmct, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmct, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmct, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmct, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowmct, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowmct, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowmct, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmct.':J'.$numrowmct)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmct++;
		}

			$totmct = $numrowmct;
			$totmct += 0;
			$summct = $nummse4+=1;
			$summct1 = $numrowmct - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmct, "Subtotal Merchant Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmct, '=SUM(E'.$summct.':E'.$summct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmct, '=SUM(F'.$summct.':F'.$summct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmct, '=SUM(G'.$summct.':G'.$summct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totmct, '=SUM(H'.$summct.':H'.$summct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totmct, '=SUM(I'.$summct.':I'.$summct1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totmct, '=SUM(J'.$summct.':J'.$summct1.')');
			$excel->getActiveSheet()->getStyle('D'.$totmct.':J'.$totmct)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmct.':J'.$totmct)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpt = $totmct;
		$numpt += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpt, "54111300");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpt, "PUBLIC TRANSPORT");
		$excel->getActiveSheet()->getStyle('C'.$numpt)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpt)->applyFromArray($style_sub);

		$category = "pt";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpt= $numpt;
		$numrowpt += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpt, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpt, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpt)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpt, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpt, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpt, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpt, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpt, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpt, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpt, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpt.':J'.$numrowpt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpt++;
		}

			$totpt = $numrowpt;
			$totpt += 0;
			$sumpt = $numpt+=1;
			$sumpt1 = $numrowpt - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpt, "Subtotal Merchant Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpt, '=SUM(E'.$sumpt.':E'.$sumpt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpt, '=SUM(F'.$sumpt.':F'.$sumpt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpt, '=SUM(G'.$sumpt.':G'.$sumpt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpt, '=SUM(H'.$sumpt.':H'.$sumpt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpt, '=SUM(I'.$sumpt.':I'.$sumpt1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpt, '=SUM(J'.$sumpt.':J'.$sumpt1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpt.':J'.$totpt)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpt.':J'.$totpt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpart = $totpt;
		$numpart += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpart, "54111400");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpart, "PARTNERSHIP");
		$excel->getActiveSheet()->getStyle('C'.$numpart)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpart)->applyFromArray($style_sub);

		$category = "part";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpart= $numpart;
		$numrowpart += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpart, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpart, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpart)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpart, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpart, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpart, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpart, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpart, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpart, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpart, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpart.':J'.$numrowpart)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpart++;
		}

			$totpart = $numrowpart;
			$totpart += 0;
			$sumpart = $numpart+=1;
			$sumpart1 = $numrowpart - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpart, "Subtotal Partnership");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpart, '=SUM(E'.$sumpart.':E'.$sumpart1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpart, '=SUM(F'.$sumpart.':F'.$sumpart1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpart, '=SUM(G'.$sumpart.':G'.$sumpart1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpart, '=SUM(H'.$sumpart.':H'.$sumpart1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpart, '=SUM(I'.$sumpart.':I'.$sumpart1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpart, '=SUM(J'.$sumpart.':J'.$sumpart1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpart.':J'.$totpart)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpart.':J'.$totpart)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrc = $totpart;
		$numrc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrc, "54111500");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrc, "RESELLER COST");
		$excel->getActiveSheet()->getStyle('C'.$numrc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrc)->applyFromArray($style_sub);

		$category = "rc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrc= $numrc;
		$numrowrc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrc.':J'.$numrowrc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrc++;
		}

			$totrc = $numrowrc;
			$totrc += 0;
			$sumrc = $numrc+=1;
			$sumrc1 = $numrowrc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrc, "Subtotal Reseller Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrc, '=SUM(E'.$sumrc.':E'.$sumrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrc, '=SUM(F'.$sumrc.':F'.$sumrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrc, '=SUM(G'.$sumrc.':G'.$sumrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrc, '=SUM(H'.$sumrc.':H'.$sumrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrc, '=SUM(I'.$sumrc.':I'.$sumrc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrc, '=SUM(J'.$sumrc.':J'.$sumrc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrc.':J'.$totrc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrc.':J'.$totrc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcicodis = $totrc;
		$numcicodis += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcicodis, "54111600");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcicodis, "CICO");
		$excel->getActiveSheet()->getStyle('C'.$numcicodis)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcicodis)->applyFromArray($style_sub);

		$category = "cicodis";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcicodis= $numcicodis;
		$numrowcicodis += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcicodis, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcicodis, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcicodis)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcicodis, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcicodis, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcicodis, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcicodis, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcicodis, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcicodis, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcicodis, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcicodis.':J'.$numrowcicodis)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcicodis++;
		}

			$totcicodis = $numrowcicodis;
			$totcicodis += 0;
			$sumcicodis = $numcicodis+=1;
			$sumcicodis1 = $numrowcicodis - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcicodis, "Subtotal Outlet Cost");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcicodis, '=SUM(E'.$sumcicodis.':E'.$sumcicodis1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcicodis, '=SUM(F'.$sumcicodis.':F'.$sumcicodis1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcicodis, '=SUM(G'.$sumcicodis.':G'.$sumcicodis1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcicodis, '=SUM(H'.$sumcicodis.':H'.$sumcicodis1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcicodis, '=SUM(I'.$sumcicodis.':I'.$sumcicodis1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcicodis, '=SUM(J'.$sumcicodis.':J'.$sumcicodis1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcicodis.':J'.$totcicodis)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcicodis.':J'.$totcicodis)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcicodis1 = $totcicodis;
			$totcicodis1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcicodis1, "SUBTOTAL DISTRIBUTION COST");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcicodis1, '=SUM(E'.$summct.':E'.$totcicodis.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcicodis1, '=SUM(F'.$summct.':F'.$totcicodis.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcicodis1, '=SUM(G'.$summct.':G'.$totcicodis.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcicodis1, '=SUM(H'.$summct.':H'.$totcicodis.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcicodis1, '=SUM(I'.$summct.':I'.$totcicodis.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcicodis1, '=SUM(J'.$summct.':J'.$totcicodis.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totcicodis1.':J'.$totcicodis1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcicodis1.':J'.$totcicodis1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numeas = $totcicodis1;
		$numeas += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numeas, "54120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numeas, "EVENT AND SPONSORSHIPS");
		$excel->getActiveSheet()->getStyle('C'.$numeas)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numeas)->applyFromArray($style_sub);

		$numeas1 = $numeas;
		$numeas1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numeas1, "54121000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numeas1, "EVENT");
		$excel->getActiveSheet()->getStyle('C'.$numeas1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numeas1)->applyFromArray($style_sub);

		$category = "eas";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numroweas= $numeas1;
		$numroweas += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numroweas, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numroweas, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numroweas)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numroweas, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numroweas, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numroweas, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numroweas, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numroweas, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numroweas, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numroweas, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numroweas.':J'.$numroweas)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numroweas++;
		}

			$toteas = $numroweas;
			$toteas += 0;
			$sumeas = $numeas1+=1;
			$sumeas1 = $numroweas - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toteas, "Subtotal Event");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toteas, '=SUM(E'.$sumeas.':E'.$sumeas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toteas, '=SUM(F'.$sumeas.':F'.$sumeas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toteas, '=SUM(G'.$sumeas.':G'.$sumeas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toteas, '=SUM(H'.$sumeas.':H'.$sumeas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toteas, '=SUM(I'.$sumeas.':I'.$sumeas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toteas, '=SUM(J'.$sumeas.':J'.$sumeas1.')');
			$excel->getActiveSheet()->getStyle('D'.$toteas.':J'.$toteas)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toteas.':J'.$toteas)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$toteas1 = $toteas;
			$toteas1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toteas1, "SUBTOTAL DISTRIBUTION COST");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toteas1, '=SUM(E'.$summct.':E'.$toteas.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toteas1, '=SUM(F'.$summct.':F'.$toteas.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toteas1, '=SUM(G'.$summct.':G'.$toteas.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toteas1, '=SUM(H'.$summct.':H'.$toteas.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toteas1, '=SUM(I'.$summct.':I'.$toteas.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toteas1, '=SUM(J'.$summct.':J'.$toteas.')/2');
			$excel->getActiveSheet()->getStyle('D'.$toteas1.':J'.$toteas1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toteas1.':J'.$toteas1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numspo = $toteas1;
		$numspo += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numspo, "54122000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numspo, "SPONSORSHIP");
		$excel->getActiveSheet()->getStyle('C'.$numspo)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numspo)->applyFromArray($style_sub);

		$category = "spo";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowspo= $numspo;
		$numrowspo += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowspo, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowspo, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowspo)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowspo, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowspo, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowspo, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowspo, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowspo, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowspo, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowspo, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowspo.':J'.$numrowspo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowspo++;
		}

			$totspo = $numrowspo;
			$totspo += 0;
			$sumspo = $numspo+=1;
			$sumspo1 = $numrowspo - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totspo, "Subtotal Sponsorship");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totspo, '=SUM(E'.$sumspo.':E'.$sumspo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totspo, '=SUM(F'.$sumspo.':F'.$sumspo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totspo, '=SUM(G'.$sumspo.':G'.$sumspo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totspo, '=SUM(H'.$sumspo.':H'.$sumspo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totspo, '=SUM(I'.$sumspo.':I'.$sumspo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totspo, '=SUM(J'.$sumspo.':J'.$sumspo1.')');
			$excel->getActiveSheet()->getStyle('D'.$totspo.':J'.$totspo)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totspo.':J'.$totspo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totspo1 = $totspo;
			$totspo1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totspo1, "SUBTOTAL EVENT AND SPONSORSHIPS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totspo1, '=SUM(E'.$sumeas.':E'.$totspo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totspo1, '=SUM(F'.$sumeas.':F'.$totspo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totspo1, '=SUM(G'.$sumeas.':G'.$totspo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totspo1, '=SUM(H'.$sumeas.':H'.$totspo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totspo1, '=SUM(I'.$sumeas.':I'.$totspo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totspo1, '=SUM(J'.$sumeas.':J'.$totspo.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totspo1.':J'.$totspo1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totspo1.':J'.$totspo1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcopc = $totspo1;
		$numcopc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcopc, "54130000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcopc, "COST OF PROSPECTIVE CUSTOMER");
		$excel->getActiveSheet()->getStyle('C'.$numcopc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcopc)->applyFromArray($style_sub);

		$category = "copc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcopc= $numcopc;
		$numrowcopc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcopc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcopc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcopc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcopc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcopc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcopc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcopc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcopc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcopc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcopc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcopc.':J'.$numrowcopc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcopc++;
		}

			$totcopc = $numrowcopc;
			$totcopc += 0;
			$sumcopc = $numcopc+=1;
			$sumcopc1 = $numrowcopc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcopc, "SUBTOTAL COST OF PROSPECTIVE CUSTOMER");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcopc, '=SUM(E'.$sumcopc.':E'.$sumcopc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcopc, '=SUM(F'.$sumcopc.':F'.$sumcopc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcopc, '=SUM(G'.$sumcopc.':G'.$sumcopc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcopc, '=SUM(H'.$sumcopc.':H'.$sumcopc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcopc, '=SUM(I'.$sumcopc.':I'.$sumcopc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcopc, '=SUM(J'.$sumcopc.':J'.$sumcopc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcopc.':J'.$totcopc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcopc.':J'.$totcopc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcopc1 = $totcopc;
			$totcopc1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcopc1, "SUBTOTAL SALES SUPPORT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcopc1, '=E'.$totcopc.'+E'.$totspo1.'+E'.$totcicodis1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcopc1, '=F'.$totcopc.'+F'.$totspo1.'+F'.$totcicodis1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcopc1, '=G'.$totcopc.'+G'.$totspo1.'+G'.$totcicodis1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcopc1, '=H'.$totcopc.'+H'.$totspo1.'+H'.$totcicodis1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcopc1, '=I'.$totcopc.'+I'.$totspo1.'+I'.$totcicodis1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcopc1, '=J'.$totcopc.'+J'.$totspo1.'+J'.$totcicodis1);
			$excel->getActiveSheet()->getStyle('D'.$totcopc1.':J'.$totcopc1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcopc1.':J'.$totcopc1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numadver = $totcopc1;
		$numadver += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadver, "54200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadver, "ADVERTISING");
		$excel->getActiveSheet()->getStyle('C'.$numadver)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadver)->applyFromArray($style_sub);

		$numadver1 = $numadver;
		$numadver1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadver1, "54210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadver1, "ADVERTISEMENT PRODUCTION");
		$excel->getActiveSheet()->getStyle('C'.$numadver1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadver1)->applyFromArray($style_sub);

		$numadver2 = $numadver1;
		$numadver2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadver2, "54211000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadver2, "ELECTRONIC MEDIA - PRODUCTION");
		$excel->getActiveSheet()->getStyle('C'.$numadver2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadver2)->applyFromArray($style_sub);

		$category = "adver";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowadver= $numadver2;
		$numrowadver += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowadver, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowadver, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowadver)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowadver, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowadver, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowadver, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowadver, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowadver, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowadver, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowadver, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowadver.':J'.$numrowadver)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowadver++;
		}

			$totadver = $numrowadver;
			$totadver += 0;
			$sumadver = $numadver2+=1;
			$sumadver1 = $numrowadver - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadver, "Subtotal Digital  Media Production");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadver, '=SUM(E'.$sumadver.':E'.$sumadver1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadver, '=SUM(F'.$sumadver.':F'.$sumadver1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadver, '=SUM(G'.$sumadver.':G'.$sumadver1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totadver, '=SUM(H'.$sumadver.':H'.$sumadver1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totadver, '=SUM(I'.$sumadver.':I'.$sumadver1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totadver, '=SUM(J'.$sumadver.':J'.$sumadver1.')');
			$excel->getActiveSheet()->getStyle('D'.$totadver.':J'.$totadver)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadver.':J'.$totadver)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numnemp = $totadver;
		$numnemp += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numnemp, "54212000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numnemp, "NON - ELECTRONIC MEDIA - PRODUCTION");
		$excel->getActiveSheet()->getStyle('C'.$numnemp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numnemp)->applyFromArray($style_sub);

		$category = "nemp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrownemp= $numnemp;
		$numrownemp += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrownemp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrownemp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrownemp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrownemp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrownemp, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrownemp, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrownemp, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrownemp, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrownemp, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrownemp, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrownemp.':J'.$numrownemp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrownemp++;
		}

			$totnemp = $numrownemp;
			$totnemp += 0;
			$sumnemp = $numnemp+=1;
			$sumnemp1 = $numrownemp - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totnemp, "Traditional Material Promo Production");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totnemp, '=SUM(E'.$sumnemp.':E'.$sumnemp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totnemp, '=SUM(F'.$sumnemp.':F'.$sumnemp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totnemp, '=SUM(G'.$sumnemp.':G'.$sumnemp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totnemp, '=SUM(H'.$sumnemp.':H'.$sumnemp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totnemp, '=SUM(I'.$sumnemp.':I'.$sumnemp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totnemp, '=SUM(J'.$sumnemp.':J'.$sumnemp1.')');
			$excel->getActiveSheet()->getStyle('D'.$totnemp.':J'.$totnemp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totnemp.':J'.$totnemp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totnemp1 = $totnemp;
			$totnemp1 += 1;
			$sumnemp = $numnemp+=1;
			$sumnemp1 = $numrownemp - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totnemp1, "SUBTOTAL ADVERTISEMENT PRODUCTION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totnemp1, '=SUM(E'.$sumadver.':E'.$totnemp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totnemp1, '=SUM(F'.$sumadver.':F'.$totnemp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totnemp1, '=SUM(G'.$sumadver.':G'.$totnemp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totnemp1, '=SUM(H'.$sumadver.':H'.$totnemp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totnemp1, '=SUM(I'.$sumadver.':I'.$totnemp.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totnemp1, '=SUM(J'.$sumadver.':J'.$totnemp.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totnemp1.':J'.$totnemp1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totnemp1.':J'.$totnemp1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numadvp = $totnemp1;
		$numadvp += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadvp, "54220000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadvp, "ADVERTISEMENT PLACEMENT");
		$excel->getActiveSheet()->getStyle('C'.$numadvp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadvp)->applyFromArray($style_sub);

		$numadvp1 = $numadvp;
		$numadvp1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadvp1, "54221000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadvp1, "ELECTRONIC MEDIA - PLACEMENT");
		$excel->getActiveSheet()->getStyle('C'.$numadvp1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadvp1)->applyFromArray($style_sub);

		$category = "advp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowadvp= $numadvp1;
		$numrowadvp += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowadvp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowadvp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowadvp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowadvp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowadvp, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowadvp, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowadvp, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowadvp, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowadvp, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowadvp, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowadvp.':J'.$numrowadvp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowadvp++;
		}

			$totadvp = $numrowadvp;
			$totadvp += 0;
			$sumadvp = $numadvp1+=1;
			$sumadvp1 = $numrowadvp - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadvp, "Subtotal Digital Placement");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadvp, '=SUM(E'.$sumadvp.':E'.$sumadvp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadvp, '=SUM(F'.$sumadvp.':F'.$sumadvp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadvp, '=SUM(G'.$sumadvp.':G'.$sumadvp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totadvp, '=SUM(H'.$sumadvp.':H'.$sumadvp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totadvp, '=SUM(I'.$sumadvp.':I'.$sumadvp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totadvp, '=SUM(J'.$sumadvp.':J'.$sumadvp1.')');
			$excel->getActiveSheet()->getStyle('D'.$totadvp.':J'.$totadvp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadvp.':J'.$totadvp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numnem = $totadvp;
		$numnem += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numnem, "54222000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numnem, "NON - ELECTRONIC MEDIA - PLACEMENT");
		$excel->getActiveSheet()->getStyle('C'.$numnem)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numnem)->applyFromArray($style_sub);

		$category = "nem";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrownem= $numnem;
		$numrownem += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrownem, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrownem, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrownem)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrownem, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrownem, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrownem, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrownem, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrownem, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrownem, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrownem, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrownem.':J'.$numrownem)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrownem++;
		}

			$totnem = $numrownem;
			$totnem += 0;
			$sumnem = $numnem+=1;
			$sumnem1 = $numrownem - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totnem, "Subtotal Traditional Placement");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totnem, '=SUM(E'.$sumnem.':E'.$sumnem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totnem, '=SUM(F'.$sumnem.':F'.$sumnem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totnem, '=SUM(G'.$sumnem.':G'.$sumnem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totnem, '=SUM(H'.$sumnem.':H'.$sumnem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totnem, '=SUM(I'.$sumnem.':I'.$sumnem1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totnem, '=SUM(J'.$sumnem.':J'.$sumnem1.')');
			$excel->getActiveSheet()->getStyle('D'.$totnem.':J'.$totnem)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totnem.':J'.$totnem)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totnem1 = $numrownem;
			$totnem1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totnem1, "SUBTOTAL ADVERTISEMENT PLACEMENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totnem1, '=SUM(E'.$sumadvp.':E'.$totnem.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totnem1, '=SUM(F'.$sumadvp.':F'.$totnem.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totnem1, '=SUM(G'.$sumadvp.':G'.$totnem.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totnem1, '=SUM(H'.$sumadvp.':H'.$totnem.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totnem1, '=SUM(I'.$sumadvp.':I'.$totnem.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totnem1, '=SUM(J'.$sumadvp.':J'.$totnem.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totnem1.':J'.$totnem1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totnem1.':J'.$totnem1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numadva = $totnem1;
		$numadva += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadva, "54230000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadva, "ADVERTISEMENT AGENCIES");
		$excel->getActiveSheet()->getStyle('C'.$numadva)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadva)->applyFromArray($style_sub);

		$category = "adva";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowadva= $numadva;
		$numrowadva += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowadva, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowadva, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowadva)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowadva, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowadva, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowadva, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowadva, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowadva, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowadva, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowadva, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowadva.':J'.$numrowadva)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowadva++;
		}

			$totadva = $numrowadva;
			$totadva += 0;
			$sumadva = $numadva+=1;
			$sumadva1 = $numrowadva - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadva, "SUBTOTAL ADVERTISEMENT AGENCIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadva, '=SUM(E'.$sumadva.':E'.$sumadva1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadva, '=SUM(F'.$sumadva.':F'.$sumadva1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadva, '=SUM(G'.$sumadva.':G'.$sumadva1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totadva, '=SUM(H'.$sumadva.':H'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totadva, '=SUM(I'.$sumadva.':I'.$sumppob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totadva, '=SUM(J'.$sumadva.':J'.$sumppob1.')');
			$excel->getActiveSheet()->getStyle('D'.$totadva.':J'.$totadva)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadva.':J'.$totadva)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totadva1 = $totadva;
			$totadva1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadva1, "SUBTOTAL ADVERTISING");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadva1, '=E'.$totadva.'+E'.$totnem1.'+E'.$totnemp1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadva1, '=F'.$totadva.'+F'.$totnem1.'+F'.$totnemp1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadva1, '=G'.$totadva.'+G'.$totnem1.'+G'.$totnemp1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totadva1, '=H'.$totadva.'+H'.$totnem1.'+H'.$totnemp1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totadva1, '=I'.$totadva.'+I'.$totnem1.'+I'.$totnemp1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totadva1, '=J'.$totadva.'+J'.$totnem1.'+J'.$totnemp1);
			$excel->getActiveSheet()->getStyle('D'.$totadva1.':J'.$totadva1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadva1.':J'.$totadva1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcsa = $totadva1;
		$numcsa += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcsa, "54300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcsa, "CUSTOMER SERVICE AGENT");
		$excel->getActiveSheet()->getStyle('C'.$numcsa)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcsa)->applyFromArray($style_sub);

		$category = "csa";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcsa= $numcsa;
		$numrowcsa += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcsa, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcsa, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcsa)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcsa, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcsa, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcsa, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcsa, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcsa, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcsa, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcsa, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcsa.':J'.$numrowcsa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcsa++;
		}

			$totcsa = $numrowcsa;
			$totcsa += 0;
			$sumcsa = $numcsa+=1;
			$sumcsa1 = $numrowcsa - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcsa, "Subtotal Customer Communication Services");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcsa, '=SUM(E'.$sumcsa.':E'.$sumcsa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcsa, '=SUM(F'.$sumcsa.':F'.$sumcsa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcsa, '=SUM(G'.$sumcsa.':G'.$sumcsa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcsa, '=SUM(H'.$sumcsa.':H'.$sumcsa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcsa, '=SUM(I'.$sumcsa.':I'.$sumcsa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcsa, '=SUM(J'.$sumcsa.':J'.$sumcsa1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcsa.':J'.$totcsa)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcsa.':J'.$totcsa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numclp = $totcsa;
		$numclp += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numclp, "54400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numclp, "CUSTOMER LOYALTY PROGRAMS");
		$excel->getActiveSheet()->getStyle('C'.$numclp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numclp)->applyFromArray($style_sub);

		$category = "clp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowclp= $numclp;
		$numrowclp += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowclp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowclp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowclp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowclp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowclp, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowclp, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowclp, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowclp, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowclp, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowclp, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowclp.':J'.$numrowclp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowclp++;
		}

			$totclp = $numrowclp;
			$totclp += 0;
			$sumclp = $numclp+=1;
			$sumclp1 = $numrowclp - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totclp, "SUBTOTAL CUSTOMER LOYALTY PROGRAMS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totclp, '=SUM(E'.$sumclp.':E'.$sumclp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totclp, '=SUM(F'.$sumclp.':F'.$sumclp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totclp, '=SUM(G'.$sumclp.':G'.$sumclp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totclp, '=SUM(H'.$sumclp.':H'.$sumclp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totclp, '=SUM(I'.$sumclp.':I'.$sumclp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totclp, '=SUM(J'.$sumclp.':J'.$sumclp1.')');
			$excel->getActiveSheet()->getStyle('D'.$totclp.':J'.$totclp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totclp.':J'.$totclp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numome = $totclp;
		$numome += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numome, "54500000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numome, "OTHERS MARKETING EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numome)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numome)->applyFromArray($style_sub);

		$category = "ome";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowome= $numome;
		$numrowome += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowome, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowome, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowome)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowome, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowome, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowome, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowome, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowome, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowome, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowome, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowome.':J'.$numrowome)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowome++;
		}

			$totome = $numrowome;
			$totome += 0;
			$sumome = $numome+=1;
			$sumome1 = $numrowome - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totome, "SUBTOTAL OTHERS MARKETING EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totome, '=SUM(E'.$sumome.':E'.$sumome1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totome, '=SUM(F'.$sumome.':F'.$sumome1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totome, '=SUM(G'.$sumome.':G'.$sumome1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totome, '=SUM(H'.$sumome.':H'.$sumome1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totome, '=SUM(I'.$sumome.':I'.$sumome1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totome, '=SUM(J'.$sumome.':J'.$sumome1.')');
			$excel->getActiveSheet()->getStyle('D'.$totome.':J'.$totome)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totome.':J'.$totome)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totome1 = $totome;
			$totome1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totome1, "SUBTOTAL MARKETING & SALES EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totome1, '=E'.$totome.'+E'.$totclp.'+E'.$totadva1.'+E'.$totcopc1.'+E'.$totcsa);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totome1, '=F'.$totome.'+F'.$totclp.'+F'.$totadva1.'+F'.$totcopc1.'+F'.$totcsa);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totome1, '=G'.$totome.'+G'.$totclp.'+G'.$totadva1.'+G'.$totcopc1.'+G'.$totcsa);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totome1, '=H'.$totome.'+H'.$totclp.'+H'.$totadva1.'+H'.$totcopc1.'+H'.$totcsa);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totome1, '=I'.$totome.'+I'.$totclp.'+I'.$totadva1.'+I'.$totcopc1.'+I'.$totcsa);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totome1, '=J'.$totome.'+J'.$totclp.'+J'.$totadva1.'+J'.$totcopc1.'+J'.$totcsa);
			$excel->getActiveSheet()->getStyle('D'.$totome1.':J'.$totome1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totome1.':J'.$totome1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numgae = $totome1;
		$numgae += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numgae, "55000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numgae, "GENERAL AND ADMINISTRATIVE EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numgae)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numgae)->applyFromArray($style_sub);

		$numgae1 = $numgae;
		$numgae1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numgae1, "55100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numgae1, "GENERAL EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numgae1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numgae1)->applyFromArray($style_sub);

		$numgae2 = $numgae1;
		$numgae2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numgae2, "55110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numgae2, "RENT");
		$excel->getActiveSheet()->getStyle('C'.$numgae2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numgae2)->applyFromArray($style_sub);

		$numgae3 = $numgae2;
		$numgae3 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numgae3, "55111000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numgae3, "RENTAL VEHICLES");
		$excel->getActiveSheet()->getStyle('C'.$numgae3)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numgae3)->applyFromArray($style_sub);

		$category = "gae";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowgae= $numgae3;
		$numrowgae += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowgae, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowgae, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowgae)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowgae, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowgae, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowgae, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowgae, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowgae, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowgae, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowgae, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowgae.':J'.$numrowgae)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowgae++;
		}

			$totgae = $numrowgae;
			$totgae += 0;
			$sumgae = $numgae3+=1;
			$sumgae1 = $numrowgae - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totgae, "Subtotal Rental Vehicles");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totgae, '=SUM(E'.$sumgae.':E'.$sumgae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totgae, '=SUM(F'.$sumgae.':F'.$sumgae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totgae, '=SUM(G'.$sumgae.':G'.$sumgae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totgae, '=SUM(H'.$sumgae.':H'.$sumgae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totgae, '=SUM(I'.$sumgae.':I'.$sumgae1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totgae, '=SUM(J'.$sumgae.':J'.$sumgae1.')');
			$excel->getActiveSheet()->getStyle('D'.$totgae.':J'.$totgae)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totgae.':J'.$totgae)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrb = $totgae;
		$numrb += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrb, "55112000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrb, "RENTAL BUILDING");
		$excel->getActiveSheet()->getStyle('C'.$numrb)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrb)->applyFromArray($style_sub);

		$category = "rb";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrb= $numrb;
		$numrowrb += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrb, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrb, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrb)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrb, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrb, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrb, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrb, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrb, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrb, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrb, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrb.':J'.$numrowrb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrb++;
		}

			$totrb = $numrowrb;
			$totrb += 0;
			$sumrb = $numrb+=1;
			$sumrb1 = $numrowrb - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrb, "Subtotal Rental Building");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrb, '=SUM(E'.$sumrb.':E'.$sumrb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrb, '=SUM(F'.$sumrb.':F'.$sumrb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrb, '=SUM(G'.$sumrb.':G'.$sumrb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrb, '=SUM(H'.$sumrb.':H'.$sumrb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrb, '=SUM(I'.$sumrb.':I'.$sumrb1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrb, '=SUM(J'.$sumrb.':J'.$sumrb1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrb.':J'.$totrb)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrb.':J'.$totrb)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrob = $totrb;
		$numrob += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrob, "55112200");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrob, "RENTAL - OFFICE BUILDING");
		$excel->getActiveSheet()->getStyle('C'.$numrob)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrob)->applyFromArray($style_sub);

		$category = "rob";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrob= $numrob;
		$numrowrob += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrob, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrob, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrob)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrob, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrob, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrob, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrob, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrob, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrob, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrob, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrob.':J'.$numrowrob)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrob++;
		}

			$totrob = $numrowrob;
			$totrob += 0;
			$sumrob = $numrob+=1;
			$sumrob1 = $numrowrob - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrob, "Subtotal Rental Office Building");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrob, '=SUM(E'.$sumrob.':E'.$sumrob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrob, '=SUM(F'.$sumrob.':F'.$sumrob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrob, '=SUM(G'.$sumrob.':G'.$sumrob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrob, '=SUM(H'.$sumrob.':H'.$sumrob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrob, '=SUM(I'.$sumrob.':I'.$sumrob1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrob, '=SUM(J'.$sumrob.':J'.$sumrob1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrob.':J'.$totrob)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrob.':J'.$totrob)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numroe = $totrob;
		$numroe += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numroe, "55113000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numroe, "RENTAL OFFICE EQUIPMENT");
		$excel->getActiveSheet()->getStyle('C'.$numroe)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numroe)->applyFromArray($style_sub);

		$numroe1 = $numroe;
		$numroe1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numroe1, "55113100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numroe1, "RENTAL - OFFICE AUTOMATION EQP.");
		$excel->getActiveSheet()->getStyle('C'.$numroe1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numroe1)->applyFromArray($style_sub);

		$category = "roe";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowroe= $numroe1;
		$numrowroe += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowroe, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowroe, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowroe)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowroe, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowroe, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowroe, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowroe, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowroe, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowroe, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowroe, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowroe.':J'.$numrowroe)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowroe++;
		}

			$totroe = $numrowroe;
			$totroe += 0;
			$sumroe = $numroe1+=1;
			$sumroe1 = $numrowroe - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totroe, "Subtotal Rental Office Automation Eqp.");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totroe, '=SUM(E'.$sumroe.':E'.$sumroe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totroe, '=SUM(F'.$sumroe.':F'.$sumroe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totroe, '=SUM(G'.$sumroe.':G'.$sumroe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totroe, '=SUM(H'.$sumroe.':H'.$sumroe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totroe, '=SUM(I'.$sumroe.':I'.$sumroe1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totroe, '=SUM(J'.$sumroe.':J'.$sumroe1.')');
			$excel->getActiveSheet()->getStyle('D'.$totroe.':J'.$totroe)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totroe.':J'.$totroe)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numref = $totroe;
		$numref += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numref, "55113200");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numref, "RENTAL - EQUIPMENT & FURNITURE");
		$excel->getActiveSheet()->getStyle('C'.$numref)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numref)->applyFromArray($style_sub);

		$category = "ref";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowref= $numref;
		$numrowref += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowref, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowref, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowref)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowref, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowref, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowref, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowref, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowref, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowref, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowref, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowref.':J'.$numrowref)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowref++;
		}

			$totref = $numrowref;
			$totref += 0;
			$sumref = $numref+=1;
			$sumref1 = $numrowref - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totref, "Subtotal Rental Office Eqp. & Furniture");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totref, '=SUM(E'.$sumref.':E'.$sumref1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totref, '=SUM(F'.$sumref.':F'.$sumref1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totref, '=SUM(G'.$sumref.':G'.$sumref1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totref, '=SUM(H'.$sumref.':H'.$sumref1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totref, '=SUM(I'.$sumref.':I'.$sumref1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totref, '=SUM(J'.$sumref.':J'.$sumref1.')');
			$excel->getActiveSheet()->getStyle('D'.$totref.':J'.$totref)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totref.':J'.$totref)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numrd = $totref;
		$numrd += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrd, "55114000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrd, "RENTAL DEVICE");
		$excel->getActiveSheet()->getStyle('C'.$numrd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrd)->applyFromArray($style_sub);

		$numrd1 = $numrd;
		$numrd1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrd1, "55114100");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrd1, "RENTAL OPERATIONAL DEVICE");
		$excel->getActiveSheet()->getStyle('C'.$numrd1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numrd1)->applyFromArray($style_sub);

		$category = "rd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowrd= $numrd1;
		$numrowrd += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowrd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowrd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowrd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrd, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowrd, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowrd, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowrd, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowrd, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowrd, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowrd.':J'.$numrowrd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowrd++;
		}

			$totrd = $numrowrd;
			$totrd += 0;
			$sumrd = $numrd1+=1;
			$sumrd1 = $numrowrd - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrd, "Subtotal Rental Office Automation Eqp.");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrd, '=SUM(E'.$sumrd.':E'.$sumrd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrd, '=SUM(F'.$sumrd.':F'.$sumrd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrd, '=SUM(G'.$sumrd.':G'.$sumrd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrd, '=SUM(H'.$sumrd.':H'.$sumrd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrd, '=SUM(I'.$sumrd.':I'.$sumrd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrd, '=SUM(J'.$sumrd.':J'.$sumrd1.')');
			$excel->getActiveSheet()->getStyle('D'.$totrd.':J'.$totrd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrd.':J'.$totrd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totrd1 = $numrowrd;
			$totrd1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totrd1, "SUBTOTAL RENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totrd1, '=SUM(E'.$sumgae.':E'.$totrd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totrd1, '=SUM(F'.$sumgae.':F'.$totrd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totrd1, '=SUM(G'.$sumgae.':G'.$totrd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totrd1, '=SUM(H'.$sumgae.':H'.$totrd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totrd1, '=SUM(I'.$sumgae.':I'.$totrd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totrd1, '=SUM(J'.$sumgae.':J'.$totrd.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totrd1.':J'.$totrd1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totrd1.':J'.$totrd1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtat = $totrd1;
		$numtat += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtat, "55120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtat, "TRAVEL AND TRANSPORTATION");
		$excel->getActiveSheet()->getStyle('C'.$numtat)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtat)->applyFromArray($style_sub);

		$category = "tat";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowtat= $numtat;
		$numrowtat += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowtat, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtat, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowtat)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowtat, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtat, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowtat, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowtat, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowtat, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowtat, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtat, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowtat.':J'.$numrowtat)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowtat++;
		}

			$tottat = $numrowtat;
			$tottat += 0;
			$sumtat = $numtat+=1;
			$sumtat1 = $numrowtat - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottat, "SUBTOTAL TRAVEL AND TRANSPORTATION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottat, '=SUM(E'.$sumtat.':E'.$sumtat1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottat, '=SUM(F'.$sumtat.':F'.$sumtat1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottat, '=SUM(G'.$sumtat.':G'.$sumtat1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottat, '=SUM(H'.$sumtat.':H'.$sumtat1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottat, '=SUM(I'.$sumtat.':I'.$sumtat1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottat, '=SUM(J'.$sumtat.':J'.$sumtat1.')');
			$excel->getActiveSheet()->getStyle('D'.$tottat.':J'.$tottat)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottat.':J'.$tottat)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numsac = $tottat;
		$numsac += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsac, "55130000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numsac, "SECURITY AND CLEANING");
		$excel->getActiveSheet()->getStyle('C'.$numsac)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numsac)->applyFromArray($style_sub);

		$numsac1 = $numsac;
		$numsac1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsac1, "55131000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numsac1, "SECURITY MANAGEMENT");
		$excel->getActiveSheet()->getStyle('C'.$numsac1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numsac1)->applyFromArray($style_sub);

		$category = "sac";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowsac= $numsac1;
		$numrowsac += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowsac, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowsac, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowsac)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowsac, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowsac, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowsac, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowsac, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowsac, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowsac, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowsac, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowsac.':J'.$numrowsac)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowsac++;
		}

			$totsac = $numrowsac;
			$totsac += 0;
			$sumsac = $numsac1+=1;
			$sumsac1 = $numrowsac - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsac, "Subtotal Security Management");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsac, '=SUM(E'.$sumsac.':E'.$sumsac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsac, '=SUM(F'.$sumsac.':F'.$sumsac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsac, '=SUM(G'.$sumsac.':G'.$sumsac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totsac, '=SUM(H'.$sumsac.':H'.$sumsac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totsac, '=SUM(I'.$sumsac.':I'.$sumsac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totsac, '=SUM(J'.$sumsac.':J'.$sumsac1.')');
			$excel->getActiveSheet()->getStyle('D'.$totsac.':J'.$totsac)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsac.':J'.$totsac)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numsm = $totsac;
		$numsm += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsm, "55132000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numsm, "SERVICE MANAGEMENTS");
		$excel->getActiveSheet()->getStyle('C'.$numsm)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numsm)->applyFromArray($style_sub);

		$category = "sm";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowsm= $numsm;
		$numrowsm += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowsm, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowsm, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowsm)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowsm, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowsm, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowsm, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowsm, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowsm, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowsm, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowsm, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowsm.':J'.$numrowsm)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowsm++;
		}

			$totsm = $numrowsm;
			$totsm += 0;
			$sumsm = $numsm+=1;
			$sumsm1 = $numrowsm - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsm, "Subtotal Service Managements");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsm, '=SUM(E'.$sumsm.':E'.$sumsm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsm, '=SUM(F'.$sumsm.':F'.$sumsm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsm, '=SUM(G'.$sumsm.':G'.$sumsm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totsm, '=SUM(H'.$sumsm.':H'.$sumsm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totsm, '=SUM(I'.$sumsm.':I'.$sumsm1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totsm, '=SUM(J'.$sumsm.':J'.$sumsm1.')');
			$excel->getActiveSheet()->getStyle('D'.$totsm.':J'.$totsm)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsm.':J'.$totsm)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totsm1 = $totsm;
			$totsm1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totsm1, "SUBTOTAL SECURITY AND CLEANING");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totsm1, '=SUM(E'.$sumsac.':E'.$totsm.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totsm1, '=SUM(F'.$sumsac.':F'.$totsm.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totsm1, '=SUM(G'.$sumsac.':G'.$totsm.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totsm1, '=SUM(H'.$sumsac.':H'.$totsm.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totsm1, '=SUM(I'.$sumsac.':I'.$totsm.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totsm1, '=SUM(J'.$sumsac.':J'.$totsm.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totsm1.':G'.$totsm1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totsm1.':G'.$totsm1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numutl = $totsm1;
		$numutl += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numutl, "55140000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numutl, "UTILITIES");
		$excel->getActiveSheet()->getStyle('C'.$numutl)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numutl)->applyFromArray($style_sub);

		$numutl1 = $numutl;
		$numutl1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numutl1, "55141000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numutl1, "UTILITIES OFFICE TELEPHONE & DATA");
		$excel->getActiveSheet()->getStyle('C'.$numutl1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numutl1)->applyFromArray($style_sub);

		$category = "utl";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowutl= $numutl1;
		$numrowutl += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowutl, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowutl, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowutl)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowutl, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowutl, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowutl, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowutl, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowutl, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowutl, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowutl, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowutl.':J'.$numrowutl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowutl++;
		}

			$totutl = $numrowutl;
			$totutl += 0;
			$sumutl = $numutl1+=1;
			$sumutl1 = $numrowutl - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totutl, "Subtotal Utilities Office Telephone & Data");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totutl, '=SUM(E'.$sumutl.':E'.$sumutl1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totutl, '=SUM(F'.$sumutl.':F'.$sumutl1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totutl, '=SUM(G'.$sumutl.':G'.$sumutl1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totutl, '=SUM(H'.$sumutl.':H'.$sumutl1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totutl, '=SUM(I'.$sumutl.':I'.$sumutl1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totutl, '=SUM(J'.$sumutl.':J'.$sumutl1.')');
			$excel->getActiveSheet()->getStyle('D'.$totutl.':J'.$totutl)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totutl.':J'.$totutl)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numue = $totutl;
		$numue += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numue, "55142000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numue, "UTILITIES ELECTRICITY");
		$excel->getActiveSheet()->getStyle('C'.$numue)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numue)->applyFromArray($style_sub);

		$category = "ue";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowue= $numue;
		$numrowue += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowue, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowue, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowue)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowue, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowue, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowue, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowue, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowue, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowue, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowue, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowue.':J'.$numrowue)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowue++;
		}

			$totue = $numrowue;
			$totue += 0;
			$sumue = $numue+=1;
			$sumue1 = $numrowue - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totue, "Subtotal Utilities Electricity");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totue, '=SUM(E'.$sumue.':E'.$sumue1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totue, '=SUM(F'.$sumue.':F'.$sumue1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totue, '=SUM(G'.$sumue.':G'.$sumue1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totue, '=SUM(H'.$sumue.':H'.$sumue1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totue, '=SUM(I'.$sumue.':I'.$sumue1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totue, '=SUM(J'.$sumue.':J'.$sumue1.')');
			$excel->getActiveSheet()->getStyle('D'.$totue.':J'.$totue)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totue.':J'.$totue)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numuw = $totue;
		$numuw += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numuw, "55143000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numuw, "UTILITIES WATER");
		$excel->getActiveSheet()->getStyle('C'.$numuw)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numuw)->applyFromArray($style_sub);

		$category = "uw";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowuw= $numuw;
		$numrowuw += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowuw, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowuw, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowuw)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowuw, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowuw, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowuw, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowuw, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowuw, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowuw, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowuw, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowuw.':J'.$numrowuw)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowuw++;
		}

			$totuw = $numrowuw;
			$totuw += 0;
			$sumuw = $numuw+=1;
			$sumuw1 = $numrowuw - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totuw, "Subtotal Utilities Water");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totuw, '=SUM(E'.$sumuw.':E'.$sumuw1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totuw, '=SUM(F'.$sumuw.':F'.$sumuw1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totuw, '=SUM(G'.$sumuw.':G'.$sumuw1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totuw, '=SUM(H'.$sumuw.':H'.$sumuw1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totuw, '=SUM(I'.$sumuw.':I'.$sumuw1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totuw, '=SUM(J'.$sumuw.':J'.$sumuw1.')');
			$excel->getActiveSheet()->getStyle('D'.$totuw.':J'.$totuw)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totuw.':J'.$totuw)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totuw1 = $totuw;
			$totuw1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totuw1, "SUBTOTAL UTILITIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totuw1, '=SUM(E'.$sumutl.':E'.$totuw.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totuw1, '=SUM(F'.$sumutl.':F'.$totuw.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totuw1, '=SUM(G'.$sumutl.':G'.$totuw.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totuw1, '=SUM(H'.$sumutl.':H'.$totuw.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totuw1, '=SUM(I'.$sumutl.':I'.$totuw.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totuw1, '=SUM(J'.$sumutl.':J'.$totuw.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totuw1.':J'.$totuw1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totuw1.':J'.$totuw1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numotas = $totuw1;
		$numotas += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numotas, "55150000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numotas, "OFFICE TOOLS AND SUPPLIES");
		$excel->getActiveSheet()->getStyle('C'.$numotas)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numotas)->applyFromArray($style_sub);

		$category = "otas";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowotas= $numotas;
		$numrowotas += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowotas, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowotas, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowotas)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowotas, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowotas, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowotas, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowotas, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowotas, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowotas, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowotas, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowotas.':J'.$numrowotas)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowotas++;
		}

			$tototas = $numrowotas;
			$tototas += 0;
			$sumotas = $numotas+=1;
			$sumotas1 = $numrowotas - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototas, "SUBTOTAL OFFICE TOOLS AND SUPPLIES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototas, '=SUM(E'.$sumotas.':E'.$sumotas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototas, '=SUM(F'.$sumotas.':F'.$sumotas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototas, '=SUM(G'.$sumotas.':G'.$sumotas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tototas, '=SUM(H'.$sumotas.':H'.$sumotas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tototas, '=SUM(I'.$sumotas.':I'.$sumotas1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tototas, '=SUM(J'.$sumotas.':J'.$sumotas1.')');
			$excel->getActiveSheet()->getStyle('D'.$tototas.':J'.$tototas)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototas.':J'.$tototas)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpaf = $tototas;
		$numpaf += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpaf, "55160000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpaf, "POSTAL AND FREIGHT");
		$excel->getActiveSheet()->getStyle('C'.$numpaf)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpaf)->applyFromArray($style_sub);

		$category = "paf";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpaf= $numpaf;
		$numrowpaf += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpaf, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpaf, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpaf)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpaf, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpaf, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpaf, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpaf, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpaf, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpaf, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpaf, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpaf.':J'.$numrowpaf)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpaf++;
		}

			$totopaf = $numrowpaf;
			$totopaf += 0;
			$sumopaf = $numpaf+=1;
			$sumopaf1 = $numrowpaf - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totopaf, "SUBTOTAL POSTAL AND FREIGHT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totopaf, '=SUM(E'.$sumopaf.':E'.$sumopaf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totopaf, '=SUM(F'.$sumopaf.':F'.$sumopaf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totopaf, '=SUM(G'.$sumopaf.':G'.$sumopaf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totopaf, '=SUM(H'.$sumopaf.':H'.$sumopaf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totopaf, '=SUM(I'.$sumopaf.':I'.$sumopaf1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totopaf, '=SUM(J'.$sumopaf.':J'.$sumopaf1.')');
			$excel->getActiveSheet()->getStyle('D'.$totopaf.':J'.$totopaf)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totopaf.':J'.$totopaf)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totopaf1 = $totopaf;
			$totopaf1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totopaf1, "SUBTOTAL GENERAL EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totopaf1, '=E'.$totopaf.'+E'.$tototas.'+E'.$totuw1.'+E'.$totsm1.'+E'.$tottat.'+E'.$totrd1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totopaf1, '=F'.$totopaf.'+F'.$tototas.'+F'.$totuw1.'+F'.$totsm1.'+F'.$tottat.'+F'.$totrd1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totopaf1, '=G'.$totopaf.'+G'.$tototas.'+G'.$totuw1.'+G'.$totsm1.'+G'.$tottat.'+G'.$totrd1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totopaf1, '=H'.$totopaf.'+H'.$tototas.'+H'.$totuw1.'+H'.$totsm1.'+H'.$tottat.'+H'.$totrd1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totopaf1, '=I'.$totopaf.'+I'.$tototas.'+I'.$totuw1.'+I'.$totsm1.'+I'.$tottat.'+I'.$totrd1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totopaf1, '=J'.$totopaf.'+J'.$tototas.'+J'.$totuw1.'+J'.$totsm1.'+J'.$tottat.'+J'.$totrd1);
			$excel->getActiveSheet()->getStyle('D'.$totopaf1.':J'.$totopaf1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totopaf1.':J'.$totopaf1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numadme = $totopaf1;
		$numadme += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadme, "55200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadme, "ADMINISTRATIVE EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numadme)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadme)->applyFromArray($style_sub);

		$numadme1 = $numadme;
		$numadme1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadme1, "55210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadme1, "PROFESSIONAL FEES");
		$excel->getActiveSheet()->getStyle('C'.$numadme1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadme1)->applyFromArray($style_sub);

		$numadme2 = $numadme1;
		$numadme2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numadme2, "55211000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numadme2, "PROFESSIONAL FEES BUSINESS");
		$excel->getActiveSheet()->getStyle('C'.$numadme2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numadme2)->applyFromArray($style_sub);

		$category = "adme";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowadme= $numadme2;
		$numrowadme += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowadme, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowadme, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowadme)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowadme, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowadme, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowadme, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowadme, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowadme, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowadme, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowadme, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowadme.':J'.$numrowadme)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowadme++;
		}

			$totadme = $numrowadme;
			$totadme += 0;
			$sumadme = $numadme2+=1;
			$sumadme1 = $numrowadme - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totadme, "Subtotal Professional Fees Business");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totadme, '=SUM(E'.$sumadme.':E'.$sumadme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totadme, '=SUM(F'.$sumadme.':F'.$sumadme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totadme, '=SUM(G'.$sumadme.':G'.$sumadme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totadme, '=SUM(H'.$sumadme.':H'.$sumadme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totadme, '=SUM(I'.$sumadme.':I'.$sumadme1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totadme, '=SUM(J'.$sumadme.':J'.$sumadme1.')');
			$excel->getActiveSheet()->getStyle('D'.$totadme.':J'.$totadme)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totadme.':J'.$totadme)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpfs = $totadme;
		$numpfs += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpfs, "55212000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpfs, "PROFESSIONAL FEES SUPPORT");
		$excel->getActiveSheet()->getStyle('C'.$numpfs)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpfs)->applyFromArray($style_sub);

		$category = "pfs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpfs= $numpfs;
		$numrowpfs += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpfs, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpfs, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpfs)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpfs, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpfs, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpfs, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpfs, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpfs, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpfs, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpfs, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpfs.':J'.$numrowpfs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpfs++;
		}

			$totpfs = $numrowpfs;
			$totpfs += 0;
			$sumpfs = $numpfs+=1;
			$sumpfs1 = $numrowpfs - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpfs, "Subtotal Professional Fees Support");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpfs, '=SUM(E'.$sumpfs.':E'.$sumpfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpfs, '=SUM(F'.$sumpfs.':F'.$sumpfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpfs, '=SUM(G'.$sumpfs.':G'.$sumpfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpfs, '=SUM(H'.$sumpfs.':H'.$sumpfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpfs, '=SUM(I'.$sumpfs.':I'.$sumpfs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpfs, '=SUM(J'.$sumpfs.':J'.$sumpfs1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpfs.':J'.$totpfs)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpfs.':J'.$totpfs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totpfs1 = $totpfs;
			$totpfs1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpfs1, "SUBTOTAL PROFESSIONAL FEES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpfs1, '=SUM(E'.$sumadme.':E'.$totpfs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpfs1, '=SUM(F'.$sumadme.':F'.$totpfs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpfs1, '=SUM(G'.$sumadme.':G'.$totpfs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpfs1, '=SUM(H'.$sumadme.':H'.$totpfs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpfs1, '=SUM(I'.$sumadme.':I'.$totpfs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpfs1, '=SUM(J'.$sumadme.':J'.$totpfs.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totpfs1.':J'.$totpfs1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpfs1.':J'.$totpfs1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numins = $totpfs1;
		$numins += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numins, "55220000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numins, "INSURANCE");
		$excel->getActiveSheet()->getStyle('C'.$numins)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numins)->applyFromArray($style_sub);

		$category = "ins";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowins= $numins;
		$numrowins += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowins, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowins, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowins)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowins, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowins, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowins, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowins, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowins, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowins, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowins, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowins.':J'.$numrowins)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowins++;
		}

			$totins = $numrowins;
			$totins += 0;
			$sumins = $numins+=1;
			$sumins1 = $numrowins - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totins, "SUBTOTAL INSURANCE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totins, '=SUM(E'.$sumins.':E'.$sumins1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totins, '=SUM(F'.$sumins.':F'.$sumins1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totins, '=SUM(G'.$sumins.':G'.$sumins1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totins, '=SUM(H'.$sumins.':H'.$sumins1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totins, '=SUM(I'.$sumins.':I'.$sumins1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totins, '=SUM(J'.$sumins.':J'.$sumins1.')');
			$excel->getActiveSheet()->getStyle('D'.$totins.':J'.$totins)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totins.':J'.$totins)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtar = $totins;
		$numtar += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtar, "55230000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtar, "TRAINING AND RECRUITMENT");
		$excel->getActiveSheet()->getStyle('C'.$numtar)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtar)->applyFromArray($style_sub);

		$numtar1 = $numtar;
		$numtar1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtar1, "55231000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtar1, "RECRUITMENT & ASSESSMENT");
		$excel->getActiveSheet()->getStyle('C'.$numtar1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtar1)->applyFromArray($style_sub);

		$category = "tar";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowtar= $numtar1;
		$numrowtar += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowtar, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtar, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowtar)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowtar, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtar, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowtar, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowtar, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowtar, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowtar, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtar, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowtar.':J'.$numrowtar)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowtar++;
		}

			$tottar = $numrowtar;
			$tottar += 0;
			$sumtar = $numtar1+=1;
			$sumtar1 = $numrowtar - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottar, "Subtotal Recruitment & Assesment");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottar, '=SUM(E'.$sumtar.':E'.$sumtar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottar, '=SUM(F'.$sumtar.':F'.$sumtar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottar, '=SUM(G'.$sumtar.':G'.$sumtar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottar, '=SUM(H'.$sumtar.':H'.$sumtar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottar, '=SUM(I'.$sumtar.':I'.$sumtar1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottar, '=SUM(J'.$sumtar.':J'.$sumtar1.')');
			$excel->getActiveSheet()->getStyle('D'.$tottar.':J'.$tottar)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottar.':J'.$tottar)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtad = $tottar;
		$numtad += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtad, "55232000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtad, "TRAINING AND DEVELOPMENT");
		$excel->getActiveSheet()->getStyle('C'.$numtad)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtad)->applyFromArray($style_sub);

		$category = "tad";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowtad= $numtad;
		$numrowtad += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowtad, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtad, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowtad)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowtad, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtad, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowtad, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowtad, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowtad, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowtad, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtad, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowtad.':J'.$numrowtad)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowtad++;
		}

			$tottad = $numrowtad;
			$tottad += 0;
			$sumtad = $numtad+=1;
			$sumtad1 = $numrowtad - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottad, "Subtotal Recruitment & Assesment");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottad, '=SUM(E'.$sumtad.':E'.$sumtad1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottad, '=SUM(F'.$sumtad.':F'.$sumtad1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottad, '=SUM(G'.$sumtad.':G'.$sumtad1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottad, '=SUM(H'.$sumtad.':H'.$sumtad1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottad, '=SUM(I'.$sumtad.':I'.$sumtad1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottad, '=SUM(J'.$sumtad.':J'.$sumtad1.')');
			$excel->getActiveSheet()->getStyle('D'.$tottad.':J'.$tottad)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottad.':J'.$tottad)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tottad1 = $tottad;
			$tottad1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottad1, "SUBTOTAL TRAINING AND RECRUITMENT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottad1, '=SUM(E'.$sumtar.':E'.$tottad.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottad1, '=SUM(F'.$sumtar.':F'.$tottad.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottad1, '=SUM(G'.$sumtar.':G'.$tottad.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottad1, '=SUM(H'.$sumtar.':H'.$tottad.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottad1, '=SUM(I'.$sumtar.':I'.$tottad.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottad1, '=SUM(J'.$sumtar.':J'.$tottad.')');
			$excel->getActiveSheet()->getStyle('D'.$tottad1.':J'.$tottad1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottad1.':J'.$tottad1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$nummare = $tottad1;
		$nummare += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummare, "55240000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummare, "MEETINGS AND REPRESENTATIONS EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$nummare)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummare)->applyFromArray($style_sub);

		$category = "mare";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowmare= $nummare;
		$numrowmare += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmare, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmare, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmare)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmare, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmare, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmare, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmare, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowmare, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowmare, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowmare, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmare.':J'.$numrowmare)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmare++;
		}

			$totmare = $numrowmare;
			$totmare += 0;
			$summare = $nummare+=1;
			$summare1 = $numrowmare - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmare, "SUBTOTAL MEETINGS AND REPRESENTATIONS EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmare, '=SUM(E'.$summare.':E'.$summare1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmare, '=SUM(F'.$summare.':F'.$summare1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmare, '=SUM(G'.$summare.':G'.$summare1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totmare, '=SUM(H'.$summare.':H'.$summare1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totmare, '=SUM(I'.$summare.':I'.$summare1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totmare, '=SUM(J'.$summare.':J'.$summare1.')');
			$excel->getActiveSheet()->getStyle('D'.$totmare.':J'.$totmare)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmare.':J'.$totmare)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdac = $totmare;
		$numdac += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdac, "55250000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdac, "DONATION AND CSR");
		$excel->getActiveSheet()->getStyle('C'.$numdac)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdac)->applyFromArray($style_sub);

		$category = "dac";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdac= $numdac;
		$numrowdac += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdac, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdac, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdac)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdac, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdac, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdac, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdac, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowdac, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowdac, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowdac, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdac.':J'.$numrowdac)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdac++;
		}

			$totdac = $numrowdac;
			$totdac += 0;
			$sumdac = $numdac+=1;
			$sumdac1 = $numrowdac - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdac, "SUBTOTAL DONATION AND CSR");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdac, '=SUM(E'.$sumdac.':E'.$sumdac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdac, '=SUM(F'.$sumdac.':F'.$sumdac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdac, '=SUM(G'.$sumdac.':G'.$sumdac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdac, '=SUM(H'.$sumdac.':H'.$sumdac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdac, '=SUM(I'.$sumdac.':I'.$sumdac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdac, '=SUM(J'.$sumdac.':J'.$sumdac1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdac.':J'.$totdac)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdac.':J'.$totdac)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$nummer = $totdac;
		$nummer += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummer, "55260000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummer, "MANAGEMENT & EXPATRIATE REMUNERATION");
		$excel->getActiveSheet()->getStyle('C'.$nummer)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummer)->applyFromArray($style_sub);

		$nummer1 = $nummer;
		$nummer1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$nummer1, "55261000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$nummer1, "MANAGEMENT REMUNERATION");
		$excel->getActiveSheet()->getStyle('C'.$nummer1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$nummer1)->applyFromArray($style_sub);

		$category = "mer";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowmer= $nummer1;
		$numrowmer += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowmer, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowmer, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowmer)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowmer, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowmer, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowmer, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowmer, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowmer, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowmer, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowmer, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowmer.':J'.$numrowmer)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowmer++;
		}

			$totmer = $numrowmer;
			$totmer += 0;
			$summer = $nummer1+=1;
			$summer1 = $numrowmer - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totmer, "Subtotal Management Remuneration");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totmer, '=SUM(E'.$summer.':E'.$summer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totmer, '=SUM(F'.$summer.':F'.$summer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totmer, '=SUM(G'.$summer.':G'.$summer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totmer, '=SUM(H'.$summer.':H'.$summer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totmer, '=SUM(I'.$summer.':I'.$summer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totmer, '=SUM(J'.$summer.':J'.$summer1.')');
			$excel->getActiveSheet()->getStyle('D'.$totmer.':J'.$totmer)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totmer.':J'.$totmer)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numer = $totmer;
		$numer += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numer, "55262000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numer, "EXPATRIATE REMUNERATION");
		$excel->getActiveSheet()->getStyle('C'.$numer)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numer)->applyFromArray($style_sub);

		$category = "er";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrower= $numer;
		$numrower += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrower, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrower, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrower)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrower, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrower, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrower, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrower, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrower, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrower, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrower, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrower.':J'.$numrower)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrower++;
		}

			$toter = $numrower;
			$toter += 0;
			$sumer = $numer+=1;
			$sumer1 = $numrower - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toter, "Subtotal Expatriate Remuneration");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toter, '=SUM(E'.$sumer.':E'.$sumer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toter, '=SUM(F'.$sumer.':F'.$sumer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toter, '=SUM(G'.$sumer.':G'.$sumer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toter, '=SUM(H'.$sumer.':H'.$sumer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toter, '=SUM(I'.$sumer.':I'.$sumer1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toter, '=SUM(J'.$sumer.':J'.$sumer1.')');
			$excel->getActiveSheet()->getStyle('D'.$toter.':J'.$toter)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toter.':J'.$toter)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$toter1 = $toter;
			$toter1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$toter1, "SUBTOTAL MANAGEMENT & EXPATRIATE REMUNERATION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$toter1, '=SUM(E'.$summer.':E'.$toter.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$toter1, '=SUM(F'.$summer.':F'.$toter.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$toter1, '=SUM(G'.$summer.':G'.$toter.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$toter1, '=SUM(H'.$summer.':H'.$toter.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$toter1, '=SUM(I'.$summer.':I'.$toter.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$toter1, '=SUM(J'.$summer.':J'.$toter.')/2');
			$excel->getActiveSheet()->getStyle('D'.$toter1.':J'.$toter1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$toter1.':J'.$toter1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numots = $toter1;
		$numots += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numots, "55270000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numots, "OTHERS");
		$excel->getActiveSheet()->getStyle('C'.$numots)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numots)->applyFromArray($style_sub);

		$category = "ots";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowots= $numots;
		$numrowots += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowots, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowots, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowots)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowots, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowots, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowots, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowots, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowots, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowots, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowots, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowots.':J'.$numrowots)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowots++;
		}

			$totots = $numrowots;
			$totots += 0;
			$sumots = $numots+=1;
			$sumots1 = $numrowots - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totots, "SUBTOTAL OTHERS  - ADMINISTRATIVE EXPENSE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totots, '=SUM(E'.$sumots.':E'.$sumots1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totots, '=SUM(F'.$sumots.':F'.$sumots1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totots, '=SUM(G'.$sumots.':G'.$sumots1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totots, '=SUM(H'.$sumots.':H'.$sumots1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totots, '=SUM(I'.$sumots.':I'.$sumots1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totots, '=SUM(J'.$sumots.':J'.$sumots1.')');
			$excel->getActiveSheet()->getStyle('D'.$totots.':J'.$totots)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totots.':J'.$totots)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totots1 = $totots;
			$totots1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totots1, "SUBTOTAL ADMINISTRATIVE EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totots1, '=E'.$totots.'+E'.$toter1.'+E'.$totdac.'+E'.$totmare.'+E'.$tottad1.'+E'.$totins.'+E'.$totpfs1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totots1, '=F'.$totots.'+F'.$toter1.'+F'.$totdac.'+F'.$totmare.'+F'.$tottad1.'+F'.$totins.'+F'.$totpfs1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totots1, '=G'.$totots.'+G'.$toter1.'+G'.$totdac.'+G'.$totmare.'+G'.$tottad1.'+G'.$totins.'+G'.$totpfs1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totots1, '=H'.$totots.'+H'.$toter1.'+H'.$totdac.'+H'.$totmare.'+H'.$tottad1.'+H'.$totins.'+H'.$totpfs1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totots1, '=I'.$totots.'+I'.$toter1.'+I'.$totdac.'+I'.$totmare.'+I'.$tottad1.'+I'.$totins.'+I'.$totpfs1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totots1, '=J'.$totots.'+J'.$toter1.'+J'.$totdac.'+J'.$totmare.'+J'.$tottad1.'+J'.$totins.'+J'.$totpfs1);
			$excel->getActiveSheet()->getStyle('D'.$totots1.':J'.$totots1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totots1.':J'.$totots1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totots2 = $totots1;
			$totots2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totots2, "SUBTOTAL GENERAL AND ADMINISTRATIVE EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totots2, '=E'.$totots1.'+E'.$totopaf1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totots2, '=F'.$totots1.'+F'.$totopaf1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totots2, '=G'.$totots1.'+G'.$totopaf1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totots2, '=H'.$totots1.'+H'.$totopaf1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totots2, '=I'.$totots1.'+I'.$totopaf1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totots2, '=J'.$totots1.'+J'.$totopaf1);
			$excel->getActiveSheet()->getStyle('D'.$totots2.':J'.$totots2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totots2.':J'.$totots2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcos = $totots2;
		$numcos += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcos, "56000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcos, "COST OF SERVICES");
		$excel->getActiveSheet()->getStyle('C'.$numcos)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcos)->applyFromArray($style_sub);

		$numcos1 = $numcos;
		$numcos1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcos1, "56100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcos1, "COST OF PRODUCTS");
		$excel->getActiveSheet()->getStyle('C'.$numcos1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcos1)->applyFromArray($style_sub);

		$numcos2 = $numcos1;
		$numcos2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcos2, "56110000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcos2, "NFC card or Sticker");
		$excel->getActiveSheet()->getStyle('C'.$numcos2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcos2)->applyFromArray($style_sub);

		$category = "cos";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcos= $numcos2;
		$numrowcos += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcos, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcos, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcos)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcos, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcos, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcos, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcos, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcos, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcos, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcos, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcos.':J'.$numrowcos)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcos++;
		}

			$totcos = $numrowcos;
			$totcos += 0;
			$sumcos = $numcos2+=1;
			$sumcos1 = $numrowcos - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcos, "Subtotal NFC card or Sticker");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcos, '=SUM(E'.$sumcos.':E'.$sumcos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcos, '=SUM(F'.$sumcos.':F'.$sumcos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcos, '=SUM(G'.$sumcos.':G'.$sumcos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcos, '=SUM(H'.$sumcos.':H'.$sumcos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcos, '=SUM(I'.$sumcos.':I'.$sumcos1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcos, '=SUM(J'.$sumcos.':J'.$sumcos1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcos.':J'.$totcos)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcos.':J'.$totcos)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdvs = $totcos;
		$numdvs += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdvs, "56120000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdvs, "DEVICES");
		$excel->getActiveSheet()->getStyle('C'.$numdvs)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdvs)->applyFromArray($style_sub);

		$category = "dvs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdvs= $numdvs;
		$numrowdvs += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdvs, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdvs, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdvs)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdvs, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdvs, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdvs, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdvs, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowdvs, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowdvs, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowdvs, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdvs.':J'.$numrowdvs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdvs++;
		}

			$totdvs = $numrowdvs;
			$totdvs += 0;
			$sumdvs = $numdvs+=1;
			$sumdvs1 = $numrowdvs - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdvs, "Subtotal Devices");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdvs, '=SUM(E'.$sumdvs.':E'.$sumdvs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdvs, '=SUM(F'.$sumdvs.':F'.$sumdvs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdvs, '=SUM(G'.$sumdvs.':G'.$sumdvs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdvs, '=SUM(H'.$sumdvs.':H'.$sumdvs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdvs, '=SUM(I'.$sumdvs.':I'.$sumdvs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdvs, '=SUM(J'.$sumdvs.':J'.$sumdvs1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdvs.':J'.$totdvs)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdvs.':J'.$totdvs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numobs = $totdvs;
		$numobs += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numobs, "56130000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numobs, "OBSOLESCENCE");
		$excel->getActiveSheet()->getStyle('C'.$numobs)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numobs)->applyFromArray($style_sub);

		$category = "obs";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowobs= $numobs;
		$numrowobs += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowobs, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowobs, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowobs)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowobs, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowobs, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowobs, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowobs, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowobs, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowobs, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowobs, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowobs.':J'.$numrowobs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowobs++;
		}

			$totobs = $numrowobs;
			$totobs += 0;
			$sumobs = $numobs+=1;
			$sumobs1 = $numrowobs - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totobs, "Subtotal Obsolescence");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totobs, '=SUM(E'.$sumobs.':E'.$sumobs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totobs, '=SUM(F'.$sumobs.':F'.$sumobs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totobs, '=SUM(G'.$sumobs.':G'.$sumobs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totobs, '=SUM(H'.$sumobs.':H'.$sumobs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totobs, '=SUM(I'.$sumobs.':I'.$sumobs1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totobs, '=SUM(J'.$sumobs.':J'.$sumobs1.')');
			$excel->getActiveSheet()->getStyle('D'.$totobs.':J'.$totobs)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totobs.':J'.$totobs)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totobs1 = $totobs;
			$totobs1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totobs1, "SUBTOTAL COST OF PRODUCTS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totobs1, '=SUM(E'.$sumcos.':E'.$totobs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totobs1, '=SUM(F'.$sumcos.':F'.$totobs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totobs1, '=SUM(G'.$sumcos.':G'.$totobs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totobs1, '=SUM(H'.$sumcos.':H'.$totobs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totobs1, '=SUM(I'.$sumcos.':I'.$totobs.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totobs1, '=SUM(J'.$sumcos.':J'.$totobs.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totobs1.':J'.$totobs1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totobs1.':J'.$totobs1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numcms = $totobs1;
		$numcms += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcms, "56200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcms, "COST OF MERCHANT SERVICE");
		$excel->getActiveSheet()->getStyle('C'.$numcms)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcms)->applyFromArray($style_sub);

		$numcms1 = $numcms;
		$numcms1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcms1, "56210000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcms1, "COST OF MERCHANT SERVICE");
		$excel->getActiveSheet()->getStyle('C'.$numcms1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcms1)->applyFromArray($style_sub);

		$numcms2 = $numcms1;
		$numcms2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcms2, "56211000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numcms2, "COST OF Virtual Account BANK");
		$excel->getActiveSheet()->getStyle('C'.$numcms2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numcms2)->applyFromArray($style_sub);

		$category = "cms";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowcms= $numcms2;
		$numrowcms += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcms, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcms, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowcms)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowcms, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcms, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowcms, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowcms, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowcms, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowcms, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowcms, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowcms.':J'.$numrowcms)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowcms++;
		}

			$totcms = $numrowcms;
			$totcms += 0;
			$sumcms = $numcms2+=1;
			$sumcms1 = $numrowcms - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcms, "Subtotal Cost of Collection Bank");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcms, '=SUM(E'.$sumcms.':E'.$sumcms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcms, '=SUM(F'.$sumcms.':F'.$sumcms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcms, '=SUM(G'.$sumcms.':G'.$sumcms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcms, '=SUM(H'.$sumcms.':H'.$sumcms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcms, '=SUM(I'.$sumcms.':I'.$sumcms1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcms, '=SUM(J'.$sumcms.':J'.$sumcms1.')');
			$excel->getActiveSheet()->getStyle('D'.$totcms.':J'.$totcms)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcms.':J'.$totcms)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcms1 = $totcms;
			$totcms1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcms1, "SUBTOTAL COLLECTION COST - OTHERS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcms1, '=SUM(E'.$sumcms.':E'.$totcms.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcms1, '=SUM(F'.$sumcms.':F'.$totcms.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcms1, '=SUM(G'.$sumcms.':G'.$totcms.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcms1, '=SUM(H'.$sumcms.':H'.$totcms.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcms1, '=SUM(I'.$sumcms.':I'.$totcms.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcms1, '=SUM(J'.$sumcms.':J'.$totcms.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totcms1.':J'.$totcms1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcms1.':J'.$totcms1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totcms2 = $totcms1;
			$totcms2 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totcms2, "SUBTOTAL COST OF COLLECTION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totcms2, '=E'.$totcms1);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totcms2, '=F'.$totcms1);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totcms2, '=G'.$totcms1);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totcms2, '=H'.$totcms1);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totcms2, '=I'.$totcms1);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totcms2, '=J'.$totcms1);
			$excel->getActiveSheet()->getStyle('D'.$totcms2.':J'.$totcms2)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totcms2.':J'.$totcms2)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numbd = $totcms2;
		$numbd += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numbd, "56300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numbd, "BAD DEBT");
		$excel->getActiveSheet()->getStyle('C'.$numbd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numbd)->applyFromArray($style_sub);

		$numbd1 = $numbd;
		$numbd1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numbd1, "56310000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numbd1, "PROVISION");
		$excel->getActiveSheet()->getStyle('C'.$numbd1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numbd1)->applyFromArray($style_sub);

		$numbd2 = $numbd1;
		$numbd2 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numbd2, "56311000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numbd2, "MITRA");
		$excel->getActiveSheet()->getStyle('C'.$numbd2)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numbd2)->applyFromArray($style_sub);

		$category = "bd";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowbd= $numbd2;
		$numrowbd += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowbd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowbd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowbd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowbd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowbd, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowbd, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowbd, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowbd, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowbd, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowbd, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowbd.':J'.$numrowbd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowbd++;
		}

			$totbd = $numrowbd;
			$totbd += 0;
			$sumbd = $numbd2+=1;
			$sumbd1 = $numrowbd - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totbd, "Subtotal Operators");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totbd, '=SUM(E'.$sumbd.':E'.$sumbd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totbd, '=SUM(F'.$sumbd.':F'.$sumbd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totbd, '=SUM(G'.$sumbd.':G'.$sumbd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totbd, '=SUM(H'.$sumbd.':H'.$sumbd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totbd, '=SUM(I'.$sumbd.':I'.$sumbd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totbd, '=SUM(J'.$sumbd.':J'.$sumbd1.')');
			$excel->getActiveSheet()->getStyle('D'.$totbd.':J'.$totbd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totbd.':J'.$totbd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numpts = $totbd;
		$numpts += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpts, "56312000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numpts, "PARTNERS");
		$excel->getActiveSheet()->getStyle('C'.$numpts)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numpts)->applyFromArray($style_sub);

		$category = "pts";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowpts= $numpts;
		$numrowpts += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpts, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowpts, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowpts)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowpts, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpts, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowpts, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowpts, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowpts, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowpts, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowpts, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowpts.':J'.$numrowpts)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowpts++;
		}

			$totpts = $numrowpts;
			$totpts += 0;
			$sumpts = $numpts+=1;
			$sumpts1 = $numrowpts - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totpts, "Subtotal Partners");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totpts, '=SUM(E'.$sumpts.':E'.$sumpts1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totpts, '=SUM(F'.$sumpts.':F'.$sumpts1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totpts, '=SUM(G'.$sumpts.':G'.$sumpts1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totpts, '=SUM(H'.$sumpts.':H'.$sumpts1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totpts, '=SUM(I'.$sumpts.':I'.$sumpts1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totpts, '=SUM(J'.$sumpts.':J'.$sumpts1.')');
			$excel->getActiveSheet()->getStyle('D'.$totpts.':J'.$totpts)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totpts.':J'.$totpts)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numtd = $totpts;
		$numtd += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtd, "56313000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtd, "TIME DEPOSIT");
		$excel->getActiveSheet()->getStyle('C'.$numtd)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numtd)->applyFromArray($style_sub);

		$category = "td";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowtd= $numtd;
		$numrowtd += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowtd, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowtd, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowtd)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowtd, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowtd, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowtd, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowtd, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowtd, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowtd, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowtd, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowtd.':J'.$numrowtd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowtd++;
		}

			$tottd = $numrowtd;
			$tottd += 0;
			$sumtd = $numtd+=1;
			$sumtd1 = $numrowtd - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottd, "Subtotal Partners");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottd, '=SUM(E'.$sumtd.':E'.$sumtd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottd, '=SUM(F'.$sumtd.':F'.$sumtd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottd, '=SUM(G'.$sumtd.':G'.$sumtd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottd, '=SUM(H'.$sumtd.':H'.$sumtd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottd, '=SUM(I'.$sumtd.':I'.$sumtd1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottd, '=SUM(J'.$sumtd.':J'.$sumtd1.')');
			$excel->getActiveSheet()->getStyle('D'.$tottd.':J'.$tottd)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottd.':J'.$tottd)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tottd1 = $tottd;
			$tottd1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottd1, "SUBTOTAL BAD DEBT - PROVISION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottd1, '=SUM(E'.$sumbd.':E'.$tottd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottd1, '=SUM(F'.$sumbd.':F'.$tottd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottd1, '=SUM(G'.$sumbd.':G'.$tottd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottd1, '=SUM(H'.$sumbd.':H'.$tottd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottd1, '=SUM(I'.$sumbd.':I'.$tottd.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottd1, '=SUM(J'.$sumbd.':J'.$tottd.')/2');
			$excel->getActiveSheet()->getStyle('D'.$tottd1.':J'.$tottd1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tottd1.':J'.$tottd1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numotc = $tottd1;
		$numotc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numotc, "56400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numotc, "OTHERS COST");
		$excel->getActiveSheet()->getStyle('C'.$numotc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numotc)->applyFromArray($style_sub);

		$category = "otc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowotc= $numotc;
		$numrowotc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowotc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowotc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowotc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowotc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowotc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowotc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowotc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowotc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowotc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowotc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowotc.':J'.$numrowotc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowotc++;
		}

			$tototc = $numrowotc;
			$tototc += 0;
			$sumotc = $numotc+=1;
			$sumotc1 = $numrowotc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototc, "SUBTOTAL OTHERS COST");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototc, '=SUM(E'.$sumotc.':E'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototc, '=SUM(F'.$sumotc.':F'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototc, '=SUM(G'.$sumotc.':G'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tototc, '=SUM(H'.$sumotc.':H'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tototc, '=SUM(I'.$sumotc.':I'.$sumotc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tototc, '=SUM(J'.$sumotc.':J'.$sumotc1.')');
			$excel->getActiveSheet()->getStyle('D'.$tototc.':J'.$tototc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototc.':J'.$tototc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$tototc1 = $tototc;
			$tototc1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$tototc1, "SUBTOTAL COST OF SERVICES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$tototc1, '=E'.$tottd1.'+E'.$totcms2.'+E'.$totobs1.'+E'.$tototc);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$tototc1, '=F'.$tottd1.'+F'.$totcms2.'+F'.$totobs1.'+F'.$tototc);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$tototc1, '=G'.$tottd1.'+G'.$totcms2.'+G'.$totobs1.'+G'.$tototc);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$tototc1, '=H'.$tottd1.'+H'.$totcms2.'+H'.$totobs1.'+H'.$tototc);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$tototc1, '=I'.$tottd1.'+I'.$totcms2.'+I'.$totobs1.'+I'.$tototc);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$tototc1, '=J'.$tottd1.'+J'.$totcms2.'+J'.$totobs1.'+J'.$tototc);
			$excel->getActiveSheet()->getStyle('D'.$tototc1.':J'.$tototc1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$tototc1.':J'.$tototc1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$tottee = $tototc1;
		$tottee += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottee, "TOTAL EXPENSE EXCLUDING DEPRECIATION & AMORTIZATION");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottee, '=E'.$tototc1.'+E'.$totots2.'+E'.$totome1.'+E'.$toteb2.'+E'.$totdir3);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottee, '=F'.$tototc1.'+F'.$totots2.'+F'.$totome1.'+F'.$toteb2.'+F'.$totdir3);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottee, '=G'.$tototc1.'+G'.$totots2.'+G'.$totome1.'+G'.$toteb2.'+G'.$totdir3);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottee, '=H'.$tototc1.'+H'.$totots2.'+H'.$totome1.'+H'.$toteb2.'+H'.$totdir3);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottee, '=I'.$tototc1.'+I'.$totots2.'+I'.$totome1.'+I'.$toteb2.'+I'.$totdir3);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottee, '=J'.$tototc1.'+J'.$totots2.'+J'.$totome1.'+J'.$toteb2.'+J'.$totdir3);
		$excel->getActiveSheet()->getStyle('D'.$tottee)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$tottee.':J'.$tottee)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$tottee.':J'.$tottee)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$totebi = $tottee;
		$totebi += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$totebi, "EARNINGS BEFORE INTEREST, TAX, DEPRECIATION, AND AMORTIZATION (EBITDA)");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$totebi, '=E'.$tottee.'+E'.$tottotrev);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$totebi, '=F'.$tottee.'+F'.$tottotrev);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$totebi, '=G'.$tottee.'+G'.$tottotrev);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$totebi, '=H'.$tottee.'+H'.$tottotrev);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$totebi, '=I'.$tottee.'+I'.$tottotrev);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$totebi, '=J'.$tottee.'+J'.$tottotrev);
		$excel->getActiveSheet()->getStyle('D'.$totebi)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$totebi.':J'.$totebi)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$totebi.':J'.$totebi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdaa = $totebi;
		$numdaa += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdaa, "51000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdaa, "DEPRECIATION AND AMORTIZATION");
		$excel->getActiveSheet()->getStyle('C'.$numdaa)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdaa)->applyFromArray($style_sub);

		$numdaa1 = $numdaa;
		$numdaa1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdaa1, "51100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdaa1, "DEPRECIATION OF DIRECT OWNERSHIP OF FIXED ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numdaa1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdaa1)->applyFromArray($style_sub);

		$category = "daa";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdaa= $numdaa1;
		$numrowdaa += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdaa, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdaa, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdaa)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdaa, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdaa, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdaa, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdaa, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowdaa, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowdaa, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowdaa, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdaa.':J'.$numrowdaa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdaa++;
		}

			$totdaa = $numrowdaa;
			$totdaa += 0;
			$sumdaa = $numdaa1+=1;
			$sumdaa1 = $numrowdaa - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdaa, "Subtotal Depreciation of Direct Ownership of Fixed Assets");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdaa, '=SUM(E'.$sumdaa.':E'.$sumdaa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdaa, '=SUM(F'.$sumdaa.':F'.$sumdaa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdaa, '=SUM(G'.$sumdaa.':G'.$sumdaa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdaa, '=SUM(H'.$sumdaa.':H'.$sumdaa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdaa, '=SUM(I'.$sumdaa.':I'.$sumdaa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdaa, '=SUM(J'.$sumdaa.':J'.$sumdaa1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdaa.':J'.$totdaa)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdaa.':J'.$totdaa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numdola = $totdaa;
		$numdola += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdola, "51200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numdola, "DEPRECIATION OF LEASED ASSETS");
		$excel->getActiveSheet()->getStyle('C'.$numdola)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numdola)->applyFromArray($style_sub);

		$category = "dola";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowdola= $numdola;
		$numrowdola += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdola, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdola, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowdola)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowdola, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdola, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowdola, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowdola, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowdola, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowdola, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowdola, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowdola.':J'.$numrowdola)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowdola++;
		}

			$totdola = $numrowdola;
			$totdola += 0;
			$sumdola = $numdola+=1;
			$sumdola1 = $numrowdola - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totdola, "Subtotal Depreciation of Leased Assets");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totdola, '=SUM(E'.$sumdola.':E'.$sumdola1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totdola, '=SUM(F'.$sumdola.':F'.$sumdola1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totdola, '=SUM(G'.$sumdola.':G'.$sumdola1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totdola, '=SUM(H'.$sumdola.':H'.$sumdola1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totdola, '=SUM(I'.$sumdola.':I'.$sumdola1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totdola, '=SUM(J'.$sumdola.':J'.$sumdola1.')');
			$excel->getActiveSheet()->getStyle('D'.$totdola.':J'.$totdola)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totdola.':J'.$totdola)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numamo = $totdola;
		$numamo += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numamo, "51300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numamo, "AMORTIZATION");
		$excel->getActiveSheet()->getStyle('C'.$numamo)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numamo)->applyFromArray($style_sub);

		$category = "amo";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowamo= $numamo;
		$numrowamo += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowamo, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowamo, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowamo)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowamo, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowamo, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowamo, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowamo, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowamo, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowamo, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowamo, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowamo.':J'.$numrowamo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowamo++;
		}

			$totamo = $numrowamo;
			$totamo += 0;
			$sumamo = $numamo+=1;
			$sumamo1 = $numrowamo - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totamo, "Subtotal Amortization");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totamo, '=SUM(E'.$sumamo.':E'.$sumamo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totamo, '=SUM(F'.$sumamo.':F'.$sumamo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totamo, '=SUM(G'.$sumamo.':G'.$sumamo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totamo, '=SUM(H'.$sumamo.':H'.$sumamo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totamo, '=SUM(I'.$sumamo.':I'.$sumamo1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totamo, '=SUM(J'.$sumamo.':J'.$sumamo1.')');
			$excel->getActiveSheet()->getStyle('D'.$totamo.':J'.$totamo)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totamo.':J'.$totamo)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totamo1 = $totamo;
			$totamo1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totamo1, "SUBTOTAL DEPRECIATION AND AMORTIZATION");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totamo1, '=SUM(E'.$sumdaa.':E'.$totamo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totamo1, '=SUM(F'.$sumdaa.':F'.$totamo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totamo1, '=SUM(G'.$sumdaa.':G'.$totamo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totamo1, '=SUM(H'.$sumdaa.':H'.$totamo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totamo1, '=SUM(I'.$sumdaa.':I'.$totamo.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totamo1, '=SUM(J'.$sumdaa.':J'.$totamo.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totamo1.':J'.$totamo1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totamo1.':J'.$totamo1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$totoe = $totamo1;
		$totoe += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoe, "TOTAL OPERATING EXPENSE INCLUDE DEPRECIATION & AMORTIZATION");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoe, '=E'.$totamo1.'+E'.$tottee);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoe, '=F'.$totamo1.'+F'.$tottee);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoe, '=G'.$totamo1.'+G'.$tottee);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoe, '=H'.$totamo1.'+H'.$tottee);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoe, '=I'.$totamo1.'+I'.$tottee);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoe, '=J'.$totamo1.'+J'.$tottee);
		$excel->getActiveSheet()->getStyle('D'.$totoe)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$totoe.':J'.$totoe)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$totoe.':J'.$totoe)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$totebia = $totoe;
		$totebia += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$totebia, "EARNINGS BEFORE INTEREST AND TAX (EBIT)");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$totebia, '=E'.$totamo1.'+E'.$totebi);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$totebia, '=F'.$totamo1.'+F'.$totebi);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$totebia, '=G'.$totamo1.'+G'.$totebi);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$totebia, '=H'.$totamo1.'+H'.$totebi);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$totebia, '=I'.$totamo1.'+I'.$totebi);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$totebia, '=J'.$totamo1.'+J'.$totebi);
		$excel->getActiveSheet()->getStyle('D'.$totebia)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$totebia.':J'.$totebia)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$totebia.':J'.$totebia)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numoie = $totebia;
		$numoie += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoie, "60000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoie, "OTHER INCOME/ENXPENSE");
		$excel->getActiveSheet()->getStyle('C'.$numoie)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoie)->applyFromArray($style_sub);

		$numoie1 = $numoie;
		$numoie1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoie1, "61100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoie1, "FOREIGN EXCHANGE");
		$excel->getActiveSheet()->getStyle('C'.$numoie1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoie1)->applyFromArray($style_sub);

		$category = "oie";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowoie= $numoie1;
		$numrowoie += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowoie, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoie, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowoie)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowoie, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoie, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowoie, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowoie, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowoie, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowoie, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoie, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowoie.':J'.$numrowoie)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowoie++;
		}

			$totoie = $numrowoie;
			$totoie += 0;
			$sumoie = $numoie1+=1;
			$sumoie1 = $numrowoie - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoie, "SUBTOTAL FOREIGN EXCHANGE");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoie, '=SUM(E'.$sumoie.':E'.$sumoie1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoie, '=SUM(F'.$sumoie.':F'.$sumoie1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoie, '=SUM(G'.$sumoie.':G'.$sumoie1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoie, '=SUM(H'.$sumoie.':H'.$sumoie1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoie, '=SUM(I'.$sumoie.':I'.$sumoie1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoie, '=SUM(J'.$sumoie.':J'.$sumoie1.')');
			$excel->getActiveSheet()->getStyle('D'.$totoie.':J'.$totoie)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoie.':J'.$totoie)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numgloa = $totoie;
		$numgloa += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numgloa, "61200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numgloa, "(GAIN) LOSS ON ASSETS ");
		$excel->getActiveSheet()->getStyle('C'.$numgloa)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numgloa)->applyFromArray($style_sub);

		$category = "gloa";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowgloa= $numgloa;
		$numrowgloa += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowgloa, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowgloa, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowgloa)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowgloa, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowgloa, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowgloa, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowgloa, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowgloa, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowgloa, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowgloa, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowgloa.':J'.$numrowgloa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowgloa++;
		}

			$totgloa = $numrowgloa;
			$totgloa += 0;
			$sumgloa = $numgloa+=1;
			$sumgloa1 = $numrowgloa - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totgloa, "SUBTOTAL (GAIN) LOSS ON ASSETS");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totgloa, '=SUM(E'.$sumgloa.':E'.$sumgloa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totgloa, '=SUM(F'.$sumgloa.':F'.$sumgloa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totgloa, '=SUM(G'.$sumgloa.':G'.$sumgloa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totgloa, '=SUM(H'.$sumgloa.':H'.$sumgloa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totgloa, '=SUM(I'.$sumgloa.':I'.$sumgloa1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totgloa, '=SUM(J'.$sumgloa.':J'.$sumgloa1.')');
			$excel->getActiveSheet()->getStyle('D'.$totgloa.':J'.$totgloa)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totgloa.':J'.$totgloa)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numoi = $totgloa;
		$numoi += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoi, "61300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoi, "OTHER INCOME / EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numoi)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoi)->applyFromArray($style_sub);

		$numoi1 = $numoi;
		$numoi1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoi1, "61310000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numoi1, "TAX RELATED EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numoi1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numoi1)->applyFromArray($style_sub);

		$category = "oi";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowoi= $numoi1;
		$numrowoi += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowoi, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowoi, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowoi)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowoi, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowoi, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowoi, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowoi, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowoi, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowoi, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowoi, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowoi.':J'.$numrowoi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowoi++;
		}

			$totoi = $numrowoi;
			$totoi += 0;
			$sumoi = $numoi1+=1;
			$sumoi1 = $numrowoi - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totoi, "Subtotal Tax Related Expenses");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totoi, '=SUM(E'.$sumoi.':E'.$sumoi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totoi, '=SUM(F'.$sumoi.':F'.$sumoi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totoi, '=SUM(G'.$sumoi.':G'.$sumoi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totoi, '=SUM(H'.$sumoi.':H'.$sumoi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totoi, '=SUM(I'.$sumoi.':I'.$sumoi1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totoi, '=SUM(J'.$sumoi.':J'.$sumoi1.')');
			$excel->getActiveSheet()->getStyle('D'.$totoi.':J'.$totoi)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totoi.':J'.$totoi)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numlpdp = $totoi;
		$numlpdp += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numlpdp, "61400000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numlpdp, "LATE PROCUREMENT DELIVERABLE PENALTY");
		$excel->getActiveSheet()->getStyle('C'.$numlpdp)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numlpdp)->applyFromArray($style_sub);

		$category = "lpdp";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowlpdp= $numlpdp;
		$numrowlpdp += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowlpdp, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowlpdp, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowlpdp)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowlpdp, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowlpdp, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowlpdp, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowlpdp, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowlpdp, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowlpdp, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowlpdp, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowlpdp.':J'.$numrowlpdp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowlpdp++;
		}

			$totlpdp = $numrowlpdp;
			$totlpdp += 0;
			$sumlpdp = $numlpdp+=1;
			$sumlpdp1 = $numrowlpdp - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totlpdp, "Subtotal Late Procurement Deliverable Penalty");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totlpdp, '=SUM(E'.$sumlpdp.':E'.$sumlpdp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totlpdp, '=SUM(F'.$sumlpdp.':F'.$sumlpdp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totlpdp, '=SUM(G'.$sumlpdp.':G'.$sumlpdp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totlpdp, '=SUM(H'.$sumlpdp.':H'.$sumlpdp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totlpdp, '=SUM(I'.$sumlpdp.':I'.$sumlpdp1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totlpdp, '=SUM(J'.$sumlpdp.':J'.$sumlpdp1.')');
			$excel->getActiveSheet()->getStyle('D'.$totlpdp.':G'.$totlpdp)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totlpdp.':G'.$totlpdp)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numox = $totlpdp;
		$numox += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numox, "61500000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numox, "OTHER INCOME/EXPENSES");
		$excel->getActiveSheet()->getStyle('C'.$numox)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numox)->applyFromArray($style_sub);

		$category = "ox";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowox= $numox;
		$numrowox += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowox, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowox, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowox)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowox, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowox, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowox, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowox, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowox, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowox, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowox, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowox.':J'.$numrowox)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowox++;
		}

			$totox = $numrowox;
			$totox += 0;
			$sumox = $numox+=1;
			$sumox1 = $numrowox - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totox, "Subtotal Other Income/Expenses");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totox, '=SUM(E'.$sumox.':E'.$sumox1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totox, '=SUM(F'.$sumox.':F'.$sumox1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totox, '=SUM(G'.$sumox.':G'.$sumox1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totox, '=SUM(H'.$sumox.':H'.$sumox1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totox, '=SUM(I'.$sumox.':I'.$sumox1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totox, '=SUM(J'.$sumox.':J'.$sumox1.')');
			$excel->getActiveSheet()->getStyle('D'.$totox.':J'.$totox)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totox.':J'.$totox)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totox1 = $totox;
			$totox1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totox1, "SUBTOTAL OTHER INCOME / EXPENSES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totox1, '=SUM(E'.$sumoi.':E'.$totox.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totox1, '=SUM(F'.$sumoi.':F'.$totox.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totox1, '=SUM(G'.$sumoi.':G'.$totox.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totox1, '=SUM(H'.$sumoi.':H'.$totox.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totox1, '=SUM(I'.$sumoi.':I'.$totox.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totox1, '=SUM(J'.$sumoi.':J'.$totox.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totox1.':J'.$totox1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totox1.':J'.$totox1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numfiac = $totox1;
		$numfiac += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numfiac, "62000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numfiac, "FINANCE INCOME AND CHARGES");
		$excel->getActiveSheet()->getStyle('C'.$numfiac)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numfiac)->applyFromArray($style_sub);

		$numfiac1 = $numfiac;
		$numfiac1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numfiac1, "62100000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numfiac1, "FINANCE INCOME");
		$excel->getActiveSheet()->getStyle('C'.$numfiac1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numfiac1)->applyFromArray($style_sub);

		$category = "fiac";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowfiac= $numfiac1;
		$numrowfiac += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfiac, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowfiac, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowfiac)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowfiac, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfiac, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowfiac, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowfiac, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowfiac, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowfiac, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowfiac, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowfiac.':J'.$numrowfiac)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowfiac++;
		}

			$totfiac = $numrowfiac;
			$totfiac += 0;
			$sumfiac = $numfiac1+=1;
			$sumfiac1 = $numrowfiac - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfiac, "SUBTOTAL FINANCE INCOME");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfiac, '=SUM(E'.$sumfiac.':E'.$sumfiac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfiac, '=SUM(F'.$sumfiac.':F'.$sumfiac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfiac, '=SUM(G'.$sumfiac.':G'.$sumfiac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfiac, '=SUM(H'.$sumfiac.':H'.$sumfiac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfiac, '=SUM(I'.$sumfiac.':I'.$sumfiac1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfiac, '=SUM(J'.$sumfiac.':J'.$sumfiac1.')');
			$excel->getActiveSheet()->getStyle('D'.$totfiac.':J'.$totfiac)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfiac.':J'.$totfiac)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numfc = $totfiac;
		$numfc += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numfc, "62200000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numfc, "FINANCE CHARGES");
		$excel->getActiveSheet()->getStyle('C'.$numfc)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numfc)->applyFromArray($style_sub);

		$category = "fc";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowfc= $numfc;
		$numrowfc += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfc, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowfc, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowfc)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowfc, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfc, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowfc, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowfc, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowfc, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowfc, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowfc, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowfc.':J'.$numrowfc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowfc++;
		}

			$totfc = $numrowfc;
			$totfc += 0;
			$sumfc = $numfc+=1;
			$sumfc1 = $numrowfc - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfc, "SUBTOTAL FINANCE CHARGES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfc, '=SUM(E'.$sumfc.':E'.$sumfc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfc, '=SUM(F'.$sumfc.':F'.$sumfc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfc, '=SUM(G'.$sumfc.':G'.$sumfc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfc, '=SUM(H'.$sumfc.':H'.$sumfc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfc, '=SUM(I'.$sumfc.':I'.$sumfc1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfc, '=SUM(J'.$sumfc.':J'.$sumfc1.')');
			$excel->getActiveSheet()->getStyle('D'.$totfc.':J'.$totfc)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfc.':J'.$totfc)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numfco = $totfc;
		$numfco += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numfco, "62300000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numfco, "FINANCE CHARGES - OTHERS");
		$excel->getActiveSheet()->getStyle('C'.$numfco)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numfco)->applyFromArray($style_sub);

		$category = "fco";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowfco= $numfco;
		$numrowfco += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfco, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowfco, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowfco)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowfco, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfco, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowfco, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowfco, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowfco, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowfco, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowfco, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowfco.':J'.$numrowfco)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowfco++;
		}

			$totfco = $numrowfco;
			$totfco += 0;
			$sumfco = $numfco+=1;
			$sumfco1 = $numrowfco - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfco, "SUBTOTAL FINANCE CHARGES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfco, '=SUM(E'.$sumfco.':E'.$sumfco1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfco, '=SUM(F'.$sumfco.':F'.$sumfco1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfco, '=SUM(G'.$sumfco.':G'.$sumfco1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfco, '=SUM(H'.$sumfco.':H'.$sumfco1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfco, '=SUM(I'.$sumfco.':I'.$sumfco1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfco, '=SUM(J'.$sumfco.':J'.$sumfco1.')');
			$excel->getActiveSheet()->getStyle('D'.$totfco.':J'.$totfco)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfco.':J'.$totfco)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$totfco1 = $totfco;
			$totfco1 += 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totfco1, "SUBTOTAL FINANCE INCOME AND CHARGES");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totfco1, '=SUM(E'.$sumfiac.':E'.$totfco.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totfco1, '=SUM(F'.$sumfiac.':F'.$totfco.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totfco1, '=SUM(G'.$sumfiac.':G'.$totfco.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totfco1, '=SUM(H'.$sumfiac.':H'.$totfco.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totfco1, '=SUM(I'.$sumfiac.':I'.$totfco.')/2');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totfco1, '=SUM(J'.$sumfiac.':J'.$totfco.')/2');
			$excel->getActiveSheet()->getStyle('D'.$totfco1.':J'.$totfco1)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totfco1.':J'.$totfco1)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$totebt = $totfco1;
		$totebt += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$totebt, "EARNINGS BEFORE TAX (EBT)");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$totebt, '=E'.$totfco1.'+E'.$totox1.'+E'.$totgloa.'+E'.$totoie.'+E'.$totebia);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$totebt, '=F'.$totfco1.'+F'.$totox1.'+F'.$totgloa.'+F'.$totoie.'+F'.$totebia);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$totebt, '=G'.$totfco1.'+G'.$totox1.'+G'.$totgloa.'+G'.$totoie.'+G'.$totebia);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$totebt, '=H'.$totfco1.'+H'.$totox1.'+H'.$totgloa.'+H'.$totoie.'+H'.$totebia);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$totebt, '=I'.$totfco1.'+I'.$totox1.'+I'.$totgloa.'+I'.$totoie.'+I'.$totebia);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$totebt, '=J'.$totfco1.'+J'.$totox1.'+J'.$totgloa.'+J'.$totoie.'+J'.$totebia);
		$excel->getActiveSheet()->getStyle('D'.$totebt)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$totebt.':J'.$totebt)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$totebt.':J'.$totebt)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numitoci = $totebt;
		$numitoci += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numitoci, "80000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numitoci, "INCOME TAX & OTHER COMPREHENSIVE INCOME");
		$excel->getActiveSheet()->getStyle('C'.$numitoci)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numitoci)->applyFromArray($style_sub);

		$numitoci1 = $numitoci;
		$numitoci1 += 1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numitoci1, "81000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numitoci1, "INCOME TAX (EXPENSES)/BENEFIT");
		$excel->getActiveSheet()->getStyle('C'.$numitoci1)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numitoci1)->applyFromArray($style_sub);

		$category = "itoci";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowitoci= $numitoci1;
		$numrowitoci += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowitoci, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowitoci, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowitoci)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowitoci, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowitoci, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowitoci, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowitoci, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowitoci, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowitoci, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowitoci, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowitoci.':J'.$numrowitoci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowitoci++;
		}

			$totitoci = $numrowitoci;
			$totitoci += 0;
			$sumitoci = $numitoci1+=1;
			$sumitoci1 = $numrowitoci - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totitoci, "SUBTOTAL INCOME TAX EXPENSES/BENEFIT");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totitoci, '=SUM(E'.$sumitoci.':E'.$sumitoci1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totitoci, '=SUM(F'.$sumitoci.':F'.$sumitoci1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totitoci, '=SUM(G'.$sumitoci.':G'.$sumitoci1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totitoci, '=SUM(H'.$sumitoci.':H'.$sumitoci1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totitoci, '=SUM(I'.$sumitoci.':I'.$sumitoci1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totitoci, '=SUM(J'.$sumitoci.':J'.$sumitoci1.')');
			$excel->getActiveSheet()->getStyle('D'.$totitoci.':J'.$totitoci)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totitoci.':J'.$totitoci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$toteat = $totitoci;
		$toteat += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$toteat, "EARNINGS AFTER TAX (EAT)");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$toteat, '=E'.$totitoci.'+E'.$totebt);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$toteat, '=F'.$totitoci.'+F'.$totebt);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$toteat, '=G'.$totitoci.'+G'.$totebt);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$toteat, '=H'.$totitoci.'+H'.$totebt);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$toteat, '=I'.$totitoci.'+I'.$totebt);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$toteat, '=J'.$totitoci.'+J'.$totebt);
		$excel->getActiveSheet()->getStyle('D'.$toteat)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$toteat.':J'.$toteat)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$toteat.':J'.$toteat)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$numocin = $toteat;
		$numocin += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numocin, "82000000");
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numocin, "OTHER COMPREHENSIVE INCOME");
		$excel->getActiveSheet()->getStyle('C'.$numocin)->applyFromArray($style_nature);
		$excel->getActiveSheet()->getStyle('D'.$numocin)->applyFromArray($style_sub);

		$category = "ocin";
		if(!isset($data[$category])){
				$data[$category] = array();
		}

		$numrowocin= $numocin;
		$numrowocin += 1;

		foreach($data[$category] as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowocin, $row['GROUP_REPORT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowocin, $row['NATURE']);
			$excel->getActiveSheet()->getStyle('C'.$numrowocin)->applyFromArray($style_row);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowocin, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowocin, $row['PTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowocin, $row['YTD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowocin, $row['PTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowocin, $row['YTD1']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowocin, $row['PTD2']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowocin, $row['YTD2']);
			$excel->getActiveSheet()->getStyle('E'.$numrowocin.':J'.$numrowocin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrowocin++;
		}

			$totocin = $numrowocin;
			$totocin += 0;
			$sumocin = $numocin+=1;
			$sumocin1 = $numrowocin - 1;
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$totocin, "SUBTOTAL OTHER COMPREHENSIVE INCOME");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$totocin, '=SUM(E'.$sumocin.':E'.$sumocin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$totocin, '=SUM(F'.$sumocin.':F'.$sumocin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$totocin, '=SUM(G'.$sumocin.':G'.$sumocin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$totocin, '=SUM(H'.$sumocin.':H'.$sumocin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$totocin, '=SUM(I'.$sumocin.':I'.$sumocin1.')');
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$totocin, '=SUM(J'.$sumocin.':J'.$sumocin1.')');
			$excel->getActiveSheet()->getStyle('D'.$totocin.':J'.$totocin)->applyFromArray($style_total);
			$excel->getActiveSheet()->getStyle('E'.$totocin.':J'.$totocin)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$tottci = $totocin;
		$tottci += 2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$tottci, "TOTAL COMPREHENSIVE INCOME");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$tottci, '=E'.$toteat.'+E'.$totocin);
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$tottci, '=F'.$toteat.'+F'.$totocin);
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$tottci, '=G'.$toteat.'+G'.$totocin);
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$tottci, '=H'.$toteat.'+H'.$totocin);
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$tottci, '=I'.$toteat.'+I'.$totocin);
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$tottci, '=J'.$toteat.'+J'.$totocin);
		$excel->getActiveSheet()->getStyle('D'.$tottci)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$tottci.':J'.$tottci)->applyFromArray($style_total);
		$excel->getActiveSheet()->getStyle('E'.$tottci.':J'.$tottci)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		
		
		
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		
		$excel->getActiveSheet(0)->setTitle("PL DETAIL");
		$excel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PL DETAIL.xls"');
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}

/* End of file Bswp.php */
/* Location: ./application/controllers/report_xls/Bswp.php */