<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Budgetrelocation_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->table_rkap_line = "RKAP_LINE";
		$this->table_budget_header = "BUDGET_HEADER";
		$this->table_budget_reloc = "BUDGET_RELOC";
		$this->table_budget_study = "BUDGET_FINANCE_STUDY";

	}


	function get_budget_relocation_datatable($reloc_date_from,$reloc_date_to)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (br.RELOC_DATE like '%".$q."%') 
			or (bh.DIRECTORAT like '%".$q."%') 
			or (bh.DIVISION like '%".$q."%') 
			or (bh.UNIT like '%".$q."%') 
			or (bh.RKAP_DESCRIPTION like '%".$q."%') 
			or (bh.TRIBE_USECASE like '%".$q."%') 
			or (tbh.DIRECTORAT like '%".$q."%') 
			or (tbh.DIVISION like '%".$q."%') 
			or (tbh.UNIT like '%".$q."%') 
			or (tbh.RKAP_DESCRIPTION like '%".$q."%')  
			or (bh.TRIBE_USECASE like '%".$q."%') 
			or (br.AMOUNT_RELOC like '%".$q."%')";
		}

		$mainQuery = "SELECT 

		br.RELOC_DATE,
		br.ID_RKAP_SOURCE,
		br.ID_RKAP_DEST,
		br.AMOUNT_RELOC,
		bh.DIRECTORAT as DIRECTORATE_SOURCE,
		bh.DIVISION as DIVISION_SOURCE,
		bh.UNIT as UNIT_SOURCE,
		bh.RKAP_DESCRIPTION as RKAP_NAME_SOURCE,
		bh.TRIBE_USECASE as TRIBE_USECASE_SOURCE,
		tbh.DIRECTORAT as DIRECTORATE_TARGET,
		tbh.DIVISION as DIVISION_TARGET,
		tbh.UNIT as UNIT_TARGET,
		tbh.RKAP_DESCRIPTION as RKAP_NAME_TARGET,
		tbh.TRIBE_USECASE as TRIBE_USECASE_TARGET

		FROM $this->table_budget_reloc br
		INNER JOIN $this->table_budget_header bh on br.ID_RKAP_SOURCE = bh.ID_RKAP_LINE 
		INNER JOIN $this->table_budget_header tbh on br.ID_RKAP_DEST = tbh.ID_RKAP_LINE 

		where 1=1

		and CONVERT(RELOC_DATE, DATE) BETWEEN ? and ?
		$where";

		$queryData = query_datatable($mainQuery);

		// echo $mainQuery; die();

		$total = $this->db->query($mainQuery, array($reloc_date_from, $reloc_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($reloc_date_from, $reloc_date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_download_budget_reloc($date_from,$date_to)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$reloc_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$reloc_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT 

		br.RELOC_DATE,
		br.ID_RKAP_SOURCE,
		br.ID_RKAP_DEST,
		br.AMOUNT_RELOC,
		bh.DIRECTORAT as DIRECTORATE_SOURCE,
		bh.DIVISION as DIVISION_SOURCE,
		bh.UNIT as UNIT_SOURCE,
		bh.RKAP_DESCRIPTION as RKAP_NAME_SOURCE,
		bh.TRIBE_USECASE as TRIBE_USECASE_SOURCE,
		tbh.DIRECTORAT as DIRECTORATE_TARGET,
		tbh.DIVISION as DIVISION_TARGET,
		tbh.UNIT as UNIT_TARGET,
		tbh.RKAP_DESCRIPTION as RKAP_NAME_TARGET,
		tbh.TRIBE_USECASE as TRIBE_USECASE_TARGET

		FROM $this->table_budget_reloc br
		INNER JOIN $this->table_budget_header bh on br.ID_RKAP_SOURCE = bh.ID_RKAP_LINE
		INNER JOIN $this->table_budget_header tbh on br.ID_RKAP_DEST = tbh.ID_RKAP_LINE

		where 1=1

		and CONVERT(RELOC_DATE, DATE) BETWEEN ? and ?";

		$query     = $this->db->query($queryExec,array($reloc_date_from, $reloc_date_to));

		return $query;
	}


	// function get_master_rkap_name_without_details(){

	// 	$param_year = $this->input->post('param_year');
	// 	$param_directorat  =  $this->input->post('param_directorat_names');
	// 	$param_division  =  $this->input->post('param_division_names');
	// 	$param_unit_name  =  $this->input->post('param_unit_names');
	// 	$param_tribe_usecase =  $this->input->post('param_tribe_usecases');
	// 	$param_ga  =  $this->input->post('param_gas');

	// 	$where = "";

	// 	if($param_year)
	// 	{
	// 		$where .= " and EXTRACT(YEAR FROM bh.MONTH) = '$param_year'";
	// 	}

	// 	if($param_directorat)
	// 	{
	// 		$where .= " and dr.DIRECTORAT_NAME = '$param_directorat'";
	// 	}

	// 	if($param_division)
	// 	{
	// 		$where .= " and dv.DIVISION_NAME = '$param_division'";
	// 	}

	// 	if($param_unit_name)
	// 	{
	// 		$where .= " and un.UNIT_NAME = '$param_unit_name'";
	// 	}

	// 	if($param_tribe_usecase)
	// 	{
	// 		$where .= " and bh.TRIBE_USECASE = '$param_tribe_usecase'";
	// 	}

	// 	if($param_ga)
	// 	{
	// 		$where .= " and bh.PARENT_ACCOUNT = '$param_ga' ";
	// 	}

	// 	$queryExec	        = " SELECT DISTINCT 
	// 	bh.RKAP_DESCRIPTION
	// 	from $this->table_budget_study bh 
	// 	INNER JOIN MASTER_UNIT un on bh.UNIT = un.UNIT_NAME
	// 	INNER JOIN MASTER_DIVISION dv on bh.DIVISION = dv.DIVISION_NAME
	// 	INNER JOIN MASTER_DIRECTORAT dr on bh.DIRECTORAT = dr.DIRECTORAT_NAME
	// 	where 1=1
	// 	".$where."";

	// 	// echo $queryExec; die();

	// 	$query 		        = $this->db->query($queryExec);

	// 	$result['query']	= $query;

	// 	return $result;
	// }

	function get_master_rkap_name_with_details(){

		$flag = 'N';
		$queryflag = "SELECT DISTINCT FLAG from MASTER_SETTING where CODE_SETTING = 'RELOC' and ID_MENU = '27'";

		$execflag = $this->db->query($queryflag);

		if ($execflag)
		{

			foreach($execflag->result_array() as $row)	
			{
				$flag = $row['FLAG'];
			}	

		}


		$param_year = $this->input->post('param_year');
		$param_directorat  =  $this->input->post('param_directorat_names');
		$param_division  =  $this->input->post('param_division_names');
		$param_unit_name  =  $this->input->post('param_unit_names');
		$param_tribe_usecase =  $this->input->post('param_tribe_usecases');
		$param_ga  =  $this->input->post('param_gas');
		$param_proc_type  =  $this->input->post('param_proc_types');
		$param_fs_name = $this->input->post('param_fs_names');
		$param_rkap_name = $this->input->post('param_rkap_names'); //penambahan rkap tidak boleh sama
		$param_rkap_name_description = $this->input->post('param_rkap_name_descriptions'); //penambahan rkap description sama

		$where = "";

		if($param_rkap_name_description) //penambahan rkap description sama
		{
			$where .= " and bh.RKAP_DESCRIPTION = '$param_rkap_name_description'";
		}

		if($param_rkap_name) //penambahan rkap tidak boleh sama
		{
			$where .= " and bh.ID_RKAP_LINE != '$param_rkap_name'";
		}

		if($param_fs_name)
		{
			$where .= " and bh.ID_FS = '$param_fs_name'";
		}

		if($param_year)
		{
			$where .= " and EXTRACT(YEAR FROM bh.MONTH) = '$param_year'";
		}

		if($param_directorat)
		{
			$where .= " and dr.DIRECTORAT_NAME = '$param_directorat'";
		}

		if($param_division)
		{
			$where .= " and dv.DIVISION_NAME = '$param_division'";
		}

		if($param_unit_name)
		{
			$where .= " and un.UNIT_NAME = '$param_unit_name'";
		}

		if($param_tribe_usecase)
		{
			$where .= " and bh.TRIBE_USECASE = '$param_tribe_usecase'";
		}

		if($flag == 'Y')
		{
			if($param_ga)
			{
				$where .= " and bh.PARENT_ACCOUNT = '$param_ga' ";
			}
		}

		if($param_proc_type)
		{
			$where .= " and bh.PROC_TYPE = '$param_proc_type' ";
		}

		$month = date("n", time());
		if($month > 7 && $month < 10){
			// $where .=" AND EXTRACT(MONTH FROM bh.MONTH) IN ('08','09')";
		}
		
		$queryExec	        = " SELECT DISTINCT
		bh.MONTH,
		bh.PARENT_ACCOUNT,
		bh.PROC_TYPE,
		bh.ID_RKAP_LINE,
		bh.RKAP_DESCRIPTION
		from $this->table_budget_header bh 
		INNER JOIN MASTER_UNIT un ON
		bh.UNIT = un.UNIT_NAME
		INNER JOIN MASTER_DIVISION dv ON
		bh.DIVISION = dv.DIVISION_NAME
		INNER JOIN MASTER_DIRECTORAT dr ON
		bh.DIRECTORAT = dr.DIRECTORAT_NAME
		where 1=1
		".$where." ";

		$query 		        = $this->db->query($queryExec);

		// echo $queryExec; die();

		$result['query']	= $query;

		return $result;
	}

	function get_master_program_id(){

		$param_year = $this->input->post('param_year');
		$param_directorat  =  $this->input->post('param_directorat_names');
		$param_division  =  $this->input->post('param_division_names');
		$param_unit_name  =  $this->input->post('param_unit_names');
		$param_tribe_usecase =  $this->input->post('param_tribe_usecases');
		$param_ga  =  $this->input->post('param_gas');
		$param_rkap_name =  $this->input->post('param_rkap_names');

		$where = "";

		if($param_year)
		{
			$where .= " and EXTRACT(YEAR FROM bh.MONTH) = '$param_year'";
		}

		if($param_directorat)
		{
			$where .= " and dr.DIRECTORAT_NAME = '$param_directorat'";
		}

		if($param_division)
		{
			$where .= " and dv.DIVISION_NAME = '$param_division'";
		}

		if($param_unit_name)
		{
			$where .= " and un.UNIT_NAME = '$param_unit_name'";
		}

		if($param_tribe_usecase)
		{
			$where .= " and bh.TRIBE_USECASE = '$param_tribe_usecase'";
		}

		if($param_ga)
		{
			$where .= " and bh.PARENT_ACCOUNT = '$param_ga' ";
		}

		if($param_rkap_name)
		{
			$where .= " and bh.ID_RKAP_LINE = '$param_rkap_name' ";
		}
		
		$queryExec	        = " SELECT DISTINCT
		bh.ENTRY_OPTIMIZE
		from $this->table_budget_header bh 
		INNER JOIN MASTER_UNIT un ON
		bh.UNIT = un.UNIT_NAME
		INNER JOIN MASTER_DIVISION dv ON
		bh.DIVISION = dv.DIVISION_NAME
		INNER JOIN MASTER_DIRECTORAT dr ON
		bh.DIRECTORAT = dr.DIRECTORAT_NAME
		where 1=1
		".$where." ";

		$query 		        = $this->db->query($queryExec);

		// echo $queryExec; die();

		$result['query']	= $query;

		return $result;
	}

	function get_master_tribe_usecase(){

		$param_year = $this->input->post('param_year');
		$param_directorat  =  $this->input->post('param_directorat_names');
		$param_division  =  $this->input->post('param_division_names');
		$param_unit_name  =  $this->input->post('param_unit_names');

		$where = "";
		$whereArr = array();

		IF($param_directorat){
			$where .= " AND DIRECTORAT_NAME = ?";
			$whereArr[] = $param_directorat;
		}

		IF($param_division){
			$where .= " AND DIVISION_NAME = ?";
			$whereArr[] = $param_division;
		}


		IF($param_unit_name){
			$where .= " AND UNIT_NAME = ?";
			$whereArr[] = $param_unit_name;
		}


		IF($param_year){
			$where .= " AND EXTRACT(YEAR FROM MONTH) = ?";
			$whereArr[] = $param_year;
		}

		
		$queryExec	        = " SELECT  DISTINCT TRIBE_USECASE from
		( SELECT
		bh.TRIBE_USECASE,
		bh.MONTH,
		un.UNIT_NAME,
		un.ID_UNIT,
		dv.DIVISION_NAME,
		dv.ID_DIVISION ,
		dr.DIRECTORAT_NAME,
		dr.ID_DIR_CODE  
		from $this->table_budget_header bh 
		INNER JOIN MASTER_UNIT un on bh.UNIT = un.UNIT_NAME
		INNER JOIN MASTER_DIVISION dv on bh.DIVISION = dv.DIVISION_NAME
		INNER JOIN MASTER_DIRECTORAT dr on bh.DIRECTORAT = dr.DIRECTORAT_NAME
		".$where." ) as TRIBE";

		//echo $queryExec; die();

		$query 		        = $this->db->query($queryExec,$whereArr);

		$result['query']	= $query;

		return $result;
	}

	function get_master_fs_name(){

		$param_year = $this->input->post('param_year');
		$param_directorat  =  $this->input->post('param_directorat_names');
		$param_division  =  $this->input->post('param_division_names');
		$param_unit_name  =  $this->input->post('param_unit_names');
		$param_rkap_name =  $this->input->post('param_rkap_names');
		$param_program_id =  $this->input->post('param_program_ids');
		$param_tribe_usecase =  $this->input->post('param_tribe_usecases');
		$where = "";

		// echo $param_rkap_name; die();
		// echo $param_redis; die();

		if($param_year)
		{
			$where .= " and EXTRACT(YEAR FROM bh.MONTH) = '$param_year'";
		}

		if($param_directorat)
		{
			$where .= " and dr.DIRECTORAT_NAME = '$param_directorat'";
		}

		if($param_division)
		{
			$where .= " and dv.DIVISION_NAME = '$param_division'";
		}

		if($param_unit_name)
		{
			$where .= " and un.UNIT_NAME = '$param_unit_name'";
		}

		if($param_rkap_name)
		{
			$where .= " and bh.ID_RKAP_LINE = '$param_rkap_name'";
		}

		if($param_tribe_usecase)
		{
			$where .= " and bh.TRIBE_USECASE = '$param_tribe_usecase'";
		}

		if($param_program_id)
		{
			$where .= " and bh.ENTRY_OPTIMIZE = '$param_program_id'";
		}
		
		$queryExec	        = " SELECT DISTINCT
		bh.FS_NUMBER,
		bh.ID_FS,
		bh.ID_RKAP_LINE
		from $this->table_budget_study bh 
		INNER JOIN MASTER_UNIT un on bh.UNIT = un.UNIT_NAME
		INNER JOIN MASTER_DIVISION dv on bh.DIVISION = dv.DIVISION_NAME
		INNER JOIN MASTER_DIRECTORAT dr on bh.DIRECTORAT = dr.DIRECTORAT_NAME
		INNER JOIN FS_BUDGET fb on bh.ID_FS = fb.ID_FS
		where 1=1

		".$where." AND ( fb.STATUS <> 'canceled' OR fb.STATUS <> 'rejected' ) AND FA_FS > 0";

		// echo $queryExec; die();

		$query 		        = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;
	}

	function get_fund_availables(){

		$param_directoratzz = $this->input->post('param_directorat_namez');
		$param_divisionzz = $this->input->post('param_division_namez');
		$param_unitzz = $this->input->post('param_unit_namez');
		$param_yearzz = $this->input->post('param_yearz');
		$param_tribe_usecasezz =  $this->input->post('param_tribe_usecasez');
		$param_rkap_namezz = $this->input->post('param_rkap_namez');
		$param_program_idzz = $this->input->post('param_program_idz');
		$param_fs_namezz = $this->input->post('param_fs_namez');
		
		$where = "";


		if($param_directoratzz)
		{
			$where .= " and dr.DIRECTORAT_NAME = '$param_directoratzz'";
		}

		if($param_divisionzz)
		{
			$where .= " and dv.DIVISION_NAME = '$param_divisionzz'";
		}

		if($param_unitzz)
		{
			$where .= " and un.UNIT_NAME = '$param_unitzz'";
		}

		if($param_yearzz)
		{
			$where .= " and EXTRACT(YEAR FROM bh.MONTH) = '$param_yearzz'";
		}

		if($param_tribe_usecasezz)
		{
			$where .= " and bh.TRIBE_USECASE = '$param_tribe_usecasezz'";
		}

		if($param_rkap_namezz)
		{
			$where .= " and bh.ID_RKAP_LINE = '$param_rkap_namezz'";

		}

		if($param_program_idzz)
		{
			$where .= " and bh.ENTRY_OPTIMIZE = '$param_program_idzz'";

		}

		if($param_fs_namezz)
		{
			$where .= " and bh.ID_FS ='$param_fs_namezz'";
		}
		
		$queryExec	        = " SELECT DISTINCT
		ifnull(bh.FA_RKAP,0) as FA_FS
		from $this->table_budget_header bh
		INNER JOIN MASTER_UNIT un on bh.UNIT = un.UNIT_NAME
		INNER JOIN MASTER_DIVISION dv on bh.DIVISION = dv.DIVISION_NAME
		INNER JOIN MASTER_DIRECTORAT dr on bh.DIRECTORAT = dr.DIRECTORAT_NAME
		where 1 = 1
		".$where."";

		// echo $queryExec; die();

		$query  = $this->db->query($queryExec);

		return $query;
	}

	function get_budget_exist_year(){

		$queryExec = "SELECT DISTINCT EXTRACT(YEAR FROM MONTH) as TAHUN FROM $this->table_budget_header";

		$query = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;

	}

	function save_data_budget_reloc($data)
	{
		$query = $this->db->insert($this->table_budget_reloc, $data);

		return true;
	}


}



/* End of file Budgetrelocation_mdl.php */

/* Location: ./application/models/Budgetrelocation_mdl.php */