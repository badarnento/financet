<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_mdl extends CI_Model {

	protected   $tbl_gl_header = "GL_HEADERS",
				$tbl_gl_after_tax    = "GL_JOURNAL_AFTER_TAX",
				$tbl_payment         = "BATCH_PAYMENT",
				$view_jurnal_payment = "V_JURNAL_PAYMENT";


	public function get_batch_inquiry($date_from, $date_to){

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
			$fieldToSearch = array("bp.BATCH_NAME");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT bp.BATCH_NAME, bp.BATCH_DATE, bp.NAMA_BANK, bp.PAID_STATUS, bp.APPROVAL1, bp.APPROVAL2, bp.BANK_CHARGES, bp.BI_RATE, sum(bp.DPP) DPP,
						ifnull((select sum(jbc.credit)-sum(debet) new_dpp from JOURNAL_BATCH_PAYMENT jbc where (jbc.NATURE='11121001' or jbc.NATURE='11122001' or jbc.NATURE='11124001') and jbc.BATCH_NAME=bp.batch_name),
					    (select sum(gat.credit) tes from GL_JOURNAL_AFTER_TAX gat where gat.GL_HEADER_ID = gh.GL_HEADER_ID and (gat.nature='21122001' or gat.nature ='21121001'))) TOTAL_AMOUNT
						FROM BATCH_PAYMENT bp,GL_HEADERS gh
						where bp.GL_HEADER_ID = gh.GL_HEADER_ID
						AND CONVERT(bp.BATCH_DATE, DATE) BETWEEN ? and ?
						$where
						GROUP BY bp.BATCH_NAME
						order by bp.BATCH_DATE DESC, bp.BATCH_NAME ASC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;


		return $result;	

	}

	public function get_batch_all($batch_name){

		$mainQuery = "SELECT bp.BATCH_NAME, bp.NAMA_BANK, bp.BATCH_DATE,
						(select sum(jbc.credit) new_dpp from JOURNAL_BATCH_PAYMENT jbc where (jbc.NATURE='11121001' or jbc.NATURE='11122001' or jbc.NATURE='11124001') and jbc.batch_name=bp.batch_name) TOTAL_AMOUNT
						FROM BATCH_PAYMENT bp
						where 1=1
						AND bp.BATCH_NAME = ?
						GROUP BY bp.BATCH_NAME";

		$data  = $this->db->query($mainQuery, $batch_name)->row_array();

		return $data;

	}

	public function get_batch_all_journal($batch_name){

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
			$fieldToSearch = array("NAMA_VENDOR", "BATCH_NAME", "NO_INVOICE", "NO_JOURNAL");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT NO_JOURNAL, NAMA_VENDOR, NO_INVOICE, NAMA_BANK,
							TGL_INVOICE, NO_KONTRAK, DESCRIPTION, NAMA_REKENING,
							NAMA_BANK, sum(DPP) TOTAL_AMOUNT
						FROM $this->tbl_payment
						where 1=1
						AND BATCH_NAME = ?
						$where
						GROUP BY NAMA_VENDOR,no_journal
						order by NO_JOURNAL";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $batch_name)->num_rows();
		$data  = $this->db->query($queryData, $batch_name)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;

	}

	function get_download_data_batch_payment_view($batch_name)
	{

		$where = "";

		if($batch_name)
		{
			$where .= " AND bp.BATCH_NAME = '".$batch_name."' ";
		}

		$queryExec = "SELECT bp.NO_JOURNAL, bp.NAMA_VENDOR, bp.NO_INVOICE, bp.NAMA_BANK,
							bp.TGL_INVOICE, bp.NO_KONTRAK, bp.DESCRIPTION, bp.NAMA_REKENING,
							bp.NAMA_BANK,ifnull((select sum(jbc.credit) new_dpp from JOURNAL_BATCH_PAYMENT jbc where (jbc.NATURE='11121001' or jbc.NATURE='11122001' or jbc.NATURE='11124001') and jbc.no_journal=bp.NO_JOURNAL),
					    (select sum(gat.credit) tes from GL_JOURNAL_AFTER_TAX gat where gat.GL_HEADER_ID = gh.GL_HEADER_ID and (gat.nature='21122001' or gat.nature='21121001'))) TOTAL_AMOUNT
						FROM BATCH_PAYMENT bp,GL_HEADERS gh
						where bp.GL_HEADER_ID = gh.GL_HEADER_ID
						$where
						GROUP BY bp.NAMA_VENDOR,bp.no_journal
						order by bp.NO_JOURNAL";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	public function get_journal_payment($batch_name){

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
			$fieldToSearch = array("NO_JOURNAL", "NATURE", "ACCOUNT_DESCRIPTION", "DEBIT", "CREDIT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT * FROM $this->view_jurnal_payment WHERE BATCH_NAME = ? ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $batch_name)->num_rows();
		$data  = $this->db->query($queryData, $batch_name)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;

	}

	function get_download_data_journal_payment($batch_name)
	{

		$where = "";

		if($batch_name)
		{
			$where .= " AND BATCH_NAME = '".$batch_name."' ";
		}

		$queryExec = "SELECT * FROM $this->view_jurnal_payment WHERE 1=1
						$where ORDER BY NO_JOURNAL";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	public function get_gl_header($date_from, $date_to, $amount, $bank=false, $amount_group=false){

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
			$fieldToSearch = array("glh.NAMA_VENDOR", "glh.BATCH_NAME", "glh.NO_INVOICE", "glh.NO_JOURNAL");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($bank){
			$bank  = strtolower($bank);
			if($bank == "bni"){
				$where .= " AND lower(NAMA_BANK) like '%bni%' ";
			}
			else{
				$where .= " AND lower(NAMA_BANK) not like '%bni%' ";
			}
		}

		if($amount_group == false){
			$glhDPP = " AND floor((glh.DPP+(glh.PERCENTAGE_PPN/100*glh.DPP))-(glh.percentage_pph/100)*glh.DPP) ";
			switch ($amount) {
				case 1:
					$where .= $glhDPP." <= 500000000";
					break;
				case 2:
					$where .= $glhDPP." <= 5000000000 ".$glhDPP." > 500000000";
					break;
				case 3:
					$where .= $glhDPP." <= 10000000000 ".$glhDPP." > 5000000000";
					break;
				default:
					$where .= $glhDPP." > 10000000000";
					break;
			}
		}


		$mainQuery = "SELECT  glh.GL_HEADER_ID, glh.NO_JOURNAL, glh.NAMA_VENDOR, glh.NO_INVOICE, glh.NAMA_BANK,
							glh.TGL_INVOICE, glh.NO_KONTRAK, glh.DESCRIPTION, glh.NAMA_REKENING,
							glh.NAMA_BANK,IF(IS_GROUP = 0, FLOOR(RAND(5)*1000), 0) GROUP_VENDOR,
                             case when(glh.CURRENCY='USD') then
                             SUM(ROUND((glh.nominal_rate+(glh.PERCENTAGE_PPN/100*glh.nominal_rate))-(glh.percentage_pph/100)*glh.nominal_rate,0)) 
                             when (glh.AMOUNT_BASE_FEE >0) then
                             SUM(ROUND((glh.dpp+(glh.PERCENTAGE_PPN/100*glh.dpp))-(glh.percentage_pph/100)*glh.amount_base_fee,0)) 
                             else
                             SUM(ROUND((glh.DPP+(glh.PERCENTAGE_PPN/100*glh.DPP))-(glh.percentage_pph/100)*glh.DPP,0))
                             end TOTAL_AMOUNT,  
							(select group_concat(IFNULL(ar.REFERENCE_2, '')) 
						from JOURNAL_AR ar where ar.REFERENCE_1 = glh.NAMA_VENDOR and ar.nature = 11212002 AND (ar.STATUS IS NULL OR STATUS = '')) AR_INVOICE
						FROM $this->tbl_gl_header glh
						where 1=1
						AND CONVERT(glh.TGL_INVOICE, DATE) BETWEEN ? and ?
						AND (glh.payment_status IS NULL OR glh.payment_status ='')
						AND glh.AR_IS_MORE_THAN_AP != 'Y'
						AND glh.APPROVED_HOU = 'Y'
						AND glh.IS_REVERSE IS NULL
						$where
						GROUP BY glh.NAMA_VENDOR, GROUP_VENDOR                         
						order by glh.TGL_INVOICE DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;

	}

	public function get_gl_header_detail($date_from, $date_to, $nama_vendor="", $bank="", $gl_id="", $is_group=true, $amount_group=false, $amount){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("NO_INVOICE", "NO_JOURNAL", "DPP");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($nama_vendor != ""){
			$where       .= " AND lower(glh.NAMA_VENDOR) = ?";
			$whereVal[]  = strtolower($nama_vendor);
		}

		if($bank){
			$bank  = strtolower($bank);
			if($bank == "bni"){
				$where .= " AND lower(NAMA_BANK) like '%bni%' ";
			}
			else{
				$where .= " AND lower(NAMA_BANK) not like '%bni%' ";
			}
		}

		if($is_group === true):
			$where .= " AND glh.IS_GROUP = 1";
		else:
			$where .= " AND glh.GL_HEADER_ID = ?";
			$whereVal[] = $gl_id;
		endif;

		if($amount_group == false){
			$glhDPP = " AND floor((glh.DPP+(glh.PERCENTAGE_PPN/100*glh.DPP))-(glh.percentage_pph/100)*glh.DPP) ";
			switch ($amount) {
				case 1:
					$where .= $glhDPP." <= 500000000";
					break;
				case 2:
					$where .= $glhDPP." <= 5000000000 ".$glhDPP." > 500000000";
					break;
				case 3:
					$where .= $glhDPP." <= 10000000000 ".$glhDPP." > 5000000000";
					break;
				default:
					$where .= $glhDPP." > 10000000000";
					break;
			}
		}

		$mainQuery = "SELECT DISTINCT glh.GL_HEADER_ID, glh.NO_JOURNAL, glh.NAMA_VENDOR, glh.NO_INVOICE, glh.BATCH_NAME,
							glh.TGL_INVOICE, glh.NO_KONTRAK, glh.DESCRIPTION, glh.NAMA_REKENING,
							glh.NAMA_BANK, glh.AR_NETTING, glh.CURRENCY,
							(select sum(gat.credit)-sum(gat.debet) tes from GL_JOURNAL_AFTER_TAX gat where gat.GL_HEADER_ID = glh.GL_HEADER_ID and (gat.nature='21122001' or gat.NATURE='21121001')) DPP,
							glh.BANK_CHARGES, glh.BI_RATE, glh.IS_GROUP, glh.AR_NETTING, glh.AR_INVOICE
						FROM $this->tbl_gl_header glh
						where 1=1
						AND CONVERT(glh.TGL_INVOICE, DATE) BETWEEN ? and ?						
						AND glh.APPROVED_HOU = 'Y'
						AND (glh.payment_status IS NULL OR glh.payment_status ='')
						AND glh.AR_IS_MORE_THAN_AP != 'Y'
						AND glh.IS_REVERSE IS NULL
						$where
						order by glh.TGL_INVOICE DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereVal)->num_rows();
		$data  = $this->db->query($queryData, $whereVal)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public function get_batch_detai($batch_name, $no_journal){

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
			$fieldToSearch = array("GLH.NAMA_VENDOR", "p.BATCH_NAME", "GLH.NO_INVOICE", "GLH.TGL_INVOICE", "GLH.DPP");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "SELECT GLH.NAMA_VENDOR, P.BATCH_NAME, GLH.NO_INVOICE, GLH.TGL_INVOICE,
						GLH.GL_HEADER_ID, P.DESCRIPTION,
						/*TRUNCATE((GLH.DPP+(GLH.PERCENTAGE_PPN/100*GLH.DPP))-(GLH.PERCENTAGE_PPH/100)*GLH.DPP,0) DPP,*/
						ifnull((select sum(gat.credit)-sum(gat.debet) tes from GL_JOURNAL_AFTER_TAX gat where gat.GL_HEADER_ID = GLH.GL_HEADER_ID and (gat.nature='21122001' or gat.nature='21121001')),TRUNCATE((GLH.DPP+(GLH.PERCENTAGE_PPN/100*GLH.DPP))-(GLH.PERCENTAGE_PPH/100)*GLH.DPP,0)) DPP,
						 GLH.BANK_CHARGES, GLH.BI_RATE, GLH.NAMA_BANK, GLH.NAMA_REKENING, GLH.ACCT_NUMBER
						FROM $this->tbl_payment P
						LEFT JOIN $this->tbl_gl_header GLH ON P.GL_HEADER_ID = GLH.GL_HEADER_ID
						where 1=1
						AND P.BATCH_NAME = ?
						AND P.NO_JOURNAL = ?
						$where
						order by GLH.TGL_INVOICE DESC";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($batch_name, $no_journal))->num_rows();
		$data  = $this->db->query($queryData, array($batch_name, $no_journal))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public function get_gl_header_gl_id($date_from, $date_to, $nama_vendor="", $bank="", $gl_id="", $is_group=true, $amount_group=false, $amount){

		$where = "";

		$whereVal[] = $date_from;
		$whereVal[] = $date_to;

		if($nama_vendor != ""){
			$nama_vendor  = strtolower($nama_vendor);
			$whereVal[] = $nama_vendor;
		}

		if($bank){
			$bank  = strtolower($bank);
			if($bank == "bni"){
				$where .= " AND lower(NAMA_BANK) like '%bni%' ";
			}
			else{
				$where .= " AND lower(NAMA_BANK) not like '%bni%' ";
			}
		}

		if($is_group === true):
			$where .= " AND glh.IS_GROUP = 1";
		else:
			$where .= " AND glh.GL_HEADER_ID = ?";
			$whereVal[] = $gl_id;
		endif;

		if($amount_group == false){
			$glhDPP = " AND floor((glh.DPP+(glh.PERCENTAGE_PPN/100*glh.DPP))-(glh.percentage_pph/100)*glh.DPP) ";
			switch ($amount) {
				case 1:
					$where .= $glhDPP." <= 500000000";
					break;
				case 2:
					$where .= $glhDPP." <= 5000000000 ".$glhDPP." > 500000000";
					break;
				case 3:
					$where .= $glhDPP." <= 10000000000 ".$glhDPP." > 5000000000";
					break;
				default:
					$where .= $glhDPP." > 10000000000";
					break;
			}
		}

		$mainQuery = "SELECT DISTINCT glh.GL_HEADER_ID
						FROM $this->tbl_gl_header glh
						LEFT JOIN $this->tbl_gl_after_tax  glat ON glh.GL_HEADER_ID = glat.GL_HEADER_ID
						where 1=1
						AND CONVERT(glh.TGL_INVOICE, DATE) BETWEEN ? and ?
						AND lower(glh.NAMA_VENDOR) = ?
						AND glh.APPROVED_HOU = 'Y'
						AND (glh.payment_status IS NULL OR glh.payment_status ='')
						AND glh.AR_IS_MORE_THAN_AP != 'Y'
						AND glh.IS_REVERSE IS NULL
						$where
						order by glh.TGL_INVOICE DESC";

		$data  = $this->db->query($mainQuery, $whereVal);

		return $data;	

	}


	public function get_journal_datatable($date_from,$date_to)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("GL_DATE","BATCH_NAME","NO_JOURNAL","NATURE","DEBET","CREDIT","ACCOUNT_DESCRIPTION","JOURNAL_DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		$mainQuery = "
		SELECT 
		CONVERT(GL_DATE, DATE) as GL_DATE,
		BATCH_NAME,
		NO_JOURNAL,
		NATURE,
		DEBET,
		CREDIT,
		ACCOUNT_DESCRIPTION,
		JOURNAL_DESCRIPTION
		FROM
		$this->view_jurnal_payment

		where 1=1

		and CONVERT(BATCH_DATE, DATE) BETWEEN ? and ? 
		$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($date_from, $date_to))->num_rows();
		$data  = $this->db->query($queryData, array($date_from, $date_to))->result_array();

		// echo $this->db->last_query(); die();

		$result['data']       = $data;

		$result['total_data'] = $total;



		return $result;		

	}

	public  function get_download_journal($date_from,$date_to)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("-", $vdate_from);
		$exp_date_to   = explode("-", $vdate_to);

		$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT * from $this->view_jurnal_payment where CONVERT(BATCH_DATE, DATE) BETWEEN '$gl_date_from' and '$gl_date_to' ORDER BY NO_JOURNAL";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	public  function get_download_batch_payment_inquiry($date_from,$date_to)
	{
		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$exp_date_from = explode("-", $vdate_from);
		$exp_date_to   = explode("-", $vdate_to);

		$batch_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$batch_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = "SELECT tp.BATCH_NAME,tp.NAMA_VENDOR,tp.NO_INVOICE,tp.NO_KONTRAK,(select sum(gat.credit)-sum(gat.debet) tes from GL_JOURNAL_AFTER_TAX gat where gat.GL_HEADER_ID = tp.GL_HEADER_ID and (gat.nature='21122001' or gat.nature='21121001')) DPP,tp.NAMA_REKENING,tp.NAMA_BANK,tp.ACCT_NUMBER,tp.DUE_DATE,tp.STATUS
		 from $this->tbl_payment tp where CONVERT(BATCH_DATE, DATE) BETWEEN '".$batch_date_from."' AND '".$batch_date_to."'";

		// echo $queryExec; die();

		$query     = $this->db->query($queryExec);

		return $query;
	}


	public function call_jurnal_payment(){
    	$sql = "CALL JURNAL_PAYMENT()";

        $this->db->query($sql);

        return true;
    }

    function get_csv($batch_name, $category)
	{
		$where = "";
		if($category == '1'){
			$where = " AND lower(NAMA_BANK) like '%bni%' ";
		}
		elseif($category == '2'){
			$where = " AND lower(a.NAMA_BANK)  not like '%bni%'
					   AND a.DPP > 1000000000";
		}else{$where = " AND lower(a.NAMA_BANK)  not like '%bni%'
					   AND a.DPP <= 1000000000";
		}
		$sql = "SELECT a.ACCT_NUMBER,
				a.NAMA_REKENING,
				a.NAMA_BANK,
				(select sum(jbc.credit) new_dpp from JOURNAL_BATCH_PAYMENT jbc where (jbc.NATURE='11121001' or jbc.NATURE='11122001' or jbc.NATURE='11124001') and jbc.no_journal=a.NO_JOURNAL) DPP,
				a.NO_INVOICE,
				a.NO_INVOICE,
				a.NO_KONTRAK,
				a.NO_JOURNAL,
				a.DOMESTIC_BANK_CODE,
				a.BIC_RTGS_CODE,
				a.BATCH_NAME
				from (SELECT
		         bp.ACCT_NUMBER,
		         bp.NAMA_REKENING,
		         bp.NAMA_BANK,
				sum(floor((bp.DPP+(gh.PERCENTAGE_PPN/100*bp.DPP))-(gh.percentage_pph/100)*bp.DPP))DPP,
		         bp.NO_INVOICE,
		         bp.NO_KONTRAK,
		         bp.NO_JOURNAL,
		         mb.DOMESTIC_BANK_CODE,
		         mb.BIC_RTGS_CODE,
      			 bp.BATCH_NAME
		         FROM BATCH_PAYMENT bp, MASTER_BANK mb, GL_HEADERS gh
		         WHERE 1=1
		         and bp.NAMA_BANK = mb.BANK_NAME
                 and gh.GL_HEADER_ID = bp.GL_HEADER_ID
                 and bp.BATCH_NAME = ?
		         group by bp.NAMA_VENDOR,bp.acct_number,no_journal
		         order by bp.NO_JOURNAL) a
                 WHERE 1=1 $where ";
						
		$query = $this->db->query($sql, $batch_name)->result_array();
		$total = $this->db->query($sql, $batch_name)->num_rows();

		//echo $this->db->last_query(); die();

		$result['data'] = $query;
		$result['total_data'] = $total;
		return $result;
	}

	function get_dpp($batch_name, $category)
	{
		$where = "";
		if($category == '1'){
			$where = " AND lower(a.NAMA_BANK)  like '%bni%' ";
		}
		elseif($category == '2'){
			$where = " AND lower(a.NAMA_BANK)  not like '%bni%'
					   AND a.DPP > 1000000000";
		}else{$where = " AND lower(a.NAMA_BANK)  not like '%bni%'
					   AND a.DPP <= 1000000000";
		}

		$sql = " SELECT
				 sum(a.NEW_DPP) DPP
				 FROM (SELECT
		         bp.ACCT_NUMBER,
		         bp.NAMA_REKENING,
		         bp.NAMA_BANK,
				 sum(floor((bp.DPP+(gh.PERCENTAGE_PPN/100*bp.DPP))-(gh.percentage_pph/100)*bp.DPP)) DPP,
				 (select sum(jbc.credit) new_dpp from JOURNAL_BATCH_PAYMENT jbc where (jbc.NATURE='11121001' or jbc.NATURE='11122001' or jbc.NATURE='11124001') and jbc.no_journal=bp.NO_JOURNAL) NEW_DPP,
		         bp.NO_INVOICE,
		         bp.NO_KONTRAK,
		         bp.NO_JOURNAL,
		         mb.DOMESTIC_BANK_CODE,
		         mb.BIC_RTGS_CODE,
      			 bp.BATCH_NAME
		         FROM BATCH_PAYMENT bp, MASTER_BANK mb, GL_HEADERS gh
		         WHERE 1=1
		         and bp.NAMA_BANK = mb.BANK_NAME
                 and gh.GL_HEADER_ID = bp.GL_HEADER_ID
                 and bp.BATCH_NAME = ?
		         group by bp.NAMA_VENDOR,bp.acct_number,no_journal
		         order by bp.NO_JOURNAL) a
                 WHERE 1=1 $where ";
						
		$query = $this->db->query($sql, $batch_name);
		return $query;
	}

	function get_last_batch_name($batch_query){

		$this->db->select("SUBSTR(batch_name, 3,2) batch_sequence, SUBSTR(no_journal, 1, 2) JOURNAL");
		$this->db->where("SUBSTR(batch_name, 6) = ", $batch_query);
		$this->db->order_by("batch_id","desc");
		$this->db->limit(1);

		$query = $this->db->get($this->tbl_payment);

		return $query->row_array();
	}

	public function get_gl_by_gl_id($gl_header_id){
		$query = "SELECT TGL_INVOICE, NAMA_VENDOR, NO_INVOICE, NO_KONTRAK, DESCRIPTION, NO_FPJP, NAMA_REKENING, NAMA_BANK, BANK_CHARGES, BI_RATE, ACCT_NUMBER, RKAP_NAME, TOP, DUE_DATE, NATURE,
		case when (no_kontrak like '%pph/21/%') then round((DPP+(PERCENTAGE_PPN/100*DPP))-floor((percentage_pph/100)*DPP))
		else
							(select sum(gat.credit)-sum(gat.debet) tes from GL_JOURNAL_AFTER_TAX gat where gat.GL_HEADER_ID = GL_HEADERS.GL_HEADER_ID and (gat.nature='21122001' or gat.nature='21121001')) 
						end DPP,
							 AR_NETTING, AR_INVOICE, AR_DEBIT, AR_DESCRIPTION
							from $this->tbl_gl_header where GL_HEADER_ID = ?";

		return $this->db->query($query, $gl_header_id)->row_array();
	}

	public function get_journal_ar($nama_vendor){

        $this->db->distinct("REFERENCE_2");
		$this->db->select("REFERENCE_2, DEBIT, JOURNAL_DESCRIPTION");
		$this->db->where("REFERENCE_1", $nama_vendor);
		$this->db->where("NATURE = '11212002' AND (STATUS IS NULL OR STATUS = '') ");
		$query = $this->db->get("JOURNAL_AR");

		return $query->result_array();
	}

	public function delete_journal_by_batch($batch_name){
		$this->db->where_in("BATCH_NAME", $batch_name);
		$this->db->delete("JOURNAL_BATCH_PAYMENT");

    	return $this->db->affected_rows();
	}


	public function update_gl_status($batch_name, $status=""){

		foreach ($this->_get_gl_by_batch($batch_name) as $key => $value) {
			$gl_header_id[] = $value['GL_HEADER_ID'];
		}

        $this->db->where_in("GL_HEADER_ID", $gl_header_id);


        $data = array("PAYMENT_STATUS" => $status, "UPDATED_BY" => $this->session->userdata('identity'));

        $this->db->update($this->tbl_gl_header, $data);

    	return $this->db->affected_rows();
	}

	private function _get_gl_by_batch($batch_name){

		$batch_name = array($batch_name);

		$sql = "SELECT GL_HEADER_ID FROM $this->tbl_gl_header
						where GL_HEADER_ID in (SELECT GL_HEADER_ID from $this->tbl_payment where BATCH_NAME IN ?)";
		$query = $this->db->query($sql, $batch_name);

		return $query->result_array();
	}

	public function get_ap_outstanding($date_from="", $date_to="", $filterdateby){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    	= "";
		$filter    	= "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("GH.BATCH_NAME","GH.NO_JOURNAL","GH.NAMA_VENDOR","GH.NO_INVOICE","GH.NO_KONTRAK","GH.NO_FPJP","GH.DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($filterdateby == '1'){
			$filter .= " AND CONVERT(GH.TGL_INVOICE, DATE) BETWEEN ? and ?";
			$filter .= " AND GH.APPROVED_HOU = 'Y' ";
		}elseif($filterdateby == '2'){
			$filter .= " AND CONVERT(GH.APPROVED_HOU_DATE, DATE) BETWEEN ? and ?";
			$filter .= " AND GH.APPROVED_HOU = 'Y' ";
		}else{
			$filter .= " AND GH.APPROVED_HOU = 'm' ";
		}

		$whereArr = array();
		if($date_from != "" && $date_to != ""){
			//$where .= " AND CONVERT(GH.TGL_INVOICE, DATE) BETWEEN ? and ? ";
			$where .= $filter;
			$whereArr[] = $date_from;
			$whereArr[] = $date_to;
		}

		$sql = "SELECT GH.TGL_INVOICE, GH.BATCH_NAME, GH.NO_JOURNAL, GH.NAMA_VENDOR, GH.NO_INVOICE,
					GH.NO_KONTRAK, GH.DESCRIPTION, GH.CURRENCY, 
					(SELECT SUM(CREDIT) FROM GL_JOURNAL_AFTER_TAX WHERE NATURE IN('21122001','21121001') AND JOURNAL_DESCRIPTION=GH.NO_JOURNAL) AP_AMOUNT,
					NO_FPJP, NAMA_REKENING, NAMA_BANK, ACCT_NUMBER, TOP, DUE_DATE, NATURE
				FROM GL_HEADERS GH
				WHERE 1=1
				$where
				and GH.PAYMENT_STATUS <>'RECONCILED'
				GROUP BY GH.NO_JOURNAL
				ORDER BY GH.TGL_INVOICE, GH.NO_JOURNAL";

		$queryData = query_datatable($sql);

		$total = $this->db->query($sql, $whereArr)->num_rows();
		$data  = $this->db->query($queryData, $whereArr)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	public  function get_download_outstanding_ap($date_from="", $date_to="", $filterdateby){

		$where 	= "";
		$filter = "";
		$whereArr = array();

		if($filterdateby == '1'){
			$filter .= " AND CONVERT(GH.TGL_INVOICE, DATE) BETWEEN ? and ?";
			$filter .= " AND GH.APPROVED_HOU = 'Y' ";
		}elseif($filterdateby == '2'){
			$filter .= " AND CONVERT(GH.APPROVED_HOU_DATE, DATE) BETWEEN ? and ?";
			$filter .= " AND GH.APPROVED_HOU = 'Y' ";
		}else{
			$filter .= " AND GH.APPROVED_HOU = 'm' ";
		}

		if($date_from != "" && $date_to != ""){
			$where .= $filter;
			$whereArr[] = $date_from;
			$whereArr[] = $date_to;
		}

		$sql = "SELECT GH.TGL_INVOICE, GH.BATCH_NAME, GH.NO_JOURNAL, GH.NAMA_VENDOR, GH.NO_INVOICE,
					GH.NO_KONTRAK, GH.DESCRIPTION, GH.CURRENCY, 
					(SELECT SUM(CREDIT) FROM GL_JOURNAL_AFTER_TAX WHERE NATURE IN('21122001','21121001') AND JOURNAL_DESCRIPTION=GH.NO_JOURNAL) AP_AMOUNT,
					NO_FPJP, NAMA_REKENING, NAMA_BANK, ACCT_NUMBER, TOP, DUE_DATE, NATURE
				FROM GL_HEADERS GH
				WHERE 1=1
				$where
				and GH.PAYMENT_STATUS <>'RECONCILED'
				GROUP BY GH.NO_JOURNAL
				ORDER BY GH.TGL_INVOICE, GH.NO_JOURNAL";

		$query = $this->db->query($sql, $whereArr);

		return $query;
	}

	public function get_list_payment($date_from, $date_to, $filterdateby, $status){

		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    				= "";
		$filter    				= "";
		$filter_unpayment    	= "";
		if($keywords != ""){
			if(strpos($keywords,".") > 0){
				$string = (int) trim_string($keywords);
				if($string > 0){
					$keywords = $string;
				}
			}
			$fieldToSearch = array("BP.DESCRIPTION","GH.NO_JOURNAL","BP.NO_INVOICE","BP.NO_JOURNAL","BP.NAMA_REKENING");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($filterdateby == '1'){
			$filter = " AND CONVERT(PCB.TRANSACTION_DATE, DATE) BETWEEN ? and ? ";
			$filter .= " AND GH.PAYMENT_STATUS =  ? ";
		}elseif($filterdateby == '2'){
			$filter .= " AND CONVERT(GH.APPROVED_DATE, DATE) BETWEEN ? and ? ";
			$filter .= " AND GH.PAYMENT_STATUS =  ? ";
		}else{
			$filter .= " AND GH.PAYMENT_STATUS =  'zzz' ";
		}

		if($filterdateby == '1'){
			$filter_unpayment = " AND CONVERT(BP.TGL_INVOICE, DATE) BETWEEN ? and ? ";
		}elseif($filterdateby == '2'){
			$filter_unpayment = " AND CONVERT(GH.APPROVED_DATE, DATE) BETWEEN ? and ? ";
		}else{
			$filter_unpayment = " AND GH.PAYMENT_STATUS =  'zzz' ";
		}

		$whereArr[] = $date_from;
		$whereArr[] = $date_to;

		$fieldAdded1 = "";
		$fieldAdded2 = "";
		$joinAdded = "";
		if($status == "RECONCILED"){
			$where      .= " AND BP.NO_JOURNAL = PCB.REFERENCE";
			$where      .= $filter;
			$whereArr[] = $status;
			$fieldAdded1 = " PCB.DEBIT TOTAL_BAYAR, ";
			$fieldAdded2 = " date(PCB.TRANSACTION_DATE) PAID_DATE, ";
			$joinAdded  = " POST_CLEARING_BANK PCB, ";
			$order      = " ORDER BY PCB.TRANSACTION_DATE";
		}else{
			$fieldAdded1 = " '0' TOTAL_BAYAR, ";
			$fieldAdded2 = " '' PAID_DATE, ";
			$where      .= $filter_unpayment;
			$where .= " AND BP.STATUS IS NULL";
			$order = " ORDER BY GH.APPROVED_DATE";
		}

		$mainQuery = "SELECT DISTINCT BP.TGL_INVOICE PERIOD,
							date(GH.APPROVED_DATE) TGL_TERIMA_AP,
							BP.NO_INVOICE, GH.NO_JOURNAL NO_JOURNAL_AP, BP.NO_JOURNAL NO_JOURNAL_TR,
							BP.NAMA_BANK REKENING_PENERIMA, BP.ACCT_NUMBER NO_RK_PENERIMA, BP.NAMA_REKENING NAMA_PENERIMA,
							BP.DPP TOTAL_INVOICE,
							 PCB.DEBIT TOTAL_BAYAR, 
							BP.DESCRIPTION KETERANGAN,
							 date(PCB.TRANSACTION_DATE) PAID_DATE, 
							date(BP.DUE_DATE) DUE_DATE
							FROM
							BATCH_PAYMENT BP,
							$joinAdded
							GL_HEADERS GH
							WHERE BP.GL_HEADER_ID = GH.GL_HEADER_ID  " . $where.
							"  GROUP BY GH.NO_JOURNAL"
							. $order;

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, $whereArr)->num_rows();
		$data  = $this->db->query($queryData, $whereArr)->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}


	public  function get_download_list_payment($date_from, $date_to, $filterdateby, $status){

		$where 				= "";
		$filter 			= "";
		$filter_unpayment 	= "";
		$whereArr = array();

		if($filterdateby == '1'){
			$filter .= " AND CONVERT(PCB.TRANSACTION_DATE, DATE) BETWEEN ? and ? ";
			$filter .= " AND GH.PAYMENT_STATUS =  ? ";
		}elseif($filterdateby == '2'){
			$filter .= " AND CONVERT(GH.APPROVED_DATE, DATE) BETWEEN ? and ? ";
			$filter .= " AND GH.PAYMENT_STATUS =  ? ";
		}else{
			$filter .= " AND GH.PAYMENT_STATUS =  'xxx' ";
		}

		if($filterdateby == '1'){
			$filter_unpayment = " AND CONVERT(BP.TGL_INVOICE, DATE) BETWEEN ? and ? ";
		}elseif($filterdateby == '2'){
			$filter_unpayment = " AND CONVERT(GH.APPROVED_DATE, DATE) BETWEEN ? and ? ";
		}else{
			$filter_unpayment = " GH.PAYMENT_STATUS =  'zzz' ";
		}

		$whereArr[] = $date_from;
		$whereArr[] = $date_to;

		$fieldAdded1 = "";
		$fieldAdded2 = "";
		$joinAdded = "";
		if($status == "RECONCILED"){
			$where      .= " AND BP.NO_JOURNAL = PCB.REFERENCE";
			$where      .= $filter;
			$whereArr[] = $status;
			$fieldAdded1 = " PCB.DEBIT TOTAL_BAYAR, ";
			$fieldAdded2 = " date(PCB.TRANSACTION_DATE) PAID_DATE, ";
			$joinAdded  = " POST_CLEARING_BANK PCB, ";
			$order      = " ORDER BY PCB.TRANSACTION_DATE";
		}else{
			$where .= $filter_unpayment;
			$where .= " AND BP.STATUS IS NULL";
			$order = " ORDER BY GH.APPROVED_DATE";
		}

		$sql = "SELECT DISTINCT BP.TGL_INVOICE PERIOD,
							date(GH.APPROVED_DATE) TGL_TERIMA_AP,
							BP.NO_INVOICE, GH.NO_JOURNAL NO_JOURNAL_AP, BP.NO_JOURNAL NO_JOURNAL_TR,
							BP.NAMA_BANK REKENING_PENERIMA, BP.ACCT_NUMBER NO_RK_PENERIMA, BP.NAMA_REKENING NAMA_PENERIMA,
							BP.DPP TOTAL_INVOICE,
							 PCB.DEBIT TOTAL_BAYAR, 
							BP.DESCRIPTION KETERANGAN,
							 date(PCB.TRANSACTION_DATE) PAID_DATE, 
							date(BP.DUE_DATE) DUE_DATE
							FROM
							BATCH_PAYMENT BP,
							$joinAdded
							GL_HEADERS GH
							WHERE BP.GL_HEADER_ID = GH.GL_HEADER_ID  " . $where.
							"  GROUP BY GH.NO_JOURNAL"
							. $order;
							
		$query = $this->db->query($sql, $whereArr);

		return $query;
	}

}

/* End of file Payment_mdl.php */
/* Location: ./application/models/Payment_mdl.php */