<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMedioPago extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sMedioPago");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$MedioPago =  $this->sMedioPago->MedioPago;
		$MediosPago = $this->sMedioPago->ListarMediosPago();

		$data = array("data" =>
					array(
						'MediosPago' => $MediosPago,
						'MedioPago' => $MedioPago
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/MedioPago/view_mainpanel_content_mediopago','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/MedioPago/view_mainpanel_footer_mediopago',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarMediosPago()
	{
		$resultado = $this->sMedioPago->ListarMediosPago();

		echo $this->json->json_response($resultado);
	}

	public function InsertarMedioPago()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMedioPago->InsertarMedioPago($data);
		$data["IdMedioPago"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarMedioPago()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMedioPago->ActualizarMedioPago($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarMedioPago()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMedioPago->BorrarMedioPago($data);
		echo $this->json->json_response($resultado);
	}

}
