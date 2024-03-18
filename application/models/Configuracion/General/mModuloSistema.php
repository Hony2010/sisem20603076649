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
               $this->ModuloSistema = $this->Base->Construir("ModuloSistema");
        }

        function ListarModulosSistema() {
          $this->db->select("*")
          ->from('ModuloSistema')
          ->where('IndicadorEstado="A"')
          ->order_by('IdModuloSistema');
          $query = $this->db->get();
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarModulosSistemaParaTipoDocumento()
        {
          $this->db->select("*")
          ->from('ModuloSistema')
          ->where('IndicadorDocumento="1"')
          ->order_by('IdModuloSistema');
          $query = $this->db->get();
          $resultado = $query->result_array();
          return $resultado;
        }

}
