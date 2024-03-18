<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mGradoAlumno extends CI_Model {

        public $GradoAlumno = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->GradoAlumno = $this->Base->Construir("GradoAlumno");
        }

        function ListarGradosAlumno()
        {
          $query = $this->db->query("Select *
                                   from GradoAlumno as GA
                                   where GA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
