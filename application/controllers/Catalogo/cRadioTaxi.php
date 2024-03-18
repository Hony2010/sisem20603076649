<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cRadioTaxi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sRadioTaxi");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}
	public function Index()
	{
		$RadioTaxi =  $this->sRadioTaxi->Inicializar();
		$RadiosTaxi =  $this->sRadioTaxi->ListarRadioTaxis();

		$data = array(
			"data" =>
			array(
				'RadioTaxi' => $RadioTaxi,
				'RadiosTaxi' => $RadiosTaxi
			)
		);

		$view_data['data'] = $data;

		$view_footer_extension['view_footer_extension'] = $this->load->View('Catalogo/RadioTaxi/view_mainpanel_footer_radiotaxi', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Catalogo/RadioTaxi/view_mainpanel_content_radiotaxi', '', true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer_extension, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ListarRadioTaxis()
	{
		$resultado = $this->sRadioTaxi->ListarRadioTaxis();
		echo $this->json->json_response($resultado);
	}

	public function InsertarRadioTaxi()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sRadioTaxi->InsertarRadioTaxi($data);
			
			if (is_array($resultado)) {
				$output["resultado"] = $resultado;
				$this->db->trans_commit();
				echo $this->json->json_response($output);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}
	public function ActualizarRadioTaxi()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sRadioTaxi->ActualizarRadioTaxi($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}

	public function BorrarRadioTaxi()
	{
		try {
			$this->db->trans_begin();
			$data = $this->input->post("Data");
			$resultado = $this->sRadioTaxi->BorrarRadioTaxi($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}
}
