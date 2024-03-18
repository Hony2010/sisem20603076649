<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cAccesoRol extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Seguridad/sMenu");
		$this->load->service("Seguridad/sAccesoRol");
		$this->load->service("Configuracion/General/sRol");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index() {
		$Roles = $this->sRol->ListarRoles();
		$data["IdRol"] = $Roles[0]["IdRol"];
		$AccesosRol = $this->sMenu->CargarOpcionesPorRol($data);

		$data = array("data" =>
					array(
						'AccesosRol' => $AccesosRol,
						'AccesoRol' => array(),
						'TiposAccesoRol' => array(),
						'Roles' => $Roles
					)
		 );

		$view_data['data'] = $data;

		$view_subcontent['view_subcontent_accesorol'] =  $this->load->View('Seguridad/AccesoRol/view_mainpanel_subcontent_accesorol','',true);

    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Seguridad/AccesoRol/view_mainpanel_content_accesorol',$view_subcontent,true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Seguridad/AccesoRol/view_mainpanel_footer_accesorol',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}


	public function InsertarAccesoRol()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sAccesoRol->InsertarAccesoRol($data);
		$data["IdAccesoRol"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarAccesoRol()
	{
		$data = json_decode($this->input->post("Data"), true);
		$IdRol = $this->input->post("IdRol");
		foreach ($data as $key => $item) {
			foreach ($item["OpcionesSistema"] as $key2 => $item2) {
				if ($item2["IdAccesoRol"] == "") {
					$item2["IdRol"] = $IdRol;
					$resultado = $this->sAccesoRol->InsertarAccesoRol($item2);
				}
				else {
					$resultado = $this->sAccesoRol->ActualizarAccesoRol($item2);
				}
			}
		}

		echo $this->json->json_response("");
	}

	public function BorrarAccesoRol()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sAccesoRol->BorrarAccesoRol($data);
		echo $this->json->json_response($resultado);
	}

}
