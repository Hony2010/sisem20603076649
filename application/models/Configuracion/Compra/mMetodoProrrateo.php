<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMetodoProrrateo extends CI_Model {

        public $MetodoProrrateo = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MetodoProrrateo = $this->Base->Construir("MetodoProrrateo");
        }

        function ListarMetodosProrrateo()
        {
          $query = $this->db->query("Select *
                                    From MetodoProrrateo
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMetodoProrrateo($data) {
          $IdMetodoProrrateo = $data["IdMetodoProrrateo"];
          $query = $this->db->query("Select *
                                    From MetodoProrrateo
                                    Where IndicadorEstado = 'A' AND IdMetodoProrrateo='$IdMetodoProrrateo'");
          $resultado = $query->row();
          return $resultado;
        }
 }
