<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cPublicacionWeb extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Base");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("FacturacionElectronica/sServidorFTP");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Catalogo/sEmpleado");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('logger');
		$this->load->library('sesionusuario');
		$this->load->library('zipper');
		$this->load->helper('date');
		$this->load->service('Configuracion/General/sEmpresa');
	}


	public function Index()
	{
		$data["FechaInicio"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaFin"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["IndicadorEstadoPW"] = "%";
		// $data["CodigoSerie"] = CODIGO_SERIE_FACTURA;
		$data["IdTipoDocumento"] = "%";
		$PublicacionesWeb = $this->sComprobanteElectronico->ConsultarComprobantesElectronicoPublicacionWeb($data);
		$data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
		$data["FechaFin"] = $data["FechaInicio"];
		$Buscador = $data;

		$parametro["IdModuloSistema"] = ID_MODULO_VENTA;
		$TiposDocumento = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($parametro, 0);

		$Numero_Filas = 0;
		foreach ($PublicacionesWeb as $key => $value) {
			if ($PublicacionesWeb[$key]['IndicadorEstadoCPE'] == ESTADO_CPE_GENERADO) {
				$Numero_Filas++;
			}
		}

		$data = array(
			"data" =>
			array(
				'PublicacionesWeb' => $PublicacionesWeb,
				'PublicacionWeb' => array(),
				'Buscador' => $Buscador,
				'Numero_Filas' => $Numero_Filas,
				'TiposDocumento' => $TiposDocumento
			)
		);

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_publicacionweb'] =   $this->load->View('FacturacionElectronica/PublicacionWeb/view_mainpanel_subcontent_buscador_publicacionweb', '', true);
		$view_subcontent['view_subcontent_consulta_publicacionweb'] =  $this->load->View('FacturacionElectronica/PublicacionWeb/view_mainpanel_subcontent_consulta_publicacionweb', $view_sub_subcontent, true);


		$view['view_footer_extension'] = $this->load->View('FacturacionElectronica/PublicacionWeb/view_mainpanel_footer_publicacionweb', $view_data, true);
		$view['view_content_min'] =  $this->load->View('FacturacionElectronica/PublicacionWeb/view_mainpanel_content_publicacionweb', $view_subcontent, true);

		$this->load->View('.Master/master_view_mainpanel_min', $view);
	}

	public function ConsultarComprobantesVentaElectronico()
	{
		$data = $this->input->post("Data");
		// $data["CodigoSerie"] = CODIGO_SERIE_FACTURA;
		$data["FechaInicio"] = convertToDate($data["FechaInicio"]);
		$data["FechaFin"] = convertToDate($data["FechaFin"]);
		$resultado = $this->sComprobanteElectronico->ConsultarComprobantesElectronicoPublicacionWeb($data);

		echo $this->json->json_response($resultado);
	}

	public function EnviarXMLFTP()
	{
		try {

			$data = $this->input->post("Data");
			$resultado =  $this->SubirComprobanteElectronicoADominioPorFTP($data);
			echo $this->json->json_response($resultado);
		} catch (Exception $e) {
			/*$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = "Ocurrio un error con la publicacion web. ";
			*/
			echo $this->json->json_response_error($e);
			//echo $this->json->json_response($resultado);
		}
	}

	public function InsertarPublicacionWeb()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sPublicacionWeb->InsertarPublicacionWeb($data);
		$data["IdPublicacionWeb"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarPublicacionWeb()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sPublicacionWeb->ActualizarPublicacionWeb($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarPublicacionWeb()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sPublicacionWeb->BorrarPublicacionWeb($data);
		echo $this->json->json_response($resultado);
	}

	private function SubirComprobanteElectronicoADominioPorFTP($data)
	{
		$data_carpeta['IdGrupoParametro'] = ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
		
		// subir XML 
		$archivo_xml = $data["NombreArchivoComprobante"] . ".xml";
		$servidor_xml["NombreArchivo"] = $archivo_xml;
		$servidor_xml["RutaArchivo"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_XML"] . $archivo_xml;
		
		if (!file_exists($servidor_xml["RutaArchivo"])) {
			$resultado["error"] = "El Archivo $archivo_xml no existe. Consulte a su Administrador";
			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = "El archivo $archivo_xml no existe en el servidor.";

			$this->TemporalLog($resultado["message"]);

			return $resultado;
		}

		$envio_xml = $this->sServidorFTP->SubirArchivoFTP($servidor_xml);

		if (array_key_exists('Error', $envio_xml)) {
			$this->TemporalLog($envio_xml["Error"]);

			$resultado["error"] = $envio_xml["Error"];
			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = $envio_xml["msg"];

			return $resultado;
		}

		// subir PDF
		$pdf = $this->sComprobanteElectronico->GenerarReportePDF($data);

		if (!is_array($pdf)) {
			throw new Exception("Error Generando PDF", 1);
		}

		$archivopdf = $data["NombreArchivoComprobante"] . ".pdf";
		$servidor_pdf["NombreArchivo"] = $archivopdf;
		$servidor_pdf["RutaArchivo"] = $pdf["BASE_RUTA"];

		$enviopdf = $this->sServidorFTP->SubirArchivoFTP($servidor_pdf);

		if (array_key_exists('Error', $enviopdf)) {
			$this->TemporalLog($enviopdf["Error"]);

			$resultado["error"] = $enviopdf["Error"];
			$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = $enviopdf["msg"];

			return $resultado;
		}

		// envio CDR
		$archivo_cdr = "R-" . $data["NombreArchivoComprobante"] . ".zip";
		$servidor_cdr["NombreArchivo"] = $archivo_cdr;
		$servidor_cdr["RutaArchivo"] = APP_PATH . $DatosCarpeta["RUTA_CARPETA_CDR"] . $archivo_cdr;

		if (file_exists($servidor_cdr["RutaArchivo"])) {
			$envio_cdr = $this->sServidorFTP->SubirArchivoFTP($servidor_cdr);

			if (array_key_exists('Error', $envio_cdr)) {
				$this->TemporalLog($envio_cdr["Error"]);

				$resultado["error"] = $envio_cdr["Error"];
				$resultado["title"] = "<strong>Ha ocurrido un error.</strong>";
				$resultado["type"] = "danger";
				$resultado["clase"] = "notify-danger";
				$resultado["message"] = $envio_cdr["msg"];

				return $resultado;
			}
		}

		$resultado["error"] = "";
		$resultado["title"] = "<strong>Éxito.</strong>";
		$resultado["estado"] = ESTADO_PW_ENVIADO;
		$resultado["type"] = "success";
		$resultado["clase"] = "notify-success";
		$resultado["message"] = "El archivo " . $data["NombreArchivoComprobante"] . " fue subido correctamente.";
		
		$this->TemporalLog($resultado["message"]);
		$data_actualizar["IdComprobanteElectronico"] = $data["IdComprobanteElectronico"];
		$data_actualizar["IndicadorEstadoPublicacionWeb"] = ESTADO_PW_ENVIADO;
		$data_actualizar["FechaPublicacionWeb"] = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data_actualizar["UsuarioPublicacionWeb"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
		$this->sComprobanteElectronico->ActualizarComprobanteElectronico($data_actualizar);

		return $resultado;
	}

	public function ConsultarComprobantesElectronicoPendienteEnvioADominio()
	{
		$fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["FechaInicio"] = "2016-01-01";
		$data["FechaFin"] = $fechaservidor;
		$data["IndicadorEstadoPW"] = ESTADO_PW_PENDIENTE;
		$data["FilasPorPagina"] = 30;
		$resultado = $this->sComprobanteElectronico->ConsultarComprobantesElectronicoPublicacionWeb($data);
		echo $this->json->json_response($resultado);
	}

	public function EnviarPublicacionMasivaFTPAutomatico()
	{
		try {
			$data = $this->json->jsontoarray($_POST["data"]);
			$this->TemporalLog("Doc :" . $data["NombreArchivoComprobante"]);
			$resultado =  $this->SubirComprobanteElectronicoADominioPorFTP($data);
			echo $this->json->json_response($resultado);
		} catch (Exception $e) {
			$this->TemporalLog($e->getMessage());
			$resultado["title"] = "<strong>Error!</strong>";
			$resultado["type"] = "danger";
			$resultado["clase"] = "notify-danger";
			$resultado["message"] = "Ocurrio un error con la publicacion web.";
			echo $this->json->json_response($resultado);
		}
	}

	function TemporalLog($text)
	{
		if (CREACION_LOG_TEMPORAL == true) {
			$now = DateTime::createFromFormat('U.u', microtime(true));
			$fecha = (string) $now->format("Y-m-d");
			$data["name"] = "PublicacionWeb-" . $fecha . ".log";
			$data["url"] = APP_PATH . "assets/data/facturacionelectronica/error/";
			$data["header"] = $text;
			$data["body"] = $fecha;
			$data["footer"] = $fecha;

			$this->logger->CrearLog($data);
		}
	}
}
