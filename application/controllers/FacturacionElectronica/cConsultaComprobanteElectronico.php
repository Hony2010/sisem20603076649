<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cConsultaComprobanteElectronico extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
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
		$data["EstadoCPE"] = "'%'";
		$data["IdTipoDocumento"] = "%";
		$parametro["IdModuloSistema"] =ID_MODULO_VENTA;
		$TiposDocumento = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($parametro,0);
		$ConsultasComprobanteElectronico = $this->sComprobanteElectronico->ConsultarComprobantesVentaElectronico($data);

		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = $data["FechaInicio"];
		$Buscador = $data;

		$ParametroEnvioEmail = $this->sConstanteSistema->ObtenerParametroEnvioEmail();

		$Numero_Filas = 0;
		foreach ($ConsultasComprobanteElectronico as $key => $value) {
			if ($ConsultasComprobanteElectronico[$key]['IndicadorEstadoCPE'] == ESTADO_CPE_GENERADO) {
				$Numero_Filas++;
			}
		}
		
		$data = array("data" =>
					array(
						'ConsultasComprobanteElectronico' => $ConsultasComprobanteElectronico,
						'ConsultaComprobanteElectronico' => array(),
						'Buscador' => $Buscador,
						'Numero_Filas' => $Numero_Filas,
						'TiposDocumento'=>$TiposDocumento,
						'ParametroEnvioEmail' => $ParametroEnvioEmail
					)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_consultacomprobanteelectronico']=   $this->load->View('FacturacionElectronica/ConsultaComprobanteElectronico/view_mainpanel_subcontent_buscador_consultacomprobanteelectronico','',true);
		$view_subcontent['view_subcontent_consulta_consultacomprobanteelectronicos'] =  $this->load->View('FacturacionElectronica/ConsultaComprobanteElectronico/view_mainpanel_subcontent_consulta_consultacomprobanteelectronicos',$view_sub_subcontent,true);
		$view_subcontent['view_mainpanel_modal_enviocomprobante'] =  $this->load->View('FacturacionElectronica/ConsultaComprobanteElectronico/view_mainpanel_modal_enviocomprobante','',true);

		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/ConsultaComprobanteElectronico/view_mainpanel_footer_consultacomprobanteelectronico',$view_data,true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/ConsultaComprobanteElectronico/view_mainpanel_content_consultacomprobanteelectronico',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarComprobantesVentaElectronico()
	{
		$data = $this->input->post("Data");
		$data["FechaInicio"]=convertToDate($data["FechaInicio"]);
		$data["FechaFin"]=convertToDate($data["FechaFin"]);
		$resultado = $this->sComprobanteElectronico->ConsultarComprobantesVentaElectronico($data);

		echo $this->json->json_response($resultado);
	}

}
