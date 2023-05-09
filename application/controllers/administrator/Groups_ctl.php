<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups_ctl extends CI_Controller {

	protected $tables,
			  $status_header;

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
		$this->load->model('group_mdl', 'group');

		$this->status_header = 401;

	}

	public function index()
	{

		if($this->ion_auth->is_admin() == true || in_array("admin/groups", $this->session->userdata['menu_url']) ){

			$data['title']         = "User Groups";
			$data['module']        = "datatable";
			$data['template_page'] = "admin/group";
			$data['menus']         = get_all_menus();

			$this->template->load('main', $data['template_page'], $data);

		}
		else{
			redirect('unauthorized', 'refresh');
		}

	}

	public function load_data_group(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/groups", $this->session->userdata['menu_url']) ){

			$result['data']            = "";
			$result['draw']            = "";
			$result['recordsTotal']    = 0;
			$result['recordsFiltered'] = 0;
			$this->status_header = 200;

			$get_all    = $this->group->get_group_datatable();
			$data       = $get_all['data'];
			$total      = $get_all['total_data'];
			$start      = (isset($_POST['start'])) ? $_POST['start'] : 0;
			$number     = $start+1;
			$attachment = "";

			foreach ($data as $k => $group)
			{
				$data[$k]['MENUS'] = $this->group->get_group_menu($group['ID'])->result_array();
			}

			if($total > 0){
				foreach($data as $value) {

					$groupMenuID = array();
					$groupMenu   = array();

					foreach($value['MENUS'] as $menu) {
						$groupMenuID[] = $menu['ID'];
						$groupMenu[]   = $menu['TITLE'];
					}

					$row[] = array(
							'no'          => $number,
							'id'          => $value['ID'],
							'group_name'  => $value['NAME'],
							'description' => $value['DESCRIPTION'],
							'menu_id'     => implode(", ", $groupMenuID),
							'group_menu'  => implode(", ", $groupMenu)
							);
					$number++;
				}

				$result['data']            = $row;
				$result['draw']            = ($this->input->post('draw')) ? $this->input->post('draw') : 0;
				$result['recordsTotal']    = $total;
				$result['recordsFiltered'] = $total;
			}

        }

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}

	public function save_group(){

		if($this->ion_auth->is_admin() == true || in_array("admin/groups", $this->session->userdata['menu_url']) ){

			$this->form_validation->set_rules('is_new_record', 'Is New Record', 'required|alpha_numeric');
			$is_new_record = $this->input->post('is_new_record');

			if($is_new_record == 1){
				$this->_createGroup();
			}
			else{
				$this->_updateGroup();
			}

		}

	}

	private function _createGroup(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/groups", $this->session->userdata['menu_url']) ){

			$this->form_validation->set_rules('group_name', 'Name', 'trim|required|is_unique[' . $this->tables['groups'] . '.NAME]');

			$result['status']   = false;
			$result['messages'] = "";

			$this->status_header = 200;

			if ($this->form_validation->run() === TRUE){
				
				$group_name  = $this->input->post('group_name');
				$description = $this->input->post('description');

				$data = array(
					'NAME'        => $group_name,
					'DESCRIPTION' => $description
				);

				$create_group = $this->crud->create($this->tables['groups'], $data);

				if ($create_group) {
					$result['status']   = true;
					$result['messages'] = "Group successfully created";
				}
				else{
					$result['messages'] = "Failed to create group";
				}
			}
			else{
				$result['messages'] = validation_errors();
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));


	}

	private function _updateGroup(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/groups", $this->session->userdata['menu_url']) ){

			$id = $this->input->post('id');
			$this->form_validation->set_rules('id', 'No id Selected', 'required|alpha_numeric');
			$this->status_header = 200;

			$result['status']   = false;
			$result['messages'] = "";

			$this->form_validation->set_rules('group_name', 'Name', 'trim|required');
			
			if ($this->form_validation->run() === TRUE){

				$data = array(
					'NAME'        => $this->input->post('group_name'),
					'DESCRIPTION' => $this->input->post('description'),
				);

				if ($this->crud->update($this->tables['groups'], $data, $id)){
					$result['status']   = true;
					$result['messages'] = "Group successfully updated";
				}
				else {
					$result['messages'] = "Failed to update group";
				}
			}
			else{
				$result['messages'] = validation_errors();
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}


	public function delete_group(){

		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$id = $this->input->post('id');
			$this->form_validation->set_rules('id', 'No id Selected', 'required|alpha_numeric');

			$this->status_header = 200;

			$result['status']   = false;
			$result['messages'] = "Failed to delete data";

			$delete = $this->crud->delete($this->tables['groups'], $id);

			if($delete > 0){
				$result['status']   = true;
				$result['messages'] = "Data successfully deleted";
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}

	public function assign_menu()
	{
		$result = false;

		if($this->ion_auth->is_admin() == true || in_array("admin/users", $this->session->userdata['menu_url']) ){

			$id = $this->input->post('id');
			$this->form_validation->set_rules('id', 'No id Selected', 'required|alpha_numeric');
			$this->form_validation->set_rules('menus[]', 'Assign Menu', 'trim|required');

			$result['status']   = false;
			$result['messages'] = "";

			$this->status_header = 200;

			if ($this->form_validation->run() === TRUE)
			{
				$table = $this->tables['groups_menus'];
				$menus = $this->input->post('menus');

				$this->crud->delete($table, array("GROUP_ID" => $id));

				foreach ($menus as $menu)
				{
					$data[] = array(
										"ID_MENU"  => $menu,
										"GROUP_ID" => $id
									);
				}

				$insert_batch = $this->crud->create_batch($table, $data);

				if($insert_batch){
					$result['status']   = true;
					$result['messages'] = "Successfully assigned groups";
				}
				else{
					$result['messages'] = "Failed to assigned groups";
				}

			}
			else{
				$result['messages'] = validation_errors();
			}
		}

		if($result === false){
			redirect('/', 'refresh');
			exit;
		}

        $this->output->set_status_header($this->status_header)
        				->set_content_type('application/json')
        				->set_output(json_encode($result));
	}

}

/* End of file Groups_ctl.php */
/* Location: ./application/controllers/administrator/Groups_ctl.php */
