<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_Varelia extends CI_Model{

	function get_vendor() 
	{
       return $this->db->get('MASTER_VENDOR');
	}

	function get_vendor_where($q) 
	{
	    return $this->db->get_where('MASTER_VENDOR',$q);
    }

    function insert_vendor($where,$data)
	{
		$this->db->insert('MASTER_VENDOR',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
	}

	function update_vendor($where,$data)
	{
		$this->db->update('MASTER_VENDOR', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}