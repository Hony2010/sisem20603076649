<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoProducto extends CI_Model {

        public $TipoProducto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoProducto = $this->Base->Construir("TipoProducto");
        }

        function ListarTiposProducto()
        {
          $this->db->select("*")
          ->from('TipoProducto')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdTipoProducto');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}
