<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cComunicacionBaja extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("FacturacionElectronica/sComunicacionBaja");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
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
		$data["FechaEmision"] =$this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaInicio"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaFin"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["EstadoCPE"] = "%";
		$data["EstadosResumen"] = array(
			array("NombreEstado" => "ACEPTADO","CodigoEstado" => "C"),
			array("NombreEstado" => "PENDIENTE","CodigoEstado" => "P"),
			array("NombreEstado" => "RECHAZADO","CodigoEstado" => "R")
		);
		$data["CodigoEstado"] = "%";

		$ComunicacionesBaja = $this->sComunicacionBaja->ConsultarFacturasElectronicasConComunicacionBaja($data);
		$ComunicacionesBajaConsulta = $this->sComunicacionBaja->ConsultarComunicacionesBaja($data);

		$data["FechaEmision"]=convertirFechaES($data["FechaEmision"]);
		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = convertirFechaES($data["FechaFin"]);
		$data["CodigoEstado"] = "";
		$Buscador = $data;
		$Numero_Filas_Pendiente = 0;
		$Numero_Filas_Generado = 0;

		$parametroBetaSUNAT = $this->sConstanteSistema->ObtenerParametroBetaSUNAT();

		$data = array("data" =>
				array(
					'ComunicacionesBaja' => $ComunicacionesBaja,
					'ComunicacionBaja' => array(),
					'ComunicacionesBajaConsulta' => $ComunicacionesBajaConsulta,
					'ComunicacionBajaConsulta' => array(),
					'Buscador' => $Buscador,
					'BuscadorConsulta' => $Buscador,
					'DetalleComunicacionesBaja' => array(),
					'ParametroBetaSUNAT' => $parametroBetaSUNAT
				)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_comunicacionbaja']=   $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_subcontent_buscador_comunicacionbaja','',true);
		$view_sub_subcontent['view_subcontent_buscador_consultacomunicacionbaja']=   $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_subcontent_buscador_consultacomunicacionbaja','',true);
		$view_subcontent['view_subcontent_preview_comunicacionbaja'] =  $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_subcontent_preview_comunicacionbaja','',true);
		$view_subcontent['view_subcontent_consulta_comunicacionbajas'] =  $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_subcontent_consulta_comunicacionbajas',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_consulta_consultacomunicacionbajas'] =  $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_subcontent_consulta_consultacomunicacionbajas',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_comunicacionbaja'] =  $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_subcontent_form_comunicacionbaja','',true);


		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_footer_comunicacionbaja',$view_data,true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/ComunicacionBaja/view_mainpanel_content_comunicacionbaja',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarFacturasElectronicasConComunicacionBaja()
	{
		$data = $this->input->post("Data");
		$data["FechaEmision"]=convertToDate($data["FechaEmision"]);
		$resultado = $this->sComunicacionBaja->ConsultarFacturasElectronicasConComunicacionBaja($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarComunicacionesBaja()
	{
		$data = $this->input->post("Data");
		$data["FechaInicio"]=convertToDate($data["FechaInicio"]);
		$data["FechaFin"]=convertToDate($data["FechaFin"]);
		$resultado = $this->sComunicacionBaja->ConsultarComunicacionesBaja($data);

		echo $this->json->json_response($resultado);
	}

	public function GenerarBaja(){
		// $data1 = $this->input->post("Data");
		$data1 = $_POST["Data"];
		$data1 = json_decode($data1, true);
		$data1 = $data1["Data"];

		$resultado["error"] = "";
		//Obteniendo DATA de ObtenerCarpetasSUNAT
		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta =$this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

		//$resultado = $this->sComunicacionBaja->TransformarXML();
		//Obteniendo DATA para la creacion del JSON
		$preparacion = $this->sComunicacionBaja->PrepararComunicacionBaja($data1);
		//echo $this->json->json_response($preparacion);
		//exit;

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
		$data_documento["CodigoTipoDocumentoElectronico"] = CODIGO_TIPO_DOCUMENTO_COMUNICACION_BAJA;
		$Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
		$nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
		$rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
		$rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
		$rutajson = $Plantillas_data->NombrePlantillaJSON;

		$nombre = $data["NombreComunicacionBaja"];
		$data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
		$data_json["plantilla"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
		$data_json["data"] = $data;

		$json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);

		$data_xml["codigotipodocumento"] = CODIGO_TIPO_DOCUMENTO_COMUNICACION_BAJA;
		$data_xml["nombrearchivo"] =  $data["NombreComunicacionBaja"];
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
		$data_envio["nombrearchivo"] = $data["NombreComunicacionBaja"];
		$data_final = array_merge($DatosCV, $data_envio);
		$envio = $this->sComunicacionBaja->EnviarComunicacionBajaSUNAT($data_envio, $DatosCV);

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
			if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_ACEPTADO) //ACEPTADO
			{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);
				$DatosCV["CodigoError"] = $respuesta["CodigoRespuesta"];
				$DatosCV["DescripcionError"] = $respuesta["MensajeRespuesta"];
				$DatosCV["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_ACEPTADO;
				$resultado["title"] = "<strong>Éxito.</strong>";
				$resultado["type"] = "success";
				$resultado["clase"] = "notify-success";
				$resultado["message"] = "La comunicacion de baja fue aceptada.";
			}
			else if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_PENDIENTE) //PENDIENTE
			{
				$DatosCV["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_EN_PROCESO;
				$resultado["title"] = "<strong>Pendiente...</strong>";
				$resultado["type"] = "warning";
				$resultado["clase"] = "notify-warning";
				$resultado["message"] = "La comunicacion de baja esta en proceso. Dirijase a las Consultas de Comunicacion de Baja y espere hasta obtener una respuesta para la comunicacion de baja.";
			}
			else if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO)//CON ERROR
			{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);
				$DatosCV["CodigoError"] = $respuesta["CodigoRespuesta"];
				$DatosCV["DescripcionError"] = $respuesta["MensajeRespuesta"];
				// $DatosCV["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_RECHAZADO;
				$resultado["title"] = "<strong>Error!</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = "La comunicacion de baja fue rechazada.";
			}
			else
			{
				$DatosCV["CodigoError"] = $envio["CodigoEstadoRespuestaSunat"];
				$DatosCV["DescripcionError"] = $envio["CodigoEstadoRespuestaSunat"];
				$DatosCV["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_RECHAZADO;
				$resultado["title"] = "<strong>Error!</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = "Ocurrio un error con la comunicacion de baja.";
			}

			$data_final = array_merge($DatosCV, $envio);

			if($envio["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO)
			{
				if($data_final["CodigoError"] >= CODIGO_INICIO_ERROR_RECHAZO && $data_final["CodigoError"] <= CODIGO_FIN_ERROR_RECHAZO)
				{
					$data_final["IndicadorEstadoResumenDiario"] = ESTADO_CPE_RECHAZADO;
					$guardado = $this->sComunicacionBaja->ActualizarComunicacionBaja($data_final);	
				}
			}
			else
			{
				$guardado = $this->sComunicacionBaja->ActualizarComunicacionBaja($data_final);
			}
		}
		//AQUI ELIMINAMOS EL ZIP
		$this->sComprobanteElectronico->EliminarZIPEnvio($data_xml);
		echo $this->json->json_response($resultado);
	}
	/***************************************************************/

	public function InsertarComunicacionBaja()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sComunicacionBaja->InsertarComunicacionBaja($data);
		$data["IdComunicacionBaja"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarComunicacionBaja()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sComunicacionBaja->ActualizarComunicacionBaja($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarComunicacionBaja()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sComunicacionBaja->BorrarComunicacionBaja($data);
		echo $this->json->json_response($resultado);
	}

	//para descargar xml y zipper
	public function DescargarXML()
	{
		$archivo= $this->input->get("nombre");
		// $ruta = ;
		$nombre_archivo = $archivo.'.xml';
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

		$url_zip =BASE_PATH."assets/data/facturacionelectronica/cdr/".$nombre_zip;

		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
		$data_zip["Destino"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"]; //URL DE DESTINO DE ARCHIVO
		$data_zip["UbicacionZIP"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_zip;  //URL DE UBICACION DEL ZIP
		$data_zip["File"] = $nombre_archivo;
		$this->zipper->ExtraerXML($data_zip);

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

	public function ConsultarEstadoComunicacionBaja()
	{
		$bajas = json_decode($this->input->post("Data"), true);
		$i = 0;
		foreach ($bajas as $key => $value) {
			if($value["IndicadorEstadoComunicacionBaja"] == ESTADO_CPE_EN_PROCESO){
				$response = $this->sComunicacionBaja->ConsultarEstadoComunicacionBajaSUNAT($value);
				if(array_key_exists("IndicadorEstadoComunicacionBaja",$response))
				{
					$bajas[$key]["IndicadorEstadoComunicacionBaja"] = $response["IndicadorEstadoComunicacionBaja"];
				}
			}
			$i++;
		}

		echo $this->json->json_response($bajas);
	}

}
