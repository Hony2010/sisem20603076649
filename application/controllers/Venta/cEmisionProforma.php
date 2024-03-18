<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cEmisionProforma extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Venta/sProforma");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service("Catalogo/sServicio");
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

		$Proforma = $this->sProforma->CargarProforma();
		$Cliente = $this->sCliente->Cargar();
		$Servicio=$this->sServicio->Inicializar();
		$Mercaderia = $this->sMercaderia->Cargar();

		$fechahoy = convertToDate($this->Base->ObtenerFechaServidor());
		$dataFiltrosBusquedaVentas = array(
			"TextoFiltro" =>"",
			"FechaInicio" =>$fechahoy,
			"FechaFin" =>$fechahoy,
			"IdUsuarioVendedor"  =>"%",
			"Vendedores" => $Proforma["Vendedores"]
		);

		$BusquedaAvanzadaProducto = array(
			"textofiltro" => '',
			"NombreMarca" => '',
			"IdMarca"=> '',
			"IdFamiliaProducto"=> '',
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
			"TipoCambio" => $Proforma["ParametroTipoCambio"],
			"MargenUtilidad" => $Proforma["ParametroMargenUtilidad"],
			"Aplicacion" => '',	
			"Familias" =>$FamiliasProducto,					
			"Marcas" => $Marcas,
			"pagina" => 1,
			"numerofilasporpagina" => '10',
			"paginadefecto" => 1,			
			"IdFamiliaProducto" =>"%",
			"IdMarca"=>"%",
			'Mercaderias'  => array(),
			"totalfilas" => 0
		);

		$data = array(
			"data" => array(
				'Proforma' => $Proforma,
				'ComprobanteVentaNuevo' => $Proforma,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia,
				'Servicio'  => $Servicio,
				'ComprobantesVentaProforma' => array(),
				'CasillerosPorGenero' => array('Masculino' => [], 'Femenino' => [] ),
				'FiltrosBusquedaVentas' =>$dataFiltrosBusquedaVentas,
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto
			)
		);

		$view_data['data'] = $data;
		$view_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente', '', true);
		$view_extra['view_mainpanel_modal_vehiculocliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_modal_vehiculocliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente', $view_extra, true);
		$views['view_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente', '', true);

		$view_form['view_form_proforma'] = $this->load->View('Venta/Proforma/view_form_proforma', '', true);
		$views['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);
		$view_form['view_panel_header_proforma'] = $this->load->View('Venta/Proforma/view_panel_header_proforma', '', true);
		$views['view_panel_proforma'] = $this->load->View('Venta/EmisionProforma/view_panel_proforma', $view_form, true);
		$view_footer['view_footer_extension'] = $this->load->View('Venta/EmisionProforma/view_footer_proforma', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Venta/EmisionProforma/view_content_proforma', $views, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}
}
