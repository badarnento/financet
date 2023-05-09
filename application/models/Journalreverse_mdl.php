<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Journalreverse_mdl extends CI_Model {



	public function __construct()

	{

		parent::__construct();
		$this->view_upload_journal = "V_JOURNAL_LINES";
	}

	function get_journal_reverse_datatable_old($gl_date_from,$gl_date_to,$journalname)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("GL_DATE","BATCH_NAME","SALDO_AWAL","DEBIT","CREDIT","NATURE","ACCOUNT_DESCRIPTION","JOURNAL_DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($journalname)
		{
			$where .= "  AND  JOURNAL_NAME = '".$journalname."' ";
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
		$where order by JOURNAL_NAME,BATCH_NAME";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($gl_date_from, $gl_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($gl_date_from, $gl_date_to))->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;		

	}

	function get_journal_reverse_datatable($journalname)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("GL_DATE","BATCH_NAME","SALDO_AWAL","DEBIT","CREDIT","NATURE","ACCOUNT_DESCRIPTION","JOURNAL_DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($journalname)
		{
			$where .= "  AND  JOURNAL_NAME = '".$journalname."' ";
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

		$where order by JOURNAL_NAME,BATCH_NAME";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;		

	}

	public function get_data_ddl_journal()
	{
		/*$mainQuery	        = "SELECT DISTINCT JOURNAL_NAME
		FROM $this->view_upload_journal 
		where ( JOURNAL_NAME not in (select distinct replace(replace(journal_name,'reverse_',''),'_reverse','') JOURNAL_NAME from V_JOURNAL_LINES where JOURNAL_DESCRIPTION like '%reverse%') AND JOURNAL_NAME not in (select distinct JOURNAL_NAME from V_JOURNAL_LINES where JOURNAL_DESCRIPTION like '%reverse%') )
		order by JOURNAL_NAME";*/
		/*$mainQuery = " SELECT DISTINCT JOURNAL_NAME
						FROM GL_JOURNAL_LINE
						where (JOURNAL_NAME not in (select distinct replace(replace(journal_name,'reverse_',''),'_reverse','') JOURNAL_NAME from GL_JOURNAL_LINE where JOURNAL_DESCRIPTION like '%reverse%') 
						AND JOURNAL_NAME not in (select distinct JOURNAL_NAME from GL_JOURNAL_LINE where JOURNAL_DESCRIPTION like '%reverse%') )
						AND CONCAT(MONTH(GL_DATE) ,'-',YEAR(GL_DATE)) IN ( SELECT CONCAT(MONTH,'-', YEAR) FROM GL_PERIODS WHERE STATUS ='OPEN')
						union
						select distinct JOURNAL_NAME 
						from JOURNAL_AFTER_TAX
						where APPROVED ='Y' and CONCAT(MONTH(TGL_INVOICE) ,'-',YEAR(TGL_INVOICE)) IN ( SELECT CONCAT(MONTH,'-', YEAR) FROM GL_PERIODS WHERE STATUS ='OPEN')
						union
						select distinct NO_JOURNAL 
						from V_JURNAL_PAYMENT
						where CONCAT(MONTH(GL_DATE) ,'-',YEAR(GL_DATE)) IN ( SELECT CONCAT(MONTH,'-', YEAR) FROM GL_PERIODS WHERE STATUS ='OPEN')
						union
						select distinct JOURNAL_NAME 
						from JOURNAL_AR
						where CONCAT(MONTH(GL_DATE) ,'-',YEAR(GL_DATE)) IN ( SELECT CONCAT(MONTH,'-', YEAR) FROM GL_PERIODS WHERE STATUS ='OPEN') ";*/
						
		$mainQuery = "SELECT DISTINCT JOURNAL_NAME
						FROM GL_JOURNAL_LINE
						where (JOURNAL_NAME not in (select distinct replace(replace(journal_name,'reverse_',''),'_reverse','') JOURNAL_NAME from GL_JOURNAL_LINE where JOURNAL_DESCRIPTION like '%reverse%')
						AND JOURNAL_NAME not in (select distinct JOURNAL_NAME from GL_JOURNAL_LINE where JOURNAL_DESCRIPTION like '%reverse%') )
						union
						select distinct JOURNAL_NAME
						from JOURNAL_AFTER_TAX
						union
						select distinct NO_JOURNAL
						from V_JURNAL_PAYMENT
						union
						select distinct JOURNAL_NAME
						from JOURNAL_AR";
						
		$query 		        = $this->db->query($mainQuery);

		$result['query']	= $query;
		return $result;			
	}

	function call_procedure_reverse($journalname){

		$sql = "CALL JOURNAL_REVERSE_GL('".$journalname."')";

		// echo 'testing'.$sql; die;

        $this->db->query($sql);

        return true;
	}


}



/* End of file Journalreverse_mdl.php */

/* Location: ./application/models/Journalreverse_mdl.php */