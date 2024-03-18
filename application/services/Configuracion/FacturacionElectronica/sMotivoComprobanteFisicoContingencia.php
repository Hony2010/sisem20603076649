<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoComprobanteFisicoContingencia extends MY_Service {

        public $MotivoComprobanteFisicoContingencia = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/FacturacionElectronica/mMotivoComprobanteFisicoContingencia');
              $this->MotivoComprobanteFisicoContingencia = $this->mMotivoComprobanteFisicoContingencia->MotivoComprobanteFisicoContingencia;
        }

        function ListarMotivosComprobanteFisicoContingencia()
        {
          $resultado = $this->mMotivoComprobanteFisicoContingencia->ListarMotivosComprobanteFisicoContingencia();
          return $resultado;
        }

}
