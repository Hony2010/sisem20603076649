<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mProvincia extends CI_Model {

        public $Provincia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Provincia = $this->Base->Construir("Provincia");
        }

        function ListarProvincias()
        {
          $query = $this->db->query("Select *
                                   from provincia as P
                                   where P.IndicadorEstado = 'A'
                                   order by P.IdProvincia");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
