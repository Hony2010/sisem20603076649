<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cConsultaComprobanteVenta extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("Venta/sFacturaVenta");
		$this->load->service("Venta/sBoletaVenta");
		$this->load->service("Venta/sOrdenPedido");
		$this->load->service("Venta/sNotaCredito");
		$this->load->service("Venta/sNotaDebito");
		$this->load->service("Venta/sBoletaViajeVenta");
		$this->load->service("Venta/sProforma");
		$this->load->service("Configuracion/Venta/sTipoVenta");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
	}

	public function Index() {	
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["IdGenero"] = '%';
		$input["NombreCasillero"] = '';
		$input["FechaInicio"] = $fechaservidor;
		$input["FechaFin"] = $fechaservidor;
		$input["TipoVenta"] = "%";
		$input["TipoDocumento"] = "%";
		$input["textofiltro"] = '';
		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sComprobanteVenta->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($input);

		$ComprobantesVenta = $this->sComprobanteVenta->ConsultarComprobantesVenta($input, $input["pagina"], $input["numerofilasporpagina"]); //$pagina

		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$ComprobanteVenta = $this->sComprobanteVenta->Cargar($parametro);


		$FacturaVenta = $this->sFacturaVenta->CargarVenta();
		$BoletaVenta = $this->sBoletaVenta->CargarBoleta();
		$OrdenPedido = $this->sOrdenPedido->CargarOrdenPedido();
		$NotaCredito = $this->sNotaCredito->CargarNotaCredito();
		$NotaDebito = $this->sNotaDebito->CargarNotaDebito();
		$BoletaViajeVenta = $this->sBoletaViajeVenta->CargarBoletaViaje();
		$Proforma = $this->sProforma->CargarProforma();

		$ComprobanteVenta = $this->sComprobanteVenta->Cargar($parametro);
		$Cliente = $this->sCliente->Cargar();
		$Mercaderia = $this->sMercaderia->Cargar();
		$input["FechaInicio"] = date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"] = date("d/m/Y", strtotime($input["FechaFin"]));
		$input["TiposVenta"] = $this->sTipoVenta->ListarTiposVenta();
		$input["IdTipoVenta"] = "";
		$input["TipoVenta"] = "";

		$input_nota["textofiltro"] = '';
		$input_nota["FechaInicio"] = $this->Base->ObtenerFechaServidor("d/m/Y");
		$input_nota["FechaFin"] = $this->Base->ObtenerFechaServidor("d/m/Y");
		$input_nota["FechaHoy"] = $this->Base->ObtenerFechaServidor("d/m/Y");
		$input_nota["IdPersona"] = 3;
		$input_nota["IdTipoDocumento"] = 3;
		$input_nota["IdMoneda"] = 3;
		$input_nota["IdTipoVenta"] = 1;

		$input["IdModuloSistema"] = ID_MODULO_VENTA;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$input["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input, 0);

		$MotivosNotaCredito = file_get_contents(BASE_PATH . 'assets/data/venta/reglamotivonotacredito.json');
		$CamposNotaCredito = file_get_contents(BASE_PATH . 'assets/data/venta/reglacamposnotacredito.json');
		$MotivosNotaDebito = file_get_contents(BASE_PATH . 'assets/data/venta/reglamotivonotadebito.json');
		$CamposNotaDebito = file_get_contents(BASE_PATH . 'assets/data/venta/reglacamposnotadebito.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH . 'assets/data/documentos/documentos.json'));
		$fechahoy = convertToDate($this->Base->ObtenerFechaServidor());
		
		$Marcas = $this->sMarca->ListarMarcas();
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();

		$dataFiltrosBusquedaVentas = array(
			"TextoFiltro" =>"",
			"FechaInicio" =>convertToDate($fechahoy,'d/m/Y'),
			"FechaFin" =>convertToDate($fechahoy,'d/m/Y'),
			"IdUsuarioVendedor"  =>"%",
			"Vendedores" => $BoletaVenta["Vendedores"],
			"pagina" => 1,
			"numerofilasporpagina" => '10',
			"paginadefecto" => 1,
			"totalfilas" => '0',
			"ClienteProforma"=>"",
			"EstadoComprobante"=>"",
			"IdCliente"=>""
		);

		$BusquedaAvanzadaProducto = array(
			"textofiltro" => '',
			"NombreMarca" => '',
			"NombreFamiliaProducto" => '',
			"CheckConSinStock" => false,
			"CodigoMercaderia2" => '',
			"CodigoAlterno" => '',
			"NombreLineaProducto" => '',
			"NombreProducto" => '',
			"Referencia" => '',
			"StockMercaderia" => '',
			"CostoUnitarioCompra" => '',
			"PrecioUnitarioCompra" => '',
			"PrecioSugerido" => '',
			"RazonSocialProveedor" => '',
			"FechaIngreso" => '',
			"TipoCambio" => '',
			"MargenUtilidad" => '',
			"Aplicacion" => '',	
			"Familias" =>$FamiliasProducto,					
			"Marcas" => $Marcas,
			"IdFamiliaProducto" =>"%",
			"IdMarca"=>"%",
			'Mercaderias'  => array(),
			"totalfilas" => 0
		);

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'ComprobantesVenta' => $ComprobantesVenta,
				'ComprobanteVenta' => $ComprobanteVenta,
				'FacturaVenta' => $FacturaVenta,
				'BoletaVenta' => $BoletaVenta,
				'OrdenPedido' => $OrdenPedido,
				'FiltrosNC' => $input_nota,
				'FiltrosND' => $input_nota,
				'NotaCredito' => $NotaCredito,
				'NotaDebito' => $NotaDebito,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia,
				'TiposDocumentoCompleto' => $TiposDocumento,
				'TiposDocumento' => array(),
				'MotivosNotaCredito' => json_decode($MotivosNotaCredito),
				'CamposNotaCredito' => json_decode($CamposNotaCredito),
				'MotivosNotaDebito' => json_decode($MotivosNotaDebito),
				'CamposNotaDebito' => json_decode($CamposNotaDebito),
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,
				'Mercaderias' => array(),
				'ComprobantesVentaReferenciaClinica' => array(),
				'ComprobantesVentaProforma' => array(),
				'BoletaViajeVenta' => $BoletaViajeVenta,
				'Proforma' => $Proforma,
				'CasillerosPorGenero' => array('Masculino' => [], 'Femenino' => [] ),
				'BusquedaProformaVenta' => array(
					'FiltrosBusquedaVentas' => $dataFiltrosBusquedaVentas,
					'ComprobantesVentaProforma' => array(),
				)
			)
		);

		$view_data['data'] = $data;
		$views_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente', '', true);
		$views_extra['view_mainpanel_modal_vehiculocliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_modal_vehiculocliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente', $views_extra, true);
		$views['view_modal_cuotapagoclientecomprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_modal_cuotapagoclientecomprobanteventa','',true);
		$views['view_modal_preview_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente', '', true);
	
		$views['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia', '', true);
		$views['view_modal_buscador_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_externo_mercaderia', '', true);
		$views['view_modal_buscador_mercaderia_lista'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_externo_mercaderia_lista', '', true);
		$views['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);
		$views['view_modal_buscadorproforma'] = $this->load->View('Venta/Proforma/view_modal_buscadorproforma', '', true);
		
		$views['view_modal_casilleroporgenero'] = $this->load->View('Venta/ComprobanteVenta/view_modal_casilleroporgenero', '', true);

		if ($FacturaVenta["ParametroVistaVenta"] == 1) {
			$views_fv['view_form_facturaventa'] = $this->load->View('Venta/FacturaVenta/view_form_facturaventa', '', true);
		} else {
			$views_fv['view_form_facturaventa'] = $this->load->View('Venta/FacturaVenta/view_form_facturaventarapida', '', true);
		}
		$views_fv['view_panel_header_facturaventa'] = $this->load->View('Venta/FacturaVenta/view_panel_header_facturaventa', '', true);
		$views['view_modal_facturaventa'] = $this->load->View('Venta/FacturaVenta/view_modal_facturaventa', $views_fv, true);

		if ($BoletaVenta["ParametroVistaVenta"] == 1) {
			$views_bv['view_form_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_form_boletaventa', '', true);
		} else {
			$views_bv['view_form_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_form_boletaventarapida', '', true);
		}
		$views_bv['view_panel_header_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_panel_header_boletaventa', '', true);
		$views['view_modal_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_modal_boletaventa', $views_bv, true);

		$views_op['view_form_ordenpedido'] = $this->load->View('Venta/OrdenPedido/view_form_ordenpedido', '', true);
		$views_op['view_panel_header_ordenpedido'] = $this->load->View('Venta/OrdenPedido/view_panel_header_ordenpedido', '', true);
		$views['view_modal_ordenpedido'] = $this->load->View('Venta/OrdenPedido/view_modal_ordenpedido', $views_op, true);

		$views_bvv['view_form_boletaventa'] = $this->load->View('Venta/BoletaViajeVenta/view_form_boletaviajeventa', '', true);
		$views_bvv['view_panel_header_boletaventa'] = $this->load->View('Venta/BoletaViajeVenta/view_panel_header_boletaviajeventa', '', true);
		$views['view_modal_boletaviajeventa'] = $this->load->View('Venta/BoletaViajeVenta/view_modal_boletaviajeventa', $views_bvv, true);

		$views_pf['view_form_proforma'] = $this->load->View('Venta/Proforma/view_form_proforma', '', true);
		$views_pf['view_panel_header_proforma'] = $this->load->View('Venta/Proforma/view_panel_header_proforma', '', true);
		$views['view_modal_proforma'] = $this->load->View('Venta/Proforma/view_modal_proforma', $views_pf, true);

		$view_busqueda_nc['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_buscador_comprobantesventa', '', true);
		$view_busqueda_nc['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_paginacion_comprobantesventa', '', true);
		$views['view_modal_buscador_notacreditoventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_comprobantesventa', $view_busqueda_nc, true);

		$view_busqueda_nd['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaND/view_mainpanel_subcontent_modal_buscador_comprobantesventa', '', true);
		$view_busqueda_nd['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaND/view_mainpanel_subcontent_modal_paginacion_comprobantesventa', '', true);
		$views['view_modal_buscador_notadebitoventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaND/view_mainpanel_subcontent_modal_comprobantesventa', $view_busqueda_nd, true);

		$views_nc['view_form_notacreditoventa'] = $this->load->View('Venta/NotaCredito/view_mainpanel_subcontent_form_notacredito', '', true);
		$views_nc['view_panel_header_notacreditoventa'] = $this->load->View('Venta/NotaCredito/view_mainpanel_subcontent_form_header_notacredito', '', true);
		$views['view_modal_notacreditoventa'] = $this->load->View('Venta/NotaCredito/view_modal_notacredito', $views_nc, true);

		$views_nv['view_form_notadevolucionventa'] = $this->load->View('Venta/NotaDevolucion/view_mainpanel_subcontent_form_notadevolucion', '', true);
		$views_nv['view_panel_header_notadevolucionventa'] = $this->load->View('Venta/NotaDevolucion/view_mainpanel_subcontent_form_header_notadevolucion', '', true);
		$views['view_modal_notadevolucionventa'] = $this->load->View('Venta/NotaDevolucion/view_modal_notadevolucion', $views_nv, true);

		$views_nd['view_form_notadebitoventa'] = $this->load->View('Venta/NotaDebito/view_mainpanel_subcontent_form_notadebito', '', true);
		$views_nd['view_panel_header_notadebitoventa'] = $this->load->View('Venta/NotaDebito/view_mainpanel_subcontent_form_header_notadebito', '', true);
		$views['view_modal_notadebitoventa'] = $this->load->View('Venta/NotaDebito/view_modal_notadebito', $views_nd, true);

		$views['view_buscador_consultacomprobanteventa'] = $this->load->View('Venta/ConsultaComprobanteVenta/view_buscador_consultacomprobanteventa', '', true);
		$views['view_tabla_consultacomprobanteventa'] = $this->load->View('Venta/ConsultaComprobanteVenta/view_tabla_consultacomprobanteventa', '', true);
		$views['view_paginacion_consultacomprobanteventa'] = $this->load->View('Venta/ConsultaComprobanteVenta/view_paginacion_consultacomprobanteventa', "", true);

		$view_form['view_form_comprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_mainpanel_subcontent_form_comprobanteventa', "", true);
		$view_form['view_panel_header_comprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_mainpanel_subcontent_panel_header_comprobanteventa', '', true);
		$views['view_modal_comprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_mainpanel_subcontent_modal_comprobanteventa', $view_form, true);
		$view['view_footer_extension'] = $this->load->View('Venta/ConsultaComprobanteVenta/view_footer_consultacomprobanteventa', $view_data, true);
		$view['view_content_min'] =  $this->load->View('Venta/ConsultaComprobanteVenta/view_content_consultacomprobanteventa', $views, true);

		$this->load->View('.Master/master_view_mainpanel_min', $view);
	}

	public function ConsultarComprobantesVenta()
	{
		$input = $this->input->get("Data");
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sComprobanteVenta->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($input);
		$output["resultado"] = $this->sComprobanteVenta->ConsultarComprobantesVenta($input, $input["pagina"], $numerofilasporpagina);
		$output["Filtros"] = array_merge(
			$input,
			array(
				"numerofilasporpagina" => $numerofilasporpagina,
				"totalfilas" => $TotalFilas,
				"paginadefecto" => 1
			)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarComprobantesVentaPorPagina()
	{
		$input = $this->input->get("Data");		
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$resultado = $this->sComprobanteVenta->ConsultarComprobantesVenta($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
	
}
