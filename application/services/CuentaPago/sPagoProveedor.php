<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sPagoProveedor extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Base");
		$this->load->library('tipocambiosunat');
    $this->load->service('Caja/sTipoOperacionCaja');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sPendientePagoProveedor');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Configuracion/General/sMedioPago');
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Configuracion/Venta/sTipoTarjeta');
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("Y-m-d");

    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_VOUCHER_EGRESO;//ID_TIPO_DOCUMENTO_RECIBO_EGRESO."','".ID_TIPO_DOCUMENTO_VOUCHER_EGRESO;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $resultado = parent::CargarComprobanteCaja($parametro);

    // $Motivos = $this->sTipoOperacionCaja->ListarTiposOperacionCajaParaDocumentoEgreso();
    // $resultado["TiposOperacionCaja"] = $Motivos;
    $resultado["IdTipoOperacionCaja"] = ID_TIPO_OPERACION_CAJA_PAGO_PROVEEDOR;//(count($Motivos) > 0) ? $Motivos[0]["IdTipoOperacionCaja"] : "";

    $MediosPago = $this->sMedioPago->ListarMediosPago();
    $resultado["MediosPago"] = $MediosPago;
    $resultado["IdMedioPago"] = (count($MediosPago) > 0) ? $MediosPago[0]->IdMedioPago : "";

    //PARA OBTENER TIPO CAMBIO
    $TipoCambioActual = $this->tipocambiosunat->ConsultarTipoCambioCompra();
    $ValorTipoCambio = ($TipoCambioActual == "") ? "" : $TipoCambioActual;
    $resultado["ValorTipoCambio"] = $ValorTipoCambio;

    $TiposTarjeta = $this->sTipoTarjeta->ListarTiposTarjeta();
    $resultado["TiposTarjeta"] = $TiposTarjeta;
    $resultado["IdTipoTarjeta"] = (count($TiposTarjeta) > 0) ? $TiposTarjeta[0]->IdTipoTarjeta : "";
    $Monedas = $this->sMoneda->ListarMonedas();
    $resultado["Monedas"] = $Monedas;
    $resultado["IdMoneda"] = (count($Monedas) > 0) ? $Monedas[0]->IdMoneda : "";

    $resultado["RazonSocial"] = "";
    $resultado["NumeroDocumentoIdentidad"] = "";

    /**OTROS CAMPOS - ESPECIALMENTE PARA COBRANZA RAPIDA*/
    $resultado["MontoOriginal"] = "";
    $resultado["MontoPagado"] = "";
    $resultado["SaldoPendiente"] = "";
    $resultado["Importe"] = "";

    $resultado["NuevoDetallePagoProveedor"] = $this->sPendientePagoProveedor->PendientePagoProveedor;
    $resultado["NuevoDetallePagoProveedor"]["MontoSoles"] = 0;
    $resultado["NuevoDetallePagoProveedor"]["MontoDolares"] = 0;
    $resultado["NuevoDetallePagoProveedor"]["Importe"] = 0;
    $resultado["NuevoDetallePagoProveedor"]["NuevoSaldo"] = 0;

    $resultado["DetallesPagoProveedor"] = array();
    $resultado["ComprobantesPorPagar"] = array();

    $fechaFirst = new DateTime($hoy);
    $fechaFirst->modify('first day of this month');
    $fechaLast = new DateTime($hoy);
    $fechaLast->modify('last day of this month');

    $resultado["Filtro"]["TextoFiltro"] = "";
    $resultado["Filtro"]["FechaInicio"] = $fechaFirst->format('d/m/Y');
    $resultado["Filtro"]["FechaFin"] = $fechaLast->format('d/m/Y');
    $resultado["Filtro"]["IdProveedor"] = "";

    $resultado["NuevaPagoProveedor"] = $resultado;

    return $resultado;
  }

  function ConsultarPendientesPagoProveedorPorIdProveedorYFiltro($data) //UPDATE
  {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    $resultado = $this->sPendientePagoProveedor->ConsultarPendientesPagoProveedorPorIdProveedorYFiltro($data);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($value["FechaVencimiento"]);
    }
    return $resultado;
  }

  function ConsultarDetallesCobranzaPorCobranza($data) //UPDATE
  {
    $resultado = $this->sMovimientoCaja->ObtenerMovimientosCajaPorComprobanteCajaParaCobranza($data);//$this->sMovimientoCaja->ObtenerMovimientosCajaPorComprobanteCaja($data);
    $detalles = array();
    foreach ($resultado as $key => $value) {
      $pendiente = array();
      if(is_numeric($value["IdComprobanteCompra"]))
      {
        $pendiente = $this->sPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdComprobanteCompra($value);
      }
      else
      {
        $pendiente = $this->sPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($value);
      }
      
      if(count($pendiente) > 0)
      {
        $pendiente[0]["FechaEmision"] = convertirFechaES($pendiente[0]["FechaEmision"]);
        $pendiente[0]["FechaVencimiento"] = convertirFechaES($pendiente[0]["FechaVencimiento"]);
        $pendiente[0]["Importe"] = $value["MontoEgresoEfectivo"];
        $pendiente[0]["SaldoPendiente"] += $value["MontoEgresoEfectivo"];
        array_push($detalles, $pendiente[0]);
      }
    }
    return $detalles;
  }
  /**###FIN DE CONSULTAS DE DETALLE */

  function ValidarPagoProveedor($data)
  {
    if($data["FechaComprobante"] == "")
    {
      return "Usted debe insertar una fecha para este pago.";
    }
    else
    {
      return "";
    }
  }

  /**FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */
  function InsertarPagoProveedor($data)
  {
    $validacion = $this->ValidarPagoProveedor($data);
    if($validacion != "")
    {
      return $validacion;
    }

    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
    
    $data["NumeroDocumento"] = "";

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
      $detalles = $this->InsertarDetallesPagoProveedor($data);//$this->sMovimientoCaja->InsertarMovimientoCajaPagoProveedor($data);
      $descuento = $this->sPendientePagoProveedor->DescontarSaldosPendientePagoProveedor($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($detalles);
      
      $resultado["Movimientos"] = $detalles;
      $saldoCajaNegativo = $this->sSaldoCajaTurno->ValidarSaldoCajaTurnoNegativo($data);
      if(is_string($saldoCajaNegativo))
      {
        return $saldoCajaNegativo;
      }
      // print_r($resultado);exit;
      return $resultado;
    }
    else {
      return "";
    }
  }
  /**FIN FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */
  function InsertarDetallesPagoProveedor($data)
  {
    $detalles = $data["DetallesPagoProveedor"];
    $movimientos = array();
    foreach ($detalles as $key => $value) {
      $importe = (is_string($value["Importe"])) ? str_replace(',', '', $value["Importe"]) : $value["Importe"];;
      if($importe > 0)
      {
        $value["IdComprobanteCaja"] = $data["IdComprobanteCaja"];
        $value["FechaComprobante"] = $data["FechaComprobante"];
        $value["FechaTurno"] = $data["FechaTurno"];
        $value["FechaCaja"] = $data["FechaCaja"];
        $value["IdCaja"] = $data["IdCaja"];
        $value["IdUsuario"] = $data["IdUsuario"];
        $value["IdTurno"] = $data["IdTurno"];
        $value["MontoComprobante"] = $importe;
        
        $movimiento = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($value);
        array_push($movimientos, $movimiento);
      }
    }
    return $movimientos;
  }

  /**FUNCIONES DE ACTUALIZADO PARA DOCUMENTO DE EGRESO */
  function ActualizarPagoProveedor($data)
  {
    $validacion = $this->ValidarPagoProveedor($data);
    if($validacion != "")
    {
      return $validacion;
    }
    
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
      // $responseBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($data);
      // $responseInsertado = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($data);
      $detallesBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($data);
      $revercion = $this->sPendientePagoProveedor->RevertirSaldosPendientePagoProveedor($detallesBorrado);

      //ACTUALIZACION DE SALDO EN MOVIMIENTOS REVERSION
      $saldosReversion = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($detallesBorrado);
      
      $detalles = $this->InsertarDetallesPagoProveedor($data);//$this->sMovimientoCaja->InsertarMovimientoCajaPagoProveedor($data);
      $descuento = $this->sPendientePagoProveedor->DescontarSaldosPendientePagoProveedor($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($detalles);

      $resultado["Movimientos"] = $detalles;
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
  function BorrarPagoProveedor($data)
  {
    // $validacion = $this->ValidarPagoProveedorParaEliminadoYAnulado($data);
    // if ($validacion != "")
    // {
    //   return $validacion;
    // }

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
      $detalles = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoEgreso($data);
      $descuento = $this->sPendientePagoProveedor->RevertirSaldosPendientePagoProveedor($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($detalles);

      $resultado["Movimientos"] = $detalles;
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
  function AnularPagoProveedor($data)
  {
    // $validacion = $this->ValidarPagoProveedorParaEliminadoYAnulado($data);
    // if ($validacion != "")
    // {
    //   return $validacion;
    // }

    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
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
      $detalles = $this->sMovimientoCaja->AnularMovimientosCajaDocumentoEgreso($data);
      $descuento = $this->sPendientePagoProveedor->RevertirSaldosPendientePagoProveedor($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendientePagoProveedor->ActualizarMovimientosCajaParaSaldoPendientePagoProveedor($detalles);

      $resultado["Movimientos"] = $detalles;
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

}
