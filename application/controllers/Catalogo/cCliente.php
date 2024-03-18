<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCliente extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sCliente");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
	}

	public function InsertarCliente()
	{
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sCliente->InsertarCliente($data);
			
			if(is_array($resultado)) {
				$resultado["CodigoDocumentoIdentidad"] = $data["CodigoDocumentoIdentidad"];
				$resultado["NombreGradoAlumno"] = $data["NombreGradoAlumno"];
				$resultado["IndicadorAfiliacionTarjeta"] = $data["IndicadorAfiliacionTarjeta"];
				$resultado["FechaInicioAfiliacionTarjeta"] = $data["FechaInicioAfiliacionTarjeta"];
				$resultado["FechaFinAfiliacionTarjeta"] = $data["FechaFinAfiliacionTarjeta"];
				$output["resultado"] = $resultado;

				$resultado2 = $this->sCliente->InsertarJSONDesdeCliente($resultado);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/cliente/clientes.json');
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;

					$numerofilasporpagina = $this->sCliente->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sCliente->ObtenerNumeroTotalClientes($data);
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

	public function ActualizarCliente()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sCliente->ActualizarCliente($data);
			
			if (is_array($resultado)) {				
				$resultado2 = $this->sCliente->ActualizarJSONDesdeCliente($data);

				if(is_array($resultado2)) {
					$this->db->trans_commit();					
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

	public function ActualizarEmailCliente()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sCliente->ActualizarEmailCliente($data);

			if ($resultado == "") {
				$resultado2 = $this->sCliente->ActualizarJSONDesdeCliente($data);

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

	public function BorrarCliente()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");

			$resultado = $this->sCliente->BorrarCliente($data);

			if ($resultado == "")
			{
				$resultado2 = $this->sCliente->BorrarJSONDesdeCliente($data);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/cliente/clientes.json');
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$numerofilasporpagina = $this->sCliente->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sCliente->ObtenerNumeroTotalClientes($data);
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

			$patcher = DIR_ROOT_ASSETS.'/img/Cliente/'.$IdPersona.'/';
			$config['upload_path'] = $patcher;

			$resultado = $this->shared->upload_file($InputFileName,$config);

			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function ObtenerClientePorIdPersona()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sCliente->ObtenerClientePorIdPersona($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSunat()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCliente->ConsultarSunat($data);
		echo $this->json->json_response($resultado);
	}
	public function ConsultarReniec()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sCliente->ConsultarReniec($data);
		echo $this->json->json_response($resultado);
	}

	public function InsertarClienteVenta()
	{
		$dataCliente = $this->sCliente->Cargar();
		$data["NumeroDocumentoIdentidad"] = $this->input->post("Data");
		$resultado = $this->sCliente->ConsultarSunat($data);
		if($resultado["success"]){
			$resultado2 = array_merge($dataCliente,$resultado["result"]);
			$resultado2["IdTipoDocumentoIdentidad"] = ID_TIPO_DOCUMENTO_IDENTIDAD_RUC;
			$resultado2["IdTipoPersona"] = $resultado2["TipoPersona"];
			$resultado2["RepresentanteLegal"] = is_array($resultado2["RepresentanteLegal"]) ? "" : $resultado2["RepresentanteLegal"];
			try {
				$this->db->trans_begin();
				$result = $this->sCliente->InsertarCliente($resultado2);
				if(is_array($result)){
					$result["CodigoDocumentoIdentidad"] = CODIGO_DOCUMENTO_IDENTIDAD_RUC;
					$result["NombreGradoAlumno"] = $dataCliente["NombreGradoAlumno"];
					$resultado = $this->sCliente->InsertarJSONDesdeCliente($result);
					if(is_array($resultado)) {
						$this->db->trans_commit();
						echo $this->json->json_response($resultado);
					}
					else {
						$this->db->trans_rollback();
						echo $this->json->json_response_error($resultado);
					}
				} else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($result);
				}
			} catch (Exception $e) {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($e);
			}
		} else {
			$resultado["message"] = $resultado["message"] . '<br>Debe Ingresar Manualmente en Catálogo.';
			echo $this->json->json_response_error($resultado["message"]);
		}
	}

	public function ObtenerDetalleCliente() {
		$data = $this->input->get("Data");
		$resultado = $this->sCliente->ObtenerDetalleCliente($data);
		echo $this->json->json_response($resultado);
	}
}
