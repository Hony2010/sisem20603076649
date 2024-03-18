<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiCatalogoGeneral {

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
		$this->CI->load->library('RestApi/Configuracion/Catalogo/RestApiFamiliaProducto');
		$this->CI->load->library('RestApi/Configuracion/Catalogo/RestApiSubFamiliaProducto');
		$this->CI->load->library('RestApi/Configuracion/Catalogo/RestApiLineaProducto');
		$this->CI->load->library('RestApi/Configuracion/Catalogo/RestApiMarca');
		$this->CI->load->library('RestApi/Configuracion/Catalogo/RestApiModelo');
		$this->CI->load->library('RestApi/Configuracion/Catalogo/RestApiFabricante');
	}
	
	//PARA EXPORTAR
	public function ConsultarCatalogoGeneralJSON($data)
	{
		$resultado["FamiliaProducto"] = $this->CI->restapifamiliaproducto->ConsultarFamiliasJSON($data);
		$resultado["SubFamiliaProducto"] = $this->CI->restapisubfamiliaproducto->ConsultarSubFamiliasJSON($data);
		$resultado["LineaProducto"] = $this->CI->restapilineaproducto->ConsultarLineasJSON($data);
		$resultado["Marca"] = $this->CI->restapimarca->ConsultarMarcasJSON($data);
		$resultado["Modelo"] = $this->CI->restapimodelo->ConsultarModelosJSON($data);
		$resultado["Fabricante"] = $this->CI->restapifabricante->ConsultarFabricantesJSON($data);
		return $resultado;
	}

}	
