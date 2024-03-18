<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cProforma extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('emailer');
		$this->load->library('sesionusuario');
		$this->load->library('RestApi/Venta/RestApiComprobanteVenta');
		$this->load->service('Venta/sComprobanteVenta');
		$this->load->service('Venta/sProforma');
	}

	public function Index()
	{
		
	}

	public function InsertarProforma()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sProforma->InsertarProforma($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				$this->restapicomprobanteventa->InsertarJSONDesdeProforma($resultado);
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

	public function ActualizarProforma()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sProforma->ActualizarProforma($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				$this->restapicomprobanteventa->ActualizarJSONDesdeProforma($resultado);
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

	public function AnularProforma()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sProforma->AnularProforma($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				$this->restapicomprobanteventa->BorrarJSONDesdeProforma($resultado);
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

	public function BorrarProforma()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sProforma->BorrarProforma($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				$input = $data["Filtros"];
				$input["FechaInicio"] = convertToDate($data["Filtros"]["FechaInicio"]);
				$input["FechaFin"] = convertToDate($data["Filtros"]["FechaFin"]);
				$input["textofiltro"] = ($input["textofiltro"] != "") ? $input["textofiltro"] : '%';

				$data["Filtros"]["totalfilas"] = $this->sComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($input);
				$resultado = $data;
				$this->restapicomprobanteventa->BorrarJSONDesdeProforma($resultado);
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
}
