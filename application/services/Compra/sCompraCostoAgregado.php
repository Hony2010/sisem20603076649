<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'services\Compra\sComprobanteCompra.php');

class sCompraCostoAgregado extends sComprobanteCompra
{
  public $ParametroCaja;
  public $DetalleCompraCostoAgregado = array();

  public function __construct()
  {

    parent::__construct();
    $this->load->library('sesionusuario');
    $this->load->service("Compra/sDocumentoReferenciaCostoAgregado");
    $this->load->service("Compra/sDetalleCompraCostoAgregado");
    $this->load->service("Configuracion/Compra/sMetodoProrrateo");
    $this->load->service("Inventario/sMovimientoAlmacen");
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Caja/sDocumentoEgreso');
    $this->load->library('RestApi/Caja/RestApiPendientePagoProveedor');
    $this->load->service('Caja/sPendientePagoProveedor');
    $this->load->service('Caja/sDocumentoIngreso');

    $DetalleCompraCostoAgregado = [];
    $DetalleCompraCostoAgregado[] = $this->sDetalleCompraCostoAgregado->Cargar();
    $this->ComprobanteCompra["DetallesCompraCostoAgregado"] = array();
    $this->ComprobanteCompra["DetallesDocumentoReferencia"] = array();
    $this->ParametroCaja = $this->sesionusuario->obtener_sesion_parametro_caja();
  }

