<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCajaCompra extends MY_Service {

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('RestApi/Caja/RestApiPendientePagoProveedor');
    $this->load->service('Compra/sCompra');
    $this->load->service('Compra/sComprobanteCompra');
    $this->load->service('Compra/sNotaCreditoCompra');
    $this->load->service('Compra/sNotaDebitoCompra');
    $this->load->service('Caja/sDocumentoIngreso');
    $this->load->service('Caja/sDocumentoEgreso');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Caja/sPendientePagoProveedor');
    $this->load->service('CuentaPago/sSaldoInicialCuentaPago');
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
  function ValidarSaldoInicialCuentaPago($data)
  {
    $resultado = $this->sSaldoInicialCuentaPago->ObtenerSaldoInicialCuentaPagoPorSerieDocumento($data);
    if(count($resultado) > 0)
    {
      return "Ya esta registrado la serie y numero de documento en saldos iniciales, favor de revisar.";
    }
    else
    {
      return "";
    }
  }

  function InsertarCompraConCaja($data)
  {
    $resultado = "";
    switch ($data["IdTipoDocumento"]) {
      case ID_TIPODOCUMENTO_NOTACREDITO:
        $resultado = $this->sNotaCreditoCompra->InsertarNotaCreditoCompra($data);
        break;
      case ID_TIPODOCUMENTO_NOTADEBITO:
        $resultado = $this->sNotaDebitoCompra->InsertarNotaDebitoCompra($data);
        break;
      default:
      $resultado = $this->sCompra->InsertarCompra($data);
        break;
    }

    //VALIDACION
    $validacion = $this->ValidarSaldoInicialCuentaPago($resultado);
    if($validacion != "")
    {
      return $validacion;
    }

    // print_r($resultado);exit;
    if(!is_string($resultado))
    {
      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
      {
        if($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $response = $this->sDocumentoEgreso->InsertarDocumentoEgresoDesdeComprobanteCompra($resultado);
        }
        else
        {
          $response = $this->sDocumentoIngreso->InsertarDocumentoIngresoDesdeComprobanteCompra($resultado);
        }

        if(!is_array($response))
        {
          return $response;
        }
      }
      else
      {
        // $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado);
        if($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado);
          $response2 = $this->restapipendientepagoproveedor->InsertarJSONDesdePendientePagoProveedor($resultado);
          // print_r($response2);exit;
        }
        else
        {
          //PREPARANDO
          $response = $this->sDocumentoEgreso->InsertarDocumentoEgresoDesdeNotaCreditoCompraCredito($resultado);
          // print_r($response);exit;
          if(!is_array($response))
          {
            return $response;
          }
          else
          {
            $this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($response["Movimientos"]);
            // print_r($response);exit;
          }
        }
      }
    }
    // print_r("adasd");print_r($resultado);exit;
    return $resultado;
  }

  function ActualizarCompraConCaja($data)
  {
    /**
     * 
     * PARA ACTUALIZAR COMPROBANTES DE VENTA DE CONTADO A CREDITO O VICEVERSA
     * DATOS PREVIO AL SIGUIENTE INSERTADO, SE DEBE BORRAR PROCEDIMIENTOS ANTERIORES 
     * 
     * */
    $borradoJSON = false;
    $comprobante = $this->sComprobanteCompra->ObtenerComprobanteCompraPorIdComprobante($data);
    if($data["IdFormaPago"] == $comprobante["IdFormaPago"])
    {
      // print_r("EL COMPROBANTE ES EL MISMO, NO HA HABIDO CAMBIO DE FORMA DE PAGO");exit;
    }
    else {
      // print_r("EL COMPROBANTE NO ES EL MISMO, HA CAMBIADO LA FORMA DE PAGO");exit;
      if($comprobante["IdFormaPago"] != ID_FORMA_PAGO_CONTADO && $data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
      {
        /** DESDE CREDITO A CONTADO
         *  1.- VALIDAR SI HAY COBRANZAS VINCULADAS EN EL COMPROBANTE DE VENTA
         *  2.- SE DEBE BORRAR DE LA TABLA PENDIENTE
         *  3.- RELIZAR LOS PROCESOS NORMALES
        */
        // $objeto = $this->sPendientePagoProveedor->BorrarPendientePagoProveedor($data);
        // // print_r($objeto);exit;
        // if(!is_array($objeto))
        // {
        //   return $objeto;
        // }

        if($comprobante[0]["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $objeto = $this->sPendientePagoProveedor->BorrarPendientePagoProveedor($data);
          // print_r($objeto);exit;
          if(!is_array($objeto))
          {
            return $objeto;
          }
          else
          {
            $response2 = $this->restapipendientepagoproveedor->BorrarJSONDesdePendientePagoProveedor($data);
            $borradoJSON = true;
          }
        }
        else
        {
          $objeto = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeNotaCreditoCompraCredito($data);
          if(!is_array($objeto))
          {
            return $objeto;
          }
          else
          {
            $this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($objeto);
          }
        }
      }
      elseif($comprobante["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $data["IdFormaPago"] != ID_FORMA_PAGO_CONTADO) {
        /** DESDE CONTADO A CREDITO 
         *  1.- BORRAR LOS MOVIMIENTOS Y COMPROBANTE DE CAJA VINCULADO
         *  2.- RELIZAR LOS PROCESOS NORMALES
        */
        $objeto = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeComprobanteCompra($data);
        if(!is_array($objeto))
        {
          return $objeto;
        }
      }
    }

    $resultado = "";
    switch ($data["IdTipoDocumento"]) {
      case ID_TIPODOCUMENTO_NOTACREDITO:
        $resultado = $this->sNotaCreditoCompra->ActualizarNotaCreditoCompra($data);
        break;
      case ID_TIPODOCUMENTO_NOTADEBITO:
        $resultado = $this->sNotaDebitoCompra->ActualizarNotaDebitoCompra($data);
        break;
      default:
        $resultado = $this->sCompra->ActualizarCompra($data);
        break;
    }

    //VALIDACION
    $validacion = $this->ValidarSaldoInicialCuentaPago($resultado);
    if($validacion != "")
    {
      return $validacion;
    }
    
    if(is_array($resultado)) {
      // print_r($data);exit;
      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
      {
        if($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $response = $this->sDocumentoEgreso->ActualizarDocumentoEgresoDesdeComprobanteCompra($resultado);
        }
        else
        {
          $response = $this->sDocumentoIngreso->ActualizarDocumentoIngresoDesdeComprobanteCompra($resultado);
        }
        
        if(!is_array($response))
        {
          return $response;
        }
      }
      else {
        // $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado);
        if($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado);
          if($borradoJSON == true)
          {
            $response2 = $this->restapipendientepagoproveedor->InsertarJSONDesdePendientePagoProveedor($resultado);
          }
          else
          {
            $response2 = $this->restapipendientepagoproveedor->ActualizarJSONDesdePendientePagoProveedor($resultado);
          }
        }
        else
        {
          // print_r($resultado);exit;
          $response = $this->sDocumentoEgreso->ActualizarDocumentoEgresoDesdeNotaCreditoCompraCredito($resultado);
          // print_r($response);exit;
          if(!is_array($response))
          {
            return $response;
          }
          else
          {
            $this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($response["Movimientos"]);
          }
        }
      }
    }
    return $resultado;
  }

  function EliminarCompraConCaja($data)
  {
    $resultado = $this->sCompra->EliminarCompra($data);
    if(is_array($resultado))
    {
      if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $data["IndicadorEstado"] == ESTADO_DOCUMENTO_ACTIVO)
      {
        if($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $response = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeComprobanteCompra($resultado);
        }
        else
        {
          $response = $this->sDocumentoIngreso->BorrarDocumentosIngresosDesdeComprobanteCompra($resultado);
        }

        if(!is_array($response))
        {
          return $response;
        }
      }
      elseif($data["IdFormaPago"] != ID_FORMA_PAGO_CONTADO)
      {
        // $response = $this->sPendientePagoProveedor->BorrarPendientePagoProveedor($resultado);
        // // print_r("XDDDD");print_r($response);exit;
        // if(is_string($response))
        // {
        //   return $response;
        // }
        if($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO)
        {
          $response = $this->sPendientePagoProveedor->BorrarPendientePagoProveedor($resultado);
          // print_r("XDDDD");print_r($response);exit;
          if(is_string($response))
          {
            return $response;
          }
          else
          {
            $response2 = $this->restapipendientepagoproveedor->BorrarJSONDesdePendientePagoProveedor($data);
          }
        }
        else
        {
          $response = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeNotaCreditoCompraCredito($resultado);
          if(!is_array($response))
          {
            return $response;
          }
          else
          {
            $this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($response);
          }
        }
      }
    }
    return $resultado;
  }
  
}
