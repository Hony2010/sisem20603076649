<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAnotacionPlatoProducto extends MY_Service {

  public $AnotacionPlatoProducto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('jsonconverter');
    $this->load->model('Catalogo/mAnotacionPlatoProducto');
    $this->load->service('Catalogo/sAnotacionPlato');
    $this->AnotacionPlatoProducto = $this->mAnotacionPlatoProducto->AnotacionPlatoProducto;
  }

  function InsertarAnotacionPlatoProducto($data)
  {
    $resultado = $this->mAnotacionPlatoProducto->InsertarAnotacionPlatoProducto($data);
    return $resultado;
  }

  function ActualizarAnotacionPlatoProducto($data)
  {
    $resultado = $this->mAnotacionPlatoProducto->ActualizarAnotacionPlatoProducto($data);
    return $resultado;
  }

  function BorrarAnotacionPlatoProducto($data)
  {
    $resultado = $this->mAnotacionPlatoProducto->BorrarAnotacionPlatoProducto($data);
    return $resultado;
  }

  //FUNCIONES DE CARGA DE OPCIONES
  function ListarAnotacionesPlatoProductoInicial()
  {
    $resultado = $this->sAnotacionPlato->ListarAnotacionesPlato();
    foreach ($resultado as $key => $value) {
      $response = array_merge($this->AnotacionPlatoProducto, $value);
      $response["Seleccionado"] = false;
      $resultado[$key] = $response;
    }
    return $resultado;
  }

  function ListarAnotacionesPlatoProductoPorProducto($data)
  {
    $resultado = $this->sAnotacionPlato->ListarAnotacionesPlato();
    $resultadoProducto = $this->mAnotacionPlatoProducto->ObtenerPlatoProductoPorIdProducto($data);
    foreach ($resultado as $key => $value) {
      $response = array_merge($this->AnotacionPlatoProducto, $value);
      $response["Seleccionado"] = false;
      $resultado[$key] = $response;
      foreach ($resultadoProducto as $key2 => $value2) {
        if($value["IdAnotacionPlato"] == $value2["IdAnotacionPlato"])
        {
          $resultado[$key]["Seleccionado"] = ($value2["EstadoOpcion"] == '1') ? true : false;
        }
      }
    }
    return $resultado;
  }
  
  function ListarAnotacionesPlatoProductoPorIdProducto($data)
  {
    $resultado = $this->mAnotacionPlatoProducto->ObtenerAnotacionesPlatoProductoPorIdProducto($data);
    return $resultado;
  }

  //FUNCIONES DE AGREGADO
  function AgregarAnotacionPlatoProducto($data)
  {
    $resultado = $this->mAnotacionPlatoProducto->ObtenerAnotacionPlatoProductoPorProductoYAnotacionPlato($data);
    if(count($resultado) > 0)
    {
      $data["IdAnotacionPlatoProducto"] = $resultado[0]["IdAnotacionPlatoProducto"];
      $response = $this->mAnotacionPlatoProducto->ActualizarAnotacionPlatoProducto($data);
      return $response;
    }
    else{
      $data["IdAnotacionPlatoProducto"] = "";
      $response = $this->mAnotacionPlatoProducto->InsertarAnotacionPlatoProducto($data);
      return $response;
    }
  }

  function AgregarAnotacionesPlatoProducto($data)
  {
    $resultado = $data["AnotacionesPlatoProducto"];
    foreach ($resultado as $key => $value) {
      $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
      $resultado[$key]["Seleccionado"] = $seleccionado;

      $value["EstadoOpcion"] = ($seleccionado) ? '1' : '0';
      $value["IdProducto"] = $data["IdProducto"];
      $response = $this->AgregarAnotacionPlatoProducto($value);
    }

    return $resultado;
  }

  function BorrarAnotacionesPlatoProductoPorIdProducto($data)
  {
    $resultado = $this->mAnotacionPlatoProducto->BorrarAnotacionesPlatoProductoPorIdProducto($data);
    return $resultado;
  }

}
