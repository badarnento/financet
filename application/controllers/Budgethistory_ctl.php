<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budgethistory_ctl extends CI_Controller {

	protected $status_header;

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Budgethistory_mdl', 'budget_history');

		$this->status_header = 401;
	}

	public function budget_history_header()
	{

		if($this->ion_auth->is_admin() == true || in_array("budget/budget-history", $this->session->userdata['menu_url']) ){

			$data['title']          = "Budget Header History";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/budget_history";
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


	public function load_data_history_header(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year       		= $this->input->post('year');
		$direktorat       	= $this->input->post('direktorat');
		$divisi       		= $this->input->post('divisi');
		$unit       		= $this->input->post('unit');
		$entry       		= $this->input->post('entry');

		if($year != ""){
			$exp_year = explode("-", $year);
			$year = $exp_year[2]."-".$exp_year[1]."-".$exp_year[0];
		}

		$get_all    = $this->budget_history->get_datatable_header($year, $direktorat, $divisi, $unit, $entry);
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


	function cetak_data_history_header()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(300);
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


		$year       = $this->input->get('year');
		$direktorat = $this->input->get('direktorat');
		$divisi     = $this->input->get('divisi');
		$unit       = $this->input->get('unit');
		$entry      = $this->input->get('entry');

		if($year != ""){
			$exp_year = explode("-", $year);
			$year = $exp_year[2]."-".$exp_year[1]."-".$exp_year[0];
		}

		$hasil = $this->budget_history->get_cetak_header($year, $direktorat, $divisi, $unit, $entry);

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

			$excel->getActiveSheet()->getStyle('W'.$numrow.":AG".$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

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
		header('Content-Disposition: attachment; filename="Data Budget History Header.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	function load_directorate()
	{

		$hasil  = $this->budget_history->get_directorate();
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

		$hasil  = $this->budget_history->get_division();
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

		$hasil  = $this->budget_history->get_unit();
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
		$hasil      = $this->budget_history->get_entry($directorat);
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

		$data = $this->budget_history->get_nominal($category, $year)->row_array();

		echo json_encode($data);

	}

	function load_nominal_bod()
	{

		$id_dir_code = $this->input->post('id_dir');
		$id_entry    = $this->input->post('id_entry');
		$year        = $this->input->post('year');

		$data = $this->budget_history->get_nominal_bod($year, $id_entry, $id_dir_code)->row_array();

		echo json_encode($data);

	}


}

/* End of file Budgethistory_ctl.php */
/* Location: ./application/controllers/Budgethistory_ctl.php */