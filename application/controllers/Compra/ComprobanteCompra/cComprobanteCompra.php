<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cComprobanteCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Compra/sComprobanteCompra");
		// $this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->model("Base");
	}

	public function Index()
	{

	}

	public function InsertarComprobanteCompra()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteCompra->InsertarComprobanteCompra($data);

			if(is_array($resultado)) {
					$this->db->trans_commit();
					echo $this->json->json_response($resultado);
			}
			else {
			 	$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ActualizarComprobanteCompra()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteCompra->ActualizarComprobanteCompra($data);

			if(is_array($resultado)) {
				$resultado=$this->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);

				if(is_array($resultado)) {
					$this->db->trans_commit();
					echo $this->json->json_response($resultado);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado);
				}
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function BorrarComprobanteCompra()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sComprobanteCompra->BorrarComprobanteCompra($data);
		if ($resultado == "") {
			$input = $data["Filtros"];
			$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
			$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
			$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;
			$data["Filtros"]["totalfilas"] = $this->sComprobanteCompra->ObtenerNumeroTotalComprobantesCompra($input);
			$resultados["resultado"] = $data;
			$resultados["error"] = "";

			echo $this->json->json_response($resultados);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function ValidarEstadoComprobanteCompra()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sComprobanteCompra->ValidarEstadoComprobanteCompra($data);
		echo $this->json->json_response($resultado);
	}

	public function BuscarDocumentosIngreso()
	{
		try {
			$data = $this->input->post("Data");
			$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
			$data["FechaFin"] = convertToDate($data["FechaFin"]);
			$resultado = $this->sComprobanteCompra->BuscarDocumentosIngreso($data);

			if(is_array($resultado)) {
					echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 echo $this->json->json_response_error($e);
		}
	}

}
