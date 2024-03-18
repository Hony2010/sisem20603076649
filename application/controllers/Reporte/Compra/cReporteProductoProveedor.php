<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cReporteProductoProveedor extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Compra/sReporteProductoProveedor");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function GenerarReporteEXCEL()
	{
		$input = $this->input->post("Data");
		$data["IdProducto"] = $input["IdProducto_ProductoProveedor"]==0 ? "%" : $input["TextoMercaderia_ProductoProveedor"];
		$data["IdProveedor"] = $input["IdProveedor_ProductoProveedor"]==0 ? "%" : $input["TextoProveedor_ProductoProveedor"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_ProductoProveedor"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_ProductoProveedor"];
		$resultado = $this->sReporteProductoProveedor->GenerarReporteEXCEL($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cReporteProductoProveedor/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePDF()
	{
		$input = $this->input->post("Data");
		$data["IdProducto"] = $input["IdProducto_ProductoProveedor"]==0 ? "%" : $input["TextoMercaderia_ProductoProveedor"];
		$data["IdProveedor"] = $input["IdProveedor_ProductoProveedor"]==0 ? "%" : $input["TextoProveedor_ProductoProveedor"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_ProductoProveedor"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_ProductoProveedor"];
		$resultado = $this->sReporteProductoProveedor->GenerarReportePDF($data);

		if ($resultado["error"] == "") {
				$resultado["url"] = site_url()."/Reporte/Compra/cReporteProductoProveedor/DescargarArchivo?nombre=".$resultado["reporte"];
				echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function GenerarReportePANTALLA()
	{
		$input = $this->input->post("Data");
		$data["IdProducto"] = $input["IdProducto_ProductoProveedor"]==0 ? "%" : $input["TextoMercaderia_ProductoProveedor"];
		$data["IdProveedor"] = $input["IdProveedor_ProductoProveedor"]==0 ? "%" : $input["TextoProveedor_ProductoProveedor"];
		$data["NombreArchivoJasper"] = $input["NombreArchivoJasper_ProductoProveedor"];
		$data["NombreArchivoReporte"] = $input["NombreArchivoReporte_ProductoProveedor"];
		$resultado = $this->sReporteProductoProveedor->GenerarReportePANTALLA($data);
		echo $this->json->json_response($resultado);
	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sReporteProductoProveedor->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}

}
