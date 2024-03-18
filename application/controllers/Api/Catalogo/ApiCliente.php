<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiCliente extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Catalogo/RestApiCliente');
    }

    public function ConsultarClientesEnVentasJSON_post() {
        // Get the post data
        $data = $this->post('data');
        $data = json_decode($data, true);
        
        $response = $this->restapicliente->ConsultarClientesEnVentasJSON($data);

        if($response){
            // Set the response and exit
            $this->response([
                  'status' => TRUE,
                  'message' => 'Se realizo la consulta.',
                  'data' => $response
              ], REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response("Datos No Actualizados.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}