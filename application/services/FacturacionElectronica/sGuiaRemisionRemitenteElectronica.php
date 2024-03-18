<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sGuiaRemisionRemitenteElectronica extends MY_Service
{

  public $GuiaRemisionRemitenteElectronica = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->helper("file");
    $this->load->helper("date");    
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('restapi');    
    $this->load->library('reporter');
    $this->load->library('json');
    $this->load->library('logger');
    $this->load->library('archivo');
    $this->load->library('javabridge');
    $this->load->library('soapclientsunat');
    $this->load->library('sesionusuario');
    $this->load->model('FacturacionElectronica/mGuiaRemisionRemitenteElectronica');
    $this->load->service('Seguridad/sErrorFacturacionElectronica');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service('Configuracion/General/sEmpresa');
    $this->load->service('Configuracion/FacturacionElectronica/sTipoDocumentoElectronico');
    $this->load->service('Venta/sGuiaRemisionRemitente');
    $this->load->service('Venta/sDetalleGuiaRemisionRemitente');
    $this->load->service('FacturacionElectronica/sComprobanteElectronico');

    $this->soapclientsunat->indicadorGuiaRemisionRemitente = true;
    // $this->GuiaRemisionRemitenteElectronica = $this->mGuiaRemisionRemitenteElectronica->GuiaRemisionRemitenteElectronica;
  }

  function InsertarGuiaRemisionRemitenteElectronica($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->InsertarGuiaRemisionRemitenteElectronica($data);
    return $resultado;
  }

  function ActualizarGuiaRemisionRemitenteElectronica($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ActualizarGuiaRemisionRemitenteElectronica($data);
    return $resultado;
  }

  function BorrarGuiaRemisionRemitenteElectronica($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->BorrarGuiaRemisionRemitenteElectronica($data);
    return $data;
  }

  function ConfirmarGuiaRemisionRemitenteElectronica($data)
  {
    $resultado = $this->ObtenerGuiaRemisionRemitenteElectronicaVigentePorIdGuiaRemisionRemitente($data);

    $input["IdGuiaRemisionRemitenteElectronica"] = $resultado->IdGuiaRemisionRemitenteElectronica;
    $input["UsuarioEnvio"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $input["FechaEnvio"] = $this->Base->ObtenerFechaServidor();
    $input["IndicadorEstadoCPE"] = $data["IndicadorEstadoCPE"];
    if(array_key_exists("NumeroTicket",$data)) {
      $input["NumeroTicket"] = $data["NumeroTicket"];
      $input["FechaRecepcion"] = $data["FechaRecepcion"];
    }
    //$input["NumeroTicket"] = $resultado->NumeroTicket;
    if(array_key_exists("CodigoError",$data)) {
      $input["CodigoError"] = $data["CodigoError"];
      $input["DescripcionError"] = $data["DescripcionError"];
      $input["CodigoHash"] = $resultado->CodigoHash;
    }
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ActualizarGuiaRemisionRemitenteElectronica($input);
    return $resultado;
  }

  function ObtenerGuiaRemisionRemitenteElectronicaVigentePorIdGuiaRemisionRemitente($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ObtenerGuiaRemisionRemitenteElectronicaVigentePorIdGuiaRemisionRemitente($data);
    return $resultado;
  }

  function EnviarGuiaRemisionRemitenteElectronica($data)
  {
    try {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      //OBTENEMOS PARAMETRO OSE
      $SunatOSE = $this->sConstanteSistema->ObtenerParametroEnvioSunatOSE();

      $RUC = $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL = $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL = $DatosEmpresa["ClaveSOL"];

      $nombreArchivoZip = $data["nombrearchivo"].".zip";
      $rutaZip = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombreArchivoZip;
      $archivoZip = file_get_contents($rutaZip);

      $nombreArchivoRpta = "R-".$nombreArchivoZip;
      $rutaArchivoRpta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombreArchivoRpta;

      //$this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL, $CONTRASEÑA_SOL);
      //$resultado = $this->soapclientsunat->sendBill($nombreArchivoZip, $archivoZip);

      $nombrearchivo = $data["nombrearchivo"]; 
      $_url="https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/$nombrearchivo";
      $datos2["nomArchivo"] = $nombreArchivoZip;
      $datos2["arcGreZip"] = base64_encode($archivoZip);
      $datos2["hashZip"] = hash_file("sha256", $rutaZip);
      $ClientIdAPISUNAT = $DatosEmpresa["ClientIdAPISUNAT"];
      $ClientSecretAPISUNAT = $DatosEmpresa["ClientSecretAPISUNAT"];
      $UsuarioSOLPrincipal =  $RUC.$DatosEmpresa["UsuarioSOLPrincipal"];
      $ClaveSOLPrincipal = $DatosEmpresa["ClaveSOLPrincipal"];
      
      $resultado=$this->restapi->enviarGuiaXML($_url,$datos2,$ClientIdAPISUNAT,$ClientSecretAPISUNAT,$UsuarioSOLPrincipal,$ClaveSOLPrincipal);
      if(is_array($resultado))
        $cadena = print_r($resultado,true);
      else
        $cadena = $resultado;

			$this->TemporalLog("enviarGuiaXML:".$cadena);

      if ($resultado == false) {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["FaultCode"] = "SUNAT";
        $response["Error"] = "Error con la coneccion SUNAT.";
        $response["TipoError"] = CODIGO_ERROR_CONECCION_SUNAT;
        return $response;
      } 
      else {
        //if (is_array($resultado)) {
          if (array_key_exists("cod", $resultado)) {
            /*
            $response["FaultCode"] = ($SunatOSE == 1) ? $resultado["faultcode"].":".$resultado["faultstring"] : $resultado["faultcode"];
            $response["FaultString"] = ($SunatOSE == 1) ? $resultado["detail"] : $resultado["faultstring"];
            $response["Error"] = $response["FaultString"];
            $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
            */
            $response["FaultCode"] = ($SunatOSE == 1) ? $resultado["cod"].":".$resultado["msg"] : $resultado["cod"];
            $response["Error"] = array_key_exists("exc", $resultado) ?  $resultado["exc"] :  $resultado["errores"]["msg"];
            $response["FaultString"] = ($SunatOSE == 1) ? $resultado["msg"] : $response["Error"];            
            $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
          
            return $response;
          }
          else {
            $ticket = $resultado["numTicket"];
            $respuesta = $this->restapi->obtenerCDRGuiaRemision($ticket,$ClientIdAPISUNAT,$ClientSecretAPISUNAT,$UsuarioSOLPrincipal,$ClaveSOLPrincipal );            
            if(is_array($respuesta))
              $cadena =  print_r($respuesta,true);
            else
              $cadena = $respuesta;
      
            $this->TemporalLog("obtenerCDRGuiaRemision:".$cadena);
    
            $response["NumeroTicket"]=$ticket;
            $response["FechaRecepcion"]=$resultado["fecRecepcion"];

            if(array_key_exists("codRespuesta",$respuesta)) {
                $response["CodigoRespuesta"] = $respuesta["codRespuesta"];
                if(!array_key_exists("error",$respuesta)) {
                    if($respuesta["codRespuesta"] =="98") {
                       $response["CodigoRespuesta"] = "98";
                    }
                    elseif($respuesta["codRespuesta"] =="0") {
                      $arcCdr =$respuesta["arcCdr"];
                      $archivoCDR = base64_decode($arcCdr);
                      file_put_contents($rutaArchivoRpta, $archivoCDR);
                      $response["FechaRespuestaEnvio"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
                      $response["Data"] = "";                    
                    }                
                }
                else {
                  if($respuesta["codRespuesta"] =="99") {
                    if($respuesta["indCdrGenerado"] == "1") {
                      $arcCdr =$respuesta["arcCdr"];
                      $archivoCDR = base64_decode($arcCdr);
                      file_put_contents($rutaArchivoRpta, $archivoCDR);
                    }

                    $response["Error"] = $respuesta["error"]["desError"];
                    $response["FaultString"] = $respuesta["error"]["desError"];
                    $response["FaultCode"] = $respuesta["error"]["numError"];
                    $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
                  }
                }
            }
            else {
              if(array_key_exists("cod",$respuesta)) {
                  //$response["cod"] = $respuesta["cod"];                  
                  if(array_key_exists("errors", $respuesta)) {
                    $response["Error"] = $respuesta["msg"].$respuesta["exc"];
                    $response["FaultString"] = $respuesta["errors"]["desError"];
                    $response["FaultCode"] = $respuesta["errors"]["codError"]; 
                  }
                  else {
                    $response["Error"] = $respuesta["exc"];
                    $response["FaultString"] = $respuesta["msg"];
                    $response["FaultCode"] = $respuesta["cod"];                    
                  } 
              }
            }
            
            return $response;
          }
        /*} else {
          file_put_contents($rutaArchivoRpta, $resultado);
          $response["FechaRespuestaEnvio"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
          $response["Data"] = "";
          return $response;
        }*/
      }
    } 
    catch (Exception $fault) {
      $response["FaultString"] = "";
      $response["FaultCode"] = "";
      $response["Error"] = $fault->getMessage();
      return $response;
    }
  }

  function ObtenerGuiaRemisionRemitenteElectronicaPorIdGuiaRemisionRemitente($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ObtenerGuiaRemisionRemitenteElectronica($data);
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitenteElectronica($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteElectronica($data);
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitenteElectronico($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteElectronico($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["DetallesGuiaRemisionRemitente"] = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($value);
    }
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitenteEnvio($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteEnvio($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["DetallesGuiaRemisionRemitente"] = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($value);
    }
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitenteEnvioPendiente($data)
  {
    $resultado = $this->mGuiaRemisionRemitenteElectronica->ConsultarGuiasRemisionRemitenteEnvioPendiente($data);

    return $resultado;
  }

  function GenerarXMLGuiaRemisionRemitenteElectronica($data, $destruir = true)
  {
    try {
      $DatosCV = $this->sGuiaRemisionRemitente->ObtenerGuiaRemisionRemitente($data);

      // $descripcion = $DatosCV["MontoLetra"];
      // $DatosCV["DetalleLeyenda"][0]["MontoLetra"] = $DatosCV["MontoLetra"];
      // print_r($DatosCV);exit;
      $resultado["error"] = "";
      $data_carpeta['IdGrupoParametro'] = ID_GRUPO_CARPETA_SUNAT;
      $DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];
      //$DatosCV =$data;
      $data["RazonSocialEmisor"] = $DatosEmpresa["RazonSocial"];
      $data = array_merge($data, $DatosEmpresa, $DatosCV);
      $data["Documento"] = $data["SerieDocumento"]."-".$data["NumeroDocumento"];
      $data_documento["CodigoTipoDocumentoElectronico"] = $data["CodigoTipoDocumento"];
      // print_r($data);
      // exit;
      $Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
      $nombreplantilla = $Plantillas_data->NombrePlantillaFTL;
      $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
      $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
      $rutajson = $Plantillas_data->NombrePlantillaJSON;

      // print_r($Plantillas_data);
      // exit;
      //Lineas del JSON NUEVO
      $nombre = $data["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
      $data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
      $data_json["plantilla"] =  APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;      
      $data["PesoBrutoTotal"] =  str_replace(',', "", $data["PesoBrutoTotal"]);
      $data["PesoBrutoTotal"] = $data["PesoBrutoTotal"] == 0 ? "0" : $data["PesoBrutoTotal"];
      $data_json["data"] = $data;
      
      // print_r($data_json["data"]);
      // print_r($data_json);
      $json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);
      // print_r($json);
      // exit;
      $data_xml["codigotipodocumento"] = $data["CodigoTipoDocumento"];
      $data_xml["nombrearchivo"] = $nombre;
      $data_xml["tipoarchivo"] = ".xml";
      $data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];

      $generar = $this->sComprobanteElectronico->GenerarXMLComprobanteVenta($data_xml, $destruir);
      // print_r($generar);
      // exit;
      if ($generar["error"] != "") {
        $resultado["error"] = $generar["error"];
        $resultado["title"] = "<strong>Ocurrio un error.</strong>";
        $resultado["type"] = "danger";
        $resultado["clase"] = "notify-danger";
        $resultado["message"] = $generar["msg"];

        throw new Exception($generar["error"]); //["error"]$e->getMessage(),$e->getCode(),$e);
        //echo $this->json->json_response($resultado);
        exit;
      } else {
        $inputCPE["NombreArchivoComprobante"] = $nombre;
        $inputCPE["FechaGeneracion"] = $this->Base->ObtenerFechaServidor();
        $inputCPE["UsuarioGeneracion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $inputCPE["IndicadorEstado"] = ESTADO_ACTIVO;
        $inputCPE["IndicadorEstadoCPE"] = ESTADO_CPE_GENERADO;
        $inputCPE["IdGuiaRemisionRemitente"] = $DatosCV["IdGuiaRemisionRemitente"];
        $inputCPE["NumeroTicket"] = "";
        $inputCPE["CodigoHash"] = $generar["firma"];

        $comprobante = $this->ObtenerGuiaRemisionRemitenteElectronicaVigentePorIdGuiaRemisionRemitente($data); //ObtenerGuiaRemisionRemitenteElectronicaPorIdGuiaRemisionRemitente

        if ($comprobante != null) {
          $input2["IdGuiaRemisionRemitenteElectronica"] = $comprobante->IdGuiaRemisionRemitenteElectronica;
          $input2["CodigoHash"] = $comprobante->CodigoHash;
          $output = $this->BorrarGuiaRemisionRemitenteElectronica($input2);
          //$output=$this->ActualizarGuiaRemisionRemitenteElectronica($inputCPE);
        }
        //else
        //{
        //$output=$this->ActualizarGuiaRemisionRemitenteElectronica($inputCPE);
        //}
        $output = $this->InsertarGuiaRemisionRemitenteElectronica($inputCPE);

        $input = $DatosCV; //["IdGuiaRemisionRemitente"]=$data["IdGuiaRemisionRemitente"];
        $input["NombreArchivoComprobante"] = $nombre;

        return $input;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function GenerarZIPEnvio($data, $destruir = true)
  {
    try {
      //Definiendo cabeceras
      //Se obtiene los datos de la base de datos que se enviaran a la funcion [Se creo una funcion para suplir]
      /*Datos principales de la Data*/
      $nombrearchivo = $data["nombrearchivo"];
      $tipoarchivo = $data["tipoarchivo"];
      $nombrearchivoxml = $nombrearchivo.$tipoarchivo;

      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      /*Rutas de Archivo*/
      $rutaDocumentoFirmado = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;
      $rutaDocumentoComprimido = $data["rutaenvio"].$nombrearchivo.".zip";

      //DECLARANDO LIBRERIAS A USAR
      $inicioZip = $this->javabridge->IniciarZip($nombrearchivo);

      $Zip = $inicioZip->get("com.sisemperu.sunat.Zip");

      /*AQUI SE REALIZA EL ZIPEADO DE ARCHIVO*/
      $zipeado = $Zip->comprimir($rutaDocumentoFirmado, $rutaDocumentoComprimido);

      if ($zipeado != "") {
        throw new Exception(serialize(['id' => 5, 'msg' => "Error al Zipear Documento: ", 'error' => $zipeado]));
      }

      //Destruyendo Sesiones
      if ($destruir == true) {
        $this->javabridge->Destruir();
      }
      //return "Exitoso.";
      $response["msg"] = "Proceso Correctamente";
      $response["error"] = "";

      return $response;
    } catch (Exception $e) {
      $this->javabridge->Destruir();
      $data = unserialize($e->getMessage());
      $response = null;
      $mensaje_error = (string) $data['error'];
      $error = $data['msg'].$mensaje_error;

      $response["msg"] = $error;
      $response["error"] = $error;

      return $response;
    }
  }

  function EliminarZIPEnvio($data, $destruir = true)
  {
    try {
      //Definiendo cabeceras
      //Se obtiene los datos de la base de datos que se enviaran a la funcion [Se creo una funcion para suplir]
      /*Datos principales de la Data*/
      $nombrearchivo = $data["nombrearchivo"];
      $tipoarchivo = $data["tipoarchivo"];
      $nombrearchivoxml = $nombrearchivo.$tipoarchivo;

      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      /*Rutas de Archivo*/
      $rutaDocumentoComprimido = $data["rutaenvio"].$nombrearchivo.".zip";

      $eliminado = $this->archivo->EliminarArchivo($rutaDocumentoComprimido);
      if (!$eliminado) {
        throw new Exception(serialize(['id' => 5, 'msg' => "Error al Zipear Documento: ", 'error' => $eliminado]));
      }

      $response["msg"] = "Proceso Correctamente";
      $response["error"] = "";

      return $response;
    } catch (Exception $e) {
      $data = unserialize($e->getMessage());
      $response = null;
      $mensaje_error = (string) $data['error'];
      $error = $data['msg'].$mensaje_error;

      $response["msg"] = $error;
      $response["error"] = $error;

      return $response;
    }
  }

  function ObtenerCodigoString($string)
  {
    $cadena = $string;
    $pos1 = strpos($cadena, '-') + 1;
    $cadena2 = substr($string, $pos1);
    $pos2 = strpos($cadena2, '-');

    $cadena_final = substr($cadena, $pos1, $pos2);
    return trim($cadena_final);
  }

  function ObtenerCarpetasSUNAT()
  {
    $data['IdGrupoParametro'] = ID_GRUPO_CARPETA_SUNAT;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data);
    //print_r($resultado);
    //exit;
    if (is_string($resultado)) {
      return $resultado;
    } else {
      return $resultado;
    }
  }

  function ConsultarEstadoCDR($data)
  {
    try {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $RUC = $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL = $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL = $DatosEmpresa["ClaveSOL"];

      $data["ruc"] = $DatosEmpresa["CodigoEmpresa"];
      $data["tipo"] = $data["CodigoTipoDocumento"];
      $data["serie"] = $data["SerieDocumento"];
      $data["numero"] = $data["NumeroDocumento"];
      $nombreArchivoZip = $data["ruc"].'-'.$data["tipo"].'-'.$data["serie"].'-'.$data["numero"].".zip";

      $nombreArchivoRpta = "R-".$nombreArchivoZip;
      $rutaArchivoRpta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombreArchivoRpta;

      //$this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL, $CONTRASEÑA_SOL);
      //$resultado = $this->soapclientsunat->getStatusCDR($data);
      $ticket = $data["NumeroTicket"];
      $ClientIdAPISUNAT = $DatosEmpresa["ClientIdAPISUNAT"];
      $ClientSecretAPISUNAT = $DatosEmpresa["ClientSecretAPISUNAT"];
      $UsuarioSOLPrincipal =  $RUC.$DatosEmpresa["UsuarioSOLPrincipal"];
      $ClaveSOLPrincipal = $DatosEmpresa["ClaveSOLPrincipal"];
      
      $cadena = $ticket;      
      $this->TemporalLog(".obtenerCDRGuiaRemision1:".$cadena);

      $respuesta = $this->restapi->obtenerCDRGuiaRemision($ticket,$ClientIdAPISUNAT,$ClientSecretAPISUNAT,$UsuarioSOLPrincipal,$ClaveSOLPrincipal);
            
      if(is_array($respuesta))
				$cadena = print_r($respuesta,true);
		  else
				$cadena = $respuesta;

			$this->TemporalLog(".obtenerCDRGuiaRemision2:".$cadena);		

      if ($respuesta == false) {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["FaultCode"] = "SUNAT";
        $response["Error"] = "Error con la conexion SUNAT.";
        $response["TipoError"] = CODIGO_ERROR_CONECCION_SUNAT;
        return $response;
      } else {

        if(array_key_exists("codRespuesta",$respuesta)) {
            if(!array_key_exists("error",$respuesta)) {
                if($respuesta["codRespuesta"] =="98") {
                   $response["CodigoRespuesta"] = "98";
                }
                elseif($respuesta["codRespuesta"] =="0") {
                  $arcCdr =$respuesta["arcCdr"];
                  $archivoCDR = base64_decode($arcCdr);
                  file_put_contents($rutaArchivoRpta, $archivoCDR);
                  $response["FechaRespuestaEnvio"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
                  $response["Data"] = "";
                }                
            }
            else {
              if($respuesta["codRespuesta"] =="99") {
                if($respuesta["indCdrGenerado"] == "1") {
                  $arcCdr =$respuesta["arcCdr"];
                  $archivoCDR = base64_decode($arcCdr);
                  file_put_contents($rutaArchivoRpta, $archivoCDR);
                }

                $response["Error"] = $respuesta["error"]["desError"];
                $response["FaultString"] = $respuesta["error"]["desError"];
                $response["FaultCode"] = $respuesta["error"]["numError"];
                $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;

              }
            }
        }
        else {
          if(array_key_exists("cod",$respuesta)) {
              //$response["cod"] = $respuesta["cod"];                  
              if(array_key_exists("errors", $respuesta)) {
                $response["Error"] = $respuesta["msg"].$respuesta["exc"];
                $response["FaultString"] = $respuesta["errors"]["desError"];
                $response["FaultCode"] = $respuesta["errors"]["codError"]; 
              }
              else {
                $response["Error"] = $respuesta["exc"];
                $response["FaultString"] = $respuesta["msg"];
                $response["FaultCode"] = $respuesta["cod"];                    
              } 
          }
          else {
            if(array_key_exists("status", $respuesta)) {
              //$response["Error"] = $respuesta["status"]."-".$respuesta["message"];
              $response["message"] = $respuesta["message"];
              $response["status"] = $respuesta["status"];
            }
          }
        }

        return $response;
      }
    } 
    catch (Exception $fault) {
      $response["FaultString"] = "";
      $response["FaultCode"] = "";
      $response["Error"] = $fault->getMessage();
      return $response;
    }
  }

  function GenerarReportePDF($data, $expo = false)
  {
    $parametros["IdGuiaRemisionRemitente"] = $data["IdGuiaRemisionRemitente"];

    $name_archive = $data["SerieDocumento"]."-".$data["NumeroDocumento"];
    if($expo)
    {
      $name_archive = $data["NombreArchivoComprobante"];
    }

    $ruta_pdf = RUTA_CARPETA_REPORTES_GENERADOS_PDF.$name_archive.".pdf";
    
    $rutaFormato = RUTA_CARPETA_REPORTES.NOMBRE_FACTURA_ELECTRONICO;
    $indicadorImpresion = INDICADOR_FORMATO_GUIA_REMISION;
    $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION."config-".$this->shared->GetDeviceName().".json";
    $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion,null,$rutaplantilla);
    if($dataConfig != false)
    {
     
      $rutaFormato = RUTA_CARPETA_REPORTES.$dataConfig["A4"];
    }
    $this->reporter->RutaReporte = $rutaFormato;
    $this->reporter->RutaPDF = $ruta_pdf;
    $this->reporter->SetearParametros($parametros);
    $resultado = $this->reporter->ExportarReporteComoPDF();

    $output["APP_RUTA"] =APP_PATH_URL."assets/reportes/Venta/".$name_archive.".pdf";//"http://".$_SERVER["HTTP_HOST"]."/sisem/
    $output["BASE_RUTA"] = APP_PATH."assets/reportes/Venta/".$name_archive.".pdf";//"http://".$_SERVER["HTTP_HOST"]."/sisem/

    return $output;
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
