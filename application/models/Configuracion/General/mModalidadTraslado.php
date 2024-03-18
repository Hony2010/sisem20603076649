<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mModalidadTraslado extends CI_Model {

        public $ModalidadTraslado = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->ModalidadTraslado = $this->Base->Construir("ModalidadTraslado");
        }

        function ListarModalidadTraslados()
        {
          $query = $this->db->query("Select *
                                   from modalidadtraslado as M
                                   where M.IndicadorEstado = 'A'
                                   order by M.IdModalidadTraslado");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
