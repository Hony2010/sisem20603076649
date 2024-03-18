<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAnotacionPlato extends CI_Model {

    public $AnotacionPlato = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->AnotacionPlato = $this->Base->Construir("AnotacionPlato");
    }

    function InsertarAnotacionPlato($data) {
        $data["UsuarioRegistro"]= $this->sesionusuario->obtener_sesion_nombre_usuario();
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $resultado = $this->mapper->map($data,$this->AnotacionPlato);
        $this->db->insert('AnotacionPlato', $resultado);
        $resultado = $this->db->insert_id();
        return($resultado);
    }

    function ActualizarAnotacionPlato($data)
    {
        $id=$data["IdAnotacionPlato"];
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $resultado = $this->mapper->map($data,$this->AnotacionPlato);
        $this->db->where('IdAnotacionPlato', $id);
        $this->db->update('AnotacionPlato', $resultado);
    }

    function BorrarAnotacionPlato($data)
    {
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $this->ActualizarAnotacionPlato($data);
    }

    function ListarAnotacionesPlato()
    {
        $query = $this->db->query("Select *
                                FROM AnotacionPlato
                                WHERE IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
}