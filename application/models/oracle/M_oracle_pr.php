<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_pr extends CI_Model{

	function get_pr($fb_status,$fb_interface_status, $coa_review) 
	{
        
        $query = "SELECT distinct fb.FS_NAME justif_name,ph.PR_DATE,
                  IFNULL((SELECT PIC_EMAIL FROM MASTER_APPROVAL WHERE ID_APPROVAL = (SELECT ID_APPROVAL FROM TRX_APPROVAL_PR WHERE PR_HEADER_ID = ph.PR_HEADER_ID AND IS_ACTIVE = 1 order by UPDATED_DATE DESC LIMIT 1 ) LIMIT 1) , CONCAT(ph.UPDATED_BY, '@linkaja.id')) approver,
                  IFNULL((SELECT EMAIL FROM MASTER_USER WHERE DISPLAY_NAME = ph.SUBMITTER ), CONCAT(ph.CREATED_BY, '@linkaja.id')) entered_by,
                  fb.FS_NUMBER attribute1,fb.ID_FS attribute2,ph.PR_HEADER_ID attribute3,ph.PR_NUMBER attribute4,
        ph.PR_NAME attribute5,rl.CAPEX_OPEX attribute6, (SELECT PROC_TYPE FROM FS_BUDGET_LINES WHERE ID_FS = ph.ID_FS LIMIT 1) as attribute7,ph.PR_NAME description,ph.DELIVERY_LOCATION,
        CASE
          WHEN pd.PR_DETAIL_NAME = pd.PR_DETAIL_DESC THEN pd.PR_DETAIL_NAME
          WHEN pd.PR_DETAIL_NAME != '' and PR_DETAIL_NAME IS NOT NULL
            THEN  
              CASE  WHEN pd.PR_DETAIL_DESC != '' and pd.PR_DETAIL_DESC IS NOT NULL
                THEN CONCAT(pd.PR_DETAIL_NAME,' - ', pd.PR_DETAIL_DESC)
                end
        END item_desc,
        pd.CATEGORY_ITEM item, IFNULL(UPPER(GOODS_SERVICES),'') LINES_TYPE, pd.QUANTITY,ph.CURRENCY,pd.PRICE,
        case when ph.CURRENCY_RATE=0 then 1 else ph.CURRENCY_RATE end  conversion_rate,ph.DELIVERY_DATE date,pd.UOM,'10' SEGMENT1,(SELECT FLEX_VALUE FROM ORACLE_SEGMENT2 OS WHERE OS.PROGRAM = rl.ENTRY_OPTIMIZE) SEGMENT2,
        ifnull((SELECT FLEX_VALUE FROM ORACLE_SEGMENT3 OS WHERE OS.ID_UNIT = ph.ID_UNIT),'000000') SEGMENT3,ifnull((SELECT  FLEX_VALUE FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(rl.RKAP_DESCRIPTION) AND SEGMENT='SEGMENT4' AND FLAG = 'Y' LIMIT 1),'00000000') SEGMENT4,
        IFNULL((SELECT FLEX_VALUE FROM ORACLE_SEGMENT5 WHERE ID_MASTER_COA = pd.ID_MASTER_COA) ,'00000000') SEGMENT5,
  CASE WHEN ph.ID_DIVISION =63 THEN (select flex_value from ORACLE_COA where SEGMENT='SEGMENT6' and upper(value_description)='SYARIAH' AND FLAG = 'Y') ELSE (select flex_value from ORACLE_COA where SEGMENT='SEGMENT6' and upper(value_description)='REGULAR' AND FLAG = 'Y') END SEGMENT6,
        IFNULL((SELECT FLEX_VALUE FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(rl.TRIBE_USECASE) AND SEGMENT='SEGMENT7' AND FLAG = 'Y'),'00000') SEGMENT7,'00' SEGMENT8,'0000' SEGMENT9,'0000'SEGMENT10,'0000' SEGMENT11,
        ph.STATUS pr_status,'FILE' TYPE,ph.DOCUMENT_UPLOAD FILE_NAME,ph.DOCUMENT_ATTACHMENT FILE_CONTENTS_ATTACHMENT,ph.DOCUMENT_UPLOAD FILE_CONTENTS_UPLOAD,ph.PO_BUYER, rl.MONTH RKAP_PERIOD
        from 
          FS_BUDGET  fb,
          PR_HEADER  ph,
          PR_LINES  pl,
          PR_DETAIL pd,
          RKAP_LINE rl
        where
          ph.PR_HEADER_ID = pl.PR_HEADER_ID
          and pd.PR_LINES_ID = pl.PR_LINES_ID
          and pl.IS_SHOW = 1
          and ph.ID_FS = fb.ID_FS
          and pl.ID_RKAP_LINE = rl.ID_RKAP_LINE
          AND ph.STATUS=? AND ph.INTERFACE_STATUS=? AND ph.COA_REVIEW=? AND ph.STATUS_ASSIGN = 'Y'";

        return $this->db->query($query, array($fb_status,$fb_interface_status,$coa_review));
	}

	function get_pr_header_where($q) 
	{
	    return $this->db->get_where('PR_HEADER',$q);
    }

	function update_pr($where,$data)
	{
		$this->db->update('PR_HEADER', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}