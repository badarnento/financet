<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appr_mdl extends CI_Model {

	protected 	$tbl_status_po 	= "REPORT_STATUS_PO";

	public function get_appr($date_from, $date_to){
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
			$fieldToSearch = array("NO_INVOICE","NO_JOURNAL");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT TGL_INVOICE RECEIVE_DATE,
						INVOICE_DATE TANGGAL_INVOICE,
						DUE_DATE,
						NO_JOURNAL,
						NO_INVOICE, NO_KONTRAK, NO_FPJP,
						NAMA_VENDOR, 
						case when
						APPROVED_INVOICE = 'Y' then 'Approved'
						when APPROVED_INVOICE = 'N' then 'Not yet Approved'
						else ''
						end APPROVED_INVOICE,
						APPROVED_INVOICE_DATE,
						VALIDATED,
						VALIDATE_DATE_TAX,
						VERIFICATED,
						VERIFICATED_DATE,
						APPROVED, APPROVED_DATE,
						APPROVED_HOU, APPROVED_HOU_DATE
						FROM GL_HEADERS
						WHERE TGL_INVOICE BETWEEN ? AND ?
						ORDER BY 1 DESC ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_cetak($date_from, $date_to){

		$sql = " SELECT TGL_INVOICE RECEIVE_DATE,
					INVOICE_DATE TANGGAL_INVOICE,
					DUE_DATE,
					BATCH_NAME,
					NO_JOURNAL,
					NO_INVOICE, NO_KONTRAK, NO_FPJP,
					NAMA_VENDOR, 
					case when
					APPROVED_INVOICE = 'Y' then 'Approved'
					when APPROVED_INVOICE = 'N' then 'Not yet Approved'
					else ''
					end APPROVED_INVOICE,
					APPROVED_INVOICE_DATE,
					VALIDATED,
					VALIDATE_DATE_TAX,
					VERIFICATED,
					VERIFICATED_DATE,
					APPROVED, APPROVED_DATE,
					APPROVED_HOU, APPROVED_HOU_DATE
					FROM GL_HEADERS
					WHERE TGL_INVOICE BETWEEN ? AND ?
					ORDER BY 1 DESC ";

		$query = $this->db->query($sql, array($date_from, $date_to));

		return $query;
	}

}