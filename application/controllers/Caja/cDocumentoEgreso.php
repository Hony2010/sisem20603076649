<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDocumentoEgreso extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sDocumentoEgreso");
	}

	public function InsertarDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sDocumentoEgreso->InsertarDocumentoEgresoDesdeEgreso($data);//$this->sDocumentoEgreso->InsertarDocumentoEgreso($data);
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

	public function ActualizarDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sDocumentoEgreso->ActualizarDocumentoEgresoDesdeEgreso($data);
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

	public function BorrarDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sDocumentoEgreso->BorrarDocumentoEgresoDesdeEgreso($data);
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

	public function AnularDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sDocumentoEgreso->AnularDocumentoEgresoDesdeEgreso($data);
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

}
