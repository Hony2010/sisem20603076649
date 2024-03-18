<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cValidacionComprobanteElectronico extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('zipper');
		$this->load->helper('date');
	}


	public function Index()
	{
		$fechaservidor=$this->Base->ObtenerFechaServidor("Y-m-d");
		$input["FechaInicio"] = $fechaservidor;
		$input["FechaFin"] = $fechaservidor;
		$input["TipoDocumento"] = '%';
		$input["TextoFiltro"] = '%';
		$Ventas = $this->sComprobanteElectronico->ConsultarComprobanteElectronicosParaValidacion($input);
		$TotalVentas = count($Ventas);
		$input["FechaInicio"]=date("d/m/Y", strtotime($input["FechaInicio"]));
		$input["FechaFin"]=date("d/m/Y", strtotime($input["FechaFin"]));
		$input["TextoFiltro"] = '';

		$input["IdModuloSistema"]=ID_MODULO_VENTA;
		$input["IdTipoDocumento"]=FILTRO_TODOS;
		$input["TiposDocumentoVenta"]=$this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'NumeroFilas' => $TotalVentas,
				'ComprobantesVenta' => $Ventas,
				'ComprobanteVenta' => array()
				)
		);
		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_validacioncomprobanteelectronico'] =   $this->load->View('FacturacionElectronica/ValidacionComprobanteElectronico/view_mainpanel_subcontent_buscador_validacioncomprobanteelectronico','',true);
		$view_subcontent['view_subcontent_consulta_validacioncomprobanteelectronico'] =  $this->load->View('FacturacionElectronica/ValidacionComprobanteElectronico/view_mainpanel_subcontent_consulta_validacioncomprobanteelectronico',$view_sub_subcontent,true);

		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/ValidacionComprobanteElectronico/view_mainpanel_footer_validacioncomprobanteelectronico',$view_data,true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/ValidacionComprobanteElectronico/view_mainpanel_content_validacioncomprobanteelectronico',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarComprobanteElectronicosParaValidacion()
	{
		try {
			$data = json_decode($this->input->post("Data"), true);
			$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
			$data["FechaFin"] = convertToDate($data["FechaFin"]);
			$resultado = $this->sComprobanteElectronico->ConsultarComprobanteElectronicosParaValidacion($data);
			if(is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 echo $this->json->json_response_error($e);
		}
	}


}
