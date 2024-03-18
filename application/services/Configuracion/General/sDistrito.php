<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDistrito extends MY_Service {

        public $Distrito = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mDistrito');
              $this->Distrito = $this->mDistrito->Distrito;
        }

        function ListarDistritos()
        {
          $resultado = $this->mDistrito->ListarDistritos();
          return $resultado;
        }
}
