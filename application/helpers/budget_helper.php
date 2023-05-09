<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('get_all_directorat')){
	
	function get_all_directorat($param=""){
		
		$CI   = get_instance();

		if(is_array($param)){
			$data = $CI->crud->read_by_param_specific("MASTER_DIRECTORAT", $param);
		}else{
			$data = $CI->crud->read("MASTER_DIRECTORAT", FALSE, "DIRECTORAT_NAME");
		}

		return $data;
		
	}
}

if ( ! function_exists('get_directorat')){
	
	function get_directorat($id, $field="DIRECTORAT_NAME"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_DIRECTORAT", array("ID_DIR_CODE" => $id));

		if($data){
			return $data[$field];
		}else{
			return '';
		}

	}
}


if ( ! function_exists('get_all_division')){
	
	function get_all_division($param=""){
		
		$CI   = get_instance();

		if(is_array($param)){
			$data = $CI->crud->read_by_param_specific("MASTER_DIVISION", $param);
		}else{
			$data = $CI->crud->read("MASTER_DIVISION", FALSE, "DIVISION_NAME");
		}

		return $data;
		
	}
}

if ( ! function_exists('get_division')){
	
	function get_division($id, $field="DIVISION_NAME"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_DIVISION", array("ID_DIVISION" => $id));

		if($data){
			return $data[$field];
		}else{
			return '';
		}
		
	}
}

if ( ! function_exists('get_all_unit')){
	
	function get_all_unit($param=""){
		
		$CI   = get_instance();

		if(is_array($param)){
			$data = $CI->crud->read_by_param_specific("MASTER_UNIT", $param);
		}else{
			$data = $CI->crud->read("MASTER_UNIT", FALSE, "UNIT_NAME");
		}

		return $data;
		
	}
}

if ( ! function_exists('get_unit')){
	
	function get_unit($id, $field="UNIT_NAME"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_UNIT", array("ID_UNIT" => $id));

		if($data){
			return $data[$field];
		}else{
			return '';
		}
		
	}
}


if ( ! function_exists('get_district_by_id')){
	
	function get_district_by_id($id, $field="DISTRICT"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_DISTRICT", array("DISTRICT_ID" => $id));

		if($data){
			return $data[$field];
		}else{
			return '';
		}
		
	}
}


if ( ! function_exists('get_district')){
	
	function get_district(){

		$CI   = get_instance();
		$get_data = $CI->crud->read("MASTER_DISTRICT", "", "DISTRICT");
		
		return $get_data;
	}
}



if ( ! function_exists('get_rkap')){
	
	function get_rkap($id, $field="RKAP_DESCRIPTION"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("BUDGET_HEADER", array("ID_RKAP_LINE" => $id));

		if($data){
			return $data[$field];
		}else{
			return '';
		}
	}
}

if ( ! function_exists('get_fs')){
	
	function get_fs($id, $field="FS_NUMBER"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("FS_BUDGET", array("ID_FS" => $id));

		return $data[$field];
		
	}
}

if ( ! function_exists('get_type')){
	
	function get_type($id, $field="FPJP_NAME"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_FPJP", array("ID_MASTER_FPJP" => $id));

		return $data[$field];
		
	}
}

if ( ! function_exists('get_submiter')){
	
	function get_submiter($id, $field="NAMA"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_EMPLOYEE", array("NAMA" => $id));

		return $data[$field];
		
	}
}

if ( ! function_exists('get_dik1')){
	
	function get_dik($id, $field="NAMA"){

		$CI   = get_instance();
		$data = $CI->crud->read_by_id("MASTER_EMPLOYEE", array("NAMA" => $id));

		return $data[$field];
		
	}
}

if ( ! function_exists('get_all_type')){
	
	function get_all_type(){
		
		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_FPJP", FALSE, "FPJP_NAME");

		return $data;
		
	}
}

if ( ! function_exists('get_all_bank')){
	
	function get_all_bank(){
		
		$CI   = get_instance();
		$data = $CI->crud->read_by_param_specific("MASTER_BANK", "", "BANK_NAME", true);

		return $data;
		
	}
}

if ( ! function_exists('get_bank_la')){
	
	function get_bank_la(){
		
		$CI   = get_instance();
		$data = $CI->crud->read_by_param("MASTER_BANK_LA", array("FLAG" => "Y"));

		return $data;
		
	}
}

