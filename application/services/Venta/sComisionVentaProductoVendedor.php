<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComisionVentaProductoVendedor extends MY_Service {

  public $ComisionVentaProductoVendedor = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Base");
    $this->load->model('Venta/mComisionVentaProductoVendedor');
    $this->load->service('Venta/sMetaVentaProducto');
    $this->ComisionVentaProductoVendedor = $this->mComisionVentaProductoVendedor->ComisionVentaProductoVendedor;
  }


  function InsertarComisionVentaProductoVendedor($data) {
    $resultado = $this->mComisionVentaProductoVendedor->InsertarComisionVentaProductoVendedor($data);
    return $resultado;
  }

  function ActualizarComisionVentaProductoVendedor($data) {
    $resultado = $this->mComisionVentaProductoVendedor->ActualizarComisionVentaProductoVendedor($data);
    return $resultado;
  }
  
  function ObtenerComisionVentaProductoVendedor($data) {
    $resultado = $this->mComisionVentaProductoVendedor->ObtenerComisionVentaProductoVendedor($data);
    return $resultado;
  }

  function AumentarComisionVentaProductoVendedor($data) {
    $data["CantidadProducto"] = $data["Cantidad"];
    return $this->GuardarComisionVentaProductoVendedor($data);
  }

  function DisminuirComisionVentaProductoVendedor($data) {
    $data["CantidadProducto"] = -$data["Cantidad"];
    return $this->GuardarComisionVentaProductoVendedor($data);
  }

  function GuardarComisionVentaProductoVendedor($data) {

    $dataMetaVentaProducto = $this->sMetaVentaProducto->ObtenerMetaVentaProducto($data);
    
    if(count($dataMetaVentaProducto) > 0 ) { //determinar si hay politica de meta producto
      $dataComisionVentaProductoVendedor = $this->ObtenerComisionVentaProductoVendedor($data);
      
      if(count($dataComisionVentaProductoVendedor) > 0) { //determinar si existe de registro de comision.
        $data["NuevaCantidadReal"] = $dataComisionVentaProductoVendedor[0]["CantidadReal"] + $data["CantidadProducto"];
        $dataComisionVentaProductoVendedor[0]["MetaCantidad"] = $dataMetaVentaProducto[0]["MetaCantidad"];
        $dataComisionVentaProductoVendedor[0]["PorcentajeComisionVenta"] = $dataMetaVentaProducto[0]["PorcentajeComisionVenta"];
        $dataComisionVentaProductoVendedor[0]["NuevaCantidadReal"] = $data["NuevaCantidadReal"];
        $dataComisionVentaProductoVendedor = $this->CalcularComisionVentaProductoVendedor($dataComisionVentaProductoVendedor[0]);
        $resultado = $this->ActualizarComisionVentaProductoVendedor($dataComisionVentaProductoVendedor);
      }
      else {
        $data["NuevaCantidadReal"] = $data["Cantidad"];
        $data["MetaCantidad"] = $dataMetaVentaProducto[0]["MetaCantidad"];
        $data["PorcentajeComisionVenta"] = $dataMetaVentaProducto[0]["PorcentajeComisionVenta"];
        //$data["NuevaCantidadReal"] = $data["NuevaCantidadReal"];
        $dataComisionVentaProductoVendedor = $this->CalcularComisionVentaProductoVendedor($data);
        $resultado = $this->InsertarComisionVentaProductoVendedor($dataComisionVentaProductoVendedor);
      }      

      return $resultado;
    }
    else {
      return "";
    }
  }

  function CalcularComisionVentaProductoVendedor($data) {
        if($data["MetaCantidad"] <= $data["NuevaCantidadReal"]) {
          $data["MontoComisionVentaProducto"] = $data["PorcentajeComisionVenta"] * $data["NuevaCantidadReal"] / 100;          
        }
        else {          
          $data["MontoComisionVentaProducto"] = 0;
        }

        $data["CantidadMeta"] = $data["MetaCantidad"];
        $data["CantidadReal"] = $data["NuevaCantidadReal"];
        $data["PorcentajeComisionVentaProducto"] =  $data["PorcentajeComisionVenta"];
        $resultado = $data;
      
      return $resultado;
  }


}
