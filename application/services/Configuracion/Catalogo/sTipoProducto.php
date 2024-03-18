<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoProducto extends MY_Service {

        public $TipoProducto = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoProducto');
              $this->TipoProducto = $this->mTipoProducto->TipoProducto;
        }

        function ListarTiposProducto()
        {
          $resultado = $this->mTipoProducto->ListarTiposProducto();
          return $resultado ;
        }
}
