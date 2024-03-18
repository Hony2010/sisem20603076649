<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sDocumentoEgreso extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
		$this->load->library('tipocambiosunat');
    $this->load->service('Caja/sTipoOperacionCaja');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Caja/sPendientePagoProveedor');
    $this->load->service('Configuracion/General/sMedioPago');
  }

  function Cargar()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_VOUCHER_EGRESO;//ID_TIPO_DOCUMENTO_RECIBO_EGRESO."','".
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $resultado = parent::CargarComprobanteCaja($parametro);

    $Motivos = $this->sTipoOperacionCaja->ListarTiposOperacionCajaParaDocumentoEgreso();
    $resultado["TiposOperacionCaja"] = $Motivos;
    $resultado["IdTipoOperacionCaja"] = ID_TIPO_OPERACION_CAJA_OTRO_EGRESO;//(count($Motivos) > 0) ? $Motivos[0]["IdTipoOperacionCaja"] : "";

    $MediosPago = $this->sMedioPago->ListarMediosPago();
    $resultado["MediosPago"] = $MediosPago;
    $resultado["IdMedioPago"] = (count($MediosPago) > 0) ? $MediosPago[0]->IdMedioPago : "";

    $resultado["CopiaSeries"] = $resultado["SeriesDocumento"];

    //PARA OBTENER TIPO CAMBIO
    $TipoCambioActual = $this->tipocambiosunat->ConsultarTipoCambioCompra();
    $ValorTipoCambio = ($TipoCambioActual == "") ? "" : $TipoCambioActual;
    $resultado["ValorTipoCambio"] = $ValorTipoCambio;

    $resultado["NuevoDocumentoEgreso"] = $resultado;

    return $resultado;
  }

  /**FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */
  function InsertarDocumentoEgresoDesdeEgreso($data)
  {
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaComprobante"];
    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
    
    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarTurnoYAperturaCajaInsercion($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }

    $resultado = parent::InsertarComprobanteCaja($data);
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $response = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($data);

      $saldoCajaNegativo = $this->sSaldoCajaTurno->ValidarSaldoCajaTurnoNegativo($data);
      if(is_string($saldoCajaNegativo))
      {
        return $saldoCajaNegativo;
      }

      return $resultado;
    }
    else {
      return "";
    }
  }

  function InsertarDocumentoEgresoDesdeComprobanteVenta($data, $option = false)
  {
    $data = array_merge($this->ComprobanteCaja, $data);
    $cabecera = $data;
    $data["IdComprobanteCaja"] = "";
    $data["IdTipoOperacionCaja"] = ($data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTACREDITO || $data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTADEVOLUCION) ? ID_TIPO_OPERACION_CAJA_EGRESO_NC_CLIENTE : ID_TIPO_OPERACION_CAJA_VENTA_CONTADO;

    if ($data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTACREDITO || $data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTADEVOLUCION)  {
      $data['IdTipoDocumento'] =  $data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTACREDITO ? ID_TIPO_DOCUMENTO_VOUCHER_EGRESO : ID_TIPO_DOCUMENTO_RECIBO_EGRESO;
    }
    else {
     $data['IdTipoDocumento'] = ($data['IdTipoDocumento'] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) ? ID_TIPO_DOCUMENTO_RECIBO_INGRESO : ID_TIPO_DOCUMENTO_VOUCHER_INGRESO;
    }

    $data["MontoComprobante"] = (is_string($data["Total"])) ? str_replace(',',"",$data["Total"]) : $data["Total"];
    $data['IdPersona'] = $data['IdCliente'];

    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $parametro['IdTipoDocumento'] = $data['IdTipoDocumento'];
    $parametro['IdSedeAgencia'] = (count($asignacionsede)>0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : '';//$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    $data["NumeroDocumento"] = "";

    $data["IdMedioPago"] = ID_MEDIO_PAGO_EFECTIVO;
    $data["FechaComprobante"] = convertToDate($data["FechaEmision"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    // $data["IdTurno"] = $this->sesionusuario->obtener_sesion_turno_usuario()["IdTurno"];

    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarTurnoYAperturaCajaInsercion($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }
    
    //OBTENER ULTIMA CAJA APERTURADA POR USUARIO, TURNO Y CAJA
    /**AQUI PONEMOS LA FECHA TURNO DEPENDIENDO DE DONDE ESTA VINIENDO */
    $data["FechaTurno"] = ($option) ? $cabecera["FechaTurno"] : $data["FechaTurno"];//convertToDate($data["FechaTurno"]);
    $resultado = parent::InsertarComprobanteCaja($data);
    if($resultado)
    {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $response = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($data);
    }

    return $resultado;
  }

  function InsertarDocumentoEgresoDesdeComprobanteCompra($data, $option = false)
  {
    $data = array_merge($this->ComprobanteCaja, $data);
    $cabecera = $data;
    //SOLO PARA NOTAS DE CREDITO
    $data["IdComprobanteCaja"] = "";
    $data["IdTipoOperacionCaja"] = ($data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTACREDITO || $data['IdTipoDocumento'] == ID_TIPODOCUMENTO_NOTADEVOLUCION) ? ID_TIPO_OPERACION_CAJA_INGRESO_NC_PROVEEDOR : ID_TIPO_OPERACION_CAJA_COMPRA_CONTADO;
    $data["IdTipoDocumento"] = ID_TIPO_DOCUMENTO_VOUCHER_EGRESO;
    $data["MontoComprobante"] = (is_string($data["Total"])) ? str_replace(',',"",$data["Total"]) : $data["Total"];
    $data['IdPersona'] = $data['IdProveedor'];

    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $parametro['IdTipoDocumento'] = $data["IdTipoDocumento"];
    $parametro['IdSedeAgencia'] = (count($asignacionsede)>0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : '';//$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    $data["NumeroDocumento"] = "";

    $data["IdMedioPago"] = ID_MEDIO_PAGO_EFECTIVO;
    $data["FechaComprobante"] = convertToDate($data["FechaEmision"]);
    // $data["FechaCaja"] = $data["FechaComprobante"];
    // $data["IdTurno"] = $this->sesionusuario->obtener_sesion_turno_usuario()["IdTurno"];

    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarTurnoYAperturaCajaInsercion($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }
    
    //OBTENER ULTIMA CAJA APERTURADA POR USUARIO, TURNO Y CAJA
    /**AQUI PONEMOS LA FECHA TURNO DEPENDIENDO DE DONDE ESTA VINIENDO */
    $data["FechaTurno"] = ($option) ? $cabecera["FechaTurno"] : $data["FechaTurno"];//convertToDate($data["FechaTurno"]);
    $resultado = parent::InsertarComprobanteCaja($data);
    if($resultado)
    {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $response = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($data);
    }

    return $resultado;
  }
  /**FIN FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */

  /**FUNCIONES DE ACTUALIZADO PARA DOCUMENTO DE EGRESO */
  function ActualizarDocumentoEgresoDesdeEgreso($data)
  {
    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
    
    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarCajaAperturadaParaVentaYCompraActualizacion($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }

    $resultado = parent::ActualizarComprobanteCaja($data);
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $responseBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($data);
      $responseInsertado = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($data);

      $saldoCajaNegativo = $this->sSaldoCajaTurno->ValidarSaldoCajaTurnoNegativo($data);
      if(is_string($saldoCajaNegativo))
      {
        return $saldoCajaNegativo;
      }

      return $resultado;
    }
    else {
      return "";
    }
  }
  /**FIN DE FUNCIONES DE ACTUALIZADO PARA DOCUMENTO DE EGRESO */

  /**FUNCIONES DE BORRADO PARA DOCUMENTO DE EGRESO */
  function BorrarDocumentoEgresoDesdeEgreso($data)
  {
    $validacion = $this->ValidarDocumentoEgresoParaEliminadoYAnulado($data);
    if ($validacion != "")
    {
      return $validacion;
    }

    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
    
    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarCajaAperturadaParaVentaYCompraActualizacion($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }

    $resultado = parent::BorrarComprobanteCaja($data);
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $responseBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($data);

      $saldoCajaNegativo = $this->sSaldoCajaTurno->ValidarSaldoCajaTurnoNegativo($data);
      if(is_string($saldoCajaNegativo))
      {
        return $saldoCajaNegativo;
      }

      return $resultado;
    }
    else {
      return "";
    }
  }
  /**FIN DE FUNCIONES DE BORRADO PARA DOCUMENTO DE EGRESO */

  /**FUNCIONES DE ANULADO PARA DOCUMENTO DE EGRESO */
  function AnularDocumentoEgresoDesdeEgreso($data)
  {
    $validacion = $this->ValidarDocumentoEgresoParaEliminadoYAnulado($data);
    if ($validacion != "")
    {
      return $validacion;
    }

    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
    
    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarCajaAperturadaParaVentaYCompraActualizacion($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }

    $resultado = parent::AnularComprobanteCaja($data);
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $responseBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($data);

      $saldoCajaNegativo = $this->sSaldoCajaTurno->ValidarSaldoCajaTurnoNegativo($data);
      if(is_string($saldoCajaNegativo))
      {
        return $saldoCajaNegativo;
      }

      return $resultado;
    }
    else {
      return "";
    }
  }
  /**FIN DE FUNCIONES DE ANULADO PARA DOCUMENTO DE EGRESO */

  /**VALIDACIONES PARA ELIMINADO Y ANULADO */
  private function ValidarDocumentoEgresoParaEliminadoYAnulado($data)
  {
    $resultado = $this->sMovimientoCaja->ValidarComprobanteCajaParaVentaOCompra($data);
    if(count($resultado) > 0)
    {
      return "No se puede eliminar o anular porque este comprobante fue generado por el sistema.";
    }
    else{
      return "";
    }
  }
  /**FIN VALIDACIONES PARA ELIMINADO Y ANULADO */

  /*
  Se borran documentos vinculados
  - SE USA PARA ACTUALIZACION DE COMPROBANTES
  */
  function BorrarDocumentosEgresosDesdeComprobanteVenta($data)
  {
    $resultado = $this->sMovimientoCaja->ObtenerDocumentosPorIdComprobanteVenta($data);

    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        //VALIDAMOS CADA COMPROBANTE, SI UNO ESTA FUERA DE FECHA SE OMITE
        $value["FechaCaja"] = $value["FechaTurno"];
        $resultado[$key]["FechaCaja"] = $value["FechaCaja"];
        $resultado[$key]["FechaTurno"] = $value["FechaTurno"];
        //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
        $response = $this->sSaldoCajaTurno->ValidarCajaAperturadaParaVentaYCompraActualizacion($value);
        if(is_array($response))
        {
          $value = $response;
        }
        else {
          return $response;
        }

        $borrado = parent::BorrarComprobanteCaja($value);
        //borramos movimiento de almacen de esas notas entrada
        $movimientos = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($value);
      }
    }
    return $resultado;
  }

  function BorrarDocumentosEgresosDesdeComprobanteCompra($data)
  {
    $resultado = $this->sMovimientoCaja->ObtenerDocumentosPorIdComprobanteCompra($data);

    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        //VALIDAMOS CADA COMPROBANTE, SI UNO ESTA FUERA DE FECHA SE OMITE
        $value["FechaCaja"] = $value["FechaTurno"];
        $resultado[$key]["FechaCaja"] =  $value["FechaTurno"];//$value["FechaCaja"];
        $resultado[$key]["FechaTurno"] =  $value["FechaTurno"];//$value["FechaCaja"];
        //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO

        $response = $this->sSaldoCajaTurno->ValidarCajaAperturadaParaVentaYCompraActualizacion($value);
        if(is_array($response))
        {
          $value = $response;          
        }
        else {
          return $response;
        }

        $borrado = parent::BorrarComprobanteCaja($value);
        //borramos movimiento de almacen de esas notas entrada
        $movimientos = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($value);
      }
    }

    return $resultado;
  }

  function ActualizarDocumentoEgresoDesdeComprobanteCompra($data)
  {    
    $response = $this->BorrarDocumentosEgresosDesdeComprobanteCompra($data);

    if (count($response)>0)    
      $data["FechaTurno"] = $response[0]["FechaCaja"];
    else {
      
    }

    $resultado = $this->InsertarDocumentoEgresoDesdeComprobanteCompra($data, true);
    return $resultado;
  }

  function ActualizarDocumentoEgresoDesdeComprobanteVenta($data)
  {
    $response = $this->BorrarDocumentosEgresosDesdeComprobanteVenta($data);
    
    if(!is_array($response))
    {
      return $response;
    }
    else
    {
      $data["FechaTurno"] = $response[0]["FechaCaja"];
     // $data["FechaCaja"] = convertToDate($data["FechaTurno"]);
    }

    $resultado = $this->InsertarDocumentoEgresoDesdeComprobanteVenta($data, true);
    return $resultado;
  }

  //ESPECIALMENTE PARA NOTA CREDITO POR CREDITO DESDE COMPRAS
  /**DIFERENCIANDO
   * 
   * 
   */
  function InsertarDocumentoEgresoDesdeNotaCreditoCompraCredito($data, $option = false)
  {
    $data = array_merge($this->ComprobanteCaja, $data);
    $cabecera = $data;
    $data["IdComprobanteCaja"] = "";
    $data["IdTipoOperacionCaja"] = ID_TIPO_OPERACION_CAJA_INGRESO_NC_PROVEEDOR; //NOTA CREDITO DESDE VENTAS
    //$data['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_VOUCHER_INGRESO; //SE OMITE POR SER NC
    $data["MontoComprobante"] = (is_string($data["Total"])) ? str_replace(',',"",$data["Total"]) : $data["Total"];
    $data['IdPersona'] = $data['IdProveedor'];

    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $parametro['IdTipoDocumento'] = $data['IdTipoDocumento'];
    $parametro['IdSedeAgencia'] = (count($asignacionsede)>0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieDocumento"] = (count($SeriesDocumento) > 0 ) ? $SeriesDocumento[0]["SerieDocumento"] : '';//$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0 ) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    //$data["NumeroDocumento"] = ""; //se omite para tomar el mismo numero

    $data["IdMedioPago"] = ID_MEDIO_PAGO_EFECTIVO;
    $data["FechaComprobante"] = convertToDate($data["FechaEmision"]);
    
    // print_r("asdasdasd");print_r($data);exit;
    //AQUI HACEMOS LAS VALIDACIONES DE TURNO
    $response = $this->sSaldoCajaTurno->ValidarTurno($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }
    
    //OBTENER ULTIMA CAJA APERTURADA POR USUARIO, TURNO Y CAJA
    /**AQUI PONEMOS LA FECHA TURNO DEPENDIENDO DE DONDE ESTA VINIENDO */
    // $data["FechaTurno"] = ($option) ? $cabecera["FechaTurno"] : $data["FechaTurno"];//convertToDate($data["FechaTurno"]);
    
    //POR NOTA CREDITO SE PASAN CAMPOS EN NULO
    // $data["IdTurno"] = NULL;
    // $data["IdHorario"] = NULL;
    $data["FechaTurno"] = NULL;
    $data["IdCaja"] = NULL;
    $data["IdComprobanteCompraReferencia"] = $data["IdComprobanteCompra"];

    // print_r($data);exit;
    // if(array_key_exists("DocumentosReferencia", $data))
    // {
    //   if(count($data["DocumentosReferencia"]) > 0)
    //   {
    //     $data["IdComprobanteCompra"] = $data["DocumentosReferencia"][0]["IdComprobanteCompra"];
    //   }
    //   else
    //   {
    //     return "No hay documento de referencia para esta nota de credito.";  
    //   }
    // }
    // else
    // {
    //   return "No hay documento de referencia para esta nota de credito.";
    // }

    $resultado = parent::InsertarComprobanteCaja($data);
    if($resultado)
    {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $movimientos = $this->InsertarMovimientosNotaCreditoCompraCredito($data);
      $descuento = $this->sPendientePagoProveedor->DescontarSaldosPendientePagoProveedor($movimientos);
      // print_r($descuento);exit;
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($movimientos);
      // print_r($saldos);exit;
      
      $resultado["Movimientos"] = $movimientos;
      // print_r($response);exit;
      // print_r($response);exit;
    }
    return $resultado;
  }

  function InsertarMovimientosNotaCreditoCompraCredito($data)
  {
    $detalles = $data["DocumentosReferencia"];
    $movimientos = array();
    foreach ($detalles as $key => $value) {
      $total = (is_string($value["Total"])) ? str_replace(',', '', $value["Total"]) : $value["Total"];;
      if($total > 0)
      {
        $value["IdComprobanteCaja"] = $data["IdComprobanteCaja"];
        $value["FechaComprobante"] = $data["FechaComprobante"];
        $value["FechaTurno"] = NULL;//$data["FechaTurno"];
        $value["FechaCaja"] = NULL;//$data["FechaCaja"];
        $value["IdCaja"] = NULL;//$data["IdCaja"];
        $value["IdUsuario"] = $data["IdUsuario"];
        $value["IdTurno"] = $data["IdTurno"];
        $value["MontoComprobante"] = $total;
        $movimiento = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgresoCredito($value);
        array_push($movimientos, $movimiento);
      }
    }
    return $movimientos;
  }

  function BorrarDocumentosEgresosDesdeNotaCreditoCompraCredito($data)
  {
    // $resultado = $this->sMovimientoCaja->ObtenerDocumentosPorIdComprobanteCompra($data);
    $resultado = parent::ObtenerDocumentosPorIdComprobanteCompraReferencia($data);
    $movimientosComprobante = array();
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        // //VALIDAMOS CADA COMPROBANTE, SI UNO ESTA FUERA DE FECHA SE OMITE
        // $value["FechaCaja"] = $value["FechaTurno"];
        // //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
        $response = $this->sSaldoCajaTurno->ValidarTurno($value);
        // print_r($response);exit;
        if(is_array($response))
        {
          $value = $response;
          $resultado[$key] = $value;
        }
        else {
          return $response;
        }

        $borrado = parent::BorrarComprobanteCaja($value);
        // print_r($borrado);exit;
        //borramos movimiento de almacen de esas notas entrada
        $movimientos = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoCajaCredito($value);
        
        //SE REVIERTEN LOS SALDOS DE PENDIENTE COBRANZA
        $revercion = $this->sPendientePagoProveedor->RevertirSaldosPendientePagoProveedor($movimientos);
        //ACTUALIZACION DE SALDO EN MOVIMIENTOS
        $saldosReversion = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($movimientos);
        $movimientosComprobante = $movimientos;
        // print_r($descuento);exit;
        // print_r($movimientos);exit;

      }
    }
    return $movimientosComprobante;
  }

  function ActualizarDocumentoEgresoDesdeNotaCreditoCompraCredito($data)
  {
    $response = $this->BorrarDocumentosEgresosDesdeNotaCreditoCompraCredito($data);
    // print_r($response);exit;
    if(!is_array($response))
    {
      return $response;
    }
    // else
    // {
    //   $data["FechaTurno"] = $response[0]["FechaCaja"];
    // }
    
    $resultado = $this->InsertarDocumentoEgresoDesdeNotaCreditoCompraCredito($data, true);
    return $resultado;
  }
}