if ( ! function_exists('get_segment')){
	
	function get_segment($segment='SEGMENT6'){
		
		$CI   = get_instance();
		$data = $CI->crud->read("ORACLE_COA", array("SEGMENT" => $segment, "FLAG" => "Y"), "FLEX_VALUE");

		return $data;
		
	}
}

if ( ! function_exists('get_all_vendor')){
	
	function get_all_vendor(){
		
		$CI   = get_instance();
		$data = $CI->crud->read("ORACLE_SUPPLIER", FALSE, "VENDOR_NAME");

		foreach ($data as $key => $value) {
			$result[] = array(
								"NAMA_VENDOR"   => $value['VENDOR_NAME'],
								"NAMA_BANK"     => $value['BANK_NAME'],
								"NAMA_REKENING" => $value['ACCOUNT_OWNER'],
								"ACCT_NUMBER"   => $value['CLEAR_BANK_ACCOUNT_NUMBER']
								);
		}
		return $result;
		
	}

}

if ( ! function_exists('get_all_vendor_bank')){
	
	function get_all_vendor_bank(){
		
		$CI   = get_instance();
		$data = $CI->crud->read("ORACLE_SUPPLIER", FALSE, "VENDOR_NAME");
		$last_vendor = "";

		foreach ($data as $key => $value) {
			$vendor_name = $value['VENDOR_NAME'];
			$key = md5(strtolower($vendor_name));
			if($last_vendor != $vendor_name){
				$vendors[$key] = $vendor_name;
			}
			$last_vendor = $vendor_name;
		}

		foreach ($data as $key => $value) {
			$vendor_name = $value['VENDOR_NAME'];
			$key = md5(strtolower($vendor_name));
			$banks[$key][] = array(
								"NAMA_BANK"     => $value['BANK_NAME'],
								"NAMA_REKENING" => $value['ACCOUNT_OWNER'],
								"SITE_CODE"     => $value['VENDOR_SITE_CODE'],
								"ACCT_NUMBER"   => $value['CLEAR_BANK_ACCOUNT_NUMBER']
								);
		}

		$result['vendor'] = $vendors;
		$result['bank']   = $banks;

		return $result;
		
	}

}

if ( ! function_exists('get_all_vendor_gl')){
	
	function get_all_vendor_gl(){
		
		$CI    = get_instance();
		$CI->load->model('GL_mdl', 'gl');
		$hasil = $CI->gl->get_all_vendor();
		$data  = $hasil['query']->result_array();

		return $data;
		
	}
}

if ( ! function_exists('get_all_batch_approval_validate')){
	
	function get_all_batch_approval_validate(){
		
		$CI    = get_instance();
		$CI->load->model('Uservalidate_mdl', 'uservalidate');
		$hasil = $CI->uservalidate->get_approve_batch();
		$data  = $hasil['query']->result_array();

		return $data;
		
	}
}

if ( ! function_exists('get_currency')){
	
	function get_currency(){

		$currency = array(
							"IDR" => "IDR",
							"USD" => "USD",
							"EUR" => "EUR",
							"SGD" => "SGD",
							"JPY" => "JPY",
							"HKD" => "HKD",
							"AUS" => "AUS"
						);

		return $currency;
		
	}
}


if ( ! function_exists('get_status_fpjp')){
	
	function get_status_fpjp(){

		$currency = array(
							"request_approve" => "Waiting Approval",
							"approved"        => "Approved",
							"rejected"        => "Rejected",
							"returned"        => "Returned"
						);

		return $currency;
		
	}
}

if ( ! function_exists('get_status_pr')){
	
	function get_status_pr(){

		$currency = array(
							"request_approve" => "Waiting Approval",
							"approved"        => "Approved",
							"rejected"        => "Rejected",
							"returned"        => "Returned",
							"po created"      => "PO Created"
						);

		return $currency;
		
	}
}

if ( ! function_exists('get_status_po')){
	
	function get_status_po(){

		$currency = array(
							"request_approve" => "Waiting Approval",
							"approved"        => "Approved",
							"rejected"        => "Rejected",
							"returned"        => "Returned",
							"canceled"        => "Canceled",
							"PAID"            => "PAID",
							"PARTIAL PAID"    => "PARTIAL PAID"
						);

		return $currency;
		
	}
}

