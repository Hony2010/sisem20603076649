<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cEmisionOrdenPedido extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sOrdenPedido");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->model("Base");
	}

	public function Index()
	{
		$Marcas = $this->sMarca->ListarMarcas();
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();

		$OrdenPedido = $this->sOrdenPedido->CargarOrdenPedido();
		$Cliente = $this->sCliente->Cargar();
		$Mercaderia = $this->sMercaderia->Cargar();
		$fechahoy = convertToDate($this->Base->ObtenerFechaServidor());

		$dataFiltrosBusquedaVentas = array(
			"TextoFiltro" =>"",
			"FechaInicio" =>convertToDate($fechahoy,"d/m/Y"),
			"FechaFin" =>convertToDate($fechahoy,"d/m/Y"),
			"IdUsuarioVendedor"  =>"%",
			"Vendedores" => $OrdenPedido["Vendedores"],
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
			"TipoCambio" => $OrdenPedido["ParametroTipoCambio"],
			"MargenUtilidad" => $OrdenPedido["ParametroMargenUtilidad"],
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
				'OrdenPedido' => $OrdenPedido,
				'ComprobanteVentaNuevo' => $OrdenPedido,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia,
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,
				'Mercaderias' => array(),
				'ComprobantesVentaReferenciaClinica' => array(),
				'ComprobantesVentaProforma' => array(),
				'CasillerosPorGenero' => array('Masculino' => [], 'Femenino' => [] ),
				'FiltrosBusquedaVentas' =>$dataFiltrosBusquedaVentas,
				'BusquedaProformaVenta' => array(
					'FiltrosBusquedaVentas' => $dataFiltrosBusquedaVentas,
					'ComprobantesVentaProforma' => array()
				)
			)
		);

		$view_data['data'] = $data;
		$view_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente', '', true);
		$view_extra['view_mainpanel_modal_vehiculocliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_modal_vehiculocliente','',true);
		
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente', $view_extra, true);
		$views['view_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente', '', true);
		$views['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia', '', true);
		$views['view_modal_buscador_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_externo_mercaderia', '', true);
		$views['view_modal_buscador_mercaderia_lista'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_externo_mercaderia_lista', '', true);
		$views['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);
		$views['view_modal_casilleroporgenero'] = $this->load->View('Venta/ComprobanteVenta/view_modal_casilleroporgenero', '', true);
		$views['view_modal_buscadorproforma'] = $this->load->View('Venta/Proforma/view_modal_buscadorproforma', '', true);
	

		$view_form['view_form_ordenpedido'] = $this->load->View('Venta/OrdenPedido/view_form_ordenpedido', '', true);
		$view_form['view_panel_header_ordenpedido'] = $this->load->View('Venta/OrdenPedido/view_panel_header_ordenpedido', '', true);

		$views['view_tipoventa_ordenpedido'] = $this->load->View('Venta/EmisionOrdenPedido/view_tipoventa_ordenpedido', '', true);
		$views['view_panel_ordenpedido'] = $this->load->View('Venta/EmisionOrdenPedido/view_panel_ordenpedido', $view_form, true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionOrdenPedido/view_footer_ordenpedido', $view_data, true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionOrdenPedido/view_content_ordenpedido', $views, true);

		$this->load->View('.Master/master_view_mainpanel_min', $view);
	}
}
