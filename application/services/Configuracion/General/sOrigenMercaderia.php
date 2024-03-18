<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sOrigenMercaderia extends MY_Service {

        public $OrigenMercaderia = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mOrigenMercaderia');
              $this->OrigenMercaderia = $this->mOrigenMercaderia->OrigenMercaderia;
        }

        function ListarOrigenMercaderia()
        {
          $resultado = $this->mOrigenMercaderia->ListarOrigenMercaderia();
          return $resultado;
        }
}
