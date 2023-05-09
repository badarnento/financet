<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_location extends CI_Model{

	function get_oracle_location_where($q) 
	{
		return $this->db->get_where('ORACLE_LOCATION',$q);
	}

	function get_oracle_location() 
	{
        	return $this->db->get('ORACLE_LOCATION');
        }

	function create_oracle_location($data)
	{
        	$this->db->insert('ORACLE_LOCATION',$data);
        	return ($this->db->affected_rows() != 1) ? false : true;
	}

	function update_oracle_location($where,$data)
	{
		$this->db->update('ORACLE_LOCATION', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}