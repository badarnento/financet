<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outstanding_ap_ctl extends CI_Controller {

	private $module_name = "payment",
			$module_url  = "outstanding-ap";

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

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$data['title']         = "Outstanding AP";
			$data['module']        = "datatable";
			$data['template_page'] = "payment_batch/outstanding_ap_inquiry";

			// $group_name = get_user_group_data();
			// $data['group_name'] = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Outstanding AP", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}

	public function load_data_outstanding(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){

			$result['data']            = "";
			$result['draw']            = "";
			$result['recordsTotal']    = 0;
			$result['recordsFiltered'] = 0;

			$date_from 			= "";
			$date_to   			= "";
			$filterdateby     	= $this->input->post('filterdateby');
			$this->status_header = 200;

			if($this->input->post('date_from') != "" && $this->input->post('date_to') != ""){
				$date_from     = date_db($this->input->post('date_from'));
				$date_to       = date_db($this->input->post('date_to'));
			}

			$get_all         = $this->payment->get_ap_outstanding($date_from, $date_to, $filterdateby);
			$data            = $get_all['data'];
			$total           = $get_all['total_data'];
			$start           = $this->input->post('start');
			$number          = $start+1;

			if($total > 0){

				foreach($data as $value) {

					$batch_name = $value['BATCH_NAME'];

					$row[] = array(
								'no'            => $number,
								'batch_name'    => $batch_name,
								'tgl_invoice'   => dateFormat($value['TGL_INVOICE'], 5, false),
								'no_journal'    => $value['NO_JOURNAL'],
								'nama_vendor'   => $value['NAMA_VENDOR'],
								'no_invoice'    => $value['NO_INVOICE'],
								'no_kontrak'    => $value['NO_KONTRAK'],
								'description'   => $value['DESCRIPTION'],
								'currency'      => $value['CURRENCY'],
								'dpp'           => number_format($value['AP_AMOUNT'],0,',','.'),
								'no_fpjp'       => $value['NO_FPJP'],
								'nama_rekening' => $value['NAMA_REKENING'],
								'nama_bank'     => $value['NAMA_BANK'],
								'acct_number'   => $value['ACCT_NUMBER'],
								'top'           => $value['TOP'],
								'due_date'      => dateFormat($value['DUE_DATE'], 5, false),
								'nature'        => $value['NATURE']
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

	
	public function download_inquiry()
	{

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
		
		$arrColumn = array("No", "Tgl Invoice", "Batch Name", "No Journal", "Nama Vendor", "No Invoice", "No Kontrak", "Description", "Currency", "AP Amount", "No FPJP", "Nama Rekening", "Nama Bank", "Acct Number", "TOP", "Due Date", "Nature");

		$totalColumn = count($arrColumn);

		$loop_column = horizontal_loop_excel("A", $totalColumn);
		$j=0;
		foreach ($loop_column as $key => $value) {
			$excel->setActiveSheetIndex(0)->setCellValue($value.'1', $arrColumn[$j]);
			$j++;
		}


		$date_from = "";
		$date_to   = "";
		$filterdateby = $this->input->get('filterdateby');

		if($this->input->get('invoice_date_from') != "" && $this->input->get('invoice_date_to') != ""){
			$date_from     = date_db($this->input->get('invoice_date_from'));
			$date_to       = date_db($this->input->get('invoice_date_to'));
		}

		$hasil = $this->payment->get_download_outstanding_ap($date_from,$date_to,$filterdateby);
		$numrow = 2;
		$number = 1;

		foreach($hasil->result_array() as $row)	{

			$loop_column = horizontal_loop_excel("A", $totalColumn);
			$valueOnly = array_values($row);
			array_unshift($valueOnly , $number);
			$j=0;

			$tgl_invoice = $row['TGL_INVOICE'];
			$due_date    = $row['DUE_DATE'];
			$acctnumber  = $row['ACCT_NUMBER'];
			foreach ($loop_column as $key => $value) {

				$data = $valueOnly[$j];

				if($data == $tgl_invoice){
					$data = dateFormat($tgl_invoice, 5, false);
				}
				if($data == $acctnumber){
					$data = ($acctnumber != "") ? "'" . $acctnumber : "";
				}
				if($data == $due_date){
					$data = dateFormat($due_date, 5, false);
				}
				$excel->setActiveSheetIndex(0)->setCellValue($value.$numrow, $data);

				$j++;
			}

			$excel->getActiveSheet()->getStyle('J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');

			$numrow++;
			$number++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", $totalColumn);
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
		header('Content-Disposition: attachment; filename="Outstanding AP.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function load_ddl_filter_date_by()
	{		
		$result  = "";
		$result .= "<option value='' data-name='' > -- Please Choose -- </option>";
		$result .= "<option value='1' data-name='INVOICE_DATE' > Invoice Date </option>";
		$result .= "<option value='2' data-name='APPROVED_DATE' > Approved Date </option>";

		echo $result;
	}

}

/* End of file Outstanding_ap_ctl.php */
/* Location: ./application/controllers/payment/Outstanding_ap_ctl.php */