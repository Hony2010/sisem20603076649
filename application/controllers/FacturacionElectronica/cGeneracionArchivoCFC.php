<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGeneracionArchivoCFC extends CI_Controller  {


	public $MotivosCFC = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("FacturacionElectronica/sGeneracionArchivoCFC");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Configuracion/FacturacionElectronica/sMotivoComprobanteFisicoContingencia");
		$this->load->service("Catalogo/sEmpleado");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('zipper');
		$this->load->helper('date');
		$this->load->service('Configuracion/General/sEmpresa');
		$this->load->service('Configuracion/General/sParametroSistema');

		$this->MotivosCFC = $this->sMotivoComprobanteFisicoContingencia->ListarMotivosComprobanteFisicoContingencia();

	}


	public function Index()
	{
		$fechaservidor=$this->Base->ObtenerFechaServidor("Y-m-d");
		$input["FechaInicio"]=$fechaservidor;

		$ComprobantesVenta = $this->sComprobanteVenta->ConsultarComprobantesVentaCFC($input);

		$input["FechaInicio"]=date("d/m/Y", strtotime($input["FechaInicio"]));

		$parametro["IdModuloSistema"] =ID_MODULO_VENTA;
		$TiposDocumento = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($parametro,0);

		foreach ($ComprobantesVenta as $key => $value) {
			if($value["IdMotivoComprobanteFisicoContingencia"] == null){
				$ComprobantesVenta[$key]["IdMotivoComprobanteFisicoContingencia"] = $this->MotivosCFC[0]["IdMotivoComprobanteFisicoContingencia"];
				$ComprobantesVenta[$key]["CodigoMotivoComprobanteFisicoContingencia"] = $this->MotivosCFC[0]["CodigoMotivoComprobanteFisicoContingencia"];
			}
			else {
				// code...
				foreach ($this->MotivosCFC as $key2 => $value2) {
					if($value["IdMotivoComprobanteFisicoContingencia"] == $value["IdMotivoComprobanteFisicoContingencia"])
					{
						$ComprobantesVenta[$key]["CodigoMotivoComprobanteFisicoContingencia"] = $value2["CodigoMotivoComprobanteFisicoContingencia"];
					}
				}
			}
		}

		$data = array("data" =>
					array(
						'ComprobantesVenta'=> $ComprobantesVenta,
						'GeneracionesArchivoCFC' => array(),
						'GeneracionArchivoCFC' => array(),
						'Buscador' => $input,
						'Motivos'=>$this->MotivosCFC
					)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_generacionarchivocfc']=   $this->load->View('FacturacionElectronica/GeneracionArchivoCFC/view_mainpanel_subcontent_buscador_generacionarchivocfc','',true);
		$view_subcontent['view_subcontent_consulta_generacionarchivocfc'] =  $this->load->View('FacturacionElectronica/GeneracionArchivoCFC/view_mainpanel_subcontent_consulta_generacionarchivocfc',$view_sub_subcontent,true);

		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/GeneracionArchivoCFC/view_mainpanel_footer_generacionarchivocfc',$view_data,true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/GeneracionArchivoCFC/view_mainpanel_content_generacionarchivocfc',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarComprobantesVenta()
	{
		$input = $this->input->post("Data");
		$input["FechaInicio"]=convertToDate($input["FechaInicio"]);
		$resultado = $this->sComprobanteVenta->ConsultarComprobantesVentaCFC($input);

		foreach ($resultado as $key => $value) {
			if($value["IdMotivoComprobanteFisicoContingencia"] == null){
				$resultado[$key]["IdMotivoComprobanteFisicoContingencia"] = $this->MotivosCFC[0]["IdMotivoComprobanteFisicoContingencia"];
				$resultado[$key]["CodigoMotivoComprobanteFisicoContingencia"] = $this->MotivosCFC[0]["CodigoMotivoComprobanteFisicoContingencia"];
			}
			else {
				// code...
				foreach ($this->MotivosCFC as $key2 => $value2) {
					if($value["IdMotivoComprobanteFisicoContingencia"] == $value["IdMotivoComprobanteFisicoContingencia"])
					{
						$resultado[$key]["CodigoMotivoComprobanteFisicoContingencia"] = $value2["CodigoMotivoComprobanteFisicoContingencia"];
					}
				}
			}
		}
		echo $this->json->json_response($resultado);
	}

	public function GenerarArchivoCFC()
	{
		try {
			$this->db->trans_begin();

			$data_post = $_POST["Data"];
			$fecha = $_POST["Fecha"];
			$data_post = json_decode($data_post, true);

			$data_empresa["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_empresa)[0];
			$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
			// $fechaservidor=$this->Base->ObtenerFechaServidor("Ymd");
			$fechaarchivo = convertToDate($fecha);

			$data_s["DetallesCFC"] = $data_post;
			$data_s["Fecha"] = $fechaarchivo;

			$this->sGeneracionArchivoCFC->InsertarResumenComprobanteFisicoContingencia($data_s);

			$numero = $this->sGeneracionArchivoCFC->ObtenerNuevoCorrelativo($data_s);
			// $numero = str_pad($numero, 3, "0", STR_PAD_LEFT);
			$data["PlantillaJSON"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"]."Plantilla_CFC.json";
			$data["RutaSalida"] =  APP_PATH.$DatosCarpeta["RUTA_CARPETA_PEI"];
			$fechaarchivo = str_replace ("-", "", $fechaarchivo);
			$data["NombreArchivo"] = $DatosEmpresa["CodigoEmpresa"]."-RF-".$fechaarchivo."-".$numero.".txt";//"nuevo.txt";
			$data["ComprobantesVenta"] = $data_post;
			// echo $this->json->json_response($data);
			// exit;
			$resultado = $this->sGeneracionArchivoCFC->GenerarArchivoCFC($data);
			if($resultado)
			{

				$this->db->trans_commit();

				$resultado = site_url()."/FacturacionElectronica/cGeneracionArchivoCFC/DescargarTXT?nombre=".$data["NombreArchivo"];

				$response["error"] = "";
				$response["url"] = $resultado;
				echo $this->json->json_response($response);
			}
			else {
				$this->db->trans_rollback();
				$response["error"] = "Ha ocurrido un error al guardar.";
				echo $this->json->json_response($response);
			}
		} catch (Exception $e) {
			echo $e;
		}

	}

	public function DescargarTXT()
	{
		$archivo= $this->input->get("nombre");
		// $ruta = ;
		$nombre_archivo = $archivo;
		$url_archivo =BASE_PATH."assets/data/pei/".$archivo;

		// Clean output buffer
		if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
		{
			@ob_clean();
		}
		header('Content-disposition: attachment; filename="'.$nombre_archivo.'"');
		header('Content-type: "text/txt"; charset="utf-8"');

		readfile($url_archivo);
		exit;
	}

	public function InsertarGeneracionArchivoCFC()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGeneracionArchivoCFC->InsertarGeneracionArchivoCFC($data);
		$data["IdGeneracionArchivoCFC"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarGeneracionArchivoCFC()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGeneracionArchivoCFC->ActualizarGeneracionArchivoCFC($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarGeneracionArchivoCFC()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sGeneracionArchivoCFC->BorrarGeneracionArchivoCFC($data);
		echo $this->json->json_response($resultado);
	}

}
