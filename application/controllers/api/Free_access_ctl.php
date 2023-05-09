<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Free_access_ctl extends CI_Controller {

	public function index()
	{
		
	}


	public function print_po($po_header_id){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$this->load->model('purchase_mdl','purchase');

		ob_start();

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		$decrypt      = decrypt_string($po_header_id, true);
		$po_header_id = (int) $decrypt;

		$mpdf = new \Mpdf\Mpdf();

		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'iso-8859-4';

		$fh = 'assets/templates/justification.pdf';

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);
		$mpdf->Image('assets/img/fintek.jpg',15,10,35);

		$mpdf->SetTextColor(0,0,0);
		$mpdf->SetFont('Courier New','B',10);

		$data = $this->purchase->get_header_po($po_header_id);

		$guideline = 0;

		$po_number = $data['PO_NUMBER'];
		$notes 	   = $data['NOTES'];
		$po_template = $data['PO_CATEGORY'];
		$doc_clause  = $data['DOCUMENT_CLAUSE'];

		$mpdf->Cell(0, 9, "Purchase Order", 0, true, 'C');
		$mpdf->Cell(0, 0, $data['PO_NUMBER'], 0, true, 'C');

		$titikdua = 41;

		$mpdf->SetFont('Roboto','',6);
		$height = 40;
		$mpdf->SetXY(18, $height);
		$mpdf->Cell(10, 10, "Company",0,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua+1, $height);
		$mpdf->Cell(10, 10, $data['NAMA_VENDOR'],$guideline,"L");

		$address_ln = strlen($data['ALAMAT']);

		if($address_ln <= 60){
			$height = 43;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Address",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ALAMAT'],0,60),$guideline,"L");
		}else{
			$height = 43;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Address",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ALAMAT'],0,60),$guideline,"L");

			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, "",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ALAMAT'],60,60),$guideline,"L");
		}

		$height = $height+3;
		$mpdf->SetXY(18, $height);
		$mpdf->Cell(10, 10, "Phone & Mobile Phone",0,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua+1, $height);
		$mpdf->Cell(10, 10, $data['NO_TLP'],$guideline,"L");

		$height = $height+3;
		$mpdf->SetXY(18, $height);
		$mpdf->Cell(10, 10, "Contact Name",0,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua+1, $height);
		$mpdf->Cell(10, 10, "",$guideline,"L");

		$mpa_ln = strlen($data['MPA_REFERENCE']);
		if($mpa_ln <=60){
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Contract / MPA No",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['MPA_REFERENCE'],0,60),$guideline,"L");
		}else{
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Contract / MPA No",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['MPA_REFERENCE'],0,60),$guideline,"L");

			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, "",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['MPA_REFERENCE'],60,60),$guideline,"L");
		}

		$est_ln = strlen($data['ESTIMATE_DATE']);
		if($est_ln <=60){
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Estimate Delivery Date",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ESTIMATE_DATE'],0,60),$guideline,"L");
		}else{
			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "Estimate Delivery Date",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, ":",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ESTIMATE_DATE'],0,60),$guideline,"L");

			$height = $height+3;
			$mpdf->SetXY(18, $height);
			$mpdf->Cell(10, 10, "",0,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10, 10, "",$guideline,"R");
			$mpdf->SetXY($titikdua+1, $height);
			$mpdf->Cell(10, 10, substr($data['ESTIMATE_DATE'],60,60),$guideline,"L");
		}

		$titikdua2 = 128;

		$mpdf->SetFont('Roboto','',6);

		$status_po = strtolower($data['STATUS']);
		$po_date = ($status_po == "approved" || $status_po == "partial paid" || $status_po== "paid") ?  date("F jS, Y", strtotime($data['PO_APPROVED'])) : "";
		$height = 37;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Date",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, $po_date,$guideline,"L");

		$height = 40;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Bill To",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10,"PT. Fintek Karya Nusantara",$guideline,"L");

		$height = 43;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Address",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, "Treasury Tower 31st Floor, District 8, SCBD Lot 28, Jl. Jend. Sudirman Kav. 52-53",$guideline,"L");
		$mpdf->SetXY($titikdua2+1, $height+3);
		$mpdf->Cell(10, 10, "Jakarta Selatan, DKI Jakarta 12190, Indonesia",$guideline,"L");

		$height = 49;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Phone/fax",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, "62-21-50880130",$guideline,"L");

		$top_trim = trim($data['TOP']);
		$term_ln = strlen($top_trim);
		$top_ln_max = 70;
		$height     = 52;
		$str_1      ="";
		$str_2      ="";
		$str_3      ="";
		for ($i=1; $i <= 3 ; $i++) {
			$line_2 = false;
			$line_3 = false;
			if($i == 1){
				$str_1 = substr($top_trim,0,$top_ln_max);
			}
			if($i == 2){
				$rplc =  trim(str_replace($str_1,"", $top_trim));
				$str_2 = substr($rplc,0,$top_ln_max);
				$line_2 = ($str_2 != "") ? true: false;
			}
			if($i == 3){
				$rplc =  trim(str_replace(array($str_1,$str_2),"", $top_trim));
				$str_3 = substr($rplc,0,$top_ln_max);
				$line_3 = ($str_3 != "") ? true: false;
			}

			if($i == 1){
				$mpdf->SetXY(110, $height);
				$mpdf->Cell(10, 10, "Term Of Payment",0,"L");
				$mpdf->SetXY($titikdua2, $height);
				$mpdf->Cell(10, 10, ":",$guideline,"R");
				$mpdf->SetXY($titikdua2+1, $height);
				$mpdf->Cell(10, 10, $str_1,$guideline,"L");
			}

			if( $line_2 ){
				$height = $height+3;
				$mpdf->SetXY(110, $height);
				$mpdf->SetXY($titikdua2, $height);
				$mpdf->Cell(10, 10, "",$guideline,"R");
				$mpdf->SetXY($titikdua2+1, $height);
				$mpdf->Cell(10, 10, $str_2,$guideline,"L");
			}
			
			if( $line_3 ){
				$height = $height+3;
				$mpdf->SetXY(110, $height);
				$mpdf->SetXY($titikdua2, $height);
				$mpdf->Cell(10, 10, "",$guideline,"R");
				$mpdf->SetXY($titikdua2+1, $height);
				$mpdf->Cell(10, 10, $str_3,$guideline,"L");
			}
		}

		$height = $height+3;
		$mpdf->SetXY(110, $height);
		$mpdf->Cell(10, 10, "Currency",0,"L");
		$mpdf->SetXY($titikdua2, $height);
		$mpdf->Cell(10, 10, ":",$guideline,"R");
		$mpdf->SetXY($titikdua2+1, $height);
		$mpdf->Cell(10, 10, $data['CURRENCY'],$guideline,"L");

		$hasil = $this->purchase->get_cetak_po($po_header_id);
		$data = $hasil['data'];

		// $height = $height+8;
		// $mpdf->SetXY(169, $height);
		// $mpdf->Cell(10, 10, "Currency :",0,"L");
		// $mpdf->SetXY(180, $height);
		// $mpdf->Cell(10, 10, "",0,"L");

		$height = $height+4;

		$mpdf->SetFont('Roboto','C',8);
		$mpdf->SetXY(15, $height+8);
		$mpdf->Cell(5,6,'NO',1,0,'C');
		$mpdf->Cell(78,6,'Description',1,0,'C');
		$mpdf->Cell(8,6,'QTY',1,0,'C');
		$mpdf->Cell(23,6,'UoM',1,0,'C');
		$mpdf->Cell(25,6,'Unit Price',1,0,'C');
		$mpdf->Cell(25,6,'Total Amount',1,0,'C');
		$mpdf->Cell(23,6,'Remark',1,1,'C');

		$no = 1;
		$sub_total = 0;
		$vat = 0;
		$total = 0;
		foreach ($data as $key => $value) {
			$price 			= $value['PRICE'];
			$qty 			= $value['QUANTITY'];
			$total_price 	= $price * $qty;
			$sub_total 		+= $total_price;
			// $vat 			= $sub_total * 10/100;
			$total 			= $sub_total;

			if($value['ITEM_NAME'] == $value['DESCRIPTION_PO']){
				$desc_po = $value['ITEM_NAME'];
			}else{
				if($value['ITEM_NAME']){
					$desc_po = $value['ITEM_NAME'];
					$desc_po .= ($value['DESCRIPTION_PO'] == "-" || $value['DESCRIPTION_PO'] == "") ? "" : " - ".$value['DESCRIPTION_PO'];
				}else{
					$desc_po = $value['DESCRIPTION_PO'];
				}
			}

			$desc_ln = strlen($desc_po);
			if($desc_ln <=50){
				$mpdf->Cell(5,5, $no,"B R L",0,'L');
				$mpdf->Cell(78,5, $desc_po,"B R L",0,'L');
				$mpdf->Cell(8,5, $value['QUANTITY'],"B R L",0,'C');
				$mpdf->Cell(23,5, $value['UOM'],"B R L",0,'C');
				$mpdf->Cell(25,5, number_format($value['PRICE'],0,',','.'),"B R L",0,'R');
				$mpdf->Cell(25,5, number_format($total_price,0,',','.'),"B R L",0,'R');
				$mpdf->Cell(23,5, "","B R L",1,'C');
			}
			elseif($desc_ln > 50 && $desc_ln <= 100){
				$mpdf->Cell(5,5, $no,"R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,0,50),"R L",0,'L');
				$mpdf->Cell(8,5, $value['QUANTITY'],"R L",0,'C');
				$mpdf->Cell(23,5, $value['UOM'],"R L",0,'C');
				$mpdf->Cell(25,5, number_format($value['PRICE'],0,',','.'),"R L",0,'R');
				$mpdf->Cell(25,5, number_format($total_price,0,',','.'),"R L",0,'R');
				$mpdf->Cell(23,5, "","R L",1,'C');

				$mpdf->Cell(5,5, "","B R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,50,50),"B R L",0,'L');
				$mpdf->Cell(8,5, "","B R L",0,'C');
				$mpdf->Cell(23,5, "","B R L",0,'C');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(23,5, "","B R L",1,'C');
			}else{
				$mpdf->Cell(5,5, $no,"R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,0,50),"R L",0,'L');
				$mpdf->Cell(8,5, $value['QUANTITY'],"R L",0,'C');
				$mpdf->Cell(23,5, $value['UOM'],"R L",0,'C');
				$mpdf->Cell(25,5, number_format($value['PRICE'],0,',','.'),"R L",0,'R');
				$mpdf->Cell(25,5, number_format($total_price,0,',','.'),"R L",0,'R');
				$mpdf->Cell(23,5, "","R L",1,'C');

				$mpdf->Cell(5,5, "","R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,50,50),"R L",0,'L');
				$mpdf->Cell(8,5, "","R L",0,'C');
				$mpdf->Cell(23,5, "","R L",0,'C');
				$mpdf->Cell(25,5, "","R L",0,'R');
				$mpdf->Cell(25,5, "","R L",0,'R');
				$mpdf->Cell(23,5, "","R L",1,'C');

				$mpdf->Cell(5,5, "","B R L",0,'L');
				$mpdf->Cell(78,5, substr($desc_po,100,50),"B R L",0,'L');
				$mpdf->Cell(8,5, "","B R L",0,'C');
				$mpdf->Cell(23,5, "","B R L",0,'C');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(25,5, "","B R L",0,'R');
				$mpdf->Cell(23,5, "","B R L",1,'C');
			}
			// $mpdf->Cell(78,6, $value['DESCRIPTION_PO'],"B R L",0,'L');

			$no++;
		}
		/*$mpdf->Cell(5,6, "","",0,'L');
		$mpdf->Cell(78,6, "","",0,'L');
		$mpdf->Cell(8,6, "","",0,'C');
		$mpdf->Cell(23,6, "","",0,'C');
		$mpdf->Cell(25,6, "Sub Total","B R L",0,'L');
		$mpdf->Cell(25,6, number_format($sub_total,0,',','.'),"B R L",0,'R');
		$mpdf->Cell(23,6, "","",1,'C');
*/
		/*$mpdf->Cell(5,6, "","",0,'L');
		$mpdf->Cell(78,6, "","",0,'L');
		$mpdf->Cell(8,6, "","",0,'C');
		$mpdf->Cell(23,6, "","",0,'C');
		$mpdf->Cell(25,6, "VAT 10%","B R L",0,'L');
		$mpdf->Cell(25,6, number_format($vat,0,',','.'),"B R L",0,'R');
		$mpdf->Cell(23,6, "","",1,'C');*/

		$mpdf->Cell(5,6, "","",0,'L');
		$mpdf->Cell(78,6, "","",0,'L');
		$mpdf->Cell(8,6, "","",0,'C');
		$mpdf->Cell(23,6, "","",0,'C');
		$mpdf->Cell(25,6, "Total","B R L",0,'L');
		$mpdf->Cell(25,6, number_format($total,0,',','.'),"B R L",0,'R');
		$mpdf->Cell(23,6, "","",1,'C');

		$mpdf->Cell(5,5, "","",1,'L');

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(5,5, "Delivery Detail",0,1,"L");

		$mpdf->SetFont('Roboto',8);
		$mpdf->SetX(20);
		$mpdf->Cell(5,6,'NO',1,0,'C');
		$mpdf->Cell(50,6,'Description',1,0,'C');
		$mpdf->Cell(50,6,'Deliver to Location',1,0,'C');
		$mpdf->Cell(50,6,'Contact Name & Phone No.',1,1,'C');

		$height = 20;
		$mpdf->SetX($height);
		$mpdf->Cell(5,6,'',1,0,'C');
		$mpdf->Cell(50,6,'',1,0,'C');
		$mpdf->Cell(50,6,'',1,0,'C');
		$mpdf->Cell(50,6,'',1,1,'C');

		$mpdf->SetFont('Roboto','B',8);
		$mpdf->Cell(10,10, "NOTE :",0,0,'L');
		$mpdf->SetFont('Roboto',8);
		$mpdf->Cell(15,8, "",0,1,'L');
		// $mpdf->MultiCell(180,4,$notes,0, 'J');
		$mpdf->MultiCell(180,4,iconv('UTF-8', 'UTF-8//IGNORE', $notes),0, 'J');
		// $mpdf->MultiCell(180,4,iconv('UTF-8', 'UTF-8//IGNORE', $notes),0, 'J');

		$mpdf->SetFont('Calibri','',10);

		$po_encrypt = $po_number . " - " . number_format(123456789,0,',','.');
		$doc_ref = encrypt_string($po_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";
		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);
	    
		$title = "Form PO - ".$po_number;
		ob_clean();
        $mpdf->SetTitle($title);
		$title = "Form PO - ".$po_number.".pdf";

		if($status_po == 'request_approve'){
			$dratf_logo = FCPATH . 'assets/img/draft-logo.png';
			$mpdf->SetWatermarkImage( $dratf_logo, -1, 'F', 'F' );
			$mpdf->showWatermarkImage = true;
		}

		if($po_template == "Non Template" && $doc_clause != ""){

			$fh = 'uploads/po_attachment/'. $doc_clause;

	        $pagecount = $mpdf->setSourceFile($fh);
		    for($i=0; $i<$pagecount; $i++){
		        $mpdf->AddPage();  
		        $tplidx = $mpdf->importPage($i+1, '/BleedBox');
		        $mpdf->useTemplate($tplidx); 
		    }

			$mpdf->Output($title, "I");

		}elseif($po_template == "Non Template" && $doc_clause == ""){
			$mpdf->Output($title, "I");

		}else{
			$fh = 'assets/templates/Po_template_new1.pdf';

			$mpdf->AddPage();
	        $mpdf->setSourceFile($fh);
	        $tplId = $mpdf->importPage(1);
			$mpdf->useTemplate($tplId);

			$fh = 'assets/templates/Po_template_new2.pdf';

			$mpdf->AddPage();
	        $mpdf->setSourceFile($fh);
	        $tplId = $mpdf->importPage(1);
			$mpdf->useTemplate($tplId);

			$mpdf->Output($title, "I");
		}
	}

	public function submit_pending_fpjp_to_ap($id_fpjp=false){

		$this->load->model('fpjp_mdl','fpjp');

		$get_fpjp = $this->fpjp->get_pending_fpjp_to_ap($id_fpjp);
		if($get_fpjp){

			$fpjp_succes = array();
			$fpjp_failed = array();
			$email_cc    = array();
			$recipient   = array();

			/*$get_user_invoice = get_user_approval_ap("INVOICE_APPROVAL");
			foreach ($get_user_invoice as $key => $value) {
				if($value['PIC_EMAIL'] == "elpa_b_ginting@linkaja.id"){
					$recipient['name']  = $value['PIC_NAME'];
					$recipient['email'] = $value['PIC_EMAIL'];
				}else{
					$email_cc[] = $value['PIC_EMAIL'];
				}
			}*/

			/*$get_ap_lead = get_user_approval_ap("APPROVED_JOURNAL");
			if($get_ap_lead){
				$data_lead = $get_ap_lead[0];
				$email_cc[] = $data_lead['PIC_EMAIL'];
			}*/
			if(count($email_cc) > 0){
				$recipient['email_cc'] = $email_cc;
			}

			foreach ($get_fpjp as $key => $value) {
				$fpjp_header_id = $value['FPJP_HEADER_ID'];
				$fpjp_number    = $value['FPJP_NUMBER'];
				$submitter      = $value['SUBMITTER'];

				$no_jurnal = $this->_invoicing_to_gl($fpjp_header_id);
				if( $no_jurnal ){
					$fpjp_succes[] = $fpjp_number;
					/*if($recipient){
						$this->_email_invoice($recipient, $no_jurnal, $submitter);
					}*/
				}else{
					$fpjp_failed[] = $fpjp_number;
				}
			}

			if(count($fpjp_succes) > 0){
				$fpjp_succes = implode(", ", $fpjp_succes);
				$log_info = 'Succeesfully submit fpjp: ('.$fpjp_succes.')';
				log_message('info', $log_info);
			}
			if(count($fpjp_failed) > 0){
				$fpjp_failed = implode(", ", $fpjp_failed);
				$log_info = 'Failed submit fpjp: ('.$fpjp_failed.')';
				log_message('info', $log_info);
			}

		}else{
			$log_info = 'No pending fpjp';;
			log_message('info', $log_info);
		}

		return true;

	}


	private function _invoicing_to_gl($id_fpjp){

		$this->load->model('GL_mdl', 'gl');
		$get_fpjp_to_invoice = $this->gl->get_fpjp_to_invoice($id_fpjp);

		$batch_name_usr = "SYS".date("d/")."AP/".date("my");
		$get_latest_no_journal = $this->gl->get_latest_no_journal_by_batc($batch_name_usr);

    	$start_no_journal = 1;
    	if($get_latest_no_journal):
    		$exp = explode("/", $get_latest_no_journal['NO_JOURNAL']);
    		$start_no_journal = (int) $exp[0];
    		$start_no_journal += 1;
    	endif;

		$no_jurnal = sprintf("%'02d", $start_no_journal)."/".$batch_name_usr;
		$no_urut   = 1;

		$date_now = date("Y-m-d");

    	foreach ($get_fpjp_to_invoice as $key => $value) {

			$tgl_invoice = (empty($value['INVOICE_DATE'])) ? $date_now : $value['INVOICE_DATE'];
			$no_invoice  = (empty($value['NO_INVOICE'])) ? $value['FPJP_NUMBER'] : $value['NO_INVOICE'];
			$description = ($value['FPJP_DETAIL_DESC'] != '') ? $value['FPJP_DETAIL_DESC'] : $value['FPJP_LINE_NAME'];
			$vendor_name = ($value['FPJP_VENDOR_NAME']) ? $value['FPJP_VENDOR_NAME'] : $value['PEMILIK_REKENING'];

    		$data[] = array(
						'TGL_INVOICE'          => $date_now,
						'INVOICE_DATE'         => $tgl_invoice,
						'BATCH_NAME'           => $batch_name_usr,
						'NO_JOURNAL'           => $no_jurnal,
						'NAMA_VENDOR'          => $vendor_name,
						'NO_INVOICE'           => $no_invoice,
						'NO_KONTRAK'           => NULL,
						'DESCRIPTION'          => $description,
						'DPP'                  => $value['FPJP_DETAIL_AMOUNT'],
						'ORIGINAL_AMOUNT_FPJP' => $value['ORIGINAL_AMOUNT'],
						'NO_FPJP'              => $value['FPJP_NUMBER'],
						'NAMA_REKENING'        => $value['PEMILIK_REKENING'],
						'NAMA_BANK'            => $value['NAMA_BANK'],
						'ACCT_NUMBER'          => $value['NO_REKENING'],
						'NATURE'               => $value['NATURE'],
						'NO_URUT_JURNAL'       => $no_urut,
						'CURRENCY'             => $value['CURRENCY']
					);

			$no_urut++;

    	}

		$valuetrue = $this->gl->insert_gl_header_import($data);

		if($valuetrue){
			if($this->crud->call_procedure("UPLOAD_BATCH") !== -1){
				if($this->crud->call_procedure("JURNAL_HEADERS") !== -1 && $this->crud->call_procedure("Journal_B_Tax") !== -1){
					return $no_jurnal;
				}
			}
		}else{
			return false;
		}
    }

    private function _email_invoice($recipient, $no_journal, $submitter){

		$this->load->model('GL_mdl', 'gl');
		$get_journal = $this->gl->get_journal_by_no_journal($no_journal);

		$data['email_preview'] = "A new invoice has been submitted for your approval with Batch Approval ".$no_journal;
		
		$action_name = $submitter;

		$email_preview = "A new invoice with No Journal $no_journal has been submitted by $action_name and need to review";

		$email_body = "A new invoice with No Journal <b>$no_journal</b> has been submitted by <b>$action_name</b> and need to review. 
					<br>
					<br>
		Invoice List:
								<br>
								  <div class='hack1'>
								  	<div class='hack2'>
										<table class='custom_table'>
											<tbody>
												<tr>
														<th>No</th>
														<th>Transaction Date</th>
														<th>No Invoice</th>
														<th>Batch Name</th>
														<th>No Journal</th>
														<th>DPP</th>
												</tr>";

		$no = 1;

		foreach ($get_journal as $key => $value) {

			$transaction_date = date("d-m-Y",strtotime($value['TGL_INVOICE']));
			$no_invoice       = $value['NO_INVOICE'];
			$batch_name       = $value['BATCH_NAME'];
			$no_journal       = $value['NO_JOURNAL'];
			$dpp              = number_format($value['DPP'],0,'.',',');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$transaction_date."</td>";
				$email_body .= "<td>".$no_invoice."</td>";
				$email_body .= "<td>".$batch_name."</td>";
				$email_body .= "<td>".$no_journal."</td>";
				$email_body .= "<td>".$dpp."</td>";
			$email_body .= "</tr>";
			$no++;

		}

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_name), true);
		$data['link'] = base_url("gl/gl-header");
		$data['link_display'] = "Financetools Invoice";

		$subject    = "Invoice Journal  - $no_journal";

		/*foreach ($recipient as $key => $value) {
			$data['email_recipient']  = $value['name'];
			$to   = $value['email'];
			$body = $this->load->view('email/ap_invoice_tax', $data, TRUE);
			$send = sendemail($to, $subject, $body);
		}*/
		$data['email_recipient']  = $recipient['name'];
		$to   = $recipient['email'];
		$body = $this->load->view('email/ap_invoice_tax', $data, TRUE);

		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$send = sendemail($to, $subject, $body, $cc);

		return true;
	}


	public function resend_email($id=false){

		$this->load->model('fpjp_mdl','fpjp');

		$decrypt = decrypt_string($id, true);
		$id      = (int) $decrypt;

		if($id > 0){
			$get_trx = $this->crud->read_by_param("TRX_EMAIL_LOG", $id);
		}else{
			$get_trx = $this->crud->read_by_param("TRX_EMAIL_LOG", [ 'IS_SENT' => 0, 'STATUS' => 'Failed' ]);
		}

		if($get_trx){
			$id_log     = $get_trx['ID'];
			$to         = $get_trx['EMAIL_TO'];
			$subject    = $get_trx['EMAIL_SUBJECT'];
			$body       = $get_trx['EMAIL_BODY'];
			$cc         = ($get_trx['EMAIL_CC']) ? $get_trx['EMAIL_CC'] : "";
			$attachment = ($get_trx['EMAIL_ATTACHMENT']) ? $get_trx['EMAIL_ATTACHMENT'] : "";
			$is_sent    = ($get_trx['IS_SENT'] > 0) ? $get_trx['IS_SENT']+1 : 1;

			$send = sendemail($to, $subject, $body, $cc, $attachment, false, $id_log);

			if($send){
				$dataUpdte['IS_SENT'] = $is_sent;
				$dataUpdte['UPDATED_BY'] = 'SYSTEM';
				$this->crud->update("TRX_EMAIL_LOG", $dataUpdte, $id_log, false);
			}
		}else{
			$log_info = 'No pending email error';
			log_message('info', $log_info);
		}

		return true;

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


	public function print_pr($pr_header_id){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}
		$this->load->model('feasibility_study_mdl','feasibility_study');
		$this->load->model('purchase_mdl','purchase');

		$decrypt      = decrypt_string($pr_header_id, true);
		$pr_header_id = (int) $decrypt;

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/form_pr_new2.pdf';

		$data = $this->purchase->get_cetak($pr_header_id);

		$mpdf->AddPage();
        $mpdf->setSourceFile($fh);
        $tplId = $mpdf->importPage(1);
		$mpdf->useTemplate($tplId);

		$mpdf->SetTextColor(0,0,0);
		$mpdf->SetFont('Courier New','',18);

		$guideline = 0;

		$doc_list = ($data['DOCUMENT_CHECKLIST']) ? $data['DOCUMENT_CHECKLIST'] : '';

		if($doc_list){

			$arrDocList = (array) json_decode($doc_list);

			if (array_key_exists('justifikasi', $arrDocList)) {
				if ($arrDocList['justifikasi'] == true) {
					$height = 56.6;
					$mpdf->SetXY(78.4, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('program_control_review', $arrDocList)) {
				if ($arrDocList['program_control_review'] == true) {
					$height = 61.4;
					$mpdf->SetXY(78.4, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('boq', $arrDocList)) {
				if ($arrDocList['boq'] == true) {
					$height = 66;
					$mpdf->SetXY(78.4, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('dpl_pl', $arrDocList)) {
				if ($arrDocList['dpl_pl'] == true) {
					$height = 56.6;
					$mpdf->SetXY(179.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('rks_tender', $arrDocList)) {
				if ($arrDocList['rks_tender'] == true) {
					$height = 61.4;
					$mpdf->SetXY(168.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('rks_pl', $arrDocList)) {
				if ($arrDocList['rks_pl'] == true) {
					$mpdf->SetXY(179.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('mom_po', $arrDocList)) {
				if ($arrDocList['mom_po'] == true) {
					$height = 66;
					$mpdf->SetXY(157.5, $height+5);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('nodin_amd', $arrDocList)) {
				if ($arrDocList['nodin_amd'] == true) {
					$height = 71;
					$mpdf->SetXY(148.2, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('nodin_tender', $arrDocList)) {
				if ($arrDocList['nodin_tender'] == true) {
					$mpdf->SetXY(168.4, $height);
					$mpdf->Cell(0,18.5,"✓",$guideline,"L");
				}
			}
			if (array_key_exists('nodin_pl', $arrDocList)) {
				if ($arrDocList['nodin_pl'] == true) {
					$mpdf->SetXY(179.8, $height);
					$mpdf->Cell(10,10,"✓",$guideline,"L");
				}
			}
		}else{
			$height = 56.6;
			$mpdf->SetXY(78.4, $height);
			$mpdf->Cell(10,10,"✓",$guideline,"L");
		}

		$mpdf->SetFont('Calibri','',10);
		$titikdua = 78;
		$height = 79.3;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"No. PR",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$height = 79.3;
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['PR_NUMBER'],$guideline,"L");

		$justif = substr($data['FS_NAME'],0,60);
		$justif2 = substr($data['FS_NAME'],60,120);
		$justif3 = substr($data['FS_NAME'],120,240);

		if(strlen($data['FS_NAME']) < 60){
			$height = 84;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"Judul Justifikasi",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,":",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$data['FS_NAME'],$guideline,"L");
		}elseif(strlen($data['FS_NAME']) > 60 && strlen($data['FS_NAME']) < 120){
			$height = 84;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"Judul Justifikasi",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,":",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif,$guideline,"L");

			$height = $height+5;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,"",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif2,$guideline,"L");
		}elseif(strlen($data['FS_NAME']) > 120){
			$height = 84;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"Judul Justifikasi",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,":",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif,$guideline,"L");

			$height = $height+5;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,"",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif2,$guideline,"L");

			$height = $height+5;
			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10,"",$guideline,"L");
			$mpdf->SetXY($titikdua, $height);
			$mpdf->Cell(10,10,"",$guideline,"R");
			$mpdf->SetXY(80, $height);
			$mpdf->Cell(10,10,$justif3,$guideline,"L");
		}

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"PIC PR",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['SUBMITTER'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Unit",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['UNIT_NAME'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Budget (with Tax )",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,number_format($data['PR_AMOUNT'],0,',','.'),$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"COA",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['NATURE'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"COA Desc.",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['DESCRIPTION'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Direktorat",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,$data['DIRECTORAT_NAME'],$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Usulan Metode Pengadaan*",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,"--",$guideline,"L");

		$height = $height+5;
		$mpdf->SetXY(28, $height);
		$mpdf->Cell(10,10,"Verification Note*",$guideline,"L");
		$mpdf->SetXY($titikdua, $height);
		$mpdf->Cell(10,10,":",$guideline,"R");
		$mpdf->SetXY(80, $height);
		$mpdf->Cell(10,10,"--",$guideline,"L");
		
		$get_approval      = $this->purchase->get_approval_by_pr($pr_header_id);
		// echo_pre($get_approval);die;
		$approval_hog      = "";
		$approval_proc_sup = "";
		$approval_hou_buy  = "";

		// echo_pre($get_approval);die;

		foreach ($get_approval as $key => $value) {

			$category = strtolower($value['CATEGORY']);

			$approval = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");

			if($category == "hog user"){
				$approval_hog = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
			}elseif($category == "procurement"){
				$approval_proc_sup = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
			}/*elseif($category == "hou procurement"){
				$approval_hou_buy = array("NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "TGL_APPROVE" => ($value['UPDATED_DATE']) ? dateFormat($value['UPDATED_DATE'], 4, false) : "-");
			}*/
		}

		if($approval_proc_sup != ""){
			$mpdf->SetFont('Calibri','B',10);

			$height = 140.5;
			$mpdf->SetXY(35, $height);
			$mpdf->Cell(10,10, "Verifikator*",$guideline,"C");

			$mpdf->SetFont('Calibri','',10);
			$height = 145.5;
			$mpdf->SetXY(35, $height);
			$mpdf->Cell(10,10, $approval_proc_sup['TGL_APPROVE'],$guideline,"C");

			$height = 155.5;
			if($approval_proc_sup['STATUS'] == "request_approve"){
				$mpdf->SetXY(29, $height);
				$status = "Waiting for review"; 
				// $status = "X"; 
			}else{
				$mpdf->SetXY(29, $height);
				$status = ($approval_proc_sup['STATUS'] == "approved") ? "Verified" : "Rejected";
			}
			$pic = $approval_proc_sup['NAME'];
			$mpdf->Cell(10,10, $status, $guideline,"L");
			$height = 162;
			$mpdf->SetXY(29, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"L");

			$height = $height+5.4;
			$mpdf->SetXY(29, $height);
			$mpdf->Cell(10,10, "Procurement Support", $guideline,"L");
		}

		if($approval_hog != ""){

			$mpdf->SetFont('Calibri','B',10);
			$height = 140.5;
			$mpdf->SetXY(95, $height);
			$mpdf->Cell(10,10, "Disetujui Oleh*",$guideline,"C");

			$mpdf->SetFont('Calibri','',10);
			$height = 145.5;
			$mpdf->SetXY(95, $height);
			$mpdf->Cell(10,10, $approval_hog['TGL_APPROVE'],$guideline,"C");

			$height = 155.5;
			$status = "";
			if($approval_hog['STATUS'] == "request_approve"){
				$mpdf->SetXY(88, $height);
				$status = "Waiting for approval"; 
			}elseif($approval_hog['STATUS'] != NULL){
				$mpdf->SetXY(88, $height);
				$status = ucfirst($approval_hog['STATUS']); 
			}
			$pic = $approval_hog['NAME'];
			$mpdf->Cell(10,10, $status, $guideline,"L");
			$height = 162;
			$mpdf->SetXY(88, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"L");

			$height = $height+5.4;
			$mpdf->SetXY(88, $height);
			$mpdf->Cell(10,10, "Head Of Group User", $guideline,"L");
		}

		$usulan_buyer = $this->crud->read_by_param("MASTER_APPROVAL", array("IS_EXIST" => 1, "IS_DELETED" => 0, "PIC_EMAIL" => $data['PO_BUYER']));
		$get_hou_proc  = $this->feasibility_study->get_data_approval("HOU Procurement");
		// if($approval_hou_buy != "" && $usulan_buyer){

			$mpdf->SetFont('Calibri','B',10);
			$height = 140.5;
			$mpdf->SetXY(145, $height);
			$mpdf->Cell(10,10, "Usulan Buyer*",$guideline,"C");

			$mpdf->SetFont('Calibri','',10);
			$height = 145.5;
			$mpdf->SetXY(145, $height);
			if($data['STATUS_ASSIGN'] == "Y" && $data['ASSIGN_DATE'] != NULL){
				$mpdf->Cell(10,10, dateFormat($data['ASSIGN_DATE'], 4, false), $guideline, "C");
			}else{
				$mpdf->Cell(10,10, "-", $guideline, "C");
			}

			$height = 155.5;
			$status = "";
			if($data['STATUS_ASSIGN'] == "Y"){
				$mpdf->SetXY(138, $height);
				$status = "Assigned"; 
			}elseif($approval_hog['STATUS'] != NULL){
				$mpdf->SetXY(138, $height);
				$status = "Waiting for assign"; 
			}
			$pic = ($usulan_buyer) ? $usulan_buyer['PIC_NAME']: "";
			$mpdf->Cell(10,10, $status, $guideline,"L");
			$height = 162;
			$mpdf->SetXY(138, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"L");

			$height = $height+5.4;
			$mpdf->SetXY(138, $height);
			$mpdf->Cell(10,10, "Procurement Buyer", $guideline,"L");
		// }
		/*if($approval_hou_buy != ""){

			$height = 147.5;
			$mpdf->SetXY(145, $height);
			$mpdf->Cell(10,10, $approval_hou_buy['TGL_APPROVE'],$guideline,"C");

			$height = 155.5;
			if($approval_hou_buy['STATUS'] == "request_approve"){
				$mpdf->SetXY(140, $height);
				$status = "Waiting for approval"; 
			}else{
				$mpdf->SetXY(145, $height);
				$status = ucfirst($approval_hou_buy['STATUS']); 
			}
			$pic = $approval_hou_buy['NAME'];
			$mpdf->Cell(10,10, $status, $guideline,"C");
			$height = 163;
			$mpdf->SetXY(140, $height);
			$mpdf->Cell(10,10, $pic, $guideline,"C");
		}*/

		$tracking[] = array('tanggal' => dateFormat($data['CREATED_DATE'], 4, false), 'keterangan' => 'Submitted by ' . $data['SUBMITTER'], 'paraf' => 'Submitted');
		$history =  $this->purchase->get_comment_history($pr_header_id);

		foreach ($history as $key => $value):
			$remarkAdd = ($value['REMARK'] != '') ? ' : "'. substr($value['REMARK'],0,30) .'"' : '';
			$status = $value['STATUS'];
			if(strtolower($value['CATEGORY']) == "procurement"){
				$status = ($status == "approved") ? "verified" : $status;
				$ket    = ucfirst($status) .' by ' . $value['PIC_NAME'] . $remarkAdd;
			}
			/*elseif(strtolower($value['CATEGORY']) == "hou procurement"){
				$status = ($status == "approved") ? "assigned" : $status;
				$ket    = ucfirst($status) .' buyer by ' . $value['PIC_NAME'] . $remarkAdd;
			}*/else{
				$ket = ucfirst($status) .' by ' . $value['PIC_NAME'] . $remarkAdd;
			}
			$paraf = ucfirst($status);

			$tracking[] = array(
								'tanggal'    => dateFormat($value['UPDATED_DATE'], 4, false),
								'keterangan' => $ket,
								'paraf'      => $paraf
							);
		endforeach;

		if($data['STATUS_ASSIGN'] == "Y"):

			$tracking[] = array(
							'tanggal'    => dateFormat($data['ASSIGN_DATE'], 4, false),
							'keterangan' => 'Assigned to buyer by ' . $get_hou_proc['PIC_NAME'],
							'paraf'      => "Assigned"
						);
		endif;


		$height = 186.4;
		foreach ($tracking as $key => $value):

			$mpdf->SetXY(28, $height);
			$mpdf->Cell(10,10, $value['tanggal'], $guideline,"L");
			$mpdf->SetXY(51, $height);
			$mpdf->Cell(10,10, $value['keterangan'], $guideline,"L");
			$mpdf->SetXY(168, $height);
			$mpdf->Cell(10,10, $value['paraf'], $guideline,"C");
			
			$height += 4.6;
		endforeach;

		$pr_encrypt = $data['PR_NUMBER'] . " - " . number_format($data['PR_AMOUNT'],0,',','.');
		$doc_ref = encrypt_string($pr_encrypt, true);
		$water_mark_desc = '"<i>This document generated by Financetool
							<br>
							Approval done through the system and no signature required."
							<br>
							Document ref: '.$doc_ref ."</i>";
		$footer = "<div style='border:1.4px dashed #686868;font-family:Courier New;width:100%;padding:5px;text-align:center;font-size:8px;font-weight:bold;color:#686868;letter-spacing: 1px;'>
			$water_mark_desc
         </div>";
		$mpdf->SetHTMLFooter($footer);
	    
		$title = "Form PR - ".$data['PR_NUMBER'] ." - ". $data['PR_NAME'];
        $mpdf->SetTitle($title);
		$title = "Form PR - ".$data['PR_NUMBER'] .".pdf";

		$mpdf->Output($title, "I");

	}

}

/* End of file Free_access_ctl.php */
/* Location: ./application/controllers/api/Free_access_ctl.php */
