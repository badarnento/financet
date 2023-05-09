<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uservalidate extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('uservalidate_mdl', 'uservalidate');
		$this->load->model('GL_mdl', 'gl');

	}

	public function user_validate($batch_approval ="")
	{
		
		if($this->ion_auth->is_admin() == true || in_array("uservalidate/user-validate", $this->session->userdata['menu_url']) ){
			$data['title']          = "User Verification";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/user_validate";

			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "User Verification", "link" => "", "class" => "active" );

			$data['status'] = array('');
			$data['all_vendor'] = get_all_vendor_gl();

			$data['all_batch_approval'] = get_all_batch_approval_validate();

			$querystring = ($batch_approval) ? str_replace("-","/", decrypt_string($batch_approval, true)) : '';

			$optVal = '<option value="" data-name="">-- Choose  --</option>';
			foreach ($data['all_batch_approval'] as $key => $value):
				$selected = ($value['BATCH_APPROVAL'] == $querystring) ? ' selected' : '';
				$optVal .= '<option value="' . $value['BATCH_APPROVAL'] . '" data-name="' . $value['BATCH_APPROVAL'] . '"' . $selected . '> ' . $value['BATCH_APPROVAL'] . ' </option>';
			endforeach;

			$data['opt_val_batch'] = $optVal;

			$data['batch_approval'] = ($batch_approval) ? str_replace("-","/", decrypt_string($batch_approval, true)) : '';

			// echo_pre($data);die;

			$optValfilterdate = '<option value="" data-name="">-- Choose  --</option>';
			$optValfilterdate .= "<option value='1' data-name='VERIFICATED_DATE' > Verificated Date </option>";
			$optValfilterdate .= "<option value='2' data-name='RECEIVE_DATE' > Receive Date </option>";

			$data['opt_val_filterdate'] = $optValfilterdate;


			$optValstatus = '<option value="" data-name="">-- Choose  --</option>';
			$optValstatus .= "<option value='"."Y"."' data-name='"."Y"."' >"."Verificated"."</option>";
			$optValstatus .= "<option value='"."N"."' data-name='"."N"."' >"."Unverificated"."</option>";	

			$data['opt_val_status'] = $optValstatus;


			$optValverified = "<option value='"."N"."' data-name='"."N"."' >"."Unverificated"."</option>";
		    $optValverified .= "<option value='"."Y"."' data-name='"."Y"."' >"."Verificated"."</option>";		

			$data['opt_val_verified'] = $optValverified;


			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	function load_ddl_verified()
	{		
		$result ="";
		// $result .= "<option value='' data-name='' >-- Choose Status --</option>";
		$result .= "<option value='"."N"."' data-name='"."N"."' >"."Unverificated"."</option>";
		$result .= "<option value='"."Y"."' data-name='"."Y"."' >"."Verificated"."</option>";	
		echo $result;

	}

	function load_ddl_all_status()
	{		
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Status --</option>";
		$result .= "<option value='"."Y"."' data-name='"."Y"."' >"."Verificated"."</option>";
		$result .= "<option value='"."N"."' data-name='"."N"."' >"."Unverificated"."</option>";	
		echo $result;

	}

	public function load_ddl_filter_date_by()
	{		
		$result  = "";
		$result .= "<option value='' data-name='' > -- Please Choose -- </option>";
		$result .= "<option value='1' data-name='VERIFICATED_DATE' > Verificated Date </option>";
		$result .= "<option value='2' data-name='RECEIVE_DATE' > Receive Date </option>";

		echo $result;
	}

	function load_ddl_Approve_batch()
	{
		$hasil	= $this->uservalidate->get_approve_batch();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Batch Approval --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['BATCH_APPROVAL']."' data-name='".$row['BATCH_APPROVAL']."' >".$row['BATCH_APPROVAL']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	public function load_data_inquiry(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('verificated_date_from') != "" && $this->input->post('verificated_date_to') != "")
		{
			$exp_verificated_date_from = explode("/", $this->input->post('verificated_date_from'));
			$exp_verificated_date_to   = explode("/", $this->input->post('verificated_date_to'));

			$verificated_date_from     = $exp_verificated_date_from[2]."-".$exp_verificated_date_from[1]."-".$exp_verificated_date_from[0];
			$verificated_date_to       = $exp_verificated_date_to[2]."-".$exp_verificated_date_to[1]."-".$exp_verificated_date_to[0];
		}

		$vendor_name = $this->input->post('vendor_name');
		$filter_date = $this->input->post('filter_date');
		$verificatedstatus = $this->input->post('verificatedstatus');
		$emailuser = $this->session->userdata('email');

		$get_all = $this->uservalidate->get_journal_inquiry_datatable($verificated_date_from,$verificated_date_to,$vendor_name,$filter_date,$verificatedstatus,$emailuser );
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nomor   = 1;
		$i=1;
		$nmjurnal = '';
		$replacejurnal ='';

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

				// $checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$value['JOURNAL_NAME'].'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" ><label class="m-0 p-0" for="checkbox-'.$value['JOURNAL_NAME'].'"></label></div>';
				// $remark = '<textarea id="remark-'.$value['JOURNAL_NAME'].'" class="form-control remark" rows="3"></textarea>';

				if($value['VERIFICATED'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist_inquiry" type="checkbox" name="checkbox-data" value='.$value['VERIFICATED'].' checked disabled><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist_inquiry" type="checkbox" name="checkbox-data"  value='.$value['VERIFICATED'].' disabled><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}

				$remark = '<textarea id="remark-'.$replacejurnal.'" class="form-control remarks" rows="3" disabled>'.$value['REMARK_VERIFICATED'].'</textarea>';

				$remark2 = '<textarea id="remark2-'.$replacejurnal.'" class="form-control remarks2" rows="3" disabled>'.$value['REMARK_APPROVED'].'</textarea>';


				if($value['AR_IS_MORE_THAN_AP'] == 'Y')
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap_inquiry" type="checkbox" name="ismorethanap-data" value='.$value['AR_IS_MORE_THAN_AP'].' checked disabled><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap_inquiry" type="checkbox" name="ismorethanap-data"  value='.$value['AR_IS_MORE_THAN_AP'].' disabled><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}

				$action = '<a href="javascript:void(0)" class="action-cetak px-5" title="Click to Download PDF File" id="isaction-'.$replacejurnal.'"  data-id=""><i class="fa fa-file-pdf-o" aria-hidden="true" for="isaction-'.$replacejurnal.'"></i></a>';

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
					'validated_value'	  => $value['VERIFICATED'],
					'validated'			  => $checkbox,
					'remark'			  => $remark,
					'remark_approved'	  => $remark2,
					'is_more_than_ap'	  => $checkbox2,
					'batch_approval'	  => $value['BATCH_APPROVAL'],
					'action'	  		  => $action
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

		// if($this->input->post('invoice_date_from') != "" && $this->input->post('invoice_date_to') != ""){
		// 	$exp_date_from = explode("/", $this->input->post('invoice_date_from'));
		// 	$exp_date_to   = explode("/", $this->input->post('invoice_date_to'));

		// 	$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		// 	$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		// }

		// $get_all = $this->uservalidate->get_journal_after_tax_datatable($invoice_date_from, $invoice_date_to);
		// bypass gk pake tanggal

		$vendor_name = $this->input->post('vendor_name');
		$verified_status = $this->input->post('verified_status');
		$batch_appr = $this->input->post('batch_appr');

		$emailuser = $this->session->userdata('email');

		$get_all = $this->uservalidate->get_journal_after_tax_datatable($vendor_name,$verified_status,$batch_appr,$emailuser);

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

				// $checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$value['JOURNAL_NAME'].'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" ><label class="m-0 p-0" for="checkbox-'.$value['JOURNAL_NAME'].'"></label></div>';
				// $remark = '<textarea id="remark-'.$value['JOURNAL_NAME'].'" class="form-control remark" rows="3"></textarea>';

				$periodstatus = $value['STATUS_CLOSING'];

				if($value['STATUS_CLOSING'] == 'CLOSE')
				{
					$hiddenstats = "disabled";
				}
				else
				{
					$hiddenstats = "";
				}

				if($value['VERIFICATED'] == 'Y')
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['VERIFICATED'].' checked '.$hiddenstats.'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['VERIFICATED'].' '.$hiddenstats.'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}

				$remark = '<textarea id="remark-'.$replacejurnal.'" class="form-control remarks" rows="3">'.$value['REMARK_VERIFICATED'].'</textarea>';

				$remark2 = '<textarea id="remark2-'.$replacejurnal.'" class="form-control remarks2" rows="3" disabled>'.$value['REMARK_APPROVED'].'</textarea>';

				if($value['AR_IS_MORE_THAN_AP'] == 'Y')
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap" type="checkbox" name="ismorethanap-data" value='.$value['AR_IS_MORE_THAN_AP'].' checked><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox2 = '<div class="checkbox checkbox-danger"><input id="ismorethanap-'.$replacejurnal.'" class="checkbox-data ismorethanap" type="checkbox" name="ismorethanap-data"  value='.$value['AR_IS_MORE_THAN_AP'].'><label class="m-0 p-0" for="ismorethanap-'.$replacejurnal.'"></label></div>';
				}

				$action = '<a href="javascript:void(0)" class="action-cetak px-5" title="Download versheet" id="isaction-'.$replacejurnal.'"  data-id=""><i class="fa fa-file-pdf-o" aria-hidden="true" for="isaction-'.$replacejurnal.'"></i></a>';

				$fpjp_num = $value['NO_KONTRAK'];

				$actionAdded = '';
				if($fpjp_num != ''){
					$fpjp_num = str_replace("/","_", $fpjp_num);
					// if(in_array($fpjp_num, $data_fpjp)){
					if (array_key_exists($fpjp_num, $data_fpjp)) {
						$link_pdf = $data_fpjp[$fpjp_num]['pdf_fpjp'];
						$link_upload = $data_fpjp[$fpjp_num]['doc_uploaded'];
						$link_upload = ($link_upload != '') ? '&nbsp;&nbsp;&nbsp;<a href="'.$link_upload.'" title="Click to download attachment FPJP" target="_blank"><i class="fa fa-download text-success" aria-hidden="true"></i></a>' : '';

						$actionAdded = '&nbsp;&nbsp;&nbsp;<a href="'.$link_pdf.'" class="action-cetak-fpjp px-5" title="Click to Download PDF File" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
						$actionAdded .= $link_upload;
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

				$fpjp_source = $actionAdded;

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
					'validated_value'	  => $value['VERIFICATED'],
					'validated'			  => $checkbox,
					'remark'			  => $remark,
					'remark_approved'	  => $remark2,
					'gl_header_id'        => $value['GL_HEADER_ID'],
					'is_more_than_ap'	  => $checkbox2,
					'batch_approval'	  => $value['BATCH_APPROVAL'],
					'period_status' 	  => $periodstatus,
					'action'	  		  => $action,
					'fpjp_source'		  => $fpjp_source
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Invoice Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Received Date");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Due Date");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Batch Description");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "No Kontrak");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Status");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Validated");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Notes");
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "No Faktur Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "Tanggal Faktur Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('V1', "NPWP");
		$excel->setActiveSheetIndex(0)->setCellValue('W1', "Kode Objek Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('X1', "Tax Code Desc");
		$excel->setActiveSheetIndex(0)->setCellValue('Y1', "KAP");
		$excel->setActiveSheetIndex(0)->setCellValue('Z1', "KJS");

		$verificated_date_from = $this->input->get('verificated_date_from');
		$verificated_date_to = $this->input->get('verificated_date_to');
		$vendor_name = $this->input->get('vendor_name');
		$filter_date = $this->input->get('filter_date');
		$verificated_status = $this->input->get('verificated_status');

		$hasil = $this->uservalidate->get_download_after_tax($verificated_date_from,$verificated_date_to,$vendor_name,$filter_date,$verificated_status);

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

			$tgl_faktur_pajak = ($row['TGL_FAKTUR_PAJAK'] == '0000-00-00') ? '' : $row['TGL_FAKTUR_PAJAK']; 
			$npwp = (empty($row['NPWP'])) ? '' : $row['NPWP'];

			$tgl_invoice  = date("d-m-Y",strtotime($row['TGL_INVOICE']));
			$invoice_date = ($row['INVOICE_DATE']) ? date("d-m-Y",strtotime($row['INVOICE_DATE'])) : '';

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $nomor);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $tgl_invoice);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $invoice_date);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d/m/Y", strtotime($row['DUE_DATE'])));
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['BATCH_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['CREDIT']);
			$excel->getActiveSheet()->getStyle('N'.$numrow.":O".$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['VERIFICATED']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['VALIDATED']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['NOTES']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $row['FAKTUR_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $tgl_faktur_pajak);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $npwp);
			$excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $row['KODE_OBJEK_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $row['TAX_CODE_DESC']);
			$excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $row['KAP']);
			$excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $row['KJS']);

			$number++;
			$numrow++;

		}

		// Set width kolom
		$loop_column = horizontal_loop_excel("A", 26);
		foreach ($loop_column as $key => $value) {
			$excel->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
		}

		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Data");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="User Validate.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function update_user_validate()
	{
		$data_line = $this->input->post('data_line');
		$user_login = get_user_data($this->session->userdata('user_id'));


		// echo print_r($data_line); die;
		$random_number  = generateRandomString(3, 'number');
		$batch_approval   = get_shorted_user($this->session->userdata('user_id'))."/".$random_number.date("/dm/y");

		$get_user_verif = get_user_approval_ap("APPROVED_JOURNAL");
		$get_user_unverif = get_user_approval_ap("INVOICE_TAX");
		$get_user = NULL;


		foreach ($data_line as $key => $value) {

			$remarks = '';
			$validatedval = $value['validated'];
			$remark_verificated = $value['remark'];
			$batch_approval_old = $value['batch_approval'];

			// echo $batch_approval_old; die;

			if($validatedval == 'Y' &&  $remark_verificated == '' || strpos($remark_verificated, 'Un'))
			{
				$remarks = ' Verificated by : '.$user_login;
			}
			elseif($validatedval == 'N' && $remark_verificated == '')
			{
				$remarks = ' Unverificated by : '.$user_login;
			}
			else
			{
				$remarks = $remark_verificated;
			}

			// $arr_no_journal[] = $value['nojournal'];


			 if($validatedval == 'Y')
			{
				$get_user = $get_user_verif;

				$data[] = array(
				
				"BATCH_APPROVAL_LEAD" => $batch_approval,
				"NO_JOURNAL"          => $value['nojournal'],
				"VERIFICATED"         => $value['validated'],
				"VALIDATED"           => $value['validated'],
				"REMARK_VERIFICATED"  => $remarks,
				"VERIFICATED_DATE"    => $value['verificated_date'],
				"APPROVED"            => NULL
			);
			}
			else
			{
				$get_user = $get_user_unverif;
				$batch_approval = $batch_approval_old;

				$data[] = array(
				
				"BATCH_APPROVAL_LEAD" => '',
				"NO_JOURNAL"          => $value['nojournal'],
				"VERIFICATED"         => $value['validated'],
				"VALIDATED"           => $value['validated'],
				"REMARK_VERIFICATED"  => $remarks,
				"VERIFICATED_DATE"    => $value['verificated_date'],
				"APPROVED"            => NULL
			);
			}



		}


		$update       = $this->crud->update_batch_data("GL_HEADERS", $data, "NO_JOURNAL");
		if($update)
		{

			if($get_user){
				foreach ($get_user as $key => $value) {
					$recipient['name']  = $value['PIC_NAME'];
					$recipient['email'] = $value['PIC_EMAIL'];
					$this->_email_verification($recipient, $batch_approval, $validatedval);
				}
			}

			echo '1';
		} else {			
			echo '0';
		}
		
	}

	// function update_user_validate()
	// {

	// 	$verified_status  = $this->input->post('verified_status');
	// 	$user_login = get_user_data($this->session->userdata('user_id'));
	// 	$nojournal = $this->input->post('nojournal');
	// 	$verificated_date = $this->input->post('verificated_date');

	// 	$status       = false;
	// 	$messages     = "";

	// 	if($verified_status == 'Y')
	// 	{
	// 		$remark_verificated = ' Verificated by : '.$user_login;
	// 	}
	// 	elseif($verified_status == 'N')
	// 	{
	// 		$remark_verificated = ' Unverificated by : '.$user_login;
	// 	}

	// 	$data = array(
	// 					'VERIFICATED' => $verified_status,
	// 					'VERIFICATED_DATE' => $verificated_date,
	// 					'REMARK_VERIFICATED' => $remark_verificated
	// 				);

	// 	$update = $this->crud->update("GL_HEADERS", $data, array("NO_JOURNAL" => $nojournal));

	// 	if($update !== -1){
	// 		$status = true;
	// 		if ($verified_status == 'Y')
	// 		{
	// 		$messages = "Data has been Verified";
	// 		}
	// 		else
	// 		{
	// 	    $messages = "Data has been Unverified !!";
	// 		}
	// 	}

	// 	$result['status']   = $status;
	// 	$result['messages'] = $messages;


	// 	echo json_encode($result);
	// }

	function update_ismorethanap()
	{

		$ismorethanap_status  = $this->input->post('ismorethanap_status');
		$nojournal = $this->input->post('nojournal');

		$status       = false;
		$messages     = "";

		$data = array(
						'AR_IS_MORE_THAN_AP' => $ismorethanap_status
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("NO_JOURNAL" => $nojournal));

		if($update !== -1){
			$status = true;
			if ($ismorethanap_status == 'Y')
			{
			$messages = "AR is more than AP";
			}
			else
			{
		    $messages = "AR is less than AP !!";
			}
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

	// function update_remark()
	// {

	// 	$remark_verificated  = $this->input->post('remark_verificated');
	// 	$validatedval  = $this->input->post('validatedval');
	// 	$user_login = get_user_data($this->session->userdata('user_id'));
	// 	$nojournal = $this->input->post('nojournal');
		
	// 	$status       = false;
	// 	$messages     = "";

	// 	if($validatedval == 'Y' &&  $remark_verificated == '' || strpos($remark_verificated, 'Un'))
	// 	{
	// 		$remark_verificated = ' Verificated by : '.$user_login;
	// 	}
	// 	elseif($validatedval == 'N' && $remark_verificated == '')
	// 	{
	// 		$remark_verificated = ' Unverificated by : '.$user_login;
	// 	}

	// 	$data = array(
	// 					'REMARK_VERIFICATED' => $remark_verificated
	// 				);

	// 	$update = $this->crud->update("GL_HEADERS", $data, array("NO_JOURNAL" => $nojournal));

	// 	if($update !== -1){
	// 		$status = true;
	// 		$messages = "Remark has change";
	// 	}

	// 	$result['status']   = $status;
	// 	$result['messages'] = $messages;


	// 	echo json_encode($result);
	// }



	private function _email_verification($recipient, $batch_approval, $validatedval){

		$action_name = get_user_data($this->session->userdata('user_id'));

		$data['email_recipient']  = $recipient['name'];
		if($validatedval == 'Y')
		{
			$get_gl = $this->uservalidate->get_journal_to_verification($batch_approval)->result_array();
			$data['email_preview'] = "A new invoice has been verified and need for your approval with Batch Approval ".$batch_approval;

			$email_body = "A new invoice with Batch Approval Journal <b>$batch_approval</b> has been verified by <b>$action_name</b> and need for your approval.";

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
														<th>Amount</th>
														<th>Account Description</th>
													</tr>";
		}

		$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_approval), true);
		$data['link'] = base_url("user-approved-journal/").$encrypt_batch_approve;
		$data['link_display'] = "Financetools User Approved Journal ".$batch_approval;

		$subject    = "Invoice Approval Request - $batch_approval";

		}
		else
		{
			$get_gl = $this->uservalidate->get_journal_to_usertax($batch_approval)->result_array();
			$data['email_preview'] = "An invoice has been unverified";

			$email_body = "An invoice  has been unverified by <b>$action_name</b>. 
						";
			if($get_gl)
			{

			$email_body .=	"<br>
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
														<th>Amount</th>
														<th>Account Description</th>
													</tr>";
			}

			$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_approval), true);
			$data['link'] = base_url("user-tax/").$encrypt_batch_approve;
			$data['link_display'] = "Financetools Invoice Tax ".$batch_approval;

		    $subject    = "Invoice Unverification";

		}

		

		$no = 1;

		if($get_gl)
		{

		foreach ($get_gl as $key => $value) {

			$transaction_date = date("d-m-Y",strtotime($value['TGL_INVOICE']));
			$journal_name = $value['JOURNAL_NAME'];
			$due_date     = ($value['DUE_DATE']) ? date("d-m-Y",strtotime($value['DUE_DATE'])) : '';
			$acc_desc     = $value['ACCOUNT_DESCRIPTION'];
			$credit       = number_format($value['CREDIT'],2,',','.');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$transaction_date."</td>";
				$email_body .= "<td>".$due_date."</td>";
				$email_body .= "<td>".$journal_name."</td>";
				$email_body .= "<td>".$credit."</td>";
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

		$send = sendemail($to, $subject, $body, $cc);

		return $send;
	}
	

	public function printPDF(){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}

		/*$mpdf = new \Mpdf\Mpdf();
		$data = $this->load->view('pages/cetakan', [], TRUE);
		$mpdf->WriteHTML($data);
		$mpdf->Output();*/
		$vendor_name = $this->input->get('vendor_name');

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/invoice_verif_new.pdf';

		if($_REQUEST['invoice_date_from'] != "" && $_REQUEST['invoice_date_to'] != ""){
			$exp_date_from = explode("/", $_REQUEST['invoice_date_from']);
			$exp_date_to   = explode("/", $_REQUEST['invoice_date_to']);

			$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$hasil = $this->uservalidate->get_cetak($vendor_name, $invoice_date_from, $invoice_date_to);
		$data = $hasil['data'];

		$user   = get_user_data($this->session->userdata('user_id'));

		foreach ($data as $row){

			$tgl_verificated = date("d/m/Y",strtotime($row['VERIFICATED_DATE']));
			$due_date 	  = '-';

			if($row['DUE_DATE']){
				$exp_due_date = explode("-", $row['DUE_DATE']);
				$due_date 	  = $exp_due_date[2]."/".$exp_due_date[1]."/".$exp_due_date[0];
			}

			$judul = "Cetak Invoice Verification Form";
			$mpdf->SetTitle($judul);

			$mpdf->AddPage();
			$mpdf->setSourceFile($fh);
			$tplId = $mpdf->importPage(1);
			$mpdf->useTemplate($tplId);

			$mpdf->SetTextColor(0,0,0);
			$mpdf->SetFont('Courier New','',8);

			$guideline = 0;

			$tgl = date("d - F - Y",time());

			$height = 25.4;
			$mpdf->SetXY(139, $height);
			$mpdf->Cell(10,10,$tgl,$guideline,1,"L");

			$height = 35;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NO_INVOICE'],$guideline,1,"L");

			$height =38;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NO_KONTRAK'],$guideline,1,"L");

			$height = 41.3;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NAMA_VENDOR'],$guideline,1,"L");

			$height = 44.5;
			$mpdf->SetXY(69, $height);

			if($row['CURRENCY'] == "USD")
			{
			  $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"L");
			}
			else
			{
			  $mpdf->Cell(10,10,number_format($row['TOTAL_PPH'],0,',','.'),$guideline,1,"L");
			}

			$height = 47.6;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['CURRENCY'],$guideline,1,"L");

			$height = 50.9;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NO_JOURNAL'],$guideline,1,"L");

			if($row['NO_JOURNAL'] != ""){
				$height = 60.2;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 60.2;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			/*$height = 71.2;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			if($row['NO_INVOICE'] != ""){
				$height = 66.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 66.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			if($row['FAKTUR_PAJAK'] != ""){
				$height = 69.7;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 69.7;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			if($row['PERCENTAGE_PPH'] != "" && $row['PERCENTAGE_PPH'] != "0"){
				$height = 72.9;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 72.9;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			if($row['DENDA']){
				$height = 79.3;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			}

			/*$height = 84;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			/*$height = 87.2;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			if($row['IS_BAST']=='Y'){
				$height = 82.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			}else{
				$height = 82.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			/*$height = 93.6;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			/*$height = 96.9;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			$desc1 = substr($row['DESCRIPTION'],0,87);
			$desc2 = substr($row['DESCRIPTION'],87,200);

			$height = 95.4;
			$mpdf->SetXY(48, $height);
			$mpdf->Cell(10,10,$desc1,$guideline,1,"L");

			$height = 98.5;
			$mpdf->SetXY(48, $height);
			$mpdf->Cell(10,10,$desc2,$guideline,1,"L");

			// $height = 117.5;
			// $mpdf->SetXY(75, $height);

			if($row['CURRENCY'] == "USD")
			{
			  $height = 117.5;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

			  $height = 117.5;
			  $mpdf->SetXY(109, $height);
			  $mpdf->Cell(10,10,number_format($row['NOMINAL'],0,',','.'),$guideline,1,"R");
			}
			else
			{

			  $height = 117.5;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP'],0,',','.'),$guideline,1,"R");
			}

			// $ppn_idr = $row['NOMINAL'] * (10/100);

			if($row['CURRENCY'] == "USD")
			{

			  $height = 122;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,"0",$guideline,1,"R");

			  // $height = 122;
			  // $mpdf->SetXY(109, $height);
			  // $mpdf->Cell(10,10,number_format($ppn_idr,0,',','.'),$guideline,1,"R");
			}
			else
			{

			  $height = 122;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP_PPN'],0,',','.'),$guideline,1,"R");
			}

			// $total_idr = $row['NOMINAL'] + $ppn_idr;
		
			if($row['CURRENCY'] == "USD")
			{

			  $height = 129.3;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

			  $height = 129.3;
			  $mpdf->SetXY(109, $height);
			  $mpdf->Cell(10,10,number_format($row['NOMINAL'],0,',','.'),$guideline,1,"R");
			}
			else
			{

			  $height = 129.3;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['TOTAL_PPN'],0,',','.'),$guideline,1,"R");
			}

			if($row['DENDA']){
				$height = 138.9;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($row['DENDA'],0,',','.'),$guideline,1,"R");
			}

			$height = 115.8;
			$mpdf->SetXY(151, $height);
			$mpdf->Cell(10,10,$row['NATURE'],$guideline,1,"L");

			$height = $height+40.3;
			$mpdf->SetXY(151, $height);
			$mpdf->Cell(10,10,$row['WHT_ACCOUNT'],$guideline,1,"L");

			$height = $height+9.6;
			$mpdf->SetXY(151, $height);
			$mpdf->Cell(10,10,$row['VAT_ACCOUNT'],$guideline,1,"L");

			if($row['VATSA_ACCOUNT'] !=""){
				$height = $height+6.9;
				$mpdf->SetXY(151, $height);
				$mpdf->Cell(10,10,$row['VATSA_ACCOUNT'],$guideline,1,"L");
			}else{
				$height = $height+6.9;
				$mpdf->SetXY(151, $height);
				$mpdf->Cell(10,10,"0",$guideline,1,"L");
			}

			if($row['CURRENCY'] == "IDR")
			{
			  $dpp_pph = "(".number_format($row['DPP_PPH'],0,',','.').")";
			}
			else
			{
			  $dpp_pph = "(".number_format($row['DPP_PPH'],0,',','.').")";
			}

			if ($row['CURRENCY'] == 'USD'){

				$height = 145;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"R");
			}else{

				$height = 145;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,$dpp_pph,$guideline,1,"R");
			}

			$pph 			= $row['NOMINAL'] * ($row['PPH']/100);
			$pph_ditanggung = $row['NOMINAL'] * (20/100);

			if($row['CURRENCY'] == 'USD'){
				if($row['COD'] == 20){

					$height = 149.5;
					$mpdf->SetXY(79, $height);
					$mpdf->Cell(10,10,"0",$guideline,1,"R");
				}else{
					$height = 149.5;
					$mpdf->SetXY(109, $height);
					$mpdf->Cell(10,10,number_format($pph_ditanggung,0,',','.'),$guideline,1,"R");
				}
			}else{
				$height = 149.5;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($pph,0,',','.'),$guideline,1,"R");
			}

			$height = 159.5;
			$mpdf->SetXY(79, $height);
			$mpdf->Cell(10,10,number_format($row['MATERAI'],0,',','.'),$guideline,1,"R");

			$subtotal_pph = ($row['NOMINAL'] + floatval($dpp_pph)) + ($pph_ditanggung + floatval($row['MATERAI']));
			$subtotal 	  = $row['NOMINAL'];
			$tot_pph 	  = floatval($row['DENDA']) + floatval($row['MATERAI']) + $row['TOTAL_PPH'];
			
			if($row['CURRENCY'] == "USD")
			{
				if($row['COD'] == 20){

					$height = 165.5;
				    $mpdf->SetXY(79, $height);
				    $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

				    $height = 165.5;
				    $mpdf->SetXY(109, $height);
				    $mpdf->Cell(10,10,number_format($subtotal,0,',','.'),$guideline,1,"R");
				}else{
					$height = 165.5;
				    $mpdf->SetXY(79, $height);
				    $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

				    $height = 165.5;
				    $mpdf->SetXY(109, $height);
				    $mpdf->Cell(10,10,number_format($subtotal_pph,0,',','.'),$guideline,1,"R");
				}
			}
			else
			{

			  $height = 167.3;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($tot_pph,0,',','.'),$guideline,1,"R");
			}

			$vatsa = $row['NOMINAL'] * ($row['VAT']/100);
			
			if($row['CURRENCY'] == 'USD'){

				$height = 174.5;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($vatsa,0,',','.'),$guideline,1,"R");

				$height = 174.5;
				$mpdf->SetXY(109, $height);
				$mpdf->Cell(10,10,number_format($vatsa,0,',','.'),$guideline,1,"R");

			}else{
				$height = 174.5;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($vatsa,0,',','.'),$guideline,1,"R");
			}

			$height = 197;
			$mpdf->SetXY(34, $height);
			$mpdf->Cell(10,10,$user,$guideline,1,"L");

			$height = 197;
			$mpdf->SetXY(96, $height);
			$mpdf->Cell(10,10,$tgl_verificated,$guideline,1,"L");

			$height = 249;
			$mpdf->SetXY(54, $height);
			$mpdf->Cell(10,10,$row['NAMA_REKENING'],$guideline,1,"L");

			$height = 252;
			$mpdf->SetXY(54, $height);
			$mpdf->Cell(10,10,$row['NAMA_BANK'],$guideline,1,"L");

			$height = 255.3;
			$mpdf->SetXY(54, $height);
			$mpdf->Cell(10,10,$row['ACCT_NUMBER'],$guideline,1,"L");

			$height = 248.5;
			$mpdf->SetXY(148, $height);
			$mpdf->Cell(10,10,"EFT",$guideline,1,"L");

			$height = 251.5;
			$mpdf->SetXY(148, $height);
			$mpdf->Cell(10,10,$row['TOP'],$guideline,1,"L");

			$height = 254.5;
			$mpdf->SetXY(148, $height);
			$mpdf->Cell(10,10,$due_date,$guideline,1,"L");

		}
		$title = "Invoice Verification Form";
		$mpdf->Output($title, "I");
	}

	public function printPDFLine(){

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
		    @set_time_limit(300);
		}
		$jurnal_name = $this->input->get('jurnal_name');

		$mpdf = new \Mpdf\Mpdf();

		$fh = 'assets/templates/invoice_verif_new.pdf';

		$hasil = $this->uservalidate->get_cetak_jurnal($jurnal_name);
		$data = $hasil['data'];

		$user   = get_user_data($this->session->userdata('user_id'));

		foreach ($data as $row){

			$tgl_verificated = ($row['VERIFICATED_DATE']) ?  date("d/m/Y",strtotime($row['VERIFICATED_DATE'])) : '';
			$due_date = '-';

			if($row['DUE_DATE']){
				$exp_due_date = explode("-", $row['DUE_DATE']);
				$due_date     = $exp_due_date[2]."/".$exp_due_date[1]."/".$exp_due_date[0];
			}


			$mpdf->AddPage();
			$mpdf->setSourceFile($fh);
			$tplId = $mpdf->importPage(1);
			$mpdf->useTemplate($tplId);

			$mpdf->SetTitle("Journal No : ".$jurnal_name);

			$mpdf->SetTextColor(0,0,0);
			$mpdf->SetFont('Courier New','',8);

			$guideline = 0;

			$tgl = date("d - F - Y",time());

			$height = 25.4;
			$mpdf->SetXY(139, $height);
			$mpdf->Cell(10,10,$tgl,$guideline,1,"L");

			$height = 35;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NO_INVOICE'],$guideline,1,"L");

			$height =38;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NO_KONTRAK'],$guideline,1,"L");

			$height = 41.3;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NAMA_VENDOR'],$guideline,1,"L");

			$height = 44.5;
			$mpdf->SetXY(69, $height);

			if($row['CURRENCY'] == "USD")
			{
			  $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"L");
			}
			else
			{
			  $mpdf->Cell(10,10,number_format($row['TOTAL_PPH'],0,',','.'),$guideline,1,"L");
			}

			$height = 47.6;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['CURRENCY'],$guideline,1,"L");

			$height = 50.9;
			$mpdf->SetXY(69, $height);
			$mpdf->Cell(10,10,$row['NO_JOURNAL'],$guideline,1,"L");

			if($row['NO_JOURNAL'] != ""){
				$height = 60.2;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 60.2;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			/*$height = 71.2;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			if($row['NO_INVOICE'] != ""){
				$height = 66.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 66.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			if($row['FAKTUR_PAJAK'] != ""){
				$height = 69.7;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 69.7;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			if($row['PERCENTAGE_PPH'] != "" && $row['PERCENTAGE_PPH'] != "0"){
				$height = 72.9;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			} else{
				$height = 72.9;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			if($row['DENDA']){
				$height = 79.3;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			}

			/*$height = 84;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			/*$height = 87.2;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			if($row['IS_BAST']=='Y'){
				$height = 82.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"√",$guideline,1,"C");
			}else{
				$height = 82.5;
				$mpdf->SetXY(76, $height);
				$mpdf->Cell(10,10,"",$guideline,1,"C");
			}

			/*$height = 93.6;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			/*$height = 96.9;
			$mpdf->SetXY(76, $height);
			$mpdf->Cell(10,10,"√",$guideline,1,"C");*/

			$desc1 = substr($row['DESCRIPTION'],0,100);
			$desc2 = substr($row['DESCRIPTION'],100,100);
			$desc3 = substr($row['DESCRIPTION'],200,100);

			$height = 95.4;
			$mpdf->SetXY(48, $height);
			$mpdf->Cell(10,10,$desc1,$guideline,1,"L");

			$height = 98.5;
			$mpdf->SetXY(48, $height);
			$mpdf->Cell(10,10,$desc2,$guideline,1,"L");

			$height = 101.5;
			$mpdf->SetXY(48, $height);
			$mpdf->Cell(10,10,$desc3,$guideline,1,"L");

			// $height = 117.5;
			// $mpdf->SetXY(75, $height);

			if($row['CURRENCY'] == "USD")
			{
			  $height = 117.5;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

			  $height = 117.5;
			  $mpdf->SetXY(109, $height);
			  $mpdf->Cell(10,10,number_format($row['NOMINAL'],0,',','.'),$guideline,1,"R");
			}
			else
			{

			  $height = 117.5;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP'],0,',','.'),$guideline,1,"R");
			}

			// $ppn_idr = $row['NOMINAL'] * (10/100);

			if($row['CURRENCY'] == "USD")
			{

			  $height = 122;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,"0",$guideline,1,"R");

			  // $height = 122;
			  // $mpdf->SetXY(109, $height);
			  // $mpdf->Cell(10,10,number_format($ppn_idr,0,',','.'),$guideline,1,"R");
			}
			else
			{

			  $height = 122;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP_PPN'],0,',','.'),$guideline,1,"R");
			}

			// $total_idr = $row['NOMINAL'] + $ppn_idr;
		
			if($row['CURRENCY'] == "USD")
			{

			  $height = 129.3;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

			  $height = 129.3;
			  $mpdf->SetXY(109, $height);
			  $mpdf->Cell(10,10,number_format($row['NOMINAL'],0,',','.'),$guideline,1,"R");
			}
			else
			{

			  $height = 129.3;
			  $mpdf->SetXY(79, $height);
			  $mpdf->Cell(10,10,number_format($row['TOTAL_PPN'],0,',','.'),$guideline,1,"R");
			}

			if($row['DENDA']){
				$height = 138.9;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($row['DENDA'],0,',','.'),$guideline,1,"R");
			}

			$height = 115.8;
			$mpdf->SetXY(151, $height);
			$mpdf->Cell(10,10,$row['NATURE'],$guideline,1,"L");

			$height = $height+40.3;
			$mpdf->SetXY(151, $height);
			$mpdf->Cell(10,10,$row['WHT_ACCOUNT'],$guideline,1,"L");

			$height = $height+9.6;
			$mpdf->SetXY(151, $height);
			$mpdf->Cell(10,10,$row['VAT_ACCOUNT'],$guideline,1,"L");

			if($row['VATSA_ACCOUNT'] !=""){
				$height = $height+6.9;
				$mpdf->SetXY(151, $height);
				$mpdf->Cell(10,10,$row['VATSA_ACCOUNT'],$guideline,1,"L");
			}else{
				$height = $height+6.9;
				$mpdf->SetXY(151, $height);
				$mpdf->Cell(10,10,"0",$guideline,1,"L");
			}

			// $dpp_pph = "(".number_format($row['DPP_PPH'],0,',','.').")";
			$dpp_pph = number_format($row['DPP_PPH'],0,',','.');

			if ($row['CURRENCY'] == 'USD'){

				$height = 145;
				$mpdf->SetXY(109, $height);
				$mpdf->Cell(10,10,$dpp_pph,$guideline,1,"R");
			}else{

				$height = 145;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,$dpp_pph,$guideline,1,"R");
			}

			/*$pph 			= $row['NOMINAL'] * ($row['PPH_DITANGGUNG']/100);
			$pph_ditanggung = $row['NOMINAL'] * (20/100);*/
			$pph_ditanggung = $row['PPH_DITANGGUNG'];

			if($row['CURRENCY'] == 'USD'){
				// if($row['COD'] == 20){

				// 	$height = 149.5;
				// 	$mpdf->SetXY(79, $height);
				// 	$mpdf->Cell(10,10,"0",$guideline,1,"R");
				// }else{
					$height = 149.5;
					$mpdf->SetXY(109, $height);
					$mpdf->Cell(10,10,number_format($pph_ditanggung,0,',','.'),$guideline,1,"R");
				// }
			}else{
				$height = 149.5;
				$mpdf->SetXY(79, $height);
				// $mpdf->Cell(10,10,number_format($pph,0,',','.'),$guideline,1,"R");
				$mpdf->Cell(10,10,number_format($pph_ditanggung,0,',','.'),$guideline,1,"R");
			}

			$height = 159.5;
			$mpdf->SetXY(79, $height);
			$mpdf->Cell(10,10,number_format($row['MATERAI'],0,',','.'),$guideline,1,"R");

			$subtotal_pph = ($row['NOMINAL'] + floatval($dpp_pph)) + ($pph_ditanggung + floatval($row['MATERAI']));
			$subtotal 	  = $row['NOMINAL'];
			$tot_pph 	  = floatval($row['DENDA']) + floatval($row['MATERAI']) + $row['TOTAL_PPH'] + floatval($pph_ditanggung);
			
			if($row['CURRENCY'] == "USD")
			{
				// if($row['COD'] == 20){

				// 	$height = 165.5;
				//     $mpdf->SetXY(79, $height);
				//     $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

				//     $height = 165.5;
				//     $mpdf->SetXY(109, $height);
				//     $mpdf->Cell(10,10,number_format($subtotal,0,',','.'),$guideline,1,"R");
				// }else{
					$height = 165.5;
				    $mpdf->SetXY(79, $height);
				    $mpdf->Cell(10,10,number_format($row['DPP'],2,',','.'),$guideline,1,"R");

				    $height = 165.5;
				    $mpdf->SetXY(109, $height);
				    $mpdf->Cell(10,10,number_format($subtotal_pph,0,',','.'),$guideline,1,"R");
				// }
			}
			else
			{

			  $height = 167.3;
			  $mpdf->SetXY(79, $height);

			  $mpdf->Cell(10,10,number_format($tot_pph,0,',','.'),$guideline,1,"R");
			  // $mpdf->Cell(10,10,number_format($row['PPH_DITANGGUNG'],0,',','.'),$guideline,1,"R");
			}

			// $vatsa = $row['NOMINAL'] * ($row['VATSA']/100);
			$vatsa = $row['VATSA'];
			
			if($row['CURRENCY'] == 'USD'){

				/*$height = 174.5;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($vatsa,0,',','.'),$guideline,1,"R");*/

				$height = 174.5;
				$mpdf->SetXY(109, $height);
				$mpdf->Cell(10,10,number_format($vatsa,0,',','.'),$guideline,1,"R");

			}else{
				$height = 174.5;
				$mpdf->SetXY(79, $height);
				$mpdf->Cell(10,10,number_format($vatsa,0,',','.'),$guideline,1,"R");
			}

			$height = 197;
			$mpdf->SetXY(34, $height);
			$mpdf->Cell(10,10,$user,$guideline,1,"L");

			$height = 197;
			$mpdf->SetXY(96, $height);
			$mpdf->Cell(10,10,$tgl_verificated,$guideline,1,"L");

			$height = 249;
			$mpdf->SetXY(54, $height);
			$mpdf->Cell(10,10,$row['NAMA_REKENING'],$guideline,1,"L");

			$height = 252;
			$mpdf->SetXY(54, $height);
			$mpdf->Cell(10,10,$row['NAMA_BANK'],$guideline,1,"L");

			$height = 255.3;
			$mpdf->SetXY(54, $height);
			$mpdf->Cell(10,10,$row['ACCT_NUMBER'],$guideline,1,"L");

			$height = 248.5;
			$mpdf->SetXY(148, $height);
			$mpdf->Cell(10,10,"EFT",$guideline,1,"L");

			$height = 251.5;
			$mpdf->SetXY(148, $height);
			$mpdf->Cell(10,10,$row['TOP'],$guideline,1,"L");

			$height = 254.5;
			$mpdf->SetXY(148, $height);
			$mpdf->Cell(10,10,$due_date,$guideline,1,"L");

		}
		$mpdf->Output("Journal No ".$jurnal_name, "I");
	}

}



/* End of file userValidate.php */

/* Location: ./application/controllers/userValidate.php */