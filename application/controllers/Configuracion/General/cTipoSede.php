<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoSede extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sTipoSede");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoSede =  $this->sTipoSede->TipoSede;
		$TiposSede = $this->sTipoSede->ListarTiposSede();

		$data = array("data" =>
					array(
						'TiposSede' => $TiposSede,
						'TipoSede' => $TipoSede
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/TipoSede/view_mainpanel_content_tiposede','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/TipoSede/view_mainpanel_footer_tiposede',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposSede()
	{
		$resultado = $this->sTipoSede->ListarTiposSede();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoSede()
	{
		$data = $this->input->post("Data");
		$data["CuentaContable"] = null;
		$resultado = $this->sTipoSede->InsertarTipoSede($data);
		$data["IdTipoSede"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarTipoSede()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoSede->ActualizarTipoSede($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoSede()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoSede->BorrarTipoSede($data);
		echo $this->json->json_response($resultado);
	}

}
