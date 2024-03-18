<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cModelo extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sModelo");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
    $data = $this->input->get("Data");
    $id = $data["IdModelo"];
		$Modelos = $this->sModelo->ConsultarMarcasPorIdMarca($id);

		$data = array("data" =>
					array(
						'Modelos' => $Modelos
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/vview_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/vview_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/vview_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/Catalogo/Marca/view_modelo','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/vview_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_footer_modelo',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/vview_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/vmaster_view_mainpanel',$view);
	}

	public function InsertarModelo()
	{
		$data = $this->input->post("Data");
		$data["IdModelo"] = null;
		$resultado = $this->sModelo->InsertarModelo($data);
		$data["IdModelo"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarModelo()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sModelo->ActualizarModelo($data);
		echo $this->json->json_response($resultado);
  }

	public function BorrarModelo()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sModelo->BorrarModelo($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarModelo()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sModelo->ListarModelos($data);

		echo $this->json->json_response($resultado);
	}

}
