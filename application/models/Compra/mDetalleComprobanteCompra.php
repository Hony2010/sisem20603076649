<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mDetalleComprobanteCompra extends CI_Model
{

  public $DetalleComprobanteCompra = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DetalleComprobanteCompra = $this->Base->Construir("DetalleComprobanteCompra");
  }

  function ConsultarDetalleComprobanteCompraPorId($data)
  {
    $id = $data["IdDetalleComprobanteCompra"];

    $query = $this->db->query("Select DCC.*,
              UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida, M.PesoUnitario,
              M.CodigoMercaderia, M.IdOrigenMercaderia, P.NombreProducto, LP.IdLoteProducto, LP.NumeroLote, LP.FechaVencimiento
              From DetalleComprobanteCompra As DCC
              Inner Join Mercaderia As M on M.IdProducto=DCC.IdProducto
              Inner Join Producto As P on P.IdProducto=M.IdProducto
              Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
              left Join LoteProducto As LP on LP.IdLoteProducto=DCC.IdLoteProducto
              Where DCC.IndicadorEstado = 'A' and DCC.IdDetalleComprobanteCompra = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompra($data)
  {
    $id = $data["IdComprobanteCompra"];

    $query = $this->db->query("Select DCC.*,
              (CASE WHEN LENGTH(DCC.NombreUnidadMedida) = 0 
              THEN UM.NombreUnidadMedida ELSE DCC.NombreUnidadMedida END) AS NombreUnidadMedida,
              UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida, M.PesoUnitario,
              M.CodigoMercaderia, M.IdOrigenMercaderia, P.NombreProducto,P.NombreLargoProducto , LP.IdLoteProducto, LP.NumeroLote, LP.FechaVencimiento,
              M.AfectoBonificacion,TAI.CodigoTipoAfectacionIGV
              From DetalleComprobanteCompra As DCC
              Inner Join Mercaderia As M on M.IdProducto=DCC.IdProducto
              Inner Join TipoAfectacionIGV  As TAI on TAI.IdTipoAfectacionIGV=M.IdTipoAfectacionIGV
              Inner Join Producto As P on P.IdProducto=M.IdProducto
              Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
              left Join LoteProducto As LP on LP.IdLoteProducto=DCC.IdLoteProducto
              Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompraGasto($data)
  {
    $id = $data["IdComprobanteCompra"];

    $query = $this->db->query("Select DCC.*,
              '' AbreviaturaUnidadMedida, '' CodigoUnidadMedida, '' PesoUnitario,
              '' CodigoMercaderia, '' IdOrigenMercaderia, 
              P.NombreProducto, LP.IdLoteProducto, LP.NumeroLote, LP.FechaVencimiento
              From DetalleComprobanteCompra As DCC
              Inner Join Gasto As G on G.IdProducto=DCC.IdProducto
              Inner Join Producto As P on P.IdProducto=G.IdProducto
              left Join LoteProducto As LP on LP.IdLoteProducto=DCC.IdLoteProducto
              Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompraCostoAgregado($data)
  {
    $id = $data["IdComprobanteCompra"];

    $query = $this->db->query("Select DCC.*,
              '' AbreviaturaUnidadMedida, '' CodigoUnidadMedida, '' PesoUnitario,
              '' CodigoMercaderia, '' IdOrigenMercaderia, 
              P.NombreProducto, LP.IdLoteProducto, LP.NumeroLote, LP.FechaVencimiento
              From DetalleComprobanteCompra As DCC
              Inner Join CostoAgregado As CA on CA.IdProducto=DCC.IdProducto
              Inner Join Producto As P on P.IdProducto=CA.IdProducto
              left Join LoteProducto As LP on LP.IdLoteProducto=DCC.IdLoteProducto
              Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesCompraGasto($data)
  {
    $id = $data["IdComprobanteCompra"];

    $query = $this->db->query("Select DCC.* , P.NombreProducto , G.*,TAI.CodigoTipoAfectacionIGV
                                    From DetalleComprobanteCompra As DCC
                                    left Join Producto As P on P.IdProducto=DCC.IdProducto
                                    left Join Mercaderia As M on M.IdProducto=P.IdProducto
                                    left Join TipoAfectacionIGV  As TAI on TAI.IdTipoAfectacionIGV=M.IdTipoAfectacionIGV
                                    left Join Gasto As G on G.IdProducto=P.IdProducto
                                    Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesCompraCostoAgregado($data)
  {
    $id = $data["IdComprobanteCompra"];
    $query = $this->db->query("Select DCC.* , P.NombreProducto , CA.*,TAI.CodigoTipoAfectacionIGV
                                    From DetalleComprobanteCompra As DCC
                                    left Join Producto As P on P.IdProducto=DCC.IdProducto
                                    left Join Mercaderia As M on M.IdProducto=P.IdProducto
                                    left Join TipoAfectacionIGV  As TAI on TAI.IdTipoAfectacionIGV=M.IdTipoAfectacionIGV
                                    left Join CostoAgregado As CA on CA.IdProducto=P.IdProducto
                                    Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompraPorNotaSalida($data)
  {
    $id = $data["IdComprobanteCompra"];

    $query = $this->db->query("Select DCC.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,M.CodigoMercaderia, P.NombreProducto
                                     From DetalleComprobanteCompra As DCC
                                     Inner Join Mercaderia As M on M.IdProducto=DCC.IdProducto
                                     Inner Join Producto As P on P.IdProducto=M.IdProducto
                                     Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                     Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id' AND DCC.SaldoPendienteSalida > 0");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompraPorNotaEntrada($data)
  {
    $id = $data["IdComprobanteCompra"];

    $query = $this->db->query("Select DCC.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,M.CodigoMercaderia, P.NombreProducto
                                     From DetalleComprobanteCompra As DCC
                                     Inner Join Mercaderia As M on M.IdProducto=DCC.IdProducto
                                     Inner Join Producto As P on P.IdProducto=M.IdProducto
                                     Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                     Where DCC.IndicadorEstado = 'A' and DCC.IdComprobanteCompra = '$id' AND DCC.SaldoPendienteEntrada > 0");
    $resultado = $query->result_array();

    return $resultado;
  }

  function TotalProductosComprados()
  {
    $query = $this->db->query("Select count(DISTINCT IdProducto) as total
                                    From DetalleComprobanteCompra
                                    Where IndicadorEstado = 'A' or IndicadorEstado = 'N'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDetalleComprobanteCompra($data)
  {
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdLoteProducto"] = $data["IdLoteProducto"] == "" ? null : $data["IdLoteProducto"];
    $resultado = $this->mapper->map($data, $this->DetalleComprobanteCompra);
    $this->db->insert('DetalleComprobanteCompra', $resultado);
    $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function BorrarDetalleComprobanteCompra($IdDetalleComprobanteCompra)
  {
    $this->db->where("IdDetalleComprobanteCompra", $IdDetalleComprobanteCompra);
    $this->db->delete("DetalleComprobanteCompra");
  }

  function BorrarDetalleComprobanteCompraPorIdComprobanteCompra($IdComprobanteCompra)
  {
    $this->db->where("IdComprobanteCompra", $IdComprobanteCompra);
    $this->db->delete("DetalleComprobanteCompra");
  }

  function ActualizarDetalleComprobanteCompra($data)
  {
    $id = $data["IdDetalleComprobanteCompra"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    if (array_key_exists("IdLoteProducto", $data)) {
      $data["IdLoteProducto"] = $data["IdLoteProducto"] == "" ? null : $data["IdLoteProducto"];
    }
    $resultado = $this->mapper->map($data, $this->DetalleComprobanteCompra);
    $this->db->where('IdDetalleComprobanteCompra', $id);
    $this->db->update('DetalleComprobanteCompra', $resultado);
    // $this->db->update('DetalleComprobanteCompra', array('SaldoPendienteSalida' => $data["SaldoPendienteSalida"]));

    return $resultado;
  }

  function EliminarDetallesPorIdComprobanteCompra($data)
  {
    $id = $data["IdComprobanteCompra"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->mapper->map($data, $this->DetalleComprobanteCompra);
    $this->db->where('IdComprobanteCompra', $id);
    $this->db->update('DetalleComprobanteCompra', $resultado);
    return $resultado;
  }

  function TotalSaldoPendienteSalida($data)
  {
    $id = $data["IdComprobanteCompra"];
    $query = $this->db->query("select sum(SaldoPendienteSalida) Total from DetalleComprobanteCompra
                                    WHERE IdComprobanteCompra = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function TotalSaldoPendienteEntrada($data)
  {
    $id = $data["IdComprobanteCompra"];
    $query = $this->db->query("select sum(SaldoPendienteEntrada) Total from DetalleComprobanteCompra
                                    WHERE IdComprobanteCompra = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }
}
