<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mListaRaleoMercaderia extends CI_Model {

  public $ListaRaleoMercaderia = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->ListaRaleoMercaderia = $this->Base->Construir("ListaRaleoMercaderia");
  }

  function ConsultarListasRaleoMercaderia($data) {

    $IdTipoListaRaleo = $data["IdTipoListaRaleo"];
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

    $query = $this->db->query("Select SFP.NombreSubFamiliaProducto, FP.NombreFamiliaProducto, MC.NombreMarca, ML.NombreModelo, M.*, P.NombreProducto, UM.AbreviaturaUnidadMedida,LRM.IdListaRaleoMercaderia, LRM.Precio, TLR.NombreTipoListaRaleo, TLR.IdTipoListaRaleo
                              From mercaderia As M
                              Inner Join unidadmedida as UM on UM.IdUnidadMedida = M.IdUnidadMedida
                              Inner Join producto As P on M.IdProducto = P.IdProducto
                              Left Join listaraleomercaderia As LRM on M.IdProducto = LRM.IdProducto
                              And LRM.IdTipoListaRaleo like '$IdTipoListaRaleo' And LRM.IndicadorEstado = 'A'
                              Left Join tipolistaraleo as TLR on TLR.IdTipoListaRaleo = LRM.IdTipoListaRaleo
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

  function ConsultarListasRaleoMercaderiaPorIdProducto($data) {

    $IdProducto = $data["IdProducto"];
    $query = $this->db->query("Select M.*, P.NombreProducto, UM.AbreviaturaUnidadMedida,LRM.*, TLR.NombreTipoListaRaleo
                              From mercaderia As M
                              Inner Join unidadmedida as UM on UM.IdUnidadMedida = M.IdUnidadMedida
                              Inner Join producto As P on M.IdProducto = P.IdProducto
                              Left Join listaraleomercaderia As LRM on M.IdProducto = LRM.IdProducto
                              Left Join tipolistaraleo as TLR on TLR.IdTipoListaRaleo = LRM.IdTipoListaRaleo
                              Where P.IndicadorEstado = 'A'
                              And LRM.IndicadorEstado = 'A'
                              And LRM.IdProducto like '$IdProducto'
                              And LRM.Precio > 0
                              ORDER BY LRM.Precio desc");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarListaRaleoMercaderia($data)
  {
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data,$this->ListaRaleoMercaderia);
    $this->db->insert('ListaRaleoMercaderia', $resultado);
    $resultado["IdListaRaleoMercaderia"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarListaRaleoMercaderia($data)
  {
    $id=$data["IdListaRaleoMercaderia"];
    $resultado = $this->mapper->map($data,$this->ListaRaleoMercaderia);
    $this->db->where('IdListaRaleoMercaderia', $id);
    $this->db->update('ListaRaleoMercaderia', $resultado);
  }

  //OBTENEMOS LA LISTA YA INSERTADA
  function ObtenerListaRaleoMercaderiaPorProductoYTipoListaRaleo($data) {
    $IdProducto = $data["IdProducto"];
    $IdTipoListaRaleo = $data["IdTipoListaRaleo"];
    $query = $this->db->query("SELECT * FROM ListaRaleoMercaderia
                WHERE IdProducto = '$IdProducto' AND IdTipoListaRaleo = '$IdTipoListaRaleo'
                AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

}
