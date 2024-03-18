<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiCorrelativoDocumento extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Configuracion/General/RestApiCorrelativoDocumento');
    }

    public function CosultarCorrelativoDocumentoParaJSON_post() {
        // Get the post data
        $data = $this->post('data');
        $data = json_decode($data, true);
        
        $response = $this->restapicorrelativodocumento->CosultarCorrelativoDocumentoParaJSON($data);

        if(is_array($response)){
            // Set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => 'Se realizo la consulta.',
                'data' => $response
            ], REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ListarCorrelativosDocumento_post() {
        // Get the post data
        //$data = $this->post('data');
        //$data = json_decode($data, true);
        
        $response = $this->restapicorrelativodocumento->ListarCorrelativosDocumento();

        if(is_array($response)){
            // Set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => 'Se realizo la consulta.',
                'data' => $response
            ], REST_Controller::HTTP_OK);
        }else{
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
