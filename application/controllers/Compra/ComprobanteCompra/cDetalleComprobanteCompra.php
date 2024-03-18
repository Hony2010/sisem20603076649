<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDetalleComprobanteCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Compra/sDetalleComprobanteCompra");
		$this->load->service("Compra/sDetalleCompraGasto");
		$this->load->service("Compra/sDetalleCompraCostoAgregado");
    	$this->load->library('json');
	}

	public function Index()
	{

	}

	public function ConsultarDetallesComprobanteCompra()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarDetallesCompraGasto()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleCompraGasto->ConsultarDetallesCompraGasto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarDetallesCompraCostoAgregado()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleCompraCostoAgregado->ConsultarDetallesCompraCostoAgregado($data);
		echo $this->json->json_response($resultado);
	}

}
