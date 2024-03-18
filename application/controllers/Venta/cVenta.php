<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVenta extends CI_Controller  {

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
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->library('RestApi/Venta/RestApiComprobanteVenta');
		$this->load->service('Venta/sVenta');
		$this->load->service('Configuracion/General/sEmpresa');
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		
		$this->ParametroCaja = $this->sesionusuario->obtener_sesion_parametro_caja();
		if($this->ParametroCaja == 1)
		{
			$this->load->service("Caja/sCajaVenta");
		}
		else
		{
			$this->load->service("Venta/sVenta");
		}
	}

	public function Index()
	{

	}

	public function InsertarVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);

			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->InsertarVentaConCaja($data) : $this->sVenta->InsertarVenta($data);

			if(is_array($resultado)) {

				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
					$resultado2=$this->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);					
					$resultado["NombreArchivoComprobante"] = $resultado2["NombreArchivoComprobante"];
					$resultado["NombreAbreviado"] = $resultado2["NombreAbreviado"];
					$resultado["NombreTipoDocumento"] = $resultado2["NombreTipoDocumento"];
					$resultado["IdComprobanteElectronico"]=$resultado2["IdComprobanteElectronico"];					
				}
				else {
					$resultado2 = $resultado;
				}

				if(is_array($resultado2)) {
					$this->db->trans_commit();

					if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
						$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesComprobanteVenta'], true);
					}

					//SI LAS VALIDACIONES ESTAN CORRECTAS, SE GENERA EL JSON
					$this->restapicomprobanteventa->InsertarJSONDesdeComprobanteVenta($resultado);

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


	public function InsertarBoletaMasiva()
	{
		try {
			$this->db->trans_begin();
			$data = $_POST["Data"];

			$data1 = json_decode($data, true);
			$numero_item = count($data1) - 1;
			$i = 0;
			$validaciones = "";
			$texto = false;
			$productos = array();
			foreach ($data1 as $key => $value) {

				$value["IdComprobanteVenta"] = '';
				
				$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->InsertarVentaConCaja($value) : $this->sVenta->InsertarVenta($value);

				$destruir = false;
				if($i == $numero_item)
				{
					$destruir = true;
				}
				if(is_array($resultado) && strpos($value["SerieDocumento"], CODIGO_SERIE_BOLETA) !== false) {
					$resultado2=$this->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado, $destruir);
				}

				if(!is_array($resultado))
				{
					$validaciones .= $value["SerieDocumento"].'-'.$value["NumeroDocumento"]."<br/>".$resultado."<br><br>";
					$texto = true;
				}
				else {
					foreach ($resultado["DetallesComprobanteVenta"] as $key4 => $value4) {
						array_push($productos, $value4["IdProducto"]);
					}
				}
				$i++;
			}

			if($texto == false)
			{
				// $this->db->trans_rollback();
				$this->db->trans_commit();
				if(count($productos)>0)
				{
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($productos);
				}

				$resultado = "Éxito.";
				echo $this->json->json_response($resultado);
			}
			else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($validaciones);
			}

		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ActualizarVenta()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->ActualizarVentaConCaja($data) : $this->sVenta->ActualizarVenta($data);
			if(is_array($resultado)) {
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
					$resultado=$this->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);
				}
				if(is_array($resultado)) {
					$this->db->trans_commit();
					if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
						$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['CopiaIdProductosDetalle']);
					}

					//SI LAS VALIDACIONES ESTAN CORRECTAS, SE GENERA EL JSON
					$this->restapicomprobanteventa->ActualizarJSONDesdeComprobanteVenta($resultado);

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

	public function AnularVenta() {
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->AnularVentaConCaja($data) : $this->sVenta->AnularVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteVenta'], true);
				}
				
				//SI LAS VALIDACIONES ESTAN CORRECTAS, SE GENERA EL JSON
				$this->restapicomprobanteventa->BorrarJSONDesdeComprobanteVenta($resultado);

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

	public function BorrarVenta() {
		try {
			$this->db->trans_begin();
			$data = json_decode($this->input->post("Data"), true);
			$resultado = ($this->ParametroCaja == 1) ? $this->sCajaVenta->EliminarVentaConCaja($data) : $this->sVenta->EliminarVenta($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				$input = $data["Filtros"];
				$input["FechaInicio"]=convertToDate($data["Filtros"]["FechaInicio"]);
				$input["FechaFin"]=convertToDate($data["Filtros"]["FechaFin"]);
				$input["textofiltro"]=($input["textofiltro"] != "") ? $input["textofiltro"] : '%' ;

				$data["Filtros"]["totalfilas"] = $this->sComprobanteVenta->ObtenerNumeroTotalComprobantesVenta($input);

				if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
					$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteVenta'], true);
				}

				//SI LAS VALIDACIONES ESTAN CORRECTAS, SE GENERA EL JSON
				$this->restapicomprobanteventa->BorrarJSONDesdeComprobanteVenta($resultado);
				
				$resultado = $data;
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

	public function EnviarEmail()
	{
		try {
			$data = $this->input->post("Data");
			$response_pdf = $this->sComprobanteElectronico->GenerarReportePDF($data);

			$data_documento["IdEmpresa"] = ID_EMPRESA;
			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
			$Carpetas = $this->sConstanteSistema->ObtenerCarpetasSUNAT();
			$resultado = array();
			$data["logo_empresa"] = "";

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
			$adjunto[0]['archivo'] = $response_pdf["BASE_RUTA"];
			
			$ruta_xml=APP_PATH.$Carpetas["RUTA_CARPETA_XML"].$data["NombreArchivo"].".xml";
			if(file_exists($ruta_xml) == true) {
				$adjunto[1]['archivo'] = $ruta_xml;
			}
			$ruta_cdr=APP_PATH.$Carpetas["RUTA_CARPETA_CDR"]."R-".$data["NombreArchivo"].".zip";						
			if(file_exists($ruta_cdr) == true) {
				$adjunto[2]['archivo'] = $ruta_cdr;				
			}			
			
			$titulo="ENVIO DE COMPROBANTE ELECTRONICO ".$documento;
			$mensaje = $this->load->view('.Master/view_contacto_solicitud',$data,true);

			$resultado = $this->emailer->send_mail($titulo, $mensaje, $email_destinatario ,$alias_destinatario,$adjunto);

			if($resultado == '1')
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

	public function AplicarPrecioEspecialCliente(){
		$data = $this->input->post("Data");
		$resultado = $this->sVenta->AplicarPrecioEspecialCliente($data);
		echo $this->json->json_response($resultado);
	}	


	public function ExportarVentas() {
		try {
			$data[0]["FechaInicio"]="01/06/2020";
			$data[0]["FechaFin"]="30/06/2020";
			$data[1]["FechaInicio"]="01/07/2020";
			$data[1]["FechaFin"]="31/07/2020";
			$data[2]["FechaInicio"]="01/08/2020";
			$data[2]["FechaFin"]="31/08/2020";
			$data[3]["FechaInicio"]="01/09/2020";
			$data[3]["FechaFin"]="30/09/2020";
			$data[4]["FechaInicio"]="01/10/2020";
			$data[4]["FechaFin"]="31/10/2020";
			$data[5]["FechaInicio"]="01/11/2020";
			$data[5]["FechaFin"]="30/11/2020";
			
			foreach($data as $key => $value) {
				$resultado = $this->sVenta->PrepararVentas($value);			
				$nArchivo = "ventas".convertToDate($value["FechaInicio"])."-".convertToDate($value["FechaFin"]);
				$extArchivo = ".json";
				$nombreArchivo = $nArchivo.$extArchivo;
				$archivo = APP_PATH."assets/data/archivo/".$nombreArchivo;
				$archivoweb = APP_PATH_URL."assets/data/archivo/".$nombreArchivo;
				$json = $this->json->CrearArchivoJSONData($archivo, $resultado);
				$response["nombre"] = $nArchivo;
				$response["extension"] = $extArchivo;
				$response["archivo"] = $nombreArchivo;
				$response["file"] = $archivoweb;
	
				echo $this->json->json_response($response);
			}
		}
		catch (Exception $e) {
		 	echo $this->json->json_response_error($e);
		}
	}

	public function ObtenerComprobanteVentaPorId() {		
		$data = $this->input->get("Data");
		$resultado = $this->sVenta->ConsultarComprobanteVentaPorId($data);
		echo $this->json->json_response($resultado);
	}
	

	function ConsultarComprobantesGuia() {
		try {
			$data = $this->input->get("Data");
			$resultado = $this->sVenta->ConsultarComprobantesGuia($data);
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			 echo $this->json->json_response_error($e);
		}
	}
}
