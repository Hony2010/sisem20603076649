<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cListaCostosAgregados extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->service("Reporte/Catalogo/sListaCostosAgregados");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL() {
		$data = $this->input->post("Data");
		$resultado = $this->sListaCostosAgregados->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Catalogo/cListaCostosAgregados/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}


	public function GenerarReportePDF() {
		$data = $this->input->post("Data");
		$resultado = $this->sListaCostosAgregados->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Catalogo/cListaCostosAgregados/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}


	public function GenerarReportePANTALLA() {
		$data = $this->input->post("Data");
		$resultado = $this->sListaCostosAgregados->GenerarReportePANTALLA($data);

		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo() {
		$data= $this->input->get("nombre");
		$resultado = $this->sListaCostosAgregados->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}

}
