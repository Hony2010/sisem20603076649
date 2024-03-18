<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sPreguntaSeguridad extends MY_Service {

        public $PreguntaSeguridad = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Seguridad/mPreguntaSeguridad');
              $this->PreguntaSeguridad = $this->mPreguntaSeguridad->PreguntaSeguridad;
        }

        function ListarPreguntasSeguridad()
        {
          $resultado = $this->mPreguntaSeguridad->ListarPreguntasSeguridad();
          return $resultado;
        }
}
