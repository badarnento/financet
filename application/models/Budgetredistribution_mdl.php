<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Budgetredistribution_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->table_rkap_line = "RKAP_LINE";
		$this->table_budget_header = "BUDGET_HEADER";
		$this->table_budget_redis = "BUDGET_REDIS";
		$this->table_fs_budget = "FS_BUDGET";
		$this->table_budget_study = "BUDGET_FINANCE_STUDY";

	}

	function get_budget_exist_year(){

		$queryExec = "SELECT DISTINCT EXTRACT(YEAR FROM MONTH) as TAHUN FROM $this->table_budget_header";

		$query = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;

	}

	function get_budget_redistribution_datatable($redis_date_from, $redis_date_to)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (br.REDIS_DATE like '%".$q."%') 
			or (bh.DIRECTORAT like '%".$q."%') 
			or (bh.DIVISION like '%".$q."%') 
			or (bh.UNIT like '%".$q."%') 
			or (bh.TRIBE_USECASE like '%".$q."%') 
			or (bh.RKAP_DESCRIPTION like '%".$q."%') 
			or (br.FUND_AV like '%".$q."%')";
		}

		$mainQuery = "SELECT 

		br.REDIS_DATE,
		br.ID_RKAP,
		br.ID_FS_SOURCE,
		br.ID_FS_DEST,
		br.FUND_AV,
		bh.DIRECTORAT as DIRECTORATE,
		bh.DIVISION as DIVISION,
		bh.UNIT as UNIT,
		bh.TRIBE_USECASE as TRIBE_USECASE_SOURCE,
		bh.RKAP_DESCRIPTION as RKAP_NAME_SOURCE,
		fbh.RKAP_DESCRIPTION as RKAP_NAME_DEST
		-- bh.ENTRY_OPTIMIZE as PROGRAM_ID_SOURCE,
		-- bh.FS_NUMBER as FS_NUMBER_SOURCE,
		-- fbh.FS_NUMBER as FS_NUMBER_DEST

		FROM $this->table_budget_redis br
		-- INNER JOIN $this->table_budget_header bh on br.ID_RKAP = bh.ID_RKAP_LINE and br.ID_FS_SOURCE = bh.ID_FS
		-- INNER JOIN $this->table_budget_header fbh on br.ID_FS_DEST = fbh.ID_FS

		INNER JOIN $this->table_budget_header bh on br.ID_RKAP = bh.ID_RKAP_LINE
		INNER JOIN $this->table_budget_header fbh on br.ID_RKAP = fbh.ID_RKAP_LINE


		where 1=1

		and CONVERT(REDIS_DATE, DATE) BETWEEN ? and ?
		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($redis_date_from, $redis_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($redis_date_from, $redis_date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_download_budget_redis($date_from,$date_to)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$redis_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$redis_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT 

		br.REDIS_DATE,
		br.ID_RKAP,
		br.ID_FS_SOURCE,
		br.ID_FS_DEST,
		br.FUND_AV,
		bh.DIRECTORAT as DIRECTORATE,
		bh.DIVISION as DIVISION,
		bh.UNIT as UNIT,
		bh.RKAP_DESCRIPTION as RKAP_NAME
		-- bh.ENTRY_OPTIMIZE as PROGRAM_ID_SOURCE,
		-- bh.FS_NUMBER as FS_NUMBER_SOURCE,
		-- fbh.FS_NUMBER as FS_NUMBER_DEST

		FROM $this->table_budget_redis br
		-- INNER JOIN $this->table_budget_header bh on br.ID_RKAP = bh.ID_RKAP_LINE and br.ID_FS_SOURCE = bh.ID_FS
		-- INNER JOIN $this->table_budget_header fbh on br.ID_FS_DEST = fbh.ID_FS

		INNER JOIN $this->table_budget_header bh on br.ID_RKAP = bh.ID_RKAP_LINE
		INNER JOIN $this->table_budget_header fbh on br.ID_RKAP = fbh.ID_RKAP_LINE

		where 1=1

		and CONVERT(REDIS_DATE, DATE) BETWEEN ? and ?";

		$query     = $this->db->query($queryExec,array($redis_date_from, $redis_date_to));

		return $query;
	}

	// function get_rkap_line_id_with_name($rkap_name){

	// 	$queryExec = "SELECT DISTINCT ID_RKAP_LINE  FROM $this->table_budget_header where RKAP_DESCRIPTION = '".$rkap_name."'";

	// 	$query = $this->db->query($queryExec);

	// 	$result['queryrkaplineid']	= $query;

	// 	return $result;

	// }

	public function save_data_budget_redis($data)
	{
		$query = $this->db->insert($this->table_budget_redis, $data);

		return true;

	}


}



/* End of file Budgetredistribution_mdl.php */

/* Location: ./application/models/Budgetredistribution_mdl.php */