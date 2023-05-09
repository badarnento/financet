<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_tax extends CI_Controller {

	private $user_active = "";

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}

		$this->user_active = get_user_data($this->session->userdata('user_id'));
		$this->load->model('User_tax_mdl', 'user_tax');
		$this->load->model('GL_mdl', 'gl');
	}

	public function user_tax()
	{

		if($this->ion_auth->is_admin() == true || in_array("user_tax/user_tax", $this->session->userdata['menu_url']) ){
			$data['title']          = "User Tax";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/user_tax";

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

	function load_ddl_validated()
	{		
		$result ="";
		$result .= "<option value='' data-name='' >-- Choose Status --</option>";
		$result .= "<option value='"."Y"."' data-name='"."Y"."' >"."Validated"."</option>";	
		$result .= "<option value='"."N"."' data-name='"."N"."' >"."UnValidated"."</option>";	
		echo $result;

	}

	public function load_data(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		if($this->input->post('invoice_date_from') != "" && $this->input->post('invoice_date_to') != "")
		{
			$exp_date_from = explode("/", $this->input->post('invoice_date_from'));
			$exp_date_to   = explode("/", $this->input->post('invoice_date_to'));

			$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
			$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		}

		$vendor_name = $this->input->post('vendor_name');
		$validate_status = $this->input->post('validate_status');

		$get_all = $this->user_tax->get_data($invoice_date_from, $invoice_date_to, $vendor_name, $validate_status );

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
		$replacejurnal ='';

		if($total > 0){

			$m_pph    = $this->crud->read('MASTER_PPH');
			$pphval   = array();
			// $pphval[] = '<option value="0" data-persen="" data-gl="" data-name="">-- Choose --</option>';
			foreach ($m_pph as $key => $value) {
				$pphval[]  = array(
									"ID_WHT_TAX"        => $value['ID_WHT_TAX'],
									"GL_ACCOUNT"        => $value['GL_ACCOUNT'],
									"PERCENTAGE"        => $value['PERCENTAGE'],
									"WHT_TAX_CODE_DESC" => $value['WHT_TAX_CODE_DESC']
								);
				// $pphval .= '<option value="'.$value['GL_ACCOUNT'].'" data-gl="'.$value['GL_ACCOUNT'].'" data-persen="'.$value['PERCENTAGE'].'" data-name="">'.$value['WHT_TAX_CODE_DESC'].'</option>';
			}
			// log_to_file(json_encode($pphval));

			$m_ppn = $this->crud->read('MASTER_PPN');
			$ppnval = array();
			//$ppnval = '<option value="0" data-glppn="" data-persenppn="" data-name="">-- Choose --</option>';

			foreach ($m_ppn as $key => $value) {
				$ppnval[]	= array(
									"ID_MSTR_PPN"       => $value['ID_MSTR_PPN'],
									"GL_ACCOUNT"        => $value['GL_ACCOUNT'],
									"PERCENTAGE"        => $value['PERCENTAGE'],
									"TAX_DESCRIPTION" 	=> $value['TAX_DESCRIPTION']
									);

				/*.= '<option value="'.$value['ID_MSTR_PPN'].'" data-glppn="'.$value['GL_ACCOUNT'].'" data-persenppn="'.$value['PERCENTAGE'].'" data-nameppn="">'.$value['TAX_DESCRIPTION'].'</option>';*/
			}

			foreach($data as $value) {

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
						$actionAdded .= $link_upload;
					}else{

						$no_invoice = $value['NO_INVOICE'];
						$dpp        = (int) $value['CREDIT'];

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

				$nmjurnal  =  $value['JOURNAL_NAME'];
				$header_id =  $value['GL_HEADER_ID'];
				$appr 	   =  $value['VALIDATED'];
				$replacejurnal = preg_replace("/[^a-zA-Z0-9]/", "", $nmjurnal);

				$pph = '<div class="form-group m-b-0"><select id="unit_opt-'.$header_id.'" data-id="'.$header_id.'" class="form-control input-sm unit_opt select2 narrow wrap">';
				$pph .=  '<option value="0" data-wht="0" data-gl="0" data-persen="0" data-name="">-- Choose --</option>';

				for ($i=0; $i < count($pphval); $i++) { 
					$selected = "";

					if($pphval[$i]['ID_WHT_TAX'] == $value['ID_WHT_TAX']){
						$selected = " selected";
					}

					$pph .= '<option value="'.$pphval[$i]['ID_WHT_TAX'].'" data-wht="'.$pphval[$i]['ID_WHT_TAX'].'" data-gl="'.$pphval[$i]['GL_ACCOUNT'].'" data-persen="'.$pphval[$i]['PERCENTAGE'].'"'.$selected.'>'.$pphval[$i]['WHT_TAX_CODE_DESC'].'</option>';

				}
				$pph .=  '</select></div>';

				if($value['NATURE'] == '21122001' || $value['NATURE'] == '21121001' ){
					$pph = '<div class="form-group m-b-0"><select id="" data-id="" class="form-control input-sm unit_opt" disabled>';
					$pph .=  '<option value="0" data-wht="0" data-gl="0" data-persen="0" data-name="">-- Choose --</option>';
				}

				if($value['NATURE'] == '21122001' || $value['NATURE'] == '21121001' ){
					$pph = '<div class="form-group m-b-0"><select id="unit_opt-'.$header_id.'" data-id="" class="form-control input-sm unit_opt" disabled>';
					$pph .=  '<option value="0" data-wht="0" data-gl="0" data-persen="0" data-name="">-- Choose --</option>';
				}

				$ppn = '<div class="form-group m-b-0"><select id="unit_optppn-'.$header_id.'" data-id="'.$header_id.'" class="form-control input-sm unit_optppn select2">';
				$ppn .=  '<option value="0" data-glppn="0" mst-ppn="0" data-persenppn="0" data-name="">-- Choose --</option>';

				for ($i=0; $i < count($ppnval); $i++) { 
					$selected = "";

					if($ppnval[$i]['ID_MSTR_PPN'] == $value['ID_MSTR_PPN']){
						$selected = " selected";
					}

				$ppn .= '<option value="'.$ppnval[$i]['ID_MSTR_PPN'].'" data-glppn="'.$ppnval[$i]['GL_ACCOUNT'].'"  data-persenppn="'.$ppnval[$i]['PERCENTAGE'].'"'.$selected.'>'.$ppnval[$i]['TAX_DESCRIPTION'].'</option>';

				}
				$ppn .=  '</select></div>';

				if($value['NATURE'] == '21122001' || $value['NATURE'] == '21121001' ){

					$ppn = '<div class="form-group m-b-0"><select id="" data-id="" class="form-control input-sm unit_optppn" disabled>';
					$ppn .=  '<option value="0" data-glppn="0" mst-ppn="0" data-persenppn="0" data-name="">-- Choose --</option>';
					$ppn .=  '</select></div>';
				}

				$vallokasi = "";
				if($value['LOKASI_SEWA'] !=""){
					$vallokasi = $value['LOKASI_SEWA'];
				}
				$lokasi_sewa = '<div class="form-group m-b-0"><input id="lokasi_sewa-'.$replacejurnal.'" data-id="'.$replacejurnal.'" class="form-control input-sm lokasi_sewa text-left" value="'.$vallokasi.'" placeholder="Lokasi Sewa"></div>';

				$valfaktur = "";
				if($value['FAKTUR_PAJAK'] !=""){
					$valfaktur = $value['FAKTUR_PAJAK'];
				}
				$faktur_pajak = '<div class="form-group m-b-0"><input id="faktur_pajak-'.$replacejurnal.'" data-id="'.$replacejurnal.'" class="form-control input-sm faktur_pajak text-left" value="'.$valfaktur.'" placeholder="Faktur Pajak"></div>';

				/*if($value['NATURE'] == '21122001'){
					$faktur_pajak = '<div class="form-group m-b-0"><input id="" data-id="" class="form-control input-sm faktur_pajak text-left" value="" placeholder="Faktur Pajak" disabled></div>';
				}*/

				$periodstatus = $value['STATUS_CLOSING'];

				if($value['STATUS_CLOSING'] == 'CLOSE')
				{
					$hiddenstats = "disabled";
				}
				else
				{
					$hiddenstats = "";
				}

				if($value['VALIDATED'] == 'Y')
				{
					$checkbox = '<div  class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data" value='.$value['VALIDATED'].' checked '.$hiddenstats.'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}
				else
				{
					$checkbox = '<div '.$hiddenstats.' class="checkbox checkbox-danger"><input id="checkbox-'.$replacejurnal.'" class="checkbox-data checklist" type="checkbox" name="checkbox-data"  value='.$value['VALIDATED'].' '.$hiddenstats.'><label class="m-0 p-0" for="checkbox-'.$replacejurnal.'"></label></div>';
				}

				$valNpwp = "";
				if($value['NPWP'] != ""){
					$valNpwp = $value['NPWP'];
				}
				$npwp = '<div class="form-group m-b-0"><input id="npwp-'.$replacejurnal.'" data-id="'.$replacejurnal.'" class="form-control input-sm npwp text-left" value="'.$valNpwp.'" placeholder="NPWP" maxlength="20"></div>';


				$notes = '<textarea id="notes-'.$replacejurnal.'" class="form-control notes" rows="3">'.$value['NOTES'].'</textarea>';

				$valamount_base_fee = "";
				if($value['AMOUNT_BASE_FEE'] != ""){
					$valamount_base_fee = $value['AMOUNT_BASE_FEE'];
				}
				$amount_base_fee = '<div class="form-group m-b-0"><input id="amount_base_fee-'.$replacejurnal.'" data-id="'.$replacejurnal.'" class="form-control input-sm amount_base_fee text-left" value="'.$valamount_base_fee.'" placeholder="Amount Base Fee" type="number" min="0" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"></div>';

				$val = $value['TGL_FAKTUR_PAJAK'];

				// echo $val; die;

				// $valtgl_faktur_pajak = ($val != ""  &&  $val != "0000-00-00") ? dateFormat($value['TGL_FAKTUR_PAJAK'], 5, false) : '00-00-0000';

				$valtgl_faktur_pajak = ($val != ""  &&  $val != "0000-00-00") ? dateFormat($value['TGL_FAKTUR_PAJAK'], 5, false) : '';

				$tgl_faktur_pajak = '<div class="form-group m-b-0"><input id="tglfakturpajak-'.$replacejurnal.'"
				 data-id="'.$replacejurnal.'" class="form-control input-sm tglfakturpajak date-picker-po" value="'.$valtgl_faktur_pajak.'"></div>';

				// $amount_base_fee = '<div class="form-group m-b-0"><input id="amount_base_fee-'.$replacejurnal.'" data-id="'.$replacejurnal.'" class="form-control input-sm amount_base_fee text-left" value="'.$$value['AMOUNT_BASE_FEE'].'" placeholder="Amount Base Fee" maxlength="20"></div>';

				/*if($value['NATURE'] == '21122001'){
					$npwp = '<div class="form-group m-b-0"><input id="" data-id="" class="form-control input-sm npwp text-left" value="" placeholder="NPWP" maxlength="20" disabled></div>';
				}*/
				$tgl_invoice  = date("d-m-Y",strtotime($value['TGL_INVOICE']));
				$invoice_date = ($value['INVOICE_DATE']) ? date("d-m-Y",strtotime($value['INVOICE_DATE'])) : '';

				$row[] = array(
					 'no'             	  => $number,
					'tanggal_invoice'     => $tgl_invoice,
					'invoice_date'     	  => $invoice_date,
					'due_date'            => date("d-m-Y",strtotime($value['DUE_DATE'])),
					'batch_name'          => $value['BATCH_NAME'],
					'gl_header_id'        => $value['GL_HEADER_ID'],
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
					'pph'                 => $pph,
					'ppn'                 => $ppn,
					'lokasi_sewa' 		  => $lokasi_sewa,
					'tgl_faktur_pajak'    => $tgl_faktur_pajak,
					'faktur_pajak'        => $faktur_pajak,
					'npwp'                => $npwp,
					'amount_base_fee'     => $amount_base_fee,
					'notes'               => $notes,
					'period_status' 	  => $periodstatus,
					'validated'           => $checkbox,
					'action'			  => $action
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

	function save_data(){

		$data_line 	= $this->input->post('data_line');
		$status   = false;
		$validated ='';
		$gl_header_id ='';
		$update = 0;

		// $random_number  = generateRandomString(3, 'number');

		// $batch_approval   = get_shorted_user($this->session->userdata('user_id'))."/".$random_number.date("/dm/y");

	if($data_line){
		foreach ($data_line as $key => $value) {

			$validated = $value['validated'];
			$gl_header_id = $value['header_id'];

			if ( $validated == 'N')
			{
				$this->user_tax->delete_data_after_tax($gl_header_id);
			}

			$data[] = array(
						// "BATCH_APPROVAL"   => $batch_approval,
						"GL_HEADER_ID"     => $value['header_id'],
						"NO_JOURNAL"       => $value['journal_name'],
						"NATURE_PPH"       => $value['gl_pph'],
						"PERCENTAGE_PPH"   => $value['persen_pph'],
						"ID_WHT_TAX"       => $value['wht_pph'],
						"NATURE_PPN"       => $value['gl_ppn'],
						"PERCENTAGE_PPN"   => $value['persen_ppn'],
						"ID_MSTR_PPN"      => $value['mst_ppn'],
						"VALIDATED"        => $value['validated'],
						"NPWP"             => $value['npwp'],
						"FAKTUR_PAJAK"     => $value['faktur_pajak'],
						"LOKASI_SEWA"      => $value['lokasi_sewa'],
						"TGL_FAKTUR_PAJAK" => $value['tgl_faktur_pajak'],
						"AMOUNT_BASE_FEE"  => $value['amount_base_fee'],
						"NOTES"            => $value['notes'],
						"VALIDATE_DATE_TAX"  => $value['validated_date']

					);

		}

		$update   	= $this->crud->update_batch_data("GL_HEADERS", $data, "GL_HEADER_ID");
	}
		if($update != -1){

			log_message('info', 'successfully saved validate by ' . $this->user_active);

		/*	$get_user_verif = get_user_approval_ap("VERIFICATION");

			if($get_user_verif){
				foreach ($get_user_verif as $key => $value) {
					$recipient['name']  = $value['PIC_NAME'];
					$recipient['email'] = $value['PIC_EMAIL'];
					$this->_email_aprove($recipient, $batch_approval);
				}
			}*/

			$result['status']    = true;
		}else{
			$result['status']   = false;
		}

		echo json_encode($result);
	}

	//old code skrg cuman manggil procedure
	// function create_user(){

	// 	$data_line 	= $this->input->post('data_line');
	// 	$status   = false;
	// 	$validated ='';
	// 	$gl_header_id ='';

	// 	foreach ($data_line as $key => $value) {

	// 		$validated = $value['validated'];
	// 		$gl_header_id = $value['header_id'];

	// 		if ( $validated == 'N')
	// 		{
	// 			$this->user_tax->delete_data_after_tax($gl_header_id);
	// 		}

	// 		$data[] = array(
	// 					"GL_HEADER_ID" 		=> $value['header_id'],
	// 					"NO_JOURNAL" 		=> $value['journal_name'],
	// 					"NATURE_PPH" 		=> $value['gl_pph'],
	// 					"PERCENTAGE_PPH"    => $value['persen_pph'],
	// 					"ID_WHT_TAX"    	=> $value['wht_pph'],
	// 					"NATURE_PPN"     	=> $value['gl_ppn'],
	// 					"PERCENTAGE_PPN"   	=> $value['persen_ppn'],
	// 					"ID_MSTR_PPN"   	=> $value['mst_ppn'],
	// 					"VALIDATED"   		=> $value['validated'],
	// 					"NPWP"   			=> $value['npwp'],
	// 					"FAKTUR_PAJAK"   	=> $value['faktur_pajak'],
	// 					"TGL_FAKTUR_PAJAK"  => $value['tgl_faktur_pajak'],
	// 					"AMOUNT_BASE_FEE"   => $value['amount_base_fee'],
	// 					"NOTES"   			=> $value['notes']

	// 				);

	// 	}

	// 	 //echo "<pre>";print_r($data);die;

	// 	$update   	= $this->crud->update_batch_data("GL_HEADERS", $data, "GL_HEADER_ID");
	// 	/*if($insert){
	// 		$cal_proc   = $this->user_tax->call_proc();
	// 	}*/

	// 	if($update != -1){
	// 			$result['status']    = true;
	// 			$cal_proc   = $this->user_tax->call_proc();
	// 		}else{
	// 			$result['status']   = false;
	// 		}

	// 		echo json_encode($result);
	// }

	//new code hanya manggil procedure
	function create_user()
	{

		$random_number  = generateRandomString(3, 'number');
		$batch_approval = get_shorted_user($this->session->userdata('user_id'))."/".$random_number.date("/dm/y");
		$get_all        = $this->user_tax->get_data_aproval();

		$dataupdate = array();

		if($get_all){
			foreach($get_all as $value):
				$dataupdate[] = array(
									"BATCH_APPROVAL" => $batch_approval,
									"GL_HEADER_ID"   => $value['GL_HEADER_ID']
								  );
			endforeach;
			if(count($dataupdate) > 0):
				$this->crud->update_batch_data("GL_HEADERS", $dataupdate, "GL_HEADER_ID");
			endif;
		}

		$cal_proc   = $this->user_tax->call_proc();

		if($cal_proc){

			log_message('info', 'successfully created validate by ' . $this->user_active);
			$get_user_verif = get_user_approval_ap("VERIFICATION");

			if($get_user_verif){
				foreach ($get_user_verif as $key => $value) 
				{
					$recipient['name']  = $value['PIC_NAME'];
					$recipient['email'] = $value['PIC_EMAIL'];
					$this->_email_aprove($recipient, $batch_approval);
				}
			}

		}

		$result['status']    = true;
		
		echo json_encode($result);

	}

	public function load_data_after(){

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
		$validate_status = $this->input->post('validate_status');

		$get_all = $this->user_tax->get_data_after($invoice_date_from, $invoice_date_to, $vendor_name, $validate_status );

		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;
		$nama_jurnal = "";
		$no = 1;

		if($total > 0){

			foreach($data as $value) {
				if($number == 1){
					$no = 1;
					$nama_jurnal = $value['JOURNAL_NAME'];
				} else{
					if($nama_jurnal == $value['JOURNAL_NAME']){
						$no++;
						$nama_jurnal = $value['JOURNAL_NAME'];
					}else{
						$no = 1;
						$nama_jurnal = $value['JOURNAL_NAME'];
					}
				}

				$tgl_invoice  = date("d-m-Y",strtotime($value['TGL_INVOICE']));
				$invoice_date = ($value['INVOICE_DATE']) ? date("d-m-Y",strtotime($value['INVOICE_DATE'])) : '';

				$row[] = array(
					'no'             	  => $no,
					'tanggal_invoice'     => $tgl_invoice,
					'invoice_date'     	  => $invoice_date,
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
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "No Kontrak");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Status");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Amount Base Fee");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Notes");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Tanggal Faktur Pajak");
		// $excel->setActiveSheetIndex(0)->setCellValue('P1', "PPN");
		// $excel->setActiveSheetIndex(0)->setCellValue('Q1', "PPH");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$vendor_name = $this->input->get('vendor_name');
		$validate_status = $this->input->get('validate_status');

		$hasil = $this->user_tax->get_download_before_tax($date_from,$date_to,$vendor_name,$validate_status);

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
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['CREDIT']);
			$excel->getActiveSheet()->getStyle('M'.$numrow.":N".$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['VALIDATED']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['AMOUNT_BASE_FEE']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $row['NOTES']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $row['TGL_FAKTUR_PAJAK']);

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
		header('Content-Disposition: attachment; filename="Journal Before Tax.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Transaction Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Due Date");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Batch Name");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Batch Description");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Nama Vendor");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Journal Name");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "No Invoice");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "No Kontrak");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "No Faktur Pajak");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Account Description");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Nature");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Currency");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Debet");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "Credit");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Journal Description");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Status");
		// $excel->setActiveSheetIndex(0)->setCellValue('P1', "STATUS");
		// $excel->setActiveSheetIndex(0)->setCellValue('Q1', "Remark");

		$date_from = $this->input->get('date_from');
		$date_to = $this->input->get('date_to');
		$vendor_name = $this->input->get('vendor_name');
		$validate_status = $this->input->get('validate_status');

		$hasil = $this->user_tax->get_download_after_tax($date_from,$date_to,$vendor_name,$validate_status);

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
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $row['JOURNAL_NAME']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $row['NO_INVOICE']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $row['NO_KONTRAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $row['FAKTUR_PAJAK']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $row['ACCOUNT_DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $row['NATURE']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $row['CURRENCY']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $row['DEBET']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $row['CREDIT']);
			$excel->getActiveSheet()->getStyle('N'.$numrow.":O".$numrow)->getNumberFormat()->setFormatCode('_(#,##_);_(\(#,##\);_("0"_);_(@_)');
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $row['DESCRIPTION']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $row['VALIDATED']);

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
		header('Content-Disposition: attachment; filename="Journal After Tax.xls"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');

	}


    private function _email_aprove($recipient, $batch_approval){

		$get_gl = $this->user_tax->get_journal_to_verification($batch_approval)->result_array();

		$data['email_recipient']  = $recipient['name'];
		$data['email_preview'] = "A new invoice has been submitted for your approval with Batch Approval ".$batch_approval;
		
		$action_name = $this->user_active;

		$email_preview = "A new invoice with Batch Approval $batch_approval has been submitted by $action_name and need for your approval";

		$email_body = "A new invoice with Batch Approval <b>$batch_approval</b> has been submitted by <b>$action_name</b> and need for your approval.";

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

		$no = 1;

		foreach ($get_gl as $key => $value) {


			$transaction_date    = date("d-m-Y",strtotime($value['TGL_INVOICE']));
			$due_Date            = date("d-m-Y",strtotime($value['DUE_DATE']));
			$batch_name          = $value['BATCH_NAME'];
			$nama_vendor         = $value['NAMA_VENDOR'];
			$journal_name        = $value['JOURNAL_NAME'];
			$no_invoice          = $value['NO_INVOICE'];
			$no_kontrak          = $value['NO_KONTRAK'];
			$account_description = $value['ACCOUNT_DESCRIPTION'];
			$currency            = $value['CURRENCY'];
			$debet               = number_format($value['DEBET'],0,'.',',');
			$credit              = number_format($value['CREDIT'],2,',','.');
			$journal_description = $value['DESCRIPTION'];

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$transaction_date."</td>";
				$email_body .= "<td>".$due_Date."</td>";
				$email_body .= "<td>".$journal_name."</td>";
				$email_body .= "<td>".$credit."</td>";
				$email_body .= "<td>".$account_description."</td>";
			$email_body .= "</tr>";
			$no++;

		}

	}

		$email_body .= 				"</tbody>
								</table></div></div>";
		$data['email_body'] = $email_body;

		$encrypt_batch_approve = encrypt_string(str_replace("/","-", $batch_approval), true);
		$data['link'] = base_url("user-verification/").$encrypt_batch_approve;
		$data['link_display'] = "Financetools User Verification ".$batch_approval;

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}
		$subject    = "Invoice Approval Request - $batch_approval";
		$body       = $this->load->view('email/ap_apprroval_request', $data, TRUE);
		// $attachment = ($attachment) ? FCPATH.'/uploads/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc);

		return $send;
	}

}

/* End of file User_tax.php */
/* Location: ./application/controllers/User_tax.php */