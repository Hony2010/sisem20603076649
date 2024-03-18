<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCostoAgregado extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sCostoAgregado");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{

		$CostoAgregado =  $this->sCostoAgregado->CostoAgregado;
		$CostosAgregado = $this->sCostoAgregado->ListarCostosAgregado(1);

		$data = array("data" =>
					array(
						'CostosAgregado' => $CostosAgregado,
						'CostoAgregado' => $CostoAgregado

					)
		 );

		$view_data['data'] = $data;
		$view['view_footer_extension'] = $this->load->View('Catalogo/CostoAgregado/view_mainpanel_footer_costoagregado',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/CostoAgregado/view_mainpanel_content_costoagregado','',true);
		$this->load->View('.Master/master_view_mainpanel_min',$view);

	}

	public function ObtenerCostoAgregadoPorIdProducto()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sCostoAgregado->ObtenerCostoAgregadoPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ListarCostosAgregado()
	{
		$resultado = $this->sCostoAgregado->ListarCostosAgregado();

		echo $this->json->json_response($resultado);
	}

	public function InsertarCostoAgregado()
	{
		try {

			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sCostoAgregado->InsertarCostoAgregado($data);

			if (is_array($resultado)) {
				$output["resultado"] = $resultado;
				$resultado2 = $this->sCostoAgregado->InsertarJSONDesdeCostoAgregado($resultado);


				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/costoagregado/costosagregado.json');
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

	public function ActualizarCostoAgregado()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sCostoAgregado->ActualizarCostoAgregado($data);

			if ($resultado == "") {
				$resultado2 = $this->sCostoAgregado->ActualizarJSONDesdeCostoAgregado($data);


				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/costoagregado/costosagregado.json');
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

	public function BorrarCostoAgregado()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sCostoAgregado->BorrarCostoAgregado($data);

			if ($resultado == "") {
				$resultado2 = $this->sCostoAgregado->BorrarJSONDesdeCostoAgregado($data);


				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/costoagregado/costosagregado.json');
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

}
