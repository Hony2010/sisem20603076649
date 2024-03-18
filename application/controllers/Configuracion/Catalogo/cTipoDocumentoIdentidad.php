<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoDocumentoIdentidad extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoDocumentoIdentidad =  $this->sTipoDocumentoIdentidad->TipoDocumentoIdentidad;
		$TiposDocumentoIdentidad = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();

		$data = array("data" =>
					array(
						'TiposDocumentoIdentidad' => $TiposDocumentoIdentidad,
						'TipoDocumentoIdentidad' => $TipoDocumentoIdentidad
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Catalogo/TipoDocumentoIdentidad/view_mainpanel_content_tipodocumentoidentidad','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/TipoDocumentoIdentidad/view_mainpanel_footer_tipodocumentoidentidad',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposDocumentoIdentidad()
	{
		$resultado = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoDocumentoIdentidad()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoDocumentoIdentidad->InsertarTipoDocumentoIdentidad($data);
		$data["IdTipoDocumentoIdentidad"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarTipoDocumentoIdentidad()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoDocumentoIdentidad->ActualizarTipoDocumentoIdentidad($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoDocumentoIdentidad()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoDocumentoIdentidad->BorrarTipoDocumentoIdentidad($data);
		echo $this->json->json_response($resultado);
	}

}
