<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiListaPrecioMercaderia extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Venta/RestApiListaPrecioMercaderia');
		$this->load->service("Seguridad/sSeguridad");
    }

    public function ConsultarTodosListasPrecioMercaderia_post() {
        // Get the post data
        //$data = $this->post('Data');
        //$data = json_decode($data, true);        
        
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Error La consulta precios mercaderias se ha realizado.',
        //     'data' => array()
        // ], REST_Controller::HTTP_OK);

        $response = $this->restapilistapreciomercaderia->ConsultarTodosListasPrecioMercaderia();
            // Set the response and exit
        if(is_array($response)) {
            
            $this->response([
                    'status' => TRUE,
                    'message' => 'La consulta precios mercaderias se ha realizado.',
                    'data' => $response
                ], REST_Controller::HTTP_OK);
        }
        else {
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }


}
