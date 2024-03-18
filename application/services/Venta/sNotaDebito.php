<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sNotaDebito extends sComprobanteVenta {

        public $DetalleNotaDebito = array();

        public function __construct()
        {
          parent::__construct();

      		$this->load->service("Venta/sDocumentoReferencia");
      		$this->load->service("Venta/sDetalleNotaDebito");
      		$this->load->service("Configuracion/Venta/sMotivoNotaDebito");
      		$this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
          $this->load->service('Configuracion/FacturacionElectronica/sTipoDocumentoElectronico');
          $this->load->service('FacturacionElectronica/sComprobanteElectronico');
      		$this->load->service('Configuracion/General/sEmpresa');
      		$this->load->service("Catalogo/sCliente");
      		// $this->load->service("Inventario/sNotaEntrada");
          $DetalleNotaDebito = [];
          $DetalleNotaDebito[] = $this->sDetalleNotaDebito->Cargar();
          $this->ComprobanteVenta["DetallesNotaDebito"] = $DetalleNotaDebito;
        }

        function CargarNotaDebito()
        {
          $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTADEBITO;
          $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          $resultado = parent::Cargar($parametro);

          $resultado["IdCorrelativoDocumento"] = (count($resultado["SeriesDocumento"])>0) ? $resultado["SeriesDocumento"][0]["IdCorrelativoDocumento"] : "";
					$resultado["SerieDocumento"] = (count($resultado["SeriesDocumento"]) > 0) ? $resultado["SeriesDocumento"][0]["SerieDocumento"] : "";
					
          $fechaservidor=$this->Base->ObtenerFechaServidor("d/m/Y");

          $resultado["TotalSaldo"] = "00.00";
      		$resultado["Concepto"] = "";
      		$resultado["Porcentaje"] = "0.00";
      		$resultado["Importe"] = "0.00";
      		$resultado["IdSede"] = "";
      		$resultado["FechaIngreso"] = $fechaservidor;
      		$MotivosNotaDebito = $this->sMotivoNotaDebito->ListarMotivosNotaDebito();
      		$ConceptosNotaDebito = $this->sConceptoNotaCreditoDebito->ListarConceptosNotaDebito();

      		$resultado["MotivosNotaDebito"] = $MotivosNotaDebito;
      		$resultado["ConceptosNotaDebito"] = $ConceptosNotaDebito;
      		$resultado["BusquedaComprobantesVentaND"] = array();
      		$resultado["BusquedaComprobanteVentaND"] = array();
      		$resultado["MiniComprobantesVentaND"] = array();
      		$resultado["GrupoDetalleComprobanteVenta"] = array();
          $resultado["DocumentosReferencia"] = array();
          $resultado["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();

          $resultado['NuevoDetalleNotaDebito']=$this->sDetalleNotaDebito->Cargar();


          return $resultado;
        }


        /*****/
        function InsertarNotaDebito($data)
        {
          $data["DetallesComprobanteVenta"] = $data["DetallesNotaDebito"];

          $resultado = parent::InsertarComprobanteVenta($data);

    			if(is_array($resultado))
    			{
            $data["IdComprobanteVenta"] = $resultado["IdComprobanteVenta"];
    				if(array_key_exists("MiniComprobantesVentaND", $data))
    				{
              $this->InsertarDocumentosReferencia($data);
    				}
    				$resultado["DocumentosReferencia"] = $data["MiniComprobantesVentaND"];
    				return $resultado;
    			}
    			else{
    				$data_error["error"]["msg"] = $resultado;
    				return $data_error;
    			}
        }

        function InsertarDocumentosReferencia($data)
        {
          foreach ($data["MiniComprobantesVentaND"] as $key => $value) {
						$value["IdComprobanteNota"] = $data["IdComprobanteVenta"];
						$documentoreferencia = $this->sDocumentoReferencia->InsertarDocumentoReferencia($value);
					}
          return "";
        }

        function ActualizarNotaDebito($data)
        {
          $data["DetallesComprobanteVenta"] = $data["DetallesNotaDebito"];
          // $resultado = $this->sNotaEntrada->BorrarNotasEntradasDesdeComprobante($data);
          // print_r($resultado);
          // exit;
          //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
          $this->sDocumentoReferencia->BorrarDocumentoReferencia($data);
          $otra_data = $data;
    			foreach ($otra_data["DetallesComprobanteVenta"] as $key => $value) {
    				$otra_data["DetallesComprobanteVenta"][$key]["SaldoPendienteNotaCredito"] = 0;
            $otra_data["DetallesComprobanteVenta"][$key]["SaldoPendienteEntrada"] = $value["Cantidad"];
    			}
          $resultado = parent::ActualizarComprobanteVenta($otra_data);

          if(is_array($resultado))
    			{
						$resultado["AbreviaturaSituacionCPE"] = $resultado["AbreviaturaSituacionCPE"]->AbreviaturaSituacionComprobanteElectronicoVentas; 
            $data["IdComprobanteVenta"] = $resultado["IdComprobanteVenta"];
    				if(array_key_exists("MiniComprobantesVentaND", $data))
    				{
              $this->InsertarDocumentosReferencia($data);
    				}
    				$resultado["DocumentosReferencia"] = $data["MiniComprobantesVentaND"];
    				return $resultado;
    			}
    			else{
    				$data_error["error"]["msg"] = $resultado;
    				return $data_error;
    			}

        }

        function GenerarXMLNotaDebito($data)
      	{
      		try {
      			$resultado["error"] = "";
            $pre_data = $data;
            $data = $this->sComprobanteVenta->ObtenerComprobanteVenta($pre_data);
            if($pre_data["MotivoNotaDebito"]["BorrarDetalles"] == 1)
            {
              $documentoreferencia = $pre_data["DocumentosReferencia"][0];
              $data["DetallesComprobanteVenta"] = $pre_data["DetallesComprobanteVenta"];

              $data["DetallesComprobanteVenta"][0]["SumaTributo"] = 0;
              $data["DetallesComprobanteVenta"][0]["ValorVentaItem"] = $data["Total"];
              $data["DetallesComprobanteVenta"][0]["SumatoriaICBP"] = 0;
              $data["DetallesComprobanteVenta"][0]["FactorICBP"] = 0;

              if($data["IGV"] > 0)
              {
                $data["DetallesComprobanteVenta"][0]["CodigoTipoTributo"] = "1000";//$documentoreferencia["DetallesComprobanteVenta"][0]["CodigoTipoTributo"];
                $data["DetallesComprobanteVenta"][0]["NombreTributo"] = "IGV";//$documentoreferencia["DetallesComprobanteVenta"][0]["NombreTributo"];
                $data["DetallesComprobanteVenta"][0]["CodigoInternacional"] = "VAT";//$documentoreferencia["DetallesComprobanteVenta"][0]["CodigoInternacional"];
                $data["DetallesComprobanteVenta"][0]["PorcentajeIGV"] = PORCENTAJE_IGV;//$documentoreferencia["DetallesComprobanteVenta"][0]["PorcentajeIGV"];
                $data["DetallesComprobanteVenta"][0]["TasaISC"] = $documentoreferencia["DetallesComprobanteVenta"][0]["TasaISC"];

                $data["DetallesComprobanteVenta"][0]["IGVItem"] = $data["IGV"];
                $data["DetallesComprobanteVenta"][0]["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;
                $data["DetallesComprobanteVenta"][0]["ValorVentaItem"] = $data["ValorVentaGravado"];
                $data["DetallesComprobanteVenta"][0]["SumaTributo"] = $data["IGV"];
              }
              else {
                $data["DetallesComprobanteVenta"][0]["CodigoTipoTributo"] = "9997";
                $data["DetallesComprobanteVenta"][0]["NombreTributo"] = "EXO";
                $data["DetallesComprobanteVenta"][0]["CodigoInternacional"] = "VAT";
                $data["DetallesComprobanteVenta"][0]["PorcentajeIGV"] = "0.00";
                $data["DetallesComprobanteVenta"][0]["TasaISC"] = "0.00";
                $data["DetallesComprobanteVenta"][0]["CodigoTipoAfectacionIGV"] = "20";
              }
            }

            $data["DocumentosReferencia"] = $pre_data["DocumentosReferencia"];
            $data["CodigoMotivoNotaDebito"] = $pre_data["CodigoMotivoNotaDebito"];
            $data["NombreMotivoNotaDebito"] = $pre_data["NombreMotivoNotaDebito"];

      			$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
      			$DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

      			$data_documento["IdEmpresa"] = ID_EMPRESA;
      			$DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
      			$DatosCV = $data;
      			//OBTENEMOS DATA DE LA TABLA CLIENTE DEL CLIENTE DE LA NOTA CREDITO
      			$consulta_cliente["IdPersona"] = $data["IdCliente"];
      			$DatosCliente = (array) $this->sCliente->ObtenerClientePorIdPersona($consulta_cliente);

      			$data["RazonSocialEmisor"] = $DatosEmpresa["RazonSocial"]; //Se toma la Razon Social de empresa
      			$data["RazonSocialCliente"] = $DatosCliente["RazonSocial"];
      			$data = array_merge($data, $DatosCV, $DatosCliente, $DatosEmpresa);
      			$data["Documento"] = $data["SerieDocumento"]."-".$data["NumeroDocumento"];

      			/*CREANDO ARCHIVO JSON TO XML*/
      			$data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      			$data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);

      			$data["DocumentoReferencia"] = $DatosCV["DocumentosReferencia"][0]["SerieDocumento"]."-".$DatosCV["DocumentosReferencia"][0]["NumeroDocumento"];
      			$data["CodigoTipoDocumentoReferencia"] = $DatosCV["DocumentosReferencia"][0]["CodigoTipoDocumento"];
      			$data["CodigoMoneda"] = "PEN";

            foreach ($data["DocumentosReferencia"] as $key => $value) {
      				$data["DocumentosReferencia"][$key]["DocumentoReferencia"] = $value["SerieDocumento"]."-".$value["NumeroDocumento"];
      			}
      			$data["DetalleLeyenda"][0]["MontoLetra"] = $data["MontoLetra"];

            $data_documento["CodigoTipoDocumentoElectronico"] = $data["CodigoTipoDocumento"];

            $Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
            $nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
            $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
            $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
            $rutajson = $Plantillas_data->NombrePlantillaJSON;

      			$nombre = $data["CodigoEmpresa"]."-".CODIGO_TIPO_DOCUMENTO_NOTA_DEBITO."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
      			$data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
      			$data_json["plantilla"] =APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
      			$data_json["data"] = $data;

      			$json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);
      			$data_xml["codigotipodocumento"] = CODIGO_TIPO_DOCUMENTO_NOTA_DEBITO;
      			$data_xml["nombrearchivo"] = $nombre;
      			$data_xml["tipoarchivo"] = ".xml";
      			$data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];
      			$generar = $this->sComprobanteElectronico->GenerarXMLComprobanteVenta($data_xml);

      			if($generar["error"] != "")
      			{
      				$resultado["error"] = $generar["error"];
      				$resultado["title"] = "<strong>Ocurrio un error.</strong>";
      				$resultado["type"] = "danger";
      				$resultado["clase"] = "notify-danger";
      				$resultado["message"] = $generar["msg"];
      				return $resultado;
      				exit;
      			}
      			else {
      				$inputCPE["NombreArchivoComprobante"]=$nombre;
      				$inputCPE["FechaGeneracion"]=$this->Base->ObtenerFechaServidor();
      				$inputCPE["UsuarioGeneracion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      				$inputCPE["IndicadorEstado"]=ESTADO_ACTIVO;
      				$inputCPE["IndicadorEstadoCPE"]=ESTADO_CPE_GENERADO;
      				$inputCPE["IdComprobanteVenta"]= $DatosCV["IdComprobanteVenta"];
      				$inputCPE["CodigoHash"]= $generar["firma"];

              $comprobante = $this->sComprobanteElectronico->ObtenerComprobanteElectronicoVigentePorIdComprobanteVenta($data);//ObtenerComprobanteElectronicoPorIdComprobanteVenta

              if($comprobante!=null)
              {
                $input2["IdComprobanteElectronico"]=$comprobante->IdComprobanteElectronico;
                $input2["CodigoHash"]=$comprobante->CodigoHash;
                $output=$this->sComprobanteElectronico->BorrarComprobanteElectronico($input2);
                //$output=$this->ActualizarComprobanteElectronico($inputCPE);
              }

      				$output=$this->sComprobanteElectronico->InsertarComprobanteElectronico($inputCPE);

      			}

      			$resultado["error"] = "";
      			$resultado["title"] = "<strong>Operacion Exitosa.</strong>";
      			$resultado["type"] = "success";
      			$resultado["clase"] = "notify-success";
      			$resultado["message"] = "Se genero Exitosamente el ComprobanteVenta.";

            $data["NombreArchivoComprobante"]=$nombre;
      			return $data;
      		} catch (Exception $e) {
      			$resultado["title"] = "<strong>Error!</strong>";
      			$resultado["type"] = "danger";
      			$resultado["clase"] = "notify-danger";
      			$resultado["message"] = "Ocurrio un error con la comunicacion de baja.";
      			return $resultado;
      		}

      	}

				function BorrarNotaDebitoDesdeServicioVenta($data)
        {
          //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
          $this->sDocumentoReferencia->BorrarDocumentoReferencia($data);
        }
}
