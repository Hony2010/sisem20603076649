<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cFamiliaProducto extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$NuevaFamiliaProducto =  $this->sFamiliaProducto->FamiliaProducto;
		$FamiliaProducto =  $this->sFamiliaProducto->FamiliaProducto;
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();
		$data["IdFamiliaProducto"]=-1;
 	  	$SubFamiliasProducto = $this->sSubFamiliaProducto->ListarSubFamiliasProducto($data);
		$SubFamiliaProducto = $this->sSubFamiliaProducto->SubFamiliaProducto;
		$NuevaSubFamiliaProducto = $this->sSubFamiliaProducto->SubFamiliaProducto;
		$NuevaFamiliaProducto["IdFamiliaProducto"] = -1;
		$NuevaSubFamiliaProducto["IdSubFamiliaProducto"] = -1;

		$data = array("data" =>
					array(
						'FamiliasProducto' => $FamiliasProducto,
						'SubFamiliasProducto' => $SubFamiliasProducto,
						'FamiliaProducto' => $FamiliaProducto,
						'NuevaFamiliaProducto' => $NuevaFamiliaProducto,
						'SubFamiliaProducto' =>$SubFamiliaProducto,
						'NuevaSubFamiliaProducto' =>$NuevaSubFamiliaProducto
					)
		 );

		$view_data['data'] = $data;

		$view_sub_subcontent['view_subcontent_buscador_familiaproducto']=   $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_buscador_familiaproducto','',true);
		$view_subcontent['view_subcontent_preview_familiaproducto'] =  $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_preview_familiaproducto','',true);
		$view_subcontent['view_subcontent_familiaproducto'] =  $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_familiaproducto',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_subfamiliaproducto'] =  $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_subfamiliaproducto','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_footer_familiaproducto',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    	$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    	$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =$this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_content_familiaproducto',$view_subcontent,true);
    	$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarFamiliasProducto() {
		$resultado = $this->sFamiliaProducto->ListarFamiliasProducto();

		echo $this->json->json_response($resultado);
	}

	public function InsertarFamiliaProducto()
	{
		$data = $this->input->post("Data");
		$data["IdFamiliaProducto"] = null;
		$resultado = $this->sFamiliaProducto->InsertarFamiliaProducto($data);
		$data["IdFamiliaProducto"] = $resultado["IdFamiliaProducto"];

		echo $this->json->json_response($data);
	}

	public function ActualizarFamiliaProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFamiliaProducto->ActualizarFamiliaProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarFamiliaProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sFamiliaProducto->BorrarFamiliaProducto($data);
		echo $this->json->json_response($resultado);
	}

}
