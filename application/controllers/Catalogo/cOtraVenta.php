<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cOtraVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sOtraVenta");
		$this->load->service("Configuracion/Catalogo/sTipoProducto");
		$this->load->service("Configuracion/General/sTipoAfectacionIGV");
		$this->load->service("Configuracion/General/sTipoSistemaISC");
		$this->load->service("Configuracion/General/sTipoPrecio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{

		$OtraVenta =  $this->sOtraVenta->Inicializar();
		$OtrasVenta = $this->sOtraVenta->ListarOtrasVenta();
		$TiposProducto = $this->sTipoProducto->ListarTiposProducto();
		$TiposAfectacionIGV = $this->sTipoAfectacionIGV->ListarTiposAfectacionIGV();
		$TiposPrecio = $this->sTipoPrecio->ListarTiposPrecio();
		$TiposSistemaISC = $this->sTipoSistemaISC->ListarTiposSistemaISC();
		$OtraVenta['NombreTipoAfectacionIGV'] = "";
		$OtraVenta['NombreTipoProducto'] = "";
		$OtraVenta['CodigoTipoSistemaISC'] = "";
		$OtraVenta['CodigoTipoPrecio'] = "";
		$OtraVenta['CodigoTipoAfectacionIGV'] = "";
		$OtraVenta['IdTipoAfectacionIGV'] = ID_AFECTACION_IGV_GRAVADO;
		$OtraVenta['CodigoTipoSistemaISC'] = CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO;
		$OtraVenta['IdTipoSistemaISC'] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
		$OtraVenta['IdUnidadMedida'] = ID_UNIDAD_MEDIDA_UNIDAD;
		$data = array("data" =>
					array(
						'OtrasVenta' => $OtrasVenta,
						'OtraVenta' => $OtraVenta,
						'TiposProducto' => $TiposProducto,
						'TiposPrecio' => $TiposPrecio,
						'TiposAfectacionIGV' => $TiposAfectacionIGV
					)
		 );

		$view_data['data'] = $data;


		$view['view_footer_extension'] = $this->load->View('Catalogo/OtraVenta/view_mainpanel_footer_otraventa',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/OtraVenta/view_mainpanel_content_otraventa','',true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ListarOtrasVenta()
	{
		$resultado = $this->sOtraVenta->ListarOtrasVenta();

		echo $this->json->json_response($resultado);
	}

	public function InsertarOtraVenta()
	{
		try {

			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sOtraVenta->InsertarOtraVenta($data);

			if (is_array($resultado)) {
				$output["resultado"] = $resultado;

				$resultado2 = $this->sOtraVenta->InsertarJSONDesdeOtraVenta($resultado);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/otraventa/otrasventas.json');
					// $data["textofiltro"] = "%";
					// $numerofilasporpagina = $this->sMercaderia->ObtenerNumeroFilasPorPagina();
					// $TotalFilas = $this->sMercaderia->ObtenerNumeroTotalMercaderias($data);
					// $output["data"] = $data;
					// $output["Filtros"] = array(
					// 	"textofiltro" => "",
					// 	"numerofilasporpagina" => $numerofilasporpagina	,
					// 	"totalfilas" => $TotalFilas,
					// 	"paginadefecto" => 2);

						echo $this->json->json_response($output);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
				}
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

	public function ActualizarOtraVenta()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sOtraVenta->ActualizarOtraVenta($data);

			if ($resultado == "") {

				$resultado2 = $this->sOtraVenta->ActualizarJSONDesdeOtraVenta($data);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/otraventa/otrasventas.json');
					echo $this->json->json_response($resultado);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
				}
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

	public function BorrarOtraVenta()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sOtraVenta->BorrarOtraVenta($data);

			if ($resultado == "") {

				$resultado2 = $this->sOtraVenta->BorrarJSONDesdeOtraVenta($data);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/otraventa/otrasventas.json');
					// $data["textofiltro"] = "%";
					// $numerofilasporpagina = $this->sMercaderia->ObtenerNumeroFilasPorPagina();
					// $TotalFilas = $this->sMercaderia->ObtenerNumeroTotalMercaderias($data);
					$output["msg"] = $resultado;

					// $output["Filtros"] = array(
					// 	"textofiltro" => "",
					// 	"numerofilasporpagina" => $numerofilasporpagina	,
					// 	"totalfilas" => $TotalFilas,
					// 	"paginadefecto" => 2);

					echo $this->json->json_response($output);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
				}
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

	public function ObtenerOtraVentaPorIdProducto()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sOtraVenta->ObtenerOtraVentaPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}
}
