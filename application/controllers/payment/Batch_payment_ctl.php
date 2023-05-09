<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch_payment_ctl extends CI_Controller {

	private $module_name = "payment_batch",
			$module_url  = "payment-batch";

	protected $status_header;

	public function __construct()
	{
		
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->load->model('payment_mdl', 'payment');

		$this->status_header = 401;

	}

	public function index()
	{

		if($this->ion_auth->is_admin() == true || in_array("payment-batch", $this->session->userdata['menu_url']) ){

			$data['title']         = "Payment Batch";
			$data['module']        = "datatable";
			$data['template_page'] = $this->module_name."/payment_batch_inquiry";

			$groups = $this->session->userdata('group_id');

			foreach ($groups as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName["NAME"];
			}

			$usr_email = $this->session->userdata('email');

			$approval = false;
			$category = "";

			if($usr_email == "mutiah_hidayanty@linkaja.id" || $usr_email == "rizki_fitriana@linkaja.id"){
				$approval = true;
				$category = "approval1";
			}
			
			$data['approval']   = $approval;
			$data['category']   = $category;
			$data['group_name'] = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Payment Batch", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

    public function view_pb($batch_name){

		if($this->ion_auth->is_admin() == true || in_array("payment-batch", $this->session->userdata['menu_url']) ){

			$decrypt     = str_replace($this->config->item('encryption_key'), "", base64url_decode($batch_name));
			$batch_name  = $decrypt;
			$check_exist = $this->crud->check_exist("BATCH_PAYMENT", array("BATCH_NAME" => $batch_name));

			if($check_exist > 0){

				$get_batch = $this->payment->get_batch_all($batch_name);

				$data['title']          = "Batch ".$get_batch['BATCH_NAME'];
				$data['module']         = "datatable";
				$data['template_page']  = $this->module_name."/payment_batch_view";
				
				$data['batch_name']   = $get_batch['BATCH_NAME'];
				$data['batch_date']   = dateFormat($get_batch['BATCH_DATE'], 5, false);
				$data['batch_bank']   = (strtolower($get_batch['NAMA_BANK']) != "bni") ? "Others" : "BNI";
				$data['batch_amount'] = number_format($get_batch['TOTAL_AMOUNT'],0,',','.');

				$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
				$breadcrumb[] = array( "name" => "Payment Batch", "link" => base_url($this->module_url), "class" => "" );
				$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
				$data['breadcrumb']    = $breadcrumb;

				$this->template->load('main', $data['template_page'], $data);

			}
			else{
				$this->session->set_flashdata('messages', 'Batch Not Exist');
				redirect($this->module_url);
			}

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_batch_payment_inquiry(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("payment-batch", $this->session->userdata['menu_url']) ){

			$result['data']            = "";
			$result['draw']            = "";
			$result['recordsTotal']    = 0;
			$result['recordsFiltered'] = 0;

			$date_from = "";
			$date_to   = "";
			$this->status_header = 200;

			if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
				$exp_date_from = explode("-", $this->input->post('date_from'));
				$exp_date_to   = explode("-", $this->input->post('date_to'));

				$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
				$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
			}

			$get_all         = $this->payment->get_batch_inquiry($date_from, $date_to);
			$data            = $get_all['data'];
			$total           = $get_all['total_data'];
			$start           = $this->input->post('start');
			$number          = $start+1;

			$usr_email = $this->session->userdata('email');
			
			$display_opt  = true;
			$disable_opt1 = "";
			$disable_opt2 = "";
			$show_opt1    = "";
			$show_opt2    = "";

			if($usr_email == "mutiah_hidayanty@linkaja.id" || $usr_email == "rizki_fitriana@linkaja.id"){
				$disable_opt2 = " disabled";
				$show_opt2    = " d-none";
			}
			else{
				$display_opt = false;
			}

			$opt_approval = array("--Choose--", "Approved", "Rejected");

			$groups = $this->session->userdata('group_id');

			foreach ($groups as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName["NAME"];
			}

			$hide_action = (in_array("Payment Batch Inquiry", $group_name)) ? true : false;

			if($total > 0){

				foreach($data as $value) {

					$checked = "";
					$batch_name = $value['BATCH_NAME'];
					$batch_encrypt = base64url_encode($batch_name.$this->config->item('encryption_key'));
					if($value['PAID_STATUS'] == "Y"){
						$checked = " checked";
					}
					$paid_status = '<div class="checkbox checkbox-danger m-0"><input id="paid_status-'.$batch_name.'" class="paid_status checkbox" type="checkbox" name="checkbox-data"'.$checked.'><label class="m-0 p-0" for="paid_status-'.$batch_name.'"></label></div>';

					$approval      = "";
					$opt_1         = "";
					$opt_2         = "";
					$label_approve = "Wait for approval";
					$valApprove1 = $value['APPROVAL1'];
					$disable_opt1 = "";

					for ($i=0; $i < count($opt_approval) ; $i++) {
						$selected = ($valApprove1 == $i) ? " selected" : "";

						if($valApprove1 == 1){
							$disable_opt2  = "";
							$label_approve = "Approved";
						}
						elseif($valApprove1 == 2){
							$disable_opt2  = " disabled";
							$disable_opt1  = " disabled";
							$label_approve = "Rejected";
						}
						else{
							$disable_opt2 = " disabled";
						}

						$opt_1 .= '<option value="'.$i.'"'.$selected.'>'.$opt_approval[$i].'</option>';
					}

					$approval     .= '<div class="form-group m-b-0 px-5 d-inline-block'.$show_opt1.'"><select id="approval1-'.$number.'" class="form-control input-sm approval1 select-center"'.$disable_opt1.'>'.$opt_1.'</select></div>';
					$class_act2 = "";

					if($hide_action == false){
						$class_act = "";
						if($valApprove1 == 1){
							$class_act = " d-none";
						}

						if($valApprove1 == 2){
							$class_act2 = " d-none";
						}
					}else{
						$class_act = " d-none";
					}

					$action = '<a href="javascript:void(0)" class="action-view px-5'.$class_act2.'" title="Click to view Batch" data-id="' .$batch_encrypt. '"><i class="fa fa-search text-success" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-delete px-5'.$class_act.'" title="Click to delete"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-cetak px-5'.$class_act2.'" title="Click to print Csv File" data-id="' .$batch_encrypt. '"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>';

					$row[] = array(
							'no'            => $number,
							'batch_name'    => $batch_name,
							'bank_name'     => $value['NAMA_BANK'],
							'bank_charges'  => $value['BANK_CHARGES'],
							'bi_rate'       => $value['BI_RATE'],
							'dpp'           => $value['DPP'],
							'batch_encrypt' => $batch_encrypt,
							'batch_date'    => dateFormat($value['BATCH_DATE'], 5, false),
							'total_amount'  => number_format($value['TOTAL_AMOUNT'],0,',','.'),
							'paid_status'   => $valApprove1,
							'action'        => $action,
							'approval'      => ($display_opt) ? $approval : $label_approve
							);
					$number++;

				}

				$result['data']            = $row;
				$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
				$result['recordsTotal']    = $total;
				$result['recordsFiltered'] = $total;

			}

		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}

	public function load_batch_payment_view(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$batch_name = $this->input->post('batch_name');
		$date_from  = "";
		$date_to    = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all         = $this->payment->get_batch_all_journal($batch_name);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
						'no'           => $number,
						'no_journal'   => $value['NO_JOURNAL'],
						'nama_vendor'  => $value['NAMA_VENDOR'],
						'nama_bank'    => $value['NAMA_BANK'],
						'no_kontrak'   => $value['NO_KONTRAK'],
						'tgl_invoice'  => dateFormat($value['TGL_INVOICE'], 5, false),
						'total_amount' => number_format($value['TOTAL_AMOUNT'],0,',','.')
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

	public function donwload_data_batch_payment_view(){


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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "No Journal");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "No Kontrak");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Tanggal Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Total Amount");

		$batch_name = $this->input->get('nama_batch');
		$hasil = $this->payment->get_download_data_batch_payment_view($batch_name);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['post_date']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $batch_name);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, dateFormat($row['TGL_INVOICE'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['TOTAL_AMOUNT']);

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
		header('Content-Disposition: attachment; filename="Batch Payment.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function load_data_journal_payment(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$batch_name = $this->input->post('batch_name');
		$date_from  = "";
		$date_to    = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all         = $this->payment->get_journal_payment($batch_name);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			$rev_journal = array();

			foreach($data as $value) {
				$no_journal    = $value['NO_JOURNAL'];
				if(strpos($no_journal, 'REV') !== false) {
					$rev_journal[] = str_replace("REV_", "", $no_journal);
				}
			}

			$rev_journal = array_unique($rev_journal);

			foreach($data as $value) {

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
						'no'                  => $number,
						'no_journal'          => $no_journal,
						'account_description' => $value['ACCOUNT_DESCRIPTION'],
						'nature'              => $value['NATURE'],
						'journal_description' => $value['JOURNAL_DESCRIPTION'],
						'invoice_desc'        => $value['INVOICE_DESCRIPTION'],
						'status'              => $value['STATUS'],
						'gl_date'             => dateFormat($value['GL_DATE'], 5, false),
						'amount_debet'        => number_format($value['DEBET'],0,',','.'),
						'amount_credit'       => number_format($value['CREDIT'],0,',','.'),
						'journal_reverse'     => $journal_reverse
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

	public function donwload_data_journal_payment(){

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

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Acounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Debet USD");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Credit USD");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Kurs");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Batch Description");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Nama Vendor");		
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Valas");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Rate");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Status");

		$batch_name = $this->input->get('nama_batch');
		$hasil = $this->payment->get_download_data_journal_payment($batch_name);

		$numrow    = 2;
		$number    = 1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, dateFormat($row['GL_DATE'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $batch_name);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DEBET_USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CREDIT_USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['KURS']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['BATCH_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['VALAS']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['RATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['STATUS']);

			// $excel->getActiveSheet()->getStyle('F'.$numrow.':J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

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
		header('Content-Disposition: attachment; filename="Journal Payment.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function load_data_journal(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$gl_date_from = "";
		$gl_date_to   = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$get_all = $this->payment->get_journal_datatable($gl_date_from, $gl_date_to);
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
					'no_journal'           => $value['NO_JOURNAL'],
					'nature'   		 	 	 => $value['NATURE'],
					'debit'   				 => number_format($value['DEBET'],0,'.',','),
					'credit'   		 		 => number_format($value['CREDIT'],0,'.',','),
					'account_description'    => $value['ACCOUNT_DESCRIPTION'],
					'journal_description'    => $value['JOURNAL_DESCRIPTION']
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

	public function download_batch_payment_inquiry()
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "No. Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nama Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Acct Number");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Due Date");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "STATUS");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$hasil = $this->payment->get_download_batch_payment_inquiry($date_from,$date_to);

		$numrow    = 2;
		$number = 1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$acctnumber = "";
			$acctnumber = $row['ACCT_NUMBER'];
			$stringaccnum = "'".$acctnumber;

			$duedate = date("d-M-Y", strtotime($row['DUE_DATE']));
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $stringaccnum);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $duedate);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['STATUS']);


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
		header('Content-Disposition: attachment; filename="Batch Payment.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function download_data_journal()
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Acounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Debet USD");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Credit USD");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Kurs");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Batch Description");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Nama Vendor");		
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Valas");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Rate");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Status");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');

		$hasil = $this->payment->get_download_journal($date_from,$date_to);

		$numrow = 2;
		$number = 1;

		foreach($hasil->result_array() as $row)	{
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $number);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, dateFormat($row['GL_DATE'], 5, false));
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DEBET_USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['CREDIT_USD']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['KURS']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['CREDIT']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['BATCH_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['VALAS']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['RATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['STATUS']);

			$numrow++;
			$number++;

			// $excel->getActiveSheet()->getStyle('F'.$numrow.':J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');


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
		header('Content-Disposition: attachment; filename="Journal.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function new_pb(){

		$data['title']         = "Create Payment Group";
		$data['module']        = "datatable";
		$data['template_page'] = $this->module_name."/payment_batch_create";

		$batch_query = get_shorted_user($this->session->userdata('user_id')).date("d/")."PY/".date("my");

		$latest_batch = $this->payment->get_last_batch_name($batch_query);

		$startBatch = 1;
		$startJournal = 1;

		if($latest_batch):
			$batch_sequence = ltrim($latest_batch['batch_sequence'], '0');
			$journal        = ltrim($latest_batch['JOURNAL'], '0');
			$startBatch     = $batch_sequence+1;
			$startJournal   = $journal+1;
		endif;

		$batch_name = "B-".sprintf("%'02d", $startBatch)."/".$batch_query;

		/*$get_last_journal = $this->payment->get_last_journal($batch_query);

		if($get_last_journal):
			$journal = ltrim($get_last_journal['JOURNAL'], '0');
			$startJournal    = $journal+1;
		endif;*/

		$data['no_journal'] = $startJournal;
		$data['batch_name'] = $batch_name;

		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => "Payment Batch", "link" => base_url($this->module_url), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}

	public function load_data_pb(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$bank_name    = $this->input->post('bank_name');
		$amount       = $this->input->post('amount');
		$amount_group = $this->input->post('amount_group');
		$date_from    = "";
		$date_to      = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$amount_group = ($amount_group == 1) ? true : false;

		$get_all = $this->payment->get_gl_header($date_from, $date_to, $amount, $bank_name, $amount_group);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$batch_name = get_shorted_user($this->session->userdata('user_id')).date("d/")."PY/".date("my");		

		if($total > 0){

			foreach($data as $value) {

				$random_key = "k".strtolower(generateRandomString(4));

				$is_checklist = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$number.'" class="checkbox-data checkbox" type="checkbox" name="checkbox-data"><label class="m-0 p-0" for="checkbox-'.$number.'"></label></div>';

				$journal = '<span id="journal_name-'.$number.'""></span>';

				$is_group  = ($value['GROUP_VENDOR'] > 0) ? 0 : 1;
				$is_groupx = ($is_group) ? true : false;

				$bank_bni = "not";

				if (strpos(strtolower($value['NAMA_BANK']), 'bni') !== false) {
					$bank_bni = "bni";
				}

				$get_all = $this->payment->get_gl_header_gl_id($date_from, $date_to, $value['NAMA_VENDOR'], $bank_bni, $value['GL_HEADER_ID'], $is_groupx, $amount_group, $amount);
				$dt = array();

				foreach($get_all->result_array() as $val) {
					$gl_id = $val['GL_HEADER_ID'];
					$dt[] = array( 'gl_id' => $gl_id, );
				}

				$row[] = array(
						'no'           => $number,
						'id'           => $value['GL_HEADER_ID'],
						'is_group'     => $is_group,
						'random_key'   => $random_key,
						'no_journal'   => $journal,
						'nama_vendor'  => $value['NAMA_VENDOR'],
						'batch_name'   => $batch_name,
						'nama_bank'    => $value['NAMA_BANK'],
						'no_kontrak'   => $value['NO_KONTRAK'],
						'ar_invoice'   => $value['AR_INVOICE'],
						'tgl_invoice'  => dateFormat($value['TGL_INVOICE'], 5, false),
						'total_amount' => $value['TOTAL_AMOUNT'],
						'is_checklist' => $is_checklist,
						'gl_data'      => $dt,
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

	public function load_data_pb_detail(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;
		
		$nama_vendor  = $this->input->post('nama_vendor');
		$bank_name    = $this->input->post('bank_name');
		$no_journal   = $this->input->post('no_journal');
		$gl_id        = $this->input->post('gl_id');
		$amount_group = $this->input->post('amount_group');
		$amount       = $this->input->post('amount');
		$is_group     = ($this->input->post('is_group') == 1) ? true : false;

		$date_from   = "";
		$date_to     = "";

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}
		$amount_group = ($amount_group == 1) ? true : false;
		
		$get_all = $this->payment->get_gl_header_detail($date_from, $date_to, $nama_vendor, $bank_name, $gl_id, $is_group, $amount_group, $amount);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		$get_journal_ar = $this->payment->get_journal_ar($nama_vendor);

		$netting     = false;

		if(count($get_journal_ar) > 0){

			$netting = true;

			$netting_opt = '<option value="0" data-debit="" data-description="">--Choose--</option>';
			foreach ($get_journal_ar as $value) {

				$invoice     = $value['REFERENCE_2'];
				$debit       = number_format($value['DEBIT'],0,',','.');
				$description = $value['JOURNAL_DESCRIPTION'];

				$netting_opt .= '<option value="'.$invoice.'" data-debit="'.$debit.'" data-description="'.$description.'">'.$invoice.'</option>'."\r\n";
			}

		}
		

		if($total > 0){

			foreach($data as $value) {

				$gl_id = $value['GL_HEADER_ID'];

				$checked  = ($value['BANK_CHARGES'] == "Y") ? " checked" : "";
				$checked2 = ($value['AR_NETTING'] == "Y") ? " checked" : "";
				$checked3 = ($value['IS_GROUP'] == "1") ? " checked" : "";

				$bank_charge = '<div class="checkbox checkbox-danger m-0"><input id="bank_charge-'.$gl_id.'" class="bank_charge checkbox" type="checkbox" name="checkbox-data"'.$checked.'><label class="m-0 p-0" for="bank_charge-'.$gl_id.'"></label></div>';

				/*$ar_netting = '<div class="checkbox checkbox-danger m-0"><input id="ar_netting-'.$gl_id.'" class="ar_netting checkbox" type="checkbox" name="checkbox-data"'.$checked2.'><label class="m-0 p-0" for="ar_netting-'.$gl_id.'"></label></div>';*/

				$is_group = '<div class="checkbox checkbox-danger m-0"><input id="is_group-'.$gl_id.'" class="is_group checkbox" type="checkbox" name="checkbox-data"'.$checked3.'><label class="m-0 p-0" for="is_group-'.$gl_id.'"></label></div>';

				$ar_invoice = "";

				if($netting){
					$ar_invoice = '<div class="form-group m-b-0 px-5 d-inline-block"><select id="ar_invoice-'.$gl_id.'" class="form-control input-sm ar_invoice select-center">'.$netting_opt.'</select></div>';
				}

				$ar_debit       = '<span id="ar_debit-'.$gl_id.'"></span>';
				$ar_description = '<span id="ar_description-'.$gl_id.'"></span>';

				$bi_rate_val  = number_format($value['BI_RATE'],0,',','.');

				$bi_rate       = '<div class="form-group m-b-0"><input id="bi_rate-'.$gl_id.'" class="form-control input-sm bi_rate text-right money-format" type="text"  value="'.$bi_rate_val.'"></div>';

				$row[] = array(
						'no'             => $number,
						'id'             => $gl_id,
						'no_journal'     => $no_journal,
						'nama_vendor'    => $value['NAMA_VENDOR'],
						'currency'       => $value['CURRENCY'],
						'batch_name'     => $value['BATCH_NAME'],
						'no_invoice'     => $value['NO_INVOICE'],
						'no_kontrak'     => $value['NO_KONTRAK'],
						'tgl_invoice'    => dateFormat($value['TGL_INVOICE'], 5, false),
						'amount'         => number_format($value['DPP'],0,',','.'),
						'bank_charge'    => $bank_charge,
						'bi_rate'        => $bi_rate,
						'ar_invoice'     => $ar_invoice,
						'ar_debit'       => $ar_debit,
						'ar_description' => $ar_description,
						'is_group'       => $is_group
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

	public function load_batch_detail(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$batch_name  = $this->input->post('batch_name');
		$no_journal  = $this->input->post('no_journal');

		$get_all = $this->payment->get_batch_detai($batch_name, $no_journal);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		$get_all_bank = get_all_bank();
		$optBank = "";
		foreach ($get_all_bank as $key => $value) {
			$optBank .= '<option value="'.$value['BANK_NAME'].'">'.$value['BANK_NAME'].'</option>'."\r\n";
		}

		if($total > 0){

			foreach($data as $value) {

				if($value['BANK_CHARGES'] == "Y"){
					$checked = " checked";
				}
				if($value['BANK_CHARGES'] == "Y"){
					$bank_charge = 'Y';
				}
				else{
					$bank_charge = 'N';
				}

				$bank_name     = '<div class="form-group m-b-0"><select id="bank_name-'.$number.'" class="form-control input-sm bank_name select2">'.$optBank.'</select></div>';
				$nama_rekening = '<div class="form-group m-b-0"><input id="nama_rekening-'.$number.'" data-id="'.$number.'" class="form-control input-sm nama_rekening" value="'.$value['NAMA_REKENING'].'"></div>';
				$acct_number   = '<div class="form-group m-b-0"><input id="acct_number-'.$number.'" data-id="'.$number.'" class="form-control input-sm acct_number" value="'.$value['ACCT_NUMBER'].'"></div>';

				$row[] = array(
						'no'            => $number,
						'no_journal'    => $no_journal,
						'gl_header_id'  => $value['GL_HEADER_ID'],
						'nama_vendor'   => $value['NAMA_VENDOR'],
						'batch_name'    => $value['BATCH_NAME'],
						'no_invoice'    => $value['NO_INVOICE'],
						'nama_bank'     => $value['NAMA_BANK'],
						'invoice_desc'  => $value['DESCRIPTION'],
						'bank_name'     => $bank_name,
						'nama_rekening' => $nama_rekening,
						'acct_number'   => $acct_number,
						'tgl_invoice'   => dateFormat($value['TGL_INVOICE'], 5, false),
						'amount'        => number_format($value['DPP'],0,',','.'),
						'bank_charge'   => $bank_charge,
						'bi_rate'       => $value['BI_RATE']
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

	public function load_data_pb_detail_gl_id(){

		$nama_vendor  = $this->input->post('nama_vendor');
		$bank_name    = $this->input->post('bank_name');
		$amount       = $this->input->post('amount');
		$amount_group = $this->input->post('amount_group');
		$gl_id        = $this->input->post('glx_id');
		$is_group     = ($this->input->post('is_group') == 1) ? true : false;
		$date_from    = "";
		$date_to      = "";

		// echo_pre($_POST);

		if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
			$exp_date_from = explode("-", $this->input->post('date_from'));
			$exp_date_to   = explode("-", $this->input->post('date_to'));

			$date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}
		$amount_group = ($amount_group == 1) ? true : false;
		
		$get_all = $this->payment->get_gl_header_gl_id($date_from, $date_to, $nama_vendor, $bank_name, $gl_id, $is_group, $amount_group, $amount);
		$row = array();
		if($get_all){

			foreach($get_all->result_array() as $value) {

				$gl_id = $value['GL_HEADER_ID'];

				$row[] = array(
								'gl_id' => $gl_id,
							);

			}
		}

		echo json_encode($row);
	}


	function update_bank_charges()
	{

		$id           = $this->input->post('id');
		$bank_charges = $this->input->post('bank_charges');

		$status       = false;
		$messages     = "";

		$data = array(
						'BANK_CHARGES' => $bank_charges,
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("GL_HEADER_ID" => $id));

		if($update !== -1){
			$status = true;
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

	function update_bi_rate()
	{

		$id      = $this->input->post('id');
		$bi_rate = $this->input->post('bi_rate');

		$status       = false;
		$messages     = "";

		$data = array(
						'BI_RATE' => $bi_rate,
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("GL_HEADER_ID" => $id));

		if($update !== -1){
			$status = true;
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

	function update_ar_netting()
	{

		$id             = $this->input->post('id');
		$ar_netting     = $this->input->post('ar_netting');
		$ar_invoice     = $this->input->post('ar_invoice');
		$ap_invoice     = $this->input->post('ap_invoice');
		$ar_debit       = $this->input->post('ar_debit');
		$ar_description = $this->input->post('ar_description');

		$status   = false;
		$messages = "";

		$data = array(
						'AR_NETTING'     => $ar_netting,
						'AR_INVOICE'     => $ar_invoice,
						'AR_DEBIT'       => $ar_debit,
						'AR_DESCRIPTION' => $ar_description
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("GL_HEADER_ID" => $id));

		if($update !== -1){

			if($ar_netting == "Y"){

				$get_invoice_ar = $this->crud->read_specific("JOURNAL_AR", "STATUS", array("REFERENCE_2" => $ar_invoice));

				$new_ap_invoice = $ap_invoice;
				if(count($get_invoice_ar) > 0 ){
					if($get_invoice_ar[0]['STATUS'] != NULL || $get_invoice_ar[0]['STATUS'] != "NULL"){
						$new_ap_invoice = $get_invoice_ar[0]['STATUS'].", ".$ap_invoice;
					}
				}

				$this->crud->update("JOURNAL_AR", array("STATUS" => $new_ap_invoice), array("REFERENCE_2" => $ar_invoice));
			}

			
			$status = true;
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

	function update_group_journal()
	{

		$id       = $this->input->post('id');
		$is_group = $this->input->post('is_group');

		$status       = false;
		$messages     = "";

		$data = array(
						'IS_GROUP' => $is_group,
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("GL_HEADER_ID" => $id));

		if($update !== -1){
			$status = true;
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

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


	public function save_batch_payment2(){

		$data_lines = $this->input->post('data_lines');
		foreach ($data_lines as $key => $value) {

			$no_journal = $value['no_journal'];
			$batch_name = $value['batch_name'];
			$data_gl    = explode(",", $value['data_gl']);

			foreach ($data_gl as $key => $gl) {

				$get_data_gl = $this->payment->get_gl_by_gl_id($gl);

				$dataUpdGl[] = array(
										"GL_HEADER_ID"   => $gl,
										"PAYMENT_STATUS" => "UNRECONCILED"
									);

				$data[] = array(
							"GL_HEADER_ID"   => $gl,
							"TGL_INVOICE"    => $get_data_gl['TGL_INVOICE'],
							"BATCH_NAME"     => $batch_name,
							"NO_JOURNAL"     => $no_journal,
							"NAMA_VENDOR"    => $get_data_gl['NAMA_VENDOR'],
							"NO_INVOICE"     => $get_data_gl['NO_INVOICE'],
							"NO_KONTRAK"     => $get_data_gl['NO_KONTRAK'],
							"DESCRIPTION"    => $get_data_gl['DESCRIPTION'],
							"DPP"            => $get_data_gl['DPP'],
							"NO_FPJP"        => $get_data_gl['NO_FPJP'],
							"NAMA_REKENING"  => $get_data_gl['NAMA_REKENING'],
							"NAMA_BANK"      => $get_data_gl['NAMA_BANK'],
							"BANK_CHARGES"   => $get_data_gl['BANK_CHARGES'],
							"BI_RATE"        => $get_data_gl['BI_RATE'],
							"ACCT_NUMBER"    => $get_data_gl['ACCT_NUMBER'],
							"RKAP_NAME"      => $get_data_gl['RKAP_NAME'],
							"TOP"            => $get_data_gl['TOP'],
							"DUE_DATE"       => $get_data_gl['DUE_DATE'],
							"NATURE"         => $get_data_gl['NATURE'],
							"AR_NETTING"     => $get_data_gl['AR_NETTING'],
							"AR_DEBIT"       => $get_data_gl['AR_DEBIT'],
							"AR_INVOICE"     => $get_data_gl['AR_INVOICE'],
							"AR_DESCRIPTION" => $get_data_gl['AR_DESCRIPTION']
						);
			}

		}

		$insert   = $this->crud->create_batch("BATCH_PAYMENT", $data);

		$status   = false;
		$messages = "";

		if($insert > 0 && $this->crud->update_batch_data("GL_HEADERS", $dataUpdGl, "GL_HEADER_ID") !== -1 && $this->crud->call_procedure("JURNAL_PAYMENT") !== -1){
			$status = true;
		}
		else{
			$messages = "Failed to Create Payment Batch";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);

	}


	public function save_batch_payment(){

		$data_lines      = $this->input->post('data_lines');
		$ar_netting_data = $this->input->post('ar_netting_data');
		
		$data_netting    = array();
		$data_inv_ap     = array();
		$journal_ar_upd  = array();

		if($ar_netting_data){
			foreach ($ar_netting_data as $key => $value) {
				$id = $value['id'];

				$ar_inv = base64url_encode($value['ar_invoice'].$this->config->item('encryption_key'));

				$data_netting[$id] = array(
											"AR_NETTING"     => $value['ar_netting'],
											"AR_DEBIT"       => $value['ar_debit'],
											"AR_INVOICE"     => $value['ar_invoice'],
											"AR_DESCRIPTION" => $value['ar_description']
										);

				$data_inv_ap[$ar_inv][] = $value['ap_invoice'];
			}
		}

		$i = 0;
		foreach ($data_lines as $key => $value) {

			$no_journal = $value['no_journal'];
			$batch_name = $value['batch_name'];
			$data_gl    = explode(",", $value['data_gl']);

			$last_ar_inv = "";
			foreach ($data_gl as $k => $gl) {

				$get_data_gl = $this->payment->get_gl_by_gl_id($gl);

				$dataUpdGl[] = array(
										"GL_HEADER_ID"   => $gl,
										"PAYMENT_STATUS" => "UNRECONCILED"
									);

				$data[$i] = array(
							"GL_HEADER_ID"  => $gl,
							"TGL_INVOICE"   => $get_data_gl['TGL_INVOICE'],
							"BATCH_NAME"    => $batch_name,
							"NO_JOURNAL"    => $no_journal,
							"NAMA_VENDOR"   => $get_data_gl['NAMA_VENDOR'],
							"NO_INVOICE"    => $get_data_gl['NO_INVOICE'],
							"NO_KONTRAK"    => $get_data_gl['NO_KONTRAK'],
							"DESCRIPTION"   => $get_data_gl['DESCRIPTION'],
							"DPP"           => $get_data_gl['DPP'],
							"NO_FPJP"       => $get_data_gl['NO_FPJP'],
							"NAMA_REKENING" => $get_data_gl['NAMA_REKENING'],
							"NAMA_BANK"     => $get_data_gl['NAMA_BANK'],
							"BANK_CHARGES"  => $get_data_gl['BANK_CHARGES'],
							"BI_RATE"       => $get_data_gl['BI_RATE'],
							"ACCT_NUMBER"   => $get_data_gl['ACCT_NUMBER'],
							"RKAP_NAME"     => $get_data_gl['RKAP_NAME'],
							"TOP"           => $get_data_gl['TOP'],
							"DUE_DATE"      => $get_data_gl['DUE_DATE'],
							"NATURE"        => $get_data_gl['NATURE']
						);

				if(array_key_exists($gl, $data_netting)){

					$get_ar_inv = $data_netting[$gl]['AR_INVOICE'];
					$ar_inv_enc = base64url_encode($get_ar_inv.$this->config->item('encryption_key'));

					if($get_ar_inv != $last_ar_inv){
						$journal_ar_upd[] = array("REFERENCE_2" => $get_ar_inv, "STATUS" => implode(", ", $data_inv_ap[$ar_inv_enc]));
					}

					$data[$i]['AR_NETTING']     = $data_netting[$gl]['AR_INVOICE'];
					$data[$i]['AR_DEBIT']       = $data_netting[$gl]['AR_DEBIT'];
					$data[$i]['AR_INVOICE']     = $data_netting[$gl]['AR_INVOICE'];
					$data[$i]['AR_DESCRIPTION'] = $data_netting[$gl]['AR_DESCRIPTION'];

					$last_ar_inv = $get_ar_inv;

				}
				else{

					$data[$i]['AR_NETTING']     = $get_data_gl['AR_NETTING'];
					$data[$i]['AR_DEBIT']       = $get_data_gl['AR_DEBIT'];
					$data[$i]['AR_INVOICE']     = $get_data_gl['AR_INVOICE'];
					$data[$i]['AR_DESCRIPTION'] = $get_data_gl['AR_DESCRIPTION'];

				}
				$i++;
			}

		}

		$insert   = $this->crud->create_batch("BATCH_PAYMENT", $data);

		$status   = false;
		$messages = "";
		$messages = "Failed to Create Payment Batch";

		if($insert > 0 && $this->crud->update_batch_data("GL_HEADERS", $dataUpdGl, "GL_HEADER_ID") !== -1){

			if(count($journal_ar_upd) > 0){
				if($this->crud->update_batch_data("JOURNAL_AR", $journal_ar_upd, "REFERENCE_2") > 0){
					if($this->crud->call_procedure("JURNAL_PAYMENT") !== -1){
						$status   = true;
						$messages = "";
					}
				}
			}else{
				if($this->crud->call_procedure("JURNAL_PAYMENT") !== -1){
					$status   = true;
					$messages = "";
				}
			}
		}
		

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);

	}

	public function save_gl_header(){

		$data_gl = $this->input->post('data_gl');

		foreach ($data_gl as $key => $value) {

			$data[] = array(
						"GL_HEADER_ID"  => $value['gl_id'],
						"NAMA_BANK"     => $value['nama_bank'],
						"NAMA_REKENING" => $value['nama_rekening'],
						"ACCT_NUMBER"   => $value['acct_number']
					);

		}

		$status   = false;
		$messages = "";

		if($this->crud->update_batch_data("GL_HEADERS", $data, "GL_HEADER_ID") !== -1){
			$status = true;
		}
		else{
			$messages = "Failed to Create Payment Batch";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;

		echo json_encode($result);

	}

	function export_csv() {
        $this->load->helper('csv_helper');
        $batch_name = $this->input->get('batch_name');
        $category   = $this->input->get('category');
        $decrypt    = str_replace($this->config->item('encryption_key'), "", base64url_decode($batch_name));
		$batch_name = $decrypt;
		$export_arr = array();
        $data       = $this->payment->get_csv($batch_name, $category);
        $dpp_total  = $this->payment->get_dpp($batch_name, $category);
        $data_csv   = $data['data'];
        $total 		= $data['total_data'];

        $total_row = 2 + $total;
        $tgl = date("Ymd");

		foreach($dpp_total->result_array() as $value){
			$total_dpp = $value['DPP'];
		}

		if($category == '1'){
			$title = array(date("Y/m/d_h:i:s"),
					   $total_row,
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "");
			$ttl   = array("P",
						   date("Ymd"),
						   "2019888819",
						   $total,
						   $total_dpp,);
	        array_push($export_arr, $title);
	        array_push($export_arr, $ttl);
	        // array_push($export_arr);
	        if (!empty($data_csv)) {         
				foreach($data_csv as $row)	{
					$invoice = strlen($row['NO_INVOICE']);
					$inv30   = substr($row['NO_INVOICE'],0,30);
					$inv     = "";

					if($invoice > 30){
						$inv = substr($row['NO_INVOICE'],30,$invoice-30);
					}

					array_push($export_arr,
						array(
							$row['ACCT_NUMBER'],
							$row['NAMA_REKENING'],
							$row['DPP'],
							$inv30,
							$inv,
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"N",
							"",
							$row['NO_JOURNAL'],
							"Y"
						));
				}
	        }
		}elseif($category == '2'){
			$title = array(date("Y/m/d_h:i:s"),
					   $total_row,
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "");
			$ttl   = array("P",
						   date("Ymd"),
						   "2019888819",
						   $total,
						   $total_dpp);
	        array_push($export_arr, $title);
	        array_push($export_arr, $ttl);
	        // array_push($export_arr);
	        if (!empty($data_csv)) {         
				foreach($data_csv as $row)	{
					$invoice = strlen($row['NO_INVOICE']);
					$inv30   = substr($row['NO_INVOICE'],0,30);
					$inv     = "";

					if($invoice > 30){
						$inv = substr($row['NO_INVOICE'],30,$invoice-30);
					}

					array_push($export_arr,
						array(
							$row['ACCT_NUMBER'],
							$row['NAMA_REKENING'],
							$row['DPP'],
							$inv30,
							$inv,
							"",
							$row['BIC_RTGS_CODE'],
							$row['NAMA_BANK'],
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"N",
							"",
							$row['NO_JOURNAL'],
							"Y"
						));
				}
	        }
		}else{
			$title = array(date("Y/m/d_h:i:s"),
					   $total_row,
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "",
					   "");
			$ttl   = array("P",
						   date("Ymd"),
						   "2019888819",
						   $total,
						   $total_dpp);
	        array_push($export_arr, $title);
	        array_push($export_arr, $ttl);
	        // array_push($export_arr);
	        if (!empty($data_csv)) {         
				foreach($data_csv as $row)	{
					$invoice = strlen($row['NO_INVOICE']);
					$inv30   = substr($row['NO_INVOICE'],0,30);
					$inv     = "";

					if($invoice > 30){
						$inv = substr($row['NO_INVOICE'],30,$invoice-30);
					}

					array_push($export_arr,
						array(
							$row['ACCT_NUMBER'],
							$row['NAMA_REKENING'],
							$row['DPP'],
							$inv30,
							$inv,
							"",
							$row['DOMESTIC_BANK_CODE'],
							$row['NAMA_BANK'],
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"",
							"N",
							"",
							$row['NO_JOURNAL'],
							"Y"
						));
				}
	        }
		}

       if($category == 1){
       		convert_to_csv($export_arr, 'Upload_IH_'.$tgl.'.csv', ',');
       	}elseif($category == 2){
       		convert_to_csv($export_arr, 'Upload_RTGS_'.$tgl.'.csv', ',');
       	}else{
       		convert_to_csv($export_arr, 'Upload_LLG_'.$tgl.'.csv', ',');
       	}
    }

    function update_paid_status()
	{

		$batch_name  = $this->input->post('batch_name');
		$paid_status = $this->input->post('paid_status');

		$status       = false;
		$messages     = "";

		$data = array(
						'PAID_STATUS' => $paid_status,
					);

		$update = $this->crud->update("BATCH_PAYMENT", $data, array("BATCH_NAME" => $batch_name));

		if($update !== -1){
			$status = true;
			$messages = "Paid Status changed";
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

    function save_approval()
	{

		$category   = $this->input->post('category');
		$data_lines = $this->input->post('data_lines');

		$status       = false;
		$messages     = "";

		if($category == "approval1"){
			$key_approval = "approval1";
		}else{
			$key_approval = "approval2";
		}

		$batch_nameArr = array();
		$updated_by = get_user_data($this->session->userdata('user_id'));

		foreach ($data_lines as $key => $value) {

			$approval   = $value[$key_approval];
			$batch_name = $value['batch_name'];

			$data[] = array(
							"BATCH_NAME"              => $batch_name,
							"UPDATED_BY"              => $updated_by,
							strtoupper($key_approval) => $approval
						);

			if($approval == 2):
				$batch_nameArr[] = $batch_name;
			endif;

		}

		$update = $this->crud->update_batch_data("BATCH_PAYMENT", $data, "BATCH_NAME");

		if($update !== -1){

			$messages = "Approval Status changed";
			$status   = true;

			if(count($batch_nameArr) > 0){
				if($this->payment->delete_journal_by_batch($batch_nameArr) <= 0 && $this->payment->update_gl_status($batch_nameArr) <= 0){
					$status = false;
					$messages = "Failed to delete journal";
				}
			}
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}


	public function delete_batch(){

		$batch_name = str_replace($this->config->item('encryption_key'), "", base64url_decode($this->input->post('batch_name')));

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$update = $this->payment->update_gl_status(array($batch_name), NULL);

		if($update !== -1){

			$delete = $this->crud->delete("BATCH_PAYMENT", array("BATCH_NAME" => $batch_name));

			if( $delete > 0 && $this->crud->delete("JOURNAL_BATCH_PAYMENT", array("BATCH_NAME" => $batch_name)) > 0 ){
				$result['status']   = true;
				$result['messages'] = "Data successfully deleted";
			}
		}

		echo json_encode($result);
	}

	public function update_approval(){

		$batch_name = $this->input->post('batch_name');
		$approval   = $this->input->post('approval');

		$result['status'] = false;
		$result['messages'] = "Failed to delete data";

		$data = array("APPROVAL1" => $approval);
		$batch_nameArr[] = $batch_name;
		$update = $this->crud->update("BATCH_PAYMENT", $data, array("BATCH_NAME" => $batch_name));

		if($update !== -1){

			$result['messages'] = "Data successfully approved";
			if($approval == 2){
				$aa = $this->crud->delete("JOURNAL_BATCH_PAYMENT", array("BATCH_NAME" => $batch_name));
				$bb = $this->payment->update_gl_status($batch_nameArr);
				$result['messages'] = "Data successfully rejected";
			}
			$result['status']   = true;
		}

		echo json_encode($result);
	}

}

/* End of file Batch_payment_ctl.php */
/* Location: ./application/controllers/payment/Batch_payment_ctl.php */