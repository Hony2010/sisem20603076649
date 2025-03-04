<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoDetraccion extends CI_Model {

        public $TipoDetraccion = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoDetraccion = $this->Base->Construir("TipoDetraccion");
        }

        function ListarTiposDetraccion()
        {
          $this->db->select("*, concat(IdTipoDetraccion, ' - ', DescripcionTipoDetraccion) AS FullDescripcionTipoDetraccion")
          ->from('TipoDetraccion')
          ->where('IndicadorEstado', ESTADO_ACTIVO)
          ->order_by('IdTipoDetraccion');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}
