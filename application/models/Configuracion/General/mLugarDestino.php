<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mLugarDestino extends CI_Model {

        public $LugarDestino = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->LugarDestino = $this->Base->Construir("LugarDestino");
        }

        function ListarLugaresDestinos()
        {
          $query = $this->db->query("Select *
                                   from lugardestino  as LD
                                   where LD.IndicadorEstado = 'A'
                                   order by LD.NombreLugarDestino");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
