<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mDetallePendienteLetraCobrar extends CI_Model
{

  public $DetallePendienteLetraCobrar = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DetallePendienteLetraCobrar = $this->Base->Construir("DetallePendienteLetraCobrar");
  }

  function InsertarDetallePendienteLetraCobrar($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->DetallePendienteLetraCobrar);
    $this->db->insert('DetallePendienteLetraCobrar', $resultado);
    $resultado["IdDetallePendienteLetraCobrar"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarDetallePendienteLetraCobrar($data)
  {
    $id = $data["IdDetallePendienteLetraCobrar"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->DetallePendienteLetraCobrar);
    $this->db->where('IdDetallePendienteLetraCobrar', $id);
    $this->db->update('DetallePendienteLetraCobrar', $resultado);

    return $resultado;
  }

  function BorrarDetallePendienteLetraCobrar($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarDetallePendienteLetraCobrar($data);
    return $resultado;
  }

  function BorrarDetallesPendienteLetraCobrarPorIdPendienteLetraCobrar($data)
  {
    $id = $data["IdPendienteLetraCobrar"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->mapper->map($data, $this->DetallePendienteLetraCobrar);
    $this->db->where('IdPendienteLetraCobrar', $id);
    $this->db->update('DetallePendienteLetraCobrar', $resultado);
    return $resultado;
  }

  function ConsultarDetallesPendienteLetraCobrarPorPendienteLetraCobrar($data)
  {
    $id=$data["IdPendienteLetraCobrar"];
    $query = $this->db->query("Select * from DetallePendienteLetraCobrar
            where IdPendienteLetraCobrar = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }
}
