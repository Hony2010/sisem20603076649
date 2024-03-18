<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sModalidadTraslado extends MY_Service {

        public $ModalidadTraslado = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mModalidadTraslado');
              $this->ModalidadTraslado = $this->mModalidadTraslado->ModalidadTraslado;
        }

        function ListarModalidadTraslados()
        {
          $resultado = $this->mModalidadTraslado->ListarModalidadTraslados();
          return $resultado;
        }
}
