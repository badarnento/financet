<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_approval_tax_mdl extends CI_Model {

	public function __construct()
	{
		parent::__construct();

		$this->procedure_tax = "JOURNAL_BEFORE_TAX";
		$this->procedure_after = "JOURNAL_AFTER_TAX";
		$this->table_gl_periods = "GL_PERIODS";
	}

	function get_data($invoice_date_from, $invoice_date_to,$vendor_name)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";
		$where    = "";
		
		if($keywords != ""){
			$fieldToSearch = array("pt.TGL_INVOICE","pt.DUE_DATE","pt.BATCH_NAME","pt.BATCH_DESCRIPTION","pt.NAMA_VENDOR","pt.JOURNAL_NAME","pt.NO_INVOICE","pt.NO_KONTRAK","pt.ACCOUNT_DESCRIPTION","pt.NATURE","pt.CURRENCY","pt.DEBET","pt.CREDIT","pt.DESCRIPTION","pt.NPWP","pt.FAKTUR_PAJAK","gp.STATUS");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereArr = array();

		IF($vendor_name){
			$where .= " AND NAMA_VENDOR = ?";
			$whereArr[] = $vendor_name;
		}

		$whereArr[] = $invoice_date_from;
		$whereArr[] = $invoice_date_to;

		// if($validate_status)
		// {
		// 	$where .= " AND VALIDATED = '".$validate_status."' ";
		// }

		$mainQuery = "SELECT pt.*, gp.STATUS as STATUS_CLOSING
						FROM $this->procedure_tax pt
						INNER JOIN $this->table_gl_periods gp on MONTH(pt.TGL_INVOICE) = gp.MONTH AND YEAR(pt.TGL_INVOICE) = gp.YEAR
						where 1=1
						and ( CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? )
						AND VALIDATED = 'Y' 
						$where ";

		$queryData = query_datatable($mainQuery);
		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();

		// echo $this->db->last_query(); die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		

	}

	function get_download_before_tax($date_from,$date_to,$vendor_name)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$where = "";

		$whereArr = array();

		IF($vendor_name){
			$where .= " AND NAMA_VENDOR = ?";
			$whereArr[] = $vendor_name;
		}

		$whereArr[] = $invoice_date_from;
		$whereArr[] = $invoice_date_to;

		// if($validate_status)
		// {
		// 	$where .= " AND VALIDATED = '".$validate_status."' ";
		// }

		$queryExec = " SELECT * from $this->procedure_tax where 1=1
						and ( CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? )
						AND VALIDATED = 'Y' 
						$where ";

		$query     = $this->db->query($queryExec,$whereArr);

		return $query;
	}

	function get_data_after($invoice_date_from, $invoice_date_to,$vendor_name)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";
		$where    = "";
		
		if($keywords != ""){
			$fieldToSearch = array("TGL_INVOICE","DUE_DATE","BATCH_NAME","BATCH_DESCRIPTION","NAMA_VENDOR","JOURNAL_NAME","NO_INVOICE","NO_KONTRAK","ACCOUNT_DESCRIPTION","NATURE","CURRENCY","DEBET","CREDIT","DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		// if($validate_status)
		// {
		// 	$where .= " AND VALIDATED = '".$validate_status."' ";
		// }

		$mainQuery = "SELECT *
						FROM $this->procedure_after
						where 1=1 and ( CONVERT(TGL_INVOICE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to' )
						AND VALIDATED = 'Y' AND VERIFICATED = 'N'
						$where ";
						
		$queryData = query_datatable($mainQuery);
		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		// echo $this->db->last_query(); die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		

	}

	function get_download_after_tax($date_from,$date_to,$vendor_name)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$where = "";

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		// if($validate_status)
		// {
		// 	$where .= " AND VALIDATED = '".$validate_status."' ";
		// }

		$queryExec = " SELECT * from $this->procedure_after where 1=1
						and ( CONVERT(TGL_INVOICE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to' )
						AND VALIDATED = 'Y'  AND VERIFICATED = 'N'
						$where ";

		$query     = $this->db->query($queryExec);



		return $query;
	}

	function call_proc(){

		$sql = "CALL Journal_After_Tax";

        $this->db->query($sql);

        return true;
	}

	function delete_data_after_tax($gl_header_id)
	{
		$this->db->where('GL_HEADER_ID', $gl_header_id);
		$this->db->delete('GL_JOURNAL_AFTER_TAX');
		return true;
	}

}

/* End of file User_tax_mdl.php */
/* Location: ./application/models/Usaer_tax_mdl.php */