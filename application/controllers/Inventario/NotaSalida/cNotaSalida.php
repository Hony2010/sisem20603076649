<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cNotaSalida extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Inventario/sNotaSalida");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
	}

	public function Index()
	{

	}

	public function InsertarNotaSalida()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sNotaSalida->InsertarNotaSalidaDesdeSalida($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				//ACTUALIZAMOS EL JSON
				$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesNotaSalida'], true);
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

	public function ActualizarNotaSalida()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sNotaSalida->ActualizarNotaSalida($data);

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

	public function BorrarNotaSalida() {
		$data = $this->input->post("Data");

		$resultado = $this->sNotaSalida->BorrarNotaSalida($data);
		if (!is_string($resultado)) {
			//ACTUALIZAMOS EL JSON
			$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesNotaSalida'], true);

			$input = $data["Filtros"];
			$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
			$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
			$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;

			$data["Filtros"]["totalfilas"] = $this->sNotaSalida->ObtenerNumeroTotalNotasSalida($input);

			$resultado = $data;

			echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response_error($resultado);
		}
	}

	public function ValidarEstadoNotaSalida()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sNotaSalida->ValidarEstadoNotaSalida($data);
		echo $this->json->json_response($resultado);
	}

	public function AnularNotaSalida()
	{
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sNotaSalida->AnularNotaSalida($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ImprimirNotaSalida()
	{
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sNotaSalida->ImprimirReporteNotaSalida($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function Imprimir2()
	{
		try {
			$data = $this->input->post("Data");
			$data["IdNotaSalida"] = "30";
			$resultado = $this->sNotaSalida->ImprimirReporteNotaSalida($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	//NUEVAS FUNCIONES
	public function ConsultarComprobantesVentaPorPersona()
	{
		$input = $this->input->get("Data");
		// $input["IdCliente"] = 3;
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);

		$output["resultado"] = $this->sNotaSalida->ConsultarComprobantesVentaPorPersona($input);

		echo $this->json->json_response($output);
	}

	public function ConsultarComprobantesCompraPorPersona()
	{
		$input = $this->input->get("Data");
		// $input["IdCliente"] = 3;
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);

		$output["resultado"] = $this->sNotaSalida->ConsultarComprobantesCompraPorPersona($input);

		echo $this->json->json_response($output);
	}

	public function ObtenerNotaSalidaVentaSinDocumento()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sNotaSalida->ObtenerNotaSalidaVentaSinDocumento($data);
		echo $this->json->json_response($resultado);
	}


}
