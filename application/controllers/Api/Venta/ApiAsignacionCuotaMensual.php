<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiAsignacionCuotaMensual extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Venta/RestApiAsignacionCuotaMensual');
    }

    public function AgregarAsignacionesCuotaMensualJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data = json_decode($data, true);

        $response = $this->restapiasignacioncuotamensual->AgregarAsignacionesCuotaMensual($data);
        if($response){
            // Set the response and exit
            $this->session->sess_destroy();
            if(is_array($response))
            {
                $this->response([
                        'status' => TRUE,
                        'message' => 'El comprobante ha sido registrado.',
                        'data' => $response
                    ], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $this->session->sess_destroy();
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response("No se pudo registrar la venta.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ConsultarAsignacionesCuotaMensualJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data = json_decode($data, true);

        $response = $this->restapiasignacioncuotamensual->ConsultarAsignacionesCuotaMensual($data);
        if($response){
            // Set the response and exit
            $this->session->sess_destroy();
            if(is_array($response))
            {
                $this->response([
                        'status' => TRUE,
                        'message' => 'El comprobante ha sido registrado.',
                        'data' => $response
                    ], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $this->session->sess_destroy();
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response("No se pudo registrar la venta.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ConsultarAsignacionesCuotaMensualParaReporteJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data = json_decode($data, true);

        $response = $this->restapiasignacioncuotamensual->ConsultarAsignacionesCuotaMensualParaReporte($data);
        if($response){
            // Set the response and exit
            $this->session->sess_destroy();
            if(is_array($response))
            {
                $this->response([
                        'status' => TRUE,
                        'message' => 'Los datos fueron consultados correctamente.',
                        'data' => $response
                    ], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $this->session->sess_destroy();
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response("No se pudo registrar la venta.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
