<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sCobranzaCliente extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Base");
		$this->load->library('tipocambiosunat');
    $this->load->service('Caja/sTipoOperacionCaja');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sPendienteCobranzaCliente');
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

    $resultado["NuevoDetalleCobranzaCliente"] = $this->sPendienteCobranzaCliente->PendienteCobranzaCliente;
    $resultado["NuevoDetalleCobranzaCliente"]["MontoSoles"] = 0;
    $resultado["NuevoDetalleCobranzaCliente"]["MontoDolares"] = 0;
    $resultado["NuevoDetalleCobranzaCliente"]["Importe"] = 0;
    $resultado["NuevoDetalleCobranzaCliente"]["NuevoSaldo"] = 0;

    $resultado["DetallesCobranzaCliente"] = array();
    $resultado["ComprobantesPorCobrar"] = array();

    $fechaFirst = new DateTime($hoy);
    $fechaFirst->modify('first day of this month');
    $fechaLast = new DateTime($hoy);
    $fechaLast->modify('last day of this month');

    $resultado["Filtro"]["TextoFiltro"] = "";
    $resultado["Filtro"]["FechaInicio"] = $fechaFirst->format('d/m/Y');
    $resultado["Filtro"]["FechaFin"] = $fechaLast->format('d/m/Y');
    $resultado["Filtro"]["IdCliente"] = "";
    $resultado["HoraEmision"]="";
    $resultado["NuevaCobranzaCliente"] = $resultado;

    return $resultado;
  }

  /**###CONSULTAS DE DETALLE */
  // function ConsultarPendientesCobranzaClientePorIdCliente($data)
  // {
  //   $resultado = $this->sPendienteCobranzaCliente->ConsultarPendientesCobranzaClientePorIdCliente($data);
  //   foreach ($resultado as $key => $value) {
  //     $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
  //     $resultado[$key]["FechaVencimiento"] = convertirFechaES($value["FechaVencimiento"]);
  //   }
  //   return $resultado;
  // }

  function ConsultarPendientesCobranzaClientePorIdClienteYFiltro($data) //UPDATE
  {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    $resultado = $this->sPendienteCobranzaCliente->ConsultarPendientesCobranzaClientePorIdClienteYFiltro($data);
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
      if(is_numeric($value["IdComprobanteVenta"]))
      {
        $pendiente = $this->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value);
      }
      else
      {
        $pendiente = $this->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($value);
      }
      
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

  function ValidarCobranzaCliente($data) {
    if($data["FechaComprobante"] == "") {
      return "Usted debe insertar una fecha para esta cobranza.";
    }
   elseif($data["MontoComprobante"] == "") {
      return "Usted debe tener un monto comprobante mayor a cero.";
    }
    else {
      $detalles = $data["DetallesCobranzaCliente"];   
      
      foreach ($detalles as $key => $value) {
        $importe = (is_string($value["Importe"])) ? str_replace(',', '', $value["Importe"]) : $value["Importe"];;
        if($importe <= 0) {
          return "En el item ".($key+1)." no tiene asignado un importe.Por favor de consultar con su administrador";
        }
      }

      return "";
    }
  }

  /**FUNCIONES DE INSERTADO PARA DOCUMENTO EGRESO */
  function InsertarCobranzaCliente($data) {
    $validacion = $this->ValidarCobranzaCliente($data);
    if($validacion != "") {
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
    if(is_array($response)) {
      $data = $response;
    }
    else {
      return $response;
    }    
    
    

    $resultado = parent::InsertarComprobanteCaja($data);
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];            
      
      $detalles = $this->InsertarDetallesCobranzaCliente($data);//$this->sMovimientoCaja->InsertarMovimientoCajaCobranzaCliente($data);
      $descuento = $this->sPendienteCobranzaCliente->DescontarSaldosPendienteCobranzaCliente($detalles);      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
    
      $saldos = $this->sPendienteCobranzaCliente->ActualizarMovimientosCajaParaSaldoPendienteCobranzaCliente($detalles);
      
      $resultado["Movimientos"] = $detalles;
      $saldoCajaNegativo = $this->sSaldoCajaTurno->ValidarSaldoCajaTurnoNegativo($data);
      if(is_string($saldoCajaNegativo)) {
        return $saldoCajaNegativo;
      }

      return $resultado;
    }
    else {
      return "";
    }
  }

  function InsertarDetallesCobranzaCliente($data) {
    $detalles = $data["DetallesCobranzaCliente"];    
    $movimientos = array();
    
    foreach ($detalles as $key => $value) {
      $importe = (is_string($value["Importe"])) ? str_replace(',', '', $value["Importe"]) : $value["Importe"];;
      if($importe > 0) {
        $value["IdComprobanteCaja"] = $data["IdComprobanteCaja"];
        $value["FechaComprobante"] = $data["FechaComprobante"];
        $value["FechaTurno"] = $data["FechaTurno"];
        $value["FechaCaja"] = $data["FechaCaja"];
        $value["IdCaja"] = $data["IdCaja"];
        $value["IdUsuario"] = $data["IdUsuario"];
        $value["IdTurno"] = $data["IdTurno"];
        $value["MontoComprobante"] = $importe;
        // $detalles[$key] = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($value);
        $movimiento = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($value);
    
        array_push($movimientos, $movimiento);
      }
    }

    return $movimientos;
  }
  
  function ActualizarCobranzaCliente($data) {
    $validacion = $this->ValidarCobranzaCliente($data);
    if($validacion != "") {
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
      $revercion = $this->sPendienteCobranzaCliente->RevertirSaldosPendienteCobranzaCliente($detallesBorrado);

      //ACTUALIZACION DE SALDO EN MOVIMIENTOS REVERSION
      $saldosReversion = $this->sPendienteCobranzaCliente->ActualizarMovimientosCajaParaSaldoPendienteCobranzaCliente($detallesBorrado);
      
      $detalles = $this->InsertarDetallesCobranzaCliente($data);//$this->sMovimientoCaja->InsertarMovimientoCajaCobranzaCliente($data);
      $descuento = $this->sPendienteCobranzaCliente->DescontarSaldosPendienteCobranzaCliente($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteCobranzaCliente->ActualizarMovimientosCajaParaSaldoPendienteCobranzaCliente($detalles);

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

  function BorrarCobranzaCliente($data) {
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];

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
      $descuento = $this->sPendienteCobranzaCliente->RevertirSaldosPendienteCobranzaCliente($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteCobranzaCliente->ActualizarMovimientosCajaParaSaldoPendienteCobranzaCliente($detalles);

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

  function AnularCobranzaCliente($data) {
    // $validacion = $this->ValidarCobranzaClienteParaEliminadoYAnulado($data);
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
      $descuento = $this->sPendienteCobranzaCliente->RevertirSaldosPendienteCobranzaCliente($detalles);
      
      //ACTUALIZACION DE SALDO EN MOVIMIENTOS
      $saldos = $this->sPendienteCobranzaCliente->ActualizarMovimientosCajaParaSaldoPendienteCobranzaCliente($detalles);

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
