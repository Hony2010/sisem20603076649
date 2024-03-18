<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mVehiculoCliente extends CI_Model
{

  public $VehiculoCliente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->VehiculoCliente = $this->Base->Construir("VehiculoCliente");
  }

  function ListarVehiculoClientes()
  {
    $query = $this->db->query("Select *
                              FROM VehiculoCliente
                              WHERE IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarVehiculoCliente($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data, $this->VehiculoCliente);
    $this->db->insert('VehiculoCliente', $resultado);
    $resultado["IdVehiculoCliente"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarVehiculoCliente($data)
  {
    $id = $data["IdVehiculoCliente"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data, $this->VehiculoCliente);
    $this->db->where('IdVehiculoCliente', $id);
    $this->db->update('VehiculoCliente', $resultado);
    return $resultado;
  }

  function BorrarVehiculoCliente($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarVehiculoCliente($data);
    return $resultado;
  }

  function BorrarVehiculosPorIdCliente($data)
  {
    $id = $data["IdCliente"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->mapper->map($data, $this->VehiculoCliente);
    $this->db->where('IdCliente', $id);
    $this->db->update('VehiculoCliente', $resultado);
    return $resultado;
  }

  function ConsultarVehiculosClientePorIdCliente($data)
  {
    $id = $data["IdPersona"];
    $query = $this->db->query("Select *
                              FROM VehiculoCliente VC
                              LEFT JOIN Vehiculo V ON VC.IdVehiculo = V.IdVehiculo
                              WHERE VC.IdCliente = '$id' AND VC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarVehiculoClientePorIdClienteYIdVehiculo($data)
  {
    $id = $data["IdPersona"];
    $idvehiculo = $data["IdVehiculo"];
    $query = $this->db->query("Select *
                              FROM VehiculoCliente VC
                              LEFT JOIN Vehiculo V ON VC.IdVehiculo = V.IdVehiculo
                              WHERE VC.IdVehiculo = '$idvehiculo' AND VC.IdCliente = '$id' AND VC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }
}
