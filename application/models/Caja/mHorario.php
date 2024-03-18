<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mHorario extends CI_Model {

    public $Horario = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->Horario = $this->Base->Construir("Horario");
    }

    function InsertarHorario($data)
    {
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
        $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data,$this->Horario);
        $this->db->insert('Horario', $resultado);
        $resultado["IdHorario"] = $this->db->insert_id();
        return($resultado);
    }

    function ActualizarHorario($data)
    {
        $id=$data["IdHorario"];
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data,$this->Horario);
        $this->db->where('IdHorario', $id);
        $this->db->update('Horario', $resultado);

        return $resultado;
    }

    function BorrarHorario($data)
    {
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $resultado = $this->ActualizarHorario($data);
        return $resultado;
    }

    function ListarHorarios()
    {
        $query = $this->db->query("Select t.IdHorario, t.HoraInicio, t.HoraFin, 
                                    t.HorasHolgura, t.HoraInicioHolgura, t.HoraFinHolgura
                                    FROM Horario t
                                    WHERE T.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    
    function ObtenerHorarioParaInsertar($data)
    {
        $Horario=$data["NombreHorario"];
        $query = $this->db->query("Select t.IdHorario, t.HoraInicio, t.HoraFin, 
                                    t.HorasHolgura, t.HoraInicioHolgura, t.HoraFinHolgura
                                    FROM Horario
                                    WHERE NombreHorario = '$Horario'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }


    function ObtenerHorarioPorIdHorario($data)
    {
        $id=$data["IdHorario"];
        $query = $this->db->query("Select t.IdHorario, t.HoraInicio, t.HoraFin, 
                                    t.HorasHolgura, t.HoraInicioHolgura, t.HoraFinHolgura
                                    FROM Horario t
                                    WHERE IdHorario = '$id'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    
    function ObtenerHorarioPorHoraInicioYFin($data)
    {
        // $id=$data["IdHorario"];
        $hinicio=$data["HoraInicio"];
        $hfin=$data["HoraFin"];
        $hsholgura=$data["HorasHolgura"];
        $query = $this->db->query("Select t.IdHorario, t.HoraInicio, t.HoraFin, 
                                    t.HorasHolgura, t.HoraInicioHolgura, t.HoraFinHolgura
                                    FROM Horario t
                                    WHERE t.HoraInicio = '$hinicio' AND t.HoraFin = '$hfin'
                                    AND t.HorasHolgura = '$hsholgura'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
}