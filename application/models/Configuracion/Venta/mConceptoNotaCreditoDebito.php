<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mConceptoNotaCreditoDebito extends CI_Model {

        public $ConceptoNotaCreditoDebito = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->ConceptoNotaCreditoDebito = $this->Base->Construir("ConceptoNotaCreditoDebito");
        }

        function ListarConceptosNotaCredito($data)
        {
          $id=$data["IdTipoDocumento"];
          $query = $this->db->query("Select *
                                    From ConceptoNotaCreditoDebito
                                    Where IndicadorEstado = 'A' AND IdTipoDocumento = '$id'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerConceptoPorMotivoNotaCredito($data)
        {
          $id=$data["IdTipoDocumento"];
          $idmotivo = $data["IdMotivoNotaCredito"];
          $query = $this->db->query("Select *
                                    From ConceptoNotaCreditoDebito
                                    Where IndicadorEstado = 'A' AND IdTipoDocumento = '$id' AND IdMotivoNotaCredito = '$idmotivo'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarConceptosNotaDebito($data)
        {
          $id=$data["IdTipoDocumento"];
          $query = $this->db->query("Select *
                                    From ConceptoNotaCreditoDebito
                                    Where IndicadorEstado = 'A' AND IdTipoDocumento = '$id'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerConceptoPorMotivoNotaDebito($data)
        {
          $id=$data["IdTipoDocumento"];
          $idmotivo = $data["IdMotivoNotaDebito"];
          $query = $this->db->query("Select *
                                    From ConceptoNotaCreditoDebito
                                    Where IndicadorEstado = 'A' AND IdTipoDocumento = '$id' AND IdMotivoNotaDebito = '$idmotivo'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
