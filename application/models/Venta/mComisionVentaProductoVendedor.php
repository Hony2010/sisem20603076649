<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mComisionVentaProductoVendedor extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->ComisionVentaProductoVendedor = $this->Base->Construir("ComisionVentaProductoVendedor");
    }

    function InsertarComisionVentaProductoVendedor($data) {
        $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"] = ESTADO_ACTIVO;
        $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data, $this->ComisionVentaProductoVendedor);
        $this->db->insert('ComisionVentaProductoVendedor', $resultado);
        $resultado["IdComisionVentaProductoVendedor"] = $this->db->insert_id();
        return $resultado;
    }


    function ActualizarComisionVentaProductoVendedor($data) {
        $id = $data["IdComisionVentaProductoVendedor"];
        $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data, $this->ComisionVentaProductoVendedor);
        $this->db->where('IdComisionVentaProductoVendedor', $id);
        $this->db->update('ComisionVentaProductoVendedor', $resultado);
        return $resultado;
    }

    function BorrarComisionVentaProductoVendedor($data) {
        $data["IndicadorEstado"] = ESTADO_ELIMINADO;
        $this->ActualizarComisionVentaProductoVendedor($data);
    }

    function ObtenerComisionVentaProductoVendedor($data) {
        $IdProducto = $data["IdProducto"];
        $IdPeriodo = $data["IdPeriodo"];
        $IdUsuarioVendedor = $data["IdUsuarioVendedor"];

        $query = $this->db->query("Select * from ComisionVentaProductoVendedor
                                where IdProducto = '$IdProducto' AND 
                                IdPeriodo = '$IdPeriodo' AND
                                IdUsuarioVendedor = '$IdUsuarioVendedor' AND
                                IndicadorEstado = 'A'");
        $resultado = $query->result_array();

        return $resultado;
    }
}