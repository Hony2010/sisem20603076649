<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once("ConsultaRENIEC/autoload.php");

class ConsultaDatosRENIEC{

  public $CI;

  function __construct()
  {
      //parent::__construct();
      /*Additional code which you want to run automatically in every function call */
      if (!isset($this->CI))
      {
          $this->CI =& get_instance();
      }
  }

  function BuscarPorNumeroDocumentoIdentidad($data)
  {
    $cliente = new \Reniec\Reniec(true,true);

  	$ruc = $data["NumeroDocumentoIdentidad"];
    $resultado =  $cliente->search( $ruc, true );
    return json_decode($resultado, true);

  }
}
