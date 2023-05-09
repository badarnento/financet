<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_payment_ctl extends CI_Controller {

	private $module_name = "report",
			$module_url  = "report/list-payment";

	protected $status_header;

	public function __construct()
	{
		
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->load->model('Tb_mdl', 'tb');
		$this->load->model('payment_mdl', 'payment');

		$this->status_header = 401;

	}

	public function index()
	{

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){
			
			$data['title']         = "List of Payment";
			$data['module']        = "datatable";
			$data['template_page'] = "report_xls/list_payment";
			$data['get_exist_year'] = $this->tb->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "List of Payment", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}
	}


	public function unpayment()
	{

		if($this->ion_auth->is_admin() == true || in_array($this->module_url, $this->session->userdata['menu_url']) ){
			
			$data['title']         = "List of Unpayment";
			$data['module']        = "datatable";
			$data['template_page'] = "report_xls/list_unpayment";
			$data['get_exist_year'] = $this->tb->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "List of Unpayment", "link" => "", "class" => "active" );

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

			$status              = $this->input->post('status');
			$this->status_header = 200;

			$date_from   		= date_db($this->input->post('date_from'));
			$date_to     		= date_db($this->input->post('date_to'));
			$filterdateby     	= $this->input->post('filterdateby');

			if($status){
				$get_all = $this->payment->get_list_payment($date_from, $date_to, $filterdateby, $status);
			}else{
				$get_all = $this->payment->get_list_payment($date_from, $date_to, $filterdateby, "RECONCILED");
			}

			$data            = $get_all['data'];
			$total           = $get_all['total_data'];
			$start           = $this->input->post('start');
			$number          = $start+1;

			$get_bank_la     = get_bank_la();
			$rekening_sumber = $get_bank_la['NAMA_BANK'];
			$no_rk_sumber    = $get_bank_la['NOMOR_REKENING'];

			if($total > 0){

				foreach($data as $value) {

					$row[] = array(
								'no'                    => $number,
								'period'                => date("M-y", strtotime($value['PERIOD'])),
								'tgl_terima_ap'         => date("d-M-y", strtotime($value['TGL_TERIMA_AP'])),
								'no_invoice'            => $value['NO_INVOICE'],
								'no_journal_ap'         => $value['NO_JOURNAL_AP'],
								'no_journal_tr'         => $value['NO_JOURNAL_TR'],
								'rekening_sumber'       => $rekening_sumber,
								'no_rk_sumber'          => $no_rk_sumber,
								'rekening_penerima'     => $value['REKENING_PENERIMA'],
								'no_rk_penerima'        => $value['NO_RK_PENERIMA'],
								'nama_penerima'         => $value['NAMA_PENERIMA'],
								'total_invoice'         => number_format($value['TOTAL_INVOICE'],0,',','.'),
								'total_bayar'           => number_format($value['TOTAL_BAYAR'],0,',','.'),
								'keterangan'            => $value['KETERANGAN'],
								'paid_date'             => dateFormat($value['PAID_DATE'], 4, false),
								'due_date'              => dateFormat($value['DUE_DATE'], 5, false)
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
		

		$date_from   		= date_db($this->input->get('from'));
		$date_to     		= date_db($this->input->get('to'));
		$filterdateby     	= $this->input->get('filterdateby');
		$status 			= $this->input->get('status');

		$pcb = true;
		if($status){
			$arrColumn = array("No", "Period", "Tgl Terima AP", "No Invoice", "No Journal AP", "No Journal TR", "Rekening Sumber", "No RK Sumber", "Rekening Penerima", "No RK Penerima", "Nama Penerima", "Total Invoice", "Keterangan", "Due Date");
			$pcb = false;
			$hasil = $this->payment->get_download_list_payment($date_from, $date_to, $filterdateby, $status);
			$fileName = "List Unpayment";
		}else{
			$arrColumn = array("No", "Period", "Tgl Terima AP", "No Invoice", "No Journal AP", "No Journal TR", "Rekening Sumber", "No RK Sumber", "Rekening Penerima", "No RK Penerima", "Nama Penerima", "Total Invoice", "Total Bayar", "Keterangan", "Paid Date", "Due Date");
			$hasil = $this->payment->get_download_list_payment($date_from, $date_to, $filterdateby, "RECONCILED");
			$fileName = "List Payment";
		}

		$totalColumn = count($arrColumn);
		$loop_column = horizontal_loop_excel("A", $totalColumn);
		$j=0;
		foreach ($loop_column as $key => $value) {
			$excel->setActiveSheetIndex(0)->setCellValue($value.'1', $arrColumn[$j]);
			$j++;
		}

		$numrow = 2;
		$number = 1;
		
		$get_bank_la        = get_bank_la();
		$rekening_sumber    = $get_bank_la['NAMA_BANK'];
		$no_rekening_sumber = $get_bank_la['NOMOR_REKENING'];

		$nama_penerima = "";
		$no_journal_tr = "";
		$last_num_row  = 0;

		$startRow = -1;
		$previousKey = '';
		$numrow_first = $numrow;

		$datas = $hasil->result_array();

		foreach($datas as $index => $row)	{
		    

			$loop_column = horizontal_loop_excel("A", $totalColumn);
			$valueOnly = array_values($row);
			array_unshift($valueOnly , $number);
			$j=0;

			if($startRow == -1){
		        $startRow = $numrow;
		        $previousKey = $row['NO_JOURNAL_TR'];
		    }

			$period        = $row['PERIOD'];
			$tgl_terima_ap = $row['TGL_TERIMA_AP'];
			$rk_sumber     = $row['REKENING_SUMBER'];
			$no_rk_sumber  = $row['NO_RK_SUMBER'];
			$acctnumber    = $row['NO_RK_PENERIMA'];
			if($pcb):
				$paid_date   = $row['PAID_DATE'];
			endif;
			$due_date      = $row['DUE_DATE'];

		    $nextKey = isset($datas[$index+1]) ? $datas[$index+1]['NO_JOURNAL_TR'] : null;

		    if($pcb && ($numrow >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null)))){
		        $cellToMerge = 'M'.$startRow.':M'.$numrow;
		        // $excel->getActiveSheet()->mergeCells($cellToMerge);
		        $startRow = -1;
		    }
			$nama_penerima = $row['NAMA_PENERIMA'];
			$no_journal_tr = $row['NO_JOURNAL_TR'];
			$last_num_row  = $numrow;

			foreach ($loop_column as $key => $value) {

				$data = $valueOnly[$j];

				if($data == $period){
					$data = date("M-y", strtotime($period));
				}
				if($data == $tgl_terima_ap){
					$data = date("d-M-y", strtotime($period));
				}
				if($data == $rk_sumber){
					$data = $rekening_sumber;
				}
				if($data == $no_rk_sumber){
					$data = $no_rekening_sumber;
				}
				if($data == $acctnumber){
					$data = ($acctnumber != "") ? "'" . $acctnumber : "";
				}
				if($pcb):
					if($data == $paid_date){
						$data = dateFormat($paid_date, 4, false);
					}
				endif;
				if($data == $due_date){
					$data = dateFormat($due_date, 5, false);
				}

				if($valueOnly[$j] == ""){
					$data = $valueOnly[$j];
				}
				$excel->setActiveSheetIndex(0)->setCellValue($value.$numrow, $data);

				$j++;
			}

			if($pcb){
				$excel->getActiveSheet()->getStyle('L'.$numrow.':M'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			}else{
				$excel->getActiveSheet()->getStyle('L'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			}

			$numrow++;
			$number++;

		}

		$style = array(
	        'alignment' => array(
	        	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
	        )
	    );

		$excel->getActiveSheet()->getStyle('M'.$numrow_first.':M'.$numrow)->applyFromArray($style);


		/*echo 'stop';
		die;
*/
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
		header('Content-Disposition: attachment; filename="'.$fileName.'.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	public function load_ddl_filter_date_by()
	{		
		$result  = "";
		$result .= "<option value='' data-name='' > -- Please Choose -- </option>";
		$result .= "<option value='1' data-name='TRANSACTION_DATE' > Paymet Date </option>";
		$result .= "<option value='2' data-name='APPROVED_DATE' > Approved Date </option>";

		echo $result;
	}

	public function load_ddl_filter_date_unpayment_by()
	{		
		$result  = "";
		$result .= "<option value='' data-name='' > -- Please Choose -- </option>";
		$result .= "<option value='1' data-name='TGL_INVOICE' > Invoice Date </option>";
		$result .= "<option value='2' data-name='APPROVED_DATE' > Approved Date </option>";

		echo $result;
	}

}

/* End of file List_payment_ctl.php */
/* Location: ./application/controllers/report_xls/List_payment_ctl.php */