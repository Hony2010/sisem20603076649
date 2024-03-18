<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mBonificacion extends CI_Model {

    public $Bonificacion = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->Bonificacion = $this->Base->Construir("Bonificacion");
    }

    function InsertarBonificacion($data)
    {
        $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"] = ESTADO_ACTIVO;
        $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
        $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data, $this->Bonificacion);
        $this->db->insert('Bonificacion', $resultado);
        $resultado["IdBonificacion"] = $this->db->insert_id();
        return($resultado);
    }

    function ActualizarBonificacion($data)
    {
        $id = $data["IdBonificacion"];
        $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data, $this->Bonificacion);
        $this->db->where('IdBonificacion', $id);
        $this->db->update('Bonificacion', $resultado);

        return $resultado;
    }

    function BorrarBonificacion($data)
    {
        $data["IndicadorEstado"] = ESTADO_ELIMINADO;
        $resultado = $this->ActualizarBonificacion($data);
        return $resultado;
    }

    function BorrarBonificacionesPorIdProducto($data)
    {
        $IdProducto = $data["IdProducto"];
        $this->db->where("IdProducto", $IdProducto);
        $this->db->delete("Bonificacion");
    }

    function ListarBonificaciones()
    {
        $query = $this->db->query("Select *
                                    FROM Bonificacion
                                    WHERE IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    
    function ListarBonificacionesPorIdProducto($data)
    {
        $producto = $data["IdProducto"];
        $query = $this->db->query("Select B.*, P.NombreProducto
                                    FROM Bonificacion B
                                    INNER JOIN Producto P ON P.IdProducto = B.IdProductoBonificacion
                                    WHERE B.IdProducto = '$producto'
                                    AND B.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
}