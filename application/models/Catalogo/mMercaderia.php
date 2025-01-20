<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMercaderia extends CI_Model {

  public $Mercaderia = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    
    $this->Mercaderia = $this->Base->Construir("Mercaderia");
  }
  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select Count(IdProducto) As NumeroFila From Producto where IndicadorEstado = 'A' ");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarMercaderias($inicio, $ValorParametroSistema)
  {
    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                                MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
                                MDL.NombreModelo, P.*,TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                                From Mercaderia As M
                                left Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                left Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                left Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                left Join Producto as P on M.IdProducto = P.IdProducto
                                left Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                left Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                left Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                left Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                left Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                left Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                left Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where P.IndicadorEstado= 'A'
                                ORDER BY(M.IdProducto)
                                LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarMercaderia($data)
  {
    $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    $resultado = $this->mapper->map($data,$this->Mercaderia);
    $this->db->insert('Mercaderia', $resultado);
    //$resultado = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarMercaderia($data) {
    $id=$data["IdProducto"];

    if(array_key_exists("IdTipoTributo", $data)) {
      $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    }

    $resultado = $this->mapper->map($data,$this->Mercaderia);
    $this->db->where('IdProducto', $id);
    $this->db->update('Mercaderia', $resultado);
    
    return $resultado;
  }

  function ObtenerNumeroTotalMercaderias($data)
  {
    $criterio=$data["textofiltro"];

    $query = $this->db->query("Select count(M.IdProducto) as cantidad
                              From mercaderia As M
                              Inner Join Producto as P on M.IdProducto = P.IdProducto
                              Where P.IndicadorEstado= 'A' AND (P.NombreProducto like '%$criterio%' or M.CodigoMercaderia like '%$criterio%')
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalMercaderiasAvanzada($data)
  {
    $criterio=$data["textofiltro"];
    $palabras = explode(' ', $criterio);
    $filtro="";
    foreach($palabras as $key => $value) {
      $filtro =$filtro . " And (P.NombreLargoProducto like '%$value%')";
    }
    
    $query = $this->db->query("Select count(M.IdProducto) as cantidad
                              From mercaderia As M
                              Inner Join Producto as P on M.IdProducto = P.IdProducto
                              Where P.IndicadorEstado= 'A' $filtro
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderia($data, $numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["textofiltro"];    
    
    if(array_key_exists("EstadoProducto",$data)) {
      $estadoProducto = $data["EstadoProducto"];
      $filtro = " And (P.EstadoProducto like '".$estadoProducto."')";
    }
    else {
      $filtro = " And (P.EstadoProducto = '1') ";
    }

    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                                MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
                                MDL.NombreModelo, P.*,TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                                From Mercaderia As M
                                Inner Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                Inner Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                Inner Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                Inner Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                Inner Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where P.IndicadorEstado= 'A' AND (P.NombreProducto like '%$criterio%' or M.CodigoMercaderia like '%$criterio%')
                                $filtro
                                ORDER BY M.IdProducto
                                LIMIT $numerofilainicio,$numerorfilasporpagina");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderiaAvanzada($data, $numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["textofiltro"];
    $palabras = explode(' ', $criterio);
    $filtro="";
    foreach($palabras as $key => $value) {
      $filtro =$filtro . " And (P.NombreLargoProducto like '%$value%')";
    }

    if(array_key_exists("EstadoProducto",$data)) {
      $filtro = $filtro . " And (P.EstadoProducto like '".$data["EstadoProducto"]."')";
    }
    else {
      $filtro = $filtro . " And (P.EstadoProducto = '1') ";
    }

    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                                MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
                                MDL.NombreModelo, P.*,TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                                From Mercaderia As M
                                Inner Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                Inner Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                Inner Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                Inner Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                Inner Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where P.IndicadorEstado= 'A' $filtro                                
                                ORDER BY M.IdProducto
                                LIMIT $numerofilainicio,$numerorfilasporpagina");
    $resultado = $query->result_array();
    return $resultado;
  }


  function  ConsultarMercaderiaPorIdProducto($data)
  {
    $criterio=$data["IdProducto"];
    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                                MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
                                MDL.NombreModelo, P.*, TAI.CodigoTipoAfectacionIGV,TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                                From Mercaderia As M
                                left Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                left Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                left Join TipoAfectacionIGV as TAI on M.IdTipoAfectacionIGV = TAI.IdTipoAfectacionIGV
                                left Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                Where M.IdProducto = '$criterio' AND P.IndicadorEstado= 'A'
                                ORDER BY(M.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function  ConsultarMercaderiaParaJSONPorIdProducto($data)
  {
    $criterio=$data["IdProducto"];
    $query = $this->db->query("Select M.IdProducto, P.NombreProducto, P.NombreLargoProducto, M.Foto, M.CodigoMercaderia, M.PrecioUnitario,
                              M.IdUnidadMedida, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, MRC.NombreMarca,
                              M.IdTipoPrecio, M.IdOrigenMercaderia, M.IdTipoTributo, M.PesoUnitario,
                              M.CodigoMercaderia2, M.CodigoAlterno, M.Referencia, LP.NombreLineaProducto,
                              M.CostoUnitarioCompra, M.PrecioUnitarioCompra, M.IdMonedaCompra, M.FechaIngresoCompra,
                              M.TipoDescuento, M.ValorDescuento, M.AfectoICBPER, M.AfectoBonificacion,P.EstadoProducto,
                              UM.AbreviaturaUnidadMedida, TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC,
                              TP.CodigoTipoPrecio, SFP.IdFamiliaProducto, SFP.IdSubFamiliaProducto, M.ReferenciaProveedor, M.UltimoPrecio,
                              FP.NombreFamiliaProducto,M.Aplicacion,M.OtroDato,M.NumeroPiezas,UM.NombreUnidadMedida, M.PesoUnitario as Peso,M.EstadoCampoCalculo
                              From Mercaderia As M
                              Inner Join Producto as P on M.IdProducto = P.IdProducto
                              Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                              Inner Join Modelo as MDL on M.IdModelo = MDL.IdModelo
                              Inner Join Marca as MRC on MRC.IdMarca = MDL.IdMarca
                              left Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                              left Join TipoAfectacionIGV as TAI on M.IdTipoAfectacionIGV = TAI.IdTipoAfectacionIGV
                              left Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                              left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                              left Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                              left join FamiliaProducto FP on FP.IdFamiliaProducto = SFP.IdFamiliaProducto
                              Where M.IdProducto = '$criterio' AND P.IndicadorEstado= 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarSugerenciaMercaderiaPorNombreProducto($data)
  {
    $criterio=$data["textofiltro"];
    $query = $this->db->query("Select P.NombreProducto
                                From Mercaderia As M
                                Inner Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                Inner Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                Where P.IndicadorEstado= 'A' AND P.NombreProducto like '%$criterio%'
                                ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroFilaPorConsultaMercaderia($data)
  {
    $criterio=$data["textofiltro"];
    $query = $this->db->query("Select Count(M.IdProducto) as NumeroFila
                                From Mercaderia As M
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                Where P.IndicadorEstado= 'A' AND (P.NombreProducto like '%$criterio%' or M.CodigoMercaderia like '%$criterio%' or FP.NombreFamiliaProducto like '%$criterio%' or SFP.NombreSubFamiliaProducto like '%$criterio%' or MRC.NombreMarca like '%$criterio%' or MDL.NombreModelo like '%$criterio%')
                                ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCodigoMercaderiaParaInsertar($data)
  {
    $codigo=$data["CodigoMercaderia"];
    $query = $this->db->query("Select M.*
                                From Mercaderia As M
                                Inner Join Producto As P on M.IdProducto = P.IdProducto
                                Where M.CodigoMercaderia = '$codigo' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCodigoMercaderiaParaActualizar($data)
  {
    $id=$data["IdProducto"];
    $codigo=$data["CodigoMercaderia"];
    $query = $this->db->query("Select M.*
                                From Mercaderia As M
                                Inner Join Producto As P on M.IdProducto = P.IdProducto
                                Where (M.IdProducto > '$id' Or M.IdProducto < '$id' ) and M.CodigoMercaderia = '$codigo' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNombreProductoParaInsertar($data)
  {
    $codigo= json_encode($data["NombreProducto"],JSON_HEX_AMP+JSON_HEX_APOS+JSON_HEX_QUOT);
    $query = $this->db->query("Select M.*
                                From Mercaderia As M
                                Inner Join Producto As P on M.IdProducto = P.IdProducto
                                Where P.NombreProducto = '$codigo' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNombreProductoParaActualizar($data)
  {
    $id=$data["IdProducto"];
    $codigo= json_encode($data["NombreProducto"],JSON_HEX_AMP+JSON_HEX_APOS+JSON_HEX_QUOT);
    $query = $this->db->query("Select M.*
                                From Mercaderia As M
                                Inner Join Producto As P on M.IdProducto = P.IdProducto
                                Where M.IdProducto != '$id' and P.NombreProducto = '$codigo' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMercaderiaPorIdProducto($data)
  {
    $criterio=$data["IdProducto"];
    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                                MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
                                MDL.NombreModelo, P.*, TAI.CodigoTipoAfectacionIGV
                                From Mercaderia As M
                                left Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                left Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                left Join TipoAfectacionIGV as TAI on M.IdTipoAfectacionIGV = TAI.IdTipoAfectacionIGV
                                Where P.IndicadorEstado= 'A' AND M.IdProducto = '$criterio'
                                ORDER BY(M.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerMercaderiaPorCodigoMercaderia($data)
  {
    $criterio=$data["CodigoMercaderia"];
    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                                MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca, DCV.NombreProducto,
                                MDL.NombreModelo, P.*,
                                UM.NombreUnidadMedida,TAI.CodigoTipoAfectacionIGV,TP.CodigoTipoPrecio,TSI.CodigoTipoSistemaISC
                                From Mercaderia As M
                                Inner Join Fabricante as F on M.IdFabricante = F.IdFabricante
                                Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                                Inner Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                                Inner Join Producto as P on M.IdProducto = P.IdProducto
                                Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                                Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                                Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                                Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                                Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                Inner join TipoAfectacionIGV as TAI  on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                Inner join TipoPrecio as TP  on  TP.IdTipoPrecio = M.IdTipoPrecio
                                Inner join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                INNER JOIN DetalleComprobanteVenta AS DCV ON DCV.IdProducto = M.IdProducto
                                Where P.IndicadorEstado= 'A' AND M.CodigoMercaderia = '$criterio'
                                ORDER BY(M.CodigoMercaderia)");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerUltimoCodigoMercaderia()
  {
    $query = $this->db->query("select max(cast(substring(M.CodigoMercaderia , 1) AS decimal)) As MaximoValor
                              from Mercaderia As M
                              inner join producto As P on M.IdProducto = P.IdProducto
                              Where P.IndicadorEstado = 'A'
                              and length(M.CodigoMercaderia) <= ".LONGITUD_MAXIMA_CODIGO_AUTOMATICO_MERCADERIA."
                              and cast(substring(M.CodigoMercaderia , 1) AS decimal) > 0");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerUltimoCodigoMercaderiaAlternativo()
  {
    $query = $this->db->query("select max(cast(substring(M.CodigoCorrelativoAutomatico , 1) AS decimal)) As MaximoValor
                              from Mercaderia As M
                              inner join producto As P on M.IdProducto = P.IdProducto
                              Where P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderiaParaJSON()
  {
    $query = $this->db->query("Select
                              M.IdProducto, P.NombreProducto, P.NombreLargoProducto, M.CodigoMercaderia, M.IdOrigenMercaderia, 
                              SFP.IdFamiliaProducto, SFP.IdSubFamiliaProducto, P.EstadoProducto,
                              MRC.NombreMarca, M.PrecioUnitario, FP.NombreFamiliaProducto,
                              M.CodigoMercaderia2, M.CodigoAlterno, M.Referencia, LP.NombreLineaProducto, M.Aplicacion,
                              M.CostoUnitarioCompra, M.PrecioUnitarioCompra, M.IdMonedaCompra, M.FechaIngresoCompra, M.ReferenciaProveedor, M.UltimoPrecio,
                              M.PesoUnitario As Peso
                              From Mercaderia As M
                              Inner Join Producto as P on M.IdProducto = P.IdProducto
                              left Join Modelo as MDL on M.IdModelo = MDL.IdModelo
                              left Join Marca as MRC on MRC.IdMarca = MDL.IdMarca
                              left Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                              left Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                              left Join FamiliaProducto As FP on FP.IdFamiliaProducto = SFP.IdFamiliaProducto
                              Where P.IndicadorEstado= 'A'
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderiaParaJSONAvanzado($data)
  {
    $texto = $data["textofiltro"];
    $filtros = explode(' ', $texto);
    $consulta = "";
    foreach ($filtros as $key => $value) {
      $consulta .= " AND P.NombreLargoProducto LIKE '%$value%' ";
    }

    $consultacompleta = "Select * From Mercaderia As M
                        Inner Join Producto as P on M.IdProducto = P.IdProducto
                        Where P.IndicadorEstado= 'A' $consulta
                        ORDER BY(M.IdProducto)";
    // print_r($consultacompleta);exit;
    $query = $this->db->query($consultacompleta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderiaEnVentasJSON($data)
  {
    $codigo = $data['CodigoMercaderia'];
    // $nombreproducto = $data['NombreProducto'];
    $query = $this->db->query("Select * FROM producto p
                                INNER JOIN mercaderia m ON m.IdProducto = p.IdProducto
                                WHERE m.CodigoMercaderia = '$codigo' AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarNombreProductoEnVentasJSON($data)
  {
    // $codigo = $data['CodigoMercaderia'];
    $nombreproducto = $data['NombreProducto'];
    $query = $this->db->query("Select * FROM producto p
                                INNER JOIN mercaderia m ON m.IdProducto = p.IdProducto
                                WHERE p.NombreProducto = '$nombreproducto' AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarProductoEnVentasJSONParaImportacion($data)
  {
    // $codigo = $data['CodigoMercaderia'];
    $nombreproducto = $data['NombreProducto'];
    $query = $this->db->query("Select * FROM producto p
                                INNER JOIN mercaderia m ON m.IdProducto = p.IdProducto
                                WHERE p.NombreProducto = '$nombreproducto'
                                AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarMercaderiaPorIdProductoParaJSON($data)
  {
    $idproducto=$data["IdProducto"];
    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
              MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
              MDL.NombreModelo, P.*,TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
              From Mercaderia As M
              Inner Join Fabricante as F on M.IdFabricante = F.IdFabricante
              Inner Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
              Inner Join Moneda As MND on M.IdMoneda = MND.IdMoneda
              Inner Join Producto as P on M.IdProducto = P.IdProducto
              Inner Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
              Inner Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
              Inner Join Modelo As MDL on M.IdModelo = MDL.IdModelo
              Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
              Inner Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
              Inner Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
              Inner Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
              Inner Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
              Inner Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
              Where M.IdProducto = '$idproducto' AND P.IndicadorEstado= 'A'
              ORDER BY(M.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarMercaderiasEnVentasJSON($data)
  {
    $query = $this->db->query("Select M.*, F.NombreFabricante,TE.NombreTipoExistencia, UM.AbreviaturaUnidadMedida,
                              MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, SFP.NombreSubFamiliaProducto, LP.NombreLineaProducto, MRC.NombreMarca, MRC.IdMarca,
                              MDL.NombreModelo, P.*,TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                              From Mercaderia As M
                              left Join Fabricante as F on M.IdFabricante = F.IdFabricante
                              left Join TipoExistencia as TE on M.IdTipoExistencia = TE.IdTipoExistencia
                              left Join Moneda As MND on M.IdMoneda = MND.IdMoneda
                              left Join Producto as P on M.IdProducto = P.IdProducto
                              left Join SubFamiliaProducto As SFP on M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto
                              left Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
                              left Join Modelo As MDL on M.IdModelo = MDL.IdModelo
                              left Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                              left Join LineaProducto As LP on LP.IdLineaProducto = M.IdLineaProducto
                              left Join UnidadMedida as UM on M.IdUnidadMedida = UM.IdUnidadMedida
                              left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                              left Join TipoSistemaISC as TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                              left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                              WHERE p.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderias($data) {    
    $SubFamiliaProducto = $data["IdSubFamiliaProducto"];
    $Modelo = $data["IdModelo"];
    $Marca = $data["IdMarca"];
    $FamiliaProducto = $data["IdFamiliaProducto"];
    $LineaProducto = $data["IdLineaProducto"];
    $Descripcion = $data["Descripcion"];
      
    if(array_key_exists("EstadoProducto",$data)) {
      $estadoProducto = $data["EstadoProducto"];
      $filtro = " And (P.EstadoProducto like '".$estadoProducto."')";
    }
    else {
      $filtro = " And (P.EstadoProducto = '1') ";
    }

    $query = $this->db->query("Select SFP.NombreSubFamiliaProducto, FP.NombreFamiliaProducto, MC.NombreMarca, ML.NombreModelo,
                              P.NombreProducto, UM.AbreviaturaUnidadMedida,M.*
                              From mercaderia As M
                              Inner Join unidadmedida as UM on UM.IdUnidadMedida = M.IdUnidadMedida
                              Inner Join producto As P on M.IdProducto = P.IdProducto                              
                              left join SubFamiliaProducto SFP on SFP.IdSubFamiliaProducto = M.IdSubFamiliaProducto
                              left join FamiliaProducto FP on FP.IdFamiliaProducto = SFP.IdFamiliaProducto
                              left join Modelo ML on ML.IdModelo = M.IdModelo
                              left join Marca MC on MC.IdMarca = ML.IdMarca
                              Where P.IndicadorEstado = 'A'
                              And M.IdSubFamiliaProducto like '$SubFamiliaProducto'
                              And M.IdModelo like '$Modelo'
                              And MC.IdMarca like '$Marca'
                              And FP.IdFamiliaProducto like '$FamiliaProducto'
                              And M.IdLineaProducto like '$LineaProducto'
                              And P.NombreProducto like '$Descripcion'
                              $filtro
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }


}
