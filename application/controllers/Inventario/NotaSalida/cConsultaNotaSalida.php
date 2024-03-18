<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConsultaNotaSalida extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->service("Inventario/sNotaSalida");
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

	public function Index()
	{
		$fechaservidor=$this->Base->ObtenerFechaServidor("Y-m-d");
		$input["FechaInicio"]=$fechaservidor;
		$input["FechaFin"]=$fechaservidor;
		$input["textofiltro"]='';
		$input["TipoDocumento"]="%";
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = $this->sNotaSalida->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["totalfilas"] = $this->sNotaSalida->ObtenerNumeroTotalNotasSalida($input);

		$Marcas = $this->sMarca->ListarMarcas();
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();

		$NotasSalida = $this->sNotaSalida->ConsultarNotasSalida($input,$input["pagina"],$input["numerofilasporpagina"]);//$pagina
		$NotaSalida=$this->sNotaSalida->Cargar();
		$Proveedor=$this->sProveedor->Cargar();

		$input["FechaInicio"]=date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"]=date("d/m/Y", strtotime($input["FechaFin"]));


		$modulo["IdModuloSistema"] = ID_MODULO_COMPRA;
		$excluir = ID_TIPODOCUMENTO_NOTADEBITO.", ".ID_TIPODOCUMENTO_NOTACREDITO;
		$documentosbymodulo = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($modulo, $excluir);

		//AQUI OBTENEMOSLOS DATOS JSON
		$Motivos = file_get_contents(BASE_PATH.'assets/data/inventario/notasalida/reglamotivonotasalida.json');
		$Campos = file_get_contents(BASE_PATH.'assets/data/inventario/notasalida/reglacamposnotasalida.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json'));

		$TiposDocumento->TiposDocumentoVenta = $TiposDocumento->TiposDocumentoCompra;
		$TiposDocumento->TiposDocumentoCompra= (array)$documentosbymodulo;

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
			"TipoCambio" => $NotaSalida["ParametroTipoCambio"],
			"MargenUtilidad" => $NotaSalida["ParametroMargenUtilidad"],
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
				'Filtros' => $input,
				'NotasSalida'=> $NotasSalida,
				'NotaSalida' => $NotaSalida,
				'Proveedor'  => $Proveedor,
				'TiposDocumento' => $TiposDocumento,
				'Motivos' => json_decode($Motivos),
				'Campos' => json_decode($Campos),
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto
				)
		);

    $view_data['data'] = $data;
		$views_extra['view_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$views['view_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$views_extra,true);
		$views['view_modal_preview_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);

		// $views_cv['view_form_facturaventa'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_form_comprobantecompra','',true);
		// $views_cv['view_panel_header_facturaventa'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_panel_header_comprobantecompra','',true);
		// $views['view_modal_facturaventa'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_modal_comprobantecompra',$views_fv,true);
		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_buscador_comprobantesventa','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_paginacion_comprobantesventa','',true);
		$views['view_subcontent_modal_comprobantesventa'] =  $this->load->View('Inventario/BusquedaComprobante/view_mainpanel_subcontent_modal_comprobantesventa',$view_subcontent_buscador_paginacion,true);

		$views['view_buscador_consultanotasalida'] = $this->load->View('Inventario/ConsultaNotaSalida/view_buscador_consultanotasalida','',true);
		$views['view_tabla_consultanotasalida'] = $this->load->View('Inventario/ConsultaNotaSalida/view_tabla_consultanotasalida','',true);
		$views['view_paginacion_consultanotasalida'] = $this->load->View('Inventario/ConsultaNotaSalida/view_paginacion_consultanotasalida',"",true);

		$view_form['view_form_notasalida'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_form_notasalida',"",true);
		$view_form['view_panel_header_notasalida'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_panel_header_notasalida','',true);
		$views['view_modal_notasalida'] = $this->load->View('Inventario/NotaSalida/view_mainpanel_subcontent_modal_notasalida',$view_form,true);

		$view_footer['view_footer_extension'] = $this->load->View('Inventario/ConsultaNotaSalida/view_footer_consultanotasalida',$view_data,true);


		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Inventario/ConsultaNotaSalida/view_content_consultanotasalida',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$view['view_option_mobile'] ="";
    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarNotasSalida()
	{
		$input = $this->input->get("Data");
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sNotaSalida->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sNotaSalida->ObtenerNumeroTotalNotasSalida($input);

		$output["resultado"] = $this->sNotaSalida->ConsultarNotasSalida($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarNotasSalidaPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$resultado = $this->sNotaSalida->ConsultarNotasSalida($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
