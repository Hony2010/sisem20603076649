<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoPrecio extends MY_Service {

        public $TipoPrecio = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoPrecio');
              $this->TipoPrecio = $this->mTipoPrecio->TipoPrecio;
        }

        function ListarTiposPrecio()
        {
          $resultado = $this->mTipoPrecio->ListarTiposPrecio();
          return $resultado;
        }
}
