<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoCompra extends CI_Model {

        public $TipoCompra = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->TipoCompra = $this->Base->Construir("TipoCompra");
        }

        function ListarTiposCompra()
        {
          $query = $this->db->query("Select *
                                    from TipoCompra
                                    where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
