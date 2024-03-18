<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDetalleNotaSalida extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Inventario/sDetalleNotaSalida");
    $this->load->library('json');
	}

	public function Index()
	{

	}

	public function ConsultarDetallesNotaSalida()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleNotaSalida->ConsultarDetallesNotaSalida($data);
		echo $this->json->json_response($resultado);
	}

}
