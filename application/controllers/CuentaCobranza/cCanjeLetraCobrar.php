<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCanjeLetraCobrar extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->helper("date");
		$this->load->library('RestApi/Caja/RestApiPendienteCobranzaCliente');
		$this->load->service("Caja/sCanjeLetraCobrar");
		$this->load->service("Caja/sPendienteCobranzaCliente");
		$this->load->service("Caja/sPendienteLetraCobrar");
	}

	public function Index()
	{
		$CanjeLetraCobrar = $this->sCanjeLetraCobrar->Cargar();
		$data = array(
			"data" => array(
				'CanjeLetraCobrar' => $CanjeLetraCobrar,
				'BusquedaPendientesCobranzaCliente' => array()
			)
		);

		$view_data['data'] = $data;
		// echo $this->json->json_response($CanjeLetraCobrar);
		// exit;
		$view_form['view_form_canjeletracobrar'] = $this->load->View('CuentaCobranza/CanjeLetraCobrar/view_form_canjeletracobrar','',true);
		$views['view_panel_canjeletracobrar'] = $this->load->View('CuentaCobranza/CanjeLetraCobrar/view_panel_canjeletracobrar',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/CanjeLetraCobrar/view_footer_canjeletracobrar',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/CanjeLetraCobrar/view_content_canjeletracobrar',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarPendientesCobranzaClienteVentaParaLetra()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);
			$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
			$data["FechaFin"] = convertToDate($data["FechaFin"]);

			$resultado = $this->sPendienteCobranzaCliente->ConsultarPendientesCobranzaClienteVentaParaLetra($data);
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

	public function ConsultarPendientesLetraCobrarPorCanje()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPendienteLetraCobrar->ObtenerPendientesLetraCobrarPorIdCanjeLetraCobrar($data);
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

	public function ConsultarPendientesCobranzaClientePorCanje()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCanjeLetraCobrar->ObtenerPendientesCobranzaClientePorCanje($data);
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

	public function ConsultarCanjeLetraCobrar()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCanjeLetraCobrar->ConsultarCanjeLetra($data);
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
	// public function ConsultarPendientesCanjeLetraCobrarPorIdCliente()
	// {
	// 	$data = json_decode($this->input->post("Data"), true);
	// 	$resultado = $this->sCanjeLetraCobrar->ConsultarPendientesCanjeLetraCobrarPorIdCliente($data);
	// 	echo $this->json->json_response($resultado);
	// }

	//CONSULTA COBRANZA
	public function ConsultarPendientesCanjeLetraCobrarPorIdClienteYFiltro()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCanjeLetraCobrar->ConsultarPendientesCanjeLetraCobrarPorIdClienteYFiltro($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarDetallesCobranzaPorCobranza()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sCanjeLetraCobrar->ConsultarDetallesCobranzaPorCobranza($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarCanjeLetraCobrar()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCanjeLetraCobrar->InsertarCanjeLetraCobrar($data);//$this->sCanjeLetra->InsertarCanjeLetra($data);
			if(is_array($resultado)) {
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["ComprobantesVentaPendiente"]);
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

	public function ActualizarCanjeLetraCobrar()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCanjeLetraCobrar->ActualizarCanjeLetraCobrar($data);
			if(is_array($resultado)) {
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["ComprobantesVentaPendiente"]);
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

	public function BorrarCanjeLetraCobrar()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCanjeLetraCobrar->BorrarCanjeLetraCobrar($data);
			if(is_array($resultado)) {
				$this->restapipendientecobranzacliente->ActualizarJSONPendientesDesdeCobranza($resultado["ComprobantesVentaPendiente"]);
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

	// public function AnularCanjeLetraCobrar()
	// {
	// 	try {
	// 		$this->db->trans_begin();

	// 		$data = json_decode($this->input->post("Data"), true);

	// 		$resultado = $this->sCanjeLetraCobrar->AnularCanjeLetra($data);
	// 		if(is_array($resultado)) {
	// 			$this->db->trans_commit();
	// 			echo $this->json->json_response($resultado);
	// 		}
	// 		else {
	// 		 	$this->db->trans_rollback();
	// 			echo $this->json->json_response_error($resultado);
	// 		}
	// 	}
	// 	catch (Exception $e) {
	// 		 $this->db->trans_rollback();
	// 		 echo $this->json->json_response_error($e);
	// 	}
	// }
}
