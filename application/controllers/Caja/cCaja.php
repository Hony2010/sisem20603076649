<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCaja extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sCaja");
		$this->load->service('Configuracion/General/sMoneda');
	}

	public function Index()
	{
		$Caja =  $this->sCaja->Cargar();
		$Cajas = $this->sCaja->ListarCajas();

		$monedas = $this->sMoneda->ListarMonedas();
		
		$data = array(
			"data" => array(
					'Cajas' => $Cajas,
					'Caja' => $Caja,
					'Monedas' => $monedas
				)
		);

		$view_data['data'] = $data;
		
		// $views['view_panel_caja'] = $this->load->View('Caja/Caja/view_panel_caja','',true);
		$view_footer['view_footer_extension'] = $this->load->View('Caja/Caja/view_footer_caja',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Caja/Caja/view_mainpanel_content_caja','',true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarCajas()
	{
		$resultado = $this->sCaja->ListarCajas();

		echo $this->json->json_response($resultado);
	}

	public function InsertarCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCaja->InsertarCaja($data);
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

	public function ActualizarCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCaja->ActualizarCaja($data);
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

	public function BorrarCaja()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sCaja->BorrarCaja($data);
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
