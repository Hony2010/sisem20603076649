<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sCobranzaLetra extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Base");
		$this->load->library('tipocambiosunat');
    $this->load->service('Caja/sTipoOperacionCaja');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sPendienteLetraCobrar');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Configuracion/General/sMedioPago');
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Configuracion/Venta/sTipoTarjeta');
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("Y-m-d");

    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_VOUCHER_INGRESO;//ID_TIPO_DOCUMENTO_RECIBO_INGRESO."','".ID_TIPO_DOCUMENTO_VOUCHER_INGRESO;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $resultado = parent::CargarComprobanteCaja($parametro);

    // $Motivos = $this->sTipoOperacionCaja->ListarTiposOperacionCajaParaDocumentoEgreso();
    // $resultado["TiposOperacionCaja"] = $Motivos;
    $resultado["IdTipoOperacionCaja"] = ID_TIPO_OPERACION_CAJA_COBRANZA_CLIENTE;//(count($Motivos) > 0) ? $Motivos[0]["IdTipoOperacionCaja"] : "";

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
    $resultado["MontoCobrado"] = "";
    $resultado["SaldoPendiente"] = "";
    $resultado["Importe"] = "";

    $resultado["NuevoDetalleCobranzaLetra"] = $this->sPendienteLetraCobrar->PendienteLetraCobrar;
    $resultado["NuevoDetalleCobranzaLetra"]["MontoSoles"] = 0;
    $resultado["NuevoDetalleCobranzaLetra"]["MontoDolares"] = 0;
    $resultado["NuevoDetalleCobranzaLetra"]["Importe"] = 0;
    $resultado["NuevoDetalleCobranzaLetra"]["NuevoSaldo"] = 0;

    $resultado["DetallesCobranzaLetra"] = array();
    $resultado["ComprobantesPorCobrar"] = array();

    $fechaFirst = new DateTime($hoy);
    $fechaFirst->modify('first day of this month');
    $fechaLast = new DateTime($hoy);
    $fechaLast->modify('last day of this month');

    $resultado["Filtro"]["TextoFiltro"] = "";
    $resultado["Filtro"]["FechaInicio"] = $fechaFirst->format('d/m/Y');
    $resultado["Filtro"]["FechaFin"] = $fechaLast->format('d/m/Y');
    $resultado["Filtro"]["IdCliente"] = "";

    $resultado["NuevaCobranzaLetra"] = $resultado;

    return $resultado;
  }

  /**###CONSULTAS DE DETALLE */
  // function ConsultarPendientesLetraCobrarPorIdCliente($data)
  // {
  //   $resultado = $this->sPendienteLetraCobrar->ConsultarPendientesLetraCobrarPorIdCliente($data);
  //   foreach ($resultado as $key => $value) {
  //     $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
  //     $resultado[$key]["FechaVencimiento"] = convertirFechaES($value["FechaVencimiento"]);
  //   }
  //   return $resultado;
  // }
  function ConsultarPendientesLetraCobrarParaCobranza($data) //UPDATE
  {
    $resultado = $this->sPendienteLetraCobrar->ConsultarPendientesLetraCobrarParaCobranza($data);
    return $resultado;
  }

  function ConsultarPendientesLetraCobrarPorIdClienteYFiltro($data) //UPDATE
  {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    $resultado = $this->sPendienteLetraCobrar->ConsultarPendientesLetraCobrarPorIdClienteYFiltro($data);
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
      // if(is_numeric($value["IdComprobanteVenta"]))
      // {
      //   $pendiente = $this->sPendienteLetraCobrar->ObtenerPendienteLetraCobrarPorIdComprobanteVenta($value);
      // }
      if(is_numeric($value["IdPendienteLetraCobrar"]))
      {
        $pendiente = $this->sPendienteLetraCobrar->ObtenerPendienteLetraCobrar($value);
      }
      // else
      // {
      //   $pendiente = $this->sPendienteLetraCobrar->ObtenerPendienteLetraCobrarPorIdSaldoInicialCuentaCobranza($value);
      // }
      
      if(count($pendiente) > 0)
      {
        $pendiente[0]["FechaEmision"] = convertirFechaES($pendiente[0]["FechaEmision"]);
        $pendiente[0]["FechaVencimiento"] = convertirFechaES($pendiente[0]["FechaVencimiento"]);
        $pendiente[0]["Importe"] = $value["MontoIngresoEfectivo"];
        $pendiente[0]["SaldoPendiente"] += $value["MontoIngresoEfectivo"];
        array_push($detalles, $pendiente[0]);
      }
    }
    return $detalles;
  }
  /**###FIN DE CONSULTAS DE DETALLE */

  function ValidarCobranzaLetra($data)
  {
    if($data["FechaComprobante"] == "")
    {
      return "Usted debe insertar una fecha para esta cobranza.";
    }
    else
    {
      return "";
    }
  }

  /**FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */
  function InsertarCobranzaLetra($data)
  {
    $validacion = $this->ValidarCobranzaLetra($data);
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
      $detalles = $this->InsertarDetallesCobranzaLetra($data);//$this->sMovimientoCaja->InsertarMovimientoCajaCobranzaLetra($data);
      $descuento = $this->sPendienteLetraCobrar->DescontarSaldosPendienteLetraCobrar($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteLetraCobrar->ActualizarMovimientosCajaParaSaldoPendienteLetraCobrar($detalles);
      
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
  /**FIN FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */
  function InsertarDetallesCobranzaLetra($data)
  {
    $detalles = $data["DetallesCobranzaLetra"];
    $movimientos = array();
    foreach ($detalles as $key => $value) {
      $importe = (is_string($value["Importe"])) ? str_replace(',', '', $value["Importe"]) : $value["Importe"];;
      if($importe > 0)
      {
        $pendiente = $this->sPendienteLetraCobrar->ConsultarPendienteLetraCobrar($value);
        if(count($pendiente) > 0)
        {
          foreach ($pendiente[0]["DetallesPendienteLetraCobrar"] as $key2 => $value2) {
            $otraData = array_merge($value, $value2);
            $otraData["IdComprobanteCaja"] = $data["IdComprobanteCaja"];
            $otraData["FechaComprobante"] = $data["FechaComprobante"];
            $otraData["FechaTurno"] = $data["FechaTurno"];
            $otraData["FechaCaja"] = $data["FechaCaja"];
            $otraData["IdCaja"] = $data["IdCaja"];
            $otraData["IdUsuario"] = $data["IdUsuario"];
            $otraData["IdTurno"] = $data["IdTurno"];
            $otraData["MontoComprobante"] = $importe;
            // $detalles[$key] = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($value);
            $movimiento = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($otraData);
            array_push($movimientos, $movimiento);
          }
            # code...
        }
      }
    }
    return $movimientos;
  }

  /**FUNCIONES DE ACTUALIZADO PARA DOCUMENTO DE EGRESO */
  function ActualizarCobranzaLetra($data)
  {
    $validacion = $this->ValidarCobranzaLetra($data);
    if($validacion != "")
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

    $resultado = parent::ActualizarComprobanteCaja($data);
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      // $responseBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoIngreso($data);
      // $responseInsertado = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($data);
      $detallesBorrado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoIngreso($data);
      $revercion = $this->sPendienteLetraCobrar->RevertirSaldosPendienteLetraCobrar($detallesBorrado);

      //ACTUALIZACION DE SALDO EN MOVIMIENTOS REVERSION
      $saldosReversion = $this->sPendienteLetraCobrar->ActualizarMovimientosCajaParaSaldoPendienteLetraCobrar($detallesBorrado);
      
      $detalles = $this->InsertarDetallesCobranzaLetra($data);//$this->sMovimientoCaja->InsertarMovimientoCajaCobranzaLetra($data);
      $descuento = $this->sPendienteLetraCobrar->DescontarSaldosPendienteLetraCobrar($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteLetraCobrar->ActualizarMovimientosCajaParaSaldoPendienteLetraCobrar($detalles);

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
  function BorrarCobranzaLetra($data)
  {
    // $validacion = $this->ValidarCobranzaLetraParaEliminadoYAnulado($data);
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
      $detalles = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoIngreso($data);
      $descuento = $this->sPendienteLetraCobrar->RevertirSaldosPendienteLetraCobrar($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteLetraCobrar->ActualizarMovimientosCajaParaSaldoPendienteLetraCobrar($detalles);

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
  function AnularCobranzaLetra($data)
  {
    // $validacion = $this->ValidarCobranzaLetraParaEliminadoYAnulado($data);
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
      $detalles = $this->sMovimientoCaja->AnularMovimientosCajaDocumentoIngreso($data);
      $descuento = $this->sPendienteLetraCobrar->RevertirSaldosPendienteLetraCobrar($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteLetraCobrar->ActualizarMovimientosCajaParaSaldoPendienteLetraCobrar($detalles);

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
  /**FIN DE FUNCIONES DE ANULADO PARA DOCUMENTO DE EGRESO */

  /**VALIDACIONES PARA ELIMINADO Y ANULADO */
  // private function ValidarCobranzaLetraParaEliminadoYAnulado($data)
  // {
  //   if(is_numeric($data["IdComprobanteVenta"]) || is_numeric($data["IdComprobanteCompra"]))
  //   {
  //     return "No se puede eliminar o anular porque este comprobante fue generado por el sistema.";
  //   }
  //   else{
  //     return "";
  //   }
  // }
  /**FIN VALIDACIONES PARA ELIMINADO Y ANULADO */
}
