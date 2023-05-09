<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa_review_mdl extends CI_Model {

	protected   $tbl_pr_header  = "PR_HEADER",
				$tbl_pr_lines        = "PR_LINES",
				$tbl_pr_detail       = "PR_DETAIL",
				$tbl_fpjp_header     = "FPJP_HEADER",
				$tbl_fpjp_lines      = "FPJP_LINES",
				$tbl_fpjp_detail     = "FPJP_DETAIL",
				$tbl_master_approval = "MASTER_APPROVAL";


	function check_is_approval($pic_email){

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


	public function get_fpjp_to_review($status=""){

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
			$fieldToSearch = array("FPJPH.FPJP_NUMBER", "FPJPH.FPJP_NAME", "FPJPH.FPJP_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($status == "reviewed"):
			$where .= " AND FPJPH.STATUS = 'approved'";
			$where .= " AND FPJPH.COA_REVIEW = 'Y'";
		elseif($status == "need_review"):
			$where .= " AND FPJPH.STATUS = 'approved'";
			$where .= " AND FPJPH.COA_REVIEW = 'N'";
		else:
			$where .= " AND FPJPH.STATUS = 'approved'";
			$where .= " AND (FPJPH.COA_REVIEW = 'N' OR FPJPH.COA_REVIEW = 'Y' )";
		endif;

		$mainQuery = " SELECT FPJPH.FPJP_HEADER_ID, FPJPH.ID_DIR_CODE, FPJPH.ID_DIVISION, FPJPH.ID_UNIT, FPJPH.FPJP_NUMBER,
						FPJPH.FPJP_NAME, FPJPH.STATUS, FPJPH.STATUS_DESCRIPTION, FPJPH.FPJP_DATE, FPJPH.FPJP_AMOUNT, FPJPH.SUBMITTER,
						FPJPH.APPROVAL_LEVEL,FPJPH.CURRENCY, FPJPH.CURRENCY_RATE, FPJPH.DOCUMENT_ATTACHMENT
						FROM $this->tbl_fpjp_header FPJPH
					WHERE 1=1
					$where
					ORDER BY FPJPH.FPJP_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_pr_to_review($status=""){

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

		if($status == "reviewed"):
			$where .= " AND (PRH.STATUS = 'approved' or PRH.STATUS = 'PO Created')";
			$where .= " AND PRH.COA_REVIEW = 'Y'";
		elseif($status == "need_review"):
			$where .= " AND PRH.STATUS = 'approved'";
			$where .= " AND PRH.COA_REVIEW = 'N'";
		else:
			$where .= " AND (PRH.STATUS = 'approved' or PRH.STATUS = 'PO Created')";
			$where .= " AND (PRH.COA_REVIEW = 'N' OR PRH.COA_REVIEW = 'Y' )";
		endif;

		$mainQuery = " SELECT PRH.PR_HEADER_ID, PRH.ID_DIR_CODE, PRH.ID_DIVISION, PRH.ID_UNIT, PRH.PR_NUMBER,
						PRH.PR_NAME, PRH.STATUS, PRH.STATUS_DESCRIPTION, PRH.PR_DATE, PRH.PR_AMOUNT, PRH.SUBMITTER,
						PRH.APPROVAL_LEVEL,PRH.CURRENCY, PRH.CURRENCY_RATE, PRH.DOCUMENT_ATTACHMENT
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

	public function get_pr_to_review_by_id($id_pr){
		
		$this->db->where("COA_REVIEW IS NOT NULL");
		$this->db->where("PR_HEADER_ID", $id_pr);
		$query  = $this->db->get($this->tbl_pr_header);

		return $query->row_array();

	}

	public function get_pr_detail($pr_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("PR_DETAIL_DESC", "PR_DETAIL_AMOUNT", "DESCRIPTION", "NATURE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and prd.pr_header_id = ?";

		$mainQuery = "SELECT prd.PR_LINES_ID, prd.PR_DETAIL_ID, prl.PR_LINES_NUMBER, prd.PR_DETAIL_NUMBER,
						prd.QUANTITY, prd.PRICE, prd.PR_DETAIL_AMOUNT, prd.PR_DETAIL_NAME,
						prd.PR_DETAIL_DESC, prd.UOM, mc.VALUE_DESCRIPTION DESCRIPTION, mc.FLEX_VALUE NATURE, prd.CATEGORY_ITEM, prd.ID_MASTER_COA, CAPEX, CAPEX_OPEX
						FROM PR_DETAIL prd
						LEFT JOIN PR_LINES prl ON prd.pr_lines_id = prl.pr_lines_id
						LEFT JOIN BUDGET_FINANCE_STUDY bfs ON prl.ID_FS = bfs.ID_FS AND prl.ID_RKAP_LINE = bfs.ID_RKAP_LINE
						LEFT JOIN ORACLE_SEGMENT5 mc ON prd.id_master_coa = mc.id_master_coa
						where 1=1
						$where
						GROUP BY PR_DETAIL_ID
						order by prl.pr_lines_id, prd.pr_detail_number + 0 ASC";


		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pr_header_id)->num_rows();
		$data  = $this->db->query($queryData, $pr_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_fpjp_detail($fpjp_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("FPJP_DETAIL_DESC", "FPJP_DETAIL_AMOUNT", "DESCRIPTION", "NATURE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and fpjp.fpjp_header_id = ?";

		$mainQuery = "SELECT fpjp.FPJP_LINES_ID, fpjp.FPJP_DETAIL_ID, prl.FPJP_LINES_NUMBER, fpjp.FPJP_DETAIL_NUMBER,
						fpjp.QUANTITY, fpjp.PRICE, fpjp.FPJP_DETAIL_AMOUNT,
						fpjp.FPJP_DETAIL_DESC, mc.VALUE_DESCRIPTION DESCRIPTION, mc.FLEX_VALUE NATURE, fpjp.ID_MASTER_COA, TAX, SEGMENT_PRODUCT, SEGMENT_TRIBE,
						IFNULL((SELECT CONCAT(FLEX_VALUE,' - ', VALUE_DESCRIPTION) FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(rl.TRIBE_USECASE) AND SEGMENT='SEGMENT7' AND FLAG = 'Y'), '') TRIBE_RKAP
						FROM FPJP_DETAIL fpjp
						LEFT JOIN FPJP_LINES prl ON fpjp.fpjp_lines_id = prl.fpjp_lines_id
						LEFT JOIN RKAP_LINE rl ON prl.ID_RKAP_LINE = rl.ID_RKAP_LINE
						LEFT JOIN ORACLE_SEGMENT5 mc ON fpjp.id_master_coa = mc.id_master_coa
						where 1=1
						$where
						GROUP BY FPJP_DETAIL_ID
						order by prl.fpjp_lines_id, fpjp.fpjp_detail_number + 0 ASC";


		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $fpjp_header_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function count_pending_coa_review(){

		$total1 = $this->db->query("SELECT COUNT(PR_HEADER_ID) TOTAL
									FROM $this->tbl_pr_header
									WHERE STATUS = 'approved'
									AND COA_REVIEW = 'N'")->row_array();

		$total2 = $this->db->query("SELECT COUNT(FPJP_HEADER_ID) TOTAL
									FROM $this->tbl_fpjp_header
									WHERE STATUS = 'approved'
									AND COA_REVIEW = 'N'")->row_array();

		$total = $total1['TOTAL']+$total2['TOTAL'];

		return $total;

	}


}

/* End of file Coa_review_mdl.php */
/* Location: ./application/models/Coa_review_mdl.php */