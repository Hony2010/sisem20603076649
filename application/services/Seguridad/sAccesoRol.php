<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAccesoRol extends MY_Service {

        public $AccesoRol = array();
        public $Persona = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Seguridad/mAccesoRol');
              $this->load->model('Configuracion/General/mModuloSistema');
              $this->load->service('Seguridad/sOpcionSistema');
              $this->Usuario = $this->mAccesoRol->AccesoRol;
        }

        function InsertarAccesoRol($data)
        {
            $resultado=$this->mAccesoRol->InsertarAccesoRol($data);
            return $resultado;
        }

        function ActualizarAccesoRol($data)
        {
            // foreach ($data as $value) {
              $resultado = $this->mAccesoRol->ActualizarAccesoRol($data);
            // }
            return $resultado;
        }

        function ValidarAccesoRol($data)
        {
            $resultado=$this->mAccesoRol->ValidarAccesoRol($data);
            return $resultado;
        }

        function ObtenerAccesosRolPorIdOpcionSistemaPorIdRol($data) {
            $resultado = $this->mAccesoRol->ObtenerAccesosRolPorIdOpcionSistemaPorIdRol($data);
            return $resultado;
        }
}
