<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mRadioTaxi extends CI_Model
{

  public $RadioTaxi = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->RadioTaxi = $this->Base->Construir("RadioTaxi");
  }

  function ListarRadioTaxis()
  {
    $query = $this->db->query("SELECT * 
    FROM  RadioTaxi 
    WHERE IndicadorEstado = 'A'
    ORDER BY NombreRadioTaxi");
    $resultado = $query->result_array();
    return $resultado;
  }
  
  function InsertarRadioTaxi($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;

    $resultado = $this->mapper->map($data, $this->RadioTaxi);
    $this->db->insert('RadioTaxi', $resultado);

    $resultado["IdRadioTaxi"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarRadioTaxi($data)
  {
    $id = $data["IdRadioTaxi"];
    $resultado = $this->mapper->map($data, $this->RadioTaxi);
    $this->db->where('IdRadioTaxi', $id);
    $this->db->update('RadioTaxi', $resultado);
    return  $resultado;
  }

  function BorrarRadioTaxi($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarRadioTaxi($data);
    return $resultado;
  }

  function ObtenerRadioTaxiPorNombre($data)
  {
    $nombre = $data["NombreRadioTaxi"];
    $query = $this->db->query("SELECT * 
              FROM  RadioTaxi 
              WHERE NombreRadioTaxi = '$nombre' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerRadioTaxiPorNombreActualizar($data)
  {
    $id = $data["IdRadioTaxi"];
    $nombre = $data["NombreRadioTaxi"];
    $query = $this->db->query("SELECT * 
              FROM RadioTaxi 
              WHERE IdRadioTaxi != '$id' AND NombreRadioTaxi = '$nombre' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerRadioTaxiPorIdRadioTaxi($data)
  {
    $id = $data["IdRadioTaxi"];
    $query = $this->db->query("Select *
                              FROM RadioTaxi
                              WHERE IdRadioTaxi = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }
}
