
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller  {

	public function __construct()
	{

		parent::__construct();
		$this->load->service("Seguridad/sSeguridad");

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
    $Seguridad =  $this->sSeguridad->Seguridad;
		$Seguridad['IdUsuario'] = "";

    $view["data"] =array('Cuenta' =>
						array(
							'NombreUsuario' => '',
							'ClaveUsuario' =>''
						)
					);


    $this->load->View('login', $view);

	}

  public function Login()
	{
    $usuario = $this->input->post("nombreusuario");
    $clave = $this->input->post("claveusuario");
    $data["NombreUsuario"] = $usuario;
    $data["ClaveUsuario"] = $clave;
		//$data = $this->input->post("Data");
		$resultado = $this->sSeguridad->Login($data);

    $usuario = array("Usuario" => $resultado);
		$this->session->set_userdata($usuario);
    $id_Usuario = $this->session->userdata("Usuario")["IdUsuario"];

    echo $id_Usuario;
    //echo $resultado;
    // print_r $this->json->json_response($resultado);

	}


}
