<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cFacturacionElectronica extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("FacturacionElectronica/sFacturacionElectronica");
		$this->load->service("Venta/sComprobanteVenta");//Pruebas
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		//$this->load->service('FacturacionElectronica/sFacturacionElectronica2');
	}

	public function Index()
	{
		$FacturacionElectronica =  $this->sFacturacionElectronica->FacturacionElectronica;
		$FacturacionesElectronica = $this->sFacturacionElectronica->ListarFacturacionesElectronica(1);
    /*
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();
		$data["IdFamiliaProducto"]=$FamiliasProducto[0]->IdFamiliaProducto;
 	  $SubFamiliasProducto = $this->sSubFamiliaProducto->ListarSubFamiliasProducto($data);
		$TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();
    $Marcas = $this->sMarca->ListarMarcas();
    $data["IdMarca"] = $Marcas[0]->IdMarca;
    $Modelos = $this->sModelo->ListarModelos($data);
		$LineasProducto = $this->sLineaProducto->ListarLineasProducto();
		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
		$Fabricantes = $this->sFabricante->ListarFabricantes();
		$Monedas = $this->sMoneda->ListarMonedas();
		*/
		$data = array("data" =>
					array(
						'FacturacionElectronica'=>$FacturacionElectronica,
						'FacturacionesElectronica'=>$FacturacionesElectronica
						//'Mercaderias'=>$Mercaderias,
						/*'FamiliasProducto'=>$FamiliasProducto,
						'SubFamiliasProducto'=>$SubFamiliasProducto,
            'TiposExistencia'=>$TiposExistencia,
            'Marcas'=>$Marcas,
            'Modelos'=>$Modelos,
						'LineasProducto' =>$LineasProducto,
						'UnidadesMedida' =>$UnidadesMedida,
						'Fabricantes' =>$Fabricantes,
						'Monedas' =>$Monedas*/
					)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_facturacionelectronica']=   $this->load->View('FacturacionElectronica/view_mainpanel_subcontent_buscador_facturacionelectronica','',true);
		$view_subcontent['view_subcontent_preview_facturacionelectronica'] =  $this->load->View('FacturacionElectronica/view_mainpanel_subcontent_preview_facturacionelectronica','',true);
		$view_subcontent['view_subcontent_consulta_facturacionelectronica'] =  $this->load->View('FacturacionElectronica/view_mainpanel_subcontent_consulta_facturacionelectronica',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_facturacionelectronica'] =  $this->load->View('FacturacionElectronica/view_mainpanel_subcontent_form_facturacionelectronica','',true);
		$view_ext['view_footer_extension'] = $this->load->View('FacturacionElectronica/view_mainpanel_footer_facturacionelectronica',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('FacturacionElectronica/view_mainpanel_content_facturacionelectronica','',true);//$view_subcontent
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

 //Prueba
	public function InsertarFacturacionElectronica($data)
	{
		//$data["IdComprobanteVenta"]=126;
		//$resultado = $this->sComprobanteVenta->ObtenerCabeceraArchivoPlanoDeComprobanteVenta($data);  //$this->input->post("Data");
		//$data = $this->input->post("Data");
		$resultado = $this->sFacturacionElectronica->InsertarFacturacionElectronica($data);//$resultado[0]

		echo $this->json->json_response($resultado);
	}

	public function GenerarFacturacionElectronica()
	{
		$data = $this->input->post("Data"); //$data["NombreArchivoFacturacion"]="20449458436-01-F001-00005627";
		$resultado = $this->sFacturacionElectronica->GenerarFacturacionElectronica($data);

		echo $resultado;//$this->json->json_response($resultado);
	}

	public function ImprimirFacturacionElectronica()
	{
		$data = $this->input->post("Data"); //$data["NombreArchivoFacturacion"]="20449458436-01-F001-00005627";
		$resultado = $this->sFacturacionElectronica->ImprimirFacturacionElectronica($data);

		echo $resultado;//$this->json->json_response($resultado);
	}

	public function EnviarFacturacionElectronica()
	{
		//$data["IdComprobanteVenta"]=4;
		//$resultado = $this->sComprobanteVenta->ObtenerCabeceraArchivoPlanoDeComprobanteVenta($data);  //$this->input->post("Data");
		//print_r($resultado[0]);
		//$resultado["NombreArchivoFacturacion"]="20449458436-01-F001-00005627";
		//$resultado = $this->sFacturacionElectronica2->EnviarFacturacionElectronica($resultado);
		echo $this->json->json_response($resultado);
	}

}
