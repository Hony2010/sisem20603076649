<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sConfiguracionImpresion extends MY_Service {

        public $ConfiguracionImpresion = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mConfiguracionImpresion');
              $this->ConfiguracionImpresion = $this->mConfiguracionImpresion->ConfiguracionImpresion;
              $this->load->service('Configuracion/General/sParametroSistema');
        }

        function ListarConfiguracionImpresion()
        {
          $resultado = $this->mConfiguracionImpresion->ListarConfiguracionImpresion();
          return $resultado;
        }

        function ActualizarConfiguracionImpresion($data)
        {

          $resultado = $this->mConfiguracionImpresion->ActualizarConfiguracionImpresion($data);
          return "";
        }
}
