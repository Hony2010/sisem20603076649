<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cActivoFijo extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sActivoFijo");
    	$this->load->service("Configuracion/Catalogo/sMarca");
    	$this->load->service("Configuracion/Catalogo/sModelo");
    	$this->load->service("Configuracion/Catalogo/sTipoActivo");
		$this->load->service("Configuracion/General/sTipoAfectacionIGV");
		$this->load->service("Configuracion/General/sTipoPrecio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
    	$ActivoFijo =  $this->sActivoFijo->Inicializar();
		$ActivosFijo = $this->sActivoFijo->ListarActivosFijo(1);
    	$Marcas = $this->sMarca->ListarMarcas();
    	$Modelos = $this->sModelo->ListarTodosModelos();
		$TiposActivo = $this->sTipoActivo->ListarTiposActivo();

		$TiposAfectacionIGV = $this->sTipoAfectacionIGV->ListarTiposAfectacionIGV();
		$TiposPrecio = $this->sTipoPrecio->ListarTiposPrecio();

		$data = array("data" =>
			array(
				'ActivoFijo'=>$ActivoFijo,
				'NuevoActivoFijo'=>$ActivoFijo,
				'ActivosFijo'=>$ActivosFijo,
				'Marcas'=>$Marcas,
				'Modelos'=>$Modelos,
				'TiposActivo' =>$TiposActivo,
				'TiposPrecio'=>$TiposPrecio,
				'TiposAfectacionIGV'=>$TiposAfectacionIGV
			)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_activofijo']=   $this->load->View('Catalogo/ActivoFijo/view_mainpanel_subcontent_buscador_activofijo','',true);
		$view_subcontent['view_subcontent_preview_activofijo'] =  $this->load->View('Catalogo/ActivoFijo/view_mainpanel_subcontent_preview_activofijo','',true);
		$view_subcontent['view_subcontent_consulta_activofijos'] =  $this->load->View('Catalogo/ActivoFijo/view_mainpanel_subcontent_consulta_activofijos',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_activofijo'] =  $this->load->View('Catalogo/ActivoFijo/view_mainpanel_subcontent_form_activofijo','',true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/ActivoFijo/view_mainpanel_footer_activofijo',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/ActivoFijo/view_mainpanel_content_activofijo',$view_subcontent,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function Sugerencias()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sActivoFijo->ConsultarActivoFijo($data, 1);

		echo $this->json->json_response($resultado);
	}

  public function ListarMarcas()
	{
		$resultado = $this->sMarcas->ListarMarcas();

		echo $this->json->json_response($resultado);
	}

	public function InsertarActivoFijo()
	{
		try {

			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sActivoFijo->InsertarActivoFijo($data);

			if (is_array($resultado)) {
				$output["resultado"] = $resultado;

				$resultado2 = $this->sActivoFijo->InsertarJSONDesdeActivoFijo($resultado);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/activofijo/activosfijos.json');

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

	public function ActualizarActivoFijo()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sActivoFijo->ActualizarActivoFijo($data);

			if ($resultado == "") {

				$resultado2 = $this->sActivoFijo->ActualizarJSONDesdeActivoFijo($data);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/activofijo/activosfijos.json');
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

	public function BorrarActivoFijo()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sActivoFijo->BorrarActivoFijo($data);

			if ($resultado == "") {

				$resultado2 = $this->sActivoFijo->BorrarJSONDesdeActivoFijo($data);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					// clearstatcache(true, BASE_PATH.'assets/data/activofijo/activosfijos.json');
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

	public function ObtenerActivoFijoPorCodigoActivoFijo()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sActivoFijo->ObtenerActivoFijoPorCodigoActivoFijo($data);
		echo $this->json->json_response($resultado);
	}
}
