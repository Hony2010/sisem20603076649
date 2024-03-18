<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGuiaRemisionRemitenteElectronica extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("FacturacionElectronica/sGuiaRemisionRemitenteElectronica");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('zipper');
		$this->load->service("Configuracion/General/sEmpresa");
		$this->load->service('Configuracion/General/sParametroSistema');
		$this->load->service("Configuracion/General/sTipoDocumento");
		$this->load->service("Seguridad/sErrorFacturacionElectronica");
	}

	public function Index()
	{

	}

	public function ExportarPDF()
	{
		$data = json_decode($this->input->post("Data"), true);

		$data["NombreArchivoReporte"] = "NuevoReporte";
		// $data["NombreArchivoJasper"] = "FacturaElectronicaModelo01";

		$resultado = $this->sGuiaRemisionRemitenteElectronica->GenerarReportePDF($data);
		echo $this->json->json_response($resultado["APP_RUTA"]);
	}

	public function MostrarXML()
	{
		$data = $this->input->post("Data");
		$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
		$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

		$data_documento["IdEmpresa"] = ID_EMPRESA;
		$data_documento["CodigoTipoDocumentoElectronico"] = $data["CodigoTipoDocumento"];
		$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
		$archivo = $data["NombreArchivoComprobante"];
		$url_archivo =BASE_PATH."assets/data/facturacionelectronica/xml/".$archivo.".xml";
		$url_exist = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$archivo.".xml";
		$xml = "";
		if(file_exists($url_exist))
		{
			$xml = file_get_contents($url_archivo);
		}

		echo $xml;
	}

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

	//VALIDACIONES DE COMPROBANTES CPE
	// public function ValidarComprobobanteElectronico()
	// {
	// 	try {
	// 		$data = json_decode($this->input->post("Data"), true);

	// 		// $data["CodigoTipoDocumento"]="03";
	// 		// $data["SerieDocumento"]="B001";
	// 		// $data["NumeroDocumento"]="00000001";

	// 		// $data["CodigoDocumentoIdentidad"]="1";
	// 		// $data["NumeroDocumentoIdentidad"]="00000000";
	// 		// $data["FechaEmision"]="15/04/2019";
	// 		// $data["Total"]="80.00";

	// 		// $data["CodigoTipoDocumento"]="01";
	// 		// $data["SerieDocumento"]="F001";
	// 		// $data["NumeroDocumento"]="00000032";

	// 		// $data["CodigoDocumentoIdentidad"]="6";
	// 		// $data["NumeroDocumentoIdentidad"]="10012173348";
	// 		// $data["FechaEmision"]="15/04/2019";
	// 		// $data["Total"]="2619.60";
	// 		$resultado = $this->sGuiaRemisionRemitenteElectronica->ConsultarEstadoComprobanteValido($data);
	// 		if(array_key_exists('Error', $resultado))
	// 		{
	// 			$codigoArray = explode(":", $resultado["FaultCode"]);
	// 			if(count($codigoArray) > 0 && is_numeric($codigoArray[0]))
	// 			{
	// 				$data["CodigoError"] = (is_numeric($codigoArray[0])) ? (int) $codigoArray[0] : $codigoArray[0];
	// 				$response = $this->sErrorFacturacionElectronica->ObtenerDescripcionErrorCodigo($data);
	// 				$error = "";
	// 				if(count($response) > 0)
	// 				{
	// 					$error = $response[0]["NombreErrorFacturacionElectronica"];
	// 				}	
	// 				// $resultado["Mensaje"] = $error;
	// 				echo $this->json->json_response_error($error);
	// 			}
	// 			else
	// 			{
	// 				echo $this->json->json_response_error($resultado["Error"]);
	// 			}
	// 		}
	// 		else{
	// 			$resultado["Estado"] = $resultado["statusMessage"];
	// 			$resultado["Codigo"] = $resultado["statusCode"];
	// 			echo $this->json->json_response($resultado);
	// 		}
	// 	} 
	// 	catch (Exception $e) {
	// 		echo $this->json->json_response_error($e);
	// 	}
	// }

	//CONSULTAR ESTADO CPE, SOLO FACTURAS Y SUS NOTAS QUE EMPIECEN CON 'F'
	// public function ValidarEstadoComprobobanteElectronico()
	// {
	// 	try {
	// 		$data = json_decode($this->input->post("Data"), true);

	// 		// $data["CodigoTipoDocumento"]="01";
	// 		// $data["SerieDocumento"]="F001";
	// 		// $data["NumeroDocumento"]="00000032";
	// 		$resultado = $this->sGuiaRemisionRemitenteElectronica->ConsultarEstadoComprobante($data);
	// 		echo $this->json->json_response($resultado);
	// 	} 
	// 	catch (Exception $e) {
	// 		echo $this->json->json_response_error($e);
	// 	}
	// }

	// public function ConsultarFacturasNoEnviadasSunat()
	// {
	// 	try {
	// 		$resultado = $this->sGuiaRemisionRemitenteElectronica->ConsultarFacturasNoEnviadasSunat();
	// 		echo $this->json->json_response($resultado);
	// 	} 
	// 	catch (Exception $e) {
	// 		echo $this->json->json_response_error($e);
	// 	}
	// }

	// public function ValidarFechaVencimientoCertificadoDigital()
	// {
	// 	try {
	// 		$resultado = $this->sGuiaRemisionRemitenteElectronica->ValidarFechaVencimientoCertificadoDigital();
	// 		echo $this->json->json_response($resultado);
	// 	} 
	// 	catch (Exception $e) {
	// 		echo $this->json->json_response_error($e);
	// 	}
	// }
}
