<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadjournalclosing extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Uploadjournalclosing_mdl', 'uploadjournalclosing');

	}

	public function upload_journal_closing()
	{

		if($this->ion_auth->is_admin() == true || in_array("Uploadjournalclosing/upload_journal_closing", $this->session->userdata['menu_url']) ){
			
			$data['title']          = "GL Closing";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/upload_journal_closing";
			$data['get_nature']  	= get_nature();

			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "GL Closing", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_upload_journal_closing(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$gl_date_from = "";
		$gl_date_to   = "";
		$nature 	  = $this->input->post('nature');
		$journaltype  = $this->input->post('journaltype');

		if($this->input->post('gl_date_from') != "" && $this->input->post('gl_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('gl_date_from'));
			$exp_date_to   = explode("/", $this->input->post('gl_date_to'));

			$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all = $this->uploadjournalclosing->get_upload_journal_closing_datatable($gl_date_from, $gl_date_to, $nature, $journaltype);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'gl_date'		 		 => date("d-m-Y",strtotime($value['GL_DATE'])),
					'batch_name'       		 => $value['BATCH_NAME'],
					'journal_name'           => $value['JOURNAL_NAME'],
					'saldo_awal'   		 	 => number_format($value['SALDO_AWAL'],0,'.',','),
					'debit'   				 => number_format($value['DEBIT'],0,'.',','),
					'credit'   		 		 => number_format($value['CREDIT'],0,'.',','),
					'nature'    			 => $value['NATURE'],
					'account_description'    => $value['ACCOUNT_DESCRIPTION'],
					'journal_description'    => $value['JOURNAL_DESCRIPTION'],
					'reference_1'    		 => $value['REFERENCE_1'],
					'reference_2'    		 => $value['REFERENCE_2'],
					'reference_3'    		 => $value['REFERENCE_3']
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


	function load_ddl_jenis_journal()
	{		
		$result ="";
		$result .= "<option value='' data-name='' >-- Pilih --</option>";
		$result .= "<option value='AR' data-name='AR' >  AR  </option>";
		$result .= "<option value='AP' data-name='AP' >  AP  </option>";
		$result .= "<option value='PY' data-name='PY' >  PY  </option>";
		$result .= "<option value='CM' data-name='CM' >  CM  </option>";
		$result .= "<option value='LA' data-name='LA' >  LA  </option>";
		$result .= "<option value='Others' data-name='Others' >  Others  </option>";
	
		echo $result;
	}


	function download_data_upload_journal_closing()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Accounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Saldo Awal");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Debit");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Reference 1");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Reference 2");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Reference 3");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$nature = $this->input->get('nature');
		$journaltype = $this->input->get('journaltype');

		$hasil = $this->uploadjournalclosing->get_download_upload_journal_closing($date_from,$date_to,$nature,$journaltype);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['GL_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['SALDO_AWAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DEBIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['REFERENCE_1']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['REFERENCE_2']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['REFERENCE_3']);

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
		header('Content-Disposition: attachment; filename="GL Closing.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
}



/* End of file Uploadjournalclosing.php */

/* Location: ./application/controllers/Uploadjournalclosing.php */