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

class Api_po_ctl extends REST_Controller {

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

		$this->table = "MASTER_VENDOR";
		$this->primary_key = "ID_VENDOR";
    $this->allowed_fields = array(
                'nomor_po'         => 'PO_NUMBER',
                'description_po'   => 'PO_LINE_DESC',
                'status'           => 'STATUS',
                'tgl_po'           => 'APPROVED_DATE',
                'tgl_period_start' => 'PO_PERIOD_FROM',
                'tgl_period_end'   => 'PO_PERIOD_TO',
                'total_amount'     => 'PO_AMOUNT',
                'invoice_amount'   => 'INVOICE_AMOUNT',
                'detail_po'        => array(
                                            'item_name'        => 'ITEM_NAME',
                                            'item_description' => 'ITEM_DESCRIPTION',
                                            'quantity'         => 'QUANTITY',
                                            'unit_price'       => 'UNIT_PRICE',
                                            'total_price'      => 'TOTAL_PRICE'
                                          )
							);

      $this->load->model('api_mdl', 'api');
    }

    public function index_get()
    {

        $id = $this->get( 'id_vendor' );

        $id = (int) $id;

        /*if ($id <= 0)
        {
          $this->response([ 'status' => false, 'message'  => 'no selected id' ], REST_Controller::HTTP_BAD_REQUEST);
        }*/
        
        $get_data = $this->api->get_po_by_vendor_id($id);

        if ( $get_data )
        {

            foreach ($get_data as $key => $value) {
              $po_num = $value['PO_NUMBER'];
              foreach ($this->allowed_fields['detail_po'] as $k => $v) {
                $data_detail[$k] = $value[$v];
              }
              $detail_po[$po_num][] = $data_detail;

            }

            $last_no_po ="";
            foreach ($get_data as $key => $value) {

              $po_num = $value['PO_NUMBER'];
              $data['url_doc'] = base_url('api/link/print-po/') . encrypt_string($value['PO_HEADER_ID'], true);
              foreach ($this->allowed_fields as $k => $v) {
                $data[$k] = ($k != 'detail_po') ? $value[$v] : $detail_po[$po_num];
                if (strpos($k, 'tgl_') !== false):
                  $data[$k] = dateFormat($value[$v], 5, false);
                elseif($k == 'detail_po'):
                  $data[$k] = $detail_po[$po_num];
                else:
                  $data[$k] = $value[$v];
                endif;
              }
              if($po_num != $last_no_po){
                $result[] = $data;
              }
              unset($data);
              $last_no_po = $po_num;
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

/* End of file Api_po_ctl.php */
/* Location: ./application/controllers/api/Api_po_ctl.php */