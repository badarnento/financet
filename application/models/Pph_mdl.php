<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pph_mdl extends CI_Model {

	protected 	$tbl_status_po 	= "REPORT_STATUS_PO";

	public function get_pph23($year, $month){
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
			$fieldToSearch = array("NO_INVOICE");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select DATE_FORMAT(gh.INVOICE_DATE, '%m') MASA,
						DATE_FORMAT(gh.INVOICE_DATE, '%Y') TAHUN_PAJAK,
						gh.INVOICE_DATE,
						gh.NO_INVOICE,
						case when
						gh.NPWP != '' then 'Y'
						else 'N' end BER_NPWP,
						gh.NPWP,
						case when
						gh.NPWP != '' then ''
						else '' end NIK,
						'' TELP,
						mp.KODE_OBJEK_PAJAK,
						'Y' BP_PENGURUS,
						gh.DPP,
						case when
						mvt.SKB_PPH23 != '' then 'SKB'
						when mvt.S_KET_DTP != '' then 'DTP'
						else 'N' end FASILITAS,
						mvt.SKB_PPH23,
						mvt.S_KET_DTP,
						mvt.KTP,
						'' NTPN_DPT
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt
						on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.KAP = 411124
						$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_exist_year(){

		$query = $this->db->query('SELECT DISTINCT DATE_FORMAT(TGL_INVOICE, "%Y") YEAR FROM GL_HEADERS');

		return $query->result_array();

	}

	function cetak_pph23($year, $month){

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$sql = " select DATE_FORMAT(gh.INVOICE_DATE, '%m') MASA,
						DATE_FORMAT(gh.INVOICE_DATE, '%Y') TAHUN_PAJAK,
						gh.INVOICE_DATE,
						gh.NO_INVOICE,
						case when
						gh.NPWP != '' then 'Y'
						else 'N' end BER_NPWP,
						gh.NPWP,
						case when
						gh.NPWP != '' then ''
						else '' end NIK,
						'' TELP,
						mp.KODE_OBJEK_PAJAK,
						'' PENANDA_TANGAN,
						'Y' BP_PENGURUS,
						gh.DPP,
						case when
						mvt.SKB_PPH23 != '' then 'SKB'
						when mvt.S_KET_DTP != '' then 'DTP'
						else 'N' end FASILITAS,
						mvt.SKB_PPH23,
						mvt.S_KET_DTP,
						mvt.KTP,
						'' NTPN_DPT
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt
						on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.KAP = 411124 ";

		$query = $this->db->query($sql, array($year, $bulan));

		return $query;
	}

	public function get_pph26($year, $month){
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
			$fieldToSearch = array("gh.NO_INVOICE","mvt.NAMA_VENDOR","mvt.ALAMAT","mvt.KODE_NEGARA");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select DATE_FORMAT(gh.INVOICE_DATE, '%m') MASA,
						DATE_FORMAT(gh.INVOICE_DATE, '%Y') TAHUN_PAJAK,
						gh.INVOICE_DATE,
						gh.NO_INVOICE,
						'' TIN,
						mvt.NAMA_VENDOR,
						'' TANGGAL_LAHIR,
						mvt.ALAMAT,
						'' NO_PASPOR,
						'' NO_KITAS_WP,
						mp.KODE_OBJEK_PAJAK,
						'Y' PENANDA_TANGAN,
						gh.DPP,
						'100' PENGHASILAN_NETO,
						case when
						mvt.SKD != '' then 'SKD'
						when mvt.S_KET_DTP != '' then 'DTP'
						else 'N' end FASILITAS,
						mvt.SKD,
						mvt.TIN,
						mvt.KODE_NEGARA,
						'' TARIF_SKD,
						mvt.S_KET_DTP,
						'' NTPN_DTP
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt
						on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.KAP = '411127'
						$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function cetak_pph26($year, $month){

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$sql = " select DATE_FORMAT(gh.INVOICE_DATE, '%m') MASA,
						DATE_FORMAT(gh.INVOICE_DATE, '%Y') TAHUN_PAJAK,
						gh.INVOICE_DATE,
						gh.NO_INVOICE,
						'' TIN,
						mvt.NAMA_VENDOR,
						'' TANGGAL_LAHIR,
						mvt.ALAMAT,
						'' NO_PASPOR,
						'' NO_KITAS_WP,
						'' KODE_NEGARA,
						mp.KODE_OBJEK_PAJAK,
						'Y' PENANDA_TANGAN,
						gh.DPP,
						'100' PENGHASILAN_NETO,
						case when
						mvt.SKD != '' then 'SKD'
						when mvt.S_KET_DTP != '' then 'DTP'
						else 'N' end FASILITAS,
						mvt.SKD,
						mvt.TIN,
						mvt.KODE_NEGARA,
						'' TARIF_SKD,
						mvt.S_KET_DTP,
						'' NTPN_DTP
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt
						on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.KAP = '411127' ";

		$query = $this->db->query($sql, array($year, $bulan));

		return $query;
	}

	public function get_pph42($year, $month){
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
			$fieldToSearch = array("mvt.NOMOR_NPWP","mvt.NAMA_VENDOR","mvt.ALAMAT","mp.NAMA_OBJEK_PAJAK","gh.FAKTUR_PAJAK","gh.LOKASI_SEWA");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						mp.NAMA_OBJEK_PAJAK NOP,
						ifnull(mvt.DOMISILI,'') DOMISILI,
						gh.INVOICE_DATE,
						gh.FAKTUR_PAJAK,
						gh.DPP,
						gh.LOKASI_SEWA,
						mp.PERCENTAGE,
						(select NPWP_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NPWP_PEMOTONG,
						(select NAMA_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NAMA_PEMOTONG
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ? 
						and mp.GL_ACCOUNT = '21320001'
						$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function cetak_pph42($year, $month){

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$sql = " select mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						mp.NAMA_OBJEK_PAJAK NOP,
						ifnull(mvt.DOMISILI,'') DOMISILI,
						gh.INVOICE_DATE,
						gh.FAKTUR_PAJAK,
						gh.DPP,
						gh.LOKASI_SEWA,
						mp.PERCENTAGE,
						(select NPWP_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NPWP_PEMOTONG,
						(select NAMA_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NAMA_PEMOTONG
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ? 
						and mp.GL_ACCOUNT = '21320001' ";

		$query = $this->db->query($sql, array($year, $bulan));

		return $query;
	}

	public function get_cekpph42($year, $month)
	{
		$bulan = "";
		if($month < 10){
			$bulan = "0".$month;
		}else{
			$bulan = $month;
		}

		$mainQuery  = "select count(*) LAST_NUM from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.GL_ACCOUNT = '21320001'";

		return $this->db->query($mainQuery, array($year, $bulan))->row();
	}

	public function get_pph42jk($year, $month){
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
			$fieldToSearch = array("mvt.NOMOR_NPWP","mvt.NAMA_VENDOR","mvt.ALAMAT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						'' NO_BUPOT,
						gh.INVOICE_DATE,
						gh.DPP,
						'' PCN_PGN,
						(select NPWP_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NPWP_PEMOTONG,
						(select NAMA_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NAMA_PEMOTONG
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ? 
						and mp.GL_ACCOUNT = '21320001'
						and mp.WHT_TAX_CODE in ('4','5','6','7','8') 
						$where";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_pph42jk_csv($year, $month)
	{

		 if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						'' NO_BUPOT,
						gh.INVOICE_DATE,
						gh.DPP,
						'' PCN_PGN,
						(select NPWP_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NPWP_PEMOTONG,
						(select NAMA_PEMOTONG from MASTER_PEMOTONG where WHT_TAX_CODE = '2') NAMA_PEMOTONG
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ? 
						and mp.GL_ACCOUNT = '21320001'
						and mp.WHT_TAX_CODE in ('4','5','6','7','8') ";		
		
		$query = $this->db->query($mainQuery, array($year, $bulan));
		return $query;
	}

	public function get_pph42_pp23($year, $month){
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
			$fieldToSearch = array("mvt.NOMOR_NPWP","mvt.NAMA_VENDOR","mvt.ALAMAT","mvt.S_KET_PP23");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						'' NTPN,
						'' TGL_NTPN,
						gh.DPP,
						mvt.S_KET_PP23,
						ROUND(nvl(gh.DPP,0) * nvl(gh.PERCENTAGE_PPH,0)) PPH,
						'' NPWP_PEMOTONG,
						'' NAMA_PEMOTONG
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.KJS = '423'
						$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_pph42_pp23_csv($year, $month)
	{

		 if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						'' NTPN,
						'' TGL_NTPN,
						gh.DPP,
						mvt.S_KET_PP23,
						ROUND(nvl(gh.DPP,0) * nvl(gh.PERCENTAGE_PPH,0)) PPH,
						'' NPWP_PEMOTONG,
						'' NAMA_PEMOTONG
						from MASTER_PPH mp inner join GL_HEADERS gh
						on mp.ID_WHT_TAX = gh.ID_WHT_TAX
						inner join MASTER_VENDOR_TAX mvt on mvt.NAMA_VENDOR = gh.NAMA_VENDOR
						and DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ? 
						and mp.KJS = '423' ";		
		
		$query = $this->db->query($mainQuery, array($year, $bulan));
		return $query;
	}

	public function get_ppn_masukan($year, $month){
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
			$fieldToSearch = array("mvt.NOMOR_NPWP","mvt.NAMA_VENDOR","mvt.ALAMAT");
			$where = query_datatable_search($keywords, $fieldToSearch);
		}

		if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select substr(gh.FAKTUR_PAJAK,1,2) KD_JENIS,
						substr(gh.FAKTUR_PAJAK,3,1) FG_PENGGANTI,
						substr(REPLACE(REPLACE(gh.FAKTUR_PAJAK,'.',''),'-',''),4,13) NO_FAKTUR,
						DATE_FORMAT(gh.INVOICE_DATE, '%m') MASA,
						DATE_FORMAT(gh.INVOICE_DATE, '%Y') TAHUN_PAJAK,
						gh.TGL_FAKTUR_PAJAK,
						mvt.NOMOR_NPWP,
						mvt.NAMA_VENDOR,
						mvt.ALAMAT,
						gh.DPP,
						nvl(gh.DPP,0) * 10/100 PPN
						from GL_HEADERS gh inner join MASTER_VENDOR_TAX mvt
						on gh.NAMA_VENDOR = mvt.NAMA_VENDOR
						inner join MASTER_PPN mp on gh.ID_MSTR_PPN = mp.ID_MSTR_PPN
						where DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.TAX_CODE = 'M2'
						$where ";

		$queryData = query_datatable($mainQuery);

		$total = $this->db->query($mainQuery, array($year, $bulan))->num_rows();
		$data  = $this->db->query($queryData, array($year, $bulan))->result_array();

		$result['data']       = $data;
		$result['total_data'] = $total;

		return $result;	

	}

	function get_ppn_masukan_csv($year, $month)
	{

		 if($month < 10){
			$bulan = '0' .$month;
		}else{
			$bulan = $month;
		}

		$mainQuery = " select substr(gh.FAKTUR_PAJAK,1,2) KD_JENIS,
						substr(gh.FAKTUR_PAJAK,3,1) FG_PENGGANTI,
						substr(REPLACE(REPLACE(gh.FAKTUR_PAJAK,'.',''),'-',''),4,13) NO_FAKTUR,
						DATE_FORMAT(gh.INVOICE_DATE, '%m') MASA,
						DATE_FORMAT(gh.INVOICE_DATE, '%Y') TAHUN_PAJAK,
						gh.TGL_FAKTUR_PAJAK,
						mv.NOMOR_NPWP,
						mv.NAMA_VENDOR,
						mv.ALAMAT,
						gh.DPP,
						nvl(gh.DPP,0) * 10/100 PPN
						from GL_HEADERS gh inner join MASTER_VENDOR mv
						on gh.NAMA_VENDOR = mv.NAMA_VENDOR
						inner join MASTER_PPN mp on gh.ID_MSTR_PPN = mp.ID_MSTR_PPN
						where DATE_FORMAT(gh.INVOICE_DATE, '%Y') = ?
						and DATE_FORMAT(gh.INVOICE_DATE, '%m') = ?
						and mp.TAX_CODE = 'M2' ";		
		
		$query = $this->db->query($mainQuery, array($year, $bulan));
		return $query;
	}

}