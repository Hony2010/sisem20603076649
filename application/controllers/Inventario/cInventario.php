<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cInventario extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->service("Inventario/sInventario");       
		$this->load->service("Inventario/sMigracionInventario");       		
	}

		
	public function ListarInventarioMercaderiasPorSede() {
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sInventario->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sInventario->ObtenerTotalInventarioMercaderiasPorSede($input);
		$output["resultado"] = $this->sInventario->ListarInventarioMercaderiasPorSede($input, $input["pagina"], $numerofilasporpagina);
		$output["Filtros"] = array_merge(
			$input,
			array(
				"numerofilasporpagina" => $numerofilasporpagina,
				"totalfilas" => $TotalFilas,
				"paginadefecto" => 1
			)
		);

		echo $this->json->json_response($output);
	}

	public function ListarInventarioMercaderiasPorSedePorPagina() {
		$input = $this->input->get("Data");		
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sInventario->ListarInventarioMercaderiasPorSede($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	function MigrarInventario() {
		try {
			//$this->db->trans_begin();
			$validaciones = "";
			$texto = false;
			//foreach ($data as $key => $value) {
			$resultado = $this->sMigracionInventario->MigrarInventario();
				// if(!is_numeric($resultado))
				// {
				// 	$validaciones .= "<br/>".$resultado;
				// 	$texto = true;
				// }
			//}

			// if($texto == false)
			// {
			// 	$this->db->trans_commit();
			// 	return $data;
			// }
			// else {
			//$this->db->trans_rollback();
			echo $this->json->json_response($resultado);
			//return $resultado;
			//}
		} catch (Exception $e) {
			//$this->db->trans_rollback();
			return $e;
		}
	}
}
