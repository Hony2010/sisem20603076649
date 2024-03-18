<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionNotaDebito extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Venta/sNotaDebito");
		$this->load->service("Catalogo/sCliente");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
	}

	public function Index()
	{

		$NotaDebito =$this->sNotaDebito->CargarNotaDebito();

		//AQUI OBTENEMOSLOS DATOS JSON

		$MotivosNotaDebito = file_get_contents(BASE_PATH.'assets/data/venta/reglamotivonotadebito.json');
		$CamposNotaDebito = file_get_contents(BASE_PATH.'assets/data/venta/reglacamposnotadebito.json');
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
				'NotaDebito' => $NotaDebito,
				'NuevoNotaDebito' => $NotaDebito,
				'TiposDocumento' => $TiposDocumento->TiposDocumentoVenta,
				'Cliente'  => $Cliente,
				'MotivosNotaDebito' => json_decode($MotivosNotaDebito),
				'CamposNotaDebito' => json_decode($CamposNotaDebito),
				'FiltrosND' => $input
				)
		);

		$view_data['data'] = $data;
		$view_subcontent['view_subcontent_form_header_notadebito'] =  $this->load->View('Venta/NotaDebito/view_mainpanel_subcontent_form_header_notadebito','',true);
		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaND/view_mainpanel_subcontent_modal_buscador_comprobantesventa','',true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaND/view_mainpanel_subcontent_modal_paginacion_comprobantesventa','',true);
		$view_subcontent['view_subcontent_modal_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaND/view_mainpanel_subcontent_modal_comprobantesventa',$view_subcontent_buscador_paginacion,true);
		$view_subcontent['view_subcontent_form_notadebito'] =  $this->load->View('Venta/NotaDebito/view_mainpanel_subcontent_form_notadebito','',true);
		$view_subcontent['view_tipoventa_notadebito'] = $this->load->View('Venta/EmisionNotaDebito/view_tipoventa_notadebito','',true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionNotaDebito/view_mainpanel_footer_notadebito',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionNotaDebito/view_mainpanel_content_notadebito',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}


}
