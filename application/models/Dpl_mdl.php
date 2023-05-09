<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dpl_mdl extends CI_Model {

	protected   $tbl_dpl         = "DPL",
				$tbl_fs_header       = "FS_BUDGET",
				$tbl_fs_lines        = "FS_BUDGET_LINES",
				$tbl_master_approval = "MASTER_APPROVAL",
				$tbl_trx_approval    = "TRX_APPROVAL_DPL";


	public function get_dpl($status=false, $id_dir_code=false, $id_division=false, $id_unit=false, $date_from, $date_to){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("DPL_NAME");
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$whereVal = array();

		if($status){
			$where .= " and STATUS = ?";
			$whereVal[] = $status;
		}
		
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

		if($date_from){
			$where .= " and CONVERT(CREATED_DATE, DATE) BETWEEN ? and ? ";
			$whereVal[] = $date_from;
			$whereVal[] = $date_to;
		}

		// if($date_to){
		// 	$where .= " and DATE_TO BETWEEN ?";
		// 	$whereVal[] = $date_to;
		// }

		$mainQuery = "SELECT * FROM $this->tbl_dpl where 1=1 $where order by ID_DPL DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		// echo $this->db->last_query();die;

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}

	public function get_vendor(){

		$mainQuery = "select NAMA_VENDOR from MASTER_VENDOR
						order by NAMA_VENDOR desc ";

		$query = $this->db->query($mainQuery);
        if ( $query->num_rows() > 0 ){
            return $query->result_array();
        }
	}

	public function get_last_dpl_number($id_dir_code){

		$this->db->select("DPL_NUMBER");
		$this->db->where("ID_DIR_CODE", $id_dir_code);
		$this->db->order_by("ID_DPL", "DESC");

		$query = $this->db->get($this->tbl_dpl);

		return $query->row()->DPL_NUMBER;

	}

	public function get_vendor_create(){

		$mainQuery	        = "SELECT DISTINCT NAMA_VENDOR
								FROM MASTER_VENDOR
								order by NAMA_VENDOR desc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	function get_cetak($id_dpl)
	{
		
		$this->db->where("ID_DPL", $id_dpl);

		return $this->db->get($this->tbl_dpl)->row_array();
	}

	function get_last_justif($submitter)
	{
		
		$sql = "SELECT FS.ID_FS, ID_DIR_CODE, ID_DIVISION, ID_UNIT, FS_NUMBER, FS_NAME,
						SUBMITTER, JABATAN_SUBMITER, NOMINAL_FS,
						DATE_FORMAT(SERVICE_PERIOD_START, '%d-%m-%Y') DATE_FROM,
						DATE_FORMAT(SERVICE_PERIOD_END, '%d-%m-%Y') DATE_TO
				FROM $this->tbl_fs_header FS
				JOIN $this->tbl_fs_lines FSL ON FS.ID_FS = FSL.ID_FS
				WHERE FS.CREATED_BY = ?
				AND lower(PROC_TYPE) like '%penunjukan langsung%'
				AND iS_DPL = 1
				ORDER BY FS.ID_FS DESC LIMIT 1";
	
		return $this->db->query($sql, $submitter)->row_array();
	}


	function get_approval_by_dpl($id_dpl){
		$this->db->select("MA.ID_APPROVAL, PIC_NAME, CATEGORY, PIC_EMAIL, JABATAN, STATUS, TRX.UPDATED_DATE, REMARK, ALIAS");
		$this->db->join($this->tbl_master_approval . " MA", "MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->order_by("TRX.ID");
		$this->db->where("ID_DPL", $id_dpl);
		$this->db->where("TRX.IS_ACTIVE", 1);
		
        $query = $this->db->get( $this->tbl_trx_approval ." TRX");

        return $query->result_array();
	}

	function check_is_verifier($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("(PIC_LEVEL ='HoG Procurement' or PIC_LEVEL = 'SVP Risk' or PIC_LEVEL ='HoG Risk and FM')");
	    $query = $this->db->get($this->tbl_master_approval . "");
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}


	function count_dpl_pending_verification($pic_email){

	    $sql = "SELECT PIC_LEVEL FROM $this->tbl_master_approval WHERE IS_EXIST = 1 AND (PIC_LEVEL ='HoG Procurement' or PIC_LEVEL = 'SVP Risk' or PIC_LEVEL ='HoG Risk and FM') AND PIC_EMAIL = ?";
	    $get_verifier = $this->db->query($sql, $pic_email)->row_array();

	    if($get_verifier){

	    	$level = $get_verifier['PIC_LEVEL'];
	    	$where = "";

	    	if($level == "HoG Procurement"):
	    		$where = " AND STATUS_VERIF = 0";
	    		$where .= " AND VERIFIKASI_PROC_EMAIL = ?";
	    	endif;
	    	if($level == "SVP Risk" || $level == "HoG Risk and FM"):
	    		$where = " AND STATUS_VERIF = 1";
	    		$where .= " AND VERIFIKASI_RISK_EMAIL = ?";
	    	endif;

	    	$sql = "SELECT * FROM $this->tbl_dpl
	    				inner join FS_BUDGET FS on FS.ID_FS = DPL.ID_FS
	    			WHERE DPL.STATUS = 'wait_verify'  $where";

			$total = $this->db->query($sql, $pic_email)->num_rows();

	    }else{
	    	$total = 0;
	    }

        return $total;

	}
	function get_dpl_from_approval($pic_email){

		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where('IS_ACTIVE', 1);
		$this->db->select("ID_DPL");
		$this->db->join($this->tbl_master_approval . " MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");

        $query = $this->db->get( $this->tbl_trx_approval ." TRX");

        return $query->result_array();

	}


	public function get_dpl_to_verify($pic_email, $status="", $level){

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
			$fieldToSearch = array("DPL.DPL_NUMBER");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

    	if($level == "HoG Procurement"):
    		if($status == "wait_verify"){
    			$where .= " AND STATUS_VERIF = 0";
				$where .= " AND DPL.STATUS = 'wait_verify'";
			}elseif($status == "verified"){
    			$where .= " AND STATUS_VERIF = 1";
    			$where .= " AND VERIFIKASI_PROC = 'verified'";
			}
			elseif($status == "rejected"){
				$where .= " AND DPL.STATUS = 'rejected'";
    			$where .= " AND STATUS_VERIF = 0";
    			$where .= " AND VERIFIKASI_PROC = 'rejected'";
			}
			$where .= " AND VERIFIKASI_PROC_EMAIL = ?";
    	endif;
    	if($level == "SVP Risk" || $level == "HoG Risk and FM"):

    		if($status == "wait_verify"){
    			$where = " AND STATUS_VERIF = 1";
				$where .= " AND DPL.STATUS = 'wait_verify'";
			}elseif($status == "verified"){
    			$where .= " AND STATUS_VERIF > 1";
    			$where .= " AND VERIFIKASI_RISK = 'verified'";
			}
			elseif($status == "rejected"){
    			$where .= " AND STATUS_VERIF = 1";
				$where .= " AND DPL.STATUS = 'rejected'";
			}else{
    			$where .= " AND STATUS_VERIF > 0";
			}
			$where .= " AND VERIFIKASI_RISK_EMAIL = ?";
    	endif;

		$mainQuery = " SELECT DPL.ID_DPL, DPL.ID_DIR_CODE, DPL.ID_DIVISION, DPL.ID_UNIT, DPL.DPL_NUMBER, DPL.STATUS, DPL.PIC_USER, FS_NUMBER, FS_NAME
						FROM $this->tbl_dpl DPL
						inner join $this->tbl_fs_header FS on FS.ID_FS = DPL.ID_FS
						where 1=1
						$where
						ORDER BY DPL.ID_DPL DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $pic_email)->num_rows();
		$data  = $this->db->query($queryData, $pic_email)->result_array();
		// echo $this->db->last_query();die;
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_dpl_to_approve($pic_email, $status=""){

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
			$fieldToSearch = array("DPL.DPL_NUMBER", "FS.FS_NAME", "FS.FS_NUMBER");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
		$whereVal[] = $pic_email;

		if($status != ""):
			$where .= " AND TRX.status = ?";
			$whereVal[] = $status;
		endif;

		$mainQuery = " SELECT DPL.ID_DPL, DPL.ID_DIR_CODE, DPL.ID_DIVISION, DPL.ID_UNIT, DPL.DPL_NUMBER, DPL.STATUS, DPL.PIC_USER, DPL.STATUS_DESCRIPTION,
						FS.FS_NUMBER, FS.FS_NAME,
						MA.PIC_LEVEL, TRX.ID ID_DPL_APPROVAL, TRX.LEVEL
						FROM $this->tbl_master_approval MA
							inner join $this->tbl_trx_approval TRX on MA.ID_APPROVAL = TRX.ID_APPROVAL
							inner join $this->tbl_dpl DPL on TRX.ID_DPL = DPL.ID_DPL
							inner join $this->tbl_fs_header FS on FS.ID_FS = DPL.ID_FS AND (FS.STATUS = 'approved' OR FS.ID_FS IN (SELECT ID_FS FROM TRX_APPROVAL WHERE STATUS = 'approved' AND IS_ACTIVE = 1 AND ID_FS = FS.ID_FS) )
							WHERE MA.PIC_EMAIL = ?
							AND TRX.STATUS IS NOT NULL
							AND TRX.IS_ACTIVE = 1
							$where
							ORDER BY DPL.ID_DPL DESC";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();
		// echo $this->db->last_query();die;

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	function get_level_verifier($pic_email){

		$sql = "SELECT PIC_LEVEL FROM $this->tbl_master_approval WHERE IS_EXIST = 1 AND (PIC_LEVEL ='HoG Procurement' or PIC_LEVEL = 'SVP Risk' or PIC_LEVEL ='HoG Risk and FM') AND PIC_EMAIL = ?";
	    $get_verifier = $this->db->query($sql, $pic_email)->row_array();

	    return $get_verifier['PIC_LEVEL'];
	}

	function get_submitter_by_id_dpl($id_dpl){
		$sql = "SELECT PIC_USER, PIC_EMAIL FROM $this->tbl_dpl DPL
					LEFT JOIN $this->tbl_master_approval MA ON CONCAT(DPL.CREATED_BY, '@linkaja.id') = MA.PIC_EMAIL
					WHERE ID_DPL = ? LIMIT 1";

        $query = $this->db->query($sql, $id_dpl);

        return $query->row_array();
	}

	function get_approver_first($id_dpl){
		$sql = "SELECT TRX.*, MA.PIC_EMAIL, MA.PIC_NAME FROM $this->tbl_trx_approval TRX
					INNER JOIN MASTER_APPROVAL MA ON MA.ID_APPROVAL = TRX.ID_APPROVAL
					WHERE TRX.ID_DPL = ?
					AND TRX.IS_ACTIVE = 1
					AND TRX.STATUS IS NULL
					ORDER BY TRX.ID LIMIT 1";

        $query = $this->db->query($sql, $id_dpl);

        return $query->row_array();
	}

	function get_verifier_list($id_dpl){
	
		$sql = "SELECT PIC_NAME,PIC_LEVEL,PIC_EMAIL,MA.JABATAN,STATUS, STATUS_VERIF,VERIFIKASI_PROC,VERIFIKASI_RISK, VERIFIKASI_RISK_DATE, VERIFIKASI_PROC_DATE
				FROM $this->tbl_master_approval MA, DPL
				WHERE 1=1
				AND PIC_LEVEL IN ('HoG Procurement', 'SVP Risk','HoG Risk and FM')
				AND (PIC_EMAIL = DPL.VERIFIKASI_PROC_EMAIL OR PIC_EMAIL = DPL.VERIFIKASI_RISK_EMAIL)
				AND ID_DPL = ?
				ORDER BY FIELD(PIC_LEVEL, 'HoG Procurement') DESC";

        $query = $this->db->query($sql, $id_dpl);

        return $query->result_array();
	}

	public function get_approver($id_dpl, $level, $is_bod=false, $except_level = 0){

		$whereArr[] = $id_dpl;
		$where = "";

		if($is_bod){
			$where = " AND ta.category = 'BOD' ";
			$where .= "AND ma.PIC_LEVEL != 'CEO'";

		}else{
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
								WHERE ta.ID_DPL = ?
								and ta.IS_ACTIVE = 1
								$where
								ORDER BY ta.ID DESC";

		$query  = $this->db->query($mainQuery, $whereArr);

		if($is_bod){
			return $query->result_array();
		}else{
			return $query->row_array();
		}

	}

	
	public function get_multi_bod($id_dpl, $status=""){

		$whereVal[] = $id_dpl;
		$where = " AND ta.category = 'BOD'";

		if($status != ""):
			$where .= " AND ta.STATUS = ?";
			$whereVal[] = $status;
		endif;

		$mainQuery = "SELECT ta.ID, ta.STATUS, ta.UPDATED_DATE, ma.PIC_NAME, ta.LEVEL, ma.PIC_EMAIL, ma.ID_APPROVAL, ta.CATEGORY
						FROM $this->tbl_trx_approval ta
								inner join $this->tbl_master_approval ma on ta.id_approval = ma.id_approval
								WHERE ta.IS_ACTIVE = 1
								and ta.ID_DPL = ?
								$where";

		$query  = $this->db->query($mainQuery, $whereVal);

		return $query->result_array();
		

	}


	function check_is_approval_dpl($pic_email){

		$this->db->where('IS_EXIST', 1);
		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where("(PIC_LEVEL ='CMO' or PIC_LEVEL = 'CFO' or PIC_LEVEL ='COO' or PIC_LEVEL ='CTO' or PIC_LEVEL ='CEO' or PIC_LEVEL ='HOG Budget' or PIC_LEVEL ='HOG User')");
	    $query = $this->db->get($this->tbl_master_approval);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }

	}

	function check_request_approval_dpl($pic_email){

		$mainQuery = "SELECT SUM((SELECT COUNT(ID) FROM $this->tbl_trx_approval
								WHERE ID_APPROVAL = MA.ID_APPROVAL
								AND STATUS = 'request_approve'
								AND IS_ACTIVE = 1
								AND ID_DPL IN (    SELECT ID_DPL FROM $this->tbl_dpl WHERE STATUS_VERIF = 2 AND STATUS = 'request_approve'
													AND ID_FS IN (SELECT ID_FS FROM $this->tbl_fs_header WHERE STATUS = 'approved')
												)
								)) TOTAL
						FROM $this->tbl_master_approval MA
						WHERE MA.PIC_EMAIL = ?
						GROUP BY MA.PIC_EMAIL";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}



	function count_pending_dpl($pic_email){

		/*$mainQuery = "SELECT SUM((SELECT COUNT(ID) FROM $this->tbl_trx_approval
								WHERE ID_APPROVAL = MA.ID_APPROVAL
								AND STATUS = 'request_approve'
								AND IS_ACTIVE = 1
								AND ID_DPL IN (    SELECT ID_DPL FROM $this->tbl_dpl WHERE STATUS_VERIF = 2 AND STATUS = 'request_approve'
													AND  ID_FS IN (SELECT ID_FS FROM $this->tbl_fs_header WHERE STATUS = 'approved')
												)
								)) TOTAL
						FROM $this->tbl_master_approval MA
						WHERE MA.PIC_EMAIL = ?
						GROUP BY MA.PIC_EMAIL";*/

		$mainQuery = "SELECT SUM((SELECT COUNT(ID) FROM $this->tbl_trx_approval
								WHERE ID_APPROVAL = MA.ID_APPROVAL
								AND STATUS = 'request_approve'
								AND IS_ACTIVE = 1
								AND ID_DPL IN ( SELECT ID_DPL FROM $this->tbl_dpl WHERE STATUS_VERIF = 2 AND STATUS = 'request_approve'
													AND  ( 
															ID_FS IN (SELECT ID_FS FROM $this->tbl_fs_header WHERE STATUS = 'approved') 
															OR
															ID_FS IN (SELECT ID_FS FROM TRX_APPROVAL WHERE STATUS = 'approved' AND IS_ACTIVE = 1 AND ID_FS = DPL.ID_FS)
															)  )
								)) TOTAL
						FROM $this->tbl_master_approval MA
						WHERE MA.PIC_EMAIL = ?
						GROUP BY MA.PIC_EMAIL";

		return $this->db->query($mainQuery, $pic_email)->row_array();
	}


	function get_dpl_for_approval($pic_email){

		$this->db->where('PIC_EMAIL', $pic_email);
		$this->db->where('TRX.IS_ACTIVE', 1);
		$this->db->select("TRX.ID_DPL");
		$this->db->join($this->tbl_master_approval." MA","MA.ID_APPROVAL = TRX.ID_APPROVAL");
		$this->db->join($this->tbl_dpl." DPL","DPL.ID_DPL = TRX.ID_DPL AND DPL.STATUS_VERIF = 2");
		$this->db->join($this->tbl_fs_header." FS","FS.ID_FS = DPL.ID_FS AND FS.STATUS = 'approved'");

        $query = $this->db->get($this->tbl_trx_approval." TRX");

        return $query->result_array();

	}





	public function get_dpl_to_approve_by_id($id_dpl, $pic_email){

		$mainQuery = "SELECT DPL.*, FS.FS_NAME, FS.FS_NUMBER, TRX.LEVEL, MA.PIC_LEVEL, TRX.STATUS TRX_STATUS, TRX.UPDATED_DATE TRX_DATE
						 FROM $this->tbl_master_approval MA
						 inner join $this->tbl_trx_approval TRX on MA.ID_APPROVAL = TRX.ID_APPROVAL
						 inner join $this->tbl_dpl DPL on TRX.ID_DPL = DPL.ID_DPL
						 inner join $this->tbl_fs_header FS on DPL.ID_FS = FS.ID_FS
						WHERE DPL.ID_DPL = ? AND MA.PIC_EMAIL = ?";

		$query  = $this->db->query($mainQuery, array($id_dpl, $pic_email));

		return $query->row_array();

	}

	function get_dpl_for_email($id_dpl){
		$query = "SELECT DPL.DPL_NUMBER, PIC_USER, KRITERIA, REKANAN, FS.FS_NAME, FS.FS_NUMBER, FS.NOMINAL_FS
					FROM DPL
					LEFT JOIN FS_BUDGET FS ON DPL.ID_FS = FS.ID_FS
					WHERE DPL.ID_DPL = ?";

		return $this->db->query($query, $id_dpl)->row_array();

	}

	function get_risk_verifier($id_dpl){
		$query = "
					SELECT PIC_NAME, PIC_EMAIL FROM DPL
					JOIN MASTER_APPROVAL MA ON DPL.VERIFIKASI_RISK_EMAIL = MA.PIC_EMAIL
					WHERE ID_DPL = ?
					LIMIT 1";

		return $this->db->query($query, $id_dpl)->row_array();

	}

	function get_proc_verifier($id_dpl){
		$query = "
					SELECT PIC_NAME, PIC_EMAIL FROM DPL
					JOIN MASTER_APPROVAL MA ON DPL.VERIFIKASI_PROC_EMAIL = MA.PIC_EMAIL
					WHERE ID_DPL = ?
					LIMIT 1";

		return $this->db->query($query, $id_dpl)->row_array();

	}
	
	/*public function check_is_dpl_approval($id_dpl, $id_approval){

		$whereVal[] = $id_dpl;
		$whereVal[] = $id_approval;

		$mainQuery = "SELECT * FROM $this->tbl_trx_approval
						WHERE ID_DPL = ?
						AND ID_APPROVAL = ?";

		$query  = $this->db->query($mainQuery, $whereVal);

		return $query->result_array();
		

	}*/

}

/* End of file Rkap_mdl.php */
/* Location: ./application/models/Rkap_mdl.php */