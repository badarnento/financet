<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	private $module_name = "master";

	protected $status_header;

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->status_header = 401;

		$this->load->model('Master_mdl', 'master');
	}


//region MASTER DIRECTORAT

	public function directorat()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/directorat", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Directorate";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/directorat";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_directorat(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_directorat_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number,
					'id_dir_code' 		=> $value['ID_DIR_CODE'], 
					'directorat_code' 	=> $value['DIRECTORAT_CODE'], 
					'directorat_name' 	=> $value['DIRECTORAT_NAME']
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

	function load_ddl_directorat()
	{
		$hasil	= $this->master->get_master_directorat();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Directorat --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_DIR_CODE']."' data-name='".$row['DIRECTORAT_NAME']."' >".$row['DIRECTORAT_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_directorat()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Directorat")
		->setSubject("Cetakan Master Data Directorat")
		->setDescription("Cetak Master Data Directorat")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "DIRECTORAT_CODE");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DIRECTORAT_NAME");

		$query	= $this->master->get_master_directorat();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIRECTORAT_NAME']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Directorate");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Directorate.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_directorat()
	{
		$this->form_validation->set_rules('txtDirectoratCode', 'Directorat Code', 'trim|required');
		$this->form_validation->set_rules('txtDirectoratName', 'Directorat Name', 'trim|required');

		$id_dir_code      	= $this->input->post('id_dir_code');
		$directorat_code	= $this->input->post('txtDirectoratCode');
		$directorat_name    = $this->input->post('txtDirectoratName');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'DIRECTORAT_CODE' 	=> $directorat_code,
			'DIRECTORAT_NAME' 	=> $directorat_name
		);

		$message = "The '".$directorat_code."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check_dir = $this->master->check_exist_directorat($directorat_code);
			$total      = $check_dir['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_directorat($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_directorat($data, $id_dir_code);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_directorat($id_dir_code){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_directorat($id_dir_code);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER DIRECTORAT


//region MASTER DIVISION
	public function division()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/division", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Division";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/division";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_division(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_division_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number,
					'id_division' 		=> $value['ID_DIVISION'], 
					'id_dir_code' 		=> $value['ID_DIR_CODE'], 
					'directorat_name' 	=> $value['DIRECTORAT_NAME'], 
					'division_code' 	=> $value['DIVISION_CODE'], 
					'division_name' 	=> $value['DIVISION_NAME']
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

	function load_ddl_division()
	{
		$hasil	= $this->master->get_master_division();
		$query  = $hasil['querymd'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Division --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_DIVISION']."' data-name='".$row['DIVISION_NAME']."' >".$row['DIVISION_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_division()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Division")
		->setSubject("Cetakan Master Data Division")
		->setDescription("Cetak Master Data Division")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "DIRECTORATE_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DIVISION_CODE");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "DIVISION_NAME");

		$query	= $this->master->get_master_division();
		$hasil  = $query['querymd'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DIVISION_NAME']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Division");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Division.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_division()
	{
		$this->form_validation->set_rules('ddlDirectoratName', 'Directorat Name', 'trim|required');
		$this->form_validation->set_rules('txtDivisionCode', 'Division Code', 'trim|required');
		$this->form_validation->set_rules('txtDivisionName', 'Division Name', 'trim|required');

		$id_division      	= $this->input->post('id_division');
		$id_dir_code      	= $this->input->post('ddlDirectoratName');
		$division_code		= $this->input->post('txtDivisionCode');
		$division_name      = $this->input->post('txtDivisionName');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'ID_DIR_CODE' 		=> $id_dir_code,
			'DIVISION_CODE' 	=> $division_code,
			'DIVISION_NAME' 	=> $division_name
		);

		$message = "The '".$division_code."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_division($division_code);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_division($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_division($data, $id_division);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_division($id_division){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_division($id_division);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER DIVISION

//region MASTER UNIT

	public function unit()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/unit", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Unit";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/unit";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_unit(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_unit_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number, 
					'id_unit' 			=> $value['ID_UNIT'], 
					'id_dir_code' 		=> $value['ID_DIR_CODE'], 
					'directorat_name' 	=> $value['DIRECTORAT_NAME'],
					'id_division' 		=> $value['ID_DIVISION'],
					'division_name' 	=> $value['DIVISION_NAME'],
					'unit_code' 		=> $value['UNIT_CODE'], 
					'unit_name' 		=> $value['UNIT_NAME']
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

	function load_ddl_unit()
	{
		$hasil	= $this->master->get_master_unit();
		$query  = $hasil['querymu'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Unit --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_UNIT']."' data-name='".$row['UNIT_NAME']."' >".$row['UNIT_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_unit()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Unit")
		->setSubject("Cetakan Master Data Unit")
		->setDescription("Cetak Master Data Unit")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "DIRECTORATE_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DIVISION_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "UNIT_CODE");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "UNIT_NAME");

		$query	= $this->master->get_master_Unit();
		$hasil  = $query['querymu'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['UNIT_NAME']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Unit");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Unit.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_unit()
	{
		$this->form_validation->set_rules('ddlDirectorat', 'Directorat Name', 'trim|required');
		$this->form_validation->set_rules('ddlDivision', 'Division Name', 'trim|required');
		$this->form_validation->set_rules('txtUnitCode', 'Unit Code', 'trim|required');
		$this->form_validation->set_rules('txtUnitName', 'Unit Name', 'trim|required');

		$id_unit      		= $this->input->post('id_unit');
		$id_division      	= $this->input->post('ddlDivision');
		$id_dir_code      	= $this->input->post('ddlDirectorat');
		$unit_code			= $this->input->post('txtUnitCode');
		$unit_name      	= $this->input->post('txtUnitName');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'ID_DIR_CODE' 		=> $id_dir_code,
			'ID_DIVISION' 		=> $id_division,
			'UNIT_CODE' 		=> $unit_code,
			'UNIT_NAME' 		=> $unit_name
		);

		$message = "The '".$unit_name."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_unit($unit_name);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_unit($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_unit($data, $id_unit);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_unit($id_unit){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_unit($id_unit);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER UNIT

//region MASTER TRIBE

	public function tribe()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/tribe", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Tribe";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/tribe";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_tribe(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_tribe_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number, 
					'id_tribe' 			=> $value['ID_TRIBE'], 
					'tribe_code' 		=> $value['TRIBE_CODE'], 
					'tribe_desc' 		=> $value['TRIBE_DESC']
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

	function load_ddl_tribe()
	{
		$hasil	= $this->master->get_master_tribe();
		$query  = $hasil['querytb'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose tribe --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_TRIBE']."' data-name='".$row['TRIBE_CODE']."' >".$row['TRIBE_CODE']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_tribe()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Tribe")
		->setSubject("Cetakan Master Data Tribe")
		->setDescription("Cetak Master Data Tribe")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "TRIBE_CODE");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "TRIBE_DESC");

		$query	= $this->master->get_master_Tribe();
		$hasil  = $query['querytb'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['TRIBE_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['TRIBE_DESC']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Tribe");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Tribe.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_tribe()
	{
		$this->form_validation->set_rules('txtTribeCode', 'Tribe Code', 'trim|required');
		$this->form_validation->set_rules('txtTribeDesc', 'Tribe Desc', 'trim|required');

		$id_tribe      		= $this->input->post('id_tribe');
		$tribe_code			= $this->input->post('txtTribeCode');
		$tribe_desc      	= $this->input->post('txtTribeDesc');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'TRIBE_CODE' 		=> $tribe_code,
			'TRIBE_DESC' 		=> $tribe_desc
		);

		$message = "The '".$tribe_code."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_tribe($tribe_code);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_tribe($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_tribe($data, $id_tribe);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_tribe($id_tribe){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_tribe($id_tribe);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER TRIBE

//region MASTER GROUP

	public function group()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/group", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Group ";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/group";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;


			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_group(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_group_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              		=> $number, 
					'id_group' 				=> $value['ID_GROUP'], 
					'group_report' 			=> $value['GROUP_REPORT']
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


	function load_ddl_group()
	{
		$hasil	= $this->master->get_master_group();
		$query  = $hasil['querygr'];			
		$result ="";
		$result .= "<option value='' data-name='' > --Choose Group-- </option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_GROUP']."' data-name='".$row['GROUP_REPORT']."' >".$row['GROUP_REPORT']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_group()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Group")
		->setSubject("Cetakan Master Data Group")
		->setDescription("Cetak Master Data Group")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "GROUP_REPORT");

		$query	= $this->master->get_master_parent_account();
		$hasil  = $query['querygr'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['GROUP_REPORT']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Group");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Group.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
	function save_group()
	{
		$this->form_validation->set_rules('txtGroup', 'Group', 'trim|required');

		$id_group      		    = $this->input->post('id_group');
		$group_report      		= $this->input->post('txtGroup');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'group_report' 		=> $group_report
		);

		$message = "The '".$group_report."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_group($group_report);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_group($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_group($data, $id_group);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_group($id_group){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_group($id_group);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER GROUP

//region MASTER GROUP RPT

	public function grouprpt()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/grouprpt", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Group Rpt";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/group_rpt";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_group_rpt(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_group_rpt_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              		=> $number, 
					'id_group_rpt' 			=> $value['ID_GROUP_RPT'], 
					'parent_account' 		=> $value['PARENT_ACCOUNT'],
					'id_group' 				=> $value['ID_GROUP'],
					'group_report' 			=> $value['GROUP_REPORT']
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

	function load_ddl_parent_account()
	{
		$hasil	= $this->master->get_master_parent_account();
		$query  = $hasil['querygr'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Parent Account --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_GROUP_RPT']."' data-name='".$row['PARENT_ACCOUNT']."' >".$row['PARENT_ACCOUNT']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_group_rpt()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Group RPT")
		->setSubject("Cetakan Master Data Group RPT")
		->setDescription("Cetak Master Data Group RPT")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PARENT_ACCOUNT");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "GROUP_REPORT");

		$query	= $this->master->get_master_parent_account();
		$hasil  = $query['querygr'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['PARENT_ACCOUNT']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['GROUP_REPORT']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Group RPT");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Group RPT.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
	function save_group_rpt()
	{
		$this->form_validation->set_rules('txtParentAccount', 'Parent Account', 'trim|required');
		$this->form_validation->set_rules('ddlGroupReport', 'Group Rpt', 'trim|required');

		$id_group_rpt      		= $this->input->post('id_group_rpt');
		$parent_account			= $this->input->post('txtParentAccount');
		$group_report      		= $this->input->post('ddlGroupReport');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'parent_account' 	=> $parent_account,
			'id_group' 			=> $group_report
		);

		$message = "The '".$parent_account."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_group_rpt($parent_account);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_group_rpt($data);

					if($save)
					{	

						$dataupdate = array
						(
						'ID_GROUP_RPT' 			=> $save
						);

						$updatecoa = $this->master->update_parent_accout($group_report,$dataupdate);

						if($updatecoa)
						{
							echo '1';
						}
						else
						{
							echo '0';
						}	
					} 
					else 
					{			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_group_rpt($data, $id_group_rpt);

				if($save)
				{		
					$nol = 0;

					$dataupdate = array
						(
						'ID_GROUP_RPT' 			=> $nol
						);

						$updatecoa = $this->master->update_parent_accout_by_parent($id_group_rpt,$dataupdate);

						if($updatecoa)
						{

						$dataupdate2 = array
						(
						'ID_GROUP_RPT' 			=> $id_group_rpt
						);

							$updatecoa2 = $this->master->update_parent_accout($group_report,$dataupdate2);

							if($updatecoa2)
							{
								echo '1';
							}
							else
							{
								echo '0';
							}
						}
						else
						{
							echo '0';
						}

				} 
				else 
				{			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_group_rpt($id_group_rpt){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$nol = 0;

		$dataupdate = array
		(
		'ID_GROUP_RPT' 			=> $nol
		);

		$updatecoa = $this->master->update_parent_accout_by_parent($id_group_rpt,$dataupdate);

	if($updatecoa)
	{


		$delete = $this->master->delete_data_group_rpt($id_group_rpt);

		if($delete > 0)
		{
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

	}

		echo json_encode($result);
	}

	//endregion MASTER GROUP RPT

//region MASTER COA

	public function coa()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/coa", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Coa";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/coa";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_coa(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_coa_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              		=> $number, 
					'id_master_coa' 		=> $value['ID_MASTER_COA'], 
					'id_group_rpt' 			=> $value['ID_GROUP_RPT'], 
					'id_group'				=> $value['ID_GROUP'],
					'group_report' 			=> $value['GROUP_REPORT'],
					'nature' 				=> $value['NATURE'], 
					'description' 			=> $value['DESCRIPTION'],
					'parent_account' 		=> $value['PARENT_ACCOUNT']
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

	function cetak_data_coa()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Coa")
		->setSubject("Cetakan Master Data Coa")
		->setDescription("Cetak Master Data Coa")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "NATURE");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DESCRIPTION");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "GROUP_REPORT");

		$query	= $this->master->get_master_Coa();
		$hasil  = $query['queryco'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['GROUP_REPORT']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Coa");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Coa.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_coa()
	{
		$this->form_validation->set_rules('ddlGroupReport', 'Group Report', 'trim|required');
		$this->form_validation->set_rules('txtNature', 'Nature', 'trim|required');
		$this->form_validation->set_rules('txtDescription', 'Description', 'trim|required');

		$id_master_coa      	= $this->input->post('id_master_coa');
		$group_report     		= $this->input->post('ddlGroupReport');
		$nature					= $this->input->post('txtNature');
		$description      		= $this->input->post('txtDescription');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'ID_GROUP'			=> $group_report,
			'NATURE' 			=> $nature,
			'DESCRIPTION' 		=> $description
		);

		$message = "The '".$nature."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_coa($nature);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_coa($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_coa($data, $id_master_coa);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_coa($id_master_coa){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_coa($id_master_coa);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER COA

//region MASTER FPJP

	public function fpjp()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/fpjp", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master FPJP ";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_fpjp";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_fpjp(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_fpjp_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              		=> $number, 
					'id_master_fpjp' 		=> $value['ID_MASTER_FPJP'],
					'fpjp_code' 			=> $value['FPJP_CODE'],
					'fpjp_name' 			=> $value['FPJP_NAME']
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


	function load_ddl_fpjp()
	{
		$hasil	= $this->master->get_master_fpjp();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' > --Choose FPJP-- </option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_MASTER_FPJP']."' data-name='".$row['FPJP_NAME']."' >".$row['FPJP_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_fpjp()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data FPJP")
		->setSubject("Cetakan Master Data FPJP")
		->setDescription("Cetak Master Data FPJP")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "FPJP_CODE");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "FPJP_NAME");

		$query	= $this->master->get_master_parent_account();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['FPJP_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['FPJP_NAME']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data FPJP");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data FPJP.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
	function save_fpjp()
	{
		$this->form_validation->set_rules('txtFPJPCode', 'FPJP Code', 'trim|required');
		$this->form_validation->set_rules('txtFPJPName', 'FPJP Name', 'trim|required');

		$id_master_fpjp      	= $this->input->post('id_master_fpjp');
		$fpjp_code      		= $this->input->post('txtFPJPCode');
		$fpjp_name      		= $this->input->post('txtFPJPName');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'FPJP_CODE' 		=> $fpjp_code,
			'FPJP_NAME' 		=> $fpjp_name
		);

		$message = "The '".$fpjp_code."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_fpjp($fpjp_code);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_fpjp($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_fpjp($data, $id_master_fpjp);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_fpjp($id_master_fpjp){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_fpjp($id_master_fpjp);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER FPJP

//region MASTER EMPLOYEE

	public function employee()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/pph", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Employee";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/employee";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_employee(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_employee_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number, 
					'id_employee' 		=> $value['ID_EMPLOYEE'], 
					'id_dir_code' 		=> $value['ID_DIR_CODE'], 
					'directorat_name' 	=> $value['DIRECTORAT_NAME'],
					'id_division' 		=> $value['ID_DIVISION'],
					'division_name' 	=> $value['DIVISION_NAME'],
					'id_unit' 			=> $value['ID_UNIT'],
					'unit_name' 		=> $value['UNIT_NAME'],
					'id_jabatan' 		=> $value['ID_JABATAN'],
					'jabatan' 			=> $value['JABATAN'],
					'nama' 				=> $value['NAMA'], 
					'nik' 				=> $value['NIK'],
					'no_hp' 			=> $value['NO_HP'],
					'alamat_email' 		=> $value['ALAMAT_EMAIL'],
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

	function load_ddl_employee()
	{
		$hasil	= $this->master->get_master_employee();
		$query  = $hasil['querymu'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose employee --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_EMPLOYEE']."' data-name='".$row['NAMA']."' >".$row['NAMA']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_employee()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Employee")
		->setSubject("Cetakan Master Data Employee")
		->setDescription("Cetak Master Data Employee")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "NIK");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "NAMA");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "JABATAN");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "UNIT_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "DIVISION_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "DIRECTORAT_NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "NO_HP");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "ALAMAT_EMAIL");

		$query	= $this->master->get_master_employee();
		$hasil  = $query['querymu'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NIK']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NAMA']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['JABATAN']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['UNIT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DIVISION_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['DIRECTORAT_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_HP']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['ALAMAT_EMAIL']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data employee");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data employee.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_employee()
	{
		$this->form_validation->set_rules('ddlDirectorat', 'Directorat Name', 'trim|required');
		$this->form_validation->set_rules('ddlDivision', 'Division Name', 'trim|required');
		$this->form_validation->set_rules('ddlUnit', 'Unit Name', 'trim|required');
		$this->form_validation->set_rules('ddlJabatan', 'Jabatan', 'trim|required');
		$this->form_validation->set_rules('txtNIK', 'NIK', 'trim|required');
		$this->form_validation->set_rules('txtName', 'Name', 'trim|required');
		$this->form_validation->set_rules('txtNoHP', 'No HP', 'trim|required');
		$this->form_validation->set_rules('txtAlamatEmail', 'Alamat Email', 'trim|required');

		$id_employee      		= $this->input->post('id_employee');
		$id_division      		= $this->input->post('ddlDivision');
		$id_dir_code      		= $this->input->post('ddlDirectorat');
		$id_unit      			= $this->input->post('ddlUnit');
		$id_jabatan      		= $this->input->post('ddlJabatan');
		$nik					= $this->input->post('txtNIK');
		$name      				= $this->input->post('txtName');
		$no_hp					= $this->input->post('txtNoHP');
		$alamat_email      		= $this->input->post('txtAlamatEmail');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'ID_DIR_CODE' 			=> $id_dir_code,
			'ID_DIVISION' 			=> $id_division,
			'ID_UNIT' 				=> $id_unit,
			'ID_JABATAN' 			=> $id_jabatan,
			'NIK' 					=> $nik,
			'NAMA' 					=> $name,
			'NO_HP' 				=> $no_hp,
			'ALAMAT_EMAIL' 			=> $alamat_email,
		);

		$message = "The '".$nik."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_employee($nik);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_employee($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_employee($data, $id_employee);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_employee($id_employee){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_employee($id_employee);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER EMPLOYEE

//region MASTER JABATAN

	public function jabatan()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/pph", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Jabatan";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/jabatan";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_jabatan(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_jabatan_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number, 
					'id_jabatan' 		=> $value['ID_JABATAN'], 
					'id_dir_code' 		=> $value['ID_DIR_CODE'], 
					'directorat_name' 	=> $value['DIRECTORAT_NAME'],
					'id_division' 		=> $value['ID_DIVISION'],
					'division_name' 	=> $value['DIVISION_NAME'],
					'id_unit' 			=> $value['ID_UNIT'],
					'unit_name' 		=> $value['UNIT_NAME'],
					'code_jabatan' 		=> $value['CODE_JABATAN'], 
					'jabatan' 			=> $value['JABATAN']
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

	function load_ddl_jabatan()
	{
		$hasil	= $this->master->get_master_jabatan();
		$query  = $hasil['querymu'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose jabatan --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_JABATAN']."' data-name='".$row['JABATAN']."' >".$row['JABATAN']."</option>"; }
			echo $result;
			$query->free_result();

		}

		function cetak_data_jabatan()
		{

			if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
				@set_time_limit(300);
			}

			ini_set('memory_limit', '-1');

			include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
			$excel = new PHPExcel();

		// Settingan awal fil excel
			$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
			->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
			->setTitle("Cetak Master Data Jabatan")
			->setSubject("Cetakan Master Data Jabatan")
			->setDescription("Cetak Master Data Jabatan")
			->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
			$excel->setActiveSheetIndex(0)->setCellValue('B1', "DIRECTORATE_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('C1', "DIVISION_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('D1', "UNIT_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('E1', "CODE_JABATAN");
			$excel->setActiveSheetIndex(0)->setCellValue('F1', "JABATAN");

			$query	= $this->master->get_master_jabatan();
			$hasil  = $query['querymu'];

			$numrow    = 2;
			$number    = 0 +1;

			foreach($hasil->result_array() as $row)	
			{
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['CODE_JABATAN']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['JABATAN']);

				$numrow++;

			}

		// Set width kolom
			$loop_column = horizontal_loop_excel("A", 22);
			foreach ($loop_column as $key => $value) 
			{
				$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
			}

			$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Master Data Jabatan");
			$excel->setActiveSheetIndex(0);

		// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Jabatan.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_jabatan()
	{
		$this->form_validation->set_rules('ddlDirectorat', 'Directorat Name', 'trim|required');
		$this->form_validation->set_rules('ddlDivision', 'Division Name', 'trim|required');
		$this->form_validation->set_rules('ddlUnit', 'Unit Name', 'trim|required');
		$this->form_validation->set_rules('txtCodeJabatan', 'Code Jabatan', 'trim|required');
		$this->form_validation->set_rules('txtJabatan', 'Name Jabatan', 'trim|required');

		$id_jabatan      		= $this->input->post('id_jabatan');
		$id_dir_code      	    = $this->input->post('ddlDirectorat');
		$id_division      		= $this->input->post('ddlDivision');
		$id_unit      			= $this->input->post('ddlUnit');
		$code_jabatan			= $this->input->post('txtCodeJabatan');
		$jabatan     			= $this->input->post('txtJabatan');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'ID_DIR_CODE' 		=> $id_dir_code,
			'ID_DIVISION' 		=> $id_division,
			'ID_UNIT' 			=> $id_unit,
			'CODE_JABATAN' 		=> $code_jabatan,
			'JABATAN' 			=> $jabatan
		);

		$message = "The '".$code_jabatan."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check = $this->master->check_exist_jabatan($code_jabatan);
			$total      = $check['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_jabatan($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_jabatan($data, $id_jabatan);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}


	}


	public function delete_jabatan($id_jabatan){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_jabatan($id_jabatan);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER JABATAN

//region PPH

	public function pph()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/pph", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master PPH";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_pph";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_pph(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_pph_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              		=> $number,
					'id_wht_tax' 			=> $value['ID_WHT_TAX'],
					'wht_tax_type' 			=> $value['WHT_TAX_TYPE'], 
					'wht_desc' 				=> $value['WHT_DESC'], 
					'wht_tax_code' 			=> $value['WHT_TAX_CODE'],
					'wht_tax_code_desc' 	=> $value['WHT_TAX_CODE_DESC'], 
					'percentage' 			=> $value['PERCENTAGE'], 
					'gl_account' 			=> $value['GL_ACCOUNT']
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

	function load_ddl_pph()
	{
		$hasil	= $this->master->get_master_pph();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose pph --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['VALUE_PPH']."' data-name='".$row['PPH_DESC']."' >".$row['PPH_DESC']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_pph()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data pph")
		->setSubject("Cetakan Master Data pph")
		->setDescription("Cetak Master Data pph")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Wht tax Type");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Wht Description");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Wht Tax Code");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Wht Tax Code Description");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Percentage");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Gl Account");

		$query	= $this->master->get_master_pph();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['WHT_TAX_TYPE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['WHT_DESC']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['WHT_TAX_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['WHT_TAX_CODE_DESC']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['PERCENTAGE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['GL_ACCOUNT']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data pph");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data pph.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_pph()
	{
		$this->form_validation->set_rules('txtWhtTaxType', 'WHT Tax Type', 'trim|required');
		$this->form_validation->set_rules('txtWhtDesc', 'WHT Desc', 'trim|required');
		$this->form_validation->set_rules('txtWhtTaxCode', 'WHT Tax Code', 'trim|required');
		$this->form_validation->set_rules('txtWhtTaxCodeDesc', 'WHT Tax Code Desc', 'trim|required');
		$this->form_validation->set_rules('txtPercentage', 'Percentage', 'trim|required');
		$this->form_validation->set_rules('txtGlAccount', 'Gl Account', 'trim|required');

		$id_wht_tax      	= $this->input->post('id_wht_tax');
		$wht_tax_type		= $this->input->post('txtWhtTaxType');
		$wht_desc    		= $this->input->post('txtWhtDesc');
		$wht_tax_code		= $this->input->post('txtWhtTaxCode');
		$wht_tax_code_desc  = $this->input->post('txtWhtTaxCodeDesc');
		$percentage			= $this->input->post('txtPercentage');
		$gl_account   		= $this->input->post('txtGlAccount');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'WHT_TAX_TYPE' 			=> $wht_tax_type,
			'WHT_DESC' 				=> $wht_desc,
			'WHT_TAX_CODE' 			=> $wht_tax_code,
			'WHT_TAX_CODE_DESC' 	=> $wht_tax_code_desc,
			'PERCENTAGE' 			=> $percentage,
			'GL_ACCOUNT' 			=> $gl_account
		);

		$message = "The ".$wht_tax_type."-".$wht_tax_code."already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check_pph 	= $this->master->check_exist_pph($wht_tax_type,$wht_tax_code);
			$total      = $check_pph['total_data'];

			// echo json_encode($data);die();

			if($isNewRecord == "1")
			{
				// echo "data baru"; die();
				
				if ($total != "1") 
				{
					// echo "not exist"; die();

					$save = $this->master->save_data_pph($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				// echo 'keluar';die();
				$save = $this->master->update_data_pph($data, $id_wht_tax);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_pph($id_wht_tax){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_pph($id_wht_tax);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	


	//endregion PPH

//region PPN

	public function ppn()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/ppn", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master PPN";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_ppn";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_ppn(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_ppn_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              		=> $number,
					'id_mstr_ppn' 			=> $value['ID_MSTR_PPN'],
					'tax_code' 				=> $value['TAX_CODE'],
					'tax_description' 		=> $value['TAX_DESCRIPTION'], 
					'percentage' 			=> $value['PERCENTAGE'], 
					'gl_account' 			=> $value['GL_ACCOUNT']
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

	function load_ddl_ppn()
	{
		$hasil	= $this->master->get_master_ppn();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose ppn --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['VALUE_PPN']."' data-name='".$row['PPN_DESC']."' >".$row['PPN_DESC']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_ppn()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data ppn")
		->setSubject("Cetakan Master Data ppn")
		->setDescription("Cetak Master Data ppn")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Tax Code");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Tax Description");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Percentage");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Gl Account");

		$query	= $this->master->get_master_ppn();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['TAX_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['TAX_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['PERCENTAGE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['GL_ACCOUNT']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data PPN");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data PPN.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_ppn()
	{
		$this->form_validation->set_rules('txtTaxCode', 'Tax Code', 'trim|required');
		$this->form_validation->set_rules('txtTaxDesc', 'Tax Desc', 'trim|required');
		$this->form_validation->set_rules('txtPercentage', 'Percentage', 'trim|required');
		$this->form_validation->set_rules('txtGlAccount', 'Gl Account', 'trim|required');

		$id_mstr_ppn      	= $this->input->post('id_mstr_ppn');
		$tax_code			= $this->input->post('txtTaxCode');
		$tax_description    = $this->input->post('txtTaxDesc');
		$percentage			= $this->input->post('txtPercentage');
		$gl_account   		= $this->input->post('txtGlAccount');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'TAX_CODE' 				=> $tax_code,
			'TAX_DESCRIPTION' 		=> $tax_description,
			'PERCENTAGE' 			=> $percentage,
			'GL_ACCOUNT' 			=> $gl_account
		);

		$message = "The ".$tax_code." already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check_ppn 	= $this->master->check_exist_ppn($tax_code);
			$total      = $check_ppn['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_ppn($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_ppn($data, $id_mstr_ppn);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}

	public function delete_ppn($id_mstr_ppn){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_ppn($id_mstr_ppn);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion PPN

//region MASTER PROGRAM 
	public function program()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/program", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Program";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_program";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_program(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_program_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number,
					'id_program' 		=> $value['ID_PROGRAM'], 
					'year' 				=> $value['TAHUN'], 
					'program' 			=> $value['PROGRAM'], 
					'description' 		=> $value['DESCRIPTION']
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

	function load_ddl_program()
	{
		$hasil	= $this->master->get_master_program();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose program --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_PROGRAM']."' data-name='".$row['DESCRIPTION']."' >".$row['DESCRIPTION']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_program()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data program")
		->setSubject("Cetakan Master Data program")
		->setDescription("Cetak Master Data program")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "tahun");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Program");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Description");

		$query	= $this->master->get_master_program();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['TAHUN']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['PROGRAM']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DESCRIPTION']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data program");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data program.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_program()
	{
		$this->form_validation->set_rules('txtProgram', 'Program', 'trim|required');
		$this->form_validation->set_rules('txtDescription', 'Description', 'trim|required');

		$id_program      	= $this->input->post('id_program');
		$program			= $this->input->post('txtProgram');
		$description    	= $this->input->post('txtDescription');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'PROGRAM' 		=> $program,
			'DESCRIPTION' 	=> $description
		);

		$message = "The '".$description."' already exist";

		// echo $message; die();

		if ($this->form_validation->run() == TRUE) 
		{
			// echo "masuk komandan", die();

			$check_program = $this->master->check_exist_program($description,$program);
			$total      = $check_program['total_data'];

			if($isNewRecord == "1")
			{
				// echo json_encode($data);die();
				if ($total != "1") 
				{
					// echo "masuk komandan"; die();

					$save = $this->master->save_data_program($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_program($data, $id_program);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_program($id_program){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_program($id_program);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER PROGRAM

//region MASTER BANK 
	public function master_bank()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/master_bank", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Bank";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_bank";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_bank(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_bank_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              				=> $number,
					'id_bank' 						=> $value['ID_BANK'], 
					'bic_rtgs_code' 				=> $value['BIC_RTGS_CODE'], 
					'domestic_bank_code' 			=> $value['DOMESTIC_BANK_CODE'], 
					'bank_name' 					=> $value['BANK_NAME'],
					'branch_code' 					=> $value['BRANCH_CODE'],
					'branch_name' 					=> $value['BRANCH_NAME'],
					'city' 							=> $value['CITY']
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

	function load_ddl_bank()
	{
		$hasil	= $this->master->get_master_bank();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Bank --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_BANK']."' data-name='".$row['BANK_NAME']."' >".$row['BANK_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_bank()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Bank")
		->setSubject("Cetakan Master Data Bank")
		->setDescription("Cetak Master Data Bank")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Bic Rtgs Code");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Domestic Bank Code");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Bank Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Branch Code");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Branch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "City");

		$query	= $this->master->get_master_bank();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['BIC_RTGS_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DOMESTIC_BANK_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['BANK_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['BRANCH_CODE']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['BRANCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CITY']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Bank");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Bank.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_bank()
	{
		$this->form_validation->set_rules('txtBicRtgsCode', 'Bic Rtgs Code', 'trim|required');
		$this->form_validation->set_rules('txtDomesticBankCode', 'Domestic bank Code', 'trim|required');
		$this->form_validation->set_rules('txtBankName', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('txtBranchCode', 'Branch Code', 'trim|required');
		$this->form_validation->set_rules('txtBranchName', 'Branch Name', 'trim|required');
		$this->form_validation->set_rules('txtCity', 'City', 'trim|required');

		$id_bank      			= $this->input->post('id_bank');
		$bic_rtgs_code			= $this->input->post('txtBicRtgsCode');
		$domestic_bank_code		= $this->input->post('txtDomesticBankCode');
		$bank_name    			= $this->input->post('txtBankName');
		$branch_code      		= $this->input->post('txtBranchCode');
		$branch_name			= $this->input->post('txtBranchName');
		$city    				= $this->input->post('txtCity');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'BIC_RTGS_CODE' 		=> $bic_rtgs_code,
			'DOMESTIC_BANK_CODE' 	=> $domestic_bank_code,
			'BANK_NAME' 			=> $bank_name,
			'BRANCH_CODE' 			=> $branch_code,
			'BRANCH_NAME' 			=> $branch_name,
			'CITY' 					=> $city
		);

		$message = "The '".$domestic_bank_code."' already exist";

		// echo $message; die();

		if ($this->form_validation->run() == TRUE) 
		{
			// echo "masuk komandan", die();

			$check_bank = $this->master->check_exist_bank($domestic_bank_code);
			$total      = $check_bank['total_data'];

			if($isNewRecord == "1")
			{
				// echo json_encode($data);die();
				if ($total != "1") 
				{
					// echo "masuk komandan"; die();

					$save = $this->master->save_data_bank($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_bank($data, $id_bank);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_bank($id_bank){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_bank($id_bank);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER BANK

//region MASTER BANK LA
	public function master_bank_la()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/master_bank_la", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Bank LA";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_bank_la";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_bank_la(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_bank_la_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				if($value['FLAG'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$number.'" class="checkbox-data checklist_inquiry" type="checkbox" name="checkbox-data" value='.$value['FLAG'].' checked disabled><label class="m-0 p-0" for="checkbox-'.$number.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$number.'" class="checkbox-data checklist_inquiry" type="checkbox" name="checkbox-data"  value='.$value['FLAG'].' disabled><label class="m-0 p-0" for="checkbox-'.$number.'"></label></div>';
				}

				$row[] = array(
					'no'              				=> $number,
					'id_bank_la' 					=> $value['ID_BANK_LA'], 
					'nama_bank' 					=> $value['NAMA_BANK'], 
					'nomor_rekening' 				=> $value['NOMOR_REKENING'], 
					'peruntukan' 					=> $value['PERUNTUKAN'],
					'coa_bank' 						=> $value['COA_BANK'],
					'coa_liabilities' 				=> $value['COA_LIABILITIES'],
					'is_active_value' 				=> $value['FLAG'],
					'is_active' 					=> $checkbox
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

	function load_ddl_bank_la()
	{
		$hasil	= $this->master->get_ddl_bank_la();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Bank --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_BANK']."' data-name='".$row['NAMA_BANK']."' >".$row['NAMA_BANK']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	public function load_ddl_Active()
	{		
		$result  = "";
		$result .= "<option value='Y' data-name='YES' > YES </option>";
		$result .= "<option value='N' data-name='NO' > NO </option>";

		echo $result;
	}

	function cetak_data_bank_la()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Bank")
		->setSubject("Cetakan Master Data Bank")
		->setDescription("Cetak Master Data Bank")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Nomor Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Peruntukan");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Coa Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Coa Liabilities");

		$query	= $this->master->get_master_bank_la();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NOMOR_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['PERUNTUKAN']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['COA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['COA_LIABILITIES']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Bank LA");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Bank LA.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_bank_la()
	{
		$this->form_validation->set_rules('txtNamaBank', 'Bic Rtgs Code', 'trim|required');
		$this->form_validation->set_rules('txtNomorRekening', 'Domestic bank Code', 'trim|required');
		$this->form_validation->set_rules('txtPeruntukan', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('txtCoaBank', 'Branch Code', 'trim|required');
		$this->form_validation->set_rules('txtCoaLiabilities', 'Branch Name', 'trim|required');
		$this->form_validation->set_rules('ddlActive', 'Activated Bank', 'trim|required');

		$id_bank_la      		= $this->input->post('id_bank_la');
		$nama_bank				= $this->input->post('txtNamaBank');
		$nomor_rekening			= $this->input->post('txtNomorRekening');
		$peruntukan    			= $this->input->post('txtPeruntukan');
		$coa_bank      			= $this->input->post('txtCoaBank');
		$coa_liabilities		= $this->input->post('txtCoaLiabilities');
		$is_active				= $this->input->post('ddlActive');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data_update = array(
			'FLAG' 				=> 'N'
		);

		$data = array(
			'NAMA_BANK' 		=> $nama_bank,
			'NOMOR_REKENING' 	=> $nomor_rekening,
			'PERUNTUKAN' 		=> $peruntukan,
			'COA_BANK' 			=> $coa_bank,
			'COA_LIABILITIES' 	=> $coa_liabilities,
			'FLAG' 				=> $is_active
		);

		$message = "The '".$nomor_rekening."' already exist";

		// echo $message; die();

		if ($this->form_validation->run() == TRUE) 
		{
			// echo "masuk komandan", die();

			$check_bank = $this->master->check_exist_bank_la($nomor_rekening);
			$total      = $check_bank['total_data'];

			if($isNewRecord == "1")
			{
				// echo json_encode($data);die();
				if ($total != "1") 
				{
					// echo "masuk komandan"; die();

					$update = $this->master->update_active_data_bank_la($data_update);

					if($update)
					{

						$save = $this->master->save_data_bank_la($data);

						if($save)
						{			
							echo '1';
						} else {			
							echo '0';
						}			
						
					} 
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$update = $this->master->update_active_data_bank_la($data_update);

				if($update)
				{			
					$save = $this->master->update_data_bank_la($data, $id_bank_la);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				} 
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_bank_la($id_bank_la){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_bank_la($id_bank_la);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER BANK LA


//region MASTER SETTING
	public function master_setting()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/vendor", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Setting";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_setting";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_setting(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_setting_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				if($value['FLAG'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$number.'" class="checkbox-data checklist_inquiry" type="checkbox" name="checkbox-data" value='.$value['FLAG'].' checked disabled><label class="m-0 p-0" for="checkbox-'.$number.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$number.'" class="checkbox-data checklist_inquiry" type="checkbox" name="checkbox-data"  value='.$value['FLAG'].' disabled><label class="m-0 p-0" for="checkbox-'.$number.'"></label></div>';
				}

				$row[] = array(
					'no'              				=> $number,
					'id_setting' 					=> $value['ID_SETTING'], 
					'id_menu' 						=> $value['ID_MENU'], 
					'nama_menu' 					=> $value['TITLE'],
					'code_setting' 					=> $value['CODE_SETTING'],
					'description' 					=> $value['DESCRIPTION'],
					'flag_value' 					=> $value['FLAG'],
					'flag' 							=> $checkbox
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


	public function load_ddl_flag()
	{		
		$result  = "";
		$result .= "<option value='Y' data-name='YES' > YES </option>";
		$result .= "<option value='N' data-name='NO' > NO </option>";

		echo $result;
	}

	function load_ddl_menu()
	{
		$hasil	= $this->master->get_master_menu();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Menu --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID']."' data-name='".$row['TITLE']."' >".$row['TITLE']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	public function cetak_data_setting()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data setting")
		->setSubject("Cetakan Master Data setting")
		->setDescription("Cetak Master Data setting")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama Menu");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Code Setting");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Flag");

		$query	= $this->master->get_master_setting();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['TITLE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['CODE_SETTING']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['FLAG']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Setting");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Setting.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_setting()
	{
		$this->form_validation->set_rules('ddlNamaMenu', 'Menu Name', 'trim|required');
		$this->form_validation->set_rules('ddlFlag', 'Flag', 'trim|required');

		$id_setting      		= $this->input->post('id_setting');
		$nama_menu				= $this->input->post('ddlNamaMenu');
		$code_setting		    = $this->input->post('txtCodeSetting');
		$description		    = $this->input->post('txtDescription');
		$flag					= $this->input->post('ddlFlag');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'ID_MENU' 			=> $nama_menu,
			'CODE_SETTING' 		=> $code_setting,
			'DESCRIPTION' 		=> $description,
			'FLAG' 				=> $flag
		);

		$message = "Setting for this Menu already exist";

		// echo $message; die();

		if ($this->form_validation->run() == TRUE) 
		{
			// echo "masuk komandan", die();

			$check_setting = $this->master->check_exist_setting($code_setting);
			$total      = $check_setting['total_data'];

			if($isNewRecord == "1")
			{
				// echo json_encode($data);die();
				if ($total != "1") 
				{
					// echo "masuk komandan"; die();
					$save = $this->master->save_data_setting($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}			
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/	
				$save = $this->master->update_data_setting($data, $id_setting);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_setting($id_setting){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_setting($id_setting);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER SETTING


//region MASTER VENDOR
	public function vendor()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/vendor", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Data Vendor";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_rekanan";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_vendor(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_vendor_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              				=> $number,
					'id_rekanan' 					=> $value['ID_VENDOR'], 
					'nama_rekanan' 					=> $value['NAMA_VENDOR'], 
					'npwp' 							=> $value['NOMOR_NPWP'], 
					'alamat' 						=> $value['ALAMAT'],
					'no_tlp' 						=> $value['NO_TLP'],
					'pic_vendor' 					=> $value['NAMA_PIC_VENDOR'],
					'alamat_email' 					=> $value['ALAMAT_EMAIL'],
					'nama_rekening' 				=> $value['NAMA_REKENING'],
					'nama_bank' 					=> $value['NAMA_BANK'],
					'acct_number' 					=> $value['ACCT_NUMBER']
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

	function load_ddl_vendor()
	{
		$hasil	= $this->master->get_master_vendor();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_VENDOR']."' data-name='".$row['NAMA_VENDOR']."' >".$row['NAMA_VENDOR']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_vendor()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Directorat")
		->setSubject("Cetakan Master Data Directorat")
		->setDescription("Cetak Master Data Directorat")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No NPWP");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Alamat");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "No Telpn");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Nama PIC Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Alamat email");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Nama rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Acct Number");

		$query	= $this->master->get_master_vendor();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NOMOR_NPWP']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NO_TLP']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NAMA_PIC_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['ALAMAT_EMAIL']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['ACCT_NUMBER']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Vendor");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Vendor.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_vendor()
	{
		$this->form_validation->set_rules('txtNamaRekanan', 'Nama Rekanan', 'trim|required');
		$this->form_validation->set_rules('txtNPWP', 'NPWP', 'trim|required');
		$this->form_validation->set_rules('txtAlamat', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('txtNoTelpWeb', 'No Telp', 'trim|required');
		$this->form_validation->set_rules('txtPICVendor', 'PIC Vendor', 'trim|required');
		$this->form_validation->set_rules('txtEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('txtNoRekening', 'No Rekening', 'trim|required');
		$this->form_validation->set_rules('txtNamaBank', 'Nama Bank', 'trim|required');
		$this->form_validation->set_rules('txtAcctNumber', 'Acct Number', 'trim|required');

		$id_rekanan 		= $this->input->post('id_rekanan');
		$nama_rekanan      	= $this->input->post('txtNamaRekanan');
		$npwp				= $this->input->post('txtNPWP');
		$alamat   	 		= $this->input->post('txtAlamat');
		$no_tlp_web   	 	= $this->input->post('txtNoTelpWeb');
		$pic_vendor   	 	= $this->input->post('txtPICVendor');
		$email   	 		= $this->input->post('txtEmail');
		$no_rekening   	 	= $this->input->post('txtNoRekening');
		$nama_bank   	 	= $this->input->post('txtNamaBank');
		$acctnumber   	 	= $this->input->post('txtAcctNumber');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'NAMA_VENDOR' 						=> $nama_rekanan,
			'NOMOR_NPWP' 						=> $npwp, 
			'ALAMAT' 							=> $alamat,
			'NO_TLP' 							=> $no_tlp_web,
			'NAMA_PIC_VENDOR' 					=> $pic_vendor,
			'ALAMAT_EMAIL' 						=> $email,
			'NAMA_BANK' 						=> $nama_bank,
			'NAMA_REKENING' 					=> $no_rekening,
			'ACCT_NUMBER' 						=> $acctnumber
		);

		$message = "The '".$nama_rekanan."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$total = $this->master->check_exist_vendor($nama_rekanan);

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total < 1) 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_vendor($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_vendor($data, $id_rekanan);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}

	public function delete_vendor($id_rekanan){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_vendor($id_rekanan);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	public function save_import_upload_vendor()
	{

		$this->load->library('excel');

		if(isset($_FILES["file"]["name"])) {
			ini_set('precision', '20');

			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			foreach($object->getWorksheetIterator() as $worksheet){

				$highestRow    = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();

				for($row=2; $row<=$highestRow; $row++){

					$nama_rekanan    		= $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$npwp  					= $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$alamat       			= $worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue();  
					$no_tlp_web       		= $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$pic_vendor  		   	= $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$email 					= $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$no_rekening   			= $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$nama_bank 				= $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$acctnumber  			= $worksheet->getCellByColumnAndRow(8, $row)->getValue();

					// echo 'sueeekk'.$npwp.' + '.$no_rekening; die;
					
					$data[] = array
					(
						'NAMA_VENDOR' 						=> $nama_rekanan,
						'NOMOR_NPWP' 						=> $npwp, 
						'ALAMAT' 							=> $alamat,
						'NO_TLP' 							=> $no_tlp_web,
						'NAMA_PIC_VENDOR' 					=> $pic_vendor,
						'ALAMAT_EMAIL' 						=> $email,
						'NAMA_BANK' 						=> $nama_bank,
						'NAMA_REKENING' 					=> $no_rekening,
						'ACCT_NUMBER' 						=> $acctnumber
					);

				}
			}

			$valuetrue = $this->master->insert_upload_vendor_import($data);

			if($valuetrue)
			{
				$result['status']   = true;
				$result['messages'] = "Data successfully imported";
			}else
			{
				$result['status']   = false;
				$result['messages'] = "Data failed imported";
			}

			echo json_encode($result);

		}

	}


	//endregion MASTER VENDOR

	public function vendor_tax()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/rekanan_tax", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Data Vendor Tax";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_vendor_tax";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_vendor_tax(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_vendor_tax_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {
				$npwp = str_replace(".","", str_replace("-","", $value['NOMOR_NPWP']));

				$row[] = array(
					'no'              				=> $number,
					'id_rekanan' 					=> $value['ID_VENDOR'], 
					'nama_rekanan' 					=> $value['NAMA_VENDOR'], 
					'domisili' 						=> $value['DOMISILI'], 
					'npwp' 							=> $npwp, 
					'alamat' 						=> $value['ALAMAT'],
					'no_tlp' 						=> $value['NO_TLP'],
					'pic_vendor' 					=> $value['NAMA_PIC_VENDOR'],
					'alamat_email' 					=> $value['ALAMAT_EMAIL'],
					'nama_rekening' 				=> $value['NAMA_REKENING'],
					'nama_bank' 					=> $value['NAMA_BANK'],
					'acct_number' 					=> $value['ACCT_NUMBER'],
					'sket_pp23' 					=> $value['S_KET_PP23'],
					'sket_dtp' 						=> $value['S_KET_DTP'],
					'skb_pph23' 					=> $value['SKB_PPH23'],
					'skb_pph_lainnya' 				=> $value['SKB_PPH_LAINNYA'],
					'tin' 							=> $value['TIN'],
					'ktp' 							=> $value['KTP'],
					'skd' 							=> $value['SKD'],
					'negara_kode' 					=> $value['KODE_NEGARA']
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

	function save_vendor_tax()
	{
		$this->form_validation->set_rules('txtNamaRekanan', 'Nama Rekanan', 'trim|required');
		$this->form_validation->set_rules('txtNPWP', 'NPWP', 'trim|required');
		$this->form_validation->set_rules('txtAlamat', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('txtNoTelpWeb', 'No Telp', 'trim|required');
		$this->form_validation->set_rules('txtPICVendor', 'PIC Vendor', 'trim|required');
		$this->form_validation->set_rules('txtEmail', 'Email', 'trim|required');
		$this->form_validation->set_rules('txtNoRekening', 'No Rekening', 'trim|required');
		$this->form_validation->set_rules('txtNamaBank', 'Nama Bank', 'trim|required');
		$this->form_validation->set_rules('txtAcctNumber', 'Acct Number', 'trim|required');

		$id_rekanan 		= $this->input->post('id_rekanan');
		$nama_rekanan      	= $this->input->post('txtNamaRekanan');
		$domisili      		= $this->input->post('ddldomisili');
		$npwp				= $this->input->post('txtNPWP');
		$alamat   	 		= $this->input->post('txtAlamat');
		$no_tlp_web   	 	= $this->input->post('txtNoTelpWeb');
		$pic_vendor   	 	= $this->input->post('txtPICVendor');
		$email   	 		= $this->input->post('txtEmail');
		$no_rekening   	 	= $this->input->post('txtNoRekening');
		$nama_bank   	 	= $this->input->post('txtNamaBank');
		$acctnumber   	 	= $this->input->post('txtAcctNumber');
		$sketpp23   	 	= $this->input->post('txtsketpp23');
		$sketDtp   	 	    = $this->input->post('txtsketDtp');
		$skbpph23   	    = $this->input->post('txtskbpph23');
		$skbpphlainnya   	= $this->input->post('txtskbpphlainnya');
		$tin   			    = $this->input->post('txttin');
		$ktp   			    = $this->input->post('txtktp');
		$skd   			    = $this->input->post('txtskd');
		$kodenegara   	    = $this->input->post('txtkodenegara');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$cek_kode_negara = $this->master->check_kode_negara($domisili);
		$kode_negara = $cek_kode_negara->ID_CODE;

		$data = array(
			'NAMA_VENDOR' 						=> $nama_rekanan,
			'DOMISILI' 							=> $domisili,
			'NOMOR_NPWP' 						=> $npwp, 
			'ALAMAT' 							=> $alamat,
			'NO_TLP' 							=> $no_tlp_web,
			'NAMA_PIC_VENDOR' 					=> $pic_vendor,
			'ALAMAT_EMAIL' 						=> $email,
			'NAMA_BANK' 						=> $nama_bank,
			'NAMA_REKENING' 					=> $no_rekening,
			'ACCT_NUMBER' 						=> $acctnumber,
			'S_KET_PP23' 						=> $sketpp23,
			'S_KET_DTP' 						=> $sketDtp,
			'SKB_PPH23' 						=> $skbpph23,
			'SKB_PPH_LAINNYA' 					=> $skbpphlainnya,
			'TIN' 								=> $tin,
			'KTP' 								=> $ktp,
			'SKD' 								=> $skd,
			'KODE_NEGARA' 						=> $kode_negara
		);

		$message = "The '".$nama_rekanan."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$total = $this->master->check_exist_vendor_tax($nama_rekanan);

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total < 1) 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_vendor_tax($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_vendor_tax($data, $id_rekanan);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}

	public function delete_vendor_tax($id_rekanan){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_vendor_tax($id_rekanan);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	function cetak_data_vendor_tax()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Vendor Tax")
		->setSubject("Cetakan Master Data Vendor Tax")
		->setDescription("Cetak Master Data Vendor Tax")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No NPWP");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Alamat");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Domisili");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "No Telpn");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nama PIC Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Alamat email");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Nama rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Acct Number");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "S.Ket PP 23");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "S.Ket DTP");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "SKB PPh 23");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "SKB PPh Lainnya");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "TIN");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "KTP");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "SKD");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Negara dan Kodenya");

		$query	= $this->master->get_master_vendor_tax();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NOMOR_NPWP']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DOMISILI']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NO_TLP']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NAMA_PIC_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['ALAMAT_EMAIL']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['ACCT_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['S_KET_PP23']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['S_KET_DTP']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['SKB_PPH23']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['SKB_PPH_LAINNYA']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['TIN']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['KTP']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['SKD']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['KODE_NEGARA']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Vendor Tax");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Vendor Tax.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function save_import_upload_vendor_tax()
	{

		$this->load->library('excel');

		if(isset($_FILES["file"]["name"])) {
			ini_set('precision', '20');

			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			foreach($object->getWorksheetIterator() as $worksheet){

				$highestRow    = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();

				for($row=2; $row<=$highestRow; $row++){

					$nama_rekanan    		= $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$npwp  					= $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$alamat       			= $worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue();  
					$domisili       		= $worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue();  
					$no_tlp_web       		= $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$pic_vendor  		   	= $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$email 					= $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$no_rekening   			= $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$nama_bank 				= $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$acctnumber  			= $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$sket_pp23  			= $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$sket_dtp  				= $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					$skbpph23  				= $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$skb_pph_lainnya  	    = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					$tin  	    			= $worksheet->getCellByColumnAndRow(14, $row)->getValue();
					$ktp  	    			= $worksheet->getCellByColumnAndRow(15, $row)->getValue();
					$skd  	    			= $worksheet->getCellByColumnAndRow(16, $row)->getValue();
					$negara_kode  	    	= $worksheet->getCellByColumnAndRow(17, $row)->getValue();

					$cek_domisili = $this->master->check_kode_negara($domisili);
					$kode_negara = $cek_domisili->ID_CODE;
					
					$data[] = array
					(
						'NAMA_VENDOR' 						=> $nama_rekanan,
						'NOMOR_NPWP' 						=> $npwp, 
						'ALAMAT' 							=> $alamat,
						'DOMISILI' 							=> $domisili,
						'NO_TLP' 							=> $no_tlp_web,
						'NAMA_PIC_VENDOR' 					=> $pic_vendor,
						'ALAMAT_EMAIL' 						=> $email,
						'NAMA_BANK' 						=> $nama_bank,
						'NAMA_REKENING' 					=> $no_rekening,
						'ACCT_NUMBER' 						=> $acctnumber,
						'S_KET_PP23' 						=> $sket_pp23,
						'S_KET_DTP' 						=> $sket_dtp,
						'SKB_PPH23' 						=> $skbpph23,
						'SKB_PPH_LAINNYA' 					=> $skb_pph_lainnya,
						'TIN' 								=> $tin,
						'KTP' 								=> $ktp,
						'SKD' 								=> $skd,
						'KODE_NEGARA' 						=> $kode_negara
					);

				}
			}

			$valuetrue = $this->master->insert_upload_vendor_import_tax($data);

			if($valuetrue)
			{
				$result['status']   = true;
				$result['messages'] = "Data successfully imported";
			}else
			{
				$result['status']   = false;
				$result['messages'] = "Data failed imported";
			}

			echo json_encode($result);

		}

	}

	function load_ddl_domisili()
	{
		$hasil	= $this->master->get_master_domisili();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_NEGARA']."' data-name='".$row['NAMA_NEGARA']."' >".$row['NAMA_NEGARA']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

//region MASTER RATE
	public function rate()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/rate", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master rate";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/rate";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_rate(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('date_from'));
			$exp_date_to   = explode("/", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all    = $this->master->get_rate_datatable($date_from,$date_to);
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number,
					'id_rate' 			=> $value['ID_RATE'], 
					'currency_date' 	=> date("d-m-Y",strtotime($value['CURRENCY_DATE'])), 
					'usd' 				=> $value['USD'], 
					'eur' 				=> $value['EUR'],
					'sgd' 				=> $value['SGD'], 
					'aud' 				=> $value['AUD'],
					'jpy' 				=> $value['JPY'], 
					'hkd' 				=> $value['HKD']
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

	function cetak_data_rate()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Directorat")
		->setSubject("Cetakan Master Data Directorat")
		->setDescription("Cetak Master Data Directorat")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Currency Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "USD");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "EUR");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "SGD");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "AUD");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "JPY");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "HKD");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$query	= $this->master->get_master_rate($date_from,$date_to);
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['CURRENCY_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['EUR']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['SGD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['AUD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['JPY']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['HKD']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Rate");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data rate.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_rate()
	{
		$this->form_validation->set_rules('txtUSD', 'USD', 'trim|required');
		$this->form_validation->set_rules('txtEUR', 'EUR', 'trim|required');
		$this->form_validation->set_rules('txtSGD', 'SGD', 'trim|required');
		$this->form_validation->set_rules('txtAUD', 'AUD', 'trim|required');
		$this->form_validation->set_rules('txtJPY', 'JPY', 'trim|required');
		$this->form_validation->set_rules('txtHKD', 'HKD', 'trim|required');

		$id_rate  = $this->input->post('id_rate');
		$usd	= $this->input->post('txtUSD');

		// echo $usd; die;
		
		$eur    = $this->input->post('txtEUR');
		$sgd	= $this->input->post('txtSGD');
		$aud    = $this->input->post('txtAUD');
		$jpy	= $this->input->post('txtJPY');
		$hkd    = $this->input->post('txtHKD');
		$inputcurrency_date    = $this->input->post('txtCurrencyDate');
		$currency_date = str_replace('/', '-', $inputcurrency_date);
		$convert_currency_date = date("Y-m-d",strtotime($currency_date));

		// echo $convert_currency_date; die;

		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'USD' 	=> $usd,
			'EUR' 	=> $eur,
			'SGD' 	=> $sgd,
			'AUD' 	=> $aud,
			'JPY' 	=> $jpy,
			'HKD' 	=> $hkd,
			'CURRENCY_DATE' 	=> $convert_currency_date
		);

		if ($this->form_validation->run() == TRUE) 
		{
			if($isNewRecord == "1")
			{
				$save = $this->master->save_data_rate($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_rate($data, $id_rate);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_rate($id_rate){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_rate($id_rate);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER RATE

//region MASTER APPROVAL

	public function master_approval()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/master_approval", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Approval";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/master_approval";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb'] = $breadcrumb;
			$data['directorat'] = get_all_directorat();
			$data['employee']   = $this->master->get_employee_approval();


			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_ddl_spc_level()
	{		
		$result  = "-- Choose --";
		$result .= "<option value='HoU' data-name='HoU' > HoU </option>";
		$result .= "<option value='HoG' data-name='HoG' > HoG </option>";
		$result .= "<option value='HOG Budget' data-name='HOG Budget' > HOG Budget </option>";
		$result .= "<option value='Director' data-name='Director' > Director </option>";
		$result .= "<option value='CFO' data-name='HoG' > CFO </option>";
		$result .= "<option value='Comite of Budget' data-name='Comite of Budget' > Comite of Budget </option>";

		echo $result;
	}

	public function load_ddl_pic_by_level()
	{		
		$hasil	= $this->master->get_master_employee();
		$query  = $hasil['querymu'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose employee --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['ID_EMPLOYEE']."' data-name='".$row['NAMA']."' data-jabatan='".$row['JABATAN']."' >".$row['NAMA']."</option>";
		}		
		echo $result;
		$query->free_result();
	}


	public function load_data_master_approval(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$directorat = $this->input->post('directorat');
		$level      = $this->input->post('level');

		$get_all    = $this->master->get_master_approval_datatable($directorat, $level);
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {


				$start = $value['DELEGATION_START_PERIOD'];
				$date_start = ($start != ""  &&  ($start != "0000-00-00" || $start != "0000-00-00 00:00:00")) ? dateFormat($value['DELEGATION_START_PERIOD'], 5, false) : '';

				$end = $value['DELEGATION_END_PERIOD'];
				$date_end = ($end != ""  &&  $end != "0000-00-00") ? dateFormat($value['DELEGATION_END_PERIOD'], 5, false) : '';

				$row[] = array(
					'no'               => $number, 
					'id_approval'      => $value['ID_APPROVAL'], 
					'id_dir_code'      => $value['ID_DIR_CODE'], 
					'directorat_name'  => $value['DIRECTORAT_NAME'],
					'id_division'      => $value['ID_DIVISION'],
					'division_name'    => $value['DIVISION_NAME'],
					'id_unit'          => $value['ID_UNIT'],
					'unit_name'        => $value['UNIT_NAME'],
					'pic_level'        => $value['PIC_LEVEL'], 
					'pic_name'         => $value['PIC_NAME'], 
					'pic_email'        => $value['PIC_EMAIL'], 
					'jabatan'          => $value['JABATAN'],
					'pic_delegation'   => $value['PIC_DELEGATION'], 
					'delegation_start' => $date_start,
					'delegation_end'   => $date_end,

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

	function cetak_data_master_approval()
		{

			if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
				@set_time_limit(300);
			}

			ini_set('memory_limit', '-1');

			include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
			$excel = new PHPExcel();

		// Settingan awal fil excel
			$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
			->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
			->setTitle("Cetak Master Data Jabatan")
			->setSubject("Cetakan Master Data Jabatan")
			->setDescription("Cetak Master Data Jabatan")
			->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
			$excel->setActiveSheetIndex(0)->setCellValue('B1', "DIRECTORATE_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('C1', "DIVISION_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('D1', "UNIT_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('E1', "PIC_LEVEL");
			$excel->setActiveSheetIndex(0)->setCellValue('F1', "PIC_NAME");
			$excel->setActiveSheetIndex(0)->setCellValue('G1', "JABATAN");
			$excel->setActiveSheetIndex(0)->setCellValue('H1', "PIC_DELEGATION");
			$excel->setActiveSheetIndex(0)->setCellValue('I1', "DELEGATION_START");
			$excel->setActiveSheetIndex(0)->setCellValue('J1', "DELEGATION_END");

			$query	= $this->master->get_master_master_approval();
			$hasil  = $query['querymu'];

			$numrow    = 2;
			$number    = 0 +1;

			foreach($hasil->result_array() as $row)	
			{
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['DIRECTORAT_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DIVISION_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['UNIT_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['PIC_LEVEL']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['PIC_NAME']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['JABATAN']);
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['PIC_DELEGATION']);
				$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DELEGATION_START_PERIOD']);
				$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['DELEGATION_END_PERIOD']);

				$numrow++;

			}

		// Set width kolom
			$loop_column = horizontal_loop_excel("A", 22);
			foreach ($loop_column as $key => $value) 
			{
				$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
			}

			$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Master Data Approval");
			$excel->setActiveSheetIndex(0);

		// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Approval.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_master_approval()
	{

		$id_approval = $this->input->post('id_approval');
		$id_dir_code = $this->input->post('directorat');
		$id_division = $this->input->post('division');
		$id_unit     = $this->input->post('unit');
		$pic_name    = $this->input->post('pic_name');
		$pic_email   = $this->input->post('pic_email');
		$pic_jabatan = $this->input->post('pic_jabatan');
		$pic_level   = $this->input->post('pic_level');
		
		$isNewRecord = $this->input->post('isNewRecord');

		// if($pic_level == )

		$data = array(
				"ID_DIR_CODE" => $id_dir_code,
				"ID_DIVISION" => $id_division,
				"ID_UNIT"     => $id_unit,
				"PIC_LEVEL"   => $pic_level,
				"PIC_NAME"    => $pic_name,
				"PIC_EMAIL"   => $pic_email,
				"JABATAN"     => $pic_jabatan
		);

		$result['status']   = false;
		$result['messages'] = "Failed to save data";

		if($isNewRecord == "1")
		{
			if($this->crud->create("MASTER_APPROVAL", $data) > 0){
				$result['status'] = true;
			}
		}
		else
		{
			if($this->crud->update("MASTER_APPROVAL", $data, array("ID_APPROVAL" => $id_approval)) !== -1){
				$result['status'] = true;
			}
		}

		echo json_encode($result);

	}


	public function delete_master_approval($id_approval){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->crud->delete("MASTER_APPROVAL", array("ID_APPROVAL" => $id_approval));

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}

	//endregion MASTER APPROVAL

	//region MASTER PROC TYPE

	public function proc_type()
	{
		
		if($this->ion_auth->is_admin() == true || in_array($this->module_name."/proc_type", $this->session->userdata['menu_url']) ){

			$data['title']          = "Master Proc Type";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/proc_type";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Master Data", "link" => "#", "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "#", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_proc_type(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->master->get_proc_type_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = $this->input->post('start');
		$number     = $start+1;

		if($total > 0){
			foreach($data as $value) {

				$row[] = array(
					'no'              	=> $number,
					'proc_type_id' 		=> $value['PROC_TYPE_ID'], 
					'mapping' 			=> $value['MAPPING'], 
					'proc_name' 	=> $value['PROC_NAME']
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

	function load_ddl_proc_type()
	{
		$hasil	= $this->master->get_master_proc_type();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Proc Type --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['PROC_TYPE_ID']."' data-name='".$row['PROC_NAME']."' data-mapping='".$row['MAPPING']."' >".$row['PROC_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function cetak_data_proc_type()
	{

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()	->setCreator('SYSTEM-FINANCE_TOOL')
		->setLastModifiedBy('SYSTEM-FINANCE_TOOL')
		->setTitle("Cetak Master Data Proc Type")
		->setSubject("Cetakan Master Data Proc Type")
		->setDescription("Cetak Master Data Proc Type")
		->setKeywords("DATA");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "MAPPING");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "PROC_NAME");

		$query	= $this->master->get_master_proc_type();
		$hasil  = $query['query'];

		$numrow    = 2;
		$number    = 0 +1;

		foreach($hasil->result_array() as $row)	
		{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number++);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['MAPPING']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['PROC_NAME']);
			
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 22);
		foreach ($loop_column as $key => $value) 
		{
			$excel->getActiveSheet()->getColumnDimension($value)->setWidth(10);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Master Data Proc Type");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Master Data Proc Type.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_proc_type()
	{
		$this->form_validation->set_rules('txtMapping', 'Mapping', 'trim|required');
		$this->form_validation->set_rules('txtProcTypeName', 'Proc Type Name', 'trim|required');

		$proc_type_id      	= $this->input->post('proc_type_id');
		$mapping	= $this->input->post('txtMapping');
		$proc_name    = $this->input->post('txtProcTypeName');
		$isNewRecord    	= $this->input->post('isNewRecord');

		$data = array(
			'MAPPING' 	=> $mapping,
			'PROC_NAME' 	=> $proc_name
		);

		$message = "The '".$proc_name."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{
			$check_dir = $this->master->check_exist_proc_type($proc_name);
			$total      = $check_dir['total_data'];

			if($isNewRecord == "1")
			{
				/*echo json_encode($data);die();*/
				if ($total != "1") 
				{
					/*echo "masuk komandan"; die();*/

					$save = $this->master->save_data_proc_type($data);

					if($save)
					{			
						echo '1';
					} else {			
						echo '0';
					}
				}
				else
				{
					echo $message;
				}

			}
			else
			{
				/*echo 'keluar';die();*/
				$save = $this->master->update_data_proc_type($data, $proc_type_id);

				if($save)
				{			
					echo '1';
				} else {			
					echo '0';
				}
			}

			
		}
		else
		{
			echo validation_errors();
		}

	}



	public function delete_proc_type($proc_type_id){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->master->delete_data_proc_type($proc_type_id);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

	//endregion MASTER PROC TYPE

}

/* End of file Master.php */
	/* Location: ./application/controllers/Master.php */