<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCostoServicio extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sCostoServicio");
		$this->load->service("Configuracion/Catalogo/sTipoProducto");
		$this->load->service("Configuracion/General/sUnidadMedida");
		$this->load->service("Configuracion/Catalogo/sTipoExistencia");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$CostoServicio =  $this->sCostoServicio->CostoServicio;
		$CostoServicio['NombreTipoProducto'] = "";
		$CostoServicio['NombreUnidadMedida'] = "";
		$CostoServicio['NombreTipoExistencia'] = "";
		$CostoServicio['AbreviaturaUnidadMedida'] = "";
		$CostosServicio = $this->sCostoServicio->ListarCostosServicio();
		$TiposProducto = $this->sTipoProducto->ListarTiposProducto();
		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
		$TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();

		$data = array("data" =>
					array(
						'CostosServicio' => $CostosServicio,
						'CostoServicio' => $CostoServicio,
						'TiposProducto' => $TiposProducto,
						'UnidadesMedida' => $UnidadesMedida,
						'TiposExistencia' => $TiposExistencia
					)
		 );

		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Catalogo/CostoServicio/view_mainpanel_content_costoservicio','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Catalogo/CostoServicio/view_mainpanel_footer_costoservicio',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarCostosServicio()
	{
		$resultado = $this->sCostoServicio->ListarCostosServicio();

		echo $this->json->json_response($resultado);
	}

	public function InsertarCostoServicio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCostoServicio->InsertarCostoServicio($data);
		$data["IdProducto"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarCostoServicio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCostoServicio->ActualizarCostoServicio($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarCostoServicio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCostoServicio->BorrarCostoServicio($data);
		echo $this->json->json_response($resultado);
	}

}
