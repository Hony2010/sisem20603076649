<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTransportista extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sTransportista");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
	}

	public function InsertarTransportista()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sTransportista->InsertarTransportista($data);
			if(is_array($resultado)) {
				$resultado["CodigoDocumentoIdentidad"] = $data["CodigoDocumentoIdentidad"];
				$output["resultado"] = $resultado;

				$resultado2 = $this->sTransportista->InsertarJSONDesdeTransportista($resultado);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/cliente/clientes.json');
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;

					$numerofilasporpagina = $this->sTransportista->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sTransportista->ObtenerNumeroTotalTransportistas($data);
					$output["data"] = $data;
					$output["Filtros"] = array(
						"textofiltro" => "",
						"numerofilasporpagina" => $numerofilasporpagina	,
						"totalfilas" => $TotalFilas,
						"paginadefecto" => 2);

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

	public function ActualizarTransportista()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sTransportista->ActualizarTransportista($data);
			
			if (is_array($resultado)) {
				$resultado2 = $this->sTransportista->ActualizarJSONDesdeTransportista($data);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/cliente/clientes.json');
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
	
	public function BorrarTransportista()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");

			$resultado = $this->sTransportista->BorrarTransportista($data);
			if ($resultado == "")
			{
				$resultado2 = $this->sTransportista->BorrarJSONDesdeTransportista($data);
				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/cliente/clientes.json');
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$numerofilasporpagina = $this->sTransportista->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sTransportista->ObtenerNumeroTotalTransportistas($data);
					$output["msg"] = $resultado;

					$output["Filtros"] = array(
						"textofiltro" => "",
						"numerofilasporpagina" => $numerofilasporpagina	,
						"totalfilas" => $TotalFilas,
						"paginadefecto" => 2);

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
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}

	public function SubirFoto()
	{
		try {
			$IdPersona = $this->input->post("IdPersona");
			$InputFileName = $this->input->post("InputFileName");

			$patcher = DIR_ROOT_ASSETS.'/img/Transportista/'.$IdPersona.'/';
			$config['upload_path'] = $patcher;

			$resultado = $this->shared->upload_file($InputFileName,$config);

			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	// public function ActualizarEmailTransportista()
	// {
	// 	try {
	// 		$this->db->trans_begin();

	// 		$data = $this->input->post("Data");
	// 		$resultado = $this->sTransportista->ActualizarEmailTransportista($data);

	// 		if ($resultado == "") {
	// 			$resultado2 = $this->sTransportista->ActualizarJSONDesdeTransportista($data);

	// 			if(is_array($resultado2)) {
	// 				$this->db->trans_commit();
	// 				// clearstatcache(true, BASE_PATH.'assets/data/cliente/clientes.json');
	// 				echo $this->json->json_response($resultado);
	// 			}
	// 			else {
	// 				$this->db->trans_rollback();
	// 				echo $this->json->json_response_error($resultado2);
	// 			}
	// 		}
	// 		else {
	// 			$this->db->trans_rollback();
	// 			echo $this->json->json_response_error($resultado);
	// 		}
	// 	}
	// 	catch (Exception $e) {
	// 		$this->db->trans_rollback();
	// 		echo $this->json->json_response_error($e);
	// 	}
	// }

	public function ObtenerTransportistaPorIdPersona()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sTransportista->ObtenerTransportistaPorIdPersona($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSunat()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTransportista->ConsultarSunat($data);
		echo $this->json->json_response($resultado);
	}
	public function ConsultarReniec()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sTransportista->ConsultarReniec($data);
		echo $this->json->json_response($resultado);
	}

	// public function InsertarTransportistaVenta()
	// {
	// 	$dataTransportista = $this->sTransportista->Cargar();
	// 	$data["NumeroDocumentoIdentidad"] = $this->input->post("Data");
	// 	$resultado = $this->sTransportista->ConsultarSunat($data);
	// 	if($resultado["success"]){
	// 		$resultado2 = array_merge($dataTransportista,$resultado["result"]);
	// 		$resultado2["IdTipoDocumentoIdentidad"] = ID_TIPO_DOCUMENTO_IDENTIDAD_RUC;
	// 		$resultado2["IdTipoPersona"] = $resultado2["TipoPersona"];
	// 		$resultado2["RepresentanteLegal"] = is_array($resultado2["RepresentanteLegal"]) ? "" : $resultado2["RepresentanteLegal"];
	// 		try {
	// 			$this->db->trans_begin();
	// 			$result = $this->sTransportista->InsertarTransportista($resultado2);
	// 			if(is_array($result)){
	// 				$result["CodigoDocumentoIdentidad"] = CODIGO_DOCUMENTO_IDENTIDAD_RUC;
	// 				$result["NombreGradoAlumno"] = $dataTransportista["NombreGradoAlumno"];
	// 				$resultado = $this->sTransportista->InsertarJSONDesdeTransportista($result);
	// 				if(is_array($resultado)) {
	// 					$this->db->trans_commit();
	// 					echo $this->json->json_response($resultado);
	// 				}
	// 				else {
	// 					$this->db->trans_rollback();
	// 					echo $this->json->json_response_error($resultado);
	// 				}
	// 			} else {
	// 				$this->db->trans_rollback();
	// 				echo $this->json->json_response_error($result);
	// 			}
	// 		} catch (Exception $e) {
	// 			$this->db->trans_rollback();
	// 			echo $this->json->json_response_error($e);
	// 		}
	// 	} else {
	// 		$resultado["message"] = $resultado["message"] . '<br>Debe Ingresar Manualmente en Catálogo.';
	// 		echo $this->json->json_response_error($resultado["message"]);
	// 	}
	// }
}
