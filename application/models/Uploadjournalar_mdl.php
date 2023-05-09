<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Uploadjournalar_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->table_upload_journal_ar = "JOURNAL_AR";
	}


	function get_upload_journal_ar_datatable($gl_date_from,$gl_date_to, $nature)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("ar.GL_DATE","ar.BATCH_NAME","ar.JOURNAL_NAME","ar.SALDO_AWAL","ar.DEBIT","ar.CREDIT","ar.NATURE","ar.ACCOUNT_DESCRIPTION","ar.JOURNAL_DESCRIPTION","ar.REFERENCE_1","ar.REFERENCE_2","ar.REFERENCE_3");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where_nature = "";

		if($nature != ""){
			$where_nature = " and ar.NATURE = $nature ";
		}

		$mainQuery = "
		SELECT 
		CONVERT(ar.GL_DATE, DATE) as GL_DATE,
		ar.BATCH_NAME,
		ar.JOURNAL_NAME,
		ar.SALDO_AWAL,
		ar.DEBIT,
		ar.CREDIT,
		ar.ACCOUNT_DESCRIPTION,
		ar.JOURNAL_DESCRIPTION,
		ar.NATURE,
		ar.REFERENCE_1,
		ar.REFERENCE_2,
		ar.REFERENCE_3,
		ar.VALIDATED,
		IFNULL(glat.CREDIT, 0) AP_AMOUNT_GL,
		IFNULL(gh.NO_INVOICE, '') AP_INVOICE_GL,
		IFNULL(gh.DESCRIPTION, '') AP_DESCRIPTION_GL,
		ar.AP_INVOICE,
		ar.AP_AMOUNT,
		ar.AP_DESCRIPTION,
		ar.STATUS_AP_NETTING,
		ar.STATUS AR_INVOICE_NETTING
		FROM
		$this->table_upload_journal_ar ar
		LEFT JOIN GL_HEADERS gh on ar.reference_1 = gh.nama_vendor and gh.ar_is_more_than_ap = 'Y'
		LEFT JOIN GL_JOURNAL_AFTER_TAX glat on gh.no_journal = glat.journal_description and glat.nature = '21122001'
		where 1=1
		and CONVERT(ar.GL_DATE, DATE) BETWEEN ? and ? 
		$where_nature
		$where order by ar.JOURNAL_NAME, ar.BATCH_NAME";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($gl_date_from, $gl_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($gl_date_from, $gl_date_to))->result_array();

		//echo $this->db->last_query(); die();

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}


	function get_download_upload_journal_ar($date_from,$date_to,$nature)
	{
		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where = "";
		$whereArr = array();

		

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$whereArr[] = $gl_date_from;
		$whereArr[] = $gl_date_to;

		if($nature !="undefined" && $nature != ""){
			$where .= " AND NATURE = ?";
			$whereArr[] = $nature;
		}

		$queryExec = " SELECT * from $this->table_upload_journal_ar where CONVERT(GL_DATE, DATE) BETWEEN ? and ? $where ";

		$query     = $this->db->query($queryExec, $whereArr);

		return $query;
	}

	function edit_data_upload_journal_ar($journal_name)
    {
    	$this->db->where('JOURNAL_NAME', $journal_name);
    	$this->db->update($this->table_upload_journal_ar, $data);
    	return true;
    }

	function update_ap_netting($journal_name, $data)
    {
    	$this->db->where('JOURNAL_NAME', $journal_name);
    	$this->db->limit(1);
    	$this->db->update($this->table_upload_journal_ar, $data);
    	return true;
    }

    function delete_data_upload_journal_ar($journal_name)
    {
    	$this->db->where('JOURNAL_NAME', $journal_name);
    	$this->db->delete($this->table_upload_journal_ar);
    	return true;
    }

}



/* End of file Uploadjournalar_mdl.php */

/* Location: ./application/models/Uploadjournalar_mdl.php */