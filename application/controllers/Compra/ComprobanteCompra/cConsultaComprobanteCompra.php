<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConsultaComprobanteCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->service("Compra/sCompraGasto");
		$this->load->service("Compra/sCompraCostoAgregado");
		$this->load->service("Compra/sNotaCreditoCompra");
		$this->load->service("Compra/sNotaDebitoCompra");
		$this->load->service("Catalogo/sProveedor");
		$this->load->service("Configuracion/Compra/sTipoCompra");
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
		$input["pagina"]=1;
		$input["TipoDocumento"]='%';
		$input["TipoCompra"]='%';
		$input["numerofilasporpagina"] = $this->sComprobanteCompra->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["totalfilas"] = $this->sComprobanteCompra->ObtenerNumeroTotalComprobantesCompra($input);

		$ComprobantesCompra = $this->sComprobanteCompra->ConsultarComprobantesCompra($input,$input["pagina"],$input["numerofilasporpagina"]);//$pagina
		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$ComprobanteCompra=$this->sComprobanteCompra->Cargar($parametro);
		$Proveedor=$this->sProveedor->Cargar();
		$Mercaderia=$this->sMercaderia->Cargar();
		$CompraGasto = $this->sCompraGasto->CargarCompraGasto();
		$CompraCostoAgregado = $this->sCompraCostoAgregado->CargarCompraCostoAgregado();
		$NotaCreditoCompra =$this->sNotaCreditoCompra->CargarNotaCreditoCompra();
		$NotaDebitoCompra =$this->sNotaDebitoCompra->CargarNotaDebitoCompra();

		$input["FechaInicio"]=date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"]=date("d/m/Y", strtotime($input["FechaFin"]));

		$inputCostoAgregado["textofiltro"]='';
		$inputCostoAgregado["FechaInicio"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$inputCostoAgregado["FechaFin"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$inputCostoAgregado["FechaHoy"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$inputCostoAgregado["IdPersona"]=0;
		$inputCostoAgregado["IdTipoDocumento"]=ID_TIPO_DOCUMENTO_FACTURA;
		$inputCostoAgregado["IdTipoCompra"]=$CompraCostoAgregado["IdTipoCompra"];

		$input["TiposCompra"] = $this->sTipoCompra->ListarTiposCompra();
		$input["IdTipoCompra"] = "";
		$input["IdTipoDocumento"] = "";
		$input["IdModuloSistema"]=ID_MODULO_COMPRA;
		$input["TiposDocumentoCompra"]=$this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);

		//REGLAS NotaCreditoCompra
		$MotivosNotaCreditoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglamotivonotacredito.json');
		$CamposNotaCreditoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglacamposnotacredito.json');
		//AQUI OBTENEMOSLOS DATOS JSON
		$MotivosNotaDebitoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglamotivonotadebito.json');
		$CamposNotaDebitoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglacamposnotadebito.json');

		$TiposDocumento = json_decode(file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json'));

		$filtroNota["textofiltro"]='';
		$filtroNota["FechaInicio"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$filtroNota["FechaFin"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$filtroNota["FechaHoy"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$filtroNota["IdPersona"]=3;
		$filtroNota["IdTipoDocumento"]=3;
		$filtroNota["IdMoneda"]=3;
		$filtroNota["IdTipoCompra"]=2;

		
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
				'Filtros' => $input,
				'FiltrosCostoAgregado' => $inputCostoAgregado,
				'ComprobantesCompra'=> $ComprobantesCompra,
				'ComprobanteCompra' => $ComprobanteCompra,
				'DocumentosCompra' => array(),
				'DocumentoCompra' => array(),
				'Proveedor'  => $Proveedor,
				'Mercaderia'  => $Mercaderia,
				'CompraGasto'  => $CompraGasto,
				'CompraCostoAgregado'  => $CompraCostoAgregado,
				'TiposDocumento' => $TiposDocumento->TiposDocumentoVenta,
				'NotaCreditoCompra' => $NotaCreditoCompra,
				'NotaDebitoCompra' => $NotaDebitoCompra,
				'MotivosNotaCreditoCompra' => json_decode($MotivosNotaCreditoCompra),
				'CamposNotaCreditoCompra' => json_decode($CamposNotaCreditoCompra),
				'FiltrosNC' => $filtroNota,
				'MotivosNotaDebitoCompra' => json_decode($MotivosNotaDebitoCompra),
				'CamposNotaDebitoCompra' => json_decode($CamposNotaDebitoCompra),
				'FiltrosND' => $filtroNota,
				'BusquedaAvanzadaProducto' => $BusquedaAvanzadaProducto
				)
		);

    $view_data['data'] = $data;
		$views_extra['view_subcontent_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$views['view_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$views_extra,true);
		$views['view_modal_preview_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);

		$views['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia','',true);
		$views['view_modal_buscador_mercaderia_lista_simple'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_mercaderia_lista', '', true);

		$views['view_buscador_consultacomprobantecompra'] = $this->load->View('Compra/ConsultaComprobanteCompra/view_buscador_consultacomprobantecompra','',true);
		$views['view_tabla_consultacomprobantecompra'] = $this->load->View('Compra/ConsultaComprobanteCompra/view_tabla_consultacomprobantecompra','',true);
		$views['view_paginacion_consultacomprobantecompra'] = $this->load->View('Compra/ConsultaComprobanteCompra/view_paginacion_consultacomprobantecompra',"",true);

		$view_subcontent_buscador['view_subcontent_modal_buscador_documentoingreso'] = $this->load->View('Compra/BusquedaDocumentoIngreso/view_mainpanel_subcontent_modal_buscador_documentoingreso','',true);
		$views['view_subcontent_modal_content_documentoingreso'] = $this->load->View('Compra/BusquedaDocumentoIngreso/view_mainpanel_subcontent_modal_content_documentoingreso',$view_subcontent_buscador,true);

		$view_form['view_form_comprobantecompra'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_form_comprobantecompra',"",true);
		$view_form['view_panel_header_comprobantecompra'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_panel_header_comprobantecompra','',true);
		$views['view_modal_comprobantecompra'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_modal_comprobantecompra',$view_form,true);

		$view_form['view_form_comprobantecompra_alternativo'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_form_comprobantecompra_alternativo',"",true);
		$views['view_modal_comprobantecompra_alternativo'] = $this->load->View('Compra/ComprobanteCompra/view_mainpanel_subcontent_modal_comprobantecompra_alternativo',$view_form,true);

		$view_form_gasto['view_form_gasto'] = $this->load->View('Compra/CompraGasto/view_mainpanel_subcontent_form_gasto',"",true);
		$view_form_gasto['view_panel_header_gasto'] = $this->load->View('Compra/CompraGasto/view_mainpanel_subcontent_panel_header_gasto','',true);
		$views['view_modal_gasto'] = $this->load->View('Compra/CompraGasto/view_mainpanel_subcontent_modal_gasto',$view_form_gasto,true);

		//PARA NOTA CREDITO
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraNC/view_mainpanel_subcontent_modal_buscador_comprobantescompra','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraNC/view_mainpanel_subcontent_modal_paginacion_comprobantescompra','',true);
		$view_subcontent['view_subcontent_modal_comprobantescompra_notacredito'] =  $this->load->View('Compra/BusquedaComprobanteCompraNC/view_mainpanel_subcontent_modal_comprobantescompra',$view_subcontent_buscador_paginacion,true);

		$view_form_notacreditocompra['view_form_notacreditocompra'] = $this->load->View('Compra/NotaCreditoCompra/view_mainpanel_subcontent_form_notacreditocompra',"",true);
		$view_form_notacreditocompra['view_panel_header_notacreditocompra'] = $this->load->View('Compra/NotaCreditoCompra/view_mainpanel_subcontent_form_header_notacreditocompra','',true);
		$views['view_modal_notacreditocompra'] = $this->load->View('Compra/NotaCreditoCompra/view_modal_notacreditocompra',$view_form_notacreditocompra,true);

		//PARA NOTA DEBITO
		$view_subcontent_buscador_notadebito['view_subcontent_modal_buscador_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraND/view_mainpanel_subcontent_modal_buscador_comprobantescompra','',true);
		$view_subcontent_buscador_notadebito['view_subcontent_modal_paginacion_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraND/view_mainpanel_subcontent_modal_paginacion_comprobantescompra','',true);
		$views['view_subcontent_modal_comprobantescompra_notadebito'] =  $this->load->View('Compra/BusquedaComprobanteCompraND/view_mainpanel_subcontent_modal_comprobantescompra',$view_subcontent_buscador_notadebito,true);

		$view_form_notadebitocompra['view_form_notadebitocompra'] = $this->load->View('Compra/NotaDebitoCompra/view_mainpanel_subcontent_form_notadebitocompra',"",true);
		$view_form_notadebitocompra['view_panel_header_notadebitocompra'] = $this->load->View('Compra/NotaDebitoCompra/view_mainpanel_subcontent_form_header_notadebitocompra','',true);
		$views['view_modal_notadebitocompra'] = $this->load->View('Compra/NotaDebitoCompra/view_modal_notadebitocompra',$view_form_notadebitocompra,true);

		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_documentocompra'] =  $this->load->View('Compra/BusquedaDocumentoCompra/view_mainpanel_subcontent_modal_buscador_documentocompra','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_documentocompra'] =  $this->load->View('Compra/BusquedaDocumentoCompra/view_mainpanel_subcontent_modal_paginacion_documentocompra','',true);
		$view_subcontent_panel['view_subcontent_modal_documentocompra'] =  $this->load->View('Compra/BusquedaDocumentoCompra/view_mainpanel_subcontent_modal_documentocompra',$view_subcontent_buscador_paginacion,true);

		$view_subcontent_panel['view_subcontent_form_costoagregado'] = $this->load->View('Compra/CompraCostoAgregado/view_mainpanel_subcontent_form_costoagregado','',true);
		$view_subcontent_panel['view_subcontent_panel_header_costoagregado'] = $this->load->View('Compra/CompraCostoAgregado/view_mainpanel_subcontent_panel_header_costoagregado','',true);
		$view_subcontent['view_subcontent_panel_registrocostoagregado'] = $this->load->View('Compra/RegistroCompraCostoAgregado/view_mainpanel_subcontent_panel_registrocostoagregado',$view_subcontent_panel,true);
		$views['view_modal_costoagregado'] = $this->load->View('Compra/CompraCostoAgregado/view_mainpanel_subcontent_modal_costoagregado',$view_subcontent,true);

		$view_footer['view_footer_extension'] = $this->load->View('Compra/ConsultaComprobanteCompra/view_footer_consultacomprobantecompra',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Compra/ConsultaComprobanteCompra/view_content_consultacomprobantecompra',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$view['view_option_mobile'] ="";
    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarComprobantesCompra()
	{
		$input = $this->input->post("Data");
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sComprobanteCompra->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sComprobanteCompra->ObtenerNumeroTotalComprobantesCompra($input);

		$output["resultado"] = $this->sComprobanteCompra->ConsultarComprobantesCompra($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarDocumentosReferenciaCostoAgregado()
	{
		$data = $this->input->get("Data");
		$resultado= $this->sDocumentoReferenciaCostoAgregado->ConsultarDocumentosReferenciaCostoAgregado($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarComprobantesCompraPorPagina()
	{
		$input = $this->input->post("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$resultado = $this->sComprobanteCompra->ConsultarComprobantesCompra($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
