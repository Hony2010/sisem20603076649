<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoServicio extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sTipoServicio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoServicio =  $this->sTipoServicio->TipoServicio;
		$TiposServicio = $this->sTipoServicio->ListarTiposServicio();

		$data = array("data" =>
					array(
						'TiposServicio' => $TiposServicio,
						'TipoServicio' => $TipoServicio
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Catalogo/TipoServicio/view_mainpanel_content_tiposervicio','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/TipoServicio/view_mainpanel_footer_tiposervicio',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposServicio()
	{
		$resultado = $this->sTipoServicio->ListarTiposServicio();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoServicio()
	{
		$data = $this->input->post("Data");
		$data["CuentaContable"] = null;
		$resultado = $this->sTipoServicio->InsertarTipoServicio($data);
		$data["IdTipoServicio"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarTipoServicio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoServicio->ActualizarTipoServicio($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoServicio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoServicio->BorrarTipoServicio($data);
		echo $this->json->json_response($resultado);
	}

}
