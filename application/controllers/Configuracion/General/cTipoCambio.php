<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTipoCambio extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sTipoCambio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper("date");
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('tipocambiosunat');
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->model("Base");
	}

	public function Index()
	{
		$TipoCambio =  $this->sTipoCambio->TipoCambio;
		$TiposCambio = $this->sTipoCambio->ListarTiposCambio();
		$EnteroValue = $this->sTipoCambio->ObtenerEnteroTipoCambio();
		$DecimalValue = $this->sTipoCambio->ObtenerDecimalTipoCambio();

		$data = array("data" =>
					array(
						'TiposCambio' => $TiposCambio,
						'TipoCambio' => $TipoCambio,
						'EnteroValue' => $EnteroValue,
						'DecimalValue' => $DecimalValue
					)
		 );


		$view_data['data'] = $data;
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
    $view['view_content'] =  $this->load->View('Configuracion/General/TipoCambio/view_mainpanel_content_tipocambio','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/TipoCambio/view_mainpanel_footer_tipocambio',$view_data,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarTiposCambio()
	{
		$input = $this->input->get("Data");
		$input['CopiaFiltro'] = convertToDate($input['textofiltro']);
		$numerofilasporpagina = $this->sTipoCambio->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sTipoCambio->ObtenerNumeroTotalTipoCambio($input);

		$output["resultado"] = $this->sTipoCambio->ConsultarTiposCambio($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}


	public function ListarTiposCambio()
	{
		$resultado = $this->sTipoCambio->ListarTiposCambio();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTipoCambio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoCambio->InsertarTipoCambio($data);

		if(is_array($resultado))
		{
			$data["IdTipoCambio"] = $resultado;
			$data["textofiltro"] = "%";
			$numerofilasporpagina = $this->sTipoCambio->ObtenerNumeroFilasPorPagina();
			$TotalFilas = $this->sTipoCambio->ObtenerNumeroTotalTipoCambio($data);
			$output["data"] = $data;
			$output["Filtros"] = array(
				"textofiltro" => "",
				"numerofilasporpagina" => $numerofilasporpagina	,
				"totalfilas" => $TotalFilas,
				"paginadefecto" => 2);

				echo $this->json->json_response($output);
		}
		else
		{
			$data["IdTipoCambio"] = $resultado;
			echo $this->json->json_response($data);
		}
	}

	public function ActualizarTipoCambio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoCambio->ActualizarTipoCambio($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarTipoCambio()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTipoCambio->BorrarTipoCambio($data);

		$data["textofiltro"] = "%";
		$numerofilasporpagina = $this->sTipoCambio->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sTipoCambio->ObtenerNumeroTotalTipoCambio($data);

		$output["msg"] = $resultado;
		$output["Filtros"] = array(
			"textofiltro" => "",
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1);

		echo $this->json->json_response($output);
	}

	public function ObtenerTipoCambio()
	{
		$data = $this->input->get("Data");
		$data["FechaCambio"] = convertToDate($data["FechaEmision"]);
		$resultado = $this->sTipoCambio->ObtenerTipoCambio($data);

		if ($resultado == null) {
			// $hoy = convertToDate($this->Base->ObtenerFechaServidor("d/m/Y"));
			
			// if($data["FechaCambio"] == $hoy)
			// {
				if($this->sConstanteSistema->ObtenerParametroTipoCambioActual() == 1)
				{
					$TipoCambioActual = $this->tipocambiosunat->ConsultarTipoCambio($data["FechaEmision"]);
					$TipoCambioActual["FechaCambio"] = $data["FechaCambio"];
					$insertar = $this->sTipoCambio->InsertarTipoCambio($TipoCambioActual);
					if ($insertar) {
						$resultado = $this->sTipoCambio->ObtenerTipoCambio($data);
					} else {
						$resultado = $TipoCambioActual;
					}
				}
			// }

		}
		echo $this->json->json_response($resultado);
	}

	public function ConsultarTiposCambioPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sTipoCambio->ConsultarTiposCambio($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarTipoCambioCompraActual()
	{
		$data = json_decode($this->input->post("Data"), true);
		$fechaEmision = convertToDate($data["FechaEmision"]);
		$hoy = convertToDate($this->Base->ObtenerFechaServidor("d/m/Y"));
		$response["TipoCambio"] = "";
		if($fechaEmision == $hoy)
		{
			if($this->sConstanteSistema->ObtenerParametroTipoCambioActual() == 1)
			{
				$TipoCambioActual = $this->tipocambiosunat->ConsultarTipoCambioCompra();
				$ValorTipoCambio = ($TipoCambioActual == "") ? "" : $TipoCambioActual;
				$response["TipoCambio"] = $ValorTipoCambio;
			}
		}
		echo $this->json->json_response($response);
	}

	public function ConsultarTipoCambioVentaActual()
	{
		$data = json_decode($this->input->post("Data"), true);
		$fechaEmision = convertToDate($data["FechaEmision"]);
		$hoy = convertToDate($this->Base->ObtenerFechaServidor("d/m/Y"));
		$response["TipoCambio"] = "";

		if($fechaEmision == $hoy)
		{
			if($this->sConstanteSistema->ObtenerParametroTipoCambioActual() == 1)
			{
				$TipoCambioActual = $this->tipocambiosunat->ConsultarTipoCambioVenta();
				$ValorTipoCambio = ($TipoCambioActual == "") ? "" : $TipoCambioActual;
				$response["TipoCambio"] = $ValorTipoCambio;
			}
		}

		echo $this->json->json_response($response);
	}

}