  function CargarCompraCostoAgregado()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);
    unset($resultado["NuevoDetalleComprobanteCompra"]);

    $resultado["IdTipoCompra"] = ID_TIPOCOMPRA_COSTOAGREGADO;

    $resultado["MetodosProrrateo"] = $this->sMetodoProrrateo->ListarMetodosProrrateo();
    $resultado["NuevoDocumentoReferencia"] = $this->sDocumentoReferenciaCostoAgregado->DocumentoReferenciaCostoAgregado;

    $resultado['NuevoDetalleCompraCostoAgregado'] = $this->sDetalleCompraCostoAgregado->Cargar();

    return $resultado;
  }

  public function InsertarCompraCostoAgregado($data)
  {
    $data["DetallesComprobanteCompra"] = $data["DetallesCompraCostoAgregado"];
    unset($data["DetalleCompraCostoAgregado"]);
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

      $resultado["DetallesCompraCostoAgregado"] = $resultado["DetallesComprobanteCompra"];
      unset($resultado["DetallesComprobanteCompra"]);

      $data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
      $this->InsertarDocumentosReferenciaCostoAgregado($data);
      $this->sMovimientoAlmacen->ActualizarMovimientosAlmacenCostoAgregado($data["DetallesDocumentoReferencia"]);
    }

    return $resultado;
  }

  function ActualizarCompraCostoAgregado($data)
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

    $data["DetallesComprobanteCompra"] = $data["DetallesCompraCostoAgregado"];
    unset($data["DetalleCompraCostoAgregado"]);
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

      $resultado["DetallesCompraCostoAgregado"] = $resultado["DetallesComprobanteCompra"];
      unset($resultado["DetallesComprobanteCompra"]);

      $data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
      $this->ActualizarDocumentosReferenciaCostoAgregado($data);
      $this->sMovimientoAlmacen->ActualizarMovimientosAlmacenCostoAgregado($data["DetallesDocumentoReferencia"]);
    }

    return $resultado;
  }

  function InsertarDocumentosReferenciaCostoAgregado($data)
  {
    foreach ($data["DetallesDocumentoReferencia"] as $key => $value) {
      $value["IdComprobanteCostoAgregado"] = $data["IdComprobanteCompra"];
      $value["Cantidad"] = str_replace(',',"",$value["Cantidad"]); 
      $documentoreferencia = $this->sDocumentoReferenciaCostoAgregado->InsertarDocumentoReferenciaCostoAgregado($value);
      $this->ActualizarPrecioMinimo($value);  
    }
    return "";
  }

  function ActualizarDocumentosReferenciaCostoAgregado($data)
  {
    // print_r($data);exit;
    $movimiento = $this->sMovimientoAlmacen->DescontarParaActualizarMovimientosAlmacenCostoAgregado($data);
    // print_r($movimiento);exit;
    $this->sDocumentoReferenciaCostoAgregado->BorrarDocumentoReferenciaPorIdComprobanteCostoAgregado($data["IdComprobanteCompra"]);
    // print_r($movimiento);exit;
    foreach ($data["DetallesDocumentoReferencia"] as $key => $value) {
      $value["IdComprobanteCostoAgregado"] = $data["IdComprobanteCompra"];
      $value["Cantidad"] = str_replace(',',"",$value["Cantidad"]); 
      $documentoreferencia = $this->sDocumentoReferenciaCostoAgregado->ActualizarDocumentoReferenciaCostoAgregado($value);
      $this->ActualizarPrecioMinimo($value);  
    }
    return "";
  }

  /*Se actualizan SaldoCompraCostoAgregado en ComprobanteCompra*/
  function ActualizarSaldoCompraCostoAgregadoEnComprobanteCompra($data)
  {

    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      $nueva_data["IdComprobanteCompra"] = $value["IdComprobanteCompra"];
      $nueva_data["SaldoCompraCostoAgregado"] = $value["SaldoCompraCostoAgregado"] - $data["Total"];

      $resultado = $this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);
    }

    return $resultado;
  }

  /*Se actualizan los comprobantes de manera proporcional, dependiendo del procentaje si son varios*/
  function ActualizarSaldoCompraCostoAgregadoEnComprobanteCompraProporcional($data)
  {

    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      $nueva_data["IdComprobanteCompra"] = $value["IdComprobanteCompra"];
      $nueva_data["SaldoCompraCostoAgregado"] = $value["SaldoCompraCostoAgregado"] - (($data["Porcentaje"] / 100) * $value["SaldoCompraCostoAgregado"]);

      $resultado = $this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);
    }

    return $resultado;
  }

  /*Se actualizan los saldos en los Detalles de Comprobante Compra*/
  function ActualizarSaldoCompraCostoAgregadoEnDetalleComprobante($data)
  {
    foreach ($data["DetallesCompraCostoAgregado"] as $key => $value) {
      $nueva_data["IdDetalleComprobanteCompra"] = $value["IdDetalleComprobanteCompra"];
      $nueva_data["SaldoPendienteCompraCostoAgregado"] = $value["SaldoPendienteCompraCostoAgregado"] - $value["SubTotal"];
      // $nueva_data["SaldoCompraCostoAgregado"] = $value["SaldoCompraCostoAgregado"] - $data["Total"];
      // parent::ActualizarDetalleComprobanteCompra($nueva_data);
      $this->sDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($nueva_data);
    }
    // $resultado = parent::sDetalleComprobanteCompra->ActualizarSaldoCompraCostoAgregadoDetalle($data["DetallesCompraCostoAgregado"]);
    return "";
  }

  function ActualizarSaldosEnComprobante($data)
  {
    if ($data["ActualizarDetalle"] == '1') {
      $this->ActualizarSaldoCompraCostoAgregadoEnDetalleComprobante($data);
    }

    if ($data["TotalProporcional"] == '1') {
      $this->ActualizarSaldoCompraCostoAgregadoEnComprobanteCompraProporcional($data);
    } else {
      $this->ActualizarSaldoCompraCostoAgregadoEnComprobanteCompra($data);
    }

    return "";
  }

  function ActualizarPrecioMinimo($data) {
    
    $resultadoPrecio=$this->sListaPrecioMercaderia->ObtenerPrecioMinimoPorIdProducto($data);    
    $CostoUnitarioCalculado=str_replace(",","",$data["CostoUnitarioCalculado"]);
    $MontoProrrateadoPorUnidad=str_replace(",","",$data["MontoProrrateadoPorUnidad"]);
    $PrecioUnitario = ($CostoUnitarioCalculado + $MontoProrrateadoPorUnidad) * 1.18;
    if (count($resultadoPrecio)>0) {      
      $resultadoPrecio[0]["Precio"] = $PrecioUnitario;
      $this->sListaPrecioMercaderia->ActualizarListaPrecioMercaderia($resultadoPrecio[0]);      
    }
    else {
      $resultadoTipoListaPrecio = $this->sTipoListaPrecio->ObtenerTipoListaPrecioMinimo();
      if (count($resultadoTipoListaPrecio) > 0) {
        $resultadoPrecio["IdTipoListaPrecio"]=$resultadoTipoListaPrecio[0]["IdTipoListaPrecio"];
        $resultadoPrecio["IdProducto"]=$data["IdProducto"];
        $resultadoPrecio["Precio"] = $PrecioUnitario;
        $this->sListaPrecioMercaderia->InsertarListaPrecioMercaderia($resultadoPrecio);
      }
    }
  }
}
