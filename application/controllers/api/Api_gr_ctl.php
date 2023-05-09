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

class Api_gr_ctl extends REST_Controller {

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

		$this->table = "GR_HEADER";
		$this->primary_key = "GR_HEADER_ID";
        $this->allowed_fields = array(
                                        'gr_code'                      => 'GR_HEADER_ID',
                                        'asset_type'                   => 'ASSET_TYPE',
                                        'no_bast'                      => 'NO_BAST',
                                        'dpis'                         => 'DPIS',
                                        'invoice_no'                   => 'INVOICE_NO',
                                        'period'                       => 'PERIOD',
                                        'cip_flag'                     => 'CIP_FLAG',
                                        'tanggal_invoice'              => 'TANGGAL_INVOICE',
                                        'asset_category_mayor_code'    => 'ASSET_CATEGORY_MAYOR_CODE',
                                        'asset_category_mayor'         => 'ASSET_CATEGORY_MAYOR',
                                        'asset_category_minor_code'    => 'ASSET_CATEGORY_MINOR_CODE',
                                        'asset_category_minor'         => 'ASSET_CATEGORY_MINOR',
                                        'item_name'                    => 'ITEM_NAME',
                                        'quantity'                     => 'QUANTITY',
                                        'unit_price'                   => 'UNIT_PRICE',
                                        'nomor_po'                     => 'NOMOR_PO',
                                        'nilai_po_idr'                 => 'NILAI_PO_IDR',
                                        'po_description'               => 'PO_DESCRIPTION',
                                        'nomor_coa'                    => 'NOMOR_COA',
                                        'nomor_pr'                     => 'NOMOR_PR',
                                        'email_user'                   => 'EMAIL_USER',
                                        'full_name'                    => 'FULL_NAME',
                                        'departement_code'             => 'DEPARTEMENT_CODE',
                                        'dept_name'                    => 'DEPT_NAME',
                                        'umur_manfaat_tahun'           => 'UMUR_MANFAAT_TAHUN',
                                        'region_code'                  => 'REGION_CODE',
                                        'region'                       => 'REGION',
                                        'location_asset_code'          => 'LOCATION_ASSET_CODE',
                                        'lokasi_aset'                  => 'LOKASI_ASET',
                                        'kepemilikan_code'             => 'KEPEMILIKAN_CODE',
                                        'kepemilikan'                  => 'KEPEMILIKAN',
                                        'vendor'                       => 'VENDOR',
                                        'contract_identification_code' => 'CONTRACT_IDENTIFICATION_CODE',
                                        'contract_identification'      => 'CONTRACT_IDENTIFICATION',
                                        'project_ownership_code'       => 'PROJECT_OWNERSHIP_CODE',
                                        'project_ownership'            => 'PROJECT_OWNERSHIP',
                                        'budget_type'                  => 'BUDGET_TYPE',
                                        'merek'                        => 'MEREK',
                                        'serial_number'                => 'SERIAL_NUMBER'
							
                            );

      $this->load->model('api_mdl', 'api');

    }

    public function index_get()
    {

        $po_number = ( $this->get('po_number') ) ? $this->get('po_number') : false;
        
        $get_data = $this->api->get_gr_for_assets($po_number);

        if ( $get_data )
        {

            // $result['id'] = $get_data[$this->primary_key];
            /*foreach ($this->allowed_fields as $k => $v) {
              $result[$k] = ($get_data[$v]) ? $get_data[$v] : '' ;
            }*/

            foreach ($get_data as $key => $value) {

              // $data['id'] = $value[$this->primary_key];
                foreach ($this->allowed_fields as $k => $v) {
                    if($v == "GR_HEADER_ID"):
                        $data[$k] = encrypt_string($value[$v],true);
                    else:
                        $data[$k] = ($value[$v]) ? $value[$v] : '' ;
                    endif;
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


    public function index_put()
    {

        $gr_code = $this->put('gr_code');
        $arrData = false;

        if(is_array($gr_code)):
            $data_arr = array();
            for ($i=0; $i < count($gr_code); $i++) {
                $id = decrypt_string($gr_code[$i], true);
                $id = (int) $id;
                if ($id > 0){
                    $data_arr[] = array("PULLED" => "Y", "UPDATED_BY" => "API HIT", $this->primary_key => $id);
                }
            }
            $arrData = (count($data_arr) > 0) ? true : false;
        else:
            $id = decrypt_string($gr_code, true);
            $id = (int) $id;
        endif;

        if ( $arrData == false && $id == 0):

            $message = [
                        'status'  => false,
                        'message' => 'No GR Code'
                    ];

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND );
        endif;

        if($arrData):
            $update = $this->crud->update_batch_data($this->table, $data_arr, $this->primary_key);
        else:
            $data   = array('PULLED' => 'Y', 'UPDATED_BY' => 'API HIT');
            $update = $this->crud->update($this->table, $data, array($this->primary_key => $id), false);
        endif;

        if($update > 0){

            $message = [
                        'status'  => true,
                        'message' => 'Data has been updated'
                    ];

            $this->set_response($message, REST_Controller::HTTP_OK);

        }
        elseif($update === 0){

            $message = [
                        'status'  => true,
                        'message' => 'No data updated'
                    ];

            $this->set_response($message, REST_Controller::HTTP_OK);

        }else{

            $message = [
                        'status'  => false,
                        'message' => 'Failed to update data'
                    ];

            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }

    }

}

/* End of file Api_gr_ctl.php */
/* Location: ./application/controllers/api/Api_gr_ctl.php */