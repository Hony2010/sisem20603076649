<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRegistroNotaDebitoCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Compra/sNotaDebitoCompra");
		$this->load->service("Catalogo/sCliente");
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
		$NotaDebitoCompra =$this->sNotaDebitoCompra->CargarNotaDebitoCompra();

		//AQUI OBTENEMOSLOS DATOS JSON
		$MotivosNotaDebitoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglamotivonotadebito.json');
		$CamposNotaDebitoCompra = file_get_contents(BASE_PATH.'assets/data/compra/reglacamposnotadebito.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json'));

		//$Cliente=$this->sCliente->Cargar();
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
				'NotaDebitoCompra' => $NotaDebitoCompra,
				'NuevoNotaDebitoCompra' => $NotaDebitoCompra,
				'TiposDocumento' => $TiposDocumento->TiposDocumentoVenta,
				'Proveedor'  => $Cliente,
				'MotivosNotaDebitoCompra' => json_decode($MotivosNotaDebitoCompra),
				'CamposNotaDebitoCompra' => json_decode($CamposNotaDebitoCompra),
				'FiltrosND' => $input
				)
		);

		$view_data['data'] = $data;
		$view_subcontent['view_subcontent_form_header_notadebitocompra'] =  $this->load->View('Compra/NotaDebitoCompra/view_mainpanel_subcontent_form_header_notadebitocompra','',true);
		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraND/view_mainpanel_subcontent_modal_buscador_comprobantescompra','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraND/view_mainpanel_subcontent_modal_paginacion_comprobantescompra','',true);
		$view_subcontent['view_subcontent_modal_comprobantescompra'] =  $this->load->View('Compra/BusquedaComprobanteCompraND/view_mainpanel_subcontent_modal_comprobantescompra',$view_subcontent_buscador_paginacion,true);
		$view_subcontent['view_subcontent_form_notadebitocompra'] =  $this->load->View('Compra/NotaDebitoCompra/view_mainpanel_subcontent_form_notadebitocompra','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Compra/RegistroNotaDebitoCompra/view_mainpanel_footer_notadebitocompra',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Compra/RegistroNotaDebitoCompra/view_mainpanel_content_notadebitocompra',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}


}
