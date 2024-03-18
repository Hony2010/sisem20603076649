<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDireccionCliente extends MY_Service {

  public $DireccionCliente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->model('Configuracion/Catalogo/mDireccionCliente');
    $this->DireccionCliente = $this->mDireccionCliente->DireccionCliente;
    $this->DireccionCliente["EstadoDireccion"] = ESTADO_SIN_CAMBIOS_DIRECCION_CLIENTE;
  }

  function NuevaDireccionCliente() {
    $this->DireccionCliente["IdDireccionCliente"]="";
    $this->DireccionCliente["IdCliente"]="";
    $this->DireccionCliente["NumeroOrdenDireccion"]=1;
    $this->DireccionCliente["EstadoDireccion"]=ESTADO_INSERTADO_DIRECCION_CLIENTE;
    return $this->DireccionCliente;
  }

  function InsertarDireccionCliente($data) {    
      $item = $this->mDireccionCliente->ObtenerNumeroOrdenMaximoIdCliente($data);
      
      if (count($item) > 0) {
        $data["NumeroOrdenDireccion"] = $item[0]["NumeroOrdenDireccionMaximo"] + 1;
      }
      else {
        $data["NumeroOrdenDireccion"] = 0;
      }

      $resultado = $this->mDireccionCliente->InsertarDireccionCliente($data);
      
      return $resultado;    
  }

  function ActualizarDireccionCliente($data)
  {
    $resultado = $this->mDireccionCliente->ActualizarDireccionCliente($data);
    return $resultado;
  }

  function BorrarDireccionCliente($data)
  {
    $resultado = $this->mDireccionCliente->BorrarDireccionCliente($data);
    return $resultado;
  }

  function ConsultarDireccionesCliente($data)
  {
    $resultado=$this->mDireccionCliente->ConsultarDireccionesCliente($data);    
    return $resultado;
  }

  function ConsultarDireccionesClienteParaJSON($data)
  {
    $resultado=$this->mDireccionCliente->ConsultarDireccionesClienteParaJSON($data);
    return $resultado;
  }

  function ObtenerDireccionClientePorIdClienteYDireccion($data)
  {
    $resultado=$this->mDireccionCliente->ObtenerDireccionClientePorIdClienteYDireccion($data);    
    return $resultado;
  }

  //INSERTANDO DIRECCIONES CLIENTE
  function AgregarDireccionesClientes($data)
  {    
    $data["IdCliente"] = $data["IdPersona"];
    //BORRAMOS TODAS LAS DIRECCIONES ANTERIORES
    $this->mDireccionCliente->BorrarDireccionesPorIdCliente($data);

    $resultado = array();
    //INSERTAMOS LAS DIRECCIONES DEL CLIENTE UN POR UNO 
    $i = 0;
    foreach ($data["DireccionesCliente"] as $key => $value) {
      $value["IdDireccionCliente"] = "";
      $value["IdCliente"] = $data["IdCliente"];
      $value["NumeroOrdenDireccion"] = $i;
      $response = $this->InsertarDireccionCliente($value);
      array_push($resultado, $response);
      $i++;
    }
    return $resultado;
  }
  
  function ActualizarDireccionesCliente($data) {
    try {
      $resultado =""; 
      $parametro=$this->sConstanteSistema->ObtenerParametroDireccionCliente();    
      $data["IdCliente"] = $data["IdPersona"];

      if(!array_key_exists("DireccionesCliente", $data)) {    
        $data["DireccionesCliente"] = array();
      }

      if(!array_key_exists("DireccionesClienteBorrado", $data)) {    
        $data["DireccionesClienteBorrado"] = array();
      }

      $data["DireccionesClienteCompleto"] = array_merge($data["DireccionesCliente"],$data["DireccionesClienteBorrado"]);

      if ($parametro == 0) { // cuando solo se ingresa una direccion
        $resultado = $this->ObtenerDireccionClientePorIdClienteYDireccion($data);
        if(count($resultado) == 0) {
          $data["IdDireccionCliente"] = "";
          $this->InsertarDireccionCliente($data);
          return "";
        }
      }
      else {// cuando solo se ingresa varias direcciones
        $resultado = $this->ValidarDireccionesCliente($data);
        
        if ($resultado == "") {
          
          foreach ($data["DireccionesClienteCompleto"] as $key => $value) {
            $value["IdCliente"] = $data["IdCliente"];
          
            switch($value["EstadoDireccion"]) {
              case ESTADO_SIN_CAMBIOS_DIRECCION_CLIENTE: break;
              case ESTADO_INSERTADO_DIRECCION_CLIENTE: 
                $value["IdDireccionCliente"] = "";    
                $this->InsertarDireccionCliente($value); break;
              case ESTADO_ACTUALIZADO_DIRECCION_CLIENTE:  $this->ActualizarDireccionCliente($value); break;
              case ESTADO_BORRADO_DIRECCION_CLIENTE:                 
                $this->BorrarDireccionCliente($value); break;
            }
          }

          return "";
        }
        else {
          return $resultado;
        }              
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    } 
  }

  function InsertarDireccionesCliente($data) {
    
    $resultado ="";    
    $parametro = $this->sConstanteSistema->ObtenerParametroDireccionCliente();    
    $data["IdCliente"] = $data["IdPersona"];
        
    if(!array_key_exists("DireccionesCliente", $data)) {    
      $data["DireccionesCliente"] = array();
    }
      
    $data["DireccionesClienteCompleto"] = $data["DireccionesCliente"];

    if ($parametro == 0) { // cuando solo se ingresa una direccion
      $resultado = $this->ObtenerDireccionClientePorIdClienteYDireccion($data);
      if(count($resultado) == 0) {
        $data["IdDireccionCliente"] = "";
        $this->InsertarDireccionCliente($data);
        return "";
      }
    }
    else {// cuando solo se ingresa varias direcciones
      $resultado = $this->ValidarDireccionesCliente($data);
      
      if ($resultado == "") {
        
        foreach ($data["DireccionesClienteCompleto"] as $key => $value) {
          $value["IdCliente"] = $data["IdCliente"];
        
          switch($value["EstadoDireccion"]) {
            case ESTADO_INSERTADO_DIRECCION_CLIENTE: 
              $value["IdDireccionCliente"] = "";    
              $this->InsertarDireccionCliente($value); break;
          }
        }

        return "";
      }
      else {
        return $resultado;
      }    
    }
            
  }

  function ValidarDuplicidadDireccionesCliente($data) {
    foreach ($data["DireccionesCliente"] as $key => $value) {
      if ($value["EstadoDireccion"] != ESTADO_BORRADO_DIRECCION_CLIENTE )       {
        $direccion = $value["Direccion"];        
        foreach ($data["DireccionesCliente"] as $key2 => $value2) {          
          $direccion2 = $value2["Direccion"];
          
          if ($key != $key2) {
            if ($value2["EstadoDireccion"] != ESTADO_BORRADO_DIRECCION_CLIENTE )  {
              if (strtoupper($direccion) == strtoupper($direccion2) ) {                
                $resultado = "Ya existe la dirección ".$direccion2.", por favor cambiar de dirección\n";
                return $resultado;
              }
            }
          }

        }
      }
    }

    return "";
  }

  function ValidarDireccionesCliente($data) {
    $validacion1 = $this->ValidarDuplicidadDireccionesCliente($data) ;
    return $validacion1;
  }

  function ConsultarDireccionesClientePorIdCliente($data) {
    $resultado = $this->mDireccionCliente->ConsultarDireccionesClientePorIdCliente($data);
    return $resultado;   // 
  }
}
