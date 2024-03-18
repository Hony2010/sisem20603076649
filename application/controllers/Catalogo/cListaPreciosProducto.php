<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cListaPreciosProducto extends CI_Controller
{

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

	public function Index()
	{
		$ListaPrecio =  $this->sListaPrecioMercaderia->Inicializar();
		$NuevoListaPrecio =  $this->sListaPrecioMercaderia->ListaPrecioMercaderia;
		$data = array(
			"data" => array(
				"ListaPrecioProducto" => $ListaPrecio,
				"NuevoListaPrecio" => $NuevoListaPrecio
			)
		);

		$view_data['data'] = $data;
		$view_form['view_form_listaprecioproducto'] = $this->load->View('Catalogo/ListaPrecioProducto/view_form_listaprecioproducto', '', true);
		$views['view_panel_listaprecioproducto'] = $this->load->View('Catalogo/ListaPrecioProducto/view_panel_listaprecioproducto', $view_form, true);

		$view_footer_ext['view_footer_extension'] = $this->load->View('Catalogo/ListaPrecioProducto/view_footer_listaprecioproducto', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Catalogo/ListaPrecioProducto/view_mainpanel_content_listaprecioproducto', $views, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer_ext, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function GuardarListaPreciosProducto()
	{
		try {
			$this->db->trans_begin();
			
			$data_post = $_POST["Data"];
			$data = json_decode($data_post , true);		
			$data = $data["Data"];			
			$resultado = $this->sListaPrecioMercaderia->GuardarListaPreciosProducto($data);

			if (is_array($resultado)) {
				$this->db->trans_commit();
				$this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true);

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


	public function ConsultarPreciosMercaderia()
	{
		$input = json_decode($this->input->post("Data"), true);

		$consulta = $this->sListaPrecioMercaderia->ConsultarPreciosMercaderia($input);
		echo $this->json->json_response($consulta);
	}
}
