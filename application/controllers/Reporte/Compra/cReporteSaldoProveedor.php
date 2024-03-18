<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cReporteSaldoProveedor extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Compra/sReporteSaldoProveedor");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteSaldoProveedor->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cReporteSaldoProveedor/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteSaldoProveedor->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cReporteSaldoProveedor/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sReporteSaldoProveedor->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sReporteSaldoProveedor->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}

}
