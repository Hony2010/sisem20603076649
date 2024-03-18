<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionBoletaTipoZVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sBoletaTipoZVenta");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$BoletaVenta = $this->sBoletaTipoZVenta->CargarBoleta();
		$Cliente=$this->sCliente->Cargar();
		$Mercaderia=$this->sMercaderia->Cargar();
		$Marcas = $this->sMarca->ListarMarcas();
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();
		
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
			"TipoCambio" => $BoletaVenta["ParametroTipoCambio"],
			"MargenUtilidad" => $BoletaVenta["ParametroMargenUtilidad"],
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
				'BoletaVenta' => $BoletaVenta,
				'ComprobanteVentaNuevo' => $BoletaVenta,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia,
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,				
				'Mercaderias' => array(),
				'ComprobantesVentaProforma' => array(),
				'CasillerosPorGenero' => array('Masculino' => [], 'Femenino' => [] )
				)
		);

    	$view_data['data'] = $data;
		$view_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$view_extra['view_mainpanel_modal_vehiculocliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_modal_vehiculocliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$view_extra,true);
		$views['view_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);
		$views['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia','',true);
		$views['view_modal_buscador_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_externo_mercaderia','',true);

		$view_form['view_form_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_form_boletaventa','',true);
		$view_form['view_panel_header_boletaventa'] = $this->load->View('Venta/BoletaVenta/view_panel_header_boletaventa','',true);

		$views['view_tipoventa_boletaventa'] = $this->load->View('Venta/EmisionBoletaTipoZVenta/view_tipoventa_boletatipozventa','',true);
		$views['view_panel_boletaventa'] = $this->load->View('Venta/EmisionBoletaTipoZVenta/view_panel_boletatipozventa',$view_form,true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionBoletaTipoZVenta/view_footer_boletatipozventa',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionBoletaTipoZVenta/view_content_boletatipozventa',$views,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

}
