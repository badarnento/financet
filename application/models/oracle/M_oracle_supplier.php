<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_supplier extends CI_Model{

	function get_oracle_supplier_where($q) 
	{
		return $this->db->get_where('ORACLE_SUPPLIER',$q);
	}

	function get_oracle_supplier() 
	{
        	return $this->db->get('ORACLE_SUPPLIER');
        }

	function create_oracle_supplier($data)
	{
        	$this->db->insert('ORACLE_SUPPLIER',$data);
        	return ($this->db->affected_rows() != 1) ? false : true;
	}

	function update_oracle_supplier($where,$data)
	{
		$this->db->update('ORACLE_SUPPLIER', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}