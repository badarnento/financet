<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Capex extends CI_Controller {

	protected $status_header;

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('capex_mdl', 'capex');

	}

	public function index()
	{

		if($this->ion_auth->is_admin() == true || in_array("budget/rkap", $this->session->userdata['menu_url']) ){
			$data['title']          = "RKAP";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/capex_rkap";
			$data['get_exist_year'] = $this->capex->get_exist_year_master();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function budget_header()
	{

		if($this->ion_auth->is_admin() == true || in_array("budget/budget-header", $this->session->userdata['menu_url']) ){
				
			$data['title']          = "E-Budget Tracking";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/capex_bgt_header";
			$data['get_exist_year'] = $this->capex->get_exist_year_rkap();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );

			$groups = $this->session->userdata('group_id');

			foreach ($groups as $key => $value) {

				$grpName = get_group_data($value);
				$group_name[] = $grpName['NAME'];
			}

		    $directorat = check_is_bod();
		    $binding    = check_binding();

		    if(count($directorat) > 1 && $binding['binding'] != false){
				$directorat = $binding['data_binding']['directorat'];
		    }

			$data['su_budget'] = (in_array("SU Budget", $group_name)) ? true : false;
			$data['directorat']   = $directorat;
			$data['binding']      = $binding['binding'];
			$data['data_binding'] = $binding['data_binding'];

			$data['group_name']    = $group_name;
			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_rkap(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year    = $this->input->post('year');
		$get_all = $this->capex->get_datatable($year);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'              => $value['NO'],
					'direktorat'      => $value['DIRECTORAT'],
					'pic'             => $value['PIC'],
					'division'        => $value['DIVISION'],
					'unit'            => $value['UNIT'],
					'tribe_usecase'   => $value['TRIBE_USECASE'],
					'capex_opex'      => $value['CAPEX_OPEX'],
					'b2b_arragement'  => $value['B2B_ARREGEMENT'],
					'sales_chanel'    => $value['SALES_CHANEL'],
					'parent_account'  => $value['PARENT_ACCOUNT'],
					'sub_parent'      => $value['SUB_PARENT'],
					'proc_type'       => $value['PROC_TYPE'],
					'month'           => date("M-y",strtotime($value['MONTH'])),
					'periode'         => $value['PERIODE'],
					'rkap_name'       => $value['RKAP_DESCRIPTION'],
					'nominal'         => number_format($value['NOMINAL'],0,'.',','),
					'target_group'    => $value['TARGET_GROUP'],
					'target_quantity' => $value['TARGET_QUANTITY'],
					'program_type'    => $value['PROGRAM_TYPE'],
					'detail_activity' => $value['DETAIL_ACTIVITY'],
					'vendor'          => $value['VENDOR_POSSIBLE'],
					'moving'          => $value['MOVING'],
					'entry_optimize'  => $value['ENTRY_OPTIMIZE']
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


	public function saveimport()
	{

		$this->load->library('excel');

		if(isset($_FILES["file"]["name"])) {

			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			$req_id = generateRandomString(6).substr(time(), 0, 4);

			foreach($object->getWorksheetIterator() as $worksheet){

				$highestRow    = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();

				for($row=2; $row<=$highestRow; $row++){   

					$month_exp = [];

					$direktorat      = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$pic             = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$no              = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$division        = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$unit        	 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$tribe_usecase   = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$capex_opex      = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$b2b_arragement  = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$sales_chanel    = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$parent_account  = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$sub_parent  	 = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$proc_type       = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					// $month           = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$periode         = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					$rkap_name       = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
					$nominal         = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
					$target_group    = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
					$target_quantity = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
					$program_type    = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
					$detail_activity = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
					$vendor          = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
					$entry_optimize  = $worksheet->getCellByColumnAndRow(21, $row)->getValue();


					$month          = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					// $dateValue      = PHPExcel_Shared_Date::ExcelToPHP($month);
					// $date_converted = date('Y-m-d', $dateValue);
					$month_exp      = explode("-", $month);
					$month_year     = ucfirst(strtolower($month_exp[0]))." 20".(int)$month_exp[1];
					$date_converted = date("Y-m-d", strtotime($month_year));

					$data[] = array(
						'KEY_UPLOAD'       => $req_id,
						'DIRECTORAT'       => $direktorat,
						'PIC'              => $pic,
						'NO'               => ($no) ? ($no) : "0",
						'UNIT'             => $unit,
						'DIVISION'         => $division,
						'TRIBE_USECASE'    => $tribe_usecase,
						'CAPEX_OPEX'       => $capex_opex,
						'B2B_ARREGEMENT'   => $b2b_arragement,
						'SALES_CHANEL'     => $sales_chanel,
						'PARENT_ACCOUNT'   => $parent_account,
						'SUB_PARENT'       => $sub_parent,
						'PROC_TYPE'        => $proc_type,
						'MONTH'            => $date_converted,
						'PERIODE'          => $periode,
						'RKAP_DESCRIPTION' => $rkap_name,
						'NOMINAL'          => $nominal,
						'TARGET_GROUP'     => $target_group,
						'TARGET_QUANTITY'  => $target_quantity,
						'PROGRAM_TYPE'     => $program_type,
						'DETAIL_ACTIVITY'  => $detail_activity,
						'VENDOR_POSSIBLE'  => $vendor,
						'ENTRY_OPTIMIZE'   => $entry_optimize
					);
				}
			}

			/*$this->capex->insertimport($data);
			$this->capex->call_procedure($req_id);

			redirect('capex/index','refresh');*/

			$import = $this->capex->insertimport($data);
			if($import){
				$this->capex->call_procedure($req_id);
				$this->session->set_flashdata('messages', 'Import Success');
				redirect('capex/index','refresh');
			}else{
				$this->session->set_flashdata('error', 'Import Gagal');
			}

		}

	}


	function cetak_data()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('BUDGET')
		->setLastModifiedBy('BUDGET')
		->setTitle("Cetak Data")
		->setSubject("Cetakan")
		->setDescription("Cetak Data")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Direktorat");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PIC");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Division");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Unit");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Tribe/Usecase");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Capex/Opex");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "B2B Arragement with Tsel (Yes/No)");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Sales chanel");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Parent Account");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Sub Parent");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Proc Type");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Month");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Periode");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "RKAP Name (Description)");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Nominal");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Target Group");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Target Quantity");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "program Type");
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "Detail Activity");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "Vendor (if possible)");
		$excel->setActiveSheetIndex(0)->setCellValue('V1', "Entry Optimize Monitize");

		$year      = $this->input->get('year');

		$hasil = $this->capex->get_cetak($year);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$month = date("M-y", strtotime($row['MONTH']));

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['DIRECTORAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['PIC']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NO']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DIVISION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['UNIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['TRIBE_USECASE']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CAPEX_OPEX']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['B2B_ARREGEMENT']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['SALES_CHANEL']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['PARENT_ACCOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['SUB_PARENT']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['PROC_TYPE']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $month);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['PERIODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['RKAP_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NOMINAL']);
			$excel->getActiveSheet()->getStyle('P'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['TARGET_GROUP']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['TARGET_QUANTITY']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['PROGRAM_TYPE']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $row['DETAIL_ACTIVITY']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $row['VENDOR_POSSIBLE']);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $row['ENTRY_OPTIMIZE']);

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
		header('Content-Disposition: attachment; filename="Data.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	public function load_data_header(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year       		= $this->input->post('year');
		$direktorat       	= $this->input->post('direktorat');
		$divisi       		= $this->input->post('divisi');
		$unit       		= $this->input->post('unit');
		$entry       		= $this->input->post('entry');
		$get_all    = $this->capex->get_datatable_header($year, $direktorat, $divisi, $unit, $entry);
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'              => $value['NO'],
					'direktorat'      => $value['DIRECTORAT'],
					'pic'             => $value['PIC'],
					'division'        => $value['DIVISION'],
					'unit'            => $value['UNIT'],
					'tribe_usecase'   => $value['TRIBE_USECASE'],
					'capex_opex'      => $value['CAPEX_OPEX'],
					'b2b_arragement'  => $value['B2B_ARREGEMENT'],
					'sales_chanel'    => $value['SALES_CHANEL'],
					'parent_account'  => $value['PARENT_ACCOUNT'],
					'sub_parent'      => $value['SUB_PARENT'],
					'proc_type'       => $value['PROC_TYPE'],
					'month'           => date("M-y",strtotime($value['MONTH'])),
					'periode'         => $value['PERIODE'],
					'rkap_name'       => $value['RKAP_DESCRIPTION'],
					'nominal'         => number_format($value['NOMINAL'],0,'.',','),
					'target_group'    => $value['TARGET_GROUP'],
					'target_quantity' => $value['TARGET_QUANTITY'],
					'program_type'    => $value['PROGRAM_TYPE'],
					'detail_activity' => $value['DETAIL_ACTIVITY'],
					'vendor'          => $value['VENDOR_POSSIBLE'],
					'entry_optimize'  => $value['ENTRY_OPTIMIZE'],
					'abs_fpjp'        => number_format($value['ABS_FPJP'],0,'.',','),
					'abs_pr'          => number_format($value['ABS_PR'],0,'.',','),
					'abs_po'          => number_format($value['ABS_PO'],0,'.',','),
					'abs_inv'         => number_format($value['ABS_INV'],0,'.',','),
					'abs_pay'         => number_format($value['ABS_PAY'],0,'.',','),
					'reloc_out'       => number_format($value['RELOC_OUT'],0,'.',','),
					'reloc_in'        => number_format($value['RELOC_IN'],0,'.',','),
					'fs'        	  => number_format($value['FS'],0,'.',','),
					'fa_rkap'         => number_format($value['FA_RKAP'],0,'.',','),
					'fa_fs'           => number_format($value['FA_FS'],0,'.',','),
					'fund_buffer'     => number_format($value['FA_BUFFER'],0,'.',',')
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


	function cetak_data_header($param="")
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(300);
		}



		$year       = $this->input->get('year');
		$direktorat = $this->input->get('direktorat');
		$divisi     = $this->input->get('divisi');
		$unit       = $this->input->get('unit');
		$entry      = $this->input->get('entry');

		if($param != ""){

			$decrypt = decrypt_string($param, true);

			if( is_object( json_decode($decrypt))){

				$obj_param  = json_decode($decrypt);
				$year       = $obj_param->year;
				$direktorat = $obj_param->directorat;
				$divisi     = $obj_param->division;
				$unit       = $obj_param->unit;
			}
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		$groups = $this->session->userdata('group_id');


		foreach ($groups as $key => $value) {

			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

		// Settingan awal fil excel
		$excel->getProperties() ->setCreator('BUDGET')
		->setLastModifiedBy('BUDGET')
		->setTitle("Cetak Data")
		->setSubject("Cetakan")
		->setDescription("Cetak Data")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Direktorat");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PIC");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Division");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Unit");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Tribe/Usecase");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Capex/Opex");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "B2B Arragement with Tsel (Yes/No)");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Sales chanel");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Parent Account");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Sub Parent");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Proc Type");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Month");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Periode");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "RKAP Name (Description)");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Nominal");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Target Group");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Target Quantity");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "program Type");
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "Detail Activity");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "Vendor (if possible)");
		$excel->setActiveSheetIndex(0)->setCellValue('V1', "Entry Optimize Monitize");
		$excel->setActiveSheetIndex(0)->setCellValue('W1', "ABS FPJP");
		$excel->setActiveSheetIndex(0)->setCellValue('X1', "ABS PR");
		$excel->setActiveSheetIndex(0)->setCellValue('Y1', "ABS PO");
		$excel->setActiveSheetIndex(0)->setCellValue('Z1', "ABS INV");
		$excel->setActiveSheetIndex(0)->setCellValue('AA1', "ABS PAY");
		$excel->setActiveSheetIndex(0)->setCellValue('AB1', "RELOC OUT");
		$excel->setActiveSheetIndex(0)->setCellValue('AC1', "RELOC IN");
		$excel->setActiveSheetIndex(0)->setCellValue('AD1', "Feasibility Study");
		$excel->setActiveSheetIndex(0)->setCellValue('AE1', "Fund Available RKAP");
		$excel->setActiveSheetIndex(0)->setCellValue('AF1', "Fund Available FS");
		if(in_array("Budget Controller", $group_name)){
			$excel->setActiveSheetIndex(0)->setCellValue('AG1', "FA BUFFER");
		}

		$hasil = $this->capex->get_cetak_header($year, $direktorat, $divisi, $unit, $entry);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$month = date("M-y", strtotime($row['MONTH']));

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['DIRECTORAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['PIC']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NO']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DIVISION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['UNIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['TRIBE_USECASE']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CAPEX_OPEX']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['B2B_ARREGEMENT']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['SALES_CHANEL']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['PARENT_ACCOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['SUB_PARENT']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['PROC_TYPE']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $month);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['PERIODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['RKAP_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NOMINAL']);
			$excel->getActiveSheet()->getStyle('P'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['TARGET_GROUP']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['TARGET_QUANTITY']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['PROGRAM_TYPE']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $row['DETAIL_ACTIVITY']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $row['VENDOR_POSSIBLE']);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $row['ENTRY_OPTIMIZE']);
			$excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $row['ABS_FPJP']);
			$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $row['ABS_PR']);
			$excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $row['ABS_PO']);
			$excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $row['ABS_INV']);
			$excel->setActiveSheetIndex(0)->setCellValue('AA'.$numrow, $row['ABS_PAY']);
			$excel->setActiveSheetIndex(0)->setCellValue('AB'.$numrow, $row['RELOC_OUT']);
			$excel->setActiveSheetIndex(0)->setCellValue('AC'.$numrow, $row['RELOC_IN']);
			$excel->setActiveSheetIndex(0)->setCellValue('AD'.$numrow, $row['FS']);
			$excel->setActiveSheetIndex(0)->setCellValue('AE'.$numrow, $row['FA_RKAP']);
			$excel->setActiveSheetIndex(0)->setCellValue('AF'.$numrow, $row['FA_FS']);
			if(in_array("Budget Controller", $group_name)){
			$excel->setActiveSheetIndex(0)->setCellValue('AG'.$numrow, $row['FA_BUFFER']);
			}

			$excel->getActiveSheet()->getStyle('W'.$numrow.':AG'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

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
		header('Content-Disposition: attachment; filename="Data Header.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	function load_directorate()
	{

		$hasil  = $this->capex->get_directorate();
		$query  = $hasil['query'];
		$result = "";

		foreach($query->result_array() as $row)	{

			$replace = str_replace("&","|AND|", $row['DIRECTORAT_NAME']);

			$result .= "<option value='".$row['ID_DIR_CODE']."' data-nominal='".number_format($row['NOMINAL'],0,'.',',')."', data-ava='".number_format($row['FA_FS'],0,'.',',')."', data-avb='".number_format($row['FA_BUFFER'],0,'.',',')."', data-direktorat='".$replace."' >".$row['DIRECTORAT_NAME']."</option>";

		}

		echo $result;

		$query->free_result();

	}


	function load_division()
	{

		$hasil  = $this->capex->get_division();
		$query  = $hasil['query'];			
		$result = "";
		$this->load->library('encryption');



		foreach($query->result_array() as $row)	{

			$replace = str_replace("&","|AND|", $row['DIVISION_NAME']);

			$result .= "<option value='".$row['ID_DIVISION']."' data-divisi='".number_format($row['NOMINAL'], 0,'.',',')."', data-ava='".number_format($row['FA_FS'],0,'.',',')."', data-avb='".number_format($row['FA_BUFFER'],0,'.',',')."', data-div='".$replace."' >".$row['DIVISION_NAME']."</option>";

		}

		echo $result;

		$query->free_result();

	}


	function load_unit()
	{

		$hasil  = $this->capex->get_unit();
		$query  = $hasil['query'];			
		$result = "";

		foreach($query->result_array() as $row)	{
			$replace = str_replace("&","|AND|", $row['UNIT_NAME']);

			$result .= "<option value='".$row['ID_UNIT']."' data-unit='".number_format($row['NOMINAL'],0,'.',',')."', data-ava='".number_format($row['FA_FS'],0,'.',',')."', data-avb='".number_format($row['FA_BUFFER'],0,'.',',')."', data-unt='".$replace."' >".$row['UNIT_NAME']."</option>";

		}

		echo $result;

		$query->free_result();

	}

	function load_entry()
	{

		$directorat = $this->input->post('directorat');
		$hasil      = $this->capex->get_entry($directorat);
		$query      = $hasil['query'];			
		$result     = "";

		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ENTRY_OPTIMIZE']."' data-entry='".$row['ENTRY_OPTIMIZE']."' >".$row['ENTRY_OPTIMIZE']."</option>";

		}

		echo $result;

		$query->free_result();

	}

	function load_nominal()
	{

		$category    = $this->input->post('category');
		$id_dir_code = $this->input->post('id_dir');
		$id_division = $this->input->post('id_div');
		$id_unit     = $this->input->post('id_unit');
		$id_entry    = $this->input->post('id_entry');
		$year        = $this->input->post('year');

		$data = $this->capex->get_nominal($category, $year)->row_array();

		echo json_encode($data);

	}

	function load_nominal_bod()
	{

		$id_dir_code = $this->input->post('id_dir');
		$id_entry    = $this->input->post('id_entry');
		$year        = $this->input->post('year');

		$data = $this->capex->get_nominal_bod($year, $id_entry, $id_dir_code)->row_array();

		echo json_encode($data);

	}

}



/* End of file Capex.php */

/* Location: ./application/controllers/Capex.php */