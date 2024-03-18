<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mPendientePagoProveedor extends CI_Model {

  public $PendientePagoProveedor = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->PendientePagoProveedor = $this->Base->Construir("PendientePagoProveedor");
  }

  function InsertarPendientePagoProveedor($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
  
    $resultado = $this->mapper->map($data,$this->PendientePagoProveedor);
    $this->db->insert('PendientePagoProveedor', $resultado);
    $resultado["IdPendientePagoProveedor"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarPendientePagoProveedor($data)
  {
    $id=$data["IdPendientePagoProveedor"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    
    $resultado = $this->mapper->map($data,$this->PendientePagoProveedor);
    $this->db->where('IdPendientePagoProveedor', $id);
    $this->db->update('PendientePagoProveedor', $resultado);

    return $resultado;
  }

  function BorrarPendientePagoProveedor($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarPendientePagoProveedor($data);
    return $resultado;
  }

  function ObtenerPendientePagoProveedorPorIdComprobanteCompra($data)
  {
    $comprobante=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select ppp.*, m.*, p.RazonSocial FROM PendientePagoProveedor ppp
                                LEFT JOIN comprobantecompra cc ON cc.IdComprobanteCompra = ppp.IdComprobanteCompra
                                LEFT JOIN moneda m ON m.Idmoneda = cc.IdMoneda
                                LEFT JOIN persona p ON p.IdPersona = ppp.IdProveedor
                                WHERE ppp.IdComprobanteCompra = '$comprobante'
                                AND  ppp.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  // function ConsultarPendientesPagoProveedorPorIdProveedor($data)
  // {
  //   $cliente=$data["IdProveedor"];

  //   $query = $this->db->query("Select ppp.*, m.* FROM PendientePagoProveedor ppp
  //                               LEFT JOIN moneda m ON m.Idmoneda = ppp.IdMoneda
  //                               WHERE ppp.IdProveedor = '$cliente'
  //                               AND ppp.SaldoPendiente > 0
  //                               AND  ppp.IndicadorEstado = 'A'
  //                               ORDER BY ppp.FechaEmision ASC");
  //   $resultado = $query->result_array();
  //   return $resultado;
  // }

  function ConsultarPendientesPagoProveedorPorIdProveedorYFiltro($data)
  {
    $cliente=$data["IdProveedor"];
    $criterio=$data["TextoFiltro"];
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $query = $this->db->query("Select ppp.*, m.* FROM PendientePagoProveedor ppp
                                LEFT JOIN moneda m ON m.Idmoneda = ppp.IdMoneda
                                WHERE ppp.IdProveedor = '$cliente'
                                AND (ppp.SerieDocumento like '%$criterio%' or ppp.NumeroDocumento like '%$criterio%')
                                AND (ppp.FechaEmision BETWEEN '$fechainicio' AND '$fechafin')
                                AND ppp.SaldoPendiente > 0
                                AND ppp.IndicadorEstado = 'A'
                                ORDER BY ppp.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  //CONSULTAS PARA SALDO INICIAL CUENTA COBRANZA - ALTERNATIVOS
  function ObtenerPendientePagoProveedorPorIdSaldoInicialCuentaPago($data)
  {
    $comprobante=$data["IdSaldoInicialCuentaPago"];
    $query = $this->db->query("Select ppp.*, m.*, p.RazonSocial FROM PendientePagoProveedor ppp
                                LEFT JOIN saldoinicialcuentapago sicc ON sicc.IdSaldoInicialCuentaPago = ppp.IdSaldoInicialCuentaPago
                                LEFT JOIN moneda m ON m.Idmoneda = sicc.IdMoneda
                                LEFT JOIN persona p ON p.IdPersona = ppp.IdProveedor
                                WHERE ppp.IdSaldoInicialCuentaPago = '$comprobante'
                                AND  ppp.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesPagoProveedorInicialPorIdProveedor($data)
  {
    $cliente=$data["IdProveedor"];

    $query = $this->db->query("Select ppp.*, m.* FROM PendientePagoProveedor ppp
                                LEFT JOIN saldoinicialcuentacobranza sicc ON sicc.IdSaldoInicialCuentaPago = ppp.IdSaldoInicialCuentaPago
                                LEFT JOIN moneda m ON m.Idmoneda = sicc.IdMoneda
                                WHERE ppp.IdProveedor = '$cliente'
                                AND ppp.SaldoPendiente > 0
                                AND  ppp.IndicadorEstado = 'A'
                                ORDER BY ppp.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesPagoProveedorInicialPorIdProveedorYFiltro($data)
  {
    $cliente=$data["IdProveedor"];
    $criterio=$data["TextoFiltro"];
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $query = $this->db->query("Select ppp.*, m.* FROM PendientePagoProveedor ppp
                                LEFT JOIN saldoinicialcuentacobranza sicc ON sicc.IdSaldoInicialCuentaPago = ppp.IdSaldoInicialCuentaPago
                                LEFT JOIN moneda m ON m.Idmoneda = sicc.IdMoneda
                                WHERE ppp.IdProveedor = '$cliente'
                                AND (sicc.SerieDocumento like '%$criterio%' or sicc.NumeroDocumento like '%$criterio%')
                                AND (CV.FechaEmision BETWEEN '$fechainicio' AND '$fechafin')
                                AND ppp.SaldoPendiente > 0
                                AND ppp.IndicadorEstado = 'A'
                                ORDER BY ppp.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesPagoProveedorParaJSON()
  {
    $query = $this->db->query("Select * FROM PendientePagoProveedor 
                                WHERE IdComprobanteCompra IS NOT NULL
                                AND SaldoPendiente > 0
                                AND IndicadorEstado = 'A'
                                ORDER BY FechaEmision DESC, CodigoTipoDocumento, SerieDocumento, NumeroDocumento");
    $resultado = $query->result_array();
    return $resultado;
  }

}
