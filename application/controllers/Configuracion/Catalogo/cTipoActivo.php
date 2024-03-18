<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoActivo extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sTipoActivo");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoActivo =  $this->sTipoActivo->TipoActivo;
		$TiposActivo = $this->sTipoActivo->ListarTiposActivo();

		$data = array("data" =>
					array(
						'TiposActivo' => $TiposActivo,
						'TipoActivo' => $TipoActivo
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Catalogo/TipoActivo/view_mainpanel_content_tipoactivo','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/TipoActivo/view_mainpanel_footer_tipoactivo',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposActivo()
	{
		$resultado = $this->sTipoActivo->ListarTiposActivo();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoActivo()
	{
		$data = $this->input->post("Data");
		$data["CuentaContable"] = null;
		$resultado = $this->sTipoActivo->InsertarTipoActivo($data);
		$data["IdTipoActivo"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarTipoActivo()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoActivo->ActualizarTipoActivo($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoActivo()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoActivo->BorrarTipoActivo($data);
		echo $this->json->json_response($resultado);
	}

}
