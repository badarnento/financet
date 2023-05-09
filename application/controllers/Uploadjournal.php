<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadjournal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Uploadjournal_mdl', 'uploadjournal');

	}

	public function upload_journal()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("uploadjournal/upload-journal", $this->session->userdata['menu_url']) ){
			$data['title']          = "Upload Journal";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/upload_journal";
			$data['get_nature']  	= get_nature();

			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Upload Journal", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_upload_journal(){

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

		$get_all = $this->uploadjournal->get_upload_journal_datatable($gl_date_from, $gl_date_to, $nature, $journaltype);
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


	public function save_import_upload_journal()
	{

		$this->load->library('excel');
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(300);
		}

		ini_set('memory_limit', '-1');

		if(isset($_FILES["file"]["name"])) {

			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			foreach($object->getWorksheetIterator() as $worksheet){

				$highestRow    = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				
				$col_d = $worksheet->getCellByColumnAndRow(3, 1)->getValue();
				$col_e = $worksheet->getCellByColumnAndRow(4, 1)->getValue();
				$col_f = $worksheet->getCellByColumnAndRow(5, 1)->getValue();

				$usd = false;
				if(str_replace(" ", "", trim(strtoupper($col_d))) == "DEBET(USD)" && str_replace(" ", "", trim(strtoupper($col_e))) == "CREDIT(USD)" && str_replace(" ", "", trim(strtoupper($col_f))) == "KURS"){
					$usd = true;
				}

				for($row=2; $row<=$highestRow; $row++){

					$gl_date_excel = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$dateValue     = PHPExcel_Shared_Date::ExcelToPHP($gl_date_excel);
					$convertdate   = date('Y-m-d',$dateValue);
					$batch_name    = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$journal_name  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();  

					if($usd){

						$debet_usd           = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$debet_usd           = ($debet_usd == "" ) ? 0 : $debet_usd;
						$credit_usd          = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
						$credit_usd          = ($credit_usd == "" ) ? 0 : $credit_usd;
						$kurs                = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
						$kurs                = ($kurs == "" ) ? 0 : $kurs;
						$saldo_awal          = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$startdebit          = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
						$debit               = ($startdebit == "" ) ? 0 : $startdebit;
						$startcredit         = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$credit              = ($startcredit == "" ) ? 0 : $startcredit;
						$nature              = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						$account_description = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						$journal_description = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
						$reference_1         = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
						$reference_2         = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
						$reference_3         = $worksheet->getCellByColumnAndRow(14, $row)->getValue();

						$data[] = array(
									'GL_DATE'             => $convertdate,
									'BATCH_NAME'          => $batch_name,
									'JOURNAL_NAME'        => $journal_name,
									'SALDO_AWAL'          => $saldo_awal,
									'DEBET_USD'           => $debet_usd,
									'CREDIT_USD'          => $credit_usd,
									'KURS'                => $kurs,
									'DEBIT'               => $debit,
									'CREDIT'              => $credit,
									'NATURE'              => $nature,
									'ACCOUNT_DESCRIPTION' => $account_description,
									'JOURNAL_DESCRIPTION' => $journal_description,
									'REFERENCE_1'         => $reference_1,
									'REFERENCE_2'         => $reference_2,
									'REFERENCE_3'         => $reference_3
								);

					}else{

						$saldo_awal          = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$startdebit          = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
						$debit               = ($startdebit == "" ) ? 0 : $startdebit;
						$startcredit         = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
						$credit              = ($startcredit == "" ) ? 0 : $startcredit;
						$nature              = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$account_description = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
						$journal_description = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$reference_1         = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						$reference_2         = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						$reference_3         = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

						$data[] = array(
									'GL_DATE'		 	 	 => $convertdate,
									'BATCH_NAME'       		 => $batch_name,
									'JOURNAL_NAME'           => $journal_name,
									'SALDO_AWAL'           	 => $saldo_awal,
									'DEBIT'           		 => $debit,
									'CREDIT'           		 => $credit,
									'NATURE'           		 => $nature,
									'ACCOUNT_DESCRIPTION'    => $account_description,
									'JOURNAL_DESCRIPTION'    => $journal_description,
									'REFERENCE_1'            => $reference_1,
									'REFERENCE_2'    		 => $reference_2,
									'REFERENCE_3'    		 => $reference_3
								);
					}
				}
			}

		    $valuetrue = $this->crud->create_batch("GL_JOURNAL_LINE", $data);

			if($valuetrue)
			{
				$result['status']   = true;
				$result['messages'] = "Data successfully imported";
			}else{
				$result['status']   = false;
				$result['messages'] = "Data failed imported";
			}
			
			echo json_encode($result);

		}

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


	function download_data_upload_journal()
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
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Debet (USD)");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Credit (USD)");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Kurs");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Saldo Awal");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Debit");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Reference 1");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Reference 2");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Reference 3");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$nature = $this->input->get('nature');
		$journaltype = $this->input->get('journaltype');

		$hasil = $this->uploadjournal->get_download_upload_journal($date_from,$date_to,$nature,$journaltype);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['GL_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DEBET_USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['CREDIT_USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['KURS']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['SALDO_AWAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['DEBIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['REFERENCE_1']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['REFERENCE_2']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['REFERENCE_3']);

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
		header('Content-Disposition: attachment; filename="upload Journal.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
}



/* End of file Uploadjournal.php */

/* Location: ./application/controllers/Uploadjournal.php */