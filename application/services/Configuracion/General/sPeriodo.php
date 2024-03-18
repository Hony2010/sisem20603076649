<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sPeriodo extends MY_Service {

        public $Periodo = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mPeriodo');
              $this->Periodo = $this->mPeriodo->Periodo;
        }

        function ListarPeriodos()  {
          $resultado = $this->mPeriodo->ListarPeriodos();
          return $resultado;
        }

        function ListarPeriodoPorId($data)  {
          $resultado = $this->mPeriodo->ListarPeriodoPorId($data);
          return $resultado;
        }

        function ListarPeriodoAños()  {
          $resultado = $this->mPeriodo->ListarPeriodoAños();
          return $resultado;
        }

        function ListarPeriodoPorAño($data)  {
          $resultado = $this->mPeriodo->ListarPeriodoPorAño($data);
          return $resultado;
        }

        function ObtenerPeriodoPorFecha($data)  {
          $resultado = $this->mPeriodo->ObtenerPeriodoPorFecha($data);
          return $resultado;
        }

}
