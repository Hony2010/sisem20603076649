<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cOtroDocumentoEgreso extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sDocumentoEgreso");
	}

	public function Index()
	{
		$OtroDocumentoEgreso =  $this->sDocumentoEgreso->Cargar();
		$data = array(
			"data" => array(
				'OtroDocumentoEgreso' => $OtroDocumentoEgreso,
			)
		);

  	$view_data['data'] = $data;

  	$view_form['view_form_otrodocumentoegreso'] = $this->load->View('Caja/OtroDocumentoEgreso/view_form_otrodocumentoegreso','',true);
		$views['view_panel_otrodocumentoegreso'] = $this->load->View('Caja/OtroDocumentoEgreso/view_panel_otrodocumentoegreso',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Caja/OtroDocumentoEgreso/view_footer_otrodocumentoegreso',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Caja/OtroDocumentoEgreso/view_content_otrodocumentoegreso',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function InsertarOtroDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sOtroDocumentoEgreso->AgregarOtroDocumentoEgreso($data);//$this->sOtroDocumentoEgreso->InsertarOtroDocumentoEgreso($data);
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

	public function ActualizarOtroDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sOtroDocumentoEgreso->ActualizarOtroDocumentoEgreso($data);
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

	public function BorrarOtroDocumentoEgreso()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sOtroDocumentoEgreso->BorrarOtroDocumentoEgreso($data);
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
