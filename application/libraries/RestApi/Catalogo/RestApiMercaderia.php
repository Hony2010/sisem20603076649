<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiMercaderia {

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
		$this->CI->load->service('Catalogo/sMercaderia');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
	}

	//AQUI SE CREAN LAS CONSULTAS AL JSON
	function ActualizarProductoJSONDesdeInventario($data)
	{
		$parametros["Lote"] = $this->CI->sConstanteSistema->ObtenerParametroLote();
		$parametros["Zofra"] = $this->CI->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$parametros["Dua"] = $this->CI->sConstanteSistema->ObtenerParametroDua();

		$response = $this->ActualizarProductoJSON($data, $parametros);
		return $response;
	}

	function ActualizarProductoJSON($data, $parametros)
	{
		$url = DIR_ROOT_ASSETS.'/data/productos/'.$data["IdProducto"].'.json';
		$fila = $this->CI->sMercaderia->ObtenerFilaMercaderiaParaJSON($data, $parametros);
		$fila = array(0=>$fila);
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $fila);
		return $resultado;
	}	

	function CrearJSONMercaderiaTodos() {
		//PARA CREAR EL JSON Mercaderias
		$url = DIR_ROOT_ASSETS.'/data/mercaderia/mercaderias.json';
		$data_json = $this->CI->sMercaderia->PrepararDataJSONMercaderias();
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $data_json);

		$parametros["Lote"] = $this->CI->sConstanteSistema->ObtenerParametroLote();
		$parametros["Zofra"] = $this->CI->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$parametros["Dua"] = $this->CI->sConstanteSistema->ObtenerParametroDua();
		foreach ($data_json as $key => $value) {
			$this->ActualizarProductoJSON($value, $parametros);
		}

		return $resultado;
	}

	//FUNCIONES PARA EL CONTROLADOR, SE USAN DE TRATADO CON EL JSON
	function InsertarJSONDesdeMercaderia($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/mercaderia/mercaderias.json';
		$fila = $this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
		$resultado2 = $this->CI->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);

		$parametros["Lote"] = $this->CI->sConstanteSistema->ObtenerParametroLote();
		$parametros["Zofra"] = $this->CI->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$parametros["Dua"] = $this->CI->sConstanteSistema->ObtenerParametroDua();
		$resultado = $this->ActualizarProductoJSON($data, $parametros);
		return $resultado;
	}

	function ActualizarJSONDesdeMercaderia($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/mercaderia/mercaderias.json';
		$fila = $this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
		$resultado2 = $this->CI->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdProducto");

		$parametros["Lote"] = $this->CI->sConstanteSistema->ObtenerParametroLote();
		$parametros["Zofra"] = $this->CI->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$parametros["Dua"] = $this->CI->sConstanteSistema->ObtenerParametroDua();
		$resultado = $this->ActualizarProductoJSON($data, $parametros);
		return $resultado;
	}

	function BorrarJSONDesdeMercaderia($data)
	{
		$url = DIR_ROOT_ASSETS.'/data/mercaderia/mercaderias.json';
		$resultado = $this->CI->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdProducto");

		$url = DIR_ROOT_ASSETS.'/data/productos/'.$data["IdProducto"].'.json';
		$this->CI->archivo->EliminarArchivo($url);
		return $resultado;
	}

	//PARA LA ACTUALIZACION DE LOS JSONs
	function ObtenerFilaMercaderiaParaJSONMovimientos($url, $data, $parametros)
	{
		$parametroRubroRepuesto = $this->CI->sConstanteSistema->ObtenerParametroRubroRepuesto();
		if($parametroRubroRepuesto == 1)
		{
			$mercaderia = $this->CI->sMercaderia->ObtenerFilaMercaderiaParaJSON($data, $parametros);
			$mercaderia = array($mercaderia);
		}
		else
		{
			$mercaderia = $this->CI->jsonconverter->ObtenerDataArchivoJSON($url);
			// $nueva_fila = $this->CI->sMercaderia->PreparaDataProductoParaJSON($mercaderia[0]);
		}
		
		$nueva_fila = $mercaderia[0];
		$listas = $this->CI->sMercaderia->ObtenerDataListaMovimientos($mercaderia[0], $parametros);
		$response = array_merge($nueva_fila, $listas);
		return $response;
	}
			
	//DATA CON ACTUALIZACION DE LISTAS Y DE MERCADERIA
	function ObtenerFilaMercaderiaParaJSONMovimientosTodo($url, $data, $parametros)
	{
		$mercaderia = $this->CI->sMercaderia->ObtenerFilaMercaderiaParaJSON($data, $parametros);
		$response = $mercaderia;
		return $response;
	}

	function ActualizarProductoJSONParaMovimientos($data, $parametros)
	{
		$url = DIR_ROOT_ASSETS.'/data/productos/'.$data["IdProducto"].'.json';
		if($parametros["Mercaderia"] == false)
		{
			$fila = $this->ObtenerFilaMercaderiaParaJSONMovimientos($url, $data, $parametros);
		}
		else
		{
			$fila = $this->ObtenerFilaMercaderiaParaJSONMovimientosTodo($url, $data, $parametros);
		}


		$parametroRubroRepuesto = $this->CI->sConstanteSistema->ObtenerParametroRubroRepuesto();
		if($parametroRubroRepuesto == 1)
		{
			$url2 = DIR_ROOT_ASSETS.'/data/mercaderia/mercaderias.json';
			$fila2 = $this->CI->sMercaderia->ObtenerDataJSONFilaMercaderia($data);
			$resultado2 = $this->CI->jsonconverter->ActualizarFilaEnArchivoJSON($url2, $fila2, "IdProducto");
		}

		$fila = array(0=>$fila);
		$resultado = $this->CI->jsonconverter->CrearArchivoJSONData($url, $fila);

		return $resultado;
	}

	//SE CONSULTA EN LA API
	function ReemplazarFilasJSON($data, $option = false, $listas = false, $merca = false)
	{
		$objeto = $data;
		if($option) {
			$objeto = array();
			foreach ($data as $key => $value) {
				array_push($objeto,$value["IdProducto"]);
			}
		}
		
		$parametros["Listas"] = $listas;
		$parametros["Mercaderia"] = $merca;
		$parametros["Lote"] = $this->CI->sConstanteSistema->ObtenerParametroLote();
		$parametros["Zofra"] = $this->CI->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$parametros["Dua"] = $this->CI->sConstanteSistema->ObtenerParametroDua();				
		
		if (count($objeto) > 0) {
			
			$es_array = is_array($objeto[0]);
			if($es_array)
				$resultado = array_key_exists("IndicadorProducto",$objeto[0]);				
			else {
				$resultado = false;
			}

			if ($resultado == false) {
				$objeto = array_values(array_unique($objeto));
				foreach ($objeto as $key => $value) {
						if(is_numeric($value))
						{
							$objeto2['IdProducto'] = $value;
							$this->ActualizarProductoJSONParaMovimientos($objeto2, $parametros);
						}			
				}
			}
			else {
				foreach ($objeto as $key => $value) {
					if(is_numeric($value["IdProducto"])) {
						if ($value["IndicadorProducto"] == "") {
							$objeto2['IdProducto'] = $value["IdProducto"];
							$this->ActualizarProductoJSONParaMovimientos($objeto2, $parametros);
						}
					}
				}
			}
		}
		
		return $data;
	}

	function ActualizarProductosServidorJSON($data, $option = false, $listas = false, $merca = false)
	{
		// API URL
		$url = APP_PATH_HOST.'Api/Catalogo/ApiMercaderia/ActualizarJSON/';
	
		// User account login info
		$parseoData= array(
				'productos' => json_encode($data),
						'opcion' => $option,
						'listas' => $listas,
						'mercaderia' => $merca
		);
	
		$response = $this->CI->restapi->ConsultarDataApi($url, $parseoData);
	
		return $response;
	}

	function ActualizarProductosJSON($data, $option = false, $listas = false, $merca = false)
	{
		$jsonMercaderia = "";
		if(PARAMETRO_SERVIDOR_CLIENTE == 1)
		{
			$jsonMercaderia = $this->ReemplazarFilasJSON($data, $option, $listas, $merca);
		}
		else {
			$jsonMercaderia = $this->ActualizarProductosServidorJSON($data, $option, $listas, $merca);
		}
		return $jsonMercaderia;
	}

	function ReemplazarFilasJSONNuevosProductos($data, $option = false)
	{
		$objeto = $data;
		if($option) {
			$objeto = array();
			foreach ($data as $key => $value) {
				array_push($objeto,$value["IdProducto"]);
			}
		}
		$objeto = array_values(array_unique($objeto));
		foreach ($objeto as $key => $value) {
			if(is_numeric($value))
			{
				$objeto2['IdProducto'] = $value;
				$this->InsertarJSONDesdeMercaderia($objeto2);
			}
		}
		return $data;
	}
				
	//SE AGREGAN LAS INSERCIONES A BASE DE DATOS DESDE API
	public function InsertarMercaderia($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sMercaderia->InsertarMercaderia($data);

			if (is_array($resultado)) {
				$resultado2 = $this->InsertarJSONDesdeMercaderia($resultado);
				if(is_array($resultado2))	{
					$this->CI->db->trans_commit();
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

	public function ActualizarMercaderia($data) {
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sMercaderia->ActualizarMercaderia($data);

			if (is_array($resultado)) {
				$resultado2 = $this->ActualizarJSONDesdeMercaderia($data);
				if(is_array($resultado2)) {
					$this->CI->db->trans_commit();
					return $resultado;
				}
				else {
					$this->CI->CI->db->trans_rollback();
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

	public function BorrarMercaderia($data) {
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sMercaderia->BorrarMercaderia($data);

			if ($resultado == "") {
				$resultado2 = $this->BorrarJSONDesdeMercaderia($data);

				if(is_array($resultado2))	{
					$this->CI->db->trans_commit();
					return $data;
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

	//PARA LAS VENTAS QUE SE IMPORTARAN
	public function ValidarMercaderiaJSON($data)
	{
		$data["IdProducto"] = "";
		$data["CodigoMercaderia"] = "";
		$data["CodigoAutomatico"] = "0";
		return $data;
	}

	//PARA LA ACTUALIZACION DE LOS JSONs
	function InsertarMercaderiaJSON($data)
	{
		$resultado = $this->ValidarMercaderiaJSON($data);//$this->CI->sImportacionVenta->ValidarComprobanteVentaJSON($data);

		if(is_array($resultado)) {
			$response = $this->InsertarMercaderia($resultado);

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
	
	//PARA EXPORTAR
	public function ConsultarMercaderiasEnVentasJSON($data)
	{
		$resultado = $this->CI->sMercaderia->ConsultarMercaderiasEnVentasJSON($data);
		return $resultado;
	}

	function CrearJSONProducto($data)	{
		$parametros["Lote"] = $this->CI->sConstanteSistema->ObtenerParametroLote();
		$parametros["Zofra"] = $this->CI->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$parametros["Dua"] = $this->CI->sConstanteSistema->ObtenerParametroDua();		
		$resultado=$this->ActualizarProductoJSON($data, $parametros);
		return $resultado;
	}
}	
