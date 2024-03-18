<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mSaldoInicialCuentaCobranza extends CI_Model {

  public $SaldoInicialCuentaCobranza = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->SaldoInicialCuentaCobranza = $this->Base->Construir("SaldoInicialCuentaCobranza");
  }

  function InsertarSaldoInicialCuentaCobranza($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->SaldoInicialCuentaCobranza);
    $this->db->insert('SaldoInicialCuentaCobranza', $resultado);
    $resultado["IdSaldoInicialCuentaCobranza"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarSaldoInicialCuentaCobranza($data)
  {
    $id=$data["IdSaldoInicialCuentaCobranza"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->SaldoInicialCuentaCobranza);
    $this->db->where('IdSaldoInicialCuentaCobranza', $id);
    $this->db->update('SaldoInicialCuentaCobranza', $resultado);

    return $resultado;
  }

  function BorrarSaldoInicialCuentaCobranza($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarSaldoInicialCuentaCobranza($data);
    return $resultado;
  }

  function ConsultarSaldosInicialCuentaCobranza($data,$numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["TextoFiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];

    $consulta = "Select SICC.*,
                TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                M.SimboloMoneda
                from SaldoInicialCuentaCobranza SICC
                left join TipoDocumento TD ON TD.IdTipoDocumento = SICC.IdTipoDocumento
                left join Persona P ON P.IdPersona = SICC.IdCliente
                left join Moneda M ON M.IdMoneda = SICC.IdMoneda
                left join Usuario U ON U.IdUsuario = SICC.IdUsuario
                WHERE (SICC.IndicadorEstado = 'A' OR SICC.IndicadorEstado = 'N' )
                AND (SICC.SerieDocumento like '%$criterio%'
                OR SICC.NumeroDocumento like '%$criterio%'
                OR P.RazonSocial like '%$criterio%'
                OR P.NumeroDocumentoIdentidad like '%$criterio%')
                AND SICC.FechaEmision BETWEEN '$fechainicio' AND '$fechafin'
                ORDER BY TD.NombreTipoDocumento, SICC.FechaEmision DESC, TD.NombreAbreviado ASC, SICC.SerieDocumento DESC, SICC.NumeroDocumento DESC
                LIMIT $numerofilainicio, $numerorfilasporpagina";
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalSaldosInicialCuentaCobranza($data)
  {
      $criterio=$data["TextoFiltro"];
      $fechainicio =$data["FechaInicio"];
      $fechafin =$data["FechaFin"];

      $consulta = "Select SICC.*,
                  TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                  P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                  M.SimboloMoneda
                  from SaldoInicialCuentaCobranza SICC
                  left join TipoDocumento TD ON TD.IdTipoDocumento = SICC.IdTipoDocumento
                  left join Persona P ON P.IdPersona = SICC.IdCliente
                  left join Moneda M ON M.IdMoneda = SICC.IdMoneda
                  left join Usuario U ON U.IdUsuario = SICC.IdUsuario
                  Where (SICC.IndicadorEstado = 'A' OR SICC.IndicadorEstado='N' ) and
                  (SICC.SerieDocumento like '%$criterio%' or
                  SICC.NumeroDocumento like '%$criterio%' or
                  P.RazonSocial like '%$criterio%' or
                  P.NumeroDocumentoIdentidad like '%$criterio%') And
                  SICC.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                  ORDER BY SICC.SerieDocumento,SICC.NumeroDocumento";

      $query = $this->db->query($consulta);
      $resultado = $query->num_rows();
      return $resultado;
  }

  function ObtenerSaldoInicialCuentaCobranzaPorSerieDocumentoInsertar($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("Select SICC.*
        from SaldoInicialCuentaCobranza As SICC
        where SICC.IndicadorEstado = 'A'
        and SICC.SerieDocumento = '$serie'
        and SICC.NumeroDocumento='$numero'
        and SICC.IdTipoDocumento = '$tipo'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerSaldoInicialCuentaCobranzaPorSerieDocumentoActualizar($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $id=$data["IdSaldoInicialCuentaCobranza"];
    $query = $this->db->query("Select SICC.*
        from SaldoInicialCuentaCobranza As SICC
        where SICC.IdSaldoInicialCuentaCobranza != '$id'
        and SICC.IndicadorEstado = 'A'
        and SICC.SerieDocumento = '$serie'
        and SICC.NumeroDocumento='$numero'
        and SICC.IdTipoDocumento = '$tipo'");
    $resultado = $query->result_array();
    return $resultado;
  }
}
