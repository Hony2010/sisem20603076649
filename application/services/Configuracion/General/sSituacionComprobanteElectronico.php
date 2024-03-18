<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sSituacionComprobanteElectronico extends MY_Service {

        public $SituacionComprobanteElectronico = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mSituacionComprobanteElectronico');
              $this->SituacionComprobanteElectronico = $this->mSituacionComprobanteElectronico->SituacionComprobanteElectronico;
        }

        function ListarSituacionesCPE()
        {
          $resultado = $this->mSituacionComprobanteElectronico->ListarSituacionesCPE();
          return $resultado;
        }

        function ObtenerSituacionCPEPorCodigo($data)
        {
          $resultado = $this->mSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($data);          
          return $resultado;
        }
}
