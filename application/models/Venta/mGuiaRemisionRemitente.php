<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mGuiaRemisionRemitente extends CI_Model {

  public $GuiaRemisionRemitente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->load->model("Base");
    $this->load->model("Configuracion/General/mSituacionComprobanteElectronico");
    $this->GuiaRemisionRemitente = $this->Base->Construir("GuiaRemisionRemitente");
  }

  function ObtenerGuiaRemisionRemitentePorSerieDocumento($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("SELECT GRR.*
        FROM GuiaRemisionRemitente AS GRR
        WHERE GRR.IndicadorEstado = 'A' AND GRR.SerieDocumento = '$serie' AND GRR.NumeroDocumento='$numero' AND GRR.IdTipoDocumento = '$tipo'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerDuplicadoDeGuiaRemisionRemitente($data)
  {
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("SELECT *
                                FROM GuiaRemisionRemitente
                                WHERE IndicadorEstado = 'A' AND NumeroDocumento = '$numero'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarGuiaRemisionRemitente($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IndicadorEstadoCPE"]=ESTADO_CPE_GENERADO;

    $data["SituacionCPE"] = $this->ObtenerSituacionCPE($data);

    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $ignore=array("DenominacionEnvioTransbordo"=>"DenominacionEnvioTransbordo");
    $resultado = $this->mapper->map($data,$this->GuiaRemisionRemitente,$ignore);
    $this->db->insert('GuiaRemisionRemitente', $resultado);
    $resultado["IdGuiaRemisionRemitente"] = $this->db->insert_id();
    $resultado["AbreviaturaSituacionCPE"] = $this->mSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($data["SituacionCPE"])->AbreviaturaSituacionComprobanteElectronicoVentas;
    return $resultado;
  }


  function ActualizarGuiaRemisionRemitente($data)
  {
    $id=$data["IdGuiaRemisionRemitente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

    if(array_key_exists("IndicadorEstado", $data) && array_key_exists("IndicadorEstadoCPE", $data)) {
      $data["SituacionCPE"] = $this->ObtenerSituacionCPE($data);
    }
    if(array_key_exists("DenominacionEnvioTransbordo",$data)) {
      $ignore=array("DenominacionEnvioTransbordo"=>"DenominacionEnvioTransbordo");      
    }
    else {
      $ignore = null;
    }

    $resultado = $this->mapper->map($data,$this->GuiaRemisionRemitente,$ignore);

    $this->db->WHERE('IdGuiaRemisionRemitente', $id);
    $this->db->update('GuiaRemisionRemitente', $resultado);

    if(array_key_exists("SituacionCPE", $data)) {      
      $resultadoSituacionComprobanteElectronico = $this->mSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($data["SituacionCPE"]);
      
      if (is_object($resultadoSituacionComprobanteElectronico)) {
        $resultado["AbreviaturaSituacionCPE"] = $resultadoSituacionComprobanteElectronico->AbreviaturaSituacionComprobanteElectronicoVentas;
      }
      else{
        $resultado["AbreviaturaSituacionCPE"]="";
      }
      
    }

    return $resultado;
  }

  function BorrarGuiaRemisionRemitente($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarGuiaRemisionRemitente($data);
    return $resultado;
  }

  //
  function ObtenerSituacionCPE($data) {

    $IndicadorEstado = $data["IndicadorEstado"];
    $IndicadorEstadoCPE = $data["IndicadorEstadoCPE"];
    // $IndicadorEstadoComunicacionBaja = $data["IndicadorEstadoComunicacionBaja"];

    if($IndicadorEstado  == ESTADO_DOCUMENTO_ELIMINADO) {
        return ESTADO_CPE_NINGUNO;
    }
    
    if($IndicadorEstado == ESTADO_DOCUMENTO_ACTIVO) {
      if ($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_NINGUNO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_GENERADO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_EN_PROCESO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_EN_PROCESO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_ACEPTADO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_RECHAZADO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_RECHAZADO;
        // }
      }
    }
    else if($IndicadorEstado == ESTADO_DOCUMENTO_ANULADO) {
      if ($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_NINGUNO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_ANULADO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_EN_PROCESO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_ANULADO;
        // }
      }
      else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
        // if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            return ESTADO_CPE_PENDIENTE_BAJA;
        // }
        // else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_GENERADO)   {
        //   return ESTADO_CPE_BAJA_GENERADA;
        // }
        // else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_EN_PROCESO)    {
        //   return ESTADO_CPE_BAJA_EN_PROCESO;
        // }
        // else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_ACEPTADO)    {
        //   return ESTADO_CPE_BAJA_ACEPTADA;
        // }
        // else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_RECHAZADO)    {
        //   return ESTADO_CPE_BAJA_RECHAZADA;
        // }
      }
    }

  }

  function ConsultarGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data)
  {
    $id = $data["IdGuiaRemisionRemitente"];

    $query = $this->db->query("SELECT GRR.*,CONCAT(GRR.SerieDocumento , '-' , GRR.NumeroDocumento) as Documento,
              TD.NombreAbreviado, TD.CodigoTipoDocumento, MT.CodigoMotivoTraslado, MT.NombreMotivoTraslado,
              MDT.CodigoModalidadTraslado, MDT.NombreModalidadTraslado,
              PD.RazonSocial 'RazonSocialDestinatario', PD.NumeroDocumentoIdentidad 'NumeroDocumentoDestinatario',
              TDID.CodigoDocumentoIdentidad 'CodigoTipoDocumentoDestinatario',
              
              PT.RazonSocial 'RazonSocialTransportista', PT.NumeroDocumentoIdentidad 'NumeroDocumentoTransportista',
              TDIT.CodigoDocumentoIdentidad 'CodigoTipoDocumentoTransportista',
              
              CONCAT(DPP.CodigoUbigeoDepartamento , PRP.CodigoUbigeoProvincia , DTP.CodigoUbigeoDistrito) as CodigoUbigeoPuntoPartida,
              CONCAT(DPL.CodigoUbigeoDepartamento , PRL.CodigoUbigeoProvincia , DTL.CodigoUbigeoDistrito) as CodigoUbigeoPuntoLlegada,              
              TIME_FORMAT(GRR.FechaRegistro, '%H:%i:%s') AS HoraEmision,
              CONCAT(CV.SerieDocumento , '-' , CV.NumeroDocumento) AS DocumentoReferencia,
              TD2.CodigoTipoDocumento AS CodigoTipoDocumentoReferencia,
              TD2.NombreTipoDocumento AS NombreTipoDocumentoReferencia,
              TDI2.CodigoDocumentoIdentidad,
              P2.NumeroDocumentoIdentidad,
              emp.CodigoEmpresa,
              FORMAT(GRR.PesoBrutoTotal, 3) AS PesoBrutoTotal
              FROM GuiaRemisionRemitente AS GRR
              LEFT JOIN TipoDocumento AS TD ON TD.IdTipoDocumento = GRR.IdTipoDocumento
              
              LEFT JOIN Persona AS PD ON PD.IdPersona = GRR.IdDestinatario
              LEFT JOIN TipoDocumentoIdentidad AS TDID ON TDID.IdTipoDocumentoIdentidad = PD.IdTipoDocumentoIdentidad
              
              LEFT JOIN MotivoTraslado AS MT ON MT.IdMotivoTraslado = GRR.IdMotivoTraslado
              LEFT JOIN ModalidadTraslado AS MDT ON MDT.IdModalidadTraslado = GRR.IdModalidadTraslado
              
              LEFT JOIN Persona AS PT ON PT.IdPersona = GRR.IdTransportista
              LEFT JOIN TipoDocumentoIdentidad AS TDIT ON TDIT.IdTipoDocumentoIdentidad = PT.IdTipoDocumentoIdentidad
              
              LEFT JOIN Departamento AS DPP ON DPP.IdDepartamento = GRR.IdDepartamentoPuntoPartida
              LEFT JOIN Provincia AS PRP ON PRP.IdProvincia = GRR.IdProvinciaPuntoPartida
              LEFT JOIN Distrito AS DTP ON DTP.IdDistrito = GRR.IdDistritoPuntoPartida
              
              LEFT JOIN Departamento AS DPL ON DPL.IdDepartamento = GRR.IdDepartamentoPuntoLlegada
              LEFT JOIN Provincia AS PRL ON PRL.IdProvincia = GRR.IdProvinciaPuntoLlegada
              LEFT JOIN Distrito AS DTL ON DTL.IdDistrito = GRR.IdDistritoPuntoLlegada

              LEFT JOIN comprobanteventa AS CV
              ON CV.IdComprobanteVenta = GRR.IdComprobanteVenta AND CV.IndicadorEstado ='A'
              LEFT JOIN tipodocumento AS TD2
              ON TD2.IdTipoDocumento = CV.IdTipoDocumento
              LEFT JOIN Persona AS P2
              ON P2.IdPersona = CV.idCliente
              LEFT JOIN TipoDocumentoIdentidad TDI2
              ON TDI2.IdTipoDocumentoIdentidad = P2.IdTipoDocumentoIdentidad
              cross join empresa emp
              WHERE GRR.IndicadorEstado = 'A' 
              AND GRR.IdGuiaRemisionRemitente = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitente($data,$numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["TextoFiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];

    // $destinatario = $data["Destinatario"];
    // $transportista = $data["Transportista"];

    // $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    // $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    // if($vistaventa == 0)
    // {
    //   $extensionConsulta = " AND U.IdUsuario = '$idusuario' ";
    // }
      //if((SELECT count(dref.SerieDocumentoReferencia) FROM documentoreferencia AS dref WHERE dref.IdGuiaRemisionRemitente = GRR.IdGuiaRemisionRemitente) > 0, 1, 0) AS IndicadorDocumentoReferencia,
    $consulta = "SELECT GRR.*,
                  CONCAT(TD.NombreAbreviado,'-',GRR.SerieDocumento,'-',GRR.NumeroDocumento) as Numero,
                  (if (GRR.IndicadorEstado = 'N','ANULADO','ACTIVO')) AS EstadoGuiaRemisionRemitente,
                  TD.NombreAbreviado, PD.IdPersona, 
                  PD.RazonSocial as RazonSocialDestinatario, PD.NumeroDocumentoIdentidad as NumeroDocumentoIdentidadDestinatario, PD.Direccion,
                  PT.RazonSocial as RazonSocialTransportista, PT.NumeroDocumentoIdentidad as NumeroDocumentoIdentidadTransportista
                  FROM GuiaRemisionRemitente AS GRR
                  LEFT JOIN TipoDocumento AS TD ON TD.IdTipoDocumento = GRR.IdTipoDocumento
                  
                  LEFT JOIN Persona AS PD ON PD.IdPersona = GRR.IdDestinatario
                  LEFT JOIN TipoDocumentoIdentidad AS TDID ON TDID.IdTipoDocumentoIdentidad = PD.IdTipoDocumentoIdentidad
                  
                  LEFT JOIN MotivoTraslado AS MT ON MT.IdMotivoTraslado = GRR.IdMotivoTraslado
                  LEFT JOIN ModalidadTraslado AS MDT ON MDT.IdModalidadTraslado = GRR.IdModalidadTraslado
                  
                  LEFT JOIN Persona AS PT ON PT.IdPersona = GRR.IdTransportista
                  LEFT JOIN TipoDocumentoIdentidad AS TDIT ON TDIT.IdTipoDocumentoIdentidad = PT.IdTipoDocumentoIdentidad
                  
                  LEFT JOIN Departamento AS DPP ON DPP.IdDepartamento = GRR.IdDepartamentoPuntoPartida
                  LEFT JOIN Provincia AS PRP ON PRP.IdProvincia = GRR.IdProvinciaPuntoPartida
                  LEFT JOIN Distrito AS DTP ON DTP.IdDistrito = GRR.IdDistritoPuntoPartida
                  
                  LEFT JOIN Departamento AS DPL ON DPL.IdDepartamento = GRR.IdDepartamentoPuntoLlegada
                  LEFT JOIN Provincia AS PRL ON PRL.IdProvincia = GRR.IdProvinciaPuntoLlegada
                  LEFT JOIN Distrito AS DTL ON DTL.IdDistrito = GRR.IdDistritoPuntoLlegada
                  WHERE (GRR.IndicadorEstado = 'A' OR GRR.IndicadorEstado = 'N' ) AND
                  (GRR.SerieDocumento LIKE '%$criterio%' OR
                  GRR.NumeroDocumento LIKE '%$criterio%' OR
                  PD.RazonSocial LIKE '%$criterio%' OR
                  PD.NumeroDocumentoIdentidad LIKE '%$criterio%'OR
                  PT.RazonSocial LIKE '%$criterio%' OR
                  PT.NumeroDocumentoIdentidad LIKE '%$criterio%') AND
                  GRR.FechaEmision BETWEEN '$fechainicio' AND '$fechafin'
                  ".$extensionConsulta."
                  ORDER BY TD.NombreTipoDocumento, GRR.FechaEmision DESC, TD.NombreAbreviado ASC, GRR.SerieDocumento DESC,GRR.NumeroDocumento DESC
                  LIMIT $numerofilainicio,$numerorfilasporpagina";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalGuiasRemisionRemitente($data)
  {
    $criterio=$data["TextoFiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    // if($vistaventa == 0)
    // {
    //   $extensionConsulta = " AND U.IdUsuario = '$idusuario' ";
    // }
    $consulta = "SELECT GRR.*,
                  CONCAT(TD.NombreAbreviado,'-',GRR.SerieDocumento,'-',GRR.NumeroDocumento) as Numero,
                  (if (GRR.IndicadorEstado = 'N','ANULADO','ACTIVO')) AS EstadoGuiaRemisionRemitente,
                  TD.NombreAbreviado, PD.IdPersona, 
                  PD.RazonSocial as RazonSocialDestinatario, PD.NumeroDocumentoIdentidad as NumeroDocumentoIdentidadDestinatario, PD.Direccion,
                  PT.RazonSocial as RazonSocialTransportista, PT.NumeroDocumentoIdentidad as NumeroDocumentoIdentidadTransportista
                  FROM GuiaRemisionRemitente AS GRR
                  LEFT JOIN TipoDocumento AS TD ON TD.IdTipoDocumento = GRR.IdTipoDocumento
                  
                  LEFT JOIN Persona AS PD ON PD.IdPersona = GRR.IdDestinatario
                  LEFT JOIN TipoDocumentoIdentidad AS TDID ON TDID.IdTipoDocumentoIdentidad = PD.IdTipoDocumentoIdentidad
                  
                  LEFT JOIN MotivoTraslado AS MT ON MT.IdMotivoTraslado = GRR.IdMotivoTraslado
                  LEFT JOIN ModalidadTraslado AS MDT ON MDT.IdModalidadTraslado = GRR.IdModalidadTraslado
                  
                  LEFT JOIN Persona AS PT ON PT.IdPersona = GRR.IdTransportista
                  LEFT JOIN TipoDocumentoIdentidad AS TDIT ON TDIT.IdTipoDocumentoIdentidad = PT.IdTipoDocumentoIdentidad
                  
                  LEFT JOIN Departamento AS DPP ON DPP.IdDepartamento = GRR.IdDepartamentoPuntoPartida
                  LEFT JOIN Provincia AS PRP ON PRP.IdProvincia = GRR.IdProvinciaPuntoPartida
                  LEFT JOIN Distrito AS DTP ON DTP.IdDistrito = GRR.IdDistritoPuntoPartida
                  
                  LEFT JOIN Departamento AS DPL ON DPL.IdDepartamento = GRR.IdDepartamentoPuntoLlegada
                  LEFT JOIN Provincia AS PRL ON PRL.IdProvincia = GRR.IdProvinciaPuntoLlegada
                  LEFT JOIN Distrito AS DTL ON DTL.IdDistrito = GRR.IdDistritoPuntoLlegada
                  WHERE (GRR.IndicadorEstado = 'A' OR GRR.IndicadorEstado = 'N' ) AND
                  (GRR.SerieDocumento LIKE '%$criterio%' OR
                  GRR.NumeroDocumento LIKE '%$criterio%' OR
                  PD.RazonSocial LIKE '%$criterio%' OR
                  PD.NumeroDocumentoIdentidad LIKE '%$criterio%'OR
                  PT.RazonSocial LIKE '%$criterio%' OR
                  PT.NumeroDocumentoIdentidad LIKE '%$criterio%') AND
                  GRR.FechaEmision BETWEEN '$fechainicio' AND '$fechafin'
                  ".$extensionConsulta."
                  ORDER BY TD.NombreTipoDocumento, GRR.FechaEmision DESC, TD.NombreAbreviado ASC, GRR.SerieDocumento DESC,GRR.NumeroDocumento DESC";

    $query = $this->db->query($consulta);
    $resultado = $query->num_rows();
    return $resultado;
  }

  function ObtenerFechaMenor($data)
  {
    $tipodocumento=$data["IdTipoDocumento"];
    $numero=$data["NumeroDocumento"];
    $serie=$data["SerieDocumento"];
    $query = $this->db->query("SELECT max(FechaEmision) AS FechaEmisionMenor  FROM comprobanteventa
                    WHERE (IndicadorEstado='A' OR IndicadorEstado='N') AND NumeroDocumento<'$numero' AND
                    SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerFechaMayor($data)
  {
    $tipodocumento=$data["IdTipoDocumento"];
    $numero=$data["NumeroDocumento"];
    $serie=$data["SerieDocumento"];
    $query = $this->db->query("SELECT min(FechaEmision) AS FechaEmisionMayor FROM comprobanteventa
                    WHERE (IndicadorEstado='A' OR IndicadorEstado='N') AND NumeroDocumento>'$numero' AND
                    SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerMinimoMaximoFechaEmisionGuiaRemisionRemitente() {
    $query = $this->db->query("SELECT min(fechaemision) AS minimofechaemision,max(fechaemision) AS maximofechaemision FROM GuiaRemisionRemitente");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerGuiasRemisionRemitentePorIdComprobanteVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $query = $this->db->query("SELECT GRR.*, TD.NombreAbreviado, TD.CodigoTipoDocumento
                    FROM GuiaRemisionRemitente GRR
                    LEFT JOIN TipoDocumento AS TD ON TD.IdTipoDocumento = GRR.IdTipoDocumento
                    WHERE GRR.IndicadorEstado='A' AND IdComprobanteVenta='$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

}
