<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMoneda extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$Moneda =  $this->sMoneda->Moneda;
		$Monedas = $this->sMoneda->ListarMonedas();

		$data = array("data" =>
					array(
						'Monedas' => $Monedas,
						'Moneda' => $Moneda
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/Moneda/view_mainpanel_content_moneda','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/Moneda/view_mainpanel_footer_moneda',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarMonedas()
	{
		$resultado = $this->sMoneda->ListarMonedas();

		echo $this->json->json_response($resultado);
	}

	public function InsertarMoneda()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMoneda->InsertarMoneda($data);
		$data["IdMoneda"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarMoneda()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMoneda->ActualizarMoneda($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarMoneda()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMoneda->BorrarMoneda($data);
		echo $this->json->json_response($resultado);
	}

}
