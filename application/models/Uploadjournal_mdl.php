<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Uploadjournal_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->table_upload_journal = "GL_JOURNAL_LINE";
		$this->view_upload_journal = "V_JOURNAL_LINES";
	}


	function get_upload_journal_datatable($gl_date_from,$gl_date_to, $nature, $journaltype)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";

		if($journaltype)
		{
			if($nature != "")
			{
				//new
				// if($journaltype == "Others")
				// {
				// 	$where .= " AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND 	journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )) and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."'))";
				// }
				// elseif($journaltype == "CM")
				// {
				// 	$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' )) and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."')) ";
				// }
				// elseif($journaltype == "LA")
				// {
				// 	$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND JOURNAL_NAME  like  '%/".$journaltype."%') and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."') and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."'))";
				// }
				// else
				// {
				// 	$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND  JOURNAL_NAME  like  '%/".$journaltype."/%') and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."'))";
				// }

				//old
				if($journaltype == "Others")
				{
					$where .= " AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND 	journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )))";
				}
				elseif($journaltype == "CM")
				{
					$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' ))) ";
				}
				elseif($journaltype == "LA")
				{
					$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND JOURNAL_NAME  like  '%/".$journaltype."%')) ";
				}
				else
				{
					$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND  JOURNAL_NAME  like  '%/".$journaltype."/%')) ";
				}

			}
			else
			{
				if($journaltype == "Others")
				{
					$where .= " AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND 	journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )";
				}
				elseif($journaltype == "CM")
				{
					$where .= "  AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' ) ";
				}
				elseif($journaltype == "LA")
				{
					$where .= "  AND JOURNAL_NAME  like  '%/".$journaltype."%' ";
				}
				else
				{
					$where .= "  AND  JOURNAL_NAME  like  '%/".$journaltype."/%' ";
				}
			}

			
		}
		else
		{
			if($nature != "")
			{
				//old
				$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."')) ";
				//new
				// $where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."')  and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."')) ";
			}
		}

		if($keywords != ""){
			$fieldToSearch = array("GL_DATE","BATCH_NAME","JOURNAL_NAME","SALDO_AWAL","DEBIT","CREDIT","NATURE","ACCOUNT_DESCRIPTION","JOURNAL_DESCRIPTION");
			$where .= query_datatable_search($keywords, $fieldToSearch);
		}


		$mainQuery = "
		SELECT 
		CONVERT(GL_DATE, DATE) as GL_DATE,
		BATCH_NAME,
		JOURNAL_NAME,
		SALDO_AWAL,
		DEBIT,
		CREDIT,
		ACCOUNT_DESCRIPTION,
		JOURNAL_DESCRIPTION,
		NATURE,
		REFERENCE_1,
		REFERENCE_2,
		REFERENCE_3
		FROM
		$this->view_upload_journal

		where 1=1

		and CONVERT(GL_DATE, DATE) BETWEEN ? and ? 

		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($gl_date_from, $gl_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($gl_date_from, $gl_date_to))->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}

	public function insert_upload_clearing_bank_import($data)
	{
		foreach ($data as $row)
		{

			// echo "<pre>";print_r($row);

			$paramKey = array_keys($row);

			for ($i=0; $i < count($paramKey); $i++) {
				$onUpdate[] = $paramKey[$i] ." = '".$row[$paramKey[$i]]."'";
			}

   			// echo implode(",", $onUpdate);

			// die;

			$nojurnal = $row['NO_JOURNAL'];
			$sql = $this->db->insert_string($this->table_post_clearing_bank, $row) . " ON DUPLICATE KEY UPDATE " .implode(",", $onUpdate);
			$this->db->query($sql);

			// echo $this->db->last_query();
			// die;

		}


		return true;

	}


	function get_download_upload_journal($date_from,$date_to,$nature,$journaltype)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where    = "";

		if($journaltype)
		{
			if($nature != "" && $nature != "undefined")
			{
				//new
				// if($journaltype == "Others")
				// {
				// 	$where .= " AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND 	journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )) and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."'))";
				// }
				// elseif($journaltype == "CM")
				// {
				// 	$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' )) and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."')) ";
				// }
				// elseif($journaltype == "LA")
				// {
				// 	$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND JOURNAL_NAME  like  '%/".$journaltype."%') and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."') and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."'))";
				// }
				// else
				// {
				// 	$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND  JOURNAL_NAME  like  '%/".$journaltype."/%') and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."'))";
				// }

				//old
				if($journaltype == "Others")
				{
					$where .= " AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND 	journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )))";
				}
				elseif($journaltype == "CM")
				{
					$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' ))) ";
				}
				elseif($journaltype == "LA")
				{
					$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND JOURNAL_NAME  like  '%/".$journaltype."%')) ";
				}
				else
				{
					$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."' AND  JOURNAL_NAME  like  '%/".$journaltype."/%')) ";
				}

			}
			else
			{
				if($journaltype == "Others")
				{
					$where .= " AND ( JOURNAL_NAME not like '%/CM/%' AND JOURNAL_NAME not like '%/TR/%' AND journal_name not like '%/AP/%' AND 	journal_name not like '%/PY/%' AND journal_name not like '%/AR/%' AND JOURNAL_NAME not like '%/LA%' )";
				}
				elseif($journaltype == "CM")
				{
					$where .= "  AND ( JOURNAL_NAME  like  '%/".$journaltype."/%' or  JOURNAL_NAME  like  '%/TR/%' ) ";
				}
				elseif($journaltype == "LA")
				{
					$where .= "  AND JOURNAL_NAME  like  '%/".$journaltype."%' ";
				}
				else
				{
					$where .= "  AND  JOURNAL_NAME  like  '%/".$journaltype."/%' ";
				}
			}

			
		}
		else
		{
			if($nature != "" && $nature != "undefined")
			{
				//new
				// $where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."') and journal_description in(select  distinct journal_description from V_JOURNAL_LINES where NATURE='".$nature."')
				// 		and reference_1 in(select  distinct reference_1 from V_JOURNAL_LINES where NATURE='".$nature."')) ";
				
				//old
				$where .= "  AND (JOURNAL_NAME in (SELECT JOURNAL_NAME from V_JOURNAL_LINES where NATURE='".$nature."')) ";
			}
		}

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT * from $this->view_upload_journal where CONVERT(GL_DATE, DATE) BETWEEN ? and ? $where";

		$query     = $this->db->query($queryExec, array($gl_date_from,$gl_date_to));

		return $query;
	}

}



/* End of file Uploadjournal_mdl.php */

/* Location: ./application/models/Uploadjournal_mdl.php */