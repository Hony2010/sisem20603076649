<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGeneracionJSON extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('jsonconverter');
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->library('RestApi/Caja/RestApiPendienteCobranzaCliente');
		$this->load->library('RestApi/Venta/RestApiComprobanteVenta');
		$this->load->service('Catalogo/sServicio');
		$this->load->service('Catalogo/sActivoFijo');
		$this->load->service('Catalogo/sOtraVenta');
		$this->load->service('Catalogo/sCostoAgregado');
		$this->load->service('Catalogo/sGasto');
		$this->load->service('Catalogo/sCliente');
		$this->load->service('Catalogo/sProveedor');
		$this->load->service('Catalogo/sEmpleado');
		$this->load->service('Catalogo/sTransportista');
		$this->load->service('Catalogo/sVehiculo');
		$this->load->service('Catalogo/sRadioTaxi');
	}

	public function Index()
	{
		$GeneracionJSON =  array();
		$TiposDocumentoIdentidad = array();

		$data = array("data" =>
					array(
						'TiposDocumentoIdentidad' => $TiposDocumentoIdentidad,
						'GeneracionJSON' => $GeneracionJSON
					)
		 );

		$view_data['data'] = $data;
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Configuracion/Catalogo/GeneracionJSON/view_mainpanel_content_generacionjson','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/GeneracionJSON/view_mainpanel_footer_generacionjson',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function GenerarJSONMercaderia($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->restapimercaderia->CrearJSONMercaderiaTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONServicio($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sServicio->CrearJSONServicioTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONActivoFijo($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sActivoFijo->CrearJSONActivoFijoTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONOtraVenta($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sOtraVenta->CrearJSONOtraVentaTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONCostoAgregado($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sCostoAgregado->CrearJSONCostoAgregadoTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONGasto($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sGasto->CrearJSONGastoTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONCliente($todos = false)
	{
		try {
			$data = array();
			$resultado = $this->sCliente->CrearJSONClienteTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONProveedor($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sProveedor->CrearJSONProveedorTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONEmpleado($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sEmpleado->CrearJSONEmpleadoTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONTransportista($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sTransportista->CrearJSONTransportistaTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONPendientesCobranzaCliente($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->restapipendientecobranzacliente->CrearJSONPendienteCobranzaClienteTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONComprobantesVenta($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->restapicomprobanteventa->CrearJSONComprobanteVentaTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONVehiculos($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sVehiculo->CrearJSONVehiculoTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONRadioTaxis($todos = false)
	{
		try {
			$data = array();

			$resultado = $this->sRadioTaxi->CrearJSONRadioTaxiTodos();

			if($todos == true)
			{
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONProformas($todos = false) {
		try {
			$data = array();

			$resultado = $this->restapicomprobanteventa->CrearJSONProformasTodos();

			if($todos == true) {
				return $resultado;
			}

			if(is_array($resultado))
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}


	public function GenerarJSONTodos()
	{
		try {
			$resultado = array();

			$this->GenerarJSONMercaderia(true);
			$this->GenerarJSONServicio(true);
			$this->GenerarJSONActivoFijo(true);
			$this->GenerarJSONOtraVenta(true);
			$this->GenerarJSONCostoAgregado(true);
			$this->GenerarJSONGasto(true);
			$this->GenerarJSONCliente(true);
			$this->GenerarJSONProveedor(true);
			$this->GenerarJSONEmpleado(true);
			$this->GenerarJSONTransportista(true);
			$this->GenerarJSONPendientesCobranzaCliente(true);
			$this->GenerarJSONComprobantesVenta(true);
			$this->GenerarJSONProformas(true);
			$this->GenerarJSONVehiculos(true);
			$this->GenerarJSONRadioTaxis(true);
			$this->GenerarJSONUsuario(true);
			
			$resultado["Response"] = "Exitoso";
			if($resultado)
			{
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONUsuario($todos = false) {
		try {
			$data = array();

			$resultado = $this->sUsuario->CrearJSONUsuariosTodos();

			if($todos == true) {
				return $resultado;
			}

			if(is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function GenerarJSONProducto() {
		try {
			
			$data = $this->input->post("Data");
			$resultado = $this->restapimercaderia->CrearJSONProducto($data);
			
			if(is_array($resultado)) {
				echo $this->json->json_response($resultado);
			}
			else {
				echo $this->json->json_response_error($resultado);
			}

		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

}
