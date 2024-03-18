<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoNotaCredito extends MY_Service {

        public $MotivoNotaCredito = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mMotivoNotaCredito');
              $this->MotivoNotaCredito = $this->mMotivoNotaCredito->MotivoNotaCredito;
        }

        function ListarMotivosNotaCredito()
        {
          $resultado = $this->mMotivoNotaCredito->ListarMotivosNotaCredito();
          return $resultado;
        }

        function ListarMotivosNotaCreditoCompra()
        {
          $resultado = $this->mMotivoNotaCredito->ListarMotivosNotaCreditoCompra();
          return $resultado;
        }

}
