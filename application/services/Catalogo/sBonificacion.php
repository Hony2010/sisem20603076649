<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sBonificacion extends MY_Service {

  public $Bonificacion = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->model('Catalogo/mBonificacion');
        $this->Bonificacion = $this->mBonificacion->Bonificacion;
  }

  function Cargar()
  {
    $this->Bonificacion["IdBonificacion"] = "";
    $this->Bonificacion["IdProducto"] = "";
    $this->Bonificacion["IdProductoBonificacion"] = "";
    $this->Bonificacion["Cantidad"] = "";
    $this->Bonificacion["CantidadBonificacion"] = "";

    $data = array();
    $resultado = array_merge($this->Bonificacion, $data);
    return $resultado;
  }

  /**CRUD*/
  function InsertarBonificacion($data)
  {
    $resultado = $this->mBonificacion->InsertarBonificacion($data);
    return $resultado;
  }

  function ActualizarBonificacion($data)
  {
    $resultado = $this->mBonificacion->ActualizarBonificacion($data);
    return $resultado;
  }
  
  function BorrarBonificacion($data)
  {
    $resultado = $this->mBonificacion->BorrarBonificacion($data);
    return $resultado;
  }
  
  function BorrarBonificacionesPorIdProducto($data)
  {
    $resultado = $this->mBonificacion->BorrarBonificacionesPorIdProducto($data);
    return $resultado;
  }
  /**FIN CRUD*/

  function ListarBonificaciones()
  {
    $resultado = $this->mBonificacion->ListarBonificaciones();
    return $resultado;
  }

  function ListarBonificacionesPorIdProducto($data)
  {
    $resultado = $this->mBonificacion->ListarBonificacionesPorIdProducto($data);
    return $resultado;
  }
  
  //Se añade o devuelve el resultado encontrado
  // function AgregarBonificacion($data)
  // {
  //   $resultado = $this->mBonificacion->ObtenerBonificacionPorHoraInicioYFin($data);
  //   if(count($resultado) > 0)
  //   {
  //     return $resultado[0];
  //   }
  //   else
  //   {
  //     $data["IdBonificacion"] = "";
      
  //     $response = $this->InsertarBonificacion($data);
  //     return $response;
  //   }
  // }
  function AgregarBonificaciones($data)
  {
    //Borramos notificaciones
    $this->BorrarBonificacionesPorIdProducto($data);
    $bonificaciones = array();
    foreach ($data["Bonificaciones"] as $key => $value) {
      $value["IdBonificacion"] = "";
      $value["IdProducto"] = $data["IdProducto"];
      $response = $this->InsertarBonificacion($value);
      array_push($bonificaciones, $response);
    }
    return $bonificaciones;
  }

}
