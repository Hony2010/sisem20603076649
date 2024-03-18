<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEnvioFactura extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->model("Base");
		$this->load->library('json');
		$this->load->library('zipper');
		$this->load->library('form_validation');
		$this->load->library("RestApi/Venta/RestApiVenta");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("Catalogo/sEmpleado");
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service('Seguridad/sErrorFacturacionElectronica');
	}


	public function Index()
	{
		$dataFecha = json_decode($this->input->get("Data"), true); // desde el mensaje de facturas por vencerce en el dashboard

		$data["NumeroDocumento"] = "%";
		$data["RazonSocial"] = "%";
		$data["FechaEmision"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaInicio"] = is_array($dataFecha) ? $dataFecha["FechaInicio"] :$this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaFin"] = is_array($dataFecha) ? $dataFecha["FechaFin"] :$this->Base->ObtenerFechaServidor("Y-m-d");
		$data["EstadoCPE"] = "%";
		$data["CodigoSerie"] = (MOSTRAR_ENVIO_FACTURA_O_BOLETA == 1) ? CODIGO_SERIE_BOLETA : CODIGO_SERIE_FACTURA;
		$EnviosFactura = $this->sComprobanteElectronico->ConsultarComprobantesVentaEnvio($data);
		$EnviosFacturaConsulta = $this->sComprobanteElectronico->ConsultarComprobantesVentaEnvioPendiente($data);
		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = $data["FechaInicio"];
		$Buscador = $data;

		$Numero_Filas = 0;
		foreach ($EnviosFactura as $key => $value) {
			if ($EnviosFactura[$key]['IndicadorEstadoCPE'] == ESTADO_CPE_GENERADO) {
				$Numero_Filas++;
			}
		}

		$parametroBetaSUNAT = $this->sConstanteSistema->ObtenerParametroBetaSUNAT();

		$data = array("data" =>
			array(
				'EnviosFactura' => $EnviosFactura,
				'EnvioFactura' => array(),
				'Buscador' => $Buscador,
				'Numero_Filas' => $Numero_Filas,
				'EnviosFacturaConsulta' => $EnviosFacturaConsulta,
				'EnvioFacturaConsulta' => array(),
				'BuscadorConsulta' => $Buscador,
				'ParametroBetaSUNAT' => $parametroBetaSUNAT
			)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_enviofactura']=   $this->load->View('FacturacionElectronica/EnvioFactura/view_mainpanel_subcontent_buscador_enviofactura','',true);
		$view_subcontent['view_subcontent_consulta_enviofacturas'] =  $this->load->View('FacturacionElectronica/EnvioFactura/view_mainpanel_subcontent_consulta_enviofacturas',$view_sub_subcontent,true);
		$view_sub_subcontent_consulta['view_subcontent_buscador_enviofacturaconsulta']=   $this->load->View('FacturacionElectronica/EnvioFactura/view_mainpanel_subcontent_buscador_enviofacturaconsulta','',true);
		$view_subcontent['view_subcontent_consulta_enviofacturasconsulta'] =  $this->load->View('FacturacionElectronica/EnvioFactura/view_mainpanel_subcontent_consulta_enviofacturasconsulta',$view_sub_subcontent_consulta,true);

		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/EnvioFactura/view_mainpanel_footer_enviofactura',$view_data,true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/EnvioFactura/view_mainpanel_content_enviofactura',$view_subcontent,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	public function ConsultarComprobantesVentaEnvio()
	{
		$data = $this->input->post("Data");
		$data["CodigoSerie"] = (MOSTRAR_ENVIO_FACTURA_O_BOLETA == 1) ? CODIGO_SERIE_BOLETA : CODIGO_SERIE_FACTURA;
		$data["FechaInicio"]=convertToDate($data["FechaInicio"]);
		$data["FechaFin"]=convertToDate($data["FechaFin"]);
		$resultado = $this->sComprobanteElectronico->ConsultarComprobantesVentaEnvio($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarComprobantesVentaEnvioPendiente()
	{
		$data = $this->input->post("Data");
		$data["CodigoSerie"] = (MOSTRAR_ENVIO_FACTURA_O_BOLETA == 1) ? CODIGO_SERIE_BOLETA : CODIGO_SERIE_FACTURA;
		$data["FechaInicio"]=convertToDate($data["FechaInicio"]);
		$data["FechaFin"]=convertToDate($data["FechaFin"]);
		$resultado = $this->sComprobanteElectronico->ConsultarComprobantesVentaEnvioPendiente($data);
		echo $this->json->json_response($resultado);
	}

	public function EnviarComprobanteElectronico()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sComprobanteElectronico->ValidarComprobanteElectronico($data);
		echo $this->json->json_response($resultado);
	}

	public function GenerarComprobanteElectronico($datos)
	{
		try {
			$data = $datos;
			$resultado["error"] = "";

			$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
			//$this->sComunicacionBaja->ConsultarFacturasElectronicasConComunicacionBaja($data);
			$DatosCV = $data;

			$data["RazonSocialEmisor"] = $DatosEmpresa["RazonSocial"]; //Se toma la Razon Social de empresa
			$data = array_merge($data, $DatosEmpresa, $DatosCV);
			$data["Documento"] = $data["SerieDocumento"]."-".$data["NumeroDocumento"];

			/*CREANDO ARCHIVO JSON TO XML*/
			$data_documento["CodigoTipoDocumentoElectronico"] = $data["CodigoTipoDocumento"];
			$Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
			$nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
			$rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
			$rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
			$rutajson = $Plantillas_data->NombrePlantillaJSON;

			//Lineas del JSON NUEVO
			$nombre = $data["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
			$data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
			$data_json["plantilla"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
			$data_json["data"] = $data;

			$json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);

			$data_xml["codigotipodocumento"] = $data["CodigoTipoDocumento"];
			$data_xml["nombrearchivo"] = $nombre;
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
				return $this->json->json_response($resultado);
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

			/*ENVIANDO DOCUMENTO A SUNAT*/
			$envio = $this->sComprobanteElectronico->EnviarComprobanteElectronico($data_xml);

			$nombre_archivo = "R-".$nombre;
			$data_zip["Destino"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_ERROR"]; //URL DE DESTINO DE ARCHIVO
	    	$data_zip["UbicacionZIP"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_archivo.'.zip';  //URL DE UBICACION DEL ZIP
	    	$data_zip["File"] = $nombre_archivo.".xml";  //NOMBRE DEL ARCHIVO XML

			if(array_key_exists('Error', $envio))
			{
				$resultado["error"] = $envio["Error"];
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = $envio["FaultString"];
			}
			else{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);
				// print_r($respuesta);
				// exit;
				$data_final = array_merge($DatosCV, $envio);
				$data_final["NombreArchivoComprobante"] = $nombre;

				if($respuesta["CodigoRespuesta"] == "0")
				{
					$data_final["EstadoCPE"] = ESTADO_CPE_ACEPTADO;
					$resultado["title"] = "<strong>Éxito.</strong>";
					$resultado["estado"] = ESTADO_CPE_ACEPTADO;
					$resultado["type"] = "success";
					$resultado["clase"] = "notify-success";
					$resultado["message"] = "El comprobante electrónico fue aceptado.";
				}
				else
				{
					$data_final["EstadoCPE"] = ESTADO_CPE_RECHAZADO;
					$resultado["title"] = "<strong>Error!</strong>";
					$resultado["estado"] = ESTADO_CPE_RECHAZADO;
					$resultado["type"] = "danger";
					$resultado["clase"] = "notify-danger";
					$resultado["message"] = "Ocurrio un error con el comprobante electrónico.";
					// code...
				}
				//para guardar errores
				$data_final["CodigoError"] = $respuesta["CodigoRespuesta"];
				$data_final["DescripcionError"] = $respuesta["MensajeRespuesta"];

				$data_actualizar["IdComprobanteVenta"] = $data_final["IdComprobanteVenta"];
				$data_actualizar["IndicadorEstadoCPE"] = $data_final["EstadoCPE"];
				$data_actualizar["IndicadorEstado"] = $data["IndicadorEstado"];
				$data_actualizar["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
				$data_actualizar["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
				$data_actualizar["SerieDocumento"] = $data["SerieDocumento"];
				$actualizar_cv = $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($data_actualizar);
				$insertar_ce = $this->sComprobanteElectronico->InsertarComprobanteElectronico($data_final);
			}

			//ELIMINAMOS EL ARCHIVO ZIP CREADO
			$this->sComprobanteElectronico->EliminarZIPEnvio($data_xml);

			return $this->json->json_response($resultado);
		} catch (Exception $e) {
			$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = "Ocurrio un error con la comunicacion de baja.";
			return $this->json->json_response($resultado);
		}

	}

	public function EnviarXML($datos) {
		try {
			$this->db->trans_begin();
			$data = $datos;
			$resultado["error"] = "";

			$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

			$nombre = $DatosEmpresa["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
			if (file_exists(APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".xml")==false) {
				$resultado["error"] = "No se encuentra disponible el XML ".$nombre.".xml".". Consulte al Administrador";
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = $resultado["error"];
				$this->db->trans_rollback();
				return $this->json->json_response($resultado);
				exit;
			}

			/*GENERANDO XML*/
			$data_xml["codigotipodocumento"] = $data["CodigoTipoDocumento"];
			$data_xml["nombrearchivo"] = $nombre;
			$data_xml["tipoarchivo"] = ".xml";
			$data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];
			$ZIP = $this->sComprobanteElectronico->GenerarZIPEnvio($data_xml);

			if(array_key_exists('Error', $ZIP)) {
				$resultado["error"] = $ZIP["Error"];
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = $ZIP["msg"];
				$this->db->trans_rollback();
				return $this->json->json_response($resultado);
				exit;
			}
			/*ENVIANDO DOCUMENTO A SUNAT*/
			$envio = $this->sComprobanteElectronico->EnviarComprobanteElectronico($data_xml);

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
				// $resultado["tipoerror"] = $envio["TipoError"];
				// $resultado["estado"] = ESTADO_CPE_RECHAZADO;
				$this->db->trans_rollback();
				$envio["FaultCode"] = $envio["FaultCode"];
				$data_cpe["IdComprobanteElectronico"] = $data["IdComprobanteElectronico"];
				$data_cpe["CodigoError"] = $envio["FaultCode"];
				$data_cpe["DescripcionError"] = $envio["FaultString"];
				if(strpos($envio["FaultCode"], '1033') !== false)
				{
					$data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_EN_PROCESO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["estado"] = ESTADO_CPE_EN_PROCESO;
				}
				// $data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_RECHAZADO;
				$this->sComprobanteElectronico->ActualizarComprobanteElectronico($data_cpe);
				return $this->json->json_response($resultado);
				exit;
			}
			else{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);

				$data_final = array();
				$data_final["NombreArchivoComprobante"] = $nombre;
				$estado_cv = $data["IndicadorEstado"];
				$indicadorAnular = false;

				if($respuesta["CodigoRespuesta"] == "0") {
					$data_final["EstadoCPE"] = ESTADO_CPE_ACEPTADO;
					$resultado["title"] = "<strong>Éxito.</strong>";
					$resultado["estado"] = ESTADO_CPE_ACEPTADO;
					$resultado["type"] = "success";
					$resultado["clase"] = "notify-success";
					$resultado["message"] = "El comprobante electrónico fue aceptado.";
				}
				else {
					$data_final["EstadoCPE"] = ESTADO_CPE_RECHAZADO;
					$resultado["title"] = "<strong>Error!</strong>";
					$resultado["estado"] = ESTADO_CPE_RECHAZADO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["type"] = "danger";
					$resultado["clase"] = "notify-danger";
					$resultado["message"] = "Ocurrio un error con el comprobante electrónico.";

					if($respuesta["CodigoRespuesta"] >= CODIGO_INICIO_ERROR_RECHAZO && $respuesta["CodigoRespuesta"] <= CODIGO_FIN_ERROR_RECHAZO)
					{
						// $estado_cv = ESTADO_ANULADO_COMPROBANTE;
						$indicadorAnular = true;
						
						$response = $this->sErrorFacturacionElectronica->CodigoErrorEnListaCodigoError($respuesta["CodigoRespuesta"]);
						if(!empty($response))
						{
							if($response["IndicadorReenvioCPE"] == 1)
							{
								$data_final["EstadoCPE"] = ESTADO_GENERADO_COMPROBANTE;
								$resultado["title"] = "<strong>Vuelva a Modificar el Comprobante y Reenvíe - </strong>";
								$resultado["estado"] = ESTADO_CPE_RECHAZADO;
								$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
								$resultado["type"] = "danger";
								$resultado["clase"] = "notify-danger";
								$resultado["message"] = $response["NombreErrorFacturacionElectronica"];
								$indicadorAnular = false;
							}
						}
					}
				}
				$data_actualizar["CodigoError"] = $respuesta["CodigoRespuesta"];
				$data_actualizar["DescripcionError"] = $respuesta["MensajeRespuesta"];

				$data_actualizar["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
				$data_actualizar["IndicadorEstadoCPE"] = $data_final["EstadoCPE"];
				$data_actualizar["IndicadorEstado"] = $data["IndicadorEstado"];
				$data_actualizar["CodigoEstado"] = $data["CodigoEstado"];
				$data_actualizar["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
				$data_actualizar["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
				$data_actualizar["SerieDocumento"] = $data["SerieDocumento"];

				$data_actualizar_cv = $data_actualizar;
				$data_actualizar_cv["IndicadorEstado"] = $estado_cv;

				$actualizar_cv = $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($data_actualizar_cv);
				$actualizar_cpe = $this->sComprobanteElectronico->ConfirmarComprobanteElectronico($data_actualizar);
				
				if($indicadorAnular)
				{
					$data_anular = array_merge($data, $data_actualizar_cv);
					$data_anular["RazonSocial"] = "";
					$response = $this->restapiventa->AnularVenta($data_anular);
				}

				$this->db->trans_commit();
				$this->sComprobanteElectronico->EliminarZIPEnvio($data_xml);

				$resultado["cantidad"] = $this->sComprobanteElectronico->ConsultarCantidadFacturasNoEnviadasSunat();
			}

			return $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			$this->db->trans_rollback();
			$resultado["error"] = "Ocurrio error";
			$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			// $resultado["message"] = "Ocurrio un error con el envio factura.";
			$resultado["message"] = $e->getMessage();
			return $this->json->json_response($resultado);
		}

	}

	public function RecrearXML($datos) {
		try {
			$data_xml = $datos;
			/*ENVIANDO DOCUMENTO A SUNAT*/
			$envio = $this->sComprobanteElectronico->RecrearXMLComprobanteElectronico($data_xml);

			if(array_key_exists('Error', $envio))
			{
				$resultado["error"] = $envio["Error"];
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = "Error";
				return $this->json->json_response($resultado);
				exit;
			}
			else{
				$resultado["title"] = "<strong>Éxito.</strong>";
				$resultado["estado"] = ESTADO_CPE_ACEPTADO;
				$resultado["type"] = "success";
				$resultado["clase"] = "notify-success";
				$resultado["message"] = "El comprobante electrónico fue aceptado.";
			}
			return $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			$this->db->trans_rollback();
			$resultado["error"] = "Ocurrio error";
			$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			// $resultado["message"] = "Ocurrio un error con el envio factura.";
			$resultado["message"] = $e->getMessage();
			return $this->json->json_response($resultado);
		}

	}

	function ErrorController()
	{
		// set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context)
		// {
		//     // error was suppressed with the @-operator
		//     if (0 === error_reporting()) { return false;}
		//     switch($err_severity)
		//     {
		//         case E_ERROR:               throw new ErrorException            ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_WARNING:             throw new WarningException          ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_PARSE:               throw new ParseException            ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_NOTICE:              throw new NoticeException           ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_CORE_ERROR:          throw new CoreErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_CORE_WARNING:        throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_COMPILE_ERROR:       throw new CompileErrorException     ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_COMPILE_WARNING:     throw new CoreWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_USER_ERROR:          throw new UserErrorException        ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_USER_WARNING:        throw new UserWarningException      ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_USER_NOTICE:         throw new UserNoticeException       ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_STRICT:              throw new StrictException           ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_RECOVERABLE_ERROR:   throw new RecoverableErrorException ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_DEPRECATED:          throw new DeprecatedException       ($err_msg, 0, $err_severity, $err_file, $err_line);
		//         case E_USER_DEPRECATED:     throw new UserDeprecatedException   ($err_msg, 0, $err_severity, $err_file, $err_line);
		//     }
		// });
		set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context)
		{
			// echo $err_severity."\n".$err_msg."\n".$err_file."\n".$err_line;
		    // error was suppressed with the @-operator
		    if (0 === error_reporting()) { return false;}
		    switch($err_severity)
		    {
		        case E_ERROR:               throw new Exception($err_msg);
		        case E_WARNING:             throw new Exception($err_msg);
		        case E_PARSE:               throw new Exception($err_msg);
		        case E_NOTICE:              throw new Exception($err_msg);
		        case E_CORE_ERROR:          throw new Exception($err_msg);
		        case E_CORE_WARNING:        throw new Exception($err_msg);
		        case E_COMPILE_ERROR:       throw new Exception($err_msg);
		        case E_COMPILE_WARNING:     throw new Exception($err_msg);
		        case E_USER_ERROR:          throw new Exception($err_msg);
		        case E_USER_WARNING:        throw new Exception($err_msg);
		        case E_USER_NOTICE:         throw new Exception($err_msg);
		        case E_STRICT:              throw new Exception($err_msg);
		        case E_RECOVERABLE_ERROR:   throw new Exception($err_msg);
		        case E_DEPRECATED:          throw new Exception($err_msg);
		        case E_USER_DEPRECATED:     throw new Exception($err_msg);
		    }
		});
	}

	public function EnviarXMLSUNAT()
	{

		try {
			$this->ErrorController();
			$data = json_decode($this->input->post("Data"), true);

			$data_param['IdParametroSistema']= ID_GENERAR_ENVIAR_XML_SUNAT;
			$generar_enviar= $this->sParametroSistema->ObtenerParametroSistemaPorId($data_param);

			$resultado = "";
			if(is_numeric($generar_enviar[0]->ValorParametroSistema))
			{
				if($generar_enviar[0]->ValorParametroSistema == 0)
				{
					$resultado = $this->EnviarXML($data);
				}
				else if($generar_enviar[0]->ValorParametroSistema == 1){
					$resultado = $this->GenerarComprobanteElectronico($data);
				}
				else
				{
					$resultado = $this->RecrearXML($data);
				}
			}

			echo $resultado;
		} catch (Exception $ex) {
			$msg = $ex->getMessage() . $ex->getTraceAsString();
			// echo $msg;
			/* guardamos en el log de errores nuestro error, warning o notice */
			// error_log('ELASTICSEARCH ERROR: ' . $msg);
		}

		// restore_error_handler();
	}



	// función de gestión de errores
	// public function myFunctionErrorHandler($errno, $errstr, $errfile, $errline)
	// {
	// 	/* Según el típo de error, lo procesamos */
	// 	switch ($errno) {
	// 		 case E_WARNING:
	// 							echo "Hay un WARNING.<br />\n";
	// 							echo "El warning es: ". $errstr ."<br />\n";
	// 							echo "El fichero donde se ha producido el warning es: ". $errfile ."<br />\n";
	// 							echo "La línea donde se ha producido el warning es: ". $errline ."<br />\n";
	// 							/* No ejecutar el gestor de errores interno de PHP, hacemos que lo pueda procesar un try catch */
	// 							return true;
	// 							break;
	//
	// 					case E_NOTICE:
	// 							echo "Hay un NOTICE:<br />\n";
	// 							/* No ejecutar el gestor de errores interno de PHP, hacemos que lo pueda procesar un try catch */
	// 							return true;
	// 							break;
	//
	// 					default:
	// 							/* Ejecuta el gestor de errores interno de PHP */
	// 							return false;
	// 							break;
	// 					}
	// 	}

	public function ExportarPDF()
	{
		$data = $this->input->post("Data");
		//$data["IdEnvioFactura"] = $resultado;
		$data["NombreArchivoReporte"] = "NuevoReporte";
		$data["NombreArchivoJasper"] = "FacturaElectronicaModelo01";

		$resultado = $this->sComprobanteElectronico->GenerarReportePDF($data);
		echo $this->json->json_response($resultado["APP_RUTA"]);
	}

	public function InsertarEnvioFactura()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEnvioFactura->InsertarEnvioFactura($data);
		$data["IdEnvioFactura"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarEnvioFactura()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEnvioFactura->ActualizarEnvioFactura($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarEnvioFactura()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEnvioFactura->BorrarEnvioFactura($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarCDR()
	{
		$resultado = $this->sComprobanteElectronico->ConsultarEstadoCDR();
		print_r($resultado);
	}

	public function ConsultarCDRSunat() {
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado["error"] = "";

			$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

			$nombre = $DatosEmpresa["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
			// $nombre = "20559213854-01-F001-00000014";
			/*ENVIANDO DOCUMENTO A SUNAT*/
			$envio = $this->sComprobanteElectronico->ConsultarEstadoCDR($data);
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
				// $resultado["tipoerror"] = $envio["TipoError"];
				// $resultado["estado"] = ESTADO_CPE_RECHAZADO;
				$this->db->trans_rollback();
				// $envio["FaultCode"] = $envio["FaultCode"].'1033';
				// $data_cpe["IdComprobanteElectronico"] = $data["IdComprobanteElectronico"];
				// $data_cpe["CodigoError"] = $envio["FaultCode"].'1033';
				// $data_cpe["DescripcionError"] = $envio["FaultString"];
				// if(strpos($envio["FaultCode"], '1033') !== false)
				// {
				// 	$data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_EN_PROCESO;
				// 	$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
				// 	$resultado["estado"] = ESTADO_CPE_EN_PROCESO;
				// }
				// // $data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_RECHAZADO;
				// $this->sComprobanteElectronico->ActualizarComprobanteElectronico($data_cpe);
				echo $this->json->json_response($resultado);
				exit;
			}
			else{
				$respuesta =  $this->zipper->LeerZIPXML($data_zip);

				$data_final = array();
				$data_final["NombreArchivoComprobante"] = $nombre;
				$estado_cv = $data["IndicadorEstado"];
				$indicadorAnular = false;

				if($respuesta["CodigoRespuesta"] == "0") {
					$data_final["EstadoCPE"] = ESTADO_CPE_ACEPTADO;
					$resultado["title"] = "<strong>Éxito.</strong>";
					$resultado["estado"] = ESTADO_CPE_ACEPTADO;
					$resultado["type"] = "success";
					$resultado["clase"] = "notify-success";
					$resultado["message"] = "El comprobante electrónico fue aceptado.";
				}
				else {
					$data_final["EstadoCPE"] = ESTADO_CPE_RECHAZADO;
					$resultado["title"] = "<strong>Error!</strong>";
					$resultado["estado"] = ESTADO_CPE_RECHAZADO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["type"] = "danger";
					$resultado["clase"] = "notify-danger";
					$resultado["message"] = "Ocurrio un error con el comprobante electrónico.";
					$estado_cv = ESTADO_CPE_RECHAZADO;

					if($respuesta["CodigoRespuesta"] >= CODIGO_INICIO_ERROR_RECHAZO && $respuesta["CodigoRespuesta"] <= CODIGO_FIN_ERROR_RECHAZO)
					{
						// $estado_cv = ESTADO_ANULADO_COMPROBANTE;
						$indicadorAnular = true;
					}
				}
				$data_actualizar["CodigoError"] = $respuesta["CodigoRespuesta"];
				$data_actualizar["DescripcionError"] = $respuesta["MensajeRespuesta"];

				$data_actualizar["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
				$data_actualizar["IndicadorEstadoCPE"] = $data_final["EstadoCPE"];
				// $data_actualizar["IndicadorEstado"] = $data["IndicadorEstado"];
				// $data_actualizar["CodigoEstado"] = $data["CodigoEstado"];
				// $data_actualizar["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
				// $data_actualizar["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
				$data_actualizar["SerieDocumento"] = $data["SerieDocumento"];

				$data_actualizar_cv = $data_actualizar;
				$data_actualizar_cv["IndicadorEstado"] = $estado_cv;

				$actualizar_cv = $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($data_actualizar_cv);
				$actualizar_cpe = $this->sComprobanteElectronico->ConfirmarComprobanteElectronico($data_actualizar);

				if($indicadorAnular)
				{
					$data_anular = array_merge($data, $data_actualizar_cv);
					$data_anular["RazonSocial"] = "";
					$response = $this->restapiventa->AnularVenta($data_anular);
				}

				$this->db->trans_commit();
			}

			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			$this->db->trans_rollback();
			$resultado["error"] = "Ocurrio error";
			$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			// $resultado["message"] = "Ocurrio un error con el envio factura.";
			$resultado["message"] = $e->getMessage();
			echo $this->json->json_response($resultado);
		}

	}
}
