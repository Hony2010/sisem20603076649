<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoOperacionCaja extends MY_Service {

  public $TipoOperacionCaja = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->model('Caja/mTipoOperacionCaja');
        $this->TipoOperacionCaja = $this->mTipoOperacionCaja->TipoOperacionCaja;
  }

  function ListarTiposOperacionCaja()
  {
    $resultado = $this->mTipoOperacionCaja->ListarTiposOperacionCaja();
    return $resultado;
  }

  function ListarTiposOperacionCajaParaDocumentoIngreso()
  {
    $data["IndicadorTipoComprobante"] = INDICADOR_TIPO_COMPROBANTE_INGRESO;
    $resultado = $this->mTipoOperacionCaja->ListarTiposOperacionCajaPorIndicadorTipoComprobante($data);
    return $resultado;
  }

  function ListarTiposOperacionCajaParaDocumentoEgreso()
  {
    $data["IndicadorTipoComprobante"] = INDICADOR_TIPO_COMPROBANTE_EGRESO;
    $resultado = $this->mTipoOperacionCaja->ListarTiposOperacionCajaPorIndicadorTipoComprobante($data);
    return $resultado;
  }

  function ObtenerTipoOperacionCajaPorIdTipoOperacionCaja($data)
  {
    $resultado = $this->mTipoOperacionCaja->ObtenerTipoOperacionCajaPorIdTipoOperacionCaja($data);
    return $resultado;
  }
}