if ( ! function_exists('get_status_fs')){
	
	function get_status_fs(){

		$currency = array(
							"request_approve" => "Waiting Approval",
							"approved"        => "Approved",
							"returned"        => "Returned",
							"rejected"        => "Rejected",
							"fs used"         => "FS Used"
						);

		return $currency;
		
	}
}

if ( ! function_exists('get_nature')){
	
	function get_nature($param=""){
		
		$CI   = get_instance();

		if(is_array($param)){
			$data = $CI->crud->read_by_param_in("MASTER_COA", "ID_MASTER_COA", $param);
		}else{
			$data = $CI->crud->read("MASTER_COA", FALSE, "NATURE");
		}

		return $data;
		
	}
}

if ( ! function_exists('check_is_approver')){
	
	function check_is_approver($email){
		
		$CI = get_instance();
		$CI->load->model('feasibility_study_mdl', 'feasibility_study');
		$CI->load->model('purchase_mdl', 'purchase');
		$CI->load->model('fpjp_mdl', 'fpjp');
		// $CI->load->model('GR_mdl', 'gr');
		$CI->load->model('dpl_mdl', 'dpl');

		$result['is_fs']           =  $CI->feasibility_study->check_is_approval($email);
		$result['is_pr']           =  $CI->purchase->check_is_approval($email);
		// $result['is_gr']           =  $CI->gr->check_is_approval($email);
		$result['is_fpjp']         =  $CI->fpjp->check_is_approval($email);
		// $result['is_po']           =  $CI->purchase->check_is_approval_po($email);
		$result['is_pr_assign']    =  $CI->purchase->check_is_pr_assigner($email);
		$result['is_coa_review']   =  $CI->purchase->check_is_coa_review($email);
		$result['is_dpl_verifier'] =  $CI->dpl->check_is_verifier($email);
		$result['is_dpl_approval'] =  $CI->dpl->check_is_approval_dpl($email);
		// $result['is_gr_review']    =  $CI->gr->check_is_reviewer($email);

		return $result;
		
	}
}

if ( ! function_exists('count_all_request')){
	
	function count_all_request($email="", $category=""){

		$CI   = get_instance();

		$email = ($email != "") ? $email : $CI->session->userdata('email');

		$total = 0;

		if( $category == "budget_approval"):
			$CI->load->model('feasibility_study_mdl', 'feasibility_study');
			$get_total  = $CI->feasibility_study->count_pending_budget($email);
		endif;

		if( $category == "fpjp_approval"):
			$CI->load->model('fpjp_mdl', 'fpjp');
			$get_total  = $CI->fpjp->count_pending_fpjp($email)
;		endif;

		if( $category == "pr_approval"):
			$CI->load->model('purchase_mdl', 'purchase');
			$get_total  = $CI->purchase->count_pending_pr($email);
		endif;

		if( $category == "po_approval"):
			$CI->load->model('purchase_mdl', 'purchase');
			$get_total  = $CI->purchase->count_pending_po($email);
		endif;

		if( $category == "pr_assign"):
			$CI->load->model('purchase_mdl', 'purchase');
			$get_total = $CI->purchase->count_pending_assign_pr();
		endif;
		
		if( $category == "coa_review"):
			$CI->load->model('coa_review_mdl', 'coa');
			$get_total = $CI->coa->count_pending_coa_review();
			$total     = ($get_total) ? $get_total : 0;
		endif;
		
		if( $category == "drafting_po"):
			$CI->load->model('purchase_mdl', 'purchase');
			$get_total = $CI->purchase->count_outstanding_pr($email);
			// echo $CI->db->last_query();die;
		endif;
		
		if( $category == "verification_dpl"):
			$CI->load->model('dpl_mdl', 'dpl');
			$get_total = $CI->dpl->count_dpl_pending_verification($email);
			$total     = ($get_total) ? $get_total : 0;
		endif;
		
		if( $category == "dpl_approval"):
			$CI->load->model('dpl_mdl', 'dpl');
			$get_total = $CI->dpl->count_pending_dpl($email);
		endif;
		
		if( $category == "gr_approval"):
			$CI->load->model('GR_mdl', 'gr');
			$get_total  = $CI->gr->count_pending_gr($email);
		endif;
		
		if( $category == "gr_review"):
			$CI->load->model('GR_mdl', 'gr');
			$get_total = $CI->gr->check_gr_to_review($email);
		endif;
		
		if($total > 0){
			$total = $total;
		}else{
			$total = ($get_total) ? $get_total['TOTAL'] : 0;
		}

		return $total;

	}
}

