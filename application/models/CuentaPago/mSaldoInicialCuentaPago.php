<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mSaldoInicialCuentaPago extends CI_Model {

  public $SaldoInicialCuentaPago = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->SaldoInicialCuentaPago = $this->Base->Construir("SaldoInicialCuentaPago");
  }

  function InsertarSaldoInicialCuentaPago($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->SaldoInicialCuentaPago);
    $this->db->insert('SaldoInicialCuentaPago', $resultado);
    $resultado["IdSaldoInicialCuentaPago"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarSaldoInicialCuentaPago($data)
  {
    $id=$data["IdSaldoInicialCuentaPago"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->SaldoInicialCuentaPago);
    $this->db->where('IdSaldoInicialCuentaPago', $id);
    $this->db->update('SaldoInicialCuentaPago', $resultado);
    return $resultado;
  }

  function BorrarSaldoInicialCuentaPago($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarSaldoInicialCuentaPago($data);
    return $resultado;
  }

  function ConsultarSaldosInicialCuentaPago($data,$numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["TextoFiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];

    $consulta = "Select SICP.*,
                TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                M.SimboloMoneda
                from SaldoInicialCuentaPago SICP
                left join TipoDocumento TD ON TD.IdTipoDocumento = SICP.IdTipoDocumento
                left join Persona P ON P.IdPersona = SICP.IdProveedor
                left join Moneda M ON M.IdMoneda = SICP.IdMoneda
                left join Usuario U ON U.IdUsuario = SICP.IdUsuario
                WHERE (SICP.IndicadorEstado = 'A' OR SICP.IndicadorEstado = 'N' )
                AND (SICP.SerieDocumento like '%$criterio%'
                OR SICP.NumeroDocumento like '%$criterio%'
                OR P.RazonSocial like '%$criterio%'
                OR P.NumeroDocumentoIdentidad like '%$criterio%')
                AND SICP.FechaEmision BETWEEN '$fechainicio' AND '$fechafin'
                ORDER BY TD.NombreTipoDocumento, SICP.FechaEmision DESC, TD.NombreAbreviado ASC, SICP.SerieDocumento DESC, SICP.NumeroDocumento DESC
                LIMIT $numerofilainicio, $numerorfilasporpagina";
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalSaldosInicialCuentaPago($data)
  {
      $criterio=$data["TextoFiltro"];
      $fechainicio =$data["FechaInicio"];
      $fechafin =$data["FechaFin"];

      $consulta = "Select SICP.*,
                  TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                  P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                  M.SimboloMoneda
                  from SaldoInicialCuentaPago SICP
                  left join TipoDocumento TD ON TD.IdTipoDocumento = SICP.IdTipoDocumento
                  left join Persona P ON P.IdPersona = SICP.IdProveedor
                  left join Moneda M ON M.IdMoneda = SICP.IdMoneda
                  left join Usuario U ON U.IdUsuario = SICP.IdUsuario
                  Where (SICP.IndicadorEstado = 'A' OR SICP.IndicadorEstado='N' ) and
                  (SICP.SerieDocumento like '%$criterio%' or
                  SICP.NumeroDocumento like '%$criterio%' or
                  P.RazonSocial like '%$criterio%' or
                  P.NumeroDocumentoIdentidad like '%$criterio%') And
                  SICP.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                  ORDER BY SICP.SerieDocumento,SICP.NumeroDocumento";

      $query = $this->db->query($consulta);
      $resultado = $query->num_rows();
      return $resultado;
  }

  function ObtenerSaldoInicialCuentaPagoPorSerieDocumentoInsertar($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("Select SICP.*
        from SaldoInicialCuentaPago As SICP
        where SICP.IndicadorEstado = 'A'
        and SICP.SerieDocumento = '$serie'
        and SICP.NumeroDocumento='$numero'
        and SICP.IdTipoDocumento = '$tipo'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerSaldoInicialCuentaPagoPorSerieDocumentoActualizar($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $id=$data["IdSaldoInicialCuentaPago"];
    $query = $this->db->query("Select SICP.*
        from SaldoInicialCuentaPago As SICP
        where SICP.IdSaldoInicialCuentaPago != '$id'
        and SICP.IndicadorEstado = 'A'
        and SICP.SerieDocumento = '$serie'
        and SICP.NumeroDocumento='$numero'
        and SICP.IdTipoDocumento = '$tipo'");
    $resultado = $query->result_array();
    return $resultado;
  }
}
