<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCajaVenta extends MY_Service {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('RestApi/Caja/RestApiPendienteCobranzaCliente');
    $this->load->service('Venta/sVenta');
    // $this->load->service('Venta/sComprobanteVenta');
    $this->load->service('Venta/sNotaCredito');
    $this->load->service('Venta/sNotaDebito');
    $this->load->service('Caja/sDocumentoIngreso');
    $this->load->service('Caja/sDocumentoEgreso');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Caja/sPendienteCobranzaCliente');
    $this->load->service('CuentaCobranza/sSaldoInicialCuentaCobranza');
    $this->load->service('Configuracion/General/sConstanteSistema');
  }

  /*
  R19.178 -Cuando se realice una modificacion a un comprobante de venta respecto al total por efectivo y al contado, 
  el sistema debera generar un voucher ingreso por ajuste faltante (si es que se incremento el total)
  o un voucher de egreso por ajuste sobrante (si es que se disminuyo el total) ejem
  factura 1 -> 20 soles, voucher ingreso 1 -> 20 soles
  factura 1 -> 200 soles, voucher ingreso 2 x ajuste faltante -> 180 soles

  factura 1 -> 200 soles, voucher ingreso 1 -> 200 soles
  factura 1 -> 30 soles, voucher salida 2 x ajuste sobrante -> 170 soles,y el cual debera guardar datos del cajero,turno ,horario del momento

  R19.179 - Cuando se anule un comprobante de venta , el sistema a parte de devolver los cantidades al stock de cada producto,
  debera generar un voucher de egreso por anulacion (salida de efectivo),y el cual debera guardar datos del cajero,turno ,horario del momento
  */
  function ValidarSaldoInicialCuentaCobranza($data) {
    $resultado = $this->sSaldoInicialCuentaCobranza->ObtenerSaldoInicialCuentaCobranzaPorSerieDocumento($data);
    if(count($resultado) > 0)
    {
      return "Ya esta registrado la serie y numero de documento en saldos iniciales, favor de revisar.";
    }
    else
    {
      return "";
    }
  }

  function InsertarVentaConCaja($data) {
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data)[0];

    $resultado = "";
    switch ($data["IdTipoDocumento"]) {
      case ID_TIPODOCUMENTO_NOTACREDITO:
      case ID_TIPODOCUMENTO_NOTADEVOLUCION:
        
        $resultado ="" ;//$this->ValidarNotaCreditoConCaja($data); 
        
        if($resultado != "") {
          return $resultado;
        }

        $resultado = $this->sNotaCredito->InsertarNotaCredito($data);
        break;
      case ID_TIPODOCUMENTO_NOTADEBITO:
        $resultado = $this->sNotaDebito->InsertarNotaDebito($data);
        break;
      default:
        $resultado = $this->sVenta->InsertarVenta($data);
        break;
    }
    
    if(!is_string($resultado)) {
      $validacion = $this->ValidarSaldoInicialCuentaCobranza($resultado);
      if($validacion != "")
      {
        return $validacion;
      }

      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $tipodocumento["IndicadorGeneraDeudaContado"] == 0) {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          $resultado["UsuarioCobrador"] =$data["AliasUsuariosVenta"];
          $response = $this->sDocumentoEgreso->InsertarDocumentoEgresoDesdeComprobanteVenta($resultado);
          $resultado["MontoCobradoContado"] = $data["Total"];
          $resultado["IndicadorEstadoDeuda"] = "C";
          $response2 = $this->sPendienteCobranzaCliente->AgregarPendienteCobranzaCliente($resultado);
        }
        else {
          $response = $this->sDocumentoIngreso->InsertarDocumentoIngresoDesdeComprobanteVenta($resultado);
        }
        
        if(!is_array($response)) {
          return $response;
        }
      }
      else {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          //$resultado["UsuarioCobrador"] =$data["AliasUsuariosVenta"];
          $resultado["MontoCobradoContado"] = $data["Total"];
          $resultado["IndicadorEstadoDeuda"] = "C";
          $response2 = $this->sPendienteCobranzaCliente->AgregarPendienteCobranzaCliente($resultado);
          /*
          $response = $this->sDocumentoIngreso->InsertarDocumentoIngresoDesdeNotaCreditoVentaCredito($resultado);
          
          if(!is_array($response)) {
            return $response;
          }
          else {
            $this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($response["Movimientos"]);
          }*/          
        }
        else {                    
          $response = $this->sPendienteCobranzaCliente->AgregarPendienteCobranzaCliente($resultado);
          $response2 = $this->restapipendientecobranzacliente->InsertarJSONDesdePendienteCobranzaCliente($resultado);                    
        }
      }
    }

    return $resultado;
  }

  function ActualizarVentaConCaja($data) {
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data)[0];
    $borradoJSON = false;
    /**
     * 
     * PARA ACTUALIZAR COMPROBANTES DE VENTA DE CONTADO A CREDITO O VICEVERSA
     * DATOS PREVIO AL SIGUIENTE INSERTADO, SE DEBE BORRAR PROCEDIMIENTOS ANTERIORES 
     * 
     * */
    $comprobante = $this->sVenta->ConsultarComprobanteVentaPorId($data);
    if($data["IdFormaPago"] == $comprobante[0]["IdFormaPago"])
    {
      // print_r("EL COMPROBANTE ES EL MISMO, NO HA HABIDO CAMBIO DE FORMA DE PAGO");exit;
    }
    else {
      // print_r("EL COMPROBANTE NO ES EL MISMO, HA CAMBIADO LA FORMA DE PAGO");exit;
      if($comprobante[0]["IdFormaPago"] != ID_FORMA_PAGO_CONTADO && $data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
      {
        /** DESDE CREDITO A CONTADO
         *  1.- VALIDAR SI HAY COBRANZAS VINCULADAS EN EL COMPROBANTE DE VENTA
         *  2.- SE DEBE BORRAR DE LA TABLA PENDIENTE
         *  3.- RELIZAR LOS PROCESOS NORMALES
        */
        if($comprobante[0]["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $comprobante[0]["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
        {
          $objeto = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeNotaCreditoVentaCredito($data);
          if(!is_array($objeto))
          {
            return $objeto;
          }
          else
          {
            $this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($objeto);
          }
        }
        else
        {
          $objeto = $this->sPendienteCobranzaCliente->BorrarPendienteCobranzaCliente($data);

          if(!is_array($objeto))
          {
            return $objeto;
          }
          else
          {
            $response2 = $this->restapipendientecobranzacliente->BorrarJSONDesdePendienteCobranzaCliente($data);
            $borradoJSON = true;
          }
        }
      }
      elseif($comprobante[0]["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $data["IdFormaPago"] != ID_FORMA_PAGO_CONTADO) {
        /*
        if($tipodocumento["IndicadorGeneraDeudaContado"] == 1) 
        {
          $objeto = $this->sPendienteCobranzaCliente->BorrarPendienteCobranzaCliente($data);          
          if(!is_array($objeto))
          {
            return $objeto;
          }
          else
          {
            $response2 = $this->restapipendientecobranzacliente->BorrarJSONDesdePendienteCobranzaCliente($data);
            $borradoJSON = true;
          }
        }
        else {
          $objeto = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeComprobanteVenta($data);
          if(!is_array($objeto))
          {
            return $objeto;
          }
        }  */      
      }
    }
    

    $resultado = "";
    switch ($data["IdTipoDocumento"]) {
      case ID_TIPODOCUMENTO_NOTACREDITO:
      case ID_TIPODOCUMENTO_NOTADEVOLUCION:
        $resultado = $this->sNotaCredito->ActualizarNotaCredito($data);
        break;
      case ID_TIPODOCUMENTO_NOTADEBITO:
        $resultado = $this->sNotaDebito->ActualizarNotaDebito($data);
        break;
      default:
        $resultado = $this->sVenta->ActualizarVenta($data);
        break;
    }

    if(is_array($resultado)) {
      $validacion = $this->ValidarSaldoInicialCuentaCobranza($resultado);
      if($validacion != "")
      {
        return $validacion;
      }

      // $resultado["IdHorario"] = $data["IdHorario"];
      /**1. VALIDAMOS EL TIPO DE DOCUMENTO
       * 2. SE VALIDA APERTURA DE CAJA SEGUN PAGO CONTADO
       */
      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $tipodocumento["IndicadorGeneraDeudaContado"] == 0) {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          $response = $this->sDocumentoEgreso->ActualizarDocumentoEgresoDesdeComprobanteVenta($resultado);
        }
        else {
          $response = $this->sDocumentoIngreso->ActualizarDocumentoIngresoDesdeComprobanteVenta($resultado);
        }

        if(!is_array($response)) {
          return $response;
        }
      }
      else {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          /*
          $response = $this->sDocumentoIngreso->ActualizarDocumentoIngresoDesdeNotaCreditoVentaCredito($resultado);
          if(!is_array($response))
          {
            return $response;
          }
          else
          {
            $this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($response["Movimientos"]);
          }
          */
        }
        else {
          
          $response = $this->sPendienteCobranzaCliente->AgregarPendienteCobranzaCliente($resultado);
          if($borradoJSON == true)
          {
            $response2 = $this->restapipendientecobranzacliente->InsertarJSONDesdePendienteCobranzaCliente($resultado);
          }
          else
          {
            $response2 = $this->restapipendientecobranzacliente->ActualizarJSONDesdePendienteCobranzaCliente($resultado);
          }          
        }

      }
    }
    return $resultado;
  }

  function AnularVentaConCaja($data) {
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data)[0];
    $resultado = $this->sVenta->AnularVenta($data);

    if(is_array($resultado)) {
      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $tipodocumento["IndicadorGeneraDeudaContado"] == 0 && $data["IndicadorEstado"] == ESTADO_DOCUMENTO_ACTIVO)
      {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
        {
          $response = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeComprobanteVenta($resultado);
        }
        else
        {
          $response = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeComprobanteVenta($resultado);
        }

        if(!is_array($response))
        {
          return $response;
        }
      }
      elseif(($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $tipodocumento["IndicadorGeneraDeudaContado"] == 1) || $data["IdFormaPago"] != ID_FORMA_PAGO_CONTADO) {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          /*
          $response = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeNotaCreditoVentaCredito($resultado);
          if(!is_array($response))
          {
            return $response;
          }
          else
          {
            $this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($response);
          }
          */
        }
        else {
          $response = $this->sPendienteCobranzaCliente->BorrarPendienteCobranzaCliente($resultado);
          
          if(is_string($response)) {
            return $response;
          }
          else {
            $response2 = $this->restapipendientecobranzacliente->BorrarJSONDesdePendienteCobranzaCliente($data);
          }          
        }
      }
    }
    return $resultado;
  }

  function EliminarVentaConCaja($data) {
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data)[0];
    $resultado = $this->sVenta->EliminarVenta($data);
    if(is_array($resultado))
    {
      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $tipodocumento["IndicadorGeneraDeudaContado"] == 0 && $data["IndicadorEstado"] == ESTADO_DOCUMENTO_ACTIVO)
      {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
        {
          $response = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeComprobanteVenta($resultado);
        }
        else {
          $response = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeComprobanteVenta($resultado);
        }

        if(!is_array($response)) {
          return $response;
        }
      }
      elseif(($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $tipodocumento["IndicadorGeneraDeudaContado"] == 1) || $data["IdFormaPago"] != ID_FORMA_PAGO_CONTADO) {
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          /*
          $response = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeNotaCreditoVentaCredito($resultado);
          if(!is_array($response))
          {
            return $response;
          }
          else
          {
            $this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($response);
          }
          */
        }
        else {          
          $response = $this->sPendienteCobranzaCliente->BorrarPendienteCobranzaCliente($resultado);
        
          if(is_string($response)) {
            return $response;
          }
          else {
            $response2 = $this->restapipendientecobranzacliente->BorrarJSONDesdePendienteCobranzaCliente($data);
          }
        }
      }
      
    }
    return $resultado;
  }
  
  function ValidarNotaCreditoConCaja($data) {

    $resultado = $this->sPendienteCobranzaCliente->ValidarExisteExcesoCobranzaComprobantePorNotaCredito($data);
    
    if ($resultado !="") {
      $SimboloMoneda=$data["MiniComprobantesVentaNC"][0]["SimboloMoneda"];
      $NombreMoneda=$data["MiniComprobantesVentaNC"][0]["NombreMoneda"];
      $montoexceso = $resultado["MontoExcesoCobrado"];
      return "No se puede aplicar la nota credito al comprobante, porque primero debe cancelar, reducir o transferir los cobros en exceso de ".$SimboloMoneda." ".$montoexceso." ".$NombreMoneda." ";
    }

    return "";
  }
}