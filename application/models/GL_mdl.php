<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class GL_mdl extends CI_Model {

	protected	$table_gl_header = "GL_HEADERS",
				$table_before_tax = "GL_JOURNAL_B_TAX",
				$view_gl          = "V_JURNAL_B_TAX",
				$tbl_fpjp_header  = "FPJP_HEADER";


	function get_all_gl(){



		$this->db->select('*');

		$this->db->from($this->table_gl_header);

		$this->db->order_by("GL_HEADER_ID", "desc"); 

		$query = $this->db->get();

		if ( $query->num_rows() > 0 )

		{

			return $query->result_array();

		}

	}

	public function get_all_vendor()
	{
		$mainQuery	        = "SELECT DISTINCT NAMA_VENDOR
		FROM $this->table_gl_header
		WHERE NAMA_VENDOR != ''
		order by NAMA_VENDOR asc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function get_master_vendor()
	{
		$mainQuery	        = "SELECT DISTINCT NAMA_VENDOR
		FROM $this->table_gl_header where VALIDATED = 'Y' 
		order by NAMA_VENDOR desc";
		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}

	public function get_master_bank()
	{
		$param_vendor_name  = $this->input->post('param_vendor_name');
		$where = "";

		if($param_vendor_name):
			$where = " AND NAMA_VENDOR = '$param_vendor_name'";
		endif;


		$mainQuery	        = "SELECT DISTINCT NAMA_BANK
		FROM $this->table_gl_header where VALIDATED = 'Y' ". $where ."
		order by NAMA_VENDOR desc";

		// echo $mainQuery; die();

		$query 		        = $this->db->query($mainQuery);
		$result['query']	= $query;
		return $result;		
	}


	function get_gl_header_datatable($invoice_date_from,$invoice_date_to,$vendor_name)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("TGL_INVOICE","BATCH_NAME","NO_JOURNAL","NAMA_VENDOR","NO_INVOICE","NO_KONTRAK","DESCRIPTION","DPP","NO_FPJP","NAMA_REKENING","NAMA_BANK","ACCT_NUMBER","NATURE","TOP");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		$mainQuery = "SELECT NO_JOURNAL,NO_FPJP,IS_BAST,IS_VATSA,DPP,DENDA,MATERAI,GL_HEADER_ID,TGL_INVOICE,INVOICE_DATE,GL_HEADER_ID,BATCH_NAME,NAMA_VENDOR,NO_INVOICE,NO_KONTRAK,DESCRIPTION,CURRENCY,KURS,NOMINAL_RATE,NAMA_REKENING,NAMA_BANK,ACCT_NUMBER,RKAP_NAME,TOP,DUE_DATE,NATURE,APPROVED_INVOICE,VALIDATED,VERIFICATED

		FROM $this->table_gl_header

		where 1=1

		and CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? 
		$where order by GL_HEADER_ID";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($invoice_date_from, $invoice_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($invoice_date_from, $invoice_date_to))->result_array();

		$result['data']       = $data;

		$result['total_data'] = $total;

		return $result;		

	}

	function get_journal_before_tax_datatable($invoice_date_from,$invoice_date_to,$vendor_name)

	{
		$keywords = (isset($_POST['search'])) ? $_POST['search']['value'] : "";
		$filterBy = (isset($_POST['filter'])) ? $_POST['filter']: "";

		$where    = "";
		if($keywords != ""){
			$fieldToSearch = array("TGL_INVOICE","DUE_DATE","BATCH_NAME","BATCH_DESCRIPTION","NAMA_VENDOR","JOURNAL_NAME","NO_INVOICE","NO_KONTRAK","ACCOUNT_DESCRIPTION","NATURE","CURRENCY","DEBET","CREDIT","DESCRIPTION");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		if($keywords != ""){
			$q = strtolower($keywords);

			$where = " and (TGL_INVOICE like '%".$q."%')
			or (DUE_DATE like '%".$q."%') 
			or (BATCH_NAME like '%".$q."%') 
			or (BATCH_DESCRIPTION like '%".$q."%')
			or (NAMA_VENDOR like '%".$q."%') 
			or (JOURNAL_NAME like '%".$q."%') 
			or (NO_INVOICE like '%".$q."%') 
			or (NO_KONTRAK like '%".$q."%') 
			or (ACCOUNT_DESCRIPTION like '%".$q."%')   
			or (NATURE like '%".$q."%')
			or (CURRENCY like '%".$q."%') 
			or (DEBET like '%".$q."%') 
			or (CREDIT like '%".$q."%') 
			or (DESCRIPTION like '%".$q."%')";
		}

		$mainQuery = "SELECT *

		FROM $this->view_gl

		where 1=1

		and CONVERT(TGL_INVOICE, DATE) BETWEEN ? and ? 
		$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($invoice_date_from, $invoice_date_to))->num_rows();
		$data  = $this->db->query($queryData, array($invoice_date_from, $invoice_date_to))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;



		return $result;	

	}

	function get_batch_name_master(){

		$query = $this->db->query('SELECT DISTINCT BATCH_NAME FROM GL_HEADERS');

		return $query->result_array();

	}

	public function insert_gl_header_import($newData)
	{
		$last_invoice = "";
		$last_kontrak = "";




		foreach ($newData as $row)
		{
			$no_invoice   = $row['NO_INVOICE'];
			$no_kontrak   = $row['NO_KONTRAK'];

			if($last_invoice == $no_invoice && $last_kontrak == $no_kontrak){

				$getData      = $this->crud->read("GL_HEADERS", array("NO_INVOICE" => $no_invoice, "NO_KONTRAK" => $no_kontrak));
				$gl_header_id = array();

				if($getData){
				// echo "Data before delete ".count($getData);
					foreach ($getData as $value) {
						$gl_header_id = $value['GL_HEADER_ID'];
						$this->crud->delete("GL_HEADERS", array("GL_HEADER_ID" => $gl_header_id));
					}
				}

				$getData2  = $this->crud->read("GL_HEADERS", array("NO_INVOICE" => $no_invoice, "NO_KONTRAK" => $no_kontrak));
				if($getData2){
					// echo "Data after delete ".count($getData2);
				}
			}
			else{

			}

			$last_invoice = $no_invoice;
			$last_kontrak = $no_kontrak;
		}

		$allNewData = array();
		foreach ($newData as $row)
		{

            $paramKey = array_keys($row);
            for ($i=0; $i < count($paramKey); $i++) {
        		$checkField[$paramKey[$i]] = $row[$paramKey[$i]];
            }

    		$check_exist[] = $this->crud->check_exist($this->table_gl_header, $checkField);

    		/*if($check_exist == 0){
    			$allNewData[] = $row;
    		}
    		else{
    			echo $this->db->last_query();
    			die;
    		}*/
		}

        $insert = $this->db->insert_batch($this->table_gl_header, $newData);


        if($insert){
            return true;
        }

	}

	public function insert_gl_header_import_old($newData)
	{
		// foreach ($data as $row)
		// {
		// 	$nojurnal = json_encode($row['NO_JOURNAL']);
		// 	$sql = $this->db->insert_string($this->table_gl_header, $row) . " ON DUPLICATE KEY UPDATE NO_JOURNAL= ".$nojurnal." ";
		// 	$this->db->query($sql);

		// }


		// return true;

		foreach ($newData as $row)
		{


            $paramKey = array_keys($row);

            for ($i=0; $i < count($paramKey); $i++) {
            	$onUpdate[] = $paramKey[$i] ." = '".$row[$paramKey[$i]]."'";
            }

   			// echo implode(",", $onUpdate);

			// die;

			$nojurnal = $row['NO_JOURNAL'];
			$sql = $this->db->insert_string($this->table_gl_header, $row) . " ON DUPLICATE KEY UPDATE " .implode(", ", $onUpdate);
			$this->db->query($sql);

		}

		return true;

	}

	public function call_prc_jurnal_headers()

	{
		$sql = "CALL JURNAL_HEADERS";

		$this->db->query($sql);

		return true;

	}

	public function call_procedure()

	{
		$sql = "CALL Journal_B_Tax";

		$this->db->query($sql);

		return true;

	}

	public function call_upload_batch()

	{
		$sql = "CALL UPLOAD_BATCH";

		$this->db->query($sql);

		return true;

	}


	function get_download_gl_header($date_from,$date_to,$vendor_name)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where = "";

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT * from $this->table_gl_header where CONVERT(TGL_INVOICE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to'  $where  order by GL_HEADER_ID";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	function get_download_before_tax($date_from,$date_to,$vendor_name)
	{

		$vdate_from = $date_from;
		$vdate_to = $date_to;

		$where = "";

		if($vendor_name)
		{
			$where .= " AND NAMA_VENDOR = '".$vendor_name."' ";
		}

		$exp_date_from = explode("/", $vdate_from);
		$exp_date_to   = explode("/", $vdate_to);

		$invoice_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		$invoice_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];

		$queryExec = " SELECT * from $this->view_gl where CONVERT(TGL_INVOICE, DATE) BETWEEN '$invoice_date_from' and '$invoice_date_to'   $where  ";

		$query     = $this->db->query($queryExec);

		return $query;
	}

	function get_cek_data($no_po){

		$sql = " select VENDOR_NAME from PO_LINES where PO_NUMBER = ?";
		$query = $this->db->query($sql, $no_po);

		return $query->row_array();
	}

	function get_cek_dpp($no_kontrak){

		$sql = "select sum(PO_LINE_AMOUNT) PO_AMOUNT from PO_LINES where PO_NUMBER = '$no_kontrak' group by PO_NUMBER ";

		$query = $this->db->query($sql)->row_array();
		return $query;
	}

	function get_cek_fpjp($no_fpjp){

		$sql = "select sum(FPJP_AMOUNT) AMOUNT_FPJP from FPJP_HEADER where FPJP_NUMBER = '$no_fpjp' group by FPJP_AMOUNT ";

		$query = $this->db->query($sql)->row_array();
		return $query;
	}


    public function check_exist_on_after($no_journal){

    	$mainQuery = "select DISTINCT GL_HEADER_ID from JOURNAL_AFTER_TAX where JOURNAL_NAME = '".$no_journal."'  AND ( VALIDATED ='Y' OR VERIFICATED = 'Y' OR  APPROVED = 'Y')";
    	$queryData = query_datatable($mainQuery);

    	$total = $this->db->query($mainQuery)->num_rows();
		$data  = $this->db->query($queryData)->result_array();
		$result['total_data'] = $total;

		return $result;
    }



    public function call_procedure_update_before_tax()

    {
    	$sql = "CALL Journal_B_Tax";

    	$this->db->query($sql);

    	return true;

    }



    function update_data_upload_invoice($data, $no_journals)
    {
    	$this->db->where('NO_JOURNAL', $no_journals);
    	$this->db->update($this->table_gl_header, $data);
    	return true;
    }


    function delete_data_before_tax($journal_description)
    {
    	$this->db->where('JOURNAL_DESCRIPTION ', $journal_description);
    	$this->db->delete($this->table_before_tax);
    	return true;
    }


    function approve_data_upload_invoice($data, $journal_description)
    {
    	$this->db->where('NO_JOURNAL', $journal_description);
    	$this->db->update($this->table_gl_header, $data);
    	return true;
    }

    function delete_data_upload_invoice($journal_description)
    {
    	$this->db->where('NO_JOURNAL', $journal_description);
    	$this->db->delete($this->table_gl_header);
    	return true;
    }

    function getsubmitter_nokontrak($no_kontrak)
    {
    	$sql = "SELECT PRH.ID_UNIT, PRH.SUBMITTER, POL.PO_NUMBER, MA.PIC_NAME, MA.PIC_EMAIL FROM PO_LINES POL INNER JOIN PO_HEADER POH ON POL.PO_HEADER_ID = POH.PO_HEADER_ID INNER JOIN PR_HEADER PRH ON POH.ID_PR_HEADER_ID = PRH.PR_HEADER_ID INNER JOIN MASTER_APPROVAL MA ON PRH.ID_UNIT = MA.ID_UNIT WHERE PRH.SUBMITTER !='' AND PRH.SUBMITTER != '0' AND POL.PO_NUMBER = ? AND IS_EXIST = 1 LIMIT 1 ";		

		$query = $this->db->query($sql,$no_kontrak)->row_array();
		// echo $this->db->last_query(); die;
		return $query;
    }

    function getsubmitter_nofpjp($no_fpjp)
    {
    	$sql = "SELECT FH.ID_UNIT, FH.SUBMITTER, MA.PIC_NAME, MA.PIC_EMAIL 
    	FROM FPJP_HEADER FH INNER JOIN MASTER_APPROVAL MA ON FH.ID_UNIT = MA.ID_UNIT where FH.FPJP_NUMBER = ? AND IS_EXIST = 1 GROUP BY SUBMITTER";		

		$query = $this->db->query($sql,$no_fpjp)->row_array();
		// echo $this->db->last_query(); die;
		return $query;
    }

    //coment heri

	/*function get_last_journal($batch_name){

		$this->db->select("SUBSTR(no_journal, 1, 2) JOURNAL");
		$this->db->where("batch_name", $batch_name);
		$this->db->order_by("gl_header_id","desc");
		$this->db->limit(1);

		$query = $this->db->get($this->table_gl_header);

		return $query->row_array();
	}*/

	//add heri 19/03/2020

	function get_last_journal($batch_name){

		$sql = "select MAX((SUBSTR(NO_JOURNAL,1,2))) JOURNAL from GL_HEADERS where BATCH_NAME = ? ";

		$query = $this->db->query($sql, $batch_name);

		return $query->row_array();
	}

	function get_fpjp_to_invoice($id_fpjp){

		$sql = "SELECT FH.ID_UNIT, FH.ID_FS, FH.FPJP_NUMBER, FH.NO_INVOICE, FH.INVOICE_DATE,
					FH.FPJP_NAME, FH.CURRENCY, FH.FPJP_AMOUNT, FH.SUBMITTER, FH.FPJP_VENDOR_NAME,
					FL.FPJP_LINE_NAME, FL.PEMILIK_REKENING, FL.NAMA_BANK, FL.NO_REKENING, FL.ORIGINAL_AMOUNT,
					FD.QUANTITY, FD.FPJP_DETAIL_DESC, SUM(FD.PRICE) PRICE, SUM(FD.FPJP_DETAIL_AMOUNT) FPJP_DETAIL_AMOUNT, FD.TAX, MC.NATURE, MC.DESCRIPTION
					FROM FPJP_HEADER FH
					INNER JOIN FPJP_LINES FL ON FH.FPJP_HEADER_ID = FL.FPJP_HEADER_ID
					INNER JOIN FPJP_DETAIL FD ON FL.FPJP_LINES_ID = FD.FPJP_LINES_ID
					LEFT JOIN MASTER_COA MC ON FD.ID_MASTER_COA = MC.ID_MASTER_COA
					WHERE  1=1 AND FH.FPJP_HEADER_ID = ?
					AND FPJP_DETAIL_AMOUNT > 0
					GROUP BY FD.FPJP_DETAIL_DESC
					ORDER BY FPJP_DETAIL_ID";

		$query = $this->db->query($sql, $id_fpjp);

		return $query->result_array();
	}

	function get_gr_to_invoice($id_gr){

		$sql = "SELECT GR.ID_UNIT, GR.ID_FS, GR.GR_NUMBER, GR.PO_NUMBER, GR.NO_BAST, GL.NO_INVOICE,
				GL.INVOICE_DATE, POH.CURRENCY, POH.PO_AMOUNT, GR.SUBMITTER, GL.ITEM_NAME,
				POL.VENDOR_NAME, POL.VENDOR_BANK_NAME,POL.VENDOR_BANK_ACCOUNT, POL.VENDOR_BANK_ACCOUNT_NAME,
				GL.QUANTITY, GL.ITEM_PRICE, GL.TOTAL_PRICE, MC.NATURE, MC.DESCRIPTION
				FROM GR_HEADER GR
				INNER JOIN GR_LINE GL ON GR.GR_HEADER_ID = GL.GR_HEADER_ID
				INNER JOIN PO_LINES POL ON POL.PO_NUMBER = GR.PO_NUMBER
				INNER JOIN PO_HEADER POH ON POH.PO_HEADER_ID = POL.PO_HEADER_ID
				INNER JOIN PO_DETAIL POD ON POD.PO_LINE_ID = POL.PO_LINE_ID
				INNER JOIN PR_DETAIL PRD ON PRD.PR_DETAIL_ID = POD.PR_DETAIL_ID
				LEFT JOIN MASTER_COA MC ON PRD.ID_MASTER_COA = MC.ID_MASTER_COA
				WHERE  1=1 AND GR.GR_HEADER_ID = ?
				AND (GL.NO_INVOICE != '' OR GL.NO_INVOICE IS NOT NULL)
				GROUP BY GR_LINE_ID
				ORDER BY GR_LINE_ID";

		$query = $this->db->query($sql, $id_gr);

		return $query->result_array();
	}

	function get_latest_no_journal_by_batc($batch_name){

		$sql = "SELECT DISTINCT NO_JOURNAL FROM GL_HEADERS WHERE BATCH_NAME = ? order by NO_JOURNAL DESC LIMIT 1";
		$query = $this->db->query($sql, $batch_name);

		return $query->row_array();
	}

	function get_journal_by_no_journal($no_journal){

		$sql = "SELECT TGL_INVOICE, BATCH_NAME, NO_JOURNAL, NO_INVOICE,
					DESCRIPTION, DPP, NO_FPJP, NATURE
					FROM GL_HEADERS
					WHERE NO_JOURNAL = ?";
		$query = $this->db->query($sql, $no_journal);

		return $query->result_array();
	}

	function get_all_fpjp_in_gl(){
		$this->db->where("STATUS", 'approved');
		$this->db->where("FPJP_NUMBER IN (SELECT DISTINCT NO_FPJP FROM $this->table_gl_header)");

		$query = $this->db->get($this->tbl_fpjp_header);

		return $query->result_array();
	}

	

	function get_all_gr_in_gl(){
		$sql = "
					SELECT PO_NUMBER, GRL.GR_HEADER_ID, ITEM_NAME, ITEM_DESCRIPTION, QUANTITY, NO_INVOICE, INVOICE_DATE, ITEM_PRICE, TOTAL_PRICE, GR_DOCUMENT
						FROM GR_LINE GRL
					JOIN GR_HEADER GR ON GR.GR_HEADER_ID = GRL.GR_HEADER_ID
					WHERE PO_NUMBER IN (SELECT NO_KONTRAK FROM GL_HEADERS WHERE NO_INVOICE = GRL.NO_INVOICE)";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

}



/* End of file GL_mdl.php */

/* Location: ./application/models/GL_mdl.php */