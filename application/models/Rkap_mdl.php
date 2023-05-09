<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rkap_mdl extends CI_Model {

	function get_exist_year_master(){

		$query = $this->db->query('SELECT DISTINCT EXTRACT(YEAR FROM MONTH) as TAHUN FROM MASTER_UPLOAD_RKAP');

		return $query->result_array();

	}
	
}

/* End of file Rkap_mdl.php */
/* Location: ./application/models/Rkap_mdl.php */