<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mActivoFijo extends CI_Model
{

  public $ActivoFijo = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->ActivoFijo = $this->Base->Construir("ActivoFijo");
  }

  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select Count(IdProducto) as NumeroFila From ActivoFijo");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarActivosFijo($inicio, $ValorParametroSistema)
  {
    $query = $this->db->query("Select AF.*, TA.NombreTipoActivo, MRC.NombreMarca, MDL.NombreModelo, P.*,
                                     TA.IdTipoActivo,MRC.IdMarca, TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Inner Join TipoActivo As TA on AF.IdTipoActivo = TA.IdTipoActivo
                                     Inner Join Modelo As MDL on AF.IdModelo = MDL.IdModelo
                                     Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                     Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
                                     Inner Join TipoPrecio As TP on TP.IdTipoPrecio = AF.IdTipoPrecio
                                     Inner Join UnidadMedida As UM on UM.IdUnidadMedida = AF.IdUnidadMedida
                                     Where P.IndicadorEstado = 'A'
                                     ORDER BY (AF.IdProducto)
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarActivoFijo($data)
  {
    $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    $resultado = $this->mapper->map($data, $this->ActivoFijo);
    $this->db->insert('ActivoFijo', $resultado);
    // $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarActivoFijo($data)
  {
    $id = $data["IdProducto"];
    if (array_key_exists("IdTipoTributo", $data)) {
      $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
    }
    $resultado = $this->mapper->map($data, $this->ActivoFijo);
    $this->db->where('IdProducto', $id);
    $this->db->update('ActivoFijo', $resultado);
  }

  function ConsultarActivoFijo($inicio, $ValorParametroSistema, $data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select AF.*, TA.NombreTipoActivo, MRC.NombreMarca, MDL.NombreModelo, P.*,
                                     TA.IdTipoActivo,MRC.IdMarca, TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Inner Join TipoActivo As TA on AF.IdTipoActivo = TA.IdTipoActivo
                                     Inner Join Modelo As MDL on AF.IdModelo = MDL.IdModelo
                                     Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                     Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
                                     Inner Join TipoPrecio As TP on TP.IdTipoPrecio = AF.IdTipoPrecio
                                     Inner Join UnidadMedida As UM on UM.IdUnidadMedida = AF.IdUnidadMedida
                                     Where P.IndicadorEstado = 'A' AND AF.CodigoActivoFijo like '%$criterio%' or P.NombreProducto like '%$criterio%' or MRC.NombreMarca like '%$criterio%' or MDL.NombreModelo like '%$criterio%' or
                                           AF.Placa like '%$criterio%' or AF.NumeroSerie like '%$criterio%' or AF.Ano like '%$criterio%' or AF.Color like '%$criterio%'
                                     ORDER BY(AF.IdProducto)
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroFilaPorConsultaActivoFijo($inicio, $ValorParametroSistema, $data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select AF.*, TA.NombreTipoActivo, MRC.NombreMarca, MDL.NombreModelo, P.*,
                                     TA.IdTipoActivo,MRC.IdMarca, TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Inner Join TipoActivo As TA on AF.IdTipoActivo = TA.IdTipoActivo
                                     Inner Join Modelo As MDL on AF.IdModelo = MDL.IdModelo
                                     Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                     Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
                                     Inner Join TipoPrecio As TP on TP.IdTipoPrecio = AF.IdTipoPrecio
                                     Inner Join UnidadMedida As UM on UM.IdUnidadMedida = AF.IdUnidadMedida
                                     Where P.IndicadorEstado = 'A' AND AF.CodigoActivoFijo like '%$criterio%' or P.NombreProducto like '%$criterio%' or MRC.NombreMarca like '%$criterio%' or MDL.NombreModelo like '%$criterio%' or
                                           AF.Placa like '%$criterio%' or AF.NumeroSerie like '%$criterio%' or AF.Ano like '%$criterio%' or AF.Color like '%$criterio%'
                                     ORDER BY (AF.IdProducto)
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCodigoActivoFijoParaInsertar($data)
  {
    $codigo = $data["CodigoActivoFijo"];
    $query = $this->db->query("Select AF.*
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Where AF.CodigoActivoFijo = '$codigo' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCodigoActivoFijoParaActualizar($data)
  {
    $id = $data["IdProducto"];
    $codigo = $data["CodigoActivoFijo"];
    $query = $this->db->query("Select AF.*
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Where (AF.IdProducto > '$id' Or AF.IdProducto < '$id' ) and AF.CodigoActivoFijo = '$codigo' AND P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerActivoFijoPorCodigoActivoFijo($data)
  {
    $criterio = $data["CodigoActivoFijo"];
    $query = $this->db->query("Select AF.*, TA.NombreTipoActivo, MRC.NombreMarca, MDL.NombreModelo, P.*,
                                     TA.IdTipoActivo,MRC.IdMarca, TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Inner Join TipoActivo As TA on AF.IdTipoActivo = TA.IdTipoActivo
                                     Inner Join Modelo As MDL on AF.IdModelo = MDL.IdModelo
                                     Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                     Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
                                     Inner Join TipoPrecio As TP on TP.IdTipoPrecio = AF.IdTipoPrecio
                                     Inner Join UnidadMedida As UM on UM.IdUnidadMedida = AF.IdUnidadMedida
                                     Where AF.CodigoActivoFijo = '$criterio' AND P.IndicadorEstado= 'A'
                                     ORDER BY(AF.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerActivoFijoPorIdProducto($data)
  {
    $criterio = $data["IdProducto"];
    $query = $this->db->query("Select AF.*, TA.NombreTipoActivo, MRC.NombreMarca, MDL.NombreModelo, P.*,
                                     TA.IdTipoActivo,MRC.IdMarca, TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida, TSI.CodigoTipoSistemaISC
                                     From ActivoFijo As AF
                                     Inner Join Producto As P on AF.IdProducto = P.IdProducto
                                     Inner Join TipoActivo As TA on AF.IdTipoActivo = TA.IdTipoActivo
                                     Inner Join Modelo As MDL on AF.IdModelo = MDL.IdModelo
                                     Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                                     Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
                                     left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = AF.IdTipoSistemaISC
                                     Inner Join TipoPrecio As TP on TP.IdTipoPrecio = AF.IdTipoPrecio
                                     Inner Join UnidadMedida As UM on UM.IdUnidadMedida = AF.IdUnidadMedida
                                     Where AF.IdProducto = '$criterio' AND P.IndicadorEstado= 'A'
                                     ORDER BY(AF.IdProducto)");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarActivoFijoParaJSON()
  {
    $query = $this->db->query("Select AF.*, TA.NombreTipoActivo, MRC.NombreMarca, MDL.NombreModelo, P.*,
                    TA.IdTipoActivo,MRC.IdMarca, TAI.CodigoTipoAfectacionIGV, TP.CodigoTipoPrecio, UM.AbreviaturaUnidadMedida, UM.NombreUnidadMedida, TSI.CodigoTipoSistemaISC
                    From ActivoFijo As AF
                    Inner Join Producto As P on AF.IdProducto = P.IdProducto
                    Inner Join TipoActivo As TA on AF.IdTipoActivo = TA.IdTipoActivo
                    Inner Join Modelo As MDL on AF.IdModelo = MDL.IdModelo
                    Inner Join Marca As MRC on MDL.IdMarca = MRC.IdMarca
                    Inner Join TipoAfectacionIGV As TAI on TAI.IdTipoAfectacionIGV = AF.IdTipoAfectacionIGV
                    left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = AF.IdTipoSistemaISC
                    Inner Join TipoPrecio As TP on TP.IdTipoPrecio = AF.IdTipoPrecio
                    Inner Join UnidadMedida As UM on UM.IdUnidadMedida = AF.IdUnidadMedida
                    Where P.IndicadorEstado= 'A'
                    ORDER BY(AF.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }
}
