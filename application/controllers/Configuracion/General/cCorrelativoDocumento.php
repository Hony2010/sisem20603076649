<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCorrelativoDocumento extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Venta/sCorrelativoDocumento");
		$this->load->service("Configuracion/General/sSede");
		$this->load->service("Configuracion/General/sTipoDocumento");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function ListarCorrelativosDocumento()
	{
		$resultado = $this->sCorrelativoDocumento->ListarCorrelativosDocumento();

		echo $this->json->json_response($resultado);
	}

	public function InsertarCorrelativoDocumento()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCorrelativoDocumento->InsertarCorrelativoDocumento($data);
		$data["IdCorrelativoDocumento"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarCorrelativoDocumento()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarCorrelativoDocumento()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCorrelativoDocumento->BorrarCorrelativoDocumento($data);
		echo $this->json->json_response($resultado);
	}

}
