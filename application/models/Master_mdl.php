<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_mdl extends CI_Model {

	protected	$table_directorat = "MASTER_DIRECTORAT",
	$table_division   = "MASTER_DIVISION",
	$table_unit       = "MASTER_UNIT",
	$table_group      = "MASTER_GROUP",
	$table_group_rpt  = "MASTER_GROUP_RPT",
	$table_tribe      = "MASTER_TRIBE",
	$table_coa        = "MASTER_COA",
	$table_fpjp       = "MASTER_FPJP",
	$table_employee   = "MASTER_EMPLOYEE",
	$table_jabatan    = "MASTER_JABATAN",
	$table_pph    	  = "MASTER_PPH",
	$table_ppn        = "MASTER_PPN",
	$table_program    = "MASTER_PROGRAM_ID",
	$table_bank    	  = "MASTER_BANK",
	$table_bank_la    = "MASTER_BANK_LA",
	$table_setting    = "MASTER_SETTING",
	$table_rekanan    = "MASTER_VENDOR",
	$table_rekanan_tax = "MASTER_VENDOR_TAX",
	$table_kode_negara = "KODE_NEGARA",
	$table_rate   	  = "MASTER_RATE",
	$table_master_approval  = "MASTER_APPROVAL",
	$table_mapping_cash_flow  = "MASTER_MAPPING_CASH_FLOW",
	$table_proc_type  = "MASTER_PROC_TYPE",
	$table_menu    	  = "MASTER_DYN_MENU";

	//alt + 0 to minimalize region

	//region DIRECTORAT

	function get_all_directorat(){

		$this->db->select('*');
		$this->db->from($this->table_directorat);
		$this->db->order_by("ID_DIR_CODE", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_directorat_datatable()
	{

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
			$fieldToSearch = array("DIRECTORAT_CODE","DIRECTORAT_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_DIR_CODE,
		DIRECTORAT_CODE,
		DIRECTORAT_NAME
		FROM $this->table_directorat
		where 1=1
		$where  order by ID_DIR_CODE desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_directorat()
	{
		$mainQuery	        = "SELECT 
		ID_DIR_CODE, 
		DIRECTORAT_CODE,
		DIRECTORAT_NAME
		FROM $this->table_directorat  
		order by ID_DIR_CODE";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_directorat($directorat_code)
	{
		$where = "";
		$whereArr = array();

		if($directorat_code){
			$where .= " AND DIRECTORAT_CODE = ?";
			$whereArr[] = $id_directorat;
		}


		$mainQuery	        = "SELECT *
		FROM $this->table_directorat  
		WHERE 1=1 
		$where
		order by ID_DIR_CODE desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_directorat($data)
	{
		$this->db->insert($this->table_directorat, $data);
		return true;
	}

	function update_data_directorat($data, $id_dir_code)
	{
		$this->db->where('ID_DIR_CODE', $id_dir_code);
		$this->db->update($this->table_directorat, $data);
		return true;
	}

	function delete_data_directorat($id_dir_code)
	{
		$this->db->where('ID_DIR_CODE', $id_dir_code);
		$this->db->delete($this->table_directorat);
		return true;
	}

	//endregion DIRECTORAT mdl

	//region DIVISION mdl

	function get_all_division(){

		$this->db->select('*');
		$this->db->from($this->table_division);
		$this->db->order_by("ID_DIVISION", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_division_datatable()
	{

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
			$fieldToSearch = array("dr.DIRECTORAT_NAME","dv.DIVISION_CODE","dv.DIVISION_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		dr.ID_DIR_CODE,
		dr.DIRECTORAT_NAME,
		dv.ID_DIVISION,
		dv.DIVISION_CODE,
		dv.DIVISION_NAME
		FROM $this->table_division dv 
		INNER JOIN $this->table_directorat dr 
		on dv.ID_DIR_CODE = dr.ID_DIR_CODE
		where 1=1
		$where order by dv.id_division desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_division()
	{
		$param_id_dir_code  = $this->input->post('param_id_dir_code');

		$where = "";
		$whereArr = array();

		if($param_id_dir_code){
			$where .= " AND dr.ID_DIR_CODE = ?";
			$whereArr[] = $param_id_dir_code;
		}


		$mainQuery	        = "SELECT 
		dr.ID_DIR_CODE,
		dr.DIRECTORAT_NAME,
		dv.ID_DIVISION,
		dv.DIVISION_CODE,
		dv.DIVISION_NAME
		FROM $this->table_division dv 
		INNER JOIN $this->table_directorat dr 
		on dv.ID_DIR_CODE = dr.ID_DIR_CODE 
		WHERE 1=1 
		$where
		order by DIVISION_NAME desc";
		$query 		        = $this->db->query($mainQuery,$whereArr);
		$result['querymd']	= $query;
		return $result;		
	}

	public function check_exist_division($division_code)
	{
		$where = "";
		$whereArr = array();

		if($division_code){
			$where .= " AND DIVISION_CODE = ?";
			$whereArr[] = $division_code;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_division  
		WHERE 1=1 
		$where
		order by ID_DIVISION desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_division($data)
	{
		$this->db->insert($this->table_division, $data);
		return true;
	}

	function update_data_division($data, $id_division)
	{
		$this->db->where('ID_DIVISION', $id_division);
		$this->db->update($this->table_division, $data);
		return true;
	}

	function delete_data_division($id_division)
	{
		$this->db->where('ID_DIVISION', $id_division);
		$this->db->delete($this->table_division);
		return true;
	}

	//endregion DIVISION mdl

	//region UNIT mdl
	
	function get_all_unit(){

		$this->db->select('*');
		$this->db->from($this->table_unit);
		$this->db->order_by("ID_UNIT", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_unit_datatable()
	{

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
			$fieldToSearch = array("dr.DIRECTORAT_NAME","dv.DIVISION_NAME","un.UNIT_CODE","un.UNIT_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		dr.ID_DIR_CODE,
		dr.DIRECTORAT_NAME,
		dv.ID_DIVISION,
		dv.DIVISION_NAME,
		un.ID_UNIT,
		un.UNIT_CODE,
		un.UNIT_NAME
		FROM $this->table_unit un 
		INNER JOIN $this->table_division dv 
		on un.ID_DIVISION = dv.ID_DIVISION
		INNER JOIN $this->table_directorat dr 
		on un.ID_DIR_CODE = dr.ID_DIR_CODE and dv.ID_DIR_CODE = dr.ID_DIR_CODE
		where 1=1
		$where order by un.UNIT_CODE";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_unit()
	{
		$param_id_dir_code  = $this->input->post('param_id_dir_code');
		$param_id_division  = $this->input->post('param_id_division');
		$where = "";
		$whereArr = array();

		if($param_id_dir_code){
			$where .= " AND un.ID_DIR_CODE = ?";
			$whereArr[] = $param_id_dir_code;
		}

		if($param_id_division){
			$where .= " AND un.ID_DIVISION = ?";
			$whereArr[] = $param_id_division;
		}

		$mainQuery	        = "SELECT 
		dr.ID_DIR_CODE,
		dr.DIRECTORAT_NAME,
		dv.ID_DIVISION,
		dv.DIVISION_NAME,
		un.ID_UNIT,
		un.UNIT_CODE,
		un.UNIT_NAME
		FROM $this->table_unit un 
		INNER JOIN $this->table_division dv 
		on un.ID_DIVISION = dv.ID_DIVISION
		INNER JOIN $this->table_directorat dr 
		on un.ID_DIR_CODE = dr.ID_DIR_CODE and dv.ID_DIR_CODE = dr.ID_DIR_CODE
		WHERE 1=1 ". $where ."
		order by un.UNIT_CODE";
		$query 		        = $this->db->query($mainQuery,$whereArr);

		$result['querymu']	= $query;
		return $result;		
	}

	public function check_exist_unit($unit_name)
	{
		$where = "";
		$whereArr = array();

		if($unit_name){
			$where .= " AND UNIT_NAME = ?";
			$whereArr[] = $unit_name;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_unit  
		where 1=1
		$where
		order by UNIT_CODE";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_unit($data)
	{
		$this->db->insert($this->table_unit, $data);
		return true;
	}

	function update_data_unit($data, $id_unit)
	{
		$this->db->where('ID_UNIT', $id_unit);
		$this->db->update($this->table_unit, $data);
		return true;
	}

	function delete_data_unit($id_unit)
	{
		$this->db->where('ID_UNIT', $id_unit);
		$this->db->delete($this->table_unit);
		return true;
	}

	//endregion UNIT mdl

	//region GROUP mdl

	function get_all_group(){

		$this->db->select('*');
		$this->db->from($this->table_group_rpt);
		$this->db->order_by("ID_GROUP", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_group_datatable()
	{

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
			$fieldToSearch = array("GROUP_REPORT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_GROUP,
		GROUP_REPORT
		FROM $this->table_group
		where 1=1
		$where  order by ID_GROUP desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_group()
	{
		$mainQuery	        = "SELECT 
		ID_GROUP,
		GROUP_REPORT
		FROM $this->table_group  
		order by ID_GROUP desc";
		$query 		        = $this->db->query($mainQuery);
		$result['querygr']	= $query;
		return $result;		
	}

	public function check_exist_group($group_report)
	{
		$where = "";
		$whereArr = array();

		if($group_report){
			$where .= " AND GROUP_REPORT = ?";
			$whereArr[] = $group_report;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_group  
		Where 1=1
		$where
		order by ID_GROUP desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_group($data)
	{
		$this->db->insert($this->table_group, $data);
		return true;
	}

	function update_data_group($data, $id_group)
	{
		$this->db->where('ID_GROUP', $id_group);
		$this->db->update($this->table_group, $data);
		return true;
	}

	function delete_data_group($id_group)
	{
		$this->db->where('ID_GROUP', $id_group);
		$this->db->delete($this->table_group);
		return true;
	}

	//endregion GROUP mdl

	//region GROUP RPT mdl
	
	function get_all_group_rpt(){

		$this->db->select('*');
		$this->db->from($this->table_group_rpt);
		$this->db->order_by("ID_GROUP_RPT", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_group_rpt_datatable()
	{

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
			$fieldToSearch = array("grpt.PARENT_ACCOUNT","gr.GROUP_REPORT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		
		$mainQuery = "SELECT 
		grpt.ID_GROUP_RPT,
		grpt.PARENT_ACCOUNT,
		grpt.ID_GROUP,
		gr.GROUP_REPORT
		FROM $this->table_group_rpt grpt 
		INNER JOIN  $this->table_group gr on grpt.ID_GROUP = gr.ID_GROUP 
		where 1=1
		$where  order by ID_GROUP_RPT desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_parent_account()
	{
		$mainQuery	        = "SELECT 
		grpt.ID_GROUP_RPT,
		grpt.PARENT_ACCOUNT,
		grpt.ID_GROUP,
		gr.GROUP_REPORT
		FROM $this->table_group_rpt grpt 
		INNER JOIN  $this->table_group gr on grpt.ID_GROUP = gr.ID_GROUP  
		order by PARENT_ACCOUNT desc";
		$query 		        = $this->db->query($mainQuery);
		$result['querygr']	= $query;
		return $result;		
	}

	public function check_exist_group_rpt($parent_account)
	{
		$where = "";
		$whereArr = array();

		if($parent_account){
			$where .= " AND PARENT_ACCOUNT = ?";
			$whereArr[] = $parent_account;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_group_rpt  
		where 1=1
		$where
		order by ID_GROUP_RPT desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_group_rpt($data)
	{
		$this->db->insert($this->table_group_rpt, $data);
		return $this->db->insert_id();
	}

	function update_data_group_rpt($data, $id_group_rpt)
	{
		$this->db->where('ID_GROUP_RPT', $id_group_rpt);
		$this->db->update($this->table_group_rpt, $data);
		return true;
	}

	function update_data_parent_account($data, $group_report)
	{
		$this->db->where('GROUP_REPORT', $group_report);
		$this->db->update($this->table_coa, $data);
		return true;
	}

	function delete_data_group_rpt($id_group_rpt)
	{
		$this->db->where('ID_GROUP_RPT', $id_group_rpt);
		$this->db->delete($this->table_group_rpt);
		return true;
	}

	function update_parent_accout($group_report,$dataupdate)
	{
		$this->db->where('ID_GROUP', $group_report);
		$this->db->update($this->table_coa, $dataupdate);
		return true;
	}

	function update_parent_accout_by_parent($id_group_report,$dataupdate)
	{
		$this->db->where('ID_GROUP_RPT', $id_group_report);
		$this->db->update($this->table_coa, $dataupdate);
		return true;
	}

	//endregion GROUP RPT mdl

	//region TRIBE mdl
	
	function get_all_tribe(){

		$this->db->select('*');
		$this->db->from($this->table_tribe);
		$this->db->order_by("id_tribe", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_tribe_datatable()
	{

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
			$fieldToSearch = array("TRIBE_CODE","TRIBE_DESC");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_TRIBE,
		TRIBE_CODE,
		TRIBE_DESC
		FROM $this->table_tribe
		where 1=1
		$where  order by TRIBE_CODE";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_tribe()
	{
		$mainQuery	        = "SELECT 
		ID_TRIBE, 
		TRIBE_CODE,
		TRIBE_DESC
		FROM $this->table_tribe  
		order by TRIBE_CODE";
		$query 		        = $this->db->query($mainQuery);
		$result['querytb']	= $query;
		return $result;		
	}

	public function check_exist_tribe($tribe_code)
	{
		$where = "";
		$whereArr = array();

		if($parent_account){
			$where .= " AND TRIBE_CODE = ?";
			$whereArr[] = $parent_account;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_tribe  
		where 1=1
		$where
		order by TRIBE_CODE";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_tribe($data)
	{
		$this->db->insert($this->table_tribe, $data);
		return true;
	}

	function update_data_tribe($data, $id_tribe)
	{
		$this->db->where('ID_TRIBE', $id_tribe);
		$this->db->update($this->table_tribe, $data);
		return true;
	}

	function delete_data_tribe($id_tribe)
	{
		$this->db->where('ID_TRIBE', $id_tribe);
		$this->db->delete($this->table_tribe);
		return true;
	}

	//endregion TRIBE mdl

	//region COA mdl
	
	function get_coa_datatable()
	{

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
			$fieldToSearch = array("co.NATURE","co.DESCRIPTION","grup.GROUP_REPORT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		co.ID_MASTER_COA, 
		co.NATURE, 
		co.DESCRIPTION, 
		co.ID_GROUP,
		grup.GROUP_REPORT,
		gr.ID_GROUP_RPT,
		COALESCE(gr.PARENT_ACCOUNT,'NOT FOUND') as PARENT_ACCOUNT
		FROM $this->table_coa co LEFT JOIN $this->table_group_rpt gr 
		on co.ID_GROUP_RPT = gr.ID_GROUP_RPT
		INNER JOIN $this->table_group grup 
		on co.ID_GROUP = grup.ID_GROUP
		where 1=1
		$where  order by co.NATURE ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_coa()
	{
		$mainQuery	        = "SELECT 
		co.ID_MASTER_COA, 
		co.NATURE, 
		co.DESCRIPTION, 
		co.ID_GROUP,
		grup.GROUP_REPORT,
		gr.ID_GROUP_RPT,
		COALESCE(gr.PARENT_ACCOUNT,'NOT FOUND') as PARENT_ACCOUNT
		FROM $this->table_coa co LEFT JOIN $this->table_group_rpt gr 
		on co.ID_GROUP_RPT = gr.ID_GROUP_RPT
		INNER JOIN $this->table_group grup 
		on co.ID_GROUP = grup.ID_GROUP  
		order by co.NATURE desc";
		$query 		        = $this->db->query($mainQuery);
		$result['queryco']	= $query;
		return $result;		
	}

	public function check_exist_coa($nature)
	{
		$where = "";
		$whereArr = array();

		if($nature){
			$where .= " AND NATURE = ?";
			$whereArr[] = $nature;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_coa  
		where 1=1
		$where
		order by ID_MASTER_COA desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_coa($data)
	{
		$this->db->insert($this->table_coa, $data);
		return true;
	}

	function update_data_coa($data, $id_master_coa)
	{
		$this->db->where('ID_MASTER_COA', $id_master_coa);
		$this->db->update($this->table_coa, $data);
		return true;
	}

	function delete_data_coa($id_master_coa)
	{
		$this->db->where('ID_MASTER_COA', $id_master_coa);
		$this->db->delete($this->table_coa);
		return true;
	}

	//endregion COA mdl

	//region FPJP mdl

	function get_all_fpjp(){

		$this->db->select('*');
		$this->db->from($this->table_fpjp_rpt);
		$this->db->order_by("id_master_fpjp", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_fpjp_datatable()
	{

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
			$fieldToSearch = array("FPJP_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_MASTER_FPJP,
		FPJP_CODE,
		FPJP_NAME
		FROM $this->table_fpjp
		where 1=1
		$where  order by FPJP_CODE";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_fpjp()
	{
		$mainQuery	        = "SELECT 
		ID_MASTER_FPJP,
		FPJP_CODE,
		FPJP_NAME
		FROM $this->table_fpjp  
		order by FPJP_CODE";
		$query 		        = $this->db->query($mainQuery);
		$result['querygr']	= $query;
		return $result;		
	}

	public function check_exist_fpjp($fpJp_code)
	{
		$where = "";
		$whereArr = array();

		if($fpJp_code){
			$where .= " AND FPJP_CODE = ?";
			$whereArr[] = $fpJp_code;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_fpjp  
		where 1=1
		$where
		order by FPJP_CODE desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_fpjp($data)
	{
		$this->db->insert($this->table_fpjp, $data);
		return true;
	}

	function update_data_fpjp($data, $id_master_fpjp)
	{
		$this->db->where('ID_MASTER_FPJP', $id_master_fpjp);
		$this->db->update($this->table_fpjp, $data);
		return true;
	}

	function delete_data_fpjp($id_master_fpjp)
	{
		$this->db->where('ID_MASTER_FPJP', $id_master_fpjp);
		$this->db->delete($this->table_fpjp);
		return true;
	}

	//endregion FPJP mdl

	//region Employee mdl

	function get_all_employee(){

		$this->db->select('*');
		$this->db->from($this->table_employee);
		$this->db->order_by("ID_EMPLOYEE", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_employee_datatable()
	{

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
			$fieldToSearch = array("emp.NIK","emp.NAMA","jbt.JABATAN","un.UNIT_NAME","dv.DIVISION_NAME","dir.DIRECTORAT_NAME","emp.NO_HP","emp.ALAMAT_EMAIL");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT emp.ID_EMPLOYEE, 
		emp.NIK, 
		emp.NAMA, 
		emp.ID_JABATAN, 
		COALESCE(jbt.JABATAN, emp.JABATAN) as JABATAN,
		emp.ID_UNIT,
		COALESCE(un.UNIT_NAME,emp.UNIT_NAME) as UNIT_NAME,  
		emp.ID_DIVISION, 
		COALESCE(dv.DIVISION_NAME, emp.DIVISION_NAME) as DIVISION_NAME, 
		emp.ID_DIR_CODE, 
		COALESCE(dir.DIRECTORAT_NAME,emp.DIRECTORAT_NAME) as DIRECTORAT_NAME, 
		emp.NO_HP, 
		emp.ALAMAT_EMAIL FROM $this->table_employee emp 
		LEFT JOIN $this->table_directorat dir on emp.ID_DIR_CODE = dir.ID_DIR_CODE
		LEFT JOIN $this->table_division dv on emp.ID_DIVISION = dv.ID_DIVISION
		LEFT JOIN $this->table_unit un on emp.ID_UNIT = un.ID_UNIT
		LEFT JOIN $this->table_jabatan jbt on emp.ID_JABATAN = jbt.ID_JABATAN
		WHERE 1=1
		$where order by emp.ID_EMPLOYEE";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_employee()
	{
		$mainQuery	        = "SELECT emp.ID_EMPLOYEE, 
		emp.NIK, 
		emp.NAMA, 
		emp.ID_JABATAN, 
		COALESCE(jbt.JABATAN, emp.JABATAN) as JABATAN,
		emp.ID_UNIT,
		COALESCE(un.UNIT_NAME,emp.UNIT_NAME) as UNIT_NAME,  
		emp.ID_DIVISION, 
		COALESCE(dv.DIVISION_NAME, emp.DIVISION_NAME) as DIVISION_NAME, 
		emp.ID_DIR_CODE, 
		COALESCE(dir.DIRECTORAT_NAME,emp.DIRECTORAT_NAME) as DIRECTORAT_NAME, 
		emp.NO_HP, 
		emp.ALAMAT_EMAIL FROM $this->table_employee emp 
		LEFT JOIN $this->table_directorat dir on emp.ID_DIR_CODE = dir.ID_DIR_CODE
		LEFT JOIN $this->table_division dv on emp.ID_DIVISION = dv.ID_DIVISION
		LEFT JOIN $this->table_unit un on emp.ID_UNIT = un.ID_UNIT
		LEFT JOIN $this->table_jabatan jbt on emp.ID_JABATAN = jbt.ID_JABATAN
		order by emp.ID_EMPLOYEE desc";
		$query 		        = $this->db->query($mainQuery);
		$result['querymu']	= $query;
		return $result;		
	}

	public function check_exist_employee($nik)
	{
		$where = "";
		$whereArr = array();

		if($nik){
			$where .= " AND NIK = ?";
			$whereArr[] = $nik;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_employee  
		where 1=1
		$where
		order by ID_EMPLOYEE";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_employee($data)
	{
		$this->db->insert($this->table_employee, $data);
		return true;
	}

	function update_data_employee($data, $id_employee)
	{
		$this->db->where('ID_EMPLOYEE', $id_employee);
		$this->db->update($this->table_employee, $data);
		return true;
	}

	function delete_data_employee($id_employee)
	{
		$this->db->where('ID_EMPLOYEE', $id_employee);
		$this->db->delete($this->table_employee);
		return true;
	}

	//endregion Employee mdl

	//region Jabatan mdl

	function get_all_jabatan(){

		$this->db->select('*');
		$this->db->from($this->table_jabatan);
		$this->db->order_by("ID_JABATAN", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_jabatan_datatable()
	{

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
			$fieldToSearch = array("dr.DIRECTORAT_NAME","dv.DIVISION_NAME","un.UNIT_NAME","jb.CODE_JABATAN","jb.JABATAN");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		jb.ID_JABATAN,
		jb.CODE_JABATAN,
		jb.JABATAN,
		dr.ID_DIR_CODE,
		COALESCE(dr.DIRECTORAT_NAME,'NOT FOUND') as DIRECTORAT_NAME,
		dv.ID_DIVISION,
		COALESCE(dv.DIVISION_NAME,'NOT FOUND') as DIVISION_NAME,
		un.ID_UNIT,
		COALESCE(un.UNIT_NAME,'NOT FOUND') as UNIT_NAME
		FROM $this->table_jabatan jb
		LEFT JOIN $this->table_unit un 
		on jb.ID_UNIT = un.ID_UNIT
		LEFT JOIN $this->table_division dv 
		on jb.ID_DIVISION = dv.ID_DIVISION
		LEFT JOIN $this->table_directorat dr 
		on jb.ID_DIR_CODE = dr.ID_DIR_CODE
		where 1=1
		". $where ." order by un.UNIT_NAME desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_jabatan()
	{
		$param_id_dir_code  = $this->input->post('param_id_dir_code');
		$param_id_division  = $this->input->post('param_id_division');
		$param_id_unit  = $this->input->post('param_id_unit');
		
		$where = "";
		$whereArr = array();

		if($param_id_dir_code){
			$where .= " AND jb.ID_DIR_CODE = ?";
			$whereArr[] = $param_id_dir_code;
		}

		if($param_id_division){
			$where .= " AND jb.ID_DIVISION = ?";
			$whereArr[] = $param_id_division;
		}

		if($param_id_unit){
			$where .= " AND jb.ID_UNIT = ?";
			$whereArr[] = $param_id_unit;
		}

		$mainQuery	        = "SELECT 
		jb.ID_JABATAN,
		jb.CODE_JABATAN,
		jb.JABATAN,
		dr.ID_DIR_CODE,
		COALESCE(dr.DIRECTORAT_NAME,'NOT FOUND') as DIRECTORAT_NAME,
		dv.ID_DIVISION,
		COALESCE(dv.DIVISION_NAME,'NOT FOUND') as DIVISION_NAME,
		un.UNIT_CODE,
		COALESCE(un.UNIT_NAME,'NOT FOUND') as UNIT_NAME
		FROM $this->table_jabatan jb
		LEFT JOIN $this->table_unit un 
		on jb.ID_UNIT = un.ID_UNIT
		LEFT JOIN $this->table_division dv 
		on jb.ID_DIVISION = dv.ID_DIVISION
		LEFT JOIN $this->table_directorat dr 
		on jb.ID_DIR_CODE = dr.ID_DIR_CODE
		where 1=1 ". $where ." 
		order by un.UNIT_NAME desc";
		$query 		        = $this->db->query($mainQuery,$whereArr);
		$result['querymu']	= $query;
		return $result;		
	}

	public function check_exist_jabatan($code_jabatan)
	{
		$where = "";
		$whereArr = array();

		if($code_jabatan){
			$where .= " AND CODE_JABATAN = ?";
			$whereArr[] = $code_jabatan;
		}

		$mainQuery	        = "SELECT * 
		FROM $this->table_jabatan  
		where 1=1
		$where
		order by CODE_JABATAN";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_jabatan($data)
	{
		$this->db->insert($this->table_jabatan, $data);
		return true;
	}

	function update_data_jabatan($data, $id_jabatan)
	{
		$this->db->where('ID_JABATAN', $id_jabatan);
		$this->db->update($this->table_jabatan, $data);
		return true;
	}

	function delete_data_jabatan($id_jabatan)
	{
		$this->db->where('ID_JABATAN', $id_jabatan);
		$this->db->delete($this->table_jabatan);
		return true;
	}

	function get_all_submit(){

		$this->db->select('*');
		$this->db->from($this->table_employee);
		$this->db->where('ID_JABATAN not in (1,3,5,8,11,13,23,26,30,31,37,39,40,43,48,54,71,75,79,83,38,59,78,106,121,143,147,165,171,229,240,259)');

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_all_diketahui(){

		$this->db->select('*');
		$this->db->from($this->table_employee);
		$this->db->where('ID_JABATAN not in (1,3,5,8,11,13,23,26,30,31,37,39,40,43,48,54,71,75,79,83,38,59,78,106,121,143,147,165,171,229,240,259)');

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	//endregion Jabatan mdl

	//region PPH mdl
	function get_all_pph(){

		$this->db->select('*');
		$this->db->from($this->table_pph);
		$this->db->order_by("ID_WHT_TAX", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_pph_datatable()
	{

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
			$fieldToSearch = array("WHT_TAX_TYPE","WHT_DESC","WHT_TAX_CODE","WHT_TAX_CODE_DESC","PERCENTAGE","GL_ACCOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_WHT_TAX,
		WHT_TAX_TYPE,
		WHT_DESC,
		WHT_TAX_CODE,
		WHT_TAX_CODE_DESC,
		PERCENTAGE,
		GL_ACCOUNT
		FROM $this->table_pph
		where 1=1
		$where  order by ID_WHT_TAX desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_pph()
	{
		$mainQuery	        = "SELECT 
		ID_WHT_TAX,
		WHT_TAX_TYPE,
		WHT_DESC,
		WHT_TAX_CODE,
		WHT_TAX_CODE_DESC,
		PERCENTAGE,
		GL_ACCOUNT
		FROM $this->table_pph  
		order by ID_WHT_TAX";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_pph($wht_tax_type,$wht_tax_code)
	{
		$where = "";
		$whereArr = array();

		if($wht_tax_type){
			$where .= " AND WHT_TAX_TYPE = ?";
			$whereArr[] = $wht_tax_type;
		}

		if($wht_tax_code){
			$where .= " AND WHT_TAX_CODE = ?";
			$whereArr[] = $wht_tax_code;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_pph  
		where 1=1
		$where
		order by ID_WHT_TAX desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_pph($data)
	{
		$insert = $this->db->insert($this->table_pph, $data);
		// echo $this->db->last_query(); die;
		return true;
	}

	function update_data_pph($data, $id_wht_tax)
	{
		$this->db->where('ID_WHT_TAX', $id_wht_tax);
		$this->db->update($this->table_pph, $data);
		// echo $this->db->last_query(); die;
		return true;
	}

	function delete_data_pph($id_wht_tax)
	{
		$this->db->where('ID_WHT_TAX', $id_wht_tax);
		$this->db->delete($this->table_pph);
		// echo $this->db->last_query(); die;
		return true;
	}
	//endregion PPH mdl

	//region PPN mdl

	function get_all_ppn(){

		$this->db->select('*');
		$this->db->from($this->table_ppn);
		$this->db->order_by("ID_MSTR_PPN", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_ppn_datatable()
	{

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
			$fieldToSearch = array("TAX_CODE","TAX_CODE_DESC","PERCENTAGE","GL_ACCOUNT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_MSTR_PPN,
		TAX_CODE,
		TAX_DESCRIPTION,
		PERCENTAGE,
		GL_ACCOUNT
		FROM $this->table_ppn
		where 1=1
		$where  order by ID_MSTR_PPN desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_ppn()
	{
		$mainQuery	        = "SELECT 
		ID_MSTR_PPN,
		TAX_CODE,
		TAX_DESCRIPTION,
		PERCENTAGE,
		GL_ACCOUNT
		FROM $this->table_ppn  
		order by ID_MSTR_PPN";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_ppn($tax_code)
	{
		$where = "";
		$whereArr = array();

		if($tax_code){
			$where .= " AND TAX_CODE = ?";
			$whereArr[] = $tax_code;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_ppn  
		where 1=1
		$where
		order by ID_MSTR_PPN desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_ppn($data)
	{
		$this->db->insert($this->table_ppn, $data);
		return true;
	}

	function update_data_ppn($data, $id_mstr_ppn)
	{
		$this->db->where('ID_MSTR_PPN', $id_mstr_ppn);
		$this->db->update($this->table_ppn, $data);
		// echo $this->db->last_query(); die;
		return true;
	}

	function delete_data_ppn($id_mstr_ppn)
	{
		$this->db->where('ID_MSTR_PPN', $id_mstr_ppn);
		$this->db->delete($this->table_ppn);
		return true;
	}

	//endregion PPN mdl

	//region Program mdl

	function get_all_program(){

		$this->db->select('*');
		$this->db->from($this->table_program);
		$this->db->order_by("ID_PROGRAM", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_program_datatable()
	{

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
			$fieldToSearch = array("DESCRIPTION","PROGRAM");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_PROGRAM,
		EXTRACT(YEAR FROM TAHUN) as TAHUN,
		DESCRIPTION,
		PROGRAM
		FROM $this->table_program
		where 1=1
		$where  order by DESCRIPTION desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_program()
	{
		$mainQuery	        = "SELECT 
		ID_PROGRAM, 
		EXTRACT(YEAR FROM TAHUN) as TAHUN,
		DESCRIPTION,
		PROGRAM
		FROM $this->table_program  
		order by DESCRIPTION";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_program($description)
	{
		$where = "";
		$whereArr = array();

		if($description){
			$where .= " AND DESCRIPTION = ?";
			$whereArr[] = $description;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_program  
		where 1=1
		$where
		order by ID_PROGRAM desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_program($data)
	{
		$this->db->insert($this->table_program, $data);
		return true;
	}

	function update_data_program($data, $id_program)
	{
		$this->db->where('ID_PROGRAM', $id_program);
		$this->db->update($this->table_program, $data);
		return true;
	}

	function delete_data_program($id_program)
	{
		$this->db->where('ID_PROGRAM', $id_program);
		$this->db->delete($this->table_program);
		return true;
	}

	//endregion Program mdl

	//region Bank mdl

	function get_all_bank(){

		$this->db->select('*');
		$this->db->from($this->table_bank);
		$this->db->order_by("ID_BANK", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_bank_datatable()
	{

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
			$fieldToSearch = array("BIC_RTGS_CODE","DOMESTIC_BANK_CODE","BANK_NAME","BRANCH_CODE","BRANCH_NAME","CITY");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT *
		FROM $this->table_bank
		where 1=1
		$where  order by ID_BANK desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_bank()
	{
		$mainQuery	        = "SELECT *
		FROM $this->table_bank  
		order by ID_BANK";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_bank($domestic_bank_code)
	{
		$where = "";
		$whereArr = array();

		if($domestic_bank_code){
			$where .= " AND DOMESTIC_BANK_CODE = ?";
			$whereArr[] = $domestic_bank_code;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_bank  
		where 1=1
		$where
		order by ID_BANK desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_bank($data)
	{
		$this->db->insert($this->table_bank, $data);
		return true;
	}

	function update_data_bank($data, $id_bank)
	{
		$this->db->where('ID_BANK', $id_bank);
		$this->db->update($this->table_bank, $data);
		return true;
	}

	function delete_data_bank($id_bank)
	{
		$this->db->where('ID_BANK', $id_bank);
		$this->db->delete($this->table_bank);
		return true;
	}

	//endregion Bank mdl

	//region Bank LA mdl

	function get_all_bank_la(){

		$this->db->select('*');
		$this->db->from($this->table_bank_la);
		$this->db->order_by("ID_BANK_LA", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_bank_la_datatable()
	{

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
			$fieldToSearch = array("NAMA_BANK","NOMOR_REKENING","PERUNTUKAN","COA_BANK","COA_LIABILITIES");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT *
		FROM $this->table_bank_la
		where 1=1
		$where  order by ID_BANK_LA desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_bank_la()
	{
		$mainQuery	        = "SELECT *
		FROM $this->table_bank_la  
		order by ID_BANK_LA";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function get_ddl_bank_la()
	{
		$mainQuery	        = "SELECT *
		FROM $this->table_bank_la  
		where FLAG = 'Y' 
		order by ID_BANK_LA";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_bank_la($nomor_rekening)
	{
		$where = "";
		$whereArr = array();

		if($nomor_rekening){
			$where .= " AND NOMOR_REKENING = ?";
			$whereArr[] = $nomor_rekening;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_bank_la  
		where 1=1
		$where
		order by ID_BANK_LA desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_bank_la($data)
	{
		$this->db->insert($this->table_bank_la, $data);
		return true;
	}

	function update_active_data_bank_la($data)
	{
		$this->db->update($this->table_bank_la, $data);
		return true;
	}

	function update_data_bank_la($data, $id_bank_la)
	{
		$this->db->where('ID_BANK_LA', $id_bank_la);
		$this->db->update($this->table_bank_la, $data);
		return true;
	}

	function delete_data_bank_la($id_bank_la)
	{
		$this->db->where('ID_BANK_LA', $id_bank_la);
		$this->db->delete($this->table_bank_la);
		return true;
	}

	//endregion Bank LA mdl

	//region Master Setting mdl

	function get_all_setting(){

		$this->db->select('*');
		$this->db->from($this->table_setting);
		$this->db->order_by("ID_SETTING", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_setting_datatable()
	{

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
			$fieldToSearch = array("mn.TITLE","st.CODE_SETTING","st.DESCRIPTION","st.FLAG");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		st.ID_SETTING,
		st.ID_MENU,
		st.CODE_SETTING,
		st.DESCRIPTION,
		st.FLAG,
		mn.TITLE
		FROM $this->table_setting st INNER JOIN
		$this->table_menu mn on st.ID_MENU = mn.ID

		$where  order by ID_setting desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_setting($code_setting = false)
	{

		$where = "";
		$whereArr = array();

		if($code_setting){
			$where .= " AND st.CODE_SETTING = ?";
			$whereArr[] = $code_setting;
		}

		$mainQuery = "SELECT 
		st.ID_SETTING,
		st.ID_MENU,
		st.CODE_SETTING,
		st.DESCRIPTION,
		st.FLAG,
		mn.TITLE
		FROM $this->table_setting st INNER JOIN
		$this->table_menu mn on st.ID_MENU = mn.ID
		where 1=1
		$where  order by ID_setting desc";

		$query 		        = $this->db->query($mainQuery,$whereArr);
		$result['query']	= $query;
		return $result;		
	}


	public function get_master_menu()
	{
		$mainQuery	        = "SELECT 
		ID, 
		TITLE
		FROM $this->table_menu  
		where PARENT_ID > 0
		order by TITLE asc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_setting($code_setting)
	{
		$where = "";
		$whereArr = array();

		if($code_setting){
			$where .= " AND st.CODE_SETTING = ?";
			$whereArr[] = $code_setting;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_setting  
		where 1=1
		$where
		order by ID_setting desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_setting($data)
	{
		$this->db->insert($this->table_setting, $data);
		return true;
	}

	function update_data_setting($data, $id_setting)
	{
		$this->db->where('ID_setting', $id_setting);
		$this->db->update($this->table_setting, $data);
		return true;
	}

	function delete_data_setting($id_setting)
	{
		$this->db->where('ID_setting', $id_setting);
		$this->db->delete($this->table_setting);
		return true;
	}

	//endregion Master Setting mdl

	//region Master data jenis rekanan mdl

	function get_all_vendor(){

		$this->db->select('*');
		$this->db->from($this->table_rekanan);
		$this->db->order_by("ID_VENDOR", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_vendor_datatable()
	{

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("NAMA_VENDOR","NOMOR_NPWP","ALAMAT","NO_TLP","NAMA_PIC_VENDOR","ALAMAT_EMAIL","NAMA_BANK","NAMA_REKENING","ACCT_NUMBER");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_VENDOR,
		NAMA_VENDOR,
		NOMOR_NPWP,
		ALAMAT,
		NO_TLP,
		NAMA_PIC_VENDOR,
		ALAMAT_EMAIL,
		NAMA_BANK,
		NAMA_REKENING,
		ACCT_NUMBER

		FROM $this->table_rekanan
		where 1=1
		$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_vendor()
	{
		$mainQuery	        = "SELECT 
		ID_VENDOR,
		NAMA_VENDOR,
		NOMOR_NPWP,
		ALAMAT,
		NO_TLP,
		NAMA_PIC_VENDOR,
		ALAMAT_EMAIL,
		NAMA_BANK,
		NAMA_REKENING,
		ACCT_NUMBER
		FROM $this->table_rekanan  
		";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_vendor($nama_rekanan)
	{
		$where = "";
		$whereArr = array();

		if($nama_rekanan){
			$where .= " AND NAMA_VENDOR = ?";
		}

		$mainQuery  = "SELECT * FROM $this->table_rekanan  
						where 1=1
						$where";

		return $this->db->query($mainQuery, $nama_rekanan)->num_rows();
	}

	function save_data_vendor($data)
	{
		$this->db->insert($this->table_rekanan, $data);
		return true;
	}

	function update_data_vendor($data, $id_vendor)
	{
		$this->db->where('ID_VENDOR', $id_vendor);
		$this->db->update($this->table_rekanan, $data);
		return true;
	}

	function delete_data_vendor($id_vendor)
	{
		$this->db->where('ID_VENDOR', $id_vendor);
		$this->db->delete($this->table_rekanan);
		return true;
	}

	function insert_upload_vendor_import($data)
	{
		foreach ($data as $row)
		{
			$paramKey = array_keys($row);

			for ($i=0; $i < count($paramKey); $i++) {
				$onUpdate[] = $paramKey[$i] ." = '".$row[$paramKey[$i]]."'";
			}

			$sql = $this->db->insert_string($this->table_rekanan, $row) . " ON DUPLICATE KEY UPDATE " .implode(",", $onUpdate);
			$this->db->query($sql);

			// echo $this->db->last_query(); die;

		}


		return true;

	}

	//endregion Master data jenis rekanan mdl

	function save_data_vendor_tax($data)
	{
		$this->db->insert($this->table_rekanan_tax, $data);
		return true;
	}

	function update_data_vendor_tax($data, $id_vendor)
	{
		$this->db->where('ID_VENDOR', $id_vendor);
		$this->db->update($this->table_rekanan_tax, $data);
		return true;
	}

	function get_vendor_tax_datatable()
	{

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("NAMA_VENDOR","NOMOR_NPWP","ALAMAT","NO_TLP","NAMA_PIC_VENDOR","ALAMAT_EMAIL","NAMA_BANK","NAMA_REKENING","ACCT_NUMBER");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
						ID_VENDOR,
						UPPER(NAMA_VENDOR) NAMA_VENDOR,
						DOMISILI,
						NOMOR_NPWP,
						ALAMAT,
						NO_TLP,
						NAMA_PIC_VENDOR,
						ALAMAT_EMAIL,
						NAMA_BANK,
						NAMA_REKENING,
						ACCT_NUMBER,
						S_KET_PP23,
						S_KET_DTP,
						SKB_PPH23,
						SKB_PPH_LAINNYA,
						TIN,
						KTP,
						SKD,
						KODE_NEGARA
						FROM $this->table_rekanan_tax
						where 1=1
						$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function check_exist_vendor_tax($nama_rekanan)
	{
		$where = "";
		$whereArr = array();

		if($nama_rekanan){
			$where .= " AND NAMA_VENDOR = ?";
		}

		$mainQuery  = "SELECT * FROM $this->table_rekanan_tax  
						where 1=1
						$where";

		return $this->db->query($mainQuery, $nama_rekanan)->num_rows();
	}

	function delete_data_vendor_tax($id_vendor)
	{
		$this->db->where('ID_VENDOR', $id_vendor);
		$this->db->delete($this->table_rekanan_tax);
		return true;
	}

	public function get_master_vendor_tax()
	{
		$mainQuery	        = "SELECT 
								ID_VENDOR,
								NAMA_VENDOR,
								NOMOR_NPWP,
								ALAMAT,
								DOMISILI,
								NO_TLP,
								NAMA_PIC_VENDOR,
								ALAMAT_EMAIL,
								NAMA_BANK,
								NAMA_REKENING,
								ACCT_NUMBER,
								S_KET_PP23,
								S_KET_DTP,
								SKB_PPH23,
								SKB_PPH_LAINNYA,
								TIN,
								KTP,
								SKD,
								KODE_NEGARA
								FROM $this->table_rekanan_tax  
								";
								$query 		        = $this->db->query($mainQuery);
								$result['query']	= $query;
								return $result;		
	}

	public function check_kode_negara($domisili)
	{
		$where = "";
		$whereArr = array();

		if($domisili){
			$where .= " AND NAMA_NEGARA = ?";
		}

		$mainQuery  = "SELECT * FROM $this->table_kode_negara  
						where 1=1
						$where";

		return $this->db->query($mainQuery, $domisili)->row();
	}

	function insert_upload_vendor_import_tax($data)
	{
		foreach ($data as $row)
		{
			$paramKey = array_keys($row);

			for ($i=0; $i < count($paramKey); $i++) {
				$onUpdate[] = $paramKey[$i] ." = '".$row[$paramKey[$i]]."'";
			}

			$sql = $this->db->insert_string($this->table_rekanan_tax, $row) . " ON DUPLICATE KEY UPDATE " .implode(",", $onUpdate);
			$this->db->query($sql);

			// echo $this->db->last_query(); die;

		}


		return true;

	}

	public function get_master_domisili()
	{
		$mainQuery	        = "select * from KODE_NEGARA";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;	
	}

	//region RATE

	function get_all_rate(){

		$this->db->select('*');
		$this->db->from($this->table_rate);
		$this->db->order_by("ID_RATE", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_rate_datatable($date_from,$date_to)
	{

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("USD","EUR","SGD","AUD","JPY","HKD","CURRENCY_DATE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID_RATE,
		USD,
		EUR,
		SGD,
		AUD,
		JPY,
		HKD,
		CURRENCY_DATE
		FROM $this->table_rate
		where 1=1

		and CONVERT(CURRENCY_DATE, DATE) BETWEEN ? and ?
		$where  order by ID_RATE desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to))->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_rate($date_from,$date_to)
	{
		$vdate_from = $date_from;
		$vdate_to = $date_to;
		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);
		$currency_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$currency_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		
		$where = "";

		$mainQuery	        = "SELECT 
		ID_RATE,
		USD,
		EUR,
		SGD,
		AUD,
		JPY,
		HKD,
		CURRENCY_DATE
		FROM $this->table_rate 
		where 1=1 
		and ( CONVERT(CURRENCY_DATE, DATE) BETWEEN ? and ? )
						$where ";
		$query 		        = $this->db->query($mainQuery, array($currency_date_from, $currency_date_to));
		$result['query']	= $query;
		return $result;		
	}

	function save_data_rate($data)
	{
		$this->db->insert($this->table_rate, $data);
		return true;
	}

	function update_data_rate($data, $id_rate)
	{
		$this->db->where('ID_RATE', $id_rate);
		$this->db->update($this->table_rate, $data);
		return true;
	}

	function delete_data_rate($id_rate)
	{
		$this->db->where('ID_RATE', $id_rate);
		$this->db->delete($this->table_rate);
		return true;
	}

	//endregion RATE mdl

	//region Master Approval mdl

	function get_all_master_approval(){

		$this->db->select('*');
		$this->db->from($this->table_master_approval);
		$this->db->order_by("ID_APPROVAL", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_master_approval_datatable($directorat="", $level="")
	{

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			$fieldToSearch = array("DIRECTORAT_NAME", "DIVISION_NAME", "UNIT_NAME", "PIC_LEVEL", "PIC_NAME", "JABATAN", "PIC_DELEGATION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}
		$whereVal = array();

		$where_dir = "";

		if($directorat != ""){
			$where_dir = " AND ma.ID_DIR_CODE = ?";

			$whereVal[] = $directorat;
		}
		if($level != ""){

          	if( $level == "BOD" ):
          		$where_dir = " AND (ma.PIC_LEVEL = 'CEO' OR ma.PIC_LEVEL = 'CFO' OR ma.PIC_LEVEL = 'CMO' OR ma.PIC_LEVEL = 'COO' OR ma.PIC_LEVEL = 'CTO')";
          	else:
				$where_dir .= " AND ma.PIC_LEVEL = ?";
				$whereVal[] = $level;
          	endif;
		}

		$where .= $where_dir;

		$mainQuery = "SELECT  ma.ID_APPROVAL, ma.ID_DIR_CODE, dr.DIRECTORAT_NAME, ma.ID_DIVISION, dv.DIVISION_NAME,
							ma.ID_UNIT, un.UNIT_NAME, ma.PIC_LEVEL, ma.PIC_NAME, ma.JABATAN, ma.PIC_EMAIL,
							ma.PIC_DELEGATION, ma.DELEGATION_START_PERIOD, ma.DELEGATION_END_PERIOD
						FROM $this->table_master_approval ma
						LEFT JOIN $this->table_unit un ON ma.ID_UNIT = un.ID_UNIT
						LEFT JOIN $this->table_division dv ON ma.ID_DIVISION = dv.ID_DIVISION
						LEFT JOIN $this->table_directorat dr ON ma.ID_DIR_CODE = dr.ID_DIR_CODE
						where 1=1
						$where
						ORDER BY FIELD(ma.PIC_LEVEL, 'BOC', 'Budget Comitee', 'CEO', 'CFO', 'CMO', 'COO', 'CTO', 'HOG Budget', 'Budget Admin', 'Risk', 'Fraud', 'HOG User', 'Submitter'), DIRECTORAT_NAME, DIVISION_NAME, PIC_NAME
					";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_master_approval()
	{
		$param_id_dir_code  = $this->input->post('param_id_dir_code');
		$param_id_division  = $this->input->post('param_id_division');
		$param_id_unit  	= $this->input->post('param_id_unit');

		$where = "";
		$whereArr = array();

		if($param_id_dir_code){
			$where .= " AND ma.ID_DIR_CODE = ?";
			$whereArr[] = $param_id_dir_code;
		}

		if($param_id_division){
			$where .= " AND ma.ID_DIVISION = ?";
			$whereArr[] = $param_id_division;
		}

		if($param_id_unit){
			$where .= " AND ma.ID_UNIT = ?";
			$whereArr[] = $param_id_unit;
		}

		$mainQuery	        = "SELECT 
		ma.ID_APPROVAL,
		ma.ID_DIR_CODE,
		dr.DIRECTORAT_NAME,
		ma.ID_DIVISION,
		dv.DIVISION_NAME,
		ma.ID_UNIT,
		un.UNIT_NAME,
		ma.PIC_LEVEL,
		ma.PIC_NAME,
		ma.JABATAN,
		ma.PIC_DELEGATION,
		ma.DELEGATION_START_PERIOD,
		ma.DELEGATION_END_PERIOD

		FROM $this->table_master_approval ma
		INNER JOIN $this->table_unit un 
		on ma.ID_UNIT = un.ID_UNIT
		INNER JOIN $this->table_division dv 
		on ma.ID_DIVISION = dv.ID_DIVISION
		INNER JOIN $this->table_directorat dr 
		on ma.ID_DIR_CODE = dr.ID_DIR_CODE
		where 1=1
		$where ";

		$query 		        = $this->db->query($mainQuery,$whereArr);
		$result['querymu']	= $query;
		return $result;		
	}

	public function get_master_employee_by_level()
	{
		$param_id_dir_code  = $this->input->post('param_id_dir_code');
		$param_id_division  = $this->input->post('param_id_division');
		$param_id_unit  	= $this->input->post('param_id_unit');
		$param_level  		= $this->input->post('param_level');
		
		$where = "";
		$whereArr = array();


		if ($param_level)
		{
			if ($param_level == 'HoG')
			{
				$where .= "  AND emp.ID_DIVISION = ? AND  jbt.JABATAN like '%Head of Group%' ";
				$whereArr[] = $param_id_division;
			}
			elseif ($param_level == 'HoU')
			{
				$where .= "  AND emp.ID_UNIT = ? AND  jbt.JABATAN like '%Head of%' ";
				$whereArr[] = $param_id_unit;
			}
			elseif ($param_level == 'Director' ||  $param_level == 'CFO')
			{
				$where .= "  AND emp.ID_DIR_CODE = ? AND  jbt.JABATAN like '%Chief%' ";
				$whereArr[] = $param_id_dir_code;
			}
			else
			{

			}
		}
			


		$mainQuery	        = "SELECT emp.ID_EMPLOYEE, 
		emp.NIK, 
		emp.NAMA, 
		emp.ID_JABATAN, 
		COALESCE(jbt.JABATAN, emp.JABATAN) as JABATAN,
		emp.ID_UNIT,
		COALESCE(un.UNIT_NAME,emp.UNIT_NAME) as UNIT_NAME,  
		emp.ID_DIVISION, 
		COALESCE(dv.DIVISION_NAME, emp.DIVISION_NAME) as DIVISION_NAME, 
		emp.ID_DIR_CODE, 
		COALESCE(dir.DIRECTORAT_NAME,emp.DIRECTORAT_NAME) as DIRECTORAT_NAME, 
		emp.NO_HP, 
		emp.ALAMAT_EMAIL FROM $this->table_employee emp 
		LEFT JOIN $this->table_directorat dir on emp.ID_DIR_CODE = dir.ID_DIR_CODE
		LEFT JOIN $this->table_division dv on emp.ID_DIVISION = dv.ID_DIVISION
		LEFT JOIN $this->table_unit un on emp.ID_UNIT = un.ID_UNIT
		LEFT JOIN $this->table_jabatan jbt on emp.ID_JABATAN = jbt.ID_JABATAN
		where 1=1 
		 ".$where." ";
		$query 		        = $this->db->query($mainQuery,$whereArr);

		// echo $this->db->last_query(); die;

		$result['querymu']	= $query;
		return $result;		
	}


	function save_data_master_approval($data)
	{
		$this->db->insert($this->table_master_approval, $data);
		return true;
	}

	function update_data_master_approval($data, $id_approval)
	{
		$this->db->where('ID_APPROVAL', $id_approval);
		$this->db->update($this->table_master_approval, $data);
		return true;
	}

	function delete_master_approval($id_approval)
	{
		$this->db->where('ID_APPROVAL', $id_approval);
		$this->db->delete($this->table_master_approval);
		return true;
	}


	function get_employee_approval(){

		$sql = "SELECT NAMA, ALAMAT_EMAIL, JABATAN FROM `MASTER_EMPLOYEE`
					-- WHERE JABATAN LIKE 'Chief%' OR JABATAN LIKE 'Head of%' OR JABATAN LIKE '% Head of %' OR JABATAN LIKE '%Group'
					ORDER BY NAMA";
					
		return $this->db->query($sql)->result_array();
	}

	

	//endregion Master Approval mdl

	//region Master Mapping Cash Flow mdl

	function get_all_mapping_cash_flow(){

		$this->db->select('*');
		$this->db->from($this->table_mapping_cash_flow);
		$this->db->order_by("ID", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_mapping_cash_flow_datatable()
	{

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$datefilter = "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("DESCRIPTION","NATURE","DESC_NATURE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		ID,
		DESCRIPTION,
		NATURE,
		DESC_NATURE
		FROM $this->table_mapping_cash_flow
		where 1=1
		$where  order by ID desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_mapping_cash_flow()
	{
		$mainQuery = "SELECT 
		ID,
		DESCRIPTION,
		NATURE,
		DESC_NATURE
		FROM $this->table_mapping_cash_flow
		where 1=1
		$where  order by ID desc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_mapping_cash_flow($nature)
	{
		$where = "";
		$whereArr = array();

		if($nature){
			$where .= " AND NATURE = ?";
			$whereArr[] = $nature;
		}

		$mainQuery	        = "SELECT *
		FROM $this->table_mapping_cash_flow  
		where 1=1
		$where
		order by ID desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_mapping_cash_flow($data)
	{
		$this->db->insert($this->table_mapping_cash_flow, $data);
		return true;
	}

	function update_data_mapping_cash_flow($data, $id)
	{
		$this->db->where('ID', $id);
		$this->db->update($this->table_mapping_cash_flow, $data);
		return true;
	}

	function delete_data_mapping_cash_flow($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete($this->table_mapping_cash_flow);
		return true;
	}

	//endregion Master Mapping Cash Flow mdl

	//region PROC TYPE

	function get_all_proc_type(){

		$this->db->select('*');
		$this->db->from($this->table_proc_type);
		$this->db->order_by("PROC_TYPE_ID", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}
	}

	function get_proc_type_datatable()
	{

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
			$fieldToSearch = array("MAPPING","PROC_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT 
		PROC_TYPE_ID,
		MAPPING,
		PROC_NAME
		FROM $this->table_proc_type
		where 1=1
		$where  order by PROC_TYPE_ID desc";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		
		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;		
	}

	public function get_master_proc_type()
	{
		$mainQuery	        = "SELECT 
		PROC_TYPE_ID, 
		MAPPING,
		PROC_NAME
		FROM $this->table_proc_type  
		order by PROC_TYPE_ID";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function check_exist_proc_type($proc_name)
	{
		$where = "";
		$whereArr = array();

		if($proc_name){
			$where .= " AND PROC_NAME = ?";
			$whereArr[] = $id_proc_type;
		}


		$mainQuery	        = "SELECT *
		FROM $this->table_proc_type  
		WHERE 1=1 
		$where
		order by PROC_TYPE_ID desc";
		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery,$whereArr)->num_rows();
		$data  = $this->db->query($queryData,$whereArr)->result_array();
		$result['total_data'] = $total;

		return $result;
	}

	function save_data_proc_type($data)
	{
		$this->db->insert($this->table_proc_type, $data);
		return true;
	}

	function update_data_proc_type($data, $proc_type_id)
	{
		$this->db->where('PROC_TYPE_ID', $PROC_TYPE_ID);
		$this->db->update($this->table_proc_type, $data);
		return true;
	}

	function delete_data_proc_type($proc_type_id)
	{
		$this->db->where('PROC_TYPE_ID', $id_dir_code);
		$this->db->delete($this->table_directorat);
		return true;
	}

	//endregion PROC TYPE mdl

}

/* End of file Master_mdl.php */
/* Location: ./application/models/Master_mdl.php */