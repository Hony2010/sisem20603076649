<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cListaRaleo extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->service("Catalogo/sListaRaleoMercaderia");
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
	}

	public function Index()
	{
		$ListaRaleo =  $this->sListaRaleoMercaderia->Inicializar();
		$NuevoListaRaleo =  $this->sListaRaleoMercaderia->ListaRaleoMercaderia;
		$data = array(
			"data" => array(
				"ListaRaleo" => $ListaRaleo,
				"NuevoListaRaleo" => $NuevoListaRaleo
				)
		);

    	$view_data['data'] = $data;
		$view_form['view_form_listaraleo'] = $this->load->View('Catalogo/ListaRaleo/view_form_listaraleo','',true);
		$views['view_panel_listaraleo'] = $this->load->View('Catalogo/ListaRaleo/view_panel_listaraleo',$view_form,true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/ListaRaleo/view_footer_listaraleo',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/ListaRaleo/view_mainpanel_content_listaraleo',$views,true);

		$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarMercaderias()
	{
		$input = json_decode($this->input->get("Data"), true);
		$consulta = $this->sListaRaleoMercaderia->ConsultarListasRaleoMercaderia($input);

		echo $this->json->json_response($consulta);
	}

	public function GuardarListaRaleo()
	{
		try {
			$this->db->trans_begin();

			// $data = $this->input->post("Data");
			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			$resultado = $this->sListaRaleoMercaderia->ActualizarListaRaleoMercaderias($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true);

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
