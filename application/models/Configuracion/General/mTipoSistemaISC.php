<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoSistemaISC extends CI_Model {

        public $TipoSistemaISC = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoSistemaISC = $this->Base->Construir("TipoSistemaISC");
        }

        function ListarTiposSistemaISC() {
          $this->db->select("*")
          ->from('TipoSistemaISC');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }
