<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMetodoProrrateo extends MY_Service {

        public $MetodoProrrateo = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Compra/mMetodoProrrateo');
              $this->MetodoProrrateo = $this->mMetodoProrrateo->MetodoProrrateo;
        }

        function ListarMetodosProrrateo()
        {
          $resultado = $this->mMetodoProrrateo->ListarMetodosProrrateo();
          return $resultado;
        }

        function ObtenerMetodoProrrateo($data) {
          $resultado = $this->mMetodoProrrateo->ObtenerMetodoProrrateo($data);
          return $resultado;
        }

}
