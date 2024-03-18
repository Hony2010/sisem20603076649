<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mSituacionComprobanteElectronico extends CI_Model {

        public $SituacionComprobanteElectronico = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->SituacionComprobanteElectronico = $this->Base->Construir("SituacionComprobanteElectronico");
        }

        function ListarSituacionesCPE() {
          $this->db->select("*")
          ->from('SituacionComprobanteElectronico');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerSituacionCPEPorCodigo($parametro) {
          $this->db->select("*")
          ->from('SituacionComprobanteElectronico')
          ->where("IndicadorEstado='A' AND CodigoSituacionComprobanteElectronico = '$parametro'");
          $query = $this->db->get();
          $resultado = $query->row();

          return $resultado;
        }
 }
