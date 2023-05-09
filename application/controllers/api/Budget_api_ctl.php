<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_api_ctl extends CI_Controller {

	public function __construct()
	{
		
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
	}

	public function load_fs_header()
	{

		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');
		$category   = $this->input->post('category');
		
		$get_fs = $this->budget->get_fs_header($directorat, $division, $unit, $category);
		$result['status'] = false;

		if($get_fs){
			$get_fs_unique = array_unique($get_fs,SORT_REGULAR);

			foreach($get_fs_unique as $row)	{

				$data[] = array(
								"id_fs"     => $row['ID_FS'],
								"fs_number" => $row['FS_NUMBER'],
								"fs_name"   => $row['FS_NAME'],
								"is_dpl"    => $row['IS_DPL'],
								"currency"  => ($row['CURRENCY']) ? $row['CURRENCY'] : "IDR",
								"rate"      => $row['CURRENCY_RATE']
							);

			}
			$result['status'] = true;
			$result['data']   = $data;
		}

		echo json_encode($result);

    }

    public function load_fs_header_dpl()
	{

		$directorat = $this->input->post('directorat');
		$division   = $this->input->post('division');
		$unit       = $this->input->post('unit');
		
		$get_fs = $this->budget->get_fs_header_dpl($directorat, $division, $unit);
		$result['status'] = false;

		if($get_fs){
			$get_fs_unique = array_unique($get_fs,SORT_REGULAR);

			foreach($get_fs_unique as $row)	{

				$data[] = array(
								"id_fs"     => $row['ID_FS'],
								"fs_number" => $row['FS_NUMBER'],
								"fs_name"   => $row['FS_NAME'],
								"amount"    => $row['NOMINAL_FS']
							);

			}
			$result['status'] = true;
			$result['data']   = $data;
		}

		echo json_encode($result);

    }

	public function load_dpl()
	{

		$id_fs = $this->input->post('id_fs');

		$get_dpl = $this->crud->read_by_param_specific("DPL", array("ID_FS" => $id_fs, "STATUS" => "approved"), "ID_DPL, DPL_NUMBER");
		$result['status'] = false;

		if($get_dpl){
			// $get_dpl_unique = array_unique($get_dpl,SORT_REGULAR);

			foreach($get_dpl as $row)	{

				$data[] = array(
								"id_dpl"     => $row['ID_DPL'],
								"dpl_number" => $row['DPL_NUMBER']
							);

			}
			$result['status'] = true;
			$result['data']   = $data;
		}

		echo json_encode($result);

    }

	public function load_data_rkap_view()
	{

		$category        = $this->input->post('category');
		$directorat_name = $this->input->post('directorat');
		$division_name   = $this->input->post('division');
		$unit_name       = $this->input->post('unit');
		$tribe           = $this->input->post('tribe');
		$list_id_rkap    = $this->input->post('list_id_rkap');

		$groups = $this->session->userdata('group_id');

		foreach ($groups as $key => $value) {
			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

		$su_budget = in_array("SU Budget", $group_name) ? true : false;

		$get_rkap  = $this->budget->get_rkap_from_view($category, $directorat_name, $division_name, $unit_name, $list_id_rkap, $su_budget);

		if($get_rkap['total'] > 0){

			$result['status'] = true;

			foreach($get_rkap['data'] as $row)	{

				if($category == "division"){
					$data[] = array(
									"id_division" => $row['ID_DIVISION'],
									"division"    => $row['DIVISION']
							);
				}
				elseif($category == "unit"){
					$data[] = array(
									"id_unit" => $row['ID_UNIT'],
									"unit"    => $row['UNIT']
							);
				}
				elseif($category == "tribe"){
					$data[] = array(
									"tribe" => $row['TRIBE_USECASE']
							);
				}

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }


	public function load_data_fs()
	{

		$id_rkap = $this->input->post('id_rkap');
		$get_fs  = $this->crud->read("FS_BUDGET", array("ID_RKAP_LINE" => $id_rkap), "FS_NAME");

		if($get_fs){

			$result['status'] = true;

			foreach($get_fs as $row) {

				$data[] = array(
								"id_fs"     => $row['ID_FS'],
								"fs_name"   => $row['FS_NAME']." - ".$row['FS_DESCRIPTION'],
								"fs_number" => $row['FS_NUMBER']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function load_data_fa_fs(){

		$id_fs     = $this->input->post('id_fs');
		$get_fa_fs = $this->budget->get_fa_fs($id_fs);

		if($get_fa_fs){

			$data['fa_fs'] = $get_fa_fs['FA_FS'];

			$result['status'] = true;
			$result['data']   = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function load_data_fa_rkap()
	{

		$id_rkap          = $this->input->post('id_rkap');
		$get_fund_nominal = $this->budget->get_fa_rkap($id_rkap);

		if($get_fund_nominal['total'] > 0){

			$row = $get_fund_nominal['data'];
			$data['fa_rkap'] = $row['FA_RKAP'];

			$result['status'] = true;
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);

    }

	public function load_data_rkap()
	{

		$directorat_name = $this->input->post('directorat');
		$division_name   = $this->input->post('division');
		$unit_name       = $this->input->post('unit');
		$tribe           = $this->input->post('tribe');
		$exclude_rkap    = $this->input->post('exclude_rkap');
		$list_id_rkap    = $this->input->post('list_id_rkap');

		$groups = $this->session->userdata('group_id');

		foreach ($groups as $key => $value) {
			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

		$su_budget = in_array("SU Budget", $group_name) ? true : false;

		$get_rkap          = $this->budget->get_all_rkap_from_view($directorat_name, $division_name, $unit_name, $tribe, $exclude_rkap, $list_id_rkap, $su_budget);
		$total_data = $get_rkap['total'];
		$get_rkap_dana_cadangan = $this->budget->get_rkap_dana_cadangan();

		foreach ($get_rkap_dana_cadangan as $key => $value) {
			array_push($get_rkap['data'], $value);
			$total_data++;
		}

		if($total_data){

			$result['status'] = true;

			$nowQ = date("n");
			$year = date("Y");

			if($nowQ  <= 3){
				$quartalNow = 1;
			}
			elseif($nowQ  <= 6){
				$quartalNow = 2;
			}
			elseif($nowQ  <= 9){
				$quartalNow = 3;
			}
			else{
				$quartalNow = 4;
			}

			$quartal = 0;

			foreach($get_rkap['data'] as $row)	{

				if(date("Y", strtotime($row['MONTH'])) == $year && date("n", strtotime($row['MONTH']))  <= 3){
					$quartal = 1;
				}
				elseif(date("Y", strtotime($row['MONTH'])) == $year && date("n", strtotime($row['MONTH']))  <= 6){
					$quartal = 2;
				}
				elseif(date("Y", strtotime($row['MONTH'])) == $year && date("n", strtotime($row['MONTH']))  <= 9){
					$quartal = 3;
				}
				elseif(date("Y", strtotime($row['MONTH'])) == $year && date("n", strtotime($row['MONTH']))  <= 12){
					$quartal = 4;
				}

				$disabled = ($quartal < $quartalNow) ? true : false;

				$rkap_desc = $row['RKAP_DESCRIPTION'];

				$dana_cadangan = (strpos(strtolower($rkap_desc), 'operational loss reserved') !== false) ? '1' : '0';

				$data[] = array(
								"id_rkap_line"  => $row['ID_RKAP_LINE'],
								"proc_type"     => $row['PROC_TYPE'],
								"month"         => date("m", strtotime($row['MONTH'])),
								"quartal"       => $quartal,
								"disabled"      => $disabled,
								"dana_cadangan" => $dana_cadangan,
								"year"          => date("Y", strtotime($row['MONTH'])),
								"rkap_name"     => $row['RKAP_DESCRIPTION']."  &ndash; ".date("M-y", strtotime($row['MONTH']))
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_data_tier(){

		$id_rkap  = $this->input->post('id_rkap');
		$get_tier = $this->crud->read_by_param_specific("BUDGET_FINANCE_STUDY", array("ID_RKAP_LINE" => $id_rkap), "ID_RKAP_LINE, ENTRY_OPTIMIZE", TRUE);

		if($get_tier){

			$result['status'] = true;

			foreach($get_tier as $row) {

				$data[] = array(
								"id_rkap"   => $row['ID_RKAP_LINE'],
								"tier_name" => $row['ENTRY_OPTIMIZE']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function load_data_tier_from_header(){

		$id_rkap  = $this->input->post('id_rkap');
		$get_tier = $this->crud->read_by_param_specific("BUDGET_HEADER", array("ID_RKAP_LINE" => $id_rkap), "ID_RKAP_LINE, ENTRY_OPTIMIZE", TRUE);

		if($get_tier){

			$result['status'] = true;

			foreach($get_tier as $row) {

				$data[] = array(
								"id_rkap"   => $row['ID_RKAP_LINE'],
								"tier_name" => $row['ENTRY_OPTIMIZE']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function load_data_proc_type(){

		// $id_rkap       = $this->input->post('id_rkap');
		// $get_proc_type = $this->crud->read_by_param_specific("BUDGET_HEADER", "", "PROC_TYPE", TRUE);
		$get_proc_type = get_proc_type();

		if($get_proc_type){

			$result['status'] = true;

		/*	$get_proc_type[] = array("PROC_TYPE" => "Sponsorship");
			foreach ($get_proc_type as $key => $v)
			{
			    $sort[$key] = $v['PROC_TYPE'];
			}
			array_multisort($sort, SORT_ASC, $get_proc_type);*/

			foreach($get_proc_type as $value) {

				$data[] = array(
								"proc_type" => $value
							);

			}

			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}


	public function load_data_district(){

		$get_district = get_district();

		if($get_district){

			$result['status'] = true;

		/*	$get_district[] = array("PROC_TYPE" => "Sponsorship");
			foreach ($get_district as $key => $v)
			{
			    $sort[$key] = $v['PROC_TYPE'];
			}
			array_multisort($sort, SORT_ASC, $get_district);*/

			foreach($get_district as $value) {

				$data[] = array(
								"id_district"   => $value['DISTRICT_ID'],
								"district_name" => $value['DISTRICT']
							);

			}

			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}


	public function load_data_submitter(){
		
		$directorat   = $this->input->post('directorat');
		$division     = $this->input->post('division');
		$jabatan_code = $this->input->post('jabatan_code');

		$get_submiter  = $this->budget->get_data_submiter($directorat, $division, $jabatan_code);

		if($get_submiter){

			$result['status'] = true;

			foreach($get_submiter as $row) {

				$data[] = array(
								"id"      => $row['ID_EMPLOYEE'],
								"nama"    => $row['NAMA'],
								"jabatan" => $row['JABATAN']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}

		echo json_encode($result);
	}

	public function load_data_nature()
	{

		$get_all = $this->input->post('get_all');

		if($get_all):
			$fieldToSelect       = array("NATURE, ID_MASTER_COA, DESCRIPTION");
			$get_data            = $this->crud->read_by_param_specific("MASTER_COA", "", $fieldToSelect, true);
			$get_nature['total'] = count($get_data);
			$get_nature['data']  = $get_data;
		else:
			$get_nature = $this->budget->get_nature_by_rkap();
		endif;


		if($get_nature['total'] > 0){

			$result['status'] = true;

			foreach($get_nature['data'] as $row)	{

				$data[] = array(
									"id_coa"      => $row['ID_MASTER_COA'],
									"nature"      => $row['NATURE'],
									"description" => $row['DESCRIPTION'],
									"nature_desc" => $row['NATURE']." - ".$row['DESCRIPTION']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }


    public function load_nature_by_rkap()
	{
		
		$id_rkap  = $this->input->post('id_rkap');
		$category = $this->input->post('category');

		if($category == "edit"){
			$get_nature = $this->budget->get_nature_by_rkap_edit($id_rkap);
		}else{
			$get_nature = $this->budget->get_nature_by_rkap($id_rkap);
		}

		if($get_nature['total'] > 0){

			$result['status'] = true;

			$id_coa_selected = 0;
/*
			if($get_nature['data_sub']){
				$id_coa_selected = $get_nature['data_sub']['ID_MASTER_COA'];
			}*/

			foreach($get_nature['data'] as $row)	{

				$selected = ($row['ID_MASTER_COA'] == $id_coa_selected) ? true : false;

				$data[] = array(
									"id_coa"      => $row['ID_MASTER_COA'],
									"nature"      => $row['NATURE'],
									"nature_desc" => $row['NATURE']." - ".$row['DESCRIPTION'],
									"selected"    => $selected
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

    public function load_category_item()
	{

		$get_category = $this->budget->get_category_item();

		$id_cat_selected = 0;

		if($get_category['total'] > 0){

			$result['status'] = true;
			
			foreach($get_category['data'] as $row)	{

				$data[] = array(
									/*"category_item" => $row['CATEGORY'],
									"nature"        => $row['COA_KONVENTIONAL']*/
									"category_item" => $row['CATEGORY_NAME'],
									"nature"        => $row['CATEGORY_COA']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

    public function load_uom()
	{

		$get_uom = $this->budget->get_uom();

		$id_cat_selected = 0;

		if($get_uom['total'] > 0){

			$result['status'] = true;
			
			foreach($get_uom['data'] as $row)	{

				$data[] = array(
									"uom" => $row['UOM_NAME'],
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }

	public function load_division_by_directorat()
	{
		
		$id_dir_code  = $this->input->post('id_dir_code');
		$get_division = $this->budget->get_division_by_dir_code($id_dir_code);

		if($get_division){

			$result['status'] = true;

			foreach($get_division as $row)	{

				$data[] = array(
									"id_division"   => $row['ID_DIVISION'],
									"division_name" => $row['DIVISION_NAME']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }
    
	public function load_unit_by_division()
	{

		$division = $this->input->post('division');
		$get_unit    = $this->budget->get_unit_by_division($division);

		if($get_unit){

			$result['status'] = true;

			foreach($get_unit as $row)	{

				$data[] = array(
									"id_unit"   => $row['ID_UNIT'],
									"unit_name" => $row['UNIT_NAME']
							);

			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);

    }
    
	public function load_data_budget_summary()
	{

		$this->load->model('feasibility_study_mdl', 'fs');
		$year       = $this->input->post('year');

		$directorat    = "";
		$division      = "";
		$unit          = "";
		$id_directorat = "";
		$id_division   = "";
		$id_unit       = "";

		$binding = check_binding();
		$data_binding = $binding['data_binding'];

		if($binding['binding'] == "directorat"){
			$directorat    = $data_binding['directorat'][0]['DIRECTORAT_NAME'];
			$id_directorat = $data_binding['directorat'][0]['ID_DIR_CODE'];
			if(strtolower($directorat) == "finance" || strtolower($directorat) == "ceo" || strtolower($directorat) == "coo"){
				$directorat    = "";
				$id_directorat = "";
			}
		}
		elseif($binding['binding'] == "division"){
			$directorat    = $data_binding['directorat'][0]['DIRECTORAT_NAME'];
			$id_directorat = $data_binding['directorat'][0]['ID_DIR_CODE'];

			$get_division = $data_binding['division'];

			if(count($get_division) > 1){
				$id_division = array();
				$division    = array();
				foreach ($get_division as $key => $value) {
					$id_division[] = $value['ID_DIVISION'];
					$division[]    = $value['DIVISION_NAME'];
				}
			}else{
				$id_division = $get_division[0]['ID_DIVISION'];
				$division    = $get_division[0]['DIVISION_NAME'];
			}
		}elseif($binding['binding'] == "unit"){
			$directorat    = $data_binding['directorat'][0]['DIRECTORAT_NAME'];
			$id_directorat = $data_binding['directorat'][0]['ID_DIR_CODE'];

			$get_division  = $data_binding['division'];
			$id_division   = $get_division[0]['ID_DIVISION'];
			$division      = $get_division[0]['DIVISION_NAME'];
			
			$get_unit = $data_binding['unit'];
			if(count($get_unit) > 1){
				$id_unit = array();
				$unit    = array();
				foreach ($get_unit as $key => $value) {
					$id_unit[] = $value['ID_UNIT'];
					$unit[]    = $value['UNIT_NAME'];
				}
			}else{
				$id_unit = $get_unit[0]['ID_UNIT'];
				$unit    = $get_unit[0]['UNIT_NAME'];
			}
		}


		$summary_budget =  $this->fs->get_summary_budget($year, $directorat, $division, $unit);
		$total_reloc   = $summary_budget['TOTAL_RELOC_IN']-$summary_budget['TOTAL_RELOC_OUT'];
		$total_rkap    = $summary_budget['TOTAL_RKAP']+$total_reloc;
		$budget_used   = $summary_budget['BUDGET_USED'];
		$budget_remain = $total_rkap - $budget_used;

		$total_fs    =  $this->fs->get_total_justification($year, $id_directorat, $id_division, $id_unit);

		$fs_total    = 0;
		$fs_request  = 0;
		$fs_approved = 0;
		$fs_used     = 0;

		foreach ($total_fs as $key => $value) {
			if(strtolower($value['STATUS']) == "approved"){
				$fs_approved += $value['TOTAL'];
			}
			if(strtolower($value['STATUS']) == "request_approve"){
				$fs_request += $value['TOTAL'];
			}

			if(strtolower($value['STATUS']) == "fs used"){
				$fs_used += $value['TOTAL'];
			}

			$fs_total += $value['TOTAL'];
		}

		$result['fs_total']      = $fs_total;
		$result['fs_request']    = $fs_request;
		$result['fs_used']       = $fs_used;
		$result['fs_approved']   = $fs_approved;
		$result['total_rkap']    = number_format($total_rkap,0,',','.');
		$result['budget_used']   = number_format($budget_used,0,',','.');
		$result['budget_remain'] = number_format($budget_remain,0,',','.');
	

		echo json_encode($result);
    }


    public function load_all_bank(){

    	$banks  = get_all_bank();

		if($banks){

			$result['status'] = true;

			foreach ($banks as $key => $bank) {
				$data[] = $bank['BANK_NAME'];
			}
			$result['data'] = $data;
		}
		else{
			$result['status'] = false;
		}


		echo json_encode($result);
    }


}

/* End of file Budget_api_ctl.php */
/* Location: ./application/controllers/api/Budget_api_ctl.php */