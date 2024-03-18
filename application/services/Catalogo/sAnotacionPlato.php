<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAnotacionPlato extends MY_Service {

  public $AnotacionPlato = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('jsonconverter');
    $this->load->model('Catalogo/mAnotacionPlato');
    $this->AnotacionPlato = $this->mAnotacionPlato->AnotacionPlato;
  }

  function ListarAnotacionesPlato()
  {
    $resultado=$this->mAnotacionPlato->ListarAnotacionesPlato();
    return $resultado;
  }

  function InsertarAnotacionPlato($data)
  {
    $resultado = $this->mAnotacionPlato->InsertarAnotacionPlato($data);
    return $resultado;
  }

  function ActualizarAnotacionPlato($data)
  {
    $resultado = $this->mAnotacionPlato->ActualizarAnotacionPlato($data);
    return $resultado;
  }

  function BorrarAnotacionPlato($data)
  {
    $resultado = $this->mAnotacionPlato->BorrarAnotacionPlato($data);
    return $resultado;
  }

}
