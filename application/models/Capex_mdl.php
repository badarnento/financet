<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Capex_mdl extends CI_Model {



    public function __construct()

    {

        parent::__construct();

		    

		$this->table1 = "MASTER_UPLOAD_RKAP";

		$this->table2 = "BUDGET_HEADER";

		$this->table3 = "master_upload_rkap";



    }



	function get_all_capex(){



		$this->db->select('*');

		$this->db->from($this->table);

		$this->db->order_by("id", "desc"); 



	    $query = $this->db->get();



	    if ( $query->num_rows() > 0 )

	    {

	        return $query->result_array();

	    }

	}



	function get_datatable($year)

	{



		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";

		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";



		$where    = "";

		/*if($keywords != ""){

			$q = strtolower($keywords);

			$where = " and (PARENT_ACCOUNT like '%".$q."%') or (RKAP_DESCRIPTION like '%".$q."%') or (DETAIL_ACTIVITY like '%".$q."%')";

		}*/

		if($keywords != ""){
			$fieldToSearch = array("PARENT_ACCOUNT","RKAP_DESCRIPTION","DETAIL_ACTIVITY");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}



		$mainQuery = "SELECT *

						FROM $this->table1

						where 1=1

						and EXTRACT(YEAR FROM MONTH) = ? $where order by DIRECTORAT, DIVISION, UNIT";



		$queryData = query_datatable($mainQuery);



		$total = $this->db->query($mainQuery, $year)->num_rows();

		$data  = $this->db->query($queryData, $year)->result_array();

		

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}

	function get_exist_year_master(){

		$query = $this->db->query('SELECT DISTINCT EXTRACT(YEAR FROM MONTH) as TAHUN FROM MASTER_UPLOAD_RKAP');

		return $query->result_array();

	}

	function get_exist_year_rkap(){

		$query = $this->db->query('SELECT DISTINCT EXTRACT(YEAR FROM MONTH) as TAHUN FROM BUDGET_HEADER');

		return $query->result_array();

	}


	public function insertimport($data)
    {

        $this->db->insert_batch('MASTER_UPLOAD_RKAP', $data);

        return $this->db->insert_id();

    }

	public function call_procedure($req_id="")

    {
    	$sql = "CALL RKPDETAIL('$req_id')";

        $this->db->query($sql);

        return true;

    }



    function get_datatable_header($year, $direktorat="", $divisi="", $unit="", $entry="")

	{



		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";

		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";



		$search    = "";

		/*if($keywords != ""){

			$q = strtolower($keywords);

			$search = " and (PARENT_ACCOUNT like '%".$q."%') or (RKAP_DESCRIPTION like '%".$q."%') or (DETAIL_ACTIVITY like '%".$q."%') or (DATE_FORMAT(MONTH,'%b-%y') like '%".$q."%')";

		}*/

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
						FROM $this->table2
						where 1=1
						and EXTRACT(YEAR FROM MONTH) = ? $where $where_entry $search order by DIRECTORAT, DIVISION, UNIT";

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
								from BUDGET_HEADER bh right join MASTER_DIRECTORAT md on bh.DIRECTORAT = md.DIRECTORAT_NAME group by md.DIRECTORAT_NAME
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
								from BUDGET_HEADER bh left join MASTER_DIVISION md on bh.DIVISION = md.DIVISION_NAME ". $where ." group by md.DIVISION_NAME";

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
								from BUDGET_HEADER bh left join MASTER_UNIT mu on bh.UNIT = mu.UNIT_NAME
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
									from $this->table2
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
		$whereVal = array();
		if($year != 0){
			$where = " and EXTRACT(YEAR FROM MONTH) = ?";
			$whereVal[] = $year;
		}

		if($id_entry){
			$where .= " and ENTRY_OPTIMIZE = ? ";
			$whereVal[] = $id_entry;
		}

		if($category == "directorat"){
			/*$join  .= " right join MASTER_DIRECTORAT md on bh.DIRECTORAT = md.DIRECTORAT_NAME";
			$where .= " and md.ID_DIR_CODE = $id_dir_code";*/

			$join  = " left join MASTER_DIRECTORAT mdir on bh.DIRECTORAT = mdir.DIRECTORAT_NAME";
			$where .= " and mdir.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}
		elseif($category == "division"){
			$join  = " left join MASTER_DIRECTORAT mdir on bh.DIRECTORAT = mdir.DIRECTORAT_NAME
						 left join MASTER_DIVISION md on bh.DIVISION = md.DIVISION_NAME";
			$where .= " and mdir.ID_DIR_CODE = ?
						and md.ID_DIVISION = ?";

			$whereVal[] = $id_dir_code;
			$whereVal[] = $id_division;
		}
		elseif($category == "unit"){
			$join  = " left join MASTER_DIRECTORAT mdir on bh.DIRECTORAT = mdir.DIRECTORAT_NAME
						 left join MASTER_DIVISION md on bh.DIVISION = md.DIVISION_NAME
						 left join MASTER_UNIT mu on bh.UNIT = mu.UNIT_NAME";
			$where .= " and mdir.ID_DIR_CODE = ?
						and md.ID_DIVISION = ?
						and mu.ID_UNIT = ?";
			$whereVal[] = $id_dir_code;
			$whereVal[] = $id_division;
			$whereVal[] = $id_unit;
		}
		/*elseif($category == "entry"){
			$where .= " and ENTRY_OPTIMIZE = ? ";
			$whereVal[] = $id_entry;
		}*/
		else{
			$join  = "";
			$where = "";
		}


		$queryExec = "SELECT sum(ifnull(bh.NOMINAL,0)) NOMINAL,
								sum(ifnull(bh.FS,0)) FS,
								sum(ifnull(bh.FA_RKAP,0)) FA_RKAP,
								sum(ifnull(bh.FA_FS,0)) FA_FS,
								sum(ifnull(bh.FA_BUFFER,0)) FA_BUFFER  
								from BUDGET_HEADER bh
								$join
						where 1=1
						$where
						";
		$query 		        = $this->db->query($queryExec, $whereVal);

		return $query;
	}

	function get_nominal_bod($year=false, $id_entry=false, $id_dir_code=false){

		$whereArr = array();
		$where    = "";
		$join     = "";

		if($year){
			$where .= " AND EXTRACT(YEAR FROM MONTH) = ?";
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
								from $this->table2 bh
								$join
								where 1=1								
								$where
								";
								
		$query = $this->db->query($queryExec, $whereArr);

		return $query;
	}

	function get_cetak($year){

		$queryExec = " SELECT * from MASTER_UPLOAD_RKAP where extract(year from month) = ? order by DIRECTORAT, DIVISION, UNIT";
		$query     = $this->db->query($queryExec, $year);

		return $query;
	}

	function get_cetak_header($year, $direktorat, $division, $unit, $entry){

		$where = "";

		$whereVal[] = $year;

		if($direktorat != "" && $direktorat != "undefined"){
			$whereVal[] = str_replace("|AND|", "&", $direktorat);
			$where .= " and DIRECTORAT = ?";
		}
		if($division != "" && $division != "undefined"){
			if(is_array($division)){
				$division_where = "";
				foreach ($division as $key => $value) {
					if($key == 0){
						$division_where .= "DIVISION = ?";
					}else{
						$division_where .= " OR DIVISION = ?";
					}
					$whereVal[] = str_replace("|AND|", "&", $value);
				}
				$where .= " AND ($division_where)";
			}else{
				$where .= " AND DIVISION = ?";
				$whereVal[] = str_replace("|AND|", "&", $division);
			}
		}
		if($unit != "" && $unit != "undefined"){
			if(is_array($unit)){
				$unit_where = "";
				foreach ($unit as $key => $value) {
					if($key == 0){
						$unit_where .= "UNIT = ?";
					}else{
						$unit_where .= " OR UNIT = ?";
					}
					$whereVal[] = str_replace("|AND|", "&", $value);
				}
				$where .= " AND ($unit_where)";
			}else{
				$where .= " AND UNIT = ?";
				$whereVal[] = str_replace("|AND|", "&", $unit);
			}
		}
		if($entry != "" && $entry != "undefined"){
			$whereVal[] = $entry;
			$where .= " and ENTRY_OPTIMIZE = ?";
		}

		// print_r($whereVal);die;

		$queryExec = " SELECT * from BUDGET_HEADER where extract(year from month) = ? $where order by DIRECTORAT, DIVISION, UNIT";

		$query     = $this->db->query($queryExec, $whereVal);

		return $query;
	}



}



/* End of file Capex_mdl.php */

/* Location: ./application/models/Capex_mdl.php */