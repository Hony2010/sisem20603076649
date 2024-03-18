<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoListaRaleo extends MY_Service {

        public $TipoListaRaleo = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoListaRaleo');
              $this->TipoListaRaleo = $this->mTipoListaRaleo->TipoListaRaleo;
        }

        function ListarTiposListaRaleo()
        {
          $resultado = $this->mTipoListaRaleo->ListarTiposListaRaleo();
          return $resultado;
        }

}
