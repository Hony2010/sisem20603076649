<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mModuloSistema extends CI_Model {

        public $ModuloSistema = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Usuario = $this->Base->Construir("ModuloSistema");
        }

        function ListarModulosSistema() {
          //$id = $data["IdModuloSistema"];
          $query = $this->db->query("select * from ModuloSistema where IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
