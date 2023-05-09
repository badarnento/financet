<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GR_mdl extends CI_Model {

	protected   $tbl_pr_header         = "PR_HEADER",
				$tbl_gr_header         = "GR_HEADER",
				$tbl_gr_lines          = "GR_LINE",
				$tbl_gr_detail         = "GR_DETAIL",
				$tbl_fs                = "FS_BUDGET",
				$tbl_fs_lines          = "FS_BUDGET_LINES",
				$tbl_rkap_view         = "BUDGET_HEADER",
				$tbl_po_header         = "PO_HEADER",
				$tbl_po_lines          = "PO_LINES",
				$tbl_po_detail         = "PO_DETAIL",
				$tbl_gr_detail_staging = "GR_DETAIL_STAGING",
				$tbl_po_detail_staging = "PO_DETAIL_STAGING",
				$tbl_master_coa        = "MASTER_COA", 
				$tbl_direktorat        = "MASTER_DIRECTORAT",
				$tbl_division          = "MASTER_DIVISION",
				$tbl_unit              = "MASTER_UNIT",
				$tbl_master_approval   = "MASTER_APPROVAL",
				$tbl_trx_approval      = "TRX_APPROVAL_GR",
				$tbl_fs_view           = "BUDGET_FINANCE_STUDY",
				$tbl_major_category    = "MASTER_MAJOR_CATEGORY",
				$tbl_minor_category    = "MASTER_MINOR_CATEGORY",
				$tbl_project_ownership = "MASTER_PROJECT_OWNERSHIP",
				$tbl_contract_indentification = "MASTER_CONTRACT_INDETIFICATION",
				$tbl_ownership 		   = "MASTER_OWNERSHIP",
				$tbl_region    		   = "MASTER_REGION",
				$tbl_location    		= "MASTER_LOCATION";

	public function get_last_gr_number($id_dir_code){

		$this->db->select("GR_NUMBER");
		$this->db->where("ID_DIR_CODE", $id_dir_code);
		$this->db->order_by("GR_HEADER_ID", "DESC");

		$query = $this->db->get($this->tbl_gr_header);

		return $query->row()->GR_NUMBER;

	}

	public function get_po_number( $id_dir_code, $id_division, $id_unit){

        $where    = "";
		$whereVal = array();

		if($id_dir_code){
			$where .= " and PRH.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and PRH.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and PRH.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}


		$queryExec = "SELECT DISTINCT POL.PO_NUMBER, POL.PO_LINE_DESC, POL.PO_HEADER_ID
					  ,PRH.PR_HEADER_ID, PRH.ID_FS, POL.VENDOR_NAME,
					  (SELECT DISTINCT GR_CATEGORY 
    				   FROM GR_HEADER
   				       WHERE PO_NUMBER =  POL.PO_NUMBER ) as CATEGORY FROM 
					  $this->tbl_po_lines POL
					  INNER JOIN $this->tbl_po_header POH ON POL.PO_HEADER_ID = POH.PO_HEADER_ID
                      INNER JOIN $this->tbl_pr_header PRH ON POH.ID_PR_HEADER_ID = PRH.PR_HEADER_ID
                      WHERE LOWER(POH.STATUS) IN ('approved','partial paid','paid')
					  $where";

		$data = $this->db->query($queryExec, $whereVal)->result_array();

		return $data;
	}

	public function get_gr_lines($id_fs){

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
			$fieldToSearch = array("POL.PO_NUMBER", "POL.VENDOR_NAME", "POD.ITEM_NAME", "POD.DESCRIPTION_PO", "QUANTITY","POD.UOM","POD.PRICE","POD.PO_DETAIL_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$amount=" AND gh.GR_CATEGORY = 'amount'";

		$where .= " and POL.PO_NUMBER = ?";

		$mainQuery = "select POD.PO_DETAIL_ID, POL.PO_NUMBER, POL.VENDOR_NAME, POD.ITEM_NAME, POD.DESCRIPTION_PO ITEM_DESCRIPTION, QUANTITY, POD.UOM, POD.PRICE ITEM_PRICE, POD.PO_DETAIL_AMOUNT TOTAL_PRICE, 
		IFNULL(
        (
        SELECT
            SUM(ITEM_PRICE)
        FROM
            GR_LINE gl INNER JOIN GR_HEADER gh on gl.GR_HEADER_ID = gh.GR_HEADER_ID $amount
        WHERE
            PO_DETAIL_ID = POD.PO_DETAIL_ID
        GROUP BY
            PO_DETAIL_ID
    ),
    0
    ) AS TOTAL_PRICE_BY_AMOUNT
			FROM $this->tbl_po_detail POD
			INNER JOIN $this->tbl_po_lines POL ON POD.PO_LINE_ID = POL.PO_LINE_ID
			INNER JOIN $this->tbl_po_header POH ON POL.PO_HEADER_ID = POH.PO_HEADER_ID
			$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $id_fs)->num_rows();
		$data  = $this->db->query($queryData, $id_fs)->result_array();
		$result['data']       = $data;
		$result['total_data'] = $total;

		// echo $this->db->last_query(); die;

		return $result;	

	}


	public function get_gr_lines_assets($id_gr){

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
			$fieldToSearch = array("NO_INVOICE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and GR.GR_HEADER_ID = ?";

		$mainQuery = "SELECT * FROM $this->tbl_gr_lines GR
						where 1=1 $where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $id_gr)->num_rows();
		$data  = $this->db->query($queryData, $id_gr)->result_array();
		$result['data']       = $data;
		$result['total_data'] = $total;

		// echo $this->db->last_query(); die;

		return $result;	

	}



	public function get_gr_header($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to){

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
			$fieldToSearch = array("gr.GR_NUMBER","gr.PO_NUMBER","pol.VENDOR_NAME","dr.DIRECTORAT_NAME","dv.DIVISION_NAME","un.UNIT_NAME","STATUS");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and gr.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and gr.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and gr.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}

		$mainQuery = "SELECT distinct gr.GR_HEADER_ID, gr.GR_NUMBER, dr.DIRECTORAT_NAME, dv.DIVISION_NAME, un.UNIT_NAME, pol.VENDOR_NAME, pol.PO_NUMBER, gr.GR_DATE, gr.STATUS
						FROM $this->tbl_gr_header gr 
						INNER JOIN $this->tbl_direktorat dr ON gr.ID_DIR_CODE = dr.ID_DIR_CODE
						INNER JOIN $this->tbl_division dv ON gr.ID_DIVISION = dv.ID_DIVISION
						INNER JOIN $this->tbl_unit un ON gr.ID_UNIT = un.ID_UNIT
						INNER JOIN $this->tbl_po_lines pol ON gr.PO_HEADER_ID = pol.PO_HEADER_ID
						where 1=1
						and CONVERT(GR_DATE, DATE) BETWEEN ? and ?
						$where
						GROUP BY gr.GR_HEADER_ID
						order by GR_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		// echo $this->db->last_query(); die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_download_gr($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to)
	{
		$where    = "";
		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and gr.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and gr.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and gr.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}

		$queryExec = "SELECT gr.*, dr.DIRECTORAT_NAME, dv.DIVISION_NAME, un.UNIT_NAME
						FROM $this->tbl_gr_header gr 
						INNER JOIN $this->tbl_direktorat dr ON gr.ID_DIR_CODE = dr.ID_DIR_CODE
						INNER JOIN $this->tbl_division dv ON gr.ID_DIVISION = dv.ID_DIVISION
						INNER JOIN $this->tbl_unit un ON gr.ID_UNIT = un.ID_UNIT
						where 1=1
						and CONVERT(GR_DATE, DATE) BETWEEN ? and ?
						$where
						order by GR_HEADER_ID DESC";

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
						poh.VENDOR_NAME, poh.VENDOR_BANK_NAME, poh.VENDOR_BANK_ACCOUNT, prh.CURRENCY, prh.PR_NUMBER
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
			if($status == "PARTIAL PAID"){
				$where .= " and (PH.STATUS = 'PARTIAL PAID' OR PH.STATUS = 'PAID' )";
			}else{
				$where .= " and PH.STATUS = ?";
				$whereVal[] = $status;
			}
		}

		$mainQuery = "SELECT PL.PO_HEADER_ID, PL.PO_NUMBER, PL.PO_LINE_DESC,
						sum(PL.PO_LINE_AMOUNT) PO_AMOUNT, PL.VENDOR_NAME, PL.VENDOR_BANK_NAME,
						PL.VENDOR_BANK_ACCOUNT, PR.CURRENCY, PR.PR_NUMBER, PH.PO_DATE,
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

		/*$this->db->where("prh.ID_DIR_CODE", $directorat);
		$this->db->where("prh.ID_DIVISION", $division);
		$this->db->where("prh.ID_UNIT", $unit);*/

		$this->db->select("pol.PO_NUMBER, pol.PO_LINE_ID, pol.PO_LINE_DESC");
		$this->db->join($this->tbl_po_header." poh","pol.PO_HEADER_ID = poh.PO_HEADER_ID");
		$this->db->join($this->tbl_pr_header." prh","poh.ID_PR_HEADER_ID = prh.PR_HEADER_ID");
		$this->db->order_by("PO_NUMBER");

        $query = $this->db->get($this->tbl_po_lines." pol");

		$result = $query->result_array();

        return $result;
	}

	public function get_purchase_gr_lines($gr_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		// if($keywords != ""){
		// 	if(strpos($keywords,".") > 0){
		// 		$string = (int) trim_string($keywords);
		// 		if($string > 0){
		// 			$keywords = $string;
		// 		}
		// 	}
		// 	$fieldToSearch = array("PR_LINE_NAME", "FS_NAME", "PR_NAME", "PR_LINE_AMOUNT");
		// 	$where = query_datatable_search($keywords, $fieldToSearch);
		// }

		$mainQuery = "SELECT GL.* ,MAC.MAJOR_NAME, MIC.MINOR_NAME, POW.OWNERSHIP_NAME, RGN.REGION as REGION_NAME, LCT.LOCATION as LOCATION_NAME  from $this->tbl_gr_lines GL 
								left JOIN	$this->tbl_major_category   MAC ON GL.MAJOR_CATEGORY = MAC.CODE
								left JOIN	$this->tbl_minor_category    MIC ON GL.MINOR_CATEGORY = MIC.CODE
								left JOIN	$this->tbl_project_ownership   POW ON GL.PROJECT_OWNERSHIP_UNIT_CODE = POW.CODE
								left JOIN	$this->tbl_region    		  RGN ON GL.REGION = RGN.CODE
								left JOIN	$this->tbl_location    		  LCT ON GL.LOCATION = LCT.CODE
								where GR_HEADER_ID = ? 
		";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $gr_header_id)->num_rows();
		$data  = $this->db->query($queryData, $gr_header_id)->result_array();
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_gr_header_by_id($id_gr){


		$mainQuery = "SELECT GR.*,
						IFNULL((SELECT DISTINCT VENDOR_NAME FROM PO_LINES WHERE PO_HEADER_ID = GR.PO_HEADER_ID LIMIT 1), '') VENDOR_NAME,
						IFNULL(CI.NAME, GR.CONTRACT) as CONTRACT_IDENTIFICATION, IFNULL(OW.OWNERSHIP_NAME, GR.PROJECT_OWNERSHIP) as OWNERSHIP_NAME
						FROM GR_HEADER GR
						LEFT JOIN MASTER_CONTRACT_INDETIFICATION  CI ON GR.CONTRACT = CI.CODE
						LEFT JOIN MASTER_OWNERSHIP OW  ON GR.PROJECT_OWNERSHIP = OW.CODE
						WHERE GR.GR_HEADER_ID = ? ";

		// echo $mainQuery; die;

		$query = $this->db->query($mainQuery, $id_gr)->row_array();

		// echo $this->db->last_query(); die;

        return $query;
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
								prl.PR_LINE_NAME, IFNULL(pol.PO_LINE_AMOUNT, 0) PO_AMOUNT
						FROM $this->tbl_po_lines pol
						LEFT JOIN $this->tbl_pr_lines prl ON pol.PR_LINES_ID = prl.PR_LINES_ID
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

		$this->db->select("PRH.PR_HEADER_ID, PR_NUMBER, PR_NAME, PO_DATE, PO_AMOUNT, PRH.CURRENCY, PRH.CURRENCY_RATE, ID_DIR_CODE, ID_DIVISION, ID_UNIT, PO_NAME, PO_TYPE, PO_REFERENCE, PO.STATUS");
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

		$mainQuery = "SELECT prl.PR_LINES_ID, prl.ID_RKAP_LINE, prl.PR_LINES_NUMBER, prl.PR_LINE_AMOUNT, prl.PR_LINE_NAME,
								prh.ID_DIR_CODE, rv.DIVISION, rv.RKAP_DESCRIPTION, rv.UNIT, rv.TRIBE_USECASE, rv.FA_FS,
								pol.PO_NUMBER, pol.PO_LINE_DESC, IFNULL(pol.PO_LINE_AMOUNT, 0) PO_AMOUNT, pol.VENDOR_NAME,  pol.VENDOR_BANK_NAME,  pol.VENDOR_BANK_ACCOUNT, pol.PO_PERIOD_FROM, pol.PO_PERIOD_TO, pol.VENDOR_BANK_ACCOUNT_NAME
						FROM $this->tbl_pr_lines prl
						LEFT JOIN $this->tbl_pr_header prh ON prl.pr_header_id = prh.pr_header_id
						LEFT JOIN $this->tbl_rkap_view rv ON prl.id_rkap_line = rv.id_rkap_line
						LEFT JOIN $this->tbl_po_lines pol on prl.pr_lines_id = pol.pr_lines_id
						where 1=1
						$where
						order by prl.pr_lines_number";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pr_header_id)->num_rows();
		$data  = $this->db->query($queryData, $pr_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_gr_for_edit($gr_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		// $where    = "";
		// if($keywords != ""){
		// 	$fieldToSearch = array("PR_LINE_NAME", "PR_NAME");
		// 	$where = query_datatable_search($keywords, $fieldToSearch);
		// }

		$mainQuery = "select * from $this->tbl_gr_lines where GR_HEADER_ID = ?";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $gr_header_id)->num_rows();
		$data  = $this->db->query($queryData, $gr_header_id)->result_array();

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
						prd.PR_DETAIL_DESC, pod.ITEM_NAME, pod.PRICE PRICE_PO, pod.QUANTITY QTY_PO, prd.UOM, IFNULL(pod.PO_DETAIL_AMOUNT, 0) PO_AMOUNT_DETAIL
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
						prd.PR_DETAIL_DESC, prd.UOM, mc.DESCRIPTION, mc.NATURE, mc.ID_MASTER_COA
						FROM $this->tbl_pr_detail prd
						LEFT JOIN $this->tbl_pr_lines prl ON prd.pr_lines_id = prl.pr_lines_id
						LEFT JOIN $this->tbl_master_coa mc ON prd.id_master_coa = mc.id_master_coa
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
						prd.PR_DETAIL_DESC, prd.QUANTITY, prd.PRICE, pod.DESCRIPTION_PO, IFNULL(pod.PO_DETAIL_AMOUNT, 0) PO_AMOUNT_DETAIL, prd.UOM, pod.ITEM_NAME, pod.QUANTITY QTY_PO, pod.PRICE PRICE_PO, prd.PR_DETAIL_NAME
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
							PRH.SUBMITTER, PRH.DOCUMENT_ATTACHMENT, PRL.ID_RKAP_LINE,
							BH.RKAP_DESCRIPTION, BH.TRIBE_USECASE, BH.CAPEX_OPEX, BH.MONTH
					FROM $this->tbl_pr_header PRH, $this->tbl_pr_lines PRL
					LEFT JOIN BUDGET_HEADER BH ON PRL.ID_RKAP_LINE = BH.ID_RKAP_LINE
					WHERE PRH.PR_HEADER_ID = ?
					AND PRL.PR_HEADER_ID = PRH.PR_HEADER_ID
					LIMIT 1";
					
		return $this->db->query($query, $id_pr)->row_array();

	}


	function check_is_approval($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("PIC_LEVEL", "HOG User");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	function check_is_reviewer($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("PIC_LEVEL", "Asset Review");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	function count_pending_gr($pic_email){

		$mainQuery = "SELECT COUNT(ID) TOTAL FROM $this->tbl_trx_approval
							WHERE IS_ACTIVE  = 1
								AND ID_APPROVAL IN (SELECT ID_APPROVAL FROM $this->tbl_master_approval WHERE PIC_EMAIL = ? AND IS_EXIST = 1 AND IS_DELETED = 0)
								AND STATUS = 'request_approve'
								AND GR_HEADER_ID IN (SELECT GR_HEADER_ID FROM $this->tbl_gr_header WHERE STATUS = 'request_approve')";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}

	function check_request_approval($pic_email){

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ma.PIC_EMAIL, ma.ID_APPROVAL
						FROM $this->tbl_trx_approval ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.status = 'request_approve'
								AND ma.PIC_EMAIL = ?
								ORDER BY ta.id DESC";

		return $this->db->query($mainQuery, $pic_email)->num_rows();	

	}

	function check_gr_to_review(){

		$total = $this->db->query("SELECT COUNT(GR_HEADER_ID) TOTAL FROM GR_HEADER
									WHERE STATUS = 'approved' AND IS_REVIEW = 0")->row_array();

		return $total;


	}

	function get_gr_for_review(){

		$this->db->select("GR_HEADER_ID");
		$this->db->where('STATUS', 'approved');
		$this->db->where('IS_REVIEW', 0);

        $query = $this->db->get($this->tbl_gr_header);

        return $query->result_array();

	}



	public function get_gr_to_review($status=""){

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
			$fieldToSearch = array("GRH.GR_NUMBER", "GRH.NO_BAST", "GRH.PO_NUMBER");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " AND GRH.STATUS = 'approved'";
		if($status == "need_review"):
			$where .= " AND GRH.IS_REVIEW = 0";
		else:
			$where .= " AND GRH.IS_REVIEW = 1";
		endif;

		$mainQuery = " SELECT GRH.GR_HEADER_ID, GRH.ID_DIR_CODE, GRH.ID_DIVISION, GRH.ID_UNIT, GRH.GR_NUMBER,
						GRH.STATUS, GRH.GR_DATE, NO_BAST, GRH.SUBMITTER, GRH.GR_DOCUMENT, STATUS_DESCRIPTION
						FROM $this->tbl_gr_header GRH
							WHERE 1=1
							$where
							ORDER BY GRH.GR_HEADER_ID DESC";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_gr_to_approve_by_id($id_gr, $pic_email){

		$mainQuery = "SELECT GRH.*, TRX.LEVEL, MA.PIC_LEVEL, TRX.STATUS TRX_STATUS, TRX.UPDATED_DATE TRX_DATE,
						IFNULL((SELECT DISTINCT VENDOR_NAME FROM PO_LINES WHERE PO_HEADER_ID = GRH.PO_HEADER_ID LIMIT 1),'') VENDOR_NAME,
						IFNULL(CI.NAME, GRH.CONTRACT) as CONTRACT_IDENTIFICATION, IFNULL(OW.OWNERSHIP_NAME, GRH.PROJECT_OWNERSHIP) as OWNERSHIP_NAME
						 FROM $this->tbl_master_approval MA
						 inner join $this->tbl_trx_approval TRX on MA.ID_APPROVAL = TRX.ID_APPROVAL
						 inner join $this->tbl_gr_header GRH on TRX.GR_HEADER_ID = GRH.GR_HEADER_ID
						LEFT JOIN MASTER_CONTRACT_INDETIFICATION  CI ON GRH.CONTRACT = CI.CODE
						LEFT JOIN MASTER_OWNERSHIP OW  ON GRH.PROJECT_OWNERSHIP = OW.CODE
						WHERE GRH.GR_HEADER_ID = ? AND MA.PIC_EMAIL = ?";

		$query  = $this->db->query($mainQuery, array($id_gr, $pic_email));

		return $query->row_array();

	}



	public function get_gr_to_review_by_id($id_gr){

		$mainQuery = " SELECT GRH.*,
						IFNULL((SELECT DISTINCT VENDOR_NAME FROM PO_LINES 
									WHERE PO_HEADER_ID = GRH.PO_HEADER_ID),'') VENDOR_NAME,
						IFNULL(CI.NAME, GRH.CONTRACT) as CONTRACT_IDENTIFICATION, IFNULL(OW.OWNERSHIP_NAME, GRH.PROJECT_OWNERSHIP) as OWNERSHIP_NAME
						FROM $this->tbl_gr_header GRH
						LEFT JOIN MASTER_CONTRACT_INDETIFICATION  CI ON GRH.CONTRACT = CI.CODE
						LEFT JOIN MASTER_OWNERSHIP OW  ON GRH.PROJECT_OWNERSHIP = OW.CODE
						WHERE GRH.GR_HEADER_ID = ?";
		$query  = $this->db->query($mainQuery, $id_gr);

		return $query->row_array();

	}

	function get_approval_before($id_gr){

		$mainQuery = " SELECT PIC_NAME, PIC_EMAIL, REMARK, trx.UPDATED_DATE TRX_DATE
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								WHERE trx.PR_HEADER_ID = ?
								AND STATUS != 'request_approve'
								order by trx.UPDATED_DATE DESC";

		$query  = $this->db->query($mainQuery, $id_gr);

		return $query->row_array();

	}


	function get_submitter_by_id_gr($id_gr){
		$sql = "SELECT SUBMITTER, PIC_NAME, PIC_EMAIL, ALAMAT_EMAIL FROM $this->tbl_gr_header GR
					LEFT JOIN $this->tbl_master_approval MA ON GR.SUBMITTER = MA.PIC_NAME
					LEFT JOIN MASTER_EMPLOYEE ME ON GR.SUBMITTER = ME.NAMA
					WHERE GR_HEADER_ID = ? LIMIT 1";

        $query = $this->db->query($sql, $id_gr);
        return $query->row_array();
	}



	public function get_approver($id_gr, $level, $except_level = 0){

		$whereArr[] = $id_gr;
		$where = " AND ta.level = ?";
		$whereArr[] = $level;

		if($except_level > 0){
			$where .= " AND ta.level != ?";
			$whereArr[] = $except_level;
		}

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.GR_HEADER_ID = ?
								$where
								ORDER BY ta.id DESC";

		$query  = $this->db->query($mainQuery, $whereArr);

		return $query->row_array();

	}

	function get_approval_by_pr($id_pr){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("PR_HEADER_ID", $id_pr);
        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();
	}

	function get_approval_by_gr($id_gr){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("GR_HEADER_ID", $id_gr);
        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();
	}

	function get_gr_for_approval($pic_email){

		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->select("GR_HEADER_ID");
		$this->db->join($this->tbl_master_approval." MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");

        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();

	}


	public function get_gr_to_approve($pic_email, $status=""){

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
			$fieldToSearch = array("GRH.GR_NUMBER");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
		$whereVal[] = $pic_email;

		if($status != ""):
			$where .= " AND trx.STATUS = ?";
			if($status == "request_approve"){
				$where .= " AND GRH.STATUS = 'request_approve'";
			}
			$whereVal[] = $status;
		endif;

		$mainQuery = " SELECT GRH.GR_HEADER_ID, GRH.ID_DIR_CODE, GRH.ID_DIVISION, GRH.ID_UNIT, GRH.GR_NUMBER,
						GRH.STATUS, GRH.GR_DATE, NO_BAST, GRH.SUBMITTER, trx.LEVEL, GRH.GR_DOCUMENT, STATUS_DESCRIPTION,
						ma.PIC_LEVEL, trx.ID ID_GR_APPROVAL
						FROM $this->tbl_master_approval ma
							inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL and trx.IS_ACTIVE =1
							inner join $this->tbl_gr_header GRH on trx.GR_HEADER_ID = GRH.GR_HEADER_ID
							WHERE ma.PIC_EMAIL = ?
							AND trx.STATUS IS NOT NULL
							$where
							ORDER BY GRH.GR_HEADER_ID DESC";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();
		// echo $this->db->last_query();die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_cetak($pr_header_id){

		$queryExec	= " SELECT ph.PR_NUMBER, fb.FS_NAME, ph.SUBMITTER,
						mc.DESCRIPTION, mc.NATURE, md.DIRECTORAT_NAME,
						mu.UNIT_NAME, ph.PR_AMOUNT
						from PR_DETAIL pd, MASTER_COA mc, PR_HEADER ph, MASTER_DIRECTORAT md, MASTER_UNIT mu, PR_LINES pl, FS_BUDGET fb
						where pd.ID_MASTER_COA = mc.ID_MASTER_COA
						and ph.PR_HEADER_ID = pl.PR_HEADER_ID
						and pl.ID_FS = fb.ID_FS
						and pd.PR_HEADER_ID = ph.PR_HEADER_ID
						and ph.ID_DIR_CODE = md.ID_DIR_CODE
						and ph.ID_UNIT = mu.ID_UNIT
						and ph.pr_header_id = ? ";
		
		$data  = $this->db->query($queryExec, $pr_header_id);

		return $data->row_array();

	}

	public function get_all_major_category_data()
	{
		
		$mainQuery	        = "SELECT
		CODE,
		MAJOR_NAME
		FROM $this->tbl_major_category";
		$query 		        = $this->db->query($mainQuery);

		$result['query']	= $query;
		return $result;		
	}

	public function get_all_minor_category_data($major_code)
	{

		
		$mainQuery	        = "SELECT
		CODE,
		MINOR_NAME
		FROM $this->tbl_minor_category
		where MAJOR_CODE = ?";

		$query 		        = $this->db->query($mainQuery, $major_code);
		$result['total'] = $query->num_rows();
		$result['data']  = $query->result_array();

		return $result;		
	}

	public function get_all_region_data()
	{
		
		$mainQuery	        = "SELECT
		CODE,
		REGION
		FROM $this->tbl_region";
		$query 		        = $this->db->query($mainQuery);

		$result['query']	= $query;
		return $result;		
	}

	public function get_all_location_data($region_code)
	{
		
		$mainQuery	        = "SELECT
		CODE,
		LOCATION
		FROM $this->tbl_location
		where REGION_CODE = $region_code";
		$query 		        = $this->db->query($mainQuery);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->result_array();

		return $result;			
	}

	public function get_all_project_owner_unit_data($id_dir_code)
	{
		
		$mainQuery	        = "SELECT
		CODE,
		OWNERSHIP_NAME
		FROM $this->tbl_project_ownership
		where ID_DIR_CODE = $id_dir_code ";
		$query 		        = $this->db->query($mainQuery);

		$result['query']	= $query;
		return $result;		
	}

	public function get_all_data_contract_indentification()
	{
		
		$mainQuery	        = "SELECT
		CODE,
		NAME
		FROM $this->tbl_contract_indentification";
		$query 		        = $this->db->query($mainQuery);

		$result['query']	= $query;
		return $result;		
	}

	public function get_all_project_ownership_data()
	{
		
		$mainQuery	        = "SELECT
		CODE,
		OWNERSHIP_NAME
		FROM $this->tbl_ownership";
		$query 		        = $this->db->query($mainQuery);

		$result['query']	= $query;
		return $result;		
	}

	function get_last_update_gr($id_gr){
		$this->db->select("PIC_NAME, STATUS, REMARK, TRX.UPDATED_DATE");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("GR_HEADER_ID", $id_gr);
		$this->db->where("STATUS !=", "request_approve");
		$this->db->where("STATUS IS NOT NULL");
		$this->db->order_by("TRX.ID DESC");
		$this->db->limit(1);

        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->row_array();
	}



}

/* End of file Purchase_mdl.php */
/* Location: ./application/models/Purchase_mdl.php */