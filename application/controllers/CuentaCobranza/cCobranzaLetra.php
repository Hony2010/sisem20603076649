<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCobranzaLetra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("CuentaCobranza/sCobranzaLetra");
	}

	public function Index()
	{
		$CobranzaLetra =  $this->sCobranzaLetra->Cargar();
		$data = array(
			"data" => array(
				'CobranzaLetra' => $CobranzaLetra
			)
		);

		$view_data['data'] = $data;

		$view_form['view_form_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaLetra/view_form_cobranzacliente','',true);
		$view_form['view_modal_comprobantes_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaLetra/view_modal_comprobantes_cobranzacliente','',true);
		$views['view_panel_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaLetra/view_panel_cobranzacliente',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/CobranzaLetra/view_footer_cobranzacliente',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/CobranzaLetra/view_content_cobranzacliente',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarCobranzaLetra()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaLetra->ConsultarCobranzaLetra($data);
			if(is_array($resultado)) {
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

	//CONSULTA COBRANZA
	// public function ConsultarPendientesCobranzaLetraPorIdCliente()
	// {
	// 	$data = json_decode($this->input->post("Data"), true);
	// 	$resultado = $this->sCobranzaLetra->ConsultarPendientesCobranzaLetraPorIdCliente($data);
	// 	echo $this->json->json_response($resultado);
	// }

	//CONSULTA COBRANZA
	public function ConsultarPendientesLetraCobrarParaCobranza()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCobranzaLetra->ConsultarPendientesLetraCobrarParaCobranza($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarPendientesCobranzaLetraPorIdClienteYFiltro()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCobranzaLetra->ConsultarPendientesCobranzaLetraPorIdClienteYFiltro($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarDetallesCobranzaPorCobranza()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCobranzaLetra->ConsultarDetallesCobranzaPorCobranza($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarCobranzaLetra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sCobranzaLetra->InsertarCobranzaLetra($data);//$this->sCobranzaLetra->InsertarCobranzaLetra($data);
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

	public function ActualizarCobranzaLetra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaLetra->ActualizarCobranzaLetra($data);
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

	public function BorrarCobranzaLetra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaLetra->BorrarCobranzaLetra($data);
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

	public function AnularCobranzaLetra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaLetra->AnularCobranzaLetra($data);
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
