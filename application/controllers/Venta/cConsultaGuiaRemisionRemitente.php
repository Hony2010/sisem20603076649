<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cConsultaGuiaRemisionRemitente extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->service("Venta/sGuiaRemisionRemitente");
		$this->load->service("Configuracion/General/sDepartamento");
		$this->load->service("Configuracion/General/sProvincia");
		$this->load->service("Configuracion/General/sDistrito");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
	}

	public function Index()
	{
		$fechaServidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["FechaInicio"] = $fechaServidor;
		$input["FechaFin"] = $fechaServidor;
		$input["NombreDestinatario"] = '';
		$input["NombreTransportista"] = '';
		$input["TextoFiltro"] = '';
		$input["pagina"] = 1;
		$input["paginadefecto"] = 1;
		$input["numerofilasporpagina"] = $this->sGuiaRemisionRemitente->ObtenerNumeroFilasPorPagina();
		$input["totalfilas"] = $this->sGuiaRemisionRemitente->ObtenerNumeroTotalGuiasRemisionRemitente($input);

		$GuiasRemisionRemitente = $this->sGuiaRemisionRemitente->ConsultarGuiasRemisionRemitente($input, $input["pagina"], $input["numerofilasporpagina"]);
		$GuiaRemisionRemitente = $this->sGuiaRemisionRemitente->Cargar();

		$departamentos = $this->sDepartamento->ListarDepartamentos();
		$provincias = $this->sProvincia->ListarProvincias();
		$distritos = $this->sDistrito->ListarDistritos();
		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();


		$input["FechaInicio"] = date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"] = date("d/m/Y", strtotime($input["FechaFin"]));
		$input["Destinatario"] = "";
		$input["Transportista"] = "";

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'GuiaRemisionRemitente' => $GuiaRemisionRemitente,
				'GuiasRemisionRemitente' => $GuiasRemisionRemitente,
				'Departamentos' => $departamentos,
				'Provincias' => $provincias,
				'Distritos' => $distritos,
				'UnidadesMedida' => $UnidadesMedida
			)
		);

		$view_data['data'] = $data;


		$views['view_buscador_consultaguiaremisionremitente'] = $this->load->View('Venta/ConsultaGuiaRemisionRemitente/view_buscador_consultaguiaremisionremitente', '', true);
		$views['view_tabla_consultaguiaremisionremitente'] = $this->load->View('Venta/ConsultaGuiaRemisionRemitente/view_tabla_consultaguiaremisionremitente', '', true);
		$views['view_paginacion_consultaguiaremisionremitente'] = $this->load->View('Venta/ConsultaGuiaRemisionRemitente/view_paginacion_consultaguiaremisionremitente', "", true);

		$view_form['view_form_guiaremisionremitente'] = $this->load->View('Venta/GuiaRemisionRemitente/view_form_guiaremisionremitente', "", true);
		$views['view_modal_guiaremisionremitente'] = $this->load->View('Venta/GuiaRemisionRemitente/view_modal_guiaremisionremitente', $view_form, true);

		$view_footer['view_footer_extension'] = $this->load->View('Venta/ConsultaGuiaRemisionRemitente/view_footer_consultaguiaremisionremitente', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Venta/ConsultaGuiaRemisionRemitente/view_content_consultaguiaremisionremitente', $views, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ConsultarGuiasRemisionRemitente()
	{
		$input = $this->input->get("Data");
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sGuiaRemisionRemitente->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sGuiaRemisionRemitente->ObtenerNumeroTotalGuiasRemisionRemitente($input);
		$output["resultado"] = $this->sGuiaRemisionRemitente->ConsultarGuiasRemisionRemitente($input, $input["pagina"], $numerofilasporpagina);
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

	public function ConsultarGuiasRemisionRemitentePorPagina()
	{
		$input = $this->input->get("Data");
		//$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$resultado = $this->sGuiaRemisionRemitente->ConsultarGuiasRemisionRemitente($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
}
