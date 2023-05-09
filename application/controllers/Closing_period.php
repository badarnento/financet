<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Closing_period extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Closing_period_mdl', 'closing_period');

	}

	public function closing_period()
	{

		
		if($this->ion_auth->is_admin() == true || in_array("Closing_period/closing_period", $this->session->userdata['menu_url']) ){
			
			$data['title']          = " Closing Period ";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/closing_period";

			$groups = $this->session->userdata('group_id');

			foreach ($groups as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName['NAME'];
			}

			$data ['group_name'] 	= $group_name;
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	function load_ddl_closing()
	{		
		$result ="";
		$result .= "<option value='' data-name='' > -- Choose --</option>";
		$result .= "<option value='"."OPEN"."' data-name='"."OPEN"."' >"."Open"."</option>";	
		$result .= "<option value='"."CLOSE"."' data-name='"."CLOSE"."' >"."Close"."</option>";
		echo $result;

	}

	function load_ddl_month()
	{	
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Month --</option>";
		for ($month = 1; $month <= 12; $month++) 
		{
			$dateObj   = DateTime::createFromFormat('!m', $month);
			$monthName = $dateObj->format('F');
			$result .= "<option value='".$month."' data-name='".$month."' >".$monthName."</option>";
		}		
		echo $result;
	}

	function load_ddl_closing_year()
	{
		$hasil	= $this->closing_period->get_closing_exist_year();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Year --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['YEAR']."' data-name='".$row['YEAR']."' >".$row['YEAR']."</option>";
		}		
		echo $result;
		$query->free_result();

	}


	public function load_data_closing(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all = $this->closing_period->get_closing_datatable();
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$i=1;
		$user_login = $user_login = get_user_data($this->session->userdata('user_id'));

		$optClosing = array("-- Choose --", "Open", "Close");

		if($total > 0){
			
			foreach($data as $value) {

				$selected = " selected";
				$disabled = " disabled";
				$optVal = "";

				$valAproved = $value['STATUS'];

				if($valAproved == "OPEN")
				{
					$valSelected = 1;
				}
				elseif($valAproved == "CLOSE")
				{					
					$valSelected = 2;
				}else
				{
					$valSelected = 0;
				}

				for ($i=0; $i < count($optClosing) ; $i++) {

					$selected = ($i == $valSelected) ? " selected" : "";

					$optVal .= '<option value="'.$i.'"'.$selected.'>'.$optClosing[$i].'</option>';
				}

				$checkbox = '<div class="checkbox form-group m-b-0"><select id="checkbox-'.$number.'" name="checkbox-data" class="checklist" data-id="'.$number.'" class="form-control input-sm closing_opt select-center" '.$disabled.'>';
				$checkbox .= $optVal;
				$checkbox .=  '</select></div>';
			
				$description = '<textarea id="description-'.$number.'" class="form-control description" rows="3" '.$disabled.'>'.$value['DESCRIPTION'].'</textarea>';

				$row[] = array(
					'no'             	=> $number,
					'id_closing'		=> $value['ID'],
					'year'       		=> $value['YEAR'],
					'month' 			=> $value['MONTH'],
					'month_text' 		=> $value['MONTH_TEXT'],
					'status'   			=> $checkbox,
					'status_value'   	=> $value['STATUS'],
					'description'		=> $description,
					'description_value'	=> $value['DESCRIPTION'],
				);

				$i++;
				$number++;
			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);

	}


	function download_data_closing()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No.");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Year");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Month");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Status");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Description");

		$hasil = $this->closing_period->get_download_data_closing();

		$numrow  = 2;
		$number = 1;
		$nomor = 0;
		$nmjurnal = '';

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['YEAR']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['MONTH']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['STATUS']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DESCRIPTION']);

			$number++;
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
		header('Content-Disposition: attachment; filename="Closing Period.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function save_closing()
	{
		$this->form_validation->set_rules('ddlYear', 'Year', 'trim|required');
		$this->form_validation->set_rules('ddlMonth', 'Month', 'trim|required');
		$this->form_validation->set_rules('ddlStatus', 'Status', 'trim|required');
		// $this->form_validation->set_rules('txtDescription', 'Description', 'trim|required');

		$id_closing      		= $this->input->post('id_closing');
		$year					= $this->input->post('ddlYear');
		$month					= $this->input->post('ddlMonth');
		$status    				= $this->input->post('ddlStatus');
		$description      		= $this->input->post('txtDescription');
		$isNewRecord    		= $this->input->post('isNewRecord');

		$data = array(
			'YEAR' 				=> $year,
			'MONTH' 			=> $month,
			'STATUS' 			=> $status,
			'DESCRIPTION' 		=> $description
		);

		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F');

		$message = "The '".$monthName." ".$year."' already exist";

		if ($this->form_validation->run() == TRUE) 
		{

			$check_closing = $this->closing_period->check_exist_closing($year,$month);
			$total      = $check_closing['total_data'];

			if($isNewRecord == "1")
			{
			
				if ($total != "1") 
				{

						$save = $this->closing_period->save_data_closing($data);

						if($save)
						{		
							if($status == "CLOSE")
							{
								// echo "masuk save closing komandan ".$status.$year.$month. "!!!"; die;

								$call_procedure   = $this->closing_period->call_procedure_closing($year,$month);
							}

							echo '1';

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
					$save = $this->closing_period->update_data_closing($data, $id_closing);

					if($save)
					{	

						if($status == "CLOSE")
						{
							// echo "masuk edit closing komandan ".$status.$year.$month. "!!!"; die;
								$call_procedure   = $this->closing_period->call_procedure_closing($year,$month);
						}

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


	public function delete_closing($id_closing){

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->closing_period->delete_data_closing($id_closing);

		if($delete > 0){
			$result['status']   = true;
			$result['messages'] = "Data successfully deleted";
		}

		echo json_encode($result);
	}	

}



/* End of file Closing_period.php */

/* Location: ./application/controllers/Closing_period.php */