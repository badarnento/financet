<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_mdl extends CI_Model {

	protected   $tbl_pr_header  = "PR_HEADER",
				$tbl_pr_lines          = "PR_LINES",
				$tbl_pr_detail         = "PR_DETAIL",
				$tbl_fs                = "FS_BUDGET",
				$tbl_fs_lines          = "FS_BUDGET_LINES",
				$tbl_rkap_view         = "BUDGET_HEADER",
				$tbl_po_header         = "PO_HEADER",
				$tbl_po_lines          = "PO_LINES",
				$tbl_po_detail         = "PO_DETAIL",
				$tbl_pr_detail_staging = "PR_DETAIL_STAGING",
				$tbl_po_detail_staging = "PO_DETAIL_STAGING",
				$tbl_master_coa        = "MASTER_COA", 
				$tbl_direktorat        = "MASTER_DIRECTORAT",
				$tbl_division          = "MASTER_DIVISION",
				$tbl_master_approval   = "MASTER_APPROVAL",
				$tbl_trx_approval_pr   = "TRX_APPROVAL_PR",
				$tbl_trx_approval_po   = "TRX_APPROVAL_PO",
				$tbl_fs_view           = "BUDGET_FINANCE_STUDY",
				$tbl_unit              = "MASTER_UNIT";
	public function get_last_pr_number($id_dir_code){

		$this->db->select("PR_NUMBER");
		$this->db->where("ID_DIR_CODE", $id_dir_code);
		$this->db->order_by("PR_HEADER_ID", "DESC");

		$query = $this->db->get($this->tbl_pr_header);

		return $query->row()->PR_NUMBER;

	}


	public function get_pr_for_po_inquiry($date_from, $date_to){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_NUMBER","PR_NAME","CURRENCY");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT *
						FROM $this->tbl_pr_header
						where 1=1
						and CONVERT(PR_DATE, DATE) BETWEEN ? and ?
						and status = 'Approved'
						$where order by PR_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}

	public function get_pr_for_po($buyer, $id_dir_code=false, $date_from=false, $date_to=false){

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
			$fieldToSearch = array("PR_NUMBER", "PR_NAME", "PR_AMOUNT", "CURRENCY");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		if($buyer):
			$where .= "  and (PR_CATEGORY is not null and PO_BUYER = ?)";
			$whereVal[] = $buyer;
		endif;

		if($id_dir_code):
			$where .= " and ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		endif;

		if($date_from == true && $date_to== true ):
			$where .= " and CONVERT(PR_DATE, DATE) BETWEEN ? and ?";
			$whereVal[] = $date_from;
			$whereVal[] = $date_to;
		endif;


		$mainQuery = "SELECT *
						FROM $this->tbl_pr_header
						where 1=1
						and STATUS = 'approved'
						$where
						order by PR_HEADER_ID DESC";
		// echo $mainQuery;die;
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_purchase_header($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to){

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
			$fieldToSearch = array("PR_NUMBER", "PR_NAME", "PR_AMOUNT", "CURRENCY");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}

		$mainQuery = "SELECT *
						FROM $this->tbl_pr_header
						where 1=1
						and CONVERT(PR_DATE, DATE) BETWEEN ? and ?
						$where
						order by PR_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_download_pr($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to)
	{
		$where    = "";
		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and PR.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and PR.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and PR.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}

		$queryExec = "SELECT PR.*, DIR.DIRECTORAT_NAME, DV.DIVISION_NAME, UN.UNIT_NAME
						FROM  $this->tbl_pr_header PR
						INNER JOIN  $this->tbl_direktorat DIR on PR.ID_DIR_CODE = DIR.ID_DIR_CODE
						INNER JOIN  $this->tbl_division DV on PR.ID_DIVISION = DV.ID_DIVISION
						INNER JOIN  $this->tbl_unit UN on PR.ID_UNIT = UN.ID_UNIT
						where 1=1
						AND DATE(PR_DATE) BETWEEN ? and ?
						$where
						order by PR.PR_HEADER_ID DESC";

		return $this->db->query($queryExec, $whereVal)->result_array();
	}

	public function get_download_po($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to)
	{
		
		$where = "";

		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and prh.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and prh.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and prh.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and poh.STATUS = ?";
			$whereVal[] = $status;
		}

		$queryExec = "SELECT 
		                dr.DIRECTORAT_NAME,
					    dv.DIVISION_NAME,
					    un.UNIT_NAME,
						poh.PO_HEADER_ID, 
						pol.PO_NUMBER, 
						pol.PO_LINE_DESC as PO_NAME, 
						poh.STATUS,
						poh.PO_DATE, 
						sum(pol.PO_LINE_AMOUNT) TOTAL_AMOUNT, 
						prh.PR_NAME,
						pol.VENDOR_NAME, 
						pol.VENDOR_BANK_NAME,
						pol.VENDOR_BANK_ACCOUNT, 
						prh.CURRENCY, 
						prh.PR_NUMBER
						FROM $this->tbl_po_header poh
						INNER JOIN  $this->tbl_po_lines pol on poh.po_header_id = pol.po_header_id
						INNER JOIN  $this->tbl_pr_header prh on poh.id_pr_header_id = prh.pr_header_id
						INNER JOIN  $this->tbl_direktorat dr on prh.ID_DIR_CODE = dr.ID_DIR_CODE
						INNER JOIN  $this->tbl_division dv on prh.ID_DIVISION = dv.ID_DIVISION
						INNER JOIN  $this->tbl_unit un on prh.ID_UNIT = un.ID_UNIT
						where 1=1
						and CONVERT(PO_DATE, DATE) BETWEEN ? and ?
						$where
						GROUP BY pol.PO_NUMBER
						order by PO_HEADER_ID DESC";

		$query = $this->db->query($queryExec, $whereVal);

		return $query;
	}

	public function get_po_header($status, $date_from, $date_to){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_NUMBER","PR_NAME","pr.CURRENCY");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($status){
			$where .= " and poh.STATUS = ?";
		}else{
			$where .= " and poh.STATUS > ?";
		}

		$mainQuery = "SELECT poh.PO_HEADER_ID, poh.PO_NUMBER, poh.PO_NAME, poh.STATUS,
						poh.PO_DATE, poh.PO_AMOUNT, prh.PR_NAME,
						poh.VENDOR_NAME, poh.VENDOR_BANK_NAME, poh.VENDOR_BANK_ACCOUNT, prh.CURRENCY, prh.PR_NUMBER, poh.MPA_REFERENCE
						FROM $this->tbl_po_header poh
						INNER JOIN $this->tbl_pr_header prh
							ON poh.id_pr_header_id = prh.pr_header_id
						where 1=1
						and CONVERT(PO_DATE, DATE) BETWEEN ? and ?
						$where
						order by PO_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to, $status))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to, $status))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}

	public function get_data_po($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to){

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
			$fieldToSearch = array("PR_NUMBER", "PL.PO_NUMBER", "PO_LINE_DESC", "PO_LINE_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and PR.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and PR.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and PR.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and PH.STATUS = ?";
			$whereVal[] = $status;
		}

		$mainQuery = "SELECT PL.PO_HEADER_ID, PL.PO_NUMBER, PL.PO_LINE_DESC,
						sum(PL.PO_LINE_AMOUNT) PO_AMOUNT, PL.VENDOR_NAME, PL.VENDOR_BANK_NAME,
						PL.VENDOR_BANK_ACCOUNT, PR.CURRENCY, PH.CURRENCY CURRENCY_PO, PR.PR_NUMBER, PH.PO_DATE,
						PH.STATUS, PR.PR_HEADER_ID, PR.ID_DIR_CODE, PR.ID_DIVISION, PR.ID_UNIT
						FROM PO_LINES PL
						INNER JOIN PO_HEADER PH ON PL.po_header_id = PH.po_header_id
						INNER JOIN PR_HEADER PR ON PH.ID_PR_HEADER_ID = PR.PR_HEADER_ID
						WHERE 1=1
						AND CONVERT(PL.CREATED_DATE, DATE) BETWEEN ? AND ?
						$where
                        GROUP BY PL.PO_NUMBER
						ORDER BY PH.PO_HEADER_ID DESC, PL.PO_NUMBER";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_po_reference($directorat, $division, $unit){

		// $this->db->where("prh.ID_DIR_CODE", $directorat);
		// $this->db->where("prh.ID_DIVISION", $division);
		// $this->db->where("prh.ID_UNIT", $unit);

		$this->db->select("pol.PO_NUMBER, pol.PO_LINE_DESC");
		$this->db->join($this->tbl_po_header." poh","pol.PO_HEADER_ID = poh.PO_HEADER_ID");
		$this->db->join($this->tbl_pr_header." prh","poh.ID_PR_HEADER_ID = prh.PR_HEADER_ID");
		$this->db->order_by("pol.PO_NUMBER");
		// $this->db->where("poh.STATUS", 'approved');
		$this->db->or_where("poh.STATUS", 'approved');
		$this->db->or_where("poh.STATUS", 'PAID');
		$this->db->or_where("poh.STATUS", 'PARTIAL PAID');
        $this->db->distinct();

        $query = $this->db->get($this->tbl_po_lines." pol");

		$result = $query->result_array();

        return $result;
	}

	public function get_fa_fs_by_pr_id( $pr_header_id ){

		$sql = "SELECT ID_RKAP_LINE, FA_FS FROM $this->tbl_fs_view where ID_FS = (SELECT ID_FS FROM PR_HEADER WHERE PR_HEADER_ID = ?)";

		return $this->db->query($sql, $pr_header_id)->result_array();

	}

	public function get_purchase_lines($pr_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("PR_LINE_NAME", "FS_NAME", "PR_NAME", "PR_LINE_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and prh.PR_HEADER_ID = ?";

		$mainQuery = "SELECT prl.PR_LINES_ID, prl.IS_SHOW, prl.ID_RKAP_LINE, prl.PR_LINES_NUMBER, prl.PR_LINE_AMOUNT,
								prl.PR_LINE_NAME, prh.ID_DIR_CODE, rv.RKAP_DESCRIPTION, rv.MONTH,
								rv.TRIBE_USECASE, rvs.FA_FS, fs.FS_DESCRIPTION, fs.FS_NUMBER
						FROM $this->tbl_pr_lines prl
						LEFT JOIN $this->tbl_pr_header prh ON prl.PR_HEADER_ID = prh.PR_HEADER_ID
						LEFT JOIN $this->tbl_rkap_view rv ON prl.ID_RKAP_LINE = rv.ID_RKAP_LINE
						LEFT JOIN $this->tbl_fs fs ON prl.ID_FS = fs.ID_FS
						LEFT JOIN $this->tbl_fs_view rvs ON prl.ID_RKAP_LINE = rvs.ID_RKAP_LINE and rvs.ID_fS = fs.ID_fS
						where 1=1
						$where
						order by prl.PR_LINES_NUMBER + 0 ASC ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pr_header_id)->num_rows();
		$data  = $this->db->query($queryData, $pr_header_id)->result_array();
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_po_lines($po_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("PO_NUMBER", "PR_LINE_AMOUNT", "PO_LINE_AMOUNT", "PR_LINE_NAME", "PO_LINE_DESC");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and pol.po_header_id = ?";

		$mainQuery = "SELECT pol.PO_LINE_ID, pol.PO_LINE_DESC, pol.PO_LINE_AMOUNT, pol.PO_NUMBER,
								pol.VENDOR_NAME, pol.VENDOR_BANK_NAME, pol.VENDOR_BANK_ACCOUNT, pol.VENDOR_BANK_ACCOUNT_NAME, pol.PO_PERIOD_FROM, pol.PO_PERIOD_TO,
								prl.ID_RKAP_LINE, prl.PR_LINE_AMOUNT,
								prl.PR_LINE_NAME, PR_VERSION, PR_AMOUNT
						FROM $this->tbl_po_lines pol
						LEFT JOIN $this->tbl_pr_lines prl ON pol.PR_LINES_ID = prl.PR_LINES_ID
						LEFT JOIN PR_HEADER prh ON prh.PR_HEADER_ID = prl.PR_HEADER_ID
						where 1=1
						$where
						order by pol.po_line_id
						";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $po_header_id)->num_rows();
		$data  = $this->db->query($queryData, $po_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_po_header_by_id($po_header_id){

		$po_header_id = (int) $po_header_id;
		$this->db->where("PO_HEADER_ID", $po_header_id);

		$this->db->select("PRH.PR_HEADER_ID, PR_NUMBER, PR_NAME, PO_DATE, PO_AMOUNT, PRH.CURRENCY, PRH.CURRENCY_RATE, ID_DIR_CODE, ID_DIVISION, ID_UNIT, PO_NAME, PO_TYPE, PO_REFERENCE, PO.STATUS, DOCUMENT_SOURCING, PO.STATUS_DESCRIPTION, PO.UPDATED_DATE, PO.MPA_REFERENCE, PO.CREATED_DATE, PO.TOP, PO.ESTIMATE_DATE, PO.NOTES, PO_BUYER, PO_CATEGORY, PO_RESUBMIT_DATE, PO.RESUBMIT_BY, PO.BUYER, PO.CURRENCY CURRENCY_PO, PO.CURRENCY_RATE CURRENCY_RATE_PO, DOCUMENT_CLAUSE");
		$this->db->join($this->tbl_pr_header." PRH","PRH.PR_HEADER_ID = ID_PR_HEADER_ID");

        $query = $this->db->get($this->tbl_po_header." PO");

        return $query->row_array();
	}


	public function get_pr_for_po_create($pr_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_LINE_NAME", "PR_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and prh.pr_header_id = ?";

		$mainQuery = "SELECT prl.PR_LINES_ID, prl.ID_RKAP_LINE, prl.PR_LINES_NUMBER, prl.PR_LINE_AMOUNT, prl.PR_LINE_NAME, prh.PR_AMOUNT,
								prh.ID_DIR_CODE, rv.DIVISION, rv.RKAP_DESCRIPTION, rv.UNIT, rv.TRIBE_USECASE, rv.FA_FS,
								pol.PO_NUMBER, pol.PO_LINE_DESC, IFNULL(pol.PO_LINE_AMOUNT, 0) PO_AMOUNT, pol.VENDOR_NAME,  pol.VENDOR_BANK_NAME,  pol.VENDOR_BANK_ACCOUNT, pol.PO_PERIOD_FROM, pol.PO_PERIOD_TO, pol.VENDOR_BANK_ACCOUNT_NAME,prh.CURRENCY_RATE
						FROM $this->tbl_pr_lines prl
						LEFT JOIN $this->tbl_pr_header prh ON prl.pr_header_id = prh.pr_header_id
						LEFT JOIN $this->tbl_rkap_view rv ON prl.id_rkap_line = rv.id_rkap_line
						LEFT JOIN $this->tbl_po_lines pol on prl.pr_lines_id = pol.pr_lines_id
						where 1=1
						AND IS_SHOW = 1
						$where
						order by prl.pr_lines_number";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pr_header_id)->num_rows();
		$data  = $this->db->query($queryData, $pr_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_po_detail_staging($pr_lines_id){

		$query = "SELECT *
					FROM $this->tbl_po_detail_staging
					WHERE id IN (
					    SELECT MAX(id)
					    FROM $this->tbl_po_detail_staging
					    GROUP BY pr_detail_id
					)
					and PR_LINES_ID = ?
					order by PR_DETAIL_ID";

		$data  = $this->db->query($query, $pr_lines_id)->result_array();

		return $data;

	}

	public function get_pr_detail_staging($pr_header_id, $pr_line_id){

		$mainQuery = "SELECT *
					FROM $this->tbl_pr_detail_staging
					WHERE id IN (
					    SELECT MAX(id)
					    FROM $this->tbl_pr_detail_staging
					    GROUP BY PR_DETAIL_NUMBER
					)
					and PR_HEADER_ID = ?
					and PR_LINE_ID = ?
					order by PR_DETAIL_NUMBER";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($pr_header_id, $pr_line_id))->num_rows();
		$data  = $this->db->query($queryData, array($pr_header_id, $pr_line_id))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;

	}

	public function get_detail_po($po_line_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_DETAIL_DESC", "PR_DETAIL_AMOUNT", "DESCRIPTION_PO", "PO_DETAIL_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
	
		$where .= " and pod.PO_LINE_ID = ?";

		$mainQuery = "SELECT pod.PO_DETAIL_NUMBER, pod.DESCRIPTION_PO, pod.PO_DETAIL_AMOUNT, pod.PO_DETAIL_ID,
						prd.QUANTITY, prd.PRICE, prd.PR_DETAIL_AMOUNT,
						prd.PR_DETAIL_NAME,	prd.PR_DETAIL_DESC, pod.ITEM_NAME, pod.PRICE PRICE_PO, pod.QUANTITY QTY_PO, prd.UOM, prd.CATEGORY_ITEM, IFNULL(pod.PO_DETAIL_AMOUNT, 0) PO_AMOUNT_DETAIL
						FROM $this->tbl_po_detail pod
						LEFT JOIN $this->tbl_pr_detail prd ON pod.pr_detail_id = prd.pr_detail_id
						where 1=1
						$where
						order by pod.po_detail_number";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $po_line_id)->num_rows();
		$data  = $this->db->query($queryData, $po_line_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_purchase_details($pr_lines_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_DETAIL_DESC", "PR_DETAIL_AMOUNT", "DESCRIPTION", "NATURE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and prl.pr_lines_id = ?";

		$mainQuery = "SELECT prd.PR_LINES_ID, prd.PR_DETAIL_ID, prd.PR_DETAIL_NUMBER,
						prd.QUANTITY, prd.PRICE, prd.PR_DETAIL_AMOUNT, prd.PR_DETAIL_NAME,
						prd.PR_DETAIL_DESC, prd.UOM,
						IFNULL(OS.VALUE_DESCRIPTION, mc.DESCRIPTION) DESCRIPTION, IFNULL(OS.FLEX_VALUE, mc.NATURE) NATURE, prd.CATEGORY_ITEM, mc.ID_MASTER_COA, prd.GOODS_SERVICES
						FROM $this->tbl_pr_detail prd
						LEFT JOIN $this->tbl_pr_lines prl ON prd.pr_lines_id = prl.pr_lines_id
						LEFT JOIN ORACLE_SEGMENT5 OS ON prd.ID_MASTER_COA = OS.ID_MASTER_COA
						LEFT JOIN MASTER_COA mc ON prd.ID_MASTER_COA = mc.ID_MASTER_COA
						where 1=1
						$where
						order by prd.pr_detail_number + 0 ASC ";


		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pr_lines_id)->num_rows();
		$data  = $this->db->query($queryData, $pr_lines_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_pr_detail_for_edit($pr_lines_id){

		$mainQuery = "SELECT prd.PR_LINES_ID, prd.PR_DETAIL_ID, prd.PR_DETAIL_NUMBER,
						prd.QUANTITY, prd.PRICE, prd.PR_DETAIL_AMOUNT, prd.PR_DETAIL_NAME,
						prd.PR_DETAIL_DESC, prd.UOM, prd.CATEGORY_ITEM, prd.GOODS_SERVICES
						FROM $this->tbl_pr_detail prd
						LEFT JOIN $this->tbl_pr_lines prl ON prd.pr_lines_id = prl.pr_lines_id
						where 1=1
						and prl.pr_lines_id = ?
						order by prd.pr_detail_number + 0 ASC ";
		$data  = $this->db->query($mainQuery, $pr_lines_id)->result_array();

		return $data;	

	}

	public function get_pr_detail_for_po($pr_lines_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_DETAIL_DESC", "PR_DETAIL_AMOUNT", "DESCRIPTION", "NATURE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and prl.pr_lines_id = ?";

		$mainQuery = "SELECT prd.PR_LINES_ID, prd.PR_DETAIL_ID, prd.PR_DETAIL_NUMBER, prd.PR_DETAIL_AMOUNT,
						prd.PR_DETAIL_DESC, prd.QUANTITY, prd.PRICE, pod.DESCRIPTION_PO, IFNULL(pod.PO_DETAIL_AMOUNT, 0) PO_AMOUNT_DETAIL, prd.UOM, pod.ITEM_NAME, pod.QUANTITY QTY_PO, pod.PRICE PRICE_PO, prd.PR_DETAIL_NAME, prd.CATEGORY_ITEM
						FROM $this->tbl_pr_detail prd
						LEFT JOIN $this->tbl_pr_lines prl ON prd.pr_lines_id = prl.pr_lines_id
						LEFT JOIN $this->tbl_po_detail pod ON prd.pr_detail_id = pod.pr_detail_id
						where 1=1
						$where
						order by prd.pr_detail_number + 0 ASC ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pr_lines_id)->num_rows();
		$data  = $this->db->query($queryData, $pr_lines_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_nominal_from_pr($id_rkap){

		$id_rkap = (int) $id_rkap;
		$this->db->where("ID_RKAP_LINE", $id_rkap);

		$this->db->select("PR_LINES_ID, PR_LINE_AMOUNT");

        $query = $this->db->get($this->tbl_pr_lines);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->row_array();

        return $result;

	}


	function get_pr_for_email($id_pr){

		$query = "SELECT PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, PRH.PR_NUMBER, PRH.PR_NAME,
							PRH.PR_AMOUNT, PRH.CURRENCY, PRH.CURRENCY_RATE,
							PRH.SUBMITTER, PRH.DOCUMENT_ATTACHMENT, PRL.ID_RKAP_LINE, DOCUMENT_UPLOAD
					FROM $this->tbl_pr_header PRH, $this->tbl_pr_lines PRL
					WHERE PRH.PR_HEADER_ID = ?
					AND PRL.PR_HEADER_ID = PRH.PR_HEADER_ID
					LIMIT 1";
					
		return $this->db->query($query, $id_pr)->row_array();

	}


	function check_is_approval($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("(PIC_LEVEL = 'HOG User' or PIC_LEVEL = 'Procurement')");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	function count_pending_pr($pic_email){

		$mainQuery = "SELECT COUNT(ID) TOTAL FROM $this->tbl_trx_approval_pr
							WHERE IS_ACTIVE  = 1
								AND ID_APPROVAL IN (SELECT ID_APPROVAL FROM $this->tbl_master_approval WHERE PIC_EMAIL = ? AND IS_EXIST = 1 AND IS_DELETED = 0)
								AND STATUS = 'request_approve'
								AND PR_HEADER_ID IN (SELECT PR_HEADER_ID FROM $this->tbl_pr_header WHERE STATUS = 'request_approve')";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}

	function count_pending_po($pic_email){

		$mainQuery = "SELECT COUNT(ID) TOTAL FROM $this->tbl_trx_approval_po
							WHERE IS_ACTIVE  = 1
								AND ID_APPROVAL IN (SELECT ID_APPROVAL FROM $this->tbl_master_approval WHERE PIC_EMAIL = ? AND IS_EXIST = 1 AND IS_DELETED = 0)
								AND STATUS = 'request_approve'
								AND PO_HEADER_ID IN (SELECT PO_HEADER_ID FROM $this->tbl_po_header WHERE STATUS = 'request_approve')";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}

	public function get_pr_to_approve_by_id($id_pr, $pic_email){

		$mainQuery = " SELECT PRH.*, TRX.LEVEL, MA.PIC_LEVEL, TRX.STATUS TRX_STATUS, TRX.UPDATED_DATE TRX_DATE
						FROM $this->tbl_master_approval MA
								inner join $this->tbl_trx_approval_pr TRX on MA.ID_APPROVAL = TRX.ID_APPROVAL AND TRX.IS_ACTIVE = 1
								inner join $this->tbl_pr_header PRH on TRX.PR_HEADER_ID = PRH.PR_HEADER_ID
								WHERE PRH.PR_HEADER_ID = ?
								AND MA.PIC_EMAIL = ?";

		$query  = $this->db->query($mainQuery, array($id_pr, $pic_email));

		return $query->row_array();

	}

	function get_approval_before($id_pr){

		$mainQuery = " SELECT PIC_NAME, PIC_EMAIL, REMARK, trx.UPDATED_DATE TRX_DATE
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval_pr trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								WHERE trx.PR_HEADER_ID = ?
								AND STATUS != 'request_approve'
								order by trx.UPDATED_DATE DESC";

		$query  = $this->db->query($mainQuery, $id_pr);

		return $query->row_array();

	}

	function get_approval_before_po($id_po){

		$mainQuery = " SELECT PIC_NAME, PIC_EMAIL, REMARK, IFNULL(trx.ACTION_DATE, trx.UPDATED_DATE) TRX_DATE
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval_po trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								WHERE trx.PO_HEADER_ID = ?
								AND STATUS != 'request_approve'
								order by IFNULL(trx.ACTION_DATE, trx.UPDATED_DATE) DESC LIMIT 1";

		$query  = $this->db->query($mainQuery, $id_po);

		return $query->row_array();

	}


	function get_submitter_by_id_pr($id_pr){
		$sql = "SELECT PIC_NAME, PIC_EMAIL, SUBMITTER FROM $this->tbl_pr_header PR
					LEFT JOIN $this->tbl_master_approval MA ON PR.ID_UNIT = MA.ID_UNIT
										AND PIC_LEVEL = 'Submitter'
										AND IS_EXIST = 1
					WHERE PR_HEADER_ID = ? LIMIT 1";

        $query = $this->db->query($sql, $id_pr);

        return $query->row_array();
	}

	public function get_approver($id_pr, $level, $is_bod=false, $except_level = 0){

		$whereArr[] = $id_pr;
		$where = "";

		if($is_bod){
			$where = " AND ta.category = 'BOD' ";

		}else{
			// $where = " AND ta.category != 'BOD' ";
			$where .= " AND ta.level = ?";
			$whereArr[] = $level;
		}

		if($except_level > 0){
			$where .= " AND ta.level != ?";
			$whereArr[] = $except_level;
		}

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval_pr ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.PR_HEADER_ID = ?
								AND ta.IS_ACTIVE = 1
								$where
								ORDER BY ta.id DESC";

		$query  = $this->db->query($mainQuery, $whereArr);

		if($is_bod){
			return $query->result_array();
		}else{
			return $query->row_array();
		}

	}

	function get_approval_by_pr($id_pr){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("IS_ACTIVE", 1);
		$this->db->where("PR_HEADER_ID", $id_pr);
        $query = $this->db->get($this->tbl_trx_approval_pr." TRX");

        return $query->result_array();
	}

	function get_pr_for_approval($pic_email){

		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->select("PR_HEADER_ID");
		$this->db->join($this->tbl_master_approval." MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");

        $query = $this->db->get($this->tbl_trx_approval_pr." TRX");

        return $query->result_array();

	}


	public function get_pr_to_approve($pic_email, $status=""){

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
			$fieldToSearch = array("PRH.PR_NUMBER", "PRH.PR_NAME", "PRH.PR_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
		$whereVal[] = $pic_email;

		if($status != ""):
			$where .= " AND trx.STATUS = ?";
			if($status == "request_approve"){
				$where .= " AND PRH.STATUS = 'request_approve'";
			}
			$whereVal[] = $status;
		endif;

		$mainQuery = " SELECT PRH.PR_HEADER_ID, PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, PRH.PR_NUMBER,
						PRH.PR_NAME, PRH.STATUS, PRH.STATUS_DESCRIPTION, PRH.PR_DATE, PRH.PR_AMOUNT, PRH.SUBMITTER,
						PRH.APPROVAL_LEVEL, trx.LEVEL, PRH.CURRENCY, PRH.CURRENCY_RATE, PRH.DOCUMENT_ATTACHMENT, ma.PIC_LEVEL, trx.ID ID_PR_APPROVAL, PRH.PO_BUYER, PRH.STATUS_ASSIGN
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval_pr trx on ma.ID_APPROVAL = trx.ID_APPROVAL AND trx.IS_ACTIVE = 1
								inner join $this->tbl_pr_header PRH on trx.PR_HEADER_ID = PRH.PR_HEADER_ID
								WHERE ma.PIC_EMAIL = ?
								AND trx.STATUS is not NULL
								$where
								ORDER BY PRH.UPDATED_DATE DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_approval_by_po($id_po){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("PO_HEADER_ID", $id_po);
		$this->db->where("TRX.IS_ACTIVE", 1);
        $query = $this->db->get($this->tbl_trx_approval_po." TRX");

        return $query->result_array();
	}

	function get_comment_history_po($id_po, $is_show=true){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, TRX.ACTION_DATE, REMARK, LEVEL");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("PO_HEADER_ID", $id_po);
		$this->db->where("STATUS !=", "request_approve");
		$this->db->where("STATUS IS NOT NULL");
		/*if($is_show == false){
			$this->db->where("MA.IS_SHOW", 1);
		}*/
		$this->db->order_by("TRX.ID");
        $query = $this->db->get($this->tbl_trx_approval_po." TRX");

        return $query->result_array();
	}

	public function get_cetak($pr_header_id){

		$queryExec	= " SELECT  PH.PR_NAME, PH.PR_NUMBER, FB.FS_NAME, PH.SUBMITTER, PH.DOCUMENT_CHECKLIST,
						IFNULL(OS.VALUE_DESCRIPTION, MC.DESCRIPTION) DESCRIPTION, IFNULL(OS.FLEX_VALUE, MC.NATURE) NATURE, MD.DIRECTORAT_NAME,
						MU.UNIT_NAME, PH.PR_AMOUNT, PH.CREATED_DATE, PH.STATUS, PH.STATUS_DESCRIPTION, PH.PO_BUYER,
						PH.STATUS_ASSIGN, PH.ASSIGN_DATE, PH.COA_REVIEW, PH.COA_REVIEW_DATE
						FROM PR_HEADER PH
						INNER JOIN PR_LINES PL ON PH.PR_HEADER_ID = PL.PR_HEADER_ID
						INNER JOIN PR_DETAIL PD ON PD.PR_LINES_ID = PL.PR_LINES_ID
						LEFT JOIN FS_BUDGET FB ON PL.ID_FS = FB.ID_FS
						LEFT JOIN MASTER_DIRECTORAT MD ON PH.ID_DIR_CODE = MD.ID_DIR_CODE
						LEFT JOIN MASTER_UNIT MU ON PH.ID_UNIT = MU.ID_UNIT
						LEFT JOIN MASTER_COA MC ON PD.ID_MASTER_COA = MC.ID_MASTER_COA
						LEFT JOIN ORACLE_SEGMENT5 OS ON PD.ID_MASTER_COA = OS.ID_MASTER_COA
						WHERE 1=1
						AND PH.PR_HEADER_ID = ?  LIMIT 1";
		
		$data  = $this->db->query($queryExec, $pr_header_id);

		return $data->row_array();

	}

	function check_is_coa_review($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("PIC_LEVEL = 'Accounting'");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}


	function get_comment_history($id_pr, $is_show=true){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK, LEVEL");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("PR_HEADER_ID", $id_pr);
		$this->db->where("STATUS !=", "request_approve");
		$this->db->where("STATUS IS NOT NULL");
		/*if($is_show == false){
			$this->db->where("MA.IS_SHOW", 1);
		}*/
		$this->db->order_by("TRX.ID");
        $query = $this->db->get($this->tbl_trx_approval_pr." TRX");

        return $query->result_array();
	}

	

	public function get_header_po($po_header_id){

		$queryExec	= " SELECT pd.PO_HEADER_ID, 
						mv.NAMA_VENDOR,
						mv.ALAMAT, mv.NO_TLP,
						pd.DESCRIPTION_PO, 
						pd.QUANTITY, 
						pd.UOM, 
						pd.PRICE, 
						pl.PO_NUMBER, 
						pl.PO_PERIOD_FROM,
						ph.CURRENCY,
						ph.MPA_REFERENCE,
						ph.ESTIMATE_DATE,
						ph.TOP,
						ph.NOTES,
						ph.STATUS,
						ph.DOCUMENT_CLAUSE,
						ph.PO_CATEGORY,
						ifnull((select UPDATED_DATE FROM TRX_APPROVAL_PO WHERE PO_HEADER_ID = ph.PO_HEADER_ID AND STATUS = 'approved' AND IS_ACTIVE = 1 ORDER BY UPDATED_DATE DESC LIMIT 1),ph.CREATED_DATE) PO_APPROVED
						from MASTER_VENDOR mv, PO_LINES pl, PO_DETAIL pd, PO_HEADER ph
						where pl.PO_HEADER_ID = pd.PO_HEADER_ID
						and pd.PO_HEADER_ID = ph.PO_HEADER_ID
						and mv.NAMA_VENDOR = pl.VENDOR_NAME and pd.PO_HEADER_ID = ? ";

		$data  = $this->db->query($queryExec, $po_header_id);

		return $data->row_array();

	}

	public function get_template($po_header_id){

		$queryExec	= " select PO_CATEGORY, DOCUMENT_CLAUSE, STATUS
						from PO_HEADER
						where PO_HEADER_ID = ? ";

		$data  = $this->db->query($queryExec, $po_header_id);

		return $data->row_array();

	}

	public function get_cetak_po($po_header_id){

		$queryExec	= " select ITEM_NAME, DESCRIPTION_PO, 
						QUANTITY,
						UOM,
						PRICE
						from PO_DETAIL
						where PO_HEADER_ID = ?";

		$data           = $this->db->query($queryExec, $po_header_id)->result_array();
		$result['data'] = $data;
		return $result;

	}

	function check_is_approval_po($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("PIC_LEVEL IN ('CFO','CMO','CEO','CTO','COO','HOG Procurement','CFO','CEO','HOU Procurement','HoU Legal','HoG Legal','HOG User')");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	public function count_outstanding_pr($email){

		$result = $this->db->query("SELECT COUNT(PR_HEADER_ID) TOTAL FROM $this->tbl_pr_header
									WHERE STATUS = 'approved' AND STATUS_ASSIGN = 'Y' and PO_BUYER = ?", $email)->row_array();

		return $result;

	}

	function check_is_pr_assigner($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("PIC_LEVEL = 'HOU Procurement'");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	public function get_category_pr($buyer_email=""){
		$this->db->order_by("CATEGORY");
		$this->db->where("CATEGORY != '' AND CATEGORY != 'NULL' AND CATEGORY IS NOT NULL");
		if($buyer_email != ""){
			$this->db->where("BUYER_EMAIL", $buyer_email);
		}
		$this->db->select("ID_SPEND, CATEGORY, BUYER_EMAIL");
        $this->db->group_by("CATEGORY, BUYER_EMAIL");

        $query = $this->db->get("MASTER_SPEND_CATEGORY_BUYER");

        return $query->result_array();
		
	}

	public function get_counter_buyer($email){
		return $this->db->query("SELECT
						(SELECT COUNT(*) FROM PR_HEADER
						WHERE STATUS = 'approved'
						and (PR_CATEGORY is not null and PO_BUYER = ? )
						) PR_PENDING,
						(SELECT COUNT(*) from PO_HEADER
						WHERE STATUS = 'request_approve') PO_PENDING,
						(SELECT COUNT(*) from PO_HEADER
						WHERE STATUS = 'approved') PO_SUCCESS
						FROM DUAL", $email)->row_array();
	}

	public function count_pending_assign_pr(){

		$result = $this->db->query("SELECT COUNT(PR_HEADER_ID) TOTAL FROM PR_HEADER
									WHERE STATUS = 'approved' AND STATUS_ASSIGN = 'N'")->row_array();

		return $result;

	}

	function get_last_update_pr($id_pr){
		$this->db->select("PIC_NAME, STATUS, REMARK, TRX.UPDATED_DATE");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("PR_HEADER_ID", $id_pr);
		$this->db->where("STATUS !=", "request_approve");
		$this->db->where("STATUS IS NOT NULL");
		$this->db->order_by("TRX.ID DESC");
		$this->db->limit(1);

        $query = $this->db->get($this->tbl_trx_approval_pr." TRX");

        return $query->row_array();
	}

	function get_po_for_email($id_po){

		$query = "SELECT POH.PO_AMOUNT, POH.DOCUMENT_SOURCING, POL.PO_NUMBER, POL.PO_LINE_DESC, POL.VENDOR_NAME, POD.ITEM_NAME, POD.DESCRIPTION_PO ITEM_DESC, POH.BUYER,
					POD.QUANTITY, POD.CATEGORY_ITEM, POD.UOM, POD.PRICE UNIT_PRICE, POD.PO_DETAIL_AMOUNT TOTAL_PRICE
				FROM $this->tbl_po_header POH
				INNER JOIN $this->tbl_po_lines POL ON POH.PO_HEADER_ID = POL.PO_HEADER_ID
				INNER JOIN $this->tbl_po_detail POD ON POL.PO_LINE_ID = POD.PO_LINE_ID
				WHERE POH.PO_HEADER_ID = ?";
					
		return $this->db->query($query, $id_po)->result_array();

	}

	function get_po_for_approval($pic_email){

		$this->db->where('TRX.IS_ACTIVE', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->select("PO_HEADER_ID");
		$this->db->join($this->tbl_master_approval." MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");

        $query = $this->db->get($this->tbl_trx_approval_po." TRX");

        return $query->result_array();

	}

	public function get_po_to_approve($pic_email, $status=""){

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
			$fieldToSearch = array("POL.PO_NUMBER", "POL.PO_LINE_DESC", "POH.PO_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
		$whereVal[] = $pic_email;

		if($status != ""):
			$where .= " AND trx.status = ?";
			if($status == "request_approve"){
				$where .= " AND POH.STATUS = 'request_approve'";
			}
			$whereVal[] = $status;
		endif;

		$mainQuery = "SELECT POH.PO_HEADER_ID,  POH.STATUS_DESCRIPTION, PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, POH.STATUS,
						POL.PO_NUMBER, POL.PO_LINE_DESC, POH.STATUS, POH.PO_DATE,
						POH.PO_AMOUNT, POH.BUYER, trx.LEVEL, POH.CURRENCY, POH.CURRENCY_RATE,
						ma.PIC_LEVEL, trx.ID ID_PO_APPROVAL
					FROM $this->tbl_master_approval ma
					inner join $this->tbl_trx_approval_po trx on ma.ID_APPROVAL = trx.ID_APPROVAL
					inner join $this->tbl_po_header POH on trx.PO_HEADER_ID = POH.PO_HEADER_ID
					inner join $this->tbl_pr_header PRH on POH.ID_PR_HEADER_ID = PRH.PR_HEADER_ID
					inner join $this->tbl_po_lines POL on POH.PO_HEADER_ID = POL.PO_HEADER_ID
					WHERE ma.PIC_EMAIL = ?
					AND trx.STATUS is not NULL
					AND trx.IS_ACTIVE = 1
					$where
					GROUP BY POL.PO_NUMBER ORDER BY POH.PO_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_po_to_approve_by_id($id_pr, $pic_email){

		$mainQuery = " SELECT POH.PO_HEADER_ID, (SELECT DISTINCT PO_NUMBER FROM PO_LINES WHERE PO_HEADER_ID = POH.PO_HEADER_ID LIMIT 1) PO_NUMBER, POH.STATUS, POH.PO_DATE, POH.PO_AMOUNT, PRH.PR_HEADER_ID,
						POH.BUYER, POH.APPROVAL_LEVEL, TRX.LEVEL, POH.DOCUMENT_SOURCING, MA.PIC_LEVEL,
						TRX.STATUS TRX_STATUS, TRX.UPDATED_DATE TRX_DATE,
						POH.UPDATED_DATE, POH.CREATED_DATE, POH.STATUS_DESCRIPTION, PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, PRH.PR_NUMBER, PRH.PR_NAME, PRH.PO_BUYER, PO_CATEGORY, MPA_REFERENCE, TOP, ESTIMATE_DATE, NOTES, DOCUMENT_CLAUSE, POH.BUYER, POH.BUYER_EMAIL, POH.CURRENCY CURRENCY_PO, POH.CURRENCY_RATE CURRENCY_RATE_PO
						FROM $this->tbl_master_approval MA
								inner join $this->tbl_trx_approval_po TRX on MA.ID_APPROVAL = TRX.ID_APPROVAL
								inner join $this->tbl_po_header POH on TRX.PO_HEADER_ID = POH.PO_HEADER_ID
								inner join $this->tbl_pr_header PRH on POH.ID_PR_HEADER_ID = PRH.PR_HEADER_ID
								WHERE TRX.IS_ACTIVE = 1
								AND POH.PO_HEADER_ID = ?
								AND MA.PIC_EMAIL = ?
								ORDER BY LEVEL DESC LIMIT 1";

		$query  = $this->db->query($mainQuery, array($id_pr, $pic_email));

		return $query->row_array();

	}

	public function get_approver_po($id_po, $level, $is_bod=false, $except_level = 0){

		$whereArr[] = $id_po;
		$where = "";

		if($is_bod){
			$where = " AND ta.category = 'BOD' ";

		}else{
			// $where = " AND ta.category != 'BOD' ";
			$where .= " AND ta.level = ?";
			$whereArr[] = $level;
		}

		if($except_level > 0){
			$where .= " AND ta.level != ?";
			$whereArr[] = $except_level;
		}

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval_po ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.PO_HEADER_ID = ?
								AND ta.IS_ACTIVE = 1
								$where
								ORDER BY ta.id DESC";

		$query  = $this->db->query($mainQuery, $whereArr);

		if($is_bod){
			return $query->result_array();
		}else{
			return $query->row_array();
		}

	}

	public function get_all_approver_po($id_po, $except_id=0, $is_null=false){

		$whereArr[] = $id_po;
		$where = "";

		if($except_id > 0){
			$where .= " AND ta.id != ?";
			$whereArr[] = $except_id;
		}

		if($is_null == false){
			$where .= " AND ta.status is not null";
		}

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval_po ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.PO_HEADER_ID = ?
								AND ta.IS_ACTIVE = 1
								$where
								ORDER BY ta.id";

		$query  = $this->db->query($mainQuery, $whereArr);

		return $query->result_array();

	}

	function get_pr_for_assign(){

		$this->db->select("PR_HEADER_ID");
		$this->db->where('STATUS', 'approved');
		$this->db->where('STATUS_ASSIGN', 'N');

        $query = $this->db->get($this->tbl_pr_header);

        return $query->result_array();

	}




	public function get_pr_to_assign($status=""){

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
			$fieldToSearch = array("PRH.PR_NUMBER", "PRH.PR_NAME", "PRH.PR_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($status == "assigned"):
			$where .= " AND (PRH.STATUS = 'approved' or PRH.STATUS = 'PO Created')";
			$where .= " AND PRH.STATUS_ASSIGN = 'Y'";
		elseif($status == "need_assign"):
			$where .= " AND PRH.STATUS = 'approved'";
			$where .= " AND PRH.STATUS_ASSIGN = 'N'";
		else:
			$where .= " AND (PRH.STATUS = 'approved' or PRH.STATUS = 'PO Created')";
			$where .= " AND (PRH.STATUS_ASSIGN = 'N' OR PRH.STATUS_ASSIGN = 'Y' )";
		endif;

		$mainQuery = " SELECT PRH.PR_HEADER_ID, PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, PRH.PR_NUMBER,
						PRH.PR_NAME, PRH.STATUS, PRH.STATUS_DESCRIPTION, PRH.PR_DATE, PRH.PR_AMOUNT, PRH.SUBMITTER,
						PRH.APPROVAL_LEVEL,PRH.CURRENCY, PRH.CURRENCY_RATE, PRH.DOCUMENT_ATTACHMENT, PRH.PO_BUYER, PRH.STATUS_ASSIGN
						FROM $this->tbl_pr_header PRH
					WHERE 1=1
					$where
					ORDER BY PRH.PR_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_pr_to_assign_by_id($id_pr){
		
		$this->db->where("STATUS_ASSIGN IS NOT NULL");
		$this->db->where("PR_HEADER_ID", $id_pr);
		$query  = $this->db->get($this->tbl_pr_header);

		return $query->row_array();

	}

	public function get_category_type_pr($buyer_email=""){
		$this->db->order_by("CATEGORY_TYPE");
		$this->db->where("CATEGORY_TYPE != '' AND CATEGORY_TYPE != 'NULL' AND CATEGORY_TYPE IS NOT NULL");
		if($buyer_email != ""){
			$this->db->where("BUYER_EMAIL", $buyer_email);
		}
		$this->db->select("ID_SPEND, CATEGORY_TYPE, BUYER_EMAIL");
        $this->db->group_by("CATEGORY_TYPE, BUYER_EMAIL");

        $query = $this->db->get("MASTER_SPEND_CATEGORY_BUYER");

        return $query->result_array();
		
	}

	public function get_all_buyer(){

		$query = "SELECT * FROM MASTER_APPROVAL where IS_EXIST = 1 and PIC_LEVEL = 'Procurement Buyer' AND IS_DELETED = 0 order by PIC_NAME";

        $query = $this->db->query($query);

        return $query->result_array();
		
	}

	function get_po_buyer($id_po){
		$sql = "SELECT BUYER, BUYER_EMAIL FROM $this->tbl_po_header PO
				WHERE PO_HEADER_ID = ?";

        $query = $this->db->query($sql, $id_po);

        return $query->row_array();
	}

	public function get_po_for_procedure($po_header_id){

		$query = "SELECT PO_NUMBER, IFNULL(OS.FLEX_VALUE, MC.NATURE) NATURE, PR_DETAIL_AMOUNT PO_AMOUNT,
					PO_PERIOD_FROM, PO_PERIOD_TO, PRD.CAPEX,
					(SELECT CAPEX_OPEX FROM RKAP_LINE WHERE ID_RKAP_LINE = PRL.ID_RKAP_LINE) CAPEX_OPEX
					FROM PO_LINES POL
					JOIN PO_DETAIL POD ON POL.PO_LINE_ID = POD.PO_LINE_ID
					JOIN PR_DETAIL PRD ON POD.PR_DETAIL_ID = PRD.PR_DETAIL_ID
					JOIN PR_LINES PRL ON PRL.PR_LINES_ID = PRD.PR_LINES_ID
					LEFT JOIN MASTER_COA MC ON PRD.ID_MASTER_COA = MC.ID_MASTER_COA
					LEFT JOIN ORACLE_SEGMENT5 OS ON PRD.ID_MASTER_COA = OS.ID_MASTER_COA
					WHERE POL.PO_HEADER_ID = ?";

        $query = $this->db->query($query, $po_header_id);

        // return $query->row_array();
        return $query->result_array();
	}

	function get_pr_for_email_accounting($id_pr){

		$query = "SELECT PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, PRH.ID_FS,
					PRH.PR_NUMBER, PRH.PR_NAME, PRH.PR_AMOUNT, PRH.CURRENCY,
					PRH.CURRENCY_RATE, PRH.SUBMITTER, PRH.DOCUMENT_ATTACHMENT, PRL.ID_RKAP_LINE,
					FS.FS_NUMBER, FS.FS_NAME, BH.RKAP_DESCRIPTION, BH.TRIBE_USECASE, BH.CAPEX_OPEX, 
					BH.MONTH, PRD.PR_DETAIL_DESC, PRD.PR_DETAIL_AMOUNT, IFNULL(OS.FLEX_VALUE, MC.NATURE) NATURE, IFNULL(OS.VALUE_DESCRIPTION, MC.DESCRIPTION) DESCRIPTION
					FROM PR_HEADER PRH
					LEFT JOIN PR_LINES PRL ON PRH.PR_HEADER_ID = PRL.PR_HEADER_ID		
					LEFT JOIN PR_DETAIL PRD ON PRL.PR_LINES_ID = PRD.PR_LINES_ID
					LEFT JOIN FS_BUDGET FS ON PRH.ID_FS = FS.ID_FS
					LEFT JOIN BUDGET_HEADER BH ON PRL.ID_RKAP_LINE = BH.ID_RKAP_LINE
					LEFT JOIN MASTER_COA MC ON PRD.ID_MASTER_COA = MC.ID_MASTER_COA
					LEFT JOIN ORACLE_SEGMENT5 OS ON PRD.ID_MASTER_COA = OS.ID_MASTER_COA
					WHERE PRH.PR_HEADER_ID = ?
					ORDER BY PRD.PR_DETAIL_ID";
							
		return $this->db->query($query, $id_pr)->result_array();

	}

	function get_email_vendor($id_po){

		$query = " SELECT NAMA_VENDOR, IFNULL(NAMA_PIC_VENDOR,NAMA_PENANGGUNG_JAWAB) PIC_VENDOR, ALAMAT_EMAIL FROM MASTER_VENDOR
			WHERE NAMA_VENDOR = (select distinct VENDOR_NAME FROM PO_LINES WHERE PO_HEADER_ID = ? LIMIT 1)";
							
		return $this->db->query($query, $id_po)->row_array();

	}



	public function get_multi_approval($pr_header_id, $category){

		$whereArr[] = $pr_header_id;
		$where = " AND lower(ta.category) = ?";
		$whereArr[] = $category;

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ta.LEVEL, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval_pr ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.pr_header_id = ?
								and ta.IS_ACTIVE = 1
								$where";

		$query  = $this->db->query($mainQuery, $whereArr);

		return $query->result_array();
		

	}


	public function get_list_trx($pr_header_id){
		$this->db->where("PR_HEADER_ID", $pr_header_id);
		$this->db->order_by("ID");

		return $this->db->get($this->tbl_trx_approval_pr)->result_array();
	}


}

/* End of file Purchase_mdl.php */
/* Location: ./application/models/Purchase_mdl.php */
