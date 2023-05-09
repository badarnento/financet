<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_mdl extends CI_Model {

	protected	$tbl_directorat = "MASTER_DIRECTORAT",
				$tbl_division  = "MASTER_DIVISION",
				$tbl_unit      = "MASTER_UNIT",
				$tbl_tribe     = "MASTER_TRIBE",
				$tbl_coa       = "MASTER_COA",
				$tbl_rkap_line = "RKAP_LINE",
				$tbl_rkap_view = "BUDGET_HEADER",
				$tbl_fs_header = "FS_BUDGET",
				$tbl_fs_lines  = "FS_BUDGET_LINES",
				$tbl_fs_view   = "BUDGET_FINANCE_STUDY",
				$tbl_employee  = "MASTER_EMPLOYEE",
				$tbl_jabatan   = "MASTER_JABATAN";

	public function get_fs_header( $id_dir_code, $id_division, $id_unit, $category ="" ){

        $this->db->where("ID_DIR_CODE", $id_dir_code);
        $this->db->where("ID_DIVISION", $id_division);
        $this->db->where("ID_UNIT", $id_unit);
        $this->db->where("(lower(STATUS) = 'approved' or lower(STATUS) = 'fs used' )");

		$this->db->join($this->tbl_fs_lines. ' FSL', 'FSL.ID_FS = fsh.ID_FS');
        if($category == "fpjp"){
       		$this->db->where(" ((PROC_TYPE IS NOT NULL and  (lower(PROC_TYPE) like '%vm%'
										or lower(PROC_TYPE) like '%fpjp%'
			       						or lower(PROC_TYPE) like '%pks%'
			       						or lower(PROC_TYPE) like '%credit card%'
			       						or lower(PROC_TYPE) like '%reimbursement%'
			       						or lower(PROC_TYPE) = 'pengadaan langsung'
			       						or lower(PROC_TYPE) = 'pengadaan internal'
			       						or lower(PROC_TYPE) = 'sponsorship'
			       						or lower(PROC_TYPE) = 'virtual money'
			       						) )
       						OR (PROC_TYPE IS NULL OR PROC_TYPE = '' )) ");
        }
        elseif($category == "pr"){

       		$this->db->where("( (PROC_TYPE IS NOT NULL and  (lower(PROC_TYPE) like '%procurement%'
										or lower(PROC_TYPE) like '%tender%'
										or lower(PROC_TYPE) like '%mpa%'
										or lower(PROC_TYPE) = 'fppl'
										or lower(PROC_TYPE) = 'pengadaan khusus - pks'
										) )
       						OR (PROC_TYPE IS NULL OR PROC_TYPE = '' )) ");

       		$this->db->where('EXTRACT(YEAR FROM fsh.CREATED_DATE) >=', date('Y'));
        }

        $this->db->select("fsh.ID_FS, FS_NUMBER, FS_NAME, NOMINAL_FS, CURRENCY, CURRENCY_RATE, IS_DPL");
        $this->db->order_by("FS_NUMBER");

        $query = $this->db->get($this->tbl_fs_header." fsh");

        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }

	}


	public function get_fs_header_dpl( $id_dir_code, $id_division, $id_unit){

        $where = "";
		$whereArr = array();

		if($id_dir_code){
			$where .= " AND ID_DIR_CODE = ?";
			$whereArr[] = $id_dir_code;
		}
		if($id_division){
			$where .= " AND ID_DIVISION = ?";
			$whereArr[] = $id_division;
		}
		if($id_unit){
			$where .= " AND ID_UNIT = ?";
			$whereArr[] = $id_unit;
		}
        $sql = "SELECT fsh.ID_FS, FS_NUMBER, FS_NAME, NOMINAL_FS, IS_DPL
				FROM FS_BUDGET fsh
				JOIN FS_BUDGET_LINES FSL ON FSL.ID_FS = fsh.ID_FS
				WHERE 1 = 1
				AND lower(STATUS) = 'approved'
				AND lower(PROC_TYPE) like '%penunjukan langsung%'
				$where
				ORDER BY FS_NUMBER";
				
		$query = $this->db->query($sql, $whereArr);

        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }

	}

	public function get_division_by_dir_code( $id_dir_code ){

		$id_dir_code = (int) $id_dir_code;

        $this->db->where("ID_DIR_CODE", $id_dir_code);

        $query = $this->db->get($this->tbl_division);


        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }

	}


	public function get_unit_by_division($id_division){

		$id_division = (int) $id_division;

        $this->db->where("ID_DIVISION", $id_division);

        $query = $this->db->get($this->tbl_unit);

        
        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }

	}

	public function get_data_submiter($id_directorat="", $id_division=""){

		$where = "";
		$whereArr = array();

		if($id_directorat){
			$where .= " AND DIRECTORAT_NAME = ?";
			$whereArr[] = $id_directorat;
		}
		$mainQuery = "SELECT ID_EMPLOYEE, NIK, NAMA, JABATAN, ALAMAT_EMAIL
						FROM MASTER_EMPLOYEE
						WHERE 1=1
						$where
                        AND JABATAN LIKE 'Head of%' and jabatan like '%unit'
                        ";

		$query = $this->db->query($mainQuery, $whereArr);
        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }
	}

	
	public function get_all_tribe(){

        $query = $this->db->get($this->tbl_tribe);

        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }

	}

	public function get_all_rkap($directorat_name, $division_name, $unit_name, $tribe){

		if($directorat_name != ""){
			$this->db->where("DIRECTORAT", $directorat_name);
		}
		if($division_name != ""){
			$this->db->where("DIVISION", $division_name);
		}
		if($unit_name != ""){
			$this->db->where("UNIT", $unit_name);
		}
		if($tribe != ""){
			$this->db->where("TRIBE_USECASE", $tribe);
		}

        $query = $this->db->get($this->tbl_rkap_line);

        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }

	}

	public function get_rkap_from_view($category, $directorat_name="", $division_name="", $unit_name="", $list_id_rkap="", $su_budget=false){

		if($category == "division"){
			$this->db->select("DIVISION, mdiv.ID_DIVISION");
			$this->db->join($this->tbl_division. ' mdiv', 'trim(lower(mdiv.DIVISION_NAME)) = trim(lower(DIVISION))
															and mdiv.ID_DIR_CODE IN (SELECT ID_DIR_CODE FROM MASTER_DIRECTORAT WHERE DIRECTORAT_NAME = DIRECTORAT)');
			$this->db->group_by("DIVISION");
		}
		elseif($category == "unit"){
			$this->db->select("UNIT, mu.ID_UNIT");
			$this->db->join($this->tbl_division. ' mdiv', 'trim(lower(mdiv.DIVISION_NAME)) = trim(lower(DIVISION))
															and mdiv.ID_DIR_CODE IN (SELECT ID_DIR_CODE FROM MASTER_DIRECTORAT WHERE DIRECTORAT_NAME = DIRECTORAT)');
			$this->db->join($this->tbl_unit. ' mu', 'trim(lower(`mu`.`UNIT_NAME`)) = trim(lower(`UNIT`))
														and mu.ID_DIVISION = mdiv.ID_DIVISION');
			$this->db->group_by("UNIT");
		}
		elseif($category == "tribe"){
			$this->db->select("TRIBE_USECASE");
			$this->db->order_by("TRIBE_USECASE");
		}





		if($directorat_name != ""){
			if($category != "tribe"){
				$this->db->where("DIRECTORAT", $directorat_name);
			}
		}
		if($division_name != ""){
			// $this->db->where("DIVISION", $division_name);
			if($category != "tribe"){
				$this->db->where("DIVISION", $division_name);
			}
		}
		if($unit_name != ""){
			if($su_budget == false){
				$this->db->where("UNIT", $unit_name);
			}
		}
		if($list_id_rkap != ""){
			$this->db->where_in("ID_RKAP_LINE", $list_id_rkap);
		}

		$this->db->distinct();
        $query = $this->db->get($this->tbl_rkap_view);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->result_array();

        return $result;

	}

	public function get_all_rkap_from_view($directorat_name, $division_name, $unit_name, $tribe, $exclude_rkap, $list_id_rkap="", $su_budget=false){

		if($su_budget == false && $directorat_name != ""){
			$this->db->where("DIRECTORAT", $directorat_name);
		}
		if($su_budget == false && $division_name != ""){
			$this->db->where("DIVISION", $division_name);
		}
		if($su_budget == false && $unit_name != ""){
			$this->db->where("UNIT", $unit_name);
		}
		if($tribe != ""){
			$this->db->where("TRIBE_USECASE", $tribe);
		}
		if($list_id_rkap != ""){
			$this->db->where_in("ID_RKAP_LINE", $list_id_rkap);
		}
		if(is_array($exclude_rkap) && count($exclude_rkap) > 0){
			$this->db->where_not_in("ID_RKAP_LINE", $exclude_rkap);
		}
		if($su_budget == false){
			$this->db->where("lower(RKAP_DESCRIPTION) != ", "others");
		}

		$month = date("n", time());
		$month_z = date("m", time());
		$min_1 = $month-1;
		if($month > $min_1 && $su_budget == false){
			// $this->db->where("EXTRACT(MONTH FROM MONTH) > ".$min_1);
		}

		$this->db->where("FA_RKAP >", 0);

		$this->db->select("ID_RKAP_LINE, RKAP_DESCRIPTION, MONTH, PROC_TYPE");

        $query = $this->db->get($this->tbl_rkap_view);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->result_array();

        return $result;

	}

	public function get_rkap_dana_cadangan(){

		$year    = date("Y", time());
		$month   = date("n", time());
		$min_1   = $month-1;
		if($month > $min_1){
			// $this->db->where("EXTRACT(MONTH FROM MONTH) > ".$min_1);
		}

		$this->db->where("FA_RKAP >", 0);
		$this->db->like("RKAP_DESCRIPTION", 'Operational Loss Reserved');
		$this->db->select("ID_RKAP_LINE, RKAP_DESCRIPTION, MONTH, PROC_TYPE");

        $query = $this->db->get($this->tbl_rkap_view);

        return $query->result_array();

	}

	public function get_master_nature(){

		$natue_in = array(1,2,3,4,5);

		$this->db->where_in("ID_GROUP_RPT", $natue_in);

		$this->db->select("ID_MASTER_COA, NATURE, DESCRIPTION");

        $query = $this->db->get($this->tbl_coa);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->result_array();

        return $result;
	}
	
	public function get_nature_by_rkap($id_rkap_line=false){

		$where = "";

		if($id_rkap_line){
			$where = " and rl.ID_RKAP_LINE = ?";
		}

		$mainQuery = "SELECT DISTINCT mc.FLEX_VALUE NATURE, mc.VALUE_DESCRIPTION DESCRIPTION, pa.SUB_PARENT,
						mc.ID_MASTER_COA
						FROM ORACLE_SEGMENT5 mc,
						(SELECT DISTINCT rl.SUB_PARENT
						FROM
						RKAP_LINE rl where 1=1 $where) pa
						WHERE lower(pa.SUB_PARENT) = lower(mc.VALUE_DESCRIPTION)";

		$query = $this->db->query($mainQuery, $id_rkap_line);
		$total = $query->num_rows();

		if($total > 0){
			$result['total'] = $total;
			$data = $query->result_array();
		}else{

			// $mainQuery = "SELECT DISTINCT ID_MASTER_COA, NATURE, DESCRIPTION FROM MASTER_COA";
			$mainQuery = "SELECT DISTINCT ID_MASTER_COA, FLEX_VALUE NATURE, VALUE_DESCRIPTION DESCRIPTION FROM ORACLE_SEGMENT5";
		
			$query = $this->db->query($mainQuery);

			$total = $query->num_rows();
			$data  = $query->result_array();

		}

		$result['total'] = $total;
		$result['data']  = $data;

        return $result;
	}

	public function get_category_item(){

		$mainQuery = "SELECT distinct CATEGORY_NAME, CATEGORY_COA
						FROM MASTER_CATEGORY_BUYER
						ORDER BY 1 ASC";

		$query = $this->db->query($mainQuery);
		$total = $query->num_rows();

		if($total > 0){
			$result['total'] = $total;
			$data = $query->result_array();
		}

		$result['total'] = $total;
		$result['data']  = $data;

        return $result;
	}

	public function get_uom(){

		$mainQuery = "SELECT DISTINCT UOM_NAME FROM `MASTER_UOM`
						ORDER BY UOM_NAME";

		$query = $this->db->query($mainQuery);
		$total = $query->num_rows();

		if($total > 0){
			$result['total'] = $total;
			$data = $query->result_array();
		}

		$result['total'] = $total;
		$result['data']  = $data;

        return $result;
	}
	
	public function get_nature_by_rkap_edit($id_rkap_line=false){

		$where = "";

		$mainQuery = "SELECT DISTINCT ID_MASTER_COA, FLEX_VALUE NATURE, VALUE_DESCRIPTION DESCRIPTION
						FROM ORACLE_SEGMENT5";
		
		$query = $this->db->query($mainQuery);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->result_array();

        return $result;
	}

	public function get_fund_av_from_view($id_rkap){

		if($id_rkap != ""){
			$id_rkap =(int) $id_rkap;
			$this->db->where("ID_RKAP_LINE", $id_rkap);
		}

		$this->db->select("FA_FS");

        $query = $this->db->get($this->tbl_fs_view);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->row_array();

        return $result;

	}

	public function get_fa_fs($id_fs){

		if($id_fs != ""){
			$id_fs =(int) $id_fs;
			$this->db->where("ID_FS", $id_fs);
		}

		$this->db->select("FA_FS");
        $query = $this->db->get($this->tbl_fs_view);

        return $query->row_array();

	}

	public function get_fa_rkap($id_rkap){

		if($id_rkap != ""){
			$id_rkap =(int) $id_rkap;
			$this->db->where("ID_RKAP_LINE", $id_rkap);
		}

		$this->db->select("FA_RKAP");

        $query = $this->db->get($this->tbl_rkap_view);

		$result['total'] = $query->num_rows();
		$result['data']  = $query->row_array();

        return $result;

	}

	public function update_fs_status($id_fs, $status, $category){

		if(is_array($id_fs)){

			foreach ($id_fs as $key => $value) {

				$id_fs_val = $value['ID_FS'];

				if($category == "FPJP"){
					$tbl_pr_fpjp = "PR_LINES";
					$queryAdded = "SELECT count(PRH.ID_FS) FROM PR_LINES PRL
									LEFT JOIN PR_HEADER PRH ON PRL.PR_HEADER_ID = PRH.PR_HEADER_ID
									 WHERE PRH.ID_FS = ? AND lower(STATUS) != 'rejected'";
				}
				elseif($category == "PR"){
					$tbl_pr_fpjp = "FPJP_LINES";
					$queryAdded = "SELECT count(FPJPH.ID_FS) FROM FPJP_LINES FPJPL
									LEFT JOIN FPJP_HEADER FPJPH ON FPJPL.FPJP_HEADER_ID = FPJPH.FPJP_HEADER_ID
									 WHERE FPJPH.ID_FS = ? AND lower(STATUS) != 'rejected'";
				}


				$sql = "SELECT  ($queryAdded) FPJP_PR,
								(SELECT count(ID_BUDGET_REDIS) FROM BUDGET_REDIS WHERE ID_FS_SOURCE = ? AND ID_FS_DEST = ?) REDIS,
								(SELECT count(ID_BUDGET_RELOC) FROM BUDGET_RELOC WHERE ID_FS_SOURCE = ? AND ID_FS_DEST = ?) RELOC";

				$query  = $this->db->query($sql, array($id_fs_val,$id_fs_val,$id_fs_val,$id_fs_val,$id_fs_val));
				$result = $query->row_array();

				if($result['REDIS'] == 0 && $result['RELOC'] == 0 &&  $result['FPJP_PR'] == 0){
					$this->crud->update("FS_BUDGET", array("STATUS" => $status), array("ID_FS" => $id_fs_val));
				}

				$query->free_result();
			}

		}else{

				$id_fs_val = $id_fs;

				if($category == "FPJP"){
					$tbl_pr_fpjp = "PR_LINES";
					$queryAdded = "SELECT count(PRH.ID_FS) FROM PR_LINES PRL
									LEFT JOIN PR_HEADER PRH ON PRL.PR_HEADER_ID = PRH.PR_HEADER_ID
									 WHERE PRH.ID_FS = ? AND lower(STATUS) != 'rejected'";
				}
				elseif($category == "PR"){
					$tbl_pr_fpjp = "FPJP_LINES";
					$queryAdded = "SELECT count(FPJPH.ID_FS) FROM FPJP_LINES FPJPL
									LEFT JOIN FPJP_HEADER FPJPH ON FPJPL.FPJP_HEADER_ID = FPJPH.FPJP_HEADER_ID
									 WHERE FPJPH.ID_FS = ? AND lower(STATUS) != 'rejected'";
				}


				$sql = "SELECT  ($queryAdded) FPJP_PR,
								(SELECT count(ID_BUDGET_REDIS) FROM BUDGET_REDIS WHERE ID_FS_SOURCE = ? AND ID_FS_DEST = ?) REDIS,
								(SELECT count(ID_BUDGET_RELOC) FROM BUDGET_RELOC WHERE ID_FS_SOURCE = ? AND ID_FS_DEST = ?) RELOC";

				$query  = $this->db->query($sql, array($id_fs_val,$id_fs_val,$id_fs_val,$id_fs_val,$id_fs_val));
				$result = $query->row_array();

				if($result['REDIS'] == 0 && $result['RELOC'] == 0 && $result['FPJP_PR'] == 0){
					$this->crud->update("FS_BUDGET", array("STATUS" => $status), array("ID_FS" => $id_fs_val));
				}

				$query->free_result();
		}

		return true;

	}



	function get_program_id($directorat = false)
	{

		if($directorat){
			$this->db->where("DIRECTORAT", $directorat);
		}
		$this->db->where("ENTRY_OPTIMIZE IS NOT NULL");
		$this->db->distinct();
		$this->db->select("ENTRY_OPTIMIZE");
		$this->db->order_by("ENTRY_OPTIMIZE");

		return $this->db->get($this->tbl_rkap_view)->result_array();

	}


	function check_fs_exist($id_fs){

		$query = "SELECT
						(SELECT COUNT(ID_FS) FROM FPJP_HEADER WHERE ID_FS = ?) +
						(SELECT COUNT(ID_FS) FROM PR_HEADER WHERE ID_FS = ?)
						TOTAL
					FROM DUAL";

		return $this->db->query($query, array($id_fs, $id_fs))->row()->TOTAL;

	}

	
	function get_absortion_justif($id_fs=false, $id_rkarnap_line=false, $nominal_lines=false){

		if($id_fs){
			if(is_array($id_fs) ){
				$this->db->where_in("ID_FS", $id_fs);
			}else{
				$this->db->where("ID_FS", $id_fs);
			}
		}
		if($id_rkap_line){
			if(is_array($id_rkap_line) ){
				$this->db->where_in("ID_RKAP_LINE", $id_rkap_line);
			}else{
				$this->db->where("ID_RKAP_LINE", $id_rkap_line);
			}
		}
		if($nominal_lines){
			$this->db->where_in("NOMINAL_FS", $nominal_lines);
		}

		return $this->db->get("BUDGET_FINANCE_STUDY");

	}



}


/* End of file Budget_mdl.php */
// 
/* Location: ./application/models/Budget_mdl.php */
				