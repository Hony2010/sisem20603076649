<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiComprobanteVenta {

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
		$this->CI->load->service('Venta/sImportacionVenta');
	}

	function ObtenerDataComprobanteParaValidarJSON($data)
	{
		$resultado = $this->CI->sImportacionVenta->ObtenerDataComprobanteParaValidarJSON($data);
		return $resultado;
	}

	//PARA LA ACTUALIZACION DE LOS JSONs
	function InsertarVentaJSON($data)
	{
		$resultado = $this->CI->sImportacionVenta->ValidarDataComprobanteVenta($data);//$this->CI->sImportacionVenta->ValidarComprobanteVentaJSON($data);

		if(is_array($resultado)) {

			if($resultado["Estado"] == 0)
			{
				$response = $this->CI->sImportacionVenta->InsertarVenta($resultado);
			}
			else
			{
				$response = $this->CI->sImportacionVenta->ActualizarVenta($resultado);
			}

			if(is_array($response)) {
				$responseData["Estado"] = "CORRECTO";
				$responseData["CodigoEstado"] = "1";
				$responseData["CodigoHash"] = $response["CodigoHash"];
				// $response = array_merge($data, $response);
				return $responseData;
			}
			else {
				return $response;
			}
		}
		else {
			return $resultado;
		}
	}
	
	//ANULAR VENTA
	function AnularVentaJSON($data)
	{
		
		$resultado = $this->CI->sImportacionVenta->ValidarComprobanteVentAParaAnulacion($data);//$this->CI->sImportacionVenta->ValidarComprobanteVentaJSON($data);

		if(is_array($resultado)) {
			$resultado["RazonSocial"] = "";
			$response = array();
			if($resultado["IndicadorEstado"] != "N")
			{
				$response = $this->CI->sImportacionVenta->AnularVenta($resultado);
			}

			if(is_array($response)) {
				$response["Estado"] = "CORRECTO";
				$response["CodigoEstado"] = "1";
				$response = array_merge($data, $response);
				return $response;
			}
			else {
				return $response;
			}
		}
		else {
			return $resultado;
		}
	}

	//ELIMINAR VENTA
	function EliminarVentaJSON($data)
	{
		$resultado = $this->CI->sImportacionVenta->ValidarComprobanteVentAParaEliminacion($data);//$this->CI->sImportacionVenta->ValidarComprobanteVentaJSON($data);

		if(is_array($resultado)) {
			$resultado["RazonSocial"] = "";
			$response = array();
			if($resultado["IndicadorEstado"] != "E")
			{
				$response = $this->CI->sImportacionVenta->BorrarVenta($resultado);
			}

			if(is_array($response)) {
				$response["Estado"] = "CORRECTO";
				$response["CodigoEstado"] = "1";
				$response = array_merge($data, $response);
				return $response;
			}
			else {
				return $response;
			}
		}
		else {
			return $resultado;
		}
	}

	/***PARA JSON DE COMPROBANTES VENTA*/
	//PREPARAR DATA
	function PreparaData($data)
	{
		$nuevaData = array(
			"IdComprobanteVenta" => $data["IdComprobanteVenta"],
			"IdCliente" => $data["IdCliente"],
			"Documento" => $data["NombreAbreviado"]." - ".$data["SerieDocumento"]." - ".$data["NumeroDocumento"],
			"NumeroDocumento" => $data["NumeroDocumento"],
			"SerieDocumento" => $data["SerieDocumento"],
			"NombreAbreviado" => $data["NombreAbreviado"],
			"CodigoTipoDocumento" => $data["CodigoTipoDocumento"],
			"IdTipoVenta" => $data["IdTipoVenta"],
			"IdAsignacionSede" => $data["IdAsignacionSede"],
			"Observacion" => $data["Observacion"],
			"Total" => $data["Total"],
			"IdFormaPago" => $data["IdFormaPago"],
			"MontoACuenta" => $data["MontoACuenta"]
		);
		return $nuevaData;
	}

	function PrepararDataJSONComprobantesVenta($data)
	{
		foreach ($data as $key => $value) {
			$data[$key] = $this->PreparaData($value);
		}
		return $data;
	}

	//AQUI SE CREAN LAS CONSULTAS AL JSON
	function CrearJSONComprobanteVentaTodos() //FUNCION PARA EL RECREADO DEL JSON
	{
		//PARA CREAR EL JSON ComprobantesVenta
		$url = DIR_ROOT_ASSETS.'/data/comprobanteventa/comprobantesventa.json';
		$response = $this->CI->sComprobanteVenta->ConsultaComprobantesVentaParaJSON();
		$data_json = $this->PrepararDataJSONComprobantesVenta($response);
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $data_json);
		return $resultado;
	}

	//AQUI SE CREAN LAS CONSULTAS AL JSON
	function CrearJSONProformasTodos() {		
		$url = DIR_ROOT_ASSETS.'/data/proforma/proformas.json';
		$response = $this->CI->sComprobanteVenta->ConsultaProformasParaJSON();
		$data_json = $this->PrepararDataJSONComprobantesVenta($response);
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $data_json);
		return $resultado;
	}

	//FUNCIONES PARA EL CONTROLADOR, SE USAN DE TRATADO CON EL JSON
	function InsertarJSONDesdeComprobanteVenta($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/comprobanteventa/comprobantesventa.json';
		$fila = $this->CI->sComprobanteVenta->ObtenerComprobanteVentaPorIdComprobante($data);
		$fila = $this->PreparaData($fila);
		$resultado = $this->CI->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);
		return $resultado;
	}

	function ActualizarJSONDesdeComprobanteVenta($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/comprobanteventa/comprobantesventa.json';
		$fila = $this->CI->sComprobanteVenta->ObtenerComprobanteVentaPorIdComprobante($data);
		$fila = $this->PreparaData($fila);
		$resultado = $this->CI->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdComprobanteVenta");
		return $resultado;
	}

	function BorrarJSONDesdeComprobanteVenta($data)
	{
		// print_r($data);exit;
		$url = DIR_ROOT_ASSETS.'/data/comprobanteventa/comprobantesventa.json';
		$resultado = $this->CI->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdComprobanteVenta");
		return $resultado;
	}

	function ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($data)
	{
		$data["IdComprobanteVenta"] = (array_key_exists("IdComprobanteVenta", $data)) ? $data["IdComprobanteVenta"] : "";
		$resultado = $this->CI->sComprobanteVenta->TotalSaldosGuiaRemisionRemitenteEnDetalles($data);
		// print_r($resultado);exit;
		if(is_numeric($resultado["TotalSaldoPendienteGuiaRemision"]))
		{
			if($resultado["TotalSaldoPendienteGuiaRemision"] > 0)
			{
				$this->BorrarJSONDesdeComprobanteVenta($data);
				$this->InsertarJSONDesdeComprobanteVenta($data);
			}
			else{
				$this->BorrarJSONDesdeComprobanteVenta($data);
			}
		}
		$data["TotalSaldoPendienteGuiaRemision"] = $resultado["TotalSaldoPendienteGuiaRemision"];
		return $data;
	}

	//FUNCIONES PARA EL CONTROLADOR, SE USAN DE TRATADO CON EL JSON
	function InsertarJSONDesdeProforma($data) {
		$url = DIR_ROOT_ASSETS.'/data/proforma/proformas.json';
		$fila = $this->CI->sComprobanteVenta->ObtenerComprobanteVentaPorIdComprobante($data);
		$fila = $this->PreparaData($fila);
		$resultado = $this->CI->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);
		return $resultado;
	}

	function ActualizarJSONDesdeProforma($data) {
		$url = DIR_ROOT_ASSETS.'/data/proforma/proformas.json';
		$fila = $this->CI->sComprobanteVenta->ObtenerComprobanteVentaPorIdComprobante($data);
		$fila = $this->PreparaData($fila);
		$resultado = $this->CI->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdComprobanteVenta");
		return $resultado;
	}

	function BorrarJSONDesdeProforma($data) {		
		$url = DIR_ROOT_ASSETS.'/data/proforma/proformas.json';
		$resultado = $this->CI->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdComprobanteVenta");
		return $resultado;
	}
}
