<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoSistemaISC extends MY_Service {

        public $TipoSistemaISC = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoSistemaISC');
              $this->TipoSistemaISC = $this->mTipoSistemaISC->TipoSistemaISC;
        }

        function ListarTiposSistemaISC()
        {
          $resultado = $this->mTipoSistemaISC->ListarTiposSistemaISC();
          return $resultado;
        }
}