if ( ! function_exists('get_user_approval_ap')){
	
	function get_user_approval_ap($category){
		
		$CI   = get_instance();
		$get_data = $CI->crud->read("MASTER_APPROVAL_AP", array("PIC_LEVEL" => $category));

		return $get_data;

	}
}

if ( ! function_exists('get_approval_by_category')){
	
	function get_approval_by_category($category){
		
		$CI   = get_instance();
		$get_data = $CI->crud->read("MASTER_APPROVAL", array("IS_EXIST" => 1, "PIC_LEVEL" => $category));

		return $get_data;

	}
}

if ( ! function_exists('get_approval_by_email_level')){
	
	function get_approval_by_email_level($email, $category=""){
		
		$CI   = get_instance();
		$param['PIC_EMAIL'] = $email;
		if($category){
			$param['PIC_LEVEL'] = $category;
		}
		$get_data = $CI->crud->read_by_param("MASTER_APPROVAL", $param);

		return $get_data;

	}
}

if ( ! function_exists('get_auto_reject_date')){
	
	function get_auto_reject_date(){
		$j = 0;
		for ($i=1; $i <= 14 ; $i++) {
			if(date("N", strtotime('+'.$i.' day', time())) < 6){
				$j++;
			}
			if($j == 8){
				$date = date("Y-m-d", strtotime('+'.$i.' day', time()));
				break;
			}
		}

		return $date;
		
	}
}

if ( ! function_exists('get_proc_type')){
	
	function get_proc_type(){

		$CI   = get_instance();
		$get_data = $CI->crud->read("MASTER_PROC_TYPE", "", "PROC_NAME");
		foreach ($get_data as $key => $value) {
			$data[] = $value['PROC_NAME'];
		}

		return $data;
	}
}


if ( ! function_exists('check_binding')){
	
	function check_binding(){

		$CI = get_instance();

		$data_employee = $CI->session->userdata('data_employee');

		$data_dir    = array();
		$data_div    = array();
		$data_unit   = array();
		$binding     = false;

		if($data_employee){
			$level = ($CI->session->userdata('email') == "ikhsan_ramdan@linkaja.id" ) ? "GENERAL" : $data_employee['level'] ;

			if($level != "GENERAL" && $level != "BOC"){

				$binding    = "directorat";
				$data_dir[] = $data_employee['directorat'];

				if($level != "BOD"){

					$binding   = "division";
					$data_div = $data_employee['division'];

					if($level == "HOU"){
						$binding = "unit";
						$data_unit = $data_employee['unit'];
					}
				}
			}
		}

		$result['binding']      = ($CI->session->userdata('email') == "haryati_lawidjaja@linkaja.id") ? false : $binding;
		$result['data_binding'] = array( "directorat" => $data_dir,  "division" => $data_div,  "unit" => $data_unit);

		return $result;

	}
}

if ( ! function_exists('get_status_gr')){
	
	function get_status_gr(){

		$currency = array(
							"request_approve" => "Waiting Approval",
							"approved"        => "Approved",
							"rejected"        => "Rejected",
							"returned"        => "Returned",
						);

		return $currency;
		
	}
}

if ( ! function_exists('get_all_major_category')){
	
	function get_all_major_category(){

		/*$CI    = get_instance();
		$CI->load->model('GR_mdl', 'gr');
		$hasil = $CI->gr->get_all_major_category_data();
		$data  = $hasil['query']->result_array();*/

		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_MAJOR_CATEGORY", FALSE, "MAJOR_NAME");

		return $data;
	}
}

if ( ! function_exists('get_all_minor_category')){
	
	function get_all_minor_category($major_code){

		/*$CI    = get_instance();
		$CI->load->model('GR_mdl', 'gr');
		$hasil = $CI->gr->get_all_minor_category_data($major_code);
		$data  = $hasil['query']->result_array();*/

		$CI   = get_instance();
		$data = $CI->crud->read_by_param_specific("MASTER_MINOR_CATEGORY", array("MAJOR_CODE" => $major_code));

		return $data;
	}
}

