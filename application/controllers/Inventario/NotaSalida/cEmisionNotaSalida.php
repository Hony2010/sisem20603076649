<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionNotaSalida extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Inventario/sNotaSalida");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$NotaSalida =$this->sNotaSalida->Cargar();

		//AQUI OBTENEMOSLOS DATOS JSON
		$Motivos = file_get_contents(BASE_PATH.'assets/data/inventario/notasalida/reglamotivonotasalida.json');
		$Campos = file_get_contents(BASE_PATH.'assets/data/inventario/notasalida/reglacamposnotasalida.json');
		$TiposDocumento = file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json');

		$Cliente=$this->sCliente->Cargar();

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
				'NotaSalida' => $NotaSalida,
				'NuevoNotaSalida' => $NotaSalida,
				'Cliente'  => $Cliente,
				'TiposDocumento' => json_decode($TiposDocumento),
				'Motivos' => json_decode($Motivos),
				'Campos' => json_decode($Campos),
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,
			)
		);

    	$view_data['data'] = $data;
		$view_subcontent_panel['view_subcontent_form_notasalida'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_form_notasalida','',true);
		$view_subcontent_panel['view_subcontent_panel_header_notasalida'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_panel_header_notasalida','',true);
		$view_subcontent['view_subcontent_panel_notasalida'] = $this->load->View('Inventario/EmisionNotaSalida/view_mainpanel_subcontent_panel_notasalida',$view_subcontent_panel,true);
		$view_subcontent['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);

		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_buscador_comprobantesventa','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_paginacion_comprobantesventa','',true);
		$view_subcontent['view_subcontent_modal_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_comprobantesventa',$view_subcontent_buscador_paginacion,true);

		$view_footer_extension['view_footer_extension'] = $this->load->View('Inventario/EmisionNotaSalida/view_mainpanel_footer_notasalida',$view_data,true);


		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Inventario/EmisionNotaSalida/view_mainpanel_content_notasalida',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}
