<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComprobanteVenta extends MY_Service {

  public $ComprobanteVenta = array();
  public $DetalleComprobanteVenta = array();
  public $ComprobanteVentaTransporte = array();

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
    $this->load->service('Venta/sDetalleComprobanteVenta');
    $this->load->service('Venta/sGuiaRemisionRemitente');
    $this->load->service("Catalogo/sCliente");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service("Configuracion/General/sTipoCambio");
    $this->load->service("Configuracion/General/sEmpresa");
    $this->load->service("Configuracion/General/sPeriodo");
    $this->load->service("Configuracion/Catalogo/sDireccionCliente");
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service('Configuracion/Venta/sPeriodoTasaImpuestoBolsa');
    $this->load->service("Configuracion/General/sFormaPago");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/Venta/sTipoTarjeta");
    $this->load->service("Configuracion/General/sMedioPago");    
    $this->load->service("Configuracion/General/sLugarDestino");
    $this->load->service("Configuracion/General/sSede");
    $this->load->service('Configuracion/General/sAsignacionSede');
    $this->load->service("Configuracion/Venta/sConfiguracionVenta");
    $this->load->service("Configuracion/General/sSituacionComprobanteElectronico");
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service('Configuracion/General/sTipoDocumento');
    $this->load->service('Catalogo/sAlumno');
    $this->load->service('Seguridad/sAccesoUsuarioAlmacen');
    $this->load->service("Configuracion/Venta/sTipoTarjeta");
    $this->load->model("Base");
    $this->load->model('Venta/mComprobanteVenta');
    $this->load->service("Venta/sComprobanteVentaTransporte");
    $this->load->service("Venta/sProformaComprobanteVenta");    
    $this->load->service("Seguridad/sLicencia");
    $this->load->service("Seguridad/sUsuario");
    $this->load->service('Seguridad/sAccesoCajaUsuario');
    $this->load->service("Venta/sCuotaPagoClienteComprobanteVenta");
    // $this->load->service('Catalogo/sRadioTaxi');

    $this->ComprobanteVenta = $this->mComprobanteVenta->ComprobanteVenta;
    $DetalleComprobanteVenta = [];
    $DetalleComprobanteVenta[] = $this->mDetalleComprobanteVenta->DetalleComprobanteVenta;
    $this->ComprobanteVenta["DetallesComprobanteVenta"] = $DetalleComprobanteVenta;
    $this->ComprobanteVentaTransporte = $this->sComprobanteVentaTransporte->ComprobanteVentaTransporte;
    $this->ComprobanteVenta = $this->herencia->Heredar($this->ComprobanteVentaTransporte,$this->ComprobanteVenta);
    
  }

  function Cargar($parametro) {
    
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");
    $dataUsuario["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();    
    $resultadoUsuario = $this->sUsuario->ObtenerUsuario($dataUsuario);
    
    $resultadoTipoDocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($parametro);
    $this->ComprobanteVenta["IndicadorPermisoEliminarComprobanteVenta"] = $resultadoUsuario[0]["IndicadorPermisoEliminarComprobanteVenta"];
    $this->ComprobanteVenta["IndicadorPermisoAnularComprobanteVenta"] = $resultadoUsuario[0]["IndicadorPermisoAnularComprobanteVenta"];
    $this->ComprobanteVenta["IndicadorPermisoEditarComprobanteVenta"] = $resultadoUsuario[0]["IndicadorPermisoEditarComprobanteVenta"];
    $this->ComprobanteVenta["IndicadorEditarCampoPrecioUnitarioVenta"] = $resultadoUsuario[0]["IndicadorEditarPrecioUnitarioVenta"];
    $this->ComprobanteVenta["IndicadorPermisoCobranzaRapida"] = $resultadoUsuario[0]["IndicadorPermisoCobranzaRapida"];
    $this->ComprobanteVenta["IndicadorPermisoStockNegativo"] = $resultadoUsuario[0]["IndicadorPermisoStockNegativo"];
    $this->ComprobanteVenta["IndicadorCrearProducto"] = $resultadoUsuario[0]["IndicadorCrearProducto"];    
    $this->ComprobanteVenta["IndicadorEnvioAutomaticoSUNAT"] = count($resultadoTipoDocumento) > 0 ? $resultadoTipoDocumento[0]["IndicadorEnvioAutomaticoSUNAT"] : "0";
    $this->ComprobanteVenta["CodigoTipoDocumento"] = count($resultadoTipoDocumento) > 0 ? $resultadoTipoDocumento[0]["CodigoTipoDocumento"] : "";
    $this->ComprobanteVenta["CodigoDocumentoIdentidad"] = "";
    $this->ComprobanteVenta["SerieDocumentoReferencia"] = "";
    $this->ComprobanteVenta["NumeroDocumentoReferencia"] = "";
    $this->ComprobanteVenta["CodigoTipoDocumentoReferencia"] = "";
    $this->ComprobanteVenta["CodigoTipoLugarEntrega"] = "0";
    $this->ComprobanteVenta["DestinatarioGuiaEnvio"] = "";
    $this->ComprobanteVenta["DireccionDestinatarioGuiaEnvio"] = "";
    $this->ComprobanteVenta["CelularDestinatario"] = "";
    $this->ComprobanteVenta["ModoBusquedaProducto"] = "1"; //1 -- basica, 2 -- avanzanda
    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] = $this->sesionusuario->obtener_sesion_id_sede();
    $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);

    $this->ComprobanteVenta["IdMoneda"] = ID_MONEDA_SOLES;

    if (array_key_exists("IdSubTipoDocumento", $parametro)) {
      $this->ComprobanteVenta["IdSubTipoDocumento"] = $parametro["IdSubTipoDocumento"];
      $this->ComprobanteVenta["IdMoneda"] = $this->sConstanteSistema->ObtenerParametroIdMonedaBoletaZofra();      
    }
    else {
      $this->ComprobanteVenta["IdSubTipoDocumento"] = "";
    }
    $this->ComprobanteVenta["CodigoSedeAgencia"] =$this->sesionusuario->obtener_sesion_codigo_sede();
    $this->ComprobanteVenta["NombreSedeAgencia"] =$this->sesionusuario->obtener_sesion_nombre_sede();
    $this->ComprobanteVenta["FechaEmision"] = $hoy;
    $this->ComprobanteVenta["FechaVencimiento"] = $hoy;
    $this->ComprobanteVenta["ValorTipoCambio"] = $TipoCambio == null ? "0.00" : $TipoCambio->TipoCambioVenta;
    $this->ComprobanteVenta["NumeroDocumentoIdentidad"] = "";
    $this->ComprobanteVenta["Direccion"] = "";
    $this->ComprobanteVenta["RazonSocial"] = "";
    $this->ComprobanteVenta["IdCorrelativoDocumento"] = ID_CORRELATIVO_DOCUMENTO_PRINCIPAL;
    $this->ComprobanteVenta["IdTipoDocumento"] = $parametro['IdTipoDocumento'];

    if (array_key_exists("IdTipoVenta", $parametro)) {
        $this->ComprobanteVenta["IdTipoVenta"] = $parametro["IdTipoVenta"];
    }
    else {
      $this->ComprobanteVenta["IdTipoVenta"] = TIPO_VENTA_MERCADERIA;
    }
    $this->ComprobanteVenta["ParametroCalculoSolesDolares"] = $this->sConstanteSistema->ObtenerParametroCalculoPrecioSolesDolares();
    $this->ComprobanteVenta["IdTipoOperacion"] = ID_TIPO_OPERACION_VENTA_INTERNA;
    $this->ComprobanteVenta["DescuentoGlobal"] = "0.00";
    $this->ComprobanteVenta["DescuentoTotalItem"] = 0.00;
    $this->ComprobanteVenta["ISC"] = 0.00;
    $this->ComprobanteVenta["OtroTributo"] = 0.00;
    $this->ComprobanteVenta["OtroCargo"] = 0.00;
    $this->ComprobanteVenta["ValorVentaInafecto"] = 0.00;
    $this->ComprobanteVenta["IndicadorDocumentoReferencia"] = "0";
    $this->ComprobanteVenta["NombreFormaPago"] = "";
    $this->ComprobanteVenta["IdFormaPago"] = ID_FORMA_PAGO_CONTADO;
    $this->ComprobanteVenta["NombreFormaPagoSUNAT"] = "Contado";
    $this->ComprobanteVenta["NombreMoneda"] = "SOLES";
    $this->ComprobanteVenta["CodigoMoneda"] = "PEN";
    $this->ComprobanteVenta["SimboloMoneda"] = "";
    $this->ComprobanteVenta["NombreAbreviado"] = "";
    $this->ComprobanteVenta["CodigoEstado"] = CODIGO_ESTADO_EMITIDO;
    $this->ComprobanteVenta["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    $this->ComprobanteVenta["DetallesComprobanteVenta"] = array();
    $this->ComprobanteVenta["ParametroLote"] = $this->sConstanteSistema->ObtenerParametroLote();
    $this->ComprobanteVenta["ParametroDua"] = $this->sConstanteSistema->ObtenerParametroDua();
    $this->ComprobanteVenta["ParametroListaVendedor"] = $this->sConstanteSistema->ObtenerParametroListaVendedor();
    $this->ComprobanteVenta["ParametroAdministrador"] = $this->sesionusuario->obtener_sesion_vista_combo_venta_usuario();
    $this->ComprobanteVenta["ParametroEnvioEmail"] = $this->sConstanteSistema->ObtenerParametroEnvioEmail();
    $this->ComprobanteVenta["IdUsuarioActivo"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $this->ComprobanteVenta["VerTodasVentas"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $this->ComprobanteVenta["IdRolUsuario"] = $this->sesionusuario->obtener_sesion_id_rol();
    if ($this->ComprobanteVenta["IdRolUsuario"] ==  5) { //provisional : para que los almaceneros elijan vendedores      
      $this->ComprobanteVenta["ParametroAdministrador"] = "1";
    }
    $this->ComprobanteVenta["ParametroTipoCambio"] = $this->sConstanteSistema->ObtenerParametroTipoCambioBusquedaAvanzadaProducto();
    $this->ComprobanteVenta["ParametroMargenUtilidad"] = $this->sConstanteSistema->ObtenerParametroMargenUtilidadBusquedaAvanzadaProducto();
    $this->ComprobanteVenta["ParametroSauna"] = $this->sConstanteSistema->ObtenerParametroSauna();
    $this->ComprobanteVenta["ParametroSeleccionUnaProformaVentas"] = $this->sConstanteSistema->ObtenerParametroSeleccionUnaProformaVentas();
    $this->ComprobanteVenta["IdGeneroAnterior"] = "";
    $this->ComprobanteVenta["IdCasilleroAnterior"] = "";
    $this->ComprobanteVenta["IdGenero"] = "";
    $this->ComprobanteVenta["IdCasillero"] = "";
    $this->ComprobanteVenta["NombreGenero"] = "";
    $this->ComprobanteVenta["NombreCasillero"] = "";
    $this->ComprobanteVenta["NombreGeneroAnterior"] = "";
    $this->ComprobanteVenta["NombreCasilleroAnterior"] = "";
    $this->ComprobanteVenta["IndicadorCasilleroDisponible"] = 0;
    $dataVendedor["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();
    $Vendedores = $this->sUsuario->ListarUsuariosPorSede($dataVendedor);

    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);

    $dataConfigCorrelativo = $this->json->ObtenerConfigCorrelativo($this->sesionusuario->obtener_sesion_id_usuario(), $parametro);

    $this->ComprobanteVenta["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : "";
    $this->ComprobanteVenta["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : "";

    if($dataConfigCorrelativo != false) {
      $busqueda = $this->BuscarCorrelativoEnSeries($SeriesDocumento, $dataConfigCorrelativo);
      if($busqueda != false)
      {
        $this->ComprobanteVenta["IdCorrelativoDocumento"] = $busqueda["IdCorrelativoDocumento"];
        $this->ComprobanteVenta["SerieDocumento"] = $busqueda["SerieDocumento"];
      }
    }
    
    $this->ComprobanteVenta["IdSedeAlmacen"] = 1;
    $this->ComprobanteVenta["FechaMovimientoAlmacen"] = $hoy;
    $this->ComprobanteVenta["EstadoPendienteNota"] = "0";
    $this->ComprobanteVenta["IdSede"] = $data["IdSedeAgencia"];
    $this->ComprobanteVenta["NombreSedeAlmacen"] = $this->ComprobanteVenta["NombreSedeAgencia"];
    $this->ComprobanteVenta["TasaISC"] = 0.00;
    $this->ComprobanteVenta["SituacionCPE"] = "";
    $this->ComprobanteVenta["AbreviaturaSituacionCPE"] = "";
    $this->ComprobanteVenta["IdClientesVarios"] = ID_CLIENTES_VARIOS;
    $this->ComprobanteVenta["CambiosFormulario"] = false;

    $this->ComprobanteVenta["ParametroComprobantesAutomaticos"] = $this->sConstanteSistema->ObtenerParametrosComprobantesAutomaticos();
    $this->ComprobanteVenta["ComprobantesAutomaticos"] = [];
    $this->ComprobanteVenta["OpcionComprobantesAutomaticos"] = false;
    $this->ComprobanteVenta["CantidadComprobantesAutomaticos"] = $this->sConstanteSistema->ObtenerParametrosComprobantesAutomaticos();
    $this->ComprobanteVenta["ParametroMaxComprobantesAutomaticos"] = $this->sConstanteSistema->ObtenerParametrosMaxComprobantesAutomaticos();

    $this->ComprobanteVenta["MontoEnvioGestion"] = "0.00";
    $this->ComprobanteVenta["TotalConEnvioGestion"] = "0.00";
    $this->ComprobanteVenta["MontoACuenta"] = "0.00";
    $this->ComprobanteVenta["ParametroCampoACuenta"] = $this->sConstanteSistema->ObtenerParametrosParaCampoACuenta();
    $this->ComprobanteVenta["ParametroCampoCampoMontoPendienteVenta"] = $this->sConstanteSistema->ObtenerParametrosParaCampoMontoPendienteVenta();
    $this->ComprobanteVenta["ParametroCamposConEnvioYGestion"] = $this->sConfiguracionVenta->ObtenerParametrosParaCamposConEnvioYGestion();
    $this->ComprobanteVenta["ParametroDua"] = $this->sConstanteSistema->ObtenerParametroDua();
    $this->ComprobanteVenta["ParametroDescuentoUnitario"] = $this->sConstanteSistema->ObtenerParametroDescuentoUnitarioVenta();
    $this->ComprobanteVenta["ParametroDescuentoItem"] = $this->sConstanteSistema->ObtenerParametroDescuentoItemVenta();
    $this->ComprobanteVenta["ParametroStockProductoVenta"] = $this->sConstanteSistema->ObtenerParametroStockProductoVenta();
    $this->ComprobanteVenta["ParametroCantidadCaja"] = $this->sConstanteSistema->ObtenerParametrosParaCantidadCaja();
    $this->ComprobanteVenta["ParametroTransporte"] = $this->sConstanteSistema->ObtenerParametroTranporte();
    $this->ComprobanteVenta["ParametroTransporteMercancia"] = $this->sConstanteSistema->ObtenerParametroTransporteMercancia();
    $this->ComprobanteVenta["ParametroAlumno"] = $this->sConstanteSistema->ObtenerParametroAlumno();
    $this->ComprobanteVenta["ParametroBannerTipoVenta"] = $this->sConstanteSistema->ObtenerParametroBannerTipoVenta();
    $this->ComprobanteVenta["ParametroObservacionDetalle"] = $this->sConstanteSistema->ObtenerParametroObservacionDetalle();
    $this->ComprobanteVenta["ParametroVistaVenta"] = $this->sConstanteSistema->ObtenerParametroVistaVenta();
    $this->ComprobanteVenta["ParametroMarcaVenta"] = $this->sConstanteSistema->ObtenerParametroMarcaVenta();
    $this->ComprobanteVenta["ParametroCodigoBarras"] = $this->sConstanteSistema->ObtenerParametroCodigoBarras();
    $this->ComprobanteVenta["ParametroCalcularCantidad"] = $this->sConstanteSistema->ObtenerParametroCalcularCantidad();

    $this->ComprobanteVenta["ParametroFiltroClienteSinRuc"] = $this->sConstanteSistema->ObtenerParametroClienteSinRuc();
    $this->ComprobanteVenta["ParametroMostrarCampoMontoRecibido"] = $this->sConstanteSistema->ObtenerParametroMostrarCampoMontoRecibido();
    $this->ComprobanteVenta["ParametroVistaPreviaImpresion"] = $this->sesionusuario->obtener_sesion_indicador_vista_previa_impresion();
    $this->ComprobanteVenta["IndicadorVistaPrecioMinimo"] = $this->sesionusuario->obtener_sesion_indicador_vista_precio_minimo();
    //PuntoVenta
    $this->ComprobanteVenta["MontoRecibido"] = "0.00";
    $this->ComprobanteVenta["VueltoRecibido"] = "0.00";
    $this->ComprobanteVenta['IdTipoDocumentoOrdenPedido'] = ID_TIPO_DOCUMENTO_ORDEN_PEDIDO;
    $this->ComprobanteVenta['IdTipoDocumentoBoleta'] = ID_TIPO_DOCUMENTO_BOLETA;
    $this->ComprobanteVenta['IdTipoDocumentoFactura'] = ID_TIPO_DOCUMENTO_FACTURA;
    $this->ComprobanteVenta['IdTipoDocumentoTicket'] = ID_TIPO_DOCUMENTO_TICKET;
    
    $this->ComprobanteVenta["ParametroGuardarClienteVenta"] = $this->sConstanteSistema->ObtenerParametroGuardarClienteVenta();
    $this->ComprobanteVenta["ParametroGuardarProductoVenta"] = $this->sConstanteSistema->ObtenerParametroGuardarProductoVenta();
    
    $this->ComprobanteVenta["ParametroOrdenPedidoDua"] = $this->sConstanteSistema->ObtenerParametroOrdenPedidoDua();

    $this->ComprobanteVenta["ParametroCalculoIGVDesdeTotal"] = $this->sConstanteSistema->ObtenerParametroCalculoIGVDesdeTotal();
    $this->ComprobanteVenta["ParametroBonificacion"] = $this->sConstanteSistema->ObtenerParametroBonificacion();
    $this->ComprobanteVenta["ParametroRubroLubricante"] = $this->sConstanteSistema->ObtenerParametroRubroLubricante();
    $this->ComprobanteVenta["ParametroRubroTransporte"] = $this->sConstanteSistema->ObtenerParametroRubroTransporte();
    $this->ComprobanteVenta["ParametroRubroRepuesto"] = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    $this->ComprobanteVenta["ParametroRubroClinica"] = $this->sConstanteSistema->ObtenerParametroRubroClinica();
    $this->ComprobanteVenta["ParametroProforma"] = $this->sConstanteSistema->ObtenerParametroProforma();
    $this->ComprobanteVenta["ParametroFormaCalculoVenta"] = $this->sConstanteSistema->ObtenerParametroFormaCalculoVenta();
    
    $this->ComprobanteVenta["IdRadioTaxi"] = "";
    $this->ComprobanteVenta["IndicadorAmPm"] = "1";
    $this->ComprobanteVenta["Observacion"] = "";
    $this->ComprobanteVenta["NombreRadioTaxi"] = "";
    $this->ComprobanteVenta["IndicadorBoletaViaje"] = 0;

    $this->ComprobanteVenta["RazonSocialDestinatario"] = "";
    if (array_key_exists("IdTipoVenta", $parametro)) {
      $this->ComprobanteVenta["TipoVentaDefecto"] = $parametro["IdTipoVenta"];
    } else {
      $this->ComprobanteVenta["TipoVentaDefecto"] = $this->sConstanteSistema->ObtenerParametroTipoVentaPorDefecto();
    }

    $this->ComprobanteVenta["CheckDestinatario"] = 0;
    $this->ComprobanteVenta["KilometrajeVehiculo"] = 0;
    $this->ComprobanteVenta["MontoComision"] = 0;
    $this->ComprobanteVenta["PorcentajeComision"] = 0;
    $this->ComprobanteVenta["DocumentoVentaReferencia"] = "";
    $this->ComprobanteVenta["DocumentoVentaProforma"] = "";
    $this->ComprobanteVenta["FechaExpedicion"] = "";

    $this->ComprobanteVenta["IdAlumno"] = "";
    $this->ComprobanteVenta["IdGradoAlumno"] = "";
    $this->ComprobanteVenta["CodigoAlumno"] = "";

    $this->ComprobanteVenta["IdMedioPago"] = "0";

    $MediosPago = $this->sMedioPago->ListarMediosPago();

    if ($this->ComprobanteVenta["ParametroAlumno"] == 1) {
      $Motivos = json_decode(file_get_contents(BASE_URL() . 'assets/data/venta/motivodescuentoalumno.json'), true);
    } else {
      $Motivos = "";
    }

    //PARA CAJA
    $Cajas = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario(); //Se listaran las cajas por usuario
    $this->ComprobanteVenta["Cajas"] = $Cajas;
    $this->ComprobanteVenta["IdCaja"] = (count($Cajas) > 0) ? $Cajas[0]["IdCaja"] : "";
    $this->ComprobanteVenta["FechaComprobante"] = $hoy;
    $this->ComprobanteVenta["ParametroCaja"] = $this->sConstanteSistema->ObtenerParametroCaja();

    //INVENTARIO
    $parametroInventario['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTASALIDA;
    $parametroInventario['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $SeriesNotaSalida = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametroInventario);
    $this->ComprobanteVenta["SeriesNotaSalida"] = $SeriesNotaSalida;
    $this->ComprobanteVenta["SerieNotaSalida"] = "";
    $this->ComprobanteVenta["NumeroNotaSalida"] = "";
    $this->ComprobanteVenta["IdNotaSalida"] = "";
    $this->ComprobanteVenta["ICBPER"] = 0;

    $this->ComprobanteVenta["VigenciaPresupuesto"] = "";
    $this->ComprobanteVenta["PlazoEntrega"] = "";
    $this->ComprobanteVenta["FechaEntrega"] = "";
    $this->ComprobanteVenta["ParametroDatosCotizacion"] = "0";//$this->sConstanteSistema->ObtenerParametroDatosCotizacion();
    //DIRECCIONES
    $this->ComprobanteVenta["IdDireccionCliente"] = "";
    $this->ComprobanteVenta["DireccionCliente"] = $this->sDireccionCliente->DireccionCliente;
    $this->ComprobanteVenta["DireccionesCliente"] = array();
    $this->ComprobanteVenta["DireccionParametroCliente"] = $this->sConstanteSistema->ObtenerParametroDireccionCliente();
    //PROFORMAS
    $this->ComprobanteVenta["ProformasComprobanteVenta"] = array();
    $this->ComprobanteVenta["CuotasPagoClienteComprobanteVenta"] = array();
    $this->ComprobanteVenta["NuevaCuotaPagoClienteComprobanteVenta"] = $this->sCuotaPagoClienteComprobanteVenta->CuotaPagoClienteComprobanteVenta;

    $this->ComprobanteVenta["ParametroNumeroDetraccionBancoNacion"] = $this->sConstanteSistema->ObtenerParametroNumeroDetraccionBancoNacion();
    $this->ComprobanteVenta["ParametroCodigoBienProductoDetraccionSUNAT"] = $this->sConstanteSistema->ObtenerParametroCodigoBienProductoDetraccionSUNAT();
    $this->ComprobanteVenta["ParametroPorcentajeDetraccion"] = $this->sConstanteSistema->ObtenerParametroPorcentajeDetraccion();

    $this->ComprobanteVenta["ParametroRetencionIGV"] = $this->sConstanteSistema->ObtenerParametroRetencionIGV();
    $this->ComprobanteVenta["ParametroPorcentajeRetencionIGV"] = $this->sConstanteSistema->ObtenerParametroPorcentajeRetencionIGV();
    $this->ComprobanteVenta["ParametroDetraccion"] = $this->sConstanteSistema->ObtenerParametroDetraccion();

    $this->ComprobanteVenta["EstadoDetraccion"] = false;
    $this->ComprobanteVenta["PorcentajeDetraccion"] = "0.00";
    $this->ComprobanteVenta["MontoDetraccion"] = "0.00";

    $this->ComprobanteVenta["EstadoRetencionIGV"] = false;
    $this->ComprobanteVenta["BaseImponibleRetencionIGV"] = 0.00;
    $this->ComprobanteVenta["PorcentajeRetencionIGV"] =  0.00;
    $this->ComprobanteVenta["MontoRetencionIGV"] = 0.00;

    $LugaresDestinos = $this->sLugarDestino->ListarLugaresDestinos();
    $TasaIGV = $this->ObtenerTasaIGV();
    $FormasPago = $this->sFormaPago->ListarFormasPago();
    $Monedas = $this->sMoneda->ListarMonedas();
    $TiposTarjeta = $this->sTipoTarjeta->ListarTiposTarjeta();
    $dataCliente = $this->sCliente->Cargar();
    $Sedes = $this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario($parametro); //$this->sSede->ListarSedesTipoAlmacen();
    $Alumnos = $this->sAlumno->ListarTodosAlumnos();
    // $RadiosTaxi=$this->sRadioTaxi->ListarRadioTaxis();
    $PeriodosTasaImpuestoBolsa = $this->sPeriodoTasaImpuestoBolsa->ListarPeriodosTasaImpuestoBolsa();
    $data2["IdSede"] = $this->ComprobanteVenta["IdSede"];
    $dataAsignacionSede = $this->sAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorIdSede($data2);

    $this->ComprobanteVenta["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];
    $this->ComprobanteVenta["NombreSede"] = $dataAsignacionSede[0]["NombreSede"];



    $this->ComprobanteVenta["ParametroAplicaPrecioEspecial"] = $this->sConstanteSistema->ObtenerParametroAplicaPrecioEspecial();
    $this->ComprobanteVenta["IdTipoListaPrecioEspecial"] = "";
    if (count($Sedes) > 0) {
      //$this->ComprobanteVenta["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];
      //$this->ComprobanteVenta["IdAsignacionSede"] = $Sedes[0]["IdAsignacionSede"];
      //$this->ComprobanteVenta["IdSede"] = $Sedes[0]["IdSede"];
      //$this->ComprobanteVenta["NombreSedeAlmacen"] = $Sedes[0]["NombreSede"];      
    }

    $MostrarCampos = $this->sConfiguracionVenta->ObtenerCamposVenta();

    $data = array(
      'NuevoDetalleComprobanteVenta' => $this->sDetalleComprobanteVenta->Cargar(),
      'CopiaIdProductosDetalle' => array(),
      'TasaIGV' => $TasaIGV,
      'SeriesDocumento' => $SeriesDocumento,
      'FormasPago' => $FormasPago,
      'Monedas' => $Monedas,
      'TiposTarjeta' => $TiposTarjeta,
      'Motivos' => $Motivos,
      'TipoCambio' => $TipoCambio,
      'MostrarCampos' => $MostrarCampos,
      'Sedes' => $Sedes,
      // 'RadiosTaxi' => $RadiosTaxi,
      'LugaresDestinos' => $LugaresDestinos,
      'Alumnos' => $Alumnos,
      'CopiaSedes' => $Sedes,
      'Vendedores' => $Vendedores,
      'PeriodosTasaImpuestoBolsa' => $PeriodosTasaImpuestoBolsa,
      'Vehiculos' => array(),
      'MediosPago' => $MediosPago
    );

    $resultado = array_merge($this->ComprobanteVenta, $data);

    return $resultado;
  }

  function BuscarCorrelativoEnSeries($data, $config)
  {
    $response = false;
    foreach ($data as $key => $value) {
      if ($config["IdCorrelativoDocumento"] == $value["IdCorrelativoDocumento"]) {
        $response = $value;
      }
    }
    return $response;
  }

  function CargarCatalogos() {
    $TasaIGV = $this->ObtenerTasaIGV();
    $FormasPago = $this->sFormaPago->ListarFormasPago();
    $Monedas = $this->sMoneda->ListarMonedas();
    $TiposTarjeta = $this->sTipoTarjeta->ListarTiposTarjeta();
    $Sedes = $this->sSede->ListarSedesTipoAlmacen();

    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");
    $data["FechaCambio"] = $hoy;
    $data["IdSedeAgencia"] = $this->sesionusuario->obtener_sesion_id_sede();
    $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);
    $resultado = array(
      'NuevoDetalleComprobanteVenta' => $this->sDetalleComprobanteVenta->Cargar(),
      "TasaIGV" => $TasaIGV,
      "FormasPago" => $FormasPago,
      'Monedas' => $Monedas,
      'TiposTarjeta' => $TiposTarjeta,
      'TipoCambio' => $TipoCambio,
      'Sedes' => $Sedes
    );

    return $resultado;
  }

  function ConsultarComprobanteVentaPorId($data)
  {
    $resultado = $this->mComprobanteVenta->ConsultarComprobanteVentaPorId($data);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaMovimientoAlmacen"] = convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
      $resultado[$key]["DetallesComprobanteVenta"] = [];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $SituacionCPE = $this->sSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($item["SituacionCPE"]);
      if ($SituacionCPE != null) {
        $resultado[$key]["AbreviaturaSituacionCPE"] = $SituacionCPE->AbreviaturaSituacionComprobanteElectronicoVentas;
      } else {
        $resultado[$key]["AbreviaturaSituacionCPE"] = "";
      }
    }

    return $resultado;
  }

  function ConsultarComprobantesVenta($data,$numeropagina,$numerofilasporpagina) {
    $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
    $parametrosauna=$this->sConstanteSistema->ObtenerParametroSauna();
    $parametroTransporteMercancia = $this->sConstanteSistema->ObtenerParametroTransporteMercancia();
    $ParametroMostrarCampoMontoRecibido = $this->sConstanteSistema->ObtenerParametroMostrarCampoMontoRecibido();
    $ParametroAplicaPrecioEspecial = $this->sConstanteSistema->ObtenerParametroAplicaPrecioEspecial();

    $dataUsuario["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();    
    $resultadoUsuario = $this->sUsuario->ObtenerUsuario($dataUsuario);
    $DireccionParametroCliente = $this->sConstanteSistema->ObtenerParametroDireccionCliente();
    $ParametroNumeroDetraccionBancoNacion = $this->sConstanteSistema->ObtenerParametroNumeroDetraccionBancoNacion();
    $ParametroCodigoBienProductoDetraccionSUNAT = $this->sConstanteSistema->ObtenerParametroCodigoBienProductoDetraccionSUNAT();
    $ParametroPorcentajeDetraccion = $this->sConstanteSistema->ObtenerParametroPorcentajeDetraccion();
    $ParametroRetencionIGV = $this->sConstanteSistema->ObtenerParametroRetencionIGV();
    $ParametroPorcentajeRetencionIGV = $this->sConstanteSistema->ObtenerParametroPorcentajeRetencionIGV();
    if ($parametrosauna == 0) {
      unset($data["IdGenero"]);
    }

    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVenta($data, $numerofilainicio, $numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    
    foreach ($resultado as $key => $item) {
      $resultado[$key]["IndicadorEditarCampoPrecioUnitarioVenta"] = $resultadoUsuario[0]["IndicadorEditarPrecioUnitarioVenta"];
      $resultado[$key]["IndicadorPermisoEliminarComprobanteVenta"] = $resultadoUsuario[0]["IndicadorPermisoEliminarComprobanteVenta"];
      $resultado[$key]["IndicadorPermisoAnularComprobanteVenta"] = $resultadoUsuario[0]["IndicadorPermisoAnularComprobanteVenta"];
      $resultado[$key]["IndicadorPermisoEditarComprobanteVenta"] = $resultadoUsuario[0]["IndicadorPermisoEditarComprobanteVenta"];
      $resultado[$key]["DireccionParametroCliente"] = $DireccionParametroCliente;
      $resultado[$key]["IndicadorCrearProducto"] = $resultadoUsuario[0]["IndicadorCrearProducto"];
      $resultadoTipoDocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($resultado[$key]);
      $resultado[$key]["IndicadorEnvioAutomaticoSUNAT"] = count($resultadoTipoDocumento) > 0 ? $resultadoTipoDocumento[0]["IndicadorEnvioAutomaticoSUNAT"] : "0";
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaEntrega"] = convertirFechaES($resultado[$key]["FechaEntrega"]);      
      $resultado[$key]["FechaMovimientoAlmacen"] = convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
      $resultado[$key]["FechaExpedicion"] = convertirFechaES($resultado[$key]["FechaExpedicion"]);
      $resultado[$key]["DetallesComprobanteVenta"] = [];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $resultado[$key]["IdUsuarioActivo"] = $this->sesionusuario->obtener_sesion_id_usuario();
      $resultado[$key]["VerTodasVentas"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
      $resultado[$key]["IdRolUsuario"] = $this->sesionusuario->obtener_sesion_id_rol();
      $resultado[$key]["ParametroCampoACuenta"] = $this->sConstanteSistema->ObtenerParametrosParaCampoACuenta();
      $resultado[$key]["ParametroCamposConEnvioYGestion"] = $this->sConfiguracionVenta->ObtenerParametrosParaCamposConEnvioYGestion();
      $resultado[$key]["ParametroVistaPreviaImpresion"] = $this->sesionusuario->obtener_sesion_indicador_vista_previa_impresion();
      $resultado[$key]["IndicadorVistaPrecioMinimo"] = $this->sesionusuario->obtener_sesion_indicador_vista_precio_minimo();
      $resultado[$key]["TipoVentaDefecto"] = TIPO_VENTA_SERVICIOS;
      $resultado[$key]["FechaEmisionAnterior"] = $resultado[$key]["FechaEmision"];
      $resultado[$key]["AliasUsuarioVentaAnterior"] = $resultado[$key]["AliasUsuarioVenta"];
      $resultado[$key]["ParametroSauna"] = $parametrosauna;
      $resultado[$key]["IdGeneroAnterior"] = $resultado[$key]["IdGenero"];
      $resultado[$key]["IdCasilleroAnterior"] = $resultado[$key]["IdCasillero"];
      $resultado[$key]["NombreGeneroAnterior"] = $resultado[$key]["NombreGenero"];
      $resultado[$key]["NombreCasilleroAnterior"] = $resultado[$key]["NombreCasillero"];
      $resultado[$key]["ParametroTransporteMercancia"] = $parametroTransporteMercancia;
      $resultado[$key]["ParametroMostrarCampoMontoRecibido"] = $ParametroMostrarCampoMontoRecibido;
      $resultado[$key]["ParametroNumeroDetraccionBancoNacion"] = $ParametroNumeroDetraccionBancoNacion;
      $resultado[$key]["ParametroCodigoBienProductoDetraccionSUNAT"] = $ParametroCodigoBienProductoDetraccionSUNAT;
      $resultado[$key]["ParametroRetencionIGV"] = $ParametroRetencionIGV;
      $resultado[$key]["ParametroPorcentajeDetraccion"] = $ParametroPorcentajeDetraccion;
      $resultado[$key]["EstadoDetraccion"] = $item["EstadoDetraccion"] == "1" ? true : false;
      $resultado[$key]["ParametroPorcentajeRetencionIGV"] = $ParametroPorcentajeRetencionIGV;
      $resultado[$key]["EstadoRetencionIGV"] = $item["EstadoRetencionIGV"] == "1" ? true : false;

      $SituacionCPE = $this->sSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($item["SituacionCPE"]);
      if ($SituacionCPE != null) {
        $resultado[$key]["AbreviaturaSituacionCPE"] = $SituacionCPE->AbreviaturaSituacionComprobanteElectronicoVentas;
      } else {
        $resultado[$key]["AbreviaturaSituacionCPE"] = "";
      }

      $item["IdPersona"]=$item["IdCliente"];
      $resultado[$key]["DireccionesCliente"] =  $this->sDireccionCliente->ConsultarDireccionesCliente($item);
      $resultado[$key]["ProformasComprobanteVenta"] = $this->sProformaComprobanteVenta->ListarProformasComprobanteVenta($resultado[$key]);
      $resultado[$key]["ParametroAplicaPrecioEspecial"] = $ParametroAplicaPrecioEspecial;//$this->sConstanteSistema->ObtenerParametroAplicaPrecioEspecial();
      $resultado[$key]["CuotasPagoClienteComprobanteVenta"] = $this->sCuotaPagoClienteComprobanteVenta->ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($item);
      $resultado[$key]["NuevaCuotaPagoClienteComprobanteVenta"] = $this->sCuotaPagoClienteComprobanteVenta->CuotaPagoClienteComprobanteVenta;
    }

    return $resultado;
  }

  function ConsultarComprobantesVentaCFC($data)
  {
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaCFC($data);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $resultado[$key]["CodigoMotivoComprobanteFisicoContingencia"] = "";
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaMovimientoAlmacen"] = convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
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

  function ObtenerNumeroTotalComprobantesVenta($data)
  {
    $parametrosauna = $this->sConstanteSistema->ObtenerParametroSauna();
    if ($parametrosauna == 0) {
      unset($data["IdGenero"]);
    }

    $resultado = $this->mComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($data);
    return $resultado;
  }

  function ConsultarComprobantesVentaPorCliente($data)
  {
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaPorCliente($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaMovimientoAlmacen"] = convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
      $resultado[$key]["DiferenciaSaldo"] = $resultado[$key]["Total"] - $resultado[$key]["SaldoNotaCredito"];
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($item);
    }

    return $resultado;
  }

  function ConsultarComprobantesVentaPorClienteParaDebito($data)
  {
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaPorClienteParaDebito($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaMovimientoAlmacen"] = convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($item);
    }

    return $resultado;
  }

  function ConsultarComprobantesVentaPendienteNotaPorCliente($data)
  {
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaPendienteNotaPorCliente($data);

    foreach ($resultado as $key => $item) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["FechaMovimientoAlmacen"] = convertirFechaES($resultado[$key]["FechaMovimientoAlmacen"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($item);
    }

    return $resultado;
  }

  function ListarVentas()
  {
    $resultado = $this->mComprobanteVenta->ListarVentas();

    foreach ($resultado as $key => $item) {
      $resultado_detalle =  $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($item); // $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($item);
      $resultado[$key]["DetallesComprobanteVenta"] = $resultado_detalle;
      //$NuevoDetalle=$this->sDetalleComprobanteVenta->Cargar();
      //$resultado[$key]['NuevoDetalleComprobanteVenta']=$NuevoDetalle;
    }

    return $resultado;
  }

  function ValidarComprobanteEnReferenciaVenta($data)
  {
    $resultado = $this->mComprobanteVenta->ObtenerDocumentosReferenciaPorComprobante($data);
    if (Count($resultado) > 0) {
      $mensaje = "Este comprobante tiene referencias, no puede ser alterado. Sus referencias son:";
      foreach ($resultado as $key => $value) {
        $mensaje .= "<br>- " . $value["NombreAbreviado"] . " " . $value["SerieDocumento"] . "-" . $value["NumeroDocumento"] . "&nbsp;&nbsp;&nbsp; Fecha Emisión: " . convertirFechaES($value["FechaEmision"]);
      }
      return $mensaje;
    } else {
      return "";
    }
  }

  function ValidarComprobanteEnGuiaRemisionRemitente($data)
  {
    $resultado = $this->sGuiaRemisionRemitente->ObtenerGuiasRemisionRemitentePorIdComprobanteVenta($data);
    //evaluar en que caso permitir editar.
    if (Count($resultado) > 0) {
      $mensaje = "Este comprobante tiene guias de remision remitente, no puede ser alterado. Sus referencias son:";
      foreach ($resultado as $key => $value) {
        $mensaje .= "<br>- " . $value["NombreAbreviado"] . " " . $value["SerieDocumento"] . "-" . $value["NumeroDocumento"] . "&nbsp;&nbsp;&nbsp; Fecha Emisión: " . convertirFechaES($value["FechaEmision"]);
      }
      return $mensaje;
    } else {
      return "";
    }
  }

  function ValidarDuplicadoDeComprobanteVenta($data)
  {
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "");

    $resultado = $this->mComprobanteVenta->ObtenerDuplicadoDeComprobanteVenta($data);
    $FechaEmision = $resultado[0]['FechaEmision'];
    $fecha = substr($FechaEmision, 0, 10);
    $tempdate = explode('-', $fecha);

    if (count($resultado) > 0) {
      return "Este Comprobante se emitio en el mes: " . $meses[(int)$tempdate[01]] . " y año: " . $tempdate[0];
    } else {
      return "";
    }
  }

  function FechaEmision($data)
  {
    $fecha = $data["FechaCambio"];
    $tempdate = explode('/', $fecha);
    $nuevafecha = ($tempdate[2] . '-' . $tempdate[1] . '-' . $tempdate[0]);
    return $nuevafecha;
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

  function ObtenerAtributosComprobanteVenta()
  {
    $data['IdGrupoParametro'] = ID_ATRIBUTO_COMPROBANTE_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
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

  function ValidarTotalComprobanteVenta($data)
  {
    $total = $data["Total"];

    if ($total == "") {
      return "El total de venta debe ser completado";
    } else {
      return "";
    }
  }

  function ValidarIgvComprobanteVenta($data)
  {
    $igv = $data["IGV"];

    if ($igv == "") {
      return "El IGV de venta debe ser completado";
    } else {
      return "";
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

  function ValidarPrecioUnitario($data)
  {
    $precio = $data["PrecioUnitario"];

    if ($precio == "") {
      return "ingresar el precio del producto";
    } else {
      return "";
    }
  }

  function ValidarDescuentoItem($data)
  {
    $descuento = $data["Descuento"];

    if ($descuento == "") {
      return "ingresar el descuento del producto";
    } else {
      return "";
    }
  }

  function ValidarSubTotal($data)
  {
    $subtotal = $data["SubTotal"];

    if ($subtotal == "") {
      return "ingresar el sub total del producto";
    } else {
      return "";
    }
  }

  function CalcularSubTotal($data)
  {
    $cantidad = $this->ValidarCantidad($data);
    $precio = $this->ValidarPrecioUnitario($data);
    $descuento = $this->ValidarDescuentoItem($data);

    if ($cantidad != "") {
      return $cantidad;
    } else if ($precio != "") {
      return $precio;
    } else if ($descuento != "") {
      return $descuento;
    } else {
      $subtotal = ($cantidad * $precio) - $descuento;
      return $subtotal;
    }
  }

  function ValidarCorrelativoNotaSalida($data)
  {
    $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    if (count($asignacionsede) <= 0) {
      return "El usuario no tiene una sede asignada.";
    }

    $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTASALIDA;
    $parametro['IdSedeAgencia'] = $asignacionsede[0]["IdSede"];
    $resultado = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);

    if (count($resultado) <= 0) {
      return "Por favor ingrese una Serie de Nota de Salida para dicho almacen.";
    } else {
      return "";
    }
  }

  function ValidarClienteEnZonaUsuario($data)
  {
    $data["IdPersona"] = $data["IdCliente"];
    $cliente = (array) $this->sCliente->ObtenerClientePorIdPersona($data);
    $usuario = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($data)[0];

    if (empty($cliente)) {
      return "No se encontro el cliente en el sistema.";
    } else {
      if ($cliente["NombreZona"] != $usuario["NombreZona"]) {
        return "El vendedor " . $usuario["AliasUsuarioVenta"] . " no puede vender a un cliente que NO esta en su ZONA";
      } else {
        return "";
      }
    }
  }

  /**VALIDACION DE FECHA DE CERTIFICADO DIGITAL */
  function ValidarCertificadoDigital($data)
  {
    $datosEmpresa = $this->sEmpresa->ObtenerDatosEmpresa();
    if($data["FechaEmision"] >= $datosEmpresa["FechaInicioCertificadoDigital"] && $data["FechaEmision"] <= $datosEmpresa["FechaFinCertificadoDigital"])
    {
      return "";
    }
    else
    {
      return  "No se puede firmar el comprobante debido a que el certificado ha vencido. Coordine con su administrador y adquiere un nuevo certificado";
    }
  }

  function InsertarComprobanteVenta($data)
  {
    try {
      
      if ($data["FechaVencimiento"] == "") {
        $data["FechaVencimiento"] = $data["FechaEmision"];
      }
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["FechaMovimientoAlmacen"] = convertToDate($data["FechaMovimientoAlmacen"]);

      if (array_key_exists("FechaEntrega", $data))
        $data["FechaEntrega"] = convertToDate($data["FechaEntrega"]);

      if (array_key_exists("FechaExpedicion", $data))
        $data["FechaExpedicion"] = convertToDate($data["FechaExpedicion"]);

      $dataAnterior["FechaEmision"] = $data["FechaEmision"];
      $dataAnterior["AliasUsuarioVenta"] = $data["AliasUsuarioVenta"];
      $dataPeriodoAnterior = $this->sPeriodo->ObtenerPeriodoPorFecha($dataAnterior);
      $dataUsuarioAnterior = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($dataAnterior);
      $data["IdPeriodoAnterior"] = count($dataPeriodoAnterior) > 0 ?  $dataPeriodoAnterior[0]["IdPeriodo"] : "";
      $data["IdUsuarioVendedorAnterior"] = count($dataUsuarioAnterior) > 0 ?  $dataUsuarioAnterior[0]["IdUsuario"] : "";

      $dataPeriodo = $this->sPeriodo->ObtenerPeriodoPorFecha($data);
      $dataUsuario = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($data);
      $data["IdPeriodo"] = count($dataPeriodo) > 0 ?  $dataPeriodo[0]["IdPeriodo"] : "";
      $data["IdUsuarioVendedor"] = count($dataUsuario) > 0 ?  $dataUsuario[0]["IdUsuario"] : "";

      $this->sDetalleComprobanteVenta->CargarComprobanteVenta($data);

      $resultadoValidacion = $this->ValidarComprobanteVenta($data);
      $resultadoValidacionCertificado = "";
      if (!is_numeric($data["SerieDocumento"])) {
        $resultadoValidacionCertificado = $this->ValidarCertificadoDigital($data);
      }

      $correlativo = "";
      if ($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTADEBITO && $data["IdTipoVenta"] == TIPO_VENTA_MERCADERIA) {
        $correlativo = $this->ValidarCorrelativoNotaSalida($data);
      }

      //VALIDACION DE ZONA DE USUARIO  
      $parametroZona = $this->sConstanteSistema->ObtenerParametroZona();
      $validacionZona = ($parametroZona == 1) ? $this->ValidarClienteEnZonaUsuario($data) : "";

      if ($correlativo != "") {
        return $correlativo;
      } elseif (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } elseif ($validacionZona != "") {
        return $validacionZona;
      } elseif ($resultadoValidacionCertificado != "") {
        return $resultadoValidacionCertificado;
      } elseif ($resultadoValidacion == "") {
        $data = $this->TotalizarMontosComprobanteVenta($data);

        $resultado = $this->mComprobanteVenta->InsertarComprobanteVenta($data);
        if (!array_key_exists("CodigoTipoDocumento", $data)) {
          $resultadoTipoDocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
          $data["CodigoTipoDocumento"] = count($resultadoTipoDocumento) > 0 ? $resultadoTipoDocumento[0]["CodigoTipoDocumento"] : "";
        }
        $resultado["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
        $resultado["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
        $resultado["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
        $resultado["TotalComprobante"] = str_replace(',', "", $data["Total"]);


        $IdComprobanteVenta = $resultado["IdComprobanteVenta"];
        $this->sDetalleComprobanteVenta->EstadoPendienteNota = $resultado["EstadoPendienteNota"];

        $data["EstadoDetraccion"] = $data["EstadoDetraccion"]  == '1' ? '1' : '0';
        $data["EstadoRetencionIGV"] = $data["EstadoRetencionIGV"]  == '1' ? '1' : '0';

        if ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          if (array_key_exists("DetallesComprobanteVenta", $data)) {
            $resultado["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->InsertarDetallesComprobanteVenta($IdComprobanteVenta, $data["DetallesComprobanteVenta"]);
          }
        } else {
          if (($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA || $data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA)) {
            if ($data["IdTipoVenta"] == TIPO_VENTA_SERVICIOS && $data["ParametroTransporte"] == "1") {
              if ($data["IndicadorBoletaViaje"] == 1) {
                $resultadoTransporte = $this->sComprobanteVentaTransporte->InsertarComprobanteVentaTransporte($IdComprobanteVenta, $data);
                if (!is_array($resultadoTransporte)) {
                  return $resultadoTransporte;
                }
              }
            }
          }
          $resultado["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->InsertarDetallesComprobanteVenta($IdComprobanteVenta, $data["DetallesComprobanteVenta"]);
        }

        if(strlen($data["IdComprobanteVenta"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $UltimoDocumento=$this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          $input = $data;
          $input["NumeroDocumento"] =$UltimoDocumento;
          $resultadoValidacionCorrelativo = $this->ValidarCorrelativoDocumento($input);
          if ($resultadoValidacionCorrelativo!="") return $resultadoValidacionCorrelativo;
          $resultado["NumeroDocumento"] =$UltimoDocumento;

          $this->ActualizarSerieDocumentoComprobanteVenta($resultado);
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

          //$resultado["NumeroDocumento"] =str_pad($data["NumeroDocumento"], CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        }

        if (array_key_exists("Direccion", $data)) {
          //Actualizar Direccion y Celular de CLiente
          $dataCliente["IdCliente"] = $data["IdCliente"];
          $dataCliente["Direccion"] = $data["Direccion"];
          $dataCliente["Celular"] = $data["CelularDestinatario"];
          $this->sCliente->ModificarCliente($dataCliente);
        }

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] = convertirFechaES($resultado["FechaVencimiento"]);
        $resultado["FechaMovimientoAlmacen"] = convertirFechaES($resultado["FechaMovimientoAlmacen"]);

        if (array_key_exists("FechaEntrega", $resultado))
          $resultado["FechaEntrega"] = convertirFechaES($resultado["FechaEntrega"]);

        if (array_key_exists("ProformasComprobanteVenta", $data)) {
          foreach ($data["ProformasComprobanteVenta"] as $key => $value) {
            $data["ProformasComprobanteVenta"][$key]["IdComprobanteVenta"] = $resultado["IdComprobanteVenta"];
          }

          $this->sProformaComprobanteVenta->GuardarProformasComprobanteVenta($data["ProformasComprobanteVenta"]);
        }
        if (array_key_exists("CuotasPagoClienteComprobanteVenta", $data)) {
          $CuotasPagoClienteComprobanteVenta = $data["CuotasPagoClienteComprobanteVenta"];
          $this->sCuotaPagoClienteComprobanteVenta->InsertarCuotasPagoClienteComprobanteVenta($CuotasPagoClienteComprobanteVenta, $resultado["IdComprobanteVenta"]);
        }
        $resultado["CodigoMoneda"] = $data["CodigoMoneda"];
        $resultado["NombreSedeAlmacen"] = $data["NombreSedeAlmacen"];
        $resultado["RazonSocial"] = $data["RazonSocial"];
        $resultado["IdSubTipoDocumento"] = $data["IdSubTipoDocumento"];
        return $resultado;
      } else {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarComprobanteVenta($data)
  {
    try {
      if ($data["FechaVencimiento"] == "") {
        $data["FechaVencimiento"] = $data["FechaEmision"];
      }
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaEmisionAnterior"] = convertToDate($data["FechaEmisionAnterior"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["FechaEntrega"] = convertToDate($data["FechaEntrega"]);
      $data["FechaMovimientoAlmacen"] = convertToDate($data["FechaMovimientoAlmacen"]);
      $data["FechaExpedicion"] = convertToDate($data["FechaExpedicion"]);
      $data["IndicadorEstadoResumenDiario"] = ESTADO_CPE_NINGUNO;

      $dataAnterior["FechaEmision"] = $data["FechaEmisionAnterior"];
      $dataAnterior["AliasUsuarioVenta"] = $data["AliasUsuarioVentaAnterior"];

      $dataPeriodoAnterior = $this->sPeriodo->ObtenerPeriodoPorFecha($dataAnterior);
      $dataUsuarioAnterior = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($dataAnterior);
      $data["IdPeriodoAnterior"] = count($dataPeriodoAnterior) > 0 ?  $dataPeriodoAnterior[0]["IdPeriodo"] : "";
      $data["IdUsuarioVendedorAnterior"] = count($dataUsuarioAnterior) > 0 ?  $dataUsuarioAnterior[0]["IdUsuario"] : "";

      $dataPeriodo = $this->sPeriodo->ObtenerPeriodoPorFecha($data);
      $dataUsuario = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($data);
      $data["IdPeriodo"] = count($dataPeriodo) > 0 ?  $dataPeriodo[0]["IdPeriodo"] : "";
      $data["IdUsuarioVendedor"] = count($dataUsuario) > 0 ?  $dataUsuario[0]["IdUsuario"] : "";

      $this->sDetalleComprobanteVenta->CargarComprobanteVenta($data);

      $resultadoValidacion = $this->ValidarComprobanteVenta($data);
      $resultadoValidacionCertificado = "";
      if (!is_numeric($data["SerieDocumento"])) {
        $resultadoValidacionCertificado = $this->ValidarCertificadoDigital($data);
      }

      $documentoreferencia = $this->ValidarComprobanteEnReferenciaVenta($data);
      $guiaremisionremitente = $this->ValidarComprobanteEnGuiaRemisionRemitente($data);

      $correlativo = "";
      if ($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTADEBITO && $data["IdTipoVenta"] == TIPO_VENTA_MERCADERIA) {
        $correlativo = $this->ValidarCorrelativoNotaSalida($data);
      }

      //VALIDACION DE ZONA DE USUARIO  
      $parametroZona = $this->sConstanteSistema->ObtenerParametroZona();
      $validacionZona = ($parametroZona == 1) ? $this->ValidarClienteEnZonaUsuario($data) : "";

      if ($correlativo != "") {
        return $correlativo;
      } elseif (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } elseif ($validacionZona != "") {
        return $validacionZona;
      } elseif ($documentoreferencia != "") {
        return $documentoreferencia;
      } elseif ($guiaremisionremitente != "") {
        return $guiaremisionremitente;
      } elseif ($resultadoValidacionCertificado != "") {
        return $resultadoValidacionCertificado;
      } else if ($resultadoValidacion == "") {
        
        $data = $this->TotalizarMontosComprobanteVenta($data);

        $resultado = $this->mComprobanteVenta->ActualizarComprobanteVenta($data);
        $IdComprobanteVenta = $data["IdComprobanteVenta"];
        $resultado["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
        $resultado["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
        $resultado["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
        $resultado["TotalComprobante"] = str_replace(',', "", $data["Total"]);


        if (($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA || $data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA)) {
          if ($data["IdTipoVenta"] == TIPO_VENTA_SERVICIOS && $data["ParametroTransporte"] == "1") {
            if ($data["IndicadorBoletaViaje"] == 1) {
              $resultadoTransporte = $this->sComprobanteVentaTransporte->ActualizarComprobanteVentaTransporte($IdComprobanteVenta, $data);
              if (!is_array($resultadoTransporte)) {
                return $resultadoTransporte;
              }
            }
          }
        }


        $resultado["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ActualizarDetallesComprobanteVenta($IdComprobanteVenta, $data["DetallesComprobanteVenta"]);

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

        if (array_key_exists("Direccion", $data)) {
          $dataCliente["IdCliente"] = $data["IdCliente"];
          $dataCliente["Direccion"] = $data["Direccion"];
          $dataCliente["Celular"] = $data["CelularDestinatario"];
          $this->sCliente->ModificarCliente($dataCliente);
        }

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] = convertirFechaES($resultado["FechaVencimiento"]);
        $resultado["FechaEntrega"] = convertirFechaES($resultado["FechaEntrega"]);
        $resultado["FechaMovimientoAlmacen"] = convertirFechaES($resultado["FechaMovimientoAlmacen"]);
        $resultado["NombreSedeAlmacen"] = $data["NombreSedeAlmacen"];
        $resultado["RazonSocial"] = $data["RazonSocial"];
        $resultado["IdSubTipoDocumento"] = $data["IdSubTipoDocumento"];

        if (array_key_exists("ProformasComprobanteVenta", $data)) {
          $this->sProformaComprobanteVenta->GuardarProformasComprobanteVenta($data["ProformasComprobanteVenta"]);
        }
        if (array_key_exists("CuotasPagoClienteComprobanteVenta", $data)) {
          $CuotasPagoClienteComprobanteVenta = $data["CuotasPagoClienteComprobanteVenta"];
          $this->sCuotaPagoClienteComprobanteVenta->ActualizarCuotasPagoClienteComprobanteVenta($CuotasPagoClienteComprobanteVenta, $resultado["IdComprobanteVenta"]);
        }
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarComprobanteVenta($data)
  {

    $documentoreferencia = $this->ValidarComprobanteEnReferenciaVenta($data);
    $guiaremisionremitente = $this->ValidarComprobanteEnGuiaRemisionRemitente($data);
    if ($documentoreferencia != "") {
      return $documentoreferencia;
    } elseif ($guiaremisionremitente != "") {
      return $guiaremisionremitente;
    } else {
      $DetalleComprobanteVenta = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);

      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["FechaMovimientoAlmacen"] = convertToDate($data["FechaMovimientoAlmacen"]);
      $data["ParametroTransporte"] = $this->sConstanteSistema->ObtenerParametroTranporte();
      $resultado = $this->mComprobanteVenta->BorrarComprobanteVenta($data);
      if (is_array($resultado)) {
        if (($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA || $data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA)) {
          if ($data["IdTipoVenta"] == TIPO_VENTA_SERVICIOS && $data["ParametroTransporte"] == "1") {
            $resultadoTransporte = $this->sComprobanteVentaTransporte->BorrarComprobanteVentaTransporte($data);
          }
        }
        $dataPeriodo = $this->sPeriodo->ObtenerPeriodoPorFecha($data);
        $dataUsuario = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($data);
        $data["IdPeriodo"] = count($dataPeriodo) > 0 ?  $dataPeriodo[0]["IdPeriodo"] : "";
        $data["IdUsuarioVendedor"] = count($dataUsuario) > 0 ?  $dataUsuario[0]["IdUsuario"] : "";
        $this->sDetalleComprobanteVenta->CargarComprobanteVenta($data);
        $detalle = $this->sDetalleComprobanteVenta->EliminarDetallesPorIdComprobanteVenta($data);
      }

      $resultado["DetallesComprobanteVenta"] = $DetalleComprobanteVenta;
      return $resultado;
    }
  }

  function ActualizarEstadoComprobanteVenta($data)
  {
    $resultado = $this->mComprobanteVenta->ActualizarComprobanteVenta($data);
    return $resultado;
  }

  function ActualizarSerieDocumentoComprobanteVenta($data)
  {
    $IdComprobanteVenta = $data["IdComprobanteVenta"];
    $this->mComprobanteVenta->ActualizarComprobanteVenta($data);
    return "";
  }


  function ValidarEstadoComprobanteVenta($data)
  {
    $resultado = "";
    if (!array_key_exists("EstadoSincronizacion", $data)) {
      if ($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ANULADO) {
        $resultado = $resultado . "El comprobante no puede ser editado , anulado o eliminado porque esta ya anulado." . "\n";
      } else if ($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ELIMINADO) {
        $resultado = $resultado . "El comprobante no puede ser editado , anulado o eliminado porque esta ya eliminado." . "\n";
      } else if ($data["IndicadorEstado"] == ESTADO_DOCUMENTO_ACTIVO) {
        if ($data["IndicadorEstadoCPE"] == ESTADO_CPE_ACEPTADO || $data["IndicadorEstadoCPE"] == ESTADO_CPE_RECHAZADO) {
          $resultado = $resultado . "El comprobante no puede ser editado , anulado o eliminado, porque fue aceptado o rechazado por SUNAT." . "\n";
        }
      }
    }

    return $resultado;
  }

  function ValidarComprobanteVenta($data)
  {
    $resultado = "";
    $FechaEmisionMinimo = $this->ObtenerFechaEmisionMinimo();

    if ($data["IdMoneda"] != ID_MONEDA_SOLES) {
      if ($data["ValorTipoCambio"] <= 0 || !is_numeric($data["ValorTipoCambio"])) {
        $resultado = $resultado . "El tipo de cambio de documento debe ser mayor a cero y numérico." . "\n";
      }
    }

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

    if (strlen($data["IdCliente"]) == 0) {
      $resultado = $resultado . "El Cliente no se encuentra disponible en el sistema." . "\n";
    }

    $data["DescuentoGlobal"] = (is_string($data["DescuentoGlobal"])) ? str_replace(',', "", $data["DescuentoGlobal"]) : $data["DescuentoGlobal"];
    if ($data["DescuentoGlobal"] < 0 || !is_numeric($data["DescuentoGlobal"])) {
      $resultado = $resultado . "El descuento global debe ser mayor o igual que cero y numerico." . "\n";
    }

    $validadoFechaVencimiento = true;

    if ($data["IdFormaPago"] == ID_FORMA_PAGO_CREDITO) {
      if($data["IdTipoDocumento"]!=ID_TIPODOCUMENTO_NOTACREDITO) {
        $resultado = $resultado . $this->ValidarCuotasPago($data);
      }

      if (strlen($data["FechaVencimiento"]) <= 0 || !validateDate($data["FechaVencimiento"], "Y-m-d")) {
        $resultado = $resultado . "La fecha de vencimiento es incorrecta y obligatoria en cuando la forma de pago es al crédito." . "\n";
        $validadoFechaVencimiento = false;
      } else {
        if (validateDateDiff($FechaEmisionMinimo, $data["FechaVencimiento"], "d") < 0) {
          $resultado = $resultado . "La fecha vencimiento debe ser mayor a " . $FechaEmisionMinimo . "\n";
          $validadoFechaVencimiento = false;
        }

        if ($validadoFechaEmision && $validadoFechaVencimiento) {
          if (validateDateDiff($FechaEmisionMinimo, $data["FechaVencimiento"], "d") < 0) {
            $resultado = $resultado . "La fecha vencimiento no debe ser menor a la fecha de emision" . "\n";
          }
        }
      }
    } else {
      if (strlen($data["FechaVencimiento"]) > 0) {

        if (!validateDate($data["FechaVencimiento"], "Y-m-d")) {
          $resultado = $resultado . "La fecha de vencimiento es incorrecta." . "\n";
          $validadoFechaVencimiento = false;
        }

        if (validateDateDiff($FechaEmisionMinimo, $data["FechaVencimiento"], "d") < 0) {
          $resultado = $resultado . "La fecha vencimiento debe ser mayor a " . $FechaEmisionMinimo . "\n";
          $validadoFechaVencimiento = false;
        }

        if ($validadoFechaEmision && $validadoFechaVencimiento) {
          if (validateDateDiff($FechaEmisionMinimo, $data["FechaVencimiento"], "d") < 0) {
            $resultado = $resultado . "La fecha vencimiento no debe ser menor a la fecha de emision" . "\n";
          }
        }
      }
    }

    $resultado = $resultado . $this->ValidarEstadoComprobanteVenta($data);

    $resultado_detalle = "";

    if ($data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTACREDITO && $data["IdTipoDocumento"] != ID_TIPODOCUMENTO_NOTADEVOLUCION) {
      $resultado_detalle = $this->sDetalleComprobanteVenta->ValidarDetallesComprobanteVenta($data["DetallesComprobanteVenta"], $data["IdAsignacionSede"]);
    }

    $resultado = $resultado . $resultado_detalle;

    $resultadoLicencia = (LICENCIA_VENTA_FECHA_PERPETUA == 1) ? "" : $this->sLicencia->ValidarLicenciaPorVenta($data);
    if ($resultadoLicencia != "") $resultado = $resultado . $resultadoLicencia;





    return $resultado;
  }

  function ObtenerComprobanteVenta($data)
  {
    $output = $this->mComprobanteVenta->ObtenerComprobanteVenta($data);
    $resultado = $output[0];
    $resultado["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);
    $resultado["DetalleTributo"] = $this->PrepararDetalleTributo($resultado);
    //$resultado["DetalleAdicionales"] = $this->PrepararDetalleAdicionales($resultado);
    if(array_key_exists("IdMotivoNotaCredito",$data)) {
      if ($data["IdMotivoNotaCredito"] == 13) {
        $resultado["TotalMontoBaseImponible"] =0;
        $resultado["Total"] =0;

        $resultado["IGV"] =0;// $IGV;
        $resultado["DescuentoTotalItem"] = 0;
        $resultado["OtroCargo"] = 0;      
        $resultado["DetalleVariablesGlobales"] =array();
        $resultado["DetalleCuotasPago"] = $this->sCuotaPagoClienteComprobanteVenta->ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data);
        $MontoNetoPendientePagoCredito=0;
        foreach($resultado["DetalleCuotasPago"] as $key=>$value) {
          $MontoNetoPendientePagoCredito = $MontoNetoPendientePagoCredito + $value["MontoCuota"];
        }
        $resultado["MontoNetoPendientePagoCredito"] = $MontoNetoPendientePagoCredito;
      }
      else {
        $DescuentoGlobal = 0; //$resultado["DescuentoGlobal"] > 0 ? $resultado["DescuentoGlobal"] : 0;
        $resultado["TotalMontoBaseImponible"] = $resultado["ValorVentaGravado"] + $resultado["ValorVentaNoGravado"] + $resultado["ValorVentaInafecto"] - $DescuentoGlobal;
        $resultado["DetalleVariablesGlobales"] = $this->PrepararDetalleVariablesGlobales($resultado);
        $resultado["DetalleCuotasPago"] = $this->sCuotaPagoClienteComprobanteVenta->ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data);
        $resultado["MontoNetoPendientePagoCredito"] = $resultado["Total"] - $resultado["MontoDetraccion"] - $resultado["MontoRetencionIGV"];
      }    
    }
    else {
      $DescuentoGlobal = 0; //$resultado["DescuentoGlobal"] > 0 ? $resultado["DescuentoGlobal"] : 0;
      $resultado["TotalMontoBaseImponible"] = $resultado["ValorVentaGravado"] + $resultado["ValorVentaNoGravado"] + $resultado["ValorVentaInafecto"] - $DescuentoGlobal;
      $resultado["DetalleVariablesGlobales"] = $this->PrepararDetalleVariablesGlobales($resultado);
      $resultado["DetalleCuotasPago"] = $this->sCuotaPagoClienteComprobanteVenta->ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data);
      $resultado["MontoNetoPendientePagoCredito"] = $resultado["Total"] - $resultado["MontoDetraccion"] - $resultado["MontoRetencionIGV"];
    }    

    return $resultado;
  }

  function PrepararDetalleVariablesGlobales($data)
  {
    //variables global de descuento
    $resultado = array();
    $filas = 0;
    if ($data["DescuentoGlobal"] > 0) {
      $resultado[0]["TipoVariableGlobal"] = "false";
      $resultado[0]["CodigoTipoVariableGlobal"] = $data["CodigoTipoVariableDescuentoGlobal"];
      $resultado[0]["PorcentajeVariableGlobal"] = $data["PorcentajeVariableDescuentoGlobal"];
      $resultado[0]["MonedaVariableGlobal"] = $data["CodigoMoneda"];
      $resultado[0]["MontoVariableGlobal"] = $data["DescuentoGlobalValorVenta"];
      $resultado[0]["MonedaBaseImponibleVariableGlobal"] = $data["CodigoMoneda"];
      $resultado[0]["MontoBaseImponibleVariableGlobal"] = $data["MontoBaseImponibleVariableDescuentoGlobal"];
      $filas = count($resultado);
    }

    if ($data["EstadoRetencionIGV"] == "1") {
      $resultado[$filas]["TipoVariableGlobal"] = "false";
      $resultado[$filas]["CodigoTipoVariableGlobal"] = "62";
      $resultado[$filas]["PorcentajeVariableGlobal"] = $data["PorcentajeRetencionIGV"] / 100;
      $resultado[$filas]["MonedaVariableGlobal"] = $data["CodigoMoneda"];
      $resultado[$filas]["MontoVariableGlobal"] = $data["MontoRetencionIGV"];
      $resultado[$filas]["MonedaBaseImponibleVariableGlobal"] = $data["CodigoMoneda"];
      $resultado[$filas]["MontoBaseImponibleVariableGlobal"] = $data["BaseImponibleRetencionIGV"];
    }

    return $resultado;
  }

  function PrepararDetalleAdicionales($data) {
    $parametro = $this->sConstanteSistema->ObtenerParametroCalculoIGVDesdeTotal();
    $resultado= array();
    
    $resultado = array();

    foreach ($data["DetallesComprobanteVenta"] as $key => $value) {
      // Descuentos por item - restan a valor unitario por item
      if ($value["DescuentoItem"] > 0) {
        $item["NumeroItem"] = $key + 1;
        $item["CodigoTipoVariable"] = $parametro == 0 ? "00" : "01";
        $item["EstadoIndicadoCargo"] = "false";
        $item["CodigoMoneda"] = $data["CodigoMoneda"];
        $item["MontoVariableItem"] = $value["DescuentoItem"];
        array_push($resultado, $item);
      }

      // Cargos por item - suman al valor unitario por item
      if (array_key_exists("CargoItem", $value)) {
        if ($value["CargoItem"] > 0) {
          $item["NumeroItem"] = $key + 1;
          $item["CodigoTipoVariable"] = $parametro == 0 ? "47" : "48";
          $item["EstadoIndicadoCargo"] = "true";
          $item["CodigoMoneda"] = $data["CodigoMoneda"];
          $item["MontoVariableItem"] = $value["CargoItem"];
          array_push($resultado, $item);
        }
      }
    }

    return $resultado;
  }

  function PrepararDetalleTributo($data)
  {
    $tributo = array();
    
    if ($data["IdMotivoNotaCredito"]==13) {
      $gravado["CodigoTributo"] = "1000";
      $gravado["NombreTributo"] = "IGV";
      $gravado["CodigoTipoTributo"] = "VAT";
      $MontoBaseImponible=0;
      $IGV=0;
      foreach($data["DetallesComprobanteVenta"] as $key=>$value ) {
        $MontoBaseImponible=$MontoBaseImponible+$value["ValorVentaItem"];
        $IGV=$IGV+$value["IGVItem"];
      }
      $gravado["MontoBaseImponible"] = $MontoBaseImponible;
      $gravado["MontoTributo"] = $IGV;

      array_push($tributo, $gravado);

      return $tributo;
    } 
      

    //PARA GRAVADO
    $gravado["CodigoTributo"] = "1000";
    $gravado["NombreTributo"] = "IGV";
    $gravado["CodigoTipoTributo"] = "VAT";
    $gravado["MontoBaseImponible"] = $data["ValorVentaGravado"];
    $gravado["MontoBaseNetoImponible"] = $data["ValorVentaNetoGravado"];
    $gravado["MontoTributo"] = $data["IGV"];

    array_push($tributo, $gravado);

    //PARA EXONERADO
    $exonerado["CodigoTributo"] = "9997";
    $exonerado["NombreTributo"] = "EXO";
    $exonerado["CodigoTipoTributo"] = "VAT";
    $exonerado["MontoBaseImponible"] = $data["ValorVentaNoGravado"];
    $exonerado["MontoBaseNetoImponible"] = $data["ValorVentaNetoNoGravado"];
    $exonerado["MontoTributo"] = "0.00";

    array_push($tributo, $exonerado);

    //PARA INAFECTA
    $inafecta["CodigoTributo"] = "9998";
    $inafecta["NombreTributo"] = "INA";
    $inafecta["CodigoTipoTributo"] = "FRE";
    $inafecta["MontoBaseImponible"] = $data["ValorVentaInafecto"];
    $inafecta["MontoBaseNetoImponible"] = $data["ValorVentaNetoInafecto"];
    $inafecta["MontoTributo"] = "0.00";

    array_push($tributo, $inafecta);

    if(array_key_exists("ValorVentaOperacionGratuita",$data) ) {
    //PARA GRATUITO    
      $gratuito["CodigoTributo"] = "9996";
      $gratuito["NombreTributo"] = "GRA";
      $gratuito["CodigoTipoTributo"] = "FRE";
      $gratuito["MontoBaseImponible"] = $data["ValorVentaOperacionGratuita"];
      $gratuito["MontoBaseNetoImponible"] = $data["ValorVentaOperacionGratuita"];
      $gratuito["MontoTributo"] = "0.00";//$data["IGVReferencial"];
      array_push($tributo, $gratuito);
    }

    //PARA ICBPER
    $icbper["CodigoTributo"] = "7152";
    $icbper["NombreTributo"] = "ICBPER";
    $icbper["CodigoTipoTributo"] = "OTH";
    $icbper["MontoBaseImponible"] = "0.00";
    $icbper["MontoBaseNetoImponible"] = "0.00";
    $icbper["MontoTributo"] = $data["ICBPER"];

    array_push($tributo, $icbper);

    return $tributo;
  }

  function ValidarAnulacionVenta($data)
  {
    $comprobante = $this->ObtenerComprobanteVenta($data);
    $SerieDocumento = (string) $comprobante["SerieDocumento"];
    $indicador = substr($SerieDocumento, 0, 1);
    if ($indicador == CODIGO_SERIE_FACTURA) {
      if ($comprobante["IndicadorEstadoCPE"] == ESTADO_CPE_ACEPTADO) {
        return "";
      } else {
        return "El comprobante no puede ser anulado porque aun no ha sido enviado a SUNAT.";
      }
    } else if ($indicador == CODIGO_SERIE_BOLETA) {
      if ($comprobante["IndicadorEstadoResumenDiario"] == ESTADO_CPE_ACEPTADO) {
        if ($comprobante["CodigoEstado"] == CODIGO_ESTADO_ANULADO) {
          return "El comprobante no puede ser anulado";
        } else {
          return ""; //SE PUEDE ANULAR
        }
      } else if ($comprobante["IndicadorEstadoResumenDiario"] == "") {
        if ($comprobante["CodigoEstado"] == CODIGO_ESTADO_EMITIDO) {
          return "El comprobante no puede ser anulado porque aun no ha sido enviado a SUNAT.";
        } else if ($comprobante["CodigoEstado"] == CODIGO_ESTADO_MODIFICADO) {
          return ""; //SE PUEDE ANULAR
        } else //CODIGO_ESTADO_ANULADO
        {
          return "El comprobante no puede ser anulado";
        }
      } else {
        return "El comprobante no puede ser anulado porque aun no ha sido enviado a SUNAT.";
      }
    } else {
      return "";
    }
  }

  function AnularComprobanteVenta($data)
  {
    try {
      $resultadoValidacion = ""; //$this->ValidarAnulacionVenta($data);
      $guiaremisionremitente = $this->ValidarComprobanteEnGuiaRemisionRemitente($data);

      if ($guiaremisionremitente != "") {
        return $guiaremisionremitente;
      } elseif ($resultadoValidacion == "") {
        $data["IndicadorEstado"] = ESTADO_DOCUMENTO_ANULADO;
        $data["IndicadorEstadoResumenDiario"] = ESTADO_CPE_NINGUNO;
        $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
        $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
        $data["FechaMovimientoAlmacen"] = convertToDate($data["FechaMovimientoAlmacen"]);
        $resultado = $this->ActualizarEstadoComprobanteVenta($data);
        $resultado["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);

        $dataPeriodo = $this->sPeriodo->ObtenerPeriodoPorFecha($data);
        $dataUsuario = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($data);
        $data["IdPeriodo"] = count($dataPeriodo) > 0 ?  $dataPeriodo[0]["IdPeriodo"] : "";
        $data["IdUsuarioVendedor"] = count($dataUsuario) > 0 ?  $dataUsuario[0]["IdUsuario"] : "";
        $this->sDetalleComprobanteVenta->CargarComprobanteVenta($data);
        $this->sDetalleComprobanteVenta->AnularDetallesPorIdComprobanteVenta($data);

        $resultado["FechaEmision"] = convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] = convertirFechaES($resultado["FechaVencimiento"]);
        $resultado["AbreviaturaSituacionCPE"] = ($resultado["AbreviaturaSituacionCPE"] != "") ? $resultado["AbreviaturaSituacionCPE"]->AbreviaturaSituacionComprobanteElectronicoVentas : "";
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ImprimirReporteComprobanteVenta($data)
  {
    try {
      $FormatoImpresion = $this->ObtenerFormatoImpresion();
      $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
      $SerieDocumento = $data["SerieDocumento"];
      $CodigoSerie = substr($data["SerieDocumento"], 0, 1);
      $CodigoSerieSubTipo = substr($data["SerieDocumento"], 0, 2);

      $printer = null;
      $rutaFormato = RUTA_CARPETA_REPORTES . NOMBRE_FACTURA_ELECTRONICO;
      $indicadorImpresion = INDICADOR_FORMATO_OTRO;

      if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA) {
        if ($CodigoSerieSubTipo == "BT") {
          $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA_T;
        } else if ($CodigoSerieSubTipo == "BZ") {
          $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA_Z;
        } else {
          if ($CodigoSerie == "B") {
            $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA;
          } else {
            $indicadorImpresion = INDICADOR_FORMATO_BOLETA_FISICA_VENTA;
          }
        }
      } else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA) {
        if ($CodigoSerie == "F") {
          $indicadorImpresion = INDICADOR_FORMATO_FACTURA_ELECTRONICA_VENTA;
        } else {
          $indicadorImpresion = INDICADOR_FORMATO_FACTURA_FISICA_VENTA;
        }
      } else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        $indicadorImpresion = INDICADOR_FORMATO_ORDEN_PEDIDO;
      } else if ($data["IdTipoDocumento"] == INDICADOR_FORMATO_NOTA_CREDITO) {
        $indicadorImpresion = INDICADOR_FORMATO_NOTA_CREDITO;
      } else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_PROFORMA) {
        $indicadorImpresion = ID_TIPO_DOCUMENTO_PROFORMA;
      } else {
        $indicadorImpresion = INDICADOR_FORMATO_OTRO;
      }

      $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION . "config-" . $this->shared->GetDeviceName() . ".json";
      $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion, $SerieDocumento, $rutaplantilla);

      if ($dataConfig != false) {
        $printer = $dataConfig["Printer"];
        $rutaFormato = RUTA_CARPETA_REPORTES . $dataConfig["Jasper"];
        $this->reporter->RutaReporte = $rutaFormato;
        $this->reporter->SetearParametros($parametros);
        $this->reporter->Imprimir($printer);
      }
      
      return "";
    }
    catch (Exception $e) {
      throw new Exception($e);
    }
  }

  function GenerarVistaPreviaPDF($data)
  {
    $SerieDocumento = $data["SerieDocumento"];
    $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];

    $name_archive = $data["SerieDocumento"] . "-" . $data["NumeroDocumento"];
    // if($expo)
    // {
    //   $name_archive = $data["NombreArchivoComprobante"];
    // }

    $ruta_pdf = RUTA_CARPETA_REPORTES_GENERADOS_PDF.$name_archive.".pdf";
    
    $CodigoSerie=substr($data["SerieDocumento"], 0,1);
    $CodigoSerieSubTipo=substr($data["SerieDocumento"], 0,2);
    $CodigoSerie = substr($data["SerieDocumento"], 0, 1);
    $CodigoSerieSubTipo = substr($data["SerieDocumento"], 0, 2);

    $rutaFormato = RUTA_CARPETA_REPORTES . NOMBRE_FACTURA_ELECTRONICO;
    $indicadorImpresion = INDICADOR_FORMATO_OTRO;

    if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA) {
      if ($CodigoSerieSubTipo == "BT") {
        $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA_T;
      } else if ($CodigoSerieSubTipo == "BZ") {
        $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA_Z;
      } else {
        if ($CodigoSerie == "B") {
          $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA;
        } else {
          $indicadorImpresion = INDICADOR_FORMATO_BOLETA_FISICA_VENTA;
        }
      }
    } else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA) {
      if ($CodigoSerie == "F") {
        $indicadorImpresion = INDICADOR_FORMATO_FACTURA_ELECTRONICA_VENTA;
      } else {
        $indicadorImpresion = INDICADOR_FORMATO_FACTURA_FISICA_VENTA;
      }
    } else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
      $indicadorImpresion = INDICADOR_FORMATO_ORDEN_PEDIDO;
    } else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_PROFORMA) {
      $indicadorImpresion = ID_TIPO_DOCUMENTO_PROFORMA;
    } else {
      $indicadorImpresion = INDICADOR_FORMATO_OTRO;
    }
    $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION . "config-" . $this->shared->GetDeviceName() . ".json";
    $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion, $SerieDocumento, $rutaplantilla);
    if ($dataConfig != false) {
      $rutaFormato = RUTA_CARPETA_REPORTES . $dataConfig["Jasper"];
    }

    $this->reporter->RutaReporte = $rutaFormato;
    $this->reporter->RutaPDF = $ruta_pdf;
    $this->reporter->SetearParametros($parametros);
    $resultado = $this->reporter->ExportarReporteComoPDF();

    $output["APP_RUTA"] = APP_PATH_URL . "assets/reportes/Venta/" . $name_archive . ".pdf"; //"http://".$_SERVER["HTTP_HOST"]."/sisem/
    $output["BASE_RUTA"] = APP_PATH . "assets/reportes/Venta/" . $name_archive . ".pdf"; //"http://".$_SERVER["HTTP_HOST"]."/sisem/

    return $output;
  }


  function ImprimirComprobanteVentaComoPDF($data)
  {
    try {
      $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
      $CodigoSerieSubTipo = substr($data["SerieDocumento"], 0, 2);
      $this->reporter->RutaReporte = RUTA_CARPETA_REPORTES . "Venta/FacturaElectronicaModelo01.jasper"; //RUTA_CARPETA_REPORTES."Venta/TicketFacturaElectronicaModelo02.jasper";
      $this->reporter->SetearParametros($parametros);
      $this->reporter->RutaPDF = RUTA_CARPETA_REPORTES_GENERADOS_PDF . $data["SerieDocumento"] . "-" . $data["NumeroDocumento"] . ".pdf";
      $resultado = $this->reporter->GenerarReporteComoPDF();
      if ($resultado == true) {
        $salida["archivo"] = $this->reporter->RutaPDF;
        $salida["impresora"] = "PDFCreator";
        $this->imprimir->ImprimirPDF($salida);
      }

      return "";
    } catch (Exception $e) {
      throw new Exception($e);
    }
  }

  function ValidarCorrelativoDocumento($data)
  {
    $resultado = "";

    if (strlen($data["IdComprobanteVenta"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
      return $resultado;
    }

    if (strlen($data["NumeroDocumento"]) > 0) {
      if (is_numeric($data["NumeroDocumento"])) {
        if (intval($data["NumeroDocumento"]) == 0) {
          $resultado = "El numero de documento " . $data["NumeroDocumento"] . " no puede ser 0. Debe ser mayor a cero." . "\n";
          return $resultado;
        }
      }
    }

    if (strlen($data["NumeroDocumento"]) > 0 && !is_numeric($data["NumeroDocumento"])) {
      $resultado = $resultado . "El numero de documento debe ser mayor a cero y numérico." . "\n";
    } else {
      $output = $this->mComprobanteVenta->ObtenerComprobanteVenta($data);

      if (count($output) > 0) //existe y es modificacion
      {
        $resultado2 = $output[0];
        if ($resultado2["IdTipoDocumento"] == $data["IdTipoDocumento"] && $resultado2["SerieDocumento"] == $data["SerieDocumento"]  && $resultado2["NumeroDocumento"] == $data["NumeroDocumento"]  && $resultado2["FechaEmision"] == $data["FechaEmision"]) {
          //$resultado = $resultado."NO hay cambios \n";
          return $resultado;
        }
      } else {
        $resultado3 = $this->mComprobanteVenta->ObtenerComprobanteVentaPorSerieDocumento($data);
        if ($resultado3 != null) {
          $resultado = $resultado . "El número de documento ya existe en otro comprobante de venta" . "\n";
          return $resultado;
        }
      }
    }

    $parametro1 = $this->sConstanteSistema->ObtenerParametroAplicarOrdenCorrelatividad();
    if ($parametro1 == "1") {
      $objeto1 = $this->mComprobanteVenta->ObtenerFechaMayor($data);
      $objeto2 = $this->mComprobanteVenta->ObtenerFechaMenor($data);
      $fechamayor = $objeto1->FechaEmisionMayor;
      $fechamenor = $objeto2->FechaEmisionMenor;

      if (strlen($fechamayor) != 0 && strlen($fechamenor) != 0) {
        if (!($data["FechaEmision"] >= $fechamenor && $data["FechaEmision"] <= $fechamayor))
          $resultado = $resultado . "La fecha emisión debe ser entre " . $fechamenor . " al " . $fechamayor . " \n";
      } elseif (strlen($fechamayor) != 0) {
        if (!($data["FechaEmision"] <= $fechamayor))
          $resultado = $resultado . "La fecha emisión debe ser menor o igual al " . $fechamayor . " \n";
      } elseif (strlen($fechamenor) != 0) {
        if (!($data["FechaEmision"] >= $fechamenor))
          $resultado = $resultado . "La fecha emisión debe ser mayor o igual al " . $fechamenor . " \n";
      } else {
        $resultado = $resultado . "La fecha emisión debe ser mayor o igual al " . $fechamenor . " \n";
      }
    }

    return $resultado;
  }

  function ActualizarDetalleComprobanteVenta($data)
  {
    //foreach ($data as $key => $value) {
    return $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($data);
    //}
    //return $data;
  }

  function TotalizarMontosComprobanteVenta($data)
  {
    $dataDetalles = $data["DetallesComprobanteVenta"];
    foreach ($dataDetalles as $key => $value) {
      if ($value["IdProducto"] != null) {

        if (array_key_exists("UnidadesProducto", $dataDetalles[$key])) {
          $dataDetalles[$key]["UnidadesProducto"] = (is_string($value["UnidadesProducto"])) ? str_replace(',', "", $value["UnidadesProducto"]) : $value["UnidadesProducto"];
          $dataDetalles[$key]["EspesorProducto"] = (is_string($value["EspesorProducto"])) ? str_replace(',', "", $value["EspesorProducto"]) : $value["EspesorProducto"];
          $dataDetalles[$key]["AnchoProducto"] = (is_string($value["AnchoProducto"])) ? str_replace(',', "", $value["AnchoProducto"]) : $value["AnchoProducto"];
          $dataDetalles[$key]["LargoProducto"] = (is_string($value["LargoProducto"])) ? str_replace(',', "", $value["LargoProducto"]) : $value["LargoProducto"];
        }

        $dataDetalles[$key]["Cantidad"] = (is_string($value["Cantidad"])) ? str_replace(',', "", $value["Cantidad"]) : $value["Cantidad"];
        $dataDetalles[$key]["PrecioUnitario"] = (is_string($value["PrecioUnitario"])) ? (str_replace(',', "", $value["PrecioUnitario"])) : $value["PrecioUnitario"];
        $dataDetalles[$key]["DescuentoUnitario"] = (is_string($value["DescuentoUnitario"])) ? str_replace(',', "", $value["DescuentoUnitario"]) : $value["DescuentoUnitario"];

        //AGREGADOS
        $dataDetalles[$key]["ValorUnitario"] = (is_string($value["ValorUnitario"])) ? str_replace(',', "", $value["ValorUnitario"]) : $value["ValorUnitario"];
        $dataDetalles[$key]["ValorVentaItem"] = (is_string($value["ValorVentaItem"])) ? str_replace(',', "", $value["ValorVentaItem"]) : $value["ValorVentaItem"];
        $dataDetalles[$key]["ISCItem"] = (is_string($value["ISCItem"])) ? str_replace(',', "", $value["ISCItem"]) : $value["ISCItem"];
        $dataDetalles[$key]["IGVItem"] = (is_string($value["IGVItem"])) ? str_replace(',', "", $value["IGVItem"]) : $value["IGVItem"];
        $dataDetalles[$key]["ValorReferencial"] = (is_string($value["ValorReferencial"])) ? str_replace(',', "", $value["ValorReferencial"]) : $value["ValorReferencial"];
        $dataDetalles[$key]["SubTotal"] = (is_string($value["SubTotal"])) ? str_replace(',', "", $value["SubTotal"]) : $value["SubTotal"];
        $dataDetalles[$key]["DescuentoItem"] = (is_string($value["DescuentoItem"])) ? str_replace(',', "", $value["DescuentoItem"]) : $value["DescuentoItem"];

        if (array_key_exists("DescuentoValorUnitario", $dataDetalles[$key])) {
          $dataDetalles[$key]["DescuentoValorUnitario"] = (is_string($value["DescuentoValorUnitario"])) ? str_replace(',', "", $value["DescuentoValorUnitario"]) : $value["DescuentoValorUnitario"];
        }
      }
    }

    $data["DetallesComprobanteVenta"] = $dataDetalles;

    $data["ValorVentaGravado"] = (is_string($data["ValorVentaGravado"])) ? str_replace(',', "", $data["ValorVentaGravado"]) : $data["ValorVentaGravado"];
    $data["ValorVentaNoGravado"] = (is_string($data["ValorVentaNoGravado"])) ? str_replace(',', "", $data["ValorVentaNoGravado"]) : $data["ValorVentaNoGravado"];
    $data["ValorVentaInafecto"] = (is_string($data["ValorVentaInafecto"])) ? str_replace(',', "", $data["ValorVentaInafecto"]) : $data["ValorVentaInafecto"];
    $data["ISC"] = (is_string($data["ISC"])) ? str_replace(',', "", $data["ISC"]) : $data["ISC"];
    $data["IGV"] = (is_string($data["IGV"])) ? str_replace(',', "", $data["IGV"]) : $data["IGV"];

    if (array_key_exists("ICBPER", $data)) {
      $data["ICBPER"] = (is_string($data["ICBPER"])) ? str_replace(',', "", $data["ICBPER"]) : $data["ICBPER"];
    }

    if (array_key_exists("MontoComision", $data)) {
      $data["MontoComision"] = (is_string($data["MontoComision"])) ? str_replace(',', "", $data["MontoComision"]) : $data["MontoComision"];
    }

    if (array_key_exists("KilometrajeVehiculo", $data))
      $data["KilometrajeVehiculo"] = (is_string($data["KilometrajeVehiculo"])) ? str_replace(',', "", $data["KilometrajeVehiculo"]) : $data["KilometrajeVehiculo"];

    $data["DescuentoTotalItem"] = (is_string($data["DescuentoTotalItem"])) ? str_replace(',', "", $data["DescuentoTotalItem"]) : $data["DescuentoTotalItem"];
    $data["Total"] = (is_string($data["Total"])) ? str_replace(',', "", $data["Total"]) : $data["Total"];
    $data["SaldoNotaCredito"] = $data["Total"];
    $data["MontoACuenta"] = (is_string($data["MontoACuenta"])) ? str_replace(',',"",$data["MontoACuenta"]) : $data["MontoACuenta"];
    $data["MontoPendientePago"] = (is_string($data["MontoPendientePago"])) ? str_replace(',',"",$data["MontoPendientePago"]) : $data["MontoPendientePago"];
    $data["MontoEnvioGestion"] = (is_string($data["MontoEnvioGestion"])) ? str_replace(',',"",$data["MontoEnvioGestion"]) : $data["MontoEnvioGestion"];
    $data["TotalConEnvioGestion"] = (is_string($data["TotalConEnvioGestion"])) ? str_replace(',',"",$data["TotalConEnvioGestion"]) : $data["TotalConEnvioGestion"];
    $data["MontoRecibido"] = (is_string($data["MontoRecibido"])) ? str_replace(',',"",$data["MontoRecibido"]) : $data["MontoRecibido"];
    $data["VueltoRecibido"] = (is_string($data["VueltoRecibido"])) ? str_replace(',',"",$data["VueltoRecibido"]) : $data["VueltoRecibido"];
    
    //AÑADIDOS
    $data["DescuentoGlobal"] = (is_string($data["DescuentoGlobal"])) ? str_replace(',', "", $data["DescuentoGlobal"]) : $data["DescuentoGlobal"];
    $data["OtroTributo"] = (is_string($data["OtroTributo"])) ? str_replace(',', "", $data["OtroTributo"]) : $data["OtroTributo"];
    $data["OtroCargo"] = (is_string($data["OtroCargo"])) ? str_replace(',', "", $data["OtroCargo"]) : $data["OtroCargo"];
    $data["Porcentaje"] = (is_string($data["Porcentaje"])) ? str_replace(',', "", $data["Porcentaje"]) : $data["Porcentaje"];

    if ($data["DescuentoGlobal"] > 0) {
      $data["CodigoTipoVariableDescuentoGlobal"] = "02";
      $IGV2= (1+$this->ObtenerTasaIGV());
      $data["DescuentoGlobalValorVenta"] = round($data["DescuentoGlobal"] / $IGV2, 2); //TODO: Cambiar valor IGV a 10
      $data["PorcentajeVariableDescuentoGlobal"] = ($data["Total"] == 0) ? 100 : round($data["DescuentoGlobalValorVenta"] / $data["Total"], 2);
      $data["MontoBaseImponibleVariableDescuentoGlobal"] = $data["ValorVentaGravado"] + $data["ValorVentaNoGravado"] + $data["ValorVentaInafecto"];
    }

    if (array_key_exists("MontoDetraccion", $data)) {
      $data["MontoDetraccion"] = (is_string($data["MontoDetraccion"])) ? str_replace(',', "", $data["MontoDetraccion"]) : $data["MontoDetraccion"];
    } else {
      $data["MontoDetraccion"] = 0;
      $data["EstadoDetraccion"] = "0";
    }

    if (array_key_exists("MontoRetencionIGV", $data)) {
      $data["MontoRetencionIGV"] = (is_string($data["MontoRetencionIGV"])) ? str_replace(',', "", $data["MontoRetencionIGV"]) : $data["MontoRetencionIGV"];
    } else {
      $data["BaseImponibleRetencionIGV"] = 0;
      $data["MontoRetencionIGV"] = 0;
      $data["EstadoRetencionIGV"] = "0";
    }

    return $data;
  }


  function ObtenerMinimoMaximoFechaEmisionComprobanteVenta()
  {
    $resultado = $this->mComprobanteVenta->ObtenerMinimoMaximoFechaEmisionComprobanteVenta();
    return $resultado;
  }

  function ObtenerComprobanteVentaPorIdComprobante($data)
  {
    $output = $this->mComprobanteVenta->ObtenerComprobanteVenta($data);
    $resultado = $output[0];
    return $resultado;
  }

  function ValidarComprobanteVentaJSONPorSerieYNumero($data)
  {
    $resultado = $this->mComprobanteVenta->ValidarComprobanteVentaJSONPorSerieYNumero($data);
    return $resultado;
  }

  function ValidarComprobanteVentaEliminadoJSONPorSerieYNumero($data)
  {
    $resultado = $this->mComprobanteVenta->ValidarComprobanteVentaEliminadoJSONPorSerieYNumero($data);
    return $resultado;
  }

  function ConsultaComprobantesVentaParaJSON()
  {
    $resultado = $this->mComprobanteVenta->ConsultaComprobantesVentaParaJSON();
    return $resultado;
  }

  function TotalSaldosGuiaRemisionRemitenteEnDetalles($data)
  {
    $resultado = $this->mComprobanteVenta->TotalSaldosGuiaRemisionRemitenteEnDetalles($data);
    return $resultado;
  }

  function ConsultarComprobantesVentaReferencia()
  {
    $data = array();
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaReferencia($data);
    return $resultado;
  }

  function ConsultarComprobantesVentaProforma()
  {
    $data = array();
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaProforma($data);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($value);
    }
    return $resultado;
  }

  function ConsultaProformasParaJSON()
  {
    $data = array();
    $resultado = $this->mComprobanteVenta->ConsultarComprobantesVentaProforma($data);
    // foreach ($resultado as $key => $value) {
    //   $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($value);
    // }
    return $resultado;
  }

  function ObtenerNumeroTotalVentasProforma($data)
  {
    $resultado = $this->mComprobanteVenta->ObtenerNumeroTotalVentasProforma($data);
    return $resultado;
  }

  function ConsultarVentasProformas($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mComprobanteVenta->ConsultarVentasProformas($data, $numerofilainicio, $numerofilasporpagina);
    return $resultado;
  }


  function ValidarCuotasPago($data)
  {
    $resultado = "";

    if (array_key_exists("MontoDetraccion", $data)) {
      $MontoDetraccion = (is_string($data["MontoDetraccion"])) ? str_replace(',', "", $data["MontoDetraccion"]) : $data["MontoDetraccion"];
    } else {
      $MontoDetraccion = 0;
    }

    if (array_key_exists("MontoRetencionIGV", $data)) {
      $MontoRetencionIGV = (is_string($data["MontoRetencionIGV"])) ? str_replace(',', "", $data["MontoRetencionIGV"]) : $data["MontoRetencionIGV"];
    } else {
      $MontoRetencionIGV = 0;
    }

    $TotalVenta = str_replace(',', "", $data["Total"]);
    $MontoAPagar = $TotalVenta - $MontoDetraccion - $MontoRetencionIGV;
    $TotalMontoCuota = 0;
    
   
      if (array_key_exists("CuotasPagoClienteComprobanteVenta", $data)) {
        if (count($data["CuotasPagoClienteComprobanteVenta"]) > 0) {
          foreach ($data["CuotasPagoClienteComprobanteVenta"] as $key => $value) {
            $FechaCuota = $value["FechaPagoCuota"];
            $FechaEmision = $data["FechaEmision"];
            if (convertToDate($FechaCuota) <= $FechaEmision) {
              $FechaEmisionES =  convertirFechaES($FechaEmision);
              $resultado = $resultado . "La fecha de cuota $FechaCuota debe ser mayor a la fecha de emisión $FechaEmisionES <br>";
            }
            $MontoCuota = str_replace(',', "", $value["MontoCuota"]);
            $TotalMontoCuota = $TotalMontoCuota + $MontoCuota;
          }

          if ($TotalMontoCuota > $MontoAPagar) {
            $resultado = $resultado . "El monto Total de Cuotas (S/ $TotalMontoCuota) no debe de superar el Total de la Venta: (S/ $MontoAPagar)";
          }
        }
      }
    

    return $resultado;
  }
  function ConsultarComprobantesGuia($data) {
    $r = $this->mComprobanteVenta->ConsultarComprobantesGuia($data);
    foreach($r as $k=>$v) {
      $d = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaPorIdComprobanteVenta($v);
      $r[$k]["DetalleComprobanteVenta"] = $d;
    }

    return $r;    
  }
}
