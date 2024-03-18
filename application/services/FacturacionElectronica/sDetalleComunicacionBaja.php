<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleComunicacionBaja extends MY_Service {

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->service('Configuracion/General/sEmpresa');
    $this->load->model('FacturacionElectronica/mDetalleComunicacionBaja');
    $this->DetalleComunicacionBaja = $this->mDetalleComunicacionBaja->DetalleComunicacionBaja;
  }

  function ConsultarFacturasElectronicasConComunicacionBaja($data)
  {
    $resultado = $this->mComunicacionBaja->ConsultarFacturasElectronicasConComunicacionBaja($data);
    return $resultado;
  }

  function ConsultarDetallesComunicacionBaja($data)
  {
    $resultado = $this->mDetalleComunicacionBaja->ConsultarDetallesComunicacionBaja($data);
    return $resultado;
  }

  function InsertarDetallesComunicacionBaja($data,$input) {
    $i = 1;
    $resultado=[];
    foreach ($data as $key => $value) {
      $value["NumeroItem"] = $i;
      $value["IdComunicacionBaja"] = $input["IdComunicacionBaja"];
      $resultado[]=$this->InsertarDetalleComunicacionBaja($value);
      $i++;
    }
    return $resultado;
  }
  
  function ActualizarDetallesComunicacionBaja($data, $input)
  {
    $response = $this->BorrarDetalleComunicacionBajaPorIdComunicacionBaja($input["IdComunicacionBaja"]);
    $resultado = $this->InsertarDetallesComunicacionBaja($data, $input);
    return $resultado;
  }

  function InsertarDetalleComunicacionBaja($data) {
    $resultado = $this->mDetalleComunicacionBaja->InsertarDetalleComunicacionBaja($data);
    return $resultado;
  }

  function BorrarDetalleComunicacionBajaPorIdComunicacionBaja($IdComunicacionBaja)
  {
    $resultado = $this->mDetalleComunicacionBaja->BorrarDetalleComunicacionBajaPorIdComunicacionBaja($IdComunicacionBaja);
    return $resultado;
  }
}
