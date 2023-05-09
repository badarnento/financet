<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalPO_ctl extends CI_Controller {

	private $module_name = "pr_po",
			$pic_email  = "",
			$module_url  = "purchase-order",
			$user_cc  = array();

	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('purchase_mdl', 'purchase');
		$this->load->model('feasibility_study_mdl', 'feasibility_study');

		$list_cc['aldji']   = 'aldji_i_kahar@linkaja.id';
		$list_cc['susanto'] = 'susanto_wu@linkaja.id';
		$list_cc['dita']    = 'dita_lestari@linkaja.id';
		$list_cc['wahyu']   = 'wahyu_bijaksana@linkaja.id';

		$is_delegate    = $this->session->userdata('is_delegate');
      	$this->pic_email = ($is_delegate !== false) ? $is_delegate : $this->session->userdata('email');

		$this->user_cc = $list_cc;

	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "po/approval");
			redirect('login', 'refresh');
		}

		$check_is_approval = $this->purchase->check_is_approval_po($this->pic_email);

		if($check_is_approval){
			$get_po_for_approval = $this->purchase->get_po_for_approval($this->pic_email);

			$id_po = array();

			if($get_po_for_approval){
				foreach ($get_po_for_approval as $value) {
					$id_po[] = $value['PO_HEADER_ID'];
				}
			}

			$data['title']          = "All Purchase Order";
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/po_approval";
			
			$data['id_po']  = json_encode($id_po);

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			$this->session->set_flashdata('messages', 'You are not as approval');
			redirect('/');

		}
	}


	public function approval_single($id_approval){

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata("redirect_page", "po/approval/".$id_approval);
			redirect('login');
		}

		$decrypt = decrypt_string($id_approval, true);
		$exp     = explode("-", $decrypt);

		$verify = (count($exp) == 2) ? true : false;

		if($verify === false){
			$this->session->set_flashdata('messages', 'Error verify PR');
			redirect('/');
		}

		$id_po       = $exp[0];
		$id_approval = $exp[1];
		$get_trx     = $this->crud->read_by_id("TRX_APPROVAL_PO", array("ID" => $id_approval));
		$get_approval = $this->crud->read_by_id("MASTER_APPROVAL", array("ID_APPROVAL" => $get_trx['ID_APPROVAL']));

		if(strtolower($get_approval['PIC_EMAIL']) != strtolower($this->pic_email)){
			$this->session->set_flashdata('messages', 'Error verify PR Approval');
			redirect('/');
		}

		$check_exist = $this->crud->check_exist("PO_HEADER", array("PO_HEADER_ID" => $id_po));
		
		if($check_exist > 0){

			$pic_email     = $this->pic_email;
			$get_po_header = $this->purchase->get_po_to_approve_by_id($id_po, $pic_email);

			$data['title']          = "PO ".$get_po_header['PO_NUMBER'];
			$data['module']         = "datatable";
			$data['template_page']  = $this->module_name."/po_approval_single";
			
			$data['id_po']          = $id_po;
			$data['pr_number']      = $get_po_header['PR_NUMBER'];
			$data['pr_name']        = $get_po_header['PR_NAME'];
			$data['po_date']        = dateFormat($get_po_header['PO_DATE'], 4, false);
			$data['pr_link']        = base_url("purchase-requisition/") . encrypt_string($get_po_header['PR_HEADER_ID'], true);
			$data['po_amount']      = number_format($get_po_header['PO_AMOUNT'],0,',','.');
			$data['po_directorat']  = $get_po_header['ID_DIR_CODE'];
			$data['po_division']    = $get_po_header['ID_DIVISION'];
			$data['po_unit']        = $get_po_header['ID_UNIT'];
			$data['po_currency']      = $get_po_header['CURRENCY_PO'];
			$data['po_rate']          = $get_po_header['CURRENCY_RATE_PO'];
			$data['po_status']      = ($get_po_header['STATUS'] == "request_approve") ? "Waiting approval" : ucfirst($get_po_header['STATUS']);
			$data['po_status_desc'] = $get_po_header['STATUS_DESCRIPTION'];
			$last_update            = ($get_po_header['UPDATED_DATE']) ? $get_po_header['UPDATED_DATE'] : $get_po_header['CREATED_DATE'];
			$data['po_last_update'] = dateFormat($last_update, "with_day", false);
			$data['trx_status']     = $get_po_header['TRX_STATUS'];
			$data['trx_date']       = dateFormat($get_po_header['TRX_DATE'], "with_day", false);
			
			$data['level']          = $get_po_header['LEVEL'];
			$data['level']          = $get_po_header['LEVEL'];
			$data['po_category']    = ($get_po_header['PO_CATEGORY']) ? $get_po_header['PO_CATEGORY'] : "-";

			if($get_po_header['LEVEL'] > 1):
				$get_approver_before            = $this->purchase->get_approval_before_po($id_po);
				$data['approver_before_name']   = $get_approver_before['PIC_NAME'];
				$data['approver_before_remark'] = $get_approver_before['REMARK'];
				$data['approver_before_date']   = dateFormat($get_approver_before['TRX_DATE'], "with_day", false);
			endif;


			$data['po_document'] = false;
			if($get_po_header['DOCUMENT_SOURCING']):
				$data['po_document'] = true;
				$data['po_document_link'] = base_url("download/") . encrypt_string("uploads/po_attachment/".$get_po_header['DOCUMENT_SOURCING'], true);
			endif;
			$data['po_document_clauses'] = false;
			if($get_po_header['DOCUMENT_CLAUSE']):
				$data['po_document_clauses'] = true;
				$data['po_document_clauses_link'] = base_url("download/") . encrypt_string("uploads/po_attachment/".$get_po_header['DOCUMENT_CLAUSE'], true);
			endif;

			// echo_pre($data);d

			$get_all_approval = $this->purchase->get_all_approver_po($id_po, $id_approval);

			$j=0;


			$data['mpa_reference']    = $get_po_header['MPA_REFERENCE'];
			$data['top']    		  = $get_po_header['TOP'];
			$data['est_date']         = $get_po_header['ESTIMATE_DATE'];
			$data['notes']    		  = $get_po_header['NOTES'];
			$data['po_document_link'] = base_url('download/'). encrypt_string("uploads/po_attachment/".$get_po_header['DOCUMENT_SOURCING'], true);
			$last_update              = ($get_po_header['UPDATED_DATE']) ? $get_po_header['UPDATED_DATE'] : $get_po_header['CREATED_DATE'];
			$data['po_last_update']   = dateFormat($last_update, "with_day", false);
			$data['po_category']      = dateFormat($last_update, "with_day", false);
			$data['po_category']      = ($get_po_header['PO_CATEGORY']) ? $get_po_header['PO_CATEGORY'] : "-";

			$po_buyer       = ($get_po_header['BUYER']) ? $get_po_header['BUYER'] : '';
			$po_buyer_email = ($get_po_header['BUYER_EMAIL']) ? $get_po_header['BUYER_EMAIL'] : '';

			$all_approval[] = array(
									"ID"       => 0,
									"CATEGORY" => "Buyer",
									"NAME"     => $po_buyer,
									"EMAIL"    => $po_buyer
								);

			foreach ($get_all_approval as $key => $value) {

				if($j == 0){
				}
				$all_approval[] = array(
											"ID"       => $value['ID'],
											"CATEGORY" => $value['CATEGORY'],
											"NAME"     => $value['PIC_NAME'],
											"EMAIL"    => $value['PIC_EMAIL']
										);
				$j++;
			}

			$data['po_buyer'] = $po_buyer;

			/*$get_buyer = $this->crud->read_by_param("MASTER_APPROVAL", array("PIC_LEVEL" => "Procurement Buyer", "PIC_EMAIL" => $po_buyer, "IS_EXIST" => 1));

			$po_history[] = array("ID" => 0, "PIC_NAME" => $get_buyer['PIC_NAME'], "STATUS" => "Created", "REMARK" => "", "ACTION_DATE" =>  dateFormat($get_po_header['CREATED_DATE'], 'fintool', false));

			$get_history = $this->purchase->get_comment_history_po($id_po);
			foreach ($get_history as $key => $value) {
				$po_history[] = array("ID" => $key+1, "PIC_NAME" => $value['PIC_NAME'], "STATUS" => $value['STATUS'], "REMARK" => $value['REMARK'], "ACTION_DATE" => dateFormat($value['UPDATED_DATE'], 'fintool', false));
			}*/
			$po_history = get_po_history($id_po);

			$data['po_history'] = $po_history;

			$data['approval_list'] = $all_approval;
			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "All PO Request", "link" => base_url("po/approval"), "class" => "" );
			$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
			$data['breadcrumb']    = $breadcrumb;

			$this->template->load('main', $data['template_page'], $data);

		}
		else{

			$this->session->set_flashdata('messages', 'PR Not Exist');
			redirect('po/approval');

		}

	}


	public function action_approval(){

		$id_po       = $this->input->post('id_po');
		$level       = $this->input->post('level');
		$remark      = $this->input->post('remark');
		$approval    = $this->input->post('approval');
		$approver    = $this->input->post('approver');
		$po_category = $this->input->post('po_category');

		$pic_email = $this->pic_email;

		$result['status'] = false;
		$result['messages'] = "Failed to $approval justification";

		if($approval == "reject"):
			$status = "rejected";
		elseif($approval == "return"):
			$status = "returned";
		else:
			$status = "approved";
		endif;

		$action_date = date("Y-m-d H:i:s", time());
		$action_name = get_user_data($this->session->userdata('user_id'));

		$data = array("STATUS" => $status, "ACTION_DATE" => $action_date, "REMARK" => $remark);
		$update = $this->crud->update("TRX_APPROVAL_PO", $data, array("PO_HEADER_ID" => $id_po, "IS_ACTIVE" => 1, "LEVEL" => $level));

		if($update !== -1){

			$get_po_buyer   =  $this->purchase->get_po_buyer($id_po);
			$buyer_email = "";
			$buyer_name  = "";
			
			if($get_po_buyer){
				$buyer_email = $get_po_buyer['BUYER_EMAIL'];
				$buyer_name  = $get_po_buyer['BUYER'];
			}
			$email_cc = array();
			$cc_email = $this->user_cc;

			if($status == "approved"):
				$next_level = $level+1;
				$get_next_aprover = $this->purchase->get_approver_po($id_po, $next_level);

				if($get_next_aprover){
					$id_approval = $get_next_aprover['ID'];

					if($buyer_email != ""){
						$email_cc[] = $buyer_email;
					}

					$email_cc[] = $cc_email['susanto'];
					$email_cc[] = $cc_email['dita'];
					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}
					$recipient['email'] = $get_next_aprover['PIC_EMAIL'];
					$recipient['name']  = $get_next_aprover['PIC_NAME'];

					$this->_email_approval($recipient, $id_po, "request_approve", $remark, $id_approval);
					$this->crud->update("TRX_APPROVAL_PO", array("STATUS" => "request_approve"), $id_approval);

				}else{

					if($buyer_email != "" && $buyer_name != ""){

						$recipient['email'] = $buyer_email;
						$recipient['name']  = $buyer_name;

						$email_cc[] = $cc_email['susanto'];
						$email_cc[] = $cc_email['dita'];
						$email_cc[] = $cc_email['aldji'];
						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}

						$this->_email_approval($recipient, $id_po, $status, $remark);
					}
					$dataPoUpdate['STATUS'] = $status;

					$this->_call_proc_po($id_po);

					$get_email_vendor = $this->purchase->get_email_vendor($id_po);
					if($get_email_vendor):
						if($get_email_vendor['ALAMAT_EMAIL'] ){
							$recipient['email'] = $get_email_vendor['ALAMAT_EMAIL'];
							$recipient['name']  = $get_email_vendor['PIC_VENDOR'];

							$email_cc = array();

							$email_cc[] = $cc_email['susanto'];
							$email_cc[] = $cc_email['dita'];
							$email_cc[] = $cc_email['aldji'];
							$email_cc[] = $buyer_email;

							$get_submitter   = $this->purchase->get_submitter_by_id_pr($id_pr);
							$submitter_email = "";
							if($get_submitter){
								$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : "";
							}

							$email_cc[] = $submitter_email;
							
							if(count($email_cc) > 0){
								$recipient['email_cc']  = $email_cc;
							}
							// $this->_email_to_vendor($recipient, $id_po);
						}
					endif;

				}
				$dataPoUpdate['STATUS_DESCRIPTION'] = "Approved by ". $action_name;
				$this->crud->update("PO_HEADER", $dataPoUpdate, array("PO_HEADER_ID" => $id_po));

			elseif($status == "returned"):

				if($approver > 0){

					$get_all_trx = $this->crud->read_by_param_specific("TRX_APPROVAL_PO", array("PO_HEADER_ID" => $id_po));

					$dataTrx = array();

					foreach ($get_all_trx as $key => $value) {

						if($value['ID'] < $approver){

							$dataTrx[] = array( 
											"PO_HEADER_ID" => $id_po,
											"CATEGORY"     => $value['CATEGORY'],
											"ID_APPROVAL"  => $value['ID_APPROVAL'],
											"REMARK"       => $value['REMARK'],
											"LEVEL"        => $value['LEVEL'],
											"STATUS"       => $value['STATUS'],
											"ACTION_DATE"  => $value['ACTION_DATE'],
											"CREATED_DATE" => $value['CREATED_DATE'],
											"CREATED_BY"   => $value['CREATED_BY'],
											"UPDATED_DATE" => $value['UPDATED_DATE'],
											"UPDATED_BY"   => $value['UPDATED_BY']
										);
						}
						elseif($value['ID'] == $approver){

							$get_approver = $this->crud->read_by_param("MASTER_APPROVAL", array("ID_APPROVAL" => $value['ID_APPROVAL']));

							if($get_approver){
								$recipient['email'] = $get_approver['PIC_EMAIL'];
								$recipient['name']  = $get_approver['PIC_NAME'];
							}

							$dataTrx[] = array( 
											"PO_HEADER_ID" => $id_po,
											"CATEGORY"     => $value['CATEGORY'],
											"ID_APPROVAL"  => $value['ID_APPROVAL'],
											"REMARK"       => "",
											"LEVEL"        => $value['LEVEL'],
											"STATUS"       => "request_approve",
											"ACTION_DATE"  => NULL,
											"CREATED_DATE" => date("Y-m-d H:i:s", time()),
											"CREATED_BY"   => NULL,
											"UPDATED_DATE" => NULL,
											"UPDATED_BY"   => NULL
										);

						}
						else{

							$dataTrx[] = array( 
											"PO_HEADER_ID" => $id_po,
											"CATEGORY"     => $value['CATEGORY'],
											"ID_APPROVAL"  => $value['ID_APPROVAL'],
											"REMARK"       => "",
											"LEVEL"        => $value['LEVEL'],
											"STATUS"       => NULL,
											"ACTION_DATE"  => NULL,
											"CREATED_DATE" => date("Y-m-d H:i:s", time()),
											"CREATED_BY"   => NULL,
											"UPDATED_DATE" => NULL,
											"UPDATED_BY"   => NULL
										);

						}
					}

					if(count($dataTrx) > 0):
						$this->crud->update("TRX_APPROVAL_PO", array("IS_ACTIVE" => 0 ), array("PO_HEADER_ID" => $id_po));
						$insert_approval = $this->crud->create_batch("TRX_APPROVAL_PO", $dataTrx);
						$id_approval     = $this->db->insert_id();
					endif;

					if($recipient){

						$email_cc[] = $cc_email['susanto'];
						$email_cc[] = $cc_email['dita'];

						if($buyer_email != ""){
							$email_cc[] = $buyer_email;
						}

						$this->_email_approval($recipient, $id_po, $status, $remark);
					}

				}else{

					if($buyer_email != "" && $buyer_name != ""){

						$email_cc[] = $cc_email['susanto'];
						$email_cc[] = $cc_email['dita'];
						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}
						$recipient['email'] = $buyer_email;
						$recipient['name']  = $buyer_name;
						$this->_email_approval($recipient, $id_po, $status, $remark);
					}

				}

				$this->crud->update("PO_HEADER", array("STATUS" => $status, "STATUS_DESCRIPTION" => ucfirst($status)." by ". $action_name), array("PO_HEADER_ID" => $id_po));
			else:

				$email_cc[] = $cc_email['susanto'];
				$email_cc[] = $cc_email['dita'];

				if($buyer_email != "" && $buyer_name != ""){

					if(count($email_cc) > 0){
						$recipient['email_cc']  = $email_cc;
					}

					$recipient['email'] = $buyer_email;
					$recipient['name']  = $buyer_name;
					
					$this->_email_approval($recipient, $id_po, $status, $remark);
				}

				$this->crud->update("PO_HEADER", array("STATUS" => $status, "STATUS_DESCRIPTION" => ucfirst($status)." by ". $action_name), array("PO_HEADER_ID" => $id_po));

				$get_id_pr = $this->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $id_po));
				$id_pr = $get_id_pr['ID_PR_HEADER_ID'];
				$dataPR = array(
						"STATUS"             => "returned",
						"STATUS_DESCRIPTION" => "Returned by Procurement",
						"PIC_RETURN"         => $action_name,
						"PIC_PO_REJECT"      => $action_name,
						"RETURN_DATE"        => date("Y-m-d H:i:s", time()),
						"REMARK_RETURN"      => "Returned by Procurement",
						"REMARK_REJECT"      => "PO rejected by Procurement",
						"PO_REJECT_DATE"     => date("Y-m-d H:i:s", time())
					);

				$update_pr = $this->crud->update("PR_HEADER", $dataPR, array("PR_HEADER_ID" => $id_pr));

				if( $update_pr !== -1  ){

					$get_submitter   = $this->purchase->get_submitter_by_id_pr($id_pr);
					$submitter_email = "";
					$submitter_name  = "";

					if($get_submitter){
						$submitter_email = ($get_submitter['PIC_EMAIL']) ? $get_submitter['PIC_EMAIL'] : "";
						$submitter_name  = $get_submitter['SUBMITTER'];
					}

					if($submitter_email != "" && $submitter_name != ""){

						if(count($email_cc) > 0){
							$recipient['email_cc']  = $email_cc;
						}
						$recipient['email'] = $submitter_email;
						$recipient['name']  = $submitter_name;
						$this->_email_cancel_pr($recipient, $id_pr, "Returned by Procurement", true);
					}
				}

			endif;

			$result['status']   = true;
			$result['messages'] = "Data successfully ".$status;
		}

		echo json_encode($result);

	}
	
	public function load_po_to_approve(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$status    = $this->input->post('status');
		$pic_email = $this->pic_email;

		$get_all         = $this->purchase->get_po_to_approve($pic_email, $status);
		$data            = $get_all['data'];
		$total           = $get_all['total_data'];
		$start           = $this->input->post('start');
		$number          = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$status_description = $value['STATUS_DESCRIPTION'];

				$row[] = array(
						'no'                 => $number,
						'id'                 => $value['PO_HEADER_ID'],
						'id_po'              => encrypt_string($value['PO_HEADER_ID'], true),
						'id_po_approval'     => encrypt_string($value['PO_HEADER_ID']."-".$value['ID_PO_APPROVAL'], true),
						'directorat'         => get_directorat($value['ID_DIR_CODE']),
						'division'           => get_division($value['ID_DIVISION']),
						'unit'               => get_unit($value['ID_UNIT']),
						'po_number'          => $value['PO_NUMBER'],
						'po_name'            => $value['PO_LINE_DESC'],
						'po_currency'        => $value['CURRENCY'],
						'po_rate'            => $value['CURRENCY_RATE'],
						'status'             => $value['STATUS'],
						'level'              => $value['LEVEL'],
						'status_description' => $status_description,
						'po_date'            => dateFormat($value['PO_DATE'], 5, false),
						'total_amount'       => number_format($value['PO_AMOUNT'],0,',','.')
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


   private function _email_approval($recipient, $id_po, $status, $remark, $id_approval =0){

		$get_po  = $this->purchase->get_po_for_email($id_po);
		$po_head = $get_po[0];
		$po_head = $get_po[0];

		// $currency      = ($get_po['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount     = number_format($po_head['PO_AMOUNT'],0,',','.');
		$po_number  = $po_head['PO_NUMBER'];
		$po_desc    = $po_head['PO_LINE_DESC'];
		$attachment = $po_head['DOCUMENT_SOURCING'];
		// $currency_rate = ($po_head['CURRENCY'] == "IDR") ? $po_head['CURRENCY'] : $po_head['CURRENCY'] ."/". number_format($po_head['CURRENCY_RATE'],0,'.',',');

		$approval_lnk = base_url("po/approval/").encrypt_string($id_po."-".$id_approval, true);

		$data['email_recipient']  = $recipient['name'];
		$action_name = get_user_data($this->session->userdata('user_id'));

		if($status == "request_approve"):
			$email_preview = "A new PO $po_number has been approved by $action_name and need for your approval";
			$title_first  = "Request approve";
		else:
			$email_preview = "Your PO $po_number has been <b>$status</b> by ".$action_name;
			$title_first  = ucfirst($status);	
		endif;
		$data['email_preview'] = $email_preview;

		$email_body = $email_preview. "
								<br>
								<br>
								Detail PO:
								<br>
								  <div class='hack1'>
								  	<div class='hack2'>
										<table class='custom_table'>
											<tbody>
												<tr>
														<th>No</th>
														<th>Item Name</th>
														<th>Item Description</th>
														<th>QTY</th>
														<th>UoM</th>
														<th>Unit Price</th>
														<th>Total Price</th>
												</tr>";

		$no = 1;
		foreach ($get_po as $key => $value) {

			$item_name   = $value['ITEM_NAME'];
			$item_desc   = $value['ITEM_DESC'];
			$qty         = $value['QUANTITY'];
			$uom         = $value['UOM'];
			$unit_price  = number_format($value['UNIT_PRICE'],0,'.',',');
			$total_price = number_format($value['TOTAL_PRICE'],0,'.',',');

			$email_body .= "<tr>";
				$email_body .= "<td align='center'>".$no."</td>";
				$email_body .= "<td>".$item_name."</td>";
				$email_body .= "<td>".$item_desc."</td>";
				$email_body .= "<td>".$qty."</td>";
				$email_body .= "<td>".$uom."</td>";
				$email_body .= "<td>".$unit_price."</td>";
				$email_body .= "<td>".$total_price."</td>";
			$email_body .= "</tr>";
			$no++;
		}

		$email_body .= 				"</tbody>
								</table></div></div>";

		$data['email_body'] = $email_body;
		$data['approval_link_all'] = base_url("po/approval");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$title = "PO ".ucfirst($title_first);

		$subject    = $title . " - " . $po_number ;
		$body       = $this->load->view('email/po_request_approve', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/po_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
	}



   private function _email_to_vendor($recipient, $id_po){

		$get_po  = $this->purchase->get_po_for_email($id_po);
		$po_head = $get_po[0];
		$po_head = $get_po[0];

		// $currency      = ($get_po['CURRENCY'] == "IDR") ? "Currency" : "Currency/Rate";
		$amount           = number_format($po_head['PO_AMOUNT'],0,',','.');
		$po_number        = $po_head['PO_NUMBER'];
		$po_desc          = $po_head['PO_LINE_DESC'];
		// $currency_rate = ($po_head['CURRENCY'] == "IDR") ? $po_head['CURRENCY'] : $po_head['CURRENCY'] ."/". number_format($po_head['CURRENCY_RATE'],0,'.',',');

		// $approval_lnk = base_url("api/link/print-po/").encrypt_string($id_po, true);
		$approval_lnk = "https://varelia.linkaja.com/";

		$data['email_recipient']  = $recipient['name'];

		$data['email_preview'] = "There's a Purchase Order (PO) with a number $po_number";

		$email_body = "There's a Purchase Order (PO) with a number $po_number related to $po_desc. Please download the PO on the Partner's dashboard / user profile so that you can immediately carry out related work by coordinating with the User.
								<br>
								<br>
								Or you can go through the <a href='$approval_lnk'>varelia link</a> to see all details.";

		$data['email_body'] = $email_body;
		$data['approval_link_all'] = base_url("po/approval");

		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$subject    = "Issuance of Purchase Order - " . $po_number ;
		$body       = $this->load->view('email/po_vendor_approved', $data, TRUE);
		$attachment = "";

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
	}

    private function _call_proc_po($id_po){

		$get_po_for_proc = $this->purchase->get_po_for_procedure($id_po);
		foreach ($get_po_for_proc as $key => $value) {
			$date1           = strtotime($value['PO_PERIOD_FROM']);
			$date2           = strtotime($value['PO_PERIOD_TO']);
			$month1          = date('n', $date1);
			$month2          = date('n', $date2);
			$month2          = (date('j', $date2) == 1) ? $month2-1 : $month2;
			$year1           = date('Y', $date1);
			$year2           = date('Y', $date2);
			$diff            = (($year2 - $year1) * 12) + ($month2 - $month1);
			$periode_pekerjaan = ($diff < 1) ? 1 : $diff+1;

			$no_po      = $value['PO_NUMBER'];
			$nature     = $value['NATURE'];
			$po_amount  = $value['PO_AMOUNT'];
			$capex_opex = ($value['CAPEX']) ? $value['CAPEX'] : $value['CAPEX_OPEX'];

			$param = array();

			$param[] = $no_po;
			$param[] = $po_amount;
			$param[] = $nature;
			$param[] = $periode_pekerjaan;
			$param[] = $capex_opex;

			// $this->crud->call_procedure("PO_ACCRUED", $param);
			$this->crud->call_procedure("PO_ACCRUED_NEW", $param);

			// code...
			unset($param);
		}

		return true;
    }


    private function _email_cancel_pr($recipient, $id_pr, $remark, $return=false, $attachment=""){

		$get_pr = $this->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $id_pr));
		
		$pr_name    = $get_pr['PR_NAME'];
		$pr_number  = $get_pr['PR_NUMBER'];

		$pr_link = base_url("purchase-requisition/").encrypt_string($id_pr, true);

		$data['pr_number']       = $pr_number;
		$data['pr_link']         = $pr_link;
		$data['pr_link_name']    = $pr_number ." - " . $pr_name;
		$data['remark']          = $remark;
		$data['email_recipient'] = $recipient['name'];

		$statusPR = ($return == true) ? "returned" : "canceled/rejected";

		$data['email_body'] = "Your PR <b>$pr_number </b> has been <b>$statusPR</b> by Procurement Team. You can see all the details about this PR by clicking the link below.";
		$data['email_preview'] = "Your PR $pr_number has been $statusPR by Procurement Team";
		
		$to = $recipient['email'];
		$cc = "";
		if(isset($recipient['email_cc'])){
			$cc = (is_array($recipient['email_cc'])) ? implode(",", $recipient['email_cc']) : $recipient['email_cc'];
		}

		$subject    = "PR $statusPR by Procurement ($pr_number - $pr_name)";
		$body       = $this->load->view('email/pr_cancellation', $data, TRUE);
		$attachment = ($attachment) ? FCPATH.'/uploads/po_attachment/'.$attachment : '';

		$send = sendemail($to, $subject, $body, $cc, $attachment);

		return $send;
    }

}

/* End of file ApprovalPO_ctl.php */
/* Location: ./application/controllers/pr_po/ApprovalPO_ctl.php */