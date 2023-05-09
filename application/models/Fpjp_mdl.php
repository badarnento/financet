<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fpjp_mdl extends CI_Model {

	protected 	$tbl_fpjp_header 	= "FPJP_HEADER",
				$tbl_fpjp_lines      = "FPJP_LINES",
				$tbl_fpjp_detail     = "FPJP_DETAIL",
				$tbl_fs              = "FS_BUDGET",
				$tbl_rkap_view       = "BUDGET_HEADER",
				$tbl_master_coa      = "MASTER_COA",
				$tbl_direktorat      = "MASTER_DIRECTORAT", //added by adi baskoro
				$tbl_division        = "MASTER_DIVISION",
				$tbl_master_approval = "MASTER_APPROVAL",
				$tbl_trx_approval    = "TRX_APPROVAL_FPJP",
				$tbl_fs_view         = "BUDGET_FINANCE_STUDY",
				$tbl_unit            = "MASTER_UNIT",
				$tbl_fpjp_boq        = "FPJP_BOQ";

	public function get_last_fpjp_number($id_dir_code){

		$this->db->select("FPJP_NUMBER");
		$this->db->where("ID_DIR_CODE", $id_dir_code);
		$this->db->order_by("FPJP_HEADER_ID", "DESC");

		$query = $this->db->get($this->tbl_fpjp_header);

		return $query->row()->FPJP_NUMBER;

	}

	public function get_fpjp_header($id_dir_code=false, $division=false, $unit=false, $status=false, $date_from, $date_to){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("FPJP_NUMBER","FPJP_AMOUNT","FPJP_NAME","CURRENCY");
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where  .= "and CONVERT(FPJP_DATE, DATE) BETWEEN ? and ?";
		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($division){
			$where .= " and ID_DIVISION = ?";
			$whereVal[] = $division;
		}

		if($unit){
			$where .= " and ID_UNIT = ?";
			$whereVal[] = $unit;
		}

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}

		$mainQuery = "SELECT *
						FROM $this->tbl_fpjp_header
						where 1=1
						$where
						order by FPJP_HEADER_ID DESC";


		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}

	public function get_download_data_fpjp_header($id_dir_code=false, $division=false, $unit=false, $status=false, $date_from, $date_to){

		$where  = "and CONVERT(FPJP_DATE, DATE) BETWEEN ? and ?";

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and fh.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($division){
			$where .= " and fh.ID_DIVISION = ?";
			$whereVal[] = $division;
		}

		if($unit){
			$where .= " and fh.ID_UNIT = ?";
			$whereVal[] = $unit;
		}

		if($status){
			$where .= " and fh.STATUS = ?";
			$whereVal[] = $status;
		}

		$queryExec = "SELECT dr.DIRECTORAT_NAME,
							 dv.DIVISION_NAME,
							 un.UNIT_NAME,
							 fh.FPJP_NUMBER,
							 fh.FPJP_NAME,
							 fh.FPJP_DATE,
							 fh.CURRENCY,
							 fh.FPJP_AMOUNT,
							 fh.SUBMITTER,
							 fh.JABATAN_SUBMITTER,
							 fh.DIKETAHUI_1,
							 fh.JABATAN_1,
							 fh.DIKETAHUI_2,
							 fh.JABATAN_2
						FROM  $this->tbl_fpjp_header fh
						INNER JOIN  $this->tbl_direktorat dr on fh.ID_DIR_CODE = dr.ID_DIR_CODE
						INNER JOIN  $this->tbl_division dv on fh.ID_DIVISION = dv.ID_DIVISION
						INNER JOIN  $this->tbl_unit un on fh.ID_UNIT = un.ID_UNIT
						where 1=1
						$where
						order by fh.FPJP_HEADER_ID DESC";

		$query     = $this->db->query($queryExec, $whereVal);
		return $query;
	}

	public function get_fpjp_lines2($fpjp_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("FPJP_LINE_NAME", "FPJP_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($fpjp_header_id){
			$where .= " and fpjph.fpjp_header_id = ?";
		}

		$mainQuery = "SELECT fpjpl.FPJP_LINES_ID, fpjpl.ID_RKAP_LINE, fpjpl.FPJP_LINES_NUMBER, fpjpl.FPJP_LINE_AMOUNT, fpjpl.FPJP_LINE_NAME, fpjpl.PEMILIK_REKENING, fpjpl.NAMA_BANK, fpjpl.NO_REKENING,
								rv.DIVISION, rv.RKAP_DESCRIPTION, rv.UNIT, rv.TRIBE_USECASE, rv.FA_FS, fs.FS_NAME, fs.FS_DESCRIPTION, fs.FS_NUMBER, rv.MONTH
						FROM $this->tbl_fpjp_lines fpjpl
						LEFT JOIN $this->tbl_fpjp_header fpjph ON fpjpl.fpjp_header_id = fpjph.fpjp_header_id
						LEFT JOIN $this->tbl_rkap_view rv ON fpjpl.id_rkap_line = rv.id_rkap_line
						LEFT JOIN $this->tbl_fs fs ON fpjpl.ID_FS = fs.ID_FS
						where 1=1
						$where
						order by fpjpl.fpjp_lines_number";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $fpjp_header_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_fpjp_lines($fpjp_header_id){

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
			$fieldToSearch = array("FPJP_LINE_NAME", "FS_NAME", "FPJP_LINE_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and fpjpl.FPJP_HEADER_ID = ?";

		$mainQuery = "SELECT fpjpl.FPJP_HEADER_ID, IS_SHOW, fpjpl.FPJP_LINES_ID, fpjpl.ID_RKAP_LINE, fpjpl.FPJP_LINES_NUMBER, fpjpl.FPJP_LINE_AMOUNT, fpjpl.ORIGINAL_AMOUNT, fpjpl.PEMILIK_REKENING, fpjpl.NAMA_BANK, fpjpl.NO_REKENING,
								fpjpl.FPJP_LINE_NAME, rvs.RKAP_DESCRIPTION, rvs.MONTH, rvs.FA_FS
						FROM $this->tbl_fpjp_lines fpjpl
						LEFT JOIN $this->tbl_fs_view rvs ON fpjpl.ID_RKAP_LINE = rvs.ID_RKAP_LINE and rvs.ID_FS = fpjpl.ID_FS
						where 1=1
						$where
						order by fpjpl.FPJP_LINES_NUMBER + 0 ASC ";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $fpjp_header_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_fpjp_details($fpjp_lines_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("FPJP_DETAIL_DESC", "FPJP_DETAIL_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and fpjpl.fpjp_lines_id = ?";

		$mainQuery = "SELECT fpjpl.FPJP_LINES_ID, fpjpd.FPJP_DETAIL_ID, fpjpd.FPJP_DETAIL_NUMBER, fpjpd.FPJP_DETAIL_AMOUNT,
						fpjpd.FPJP_DETAIL_DESC, fpjpd.ID_MASTER_COA, fpjpd.QUANTITY, fpjpd.PRICE, fpjpd.TAX, fpjpd.PPH, fpjpd.VATSA,
							IFNULL(os.FLEX_VALUE, mc.NATURE) NATURE, IFNULL(os.VALUE_DESCRIPTION,mc.DESCRIPTION) DESCRIPTION
						FROM $this->tbl_fpjp_detail fpjpd
						LEFT JOIN $this->tbl_fpjp_lines fpjpl ON fpjpd.fpjp_lines_id = fpjpl.fpjp_lines_id
						LEFT JOIN $this->tbl_master_coa mc ON fpjpd.id_master_coa = mc.id_master_coa
						LEFT JOIN ORACLE_SEGMENT5 os ON fpjpd.id_master_coa = os.id_master_coa
						where 1=1
						$where
						order by fpjpd.fpjp_detail_number";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $fpjp_lines_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_lines_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_fpjp_details_boq($fpjp_header_id){

		$keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
		$filterBy = ($this->input->post('filter')) ? $_POST['filter'] : "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("FPJP_DETAIL_DESC", "FPJP_DETAIL_AMOUNT", "FPJP_DETAIL_NAME", "PRICE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and fpjpl.fpjp_lines_id = ?";

		$mainQuery = "select * from $this->tbl_fpjp_boq
						where FPJP_HEADER_ID = ? ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $fpjp_header_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	function get_header($fpjp_header_id)
	{
		ini_set('memory_limit', '-1');
		
		$queryExec	= " SELECT distinct
						fh.SUBMITTER,
						fh.CURRENCY,
						fh.FPJP_AMOUNT,
						fh.ID_FS,
						fl.ID_FS ID_FS2,
						fh.STATUS,
						fh.JABATAN_SUBMITTER,
						fh.DIKETAHUI_1,
						fh.DIKETAHUI_2,
						fh.FPJP_NUMBER,
						fh.FPJP_NAME,
						fh.FPJP_DATE,
						mf.FPJP_NAME FPJP_TYPE,
						md.DIVISION_NAME,
						fl.PEMILIK_REKENING,
						fh.JABATAN_1,
						fh.JABATAN_2,
						fh.FPJP_VENDOR_NAME,
						fh.FPJP_BANK_NAME,
						fh.FPJP_ACC_NAME,
						fh.FPJP_ACC_NUMBER
						from FPJP_HEADER fh,
						MASTER_DIVISION md,
                        FPJP_DETAIL fd,
                        MASTER_FPJP mf,
                        FPJP_LINES fl
						where fh.ID_DIVISION = md.ID_DIVISION
                        and fh.ID_MASTER_FPJP = mf.ID_MASTER_FPJP
                        and fh.FPJP_HEADER_ID = fd.FPJP_HEADER_ID
                        and fh.FPJP_HEADER_ID = fl.FPJP_HEADER_ID
                        and fd.FPJP_HEADER_ID = ?";
		
		$data           = $this->db->query($queryExec, $fpjp_header_id)->result_array();
		$result['data'] = $data;

		return $result;
	}

	function get_detail_pdf($fpjp_header_id)
	{
		
		$query	= "SELECT FPJP_DETAIL_DESC, FPJP_DETAIL_AMOUNT,
							IFNULL(OS.FLEX_VALUE, MC.NATURE) NATURE, IFNULL(OS.VALUE_DESCRIPTION,MC.DESCRIPTION) DESCRIPTION
							FROM FPJP_DETAIL FD
							LEFT JOIN MASTER_COA MC ON FD.ID_MASTER_COA = MC.ID_MASTER_COA
							LEFT JOIN ORACLE_SEGMENT5 OS ON FD.ID_MASTER_COA = OS.ID_MASTER_COA
							WHERE FPJP_HEADER_ID = ?
							and FPJP_DETAIL_AMOUNT !=0
							order by FPJP_LINES_ID, FPJP_DETAIL_NUMBER + 0 ASC";
		

		return $this->db->query($query, $fpjp_header_id)->result_array();
	}

	function get_tax_pdf($fpjp_header_id)
	{
		$query	= "SELECT SUM(ROUND(FPJP_DETAIL_AMOUNT * (TAX/100))) TOTAL_AMOUNT_TAX , SUM(IFNULL(AMOUNT_PPN,0)) AMOUNT_PPN, TAX TOTAL_TAX
							FROM FPJP_DETAIL
							WHERE FPJP_HEADER_ID = ?
							AND TAX > 0
							and FPJP_DETAIL_AMOUNT !=0";
		
		return $this->db->query($query, $fpjp_header_id)->row_array();
	}

	function get_pph_pdf($fpjp_header_id)
	{
		
		$query	= "SELECT PPH, SUM(ROUND(FPJP_DETAIL_AMOUNT *(20/100))) TOTAL_PPH
							FROM FPJP_DETAIL
							WHERE FPJP_HEADER_ID = ? 
							and FPJP_DETAIL_AMOUNT !=0";
		
		return $this->db->query($query, $fpjp_header_id)->row_array();
	}

	function get_ppn($fpjp_header_id)
	{
		ini_set('memory_limit', '-1');
		
		$queryExec	= " SELECT case when fl.IS_PPN = 'Y' then sum(fd.FPJP_DETAIL_AMOUNT) * 10/100
						else sum(fd.FPJP_DETAIL_AMOUNT) * 0
						end PPN
						from FPJP_HEADER fh,
                        FPJP_DETAIL fd,
                        MASTER_FPJP mf,
                        FPJP_LINES fl
						where fh.ID_DIVISION = md.ID_DIVISION
                        and fh.FPJP_HEADER_ID = fd.FPJP_HEADER_ID
                        and fd.FPJP_HEADER_ID = ? 
						and FPJP_DETAIL_AMOUNT !=0";
		
		$queryData = query_datatable($queryExec);

		$total = $this->db->query($queryExec, $fpjp_header_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;
	}

	function get_total($fpjp_header_id)
	{
		ini_set('memory_limit', '-1');
		                        
		$queryExec	= " SELECT SUM(fd.FPJP_DETAIL_AMOUNT) FPJP_DETAIL_AMOUNT, fl.NO_REKENING, fl.NAMA_BANK, fl.PEMILIK_REKENING
						from FPJP_HEADER fh,
                        FPJP_DETAIL fd,
                        FPJP_LINES fl
						where fh.FPJP_HEADER_ID = fl.FPJP_HEADER_ID
                        and fl.FPJP_LINES_ID = fd.FPJP_LINES_ID
                        and fh.FPJP_HEADER_ID = ?
						and FPJP_DETAIL_AMOUNT !=0";
		
		$queryData = query_datatable($queryExec);

		$total = $this->db->query($queryExec, $fpjp_header_id)->num_rows();
		$data  = $this->db->query($queryData, $fpjp_header_id)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;
	}

	function get_vatsa($fpjp_header_id)
	{
		
		$query	= "SELECT VATSA, SUM(ROUND(FPJP_DETAIL_AMOUNT *(10/100))) TOTAL_VATSA
					FROM FPJP_DETAIL where FPJP_HEADER_ID = ?
					and PPH > 0
					and FPJP_DETAIL_AMOUNT";
		
		return $this->db->query($query, $fpjp_header_id)->row_array();
	}



	function get_fpjp_for_email($id_fpjp){

		$query = "SELECT FPJPH.ID_DIR_CODE, FPJPH.ID_DIVISION, FPJPH.ID_UNIT, FPJPH.ID_MASTER_FPJP, FPJPH.FPJP_NUMBER, FPJPH.FPJP_NAME,
							FPJPH.FPJP_AMOUNT, FPJPH.CURRENCY, FPJPH.CURRENCY_RATE,
							FPJPH.SUBMITTER, FPJPH.DOCUMENT_ATTACHMENT, FPJPL.ID_RKAP_LINE, FS.FS_NUMBER, FS.FS_NAME,
							BH.RKAP_DESCRIPTION, BH.TRIBE_USECASE, BH.CAPEX_OPEX, BH.MONTH
					FROM $this->tbl_fpjp_header FPJPH
					LEFT JOIN $this->tbl_fpjp_lines FPJPL ON FPJPH.FPJP_HEADER_ID = FPJPL.FPJP_HEADER_ID
					LEFT JOIN FS_BUDGET FS ON FPJPH.ID_FS = FS.ID_FS
					LEFT JOIN BUDGET_HEADER BH ON FPJPL.ID_RKAP_LINE = BH.ID_RKAP_LINE
					WHERE FPJPH.FPJP_HEADER_ID = ?
					LIMIT 1";
					
		return $this->db->query($query, $id_fpjp)->row_array();

	}


	function get_fpjp_for_email_accounting($id_fpjp){

		$query = "SELECT FPJPH.ID_DIR_CODE, FPJPH.ID_DIVISION, FPJPH.ID_UNIT, FPJPH.ID_MASTER_FPJP,
					FPJPH.FPJP_NUMBER, FPJPH.FPJP_NAME, FPJPH.FPJP_AMOUNT, FPJPH.CURRENCY,
					FPJPH.CURRENCY_RATE, FPJPH.SUBMITTER, FPJPH.DOCUMENT_ATTACHMENT, FPJPL.ID_RKAP_LINE,
					FS.FS_NUMBER, FS.FS_NAME, BH.RKAP_DESCRIPTION, BH.TRIBE_USECASE, BH.CAPEX_OPEX, 
					BH.MONTH, FPJPD.FPJP_DETAIL_DESC, FPJPD.TAX, FPJPD.FPJP_DETAIL_AMOUNT,
					IFNULL(OS.FLEX_VALUE, MC.NATURE) NATURE, IFNULL(OS.VALUE_DESCRIPTION,MC.DESCRIPTION) DESCRIPTION
					FROM FPJP_HEADER FPJPH
					LEFT JOIN FPJP_LINES FPJPL ON FPJPH.FPJP_HEADER_ID = FPJPL.FPJP_HEADER_ID		
					LEFT JOIN FPJP_DETAIL FPJPD ON FPJPL.FPJP_LINES_ID = FPJPD.FPJP_LINES_ID
					LEFT JOIN FS_BUDGET FS ON FPJPH.ID_FS = FS.ID_FS
					LEFT JOIN BUDGET_HEADER BH ON FPJPL.ID_RKAP_LINE = BH.ID_RKAP_LINE
					LEFT JOIN MASTER_COA MC ON FPJPD.ID_MASTER_COA = MC.ID_MASTER_COA
					LEFT JOIN ORACLE_SEGMENT5 OS ON FPJPD.ID_MASTER_COA = OS.ID_MASTER_COA
					WHERE FPJPH.FPJP_HEADER_ID = ?
					ORDER BY FPJPD.FPJP_DETAIL_ID";
							
		return $this->db->query($query, $id_fpjp)->result_array();

	}


	function check_is_approval($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("(PIC_LEVEL ='HOG User' or PIC_LEVEL ='CMO' or PIC_LEVEL ='CFO' or PIC_LEVEL ='COO' or PIC_LEVEL ='BOC' or PIC_LEVEL ='CTO' or PIC_LEVEL ='CEO')");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

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


	function count_pending_fpjp($pic_email){

		$mainQuery = "SELECT COUNT(ID) TOTAL FROM $this->tbl_trx_approval
							WHERE IS_ACTIVE  = 1
								AND ID_APPROVAL IN (SELECT ID_APPROVAL FROM $this->tbl_master_approval WHERE PIC_EMAIL = ? AND IS_EXIST = 1 AND IS_DELETED = 0)
								AND STATUS = 'request_approve'
								AND FPJP_HEADER_ID IN (SELECT FPJP_HEADER_ID FROM $this->tbl_fpjp_header WHERE STATUS = 'request_approve')";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}


	public function get_fpjp_to_approve_by_id($id_fpjp, $pic_email){

		$mainQuery = " SELECT FPJPH.*, MA.PIC_LEVEL, TRX.LEVEL, TRX.STATUS TRX_STATUS, TRX.UPDATED_DATE TRX_DATE
						FROM $this->tbl_master_approval MA
								inner join $this->tbl_trx_approval TRX on MA.ID_APPROVAL = TRX.ID_APPROVAL
								inner join $this->tbl_fpjp_header FPJPH on TRX.FPJP_HEADER_ID = FPJPH.FPJP_HEADER_ID
								WHERE FPJPH.FPJP_HEADER_ID = ?
								AND MA.PIC_EMAIL = ?";

		$query  = $this->db->query($mainQuery, array($id_fpjp, $pic_email));

		return $query->row_array();

	}

	function get_approval_before($id_fpjp){

		$mainQuery = " SELECT PIC_NAME, PIC_EMAIL, REMARK, trx.UPDATED_DATE TRX_DATE
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								WHERE trx.FPJP_HEADER_ID = ?
								AND STATUS != 'request_approve'
								order by trx.UPDATED_DATE DESC";

		$query  = $this->db->query($mainQuery, $id_fpjp);

		return $query->row_array();

	}


	function get_submitter_by_id_fpjp($id_fpjp){
		$sql = "SELECT SUBMITTER, PIC_EMAIL, COA_REVIEW, ALAMAT_EMAIL FROM $this->tbl_fpjp_header FPJP
					LEFT JOIN $this->tbl_master_approval MA ON FPJP.SUBMITTER = MA.PIC_NAME
					LEFT JOIN MASTER_EMPLOYEE ME ON FPJP.SUBMITTER = ME.NAMA
					WHERE FPJP_HEADER_ID = ? LIMIT 1";

        $query = $this->db->query($sql, $id_fpjp);

        return $query->row_array();
	}



	public function get_approver($id_fpjp, $level, $is_bod=false, $except_level = 0){

		$whereArr[] = $id_fpjp;
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
						FROM $this->tbl_trx_approval ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.FPJP_HEADER_ID = ?
								$where
								ORDER BY ta.id DESC";

		$query  = $this->db->query($mainQuery, $whereArr);

		if($is_bod){
			return $query->result_array();
		}else{
			return $query->row_array();
		}

	}

	function get_approval_by_fpjp($id_fpjp){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("FPJP_HEADER_ID", $id_fpjp);
        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();
	}

	function get_fpjp_for_approval($pic_email){

		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->select("FPJP_HEADER_ID");
		$this->db->join($this->tbl_master_approval." MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");

        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();

	}

	public function get_fpjp_to_approve($pic_email, $status=""){

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
		$whereVal[] = $pic_email;

		if($status != ""):
			$where .= " AND trx.STATUS = ?";
			if($status == "request_approve"){
				$where .= " AND FPJPH.STATUS = 'request_approve'";
			}
			$whereVal[] = $status;
		endif;

		$mainQuery = " SELECT FPJPH.FPJP_HEADER_ID, FPJPH.ID_DIR_CODE, FPJPH.ID_MASTER_FPJP, FPJPH.ID_DIVISION, FPJPH.ID_UNIT, FPJPH.FPJP_NUMBER,
						FPJPH.FPJP_NAME, FPJPH.STATUS, FPJPH.STATUS_DESCRIPTION, FPJPH.FPJP_DATE, FPJPH.FPJP_AMOUNT, FPJPH.SUBMITTER,
						FPJPH.APPROVAL_LEVEL, trx.LEVEL, FPJPH.CURRENCY, FPJPH.CURRENCY_RATE, FPJPH.DOCUMENT_ATTACHMENT, ma.PIC_LEVEL, trx.ID ID_FPJP_APPROVAL
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								inner join $this->tbl_fpjp_header FPJPH on trx.FPJP_HEADER_ID = FPJPH.FPJP_HEADER_ID
								WHERE ma.PIC_EMAIL = ?
								AND trx.STATUS is not NULL
								$where
								ORDER BY FPJPH.FPJP_HEADER_ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_comment_history($id_fpjp, $is_show=true){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK, LEVEL");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("FPJP_HEADER_ID", $id_fpjp);
		$this->db->where("STATUS !=", "request_approve");
		$this->db->where("STATUS IS NOT NULL");
		/*if($is_show == false){
			$this->db->where("MA.IS_SHOW", 1);
		}*/
		$this->db->order_by("TRX.ID");
        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();
	}

	function get_pending_fpjp_to_ap($id_fpjp=false){

		$where = ($id_fpjp) ? "AND FPJP_HEADER_ID = ?" : "";

		$query= "SELECT * FROM $this->tbl_fpjp_header
				WHERE STATUS = 'approved'
				AND COA_REVIEW = 'Y'
				$where
				AND FPJP_NUMBER NOT IN (SELECT DISTINCT NO_FPJP FROM GL_HEADERS WHERE DATE(CREATED_DATE) > '2021-04-15' AND NO_FPJP IS NOT NULL AND NO_FPJP != '')
				AND DATE(CREATED_DATE)  > '2021-04-15' ORDER BY UPDATED_DATE LIMIT 1";

		return $this->db->query($query, $id_fpjp)->result_array();

	}

}

/* End of file Purchase_mdl.php */
/* Location: ./application/models/Purchase_mdl.php */