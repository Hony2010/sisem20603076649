<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cSaldoInicialCuentaCobranza extends CI_Controller  {

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
		$this->load->service("CuentaCobranza/sSaldoInicialCuentaCobranza");
	}

	public function Index()
	{
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$input["TextoFiltro"] = '';
		$input["FechaInicio"] = date("Y-m-d", strtotime($fechaservidor));
		$input["FechaFin"] = $input["FechaInicio"];

		$input["pagina"] = 1;
		$input["numerofilasporpagina"] = $this->sSaldoInicialCuentaCobranza->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"] = 1;
		$input["totalfilas"] = $this->sSaldoInicialCuentaCobranza->ObtenerNumeroTotalSaldosInicialCuentaCobranza($input);
		$SaldoInicialCuentaCobranza =  $this->sSaldoInicialCuentaCobranza->CargarSaldoInicialCuentaCobranza(array());
		$SaldosInicialesCuentaCobranza = array();//$this->sSaldoInicialCuentaCobranza->ConsultarSaldosInicialCuentaCobranza($input,$input["pagina"],$input["numerofilasporpagina"]);
		$input["FechaInicio"] = date("d/m/Y", strtotime($fechaservidor));
		$input["FechaFin"] = $input["FechaInicio"];

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'SaldoInicialCuentaCobranza' => $SaldoInicialCuentaCobranza,
				'NuevoSaldoInicialCuentaCobranza' => $SaldoInicialCuentaCobranza,
				'SaldosInicialesCuentaCobranza' => $SaldosInicialesCuentaCobranza,
				'NuevoDetallesSaldoInicialCuentaCobranza' => $SaldoInicialCuentaCobranza["DetalleSaldoInicialCuentaCobranza"],
				'DetallesSaldoInicialCuentaCobranza' => array()
			)
		);

		$view_data['data'] = $data;

		$views['view_buscador_saldoinicialcuentacobranza'] = $this->load->View('CuentaCobranza/SaldoInicialCuentaCobranza/view_buscador_saldoinicialcuentacobranza','',true);
		$views['view_tabla_saldoinicialcuentacobranza'] = $this->load->View('CuentaCobranza/SaldoInicialCuentaCobranza/view_tabla_saldoinicialcuentacobranza','',true);
		$views['view_paginacion_saldoinicialcuentacobranza'] = $this->load->View('CuentaCobranza/SaldoInicialCuentaCobranza/view_paginacion_saldoinicialcuentacobranza',"",true);
		$views['view_modal_saldoinicialcuentacobranza'] = $this->load->View('CuentaCobranza/SaldoInicialCuentaCobranza/view_modal_saldoinicialcuentacobranza',"",true);

		$view_footer['view_footer_extension'] = $this->load->View('CuentaCobranza/SaldoInicialCuentaCobranza/view_footer_saldoinicialcuentacobranza',$view_data,true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('CuentaCobranza/SaldoInicialCuentaCobranza/view_content_saldoinicialcuentacobranza',$views,true);

		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);
		$this->load->View('.Master/master_view_mainpanel',$view);

	}

	public function ConsultarSaldosInicialCuentaCobranza()
	{
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sSaldoInicialCuentaCobranza->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sSaldoInicialCuentaCobranza->ObtenerNumeroTotalSaldosInicialCuentaCobranza($input);
		$output["resultado"] = $this->sSaldoInicialCuentaCobranza->ConsultarSaldosInicialCuentaCobranza($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarSaldosInicialCuentaCobranzaPorPagina()
	{
		$input = json_decode($this->input->post("Data"), true);
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$input["FechaFin"]=convertToDate($input["FechaFin"]);
		$resultado = $this->sSaldoInicialCuentaCobranza->ConsultarSaldosInicialCuentaCobranza($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function InsertarSaldoInicialCuentaCobranza()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sSaldoInicialCuentaCobranza->InsertarSaldoInicialCuentaCobranza($data);//$this->sSaldoInicialCuentaCobranza->InsertarSaldoInicialCuentaCobranza($data);
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

	public function ActualizarSaldoInicialCuentaCobranza()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sSaldoInicialCuentaCobranza->ActualizarSaldoInicialCuentaCobranza($data);
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

	public function BorrarSaldoInicialCuentaCobranza()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sSaldoInicialCuentaCobranza->BorrarSaldoInicialCuentaCobranza($data);
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
