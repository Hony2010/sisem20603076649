<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoTraslado extends MY_Service {

        public $MotivoTraslado = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mMotivoTraslado');
              $this->MotivoTraslado = $this->mMotivoTraslado->MotivoTraslado;
        }

        function ListarMotivoTraslados()
        {
          $resultado = $this->mMotivoTraslado->ListarMotivoTraslados();
          return $resultado;
        }
}
