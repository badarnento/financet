<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_Flow extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Report_xls_mdl', 'report_xls');
		$this->load->model('Tb_mdl', 'tb');
	}

	public function show_cashflow(){
		$data['title']          = "Cash Flow";
		$data['module']         = "datatable";
		$data['template_page']  = "report_xls/cashflow";
		$data['get_exist_year'] = $this->tb->get_exist_year();
		
		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_cashflow(){
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0) {
			@set_time_limit(1200);
		}

		ini_set('memory_limit', '-1');

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];
		$bulan 		= $_REQUEST['bulan'];
		$date	    = date("Y-m-d H:i:s");
		
		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('Cash Flow')
								->setLastModifiedBy('Cash Flow')
								->setTitle("Cash Flow")
								->setSubject("Cash Flow")
								->setDescription("Cash Flow")
								->setKeywords("Cash Flow");

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

		$style_sub = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		  )
		);

		$style_nature = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  )
		);

		$style_total = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);
		
		$style_row = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  )
		);

		$style_double = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_DOUBLE)
		  )
		);

		$style_line = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_line_netral = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_line_total = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_line_down_up = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$style_double_up = array(
		        'font' => array('bold' => true),
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_DOUBLE)
		  )
		);

		$style_line_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  ),
			'borders' => array(
			 'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
		  )
		);

		$fintek = 'assets/img/fintek.jpg';
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$logo = $fintek;
		if(file_exists($logo)){
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('A1');
			$objDrawing->setHeight(90);
			$objDrawing->setWorksheet($excel->getActiveSheet());
		}
		
		$excel->setActiveSheetIndex(0)->setCellValue('A5', "PT FINTEK KARYA NUSANTARA");
		$excel->getActiveSheet()->mergeCells('A5:F5');
		$excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A6', "STATEMENTS OF CASH FLOWSE");
		$excel->getActiveSheet()->mergeCells('A6:F6');
		$excel->getActiveSheet()->getStyle('A6')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A7', "FOR THE PERIOD ".strtoupper($bulan).", ".$year." (UNAUDITED)");
		$excel->getActiveSheet()->mergeCells('A7:F7');
		$excel->getActiveSheet()->getStyle('A7')->applyFromArray($style_nature);
		$excel->setActiveSheetIndex(0)->setCellValue('A8', "( In Indonesian Rupiah )");
		$excel->getActiveSheet()->mergeCells('A8:F8');
		$excel->getActiveSheet()->getStyle('A8')->applyFromArray($style_nature);

		$excel->getActiveSheet()->getStyle('A9:F9')->applyFromArray($style_double);

		$excel->setActiveSheetIndex(0)->setCellValue('F11', $year);
		$excel->getActiveSheet()->getStyle('F11')->applyFromArray($style_line);

		$excel->setActiveSheetIndex(0)->setCellValue('B13', "CASH FLOWS FROM OPERATING ACTIVITIES");
		$excel->getActiveSheet()->getStyle('B13')->applyFromArray($style_sub);

		$excel->setActiveSheetIndex(0)->setCellValue('B14', "Cash receipts from operating revenues");
		$excel->getActiveSheet()->getStyle('B14')->applyFromArray($style_sub);

		$numrow = 15;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, "Transaction Fee");
		$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_line_amount);

		$numtotal = $numrow+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numtotal, "Total cash receipts from operating revenues");

		$numcpf = $numtotal+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numcpf, "Cash payments for");
		$excel->getActiveSheet()->getStyle('B'.$numcpf)->applyFromArray($style_sub);

		$numcpfp = $numcpf+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcpfp, "Cash paid for promotion expenses (Operational)");

		$numcpfe = $numcpfp+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcpfe, "Cash paid to employee");

		$numcpfpe = $numcpfe+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcpfpe, "Cash paid for promotion expenses (Virtual Money)");

		$numcpfo = $numcpfpe+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcpfo, "Cash paid for operation and maintenance");

		$numcpfg = $numcpfo+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcpfg, "Cash paid to general affair expenses");

		$numcpfs = $numcpfg+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcpfs, "Cash paid for service cost");

		$numoe = $numcpfs+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numoe, "Others Expenses");

		$numdwc = $numoe+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numdwc, "Deposit Working Capital");

		$numsd = $numdwc+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsd, "Security Deposit");

		$numwco = $numsd+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numwco, "Working Capital Outflow");
		$excel->getActiveSheet()->getStyle('F'.$numwco)->applyFromArray($style_line_amount);

		$numttl = $numwco+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numttl, "Total cash payments for operating costs and expenses");
		$excel->getActiveSheet()->getStyle('F'.$numttl)->applyFromArray($style_line_total);

		$numtp = $numttl+=4;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numtp, "Tax paid");

		$numir = $numtp+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numir, "Interest received");
		$excel->getActiveSheet()->getStyle('F'.$numir)->applyFromArray($style_line_amount);

		$numncu = $numir+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numncu, "Net cash used in operating activities");
		$excel->getActiveSheet()->getStyle('D'.$numncu)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('F'.$numncu)->applyFromArray($style_line_total);

		$numcff = $numncu+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numcff, "CASH FLOWS FROM INVESTING ACTIVITIES");
		$excel->getActiveSheet()->getStyle('B'.$numcff)->applyFromArray($style_sub);

		$numafa = $numcff+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numafa, "Acquisitions of fixed assets and intangible assets");

		$numpfi = $numafa+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpfi, "Proceeds from insurance claims");

		$numaai = $numpfi+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numaai, "Advance for acquisition of intangible assets");

		$numpea = $numaai+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpea, "Placement of escrow account");

		$numpcc = $numpea+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpcc, "Pledge of cash and cash equivalents");

		$numpce = $numpcc+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpce, "Pledge of cash and cash equivalents as collateral for bank guarantees");

		$numpfm = $numpce+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpfm, "Proceeds from maturity of short-term investments");

		$numrcc = $numpfm+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrcc, "Release of cash and cash equivalents previously pledged as collateral for bank guarantees");

		$numrpc = $numrcc+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrpc, "Release of pledge of cash and cash equivalents as collateral for bank guarantees");

		$numsti = $numrpc+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsti, "Short-term investment in time deposits");

		$numsss = $numsti+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numsss, "Subscription of shares of stock");

		$numrfl = $numsss+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrfl, "Receipt from liquidation of escrow account");
		$excel->getActiveSheet()->getStyle('F'.$numrfl)->applyFromArray($style_line_amount);

		$numnet = $numrfl+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numnet, "Net cash used in investing activities");
		$excel->getActiveSheet()->getStyle('D'.$numnet)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('F'.$numnet)->applyFromArray($style_line_total);

		$numcfff = $numnet+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numcfff, "CASH FLOWS FROM FINANCING ACTIVITIES");
		$excel->getActiveSheet()->getStyle('B'.$numcfff)->applyFromArray($style_sub);

		$numcd = $numcfff+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numcd, "Cash dividend");

		$numpcs = $numcd+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpcs, "Paid-in Capital Stock");

		$numrld = $numpcs+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrld, "Repayments of long-term debt");

		$numpi = $numrld+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpi, "Payments of interest");

		$numpcd = $numpi+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpcd, "Payment of cash dividends");

		$numpmt = $numpcd+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpmt, "Payments of medium-term loans");

		$numpst = $numpmt+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpst, "Payments of short-term loans");

		$numpou = $numpst+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpou, "Payments of obligations under finance lease");

		$numpmtl = $numpou+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpmtl, "Proceed of medium-term loans");

		$numpfpc = $numpmtl+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpfpc, "Proceed from parent company");

		$numpsl = $numpfpc+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpsl, "Proceed of short-term loans from parent company");

		$numpslc = $numpsl+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpslc, "Payments of short-term loans from parent company");

		$numpltl = $numpslc+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numpltl, "Proceed of long-term loan");

		$numrpl = $numpltl+=1;
		$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrpl, "Refund of premium on long-term loan");
		$excel->getActiveSheet()->getStyle('F'.$numrpl)->applyFromArray($style_line_amount);

		$numncash = $numrpl+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('D'.$numncash, "Net cash received from financing activity");
		$excel->getActiveSheet()->getStyle('D'.$numncash)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('F'.$numncash)->applyFromArray($style_line_total);

		$numequ = $numncash+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numequ, "NET  IN CASH AND CASH EQUIVALENTS");
		$excel->getActiveSheet()->getStyle('B'.$numequ)->applyFromArray($style_sub);

		$numcha = $numequ+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numcha, "EFFECT OF FOREIGN EXCHANGE RATE CHANGES");
		$excel->getActiveSheet()->getStyle('B'.$numcha)->applyFromArray($style_sub);

		$numper = $numcha+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numper, "CASH AND CASH EQUIVALENTS AT BEGINNING OF PERIOD");
		$excel->getActiveSheet()->getStyle('B'.$numper)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('F'.$numper)->applyFromArray($style_line_total);

		$numperend = $numper+=2;
		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numperend, "CASH AND CASH EQUIVALENTS AT END OF PERIOD");
		$excel->getActiveSheet()->getStyle('B'.$numperend)->applyFromArray($style_sub);
		$excel->getActiveSheet()->getStyle('F'.$numperend)->applyFromArray($style_double);

		$numline = $numperend+=2;
		$excel->getActiveSheet()->getStyle('B'.$numline.':F'.$numline)->applyFromArray($style_line);


		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		
		$excel->getActiveSheet(0)->setTitle("Cash Flow");
		$excel->setActiveSheetIndex(0);
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Cash Flow.xls"');
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}

/* End of file Bswp.php */
/* Location: ./application/controllers/report_xls/Bswp.php */