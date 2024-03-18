<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mOrigenMercaderia extends CI_Model {

        public $OrigenMercaderia = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->OrigenMercaderia = $this->Base->Construir("OrigenMercaderia");
        }

        function ListarOrigenMercaderia() {
          $this->db->select("*")
          ->from('OrigenMercaderia');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }
