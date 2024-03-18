<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiGuiaRemisionRemitente {

	public $CI;

	function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}

		$this->CI->load->library('RestApi/Venta/RestApiComprobanteVenta');
		$this->CI->load->service('Configuracion/General/sEmpresa');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
		$this->CI->load->service("Venta/sComprobanteVenta");
		$this->CI->load->service("Venta/sGuiaRemisionRemitente");
		$this->CI->load->service("Venta/sDetalleGuiaRemisionRemitente");
		$this->CI->load->service("FacturacionElectronica/sGuiaRemisionRemitenteElectronica");
	}

	//
	public function InsertarGuiaRemisionRemitente($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sGuiaRemisionRemitente->InsertarGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				$response=$this->CI->sGuiaRemisionRemitenteElectronica->GenerarXMLGuiaRemisionRemitenteElectronica($resultado);

				$resultado["NombreArchivoComprobante"] = $response["NombreArchivoComprobante"];
				$resultado["NombreAbreviado"] = $response["NombreAbreviado"];
				// $resultado["NombreTipoDocumento"] = $response["NombreTipoDocumento"];
				if(is_array($response)) {
					$response = $this->CI->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);
					if(is_array($response))
					{
						$this->CI->db->trans_commit();
						echo $this->CI->json->json_response($resultado);
					}
					else
					{
						$this->CI->db->trans_rollback();
						echo $this->CI->json->json_response_error($response);
					}
				}
				else {
					$this->CI->db->trans_rollback();
					echo $this->CI->json->json_response_error($response);
				}
			}
			else {
			 	$this->CI->db->trans_rollback();
				echo $this->CI->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->CI->db->trans_rollback();
			 echo $this->CI->json->json_response_error($e);
		}
	}

	public function ActualizarGuiaRemisionRemitente($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sGuiaRemisionRemitente->ActualizarGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				$resultado=$this->CI->sGuiaRemisionRemitenteElectronica->GenerarXMLGuiaRemisionRemitenteElectronica($resultado);
				if(is_array($resultado)) {
					$response = $this->CI->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);
					if(is_array($response)) {
						$this->CI->db->trans_commit();
						echo $this->CI->json->json_response($resultado);
					}
					else
					{
						$this->CI->db->trans_rollback();
						echo $this->CI->json->json_response_error($response);
					}
				}
				else {
					$this->CI->db->trans_rollback();
					echo $this->CI->json->json_response_error($resultado);
				}
			}
			else {
				$this->CI->db->trans_rollback();
				echo $this->CI->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->CI->db->trans_rollback();
			 echo $this->CI->json->json_response_error($e);
		}
	}

	public function AnularGuiaRemisionRemitente($data) {
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sGuiaRemisionRemitente->AnularGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				// print_r($resultado);exit;
				$response = $this->CI->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);
				if(is_array($response)) {
					$this->CI->db->trans_commit();
					echo $this->CI->json->json_response($resultado);
				}
				else
				{
					$this->CI->db->trans_rollback();
					echo $this->CI->json->json_response_error($response);
				}
			}
			else {
				$this->CI->db->trans_rollback();
				echo $this->CI->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->CI->db->trans_rollback();
			 echo $this->CI->json->json_response_error($e);
		}
	}

	public function BorrarGuiaRemisionRemitente() {
		try {
			$this->CI->db->trans_begin();
			$data = json_decode($this->CI->input->post("Data"), true);
			$resultado = $this->CI->sGuiaRemisionRemitente->BorrarGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				$input = $data["Filtros"];
				$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
				$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
				$input["TextoFiltro"]=($input["TextoFiltro"] != "") ? $input["TextoFiltro"] : '%' ;
				$data["Filtros"]["totalfilas"] = $this->CI->sGuiaRemisionRemitente->ObtenerNumeroTotalGuiasRemisionRemitente($input);
				$resultado = $data;
				$response = $this->CI->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);
				if(is_array($response)) {
					$this->CI->db->trans_commit();
					echo $this->CI->json->json_response($resultado);
				}
				else
				{
					$this->CI->db->trans_rollback();
					echo $this->CI->json->json_response_error($response);
				}
			}
			else {
				$this->CI->db->trans_rollback();
				echo $this->CI->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->CI->db->trans_rollback();
			 echo $this->CI->json->json_response_error($e);
		}
	}
}
