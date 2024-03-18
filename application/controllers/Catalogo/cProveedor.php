<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cProveedor extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sProveedor");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
	}

	public function InsertarProveedor()
	{
		try {


			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sProveedor->InsertarProveedor($data);

			if(is_array($resultado)) {
				$output["resultado"] = $resultado;
				$resultado["CodigoDocumentoIdentidad"] = $data["CodigoDocumentoIdentidad"];

				$resultado2 = $this->sProveedor->InsertarJSONDesdeProveedor($resultado);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/proveedor/proveedores.json');

					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;

					$numerofilasporpagina = $this->sProveedor->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sProveedor->ObtenerNumeroTotalProveedores($data);
					$output["data"] = $data;
					$output["Filtros"] = array(
						"textofiltro" => "",
						"numerofilasporpagina" => $numerofilasporpagina,
						"totalfilas" => $TotalFilas,
						"paginadefecto" => 2);

					echo $this->json->json_response($output);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
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

	public function ActualizarProveedor()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sProveedor->ActualizarProveedor($data);

			//echo $this->json->json_response($resultado);
			if (is_array($resultado)) {
				$resultado2 = $this->sProveedor->ActualizarJSONDesdeProveedor($data);
				
				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/proveedor/proveedores.json');
					echo $this->json->json_response($resultado);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
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

	public function BorrarProveedor()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");

			$resultado = $this->sProveedor->BorrarProveedor($data);

			if ($resultado == "")
			{
				$resultado2 = $this->sProveedor->BorrarJSONDesdeProveedor($data);
				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/proveedor/proveedores.json');

					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;

					$numerofilasporpagina = $this->sProveedor->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sProveedor->ObtenerNumeroTotalProveedores($data);
					$output["msg"] = $resultado;

					$output["Filtros"] = array(
						"textofiltro" => "",
						"numerofilasporpagina" => $numerofilasporpagina	,
						"totalfilas" => $TotalFilas,
						"paginadefecto" => 2);

					echo $this->json->json_response($output);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
				}
			}
			else
			{
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}

	public function SubirFoto()
	{
		try {
			$IdPersona = $this->input->post("IdPersona");
			$InputFileName = $this->input->post("InputFileName");
			$patcher = DIR_ROOT_ASSETS.'/img/Proveedor/'.$IdPersona.'/';
			//$patcher = site_url().'../img/proveedor/';
			//$config['upload_path'] = '../img/proveedor/'.$IdProducto.'/';
			$config['upload_path'] = $patcher;

			$resultado = $this->shared->upload_file($InputFileName,$config);

			//print_r($resultado."\n");
			//print_r($resultado);
			//print_r($config['upload_path']);
			//print_r($config);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ObtenerProveedorPorIdPersona()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sProveedor->ObtenerProveedorPorIdPersona($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSunat()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sProveedor->ConsultarSunat($data);
		echo $this->json->json_response($resultado);
	}
	public function ConsultarReniec()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sProveedor->ConsultarReniec($data);
		echo $this->json->json_response($resultado);
	}


}
