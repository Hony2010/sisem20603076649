<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConfiguracionImpresion extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sConfiguracionImpresion");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');

    $this->load->library('conversorpdf');
    $this->load->library('imprimir');
	}

	public function Index()
	{
		$ConfiguracionImpresion = $this->sConfiguracionImpresion->ListarConfiguracionImpresion();
		$canvas["AltoHoja"] = "";
		$canvas["AnchoHoja"] = "";
		$canvas["RutaArchivoConfiguracionImpresion"] = "";
		$canvas["RutaArchivoPlantillaCanvas"] = "";
		$canvas["NombreConfiguracionImpresion"] = "";
		$canvas["RutaMarcaAgua"] = "";

		$data = array("data" =>
					array(
						'ConfiguracionImpresion'=>$ConfiguracionImpresion,
						'Canvas'=>$canvas
					)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_configuracionimpresion']=   $this->load->View('Configuracion/General/ConfiguracionImpresion/view_mainpanel_subcontent_buscador_configuracionimpresion','',true);
		$view_subcontent['view_subcontent_preview_canvas'] =  $this->load->View('Configuracion/General/ConfiguracionImpresion/view_mainpanel_subcontent_preview_canvas','',true);
		$view_subcontent['view_subcontent_consulta_configuracionimpresion'] =  $this->load->View('Configuracion/General/ConfiguracionImpresion/view_mainpanel_subcontent_consulta_configuracionimpresion',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_consulta_canvas'] =  $this->load->View('Configuracion/General/ConfiguracionImpresion/view_mainpanel_subcontent_consulta_canvas',$view_sub_subcontent,true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/ConfiguracionImpresion/view_mainpanel_footer_configuracionimpresion',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Configuracion/General/ConfiguracionImpresion/view_mainpanel_content_configuracionimpresion',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ActualizarConfiguracionImpresion()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sConfiguracionImpresion->ActualizarConfiguracionImpresion($data);
		echo $this->json->json_response($resultado);
	}

	public function CrearJSONCanvas()
  {
    $data = $this->input->post("data");

		//CREANDO JSON PARA PRE-IMPRESION
		$data_json["archivo"] = $data["archivoimpresion"];
		$data_json["data"] = $data;
		$json = $this->json->CrearJSONCanvas($data_json);

    //GUARDANDO DATA DE OBJETOS
		$data_plantilla["archivo"] = $data["archivocanvas"];
    $data_plantilla["data"] = $data["datos_json"];
    $json_objeto = $this->json->CrearArchivoJSON($data_plantilla);

    $response["data"] = $json_canvas;
    $response["msg"] = "";
    echo $this->json->json_response($response);
  }

  //FUNCION PARA RENDERIZAR
  public function RenderizarJSONImpresion()
  {
		$data1 = $this->input->post("data");

    $data3["codigo"] = "BOL120-001";
  	$data3["nombre"] = "Nuevo Articulo | ©";
  	$data3["detalle"][0]["CodigoDescripcion"] = "123.00";
  	$data3["detalle"][0]["NombreDescripcion"] = "ñññññì";
  	$data3["detalle"][0]["TipoCambio"] = "SOL";
  	$data3["detalle"][0]["Moneda"] = "S";
  	$data3["detalle"][1]["CodigoDescripcion"] = "823.00";
  	$data3["detalle"][1]["NombreDescripcion"] = "ssç#$!--";
  	$data3["detalle"][1]["TipoCambio"] = "DOL";
  	$data3["detalle"][1]["Moneda"] = "D";
  	$data3["detalle"][2]["CodigoDescripcion"] = "5000.00";
  	$data3["detalle"][2]["NombreDescripcion"] = "PRODUCTO1";
  	$data3["detalle"][2]["TipoCambio"] = "SOL";
  	$data3["detalle"][2]["Moneda"] = "S";

		$data["plantilla"] = $data1;
    $data["imprimir"] =APP_PATH."assets/data/archivo/imprimir.json";
    $data["data"] = $data3;
    $resultado = $this->conversorpdf->RenderizacionJSONObjets($data);
    echo $this->json->json_response($resultado);
  }

  public function ImprimirJSON()
  {
		$data1 = $this->input->post("data");

    //Convertimos a PDF el archivo
    $data["json"] = APP_PATH."assets/data/archivo/imprimir.json";
    $data["archivo"] = APP_PATH."assets/data/archivo/print.pdf";
    $data["impresora"] = "PDFCreator";

    $resultado2 = $this->conversorpdf->CrearPDFJSON($data);
    $resultado = $this->imprimir->ImprimirPDF($data);
    echo $resultado.$resultado2;
  }


  public function CargarJSON()
  {
		$data1 = $this->input->post("data");

		$archivo = $data1;    
    $resultado = file_get_contents($archivo);
    echo $resultado;
  }

}
