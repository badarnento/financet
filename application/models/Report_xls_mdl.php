<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_xls_mdl extends CI_Model {

	function get_bswp($year, $month, $category, $year1, $month1, $year2, $month2){

		$sql = " SELECT mc.NATURE,
						mc.DESCRIPTION,
						mg.GROUP_REPORT,
			       		ifnull((select IFNULL(SUM(saldo_awal),0) from V_TRIAL_BALANCE vtb where vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0)
				   		+     
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where (vtb.month between 1 and $month)  and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) AMOUNT,
			       		ifnull((select IFNULL(SUM(saldo_awal),0) from V_TRIAL_BALANCE vtb where vtb.YEAR = $year1 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0)
				   		+     
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where (vtb.month between 1 and $month1)  and vtb.YEAR = $year1 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) AMOUNT1,
			       		ifnull((select IFNULL(SUM(saldo_awal),0) from V_TRIAL_BALANCE vtb where vtb.YEAR = $year2 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0)
				   		+     
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where (vtb.month between 1 and $month2)  and vtb.YEAR = $year2 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) AMOUNT2
				 FROM 
				       MASTER_COA mc
				       inner join MASTER_GROUP mg
				       on mg.ID_GROUP = mc.ID_GROUP
				 WHERE mc.nature <= 32000001
				 ORDER BY mc.NATURE ";

		$query = $this->db->query($sql);

		return $query;
	}

	function get_pldetail($year, $month, $category, $year1, $month1, $year2, $month2){

		$sql = " SELECT mc.NATURE,
						mc.DESCRIPTION,
						mg.GROUP_REPORT,
						ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month= $month and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) PTD,
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where (vtb.month between 1 and $month)  and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) YTD,
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month= $month1 and vtb.YEAR = $year1 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) PTD1,
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where (vtb.month between 1 and $month1)  and vtb.YEAR = $year1 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) YTD1,
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where vtb.month= $month2 and vtb.YEAR = $year2 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) PTD2,
			       		ifnull((select IFNULL(SUM(balance),0) from V_TRIAL_BALANCE vtb where (vtb.month between 1 and $month2)  and vtb.YEAR = $year2 and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) YTD2
				 FROM 
				       MASTER_COA mc
				       inner join MASTER_GROUP mg
				       on mg.ID_GROUP = mc.ID_GROUP
				 WHERE mc.nature > 40000000
				 ORDER BY mc.NATURE ";

		$query = $this->db->query($sql);

		return $query;
	}

	function get_bs($year, $month){

		$sql = " select total.GROUP_REPORT,sum(total.total) TOTAL_AMOUNT
					from
					(SELECT mc.NATURE,
											mc.DESCRIPTION,
											mg.GROUP_REPORT,
								       		ifnull((select IFNULL(SUM(saldo_awal),0) from GL_TB_CLOSING vtb where vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0)
									   		+     
								       		ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where (vtb.month between 1 and $month)  and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) TOTAL
									 FROM 
									       MASTER_COA mc
									       inner join MASTER_GROUP mg
									       on mg.ID_GROUP = mc.ID_GROUP
									 WHERE mc.nature <= 32000001   
									 ORDER BY mc.NATURE) total
					group by total.group_report ";

		$query = $this->db->query($sql);

		return $query;
	}

	function get_she($year, $month){

		$sql = " SELECT mc.NATURE,
						mc.DESCRIPTION,
						mg.GROUP_REPORT,
			       		ifnull((select IFNULL(SUM(saldo_awal),0) from GL_TB_CLOSING vtb where vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0)
				   		+     
			       		ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where (vtb.month between 1 and $month)  and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) AMOUNT
				 FROM 
				       MASTER_COA mc
				       inner join MASTER_GROUP mg
				       on mg.ID_GROUP = mc.ID_GROUP
				 WHERE mc.nature <= 32000001
				 ORDER BY mc.NATURE ";

		$query = $this->db->query($sql);

		return $query;
	}

	function get_pl($year, $month){

		$sql = " select total.GROUP_REPORT, total.NATURE, sum(total.total) TOTAL_AMOUNT
					from
					(SELECT mc.NATURE,
											mc.DESCRIPTION,
											mg.GROUP_REPORT,   
								       		ifnull((select IFNULL(SUM(balance),0) from GL_TB_CLOSING vtb where (vtb.month between 1 and $month)  and vtb.YEAR = $year and vtb.nature= mc.nature group by vtb.nature,vtb.year),0) TOTAL
									 FROM 
									       MASTER_COA mc
									       inner join MASTER_GROUP mg
									       on mg.ID_GROUP = mc.ID_GROUP
									 WHERE mc.nature > 40000000
									 ORDER BY mc.NATURE) total
					group by total.group_report ";

		$query = $this->db->query($sql);

		return $query;
	}

	public function get_ap_approuved_jornal($accounting_date_from, $accounting_date_to){

		$mainQuery = "select date(vjl.gl_date) ACCOUNTING_DATE,vjl.BATCH_NAME,
						vjl.JOURNAL_NAME,vjl.DEBIT,vjl.CREDIT,vjl.NATURE,vjl.ACCOUNT_DESCRIPTION,
						vjl.JOURNAL_DESCRIPTION,vjl.REFERENCE_1,vjl.REFERENCE_2,vjl.REFERENCE_3,
						ifnull((select distinct it.payment_create from INVOICE_TRACKER it where it.NO_JOURNAL = vjl.journal_name limit 1),0) PAID_DATE,
						(
						    SELECT DISTINCT
						        MASTER_GROUP.GROUP_REPORT
						    FROM
						        MASTER_GROUP
						    WHERE
						        MASTER_GROUP.ID_GROUP =(
						        SELECT
						            MC.ID_GROUP
						        FROM
						            MASTER_COA MC
						        WHERE
						            MC.NATURE = vjl.NATURE
						    )
						) AS TYPE_TAX
						from V_JOURNAL_LINES vjl
						where BATCH_NAME like '%/AP/%' and ( CONVERT(vjl.GL_DATE, DATE) BETWEEN ? and ? ) ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($accounting_date_from, $accounting_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($accounting_date_from, $accounting_date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_ap_appr_journal_download($accounting_date_from, $accounting_date_to){

		$sql = " select date(vjl.gl_date) ACCOUNTING_DATE,vjl.BATCH_NAME,
						vjl.JOURNAL_NAME,vjl.DEBIT,vjl.CREDIT,vjl.NATURE,vjl.ACCOUNT_DESCRIPTION,
						vjl.JOURNAL_DESCRIPTION,vjl.REFERENCE_1,vjl.REFERENCE_2,vjl.REFERENCE_3,
						ifnull((select distinct it.payment_create from INVOICE_TRACKER it where it.NO_JOURNAL = vjl.journal_name limit 1),0) PAID_DATE,
						(
						    SELECT DISTINCT
						        MASTER_GROUP.GROUP_REPORT
						    FROM
						        MASTER_GROUP
						    WHERE
						        MASTER_GROUP.ID_GROUP =(
						        SELECT
						            MC.ID_GROUP
						        FROM
						            MASTER_COA MC
						        WHERE
						            MC.NATURE = vjl.NATURE
						    )
						) AS TYPE_TAX
						from V_JOURNAL_LINES vjl
						where BATCH_NAME like '%/AP/%' and ( CONVERT(vjl.GL_DATE, DATE) BETWEEN ? and ? ) ";

		$query = $this->db->query($sql, array($accounting_date_from, $accounting_date_to));

		return $query;
	}

}

/* End of file Report_xls_mdl.php */
/* Location: ./application/models/Report_xls_mdl.php */