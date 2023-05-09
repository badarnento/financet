<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tb_mdl extends CI_Model {

	function get_tb_ytd($year, $category){

		$where = "";
		if($category == "tb"){
			$where = " vtb.YEAR = ? ";
		}else{
			$where = "";
		}

		/*$where_1 = " vtb.YEAR = '$year'";*/

		$sql = "SELECT mc.NATURE,mc.DESCRIPTION,mg.GROUP_REPORT,
				       ifnull((select IFNULL(SUM(saldo_awal),0) from GL_TB_CLOSING vtb where vtb.YEAR =? and vtb.nature= mc.nature group by vtb.nature),0) SALDO_AWAL,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=1 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) JAN,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=2 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) FEB,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=3 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) MAR,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=4 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) APR,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=5 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) MAY,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=6 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) JUN,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=7 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) JUL,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=8 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) AUG,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=9 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) SEP,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=10 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) OCT,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=11 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) NOV,
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.month=12 and vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) DES,     
				       ifnull((select IFNULL(SUM(saldo_awal),0) from GL_TB_CLOSING vtb where vtb.YEAR =? and vtb.nature= mc.nature group by vtb.nature),0)
					   + 
				       ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where vtb.YEAR = ? and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) YTD
									 FROM 
									       MASTER_COA mc
									       inner join MASTER_GROUP mg
									       on mg.ID_GROUP = mc.ID_GROUP
									 ORDER BY mc.NATURE";

		for ($i=0; $i < 15 ; $i++) { 
			$arrYear[] = $year;
		}

		$query = $this->db->query($sql, $arrYear);

		return $query;
	}

	function get_exist_year(){

		$query = $this->db->query('SELECT DISTINCT YEAR FROM GL_TB_CLOSING');

		return $query->result_array();

	}

	function get_tb($year, $month, $category){

		$where = "";
		if($category == "tb"){
			$where = " and YEAR = ? and MONTH = ?";
		}else{
			$where = "";
		}
		$sql = "select vtb.MONTH,
							 vtb.YEAR,
							 vtb.NATURE,
							 mc.DESCRIPTION,
							 vtb.SALDO_AWAL,
							 vtb.DEBIT,
							 vtb.CREDIT,
							 vtb.BALANCE, 
							 vtb.SALDO_AKHIR
						from V_TRIAL_BALANCE vtb
						inner join MASTER_COA mc
						on vtb.NATURE = mc.NATURE
						and vtb.NATURE = mc.NATURE
						$where ";

		$query = $this->db->query($sql, array($year, $month));

		return $query;
	}

	function get_exist_tahun(){

		$query = $this->db->query('SELECT DISTINCT YEAR FROM V_TRIAL_BALANCE');

		return $query->result_array();

	}

	function get_ytd($year){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("NATURE","GROUP_REPORT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		/*$where = " vtb.YEAR = '$year'";*/

		if($year == 0){
			$mainQuery = "SELECT mc.NATURE,mc.DESCRIPTION,mg.GROUP_REPORT, mg.GROUP_REPORT SALDO_AWAL, mc.NATURE JAN, mc.NATURE FEB, mc.NATURE MAR, mc.NATURE APR, mc.NATURE MAY, mc.NATURE JUN, mc.NATURE JUL, mc.NATURE AUG, mc.NATURE SEP, mc.NATURE OCT, mc.NATURE NOV, mc.NATURE DES
				 FROM 
				       MASTER_COA mc, MASTER_GROUP mg
				       where mg.ID_GROUP = mc.ID_GROUP
                       and mc.DESCRIPTION = 'dvsdhsvdshdvsh'";
				   }else{
				   		$mainQuery = "SELECT mc.NATURE,mc.DESCRIPTION,mg.GROUP_REPORT,
								       ifnull((select IFNULL(SUM(saldo_awal),0) from V_TRIAL_BALANCE vtb where vtb.YEAR =$year and vtb.nature= mc.nature group by vtb.nature),0) SALDO_AWAL,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=1 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) JAN,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=2 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) FEB,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=3 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) MAR,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=4 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) APR,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=5 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) MAY,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=6 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) JUN,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=7 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) JUL,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=8 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) AUG,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=9 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) SEP,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=10 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) OCT,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=11 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) NOV,
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month=12 and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) DES,     
								       ifnull((select IFNULL(SUM(saldo_awal),0) from V_TRIAL_BALANCE vtb where vtb.YEAR =$year and vtb.nature= mc.nature group by vtb.nature),0)
									   + 
								       ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) YTD
									 FROM 
									       MASTER_COA mc
									       inner join MASTER_GROUP mg
									       on mg.ID_GROUP = mc.ID_GROUP
									 ORDER BY mc.NATURE";
				   }

		/*for ($i=0; $i < 24; $i++) {
			$arrYear[] = $year;
		}*/

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}

	function get_tbdtl($year, $bulan){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("NATURE","ACCOUNT_DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "select vtb.MONTH,
							 vtb.YEAR,
							 vtb.NATURE,
							 mc.DESCRIPTION,
							 vtb.SALDO_AWAL,
							 vtb.DEBIT,
							 vtb.CREDIT,
							 vtb.BALANCE, 
							 vtb.SALDO_AKHIR
						from V_TRIAL_BALANCE vtb
						inner join MASTER_COA mc
						on vtb.NATURE = mc.NATURE
						and vtb.YEAR = ?
						and vtb.MONTH = ?
						$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}
	

}

/* End of file Tb_mdl.php */
/* Location: ./application/models/Tb_mdl.php */