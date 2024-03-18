<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mVehiculo extends CI_Model
{

  public $Vehiculo = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->Vehiculo = $this->Base->Construir("Vehiculo");
  }

  function ListaVehiculos()
  {
    $query = $this->db->query("SELECT V.*, R.NombreRadioTaxi
    FROM Vehiculo V
    LEFT JOIN RadioTaxi R ON R.IdRadioTaxi = V.IdRadioTaxiActual
    WHERE V.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select Count(IdVehiculo) As NumeroFila From Vehiculo");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarVehiculos($inicio, $ValorParametroSistema)
  {
    $query = $this->db->query("Select V.*, R.NombreRadioTaxi
                                     From Vehiculo As V
                                     LEFT JOIN RadioTaxi R ON R.IdRadioTaxi = V.IdRadioTaxiActual
                                     Where V.IndicadorEstado='A'
                                     ORDER  BY (V.IdVehiculo) ASC
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalVehiculos($data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select count(V.IdVehiculo) as cantidad
                                     From Vehiculo As V
                                     LEFT JOIN RadioTaxi R ON R.IdRadioTaxi = V.IdRadioTaxiActual
                                     Where V.IndicadorEstado='A' AND (V.NumeroPlaca like '%$criterio%'  or R.NombreRadioTaxi like '%$criterio%')
                                     ORDER  BY (V.IdVehiculo)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarVehiculos($inicio, $ValorParametroSistema, $data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select V.*, R.NombreRadioTaxi
                                     From Vehiculo As V
                                     LEFT JOIN RadioTaxi R ON R.IdRadioTaxi = V.IdRadioTaxiActual
                                     Where V.IndicadorEstado='A' AND (V.NumeroPlaca like '%$criterio%'  or R.NombreRadioTaxi like '%$criterio%')
                                     ORDER  BY (V.IdVehiculo)
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarVehiculo($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;

    $resultado = $this->mapper->map($data, $this->Vehiculo);
    $this->db->insert('Vehiculo', $resultado);

    $resultado["IdVehiculo"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarVehiculo($data)
  {
    $id = $data["IdVehiculo"];
    $resultado = $this->mapper->map($data, $this->Vehiculo);
    $this->db->where('IdVehiculo', $id);
    $this->db->update('Vehiculo', $resultado);
    return  $resultado;
  }

  function BorrarVehiculo($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarVehiculo($data);
    return $resultado;
  }

  function ObtenerVehiculoPorNumeroPlaca($data)
  {
    $numero = $data["NumeroPlaca"];
    $query = $this->db->query("Select *
                              FROM Vehiculo
                              WHERE NumeroPlaca = '$numero' AND IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }

  function ObtenerVehiculoPorNumeroPlacaActualizar($data)
  {
    $id = $data["IdVehiculo"];
    $numero = $data["NumeroPlaca"];
    $query = $this->db->query("Select *
                              FROM Vehiculo
                              WHERE IdVehiculo != '$id' AND NumeroPlaca = '$numero' AND IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }

  function ObtenerVehiculoPorIdVehiculo($data)
  {
    $id = $data["IdVehiculo"];
    $query = $this->db->query("Select *
                              FROM Vehiculo
                              WHERE IdVehiculo = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }
}
