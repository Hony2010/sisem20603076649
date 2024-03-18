<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiCliente {

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
		$this->CI->load->service('Catalogo/sCliente');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
	}

	//SE AGREGAN LAS INSERCIONES A BASE DE DATOS DESDE API
	public function InsertarCliente($data)
	{
		try {
			$this->CI->db->trans_begin();

			$resultado = $this->CI->sCliente->InsertarCliente($data);

			if(is_array($resultado)) {
				$resultado["CodigoDocumentoIdentidad"] = $data["CodigoDocumentoIdentidad"];
				$resultado["NombreGradoAlumno"] = $data["NombreGradoAlumno"];

				$resultado2 = $this->CI->sCliente->InsertarJSONDesdeCliente($resultado);
				if(is_array($resultado2)) {
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

	public function ActualizarCliente($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sCliente->ActualizarCliente($data);

			if ($resultado == "") {
				$resultado2 = $this->CI->sCliente->ActualizarJSONDesdeCliente($data);

				if(is_array($resultado2)) {
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

	public function BorrarCliente($data)
	{
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sCliente->BorrarCliente($data);

			if ($resultado == "")
			{
				$resultado2 = $this->CI->sCliente->BorrarJSONDesdeCliente($data);

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
		} catch (Exception $e) {
			$this->CI->db->trans_rollback();
			return $e->getMessage();
		}
	}

	//PARA LAS VENTAS QUE SE IMPORTARAN
	public function ValidarClienteJSON($data)
	{
		$data["IdCliente"] = "";
		$data["IdPersona"] = "";
		return $data;
	}

	//PARA LA ACTUALIZACION DE LOS JSONs
	function InsertarClienteJSON($data)
	{
		$resultado = $this->ValidarClienteJSON($data);//$this->CI->sImportacionVenta->ValidarComprobanteVentaJSON($data);

		if(is_array($resultado)) {
			$response = $this->InsertarCliente($resultado);

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
	
	//PARA EXPORTACION
	function ConsultarClientesEnVentasJSON($data)
	{
		$resultado = $this->CI->sCliente->ConsultarClientesEnVentasJSON($data);
		return $resultado;
	}

}
