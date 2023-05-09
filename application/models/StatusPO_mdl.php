<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StatusPO_mdl extends CI_Model {

	protected 	$tbl_status_po 	= "REPORT_STATUS_PO";

	public function get_status_po(){

		$mainQuery = " select * from $this->tbl_status_po ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_report(){

		$sql = " select * from $this->tbl_status_po ";

		$query = $this->db->query($sql);

		return $query;
	}

}