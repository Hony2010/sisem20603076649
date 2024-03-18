<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cPagoProveedor extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('RestApi/Caja/RestApiPendientePagoProveedor');
		$this->load->service("CuentaPago/sPagoProveedor");
	}

	public function Index()
	{
		$PagoProveedor =  $this->sPagoProveedor->Cargar();
		$data = array(
			"data" => array(
				'PagoProveedor' => $PagoProveedor,
			)
		);

		$view_data['data'] = $data;

		$view_form['view_form_pagoproveedor'] = $this->load->View('CuentaPago/PagoProveedor/view_form_pagoproveedor','',true);
		$view_form['view_modal_comprobantes_pagoproveedor'] = $this->load->View('CuentaPago/PagoProveedor/view_modal_comprobantes_pagoproveedor','',true);
		$views['view_panel_pagoproveedor'] = $this->load->View('CuentaPago/PagoProveedor/view_panel_pagoproveedor',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('CuentaPago/PagoProveedor/view_footer_pagoproveedor',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaPago/PagoProveedor/view_content_pagoproveedor',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarPagoProveedor()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPagoProveedor->ConsultarPagoProveedor($data);
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
	// public function ConsultarPendientesPagoProveedorPorIdProveedor()
	// {
	// 	$data = json_decode($this->input->post("Data"), true);
	// 	$resultado = $this->sPagoProveedor->ConsultarPendientesPagoProveedorPorIdProveedor($data);
	// 	echo $this->json->json_response($resultado);
	// }

	//CONSULTA COBRANZA
	public function ConsultarPendientesPagoProveedorPorIdProveedorYFiltro()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sPagoProveedor->ConsultarPendientesPagoProveedorPorIdProveedorYFiltro($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarDetallesCobranzaPorCobranza()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sPagoProveedor->ConsultarDetallesCobranzaPorCobranza($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarPagoProveedor()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPagoProveedor->InsertarPagoProveedor($data);//$this->sPagoProveedor->InsertarPagoProveedor($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($resultado["Movimientos"]);
				
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

	public function ActualizarPagoProveedor()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPagoProveedor->ActualizarPagoProveedor($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($resultado["Movimientos"]);
				
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

	public function BorrarPagoProveedor()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPagoProveedor->BorrarPagoProveedor($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($resultado["Movimientos"]);
				
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

	public function AnularPagoProveedor()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sPagoProveedor->AnularPagoProveedor($data);
			if(is_array($resultado)) {
				//ACTUALIZAMOS JSON
				$this->restapipendientepagoproveedor->ActualizarJSONPendientesDesdePago($resultado["Movimientos"]);
				
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
