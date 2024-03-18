<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDetalleNotaEntrada extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Inventario/sDetalleNotaEntrada");
    $this->load->library('json');
	}

	public function Index()
	{

	}

	public function ConsultarDetallesNotaEntrada()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleNotaEntrada->ConsultarDetallesNotaEntrada($data);
		echo $this->json->json_response($resultado);
	}

}
