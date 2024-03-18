<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMetaVentaVendedor extends CI_Model {

  public $MetaVentaVendedor = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->MetaVentaVendedor = $this->Base->Construir("MetaVentaVendedor");
  }

  function ConsultarMetasVentaVendedor($data)
  {
    $rol = ID_ROL_VENDEDOR;
    $consulta = "Select u.AliasUsuarioVenta, p.RazonSocial, mvv.*, p.IdPersona, e.Sueldo
                  FROM usuario As u
                  LEFT JOIN persona As p on p.IdPersona = u.IdPersona
                  LEFT JOIN empleado As e on u.IdPersona = e.IdPersona
                  LEFT JOIN metaventavendedor As mvv on u.IdPersona = mvv.IdPersona
                  Where u.IndicadorEstado = 'A'
                  and p.IdRol = '$rol'";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMetaVentaVendedorPorIdPersona($data)
  {
    $id=$data["IdPersona"];
    $query = $this->db->query("Select * from MetaVentaVendedor
                              where IdPersona = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarMetaVentaVendedor($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    // $data["IdPersona"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->MetaVentaVendedor);
    $this->db->insert('MetaVentaVendedor', $resultado);
    $resultado["IdMetaVentaVendedor"] = $this->db->insert_id();
    return($resultado);
  }


  function ActualizarMetaVentaVendedor($data)
  {
    $id=$data["IdMetaVentaVendedor"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->MetaVentaVendedor);
    $this->db->where('IdMetaVentaVendedor', $id);
    $this->db->update('MetaVentaVendedor', $resultado);

    return $resultado;
  }

  function BorrarMetaVentaVendedor($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarMetaVentaVendedor($data);
  }
}