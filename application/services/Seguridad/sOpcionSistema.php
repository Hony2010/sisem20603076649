<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sOpcionSistema extends MY_Service {

        public $TipoExistencia = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Seguridad/mOpcionSistema');
              $this->OpcionSistema = $this->mOpcionSistema->OpcionSistema;
        }

        function ObtenerOpcionesSistemaPorModuloSistema($data) {
          $resultado = $this->mOpcionSistema->ObtenerOpcionesSistemaPorModuloSistema($data);
          return $resultado;
        }

        function ObtenerOpcionesPorIdUsuarioPorIdModuloSistema($data) {
          $resultado = $this->mOpcionSistema->ObtenerOpcionesPorIdUsuarioPorIdModuloSistema($data);
          return $resultado;
        }
}
