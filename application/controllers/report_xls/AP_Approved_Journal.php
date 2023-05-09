<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AP_Approved_Journal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Report_xls_mdl', 'ap_appr');
	}

	public function index()
	{
		
	}

	public function show_ap_approuved(){

		    $data['title']          = "AP Approuved Journal";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/ap_approved_journal";

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "AP Approuved Journal", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_data(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$accounting_date_from   		= date_db($this->input->post('accounting_date_from'));
		$accounting_date_to     		= date_db($this->input->post('accounting_date_to'));
		
		$get_all = $this->ap_appr->get_ap_approuved_jornal($accounting_date_from, $accounting_date_to);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'           			=> $number,
					'accounting_date'  		=> $value['ACCOUNTING_DATE'],
					'batch_name'  			=> $value['BATCH_NAME'],
					'journal_name' 			=> $value['JOURNAL_NAME'],
					'debit' 				=> number_format($value['DEBIT'],0,',','.'),
					'credit' 				=> number_format($value['CREDIT'],0,',','.'),
					'nature' 				=> $value['NATURE'],
					'account_description' 	=> $value['ACCOUNT_DESCRIPTION'],
					'journal_description' 	=> $value['JOURNAL_DESCRIPTION'],
					'reference_1' 			=> $value['REFERENCE_1'],
					'reference_2' 			=> $value['REFERENCE_2'],
					'reference_3' 			=> $value['REFERENCE_3'],
					'paid_date' 			=> $value['PAID_DATE'],
					'type_tax' 				=> $value['TYPE_TAX']
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

	public function cetak_report(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('Report Status PO')
		->setLastModifiedBy('')
		->setTitle("Report_statusPO")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$accounting_date_from 		= date_db($_REQUEST['accounting_date_from']);
		$accounting_date_to 		= date_db($_REQUEST['accounting_date_to']);

		$style_header = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			   'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_row = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			   'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			  'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			   'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Accounting Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Debit");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Reference 1");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Reference 2");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Reference 3");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Paid Date");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Type Tax");
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('J1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('K1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('L1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('M1')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_header);

		$hasil = $this->ap_appr->get_ap_appr_journal_download($accounting_date_from, $accounting_date_to);

		$numrow    = 2;
		$no=1;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['ACCOUNTING_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, number_format($row['DEBIT'],0,',','.'));
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, number_format($row['CREDIT'],0,',','.'));
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['JOURNAL_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['REFERENCE_1']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['REFERENCE_2']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['REFERENCE_3']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['PAID_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['TYPE_TAX']);

			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);

			$numrow++;
			$no++;

		}

		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(100);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(30);

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("AP Approuved Journal");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="AP Approuved Journal.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}