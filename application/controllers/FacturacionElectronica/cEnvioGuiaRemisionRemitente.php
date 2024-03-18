<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cEnvioGuiaRemisionRemitente extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->model("Base");
		$this->load->library('logger');
		$this->load->library('json');
		$this->load->library('zipper');
		$this->load->library('form_validation');
		$this->load->library("RestApi/Venta/RestApiGuiaRemisionRemitente");
		$this->load->service("FacturacionElectronica/sGuiaRemisionRemitenteElectronica");
		$this->load->service("Catalogo/sEmpleado");
		$this->load->service("Venta/sGuiaRemisionRemitente");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service('Seguridad/sErrorFacturacionElectronica');
	}


	public function Index()
	{
		// $dataFecha = json_decode($this->input->get("Data"), true); // desde el mensaje de facturas por vencerce en el dashboard

		$data["NumeroDocumento"] = "%";
		$data["RazonSocial"] = "%";
		$data["FechaEmision"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaInicio"] = $this->Base->ObtenerFechaServidor("Y-m-d"); //is_array($dataFecha) ? $dataFecha["FechaInicio"] :$this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaFin"] = $this->Base->ObtenerFechaServidor("Y-m-d"); //is_array($dataFecha) ? $dataFecha["FechaFin"] :$this->Base->ObtenerFechaServidor("Y-m-d");
		$data["EstadoCPE"] = "%";
		// $data["CodigoSerie"] = (MOSTRAR_ENVIO_FACTURA_O_BOLETA == 1) ? CODIGO_SERIE_BOLETA : CODIGO_SERIE_FACTURA;
		$EnviosGuiaRemisionRemitente = $this->sGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteEnvio($data);
		$EnviosGuiaRemisionRemitenteConsulta = $this->sGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteEnvioPendiente($data);
		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = $data["FechaInicio"];
		$Buscador = $data;

		$Numero_Filas = 0;
		foreach ($EnviosGuiaRemisionRemitente as $key => $value) {
			// if ($EnviosGuiaRemisionRemitente[$key]['IndicadorEstadoCPE'] == ESTADO_CPE_GENERADO) {
			$Numero_Filas++;
			// }
		}

		$parametroBetaSUNAT = $this->sConstanteSistema->ObtenerParametroBetaSUNAT();

		$data = array(
			"data" =>
			array(
				'EnviosGuiaRemisionRemitente' => $EnviosGuiaRemisionRemitente,
				'EnvioGuiaRemisionRemitente' => array(),
				'Buscador' => $Buscador,
				'Numero_Filas' => $Numero_Filas,
				'EnviosGuiaRemisionRemitenteConsulta' => $EnviosGuiaRemisionRemitenteConsulta,
				'EnvioGuiaRemisionRemitenteConsulta' => array(),
				'BuscadorConsulta' => $Buscador,
				'ParametroBetaSUNAT' => $parametroBetaSUNAT
			)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_envioguiaremisionremitente'] =   $this->load->View('FacturacionElectronica/EnvioGuiaRemisionRemitente/view_mainpanel_subcontent_buscador_envioguiaremisionremitente', '', true);
		$view_subcontent['view_subcontent_consulta_envioguiasremisionremitente'] =  $this->load->View('FacturacionElectronica/EnvioGuiaRemisionRemitente/view_mainpanel_subcontent_consulta_envioguiasremisionremitente', $view_sub_subcontent, true);
		$view_sub_subcontent_consulta['view_subcontent_buscador_envioguiaremisionremitenteconsulta'] =   $this->load->View('FacturacionElectronica/EnvioGuiaRemisionRemitente/view_mainpanel_subcontent_buscador_envioguiaremisionremitenteconsulta', '', true);
		$view_subcontent['view_subcontent_consulta_envioguiasremisionremitenteconsulta'] =  $this->load->View('FacturacionElectronica/EnvioGuiaRemisionRemitente/view_mainpanel_subcontent_consulta_envioguiasremisionremitenteconsulta', $view_sub_subcontent_consulta, true);

		$view_footer['view_footer_extension'] = $this->load->View('FacturacionElectronica/EnvioGuiaRemisionRemitente/view_mainpanel_footer_envioguiaremisionremitente', $view_data, true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
		$view['view_content'] =  $this->load->View('FacturacionElectronica/EnvioGuiaRemisionRemitente/view_mainpanel_content_envioguiaremisionremitente', $view_subcontent, true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

		$this->load->View('.Master/master_view_mainpanel', $view);
	}

	public function ConsultarGuiasRemisionRemitenteEnvio()
	{
		$data = $this->input->post("Data");
		// $data["CodigoSerie"] = (MOSTRAR_ENVIO_FACTURA_O_BOLETA == 1) ? CODIGO_SERIE_BOLETA : CODIGO_SERIE_FACTURA;
		$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
		$data["FechaFin"] = convertToDate($data["FechaFin"]);
		$resultado = $this->sGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteEnvio($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarGuiasRemisionRemitenteEnvioPendiente()
	{
		$data = $this->input->post("Data");
		// $data["CodigoSerie"] = (MOSTRAR_ENVIO_FACTURA_O_BOLETA == 1) ? CODIGO_SERIE_BOLETA : CODIGO_SERIE_FACTURA;
		$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
		$data["FechaFin"] = convertToDate($data["FechaFin"]);
		$resultado = $this->sGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteEnvioPendiente($data);
		echo $this->json->json_response($resultado);
	}

	// public function EnviarGuiaRemisionRemitenteElectronica()
	// {
	// 	$data = $this->input->post("Data");
	// 	$resultado = $this->sGuiaRemisionRemitenteElectronica->ValidarGuiaRemisionRemitenteElectronica($data);
	// 	echo $this->json->json_response($resultado);
	// }

	// public function GenerarGuiaRemisionRemitenteElectronica($datos)
	// {
	// 	try {
	// 		$data = $datos;
	// 		$resultado["error"] = "";

	// 		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
	// 		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

	// 		$data_documento["IdEmpresa"] = ID_EMPRESA;
	// 		$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
	// 		$DatosCV = $data;

	// 		$data["RazonSocialEmisor"] = $DatosEmpresa["RazonSocial"]; //Se toma la Razon Social de empresa
	// 		$data = array_merge($data, $DatosEmpresa, $DatosCV);
	// 		$data["Documento"] = $data["SerieDocumento"]."-".$data["NumeroDocumento"];

	// 		/*CREANDO ARCHIVO JSON TO XML*/
	// 		$data_documento["CodigoTipoDocumentoElectronico"] = $data["CodigoTipoDocumento"];
	// 		$Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
	// 		$nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
	// 		$rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
	// 		$rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
	// 		$rutajson = $Plantillas_data->NombrePlantillaJSON;

	// 		//Lineas del JSON NUEVO
	// 		$nombre = $data["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
	// 		$data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
	// 		$data_json["plantilla"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
	// 		$data_json["data"] = $data;

	// 		$json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);

	// 		$data_xml["codigotipodocumento"] = $data["CodigoTipoDocumento"];
	// 		$data_xml["nombrearchivo"] = $nombre;
	// 		$data_xml["tipoarchivo"] = ".xml";
	// 		$data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];
	// 		$generar = $this->sGuiaRemisionRemitenteElectronica->GenerarXMLGuiaRemisionRemitente($data_xml,false);

	// 		if($generar["error"] != "")
	// 		{
	// 			$resultado["error"] = $generar["error"];
	// 			$resultado["title"] = "<strong>Ocurrio un error.</strong>";
	// 			$resultado["type"] = "danger";
	// 			$resultado["clase"] = "notify-danger";
	// 			$resultado["message"] = $generar["msg"];
	// 			return $this->json->json_response($resultado);
	// 			exit;
	// 		}

	// 		$ZIP = $this->sGuiaRemisionRemitenteElectronica->GenerarZIPEnvio($data_xml);

	// 		if($ZIP["error"] != "")
	// 		{
	// 			$resultado["error"] = $ZIP["Error"];
	// 			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
	// 			$resultado["type"] = "danger";
	// 			$resultado["clase"] = "notify-danger";
	// 			$resultado["message"] = $ZIP["msg"];
	// 			return $this->json->json_response($resultado);
	// 			exit;
	// 		}

	// 		/*ENVIANDO DOCUMENTO A SUNAT*/
	// 		$envio = $this->sGuiaRemisionRemitenteElectronica->EnviarGuiaRemisionRemitenteElectronica($data_xml);

	// 		$nombre_archivo = "R-".$nombre;
	// 		$data_zip["Destino"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_ERROR"]; //URL DE DESTINO DE ARCHIVO
	//     	$data_zip["UbicacionZIP"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_archivo.'.zip';  //URL DE UBICACION DEL ZIP
	//     	$data_zip["File"] = $nombre_archivo.".xml";  //NOMBRE DEL ARCHIVO XML

	// 		if(array_key_exists('Error', $envio))
	// 		{
	// 			$resultado["error"] = $envio["Error"];
	// 			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
	// 			$resultado["type"] = "danger";
	// 			$resultado["clase"] = "notify-danger";
	// 			$resultado["message"] = $envio["FaultString"];
	// 		}
	// 		else{
	// 			$respuesta =  $this->zipper->LeerZIPXML($data_zip);
	// 			// print_r($respuesta);
	// 			// exit;
	// 			$data_final = array_merge($DatosCV, $envio);
	// 			$data_final["NombreArchivoComprobante"] = $nombre;

	// 			if($respuesta["CodigoRespuesta"] == "0")
	// 			{
	// 				$data_final["EstadoCPE"] = ESTADO_CPE_ACEPTADO;
	// 				$resultado["title"] = "<strong>Éxito.</strong>";
	// 				$resultado["estado"] = ESTADO_CPE_ACEPTADO;
	// 				$resultado["type"] = "success";
	// 				$resultado["clase"] = "notify-success";
	// 				$resultado["message"] = "El comprobante electrónico fue aceptado.";
	// 			}
	// 			else
	// 			{
	// 				$data_final["EstadoCPE"] = ESTADO_CPE_RECHAZADO;
	// 				$resultado["title"] = "<strong>Error!</strong>";
	// 				$resultado["estado"] = ESTADO_CPE_RECHAZADO;
	// 				$resultado["type"] = "danger";
	// 				$resultado["clase"] = "notify-danger";
	// 				$resultado["message"] = "Ocurrio un error con el comprobante electrónico.";
	// 				// code...
	// 			}
	// 			//para guardar errores
	// 			$data_final["CodigoError"] = $respuesta["CodigoRespuesta"];
	// 			$data_final["DescripcionError"] = $respuesta["MensajeRespuesta"];

	// 			$data_actualizar["IdGuiaRemisionRemitente"] = $data_final["IdGuiaRemisionRemitente"];
	// 			$data_actualizar["IndicadorEstadoCPE"] = $data_final["EstadoCPE"];
	// 			$data_actualizar["IndicadorEstado"] = $data["IndicadorEstado"];
	// 			$data_actualizar["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
	// 			$data_actualizar["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
	// 			$data_actualizar["SerieDocumento"] = $data["SerieDocumento"];
	// 			$actualizar_cv = $this->sGuiaRemisionRemitente->ActualizarEstadoGuiaRemisionRemitente($data_actualizar);
	// 			$insertar_ce = $this->sGuiaRemisionRemitenteElectronica->InsertarGuiaRemisionRemitenteElectronica($data_final);
	// 		}

	// 		//ELIMINAMOS EL ARCHIVO ZIP CREADO
	// 		$this->sGuiaRemisionRemitenteElectronica->EliminarZIPEnvio($data_xml);

	// 		return $this->json->json_response($resultado);
	// 	} catch (Exception $e) {
	// 		$resultado["title"] = "<strong>Error!</strong>";
	// 		$resultado["type"] = "danger";
	// 		$resultado["clase"] = "notify-danger";
	// 		$resultado["message"] = "Ocurrio un error con la comunicacion de baja.";
	// 		return $this->json->json_response($resultado);
	// 	}

	// }

	public function EnviarXML($datos)
	{
		try {
			$this->db->trans_begin();
			$data = $datos;
			$resultado["error"] = "";
			// print_r($data);exit;
			$data_carpeta['IdGrupoParametro'] = ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

			$nombre = $DatosEmpresa["CodigoEmpresa"] . "-" . $data["CodigoTipoDocumento"] . "-" . $data["SerieDocumento"] . "-" . $data["NumeroDocumento"];
			if (file_exists(APP_PATH . $DatosCarpeta["RUTA_CARPETA_XML"] . $nombre . ".xml") == false) {
				$resultado["error"] = "No se encuentra disponible el XML " . $nombre . ".xml" . ". Consulte al Administrador";
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
			$data_xml["rutaenvio"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_XML"];
			// print_r($data_xml);exit;
			$ZIP = $this->sGuiaRemisionRemitenteElectronica->GenerarZIPEnvio($data_xml);

			if (array_key_exists('Error', $ZIP)) {
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
			$this->TemporalLog("Inicio envio guia , archivo :".$nombre);
			$envio = $this->sGuiaRemisionRemitenteElectronica->EnviarGuiaRemisionRemitenteElectronica($data_xml); 
			if(is_array($envio))
		        $cadena = print_r($envio,true);
      		else
        		$cadena = $envio;

			$this->TemporalLog("EnviarGuiaRemisionRemitenteElectronica:".$cadena);
			//print_r($envio);
			//exit;			
			$nombre_archivo = "R-" . $nombre;
			$data_zip["Destino"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_ERROR"]; //URL DE DESTINO DE ARCHIVO
			$data_zip["UbicacionZIP"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_CDR"] . $nombre_archivo . '.zip';
			$data_zip["File"] = $nombre_archivo . ".xml";  //NOMBRE DEL ARCHIVO XML

			if (array_key_exists('Error', $envio)) {
				$resultado["error"] = $envio["Error"];
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = $envio["FaultString"];
				// $resultado["tipoerror"] = $envio["TipoError"];
				// $resultado["estado"] = ESTADO_CPE_RECHAZADO;
				//$this->db->trans_rollback();
				$envio["FaultCode"] = $envio["FaultCode"];
				$data_cpe["IdGuiaRemisionRemitenteElectronica"] = $data["IdGuiaRemisionRemitenteElectronica"];
				$data_cpe["CodigoError"] = $envio["FaultCode"];
				$data_cpe["DescripcionError"] = $envio["FaultString"];

				$data_cpe["NumeroTicket"] = $envio["NumeroTicket"];
				$data_cpe["FechaRecepcion"] = $envio["FechaRecepcion"];
				if ($envio["FaultCode"] ==  '1033') {
					$data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_EN_PROCESO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["estado"] = ESTADO_CPE_EN_PROCESO;
				} else {
					$data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_RECHAZADO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["estado"] = ESTADO_CPE_RECHAZADO;
				}

				$this->sGuiaRemisionRemitenteElectronica->ActualizarGuiaRemisionRemitenteElectronica($data_cpe);
				$this->db->trans_commit();
				// $data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_RECHAZADO;				
				return $this->json->json_response($resultado);
			} else {

				if ($envio["CodigoRespuesta"] == "98") {
					$resultado["error"] = "El ENVIO ESTA EN PROCESO. INTENTE NUEVAMENTE";
					$data_cpe["IndicadorEstadoCPE"] = ESTADO_CPE_EN_PROCESO;
					$resultado["IndicadorEstadoCPE"] = ESTADO_CPE_EN_PROCESO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["estado"] = ESTADO_CPE_EN_PROCESO;
					$resultado["title"] = "<strong>Pendiente...</strong>";
					$resultado["type"] = "warning";
					$resultado["clase"] = "notify-warning";
					$resultado["message"] = "El envio de la guia esta en proceso. Dirijase a las Consultas de Guias y consulte nuevamente  hasta obtener una respuesta para el envio de guia.";
			
					$data_cpe["NumeroTicket"] = $envio["NumeroTicket"];
					$data_cpe["FechaRecepcion"] = $envio["FechaRecepcion"];
					$data_cpe["IdGuiaRemisionRemitenteElectronica"] = $data["IdGuiaRemisionRemitenteElectronica"];
					$this->sGuiaRemisionRemitenteElectronica->ActualizarGuiaRemisionRemitenteElectronica($data_cpe);
					$this->db->trans_commit();
					return $this->json->json_response($resultado);
				}

				$respuesta =  $this->zipper->LeerZIPXML($data_zip);

				$data_final = array();
				$data_final["NombreArchivoComprobante"] = $nombre;
				$estado_cv = $data["IndicadorEstado"];
				$indicadorAnular = false;

				if ($respuesta["CodigoRespuesta"] == "0") {
					$data_final["EstadoCPE"] = ESTADO_CPE_ACEPTADO;
					$resultado["title"] = "<strong>Éxito.</strong>";
					$resultado["estado"] = ESTADO_CPE_ACEPTADO;
					$resultado["type"] = "success";
					$resultado["clase"] = "notify-success";
					$resultado["message"] = "El comprobante electrónico fue aceptado.";
				} else {
					$data_final["EstadoCPE"] = ESTADO_CPE_RECHAZADO;
					$resultado["title"] = "<strong>Error!</strong>";
					$resultado["estado"] = ESTADO_CPE_RECHAZADO;
					$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
					$resultado["type"] = "danger";
					$resultado["clase"] = "notify-danger";
					$resultado["message"] = "Ocurrio un error con el comprobante electrónico.";

					if ($respuesta["CodigoRespuesta"] >= CODIGO_INICIO_ERROR_RECHAZO && $respuesta["CodigoRespuesta"] <= CODIGO_FIN_ERROR_RECHAZO) {
						// $estado_cv = ESTADO_ANULADO_COMPROBANTE;
						$indicadorAnular = true;

						$response = $this->sErrorFacturacionElectronica->CodigoErrorEnListaCodigoError($respuesta["CodigoRespuesta"]);
						if (!empty($response)) {
							if ($response["IndicadorReenvioCPE"] == 1) {
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

				$data_actualizar["IdGuiaRemisionRemitente"] = $data["IdGuiaRemisionRemitente"];
				$data_actualizar["IndicadorEstadoCPE"] = $data_final["EstadoCPE"];
				$data_actualizar["IndicadorEstado"] = $data["IndicadorEstado"];
				// $data_actualizar["CodigoEstado"] = $data["CodigoEstado"];
				// $data_actualizar["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
				// $data_actualizar["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
				$data_actualizar["SerieDocumento"] = $data["SerieDocumento"];

				$data_actualizar_cv = $data_actualizar;
				$data_actualizar_cv["IndicadorEstado"] = $estado_cv;
				$data_actualizar["NumeroTicket"] = $envio["NumeroTicket"];
				$data_actualizar["FechaRecepcion"] = $envio["FechaRecepcion"];

				$actualizar_cv = $this->sGuiaRemisionRemitente->ActualizarEstadoGuiaRemisionRemitente($data_actualizar_cv);
				$actualizar_cpe = $this->sGuiaRemisionRemitenteElectronica->ConfirmarGuiaRemisionRemitenteElectronica($data_actualizar);

				if ($indicadorAnular) {
					$data_anular = array_merge($data, $data_actualizar_cv);
					$data_anular["RazonSocial"] = "";
					$response = $this->restapiguiaremisionremitente->AnularGuiaRemisionRemitente($data_anular);
				}

				$this->db->trans_commit();
				$this->sGuiaRemisionRemitenteElectronica->EliminarZIPEnvio($data_xml);

				// $resultado["cantidad"] = $this->sGuiaRemisionRemitenteElectronica->ConsultarCantidadFacturasNoEnviadasSunat();
			}

			return $this->json->json_response($resultado);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$resultado["error"] = "Ocurrio error";
			$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = $e->getMessage() . " -  Linea : " . $e->getLine() . " - Codigo : " . $e->getCode();
			return $this->json->json_response($resultado);
		}
	}



	function ErrorController()
	{
		set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
			if (0 === error_reporting()) {
				return false;
			}
			switch ($err_severity) {
				case E_ERROR:
					throw new Exception($err_msg);
				case E_WARNING:
					throw new Exception($err_msg);
				case E_PARSE:
					throw new Exception($err_msg);
				case E_NOTICE:
					throw new Exception($err_msg);
				case E_CORE_ERROR:
					throw new Exception($err_msg);
				case E_CORE_WARNING:
					throw new Exception($err_msg);
				case E_COMPILE_ERROR:
					throw new Exception($err_msg);
				case E_COMPILE_WARNING:
					throw new Exception($err_msg);
				case E_USER_ERROR:
					throw new Exception($err_msg);
				case E_USER_WARNING:
					throw new Exception($err_msg);
				case E_USER_NOTICE:
					throw new Exception($err_msg);
				case E_STRICT:
					throw new Exception($err_msg);
				case E_RECOVERABLE_ERROR:
					throw new Exception($err_msg);
				case E_DEPRECATED:
					throw new Exception($err_msg);
				case E_USER_DEPRECATED:
					throw new Exception($err_msg);
			}
		});
	}

	public function EnviarXMLSUNAT()
	{
		try {
			$this->ErrorController();
			$data = json_decode($this->input->post("Data"), true);

			$data_param['IdParametroSistema'] = ID_GENERAR_ENVIAR_XML_SUNAT;
			$generar_enviar = $this->sParametroSistema->ObtenerParametroSistemaPorId($data_param);

			$resultado = "";
			if (is_numeric($generar_enviar[0]->ValorParametroSistema)) {
				if ($generar_enviar[0]->ValorParametroSistema == 0) {
					$resultado = $this->EnviarXML($data);
				}
				// else if($generar_enviar[0]->ValorParametroSistema == 1){
				// 	$resultado = $this->GenerarGuiaRemisionRemitenteElectronica($data);
				// }
				// else
				// {
				// 	$resultado = $this->RecrearXML($data);
				// }
			}

			echo $resultado;
		} catch (Exception $ex) {
			$msg = $ex->getMessage() . $ex->getTraceAsString();
		}
	}



	public function ConsultarCDRSunat()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado["error"] = "";

			$data_carpeta['IdGrupoParametro'] = ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

			$nombre = $DatosEmpresa["CodigoEmpresa"] . "-" . $data["CodigoTipoDocumento"] . "-" . $data["SerieDocumento"] . "-" . $data["NumeroDocumento"];

			/*ENVIANDO DOCUMENTO A SUNAT*/
			$envio = $this->sGuiaRemisionRemitenteElectronica->ConsultarEstadoCDR($data);
			if(is_array($envio))
				$cadena = print_r($envio,true);
		  	else
				$cadena = $envio;

			$this->TemporalLog("ConsultarEstadoCDR:".$cadena);
		
			$nombre_archivo = "R-" . $nombre;
			$data_zip["Destino"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_ERROR"]; //URL DE DESTINO DE ARCHIVO
			$data_zip["UbicacionZIP"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_CDR"] . $nombre_archivo . '.zip';
			$data_zip["File"] = $nombre_archivo . ".xml";  //NOMBRE DEL ARCHIVO XML

			if (array_key_exists('Error', $envio)) {
				$resultado["error"] = $envio["Error"];
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = $envio["FaultString"];
				$this->db->trans_rollback();
				echo $this->json->json_response($resultado);
				exit;
			} else {

				if (array_key_exists("status", $envio)) {
					if ($envio["status"] == "404") {
						$data_final["EstadoCPE"] = ESTADO_CPE_EN_PROCESO;
						$resultado["title"] = "<strong>Error!</strong>";
						$resultado["estado"] = ESTADO_CPE_EN_PROCESO;
						$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
						$resultado["type"] = "danger";
						$resultado["clase"] = "notify-danger";
						$resultado["message"] = $envio["message"];
						$estado_cv = ESTADO_CPE_EN_PROCESO;
						echo $this->json->json_response($resultado);
						return;						
					}
				} else {
					$respuesta =  $this->zipper->LeerZIPXML($data_zip);
					$data_final = array();
					$data_final["NombreArchivoComprobante"] = $nombre;
					$estado_cv = $data["IndicadorEstado"];
					$indicadorAnular = false;

					if(is_array($respuesta))
						$cadena = print_r($respuesta,true);
				  	else
						$cadena = $respuesta;
	
					$this->TemporalLog("LeerZIPXML:".$cadena);

					if ($respuesta["CodigoRespuesta"] == "0") {
						$data_final["EstadoCPE"] = ESTADO_CPE_ACEPTADO;
						$resultado["title"] = "<strong>Éxito.</strong>";
						$resultado["estado"] = ESTADO_CPE_ACEPTADO;
						$resultado["type"] = "success";
						$resultado["clase"] = "notify-success";
						$resultado["message"] = "El comprobante electrónico fue aceptado.";
						$estado_cv = ESTADO_ACTIVO;
					} else {
						$data_final["EstadoCPE"] = ESTADO_CPE_RECHAZADO;
						$resultado["title"] = "<strong>Error!</strong>";
						$resultado["estado"] = ESTADO_CPE_RECHAZADO;
						$resultado["tipoerror"] = CODIGO_ERROR_RECHAZO_SUNAT;
						$resultado["type"] = "danger";
						$resultado["clase"] = "notify-danger";
						$resultado["message"] = "Ocurrió un error con el comprobante electrónico.";
						$estado_cv = ESTADO_ACTIVO;

						if ($respuesta["CodigoRespuesta"] >= CODIGO_INICIO_ERROR_RECHAZO && $respuesta["CodigoRespuesta"] <= CODIGO_FIN_ERROR_RECHAZO) {
							// $estado_cv = ESTADO_ANULADO_COMPROBANTE;
							$indicadorAnular = true;
						}
					}

					$data_actualizar["CodigoError"] = $respuesta["CodigoRespuesta"];
					$data_actualizar["DescripcionError"] = $respuesta["MensajeRespuesta"];
					$data_actualizar["IdGuiaRemisionRemitente"] = $data["IdGuiaRemisionRemitente"];
					$data_actualizar["IndicadorEstado"] = $estado_cv;
					$data_actualizar["IndicadorEstadoCPE"] = $data_final["EstadoCPE"];
					$data_actualizar["SituacionCPE"] = $data_final["EstadoCPE"];
					$data_actualizar["SerieDocumento"] = $data["SerieDocumento"];

					$data_actualizar_cv = $data_actualizar;				
				}


				$actualizar_cv = $this->sGuiaRemisionRemitente->ActualizarEstadoGuiaRemisionRemitente($data_actualizar_cv);
				$actualizar_cpe = $this->sGuiaRemisionRemitenteElectronica->ConfirmarGuiaRemisionRemitenteElectronica($data_actualizar);

				if ($indicadorAnular) {
					$data_anular = array_merge($data, $data_actualizar_cv);
					$data_anular["RazonSocial"] = "";
					$response = $this->restapiguiaremisionremitente->AnularGuiaRemisionRemitente($data_anular);
				}

				$this->db->trans_commit();
			}

			echo $this->json->json_response($resultado);
			
		} catch (Exception $e) {
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

	function TemporalLog($text)
	{
	  if(CREACION_LOG_TEMPORAL == true)
	  {
		$now = DateTime::createFromFormat('U.u', microtime(true));
		$fecha = (String) $now->format("Y-m-d");
		$data["name"] = "log-guia-".$fecha.".log";
		$data["url"] = APP_PATH."assets/data/facturacionelectronica/error/";
		$data["header"] = $text;
		$data["body"] = $fecha;
		$data["footer"] = $fecha;
  
		$this->logger->CrearLog($data);
	  }
	}
}
