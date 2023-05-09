<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_justifikasi extends CI_Model{

	function get_justifikasi($fb_status,$fb_interface_status,$fb_ucm) 
	{
	
	$inStatus = explode(",",$fb_status);
	$whereIn = "";
	foreach($inStatus as $key => $val){
		$whereIn = ",'".$val."'";
        }
	
        $WHERE = $fb_ucm == "IS_NULL"  ? " AND  FB.UCM_ID IS NULL"  :  ($fb_ucm == "IS_NOT_NULL"  ? "AND  FB.UCM_ID IS NOT NULL" : " AND  FB.UCM_ID=?");


        $query = "SELECT 'IDR' CURRENCY_CODE,DATE(FB.FS_DATE) JOURNAL_ENTRY,'10' SEGMENT1,(SELECT FLEX_VALUE FROM ORACLE_SEGMENT2 OS WHERE OS.PROGRAM = RL.ENTRY_OPTIMIZE) SEGMENT2,
            IFNULL((SELECT FLEX_VALUE FROM ORACLE_SEGMENT3 OS WHERE OS.ID_UNIT = FB.ID_UNIT),'000000') SEGMENT3,IFNULL((SELECT  FLEX_VALUE FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(RL.RKAP_DESCRIPTION) AND SEGMENT='SEGMENT4' AND FLAG = 'Y' limit 1),'00000000') SEGMENT4,
            IFNULL((SELECT FLEX_VALUE FROM ORACLE_COA WHERE SEGMENT='SEGMENT5' and LOWER(VALUE_DESCRIPTION) = LOWER(RL.SUB_PARENT) and FLAG='Y') ,'00000000') SEGMENT5,
            CASE WHEN FB.ID_DIVISION =63 THEN (select flex_value from ORACLE_COA where SEGMENT='SEGMENT6' and upper(value_description)='SYARIAH' AND FLAG = 'Y') ELSE (select flex_value from ORACLE_COA where SEGMENT='SEGMENT6' and upper(value_description)='REGULAR' AND FLAG = 'Y') END SEGMENT6,
	    IFNULL((SELECT FLEX_VALUE FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(RL.TRIBE_USECASE) AND SEGMENT='SEGMENT7' AND FLAG = 'Y'),'00000') SEGMENT7,'00' SEGMENT8,'0000' SEGMENT9,'0000'SEGMENT10,'0000' SEGMENT11,
            BFS.FA_FS ENTERED_DEBIT_AMOUNT,FB.FS_NUMBER REFERENCE4,FB.FS_NAME REFERENCE5,FB.ID_FS JUSTIFICATION_ID,FBL.FS_LINES_NUMBER JUSTIF_LINE_NUMBER,FBL.PROC_TYPE,FBL.SERVICE_PERIOD_START,FBL.SERVICE_PERIOD_END,
            FB.UCM_ID,            
 	    FB.CANCEL_FLAG,       
 	    FB.INTERFACE_STATUS,
            FB.JE_HEADER_ID,
            FB.ERROR_MESSAGE 
        FROM 
            FS_BUDGET_LINES FBL,
            FS_BUDGET FB,
            RKAP_LINE RL,
            BUDGET_FINANCE_STUDY BFS
        WHERE
            FB.ID_FS = FBL.ID_FS
            AND RL.ID_RKAP_LINE = FBL.ID_RKAP_LINE
            AND BFS.ID_FS =FB.ID_FS
            AND BFS.ID_RKAP_LINE = FBL.ID_RKAP_LINE
            AND FA_FS > 0
            AND FB.STATUS IN(".substr($whereIn,1).") AND FB.INTERFACE_STATUS=? ". $WHERE;

      
        $filter = $fb_ucm == "IS_NULL"  ? array($fb_interface_status)  :  ($fb_ucm == "IS_NOT_NULL"  ? array($fb_interface_status) : array($fb_interface_status,$fb_ucm));  
        return $this->db->query($query, $filter );
	}

	function get_fs_budget_where($q) 
	{

	return $this->db->get_where('FS_BUDGET',$q);
    }

	function update_justifikasi($where,$data)
	{
		$this->db->update('FS_BUDGET', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}