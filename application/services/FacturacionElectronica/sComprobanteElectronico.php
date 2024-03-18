<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComprobanteElectronico extends MY_Service {

  public $ComprobanteElectronico = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->helper("file");
    $this->load->helper("date");    
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('reporter');
    $this->load->library('json');
    $this->load->library('logger');
    $this->load->library('archivo');
    $this->load->library('javabridge');
    $this->load->library('soapclientsunat');
    $this->load->library('sesionusuario');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/FacturacionElectronica/sTipoDocumentoElectronico');
    $this->load->service('Configuracion/General/sEmpresa');
    $this->load->service('Seguridad/sErrorFacturacionElectronica');
    $this->load->service('Venta/sComprobanteVenta');
    $this->load->service('Venta/sDetalleComprobanteVenta');
    $this->load->model('FacturacionElectronica/mComprobanteElectronico');
    $this->load->service('Configuracion/General/sConstanteSistema');

    $this->ComprobanteElectronico = $this->mComprobanteElectronico->ComprobanteElectronico;
  }


  function InsertarComprobanteElectronico($data)
  {
    $resultado = $this->mComprobanteElectronico->InsertarComprobanteElectronico($data);
    return $resultado;
  }

  function ActualizarComprobanteElectronico($data)
  {
    $resultado = $this->mComprobanteElectronico->ActualizarComprobanteElectronico($data);
    return $resultado;
  }

  function ConfirmarComprobanteElectronico($data) {

    $resultado = $this->ObtenerComprobanteElectronicoVigentePorIdComprobanteVenta($data);

    $input["IdComprobanteElectronico"] = $resultado->IdComprobanteElectronico;
    $input["UsuarioEnvio"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $input["FechaEnvio"] = $this->Base->ObtenerFechaServidor();
    $input["IndicadorEstadoCPE"] =$data["IndicadorEstadoCPE"];
    $input["CodigoError"] =$data["CodigoError"];
    $input["DescripcionError"] =$data["DescripcionError"];
    $input["CodigoHash"] =$resultado->CodigoHash;
    $resultado= $this->mComprobanteElectronico->ActualizarComprobanteElectronico($input);
    return $resultado;
  }

  function ObtenerComprobanteElectronicoVigentePorIdComprobanteVenta($data) {
    $resultado =$this->mComprobanteElectronico->ObtenerComprobanteElectronicoVigentePorIdComprobanteVenta($data);
    return $resultado;
  }

  function ImprimirComprobanteElectronico($data) {
    $parametros["nomArch"]=$data["NombreArchivoComprobante"];
    $parametros["sitArch"]="03";
    $session = $this->javabridge->IniciarJava();
    $sisem_report_java = $session->get("sisem_report_java");
    $resultado = $sisem_report_java->mostrarXml($parametros);

    return $resultado;
  }

  function EnviarComprobanteElectronico($data)
  {
    try
    {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      //OBTENEMOS PARAMETRO OSE
      $SunatOSE = $this->sConstanteSistema->ObtenerParametroEnvioSunatOSE();

      $RUC= $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL= $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL= $DatosEmpresa["ClaveSOL"];

      $nombreArchivoZip = $data["nombrearchivo"].".zip";
      $rutaZip = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombreArchivoZip;
      $archivoZip = file_get_contents($rutaZip);

      $nombreArchivoRpta ="R-".$nombreArchivoZip;
      $rutaArchivoRpta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombreArchivoRpta;

      $this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL,$CONTRASEÑA_SOL);
      $resultado=$this->soapclientsunat->sendBill($nombreArchivoZip,$archivoZip);

      if($resultado == false)
      {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["FaultCode"] = "SUNAT";
        $response["Error"] = "Error con la coneccion SUNAT.";
        $response["TipoError"] = CODIGO_ERROR_CONECCION_SUNAT;
        return $response;
      }
      else {
        if(is_array($resultado))
        {
          if(array_key_exists("Error", $resultado))
          {
            $response["FaultCode"] = ($SunatOSE == 1) ? $resultado["faultcode"].":".$resultado["faultstring"] : $resultado["faultcode"];
            $response["FaultString"] = ($SunatOSE == 1) ? $resultado["detail"] : $resultado["faultstring"];
            $response["Error"] = $response["FaultString"];
            $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
            return $response;
          }
        }
        else {
          file_put_contents($rutaArchivoRpta, $resultado);
          $response["FechaRespuestaEnvio"] =$this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
          $response["Data"] = "";
          return $response;
        }
      }
    }
    catch (SoapFault $fault)
    {
      $response["FaultCode"] = $fault->faultcode;
      $response["FaultString"] = $fault->faultstring;
      $response["Error"] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
      return $response;
    }
  }

  function ObtenerComprobanteElectronicoPorIdComprobanteVenta($data)
  {
    $resultado =$this->mComprobanteElectronico->ObtenerComprobanteElectronico($data);
    return $resultado;
  }

  function BorrarComprobanteElectronico($data)
  {
    $resultado = $this->mComprobanteElectronico->BorrarComprobanteElectronico($data);
    return $data;
  }

  function ConsultarComprobantesElectronico($data) {
    $resultado = $this->mComprobanteElectronico->ConsultarComprobantesElectronico($data);
    return $resultado;
  }

  function ConsultarComprobantesVentaElectronico($data) {
    $resultado = $this->mComprobanteElectronico->ConsultarComprobantesVentaElectronico($data);
    $ParametroCarpetaURLCPE=$this->sConstanteSistema->ObtenerParametroCarpetaURLCPE();
    $dataEmpresa["IdEmpresa"] = ID_EMPRESA;
    $resultadoEmpresa = $this->sEmpresa->ListarEmpresas($dataEmpresa);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["URLComprobanteElectronicoPDF"] = $ParametroCarpetaURLCPE.$value["NombreArchivoComprobante"].".pdf";
      $resultado[$key]["URLComprobanteElectronicoXML"] = $ParametroCarpetaURLCPE.$value["NombreArchivoComprobante"].".xml";
      $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($value);
    }
    return $resultado;
  }

  function ConsultarComprobantesVentaEnvio($data) {
    $resultado = $this->mComprobanteElectronico->ConsultarComprobantesVentaEnvio($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($value);
    }
    return $resultado;
  }

  function ConsultarComprobantesVentaEnvioPendiente($data) {
    $resultado = $this->mComprobanteElectronico->ConsultarComprobantesVentaEnvioPendiente($data);

    return $resultado;
  }

  function ConsultarComprobantesElectronicoPublicacionWeb($data) {
    $resultado = $this->mComprobanteElectronico->ConsultarComprobantesElectronicoPublicacionWeb($data);

    // foreach ($resultado as $key => $value) {
    //   $resultado[$key]["DetallesComprobanteVenta"] = $this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($value);
    // }
    return $resultado;
  }

  function ValidarComprobanteElectronico($data)
  {
    $resultado = $data;
    $data = [];
    foreach ($resultado as $key => $item)
    {
      if ($resultado[$key]["IndicadorEstado"] == 'A' )
      {
        return "Existe datos con estado A";
      }
      else
      {
        if ($resultado[$key]["IndicadorEstado"] = 'P')
        {
          $data[$key] = $resultado[$key];
        }
      }
    }

    for($i=0; $i < count($data) ; $i++)
    {
      $actualizar = $this->mComprobanteElectronico->ActivarComprobanteElectronico($data[$i]);
    }
    return "";

  }

  function RecrearXMLComprobanteElectronico($data, $destruir = true)
  {
    try
    {
      $DatosCV = $this->sComprobanteVenta->ObtenerComprobanteVenta($data);

      $descripcion = $DatosCV["MontoLetra"];
      $DatosCV["DetalleLeyenda"][0]["MontoLetra"]=$DatosCV["MontoLetra"];

      $resultado["error"] = "";
      $data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
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
      $nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
      $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
      $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
      $rutajson = $Plantillas_data->NombrePlantillaJSON;

      //Lineas del JSON NUEVO
      $nombre = $data["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
      $data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
      $data_json["plantilla"] =  APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
      $data_json["data"] = $data;
      //print_r($data_json["data"]);
      $json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);

      $data_xml["codigotipodocumento"] = $data["CodigoTipoDocumento"];
      $data_xml["nombrearchivo"] = $nombre;
      $data_xml["tipoarchivo"] = ".xml";
      $data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];

      $generar = $this->GenerarXMLComprobanteVenta($data_xml, $destruir);

      if($generar["error"] != "")
      {
        $resultado["error"] = $generar["error"];
        $resultado["title"] = "<strong>Ocurrio un error.</strong>";
        $resultado["type"] = "danger";
        $resultado["clase"] = "notify-danger";
        $resultado["message"] = $generar["msg"];

        throw new Exception($generar["error"]);//["error"]$e->getMessage(),$e->getCode(),$e);
        //echo $this->json->json_response($resultado);
        exit;
      }
      else
      {
        return $generar;
      }
    }
    catch (Exception $e)
    {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function GenerarXMLComprobanteElectronico($data, $destruir = true)
  {
    try
    {
      $DatosCV = $this->sComprobanteVenta->ObtenerComprobanteVenta($data);

      $descripcion = $DatosCV["MontoLetra"];
      $DatosCV["DetalleLeyenda"][0]["MontoLetra"]=$DatosCV["MontoLetra"];

      $resultado["error"] = "";
      $data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
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
      $nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
      $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
      $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
      $rutajson = $Plantillas_data->NombrePlantillaJSON;

      //Lineas del JSON NUEVO
      $nombre = $data["CodigoEmpresa"]."-".$data["CodigoTipoDocumento"]."-".$data["SerieDocumento"]."-".$data["NumeroDocumento"];
      $data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombre.".json";
      $data_json["plantilla"] =  APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"].$rutajson;
      $data_json["data"] = $data;
      //print_r($data_json["data"]);
      $json = $this->json->CrearArchivoJSONDesdePlantilla($data_json);

      $data_xml["codigotipodocumento"] = $data["CodigoTipoDocumento"];
      $data_xml["nombrearchivo"] = $nombre;
      $data_xml["tipoarchivo"] = ".xml";
      $data_xml["rutaenvio"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"];

      $generar = $this->GenerarXMLComprobanteVenta($data_xml, $destruir);
      if($generar["error"] != "")
      {
        $resultado["error"] = $generar["error"];
        $resultado["title"] = "<strong>Ocurrio un error.</strong>";
        $resultado["type"] = "danger";
        $resultado["clase"] = "notify-danger";
        $resultado["message"] = $generar["msg"];

        throw new Exception($generar["error"]);//["error"]$e->getMessage(),$e->getCode(),$e);
        //echo $this->json->json_response($resultado);
        exit;
      }
      else
      {
        $inputCPE["NombreArchivoComprobante"] = $nombre;
        $inputCPE["FechaGeneracion"] = $this->Base->ObtenerFechaServidor();
        $inputCPE["UsuarioGeneracion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $inputCPE["IndicadorEstado"] = ESTADO_ACTIVO;
        $inputCPE["IndicadorEstadoCPE"] = ESTADO_CPE_GENERADO;
        $inputCPE["IdComprobanteVenta"] = $DatosCV["IdComprobanteVenta"];
        $inputCPE["CodigoHash"] = (string) $generar["firma"];

        $comprobante = $this->ObtenerComprobanteElectronicoVigentePorIdComprobanteVenta($data);//ObtenerComprobanteElectronicoPorIdComprobanteVenta

        if($comprobante!=null)
        {
          $input2["IdComprobanteElectronico"] = $comprobante->IdComprobanteElectronico;
          $input2["CodigoHash"] = $comprobante->CodigoHash;
          $output = $this->BorrarComprobanteElectronico($input2);
          //$output=$this->ActualizarComprobanteElectronico($inputCPE);
        }
        //else
        //{
          //$output=$this->ActualizarComprobanteElectronico($inputCPE);
        //}
        $output = $this->InsertarComprobanteElectronico($inputCPE);
        $input = $DatosCV;//["IdComprobanteVenta"]=$data["IdComprobanteVenta"];
        $input["NombreArchivoComprobante"] = $nombre;
        $input["CodigoHash"] = $inputCPE["CodigoHash"];
        $input["IdComprobanteElectronico"] = $output["IdComprobanteElectronico"];        
        return $input;
      }
    }
    catch (Exception $e)
    {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }


  function GenerarXMLyZIPComprobanteVenta($data){
    try {
      //Definiendo cabeceras
      //Se obtiene los datos de la base de datos que se enviaran a la funcion [Se creo una funcion para suplir]
      /*Datos principales de la Data*/
      $rutasArchivo = Array();
      $codigotipodocumento = $data["codigotipodocumento"];//Factura
      $nombrearchivo = $data["nombrearchivo"];
      $tipoarchivo = $data["tipoarchivo"];
      $nombrearchivoxml = $nombrearchivo.$tipoarchivo;
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $data_documento["CodigoTipoDocumentoElectronico"] = $codigotipodocumento;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $DatosEmpresa["RutaCertificado"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CERTIFI"].$DatosEmpresa["NombreCertificado"];
      $DatosEmpresa["RutaDocumentoSunat"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CERTIFI"].$DatosEmpresa["NombreLlaveFacturador"];
      $Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
      $nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
      $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
      $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;
      /*Rutas de Archivo*/
      $rutaArchivoJSON = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivo.".JSON";
      $rutaDocumentoXML = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;
      $rutadestino= APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;//ANTES RUTA_CARPETA_XML;
      $rutaDocumentoFirmado = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;
      $rutaDocumentoComprimido = $data["rutaenvio"].$nombrearchivo.".zip";

      $parametros_firmado["nombrearchivo"] = $nombrearchivo;
      $parametros_firmado["claveprivadacertificado"] = $DatosEmpresa["ClavePrivadaCertificado"];
      $parametros_firmado["rutaarchivoempresa"] = $rutadestino;
      $parametros_firmado["rutadocumentosunat"] = $DatosEmpresa["RutaDocumentoSunat"];

      //DECLARANDO LIBRERIAS A USAR
      $inicioCertificado = $this->javabridge->IniciarCertificado($DatosEmpresa);
      $Certificado = $inicioCertificado->get("com.sisemperu.sunat.Certificado");

      $inicioArchivoJSON = $this->javabridge->IniciarArchivoJSON($rutaArchivoJSON);
      $ArchivoJSON = $inicioArchivoJSON->get("com.sisemperu.sunat.ArchivoJSON");
      $inicioTemplateManager = $this->javabridge->IniciarTemplateManager(APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"], $nombreplantilla);
      $TemplateManager = $inicioTemplateManager->get("com.sisemperu.sunat.TemplateManager");

      $inicioXMLSchema = $this->javabridge->IniciarXMLSchema($rutaEsquema);
      $XMLSchema = $inicioXMLSchema->get("com.sisemperu.sunat.XMLSchema");

      $inicioXMLTransformer = $this->javabridge->IniciarXMLTransformer($rutaplantilla);
      $XMLTransformer = $inicioXMLTransformer->get("com.sisemperu.sunat.XMLTransformer");

      $inicioXMLSignaturer = $this->javabridge->IniciarXMLSignaturer($parametros_firmado);
      $XMLSignaturer = $inicioXMLSignaturer->get("com.sisemperu.sunat.XMLSignaturer");

      $inicioZip = $this->javabridge->IniciarZip($nombrearchivo);
      $Zip = $inicioZip->get("com.sisemperu.sunat.Zip");

      /*AQUI SE VALIDA EL CERTIFICADO*/
      $validacion_certificado = $Certificado->validar();
      if($validacion_certificado != ""){
        //throw new Exception("Error al Validar Certificado: ".$validacion_certificado);
        throw new Exception(serialize(['id'=>0,'msg'=>"Error al Validar Certificado: ",'error'=>$validacion_certificado]));
      }
      ////////////////////////////////////////////////////
      /*AQUI SE CREA TXT A XML || AHORA JSON*/
      $factura = $ArchivoJSON->Mapear();
      $renderizado= $TemplateManager->renderizar($factura["Data"], $rutaDocumentoXML);
      if($renderizado != "")
      {
        //throw new Exception("Error al Renderizar Archivo: ".$renderizado);
        throw new Exception(serialize(['id'=>1,'msg'=>"Error al Renderizar Archivo: ",'error'=>$renderizado]));
      }
      //print_r($Plantillas_data);
      //echo $rutaArchivoJSON;

      //echo $factura["Data"];
      //exit;
      ///////////////////////////////////////////////////////////////////////
      /*AQUI SE VALIDA ESQUEMA ELECTRONICO*/
      $validacion_esquema= $XMLSchema->validar($rutaDocumentoXML);
      if($validacion_esquema != "")
      {
        //throw new Exception("Error al Validar Esquema: ".$validacion_esquema);
        throw new Exception(serialize(['id'=>2,'msg'=>"Error al Validar Esquema: ",'error'=>$validacion_esquema]));
      }
      ////////////////////////////////////////////////////
      /*AQUI SE TRANSFORMA XML*/
      //echo $rutaplantilla."-".$rutaDocumentoXML."-".$rutadestino;
      //exit;
      //print_r($Plantillas_data);
      //echo $rutaDocumentoXML.$rutadestino.$rutaplantilla;
      //exit;
      $transformacionXML = $XMLTransformer->transformar($rutaDocumentoXML, $rutadestino);
      if($transformacionXML != ""){
        //throw new Exception("Error al Transformar XML: ".$transformacionXML);
        throw new Exception(serialize(['id'=>3,'msg'=>"Error al Transformar XML: ",'error'=>$transformacionXML]));
      }
      ////////////////////////////////////
      /*AQUI SE REALIZA EL FIRMADO DE ARCHIVO*/
      echo "-prueba-<br>";
      echo $rutaDocumentoFirmado;
      $firmado= $XMLSignaturer->firmar($rutaDocumentoFirmado);
      if($firmado == ""){
        //throw new Exception("Error al Firmar Documento: ".$firmado);
        throw new Exception(serialize(['id'=>4,'msg'=>"Error al Firmar Documento: ",'error'=>$firmado]));
      }
      ////////////////////////
      /*AQUI SE REALIZA EL ZIPEADO DE ARCHIVO*/
      $zipeado= $Zip->comprimir($rutaDocumentoFirmado,$rutaDocumentoComprimido);
      if($zipeado != ""){
        //throw new Exception("Error al Zipear Documento: ".$zipeado);
        throw new Exception(serialize(['id'=>5,'msg'=>"Error al Zipear Documento: ",'error'=>$zipeado]));
      }

      //Destruyendo Sesiones
      $this->javabridge->Destruir();

      //return "Exitoso.";
      $response["msg"] = "Proceso Correctamente";
      $response["error"] = "";

      return $response;
    } catch (Exception $e) {
      $this->javabridge->Destruir();

      $data = unserialize($e->getMessage());
      $response = null;
      $mensaje_error = (string)$data['error'];
      $error = $data['msg'].$mensaje_error;
      if($data['id'] == 3)
      {

        $dato["CodigoError"] = $this->ObtenerCodigoString($mensaje_error);
        if(is_numeric($dato["CodigoError"])){
          $errorcode = $this->sErrorFacturacionElectronica->ObtenerDescripcionErrorCodigo($dato)[0];
          $response["msg"] = $errorcode["NombreErrorFacturacionElectronica"];
        }
        else {
          $response["msg"] = "Ha ocurrido un Error al Momento de la validación.";
        }
        //$response["msg"] = $dato["CodigoError"];
        $response["error"] = $error;
      }
      else
      {
        $response["msg"] = $error;
        $response["error"] = $error;
      }

      return $response;
    }

  }

  function GenerarKeyCertificadoDigital()
  {
    try {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $DatosEmpresa["RutaCertificado"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CERTIFI"].$DatosEmpresa["NombreCertificado"];
      $DatosEmpresa["RutaDocumentoSunat"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CERTIFI"].$DatosEmpresa["NombreLlaveFacturador"];

      $inicioCertificado = $this->javabridge->IniciarCertificado($DatosEmpresa);
      $Certificado = $inicioCertificado->get("com.sisemperu.sunat.Certificado");

      /*AQUI SE VALIDA EL CERTIFICADO*/
        $validacion_certificado = $Certificado->validar();

        if($validacion_certificado != ""){
          $this->TemporalLog($validacion_certificado);
          throw new Exception(serialize(['id'=>0,'msg'=>"Error al Validar Certificado: ",'error'=>$validacion_certificado]));
        }

        $this->javabridge->Destruir();

        $response["msg"] = "Proceso Correctamente";
        $response["error"] = "";

        return $response;

    } catch (Exception $e) {
      $this->javabridge->Destruir();
      $data = unserialize($e->getMessage());
      $response = null;
      $mensaje_error = (string)$data['error'];
      $error = $data['msg'].$mensaje_error;
      if($data['id'] == 3)
      {
        $dato["CodigoError"] = $this->ObtenerCodigoString($mensaje_error);
        if(is_numeric($dato["CodigoError"])){
          $errorcode = $this->sErrorFacturacionElectronica->ObtenerDescripcionErrorCodigo($dato)[0];
          $response["msg"] = $errorcode["NombreErrorFacturacionElectronica"];
        }
        else {
          $response["msg"] = "Ha ocurrido un Error al Momento de la validación.";
        }
        //$response["msg"] = $dato["CodigoError"];
        $response["error"] = $error;
      }
      else
      {
        $response["msg"] = $error;
        $response["error"] = $error;
      }

      return $response;

    }

  }

  function GenerarXMLComprobanteVenta($data,$destruir=true){
    try {
      //Definiendo cabeceras
      //Se obtiene los datos de la base de datos que se enviaran a la funcion [Se creo una funcion para suplir]
      /*Datos principales de la Data*/
      $rutasArchivo = Array();
      $codigotipodocumento = $data["codigotipodocumento"];//Factura
      $nombrearchivo = $data["nombrearchivo"];
      $tipoarchivo = $data["tipoarchivo"];
      $nombrearchivoxml = $nombrearchivo.$tipoarchivo;
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $data_documento["CodigoTipoDocumentoElectronico"] = $codigotipodocumento;
      //print_r($data_documento);
      //exit;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      //$DatosEmpresa = $this->ObtenerDatosEmpresa();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $DatosEmpresa["RutaCertificado"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CERTIFI"].$DatosEmpresa["NombreCertificado"];
      $DatosEmpresa["RutaDocumentoSunat"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CERTIFI"].$DatosEmpresa["NombreLlaveFacturador"];

      $Plantillas_data = $this->sTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data_documento);
      $nombreplantilla= $Plantillas_data->NombrePlantillaFTL;
      $rutaEsquema = $Plantillas_data->NombrePlantillaXSD;
      $rutaplantilla = $Plantillas_data->NombrePlantillaXLS;

      $rutaArchivoJSON = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivo.".JSON";
      $rutaDocumentoXML = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;
      $rutadestino= APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;
      $rutaDocumentoFirmado = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombrearchivoxml;

      $parametros_firmado["nombrearchivo"] = $nombrearchivo;
      $parametros_firmado["claveprivadacertificado"] = $DatosEmpresa["ClavePrivadaCertificado"];
      $parametros_firmado["rutaarchivoempresa"] = $rutadestino;
      $parametros_firmado["rutadocumentosunat"] = $DatosEmpresa["RutaDocumentoSunat"];

      //DECLARANDO LIBRERIAS A USAR
      // $inicioCertificado = $this->javabridge->IniciarCertificado($DatosEmpresa);
      // $Certificado = $inicioCertificado->get("com.sisemperu.sunat.Certificado");

      $inicioArchivoJSON = $this->javabridge->IniciarArchivoJSON($rutaArchivoJSON);
      $ArchivoJSON = $inicioArchivoJSON->get("com.sisemperu.sunat.ArchivoJSON");

      $inicioTemplateManager = $this->javabridge->IniciarTemplateManager(APP_PATH.$DatosCarpeta["RUTA_CARPETA_TEMPLATES"], $nombreplantilla);
      $TemplateManager = $inicioTemplateManager->get("com.sisemperu.sunat.TemplateManager");

      //$inicioXMLSchema = $this->javabridge->IniciarXMLSchema($rutaEsquema);
      //$XMLSchema = $inicioXMLSchema->get("com.sisemperu.sunat.XMLSchema");

      //$inicioXMLTransformer = $this->javabridge->IniciarXMLTransformer($rutaplantilla);
      //$XMLTransformer = $inicioXMLTransformer->get("com.sisemperu.sunat.XMLTransformer");

      $inicioXMLSignaturer = $this->javabridge->IniciarXMLSignaturer($parametros_firmado);
      $XMLSignaturer = $inicioXMLSignaturer->get("com.sisemperu.sunat.XMLSignaturer");
      /*AQUI SE VALIDA EL CERTIFICADO*/
        $this->TemporalLog("Inicio Validacion Certificado");
        $validacion_certificado ="";// $Certificado->validar();

        if($validacion_certificado != ""){

          throw new Exception(serialize(['id'=>0,'msg'=>"Error al Validar Certificado: ",'error'=>$validacion_certificado]));
        }
        $this->TemporalLog("Fin Validacion Certificado");
      ////////////////////////////////////////////////////
      /*AQUI SE CREA TXT A XML || AHORA JSON*/
      $this->TemporalLog("Inicio Renderizacion JSON");
      $factura = $ArchivoJSON->Mapear();

      $renderizado= $TemplateManager->renderizar($factura["Data"], $rutaDocumentoXML);
      if($renderizado != "")
      {
        //throw new Exception("Error al Renderizar Archivo: ".$renderizado);
        $this->TemporalLog($renderizado);
        throw new Exception(serialize(['id'=>1,'msg'=>"Error al Renderizar Archivo: ",'error'=>$renderizado]));
      }
      $this->TemporalLog("Fin Renderizacion JSON");
      ///////////////////////////////////////////////////////////////////////
      /*AQUI SE VALIDA ESQUEMA ELECTRONICO*/
      $this->TemporalLog("Inicio Validacion Documento");
      $validacion_esquema="";//$XMLSchema->validar($rutaDocumentoXML);

      if($validacion_esquema != "")
      {
        //throw new Exception("Error al Validar Esquema: ".$validacion_esquema);
        $this->TemporalLog($validacion_esquema);
        throw new Exception(serialize(['id'=>2,'msg'=>"Error al Validar Esquema: ",'error'=>$validacion_esquema]));
      }
      $this->TemporalLog("Fin Validacion Documento");
      ////////////////////////////////////////////////////
      /*AQUI SE TRANSFORMA XML*/
      $this->TemporalLog("Inicio TransformacionXML");
      $transformacionXML ="";//$XMLTransformer->transformar($rutaDocumentoXML, $rutadestino);
      if($transformacionXML != ""){
        //throw new Exception("Error al Transformar XML: ".$transformacionXML);
        $this->TemporalLog($transformacionXML);
        throw new Exception(serialize(['id'=>3,'msg'=>"Error al Transformar XML: ",'error'=>$transformacionXML]));
      }
      $this->TemporalLog("Fin TransformacionXML");
      ////////////////////////////////////
      /*AQUI SE REALIZA EL FIRMADO DE ARCHIVO*/
      $this->TemporalLog("Inicio Firmado Archivo");

      $firmado= $XMLSignaturer->firmar($rutaDocumentoFirmado);
      // print_r($parametros_firmado);      
      //echo $rutaDocumentoFirmado;
      $this->TemporalLog($firmado);      
      if (strpos($firmado, 'Causa') === true) {
        echo $firmado;
        //throw new Exception("Error al Firmar Documento: ".$firmado);
        $this->TemporalLog($firmado);        
        throw new Exception(serialize(['id'=>4,'msg'=>"Error al Firmar Documento: ",'error'=>$firmado]));
      }

      $this->TemporalLog("Fin Firmado Archivo");

      //Destruyendo Sesiones
      $this->TemporalLog("Inicio Destruir");
      if($destruir == true) {
        $this->javabridge->Destruir();
      }
      $this->TemporalLog("Fin Destruir");

      //return "Exitoso.";
      $response["msg"] = "Proceso Correctamente";
      $response["firma"] = $firmado;
      $response["error"] = "";

      return $response;
    } catch (Exception $e) {
      $this->javabridge->Destruir();
      $data = unserialize($e->getMessage());
      $response = null;
      $mensaje_error = (string)$data['error'];
      $error = $data['msg'].$mensaje_error;
      if($data['id'] == 3)
      {
        $dato["CodigoError"] = $this->ObtenerCodigoString($mensaje_error);
        if(is_numeric($dato["CodigoError"])){
          $errorcode = $this->sErrorFacturacionElectronica->ObtenerDescripcionErrorCodigo($dato)[0];
          $response["msg"] = $errorcode["NombreErrorFacturacionElectronica"];
        }
        else {
          $response["msg"] = "Ha ocurrido un Error al Momento de la validación.";
        }
        //$response["msg"] = $dato["CodigoError"];
        $response["error"] = $error;
      }
      else
      {
        $response["msg"] = $error;
        $response["error"] = $error;
      }

      return $response;
    }

  }

  function TemporalLog($text)
  {
    if(CREACION_LOG_TEMPORAL == true)
    {
      $now = DateTime::createFromFormat('U.u', microtime(true));
      $fecha = (String) $now->format("Y-m-d");
      $data["name"] = "Velocidad_Creacion-".$fecha.".log";
      $data["url"] = APP_PATH."assets/data/facturacionelectronica/error/";
      $data["header"] = $text;
      $data["body"] = $fecha;
      $data["footer"] = $fecha;

      $this->logger->CrearLog($data);
    }
  }

  function GenerarZIPEnvio($data,$destruir=true){
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
      $zipeado= $Zip->comprimir($rutaDocumentoFirmado,$rutaDocumentoComprimido);

      if($zipeado != ""){
        throw new Exception(serialize(['id'=>5,'msg'=>"Error al Zipear Documento: ",'error'=>$zipeado]));
      }

      //Destruyendo Sesiones
      if($destruir == true) {
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
      $mensaje_error = (string)$data['error'];
      $error = $data['msg'].$mensaje_error;

      $response["msg"] = $error;
      $response["error"] = $error;

      return $response;
    }

  }

  function EliminarZIPEnvio($data,$destruir=true){
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
      if(!$eliminado){
        throw new Exception(serialize(['id'=>5,'msg'=>"Error al Zipear Documento: ",'error'=>$eliminado]));
      }

      $response["msg"] = "Proceso Correctamente";
      $response["error"] = "";

      return $response;
    } catch (Exception $e) {
      $data = unserialize($e->getMessage());
      $response = null;
      $mensaje_error = (string)$data['error'];
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
    $data['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data);
    //print_r($resultado);
    //exit;
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      return $resultado;
    }
  }

  function ObtenerFormatoJasperVentaZofraTicket()
  {
    $data['IdParametroSistema']= ID_NOMBRE_JASPER_FORMATO_TICKET_ZOFRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerFormatoJasperVentaTicket()
  {
    $data['IdParametroSistema']= ID_NOMBRE_JASPER_FORMATO_TICKET;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  // function GenerarReportePDF($data, $expo = false)
  // {
  //   $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];

  //   $name_archive = $data["SerieDocumento"]."-".$data["NumeroDocumento"];
  //   if($expo)
  //   {
  //     $name_archive = $data["NombreArchivoComprobante"];
  //   }

  //   $ruta_pdf = RUTA_CARPETA_REPORTES_GENERADOS_PDF.$name_archive.".pdf";
  //   $CodigoSerieSubTipo=substr($data["SerieDocumento"], 0,2);

  //   if($CodigoSerieSubTipo =="BZ") {
  //     $this->reporter->RutaReporte = RUTA_CARPETA_REPORTES.NOMBRE_BOLETA_ELECTRONICO_TIPO_Z_A4;
  //   }
  //   else if ($CodigoSerieSubTipo =="BT") {
  //     $this->reporter->RutaReporte = RUTA_CARPETA_REPORTES.NOMBRE_BOLETA_ELECTRONICO_TIPO_T_A4;
  //   }
  //   else {
  //     $this->reporter->RutaReporte = RUTA_CARPETA_REPORTES.NOMBRE_DOCUMENTO_ELECTRONICO_A4;
  //   }

  //   $this->reporter->RutaPDF = $ruta_pdf;
  //   $this->reporter->SetearParametros($parametros);
  //   $resultado = $this->reporter->ExportarReporteComoPDF();

  //   $output["APP_RUTA"] =APP_PATH_URL."assets/reportes/Venta/".$name_archive.".pdf";//"http://".$_SERVER["HTTP_HOST"]."/sisem/
  //   $output["BASE_RUTA"] = APP_PATH."assets/reportes/Venta/".$name_archive.".pdf";//"http://".$_SERVER["HTTP_HOST"]."/sisem/

  //   return $output;
  // }

  function GenerarReportePDF($data, $expo = false)
  {
    $parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
    $SerieDocumento=$data["SerieDocumento"];
    $name_archive = $data["SerieDocumento"]."-".$data["NumeroDocumento"];
    if($expo)
    {
      $name_archive = $data["NombreArchivoComprobante"];
    }

    $ruta_pdf = RUTA_CARPETA_REPORTES_GENERADOS_PDF.$name_archive.".pdf";
    
    $CodigoSerie=substr($data["SerieDocumento"], 0,1);
    $CodigoSerieSubTipo=substr($data["SerieDocumento"], 0,2);

    $rutaFormato = RUTA_CARPETA_REPORTES.NOMBRE_FACTURA_ELECTRONICO;
    $indicadorImpresion = INDICADOR_FORMATO_OTRO;

    if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_BOLETA) {
      if ($CodigoSerieSubTipo == "BT") {
      $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA_T;
      }
      else if ($CodigoSerieSubTipo == "BZ") {
      $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA_Z;
      }
      else {
        if ($CodigoSerie == "B") {
        $indicadorImpresion = INDICADOR_FORMATO_BOLETA_ELECTRONICA_VENTA;
        } else {
        $indicadorImpresion = INDICADOR_FORMATO_BOLETA_FISICA_VENTA;
        }
      }
    }
    else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_FACTURA) {
      if ($CodigoSerie == "F") {
      $indicadorImpresion = INDICADOR_FORMATO_FACTURA_ELECTRONICA_VENTA;
      } else {
      $indicadorImpresion = INDICADOR_FORMATO_FACTURA_FISICA_VENTA;
      }
    }
    else if ($data["IdTipoDocumento"] == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
    $indicadorImpresion = INDICADOR_FORMATO_ORDEN_PEDIDO;
    }
    else {
    $indicadorImpresion = INDICADOR_FORMATO_OTRO;
    }
    $rutaplantilla = RUTA_CARPETA_CONFIG_IMPRESION."config-".$this->shared->GetDeviceName().".json";
    $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion,$SerieDocumento,$rutaplantilla);
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

  function ConsultarEstadoCDR($data)
  {
    try
    {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $RUC= $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL= $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL= $DatosEmpresa["ClaveSOL"];

      $data["ruc"] = $DatosEmpresa["CodigoEmpresa"];
      $data["tipo"] = $data["CodigoTipoDocumento"];
      $data["serie"] = $data["SerieDocumento"];
      $data["numero"] = $data["NumeroDocumento"];
      $nombreArchivoZip = $data["ruc"].'-'.$data["tipo"].'-'.$data["serie"].'-'.$data["numero"].".zip";

      $nombreArchivoRpta ="R-".$nombreArchivoZip;
      $rutaArchivoRpta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombreArchivoRpta;

      $this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL,$CONTRASEÑA_SOL);
      $resultado=$this->soapclientsunat->getStatusCDR($data);
      if($resultado == false)
      {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["FaultCode"] = "SUNAT";
        $response["Error"] = "Error con la coneccion SUNAT.";
        $response["TipoError"] = CODIGO_ERROR_CONECCION_SUNAT;
        return $response;
      }
      else {
        if(is_array($resultado))
        {
          if(array_key_exists("Error", $resultado))
          {
            $response["FaultCode"] = $resultado["faultcode"];
            $response["FaultString"] = $resultado["faultstring"];
            $response["Error"] = $resultado["faultstring"];
            $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
            return $response;
          }
        }
        else if(!property_exists($resultado, 'content'))
        {
          $response["FaultCode"] = $resultado->statusCode;
          $response["FaultString"] = $resultado->statusMessage;
          $response["Error"] = $resultado->statusMessage;
          $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
          return $response;
        }
        else {
          file_put_contents($rutaArchivoRpta, $resultado->content);
          $response["FechaRespuestaEnvio"] =$this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
          $response["Data"] = "";
          return $response;
        }
      }
    }
    catch (SoapFault $fault)
    {
      $response["FaultCode"] = $fault->faultcode;
      $response["FaultString"] = $fault->faultstring;
      $response["Error"] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
      return $response;
    }
  }

  //SOLO PARA FACTURAS Y SUS NOTAS QUE EMPIECEN CON 'F'
  function ConsultarEstadoComprobante($data)
  {
    try
    {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $RUC= $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL= $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL= $DatosEmpresa["ClaveSOL"];

      $data["ruc"] = $DatosEmpresa["CodigoEmpresa"];
      $data["tipo"] = $data["CodigoTipoDocumento"];
      $data["serie"] = $data["SerieDocumento"];
      $data["numero"] = $data["NumeroDocumento"];

      $this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL,$CONTRASEÑA_SOL);
      $resultado=$this->soapclientsunat->getStatusComprobante($data);
      if($resultado == false)
      {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["FaultCode"] = "SUNAT";
        $response["Error"] = "Error con la coneccion SUNAT.";
        $response["TipoError"] = CODIGO_ERROR_CONECCION_SUNAT;
        return $response;
      }
      else {
        if(is_array($resultado))
        {
          if(array_key_exists("Error", $resultado))
          {
            $response["FaultCode"] = $resultado["faultcode"];
            $response["FaultString"] = $resultado["faultstring"];
            $response["Error"] = $resultado["faultstring"];
            $response["TipoError"] = CODIGO_ERROR_RECHAZO_SUNAT;
            return $response;
          }
        }
        else {
          $response = (array) $resultado;
          return $response;
        }
      }
    }
    catch (SoapFault $fault)
    {
      $response["FaultCode"] = $fault->faultcode;
      $response["FaultString"] = $fault->faultstring;
      $response["Error"] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
      return $response;
    }
  }

  //VALIDADOR DE COMPROBANTE ELECTRONICOS
  function ConsultarEstadoComprobanteValido($data)
  {
    try
    {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $RUC= $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL= $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL= $DatosEmpresa["ClaveSOL"];

      $data["rucEmisor"] = $DatosEmpresa["CodigoEmpresa"];
      $data["tipoCDP"] = $data["CodigoTipoDocumento"];
      $data["serieCDP"] = $data["SerieDocumento"];
      $data["numeroCDP"] = $data["NumeroDocumento"];
      $data["tipoDocIdReceptor"] = $data["CodigoDocumentoIdentidad"];
      $data["numeroDocIdReceptor"] = $data["NumeroDocumentoIdentidad"];
      $data["fechaEmision"] = $data["FechaEmision"];
      $data["importeTotal"] = $data["Total"];

      $this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL,$CONTRASEÑA_SOL);
      $resultado=$this->soapclientsunat->validarComprobanteElectronico($data);
      if($resultado == false)
      {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["FaultCode"] = "SUNAT";
        $response["Error"] = "Error con la coneccion SUNAT.";
        $response["TipoError"] = CODIGO_ERROR_CONECCION_SUNAT;
        return $response;
      }
      else {
        if(is_array($resultado))
        {
          if(array_key_exists("Error", $resultado))
          {
            $response["FaultCode"] = $resultado["faultcode"];
            $response["FaultString"] = $resultado["faultstring"];
            $response["Error"] = $resultado["faultstring"];
            return $response;
          }
        }
        else {
          $response = (array) $resultado;
          return $response;
        }
      }
    }
    catch (SoapFault $fault)
    {
      $response["FaultCode"] = $fault->faultcode;
      $response["FaultString"] = $fault->faultstring;
      $response["Error"] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
      return $response;
    }
  }

  function ConsultarFacturasNoEnviadasSunat()
  {
    $numerodias = $this->sConstanteSistema->ObtenerParametroSieteDiasSunat();

    $response = array();
    for ($i=1; $i <= $numerodias; $i++) { 
      $resultado = $this->mComprobanteElectronico->ConsultarFacturasNoEnviadasSunat($i);
      $response = $resultado;
      if($response > 0)
      {
        break;
      }
    }

    return $response;
  }

  function ConsultarCantidadFacturasNoEnviadasSunat($dias = false)
  {
    $numerodias = $this->sConstanteSistema->ObtenerParametroSieteDiasSunat();
    $response = 0;
    $cantidaddias = 1;
    for ($i=1; $i <= $numerodias; $i++) { 
      $resultado = $this->mComprobanteElectronico->ConsultarCantidadFacturasNoEnviadasSunat($i);
      $response = $resultado->Total;
      $cantidaddias = $i;
      if($response > 0)
      {
        break;
      }
    }
    //aplicaria un metodo aqui para saber hace cuantos dias vencidos y cuantas facturas se vencieron. ConsultarFacturasNoEnviadasVencidasSunat 
    return ($dias) ? $cantidaddias : $response; //RETORNAMOS EL NUMERO DE DIAS A VENCERSE || RETORNAMOS LA CANTIDAD DE FACTURAS A ENVIAR
  }

  function ConsultarRangoFechasFacturasNoEnviadas()
  {
    $dias = (int) $this->ConsultarCantidadFacturasNoEnviadasSunat(true);
    $diasReales = $dias - 1;
    $diasSemana = PLAZO_DE_DIAS_SUNAT - $diasReales;
    
    $fechaactual = $this->Base->ObtenerFechaServidor("d-m-Y");
    $fechasietedias = date("d-m-Y",strtotime($fechaactual."- ".$diasSemana." days"));

    $fechaFormateada = convertirFechaES($fechasietedias);
    $fechaDataBase = convertToDate($fechasietedias);
    $data["FechaInicio"] = $fechaDataBase;
    $data["FechaFin"] = $fechaDataBase;//($diasReales > 0) ? date("d-m-Y",strtotime($fechasietedias."+ ".$diasReales." days")) : $fechasietedias;
    $data["FechaInicioFormateada"] = $fechaFormateada;
    $data["FechaFinFormateada"] = $fechaFormateada;
    return $data;
  }

  function ConsultarComprobanteElectronicosParaValidacion($data) {
    $resultado = $this->mComprobanteElectronico->ConsultarComprobanteElectronicosParaValidacion($data);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($value["FechaEmision"]);
      $resultado[$key]["Estado"] = "";
      $resultado[$key]["Codigo"] = "";
    }
    return $resultado;
  }

  function ValidarFechaVencimientoCertificadoDigital() {
    $datosEmpresa = $this->sEmpresa->ObtenerDatosEmpresa();
    $hoy = $this->Base->ObtenerFechaServidor("Y-m-d");
    $hoy = new DateTime($hoy);
    $fechaFinCertificado = $datosEmpresa["FechaFinCertificadoDigital"];
    $fechaFinCertificado = new DateTime($fechaFinCertificado);
    $parametroDiasExpiracionCertificado = $this->sConstanteSistema->ObtenerParametroNumeroDiasExpiracionCertificado();
    $diff = $hoy->diff($fechaFinCertificado);
    
    if($diff->invert == 1)
    {
      $response["msg"] = "Advertencia: Su certificado digital expiró hace ".$diff->days." días, si no renueva no podrá emitir facturas electronicas, coordine con su administrador.";
      $response["error"] = 2;
      return $response;
    }
    else
    {
      if($diff->days <= $parametroDiasExpiracionCertificado)
      {
        $response["msg"] = "Advertencia: Su certificado digital expirará en ".$diff->days." días, y si no renueva no podrá emitir facturas electronicas, coordine con su administrador.";
        $response["error"] = 1;
        return $response;
      }
      else
      {
        $response["msg"] = "";
        $response["error"] = 0;
        return $response;
      }
    }

  }
}
