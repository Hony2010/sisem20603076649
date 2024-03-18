<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoNotaSalida extends MY_Service {

        public $MotivoNotaSalida = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Inventario/mMotivoNotaSalida');
              $this->MotivoNotaSalida = $this->mMotivoNotaSalida->MotivoNotaSalida;
        }

        function ListarMotivosNotaSalida()
        {
          $resultado = $this->mMotivoNotaSalida->ListarMotivosNotaSalida();
          return $resultado;
        }

        function ObtenerMotivoNotaSalida($data) {
          $resultado = $this->mMotivoNotaSalida->ObtenerMotivoNotaSalida($data);
          return $resultado;
        }

}
