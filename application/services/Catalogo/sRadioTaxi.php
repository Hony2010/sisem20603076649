<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sRadioTaxi extends MY_Service
{

  public $RadioTaxi = array();
  
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('archivo');
    $this->load->library('jsonconverter');
    $this->load->model('Catalogo/mRadioTaxi');
    $this->RadioTaxi = $this->mRadioTaxi->RadioTaxi;
  }

  function Inicializar()
  {
    $this->RadioTaxi["NombreRadioTaxi"] = "";
    return $this->RadioTaxi;
  }

  function ListarRadioTaxis()
  {
    $resultado = $this->mRadioTaxi->ListarRadioTaxis();
    return $resultado;
  }

  function ValidarRadioTaxi($data, $actualizar = false)
  {
    $data["NombreRadioTaxi"] = trim($data["NombreRadioTaxi"]);
    $duplicado = ($actualizar) ? $this->mRadioTaxi->ObtenerRadioTaxiPorNombreActualizar($data) : $this->mRadioTaxi->ObtenerRadioTaxiPorNombre($data);
    if($data["NombreRadioTaxi"] == "")
    {
      return "Debe insertar un nombre de Radio Taxi.";
    }
    elseif(count($duplicado) > 0)
    {
      return "El Nombre de Radio Taxi ya esta registrado.";
    }
    else{
      return "";
    }
  }

  function InsertarRadioTaxi($data)
  {
    $validacion = $this->ValidarRadioTaxi($data);
    if($validacion != "")
    {
      return $validacion;
    }
    else
    {
      $resultado = $this->mRadioTaxi->InsertarRadioTaxi($data);
      $json = $this->InsertarJSONDesdeRadioTaxi($resultado);
      return $resultado;
    }
  }

  function ActualizarRadioTaxi($data)
  {
    $validacion = $this->ValidarRadioTaxi($data, true);
    if($validacion != "")
    {
      return $validacion;
    }
    else
    {
      $resultado = $this->mRadioTaxi->ActualizarRadioTaxi($data);
      $json = $this->ActualizarJSONDesdeRadioTaxi($resultado);
      return $resultado;
    }
  }

  function BorrarRadioTaxi($data)
  {
    $resultado = $this->mRadioTaxi->BorrarRadioTaxi($data);
    $json = $this->BorrarJSONDesdeRadioTaxi($resultado);
    return $resultado;
  }

  function PrepararFilaRadioTaxiParaJSON($data)
  {
    $response = array(
      "IdRadioTaxi" => $data["IdRadioTaxi"],
      "NombreRadioTaxi" => $data["NombreRadioTaxi"]
    );
    return $response;
  }

  function CrearJSONRadioTaxiTodos()
  {
    $url = DIR_ROOT_ASSETS . '/data/radiotaxi/radiotaxi.json';
    $data_json = $this->mRadioTaxi->ListarRadioTaxis();
    foreach ($data_json as $key => $value) {
      $response = $this->PrepararFilaRadioTaxiParaJSON($value);
      $data_json[$key] = $response;
    }
    
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
    return $resultado;
  }
  
  //PARA VEHICULO AVANZADO
  function InsertarJSONDesdeRadioTaxi($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/radiotaxi/radiotaxi.json';
    $vehiculo = $this->mRadioTaxi->ObtenerRadioTaxiPorIdRadioTaxi($data);
    $fila = $this->PrepararFilaRadioTaxiParaJSON($vehiculo);
    $resultado = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);
    return $resultado;
  }

  function ActualizarJSONDesdeRadioTaxi($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/radiotaxi/radiotaxi.json';
    $vehiculo = $this->mRadioTaxi->ObtenerRadioTaxiPorIdRadioTaxi($data);
    $fila = $this->PrepararFilaRadioTaxiParaJSON($vehiculo);
    $resultado = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdRadioTaxi");
    return $resultado;
  }

  function BorrarJSONDesdeRadioTaxi($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/radiotaxi/radiotaxi.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdRadioTaxi");
    return $resultado;
  }
}
