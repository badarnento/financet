<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadjournalar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Uploadjournalar_mdl', 'uploadjournalar');

	}

	public function upload_journal_ar()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("Uploadjournalar/upload_journal_ar", $this->session->userdata['menu_url']) ){

			$data['title']          = "Upload Journal AR";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/upload_journal_ar";
			$data['get_nature']  	= get_nature();
			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Journal AR", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_upload_journal_ar(){

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

		$get_all = $this->uploadjournalar->get_upload_journal_ar_datatable($gl_date_from, $gl_date_to, $nature);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nomor   = 1;
		$i=1;
		$nmjurnal = '';
		$replacejurnal ='';

		if($total > 0){

			$ap_netting_journal = array();
			$ap_netting_journal_data = array();

			foreach($data as $bVal) {

				if($bVal['STATUS_AP_NETTING'] == "Y"){

					$journal_name = $bVal['JOURNAL_NAME'];

					$ap_netting_journal[] = $journal_name;
					$ap_netting_journal_data[$journal_name] = array(
																		"AP_INVOICE"     => $bVal['AP_INVOICE'],
																		"AP_AMOUNT"      => $bVal['AP_AMOUNT'],
																		"AP_DESCRIPTION" => $bVal['AP_DESCRIPTION']
																	);

				}

			}

			foreach($data as $value) {

				if ($number == 1)
				{
					$nmjurnal =  $value['JOURNAL_NAME'];
					$nomor = 1;
				}
				else
				{
					if($nmjurnal ==  $value['JOURNAL_NAME'])
					{
						$nmjurnal =  $value['JOURNAL_NAME'];
						$nomor++;

					}
					else
					{
						$nmjurnal =  $value['JOURNAL_NAME'];
						$nomor = 1;
					}
				}

				$journal_encypt = base64url_encode($value['JOURNAL_NAME'].$this->config->item('encryption_key'));

				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal);
				$replacejurnal = (!empty($replacejurnal) || $replacejurnal != "") ? $replacejurnal : "xx1";

				if($value['VALIDATED'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['VALIDATED'].' checked><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';

					// $action = '<a href="javascript:void(0)" class="action-edit" title="Click to edit " hidden><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete " hidden><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

					$action = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete " hidden><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['VALIDATED'].'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';

					// $action = '<a href="javascript:void(0)" class="action-edit" title="Click to edit "><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete "><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

					$action = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete "><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
				}

				if($value['VALIDATED'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['VALIDATED'].' checked><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';

					// $action = '<a href="javascript:void(0)" class="action-edit" title="Click to edit " hidden><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete " hidden><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

					$action = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete " hidden><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['VALIDATED'].'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';

					// $action = '<a href="javascript:void(0)" class="action-edit" title="Click to edit "><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete "><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

					$action = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete "><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
				}
				$ap_amount_gl      = $value['AP_AMOUNT_GL'];
				$ap_description_gl = $value['AP_DESCRIPTION_GL'];
				$ap_invoice_val_gl = $value['AP_INVOICE_GL'];

				$ap_invoice = '<span id="ap_invoice-'.$replacejurnal.'"></span>';
				$ap_credit  = '<span id="ap_credit-'.$journal_encypt.'"></span>';
				$ap_description  = '<span id="ap_description-'.$journal_encypt.'"></span>';

				$substr_batch = substr($value['BATCH_NAME'], 0, 7);
				$jur_name  = $value['JOURNAL_NAME'];
				if($ap_amount_gl > 0 && $ap_invoice_val_gl != "" && $substr_batch != "netting"  /* || in_array($value['JOURNAL_NAME'], $ap_netting_journal)*/){
					
					$disabled    = "";
					$selected    = "";
					$valApAmount = "";
					$valApDesc   = "";
					if(in_array($value['JOURNAL_NAME'], $ap_netting_journal)){
						$disabled = " disabled";
						$selected = " selected";

						$valApAmount = number_format($ap_netting_journal_data[$jur_name]['AP_AMOUNT'],0,',','.');
						$valApDesc = $ap_netting_journal_data[$jur_name]['AP_DESCRIPTION'];

					}

					$netting_opt = '<option value="0" data-credit="" data-description="">--Choose--</option>';
					$netting_opt .= '<option value="'.$ap_invoice_val_gl.'" data-credit="'.number_format($ap_amount_gl,0,',','.').'" data-description="'.$ap_description_gl.'"'.$selected.'>'.$ap_invoice_val_gl.'</option>'."\r\n";

					$ap_invoice = '<div class="form-group m-b-0 px-5 d-inline-block"><select id="ap_invoice-'.$replacejurnal.'" class="form-control input-sm ap_invoice select-center"'.$disabled.'>'.$netting_opt.'</select></div>';
					$ap_credit      = '<span id="ap_credit-'.$journal_encypt.'">'.$valApAmount.'</span>';
					$ap_description = '<span id="ap_description-'.$journal_encypt.'">'.$valApDesc.'</span>';
				}


				$row[] = array(
					'no'                  => $number,
					'gl_date'             => date("d-m-Y",strtotime($value['GL_DATE'])),
					'batch_name'          => $value['BATCH_NAME'],
					'journal_name'        => $value['JOURNAL_NAME'],
					'journal_encypt'      => $journal_encypt,
					'saldo_awal'          => number_format($value['SALDO_AWAL'],0,',','.'),
					'debit'               => number_format($value['DEBIT'],0,',','.'),
					'credit'              => number_format($value['CREDIT'],0,',','.'),
					'nature'              => $value['NATURE'],
					'account_description' => $value['ACCOUNT_DESCRIPTION'],
					'journal_description' => $value['JOURNAL_DESCRIPTION'],
					'reference_1'         => $value['REFERENCE_1'],
					'reference_2'         => $value['REFERENCE_2'],
					'reference_3'         => $value['REFERENCE_3'],
					'ar_invoice_netting'  => $value['AR_INVOICE_NETTING'],
					'ap_invoice'          => $ap_invoice,
					'ap_credit'           => $ap_credit,
					'ap_description'      => $ap_description,
					'validated'           => $checkbox,
					'action'              => $action,

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


	function update_ap_netting()
	{
		
		$journal_name   = $this->input->post('journal_name');
		$ap_netting     = $this->input->post('ap_netting');
		$ap_invoice     = $this->input->post('ap_invoice');
		$ap_description = $this->input->post('ap_description');
		$ap_credit      = $this->input->post('ap_credit');

		$status   = false;
		$messages = "";

		$data = array(
						'STATUS_AP_NETTING' => $ap_netting,
						'AP_INVOICE'        => ($ap_invoice != " ") ? $ap_invoice : "",
						'AP_DESCRIPTION'    => ($ap_description != " ") ? $ap_description : "",
						'AP_AMOUNT'         => ($ap_credit > 0) ? $ap_credit : 0
					);

		$update = $this->uploadjournalar->update_ap_netting($journal_name, $data);

		if($update !== -1){
			
			if($this->crud->call_procedure("JOURNAL_AR_NETTING") !== -1){
				$status   = true;
			}
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}


	public function save_import_upload_journal_ar()
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

				for($row=2; $row<=$highestRow; $row++){
					$gl_date_excel    		= $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$dateValue 		 		= PHPExcel_Shared_Date::ExcelToPHP($gl_date_excel);                       
					$convertdate     		= date('Y-m-d',$dateValue);
					$batch_name  			= $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$journal_name       	= $worksheet->getCellByColumnAndRow(2, $row)->getValue();  
					$saldo_awal       		= $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$startdebit   		    = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$debit 					= ($startdebit == "" ) ? 0 : $startdebit;
					$startcredit   			= $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$credit 				= ($startcredit == "" ) ? 0 : $startcredit;
					$nature   				= $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$account_description   	= $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$journal_description   	= $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$reference_1   			= $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$reference_2   		    = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$reference_3   	        = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					
					$data[] = array
					(
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



		    $valuetrue = $this->crud->create_batch("JOURNAL_AR", $data);
	

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


	function update_journal_validate()
	{

		$validated_status  = $this->input->post('validated_status');
		$nojournal = $this->input->post('nojournal');

		$status       = false;
		$messages     = "";

		$data = array(
						'VALIDATED' => $validated_status
					);

		$update = $this->crud->update("JOURNAL_AR", $data, array("JOURNAL_NAME" => $nojournal));

		if($update !== -1){
			$status = true;
			if ($validated_status == 'Y')
			{
			$messages = "Data has been Validated";
			}
			else
			{
		    $messages = "Data has been Unvalidated !!";
			}
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

	public function edit_journal_ar()
	{
		// $this->form_validation->set_rules('txtBatchName', 'Batch Name', 'trim|required');
		// $this->form_validation->set_rules('txtNamaVendor', 'Vendor Name', 'trim|required');
		// $this->form_validation->set_rules('txtNoInvoice', 'No Invoice', 'trim|required');
		// $this->form_validation->set_rules('txtDescription', 'Description', 'trim|required');
		// $this->form_validation->set_rules('txtDPP', 'DPP', 'trim|required');
		// $this->form_validation->set_rules('txtNamaRekening', 'Nama Rekening', 'trim|required');
		// $this->form_validation->set_rules('txtNamaBank', 'Bank Name', 'trim|required');
		// $this->form_validation->set_rules('txtAcctNumber', 'Acct Number', 'trim|required');
		// $this->form_validation->set_rules('txtTop', 'Top', 'trim|required');
		// $this->form_validation->set_rules('txtNature', 'Nature', 'trim|required');


		// $gl_header_id		= $this->input->post('gl_header_id');
		// $tanggal_invoice	= $this->input->post('txtTanggalInvoice');
		// $convert_tanggal_invoice = date("Y-m-d",strtotime($tanggal_invoice));
		// $batch_name			= $this->input->post('txtBatchName');
		// $nama_vendor		= $this->input->post('txtNamaVendor');
		// $no_invoice			= $this->input->post('txtNoInvoice');
		// $no_kontrak			= $this->input->post('txtNoKontrak');
		// $description		= $this->input->post('txtDescription');
		// $dpp 				= preg_replace("/[^a-zA-Z0-9]/", "", $this->input->post('txtDPP'));
		// $no_fpjp			= $this->input->post('txtNoFPJP');
		// $nama_rekening		= $this->input->post('txtNamaRekening');
		// $nama_bank			= $this->input->post('txtNamaBank');
		// $acct_number		= $this->input->post('txtAcctNumber');
		// $top				= $this->input->post('txtTop');
		// $due_date			= date('Y-m-d', strtotime($convert_tanggal_invoice . ' + '.$top.' day'));
		// $nature				= $this->input->post('txtNature');



		// $data = array(
		// 	'TGL_INVOICE'		 	 => $convert_tanggal_invoice,
		// 	'BATCH_NAME'             => $batch_name,
		// 	'NAMA_VENDOR'            => $nama_vendor,
		// 	'NO_INVOICE'   			 => $no_invoice,
		// 	'NO_KONTRAK'      	  	 => $no_kontrak,
		// 	'DESCRIPTION' 			 => $description,
		// 	'DPP'					 => $dpp,
		// 	'NO_FPJP'  				 => $no_fpjp,
		// 	'NAMA_REKENING'      	 => $nama_rekening,
		// 	'NAMA_BANK'       		 => $nama_bank,
		// 	'ACCT_NUMBER'         	 => $acct_number,
		// 	'TOP'    				 => $top,
		// 	'DUE_DATE' 				 => $due_date,
		// 	'NATURE' 				 => $nature
		// );

		// if ($this->form_validation->run() == TRUE) 
		// {

		// 	$save = $this->gl->update_data_upload_journal_ar($data, $gl_header_id);

		// 	if ($save)
		// 	{			
		// 		echo '1';
		// 	}
		// 	else
		// 	{
		// 		echo '0';
		// 	}

		// }
		// else
		// {
		// 	echo validation_errors();
		// }

	}

	public function delete_upload_invoice($journal_name){

		$journal_decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($journal_name));

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$delete = $this->uploadjournalar->delete_data_upload_journal_ar($journal_decrypt);

		if($delete > 0)
		{
				$result['status']   = true;
				$result['messages'] = "Data successfully deleted";
		}
		else
		{
			echo '0';
		}

		echo json_encode($result);
	}


	function download_data_upload_journal_ar()
	{


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
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Validated");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$nature = $this->input->get('nature');

		$hasil = $this->uploadjournalar->get_download_upload_journal_ar($date_from,$date_to,$nature);

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
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['VALIDATED']);

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
		header('Content-Disposition: attachment; filename="upload Journal AR.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}
}



/* End of file Uploadjournalar.php */

/* Location: ./application/controllers/Uploadjournalar.php */