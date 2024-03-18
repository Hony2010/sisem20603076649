<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoVenta extends CI_Model {

        public $TipoVenta = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoVenta = $this->Base->Construir("TipoVenta");
        }

        function ListarTiposVenta()
        {
          $this->db->select("*")
          ->from('TipoVenta')
          ->order_by('IdTipoVenta');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}