if ( ! function_exists('get_all_region')){
	
	function get_all_region(){

		/*$CI    = get_instance();
		$CI->load->model('GR_mdl', 'gr');
		$hasil = $CI->gr->get_all_region_data();
		$data  = $hasil['query']->result_array();*/

		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_REGION", FALSE, "REGION");

		return $data;
	}
}

if ( ! function_exists('get_location')){
	
	function get_location($param=""){
		
		$CI   = get_instance();

		$data = $CI->crud->read("MASTER_LOCATION", FALSE, "LOCATION");
		return $data;
		
	}
}

if ( ! function_exists('get_all_location')){
	
	function get_all_location($region_code){

		/*$CI    = get_instance();
		$CI->load->model('GR_mdl', 'gr');
		$hasil = $CI->gr->get_all_location_data($region_code);
		$data  = $hasil['query']->result_array();*/

		$CI   = get_instance();
		$data = $CI->crud->read_by_param_specific("MASTER_LOCATION", array("REGIONCODE" => $region_code));

		return $data;
	}
}

if ( ! function_exists('get_all_project_owner_unit')){
	
	function get_all_project_owner_unit($id_dir_code){

		/*$CI    = get_instance();
		$CI->load->model('GR_mdl', 'gr');
		$hasil = $CI->gr->get_all_project_owner_unit_data($id_dir_code);
		$data  = $hasil['query']->result_array();*/

		$CI   = get_instance();
		$data = $CI->crud->read_by_param_specific("MASTER_PROJECT_OWNERSHIP", array("ID_DIR_CODE" => $id_dir_code));

		return $data;
	}
}

if ( ! function_exists('get_data_ci')){
	
	function get_data_ci(){

		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_CONTRACT_INDETIFICATION", FALSE, "NAME");

		return $data;
	}
}

if ( ! function_exists('get_data_project_owner')){
	
	function get_data_project_owner(){

		/*$CI    = get_instance();
		$CI->load->model('GR_mdl', 'gr');
		$hasil = $CI->gr->get_all_project_ownership_data();
		$data  = $hasil['query']->result_array();*/

		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_OWNERSHIP", FALSE, "OWNERSHIP_NAME");

		return $data;
	}
}

