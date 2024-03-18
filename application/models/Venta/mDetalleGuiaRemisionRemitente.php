<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleGuiaRemisionRemitente extends CI_Model {

  public $DetalleGuiaRemisionRemitente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DetalleGuiaRemisionRemitente = $this->Base->Construir("DetalleGuiaRemisionRemitente");
  }

  function ConsultarDetallesGuiaRemisionRemitente($data, $estadoSecundarioActivo = false)
  {
    $id = $data["IdGuiaRemisionRemitente"];

    $estadoSecundario = ($estadoSecundarioActivo) ? 'A' : 'N';
    $query = $this->db->query("Select DGRR.*,
                                DGRR.AbreviaturaUnidadMedida, UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                                M.CodigoMercaderia, TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC, 
                                M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, DCV.SaldoPendienteGuiaRemision,
                                LP.NumeroLote, LP.FechaVencimiento
                                From DetalleGuiaRemisionRemitente As DGRR
                                Left Join DetalleComprobanteVenta As DCV on DCV.IdDetalleComprobanteVenta = DGRR.IdDetalleComprobanteVenta
                                Inner Join Mercaderia As M on M.IdProducto = DGRR.IdProducto
                                Inner Join Producto As P on P.IdProducto = M.IdProducto
                                Inner Join UnidadMedida As UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                left Join LoteProducto As LP on LP.IdLoteProducto = DGRR.IdLoteProducto
                                Where (DGRR.IndicadorEstado = 'A' OR DGRR.IndicadorEstado = '$estadoSecundario') and DGRR.IdGuiaRemisionRemitente = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }
  
  function InsertarDetalleGuiaRemisionRemitente($data)
  {
    $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdLoteProducto"] = $data["IdLoteProducto"] == "" ? null : $data["IdLoteProducto"];
    $resultado = $this->mapper->map($data,$this->DetalleGuiaRemisionRemitente);
    $this->db->insert('DetalleGuiaRemisionRemitente', $resultado);
    $resultado = $this->db->insert_id();
    return($resultado);
  }

  function BorrarDetalleGuiaRemisionRemitente($IdDetalleGuiaRemisionRemitente)
  {
    $this->db->where("IdDetalleGuiaRemisionRemitente",$IdDetalleGuiaRemisionRemitente);
    $this->db->delete("DetalleGuiaRemisionRemitente");
  }

  function BorrarDetalleGuiaRemisionRemitentePorIdGuiaRemisionRemitente($IdGuiaRemisionRemitente)
  {
    $this->db->where("IdGuiaRemisionRemitente",$IdGuiaRemisionRemitente);
    $this->db->delete("DetalleGuiaRemisionRemitente");
  }

  function EliminarDetallesPorIdGuiaRemisionRemitente($data)
  {
    $id=$data["IdGuiaRemisionRemitente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->mapper->map($data,$this->DetalleGuiaRemisionRemitente);
    $this->db->where('IdGuiaRemisionRemitente', $id);
    $this->db->update('DetalleGuiaRemisionRemitente', $resultado);
    return $resultado;
  }

  function AnularDetallesPorIdGuiaRemisionRemitente($data)
  {
    $id=$data["IdGuiaRemisionRemitente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["IndicadorEstado"]=ESTADO_ANULADO;
    $resultado = $this->mapper->map($data,$this->DetalleGuiaRemisionRemitente);
    $this->db->where('IdGuiaRemisionRemitente', $id);
    $this->db->where('IndicadorEstado = "A"');
    $this->db->update('DetalleGuiaRemisionRemitente', $resultado);
    return $resultado;
  }

  function ActualizarDetalleGuiaRemisionRemitente($data)
  {
    $id=$data["IdDetalleGuiaRemisionRemitente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->DetalleGuiaRemisionRemitente);
    $this->db->where('IdDetalleGuiaRemisionRemitente', $id);
    $this->db->update('DetalleGuiaRemisionRemitente', $resultado);
    return $resultado;
  }

  function ConsultarDetalleGuiaRemisionRemitentePorId($data)
  {
    $id = $data["IdDetalleGuiaRemisionRemitente"];

    $query = $this->db->query("Select DGRR.*,
        UM.AbreviaturaUnidadMedida, UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida, M.PesoUnitario,
        M.CodigoMercaderia, M.IdOrigenMercaderia, LP.NumeroLote, LP.FechaVencimiento
        From DetalleGuiaRemisionRemitente As DGRR
        Inner Join Mercaderia As M on M.IdProducto = DGRR.IdProducto
        Inner Join Producto As P on P.IdProducto = M.IdProducto
        Inner Join UnidadMedida As UM on M.IdUnidadMedida = UM.IdUnidadMedida
        left Join LoteProducto As LP on LP.IdLoteProducto = DGRR.IdLoteProducto
        Where DGRR.IndicadorEstado = 'A' and DGRR.IdDetalleGuiaRemisionRemitente = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ActualizarDetalleGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data)
  {
    $id=$data["IdGuiaRemisionRemitente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    if(array_key_exists("IdLoteProducto", $data)) {
      $data["IdLoteProducto"] = $data["IdLoteProducto"] == "" ? null : $data["IdLoteProducto"];
    }

    $resultado = $this->mapper->map($data,$this->DetalleGuiaRemisionRemitente);
    $this->db->where('IdGuiaRemisionRemitente', $id);
    $this->db->update('DetalleGuiaRemisionRemitente', $resultado);
    return $resultado;
  }

}
