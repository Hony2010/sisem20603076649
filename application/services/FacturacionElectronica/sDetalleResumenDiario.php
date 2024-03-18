<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleResumenDiario extends MY_Service {

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->service('Configuracion/General/sEmpresa');
        $this->load->model('FacturacionElectronica/mDetalleResumenDiario');
        $this->DetalleResumenDiario = $this->mDetalleResumenDiario->DetalleResumenDiario;
  }

  function ConsultarDetallesResumenDiario($data)
  {
    $resultado = $this->mDetalleResumenDiario->ConsultarDetallesResumenDiario($data);
    return $resultado;
  }

  function InsertarDetallesResumenDiario($data,$input) {
    $i = 1;
    $resultado=[];
    foreach ($data as $key => $value) {
      $value["IdDetalleResumenDiario"] = "";
      $value["NumeroItem"] = $i;
      $value["IdResumenDiario"] = $input["IdResumenDiario"];
      $value["MontoTotal"] = $value["Total"];
      $resultado[]=$this->InsertarDetalleResumenDiario($value);
      $i++;
    }
    return $resultado;
  }

  function ActualizarDetallesResumenDiario($data, $input)
  {
    $response = $this->BorrarDetalleResumenDiarioPorIdResumenDiario($input["IdResumenDiario"]);
    $resultado = $this->InsertarDetallesResumenDiario($data, $input);
    return $resultado;
  }

  function InsertarDetalleResumenDiario($data) {
    $resultado = $this->mDetalleResumenDiario->InsertarDetalleResumenDiario($data);
    return $resultado;
  }

  function BorrarDetalleResumenDiarioPorIdResumenDiario($IdResumenDiario)
  {
    $resultado = $this->mDetalleResumenDiario->BorrarDetalleResumenDiarioPorIdResumenDiario($IdResumenDiario);
    return $resultado;
  }

}
