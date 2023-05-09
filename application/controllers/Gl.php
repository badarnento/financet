<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('GL_mdl', 'gl');

	}

	public function gl_header()
	{
		
		if($this->ion_auth->is_admin() == true || in_array("gl/gl-header", $this->session->userdata['menu_url']) ){

			$data['title']          = "GL Headers";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/gl_header";

			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;
			
			$this->template->load('main', $data['template_page'], $data);
		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_gl_header(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$invoice_date_from = "";
		$invoice_date_to   = "";

		if($this->input->post('invoice_date_from') != "" && $this->input->post('invoice_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('invoice_date_from'));
			$exp_date_to   = explode("/", $this->input->post('invoice_date_to'));

			$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$vendor_name = $this->input->post('vendor_name');

		$get_all      = $this->gl->get_gl_header_datatable($invoice_date_from, $invoice_date_to, $vendor_name);
		$get_all_fpjp = $this->gl->get_all_fpjp_in_gl();
		$data_fpjp = array();
		foreach ($get_all_fpjp as $key => $value) {
			$no_fpjp             = str_replace("/","_",$value['FPJP_NUMBER']);
			$doc_upload          = ($value['DOCUMENT_UPLOAD']) ? base_url("download/") . encrypt_string("uploads/fpjp_attachment/".$value['DOCUMENT_UPLOAD'], true) : "";
			$pdf_fpjp            = base_url("fpjp/api/printPDF/") . encrypt_string($value['FPJP_HEADER_ID'], true);
			$notes_user          = ($value['NOTES_USER']) ? $value['NOTES_USER'] : '-';
			$data_fpjp[$no_fpjp] = array('pdf_fpjp' => $pdf_fpjp, 'doc_uploaded' => $doc_upload, 'notes_user' => $notes_user);

		}

		$get_all_gr_in_gl = $this->gl->get_all_gr_in_gl();
		$data_gr = array();
		foreach ($get_all_gr_in_gl as $key => $value) {
			$enc_key           = $value['PO_NUMBER']."_|_".$value['NO_INVOICE']."_|_".$value['TOTAL_PRICE'];
			$key               = encrypt_string($enc_key, true);
			$doc_upload        = ($value['GR_DOCUMENT']) ? base_url("download/") . encrypt_string("uploads/gr_attachment/".$value['GR_DOCUMENT'], true) : "";
			$data_gr[] = array( 'key' => $key, 'gr_id' => $value['GR_HEADER_ID'] , 'doc_uploaded' => $doc_upload );

		}

		// echo_pre($data_gr);die;

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$no_jurnal = "";
		$nomor   = 1;

		if($total > 0){

			foreach($data as $value) {

				$action = "";

				if ($number == 1)
				{
					$nomor = 1;
					$no_jurnal = $value['NO_JOURNAL'];
				}
				else
				{
					if($no_jurnal == $value['NO_JOURNAL']){
						$nomor++;
						$no_jurnal = $value['NO_JOURNAL'];
					}else{
						$nomor = 1;
						$no_jurnal = $value['NO_JOURNAL'];
					}
				}

				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $no_jurnal);
				$fpjp_num = $value['NO_FPJP'];
				$actionAdded = '';
				$notes_user = '';

				if($fpjp_num != ''){
					$fpjp_num = str_replace("/","_", $fpjp_num);
					if (array_key_exists($fpjp_num, $data_fpjp)) {
						$link_pdf = $data_fpjp[$fpjp_num]['pdf_fpjp'];
						$notes_user  = $data_fpjp[$fpjp_num]['notes_user'];
						$link_upload = $data_fpjp[$fpjp_num]['doc_uploaded'];
						$link_upload = ($link_upload != '') ? '<a href="'.$link_upload.'" class="px-5" title="Click to download attachment FPJP" target="_blank"><i class="fa fa-download text-info" aria-hidden="true"></i></a>' : '';
						$actionAdded = '<a href="'.$link_pdf.'" class="action-cetak px-5" title="Click to view FPJP Detail" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
						$actionAdded .= $link_upload;
					}
				}

				$no_kontrak = $value['NO_KONTRAK'];
				$gr_id = 0;
				if($no_kontrak != ''){
					$no_invoice = $value['NO_INVOICE'];
					$dpp        = (int) $value['DPP'];
					foreach ($data_gr as $x => $v_kontrak) {
						$key = decrypt_string($v_kontrak['key'], true);
						$exp = explode("_|_", $key);

						$v_no_po = $exp[0];
						$v_invoice = $exp[1];
						$v_dpp = $exp[2];

						if($v_no_po == $no_kontrak && $v_invoice == $no_invoice && $v_dpp == $dpp){
							$gr_id = $v_kontrak['gr_id'];
							$link_upload = $v_kontrak['doc_uploaded'];
							$link_upload = ($link_upload != '') ? '<a href="'.$link_upload.'" class="px-5" title="Click to download attachment kontrak" target="_blank"><i class="fa fa-download text-info" aria-hidden="true"></i></a>' : '';
							$actionAdded .= $link_upload;
						}
					}
				}

				if( $value['VALIDATED'] == "Y"  || $value['VERIFICATED'] == "Y" )
				{
					$action = '<a href="javascript:void(0)" class="no-action px-5" title="Unable to edit"><i class="fa fa-edit text-grey" aria-hidden="true"></i></a><a href="javascript:void(0)" class="no-action px-5" title="Approved"><i class="fa fa-check-circle text-grey" aria-hidden="true"></i></a><a href="javascript:void(0)" class="no-action px-5" title="Unable to reject"><i class="fa fa-times text-grey" aria-hidden="true"></i></a>';
				}
				else
				{
					if($value['APPROVED_INVOICE'] == "Y"){
						$action = '<a href="javascript:void(0)" class="action-edit px-5" title="Click to edit"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a><a href="javascript:void(0)" class="no-action px-5" title="Approved"><i class="fa fa-check-circle text-grey" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-delete px-5" title="Click to reject or delete"><i class="fa fa-times text-danger" aria-hidden="true"></i></a>';
					}else{
						$action = '<a href="javascript:void(0)" class="action-edit px-5" title="Click to edit"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-approve px-5" title="Click to approve"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></a><a href="javascript:void(0)" class="action-delete px-5" title="Click to reject or delete"><i class="fa fa-times text-danger" aria-hidden="true"></i></a>';
					}
				}

				$action .= $actionAdded;

				$checkedBast = ($value['IS_BAST'] == 'Y') ? ' checked' : '';
				$checkbox = '<div class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value="'.$value['IS_BAST'].'"'.$checkedBast.'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				$dpp = number_format($value['DPP'],0,'.',',');

				$valDenda = "";
				if($value['DENDA'] != ""){
					$valDenda = $value['DENDA'];
				}
				$denda = '<div class="form-group m-b-0"><input id="denda-'.$value['GL_HEADER_ID'].'" class="form-control input-sm denda text-left" value="'.$valDenda.'" placeholder="DENDA" maxlength="20"></div>';
				$valMaterai = "";
				if($value['MATERAI'] != ""){
					$valMaterai = $value['MATERAI'];
				}
				$materai = '<div class="form-group m-b-0"><input id="materai-'.$value['GL_HEADER_ID'].'" class="form-control input-sm materai text-left" value="'.$valMaterai.'" placeholder="MATERAI" maxlength="20"></div>';
				$tgl_invoice = date("d-m-Y",strtotime($value['TGL_INVOICE']));
				$invoice_date = ($value['INVOICE_DATE']) ? date("d-m-Y",strtotime($value['INVOICE_DATE'])) : '';

				$row[] = array(
					'no'              => $nomor,
					'gl_header_id'    => $value['GL_HEADER_ID'],
					'tanggal_invoice' => $tgl_invoice,
					'invoice_date'    => $invoice_date,
					'batch_name'      => $value['BATCH_NAME'],
					'no_journal'      => $value['NO_JOURNAL'],
					'journal_encypt'  => base64url_encode($value['NO_JOURNAL'].$this->config->item('encryption_key')),
					'nama_vendor'     => $value['NAMA_VENDOR'],
					'no_invoice'      => $value['NO_INVOICE'],
					'no_kontrak'      => $value['NO_KONTRAK'],
					'description'     => $value['DESCRIPTION'],
					'currency'        => $value['CURRENCY'],
					'dpp'             => $dpp,
					'kurs'            => $value['KURS'],
					'nominal_rate'    => number_format($value['NOMINAL_RATE'],0,'.',','),
					'no_fpjp'         => $value['NO_FPJP'],
					'gr_id'           => $gr_id,
					'nama_rekening'   => $value['NAMA_REKENING'],
					'nama_bank'       => $value['NAMA_BANK'],
					'acct_number'     => $value['ACCT_NUMBER'],
					'rkap_name'       => $value['RKAP_NAME'],
					'top'             => $value['TOP'],
					'due_date'        => ($value['DUE_DATE']) ? date("d-m-Y",strtotime($value['DUE_DATE'])) : '-',
					'nature'          => $value['NATURE'],
					'notes_user'      => $notes_user,
					'denda'           => number_format($value['DENDA'],0,',','.'),
					'materai'         => $value['MATERAI'],
					'is_bast'         => $checkbox,
					'action'          => $action,
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

	function load_ddl_all_vendor()
	{
		$hasil	= $this->gl->get_all_vendor();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Vendor --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_VENDOR']."' data-name='".$row['NAMA_VENDOR']."' >".$row['NAMA_VENDOR']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_vendor()
	{
		$hasil	= $this->gl->get_master_vendor();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Vendor --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_VENDOR']."' data-name='".$row['NAMA_VENDOR']."' >".$row['NAMA_VENDOR']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	function load_ddl_bank()
	{
		$hasil	= $this->gl->get_master_bank();
		$query  = $hasil['query'];			
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Bank --</option>";
		foreach($query->result_array() as $row)	{
			$result .= "<option value='".$row['NAMA_BANK']."' data-name='".$row['NAMA_BANK']."' >".$row['NAMA_BANK']."</option>";
		}		
		echo $result;
		$query->free_result();

	}

	public function load_data_journal_before_tax(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('invoice_date_from') != "" && $this->input->post('invoice_date_to') != ""){
			$exp_date_from = explode("/", $this->input->post('invoice_date_from'));
			$exp_date_to   = explode("/", $this->input->post('invoice_date_to'));

			$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$vendor_name = $this->input->post('vendor_name');

		$get_all = $this->gl->get_journal_before_tax_datatable($invoice_date_from, $invoice_date_to, $vendor_name);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$no_jurnal = "";
		$nomor   = 1;

		if($total > 0){

			foreach($data as $value) {

				if ($number == 1)
				{
					$nomor = 1;
					$no_jurnal = $value['JOURNAL_NAME'];
				}
				else
				{
					if($no_jurnal == $value['JOURNAL_NAME']){
						$nomor++;
						$no_jurnal = $value['JOURNAL_NAME'];
					}else{
						$nomor = 1;
						$no_jurnal = $value['JOURNAL_NAME'];
					}
				}

				$tgl_invoice  = date("d-m-Y",strtotime($value['TGL_INVOICE']));
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
					'journal_description' => $value['DESCRIPTION']
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

	public function create_gl_lines(){
		$insert = 1;

		if($insert > 0){
			$result['status']    = true;
		}
		else{
			$result['status']   = false;
			$result['messages'] = "Failed to Create GL Lines";
		}

		echo json_encode($result);

	}

	public function gl_lines($batch_names){
		$data['title']         = "Create GL Lines";
		$data['module']        = "datatable";
		$data['template_page'] = "pages/gl_detail_create";
		// echo $batch_names;  die();
		$decrypt = base64url_decode($batch_names);
		$data['batch_names']    = $decrypt;
		// echo $decrypt;  die();

		$this->template->load('main', $data['template_page'], $data);
	}


	public function save_import_gl_header()
	{

		$this->load->library('excel');

		if(isset($_FILES["file"]["name"])) {

			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			$req_id = generateRandomString(6).substr(time(), 0, 4);

			$topNull 		   = false;
			$dppNull           = false;
			$no_kontrakNull    = false;
			$natureNull        = false;
			$nama_rekeningNull = false;
			//$dppCheck 		   = false;
			$no = 0;

			$batch_name_usr   = get_shorted_user($this->session->userdata('user_id')).date("d/")."AP/".date("my");
			$get_last_journal = ($this->gl->get_last_journal($batch_name_usr));

			//coment heri
			/*if($get_last_journal):
				$journal = ltrim($get_last_journal['JOURNAL'], '0');
				$no      = $journal+1;
			endif;*/

			//add heri 19/03/2020

			if($get_last_journal):
				$journal = ltrim($get_last_journal['JOURNAL'], '0');
				$no      = $journal;
			endif;

			$last_invoice = "";
			$last_kontrak = "";


			foreach($object->getWorksheetIterator() as $worksheet){

				$highestRow    = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();

				for($row=2; $row<=$highestRow; $row++){
					$lineNum = $row-1;

					$month_exp = [];

					$tanggal_invoice    = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$dateValue 		 	= PHPExcel_Shared_Date::ExcelToPHP($tanggal_invoice);                       
					$convertdate     	= date('Y-m-d',$dateValue);
					$invoice_date    	= $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$dateValue_id 		= PHPExcel_Shared_Date::ExcelToPHP($invoice_date);                       
					$convertdate_id     = date('Y-m-d',$dateValue_id);
					$batch_name         = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$no_journal       	= $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$nama_vendor        = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$no_invoice   		= $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$description 		= $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$no_kontrak      	= $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$no_fpjp  			= $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$dpp  				= $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$nama_rekening      = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$nama_bank       	= $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					$acct_number        = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$top    			= $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					$nature    			= $worksheet->getCellByColumnAndRow(14, $row)->getValue();
					$currency    		= $worksheet->getCellByColumnAndRow(15, $row)->getValue();
					$due_date 			= date('Y-m-d', strtotime($convertdate . ' + '.$top.' day'));

					$no_urut_data       = "";


					// print_r($check_dpp);die();

					/*print_r($check_dpp);die();

					if($dpp > $check_dpp){
						$dppCheck 	    	 = true;
						$errorLineDP/PCheck[] = $lineNum;
					}*/

					$no_kontrak_arr[] = array("NO_KONTRAK" => $no_kontrak, "DPP" => $dpp);
					$no_fpjp_arr[]    = array("NO_FPJP" => $no_fpjp, "DPP" => $dpp);


					if($top == ""){
						$topNull 	    = true;
						$errorLineTop[] = $lineNum;
					}
					elseif($dpp == ""){
						$dppNull        = true;
						$errorLineDPP[] = $lineNum;
					}
					else{
						if($no_kontrak == "" && $no_fpjp == "" && $nature == ""){
							$no_kontrakNull     = true;
							$errorLineKontrak[] = $lineNum;
						}
					}
					if($nama_rekening == "" || $nama_bank == "" || $acct_number == ""){
						$nama_rekeningNull = true;
						$errorLineRek[]    = $lineNum;
					}

					if($last_invoice == $no_invoice && $last_kontrak == $no_kontrak){
					}
					else{
						$no++;
					}

					$no = sprintf("%'02d", $no);

					$no_jurnal_new = $no."/".$batch_name_usr;

					if($invoice_date == ""){
						$convertdate_id = date("Y-m-d",time());
					}

					$data[] = array(
						'TGL_INVOICE'		 	 => $convertdate,
						'INVOICE_DATE'		 	 => $convertdate_id,
						'BATCH_NAME'             => $batch_name_usr,
						'NO_JOURNAL'       		 => $no_jurnal_new,
						'NAMA_VENDOR'            => $nama_vendor,
						'NO_INVOICE'   			 => $no_invoice,
						'NO_KONTRAK'      	  	 => $no_kontrak,
						'DESCRIPTION' 			 => $description,
						'DPP'					 => $dpp,
						'NO_FPJP'  				 => $no_fpjp,
						'NAMA_REKENING'      	 => $nama_rekening,
						'NAMA_BANK'       		 => $nama_bank,
						'ACCT_NUMBER'         	 => $acct_number,
						'TOP'    				 => $top,
						'DUE_DATE' 		     	 => $due_date,
						'NATURE' 		     	 => $nature,
						'NO_URUT_JURNAL' 		 => $no_urut_data,
						'CURRENCY' 		         => $currency
					);

					$last_invoice = $no_invoice;
					$last_kontrak = $no_kontrak;
				}
			}

			foreach ($no_kontrak_arr as $key => $row)
			{
			    $sort[$key] = $row['NO_KONTRAK'];
			}
			array_multisort($sort, SORT_ASC, $no_kontrak_arr);
			$lastVal = "";
			$lastDpp = 0;

			foreach ($no_kontrak_arr as $key => $value) {

				$no_kontrak = $value['NO_KONTRAK'];

				if($no_kontrak == $lastVal):
					$dpp_kontrak[$no_kontrak] = $value['DPP']+$lastDpp;
				else:
					$dpp_kontrak[$no_kontrak] = $value['DPP'];
				endif;

				$lastVal = $value['NO_KONTRAK'];
				$lastDpp = $value['DPP'];

			}

			$errorListKontrak= array();

			foreach ($dpp_kontrak as $key => $value) {
				$check_dpp = $this->gl->get_cek_dpp($key);

				if($check_dpp){
					if($check_dpp['PO_AMOUNT'] < $value){
						$errorListKontrak[] = $key;
					}
				}

			}

			foreach ($no_fpjp_arr as $key => $row)
			{
			    $sort[$key] = $row['NO_FPJP'];
			}
			array_multisort($sort, SORT_ASC, $no_fpjp_arr);
			$lastVal = "";
			$lastDpp = 0;

			foreach ($no_fpjp_arr as $key => $value) {

				$no_fpjp = $value['NO_FPJP'];

				if($no_fpjp == $lastVal):
					$dpp_fpjp[$no_fpjp] = $value['DPP']+$lastDpp;
				else:
					$dpp_fpjp[$no_fpjp] = $value['DPP'];
				endif;

				$lastVal = $value['NO_FPJP'];
				$lastDpp = $value['DPP'];

			}

			$errorListFpjp= array();

			foreach ($dpp_fpjp as $key => $value) {
				$check_dpp = $this->gl->get_cek_fpjp($key);

				if($check_dpp){
					if($check_dpp['AMOUNT_FPJP'] < $value){
						$errorListFpjp[] = $key;
					}
				}

			}

			usort($data, function ($item1, $item2) {
			    return $item1['NO_INVOICE'] <=> $item2['NO_INVOICE'];
			});


			$last_invoice = "";
			$last_kontrak = "";
			$last_no_fpjp  = "";

			$no_urut_invoice = 1;

			$line_gagal = array();
			$j =1;

			foreach ($data as $key => $value) {


				// if($check_exist > 0){

					$vendor = $value['NAMA_VENDOR'];
					$no_po  = $value['NO_KONTRAK'];

					/*foreach ($cek_vendor as $key=> $val) {
						
						if($vendor == ""){
						$new_vendor = $val['VENDOR_NAME'];
						}
					}*/

					$new_vendor = $vendor;

					if($vendor == ""){
						$cek_vendor = $this->gl->get_cek_data($no_po);
						$new_vendor = $cek_vendor['VENDOR_NAME'];
					}

					if($last_invoice == $value['NO_INVOICE'] && $last_kontrak == $value['NO_KONTRAK'] && $last_no_fpjp == $value['NO_FPJP']){
						$no_urut_invoice++;
					}else{
						$no_urut_invoice = 1;
					}

					$newData[] = array(
							'TGL_INVOICE'		 	 => $value['TGL_INVOICE'],
							'INVOICE_DATE'		 	 => $value['INVOICE_DATE'],
							'BATCH_NAME'             => $value['BATCH_NAME'],
							'NO_JOURNAL'       		 => $value['NO_JOURNAL'],
							'NAMA_VENDOR'            => $new_vendor,
							'NO_INVOICE'   			 => $value['NO_INVOICE'],
							'NO_KONTRAK'      	  	 => $value['NO_KONTRAK'],
							'DESCRIPTION' 			 => $value['DESCRIPTION'],
							'DPP'					 => $value['DPP'],
							'NO_FPJP'  				 => $value['NO_FPJP'],
							'NAMA_REKENING'      	 => $value['NAMA_REKENING'],
							'NAMA_BANK'       		 => $value['NAMA_BANK'],
							'ACCT_NUMBER'         	 => $value['ACCT_NUMBER'],
							'TOP'    				 => $value['TOP'],
							'DUE_DATE' 		     	 => $value['DUE_DATE'],
							'NATURE' 		     	 => $value['NATURE'],
							'NO_URUT_JURNAL' 		 => $no_urut_invoice,
							'CURRENCY' 		     	 => $value['CURRENCY']
						);
					$last_invoice = $value['NO_INVOICE'];
					$last_kontrak = $value['NO_KONTRAK'];
					$last_no_fpjp = $value['NO_FPJP'];

					/*$check_exist = $this->crud->check_exist("PO_LINES", array("PO_NUMBER" => $value['NO_KONTRAK'], "VENDOR_NAME" => $new_vendor));*/

					//$check_exist = $this->crud->check_exist("PO_LINES", array("VENDOR_NAME" => $new_vendor));

					// echo $check_exist."\n\r";

					/*if($check_exist < 1){
						$line_gagal[] = $j;
					}

					$j++;*/

				/*}else{
					$this->session->set_flashdata('warning', 'Import Gagal, check PO Number dan Vendor Name');
				}*/
			}

			// echo_pre($newData);

			// die;

			/*if($dppCheck){
				$errorMessages = "Nilai DPP lebih besar sama dengan po lines pada line ";
				$this->session->set_flashdata('warning', $errorMessages." ".implode(", ", $errorLineDPPCheck));
				redirect('gl/gl_header','refresh');
			}*/

			if($topNull){
				$errorMessages = "Nilai Top tidak boleh kosong pada line ";
				$this->session->set_flashdata('warning', $errorMessages." ".implode(", ", $errorLineTop));
				redirect('gl/gl_header','refresh');
			}
			elseif($dppNull){

				$errorMessages = "Nilai DPP tidak boleh 0 atau lebih besar dari PO Line pada line ";
				$this->session->set_flashdata('warning', $errorMessages." ".implode(", ", $errorLineDPP));
				redirect('gl/gl_header','refresh');

			}
			elseif($no_kontrakNull){
				$errorMessages = "No Kontrak, No FPJP dan Nature tidak boleh kosong pada line ";
				$this->session->set_flashdata('warning', $errorMessages." ".implode(", ", $errorLineKontrak));
				redirect('gl/gl_header','refresh');

			}
			elseif($nama_rekeningNull){
				$errorMessages = "Nama rekening, Nama Bank, Acct Number tidak boleh kosong pada line ";
				$this->session->set_flashdata('warning', $errorMessages." ".implode(", ", $errorLineRek));
				redirect('gl/gl_header','refresh');	
			}elseif(count($errorListKontrak) > 0){
				$errorMessages = "Nilai DPP pada no kontrak ".implode(", ",$errorListKontrak)." lebih besar dari Total Amount PO";
				$this->session->set_flashdata('warning', $errorMessages);
				redirect('gl/gl_header','refresh');	
			}elseif(count($errorListFpjp) > 0){
				$errorMessages = "Nilai DPP pada no fpjp ".implode(", ",$errorListFpjp)." lebih besar dari Total Amount FPJP";
				$this->session->set_flashdata('warning', $errorMessages);
				redirect('gl/gl_header','refresh');	
			}
			/*elseif(count($line_gagal) > 0){
				$errorMessages = "Import Gagal, Check Vendor Name pada line ";
				$this->session->set_flashdata('warning', $errorMessages." ".implode(", ", $line_gagal));
				redirect('gl/gl_header','refresh');
			}*/
			else{

				$valuetrue = $this->gl->insert_gl_header_import($newData);
				// $valuetrue = $this->crud->create_batch("GL_HEADERS", $newData);
				if($valuetrue){
					if($this->crud->call_procedure("UPLOAD_BATCH") !== -1){

						if($this->crud->call_procedure("JURNAL_HEADERS") !== -1 && $this->crud->call_procedure("Journal_B_Tax") !== -1){
							$this->session->set_flashdata('messages', 'Import Success');
							redirect('gl/gl_header','refresh');
						}
					}
				}else{
					$this->session->set_flashdata('error', 'Import Gagal');
				}

			}
		}

	}


	function download_data_before_tax()
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Transaction Date");
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

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$vendor_name = $this->input->get('vendor_name');

		$hasil = $this->gl->get_download_before_tax($date_from,$date_to,$vendor_name);

		$numrow    = 2;
		$number = 1;
		$nomor = 0;

		foreach($hasil->result_array() as $row)	{

			if ($number % 2 == 1)
				{
					$nomor = 1;
				}
				else
				{
					$nomor = 2;
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
		header('Content-Disposition: attachment; filename="Before Tax.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}

	function download_data_gl_header()
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
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Transaction Date");
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "No Journal");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "No. Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "No Kontrak/PO/SPK/Nodin");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "No FPJP");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "DPP");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Kurs");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Nominal Rate");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Nama Rekening");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Nama Bank");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Acct Number");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "TOP");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Faktur Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "NPWP");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$vendor_name = $this->input->get('vendor_name');

		$hasil = $this->gl->get_download_gl_header($date_from,$date_to,$vendor_name);

		$numrow    = 2;

		foreach($hasil->result_array() as $row)	{

			// $tanggalinv = date("dd-MM-Yy", strtotime($row['TANGGAL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $row['TGL_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $row['BATCH_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $row['NO_JOURNAL']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $row['NAMA_VENDOR']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_FPJP']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['DPP']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['KURS']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['NOMINAL_RATE']);
			if(strtolower($row['CURRENCY']) == 'idr'){
				$excel->getActiveSheet()->getStyle('J'.$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			}
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['NAMA_REKENING']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['NAMA_BANK']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['ACCT_NUMBER']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['TOP']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['FAKTUR_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['NPWP']);

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
		header('Content-Disposition: attachment; filename="GL Header.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


	public function edit_upload_invoice()
	{
		$this->form_validation->set_rules('txtBatchName', 'Batch Name', 'trim|required');
		$this->form_validation->set_rules('txtNamaVendor', 'Vendor Name', 'trim|required');
		$this->form_validation->set_rules('txtNoInvoice', 'No Invoice', 'trim|required');
		// $this->form_validation->set_rules('txtDescription', 'Description', 'trim|required'); tidak diedit
		// $this->form_validation->set_rules('txtDPP', 'DPP', 'trim|required');  tidak diedit
		$this->form_validation->set_rules('txtNamaRekening', 'Nama Rekening', 'trim|required');
		$this->form_validation->set_rules('txtNamaBank', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('txtAcctNumber', 'Acct Number', 'trim|required');
		$this->form_validation->set_rules('txtTop', 'Top', 'trim|required');
		// $this->form_validation->set_rules('txtNature', 'Nature', 'trim|required');  tidak diedit
		// $this->form_validation->set_rules('txtDenda', 'Denda', 'trim|required');
		// $this->form_validation->set_rules('txtMaterai', 'Materai', 'trim|required');

		$no_journals = $this->input->post('txtNoJournal');
		$gl_header_id		= $this->input->post('gl_header_id');

		$inputtanggal_invoice	= $this->input->post('txtTanggalInvoice');
		$tanggal_invoice = str_replace('/', '-', $inputtanggal_invoice);
		$convert_tanggal_invoice = date("Y-m-d",strtotime($tanggal_invoice));

		$inputtanggal_invoiced	= $this->input->post('txtInvoiceDate');
		$tanggal_invoiced = str_replace('/', '-', $inputtanggal_invoiced);
		$convert_tanggal_invoiced = date("Y-m-d",strtotime($tanggal_invoiced));
		
		$batch_name			= $this->input->post('txtBatchName');
		$nama_vendor		= $this->input->post('txtNamaVendor');
		$no_invoice			= $this->input->post('txtNoInvoice');
		$no_kontrak			= $this->input->post('txtNoKontrak');
		$description		= $this->input->post('txtDescription');
		$dpp 				= preg_replace("/[^a-zA-Z0-9]/", "", $this->input->post('txtDPP'));
		$no_fpjp			= $this->input->post('txtNoFPJP');
		$nama_rekening		= $this->input->post('txtNamaRekening');
		$nama_bank			= $this->input->post('txtNamaBank');
		$acct_number		= $this->input->post('txtAcctNumber');
		$top				= $this->input->post('txtTop');
		$due_date			= date('Y-m-d', strtotime($convert_tanggal_invoice . ' + '.$top.' day'));
		$nature				= $this->input->post('txtNature');
		$denda				= $this->input->post('txtDenda');
		$materai			= $this->input->post('txtMaterai');


		$data = array(
			'TGL_INVOICE'		 	 => $convert_tanggal_invoice,
			'INVOICE_DATE'		 	 => $convert_tanggal_invoiced,
			'BATCH_NAME'             => $batch_name,
			'NAMA_VENDOR'            => $nama_vendor,
			'NO_INVOICE'   			 => $no_invoice,
			'NO_KONTRAK'      	  	 => $no_kontrak,
			// 'DESCRIPTION' 			 => $description,
			// 'DPP'					 => $dpp,
			'NO_FPJP'  				 => $no_fpjp,
			'NAMA_REKENING'      	 => $nama_rekening,
			'NAMA_BANK'       		 => $nama_bank,
			'ACCT_NUMBER'         	 => $acct_number,
			'TOP'    				 => $top,
			'DUE_DATE' 				 => $due_date,
			// 'NATURE' 				 => $nature,
			'DENDA' 				 => str_replace(".","",$denda),
			'MATERAI' 				 => $materai
		);

		if ($this->form_validation->run() == TRUE) 
		{
			// echo "masuk pak eko!!!!".$no_journals; die;

			$save = $this->gl->update_data_upload_invoice($data, $no_journals);

			if ($save)
			{
				$call_procedure_before_tax = $this->gl->call_procedure_update_before_tax();
				
				if($call_procedure_before_tax)
				{			
					echo '1';
				} else {			
					echo '0';
				}

			}
			else
			{
				echo '0';
			}

		}
		else
		{
			echo validation_errors();
		}

	}

	public function reject_upload_invoice()
	{
		

		$gl_header_id		= $this->input->post('gl_header_id');
		$journal_name		= $this->input->post('journal_name');
		$groupingstext		= $this->input->post('groupingstext');
		$groupings			= $this->input->post('groupings');
		$reason				= $this->input->post('reason');
		$attachment    	    = $this->input->post('attachment');
		$nomor_fpjp    	    = $this->input->post('nomor_fpjp');
		$id_gr    	    = $this->input->post('id_gr');
		$datafpjp = null;


		// echo_pre($_POST); die;

	    $journal_decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($journal_name));

		$result['status'] = false;
		$result['messages'] = "Failed to reject data";
		$Submitter="";
		$Email="";

		if($groupings != "" && $groupingstext == "No Kontrak")
		{
			$Data = $this->gl->getsubmitter_nokontrak($groupings);

			$gr_upd['STATUS'] = "returned";
			if($reason != ""){
				$gr_upd['STATUS_DESCRIPTION'] = $reason;
			}

			if($id_gr > 0){
				$this->crud->update("GR_HEADER", $gr_upd, array("GR_HEADER_ID" => $id_gr));
			}

			if($Data)
			{
			  $Submitter =	$Data['PIC_NAME'];
			  $Email = $Data['PIC_EMAIL'];
			}
		}
		if($groupings != "" && $groupingstext == "No FPJP")
		{
			$Data = $this->gl->getsubmitter_nofpjp($groupings);

			if($Data)
			{

				$Submitter = $Data['PIC_NAME'];
				$Email     = $Data['PIC_EMAIL'];
			}
			
			$fpjp_upd['STATUS'] = "returned";
			if($reason != ""){
				$fpjp_upd['STATUS_DESCRIPTION'] = $reason;
			}

			if($attachment != ""){
				$fpjp_upd['DOCUMENT_RETURN'] = $attachment;
			}

			$this->crud->update("FPJP_HEADER", $fpjp_upd, array("FPJP_NUMBER" => $groupings));


		}

		$delete = $this->gl->delete_data_upload_invoice($journal_decrypt);

		if($delete > 0)
		{

			$delete_before_tax = $this->gl->delete_data_before_tax($journal_decrypt);
			
			if($delete_before_tax > 0)
			{
				if($Submitter !="" && $Email !="")
				{
					$this->_email_reject($Submitter, $Email, $groupings, $groupingstext, $reason, $attachment);
				}
					echo '1';
			}
		}
		else
		{
					echo '0';
		}
	}

	public function approve_upload_invoice()
	{

		$journal_name		= $this->input->post('journal_name');
		$trigger		    = $this->input->post('trigger');

		$emailuser = $this->session->userdata('email');

		// echo_pre($_POST); die;

	    $journal_decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($journal_name));

		$result['status'] = false;
		$result['messages'] = "Failed to Approve data";

		$data = array(
			'APPROVED_INVOICE'		 => "Y",
			'APPROVED_INVOICE_DATE' => date("Y-m-d H:i:s"),
			'APPROVER_UPLOADER'		 => $emailuser
		);


		$approve = $this->gl->approve_data_upload_invoice($data,$journal_decrypt);

		if($approve > 0)
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}

	private function _email_reject($Submitter, $Email, $groupings, $groupingstext, $reason, $attachment=""){

		$action_name = get_user_data($this->session->userdata('user_id'));
		$data['email_recipient']  = $Submitter;
		
			$data['email_preview'] = "Your invoice with $groupingstext : $groupings has been rejected";

			$email_body = "Your invoice with $groupingstext : $groupings  has been rejected by <b>$action_name</b>. 
						<br>
						<br>
			       
						Reason rejection : $reason
						<br>
									 ";

			$data['link'] = base_url("gl/gl-header/");
			$data['link_display'] = "Financetools Upload Invoice Batch ";

		    $subject    = "Your invoice with $groupingstext : $groupings has been rejected";

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$to = $Email;
		$cc = "";

		$body       = $this->load->view('email/ap_apprroval_request', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/fpjp_return_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
	}

	// public function delete_upload_invoice($gl_header_id,$journal_name,$groupingstext,$groupings,$reason){

	// 	$journal_decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($journal_name));

	// 	$result['status'] = false;
	// 	$result['messages'] = "Failed to reject data";
	// 	$Submitter="";
	// 	$Email="";

	// 	if($groupings.equals("nokontrak") && $groupingstext != "")
	// 	{
	// 		$Data = $this->gl->getsubmitter_nokontrak($groupings);

	// 		if($Data)
	// 		{
	// 		  $Submitter =	$Data['PIC_NAME'];
	// 		  $Email = $Data['PIC_EMAIL'];
	// 		}
	// 	}

	// 	if($groupings.equals("nofpjp") && $groupingstext != "")
	// 	{
	// 		$Data = $this->gl->getsubmitter_nofpjp($groupings);

	// 		if($Data)
	// 		{
	// 		  $Submitter =	$Data['PIC_NAME'];
	// 		  $Email = $Data['PIC_EMAIL'];
	// 		}

	// 	}


	// 	$delete = $this->gl->delete_data_upload_invoice($gl_header_id);

	// 	if($delete > 0)
	// 	{
	// 		$delete_before_tax = $this->gl->delete_data_before_tax($journal_decrypt);
			
	// 		if($delete_before_tax > 0){
	// 			$result['status']   = true;
	// 			$result['messages'] = "Data successfully rejected";
	// 		}
	// 	}
	// 	else
	// 	{
	// 		echo '0';
	// 	}

	// 	echo json_encode($result);
	// }

	function update_bast()
	{

		$verified_status  = $this->input->post('verified_status');
		$gl_header = $this->input->post('gl_header');
		$no_journal = $this->input->post('no_journal');

		$status       = false;
		$messages     = "";

		$data = array(
						'IS_BAST' => $verified_status
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("NO_JOURNAL" => $no_journal));

		if($update !== -1){
			$status = true;
			if ($verified_status == 'Y')
			{
			$messages = "Data has been Cheked";
			}
			else
			{
		    $messages = "Data has been Uncheked !!";
			}
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}

	function update_vatsa()
	{

		$verified_status  = $this->input->post('verified_status');
		$gl_header = $this->input->post('gl_header');

		$status       = false;
		$messages     = "";

		$data = array(
						'IS_VATSA' => $verified_status
					);

		$update = $this->crud->update("GL_HEADERS", $data, array("GL_HEADER_ID" => $gl_header));

		if($update !== -1){
			$status = true;
			if ($verified_status == 'Y')
			{
			$messages = "Data has been Cheked";
			}
			else
			{
		    $messages = "Data has been Uncheked !!";
			}
		}

		$result['status']   = $status;
		$result['messages'] = $messages;


		echo json_encode($result);
	}



}



/* End of file Gl.php */

/* Location: ./application/controllers/Gl.php */