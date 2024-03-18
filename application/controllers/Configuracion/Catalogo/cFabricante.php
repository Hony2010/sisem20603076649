<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cFabricante extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sFabricante");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$Fabricante =  $this->sFabricante->Fabricante;
		$Fabricantes = $this->sFabricante->ListarFabricantes();

		$data = array("data" =>
					array(
						'Fabricantes' => $Fabricantes,
						'Fabricante' => $Fabricante
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Catalogo/Fabricante/view_mainpanel_content_fabricante','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/Fabricante/view_mainpanel_footer_fabricante',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarFabricantes()
	{
		$resultado = $this->sFabricante->ListarFabricantes();

		echo $this->json->json_response($resultado);
	}

	public function InsertarFabricante()
	{
		$data = $this->input->post("Data");
		$data["CuentaContable"] = null;
		$resultado = $this->sFabricante->InsertarFabricante($data);
		$data["IdFabricante"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarFabricante()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFabricante->ActualizarFabricante($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarFabricante()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFabricante->BorrarFabricante($data);
		echo $this->json->json_response($resultado);
	}

}
