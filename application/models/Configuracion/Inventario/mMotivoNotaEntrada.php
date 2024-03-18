<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoNotaEntrada extends CI_Model {

        public $MotivoNotaEntrada = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoNotaEntrada = $this->Base->Construir("MotivoNotaEntrada");
        }

        function ListarMotivosNotaEntrada()
        {
          $query = $this->db->query("Select *
                                    From MotivoNotaEntrada
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMotivoNotaEntrada($data) {
          $IdMotivoNotaEntrada = $data["IdMotivoNotaEntrada"];
          $query = $this->db->query("Select *
                                    From MotivoNotaEntrada
                                    Where IndicadorEstado = 'A' AND IdMotivoNotaEntrada='$IdMotivoNotaEntrada'");
          $resultado = $query->row();
          return $resultado;
        }
 }
