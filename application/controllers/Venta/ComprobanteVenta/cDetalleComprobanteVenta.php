<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDetalleComprobanteVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Venta/sDetalleComprobanteVenta");
    $this->load->library('json');
	}

	public function Index()
	{

	}

	public function ConsultarDetallesComprobanteVenta()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);
		echo $this->json->json_response($resultado);
	}

}
