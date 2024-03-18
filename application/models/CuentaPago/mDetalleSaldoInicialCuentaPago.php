<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleSaldoInicialCuentaPago extends CI_Model {

  public $DetalleSaldoInicialCuentaPago = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DetalleSaldoInicialCuentaPago = $this->Base->Construir("DetalleSaldoInicialCuentaPago");
  }

  function ConsultarDetallesSaldoInicialCuentaPago($data)
  {
    $id = $data["IdSaldoInicialCuentaPago"];

    $query = $this->db->query("Select DSICP.*,
                                UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                                M.CodigoMercaderia, P.NombreProducto,TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC
                                From DetalleSaldoInicialCuentaPago As DSICP
                                left Join Mercaderia As M on M.IdProducto=DSICP.IdProducto
                                left Join Producto As P on P.IdProducto=M.IdProducto
                                left Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where DSICP.IndicadorEstado = 'A' and DSICP.IdSaldoInicialCuentaPago = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function InsertarDetalleSaldoInicialCuentaPago($data)
  {
    $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;

    $resultado = $this->mapper->map($data,$this->DetalleSaldoInicialCuentaPago);
    $this->db->insert('DetalleSaldoInicialCuentaPago', $resultado);
    $resultado = $this->db->insert_id();
    return($resultado);
  }

  function BorrarDetalleSaldoInicialCuentaPago($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarDetalleSaldoInicialCuentaPago($data);
    return $resultado;

  }

  function BorrarDetallesPorIdSaldoInicialCuentaPago($data)
  {
    $id=$data["IdSaldoInicialCuentaPago"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->mapper->map($data,$this->DetalleSaldoInicialCuentaPago);
    $this->db->where('IdSaldoInicialCuentaPago', $id);
    $this->db->update('DetalleSaldoInicialCuentaPago', $resultado);
    return $resultado;
  }

  function ActualizarDetalleSaldoInicialCuentaPago($data)
  {
    $id=$data["IdDetalleSaldoInicialCuentaPago"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data,$this->DetalleSaldoInicialCuentaPago);
    $this->db->where('IdDetalleSaldoInicialCuentaPago', $id);
    $this->db->update('DetalleSaldoInicialCuentaPago', $resultado);
    // $this->db->update('DetalleSaldoInicialCuentaPago', array('SaldoPendienteSalida' => $data["SaldoPendienteSalida"]));

    return $resultado;
  }

  function BorrarDetalleSaldoInicialCuentaPagoPorIdSaldoInicialCuentaPago($IdSaldoInicialCuentaPago)
  {
    $this->db->where("IdSaldoInicialCuentaPago",$IdSaldoInicialCuentaPago);
    $this->db->delete("DetalleSaldoInicialCuentaPago");
  }

 }
