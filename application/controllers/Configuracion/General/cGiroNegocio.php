<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGiroNegocio extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sGiroNegocio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$GiroNegocio =  $this->sGiroNegocio->GiroNegocio;
		$GirosNegocio = $this->sGiroNegocio->ListarGirosNegocio();

		$data = array("data" =>
					array(
						'GirosNegocio' => $GirosNegocio,
						'GiroNegocio' => $GiroNegocio

					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/GiroNegocio/view_mainpanel_content_gironegocio','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/GiroNegocio/view_mainpanel_footer_gironegocio',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarGirosNegocio()
	{
		$resultado = $this->sGiroNegocio->ListarGirosNegocio();

		echo $this->json->json_response($resultado);
	}

	public function InsertarGiroNegocio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGiroNegocio->InsertarGiroNegocio($data);
		$data["IdGiroNegocio"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarGiroNegocio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGiroNegocio->ActualizarGiroNegocio($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarGiroNegocio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGiroNegocio->BorrarGiroNegocio($data);
		echo $this->json->json_response($resultado);
	}

}
