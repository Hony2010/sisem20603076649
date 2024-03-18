<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mPendienteLetraCobrar extends CI_Model
{

  public $PendienteLetraCobrar = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->PendienteLetraCobrar = $this->Base->Construir("PendienteLetraCobrar");
  }

  function InsertarPendienteLetraCobrar($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->PendienteLetraCobrar);
    $this->db->insert('PendienteLetraCobrar', $resultado);
    $resultado["IdPendienteLetraCobrar"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarPendienteLetraCobrar($data)
  {
    $id = $data["IdPendienteLetraCobrar"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->PendienteLetraCobrar);
    $this->db->where('IdPendienteLetraCobrar', $id);
    $this->db->update('PendienteLetraCobrar', $resultado);

    return $resultado;
  }

  function BorrarPendienteLetraCobrar($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarPendienteLetraCobrar($data);
    return $resultado;
  }

  function ObtenerPendientesLetraCobrarPorIdCanjeLetraCobrar($data)
  {
    $id = $data["IdCanjeLetraCobrar"];
    $query = $this->db->query("SELECT PLC.*, P.RazonSocial, P.NumeroDocumentoIdentidad
              FROM PendienteLetraCobrar PLC 
              INNER JOIN CanjeLetraCobrar CLC ON CLC.IdCanjeLetraCobrar = PLC.IdCanjeLetraCobrar
              INNER JOIN Persona P ON P.IdPersona = PLC.IdCliente
              WHERE CLC.IdCanjeLetraCobrar = '$id' 
              AND PLC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesLetraCobrarPorIdClienteYFiltro($data)
  {
    $cliente=$data["IdCliente"];
    $criterio=$data["TextoFiltro"];
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $query = $this->db->query("SELECT pcc.*, m.* FROM PendienteLetraCobrar pcc
                                LEFT JOIN moneda m ON m.Idmoneda = pcc.IdMoneda
                                WHERE pcc.IdCliente = '$cliente'
                                AND (pcc.SerieDocumento like '%$criterio%' or pcc.NumeroDocumento like '%$criterio%')
                                AND (pcc.FechaEmision BETWEEN '$fechainicio' AND '$fechafin')
                                AND pcc.SaldoPendiente > 0
                                AND pcc.IndicadorEstado = 'A'
                                ORDER BY pcc.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerPendienteLetraCobrarPorIdComprobanteVenta($data)
  {
    $comprobante=$data["IdComprobanteVenta"];
    $query = $this->db->query("SELECT pcc.*, m.*, p.RazonSocial FROM PendienteLetraCobrar pcc
                                LEFT JOIN comprobanteventa cv ON cv.IdComprobanteVenta = pcc.IdComprobanteVenta
                                LEFT JOIN moneda m ON m.Idmoneda = cv.IdMoneda
                                LEFT JOIN persona p ON p.IdPersona = pcc.IdCliente
                                WHERE pcc.IdComprobanteVenta = '$comprobante'
                                AND  pcc.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  //OTRO
  function ObtenerFechaMenor($data)
  {
    $tipodocumento = $data["IdTipoDocumento"];
    $numero = $data["NumeroDocumento"];
    $serie = $data["SerieDocumento"];
    $query = $this->db->query("SELECT max(FechaGiro) AS FechaGiroMenor  FROM PendienteLetraCobrar
      WHERE (IndicadorEstado='A' or IndicadorEstado='N') AND NumeroDocumento<'$numero' AND
      SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerFechaMayor($data)
  {
    $tipodocumento = $data["IdTipoDocumento"];
    $numero = $data["NumeroDocumento"];
    $serie = $data["SerieDocumento"];
    $query = $this->db->query("SELECT min(FechaGiro) AS FechaGiroMayor FROM PendienteLetraCobrar
      WHERE (IndicadorEstado='A' or IndicadorEstado='N') AND NumeroDocumento>'$numero' AND
      SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerPendienteLetraCobrar($data)
  {
    $IdPendienteLetraCobrar = $data["IdPendienteLetraCobrar"];

    $query = $this->db->query("SELECT
                                    CONCAT(TD.NombreAbreviado, '-', PLC.SerieDocumento,'-',PLC.NumeroDocumento) AS Numero,
                                    CONCAT(M.SimboloMoneda,' ',CAST(PLC.ImporteLetra AS char(10))) AS TotalComprobante,
                                    P.NumeroDocumentoIdentidad, TD.NombreAbreviado, TD.NombreTipoDocumento,
                                    P.RazonSocial AS RazonSocialCliente, P.Direccion,
                                    PLC.*, M.CodigoMoneda, TDI.CodigoDocumentoIdentidad,
                                    TD.CodigoTipoDocumento FROM PendienteLetraCobrar PLC
                                    INNER JOIN TipoDocumento TD ON TD.IdTipoDocumento = PLC.IdTipoDocumento
                                    INNER JOIN Persona P On P.IdPersona = PLC.IdCliente
                                    INNER JOIN TipoDocumentoIdentidad TDI ON TDI.IdTipoDocumentoIdentidad = P.IdTipoDocumentoIdentidad
                                    INNER JOIN Moneda M ON M.IdMoneda = PLC.IdMoneda
                                    WHERE PLC.IdPendienteLetraCobrar = '$IdPendienteLetraCobrar'");

    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerPendienteLetraCobrarPorSerieDocumento($data)
  {
    $tipo = $data["IdTipoDocumento"];
    $serie = $data["SerieDocumento"];
    $numero = $data["NumeroDocumento"];
    $query = $this->db->query("SELECT PLC.*
            from PendienteLetraCobrar As PLC
            where PLC.IndicadorEstado = 'A' AND PLC.SerieDocumento = '$serie' AND PLC.NumeroDocumento='$numero' AND PLC.IdTipoDocumento = '$tipo'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarPendientesLetraCobrarParaCobranza()
  {
    $query = $this->db->query("SELECT PLC.*
            from PendienteLetraCobrar As PLC
            where PLC.IndicadorEstado = 'A' AND PLC.ImporteCobrado > 0");
    $resultado = $query->result_array();
    return $resultado;
  }
}
