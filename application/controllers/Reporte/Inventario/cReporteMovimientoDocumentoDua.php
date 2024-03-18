<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cReporteMovimientoDocumentoDua extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Inventario/sReporteMovimientoDocumentoDua");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarJsonDuaProductoPorFiltros()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteMovimientoDocumentoDua->GenerarJsonDuaProductoPorFiltros($data);
		echo $this->json->json_response($resultado);
	}

	public function GenerarReporteEXCEL()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sReporteMovimientoDocumentoDua->GenerarReporteEXCEL($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cReporteMovimientoDocumentoDua/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sReporteMovimientoDocumentoDua->GenerarReportePDF($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cReporteMovimientoDocumentoDua/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}

	}

	public function GenerarReportePANTALLA()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteMovimientoDocumentoDua->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sReporteMovimientoDocumentoDua->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
