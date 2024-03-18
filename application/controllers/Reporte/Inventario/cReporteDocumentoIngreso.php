<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cReporteDocumentoIngreso extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Inventario/sReporteDocumentoIngreso");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarJsonDocumentoIngresoPorFiltros()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteDocumentoIngreso->GenerarJsonDocumentoIngresoPorFiltros($data);
		echo $this->json->json_response($resultado);
	}

	public function GenerarReporteEXCEL()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sReporteDocumentoIngreso->GenerarReporteEXCEL($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cReporteDocumentoIngreso/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sReporteDocumentoIngreso->GenerarReportePDF($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cReporteDocumentoIngreso/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}

	}

	public function GenerarReportePANTALLA()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteDocumentoIngreso->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sReporteDocumentoIngreso->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
