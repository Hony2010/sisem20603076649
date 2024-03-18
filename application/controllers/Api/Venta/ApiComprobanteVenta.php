<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiComprobanteVenta extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Venta/RestApiComprobanteVenta');
		$this->load->service("Seguridad/sSeguridad");
    }

    public function ObtenerDataComprobanteParaValidarJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data = json_decode($data, true);

        $response = $this->restapicomprobanteventa->ObtenerDataComprobanteParaValidarJSON($data);
        if($response){
            // Set the response and exit
            if(is_array($response))
            {
                $this->response([
                        'status' => TRUE,
                        'message' => 'La consulta se ha realizado.',
                        'data' => $response
                    ], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response($response, REST_Controller::HTTP_OK);
            }
        }else{
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response("No se pudo obtener consulta.", REST_Controller::HTTP_OK);
        }
    }

    public function InsertarVentaJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data2 = json_decode($data, true);

        $session = $this->sSeguridad->IniciarSesionApi($data2);
        if(is_array($session))
        {
            $response = $this->restapicomprobanteventa->InsertarVentaJSON($data2);
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
                    // $this->response([
                    //     'status' => TRUE,
                    //     'message' => 'Se detectaron llas siguientes observaciones.',
                    //     'error' => $response
                    // ], REST_Controller::HTTP_OK);
                    $this->response($response, REST_Controller::HTTP_OK);
                }
            }else{
                $this->session->sess_destroy();
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response($response, REST_Controller::HTTP_OK);
            }
        }
        else
        {
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code            
            $this->response($session, REST_Controller::HTTP_OK);            
        }
    }

    public function AnularVentaJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data = json_decode($data, true);

        $session = $this->sSeguridad->IniciarSesionApi($data);
        if(is_array($session))
        {
            $response = $this->restapicomprobanteventa->AnularVentaJSON($data);
            if($response){
                // Set the response and exit
                $this->session->sess_destroy();
                if(is_array($response))
                {
                    $this->response([
                          'status' => TRUE,
                          'message' => 'El comprobante ha sido anulado.',
                          'data' => $response
                      ], REST_Controller::HTTP_OK);
                }
                else
                {
                    // $this->response([
                    //     'status' => TRUE,
                    //     'message' => 'Se detectaron llas siguientes observaciones.',
                    //     'error' => $response
                    // ], REST_Controller::HTTP_OK);
                    $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
                }
            }else{
                $this->session->sess_destroy();
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response("No se pudo anular la venta.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response($session, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function EliminarVentaJSON_post() {
        // Get the post data
        $data = $this->post('Data');
        $data = json_decode($data, true);

        $session = $this->sSeguridad->IniciarSesionApi($data);
        if(is_array($session))
        {
            $response = $this->restapicomprobanteventa->EliminarVentaJSON($data);
            if($response){
                // Set the response and exit
                $this->session->sess_destroy();
                if(is_array($response))
                {
                    $this->response([
                          'status' => TRUE,
                          'message' => 'El comprobante ha sido eliminado.',
                          'data' => $response
                      ], REST_Controller::HTTP_OK);
                }
                else
                {
                    // $this->response([
                    //     'status' => TRUE,
                    //     'message' => 'Se detectaron llas siguientes observaciones.',
                    //     'error' => $response
                    // ], REST_Controller::HTTP_OK);
                    $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
                }
            }else{
                $this->session->sess_destroy();
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response("No se pudo eliminar la venta.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            $this->response($session, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
