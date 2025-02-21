<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoDetraccion extends MY_Service {

        public $TipoDetraccion = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mTipoDetraccion');
              $this->TipoDetraccion = $this->mTipoDetraccion->TipoDetraccion;
        }

        function ListarTiposDetraccion()
        {
          $resultado = $this->mTipoDetraccion->ListarTiposDetraccion();
          return $resultado ;
        }
}
