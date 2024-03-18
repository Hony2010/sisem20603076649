<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMovimientoCaja extends MY_Service {

  public $MovimientoCaja = array();

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
    $this->load->model('Caja/mMovimientoCaja');
    $this->load->model('Catalogo/mMercaderia');
    $this->load->service('Caja/sSaldoCajaTurno');

    $this->MovimientoCaja = $this->mMovimientoCaja->MovimientoCaja;
  }

  /**SE REALIZAN LAS INSERCIONES POR INGRESO Y EGRESO */
  function InsertarMovimientoCajaDocumentoIngreso($data)
  {
    $data["FechaMovimiento"] = $data["FechaComprobante"];
    $data["MontoIngresoEfectivo"] = $data["MontoComprobante"];

    $response = $this->sSaldoCajaTurno->AgregarSaldoCajaTurnoDocumentoIngreso($data);
    $data["SaldoCajaTurno"] = $response["SaldoActual"];
    $resultado = $this->InsertarMovimientoCaja($data);

    return $resultado;
  }

  function InsertarMovimientoCajaDocumentoEgreso($data)
  {
    $data["FechaMovimiento"] = $data["FechaComprobante"];
    $data["MontoEgresoEfectivo"] = $data["MontoComprobante"];

    $response = $this->sSaldoCajaTurno->AgregarSaldoCajaTurnoDocumentoEgreso($data);
    $data["SaldoCajaTurno"] = $response["SaldoActual"];
    $resultado = $this->InsertarMovimientoCaja($data);

    return $resultado;
  }
  /**FIN DE INSERCIONES */

  function InsertarMovimientoCaja($data)
  {
    try {
      $resultadoValidacion = "";
      if($resultadoValidacion == "")
      {
        if(array_key_exists("IdComprobanteVenta", $data))
        {
          $data["IdComprobanteVenta"] = (is_numeric($data["IdComprobanteVenta"])) ? $data["IdComprobanteVenta"] : NULL;
        }
        
        if(array_key_exists("IdComprobanteCompra", $data))
        {
          $data["IdComprobanteCompra"] = (is_numeric($data["IdComprobanteCompra"])) ? $data["IdComprobanteCompra"] : NULL;          
        }

        if(array_key_exists("IdSaldoInicialCuentaCobranza", $data))
        {
          $data["IdSaldoInicialCuentaCobranza"] = (is_numeric($data["IdSaldoInicialCuentaCobranza"])) ? $data["IdSaldoInicialCuentaCobranza"] : NULL;          
        }

        $resultado = $this->mMovimientoCaja->InsertarMovimientoCaja($data);
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
  
  function ActualizarMovimientoCaja($data)
  {
    try {
      $resultadoValidacion = "";
      if($resultadoValidacion == "")
      {
        $resultado = $this->mMovimientoCaja->ActualizarMovimientoCaja($data);
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

  function BorrarMovimientoCaja($data)
  {
    $this->mMovimientoCaja->BorrarMovimientoCaja($data);
    return "";
  }

  function BorrarMovimientosCajaDocumentoIngreso($data) {
    $resultado = $this->ObtenerMovimientosCajaPorComprobanteCaja($data);//MOVIMIENTOS POR RECIBO INGRESO
    
    if(count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        $value["FechaTurno"] = $data["FechaTurno"];
        $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurnoDocumentoIngreso($value);
        $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        $this->mMovimientoCaja->BorrarMovimientoCaja($dataMovimiento);
      }
    }

    return $resultado;
  }

  function BorrarMovimientosCajaDocumentoEgreso($data)
  {
    $resultado = $this->ObtenerMovimientosCajaPorComprobanteCaja($data);
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $value["FechaTurno"] = $data["FechaTurno"];
        $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurnoDocumentoEgreso($value);
        $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        $this->mMovimientoCaja->BorrarMovimientoCaja($dataMovimiento);
      }
    }

    return $resultado;
  }

  //PARA ANULACIONES
  function AnularMovimientosCajaDocumentoIngreso($data)
  {
    $resultado = $this->ObtenerMovimientosCajaPorComprobanteCaja($data);//MOVIMIENTOS POR RECIBO INGRESO
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $value["FechaTurno"] = $data["FechaTurno"];
        $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurnoDocumentoIngreso($value);
        $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        $this->mMovimientoCaja->AnularMovimientoCaja($dataMovimiento);
      }
    }

    return $resultado;
  }

  function AnularMovimientosCajaDocumentoEgreso($data)
  {
    $resultado = $this->ObtenerMovimientosCajaPorComprobanteCaja($data);

    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $value["FechaTurno"] = $data["FechaTurno"];
        $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurnoDocumentoEgreso($value);
        $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        $this->mMovimientoCaja->AnularMovimientoCaja($dataMovimiento);
      }
    }

    return $resultado;
  }

  //PARA FUNCIONES DE ACTUALIZAR MOVIMIENTOS
  function ActualizarMovimientosCajaDocumentoIngreso($data)
  {
    $this->BorrarMovimientosCajaDocumentoIngreso($data);
    $resultado = $this->InsertarMovimientoCajaDocumentoIngreso($data);
    return $resultado;
  }

  function ActualizarMovimientosCajaDocumentoEgreso($data)
  {
    $this->BorrarMovimientosCajaDocumentoEgreso($data);
    $resultado = $this->InsertarMovimientoCajaDocumentoEgreso($data);
    return $resultado;
  }

  //FUNCIONES ESPECIALMENTE PARA TRANSFERENCIA
  function BorrarMovimientosCajaTransferenciaCaja($data)
  {
    $resultado = $this->ObtenerMovimientosCajaPorComprobanteCaja($data);//MOVIMIENTOS POR RECIBO INGRESO
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $value["FechaComprobante"] = $value["FechaMovimiento"];
        
        if($value["MontoIngresoEfectivo"] > 0)
        {
          $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurnoDocumentoIngreso($value);
        }
        else if($value["MontoEgresoEfectivo"] > 0){
          $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurnoDocumentoEgreso($value);
        }

        $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        $this->mMovimientoCaja->BorrarMovimientoCaja($dataMovimiento);
      }
    }
    return $resultado;
  }

  //NUEVAS FUNCIONES PARA OBTENER MOVIMIENTOS POR NOTAS - CV - CC
  function ObtenerMovimientosCajaPorComprobanteCajaParaCobranza($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosCajaPorComprobanteCajaParaCobranza($data);
    return $resultado;
  }

  function ObtenerMovimientosCajaPorComprobanteCaja($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosCajaPorComprobanteCaja($data);
    return $resultado;
  }

  function ObtenerMovimientosPorComprobanteVenta($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosPorComprobanteVenta($data);
    return $resultado;
  }

  //PARAC COBRANZA Y ACTUALZIACION
  function ObtenerMovimientosParaCobranzaClientePorComprobanteVenta($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosParaCobranzaClientePorComprobanteVenta($data);
    return $resultado;
  }

  function ObtenerMovimientosParaCobranzaClientePorSaldoInicialCuentaCobranza($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosParaCobranzaClientePorSaldoInicialCuentaCobranza($data);
    return $resultado;
  }

  //PARAC COBRANZA Y ACTUALZIACION
  function ObtenerMovimientosParaCobranzaProveedorPorComprobanteCompra($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosParaCobranzaProveedorPorComprobanteCompra($data);
    return $resultado;
  }

  function ObtenerMovimientosParaCobranzaProveedorPorSaldoInicialCuentaPago($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosParaCobranzaProveedorPorSaldoInicialCuentaPago($data);
    return $resultado;
  }

  function ObtenerMovimientosPorComprobanteCompra($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosPorComprobanteCompra($data);
    return $resultado;
  }

  function ObtenerDocumentosPorIdComprobanteVenta($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerDocumentosPorIdComprobanteVenta($data);
    return $resultado;
  }

  function ObtenerDocumentosPorIdComprobanteCompra($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerDocumentosPorIdComprobanteCompra($data);
    return $resultado;
  }

  /**PARA SALDO INICIAL CUENTA COBRANZA */
  function ObtenerMovimientosPorSaldoInicialCuentaCobranza($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosPorSaldoInicialCuentaCobranza($data);
    return $resultado;
  }

  function ObtenerDocumentosPorIdSaldoInicialCuentaCobranza($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerDocumentosPorIdSaldoInicialCuentaCobranza($data);
    return $resultado;
  }
  /**FIN PARA SALDO INICIAL CUENTA COBRANZA */

  /**PARA SALDO INICIAL CUENTA PAGO */
  function ObtenerMovimientosPorSaldoInicialCuentaPago($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerMovimientosPorSaldoInicialCuentaPago($data);
    return $resultado;
  }

  function ObtenerDocumentosPorIdSaldoInicialCuentaPago($data)
  {
    $resultado = $this->mMovimientoCaja->ObtenerDocumentosPorIdSaldoInicialCuentaPago($data);
    return $resultado;
  }
  /**FIN PARA SALDO INICIAL CUENTA COBRANZA */

  function ValidarComprobanteCajaParaVentaOCompra($data)
  {
    $resultado = $this->mMovimientoCaja->ValidarComprobanteCajaParaVentaOCompra($data);
    return $resultado;
  }
  /**FIN */

  //SUMATORIA PARA ACTUALIZAR COMPROBANTES DE VENTA
  // function ObtenerTotalMovimientosComprobanteVenta($data)
  // {
  //   $resultado = $this->mMovimientoCaja->ObtenerMovimientosPorComprobanteVenta($data);
  //   $total = 0;
  //   foreach ($resultado as $key => $value) {
  //     if($value["MontoIngresoEfectivo"] > 0)
  //     {
  //       $total = $total + $value["MontoIngresoEfectivo"];
  //     }
  //     else if($value["MontoEgresoEfectivo"] > 0)
  //     {
  //       $total = $total - $value["MontoEgresoEfectivo"];
  //     }
  //     else
  //     {
  //       $total = $total + 0;
  //     }
  //   }
  //   return $total;
  // }

  //SUMATORIA PARA ACTUALIZAR COMPROBANTES DE COMPRA
  // function ObtenerTotalMovimientosComprobanteCompra($data)
  // {
  //   $resultado = $this->mMovimientoCaja->ObtenerMovimientosPorComprobanteCompra($data);
  //   $total = 0;
  //   foreach ($resultado as $key => $value) {
  //     if($value["MontoIngresoEfectivo"] > 0)
  //     {
  //       $total = $total + $value["MontoIngresoEfectivo"];
  //     }
  //     else if($value["MontoEgresoEfectivo"] > 0)
  //     {
  //       $total = $total - $value["MontoEgresoEfectivo"];
  //     }
  //     else
  //     {
  //       $total = $total + 0;
  //     }
  //   }
  //   return $total;
  // }

  /**FUNCIONES PARA NOTA CREDITO POR CREDITO TOTAL DE MOVIMIENTOS*/
  function InsertarMovimientoCajaDocumentoIngresoCredito($data)
  {
    $data["FechaMovimiento"] = $data["FechaComprobante"];
    $data["MontoIngresoEfectivo"] = $data["MontoComprobante"];
    $data["SaldoCajaTurno"] = ""; //VACIO PORQUE NO SE RESTA EN SALDO CAJA TURNO
    $resultado = $this->InsertarMovimientoCaja($data);
    return $resultado;
  }

  function InsertarMovimientoCajaDocumentoEgresoCredito($data)
  {
    $data["FechaMovimiento"] = $data["FechaComprobante"];
    $data["MontoEgresoEfectivo"] = $data["MontoComprobante"];
    $data["SaldoCajaTurno"] = ""; //VACIO PORQUE NO SE RESTA EN SALDO CAJA TURNO
    $resultado = $this->InsertarMovimientoCaja($data);
    return $resultado;
  }

  function BorrarMovimientosCajaDocumentoCajaCredito($data)
  {
    $resultado = $this->ObtenerMovimientosCajaPorComprobanteCaja($data);//MOVIMIENTOS POR RECIBO INGRESO
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $dataMovimiento["IdMovimientoCaja"] = $value["IdMovimientoCaja"];
        $this->mMovimientoCaja->BorrarMovimientoCaja($dataMovimiento);
      }
    }
    return $resultado;
  }

  function ActualizarMovimientosCajaDocumentoIngresoCredito($data)
  {
    $this->BorrarMovimientosCajaDocumentoCajaCredito($data);
    $resultado = $this->InsertarMovimientoCajaDocumentoIngresoCredito($data);
    return $resultado;
  }

  function ActualizarMovimientosCajaDocumentoEgresoCredito($data)
  {
    $this->BorrarMovimientosCajaDocumentoCajaCredito($data);
    $resultado = $this->InsertarMovimientoCajaDocumentoEgresoCredito($data);
    return $resultado;
  }

}
