<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feasibility_study_mdl extends CI_Model {

	protected   $tbl_fs_header 		 = "FS_BUDGET",
				$tbl_fs_lines        = "FS_BUDGET_LINES",
				$tbl_rkap_view       = "BUDGET_HEADER",
				$tbl_master_approval = "MASTER_APPROVAL",
				$tbl_trx_approval    = "TRX_APPROVAL",
				$tbl_fs_view         = "BUDGET_FINANCE_STUDY";

	public function get_total_justification($year="", $directorat="", $division="", $unit=""){
		$this->db->select("count(*) TOTAL, STATUS");
		
		if($year != ""){
			$this->db->where("EXTRACT(YEAR FROM FS_DATE) =", $year);
		}
		if($directorat != ""){
			$this->db->where("ID_DIR_CODE", $directorat);
		}
		if($division != ""){
			if(is_array($division)){
				$this->db->where_in("ID_DIVISION", $division);
			}else{
				$this->db->where("ID_DIVISION", $division);
			}
		}
		if($unit != ""){
			if(is_array($unit)){
				$this->db->where_in("ID_UNIT", $unit);
			}else{
				$this->db->where("ID_UNIT", $unit);
			}
		}

		$this->db->group_by("STATUS");

		return $this->db->get($this->tbl_fs_header)->result_array();
	
	}
	public function get_summary_budget($year="", $directorat="", $division="", $unit=""){
		$this->db->select("SUM(NOMINAL) TOTAL_RKAP, SUM(FS) BUDGET_USED, SUM(RELOC_IN) TOTAL_RELOC_IN, SUM(RELOC_OUT) TOTAL_RELOC_OUT");
		
		if($year != ""){
			$this->db->where("EXTRACT(YEAR FROM MONTH) =", $year);
		}
		if($directorat != ""){
			$this->db->where("DIRECTORAT", $directorat);
		}
		if($division != ""){
			if(is_array($division)){
				$this->db->where_in("DIVISION", $division);
			}else{
				$this->db->where("DIVISION", $division);
			}
		}
		if($unit != ""){
			if(is_array($unit)){
				$this->db->where_in("UNIT", $unit);
			}else{
				$this->db->where("UNIT", $unit);
			}
		}

		return $this->db->get($this->tbl_rkap_view)->row_array();
		// $this->db->get($this->tbl_rkap_view);
		// echo $this->db->last_query();
		// die;
	}

	public function get_justification($year="", $directorat="", $division="", $unit=""){

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
			$fieldToSearch = array("FS_NUMBER", "FS_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		if($year != ""){
			$where .= " AND EXTRACT(YEAR FROM FS_DATE) = ?";
			$whereVal[] = $year;
		}
		if($directorat != ""){
			$where .= " AND ID_DIR_CODE = ?";
			$whereVal[] = $directorat;
		}

		if($division != ""){
			if(is_array($division)){
				$division_where = "";
				foreach ($division as $key => $value) {
					if($key == 0){
						$division_where .= "ID_DIVISION = ?";
					}else{
						$division_where .= " OR ID_DIVISION = ?";
					}
					$whereVal[] = $value;
				}
				$where .= " AND ($division_where)";
			}else{
				$where .= " AND ID_DIVISION = ?";
				$whereVal[] = $division;
			}
		}

		if($unit != ""){
			if(is_array($unit)){
				$unit_where = "";
				foreach ($unit as $key => $value) {
					if($key == 0){
						$unit_where .= "ID_UNIT = ?";
					}else{
						$unit_where .= " OR ID_UNIT = ?";
					}
					$whereVal[] = $value;
				}
				$where .= " AND ($unit_where)";
			}else{
				$where .= " AND ID_UNIT = ?";
				$whereVal[] = $unit;
			}
		}

		$mainQuery = "SELECT ID_FS, NOMINAL_FS, FS_NUMBER, FS_NAME, FS_DATE, CREATED_DATE, STATUS
						FROM $this->tbl_fs_header
						where 1=1
						$where
						order by ID_FS DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}


	public function get_fs_header($id_dir_code=false, $id_division=false, $id_unit=false, $status=false, $date_from, $date_to){

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
			$fieldToSearch = array("fsh.FS_NUMBER", "RKAP_DESCRIPTION", "fsh.FS_NAME", "fsh.NOMINAL_FS");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($id_dir_code){
			$where .= " and fsh.ID_DIR_CODE = ?";
			$whereVal[] = $id_dir_code;
		}

		if($id_division){
			$where .= " and fsh.ID_DIVISION = ?";
			$whereVal[] = $id_division;
		}

		if($id_unit){
			$where .= " and fsh.ID_UNIT = ?";
			$whereVal[] = $id_unit;
		}

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}

		$mainQuery = "SELECT fsv.RKAP_DESCRIPTION, fsv.MONTH, fsh.ID_FS, mdir.DIRECTORAT_NAME,
							mdiv.DIVISION_NAME, mu.UNIT_NAME, fsh.FS_NUMBER, fsh.FS_NAME,
							fsh.STATUS, fsh.FS_DATE, fsv.NOMINAL_FS,
							fsv.ABS_FPJP, fsv.ABS_PR, fsv.FA_FS, fsv.RELOC_IN, fsv.RELOC_OUT,
							fsh.SUBMITTER
						FROM FS_BUDGET fsh
						left join BUDGET_FINANCE_STUDY fsv ON fsh.ID_FS = fsv.ID_FS
						left join MASTER_DIRECTORAT mdir ON fsh.ID_DIR_CODE = mdir.ID_DIR_CODE
						left join MASTER_DIVISION mdiv ON fsh.ID_DIVISION = mdiv.ID_DIVISION
						left join MASTER_UNIT mu ON fsh.ID_UNIT = mu.ID_UNIT
						where CONVERT(fsh.FS_DATE, DATE) BETWEEN ? and ?
						$where
						order by fsh.ID_FS DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;

	}

	public function get_last_fs_number($id_dir_code){

		$this->db->select("FS_NUMBER");
		$this->db->where("ID_DIR_CODE", $id_dir_code);
		$this->db->order_by("ID_FS", "DESC");

		$query = $this->db->get($this->tbl_fs_header);

		return $query->row()->FS_NUMBER;

	}

	public function get_fs_lines($id_fs, $pr_fpjp = false){

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
			$fieldToSearch = array("FS_LINES_NAME", "rv.RKAP_DESCRIPTION", "fsbl.PROC_TYPE", "fsbl.PROC_TYPE_DESC", "FS_LINES_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and fsbh.id_fs = ?";

		if($pr_fpjp == "pr"){
			$where .= " AND ( (fsbl.PROC_TYPE IS NOT NULL and  (lower(fsbl.PROC_TYPE) like '%procurement%'
										or lower(fsbl.PROC_TYPE) like '%tender%'
										or lower(fsbl.PROC_TYPE) like '%mpa%'
										or lower(fsbl.PROC_TYPE) = 'fppl'
										or lower(fsbl.PROC_TYPE) = 'pengadaan khusus - pks'
										) )
       						OR (fsbl.PROC_TYPE IS NULL OR fsbl.PROC_TYPE = '' ))";

		}
		if($pr_fpjp == "fpjp"){
			$where .= " AND  ((fsbl.PROC_TYPE IS NOT NULL and  (lower(fsbl.PROC_TYPE) like '%vm%'
										or lower(fsbl.PROC_TYPE) like '%fpjp%'
			       						or lower(fsbl.PROC_TYPE) like '%pks%'
			       						or lower(fsbl.PROC_TYPE) like '%credit card%'
			       						or lower(fsbl.PROC_TYPE) like '%reimbursement%'
			       						or lower(fsbl.PROC_TYPE) = 'pengadaan langsung'
			       						or lower(fsbl.PROC_TYPE) = 'pengadaan internal'
			       						or lower(fsbl.PROC_TYPE) = 'sponsorship'
			       						or lower(fsbl.PROC_TYPE) = 'virtual money'
			       						) )
       						OR (fsbl.PROC_TYPE IS NULL OR fsbl.PROC_TYPE = '' ))";

		}

		$mainQuery = "SELECT fsbl.FS_LINES_ID, fsbl.ID_RKAP_LINE, fsbl.FS_LINES_NUMBER, fsbl.FS_LINES_AMOUNT, rv.DIRECTORAT, rv.DIVISION, rv.UNIT, fsbl.FS_LINES_NAME, fsbh.ID_DIR_CODE, fsbh.CURRENCY, rv.RKAP_DESCRIPTION, rv.TRIBE_USECASE, rv.ENTRY_OPTIMIZE, rv.FA_RKAP, rv.MONTH, fsbl.PROC_TYPE, fsbl.PROC_TYPE_DESC, fsbl.SERVICE_PERIOD_START, fsbl.SERVICE_PERIOD_END, fsbl.AMOUNT_CURRENCY, rvs.FA_FS
						FROM $this->tbl_fs_lines fsbl
						LEFT JOIN $this->tbl_fs_header fsbh ON fsbl.ID_FS = fsbh.ID_FS
						LEFT JOIN $this->tbl_rkap_view rv ON fsbl.ID_RKAP_LINE = rv.ID_RKAP_LINE						
						LEFT JOIN $this->tbl_fs_view rvs on  rvs.ID_FS = fsbl.ID_FS AND rvs.ID_RKAP_LINE = fsbl.ID_RKAP_LINE
						where 1=1
						$where
						order by fsbl.FS_LINES_NUMBER + 0 ASC ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $id_fs)->num_rows();
		$data  = $this->db->query($queryData, $id_fs)->result_array();
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_fs_lines_new($id_fs, $pr_fpjp = false){

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
			$fieldToSearch = array("FS_LINES_NAME", "PROC_TYPE", "FS_LINES_AMOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$where .= " and fsh.id_fs = ?";

		if($pr_fpjp == "pr"){
			$where .= " AND ( (PROC_TYPE IS NOT NULL and  (lower(PROC_TYPE) like '%procurement%'
										or lower(PROC_TYPE) like '%tender%'
										or lower(PROC_TYPE) like '%mpa%'
										or lower(PROC_TYPE) = 'fppl'
										or lower(PROC_TYPE) = 'pengadaan khusus – pks'
										) )
       						OR (PROC_TYPE IS NULL OR PROC_TYPE = '' ))";

		}
		if($pr_fpjp == "fpjp"){
			$where .= " AND  ((PROC_TYPE IS NOT NULL and  (lower(PROC_TYPE) like '%vm%'
										or lower(PROC_TYPE) like '%fpjp%'
			       						or lower(PROC_TYPE) like '%pks%'
			       						or lower(PROC_TYPE) like '%credit card%'
			       						or lower(PROC_TYPE) like '%reimbursement%'
			       						or lower(PROC_TYPE) = 'pengadaan langsung'
			       						or lower(PROC_TYPE) = 'pengadaan internal'
			       						or lower(PROC_TYPE) = 'sponsorship'
			       						or lower(PROC_TYPE) = 'virtual money'
			       						) )
       						OR (PROC_TYPE IS NULL OR PROC_TYPE = '' ))";

		}

		/*$mainQuery = "SELECT fsbl.FS_LINES_ID, fsbl.ID_RKAP_LINE, fsbl.FS_LINES_NUMBER, fsbl.FS_LINES_AMOUNT, rv.DIRECTORAT, rv.DIVISION, rv.UNIT, fsbl.FS_LINES_NAME, fsbh.ID_DIR_CODE, fsbh.CURRENCY, rv.RKAP_DESCRIPTION, rv.TRIBE_USECASE, rv.ENTRY_OPTIMIZE, rv.FA_RKAP, rv.MONTH, fsbl.PROC_TYPE, fsbl.PROC_TYPE_DESC, fsbl.SERVICE_PERIOD_START, fsbl.SERVICE_PERIOD_END, fsbl.AMOUNT_CURRENCY, rvs.FA_FS
						FROM $this->tbl_fs_lines fsbl
						LEFT JOIN $this->tbl_fs_header fsbh ON fsbl.ID_FS = fsbh.ID_FS
						LEFT JOIN $this->tbl_rkap_view rv ON fsbl.ID_RKAP_LINE = rv.ID_RKAP_LINE						
						LEFT JOIN $this->tbl_fs_view rvs on  rvs.ID_FS = fsbl.ID_FS AND rvs.ID_RKAP_LINE = fsbl.ID_RKAP_LINE
						where 1=1
						$where
						order by fsbl.FS_LINES_NUMBER + 0 ASC ";*/


		$mainQuery = "SELECT fsl.*, fsh.NOMINAL_FS, fsh.CURRENCY, mdir.DIRECTORAT_NAME DIRECTORAT, mdiv.DIVISION_NAME DIVISION, mu.UNIT_NAME UNIT
						FROM FS_BUDGET fsh
						left join FS_BUDGET_LINES fsl ON fsh.ID_FS = fsl.ID_FS
						left join MASTER_DIRECTORAT mdir ON fsh.ID_DIR_CODE = mdir.ID_DIR_CODE
						left join MASTER_DIVISION mdiv ON fsh.ID_DIVISION = mdiv.ID_DIVISION
						left join MASTER_UNIT mu ON fsh.ID_UNIT = mu.ID_UNIT
						where 1=1
						$where
						order by fsl.FS_LINES_NUMBER + 0 ASC ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $id_fs)->num_rows();
		$data  = $this->db->query($queryData, $id_fs)->result_array();
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	function get_download_inquiry($fs_date_from, $fs_date_to, $vdir, $vstat, $divisi, $unit){

		$where = "";
		$whereVal = array();

		$whereVal[] = $fs_date_from;
		$whereVal[] = $fs_date_to;

		if($vdir){
			$where .= " and fsh.ID_DIR_CODE = ?";
			$whereVal[] = $vdir;
		}
		if($divisi){
			$where .= " and fsh.ID_DIVISION = ?";
			$whereVal[] = $divisi;
		}
		if($unit){
			$where .= " and fsh.ID_UNIT = ?";
			$whereVal[] = $unit;
		}

		if($vstat){
			$where .= " and fsh.STATUS = ?";
			$whereVal[] = $vstat;
		}
						
		$queryExec = " SELECT fsv.RKAP_DESCRIPTION,fsv.TRIBE_USECASE, fsv.MONTH, fsh.ID_FS, fsh.ID_DIR_CODE, fsh.FS_NUMBER, fsh.FS_NAME, fsh.FS_DESCRIPTION, fsh.STATUS, fsl.SERVICE_PERIOD_START, fsl.SERVICE_PERIOD_END,
		fsh.FS_DATE, fsl.FS_LINES_AMOUNT NOMINAL_FS, fsv.ABS_FPJP, fsv.ABS_PR, fsv.FA_FS, fsv.RELOC_IN, fsv.RELOC_OUT, fsh.SUBMITTER, fsh.JABATAN_SUBMITER, fsv.DIRECTORAT, fsv.DIVISION, fsv.UNIT, fsl.PROC_TYPE
						FROM $this->tbl_fs_header fsh
						LEFT JOIN $this->tbl_fs_lines fsl ON fsl.ID_FS = fsh.ID_FS
						LEFT JOIN $this->tbl_rkap_view rv ON fsl.ID_RKAP_LINE = rv.ID_RKAP_LINE						
						LEFT JOIN $this->tbl_fs_view fsv on  fsv.ID_FS = fsl.ID_FS AND fsv.ID_RKAP_LINE = fsl.ID_RKAP_LINE
						where 1=1
						and fsh.FS_DATE BETWEEN ? and ?
						$where
						order by fsh.ID_FS DESC,  fsl.FS_LINES_NUMBER + 0 ASC";

		$query     = $this->db->query($queryExec, $whereVal);

		return $query;
	}

	function get_fs_status_approver($id_fs){

		$implode_fs = implode(",", $id_fs);

		$queryExec = "SELECT * FROM (
						SELECT DISTINCT ID_FS, 
						CASE
						    WHEN PIC_NAME = 'Ra Fira Wukir Andy' THEN 'Budget Team'
							WHEN PIC_NAME = 'Siska Agus Sulistiawati' THEN 'Budget Team'
							WHEN PIC_NAME = 'Evan G Pasaribu' THEN 'Budget Team'
							WHEN PIC_NAME = 'Vidya Sukmawati' THEN 'Budget Team'
						    ELSE PIC_NAME
						END APPROVER_NAME, STATUS
						FROM TRX_APPROVAL TRX
						LEFT JOIN MASTER_APPROVAL MA ON TRX.ID_APPROVAL = MA.ID_APPROVAL
						WHERE ID_FS IN ($implode_fs)
						AND STATUS IS NOT NULL
						AND IS_ACTIVE = 1
						ORDER BY LEVEL DESC, TRX.CREATED_DATE DESC
						) AS T1
			GROUP BY ID_FS";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	function get_pr_fpjp_by_fs($id_fs){

		$implode_fs = implode(",", $id_fs);

		$queryExec = "SELECT DISTINCT ID_FS, 
						IFNULL(( 	
								SELECT DISTINCT GROUP_CONCAT(DISTINCT PR_NUMBER SEPARATOR ', ') PR_NUMBER
								FROM PR_HEADER PRH
								INNER JOIN PR_LINES PRL ON PRL.PR_HEADER_ID = PRH.PR_HEADER_ID
								WHERE PRL.ID_FS = FS.ID_FS
								ORDER BY PRH.PR_HEADER_ID
						),'') PR_NUMBERS,						
						IFNULL(( 
								SELECT DISTINCT GROUP_CONCAT(DISTINCT FPJP_NUMBER SEPARATOR ', ') FPJP_NUMBER
								FROM FPJP_HEADER FPH
								INNER JOIN FPJP_LINES FPL ON FPL.FPJP_HEADER_ID = FPH.FPJP_HEADER_ID
								WHERE FPL.ID_FS = FS.ID_FS
								ORDER BY FPH.FPJP_HEADER_ID
						),'') FPJP_NUMBERS
						FROM  FS_BUDGET FS
						WHERE FS.STATUS != 'request_approve'
						AND ID_FS IN ($implode_fs)";
		log_query();
		$query     = $this->db->query($queryExec);

		return $query;
	}


	function get_fs_auto_reject(){

		$this->db->where("STATUS", "request_approve");
		$this->db->where("AUTO_REJECT_DATE", date("Y-m-d"));
        $this->db->select("ID_FS");

        $query = $this->db->get($this->tbl_fs_header);

    	return $query->result_array();
	}

	function get_approval($category, $directorat=0, $division=0, $unit=0){

		if($category == "HOG Budget" || $category == "CEO" || $category == "CFO"){

			$this->db->where("PIC_LEVEL", $category);

		}
		elseif($category == "BOD"){

			$this->db->where("ID_DIVISION", 0);
			$this->db->where("ID_UNIT", 0);
			$this->db->where("PIC_LEVEL !=", "HOG Budget");
			$this->db->where("PIC_LEVEL !=", "CFO");
			$this->db->where("PIC_LEVEL !=", "CEO");
		}
		else{

			if($category == "Director"){
				$this->db->where("ID_DIR_CODE", $directorat);
			}
			elseif($category == "HOG User"){
				$this->db->where("ID_DIR_CODE", $directorat);
				$this->db->where("ID_DIVISION", $division);
			}
			$this->db->where("PIC_LEVEL", $category);
		}

		$this->db->select("ID_APPROVAL, PIC_NAME, PIC_EMAIL");

		$query = $this->db->get($this->tbl_master_approval);

		if($category == "BOD"){
			return $query->result_array();
		}else{
			return $query->row_array();
		}

	}

	function get_approval_by_fs($id_fs, $is_show=true){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK, ALIAS");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("ID_FS", $id_fs);
		$this->db->where("TRX.IS_ACTIVE", 1);
		if($is_show == false){
			$this->db->where("MA.IS_SHOW", 1);
		}
        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();
	}

	function get_comment_history($id_fs, $is_show=true){
		$this->db->select("PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("ID_FS", $id_fs);
		$this->db->where("STATUS !=", "request_approve");
		$this->db->where("STATUS IS NOT NULL");
		if($is_show == false){
			$this->db->where("MA.IS_SHOW", 1);
		}
		$this->db->order_by("TRX.ID");
        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();
	}

	function get_approval_before($id_fs){

		$mainQuery = " SELECT PIC_NAME, PIC_EMAIL, REMARK, trx.UPDATED_DATE TRX_DATE
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								WHERE IS_ACTIVE = 1
								AND trx.ID_FS = ?
								AND STATUS != 'request_approve'
								order by trx.UPDATED_DATE DESC";

		$query  = $this->db->query($mainQuery, $id_fs);

		return $query->row_array();

	}

	public function get_approver($id_fs, $level, $is_bod=false, $except_level = 0){

		$whereArr[] = $id_fs;
		$where = "";

		if($is_bod){
			$where = " AND ta.category = 'BOD' ";
			$where .= "AND ma.PIC_LEVEL != 'CEO'";

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
								WHERE ta.id_fs = ?
								and ta.IS_ACTIVE = 1
								$where
								ORDER BY ta.id DESC";

		$query  = $this->db->query($mainQuery, $whereArr);

		if($is_bod){
			return $query->result_array();
		}else{
			return $query->row_array();
		}

	}

	public function get_multi_approval($id_fs, $category){

		$whereArr[] = $id_fs;
		$where = " AND lower(ta.category) = ?";
		$whereArr[] = $category;

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ta.LEVEL, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.id_fs = ?
								and ta.IS_ACTIVE = 1
								$where";

		$query  = $this->db->query($mainQuery, $whereArr);

		return $query->result_array();
		

	}
	
	public function get_multi_bod($id_fs, $status=""){

		$whereVal[] = $id_fs;
		$where = " AND ta.category = 'BOD'";

		if($status != ""):
			$where .= " AND ta.STATUS = ?";
			$whereVal[] = $status;
		endif;

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ta.LEVEL, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.IS_ACTIVE = 1
								and ta.id_fs = ?
								$where";

		$query  = $this->db->query($mainQuery, $whereVal);

		return $query->result_array();
		

	}

	public function get_list_trx($id_fs){
		$this->db->where("ID_FS", $id_fs);
		$this->db->order_by("ID");

		return $this->db->get($this->tbl_trx_approval)->result_array();
	}



	public function get_fs_to_approve($pic_email, $status=""){

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
			$fieldToSearch = array("fsh.FS_NUMBER", "fsh.FS_NAME", "fsh.NOMINAL_FS");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
		$whereVal[] = $pic_email;

		if($status != ""):
			$where .= " AND trx.STATUS = ?";
			if($status == "request_approve"){
				$where .= " AND fsh.STATUS = 'request_approve'";
			}
			$whereVal[] = $status;
			// $whereVal[] = $status;
		endif;

		$mainQuery = " SELECT fsh.ID_FS, fsh.ID_DIR_CODE, fsh.ID_DIVISION, fsh.ID_UNIT, fsh.FS_NUMBER,
						fsh.FS_NAME, fsh.FS_DESCRIPTION, fsh.STATUS, fsh.STATUS_DESCRIPTION, fsh.FS_DATE, fsh.NOMINAL_FS, fsh.SUBMITTER,
						fsh.APPROVAL_LEVEL, trx.LEVEL, fsh.CURRENCY, fsh.CURRENCY_RATE, fsh.DOCUMENT_ATTACHMENT, ma.PIC_LEVEL, trx.ID ID_FS_APPROVAL
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								inner join $this->tbl_fs_header fsh on trx.ID_FS = fsh.ID_FS
								WHERE ma.PIC_EMAIL = ?
								AND trx.STATUS is not NULL
								AND trx.IS_ACTIVE = 1
								$where
								ORDER BY fsh.ID_FS DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_fs_to_approve_by_id($id_fs, $id_trx, $pic_email){

		$mainQuery = " SELECT fsh.ID_FS, fsh.ID_DIR_CODE, fsh.ID_DIVISION, fsh.ID_UNIT, fsh.FS_NUMBER, fsh.CREATED_DATE,
						fsh.FS_NAME, fsh.FS_DESCRIPTION, fsh.STATUS, fsh.FS_DATE, fsh.NOMINAL_FS, fsh.SUBMITTER,
						fsh.APPROVAL_LEVEL, trx.LEVEL, fsh.CURRENCY, fsh.CURRENCY_RATE, fsh.DOCUMENT_ATTACHMENT, ma.PIC_LEVEL, fsh.JABATAN_SUBMITER, trx.STATUS TRX_STATUS, trx.UPDATED_DATE TRX_DATE, fsh.UPDATED_DATE, fsh.STATUS_DESCRIPTION,
						fsh.BOC_ATTACHMENT, fsh.RISK_ATTACHMENT, fsh.CFO_ATTACHMENT, fsh.JUSTIF_MOM_ATTACHMENT, fsh.IS_DPL, fsh.ID_DISTRICT
						FROM $this->tbl_master_approval ma
								inner join $this->tbl_trx_approval trx on ma.ID_APPROVAL = trx.ID_APPROVAL
								inner join $this->tbl_fs_header fsh on trx.ID_FS = fsh.ID_FS
								WHERE trx.IS_ACTIVE = 1
								AND fsh.ID_FS = ?
								AND trx.ID = ?
								AND ma.PIC_EMAIL = ?";

		$query  = $this->db->query($mainQuery, array($id_fs, $id_trx, $pic_email));

		return $query->row_array();

	}

	function get_fs_for_email($id_fs){
		$query = "SELECT FSH.ID_DIR_CODE, FSH.ID_DIVISION, FSH.ID_UNIT, FSH.FS_NUMBER, FSH.FS_NAME,
							FSH.FS_DESCRIPTION, FSH.NOMINAL_FS, FSH.CURRENCY, FSH.CURRENCY_RATE,
							FSH.SUBMITTER, FSH.DOCUMENT_ATTACHMENT, FSL.ID_RKAP_LINE, FSL.PROC_TYPE,
							BH.RKAP_DESCRIPTION, BH.TRIBE_USECASE, BH.CAPEX_OPEX, BH.MONTH
					FROM FS_BUDGET FSH, FS_BUDGET_LINES FSL
					LEFT JOIN BUDGET_HEADER BH ON FSL.ID_RKAP_LINE = BH.ID_RKAP_LINE
					WHERE FSH.ID_FS = ?
					AND FSL.ID_FS = FSH.ID_FS
					LIMIT 1";
					
		return $this->db->query($query, $id_fs)->row_array();

	}


	function get_data_approval($category, $directorat="", $division="", $unit="", $except_dircode=0){

		$this->db->select("ID_APPROVAL, PIC_NAME, PIC_EMAIL, PIC_LEVEL, JABATAN");

      	if($category == "HOG Budget" || $category == "Budget Admin" || $category == "Risk" || $category == "Fraud" || $category == "CEO" || $category == "CFO" || $category == "Budget Comitee" || $category == "BOC" || $category == "Procurement" || $category == "HoG Risk and FM" || $category == "HOU Procurement" || $category == "HOU Proc Support"):
			$this->db->where("PIC_LEVEL", $category);

		elseif($category == "BOD"):
			$this->db->where_in("PIC_LEVEL", array("CMO", "CTO", "COO"));
			$this->db->where("PIC_LEVEL !=", "CFO");
            $this->db->order_by("FIELD(PIC_LEVEL, 'CMO', 'CTO', 'COO', 'CEO')");
		else:

			$this->db->where("PIC_LEVEL", $category);
			if($directorat):
				$this->db->where("ID_DIR_CODE", $directorat);
			endif;
			if($division):
				$this->db->where("ID_DIVISION", $division);
			endif;
			if($unit):
				$this->db->where("ID_UNIT", $unit);
			endif;
      	endif;

      	if($except_dircode > 0):
      		$this->db->where("ID_DIR_CODE !=", $except_dircode);
      	endif;

  		$this->db->where("IS_EXIST", 1);
      	if($category == "Submitter" || $category == "BOD" || $category == "Fraud" || $category == "Budget Admin" || $category == "Procurement"):
			return $this->db->get($this->tbl_master_approval)->result_array();
      	else:
			return $this->db->get($this->tbl_master_approval)->row_array();
      	endif;
					
	}


	function check_is_approval($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where('PIC_LEVEL !=', "Submitter");
		$this->db->where('PIC_LEVEL !=', "Procurement");
		$this->db->where('PIC_LEVEL !=', "Procurement Buyer");
		$this->db->where('PIC_LEVEL !=', "HOU Procurement");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	function get_fs_from_approval($pic_email){

		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->select("ID_FS");
		$this->db->join($this->tbl_master_approval." MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");

        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();

	}

	function get_submitter_by_id_fs($id_fs){
		$sql = "SELECT SUBMITTER, PIC_EMAIL, ALAMAT_EMAIL FROM $this->tbl_fs_header FS
					LEFT JOIN $this->tbl_master_approval MA ON FS.SUBMITTER = MA.PIC_NAME
					LEFT JOIN MASTER_EMPLOYEE ME ON FS.SUBMITTER = ME.NAMA
					WHERE ID_FS = ? LIMIT 1";

        $query = $this->db->query($sql, $id_fs);

        return $query->row_array();
	}


	function count_pending_budget($pic_email){

		$mainQuery = "SELECT COUNT(ID) TOTAL FROM $this->tbl_trx_approval
							WHERE IS_ACTIVE  = 1
								AND ID_APPROVAL IN (SELECT ID_APPROVAL FROM $this->tbl_master_approval WHERE PIC_EMAIL = ? AND IS_EXIST = 1 AND IS_DELETED = 0)
								AND STATUS = 'request_approve'
								AND ID_FS IN (SELECT ID_FS FROM $this->tbl_fs_header WHERE STATUS = 'request_approve')";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}


	function get_cetak($fs_header_id){
						
		$queryExec = " SELECT fsv.RKAP_DESCRIPTION,fsv.TRIBE_USECASE, fsv.MONTH, fsh.ID_FS, fsh.ID_DIR_CODE, fsh.FS_NUMBER, fsh.FS_NAME, fsh.FS_DESCRIPTION, fsh.STATUS, fsh.DIKETAHUI_1, fsh.DIKETAHUI_2, fsh.JABATAN_1, fsh.JABATAN_2, fsl.SERVICE_PERIOD_START, fsl.SERVICE_PERIOD_END, fsh.CURRENCY,
		fsh.FS_DATE, fsh.NOMINAL_FS, fsv.ABS_FPJP, fsv.ABS_PR, fsv.FA_FS, fsv.RELOC_IN, fsv.RELOC_OUT, fsh.SUBMITTER, fsh.JABATAN_SUBMITER, fsv.DIRECTORAT, fsv.DIVISION, fsv.UNIT, fsl.PROC_TYPE
						FROM $this->tbl_fs_header fsh
						LEFT JOIN $this->tbl_fs_lines fsl ON fsl.ID_FS = fsh.ID_FS
						LEFT JOIN $this->tbl_rkap_view rv ON fsl.ID_RKAP_LINE = rv.ID_RKAP_LINE						
						LEFT JOIN $this->tbl_fs_view fsv on  fsv.ID_FS = fsl.ID_FS AND fsv.ID_RKAP_LINE = fsl.ID_RKAP_LINE
						where fsh.id_fs = ? ";

		$data  = $this->db->query($queryExec, $fs_header_id);

		return $data->row_array();
	}


	public function get_cetak_lines($id_fs){


		$queryExec = "SELECT fsbl.FS_LINES_ID, fsbl.ID_RKAP_LINE, fsbl.FS_LINES_NUMBER, fsbl.FS_LINES_AMOUNT, rv.DIRECTORAT, rv.DIVISION, rv.UNIT, fsbl.FS_LINES_NAME, fsbh.ID_DIR_CODE, fsbh.CURRENCY, rv.RKAP_DESCRIPTION, rv.TRIBE_USECASE, rv.ENTRY_OPTIMIZE, rv.FA_RKAP, rv.MONTH, fsbl.PROC_TYPE, fsbl.PROC_TYPE_DESC, fsbl.SERVICE_PERIOD_START, fsbl.SERVICE_PERIOD_END, fsbl.AMOUNT_CURRENCY, rvs.FA_FS
						FROM $this->tbl_fs_lines fsbl
						LEFT JOIN $this->tbl_fs_header fsbh ON fsbl.ID_FS = fsbh.ID_FS
						LEFT JOIN $this->tbl_rkap_view rv ON fsbl.ID_RKAP_LINE = rv.ID_RKAP_LINE						
						LEFT JOIN $this->tbl_fs_view rvs on  rvs.ID_FS = fsbl.ID_FS AND rvs.ID_RKAP_LINE = fsbl.ID_RKAP_LINE
						where fsbh.ID_FS = ? 
						order by fsbl.FS_LINES_NUMBER + 0 ASC ";

		return $this->db->query($queryExec, $id_fs);

	}

	function get_last_update_fs($id_fs){
		$this->db->select("PIC_NAME, STATUS, REMARK, TRX.CATEGORY, TRX.UPDATED_DATE");
		$this->db->join($this->tbl_master_approval." MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->where("ID_FS", $id_fs);
		$this->db->where("IS_ACTIVE", 1);
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