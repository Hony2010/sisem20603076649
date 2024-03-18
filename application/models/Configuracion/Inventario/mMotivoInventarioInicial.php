<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoInventarioInicial extends CI_Model {

        public $MotivoInventarioInicial = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoInventarioInicial = $this->Base->Construir("MotivoInventarioInicial");
        }

        function ListarMotivosInventarioInicial()
        {
          $query = $this->db->query("Select *
                                    From MotivoInventarioInicial
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMotivoInventarioInicial($data) {
          $IdMotivoInventarioInicial = $data["IdMotivoInventarioInicial"];
          $query = $this->db->query("Select *
                                    From MotivoInventarioInicial
                                    Where IndicadorEstado = 'A' AND IdMotivoInventarioInicial='$IdMotivoInventarioInicial'");
          $resultado = $query->row();
          return $resultado;
        }
 }
