<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRegistroNotaCreditoCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Compra/sNotaCreditoCompra");
		$this->load->service("Catalogo/sCliente");
		$this->load->service("Catalogo/sProveedor");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
    	$this->load->helper("date");
		$this->load->model("Base");
	}

	public function Index()
	{
		// $ComprobanteCompra =$this->sComprobanteCompra->Cargar();
		$NotaCreditoCompra =$this->sNotaCreditoCompra->CargarNotaCreditoCompra();

		//AQUI OBTENEMOSLOS DATOS JSON
		$MotivosNotaCreditoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglamotivonotacredito.json');
		$CamposNotaCreditoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglacamposnotacredito.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json'));

		// $Cliente=$this->sCliente->Cargar();
		$Cliente= $this->sProveedor->Cargar();

		$input["textofiltro"]='';
		$input["FechaInicio"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaFin"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaHoy"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["IdPersona"]=3;
		$input["IdTipoDocumento"]=3;
		$input["IdMoneda"]=3;
		$input["IdTipoCompra"]=2;

		$data = array(
			"data" => array(
				'NotaCreditoCompra' => $NotaCreditoCompra,
				'NuevoNotaCreditoCompra' => $NotaCreditoCompra,
				'TiposDocumento' => $TiposDocumento->TiposDocumentoVenta,
				'Proveedor'  => $Cliente,
				'MotivosNotaCreditoCompra' => json_decode($MotivosNotaCreditoCompra),
				'CamposNotaCreditoCompra' => json_decode($CamposNotaCreditoCompra),
				'FiltrosNC' => $input
				)
		);

		$view_data['data'] = $data;
		$view_subcontent['view_subcontent_form_header_notacreditocompra'] =  $this->load->View('Compra/NotaCreditoCompra/view_mainpanel_subcontent_form_header_notacreditocompra','',true);
		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraNC/view_mainpanel_subcontent_modal_buscador_comprobantescompra','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraNC/view_mainpanel_subcontent_modal_paginacion_comprobantescompra','',true);
		$view_subcontent['view_subcontent_modal_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraNC/view_mainpanel_subcontent_modal_comprobantescompra',$view_subcontent_buscador_paginacion,true);
		$view_subcontent['view_subcontent_form_notacreditocompra'] =  $this->load->View('Compra/NotaCreditoCompra/view_mainpanel_subcontent_form_notacreditocompra','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Compra/EmisionNotaCreditoCompra/view_mainpanel_footer_notacreditocompra',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Compra/EmisionNotaCreditoCompra/view_mainpanel_content_notacreditocompra',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}


}
