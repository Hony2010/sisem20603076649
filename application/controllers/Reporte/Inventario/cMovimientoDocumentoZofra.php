<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMovimientoDocumentoZofra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Inventario/sMovimientoDocumentoZofra");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarJsonDuaProductoPorFiltros()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMovimientoDocumentoZofra->GenerarJsonDuaProductoPorFiltros($data);
		echo $this->json->json_response($resultado);
	}

	public function GenerarReporteEXCEL()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sMovimientoDocumentoZofra->GenerarReporteEXCEL($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cMovimientoDocumentoZofra/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sMovimientoDocumentoZofra->GenerarReportePDF($data);
		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Inventario/cMovimientoDocumentoZofra/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}

	}

	public function GenerarReportePANTALLA()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMovimientoDocumentoZofra->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sMovimientoDocumentoZofra->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
