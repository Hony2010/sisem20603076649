<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoRol extends MY_Service {

        public $TipoRol = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoRol');
              $this->TipoRol = $this->mTipoRol->TipoRol;
        }

        function ListarTiposRol()
        {
          $resultado = $this->mTipoRol->ListarTiposRol();
          return $resultado;
        }
}
