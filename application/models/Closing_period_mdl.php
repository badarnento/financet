<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Closing_period_mdl extends CI_Model {



	public function __construct()
	{
		parent::__construct();
		$this->table_gl_period = "GL_PERIODS";
	}


	function get_closing_datatable()

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			$fieldToSearch = array("YEAR","MONTHNAME","STATUS","DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT ID, YEAR, MONTH, STATUS, DESCRIPTION, MONTHNAME(STR_TO_DATE(MONTH, '%m')) as MONTH_TEXT from $this->table_gl_period where 1=1  $where group by YEAR, MONTH";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		// echo $this->db->last_query(); die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_closing_exist_year(){

		$queryExec = "SELECT DISTINCT YEAR FROM $this->table_gl_period";

		$query = $this->db->query($queryExec);

		$result['query']	= $query;

		return $result;

	}


	function get_download_data_closing()
	{

		$where = "";

		$queryExec = " SELECT ID, YEAR, MONTH, STATUS, DESCRIPTION, MONTHNAME(STR_TO_DATE(MONTH, '%m')) as MONTH_TEXT from $this->table_gl_period where 1=1  $where group by MONTH";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	public function check_exist_closing($year,$month)
	{
		$where = "";
		$whereArr = array();

		IF($year){
			$where .= " AND YEAR = ?";
			$whereArr[] = $year;
		}

		IF($month){
			$where .= " AND MONTH = ?";
			$whereArr[] = $month;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_gl_period  
		where 1=1
		$where";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_closing($data)
	{
		$this->db->insert($this->table_gl_period, $data);
		return true;
	}

	function update_data_closing($data, $id_closing)
	{
		$this->db->where('ID', $id_closing);
		$this->db->update($this->table_gl_period, $data);
		return true;
	}

	function call_procedure_closing($year,$month){

		$sql = "CALL GL_CLOSING($year,$month)";

        $this->db->query($sql);

        // echo $this->db->last_query(); die;

        return true;
	}


	function delete_data_closing($id_closing)
	{
		$this->db->where('ID', $id_closing);
		$this->db->delete($this->table_gl_period);
		return true;
	}

}



/* End of file Closing_period_mdl.php */

/* Location: ./application/models/Closing_period_mdl.php */