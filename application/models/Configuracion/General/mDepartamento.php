<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDepartamento extends CI_Model {

        public $Departamento = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Departamento = $this->Base->Construir("Departamento");
        }

        function ListarDepartamentos()
        {
          $query = $this->db->query("Select *
                                   from departamento as D
                                   where D.IndicadorEstado = 'A'
                                   order by D.IdDepartamento");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
