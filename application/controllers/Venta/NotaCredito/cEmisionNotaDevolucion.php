<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cEmisionNotaDevolucion extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Venta/sNotaCredito");
		$this->load->service("Catalogo/sCliente");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->helper("date");
		$this->load->model("Base");
	}

	public function Index() {
		$NotaCredito = $this->sNotaCredito->CargarNotaCredito(true);

		//AQUI OBTENEMOSLOS DATOS JSON
		$MotivosNotaCredito = file_get_contents(BASE_PATH . 'assets/data/venta/reglamotivonotacredito.json');
		$CamposNotaCredito = file_get_contents(BASE_PATH . 'assets/data/venta/reglacamposnotacredito.json');
		$TiposDocumento = json_decode(file_get_contents(BASE_PATH . 'assets/data/documentos/documentos.json'));

		$Cliente = $this->sCliente->Cargar();

		$input["textofiltro"] = '';
		$input["FechaInicio"] = $this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaFin"] = $this->Base->ObtenerFechaServidor("d/m/Y");
		$input["FechaHoy"] = $this->Base->ObtenerFechaServidor("d/m/Y");
		$input["IdPersona"] = 3;
		$input["IdTipoDocumento"] = ID_TIPO_DOCUMENTO_ORDEN_PEDIDO;
		$input["IdMoneda"] = 3;
		$input["IdTipoVenta"] = 1;

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
		$view_subcontent['view_subcontent_form_header_notadevolucion'] =  $this->load->View('Venta/NotaDevolucion/view_mainpanel_subcontent_form_header_notadevolucion', '', true);
		//MODAL PAGINACION
		$view_subcontent_buscador_paginacion['view_subcontent_modal_buscador_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_buscador_comprobantesventa', '', true);
		$view_subcontent_buscador_paginacion['view_subcontent_modal_paginacion_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_paginacion_comprobantesventa', '', true);
		$view_subcontent['view_subcontent_modal_comprobantesventa'] =  $this->load->View('Venta/BusquedaComprobanteVentaNC/view_mainpanel_subcontent_modal_comprobantesventa', $view_subcontent_buscador_paginacion, true);
		$view_subcontent['view_subcontent_form_notadevolucion'] =  $this->load->View('Venta/NotaDevolucion/view_mainpanel_subcontent_form_notadevolucion', '', true);
		$view_subcontent['view_tipoventa_notadevolucion'] = $this->load->View('Venta/EmisionNotaDevolucion/view_tipoventa_notadevolucion', '', true);

		$view_footer['view_footer_extension'] = $this->load->View('Venta/EmisionNotaDevolucion/view_mainpanel_footer_notadevolucion', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Venta/EmisionNotaDevolucion/view_mainpanel_content_notadevolucion', $view_subcontent, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}
}
