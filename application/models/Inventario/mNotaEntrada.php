<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mNotaEntrada extends CI_Model {

  public $NotaEntrada = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->NotaEntrada = $this->Base->Construir("NotaEntrada");
  }

  function InsertarNotaEntrada($data) {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->NotaEntrada);
    $this->db->insert('NotaEntrada', $resultado);
    $resultado["IdNotaEntrada"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarNotaEntrada($data) {
    $id=$data["IdNotaEntrada"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->NotaEntrada);
    $this->db->where('IdNotaEntrada', $id);
    $this->db->update('NotaEntrada', $resultado);

    return $resultado;
  }

  function BorrarNotaEntrada($data) {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarNotaEntrada($data);
  }

  function ConsultarNotasEntrada($data,$numerofilainicio,$numerorfilasporpagina) {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipodocumento=$data["TipoDocumento"];

    $query = $this->db->query("Select
        NE.*, TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
        U.AliasUsuarioVenta, Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
        MND.SimboloMoneda,
        IF(ISNULL(DRNE.IdDocumentoReferenciaNotaEntrada), '0', '1') as Referencia,
        CONCAT(DRNE.NombreAbreviadoDocumentoReferencia,' ',DRNE.SerieDocumentoReferencia,'-',DRNE.NumeroDocumentoReferencia) As DocumentoReferencia,
        SG.IdSede
        From NotaEntrada As NE
        Inner Join TipoDocumento As TD on TD.IdTipoDocumento = NE.IdTipoDocumento
        left Join MotivoNotaEntrada As MNE on MNE.IdMotivoNotaEntrada = NE.IdMotivoNotaEntrada
        left Join Persona As Per on Per.IdPersona = NE.IdPersona
        inner Join Moneda As MND on MND.IdMoneda = NE.IdMoneda
        inner Join Usuario As U on U.IdUsuario = NE.IdUsuario
        left Join DocumentoReferenciaNotaEntrada As DRNE on DRNE.IdNotaEntrada = NE.IdNotaEntrada AND DRNE.IndicadorEstado = 'A'
        left join AsignacionSede As SG on SG.IdAsignacionSede = NE.IdAsignacionSede 
        Where (NE.IndicadorEstado = 'A' OR NE.IndicadorEstado = 'N' ) and
        (NE.SerieNotaEntrada like '%$criterio%' or
        NE.NumeroNotaEntrada like '%$criterio%') And
        NE.IdTipoDocumento like '$tipodocumento' And
        NE.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
        ORDER BY NE.FechaEmision DESC, TD.NombreAbreviado ASC
        LIMIT $numerofilainicio,$numerorfilasporpagina");
    $resultado = $query->result_array();
    
    return $resultado;
  }

  function ObtenerNumeroTotalNotasEntrada($data) {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipodocumento=$data["TipoDocumento"];
    $query = $this->db->query("Select
          NE.*, TD.NombreAbreviado, MND.NombreMoneda, MND.SimboloMoneda,
          U.AliasUsuarioVenta, Per.IdPersona, Per.RazonSocial,
          Per.NumeroDocumentoIdentidad,Per.Direccion
          From NotaEntrada As NE
          inner Join TipoDocumento As TD on TD.IdTipoDocumento = NE.IdTipoDocumento
          left Join Persona As Per on Per.IdPersona = NE.IdPersona
          left Join MotivoNotaEntrada As MNE on MNE.IdMotivoNotaEntrada = NE.IdMotivoNotaEntrada
          inner Join Moneda As MND on MND.IdMoneda = NE.IdMoneda
          inner Join Usuario As U on U.IdUsuario = NE.IdUsuario
          Where (NE.IndicadorEstado = 'A' OR NE.IndicadorEstado='N' ) and
          (NE.SerieNotaEntrada like '%$criterio%' or
          NE.NumeroNotaEntrada like '%$criterio%') And
          NE.IdTipoDocumento like '$tipodocumento' And
          NE.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
          ORDER BY NE.IdNotaEntrada");
    $resultado = $query->num_rows();
    return $resultado;
  }

 }
