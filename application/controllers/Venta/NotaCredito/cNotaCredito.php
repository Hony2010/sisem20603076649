<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cNotaCredito extends CI_Controller  {

	public $ParametroCaja;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper("date");
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('shared');
		$this->load->library('emailer');
		$this->load->library('sesionusuario');
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
		$this->load->service("Venta/sNotaCredito");
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->service("Venta/sDocumentoReferencia");

		$this->ParametroCaja = $this->sesionusuario->obtener_sesion_parametro_caja();
		if($this->ParametroCaja == 1)
		{
			$this->load->service("Caja/sCajaVenta");
		}
	}

	public function ConsultarComprobantesVentaPorCliente()
	{
		$input = $this->input->get("Data");
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$output["resultado"] = $this->sComprobanteVenta->ConsultarComprobantesVentaPorCliente($input);
		echo $this->json->json_response($output);
	}

	public function ConsultarDocumentosReferencia()
	{
		$data = $this->input->get("Data");
		$resultado= $this->sDocumentoReferencia->ConsultarDocumentosReferencia($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarNotaCreditoPorIdProducto()
	{
		$data["IdProducto"] = $this->input->post("Data");
		$resultado = $this->sNotaCredito->ConsultarNotaCreditoPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaNotaCreditoPorNombreProducto($data)
	{
		$q =$data;
		$data["textofiltro"] = $q;
		$resultado = $this->sNotaCredito->ConsultarSugerenciaNotaCreditoPorNombreProducto($data, 1);
		echo $this->json->json_response($resultado);
	}

  	public function ListarFamiliasProducto()
	{
		$resultado = $this->sFamiliaProducto->ListarFamiliasProducto();
		echo $this->json->json_response($resultado);
	}

	public function InsertarNotaCredito()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$data = json_decode($data, true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->InsertarVentaConCaja($data) : $this->sNotaCredito->InsertarNotaCredito($data);
			if(is_array($resultado)) {
				$resultado["CodigoMotivoNotaCredito"] = $data["CodigoMotivoNotaCredito"];
				$resultado["NombreMotivoNotaCredito"] = $data["NombreMotivoNotaCredito"];
				$resultado["MotivoNotaCredito"] = $data["MotivoNotaCredito"];
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
					$resultado2=$this->sNotaCredito->GenerarXMLNotaCredito($resultado);

					if(array_key_exists('error', $resultado2))
					{
						$resultado2 = $resultado2["error"];
					}
					else
					{
						$resultado["NombreArchivoComprobante"] = $resultado2["NombreArchivoComprobante"];
						$resultado["NombreAbreviado"] = $resultado2["NombreAbreviado"];
						$resultado["NombreTipoDocumento"] = $resultado2["NombreTipoDocumento"];
					}
				}
				else {
					$resultado2 = $resultado;
				}

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

	public function ActualizarNotaCredito()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$data = json_decode($data, true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->ActualizarVentaConCaja($data) : $this->sNotaCredito->ActualizarNotaCredito($data);
			if(is_array($resultado)) {
				$resultado["CodigoMotivoNotaCredito"] = $data["CodigoMotivoNotaCredito"];
				$resultado["NombreMotivoNotaCredito"] = $data["NombreMotivoNotaCredito"];
				$resultado["MotivoNotaCredito"] = $data["MotivoNotaCredito"];

				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {						
					$resultado2 = $this->sNotaCredito->GenerarXMLNotaCredito($resultado);
					
					if(array_key_exists('error', $resultado2)) {
						$resultado2 = $resultado2["error"];
					}
					else {
						$resultado["NombreArchivoComprobante"] = $resultado2["NombreArchivoComprobante"];
						$resultado["NombreAbreviado"] = $resultado2["NombreAbreviado"];
						$resultado["NombreTipoDocumento"] = $resultado2["NombreTipoDocumento"];
					}
				}
				else {					
					$resultado2 = $resultado;
				}

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

	public function BorrarNotaCredito()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sNotaCredito->BorrarNotaCredito($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerNotaCreditoPorIdProducto()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sNotaCredito->ObtenerNotaCreditoPorIdProducto($data);
		echo $this->json->json_response($resultado);
	}

	public function ObtenerConceptosPorMotivo()
	{
		$data = $this->input->post("Data");

		$resultado = $this->sConceptoNotaCreditoDebito->ObtenerConceptoPorMotivoNotaCredito($data);
		echo $this->json->json_response($resultado);
	}

	public function EnviarEmail()
	{
		try {
			$data = $this->input->post("Data");
			$response_pdf = $this->sComprobanteElectronico->GenerarReportePDF($data);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
			$Carpetas = $this->sConstanteSistema->ObtenerCarpetasSUNAT();
			$resultado = array();

			// $path = APP_PATH.'assets/img/logooooo1.png';
			// $type = pathinfo($path, PATHINFO_EXTENSION);
			// $data_image = file_get_contents($path);
			// $base64 = 'data:image/'.$type.';base64,'.base64_encode($data_image);

			// $data["logo_empresa"] = "image_png";//$base64;
			$data["logo_empresa"] = "";//$base64;

			$documento = $data["NombreTipoDocumento"]." ".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
			$data["nombre_empresa"]= $DatosEmpresa["RazonSocial"];
			$data["titulo"] = "ENVIO DE COMPROBANTE ELECTRONICO ".$documento;
			$total = number_format($data["Total"], NUMERO_DECIMALES_VENTA, '.', '');
			$data["mensaje"] = "Mediante este medio se hace el envío del comprobante electrónico correspondiente. <br/><br/>
				<b>Cliente:</b> ".$data["RazonSocial"]."<br/>
				<b>Tipo:</b> ".$data["NombreTipoDocumento"]."<br/>
				<b>Serie:</b> ".$data["SerieDocumento"]."<br/>
				<b>Numero:</b> ".$data["NumeroDocumento"]."<br/>
				<b>Monto:</b> ".$total."
				";

			$alias_destinatario = $data["RazonSocial"];
			$email_destinatario = $data["Email"];
			$adjunto[0]['archivo'] = APP_PATH.$Carpetas["RUTA_CARPETA_XML"].$data["NombreArchivo"].".xml";
			$adjunto[1]['archivo'] = $response_pdf["BASE_RUTA"];

			$titulo="ENVIO DE COMPROBANTE ELECTRONICO ".$documento;//FACTURA F001-0004
			$titulo = $this->shared->QuitarTildes($titulo);
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
			echo $this->json->json_response_error($e);
		}
	}

}
