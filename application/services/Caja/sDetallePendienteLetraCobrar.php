<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sDetallePendienteLetraCobrar extends MY_Service
{

  public $DetallePendienteLetraCobrar = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('reporter');
    $this->load->library('imprimir');
    $this->load->helper("date");
    $this->load->model("Base");
    $this->load->model('Caja/mDetallePendienteLetraCobrar');
    $this->load->service("Caja/sPendienteLetraCobrar");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->DetallePendienteLetraCobrar = $this->mDetallePendienteLetraCobrar->DetallePendienteLetraCobrar;
  }

  function Cargar()
  {
    $this->DetallePendienteLetraCobrar["ImporteCobradoComprobanteVenta"] = 0;

    $data = array();

    $resultado = array_merge($this->DetallePendienteLetraCobrar, $data);

    return $resultado;
  }

  function InsertarDetallesPendienteLetraCobrar($data)
  {
    $detalles = $data["DetallesPendienteLetraCobrar"];
    foreach ($detalles as $key => $value) {
      $value["IdPendienteLetraCobrar"] = $data["IdPendienteLetraCobrar"];
      $resultado = $this->mDetallePendienteLetraCobrar->InsertarDetallePendienteLetraCobrar($value);
      $detalles[$key] = $resultado;
    }
    return $detalles;
  }

  function BorrarDetallesPendienteLetraCobrarPorIdPendienteLetraCobrar($data)
  {
    $resultado = $this->mDetallePendienteLetraCobrar->BorrarDetallesPendienteLetraCobrarPorIdPendienteLetraCobrar($data);
    return $resultado;
  }
  // function InsertarDetallePendienteLetraCobrar($data)
  // {
  //   try {
  //     $resultadoValidacion = "";

  //     if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
  //     {
  //       return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
  //     }
  //     else if($resultadoValidacion == "")
  //     {
  //       $resultado = $this->mDetallePendienteLetraCobrar->InsertarDetallePendienteLetraCobrar($data);
  //       return $resultado;
  //     }
  //     else
  //     {
  //       $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
  //       return $resultado;
  //     }
  //   }
  //   catch (Exception $e) {
  //     throw new Exception($e->getMessage(),$e->getCode(),$e);
  //   }
  // }

  // function ActualizarDetallePendienteLetraCobrar($data)
  // {
  //   try {
  //     // $data["FechaComprobante"]=$data["FechaComprobante"];
  //     $resultadoValidacion = "";

  //     if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
  //     {
  //       return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
  //     }
  //     else if($resultadoValidacion == "")
  //     {
  //       // $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
  //       $resultado=$this->mDetallePendienteLetraCobrar->ActualizarDetallePendienteLetraCobrar($data);
  //       return $resultado;
  //     }
  //     else
  //     {
  //       throw new Exception(nl2br($resultadoValidacion));
  //     }
  //   }
  //   catch (Exception $e) {
  //     throw new Exception($e->getMessage(),$e->getCode(),$e);
  //   }
  // }


  // //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  // function BorrarDetallePendienteLetraCobrar($data) {
  //   // $data["FechaComprobante"]=convertToDate($data["FechaComprobante"]);
  //   $resultado = $this->ValidarComprobanteVentaEnCobranzaCliente($data);
  //   // print_r($resultado);exit;
  //   if($resultado == "")
  //   {
  //     // $pendiente = $this->ObtenerDetallePendienteLetraCobrarPorIdComprobanteVenta($data);
  //     // // print_r($pendiente);exit;
  //     // if(count($pendiente) > 0)
  //     // {
  //       $resultado = $this->mDetallePendienteLetraCobrar->BorrarDetallePendienteLetraCobrar($data);
  //       return $resultado;
  //     // }
  //     // else{
  //     //   return $data;
  //     // }
  //   }
  //   return $resultado;
  // }

  function ConsultarDetallesPendienteLetraCobrarPorPendienteLetraCobrar($data)
  {
    $resultado = $this->mDetallePendienteLetraCobrar->ConsultarDetallesPendienteLetraCobrarPorPendienteLetraCobrar($data);
    return $resultado;
  }

}
