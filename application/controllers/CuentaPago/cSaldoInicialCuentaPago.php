<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cSaldoInicialCuentaPago extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->service("CuentaPago/sSaldoInicialCuentaPago");
	}

	public function Index()
	{
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["TextoFiltro"] = '';
		$input["FechaInicio"] = date("Y-m-d", strtotime($fechaservidor));
		$input["FechaFin"] = $input["FechaInicio"];

		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sSaldoInicialCuentaPago->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sSaldoInicialCuentaPago->ObtenerNumeroTotalSaldosInicialCuentaPago($input);
		$SaldoInicialCuentaPago =  $this->sSaldoInicialCuentaPago->CargarSaldoInicialCuentaPago(array());
		$SaldosInicialesCuentaPago = array();//$this->sSaldoInicialCuentaPago->ConsultarSaldosInicialCuentaPago($input,$input["pagina"],$input["numerofilasporpagina"]);
		$input["FechaInicio"] = date("d/m/Y", strtotime($fechaservidor));
		$input["FechaFin"] = $input["FechaInicio"];

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'SaldoInicialCuentaPago' => $SaldoInicialCuentaPago,
				'NuevoSaldoInicialCuentaPago' => $SaldoInicialCuentaPago,
				'SaldosInicialesCuentaPago' => $SaldosInicialesCuentaPago,
				'NuevoDetallesSaldoInicialCuentaPago' => $SaldoInicialCuentaPago["DetalleSaldoInicialCuentaPago"],
				'DetallesSaldoInicialCuentaPago' => array()
			)
		);

		$view_data['data'] = $data;

		$views['view_buscador_saldoinicialcuentapago'] = $this->load->View('CuentaPago/SaldoInicialCuentaPago/view_buscador_saldoinicialcuentapago','',true);
		$views['view_tabla_saldoinicialcuentapago'] = $this->load->View('CuentaPago/SaldoInicialCuentaPago/view_tabla_saldoinicialcuentapago','',true);
		$views['view_paginacion_saldoinicialcuentapago'] = $this->load->View('CuentaPago/SaldoInicialCuentaPago/view_paginacion_saldoinicialcuentapago',"",true);
		$views['view_modal_saldoinicialcuentapago'] = $this->load->View('CuentaPago/SaldoInicialCuentaPago/view_modal_saldoinicialcuentapago',"",true);

		$view_footer['view_footer_extension'] = $this->load->View('CuentaPago/SaldoInicialCuentaPago/view_footer_saldoinicialcuentapago',$view_data,true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaPago/SaldoInicialCuentaPago/view_content_saldoinicialcuentapago',$views,true);

		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);
		$this->load->View('.Master/master_view_mainpanel',$view);

	}

	public function ConsultarSaldosInicialCuentaPago()
	{
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sSaldoInicialCuentaPago->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sSaldoInicialCuentaPago->ObtenerNumeroTotalSaldosInicialCuentaPago($input);
		$output["resultado"] = $this->sSaldoInicialCuentaPago->ConsultarSaldosInicialCuentaPago($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarSaldosInicialCuentaPagoPorPagina()
	{
		$input = json_decode($this->input->post("Data"), true);
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$resultado = $this->sSaldoInicialCuentaPago->ConsultarSaldosInicialCuentaPago($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function InsertarSaldoInicialCuentaPago()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sSaldoInicialCuentaPago->InsertarSaldoInicialCuentaPago($data);//$this->sSaldoInicialCuentaPago->InsertarSaldoInicialCuentaPago($data);
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

	public function ActualizarSaldoInicialCuentaPago()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sSaldoInicialCuentaPago->ActualizarSaldoInicialCuentaPago($data);
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

	public function BorrarSaldoInicialCuentaPago()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sSaldoInicialCuentaPago->BorrarSaldoInicialCuentaPago($data);
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
}
