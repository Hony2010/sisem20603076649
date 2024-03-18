<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mPreguntaSeguridad extends CI_Model {

        public $PreguntaSeguridad = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->PreguntaSeguridad = $this->Base->Construir("PreguntaSeguridad");
        }

        function ListarPreguntasSeguridad()
        {
          $this->db->select("PreguntaSeguridad.*")
          ->from('PreguntaSeguridad')
          ->order_by('IdPreguntaSeguridad');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}
