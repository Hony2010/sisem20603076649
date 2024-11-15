<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApi {
  private $API_KEY = 'CODEX@123';
  private $API_USER = 'admin';
  private $API_PASS = '1234';
  private $API_URL = '';

  function __construct()
  {
      //parent::__construct();
      /*Additional code which you want to run automatically in every function call */
      if (!isset($this->CI))
      {
          $this->CI =& get_instance();
      }
      // $this->CI->load->service('Configuracion/General/sEmpresa');
  }

  function ConsultarDataApi($url, $data)
  {
    try {
      $this->API_URL = $url;
      // Create a new cURL resource
  		$ch = curl_init($this->API_URL);

  		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
  		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $this->API_KEY));
  		curl_setopt($ch, CURLOPT_USERPWD, "$this->API_USER:$this->API_PASS");
  		curl_setopt($ch, CURLOPT_POST, 1);
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

  		$result = curl_exec($ch);
  		// Close cURL resource
  		curl_close($ch);

  		return $result;
    } catch (Exception $e) {

    }
  }

function obtenerToken($client_id,$client_secret,$username,$password) {
 
   $this->API_URL = "https://api-seguridad.sunat.gob.pe/v1/clientessol/$client_id/oauth2/token/";

   $parseoData = array(
      'grant_type' =>"password",
      'scope'=>"https://api-cpe.sunat.gob.pe",
      'client_id'=>$client_id,
      'client_secret'=>$client_secret,
      'username'=>$username,
      'password'=>$password
    );  
    
    $datos=http_build_query($parseoData);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->API_URL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $datos,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));

    $result = curl_exec($curl);

    // Close cURL resource
    curl_close($curl);

    $resultado = json_decode($result, true); 

    return $resultado;
}

function enviarGuiaXML($url, $data,$client_id,$client_secret,$username,$password) {
  try {
    
    $this->API_URL = $url;
    
    $parseoData = array(
      'archivo' =>$data
    );      
   // echo "antes de token<br>";
   $tokens = $this->obtenerToken($client_id,$client_secret,$username,$password);
   $token=$tokens["access_token"];

   $headers = array(
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
          );

   $datos=json_encode($parseoData);

    // Create a new cURL resource
   $curl = curl_init();
   curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $datos,
    CURLOPT_HTTPHEADER => $headers)
    );
    
    $response = curl_exec($curl);
    curl_close($curl);

//      echo $datos;      
//      print_r($response);
//      exit;
    $resultado = json_decode($response, true);              

    return $resultado;

  } catch (Exception $e) {
      throw new Exception($e->getMessage());      
  }
}


function obtenerCDRGuiaRemision($ticket,$client_id,$client_secret,$username,$password) {
  try {
           
   $url = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/$ticket";    
   
   $tokens = $this->obtenerToken($client_id,$client_secret,$username,$password);
   $token=$tokens["access_token"];

   $headers = array(
            'Authorization: Bearer '.$token
          );

   // Create a new cURL resource
   $curl = curl_init();
   curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => $headers)
    );
    
    $response = curl_exec($curl);
    curl_close($curl);

    //echo $datos;
    //print_r($response);
    //exit;
    $resultado = json_decode($response, true);              

    return $resultado;

  } catch (Exception $e) {
      throw new Exception($e->getMessage());      
  }
}

}
