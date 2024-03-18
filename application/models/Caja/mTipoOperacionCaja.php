<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoOperacionCaja extends CI_Model {

        public $TipoOperacionCaja = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoOperacionCaja = $this->Base->Construir("TipoOperacionCaja");
        }

        function ListarTiposOperacionCaja()
        {
          $query = $this->db->query("Select *
                                    From TipoOperacionCaja
                                    Where IndicadorEstado = 'A'
                                    order by NombreConceptoCaja asc");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarTiposOperacionCajaPorIndicadorTipoComprobante($data)
        {
          $id=$data["IndicadorTipoComprobante"];
          $query = $this->db->query("Select *
                                    From TipoOperacionCaja
                                    Where IndicadorEstado = 'A' AND IndicadorTipoComprobante = '$id'
                                    order by NombreConceptoCaja asc");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerTipoOperacionCajaPorIdTipoOperacionCaja($data)
        {
          $id = $data["IdTipoOperacionCaja"];
          $query = $this->db->query("Select *
                                    From TipoOperacionCaja
                                    Where IndicadorEstado = 'A' AND IdTipoOperacionCaja = '$id''");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
