<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConsultaCuentaCobranza extends CI_Controller  {

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
		$this->load->service("Caja/sComprobanteCaja");
		$this->load->service("Caja/sTipoOperacionCaja");
		$this->load->service('Seguridad/sAccesoCajaUsuario');
		$this->load->service("CuentaCobranza/sCobranzaCliente");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Configuracion/General/sMedioPago");
		$this->load->service("Seguridad/sUsuario");
		$this->load->service('Seguridad/sAccesoCajaUsuario');
	}

	public function Index()
	{
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["TextoFiltro"] = '';
		$input["FechaInicio"] = $fechaservidor;
		$input["FechaFin"] = $fechaservidor;
		$input["IdCaja"] = '%';
		$input["IdTipoOperacionCaja"] = '%';
		$input["IdTipoDocumento"] = '%';
		$input["IdUsuario"] = '%';
		$input["IdMedioPago"] = '%';
		$input["IdCaja"] = '%';
		

		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sComprobanteCaja->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sComprobanteCaja->ObtenerNumeroTotalCobranzasCliente($input);

		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_RECIBO_INGRESO;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$CuentaCobranza= $this->sComprobanteCaja->CargarComprobanteCaja($parametro);
		$CuentasCobranza = $this->sComprobanteCaja->ConsultarCobranzasCliente($input,$input["pagina"],$input["numerofilasporpagina"]);//$pagina


		$input["FechaInicio"]=date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"]=date("d/m/Y", strtotime($input["FechaFin"]));
		$input["Cajas"] = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario();
		$input["TiposOperacionCaja"] = $this->sTipoOperacionCaja->ListarTiposOperacionCaja();;
		$input["IdCaja"] = "";
		$input["IdTipoOperacionCaja"] = "";

		$input["IdModuloSistema"]=ID_MODULO_CUENTA_POR_COBRAR;
		$input["IdTipoDocumento"]=FILTRO_TODOS;
		$input["TiposDocumentoCaja"]=$this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);
		$input["Usuarios"] = $this->sUsuario->ListarUsuarios();
		$input["Cajas"] = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario();
		$input["MediosPago"] = $this->sMedioPago->ListarMediosPago();

		$CobranzaCliente =  $this->sCobranzaCliente->Cargar();

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'CuentaCobranza' => $CuentaCobranza,
				'CuentasCobranza'=> $CuentasCobranza,
				'CobranzaCliente' => $CobranzaCliente
			)
		);

		$view_data['data'] = $data;

		$views_cc['view_form_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_form_cobranzacliente','',true);
		$views_cc['view_modal_comprobantes_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_modal_comprobantes_cobranzacliente','',true);
		$views['view_modal_cobranzacliente'] = $this->load->View('CuentaCobranza/CobranzaCliente/view_modal_cobranzacliente',$views_cc,true);

		$views['view_buscador_consultacuentacobranza'] = $this->load->View('CuentaCobranza/ConsultaCuentaCobranza/view_buscador_consultacuentacobranza','',true);
		$views['view_tabla_consultacuentacobranza'] = $this->load->View('CuentaCobranza/ConsultaCuentaCobranza/view_tabla_consultacuentacobranza','',true);
		$views['view_paginacion_consultacuentacobranza'] = $this->load->View('CuentaCobranza/ConsultaCuentaCobranza/view_paginacion_consultacuentacobranza',"",true);

		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/ConsultaCuentaCobranza/view_footer_consultacuentacobranza',$view_data,true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/ConsultaCuentaCobranza/view_content_consultacuentacobranza',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$this->load->View('.Master/master_view_mainpanel',$view);

	}

	public function ConsultarCuentasCobranza()
	{
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sComprobanteCaja->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sComprobanteCaja->ObtenerNumeroTotalCobranzasCliente($input);
		$output["resultado"] = $this->sComprobanteCaja->ConsultarCobranzasCliente($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarcuentasCobranzaPorPagina()
	{
		$input = json_decode($this->input->post("Data"), true);
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$resultado = $this->sComprobanteCaja->ConsultarCobranzasCliente($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
