<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Userapprovedjournalhou extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Userapprovedjournalhou_mdl', 'user_approved_journal');
		$this->load->model('GL_mdl', 'gl');

	}

	public function user_approved_journal($batch_approval ="")
	{

		if($this->ion_auth->is_admin() == true || in_array("userapprovedjournalhou/user_approved_journal", $this->session->userdata['menu_url']) ){
			$data['title']          = "Approval HoU ";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/user_approved_journal_hou";

			$groups = $this->session->userdata('group_id');

			foreach ($groups as $key => $value) {
				$grpName = get_group_data($value);
				$group_name[] = $grpName['NAME'];
			}

			$data ['group_name'] 	= $group_name;
			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Approval Hou", "link" => "", "class" => "active" );

			$data['batch_approval'] = ($batch_approval) ? str_replace("-","/", decrypt_string($batch_approval, true)) : '';

			// echo_pre($data);die;

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	function load_ddl_approved()
	{		
		$result ="";
		$result .= "<option value='' data-name='' >Not yet Approved</option>";
		$result .= "<option value='"."Y"."' data-name='"."Y"."' >"."Approved"."</option>";	
		$result .= "<option value='"."N"."' data-name='"."N"."' >"."Rejected"."</option>";
		echo $result;

	}

	function load_ddl_Approve_batch()
	{
		$hasil	= $this->user_approved_journal->get_approve_batch();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Batch Approval --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['BATCH_APPROVAL_HOU']."' data-name='".$row['BATCH_APPROVAL_HOU']."' >".$row['BATCH_APPROVAL_HOU']."</option>";
		}		
		echo $result;
		$query->free_result();

	}


	public function load_data_inquiry(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('approved_date_from') != "" && $this->input->post('approved_date_to') != "")
		{
			$exp_approved_date_from = explode("/", $this->input->post('approved_date_from'));
			$exp_approved_date_to   = explode("/", $this->input->post('approved_date_to'));

			$approved_date_from     = $exp_approved_date_from[2]."-".$exp_approved_date_from[1]."-".$exp_approved_date_from[0];
			$approved_date_to       = $exp_approved_date_to[2]."-".$exp_approved_date_to[1]."-".$exp_approved_date_to[0];
		}

		$vendor_name = $this->input->post('vendor_name');
		$filter_date = $this->input->post('filter_date');

		$get_all = $this->user_approved_journal->get_journal_inquiry_datatable($approved_date_from,$approved_date_to,$vendor_name,$filter_date);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nomor   = 1;
		$i=1;
		$nmjurnal = '';
		$replacejurnal ='';
		$user_login = $user_login = get_user_data($this->session->userdata('user_id'));

		$optApproved = array("-- Choose --", "Approved", "Rejected");

		if($total > 0){
			
			foreach($data as $value) {

				if ($number == 1)
				{
					$nmjurnal =  $value['JOURNAL_NAME'];
					$nomor = 1;
				}
				else
				{
					if($nmjurnal ==  $value['JOURNAL_NAME'])
					{
						$nmjurnal =  $value['JOURNAL_NAME'];
						$nomor++;

					}
					else
					{
						$nmjurnal =  $value['JOURNAL_NAME'];
						$nomor = 1;
					}
				}

				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal."Inquiry");
				$selected = " selected";
				$disabled = " disabled";
				$optVal = "";

				$valAproved = $value['APPROVED_HOU'];

				if($valAproved == "Y")
				{
					$valSelected = 1;
				}
				elseif($valAproved == "N")
				{					
					$valSelected = 2;
				}else
				{
					$valSelected = 0;
				}

				for ($i=0; $i < count($optApproved) ; $i++) {

					$selected = ($i == $valSelected) ? " selected" : "";

					$optVal .= '<option value="'.$i.'"'.$selected.'>'.$optApproved[$i].'</option>';
				}

				$checkbox = '<div class="checkbox form-group m-b-0"><select id="checkbox-'.$replacejurnal.'" name="checkbox-data" class="checklist" data-id="'.$replacejurnal.'" class="form-control input-sm unit_opt select-center" '.$disabled.'>';
				$checkbox .= $optVal;
				$checkbox .=  '</select></div>';
			
				$remark = '<textarea id="remark-'.$replacejurnal.'" class="form-control remark" rows="3" disabled>'.$value['REMARK_APPROVED_HOU'].'</textarea>';

				$remark2 = '<textarea id="remark2-'.$replacejurnal.'" class="form-control remark2" rows="3" disabled>'.$value['REMARK_APPROVED'].'</textarea>';

				if($value['AR_IS_MORE_THAN_AP'] == 'Y')
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap_inquiry" type="checkbox" name="ismorethanap-data" value='.$value['AR_IS_MORE_THAN_AP'].' checked disabled><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap_inquiry" type="checkbox" name="ismorethanap-data"  value='.$value['AR_IS_MORE_THAN_AP'].' disabled><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}

				$tgl_invoice = date("d-m-Y",strtotime($value['TGL_INVOICE']));
				$invoice_date = ($value['INVOICE_DATE']) ? date("d-m-Y",strtotime($value['INVOICE_DATE'])) : '';

				$row[] = array(
					'no'             	=> $nomor,
					'tanggal_invoice'	=> $tgl_invoice,
					'invoice_date'		=> $invoice_date,
					'due_date'          => date("d-m-Y",strtotime($value['DUE_DATE'])),
					'batch_name'       	=> $value['BATCH_NAME'],
					'batch_description' => $value['BATCH_DESCRIPTION'],
					'nama_vendor'   	=> $value['NAMA_VENDOR'],
					'journal_name'      => $value['JOURNAL_NAME'],
					'no_invoice' 	    => $value['NO_INVOICE'],
					'no_kontrak'		=> $value['NO_KONTRAK'],
					'account_description' => $value['ACCOUNT_DESCRIPTION'],
					'nature'      		  => $value['NATURE'],
					'currency'       	  => $value['CURRENCY'],
					'debet'         	  => number_format($value['DEBET'],0,'.',','),
					'credit'       		  => number_format($value['CREDIT'],0,'.',','),
					'journal_description' => $value['DESCRIPTION'],
					'status'			  => $checkbox,
					'remark_approved_hou'	  => $remark,
					'remark_approved'  => $remark2,
					'user_login'		  => $user_login,
					'is_more_than_ap'	  => $checkbox2,
					'batch_approval'	  => $value['BATCH_APPROVAL_HOU']
				);

				$i++;
				$number++;
			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);

	}

	public function load_data_journal_after_tax(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$vendor_name = $this->input->post('vendor_name');
		$approved_status = $this->input->post('approved_status');
		$batch_appr = $this->input->post('batch_appr');

		$get_all = $this->user_approved_journal->get_journal_after_tax_datatable($vendor_name,$approved_status,$batch_appr);

		$get_all_fpjp = $this->gl->get_all_fpjp_in_gl();
		$data_fpjp = array();
		foreach ($get_all_fpjp as $key => $value) {
			$no_fpjp             = str_replace("/","_",$value['FPJP_NUMBER']);
			$doc_upload          = ($value['DOCUMENT_UPLOAD']) ? base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$value['DOCUMENT_UPLOAD'], true) : "";
			$pdf_fpjp            = base_url("fpjp/api/printPDF/") . encrypt_string($value['FPJP_HEADER_ID'], true);
			$data_fpjp[$no_fpjp] = array('pdf_fpjp' => $pdf_fpjp, 'doc_uploaded' => $doc_upload);
		}

		$get_all_gr_in_gl = $this->gl->get_all_gr_in_gl();
		$data_gr = array();
		foreach ($get_all_gr_in_gl as $key => $value) {
			$enc_key           = $value['PO_NUMBER']."_|_".$value['NO_INVOICE']."_|_".$value['TOTAL_PRICE'];
			$key               = encrypt_string($enc_key, true);
			$doc_upload        = ($value['GR_DOCUMENT']) ? base_url("download/") . encrypt_string("uploads/gr_attachment/".$value['GR_DOCUMENT'], true) : "";
			$data_gr[] = array( 'key' => $key, 'gr_id' => $value['GR_HEADER_ID'] , 'doc_uploaded' => $doc_upload );

		}

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nomor   = 1;
		$i=1;
		$nmjurnal = '';
		$replacejurnal ='';
		$user_login = get_user_data($this->session->userdata('user_id'));

		$optApproved = array("-- Choose --", "Approved", "Rejected");

		if($total > 0){
			
			foreach($data as $value) {

				if ($number == 1)
				{
					$nmjurnal =  $value['JOURNAL_NAME'];
					$nomor = 1;
				}
				else
				{
					if($nmjurnal ==  $value['JOURNAL_NAME'])
					{
						$nmjurnal =  $value['JOURNAL_NAME'];
						$nomor++;

					}
					else
					{
						$nmjurnal =  $value['JOURNAL_NAME'];
						$nomor = 1;
					}
				}

				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal);
				$selected = " selected";
				$disabled = " disabled";
				$optVal = "";

				$valAproved = $value['APPROVED_HOU'];

				// echo $valAproved ; die;

				if($valAproved == "Y")
				{
					$valSelected = 1;
				}
				elseif($valAproved == "N")
				{					
					$valSelected = 2;
				}else
				{
					$valSelected = 0;
				}

				$periodstatus = $value['STATUS_CLOSING'];

				if($value['STATUS_CLOSING'] == 'CLOSE')
				{
					$hiddenstats = "disabled";
				}
				else
				{
					$hiddenstats = "";
				}

				for ($i=0; $i < count($optApproved) ; $i++) {

					$selected = ($i == $valSelected) ? " selected" : "";

					$optVal .= '<option value="'.$i.'"'.$selected.'>'.$optApproved[$i].'</option>';
				}

				$checkbox = '<div class="checkbox form-group m-b-0"><select id="checkbox-'.$replacejurnal.'" name="checkbox-data" class="checklist" data-id="'.$replacejurnal.'" class="form-control input-sm unit_opt select-center" '.$hiddenstats.'>';
				$checkbox .= $optVal;
				$checkbox .=  '</select></div>';

				$remark = '<textarea id="remark-'.$replacejurnal.'" class="form-control remark" rows="3">'.$value['REMARK_APPROVED_HOU'].'</textarea>';

				$remark2 = '<textarea id="remark2-'.$replacejurnal.'" class="form-control remark2" rows="3" disabled>'.$value['REMARK_APPROVED'].'</textarea>';

				if($value['AR_IS_MORE_THAN_AP'] == 'Y')
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap" type="checkbox" name="ismorethanap-data" value='.$value['AR_IS_MORE_THAN_AP'].' checked disabled><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap" type="checkbox" name="ismorethanap-data"  value='.$value['AR_IS_MORE_THAN_AP'].' disabled><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}

				$fpjp_num = $value['NO_KONTRAK'];

				$actionAdded = '';
				if($fpjp_num != ''){
					$fpjp_num = str_replace("/","_", $fpjp_num);
					// if(in_array($fpjp_num, $data_fpjp)){
					if (array_key_exists($fpjp_num, $data_fpjp)) {
						$link_pdf = $data_fpjp[$fpjp_num]['pdf_fpjp'];
						$link_upload = $data_fpjp[$fpjp_num]['doc_uploaded'];
						$link_upload = ($link_upload != '') ? '&nbsp;&nbsp;&nbsp;<a href="'.$link_upload.'" title="Click to download attachment FPJP" target="_blank"><i class="fa fa-download text-success" aria-hidden="true"></i></a>' : '';

						$actionAdded = '&nbsp;&nbsp;&nbsp;<a href="'.$link_pdf.'" class="action-cetak px-5" title="Click to Download PDF File" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
						$actionAdded = $link_upload;
					}else{

						$no_invoice = $value['NO_INVOICE'];

						foreach ($data_gr as $x => $v_kontrak) {
							$key = decrypt_string($v_kontrak['key'], true);
							$exp = explode("_|_", $key);

							$v_no_po = $exp[0];
							$v_invoice = $exp[1];
							$v_dpp = $exp[2];

							if($v_no_po == $fpjp_num && $v_invoice == $no_invoice){
								$gr_id = $v_kontrak['gr_id'];
								$link_upload = $v_kontrak['doc_uploaded'];
								$link_upload = ($link_upload != '') ? '<a href="'.$link_upload.'" class="px-5" title="Click to download attachment kontrak" target="_blank"><i class="fa fa-download text-info" aria-hidden="true"></i></a>' : '';
								$actionAdded = $link_upload;

							}
						}
					}
				}

				$action = $actionAdded;

				$tgl_invoice  = date("d-m-Y",strtotime($value['TGL_INVOICE']));
				$invoice_date = ($value['INVOICE_DATE']) ? date("d-m-Y",strtotime($value['INVOICE_DATE'])) : '';

				$row[] = array(
					'no'                  => $nomor,
					'tanggal_invoice'     => $tgl_invoice,
					'invoice_date'        => $invoice_date,
					'due_date'            => date("d-m-Y",strtotime($value['DUE_DATE'])),
					'batch_name'          => $value['BATCH_NAME'],
					'batch_description'   => $value['BATCH_DESCRIPTION'],
					'nama_vendor'         => $value['NAMA_VENDOR'],
					'journal_name'        => $value['JOURNAL_NAME'],
					'no_invoice'          => $value['NO_INVOICE'],
					'no_kontrak'          => $value['NO_KONTRAK'],
					'account_description' => $value['ACCOUNT_DESCRIPTION'],
					'nature'              => $value['NATURE'],
					'currency'            => $value['CURRENCY'],
					'debet'               => number_format($value['DEBET'],0,'.',','),
					'credit'              => number_format($value['CREDIT'],0,'.',','),
					'journal_description' => $value['DESCRIPTION'],
					'status'              => $checkbox,
					'remark_approved_hou' => $remark,
					'remark_approved'     => $remark2,
					'gl_header_id'        => $value['GL_HEADER_ID'],
					'user_login'          => $user_login,
					'is_more_than_ap'     => $checkbox2,
					'batch_approval'      => $value['BATCH_APPROVAL_HOU'],
					'period_status'       => $periodstatus,
					'action'              => $action
				);

				$i++;
				$number++;
			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);

	}


	function download_data_after_tax()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "No.");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Tanggal Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Due Date");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Batch Description");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "No Kontrak");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Status");
		// $excel->setActiveSheetIndex(0)->setCellValue('P1', "STATUS");
		// $excel->setActiveSheetIndex(0)->setCellValue('Q1', "Remark");

		$approved_date_from = $this->input->get('approved_date_from');
		$approved_date_to = $this->input->get('approved_date_to');
		$vendor_name = $this->input->get('vendor_name');
		$filter_date = $this->input->get('filter_date');

		$hasil = $this->user_approved_journal->get_download_after_tax($approved_date_from,$approved_date_to,$vendor_name,$filter_date);

		$numrow  = 2;
		$number = 1;
		$nomor = 0;
		$nmjurnal = '';

		foreach($hasil->result_array() as $row)	{

			if ($number == 1)
			{
				$nmjurnal =  $row['JOURNAL_NAME'];
				$nomor = 1;
			}
			else
			{
				if($nmjurnal ==  $row['JOURNAL_NAME'])
				{
					$nmjurnal =  $row['JOURNAL_NAME'];
					$nomor++;

				}
				else
				{
					$nmjurnal =  $row['JOURNAL_NAME'];
					$nomor = 1;
				}
			}

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $nomor);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['TGL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['DUE_DATE']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['BATCH_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['CREDIT']);
			$excel->getActiveSheet()->getStyle('M'.$numrow.":N".$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['APPROVED_HOU']);
			// $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['STATUS']);
			// $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['REMARK']);

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
		header('Content-Disposition: attachment; filename="Approved Hou.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function load_ddl_all_vendor_1m()
	{
		$hasil	= $this->user_approved_journal->get_all_vendor_1m();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Vendor --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_VENDOR']."' data-name='".$row['NAMA_VENDOR']."' >".$row['NAMA_VENDOR']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	//kodingan lama
	function update_user_approved_journal()
	{
		$data_line = $this->input->post('data_line');
		$user_login = get_user_data($this->session->userdata('user_id'));

		$approvedval = "";

		$random_number  = generateRandomString(3, 'number');
		$batch_approval   = get_shorted_user($this->session->userdata('user_id'))."/".$random_number.date("/dm/y");

		// $get_user_approval_journal = get_user_approval_ap("HOG");
		$get_user_verif = get_user_approval_ap("APPROVED_JOURNAL");
		$get_user = NULL;
		$dataapprove = NULL;
		$datareject = NULL;

		foreach ($data_line as $key => $value) 
		{
			$batch_approval_old = $value['batch_approval'];
			$approvedval = $value['status'];

			if($approvedval == "N")
			{
				$get_user = $get_user_verif;
				$batch_approval = $batch_approval_old;

				$data[] = array(
					"BATCH_APPROVAL_HOG"		=> '',
					"NO_JOURNAL" 				=> $value['nojournal'],
					"APPROVED_HOU" 				=> $value['status'],
					"REMARK_APPROVED_HOU"       => $value['remark_approved'],
					"APPROVED_HOU_DATE" 		=> $value['approved_date']
				);

				$datareject = $data;

			}
			else
			{
				$get_user = $get_user_approval_journal;

				$data[] = array(
					"BATCH_APPROVAL_HOG"		=> $batch_approval,
					"NO_JOURNAL" 				=> $value['nojournal'],
					"APPROVED_HOU" 				=> $value['status'],
					"REMARK_APPROVED_HOU"       => $value['remark_approved'],
					"APPROVED_HOU_DATE" 		=> $value['approved_date']
				);

				$dataapprove = $data;
			}
	
		}

		$update   	= $this->crud->update_batch_data("GL_HEADERS", $data, "NO_JOURNAL");

		//$update   	= true;
		
		if($update)
		{
			/*if($dataapprove){
			if($get_user){
							foreach ($get_user as $key => $value) {
								$recipient['name']  = $value['PIC_NAME'];
								$recipient['email'] = $value['PIC_EMAIL'];
								$this->_email_verification($recipient, $batch_approval, 'Y');
							}
						}

			}*/
			
			if($datareject){
				if($get_user){
					foreach ($get_user as $key => $value) {
						$recipient['name']  = $value['PIC_NAME'];
						$recipient['email'] = $value['PIC_EMAIL'];
						$this->_email_verification($recipient, $batch_approval, 'N');
					}
				}
			}

			echo '1';
		} 
		else 
		{			
			echo '0';
		}
		
	}

	public function load_ddl_filter_date_by()
	{		
		$result  = "";
		$result .= "<option value='' data-name='' > -- Please Choose -- </option>";
		$result .= "<option value='1' data-name='APPROVED_DATE' > Approved HoU Date </option>";
		$result .= "<option value='3' data-name='APPROVED_LEAD_DATE' > Approved Lead Date </option>";
		$result .= "<option value='2' data-name='RECEIVE_DATE' > Received Date </option>";

		echo $result;
	}

	private function _email_verification($recipient, $batch_approval, $validatedval){

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient']  = $recipient['name'];
		if($validatedval == 'Y')
		{
			$get_gl = $this->user_approved_journal->get_journal_to_approvehog($batch_approval)->result_array();
			$data['email_preview'] = "A new invoice has been approved and need for your approval with Batch Approval ".$batch_approval;

			$email_body = "A new invoice with Batch Approval Journal <b>$batch_approval</b> has been Approved by <b>$action_name</b> and need for your approval.";

			if($get_gl)
			{

			$email_body .=	"
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
														<th>Due Date</th>
														<th>Nama Journal</th>
														<th>Account Description</th>
													</tr>";
												}

		$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_approval), true);
		$data['link'] = base_url("user-approved-journalhog/").$encrypt_batch_approve;
		$data['link_display'] = "Financetools User Approved Journal Hog".$batch_approval;

		$subject    = "Invoice Approval Request - $batch_approval";

		}
		else
		{
			$get_gl = $this->user_approved_journal->get_journal_to_approved_journal($batch_approval)->result_array();
			$data['email_preview'] = "An invoice has been Rejected";

			$email_body = "An invoice  has been Rejected by <b>$action_name</b>.";

			if($get_gl)
			{

			$email_body .=	"
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
														<th>Due Date</th>
														<th>Nama Journal</th>
														<th>Account Description</th>
													</tr>";
												}

			$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_approval), true);
			$data['link'] = base_url("user-approved-journal/").$encrypt_batch_approve;
			$data['link_display'] = "Financetools User Approved Journal ".$batch_approval;

		    $subject    = "Invoice Rejected Request";
		}

		
		if($get_gl)
		{

		$no = 1;

		if($get_gl)
		{

		foreach ($get_gl as $key => $value) {

			$transaction_date    = date("d-m-Y",strtotime($value['TGL_INVOICE']));
			$journal_name        = $value['JOURNAL_NAME'];
			$due_date    = date("d-m-Y",strtotime($value['DUE_DATE']));
			$acc_desc        = $value['ACCOUNT_DESCRIPTION'];
	

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$transaction_date."</td>";
				$email_body .= "<td>".$due_date."</td>";
				$email_body .= "<td>".$journal_name."</td>";
				$email_body .= "<td>".$acc_desc."</td>";
			$email_body .= "</tr>";
			$no++;
		}

	}

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$body       = $this->load->view('email/ap_apprroval_request', $data, TRUE);
		// $attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';
		$body       = $this->load->view('email/ap_apprroval_request', $data, TRUE);

		$send = sendemail($to, $subject, $body, $cc);

		}
		else
		{
		   $send = false;
		}

		

		return $send;
	}

}



/* End of file Userapprovedjournalhou.php */

/* Location: ./application/controllers/Userapprovedjournalhou.php */