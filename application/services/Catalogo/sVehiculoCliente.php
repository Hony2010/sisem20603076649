<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sVehiculoCliente extends MY_Service
{

  public $VehiculoCliente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Catalogo/mVehiculoCliente');
    $this->load->service('Catalogo/sVehiculo');
    $this->VehiculoCliente = $this->mVehiculoCliente->VehiculoCliente;
  }

  function Inicializar()
  {
    $Vehiculo = $this->sVehiculo->Inicializar();
    $resultado = array_merge($this->VehiculoCliente, $Vehiculo);
    return $resultado;
  }

  function InsertarVehiculoCliente($data)
  {
    $vehiculo = $this->sVehiculo->AgregarVehiculo($data);
    $data["IdVehiculo"] = $vehiculo["IdVehiculo"];

    $resultado = $this->mVehiculoCliente->InsertarVehiculoCliente($data);
    $resultado = array_merge($vehiculo, $resultado);
    return $resultado;
  }

  function ActualizarVehiculoCliente($data)
  {
    $vehiculo = $this->sVehiculo->AgregarVehiculo($data);
    $data["IdVehiculo"] = $vehiculo["IdVehiculo"];

    $resultado = $this->mVehiculoCliente->ActualizarVehiculoCliente($data);
    $resultado = array_merge($vehiculo, $resultado);
    return $resultado;
  }

  function BorrarVehiculoCliente($data)
  {
    $resultado = $this->mVehiculoCliente->BorrarVehiculoCliente($data);
    return $resultado;
  }

  function AgregarVehiculoClienteDesdeVenta($data)
  {
    $resultado = $this->sVehiculo->AgregarVehiculoDesdeVenta($data);

    $resultado["IdCliente"] = $data["IdCliente"];
    $resultado["IdPersona"] = $data["IdCliente"];
    $consulta = $this->mVehiculoCliente->ConsultarVehiculoClientePorIdClienteYIdVehiculo($resultado);
    if(count($consulta) > 0)
    {
      //NO PASA NADA
    }
    else
    {
      $vehiculocliente = $this->mVehiculoCliente->InsertarVehiculoCliente($resultado);
    }

    return $resultado;
  }

  function ConsultarVehiculosClientePorIdCliente($data)
  {
    $resultado = $this->mVehiculoCliente->ConsultarVehiculosClientePorIdCliente($data);
    return $resultado;
  }

  //INSERTANDO DIRECCIONES CLIENTE
  function AgregarVehiculosClientes($data) {
    $data["IdCliente"] = $data["IdPersona"];
    //BORRAMOS TODAS LAS DIRECCIONES ANTERIORES
    $this->mVehiculoCliente->BorrarVehiculosPorIdCliente($data);

    $resultado = array();    
    $i = 0;
    
    if(!array_key_exists("VehiculosCliente", $data)) {
      $data["VehiculosCliente"] = array();
    }
    
    foreach ($data["VehiculosCliente"] as $key => $value) {
      $value["IdVehiculoCliente"] = "";
      $value["IdCliente"] = $data["IdCliente"];
      // $value["NumeroOrdenDireccion"] = $i;
      $response = $this->InsertarVehiculoCliente($value);
      array_push($resultado, $response);
      $i++;
    }
    return $resultado;
  }
}
