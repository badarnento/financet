<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_coa extends CI_Model{

	function get_oracle_coa_where($q) 
	{
		return $this->db->get_where('ORACLE_COA',$q);
	}

	function get_oracle_coa() 
	{
        	return $this->db->get('ORACLE_COA');
        }

	function create_oracle_coa($data)
	{
        	$this->db->insert('ORACLE_COA',$data);
        	return ($this->db->affected_rows() != 1) ? false : true;
	}

	function update_oracle_coa($where,$data)
	{
		$this->db->update('ORACLE_COA', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}