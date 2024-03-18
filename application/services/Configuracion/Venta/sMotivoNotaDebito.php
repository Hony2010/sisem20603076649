<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoNotaDebito extends MY_Service {

        public $MotivoNotaDebito = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mMotivoNotaDebito');
              $this->MotivoNotaDebito = $this->mMotivoNotaDebito->MotivoNotaDebito;
        }

        function ListarMotivosNotaDebito()
        {
          $resultado = $this->mMotivoNotaDebito->ListarMotivosNotaDebito();
          return $resultado;
        }

        function ListarMotivosNotaDebitoCompra()
        {
          $resultado = $this->mMotivoNotaDebito->ListarMotivosNotaDebitoCompra();
          return $resultado;
        }

}
