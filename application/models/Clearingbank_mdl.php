<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Clearingbank_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->table_post_clearing_bank = "POST_CLEARING_BANK";
		$this->table_payment_batch = "BATCH_PAYMENT";
		$this->table_gl_header = "GL_HEADERS";
	}


	function get_upload_clearing_bank_datatable($post_date_from,$post_date_to,$bank)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";

		if($keywords != ""){
			$fieldToSearch = array("BALANCE", "NO_REKENING", "DESCRIPTION", "KURS", "CREDIT","DEBIT", "BANK_NAME", "STATUS");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($bank)
		{
			$where .= " AND BANK_NAME = '".$bank."' ";
		}

		// $mainQuery = "
		// SELECT 
		// TRANSACTION_DATE as TRANSACTION_DATE,
		// BALANCE,
		// NO_REKENING,
		// CONCAT (DESCRIPTION_A, '|', DESCRIPTION_B, '|', DESCRIPTION_C, '|', DESCRIPTION_D, '|', DESCRIPTION_E)  AS DESCRIPTION,
		// KURS,
		// MUTASI,
		// BANK_NAME,
		// STATUS
		// FROM
		// $this->table_post_clearing_bank

		// where 1=1

		// and CONVERT(TRANSACTION_DATE, DATE) BETWEEN ? and ? 
		// $where order by ID_POST";

		$mainQuery = "SELECT 
		TRANSACTION_DATE as TRANSACTION_DATE,
		BALANCE,
		NO_REKENING,
		DESCRIPTION,
		KURS,
		CREDIT,
		DEBIT,
		BANK_NAME,
		STATUS
		FROM
		$this->table_post_clearing_bank

		where 1=1

		and CONVERT(TRANSACTION_DATE, DATE) BETWEEN ? and ? 
		$where order by ID_POST";


		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($post_date_from, $post_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($post_date_from, $post_date_to))->result_array();

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}

	//region code lama
	// public function insert_upload_clearing_bank_import($data)
	// {
	// 	foreach ($data as $row)
	// 	{

	// 		// echo "<pre>";print_r($row);

	// 		$paramKey = array_keys($row);

	// 		for ($i=0; $i < count($paramKey); $i++) {
	// 			$onUpdate[] = $paramKey[$i] ." = '".$row[$paramKey[$i]]."'";
	// 		}

 //   			// echo implode(",", $onUpdate);

	// 		// die;

	// 		$nojurnal = $row['NO_JOURNAL'];
	// 		$sql = $this->db->insert_string($this->table_post_clearing_bank, $row) . " ON DUPLICATE KEY UPDATE " .implode(",", $onUpdate);
	// 		$this->db->query($sql);

	// 		// echo $this->db->last_query();
	// 		// die;

	// 	}


	// 	return true;

	// }


	//kodingan lama make merge
	// public function insert_upload_clearing_bank_import($data)
	// {
	// 	foreach ($data as $row)
	// 	{

	// 		// echo "<pre>";print_r($row);

	// 		$paramKey = array_keys($row);

	// 		for ($i=0; $i < count($paramKey); $i++) {
	// 			$onUpdate[] = $paramKey[$i] ." = '".$row[$paramKey[$i]]."'";
	// 		}

 //   			// echo implode(",", $onUpdate);

	// 		// die;

	// 		$nojurnal = $row['NO_REKENING'];
	// 		$sql = $this->db->insert_string($this->table_post_clearing_bank, $row) . " ON DUPLICATE KEY UPDATE " .implode(",", $onUpdate);
	// 		$this->db->query($sql);

	// 		// echo $this->db->last_query();
	// 		// die;

	// 	}


	// 	return true;

	// }
	//endregion code lama

	public function insert_upload_clearing_bank_import($datas)
	{
		foreach ($datas as $key => $value) {

			// echo_pre($value);  die;
			$transaction_date = $value['TRANSACTION_DATE'];
			$balance = $value['BALANCE'];
			$no_rekening = $value['NO_REKENING'];

			$check_exist = $this->crud->check_exist("POST_CLEARING_BANK", array("TRANSACTION_DATE" => $transaction_date, "BALANCE" => $balance, "NO_REKENING" => $no_rekening));

			if($check_exist > 0)
			{
				$this->db->where('TRANSACTION_DATE', $transaction_date);
				$this->db->where('BALANCE', $balance);
				$this->db->where('NO_REKENING', $no_rekening);
				$this->db->update($this->table_post_clearing_bank, $value);
			}
			else
			{
				$this->db->insert($this->table_post_clearing_bank, $value);
			}

		}


		return true;

	}

	public function get_ddl_bank_transaction()
	{
		$mainQuery	        = "SELECT DISTINCT BANK_NAME
		FROM $this->table_post_clearing_bank  
		order by BANK_NAME";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}


	function get_download_upload_clearing_bank($date_from,$date_to,$bank="")
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where = "";
		$whereArr = array();

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$post_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$post_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$whereArr[] = $post_date_from;
		$whereArr[] = $post_date_to;

		if($bank){
			$where .= " AND BANK_NAME = ?";
			$whereArr[] = $bank;
		}
		
		$queryExec = " SELECT  TRANSACTION_DATE as TRANSACTION_DATE,
								BALANCE,
								NO_REKENING,
								DESCRIPTION,
								KURS,
								CREDIT,
								DEBIT,
								BANK_NAME,
								STATUS
						FROM $this->table_post_clearing_bank 
						where CONVERT(TRANSACTION_DATE, DATE) BETWEEN ? and ? $where order by TRANSACTION_DATE";

		$query     = $this->db->query($queryExec, $whereArr);

		return $query;
	}

	public function call_procedure()

	{
		$sql = "CALL PAYMENT_RECONCILE";

		$this->db->query($sql);

		return true;

	}
	
	public function call_procedure_po()

	{
		$sql = "CALL STATUS_PO";

		$this->db->query($sql);

		return true;

	}

	public function get_master_batch_name()
	{
		
		$mainQuery	        = "SELECT DISTINCT BATCH_NAME
		FROM $this->table_payment_batch
		order by BATCH_NAME desc";
		
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	function get_batch_payment_datatable($batchname)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		
		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("bp.BATCH_DATE","bp.BATCH_NAME","bp.BATCH_NUMBER","bp.JURNAL_PAYMENT_NUMBER","bp.TGL_INVOICE","bp.NO_JOURNAL","bp.NAMA_VENDOR","bp.NO_INVOICE","bp.NO_KONTRAK","bp.DESCRIPTION","bp.DPP","bp.NO_FPJP","bp.NAMA_REKENING","bp.NAMA_BANK","bp.ACCT_NUMBER","bp.RKAP_NAME","bp.TOP","bp.DUE_DATE","bp.NATURE","bp.STATUS","bp.BANK_CHARGES");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($batchname)
		{
			$where .= " and bp.BATCH_NAME = '".$batchname."'";
		}

		$mainQuery = "SELECT 

		bp.BATCH_DATE,
		bp.BATCH_NAME,
		bp.BATCH_NUMBER,
		bp.JURNAL_PAYMENT_NUMBER,
		bp.TGL_INVOICE,
		bp.NO_JOURNAL,
		bp.NAMA_VENDOR,
		bp.NO_INVOICE,
		bp.NO_KONTRAK,
		bp.DESCRIPTION,
		/*sum(bp.DPP) DPP,*/
		SUM(
		TRUNCATE
		(
		(
		bp.DPP +(gh.PERCENTAGE_PPN / 100 * bp.DPP)
		) -(gh.percentage_pph / 100) * bp.DPP,
		0
		)
		) DPP,
		bp.NO_FPJP,
		bp.NAMA_REKENING,
		bp.NAMA_BANK,
		bp.ACCT_NUMBER,
		bp.RKAP_NAME,
		bp.TOP,
		bp.DUE_DATE,
		bp.NATURE,
		bp.STATUS,
		bp.BANK_CHARGES,
		/*SUM(
		TRUNCATE
		(
		(
		bp.DPP +(gh.PERCENTAGE_PPN / 100 * bp.DPP)
		) -(gh.percentage_pph / 100) * bp.DPP,
		0
		)
		) AMOUNT_TO_PAYMENT*/
		(select sum(jbc.credit) new_dpp from JOURNAL_BATCH_PAYMENT jbc where jbc.NATURE='11121001' and jbc.no_journal=bp.NO_JOURNAL) AMOUNT_TO_PAYMENT

		FROM $this->table_payment_batch bp 
		INNER JOIN $this->table_gl_header gh
		on gh.GL_HEADER_ID = bp.GL_HEADER_ID

		where 1=1

		$where  group by bp.NO_JOURNAL";

	// echo $mainQuery; die();

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_download_batch_payment($batchname)
	{

		$where = "";

		if($batchname)
		{
			$where .= " and bp.BATCH_NAME = '".$batchname."'";
		}

		$mainQuery = "SELECT 

		bp.BATCH_DATE,
		bp.BATCH_NAME,
		bp.BATCH_NUMBER,
		bp.JURNAL_PAYMENT_NUMBER,
		bp.TGL_INVOICE,
		bp.NO_JOURNAL,
		bp.NAMA_VENDOR,
		bp.NO_INVOICE,
		bp.NO_KONTRAK,
		bp.DESCRIPTION,
		/*sum(bp.DPP) DPP,*/
		SUM(
		TRUNCATE
		(
		(
		bp.DPP +(gh.PERCENTAGE_PPN / 100 * bp.DPP)
		) -(gh.percentage_pph / 100) * bp.DPP,
		0
		)
		) DPP,
		bp.NO_FPJP,
		bp.NAMA_REKENING,
		bp.NAMA_BANK,
		bp.ACCT_NUMBER,
		bp.RKAP_NAME,
		bp.TOP,
		bp.DUE_DATE,
		bp.NATURE,
		bp.STATUS,
		bp.BANK_CHARGES,
		/*SUM(
		TRUNCATE
		(
		(
		bp.DPP +(gh.PERCENTAGE_PPN / 100 * bp.DPP)
		) -(gh.percentage_pph / 100) * bp.DPP,
		0
		)
		) AMOUNT_TO_PAYMENT*/
		(select sum(jbc.credit) new_dpp from JOURNAL_BATCH_PAYMENT jbc where jbc.NATURE='11121001' and jbc.no_journal=bp.NO_JOURNAL) AMOUNT_TO_PAYMENT

		FROM $this->table_payment_batch bp 
		INNER JOIN $this->table_gl_header gh
		on gh.GL_HEADER_ID = bp.GL_HEADER_ID

		where 1=1

		$where  group by bp.NO_JOURNAL";

		$query     = $this->db->query($mainQuery);

		return $query;
	}

}



/* End of file Clearingbank_mdl.php */

/* Location: ./application/models/Clearingbank_mdl.php */