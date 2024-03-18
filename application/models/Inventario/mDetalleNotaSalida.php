<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleNotaSalida extends CI_Model {

  public $DetalleNotaSalida = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DetalleNotaSalida = $this->Base->Construir("DetalleNotaSalida");
  }

  function ConsultarDetallesNotaSalida($data)
  {
    $id = $data["IdNotaSalida"];

    $query = $this->db->query("Select DNS.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,M.CodigoMercaderia, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, P.NombreProducto,
                              TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, TP.CodigoTipoPrecio
                              From DetalleNotaSalida As DNS
                              Inner Join Mercaderia As M on M.IdProducto=DNS.IdProducto
                              Inner Join Producto As P on P.IdProducto=M.IdProducto
                              Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                              left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                              left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                              left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                              Where DNS.IndicadorEstado = 'A' and DNS.IdNotaSalida = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function TotalProductosVendido()
  {
    $query = $this->db->query("Select count(DISTINCT IdProducto) as total
                              From detallecomprobanteventa
                              Where IndicadorEstado = 'A' or IndicadorEstado = 'N'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDetalleNotaSalida($data)
  {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
      $data["IndicadorEstado"]=ESTADO_ACTIVO;
      $resultado = $this->mapper->map($data,$this->DetalleNotaSalida);
      $this->db->insert('DetalleNotaSalida', $resultado);
      $resultado = $this->db->insert_id();
      return($resultado);
  }

  function ActualizarDetalleNotaSalida($data)
  {
    $id=$data["IdDetalleNotaSalida"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->DetalleNotaSalida);
    $this->db->where('IdDetalleNotaSalida', $id);
    $this->db->update('DetalleNotaSalida', $resultado);
    return $resultado;
  }

  function BorrarDetalleNotaSalida($IdDetalleNotaSalida)
  {
    $this->db->where("IdDetalleNotaSalida",$IdDetalleNotaSalida);
    $this->db->delete("DetalleNotaSalida");
  }

  function BorrarDetalleNotaSalidaPorIdNotaSalida($IdNotaSalida)
  {
    $this->db->where("IdNotaSalida",$IdNotaSalida);
    $this->db->delete("DetalleNotaSalida");
  }

  function BorrarDetalleNotaSalidaPorIdNotaSalidaEstado($IdNotaSalida)
  {
    $query = $this->db->query("UPDATE DetalleNotaSalida SET IndicadorEstado = 'E'
                              Where IdNotaSalida = '$IdNotaSalida'");
    return "";
  }

 }
