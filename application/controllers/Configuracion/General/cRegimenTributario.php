<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRegimenTributario extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sRegimenTributario");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$RegimenTributario =  $this->sRegimenTributario->RegimenTributario;
		$RegimenesTributario = $this->sRegimenTributario->ListarRegimenesTributario();

		$data = array("data" =>
					array(
						'RegimenesTributario' => $RegimenesTributario,
						'RegimenTributario' => $RegimenTributario
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/RegimenTributario/view_mainpanel_content_regimentributario','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/RegimenTributario/view_mainpanel_footer_regimentributario',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarRegimenesTributario()
	{
		$resultado = $this->sRegimenTributario->ListarRegimenesTributario();

		echo $this->json->json_response($resultado);
	}

	public function InsertarRegimenTributario()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sRegimenTributario->InsertarRegimenTributario($data);
		$data["IdRegimenTributario"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarRegimenTributario()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sRegimenTributario->ActualizarRegimenTributario($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarRegimenTributario()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sRegimenTributario->BorrarRegimenTributario($data);
		echo $this->json->json_response($resultado);
	}

}
