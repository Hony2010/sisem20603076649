<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sGuiaRemisionRemitente extends MY_Service
{

  public $GuiaRemisionRemitente = array();
  public $DetalleGuiaRemisionRemitente = array();

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
    $this->load->model('Venta/mGuiaRemisionRemitente');
    $this->load->service('Venta/sDetalleGuiaRemisionRemitente');
    $this->load->service('Venta/sDetalleComprobanteVenta');
    $this->load->service('Venta/sComprobanteVenta');
    $this->load->service("Catalogo/sCliente");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service("Configuracion/General/sEmpresa");
    $this->load->service("Configuracion/Catalogo/sDireccionCliente");
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    // $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sSede");
    $this->load->service('Configuracion/General/sAsignacionSede');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service("Seguridad/sLicencia");
    $this->load->service("Seguridad/sUsuario");
    $this->load->service("Seguridad/sAccesoUsuarioAlmacen");
    $this->load->service('Configuracion/General/sModalidadTraslado');
    $this->load->service('Configuracion/General/sMotivoTraslado');
    $this->load->service("Configuracion/General/sSituacionComprobanteElectronico");

    $this->GuiaRemisionRemitente = $this->mGuiaRemisionRemitente->GuiaRemisionRemitente;
    $DetalleGuiaRemisionRemitente = [];
    // $DetalleGuiaRemisionRemitente[] = $this->mDetalleGuiaRemisionRemitente->DetalleGuiaRemisionRemitente;
    $this->GuiaRemisionRemitente["DetallesGuiaRemisionRemitente"] = $DetalleGuiaRemisionRemitente;
  }

  function BuscarCorrelativoEnSeries($data, $config)
  {
    $response = false;
    foreach ($data as $key => $value) {
      if($config["IdCorrelativoDocumento"] == $value["IdCorrelativoDocumento"])
      {
        $response = $value;
      }
    }
    return $response;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] = $this->sesionusuario->obtener_sesion_id_sede();
    $this->GuiaRemisionRemitente["IdSedeAgencia"]  = $this->sesionusuario->obtener_sesion_id_sede();
    // $this->GuiaRemisionRemitente["IdMoneda"] = ID_MONEDA_SOLES;
    $dataVendedor["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();    
    $this->GuiaRemisionRemitente["Vendedores"] = $this->sUsuario->ListarUsuariosPorSede($dataVendedor);
    $this->GuiaRemisionRemitente["CodigoSedeAgencia"] = $this->sesionusuario->obtener_sesion_codigo_sede();
    $this->GuiaRemisionRemitente["NombreSedeAgencia"] = $this->sesionusuario->obtener_sesion_nombre_sede();
    $this->GuiaRemisionRemitente["FechaEmision"] = $hoy;
    $this->GuiaRemisionRemitente["FechaTraslado"] = $hoy;
    $this->GuiaRemisionRemitente["NumeroDocumentoIdentidad"] = "";
    $this->GuiaRemisionRemitente["Direccion"] = "";
    $this->GuiaRemisionRemitente["RazonSocial"] = "";
    // $this->GuiaRemisionRemitente["IdCorrelativoDocumento"] = ID_CORRELATIVO_DOCUMENTO_PRINCIPAL;
    $this->GuiaRemisionRemitente["IdTipoDocumento"] = ID_TIPO_DOCUMENTO_GUIA_REMISION_REMITENTE;
    // $this->GuiaRemisionRemitente["NombreMoneda"] = "";
    $this->GuiaRemisionRemitente["Observacion"] = "";
    $this->GuiaRemisionRemitente["NombreAbreviado"] = "";
    $this->GuiaRemisionRemitente["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    
    $this->GuiaRemisionRemitente["NumeroBrevete"] = "";
    $this->GuiaRemisionRemitente["NumeroConstanciaInscripcion"] = "";
    $this->GuiaRemisionRemitente["NumeroGuiaTransportista"] = "";
    $this->GuiaRemisionRemitente["NumeroContenedor"] = "";
    $this->GuiaRemisionRemitente["CodigoPuerto"] = "";

    // $this->GuiaRemisionRemitente["ParametroAdministrador"] = $this->sesionusuario->obtener_sesion_vista_combo_venta_usuario();
    // $this->GuiaRemisionRemitente["IdUsuarioActivo"] = $this->sesionusuario->obtener_sesion_id_usuario();
    // $this->GuiaRemisionRemitente["VerTodasVentas"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    // $this->GuiaRemisionRemitente["IdRolUsuario"] = $this->sesionusuario->obtener_sesion_id_rol();

    $MotivosTraslado = $this->sMotivoTraslado->ListarMotivoTraslados();
    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($this->GuiaRemisionRemitente);

    $dataConfigCorrelativo = $this->json->ObtenerConfigCorrelativo($this->sesionusuario->obtener_sesion_id_usuario(), $this->GuiaRemisionRemitente);

    $this->GuiaRemisionRemitente["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : "";
    $this->GuiaRemisionRemitente["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : "";
    $this->GuiaRemisionRemitente["IdSede"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdSede"] : "";

    if($dataConfigCorrelativo != false)
    {
      $busqueda = $this->BuscarCorrelativoEnSeries($SeriesDocumento, $dataConfigCorrelativo);
      if($busqueda != false)
      {
        $this->GuiaRemisionRemitente["IdCorrelativoDocumento"] = $busqueda["IdCorrelativoDocumento"];
        $this->GuiaRemisionRemitente["SerieDocumento"] = $busqueda["SerieDocumento"];
      }
    }

    //$this->GuiaRemisionRemitente["IdSedeAlmacen"] = 1;
    $this->GuiaRemisionRemitente["NombreSedeAlmacen"] = $this->GuiaRemisionRemitente["NombreSedeAgencia"];
    $this->GuiaRemisionRemitente["CambiosFormulario"] = false;
    $this->GuiaRemisionRemitente["IdModalidadTraslado"] = ID_PARAMETRO_MODALIDAD_TRASLADO_PUBLICO;
    $this->GuiaRemisionRemitente["IdMotivoTraslado"] = ID_PARAMETRO_MOTIVO_TRASLADO_VENTA;
    // $Monedas = $this->sMoneda->ListarMonedas();
    $dataCliente = $this->sCliente->Cargar();

    $this->GuiaRemisionRemitente["DistritosPuntoPartida"] = array();
    $this->GuiaRemisionRemitente["ProvinciasPuntoPartida"] = array();
    $this->GuiaRemisionRemitente["DistritosPuntoLlegada"] = array();
    $this->GuiaRemisionRemitente["ProvinciasPuntoLlegada"] = array();

    $this->GuiaRemisionRemitente["NumeroDocumentoIdentidadDestinatario"] = "";
    $this->GuiaRemisionRemitente["RazonSocialDestinatario"] = "";

    $this->GuiaRemisionRemitente["NumeroDocumentoIdentidadTransportista"] = "";
    $this->GuiaRemisionRemitente["RazonSocialTransportista"] = "";
    $this->GuiaRemisionRemitente["NombreDocumento"] = "";

    $this->GuiaRemisionRemitente["ParametroObservacionGuia"] = $this->sConstanteSistema->ObtenerParametroObservacionGuia();
    $this->GuiaRemisionRemitente["ParametroLote"] = $this->sConstanteSistema->ObtenerParametroLote();
    $Sedes = $this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario();
    $this->GuiaRemisionRemitente["IdAsignacionSede"] =$Sedes[0]["IdAsignacionSede"];
          
  
    $data = array(
      'NuevoDetalleGuiaRemisionRemitente' => $this->sDetalleGuiaRemisionRemitente->Cargar(),
      'DetallesGuiaRemisionRemitente' => array(),
      'SeriesDocumento' => $SeriesDocumento,
      'MotivosTraslado' => $MotivosTraslado,
      'Sedes' => $Sedes
      // 'Monedas'=>$Monedas,
    );

    $resultado = array_merge($this->GuiaRemisionRemitente, $data);

    return $resultado;
  }
  

  function ConsultarGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data)
  {
    $resultado = $this->mGuiaRemisionRemitente->ConsultarGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaTraslado"] = convertirFechaES($resultado[$key]["FechaTraslado"]);
      $resultado[$key]["DetallesGuiaRemisionRemitente"] = [];
      $SituacionCPE = $this->sSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($item["SituacionCPE"]);
      $resultado[$key]["AbreviaturaSituacionCPE"] = ($SituacionCPE != null) ? $SituacionCPE->AbreviaturaSituacionComprobanteElectronicoVentas : '';
    }
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitente($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mGuiaRemisionRemitente->ConsultarGuiasRemisionRemitente($data, $numerofilainicio, $numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaTraslado"] = convertirFechaES($resultado[$key]["FechaTraslado"]);
      $resultado[$key]["DetallesGuiaRemisionRemitente"] = [];
      $SituacionCPE = $this->sSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($item["SituacionCPE"]);
      $resultado[$key]["AbreviaturaSituacionCPE"] = ($SituacionCPE != null) ? $SituacionCPE->AbreviaturaSituacionComprobanteElectronicoVentas : '';
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

  function ObtenerNumeroTotalGuiasRemisionRemitente($data)
  {
    $resultado = $this->mGuiaRemisionRemitente->ObtenerNumeroTotalGuiasRemisionRemitente($data);
    return $resultado;
  }

  function ValidarDuplicadoDeGuiaRemisionRemitente($data)
  {
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "");

    $resultado = $this->mGuiaRemisionRemitente->ObtenerDuplicadoDeGuiaRemisionRemitente($data);
    $FechaEmision = $resultado[0]['FechaEmision'];
    $fecha = substr($FechaEmision, 0, 10);
    $tempdate = explode('-', $fecha);

    if (count($resultado) > 0) {
      return "Este Comprobante se emitio en el mes: " . $meses[(int) $tempdate[01]] . " y año: " . $tempdate[0];
    } else {
      return "";
    }
  }

  function ObtenerFechaEmisionMinimo()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_FECHA_EMISION_MINIMO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerAtributosGuiaRemisionRemitente()
  {
    $data['IdGrupoParametro'] = ID_ATRIBUTO_COMPROBANTE_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      return $resultado;
    }
  }

  function ObtenerFormatoImpresion()
  {
    $data['IdParametroSistema'] = ID_FORMATO_IMPRESION;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ValidarCantidad($data)
  {
    $cantidad = $data["Cantidad"];

    if ($cantidad == "") {
      return "ingresar la cantidad del producto";
    } else {
      return "";
    }
  }

  /**VALIDACION DE FECHA DE CERTIFICADO DIGITAL */
  function ValidarCertificadoDigital($data)
  {
    $datosEmpresa = $this->sEmpresa->ObtenerDatosEmpresa();
    if ($data["FechaEmision"] >= $datosEmpresa["FechaInicioCertificadoDigital"] && $data["FechaEmision"] <= $datosEmpresa["FechaFinCertificadoDigital"]) {
      return "";
    } else {
      return  "No se puede firmar el comprobante debido a que el certificado ha vencido. Coordine con su administrador y adquiere un nuevo certificado";
    }
  }

  function InsertarGuiaRemisionRemitente($data)
  {
    try {
      if ($data["FechaTraslado"] == "") {
        $data["FechaTraslado"] = $data["FechaEmision"];
      }
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaTraslado"] = convertToDate($data["FechaTraslado"]);
      $data["IndicadorM1L"] = convertToDate($data["IndicadorM1L"]);
      $data["IndicadorTransbordo"]=$data["IdModalidadTraslado"] == 1 ? "1" : "0";
      $data["DenominacionEnvioTransbordo"]=$data["IndicadorTransbordo"] == "1" ? "SUNAT_Envio_IndicadorTrasladoVehiculoM1L" : "";
      $resultadoValidacion = $this->ValidarGuiaRemisionRemitente($data);
      $resultadoValidacionCertificado = $this->ValidarCertificadoDigital($data);

      if(isset($_POST['IndicadorM1L']) && $_POST['IndicadorM1L'] == '1'){
      $valor_checkbox = 1;
      } else {
      $valor_checkbox = 0;
      };

      if (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } elseif ($resultadoValidacionCertificado != "") {
        return $resultadoValidacionCertificado;
      } elseif ($resultadoValidacion == "") {
        $resultado = $this->mGuiaRemisionRemitente->InsertarGuiaRemisionRemitente($data);
        $IdGuiaRemisionRemitente = $resultado["IdGuiaRemisionRemitente"];

        $resultado["DetallesGuiaRemisionRemitente"] = $this->sDetalleGuiaRemisionRemitente->InsertarDetallesGuiaRemisionRemitente($IdGuiaRemisionRemitente, $data["DetallesGuiaRemisionRemitente"]);
        // print_r($resultado);exit;
        if (strlen($data["IdGuiaRemisionRemitente"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $UltimoDocumento = $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          // print_r($dataCorrelativo["IdCorrelativoDocumento"]);exit;
          // print_r($UltimoDocumento);exit;
          $input = $data;
          $input["NumeroDocumento"] = $UltimoDocumento;
          $resultadoValidacionCorrelativo = $this->ValidarCorrelativoDocumento($input);
          if ($resultadoValidacionCorrelativo != "") return $resultadoValidacionCorrelativo;
          $resultado["NumeroDocumento"] = $UltimoDocumento;

          $this->ActualizarSerieDocumentoGuiaRemisionRemitente($resultado);
          $resultado["NumeroDocumento"] = str_pad($UltimoDocumento, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        } else {
          $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);
          if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
            //VALIDACION DE COMPROBANTES LIBRES
            $cantidadComprobanteLibreSerie =  $this->sConstanteSistema->ObtenerParametroCantidadComprobanteLibreSerie();
            $diferenciaNumeroDocumento = (int) $data["NumeroDocumento"] - (int) $resultadoCorrelativo->UltimoDocumento;
            if ($diferenciaNumeroDocumento > $cantidadComprobanteLibreSerie) {
              return "Usted acaba de dejar " . $diferenciaNumeroDocumento . " comprobantes libres y solo esta permitido un máximo de " . $cantidadComprobanteLibreSerie . " comprobantes libres. Por favor, revise el número del comprobante ingresado.";
            }
            $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
            $input["UltimoDocumento"] = $data["NumeroDocumento"];
            $input["SerieDocumento"] = $data["SerieDocumento"];
            $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
            $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
          }
        }

        //DESCONTAMOS SALDOS DE GUIA REMISION
        $this->DescontarSaldosEnDetallesComprobanteVenta($resultado);

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaTraslado"] = convertirFechaES($resultado["FechaTraslado"]);
        
        if (strlen($data["IdComprobanteVenta"]) > 0) {
          $dataComprobanteVenta["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
          $dataComprobanteVenta["GuiaRemision"] = $resultado["SerieDocumento"]."-".$resultado["NumeroDocumento"];
          $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($dataComprobanteVenta);
        }

        return $resultado;
      } else {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarGuiaRemisionRemitente($data)
  {
    try {
      if ($data["FechaTraslado"] == "") {
        $data["FechaTraslado"] = $data["FechaEmision"];
      }
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaTraslado"] = convertToDate($data["FechaTraslado"]);
      $data["IndicadorM1L"] = convertToDate($data["IndicadorM1L"]);
      $data["IndicadorTransbordo"]=$data["IdModalidadTraslado"] == 1 ? "1" : "0";
      $data["DenominacionEnvioTransbordo"]=$data["IndicadorTransbordo"] == "1" ? "SUNAT_Envio_IndicadorTrasladoVehiculoM1L" : "";
      $resultadoValidacion = $this->ValidarGuiaRemisionRemitente($data);
      $resultadoValidacionCertificado = $this->ValidarCertificadoDigital($data);

      if(isset($_POST['IndicadorM1L']) && $_POST['IndicadorM1L'] == '1'){
      $valor_checkbox = 1;
      } else {
      $valor_checkbox = 0;
      };

      if (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } elseif ($resultadoValidacionCertificado != "") {
        return $resultadoValidacionCertificado;
      } else if ($resultadoValidacion == "") {
        //REVERTIMOS LOS SALDOS DE GUIA REMISION
        $this->RevertirSaldosEnDetallesComprobanteVenta($data);

        $resultado = $this->mGuiaRemisionRemitente->ActualizarGuiaRemisionRemitente($data);
        $IdGuiaRemisionRemitente = $data["IdGuiaRemisionRemitente"];

        $resultado["DetallesGuiaRemisionRemitente"] = $this->sDetalleGuiaRemisionRemitente->ActualizarDetallesGuiaRemisionRemitente($IdGuiaRemisionRemitente, $data["DetallesGuiaRemisionRemitente"]);

        $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);
        if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
          //VALIDACION DE COMPROBANTES LIBRES
          $cantidadComprobanteLibreSerie =  $this->sConstanteSistema->ObtenerParametroCantidadComprobanteLibreSerie();
          $diferenciaNumeroDocumento = (int) $data["NumeroDocumento"] - (int) $resultadoCorrelativo->UltimoDocumento;
          if ($diferenciaNumeroDocumento > $cantidadComprobanteLibreSerie) {
            return "Usted acaba de dejar " . $diferenciaNumeroDocumento . " comprobantes libres y solo esta permitido un máximo de " . $cantidadComprobanteLibreSerie . " comprobantes libres. Por favor, revise el número del comprobante ingresado.";
          }
          $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $input["UltimoDocumento"] = $data["NumeroDocumento"];
          $input["SerieDocumento"] = $data["SerieDocumento"];
          $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
          $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
        }

        //DESCONTAMOS LOS SALDOS
        $this->DescontarSaldosEnDetallesComprobanteVenta($resultado);

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaTraslado"] = convertirFechaES($resultado["FechaTraslado"]);

        if (strlen($data["IdComprobanteVenta"]) > 0) {
          $dataComprobanteVenta["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
          $dataComprobanteVenta["GuiaRemision"] = $data["SerieDocumento"]."-".$data["NumeroDocumento"];
          $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($dataComprobanteVenta);
        }

        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarGuiaRemisionRemitente($data)
  {
    //REVERTIMOS LOS SALDOS DE GUIA REMISION
    $this->RevertirSaldosEnDetallesComprobanteVenta($data);
    
    $DetalleGuiaRemisionRemitente = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data);
    // $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
    // $data["FechaTraslado"] = convertToDate($data["FechaTraslado"]);
    $resultado = $this->mGuiaRemisionRemitente->BorrarGuiaRemisionRemitente($data);
    // print_r($resultado);exit;
    if (is_array($resultado)) {
      $detalle = $this->sDetalleGuiaRemisionRemitente->EliminarDetallesPorIdGuiaRemisionRemitente($data);
    }
    $resultado["DetallesGuiaRemisionRemitente"] = $DetalleGuiaRemisionRemitente;
    return $resultado;
  }

  function ActualizarEstadoGuiaRemisionRemitente($data)
  {
    $resultado = $this->mGuiaRemisionRemitente->ActualizarGuiaRemisionRemitente($data);
    return $resultado;
  }

  function ActualizarSerieDocumentoGuiaRemisionRemitente($data)
  {
    $IdGuiaRemisionRemitente = $data["IdGuiaRemisionRemitente"];
    $this->mGuiaRemisionRemitente->ActualizarGuiaRemisionRemitente($data);
    return "";
  }

  // function ValidarEstadoGuiaRemisionRemitente($data)
  // {
  //   $resultado="";

  //   if ($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ANULADO)
  //   {
  //     $resultado = $resultado."El comprobante no puede ser editado , anulado o eliminado porque esta ya anulado."."\n";
  //   }
  //   else if ($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ELIMINADO)
  //   {
  //      $resultado = $resultado."El comprobante no puede ser editado , anulado o eliminado porque esta ya eliminado."."\n";
  //   }
  //   else if($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ACTIVO)
  //   {
  //     if($data["IndicadorEstadoCPE"] == ESTADO_CPE_ACEPTADO || $data["IndicadorEstadoCPE"] == ESTADO_CPE_RECHAZADO)
  //     {
  //       $resultado = $resultado."El comprobante no puede ser editado , anulado o eliminado, porque fue aceptado o rechazado por SUNAT."."\n";
  //     }
  //   }

  //   return $resultado;
  // }

  function ValidarGuiaRemisionRemitente($data)
  {
    $resultado = "";
    $FechaEmisionMinimo = $this->ObtenerFechaEmisionMinimo();
    // print_r($FechaEmisionMinimo);exit;
    $resultado = $resultado . $this->ValidarCorrelativoDocumento($data);

    $validadoFechaEmision = true;

    if (strlen($data["FechaEmision"]) <= 0 || !validateDate($data["FechaEmision"], "Y-m-d")) {
      $resultado = $resultado . "La fecha de emision es incorrecta." . "\n";
      $validadoFechaEmision = false;
    } else {
      if (validateDateDiff($FechaEmisionMinimo, $data["FechaEmision"], "d") < 0) {
        $resultado = $resultado . "La fecha emision debe ser mayor a " . $FechaEmisionMinimo . "\n";
        $validadoFechaEmision = false;
      }
    }

    if (strlen($data["IdDestinatario"]) == 0) {
      $resultado = $resultado . "El Destinatario no se encuentra disponible en el sistema." . "\n";
    }

    if (strlen($data["IdTransportista"]) == 0) {
      $resultado = $resultado . "El Transportista no se encuentra disponible en el sistema." . "\n";
    }

    $validadoFechaTraslado = true;
    if (strlen($data["FechaTraslado"]) > 0) {

      if (!validateDate($data["FechaTraslado"], "Y-m-d")) {
        $resultado = $resultado . "La fecha de vencimiento es incorrecta." . "\n";
        $validadoFechaTraslado = false;
      }

      if (validateDateDiff($FechaEmisionMinimo, $data["FechaTraslado"], "d") < 0) {
        $resultado = $resultado . "La fecha vencimiento debe ser mayor a " . $FechaEmisionMinimo . "\n";
        $validadoFechaTraslado = false;
      }

      if ($validadoFechaEmision && $validadoFechaTraslado) {
        if (validateDateDiff($FechaEmisionMinimo, $data["FechaTraslado"], "d") < 0) {
          $resultado = $resultado . "La fecha vencimiento no debe ser menor a la fecha de emision" . "\n";
        }
      }

      if($data["IdModalidadTraslado"]  == 2) {//1 = Modalidad Publico, 2 = Modalidad Privado
        if (strlen($data["NumeroDocumentoIdentidadTransportista"]) == 2) {
          $resultado = $resultado . "En la modalidad de traslado privado debe ingresar el DNI del chofer de la empresa" . "\n";
        }
      }

      if($data["IdModalidadTraslado"]  == 1) {//1 = Modalidad Publico, 2 = Modalidad Privado
        if (strlen($data["NumeroDocumentoIdentidadTransportista"]) == 4) {
          $resultado = $resultado . "En la modalidad de traslado Público debe ingresar el RUC de la empresa de transporte" . "\n";
        }
      }

      // if($data["IdModalidadTraslado"]  == 2 ) {
      //   if (!ctype_alnum($data["PlacaVehiculo"])) {
      //     $resultado = $resultado . "El número de Placa solo debe tener letras y números (sin espacios ni comas)" . "\n";
      //   }
      // }
    }

    // $resultado = $resultado.$this->ValidarEstadoGuiaRemisionRemitente($data);
    $resultado_detalle = $this->sDetalleGuiaRemisionRemitente->ValidarDetallesGuiaRemisionRemitente($data["DetallesGuiaRemisionRemitente"]);

    $resultado = $resultado . $resultado_detalle;

    $resultadoLicencia = (LICENCIA_VENTA_FECHA_PERPETUA == 1) ? "" : $this->sLicencia->ValidarLicenciaPorVenta($data);
    if ($resultadoLicencia != "") $resultado = $resultado . $resultadoLicencia;

    return $resultado;
  }

  //PARA FACTURACION ELECTRONICA
  function ObtenerGuiaRemisionRemitente($data)
  {
    $output = $this->mGuiaRemisionRemitente->ConsultarGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data);
    $resultado = $output[0];
    $resultado["DetallesGuiaRemisionRemitente"] = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data);

    return $resultado;
  }

  function AnularGuiaRemisionRemitente($data)
  {
    try {
      $resultadoValidacion = "";
      if ($resultadoValidacion == "") {
        //REVERTIMOS LOS SALDOS DE GUIA REMISION
        $this->RevertirSaldosEnDetallesComprobanteVenta($data);

        $data["IndicadorEstado"] = ESTADO_DOCUMENTO_ANULADO;
        $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
        $data["FechaTraslado"] = convertToDate($data["FechaTraslado"]);
        $resultado = $this->ActualizarEstadoGuiaRemisionRemitente($data);
        $resultado["DetallesGuiaRemisionRemitente"] = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data);

        if (is_array($resultado)) {
          $detalle = $this->sDetalleGuiaRemisionRemitente->AnularDetallesPorIdGuiaRemisionRemitente($data);
        }

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaTraslado"] = convertirFechaES($resultado["FechaTraslado"]);
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  // function ImprimirGuiaRemisionRemitenteComoPDF($data)
  // {
  //   try {
  //     $parametros["IdGuiaRemisionRemitente"] = $data["IdGuiaRemisionRemitente"];
  //     $CodigoSerieSubTipo = substr($data["SerieDocumento"], 0, 2);
  //     $this->reporter->RutaReporte = RUTA_CARPETA_REPORTES . "Venta/FacturaElectronicaModelo01.jasper"; //RUTA_CARPETA_REPORTES."Venta/TicketFacturaElectronicaModelo02.jasper";
  //     $this->reporter->SetearParametros($parametros);
  //     $this->reporter->RutaPDF = RUTA_CARPETA_REPORTES_GENERADOS_PDF . $data["SerieDocumento"] . "-" . $data["NumeroDocumento"] . ".pdf";
  //     $resultado = $this->reporter->GenerarReporteComoPDF();
  //     if ($resultado == true) {
  //       $salida["archivo"] = $this->reporter->RutaPDF;
  //       $salida["impresora"] = "PDFCreator";
  //       $this->imprimir->ImprimirPDF($salida);
  //     }

  //     return "";
  //   } catch (Exception $e) {
  //     throw new Exception($e);
  //   }
  // }

  function ValidarCorrelativoDocumento($data)
  {
    $resultado = "";

    if (strlen($data["IdGuiaRemisionRemitente"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
      return $resultado;
    }

    if (strlen($data["NumeroDocumento"]) > 0 && !is_numeric($data["NumeroDocumento"])) {
      $resultado = $resultado . "El numero de documento debe ser mayor a cero y numérico." . "\n";
    } else {
      $output = $this->mGuiaRemisionRemitente->ConsultarGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data);

      if (count($output) > 0) //existe y es modificacion
      {
        $resultado2 = $output[0];
        if ($resultado2["IdTipoDocumento"] == $data["IdTipoDocumento"] && $resultado2["SerieDocumento"] == $data["SerieDocumento"]  && $resultado2["NumeroDocumento"] == $data["NumeroDocumento"]  && $resultado2["FechaEmision"] == $data["FechaEmision"]) {
          //$resultado = $resultado."NO hay cambios \n";
          return $resultado;
        }
      } else {
        $resultado3 = $this->mGuiaRemisionRemitente->ObtenerGuiaRemisionRemitentePorSerieDocumento($data);
        if ($resultado3 != null) {
          $resultado = $resultado . "El número de documento ya existe en otro comprobante de venta" . "\n";
          return $resultado;
        }
      }
    }

    $objeto1 = $this->mGuiaRemisionRemitente->ObtenerFechaMayor($data);
    $objeto2 = $this->mGuiaRemisionRemitente->ObtenerFechaMenor($data);
    $fechamayor = $objeto1->FechaEmisionMayor;
    $fechamenor = $objeto2->FechaEmisionMenor;

    if (strlen($fechamayor) != 0 && strlen($fechamenor) != 0) {
      if (!($data["FechaEmision"] >= $fechamenor && $data["FechaEmision"] <= $fechamayor))
        $resultado = $resultado . "La fecha emisión debe ser entre " . $fechamenor . " al " . $fechamayor . " \n";
    } elseif (strlen($fechamayor) != 0) {
      if (!($data["FechaEmision"] <= $fechamayor))
        $resultado = $resultado . "La fecha emisión debe ser menor o igual al " . $fechamayor . " \n";
    } elseif (strlen($fechamenor) != 0) {
      // if(!($data["FechaEmision"]>=$fechamenor))
      // $resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    } else {
      //$resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    }

    return $resultado;
  }

  function ObtenerMinimoMaximoFechaEmisionGuiaRemisionRemitente()
  {
    $resultado = $this->mGuiaRemisionRemitente->ObtenerMinimoMaximoFechaEmisionGuiaRemisionRemitente();
    return $resultado;
  }

  function ObtenerGuiasRemisionRemitentePorIdComprobanteVenta($data)
  {
    $resultado = $this->mGuiaRemisionRemitente->ObtenerGuiasRemisionRemitentePorIdComprobanteVenta($data);
    return $resultado;
  }

  function DescontarSaldosEnDetallesComprobanteVenta($data)
  {
    $resultado = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data, true);
    // print_r($resultado);exit;
    foreach ($resultado as $key => $value) {
      $detalle = $this->sDetalleComprobanteVenta->ConsultarDetalleComprobanteVentaPorId($value);
      if(count($detalle) > 0)
      {
        $detalle = $detalle[0];
        $detalle["SaldoPendienteGuiaRemision"] = $detalle["SaldoPendienteGuiaRemision"] - $value["Cantidad"];
        // print_r($detalle);exit;
        $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($detalle);
      }
    }
  }

  function RevertirSaldosEnDetallesComprobanteVenta($data)
  {
    $resultado = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data, true);
    foreach ($resultado as $key => $value) {
      $detalle = $this->sDetalleComprobanteVenta->ConsultarDetalleComprobanteVentaPorId($value);
      if(count($detalle) > 0)
      {
        $detalle = $detalle[0];
        $detalle["SaldoPendienteGuiaRemision"] = $detalle["SaldoPendienteGuiaRemision"] + $value["Cantidad"];
        $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($detalle);
      }
    }
  }

  //IMPRESION
  function ImprimirReporteGuiaRemisionRemitente($data) {
    try {
      $FormatoImpresion = $this->ObtenerFormatoImpresion();
      $parametros["IdGuiaRemisionRemitente"] = $data["IdGuiaRemisionRemitente"];
      $SerieDocumento=$data["SerieDocumento"];

      $printer = null;
      $rutaFormato = RUTA_CARPETA_REPORTES.NOMBRE_FACTURA_ELECTRONICO;
      $indicadorImpresion = INDICADOR_FORMATO_GUIA_REMISION;
   
      $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION."config-".$this->shared->GetDeviceName().".json";
      $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion,$SerieDocumento,$rutaplantilla);
      if($dataConfig != false) {
         $printer = $dataConfig["Printer"];
         $rutaFormato = RUTA_CARPETA_REPORTES.$dataConfig["Jasper"];
         $this->reporter->RutaReporte = $rutaFormato;
         $this->reporter->SetearParametros($parametros);
   
         $this->reporter->Imprimir($printer);   
       }

      return "";//$data
    }
    catch (Exception $e) {
      throw new Exception($e);
    }
  }
}
