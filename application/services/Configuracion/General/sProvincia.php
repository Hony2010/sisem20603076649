<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sProvincia extends MY_Service {

        public $Provincia = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mProvincia');
              $this->Provincia = $this->mProvincia->Provincia;
        }

        function ListarProvincias()
        {
          $resultado = $this->mProvincia->ListarProvincias();
          return $resultado;
        }
}
