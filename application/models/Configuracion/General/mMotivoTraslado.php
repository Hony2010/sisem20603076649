<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoTraslado extends CI_Model {

        public $MotivoTraslado = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoTraslado = $this->Base->Construir("MotivoTraslado");
        }

        function ListarMotivoTraslados()
        {
          $query = $this->db->query("Select *
                                   from motivotraslado as M
                                   where M.IndicadorEstado = 'A'
                                   order by M.IdMotivoTraslado");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
