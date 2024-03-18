<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sNotaCredito extends sComprobanteVenta {

        public $DetalleNotaCredito = array();

        public function __construct()
        {

          parent::__construct();

      		$this->load->service("Venta/sDocumentoReferencia");
      		$this->load->service("Venta/sDetalleDocumentoReferencia");
          $this->load->service("Inventario/sNotaEntrada");
      		$this->load->service("Venta/sDetalleNotaCredito");
      		$this->load->service("Configuracion/Venta/sMotivoNotaCredito");
      		$this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
          $this->load->service('Configuracion/FacturacionElectronica/sTipoDocumentoElectronico');
          $this->load->service('FacturacionElectronica/sComprobanteElectronico');
      		$this->load->service('Configuracion/General/sEmpresa');
      		$this->load->service("Catalogo/sCliente");
          $this->load->service('Configuracion/Inventario/sMotivoNotaEntrada');

          $DetalleNotaCredito = [];
          $DetalleNotaCredito[] = $this->sDetalleNotaCredito->Cargar();
          $this->ComprobanteVenta["DetallesNotaCredito"] = $DetalleNotaCredito;
        }

        function CargarNotaCredito($devolucion = false)
        {
          $parametro['IdTipoDocumento'] = ($devolucion) ? ID_TIPODOCUMENTO_NOTADEVOLUCION : ID_TIPODOCUMENTO_NOTACREDITO;
          $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          $resultado = parent::Cargar($parametro);

          $resultado["IdCorrelativoDocumento"] = (count($resultado["SeriesDocumento"]) > 0) ? $resultado["SeriesDocumento"][0]["IdCorrelativoDocumento"] : "";
          $resultado["SerieDocumento"] = (count($resultado["SeriesDocumento"]) > 0) ? $resultado["SeriesDocumento"][0]["SerieDocumento"] : "";
          // $resultado["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTACREDITO;
          $resultado["ActualizarDetalle"] = "0";
          $resultado["TotalProporcional"] = "0";

          $resultado["NombreAlmacen"] = (count($resultado["Sedes"]) > 0) ? $resultado["Sedes"][0]["NombreSede"] : "";
          $resultado["IdMotivoNotaEntrada"] = ID_MOTIVO_NOTA_ENTRADA_DEVOLUCION_CLIENTE_CON_DOCUMENTO;
          $resultadoMotivoEntrada = $this->sMotivoNotaEntrada->ObtenerMotivoNotaEntrada($resultado);
          $resultado["MotivoMovimiento"]=$resultadoMotivoEntrada->NombreMotivoNotaEntrada;

          $resultado["EstadoPendienteNota"] = '0';
          $resultado["EstadoPendienteComprobante"] = '0';

          $fechaservidor=$this->Base->ObtenerFechaServidor("d/m/Y");

          $resultado["TotalSaldo"] = "00.00";
      		$resultado["Concepto"] = "";
      		$resultado["Porcentaje"] = "0.00";
      		$resultado["Importe"] = "0.00";
      		$resultado["IdAsignacionSede"] = "";
      		$resultado["FechaIngreso"] = $fechaservidor;
      		$MotivosNotaCredito = $this->sMotivoNotaCredito->ListarMotivosNotaCredito();
      		$ConceptosNotaCredito = $this->sConceptoNotaCreditoDebito->ListarConceptosNotaCredito();

      		$resultado["MotivosNotaCredito"] = $MotivosNotaCredito;
      		$resultado["ConceptosNotaCredito"] = $ConceptosNotaCredito;
      		$resultado["BusquedaComprobantesVentaNC"] = array();
      		$resultado["BusquedaComprobanteVentaNC"] = array();
      		$resultado["MiniComprobantesVentaNC"] = array();//$this->ComprobanteVenta;//array();
      		$resultado["GrupoDetalleComprobanteVenta"] = array();
          $resultado["DocumentosReferencia"] = array();

          $resultado['NuevoDetalleNotaCredito']=$this->sDetalleNotaCredito->Cargar();

          return $resultado;
        }

        
        function InsertarNotaCredito($data) {
          
          $data["DetallesComprobanteVenta"] = $data["DetallesNotaCredito"];          
          $otra_data = $data;
    			
          foreach ($otra_data["DetallesComprobanteVenta"] as $key => $value) {
    				$otra_data["DetallesComprobanteVenta"][$key]["SaldoPendienteNotaCredito"] = 0;
            $otra_data["DetallesComprobanteVenta"][$key]["SaldoPendienteEntrada"] = $value["Cantidad"];
    			}
    			
          $resultado = parent::InsertarComprobanteVenta($otra_data);

          $motivoAfectarCosto = $data["MotivoNotaCredito"]["Reglas"]["AfectarCosto"];
          $motivoAfectarAlmacen = $data["MotivoNotaCredito"]["Reglas"]["AfectarAlmacen"];

          if(is_array($resultado)) {
    				$data["IdComprobanteVenta"] = $resultado["IdComprobanteVenta"];
            $data["NumeroDocumento"] = $resultado["NumeroDocumento"];
            
            $documentosReferencia = array();
            if(array_key_exists("MiniComprobantesVentaNC", $data)) {
    					$this->InsertarDocumentosReferencia($data);
    				}

            if($data["IdTipoVenta"] == TIPO_VENTA_MERCADERIA && $motivoAfectarAlmacen == 1) {
              $this->InsertarNotaEntrada($data);
            }

            $documentosReferencia = $this->ActualizarSaldosEnComprobante($data);
    				$resultado["DocumentosReferencia"] = $documentosReferencia;
    				return $resultado;
    			}
          
          return $resultado;    		
        }

        function ActualizarNotaCredito($data)
        {
          $data["ValorVentaGravado"] =str_replace(',',"",$data["ValorVentaGravado"]);
          $data["IGV"] =str_replace(',',"",$data["IGV"]);
          $data["DetallesComprobanteVenta"] = $data["DetallesNotaCredito"];
          $motivoAfectarCosto = $data["MotivoNotaCredito"]["Reglas"]["AfectarCosto"];
          $motivoAfectarAlmacen = $data["MotivoNotaCredito"]["Reglas"]["AfectarAlmacen"];
          $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
         
          $this->RevertirSaldosCabecerayDetalleEnComprobanteReferencias($data);
          $this->sDocumentoReferencia->BorrarDocumentoReferencia($data);
         
          $data["DetallesComprobanteVenta"] = $data["DetallesNotaCredito"];
          $otra_data = $data;

    			foreach ($otra_data["DetallesComprobanteVenta"] as $key => $value) {
    				$otra_data["DetallesComprobanteVenta"][$key]["SaldoPendienteNotaCredito"] = 0;
            $otra_data["DetallesComprobanteVenta"][$key]["SaldoPendienteEntrada"] = $value["Cantidad"];
    			}
          
          //echo "Id:".$otra_data["IdComprobanteVenta"]."<br>";
          $resultado = parent::ActualizarComprobanteVenta($otra_data);
          //echo "IdR:".$resultado["IdComprobanteVenta"];
          //print_r($resultado);
          //return "ERROR";
          if(is_array($resultado)) {
            $resultado["AbreviaturaSituacionCPE"] = $resultado["AbreviaturaSituacionCPE"]->AbreviaturaSituacionComprobanteElectronicoVentas;
    				$data["IdComprobanteVenta"] = $resultado["IdComprobanteVenta"];
            $data["NumeroDocumento"] = $resultado["NumeroDocumento"];
    				if(array_key_exists("MiniComprobantesVentaNC", $data)) {
    					$this->InsertarDocumentosReferencia($data);
    				}

            if($data["IdTipoVenta"] == TIPO_VENTA_MERCADERIA && $motivoAfectarAlmacen == 1) {
    				  $this->ActualizarNotaEntrada($data);
            }

            $documentosReferencia = $this->ActualizarSaldosEnComprobante($data);
          
    				$resultado["DocumentosReferencia"] = $documentosReferencia;
    				return $resultado;
    			}

          return $resultado;
        }

        function BorrarNotaCredito($data)
        {
          $this->sDetalleNotaCredito->BorrarDetallesNotaCredito($data);
          $resultado = $this->mComprobanteVenta->BorrarComprobanteVenta($data);
          return "";
        }

        function BorrarNotaCreditoDesdeServicioVenta($data)
        {
          //REVERTIMOS LOS SALDOS POR NOTA DE CREDITO
          $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
          $this->RevertirSaldosCabecerayDetalleEnComprobanteReferencias($data);

          //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
          $this->sDocumentoReferencia->BorrarDocumentoReferencia($data);
        }

        function InsertarDocumentosReferencia($data)
        {
          foreach ($data["MiniComprobantesVentaNC"] as $key => $value) {
						$value["IdComprobanteNota"] = $data["IdComprobanteVenta"];
						$documentoreferencia = $this->sDocumentoReferencia->InsertarDocumentoReferencia($value);
            // print_r($documentoreferencia);exit;
          }
          return "";
        }

        function ActualizarDocumentosReferencia($data)
        {
          foreach ($data["MiniComprobantesVentaNC"] as $key => $value) {
						$value["IdComprobanteNota"] = $data["IdComprobanteVenta"];
            $consulta = $this->sDocumentoReferencia->ConsultarDocumentoReferenciaPorNota($value);

						$documentoreferencia = $this->sDocumentoReferencia->InsertarDocumentoReferencia($value);
					}
          return "";
        }

        function InsertarNotaEntrada($data)
        {
          $entrada = "";

          if($data["EstadoPendienteNota"] == '0' && $data["ActualizarDetalle"] == '1'){
						$entrada = $this->sNotaEntrada->InsertarNotaEntradaDesdeComprobante($data);
					}

          return $entrada;
        }

        function ActualizarNotaEntrada($data)
        {
          $entrada = "";
          if($data["EstadoPendienteNota"] == '0' && $data["ActualizarDetalle"] == '1'){
						$entrada = $this->sNotaEntrada->ActualizarNotaEntradaDesdeComprobanteVenta($data);
					}

          return $entrada;
        }

        /*Se actualizan SaldoNotaCredito en ComprobanteVenta*/
        function ActualizarSaldoNotaCreditoEnComprobanteVenta($data)
        {
          $documentosReferencia = $data["MiniComprobantesVentaNC"];
          foreach ($documentosReferencia as $key => $value) {
            // code...
            $comprobante = parent::ConsultarComprobanteVentaPorId($value);
            // if($comprobante["SaldoNotaCredito"] > 0)
            // {
              $nueva_data = array();
              $nueva_data["IdComprobanteVenta"] = $value["IdComprobanteVenta"];
              if($data["IdMotivoNotaCredito"] == 13) {
                $nueva_data["SaldoNotaCredito"] = 0;
              }
              else {
                $nueva_data["SaldoNotaCredito"] = $comprobante[0]["SaldoNotaCredito"] - $data["Total"];
              }
              $nueva_data["IndicadorEstado"] = $value["IndicadorEstado"];
              $nueva_data["IndicadorEstadoCPE"] = $value["IndicadorEstadoCPE"];
              $nueva_data["IndicadorEstadoComunicacionBaja"] = $value["IndicadorEstadoComunicacionBaja"];
              $nueva_data["IndicadorEstadoResumenDiario"] = $value["IndicadorEstadoResumenDiario"];
              $nueva_data["SerieDocumento"] = $value["SerieDocumento"];
              $nueva_data["CodigoEstado"] = $value["CodigoEstado"];

              // $resultado = parent::ConsultarComprobanteVentaPorId($value);
              $resultado=$this->mComprobanteVenta->ActualizarComprobanteVenta($nueva_data);

              $nueva_data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
              $nueva_data["Total"] = $data["Total"];
              $documentoReferencia = $this->sDocumentoReferencia->ActualizarSaldosEnDocumentoReferencia($nueva_data);
              $documentoReferencia = array_merge($value, $documentoReferencia);
              $documentosReferencia[$key] = $documentoReferencia;
            // }
          }
          return $documentosReferencia;
        }

        function RevertirSaldosEnComprobanteVentaDesdeReferencia($data)
        {
          $resultado = $this->sDocumentoReferencia->ObtenerDocumentosReferenciaByComprobante($data);
          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $comprobante = parent::ObtenerComprobanteVentaPorIdComprobante($value);
              if($comprobante)
              {
                $nueva_data["IdComprobanteVenta"] = $comprobante["IdComprobanteVenta"];
                if($comprobante["IdMotivoNotaCredito"] == 13) {
                  $nueva_data["SaldoNotaCredito"] = $comprobante["Total"];
                }
                else {
                  $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] + $value["TotalNota"]; 
                }
                $resultado=$this->mComprobanteVenta->ActualizarComprobanteVenta($nueva_data);
              }
            }
          }
          return $data;
        }

        /*Se actualizan los comprobantes de manera proporcional, dependiendo del procentaje si son varios*/
        function ActualizarSaldoNotaCreditoEnComprobanteVentaProporcional($data)
        {
            $documentosReferencia = $data["MiniComprobantesVentaNC"];
            // print_r($data);exit;
            foreach ($documentosReferencia as $key => $value) {
              // code...
              $comprobante = parent::ObtenerComprobanteVentaPorIdComprobante($value);
              // print_r("AAA");print_r($comprobante);exit;
              // if($comprobante["SaldoNotaCredito"] > 0)
              // {
                $nueva_data = array();
                // $comprobante = parent::ObtenerComprobanteVentaPorIdComprobante($value);
                $nueva_data["IdComprobanteVenta"] = $comprobante["IdComprobanteVenta"];
                $SaldoPorcentaje = ($data["Porcentaje"] / 100) * $comprobante["Total"];//$comprobante["SaldoNotaCredito"];

                if($data["IdMotivoNotaCredito"] == 13)
                  $nueva_data["SaldoNotaCredito"] = 0;                
                else
                  $nueva_data["SaldoNotaCredito"] = $comprobante["SaldoNotaCredito"] - $SaldoPorcentaje;

                $resultado=$this->mComprobanteVenta->ActualizarComprobanteVenta($nueva_data);
                // print_r($comprobante);print_r($SaldoPorcentaje);exit;
                $nueva_data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
                $nueva_data["Total"] = $SaldoPorcentaje;
                $documentoReferencia = $this->sDocumentoReferencia->ActualizarSaldosEnDocumentoReferencia($nueva_data);
                $documentoReferencia = array_merge($value, $documentoReferencia);
                $documentosReferencia[$key] = $documentoReferencia;
              // }
            }
            // print_r($documentosReferencia);exit;
            return $documentosReferencia;
        }

        /*Se actualizan los saldos en los Detalles de Comprobante Venta*/
        function ActualizarSaldoNotaCreditoEnDetalleComprobante($data)
        {
        
          foreach ($data["DetallesNotaCredito"] as $key => $value) {
            // if($value["SaldoPendienteNotaCredito"] > 0)
            // {
              $nueva_data = array();
              $nueva_data["IdDetalleComprobanteVenta"] = $value["IdDetalleReferencia"];
              $detalle = $this->sDetalleComprobanteVenta->ConsultarDetalleComprobanteVentaPorId($nueva_data);
              if(count($detalle)>0) {
                $detalle[0]["SaldoPendienteNotaCredito"] = (is_string($detalle[0]["SaldoPendienteNotaCredito"])) ? str_replace(',',"",$detalle[0]["SaldoPendienteNotaCredito"]) : $detalle[0]["SaldoPendienteNotaCredito"]; 
                $value["SubTotal"] = (is_string($value["SubTotal"])) ? str_replace(',',"",$value["SubTotal"]) : $value["SubTotal"]; 
                $nueva_data["SaldoPendienteNotaCredito"] = $detalle[0]["SaldoPendienteNotaCredito"] - $value["SubTotal"];
              
              }
              else{                
                $value["SubTotal"] = (is_string($value["SubTotal"])) ? str_replace(',',"",$value["SubTotal"]) : $value["SubTotal"]; 
                $nueva_data["SaldoPendienteNotaCredito"] = 0;
              }
              
              if($data["IdMotivoNotaCredito"]==13)
                $nueva_data["SaldoPendienteNotaCredito"] = 0;

              $this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($nueva_data);

              $value["IdComprobanteNota"] = $data["IdComprobanteVenta"];
              $this->sDetalleDocumentoReferencia->InsertarDetalleDocumentoReferencia($value);
            // }              
          }

          return "";
        }

        function RevertirSaldosEnDetalleComprobanteVentaDesdeReferencia($data)
        {
          $resultado = $this->sDetalleDocumentoReferencia->ConsultarDetallesDocumentoReferencia($data);
          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $comprobante = $this->sDetalleComprobanteVenta->ConsultarDetalleComprobanteVentaPorId($value);
              if($comprobante)
              {
                $nueva_data["IdDetalleComprobanteVenta"] = $comprobante[0]["IdDetalleComprobanteVenta"];
                $SaldoDetalleDocumentoReferencia = is_string($value["SaldoDetalleDocumentoReferencia"]) ? str_replace(',',"",$value["SaldoDetalleDocumentoReferencia"]) : $value["SaldoDetalleDocumentoReferencia"]; 
                $nueva_data["SaldoPendienteNotaCredito"] = $comprobante[0]["SaldoPendienteNotaCredito"] + $SaldoDetalleDocumentoReferencia;
                $resultado=$this->sDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($nueva_data);
              }
            }
          }
          return $data;
        }

        function RevertirSaldosCabecerayDetalleEnComprobanteReferencias($data)
        {
          $documentosReferencia = $this->RevertirSaldosEnComprobanteVentaDesdeReferencia($data);
          $detallesReferencia = $this->RevertirSaldosEnDetalleComprobanteVentaDesdeReferencia($data);
          //AQUI BORRAMOS LOS DETALLES EN SI
          $this->sDetalleDocumentoReferencia->BorrarDetallesDocumentoReferenciaPorIdNota($data);
          return $documentosReferencia;
        }

        function ActualizarSaldosEnComprobante($data)
        {
        
          if($data["ActualizarDetalle"] == '1')
  				{
  					$this->ActualizarSaldoNotaCreditoEnDetalleComprobante($data);
  				}

          $documentoReferencia = array();
  				if($data["TotalProporcional"] == '1'){
  					$documentoReferencia = $this->ActualizarSaldoNotaCreditoEnComprobanteVentaProporcional($data);
  				}
  				else{
  					$documentoReferencia = $this->ActualizarSaldoNotaCreditoEnComprobanteVenta($data);
  				}

          return $documentoReferencia;
        }

        function GenerarXMLNotaCredito($data)
        {
          try {
      			$resultado["error"] = "";
            $pre_data = $data;
   
            $data = $this->sComprobanteVenta->ObtenerComprobanteVenta($pre_data);
                        
            if($pre_data["MotivoNotaCredito"]["Reglas"]["BorrarDetalles"] == 1) {
              if ($pre_data["CodigoMotivoNotaCredito"] !="04" && $pre_data["CodigoMotivoNotaCredito"] !="13") {
                  $documentoreferencia = $pre_data["DocumentosReferencia"][0]; 
                  //$data["DetallesComprobanteVenta"] = $pre_data["DetallesComprobanteVenta"];

                  $data["DetallesComprobanteVenta"][0]["SumaTributo"] = 0;
                  $data["DetallesComprobanteVenta"][0]["ValorVentaItem"] = $data["Total"];
                  $data["DetallesComprobanteVenta"][0]["SumatoriaICBP"] = 0;
                  $data["DetallesComprobanteVenta"][0]["FactorICBP"] = 0;

                  if($data["IGV"] > 0) {
                    $data["DetallesComprobanteVenta"][0]["CodigoTipoTributo"] = "1000";//$documentoreferencia["DetallesComprobanteVenta"][0]["CodigoTipoTributo"];
                    $data["DetallesComprobanteVenta"][0]["NombreTributo"] = "IGV";//$documentoreferencia["DetallesComprobanteVenta"][0]["NombreTributo"];
                    $data["DetallesComprobanteVenta"][0]["CodigoInternacional"] = "VAT";//$documentoreferencia["DetallesComprobanteVenta"][0]["CodigoInternacional"];
                    $data["DetallesComprobanteVenta"][0]["PorcentajeIGV"] = PORCENTAJE_IGV;//$documentoreferencia["DetallesComprobanteVenta"][0]["PorcentajeIGV"];
                    $data["DetallesComprobanteVenta"][0]["TasaISC"] = $documentoreferencia["DetallesComprobanteVenta"][0]["TasaISC"];

                    $data["DetallesComprobanteVenta"][0]["IGVItem"] = $data["IGV"];
                    $data["DetallesComprobanteVenta"][0]["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;
                    $data["DetallesComprobanteVenta"][0]["ValorVentaUnitario"] = $data["ValorVentaUnitario"];
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
            }

            if($pre_data["CodigoMotivoNotaCredito"]=="13") {
              //$data["DetallesComprobanteVenta"]=array();
            }
            
            $data["DocumentosReferencia"] = $pre_data["DocumentosReferencia"];
            $data["CodigoMotivoNotaCredito"] = $pre_data["CodigoMotivoNotaCredito"];
            $data["NombreMotivoNotaCredito"] = $pre_data["NombreMotivoNotaCredito"];

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
      			//$data["CodigoMoneda"] = "PEN";

      			foreach ($data["DocumentosReferencia"] as $key => $value) {
      				$data["DocumentosReferencia"][$key]["DocumentoReferencia"] = $value["SerieDocumento"]."-".$value["NumeroDocumento"];
              $data["DocumentosReferencia"][$key]["FechaEmisionReferencia"] = convertToDate($value["FechaEmision"]);
      			}
      			$data["DetalleLeyenda"][0]["MontoLetra"] = $data["MontoLetra"];//"NUEVOS SOLES";

            $data_documento["CodigoTipoDocumentoElectronico"] = $data["CodigoTipoDocumento"];

            $Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
            $nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
            $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
            $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
            $rutajson = $Plantillas_data->NombrePlantillaJSON;

      			//Lineas del JSON NUEVO
      			$nombre = $data["CodigoEmpresa"]."-".CODIGO_TIPO_DOCUMENTO_NOTA_CREDITO."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
      			$data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
      			$data_json["plantilla"] =APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
      			$data_json["data"] = $data;

      			$json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);
      			/*GENERANDO XML*/
      			$data_xml["codigotipodocumento"] = CODIGO_TIPO_DOCUMENTO_NOTA_CREDITO;
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

}
