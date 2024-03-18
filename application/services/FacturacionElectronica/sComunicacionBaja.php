<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComunicacionBaja extends MY_Service {

  private $SunatOSE = "";

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->load->library('soapclientsunat');
    $this->load->library('zipper');
    $this->load->model('FacturacionElectronica/mComunicacionBaja');
    $this->load->service('Configuracion/General/sEmpresa');
    $this->load->service('FacturacionElectronica/sCorrelativoComunicacionBaja');
    $this->load->service('FacturacionElectronica/sDetalleComunicacionBaja');
    $this->load->service('Venta/sComprobanteVenta');
    $this->load->service('Configuracion/General/sConstanteSistema');

    $this->ComunicacionBaja = $this->mComunicacionBaja->ComunicacionBaja;
    $this->SunatOSE = $this->sConstanteSistema->ObtenerParametroEnvioSunatOSE();
  }

  function ValidarComunicacionBaja($data) {
    $resultado ="";
      foreach ($data as $key => $value) {
        if($value["MotivoBaja"] == "")
        {
          $resultado = "El comprobante de venta ".$value["Numero"]." no tiene motivo de baja!";
          return $resultado;
        }
        else if (strlen($value["MotivoBaja"]) <= 3)
        {
          $resultado = "El motivo de baja tiene que ser de mas de 3 caracteres!";
          return $resultado;
        }
      }

    return $resultado;
  }

  function ConsultarFacturasElectronicasConComunicacionBaja($data)
  {
    $resultado = $this->mComunicacionBaja->ConsultarFacturasElectronicasConComunicacionBaja($data);
    return $resultado;
  }

  function ConsultarComunicacionesBaja($data)
  {
    $resultado = $this->mComunicacionBaja->ConsultarComunicacionesBaja($data);

    foreach ($resultado as $key => $value) {
      $items= $this->mDetalleComunicacionBaja->ConsultarDetallesComunicacionBaja($value);
      $resultado[$key]["DetallesComunicacionBaja"]=$items;
    }

    return $resultado;
  }

  function PrepararComunicacionBaja($data) {
    $resultado = $this->ValidarComunicacionBaja($data);

    if($resultado =="")
    {
      $fecha =$this->Base->ObtenerFechaServidor("Y-m-d");
      $fechacomunicacionbaja=$this->Base->ObtenerFechaServidor("Ymd");
      $input["IdEmpresa"]=$this->sEmpresa->ObtenerIdEmpresa();
      $input["FechaComunicacionBaja"] =$fecha;
      $dataEmpresa = $this->sEmpresa->ListarEmpresas($input);
      $numeroenvio = $this->sCorrelativoComunicacionBaja->ObtenerNuevoCorrelativoComunicacionBaja($input);
      $input["NumeroEnvio"] = $this->shared->rellenar_ceros($numeroenvio,5);
      $input["NombreComunicacionBaja"]=$dataEmpresa[0]["CodigoEmpresa"].'-'.CODIGO_TIPO_DOCUMENTO_COMUNICACION_BAJA.'-'.$fechacomunicacionbaja.'-'.$input["NumeroEnvio"];
      $input["CodigoComunicacion"]=CODIGO_TIPO_DOCUMENTO_COMUNICACION_BAJA.'-'.$fechacomunicacionbaja.'-'.$input["NumeroEnvio"];
      $input["FechaEmisionDocumento"] =$data[0]["FechaEmision"];
      $input["FechaGeneracionBaja"]=$this->Base->ObtenerFechaServidor();
      $input["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      $input["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
      $input["IndicadorEstado"]=ESTADO_DOCUMENTO_ACTIVO;
      $input["IndicadorEstadoComunicacionBaja"]=ESTADO_CPE_GENERADO;

      $resultado = $input;

      foreach ($data as $key => $value) {
        $value["IndicadorEstadoComunicacionBaja"]=ESTADO_CPE_GENERADO;
        $value["NumeroItem"]=$key+1;
        $resultado["DetallesComunicacionBaja"][]=$value;
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

  function InsertarComunicacionBaja($data) {
    //COMPROBANDO DETALLES
    $data["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_EN_PROCESO;
    foreach ($data["DetallesComunicacionBaja"] as $key => $value) {
      $data["DetallesComunicacionBaja"][$key]["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
    }
    
    $resultado= $this->mComunicacionBaja->InsertarComunicacionBaja($data);
    $detalle=$this->sDetalleComunicacionBaja->InsertarDetallesComunicacionBaja($data["DetallesComunicacionBaja"],$resultado);

    foreach ($detalle as $key => $value) {
      $ComprobanteVenta["IdComprobanteVenta"] = $detalle[$key]["IdComprobanteVenta"];
      $ComprobanteVenta["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
      $ComprobanteVenta["IndicadorEstado"] = $detalle[$key]["IndicadorEstado"];
      $ComprobanteVenta["IndicadorEstadoResumenDiario"] = $detalle[$key]["IndicadorEstadoResumenDiario"];
      $ComprobanteVenta["IndicadorEstadoCPE"] = $detalle[$key]["IndicadorEstadoCPE"];
      $ComprobanteVenta["SerieDocumento"] = $detalle[$key]["SerieDocumento"];
      $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($ComprobanteVenta);
    }

    $this->sCorrelativoComunicacionBaja->IncrementarCorrelativoComunicacionBaja($data);
    return $resultado;
  }

  function ActualizarComunicacionBaja($data) {
    //COMPROBANDO DETALLES
    foreach ($data["DetallesComunicacionBaja"] as $key => $value) {
      $data["DetallesComunicacionBaja"][$key]["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
    }
    
    $resultado= $this->mComunicacionBaja->ActualizarComunicacionBaja($data);
    $detalle=$this->sDetalleComunicacionBaja->ActualizarDetallesComunicacionBaja($data["DetallesComunicacionBaja"],$resultado);

    foreach ($detalle as $key => $value) {
      $ComprobanteVenta["IdComprobanteVenta"] = $detalle[$key]["IdComprobanteVenta"];
      $ComprobanteVenta["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
      $ComprobanteVenta["IndicadorEstado"] = $detalle[$key]["IndicadorEstado"];
      $ComprobanteVenta["IndicadorEstadoResumenDiario"] = $detalle[$key]["IndicadorEstadoResumenDiario"];
      $ComprobanteVenta["IndicadorEstadoCPE"] = $detalle[$key]["IndicadorEstadoCPE"];
      $ComprobanteVenta["SerieDocumento"] = $detalle[$key]["SerieDocumento"];
      $this->sComprobanteVenta->ActualizarEstadoComprobanteVenta($ComprobanteVenta);
    }

    return $resultado;
  }

  function BorrarComunicacionBaja($data) {
    $this->mComunicacionBaja->BorrarComunicacionBaja($data);
    return "";
  }

  function ConfirmarAceptacionComunicacionBaja($data) {
      $data["IndicadorEstadoComunicacionBaja"]=ESTADO_CPE_ACEPTADO;
      $this->mComunicacionBaja->ActualizarComunicacionBaja($data);
      return "";
  }

  function ConfirmarEnProcesoComunicacionBaja($data) {
      $value["IndicadorEstadoComunicacionBaja"]=ESTADO_CPE_EN_PROCESO;
      $this->mComunicacionBaja->ActualizarComunicacionBaja($data);
      return "";
  }

  function ConfirmarRechazoComunicacionBaja($data) {
      $data["IndicadorEstadoComunicacionBaja"]=ESTADO_CPE_RECHAZADO;
      $this->mComunicacionBaja->ActualizarComunicacionBaja($data);
      return "";
  }

  function ObtenerEstadoComunicacionBajaSUNAT($ticket)
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
        $response["FechaRespuestaBaja"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
        $response["Data"] = $resultado;

        return $response;
      }

    }
    catch (SoapFault $fault)
    {
      //print_r($fault);
      $response["Error"] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
      return $response;
    }

  }

  function EnviarComunicacionBajaSUNAT($data, $datos = array()){
    try {
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

      if($ticket == false) {
        $response["FaultString"] = "El servidor de SUNAT no se encuentra disponible en este momento. Podria comunicarse al teléfono de SUNAT: ".TELEFONO_SUNAT;
        $response["Error"] = "Error con la coneccion SUNAT.";
        return $response;
      }
      else {
        if(is_array($ticket))
        {
          if(array_key_exists("Error", $ticket)){
            $response["FaultCode"] = ($this->SunatOSE == 1) ? $ticket["faultstring"] : $ticket["faultcode"];//$ticket["faultcode"].":".$ticket["faultstring"] : $ticket["faultcode"];
            $response["FaultString"] = ($this->SunatOSE == 1) ? $ticket["detail"] : $ticket["faultstring"];
            $response["Error"] = $response["FaultString"];
            return $response;
          }
        }
        else {
          $datos["NumeroTicket"] = $ticket;
          $baja = $this->InsertarComunicacionBaja($datos);
          if(is_array($baja))
          {
            $response["IdComunicacionBaja"] = $baja["IdComunicacionBaja"];
          }
          
          $resultado = $this->ObtenerEstadoComunicacionBajaSUNAT($ticket);
          if(array_key_exists("Error", $resultado))
          {
            $response["FechaEnvioBaja"] = $this->Base->ObtenerFechaServidor("Y-m-d H:i:s");
            $response["FechaRespuestaBaja"] = "";
            $response["CodigoEstadoRespuestaSunat"] = CODIGO_SUNAT_ESTADO_PENDIENTE;
            $response["NumeroTicket"] = $ticket;
            //POR ERRORES
            $response["CodigoError"] = $resultado["FaultCode"];
            $response["DescripcionError"] = $resultado["FaultString"];
            return $response;
          }
          else {
            $response["FechaRespuestaBaja"] = $resultado["FechaRespuestaBaja"];
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

  function ConsultarEstadoComunicacionBajaSUNAT($data)
  {
    $ticket = $data["NumeroTicket"];
    $nombre = $data["NombreComunicacionBaja"];

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

    $resultado = $this->ObtenerEstadoComunicacionBajaSUNAT($ticket);
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
      $response["FechaRespuestaBaja"] = $resultado["FechaRespuestaBaja"];
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
        $data["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_ACEPTADO;
      }
      else if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_PENDIENTE) //PENDIENTE
      {
        $data["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_EN_PROCESO;
      }
      else if($response["CodigoEstadoRespuestaSunat"] == CODIGO_SUNAT_ESTADO_RECHAZADO)//CON ERROR
      {
        $respuesta =  $this->zipper->LeerZIPXML($data_zip);
        $data["CodigoError"] = $respuesta["CodigoRespuesta"];
        $data["DescripcionError"] = $respuesta["MensajeRespuesta"];
        $data["IndicadorEstadoComunicacionBaja"] = ESTADO_CPE_RECHAZADO;
      }
      else
      {
        $data["CodigoError"] = $response["CodigoEstadoRespuestaSunat"];
        $data["DescripcionError"] = $resultado["Data"]->content;
      }

      $guardado = $this->mComunicacionBaja->ActualizarComunicacionBaja($data);
      
      $comprobantes = $this->mComunicacionBaja->ConsultarComprobantesVentaPorBaja($data);
      foreach ($comprobantes as $key => $value) {
        $value["IndicadorEstadoComunicacionBaja"] = $data["IndicadorEstadoComunicacionBaja"];
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
