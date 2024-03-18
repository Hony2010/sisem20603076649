<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMenu extends CI_Controller  {

	public function __construct() {
		parent::__construct();

    $this->load->service("Seguridad/sMenu");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('json');
	}

	public function Index() {

	}

  public function CargarOpcionesPorUsuario() {
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sMenu->CargarOpcionesPorUsuario($data);

		echo $this->json->json_response($resultado);
	}

	public function CargarOpcionesPorRol() {
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sMenu->CargarOpcionesPorRol($data);

		echo $this->json->json_response($resultado);
	}

}
