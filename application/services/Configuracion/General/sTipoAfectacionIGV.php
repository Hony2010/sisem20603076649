<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoAfectacionIGV extends MY_Service {

        public $TipoAfectacionIGV = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoAfectacionIGV');
              $this->TipoAfectacionIGV = $this->mTipoAfectacionIGV->TipoAfectacionIGV;
        }

        function ListarTiposAfectacionIGV()
        {
          $resultado = $this->mTipoAfectacionIGV->ListarTiposAfectacionIGV();
          return $resultado;
        }

        function ObtenerTipoAfectacionIGVPorId($data)
        {
          $resultado = $this->mTipoAfectacionIGV->ObtenerTipoAfectacionIGVPorId($data);
          if(count($resultado))
          {
            return $resultado[0];
          }
          else
          {
            return "No se encontro el tipo de afectacion.";
          }
        }
}
