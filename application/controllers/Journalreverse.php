<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Journalreverse extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->model('Journalreverse_mdl', 'journalreverse');

	}

	public function journal_reverse()
	{

		if($this->ion_auth->is_admin() == true || in_array("Journalreverse/journal_reverse", $this->session->userdata['menu_url']) ){

			$data['title']          = "Journal Reverse";
			$data['module']         = "datatable";
			$data['template_page']  = "pages/journal_reverse";
			$data['get_nature']  	= get_nature();

			$group = $this->session->userdata('group_id');

				foreach ($group as $key => $value) {
					$grpName = get_group_data($value);
					$group_name[] = $grpName['NAME'];
				}

			$data['group_name']    = $group_name;

			$breadcrumb[] = array( "name" => "Home", "link" => base_url(), "class" => "" );
			$breadcrumb[] = array( "name" => "Journal Reverse", "link" => "", "class" => "active" );

			$data['breadcrumb']    = $breadcrumb;
			
			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}


	public function load_data_journal_reverse(){

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$gl_date_from = "";
		$gl_date_to   = "";
		$journalnameencrypt  = $this->input->post('journalname');
		$journalname = str_replace($this->config->item('encryption_key'), "", base64url_decode($journalnameencrypt));

		// if($this->input->post('gl_date_from') != "" && $this->input->post('gl_date_to') != ""){
		// 	$exp_date_from = explode("/", $this->input->post('gl_date_from'));
		// 	$exp_date_to   = explode("/", $this->input->post('gl_date_to'));

		// 	$gl_date_from     = $exp_date_from[2]."-".$exp_date_from[1]."-".$exp_date_from[0];
		// 	$gl_date_to       = $exp_date_to[2]."-".$exp_date_to[1]."-".$exp_date_to[0];
		// }

		// $get_all = $this->journalreverse->get_journal_reverse_datatable($gl_date_from, $gl_date_to, $journalname);

		$get_all = $this->journalreverse->get_journal_reverse_datatable($journalname);
		$data    = $get_all['data'];
		$total   = $get_all['total_data'];
		$start   = $this->input->post('start');
		$number  = $start+1;

		if($total > 0){

			foreach($data as $value) {

				$row[] = array(
					'no'             		 => $number,
					'gl_date'		 		 => date("d-m-Y",strtotime($value['GL_DATE'])),
					'batch_name'       		 => $value['BATCH_NAME'],
					'journal_name'           => $value['JOURNAL_NAME'],
					'saldo_awal'   		 	 => number_format($value['SALDO_AWAL'],0,'.',','),
					'debit'   				 => number_format($value['DEBIT'],0,'.',','),
					'credit'   		 		 => number_format($value['CREDIT'],0,'.',','),
					'nature'    			 => $value['NATURE'],
					'account_description'    => $value['ACCOUNT_DESCRIPTION'],
					'journal_description'    => $value['JOURNAL_DESCRIPTION'],
					'reference_1'    		 => $value['REFERENCE_1'],
					'reference_2'    		 => $value['REFERENCE_2'],
					'reference_3'    		 => $value['REFERENCE_3']
				);

				$number++;

			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;

		}

		echo json_encode($result);

	}

	public function reverse_journal(){

		$journalname   = $this->input->post('journalname');
		$journal_decrypt = str_replace($this->config->item('encryption_key'), "", base64url_decode($journalname));

		// echo 'testing'.$journal_decrypt; die;

		$result['status'] = false;
		$result['messages'] = "Failed to reverse data";

		$reverse = $this->journalreverse->call_procedure_reverse($journal_decrypt);

		if($reverse)
		{
				$result['status']   = true;
				$result['messages'] = "Data successfully reversed !!";
		}
		else
		{
			echo '0';
			$result['messages'] = "Data failed reversed !!";
		}

		echo json_encode($result);
	}




	function load_ddl_journal()
	{		
		$hasil	= $this->journalreverse->get_data_ddl_journal();
		$query  = $hasil['query'];
		$result .= "<option value='' data-name='' >-- Choose Journal --</option>";
		foreach($query->result_array() as $row)	
		{
			$journalencrypt = base64url_encode($row['JOURNAL_NAME'].$this->config->item('encryption_key'));
			$result .= "<option value='".$journalencrypt."' data-name='".$journalencrypt."' >".$row['JOURNAL_NAME']."</option>";
		}		
		echo $result;
		$query->free_result();
	}

}



/* End of file Journalreverse.php */

/* Location: ./application/controllers/Journalreverse.php */