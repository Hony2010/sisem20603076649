<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cTransportistas extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sTransportista");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$input["textofiltro"] = '';
		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sTransportista->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sTransportista->ObtenerNumeroTotalTransportistas($input);

		$Transportista = $this->sTransportista->Cargar();
		$Transportistas = $this->sTransportista->ListarTransportistas(1);
		$data = array(
			"data" => array(
				'Filtros' => $input,
				'Transportista'  => $Transportista,
				'Transportistas'  => $Transportistas
			)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_transportistas'] =   $this->load->View('Catalogo/Transportistas/view_mainpanel_subcontent_buscador_transportistas', '', true);
		$view_sub_subcontent['view_subcontent_paginacion_transportistas'] =   $this->load->View('Catalogo/Transportistas/view_mainpanel_subcontent_paginacion_transportistas', '', true);
		$view_subcontent['view_subcontent_preview_transportista'] =  $this->load->View('Catalogo/Transportista/view_mainpanel_subcontent_preview_transportista', '', true);
		$view_subcontent['view_subcontent_consulta_transportistas'] =  $this->load->View('Catalogo/Transportistas/view_mainpanel_subcontent_consulta_transportistas', $view_sub_subcontent, true);
		$view_subcontent_panel['view_subcontent_modal_preview_foto_transportista'] =  $this->load->View('Catalogo/Transportista/view_mainpanel_subcontent_modal_preview_foto_transportista', '', true);
		$view_subcontent_panel['view_subcontent_form_transportista'] =  $this->load->View('Catalogo/Transportista/view_mainpanel_subcontent_form_transportista', '', true);
		$view_subcontent['view_subcontent_modal_transportista'] =  $this->load->View('Catalogo/Transportista/view_mainpanel_subcontent_modal_transportista', $view_subcontent_panel, true);

		$view_footer['view_footer_extension'] = $this->load->View('Catalogo/Transportistas/view_mainpanel_footer_transportistas', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Catalogo/Transportistas/view_mainpanel_content_transportistas', $view_subcontent, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ConsultarTransportistas()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sTransportista->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sTransportista->ObtenerNumeroTotalTransportistas($input);
		$output["resultado"] = $this->sTransportista->ConsultarTransportistas($input, $input["pagina"], $numerofilasporpagina);
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

	public function ConsultarTransportistasPorIdPersona()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;
		$resultado = $this->sTransportista->ConsultarTransportistasPorIdPersona($data, 1);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaTransportistasPorRuc()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sTransportista->ConsultarSugerenciaTransportistasPorRuc($data, 1);

		echo $this->json->json_response($resultado);
	}


	public function ListarTransportistas()
	{
		$resultado = $this->sTransportista->ListarTransportistas();

		echo $this->json->json_response($resultado);
	}

	public function ConsultarTransportistasPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sTransportista->ConsultarTransportistas($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
}
