<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionNotaCredito extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Venta/sNotaCredito");
		$this->load->service("Catalogo/sCliente");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->service("Configuracion/General/sMedioPago");
		$this->load->library('json');
		$this->load->library('shared');
    $this->load->helper("date");
		$this->load->model("Base");
	}

	public function Index()
	{
		// $ComprobanteVenta =$this->sComprobanteVenta->Cargar();
		$NotaCredito =$this->sNotaCredito->CargarNotaCredito();

		//AQUI OBTENEMOSLOS DATOS JSON
		$MotivosNotaCredito = file_get_contents(BASE_PATH.'assets/data/venta/reglamotivonotacredito.json');
		$CamposNotaCredito = file_get_contents(BASE_PATH.'assets/data/venta/reglacamposnotacredito.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH.'assets/data/documentos/documentos.json'));

		$Cliente=$this->sCliente->Cargar();

		$input["textofiltro"]='';
		$input["FechaInicio"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaFin"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaHoy"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["IdPersona"]=3;
		$input["IdTipoDocumento"]=3;
		$input["IdMoneda"]=3;
		$input["IdTipoVenta"]=1;

		$data = array(
			"data" => array(
				'NotaCredito' => $NotaCredito,
				'NuevoNotaCredito' => $NotaCredito,
				'TiposDocumentoCompleto' => $TiposDocumento, 
				'TiposDocumento' => array(),
				'Cliente'  => $Cliente,
				'MotivosNotaCredito' => json_decode($MotivosNotaCredito),
				'CamposNotaCredito' => json_decode($CamposNotaCredito),
				'FiltrosNC' => $input
				)
		);

		$view_data['data'] = $data;
		$view_subcontent['view_subcontent_form_header_notacredito'] =  $this->load->View('Venta/NotaCredito/view_mainpanel_subcontent_form_header_notacredito','',true);
		//MODAL PAGINACION
		$view_subcontent['view_modal_cuotapagoclientecomprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_modal_cuotapagoclientecomprobanteventa','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_buscador_comprobantesventa','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_paginacion_comprobantesventa','',true);
		$view_subcontent['view_subcontent_modal_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_comprobantesventa',$view_subcontent_buscador_paginacion,true);
		$view_subcontent['view_subcontent_form_notacredito'] =  $this->load->View('Venta/NotaCredito/view_mainpanel_subcontent_form_notacredito','',true);
		$view_subcontent['view_tipoventa_notacredito'] = $this->load->View('Venta/EmisionNotaCredito/view_tipoventa_notacredito','',true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionNotaCredito/view_mainpanel_footer_notacredito',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionNotaCredito/view_mainpanel_content_notacredito',$view_subcontent,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}


}
