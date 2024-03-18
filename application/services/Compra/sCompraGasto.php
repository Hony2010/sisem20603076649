<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'services\Compra\sComprobanteCompra.php');

class sCompraGasto extends sComprobanteCompra
{
  public $ParametroCaja;
  public $DetalleCompraGasto = array();

  public function __construct()
  {

    parent::__construct();
    $this->load->library('sesionusuario');
    $this->load->service("Compra/sDetalleCompraGasto");
    $this->load->service('Configuracion/General/sMoneda');

    $this->load->library('RestApi/Caja/RestApiPendientePagoProveedor');
    $this->load->service('Caja/sPendientePagoProveedor');
    $this->load->service('Caja/sDocumentoIngreso');
    $this->load->service('Caja/sDocumentoEgreso');

    $DetalleCompraGasto = [];
    $DetalleCompraGasto[] = $this->sDetalleCompraGasto->Cargar();
    $this->ComprobanteCompra["DetallesCompraGasto"] = array();
    $this->ParametroCaja = $this->sesionusuario->obtener_sesion_parametro_caja();
  }

  function CargarCompraGasto()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);

    $resultado["IdTipoCompra"] = ID_TIPOCOMPRA_GASTO;
    $resultado['NuevoDetalleCompraGasto'] = $this->sDetalleCompraGasto->Cargar();

    return $resultado;
  }

  /*****/
  public function InsertarCompraGasto($data)
  {
    $data["DetallesComprobanteCompra"] = $data["DetallesCompraGasto"];
    unset($data["DetalleCompraGasto"]);
    $resultado = parent::InsertarComprobanteCompra($data);

    if (is_array($resultado)) {

      //SE CONDICIONA POR EL TEMA DE CAJA
      if($this->ParametroCaja == 1)
      {
        if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
        {
          $response = $this->sDocumentoEgreso->InsertarDocumentoEgresoDesdeComprobanteCompra($resultado); 
          if(!is_array($response))
          {
            return $response;
          }
        }
        else
        {
          $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado);
          $response2 = $this->restapipendientepagoproveedor->InsertarJSONDesdePendientePagoProveedor($resultado);
        }
      }

      $resultado["DetallesCompraGasto"] = $resultado["DetallesComprobanteCompra"];
      unset($resultado["DetallesComprobanteCompra"]);
    }

    return $resultado;
  }

  function BorrarCompraGasto($data)
  {
    $this->sDetalleCompraGasto->BorrarDetallesCompraGasto($data);
    $resultado = $this->mComprobanteCompra->BorrarComprobanteCompra($data);
    return "";
  }

  function ActualizarCompraGasto($data)
  {
    //VALIDACIONES
    $documentoreferenciacompra = $this->ValidarComprobanteEnReferenciaCompra($data);
    if ($documentoreferenciacompra != "" )
    {
      return $documentoreferenciacompra;
    }

    //PREDATA CAJA
    if($this->ParametroCaja == 1)
		{
      $borradoJSON = false;
      $comprobante = parent::ObtenerComprobanteCompraPorIdComprobante($data);
      if($data["IdFormaPago"] == $comprobante["IdFormaPago"])
      {
        // print_r("EL COMPROBANTE ES EL MISMO, NO HA HABIDO CAMBIO DE FORMA DE PAGO");exit;
      }
      else {
        // print_r("EL COMPROBANTE NO ES EL MISMO, HA CAMBIADO LA FORMA DE PAGO");exit;
        if($comprobante["IdFormaPago"] != ID_FORMA_PAGO_CONTADO && $data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
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
        elseif($comprobante["IdFormaPago"] == ID_FORMA_PAGO_CONTADO && $data["IdFormaPago"] != ID_FORMA_PAGO_CONTADO) {
          $objeto = $this->sDocumentoEgreso->BorrarDocumentosEgresosDesdeComprobanteCompra($data);
          if(!is_array($objeto))
          {
            return $objeto;
          }
        }
      }
    }

    $data["DetallesComprobanteCompra"] = $data["DetallesCompraGasto"];
    unset($data["DetalleCompraGasto"]);
    $resultado = parent::ActualizarComprobanteCompra($data);

    if (is_array($resultado)) {

      $moneda = $this->sMoneda->ObtenerMonedaPorId($resultado);
      if (count($moneda) > 0) {
        $resultado["SimboloMoneda"] = $moneda[0]["SimboloMoneda"];
      }

      //SE CONDICIONA POR EL TEMA DE CAJA
      if($this->ParametroCaja == 1)
      {
        if($data["IdFormaPago"] == ID_FORMA_PAGO_CONTADO)
        {
          $response = $this->sDocumentoEgreso->InsertarDocumentoEgresoDesdeComprobanteCompra($resultado); 
          if(!is_array($response))
          {
            return $response;
          }
        }
        else
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
      }

      $resultado["DetallesCompraGasto"] = $resultado["DetallesComprobanteCompra"];
      unset($resultado["DetallesComprobanteCompra"]);
    }

    return $resultado;
  }
}
