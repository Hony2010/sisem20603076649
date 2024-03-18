<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAlumno extends CI_Model {

        public $Alumno = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->Alumno = $this->Base->Construir("Alumno");
        }

        function ListarAlumnos($data)
        {

          $id=$data["IdPersona"];
          $this->db->select("*")
          ->from('Alumno')
          ->where("IdCliente = '$id' AND IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ListarTodosAlumnos()
        {
          $query = $this->db->query("Select *, concat(NombreCompleto,' ',ApellidoCompleto) as NombreAlumno
                                    From Alumno
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarAlumno($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Alumno);
          $this->db->insert('Alumno', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarAlumno($data)
       	{
       		$id=$data["IdAlumno"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
       		$resultado = $this->mapper->map($data,$this->Alumno);
       		$this->db->where('IdAlumno', $id);
       		$this->db->update('Alumno', $resultado);
       	}

        function BorrarAlumno($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        	$this->ActualizarAlumno($data);
        }
}
