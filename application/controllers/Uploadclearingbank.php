<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadclearingbank extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Clearingbank_mdl', 'clearing');

	}

	public function upload_clearing_bank()
	{

		
		if($this->ion_auth->is_admin() == true || in_array("uploadclearingbank/upload-clearing-bank", $this->session->userdata['menu_url']) ){

			$data['title']          = "Upload Clearing Bank";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/upload_clearing_bank";
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_upload_clearing_bank(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$post_date_from = "";
		$post_date_to   = "";

		if($this->input->post('post_date_from') != "" && $this->input->post('post_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('post_date_from'));
			$exp_date_to   = explode("/", $this->input->post('post_date_to'));

			$post_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$post_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$bank = $this->input->post('bank');

		$get_all = $this->clearing->get_upload_clearing_bank_datatable($post_date_from, $post_date_to, $bank);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'transaction_date'		 => $value['TRANSACTION_DATE'],
					'no_rekening'       	 => $value['NO_REKENING'],
					'balance'            	 => $value['BALANCE'],
					'kurs'   		 		 => $value['KURS'],
					'debit'   				 => $value['DEBIT'],
					'credit'   				 => $value['CREDIT'],
					'bank'            	 	 => $value['BANK_NAME'],
					'status'   		 		 => $value['STATUS'],
					'description'   		 => $value['DESCRIPTION']

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

	function load_ddl_bank_transaction()
	{
		$hasil	= $this->clearing->get_ddl_bank_transaction();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Bank --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['BANK_NAME']."' data-name='".$row['BANK_NAME']."' >".$row['BANK_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	
	public function save_import_upload_clearing_bank()
	{

		$file_name = "";
		$time      = time();
		$encrypted = md5($time);

		if(isset($_FILES['fileToUpload'])){
			if($_FILES['fileToUpload']['name'] != ""){
				$rand      = substr(str_shuffle(str_repeat($encrypted, 8)), 0, 8);
				$path      = $_FILES['fileToUpload']['name'];
				$name      = explode(".", $path);
				$fix_name  = (strlen($name[0]) > 50 ) ? substr($name[0],0, 50) : $name[0];
				$ext       = pathinfo($path, PATHINFO_EXTENSION);
				$file_name = str_replace(" ", "", $fix_name)."_".$rand.".".$ext;
			}
		}

		$upl = $this->_upload('fileToUpload', $file_name, "temp");
		$postbank  = $this->input->post('ddlBank');


		if ($upl)
		{

			$source_file = './uploads/temp/' . $file_name;
			$read_file   = fopen($source_file,'r'); // open file
			$data        = array();

			// push file to data
			while ($line = fgets($read_file)) {
			  // echo $line  ."<br/>";
			   $data[] = $line; // push to array
			}
			fclose($read_file); // close file

			unlink($source_file); // delete file

			// echo_pre($data);


			foreach ($data as $value) 
			{
				$credit= 0;
				$debit= 0;

				$index1 = $index2 = $index3 = $index4 = $index5 = array();
				$vals   = preg_replace('!\s+!', ' ', $value);
				$exp    = explode(" ", trim($vals));

				$index1 = array_shift($exp);
				$index2 = array_shift($exp) . " " . array_shift($exp);
				$index5 = array_pop($exp);
				$index4 = array_pop($exp);
				$index3 = implode(" ", $exp);

				if($index1 != "" || $index2 != "")
				{

				$description = $index3;
		
				$norek = $index1;
				$kurs = substr($index2,0,3);
				$index2_length = strlen($index2);
				$transaction_date_txt = substr($index2,3,$index2_length);
				$transaction_date_txt_length = strlen($transaction_date_txt);

				$transaction_date_date = substr($transaction_date_txt,0,10);

				$transaction_date_hour = str_replace(".",":",substr($transaction_date_txt,10,$transaction_date_txt_length));

				$exp_transaction_date = explode("/", $transaction_date_date);

				$count = count($exp_transaction_date);

				if($count>2)
				{
					$transaction_date     = $exp_transaction_date[2]."-".$exp_transaction_date[1]."-".$exp_transaction_date[0].$transaction_date_hour;
				}
			
				$mutasi = $index4;

				$lengthmutasi = strlen($mutasi);

				if(strpos($mutasi, "CR") !== false)
				{
					$credit = substr($mutasi,0, $lengthmutasi-2);
				}
				else
				{
					$debit = substr($mutasi,0, $lengthmutasi-2);
				}

				$balance = $index5;

				$datas[] = array
				(
					'NO_REKENING'            => $norek,
					'TRANSACTION_DATE'		 => $transaction_date,
					'KURS'           		 => $kurs,
					'DEBIT'           		 => $debit,
					'CREDIT'           		 => $credit,
					'BALANCE'           	 => $balance,
					'BANK_NAME'           	 => $postbank,
					'DESCRIPTION'   		 => $description

				);

				$valuetrue = $this->clearing->insert_upload_clearing_bank_import($datas);

				}

				

			}

			if($valuetrue)
			{
				$procedure = $this->clearing->call_procedure(); 
				$result['status']   = true;
				$result['messages'] = "Data successfully imported";
			}else
			{
				$result['status']   = false;
				$result['messages'] = "Data failed imported";
			}

			$procedure = $this->clearing->call_procedure(); 
			$procedure_po = $this->clearing->call_procedure_po();
			
			echo json_encode($result);

		}

	}

	private function _upload($field_name, $file_name="", $folder=""){

        if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
        {
            @set_time_limit(300);
        }
        //file upload destination
        $config['upload_path']   = ($folder != "") ? './uploads/'.$folder : './uploads';
        //allowed file types. * means all types
        $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|jpg|jpeg|png|txt';
        //allowed max file size. 0 means unlimited file size
        $config['max_size'] = '0';
        //max file name size
        $config['max_filename'] = '355';
        //whether file name should be encrypted or not
        $config['encrypt_name'] = FALSE;
        $config['file_name'] = $file_name;
        //store image info once uploaded
        $image_data = array();
        //check for errors
        $is_file_error = FALSE;
        //check if file was selected for upload
        if (!$_FILES) {
            $is_file_error = TRUE;
        }
        //if file was selected then proceed to upload
        if (!$is_file_error) {

            /*if (file_exists(FCPATH.$upload_path.$folder_name."/".$file_name)){
                unlink($upload_path.$folder_name."/".$file_name);
            }*/
            //load the preferences
            $this->load->library('upload', $config);
            //check file successfully uploaded. 'image_name' is the name of the input
            if (!$this->upload->do_upload($field_name)) {
                //if file upload failed then catch the errors
                $is_file_error = TRUE;
            } else {
                //store the file info
                $image_data = $this->upload->data();

                if($image_data){
                    return true;
                }

            }
        }
        return false;
    }


	function download_data_upload_clearing_bank()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Transaction Date");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "No Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Debit");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Balance");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Status");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$bank = $this->input->get('bank');

		$hasil = $this->clearing->get_download_upload_clearing_bank($date_from,$date_to,$bank);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['post_date']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['TRANSACTION_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NO_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['KURS']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['DEBIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['BALANCE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['BANK_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['STATUS']);

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
		header('Content-Disposition: attachment; filename="Clearing Bank.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function load_ddl_batch_name()
	{
		$hasil	= $this->clearing->get_master_batch_name();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Batch Name --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['BATCH_NAME']."' data-name='".$row['BATCH_NAME']."' >".$row['BATCH_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	public function load_data_batch_payment(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$batchname = $this->input->post('batch_name');

		// echo "masuk"; die();
		// echo "ini adalah value".$batchname; die();
		
		$get_all = $this->clearing->get_batch_payment_datatable($batchname);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nmjurnal = '';
		$replacejurnal ='';

		if($total > 0){

			$rev_journal = array();

			$checkJournal = $this->crud->read_specific("JOURNAL_BATCH_PAYMENT", "NO_JOURNAL", array("BATCH_NAME" => $batchname));

			if($checkJournal){
				foreach($checkJournal as $value) {
					$no_journal    = $value['NO_JOURNAL'];
					if(strpos($no_journal, 'REV') !== false) {
						$rev_journal[] = str_replace("REV_", "", $no_journal);
					}
				}

				$rev_journal = array_unique($rev_journal);
			}

			foreach($data as $value) {

				$nmjurnal =  $value['NO_JOURNAL'];
				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal);

				if($value['BANK_CHARGES'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['BANK_CHARGES'].' checked disabled><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['BANK_CHARGES'].' disabled><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}


				$no_journal  = $value['NO_JOURNAL'];

				if(strpos($no_journal, 'REV') !== false) {
					$journal_reverse = '';
				}
				elseif(in_array($no_journal, $rev_journal)){
					$journal_reverse = '<a href="javascript:void(0)" title="Cannot to Reverse Journal '.$no_journal.'"><i class="fa fa-refresh text-muted" aria-hidden="true"></i></a>';
				}
				else{
					$journal_reverse = '<a href="javascript:void(0)" class="action-reverse" title="Reverse Journal '.$no_journal.'" data-id="'.$no_journal.'"><i class="fa fa-refresh text-success" aria-hidden="true"></i></a>';
				}

				$row[] = array(
						'no'                     => $number,
						'batch_date'             => date("d-m-Y",strtotime($value['BATCH_DATE'])),
						'batch_name'             => $value['BATCH_NAME'],
						'batch_number'           => $value['BATCH_NUMBER'],
						'journal_payment_number' => $value['JURNAL_PAYMENT_NUMBER'],
						'tanggal_invoice'        => date("d-m-Y",strtotime($value['TGL_INVOICE'])),
						'no_journal'             => $value['NO_JOURNAL'],
						'nama_vendor'            => $value['NAMA_VENDOR'],
						'no_invoice'             => $value['NO_INVOICE'],
						'no_kontrak'             => $value['NO_KONTRAK'],
						'description'            => $value['DESCRIPTION'],
						'dpp'                    => number_format($value['DPP'],0,'.',','),
						'no_fpjp'                => $value['NO_FPJP'],
						'nama_rekening'          => $value['NAMA_REKENING'],
						'nama_bank'              => $value['NAMA_BANK'],
						'acct_number'            => $value['ACCT_NUMBER'],
						'rkap_name'              => $value['RKAP_NAME'],
						'top'                    => $value['TOP'],
						'due_date'               => date("d-m-Y",strtotime($value['DUE_DATE'])),
						'nature'                 => $value['NATURE'],
						'status'                 => $value['STATUS'],
						'amount_to_payment'      => number_format($value['AMOUNT_TO_PAYMENT'],0,'.',','),
						'bank_charge'            => $checkbox,
						'journal_reverse'        => $journal_reverse
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


	function reverse_journal()
	{

		$no_journal = $this->input->post('no_journal');
		$status     = false;
		$messages   = "";

		if($this->crud->call_procedure("JOURNAL_REVERSE", $no_journal) !== -1){
			$status   = true;
			$messages = "Reverse journal success";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}


	public function download_data_batch_payment()
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Batch Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Invoice Date");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "No. Journal");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "No. Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "No Kontrak/PO/SPK/Nodin");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "No FPJP");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Amount to Payment");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Nama Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Acct Number");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Due Date");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Status");

		$batchname = $this->input->get('batch_name');

		$hasil = $this->clearing->get_download_batch_payment($batchname);

		$numrow    = 2;
		$number    = 1;

		foreach($hasil->result_array() as $row)	{

			$acctnumber = "";
			$acctnumber = $row['ACCT_NUMBER'];

			$stringaccnum = "'".$acctnumber;

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
		    $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, date("d-m-Y",strtotime($row['BATCH_DATE'])));
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d-m-Y",strtotime($row['TGL_INVOICE'])));
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['NO_FPJP']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['AMOUNT_TO_PAYMENT']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $stringaccnum);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, date("d-m-Y",strtotime($row['DUE_DATE'])));
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['STATUS']);

			$numrow++;
			$number++;

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
		header('Content-Disposition: attachment; filename="Payment Batch.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}



}



/* End of file Uploadclearingbank.php */

/* Location: ./application/controllers/Uploadclearingbank.php */