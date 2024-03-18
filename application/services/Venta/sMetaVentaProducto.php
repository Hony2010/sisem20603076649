<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMetaVentaProducto extends MY_Service {

  public $MetaVentaProducto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Base");
    $this->load->model('Venta/mMetaVentaProducto');
    $this->MetaVentaProducto = $this->mMetaVentaProducto->MetaVentaProducto;
  }

  function Cargar()
  {
    $this->MetaVentaProducto["CodigoMercaderia"] = '';
    $resultado = $this->MetaVentaProducto;
    return $resultado;
  }
  
  function InsertarMetaVentaProducto($data)
  {
    $resultado = $this->mMetaVentaProducto->InsertarMetaVentaProducto($data);
    return $resultado;
  }

  function ActualizarMetaVentaProducto($data)
  {
    $resultado = $this->mMetaVentaProducto->ActualizarMetaVentaProducto($data);
    return $resultado;
  }

  function BorrarMetaVentaProducto($data)
  {
    $resultado = $this->mMetaVentaProducto->BorrarMetaVentaProducto($data);
    return $resultado;
  }

  function ObtenerMetaVentaProducto($data) {
    $resultado = $this->mMetaVentaProducto->ObtenerMetaVentaProductoPorIdProducto($data);
    return $resultado;
  }


  function AgregarMetaVentaProducto($data)
  {
    $data["MetaCantidad"] = (is_string($data["MetaCantidad"])) ? str_replace(',',"",$data["MetaCantidad"]) : $data["MetaCantidad"];
    $data["PorcentajeComisionVenta"] = (is_string($data["PorcentajeComisionVenta"])) ? str_replace(',',"",$data["PorcentajeComisionVenta"]) : $data["PorcentajeComisionVenta"];

    $resultado = $this->mMetaVentaProducto->ObtenerMetaVentaProductoPorIdProducto($data);
    if(count($resultado) > 0)
    {
      $data["IdMetaVentaProducto"] = $resultado[0]["IdMetaVentaProducto"];
      $response = $this->ActualizarMetaVentaProducto($data);
      return $response;
    }
    else
    {
      $data["IdMetaVentaProducto"] = "";
      $response = $this->InsertarMetaVentaProducto($data);
      return $response;
    }
  }

  //VALIDACIONES
  function ValidarMetasVentaProducto($data)
  {
    $ids = $this->ObtenerIdsProducto($data);
    $unicos = array_unique($ids);
    if(count($ids) > count($unicos)){
      return "¡Hay productos repetidos repetidos!";
    }else{
      return "";
    }
  }

  //INSERTAR CUOTA MENSUAL POR USUARIO
  function AgregarMetasVentaProducto($data)
  {
    $validacion = $this->ValidarMetasVentaProducto($data);
    if($validacion != "")
    {
      return $validacion;
    }

    foreach ($data as $key => $value) {
      $data[$key] = $this->AgregarMetaVentaProducto($value);
    }
    
    $ids = $this->ObtenerIdsProductoParaFiltrar($data);
    $this->mMetaVentaProducto->BorrarMetasVentaProductoNoListados($ids);
    return $data;
  }

  function ObtenerIdsProducto($data)
  {
    $ids = array();
    foreach ($data as $key => $value) {
      array_push($ids, $value["IdProducto"]);
    }
    return $ids;
  }

  function ObtenerIdsProductoParaFiltrar($data)
  {
    $ids = $this->ObtenerIdsProducto($data);
    $ids = array_unique($ids);
    $cadena = "'".implode("','", $ids)."'";
    return $cadena;
  }

  //CONSULTAR CUOTAS MENSUALES POR USUARIO
  function ConsultarMetasVentaProducto()
  {
    $resultado = $this->mMetaVentaProducto->ConsultarMetasVentaProducto();
    return $resultado;
  }
}
