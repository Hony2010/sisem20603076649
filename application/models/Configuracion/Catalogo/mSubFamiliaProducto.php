<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mSubFamiliaProducto extends CI_Model
{

  public $SubFamiliaProducto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->SubFamiliaProducto = $this->Base->Construir("SubFamiliaProducto");
  }

  function ListarSubFamiliasProducto($data)
  {
    $id = $data["IdFamiliaProducto"];
    $this->db->select("*")
      ->from('SubFamiliaProducto')
      ->where("IdFamiliaProducto = '$id' AND IndicadorEstado = 'A'")
      ->order_by('NoEspecificado desc, NombreSubFamiliaProducto asc');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ListarTodosSubFamiliasProducto()
  {
    $query = $this->db->query("Select *
                                    From SubFamiliaProducto
                                    Where IndicadorEstado = 'A'
                                    order by NoEspecificado desc, NombreSubFamiliaProducto asc");
    $resultado = $query->result();
    return $resultado;
  }

  function InsertarSubFamiliaProducto($data) {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data, $this->SubFamiliaProducto);
    $this->db->insert('SubFamiliaProducto', $resultado);
    $resultado["IdSubFamiliaProducto"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarSubFamiliaProducto($data)
  {
    $id = $data["IdSubFamiliaProducto"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data, $this->SubFamiliaProducto);
    $this->db->where('IdSubFamiliaProducto', $id);
    $this->db->update('SubFamiliaProducto', $resultado);
  }

  function BorrarSubFamiliaProducto($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarSubFamiliaProducto($data);
  }

  function ConsultarSubFamiliasProductoEnMercaderia($data)
  {
    $id = $data["IdSubFamiliaProducto"];
    $this->db->select("M.*")
      ->from('Mercaderia As M')
      ->join('Producto as P', 'M.IdProducto = P.IdProducto')
      ->where("M.IdSubFamiliaProducto = '$id' AND P.IndicadorEstado = 'A'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarSubFamiliasProducto($data)
  {
    $criterio = $data["textofiltro"];
    $this->db->select("SubFamiliaProducto.*")
      ->from('SubFamiliaProducto')
      ->where('NombreSubFamiliaProducto like "%' . $criterio . '%" or IdSubFamiliaProducto like "%' . $criterio . '%" AND IndicadorEstado="A"');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarSubFamiliasJSON($data)
  {
    $query = $this->db->query("Select SFP.*
                                    From SubFamiliaProducto As SFP
                                    WHERE SFP.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarSubFamiliaNoEspefificadoPorFamilia($data)
  {
    $familia = $data["IdFamiliaProducto"];
    $query = $this->db->query("Select SFP.*
                                    From SubFamiliaProducto As SFP
                                    WHERE SFP.IdFamiliaProducto = '$familia' 
                                    AND SFP.NoEspecificado = 'S' 
                                    AND SFP.IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }

  function ObtenerSubFamiliaProductoPorNombreSubFamilia($data) {
    $IdFamiliaProducto=$data["IdFamiliaProducto"];
    $NombreSubFamilia=$data["NombreSubFamiliaProducto"];
    $sql="Select SFP.*
    From SubFamiliaProducto As SFP
    WHERE SFP.IdFamiliaProducto=$IdFamiliaProducto And SFP.NombreSubFamiliaProducto='$NombreSubFamilia' And SFP.IndicadorEstado = 'A'";
    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;
  }

}
