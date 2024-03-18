<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cListaClientes extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->service("Reporte/Catalogo/sListaClientes");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL() {
		$input = $this->input->post("Data");
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_D"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_D"];
		$resultado = $this->sListaClientes->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Catalogo/cListaClientes/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}


	public function GenerarReportePDF() {
		$input = $this->input->post("Data");
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_D"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_D"];
		$resultado = $this->sListaClientes->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Catalogo/cListaClientes/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}


	public function GenerarReportePANTALLA() {
		$input = $this->input->post("Data");
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_D"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_D"];

		$resultado = $this->sListaClientes->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo() {
		$data= $this->input->get("nombre");
		$resultado = $this->sListaClientes->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}

}
