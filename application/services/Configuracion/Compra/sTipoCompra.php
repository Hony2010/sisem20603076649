<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoCompra extends MY_Service {

        public $TipoCompra = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Compra/mTipoCompra');
              $this->TipoCompra = $this->mTipoCompra->TipoCompra;
        }

        function ListarTiposCompra()
        {
          $resultado = $this->mTipoCompra->ListarTiposCompra();
          return $resultado;
        }

}
