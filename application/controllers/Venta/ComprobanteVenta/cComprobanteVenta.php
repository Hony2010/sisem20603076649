<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cComprobanteVenta extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("Configuracion/General/sConstanteSistema");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('sesionusuario');
	}

	public function Index() {

	}

	public function InsertarComprobanteVenta() {
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteVenta->InsertarComprobanteVenta($data);

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

	public function ActualizarComprobanteVenta() {
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteVenta->ActualizarComprobanteVenta($data);

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

	public function BorrarComprobanteVenta() {
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");

			$resultado = $this->sComprobanteVenta->BorrarComprobanteVenta($data);
			if (!is_string($resultado)) {
				$this->db->trans_commit();

				$input = $data["Filtros"];
				$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
				$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
				$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;

				$data["Filtros"]["totalfilas"] = $this->sComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($input);

				$resultado = $data;

				echo $this->json->json_response($resultado);
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ValidarEstadoComprobanteVenta() {
		$data = $this->input->get("Data");
		$resultado = $this->sComprobanteVenta->ValidarEstadoComprobanteVenta($data);
		echo $this->json->json_response($resultado);
	}

	public function AnularComprobanteVenta() {
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteVenta->AnularComprobanteVenta($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ImprimirComprobanteVenta() {
		try {
			$data = $this->input->post("Data");
			$resultado = "";

			$indicadorVistaPreviaImpresion = $this->sesionusuario->obtener_sesion_indicador_vista_previa_impresion();
			if($indicadorVistaPreviaImpresion == '0')
			{
				if(is_numeric($data["SerieDocumento"]))
				{
					$resultado = $this->sComprobanteVenta->ImprimirReporteComprobanteVenta($data);
				}
				else
				{
					$cantidadImpresion = $this->sConstanteSistema->ObtenerParametroCantidadImpresion();
					
					for ($i=1; $i <= $cantidadImpresion; $i++) { 
						$resultado = $this->sComprobanteVenta->ImprimirReporteComprobanteVenta($data);
					}
				}
			}
			else
			{
				$resultado = $this->sComprobanteVenta->GenerarVistaPreviaPDF($data);
			}

			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ConsultarComprobantesVentaReferencia() {
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteVenta->ConsultarComprobantesVentaReferencia();
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ConsultarComprobantesVentaProforma() {
		try {
			$data = $this->input->post("Data");
			$resultado = $this->sComprobanteVenta->ConsultarComprobantesVentaProforma();
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	
	public function ConsultarVentasProformas() {

		$input = $this->input->post("Data");
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sComprobanteVenta->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sComprobanteVenta->ObtenerNumeroTotalVentasProforma($input);
		$output["resultado"] = $this->sComprobanteVenta->ConsultarVentasProformas($input, $input["pagina"], $numerofilasporpagina);
		$output["Filtros"] = array_merge(
			$input,
			array(
				"numerofilasporpagina" => $numerofilasporpagina,
				"totalfilas" => $TotalFilas,
				"paginadefecto" => 1
			)
		);

		echo $this->json->json_response($output);
	}

	public function ConsultarVentasProformasPorPagina() {
		$input = $this->input->post("Data");		
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$resultado = $this->sComprobanteVenta->ConsultarVentasProformas($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
	
}
