<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiAsignacionCuotaMensual {

	public $CI;

	function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}

		$this->CI->load->service('Venta/sAsignacionCuotaMensual');
	}

	function ConsultarAsignacionesCuotaMensual($data)
	{
		$resultado = $this->CI->sAsignacionCuotaMensual->ConsultarAsignacionesCuotaMensual($data);
		return $resultado;
	}

	function AgregarAsignacionesCuotaMensual($data)
	{
		$resultado = $this->CI->sAsignacionCuotaMensual->AgregarAsignacionesCuotaMensual($data);
		return $resultado;
	}

	function ConsultarAsignacionesCuotaMensualParaReporte($data)
	{
		$resultado = $this->CI->sAsignacionCuotaMensual->ConsultarAsignacionesCuotaMensualParaReporte($data);
		return $resultado;
	}
}
