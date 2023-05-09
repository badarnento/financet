<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Uservalidate_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->view_gl = "JOURNAL_AFTER_TAX";
		$this->table_gl_periods = "GL_PERIODS";
	}

	function get_journal_inquiry_datatable($verificated_date_from,$verificated_date_to,$vendor_name,$filter_date,$verificatedstatus,$emailuser)

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

		if($filter_date)
		{
			if($filter_date == "1") 
			{
				$where .= " AND CONVERT(VERIFICATED_DATE, DATE) BETWEEN '".$verificated_date_from."' and '".$verificated_date_to."' ";
			}
			else
			{
				$where .= " AND CONVERT(TGL_INVOICE, DATE) BETWEEN '".$verificated_date_from."' and '".$verificated_date_to."' ";
			}
		}
		else
		{
			$where .= " AND CONVERT(TGL_INVOICE, DATE) BETWEEN '".$verificated_date_from."' and '".$verificated_date_to."' ";
		}

		if($verificatedstatus)
		{
			$where .= " AND VERIFICATED = '".$verificatedstatus."' ";
		}

		/*if($emailuser)
		{
			$where .= " AND ( APPROVER_UPLOADER = '".$emailuser."' OR APPROVER_UPLOADER = '' OR APPROVER_UPLOADER IS NULL ) ";
		}*/

		$mainQuery = "SELECT *

		FROM $this->view_gl

		where 1=1

		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		// echo $this->db->last_query(); die;

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	

	}

	// function get_journal_after_tax_datatable($invoice_date_from,$invoice_date_to)
	// bypass gk pake tanggal


	function get_journal_after_tax_datatable($vendor_name,$verified_status,$batch_appr,$emailuser)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("gl.TGL_INVOICE","gl.DUE_DATE","gl.BATCH_NAME","gl.BATCH_DESCRIPTION","gl.NAMA_VENDOR","gl.JOURNAL_NAME","gl.NO_INVOICE","gl.NO_KONTRAK","gl.ACCOUNT_DESCRIPTION","gl.NATURE","gl.CURRENCY","gl.DEBET","gl.CREDIT","gl.DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = ? ";
			$whereVal[] = $vendor_name;
		}

		if($batch_appr)
		{
			$where .= " AND BATCH_APPROVAL = ? ";
			$whereVal[] = $batch_appr;
		}

		if($verified_status)
		{
			$where .= " AND VERIFICATED = ? ";
			$whereVal[] = $verified_status;
		}
		else
		{
			$where .= " AND VERIFICATED = 'N' ";
		}

		/*if($emailuser)
		{
			$where .= " AND ( APPROVER_UPLOADER = '".$emailuser."' OR APPROVER_UPLOADER = '' OR APPROVER_UPLOADER IS NULL ) ";
		}*/

		$mainQuery = "SELECT gl.*, gp.STATUS as STATUS_CLOSING

					FROM $this->view_gl gl
					INNER JOIN $this->table_gl_periods gp on MONTH(gl.TGL_INVOICE) = gp.MONTH AND YEAR(gl.TGL_INVOICE) = gp.YEAR

					where 1=1 and (APPROVED IS NULL or APPROVED='N' or APPROVED='')
					$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	

	}


	function get_download_after_tax($verificated_date_from, $verificated_date_to,$vendor_name,$filter_date,$verificatedstatus)
	{

		$verificated_date_from = $verificated_date_from;
		$verificated_date_to = $verificated_date_to;

		$exp_verificated_date_from = explode("/", $verificated_date_from);
		$exp_verificated_date_to   = explode("/", $verificated_date_to);

		$verificated_date_from     = $exp_verificated_date_from[2]."-".$exp_verificated_date_from[1]."-".$exp_verificated_date_from[0];
		$verificated_date_to       = $exp_verificated_date_to[2]."-".$exp_verificated_date_to[1]."-".$exp_verificated_date_to[0];

		$where = "";

		$whereArr = array();

		if($vendor_name){
			$where .= " AND NAMA_VENDOR = ?";
			$whereArr[] = $vendor_name;
		}

		if($filter_date)
		{
			if($filter_date == "1") 
			{
				$where = " AND CONVERT(VERIFICATED_DATE, DATE) BETWEEN ? and ? ";
				$whereArr[] = $verificated_date_from;
				$whereArr[] = $verificated_date_to;
			}
			else
			{
				$where = " AND CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? ";
				$whereArr[] = $verificated_date_from;
				$whereArr[] = $verificated_date_to;
			}
		}

		if($verificatedstatus){
			$where .= " AND VERIFICATED = ?";
			$whereArr[] = $verificatedstatus;
		}

		$queryExec = " SELECT * from $this->view_gl
						where 1=1 $where ";

		$query     = $this->db->query($queryExec,$whereArr);

		return $query;
	}

	function get_journal_to_verification($batch_approval){
		$this->db->where("BATCH_APPROVAL_LEAD", $batch_approval);
		$this->db->where_in("NATURE", array('21122001','21121001'));
		
		return $this->db->get($this->view_gl);
	}

	function get_journal_to_usertax($batch_approval){
		$this->db->where("BATCH_APPROVAL", $batch_approval);
		$this->db->where_in("NATURE", array('21122001','21121001'));
		
		return $this->db->get($this->view_gl);
	}


	public function get_approve_batch()
	{
		$mainQuery	        = "SELECT DISTINCT
		BATCH_APPROVAL
		FROM $this->view_gl  
		where BATCH_APPROVAL != ''
		AND VALIDATED = 'Y'";
		$query 		        = $this->db->query($mainQuery);

		// echo $mainQuery; die;

		$result['query']	= $query;
		return $result;		
	}



	function get_cetak($vendor_name, $invoice_date_from, $invoice_date_to)
	{
		ini_set('memory_limit', '-1');

						$where = "";

						if($vendor_name)
						{
							$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
						}

						$queryExec	= "SELECT distinct gh.NO_INVOICE,
						gh.NO_KONTRAK,
						gh.NAMA_VENDOR,
						gh.NO_JOURNAL,
						gh.FAKTUR_PAJAK,
						gh.PERCENTAGE_PPH,
						gh.DESCRIPTION,
						gh.CURRENCY,
						gh.IS_BAST,
						sum(gh.DPP) DPP,
						sum(gh.NOMINAL_RATE) NOMINAL,
						(select ifnull(sum(debet),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPN and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) DPP_PPN,
						(select ifnull(sum(debet),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPN and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) + sum(gh.DPP) TOTAL_PPN,
						(select ifnull(sum(credit),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPH and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) DPP_PPH,
						((select ifnull(sum(debet),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPN and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) + sum(gh.DPP) - (select ifnull(sum(credit),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPH and JOURNAL_DESCRIPTION = gh.NO_JOURNAL)) TOTAL_PPH,
						(select abs(ifnull(sum(debet),0)-ifnull(sum(credit),0)) from GL_JOURNAL_AFTER_TAX where nature = '21310003' and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) VATSA,
						/* (select fd.VATSA from FPJP_DETAIL fd, FPJP_HEADER fh where gh.NO_FPJP =  fh.FPJP_NUMBER and fd.FPJP_HEADER_ID = fh.FPJP_HEADER_ID and fd.VATSA != 0) VAT,
						-- (select fd.PPH from FPJP_DETAIL fd, FPJP_HEADER fh where gh.NO_FPJP =  fh.FPJP_NUMBER and fd.FPJP_HEADER_ID = fh.FPJP_HEADER_ID and fd.VATSA != 0) PPH,
						-- (select fd.PPH from FPJP_DETAIL fd, FPJP_HEADER fh where gh.NO_FPJP =  fh.FPJP_NUMBER and fd.FPJP_HEADER_ID = fh.FPJP_HEADER_ID and fd.PPH != 0) COD, */
						gh.NATURE,
						gh.NAMA_REKENING,
						gh.NAMA_BANK,
						gh.ACCT_NUMBER,
						gh.TOP,
						gh.DENDA,
						(select ghr.dpp from GL_HEADERS ghr where ghr.nature='55270001' and ghr.no_journal=gh.no_journal) MATERAI,
						(select ghr.dpp from GL_HEADERS ghr where lower(replace(ghr.DESCRIPTION,' ','')) like'%PPhyangditanggung%'  and ghr.no_journal=gh.no_journal) PPH_DITANGGUNG,
						gh.VERIFICATED,
						gh.VERIFICATED_DATE,
						nvl(gh.NATURE_PPH,0) WHT_ACCOUNT,
						nvl(gh.NATURE_PPN,0) VAT_ACCOUNT,
						(select NATURE from GL_JOURNAL_AFTER_TAX where nature = '21310003' and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) VATSA_ACCOUNT,
						CONVERT(gh.DUE_DATE, DATE) DUE_DATE
						from GL_HEADERS gh
						WHERE gh.VERIFICATED = 'Y'
						and CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? $where and gh.NATURE <>'55270001'
						AND lower(replace(gh.DESCRIPTION,' ','')) not like '%PPhyangditanggung%'
						group by gh.NAMA_BANK,gh.no_journal,gh.nama_vendor,gh.nama_rekening,gh.no_kontrak,gh.no_invoice";

						$queryData = query_datatable_nolimit($queryExec);

						$total = $this->db->query($queryExec, array($invoice_date_from, $invoice_date_to))->num_rows();
						$data  = $this->db->query($queryData, array($invoice_date_from, $invoice_date_to))->result_array();

						$result['data']       = $data;
						$result['total_data'] = $total;

						return $result;
					}

	function get_cetak_jurnal($jurnal_name)
	{
		ini_set('memory_limit', '-1');

						$queryExec = "SELECT distinct gh.NO_INVOICE,
						gh.NO_KONTRAK,
						gh.NAMA_VENDOR,
						gh.NO_JOURNAL,
						gh.FAKTUR_PAJAK,
						gh.PERCENTAGE_PPH,
						gh.DESCRIPTION,
						gh.CURRENCY,
						gh.IS_BAST,
						sum(gh.DPP) DPP,
						sum(gh.NOMINAL_RATE) NOMINAL,
						(select ifnull(sum(debet),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPN and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) DPP_PPN,
						(select ifnull(sum(debet),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPN and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) + sum(gh.DPP) TOTAL_PPN,
						(select ifnull(sum(credit),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPH and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) DPP_PPH,
						((select ifnull(sum(debet),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPN and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) + sum(gh.DPP) - (select ifnull(sum(credit),0) from GL_JOURNAL_AFTER_TAX where nature = gh.NATURE_PPH and JOURNAL_DESCRIPTION = gh.NO_JOURNAL)) TOTAL_PPH,
						(select abs(ifnull(sum(debet),0)-ifnull(sum(credit),0)) from GL_JOURNAL_AFTER_TAX where nature = '21310003' and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) VATSA,
						/* (select fd.VATSA from FPJP_DETAIL fd, FPJP_HEADER fh where gh.NO_FPJP =  fh.FPJP_NUMBER and fd.FPJP_HEADER_ID = fh.FPJP_HEADER_ID and fd.VATSA != 0) VAT,
						-- (select fd.PPH from FPJP_DETAIL fd, FPJP_HEADER fh where gh.NO_FPJP =  fh.FPJP_NUMBER and fd.FPJP_HEADER_ID = fh.FPJP_HEADER_ID and fd.VATSA != 0) PPH,
						-- (select fd.PPH from FPJP_DETAIL fd, FPJP_HEADER fh where gh.NO_FPJP =  fh.FPJP_NUMBER and fd.FPJP_HEADER_ID = fh.FPJP_HEADER_ID and fd.PPH != 0) COD, */
						gh.NATURE,
						gh.NAMA_REKENING,
						gh.NAMA_BANK,
						gh.ACCT_NUMBER,
						gh.TOP,
						gh.DENDA,
						(select ghr.dpp from GL_HEADERS ghr where ghr.nature='55270001' and ghr.no_journal=gh.no_journal) MATERAI,
						(select ghr.dpp from GL_HEADERS ghr where lower(replace(DESCRIPTION,' ','')) like'%PPhyangditanggung%' and ghr.no_journal=gh.no_journal) PPH_DITANGGUNG,
						gh.VERIFICATED,
						gh.VERIFICATED_DATE,
						nvl(gh.NATURE_PPH,0) WHT_ACCOUNT,
						nvl(gh.NATURE_PPN,0) VAT_ACCOUNT,
						(select distinct NATURE from GL_JOURNAL_AFTER_TAX where nature = '21310003' and JOURNAL_DESCRIPTION = gh.NO_JOURNAL) VATSA_ACCOUNT,
						CONVERT(gh.DUE_DATE, DATE) DUE_DATE
						from GL_HEADERS gh
						WHERE gh.NO_JOURNAL = ? and gh.NATURE <>'55270001'
						AND lower(replace(DESCRIPTION,' ','')) not like '%PPhyangditanggung%'
						group by gh.NAMA_BANK,gh.no_journal,gh.nama_vendor,gh.nama_rekening,gh.no_kontrak,gh.no_invoice";

						$queryData = query_datatable_nolimit($queryExec);

						$total = $this->db->query($queryExec,$jurnal_name)->num_rows();
						$data  = $this->db->query($queryData,$jurnal_name)->result_array();

						$result['data']       = $data;
						$result['total_data'] = $total;

						return $result;
		}

	}



				/* End of file Uservalidate_mdl.php */

/* Location: ./application/models/Uservalidate_mdl.php */