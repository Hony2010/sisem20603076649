<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cServicio extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sServicio");
    	$this->load->service("Configuracion/Catalogo/sLineaProducto");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
    	$this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
    	$this->load->service("Configuracion/Catalogo/sTipoServicio");
		$this->load->service("Configuracion/General/sTipoAfectacionIGV");
		$this->load->service("Configuracion/General/sTipoPrecio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$input["textofiltro"]='';
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = $this->sServicio->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["totalfilas"] =$this->sServicio->ObtenerNumeroTotalServicios($input);

    	$Servicio = $this->sServicio->Inicializar();
		$Servicios	 = $this->sServicio->ListarServicios(1);
		$LineasProducto	 = $this->sLineaProducto->ListarLineasProducto();
   	 	$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();
    	$SubFamiliasProducto = $this->sSubFamiliaProducto->ListarTodosSubFamiliasProducto();
		$TiposServicio = $this->sTipoServicio->ListarTiposServicio();
		$ImageURL = $this->sServicio->ObtenerUrlCarpetaImagenes();

		$TiposAfectacionIGV = $this->sTipoAfectacionIGV->ListarTiposAfectacionIGV();
		$TiposPrecio = $this->sTipoPrecio->ListarTiposPrecio();

		$data = array("data" =>
			array(
				'Filtros' => $input,
				'Servicio'=>$Servicio,
				'NuevoServicio'=>$Servicio,
				'Servicios'=>$Servicios,
				'LineasProducto'=>$LineasProducto,
				'FamiliasProducto'=>$FamiliasProducto,
				'SubFamiliasProducto'=>$SubFamiliasProducto,
				'TiposServicio' =>$TiposServicio,
				'ImageURL' =>$ImageURL,
				'TiposPrecio'=>$TiposPrecio,
				'TiposAfectacionIGV'=>$TiposAfectacionIGV
			)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_servicio']=   $this->load->View('Catalogo/Servicio/view_mainpanel_subcontent_buscador_servicio','',true);
		$view_sub_subcontent['view_subcontent_paginacion_servicio']=   $this->load->View('Catalogo/Servicio/view_mainpanel_subcontent_paginacion_servicio','',true);
		$view_subcontent['view_subcontent_preview_servicio'] =  $this->load->View('Catalogo/Servicio/view_mainpanel_subcontent_preview_servicio','',true);
		$view_subcontent['view_subcontent_consulta_servicios'] =  $this->load->View('Catalogo/Servicio/view_mainpanel_subcontent_consulta_servicios',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_servicio'] =  $this->load->View('Catalogo/Servicio/view_mainpanel_subcontent_form_servicio','',true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/Servicio/view_mainpanel_footer_Servicio',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/Servicio/view_mainpanel_content_servicio',$view_subcontent,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function Sugerencias()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sServicio->ConsultarServicio($data, 1);

		echo $this->json->json_response($resultado);
	}

  public function ListarFamiliasProductos()
	{
		$resultado = $this->sFamiliaProducto->ListarFamiliasProductos();

		echo $this->json->json_response($resultado);
	}

	public function InsertarServicio()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sServicio->InsertarServicio($data);

			if (is_array($resultado)) {
				$output["resultado"] = $resultado;

				$resultado2 = $this->sServicio->InsertarJSONDesdeServicio($resultado);
				if(is_array($resultado2))	{
					$this->db->trans_commit();
					
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$numerofilasporpagina = $this->sServicio->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sServicio->ObtenerNumeroTotalServicios($data);
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

	public function ActualizarServicio()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			
			$resultado = $this->sServicio->ActualizarServicio($data);
			
			if ($resultado == "") {
				$resultado2 = $this->sServicio->ActualizarJSONDesdeServicio($data);
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

	public function BorrarServicio()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sServicio->BorrarServicio($data);

			if ($resultado == "") {

				$resultado2 = $this->sServicio->BorrarJSONDesdeServicio($data);

				if(is_array($resultado2))	{
					$this->db->trans_commit();
					
					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$numerofilasporpagina = $this->sServicio->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sServicio->ObtenerNumeroTotalServicios($data);
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

		}
		catch (Exception $e) {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($e);
		}
	}

	public function SubirFoto()
	{
		$IdProducto = $this->input->post("IdProducto");
		$InputFileName = $this->input->post("InputFileName");
		//$InputFileCode = $this->input->post("InputFileCode");

		$patcher = DIR_ROOT_ASSETS.'/img/Servicio/'.$IdProducto.'/';

		$config['upload_path'] = $patcher;
		$resultado = $this->shared->upload_file($InputFileName,$config);
		
		print_r($resultado);
		print_r($config['upload_path']);
		print_r($config);
	}

	public function ObtenerServicioPorIdProducto()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sServicio->ObtenerServicioPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerServicioPorCodigoServicio()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sServicio->ObtenerServicioPorCodigoServicio($data);
		echo $this->json->json_response($resultado);
	}


	public function ConsultarServicioPorIdProducto()
	{
		$data["IdProducto"] = $this->input->post("Data");
		$resultado = $this->sServicio->ConsultarServicioPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	
	public function ConsultarServicioParaJSONAvanzado()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sServicio->ConsultarServicioParaJSONAvanzado($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarServiciosPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sServicio->ConsultarServicios($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarServicios() {		
		$input=$this->input->get("Data");
		$numerofilasporpagina = $this->sServicio->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sServicio->ObtenerNumeroTotalServicios($input);
		$output["resultado"] = $this->sServicio->ConsultarServicios($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);

		echo $this->json->json_response($output);
	}

}
