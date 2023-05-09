<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function index()
	{
		
	}

	


	public function print_fpjp($fpjp_header_id){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$this->load->model('fpjp_mdl','fpjp');
		$decrypt        = decrypt_string($fpjp_header_id, true);
		$fpjp_header_id = (int) $decrypt;

		if($fpjp_header_id == 0){
			redirect('fpjp','refresh');
			exit;
		}

		$mpdf = new \Mpdf\Mpdf();

		//$fh = 'assets/templates/formfpjpfinarya.pdf';
		$fh = 'assets/templates/cetakan_justif.pdf';

		$hasil = $this->fpjp->get_header($fpjp_header_id);
		$data = $hasil['data'];

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);
		$mpdf->Image('assets/img/fintek.jpg',15,10,35);

		$mpdf->SetFont('Roboto','',11);
		$height = 23;
		$mpdf->SetXY(60, $height);
		$mpdf->Cell(10, 10, "FORMULIR PERTANGGUNG JAWABAN PENGELUARAN",0,"");

		$mpdf->SetFont('Roboto','',6);
		$height = 6;
		$mpdf->SetXY(100, $height);
		$mpdf->Cell(10, 10, "FPJP Report",0,"");

		$id_fs = 0;

		$jabatan_sub = "";
		$title = "";

		foreach ($data as $row){

			$submitter             = $row['SUBMITTER'];
			$jabatan_sub           = $row['JABATAN_SUBMITTER'];
			$diketahui1            = $row['DIKETAHUI_1'];
			$diketahui2            = $row['DIKETAHUI_2'];
			$pemilik               = $row['PEMILIK_REKENING'];
			$currency              = $row['CURRENCY'];
			$fpjp_amount           = $row['FPJP_AMOUNT'];
			$fpjp_nama_vendor      = $row['FPJP_VENDOR_NAME'];
			$fpjp_pemilik_rekening = $row['FPJP_ACC_NAME'];
			$fpjp_nama_bank        = $row['FPJP_BANK_NAME'];
			$fpjp_account_number   = $row['FPJP_ACC_NUMBER'];

			$id_fs = ($row['ID_FS'] != 0) ? $row['ID_FS'] : $row['ID_FS2'];

			$mpdf->SetTextColor(0,0,0);
			$mpdf->SetFont('Roboto','',5.5);

			$guideline = 0;

			$title = "FPJP " . $row['FPJP_NUMBER'] . " - " . $row['FPJP_NAME'];

		}
		$jabatan_sub = str_replace("&amp;", "&", $jabatan_sub);

        $mpdf->SetTitle($title);

		$mpdf->SetTextColor(0,0,0);
		$guideline = 0;

		$mpdf->SetFont('Roboto','',5.5);
		$height = 39.5;
		$mpdf->SetXY(13.3, $height);
		$mpdf->Cell(11.6,10,"Nomor FPJP :",$guideline,1,"R");

		$height = 39.5;
		$mpdf->SetXY(24, $height);
		$mpdf->Cell(10,10,$row['FPJP_NUMBER'],$guideline,1,"L");

		$height = 42;
		$mpdf->SetXY(13.3, $height);
		$mpdf->Cell(11.6,10,"FPJP Type :",$guideline,1,"R");

		$height = 42;
		$mpdf->SetXY(24, $height);
		$mpdf->Cell(10,10, $row['FPJP_TYPE'],$guideline,1,"L");

		$height = 45;
		$mpdf->SetXY(13.3, $height);
		$mpdf->Cell(11.6,10,"Tanggal :",$guideline,1,"R");

		$height = 45;
		$mpdf->SetXY(24, $height);
		$mpdf->Cell(10,10, dateFormat($row['FPJP_DATE'], 4, false),$guideline,1,"L");

		$height = 39.5;
		$mpdf->SetXY(166, $height);
		$mpdf->Cell(8,10,"Nama/NIK :",$guideline,1,"R");

		$height = 39.5;
		$mpdf->SetXY(173, $height);
		$mpdf->Cell(10,10,$row['SUBMITTER'],$guideline,1,"L");

		$height = 42.5;
		$mpdf->SetXY(166, $height);
		$mpdf->Cell(8,10,"Jabatan :",$guideline,1,"R");

		$jabatan_upln = strlen($row['JABATAN_SUBMITTER']);
		if($jabatan_upln <= 30){
			$height = 42.5;
			$mpdf->SetXY(173, $height);
			$mpdf->Cell(10,10,substr($row['JABATAN_SUBMITTER'],0,30),$guideline,1,"L");
		}else{
			$height = 42.5;
			$mpdf->SetXY(173, $height);
			$mpdf->Cell(10,10,substr($row['JABATAN_SUBMITTER'],0,30),$guideline,1,"L");

			$height = 45;
			$mpdf->SetXY(173, $height);
			$mpdf->Cell(10,10,substr($row['JABATAN_SUBMITTER'],30,60),$guideline,1,"L");
		}

		$heightd = $height+2.5;
		$mpdf->SetXY(166, $heightd);
		$mpdf->Cell(8,10,"Divisi :",$guideline,1,"R");

		$divisi = strlen($row['DIVISION_NAME']);
		if($divisi <= 30){
			$heightstr = $heightd;
			$mpdf->SetXY(173, $heightstr);
			$mpdf->Cell(10,10,substr($row['DIVISION_NAME'],0,30),$guideline,1,"L");
		}else{
			$heightstr = $heightd;
			$mpdf->SetXY(173, $heightstr);
			$mpdf->Cell(10,10,substr($row['DIVISION_NAME'],0,30),$guideline,1,"L");

			$heightstr2 = $heightstr+2.5;
			$mpdf->SetXY(173, $heightstr2);
			$mpdf->Cell(10,10,substr($row['DIVISION_NAME'],30,60),$guideline,1,"L");
		}
		if($id_fs > 0){

			$get_justif = $this->crud->read_by_param("FS_BUDGET", array("ID_FS" => $id_fs));
			$justification = $get_justif['FS_NUMBER'] . " - " .  $get_justif['FS_NAME'];
			$mpdf->SetXY(11.6, 47.8);
			$mpdf->Cell(11.6,10,"Justification :  ".$justification,$guideline,2,"L");

		}else{
			$mpdf->SetXY(11.6, 47.8);
			$mpdf->Cell(11.6,10,"Justification :  Non Justif",$guideline,2,"L");
		}

		$mpdf->SetFont('Courier New','',8);
		$height = 55;
		$mpdf->SetXY(15, $height);
		$mpdf->Cell(10,10,"Mohon dapat dibayarkan pengeluaran untuk:",$guideline,1,"L");

        $cell_1 = 10;
        $cell_2 = 55;
        $cell_3 = 65;
        $cell_4 = 20;
        $cell_5 = 30;

        $mpdf->Cell($cell_1,6,'No',1,0,'C');
        $mpdf->Cell($cell_2,6,'Keterangan',1,0,'C');
        $mpdf->Cell($cell_3,6,'Kode Akun',1,0,'C');
        $mpdf->Cell($cell_4,6,'Mata Uang',1,0,'C');
        $mpdf->Cell($cell_5,6,'Jumlah',1,1,'C');

        $mpdf->SetFont('Courier New','',8);
        // $hasil_detail = $this->fpjp->get_cetak($fpjp_header_id);
		// $data_detail = $hasil_detail['data'];
        $data_detail = $this->fpjp->get_detail_pdf($fpjp_header_id);
        $no=1;

        $rowHeight = 0;

        $generateNewHeightY = 0;

        $total = count($data_detail);

        if($total < 3){
        	$generateNewHeightY = 15;
        }
        elseif($total < 7){
        	$generateNewHeightY = 5;
        }
        elseif($total < 10){
        	$generateNewHeightY = 0;
        }else{
        	$generateNewHeightY -= 5;
        }

        foreach ($data_detail as $val){
            $rowHeight += 6;

			$heightY   = 6;
			$detail    = $val['FPJP_DETAIL_DESC'];
			$nature    = $val['NATURE'] ." - " .$val['DESCRIPTION'];
			$detail_ln = strlen($detail)." ";
			$nature_ln = strlen($nature)." ";

        	if($detail_ln > 30 || $nature_ln > 30){
        		// $heightY +=6;
				$detail = str_replace(" ", " |", $detail);
				$newDetail = explode("|",$detail);
				$newLine = "";

				$strC = 0;
				$lastDetail = "";
				$detaillinebreak = "";
				$lastdetaillinebreak = "";

				for ($i=0; $i < count($newDetail); $i++) 
				{
					$str = strlen($newDetail[$i]);
					$strC += $str;

					if($strC > 60)
					{
						$i_last = $i;
						$lastdetaillinebreak .= $newLine.$newDetail[$i];
					}
					else if ($strC > 30 && $strC <= 60)
					{
						$i_last = $i;
						$detaillinebreak .= $newLine.$newDetail[$i];
					}
					else
					{
						$lastDetail .= $newLine.$newDetail[$i];
					}

				}

				$nature = str_replace(" ", " |", $nature);
				$newNature = explode("|",$nature);
				$newLine = "";

				$strC = 0;
				$lastNature = "";
				$natureLineBreak = "";
				$lastnatureLineBreak = "";

				for ($i=0; $i < count($newNature); $i++) 
				{
					$str = strlen($newNature[$i]);
					$strC += $str;

					if($strC > 60)
					{
						$i_last = $i;
						$lastnatureLineBreak .= $newLine.$newNature[$i];
					}
					else if ($strC > 30 && $strC <= 60)
					{
						$i_last = $i;
						$natureLineBreak .= $newLine.$newNature[$i];
					}
					else
					{
						$lastNature .= $newLine.$newNature[$i];
					}

				}
			}

			
			if($detail_ln > 60 || $nature_ln > 60)
			{

				$mpdf->Cell($cell_1,$heightY, $no,"R L",0,'C');
				$mpdf->Cell($cell_2,$heightY, $lastDetail,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $lastNature,"R L",0,'L');
				$mpdf->Cell($cell_4,$heightY, $currency, "R L",0,'C');
				$mpdf->Cell($cell_5,$heightY,number_format($val['FPJP_DETAIL_AMOUNT'],0,',','.'),"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY, " ","R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $detaillinebreak,"R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY, $natureLineBreak,"R L",0,'L');
				$mpdf->Cell($cell_4,$newHeightY, " ","R L",0,'L');
				$mpdf->Cell($cell_5,$newHeightY, " ","R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY, " ","B R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $lastdetaillinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY, $lastnatureLineBreak,"B R L",0,'L');
				$mpdf->Cell($cell_4,$newHeightY, " ","B R L",0,'L');
				$mpdf->Cell($cell_5,$newHeightY, " ","B R L",1,'C');

			}
			else if ( ($detail_ln > 30 && $detail_ln <=60) || ($nature_ln > 30 && $nature_ln <=60))
			{

				$mpdf->Cell($cell_1,$heightY, $no,"R L",0,'C');
				$mpdf->Cell($cell_2,$heightY, $lastDetail,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $lastNature,"R L",0,'L');
				$mpdf->Cell($cell_4,$heightY, $currency, "R L",0,'C');
				$mpdf->Cell($cell_5,$heightY,number_format($val['FPJP_DETAIL_AMOUNT'],0,',','.'),"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY, " ","B R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $detaillinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY, $natureLineBreak,"B R L",0,'L');
				$mpdf->Cell($cell_4,$newHeightY, " ","B R L",0,'L');
				$mpdf->Cell($cell_5,$newHeightY, " ","B R L",1,'C');
			}
			else{

				$mpdf->Cell($cell_1,$heightY, $no,1,0,'C');
	            $mpdf->Cell($cell_2,$heightY, $detail,1,0,'L');
	            $mpdf->Cell($cell_3,$heightY, $nature,1,0,'L');
	            $mpdf->Cell($cell_4,$heightY, $currency, 1,0,'C');
	            $mpdf->Cell($cell_5,$heightY, number_format($val['FPJP_DETAIL_AMOUNT'],0,',','.'),1,1,'C');
			}
			$generateNewHeightY += 10;

			$no++;
        }

        $data_ppn = $this->fpjp->get_tax_pdf($fpjp_header_id);

        $amount_ppn = 0;

        if($data_ppn['TOTAL_TAX'] > 0):

        	$amount_ppn += ($data_ppn['AMOUNT_PPN'] > 0) ? $data_ppn['AMOUNT_PPN'] : $data_ppn['TOTAL_AMOUNT_TAX'];

	    	$mpdf->Cell($cell_1,6,$no,1,0,'C');
	        $mpdf->Cell($cell_2,6,"PPN ".$data_ppn['TOTAL_TAX'] ."%",1,0,'L');
	        $mpdf->Cell($cell_3,6, "11512001 - Prepaid Taxes VAT In",1,0,'L');
	        $mpdf->Cell($cell_4,6, $currency,1,0,'C');
	        $mpdf->Cell($cell_5,6, number_format($amount_ppn,0,',','.'),1,1,'C');

	        $no++;
    	endif;

		$generateNewHeightY += 15;
        $generateNewHeightY += 9;

        $height += $generateNewHeightY;

        $mpdf->SetFont('Courier New','',8);

		$mpdf->Cell(10,5,"",0,1,"L");

		$jumlah_desc = ($amount_ppn > 0) ? "Jumlah Setelah PPN" : "Jumlah";

        $mpdf->Cell(20,6,'Mata Uang',1,0,'C');
        $mpdf->Cell(30,6, $jumlah_desc,"B T",0,'C');
        $mpdf->Cell(80,6,'Terbilang',"L B T",0,'C');
        $mpdf->Cell(50,6,'Nomor Rekening, Nama Bank & a/n',1,1,'C');

        $mpdf->SetFont('Courier New','',8);
        //$data = $this->db->get('FPJP_HEADER')->result();
        $hasil_detail = $this->fpjp->get_total($fpjp_header_id);
		$data_detail = $hasil_detail['data'];
        $no=1;
        $addedHeight = 20;


        foreach ($data_detail as $val){
			/*$total          = ($val['FPJP_DETAIL_AMOUNT'] * 10/100) + $val['FPJP_DETAIL_AMOUNT'];*/
			// $total            = $val['PPN'];
			$total            = $val['FPJP_DETAIL_AMOUNT']+$amount_ppn;
			//$vatsa 			  = $fpjp_amount * ($val['VATSA']/100);
			$no_rekening      = ($fpjp_account_number) ? $fpjp_account_number : $val['NO_REKENING'];
			$nama_bank        = ($fpjp_nama_bank) ? $fpjp_nama_bank : $val['NAMA_BANK'];
			$pemilik_rekening = ($fpjp_pemilik_rekening) ? $fpjp_pemilik_rekening : $val['PEMILIK_REKENING'];
			$heightY          = 6;
			$nominal          = number_format($total,0,',','.');
			$terbilang        = terbilang($nominal);
			if(strlen($terbilang) > 50){
				$terbilang       = str_replace(" ", " |", $terbilang);
				$newTerbilang    = explode("|",$terbilang);
				$strC            = 0;
				$firstTerbilang  = $secondTerbilang = $thirdTerbilang  = "";
				for ($i=0; $i < count($newTerbilang); $i++) 
				{
					$strC += strlen($newTerbilang[$i]);
					if($strC > 100):
						$thirdTerbilang .= $newTerbilang[$i];
					elseif($strC > 50 && $strC <= 100):
						$secondTerbilang .= $newTerbilang[$i];
					else:
						$firstTerbilang .= $newTerbilang[$i];
					endif;
				}

			}

			
			if(strlen($terbilang) > 100) {

				$mpdf->Cell(20, $heightY, $currency, "R L", 0, "C");
				$mpdf->Cell(30, $heightY, $nominal, "R L", 0, "R");
				$mpdf->Cell(80, $heightY, ucfirst($firstTerbilang), "R L", 0, "L");
				$mpdf->Cell(50, $heightY, $no_rekening, "R L",1, "L");

				if(strlen($nama_bank) > 30){

					$nama_bank      = str_replace(" ", " |", $nama_bank);
					$newNamaBank    = explode("|",$nama_bank);
					$strC           = 0;
					$firstNamaBank  = "";
					$secondNamaBank = "";
					for ($i=0; $i < count($newNamaBank); $i++) 
					{
						$strC += strlen($newNamaBank[$i]);
						if($strC > 30):
							$secondNamaBank .= $newNamaBank[$i];
						else:
							$firstNamaBank .= $newNamaBank[$i];
						endif;
					}

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R L", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang, "R L", 0, "L");
					$mpdf->Cell(50, $heightY, $firstNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R L", 0, "R");
					$mpdf->Cell(80, $heightY, ltrim($thirdTerbilang." rupiah", " "), "R L", 0, "L");
					$mpdf->Cell(50, $heightY, $secondNamaBank, "R L",1, "L");

					if(strlen($pemilik_rekening) > 30){

						$pemilik_rekening      = str_replace(" ", " |", $pemilik_rekening);
						$newPemilikRekening    = explode("|",$pemilik_rekening);
						$strC           = 0;
						$firstPemilikRekening  = "";
						$secondPemilikRekening = "";
						for ($i=0; $i < count($newPemilikRekening); $i++) 
						{
							$strC += strlen($newPemilikRekening[$i]);
							if($strC > 30):
								$secondPemilikRekening .= $newPemilikRekening[$i];
							else:
								$firstPemilikRekening .= $newPemilikRekening[$i];
							endif;
						}

						$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
						$mpdf->Cell(30, $heightY, "", "R", 0, "R");
						$mpdf->Cell(80, $heightY, "", "", 0, "L");
						$mpdf->Cell(50, $heightY, $firstPemilikRekening, "R L",1, "L");

						$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
						$mpdf->Cell(30, $heightY, "", "B R L", 0, "R");
						$mpdf->Cell(80, $heightY, "", "B R L", 0, "L");
						$mpdf->Cell(50, $heightY, $secondPemilikRekening, "B R L", 1, "L");
						$addedHeight = 30;
					}
					else{
						$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
						$mpdf->Cell(30, $heightY, "", "B R L", 0, "R");
						$mpdf->Cell(80, $heightY, "", "B R L", 0, "L");
						$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");
					}

					$addedHeight = 30;
				}
				else{
					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R L", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang, "R L", 0, "L");
					$mpdf->Cell(50, $heightY, $nama_bank, "R L", 1, "L");

					if(strlen($pemilik_rekening) > 30){

						$pemilik_rekening      = str_replace(" ", " |", $pemilik_rekening);
						$newPemilikRekening    = explode("|",$pemilik_rekening);
						$strC           = 0;
						$firstPemilikRekening  = "";
						$secondPemilikRekening = "";
						for ($i=0; $i < count($newPemilikRekening); $i++) 
						{
							$strC += strlen($newPemilikRekening[$i]);
							if($strC > 30):
								$secondPemilikRekening .= $newPemilikRekening[$i];
							else:
								$firstPemilikRekening .= $newPemilikRekening[$i];
							endif;
						}

						$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
						$mpdf->Cell(30, $heightY, "", "R", 0, "R");
						$mpdf->Cell(80, $heightY, ltrim($thirdTerbilang." rupiah", " "), "", 0, "L");
						$mpdf->Cell(50, $heightY, $firstPemilikRekening, "R L",1, "L");

						$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
						$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
						$mpdf->Cell(80, $heightY, "", "B", 0, "L");
						$mpdf->Cell(50, $heightY, $secondPemilikRekening, "B R L", 1, "L");
						$addedHeight = 30;
					}
					else{
						$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
						$mpdf->Cell(30, $heightY, "", "B R L", 0, "R");
						$mpdf->Cell(80, $heightY, ltrim($thirdTerbilang." rupiah", " "), "B R L", 0, "L");
						$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");
					}
				}
			}
			else if (strlen($terbilang) > 50 && strlen($terbilang) <= 100) {

				$mpdf->Cell(20, $heightY, $currency, "R L", 0, "C");
				$mpdf->Cell(30, $heightY, $nominal, "R", 0, "R");
				$mpdf->Cell(80, $heightY, ucfirst($firstTerbilang), "", 0, "L");
				$mpdf->Cell(50, $heightY, $no_rekening, "R L",1, "L");

				if(strlen($nama_bank) > 30){

					$nama_bank      = str_replace(" ", " |", $nama_bank);
					$newNamaBank    = explode("|",$nama_bank);
					$strC           = 0;
					$firstNamaBank  = "";
					$secondNamaBank = "";
					for ($i=0; $i < count($newNamaBank); $i++) 
					{
						$strC += strlen($newNamaBank[$i]);
						if($strC > 30):
							$secondNamaBank .= $newNamaBank[$i];
						else:
							$firstNamaBank .= $newNamaBank[$i];
						endif;
					}
					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang." rupiah", "", 0, "L");
					$mpdf->Cell(50, $heightY, $firstNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $secondNamaBank, "R L",1, "L");
					$addedHeight = 30;
				}
				else{
					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, $secondTerbilang." rupiah", "", 0, "L");
					$mpdf->Cell(50, $heightY, $nama_bank, "R L",1, "L");
				}


				if(strlen($pemilik_rekening) > 30){

					$pemilik_rekening      = str_replace(" ", " |", $pemilik_rekening);
					$newPemilikRekening    = explode("|",$pemilik_rekening);
					$strC           = 0;
					$firstPemilikRekening  = "";
					$secondPemilikRekening = "";
					for ($i=0; $i < count($newPemilikRekening); $i++) 
					{
						$strC += strlen($newPemilikRekening[$i]);
						if($strC > 30):
							$secondPemilikRekening .= $newPemilikRekening[$i];
						else:
							$firstPemilikRekening .= $newPemilikRekening[$i];
						endif;
					}

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $firstPemilikRekening, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "B", 0, "L");
					$mpdf->Cell(50, $heightY, $secondPemilikRekening, "B R L", 1, "L");
					$addedHeight = 30;
				}
				else{
					$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "B", 0, "L");
					$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");
				}

			}
			else{

				$mpdf->Cell(20, $heightY, $currency, "R L", 0, "C");
				$mpdf->Cell(30, $heightY, $nominal, "R", 0, "R");
				$mpdf->Cell(80, $heightY, ucfirst($terbilang)." rupiah", "", 0, "L");
				$mpdf->Cell(50, $heightY, $no_rekening, "R L",1, "L");

				if(strlen($nama_bank) > 30){

					$nama_bank      = str_replace(" ", " |", $nama_bank);
					$newNamaBank    = explode("|",$nama_bank);
					$strC           = 0;
					$firstNamaBank  = "";
					$secondNamaBank = "";
					for ($i=0; $i < count($newNamaBank); $i++) 
					{
						$strC += strlen($newNamaBank[$i]);
						if($strC > 30):
							$secondNamaBank .= $newNamaBank[$i];
						else:
							$firstNamaBank .= $newNamaBank[$i];
						endif;
					}

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $firstNamaBank, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $secondNamaBank, "R L",1, "L");
					$addedHeight = 30;
				}
				else{

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $nama_bank, "R L",1, "L");
				}

				if(strlen($pemilik_rekening) > 30){

					$pemilik_rekening      = str_replace(" ", " |", $pemilik_rekening);
					$newPemilikRekening    = explode("|",$pemilik_rekening);
					$strC           = 0;
					$firstPemilikRekening  = "";
					$secondPemilikRekening = "";
					for ($i=0; $i < count($newPemilikRekening); $i++) 
					{
						$strC += strlen($newPemilikRekening[$i]);
						if($strC > 30):
							$secondPemilikRekening .= $newPemilikRekening[$i];
						else:
							$firstPemilikRekening .= $newPemilikRekening[$i];
						endif;
					}

					$mpdf->Cell(20, $heightY, "", "R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "", 0, "L");
					$mpdf->Cell(50, $heightY, $firstPemilikRekening, "R L",1, "L");

					$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "B", 0, "L");
					$mpdf->Cell(50, $heightY, $secondPemilikRekening, "B R L", 1, "L");
					$addedHeight = 30;
				}
				else{
					$mpdf->Cell(20, $heightY, "", "B R L", 0, "C");
					$mpdf->Cell(30, $heightY, "", "B R", 0, "R");
					$mpdf->Cell(80, $heightY, "", "B", 0, "L");
					$mpdf->Cell(50, $heightY, $pemilik_rekening, "B R L", 1, "L");
				}

			}

			$no++;
		}

		$data_vatsa = $this->fpjp->get_vatsa($fpjp_header_id);

		$vatsa = $data_vatsa['TOTAL_VATSA'];

		if ($data_vatsa['VATSA'] == 10){
			$mpdf->Cell(10, 10, "V.A.T.S.A  dibayarkan kepada kas Negara : ".number_format($vatsa),0,',','.',$guideline,0,"L");
			$mpdf->Cell(10,10,"",0,1,"L");
		}else{

		}

		$height += $heightY;
		
		$height += $addedHeight;
		// $mpdf->SetXY(15, $height);
		//$mpdf->Cell(10,5,"",0,1,"L");


		$nama_vendor = ($fpjp_nama_vendor) ? $fpjp_nama_vendor : $pemilik_rekening;
		$mpdf->Cell(10,10,"Harap dibayarkan kepada: ".$nama_vendor ,$guideline,1,"L");
		
		$mpdf->SetFont('Courier New','',8);

		$cell_1 = 50;
		$cell_2 = 90;
		$cell_3 = 40;
		
		$mpdf->Cell($cell_1,6,'Nama',1,0,'C');
		$mpdf->Cell($cell_2,6,'Jabatan',1,0,"C");
		$mpdf->Cell($cell_3,6,'Status',1,1,'C');
		
		$mpdf->SetFont('Courier New','',8);
		$mpdf->Cell($cell_1,15,"1. ".$submitter,1,0,'L');
		$mpdf->Cell($cell_2,15, $jabatan_sub,1,0,'L');
		$mpdf->Cell($cell_3,15,"Approved",1,1,'C');


		if($row['STATUS'] == "request_approve"){
			$status = "Waiting for approval";
		}else{
			$status = ucfirst($row['STATUS']);
		}

		$approval[] = ["PIC_NAME" => $diketahui1, "JABATAN" => $row['JABATAN_1'], "STATUS" => ($diketahui1 != "") ? $status : ""];
		$approval[] = ["PIC_NAME" => $diketahui2, "JABATAN" => $row['JABATAN_2'], "STATUS" => ($diketahui1 != "") ? $status : ""];

		$get_approval = $this->fpjp->get_approval_by_fpjp($fpjp_header_id);
		if($get_approval){
			$approval = $get_approval;
		}

		$heightY = 8;
		$height +=35;

		foreach ($approval as $key => $value) {
			$pic_name   = $value['PIC_NAME'];
			$jabatan    = $value['JABATAN'];
			$jabatan_ln = strlen($jabatan);

			if($value['STATUS'] == "request_approve"){
				$status = "Waiting for approval";
			}else{
				$status = ucfirst($value['STATUS']);
			}

			if($jabatan_ln > 40){
        		// $heightY +=6;
				$jabatan = str_replace(" ", " |", $jabatan);
				$newjabatan = explode("|",$jabatan);
				$newLine = "";

				$strC = 0;
				$lastjabatan = "";
				$jabatanlinebreak = "";
				$lastjabatanlinebreak = "";

				for ($i=0; $i < count($newjabatan); $i++) 
				{
					$str = strlen($newjabatan[$i]);
					$strC += $str;

					if($strC > 60)
					{
						$i_last = $i;
						$lastjabatanlinebreak .= $newLine.$newjabatan[$i];
					}
					else if ($strC > 40 && $strC <= 60)
					{
						$i_last = $i;
						$jabatanlinebreak .= $newLine.$newjabatan[$i];
					}
					else
					{
						$lastjabatan .= $newLine.$newjabatan[$i];
					}
				}
			}

			if($jabatan_ln > 60)
			{

				$mpdf->Cell($cell_1,15,$no.". ". $pic_name,"R L",0,'L');
				$mpdf->Cell($cell_2,15,$lastjabatan,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $status,"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY,"","R L",0,'L');
				$mpdf->Cell($cell_2,$newHeightY, $jabatanlinebreak,"R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY,"","R L",1,'L');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY,"","B R L",0,'C');
				$mpdf->Cell($cell_2,$newHeightY, $lastjabatanlinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY,"","B R L",1,'L');

			}
			else if ($jabatan_ln > 40 && $jabatan_ln <=60)
			{

				$mpdf->Cell($cell_1,$heightY, $no.". ". $pic_name,"R L",0,'L');
				$mpdf->Cell($cell_2,$heightY, $lastjabatan,"R L",0,'L');
				$mpdf->Cell($cell_3,$heightY, $status,"R L",1,'C');

				$newHeightY = $heightY;
				$mpdf->Cell($cell_1,$newHeightY,"","B R L",0,'L');
				$mpdf->Cell($cell_2,$newHeightY, $jabatanlinebreak,"B R L",0,'L');
				$mpdf->Cell($cell_3,$newHeightY,"","B R L",1,'L');
			}
			else{

				$mpdf->Cell($cell_1,15, $no.". ". $pic_name,1,0,'L');
				$mpdf->Cell($cell_2,15, $jabatan,1,0,'L');
				$mpdf->Cell($cell_3,15, $status,1,1,'C');
			}

			$no++;
			$height += 15;
		}

		$height;

		$mpdf->SetFont('Courier New','',7);
		// $mpdf->SetXY(15, $height);
		$mpdf->Cell(10,5,"",$guideline,1,"L");
		$mpdf->Cell(10,5,"Catatan : 1. Bukti pertanggungjawaban pengeluaran harus disertai dengan bukti tertulis dan bukti pendukung asli",$guideline,1,"L");
		// $mpdf->SetX(29);
		$mpdf->Cell(10,1,"(kecuali hal ini tidak memungkinkan)",$guideline,1,"L");

		$fpjp_encrypt =$row['FPJP_NUMBER'] . " - " . number_format($fpjp_amount,0,',','.');
		// echo md5(json_encode($fpjp_encrypt));die;

		$doc_ref = encrypt_string($fpjp_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";

		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);

		$title = "FPJP - ".$row['FPJP_NAME'] .".pdf";

		$mpdf->Output($title, "I");

	}

}

/* End of file Report.php */
/* Location: ./application/controllers/api/Report.php */