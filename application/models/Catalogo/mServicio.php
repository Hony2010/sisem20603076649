<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mServicio extends CI_Model
{

  public $Servicio = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->Servicio = $this->Base->Construir("Servicio");
  }

  function ObtenerNumeroTotalServicios($data)
  {
    $criterio=$data["textofiltro"];

    $query = $this->db->query("Select count(S.IdProducto) as cantidad
                              From servicio As S
                              Inner Join Producto as P on S.IdProducto = P.IdProducto
                              Where P.IndicadorEstado= 'A' AND (P.NombreProducto like '%$criterio%' or S.CodigoServicio like '%$criterio%')
                              ORDER BY(S.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalServiciosAvanzada($data)
  {
    $criterio=$data["textofiltro"];
    $palabras = explode(' ', $criterio);
    $filtro="";
    foreach($palabras as $key => $value) {
      $filtro =$filtro . " And (P.NombreLargoProducto like '%$value%')";
    }
    
    $query = $this->db->query("Select count(S.IdProducto) as cantidad
                              From servicio As S
                              Inner Join Producto as P on S.IdProducto = P.IdProducto
                              Where P.IndicadorEstado= 'A' $filtro
                              ORDER BY(S.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select Count(IdProducto) as NumeroFila From Servicio");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarServicios($inicio, $ValorParametroSistema)
  {
    $query = $this->db->query("Select S.*, LP.NombreLineaProducto, SFP.NombreSubFamiliaProducto, TS.NombreTipoServicio, P.*, SFP.IdFamiliaProducto,
                                    TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                    From Servicio As S
                                    Inner Join Producto as P on S.IdProducto = P.IdProducto
                                    Inner Join LineaProducto As LP on S.IdLineaProducto = LP.IdLineaProducto
                                    Inner Join SubFamiliaProducto As SFP on S.IdSubFamiliaProducto =SFP.IdSubFamiliaProducto
                                    Inner Join TipoServicio As TS on TS.IdTipoServicio = S.IdTipoServicio
                                    Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
                                    Inner Join TipoPrecio As TP on TP.IdTipoPrecio = S.IdTipoPrecio
                                    Inner Join UnidadMedida As UM on UM.IdUnidadMedida = S.IdUnidadMedida
                                    Where P.IndicadorEstado = 'A'
                                    ORDER BY(S.IdProducto)
                                    LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarServicio($data)
  {
    $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    $resultado = $this->mapper->map($data, $this->Servicio);
    $this->db->insert('Servicio', $resultado);
    // $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarServicio($data)
  {
    $id = $data["IdProducto"];
    if (array_key_exists("IdTipoTributo", $data)) {
      $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    }
    $resultado = $this->mapper->map($data, $this->Servicio);
    $this->db->where('IdProducto', $id);
    $this->db->update('Servicio', $resultado);
  }

  function ConsultarServicio($data, $numerofilainicio,$numerorfilasporpagina)//$inicio, $ValorParametroSistema, $data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select S.*, LP.NombreLineaProducto, SFP.NombreSubFamiliaProducto, TS.NombreTipoServicio, P.*, SFP.IdFamiliaProducto,
                                    TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                    From Servicio As S
                                    Inner Join Producto as P on S.IdProducto = P.IdProducto
                                    Inner Join LineaProducto As LP on S.IdLineaProducto = LP.IdLineaProducto
                                    Inner Join SubFamiliaProducto As SFP on S.IdSubFamiliaProducto =SFP.IdSubFamiliaProducto
                                    Inner Join TipoServicio As TS on TS.IdTipoServicio = S.IdTipoServicio
                                    Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
                                    Inner Join TipoPrecio As TP on TP.IdTipoPrecio = S.IdTipoPrecio
                                    Inner Join UnidadMedida As UM on UM.IdUnidadMedida = S.IdUnidadMedida
                                    Where P.IndicadorEstado = 'A' AND (S.CodigoServicio like '%$criterio%' or P.NombreProducto like '%$criterio%' or TS.NombreTipoServicio like '%$criterio%')
                                    ORDER BY(S.IdProducto)
                                    LIMIT $numerofilainicio,$numerorfilasporpagina");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroFilaPorConsultaServicio($data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select S.*, LP.NombreLineaProducto, SFP.NombreSubFamiliaProducto, TS.NombreTipoServicio, P.*, SFP.IdFamiliaProducto,
                                    TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                    From Servicio As S
                                    Inner Join Producto as P on S.IdProducto = P.IdProducto
                                    Inner Join LineaProducto As LP on S.IdLineaProducto = LP.IdLineaProducto
                                    Inner Join SubFamiliaProducto As SFP on S.IdSubFamiliaProducto =SFP.IdSubFamiliaProducto
                                    Inner Join TipoServicio As TS on TS.IdTipoServicio = S.IdTipoServicio
                                    Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
                                    Inner Join TipoPrecio As TP on TP.IdTipoPrecio = S.IdTipoPrecio
                                    Inner Join UnidadMedida As UM on UM.IdUnidadMedida = S.IdUnidadMedida
                                    Where S.CodigoServicio like '%$criterio%' or P.IndicadorEstado = 'A' AND  P.NombreProducto like '%$criterio%' or TS.NombreTipoServicio like '%$criterio%'
                                    ORDER BY(S.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCodigoServicioParaInsertar($data)
  {
    $codigo = $data["CodigoServicio"];
    $query = $this->db->query("Select S.*
                                     From Servicio As S
                                     Inner Join Producto As P on S.IdProducto = P.IdProducto
                                     Where S.CodigoServicio = '$codigo' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCodigoServicioParaActualizar($data)
  {
    $id = $data["IdProducto"];
    $codigo = $data["CodigoServicio"];
    $query = $this->db->query("Select S.*
                                     From Servicio As S
                                     Inner Join Producto As P on S.IdProducto = P.IdProducto
                                     Where (S.IdProducto > '$id' Or S.IdProducto < '$id' ) and S.CodigoServicio = '$codigo'  and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerServicioPorIdProducto($data)
  {
    $criterio = $data["IdProducto"];
    $query = $this->db->query("Select S.*, LP.NombreLineaProducto, SFP.NombreSubFamiliaProducto, TS.NombreTipoServicio, P.*, SFP.IdFamiliaProducto,
                                    TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida, TSI.CodigoTipoSistemaISC
                                    From Servicio As S
                                    Inner Join Producto as P on S.IdProducto = P.IdProducto
                                    Inner Join LineaProducto As LP on S.IdLineaProducto = LP.IdLineaProducto
                                    Inner Join SubFamiliaProducto As SFP on S.IdSubFamiliaProducto =SFP.IdSubFamiliaProducto
                                    Inner Join TipoServicio As TS on TS.IdTipoServicio = S.IdTipoServicio
                                    Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
                                    Inner Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = S.IdTipoSistemaISC
                                    Inner Join TipoPrecio As TP on TP.IdTipoPrecio = S.IdTipoPrecio
                                    Inner Join UnidadMedida As UM on UM.IdUnidadMedida = S.IdUnidadMedida
                                    Where S.IdProducto = '$criterio' AND P.IndicadorEstado= 'A'
                                    ORDER BY(S.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerServicioPorCodigoServicio($data)
  {
    $criterio = $data["CodigoServicio"];
    $query = $this->db->query("Select S.*, LP.NombreLineaProducto, SFP.NombreSubFamiliaProducto, TS.NombreTipoServicio, P.*, SFP.IdFamiliaProducto,
                                    TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                    From Servicio As S
                                    Inner Join Producto as P on S.IdProducto = P.IdProducto
                                    Inner Join LineaProducto As LP on S.IdLineaProducto = LP.IdLineaProducto
                                    Inner Join SubFamiliaProducto As SFP on S.IdSubFamiliaProducto =SFP.IdSubFamiliaProducto
                                    Inner Join TipoServicio As TS on TS.IdTipoServicio = S.IdTipoServicio
                                    Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
                                    Inner Join TipoPrecio As TP on TP.IdTipoPrecio = S.IdTipoPrecio
                                    Inner Join UnidadMedida As UM on UM.IdUnidadMedida = S.IdUnidadMedida
                                    Where S.CodigoServicio = '$criterio' AND P.IndicadorEstado= 'A'
                                    ORDER BY(S.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarServicioParaJSON()
  {
    $query = $this->db->query("Select S.*, LP.NombreLineaProducto, SFP.NombreSubFamiliaProducto, TS.NombreTipoServicio, P.*, SFP.IdFamiliaProducto,
              TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida, TSI.CodigoTipoSistemaISC
              From Servicio As S
              Inner Join Producto as P on S.IdProducto = P.IdProducto
              Inner Join LineaProducto As LP on S.IdLineaProducto = LP.IdLineaProducto
              Inner Join SubFamiliaProducto As SFP on S.IdSubFamiliaProducto =SFP.IdSubFamiliaProducto
              Inner Join TipoServicio As TS on TS.IdTipoServicio = S.IdTipoServicio
              Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = S.IdTipoAfectacionIGV
              Inner Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = S.IdTipoSistemaISC
              Inner Join TipoPrecio As TP on TP.IdTipoPrecio = S.IdTipoPrecio
              Inner Join UnidadMedida As UM on UM.IdUnidadMedida = S.IdUnidadMedida
              Where P.IndicadorEstado= 'A'
              ORDER BY(S.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerUltimoCodigoServicio()
  {
    $query = $this->db->query("select max(cast(substring(S.CodigoServicio , 1) AS decimal)) As MaximoValor
                                    from servicio As S
                                    inner join producto As P on S.IdProducto = P.IdProducto
                                    Where P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }
}
