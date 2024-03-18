<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sNotaEntrada extends MY_Service
{

  public $NotaEntrada = array();
  public $DetalleNotaEntrada = array();

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
    $this->load->library('json');
    $this->load->helper("date");
    $this->load->model("Base");
    $this->load->model('Inventario/mNotaEntrada');
    $this->load->model('Venta/mComprobanteVenta');
    $this->load->model('Compra/mComprobanteCompra');
    $this->load->service('Compra/sComprobanteCompra');
    $this->load->service('Inventario/sDetalleNotaEntrada');
    $this->load->service("Inventario/sDocumentoReferenciaNotaEntrada");
    $this->load->service("Inventario/sMovimientoAlmacen");
    $this->load->service("Inventario/sMovimientoAlmacenProductoLote");
    $this->load->service("Inventario/sMovimientoDocumentoSalidaZofra");
    $this->load->service("Inventario/sMovimientoDocumentoDua");
    $this->load->service("Configuracion/Inventario/sMotivoNotaEntrada");
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
    $this->load->service('Seguridad/sAccesoUsuarioAlmacen');

    $this->NotaEntrada = $this->mNotaEntrada->NotaEntrada;
    $DetalleNotaEntrada = [];
    $DetalleNotaEntrada[] = $this->mDetalleNotaEntrada->DetalleNotaEntrada;
    $this->NotaEntrada["DetallesNotaEntrada"] = $DetalleNotaEntrada;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] = $this->sesionusuario->obtener_sesion_id_sede();
    $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);

    $Sedes = $this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario(); // $Sedes = $this->sSede->ListarSedesTipoAlmacen();
    if (count($Sedes) > 0) {
      $this->NotaEntrada["IdAsignacionSede"] = $Sedes[0]["IdAsignacionSede"];
    }
    $MotivosNotaEntrada = $this->sMotivoNotaEntrada->ListarMotivosNotaEntrada();

    $this->NotaEntrada["CodigoSedeAgencia"] = $this->sesionusuario->obtener_sesion_codigo_sede();
    $this->NotaEntrada["NombreSedeAgencia"] = $this->sesionusuario->obtener_sesion_nombre_sede();
    $this->NotaEntrada["FechaEmision"] = $hoy;
    $this->NotaEntrada["ValorTipoCambio"] = $TipoCambio == null ? "0.00" : $TipoCambio->TipoCambioVenta;
    $this->NotaEntrada["NumeroDocumentoIdentidad"] = "";
    $this->NotaEntrada["Direccion"] = "";
    $this->NotaEntrada["RazonSocial"] = "";
    $this->NotaEntrada["IdTipoVenta"] = "1";
    $this->NotaEntrada["NombreFormaPago"] = "";
    $this->NotaEntrada["NombreMoneda"] = "";
    $this->NotaEntrada["IdAsignacionSede"] = "";
    $this->NotaEntrada["EstadoPendienteNota"] = '0';
    $this->NotaEntrada["IdPersona"] = CLIENTE_NO_ESPECIFICADO;
    $this->NotaEntrada["SimboloMoneda"] = "";
    $this->NotaEntrada["NombreAbreviado"] = "";
    $this->NotaEntrada["SerieDocumento"] = "";
    $this->NotaEntrada["NumeroDocumento"] = "";
    $this->NotaEntrada["NombreAlmacen"] = $this->NotaEntrada["NombreSedeAgencia"]; //(count($Sedes) > 0) ? $Sedes[0]["NombreSede"] : "";
    $this->NotaEntrada["MotivoMovimiento"] = (count($MotivosNotaEntrada) > 0) ? $MotivosNotaEntrada[0]["NombreMotivoNotaEntrada"] : "";
    $this->NotaEntrada["RazonSocial"] = "";
    $this->NotaEntrada["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    $this->NotaEntrada["DetallesNotaEntrada"] = array(); //[0]=$this->sDetalleNotaEntrada->Cargar();
    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTAENTRADA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $this->NotaEntrada["SerieNotaEntrada"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]['SerieDocumento'] : "";
    $this->NotaEntrada["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]['IdCorrelativoDocumento'] : "";
    $this->NotaEntrada["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTAENTRADA;
    
    $this->NotaEntrada["IdSede"] =$parametro['IdSedeAgencia'];
    $this->NotaEntrada["NombreSede"] = $this->NotaEntrada["NombreSedeAgencia"];
    $data2["IdSede"] = $this->NotaEntrada["IdSede"];    
    $dataAsignacionSede=$this->sAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorIdSede($data2);
    $this->NotaEntrada["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];


    $fechaservidor = $this->Base->ObtenerFechaServidor("d/m/Y");
    $input["textofiltro"] = '';
    $input["FechaInicio"] = $fechaservidor;
    $input["FechaFin"] = $fechaservidor;
    $input["FechaHoy"] = $fechaservidor;
    $input["IdPersona"] = 3;
    $input["IdTipoDocumento"] = 3;
    $input["IdMoneda"] = 3;
    $input["TipoPersona"] = 2;

    $this->NotaEntrada["Filtros"] = $input;
    $this->NotaEntrada["BusquedaComprobantesVenta"] = array();
    $this->NotaEntrada["BusquedaComprobanteVenta"] = array();
    $this->NotaEntrada["MiniComprobantesVenta"] = array();
    $this->NotaEntrada["GrupoDetalleComprobanteVenta"] = array();

    $TasaIGV = $this->ObtenerTasaIGV();
    $FormasPago = $this->sFormaPago->ListarFormasPago();
    $Monedas = $this->sMoneda->ListarMonedas();
    $TiposTarjeta = $this->sTipoTarjeta->ListarTiposTarjeta();

    $data = array(
      'NuevoDetalleNotaEntrada' => $this->sDetalleNotaEntrada->Cargar(),
      'TasaIGV' => $TasaIGV,
      'SeriesDocumento' => $SeriesDocumento,
      'FormasPago' => $FormasPago,
      'Monedas' => $Monedas,
      'TiposTarjeta' => $TiposTarjeta,
      'Sedes' => $Sedes,
      'MotivosNotaEntrada' => $MotivosNotaEntrada,
      'TipoCambio' => $TipoCambio,
      'NoCliente' => CLIENTE_NO_ESPECIFICADO
    );

    $resultado = array_merge($this->NotaEntrada, $data);

    return $resultado;
  }

  function ConsultarNotasEntrada($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mNotaEntrada->ConsultarNotasEntrada($data, $numerofilainicio, $numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["DetallesNotaEntrada"] = [];
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

  function ObtenerNumeroTotalNotasEntrada($data)
  {
    $resultado = $this->mNotaEntrada->ObtenerNumeroTotalNotasEntrada($data);
    return $resultado;
  }

  function ConsultarComprobantesVentaPorPersona($data)
  {
    $data["TipoNota"] = ESTADO_PENDIENTE_NOTAENTRADA;
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaPendienteNotaPorCliente($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["IdComprobante"] = $item["IdComprobanteVenta"];
      $resultado[$key]["Modulo"] = ID_MODULO_VENTA;
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesNotaEntrada"] = $this->sDetalleNotaEntrada->ConsultarDetallesComprobanteVenta($item);
    }

    return $resultado;
  }

  function ConsultarComprobantesCompraPorPersona($data)
  {
    $data["TipoNota"] = ESTADO_PENDIENTE_NOTAENTRADA;
    $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompraPendienteNotaPorCliente($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["IdComprobante"] = $item["IdComprobanteCompra"];
      $resultado[$key]["Modulo"] = ID_MODULO_COMPRA;
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesNotaEntrada"] = $this->sDetalleNotaEntrada->ConsultarDetallesComprobanteCompra($item);
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

  function InsertarNotaEntrada($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      // $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);

      // $resultadoValidacion = $this->ValidarNotaEntrada($data);
      $resultadoValidacion = "";

      if ($resultadoValidacion == "") {
        $resultado = $this->mNotaEntrada->InsertarNotaEntrada($data);
        $IdNotaEntrada = $resultado["IdNotaEntrada"];

        $resultado["DetallesNotaEntrada"] = $this->sDetalleNotaEntrada->InsertarDetallesNotaEntrada($IdNotaEntrada, $data["DetallesNotaEntrada"]);

        if (strlen($data["IdNotaEntrada"]) == 0 && strlen($data["NumeroNotaEntrada"]) == 0) {
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $resultadoCorrelativo =  $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          // $resultadoCorrelativo =  $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);

          $resultado["NumeroNotaEntrada"] = $resultadoCorrelativo;
          $this->ActualizarSerieDocumentoNotaEntrada($resultado);
          $resultado["NumeroNotaEntrada"] = str_pad($resultadoCorrelativo, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
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

  function InsertarNotaEntradaDesdeEntrada($data)
  {
    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';

    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';

    //PARA MOTIVO COMPROBANTE VENTA
    $resultadoMotivoEntrada = $this->sMotivoNotaEntrada->ObtenerMotivoNotaEntrada($data);
    $data["MotivoMovimiento"] = $resultadoMotivoEntrada->NombreMotivoNotaEntrada;

    $resultado = $this->InsertarNotaEntrada($data);
    $data["DetallesNotaEntrada"] = $resultado["DetallesNotaEntrada"];

    if (is_array($resultado)) {
      $data["IdNotaEntrada"] = $resultado["IdNotaEntrada"];
      foreach ($data["DetallesNotaEntrada"] as $key => $value) {
        if (is_numeric($value["IdProducto"])) {
          $data["DetallesNotaEntrada"][$key]["CostoUnitarioAdquisicion"] = (is_string($value["ValorUnitario"])) ? str_replace(',', "", $value["ValorUnitario"]) : $value["ValorUnitario"];
        }
      }

      if (array_key_exists("MiniComprobantesVenta", $data)) {
        
        if (count($data["MiniComprobantesVenta"]) > 0 ){

          foreach ($data["DetallesNotaEntrada"] as $key => $value) {
            if (is_numeric($value["IdProducto"])) {
              if (array_key_exists("CostoUnitarioAdquisicion", $data["DetallesNotaEntrada"][$key])) {
                if ($data["MiniComprobantesVenta"][0]["IdMoneda"] == ID_MONEDA_SOLES) {
                  $data["DetallesNotaEntrada"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"]; //$value["ValorUnitario"] - $value["DescuentoUnitario"];
                } else {
                  $data["DetallesNotaEntrada"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"] * $data["MiniComprobantesVenta"][0]["ValorTipoCambio"];
                }
              }
            }
          }
        
          $this->InsertarDocumentosReferenciaNotaEntrada($data);

          $this->sDetalleNotaEntrada->ActualizarSaldoPendienteDetalleComprobanteVenta($data["DetallesNotaEntrada"]);

          //Se actualizan los estados
          $this->ActualizarEstadoDocumentosReferenciaNotaEntrada($data);
        }                
      }

      $this->InsertarMovimientoAlmacen($data);

      return $resultado;
    } else {
      return "";
    }
  }

  function InsertarDocumentosReferenciaNotaEntrada($data)
  {
    $this->sDocumentoReferenciaNotaEntrada->BorrarDocumentosReferenciaNotaEntrada($data);

    foreach ($data["MiniComprobantesVenta"] as $key => $value) {
      $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
      $this->sDocumentoReferenciaNotaEntrada->InsertarDocumentoReferenciaNotaEntrada($value);
    }

    return "";
  }

  function ActualizarEstadoDocumentosReferenciaNotaEntrada($data)
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

  function InsertarNotaEntradaDesdeComprobante($data)
  {
    //PARA NUEVA DATA
    $tipodocumentoreferencia = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumentoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["CodigoTipoDocumento"] : '';
    $data["NombreAbreviadoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["NombreAbreviado"] : '';
    $data["SerieDocumentoReferencia"] = $data["SerieDocumento"];
    $data["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];

    $data["IdNotaEntrada"] = "";
    $data["NumeroNotaEntrada"] = ""; //$data["NumeroDocumento"];
    if (array_key_exists("FechaMovimientoAlmacen", $data)) {
      $data["FechaEmision"] = $data["FechaMovimientoAlmacen"];
    }
    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTAENTRADA;
    $parametro['IdSedeAgencia'] = (count($asignacionsede) > 0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieNotaEntrada"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : ''; //$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';

    $data_nota["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTAENTRADA;
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data_nota); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';

    $data_moneda["IdMoneda"] = $data["IdMoneda"];
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data_moneda);
    $data["NombreMoneda"] = (count($moneda) > 0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda) > 0) ? $moneda[0]["CodigoMoneda"] : '';

    //PARA MOTIVO COMPROBANTE VENTA
    $data["IdMotivoNotaEntrada"] = ID_MOTIVO_NOTA_ENTRADA_DEVOLUCION_CLIENTE_CON_DOCUMENTO;
    $resultadoMotivoEntrada = $this->sMotivoNotaEntrada->ObtenerMotivoNotaEntrada($data);
    $data["MotivoMovimiento"] = $resultadoMotivoEntrada->NombreMotivoNotaEntrada;

    $data["DetallesNotaEntrada"] = $data["DetallesComprobanteVenta"];
    $resultado = $this->InsertarNotaEntrada($data);
    if ($resultado) {
      $data["IdNotaEntrada"] = $resultado["IdNotaEntrada"];
      $this->InsertarMovimientoAlmacen($data);

      $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();

      if ($parametro_lote == 1) {
        //SE añade funcion de actualizado
        $this->InsertarMovimientoAlmacenProductoLote($data);
      }

      $this->sDocumentoReferenciaNotaEntrada->InsertarDocumentoReferenciaNotaEntrada($data);
    }
    return $resultado;
  }

  function InsertarNotaEntradaDesdeComprobanteCompra($data)
  {
    //PARA NUEVA DATA
    $tipodocumentoreferencia = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumentoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["CodigoTipoDocumento"] : '';
    $data["NombreAbreviadoReferencia"] = (count($tipodocumentoreferencia) > 0) ? $tipodocumentoreferencia[0]["NombreAbreviado"] : '';
    $data["SerieDocumentoReferencia"] = $data["SerieDocumento"];
    $data["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];

    $data["IdNotaEntrada"] = "";
    $data["NumeroNotaEntrada"] = ""; //$data["NumeroDocumento"];
    if (array_key_exists("FechaMovimientoAlmacen", $data)) {
      $data["FechaEmision"] = $data["FechaMovimientoAlmacen"];
    }

    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);

    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTAENTRADA;
    $parametro['IdSedeAgencia'] = (count($asignacionsede) > 0) ? $asignacionsede[0]["IdSede"] : '';
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $data["SerieNotaEntrada"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : ''; //$data["SerieDocumento"];
    $data["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : '';
    $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';

    $data_nota["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTAENTRADA;
    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data_nota); //$this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';

    $data_moneda["IdMoneda"] = $data["IdMoneda"];
    $moneda = $this->sMoneda->ObtenerMonedaPorId($data_moneda);
    $data["NombreMoneda"] = (count($moneda) > 0) ? $moneda[0]["NombreMoneda"] : '';
    $data["CodigoMoneda"] = (count($moneda) > 0) ? $moneda[0]["CodigoMoneda"] : '';

    //PARA MOTIVO COMPROBANTE VENTA
    $data["IdMotivoNotaEntrada"] = ID_MOTIVO_NOTA_ENTRADA_COMPRA_CON_DOCUMENTO;
    $resultadoMotivoEntrada = $this->sMotivoNotaEntrada->ObtenerMotivoNotaEntrada($data);
    $data["MotivoMovimiento"] = $resultadoMotivoEntrada->NombreMotivoNotaEntrada;

    $data["DetallesNotaEntrada"] = $data["DetallesComprobanteCompra"];
    $resultado = $this->InsertarNotaEntrada($data);

    if ($resultado) {
      $data["IdNotaEntrada"] = $resultado["IdNotaEntrada"];

      if (is_string($data["ValorTipoCambio"])) {
        $data["ValorTipoCambio"] = str_replace(',', "", $data["ValorTipoCambio"]);
      }

      foreach ($data["DetallesNotaEntrada"] as $key => $value) {
        if (is_numeric($value["IdProducto"])) {
          if ($data["IdMoneda"] == ID_MONEDA_SOLES) {
            $data["DetallesNotaEntrada"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"]; //$value["ValorUnitario"] - $value["DescuentoUnitario"];
          } else {
            $data["DetallesNotaEntrada"][$key]["CostoUnitarioAdquisicion"] = $value["CostoUnitarioCalculado"] * $data["ValorTipoCambio"];
          }
        }
      }

      $this->InsertarMovimientoAlmacen($data);

      $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
      if ($parametro_lote == 1) {
        //SE añade funcion de actualizado
        $this->InsertarMovimientoAlmacenProductoLote($data);
      }

      $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
      $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
      if ($parametro_zofra == 1 && $data["IdTipoDocumento"] == $parametro_tipodocumento_zofra) {
        //SE añade funcion de actualizado
        $this->InsertarMovimientoDocumentoSalidaZofra($data);
      }

      $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
      $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
      if ($parametro_dua == 1 && ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $parametro_tipodocumento_dua_alternativo)) {
        $this->InsertarMovimientoDocumentoDua($data);
      }

      $this->sDocumentoReferenciaNotaEntrada->InsertarDocumentoReferenciaNotaEntrada($data);
    }

    return $resultado;
  }

  function ActualizarNotaEntrada($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);

      // $resultadoValidacion = $this->ValidarNotaEntrada($data);
      // if($resultadoValidacion == "")
      // {
      $resultado = $this->mNotaEntrada->ActualizarNotaEntrada($data);
      $IdNotaEntrada = $data["IdNotaEntrada"];
      $resultado["DetallesNotaEntrada"] = $this->sDetalleNotaEntrada->ActualizarDetallesNotaEntrada($IdNotaEntrada, $data["DetallesNotaEntrada"]);
      $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);

      if (array_key_exists("MiniComprobantesVenta", $data)) {
        //SE BORRAN LOS MOVIMIENTOS ALMACEN
        $this->InsertarDocumentosReferenciaNotaEntrada($data);

        // $this->sDetalleNotaEntrada->ActualizarSaldoPendienteDetalleComprobanteVenta($data["DetallesNotaEntrada"]);

        //Se actualizan los estados
        // $this->ActualizarEstadoDocumentosReferenciaNotaEntrada($data);
      }

      $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaEntrada($data);

      $this->InsertarMovimientoAlmacen($data);

      //SE añade funcion de actualizado
      $this->InsertarMovimientoAlmacenProductoLote($data);

      return $resultado;
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarNotaEntrada($data)
  {
    $resultado = $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaEntrada($data);

    $this->BorrarNotasEntrada($data);
    $data["DetallesNotaEntrada"] = $resultado;
    return $data;
  }

  /*FUNCION PARA ELIMINAR NOTA DE ENTRADA Y DETALLES*/
  function BorrarNotasEntrada($data)
  {
    $this->mNotaEntrada->BorrarNotaEntrada($data);
    $this->sDetalleNotaEntrada->BorrarDetalleNotaEntrada($data);
    return $data;
  }

  function ActualizarSerieDocumentoNotaEntrada($data)
  {
    $IdNotaEntrada = $data["IdNotaEntrada"];
    $this->mNotaEntrada->ActualizarNotaEntrada($data);
    return "";
  }

  public function ActualizarEstadoPendienteNotaVenta($data)
  {
    $resultado = $this->sDetalleNotaEntrada->TotalSaldoPendienteEntradaVenta($data);

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
    $resultado = $this->sDetalleNotaEntrada->TotalSaldoPendienteEntradaCompra($data);

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
    foreach ($data["DetallesNotaEntrada"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        $value["NombreAlmacen"] = $data["NombreAlmacen"];
        $value["FechaEmision"] = $data["FechaEmision"];
        $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
        $value["CodigoSede"] = $data["CodigoSede"];
        //NUEVOS DATOS - PARA DI - DC EN COMPRAS
        // $value["IdTipoDocumento"] = $data["IdTipoDocumento"];

        //NUEVOS DATOS COMPRA O VENTA
        $value["CodigoTipoDocumentoReferencia"] = (array_key_exists("CodigoTipoDocumentoReferencia", $data)) ? $data["CodigoTipoDocumentoReferencia"] : NULL;
        $value["NombreAbreviadoReferencia"] = (array_key_exists("NombreAbreviadoReferencia", $data)) ? $data["NombreAbreviadoReferencia"] : NULL;
        $value["SerieDocumentoReferencia"] = (array_key_exists("SerieDocumentoReferencia", $data)) ? $data["SerieDocumentoReferencia"] : NULL;
        $value["NumeroDocumentoReferencia"] = (array_key_exists("NumeroDocumentoReferencia", $data)) ? $data["NumeroDocumentoReferencia"] : NULL;

        $this->sMovimientoAlmacen->InsertarMovimientoAlmacenNotaEntrada($value);
      }
    }
  }

  public function InsertarMovimientoAlmacenProductoLote($data)
  {
    foreach ($data["DetallesNotaEntrada"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);

        $value["IdLoteProducto"] = $value["IdLoteProducto"];
        $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];

        // $value["RazonSocial"] = $data["RazonSocial"];
        // $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        // $value["NombreAlmacen"] = $data["NombreAlmacen"];
        // $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
        // $value["CodigoSede"] = $data["CodigoSede"];
        $this->sMovimientoAlmacenProductoLote->InsertarMovimientoAlmacenProductoLoteNotaEntrada($value);
      }
    }
  }

  public function InsertarMovimientoDocumentoSalidaZofra($data)
  {
    foreach ($data["DetallesNotaEntrada"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);

        $value["IdDocumentoSalidaZofra"] = $data["IdDocumentoSalidaZofra"];
        $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];

        $value["FechaMovimiento"] = convertToDate($data["FechaEmision"]);
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        
        if(array_key_exists("IdDocumentoSalidaZofra", $value))
        {
          if(is_numeric($value["IdDocumentoSalidaZofra"]))
          {
            $this->sMovimientoDocumentoSalidaZofra->InsertarMovimientoDocumentoSalidaZofraNotaEntrada($value);
          }
        }
      }
    }
  }

  //PARA INSERTAR MOVIMIENTO A Dua
  public function InsertarMovimientoDocumentoDua($data)
  {
    foreach ($data["DetallesNotaEntrada"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
        $value["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);

        $value["FechaMovimiento"] = convertToDate($data["FechaEmision"]);
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];

        $value["IdDua"] = $data["IdDua"];
        $value["IdNotaEntrada"] = $data["IdNotaEntrada"];

        $this->sMovimientoDocumentoDua->InsertarMovimientoDocumentoDuaNotaEntrada($value);
      }
    }
  }

  /*Se borran documentos vinculados*/
  function BorrarNotasEntradasDesdeComprobanteVenta($data)
  {
    $resultado = $this->sDocumentoReferenciaNotaEntrada->ObtenerDocumentosReferenciaByComprobanteVenta($data);
    if (count($resultado) > 0) {
      $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
      foreach ($resultado as $key => $value) {
        $this->BorrarNotasEntrada($value);

        $this->sDocumentoReferenciaNotaEntrada->BorrarDocumentoReferenciaNotaEntrada($value);

        //borramos movimiento de almacen de esas notas entrada
        $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaEntrada($value);

        if ($parametro_lote == 1) {
          /*Aqui se debe borrar data de movimientoalmacenproductolote*/
          $this->sMovimientoAlmacenProductoLote->BorrarMovimientosAlmacenProductoLoteNotaEntrada($value);
        }
      }
    }

    return $resultado;
  }

  function BorrarNotasEntradasDesdeComprobanteCompra($data, $json = false)
  {
    $resultado = $this->sDocumentoReferenciaNotaEntrada->ObtenerDocumentosReferenciaByComprobanteCompra($data);

    if (count($resultado) > 0) {
      $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
      $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
      $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();

      foreach ($resultado as $key => $value) {
        $this->BorrarNotasEntrada($value);

        $this->sDocumentoReferenciaNotaEntrada->BorrarDocumentoReferenciaNotaEntrada($value);

        //borramos movimiento de almacen de esas notas entrada
        $this->sMovimientoAlmacen->BorrarMovimientosAlmacenNotaEntrada($value);

        if ($parametro_lote == 1) {
          /*Aqui se debe borrar data de movimientoalmacenproductolote*/
          $this->sMovimientoAlmacenProductoLote->BorrarMovimientosAlmacenProductoLoteNotaEntrada($value);
        }

        if ($parametro_zofra == 1) {
          //SE añade funcion de actualizado
          $this->sMovimientoDocumentoSalidaZofra->BorrarMovimientosDocumentoSalidaZofraProductoNotaEntrada($value);
        }

        if ($parametro_dua == 1) {
          $this->sMovimientoDocumentoDua->BorrarMovimientosDocumentoDuaNotaEntrada($value);
        }
      }
    }

    return $resultado;
  }

  function ActualizarNotaEntradaDesdeComprobanteCompra($data)
  {
    $this->BorrarNotasEntradasDesdeComprobanteCompra($data);
    $resultado = $this->InsertarNotaEntradaDesdeComprobanteCompra($data);
    return $resultado;
  }

  function ActualizarNotaEntradaDesdeComprobanteVenta($data)
  {
    $this->BorrarNotasEntradasDesdeComprobanteVenta($data);
    $resultado = $this->sNotaEntrada->InsertarNotaEntradaDesdeComprobante($data);
    return $resultado;
  }

  //FUNCION PARA DOCUMENTO INGRESO Y STOCK DE PRODUCTOS
  function ConsultarMovimientoAlmacenNotasEntradaDesdeComprobanteCompra($data)
  {
    $resultado = $this->sDocumentoReferenciaNotaEntrada->ObtenerDocumentosReferenciaByComprobanteCompra($data);

    $movimientos = array();
    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        //borramos movimiento de almacen de esas notas entrada
        $response = $this->sMovimientoAlmacen->ObtenerMovimientosAlmacenNotaEntrada($value);
        if (count($response) > 0) {
          foreach ($response as $key2 => $value2) {
            array_push($movimientos, $value2);
          }
        }
      }
    }

    return $movimientos;
  }
}
