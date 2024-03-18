<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cMercaderia extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
    	$this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
    	$this->load->service("Configuracion/Catalogo/sTipoExistencia");
		$this->load->service("Configuracion/Catalogo/sLineaProducto");
    	$this->load->service("Configuracion/Catalogo/sMarca");
    	$this->load->service("Configuracion/Catalogo/sModelo");
    	$this->load->service("Configuracion/General/sUnidadMedida");
		$this->load->service("Configuracion/Catalogo/sFabricante");
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->service("Configuracion/General/sTipoSistemaISC");
		$this->load->service("Configuracion/General/sTipoAfectacionIGV");
		$this->load->service("Configuracion/General/sTipoPrecio");
		$this->load->service("Configuracion/General/sOrigenMercaderia");
		$this->load->service("Catalogo/sListaPrecioMercaderia");
		$this->load->service("Catalogo/sListaRaleoMercaderia");
		$this->load->service("Catalogo/sProductoProveedor");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->component('Catalogo/pMercaderia');
	}

	public function Index()
	{
		
		$view_data['data'] =$this->pMercaderia->Iniciar();// $data;
		
		$view_sub_subcontent['view_subcontent_buscador_mercaderia']=   $this->load->View('Catalogo/Mercaderia/view_mainpanel_subcontent_buscador_mercaderia','',true);
		$view_sub_subcontent['view_subcontent_paginacion_mercaderia']=   $this->load->View('Catalogo/Mercaderia/view_mainpanel_subcontent_paginacion_mercaderia','',true);
		$view_subcontent['view_subcontent_preview_mercaderia'] =  $this->load->View('Catalogo/Mercaderia/view_mainpanel_subcontent_preview_mercaderia','',true);
		$view_subcontent['view_subcontent_consulta_mercaderias'] =  $this->load->View('Catalogo/Mercaderia/view_mainpanel_subcontent_consulta_mercaderias',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_mercaderia'] =  $this->load->View('Catalogo/Mercaderia/view_mainpanel_subcontent_form_mercaderia','',true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_footer_mercaderia',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/Mercaderia/view_mainpanel_content_mercaderia',$view_subcontent,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarMercaderias()
	{
		
		$resultado  = $this->pMercaderia->ConsultarMercaderias($this->input->get("Data"));
		echo $this->json->json_response($resultado);
	}

	public function ListarBonificacionesPorIdProducto()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sMercaderia->ListarBonificacionesPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarMercaderiaPorIdProducto()
	{
		$data["IdProducto"] = $this->input->post("Data");
		$resultado = $this->sMercaderia->ConsultarMercaderiaPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaMercaderiaPorNombreProducto($data)
	{
		$q =$data;// $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sMercaderia->ConsultarSugerenciaMercaderiaPorNombreProducto($data, 1);

		echo $this->json->json_response($resultado);
	}

  public function ListarFamiliasProducto()
	{
		$resultado = $this->sFamiliaProducto->ListarFamiliasProducto();

		echo $this->json->json_response($resultado);
	}

	public function InsertarMercaderia()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sMercaderia->InsertarMercaderia($data);

			if (is_array($resultado)) {
				$output["resultado"] = $resultado;

				$resultado2 = $this->restapimercaderia->InsertarJSONDesdeMercaderia($resultado);
			 	if(is_array($resultado2))	{
					$this->db->trans_commit();

					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$numerofilasporpagina = $this->sMercaderia->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sMercaderia->ObtenerNumeroTotalMercaderias($data);
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

	public function ActualizarMercaderia() {
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sMercaderia->ActualizarMercaderia($data);
			// print_r($resultado);exit;
			if (is_array($resultado)) {
				$resultado2 = $this->restapimercaderia->ActualizarJSONDesdeMercaderia($data);
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

	public function BorrarMercaderia() {
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$filtro = $this->input->post("Filtro");
			$resultado = $this->sMercaderia->BorrarMercaderia($data);

			if ($resultado == "") {

				$resultado2 = $this->restapimercaderia->BorrarJSONDesdeMercaderia($data);

				if(is_array($resultado2))	{
					$this->db->trans_commit();

					$data["textofiltro"] = (trim($filtro) == "") ? "%" : $filtro;
					$numerofilasporpagina = $this->sMercaderia->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sMercaderia->ObtenerNumeroTotalMercaderias($data);
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
		$InputFileCode = $this->input->post("InputFileCode");
		$InputFileCodeImg = $this->input->post("CodigoBarrasImage");

		$patcher = DIR_ROOT_ASSETS.'/img/Mercaderia/'.$IdProducto.'/';
		$patcher2 = DIR_ROOT_ASSETS.'/img/Mercaderia/'.$IdProducto.'/CodigoBarra/';

		//$patcher = site_url().'../img/Mercaderia/';
		//$config['upload_path'] = '../img/Mercaderia/'.$IdProducto.'/';
		$config['upload_path'] = $patcher;
		$resultado = $this->shared->upload_file($InputFileName,$config);

		if(mkdir($patcher2, 0777))
		{echo "Se creó la subcarpeta corectamente.";}
		else
		{echo "Fallo al crear la subcarpeta";}

		$rutaCarpetaRpta=DIR_ROOT_ASSETS.'/img/Mercaderia/'.$IdProducto.'/CodigoBarra/'.$InputFileCode;
		$imgcreate = imagecreatefromjpeg($InputFileCodeImg);
		imagejpeg($imgcreate, $rutaCarpetaRpta, 100);
		imagedestroy($imgcreate);

		//file_put_contents($rutaCarpetaRpta, $data_image);

		//$config2['upload_path'] = $patcher_code;
		//$resultado2 = $this->shared->upload_file($InputFileCode,$config2);

		//print_r($resultado."\n");
		print_r($resultado);
		print_r($config['upload_path']);
		print_r($config);
		print_r($InputFileCode);
		/*print_r($_POST['procesar']);
		print_r($resultado2);
		print_r($config2['upload_path']);
		print_r($config2);*/
	}

	public function ObtenerMercaderiaPorIdProducto()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sMercaderia->ObtenerMercaderiaPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerMercaderiaPorCodigoMercaderia()
	{
		$data = $this->input->get("Data");
		$resultado = $this->sMercaderia->ObtenerMercaderiaPorCodigoMercaderia($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarMercaderiaParaJSONAvanzado()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sMercaderia->ConsultarMercaderiaParaJSONAvanzado($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarMercaderiasPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sMercaderia->ConsultarMercaderias($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerUltimoCodigoMercaderia()
	{
		$resultado = $this->sMercaderia->ObtenerUltimoCodigoMercaderia();
		echo $this->json->json_response($resultado);
	}

	public function ImprimirCodigoBarras() {
		try {
			$data = $this->input->post("Data");
			$resultado = "";

			$indicadorVistaPreviaImpresion = $this->sesionusuario->obtener_sesion_indicador_vista_previa_impresion();
			if($indicadorVistaPreviaImpresion == '0') {
				$resultado = $this->sMercaderia->ImprimirCodigoBarra($data);								
			}
			else {
				$resultado = $this->sMercaderia->GenerarVistaPreviaPDF($data);
			}

			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

}
