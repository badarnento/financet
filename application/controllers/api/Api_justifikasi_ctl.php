<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Api_justifikasi_ctl extends REST_Controller {

	protected $table,
			  $primary_key,
			  $allowed_fields;

	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

		$this->table = "FS_BUDGET";
		$this->primary_key = "ID_FS";
        $this->allowed_fields = array(
                                'justifikasi_number'      => 'FS_NUMBER',
                                'justifikasi_name'        => 'FS_NAME',
                                'justifikasi_description' => 'FS_DESCRIPTION',
                                'justifikasi_nominal'     => 'NOMINAL_FS',
                                'status'                  => 'STATUS',
                                'id_directorate'          => 'ID_DIR_CODE',
                                'id_division'             => 'ID_DIVISION',
                                'id_unit'                 => 'ID_UNIT'
							);
    }

    public function index_get()
    {

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            $get_data = $this->crud->read($this->table);
            if ( $get_data )
            {
                foreach ($get_data as $key => $value) {

            		$data['id'] = $value[$this->primary_key];
                	foreach ($this->allowed_fields as $k => $v) {
                		$data[$k] = ($value[$v]) ? $value[$v] : '' ;
                	}

                	$result[] = $data;
                	unset($data);
                }
                $this->response( [ 'status' => true, 'data'  => $result ], REST_Controller::HTTP_OK );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'Data not found'
                ], REST_Controller::HTTP_NOT_FOUND );
            }
        }
        else
        {

            $id = (int) $id;

            if ($id <= 0)
            {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            }
            
            $get_data = $this->crud->read_by_param($this->table, array($this->primary_key => $id));

            if ( $get_data )
            {

        		$result['id'] = $get_data[$this->primary_key];
                foreach ($this->allowed_fields as $k => $v) {
            		$result[$k] = ($get_data[$v]) ? $get_data[$v] : '' ;
            	}

                $this->response( [ 'status' => true, 'data'  => $result ], REST_Controller::HTTP_OK );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'Data not found'
                ], REST_Controller::HTTP_NOT_FOUND );
            }
        }
    }

}

/* End of file Api_justifikasi_ctl.php */
/* justifikasi: ./application/controllers/api/Api_justifikasi_ctl.php */