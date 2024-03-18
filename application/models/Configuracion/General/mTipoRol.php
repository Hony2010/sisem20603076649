<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoRol extends CI_Model {

        public $TipoRol = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoRol = $this->Base->Construir("TipoRol");
        }

        function ListarTiposRol()
        {
          $this->db->select("*")
          ->from('TipoRol');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }        
 }
