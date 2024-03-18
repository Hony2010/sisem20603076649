<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mVenta extends CI_Model {

  public $Venta = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    //$this->load->model("Configuracion/General/mSituacionComprobanteElectronico");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    //$this->ComprobanteVenta = $this->Base->Construir("ComprobanteVenta");
  }

  function ListarRankingClientesMayoresVentas($data) {    
    $NumeroMaximoClientesRanking = $data["NumeroMaximoClientesRanking"];
    
    $sql = "SELECT
            cv.IdCliente,
            p.RazonSocial,
            SUM(cv.Total) AS Total
            FROM comprobanteventa  cv
            LEFT JOIN cliente c
            ON c.IdPersona=cv.IdCliente
            LEFT JOIN persona p
            ON p.IdPersona=c.IdPersona
            WHERE cv.IndicadorEstado='A'
            GROUP BY cv.IdCliente
            ORDER BY SUM(cv.Total) DESC
            LIMIT $NumeroMaximoClientesRanking";

    $query = $this->db->query($sql);    
    $resultado = $query->result_array();

    return $resultado;
  }

  function ConsultarVentas($data) {
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    
    $sql="Select cv.*, CONCAT(cv.SerieDocumento , '-' , cv.NumeroDocumento) as Numero,
          p.RazonSocial, p.NumeroDocumentoIdentidad,ss.IdSede,td.CodigoTipoDocumento
          FROM comprobanteventa cv
          left join asignacionsede ss
          on cv.IdAsignacionSede = ss.IdAsignacionSede
          inner join tipodocumento td
          on td.IdTipoDocumento = cv.IdTipoDocumento
          INNER JOIN persona p ON p.IdPersona = cv.IdCliente
          WHERE cv.FechaEmision BETWEEN '$fechainicio' AND '$fechafin' AND cv.IndicadorEstado = 'A'
          and ss.IndicadorEstado='A'
          ORDER BY cv.SerieDocumento,cv.NumeroDocumento ASC";
    
    $query = $this->db->query($sql);
    
    $resultado = $query->result_array();

    return $resultado;    
  }

  function ConsultarItemsVentas($data) {
    $id = $data["IdComprobanteVenta"];

    $query = $this->db->query("Select DCV.*, M.AfectoICBPER,
                              UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida, MRC.NombreMarca,
                              M.CodigoMercaderia, TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC,
                              TSI.TasaISC, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, TT.*, LP.NumeroLote, DSZ.NumeroDocumentoSalidaZofra, D.NumeroDua, M.IdOrigenMercaderia,
                              M.PesoUnitario As Peso,
                              CASE WHEN DCV.IndicadorOperacionGratuita = '1' THEN 'true' ELSE 'false' END as EstadoOperacionGratuita                              
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
}
