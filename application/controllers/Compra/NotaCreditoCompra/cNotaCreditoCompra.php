<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cNotaCreditoCompra extends CI_Controller  {

	public $ParametroCaja;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
    	$this->load->helper("date");
		$this->load->library('form_validation');
		$this->load->library('sesionusuario');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->service("Compra/sNotaCreditoCompra");
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->service("Venta/sDocumentoReferenciaCompra");
		$this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
		$this->load->service('FacturacionElectronica/sComprobanteElectronico');
		$this->load->service('Configuracion/General/sEmpresa');
		$this->load->service("Catalogo/sCliente");
		// $this->load->service("Configuracion/Compra/sMotivoNotaCreditoCompra");
		// $this->load->service("Configuracion/General/sSede");
		// $this->load->service("Inventario/sNotaEntrada");

		$this->ParametroCaja = $this->sesionusuario->obtener_sesion_parametro_caja();
		if($this->ParametroCaja == 1)
		{
			$this->load->service("Caja/sCajaCompra");
		}
	}

	public function Index()
	{

	}

	public function ConsultarComprobantesCompraPorProveedor()
	{
		$input = $this->input->get("Data");
		// $input["IdCliente"] = 3;
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);

		$output["resultado"] = $this->sComprobanteCompra->ConsultarComprobantesCompraPorProveedorParaNotaCredito($input);

		echo $this->json->json_response($output);
	}

	public function ConsultarDocumentosReferencia()
	{
		$data = $this->input->get("Data");
		$resultado= $this->sDocumentoReferenciaCompra->ConsultarDocumentosReferencia($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarNotaCreditoCompraPorIdProducto()
	{
		$data["IdProducto"] = $this->input->post("Data");
		$resultado = $this->sNotaCreditoCompra->ConsultarNotaCreditoCompraPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaNotaCreditoCompraPorNombreProducto($data)
	{
		$q =$data;// $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sNotaCreditoCompra->ConsultarSugerenciaNotaCreditoCompraPorNombreProducto($data, 1);

		echo $this->json->json_response($resultado);
	}

  public function ListarFamiliasProducto()
	{
		$resultado = $this->sFamiliaProducto->ListarFamiliasProducto();

		echo $this->json->json_response($resultado);
	}

	public function InsertarNotaCreditoCompra()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->InsertarCompraConCaja($data) : $this->sNotaCreditoCompra->InsertarNotaCreditoCompra($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesNotaCreditoCompra'], true);
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

	public function ActualizarNotaCreditoCompra()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->ActualizarCompraConCaja($data) : $this->sNotaCreditoCompra->ActualizarNotaCreditoCompra($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				if ($data['IdTipoCompra'] == ID_TIPOCOMPRA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['ListaIdsDetalle']);
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

	public function BorrarNotaCreditoCompra()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sNotaCreditoCompra->BorrarNotaCreditoCompra($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerNotaCreditoCompraPorIdProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sNotaCreditoCompra->ObtenerNotaCreditoCompraPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerConceptosPorMotivo()
	{
		$data = $this->input->post("Data");
		// print_r($data);
		// exit;
		$resultado = $this->sConceptoNotaCreditoDebito->ObtenerConceptoPorMotivoNotaCredito($data);
		echo $this->json->json_response($resultado);
	}



}
