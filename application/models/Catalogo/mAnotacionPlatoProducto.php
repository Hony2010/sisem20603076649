<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAnotacionPlatoProducto extends CI_Model {

    public $AnotacionPlatoProducto = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->AnotacionPlatoProducto = $this->Base->Construir("AnotacionPlatoProducto");
    }

    function InsertarAnotacionPlatoProducto($data) {
        $data["UsuarioRegistro"]= $this->sesionusuario->obtener_sesion_nombre_usuario();
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $resultado = $this->mapper->map($data,$this->AnotacionPlatoProducto);
        $this->db->insert('AnotacionPlatoProducto', $resultado);
        $resultado = $this->db->insert_id();
        return($resultado);
    }

    function ActualizarAnotacionPlatoProducto($data)
    {
        $id=$data["IdAnotacionPlatoProducto"];
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $resultado = $this->mapper->map($data,$this->AnotacionPlatoProducto);
        $this->db->where('IdAnotacionPlatoProducto', $id);
        $this->db->update('AnotacionPlatoProducto', $resultado);
    }

    function BorrarAnotacionPlatoProducto($data)
    {
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $this->ActualizarAnotacionPlatoProducto($data);
    }

    function ListarAnotacionPlatoProducto($data)
    {
        $query = $this->db->query("Select *
                                FROM AnotacionPlatoProducto
                                WHERE IndicadorEstado = 'A' AND EstadoOpcion = 1");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerAnotacionPlatoProductoPorProductoYAnotacionPlato($data){
        $producto=$data["IdProducto"];
        $plato=$data["IdAnotacionPlato"];
        $query = $this->db->query("Select *
                                    FROM AnotacionPlatoProducto app
                                    WHERE app.IdProducto = '$producto'
                                    AND app.IdAnotacionPlato = '$plato'
                                    AND app.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerPlatoProductoPorIdProducto($data){
        $producto=$data["IdProducto"];
        $query = $this->db->query("Select *
                                    FROM AnotacionPlatoProducto app
                                    WHERE app.IdProducto = '$producto'
                                    AND app.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    
    function BorrarAnotacionesPlatoProductoPorIdProducto($data){
        $producto=$data["IdProducto"];
        $query = $this->db->query("Update AnotacionPlatoProducto SET IndicadorEstado = 'E'
                                    WHERE IdProducto = '$producto'");
        $resultado = $this->db->affected_rows();
        return $resultado;
    }
    
    function ObtenerAnotacionesPlatoProductoPorIdProducto($data){
        $producto=$data["IdProducto"];
        $query = $this->db->query("Select * 
                                    FROM AnotacionPlatoProducto app
                                    INNER JOIN AnotacionPlato ap ON ap.IdAnotacionPlato = app.IdAnotacionPlato
                                    WHERE app.IdProducto = '$producto'
                                    AND app.IndicadorEstado = 'A'
                                    AND app.EstadoOpcion = 1
                                    AND ap.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
}