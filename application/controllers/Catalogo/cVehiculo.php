<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cVehiculo extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sVehiculo");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}
	
	public function Index()
	{
		
	}

	public function ListarVehiculos()
	{
		$resultado = $this->sVehiculo->ListarVehiculos();
		echo $this->json->json_response($resultado);
	}

	public function InsertarVehiculo()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$filtro = $this->input->post("Filtro");

			$resultado = $this->sVehiculo->InsertarVehiculoDesdeVehiculo($data);
			if (is_array($resultado)) {
				$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
				$output["resultado"] = $resultado;
				$numerofilasporpagina = $this->sVehiculo->ObtenerNumeroFilasPorPagina();
				$TotalFilas = $this->sVehiculo->ObtenerNumeroTotalVehiculos($data);
				$output["data"] = $data;
				$output["Filtros"] = array(
					"textofiltro" => "",
					"numerofilasporpagina" => $numerofilasporpagina	,
					"totalfilas" => $TotalFilas,
					"paginadefecto" => 2);

				// echo $this->json->json_response($output);
				$this->db->trans_commit();
				echo $this->json->json_response($output);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}
	public function ActualizarVehiculo()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sVehiculo->ActualizarVehiculoDesdeVehiculo($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}

	public function BorrarVehiculo()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sVehiculo->BorrarVehiculo($data);
			if (is_array($resultado)) {
				$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
				$numerofilasporpagina = $this->sCliente->ObtenerNumeroFilasPorPagina();
				$TotalFilas = $this->sCliente->ObtenerNumeroTotalClientes($data);
				$output["msg"] = $resultado;

				$output["Filtros"] = array(
					"textofiltro" => "",
					"numerofilasporpagina" => $numerofilasporpagina	,
					"totalfilas" => $TotalFilas,
					"paginadefecto" => 2);
				$this->db->trans_commit();
				echo $this->json->json_response($output);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}
}
