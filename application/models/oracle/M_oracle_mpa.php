<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_mpa extends CI_Model{

	function get_oracle_mpa_where($q) 
	{
		return $this->db->get_where('ORACLE_MPA',$q);
	}

	function get_oracle_mpa() 
	{
        	return $this->db->get('ORACLE_MPA');
        }

	function create_oracle_mpa($data)
	{
        	$this->db->insert('ORACLE_MPA',$data);
        	return ($this->db->affected_rows() != 1) ? false : true;
	}

	function update_oracle_mpa($where,$data)
	{
		$this->db->update('ORACLE_MPA', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}