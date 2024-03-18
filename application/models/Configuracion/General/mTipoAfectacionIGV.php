<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoAfectacionIGV extends CI_Model {

        public $TipoAfectacionIGV = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoAfectacionIGV = $this->Base->Construir("TipoAfectacionIGV");
        }

        function ListarTiposAfectacionIGV()
        {
          $this->db->select("*")
          ->from('TipoAfectacionIGV');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerTipoAfectacionIGVPorId($data)
        {
              $id = $data["IdTipoAfectacionIGV"];
              $query = $this->db->query("Select * From tipoafectacionigv where IdTipoAfectacionIGV = '$id'");
              $resultado = $query->result_array();
              return $resultado;
        }
 }
