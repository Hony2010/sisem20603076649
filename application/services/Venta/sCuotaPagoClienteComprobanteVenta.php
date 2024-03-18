<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCuotaPagoClienteComprobanteVenta extends MY_Service {

  public $sCuotaPagoClienteComprobanteVenta = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->model('Venta/mCuotaPagoClienteComprobanteVenta');
    $this->CuotaPagoClienteComprobanteVenta = $this->mCuotaPagoClienteComprobanteVenta->CuotaPagoClienteComprobanteVenta;
    //$this->sCuotaPagoClienteComprobanteVenta["EstadoDireccion"] = ESTADO_SIN_CAMBIOS_DIRECCION_CLIENTE;
  }

    function InsertarCuotaPagoClienteComprobanteVenta($data) {          
      $resultado = $this->mCuotaPagoClienteComprobanteVenta->InsertarCuotaPagoClienteComprobanteVenta($data);      
      return $resultado;    
    }


  function InsertarCuotasPagoClienteComprobanteVenta($data,$IdComprobanteVenta) {
    foreach($data as $key => $value) {
      $value["IdCuotaPagoClienteComprobanteVenta"]="";
      $value["IdComprobanteVenta"] =$IdComprobanteVenta;
      $value["FechaPagoCuota"] = convertToDate($value["FechaPagoCuota"]);      
      $value["MontoCuota"] =  (is_string($value["MontoCuota"])) ? str_replace(',',"",$value["MontoCuota"]) : $value["MontoCuota"];      
      $this->InsertarCuotaPagoClienteComprobanteVenta($value);
    }
  }

  function BorrarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data) {
    $resultado = $this->mCuotaPagoClienteComprobanteVenta->EliminarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data);      
    return $resultado;        
  }

  function ActualizarCuotasPagoClienteComprobanteVenta($data,$IdComprobanteVenta) {       
    $dataCuota["IdComprobanteVenta"] =$IdComprobanteVenta;
    $resultado =$this->BorrarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($dataCuota);      
    $this->InsertarCuotasPagoClienteComprobanteVenta($data,$IdComprobanteVenta);
    return $resultado;
  }

  function ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data) {
    $resultado=$this->mCuotaPagoClienteComprobanteVenta->ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data);    
    return $resultado;
  }
}
