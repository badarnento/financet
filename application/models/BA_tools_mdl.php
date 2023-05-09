<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BA_tools_mdl extends CI_Model {

	protected   $table = "TRX_UPLOAD_DIGIPOS";

	public function get_all($category){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("FILE_NAME", "KEY_UPLOAD", "UPLOAD_BY");
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT * FROM $this->table where CATEGORY = ? $where order by ID DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $category)->num_rows();
		$data  = $this->db->query($queryData, $category)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

}

/* End of file BA_tools_mdl.php */
/* Location: ./application/models/BA_tools_mdl.php */