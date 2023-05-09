<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rekonbank extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Rekonbank_mdl', 'rekonbank');

	}

	public function rekon_bank()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("rekonbank/rekon_bank", $this->session->userdata['menu_url']) ){
			$data['title']          = "Rekon Bank";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/rekon_bank";
			$data['get_nature']  	= get_nature();

			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Rekon Bank", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_rekon_bank(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$gl_date_from = "";
		$gl_date_to   = "";
		$nature 	  = $this->input->post('nature');

		if($this->input->post('gl_date_from') != "" && $this->input->post('gl_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('gl_date_from'));
			$exp_date_to   = explode("/", $this->input->post('gl_date_to'));

			$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all = $this->rekonbank->get_rekon_bank_datatable($gl_date_from, $gl_date_to, $nature);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'gl_date'		 		 => date("d-m-Y",strtotime($value['ACCOUNTING_DATE'])),
					'batch_name'       		 => $value['BATCH_NAME'],
					'journal_name'           => $value['JOURNAL_NAME'],
					'currency'               => $value['CURRENCY'],
					'debit'   				 => number_format($value['DEBIT'],0,'.',','),
					'credit'   		 		 => number_format($value['CREDIT'],0,'.',','),
					'nature'    			 => $value['NATURE'],
					'account_description'    => $value['BANK'],
					'journal_description'    => $value['JOURNAL_DESCRIPTION'],
					'reference_1'    		 => $value['BATCH_DESCRIPTION'],
					'reference_2'    		 => $value['JOURNAL_REFERENCE'],
					'reference_3'    		 => $value['LINE_DESCRIPTION']
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


	function download_data_rekon_bank()
	{

		set_time_limit(0); //awalnya 300

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
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Debit");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Batch Description");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Journal Reference");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Line Description");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$nature = $this->input->get('nature');

		$hasil = $this->rekonbank->get_download_rekon_bank($date_from,$date_to,$nature);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['ACCOUNTING_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DEBIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['DEBIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['BATCH_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['JOURNAL_REFERENCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['LINE_DESCRIPTION']);

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
		header('Content-Disposition: attachment; filename="rekon bank.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
}



/* End of file rekonbank.php */

/* Location: ./application/controllers/rekonbank.php */