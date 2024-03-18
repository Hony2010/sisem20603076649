<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiModelo {

	public $CI;

	function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}

		$this->CI->load->library('jsonconverter');
		$this->CI->load->library('restapi');
		$this->CI->load->library('archivo');
		$this->CI->load->service('Configuracion/Catalogo/sModelo');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
	}
	
	//PARA EXPORTAR
	public function ConsultarModelosJSON($data)
	{
		$resultado = $this->CI->sModelo->ConsultarModelosJSON($data);
		return $resultado;
	}

}	
