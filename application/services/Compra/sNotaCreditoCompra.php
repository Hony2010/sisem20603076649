<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Compra\sComprobanteCompra.php');

class sNotaCreditoCompra extends sComprobanteCompra {

  public $DetalleNotaCreditoCompra = array();

  public function __construct()
  {

    parent::__construct();

    $this->load->service('Configuracion/Inventario/sMotivoNotaSalida');
    $this->load->service("Configuracion/Venta/sMotivoNotaCredito");
    $this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
    $this->load->service("Inventario/sNotaSalida");
    $this->load->service("Compra/sDocumentoReferenciaCompra");
    $this->load->service("Compra/sDetalleNotaCreditoCompra");
    $this->load->service("Compra/sDetalleDocumentoReferenciaCompra");
    $this->load->service("Compra/sDocumentoReferenciaCostoAgregado");

    $DetalleNotaCreditoCompra = [];
    $DetalleNotaCreditoCompra[] = $this->sDetalleNotaCreditoCompra->Cargar();
    $this->ComprobanteCompra["DetallesNotaCreditoCompra"] = $DetalleNotaCreditoCompra;
  }

  function CargarNotaCreditoCompra()
  {
    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTACREDITO;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);

    // $resultado["IdCorrelativoDocumento"] = $resultado["SeriesDocumento"][0]["IdCorrelativoDocumento"];
    // $resultado["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTACREDITO;
    $resultado["ActualizarDetalle"] = "0";
    $resultado["TotalProporcional"] = "0";
    $resultado["NombreAlmacen"] = $this->sSede->ListarSedesTipoAlmacen()[0]["NombreSede"];
    $resultado["IdMotivoNotaSalida"] = ID_MOTIVO_NOTA_SALIDA_DEVOLUCION_PROVEEDOR_CON_DOCUMENTO;
    $resultadoMotivoSalida = $this->sMotivoNotaSalida->ObtenerMotivoNotaSalida($resultado);
    $resultado["MotivoMovimiento"]=$resultadoMotivoSalida->NombreMotivoNotaSalida;

    $resultado["EstadoPendienteNota"] = '0';
    $resultado["EstadoPendienteComprobante"] = '0';

    $fechaservidor=$this->Base->ObtenerFechaServidor("d/m/Y");

    // $resultado["SaldoNotaCredito"] = "00.00";

    $resultado["TotalSaldo"] = "00.00";
    $resultado["Concepto"] = "";
    $resultado["MontoLetra"] = "";
    $resultado["Porcentaje"] = "0.00";
    $resultado["PorcentajeOld"] = "0.00";
    $resultado["Importe"] = "0.00";
    $resultado["IdSede"] =  $parametro['IdSedeAgencia'];
    $resultado["IdPersona"] = 0;
    $resultado["ListaIdsDetalle"] = array();
    // $resultado["IdPeriodo"] = 10;
    $resultado["FechaIngreso"] = $fechaservidor;
    $MotivosNotaCreditoCompra = $this->sMotivoNotaCredito->ListarMotivosNotaCreditoCompra();
    $ConceptosNotaCreditoCompra = $this->sConceptoNotaCreditoDebito->ListarConceptosNotaCredito();

    $resultado["MotivosNotaCreditoCompra"] = $MotivosNotaCreditoCompra;
    $resultado["ConceptosNotaCreditoCompra"] = $ConceptosNotaCreditoCompra;
    $resultado["BusquedaComprobantesCompraNC"] = array();
    $resultado["BusquedaComprobanteCompraNC"] = array();
    $resultado["MiniComprobantesCompraNC"] = array();//$this->ComprobanteCompra;//array();
    $resultado["GrupoDetalleComprobanteCompra"] = array();
    $resultado["DocumentosReferencia"] = array();

    $resultado['NuevoDetalleNotaCreditoCompra']=$this->sDetalleNotaCreditoCompra->Cargar();
    $resultado['NuevoDetalleNotaCreditoCompra']["ParametroPrecioCompra"]=$resultado["ParametroPrecioCompra"];
    
