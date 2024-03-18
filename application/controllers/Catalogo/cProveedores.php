<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cProveedores extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sProveedor");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$input["textofiltro"]='';
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = $this->sProveedor->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["totalfilas"] =$this->sProveedor->ObtenerNumeroTotalProveedores($input);

		$Proveedor=$this->sProveedor->Cargar();
		$Proveedores = $this->sProveedor->ListarProveedores(1);
		$data = array(
			"data" => array(
				'Filtros' => $input,
				'Proveedor'  => $Proveedor,
				'Proveedores'  => $Proveedores
				)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_proveedores']=   $this->load->View('Catalogo/Proveedores/view_mainpanel_subcontent_buscador_proveedores','',true);
		$view_sub_subcontent['view_subcontent_paginacion_proveedores']=   $this->load->View('Catalogo/Proveedores/view_mainpanel_subcontent_paginacion_proveedores','',true);
		$view_subcontent['view_subcontent_preview_proveedor'] =  $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_preview_proveedor','',true);
		$view_subcontent['view_subcontent_consulta_proveedores'] =  $this->load->View('Catalogo/Proveedores/view_mainpanel_subcontent_consulta_proveedores',$view_sub_subcontent,true);
		$view_subcontent_panel['view_subcontent_modal_preview_foto_proveedor'] =  $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);
    $view_subcontent_panel['view_subcontent_form_proveedor'] =  $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$view_subcontent['view_subcontent_modal_proveedor'] =  $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$view_subcontent_panel,true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/Proveedores/view_mainpanel_footer_proveedores',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/Proveedores/view_mainpanel_content_proveedores',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarProveedores()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sProveedor->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sProveedor->ObtenerNumeroTotalProveedores($input);
		$output["resultado"] = $this->sProveedor->ConsultarProveedores($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarProveedoresPorIdPersona()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;
		$resultado = $this->sProveedor->ConsultarProveedoresPorIdPersona($data, 1);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaProveedoresPorRuc()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sProveedor->ConsultarSugerenciaProveedoresPorRuc($data, 1);

		echo $this->json->json_response($resultado);
	}


	public function ListarProveedores()
	{
		$resultado = $this->sProveedor->ListarProveedores();

		echo $this->json->json_response($resultado);
	}

	public function ConsultarProveedoresPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sProveedor->ConsultarProveedores($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
}
