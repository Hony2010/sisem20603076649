<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMarcasMasVendidos extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Venta/sMarcasMasVendidos");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_Marca"];
		$data["FechaFinal"] = $input["FechaFinal_Marca"];
		$data["CantidadFila"] =  $input["CantidadFilas_Marca"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_Marca"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_Marca"];

		$resultado = $this->sMarcasMasVendidos->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Venta/cMarcasMasVendidos/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_Marca"];
		$data["FechaFinal"] = $input["FechaFinal_Marca"];
		$data["CantidadFila"] =  $input["CantidadFilas_Marca"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_Marca"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_Marca"];

		$resultado = $this->sMarcasMasVendidos->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Venta/cMarcasMasVendidos/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$input = $this->input->post("Data");

		$data["FechaInicial"] = $input["FechaInicio_Marca"];
		$data["FechaFinal"] = $input["FechaFinal_Marca"];
		$data["CantidadFila"] =  $input["CantidadFilas_Marca"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_Marca"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_Marca"];

		$resultado = $this->sMarcasMasVendidos->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sMarcasMasVendidos->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
