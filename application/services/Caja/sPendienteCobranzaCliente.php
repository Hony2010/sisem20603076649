<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sPendienteCobranzaCliente extends MY_Service {

  public $PendienteCobranzaCliente = array();

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
    $this->load->model('Caja/mPendienteCobranzaCliente');
    $this->load->service("Caja/sMovimientoCaja");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->PendienteCobranzaCliente = $this->mPendienteCobranzaCliente->PendienteCobranzaCliente;
  }

  function InsertarPendienteCobranzaCliente($data)
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
        $resultado = $this->mPendienteCobranzaCliente->InsertarPendienteCobranzaCliente($data);
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

  function ActualizarPendienteCobranzaCliente($data)
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
        $resultado=$this->mPendienteCobranzaCliente->ActualizarPendienteCobranzaCliente($data);
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
  function ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data)
  {
    $resultado = $this->mPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data);
    return $resultado;
  }

  function ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($data)
  {
    $resultado = $this->mPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($data);
    return $resultado;
  }

  // function ConsultarPendientesCobranzaClientePorIdCliente($data)
  // {
  //   $resultado = $this->mPendienteCobranzaCliente->ConsultarPendientesCobranzaClientePorIdCliente($data);
  //   return $resultado;
  // }

  function ConsultarPendientesCobranzaClientePorIdClienteYFiltro($data)
  {
    $resultado = $this->mPendienteCobranzaCliente->ConsultarPendientesCobranzaClientePorIdClienteYFiltro($data);
    return $resultado;
  }

  //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  function ValidarComprobanteVentaEnCobranzaCliente($data)
  {
    $resultado = $this->sMovimientoCaja->ObtenerMovimientosPorComprobanteVenta($data);
    
    if(count($resultado) > 0)
    {
      return "El comprobante venta tiene cobranzas realizadas. No puede ser alterado hasta que sean borradas sus cobranzas.";
    }
    else
    {
      return "";
    }
  }

  //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  function ValidarSaldoInicialCuentaCobranzaEnCobranzaCliente($data)
  {
    $resultado = $this->sMovimientoCaja->ObtenerMovimientosPorSaldoInicialCuentaCobranza($data);
    
    if(count($resultado) > 0)
    {
      return "El saldo inicial cuenta cobranza tiene cobranzas realizadas. No puede ser alterado hasta que sean borradas sus cobranzas.";
    }
    else
    {
      return "";
    }
  }

  function BorrarPendienteCobranzaCliente($data, $inicial = false) {
    // $data["FechaComprobante"]=convertToDate($data["FechaComprobante"]);
    $resultado = ($inicial) ? $this->ValidarSaldoInicialCuentaCobranzaEnCobranzaCliente($data) : $this->ValidarComprobanteVentaEnCobranzaCliente($data);
    
    if($resultado == "")
    {
      $pendiente = ($inicial) ? $this->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($data) : $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data);
    
      if(count($pendiente) > 0)
      {
        $resultado = $this->mPendienteCobranzaCliente->BorrarPendienteCobranzaCliente($pendiente[0]);
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
  function AgregarPendienteCobranzaCliente($data, $inicial = false)
  {
    $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
    $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
    $data["TipoCambioVenta"] = ($inicial) ? $data["TipoCambioVenta"] : $data["ValorTipoCambio"];
    $data["MontoOriginal"] = ($inicial) ? $data["MontoOriginal"] : $data["Total"];
    
    //OBTENER CODIGO MONEDA Y CODIGO TIPO DOCUMENTO
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data);
    $data["NombreMoneda"] = (count($moneda)>0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda)>0) ? $moneda[0]["CodigoMoneda"] : '';
    $data["SimboloMoneda"] = (count($moneda)>0) ? $moneda[0]["SimboloMoneda"] : '';

    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento)>0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';
    $data["NombreAbreviado"] = (count($tipodocumento)>0) ? $tipodocumento[0]["NombreAbreviado"] : '';

    $pendiente = ($inicial) ? $this->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($data) : $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data);
    if(count($pendiente) > 0)
    {
      
      $dataPendiente = $this->mapper->map_real($data,$this->PendienteCobranzaCliente);
      $dataPendiente = array_merge($pendiente[0], $dataPendiente);
      $dataPendiente["SaldoPendiente"] = $dataPendiente["MontoOriginal"] - $dataPendiente["MontoCobrado"];
      
      $resultado = $this->ActualizarPendienteCobranzaCliente($dataPendiente);
      // if($pendiente[0]["MontoOriginal"] != $data["MontoOriginal"])
      // {
      //   $reponse = $this->ActualizarMovimientosCajaDesdePendienteCobranza($resultado);
      // }      
      return $resultado;
    }
    else {
      
      if (array_key_exists("MontoCobradoContado",$data)) {
        $data["IdPendienteCobranzaCliente"] = "";
        $data["SaldoPendiente"] =0;
        $data["MontoCobrado"] =  $data["MontoOriginal"];
      }
      else {
        $data["IdPendienteCobranzaCliente"] = "";
        $data["SaldoPendiente"] = $data["MontoOriginal"];
        $data["MontoCobrado"] = 0;      
      }
      
      $resultado = $this->InsertarPendienteCobranzaCliente($data);
      
      return $resultado;
    }
  }

  //SE ACTUALIZAN LOS MOVIMIENTOS QUE SE REALIZAN, PARA EL CAMPO - SaldoDocumentoPendienteCliente
  // function ActualizarMovimientosCajaDesdePendienteCobranza($data)
  // {
  //   $movimientos = $this->sMovimientoCaja->ObtenerMovimientosPorComprobanteVenta($data);
  //   $MontoOriginal = $data["MontoOriginal"];
  //   foreach ($movimientos as $key => $value) {
  //     $MontoOriginal = $MontoOriginal - $value["MontoIngresoEfectivo"];
  //     $value["SaldoDocumentoPendienteCliente"] = $MontoOriginal;
  //     $response = $this->sMovimientoCaja->ActualizarMovimientoCaja($value);
  //   }

  //   return $data;
  // }

  //SE ACTUALIZAN LOS MOVIMIENTOS QUE SE REALIZAN, PARA EL CAMPO - SaldoDocumentoPendienteCliente
  function ActualizarMovimientosCajaParaCobranzaCliente($data) {
    $pendiente = array();
    $movimientos = array();
    // print_r($data);
    // exit();
    if(array_key_exists("IdComprobanteVenta", $data)) {
      if(is_numeric($data["IdComprobanteVenta"])) {        
        $pendiente = $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data)[0];
        $movimientos = $this->sMovimientoCaja->ObtenerMovimientosParaCobranzaClientePorComprobanteVenta($data);       
      }
    }

    if(array_key_exists("IdSaldoInicialCuentaCobranza", $data)) {
      if(is_numeric($data["IdSaldoInicialCuentaCobranza"])) {
        $pendiente = $this->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($data)[0];
        $movimientos = $this->sMovimientoCaja->ObtenerMovimientosParaCobranzaClientePorSaldoInicialCuentaCobranza($data);
      }
    }

    
    $MontoOriginal = $pendiente["MontoOriginal"];
    $i = 0;
    
    foreach ($movimientos as $key => $value) {
      if($i == 0) {
        $value["SaldoDocumentoPendienteCliente"] = $MontoOriginal;
      }
      else{
        $MontoOriginal = $MontoOriginal - $value["MontoIngresoEfectivo"];
        $value["SaldoDocumentoPendienteCliente"] = $MontoOriginal;
      }

      $response = $this->sMovimientoCaja->ActualizarMovimientoCaja($value);
      $i++;
    }

    return $data;
  }

  //PARA JSON
  function ConsultasPendientesCobranzaClienteParaJSON()
  {
    $resultado = $this->mPendienteCobranzaCliente->ConsultarPendientesCobranzaClienteParaJSON();
    return $resultado;
  }

  //PARA DESCONTAR PENDIENTE COBRANZA CLIENTE
  function DescontarSaldosPendienteCobranzaCliente($data) //UPDATE
  {    
    foreach ($data as $key => $value) {
      $response = array();
      if(array_key_exists("IdComprobanteVenta", $value))
      {
        if(is_numeric($value["IdComprobanteVenta"]))
        {
          $response = $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value);
        }
      }
      
      if(array_key_exists("IdSaldoInicialCuentaCobranza", $value))
      {
        if(is_numeric($value["IdSaldoInicialCuentaCobranza"]))
        {
          $response = $this->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($value);
        }
      }
      
      if(count($response) > 0) {
        $response[0]["SaldoPendiente"] = $response[0]["SaldoPendiente"] - $value["MontoIngresoEfectivo"];
        $response[0]["MontoCobrado"] = $response[0]["MontoCobrado"] + $value["MontoIngresoEfectivo"];
        $response[0]["IndicadorEstadoDeuda"] = (0 < $response[0]["SaldoPendiente"]) ? "P" : "C";
        $data[$key] = $this->ActualizarPendienteCobranzaCliente($response[0]);
        // $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        // $dataMovimiento["SaldoDocumentoPendienteCliente"] = $response[0]["SaldoPendiente"];
        // $this->sMovimientoCaja->ActualizarMovimientoCaja($dataMovimiento);
      }
    }
    return $data;
  }

  //PARA REVERTIR PENDIENTE COBRANZA CLIENTE
  function RevertirSaldosPendienteCobranzaCliente($data) //UPDATE
  {
    foreach ($data as $key => $value) {
      $response = array();
      if(array_key_exists("IdComprobanteVenta", $value))
      {
        if(is_numeric($value["IdComprobanteVenta"]))
        {
          $response = $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value);
        }
      }

      if(array_key_exists("IdSaldoInicialCuentaCobranza", $value))
      {
        if(is_numeric($value["IdSaldoInicialCuentaCobranza"]))
        {
          $response = $this->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($value);
        }
      }

      if(count($response) > 0)
      {
        $response[0]["SaldoPendiente"] = $response[0]["SaldoPendiente"] + $value["MontoIngresoEfectivo"];
        $response[0]["MontoCobrado"] = $response[0]["MontoCobrado"] - $value["MontoIngresoEfectivo"];
        $response[0]["IndicadorEstadoDeuda"] = (0 < $response[0]["SaldoPendiente"]) ? "P" : "C";
        $data[$key] = $this->ActualizarPendienteCobranzaCliente($response[0]);
        
      }
    }
    return $data;
  }

  //ACTUALIZACIOND DE SALDOS EN REVERSA
  function ActualizarMovimientosCajaParaSaldoPendienteCobranzaCliente($data)
  {
    foreach ($data as $key => $value) {
      $response = $this->ActualizarMovimientosCajaParaCobranzaCliente($value);
    }
    return $data;
  }

  //PARA LETRA
  function ConsultarPendientesCobranzaClienteVentaParaLetra($data)
  {
    $resultado = $this->mPendienteCobranzaCliente->ConsultarPendientesCobranzaClienteVentaParaLetra($data);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
      $resultado[$key]["ComprobanteSeleccionado"] = false;
    }
    return $resultado;
  }

  function ValidarExisteCobranza($data) {
    $resultado = $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data);
    
    if(count($resultado)>0) {
      if ( $resultado[0]["MontoCobrado"] > 0) {
        return $resultado[0];
      }
    }

    return "";
  }


  function ValidarExisteExcesoCobranzaComprobantePorNotaCredito($data) {
    $datacomprobanteventa=$data["MiniComprobantesVentaNC"][0];
    $resultado = $this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($datacomprobanteventa);

    if(count($resultado)>0) {
      $resultado[0]["MontoExcesoCobrado"] =  $resultado[0]["MontoCobrado"] - ($datacomprobanteventa["SaldoNotaCredito"] - $data["Total"]);
      if ($resultado[0]["MontoExcesoCobrado"] > 0 ) {                
        return $resultado[0];
      }
    }

    return ""; 
  }

  function ConsultarComprobantesVentaPendientesCobranzaClientePorVendedor($data) {
    $resultado = $this->mPendienteCobranzaCliente->ConsultarComprobantesVentaPendientesCobranzaClientePorVendedor($data);
     foreach($resultado as $key => $value) {
        
        $dataCobranzaRapida = $this->sCobranzaCliente->Cargar();        
        $dataPendienteCobranzaCliente=$this->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value);        
        $dataPendienteCobranzaCliente[0]["IdPersona"] = $dataPendienteCobranzaCliente[0]["IdCliente"];
        $dataPendienteCobranzaCliente[0]["ValorTipoCambio"] = $dataCobranzaRapida["ValorTipoCambio"];        
        $dataPendienteCobranzaCliente[0]["Importe"] = $value["Importe"];
        $dataPendienteCobranzaCliente[0]["MontoCobrado"] = $value["Importe"];
        $dataPendienteCobranzaCliente[0]["MontoSoles"] = $value["Importe"];
        //$dataPendienteCobranzaCliente[0]["MontoComprobante"] = $value["Importe"]; 
        $dataPendienteCobranzaCliente[0]["MontoDolares"] = $value["Importe"] * $dataCobranzaRapida["ValorTipoCambio"];
        $dataPendienteCobranzaCliente[0]["NuevoSaldo"] = $value["SaldoPendiente"] - $value["MontoACobrar"];        
        $resultado[$key]=array_merge($dataCobranzaRapida,$resultado[$key]);
        $resultado[$key]["AliasUsuarioVenta"] = $value["AliasUsuarioVenta"];        
        $resultado[$key]["UsuarioCobrador"] = $value["AliasUsuarioVenta"];        
        $resultado[$key]["MontoComprobante"] = $value["Importe"];
        $resultado[$key]["DetallesCobranzaCliente"]=array();
        $resultado[$key]["NuevoDetalleCobranzaCliente"] = $dataPendienteCobranzaCliente[0];
     }

    return $resultado;    
  }

}
