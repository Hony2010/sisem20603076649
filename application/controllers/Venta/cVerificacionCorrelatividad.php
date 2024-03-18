<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVerificacionCorrelatividad extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service('Venta/sVerificacionCorrelatividad');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('zipper');
		$this->load->helper('date');
	}

	public function Index()
	{
		$Buscador["FechaInicio"] = $this->sVerificacionCorrelatividad->obtener_primer_dia_mes();
		$Buscador["FechaFin"] = $this->sVerificacionCorrelatividad->obtener_ultimo_dia_mes();

		$verificacion = $this->sVerificacionCorrelatividad->Cargar();

		$data = array("data" =>
					array(
						'VerificacionCorrelatividad' => $verificacion,
						'Buscador' => $Buscador
					)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_verificacioncorrelatividad']=   $this->load->View('Venta/VerificacionCorrelatividad/view_mainpanel_subcontent_buscador_verificacioncorrelatividad','',true);
		$view_subcontent['view_subcontent_consulta_verificacioncorrelatividad'] =  $this->load->View('Venta/VerificacionCorrelatividad/view_mainpanel_subcontent_consulta_verificacioncorrelatividad',$view_sub_subcontent,true);

		$view['view_footer_extension'] = $this->load->View('Venta/VerificacionCorrelatividad/view_mainpanel_footer_verificacioncorrelatividad',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/VerificacionCorrelatividad/view_mainpanel_content_verificacioncorrelatividad',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function VerificarCorrelatividadTipo() {
		try {
			$data = $this->input->post("Data");
			$data["Filtros"]["FechaInicio"] = convertToDate($data["Filtros"]["FechaInicio"]);
			$data["Filtros"]["FechaFin"] = convertToDate($data["Filtros"]["FechaFin"]);

			$this->sVerificacionCorrelatividad->BorrarCorrelatividadComprobanteVenta();

			foreach ($data["DetallesVerificacionCorrelatividad"] as $key => $value) {
				if ($value["IndicadorEstadoCheck"] == 'true') {
					$resultado = $this->sVerificacionCorrelatividad->VerificarCorrelatividadTipo($value, $data["Filtros"]);
					$fin = $this->sVerificacionCorrelatividad->InsertarVerificadorCorrelatividad($resultado);
				}
			}
			if (is_numeric($fin)) {
				$jasper  = $this->sVerificacionCorrelatividad->GenerarReporteEXCEL($data["Filtros"]);
				$url["url"] = site_url()."/Venta/cVerificacionCorrelatividad/DescargarArchivo?nombre=".$jasper["reporte"];
			}
			echo $this->json->json_response($url);

		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
	   }

	}

	function DescargarArchivo()
	{
		$data= $this->input->get("nombre");
		$resultado = $this->sVerificacionCorrelatividad->DescargarArchivo($data);
		echo $this->json->json_response($resultado);
	}
}
