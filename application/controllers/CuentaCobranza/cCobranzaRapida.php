<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCobranzaRapida extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->model("Base");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("CuentaCobranza/sCobranzaCliente");	
		$this->load->service("Venta/sComprobanteVenta");

	}

	public function Index()
	{
		$CobranzaCliente =  $this->sCobranzaCliente->Cargar();
		$fechaservidor = date("d/m/Y", strtotime($this->Base->ObtenerFechaServidor("Y-m-d")));

		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		
		$ComprobanteVenta = $this->sComprobanteVenta->Cargar($parametro);
		$ComprobanteVenta["NombreCaja"] = "";

		$filtros = [
			'FechaInicio' => $fechaservidor,
			'FechaFin' => $fechaservidor,
			'IdPersona' => '',
			'IdRol' => '2',
			'IndicadorPermisoCobranzaRapida' => $ComprobanteVenta["IndicadorPermisoCobranzaRapida"]
		];

		$data = array(
			"data" => array(
				'Filtros' => $filtros,
				'CobranzaRapida' => $CobranzaCliente,
				'CobranzasCliente' => array(),
				'ComprovanteVenta' => $ComprobanteVenta,
			)
		);

		$view_data['data'] = $data;

		$views['view_panel_cobranzarapida'] = $this->load->View('CuentaCobranza/CobranzaRapida/view_panel_cobranzarapida','',true);
		$views['view_modal_vistacomprobante'] = $this->load->View('Venta/ComprobanteVenta/view_modal_vistacomprobante','',true);
		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/CobranzaRapida/view_footer_cobranzarapida',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/CobranzaRapida/view_content_cobranzarapida',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	//CONSULTA COBRANZA
	public function ObtenerPendienteCobranzaClientePorIdComprobanteVenta()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarCobranzaRapida() {
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$error = "";
			foreach ($data["CobranzasCliente"] as $key => $value) {
				$resultado = $this->sCobranzaCliente->InsertarCobranzaCliente($value);
				if(!is_array($resultado)) {
					$error .= $resultado.PHP_EOL;
				}
				else {
					$data["CobranzasCliente"][$key] = $resultado;
				}
			}

			if($error == "") {
				$this->db->trans_commit();
				echo $this->json->json_response($data);
			}
			else {
			 	$this->db->trans_rollback();
				echo $this->json->json_response_error($error);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ConsultarComprobantesVentaClientesConDeudaPorVendedor() {
		$data = json_decode($this->input->post("Data"),true);		
		$data["FechaInicio"]=convertToDate($data["FechaInicio"]);
		$data["FechaFin"]=convertToDate($data["FechaFin"]);
		$resultado = $this->sPendienteCobranzaCliente->ConsultarComprobantesVentaPendientesCobranzaClientePorVendedor($data);
		echo $this->json->json_response($resultado);
	}
}
