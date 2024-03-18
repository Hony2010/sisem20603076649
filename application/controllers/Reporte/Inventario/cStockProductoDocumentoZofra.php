<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cStockProductoDocumentoZofra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Inventario/sStockProductoDocumentoZofra");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarJsonDocumentoZofraPorFiltros()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sStockProductoDocumentoZofra->GenerarJsonDocumentoZofraPorFiltros($data);
		echo $this->json->json_response($resultado);
	}

	public function GenerarReporteEXCEL()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sStockProductoDocumentoZofra->GenerarReporteEXCEL($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cStockProductoDocumentoZofra/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sStockProductoDocumentoZofra->GenerarReportePDF($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cStockProductoDocumentoZofra/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}

	}

	public function GenerarReportePANTALLA()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sStockProductoDocumentoZofra->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sStockProductoDocumentoZofra->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
