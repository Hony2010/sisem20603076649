<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCierreCaja extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sCierreCaja");
	}

	public function Index()
	{
		$CierreCaja =  $this->sCierreCaja->Cargar();

		$data = array(
			"data" => array(
				'CierreCaja' => $CierreCaja,
			)
		);

		$view_data['data'] = $data;

		$view_form['view_form_cierrecaja'] = $this->load->View('Caja/CierreCaja/view_form_cierrecaja','',true);
		$views['view_panel_cierrecaja'] = $this->load->View('Caja/CierreCaja/view_panel_cierrecaja',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Caja/CierreCaja/view_footer_cierrecaja',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Caja/CierreCaja/view_content_cierrecaja',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function InsertarCierreCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCierreCaja->AgregarCierreCaja($data);//$this->sCierreCaja->InsertarCierreCaja($data);
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

	public function ActualizarCierreCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCierreCaja->ActualizarCierreCaja($data);
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

	public function BorrarCierreCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCierreCaja->BorrarCierreCaja($data);
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

	public function ObtenerUltimaAperturaPorUsuarioYCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCierreCaja->ObtenerUltimaAperturaPorUsuarioYCaja($data);
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
