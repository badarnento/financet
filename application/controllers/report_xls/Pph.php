<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pph extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Pph_mdl', 'pph');
	}

	public function index()
	{
		
	}

	public function show_pph_23(){

		    $data['title']          = "PPH 23";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/pph_23";
			$data['get_exist_year'] = $this->pph->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "PPH 23", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_pph23(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		$get_all = $this->pph->get_pph23($year, $month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$no_urut_dok = "";
				if($number <10){
					$no_urut_dok = "00".$number;
				}elseif($number >=10 && $number <100){
					$no_urut_dok = "0".$number;
				}else{
					$no_urut_dok = $number;
				}

				$rpl_npwp = str_replace("-","", str_replace(".","", $value['NPWP']));

				$row[] = array(
					'no'           			=> $number,
					'masa_pajak'  			=> $value['MASA'],
					'tahun_pajak'  			=> $value['TAHUN_PAJAK'],
					'tgl_pemotong' 			=> dateFormat($value['INVOICE_DATE'], "bupot", false, "id"),
					'ber_npwp' 				=> $value['BER_NPWP'],
					'npwp' 					=> $rpl_npwp,
					'nik' 					=> $value['NIK'],
					'telp'   				=> $value['TELP'],
					'kode_objek_pajak' 		=> $value['KODE_OBJEK_PAJAK'],
					'bp_pengurus'  			=> $value['BP_PENGURUS'],
					'dpp'					=> number_format($value['DPP'],0,',','.'),
					'no_doc'				=> $no_urut_dok."/".$value['NO_INVOICE'],
					'no_invoice'			=> $value['NO_INVOICE'],
					'tgl_invoice'			=> date("d/m/Y", strtotime($value['INVOICE_DATE'])),
					'fasilitas'				=> $value['FASILITAS'],
					'no_skb' 				=> $value['SKB_PPH23'],
					'aturan_dtp' 			=> $value['S_KET_DTP'],
					'ktp' 					=> $value['KTP'],
					'ntpn_dpt' 				=> $value['NTPN_DPT']
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

	public function cetak_pph23(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PPH 23')
		->setLastModifiedBy('')
		->setTitle("PPH 23")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

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
		  )
		);

		$style_center = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$textFormat='@';
		$GeneralFormat='General';
		$NumberFormat='0';

		$excel->setActiveSheetIndex(0)->setCellValue('A2', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "Masa Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('C2', "Tahun Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('D2', "Tanggal Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('E2', "Tgl Pemotongan (dd/MM/yyyy)");
		$excel->setActiveSheetIndex(0)->setCellValue('F2', "Ber-NPWP ? (Y/N)");
		$excel->setActiveSheetIndex(0)->setCellValue('G2', "NPWP (tanpa format/tanda baca)");
		$excel->setActiveSheetIndex(0)->setCellValue('H2', "NIK (tanpa format/tanda baca)");
		$excel->setActiveSheetIndex(0)->setCellValue('I2', "Nomor Telp");
		$excel->setActiveSheetIndex(0)->setCellValue('J2', "Kode Objek Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('K2', "Penanda tangan BP Pengurus ? (Y/N)");
		$excel->setActiveSheetIndex(0)->setCellValue('L2', "Penghasilan Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('M2', "No Dok");
		// $excel->setActiveSheetIndex(0)->setCellValue('N2', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('N2', "Mendapatkan Fasilitas ? (N/SKB/DTP)");
		$excel->setActiveSheetIndex(0)->setCellValue('O2', "Nomor SKB");
		$excel->setActiveSheetIndex(0)->setCellValue('P2', "Nomor Aturan DTP");
		$excel->setActiveSheetIndex(0)->setCellValue('Q2', "KTP");
		$excel->setActiveSheetIndex(0)->setCellValue('R2', "NTPN DTP");

		$excel->getActiveSheet()->getStyle('A2:S2')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('A2:B2')->getNumberFormat()->setFormatCode($GeneralFormat);
		$excel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode($NumberFormat);
		$excel->getActiveSheet()->getStyle('D2:K2')->getNumberFormat()->setFormatCode($textFormat);
		$excel->getActiveSheet()->getStyle('L2:O2')->getNumberFormat()->setFormatCode($GeneralFormat);
		$excel->getActiveSheet()->getStyle('P2:R2')->getNumberFormat()->setFormatCode($textFormat);
		$excel->getActiveSheet()->getStyle('A2:R2')->getAlignment()->setWrapText(true);

		$hasil = $this->pph->cetak_pph23($year, $month);

		$numrow    = 3;
		$no=1;

		foreach($hasil->result_array() as $row)	{

			$npwp 		= str_replace('.','',$row['NPWP']);
			$npwp_final = str_replace('-','',$npwp);
			if($row['MASA'] > 10){
				$masa = $row['MASA'];
			}elseif($row['MASA'] < 10){
				$masa = str_replace('0',"", $row['MASA']);
			}

			$no_urut_dok = "";
			if($no <10){
				$no_urut_dok = "00".$no;
			}elseif($no >=10 && $no <100){
				$no_urut_dok = "0".$no;
			}else{
				$no_urut_dok = $no;
			}

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $masa);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['TAHUN_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d/m/Y", strtotime($row['INVOICE_DATE'])));
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, dateFormat($row['INVOICE_DATE'],"bupot", false, "id"));
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['BER_NPWP']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $npwp_final);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NIK']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['TELP']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['KODE_OBJEK_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['BP_PENGURUS']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['DPP']);
			// $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $no_urut_dok."/".$row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['FASILITAS']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['SKB_PPH23']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['S_KET_DTP']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['KTP']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['NTPN_DPT']);

			$excel->getActiveSheet()->getStyle('A'.$numrow.':B'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->getNumberFormat()->setFormatCode($NumberFormat)->applyFromArray($style_row);

			if(substr($npwp_final,0,1) == 0){
				$excel->getActiveSheet()->getStyle('D'.$numrow.':K'.$numrow)->getNumberFormat()->setFormatCode($textFormat);
			}else{
				$excel->getActiveSheet()->getStyle('D'.$numrow.':K'.$numrow)->getNumberFormat()->setFormatCode($NumberFormat);
			}

			$excel->getActiveSheet()->getStyle('K'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat);
			$excel->getActiveSheet()->getStyle('N'.$numrow.':R'.$numrow)->getNumberFormat()->setFormatCode($textFormat)->applyFromArray($style_row);

			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_center);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_center);

			$numrow++;
			$no++;

		}

		// Set width kolom
		/*$loop_column = horizontal_loop_excel("A", 3);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}*/
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("PPH 23");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PPH 23.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function show_pph_26(){

		    $data['title']          = "PPH 26";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/pph_26";
			$data['get_exist_year'] = $this->pph->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "PPH 26", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_pph26(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		$get_all = $this->pph->get_pph26($year, $month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$no_urut_dok = "";
				if($number <10){
					$no_urut_dok = "00".$number;
				}elseif($number >=10 && $number <100){
					$no_urut_dok = "0".$number;
				}else{
					$no_urut_dok = $number;
				}

				$row[] = array(
					'no'           			=> $number,
					'masa_pajak'  			=> $value['MASA'],
					'tahun_pajak'  			=> $value['TAHUN_PAJAK'],
					'tgl_pemotong' 			=> dateFormat($value['INVOICE_DATE'], "bupot", false, "id"),
					'tgl_invoice' 			=> date("d/m/Y", strtotime($value['INVOICE_DATE'])),
					'tin' 					=> $value['TIN'],
					'no_dok' 				=> $no_urut_dok."/".$value['NO_INVOICE'],
					'no_invoice' 			=> $value['NO_INVOICE'],
					'nama_wp' 				=> $value['NAMA_VENDOR'],
					'tgl_lahir' 			=> $value['TANGGAL_LAHIR'],
					'alamat_wp'   			=> $value['ALAMAT'],
					'no_paspor'   			=> $value['NO_PASPOR'],
					'no_kitas'   			=> $value['NO_KITAS_WP'],
					'kode_negara'   		=> $value['KODE_NEGARA'],
					'kode_objek_pajak' 		=> $value['KODE_OBJEK_PAJAK'],
					'penanda_tangan' 		=> $value['PENANDA_TANGAN'],
					'dpp'					=> number_format($value['DPP'],0,',','.'),
					'perkiraan_penghasilan'	=> $value['PENGHASILAN_NETO'],
					'fasilitas'				=> $value['FASILITAS'],
					'no_tanda_terima' 		=> $value['SKD'],
					'tarif_skd' 			=> $value['TARIF_SKD'],
					'aturan_dtp' 			=> $value['S_KET_DTP'],
					'ntpn_dtp' 				=> $value['NTPN_DTP']
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

	public function cetak_pph26(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PPH 26')
		->setLastModifiedBy('')
		->setTitle("PPH 26")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

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
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$textFormat='@';
		$GeneralFormat='General';
		$NumberFormat='0';

		$excel->setActiveSheetIndex(0)->setCellValue('A2', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "Masa Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('C2', "Tahun Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('D2', "Tanggal Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('E2', "Tgl Pemotongan (dd/MM/yyyy)");
		$excel->setActiveSheetIndex(0)->setCellValue('F2', "TIN (dengan format/tanda baca)");
		$excel->setActiveSheetIndex(0)->setCellValue('G2', "Nama WP Terpotong");
		$excel->setActiveSheetIndex(0)->setCellValue('H2', "Tgl Lahir WP Terpotong (dd/MM/yyyy)");
		$excel->setActiveSheetIndex(0)->setCellValue('I2', "Alamat WP Terpotong");
		$excel->setActiveSheetIndex(0)->setCellValue('J2', "No Paspor WP Terpotong");
		$excel->setActiveSheetIndex(0)->setCellValue('K2', "No Kitas WP Terpotong");
		$excel->setActiveSheetIndex(0)->setCellValue('L2', "Kode Negara");
		$excel->setActiveSheetIndex(0)->setCellValue('M2', "Kode Objek Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('N2', "Penanda tangan BP Pengurus ? (Y/N)");
		$excel->setActiveSheetIndex(0)->setCellValue('O2', "Penghasilan Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('P2', "No Dok");
		// $excel->setActiveSheetIndex(0)->setCellValue('Q2', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('Q2', "Perkiraan Penghasilan Neto (%)");
		$excel->setActiveSheetIndex(0)->setCellValue('R2', "Mendapatkan Fasilitas ? (N/SKD/DTP)");
		$excel->setActiveSheetIndex(0)->setCellValue('S2', "Nomor Tanda Terima SKD");
		$excel->setActiveSheetIndex(0)->setCellValue('T2', "Tarif SKD");
		$excel->setActiveSheetIndex(0)->setCellValue('U2', "Nomor Aturan DTP");
		$excel->setActiveSheetIndex(0)->setCellValue('V2', "NTPN DTP");
		$excel->getActiveSheet()->getStyle('A2:V2')->getAlignment()->setWrapText(true);
		$excel->getActiveSheet()->getStyle('A2:V2')->applyFromArray($style_header);
		$excel->getActiveSheet()->getStyle('A2:B2')->getNumberFormat()->setFormatCode($GeneralFormat);
		$excel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode($NumberFormat);
		$excel->getActiveSheet()->getStyle('D2:H2')->getNumberFormat()->setFormatCode($textFormat);
		$excel->getActiveSheet()->getStyle('M2')->getNumberFormat()->setFormatCode($textFormat);
		$excel->getActiveSheet()->getStyle('O2')->getNumberFormat()->setFormatCode($GeneralFormat);
		$excel->getActiveSheet()->getStyle('U2:V2')->getNumberFormat()->setFormatCode($textFormat);

		$hasil = $this->pph->cetak_pph26($year, $month);

		$numrow    = 3;
		$no=1;

		foreach($hasil->result_array() as $row)	{

			if($row['MASA'] > 10){
				$masa = $row['MASA'];
			}elseif($row['MASA'] < 10){
				$masa = str_replace('0', "", $row['MASA']);
			}

			$no_urut_dok = "";
			if($no <10){
				$no_urut_dok = "00".$no;
			}elseif($no >=10 && $no <100){
				$no_urut_dok = "0".$no;
			}else{
				$no_urut_dok = $no;
			}

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $masa);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['TAHUN_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d/m/Y", strtotime($row['INVOICE_DATE'])));
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, dateFormat($row['INVOICE_DATE'], "bupot", false, "id"));
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['TIN']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['TANGGAL_LAHIR']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NO_PASPOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['NO_KITAS_WP']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['KODE_NEGARA']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['KODE_OBJEK_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['PENANDA_TANGAN']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['DPP']);
			// $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $no_urut_dok."/".$row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['PENGHASILAN_NETO']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['FASILITAS']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['SKD']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $row['TARIF_SKD']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $row['S_KET_DTP']);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $row['NTPN_DTP']);

			$excel->getActiveSheet()->getStyle('A'.$numrow.':B'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->getNumberFormat()->setFormatCode($NumberFormat)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow.':H'.$numrow)->getNumberFormat()->setFormatCode($textFormat)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->getNumberFormat()->setFormatCode($textFormat)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('O'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat)->applyFromArray($style_amount);
			$excel->getActiveSheet()->getStyle('P'.$numrow)->getNumberFormat()->setFormatCode($GeneralFormat)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('U'.$numrow.':V'.$numrow)->getNumberFormat()->setFormatCode($textFormat)->applyFromArray($style_row);

			$numrow++;
			$no++;

		}

		// Set width kolom
		/*$loop_column = horizontal_loop_excel("A", 19);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}*/

		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('O')->setWidth(18);
		$excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
		$excel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
		$excel->getActiveSheet()->getColumnDimension('T')->setWidth(20);

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("PPH 26");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PPH 26.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function show_pph_42(){

		    $data['title']          = "PPh 42 SEWA";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/pph42";
			$data['get_exist_year'] = $this->pph->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "PPh 42 Sewa", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_pph42(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year = $this->input->post('year');
		$month = $this->input->post('month');

		$bulan = "";
		if($month <10){
			$bulan = '0'.$month;
		}elseif($month >10){
			$bulan = $month;
		}

		$tahun = substr($year,2,2);
		
		$get_all = $this->pph->get_pph42($year, $month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$no_urut = "";
				if($number < 10){
					$no_urut = '00'.$number;
				}elseif($number >= 10){
					$no_urut = '0'.$number;
				}
				$pph = $value['DPP'] * 10/100;
				$rpl_npwp = str_replace("-","", str_replace(".","", $value['NOMOR_NPWP']));

				$row[] = array(
					'no'           			=> $number,
					'npwp_dipotong'  		=> $rpl_npwp,
					'wp_dipotong'  			=> $value['NAMA_VENDOR'],
					'alamat' 				=> $value['ALAMAT'],
					'nop' 					=> $value['NOP'],
					'lokasi' 				=> $value['LOKASI_SEWA'],
					'no_bupot' 				=> $no_urut."/FINARYA/".$bulan.$tahun."/PPH42",
					'tgl_bupot' 			=> dateFormat($value['INVOICE_DATE'], "bupot", false, "id"),
					'dpp'   				=> number_format($value['DPP'],0,',','.'),
					'pph' 					=> number_format($pph,0,',','.'),
					'npwp_pemotong'  		=> '903134294012000',
					'nama_pemotong'			=> 'PT Fintek Karya Nusantara'
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

	/*public function cetak_pph42(){

		$this->load->helper('csv_helper');

        $year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

        $export_arr = array();      
				
		$title = array("NPWP WP Dipotong", 
						"Nama WP Dipotong", 
						"Alamat WP Dipotong",
						"NOP", 
						"Lokasi",
						"Nomor Bukti Potong", 
						"Tanggal Bukti Potong", 
						"Bruto", 
						"PPh", 
						"Npwp Pemotong", 
						"Nama Pemotong"
		);

        array_push($export_arr, $title);

        $data       = $this->pph->cetak_pph42($year, $month);

        if (!empty($data)) {         
			foreach($data->result_array() as $row)	{

					$pph = $row['DPP'] * 10/100;
					$npwp = str_replace("-","", (str_replace(".","", $row['NOMOR_NPWP'])));
					$nama = rtrim($row['NAMA_PEMOTONG']);

					array_push($export_arr, array(
						$npwp, 
						$row['NAMA_VENDOR'], 
						$row['ALAMAT'],
						$row['NOP'],
						$row['DOMISILI'], 
						'', 
						date("d/m/Y", strtotime($row['INVOICE_DATE'])),
						$row['DPP'], 
						$pph,
						$row['NPWP_PEMOTONG'],
						$nama		
					));				
			}
        }

        convert_to_csv2($export_arr, 'PPh 4(2) Sewa Bangunan .csv', ';');
	}*/

	public function cetak_pph42(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PPh 4(2) Sewa')
		->setLastModifiedBy('')
		->setTitle("PPh 4(2) Sewa")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

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
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "NPWP WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Nama WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Alamat WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "NOP");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Lokasi Sewa");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nomor Bukti Potong");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Tanggal Bukti Potong");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Npwp Pemotong");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Nama Pemotong");

		$hasil = $this->pph->cetak_pph42($year, $month);

		$numrow    = 2;
		$no=1;

		$numberFormat = '0';

		foreach($hasil->result_array() as $row)	{
			$bulan = "";
			if($month < 10){
				$bulan = '0'.$month;
			}elseif($month > 10){
				$bulan = $month;
			}

			$no_urut = "";
			if($no < 10 ){
				$no_urut = '00'.$no;
			}elseif($no >= 10){
				$no_urut = '0'.$no;
			}

			$tahun = substr($year,2,2);
			$rpl_npwp = str_replace("-","",str_replace(".", "", $row['NOMOR_NPWP']));
			$pph = $row['DPP'] * 10/100;

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $rpl_npwp);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NOP']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['LOKASI_SEWA']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $no_urut."/FINARYA/".$bulan.$tahun."/PPH42");
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, dateFormat($row['INVOICE_DATE'], "bupot", false, "id"));
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $pph);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, "903134294012000");
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, "PT Fintek Karya Nusantara");

			$excel->getActiveSheet()->getStyle('B'.$numrow)->getNumberFormat()->setFormatCode($numberFormat);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->getNumberFormat()->setFormatCode($numberFormat);

			$numrow++;
			$no++;		

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 12);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("PPh 4(2) Sewa");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PPh 4(2) Sewa.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function show_pph_42jk(){

		    $data['title']          = "PPh 4(2)";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/pph42jk";
			$data['get_exist_year'] = $this->pph->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "PPh 4(2)", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_pph42jk(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		$get_all = $this->pph->get_pph42jk($year, $month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$cek_pph42 = $this->pph->get_cekpph42($year, $month);

		$no_pph = $cek_pph42->LAST_NUM;
		$no_urutpph = $no_pph+1;

		if($total > 0){

			foreach($data as $value) {

				$bulan = "";
				if($month < 10){
					$bulan = '0'.$month;
				}elseif($month > 10){
					$bulan = $month;
				}
				$tahun = substr($year,2,2);

				$no_urut = "";
				if($no_urutpph < 10){
					$no_urut = '00'.$no_urutpph;
				}elseif($no_urutpph >= 10){
					$no_urut = '0'.$no_urutpph;
				}

				$pph_jkb 	= $value['DPP'] * 2/100;
				$pph_jnkb 	= $value['DPP'] * 3/100;
				$pph_jlb 	= $value['DPP'] * 4/100;
				$pph_pjb 	= $value['DPP'] * 3/100;
				$pph_pjnkb 	= $value['DPP'] * 4/100;
				$rpl_npwp = str_replace("-","", str_replace(".","", $value['NOMOR_NPWP']));

				$row[] = array(
					'no'           			=> $number,
					'npwp_dipotong'  		=> $rpl_npwp,
					'wp_dipotong'  			=> $value['NAMA_VENDOR'],
					'alamat'  				=> $value['ALAMAT'],
					'no_bupot' 				=> $no_urut."/FINARYA/".$bulan.$tahun."/PPH42",
					'tgl_bupot' 			=> dateFormat($value['INVOICE_DATE'], "bupot", false, "id"),
					'dpp' 					=> number_format($value['DPP'],0,',','.'),
					'dpp_null' 				=> "0",
					'pph_jkb' 				=> number_format($pph_jkb,0,',','.'),
					/*'pph_jnkb' 				=> number_format($pph_jnkb,0,',','.'),
					'pph_jlb' 				=> number_format($pph_jlb,0,',','.'),
					'pph_pjb' 				=> number_format($pph_pjb,0,',','.'),
					'pph_pjnkb' 			=> number_format($pph_pjnkb,0,',','.'),*/
					'pph_jnkb' 				=> "0",
					'pph_jlb' 				=> "0",
					'pph_pjb' 				=> "0",
					'pph_pjnkb' 			=> "0",
					'pcn_pgn' 				=> $value['PCN_PGN'],
					'npwp_pemotong' 		=> '903134294012000',
					'nama_pemotong' 		=> 'PT Fintek Karya Nusantara'
				);

				$number++;
				$no_urutpph++;

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);

	}

	/*function cetak_pph42jk() {
        $this->load->helper('csv_helper');

        $year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

        $export_arr = array();      
				
		$title = array("NPWP WP Dipotong", 
						"Nama WP Dipotong", 
						"Alamat WP Dipotong",
						"Nomor Bukti Potong", 
						"Tanggal Bukti Potong", 
						"Pelaksana Jaskon Kecil Bruto",
						"PPh", 
						"Pelaksana Jaskon Non Kualifikasi Bruto", 
						"PPh", 
						"Pelaksana Jaskon Lainnya Bruto", 
						"PPh", 
						"Perencana/Pengawas Jaskon Bruto", 
						"PPh", 
						"Perencanaan/Pengawasan", 
						"Perencana/Pengawas Jaskon Non Kualifikasi Bruto", 
						"PPh", 
						"Perencanaan/Pengawasan", 
						"Npwp Pemotong", 
						"Nama Pemotong"
		);

        array_push($export_arr, $title);

        $data       = $this->pph->get_pph42jk_csv($year, $month);

        if (!empty($data)) {         
			foreach($data->result_array() as $row)	{

					$pph_jkb 	= $row['DPP'] * 2/100;
					$pph_jnkb 	= $row['DPP'] * 3/100;
					$pph_jlb 	= $row['DPP'] * 4/100;
					$pph_pjb 	= $row['DPP'] * 3/100;
					$pph_pjnkb 	= $row['DPP'] * 4/100;
					$nama = rtrim($row['NAMA_PEMOTONG']);

					array_push($export_arr, array(
						$row['NOMOR_NPWP'], 
						$row['NAMA_VENDOR'], 
						$row['ALAMAT'], 
						$row['NO_BUPOT'],
						$row['TGL_BUPOT'], 
						$row['DPP'], 
						$pph_jkb, 
						$row['DPP'], 
						$pph_jnkb,
						$row['DPP'],
						$pph_jlb,
						$row['DPP'],
						$pph_pjb,
						$row['PCN_PGN'],
						$row['DPP'],
						$pph_pjnkb,
						$row['PCN_PGN'],				
						$row['NPWP_PEMOTONG'],			
						$nama		
					));				
			}
        }

        convert_to_csv2($export_arr, 'PPh 4(2)J. Konstruksi .csv', ';');
    }*/

    public function cetak_pph42jk(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PPh 4(2)J. Konstruksi')
		->setLastModifiedBy('')
		->setTitle("PPh 4(2)J. Konstruksi")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

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
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NPWP WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Nama WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Alamat WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Nomor Bukti Potong");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Tanggal Bukti Potong");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Pelaksana Jaskon Kecil Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Pelaksana Jaskon Non Kualifikasi Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Pelaksana Jaskon Lainnya Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Perencana/Pengawas Jaskon Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Perencanaan/Pengawasan");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Perencana/Pengawas Jaskon Non Kualifikasi Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Perencanaan/Pengawasan");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Npwp Pemotong");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Nama Pemotong");

		$hasil = $this->pph->get_pph42jk_csv($year, $month);
		$cek_pph42 = $this->pph->get_cekpph42($year, $month);

		$no_pph = $cek_pph42->LAST_NUM;
		$no_urutpph = $no_pph+1;

		$numrow    = 2;
		$no=1;
		$numberFormat = '0';

		foreach($hasil->result_array() as $row)	{
			$bulan = "";
			if($month < 10){
				$bulan = '0'.$month;
			}elseif($month > 10){
				$bulan = $month;
			}
			$tahun = substr($year,2,2);

			$no_urut = "";
			if($no_urutpph < 10){
				$no_urut = '00'.$no_urutpph;
			}elseif($no_urutpph >= 10){
				$no_urut = '0'.$no_urutpph;
			}
			$rpl_npwp = str_replace("-","",str_replace(".", "", $row['NOMOR_NPWP']));
			$pph_jkb 	= $row['DPP'] * 2/100;
			$pph_jnkb 	= $row['DPP'] * 3/100;
			$pph_jlb 	= $row['DPP'] * 4/100;
			$pph_pjb 	= $row['DPP'] * 3/100;
			$pph_pjnkb 	= $row['DPP'] * 4/100;

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $rpl_npwp);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $no_urut."/FINARYA/".$bulan.$tahun."/PPH42");
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, dateFormat($row['INVOICE_DATE'], "bupot", false, "id"));
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $pph_jkb);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['DPP']);
			/*$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $pph_jnkb);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $pph_jlb);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $pph_pjb);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['PCN_PGN']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $pph_pjnkb);*/
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['PCN_PGN']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, '903134294012000');
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow,'PT Fintek Karya Nusantara');

			$excel->getActiveSheet()->getStyle('A'.$numrow)->getNumberFormat()->setFormatCode($numberFormat);
			$excel->getActiveSheet()->getStyle('R'.$numrow)->getNumberFormat()->setFormatCode($numberFormat);

			$numrow++;
			$no++;
			$no_urutpph++;	

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 20);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("PPh 4(2)J. Konstruksi");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PPh 4(2)J. Konstruksi.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

    public function show_pph_42_pp23(){

		    $data['title']          = "PP 23";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/pph42_pp23";
			$data['get_exist_year'] = $this->pph->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "PP 23", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_pph42_pp23(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		$get_all = $this->pph->get_pph42_pp23($year, $month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'           			=> $number,
					'npwp_dipotong'  		=> $value['NOMOR_NPWP'],
					'wp_dipotong'  			=> $value['NAMA_VENDOR'],
					'alamat' 				=> $value['ALAMAT'],
					'ntpn' 					=> $value['NTPN'],
					'tgl_ntpn' 				=> $value['TGL_NTPN'],
					'dpp'   				=> number_format($value['DPP'],0,',','.'),
					'pph' 					=> number_format($value['PPH'],0,',','.'),
					'sket_pp23' 			=> $value['S_KET_PP23'],
					'npwp_pemotong'  		=> '903134294012000',
					'nama_pemotong'			=> 'PT Fintek Karya Nusantara'
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

	/*public function cetak_pph42_pp23(){

		$this->load->helper('csv_helper');

        $year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

        $export_arr = array();      
				
		$title = array("NPWP WP Dipotong", 
						"Nama WP Dipotong", 
						"Alamat WP Dipotong",
						"NTPN", 
						"Tanggal NTPN", 
						"Bruto",
						"PPh",
						"Npwp Pemotong", 
						"Nama Pemotong"
		);

        array_push($export_arr, $title);

        $data       = $this->pph->get_pph42_pp23_csv($year, $month);

        if (!empty($data)) {         
			foreach($data->result_array() as $row)	{

				$rpl_npwp_dp_ttk = str_replace(".","", $row['NOMOR_NPWP']);
				$rpl_npwp_dp_ok  = str_replace("-","", $rpl_npwp_dp_ttk);

				$rpl_npwp_pm_ttk = str_replace(".","", $row['NPWP_PEMOTONG']);
				$rpl_npwp_pm_ok  = str_replace("-","", $rpl_npwp_pm_ttk);
				$nama = rtrim($row['NAMA_PEMOTONG']);

					array_push($export_arr, array(
						$rpl_npwp_dp_ok, 
						$row['NAMA_VENDOR'], 
						$row['ALAMAT'], 
						$row['NTPN'], 
						$row['TGL_NTPN'],
						number_format($row['DPP'],0,'',''), 
						$row['PPH'],				
						$rpl_npwp_pm_ok,			
						$nama		
					));				
			}
        }

        convert_to_csv2($export_arr, 'PPh 4(2) PP23 .csv', ';');
	}*/

	public function cetak_pph42_pp23(){

		ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PP 23')
		->setLastModifiedBy('')
		->setTitle("PP 23")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

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
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "NPWP WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Nama WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Alamat WP Dipotong");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "NTPN");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Tanggal NTPN");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Bruto");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "PPh");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "S.Ket PP 23");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Npwp Pemotong");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Nama Pemotong");

		$hasil = $this->pph->get_pph42_pp23_csv($year, $month);

		$numrow    = 2;
		$no=1;
		$NumberFormat = "0";

		foreach($hasil->result_array() as $row)	{
			$rpl_npwp = str_replace("-","",str_replace(".", "", $row['NOMOR_NPWP']));

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $rpl_npwp);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NTPN']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['TGL_NTPN']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, number_format($row['DPP'],0,'',''));
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['PPH']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['S_KET_PP23']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, "903134294012000");
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, "PT Fintek Karya Nusantara");

			$excel->getActiveSheet()->getStyle('B'.$numrow)->getNumberFormat()->setFormatCode($NumberFormat);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->getNumberFormat()->setFormatCode($NumberFormat);

			$numrow++;
			$no++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 11);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("PP 23");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PP 23.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function show_ppn_masukan(){

		    $data['title']          = "PPN Masukan";
			$data['module']         = "datatable";
			$data['template_page']  = "report_xls/ppn_masukan";
			$data['get_exist_year'] = $this->pph->get_exist_year();

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "PPN Masukan", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);
	}

	public function load_ppn_masukan(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$year = $this->input->post('year');
		$month = $this->input->post('month');
		
		$get_all = $this->pph->get_ppn_masukan($year, $month);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$rpl_npwp = str_replace("-","", str_replace(".","", $value['NOMOR_NPWP']));

				$tgl_faktur = "";
				if($value['TGL_FAKTUR_PAJAK'] == "0000-00-00"){
					$tgl_faktur = "0000-00-00";
				}elseif($value['TGL_FAKTUR_PAJAK'] !=""){
					$tgl_faktur = date("d/m/Y", strtotime($value['TGL_FAKTUR_PAJAK']));
				}

				$row[] = array(
					'no'           			=> $number,
					'fm'  					=> "FM",
					'kd_jenis'  			=> $value['KD_JENIS'],
					'no_faktur' 			=> $value['NO_FAKTUR'],
					'fg_pengganti' 			=> $value['FG_PENGGANTI'],
					'masa_pajak' 			=> $value['MASA'],
					'tahun_pajak' 			=> $value['TAHUN_PAJAK'],
					'tgl_faktur'   			=> $tgl_faktur,
					'npwp' 					=> $rpl_npwp,
					'nama'  				=> $value['NAMA_VENDOR'],
					'alamat'				=> $value['ALAMAT'],
					'dpp'					=> number_format($value['DPP'],0,',','.'),
					'ppn'					=> number_format($value['PPN'],0,',','.'),
					'ppnbm'					=> 0,
					'is_creditable'			=> ""
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

	public function cetak_ppn_masukan(){

		/*$this->load->helper('csv_helper');

        $year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

        $export_arr = array();      
				
		$title = array("FM", 
						"KD_JENIS_TRANSAKSI", 
						"FG_PENGGANTI",
						"NOMOR_FAKTUR", 
						"MASA_PAJAK", 
						"TAHUN_PAJAK",
						"TGL_FAKTUR_PAJAK",
						"NPWP", 
						"NAMA",
						"ALAMAT_LENGKAP",
						"JUMLAH_DPP",
						"JUMLAH_PPN",
						"JUMLAH_PPNBM",
						"IS_CREDITABLE"
		);

        array_push($export_arr, $title);

        $data       = $this->pph->get_ppn_masukan_csv($year, $month);

        if (!empty($data)) {         
			foreach($data->result_array() as $row)	{

				$rpl_npwp = str_replace("-","", str_replace(".","", $row['NOMOR_NPWP']));

					array_push($export_arr, array(
						"FM", 
						$row['KD_JENIS'], 
						$row['FG_PENGGANTI'], 
						$row['NO_FAKTUR'],
						$row['MASA'],
						$row['TAHUN_PAJAK'], 
						($row['TGL_FAKTUR_PAJAK'] == '0000-00-00') ? '' : $row['TGL_FAKTUR_PAJAK'],		
						$rpl_npwp,	
						$row['NAMA_VENDOR'],	
						$row['ALAMAT'],	
						number_format($row['DPP'],0,'',''),	
						number_format($row['PPN'],0,'',''),	
						0,	
						""	
					));			
			}
        }

        convert_to_csv2($export_arr, 'PPN Masukan .csv', ';');*/

        ini_set('memory_limit', '-1');

		include APPPATH.'third_party/PHPExcel.php';

		$excel = new PHPExcel();

		$excel->getProperties()	->setCreator('PPN Masukan')
		->setLastModifiedBy('')
		->setTitle("PPN Masukan")
		->setSubject("Cetakan")
		->setDescription("Cetak Report")
		->setKeywords("Report");

		$year 		= $_REQUEST['year'];
		$month 		= $_REQUEST['month'];

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
		  )
		);

		$style_amount = array(
		   'alignment' => array(
		  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		  )
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "FM");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Kode Jenis Transaksi");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "FG Pengganti");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Nomor Faktur");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Masa Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Tahun Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Tanggal Faktur");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "NPWP");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Nama");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Alamat Lengkap");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Jumlah DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Jumlah PPN");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Jumlah PPNBM");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Is Creditable
");

		$hasil = $this->pph->get_ppn_masukan_csv($year, $month);

		$numrow    = 2;
		$no=1;
		$numberFormat = '0';

		foreach($hasil->result_array() as $row)	{

			$rpl_npwp = str_replace("-","",str_replace(".", "", $row['NOMOR_NPWP']));

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, "FM");
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['KD_JENIS']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['FG_PENGGANTI']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NO_FAKTUR']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['MASA']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['TAHUN_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, ($row['TGL_FAKTUR_PAJAK'] == '0000-00-00') ? '0000-00-00' : $row['TGL_FAKTUR_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $rpl_npwp);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['ALAMAT']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, number_format($row['DPP'],0,'',''));
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, number_format($row['PPN'],0,'',''));
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, "0");
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, "");

			$excel->getActiveSheet()->getStyle('I'.$numrow)->getNumberFormat()->setFormatCode($numberFormat);

			$numrow++;
			$no++;	

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 15);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("PPN Masukan");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PPN Masukan.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

}