<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiListaPrecioMercaderia {

	public $CI;

	function __construct() {
		
		if (!isset($this->CI)) {
			$this->CI =& get_instance();
		}
		
		$this->CI->load->library('jsonconverter');
		$this->CI->load->library('restapi');
		$this->CI->load->service('Catalogo/sListaPrecioMercaderia');
	}

	public function ConsultarTodosListasPrecioMercaderia() {		
		$data["IdSede"]="%";
		$data["IdTipoListaPrecio"]="%";
		$data["IdSubFamiliaProducto"]="%";
		$data["IdModelo"]="%";
		$data["IdMarca"]="%";
		$data["IdFamiliaProducto"]="%";
		$data["IdLineaProducto"]="%";
		$data["Descripcion"]="%";
		
		$resultado = $this->CI->sListaPrecioMercaderia->ConsultarTodosListasPrecioMercaderia($data);

		return $resultado;
	}
}
