<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMovimientoCaja extends CI_Model {

  public $MovimientoCaja = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->MovimientoCaja = $this->Base->Construir("MovimientoCaja");
  }

  function InsertarMovimientoCaja($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    if (array_key_exists("IdComprobanteCompra", $data)){
      $data["IdTurno"] = ($data["IdTurno"] == '') ? null : $data["IdTurno"];
    }

    $resultado = $this->mapper->map($data,$this->MovimientoCaja);
    $this->db->insert('MovimientoCaja', $resultado);
    $resultado["IdMovimientoCaja"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarMovimientoCaja($data)
  {
    $id=$data["IdMovimientoCaja"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    if (array_key_exists("IdComprobanteCompra", $data)){
      $data["IdTurno"] = ($data["IdTurno"] == '') ? null : $data["IdTurno"];
    }
    
    $resultado = $this->mapper->map($data,$this->MovimientoCaja);
    $this->db->where('IdMovimientoCaja', $id);
    $this->db->update('MovimientoCaja', $resultado);

    return $resultado;
  }

  function BorrarMovimientoCaja($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    // $data["IndicadorEstado"]=ESTADO_ANULADO;
    $this->ActualizarMovimientoCaja($data);
  }

  function AnularMovimientoCaja($data)
  {
    // $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $data["IndicadorEstado"]=ESTADO_ANULADO;
    $this->ActualizarMovimientoCaja($data);
  }

  function ObtenerMovimientosCajaPorComprobanteCajaParaCobranza($data)
  {
    $id=$data["IdComprobanteCaja"];
    $query = $this->db->query("Select * from MovimientoCaja
            where IdComprobanteCaja = '$id' AND (IndicadorEstado = 'A' OR IndicadorEstado = 'N')");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientosCajaPorComprobanteCaja($data)
  {
    $id=$data["IdComprobanteCaja"];
    $query = $this->db->query("Select * from MovimientoCaja
            where IdComprobanteCaja = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientosPorComprobanteVenta($data)
  {
    $comprobante=$data["IdComprobanteVenta"];
    $query = $this->db->query("Select mc.* FROM comprobanteventa cv
                                INNER JOIN movimientocaja mc ON mc.IdComprobanteVenta = cv.IdComprobanteVenta
                                WHERE mc.IndicadorEstado = 'A'
                                AND mc.IdComprobanteVenta = '$comprobante'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientosPorComprobanteCompra($data)
  {
    $comprobante=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select mc.* FROM comprobantecompra cc
                                INNER JOIN movimientocaja mc ON mc.IdComprobanteCompra = cc.IdComprobanteCompra
                                WHERE mc.IndicadorEstado = 'A'
                                AND mc.IdComprobanteCompra = '$comprobante'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosPorIdComprobanteVenta($data)
  {
      $comprobante = $data["IdComprobanteVenta"];

      $query = $this->db->query("Select * from MovimientoCaja MC
                                  LEFT JOIN ComprobanteCaja CC on CC.IdComprobanteCaja = MC.IdComprobanteCaja
                                  WHERE MC.IdComprobanteVenta = '$comprobante'
                                  AND MC.IndicadorEstado = 'A'
                                  AND CC.IndicadorEstado = 'A'");
      $resultado = $query->result_array();
      return $resultado;
  }    
    
  function ObtenerDocumentosPorIdComprobanteCompra($data)
  {
      $comprobante = $data["IdComprobanteCompra"];

      $query = $this->db->query("Select * from MovimientoCaja MC
                                  LEFT JOIN ComprobanteCaja CC on CC.IdComprobanteCaja = MC.IdComprobanteCaja
                                  WHERE MC.IdComprobanteCompra = '$comprobante'
                                  AND MC.IndicadorEstado = 'A'
                                  AND CC.IndicadorEstado = 'A'");
      $resultado = $query->result_array();
      return $resultado;
  }

  /**PARA SALDO INICIAL CUENTA COBRANZA */
  function ObtenerMovimientosPorSaldoInicialCuentaCobranza($data)
  {
    $comprobante=$data["IdSaldoInicialCuentaCobranza"];
    $query = $this->db->query("Select mc.* FROM saldoinicialcuentacobranza sicc
                                INNER JOIN movimientocaja mc ON mc.IdSaldoInicialCuentaCobranza = sicc.IdSaldoInicialCuentaCobranza
                                WHERE mc.IndicadorEstado = 'A'
                                AND mc.IdSaldoInicialCuentaCobranza = '$comprobante'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosPorIdSaldoInicialCuentaCobranza($data)
  {
      $comprobante = $data["IdSaldoInicialCuentaCobranza"];

      $query = $this->db->query("Select * from MovimientoCaja MC
                                  LEFT JOIN ComprobanteCaja CC on CC.IdComprobanteCaja = MC.IdComprobanteCaja
                                  WHERE MC.IdSaldoInicialCuentaCobranza = '$comprobante'
                                  AND MC.IndicadorEstado = 'A'
                                  AND CC.IndicadorEstado = 'A'");
      $resultado = $query->result_array();
      return $resultado;
  }  
  /**FIN PARA SALDO INICIAL CUENTA COBRANZA */

  /**PARA SALDO INICIAL CUENTA PAGO */
  function ObtenerMovimientosPorSaldoInicialCuentaPago($data)
  {
    $comprobante=$data["IdSaldoInicialCuentaPago"];
    $query = $this->db->query("Select mc.* FROM saldoinicialcuentapago sicp
                                INNER JOIN movimientocaja mc ON mc.IdSaldoInicialCuentaPago = sicp.IdSaldoInicialCuentaPago
                                WHERE mc.IndicadorEstado = 'A'
                                AND mc.IdSaldoInicialCuentaPago = '$comprobante'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosPorIdSaldoInicialCuentaPago($data)
  {
      $comprobante = $data["IdSaldoInicialCuentaPago"];

      $query = $this->db->query("Select * from MovimientoCaja MC
                                  LEFT JOIN ComprobanteCaja CC on CC.IdComprobanteCaja = MC.IdComprobanteCaja
                                  WHERE MC.IdSaldoInicialCuentaPago = '$comprobante'
                                  AND MC.IndicadorEstado = 'A'
                                  AND CC.IndicadorEstado = 'A'");
      $resultado = $query->result_array();
      return $resultado;
  }  
  /**FIN PARA SALDO INICIAL CUENTA PAGO */

  function ValidarComprobanteCajaParaVentaOCompra($data)
  {
    $comprobante= $data["IdComprobanteCaja"];

    $query = $this->db->query("Select * from MovimientoCaja MC
                                LEFT JOIN ComprobanteCaja CC on CC.IdComprobanteCaja = MC.IdComprobanteCaja
                                WHERE MC.IdComprobanteCaja = '$comprobante'
                                AND (MC.IdComprobanteVenta is not null OR MC.IdComprobanteCompra is not null) 
                                AND MC.IndicadorEstado = 'A' AND CC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientosParaCobranzaClientePorComprobanteVenta($data)
  {
    $comprobante= $data["IdComprobanteVenta"];

    $query = $this->db->query("Select MC.*
                                FROM  movimientocaja MC
                                LEFT JOIN comprobanteventa CV ON CV.IdComprobanteVenta = MC.IdComprobanteVenta
                                WHERE (	CV.IdComprobanteVenta = '$comprobante')
                                AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N' )
                                AND (CV.IndicadorEstado='A' or CV.IndicadorEstado='N')
                                ORDER BY CV.FechaEmision");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientosParaCobranzaClientePorSaldoInicialCuentaCobranza($data)
  {
    $comprobante= $data["IdSaldoInicialCuentaCobranza"];

    $query = $this->db->query("Select MC.*
                                FROM  movimientocaja MC
                                LEFT JOIN saldoinicialcuentacobranza SICC ON SICC.IdSaldoInicialCuentaCobranza = MC.IdSaldoInicialCuentaCobranza
                                WHERE (	SICC.IdSaldoInicialCuentaCobranza = '$comprobante')
                                AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N' )
                                AND (SICC.IndicadorEstado='A' or SICC.IndicadorEstado='N')
                                ORDER BY SICC.FechaEmision");
    $resultado = $query->result_array();
    return $resultado;
  }

  //PARA CUENTA PAGO
  function ObtenerMovimientosParaCobranzaProveedorPorComprobanteCompra($data)
  {
    $comprobante= $data["IdComprobanteCompra"];
    $query = $this->db->query("Select MC.*
                                FROM  movimientocaja MC
                                LEFT JOIN comprobantecompra CC ON CC.IdComprobanteCompra = MC.IdComprobanteCompra
                                WHERE (	CC.IdComprobanteCompra = '$comprobante')
                                AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N' )
                                AND (CC.IndicadorEstado='A' or CC.IndicadorEstado='N')
                                ORDER BY CC.FechaEmision");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientosParaCobranzaProveedorPorSaldoInicialCuentaPago($data)
  {
    $comprobante= $data["IdSaldoInicialCuentaPago"];
    $query = $this->db->query("Select MC.*
                                FROM movimientocaja MC
                                LEFT JOIN saldoinicialcuentacobranza SICP ON SICP.IdSaldoInicialCuentaPago = MC.IdSaldoInicialCuentaPago
                                WHERE (	SICP.IdSaldoInicialCuentaPago = '$comprobante')
                                AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N' )
                                AND (SICP.IndicadorEstado='A' or SICP.IndicadorEstado='N')
                                ORDER BY SICP.FechaEmision");
    $resultado = $query->result_array();
    return $resultado;
  }

}
