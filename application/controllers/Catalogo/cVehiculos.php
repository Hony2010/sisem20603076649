<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVehiculos extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sVehiculo");
		$this->load->service("Catalogo/sRadioTaxi");
		// $this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$input["textofiltro"]='';
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = $this->sVehiculo->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["totalfilas"] = $this->sVehiculo->ObtenerNumeroTotalVehiculos($input);
		$Vehiculo=$this->sVehiculo->Inicializar();
		$Vehiculos = $this->sVehiculo->ListarVehiculos(1);
		$RadiosTaxi = $this->sRadioTaxi->ListarRadioTaxis();

		$data = array(
		"data" => array(
				'Filtros' => $input,
				'Vehiculo'  => $Vehiculo,
				'NuevoVehiculo'  => $Vehiculo,
				'Vehiculos'  => $Vehiculos,
				'RadiosTaxi' => $RadiosTaxi
			)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_vehiculos']=   $this->load->View('Catalogo/Vehiculos/view_mainpanel_subcontent_buscador_vehiculos','',true);
		$view_sub_subcontent['view_subcontent_paginacion_vehiculos']=   $this->load->View('Catalogo/vehiculos/view_mainpanel_subcontent_paginacion_vehiculos','',true);
  		$view_subcontent['view_subcontent_preview_vehiculo'] =  $this->load->View('Catalogo/Vehiculo/view_mainpanel_subcontent_preview_vehiculo','',true);
		$view_subcontent['view_subcontent_consulta_vehiculos'] =  $this->load->View('Catalogo/Vehiculos/view_mainpanel_subcontent_consulta_vehiculos',$view_sub_subcontent,true);
		$view_subcontent_panel['view_subcontent_modal_preview_foto_vehiculo'] =  $this->load->View('Catalogo/Vehiculo/view_mainpanel_subcontent_modal_preview_foto_vehiculo','',true);
		$view_subcontent_panel['view_form_vehiculo'] =  $this->load->View('Catalogo/Vehiculo/view_mainpanel_subcontent_form_vehiculo','',true);
		$view_subcontent['view_subcontent_modal_vehiculo'] =  $this->load->View('Catalogo/Vehiculo/view_mainpanel_subcontent_modal_vehiculo',$view_subcontent_panel,true);

		$view_footer_extension['view_footer_extension'] = $this->load->View('Catalogo/Vehiculos/view_mainpanel_footer_vehiculos',$view_data,true);
		// $view_content['view_content_min'] =  $this->load->View('Catalogo/Vehiculos/view_mainpanel_content_vehiculos',$view_subcontent,true);

		// $this->load->View('.Master/master_view_mainpanel_min',$view);
		
		////////

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('Catalogo/Vehiculos/view_mainpanel_content_vehiculos', $view_subcontent, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer_extension, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ConsultarVehiculos()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sVehiculo->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sVehiculo->ObtenerNumeroTotalVehiculos($input);
		$output["resultado"] = $this->sVehiculo->ConsultarVehiculos($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarVehiculosPorIdPersona()
	{
		$data["IdPersona"] = $this->input->post("Data");
		$resultado = $this->sVehiculo->ConsultarVehiculosPorIdPersona($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaVehiculosPorRuc()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sVehiculo->ConsultarSugerenciaVehiculosPorRuc($data, 1);
		echo $this->json->json_response($resultado);
	}


	public function ListarVehiculos()
	{
		$resultado = $this->sVehiculo->ListarVehiculos();
		echo $this->json->json_response($resultado);
	}

	public function ConsultarVehiculosPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sVehiculo->ConsultarVehiculos($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
