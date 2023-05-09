<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_oracle_fpjp extends CI_Model{

	function get_fpjp($fb_status,$fb_interface_status) 
	{

    $query = "SELECT FH.FPJP_HEADER_ID,FH.NO_INVOICE, FH.CURRENCY, 
                    case when FH.CURRENCY_RATE=0 then 1 else FH.CURRENCY_RATE end CURRENCY_RATE,
                    case when FD.TAX = 10 and FD.FPJP_DETAIL_AMOUNT = FH.FPJP_AMOUNT
                      then FPJP_DETAIL_AMOUNT + (FD.FPJP_DETAIL_AMOUNT*0.1)
                     when FD.TAX = 11 and FD.FPJP_DETAIL_AMOUNT = FH.FPJP_AMOUNT
                      then FPJP_DETAIL_AMOUNT + (FD.FPJP_DETAIL_AMOUNT*0.11)
                     when FD.TAX = 1 and FD.FPJP_DETAIL_AMOUNT = FH.FPJP_AMOUNT
                      then FPJP_DETAIL_AMOUNT + (FD.FPJP_DETAIL_AMOUNT*0.01)
                     else FH.FPJP_AMOUNT
                     end FPJP_AMOUNT,
                    FH.INVOICE_DATE,
                    IFNULL(FH.FPJP_VENDOR_NAME, FL.PEMILIK_REKENING) SUPPLIER,
                    IFNULL( (select vendor_site_code from ORACLE_SUPPLIER where VENDOR_SITE_CODE = FH.FPJP_SITE_CODE limit 1),
                    (select vendor_site_code from ORACLE_SUPPLIER where LOWER(vendor_name)= LOWER(ifnull(FH.FPJP_VENDOR_NAME,FL.PEMILIK_REKENING))  LIMIT 1)) SUPPLIER_SITE,
                    FL.FPJP_LINE_NAME DESCRIPTION, '' ACCOUNTING_DATE, FH.FPJP_NUMBER, FH.FPJP_NAME, FB.FS_NUMBER JUSTIF_NUMBER,
                    (SELECT FPJP_NAME FROM MASTER_FPJP WHERE ID_MASTER_FPJP = FH.ID_MASTER_FPJP) FPJP_TYPE,
                    IFNULL(FH.FPJP_ACC_NUMBER, FL.NO_REKENING) SUPPLIER_BANK_ACCOUNT,
                    IFNULL(FH.FPJP_BANK_NAME, FL.NAMA_BANK) SUPPLIER_BANK_NAME,
                    IFNULL(FH.FPJP_ACC_NAME, FL.PEMILIK_REKENING) SUPPLIER_REKENING,
                    FD.TAX INCLUDE_TAX, FH.NOTES_USER FPJP_NOTE, FD.FPJP_DETAIL_NUMBER LINE_NUMBER, FD.FPJP_DETAIL_DESC, FD.FPJP_DETAIL_AMOUNT,
                    '10' SEGMENT1, IFNULL((SELECT FLEX_VALUE FROM ORACLE_SEGMENT2 OS WHERE OS.PROGRAM = RL.ENTRY_OPTIMIZE),'00000') SEGMENT2,
                    IFNULL((SELECT FLEX_VALUE FROM ORACLE_SEGMENT3 OS WHERE  UNIT_NAME = RL.UNIT limit 1), 
                        IFNULL((SELECT FLEX_VALUE FROM ORACLE_SEGMENT3 OS WHERE OS.ID_UNIT = FH.ID_UNIT) ,'000000')) SEGMENT3,
                    IFNULL((SELECT  FLEX_VALUE FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(RL.RKAP_DESCRIPTION) AND SEGMENT='SEGMENT4' AND FLAG = 'Y' LIMIT 1),'00000000') SEGMENT4,
                    IFNULL((SELECT FLEX_VALUE FROM ORACLE_SEGMENT5 WHERE ID_MASTER_COA = FD.ID_MASTER_COA ) ,'00000000') SEGMENT5,
                    IFNULL( FD.SEGMENT_PRODUCT ,
                    CASE WHEN FB.ID_DIVISION = 63 THEN
                                                  (select flex_value from ORACLE_COA where SEGMENT='SEGMENT6' and upper(value_description)='SYARIAH' AND FLAG = 'Y')
                      ELSE (select flex_value from ORACLE_COA where SEGMENT='SEGMENT6' and upper(value_description)='REGULAR' AND FLAG = 'Y') END )SEGMENT6,
                    IFNULL( FD.SEGMENT_TRIBE ,
                    IFNULL((SELECT FLEX_VALUE FROM ORACLE_COA WHERE LOWER(VALUE_DESCRIPTION) = LOWER(RL.TRIBE_USECASE) AND SEGMENT='SEGMENT7' AND FLAG = 'Y'),'00000')) SEGMENT7,
                    '00' SEGMENT8,'0000' SEGMENT9, '0000'SEGMENT10, '0000' SEGMENT11,
                    FL.FPJP_LINE_NAME LINE_DESCRIPTION, 'FILE' TYPE, FH.DOCUMENT_UPLOAD FILE_NAME, FH.DOCUMENT_ATTACHMENT FILE_CONTENTS_ATTACHMENT,
                    FH.DOCUMENT_UPLOAD FILE_CONTENTS_UPLOAD, IFNULL((SELECT EMAIL FROM MASTER_USER WHERE USERNAME = FH.CREATED_BY ) ,'NONE')  FPJP_SUBMITED, FH.INVOICE_ID, FH.RETURN_FLAG, FH.INTERFACE_STATUS, FH.ERROR_MEASSAGE, FH.COA_REVIEW, RL.MONTH RKAP_PERIOD
                    FROM FPJP_HEADER FH
                    JOIN FPJP_LINES FL ON  FH.FPJP_HEADER_ID = FL.FPJP_HEADER_ID
                    JOIN FPJP_DETAIL FD ON FL.FPJP_LINES_ID = FD.FPJP_LINES_ID
                    LEFT JOIN FS_BUDGET FB ON FB.ID_FS = FH.ID_FS
                    LEFT JOIN RKAP_LINE RL ON FL.ID_RKAP_LINE = RL.ID_RKAP_LINE
                    WHERE FH.COA_REVIEW= 'Y' AND lower(FH.STATUS)=? AND FH.INTERFACE_STATUS=?";

        return $this->db->query($query, array(strtolower($fb_status), $fb_interface_status) );
	}

	function get_fpjp_header_where($q) 
	{
	    return $this->db->get_where('FPJP_HEADER',$q);
    	}

	function update_fpjp($where,$data)
	{
		$this->db->update('FPJP_HEADER', $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
}
