<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Uploadjournalclosing_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->view_upload_journal_closing = "GL_JOURNAL_LINES_CLOSING";
	}


	function get_upload_journal_closing_datatable($gl_date_from,$gl_date_to, $nature, $journaltype)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";

		if($journaltype)
		{
			if($journaltype == "Others")
			{
				$where .= " AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )";
			}
			elseif($journaltype == "CM")
			{
				$where .= "  AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' ) ";
			}
			elseif($journaltype == "LA")
			{
				$where .= "  AND JOURNAL_NAME  like  '%/".$journaltype."%' ";
			}
			else
			{
				$where .= "  AND  JOURNAL_NAME  like  '%/".$journaltype."/%' ";
			}
		}

		if($keywords != ""){
			$fieldToSearch = array("GL_DATE","BATCH_NAME","JOURNAL_NAME","SALDO_AWAL","DEBIT","CREDIT","NATURE","ACCOUNT_DESCRIPTION","JOURNAL_DESCRIPTION");
			$where .= query_datatable_search($keywords, $fieldToSearch);
		}

		$where_nature = "";

		if($nature != ""){
			$where_nature = " and NATURE = $nature ";
		}

		

		$mainQuery = "
		SELECT 
		CONVERT(GL_DATE, DATE) as GL_DATE,
		BATCH_NAME,
		JOURNAL_NAME,
		SALDO_AWAL,
		DEBIT,
		CREDIT,
		ACCOUNT_DESCRIPTION,
		JOURNAL_DESCRIPTION,
		NATURE,
		REFERENCE_1,
		REFERENCE_2,
		REFERENCE_3
		FROM
		$this->view_upload_journal_closing

		where 1=1

		and CONVERT(GL_DATE, DATE) BETWEEN ? and ? 
		$where_nature
		$where order by JOURNAL_NAME,BATCH_NAME";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($gl_date_from, $gl_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($gl_date_from, $gl_date_to))->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}


	function get_download_upload_journal_closing($date_from,$date_to,$nature,$journaltype)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where_nature = "";
		$where = "";
		if($nature != "" && $nature != "undefined"){
			$where_nature = " and NATURE = $nature ";
		}

		if($journaltype)
		{
			if($journaltype == "Others")
			{
				$where .= " AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )";
			}
			elseif($journaltype == "CM")
			{
				$where .= "  AND (  JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' ) ";
			}
			elseif($journaltype == "LA")
			{
				$where .= "  AND JOURNAL_NAME  like  '%/".$journaltype."%' ";
			}
			else
			{
				$where .= "  AND  JOURNAL_NAME  like  '%/".$journaltype."/%' ";
			}
		}

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT * from $this->view_upload_journal_closing where CONVERT(GL_DATE, DATE) BETWEEN ? and ? $where_nature $where";

		$query     = $this->db->query($queryExec, array($gl_date_from,$gl_date_to));

		return $query;
	}

}



/* End of file Uploadjournalclosing_mdl.php */

/* Location: ./application/models/Uploadjournalclosing_mdl.php */