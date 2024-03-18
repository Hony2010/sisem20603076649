<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sPendientePagoProveedor extends MY_Service {

  public $PendientePagoProveedor = array();

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
    $this->load->model('Caja/mPendientePagoProveedor');
    $this->load->service("Caja/sMovimientoCaja");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->PendientePagoProveedor = $this->mPendientePagoProveedor->PendientePagoProveedor;
  }

  function InsertarPendientePagoProveedor($data)
  {
    try {
      $resultadoValidacion = "";

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        // $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
        $resultado = $this->mPendientePagoProveedor->InsertarPendientePagoProveedor($data);
        return $resultado;
      }
      else
      {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function ActualizarPendientePagoProveedor($data)
  {
    try {
      // $data["FechaComprobante"]=$data["FechaComprobante"];
      $resultadoValidacion = "";

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        // $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
        $resultado=$this->mPendientePagoProveedor->ActualizarPendientePagoProveedor($data);
        return $resultado;
      }
      else
      {
        throw new Exception(nl2br($resultadoValidacion));
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  //OBTENER PCC
  function ObtenerPendientePagoProveedorPorIdComprobanteCompra($data)
  {
    // print_r($data);exit;
    $resultado = $this->mPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdComprobanteCompra($data);
    return $resultado;
  }

  function ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($data)
  {
    $resultado = $this->mPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($data);
    return $resultado;
  }

  // function ConsultarPendientesPagoProveedorPorIdProveedor($data)
  // {
  //   $resultado = $this->mPendientePagoProveedor->ConsultarPendientesPagoProveedorPorIdProveedor($data);
  //   return $resultado;
  // }

  function ConsultarPendientesPagoProveedorPorIdProveedorYFiltro($data)
  {
    $resultado = $this->mPendientePagoProveedor->ConsultarPendientesPagoProveedorPorIdProveedorYFiltro($data);
    return $resultado;
  }

  //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  function ValidarComprobanteCompraEnCobranzaProveedor($data)
  {
    $resultado = $this->sMovimientoCaja->ObtenerMovimientosPorComprobanteCompra($data);
    // print_r($resultado);exit;
    if(count($resultado) > 0)
    {
      return "El comprobante compra tiene pagos realizados. No puede ser alterado hasta que sean borrados sus pagos.";
    }
    else
    {
      return "";
    }
  }

  //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  function ValidarSaldoInicialCuentaPagoEnCobranzaProveedor($data)
  {
    $resultado = $this->sMovimientoCaja->ObtenerMovimientosPorSaldoInicialCuentaPago($data);
    // print_r($resultado);exit;
    if(count($resultado) > 0)
    {
      return "El saldo inicial cuenta pago tiene pagos realizados. No puede ser alterado hasta que sean borrados sus pagos.";
    }
    else
    {
      return "";
    }
  }

  function BorrarPendientePagoProveedor($data, $inicial = false) {
    // $data["FechaComprobante"]=convertToDate($data["FechaComprobante"]);
    $resultado = ($inicial) ? $this->ValidarSaldoInicialCuentaPagoEnCobranzaProveedor($data) : $this->ValidarComprobanteCompraEnCobranzaProveedor($data);
    // print_r($resultado);exit;
    if($resultado == "")
    {
      $pendiente = ($inicial) ? $this->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($data) : $this->ObtenerPendientePagoProveedorPorIdComprobanteCompra($data);
      // print_r($pendiente);exit;
      if(count($pendiente) > 0)
      {
        $resultado = $this->mPendientePagoProveedor->BorrarPendientePagoProveedor($pendiente[0]);
        return $resultado;
      }
      else{
        return $data;
      }
    }
    return $resultado;
  }

  //AGREGAMOS EL PENDIENTE COBRANZA CLIENTE
  /**VALIDAR SI EL PENDIENTE DE COMPROBANTE YA ESTA EN UNA COBRANZA*/
  function AgregarPendientePagoProveedor($data, $inicial = false)
  {
    $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
    $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
    $data["TipoCambioCompra"] = ($inicial) ? $data["TipoCambioCompra"] : $data["ValorTipoCambio"];
    $data["MontoOriginal"] = ($inicial) ? $data["MontoOriginal"] : $data["Total"];
    
    //OBTENER CODIGO MONEDA Y CODIGO TIPO DOCUMENTO
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data);
    $data["NombreMoneda"] = (count($moneda)>0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda)>0) ? $moneda[0]["CodigoMoneda"] : '';
    $data["SimboloMoneda"] = (count($moneda)>0) ? $moneda[0]["SimboloMoneda"] : '';

    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento)>0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';
    $data["NombreAbreviado"] = (count($tipodocumento)>0) ? $tipodocumento[0]["NombreAbreviado"] : '';

    $pendiente = ($inicial) ? $this->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($data) : $this->ObtenerPendientePagoProveedorPorIdComprobanteCompra($data);
    if(count($pendiente) > 0)
    {
      // print_r("DATA A PROCESAR: ".PHP_EOL);print_r($pendiente[0]);
      $dataPendiente = $this->mapper->map_real($data,$this->PendientePagoProveedor);
      $dataPendiente = array_merge($pendiente[0], $dataPendiente);
      $dataPendiente["SaldoPendiente"] = $dataPendiente["MontoOriginal"] - $dataPendiente["MontoPagado"];
      // print_r(PHP_EOL."DATA A PROCESAR2: ".PHP_EOL);print_r($dataPendiente);exit;
      $resultado = $this->ActualizarPendientePagoProveedor($dataPendiente);
      // if($pendiente[0]["MontoOriginal"] != $data["MontoOriginal"])
      // {
      //   $reponse = $this->ActualizarMovimientosCajaDesdePendienteCobranza($resultado);
      // }
      // print_r("DATA A PROCESAR: ".PHP_EOL);print_r($resultado);exit;
      return $resultado;
    }
    else {
      $data["IdPendientePagoProveedor"] = "";
      $data["SaldoPendiente"] = $data["MontoOriginal"];
      $data["MontoPagado"] = 0;
      // print_r("DATA A PROCESAR: ".PHP_EOL);print_r($data);exit;
      $resultado = $this->InsertarPendientePagoProveedor($data);
      
      return $resultado;
    }
  }

  //SE ACTUALIZAN LOS MOVIMIENTOS QUE SE REALIZAN, PARA EL CAMPO - SaldoDocumentoPendienteProveedor
  // function ActualizarMovimientosCajaDesdePendienteCobranza($data)
  // {
  //   $movimientos = $this->sMovimientoCaja->ObtenerMovimientosPorComprobanteCompra($data);
  //   $MontoOriginal = $data["MontoOriginal"];
  //   foreach ($movimientos as $key => $value) {
  //     $MontoOriginal = $MontoOriginal - $value["MontoIngresoEfectivo"];
  //     $value["SaldoDocumentoPendienteProveedor"] = $MontoOriginal;
  //     $response = $this->sMovimientoCaja->ActualizarMovimientoCaja($value);
  //   }

  //   return $data;
  // }

  //SE ACTUALIZAN LOS MOVIMIENTOS QUE SE REALIZAN, PARA EL CAMPO - SaldoDocumentoPendienteProveedor
  function ActualizarMovimientosCajaParaCobranzaProveedor($data)
  {
    $pendiente = array();
    $movimientos = array();
    if(array_key_exists("IdComprobanteCompra", $data))
    {
      if(is_numeric($data["IdComprobanteCompra"]))
      {
        // print_r($data);exit;
        $pendiente = $this->ObtenerPendientePagoProveedorPorIdComprobanteCompra($data)[0];
        $movimientos = $this->sMovimientoCaja->ObtenerMovimientosParaCobranzaProveedorPorComprobanteCompra($data);
        // print_r($pendiente);exit;
      }
    }

    if(array_key_exists("IdSaldoInicialCuentaPago", $data))
    {
      if(is_numeric($data["IdSaldoInicialCuentaPago"]))
      {
        // print_r($data);exit;
        $pendiente = $this->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($data)[0];
        $movimientos = $this->sMovimientoCaja->ObtenerMovimientosParaCobranzaProveedorPorSaldoInicialCuentaPago($data);
      }
    }

    // print_r($pendiente);
    // print_r($pendiente);exit;
    $MontoOriginal = $pendiente["MontoOriginal"];
    $i = 0;
    foreach ($movimientos as $key => $value) {
      if($i == 0)
      {
        $value["SaldoDocumentoPendienteProveedor"] = $MontoOriginal;
      }
      else{
        $MontoOriginal = $MontoOriginal - $value["MontoEgresoEfectivo"];
        $value["SaldoDocumentoPendienteProveedor"] = $MontoOriginal;
      }
      $response = $this->sMovimientoCaja->ActualizarMovimientoCaja($value);
      $i++;
    }

    return $data;
  }

  //PARA JSON
  function ConsultasPendientesPagoProveedorParaJSON()
  {
    $resultado = $this->mPendientePagoProveedor->ConsultarPendientesPagoProveedorParaJSON();
    return $resultado;
  }

  //PARA DESCONTAR PENDIENTE COBRANZA CLIENTE
  function DescontarSaldosPendientePagoProveedor($data) //UPDATE
  {
    // print_r($data);exit;
    foreach ($data as $key => $value) {
      $response = array();
      if(array_key_exists("IdComprobanteCompra", $value))
      {
        if(is_numeric($value["IdComprobanteCompra"]))
        {
          $response = $this->ObtenerPendientePagoProveedorPorIdComprobanteCompra($value);
        }
      }
      
      if(array_key_exists("IdSaldoInicialCuentaPago", $value))
      {
        if(is_numeric($value["IdSaldoInicialCuentaPago"]))
        {
          $response = $this->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($value);
        }
      }
      // print_r($value);print_r($response);exit;
      if(count($response) > 0)
      {
        $response[0]["SaldoPendiente"] = $response[0]["SaldoPendiente"] - $value["MontoEgresoEfectivo"];
        $response[0]["MontoPagado"] = $response[0]["MontoPagado"] + $value["MontoEgresoEfectivo"];
        $data[$key] = $this->ActualizarPendientePagoProveedor($response[0]);

        // $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        // $dataMovimiento["SaldoDocumentoPendienteProveedor"] = $response[0]["SaldoPendiente"];
        // $this->sMovimientoCaja->ActualizarMovimientoCaja($dataMovimiento);
        // print_r($resultado);exit;
      }
    }
    return $data;
  }

  //PARA REVERTIR PENDIENTE COBRANZA CLIENTE
  function RevertirSaldosPendientePagoProveedor($data) //UPDATE
  {
    // print_r($data);exit;
    foreach ($data as $key => $value) {
      $response = array();
      if(array_key_exists("IdComprobanteCompra", $value))
      {
        if(is_numeric($value["IdComprobanteCompra"]))
        {
          $response = $this->ObtenerPendientePagoProveedorPorIdComprobanteCompra($value);
        }
      }

      if(array_key_exists("IdSaldoInicialCuentaPago", $value))
      {
        if(is_numeric($value["IdSaldoInicialCuentaPago"]))
        {
          $response = $this->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($value);
        }
      }
      // print_r($response);exit;
      if(count($response) > 0)
      {
        $response[0]["SaldoPendiente"] = $response[0]["SaldoPendiente"] + $value["MontoEgresoEfectivo"];
        $response[0]["MontoPagado"] = $response[0]["MontoPagado"] - $value["MontoEgresoEfectivo"];
        $data[$key] = $this->ActualizarPendientePagoProveedor($response[0]);
        // print_r($resultado);exit;
      }
    }
    return $data;
  }

  //ACTUALIZACIOND DE SALDOS EN REVERSA
  function ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($data)
  {
    foreach ($data as $key => $value) {
      $response = $this->ActualizarMovimientosCajaParaCobranzaProveedor($value);
    }
    return $data;
  }
}
