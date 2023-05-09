<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_mdl extends CI_Model {
	
	protected   $tbl_group      = "MASTER_GROUPS",
				$tbl_menu       = "MASTER_DYN_MENU",
				$tbl_group_menu = "MASTER_GROUP_MENU";

	function get_group_datatable(){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("NAME", "DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$this->db->select("ID, NAME, DESCRIPTION");
		$this->db->where("ID!=1".$where);

		$query = $this->db->get($this->tbl_group);
		$total = $query->num_rows();
		$data  = $query->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_group_menu($id=null){

		$query = "SELECT MGM.ID_MENU AS ID, MDN.TITLE
					FROM $this->tbl_group_menu MGM
					JOIN $this->tbl_menu MDN ON MGM.ID_MENU=MDN.ID
					WHERE MGM.GROUP_ID = ?";

		$query = $this->db->query($query, $id);

		return $query;
	}
}

/* End of file Group_mdl.php */
/* Location: ./application/models/Group_mdl.php */