<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComprobanteVentaTransporte extends MY_Service {

  public $ComprobanteVentaTransporte = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('reporter');
    $this->load->library('imprimir');
    $this->load->helper("date");
    $this->load->model("Base");
    $this->load->model("Venta/mComprobanteVentaTransporte");
    $this->load->service('Configuracion/General/sConstanteSistema');

    $this->ComprobanteVentaTransporte = $this->mComprobanteVentaTransporte->ComprobanteVentaTransporte;
  }

  function ValidarComprobanteVentaTransporte($data)
  {
    if(!is_numeric($data["NumeroAsiento"]))
    {
      return "El campo Numero de Asiento debe ser numerico.";
    }
    elseif($data["NumeroAsiento"] > 50 || $data["NumeroAsiento"] < 0)
    {
      return "El campor Numero de Asiento deber estar entre el rango de 0 a 50.";
    }
    else
    {
      return "";
    }
  }

  function InsertarComprobanteVentaTransporte($id, $data)
  {
    $parametroRubroTransporte = $this->sConstanteSistema->ObtenerParametroRubroTransporte();
    $validacionNumeroAsiento =  ($parametroRubroTransporte == 1) ? $this->ValidarComprobanteVentaTransporte($data) : "";
    if($validacionNumeroAsiento != "")
    {
      return $validacionNumeroAsiento;
    }
    else
    {
      $data["IdComprobanteVenta"] = $id;
      $resultado = $this->mComprobanteVentaTransporte->InsertarComprobanteVentaTransporte($data);
      return $resultado;
    }
  }

  function ActualizarComprobanteVentaTransporte($id, $data)
  {
    $parametroRubroTransporte = $this->sConstanteSistema->ObtenerParametroRubroTransporte();
    $validacionNumeroAsiento =  ($parametroRubroTransporte == 1) ? $this->ValidarComprobanteVentaTransporte($data) : "";
    if($validacionNumeroAsiento != "")
    {
      return $validacionNumeroAsiento;
    }
    else
    {
      $data["IdComprobanteVenta"] = $id;
      $resultado = $this->mComprobanteVentaTransporte->ActualizarComprobanteVentaTransporte($data);
      return $resultado;
    }
  }

  function BorrarComprobanteVentaTransporte($data)
  {
    $resultado = $this->mComprobanteVentaTransporte->BorrarComprobanteVentaTransporte($data);
    return $resultado;
  }

}
