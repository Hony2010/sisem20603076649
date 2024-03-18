<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTransferenciaAlmacen extends CI_Controller  {

	public function __construct() {
		parent::__construct();		
		$this->load->service("Inventario/sTransferenciaAlmacen");
		$this->load->service("Inventario/sDetalleTransferenciaAlmacen");		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');		
		$this->load->service("Configuracion/General/sSede");
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
	}

	public function Index() {
		
		$TransferenciaAlmacen =$this->sTransferenciaAlmacen->Cargar();

		$data = array(
			"data" => array(
				'TransferenciaAlmacen' => $TransferenciaAlmacen,
				'NuevoTransferenciaAlmacen' => $TransferenciaAlmacen				
				)
		);

    	$view_data['data'] = $data;
		$view_form['view_form_transferenciaalmacen'] = $this->load->View('Inventario/TransferenciaAlmacen/view_form_transferenciaalmacen','',true);
		$view_form['view_panel_header_transferenciaalmacen'] = $this->load->View('Inventario/TransferenciaAlmacen/view_panel_header_transferenciaalmacen','',true);
		$views['view_panel_transferenciaalmacen'] = $this->load->View('Inventario/TransferenciaAlmacen/view_panel_transferenciaalmacen',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Inventario/TransferenciaAlmacen/view_footer_transferenciaalmacen',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Inventario/TransferenciaAlmacen/view_mainpanel_content_transferenciaalmacen',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function InsertarTransferenciaAlmacen() {
		try {
			$this->db->trans_begin();			
			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			$resultado = $this->sTransferenciaAlmacen->InsertarTransferenciaAlmacen($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();				
				$resultado2 = $this->restapimercaderia->ActualizarProductosJSON($resultado["IdProductos"]);
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

	public function ActualizarTransferenciaAlmacen() {
		try {
			$this->db->trans_begin();

			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			
			$resultado = $this->sTransferenciaAlmacen->ActualizarTransferenciaAlmacen($data);

			if(is_array($resultado)) {				
				$this->db->trans_commit();
				$resultado2 = $this->restapimercaderia->ActualizarProductosJSON($resultado["IdProductos"]);
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

	public function AnularTransferenciaAlmacen() {
		try {
			$this->db->trans_begin();

			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			
			$resultado =  $this->sTransferenciaAlmacen->AnularTransferenciaAlmacen($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				$resultado2 = $this->restapimercaderia->ActualizarProductosJSON($resultado["IdProductos"]);
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

	public function EliminarTransferenciaAlmacen() {
		try {
			$this->db->trans_begin();

			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			
			$resultado =  $this->sTransferenciaAlmacen->BorrarTransferenciaAlmacen($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				$resultado2 = $this->restapimercaderia->ActualizarProductosJSON($resultado["IdProductos"]);								
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

	public function ConsultarDetallesTransferenciaAlmacen() {
		$data = $this->input->get("Data");
		$resultado = $this->sDetalleTransferenciaAlmacen->ConsultarDetallesTransferenciaAlmacen($data);
		echo $this->json->json_response($resultado);
	}
}
    