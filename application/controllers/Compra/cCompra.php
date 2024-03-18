<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCompra extends CI_Controller  {

	public $ParametroCaja;

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sProductoProveedor");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');

		$this->ParametroCaja = $this->sesionusuario->obtener_sesion_parametro_caja();
		if($this->ParametroCaja == 1)
		{
			$this->load->service("Caja/sCajaCompra");
		}
		else
		{
			$this->load->service("Compra/sCompra");
		}
	}

	public function Index()
	{

	}

	public function InsertarCompra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->InsertarCompraConCaja($data) : $this->sCompra->InsertarCompra($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();

				if ($data["ParametroCodigoProductoProveedor"] == 1) {
					$resultado2 = $this->sProductoProveedor->InsertarProductoProveedor($data);
				}

				if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesComprobanteCompra'], true, true);
				}

				echo $this->json->json_response($resultado);
			}
			else {
			 	$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ActualizarCompra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->ActualizarCompraConCaja($data) : $this->sCompra->ActualizarCompra($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				
				if ($data["ParametroCodigoProductoProveedor"] == 1) {
					$resultado2 = $this->sProductoProveedor->InsertarProductoProveedor($data);
				}

				if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle'], false, true);
				}

				echo $this->json->json_response($resultado);
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ActualizarCompraAlternativo()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sCompra->ActualizarCompraAlternativo($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function BorrarCompra()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->EliminarCompraConCaja($data) : $this->sCompra->EliminarCompra($data);
			$response["error"] = $resultado;
			if(is_array($resultado)) {
				$this->db->trans_commit();
				$input = $data["Filtros"];
				$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
				$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
				$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;
				$data["Filtros"]["totalfilas"] = $this->sComprobanteCompra->ObtenerNumeroTotalComprobantesCompra($input);
				$resultados["resultado"] = $data;
				$resultados["error"] = "";

				if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteCompra'], true);
				}

				echo $this->json->json_response($resultados);
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response($response);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}

	}

	public function InsertarCompraMasiva()
	{
		try {
			$this->db->trans_begin();
			$data = $_POST["Data"];

			$data1 = json_decode($data, true);
			$numero_item = count($data1) - 1;
			$i = 0;
			$validaciones = "";
			$texto = false;
			foreach ($data1 as $key => $value) {

				$value["IdComprobanteCompra"] = '';
				$resultado = $this->sCompra->InsertarCompra($value);

				if(!is_array($resultado))
				{
					$validaciones .= $value["SerieDocumento"].'-'.$value["NumeroDocumento"]."<br/>".$resultado;
					$texto = true;
				}
				$i++;
			}

			if($texto == false)
			{
				// $this->db->trans_rollback();
				$this->db->trans_commit();
				$resultado = "Éxito.";
				echo $this->json->json_response($resultado);
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($validaciones);
			}

		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

}
