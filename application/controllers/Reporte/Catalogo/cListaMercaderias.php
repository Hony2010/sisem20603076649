<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cListaMercaderias extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->service("Reporte/Catalogo/sListaMercaderias");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL() {
		$input = $this->input->post("Data");
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_M"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_M"];
		$resultado = $this->sListaMercaderias->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Catalogo/cListaMercaderias/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}


	public function GenerarReportePDF() {
		$input = $this->input->post("Data");
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_M"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_M"];
		$resultado = $this->sListaMercaderias->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Catalogo/cListaMercaderias/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}


	public function GenerarReportePANTALLA() {
		$input = $this->input->post("Data");
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_M"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_M"];

		$resultado = $this->sListaMercaderias->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo() {
		$data= $this->input->get("nombre");
		$resultado = $this->sListaMercaderias->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}

}
