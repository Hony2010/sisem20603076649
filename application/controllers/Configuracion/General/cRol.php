<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRol extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sRol");
		$this->load->service("Configuracion/General/sTipoRol");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$Rol =  $this->sRol->Rol;
		$Rol['NombreTipoRol'] = "";
		$Roles = $this->sRol->ListarRoles();
		$TiposRol = $this->sTipoRol->ListarTiposRol();

		$data = array("data" =>
					array(
						'Roles' => $Roles,
						'Rol' => $Rol,
						'TiposRol' => $TiposRol
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/Rol/view_mainpanel_content_rol','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/Rol/view_mainpanel_footer_rol',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarRoles()
	{
		$resultado = $this->sRol->ListarRoles();

		echo $this->json->json_response($resultado);
	}

	public function InsertarRol()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sRol->InsertarRol($data);
		$data["IdRol"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarRol()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sRol->ActualizarRol($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarRol()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sRol->BorrarRol($data);
		echo $this->json->json_response($resultado);
	}

}
