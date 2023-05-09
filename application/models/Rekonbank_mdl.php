<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Rekonbank_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
	}


	function get_rekon_bank_datatable($gl_date_from,$gl_date_to, $nature)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where_nature    = "";
		$where  = "";
		$whereArr = array();

			if($nature != "")
			{
					$where_nature .= " WHERE gl.NATURE = ? ";
					$whereArr[] = $nature;
			}

		if($keywords != ""){
			$fieldToSearch = array("GL_DATE","BATCH_NAME","JOURNAL_NAME","CURRENCY","DEBIT","CREDIT","NATURE","ACCOUNT_DESCRIPTION","JOURNAL_DESCRIPTION","BATCH_DESCRIPTION","JOURNAL_REFERENCE","LINE_DESCRIPTION");
			$where .= query_datatable_search($keywords, $fieldToSearch);
		}

		$whereArr[] = $gl_date_from;
		$whereArr[] = $gl_date_to;

		$mainQuery = "
		SELECT
		DATE(gjl.GL_DATE) ACCOUNTING_DATE,
		gjl.BATCH_NAME,
		gjl.JOURNAL_NAME,
		gjl.CURRENCY,
		gjl.DEBIT,
		gjl.CREDIT,
		gjl.NATURE,
		gjl.ACCOUNT_DESCRIPTION BANK,
		gjl.JOURNAL_DESCRIPTION,
		gjl.REFERENCE_1 BATCH_DESCRIPTION,
		gjl.REFERENCE_2 JOURNAL_REFERENCE,
		gjl.REFERENCE_3 LINE_DESCRIPTION
		FROM
		    GL_JOURNAL_LINE gjl
		WHERE
		    gjl.JOURNAL_NAME IN(
		    SELECT DISTINCT
		        gl.journal_name
		    FROM
		        GL_JOURNAL_LINE gl
		    $where_nature
		) AND DATE(gjl.GL_DATE) BETWEEN ? AND ?

		$where

		ORDER BY
		    gjl.GL_DATE,
		    gjl.JOURNAL_NAME";

		$queryData = query_datatable($mainQuery);
		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;		

	}




	function get_download_rekon_bank($date_from,$date_to,$nature)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where_nature    = "";
		$where  = "";
		$whereArr = array();

			if($nature != "")
			{
					$where_nature .= " WHERE gl.NATURE = ? ";
					$whereArr[] = $nature;
			}

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$whereArr[] = $gl_date_from;
		$whereArr[] = $gl_date_to;

		$queryExec = "
		SELECT
		DATE(gjl.GL_DATE) ACCOUNTING_DATE,
		gjl.BATCH_NAME,
		gjl.JOURNAL_NAME,
		gjl.CURRENCY,
		gjl.DEBIT,
		gjl.CREDIT,
		gjl.NATURE,
		gjl.ACCOUNT_DESCRIPTION BANK,
		gjl.JOURNAL_DESCRIPTION,
		gjl.REFERENCE_1 BATCH_DESCRIPTION,
		gjl.REFERENCE_2 JOURNAL_REFERENCE,
		gjl.REFERENCE_3 LINE_DESCRIPTION
		FROM
		    GL_JOURNAL_LINE gjl
		WHERE
		    gjl.JOURNAL_NAME IN(
		    SELECT DISTINCT
		        gl.journal_name
		    FROM
		        GL_JOURNAL_LINE gl
		    $where_nature
		) AND DATE(gjl.GL_DATE) BETWEEN ? AND ?

		$where

		ORDER BY
		    gjl.GL_DATE,
		    gjl.JOURNAL_NAME";

		$query     = $this->db->query($queryExec,$whereArr);

		return $query;
	}

}



/* End of file Rekonbank_mdl.php */

/* Location: ./application/models/Rekonbank_mdl.php */