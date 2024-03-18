<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiVenta {

	public $CI;

	function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}

		$this->CI->load->library('jsonconverter');
		$this->CI->load->library('restapi');
		$this->CI->load->library('archivo');
		$this->CI->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->CI->load->service("Venta/sVenta");
		$this->CI->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->CI->load->service('Configuracion/General/sEmpresa');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
		$this->CI->load->service('Venta/sComprobanteVenta');

	}

	public function InsertarVenta($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sVenta->InsertarVenta($data);
			if(is_array($resultado)) {
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
					$resultado2=$this->CI->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);

					$resultado["NombreArchivoComprobante"] = $resultado2["NombreArchivoComprobante"];
					$resultado["NombreAbreviado"] = $resultado2["NombreAbreviado"];
					$resultado["NombreTipoDocumento"] = $resultado2["NombreTipoDocumento"];
				}
				else {
					$resultado2 = $resultado;
				}

				if(is_array($resultado2)) {
					$this->CI->db->trans_commit();
					if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
						$jsonMercaderia = $this->CI->restapimercaderia->ActualizarProductosJSON($data['DetallesComprobanteVenta'], true);
					}
					return $resultado;
				}
				else {
					$this->CI->db->trans_rollback();
					return $resultado2;
				}
			}
			else {
			 	$this->CI->db->trans_rollback();
				return $resultado;
			}
		}
		catch (Exception $e) {
			 $this->CI->db->trans_rollback();
			 return $e->getMessage();
		}
	}
	
	public function ActualizarVenta($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sVenta->ActualizarVenta($data);
			if(is_array($resultado)) {
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
					$resultado=$this->CI->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);
				}
				if(is_array($resultado)) {
					$this->CI->db->trans_commit();
					if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
						$jsonMercaderia = $this->CI->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle']);
					}
					return $resultado;
				}
				else {
					$this->CI->db->trans_rollback();
					return $resultado;
				}
			}
			else {
				$this->CI->db->trans_rollback();
				return $resultado;
			}
		}
		catch (Exception $e) {
			 $this->CI->db->trans_rollback();
			 return $e->getMessage();
		}
	}

	public function AnularVenta($data) {
		try {
			$resultado = $this->CI->sVenta->AnularVenta($data);

			if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
				$jsonMercaderia = $this->CI->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteVenta'], true);
			}
			return $resultado;
		}
		catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function BorrarVenta($data) {
		$resultado = $this->CI->sVenta->EliminarVenta($data);
		if (!is_string($resultado)) {
			$input = $data["Filtros"];
			$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
			$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
			$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;

			$data["Filtros"]["totalfilas"] = $this->CI->sComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($input);

			if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
				$jsonMercaderia = $this->CI->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteVenta'], true);
			}

			// $resultado = $data;
			$resultado["Filtros"] = $input;
			return $resultado;
		}
		else {
			return $resultado;
		}
	}
}
