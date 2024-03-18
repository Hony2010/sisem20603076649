<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoTarjeta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Venta/sTipoTarjeta");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoTarjeta =  $this->sTipoTarjeta->TipoTarjeta;
		$TiposTarjeta = $this->sTipoTarjeta->ListarTiposTarjeta();

		$data = array("data" =>
					array(
						'TiposTarjeta' => $TiposTarjeta,
						'TipoTarjeta' => $TipoTarjeta
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Venta/TipoTarjeta/view_mainpanel_content_tipotarjeta','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Venta/TipoTarjeta/view_mainpanel_footer_tipotarjeta',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposTarjeta()
	{
		$resultado = $this->sTipoTarjeta->ListarTiposTarjeta();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoTarjeta()
	{
		$data = $this->input->post("Data");
		$data["CuentaContable"] = null;
		$resultado = $this->sTipoTarjeta->InsertarTipoTarjeta($data);
		$data["IdTipoTarjeta"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarTipoTarjeta()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoTarjeta->ActualizarTipoTarjeta($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoTarjeta()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoTarjeta->BorrarTipoTarjeta($data);
		echo $this->json->json_response($resultado);
	}

}
