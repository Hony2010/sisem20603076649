<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMarca extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sModelo");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$NuevaMarca =  $this->sMarca->Marca;
		$Marca =  $this->sMarca->Marca;
		$Marcas = $this->sMarca->ListarMarcas();
		$data["IdMarca"]=-1;
 	  $Modelos = $this->sModelo->ListarModelos($data);
		$Modelo = $this->sModelo->Modelo;
		$NuevoModelo =  $this->sModelo->Modelo;
		$NuevaMarca["IdMarca"] = -1;
		$NuevoModelo["IdModelo"] = -1;

		$data = array("data" =>
					array(
						'Marcas' => $Marcas,
						'Modelos' => $Modelos,
						'Marca' => $Marca,
						'NuevaMarca' => $NuevaMarca,
						'Modelo' =>$Modelo,
						'NuevoModelo' => $NuevoModelo
					)
		 );

		$view_data['data'] = $data;

		$view_subcontent['view_subcontent_marca'] =  $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_subcontent_marca','',true);
		$view_subcontent['view_subcontent_modelo'] =  $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_subcontent_modelo','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_footer_marca',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_content_marca',$view_subcontent,true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

  public function ListarMarcas()
	{
		$resultado = $this->sMarca->ListarMarcas();

		echo $this->json->json_response($resultado);
	}

	public function InsertarMarca()
	{
		$data = $this->input->post("Data");
		$data["IdMarca"] = null;
		$resultado = $this->sMarca->InsertarMarca($data);
		$data["IdMarca"] = $resultado["IdMarca"];

		echo $this->json->json_response($data);
	}

	public function ActualizarMarca()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMarca->ActualizarMarca($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarMarca()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMarca->BorrarMarca($data);
		echo $this->json->json_response($resultado);
	}

}
