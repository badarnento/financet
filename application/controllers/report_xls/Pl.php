<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl extends CI_Controller {

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

	public function show_pl(){
		
		if($this->ion_auth->is_admin() == true || in_array("report/pl", $this->session->userdata['menu_url']) ){

			$data['title']          = "PL";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/pl";
			$data['get_exist_year'] = $this->tb->get_exist_year();
			
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

	public function load_pl(){
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(1200);
		}

		ini_set('memory_limit', '-1');

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];
		$bulan 		= $_REQUEST['bulan'];
		$date	    = date("Y-m-d H:i:s");
		
		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PL')
								->setLastModifiedBy('PL')
								->setTitle("PL")
								->setSubject("PL")
								->setDescription("PL")
								->setKeywords("PL");

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

		$style_line_netral = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
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

		$style_line_down_up = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_double_up = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
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
			$objDrawing->setCoordinates('A1');
			$objDrawing->setHeight(90);
			$objDrawing->setWorksheet($excel->getActiveSheet());
		}
		
		$excel->setActiveSheetIndex(0)->setCellValue('A6', "PT FINTEK KARYA NUSANTARA");
		$excel->getActiveSheet()->mergeCells('A6:E6');
		$excel->getActiveSheet()->getStyle('A6')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A7', "STATEMENTS OF PROFIT OR LOSS AND OTHER COMPREHENSIVE INCOME");
		$excel->getActiveSheet()->mergeCells('A7:E7');
		$excel->getActiveSheet()->getStyle('A7')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A8', "FOR THE PERIOD ".strtoupper($bulan).", ".$year." (UNAUDITED)");
		$excel->getActiveSheet()->mergeCells('A8:E8');
		$excel->getActiveSheet()->getStyle('A8')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A9', "( In Indonesian Rupiah )");
		$excel->getActiveSheet()->mergeCells('A9:E9');
		$excel->getActiveSheet()->getStyle('A9')->applyFromArray($style_nature);

		$excel->getActiveSheet()->getStyle('A10:E10')->applyFromArray($style_double);

		$excel->setActiveSheetIndex(0)->setCellValue('E12', "".$year);
		$excel->getActiveSheet()->getStyle('E12')->applyFromArray($style_nature);

		$excel->getActiveSheet()->getStyle('A13:E13')->applyFromArray($style_line);

		$excel->setActiveSheetIndex(0)->setCellValue('B15', "REVENUES");
		$excel->getActiveSheet()->getStyle('B15')->applyFromArray($style_sub);

		$excel->setActiveSheetIndex(0)->setCellValue('D15', "Rp");
		$excel->getActiveSheet()->getStyle('D15')->applyFromArray($style_nature);

		$hasil = $this->report_xls->get_pl($year, $month);

		$data = array();
		/*$category_arr = array(
						"revenues"      => array("41000001", "41000002", "41000003", "41000004", "41000005", "41000006", "41000007", "41000008", "41000009", "41000011", "41000012", "41000013", "42000001", "42000002", "42000003", "43000001", "43000002", "44000001", "44000002", "44000003", "44000004", "44000005", "44000006", "45000001", "45000002", "45000003", "45000004", "45000005", "45000006", "45000007", "46000001", "46000002", "46000003", "46000004", "47000001", "47000002", "48100001", "48200001", "48300001", "48300002", "48300003"),
						"expenses"      => array("52111001", "52112001", "52113001", "52114001", "52211001", "52212001", "52213001", "52214001", "52115001", "52116001", "52117001", "52121001", "52122001", "52131001", "52131002", "52131003", "52131004", "52131005", "52132001", "52132002", "52133001", "52133002", "52134001", "52135001", "52136001", "52311001"),
						"daa"      		=> array("51100001", "51100002", "51100003", "51100004", "51100005", "51100006", "51100007", "51100008", "51100009", "51100011", "51210001", "51210002", "51210003", "51210004", "51210005", "51300001"),
						"pe"      		=> array("53111001", "53111002", "53112001", "53112002", "53121001", "53121002", "53121003", "53121004", "53121005", "53121006", "53122001", "53122002", "53122003", "53210001", "53210002", "53210003", "53210004", "53210005"),
						"cos"      		=> array("56110001", "56110002", "56120001", "56130001", "56130002", "56130003", "56211001", "56211002", "56211003", "56211004", "56211005", "56212001", "56212002", "56311001", "56312001", "56313001"),
						"mse"      		=> array("54111101", "54111201", "54111202", "54111203", "54111204", "54111205", "54111206", "54111207", "54111208", "54111301", "54111302", "54111401", "54111402", "54111501", "54111502", "54111503", "54111601", "54111602", "54111701", "54111702", "54121001", "54121002", "54122001", "54130001", "54130002", "54130003", "54130004", "54130005", "54130006", "54130007", "54130008", "54130009", "54211001", "54212001", "54221001", "54222001", "54230001", "54300001", "54300002", "54400001", "54500001", "54500002", "54500003"),
						"gae"      		=> array("55111001", "55111002", "55112100", "55112101", "55112201", "55113101", "55113201", "55113201", "55114101", "55120001", "55120002", "55120003", "55131001", "55132001", "55141001", "55142001", "55143001", "55150001", "55150002", "55150003", "55150004", "55160001", "55160002", "55211001", "55211002", "55212001", "55220001", "55220002", "55231001", "55232001", "55240001", "55240002", "55240003", "55250001", "55250002", "55261001", "55261002", "55261003", "55262001", "55270001", "55270002", "55270003", "55270004", "55270005"),
						"oie"      		=> array("61100001", "61100002", "61100003"),
						"oi"      		=> array("61310001", "61310002", "61400001", "61500001"),
						"fc"      		=> array("62200001", "62200002", "62200003", "62200004", "62200005", "62300001"),
						"fiac"      	=> array("62100001", "62100002", "62100003"),
						"cit"      		=> array("81000001"),
						"cite"      	=> array("81000002"),
						"ocin"      	=> array("82000001")
					);
*/
					$category_arr = array(
						"revenues"      => array("Revenues"),
						"me"      		=> array("Marketing expenses"),
						"pe"      		=> array("Personnel expenses"),
						"ome"      		=> array("Operations and maintenance expenses"),
						"gae"      		=> array("General and administrative expenses"),
						"dae"      		=> array("Depreciation and amortization expenses"),
						"cos"          => array("Cost of services"),
						"bvae"      	=> array("Bank virtual account expenses"),
						"fel"      		=> array("Foreign exchange loss (net)"),
						"ooi"      		=> array("Other operating income (net)"),
						"fi"      		=> array("Finance income (net of final tax)"),
						"fc"      		=> array("Finance charges"),
						"cite"      	=> array("Current Income Tax Expenses"),
						"df"      		=> array("Deferred"),
						"rdb"      		=> array("Remeasurement of defined benefit pension plans")
					);

		foreach($hasil->result_array() as $row)	{

			$group = $row['GROUP_REPORT'];

			if(in_array($group, $category_arr['revenues'])){
				$data['revenues'][] = $row;
			}elseif(in_array($group, $category_arr['me'])){
				$data['me'][] = $row;
			}elseif(in_array($group, $category_arr['pe'])){
				$data['pe'][] = $row;
			}elseif(in_array($group, $category_arr['ome'])){
				$data['ome'][] = $row;
			}elseif(in_array($group, $category_arr['gae'])){
				$data['gae'][] = $row;
			}elseif(in_array($group, $category_arr['dae'])){
				$data['dae'][] = $row;
			}elseif(in_array($group, $category_arr['cos'])){
				$data['cos'][] = $row;
			}elseif(in_array($group, $category_arr['bvae'])){
				$data['bvae'][] = $row;
			}elseif(in_array($group, $category_arr['fel'])){
				$data['fel'][] = $row;
			}elseif(in_array($group, $category_arr['ooi'])){
				$data['ooi'][] = $row;
			}elseif(in_array($group, $category_arr['fi'])){
				$data['fi'][] = $row;
			}elseif(in_array($group, $category_arr['fc'])){
				$data['fc'][] = $row;
			}elseif(in_array($group, $category_arr['cite'])){
				$data['cite'][] = $row;
			}elseif(in_array($group, $category_arr['df'])){
				$data['df'][] = $row;
			}elseif(in_array($group, $category_arr['rdb'])){
				$data['rdb'][] = $row;
			}
		}

		$category = "revenues";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$revTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$revTotal       += $value['TOTAL_AMOUNT'];
		}

		$numrow = 15;
		if($revTotal > 0 || $revTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $revTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
			$excel->getActiveSheet()->getStyle('D'.$numrow.':E'.$numrow)->applyFromArray($style_line_total);

			$numrow++;
		}

		$operatingrow = $numrow+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$operatingrow, "OPERATING INCOME/EXPENSES");
		$excel->getActiveSheet()->getStyle('B'.$operatingrow)->applyFromArray($style_sub);

		$category = "me";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$meTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$meTotal       += $value['TOTAL_AMOUNT'];
			$meGroup       = $value['GROUP_REPORT'];
		}

		$numrowme = $operatingrow+=1;

		if($meTotal > 0 || $meTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowme, $meGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowme, $meTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowme)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowme++;
		}

		$category = "pe";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$peTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$peTotal       += $value['TOTAL_AMOUNT'];
			$peGroup       = $value['GROUP_REPORT'];
		}

		$numrowpe = $numrowme+=0;

		if($peTotal > 0 || $peTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowpe, $peGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowpe, $peTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowpe)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowpe++;
		}

		$category = "ome";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$omeTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$omeTotal       += $value['TOTAL_AMOUNT'];
			$omeGroup       = $value['GROUP_REPORT'];
		}

		$numrowome = $numrowpe+=0;

		if($omeTotal > 0 || $omeTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowome, $omeGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowome, $omeTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowome)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowome++;
		}

		$category = "gae";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$gaeTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$gaeTotal       += $value['TOTAL_AMOUNT'];
			$gaeGroup       = $value['GROUP_REPORT'];
		}

		$numrowgae = $numrowome+=0;

		if($gaeTotal > 0 || $gaeTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowgae, $gaeGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowgae, $gaeTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowgae)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowgae++;
		}


		$category = "dae";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$daeTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$daeTotal       += $value['TOTAL_AMOUNT'];
			$daeGroup       = $value['GROUP_REPORT'];
		}

		$numrowdae = $numrowgae+=0;

		if($daeTotal > 0 || $daeTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowdae, $daeGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdae, $daeTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowdae)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowdae++;
		}

		
         $category = "cos";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$cosTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$cosTotal       += $value['TOTAL_AMOUNT'];
			$cosGroup= $value['GROUP_REPORT'];
		}

		$numrowcos = $numrowdae+=0;

		if($cosTotal > 0 || $cosTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowcos, $cosGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcos, $cosTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowcos)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowcos++;
		}

		

		$category = "bvae";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$bvaeTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$bvaeTotal       += $value['TOTAL_AMOUNT'];
			$bvaeGroup       = $value['GROUP_REPORT'];
		}

		$numrowbvae = $numrowcos+=0;

		if($bvaeTotal > 0 || $bvaeTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowbvae, $bvaeGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowbvae, $bvaeTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowbvae)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowbvae++;
		}

		$category = "fel";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$felTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$felTotal       += $value['TOTAL_AMOUNT'];
			$felGroup       = $value['GROUP_REPORT'];
		}

		$numrowfel = $numrowbvae+=0;

		if($felTotal > 0 || $felTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfel, $felGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfel, $felTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowfel)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowfel++;
		}

		$category = "ooi";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$ooiTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$ooiTotal       += $value['TOTAL_AMOUNT'];
			$ooiGroup       = $value['GROUP_REPORT'];
		}

		$numrowooi = $numrowfel+=0;

		if($ooiTotal > 0 || $ooiTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowooi, $ooiGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowooi, $ooiTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowooi)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowooi++;
		}

		$totalrow = $numrowooi+=1;
		$sumttl = $numrowme-1;
		$sumttl1 = $numrowooi-2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$totalrow, "Total operating income (net)");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$totalrow, '=SUM(E'.$sumttl.':E'.$sumttl1.')');
		$excel->getActiveSheet()->getStyle('E'.$totalrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$totalrow.':E'.$totalrow)->applyFromArray($style_line_down_up);

		$olrow = $totalrow+=2;
		$sumol = $numrow-2;
		$sumol1 = $totalrow-2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$olrow, "OPERATING LOSS");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$olrow, '=E'.$sumol.'+E'.$sumol1);
		$excel->getActiveSheet()->getStyle('E'.$olrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('B'.$olrow)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('D'.$olrow.':E'.$olrow)->applyFromArray($style_line_down_up);

		$otherrow = $olrow+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$otherrow, "OTHER INCOME/(EXPENSES)");
		$excel->getActiveSheet()->getStyle('B'.$otherrow)->applyFromArray($style_sub);

		$category = "fi";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$fiTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$fiTotal       += $value['TOTAL_AMOUNT'];
			$fiGroup       = $value['GROUP_REPORT'];
		}

		$numrowfi = $otherrow+=1;

		if($fiTotal > 0 || $fiTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfi, $fiGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfi, $fiTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowfi)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowfi++;
		}

		$category = "fc";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$fcTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$fcTotal       += $value['TOTAL_AMOUNT'];
			$fcGroup       = $value['GROUP_REPORT'];
		}

		$numrowfc = $numrowfi+=0;

		if($fcTotal > 0 || $fcTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowfc, $fcGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowfc, $fcTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowfc)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowfc++;
		}

		$toittl = $numrowfc+=0;
		$sumtoi = $numrowfi-1;
		$sumtoi1 = $numrowfc-1;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$toittl, "Total other income (net)");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$toittl, '=SUM(E'.$sumtoi.':E'.$sumtoi1.')');
		$excel->getActiveSheet()->getStyle('E'.$toittl)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('B'.$toittl)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('D'.$toittl.':E'.$toittl)->applyFromArray($style_line_down_up);

		$lbcitbrow = $toittl+=2;
		$ttllbcitb = $olrow-2;
		$ttllbcitb1 = $toittl-2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$lbcitbrow, "LOSS BEFORE CORPORATE INCOME TAX BENEFIT");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$lbcitbrow, '=E'.$ttllbcitb.'+E'.$ttllbcitb1);
		$excel->getActiveSheet()->getStyle('B'.$lbcitbrow)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$lbcitbrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$lbcitbrow.':E'.$lbcitbrow)->applyFromArray($style_total);
		$citrow = $lbcitbrow+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$citrow, "CORPORATE INCOME TAX (EXPENSE)/BENEFIT");
		$excel->getActiveSheet()->getStyle('B'.$citrow)->applyFromArray($style_sub);

		$category = "cite";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$citeTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$citeTotal       += $value['TOTAL_AMOUNT'];
			$citeGroup       = $value['GROUP_REPORT'];
		}

		$numrowcite = $citrow+=1;

		if($citeTotal > 0 || $citeTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowcite, $citeGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowcite, $citeTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowcite)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowcite++;
		}

		$category = "df";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$dfTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$dfTotal       += $value['TOTAL_AMOUNT'];
			$dfGroup       = $value['GROUP_REPORT'];
		}

		$numrowdf = $numrowcite+=0;

		if($dfTotal > 0 || $dfTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowdf, $dfGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowdf, $dfTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowdf)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');

			$numrowdf++;
		}

		$tcitbrow = $numrowdf+=0;
		$ttlltcitb = $numrowcite-1;
		$ttlltcitb1 = $numrowdf-1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$tcitbrow, "Total corporate income tax benefit");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$tcitbrow, '=E'.$ttlltcitb.'+E'.$ttlltcitb1);
		$excel->getActiveSheet()->getStyle('E'.$tcitbrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$tcitbrow.':E'.$tcitbrow)->applyFromArray($style_line_down_up);

		$plrow = $tcitbrow+=2;
		$ttlpl = $lbcitbrow-2;
		$ttlpl1 = $tcitbrow-2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$plrow, "PROFIT (LOSS) FOR THE PERIOD");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$plrow, '=E'.$ttlpl.'+E'.$ttlpl1);
		$excel->getActiveSheet()->getStyle('B'.$plrow)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$plrow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$plrow.':E'.$plrow)->applyFromArray($style_double_up);

		$ocirow = $plrow+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$ocirow, "OTHER COMPREHENSIVE INCOME");
		$excel->getActiveSheet()->getStyle('B'.$ocirow)->applyFromArray($style_sub);

		$fparow = $ocirow+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$fparow, "FOR THE PERIOD AFTER TAX");
		$excel->getActiveSheet()->getStyle('C'.$fparow)->applyFromArray($style_sub);

		$itwrow = $fparow+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$itwrow, "Item that will not be reclassified to profit or loss:");

		$category = "rdb";
		if(!isset($data[$category])){
			$data[$category] = array();
		}

		$rdbTotal = 0;

		foreach ($data[$category] as $key => $value) {
			$rdbTotal       += $value['TOTAL_AMOUNT'];
			$rdbGroup       = $value['GROUP_REPORT'];
		}

		$numrowrdb = $itwrow+=1;

		if($rdbTotal > 0 || $rdbTotal <= 0){

			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowrdb, $rdbGroup);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowrdb, $rdbTotal);
			$excel->getActiveSheet()->getStyle('E'.$numrowrdb)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
			$excel->getActiveSheet()->getStyle('D'.$numrowrdb.':E'.$numrowrdb)->applyFromArray($style_line_total);

			$numrowrdb++;
		}

		$tcirow = $numrowrdb+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$tcirow, "TOTAL COMPREHENSIVE INCOME");
		$excel->getActiveSheet()->getStyle('B'.$tcirow)->applyFromArray($style_sub);

		$ftprow = $tcirow+=1;
		$ttlftp = $plrow-2;
		$ttlftp1 = $numrowrdb-2;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$ftprow, "FOR THE PERIOD");
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$ftprow, '=E'.$ttlftp.'+E'.$ttlftp1);
		$excel->getActiveSheet()->getStyle('C'.$ftprow)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('E'.$ftprow)->getNumberFormat()->setFormatCode('_(\(#,##\);_(#,##_);_("0"_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$ftprow.':E'.$ftprow)->applyFromArray($style_double_up);

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(2);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		
		
		
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		
		$excel->getActiveSheet(0)->setTitle("PL");
		$excel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PL.xls"');
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}

/* End of file Bswp.php */
/* Location: ./application/controllers/report_xls/Bswp.php */