<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cReporteFormato8_1Compra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Compra/sReporteFormato8_1Compra");
		$this->load->service("Configuracion/General/sPeriodo");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$data = $this->input->post("Data");
		$data["Año"] = $data["Anio"];

		$resultado = $this->sReporteFormato8_1Compra->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cReporteFormato8_1Compra/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$data = $this->input->post("Data");
		$data["Año"] = $data["Anio"];

		$resultado = $this->sReporteFormato8_1Compra->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cReporteFormato8_1Compra/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$data = $this->input->post("Data");
		$data["Año"] = $data["Anio"];
		$resultado = $this->sReporteFormato8_1Compra->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sReporteFormato8_1Compra->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}

	public function ListarPeriodoPorAno()
	{
		$data = $this->input->post("Data");
		$data["Año"] = $data["Ano"];
		$resultado = $this->sPeriodo->ListarPeriodoPorAño($data);
		echo $this->json->json_response($resultado);
	}
}
