<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAccesoTurnoUsuario extends CI_Model {

    public $AccesoTurnoUsuario = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->AccesoTurnoUsuario = $this->Base->Construir("AccesoTurnoUsuario");
    }

    function InsertarAccesoTurnoUsuario($data)
    {
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        // $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $resultado = $this->mapper->map($data,$this->AccesoTurnoUsuario);
        $this->db->insert('AccesoTurnoUsuario', $resultado);
        $data["IdAccesoTurnoUsuario"] = $this->db->insert_id();
        return $data;
    }

    function ActualizarAccesoTurnoUsuario($data)
    {
        $id=$data["IdAccesoTurnoUsuario"];
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $resultado = $this->mapper->map($data,$this->AccesoTurnoUsuario);
        $this->db->where('IdAccesoTurnoUsuario', $id);
        $this->db->update('AccesoTurnoUsuario', $resultado);
        return $data;
    }

    function BorrarAccesoTurnoUsuario($data)
    {
        $id=$data["IdAccesoTurnoUsuario"];
        $this->db->where("IdAccesoTurnoUsuario",$id);
        $this->db->delete("AccesoTurnoUsuario");
    }

    function BorrarAccesosTurnoUsuarioPorUsuario($data)
    {
        $id=$data["IdUsuario"];
        $this->db->where("IdUsuario",$id);
        $this->db->delete("AccesoTurnoUsuario");
    }

    function ConsultarTurnosUsuario($data)
    {
        $id = $data["IdUsuario"]; //revisar parametro
        $query = $this->db->query("select ATU.*
                            from AccesoTurnoUsuario as ATU
                            where ATU.IdUsuario='$id'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerAccesoTurnoUsuarioPorUsuarioYTurno($data)
    {
        $usuario = $data["IdUsuario"];
        $turno = $data["IdTurno"];
        $query = $this->db->query("Select *
                                FROM accesoTurnoUsuario as ATU
                                WHERE ATU.IdUsuario='$usuario' and ATU.IdTurno = '$turno'
                                AND ATU.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerTurnosPorIdUsuario($data)
    {
        $usuario = $data["IdUsuario"];
        
        $query = $this->db->query("Select *
                                FROM AccesoTurnoUsuario as ATU
                                INNER JOIN Turno T ON T.IdTurno = ATU.IdTurno
                                LEFT JOIN horario h ON h.IdHorario = t.IdHorario
                                WHERE ATU.IdUsuario = '$usuario' AND ATU.IndicadorEstado = 'A'
                                AND ATU.EstadoTurnoUsuario = 1 AND T.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
}
