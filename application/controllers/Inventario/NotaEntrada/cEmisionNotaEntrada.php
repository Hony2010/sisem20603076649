<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionNotaEntrada extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Inventario/sNotaEntrada");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$NotaEntrada =$this->sNotaEntrada->Cargar();

		$modulo["IdModuloSistema"] = ID_MODULO_COMPRA;
		$excluir = ID_TIPODOCUMENTO_NOTADEBITO.", ".ID_TIPODOCUMENTO_NOTACREDITO;
		$documentosbymodulo = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($modulo, $excluir);

		//AQUI OBTENEMOSLOS DATOS JSON
		$Motivos = file_get_contents(BASE_PATH.'assets/data/inventario/notaentrada/reglamotivonotaentrada.json');
		$Campos = file_get_contents(BASE_PATH.'assets/data/inventario/notaentrada/reglacamposnotaentrada.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json'));
		// print_r($Campos);
		// exit;
		$TiposDocumento->TiposDocumentoVenta = $TiposDocumento->TiposDocumentoCompra;
		$TiposDocumento->TiposDocumentoCompra= (array)$documentosbymodulo;

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

		$Cliente=$this->sCliente->Cargar();
		$data = array(
			"data" => array(
				'NotaEntrada' => $NotaEntrada,
				'NuevoNotaEntrada' => $NotaEntrada,
				'Cliente'  => $Cliente,
				'TiposDocumento' => $TiposDocumento,
				'Motivos' => json_decode($Motivos),
				'Campos' => json_decode($Campos),
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto,				
			)
		);

   		$view_data['data'] = $data;
		$view_subcontent_panel['view_subcontent_form_notaentrada'] = $this->load->View('Inventario/NotaEntrada/view_mainpanel_subcontent_form_notaentrada','',true);
		$view_subcontent_panel['view_subcontent_panel_header_notaentrada'] = $this->load->View('Inventario/NotaEntrada/view_mainpanel_subcontent_panel_header_notaentrada','',true);
		$view_subcontent['view_subcontent_panel_notaentrada'] = $this->load->View('Inventario/EmisionNotaEntrada/view_mainpanel_subcontent_panel_notaentrada',$view_subcontent_panel,true);
		$view_subcontent['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);

		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_buscador_comprobantesventa','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_paginacion_comprobantesventa','',true);
		$view_subcontent['view_subcontent_modal_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_comprobantesventa',$view_subcontent_buscador_paginacion,true);

		$view_footer_extension['view_footer_extension'] = $this->load->View('Inventario/EmisionNotaEntrada/view_mainpanel_footer_notaentrada',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Inventario/EmisionNotaEntrada/view_mainpanel_content_notaentrada',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

}
