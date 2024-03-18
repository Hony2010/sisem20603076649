<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cBusquedaDocumentoCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
    $this->load->helper("date");
	}

	public function Index()
	{

	}

	public function BuscarComprobanteCompra()
	{
		try {
			$data = $this->input->post("Data");
			$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
			$data["FechaFin"] = convertToDate($data["FechaFin"]);
			$resultado = $this->sComprobanteCompra->ConsultarComprobantesCompraPorProveedorParaCostoAgregado($data);

			if(is_array($resultado)) {
					echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 echo $this->json->json_response_error($e);
		}
	}

}
