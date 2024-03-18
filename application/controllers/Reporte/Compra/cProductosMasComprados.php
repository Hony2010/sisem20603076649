<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cProductosMasComprados extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Compra/sProductosMasComprados");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_ProductoMasComprado"];
		$data["FechaFinal"] = $input["FechaFinal_ProductoMasComprado"];
		$data['CantidadFila'] = $input['CantidadFilas_ProductoMasComprado'];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_ProductoMasComprado"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_ProductoMasComprado"];

		$resultado = $this->sProductosMasComprados->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cProductosMasComprados/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_ProductoMasComprado"];
		$data["FechaFinal"] = $input["FechaFinal_ProductoMasComprado"];
		$data['CantidadFila'] = $input['CantidadFilas_ProductoMasComprado'];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_ProductoMasComprado"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_ProductoMasComprado"];

		$resultado = $this->sProductosMasComprados->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cProductosMasComprados/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_ProductoMasComprado"];
		$data["FechaFinal"] = $input["FechaFinal_ProductoMasComprado"];
		$data['CantidadFila'] = $input['CantidadFilas_ProductoMasComprado'];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_ProductoMasComprado"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_ProductoMasComprado"];

		$resultado = $this->sProductosMasComprados->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sProductosMasComprados->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
