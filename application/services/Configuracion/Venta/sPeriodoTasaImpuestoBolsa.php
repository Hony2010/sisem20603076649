<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sPeriodoTasaImpuestoBolsa extends MY_Service {

  public $PeriodoTasaImpuestoBolsa = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Configuracion/Venta/mPeriodoTasaImpuestoBolsa');
    $this->PeriodoTasaImpuestoBolsa = $this->mPeriodoTasaImpuestoBolsa->PeriodoTasaImpuestoBolsa;
  }

  function ListarPeriodosTasaImpuestoBolsa()
  {
    $resultado = $this->mPeriodoTasaImpuestoBolsa->ListarPeriodosTasaImpuestoBolsa();
    return $resultado ;
  }
}
