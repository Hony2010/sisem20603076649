<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCompraGasto extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		// $this->load->service("CompraGasto/sCompraGasto");
		$this->load->service("Compra/sCompraGasto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
	}

	public function Index()
	{

	}

	public function InsertarCompraGasto()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sCompraGasto->InsertarCompraGasto($data);

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

	public function ActualizarCompraGasto()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sCompraGasto->ActualizarCompraGasto($data);

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
