<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mCanjeLetraCobrar extends CI_Model
{

  public $CanjeLetraCobrar = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->CanjeLetraCobrar = $this->Base->Construir("CanjeLetraCobrar");
  }

  function InsertarCanjeLetraCobrar($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->CanjeLetraCobrar);
    $this->db->insert('CanjeLetraCobrar', $resultado);
    $resultado["IdCanjeLetraCobrar"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarCanjeLetraCobrar($data)
  {
    $id = $data["IdCanjeLetraCobrar"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->CanjeLetraCobrar);
    $this->db->where('IdCanjeLetraCobrar', $id);
    $this->db->update('CanjeLetraCobrar', $resultado);

    return $resultado;
  }

  function BorrarCanjeLetraCobrar($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarCanjeLetraCobrar($data);
    return $resultado;
  }

  function ObtenerComprobanteVentaPorIdCanjeLetraCobrar($data)
  {
    $id = $data["IdCanjeLetraCobrar"];
    $query = $this->db->query("SELECT DISTINCT(DPLC.IdComprobanteVenta)
              FROM DetallePendienteLetraCobrar DPLC
              INNER JOIN PendienteLetraCobrar PLC ON PLC.IdPendienteLetraCobrar = DPLC.IdPendienteLetraCobrar
              INNER JOIN CanjeLetraCobrar CLC ON CLC.IdCanjeLetraCobrar = PLC.IdCanjeLetraCobrar
              WHERE CLC.IdCanjeLetraCobrar = '$id' 
              AND PLC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  //OTRO
  function ObtenerFechaMenor($data)
  {
    $tipodocumento = $data["IdTipoDocumento"];
    $numero = $data["NumeroDocumento"];
    $serie = $data["SerieDocumento"];
    $query = $this->db->query("Select max(FechaDocumento) AS FechaDocumentoMenor  FROM CanjeLetraCobrar
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
    $query = $this->db->query("Select min(FechaDocumento) AS FechaDocumentoMayor FROM CanjeLetraCobrar
      WHERE (IndicadorEstado='A' or IndicadorEstado='N') AND NumeroDocumento>'$numero' AND
      SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerCanjeLetraCobrar($data)
  {
    $IdCanjeLetraCobrar = $data["IdCanjeLetraCobrar"];

    // $query = $this->db->query("select
    //                                 CONCAT(TD.NombreAbreviado, '-', CLC.SerieDocumento,'-',CLC.NumeroDocumento) AS Numero,
    //                                 CONCAT(M.SimboloMoneda,' ',CAST(CLC.ImporteTotalCanje AS char(10))) AS TotalComprobante,
    //                                 P.NumeroDocumentoIdentidad, TD.NombreAbreviado, TD.NombreTipoDocumento,
    //                                 P.RazonSocial AS RazonSocialCliente, P.Direccion,
    //                                 CLC.*, M.CodigoMoneda, TDI.CodigoDocumentoIdentidad,
    //                                 TD.CodigoTipoDocumento FROM CanjeLetraCobrar CLC
    //                                 INNER JOIN TipoDocumento TD ON TD.IdTipoDocumento = CLC.IdTipoDocumento
    //                                 INNER JOIN Persona P On P.IdPersona = CLC.IdCliente
    //                                 INNER JOIN TipoDocumentoIdentidad TDI ON TDI.IdTipoDocumentoIdentidad = P.IdTipoDocumentoIdentidad
    //                                 INNER JOIN Moneda M ON M.IdMoneda = CLC.IdMoneda
    //                                 LEFT JOIN TipoOperacionCaja CCA ON CCA.IdTipoOperacionCaja = CLC.IdTipoOperacionCaja
    //                                 WHERE CLC.IdCanjeLetraCobrar = '$IdCanjeLetraCobrar'");

    $query = $this->db->query("select
                                    CONCAT(TD.NombreAbreviado, '-', CLC.SerieDocumento,'-',CLC.NumeroDocumento) AS Numero,
                                    TD.NombreAbreviado, TD.NombreTipoDocumento,
                                    CLC.*
                                    FROM CanjeLetraCobrar CLC
                                    INNER JOIN TipoDocumento TD ON TD.IdTipoDocumento = CLC.IdTipoDocumento
                                    WHERE CLC.IdCanjeLetraCobrar = '$IdCanjeLetraCobrar'");

    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCanjeLetraCobrarPorSerieDocumento($data)
  {
    $tipo = $data["IdTipoDocumento"];
    $serie = $data["SerieDocumento"];
    $numero = $data["NumeroDocumento"];
    $query = $this->db->query("Select CLC.*
            from CanjeLetraCobrar As CLC
            where CLC.IndicadorEstado = 'A' and CLC.SerieDocumento = '$serie' and CLC.NumeroDocumento='$numero' and CLC.IdTipoDocumento = '$tipo'");
    $resultado = $query->row();
    return $resultado;
  }

  //consultas
  function ConsultarCanjesLetraCobrar($data, $numerofilainicio, $numerorfilasporpagina)
  {
    $criterio = $data["TextoFiltro"];
    $fechainicio = $data["FechaInicio"];
    $fechafin = $data["FechaFin"];
    $tipodocumento = $data["IdTipoDocumento"];
    $moneda = $data["IdMoneda"];
    $consulta = "Select CLC.*, 
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda
                    From CanjeLetraCobrar CLC
                    LEFT JOIN TipoDocumento TD ON TD.IdTipoDocumento = CLC.IdTipoDocumento
                    LEFT JOIN Persona P ON P.IdPersona = CLC.IdCliente
                    LEFT JOIN Moneda M ON M.IdMoneda = CLC.IdMoneda
                    WHERE (CLC.IndicadorEstado = 'A' OR CLC.IndicadorEstado = 'N' )
                    AND (CLC.SerieDocumento like '%$criterio%'
                    OR CLC.NumeroDocumento like '%$criterio%'
                    OR P.RazonSocial like '%$criterio%'
                    OR P.NumeroDocumentoIdentidad like '%$criterio%')
                    AND CLC.IdTipoDocumento like '$tipodocumento'
                    AND CLC.IdMoneda like '$moneda'
                    AND CLC.FechaDocumento BETWEEN '$fechainicio' AND '$fechafin'
                    ORDER BY TD.NombreTipoDocumento, CLC.FechaDocumento DESC, TD.NombreAbreviado ASC, CLC.SerieDocumento DESC, CLC.NumeroDocumento DESC
                    LIMIT $numerofilainicio, $numerorfilasporpagina";
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalCanjesLetraCobrar($data)
  {
    $criterio = $data["TextoFiltro"];
    $fechainicio = $data["FechaInicio"];
    $fechafin = $data["FechaFin"];
    $tipodocumento = $data["IdTipoDocumento"];
    $moneda = $data["IdMoneda"];

    $consulta = "Select CLC.*, 
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda
                    From CanjeLetraCobrar CLC
                    LEFT JOIN TipoDocumento TD ON TD.IdTipoDocumento = CLC.IdTipoDocumento
                    LEFT JOIN Persona P ON P.IdPersona = CLC.IdCliente
                    LEFT JOIN Moneda M ON M.IdMoneda = CLC.IdMoneda
                    Where (CLC.IndicadorEstado = 'A' OR CLC.IndicadorEstado='N' ) and
                    (CLC.SerieDocumento like '%$criterio%' or
                    CLC.NumeroDocumento like '%$criterio%' or
                    P.RazonSocial like '%$criterio%' or
                    P.NumeroDocumentoIdentidad like '%$criterio%') And
                    CLC.IdTipoDocumento like '$tipodocumento' And
                    CLC.IdMoneda like '$moneda' AND
                    CLC.FechaDocumento BETWEEN '$fechainicio' And '$fechafin'
                    ORDER BY CLC.SerieDocumento,CLC.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->num_rows();
    return $resultado;
  }
}
