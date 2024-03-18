<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cSede extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sSede");
		$this->load->service("Configuracion/General/sTipoSede");
		$this->load->service("Configuracion/General/sAsignacionSede");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$Sede =  $this->sSede->Sede;
		$Sede['NombreTipoSede'] = "";
		$Sedes = $this->sSede->ListarSedes();
		$TiposSede = $this->sTipoSede->ListarTiposSede();

		$data = array("data" =>
					array(
						'Sedes' => $Sedes,
						'Sede' => $Sede,
						'TiposSede' => $TiposSede
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/Sede/view_mainpanel_content_sede','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/Sede/view_mainpanel_footer_sede',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarSedes()
	{
		$resultado = $this->sSede->ListarSedes();

		echo $this->json->json_response($resultado);
	}

	public function InsertarSede()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sSede->InsertarSede($data);

		if (is_numeric($resultado)) {
			$data["IdSede"] = $resultado;
			$TipoSede = $this->sAsignacionSede->InsertarActualizarAsignacionSede($data);
			$data['TiposSede'] = $TipoSede;
			$data['IdTipoDocumento'] = $resultado;
			echo $this->json->json_response($data);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function ActualizarSede()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sSede->ActualizarSede($data);

		if ($resultado == "") {
			$TipoSede = $this->sAsignacionSede->InsertarActualizarAsignacionSede($data);
			echo $this->json->json_response($TipoSede);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function BorrarSede()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sSede->BorrarSede($data);
		echo $this->json->json_response($resultado);
	}

}
