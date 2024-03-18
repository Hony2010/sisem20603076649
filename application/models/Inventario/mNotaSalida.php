<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mNotaSalida extends CI_Model {

  public $NotaSalida = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->NotaSalida = $this->Base->Construir("NotaSalida");
  }

  function InsertarNotaSalida($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->NotaSalida);
    $this->db->insert('NotaSalida', $resultado);
    $resultado["IdNotaSalida"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarNotaSalida($data)
  {
    $id=$data["IdNotaSalida"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->NotaSalida);
    $this->db->where('IdNotaSalida', $id);
    $this->db->update('NotaSalida', $resultado);

    return $resultado;
  }

  function BorrarNotaSalida($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarNotaSalida($data);
  }

  function ConsultarNotasSalida($data,$numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipodocumento=$data["TipoDocumento"];

    $query = $this->db->query("Select
        NS.*, TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
        U.AliasUsuarioVenta, Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
        MND.SimboloMoneda,
        IF(ISNULL(DRNS.IdDocumentoReferenciaNotaSalida), '0', '1') as Referencia,
        SG.IdSede
        From NotaSalida As NS
        Inner Join TipoDocumento As TD on TD.IdTipoDocumento = NS.IdTipoDocumento
        left Join MotivoNotaSalida As MNE on MNE.IdMotivoNotaSalida = NS.IdMotivoNotaSalida
        left Join Persona As Per on Per.IdPersona = NS.IdPersona
        inner Join Moneda As MND on MND.IdMoneda = NS.IdMoneda
        inner Join Usuario As U on U.IdUsuario = NS.IdUsuario
        left Join DocumentoReferenciaNotaSalida As DRNS on DRNS.IdNotaSalida = NS.IdNotaSalida AND DRNS.IndicadorEstado = 'A'
        left join AsignacionSede As SG on SG.IdAsignacionSede = NS.IdAsignacionSede
        Where (NS.IndicadorEstado = 'A' OR NS.IndicadorEstado = 'N' ) and
        (NS.SerieNotaSalida like '%$criterio%' or
        NS.NumeroNotaSalida like '%$criterio%') And
        NS.IdTipoDocumento like '$tipodocumento' And
        NS.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
        ORDER BY NS.FechaEmision DESC, TD.NombreAbreviado ASC
        LIMIT $numerofilainicio,$numerorfilasporpagina");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalNotasSalida($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipodocumento=$data["TipoDocumento"];
    $query = $this->db->query("Select
          NS.*, TD.NombreAbreviado, MND.NombreMoneda, MND.SimboloMoneda,
          U.AliasUsuarioVenta, Per.IdPersona, Per.RazonSocial,
          Per.NumeroDocumentoIdentidad,Per.Direccion
          From NotaSalida As NS
          inner Join TipoDocumento As TD on TD.IdTipoDocumento = NS.IdTipoDocumento
          left Join Persona As Per on Per.IdPersona = NS.IdPersona
          left Join MotivoNotaSalida As MNE on MNE.IdMotivoNotaSalida = NS.IdMotivoNotaSalida
          inner Join Moneda As MND on MND.IdMoneda = NS.IdMoneda
          inner Join Usuario As U on U.IdUsuario = NS.IdUsuario
          Where (NS.IndicadorEstado = 'A' OR NS.IndicadorEstado='N' ) and
          (NS.SerieNotaSalida like '%$criterio%' or
          NS.NumeroNotaSalida like '%$criterio%') And
          NS.IdTipoDocumento like '$tipodocumento' And
          NS.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
          ORDER BY NS.IdNotaSalida");
    $resultado = $query->num_rows();
    return $resultado;
  }

  function ObtenerNotaSalidaVentaSinDocumento($data)
  {
    $serie = $data["SerieNotaSalida"];
    $numero = $data["NumeroNotaSalida"];
    $idmotivo = ID_MOTIVO_NOTA_SALIDA_VENTA_SIN_DOCUMENTO;

    $query = $this->db->query("SELECT ns.SerieNotaSalida, ns.NumeroNotaSalida, dns.*
                              FROM notasalida ns
                              INNER JOIN detallenotasalida dns ON dns.IdNotaSalida = ns.IdNotaSalida
                              WHERE (ns.SerieNotaSalida = '$serie' AND ns.NumeroNotaSalida = '$numero') 
                              AND ns.IdMotivoNotaSalida = '$idmotivo' AND ns.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

 }
