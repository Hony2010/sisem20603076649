<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRegistroComprobanteCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sProveedor");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->library('sesionusuario');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$ComprobanteCompra =$this->sComprobanteCompra->Cargar($parametro);
		$Mercaderia=$this->sMercaderia->Cargar();
		$Proveedor=$this->sProveedor->Cargar();

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
				'ComprobanteCompra' => $ComprobanteCompra,
				'ComprobanteCompraNuevo' => $ComprobanteCompra,
				'Proveedor'  => $Proveedor,
				'Mercaderia'  => $Mercaderia,
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,				
			)
		);

    	$view_data['data'] = $data;
		$view_subsubcontent_extra['view_subcontent_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$view_subcontent['view_subcontent_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$view_subsubcontent_extra,true);
		$view_subcontent['view_subcontent_modal_preview_foto_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);
		$view_subcontent['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia','',true);
		$view_subcontent['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);

		$view_subcontent_buscador['view_subcontent_modal_buscador_documentoingreso'] = $this->load->View('Compra/BusquedaDocumentoIngreso/view_mainpanel_subcontent_modal_buscador_documentoingreso','',true);
		$view_subcontent['view_subcontent_modal_content_documentoingreso'] = $this->load->View('Compra/BusquedaDocumentoIngreso/view_mainpanel_subcontent_modal_content_documentoingreso',$view_subcontent_buscador,true);

		$view_subcontent_panel['view_subcontent_form_comprobantecompra'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_form_comprobantecompra','',true);
		$view_subcontent_panel['view_subcontent_panel_header_comprobantecompra'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_panel_header_comprobantecompra','',true);
		$view_subcontent['view_subcontent_panel_comprobantecompra'] = $this->load->View('Compra/RegistroComprobanteCompra/view_mainpanel_subcontent_panel_comprobantecompra',$view_subcontent_panel,true);
		$view_footer_extension['view_footer_extension'] = $this->load->View('Compra/RegistroComprobanteCompra/view_mainpanel_footer_comprobantecompra',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Compra/RegistroComprobanteCompra/view_mainpanel_content_comprobantecompra',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

	    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}
