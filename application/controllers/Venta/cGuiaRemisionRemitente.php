<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cGuiaRemisionRemitente extends CI_Controller  {

	public $ParametroCaja;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('emailer');
		$this->load->library('sesionusuario');
		$this->load->library('RestApi/Venta/RestApiComprobanteVenta');
		$this->load->service('Configuracion/General/sEmpresa');
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("Venta/sGuiaRemisionRemitente");
		$this->load->service("Venta/sDetalleGuiaRemisionRemitente");
		$this->load->service("FacturacionElectronica/sGuiaRemisionRemitenteElectronica");
	}

	public function Index() {
		
	}

	public function InsertarGuiaRemisionRemitente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = $this->sGuiaRemisionRemitente->InsertarGuiaRemisionRemitente($data);
			
			if(is_array($resultado)) {
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'T') {
					$resultado2 =$this->sGuiaRemisionRemitenteElectronica->GenerarXMLGuiaRemisionRemitenteElectronica($resultado);

					$resultado["NombreArchivoComprobante"] = $resultado2["NombreArchivoComprobante"];
					$resultado["NombreAbreviado"] = $resultado2["NombreAbreviado"];
					// $resultado["NombreTipoDocumento"] = $response["NombreTipoDocumento"];
				}
				else {
					$resultado2 = $resultado;
				}

				if(is_array($resultado2)) {
					$this->db->trans_commit();

					$response = $this->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);

					echo $this->json->json_response($resultado);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($response);
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

	public function ActualizarGuiaRemisionRemitente()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sGuiaRemisionRemitente->ActualizarGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'T') {					
					$resultado=$this->sGuiaRemisionRemitenteElectronica->GenerarXMLGuiaRemisionRemitenteElectronica($resultado);
				}
				if(is_array($resultado)) {
					$this->db->trans_commit();
					$response = $this->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);
					echo $this->json->json_response($resultado);					
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado);
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

	public function AnularGuiaRemisionRemitente() {
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sGuiaRemisionRemitente->AnularGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				$response = $this->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);
				echo $this->json->json_response($resultado);				
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

	public function BorrarGuiaRemisionRemitente() {
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sGuiaRemisionRemitente->BorrarGuiaRemisionRemitente($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				$input = $data["Filtros"];
				$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
				$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
				$input["TextoFiltro"]=($input["TextoFiltro"] != "") ? $input["TextoFiltro"] : '%' ;				
				$data["Filtros"]["totalfilas"] = $this->sGuiaRemisionRemitente->ObtenerNumeroTotalGuiasRemisionRemitente($input);
				$resultado = $data;
				$response = $this->restapicomprobanteventa->ActualizarJSONComprobantesVentaDesdeGuiaRemisionRemitente($resultado);				
				echo $this->json->json_response($resultado);				
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

	public function ConsultarComprobanteVenta() {
		try {
			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sComprobanteVenta->ObtenerComprobanteVenta($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			 echo $this->json->json_response_error($e);
		}
	}

	public function ImprimirGuiaRemisionRemitente() {
		try {
			$data = $this->input->post("Data");
			
			$resultado = $this->sGuiaRemisionRemitente->ImprimirReporteGuiaRemisionRemitente($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}
	}

	public function EnviarEmail() {

		try {
			$data = $this->input->post("Data");
			$response_pdf = $this->sGuiaRemisionRemitenteElectronica->GenerarReportePDF($data);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
			$Carpetas = $this->sConstanteSistema->ObtenerCarpetasSUNAT();
			$resultado = array();
			$data["logo_empresa"] = "";

			$documento = $data["NombreTipoDocumento"]." ".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
			$data["nombre_empresa"]= $DatosEmpresa["RazonSocial"];
			$data["titulo"] = "ENVIO DE GUIA REMISION ELECTRONICA ".$documento;
			
			$data["mensaje"] = "Mediante este medio se hace el envío de la guía remisión electrónico correspondiente. <br/><br/>
				<b>Cliente:</b> ".$data["RazonSocial"]."<br/>
				<b>Tipo:</b> ".$data["NombreTipoDocumento"]."<br/>
				<b>Serie:</b> ".$data["SerieDocumento"]."<br/>
				<b>Numero:</b> ".$data["NumeroDocumento"]."<br/>";

			$alias_destinatario = $data["RazonSocial"];
			$email_destinatario = $data["Email"];
			$adjunto[0]['archivo'] = APP_PATH.$Carpetas["RUTA_CARPETA_XML"].$data["NombreArchivo"].".xml";
			$adjunto[1]['archivo'] = $response_pdf["BASE_RUTA"];
			$ruta_cdr=APP_PATH.$Carpetas["RUTA_CARPETA_CDR"]."R-".$data["NombreArchivo"].".zip";
						
			if(file_exists($ruta_cdr) == true) {
				$adjunto[2]['archivo'] = $ruta_cdr;				
			}			
			
			$titulo="ENVIO DE GUIA REMISIÓN ELECTRONICO ".$documento;//FACTURA F001-0004
			$mensaje = $this->load->view('.Master/view_contacto_solicitud',$data,true);

			$resultado = $this->emailer->send_mail($titulo, $mensaje, $email_destinatario ,$alias_destinatario,$adjunto);

			if($resultado)
			{
				$response["title"] = "<strong>Éxito.</strong>";
				$response["type"] = "success";
				$response["clase"] = "notify-success";
				$response["message"] = "El email fue enviado.";
				echo $this->json->json_response($response);
			}
			else {
				$response["title"] = "<strong>Error!</strong>";
				$response["type"] = "danger";
				$response["clase"] = "notify-danger";
				$response["message"] = "Ocurrio un error al enviar email.";
				echo $this->json->json_response($response);
			}
		} catch (Exception $e) {
			$response["title"] = "<strong>Error!</strong>";
			$response["type"] = "danger";
			$response["clase"] = "notify-danger";
			$response["message"] = $e->getMessage();
			echo $this->json->json_response($response);
		}
	}

}
