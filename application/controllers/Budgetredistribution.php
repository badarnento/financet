<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Budgetredistribution extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Budgetredistribution_mdl', 'budget_redistribution');

	}

	public function budget_redistribution()
	{

		if($this->ion_auth->is_admin() == true || in_array("budgetredistribution/budget-redistribution", $this->session->userdata['menu_url']) ){

			$data['title']          = "Budget Redistribution";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/budget_redistribution";
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_budget_redistribution(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('redis_date_from') != "" && $this->input->post('redis_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('redis_date_from'));
			$exp_date_to   = explode("/", $this->input->post('redis_date_to'));

			$redis_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$redis_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all = $this->budget_redistribution->get_budget_redistribution_datatable($redis_date_from, $redis_date_to);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){
			
			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'date_redis'		 	 => date("d-m-Y",strtotime($value['REDIS_DATE'])),
					'directorate'     		 => $value['DIRECTORATE'],
					'division'        		 => $value['DIVISION'],
					'unit'            		 => $value['UNIT'],
					'rkap_name_source'   	 => $value['RKAP_NAME_SOURCE'],
					'rkap_name_target'   	 => $value['RKAP_NAME_DEST'],
					'tribe_usecase_source'   => $value['TRIBE_USECASE_SOURCE'],
					// 'fs_name_source'   		 => $value['FS_NUMBER_SOURCE'],
					// 'fs_name_target'  		 => $value['FS_NUMBER_DEST'],
					'amount_redis'      	 => number_format($value['FUND_AV'],0,'.',','),
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

	function download_data_budget_redis()
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Redis Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Directorate");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Division");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Unit");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "RKAP Name Source");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "RKAP Name Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Tribe Usecase Source");
		// $excel->setActiveSheetIndex(0)->setCellValue('I1', "FS Number Source");
		// $excel->setActiveSheetIndex(0)->setCellValue('J1', "FS Number Destination");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Amount Redis");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$hasil = $this->budget_redistribution->get_download_budget_redis($date_from,$date_to);

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	{

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['REDIS_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIRECTORATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DIVISION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['UNIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['RKAP_NAME_SOURCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['RKAP_NAME_DEST']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['TRIBE_USECASE_SOURCE']);
			// $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['FS_NUMBER_SOURCE']);
			// $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['FS_NUMBER_DEST']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['FUND_AV']);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

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
		header('Content-Disposition: attachment; filename="Budget Redis.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_budget_redis()
	{
		$this->form_validation->set_rules('ddlDirectorate', 'Directorate Source', 'trim|required');
		$this->form_validation->set_rules('ddlDivision', 'Division Source', 'trim|required');
		$this->form_validation->set_rules('ddlUnit', 'Unit Source', 'trim|required');
		$this->form_validation->set_rules('ddlRKAPName', 'RKAP Name Source', 'trim|required');
		$this->form_validation->set_rules('ddlRKAPName2', 'RKAP Name Destination', 'trim|required');
		$this->form_validation->set_rules('ddlTribeUsecase', 'Tribe Usecase Source', 'trim|required');
		// $this->form_validation->set_rules('ddlFSName', 'FS Name', 'trim|required');
		// $this->form_validation->set_rules('ddlFSName2', 'FS Name', 'trim|required');
		$id_rkap_line 		= $this->input->post('ddlRKAPName');
		$id_rkap_line_dest 		= $this->input->post('ddlRKAPName2');
		// $fs_id_resource     = $this->input->post('ddlFSName');
		// $fs_id_destination  = $this->input->post('ddlFSName2');
		$amout_to_redis    	= preg_replace("/[^a-zA-Z0-9]/", "", $this->input->post('txtFundAvailable'));
		
		$data = array(
			"ID_RKAP" 		=> $id_rkap_line,
			"ID_RKAP_DEST" 	=> $id_rkap_line_dest,
			// "ID_FS_SOURCE" 	=> $fs_id_resource,
			// "ID_FS_DEST" 	=> $fs_id_destination,
			"FUND_AV" 		=> $amout_to_redis
		);



		if ($this->form_validation->run() == TRUE) 
		{
			$save = $this->budget_redistribution->save_data_budget_redis($data);

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



/* End of file Budgetredistribution.php */

/* Location: ./application/controllers/Budgetredistribution.php */