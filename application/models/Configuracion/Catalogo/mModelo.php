<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mModelo extends CI_Model
{

  public $Modelo = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->Modelo = $this->Base->Construir("Modelo");
  }

  function ListarModelos($data)
  {
    $id = $data["IdMarca"];
    $this->db->select("Modelo.*")
      ->from('Modelo')
      ->where("IdMarca = '$id' AND IndicadorEstado = 'A'")
      ->order_by('NoEspecificado desc, NombreModelo asc');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ListarTodosModelos()
  {
    $query = $this->db->query("Select *
                                    From Modelo
                                    Where IndicadorEstado = 'A'
                                    order by NoEspecificado desc, NombreModelo asc");
    $resultado = $query->result();
    return $resultado;
  }

  function InsertarModelo($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data, $this->Modelo);
    $this->db->insert('Modelo', $resultado);
    $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarModelo($data)
  {
    $id = $data["IdModelo"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data, $this->Modelo);
    $this->db->where('IdModelo', $id);
    $this->db->update('Modelo', $resultado);
  }

  function BorrarModelo($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarModelo($data);
  }

  function ConsultarModeloEnMercaderia($data)
  {
    $id = $data["IdModelo"];
    $this->db->select("M.*")
      ->from('Mercaderia as M')
      ->join('Producto as P', 'M.IdProducto = P.IdProducto')
      ->where("M.IdModelo = '$id' AND P.IndicadorEstado = 'A'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarModeloEnActivoFijo($data)
  {
    $id = $data["IdModelo"];
    $this->db->select("AF.*")
      ->from('ActivoFijo as AF')
      ->join('Producto as P', 'AF.IdProducto = P.IdProducto')
      ->where("AF.IdModelo = '$id' AND P.IndicadorEstado = 'A'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarModelo($data)
  {
    $criterio = $data["textofiltro"];
    $this->db->select("Modelo.*")
      ->from('Modelo')
      ->where('NombreModelo like "%' . $criterio . '%" or IdModelo like "%' . $criterio . '%" AND IndicadorEstado="A"');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarModelosJSON($data)
  {
    $query = $this->db->query("Select M.*
                                    From Modelo As M
                                    WHERE M.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarModeloNoEspefificadoPorMarca($data)
  {
    $marca = $data["IdMarca"];
    $query = $this->db->query("Select M.*
                                From Modelo As M
                                WHERE M.IdMarca = '$marca' 
                                AND M.NoEspecificado = 'S' 
                                AND M.IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }


  function ObtenerModeloMarcaPorNombreModelo($data) {
    $IdMarca = $data["IdMarca"];
    $NombreModelo = $data["NombreModelo"];
    $sql = "Select M.*
            From Modelo As M
            WHERE M.IdMarca = '$IdMarca' 
            AND M.NombreModelo = '$NombreModelo' 
            AND M.IndicadorEstado = 'A'";

    $query = $this->db->query($sql);
    $resultado = $query->result_array();

    return $resultado;
  }

}
