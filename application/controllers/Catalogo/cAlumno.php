<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cAlumno extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sAlumno");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{

	}

	public function InsertarAlumno()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sAlumno->InsertarAlumno($data);
		$data["IdAlumno"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarAlumno()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sAlumno->ActualizarAlumno($data);
		echo $this->json->json_response($resultado);
  }

	public function BorrarAlumno()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sAlumno->BorrarAlumno($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarAlumno()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sAlumno->ListarAlumnos($data);

		echo $this->json->json_response($resultado);
	}

}
