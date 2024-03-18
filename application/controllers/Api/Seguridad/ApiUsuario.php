<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
// use namespace
use Restserver\Libraries\REST_Controller;
class ApiUsuario extends REST_Controller {

    public function __construct() {
        parent::__construct();

        // Load the user model
    	$this->load->library('RestApi/Seguridad/RestApiUsuario');
    }

    public function PreparaDataUsuarios_post() {
        // Get the post data
        $data = $this->post('data');
        $data = json_decode($data, true);
        
        $response = $this->restapiusuario->PreparaDataUsuarios();
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
            $this->response("No response data.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ListarUsuarios_post() {
        // Get the post data
        $data = $this->post('data');
        $data = json_decode($data, true);
        
        $response = $this->restapiusuario->ListarUsuarios();
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
            $this->response("No response data.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function InsertarUsuario_post() {
        // Get the post data
        $content = $this->post('Data');//trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Se realizo la consulta.',
        //     'data' => $data
        // ], REST_Controller::HTTP_OK);exit;

        // $data = $this->post('data');
        // $data = json_decode($data, true);
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Se realizo la consulta.',
        //     'data' => $data
        // ], REST_Controller::HTTP_OK);exit;
        // $data = json_decode($data, true);
        $response = $this->restapiusuario->InsertarUsuario($data);
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
            $this->response($response, REST_Controller::HTTP_OK);
        }
    }

    public function ActualizarUsuario_post() {
        // Get the post data
        $content = $this->post('Data');//trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Se realizo la consulta.',
        //     'data' => $data
        // ], REST_Controller::HTTP_OK);exit;

        // $data = $this->post('data');
        // $data = json_decode($data, true);
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Se realizo la consulta.',
        //     'data' => $data
        // ], REST_Controller::HTTP_OK);exit;
        // $data = json_decode($data, true);
        $response = $this->restapiusuario->ActualizarUsuario($data);
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
            $this->response($response, REST_Controller::HTTP_OK);
        }
    }

    public function BorrarUsuario_post() {
        // Get the post data
        $content = $this->post('Data');//trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Se realizo la consulta.',
        //     'data' => $data
        // ], REST_Controller::HTTP_OK);exit;

        // $data = $this->post('data');
        // $data = json_decode($data, true);
        // $this->response([
        //     'status' => TRUE,
        //     'message' => 'Se realizo la consulta.',
        //     'data' => $data
        // ], REST_Controller::HTTP_OK);exit;
        // $data = json_decode($data, true);
        $response = $this->restapiusuario->BorrarUsuario($data);
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
            $this->response($response, REST_Controller::HTTP_OK);
        }
    }

}
