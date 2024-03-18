<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoDocumento extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sTipoDocumento");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$TipoDocumento =  $this->sTipoDocumento->TipoDocumento;
		$TiposDocumento = $this->sTipoDocumento->ListarTiposDocumento();

		$data = array("data" =>
					array(
						'TiposDocumento' => $TiposDocumento,
						'TipoDocumento' => $TipoDocumento
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/TipoDocumento/view_mainpanel_content_tipodocumento','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/TipoDocumento/view_mainpanel_footer_tipodocumento',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTiposDocumento()
	{
		$resultado = $this->sTipoDocumento->ListarTiposDocumento();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoDocumento()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoDocumento->InsertarTipoDocumento($data);

		if (is_numeric($resultado)) {
			$data["IdTipoDocumento"] = $resultado;
			$ModuloSistema = $this->sTipoDocumentoModuloSistema->InsertarActualizarTipoDocumentoModuloSistema($data);
			$data['ModulosSistema'] = $ModuloSistema;
			$data['IdTipoDocumento'] = $resultado;
			echo $this->json->json_response($data);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function ActualizarTipoDocumento()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoDocumento->ActualizarTipoDocumento($data);

		if ($resultado == "") {
			$ModuloSistema = $this->sTipoDocumentoModuloSistema->InsertarActualizarTipoDocumentoModuloSistema($data);
			echo $this->json->json_response($ModuloSistema);
		}
		else {
			echo $this->json->json_response($resultado);
		}
	}

	public function BorrarTipoDocumento()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoDocumento->BorrarTipoDocumento($data);
		echo $this->json->json_response($resultado);
	}

}
