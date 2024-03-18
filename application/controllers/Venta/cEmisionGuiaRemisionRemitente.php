<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cEmisionGuiaRemisionRemitente extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Venta/sGuiaRemisionRemitente");
		$this->load->service("Configuracion/General/sDepartamento");
		$this->load->service("Configuracion/General/sProvincia");
		$this->load->service("Configuracion/General/sDistrito");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$guiaRemisionRemitente = $this->sGuiaRemisionRemitente->Cargar();
		$departamentos = $this->sDepartamento->ListarDepartamentos();
		$provincias = $this->sProvincia->ListarProvincias();
		$distritos = $this->sDistrito->ListarDistritos();		
		//$Servicio = $this->sServicio->Inicializar();		
		$fechahoy = convertToDate($this->Base->ObtenerFechaServidor());
		$TotalVendedores = count($guiaRemisionRemitente["Vendedores"]);
		$dataFiltrosBuscadorFacturas = array(
			"TextoFiltro" =>"",
			"IdUsuarioVendedor"  =>"%",
			"Vendedores" => $guiaRemisionRemitente["Vendedores"],
			"FechaInicio" =>convertToDate($fechahoy,"d/m/Y"),
			"FechaFin" =>convertToDate($fechahoy,"d/m/Y"),			
			"NumeroVendedoresSeleccionados"=>0,
			"VendedoresSeleccionados"=> array(),
			"SeleccionarTodosVendedores" =>"true",
			"pagina" => 1,
			"numerofilasporpagina" => '10',
			"paginadefecto" => 1,
			"totalfilas" => 0,
			"ClienteProforma"=>"",
			"EstadoComprobante"=>"",
			"IdCliente"=>"",
			"TotalVendedores"=>$TotalVendedores,
			"ComprobantesSeleccionados"=>array(),
			"TodosComprobantes"=>"true",
			"NumeroComprobantesSeleccionados"=>0,
			"TotalComprobantes"=>0
		);

		$data = array(
			"data" => array(
				'GuiaRemisionRemitente' => $guiaRemisionRemitente,
				'GuiaRemisionRemitenteNuevo' => $guiaRemisionRemitente,
				'Departamentos' => $departamentos,
				'Provincias' => $provincias,
				'Distritos' => $distritos,
				'BuscadorFacturasGuia' => array(
					'FiltrosGuia' => $dataFiltrosBuscadorFacturas,
					'ComprobantesVentaGuia' => array()
				)
			)
		);

		$view_data['data'] = $data;

		$view_form['view_form_guiaremisionremitente'] = $this->load->View('Venta/GuiaRemisionRemitente/view_form_guiaremisionremitente', '', true);
		$views['view_panel_guiaremisionremitente'] = $this->load->View('Venta/EmisionGuiaRemisionRemitente/view_panel_guiaremisionremitente', $view_form, true);
		//$views['view_modal_guiaremisionremitente_buscador_facturas'] = $this->load->View('Venta/GuiaRemisionRemitente/view_modal_guiaremisionremitente_buscador_facturas', '', true);
		$view_footer['view_footer_extension'] = $this->load->View('Venta/EmisionGuiaRemisionRemitente/view_footer_guiaremisionremitente', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Venta/EmisionGuiaRemisionRemitente/view_content_guiaremisionremitente', $views, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}
}
