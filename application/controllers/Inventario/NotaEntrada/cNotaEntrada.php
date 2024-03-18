<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cNotaEntrada extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Inventario/sNotaEntrada");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("Inventario/sDocumentoReferenciaNotaEntrada");
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

	public function InsertarNotaEntrada()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"),true);						
			$resultado = $this->sNotaEntrada->InsertarNotaEntradaDesdeEntrada($data);
			
			if(is_array($resultado)) {				
				$this->db->trans_commit();
				//ACTUALIZAMOS EL JSON
				$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesNotaEntrada'], true);
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

	public function ActualizarNotaEntrada()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sNotaEntrada->ActualizarNotaEntrada($data);
			// $resultado = $this->sNotaEntrada->ActualizarNotaEntradaDesdeNotaEntrada($data);

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

	public function BorrarNotaEntrada() {
		$data = $this->input->post("Data");

		$resultado = $this->sNotaEntrada->BorrarNotaEntrada($data);
		if (!is_string($resultado)) {
			//ACTUALIZAMOS EL JSON
			$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesNotaEntrada'], true);

			$input = $data["Filtros"];
			$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
			$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
			$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;

			$data["Filtros"]["totalfilas"] = $this->sNotaEntrada->ObtenerNumeroTotalNotasEntrada($input);

			$resultado = $data;

			echo $this->json->json_response($resultado);
		}
		else {
			echo $this->json->json_response_error($resultado);
		}
	}

	public function ValidarEstadoNotaEntrada()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sNotaEntrada->ValidarEstadoNotaEntrada($data);
		echo $this->json->json_response($resultado);
	}

	public function AnularNotaEntrada()
	{
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sNotaEntrada->AnularNotaEntrada($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ImprimirNotaEntrada()
	{
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sNotaEntrada->ImprimirReporteNotaEntrada($data);
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
			$data["IdNotaEntrada"] = "30";
			$resultado = $this->sNotaEntrada->ImprimirReporteNotaEntrada($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	//NUEVAS FUNCIONES
	public function ConsultarDocumentosReferencia()
	{
		$data = $this->input->get("Data");
		$resultado= $this->sDocumentoReferenciaNotaEntrada->ConsultarDocumentosReferencia($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarComprobantesVentaPorPersona()
	{
		$input = $this->input->get("Data");
		// $input["IdCliente"] = 3;
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);

		$output["resultado"] = $this->sNotaEntrada->ConsultarComprobantesVentaPorPersona($input);

		echo $this->json->json_response($output);
	}

	public function ConsultarComprobantesCompraPorPersona()
	{
		$input = $this->input->get("Data");
		// $input["IdCliente"] = 3;
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);

		$output["resultado"] = $this->sNotaEntrada->ConsultarComprobantesCompraPorPersona($input);

		echo $this->json->json_response($output);
	}


}
