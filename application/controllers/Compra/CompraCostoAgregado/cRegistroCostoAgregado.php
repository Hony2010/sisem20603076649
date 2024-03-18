<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRegistroCostoAgregado extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sProveedor");
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->service("Compra/sCompraCostoAgregado");
		$this->load->library('sesionusuario');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		// $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		// $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		// $CompraCostoAgregado =$this->sComprobanteCompra->Cargar($parametro);
		$CompraCostoAgregado =$this->sCompraCostoAgregado->CargarCompraCostoAgregado();
		// $CompraCostoAgregado["IdTipoCompra"] = ID_TIPOCOMPRA_GASTO;
		$Proveedor=$this->sProveedor->Cargar();

		$input["textofiltro"]='';
		$input["FechaInicio"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaFin"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaHoy"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["IdPersona"]=0;
		$input["IdTipoDocumento"]=ID_TIPO_DOCUMENTO_FACTURA;
		$input["IdTipoCompra"]=$CompraCostoAgregado["IdTipoCompra"];

		$data = array(
			"data" => array(
				'CompraCostoAgregado' => $CompraCostoAgregado,
				'CompraCostoAgregadoNuevo' => $CompraCostoAgregado,
				'DocumentosCompra' => array(),
				'DocumentoCompra' => array(),
				'FiltrosCostoAgregado' => $input,
				'Proveedor'  => $Proveedor
				)
		);

    $view_data['data'] = $data;
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_documentocompra'] =  $this->load->View('Compra/BusquedaDocumentoCompra/view_mainpanel_subcontent_modal_buscador_documentocompra','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_documentocompra'] =  $this->load->View('Compra/BusquedaDocumentoCompra/view_mainpanel_subcontent_modal_paginacion_documentocompra','',true);
		$view_subcontent_panel['view_subcontent_modal_documentocompra'] =  $this->load->View('Compra/BusquedaDocumentoCompra/view_mainpanel_subcontent_modal_documentocompra',$view_subcontent_buscador_paginacion,true);

		$view_subsubcontent_extra['view_subcontent_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$view_subcontent['view_subcontent_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$view_subsubcontent_extra,true);
		$view_subcontent['view_subcontent_modal_preview_foto_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);
		$view_subcontent_panel['view_subcontent_form_costoagregado'] = $this->load->View('Compra/CompraCostoAgregado/view_mainpanel_subcontent_form_costoagregado','',true);
		$view_subcontent_panel['view_subcontent_panel_header_costoagregado'] = $this->load->View('Compra/CompraCostoAgregado/view_mainpanel_subcontent_panel_header_costoagregado','',true);
		$view_subcontent['view_subcontent_panel_registrocostoagregado'] = $this->load->View('Compra/RegistroCompraCostoAgregado/view_mainpanel_subcontent_panel_registrocostoagregado',$view_subcontent_panel,true);
		$view_footer_extension['view_footer_extension'] = $this->load->View('Compra/RegistroCompraCostoAgregado/view_mainpanel_footer_registrocostoagregado',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Compra/RegistroCompraCostoAgregado/view_mainpanel_content_registrocostoagregado',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}
