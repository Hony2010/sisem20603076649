<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cConsultaComprobanteCaja extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->service("Caja/sComprobanteCaja");
		$this->load->service("Caja/sTipoOperacionCaja");
		$this->load->service('Seguridad/sAccesoCajaUsuario');
		$this->load->service("Caja/sAperturaCaja");
		$this->load->service("Caja/sDocumentoIngreso");
		$this->load->service("Caja/sDocumentoEgreso");
		$this->load->service("Caja/sTransferenciaCaja");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->service("Seguridad/sUsuario");
	}


	public function Index()
	{
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["TextoFiltro"] = '';
		$input["FechaInicio"] = $fechaservidor;
		$input["FechaFin"] = $fechaservidor;
		$input["IdCaja"] = '%';
		$input["IdTipoOperacionCaja"] = '%';
		$input["IdTipoDocumento"] = '%';
		$input["IdMoneda"] = '%';
		$input["IdUsuario"] = '%';

		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sComprobanteCaja->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sComprobanteCaja->ObtenerNumeroTotalComprobantesCaja($input);

		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_RECIBO_INGRESO;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$ComprobanteCaja = $this->sComprobanteCaja->CargarComprobanteCaja($parametro);

		$ComprobantesCaja = $this->sComprobanteCaja->ConsultarComprobantesCaja($input, $input["pagina"], $input["numerofilasporpagina"]); //$pagina
		$input["FechaInicio"] = date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"] = date("d/m/Y", strtotime($input["FechaFin"]));
		$input["Cajas"] = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario();
		$input["TiposOperacionCaja"] = $this->sTipoOperacionCaja->ListarTiposOperacionCaja();;
		$input["IdCaja"] = "";
		$input["IdTipoOperacionCaja"] = "";

		$input["IdModuloSistema"] = ID_MODULO_CAJA;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$input["TiposDocumentoCaja"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input, 0);

		$input["Monedas"] = $this->sMoneda->ListarMonedas();
		$input["Usuarios"] = $this->sUsuario->ListarUsuarios();

		$AperturaCaja =  $this->sAperturaCaja->Cargar();
		$OtroDocumentoIngreso =  $this->sDocumentoIngreso->Cargar();
		$OtroDocumentoEgreso =  $this->sDocumentoEgreso->Cargar();
		$TransferenciaCaja =  $this->sTransferenciaCaja->Cargar();

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'ComprobantesCaja' => $ComprobantesCaja,
				'ComprobanteCaja' => $ComprobanteCaja,
				'AperturaCaja' => $AperturaCaja,
				'OtroDocumentoIngreso' => $OtroDocumentoIngreso,
				'OtroDocumentoEgreso' => $OtroDocumentoEgreso,
				'TransferenciaCaja' => $TransferenciaCaja
			)
		);

		$view_data['data'] = $data;

		$views_ac['view_form_aperturacaja'] = $this->load->View('Caja/AperturaCaja/view_form_aperturacaja', '', true);
		$views['view_modal_aperturacaja'] = $this->load->View('Caja/AperturaCaja/view_modal_aperturacaja', $views_ac, true);

		$views_di['view_form_otrodocumentoingreso'] = $this->load->View('Caja/OtroDocumentoIngreso/view_form_otrodocumentoingreso', '', true);
		$views['view_modal_otrodocumentoingreso'] = $this->load->View('Caja/OtroDocumentoIngreso/view_modal_otrodocumentoingreso', $views_di, true);

		$views_de['view_form_otrodocumentoegreso'] = $this->load->View('Caja/OtroDocumentoEgreso/view_form_otrodocumentoegreso', '', true);
		$views['view_modal_otrodocumentoegreso'] = $this->load->View('Caja/OtroDocumentoEgreso/view_modal_otrodocumentoegreso', $views_de, true);

		$views_tc['view_form_transferenciacaja'] = $this->load->View('Caja/TransferenciaCaja/view_form_transferenciacaja', '', true);
		$views['view_modal_transferenciacaja'] = $this->load->View('Caja/TransferenciaCaja/view_modal_transferenciacaja', $views_tc, true);

		$views['view_buscador_consultacomprobantecaja'] = $this->load->View('Caja/ConsultaComprobanteCaja/view_buscador_consultacomprobantecaja', '', true);
		$views['view_tabla_consultacomprobantecaja'] = $this->load->View('Caja/ConsultaComprobanteCaja/view_tabla_consultacomprobantecaja', '', true);
		$views['view_paginacion_consultacomprobantecaja'] = $this->load->View('Caja/ConsultaComprobanteCaja/view_paginacion_consultacomprobantecaja', "", true);

		$view_footer['view_footer_extension'] = $this->load->View('Caja/ConsultaComprobanteCaja/view_footer_consultacomprobantecaja', $view_data, true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Caja/ConsultaComprobanteCaja/view_content_consultacomprobantecaja', $views, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ConsultarComprobantesCaja()
	{
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sComprobanteCaja->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sComprobanteCaja->ObtenerNumeroTotalComprobantesCaja($input);
		$output["resultado"] = $this->sComprobanteCaja->ConsultarComprobantesCaja($input, $input["pagina"], $numerofilasporpagina);
		$output["Filtros"] = array_merge(
			$input,
			array(
				"numerofilasporpagina" => $numerofilasporpagina,
				"totalfilas" => $TotalFilas,
				"paginadefecto" => 1
			)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarComprobantesCajaPorPagina()
	{
		$input = json_decode($this->input->post("Data"), true);
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$resultado = $this->sComprobanteCaja->ConsultarComprobantesCaja($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
}
