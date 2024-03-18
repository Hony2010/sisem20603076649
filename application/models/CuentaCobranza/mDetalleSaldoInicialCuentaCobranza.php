<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleSaldoInicialCuentaCobranza extends CI_Model {

    public $DetalleSaldoInicialCuentaCobranza = array();

    public function __construct()
    {
            parent::__construct();
            $this->load->database();
            $this->load->model("Base");
            $this->load->library('shared');
            $this->load->library('mapper');
            $this->load->library('sesionusuario');
            $this->DetalleSaldoInicialCuentaCobranza = $this->Base->Construir("DetalleSaldoInicialCuentaCobranza");
    }

    function ConsultarDetallesSaldoInicialCuentaCobranza($data)
    {
      $id = $data["IdSaldoInicialCuentaCobranza"];

      $query = $this->db->query("Select DSICC.*,
                                  UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                                  M.CodigoMercaderia, P.NombreProducto,TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC
                                  From DetalleSaldoInicialCuentaCobranza As DSICC
                                  left Join Mercaderia As M on M.IdProducto=DSICC.IdProducto
                                  left Join Producto As P on P.IdProducto=M.IdProducto
                                  left Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                  left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                  left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                  left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                  Where DSICC.IndicadorEstado = 'A' and DSICC.IdSaldoInicialCuentaCobranza = '$id'");
      $resultado = $query->result_array();

      return $resultado;
    }

    function InsertarDetalleSaldoInicialCuentaCobranza($data)
    {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
      $data["IndicadorEstado"]=ESTADO_ACTIVO;

      $resultado = $this->mapper->map($data,$this->DetalleSaldoInicialCuentaCobranza);
      $this->db->insert('DetalleSaldoInicialCuentaCobranza', $resultado);
      $resultado = $this->db->insert_id();
      return($resultado);
    }

    function BorrarDetalleSaldoInicialCuentaCobranza($data)
    {
      $data["IndicadorEstado"]=ESTADO_ELIMINADO;
      $resultado = $this->ActualizarDetalleSaldoInicialCuentaCobranza($data);
      return $resultado;

    }

    function BorrarDetallesPorIdSaldoInicialCuentaCobranza($data)
    {
      $id=$data["IdSaldoInicialCuentaCobranza"];
      $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
      $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      $data["IndicadorEstado"]=ESTADO_ELIMINADO;
      $resultado = $this->mapper->map($data,$this->DetalleSaldoInicialCuentaCobranza);
      $this->db->where('IdSaldoInicialCuentaCobranza', $id);
      $this->db->update('DetalleSaldoInicialCuentaCobranza', $resultado);
      return $resultado;
    }

    function ActualizarDetalleSaldoInicialCuentaCobranza($data)
    {
      $id=$data["IdDetalleSaldoInicialCuentaCobranza"];
      $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
      $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

      $resultado = $this->mapper->map($data,$this->DetalleSaldoInicialCuentaCobranza);
      $this->db->where('IdDetalleSaldoInicialCuentaCobranza', $id);
      $this->db->update('DetalleSaldoInicialCuentaCobranza', $resultado);
      // $this->db->update('DetalleSaldoInicialCuentaCobranza', array('SaldoPendienteSalida' => $data["SaldoPendienteSalida"]));

      return $resultado;
    }

    function BorrarDetalleSaldoInicialCuentaCobranzaPorIdSaldoInicialCuentaCobranza($IdSaldoInicialCuentaCobranza)
    {
      $this->db->where("IdSaldoInicialCuentaCobranza",$IdSaldoInicialCuentaCobranza);
      $this->db->delete("DetalleSaldoInicialCuentaCobranza");
    }

 }
