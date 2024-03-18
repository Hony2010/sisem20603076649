<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiPendientePagoProveedor {

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
		$this->CI->load->service('Caja/sPendientePagoProveedor');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
	}

	function PrepararDataJSONPendientesPagoProveedor($data)
	{
		foreach ($data as $key => $value) {
			$data[$key] = $this->PreparaData($value);
		}
		return $data;
	}

	//AQUI SE CREAN LAS CONSULTAS AL JSON
	function CrearJSONPendientePagoProveedorTodos() //FUNCION PARA EL RECREADO DEL JSON
	{
		//PARA CREAR EL JSON PendientesPagoProveedor
		$url = DIR_ROOT_ASSETS.'/data/pendientepagoproveedor/pendientespagoproveedor.json';
		$response = $this->CI->sPendientePagoProveedor->ConsultasPendientesPagoProveedorParaJSON();
		$data_json = $this->PrepararDataJSONPendientesPagoProveedor($response);
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $data_json);
		return $resultado;
	}

	//PREPARAR DATA
	function PreparaData($data)
	{
		$nuevaData = array(
			"IdComprobanteCompra" => $data["IdComprobanteCompra"],
			"IdProveedor" => $data["IdProveedor"],
			"Documento" => $data["NombreAbreviado"]." - ".$data["SerieDocumento"]." - ".$data["NumeroDocumento"],
			"NumeroDocumento" => $data["NumeroDocumento"],
			"SerieDocumento" => $data["SerieDocumento"],
			"NombreAbreviado" => $data["NombreAbreviado"],
			"CodigoTipoDocumento" => $data["CodigoTipoDocumento"],
			"MontoOriginal" => $data["MontoOriginal"]
		);
		return $nuevaData;
	}

	//FUNCIONES PARA EL CONTROLADOR, SE USAN DE TRATADO CON EL JSON
	function InsertarJSONDesdePendientePagoProveedor($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/pendientepagoproveedor/pendientespagoproveedor.json';
		$fila = $this->CI->sPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdComprobanteCompra($data)[0];
		$fila = $this->PreparaData($fila);//$this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
		$resultado = $this->CI->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);
		// print_r($resultado);exit;
		return $resultado;
	}

	function ActualizarJSONDesdePendientePagoProveedor($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/pendientepagoproveedor/pendientespagoproveedor.json';
		$fila = $this->CI->sPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdComprobanteCompra($data)[0];
		$fila = $this->PreparaData($fila);//$this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
		$resultado = $this->CI->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdComprobanteCompra");
		return $resultado;
	}

	function BorrarJSONDesdePendientePagoProveedor($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/pendientepagoproveedor/pendientespagoproveedor.json';
		$resultado = $this->CI->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdComprobanteCompra");
		return $resultado;
	}

	function ActualizarJSONPendientesDesdePago($data)
	{
		foreach ($data as $key => $value) {
			if(array_key_exists("IdComprobanteCompra", $value))
			{
				if(is_numeric($value["IdComprobanteCompra"]))
				{
					$fila = $this->CI->sPendientePagoProveedor->ObtenerPendientePagoProveedorPorIdComprobanteCompra($value)[0];
					if($fila["SaldoPendiente"] > 0)
					{
						//NO PASA NADA
						$this->BorrarJSONDesdePendientePagoProveedor($value);
						$this->InsertarJSONDesdePendientePagoProveedor($value);
					}
					else
					{
						$this->BorrarJSONDesdePendientePagoProveedor($value);
					}
				}
			}
		}
	}
}	
