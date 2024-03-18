<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cFormaPago extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sFormaPago");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$FormaPago =  $this->sFormaPago->FormaPago;
		$FormasPago = $this->sFormaPago->ListarFormasPago();

		$data = array("data" =>
					array(
						'FormasPago' => $FormasPago,
						'FormaPago' => $FormaPago

					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/FormaPago/view_mainpanel_content_formapago','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/FormaPago/view_mainpanel_footer_formapago',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarFormasPago()
	{
		$resultado = $this->sFormaPago->ListarFormasPago();

		echo $this->json->json_response($resultado);
	}

	public function InsertarFormaPago()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFormaPago->InsertarFormaPago($data);
		$data["IdFormaPago"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarFormaPago()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFormaPago->ActualizarFormaPago($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarFormaPago()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFormaPago->BorrarFormaPago($data);
		echo $this->json->json_response($resultado);
	}

}
