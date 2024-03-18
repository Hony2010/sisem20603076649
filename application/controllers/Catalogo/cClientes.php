<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cClientes extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sCliente");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("Catalogo/sAlumno");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$input["textofiltro"]='';
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = $this->sCliente->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["totalfilas"] =$this->sCliente->ObtenerNumeroTotalClientes($input);
		$Cliente=$this->sCliente->Cargar();
		$Clientes = $this->sCliente->ListarClientes(1);


		// Alunmo
		$Parametro['ParametroAlumno'] = $this->sConstanteSistema->ObtenerParametroAlumno();
		
		$data["IdPersona"]="";
		$Alumnos = $this->sAlumno->ListarAlumnos($data);
		$Alumno = $this->sAlumno->Alumno;
		$NuevoAlumno = $this->sAlumno->Alumno;
		$NuevoAlumno["IdAlumno"] = -1;

		$data = array(
		"data" => array(
				'Filtros' => $input,
				'Cliente'  => $Cliente,
				'Clientes'  => $Clientes,
				'Parametro'  => $Parametro,
				'Alumnos' => $Alumnos,
				'Alumno' =>$Alumno,
				'NuevoAlumno' =>$NuevoAlumno
			)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_clientes']=   $this->load->View('Catalogo/Clientes/view_mainpanel_subcontent_buscador_clientes','',true);
		$view_sub_subcontent['view_subcontent_paginacion_clientes']=   $this->load->View('Catalogo/clientes/view_mainpanel_subcontent_paginacion_clientes','',true);
  		$view_subcontent['view_subcontent_preview_cliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_preview_cliente','',true);
		$view_subcontent['view_subcontent_consulta_clientes'] =  $this->load->View('Catalogo/Clientes/view_mainpanel_subcontent_consulta_clientes',$view_sub_subcontent,true);
		$view_subcontent_panel['view_subcontent_modal_preview_foto_cliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);
		$view_subcontent_panel['view_form_cliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$view_subcontent_panel['view_mainpanel_modal_vehiculocliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_modal_vehiculocliente','',true);
		$view_subcontent['view_form_alumno'] =  $this->load->View('Catalogo/Clientes/view_mainpanel_subcontent_alumno','',true);
		$view_subcontent['view_subcontent_modal_cliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$view_subcontent_panel,true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/Clientes/view_mainpanel_footer_clientes',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/Clientes/view_mainpanel_content_clientes',$view_subcontent,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}
	public function ConsultarClientes()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sCliente->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sCliente->ObtenerNumeroTotalClientes($input);
		$output["resultado"] = $this->sCliente->ConsultarClientes($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarClientesPorIdPersona()
	{
		$data["IdPersona"] = $this->input->post("Data");
		$resultado = $this->sCliente->ConsultarClientesPorIdPersona($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaClientesPorRuc()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sCliente->ConsultarSugerenciaClientesPorRuc($data, 1);
		echo $this->json->json_response($resultado);
	}


	public function ListarClientes()
	{
		$resultado = $this->sCliente->ListarClientes();
		echo $this->json->json_response($resultado);
	}

	public function ConsultarClientesPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sCliente->ConsultarClientes($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
