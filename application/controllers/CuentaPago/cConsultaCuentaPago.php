<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConsultaCuentaPago extends CI_Controller  {

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
		$this->load->service("CuentaPago/sPagoProveedor");
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
		$input["totalfilas"] = $this->sComprobanteCaja->ObtenerNumeroTotalPagosProveedor($input);

		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_RECIBO_INGRESO;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$CuentaPago= $this->sComprobanteCaja->CargarComprobanteCaja($parametro);
		$CuentasPago = $this->sComprobanteCaja->ConsultarPagosProveedor($input,$input["pagina"],$input["numerofilasporpagina"]);//$pagina

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


		$PagoProveedor =  $this->sPagoProveedor->Cargar();

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'CuentaPago' => $CuentaPago,
				'CuentasPago'=> $CuentasPago,
				'PagoProveedor' => $PagoProveedor
			)
		);

		$view_data['data'] = $data;

		$views_cc['view_form_pagoproveedor'] = $this->load->View('CuentaPago/PagoProveedor/view_form_pagoproveedor','',true);
		$views_cc['view_modal_comprobantes_pagoproveedor'] = $this->load->View('CuentaPago/PagoProveedor/view_modal_comprobantes_pagoproveedor','',true);
		$views['view_modal_pagoproveedor'] = $this->load->View('CuentaPago/PagoProveedor/view_modal_pagoproveedor',$views_cc,true);

		$views['view_buscador_consultacuentapago'] = $this->load->View('CuentaPago/ConsultaCuentaPago/view_buscador_consultacuentapago','',true);
		$views['view_tabla_consultacuentapago'] = $this->load->View('CuentaPago/ConsultaCuentaPago/view_tabla_consultacuentapago','',true);
		$views['view_paginacion_consultacuentapago'] = $this->load->View('CuentaPago/ConsultaCuentaPago/view_paginacion_consultacuentapago',"",true);

		$view_footer['view_footer_extension'] = $this->load->View('CuentaPago/ConsultaCuentaPago/view_footer_consultacuentapago',$view_data,true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaPago/ConsultaCuentaPago/view_content_consultacuentapago',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarCuentasPago()
	{
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sComprobanteCaja->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sComprobanteCaja->ObtenerNumeroTotalPagosProveedor($input);
		$output["resultado"] = $this->sComprobanteCaja->ConsultarPagosProveedor($input,$input["pagina"],$numerofilasporpagina);
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
		$resultado = $this->sComprobanteCaja->ConsultarPagosProveedor($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}
