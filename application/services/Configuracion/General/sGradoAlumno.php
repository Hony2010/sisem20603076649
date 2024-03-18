<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sGradoAlumno extends MY_Service {

        public $GradoAlumno = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mGradoAlumno');
              $this->GradoAlumno = $this->mGradoAlumno->GradoAlumno;
        }

        function ListarGradosAlumno()
        {
          $resultado = $this->mGradoAlumno->ListarGradosAlumno();
          return $resultado;
        }
}
