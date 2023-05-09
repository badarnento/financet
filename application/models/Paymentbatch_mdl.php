<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Paymentbatch_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->table_gl_header = "GL_HEADERS";
		$this->table_payment_batch = "BATCH_PAYMENT";
		$this->table_journal_payment_batch = "JOURNAL_BATCH_PAYMENT";

	}


	public function get_payment_batch_datatable($invoice_date_from,$invoice_date_to,$vendorname,$bankname)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";

		if($vendorname)
		{
			$where .= " or NAMA_VENDOR = '".$vendorname."'";
		}

		if($bankname)
		{
			$where .= " or NAMA_BANK = '".$bankname."'";
		}

		

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (TGL_INVOICE like '%".$q."%') 
			or (BATCH_NAME like '%".$q."%') 
			or (NO_JOURNAL like '%".$q."%') 
			or (NAMA_VENDOR like '%".$q."%') 
			or (NO_INVOICE like '%".$q."%') 
			or (NO_KONTRAK like '%".$q."%') 
			or (DESCRIPTION like '%".$q."%') 
			or (DPP like '%".$q."%') 
			or (NO_FPJP like '%".$q."%') 
			or (NAMA_REKENING like '%".$q."%') 
			or (NAMA_BANK like '%".$q."%') 
			or (ACCT_NUMBER like '%".$q."%')  
			or (NATURE like '%".$q."%')  
			or (TOP like '%".$q."%')";
		}

		$mainQuery = "SELECT *

		FROM $this->table_gl_header

		where 1=1

		and CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? and VALIDATED = 'Y' 
		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($invoice_date_from, $invoice_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($invoice_date_from, $invoice_date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	

	}

	function get_download_payment_batch($date_from,$date_to,$vendor_name,$bank_name)
	{
		$where = "";

		if($vendor_name)
		{
			$where .= " or NAMA_VENDOR = '".$vendor_name."'";
		}

		if($bank_name)
		{
			$where .= " or NAMA_BANK = '".$bank_name."'";
		}

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT * from $this->table_payment_batch where CONVERT(TGL_INVOICE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to'  and VALIDATED = 'Y'  $where ";

		// echo $queryExec; die();

		$query     = $this->db->query($queryExec);

		return $query;
	}

	public function call_procedure($batch_name,$batch_number,$journal_payment_number,$batch_date,$vendor_name,$bank_name,$invoice_date_from,$invoice_date_to,$jurnal_description)

	{
		$sql = "CALL Payment_Batch('".$batch_name."','".$batch_number."','".$journal_payment_number."','".$batch_date."','".$vendor_name."','".$bank_name."','".$invoice_date_from."','".$invoice_date_to."','".$jurnal_description."')";

		// echo $sql; die();

		$query = $this->db->query($sql);

		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function call_journal_payment()

	{
		$sql = "CALL JURNAL_PAYMENT";

		// echo $sql; die();

		$query = $this->db->query($sql);

		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function get_master_batch_name($batch_date_from,$batch_date_to)
	{
		$mainQuery	        = "SELECT DISTINCT BATCH_NAME
		FROM $this->table_payment_batch
		where CONVERT(BATCH_DATE, DATE) BETWEEN '$batch_date_from' and '$batch_date_to'
		order by BATCH_NAME desc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function get_master_batch_number($batch_date_from,$batch_date_to)
	{
		$mainQuery	        = "SELECT DISTINCT BATCH_NUMBER
		FROM $this->table_payment_batch
		where CONVERT(BATCH_DATE, DATE) BETWEEN '$batch_date_from' and '$batch_date_to'
		order by BATCH_NUMBER desc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;

		// echo $mainQuery; die();
		return $result;		
	}

	public function get_master_pj_number($batch_date_from,$batch_date_to)
	{
		$mainQuery	        = "SELECT DISTINCT JURNAL_PAYMENT_NUMBER
		FROM $this->table_payment_batch
		where CONVERT(BATCH_DATE, DATE) BETWEEN '$batch_date_from' and '$batch_date_to'
		order by JURNAL_PAYMENT_NUMBER desc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;

		// echo $mainQuery; die();
		return $result;		
	}


	

	function get_payment_batch_inquiry_datatable($batch_date_from, $batch_date_to, $batchname, $batchnumber, $jurnalpaymentnumber,$invoicedate)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		
		$where = "";

		if($batchname)
		{
			$where .= " or BATCH_NAME = '".$batchname."'";
		}

		if($batchnumber)
		{
			$where .= " or BATCH_NUMBER = '".$batchnumber."'";
		}

		if($jurnalpaymentnumber)
		{
			$where .= " or JURNAL_PAYMENT_NUMBER = '".$jurnalpaymentnumber."'";
		}

		if($invoicedate)
		{
			$where .= " or CONVERT(TGL_INVOICE, DATE) = '".$invoicedate."'";
		}

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (TGL_INVOICE like '%".$q."%') 
			or (BATCH_NAME like '%".$q."%') 
			or (NO_JOURNAL like '%".$q."%') 
			or (NAMA_VENDOR like '%".$q."%') 
			or (NO_INVOICE like '%".$q."%') 
			or (NO_KONTRAK like '%".$q."%') 
			or (DESCRIPTION like '%".$q."%') 
			or (DPP like '%".$q."%') 
			or (NO_FPJP like '%".$q."%') 
			or (NAMA_REKENING like '%".$q."%') 
			or (NAMA_BANK like '%".$q."%') 
			or (ACCT_NUMBER like '%".$q."%')  
			or (NATURE like '%".$q."%')  
			or (TOP like '%".$q."%')";
		}

		$mainQuery = "SELECT *

		FROM $this->table_payment_batch

		where 1=1

		and (CONVERT(BATCH_DATE, DATE) BETWEEN ? and ?) 
		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($batch_date_from, $batch_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($batch_date_from, $batch_date_to))->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_download_payment_batch_inquiry($date_from,$date_to,$batchname,$batchnumber,$jurnalpaymentnumber,$invoicedate)
	{

		$where = "";

		if($batchname)
		{
			$where .= " or BATCH_NAME = '".$batchname."'";
		}

		if($batchnumber)
		{
			$where .= " or BATCH_NUMBER = '".$batchnumber."'";
		}

		if($jurnalpaymentnumber)
		{
			$where .= " or JURNAL_PAYMENT_NUMBER = '".$jurnalpaymentnumber."'";
		}

		$vdate_from = $date_from;
		$vdate_to = $date_to;
		$vinvoice_date = $invoicedate;

		/*echo $invoicedate; die();*/

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);
		$exp_invoicedate   = explode("/", $vinvoice_date);

		$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		$tanggal_invoice     = $exp_invoicedate[2]."-".$exp_invoicedate[1]."-".$exp_invoicedate[0];

		// echo $batch_date_from; die();

		if($invoicedate)
		{
			$where .= " or CONVERT(TGL_INVOICE, DATE) = '".$tanggal_invoice."'";
		}

		$queryExec = " SELECT * from $this->table_payment_batch where CONVERT(BATCH_DATE, DATE) BETWEEN '".$batch_date_from."' AND '".$batch_date_to."'  $where";

		// echo $queryExec; die();

		$query     = $this->db->query($queryExec);

		return $query;
	}

	function get_journal_payment_batch_datatable($invoice_date_from,$invoice_date_to,$batch_name,$batch_number)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where = "";

		if($batch_name)
		{
			$where .= " or pb.BATCH_NAME = '".$batch_name."'";
		}

		if($batch_number)
		{
			$where .= " or pb.BATCH_NUMBER = '".$batch_number."'";
		}

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (jpb.CURRENCY like '%".$q."%')
			or (jpb.NATURE like '%".$q."%') 
			or (jpb.ACCOUNT_DESCRIPTION like '%".$q."%') 
			or (jpb.DEBET like '%".$q."%') 
			or (jpb.CREDIT like '%".$q."%') 
			or (pb.BATCH_NAME like '%".$q."%') 
			or (jpb.JOURNAL_DESCRIPTION like '%".$q."%')
			or (jpb.STATUS like '%".$q."%')";
		}

		$mainQuery = "SELECT 
		'IDR' as CURRENCY,
		jpb.GL_DATE,
		jpb.NATURE,
		jpb.ACCOUNT_DESCRIPTION,
		jpb.DEBET,
		jpb.CREDIT,
		pb.BATCH_NAME,
		pb.BATCH_NUMBER,
		jpb.JOURNAL_DESCRIPTION,
		jpb.STATUS
		FROM $this->table_journal_payment_batch jpb
		INNER JOIN  $this->table_payment_batch pb ON jpb.BATCH_ID = pb.BATCH_ID

		where 1=1

		and CONVERT(jpb.GL_DATE, DATE) BETWEEN ? and ? 
		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($invoice_date_from, $invoice_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($invoice_date_from, $invoice_date_to))->result_array();


		// echo $this->db->last_query(); die();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	

	}

	function get_download_journal_payment_batch($date_from,$date_to,$batch_name,$batch_number)
	{

		$where = "";

		if($batch_name)
		{
			$where .= " or pb.BATCH_NAME = '".$batch_name."'";
		}

		if($batch_number)
		{
			$where .= " or pb.BATCH_NUMBER = '".$batch_number."'";
		}

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT 
		'IDR' as CURRENCY,
		jpb.GL_DATE,
		jpb.NATURE,
		jpb.ACCOUNT_DESCRIPTION,
		jpb.DEBET,
		jpb.CREDIT,
		pb.BATCH_NAME,
		pb.BATCH_NUMBER,
		jpb.JOURNAL_DESCRIPTION,
		jpb.STATUS
		FROM $this->table_journal_payment_batch jpb
		INNER JOIN  $this->table_payment_batch pb ON jpb.BATCH_ID = pb.BATCH_ID
		where CONVERT(jpb.GL_DATE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to' $where";

		// echo $queryExec; die();

		$query     = $this->db->query($queryExec);

		return $query;
	}

	function get_journal_payment_batch_inquiry_datatable($batch_date_from, $batch_date_to, $batch_name, $batch_number)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where = "";

		if($batch_name)
		{
			$where .= " or pb.BATCH_NAME = '".$batch_name."'";
		}

		if($batch_number)
		{
			$where .= " or pb.BATCH_NUMBER = '".$batch_number."'";
		}

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (jpb.CURRENCY like '%".$q."%')
			or (jpb.NATURE like '%".$q."%') 
			or (jpb.ACCOUNT_DESCRIPTION like '%".$q."%') 
			or (jpb.DEBET like '%".$q."%') 
			or (jpb.CREDIT like '%".$q."%') 
			or (pb.BATCH_NAME like '%".$q."%') 
			or (jpb.JOURNAL_DESCRIPTION like '%".$q."%')
			or (jpb.STATUS like '%".$q."%')";
		}

		$mainQuery = "SELECT 
		'IDR' as CURRENCY,
		jpb.GL_DATE,
		jpb.NATURE,
		jpb.ACCOUNT_DESCRIPTION,
		jpb.DEBET,
		jpb.CREDIT,
		pb.BATCH_NAME,
		pb.BATCH_NUMBER,
		jpb.JOURNAL_DESCRIPTION,
		jpb.STATUS
		FROM $this->table_journal_payment_batch jpb
		INNER JOIN  $this->table_payment_batch pb ON jpb.BATCH_ID = pb.BATCH_ID

		where 1=1

		or CONVERT(jpb.GL_DATE, DATE) BETWEEN ? and ? 
		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($batch_date_from, $batch_date_from))->num_rows();
		$data  = $this->db->query($queryData, array($batch_date_to, $batch_date_to))->result_array();


		// echo $this->db->last_query(); die();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	

	}

	function get_download_journal_payment_batch_inquiry($date_from,$date_to,$batch_name="",$batch_number="")
	{

		$where = "";

		if($batch_name != "")
		{
			$where .= " or pb.BATCH_NAME = '".$batch_name."'";
		}

		if($batch_number != "")
		{
			$where .= " or pb.BATCH_NUMBER = '".$batch_number."'";
		}

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT 
		'IDR' as CURRENCY,
		jpb.GL_DATE,
		jpb.NATURE,
		jpb.ACCOUNT_DESCRIPTION,
		jpb.DEBET,
		jpb.CREDIT,
		pb.BATCH_NAME,
		pb.BATCH_NUMBER,
		jpb.JOURNAL_DESCRIPTION,
		jpb.STATUS
		FROM $this->table_journal_payment_batch jpb
		INNER JOIN  $this->table_payment_batch pb ON jpb.BATCH_ID = pb.BATCH_ID
		where CONVERT(jpb.GL_DATE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to' $where";

		// echo $queryExec; die();

		$query     = $this->db->query($queryExec);

		return $query;
	}

}



/* End of file Paymentbatch_mdl.php */

/* Location: ./application/models/Paymentbatch_mdl.php */