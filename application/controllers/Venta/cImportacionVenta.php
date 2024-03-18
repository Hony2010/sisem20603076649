<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cImportacionVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("Venta/sVenta");
		$this->load->service("Venta/sImportacionVenta");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$BoletaVenta = array();
		$data = array(
			"data" => array(
					'ComprobantesVenta' => array(),
					'ClientesImportacion' => array(),
					'ProductosImportacion' => array(),
					'ClientesBase' => array(),
					'ProductosBase' => array()
				)
		);

		$view_data['data'] = $data;
		
		$view_form['view_panel_header_importacionventa'] = $this->load->View('Venta/ImportacionVenta/view_panel_header_importacionventa','',true);
		$view_form['view_form_importacionventa'] = $this->load->View('Venta/ImportacionVenta/view_form_importacionventa','',true);
		$views['view_panel_importacionventa'] = $this->load->View('Venta/ImportacionVenta/view_panel_importacionventa',$view_form,true);

		$view['view_footer_extension'] = $this->load->View('Venta/ImportacionVenta/view_footer_importacionventa',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/ImportacionVenta/view_content_importacionventa',$views,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ValidarListadoCliente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sImportacionVenta->ValidarListadoCliente($data);
			if(is_array($resultado)) {
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

	public function ValidarListadoProducto()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sImportacionVenta->ValidarListadoProducto($data);
			if(is_array($resultado)) {
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


	public function InsertarComprobanteVentaJSON()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sImportacionVenta->ValidarComprobanteVentaJSON($data);
			if(is_array($resultado)) {
				$response = $this->sImportacionVenta->InsertarVenta($resultado);
				if(is_array($response)) {
					$response["Estado"] = "CORRECTO";
					$response["CodigoEstado"] = "1";
					$response = array_merge($data, $response);
					echo $this->json->json_response($response);
				}
				else {
					echo $this->json->json_response_error($response);
				}
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
