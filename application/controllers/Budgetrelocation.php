<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Budgetrelocation extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Budgetrelocation_mdl', 'budget_relocation');

	}

	public function load_data_budget_reloc(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('reloc_date_from') != "" && $this->input->post('reloc_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('reloc_date_from'));
			$exp_date_to   = explode("/", $this->input->post('reloc_date_to'));

			$reloc_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$reloc_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all = $this->budget_relocation->get_budget_relocation_datatable($reloc_date_from, $reloc_date_to);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){
			
			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'date_reloc'		 	 => date("d-m-Y",strtotime($value['RELOC_DATE'])),
					'directorate_source'     => $value['DIRECTORATE_SOURCE'],
					'division_source'        => $value['DIVISION_SOURCE'],
					'unit_source'            => $value['UNIT_SOURCE'],
					'rkap_name_source'   	 => $value['RKAP_NAME_SOURCE'],
					//'fs_number_source'   	 => $value['FS_NUMBER_SOURCE'],
					'tribe_usecase_source'   => $value['TRIBE_USECASE_SOURCE'],
					'directorate_target'     => $value['DIRECTORATE_TARGET'],
					'division_target' 	     => $value['DIVISION_TARGET'],
					'unit_target'			 => $value['UNIT_TARGET'],
					'rkap_name_target'  	 => $value['RKAP_NAME_TARGET'],
					'tribe_usecase_target'   => $value['TRIBE_USECASE_TARGET'],
					//'fs_number_target'   	 => $value['FS_NUMBER_TARGET'],
					'amount_reloc'      	 => number_format($value['AMOUNT_RELOC'],0,'.',','),	
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

	function download_data_budget_reloc()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('FINANCE TOOL - SYSTEM')
		->setLastModifiedBy('FINANCE TOOL - SYSTEM')
		->setTitle("Download Data")
		->setSubject("Download Data")
		->setDescription("Download Data")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Reloc Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Directorate Source");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Division Source");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Unit Source");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "RKAP Name Source");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Tribe Usecase Source");
		//$excel->setActiveSheetIndex(0)->setCellValue('H1', "FS Number Source");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Directorate Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Division Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Unit Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "RKAP Name Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Tribe Usecase Destination");
		//$excel->setActiveSheetIndex(0)->setCellValue('N1', "FS Number Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Amount Reloc");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$hasil = $this->budget_relocation->get_download_budget_reloc($date_from,$date_to);

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	{

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['RELOC_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIRECTORATE_SOURCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DIVISION_SOURCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['UNIT_SOURCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['RKAP_NAME_SOURCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['TRIBE_USECASE_SOURCE']);
			//$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['FS_NUMBER_SOURCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['DIRECTORATE_TARGET']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DIVISION_TARGET']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['UNIT_TARGET']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['RKAP_NAME_TARGET']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['TRIBE_USECASE_TARGET']);
			//$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['FS_NUMBER_TARGET']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['AMOUNT_RELOC']);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

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
		header('Content-Disposition: attachment; filename="Budget Reloc.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function budget_relocation()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("budgetrelocation/budget-relocation", $this->session->userdata['menu_url']) ){

			$data['title']          = "Budget Relocation";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/budget_relocation";
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	function load_ddl_budget_year()
	{
		$hasil	= $this->budget_relocation->get_budget_exist_year();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Year --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['TAHUN']."' data-name='".$row['TAHUN']."' >".$row['TAHUN']."</option>";
		}		
		echo $result;
		$query->free_result();

	}



	function load_ddl_rkap_name()
	{
		$hasil	= $this->budget_relocation->get_master_rkap_name_with_details();
		
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose RKAP Name --</option>";
		foreach($query->result_array() as $row)	{
			$rekapconcat = $row['RKAP_DESCRIPTION'].' &ndash;'.date('M-y',strtotime($row['MONTH']));
			$result .= "<option value='".$row['ID_RKAP_LINE']."'  data-name='".$row['RKAP_DESCRIPTION']."' , data-proc='".$row['PROC_TYPE']."' ,  data-ga='".$row['PARENT_ACCOUNT']."'>".$rekapconcat."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_program_id()
	{
		$hasil	= $this->budget_relocation->get_master_program_id();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Program ID --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ENTRY_OPTIMIZE']."' data-name='".$row['ENTRY_OPTIMIZE']."'>".$row['ENTRY_OPTIMIZE']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_fs_name()
	{
		$hasil	= $this->budget_relocation->get_master_fs_name();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose FS Name --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_FS']."' data-name='".$row['FS_NUMBER']."'>".$row['FS_NUMBER']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_rkap_name_without_detail()
	{
		$hasil	= $this->budget_relocation->get_master_rkap_name_without_details();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose RKAP Name --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['RKAP_DESCRIPTION']."'  data-name='".$row['RKAP_DESCRIPTION']."'>".$row['RKAP_DESCRIPTION']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_tribe()
	{
		$hasil	= $this->budget_relocation->get_master_tribe_usecase();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Tribe / Usecase --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['TRIBE_USECASE']."'  data-name='".$row['TRIBE_USECASE']."' >".$row['TRIBE_USECASE']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function get_fund_available()
	{
		$data = $this->budget_relocation->get_fund_availables()->row_array();

		echo json_encode($data);

	}

	function save_budget_reloc()
	{
		$this->form_validation->set_rules('ddlDirectorate', 'Directorate Source', 'trim|required');
		$this->form_validation->set_rules('ddlDirectorate2', 'Directorate Destination', 'trim|required');
		$this->form_validation->set_rules('ddlDivision', 'Division Source', 'trim|required');
		$this->form_validation->set_rules('ddlDivision2', 'Division Destination', 'trim|required');
		$this->form_validation->set_rules('ddlUnit', 'Unit Source', 'trim|required');
		$this->form_validation->set_rules('ddlUnit2', 'Unit Destination', 'trim|required');
		$this->form_validation->set_rules('ddlRKAPName', 'RKAP Name Source', 'trim|required');
		$this->form_validation->set_rules('ddlRKAPName2', 'RKAP Name Destination', 'trim|required');
		$this->form_validation->set_rules('ddlTribeUsecase', 'Tribe Usecase Source', 'trim|required');
		$this->form_validation->set_rules('ddlTribeUsecase2', 'Tribe Usecase Dest', 'trim|required');
		// $this->form_validation->set_rules('ddlFSName', 'FS Name', 'trim|required');
		// $this->form_validation->set_rules('ddlFSName2', 'FS Name', 'trim|required');
		$this->form_validation->set_rules('txtAmountToReloc', 'Amount To Reloc', 'trim|required');

		$rkap_id_resource     = $this->input->post('ddlRKAPName');
		$rkap_id_destination  = $this->input->post('ddlRKAPName2');
		// $fs_id_resource       = $this->input->post('ddlFSName');
		// $fs_id_destination    = $this->input->post('ddlFSName2');
		$amout_to_reloc    	  = preg_replace("/[^a-zA-Z0-9]/", "", $this->input->post('txtAmountToReloc'));

		$data = array(
			'ID_RKAP_SOURCE' 	=> $rkap_id_resource,
			'ID_RKAP_DEST' 		=> $rkap_id_destination,
			// 'ID_FS_SOURCE' 		=> $fs_id_resource,
			// 'ID_FS_DEST' 		=> $fs_id_destination,
			'AMOUNT_RELOC' 	    => $amout_to_reloc
		);

		if ($this->form_validation->run() == TRUE) 
		{
			$save = $this->budget_relocation->save_data_budget_reloc($data);

			if($save)
			{			
				echo '1';
			} else {			
				echo '0';
			}
		}
		else
		{
			echo validation_errors();
		}


	}



}



/* End of file Budgetrelocation.php */

/* Location: ./application/controllers/Budgetrelocation.php */