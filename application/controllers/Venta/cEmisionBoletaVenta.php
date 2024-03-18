<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cEmisionBoletaVenta extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sBoletaVenta");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
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

		$BoletaVenta = $this->sBoletaVenta->CargarBoleta();
		$Cliente = $this->sCliente->Cargar();
		$Mercaderia = $this->sMercaderia->Cargar();
		$fechahoy = convertToDate($this->Base->ObtenerFechaServidor());
		$Servicio=$this->sServicio->Inicializar();

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
			"TipoCambio" => $BoletaVenta["ParametroTipoCambio"],
			"MargenUtilidad" => $BoletaVenta["ParametroMargenUtilidad"],
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
				'BoletaVenta' => $BoletaVenta,
				'ComprobanteVentaNuevo' => $BoletaVenta,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia,
				'Servicio'  => $Servicio,
				'Vehiculo' => array(),
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,
				'Mercaderias' => array(),
				'CasillerosPorGenero' => array('Masculino' => [], 'Femenino' => [] ),
				'BusquedaProformaVenta' => array(
					'FiltrosBusquedaVentas' => $dataFiltrosBusquedaVentas,
					'ComprobantesVentaProforma' => array(),
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
		$views['view_modal_buscadorproforma'] = $this->load->View('Venta/Proforma/view_modal_buscadorproforma', '', true);


		if ($BoletaVenta["ParametroVistaVenta"] == 1) {
			$view_form['view_form_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_form_boletaventa', '', true);
		} else {
			$view_form['view_form_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_form_boletaventarapida', '', true);
		}

		$view_form['view_panel_header_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_panel_header_boletaventa', '', true);
		$views['view_tipoventa_boletaventa'] = $this->load->View('Venta/EmisionBoletaVenta/view_tipoventa_boletaventa', '', true);
		$views['view_panel_boletaventa'] = $this->load->View('Venta/EmisionBoletaVenta/view_panel_boletaventa', $view_form, true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionBoletaVenta/view_footer_boletaventa', $view_data, true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionBoletaVenta/view_content_boletaventa', $views, true);

		$this->load->View('.Master/master_view_mainpanel_min', $view);
	}
}