if ( ! function_exists('get_pr_history')){
	
	function get_pr_history($id_pr, $pr_header=false){

		$CI   = get_instance();
		$CI->load->model('purchase_mdl', 'purchase');

		$get_pr_header = (is_array($pr_header)) ? $pr_header : $CI->crud->read_by_param("PR_HEADER", array("PR_HEADER_ID" => $id_pr));
		$get_po_header =  $CI->crud->read_by_param("PO_HEADER", array("ID_PR_HEADER_ID" => $id_pr));

		$get_history = $CI->purchase->get_comment_history($id_pr);
		$pr_history[] = array("PIC_NAME" => $get_pr_header['SUBMITTER'], "STATUS" => "Submitted", "REMARK" => "", "ACTION_DATE" =>  dateFormat($get_pr_header['CREATED_DATE'], 'fintool', false));

		foreach ($get_history as $key => $value) {
			$status = $value['STATUS'];
			if(strtolower($value['CATEGORY']) == "procurement"){
				$status = ($status == "approved") ? "verified" : $status;
			}
			$pr_history[] = array("PIC_NAME" => $value['PIC_NAME'], "STATUS" => $status, "REMARK" => $value['REMARK'], "ACTION_DATE" => dateFormat($value['UPDATED_DATE'], 'fintool', false));
		}

		if($get_pr_header['STATUS_ASSIGN'] == "Y"){
			$action_date = ($get_pr_header['ASSIGN_DATE']) ? dateFormat($get_pr_header['ASSIGN_DATE'], 'fintool', false) : '';
			$pr_history[] = array("PIC_NAME" => $get_pr_header['PIC_ASSIGN'], "STATUS" => "Assigned", "REMARK" => $get_pr_header['REMARK_ASSIGN'], "ACTION_DATE" => $action_date);
		}

		if($get_pr_header['COA_REVIEW'] == "Y" && $get_pr_header['PIC_COA'] != ""){
			$action_date = ($get_pr_header['COA_REVIEW_DATE']) ? dateFormat($get_pr_header['COA_REVIEW_DATE'], 'fintool', false) : '';
			$pr_history[] = array("PIC_NAME" => $get_pr_header['PIC_COA'], "STATUS" => "Reviewed", "REMARK" => "", "ACTION_DATE" => $action_date);
		}

		if($get_pr_header['PIC_RETURN'] != ""){
			// $get_pic_name = $CI->crud->read_by_param("MASTER_APPROVAL", array("PIC_EMAIL" => $get_pr_header['PIC_RETURN']));
			$action_date = ($get_pr_header['RETURN_DATE']) ? dateFormat($get_pr_header['RETURN_DATE'], 'fintool', false) : '';
			$pr_history[] = array("PIC_NAME" => $get_pr_header['PIC_RETURN'], "STATUS" => "Returned", "REMARK" => $get_pr_header['REMARK_RETURN'], "ACTION_DATE" => $action_date);
		}

		if($get_pr_header['PIC_PO_REJECT'] != ""){
			$action_date = ($get_pr_header['PO_REJECT_DATE']) ? dateFormat($get_pr_header['PO_REJECT_DATE'], 'fintool', false) : '';
			$pr_history[] = array("PIC_NAME" => $get_pr_header['PIC_PO_REJECT'], "STATUS" => "PO Rejected", "REMARK" => $get_pr_header['REMARK_REJECT'], "ACTION_DATE" => $action_date);
		}

		if($get_pr_header['RESUBMIT_BY'] != ""){
			$action_date = ($get_pr_header['RESUBMIT_DATE']) ? dateFormat($get_pr_header['RESUBMIT_DATE'], 'fintool', false) : '';
			$pr_history[] = array("PIC_NAME" => $get_pr_header['RESUBMIT_BY'], "STATUS" => "Resubmited", "REMARK" => "", "ACTION_DATE" => $action_date);
		}

		if($get_po_header){
			$action_date = ($get_po_header['CREATED_DATE']) ? dateFormat($get_po_header['CREATED_DATE'], 'fintool', false) : '';
			$pr_history[] = array("PIC_NAME" => $get_po_header['BUYER'], "STATUS" => "PO Created", "REMARK" => '-', "ACTION_DATE" => $action_date);
		}

		$pr_history_sort = array();
		foreach ($pr_history as $key => $row)
		{
			$action_date = strtotime($row['ACTION_DATE']);
		    $pr_history_sort[$key] = $action_date;
		}
		array_multisort($pr_history_sort, SORT_ASC, $pr_history);

		return $pr_history;
	}
}

if ( ! function_exists('get_po_history')){
	
	function get_po_history($id_po, $po_header=false){

		$CI   = get_instance();
		$CI->load->model('purchase_mdl', 'purchase');

		$get_po_header = (is_array($po_header)) ? $po_header : $CI->crud->read_by_param("PO_HEADER", array("PO_HEADER_ID" => $id_po));

		$get_history = $CI->purchase->get_comment_history_po($id_po);

		if($get_po_header['BUYER']){
			$submitter = $get_po_header['BUYER'];
		}else{
			$created_by = $get_po_header['CREATED_BY'];
			if($created_by == "Wahyu Bijaksana" || $created_by == "wahyu_bijaksana"){
				$submitter = "Wahyu Bijaksana";
			}
			elseif($created_by == "dita_lestari"){
				$submitter = "Dita Fuji Lestari";
			}else{
				$submitter = "Admin";
			}
		}
		$po_history[] = array("PIC_NAME" => $submitter, "STATUS" => "Submitted", "REMARK" => "", "ACTION_DATE" =>  dateFormat($get_po_header['CREATED_DATE'], 'fintool', false));

		foreach ($get_history as $key => $value) {
			$status = $value['STATUS'];
			$action_date = ($value['ACTION_DATE']) ? $value['ACTION_DATE'] : $value['UPDATED_DATE'];
			$po_history[] = array("PIC_NAME" => $value['PIC_NAME'], "STATUS" => $status, "REMARK" => $value['REMARK'], "ACTION_DATE" => dateFormat($action_date, 'fintool', false));
		}

		if($get_po_header['RESUBMIT_BY'] != ""){
			$action_date = ($get_po_header['PO_RESUBMIT_DATE']) ? dateFormat($get_po_header['PO_RESUBMIT_DATE'], 'fintool', false) : '';
			$po_history[] = array("PIC_NAME" => $get_po_header['RESUBMIT_BY'], "STATUS" => "Resubmited", "REMARK" => "", "ACTION_DATE" => $action_date);
		}

		$po_history_sort = array();
		foreach ($po_history as $key => $row)
		{
			$action_date = strtotime($row['ACTION_DATE']);
		    $po_history_sort[$key] = $action_date;
		}
		array_multisort($po_history_sort, SORT_ASC, $po_history);

		return $po_history;
	}
}

