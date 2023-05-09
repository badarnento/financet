<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menurights_ctl extends CI_Controller {

	protected $tables;

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->tables = $this->config->item('tables', 'ion_auth');

		$this->lang->load('auth');
		$this->load->helper('user_helper');
		$this->load->model('user_mdl', 'user');
		$this->load->model('menu_mdl', 'menu');

	}

	public function index()
	{
		
		$data['title']         = "Menu Rights";
		$data['module']        = "datatable";
		$data['template_page'] = "admin/menu";

		$this->template->load('main', $data['template_page'], $data);
	}

	public function load_data_menu()
	{

		$result['data']            = "";
		$result['draw']            = "";
		$result['recordsTotal']    = 0;
		$result['recordsFiltered'] = 0;

		$get_all    = $this->menu->get_menu_datatable();
		$data       = $get_all['data'];
		$total      = $get_all['total_data'];
		$start      = (isset($_POST['start'])) ? $_POST['start'] : 0;
		$number     = $start+1;

		if($total > 0){
			
			$previousID = null;

			foreach($data as $value) {

				$parent    = ($value['PARENT_NAME'] != NULL) ? $value['PARENT_NAME'] : "XXX";
				$showorder = "<a href='javascript:void(0)' class='showorder' data-productid='".$value['SHOWORDER']."' data-order='".$value['SHOWORDER']."' data-parent='". strtolower(str_replace(' ', '_', $parent)) ."' style='color:#ff6436'><i class='fa fa-retweet' aria-hidden='true'></i></a>";

				$row[] = array(
							'no'              => $number,
							'id'              => $value['ID'],
							'parent'          => $parent,
							'parent_id'       => $value['PARENT_ID'],
							'category'        => $value['LINK_TYPE'],
							'title'           => $value['TITLE'],
							'url'             => $value['URL'],
							'style'           => $value['STYLE'],
							'showorder_after' => $previousID,
							'showorder'       => $value['SHOWORDER'],
							'showorder_icon'  => $showorder
						);
				$previousID = $value['ID'];
				$number++;
			}

			$result['data']            = $row;
			$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
			$result['recordsTotal']    = $total;
			$result['recordsFiltered'] = $total;
		}

		echo json_encode($result);

	}


	

}

/* End of file Menurights_ctl.php */
/* Location: ./application/controllers/administrator/Menurights_ctl.php */