<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sNotaSalida extends MY_Service
{

  public $NotaSalida = array();
  public $DetalleNotaSalida = array();

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
    $this->load->model('Inventario/mNotaSalida');
    $this->load->model('Venta/mComprobanteVenta');
    $this->load->model('Compra/mComprobanteCompra');
    $this->load->service('Inventario/sDetalleNotaSalida');
    $this->load->service("Inventario/sMovimientoAlmacen");
    $this->load->service("Inventario/sMovimientoAlmacenProductoLote");
    $this->load->service("Inventario/sDocumentoReferenciaNotaSalida");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service("Configuracion/General/sTipoCambio");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->load->service("Configuracion/General/sFormaPago");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sSede");
    $this->load->service('Configuracion/General/sAsignacionSede');
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service("Configuracion/Venta/sTipoTarjeta");
    $this->load->service("Configuracion/Inventario/sMotivoNotaSalida");
    $this->load->service('Seguridad/sAccesoUsuarioAlmacen');
    $this->load->service('Compra/sComprobanteCompra');

    $this->NotaSalida = $this->mNotaSalida->NotaSalida;
    $DetalleNotaSalida = [];
    $DetalleNotaSalida[] = $this->mDetalleNotaSalida->DetalleNotaSalida;
    $this->NotaSalida["DetallesNotaSalida"] = $DetalleNotaSalida;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] = $this->sesionusuario->obtener_sesion_id_sede();
    $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);
    $Sedes = $this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario(); // $Sedes = $this->sSede->ListarSedesTipoAlmacen();
    if (count($Sedes) > 0) {
      $this->NotaSalida["IdAsignacionSede"] = $Sedes[0]["IdAsignacionSede"];
    }
    $MotivosNotaSalida = $this->sMotivoNotaSalida->ListarMotivosNotaSalida();

    $this->NotaSalida["CodigoSedeAgencia"] = $this->sesionusuario->obtener_sesion_codigo_sede();
    $this->NotaSalida["NombreSedeAgencia"] = $this->sesionusuario->obtener_sesion_nombre_sede();
    $this->NotaSalida["FechaEmision"] = $hoy;
    $this->NotaSalida["ValorTipoCambio"] = $TipoCambio == null ? "0.00" : $TipoCambio->TipoCambioVenta;
    $this->NotaSalida["NumeroDocumentoIdentidad"] = "";
    $this->NotaSalida["Direccion"] = "";
    $this->NotaSalida["RazonSocial"] = "";
    $this->NotaSalida["IdTipoVenta"] = "1";
    $this->NotaSalida["NombreFormaPago"] = "";
    $this->NotaSalida["NombreMoneda"] = "";
    $this->NotaSalida["IdAsignacionSede"] = "";
    $this->NotaSalida["DocumentoPendiente"] = false;
    $this->NotaSalida["EstadoPendienteNota"] = '0';
    $this->NotaSalida["IdPersona"] = CLIENTE_NO_ESPECIFICADO;
    $this->NotaSalida["SimboloMoneda"] = "";
    $this->NotaSalida["NombreAbreviado"] = "";
    $this->NotaSalida["SerieDocumento"] = "";
    $this->NotaSalida["NumeroDocumento"] = "";
    $this->NotaSalida["NombreAlmacen"] = $this->NotaSalida["NombreSedeAgencia"];//(count($Sedes) > 0) ? $Sedes[0]["NombreSede"] : "";
    $this->NotaSalida["MotivoMovimiento"] = (count($MotivosNotaSalida) > 0) ? $MotivosNotaSalida[0]["NombreMotivoNotaSalida"] : "";
    $this->NotaSalida["RazonSocial"] = "";
    $this->NotaSalida["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    $this->NotaSalida["DetallesNotaSalida"] = array(); //[0]=$this->sDetalleNotaSalida->Cargar();
    $this->NotaSalida["ParametroTipoCambio"] = $this->sConstanteSistema->ObtenerParametroTipoCambioBusquedaAvanzadaProducto();
    $this->NotaSalida["ParametroMargenUtilidad"] = $this->sConstanteSistema->ObtenerParametroMargenUtilidadBusquedaAvanzadaProducto();    

    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTASALIDA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $this->NotaSalida["IdSede"] = $parametro['IdSedeAgencia'];
    $data2["IdSede"] = $this->NotaSalida["IdSede"];
  
    $dataAsignacionSede=$this->sAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorIdSede($data2);
    $this->NotaSalida["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];

    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    // $this->NotaSalida["SerieDocumento"] = $SeriesDocumento[0]['SerieDocumento'];
    $this->NotaSalida["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]['IdCorrelativoDocumento'] : "";
    $this->NotaSalida["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTASALIDA;
    $this->NotaSalida["SerieNotaSalida"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]['SerieDocumento'] : "";

    $fechaservidor = $this->Base->ObtenerFechaServidor("d/m/Y");
    $input["textofiltro"] = '';
    $input["FechaInicio"] = $fechaservidor;
    $input["FechaFin"] = $fechaservidor;
    $input["FechaHoy"] = $fechaservidor;
    $input["IdPersona"] = 3;
    $input["IdTipoDocumento"] = 3;
    $input["IdMoneda"] = 3;
    $input["TipoPersona"] = 1;

    $this->NotaSalida["Filtros"] = $input;
    $this->NotaSalida["BusquedaComprobantesVenta"] = array();
    $this->NotaSalida["BusquedaComprobanteVenta"] = array();
    $this->NotaSalida["MiniComprobantesVenta"] = array();
    $this->NotaSalida["GrupoDetalleComprobanteVenta"] = array();

    $TasaIGV = $this->ObtenerTasaIGV();
    $FormasPago = $this->sFormaPago->ListarFormasPago();
    $Monedas = $this->sMoneda->ListarMonedas();
    $TiposTarjeta = $this->sTipoTarjeta->ListarTiposTarjeta();

    $data = array(
      'NuevoDetalleNotaSalida' => $this->sDetalleNotaSalida->Cargar(),
      'TasaIGV' => $TasaIGV,
      'SeriesDocumento' => $SeriesDocumento,
      'FormasPago' => $FormasPago,
      'Monedas' => $Monedas,
      'TiposTarjeta' => $TiposTarjeta,
      'Sedes' => $Sedes,
      'MotivosNotaSalida' => $MotivosNotaSalida,
      'TipoCambio' => $TipoCambio,
      'NoCliente' => CLIENTE_NO_ESPECIFICADO
    );

    $resultado = array_merge($this->NotaSalida, $data);

    return $resultado;
  }

  function ConsultarNotasSalida($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mNotaSalida->ConsultarNotasSalida($data, $numerofilainicio, $numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["DetallesNotaSalida"] = [];
    }

    return $resultado;
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_COMPROBANTEVENTA;
    $parametro = $this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina = $parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalNotasSalida($data)
  {
    $resultado = $this->mNotaSalida->ObtenerNumeroTotalNotasSalida($data);
    return $resultado;
  }

  function ConsultarComprobantesVentaPorPersona($data)
  {
    $data["TipoNota"] = ESTADO_PENDIENTE_NOTASALIDA;
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaPendienteNotaPorCliente($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["IdComprobante"] = $item["IdComprobanteVenta"];
      $resultado[$key]["Modulo"] = ID_MODULO_VENTA;
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["DetallesNotaSalida"] = $this->sDetalleNotaSalida->ConsultarDetallesComprobanteVenta($item);
    }

    return $resultado;
  }

  function ConsultarComprobantesCompraPorPersona($data)
  {
    $data["TipoNota"] = ESTADO_PENDIENTE_NOTASALIDA;
    $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompraPendienteNotaPorCliente($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["IdComprobante"] = $item["IdComprobanteCompra"];
      $resultado[$key]["Modulo"] = ID_MODULO_COMPRA;
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesNotaSalida"] = $this->sDetalleNotaSalida->ConsultarDetallesComprobanteCompra($item);
    }

    return $resultado;
  }

  function ObtenerTasaIGV()
  {
    $data['IdParametroSistema'] = ID_TASA_IGV;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function InsertarNotaSalidaDesdeComprobante($data)
  {
    //PARA NUEVA DATA
    $tipodocumentoreferencia = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumentoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["CodigoTipoDocumento"] : '';
    $data["NombreAbreviadoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["NombreAbreviado"] : '';
    $data["SerieDocumentoReferencia"] = $data["SerieDocumento"];
    $data["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];

    $data["IdNotaSalida"] = "";
    $data["NumeroNotaSalida"] = ""; //$data["NumeroDocumento"];
    if (array_key_exists("FechaMovimientoAlmacen", $data)) {
      $data["FechaEmision"] = $data["FechaMovimientoAlmacen"];
    }
    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTASALIDA;
    $parametro['IdSedeAgencia'] = (count($asignacionsede) > 0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieNotaSalida"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : ''; //$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';

    $data_nota["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTASALIDA;
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data_nota); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';

    $data_moneda["IdMoneda"] = $data["IdMoneda"];
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data_moneda);
    $data["NombreMoneda"] = (count($moneda) > 0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda) > 0) ? $moneda[0]["CodigoMoneda"] : '';

    //PARA MOTIVO COMPROBANTE VENTA
    $data["IdMotivoNotaSalida"] = ID_MOTIVO_NOTA_SALIDA_VENTA_CON_DOCUMENTO;
    $resultadoMotivoSalida = $this->sMotivoNotaSalida->ObtenerMotivoNotaSalida($data);
    $data["MotivoMovimiento"] = $resultadoMotivoSalida->NombreMotivoNotaSalida;

    $data["DetallesNotaSalida"] = $data["DetallesComprobanteVenta"];
    $resultado = $this->InsertarNotaSalida($data);
    if ($resultado) {
      $data["IdNotaSalida"] = $resultado["IdNotaSalida"];
      $movimiento = $this->InsertarMovimientoAlmacen($data);

      $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
      $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();

      if ($parametro_lote == 1) {
        if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
          $this->InsertarMovimientoAlmacenProductoLote($data);
        }
      }

      if ($parametro_dua == 1 && $data['IdTipoDocumento'] != ID_TIPO_DOCUMENTO_ORDEN_PEDIDO && $data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
        if (!($data['IdSubTipoDocumento'] == ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_T || $data['IdSubTipoDocumento'] == ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_Z)) {
          $this->InsertarMovimientoDocumentoDua($data);
        }
      }
      
      if ($data['IdTipoDocumento'] == ID_TIPO_DOCUMENTO_BOLETA && ($data['IdSubTipoDocumento'] == ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_T || $data['IdSubTipoDocumento'] == ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_Z)) {
        if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
          
          $this->InsertarMovimientoDocumentoSalidaZofra($data);
        }
      }

      $this->sDocumentoReferenciaNotaSalida->InsertarDocumentoReferenciaNotaSalida($data);
    }
    return $resultado;
  }

  function InsertarNotaSalidaDesdeComprobanteCompra($data)
  {
    //PARA NUEVA DATA
    $tipodocumentoreferencia = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumentoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["CodigoTipoDocumento"] : '';
    $data["NombreAbreviadoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["NombreAbreviado"] : '';
    $data["SerieDocumentoReferencia"] = $data["SerieDocumento"];
    $data["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];

    //SOLO PARA NOTAS DE CREDITO
    $data["IdNotaSalida"] = "";
    $data["NumeroNotaSalida"] = ""; //$data["NumeroDocumento"];
    if (array_key_exists("FechaMovimientoAlmacen", $data)) {
      $data["FechaEmision"] = $data["FechaMovimientoAlmacen"];
    }

    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTASALIDA;
    $parametro['IdSedeAgencia'] = (count($asignacionsede) > 0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieNotaSalida"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : ''; //$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';

    $data_nota["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTASALIDA;
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data_nota); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';

    $data_moneda["IdMoneda"] = $data["IdMoneda"];
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data_moneda);
    $data["NombreMoneda"] = (count($moneda) > 0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda) > 0) ? $moneda[0]["CodigoMoneda"] : '';

    //PARA MOTIVO COMPROBANTE VENTA
    $data["IdMotivoNotaSalida"] = ID_MOTIVO_NOTA_SALIDA_DEVOLUCION_AL_PROVEEDOR;
    $resultadoMotivoSalida = $this->sMotivoNotaSalida->ObtenerMotivoNotaSalida($data);
    $data["MotivoMovimiento"] = $resultadoMotivoSalida->NombreMotivoNotaSalida;

    $data["DetallesNotaSalida"] = $data["DetallesComprobanteCompra"];
    $resultado = $this->InsertarNotaSalida($data);
    if ($resultado) {
      $data["IdNotaSalida"] = $resultado["IdNotaSalida"];

      if (is_string($data["ValorTipoCambio"])) {
        $data["ValorTipoCambio"] = str_replace(',', "", $data["ValorTipoCambio"]);
      }

      foreach ($data["DetallesNotaSalida"] as $key => $value) {
        if (is_numeric($value["IdProducto"])) {
          if (array_key_exists('IdMotivoNotaCredito', $data)) {
            $data["DetallesNotaSalida"][$key]["IdMotivoNotaCredito"] = $data["IdMotivoNotaCredito"]; //$value["ValorUnitario"] - $value["DescuentoUnitario"];
          }
          if ($data["IdMoneda"] == ID_MONEDA_SOLES) {
            $data["DetallesNotaSalida"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"]; //$value["ValorUnitario"] - $value["DescuentoUnitario"];
          } else {
            $data["DetallesNotaSalida"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"] * $data["ValorTipoCambio"];
          }
        }
      }

      $movimiento = $this->InsertarMovimientoAlmacen($data);

      $this->sDocumentoReferenciaNotaSalida->InsertarDocumentoReferenciaNotaSalida($data);
    }
    return $resultado;
  }

  function InsertarNotaSalidaDesdeSalida($data)
  {
    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';

    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';

    //PARA MOTIVO COMPROBANTE VENTA
    $resultadoMotivoSalida = $this->sMotivoNotaSalida->ObtenerMotivoNotaSalida($data);
    $data["MotivoMovimiento"] = $resultadoMotivoSalida->NombreMotivoNotaSalida;

    $resultado = $this->InsertarNotaSalida($data);
    $data["DetallesNotaSalida"] = $resultado["DetallesNotaSalida"];

    if (is_array($resultado)) {
      $data["IdNotaSalida"] = $resultado["IdNotaSalida"];

      foreach ($data["DetallesNotaSalida"] as $key => $value) {
        if (is_numeric($value["IdProducto"])) {
          $data["DetallesNotaSalida"][$key]["CostoUnitarioAdquisicion"] = (is_string($value["ValorUnitario"])) ? str_replace(',', "", $value["ValorUnitario"]) : $value["ValorUnitario"];
        }
      }

      if (array_key_exists("MiniComprobantesVenta", $data)) {
        if (count($data["MiniComprobantesVenta"]) > 0 ){

          foreach ($data["DetallesNotaSalida"] as $key => $value) {
            if (is_numeric($value["IdProducto"])) {
              if (array_key_exists("CostoUnitarioAdquisicion", $data["DetallesNotaSalida"][$key])) {
                if ($data["MiniComprobantesVenta"][0]["IdMoneda"] == ID_MONEDA_SOLES) {
                  $data["DetallesNotaSalida"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"]; //$value["ValorUnitario"] - $value["DescuentoUnitario"];
                } else {
                  $data["DetallesNotaSalida"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"] * $data["MiniComprobantesVenta"][0]["ValorTipoCambio"];
                }
              }
            }
          }

          $this->InsertarDocumentosReferenciaNotaSalida($data);
          $this->sDetalleNotaSalida->ActualizarSaldoPendienteDetalleComprobante($data["DetallesNotaSalida"]);

          //Se actualizan los estados
          $this->ActualizarEstadoDocumentosReferenciaNotaSalida($data);
        }
      }

      $this->InsertarMovimientoAlmacen($data);

      return $resultado;
    } else {
      return "";
    }
  }

  function InsertarDocumentosReferenciaNotaSalida($data)
  {
    foreach ($data["MiniComprobantesVenta"] as $key => $value) {
      $value["IdNotaSalida"] = $data["IdNotaSalida"];

      $this->sDocumentoReferenciaNotaSalida->InsertarDocumentoReferenciaNotaSalida($value);
    }

    return "";
  }

  function ActualizarEstadoDocumentosReferenciaNotaSalida($data)
  {
    foreach ($data["MiniComprobantesVenta"] as $key => $value) {
      if ($value["Modulo"] == ID_MODULO_VENTA) {
        $this->ActualizarEstadoPendienteNotaVenta($value);
      } else {
        $this->ActualizarEstadoPendienteNotaCompra($value);
      }
    }
    return "";
  }

  function InsertarNotaSalida($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      // $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);

      // $resultadoValidacion = $this->ValidarNotaSalida($data);
      $resultadoValidacion = "";

      if ($resultadoValidacion == "") {
        $resultado = $this->mNotaSalida->InsertarNotaSalida($data);
        $IdNotaSalida = $resultado["IdNotaSalida"];

        $resultado["DetallesNotaSalida"] = $this->sDetalleNotaSalida->InsertarDetallesNotaSalida($IdNotaSalida, $data["DetallesNotaSalida"]);

        if (strlen($data["IdNotaSalida"]) == 0 && strlen($data["NumeroNotaSalida"]) == 0) {
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $resultadoCorrelativo =  $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          // $resultadoCorrelativo =  $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);
          // $resultado["NumeroNotaSalida"] = $resultadoCorrelativo->UltimoDocumento;
          $resultado["NumeroNotaSalida"] = $resultadoCorrelativo;
          $this->ActualizarSerieDocumentoNotaSalida($resultado);
          $resultado["NumeroNotaSalida"] = str_pad($resultadoCorrelativo, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        }

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        // $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);

        return $resultado;
      } else {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarNotaSalida($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);

      $resultadoValidacion = $this->ValidarNotaSalida($data);

      if ($resultadoValidacion == "") {
        $resultado = $this->mNotaSalida->ActualizarNotaSalida($data);
        $IdNotaSalida = $data["IdNotaSalida"];
        $resultado["DetallesNotaSalida"] = $this->sDetalleNotaSalida->ActualizarDetallesNotaSalida($IdNotaSalida, $data["DetallesNotaSalida"]);
        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] = convertirFechaES($resultado["FechaVencimiento"]);
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarNotaSalida($data)
  {
    $resultado = $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaSalida($data);

    $this->BorrarNotasSalida($data);
    $data["DetallesNotaSalida"] = $resultado;
    return $data;
  }

  /*FUNCION PARA ELIMINAR NOTA DE SALIDA Y DETALLES*/
  function BorrarNotasSalida($data)
  {
    $this->mNotaSalida->BorrarNotaSalida($data);
    $this->sDetalleNotaSalida->BorrarDetalleNotaSalida($data);
    return $data;
  }

  function ActualizarSerieDocumentoNotaSalida($data)
  {
    $IdNotaSalida = $data["IdNotaSalida"];
    $this->mNotaSalida->ActualizarNotaSalida($data);
    return "";
  }

  public function ActualizarEstadoPendienteNotaVenta($data)
  {
    $resultado = $this->sDetalleNotaSalida->TotalSaldoPendienteSalidaVenta($data);

    if ($resultado[0]["Total"] <= 0) {
      $nueva_data["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
      $nueva_data["EstadoPendienteNota"] = '0';
      $nueva_data["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
      $nueva_data["IndicadorEstado"] = $data["IndicadorEstado"];
      $nueva_data["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
      $nueva_data["IndicadorEstadoCPE"] = $data["IndicadorEstadoCPE"];
      $nueva_data["SerieDocumento"] = $data["SerieDocumento"];
      $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($nueva_data);
    }
    return $resultado;
  }

  public function ActualizarEstadoPendienteNotaCompra($data)
  {
    $resultado = $this->sDetalleNotaSalida->TotalSaldoPendienteSalidaCompra($data);

    if ($resultado[0]["Total"] <= 0) {
      $nueva_data["IdComprobanteCompra"] = $data["IdComprobanteCompra"];
      $nueva_data["EstadoPendienteNota"] = '0';
      $nueva_data["IndicadorEstado"] = $data["IndicadorEstado"];
      $nueva_data["SerieDocumento"] = $data["SerieDocumento"];
      $this->sComprobanteCompra->ActualizarEstadoComprobanteCompra($nueva_data);
    }
    return $resultado;
  }

  public function InsertarMovimientoAlmacen($data)
  {

    foreach ($data["DetallesNotaSalida"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        $value["NombreAlmacen"] = $data["NombreAlmacen"];
        $value["FechaEmision"] = $data["FechaEmision"];
        $value["IdNotaSalida"] = $data["IdNotaSalida"];
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
        $value["CodigoSede"] = $data["CodigoSede"];
        if (array_key_exists('IdMotivoNotaCredito', $value)) {
          if ($value["IdMotivoNotaCredito"] == ID_MOTIVO_NOTA_CREDITO_DESCUENTO_ITEM) {
            $value["CostoUnitarioDeducido"] = $value["DescuentoUnitario"];
          }
        }

        //NUEVOS DATOS COMPRA O VENTA
        $value["CodigoTipoDocumentoReferencia"] = (array_key_exists("CodigoTipoDocumentoReferencia", $data)) ? $data["CodigoTipoDocumentoReferencia"] : NULL;
        $value["NombreAbreviadoReferencia"] = (array_key_exists("NombreAbreviadoReferencia", $data)) ? $data["NombreAbreviadoReferencia"] : NULL;
        $value["SerieDocumentoReferencia"] = (array_key_exists("SerieDocumentoReferencia", $data)) ? $data["SerieDocumentoReferencia"] : NULL;
        $value["NumeroDocumentoReferencia"] = (array_key_exists("NumeroDocumentoReferencia", $data)) ? $data["NumeroDocumentoReferencia"] : NULL;
        // $value["CostoUnitarioAdquisicion"] = $value["PrecioUnitario"];

        $this->sMovimientoAlmacen->InsertarMovimientoAlmacenNotaSalida($value);
      }
    }
  }

  function InsertarMovimientoAlmacenProductoLote($data)
  {
    foreach ($data["DetallesNotaSalida"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["IdLoteProducto"] = $value["IdLoteProducto"];
        $value["IdNotaSalida"] = $data["IdNotaSalida"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];

        $this->sMovimientoAlmacenProductoLote->InsertarMovimientoAlmacenProductoLoteNotaSalida($value);
      }
    }
  }

  function InsertarMovimientoDocumentoDua($data)
  {
    foreach ($data["DetallesNotaSalida"] as $key => $value) {
      if (is_numeric($value["IdProducto"]) && $value["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA) {
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["IdNotaSalida"] = $data["IdNotaSalida"];

        $value["FechaMovimiento"] = convertToDate($data["FechaEmision"]);
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];

        $this->sMovimientoDocumentoDua->InsertarMovimientoDocumentoDuaNotaSalida($value);
      }
    }
  }

  function InsertarMovimientoDocumentoSalidaZofra($data)
  {
    foreach ($data["DetallesNotaSalida"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        // $value["IdDocumentoSalidaZofra"] = $value["IdDocumentoSalidaZofra"];
        $value["FechaMovimiento"] = convertToDate($data["FechaEmision"]);
        $value["IdNotaSalida"] = $data["IdNotaSalida"];
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
        
        if(array_key_exists("IdDocumentoSalidaZofraProducto", $value))
        {
          if(is_numeric($value["IdDocumentoSalidaZofraProducto"]))
          {        
            $this->sMovimientoDocumentoSalidaZofra->InsertarMovimientoDocumentoSalidaZofraNotaSalida($value);
          }
        }
      }
    }
  }

  /*Se borran documentos vinculados*/
  function BorrarNotasSalidasDesdeComprobanteVenta($data)
  {
    $resultado = $this->sDocumentoReferenciaNotaSalida->ObtenerDocumentosReferenciaByComprobanteVenta($data);

    if (count($resultado) > 0) {
      $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
      $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();

      foreach ($resultado as $key => $value) {
        $this->BorrarNotasSalida($value);

        $this->sDocumentoReferenciaNotaSalida->BorrarDocumentoReferenciaNotaSalida($value);

        //borramos movimiento de almacen de esas notas entrada
        $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaSalida($value);

        if ($parametro_lote == 1) {
          $this->sMovimientoAlmacenProductoLote->BorrarMovimientosAlmacenNotaSalida($value);
        }

        if ($parametro_dua == 1) {
          $this->sMovimientoDocumentoDua->BorrarMovimientosDocumentoDuaNotaSalida($value);
        }
        
        if ($data['IdTipoDocumento'] == ID_TIPO_DOCUMENTO_BOLETA) {
          if (array_key_exists("IdSubTipoDocumento", $data)) {
            if ($data['IdSubTipoDocumento'] == ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_T || $data['IdSubTipoDocumento'] == ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_Z) {
              $this->sMovimientoDocumentoSalidaZofra->BorrarMovimientosDocumentoSalidaZofraProductoNotaSalida($value);
            }
          }
        }
      }
    }

    return $resultado;
  }

  function BorrarNotasSalidasDesdeComprobanteCompra($data)
  {
    $resultado = $this->sDocumentoReferenciaNotaSalida->ObtenerDocumentosReferenciaByComprobanteCompra($data);
    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        $this->BorrarNotasSalida($value);
        $this->sDocumentoReferenciaNotaSalida->BorrarDocumentoReferenciaNotaSalida($value);

        //borramos movimiento de almacen de esas notas entrada
        $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaSalida($value);
      }
    }

    return $resultado;
  }

  function ActualizarNotaSalidaDesdeComprobanteCompra($data)
  {
    $this->BorrarNotasSalidasDesdeComprobanteCompra($data);
    $resultado = $this->sNotaSalida->InsertarNotaSalidaDesdeComprobanteCompra($data);
    return $resultado;
  }

  function ActualizarNotaSalidaDesdeComprobanteVenta($data)
  {
    $this->BorrarNotasSalidasDesdeComprobanteVenta($data);
    $resultado = $this->InsertarNotaSalidaDesdeComprobante($data);
    return $resultado;
  }

  function ObtenerNotaSalidaVentaSinDocumento($data)
  {
    $resultado = $this->mNotaSalida->ObtenerNotaSalidaVentaSinDocumento($data);
    return $resultado;
  }

  //[PARA CAMBIOS DE VINCULACION Y REVERSION DE SALDOS]
  function DescontarSaldosDesdeComprobanteVenta($data)
  {
    $resultado = $this->sDetalleNotaSalida->ConsultarDetallesNotaSalida($data);

    foreach ($resultado as $key => $value) {
      foreach ($data["DetallesComprobanteVenta"] as $key2 => $value2) {
        if (is_numeric($value2["IdProducto"])) {
          if ($value["IdProducto"] == $value2["IdProducto"]) {
            $value2["Cantidad"] = (is_string($value2["Cantidad"])) ? str_replace(',', "", $value2["Cantidad"]) : $value2["Cantidad"];
            if ($value2["Cantidad"] > $value["SaldoPendienteComprobante"]) {
              return "En el item del producto ".$value["NombreProducto"]." solo puede ingresarse una cantidad máxima de ".$value["SaldoPendienteComprobante"];
            }
            $value["SaldoPendienteComprobante"] = $value["SaldoPendienteComprobante"] - $value2["Cantidad"];
            $this->sDetalleNotaSalida->ActualizarDetalleNotaSalida($value);
          }
        }
      }
    }

    return $resultado;
  }

  function VincularComprobanteVentaConNotaSalida($data)
  {
    $data_moneda["IdMoneda"] = $data["IdMoneda"];
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data_moneda);
    $data["NombreMoneda"] = (count($moneda) > 0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda) > 0) ? $moneda[0]["CodigoMoneda"] : '';

    $this->sDocumentoReferenciaNotaSalida->InsertarDocumentoReferenciaNotaSalida($data);
    $resultado = $this->DescontarSaldosDesdeComprobanteVenta($data);
    return $resultado;
  }

  //[PROCESO PARA DESVINCULACION DE COMPROBANTEVENTA CON NOTA SALIDA]
  function RestaurarSaldosDesdeComprobanteVenta($data)
  {    
    $resultado = $this->sDetalleNotaSalida->ConsultarDetallesNotaSalida($data);

    foreach ($resultado as $key => $value) {
      foreach ($data["DetallesComprobanteVenta"] as $key2 => $value2) {
        if (is_numeric($value2["IdProducto"])) {
          if ($value["IdProducto"] == $value2["IdProducto"]) {
            $value2["Cantidad"] = (is_string($value2["Cantidad"])) ? str_replace(',', "", $value2["Cantidad"]) : $value2["Cantidad"];
            $value["SaldoPendienteComprobante"] = $value["SaldoPendienteComprobante"] + $value2["Cantidad"];
            $this->sDetalleNotaSalida->ActualizarDetalleNotaSalida($value);
          }
        }
      }
    }

    return $resultado;
  }

  function DesvincularComprobanteVentaConNotaSalida($data)
  {
    $resultado = $this->sDocumentoReferenciaNotaSalida->ObtenerDocumentosReferenciaByComprobanteVentaYNotaSalida($data);
    foreach ($resultado as $key => $value) {
      $this->sDocumentoReferenciaNotaSalida->BorrarDocumentoReferenciaNotaSalida($value);
      $value["DetallesComprobanteVenta"] = $data["DetallesComprobanteVenta"];
      $this->RestaurarSaldosDesdeComprobanteVenta($value);
    }
  }
}
