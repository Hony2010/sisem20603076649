<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoNotaDebito extends CI_Model {

        public $MotivoNotaDebito = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoNotaDebito = $this->Base->Construir("MotivoNotaDebito");
        }

        function ListarMotivosNotaDebito()
        {
          $query = $this->db->query("Select *
                                    From MotivoNotaDebito
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarMotivosNotaDebitoCompra()
        {
          $query = $this->db->query("Select *
                                    From MotivoNotaDebito
                                    Where IndicadorEstado = 'A' AND IndicadorCompra = '1'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
