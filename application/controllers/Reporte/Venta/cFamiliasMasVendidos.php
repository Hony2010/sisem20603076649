<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cFamiliasMasVendidos extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Venta/sFamiliasMasVendidos");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_Familia"];
		$data["FechaFinal"] = $input["FechaFinal_Familia"];
		$data["CantidadFila"] =  $input["CantidadFilas_Familia"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_Familia"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_Familia"];

		$resultado = $this->sFamiliasMasVendidos->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Venta/cFamiliasMasVendidos/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_Familia"];
		$data["FechaFinal"] = $input["FechaFinal_Familia"];
		$data["CantidadFila"] =  $input["CantidadFilas_Familia"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_Familia"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_Familia"];

		$resultado = $this->sFamiliasMasVendidos->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Venta/cFamiliasMasVendidos/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_Familia"];
		$data["FechaFinal"] = $input["FechaFinal_Familia"];
		$data["CantidadFila"] =  $input["CantidadFilas_Familia"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_Familia"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_Familia"];

		$resultado = $this->sFamiliasMasVendidos->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sFamiliasMasVendidos->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
