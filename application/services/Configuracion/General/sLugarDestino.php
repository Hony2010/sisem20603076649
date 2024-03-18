<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sLugarDestino extends MY_Service {

        public $LugarDestino = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mLugarDestino');
              $this->LugarDestino = $this->mLugarDestino->LugarDestino;
        }

        function ListarLugaresDestinos()
        {
          $resultado = $this->mLugarDestino->ListarLugaresDestinos();
          return $resultado;
        }
}
