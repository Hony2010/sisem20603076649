<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDocumentoReferenciaNotaSalida extends MY_Service {

  public $DocumentoReferenciaNotaSalida = array();
  public $DetalleDocumentoReferenciaNotaSalida = array();

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
    $this->load->model('Inventario/mDocumentoReferenciaNotaSalida');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service("Configuracion/General/sTipoCambio");
    $this->load->service("Configuracion/General/sFormaPago");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service("Configuracion/Venta/sTipoTarjeta");
    $this->load->service("Catalogo/sCliente");

    $this->DocumentoReferenciaNotaSalida = $this->mDocumentoReferenciaNotaSalida->DocumentoReferenciaNotaSalida;
  }

  function InsertarDocumentoReferenciaNotaSalida($data)
  {
    try {
      $dataParseada = $this->ParsearDataDocumentoReferenciaNotaSalida($data);
      $resultado= $this->mDocumentoReferenciaNotaSalida->InsertarDocumentoReferenciaNotaSalida($dataParseada);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function ActualizarDocumentoReferenciaNotaSalida($data)
  {
    try {
      $data["FechaEmision"]=convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);

      $resultado=$this->mDocumentoReferenciaNotaSalida->ActualizarDocumentoReferenciaNotaSalida($data);
      $IdDocumentoReferenciaNotaSalida =$data["IdDocumentoReferenciaNotaSalida"];
      $resultado["DetallesDocumentoReferenciaNotaSalida"] = $this->sDetalleDocumentoReferenciaNotaSalida->ActualizarDetallesDocumentoReferenciaNotaSalida($IdDocumentoReferenciaNotaSalida, $data["DetallesDocumentoReferenciaNotaSalida"]);
      $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
      $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function BorrarDocumentoReferenciaNotaSalida($data)
  {
    $this->mDocumentoReferenciaNotaSalida->BorrarDocumentoReferenciaNotaSalida($data);
    return "";
  }

  function ParsearDataDocumentoReferenciaNotaSalida($data)
  {
    $dataParseada = $data;
    $dataParseada["IdDocumentoReferenciaNotaSalida"] = "";

    $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    $dataParseada["CodigoTipoDocumentoReferencia"] = $tipodocumento[0]["CodigoTipoDocumento"];
    $dataParseada["NombreAbreviadoDocumentoReferencia"] = $tipodocumento[0]["NombreAbreviado"];
    $dataParseada["SerieDocumentoReferencia"] = $data["SerieDocumento"];
    $dataParseada["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];
    $dataParseada["FechaDocumentoReferencia"] = $data["FechaEmision"];
    $dataParseada["TotalDocumentoReferencia"] = $data["Total"];
    $dataParseada["MonedaDocumentoReferencia"] = $data["NombreMoneda"];
    $dataParseada["CodigoMonedaDocumentoReferencia"] = $data["CodigoMoneda"];
    $dataParseada["TipoCambioDocumentoReferencia"] = $data["ValorTipoCambio"];

    return $dataParseada;
  }

  /*Se borran documentos vinculados*/
  function ObtenerDocumentosReferenciaByComprobanteVenta($data)
  {
    $resultado = $this->mDocumentoReferenciaNotaSalida->ObtenerDocumentosReferenciaByComprobanteVenta($data);
    return $resultado;
  }

  function ObtenerDocumentosReferenciaByComprobanteCompra($data)
  {
    $resultado = $this->mDocumentoReferenciaNotaSalida->ObtenerDocumentosReferenciaByComprobanteCompra($data);
    return $resultado;
  }

  /*SE OBTIENEN LOS DOCUMENTOS VINCULADOS CON NOTAS*/
  function ObtenerDocumentosReferenciaByComprobanteVentaYNotaSalida($data)
  {
    $resultado = $this->mDocumentoReferenciaNotaSalida->ObtenerDocumentosReferenciaByComprobanteVentaYNotaSalida($data);
    return $resultado;
  }
}
