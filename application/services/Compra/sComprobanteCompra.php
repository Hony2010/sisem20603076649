<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComprobanteCompra extends MY_Service {

  public $ComprobanteCompra = array();
  public $DetalleComprobanteCompra = array();

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
    $this->load->service('Compra/sDetalleComprobanteCompra');
    $this->load->service("Catalogo/sProveedor");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service("Configuracion/General/sTipoCambio");
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service("Configuracion/General/sFormaPago");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sSede");
    $this->load->service("Configuracion/General/sPeriodo");
    $this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
    $this->load->service('Catalogo/sProductoProveedor');
    $this->load->service('Catalogo/sMercaderia');
    $this->load->service('Seguridad/sAccesoUsuarioAlmacen');
    $this->load->service('Configuracion/General/sAsignacionSede');
    $this->load->service('Inventario/sDocumentoSalidaZofra');
    $this->load->service("Inventario/sMovimientoAlmacen");
    $this->load->service('Inventario/sDua');
    $this->load->model("Base");
    $this->load->model('Compra/mComprobanteCompra');
    $this->load->service('Seguridad/sAccesoCajaUsuario');

    $this->ComprobanteCompra = $this->mComprobanteCompra->ComprobanteCompra;
    $DetalleComprobanteCompra = [];
    $DetalleComprobanteCompra[] = $this->mDetalleComprobanteCompra->DetalleComprobanteCompra;
    $this->ComprobanteCompra["DetallesComprobanteCompra"] = $DetalleComprobanteCompra;
  }

  function Cargar($parametro)
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] =$this->sesionusuario->obtener_sesion_id_sede();
    $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);
    $this->ComprobanteCompra["CodigoSedeAgencia"] =$this->sesionusuario->obtener_sesion_codigo_sede();
    $this->ComprobanteCompra["NombreSedeAgencia"] =$this->sesionusuario->obtener_sesion_nombre_sede();
    $this->ComprobanteCompra["FechaEmision"] = $hoy;
    $this->ComprobanteCompra["FechaVencimiento"] = $hoy;
    $this->ComprobanteCompra["ValorTipoCambio"] = $TipoCambio == null ? "0.00" : $TipoCambio->TipoCambioCompra;
    $this->ComprobanteCompra["NumeroDocumentoIdentidad"] = "";
    $this->ComprobanteCompra["Direccion"] = "";
    $this->ComprobanteCompra["RazonSocial"] = "";
    $this->ComprobanteCompra["IdTipoDocumento"] = $parametro['IdTipoDocumento'];
    $this->ComprobanteCompra["IdTipoCompra"] = (string)ID_TIPOCOMPRA_MERCADERIA;
    $this->ComprobanteCompra["DescuentoGlobal"] = "0.00";
    $this->ComprobanteCompra["DescuentoTotalItem"] = 0.00;
    $this->ComprobanteCompra["ISC"] = 0.00;
    $this->ComprobanteCompra["OtroTributo"] = 0.00;
    $this->ComprobanteCompra["OtroCargo"] = 0.00;
    $this->ComprobanteCompra["DocumentoDetraccion"] = "";
    $this->ComprobanteCompra["CheckDetraccion"] = false;
    $this->ComprobanteCompra["NombreFormaPago"] = "";
    $this->ComprobanteCompra["NombreMoneda"] = "";
    $this->ComprobanteCompra["SimboloMoneda"] = "";
    $this->ComprobanteCompra["NombreAbreviado"] = "";
    $this->ComprobanteCompra["IdAsignacionSede"] = "";
    // $this->ComprobanteCompra["AliasUsuarioCompra"] = $this->sesionusuario->obtener_alias_usuario();
    $this->ComprobanteCompra["DetallesComprobanteCompra"]=array();//[0]=$this->sDetalleComprobanteCompra->Cargar();
    $this->ComprobanteCompra["IndicadorReferenciaCostoAgregado"] = array();
    $this->ComprobanteCompra["SerieDocumento"] = "";
    $this->ComprobanteCompra["IdSedeAlmacen"] = 0;
    $this->ComprobanteCompra["FechaMovimientoAlmacen"] = $hoy;
    $this->ComprobanteCompra["EstadoPendienteNota"] = "0";
    $data["FechaEmision"] = $this->Base->ObtenerFechaServidor("Y-m-d");
    $periodo = $this->sPeriodo->ObtenerPeriodoPorFecha($data);
    $this->ComprobanteCompra["IdPeriodo"] = $periodo[0]["IdPeriodo"];//"0";
    $this->ComprobanteCompra["IdSede"] =  $this->sesionusuario->obtener_sesion_id_sede();
    $this->ComprobanteCompra["NombreSedeAlmacen"] = $this->ComprobanteCompra["NombreSedeAgencia"];
    $this->ComprobanteCompra["CambiosFormulario"] = false;
    $this->ComprobanteCompra["ParametroCodigoProductoProveedor"] = $this->sConstanteSistema->ObtenerParametroCodigoProductoProveedor();
    $this->ComprobanteCompra["ParametroLote"] = $this->sConstanteSistema->ObtenerParametroLote();

    $this->ComprobanteCompra["ParametroDocumentoSalidaZofra"] = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
    $this->ComprobanteCompra["ParametroTipoDocumentoSalidaZofra"] = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
    $this->ComprobanteCompra["ParametroTipoDocumentoDuaAlternativo"] = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
    $this->ComprobanteCompra["CheckDocumentoSalidaZofra"] = false;
    $this->ComprobanteCompra["NumeroDocumentoSalidaZofra"] = "";
    $this->ComprobanteCompra["IdDocumentoIngresoZofra"] = "";
    $this->ComprobanteCompra["IdDocumentoSalidaZofra"] = "";
    $this->ComprobanteCompra["BloquearDocumentoZofra"] = false;
    $this->ComprobanteCompra["DocumentoIngreso"] = "";
    $this->ComprobanteCompra["FechaEmisionDocumentoIngreso"] = "";

    $this->ComprobanteCompra["MontoACuenta"] = "0.00";
    $this->ComprobanteCompra["ParametroCampoACuenta"] = $this->sConstanteSistema->ObtenerParametrosParaCampoACuenta();
    $this->ComprobanteCompra["TamanoSerieCompra"] = $this->sConstanteSistema->ObtenerParametroTamanoSerieCompra();
    $this->ComprobanteCompra["ParametroPrecioCompra"] = $this->sConstanteSistema->ObtenerParametroPrecioCompra();
    $this->ComprobanteCompra["ParametroRubroRepuesto"] = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    $this->ComprobanteCompra["IndicadorTipoCalculoIGV"] = $this->ComprobanteCompra["ParametroPrecioCompra"];
    //PARA PERCEPCION
    $this->ComprobanteCompra["TasaPercepcionPorcentaje"] = "0.00";
    $this->ComprobanteCompra["TasaPercepcion"] = "0.00";
    $this->ComprobanteCompra["MontoPercepcion"] = "0.00";
    $this->ComprobanteCompra["TotalMasPercepcion"] = "0.00";
    
    $this->ComprobanteCompra["ParametroDescuentoItem"] = "0";
    $this->ComprobanteCompra["ParametroDescuentoUnitario"] = "0";
    
    //PARA CAJA
    $Cajas = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario(); //Se listaran las cajas por usuario
    $this->ComprobanteCompra["Cajas"] = $Cajas;
    $this->ComprobanteCompra["IdCaja"] = (count($Cajas) > 0) ? $Cajas[0]["IdCaja"] : "";
    $this->ComprobanteCompra["FechaComprobante"] = $hoy;
    $this->ComprobanteCompra["ParametroCaja"] = $this->sConstanteSistema->ObtenerParametroCaja();
    
    //PARA BONIFICACION
    $this->ComprobanteCompra["ParametroBonificacion"] = $this->sConstanteSistema->ObtenerParametroBonificacion();

    $TasaIGV = $this->ObtenerTasaIGV();
    $FormasPago = $this->sFormaPago->ListarFormasPago();
    $Monedas = $this->sMoneda->ListarMonedas();
    $dataProveedor = $this->sProveedor->Cargar();
    $Periodos = $this->sPeriodo->ListarPeriodos();

    $Sedes=$this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario();//$this->sSede->ListarSedesTipoAlmacen();
    $data2["IdSede"] =  $this->ComprobanteCompra["IdSede"];
    $dataAsignacionSede=$this->sAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorIdSede($data2);
    if(count($Sedes) > 0) {      
      $this->ComprobanteCompra["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];
      //$this->ComprobanteCompra["IdAsignacionSede"] = $Sedes[0]["IdAsignacionSede"];
      // $this->ComprobanteCompra["IdSede"] = $Sedes[0]["IdSede"];
      // $this->ComprobanteCompra["NombreSedeAlmacen"] = $Sedes[0]["NombreSede"];      
    }

    $parametro["IdModuloSistema"] =ID_MODULO_COMPRA;
    $excluir = ID_TIPODOCUMENTO_NOTADEBITO.", ".ID_TIPODOCUMENTO_NOTACREDITO;
    $TiposDocumento = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($parametro,$excluir);

    $input["textofiltro"]='';
    $input["FechaInicio"]=$this->shared->obtener_primer_dia_mes();
    $input["FechaFin"]=$this->shared->obtener_ultimo_dia_mes();
    $input["FechaHoy"]=$this->Base->ObtenerFechaServidor("d/m/Y");
    $input["IdPersona"]=3;
    $input["IdTipoDocumento"]=3;
    $input["IdMoneda"]=3;
    $input["IdTipoVenta"]=1;

    $data =array(
      'NuevoDetalleComprobanteCompra'=>$this->sDetalleComprobanteCompra->Cargar(),
      'CopiaIdProductosDetalle'=>array(),
      'TasaIGV'=>$TasaIGV,
      'FormasPago'=>$FormasPago,
      'Monedas'=>$Monedas,
      'TipoCambio'=>$TipoCambio,
      'Sedes' => $Sedes,
      'CopiaSedes' => $Sedes,
      'Periodos'=>$Periodos,
      'TiposDocumento'=>$TiposDocumento,
      'FiltrosIngreso' => $input,
      'DocumentosIngreso' => array()
    );

    $resultado = array_merge($this->ComprobanteCompra,$data);

    return $resultado;
  }

  function CargarCatalogos() {
    $TasaIGV = $this->ObtenerTasaIGV();
    $FormasPago = $this->sFormaPago->ListarFormasPago();
    $Monedas = $this->sMoneda->ListarMonedas();
    $Sedes=$this->sSede->ListarSedesTipoAlmacen();

    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");
    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] =$this->sesionusuario->obtener_sesion_id_sede();
    $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);
    $resultado = array(
      'NuevoDetalleComprobanteCompra'=>$this->sDetalleComprobanteCompra->Cargar(),
      "TasaIGV" => $TasaIGV,
      "FormasPago" => $FormasPago,
      'Monedas'=>$Monedas,
      'TipoCambio'=>$TipoCambio,
      'Sedes' => $Sedes
    );

    return $resultado;
  }

  function ConsultarComprobanteCompraPorId($data)
  {
    $resultado = $this->mComprobanteCompra->ConsultarComprobanteCompraPorId($data);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
    $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
    $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = $resultado[$key]["FechaVencimiento"] == "" ? "" : convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaDetraccion"] =convertirFechaES($resultado[$key]["FechaDetraccion"]);
      $resultado[$key]["FechaMovimientoAlmacen"] =convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
      $resultado[$key]["BloquearDocumentoZofra"] = false;
      $resultado[$key]["NumeroDocumentoSalidaZofra"] = $item["DocumentoIngreso"];
      $resultado[$key]["CheckDocumentoSalidaZofra"] =false;

      if(($resultado[$key]["IdTipoDocumento"] == $parametro_tipodocumento_zofra || $resultado[$key]["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $resultado[$key]["IdTipoDocumento"] == $parametro_tipodocumento_dua_alternativo) && is_numeric($resultado[$key]["IdDocumentoIngresoZofra"]))
      {
        if($resultado[$key]["IdDocumentoIngresoZofra"] != 0)
        {
          $resultado[$key]["BloquearDocumentoZofra"] = true;
          $resultado[$key]["CheckDocumentoSalidaZofra"] =true;
        }
      }

      $resultado[$key]["DetallesComprobanteCompra"] =[];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;

      $indicador_referencia_costo_agregado = $this->mComprobanteCompra->ObtenerCompraMercaderiaEnDocumentoReferencia($item);
      $resultado[$key]["IndicadorReferenciaCostoAgregado"] = $indicador_referencia_costo_agregado;
    }

    return $resultado;
  }

  function ConsultarComprobantesCompra($data,$numeropagina,$numerofilasporpagina)
  {
      $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
      $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompra($data,$numerofilainicio,$numerofilasporpagina);
      $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

      $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
      $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
      $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
      foreach ($resultado as $key => $item) {        
        $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
        $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);        
        $resultado[$key]["FechaVencimiento"] =$resultado[$key]["FechaVencimiento"] == "" ? "" : convertirFechaES($resultado[$key]["FechaVencimiento"]);
        $resultado[$key]["FechaDetraccion"] =convertirFechaES($resultado[$key]["FechaDetraccion"]);
        $resultado[$key]["FechaMovimientoAlmacen"] =convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
        $resultado[$key]["BloquearDocumentoZofra"] = false;
        $resultado[$key]["NumeroDocumentoSalidaZofra"] = $item["DocumentoIngreso"];
        $resultado[$key]["CheckDocumentoSalidaZofra"] =false;
        $resultado[$key]["TasaPercepcionPorcentaje"] = $resultado[$key]["TasaPercepcion"] * 100;
        
        if(($resultado[$key]["IdTipoDocumento"] == $parametro_tipodocumento_zofra || $resultado[$key]["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $resultado[$key]["IdTipoDocumento"] == $parametro_tipodocumento_dua_alternativo) && is_numeric($resultado[$key]["IdDocumentoIngresoZofra"]))
        {
          if($resultado[$key]["IdDocumentoIngresoZofra"] != 0)
          {
            $resultado[$key]["BloquearDocumentoZofra"] = true;
            $resultado[$key]["CheckDocumentoSalidaZofra"] =true;
          }
        }

        $resultado[$key]["DetallesComprobanteCompra"] =[];
        $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
        $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;

        $indicador_referencia_costo_agregado = $this->mComprobanteCompra->ObtenerCompraMercaderiaEnDocumentoReferencia($item);
        $resultado[$key]["IndicadorReferenciaCostoAgregado"] = $indicador_referencia_costo_agregado;
      }

      return $resultado;
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_COMPROBANTECOMPRA;
    $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina=$parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalComprobantesCompra($data)
  {
      $resultado = $this->mComprobanteCompra->ObtenerNumeroTotalComprobantesCompra($data);
      return $resultado;
  }

  function ConsultarComprobantesCompraPorProveedorParaCostoAgregado($data)
  {
      $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompraPorProveedorParaCostoAgregado($data);

      foreach ($resultado as $key => $item) {
        $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
        $resultado[$key]["FechaVencimiento"] =$resultado[$key]["FechaVencimiento"] == "" ? "" : convertirFechaES($resultado[$key]["FechaVencimiento"]);
        $resultado[$key]["DetallesComprobanteCompra"] =$this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);
      }

      return $resultado;
  }

  function ConsultarComprobantesCompraPorProveedor($data)
  {
      $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompraPorProveedor($data);

      foreach ($resultado as $key => $item) {
        $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
        $resultado[$key]["FechaVencimiento"] =$resultado[$key]["FechaVencimiento"] == "" ? "" : convertirFechaES($resultado[$key]["FechaVencimiento"]);
        $resultado[$key]["DetallesComprobanteCompra"] =$this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);
      }

      return $resultado;
  }

  function ConsultarComprobantesCompraPorProveedorParaNotaCredito($data)
  {
      $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompraPorProveedorParaNotaCredito($data);

      foreach ($resultado as $key => $item) {
        $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
        $resultado[$key]["FechaVencimiento"] =convertirFechaES($resultado[$key]["FechaVencimiento"]);
        $resultado[$key]["DiferenciaSaldo"] = $resultado[$key]["Total"] - $resultado[$key]["SaldoNotaCredito"];
        $resultado[$key]["DetallesComprobanteCompra"] =$this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);
      }

      return $resultado;
  }

  function ConsultarComprobantesCompraPendienteNotaPorProveedor($data)
  {
      $resultado = $this->mComprobanteCompra->ConsultarComprobantesCompraPendienteNotaPorProveedor($data);

      foreach ($resultado as $key => $item) {
        $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
        $resultado[$key]["FechaVencimiento"] =convertirFechaES($resultado[$key]["FechaVencimiento"]);
        // $resultado[$key]["DetallesComprobanteCompra"] =[];
        $resultado[$key]["DetallesComprobanteCompra"] =$this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);
      }

      return $resultado;
  }

  function BuscarDocumentosIngreso($data)
  {
      $resultado = $this->mComprobanteCompra->BuscarDocumentosIngreso($data);
      $response = array();
      foreach ($resultado as $key => $item) {
        $resultado[$key]["FechaEmisionCopia"] = $item["FechaEmision"];
        $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
        $resultado[$key]["FechaVencimiento"] =convertirFechaES($resultado[$key]["FechaVencimiento"]);

        $item["IdSede"] = $item["IdAsignacionSede"];
        $resultado[$key]["DetallesComprobanteCompra"] =$this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);

        $total = 0;
        foreach ($resultado[$key]["DetallesComprobanteCompra"] as $key2 => $value2) {
          $total += $value2["SaldoDocumentoIngreso"];
        }
        if($total > 0)
        {
          array_push($response, $resultado[$key]);
        }
      }

      return $response;
  }

  function ListarCompras()
  {
    $resultado = $this->mComprobanteCompra->ListarCompras();

    foreach ($resultado as $key => $item) {
      $resultado_detalle =  $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);// $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);
      $resultado[$key]["DetallesComprobanteCompra"] =$resultado_detalle;
      //$NuevoDetalle=$this->sDetalleComprobanteCompra->Cargar();
      //$resultado[$key]['NuevoDetalleComprobanteCompra']=$NuevoDetalle;
    }

    return $resultado;
  }

  function ValidarDuplicadoDeComprobanteCompra($data)
  {
    $meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","");

    $resultado = $this->mComprobanteCompra->ObtenerDuplicadoDeComprobanteCompra($data);
    $FechaEmision = $resultado[0]['FechaEmision'];
    $fecha = substr($FechaEmision, 0, 10);
    $tempdate = explode('-',$fecha);

    if (count($resultado) > 0)
    {
      return "Este Comprobante se emitio en el mes: ".$meses[(int)$tempdate[01]]. " y año: " .$tempdate[0];
    }
    else
    {
      return"";
    }
  }

  function FechaEmision($data)
  {
    $fecha = $data["FechaCambio"];
    $tempdate = explode('/',$fecha);
    $nuevafecha = ($tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0]);
    return $nuevafecha;

  }

  function ObtenerFechaEmisionMinimo()
  {
    $data['IdParametroSistema']= ID_PARAMETRO_FECHA_EMISION_MINIMO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if(is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerAtributosComprobanteCompra()
  {
    $data['IdGrupoParametro']= ID_ATRIBUTO_COMPROBANTE_COMPRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      return $resultado;
    }
  }

  function ObtenerTasaIGV()
  {
    $data['IdParametroSistema']= ID_TASA_IGV;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }

  }

  function ValidarTotalComprobanteCompra($data)
  {
    $total = $data["Total"];

    if ($total == "" )
    {
      return "El total de compra debe ser completado";
    }
    else
    {
      return "";
    }
  }

  function ValidarIgvComprobanteCompra($data)
  {
    $igv = $data["IGV"];

    if ($igv == "" )
    {
      return "El IGV de compra debe ser completado";
    }
    else
    {
      return "";
    }
  }

  function ValidarFechaVencimientoComprobanteCompra($data)
  {
    $fechavencimiento = $data["FechaVencimiento"];

    if ($igv == "" )
    {
      return "LA FechaVencimiento de la compra debe ser completado";
    }
    else
    {
      return "";
    }
  }

  function ValidarCantidad($data)
  {
    $cantidad=$data["Cantidad"];

    if ($cantidad == "")
    {
      return "ingresar la cantidad del producto";
    }
    else
    {
      return "";
    }
  }

  function ValidarPrecioUnitario($data)
  {
    $precio=$data["PrecioUnitario"];

    if ($precio == "")
    {
      return "ingresar el precio del producto";
    }
    else
    {
      return "";
    }
  }

  function ValidarDescuentoItem($data)
  {
    $descuento=$data["Descuento"];

    if ($descuento == "")
    {
      return "ingresar el descuento del producto";
    }
    else
    {
      return "";
    }
  }

  function ValidarSubTotal($data)
  {
    $subtotal=$data["SubTotal"];

    if ($subtotal == "")
    {
      return "ingresar el sub total del producto";
    }
    else
    {
      return "";
    }
  }

  function CalcularSubTotal($data)
  {
    $cantidad = $this-> ValidarCantidad($data);
    $precio = $this-> ValidarPrecioUnitario($data);
    $descuento= $this->ValidarDescuentoItem($data);

    if ($cantidad != "")
    {
      return $cantidad;
    }
    else if ($precio != "")
    {
      return $precio;
    }
    else if ($descuento !="")
    {
      return $descuento;
    }
    else
    {
      $subtotal = ($cantidad * $precio) - $descuento;
      return $subtotal;
    }
  }

  function ValidarExistenciaDuplicadoParaInsertar($data)
  {
    $resultado = $this->mComprobanteCompra->ObtenerDuplicadoParaInsertar($data);

    if (count($resultado)>0)
    {
      return "Este número de documento y serie para este tipo de documento ya fue registrado con el mismo proveedor.";
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaDuplicadoParaActualizar($data)
  {
    $resultado = $this->mComprobanteCompra->ObtenerDuplicadoParaActualizar($data);
    if (Count($resultado)>0)
    {
      return "Este número de documento y serie para este tipo de documento ya fue registrado con el mismo proveedor.";
    }
    else
    {
      return "";
    }
  }

  function ValidarReferenciasParaDocumentoIngreso($data)
  {
    $resultado = $this->mComprobanteCompra->ObtenerReferenciasParaDocumentoIngreso($data);
    if (Count($resultado)>0)
    {
      $mensaje = "Este comprobante tiene referencias, no puede ser alterado. Sus referencias son:";
        foreach ($resultado as $key => $value) {
          $mensaje .= "<br>- ".$value["NombreAbreviado"]." ".$value["SerieDocumento"]."-".$value["NumeroDocumento"]."&nbsp;&nbsp;&nbsp; Fecha Emisión: ".convertirFechaES($value["FechaEmision"]);
        }
      return $mensaje;
    }
    else
    {
      return "";
    }
  }

  function ValidarComprobanteEnReferenciaCompra($data)
  {
    $resultado = $this->mComprobanteCompra->ObtenerDocumentosReferenciaCompraPorComprobante($data);
    if (Count($resultado)>0)
    {
      $mensaje = "Este comprobante tiene referencias, no puede ser alterado. Sus referencias son:";
        foreach ($resultado as $key => $value) {
          $mensaje .= "<br>- ".$value["NombreAbreviado"]." ".$value["SerieDocumento"]."-".$value["NumeroDocumento"]."&nbsp;&nbsp;&nbsp; Fecha Emisión: ".convertirFechaES($value["FechaEmision"]);
        }
      return $mensaje;
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaCompraMercaderiaEnDocumentoReferencia($data,$operacion)
  {
    $input["IdComprobanteCompra"] = $data["IdComprobanteCompra"];
    $resultado = $this->mComprobanteCompra->ObtenerCompraMercaderiaEnDocumentoReferencia($input);
    $mensaje = "Para poder ".$operacion." este comprobante usted debera modificar o eliminar sus documentos relacionados:<br>";
    if (Count($resultado)>0)
    {
      foreach ($resultado as $key => $value) {
        $mensaje .= '- '.$value['NombreAbreviado'].' '.$value['SerieDocumento'].'-'.$value['NumeroDocumento'].' '.convertirFechaES($value['FechaEmision']).'<br>';
      }
      return $mensaje;
    }
    else
    {
      return "";
    }
  }

  function ValidarPeriodoRegistro($data)
  {
    $periodo = $this->sPeriodo->ListarPeriodoPorId($data);
    $año = $periodo[0]['Año'];
    $mes = $periodo[0]['NumeroMes'];
    $dia = days_in_month($mes,$año);
    $fechaperiodo = $año.'-'.$mes.'-'.$dia;
    $fechaemision = $data['FechaEmision'];

    if ($fechaemision > $fechaperiodo) {
      return "La fecha de emision no puede ser mayor al periodo de registro";
    }
    else {
      return "";
    }
  }

  function ValidarCorrelativoNotaEntrada($data)
  {
    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    if(count($asignacionsede) <= 0)
    {
      return "El usuario no tiene una sede asignada.";
    }

    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTAENTRADA;
    $parametro['IdSedeAgencia'] = $asignacionsede[0]["IdSede"];
    $resultado = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);

    if (count($resultado)<=0)
    {
      return "Por favor ingrese una Serie de Nota de Entrada para dicho almacen.";
    }
    else
    {
      return "";
    }
  }

  function InsertarComprobanteCompra($data) {
    try {
      $data["FechaEmision"]=convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);
      $data["FechaDetraccion"]=convertToDate($data["FechaDetraccion"]);
      $data["FechaMovimientoAlmacen"]=convertToDate($data["FechaMovimientoAlmacen"]);
      $parametroProductoProveedor = $this->sConstanteSistema->ObtenerParametroCodigoProductoProveedor();
      if ($parametroProductoProveedor = 1 && $data["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA) {
        $codigoproductoproveedor = $this->sProductoProveedor->ValidarProductoProveedor($data);
      } else {
        $codigoproductoproveedor = "";
      }
      $resultadoValidacion = $this->ValidarComprobanteCompra($data);
      $duplicado = $this->ValidarExistenciaDuplicadoParaInsertar($data);
      $periodo = $this->ValidarPeriodoRegistro($data);
      $correlativo = $data["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA ? $this->ValidarCorrelativoNotaEntrada($data) : "";

      if($data["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE && $correlativo != "") {
        return $correlativo;
      }
      else if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if ($duplicado != "")
      {
        return $duplicado;
      }
      else if ($periodo != "") {
        return $periodo;
      }
      else if ($codigoproductoproveedor != "") {
        return $codigoproductoproveedor;
      }
      else if($resultadoValidacion == "")
      {
        $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
        $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
        if($parametro_zofra == 1 && $data["IdTipoDocumento"] == $parametro_tipodocumento_zofra)
        {
          $response = $this->sDocumentoSalidaZofra->AgregarDocumentoSalidaZofra($data);

          $data["IdDocumentoSalidaZofra"] = $response["IdDocumentoSalidaZofra"];
        }

        $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
        $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
        if($parametro_dua == 1 && ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $parametro_tipodocumento_dua_alternativo))
        {
          $response = $this->sDua->AgregarDua($data);
          $data["IdDua"] = $response["IdDua"];
        }

        if(is_string($data["MontoACuenta"])){$data["MontoACuenta"] = str_replace(',',"",$data["MontoACuenta"]);}
        if(is_string($data["ValorCompraGravado"])){$data["ValorCompraGravado"] = str_replace(',',"",$data["ValorCompraGravado"]);}
        if(is_string($data["ValorCompraNoGravado"])){$data["ValorCompraNoGravado"] = str_replace(',',"",$data["ValorCompraNoGravado"]);}        
        if ($data["ValorCompraInafecto"] == "" || $data["ValorCompraInafecto"]== null)  $data["ValorCompraInafecto"] = 0.00;
        if(is_string($data["ValorCompraInafecto"])){$data["ValorCompraInafecto"] = str_replace(',',"",$data["ValorCompraInafecto"]);}
        if(is_string($data["Total"])){$data["Total"] = str_replace(',',"",$data["Total"]);}
        $data["SaldoNotaCredito"] = $data["Total"];
        $resultado= $this->mComprobanteCompra->InsertarComprobanteCompra($data);
        $IdComprobanteCompra = $resultado["IdComprobanteCompra"];
        
        $this->sDetalleComprobanteCompra->CargarComprobanteCompra($data);
        
        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO) {
          if(array_key_exists("DetallesComprobanteCompra", $data)) {
            $resultado["DetallesComprobanteCompra"] = $this->sDetalleComprobanteCompra->InsertarDetallesComprobanteCompra($IdComprobanteCompra, $data["DetallesComprobanteCompra"]);//, $data["IdAsignacionSede"], $data["IdTipoCompra"], $resultado
          }
        }
        else{
          $resultado["DetallesComprobanteCompra"] = $this->sDetalleComprobanteCompra->InsertarDetallesComprobanteCompra($IdComprobanteCompra, $data["DetallesComprobanteCompra"]);//, $data["IdAsignacionSede"], $data["IdTipoCompra"], $resultado
        }

        $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
        $resultado["CheckDocumentoSalidaZofra"] = $data["CheckDocumentoSalidaZofra"];
        $resultado["NumeroDocumentoSalidaZofra"] = $data["NumeroDocumentoSalidaZofra"];

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

  function ActualizarComprobanteCompra($data)
  {
    try {
      $data["FechaEmision"]=convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);
      $data["FechaDetraccion"]=convertToDate($data["FechaDetraccion"]);
      $data["FechaMovimientoAlmacen"]=convertToDate($data["FechaMovimientoAlmacen"]);

      $parametroProductoProveedor = $this->sConstanteSistema->ObtenerParametroCodigoProductoProveedor();
      if ($parametroProductoProveedor = 1 && $data["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA) {
        $codigoproductoproveedor = $this->sProductoProveedor->ValidarProductoProveedor($data);
      } else {
        $codigoproductoproveedor = "";
      }

      $documentoingreso = $this->ValidarReferenciasParaDocumentoIngreso($data);
      $documentoreferenciacompra = $this->ValidarComprobanteEnReferenciaCompra($data);

      $resultadoValidacion = $this->ValidarComprobanteCompra($data);
      $duplicado = $this->ValidarExistenciaDuplicadoParaActualizar($data);
      $operacion = "actualizar";
      $documentoReferencia = $this->ValidarExistenciaCompraMercaderiaEnDocumentoReferencia($data,$operacion);
      $periodo = $this->ValidarPeriodoRegistro($data);
      // $correlativo = $this->ValidarCorrelativoNotaEntrada($data);
      $correlativo = $data["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA ? $this->ValidarCorrelativoNotaEntrada($data) : "";
      if($data["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE && $correlativo != "") {
        return $correlativo;
      }
      else if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if ($duplicado != "" )
      {
          return $duplicado;
      }
      else if ($documentoingreso != "" )
      {
          return $documentoingreso;
      }
      else if ($documentoreferenciacompra != "" )
      {
          return $documentoreferenciacompra;
      }
      else if ($documentoReferencia != "" )
      {
         return $documentoReferencia;
      }
      else if ($periodo != "") {
        return $periodo;
      }
      else if ($codigoproductoproveedor != "") {
        return $codigoproductoproveedor;
      }
      else if($resultadoValidacion == "")
      {
        $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
        $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
        if($parametro_zofra == 1 && $data["IdTipoDocumento"] == $parametro_tipodocumento_zofra)
        {
          $response = $this->sDocumentoSalidaZofra->AgregarDocumentoSalidaZofra($data);
          $data["IdDocumentoSalidaZofra"] = $response["IdDocumentoSalidaZofra"];
        }
        
        $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
        $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
        if($parametro_dua == 1 && ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $parametro_tipodocumento_dua_alternativo))
        {
          $response = $this->sDua->AgregarDua($data);
          $data["IdDua"] = $response["IdDua"];
        }

        if(is_string($data["MontoACuenta"])){$data["MontoACuenta"] = str_replace(',',"",$data["MontoACuenta"]);}
        $data["SaldoNotaCredito"] = $data["Total"];
        $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($data);
        $IdComprobanteCompra =$data["IdComprobanteCompra"];
        if(array_key_exists('IndicadorReferenciaCostoAgregado', $data))
        {
          $data["DetallesComprobanteCompra"]["IndicadorReferenciaCostoAgregado"] = $data["IndicadorReferenciaCostoAgregado"];
        }
        else {
          $data["DetallesComprobanteCompra"]["IndicadorReferenciaCostoAgregado"] = '0';
        }
        
        $this->sDetalleComprobanteCompra->CargarComprobanteCompra($data);
        //, $data["IdAsignacionSede"], $data["IdTipoCompra"], $resultado
        $resultado["DetallesComprobanteCompra"] = $this->sDetalleComprobanteCompra->ActualizarDetallesComprobanteCompra($IdComprobanteCompra, $data["DetallesComprobanteCompra"]);//, $data["IdAsignacionSede"], $data["IdTipoCompra"], $resultado);
        $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
        $resultado["FechaMovimientoAlmacen"] =convertirFechaES($resultado["FechaMovimientoAlmacen"]);
        $resultado["FechaDetraccion"] =convertirFechaES($resultado["FechaDetraccion"]);

        $resultado["CheckDocumentoSalidaZofra"] = $data["CheckDocumentoSalidaZofra"];
        $resultado["NumeroDocumentoSalidaZofra"] = $data["NumeroDocumentoSalidaZofra"];
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

  function ActualizarComprobanteCompraAlternativo($data)
  {
    try {
      $resultadoValidacion = "";//$this->ValidarComprobanteCompra($data);
      $duplicado = $this->ValidarExistenciaDuplicadoParaActualizar($data);
      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if ($duplicado != "" )
      {
          return $duplicado;
      }
      else if($resultadoValidacion == "")
      {
        $dataOtra["IdComprobanteCompra"] = $data["IdComprobanteCompra"]; 
        $dataOtra["SerieDocumento"] = $data["SerieDocumento"];
        $dataOtra["NumeroDocumento"] = $data["NumeroDocumento"];
        $dataOtra["Observacion"] = $data["Observacion"];
        $dataOtra["IdTipoDocumento"] = $data["IdTipoDocumento"];
        $dataOtra["IdTipoCompra"] = $data["IdTipoCompra"];
        $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($dataOtra);

        $response = array_merge($data, $resultado);
        return $response;
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

  function BorrarComprobanteCompra($data)
  {
    $operacion = "borrar";
    $documentoReferencia = $this->ValidarExistenciaCompraMercaderiaEnDocumentoReferencia($data,$operacion);
    $documentoingreso = $this->ValidarReferenciasParaDocumentoIngreso($data);
    $documentoreferenciacompra = $this->ValidarComprobanteEnReferenciaCompra($data);

    if ($documentoReferencia != "" )
    {
        return $documentoReferencia;
    }
    else if ($documentoingreso != "" )
    {
        return $documentoingreso;
    }
    else if ($documentoreferenciacompra != "" )
    {
        return $documentoreferenciacompra;
    }
    else {
      $DetalleComprobanteCompra = $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);

      if ($data['IdTipoCompra'] = ID_TIPOCOMPRA_COSTOAGREGADO) {
        $this->sMovimientoAlmacen->DescontarParaActualizarMovimientosAlmacenCostoAgregado($data);
        $this->sDocumentoReferenciaCostoAgregado->BorrarDocumentoReferenciaPorIdComprobanteCostoAgregado($data["IdComprobanteCompra"]);
      }
      $resultado = $this->mComprobanteCompra->BorrarComprobanteCompra($data);
      if (is_array($resultado)) {
        $detalle = $this->sDetalleComprobanteCompra->EliminarDetallesPorIdComprobanteCompra($data);
      }
      $resultado['DetallesComprobanteCompra'] = $DetalleComprobanteCompra;
      return $resultado;
    }
  }

  function ActualizarEstadoComprobanteCompra($data)
  {
      $resultado=$this->mComprobanteCompra->ActualizarComprobanteCompra($data);
      return $resultado;
  }

  function ActualizarSerieDocumentoComprobanteCompra($data)
  {
      $IdComprobanteCompra =$data["IdComprobanteCompra"];
      $this->mComprobanteCompra->ActualizarComprobanteCompra($data);
      return "";
  }


  function ValidarEstadoComprobanteCompra($data)
  {
    $resultado="";

    if ($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ELIMINADO)
    {
        $resultado = $resultado."El comprobante no puede ser editado , anulado o eliminado porque esta ya eliminado."."\n";
    }
    /*else if($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ACTIVO)
    {
          $resultado = $resultado."El comprobante no puede ser editado , anulado o eliminado."."\n";
    }*/

    return $resultado;
  }

  function ValidarComprobanteCompra($data)
  {
    $resultado="";
    $FechaEmisionMinimo = $this->ObtenerFechaEmisionMinimo();

    if ($data["IdMoneda"] != ID_MONEDA_SOLES)
    {
      if($data["ValorTipoCambio"]<=0 || !is_numeric($data["ValorTipoCambio"]))
      {
          $resultado = $resultado."El tipo de cambio de documento debe ser mayor a cero y numérico."."\n";
      }
    }

    $validadoFechaEmision = true;

    if(strlen($data["FechaEmision"]) <= 0 || !validateDate($data["FechaEmision"],"Y-m-d"))
    {
        $resultado = $resultado."La fecha de emision es incorrecta."."\n";
        $validadoFechaEmision = false;
    }
    else
    {
      if(validateDateDiff($FechaEmisionMinimo,$data["FechaEmision"],"d") < 0)
      {
        $resultado = $resultado."La fecha emision debe ser mayor a ".$FechaEmisionMinimo."\n";
        $validadoFechaEmision = false;
      }
    }

    if(strlen($data["IdProveedor"]) == 0)
    {
      $resultado = $resultado."No se han encontrado resultados para tu búsqueda de Proveedor."."\n";
    }
    $data["DescuentoGlobal"] = (is_string($data["DescuentoGlobal"])) ? str_replace(',',"",$data["DescuentoGlobal"]) : $data["DescuentoGlobal"];
    if ($data["DescuentoGlobal"] < 0 || !is_numeric($data["DescuentoGlobal"]))
    {
      $resultado =$resultado."El descuento global debe ser mayor o igual que cero y numerico."."\n";
    }

    $validadoFechaVencimiento = true;
    if($data["IdFormaPago"] == ID_FORMA_PAGO_CREDITO)
    {
      if( strlen($data["FechaVencimiento"]) <= 0 || !validateDate($data["FechaVencimiento"],"Y-m-d") )
      {
          $resultado = $resultado."La fecha de vencimiento es incorrecta y obligatoria en cuando la forma de pago es al crédito."."\n";
          $validadoFechaVencimiento = false;
      }
      else
      {
        if(validateDateDiff($FechaEmisionMinimo,$data["FechaVencimiento"],"d") < 0)
        {
            $resultado = $resultado."La fecha vencimiento debe ser mayor a ".$FechaEmisionMinimo."\n";
            $validadoFechaVencimiento = false;
        }

        if ($validadoFechaEmision && $validadoFechaVencimiento)
        {
          if(validateDateDiff($FechaEmisionMinimo,$data["FechaVencimiento"],"d") < 0)
          {
              $resultado = $resultado."La fecha vencimiento no debe ser menor a la fecha de emision"."\n";
          }
        }
      }
    }
    else
    {
      if(strlen($data["FechaVencimiento"])> 0)
      {

        if(!validateDate($data["FechaVencimiento"],"Y-m-d"))
        {
          $resultado = $resultado."La fecha de vencimiento es incorrecta."."\n";
          $validadoFechaVencimiento = false;
        }

        if(validateDateDiff($FechaEmisionMinimo,$data["FechaVencimiento"],"d") < 0)
        {
            $resultado = $resultado."La fecha vencimiento debe ser mayor a ".$FechaEmisionMinimo."\n";
            $validadoFechaVencimiento = false;
        }

        if ($validadoFechaEmision && $validadoFechaVencimiento)
        {
          if(validateDateDiff($FechaEmisionMinimo,$data["FechaVencimiento"],"d") < 0)
          {
              $resultado = $resultado."La fecha vencimiento no debe ser menor a la fecha de emision"."\n";
          }
        }
      }
    }

    $resultado = $resultado.$this->ValidarEstadoComprobanteCompra($data);
    $resultado_detalle = "";

    if($data["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA) //$data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO ||
    {
      $resultado_detalle = $this->sDetalleComprobanteCompra->ValidarDetallesComprobanteCompra($data["DetallesComprobanteCompra"]);
    }

    $resultado =$resultado.$resultado_detalle;

    return $resultado;
  }

  function ObtenerComprobanteCompra($data)
  {
    $output = $this->mComprobanteCompra->ObtenerComprobanteCompra($data);
    $resultado=$output[0];
    $resultado["DetallesComprobanteCompra"] = $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);
    return $resultado;
  }

  function ObtenerComprobanteCompraPorIdComprobante($data)
  {
    $output = $this->mComprobanteCompra->ObtenerComprobanteCompra($data);
    $resultado=$output[0];
    return $resultado;
  }

  function ActualizarDetalleComprobanteCompra($data) {
    return $this->sDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($data);
  }

}
