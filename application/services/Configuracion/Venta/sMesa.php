<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMesa extends MY_Service {

  public $Mesa = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Configuracion/Venta/mMesa');
    $this->Mesa = $this->mMesa->Mesa;
  }

  function ListarMesas()
  {
    $resultado = $this->mMesa->ListarMesas();
    return $resultado;
  }

  function InsertarMesa($data)
  {
    $resultado = $this->mMesa->InsertarMesa($data);
    return $resultado;
  }

  function ActualizarMesa($data)
  {
    $resultado = $this->mMesa->ActualizarMesa($data);
    return $resultado;
  }

  function BorrarMesa($data)
  {
    $resultado = $this->mMesa->BorrarMesa($data);
    return $resultado;
  }

}
