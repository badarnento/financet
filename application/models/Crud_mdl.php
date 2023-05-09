<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_mdl extends CI_Model {

    function create($table, $data, $history=true){

        if($history){
            $data['CREATED_BY'] = $this->session->userdata('identity');
        }

    	$this->db->insert($table, $data);

    	return $this->db->insert_id();

    }

    function create_batch($table, $data){

        $insert = $this->db->insert_batch($table, $data);

        if($insert){
            return true;
        }

    }

    function check_exist($table, $param){

        $this->db->select('*');
        $this->db->from($table);

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }
        else{
            $this->db->where("ID", $param);
        }

        return $this->db->get()->num_rows();
    }


    function read_by_id($table, $param){

        $this->db->select('*');
        $this->db->from($table);

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }
        else{
            $this->db->where("ID", $param);
        }

        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            return $query->row_array();
        }
    }

    function read_by_param($table, $param){

        $this->db->select('*');
        $this->db->from($table);

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }
        else{
            $this->db->where("ID", $param);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            return $query->row_array();
        }
    }

    function read_by_param_specific($table, $param="", $field="", $distinct=false){

        if(is_array($field)){
            $fieldToSelect = implode(",", $field);
        }
        else{
            $fieldToSelect = (!empty($field) || $field != "") ? $field : "*";
        }

        $this->db->select($fieldToSelect);

        if($distinct){
            $this->db->distinct();
        }

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }

        $query = $this->db->get($table);

        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
    }

    function read_by_param_in($table, $param, $value){

        $this->db->select('*');
        $this->db->from($table);

        if(is_array($value)){
            $value = array_unique($value);
            $this->db->where_in($param, $value);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
    }
    
    function read($table, $where="", $order=""){

        $this->db->from($table);
        $this->db->where("1=1");

        if($where != ""){
            if(is_array($where)){
                $paramKey = array_keys($where);
                for ($i=0; $i < count($where); $i++) { 
                    $this->db->where($paramKey[$i], $where[$paramKey[$i]]);
                }
            }else{
                $this->db->where($where);
            }
        }
        if($order != ""){
            $this->db->order_by($order);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
    }
    
    function read_specific($table, $field="", $where="", $order=""){

        if(is_array($field)){
            $fieldToSelect = implode(",", $field);
        }
        else{
            $fieldToSelect = (!empty($field)) ? $field : "*";
        }

        $this->db->select($fieldToSelect);
        $this->db->from($table);
        $this->db->where("1=1");

        if($where != ""){
            if(is_array($where)){
                $paramKey = array_keys($where);
                for ($i=0; $i < count($where); $i++) { 
                    $this->db->where($paramKey[$i], $where[$paramKey[$i]]);
                }
            }else{
                $this->db->where($where);
            }
        }
        if($order != ""){
            $this->db->order_by($order);
        }

        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
    }


    function read_datatable($table, $search=array())
    {

        $keywords = ($this->input->post('search')) ? $this->input->post('search')['value'] : "";
        $where    = "";

        if($keywords != "" && count($search) > 0){
            $q = strtolower($keywords);

            $i=0;
            foreach ($search as $value) {
                if($i>0){
                    $where .= " or ".$value." like '%".$q."%'";
                }
                else{
                    $where .= " and ( ".$value." like '%".$q."%'";
                }
                $i++;
            }
            $where .= ")";

        }

        $mainQuery = "SELECT * FROM $table where 1=1 $where";
        $queryData = query_datatable($mainQuery);

        $total = $this->db->query($mainQuery)->num_rows();
        $data  = $this->db->query($queryData)->result_array();
        
        $result['data']       = $data;
        $result['total_data'] = $total;

        return $result;     
    }

    function update($table, $data, $param, $history=true){

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }
        else{
            $this->db->where("ID", $param);
        }

        if($history){
            $data['UPDATED_BY'] = $this->session->userdata('identity');
        }

        $this->db->update($table, $data);

    	return $this->db->affected_rows();

    }

    function delete($table, $param){

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }
        else{
            $this->db->where("ID", $param);
        }
        
    	$this->db->delete($table);

    	return $this->db->affected_rows();

    }

    function update_batch_data($table, $data, $id)
    {
        $update = $this->db->update_batch($table, $data, $id);
        
        return $this->db->affected_rows();

    }

    function update_history($table, $param, $category="UPDATED_BY"){

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) { 
                $this->db->where($paramKey[$i], $param[$paramKey[$i]]);
            }
        }
        else{
            $this->db->where("ID", $param);
        }

        if($category == "UPDATED_BY"){
            $data = array("UPDATED_BY" => $this->session->userdata('identity'));
        }else{
            $data = array("CREATED_BY" => $this->session->userdata('identity'));
        }

        $this->db->update($table, $data);

        return $this->db->affected_rows();
    }

    function call_procedure($procedure_name, $param="") {

                // echo $param;

        if(is_array($param)){
            $paramKey = array_keys($param);
            for ($i=0; $i < count($param); $i++) {
                $symbl[] = "?";
            }
            $paramAdd = "(".implode(",", $symbl).")";
        }
        elseif($param != ""){
            $paramAdd = "(?)";
        }
        else{
            $paramAdd = "";
        }
// echo ' ~ ';
        $sql = "CALL " . $procedure_name . $paramAdd;
        // echo $sql;
        // die;

        $this->db->query($sql, $param);

        return $this->db->affected_rows();
    }
	
}

/* End of file Crud_mdl.php */
/* Location: ./application/models/Crud_mdl.php */