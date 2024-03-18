<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleComprobanteVenta extends CI_Model {

  public $DetalleComprobanteVenta = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DetalleComprobanteVenta = $this->Base->Construir("DetalleComprobanteVenta");
  }

  function ConsultarDetallesComprobanteVenta($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,
                                UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                                M.CodigoMercaderia, TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC
                                From DetalleComprobanteVenta As DCV
                                Inner Join Producto As P on P.IdProducto=DCV.IdProducto
                                left Join Mercaderia As M on M.IdProducto=P.IdProducto
                                left Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteVentaPorMercaderia($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*, M.AfectoICBPER,M.AfectoBonificacion,
                              UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida, MRC.NombreMarca,
                              M.CodigoMercaderia, TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC,
                              TSI.TasaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, TT.*, LP.NumeroLote, DSZ.NumeroDocumentoSalidaZofra, D.NumeroDua, M.IdOrigenMercaderia,
                              M.PesoUnitario As Peso,
                              CASE WHEN DCV.IndicadorOperacionGratuita = '1' THEN 'true' ELSE 'false' END as EstadoOperacionGratuita,
                              M.EstadoCampoCalculo                          
                              From detallecomprobanteventa As DCV
                              left Join Mercaderia As M on M.IdProducto=DCV.IdProducto
                              left Join Producto As P on P.IdProducto=M.IdProducto
                              Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                              left Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                              left Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                              left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                              left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                              left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                              left Join TipoTributo as TT on TT.IdTipoTributo = DCV.IdTipoTributo
                              left Join loteproducto as LP on DCV.IdLoteProducto = LP.IdLoteProducto
                              left Join documentosalidazofraproducto as DSZP on DCV.IdDocumentoSalidaZofraProducto = DSZP.IdDocumentoSalidaZofraProducto
                              left Join documentosalidazofra as DSZ on DSZP.IdDocumentoSalidaZofra = DSZ.IdDocumentoSalidaZofra
                              left Join duaproducto as DP on DCV.IdDuaProducto = DP.IdDuaProducto
                              left Join dua as D on D.IdDua = DP.IdDua
                              Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteVentaPorServicio($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,
              UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
              S.CodigoServicio AS CodigoMercaderia, TP.CodigoTipoPrecio,
              TSI.CodigoTipoSistemaISC,TSI.TasaISC, TT.*, LP.NumeroLote, DSZ.NumeroDocumentoSalidaZofra, D.NumeroDua,
              CASE WHEN DCV.IndicadorOperacionGratuita = '1' THEN 'true' ELSE 'false' END as EstadoOperacionGratuita,
              '0' AS EstadoCampoCalculo, '' as NombreMarca
              From DetalleComprobanteVenta As DCV
              Inner Join Servicio As S on S.IdProducto=DCV.IdProducto
              Inner Join Producto As P on P.IdProducto=S.IdProducto
              Inner Join UnidadMedida As UM on S.IdUnidadMedida=UM.IdUnidadMedida
              left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = S.IdTipoSistemaISC
              left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
              left Join TipoPrecio as TP on TP.IdTipoPrecio = S.IdTipoPrecio
              left Join TipoTributo as TT on TT.IdTipoTributo = DCV.IdTipoTributo
              left Join loteproducto as LP on DCV.IdLoteProducto = LP.IdLoteProducto
              left Join documentosalidazofraproducto as DSZP on DCV.IdDocumentoSalidaZofraProducto = DSZP.IdDocumentoSalidaZofraProducto
              left Join documentosalidazofra as DSZ on DSZP.IdDocumentoSalidaZofra = DSZ.IdDocumentoSalidaZofra
              left Join duaproducto as DP on DCV.IdDuaProducto = DP.IdDuaProducto
              left Join dua as D on D.IdDua = DP.IdDua
              Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteVentaPorActivoFijo($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,
            UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
            AF.CodigoActivoFijo AS CodigoMercaderia, TP.CodigoTipoPrecio,
            TSI.CodigoTipoSistemaISC,TSI.TasaISC, TT.*, LP.NumeroLote, DSZ.NumeroDocumentoSalidaZofra, D.NumeroDua,
            CASE WHEN DCV.IndicadorOperacionGratuita = '1' THEN 'true' ELSE 'false' END as EstadoOperacionGratuita,
            '0' AS EstadoCampoCalculo
            From DetalleComprobanteVenta As DCV
            Inner Join ActivoFijo As AF on AF.IdProducto=DCV.IdProducto
            Inner Join Producto As P on P.IdProducto=AF.IdProducto
            Inner Join UnidadMedida As UM on AF.IdUnidadMedida=UM.IdUnidadMedida
            left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = AF.IdTipoSistemaISC
            left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
            left Join TipoPrecio as TP on TP.IdTipoPrecio = AF.IdTipoPrecio
            left Join TipoTributo as TT on TT.IdTipoTributo = DCV.IdTipoTributo
            left Join loteproducto as LP on DCV.IdLoteProducto = LP.IdLoteProducto
            left Join documentosalidazofraproducto as DSZP on DCV.IdDocumentoSalidaZofraProducto = DSZP.IdDocumentoSalidaZofraProducto
            left Join documentosalidazofra as DSZ on DSZP.IdDocumentoSalidaZofra = DSZ.IdDocumentoSalidaZofra
            left Join duaproducto as DP on DCV.IdDuaProducto = DP.IdDuaProducto
            left Join dua as D on D.IdDua = DP.IdDua
            Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteVentaOtraVenta($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,
                        UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                        OV.CodigoOtraVenta as CodigoMercaderia, TP.CodigoTipoPrecio,
                        TSI.CodigoTipoSistemaISC,TSI.TasaISC, TT.*, LP.NumeroLote, DSZ.NumeroDocumentoSalidaZofra, D.NumeroDua,
                        CASE WHEN DCV.IndicadorOperacionGratuita = '1' THEN 'true' ELSE 'false' END as EstadoOperacionGratuita,
                        '0' AS EstadoCampoCalculo
                        From DetalleComprobanteVenta As DCV
                        Inner Join OtraVenta As OV on OV.IdProducto=DCV.IdProducto
                        Inner Join Producto As P on P.IdProducto=OV.IdProducto
                        Inner Join UnidadMedida As UM on OV.IdUnidadMedida=UM.IdUnidadMedida
                        left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = OV.IdTipoSistemaISC
                        left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = OV.IdTipoAfectacionIGV
                        left Join TipoPrecio as TP on TP.IdTipoPrecio = OV.IdTipoPrecio
                        left Join TipoTributo as TT on TT.IdTipoTributo = DCV.IdTipoTributo
                        left Join loteproducto as LP on DCV.IdLoteProducto = LP.IdLoteProducto
                        left Join documentosalidazofraproducto as DSZP on DCV.IdDocumentoSalidaZofraProducto = DSZP.IdDocumentoSalidaZofraProducto
                        left Join documentosalidazofra as DSZ on DSZP.IdDocumentoSalidaZofra = DSZ.IdDocumentoSalidaZofra
                        left Join duaproducto as DP on DCV.IdDuaProducto = DP.IdDuaProducto
                        left Join dua as D on D.IdDua = DP.IdDua
                        Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteVentaPorNotaSalida($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,M.CodigoMercaderia, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, P.NombreProducto,
                                TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, TP.CodigoTipoPrecio
                                From DetalleComprobanteVenta As DCV
                                Inner Join Mercaderia As M on M.IdProducto=DCV.IdProducto
                                Inner Join Producto As P on P.IdProducto=M.IdProducto
                                Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id' AND DCV.SaldoPendienteSalida > 0");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarDetallesComprobanteVentaPorNotaEntrada($data)
  {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,M.CodigoMercaderia, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, P.NombreProducto,
                                TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, TP.CodigoTipoPrecio
                                From DetalleComprobanteVenta As DCV
                                Inner Join Mercaderia As M on M.IdProducto=DCV.IdProducto
                                Inner Join Producto As P on P.IdProducto=M.IdProducto
                                Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where DCV.IndicadorEstado = 'A' and DCV.IdComprobanteVenta = '$id' AND DCV.SaldoPendienteEntrada > 0");
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

  function InsertarDetalleComprobanteVenta($data)
  {
    $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdLoteProducto"] = $data["IdLoteProducto"] == "" ? null : $data["IdLoteProducto"];

    if(array_key_exists("IdDuaProducto", $data)) {
      $data["IdDuaProducto"] = $data["IdDuaProducto"] == "" ? null : $data["IdDuaProducto"];
    }

    if(array_key_exists("IdDocumentoSalidaZofraProducto", $data)) {
      $data["IdDocumentoSalidaZofraProducto"] = $data["IdDocumentoSalidaZofraProducto"] == "" ? null : $data["IdDocumentoSalidaZofraProducto"];
    }

    if(array_key_exists("IdTipoTributo", $data)) {
      $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    }

    if(array_key_exists("IdPeriodoCuota", $data)) {
      $data["IdPeriodoCuota"] = $data["IdPeriodoCuota"] == "" ? null : $data["IdPeriodoCuota"];
    }

    $resultado = $this->mapper->map($data,$this->DetalleComprobanteVenta);
    $this->db->insert('DetalleComprobanteVenta', $resultado);
    $resultado = $this->db->insert_id();
    return($resultado);
  }

  function BorrarDetalleComprobanteVenta($IdDetalleComprobanteVenta)
  {
    $this->db->where("IdDetalleComprobanteVenta",$IdDetalleComprobanteVenta);
    $this->db->delete("DetalleComprobanteVenta");
  }

  function BorrarDetalleComprobanteVentaPorIdComprobanteVenta($IdComprobanteVenta)
  {
    $this->db->where("IdComprobanteVenta",$IdComprobanteVenta);
    $this->db->delete("DetalleComprobanteVenta");
  }

  function EliminarDetallesPorIdComprobanteVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->mapper->map($data,$this->DetalleComprobanteVenta);
    $this->db->where('IdComprobanteVenta', $id);
    $this->db->update('DetalleComprobanteVenta', $resultado);
    return $resultado;
  }

  function ActualizarDetalleComprobanteVenta($data)
  {
    $id=$data["IdDetalleComprobanteVenta"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

    if(array_key_exists("IdLoteProducto", $data)) {
      $data["IdLoteProducto"] = $data["IdLoteProducto"] == "" ? null : $data["IdLoteProducto"];
    }

    if(array_key_exists("IdDuaProducto", $data)) {
      $data["IdDuaProducto"] = $data["IdDuaProducto"] == "" ? null : $data["IdDuaProducto"];
    }

    if(array_key_exists("IdDocumentoSalidaZofraProducto", $data)) {
      $data["IdDocumentoSalidaZofraProducto"] = $data["IdDocumentoSalidaZofraProducto"] == "" ? null : $data["IdDocumentoSalidaZofraProducto"];
    }

    if(array_key_exists("IdTipoTributo", $data)) {
      $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    }

    if(array_key_exists("IdPeriodoCuota", $data)) {
      $data["IdPeriodoCuota"] = $data["IdPeriodoCuota"] == "" ? null : $data["IdPeriodoCuota"];
    }
    
    $resultado = $this->mapper->map($data,$this->DetalleComprobanteVenta);
    $this->db->where('IdDetalleComprobanteVenta', $id);
    $this->db->update('DetalleComprobanteVenta', $resultado);
    // $this->db->update('DetalleComprobanteVenta', array('SaldoPendienteSalida' => $data["SaldoPendienteSalida"]));

    return $resultado;
  }

  function TotalSaldoPendienteSalida($data)
  {
    $id = $data["IdComprobanteVenta"];
    $query = $this->db->query("select sum(SaldoPendienteSalida) Total from DetalleComprobanteVenta
                              WHERE IdComprobanteVenta = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function TotalSaldoPendienteEntrada($data)
  {
    $id = $data["IdComprobanteVenta"];
    $query = $this->db->query("select sum(SaldoPendienteEntrada) Total from DetalleComprobanteVenta
                              WHERE IdComprobanteVenta = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarDetalleComprobanteVentaPorId($data)
  {
    $id = $data["IdDetalleComprobanteVenta"];

    $query = $this->db->query("Select DCV.*,
        UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida, M.PesoUnitario,
        M.CodigoMercaderia, M.IdOrigenMercaderia, DCV.NombreProducto, LP.IdLoteProducto, LP.NumeroLote, LP.FechaVencimiento
        From DetalleComprobanteVenta As DCV
        left Join Mercaderia As M on M.IdProducto=DCV.IdProducto
        left Join Producto As P on P.IdProducto=M.IdProducto
        left Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
        left Join LoteProducto As LP on LP.IdLoteProducto=DCV.IdLoteProducto
        Where DCV.IndicadorEstado = 'A' and DCV.IdDetalleComprobanteVenta = '$id'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ActualizarDetalleComprobanteVentaPorIdComprobanteVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data,$this->DetalleComprobanteVenta);
    $this->db->where('IdComprobanteVenta', $id);
    $this->db->update('DetalleComprobanteVenta', $resultado);

    return $resultado;
  }

  function ConsultarSaldosPorDetallesPreCuenta($data)
  {
    $id=$data["IdComprobanteVenta"];

    $query = $this->db->query("Select SUM(dcv.SaldoPendientePreVenta) as Total
                                FROM detallecomprobanteventa dcv
                                WHERE dcv.IdComprobanteVenta = '$id'
                                AND dcv.IndicadorEstado = 'A'");

    $resultado = $query->result_array();
    return $resultado;
  }


}
