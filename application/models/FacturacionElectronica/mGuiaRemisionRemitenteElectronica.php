<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mGuiaRemisionRemitenteElectronica extends CI_Model
{

  public $GuiaRemisionRemitenteElectronica = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->GuiaRemisionRemitenteElectronica = $this->Base->Construir("GuiaRemisionRemitenteElectronica");
  }

  function InsertarGuiaRemisionRemitenteElectronica($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    if(array_key_exists("NumeroTicket",$data)) {
      $ignore = array("NumeroTicket"=>"NumeroTicket"); //array("CodigoHash"=>"CodigoHash");
    }
    else {
      $ignore=null;
    }
    $data["IndicadorEstadoPublicacionWeb"] = ESTADO_PW_PENDIENTE;
    $resultado = $this->mapper->map($data, $this->GuiaRemisionRemitenteElectronica, $ignore);
    if (array_key_exists("CodigoHash", $data)) {
      $resultado["CodigoHash"] = $data["CodigoHash"];
    }
    $this->db->insert('GuiaRemisionRemitenteElectronica', $resultado);
    $resultado["IdGuiaRemisionRemitenteElectronica"] = $this->db->insert_id();

    return ($resultado);
  }

  function ActualizarGuiaRemisionRemitenteElectronica($data)
  {
    $id = $data["IdGuiaRemisionRemitenteElectronica"];

    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    if(array_key_exists("NumeroTicket",$data)) {
      $ignore = array("NumeroTicket"=>"NumeroTicket"); //array("CodigoHash"=>"CodigoHash");
    }
    else {
      $ignore=null;
    }
    //$ignore = null;
    // if(array_key_exists("CodigoHash", $data))
    // {
    //   $ignore = array("CodigoHash"=>"CodigoHash");
    // }
    $resultado = $this->mapper->map($data, $this->GuiaRemisionRemitenteElectronica, $ignore);
    if (array_key_exists("CodigoHash", $data)) {
      $resultado["CodigoHash"] = $data["CodigoHash"];
    }
    $this->db->where('IdGuiaRemisionRemitenteElectronica', $id);
    $this->db->update('GuiaRemisionRemitenteElectronica', $resultado);
    return $resultado;
  }

  function ObtenerGuiaRemisionRemitenteElectronica($data)
  {
    $id = $data["IdGuiaRemisionRemitente"];
    $query = $this->db->query("Select *
                                From GuiaRemisionRemitenteElectronica
                                Where IdGuiaRemisionRemitente = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerGuiaRemisionRemitenteElectronicaVigentePorIdGuiaRemisionRemitente($data)
  {
    $id = $data["IdGuiaRemisionRemitente"];
    $query = $this->db->query("Select *
                                From GuiaRemisionRemitenteElectronica
                                Where IdGuiaRemisionRemitente = '$id' And IndicadorEstado='A'");
    $resultado = $query->row();
    return $resultado;
  }

  function BorrarGuiaRemisionRemitenteElectronica($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarGuiaRemisionRemitenteElectronica($data);
    return $data;
  }

  function ActivarGuiaRemisionRemitenteElectronica($data)
  {
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $this->ActualizarGuiaRemisionRemitenteElectronica($data);
  }

  // function ConsultarGuiasRemisionRemitenteElectronico($data)
  // {
  //   $codigo_serie = !array_key_exists("CodigoSerie", $data) ? "" : $data["CodigoSerie"];
  //   $estado = ESTADO_ACTIVO;
  //   $NumeroDocumento = $data["NumeroDocumento"];
  //   $RazonSocial = !array_key_exists("RazonSocial", $data) ? "%" : $data["RazonSocial"];
  //   $FechaInicio = $data["FechaInicio"];
  //   $FechaFin = $data["FechaFin"];
  //   $IdTipoDocumento = !array_key_exists("IdTipoDocumento", $data) ? "%" : $data["IdTipoDocumento"];
  //   $estadocpe = $data["EstadoCPE"];
  //   $EstadoCPE_1 = $estadocpe == "'%'" ? "'%'" : "''";
  //   $EstadoCPE_2 = $estadocpe == "%" ? "'%'" : $data["EstadoCPE"];

  //   $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_RECHAZADO."'";
  //   $estado_aceptado = ESTADO_CPE_ACEPTADO;
  //   $estadoAnulado = ESTADO_DOCUMENTO_ANULADO;

  //   $estadoComunicacionBaja = "";

  //   $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
  //   $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
  //   $extensionConsulta = "";
  //   // if($vistaventa == 0)
  //   // {
  //   //   $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
  //   // }

  //   $consulta = "select CONCAT(td.NombreAbreviado,' ',grr.SerieDocumento,'-',grr.NumeroDocumento) as Numero,
  //       pe.NumeroDocumentoIdentidad, td.NombreAbreviado, td.NombreTipoDocumento,
  //       pe.RazonSocial as RazonSocialCliente, pe.Direccion,
  //       grre.CodigoError, efe.NombreErrorFacturacionElectronica AS DescripcionError, grr.*,
  //       tdi.CodigoDocumentoIdentidad, td.CodigoTipoDocumento, grre.NombreArchivoComprobante, sce.*
  //       from guiaremisionremitenteelectronica as grre
  //       inner join guiaremisionremitente as grr on grre.IdGuiaRemisionRemitente=grr.IdGuiaRemisionRemitente
  //       inner join tipodocumento td on td.IdTipoDocumento=grr.IdTipoDocumento
  //       inner join transportista cli on cli.IdTransportista = grr.IdTransportista
  //       inner join Persona pe on pe.IdPersona = cli.IdTransportista
  //       inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
  //       left join errorfacturacionelectronica efe on grre.CodigoError = efe.CodigoErrorFacturacionElectronica
  //       LEFT JOIN situacioncomprobanteelectronico sce ON sce.CodigoSituacionComprobanteElectronico = grr.SituacionCPE
  //       where grr.SerieDocumento like '$codigo_serie%' and
  //       (grr.SerieDocumento like '%$NumeroDocumento%' or
  //       grr.NumeroDocumento like '%$NumeroDocumento%')  and
  //       (pe.RazonSocial like '%$RazonSocial%' or pe.NumeroDocumentoIdentidad like '%$RazonSocial%')  and
  //       (grr.IndicadorEstado = '$estado' or grr.IndicadorEstado = '$estadoAnulado') and grre.IndicadorEstado='$estado' and
  //       (grr.FechaEmision between '$FechaInicio' AND '$FechaFin') And
  //       ( ($EstadoCPE_1= '%') OR  (grre.IndicadorEstadoCPE in ($EstadoCPE_2) ) ) And
  //       (grr.IdTipoDocumento like '%$IdTipoDocumento%')
  //       ".$extensionConsulta."
  //       ORDER BY td.NombreAbreviado ASC, grr.FechaEmision DESC, grr.SerieDocumento DESC,grr.NumeroDocumento DESC";

  //   $query = $this->db->query($consulta);
  //   $resultado = $query->result_array();

  //   return $resultado;
  // }

  function ConsultarGuiasRemisionRemitenteEnvio($data)
  {
    $codigo_serie = !array_key_exists("CodigoSerie", $data) ? "" : $data["CodigoSerie"];
    $estado = ESTADO_ACTIVO;
    $NumeroDocumento = $data["NumeroDocumento"];
    $RazonSocial = $data["RazonSocial"];
    $FechaInicio = $data["FechaInicio"];
    $FechaFin = $data["FechaFin"];
    $IdTipoDocumento = !array_key_exists("IdTipoDocumento", $data) ? "%" : $data["IdTipoDocumento"];
    $EstadoCPE = $data["EstadoCPE"];
    $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_EN_PROCESO."'";
    $estadoComunicacionBaja = "";

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    // if($vistaventa == 0)
    // {
    //   $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
    // }

    $consulta = "SELECT CONCAT(td.NombreAbreviado,' ',grr.SerieDocumento,'-',grr.NumeroDocumento) as Numero,
                  pe.NumeroDocumentoIdentidad, pe.RazonSocial as RazonSocialCliente, pe.Direccion,
                  grre.IdGuiaRemisionRemitenteElectronica, grr.*, tdi.CodigoDocumentoIdentidad,
                  td.CodigoTipoDocumento, grre.NombreArchivoComprobante
                  from guiaremisionremitenteelectronica as grre
                  inner join guiaremisionremitente as grr on grre.IdGuiaRemisionRemitente=grr.IdGuiaRemisionRemitente
                  inner join tipodocumento td on td.IdTipoDocumento=grr.IdTipoDocumento
                  inner join Persona pe on pe.IdPersona = grr.IdTransportista
                  inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
                  where grr.SerieDocumento like '$codigo_serie%' and
                  (grr.SerieDocumento like '%$NumeroDocumento%' or
                  grr.NumeroDocumento like '%$NumeroDocumento%')  and
                  (pe.RazonSocial like '%$RazonSocial%' or
                  pe.NumeroDocumentoIdentidad like '%$RazonSocial%')  and
                  (grr.IndicadorEstado = '$estado') and grre.IndicadorEstado='$estado' and
                  (grr.FechaEmision between '$FechaInicio' AND '$FechaFin') and
                  (grre.IndicadorEstadoCPE like '%$EstadoCPE%') And
                  (grr.IdTipoDocumento like '%$IdTipoDocumento%') And
                  (grre.IndicadorEstadoCPE not in($excluye))
                  ".$extensionConsulta."
                  ORDER BY grr.FechaEmision DESC, td.NombreTipoDocumento, grr.SerieDocumento, grr.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitenteEnvioPendiente($data)
  {
    $codigo_serie = !array_key_exists("CodigoSerie", $data) ? "" : $data["CodigoSerie"];
    $estado = ESTADO_ACTIVO;
    $NumeroDocumento = $data["NumeroDocumento"];
    $RazonSocial = $data["RazonSocial"];
    $FechaInicio = $data["FechaInicio"];
    $FechaFin = $data["FechaFin"];
    $IdTipoDocumento = !array_key_exists("IdTipoDocumento", $data) ? "%" : $data["IdTipoDocumento"];
    $EstadoCPE = ESTADO_CPE_EN_PROCESO; //$data["EstadoCPE"];
    $estadoComunicacionBaja = "";

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    // if($vistaventa == 0)
    // {
    //   $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
    // }

    $consulta = "select CONCAT(td.NombreAbreviado,' ',grr.SerieDocumento,'-',grr.NumeroDocumento) as Numero,
                  pe.NumeroDocumentoIdentidad, pe.RazonSocial as RazonSocialCliente, pe.Direccion,
                  grre.IdGuiaRemisionRemitenteElectronica, grr.*, grre.IndicadorEstadoCPE,
                  tdi.CodigoDocumentoIdentidad, td.CodigoTipoDocumento, grre.NombreArchivoComprobante,
                  grre.NumeroTicket
                  from guiaremisionremitenteelectronica as grre
                  inner join guiaremisionremitente as grr on grre.IdGuiaRemisionRemitente=grr.IdGuiaRemisionRemitente
                  inner join tipodocumento td on td.IdTipoDocumento=grr.IdTipoDocumento
                  inner join transportista cli on cli.IdTransportista = grr.IdTransportista
                  inner join Persona pe on pe.IdPersona = cli.IdTransportista
                  inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
                  where
                  grr.SerieDocumento like '$codigo_serie%' and
                  (grr.SerieDocumento like '%$NumeroDocumento%' or
                  grr.NumeroDocumento like '%$NumeroDocumento%')  and
                  (pe.RazonSocial like '%$RazonSocial%' or
                  pe.NumeroDocumentoIdentidad like '%$RazonSocial%')  and
                  (grr.IndicadorEstado = '$estado') and grre.IndicadorEstado='$estado' and
                  (grr.FechaEmision between '$FechaInicio' AND '$FechaFin') and
                  (grre.IndicadorEstadoCPE like '%$EstadoCPE%') And
                  (grr.IdTipoDocumento like '%$IdTipoDocumento%') And
                  (grre.IndicadorEstadoCPE in('".ESTADO_CPE_EN_PROCESO."'))
                  ".$extensionConsulta."
                  ORDER BY grr.FechaEmision DESC, td.NombreTipoDocumento, grr.SerieDocumento, grr.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarGuiasRemisionRemitenteElectronica($data)
  {
    $NumeroDocumento = $data["NumeroDocumento"];
    $FechaInicio = $data["FechaInicio"];
    $FechaFin = $data["FechaFin"];
    $EstadoCPE = $data["EstadoCPE"];
    
    if ($EstadoCPE == '%') {
      $estadoTodo = ' AND GRR.SituacionCPE LIKE "'.$EstadoCPE.'" ';
    }
    else {
      $estadoTodo =' AND GRR.SituacionCPE IN ('.$EstadoCPE.') ';
    }

    $consulta = "SELECT GRR.*, CONCAT(GRR.SerieDocumento,'-',GRR.NumeroDocumento) as Numero,
                TD.NombreAbreviado, TD.CodigoTipoDocumento, MT.CodigoMotivoTraslado, MT.NombreMotivoTraslado,
                MDT.CodigoModalidadTraslado, MDT.NombreModalidadTraslado,
                PD.RazonSocial 'RazonSocialDestinatario', PD.NumeroDocumentoIdentidad 'NumeroDocumentoDestinatario',
                TDID.CodigoDocumentoIdentidad 'CodigoTipoDocumentoDestinatario',
                
                PT.RazonSocial 'RazonSocialTransportista', PT.NumeroDocumentoIdentidad 'NumeroDocumentoTransportista',
                TDIT.CodigoDocumentoIdentidad 'CodigoTipoDocumentoTransportista',
                
                CONCAT(DPP.CodigoUbigeoDepartamento,'',PRP.CodigoUbigeoProvincia,'',DTP.CodigoUbigeoDistrito) as CodigoUbigeoPuntoPartida,
                CONCAT(DPL.CodigoUbigeoDepartamento,'',PRL.CodigoUbigeoProvincia,'',DTL.CodigoUbigeoDistrito) as CodigoUbigeoPuntoLlegada,
                
                GRRE.CodigoError, GRRE.DescripcionError, GRRE.CodigoHash, GRRE.NombreArchivoComprobante, 
                SCE.CodigoSituacionComprobanteElectronico, SCE.NombreSituacionComprobanteElectronico, 
                SCE.AbreviaturaSituacionComprobanteElectronicoActual, SCE.AbreviaturaSituacionComprobanteElectronicoActual,
                TD.NombreTipoDocumento
                FROM GuiaRemisionRemitente AS  GRR
                LEFT JOIN GuiaRemisionRemitenteElectronica AS GRRE ON GRRE.IdGuiaRemisionRemitente = GRR.IdGuiaRemisionRemitente
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
                
                LEFT JOIN ErrorFacturacionElectronica AS EFE ON GRRE.CodigoError = EFE.CodigoErrorFacturacionElectronica
                LEFT JOIN SituacionComprobanteElectronico AS SCE ON SCE.CodigoSituacionComprobanteElectronico = GRR.SituacionCPE

                WHERE GRR.SerieDocumento LIKE '$NumeroDocumento' AND
                (GRR.FechaEmision BETWEEN '$FechaInicio' AND '$FechaFin') 
                ".$estadoTodo."
                AND GRRE.IndicadorEstado != 'E'
                ORDER BY GRR.SerieDocumento, GRR.NumeroDocumento DESC, GRR.FechaEmision DESC, TD.NombreTipoDocumento";
                //print_r($consulta);exit;

    $query = $this->db->query($consulta);

    $resultado = $query->result_array();
    return $resultado;
  }

}
