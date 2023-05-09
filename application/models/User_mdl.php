<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_mdl extends CI_Model {
	
	protected   $tbl_user 		 = "MASTER_USER",
				$tbl_group       = "MASTER_GROUPS",
				$tbl_user_group  = "MASTER_USER_GROUP";


	function get_all_user(){

		$this->db->select('*');
		$this->db->from($this->tbl_user);
		$this->db->order_by("id"); 

	    $query = $this->db->get();

	    if ( $query->num_rows() > 0 )
	    {
	        return $query->result_array();
	    }
	}

	function get_user_by_id($id){

		$sql = " SELECT mu.*, mg.id as GROUP_ID, mg.name as GROUP_NAME from $this->tbl_user mu
						INNER JOIN $this->tbl_user_group mug on mu.id = mug.user_id
						INNER JOIN $this->tbl_group mg on mg.id = mug.group_id
						where mu.id = ?";

		$query = $this->db->query($sql, $id);

	    if ( $query->num_rows() > 0 )
	    {
	        return $query->row_array();
	    }

    }

	function get_all_groups(){

		$this->db->select('*');
		$this->db->from("MASTER_GROUPS");
		$this->db->order_by("id");

	    $query = $this->db->get();

		$query = $this->db->get($this->tbl_user);
		$total = $query->num_rows();
		$data  = $query->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	
	}


    // punya badar
	// function get_user_datatable(){

	// 	$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
	// 	$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

	// 	$where    = "";
	// 	if($keywords != ""){
	// 		$fieldToSearch = array("U.USERNAME" ,"U.EMAIL", "U.DISPLAY_NAME", "D.DIRECTORAT_NAME");
	// 		$where = query_datatable_search($keywords, $fieldToSearch);
	// 	}

	// 	$sql = "SELECT U.ID, U.DISPLAY_NAME, U.USERNAME, U.EMAIL, U.IS_ACTIVE, U.ID_DIR_CODE, D.DIRECTORAT_NAME
	// 			FROM $this->tbl_user U LEFT JOIN MASTER_DIRECTORAT D ON IFNULL(U.ID_DIR_CODE, '') = IFNULL(D.ID_DIR_CODE, '')  
	// 			WHERE ID NOT IN ? $where";
				
	// 	$query = $this->db->query($sql, array(array(1, $this->session->userdata('user_id'))));

	// 	$total = $query->num_rows();
	// 	$data  = $query->result_array();

	// 	$result['data']       = $data;
	// 	$result['total_data'] = $total;

	// 	return $result;	

	// }

	function get_user_datatable(){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("U.USERNAME" ,"U.EMAIL", "U.DISPLAY_NAME", "D.DIRECTORAT_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$sql = "SELECT U.ID, U.DISPLAY_NAME, U.USERNAME, U.EMAIL, U.IS_ACTIVE, U.ID_DIR_CODE, D.DIRECTORAT_NAME
				FROM $this->tbl_user U LEFT JOIN MASTER_DIRECTORAT D ON IFNULL(U.ID_DIR_CODE, '') = IFNULL(D.ID_DIR_CODE, '')  
				WHERE ID NOT IN ? $where";

		$queryData = query_datatable_nolimit($sql);

		$total = $this->db->query($sql, array(array(1, $this->session->userdata('user_id'))))->num_rows();
		$data  = $this->db->query($queryData, array(array(1, $this->session->userdata('user_id'))))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

}

/* End of file Siswa_mdl.php */
/* Location: ./application/models/Siswa_mdl.php */