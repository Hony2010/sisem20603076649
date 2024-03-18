<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cSeguridad extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Seguridad/sSeguridad");
		$this->load->service("Seguridad/sUsuario");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('sesionusuario');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		if($this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)){
			$url = "/cDashBoard/";
			redirect($url);
		}

    $Seguridad =  $this->sSeguridad->Seguridad;
		$Seguridad['IdUsuario'] = "";

    $data["data"] =array('Seguridad' =>
						array(
							'NombreUsuario' => '',
							'ClaveUsuario' =>''
						)
					);

    $view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
    $view['view_content'] =  $this->load->View('Seguridad/Seguridad/view_mainpanel_content_seguridad','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Seguridad/Seguridad/view_mainpanel_footer_seguridad',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('Seguridad/Seguridad/master_view_login',$view);

	}

	public function Login()
	{
    $usuario = $this->input->post("nombreusuario");
    $clave = $this->input->post("claveusuario");
    $data["NombreUsuario"] = $usuario;
    $data["ClaveUsuario"] = $clave;

		$resultado = $this->sSeguridad->IniciarSesion($data);

		echo $this->json->json_response($resultado);
	}

	function Logout()
	{
		$data = $this->input->post("Data");
		$this->session->sess_destroy();
		redirect(site_url()."/Seguridad/cSeguridad/");
	}

	public function seleccionador_tab()
	{
		$sub_tab_menu = $this->input->post("sub_tab_menu");
		$this->session->set_userdata("tab",$sub_tab_menu);
		echo $this->session->userdata("tab");
	}

	public function seleccionador_item()
	{
		$sub_item_menu = $this->input->post("sub_item_menu");
		$this->session->set_userdata("item",$sub_item_menu);

		echo $this->session->userdata("item");

	}

	public function ActualizarTemaUsuario()
	{
		 $tema = $this->input->post("TemaSistema");
		 $data['TemaSistema'] = $tema;
		 $data['IdUsuario'] = $this->sesionusuario->obtener_sesion_id_usuario();
		 $resultado = $this->sUsuario->ActualizarTemaUsuario($data);

		 $sessionData = $this->session->userdata('Usuario_'.LICENCIA_EMPRESA_RUC);
		 $sessionData['TemaSistema']= $data["TemaSistema"];
		 $this->session->set_userdata('Usuario_'.LICENCIA_EMPRESA_RUC, $sessionData);

		 echo $this->json->json_response("success");

	 }
}
