<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTurno extends CI_Model {

    public $Turno = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->Turno = $this->Base->Construir("Turno");
    }

    function InsertarTurno($data)
    {
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
        $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data,$this->Turno);
        $this->db->insert('Turno', $resultado);
        $resultado["IdTurno"] = $this->db->insert_id();
        return($resultado);
    }

    function ActualizarTurno($data)
    {
        $id=$data["IdTurno"];
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $resultado = $this->mapper->map($data,$this->Turno);
        $this->db->where('IdTurno', $id);
        $this->db->update('Turno', $resultado);

        return $resultado;
    }

    function BorrarTurno($data)
    {
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $resultado = $this->ActualizarTurno($data);
        return $resultado;
    }

    function ObtenerTurnoParaInsertar($data)
    {
        $turno=$data["NombreTurno"];
        $query = $this->db->query("Select * FROM Turno
                                    WHERE NombreTurno = '$turno'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerTurnoParaActualizar($data)
    {
        $id=$data["IdTurno"];
        $turno=$data["NombreTurno"];
        $query = $this->db->query("Select *
                        FROM Turno
                        WHERE IdTurno != '$id'
                        AND NombreTurno = '$turno'
                        AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ListarTurnos()
    {
        $query = $this->db->query("Select t.*, h.*
                                    FROM turno t
                                    LEFT JOIN horario h ON h.IdHorario = t.IdHorario
                                    WHERE T.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerTurnoPorIdTurno($data)
    {
        $id=$data["IdTurno"];
        $query = $this->db->query("Select t.*, h.*
                                    FROM turno t
                                    LEFT JOIN horario h ON h.IdHorario = t.IdHorario
                                    WHERE IdTurno = '$id'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

}
