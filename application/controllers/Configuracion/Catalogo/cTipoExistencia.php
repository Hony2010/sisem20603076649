<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoExistencia extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sTipoExistencia");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoExistencia =  $this->sTipoExistencia->TipoExistencia;
		$TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();

		$data = array("data" =>
					array(
						'TiposExistencia' => $TiposExistencia,
						'TipoExistencia' => $TipoExistencia
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Catalogo/TipoExistencia/view_mainpanel_content_tipoexistencia','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/TipoExistencia/view_mainpanel_footer_tipoexistencia',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposExistencia()
	{
		$resultado = $this->sTipoExistencia->ListarTiposExistencia();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoExistencia()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoExistencia->InsertarTipoExistencia($data);
		$data["IdTipoExistencia"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarTipoExistencia()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoExistencia->ActualizarTipoExistencia($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoExistencia()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoExistencia->BorrarTipoExistencia($data);
		echo $this->json->json_response($resultado);
	}

}
