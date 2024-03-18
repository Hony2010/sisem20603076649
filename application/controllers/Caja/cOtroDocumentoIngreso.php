<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cOtroDocumentoIngreso extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sDocumentoIngreso");
	}

	public function Index() {
		
		$OtroDocumentoIngreso =  $this->sDocumentoIngreso->Cargar();
		$data = array(
			"data" => array(
				'OtroDocumentoIngreso' => $OtroDocumentoIngreso,
			)
		);

  		$view_data['data'] = $data;

  		$view_form['view_form_otrodocumentoingreso'] = $this->load->View('Caja/OtroDocumentoIngreso/view_form_otrodocumentoingreso','',true);
		$views['view_panel_otrodocumentoingreso'] = $this->load->View('Caja/OtroDocumentoIngreso/view_panel_otrodocumentoingreso',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Caja/OtroDocumentoIngreso/view_footer_otrodocumentoingreso',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Caja/OtroDocumentoIngreso/view_content_otrodocumentoingreso',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function InsertarOtroDocumentoIngreso()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sOtroDocumentoIngreso->AgregarOtroDocumentoIngreso($data);//$this->sOtroDocumentoIngreso->InsertarOtroDocumentoIngreso($data);
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

	public function ActualizarOtroDocumentoIngreso()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sOtroDocumentoIngreso->ActualizarOtroDocumentoIngreso($data);
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

	public function BorrarOtroDocumentoIngreso()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sOtroDocumentoIngreso->BorrarOtroDocumentoIngreso($data);
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
