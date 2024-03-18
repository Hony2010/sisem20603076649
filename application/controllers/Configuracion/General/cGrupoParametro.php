<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGrupoParametro extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sGrupoParametro");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$GrupoParametro =  $this->sGrupoParametro->GrupoParametro;
		$GruposParametro = $this->sGrupoParametro->ListarGruposParametro();

		$data = array("data" =>
					array(
						'GruposParametro' => $GruposParametro,
						'GrupoParametro' => $GrupoParametro

					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/GrupoParametro/view_mainpanel_content_grupoparametro','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/GrupoParametro/view_mainpanel_footer_grupoparametro',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarGruposParametro()
	{
		$resultado = $this->sGrupoParametro->ListarGruposParametro();

		echo $this->json->json_response($resultado);
	}

	public function InsertarGrupoParametro()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGrupoParametro->InsertarGrupoParametro($data);
		$data["IdGrupoParametro"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarGrupoParametro()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGrupoParametro->ActualizarGrupoParametro($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarGrupoParametro()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGrupoParametro->BorrarGrupoParametro($data);
		echo $this->json->json_response($resultado);
	}

}
