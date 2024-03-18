<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiMercaderia extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Catalogo/RestApiMercaderia');
    }

    public function ActualizarJSON_post() {
        // Get the post data
        $productos = $this->post('productos');
        $opcion = $this->post('opcion');
        $listas = $this->post('listas');
        $merca = $this->post('mercaderia');
        $data = json_decode($productos, true);

        $opcion = filter_var($opcion, FILTER_VALIDATE_BOOLEAN);
        $listas = filter_var($listas, FILTER_VALIDATE_BOOLEAN);
        $merca = filter_var($merca, FILTER_VALIDATE_BOOLEAN);
        $response = $this->restapimercaderia->ReemplazarFilasJSON($data, $opcion,$listas, $merca);

        if($response){
            // Set the response and exit
            $this->response([
                  'status' => TRUE,
                  'message' => 'Se actualizaron los productos.',
                  'data' => $productos
              ], REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response("Datos No Actualizados.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ConsultarMercaderiasEnVentasJSON_post() {
        // Get the post data
        $data = $this->post('data');
        $data = json_decode($data, true);
        
        $response = $this->restapimercaderia->ConsultarMercaderiasEnVentasJSON($data);

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
