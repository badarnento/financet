<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Expense_mdl', 'expense');
		$this->load->model('Pph_mdl', 'pph');
	}

	public function show_expense(){
		$data['title']          = "Expense Dhasboard";
		$data['module']         = "datatable";
		$data['template_page']  = "report_xls/expense_dhasboard";
		$data['get_exist_year'] = $this->pph->get_exist_year();
		
		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_data(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year     	= $this->input->post('year');
		$month     	= $this->input->post('month');
		
		$get_all = $this->expense->get_expense($year,$month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$grand_total = 0;
				$grand_total = $value['OUTSTANDING'] + $value['TANGGAL_1'] + $value['TANGGAL_2'] + $value['TANGGAL_3'] + $value['TANGGAL_4'] + $value['TANGGAL_5'] + $value['TANGGAL_6'] + $value['TANGGAL_7'] + $value['TANGGAL_8'] + $value['TANGGAL_9'] + $value['TANGGAL_10'] + $value['TANGGAL_11'] + $value['TANGGAL_12'] + $value['TANGGAL_13'] + $value['TANGGAL_14'] + $value['TANGGAL_15'] + $value['TANGGAL_16'] + $value['TANGGAL_17'] + $value['TANGGAL_18'] + $value['TANGGAL_19'] + $value['TANGGAL_20'] + $value['TANGGAL_21'] + $value['TANGGAL_22'] + $value['TANGGAL_23'] + $value['TANGGAL_24'] + $value['TANGGAL_25'] + $value['TANGGAL_26'] + $value['TANGGAL_27'] + $value['TANGGAL_28'] + $value['TANGGAL_29'] + $value['TANGGAL_30'] + $value['TANGGAL_31'];

				$row[] = array(
					'no'           		=> $number,
					'nature'  			=> $value['NATURE'],
					'coa_description'  	=> $value['COA_DESCRIPTION'],
					'outstanding'  		=> number_format($value['OUTSTANDING'],0,',','.'),
					'reverse'  			=> $value['REVERSE'],
					'tanggal_1'  		=> number_format($value['TANGGAL_1'],0,',','.'),
					'tanggal_2'  		=> number_format($value['TANGGAL_2'],0,',','.'),
					'tanggal_3'  		=> number_format($value['TANGGAL_3'],0,',','.'),
					'tanggal_4'  		=> number_format($value['TANGGAL_4'],0,',','.'),
					'tanggal_5'  		=> number_format($value['TANGGAL_5'],0,',','.'),
					'tanggal_6'  		=> number_format($value['TANGGAL_6'],0,',','.'),
					'tanggal_7'  		=> number_format($value['TANGGAL_7'],0,',','.'),
					'tanggal_8'  		=> number_format($value['TANGGAL_8'],0,',','.'),
					'tanggal_9'  		=> number_format($value['TANGGAL_9'],0,',','.'),
					'tanggal_10'  		=> number_format($value['TANGGAL_10'],0,',','.'),
					'tanggal_11'  		=> number_format($value['TANGGAL_11'],0,',','.'),
					'tanggal_12'  		=> number_format($value['TANGGAL_12'],0,',','.'),
					'tanggal_13'  		=> number_format($value['TANGGAL_13'],0,',','.'),
					'tanggal_14'  		=> number_format($value['TANGGAL_14'],0,',','.'),
					'tanggal_15'  		=> number_format($value['TANGGAL_15'],0,',','.'),
					'tanggal_16'  		=> number_format($value['TANGGAL_16'],0,',','.'),
					'tanggal_17'  		=> number_format($value['TANGGAL_17'],0,',','.'),
					'tanggal_18'  		=> number_format($value['TANGGAL_18'],0,',','.'),
					'tanggal_19'  		=> number_format($value['TANGGAL_19'],0,',','.'),
					'tanggal_20'  		=> number_format($value['TANGGAL_20'],0,',','.'),
					'tanggal_21'  		=> number_format($value['TANGGAL_21'],0,',','.'),
					'tanggal_22'  		=> number_format($value['TANGGAL_22'],0,',','.'),
					'tanggal_23'  		=> number_format($value['TANGGAL_23'],0,',','.'),
					'tanggal_24'  		=> number_format($value['TANGGAL_24'],0,',','.'),
					'tanggal_25'  		=> number_format($value['TANGGAL_25'],0,',','.'),
					'tanggal_26'  		=> number_format($value['TANGGAL_26'],0,',','.'),
					'tanggal_27'  		=> number_format($value['TANGGAL_27'],0,',','.'),
					'tanggal_28'  		=> number_format($value['TANGGAL_28'],0,',','.'),
					'tanggal_29'  		=> number_format($value['TANGGAL_29'],0,',','.'),
					'tanggal_30'  		=> number_format($value['TANGGAL_30'],0,',','.'),
					'tanggal_31'  		=> number_format($value['TANGGAL_31'],0,',','.'),
					'grand_total'  		=> number_format($grand_total,0,',','.')
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

	public function cetak_expense(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('Expense Dhasboard')
		->setLastModifiedBy('')
		->setTitle("Expense_dhasboard")
		->setSubject("Cetak")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

		$style_header = array(
		    'alignment'=> array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		  )
		);

		$style_row = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$style_center = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
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

		$excel->setActiveSheetIndex(0)->setCellValue('A2', "Expense Detail daily basis");
		$excel->getActiveSheet()->mergeCells('A2:B2');
		$excel->setActiveSheetIndex(0)->setCellValue('A3', "COA");
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "CoA Description");
		$excel->setActiveSheetIndex(0)->setCellValue('C2', "Payment Date");
		$excel->getActiveSheet()->mergeCells('C2:T2');
		$excel->getActiveSheet()->getStyle('C2:T2')->applyFromArray($style_center);
		$excel->setActiveSheetIndex(0)->setCellValue('C3', "Outstanding");
		$excel->setActiveSheetIndex(0)->setCellValue('D3', "Reverse");
		$excel->setActiveSheetIndex(0)->setCellValue('E3', "01/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('F3', "02/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('G3', "03/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('H3', "04/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('I3', "05/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('J3', "06/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('K3', "07/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('L3', "08/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('M3', "09/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('N3', "10/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('O3', "11/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('P3', "12/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('Q3', "13/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('R3', "14/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('S3', "15/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('T3', "16/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('U3', "17/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('V3', "18/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('W3', "19/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('X3', "20/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('Y3', "21/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('Z3', "22/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AA3', "23/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AB3', "24/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AC3', "25/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AD3', "26/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AE3', "27/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AF3', "28/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AG3', "29/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AH3', "30/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AI3', "31/".$month."/".$year);
		$excel->setActiveSheetIndex(0)->setCellValue('AJ3', "Grand Total");

		$hasil = $this->expense->get_cetak($year, $month);

		$numrow    = 4;

		foreach($hasil->result_array() as $row)	{

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['COA_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['OUTSTANDING']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['REVERSE']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['TANGGAL_1']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['TANGGAL_2']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['TANGGAL_3']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['TANGGAL_4']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['TANGGAL_5']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['TANGGAL_6']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['TANGGAL_7']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['TANGGAL_8']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['TANGGAL_9']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['TANGGAL_10']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['TANGGAL_11']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['TANGGAL_12']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['TANGGAL_13']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['TANGGAL_14']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['TANGGAL_15']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $row['TANGGAL_16']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $row['TANGGAL_17']);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $row['TANGGAL_18']);
			$excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $row['TANGGAL_19']);
			$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $row['TANGGAL_20']);
			$excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $row['TANGGAL_21']);
			$excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $row['TANGGAL_22']);
			$excel->setActiveSheetIndex(0)->setCellValue('AA'.$numrow, $row['TANGGAL_23']);
			$excel->setActiveSheetIndex(0)->setCellValue('AB'.$numrow, $row['TANGGAL_24']);
			$excel->setActiveSheetIndex(0)->setCellValue('AC'.$numrow, $row['TANGGAL_25']);
			$excel->setActiveSheetIndex(0)->setCellValue('AD'.$numrow, $row['TANGGAL_26']);
			$excel->setActiveSheetIndex(0)->setCellValue('AE'.$numrow, $row['TANGGAL_27']);
			$excel->setActiveSheetIndex(0)->setCellValue('AF'.$numrow, $row['TANGGAL_28']);
			$excel->setActiveSheetIndex(0)->setCellValue('AG'.$numrow, $row['TANGGAL_29']);
			$excel->setActiveSheetIndex(0)->setCellValue('AH'.$numrow, $row['TANGGAL_30']);
			$excel->setActiveSheetIndex(0)->setCellValue('AI'.$numrow, $row['TANGGAL_31']);
			$excel->setActiveSheetIndex(0)->setCellValue('AJ'.$numrow, '=SUM(C'.$numrow.':AI'.$numrow.')');

			$excel->getActiveSheet()->getStyle('C'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("-"??_);_(@_)');
			$excel->getActiveSheet()->getStyle('E'.$numrow.':AJ'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("-"??_);_(@_)');

			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_header);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_header);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('U'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('V'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('W'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('X'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('Y'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('Z'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AA'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AB'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AC'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AD'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AE'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AF'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AG'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AH'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AI'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AJ'.$numrow)->applyFromArray($style_row);

			$numrow++;

		}

		$last_row = $numrow-1;
		$row_grand = $numrow;

		$excel->setActiveSheetIndex(0)->setCellValue('C'.$row_grand, '=SUM(C4:C'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('E'.$row_grand, '=SUM(E4:E'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('F'.$row_grand, '=SUM(F4:F'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('G'.$row_grand, '=SUM(G4:G'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$row_grand, '=SUM(H4:H'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('I'.$row_grand, '=SUM(I4:I'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('J'.$row_grand, '=SUM(J4:J'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('K'.$row_grand, '=SUM(K4:K'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('L'.$row_grand, '=SUM(L4:L'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('M'.$row_grand, '=SUM(M4:M'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('N'.$row_grand, '=SUM(N4:N'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('O'.$row_grand, '=SUM(O4:O'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('P'.$row_grand, '=SUM(P4:P'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('Q'.$row_grand, '=SUM(Q4:Q'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('R'.$row_grand, '=SUM(R4:R'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('S'.$row_grand, '=SUM(S4:S'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('T'.$row_grand, '=SUM(T4:T'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('U'.$row_grand, '=SUM(U4:U'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('V'.$row_grand, '=SUM(V4:V'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('W'.$row_grand, '=SUM(W4:W'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('X'.$row_grand, '=SUM(X4:X'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('Y'.$row_grand, '=SUM(Y4:Y'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('Z'.$row_grand, '=SUM(Z4:Z'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AA'.$row_grand, '=SUM(AA4:AA'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AB'.$row_grand, '=SUM(AB4:AB'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AC'.$row_grand, '=SUM(AC4:AC'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AD'.$row_grand, '=SUM(AD4:AD'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AE'.$row_grand, '=SUM(AE4:AE'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AF'.$row_grand, '=SUM(AF4:AF'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AG'.$row_grand, '=SUM(AG4:AG'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AH'.$row_grand, '=SUM(AH4:AH'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AI'.$row_grand, '=SUM(AI4:AI'.$last_row.')');
		$excel->setActiveSheetIndex(0)->setCellValue('AJ'.$row_grand, '=SUM(AJ4:AJ'.$last_row.')');
		
		$excel->getActiveSheet()->getStyle('C'.$row_grand)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("-"??_);_(@_)');
		$excel->getActiveSheet()->getStyle('D'.$row_grand.':AJ'.$row_grand)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("-"??_);_(@_)');

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 36);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Expense Dhasboard");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Expense Dhasboard.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}