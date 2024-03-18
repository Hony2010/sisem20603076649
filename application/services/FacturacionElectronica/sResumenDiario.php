<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sResumenDiario extends MY_Service {

  private $SunatOSE = "";

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('soapclientsunat');
    $this->load->library('mapper');
    $this->load->library('zipper');
    $this->load->model('FacturacionElectronica/mResumenDiario');
    $this->load->service('Configuracion/General/sEmpresa');
    $this->load->service('FacturacionElectronica/sDetalleResumenDiario');
    $this->load->service('FacturacionElectronica/sCorrelativoResumenDiario');
    $this->load->service('Configuracion/General/sConstanteSistema');

    $this->ResumenDiario = $this->mResumenDiario->ResumenDiario;
    $this->SunatOSE = $this->sConstanteSistema->ObtenerParametroEnvioSunatOSE();
  }


  function ConsultarComprobantesVenta($data)
  {
    $resultado = $this->mResumenDiario->ConsultarComprobantesVenta($data);
    return $resultado;
  }

  function ConsultarResumenesDiario($data)
  {
    $resultado = $this->mResumenDiario->ConsultarResumenesDiario($data);

    foreach ($resultado as $key => $value) {
      $items= $this->sDetalleResumenDiario->ConsultarDetallesResumenDiario($value);
      $resultado[$key]["DetallesResumenDiario"]=$items;
    }

    return $resultado;
  }

  function ValidarResumenDiario($data) {
    $parametro = NUMERO_COMPROBANTES_MAXIMO_RESUMEN_DIARIO;
    $resultado="";
    $total =count($data);
    if ($total  > $parametro) {
      $resultado = $resultado . "Usted ha seleccionado ".$total." comprobantes y solo se puede enviar en un resumen hasta ".$parametro." comprobantes\n";
    }

    return $resultado;
  }

  function PrepararResumenDiario($data) {
    //$resultado = $this->ValidarReusumenDiario($data);
    $resultado ="";

    $resultado=$this->ValidarResumenDiario($data);

    if($resultado =="")
    {
      $fecha = $this->Base->ObtenerFechaServidor("Y-m-d");
      $fecharesumendiario = $this->Base->ObtenerFechaServidor("Ymd");

      $input["IdEmpresa"]=$this->sEmpresa->ObtenerIdEmpresa();
      $input["FechaResumenDiario"] =$fecha;
      $dataEmpresa = $this->sEmpresa->ListarEmpresas($input);
      $numeroenvio = $this->sCorrelativoResumenDiario->ObtenerNuevoCorrelativoResumenDiario($input);
      $input["NumeroEnvio"] = $this->shared->rellenar_ceros($numeroenvio,5);
      $input["NombreResumenDiario"]=$dataEmpresa[0]["CodigoEmpresa"].'-'.CODIGO_TIPO_DOCUMENTO_RESUMEN_DIARIO.'-'.$fecharesumendiario.'-'.$input["NumeroEnvio"];
      $input["CodigoComunicacion"]=CODIGO_TIPO_DOCUMENTO_RESUMEN_DIARIO.'-'.$fecharesumendiario.'-'.$input["NumeroEnvio"];
      $input["FechaEmisionDocumento"] =$data[0]["FechaEmision"];
      $input["FechaGeneracionResumenDiario"]=$this->Base->ObtenerFechaServidor();
      $input["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      $input["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
      $input["IndicadorEstado"]=ESTADO_DOCUMENTO_ACTIVO;
      $input["IndicadorEstadoResumenDiario"]=ESTADO_CPE_GENERADO;

      $resultado = $input;

      foreach ($data as $key => $value) {
        $value["IndicadorEstadoResumenDiario"]=ESTADO_CPE_GENERADO;
        $value["NumeroItem"]=$key+1;
        $resultado["DetallesResumenDiario"][]=$value;
      }

      $output["data"]=$resultado;
      $output["dataEmpresa"] = $dataEmpresa;
      $output["error"] = "";
    }
    else {
      $output["error"] = $resultado;
    }

    return $output;
  }

  function InsertarResumenDiario($data) {
    //COMPROBANDO DETALLES
    $data["IndicadorEstadoResumenDiario"] = ESTADO_CPE_EN_PROCESO;
    foreach ($data["DetallesResumenDiario"] as $key => $value) {
      $data["DetallesResumenDiario"][$key]["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
    }

    $resultado = $this->mResumenDiario->InsertarResumenDiario($data);
    $detalle = $this->sDetalleResumenDiario->InsertarDetallesResumenDiario($data["DetallesResumenDiario"], $resultado);
    foreach ($detalle as $key => $value) {
      $value["IdComprobanteVenta"] = $detalle[$key]["IdComprobanteVenta"];
      $value["IndicadorEstadoResumenDiario"] = $detalle[$key]["IndicadorEstadoResumenDiario"];
      $value["IndicadorEstado"] = $detalle[$key]["IndicadorEstado"];
      $value["IndicadorEstadoComunicacionBaja"] = $detalle[$key]["IndicadorEstadoComunicacionBaja"];
      $value["IndicadorEstadoCPE"] = $detalle[$key]["IndicadorEstadoCPE"];
      $value["CodigoEstado"] = $detalle[$key]["CodigoEstado"];
      $value["SerieDocumento"] = $detalle[$key]["SerieDocumento"];
      $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($value);
    }

    $this->sCorrelativoResumenDiario->IncrementarCorrelativoResumenDiario($data);
    return $resultado;
  }

  function ActualizarResumenDiario($data) {
    //COMPROBANDO DETALLES
    foreach ($data["DetallesResumenDiario"] as $key => $value) {
      $data["DetallesResumenDiario"][$key]["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
    }

    $resultado= $this->mResumenDiario->ActualizarResumenDiario($data);
    $detalle=$this->sDetalleResumenDiario->ActualizarDetallesResumenDiario($data["DetallesResumenDiario"],$resultado);
    // print_r($detalle);exit;
    foreach ($detalle as $key => $value) {
      $value["IdComprobanteVenta"] = $detalle[$key]["IdComprobanteVenta"];
      $value["IndicadorEstadoResumenDiario"] = $detalle[$key]["IndicadorEstadoResumenDiario"];
      $value["IndicadorEstado"] = $detalle[$key]["IndicadorEstado"];
      $value["IndicadorEstadoComunicacionBaja"] = $detalle[$key]["IndicadorEstadoComunicacionBaja"];
      $value["IndicadorEstadoCPE"] = $detalle[$key]["IndicadorEstadoCPE"];
      $value["CodigoEstado"] = $detalle[$key]["CodigoEstado"];
      $value["SerieDocumento"] = $detalle[$key]["SerieDocumento"];
      $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($value);
    }

    return $resultado;
  }

  function BorrarResumenDiario($data) {
    $this->mResumenDiario->BorrarResumenDiario($data);
    return "";
  }

  function ConfirmarAceptacionResumenDiario($data) {
      $data["IndicadorEstadoResumenDiario"]=ESTADO_CPE_ACEPTADO;
      $this->mResumenDiario->ActualizarResumenDiario($data);
      return "";
  }

  function ConfirmarEnProcesoResumenDiario($data) {
      $value["IndicadorEstadoResumenDiario"]=ESTADO_CPE_EN_PROCESO;
      $this->mResumenDiario->ActualizarResumenDiario($data);
      return "";
  }


  function ConfirmarRechazoResumenDiario($data) {
      $data["IndicadorEstadoResumenDiario"]=ESTADO_CPE_RECHAZADO;
      $this->mResumenDiario->ActualizarResumenDiario($data);
      return "";
  }

  function ObtenerEstadoResumenDiarioSUNAT($ticket)
  {
    try
    {
      $resultado = $this->soapclientsunat->getStatus($ticket);
      if(is_array($resultado))
      {
        if(array_key_exists("Error", $resultado))
        {
          $response["FaultCode"] = ($this->SunatOSE == 1) ? $resultado["faultstring"] : $resultado["faultcode"];//$resultado["faultcode"].":".$resultado["faultstring"] : $resultado["faultcode"];
          $response["FaultString"] = ($this->SunatOSE == 1) ? $resultado["detail"] : $resultado["faultstring"];
          $response["Error"] = $response["FaultString"];
          return $response;
        }
      }
      else {
        $response["FechaRespuestaResumen"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
        $response["Data"] = $resultado;
        return $response;
      }
    }
    catch (SoapFault $fault)
    {
      $response["Error"] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
      return $response;
    }

  }

  function EnviarResumenDiarioSUNAT($data, $datos = array())
  {
    try
    {
      $data_documento["IdEmpresa"] = ID_EMPRESA;
      $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
      $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

      $RUC= $DatosEmpresa["CodigoEmpresa"];
      $USUARIO_SOL= $DatosEmpresa["UsuarioSOL"];
      $CONTRASEÑA_SOL= $DatosEmpresa["ClaveSOL"];

      $nombreArchivoZip = $data["nombrearchivo"].".zip";
      $rutaZip = APP_PATH.$DatosCarpeta["RUTA_CARPETA_XML"].$nombreArchivoZip;
      $archivoZip = file_get_contents($rutaZip);

      $nombreArchivoRpta ="R-".$nombreArchivoZip;
      $rutaArchivoRpta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombreArchivoRpta;

      $this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL,$CONTRASEÑA_SOL);
      $ticket=$this->soapclientsunat->sendSummary($nombreArchivoZip,$archivoZip);

      if($ticket == false)
      {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["Error"] = "Error con la coneccion SUNAT.";
        return $response;
      }
      else {
        if(is_array($ticket))
        {
          if(array_key_exists("Error", $ticket))
          {
            $response["FaultCode"] = ($this->SunatOSE == 1) ? $ticket["faultstring"] : $ticket["faultcode"];//$ticket["faultcode"].":".$ticket["faultstring"] : $ticket["faultcode"];
            $response["FaultString"] = ($this->SunatOSE == 1) ? $ticket["detail"] : $ticket["faultstring"];
            $response["Error"] = $response["FaultString"];
            return $response;
          }
        }
        else {
          $datos["NumeroTicket"] = $ticket;
          $resumen = $this->InsertarResumenDiario($datos);
          if(is_array($resumen))
          {
            $response["IdResumenDiario"] = $resumen["IdResumenDiario"];
          }
          $resultado = $this->ObtenerEstadoResumenDiarioSUNAT($ticket);
          if(array_key_exists("Error", $resultado))
          {
            $response["FechaEnvioResumen"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
            $response["FechaRespuestaResumen"] = "";
            $response["CodigoEstadoRespuestaSunat"] = CODIGO_SUNAT_ESTADO_PENDIENTE;
            $response["NumeroTicket"] = $ticket;
            //POR ERRORES
            $response["CodigoError"] = $resultado["FaultCode"];
            $response["DescripcionError"] = $resultado["FaultString"];
            return $response;
          }
          else {
            $response["FechaEnvioResumen"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
            $response["FechaRespuestaResumen"] = $resultado["FechaRespuestaResumen"];
            $response["CodigoEstadoRespuestaSunat"] = $resultado["Data"]->statusCode;
            $response["NumeroTicket"] = $ticket;
            if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_ACEPTADO || $response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO) //ACEPTADO
            {
              file_put_contents($rutaArchivoRpta, $resultado["Data"]->content);
            }

            return $response;
          }
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

  function ConsultarEstadoResumenDiarioSUNAT($data)
  {
    $ticket = $data["NumeroTicket"];
    $nombre = $data["NombreResumenDiario"];

    $data_documento["IdEmpresa"] = ID_EMPRESA;
    $DatosCarpeta = $this->ObtenerCarpetasSUNAT();
    $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];

    $RUC= $DatosEmpresa["CodigoEmpresa"];
    $USUARIO_SOL= $DatosEmpresa["UsuarioSOL"];
    $CONTRASEÑA_SOL= $DatosEmpresa["ClaveSOL"];

    $nombreArchivoZip = $nombre.".zip";
    $nombreArchivoRpta ="R-".$nombreArchivoZip;
    $rutaArchivoRpta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombreArchivoRpta;

    $this->soapclientsunat->__setUsernameToken($RUC.$USUARIO_SOL,$CONTRASEÑA_SOL);

    $resultado = $this->ObtenerEstadoResumenDiarioSUNAT($ticket);
    // print_r($resultado);
    // exit;
    if(array_key_exists("Error", $resultado))
    {
      $response["FechaEnvioBaja"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
      $response["FaultCode"] = $resultado["FaultCode"];
      $response["FaultString"] = $resultado["FaultString"];
      $response["Error"] = $response["FaultString"];
      return $response;
    }
    else {
      $response["FechaRespuestaResumen"] = $resultado["FechaRespuestaResumen"];
      $response["CodigoEstadoRespuestaSunat"] = $resultado["Data"]->statusCode;
      $response["NumeroTicket"] = $ticket;
      
      if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_ACEPTADO || $response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO) //ACEPTADO
      {
        file_put_contents($rutaArchivoRpta, $resultado["Data"]->content);
      }
      
      $nombre_archivo = "R-".$nombre;
      $data_zip["Destino"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_ERROR"]; //URL DE DESTINO DE ARCHIVO
      $data_zip["UbicacionZIP"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_CDR"].$nombre_archivo.'.zip';
      $data_zip["File"] = $nombre_archivo.".xml";  //NOMBRE DEL ARCHIVO XML

      if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_ACEPTADO) //ACEPTADO
      {
        $respuesta =  $this->zipper->LeerZIPXML($data_zip);
        $data["CodigoError"] = $respuesta["CodigoRespuesta"];
        $data["DescripcionError"] = $respuesta["MensajeRespuesta"];
        $data["IndicadorEstadoResumenDiario"] = ESTADO_CPE_ACEPTADO;
      }
      else if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_PENDIENTE) //PENDIENTE
      {
        $data["IndicadorEstadoResumenDiario"] = ESTADO_CPE_EN_PROCESO;
      }
      else if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO)//CON ERROR
      {
        $respuesta =  $this->zipper->LeerZIPXML($data_zip);
        $data["CodigoError"] = $respuesta["CodigoRespuesta"];
        $data["DescripcionError"] = $respuesta["MensajeRespuesta"];
        $data["IndicadorEstadoResumenDiario"] = ESTADO_CPE_RECHAZADO;
      }
      else
      {
        $data["CodigoError"] = $response["CodigoEstadoRespuestaSunat"];
        $data["DescripcionError"] = $resultado["Data"]->content;
      }

      $guardado = $this->mResumenDiario->ActualizarResumenDiario($data);

      $comprobantes = $this->mResumenDiario->ConsultarComprobantesVentaPorResumen($data);
      foreach ($comprobantes as $key => $value) {
        $value["IndicadorEstadoResumenDiario"] = $data["IndicadorEstadoResumenDiario"];
        $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($value);
      }

      return $guardado;
    }
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
}
