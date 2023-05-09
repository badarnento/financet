<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO_manual_ctl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		
	}
	

	public function index(){

		$data['title']          = "PO Upload Manual";
		$data['module']         = "datatable";
		$data['template_page']  = "pr_po/po_manual";
		
		$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
		$breadcrumb[] = array( "name" => $data['title'], "link" => "", "class" => "active" );
		$data['breadcrumb']    = $breadcrumb;

		$this->template->load('main', $data['template_page'], $data);

	}

	public function upload_po_header(){


		if($_POST && isset($_FILES["file"]["name"])){

			$limit_max   = $this->input->post('limit_max');
			$action_name = "admin_badar";

			$this->load->library('excel');
			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			$buyer_arr[] = [ "BUYER_NAME" => "Prayogi D Santos", "BUYER_EMAIL" => "prayogi_d_santosa@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Syafina Nur Fauzia", "BUYER_EMAIL" => "syafina_n_fauzia@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Yani Syamsiah", "BUYER_EMAIL" => "yani_syamsiah@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Adin Y Yulian", "BUYER_EMAIL" => "adin_y_yulian@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Rifky Kurniawan", "BUYER_EMAIL" => "rifky_arief@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Aditia Putranto", "BUYER_EMAIL" => "aditia_putranto@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Theresa Sellen Tan", "BUYER_EMAIL" => "theresa_s_tan@linkaja.id" ];				
			$buyer_arr[] = [ "BUYER_NAME" => "Paramitha Widyasari", "BUYER_EMAIL" => "paramitha_widyasari@linkaja.id" ];
				
			$get_all_vendor = $this->db->query("SELECT NAMA_VENDOR, NAMA_REKENING, NAMA_BANK, ACCT_NUMBER FROM MASTER_VENDOR")->result_array();
			foreach ($get_all_vendor as $key => $value) {
				$dataVendor[] = array(
										"NAMA_VENDOR"   => $value['NAMA_VENDOR'],
										"NAMA_BANK"     => $value['NAMA_BANK'],
										"NAMA_REKENING" => $value['NAMA_REKENING'],
										"ACCT_NUMBER"   => $value['ACCT_NUMBER']
									);
			}

			foreach($object->getWorksheetIterator() as $worksheet){

				$j=0;
				for($row=2; $row<= $worksheet->getHighestRow(); $row++){

					$no_pr  = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$action = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

					if(strtolower($action) != 'cancel pr'){

						$j++;

						$check_data = $this->db->get_where("PR_HEADER", [ "PR_NUMBER" => $no_pr]);
						$x=2;
						$no_po        = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$po_ref       = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$po_desc      = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$badan_usaha  = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$vendor       = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$vendor_name  = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$buyer        = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$period_start = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$period_start = ($period_start == 0 || $period_start == "") ? '' : date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($period_start));
						$period_end   = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$period_end   = ($period_end == 0 || $period_end == "") ? '' : date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($period_end));
						$po_amount    = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$category_po  = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$status_po    = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$approve_date = $worksheet->getCellByColumnAndRow($x++, $row)->getValue();
						$approve_date = ($approve_date == 0 || $approve_date == "") ? '' : date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($approve_date));

						if($check_data->num_rows() > 0){

							$pr        = $check_data->row_array();
							$pr_id     = $pr['PR_HEADER_ID'];
							$pr_status = $pr['STATUS'];

							$check_po_exist = $this->db->get_where("PO_HEADER", [ "ID_PR_HEADER_ID" => $pr_id]);

							if($check_po_exist->num_rows() < 1){

								$nama_vendor   = $vendor_name;
								$nama_bank     = "";
								$nama_rekening = "";
								$acct_number   = "";
								foreach ($dataVendor as $key => $vend) {

									if (strpos(strtolower($vend['NAMA_VENDOR']), strtolower($vendor)) !== false) {
										$nama_vendor   = $vend['NAMA_VENDOR'];
										$nama_bank     = $vend['NAMA_BANK'];
										$nama_rekening = $vend['NAMA_REKENING'];
										$acct_number   = $vend['ACCT_NUMBER'];
									}

									if( strtolower($vend['NAMA_VENDOR']) == strtolower($vendor) ){
										$nama_vendor   = $vend['NAMA_VENDOR'];
										$nama_bank     = $vend['NAMA_BANK'];
										$nama_rekening = $vend['NAMA_REKENING'];
										$acct_number   = $vend['ACCT_NUMBER'];
									}
									
									if( strtolower($vend['NAMA_VENDOR']) == strtolower($vendor_name) ){
										$nama_vendor   = $vend['NAMA_VENDOR'];
										$nama_bank     = $vend['NAMA_BANK'];
										$nama_rekening = $vend['NAMA_REKENING'];
										$acct_number   = $vend['ACCT_NUMBER'];
									}
								}

								foreach ($buyer_arr as $key => $buy) {
									if (strpos(strtolower($buy['BUYER_NAME']), strtolower($buyer)) !== false) {
										$buyer_name  = $buy['BUYER_NAME'];
										$buyer_email = $buy['BUYER_EMAIL'];
									}
								}

								$dataCreatePO = array(
														"ID_PR_HEADER_ID"    => $pr_id,
														"PO_TYPE"            => "Normal",
														"PO_AMOUNT"          => $po_amount,
														"STATUS"             => 'approved',
														"STATUS_DESCRIPTION" => 'Approved',
														"BUYER"              => $buyer_name,
														"BUYER_EMAIL"        => $buyer_email,
														"CURRENCY"           => 'IDR',
														"CURRENCY_RATE"      => 0,
														"CREATED_BY"         => $action_name,
														"UPDATED_BY"         => $action_name
													);

								if($approve_date){
									$dataCreatePO['PO_DATE']      = $approve_date;
									$dataCreatePO['UPDATED_DATE'] = $approve_date;
								}

								$insert   = $this->crud->create("PO_HEADER", $dataCreatePO);
								$dataUpdPR = array();

								if($insert > 0){

									$batchDataPoLine = array();
									$get_pr_line = $this->crud->read('PR_LINES', [ "PR_HEADER_ID" => $pr_id], "PR_LINES_ID" );
									foreach ($get_pr_line as $key => $pr_l) {
										$prl_id      = $pr_l['PR_LINES_ID'];
										$prl_id_rkap = $pr_l['ID_RKAP_LINE'];

										$dataCreatePOLine = array(
																"PO_HEADER_ID"             => $insert,
																"ID_RKAP_LINE"             => $prl_id_rkap,
																"PR_LINES_ID"              => $prl_id,
																"PO_NUMBER"                => $no_po,
																"PO_LINE_DESC"             => $po_desc,
																"PO_LINE_AMOUNT"           => 0,
																"VENDOR_NAME"              => $nama_vendor,
																"VENDOR_BANK_NAME"         => $nama_bank,
																"VENDOR_BANK_ACCOUNT_NAME" => $nama_rekening,
																"VENDOR_BANK_ACCOUNT"      => $acct_number,
																"CREATED_BY"               => $action_name,
																"UPDATED_BY"               => $action_name
															);

										if($period_start && $period_end){
											$dataCreatePOLine['PO_PERIOD_FROM'] = $period_start;
											$dataCreatePOLine['PO_PERIOD_TO']   = $period_end;
										}

										$batchDataPoLine[] = $dataCreatePOLine;
									}

									if($batchDataPoLine){
										$this->crud->create_batch("PO_LINES", $batchDataPoLine);
										$dataUpdPR[] = [ "PR_CATEGORY" => $category_po, "STATUS" => "po created", "UPDATED_BY" => $action_name, "PR_HEADER_ID" => $pr_id];
									}
								}

								if($dataUpdPR){
									$this->crud->update_batch_data("PR_HEADER", $dataUpdPR, "PR_HEADER_ID");
								}
								echo $no_pr . ' successfully created';

							}else{
								echo $no_pr . ' po created/exist';
							}
							echo "<br>";

						}else{
							echo $no_pr . " Gak ada";
							echo "<br>";
						}
					}

					if($j > $limit_max){
						echo 'Total ' . $j;
						echo ' -- All Done --';
						die;
					}

				}

			}

		}

	}

	public function upload_po_boq(){


		if($_POST && isset($_FILES["file"]["name"])){

			$limit_max = $this->input->post('limit_max');
			$action_name = "admin_badar";

			$this->load->library('excel');
			$path   = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			foreach($object->getWorksheetIterator() as $worksheet){

				$j=0;
				for($row=2; $row<= $worksheet->getHighestRow(); $row++){

					$no_pr  = $worksheet->getCellByColumnAndRow(0, $row)->getValue();

					$x             =2;
					$pr_detail_id  = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$item_name     = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$item_desc     = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$category_item = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$qty           = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$uom           = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$unit_price    = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$total_price   = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					$no_po         = $worksheet->getCellByColumnAndRow(15, $row)->getValue();

					$check_data    = $this->db->get_where("PR_DETAIL", [ "PR_DETAIL_ID" => $pr_detail_id]);

					if($check_data->num_rows() > 0){


						$pr_d       = $check_data->row_array();
						$pr_id      = $pr_d['PR_HEADER_ID'];
						$updated_by = $pr_d['UPDATED_BY'];

							$dataUpdPR = [
												"CATEGORY_ITEM" => $category_item,
												"UOM"           => $uom,
												"UPDATED_BY"    => $action_name
											];

							$this->crud->update("PR_DETAIL", $dataUpdPR, [ "PR_DETAIL_ID"  => $pr_detail_id ], false);
							$get_pl_id   = $this->db->get_where("PR_DETAIL", [ "PR_DETAIL_ID" => $pr_detail_id])->row_array();

							if($get_pl_id){

								$get_po_data = $this->db->get_where("PO_LINES", [ "PR_LINES_ID" => $get_pl_id['PR_LINES_ID'], "PO_NUMBER" => $no_po ] )->row_array();
								if($get_po_data){

									$dataCreateBOQ = array(
															"PO_HEADER_ID"     => $get_po_data['PO_HEADER_ID'],
															"PO_LINE_ID"       => $get_po_data['PO_LINE_ID'],
															"PR_DETAIL_ID"     => $pr_detail_id,
															"PO_DETAIL_NUMBER" => $get_pl_id['PR_DETAIL_NUMBER'],
															"ITEM_NAME"        => ($item_name) ? $item_name : $get_po_data['PO_LINE_DESC'],
															"DESCRIPTION_PO"   => ($item_name) ? $item_name : $get_po_data['PO_LINE_DESC'],
															"CATEGORY_ITEM"    => $category_item,
															"UOM"              => $uom,
															"PRICE"            => $unit_price,
															"PO_DETAIL_AMOUNT" => $total_price,
															"CREATED_BY"       => $action_name,
															"UPDATED_BY"       => $action_name
															);
									$this->crud->create('PO_DETAIL', $dataCreateBOQ, false);

									echo $no_po . " successfully created boq";
								}else{
									echo $no_po . " Gak ada PO nya";
								}
									echo "<br>";
								$j++;

							}
					}

					if($j > $limit_max){
						echo 'Total ' . $j;
						echo ' -- All Done --';
						die;
					}

				}

			}

		}

	}

}

/* End of file PO_manual_ctl.php */
/* Location: ./application/controllers/pr_po/PO_manual_ctl.php */