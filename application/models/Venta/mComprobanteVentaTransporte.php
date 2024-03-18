<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mComprobanteVentaTransporte extends CI_Model
{

  public $ComprobanteVentaTransporte = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->model("Configuracion/General/mSituacionComprobanteElectronico");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->ComprobanteVentaTransporte = $this->Base->Construir("ComprobanteVentaTransporte");
  }

  function InsertarComprobanteVentaTransporte($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data, $this->ComprobanteVentaTransporte);
    $resultado["IdDestinatario"] = $data["IdDestinatario"] == "" ? null : $resultado["IdDestinatario"];
    $this->db->insert('ComprobanteVentaTransporte', $resultado);
    $resultado["IdComprobanteVenta"] = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarComprobanteVentaTransporte($data)
  {
    $id = $data["IdComprobanteVenta"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado = $this->mapper->map($data, $this->ComprobanteVentaTransporte);
    $resultado["IdDestinatario"] = $data["IdDestinatario"] == "" ? null : $resultado["IdDestinatario"];
    $this->db->where('IdComprobanteVenta', $id);
    $this->db->update('ComprobanteVentaTransporte', $resultado);
    return $resultado;
  }

  function BorrarComprobanteVentaTransporte($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $resultado = $this->ActualizarComprobanteVentaTransporte($data);
    return $resultado;
  }
}
