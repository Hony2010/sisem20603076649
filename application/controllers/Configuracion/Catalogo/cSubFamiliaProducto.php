<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cSubFamiliaProducto extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index() {
		$data = $this->input->get("Data");
		$id = $data["IdSubFamiliaProducto"];
		$SubFamiliasProducto = $this->sSubFamiliaProducto->ConsultarFamiliasProductoPorIdFamiliaProducto($id);

		$data = array("data" =>
					array(
						'SubFamiliasProducto' => $SubFamiliasProducto
					)
		);

		$view_data['data'] = $data;
		$view['view_navigationbar'] = $this->load->view('view_mainpanel_navigationbar','',true);
		$view['view_header'] = $this->load->view('view_mainpanel_header','',true);
		$view['view_menu'] = $this->load->view('view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('view_subfamiliaproducto','',true);
		$view['view_demo_theme'] = $this->load->view('view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('view_mainpanel_footer_subfamiliaproducto',$view_data,true);
		$view['view_footer'] = $this->load->view('view_mainpanel_footer',$view_ext,true);

		$this->load->View('master_view_mainpanel',$view);
	}

	public function InsertarSubFamiliaProducto() {
		$data = $this->input->post("Data");
		$data["IdSubFamiliaProducto"] = null;
		$resultado = $this->sSubFamiliaProducto->InsertarSubFamiliaProducto($data);
		$data["IdSubFamiliaProducto"] = $resultado["IdSubFamiliaProducto"];

		echo $this->json->json_response($data);
	}

	public function ActualizarSubFamiliaProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sSubFamiliaProducto->ActualizarSubFamiliaProducto($data);
		echo $this->json->json_response($resultado);
  }

	public function BorrarSubFamiliaProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sSubFamiliaProducto->BorrarSubFamiliaProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSubFamiliaProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sSubFamiliaProducto->ListarSubFamiliasProducto($data);

		echo $this->json->json_response($resultado);
	}

}
