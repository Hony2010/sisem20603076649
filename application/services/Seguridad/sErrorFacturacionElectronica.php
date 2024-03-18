<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sErrorFacturacionElectronica extends MY_Service {

  public $Usuario = array();
  public $Persona = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Seguridad/mErrorFacturacionElectronica");
  }

  function ObtenerDescripcionErrorCodigo($data)
  {
    $data["CodigoErrorFacturacionElectronica"] = $data["CodigoError"];
    $resultado = $this->mErrorFacturacionElectronica->ObtenerDescripcionErrorCodigo($data);
    return $resultado;
  }

  function ObtenerListadoCodigoErrores()
  {
    $resultado = $this->mErrorFacturacionElectronica->ObtenerListadoCodigoErrores();
    return $resultado;
  }

  function CodigoErrorEnListaCodigoError($codigo)
  {
    $lista = $this->ObtenerListadoCodigoErrores();
    $error = array();
    foreach ($lista as $key => $value) {
      if($value["CodigoErrorFacturacionElectronica"] == $codigo)
      {
        $error = $value;
      }
    }

    return $error;
  }
}
