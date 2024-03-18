<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTransferenciaCaja extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sTransferenciaCaja");
	}

	public function Index()
	{
		$TransferenciaCaja =  $this->sTransferenciaCaja->Cargar();
		$data = array(
			"data" => array(
				'TransferenciaCaja' => $TransferenciaCaja,
			)
		);

  		$view_data['data'] = $data;

  		$view_form['view_form_transferenciacaja'] = $this->load->View('Caja/TransferenciaCaja/view_form_transferenciacaja','',true);
		$views['view_panel_transferenciacaja'] = $this->load->View('Caja/TransferenciaCaja/view_panel_transferenciacaja',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Caja/TransferenciaCaja/view_footer_transferenciacaja',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Caja/TransferenciaCaja/view_content_transferenciacaja',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function InsertarTransferenciaCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sTransferenciaCaja->AgregarTransferenciaCaja($data);//$this->sTransferenciaCaja->InsertarTransferenciaCaja($data);
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

	public function ActualizarTransferenciaCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sTransferenciaCaja->ActualizarTransferenciaCaja($data);
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

	public function BorrarTransferenciaCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sTransferenciaCaja->BorrarTransferenciaCaja($data);
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

	public function BuscarSaldoCajaTurnoOrigen()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sTransferenciaCaja->BuscarSaldoCajaTurnoOrigen($data);
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

	public function BuscarSaldoCajaTurnoDestino()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sTransferenciaCaja->BuscarSaldoCajaTurnoDestino($data);
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

}
