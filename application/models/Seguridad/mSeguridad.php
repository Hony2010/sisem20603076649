<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mSeguridad extends CI_Model {

        public $Usuario = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Usuario = $this->Base->Construir("Usuario");
        }


        

}
