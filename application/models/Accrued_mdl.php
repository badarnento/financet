<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accrued_mdl extends CI_Model {

	function get_accrued_po($type){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("JOURNAL_NAME", "BATCH_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($type != '0'){
			$whereType = "and TYPE = ?";
		}else{
			$whereType = "/*and TYPE = ?*/";
		}

		$sql = " select date(gl_date) ACCOUNTING_DATE,
					BATCH_NAME,
					JOURNAL_NAME,
					JOURNAL_DESCRIPTION,
					ACCOUNT_DESCRIPTION,
					NATURE,
					DEBIT,
					CREDIT
					from JOURNAL_ACRUAL_PO
					where 1=1
					$whereType
					$where
					order by ACCOUNTING_DATE, ID ";

		$queryData = query_datatable($sql);
		$total = $this->db->query($sql, array($type))->num_rows();
		$data  = $this->db->query($queryData, array($type))->result_array();

		// echo $this->db->last_query();die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;
	}

	function get_accrued_po_cetak($type){

		if($type != '0'){
			$whereType = "and TYPE = ?";
		}else{
			$whereType = "/*and TYPE = ?*/";
		}

		$sql = " select date(gl_date) ACCOUNTING_DATE,
					BATCH_NAME,
					JOURNAL_NAME,
					JOURNAL_DESCRIPTION,
					ACCOUNT_DESCRIPTION,
					NATURE,
					DEBIT,
					CREDIT
					from JOURNAL_ACRUAL_PO
					where 1=1
					$whereType
					order by ACCOUNTING_DATE, JOURNAL_NAME ";

		$query = $this->db->query($sql, array($type));

		return $query;
	}

	function get_accr_type(){

		$query = $this->db->query('SELECT DISTINCT TYPE FROM JOURNAL_ACRUAL_PO');

		return $query->result_array();

	}

}

/* End of file Report_xls_mdl.php */
/* Location: ./application/models/Report_xls_mdl.php */