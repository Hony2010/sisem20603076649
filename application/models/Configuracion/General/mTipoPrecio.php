<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoPrecio extends CI_Model {

        public $TipoPrecio = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoPrecio = $this->Base->Construir("TipoPrecio");
        }

        function ListarTiposPrecio()
        {
          $this->db->select("*")
          ->from('TipoPrecio');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }
