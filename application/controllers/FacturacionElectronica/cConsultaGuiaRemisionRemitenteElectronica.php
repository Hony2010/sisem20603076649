<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cConsultaGuiaRemisionRemitenteElectronica extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("FacturacionElectronica/sGuiaRemisionRemitenteElectronica");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Configuracion/General/sConstanteSistema");
		$this->load->helper('url');
		$this->load->helper('form');
		//$this->load->helper('security');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('logger');
		$this->load->helper('date');
	}

	public function Index()
	{
		$data["NumeroDocumento"] = "%";
		$data["RazonSocial"] = "%";
		$data["FechaInicio"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaFin"] = $data["FechaInicio"];
		$data["EstadoCPE"] = "%";
		$ConsultasGuiaRemisionRemitenteElectronica = $this->sGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteElectronica($data);

		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = $data["FechaInicio"];
		$Buscador = $data;

		$ParametroEnvioEmail = $this->sConstanteSistema->ObtenerParametroEnvioEmail();

		// $Numero_Filas = 0;
		// foreach ($ConsultasGuiaRemisionRemitenteElectronica as $key => $value) {
		// 	if ($ConsultasGuiaRemisionRemitenteElectronica[$key]['IndicadorEstadoCPE'] == ESTADO_CPE_GENERADO) {
		// 		$Numero_Filas++;
		// 	}
		// }

		$data = array(
			"data" =>
			array(
				'ConsultasGuiaRemisionRemitenteElectronica' => $ConsultasGuiaRemisionRemitenteElectronica,
				'ConsultaGuiaRemisionRemitenteElectronica' => array(),
				'Buscador' => $Buscador,
				// 'Numero_Filas' => $Numero_Filas,
				'ParametroEnvioEmail' => $ParametroEnvioEmail
			)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_consultaguiaremisionremitenteelectronica'] =   $this->load->View('FacturacionElectronica/ConsultaGuiaRemisionRemitenteElectronica/view_mainpanel_subcontent_buscador_consultaguiaremisionremitenteelectronica', '', true);
		$view_subcontent['view_subcontent_consulta_consultaguiaremisionremitenteelectronicas'] =  $this->load->View('FacturacionElectronica/ConsultaGuiaRemisionRemitenteElectronica/view_mainpanel_subcontent_consulta_consultaguiaremisionremitenteelectronicas', $view_sub_subcontent, true);

		$view_footer['view_footer_extension'] = $this->load->View('FacturacionElectronica/ConsultaGuiaRemisionRemitenteElectronica/view_mainpanel_footer_consultaguiaremisionremitenteelectronica', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('FacturacionElectronica/ConsultaGuiaRemisionRemitenteElectronica/view_mainpanel_content_consultaguiaremisionremitenteelectronica', $view_subcontent, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ConsultarGuiasRemisionRemitenteElectronica()
	{
		$data = $this->input->post("Data");
		$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
		$data["FechaFin"] = convertToDate($data["FechaFin"]);
		$resultado = $this->sGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteElectronica($data);

		echo $this->json->json_response($resultado);
	}

	
}
