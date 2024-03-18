<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoNotaSalida extends CI_Model {

        public $MotivoNotaSalida = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoNotaSalida = $this->Base->Construir("MotivoNotaSalida");
        }

        function ListarMotivosNotaSalida()
        {
          $query = $this->db->query("Select *
                                    From MotivoNotaSalida
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }


        function ObtenerMotivoNotaSalida($data) {
          $IdMotivoNotaSalida = $data["IdMotivoNotaSalida"];
          $query = $this->db->query("Select *
                                    From MotivoNotaSalida
                                    Where IndicadorEstado = 'A' AND IdMotivoNotaSalida='$IdMotivoNotaSalida'");
          $resultado = $query->row();
          return $resultado;
        }

 }
