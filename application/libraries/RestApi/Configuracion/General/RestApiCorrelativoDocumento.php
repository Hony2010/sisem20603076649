<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiCorrelativoDocumento {

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
		$this->CI->load->service('Configuracion/Venta/sCorrelativoDocumento');
		$this->CI->load->service('Configuracion/General/sConstanteSistema');
	}
	
	//PARA EXPORTAR
	public function CosultarCorrelativoDocumentoParaJSON($data)
	{
		$resultado = $this->CI->sCorrelativoDocumento->CosultarCorrelativoDocumentoParaJSON($data);
		return $resultado;
	}

	public function ListarCorrelativosDocumento() {
		$resultado = $this->CI->sCorrelativoDocumento->ListarCorrelativosDocumento();
		return $resultado;
	}

}	
