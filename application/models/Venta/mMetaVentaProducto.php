<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mMetaVentaProducto extends CI_Model
{

  public $MetaVentaProducto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->MetaVentaProducto = $this->Base->Construir("MetaVentaProducto");
  }

  function ConsultarMetasVentaProducto()
  {
    $estado = ESTADO_ACTIVO;

    $consulta = "SELECT m.CodigoMercaderia, p.IdProducto, p.NombreProducto, mp.MetaCantidad, mp.PorcentajeComisionVenta, mp.IdMetaVentaProducto
                FROM mercaderia m 
                INNER JOIN producto p ON p.IdProducto = m.IdProducto
                INNER JOIN metaventaproducto mp ON mp.IdProducto = p.IdProducto
                WHERE p.IndicadorEstado = '$estado' AND MP.IndicadorEstado = '$estado'";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMetaVentaProductoPorIdProducto($data)
  {
    $id = $data["IdProducto"];
    $query = $this->db->query("Select * from MetaVentaProducto
                              where IdProducto = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarMetaVentaProducto($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    // $data["IdProducto"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data, $this->MetaVentaProducto);
    $this->db->insert('MetaVentaProducto', $resultado);
    $resultado["IdMetaVentaProducto"] = $this->db->insert_id();
    return ($resultado);
  }


  function ActualizarMetaVentaProducto($data)
  {
    $id = $data["IdMetaVentaProducto"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data, $this->MetaVentaProducto);
    $this->db->where('IdMetaVentaProducto', $id);
    $this->db->update('MetaVentaProducto', $resultado);

    return $resultado;
  }

  function BorrarMetaVentaProducto($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarMetaVentaProducto($data);
  }

  function BorrarMetasVentaProductoNoListados($ids)
  {
    $estado = ESTADO_ELIMINADO;
    $consulta = "UPDATE metaventaproducto
                SET IndicadorEstado = '$estado'
                WHERE IdProducto NOT IN(".$ids.")";
    $query = $this->db->query($consulta);
    $resultado = $this->db->affected_rows();
    return $resultado;
  }

  
}
