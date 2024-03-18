<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mPersona extends CI_Model
{

  public $Persona = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->Persona = $this->Base->Construir("Persona");
  }

  function InsertarPersona($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdGradoAlumno"] = $data["IdGradoAlumno"] != "" ? $data["IdGradoAlumno"] : null;
    $resultado = $this->mapper->map($data, $this->Persona);
    $this->db->insert('Persona', $resultado);
    $resultado["IdPersona"] = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarPersona($data)
  {
    $id = $data["IdPersona"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["IdGradoAlumno"] = array_key_exists("IdGradoAlumno", $data) ? $data["IdGradoAlumno"] : null;
    $data["IdGradoAlumno"] = $data["IdGradoAlumno"] != "" ? $data["IdGradoAlumno"] : null;
    $resultado = $this->mapper->map($data, $this->Persona);
    $this->db->where('IdPersona', $id);
    $this->db->update('Persona', $resultado);
    return $resultado;
  }

  function BorrarPersona($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarPersona($data);
  }

  function ConsultarClienteEnComprobanteVenta($data)
  {
    $id = $data["IdPersona"];
    $this->db->select("*")
      ->from('ComprobanteVenta')
      ->where("IndicadorEstado='A' AND IdCliente = '$id'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarProveedorEnComprobanteCompra($data)
  {
    $id = $data["IdPersona"];
    $this->db->select("*")
      ->from('ComprobanteCompra')
      ->where("IndicadorEstado='A' AND IdProveedor = '$id'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }
}