if ( ! function_exists('getTujuanDPL')){
	
	function getTujuanDPL(){

		$tujuanData[] = 'Revenue Generation ';
		$tujuanData[] = 'Increase Subscriber Base ';
		$tujuanData[] = 'Cost Leadership/Cost Saving/Cost Effectiveness';
		$tujuanData[] = 'Brand Awareness';
		$tujuanData[] = 'Network Capacity /Coverage';
		$tujuanData[] = 'Customer Process';
		$tujuanData[] = 'Corporate Images/ Value';
		$tujuanData[] = 'Legal/Regulatory/Compliance/Risk Management';
		$tujuanData[] = 'Skill/Professional/Training/Management/HCM';
		$tujuanData[] = 'Others: Industry Intelligence';

		return $tujuanData;
		
	}
}

if ( ! function_exists('getKriteriaDPL')){
	
	function getKriteriaDPL(){

		$kriteriaData[] = 'Penyedia/Supplier/Rekanan Tunggal';
		$kriteriaData[] = 'Lanjutan dari Pekerjaan sebelumnya yang tidak terelakkan';
		$kriteriaData[] = 'Critical (Penting & Mendesak)';

		return $kriteriaData;
		
	}
}

if ( ! function_exists('checkIsDPLVerified')){
	
	function checkIsDPLVerified($id_dpl){

		$CI   = get_instance();
		$data = $CI->crud->read_by_param("DPL", array("ID_DPL" => $id_dpl));

		if($data['STATUS_VERIF'] == 2):
			return true;
		else:
			return false;
		endif;
		
	}
}

if ( ! function_exists('check_is_dpl_approval')){
	
	function check_is_dpl_approval($id_dpl, $id_approval){

		$CI   = get_instance();
		$data = $CI->crud->read_by_param("TRX_APPROVAL_DPL", array("ID_DPL" => $id_dpl, "ID_APPROVAL" => $id_approval));
		
		return $data;
		
	}
}

if ( ! function_exists('check_is_justif_approval')){
	
	function check_is_justif_approval($id_fs, $id_approval){

		$CI   = get_instance();
		$data = $CI->crud->read_by_param("TRX_APPROVAL", array("ID_FS" => $id_fs, "ID_APPROVAL" => $id_approval, "IS_ACTIVE" => 1 ));
		
		return $data;
		
	}
}



