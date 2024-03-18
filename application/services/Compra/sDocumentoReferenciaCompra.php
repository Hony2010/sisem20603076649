<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sDocumentoReferenciaCompra extends MY_Service
{

  public $DocumentoReferenciaCompra = array();
  public $DetalleDocumentoReferenciaCompra = array();

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
    $this->load->model('Compra/mDocumentoReferenciaCompra');
    $this->load->service('Compra/sDetalleComprobanteCompra');

    $this->DocumentoReferenciaCompra = $this->mDocumentoReferenciaCompra->DocumentoReferenciaCompra;
  }

  function ConsultarDocumentosReferenciaNotaComprobanteCompra($data)
  {
    $resultado = $this->mDocumentoReferenciaCompra->ConsultarDocumentosReferenciaNotaComprobanteCompra($data);
    return $resultado;
  }

  function ObtenerMovimientoAlmacenPorNotaComprobanteCompra($data)
  {
    $resultado = $this->mDocumentoReferenciaCompra->ObtenerMovimientoAlmacenPorNotaComprobanteCompra($data);
    return $resultado;
  }

  function ConsultarDocumentosReferencia($data)
  {
    $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
    $resultado = $this->mDocumentoReferenciaCompra->ConsultarDocumentosReferencia($data);
    foreach ($resultado as $key => $item) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($resultado[$key]["FechaVencimiento"]);
      $resultado[$key]["DiferenciaSaldo"] = $resultado[$key]["Total"] - $resultado[$key]["SaldoNotaCredito"];
      // $resultado[$key]["DetallesComprobanteCompra"] =[];
      $resultado[$key]["DetallesComprobanteCompra"] = $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($item);
    }
    return $resultado;
  }

  function InsertarDocumentoReferenciaCompra($data)
  {
    try {
      $nueva_data = $this->ParsearDataDocumentoReferenciaCompra($data);

      $resultado = $this->mDocumentoReferenciaCompra->InsertarDocumentoReferenciaCompra($nueva_data);

      return $resultado;
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarDocumentoReferenciaCompra($data)
  {
    try {
      $resultado = $this->mDocumentoReferenciaCompra->ActualizarDocumentoReferenciaCompra($data);
      return $resultado;
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ConsultarComprobantesPorIdNota($data)
  {
    $resultado = $this->mDocumentoReferenciaCompra->ObtenerDocumentosReferenciaByComprobante($data);
    return $resultado;
  }

  function ObtenerDocumentoReferenciaPorComprobanteYNota($data)
  {
    $resultado = $this->mDocumentoReferenciaCompra->ObtenerDocumentoReferenciaPorComprobanteYNota($data);
    return $resultado;
  }

  function BorrarDocumentoReferenciaCompra($data)
  {
    //regla : validar que al eliminar el comprobante no tenga facturacion electronica enviado.
    // $resultado =[];// $this->sComprobanteElectronico->ObtenerComprobanteElectronicoPorIdDocumentoReferencia($data);
    $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
    $resultado = $this->ConsultarComprobantesPorIdNota($data);

    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        // code...
        $this->mDocumentoReferenciaCompra->BorrarDocumentoReferenciaCompra($value);
      }
    }

    return $resultado;
  }

  function ParsearDataDocumentoReferenciaCompra($data)
  {
    $otra_data = $data;
    $otra_data["IdDocumentoReferencia"] = "";
    // $otra_data["CodigoTipoDocumentoReferencia"] = $data["IdTipoDocumento"];
    $otra_data["CodigoTipoDocumentoReferencia"] = $data["CodigoTipoDocumento"];

    $otra_data["SerieDocumentoReferencia"] = $data["SerieDocumento"];
    $otra_data["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];
    $otra_data["FechaDocumentoReferencia"] = convertToDate($data["FechaEmision"]);
    $otra_data["TotalDocumentoReferencia"] = $data["Total"];
    $otra_data["NombreAbreviadoDocumentoReferencia"] = $data["NombreAbreviado"];

    return $otra_data;
  }

  function ActualizarSaldosEnDocumentoReferenciaCompra($data)
  {
    $resultado = $this->ObtenerDocumentoReferenciaPorComprobanteYNota($data);

    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        $value["TotalNota"] = $data["Total"];
        $this->ActualizarDocumentoReferenciaCompra($value);
      }
    }
    return $data;
  }
}
