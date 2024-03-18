<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mOtraVenta extends CI_Model
{

  public $OtraVenta = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->OtraVenta = $this->Base->Construir("OtraVenta");
  }

  function ListarOtrasVenta()
  {
    $query = $this->db->query("Select OV.*, TP.IdTipoProducto, TP.NombreTipoProducto, P.* ,TAI.*, TPC.CodigoTipoPrecio, TSI.CodigoTipoSistemaISC, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                     From OtraVenta As OV
                                     Inner Join Producto As P on OV.IdProducto = P.IdProducto
                                     Inner Join TipoProducto As TP on TP.IdTipoProducto = OV.IdTipoProducto
                                     Inner join TipoAfectacionIGV As TAI on OV.IdTipoAfectacionIGV = TAI.IdTipoAfectacionIGV
                                     Inner join TipoSistemaISC As TSI on OV.IdTipoSistemaISC = TSI.IdTipoSistemaISC
                                     Inner Join UnidadMedida as UM on OV.IdUnidadMedida = UM.IdUnidadMedida
                                     Inner join TipoPrecio As TPC on OV.IdTipoPrecio = TPC.IdTipoPrecio
                                     Where P.IndicadorEstado = 'A' or P.IndicadorEstado = 'T'
                                     ORDER BY (OV.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarOtraVenta($data)
  {
    $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    $resultado = $this->mapper->map($data, $this->OtraVenta);
    $this->db->insert('OtraVenta', $resultado);
    // $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarOtraVenta($data)
  {
    $id = $data["IdProducto"];
    if (array_key_exists("IdTipoTributo", $data)) {
      $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    }
    $resultado = $this->mapper->map($data, $this->OtraVenta);
    $this->db->where('IdProducto', $id);
    $this->db->update('OtraVenta', $resultado);
  }

  function ConsultarCostosServicio($data)
  {
    $criterio = $data["textofiltro"];
    $this->db->select(" OV.IdProducto, TP.IdTipoProducto, TP.NombreTipoProducto, P.*")
      ->from('OtraVenta As OV')
      ->join('Producto As P', 'OV.IdProducto = P.IdProducto')
      ->join('TipoProducto As TP', 'TP.IdTipoProducto = OV.IdTipoProducto')
      ->where('OV.IdProducto like "%' . $criterio . '%" or P.NombreProducto like "%' . $criterio . '%" or TP.NombreTipoProducto like "%' . $criterio . '%" AND P.IndicadorEstado="A" ');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ObtenerOtraVentaEnDetalleComprobanteVenta($data)
  {
    $criterio = $data["IdProducto"];
    $query = $this->db->query("Select *
                                    From DetalleComprobanteVenta As DCV
                                    Where DCV.IndicadorEstado = 'A' and DCV.IdProducto = '$criterio'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerOtraVentaPorIdProducto($data)
  {
    $criterio = $data["IdProducto"];
    $query = $this->db->query("Select OV.*, TP.IdTipoProducto, TP.NombreTipoProducto, P.* ,TAI.*, TPC.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida, TSI.CodigoTipoSistemaISC
                                     From OtraVenta As OV
                                     Inner Join Producto As P on OV.IdProducto = P.IdProducto
                                     Inner Join TipoProducto As TP on TP.IdTipoProducto = OV.IdTipoProducto
                                     Inner join TipoAfectacionIGV As TAI on OV.IdTipoAfectacionIGV = TAI.IdTipoAfectacionIGV
                                     left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = OV.IdTipoSistemaISC
                                     Inner Join UnidadMedida as UM on OV.IdUnidadMedida = UM.IdUnidadMedida
                                     Inner join TipoPrecio As TPC on OV.IdTipoPrecio = TPC.IdTipoPrecio
                                     Where P.IndicadorEstado = 'A' and OV.IdProducto = '$criterio'
                                     ORDER BY (OV.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarOtraVentaParaJSON()
  {
    $query = $this->db->query("Select OV.*, TP.IdTipoProducto, TP.NombreTipoProducto, P.* ,TAI.*, TPC.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida, TSI.CodigoTipoSistemaISC
                From OtraVenta As OV
                Inner Join Producto As P on OV.IdProducto = P.IdProducto
                Inner Join TipoProducto As TP on TP.IdTipoProducto = OV.IdTipoProducto
                Inner join TipoAfectacionIGV As TAI on OV.IdTipoAfectacionIGV = TAI.IdTipoAfectacionIGV
                left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = OV.IdTipoSistemaISC
                Inner Join UnidadMedida as UM on OV.IdUnidadMedida = UM.IdUnidadMedida
                Inner join TipoPrecio As TPC on OV.IdTipoPrecio = TPC.IdTipoPrecio
                Where P.IndicadorEstado = 'A'
                ORDER BY (OV.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }
}
