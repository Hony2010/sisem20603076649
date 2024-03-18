<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAlumno extends MY_Service {

        public $Alumno = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Catalogo/mAlumno');
              $this->Alumno = $this->mAlumno->Alumno;
        }

        function ListarAlumnos($data)
        {
          $resultado = $this->mAlumno->ListarAlumnos($data);
          return $resultado;
        }

        function ListarTodosAlumnos()
        {
          $resultado = $this->mAlumno->ListarTodosAlumnos();
          return $resultado;
        }

        function InsertarAlumno($data)
        {
          $resultado = $this->mAlumno->InsertarAlumno($data);
          return $resultado;
        }

        function ActualizarAlumno($data)
        {
          $data["IndicadorEstado"] = ESTADO_ACTIVO;
          $this->mAlumno->ActualizarAlumno($data);
          return "";
        }

        function ValidarExistenciaAlumnosEnMercaderia($data)
        {
          $resultado = $this->mAlumno->ConsultarAlumnosEnMercaderia($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque la Sub familia tiene movimientos en Mercaderia";
          }
          else
          {
            return "";
          }
        }

        function BorrarAlumno($data)
        {
          $resultado = "";//$this -> ValidarExistenciaAlumnosEnMercaderia($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
             $this->mAlumno->BorrarAlumno($data);
             return "";
          }
        }

        function ConsultarAlumnos($data)
        {
          $resultado=$this->mAlumno->ConsultarAlumnos($data);
          return $resultado;
        }
}