if ( ! function_exists('get_dpl_history')){
	
	function get_dpl_history($id_dpl, $dpl_data=false){

		$CI   = get_instance();
		$CI->load->model('dpl_mdl', 'dpl');

		$get_dpl = (is_array($dpl_data)) ? $dpl_data : $CI->crud->read_by_param("DPL_HEADER", array("DPL_HEADER_ID" => $id_dpl));
		// echo_pre($get_dpl);die;
		$get_po_header =  $CI->crud->read_by_param("PO_HEADER", array("ID_DPL_HEADER_ID" => $id_dpl));

		$get_history = $CI->dpl->get_comment_history($id_dpl);
		$dpl_history[] = array("PIC_NAME" => $get_dpl['SUBMITTER'], "STATUS" => "Submitted", "REMARK" => "", "ACTION_DATE" =>  dateFormat($get_dpl['CREATED_DATE'], 'fintool', false));

		foreach ($get_history as $key => $value) {
			$status = $value['STATUS'];
			if(strtolower($value['CATEGORY']) == "procurement"){
				$status = ($status == "approved") ? "verified" : $status;
			}
			$dpl_history[] = array("PIC_NAME" => $value['PIC_NAME'], "STATUS" => $status, "REMARK" => $value['REMARK'], "ACTION_DATE" => dateFormat($value['UPDATED_DATE'], 'fintool', false));
		}

		if($get_dpl['STATUS_ASSIGN'] == "Y"){
			$action_date = ($get_dpl['ASSIGN_DATE']) ? dateFormat($get_dpl['ASSIGN_DATE'], 'fintool', false) : '';
			$dpl_history[] = array("PIC_NAME" => $get_dpl['PIC_ASSIGN'], "STATUS" => "Assigned", "REMARK" => $get_dpl['REMARK_ASSIGN'], "ACTION_DATE" => $action_date);
		}

		if($get_dpl['COA_REVIEW'] == "Y" && $get_dpl['PIC_COA'] != ""){
			$action_date = ($get_dpl['COA_REVIEW_DATE']) ? dateFormat($get_dpl['COA_REVIEW_DATE'], 'fintool', false) : '';
			$dpl_history[] = array("PIC_NAME" => $get_dpl['PIC_COA'], "STATUS" => "Reviewed", "REMARK" => "", "ACTION_DATE" => $action_date);
		}

		if($get_dpl['PIC_RETURN'] != ""){
			$get_pic_name = $CI->crud->read_by_param("MASTER_APPROVAL", array("PIC_EMAIL" => $get_dpl['PIC_RETURN']));
			$action_date = ($get_dpl['RETURN_DATE']) ? dateFormat($get_dpl['RETURN_DATE'], 'fintool', false) : '';
			$dpl_history[] = array("PIC_NAME" => $get_pic_name['PIC_NAME'], "STATUS" => "Returned", "REMARK" => $get_dpl['REMARK_RETURN'], "ACTION_DATE" => $action_date);
		}

		if($get_po_header){
			$action_date = ($get_po_header['CREATED_DATE']) ? dateFormat($get_po_header['CREATED_DATE'], 'fintool', false) : '';
			$dpl_history[] = array("PIC_NAME" => $get_po_header['BUYER'], "STATUS" => "PO Created", "REMARK" => '-', "ACTION_DATE" => $action_date);
		}

		$dpl_history_sort = array();
		foreach ($dpl_history as $key => $row)
		{
			$action_date = strtotime($row['ACTION_DATE']);
		    $dpl_history_sort[$key] = $action_date;
		}
		array_multisort($dpl_history_sort, SORT_ASC, $dpl_history);

		return $dpl_history;
	}
}


if ( ! function_exists('get_absortion_justif')){
	
	function get_absortion_justif($id_fs=false, $id_rkap_line=false, $nominal=true){

		$CI   = get_instance();
		$CI->load->model('budget_mdl', 'budget');

		$get_absortion = $CI->budget->get_absortion_justif($id_fs, $id_rkap_line);

		$result = array();
		if($get_absortion->num_rows() > 0){

			foreach ($get_absortion->result_array() as $key => $value) {

				if($nominal){
					$enc_key = encrypt_string($value['ID_FS'].$value['ID_RKAP_LINE'].$value['NOMINAL_FS'], TRUE);
				}else{
					$enc_key = encrypt_string($value['ID_FS'].$value['ID_RKAP_LINE'], TRUE);
				}

				$result[] = array(
									"ENC_KEY"          => $enc_key,
									"ID_FS"            => $value['ID_FS'],
									"ID_RKAP_LINE"     => $value['ID_RKAP_LINE'],
									"NOMINAL_FS"       => $value['NOMINAL_FS'],
									"RKAP_DESCRIPTION" => $value['RKAP_DESCRIPTION'],
									"MONTH"            => $value['MONTH'],
									"ENTRY_OPTIMIZE"   => $value['ENTRY_OPTIMIZE'],
									"TRIBE_USECASE"    => $value['TRIBE_USECASE'],
									"FA_FS"            => $value['FA_FS'],
									"ABS_PR"           => $value['ABS_PR'],
									"ABS_FPJP"         => $value['ABS_FPJP'],
									"RELOC_IN"         => $value['RELOC_IN'],
									"RELOC_OUT"        => $value['RELOC_OUT'],
									"REDIS_IN"         => $value['REDIS_IN'],
									"REDIS_OUT"        => $value['REDIS_OUT']
								);
			}

		}
		
		return $result;
		
	}
}
