<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cResumenDiario extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("FacturacionElectronica/sResumenDiario");
		$this->load->service("FacturacionElectronica/sDetalleResumenDiario");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("Configuracion/FacturacionElectronica/sTipoDocumentoElectronico");
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("Configuracion/General/sEmpresa");
		$this->load->service('Configuracion/General/sParametroSistema');
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("Catalogo/sEmpleado");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('archivo');
		$this->load->helper('date');
		$this->load->library('zipper');
	}

	public function Index()
	{
		$data["NumeroDocumento"] = "%";
		$data["RazonSocial"] = "%";
		$data["FechaEmision"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaInicio"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaFin"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["EstadoCPE"] = "%";
		$data["EstadosResumen"] = array(
			array("NombreEstado" => "ACEPTADO","CodigoEstado" => "C"),
			array("NombreEstado" => "PENDIENTE","CodigoEstado" => "P"),
			array("NombreEstado" => "RECHAZADO","CodigoEstado" => "R")
		);
		$data["CodigoEstado"] = "%";

		$ResumenesDiario = $this->sResumenDiario->ConsultarComprobantesVenta($data);
		$ResumenesDiarioConsulta = $this->sResumenDiario->ConsultarResumenesDiario($data);
		$data["FechaEmision"]=convertirFechaES($data["FechaEmision"]);
		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = convertirFechaES($data["FechaFin"]);
		$data["CodigoEstado"] = "";
		$Buscador = $data;

		$parametroBetaSUNAT = $this->sConstanteSistema->ObtenerParametroBetaSUNAT();

		$data = array("data" =>
				array(
					'ResumenesDiario' => $ResumenesDiario,
					'ResumenDiario' => array(),
					'ResumenesDiarioConsulta' => $ResumenesDiarioConsulta,
					'ResumenDiarioConsulta' => array(),
					'Buscador' => $Buscador,
					'BuscadorConsulta' => $Buscador,
					'DetalleResumenesDiario' => array(),
					'ParametroBetaSUNAT' => $parametroBetaSUNAT
				)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_resumendiario']=   $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_subcontent_buscador_resumendiario','',true);
		$view_sub_subcontent['view_subcontent_buscador_consultaresumendiario']=   $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_subcontent_buscador_consultaresumendiario','',true);
		$view_subcontent['view_subcontent_preview_resumendiario'] =  $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_subcontent_preview_resumendiario','',true);
		$view_subcontent['view_subcontent_consulta_resumendiarios'] =  $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_subcontent_consulta_resumendiarios',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_consulta_consultaresumendiarios'] =  $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_subcontent_consulta_consultaresumendiarios',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_resumendiario'] =  $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_subcontent_form_resumendiario','',true);

		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_footer_resumendiario',$view_data,true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/ResumenDiario/view_mainpanel_content_resumendiario',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarComprobantesVenta()
	{
		$data = $this->input->post("Data");
		$data["FechaEmision"]=convertToDate($data["FechaEmision"]);
		$resultado = $this->sResumenDiario->ConsultarComprobantesVenta($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarResumenesDiario()
	{
		$data = $this->input->post("Data");
		$data["FechaInicio"]=convertToDate($data["FechaInicio"]);
		$data["FechaFin"]=convertToDate($data["FechaFin"]);
		$resultado = $this->sResumenDiario->ConsultarResumenesDiario($data);

		echo $this->json->json_response($resultado);
	}

	public function GenerarResumen(){
		// $data1 = $this->input->post("Data");
		$data1 = $_POST["Data"];
		$data1 = json_decode($data1, true);
		$data1 = $data1["Data"];

		$resultado["error"] = "";
		//Obteniendo DATA de ObtenerCarpetasSUNAT
		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
		
		foreach ($data1 as $key => $value) {
			if (!array_key_exists("SerieNumeroDocumento",$value) ) {
				$data1[$key]["FechaEmision"]=convertToDate($value["FechaEmision"]);
				$data1[$key]["SerieNumeroDocumento"] = $value["SerieDocumento"]."-".$value["NumeroDocumento"];				
			}

			if (!array_key_exists("NumeroDocumentoCliente",$value) ) {
				$data1[$key]["NumeroDocumentoCliente"] = $value["NumeroDocumentoIdentidad"];
				$data1[$key]["CodigoMoneda"] =$value["CodigoMoneda"];
			}
			
			if($value["IndicadorEstado"] == ESTADO_DOCUMENTO_ANULADO)
			{
				$data1[$key]["Total"] = '0.00';
				$data1[$key]["ValorVentaGravado"] = '0.00';
				$data1[$key]["ValorVentaNoGravado"] = '0.00';
				$data1[$key]["ValorVentaInafecto"] = '0.00';
				$data1[$key]["OtroCargo"] = '0.00';
				$data1[$key]["IGV"] = '0.00';
				$data1[$key]["ISC"] = '0.00';
				$data1[$key]["OtroTributo"] = '0.00';
			}
		}

		//$resultado = $this->sResumenDiario->TransformarXML();
		//Obteniendo DATA para la creacion del JSON
		$preparacion = $this->sResumenDiario->PrepararResumenDiario($data1);

		if($preparacion["error"] != ""){
			$resultado["error"] = $preparacion["error"];
			$resultado["title"] = "<strong>Ocurrio un error.</strong>";
			$resultado["type"] = "warning";
			$resultado["clase"] = "notify-warning";
			$resultado["message"] = $preparacion["error"];
			echo $this->json->json_response($resultado);
			exit;
		}

		$DatosEmpresa = $preparacion["dataEmpresa"][0];

		$DatosCV = $preparacion["data"];

		$data = array_merge($DatosEmpresa, $DatosCV);

		$data_documento["IdEmpresa"] = ID_EMPRESA;
		$data_documento["CodigoTipoDocumentoElectronico"] = CODIGO_TIPO_DOCUMENTO_RESUMEN_DIARIO;
		$Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
		$nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
		$rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
		$rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
		$rutajson = $Plantillas_data->NombrePlantillaJSON;

		$nombre = $data["NombreResumenDiario"];
		$data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
		$data_json["plantilla"] =APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
		$data_json["data"] = $data;
		// $data_json["data"]["FechaEmisionDocumento"] = "34-34-34";
		$json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);

		$data_xml["codigotipodocumento"] = $data_documento["CodigoTipoDocumentoElectronico"];
		$data_xml["nombrearchivo"] =  $data["NombreResumenDiario"];
		$data_xml["tipoarchivo"] = ".xml";
		$data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];
		$generar = $this->sComprobanteElectronico->GenerarXMLComprobanteVenta($data_xml,false);

		if($generar["error"] != "")
		{
			$resultado["error"] = $generar["error"];
			$resultado["title"] = "<strong>Ocurrio un error.</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = $generar["msg"];
			echo $this->json->json_response($resultado);
			exit;
		}

		$ZIP = $this->sComprobanteElectronico->GenerarZIPEnvio($data_xml);

		if($ZIP["error"] != "")
		{
			$resultado["error"] = $ZIP["Error"];
			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = $ZIP["msg"];
			return $this->json->json_response($resultado);
			exit;
		}

		//ENVIANDO EL DOCUMENTO A Sunat
		$data_envio["nombrearchivo"] = $data["NombreResumenDiario"];
		$envio = $this->sResumenDiario->EnviarResumenDiarioSUNAT($data_envio, $DatosCV);

		$nombre_archivo = "R-".$nombre;
		$data_zip["Destino"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_ERROR"]; //URL DE DESTINO DE ARCHIVO
		$data_zip["UbicacionZIP"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_archivo.'.zip';
		$data_zip["File"] = $nombre_archivo.".xml";  //NOMBRE DEL ARCHIVO XML

		if(array_key_exists('Error', $envio))
		{
			$resultado["error"] = $envio["Error"];
			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = $envio["FaultString"];
			// $resultado["CodigoError"] = $envio["FaultCode"];
			$resultado["DescripcionError"] = $envio["FaultString"];
		}
		else{
			$data_final = array_merge($DatosCV, $envio);
			if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_ACEPTADO) //ACEPTADO
			{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);
				$data_final["CodigoError"] = $respuesta["CodigoRespuesta"];
				$data_final["DescripcionError"] = $respuesta["MensajeRespuesta"];
				$data_final["IndicadorEstadoResumenDiario"] = ESTADO_CPE_ACEPTADO;
				$resultado["title"] = "<strong>Éxito.</strong>";
				$resultado["type"] = "success";
				$resultado["clase"] = "notify-success";
				$resultado["message"] = "El resumen diario fue aceptado.";
			}
			else if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_PENDIENTE) //PENDIENTE
			{
				$data_final["IndicadorEstadoResumenDiario"] = ESTADO_CPE_EN_PROCESO;
				$resultado["title"] = "<strong>Pendiente...</strong>";
				$resultado["type"] = "warning";
				$resultado["clase"] = "notify-warning";
				$resultado["message"] = "El resumen diario esta en proceso. Dirijase a las Consultas de Resumen Diario y espere hasta obtener una respuesta para el resumen diario.";
			}
			else if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO)//CON ERROR
			{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);
				$data_final["CodigoError"] = $respuesta["CodigoRespuesta"];
				$data_final["DescripcionError"] = $respuesta["MensajeRespuesta"];
				$resultado["title"] = "<strong>Error!</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = "El resumen diario ha sido rechazado.";
			}
			else
			{
				$data_final["CodigoError"] = $envio["CodigoEstadoRespuestaSunat"];
				$data_final["DescripcionError"] = $envio["CodigoEstadoRespuestaSunat"];
				$data_final["IndicadorEstadoResumenDiario"] = ESTADO_CPE_RECHAZADO;
				$resultado["title"] = "<strong>Error!</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = "Ocurrio un error con el resumen diario.";
			}
			
			if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO)
			{
				if($data_final["CodigoError"] >= CODIGO_INICIO_ERROR_RECHAZO && $data_final["CodigoError"] <= CODIGO_FIN_ERROR_RECHAZO)
				{
					$data_final["IndicadorEstadoResumenDiario"] = ESTADO_CPE_RECHAZADO;
					$guardado = $this->sResumenDiario->ActualizarResumenDiario($data_final);
				}
			}
			else
			{
				$guardado = $this->sResumenDiario->ActualizarResumenDiario($data_final);
			}

		}
		//AQUI ELIMINAMOS EL ZIP
		$this->sComprobanteElectronico->EliminarZIPEnvio($data_xml);

		echo $this->json->json_response($resultado);
		//echo $resultado;
		//echo $resultado;
	}


	public function InsertarResumenDiario()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sResumenDiario->InsertarResumenDiario($data);
		$data["IdResumenDiario"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarResumenDiario()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sResumenDiario->ActualizarResumenDiario($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarResumenDiario()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sResumenDiario->BorrarResumenDiario($data);
		echo $this->json->json_response($resultado);
	}

	//DESCARGANDO ARCHIVOS XML Y CDR
	public function DescargarXML()
	{
		$archivo= $this->input->get("nombre");
		// $ruta = ;
		$nombre_archivo = $archivo.'.xml';
		// $url_archivo =BASE_PATH."assets/data/facturacionelectronica/xml/".$archivo.".xml";
		$url_archivo =BASE_PATH."assets/data/facturacionelectronica/xml/".$archivo.".xml";

		// Clean output buffer
		if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
		{
			@ob_clean();
		}

		header('Content-disposition: attachment; filename="'.$nombre_archivo.'"');
		header('Content-type: "text/xml"; charset="utf8"');

		readfile($url_archivo);
		exit;
		// echo $url_archivo;
	}

	public function DescargarCDR()
	{
		$archivo= $this->input->get("nombre");
		// $ruta = ;
		$nombre_zip = "R-".$archivo.'.zip';
		$nombre_archivo = "R-".$archivo.'.xml';

		// $url_zip =BASE_PATH."assets/data/facturacionelectronica/cdr/".$nombre_zip;
		$url_zip =BASE_PATH."assets/data/facturacionelectronica/cdr/".$nombre_zip;

		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
		$data_zip["Destino"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"]; //URL DE DESTINO DE ARCHIVO
		$data_zip["UbicacionZIP"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_zip;  //URL DE UBICACION DEL ZIP
		$data_zip["File"] = $nombre_archivo;
		$this->zipper->ExtraerXML($data_zip);

		// $url_archivo =BASE_PATH."assets/data/facturacionelectronica/cdr/".$nombre_archivo;
		$url_archivo =BASE_PATH."assets/data/facturacionelectronica/cdr/".$nombre_archivo;

		// Clean output buffer
		if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
		{
			@ob_clean();
		}

		header('Content-disposition: attachment; filename="'.$nombre_archivo.'"');
		header('Content-type: "text/xml"; charset="utf8"');

		readfile($url_archivo);

		unlink(APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_archivo);
		exit;
		// echo $url_archivo;
	}

	public function ValidarXML()
	{
		$archivo= $this->input->post("nombre");

		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

		$url_exist = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$archivo.".xml";
		if(file_exists($url_exist))
		{
			echo "1";
		}
		else {
			echo "0";
		}
	}

	public function ValidarCDR()
	{
		$archivo= $this->input->post("nombre");

		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

		$url_exist = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"]."R-".$archivo.".zip";
		if(file_exists($url_exist))
		{
			echo "1";
		}
		else {
			echo "0";
		}
	}

	public function ConsultarEstadoResumenDiario()
	{
		$bajas = json_decode($this->input->post("Data"), true);
		$i = 0;
		foreach ($bajas as $key => $value) {
			if($value["IndicadorEstadoResumenDiario"] == ESTADO_CPE_EN_PROCESO){
				$response = $this->sResumenDiario->ConsultarEstadoResumenDiarioSUNAT($value);
				if(array_key_exists("IndicadorEstadoResumenDiario",$response))
				{
					$bajas[$key]["IndicadorEstadoResumenDiario"] = $response["IndicadorEstadoResumenDiario"];
				}
			}
			$i++;
		}

		echo $this->json->json_response($bajas);
	}


}
