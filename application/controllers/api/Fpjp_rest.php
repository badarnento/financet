<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

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
class Fpjp_rest extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {

        $id = $this->get( 'id' );

        if ( $id === null )
        {

            $fpjp_data = $this->crud->read("FPJP_HEADER");
            if ( $fpjp_data )
            {
                foreach ($fpjp_data as $key => $value) {
                   $result[] = array(
                                        "id"        => $value['FPJP_HEADER_ID'],
                                        "tgl_fpjp"  => dateFormat($value['FPJP_DATE'],4,false),
                                        "no_fpjp"   => $value['FPJP_NUMBER'],
                                        "nama_fpjp" => $value['FPJP_NAME'],
                                        "amount"    => $value['FPJP_AMOUNT']
                                );
                }
                $this->response( $result, REST_Controller::HTTP_OK );
            }
            else
            {
                // Set the response and exit
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
            
            $fpjp_data = $this->crud->read_by_param("FPJP_HEADER", array("FPJP_HEADER_ID" => $id));

            if ( $fpjp_data )
            {
                $result = array(
                                    "id"        => $fpjp_data['FPJP_HEADER_ID'],
                                    "tgl_fpjp"  => dateFormat($fpjp_data['FPJP_DATE'],4,false),
                                    "no_fpjp"   => $fpjp_data['FPJP_NUMBER'],
                                    "nama_fpjp" => $fpjp_data['FPJP_NAME'],
                                    "amount"    => $fpjp_data['FPJP_AMOUNT']
                                );
                $this->response( $result, 200 );
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

    public function index_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
}
/* End of file Fpjp_rest.php */
/* Location: ./application/controllers/api/Fpjp_rest.php */