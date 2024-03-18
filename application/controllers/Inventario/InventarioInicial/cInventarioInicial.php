<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cInventarioInicial extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Inventario/sInventarioInicial");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
    	$this->load->service("Configuracion/General/sUnidadMedida");
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
	}

	public function Index() {
		$InventarioInicial =$this->sInventarioInicial->Cargar();

		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();

		$data = array(
			"data" => array(
				'InventarioInicial' => $InventarioInicial,
				'NuevoInventarioInicial' => $InventarioInicial,
				'UnidadesMedida' =>$UnidadesMedida
				)
		);

    	$view_data['data'] = $data;
		$view_form['view_form_inventarioinicial'] = $this->load->View('Inventario/InventarioInicial/view_form_inventarioinicial','',true);
		$view_form['view_panel_header_inventarioinicial'] = $this->load->View('Inventario/InventarioInicial/view_panel_header_inventarioinicial','',true);
		$views['view_panel_inventarioinicial'] = $this->load->View('Inventario/InventarioInicial/view_panel_inventarioinicial',$view_form,true);
		$view_footer['view_footer_extension'] = $this->load->View('Inventario/InventarioInicial/view_footer_inventarioinicial',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Inventario/InventarioInicial/view_mainpanel_content_inventarioinicial',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function InsertarInventarioInicial()
	{
		try {
			$this->db->trans_begin();

			// $data = $this->input->post("Data");
			$data_post = $_POST["Data"];
			$data = json_decode($data_post, true);
			$data = $data["Data"];
			$resultado = $this->sInventarioInicial->InsertarInventarioInicial($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				$resultado2 = $this->restapimercaderia->ActualizarProductosJSON($resultado["ProductosAntiguos"]);
				$resultado3 = $this->restapimercaderia->ReemplazarFilasJSONNuevosProductos($resultado["ProductosNuevos"]);

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

	public function InsertarInventario()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $data;
			// $resultado = $this->sInventarioInicial->InsertarMercaderiaInventarioInicial($data);
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

	public function ActualizarInventario() {
		try {
			$this->db->trans_begin();

			// $data = $this->input->post("Data");
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sInventarioInicial->ActualizarInventarioInicialLista($data);

			if ($resultado == "") {
					$this->db->trans_commit();
					$resultado2 = $this->restapimercaderia->ActualizarProductoJSONDesdeInventario($data);
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

	public function BorrarInventario() {
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$sede = $this->input->post("Sede");
			$resultado = $this->sInventarioInicial->BorrarInventarioInicialLista($data);

			if ($resultado == "") {
				$this->db->trans_commit();
				$resultado2 = $this->restapimercaderia->ActualizarProductoJSONDesdeInventario($data);
				$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
				$data["IdAsignacionSede"] = (trim($sede) == "") ? "%" : $sede;
				$numerofilasporpagina = $this->sInventarioInicial->ObtenerNumeroFilasPorPagina();
				$TotalFilas = $this->sInventarioInicial->ObtenerNumeroTotalInventariosInicial($data);
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
				echo $this->json->json_response_error($resultado);
			}

		}
		catch (Exception $e) {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($e);
		}
	}

	public function ValidarUnidadMedida()
	{
		$data = $this->input->post("Data");
		$data["AbreviaturaUnidadMedida"] = "HOLA";
		$resultado = $this->sUnidadMedida->ValidarAbreviaturaUnidadMedidaParaInsertar($data);
		$response["resultado"] = $resultado;
		if($resultado=="")
		{
			$response["error"] = "No se encontraron coincidencias.";
			echo $this->json->json_response($response);
		}
		else {
			echo $this->json->json_response($response);
		}
	}

	public function InsertarMercaderiaInventarioInicial()
	{
		try {
			$this->db->trans_begin();

			// $data = $this->input->post("Data");
			// $data = $_POST["Data"];
			$data = json_decode($this->input->post("Data"), true);
			$filtro = $this->input->post("Filtro");
			$sede = $this->input->post("Sede");

			$resultado = $this->sInventarioInicial->InsertarMercaderiaInventarioInicial($data);

			if(is_array($resultado)) {
				$this->db->trans_commit();
				$resultado2 = $this->restapimercaderia->ActualizarProductoJSONDesdeInventario($data);

				if($resultado2)
				{
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$data["IdAsignacionSede"] = (trim($sede) == "") ? "%" : $sede;
					$numerofilasporpagina = $this->sInventarioInicial->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sInventarioInicial->ObtenerNumeroTotalInventariosInicial($data);
					$output["msg"] = "";
					$resultado["FechaInicial"] = convertirFechaES($resultado["FechaInicial"]);

					if(array_key_exists("FechaEmision", $resultado))
					{
						$resultado["FechaEmision"] = ($resultado["FechaEmision"] != "") ? convertirFechaES($resultado["FechaEmision"]) : "";
					}
					$resultado["FechaEmisionDocumentoSalidaZofra"] = ($resultado["FechaEmisionDocumentoSalidaZofra"] != "") ? convertirFechaES($resultado["FechaEmisionDocumentoSalidaZofra"]) : "";
					$resultado["FechaEmisionDua"] = ($resultado["FechaEmisionDua"] != "") ? convertirFechaES($resultado["FechaEmisionDua"]) : "";

					$output["objeto"] = $resultado;

					$output["Filtros"] = array(
						"textofiltro" => "",
						"numerofilasporpagina" => $numerofilasporpagina	,
						"totalfilas" => $TotalFilas,
						"paginadefecto" => 2);

					echo $this->json->json_response($output);
				}
				else {
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

	public function ConsultarInventarioInicialPorIdProductoSede() {
		$data = $this->input->get("Data");		
		$resultado = $this->sInventarioInicial->ConsultarInventarioInicialPorIdProductoSede($data);		
		echo $this->json->json_response($resultado);
	}

}
