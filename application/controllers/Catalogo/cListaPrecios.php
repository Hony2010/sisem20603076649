<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cListaPrecios extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->service("Catalogo/sListaPrecioMercaderia");
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
	}

	public function Index() {
		
		$ListaPrecio =  $this->sListaPrecioMercaderia->Inicializar();
		$NuevoListaPrecio =  $this->sListaPrecioMercaderia->ListaPrecioMercaderia;
		$data = array(
			"data" => array(
				"ListaPrecio" => $ListaPrecio,
				"NuevoListaPrecio" => $NuevoListaPrecio
				)
		);

   	 	$view_data['data'] = $data;
		$view_form['view_form_listaprecio'] = $this->load->View('Catalogo/ListaPrecio/view_form_listaprecio','',true);
		$view_form['view_panel_header_listaprecio'] = $this->load->View('Catalogo/ListaPrecio/view_panel_header_listaprecio','',true);
		$views['view_panel_listaprecio'] = $this->load->View('Catalogo/ListaPrecio/view_panel_listaprecio',$view_form,true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/ListaPrecio/view_footer_listaprecio',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/ListaPrecio/view_mainpanel_content_listaprecio',$views,true);

		$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarMercaderias()
	{
		$input = json_decode($this->input->get("Data"), true);
		$consulta = $this->sListaPrecioMercaderia->ConsultarListasPrecioMercaderia($input);

		echo $this->json->json_response($consulta);
	}

	public function ConsultarMercaderiasParaPrecioBase()
	{
		$input = json_decode($this->input->get("Data"), true);
		$consulta = $this->sListaPrecioMercaderia->ConsultarMercaderiasParaPrecio($input);

		echo $this->json->json_response($consulta);
	}

	public function GuardarListaPrecio()
	{
		try {
			$this->db->trans_begin();

			// $data = $this->input->post("Data");
			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			if($data["PrecioBase"] == 0)
			{
				$resultado = $this->sListaPrecioMercaderia->ActualizarListaPreciosMercaderias($data);
			}
			else{
				$resultado = $this->sListaPrecioMercaderia->ActualizarMercaderiasParaPrecioBase($data);
			}
			
			if(is_array($resultado)) {
				$this->db->trans_commit();			
				if($data["PrecioBase"] == 0) {			
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true);
				}
				else
				{
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true, true);
				}

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

	public function ObtenerCostosPromedios()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$filtro = $this->input->post("Filtro");
			
			$resultado = $this->sListaPrecioMercaderia->ObtenerCostosPromedios($data, $filtro);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				// $jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true);

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

	public function ObtenerPreciosPromedios()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$filtro = $this->input->post("Filtro");
			
			$resultado = $this->sListaPrecioMercaderia->ObtenerPreciosPromedios($data, $filtro);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				// $jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true);

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

	public function ConsultarPreciosMercaderia()
	{
		$input = json_decode($this->input->get("Data"), true);
		$consulta = $this->sListaPrecioMercaderia->ConsultarPreciosMercaderia($input);

		echo $this->json->json_response($consulta);
	}

}