    return $resultado;
  }

  /*****/
  function InsertarNotaCreditoCompra($data)
  {
    $data["DetallesComprobanteCompra"] = $data["DetallesNotaCreditoCompra"];
    $otra_data = $data;
    foreach ($otra_data["DetallesComprobanteCompra"] as $key => $value) {
      $otra_data["DetallesComprobanteCompra"][$key]["SaldoPendienteNotaCredito"] = 0;
      $otra_data["DetallesComprobanteCompra"][$key]["SaldoPendienteEntrada"] = $value["Cantidad"];
    }
    
    $resultado = parent::InsertarComprobanteCompra($otra_data);
    
    $motivoAfectarCosto = $data["MotivoNotaCreditoCompra"]["Reglas"]["AfectarCosto"];
    $motivoAfectarAlmacen = $data["MotivoNotaCreditoCompra"]["Reglas"]["AfectarAlmacen"];

    if(is_array($resultado))
    {
      $data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
      $data["NumeroDocumento"] = $resultado["NumeroDocumento"];
      if(array_key_exists("MiniComprobantesCompraNC", $data))
      {
        $this->InsertarDocumentosReferencia($data);
      }
      // print_r($resultado);exit;
      $documentosReferencia = $data["MiniComprobantesCompraNC"];
      if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA && $motivoAfectarAlmacen == 1) {
        //$documentosReferencia = 
        $this->InsertarNotaSalida($data);
      }

      $documentosReferencia = $this->ActualizarSaldosEnComprobante($data);
      $resultado["DocumentosReferencia"] = $documentosReferencia;

      if($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA && $motivoAfectarCosto == 1)
      {
        $this->sMovimientoAlmacen->ActualizarMovimientosAlmacenNotaCreditoCompra($resultado["DetallesComprobanteCompra"]);
      }
      
      if($resultado["IdTipoCompra"] == ID_TIPOCOMPRA_COSTOAGREGADO)
      {
        $costosAgregados = $this->AnularDocumentosReferenciaCostoAgregado($data);
      }
            
      return $resultado;
    }
    else{
      $data_error["error"]["msg"] = $resultado;
      return $data_error;
    }
  }

  function ActualizarNotaCreditoCompra($data)
  {
    $data["DetallesComprobanteCompra"] = $data["DetallesNotaCreditoCompra"];

    $motivoAfectarCosto = $data["MotivoNotaCreditoCompra"]["Reglas"]["AfectarCosto"];
    $motivoAfectarAlmacen = $data["MotivoNotaCreditoCompra"]["Reglas"]["AfectarAlmacen"];

    $this->sMovimientoAlmacen->DescontarParaActualizarMovimientosAlmacenNotaCreditoCompra($data);
    //REVERTIMOS LOS SALDOS POR NOTA DE CREDITO
    $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
    $this->RevertirSaldosCabecerayDetalleEnComprobanteReferencias($data);
    //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
    $documentosReferenciaNC = $this->sDocumentoReferenciaCompra->BorrarDocumentoReferenciaCompra($data);
    // print_r($documentosReferenciaNC);exit;
    //PARA NOTA CREDITO POR COSTOS AGREGADOS
    $dataAlternativo["MiniComprobantesCompraNC"] = $documentosReferenciaNC;
    $this->ActivarDocumentosReferenciaCostoAgregado($dataAlternativo);

    $data["DetallesComprobanteCompra"] = $data["DetallesNotaCreditoCompra"];
    $otra_data = $data;
    foreach ($otra_data["DetallesComprobanteCompra"] as $key => $value) {
      $otra_data["DetallesComprobanteCompra"][$key]["SaldoPendienteNotaCredito"] = 0;
      $otra_data["DetallesComprobanteCompra"][$key]["SaldoPendienteEntrada"] = $value["Cantidad"];
    }
    $resultado = parent::ActualizarComprobanteCompra($otra_data);

    if(is_array($resultado))
    {
      $data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
      $data["NumeroDocumento"] = $resultado["NumeroDocumento"];
      if(array_key_exists("MiniComprobantesCompraNC", $data))
      {
        $this->InsertarDocumentosReferencia($data);
      }

      $documentosReferencia = $data["MiniComprobantesCompraNC"];
      if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA && $motivoAfectarAlmacen == 1) {
        $documentosReferencia = $this->ActualizarNotaSalida($data);
      }

      if($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA && $motivoAfectarCosto == 1)
      {
        $this->sMovimientoAlmacen->ActualizarMovimientosAlmacenNotaCreditoCompra($resultado["DetallesComprobanteCompra"]);
      }

      if($resultado["IdTipoCompra"] == ID_TIPOCOMPRA_COSTOAGREGADO)
      {
        $this->AnularDocumentosReferenciaCostoAgregado($data);
      }

      $resultado["DocumentosReferencia"] = $documentosReferencia;
      return $resultado;
    }
    else{
      $data_error["error"]["msg"] = $resultado;
      return $data_error;
    }
  }

  function BorrarNotaCreditoCompra($data)
  {
    $this->sDetalleNotaCreditoCompra->BorrarDetallesNotaCreditoCompra($data);
    $resultado = $this->mComprobanteCompra->BorrarComprobanteCompra($data);

    return "";
  }

  function BorrarNotaCreditoCompraDesdeServicioCompra($data)
  {
    //REVERTIMOS LOS SALDOS POR NOTA DE CREDITO
    $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
    $this->RevertirSaldosCabecerayDetalleEnComprobanteReferencias($data);

    $this->sMovimientoAlmacen->DescontarParaActualizarMovimientosAlmacenNotaCreditoCompra($data);
    //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
    $documentosReferenciaNC["MiniComprobantesCompraNC"] = $this->sDocumentoReferenciaCompra->BorrarDocumentoReferenciaCompra($data);
    // print_r($documentosReferencia);exit;
    if($data["IdTipoCompra"] == ID_TIPOCOMPRA_COSTOAGREGADO)
    {
      $this->ActivarDocumentosReferenciaCostoAgregado($documentosReferenciaNC);
    }
  }

  function InsertarDocumentosReferencia($data)
  {
    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      $value["IdComprobanteNota"] = $data["IdComprobanteCompra"];
      $documentoreferencia = $this->sDocumentoReferenciaCompra->InsertarDocumentoReferenciaCompra($value);
    }
    return "";
  }

  /**PARA NOTA DE CREDITO DE COSTO AGREGADO */
  function AnularDocumentosReferenciaCostoAgregado($data)
  {
    $resultado = array();
    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      $resultado = $this->sDocumentoReferenciaCostoAgregado->AnularDocumentosReferenciaPorIdComprobanteCostoAgregado($value);
    }
    return $resultado;
  }

  function ActivarDocumentosReferenciaCostoAgregado($data)
  {
    $resultado = array();
    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      $resultado = $this->sDocumentoReferenciaCostoAgregado->ActivarDocumentosReferenciaPorIdComprobanteCostoAgregado($value);
    }
    return $resultado;
  }
  /**FIN PARA NOTA DE CREDITO DE COSTO AGREGADO */

  function InsertarNotaSalida($data)
  {
    $resultado = "";
    if($data["EstadoPendienteNota"] == '0' && $data["ActualizarDetalle"] == '1'){
      $resultado = $this->sNotaSalida->InsertarNotaSalidaDesdeComprobanteCompra($data);
    }

    // if(is_array($entrada))
    // {
    //$response = $this->ActualizarSaldosEnComprobante($data);
    // }

    return $resultado;
  }

  function ActualizarNotaSalida($data)
  {
    $entrada = "";
    if($data["EstadoPendienteNota"] == '0' && $data["ActualizarDetalle"] == '1'){
      $entrada = $this->sNotaSalida->ActualizarNotaSalidaDesdeComprobanteCompra($data);
    }

    $response = $this->ActualizarSaldosEnComprobante($data);
    // if(is_array($entrada))
    // {
    // }
    return $response;
  }

  /* AQUI SE ACTUALIZARA LO QUE SON LAS REFERENCIAS CON SUS RESPECTIVOS SALDOS*/
  /*Se actualizan SaldoNotaCredito en ComprobanteCompra*/
  function ActualizarSaldoNotaCreditoCompraEnComprobanteCompra($data)
  {
    $documentosReferencia = $data["MiniComprobantesCompraNC"];
    foreach ($documentosReferencia as $key => $value) {
      // code...
      $nueva_data = array();
      $comprobante = parent::ObtenerComprobanteCompraPorIdComprobante($value);
      $nueva_data["IdComprobanteCompra"] = $value["IdComprobanteCompra"];
      $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] - $data["Total"];

      $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);

      $nueva_data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
      $nueva_data["Total"] = $data["Total"];

      $documentoReferencia = $this->sDocumentoReferenciaCompra->ActualizarSaldosEnDocumentoReferenciaCompra($nueva_data);
      $documentoReferencia = array_merge($value, $documentoReferencia);
      $documentosReferencia[$key] = $documentoReferencia;
    }
    return $documentosReferencia;
  }

  function RevertirSaldosEnComprobanteCompraDesdeReferencia($data)
  {
    $resultado = $this->sDocumentoReferenciaCompra->ConsultarComprobantesPorIdNota($data);
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $comprobante = parent::ObtenerComprobanteCompraPorIdComprobante($value);
        if($comprobante)
        {
          $nueva_data["IdComprobanteCompra"] = $comprobante["IdComprobanteCompra"];
          $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] + $value["TotalNota"];
          $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);
        }
      }
    }
    return $data;
  }

  /*Se actualizan los comprobantes de manera proporcional, dependiendo del procentaje si son varios*/
  function ActualizarSaldoNotaCreditoCompraEnComprobanteCompraProporcional($data)
  {
    $documentosReferencia = $data["MiniComprobantesCompraNC"];
    foreach ($documentosReferencia as $key => $value) {
      // code...
      $nueva_data = array();
      $comprobante = parent::ObtenerComprobanteCompraPorIdComprobante($value);
      $nueva_data["IdComprobanteCompra"] = $comprobante["IdComprobanteCompra"];
      $SaldoPorcentaje = ($data["Porcentaje"] / 100) * $comprobante["Total"];
      $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] - $SaldoPorcentaje;
      
      $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);

      $nueva_data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
      $nueva_data["Total"] = $SaldoPorcentaje;
      $documentoReferencia = $this->sDocumentoReferenciaCompra->ActualizarSaldosEnDocumentoReferenciaCompra($nueva_data);
      $documentoReferencia = array_merge($value, $documentoReferencia);
      $documentosReferencia[$key] = $documentoReferencia;
    }

    return $documentosReferencia;
  }

  /*Se actualizan los saldos en los Detalles de Comprobante Compra*/
  function ActualizarSaldoNotaCreditoCompraEnDetalleComprobante($data)
  {
    foreach ($data["DetallesNotaCreditoCompra"] as $key => $value) {
      $nueva_data["IdDetalleComprobanteCompra"] = $value["IdDetalleReferencia"];
      $detalle = $this->sDetalleComprobanteCompra->ConsultarDetalleComprobanteCompraPorId($nueva_data);      
      $detalle[0]["SaldoPendienteNotaCredito"] = (is_string($detalle[0]["SaldoPendienteNotaCredito"])) ? str_replace(',',"",$detalle[0]["SaldoPendienteNotaCredito"]) : $detalle[0]["SaldoPendienteNotaCredito"]; //$nueva_data["SaldoPendienteNotaCredito"] = $detalle[0]["SaldoPendienteNotaCredito"] - $value["CostoItem"];
      //$value["SubTotal"] = (is_string($value["SubTotal"])) ? str_replace(',',"",$value["SubTotal"]) : $value["SubTotal"]; 
      //$nueva_data["SaldoPendienteNotaCredito"] = $detalle[0]["SaldoPendienteNotaCredito"] - $value["SubTotal"];
      $value["CostoItem"] = (is_string($value["CostoItem"])) ? str_replace(',',"",$value["CostoItem"]) : $value["CostoItem"]; 
      $nueva_data["SaldoPendienteNotaCredito"] = $detalle[0]["SaldoPendienteNotaCredito"] - $value["CostoItem"];
      
      // $nueva_data["SaldoNotaCredito"] = $value["SaldoNotaCredito"] - $data["Total"];
      // parent::ActualizarDetalleComprobanteCompra($nueva_data);
      $this->sDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($nueva_data);

      $value["IdComprobanteNota"] = $data["IdComprobanteCompra"];
      $this->sDetalleDocumentoReferenciaCompra->InsertarDetalleDocumentoReferenciaCompra($value);
    }
    // $resultado = parent::sDetalleComprobanteCompra->ActualizarSaldoNotaCreditoCompraDetalle($data["DetallesNotaCreditoCompra"]);
    return "";
  }

  function RevertirSaldosEnDetalleComprobanteCompraDesdeReferencia($data)
  {
    $resultado = $this->sDetalleDocumentoReferenciaCompra->ConsultarDetallesDocumentoReferencia($data);
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $comprobante = $this->sDetalleComprobanteCompra->ConsultarDetalleComprobanteCompraPorId($value);
        if($comprobante)
        {
          $nueva_data["IdDetalleComprobanteCompra"] = $comprobante[0]["IdDetalleComprobanteCompra"];
          $nueva_data["SaldoPendienteNotaCredito"] = $comprobante[0]["SaldoPendienteNotaCredito"] + $value["SaldoDetalleDocumentoReferencia"];
          $resultado=$this->sDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($nueva_data);
        }
      }
    }
    return $data;
  }
  
  function RevertirSaldosCabecerayDetalleEnComprobanteReferencias($data)
  {
    $this->RevertirSaldosEnComprobanteCompraDesdeReferencia($data);
    $this->RevertirSaldosEnDetalleComprobanteCompraDesdeReferencia($data);
    //AQUI BORRAMOS LOS DETALLES EN SI
    $this->sDetalleDocumentoReferenciaCompra->BorrarDetallesDocumentoReferenciaCompraPorIdNota($data);
  }
  /*FIN DE LOS COMPROBANTES */

  function ActualizarSaldosEnComprobante($data)
  {
    if($data["ActualizarDetalle"] == '1')
    {
      $this->ActualizarSaldoNotaCreditoCompraEnDetalleComprobante($data);
    }

    $documentoReferencia = array();
    if($data["TotalProporcional"] == '1'){
      $documentoReferencia = $this->ActualizarSaldoNotaCreditoCompraEnComprobanteCompraProporcional($data);
    }
    else{
      $documentoReferencia = $this->ActualizarSaldoNotaCreditoCompraEnComprobanteCompra($data);
    }

    return $documentoReferencia;
  }


  /*Se actualizan SaldoNotaCredito en ComprobanteCompra*/
  function BorrarSaldoNotaCreditoCompraEnComprobanteCompra($data)
  {
    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      // code...
      $comprobante = parent::ObtenerComprobanteCompraPorIdComprobante($value);
      $nueva_data["IdComprobanteCompra"] = $value["IdComprobanteCompra"];
      $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] + $data["SaldoNotaCredito"];

      $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);
    }

    return $resultado;
  }

  /*Se actualizan los comprobantes de manera proporcional, dependiendo del procentaje si son varios*/
  function BorrarSaldoNotaCreditoCompraEnComprobanteCompraProporcional($data)
  {
    foreach ($data["MiniComprobantesCompraNC"] as $key => $value) {
      // code...
      $comprobante = parent::ObtenerComprobanteCompraPorIdComprobante($value);
      $nueva_data["IdComprobanteCompra"] = $value["IdComprobanteCompra"];
      $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] - (($data["Porcentaje"] / 100) * $value["SaldoNotaCredito"]);

      $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($nueva_data);
    }

    return $resultado;
  }

  function RevertirSaldosEnComprobante($data)
  {
    if($data["TotalProporcional"] == '1'){
      // $this->BorrarSaldoNotaCreditoCompraEnComprobanteCompraProporcional($data);
    }
    else{
      $this->BorrarSaldoNotaCreditoCompraEnComprobanteCompra($data);
    }

    return "";
  }

}
