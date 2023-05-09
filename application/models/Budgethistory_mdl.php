<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budgethistory_mdl extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table_budget_history = "HISTORY_BUDGET_HEADER";
		$this->table1 = "MASTER_UPLOAD_RKAP";
		$this->table2 = "BUDGET_HEADER";
		$this->table3 = "master_upload_rkap";
		
	}


	function get_datatable_header($year, $direktorat="", $divisi="", $unit="", $entry="")

	{



		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";

		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";



		$search    = "";

		if($keywords != ""){
			$fieldToSearch = array("PARENT_ACCOUNT","RKAP_DESCRIPTION","DETAIL_ACTIVITY","DATE_FORMAT(MONTH,'%b-%y')");
			$search = query_datatable_search($keywords, $fieldToSearch);
		}

		$where = "";
		$where_entry = "";

		if($direktorat != ""){
			$where .= " and DIRECTORAT = '".str_replace("|AND|", "&", $direktorat)."'";
		}
		if($divisi != ""){
			$where .= " and DIVISION = '".str_replace("|AND|", "&", $divisi)."'";
		}
		if($unit != ""){
			$where .= " and UNIT = '".str_replace("|AND|", "&", $unit)."'";
		}

		if($entry !='0'){
			$where_entry = " and ENTRY_OPTIMIZE = '$entry' ";
		}else{
			$where_entry = " ";
		}


		$mainQuery = "SELECT *
		FROM $this->table_budget_history
		where 1=1
		and CONVERT(HISTORY_DATE, DATE) = ? $where $where_entry $search order by DIRECTORAT, DIVISION, UNIT";

						// log_to_file($mainQuery);


		$queryData = query_datatable($mainQuery);



		$total = $this->db->query($mainQuery, $year)->num_rows();

		$data  = $this->db->query($queryData, $year)->result_array();

		

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}



	function get_directorate()

	{

		$queryExec	        = " SELECT sum(ifnull(bh.NOMINAL,0)) NOMINAL,
		sum(ifnull(bh.FA_FS,0)) FA_FS,
		sum(ifnull(bh.FA_BUFFER,0))FA_BUFFER,
		md.DIRECTORAT_NAME, 
		md.ID_DIR_CODE 
		from $this->table_budget_history bh right join MASTER_DIRECTORAT md on bh.DIRECTORAT = md.DIRECTORAT_NAME group by md.DIRECTORAT_NAME
		";

		$query 		        = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;		

	}



	function get_division()

	{
		$id_dir_code  = $this->input->post('id_dir');
		$where = "";

		if($id_dir_code):
			$where = " WHERE md.ID_DIR_CODE = $id_dir_code";
		endif;

		$queryExec	        = " SELECT sum(ifnull(bh.NOMINAL,0)) NOMINAL,
		sum(ifnull(bh.FA_FS,0)) FA_FS,
		sum(ifnull(bh.FA_BUFFER,0))FA_BUFFER,
		md.DIVISION_NAME,
		md.ID_DIVISION 
		from $this->table_budget_history bh right join MASTER_DIVISION md on bh.DIVISION = md.DIVISION_NAME ". $where ." group by md.DIVISION_NAME";

		$query 		        = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;		

	}


	function get_unit()

	{

		$id_dir_code  = $this->input->post('id_dir');
		$id_division  = $this->input->post('id_div');
		$where = "";

		if($id_dir_code):
			$where .= " WHERE mu.ID_DIR_CODE = $id_dir_code";
		endif;
		
		if($id_division):
			$where .= " AND mu.ID_DIVISION = $id_division";
		endif;

		$queryExec	        = " SELECT sum(ifnull(bh.NOMINAL,0)) NOMINAL,
		sum(ifnull(bh.FA_FS,0)) FA_FS,
		sum(ifnull(bh.FA_BUFFER,0))FA_BUFFER,
		mu.UNIT_NAME,
		mu.ID_UNIT 
		from $this->table_budget_history bh right join MASTER_UNIT mu on bh.UNIT = mu.UNIT_NAME
		".$where."
		group by mu.UNIT_NAME";

		$query 		        = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;		

	}

	function get_entry($directorat = false)
	{

		$where = "";
		if($directorat){
			$where = " AND DIRECTORAT = ?";
		}

		$queryExec	        = "SELECT DISTINCT ENTRY_OPTIMIZE
									from $this->table_budget_history
									where ENTRY_OPTIMIZE is not null
									$where
									group by ENTRY_OPTIMIZE";

		$query 		        = $this->db->query($queryExec, $directorat);

		$result['query']	= $query;

		return $result;		

	}

	function get_nominal($category, $year){


		$id_dir_code  = $this->input->post('id_dir');
		$id_division  = $this->input->post('id_div');
		$id_unit      = $this->input->post('id_unit');
		$id_entry     = $this->input->post('id_entry');


		$join  = "";
		$where = "";
		if($year != 0){
			$where .= " and CONVERT(HISTORY_DATE, DATE) = '$year'";
		}
		//$where .= " and ENTRY_OPTIMIZE = '$id_entry' ";

		if($id_entry){
			$where .= " and ENTRY_OPTIMIZE = '$id_entry' ";
		}elseif($id_entry == 0){
			$where .= " ";
		}

		if($category == "directorat"){
			$join  .= " right join MASTER_DIRECTORAT md on bh.DIRECTORAT = md.DIRECTORAT_NAME";
			$where .= " and md.ID_DIR_CODE = $id_dir_code";
		}
		elseif($category == "division"){
			$join  .= " right join MASTER_DIVISION md on bh.DIVISION = md.DIVISION_NAME";
			$where .= " and md.ID_DIR_CODE = $id_dir_code
			and md.ID_DIVISION = $id_division";
		}
		elseif($category == "unit"){
			$join  .= " right join MASTER_UNIT mu on bh.UNIT = mu.UNIT_NAME";
			$where .= " and mu.ID_DIR_CODE = $id_dir_code
			and mu.ID_DIVISION = $id_division
			and mu.ID_UNIT = $id_unit";
		}
		elseif($category == "entry"){
			$where .= " and ENTRY_OPTIMIZE = '$id_entry' ";
		}
		else{
			$join  = "";
			$where = "";
		}

		$queryExec	        = " SELECT sum(ifnull(bh.NOMINAL,0)) NOMINAL,
		sum(ifnull(bh.FS,0)) FS,
		sum(ifnull(bh.FA_RKAP,0)) FA_RKAP,
		sum(ifnull(bh.FA_FS,0)) FA_FS,
		sum(ifnull(bh.FA_BUFFER,0)) FA_BUFFER  
		from $this->table_budget_history bh
		$join
		where 1=1								
		$where
		";

		// echo $queryExec; die();

		$query 		        = $this->db->query($queryExec);

		return $query;
	}

	function get_nominal_bod($year=false, $id_entry=false, $id_dir_code=false){

		$whereArr = array();
		$where    = "";
		$join     = "";

		if($year){
			$where .= " AND CONVERT(HISTORY_DATE, DATE) = ?";
			$whereArr[] = $year;
		}
		
		if($id_entry){
			$where .= " AND ENTRY_OPTIMIZE = ?";
			$whereArr[] = $id_entry;
		}

		if($id_dir_code){
			$join  .= " right join MASTER_DIRECTORAT md on bh.DIRECTORAT = md.DIRECTORAT_NAME";
			$where .= " and md.ID_DIR_CODE = ?";
			$whereArr[] = $id_dir_code;
		}

		$queryExec	        = " SELECT sum(ifnull(bh.NOMINAL,0)) NOMINAL,
								sum(ifnull(bh.FS,0)) FS,
								sum(ifnull(bh.FA_RKAP,0)) FA_RKAP,
								sum(ifnull(bh.FA_FS,0)) FA_FS,
								sum(ifnull(bh.FA_BUFFER,0)) FA_BUFFER  
								from $this->table_budget_history bh
								$join
								where 1=1 $where ";
		$query = $this->db->query($queryExec, $whereArr);

		return $query;
	}


	function get_cetak_header($year, $direktorat, $divisi, $unit, $entry){

		$where = "";

		$whereVal[] = $year;

		if($direktorat != "" && $direktorat != "undefined"){
			$whereVal[] = str_replace("|AND|", "&", $direktorat);
			$where .= " and DIRECTORAT = ?";
		}
		if($divisi != "" && $divisi != "undefined"){
			$whereVal[] = str_replace("|AND|", "&", $divisi);
			$where .= " and DIVISION = ?";
		}
		if($unit != "" && $unit != "undefined"){
			$whereVal[] = str_replace("|AND|", "&", $unit);
			$where .= " and UNIT = ?";
		}
		if($entry != "" && $entry != "undefined"){
			$whereVal[] = $entry;
			$where .= " and ENTRY_OPTIMIZE = ?";
		}

		// print_r($whereVal);die;

		$queryExec = " SELECT * from $this->table_budget_history where CONVERT(HISTORY_DATE, DATE) = ? $where order by DIRECTORAT, DIVISION, UNIT";

		$query     = $this->db->query($queryExec, $whereVal);

		return $query;
	}
	

}

/* End of file Budgethistory_mdl.php */
/* Location: ./application/models/Budgethistory_mdl.php */