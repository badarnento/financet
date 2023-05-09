<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Userapprovedjournalhou_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->view_gl = "JOURNAL_AFTER_TAX";
		$this->table_gl_periods = "GL_PERIODS";
	}


	function get_journal_inquiry_datatable($approved_date_from,$approved_date_to,$vendor_name,$filter_date)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			$fieldToSearch = array("TGL_INVOICE","DUE_DATE","BATCH_NAME","BATCH_DESCRIPTION","NAMA_VENDOR","JOURNAL_NAME","NO_INVOICE","NO_KONTRAK","ACCOUNT_DESCRIPTION","NATURE","CURRENCY","DEBET","CREDIT","DESCRIPTION","REMARK_APPROVED","REMARK_APPROVED_HOU");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}


		if($filter_date)
		{
			if($filter_date == "1") 
			{
				$where .= " AND CONVERT(APPROVED_HOU_DATE, DATE) BETWEEN ? and ?";
			}
			elseif($filter_date == "3") 
			{
				$where .= " AND CONVERT(APPROVED_DATE, DATE) BETWEEN ? and ?";
			}
			else
			{
				$where .= " AND CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ?";
			}
		}
		else
		{
			$where .= " AND CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ?";
		}
		$whereVal[] = $approved_date_from;
		$whereVal[] = $approved_date_to;

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = ?";
			$whereVal[] = $vendor_name;
		}

		$mainQuery = "SELECT * FROM $this->view_gl where 1=1 AND VERIFICATED = 'Y' AND APPROVED = 'Y' $where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		// echo $this->db->last_query(); die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_journal_after_tax_datatable($vendor_name,$approved_status,$batch_appr)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("gl.TGL_INVOICE","gl.DUE_DATE","gl.BATCH_NAME","gl.BATCH_DESCRIPTION","gl.NAMA_VENDOR","gl.JOURNAL_NAME","gl.NO_INVOICE","gl.NO_KONTRAK","gl.ACCOUNT_DESCRIPTION","gl.NATURE","gl.CURRENCY","gl.DEBET","gl.CREDIT","gl.DESCRIPTION","gl.REMARK_APPROVED","gl.REMARK_APPROVED_HOU");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		if($batch_appr)
		{
			$where .= " AND BATCH_APPROVAL_HOU = '".$batch_appr."' ";
		}

		if($approved_status)
		{
			$where .= " AND APPROVED_HOU = '".$approved_status."' ";
		}
		else
		{
			$where .= " AND (APPROVED_HOU = '' or APPROVED_HOU IS NULL)  ";
		}

		// $mainQuery = "SELECT *

		// FROM $this->view_gl

		// where 1=1

		// and VERIFICATED = 'Y' AND  APPROVED = 'Y'  AND ( PAYMENT_STATUS = '' or  PAYMENT_STATUS is null )  AND ( (DEBET <= 20000000) or (CREDIT <= 20000000) )  $where";

		$mainQuery = "SELECT gl.*, gp.STATUS as STATUS_CLOSING

		FROM $this->view_gl gl
		INNER JOIN $this->table_gl_periods gp on MONTH(gl.TGL_INVOICE) = gp.MONTH AND YEAR(gl.TGL_INVOICE) = gp.YEAR

		where 1=1

		and VERIFICATED = 'Y' AND  APPROVED = 'Y'  AND (BATCH_APPROVAL_HOU != '' and BATCH_APPROVAL_HOU IS NOT NULL) AND ( PAYMENT_STATUS = '' or  PAYMENT_STATUS is null ) $where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	function get_download_after_tax($approved_date_from, $approved_date_to, $vendor_name ,$filter_date)
	{

		$approved_date_from = $approved_date_from;
		$approved_date_to = $approved_date_to;

		$exp_approved_date_from = explode("/", $approved_date_from);
		$exp_approved_date_to   = explode("/", $approved_date_to);

		$approved_date_from     = $exp_approved_date_from[2]."-".$exp_approved_date_from[1]."-".$exp_approved_date_from[0];
		$approved_date_to       = $exp_approved_date_to[2]."-".$exp_approved_date_to[1]."-".$exp_approved_date_to[0];

		$where = "";

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		if($filter_date)
		{
			if($filter_date == "1") 
			{
				$where = " AND CONVERT(APPROVED_HOU_DATE, DATE) BETWEEN '".$approved_date_from."' and '".$approved_date_to."' ";
			}
			else
			{
				$where = " AND CONVERT(TGL_INVOICE, DATE) BETWEEN '".$approved_date_from."' and '".$approved_date_to."' ";
			}
		}

		// $queryExec = " SELECT * from $this->view_gl where 1=1  and VERIFICATED = 'Y' and APPROVED = 'Y'  AND ( (DEBET <= 20000000) or (CREDIT <= 20000000) )   $where ";

		$queryExec = " SELECT * from $this->view_gl where 1=1  and VERIFICATED = 'Y' and APPROVED = 'Y'  $where ";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	public function get_all_vendor_1m()
	{
		// $mainQuery	        = "SELECT DISTINCT NAMA_VENDOR
		// FROM $this->view_gl where VERIFICATED = 'Y' AND APPROVED = 'Y' AND ( (DEBET <= 20000000) or (CREDIT <= 20000000) )
		// order by NAMA_VENDOR desc";

	    $mainQuery	        = "SELECT DISTINCT NAMA_VENDOR
		FROM $this->view_gl where VERIFICATED = 'Y' AND APPROVED = 'Y'
		order by NAMA_VENDOR desc";

		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	function get_journal_to_approvehog($batch_approval){
		$this->db->where("BATCH_APPROVAL_HOG", $batch_approval);
		$this->db->where_in("NATURE", array('21122001','21121001'));
		$this->db->where("( DEBET > 20000000  or CREDIT > 20000000 )");
		
		return $this->db->get($this->view_gl);
	}

	function get_journal_to_approved_journal($batch_approval){
		$this->db->where("BATCH_APPROVAL_HOU", $batch_approval);
		$this->db->where_in("NATURE", array('21122001','21121001'));
		
		return $this->db->get($this->view_gl);
	}

	public function get_approve_batch()
	{
		$param_verified_status = $this->input->post('param_verified_status');

		$where = "";
		$whereArr = array();

		if($param_verified_status){
			$where .= " AND APPROVED_HOU = ?";
			$whereArr[] = $param_verified_status;
		}
		else
		{
			$where .= " AND ( APPROVED_HOU = '' or APPROVED_HOU IS NULL )";
			// $whereArr[] = 'N';
		}

		$mainQuery	        = "SELECT DISTINCT
		BATCH_APPROVAL_HOU
		FROM $this->view_gl  
		where BATCH_APPROVAL_HOU != ''";
		$query 		        = $this->db->query($mainQuery,$whereArr);

		// echo $mainQuery; die;

		$result['query']	= $query;
		return $result;		
	}

}



/* End of file Userapprovedjournalhou_mdl.php */

/* Location: ./application/models/Userapprovedjournalhou_mdl.php */