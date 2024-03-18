<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cNotaDebitoCompra extends CI_Controller  {

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
		$this->load->service("Compra/sNotaDebitoCompra");
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->service("Venta/sDocumentoReferenciaCompra");
		$this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
		$this->load->service('FacturacionElectronica/sComprobanteElectronico');
		$this->load->service('Configuracion/General/sEmpresa');
		$this->load->service("Catalogo/sCliente");

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

		$output["resultado"] = $this->sComprobanteCompra->ConsultarComprobantesCompraPorProveedor($input);

		echo $this->json->json_response($output);
	}

	public function ConsultarDocumentosReferencia()
	{
		$data = $this->input->get("Data");
		$resultado= $this->sDocumentoReferenciaCompra->ConsultarDocumentosReferencia($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarNotaDebitoCompraPorIdProducto()
	{
		$data["IdProducto"] = $this->input->post("Data");
		$resultado = $this->sNotaDebitoCompra->ConsultarNotaDebitoCompraPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaNotaDebitoCompraPorNombreProducto($data)
	{
		$q =$data;// $this->input->post("Data");
		$data["textofiltro"] = $q;


		$resultado = $this->sNotaDebitoCompra->ConsultarSugerenciaNotaDebitoCompraPorNombreProducto($data, 1);

		echo $this->json->json_response($resultado);
	}

  public function ListarFamiliasProducto()
	{
		$resultado = $this->sFamiliaProducto->ListarFamiliasProducto();

		echo $this->json->json_response($resultado);
	}

	public function InsertarNotaDebitoCompra()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->InsertarCompraConCaja($data) : $this->sNotaDebitoCompra->InsertarNotaDebitoCompra($data);

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

	public function ActualizarNotaDebitoCompra()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaCompra->ActualizarCompraConCaja($data) : $this->sNotaDebitoCompra->ActualizarNotaDebitoCompra($data);
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

	public function BorrarNotaDebitoCompra()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sNotaDebitoCompra->BorrarNotaDebitoCompra($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerNotaDebitoCompraPorIdProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sNotaDebitoCompra->ObtenerNotaDebitoCompraPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerConceptosPorMotivo()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sConceptoNotaCreditoDebito->ObtenerConceptoPorMotivoNotaDebito($data);
		echo $this->json->json_response($resultado);
	}

}
