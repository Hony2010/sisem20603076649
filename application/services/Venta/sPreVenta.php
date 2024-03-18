<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sPreVenta extends sComprobanteVenta {

  private $ModeloPreVenta;

  public function __construct() {
    parent::__construct();
    $this->load->helper("date");
    $this->load->library('coleccion');
    $this->load->library('shared');
    $this->load->library('RestApi/Venta/RestApiVenta');
    $this->load->model('Venta/mPreVenta');
    $this->load->service('Venta/sDetalleComprobanteVenta');
    $this->load->service('Configuracion/Venta/sMesa');
    $this->ModeloPreVenta =  new mPreVenta();
  }

  function CargarPreVenta()
  {
    $parametro['IdTipoDocumento'] = "%";
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $resultado = parent::Cargar($parametro);
    $resultado["NuevaPreVenta"] = $resultado;
    return $resultado;
  }

  public function ConsultarUltimaComandaPorNumeroMesa($data)
  {
    $resultado = $this->ModeloPreVenta->ConsultarUltimaComandaPorNumeroMesa($data);
    if(count($resultado) > 0)
    {
      $resultado = $resultado[0];
      $resultado["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($resultado);
      return $resultado;
    }
    return $resultado;
  }

  public function ConsultarDetallesComprobantePreVentaConsolidado($data)
  {
    $resultado = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);
    $response = array();
    $filtro = $this->coleccion->super_unique($resultado, "IdProducto");
    foreach ($filtro as $key => $value) {
      $temp_array = array();
      foreach ($resultado as $key2 => $value2) {
        if($value["IdProducto"] == $value2["IdProducto"])
        {
          if(array_key_exists("IdProducto", $temp_array))
          {
            $temp_array["Cantidad"] += $value2["Cantidad"];
            $temp_array["SaldoPendientePreVenta"] += $value2["SaldoPendientePreVenta"]; 
            // $temp_array["PrecioUnitario"] += $value2["PrecioUnitario"];
            // $temp_array["ValorUnitario"] += $value2["ValorUnitario"];
            // $temp_array["ValorVentaItem"] += $value2["ValorVentaItem"];
            // $temp_array["ISCItem"] += $value2["ISCItem"];
            // $temp_array["IGVItem"] += $value2["IGVItem"];
            // $temp_array["ValorReferencial"] += $value2["ValorReferencial"];
            // $temp_array["SubTotal"] += $value2["SubTotal"];
            // $temp_array["DescuentoUnitario"] += $value2["DescuentoUnitario"];
            // $temp_array["DescuentoItem"] += $value2["DescuentoItem"];
          }
          else
          {
            $temp_array = $value2;
          }
        }
      }
      array_push($response, $temp_array);
    }

    return $response;
  }

  public function ConsultarDetallesComprobanteVenta($data)
  {
    $resultado = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);
    return $resultado;
  }

  public function InsertarPreVenta($data) {
    $data["IndicadorPreCuenta"] = ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_COMANDA) ? ESTADO_PRE_CUENTA_PENDIENTE : "";
    $data["IndicadorPagado"] = ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) ? ESTADO_CANJEADO_PENDIENTE :  "";
    $data["IndicadorCanjeado"] = ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) ? ESTADO_CANJEADO_PENDIENTE : "";

    $resultado = parent::InsertarComprobanteVenta($data);
    if(!is_string($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_COMANDA)
      {
        //CAMBIAMOS ESTADO DE LA MESA
        $dataMesa["IdMesa"] = $data["IdMesa"];
        $dataMesa["SituacionMesa"] = SITUACION_MESA_OCUPADO;
        $response = $this->sMesa->ActualizarMesa($dataMesa);
      }
      elseif($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO)
      {
        //CANJEAMOS LA COMANDA
        $dataComanda["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        $dataComanda["IndicadorPreCuenta"] = ESTADO_PRE_CUENTA_CANJEADO;
        $response = parent::ActualizarEstadoComprobanteVenta($dataComanda);

        //CAMBIAMOS ESTADO DE LA MESA
        $dataMesa["IdMesa"] = $data["IdMesa"];
        $dataMesa["SituacionMesa"] = SITUACION_MESA_DISPONIBLE;
        $responseMesa = $this->sMesa->ActualizarMesa($dataMesa);
      }
    }
    return $resultado;
  }

  public function ActualizarPreVenta($data)
  {
    $resultado = parent::ActualizarComprobanteVenta($data);
    if(is_array($resultado))
    {
      
    }
    return $resultado;
  }

  public function EliminarPreVenta($data)
  {
    $resultado = parent::BorrarComprobanteVenta($data);
    if(is_array($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_COMANDA)
      {
        //CAMBIAMOS ESTADO DE LA MESA
        $dataMesa["IdMesa"] = $data["IdMesa"];
        $dataMesa["SituacionMesa"] = SITUACION_MESA_DISPONIBLE;
        $response = $this->sMesa->ActualizarMesa($dataMesa);
      }
    }
    return $resultado;
  }

  public function ImprimirPreVenta($data)
  {
    try {
      $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];

      $printer = null;
      $rutaFormato = RUTA_CARPETA_REPORTES.NOMBRE_FACTURA_ELECTRONICO;
      $indicadorImpresion = INDICADOR_FORMATO_OTRO;

      $otroJasper = false;
      $otraRutaJasper = "";
      if ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_COMANDA) {
        $indicadorImpresion = INDICADOR_FORMATO_COMANDA;
      }
      elseif($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        $indicadorImpresion = INDICADOR_FORMATO_ORDEN_PEDIDO;
      }
      elseif ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA)
      {
        $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA;
        if($data["IndicadorTipoImpresion"] == INDICADOR_IMPRESION_CONSUMO)
        {
          $otraRutaJasper = RUTA_JASPER_CONSUMO_FACTURA;
        }
      }
      elseif ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA)
      {
        $indicadorImpresion = INDICADOR_FORMATO_FACTURA_ELECTRONICA_VENTA;
        if($data["IndicadorTipoImpresion"] == INDICADOR_IMPRESION_CONSUMO)
        {
          $otraRutaJasper = RUTA_JASPER_CONSUMO_BOLETA;
        }
      }
      else {
        $indicadorImpresion = INDICADOR_FORMATO_OTRO;
      }
      
      $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION."config-".$this->shared->GetDeviceName().".json";
      $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion,null,$rutaplantilla);
      if($dataConfig != false)
      {
        $printer = $dataConfig["Printer"];
        if($otroJasper)
        {
          $dataConfig["Jasper"] = $otraRutaJasper;
        }
        $rutaFormato = RUTA_CARPETA_REPORTES.$dataConfig["Jasper"];
      }

      $this->reporter->RutaReporte = $rutaFormato;
      $this->reporter->SetearParametros($parametros);

      $this->reporter->Imprimir($printer);
      return "";
    }
    catch (Exception $e) {
      throw new Exception($e);
    }
  }

  public function ImprimirComanda($data)
  {
    $resultado = $this->ImprimirPreVenta($data);
    if($resultado == "")
    {
      //ACTUALZIAMOS LOS DETALLES CON ESTADO 0
      $dataImpresion["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
      $dataImpresion["IndicadorImpresion"] = "1";
      $resultado = $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVentaPorIdComprobanteVenta($dataImpresion);
      return $resultado;
    }

    return $resultado;
  }

  public function ImprimirAnuladoComanda($data)
  {
    try {
      $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
      $parametros["IdDetalleComprobanteVenta"] = $data["IdDetalleComprobanteVenta"];

      $printer = null;
      $rutaFormato = RUTA_CARPETA_REPORTES.NOMBRE_FACTURA_ELECTRONICO;
      $indicadorImpresion = INDICADOR_FORMATO_COMANDA;
      $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION."config-".$this->shared->GetDeviceName().".json";
      $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion,null,$rutaplantilla);
      if($dataConfig != false)
      {
        $printer = $dataConfig["Printer"];
        $rutaFormato = RUTA_CARPETA_REPORTES.NOMBRE_COMANDA_DETALLE_ANULADO;
      }

      $this->reporter->RutaReporte = $rutaFormato;
      $this->reporter->SetearParametros($parametros);

      $this->reporter->Imprimir($printer);
      return "";
    }
    catch (Exception $e) {
      throw new Exception($e);
    }
  }

  public function ImprimirItemAnuladoComanda($data)
  {
    $resultado = $this->ImprimirAnuladoComanda($data);
    if($resultado == "")
    {
      //ACTUALZIAMOS LOS DETALLES CON ESTADO 0
      $dataImpresion["IdDetalleComprobanteVenta"] = $data["IdDetalleComprobanteVenta"];
      $dataImpresion["IndicadorEstado"] = ESTADO_ELIMINADO;
      $resultado = $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($dataImpresion);
      return $resultado;
    }

    return $resultado;
  }

  public function AnularPreCuenta($data)
  {

  }

  // public function CanjearComanda($data)
  // {
  //   //BUSCAR COMANDA POR ID
  //   $dataComanda["IdComprobanteVenta"] = $data["IdComprobantePreVenta"];
  //   $comanda = $this->ModeloPreVenta->ConsultarComandaPorIdComprobanteVenta($dataComanda);
  //   //RESTAR SALDOS A LOS DETALLES DE LAS COMANDAS

  //   //COMPROBAR SALDOS A CERO PARA CERRAR COMANDA

  //   //CAMBIAR ESTADO DE LA COMANDA A CERRADO
  //   $dataComanda["SituacionPreVenta"] = SITUACION_PREVENTA_CANJEADO;
  //   $resultado = parent::ActualizarComprobanteVenta($dataComanda);
  //   return $resultado;
  // }
  public function CancelarPreCuenta($data)
  {
    $dataPrecuenta["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
    $dataPrecuenta["IndicadorPagado"] = ESTADO_CANJEADO_TOTAL;
    $dataPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_TOTAL;
    $response = parent::ActualizarEstadoComprobanteVenta($dataPrecuenta);
    return $data;
  }

  //PARA INSERCION DE COMPROBANTEPRECUENTA
  public function InsertarVentaDesdePreVenta($data)
  {
    $resultado = $this->restapiventa->InsertarVenta($data);
    if(is_array($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO)
      {
        // //CANJEAMOS LA COMANDA
        // $dataComanda["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        // $dataComanda["IndicadorPreCuenta"] = ESTADO_PRE_CUENTA_CANJEADO;
        // $response = parent::ActualizarEstadoComprobanteVenta($dataComanda);

        // //CAMBIAMOS ESTADO DE LA MESA
        // $dataMesa["IdMesa"] = $data["IdMesa"];
        // $dataMesa["SituacionMesa"] = SITUACION_MESA_DISPONIBLE;
        // $responseMesa = $this->sMesa->ActualizarMesa($dataMesa);
      }
      else {
        $this->DescontarSaldosPreCuenta($resultado);

        $dataPrecuenta["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        $responsePrecuenta = $this->sDetalleComprobanteVenta->ConsultarSaldosPorDetallesPrecuenta($dataPrecuenta);

        if($responsePrecuenta[0]["Total"] == 0)
        {
          // $resultadoPrecuenta = parent::ConsultarComprobanteVentaPorId($dataPrecuenta);
          // $resultadoPrecuenta[0]["IndicadorPagado"] = ESTADO_CANJEADO_TOTAL;
          // $resultadoPrecuenta[0]["IndicadorCanjeado"] = ESTADO_CANJEADO_TOTAL;
          // $response = $this->restapiventa->AnularVenta($resultadoPrecuenta[0]);
          $dataPrecuenta["IndicadorPagado"] = ESTADO_CANJEADO_TOTAL;
          $dataPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_TOTAL;
          $response = parent::ActualizarEstadoComprobanteVenta($dataPrecuenta);
        }
        else
        {
          // $resultadoPrecuenta = parent::ConsultarComprobanteVentaPorId($dataPrecuenta);
          $dataPrecuenta["IndicadorPagado"] = ESTADO_CANJEADO_PARCIAL;
          $dataPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_PARCIAL;
          $response = parent::ActualizarEstadoComprobanteVenta($dataPrecuenta);
        }
      }

    }
    return $resultado;
  }

  public function ActualizarVentaDesdePreVenta($data)
  {
    // $data["IndicadorCanjeado"] = ESTADO_CANJEADO_PENDIENTE;
    $resultado = $this->restapiventa->ActualizarVenta($data);
    if(is_array($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO)
      {
        // //CANJEAMOS LA COMANDA
        // $dataComanda["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        // $dataComanda["IndicadorPreCuenta"] = ESTADO_PRE_CUENTA_CANJEADO;
        // $response = $this->ActualizarComprobanteVenta($dataComanda);
      }
      else {
        // $this->DescontarSaldosPreCuenta($resultado);

        // $dataPrecuenta["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        // $responsePrecuenta = $this->sDetalleComprobanteVenta->ConsultarSaldosPorDetallesPrecuenta($dataPrecuenta);

        // if($responsePrecuenta[0]["Total"] == 0)
        // {
        //   $resultadoPrecuenta = parent::ConsultarComprobanteVentaPorId($dataPrecuenta);
        //   $resultadoPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_TOTAL;
        //   $response = $this->restapiventa->AnularVenta($resultadoPrecuenta);
        // }
        // else
        // {
        //   $resultadoPrecuenta = parent::ConsultarComprobanteVentaPorId($dataPrecuenta);
        //   $resultadoPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_PARCIAL;
        //   $response = $this->ActualizarComprobanteVenta($resultadoPrecuenta);
        // }
      }
    }
    return $resultado;
  }

  public function AnularVentaDesdePreVenta($data)
  {
    // $data["IndicadorCanjeado"] = ESTADO_CANJEADO_PENDIENTE;
    $resultado = $this->restapiventa->AnularVenta($data);
    if(is_array($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO)
      {
        // //CANJEAMOS LA COMANDA
        // $dataComanda["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        // $dataComanda["IndicadorPreCuenta"] = ESTADO_PRE_CUENTA_CANJEADO;
        // $response = $this->ActualizarComprobanteVenta($dataComanda);
      }
      else {
        $this->RestaurarSaldosPreCuenta($resultado);

        $dataPrecuenta["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        $responsePrecuenta = $this->sDetalleComprobanteVenta->ConsultarSaldosPorDetallesPrecuenta($dataPrecuenta);

        if($responsePrecuenta[0]["Total"] > 0)
        {
          $dataPrecuenta["IndicadorPagado"] = ESTADO_CANJEADO_PARCIAL;
          $dataPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_PARCIAL;
          $response = parent::ActualizarEstadoComprobanteVenta($dataPrecuenta);
        }
      }
    }
    return $resultado;
  }

  public function BorrarVentaDesdePreVenta($data)
  {
    // $data["IndicadorCanjeado"] = ESTADO_CANJEADO_PENDIENTE;
    $resultado = $this->restapiventa->BorrarVenta($data);
    if(is_array($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO)
      {
        // //CANJEAMOS LA COMANDA
        // $dataComanda["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        // $dataComanda["IndicadorPreCuenta"] = ESTADO_PRE_CUENTA_CANJEADO;
        // $response = $this->ActualizarComprobanteVenta($dataComanda);
      }
      else {
        $this->RestaurarSaldosPreCuenta($resultado);

        $dataPrecuenta["IdComprobanteVenta"] = $resultado["IdComprobantePreVenta"];
        $responsePrecuenta = $this->sDetalleComprobanteVenta->ConsultarSaldosPorDetallesPrecuenta($dataPrecuenta);

        if($responsePrecuenta[0]["Total"] > 0)
        {
          $dataPrecuenta["IndicadorPagado"] = ESTADO_CANJEADO_PARCIAL;
          $dataPrecuenta["IndicadorCanjeado"] = ESTADO_CANJEADO_PARCIAL;
          $response = parent::ActualizarEstadoComprobanteVenta($dataPrecuenta);
        }
      }
    }
    return $resultado;
  }

  public function DescontarSaldosPreCuenta($data)
  {
    $dataPrecuenta["IdComprobanteVenta"] = $data["IdComprobantePreVenta"];
    $dataPrecuenta["IdTipoVenta"] = $data["IdTipoVenta"];
    $dataPrecuenta["IdAsignacionSede"] = $data["IdAsignacionSede"];
    $resultado = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($dataPrecuenta);
    foreach ($resultado as $key => $value) {
      foreach ($data["DetallesComprobanteVenta"] as $key2 => $value2) {
        if($value["IdProducto"] == $value2["IdProducto"])
        {
          $dataDetalle["IdDetalleComprobanteVenta"] = $value["IdDetalleComprobanteVenta"];
          $dataDetalle["SaldoPendientePreVenta"] = $value["SaldoPendientePreVenta"] - $value2["Cantidad"];
          $resultado = $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($dataDetalle);
        }
      }
    }
    return $resultado;
  }

  public function RestaurarSaldosPreCuenta($data)
  {
    $dataPrecuenta["IdComprobanteVenta"] = $data["IdComprobantePreVenta"];
    $dataPrecuenta["IdTipoVenta"] = $data["IdTipoVenta"];
    $dataPrecuenta["IdAsignacionSede"] = $data["IdAsignacionSede"];
    $resultado = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($dataPrecuenta);
    foreach ($resultado as $key => $value) {
      foreach ($data["DetallesComprobanteVenta"] as $key2 => $value2) {
        if($value["IdProducto"] == $value2["IdProducto"])
        {
          $dataDetalle["IdDetalleComprobanteVenta"] = $value["IdDetalleComprobanteVenta"];
          $dataDetalle["SaldoPendientePreVenta"] = $value["SaldoPendientePreVenta"] + $value2["Cantidad"];
          $resultado = $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($dataDetalle);
        }
      }
    }
    return $resultado;
  }

  /**CONSULTAS */
  public function ConsultarComandasPorMesa($data)
  {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    $resultado = $this->ModeloPreVenta->ConsultarComandasPorMesa($data);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
    }
    return $resultado;
  }

  public function ConsultarPreVentasPorMesa($data)
  {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    $resultado = $this->ModeloPreVenta->ConsultarPreVentasPorMesa($data);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
    }
    return $resultado;
  }

}
