<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoVenta extends MY_Service {

        public $TipoVenta = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mTipoVenta');
              $this->TipoVenta = $this->mTipoVenta->TipoVenta;
        }

        function ListarTiposVenta()
        {
          $resultado = $this->mTipoVenta->ListarTiposVenta();
          return $resultado ;
        }
}
