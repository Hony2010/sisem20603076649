<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoInventarioInicial extends MY_Service {

        public $MotivoInventarioInicial = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Inventario/mMotivoInventarioInicial');
              $this->MotivoInventarioInicial = $this->mMotivoInventarioInicial->MotivoInventarioInicial;
        }

        function ListarMotivosInventarioInicial()
        {
          $resultado = $this->mMotivoInventarioInicial->ListarMotivosInventarioInicial();
          return $resultado;
        }

        function ObtenerMotivoInventarioInicial($data) {
          $resultado = $this->mMotivoInventarioInicial->ObtenerMotivoInventarioInicial($data);
          return $resultado;
        }

}
