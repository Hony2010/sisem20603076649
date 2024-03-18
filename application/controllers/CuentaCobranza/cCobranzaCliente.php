<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCobranzaCliente extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('RestApi/Caja/RestApiPendienteCobranzaCliente');
		$this->load->service("CuentaCobranza/sCobranzaCliente");
	}

	public function Index()
	{
		$CobranzaCliente =  $this->sCobranzaCliente->Cargar();
		$data = array(
			"data" => array(
				'CobranzaCliente' => $CobranzaCliente,
			)
		);

		$view_data['data'] = $data;

		$view_form['view_form_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_form_cobranzacliente','',true);
		$view_form['view_modal_comprobantes_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_modal_comprobantes_cobranzacliente','',true);
		$views['view_panel_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_panel_cobranzacliente',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_footer_cobranzacliente',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/CobranzaCliente/view_content_cobranzacliente',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarCobranzaCliente()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaCliente->ConsultarCobranzaCliente($data);
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
	// public function ConsultarPendientesCobranzaClientePorIdCliente()
	// {
	// 	$data = json_decode($this->input->post("Data"), true);
	// 	$resultado = $this->sCobranzaCliente->ConsultarPendientesCobranzaClientePorIdCliente($data);
	// 	echo $this->json->json_response($resultado);
	// }

	//CONSULTA COBRANZA
	public function ConsultarPendientesCobranzaClientePorIdClienteYFiltro()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCobranzaCliente->ConsultarPendientesCobranzaClientePorIdClienteYFiltro($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarDetallesCobranzaPorCobranza()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCobranzaCliente->ConsultarDetallesCobranzaPorCobranza($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarCobranzaCliente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaCliente->InsertarCobranzaCliente($data);//$this->sCobranzaCliente->InsertarCobranzaCliente($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["Movimientos"]);
				
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

	public function ActualizarCobranzaCliente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaCliente->ActualizarCobranzaCliente($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["Movimientos"]);
				
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

	public function BorrarCobranzaCliente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaCliente->BorrarCobranzaCliente($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["Movimientos"]);
				
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

	public function AnularCobranzaCliente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCobranzaCliente->AnularCobranzaCliente($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["Movimientos"]);
				
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
