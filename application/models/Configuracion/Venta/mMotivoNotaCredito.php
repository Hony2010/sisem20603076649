<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoNotaCredito extends CI_Model {

        public $MotivoNotaCredito = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoNotaCredito = $this->Base->Construir("MotivoNotaCredito");
        }

        function ListarMotivosNotaCredito()
        {
          $query = $this->db->query("Select *
                                    From MotivoNotaCredito
                                    Where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarMotivosNotaCreditoCompra()
        {
          $query = $this->db->query("Select *
                                    From MotivoNotaCredito
                                    Where IndicadorEstado = 'A' AND IndicadorCompra = '1'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
