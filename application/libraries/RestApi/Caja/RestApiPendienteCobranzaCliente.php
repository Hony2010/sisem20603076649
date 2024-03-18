<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiPendienteCobranzaCliente {

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
		$this->CI->load->service('Caja/sPendienteCobranzaCliente');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
	}

	function PrepararDataJSONPendientesCobranzaCliente($data)
	{
		foreach ($data as $key => $value) {
			$data[$key] = $this->PreparaData($value);
		}
		return $data;
	}

	//AQUI SE CREAN LAS CONSULTAS AL JSON
	function CrearJSONPendienteCobranzaClienteTodos() //FUNCION PARA EL RECREADO DEL JSON
	{
		//PARA CREAR EL JSON PendientesCobranzaCliente
		$url = DIR_ROOT_ASSETS.'/data/pendientecobranzacliente/pendientescobranzacliente.json';
		$response = $this->CI->sPendienteCobranzaCliente->ConsultasPendientesCobranzaClienteParaJSON();
		$data_json = $this->PrepararDataJSONPendientesCobranzaCliente($response);
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $data_json);
		return $resultado;
	}

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
			"MontoOriginal" => $data["MontoOriginal"]
		);
		return $nuevaData;
	}

	//FUNCIONES PARA EL CONTROLADOR, SE USAN DE TRATADO CON EL JSON
	function InsertarJSONDesdePendienteCobranzaCliente($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/pendientecobranzacliente/pendientescobranzacliente.json';
		$fila = $this->CI->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data)[0];
		$fila = $this->PreparaData($fila);//$this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
		$resultado = $this->CI->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);
		// print_r($resultado);exit;
		return $resultado;
	}

	function ActualizarJSONDesdePendienteCobranzaCliente($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/pendientecobranzacliente/pendientescobranzacliente.json';
		$fila = $this->CI->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data)[0];
		$fila = $this->PreparaData($fila);//$this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
		$resultado = $this->CI->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdComprobanteVenta");
		return $resultado;
	}

	function BorrarJSONDesdePendienteCobranzaCliente($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/pendientecobranzacliente/pendientescobranzacliente.json';
		$resultado = $this->CI->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdComprobanteVenta");
		return $resultado;
	}

	function ActualizarJSONPendientesDesdeCobranza($data)
	{
		foreach ($data as $key => $value) {
			if(array_key_exists("IdComprobanteVenta", $value))
			{
				if(is_numeric($value["IdComprobanteVenta"]))
				{
					$fila = $this->CI->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value)[0];
					if($fila["SaldoPendiente"] > 0)
					{
						//NO PASA NADA
						$this->BorrarJSONDesdePendienteCobranzaCliente($value);
						$this->InsertarJSONDesdePendienteCobranzaCliente($value);
					}
					else
					{
						$this->BorrarJSONDesdePendienteCobranzaCliente($value);
					}
				}
			}
		}
	}
}	
