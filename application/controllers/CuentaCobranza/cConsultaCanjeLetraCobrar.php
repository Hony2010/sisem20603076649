<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConsultaCanjeLetraCobrar extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->service("Caja/sCanjeLetraCobrar");
		$this->load->service("Caja/sTipoOperacionCaja");
		$this->load->service('Seguridad/sAccesoCajaUsuario');
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
	}

	public function Index()
	{
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["TextoFiltro"] = '';
		$input["FechaInicio"] = $fechaservidor;
		$input["FechaFin"] = $fechaservidor;
		$input["IdMoneda"] = '%';
		$input["IdTipoDocumento"] = '%';

		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sCanjeLetraCobrar->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sCanjeLetraCobrar->ObtenerNumeroTotalCanjesLetraCobrar($input);

		$CanjeLetraCobrar= $this->sCanjeLetraCobrar->Cargar();
		$CanjesLetraCobrar = $this->sCanjeLetraCobrar->ConsultarCanjesLetraCobrar($input,$input["pagina"],$input["numerofilasporpagina"]);//$pagina


		$input["FechaInicio"]=date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"]=date("d/m/Y", strtotime($input["FechaFin"]));

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'CanjeLetraCobrar' => $CanjeLetraCobrar,
				'CanjesLetraCobrar'=> $CanjesLetraCobrar,
				'BusquedaPendientesCobranzaCliente' => array()
			)
		);

		$view_data['data'] = $data;

		$views_cc['view_form_canjeletracobrar'] = $this->load->View('CuentaCobranza/CanjeLetraCobrar/view_form_canjeletracobrar','',true);
		$views['view_modal_canjeletracobrar'] = $this->load->View('CuentaCobranza/CanjeLetraCobrar/view_modal_canjeletracobrar',$views_cc,true);

		$views['view_buscador_consultacanjeletracobrar'] = $this->load->View('CuentaCobranza/ConsultaCanjeLetraCobrar/view_buscador_consultacanjeletracobrar','',true);
		$views['view_tabla_consultacanjeletracobrar'] = $this->load->View('CuentaCobranza/ConsultaCanjeLetraCobrar/view_tabla_consultacanjeletracobrar','',true);
		$views['view_paginacion_consultacanjeletracobrar'] = $this->load->View('CuentaCobranza/ConsultaCanjeLetraCobrar/view_paginacion_consultacanjeletracobrar',"",true);

		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/ConsultaCanjeLetraCobrar/view_footer_consultacanjeletracobrar',$view_data,true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/ConsultaCanjeLetraCobrar/view_content_consultacanjeletracobrar',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$this->load->View('.Master/master_view_mainpanel',$view);

	}

	public function ConsultarCanjesLetraCobrar()
	{
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sCanjeLetraCobrar->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sCanjeLetraCobrar->ObtenerNumeroTotalCanjesLetraCobrar($input);
		$output["resultado"] = $this->sCanjeLetraCobrar->ConsultarCanjesLetraCobrar($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarCanjesLetraCobrarPorPagina()
	{
		$input = json_decode($this->input->post("Data"), true);
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$resultado = $this->sCanjeLetraCobrar->ConsultarCanjesLetraCobrar($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
