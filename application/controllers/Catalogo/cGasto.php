<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGasto extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sGasto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$Gasto =  $this->sGasto->Gasto;
		$Gastos = $this->sGasto->ListarGastos(1);

		$data = array("data" =>
					array(
						'Gastos' => $Gastos,
						'Gasto' => $Gasto

					)
		 );

		$view_data['data'] = $data;
		$view['view_footer_extension'] = $this->load->View('Catalogo/Gasto/view_mainpanel_footer_gasto',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/Gasto/view_mainpanel_content_gasto','',true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ListarGastos()
	{
		$resultado = $this->sGasto->ListarGastos();

		echo $this->json->json_response($resultado);
	}

	public function ObtenerGastoPorIdProducto()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sGasto->ObtenerGastoPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarGasto()
	{
		try {

			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sGasto->InsertarGasto($data);
			if (is_array($resultado)) {
				$output["resultado"] = $resultado;

				$resultado2 = $this->sGasto->InsertarJSONDesdeGasto($resultado);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/gasto/gastos.json');
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

	public function ActualizarGasto()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sGasto->ActualizarGasto($data);

			if ($resultado == "") {

				$resultado2 = $this->sGasto->ActualizarJSONDesdeGasto($data);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/gasto/gastos.json');
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

	public function BorrarGasto()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sGasto->BorrarGasto($data);

			if ($resultado == "") {

				$resultado2 = $this->sGasto->BorrarJSONDesdeGasto($data);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/gasto/gastos.json');
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
