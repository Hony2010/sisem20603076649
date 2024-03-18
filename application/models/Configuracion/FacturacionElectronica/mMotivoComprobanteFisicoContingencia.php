<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoComprobanteFisicoContingencia extends CI_Model {

        public $MotivoComprobanteFisicoContingencia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoComprobanteFisicoContingencia = $this->Base->Construir("MotivoComprobanteFisicoContingencia");
        }

        function ListarMotivosComprobanteFisicoContingencia()
        {
          $query = $this->db->query("Select *
                                    From MotivoComprobanteFisicoContingencia
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
