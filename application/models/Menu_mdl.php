<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_mdl extends CI_Model {

	function menus()
	{
    	$this->db->select('*');
		$this->db->from('MASTER_DYN_MENU');
		$this->db->order_by('SHOWORDER');
		$this->db->where('ID !=', 1);

		$query = $this->db->get();

		return $query->result_array();
	}

	function get_all_menu($start, $length, $keywords){

		$where = "";

		if($keywords) {
			$q     = strtoupper($keywords);
			$where = " AND (
						UPPER(TITLE) LIKE '%".$q."%'
						) ";
		}

		$mainQuery	= "SELECT b.*, (SELECT TITLE FROM MASTER_DYN_MENU WHERE ID = b.PARENT_ID) as PARENT_NAME,
							(SELECT SHOWORDER FROM MASTER_DYN_MENU WHERE ID = b.PARENT_ID) as PARENT_ORDER
							FROM MASTER_DYN_MENU b
							WHERE ID != 1
							".$where."
							ORDER BY
								CASE
									WHEN PARENT_ORDER IS NULL THEN 001
									ELSE PARENT_ORDER
								END, b.SHOWORDER";

		$sql		= $mainQuery." limit ".$_POST['start'].",".$_POST['length'];

		$queryCount       = $this->db->query($mainQuery);
		$rowCount         = $queryCount->num_rows();
		$query            = $this->db->query($sql);
		
		$result['query']  = $query;
		$result['jmlRow'] = $rowCount;

		return $result;

	}

	function get_by_id($id){
		
    	$this->db->select('*');
		$this->db->from('MASTER_DYN_MENU'); 
		$this->db->where('ID', $id);
		$query = $this->db->get();

		return $query->row();
	}

	function get_parent(){

		$this->db->select("*");
		$this->db->from("MASTER_DYN_MENU");
		$this->db->where("LINK_TYPE", "parent");
		$this->db->order_by("SHOWORDER");
		$query = $this->db->get();

		return $query->result_array();

	}

	function get_parent_single($id = NULL){

		$this->db->select("*");
		$this->db->from("MASTER_DYN_MENU");
		$this->db->where("LINK_TYPE !=", "child");
		$this->db->where("ID !=", 1);
		if($id != NULL){
			$this->db->where("ID !=", $id);
		}
		$this->db->order_by("SHOWORDER");
		$query = $this->db->get();

		return $query->result_array();

	}

	function get_child($id, $idSelf = NULL){

		$this->db->select("*");
		$this->db->from("MASTER_DYN_MENU");
		$this->db->where("LINK_TYPE", "child");
		$this->db->where("PARENT_ID", $id);
		if($idSelf != NULL){
			$this->db->where("ID !=", $idSelf);
		}
		$this->db->order_by("SHOWORDER");
		$query = $this->db->get();

		return $query->result_array();

	}

	function add($data){

		$insert = $this->db->insert("MASTER_DYN_MENU", $data);

		return true;
	}

	function update($data, $id){

		$this->db->where('ID', $id);
		$update = $this->db->update("MASTER_DYN_MENU", $data);
		//simtax_update_history("MASTER_DYN_MENU", "UPDATE", $id);

		return true;
	}

	function delete($id){

		$this->db->where('ID', $id);
		$this->db->delete('MASTER_DYN_MENU');

		return true;
	}

	function update_multi_menu($update_data){
		$upd = $this->db->update_batch('MASTER_DYN_MENU', $update_data, 'ID');
		foreach ($update_data as $key => $value) {
			$id = $value["ID"];
			//simtax_update_history("MASTER_DYN_MENU", "UPDATE", $id);
		}
		return TRUE;
	}

}

/* End of file Menu_mdl.php */
/* Location: ./application/models/Menu_mdl.php */