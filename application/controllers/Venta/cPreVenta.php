<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cPreVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('emailer');
		$this->load->service("Venta/sPreVenta");
	}

	public function Index()
	{

	}

	public function ConsultarComandasPorMesa() {
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->ConsultarComandasPorMesa($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ConsultarPreVentasPorMesa() {
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->ConsultarPreVentasPorMesa($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function InsertarPreVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->InsertarPreVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
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

	public function ActualizarPreVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->ActualizarPreVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
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

	public function BorrarPreVenta() {
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->EliminarPreVenta($data);
			if (!is_string($resultado)) {
				$this->db->trans_commit();
				$resultado = $data;
				echo $this->json->json_response($resultado);
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

	public function CancelarPreCuenta() {
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->CancelarPreCuenta($data);
			if (!is_string($resultado)) {
				$this->db->trans_commit();
				$resultado = $data;
				echo $this->json->json_response($resultado);
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

	public function InsertarVentaDesdePreVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPreVenta->InsertarVentaDesdePreVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
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

	public function AnularVentaDesdePreVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPreVenta->AnularVentaDesdePreVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
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

	public function BorrarVentaDesdePreVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPreVenta->BorrarVentaDesdePreVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
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

	public function ImprimirVentaDesdePreVenta() {
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPreVenta->ImprimirPreVenta($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ImprimirComanda() {
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPreVenta->ImprimirComanda($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ImprimirItemAnuladoComanda() {
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPreVenta->ImprimirItemAnuladoComanda($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	//CONSULTA
	public function ConsultarUltimaComandaPorNumeroMesa() {
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->ConsultarUltimaComandaPorNumeroMesa($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ConsultarDetallesComprobantePreVentaConsolidado() {
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->ConsultarDetallesComprobantePreVentaConsolidado($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ConsultarDetallesComprobanteVenta() {
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sPreVenta->ConsultarDetallesComprobanteVenta($data);
			if (is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}
}
