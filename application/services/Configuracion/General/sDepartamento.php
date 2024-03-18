<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDepartamento extends MY_Service {

        public $Departamento = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mDepartamento');
              $this->Departamento = $this->mDepartamento->Departamento;
        }

        function ListarDepartamentos()
        {
          $resultado = $this->mDepartamento->ListarDepartamentos();
          return $resultado;
        }
}
