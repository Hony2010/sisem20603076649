<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDistrito extends CI_Model {

        public $Distrito = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Distrito = $this->Base->Construir("Distrito");
        }

        function ListarDistritos()
        {
          $query = $this->db->query("Select *
                                   from distrito as D
                                   where D.IndicadorEstado = 'A'
                                   order by D.IdDistrito");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
