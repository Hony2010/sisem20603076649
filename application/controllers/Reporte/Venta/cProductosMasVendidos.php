<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cProductosMasVendidos extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Venta/sProductosMasVendidos");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_MAS"];
		$data["FechaFinal"] = $input["FechaFinal_MAS"];
		$data['CantidadFila'] = $input['CantidadFilas_MAS'];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_MAS"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_MAS"];
		$data["OrdenadoPor"] = $input["OrdenadoPor"];

		$resultado = $this->sProductosMasVendidos->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Venta/cProductosMasVendidos/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}

	}

	public function GenerarReportePDF()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_MAS"];
		$data["FechaFinal"] = $input["FechaFinal_MAS"];
		$data['CantidadFila'] = $input['CantidadFilas_MAS'];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_MAS"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_MAS"];
		$data["OrdenadoPor"] = $input["OrdenadoPor"];

		$resultado = $this->sProductosMasVendidos->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Venta/cProductosMasVendidos/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_MAS"];
		$data["FechaFinal"] = $input["FechaFinal_MAS"];
		$data['CantidadFila'] = $input['CantidadFilas_MAS'];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_MAS"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_MAS"];
		$data["OrdenadoPor"] = $input["OrdenadoPor"];
		
		$resultado = $this->sProductosMasVendidos->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sProductosMasVendidos->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
