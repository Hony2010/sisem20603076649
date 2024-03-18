<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoNotaEntrada extends MY_Service {

        public $MotivoNotaEntrada = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Inventario/mMotivoNotaEntrada');
              $this->MotivoNotaEntrada = $this->mMotivoNotaEntrada->MotivoNotaEntrada;
        }

        function ListarMotivosNotaEntrada()
        {
          $resultado = $this->mMotivoNotaEntrada->ListarMotivosNotaEntrada();
          return $resultado;
        }

        function ObtenerMotivoNotaEntrada($data) {
          $resultado = $this->mMotivoNotaEntrada->ObtenerMotivoNotaEntrada($data);
          return $resultado;
        }

}
