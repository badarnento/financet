<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Invoicetracker_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->view_invoicetracker = "INVOICE_TRACKER";
	}

	function get_invoice_tracker_inquiry_datatable($invoice_date_from,$invoice_date_to,$filterdateby)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		/*if($keywords != ""){
			$fieldToSearch = array("TANGGAL","NO_JOURNAL","NAMA_VENDOR","NO_INVOICE","TOTAL_AMOUNT","DESCRIPTION","NO_KONTRAK","NO_FPJP","DPP","PPN","PPH","TOTAL","NAMA_REKENING","NAMA_BANK","ACCT_NUMBER","RKAP_NAME","TOP","TAX_VERIFICATION_DATE","NO_SERI_FAKTUR_PAJAK","NOMOR_NPWP","JOURNAL_AP_BY","NATURE","COA_PARENT","DUE_DATE","HAND_OVER_TO_TREASURY_BY","PAYMENT_CREATE","TRANSFER_AMOUNT","PAYMENT_DATE","DIFFERENCE","STATUS","AR_NETTING","AR_AMOUNT","AR_INVOICE_DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
*/
		if($keywords != ""){
			$fieldToSearch = array("GH.TGL_INVOICE"," GH.NO_JOURNAL","GH.NAMA_VENDOR","GH.NO_INVOICE","GH.NO_KONTRAK","GH.NO_FPJP","GH.NAMA_REKENING","GH.NAMA_BANK","GH.ACCT_NUMBER","GH.RKAP_NAME","GH.TOP","GH.FAKTUR_PAJAK","GH.NPWP","GH.NATURE","GH.PAYMENT_STATUS","GH.AR_NETTING");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where_filter = "";
		if($filterdateby == '1'){
			$where_filter = " and CONVERT(GH.TGL_INVOICE, DATE) BETWEEN ? and ? ";
		}elseif($filterdateby == '2'){
			$where_filter = " and CONVERT(GH.APPROVED_DATE, DATE) BETWEEN ? and ?";
		}else{
			$where_filter = " and GH.NO_JOURNAL = 'xx' ";
		}

		/*$mainQuery = "SELECT *

		FROM $this->view_invoicetracker

		where 1=1 $where_filter
		$where";*/

		$mainQuery = "SELECT DATE_FORMAT(GH.TGL_INVOICE, '%d-%M-%y') AS TANGGAL,
				   GH.NO_JOURNAL AS NO_JOURNAL, GH.NAMA_VENDOR AS NAMA_VENDOR, GH.NO_INVOICE AS NO_INVOICE,
				   GH.CURRENCY AS CURRENCY,
				   (
					    SELECT SUM(gat.CREDIT) AS tes FROM GL_JOURNAL_AFTER_TAX gat
					    	WHERE ( gat.NATURE = '21122001' OR gat.NATURE = '21121001' ) AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY GH.NO_JOURNAL
					) AS TOTAL_AMOUNT,
					group_concat(GH.DESCRIPTION separator ',') AS DESCRIPTION,
					GH.NO_KONTRAK AS NO_KONTRAK,
					GH.NO_FPJP AS NO_FPJP,
					SUM(GH.DPP) AS DPP,
					(
					    SELECT SUM(gat.DEBET) AS tes FROM GL_JOURNAL_AFTER_TAX gat
			    			WHERE gat.NATURE = GH.NATURE_PPN AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY  GH.NO_JOURNAL
					) AS PPN,
					SUM(GH.DPP) + (
								    SELECT SUM(gat.DEBET) AS tes FROM GL_JOURNAL_AFTER_TAX gat
								    WHERE gat.NATURE = GH.NATURE_PPN AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
								    GROUP BY GH.NO_JOURNAL
								) AS SUB_TOTAL,
					(
					    SELECT SUM(gat.CREDIT) AS tes FROM GL_JOURNAL_AFTER_TAX gat
					    WHERE gat.NATURE = GH.NATURE_PPH AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY
					        GH.NO_JOURNAL
					) AS PPH,
					(
					    SELECT SUM(gat.CREDIT) AS tes FROM GL_JOURNAL_AFTER_TAX gat
					    WHERE ( gat.NATURE = '21122001' OR gat.NATURE = '21121001' ) AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY GH.NO_JOURNAL
					) AS TOTAL,
					GH.NAMA_REKENING AS NAMA_REKENING,
					GH.NAMA_BANK AS NAMA_BANK,
					GH.ACCT_NUMBER AS ACCT_NUMBER,
					GH.RKAP_NAME AS RKAP_NAME,
					GH.TOP AS TOP,
					DATE_FORMAT(GH.CREATED_DATE, '%d-%M-%y') AS TAX_VERIFICATION_DATE,
					GH.FAKTUR_PAJAK AS NO_SERI_FAKTUR_PAJAK,
					GH.NPWP AS NOMOR_NPWP,
					GH.TGL_FAKTUR_PAJAK AS TGL_FAKTUR_PAJAK,
					GH.AMOUNT_BASE_FEE AS AMOUNT_BASE_FEE,
					GH.NOTES AS NOTES,
					CASE SUBSTR(GH.BATCH_NAME, 1, 2)WHEN 'EB' THEN 'ELPA' WHEN 'AN' THEN 'Anis_N_A' END AS JOURNAL_AP_BY,
					GH.NATURE AS NATURE,
					(
						SELECT DISTINCT MASTER_GROUP.GROUP_REPORT FROM MASTER_GROUP
							WHERE MASTER_GROUP.ID_GROUP = ( SELECT MC.ID_GROUP FROM MASTER_COA MC WHERE MC.NATURE = GH.NATURE )
					) AS COA_PARENT,
					DATE_FORMAT(GH.DUE_DATE, '%d-%M-%y') AS DUE_DATE,
					CASE SUBSTR(GH.BATCH_NAME, 1, 2) WHEN 'EB' THEN 'ELPA' WHEN 'AN' THEN 'Anis_N_A' END AS HAND_OVER_TO_TREASURY_BY,
					DATE_FORMAT(BP.CREATED_DATE, '%d-%M-%y') AS PAYMENT_CREATE,
					case when (GH.PAYMENT_STATUS = 'RECONCILED') then BP.DPP else 0 end TRANSFER_AMOUNT,
					(select distinct date_format(pcb.TRANSACTION_DATE,'%d-%M-%y') from POST_CLEARING_BANK pcb where pcb.REFERENCE = BP.NO_JOURNAL limit 1) AS PAYMENT_DATE,
					0 AS DIFFERENCE, GH.PAYMENT_STATUS AS STATUS, GH.AR_NETTING AS AR_NETTING, GH.AR_DEBIT AS AR_AMOUNT, GH.AR_DESCRIPTION AS AR_INVOICE_DESCRIPTION FROM ( GL_HEADERS GH LEFT JOIN BATCH_PAYMENT BP ON ( GH.GL_HEADER_ID = BP.GL_HEADER_ID AND BP.APPROVAL1 <> 2 )
								)
					WHERE GH.APPROVED = 'Y'
					$where_filter
					$where
					GROUP BY GH.NO_JOURNAL
					ORDER BY GH.TGL_INVOICE";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($invoice_date_from, $invoice_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($invoice_date_from, $invoice_date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	
	}


	function get_download_invoice_tracker_inquiry($date_from,$date_to,$filterdateby)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;
		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);
		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		
		$where = "";

		$where_filter = "";
		if($filterdateby == "1"){
			$where_filter = " and CONVERT(GH.TGL_INVOICE, DATE) BETWEEN ? and ? ";
		}elseif($filterdateby == "2"){
			$where_filter = " and CONVERT(GH.APPROVED_DATE, DATE) BETWEEN ? and ? ";
		}else{
			$where_filter = " and GH.NO_JOURNAL = 'xx' ";
		}

		// $queryExec = " SELECT * from $this->view_invoicetracker where 1=1 $where_filter $where ";

		$queryExec = "SELECT DATE_FORMAT(GH.TGL_INVOICE, '%d-%M-%y') AS TANGGAL,
				   GH.NO_JOURNAL AS NO_JOURNAL, GH.NAMA_VENDOR AS NAMA_VENDOR, GH.NO_INVOICE AS NO_INVOICE,
				   GH.CURRENCY AS CURRENCY,
				   (
					    SELECT SUM(gat.CREDIT) AS tes FROM GL_JOURNAL_AFTER_TAX gat
					    	WHERE ( gat.NATURE = '21122001' OR gat.NATURE = '21121001' ) AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY GH.NO_JOURNAL
					) AS TOTAL_AMOUNT,
					group_concat(GH.DESCRIPTION separator ',') AS DESCRIPTION,
					GH.NO_KONTRAK AS NO_KONTRAK,
					GH.NO_FPJP AS NO_FPJP,
					SUM(GH.DPP) AS DPP,
					(
					    SELECT SUM(gat.DEBET) AS tes FROM GL_JOURNAL_AFTER_TAX gat
			    			WHERE gat.NATURE = GH.NATURE_PPN AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY  GH.NO_JOURNAL
					) AS PPN,
					SUM(GH.DPP) + (
								    SELECT SUM(gat.DEBET) AS tes FROM GL_JOURNAL_AFTER_TAX gat
								    WHERE gat.NATURE = GH.NATURE_PPN AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
								    GROUP BY GH.NO_JOURNAL
								) AS SUB_TOTAL,
					(
					    SELECT SUM(gat.CREDIT) AS tes FROM GL_JOURNAL_AFTER_TAX gat
					    WHERE gat.NATURE = GH.NATURE_PPH AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY
					        GH.NO_JOURNAL
					) AS PPH,
					(
					    SELECT SUM(gat.CREDIT) AS tes FROM GL_JOURNAL_AFTER_TAX gat
					    WHERE ( gat.NATURE = '21122001' OR gat.NATURE = '21121001' ) AND gat.JOURNAL_DESCRIPTION = GH.NO_JOURNAL
					    GROUP BY GH.NO_JOURNAL
					) AS TOTAL,
					GH.NAMA_REKENING AS NAMA_REKENING,
					GH.NAMA_BANK AS NAMA_BANK,
					GH.ACCT_NUMBER AS ACCT_NUMBER,
					GH.RKAP_NAME AS RKAP_NAME,
					GH.TOP AS TOP,
					DATE_FORMAT(GH.CREATED_DATE, '%d-%M-%y') AS TAX_VERIFICATION_DATE,
					GH.FAKTUR_PAJAK AS NO_SERI_FAKTUR_PAJAK,
					GH.NPWP AS NOMOR_NPWP,
					GH.TGL_FAKTUR_PAJAK AS TGL_FAKTUR_PAJAK,
					GH.AMOUNT_BASE_FEE AS AMOUNT_BASE_FEE,
					GH.NOTES AS NOTES,
					CASE SUBSTR(GH.BATCH_NAME, 1, 2)WHEN 'EB' THEN 'ELPA' WHEN 'AN' THEN 'Anis_N_A' END AS JOURNAL_AP_BY,
					GH.NATURE AS NATURE,
					(
						SELECT DISTINCT MASTER_GROUP.GROUP_REPORT FROM MASTER_GROUP
							WHERE MASTER_GROUP.ID_GROUP = ( SELECT MC.ID_GROUP FROM MASTER_COA MC WHERE MC.NATURE = GH.NATURE )
					) AS COA_PARENT,
					DATE_FORMAT(GH.DUE_DATE, '%d-%M-%y') AS DUE_DATE,
					CASE SUBSTR(GH.BATCH_NAME, 1, 2) WHEN 'EB' THEN 'ELPA' WHEN 'AN' THEN 'Anis_N_A' END AS HAND_OVER_TO_TREASURY_BY,
					DATE_FORMAT(BP.CREATED_DATE, '%d-%M-%y') AS PAYMENT_CREATE,
					case when (GH.PAYMENT_STATUS = 'RECONCILED') then BP.DPP else 0 end TRANSFER_AMOUNT,
					(select distinct date_format(pcb.TRANSACTION_DATE,'%d-%M-%y') from POST_CLEARING_BANK pcb where pcb.REFERENCE = BP.NO_JOURNAL limit 1) AS PAYMENT_DATE,
					0 AS DIFFERENCE, GH.PAYMENT_STATUS AS STATUS, GH.AR_NETTING AS AR_NETTING, GH.AR_DEBIT AS AR_AMOUNT, GH.AR_DESCRIPTION AS AR_INVOICE_DESCRIPTION FROM ( GL_HEADERS GH LEFT JOIN BATCH_PAYMENT BP ON ( GH.GL_HEADER_ID = BP.GL_HEADER_ID AND BP.APPROVAL1 <> 2 )
								)
					WHERE GH.APPROVED = 'Y'
					$where_filter
					GROUP BY GH.NO_JOURNAL
					ORDER BY GH.TGL_INVOICE";

		$query     = $this->db->query($queryExec, array($invoice_date_from,$invoice_date_to));

		return $query;
	}

}



/* End of file Invoicetracker_mdl.php */

/* Location: ./application/models/Invoicetracker_mdl.php */