<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mPeriodoTasaImpuestoBolsa extends CI_Model {

        public $PeriodoTasaImpuestoBolsa = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->PeriodoTasaImpuestoBolsa = $this->Base->Construir("PeriodoTasaImpuestoBolsa");
        }

        function ListarPeriodosTasaImpuestoBolsa()
        {
          $query = $this->db->query("Select *
                                    From PeriodoTasaImpuestoBolsa
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }