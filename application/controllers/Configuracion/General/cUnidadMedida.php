<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cUnidadMedida extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sUnidadMedida");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$UnidadMedida =  $this->sUnidadMedida->UnidadMedida;
		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();

		$data = array("data" =>
					array(
						'UnidadesMedida' => $UnidadesMedida,
						'UnidadMedida' => $UnidadMedida
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/UnidadMedida/view_mainpanel_content_unidadmedida','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/UnidadMedida/view_mainpanel_footer_unidadmedida',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	/*INICIO PAGINACION*/
	public function ConsultarOtraUnidadesMedidaPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sUnidadMedida->ConsultarOtraUnidadesMedida($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarOtraUnidadesMedida()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sUnidadMedida->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sUnidadMedida->ObtenerNumeroTotalOtraUnidadesMedida($input);
		$output["resultado"] = $this->sUnidadMedida->ConsultarOtraUnidadesMedida($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}
	/*FIN PAGINACION*/

	public function ListarUnidadesMedida()
	{
		$resultado = $this->sUnidadMedida->ListarUnidadesMedida();

		echo $this->json->json_response($resultado);
	}

	public function ListarOtraUnidadesMedida()
	{
		$resultado = $this->sUnidadMedida->ListarOtraUnidadesMedida(1);

		echo $this->json->json_response($resultado);
	}

	public function InsertarUnidadMedida()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sUnidadMedida->InsertarUnidadMedida($data);
		$data["IdUnidadMedida"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarUnidadMedida()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sUnidadMedida->ActualizarUnidadMedida($data);
		echo $this->json->json_response($resultado);
	}

	public function ActualizarOtraUnidadMedida()
	{
		$data = $this->input->post("Data");
		$resultado = "";
		foreach ($data as $key => $value) {
			$resultado = $this->sUnidadMedida->ActualizarOtraUnidadMedida($value);
			$data[$key]["IndicadorEstado"] = ESTADO_ACTIVO;
		}
		echo $this->json->json_response($data);
	}

	public function BorrarUnidadMedida()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sUnidadMedida->BorrarUnidadMedida($data);
		echo $this->json->json_response($resultado);
	}

}
