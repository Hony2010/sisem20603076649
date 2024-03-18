<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cAperturaCaja extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sAperturaCaja");
	}

	public function Index()
	{
		$AperturaCaja =  $this->sAperturaCaja->Cargar();
		$data = array(
			"data" => array(
				'AperturaCaja' => $AperturaCaja,
			)
		);

  		$view_data['data'] = $data;

  		$view_form['view_form_aperturacaja'] = $this->load->View('Caja/AperturaCaja/view_form_aperturacaja','',true);
		$views['view_panel_aperturacaja'] = $this->load->View('Caja/AperturaCaja/view_panel_aperturacaja',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Caja/AperturaCaja/view_footer_aperturacaja',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Caja/AperturaCaja/view_content_aperturacaja',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarAperturaCaja()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sAperturaCaja->ConsultarAperturaCaja($data);
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

	public function InsertarAperturaCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sAperturaCaja->AgregarAperturaCaja($data);//$this->sAperturaCaja->InsertarAperturaCaja($data);
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

	public function ActualizarAperturaCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sAperturaCaja->ActualizarAperturaCaja($data);
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

	public function BorrarAperturaCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sAperturaCaja->BorrarAperturaCaja($data);
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
