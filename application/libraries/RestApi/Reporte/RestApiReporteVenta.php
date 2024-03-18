<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiReporteVenta {

	public $CI;

	function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}
		
		$this->CI->load->helper("date");
		$this->CI->load->library('archivo');
		$this->CI->load->library('json');
		$this->CI->load->model("Venta/mComprobanteVenta");
		// $this->CI->load->service("Seguridad/sUsuario");
	}

	public function ReporteResumenVentasPorSerie($data)
	{
		$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
		$data["FechaFin"] = convertToDate($data["FechaFin"]);
		$resultado = $this->CI->mComprobanteVenta->ConsultarReporteResumenVentasPorSerie($data);
		return $resultado;
	}

}
