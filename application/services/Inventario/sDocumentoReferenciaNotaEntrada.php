<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDocumentoReferenciaNotaEntrada extends MY_Service {

  public $DocumentoReferenciaNotaEntrada = array();
  public $DetalleDocumentoReferenciaNotaEntrada = array();

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
    $this->load->model('Inventario/mDocumentoReferenciaNotaEntrada');
    $this->load->service('Inventario/sDetalleNotaEntrada');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service("Configuracion/General/sTipoCambio");
    $this->load->service("Configuracion/General/sFormaPago");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service("Configuracion/Venta/sTipoTarjeta");
    $this->load->service("Catalogo/sCliente");

    $this->DocumentoReferenciaNotaEntrada = $this->mDocumentoReferenciaNotaEntrada->DocumentoReferenciaNotaEntrada;
  }

  function InsertarDocumentoReferenciaNotaEntrada($data)
  {
    try {
      $dataParseada = $this->ParsearDataDocumentoReferenciaNotaEntrada($data);
      $resultado= $this->mDocumentoReferenciaNotaEntrada->InsertarDocumentoReferenciaNotaEntrada($dataParseada);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function ActualizarDocumentoReferenciaNotaEntrada($data)
  {
    try {
      $data["FechaEmision"]=convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);

      $resultado=$this->mDocumentoReferenciaNotaEntrada->ActualizarDocumentoReferenciaNotaEntrada($data);
      $IdDocumentoReferenciaNotaEntrada =$data["IdDocumentoReferenciaNotaEntrada"];
      $resultado["DetallesDocumentoReferenciaNotaEntrada"] = $this->sDetalleDocumentoReferenciaNotaEntrada->ActualizarDetallesDocumentoReferenciaNotaEntrada($IdDocumentoReferenciaNotaEntrada, $data["DetallesDocumentoReferenciaNotaEntrada"]);
      $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
      $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function BorrarDocumentoReferenciaNotaEntrada($data)
  {
    $this->mDocumentoReferenciaNotaEntrada->BorrarDocumentoReferenciaNotaEntrada($data);

    return "";
  }

  function BorrarDocumentosReferenciaNotaEntrada($data)
  {
    $resultado = $this->mDocumentoReferenciaNotaEntrada->ConsultarDocumentosReferenciaPorNotaEntrada($data);
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $this->BorrarDocumentoReferenciaNotaEntrada($value);
      }
    }
  }

  function ParsearDataDocumentoReferenciaNotaEntrada($data)
  {
    $dataParseada = $data;
    $dataParseada["IdDocumentoReferenciaNotaEntrada"] = "";

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
    $resultado = $this->mDocumentoReferenciaNotaEntrada->ObtenerDocumentosReferenciaByComprobanteVenta($data);
    return $resultado;
  }

  function ObtenerDocumentosReferenciaByComprobanteCompra($data)
  {
    $resultado = $this->mDocumentoReferenciaNotaEntrada->ObtenerDocumentosReferenciaByComprobanteCompra($data);
    return $resultado;
  }

  function ConsultarDocumentosReferencia($data)
  {
    $data["IdNotaEntrada"] = $data["IdNotaEntrada"];
    $resultado = $this->mDocumentoReferenciaNotaEntrada->ConsultarDocumentosReferenciaPorNotaEntrada($data);
    $response = array();
    if(count($resultado) > 0)
    {
      if(is_numeric($resultado[0]["IdComprobanteVenta"]))
      {
        $response = $this->ConsultarDocumentosReferenciaVenta($data);
      }
      else {
        $response = $this->ConsultarDocumentosReferenciaCompra($data);
      }
    }

    return $response;
  }

  function ConsultarDocumentosReferenciaVenta($data)
  {
    $data["IdNotaEntrada"] = $data["IdNotaEntrada"];
    $resultado = $this->mDocumentoReferenciaNotaEntrada->ConsultarDocumentosReferenciaVenta($data);
    foreach ($resultado as $key => $item) {
      $resultado[$key]["IdComprobante"] =$item["IdComprobanteVenta"];
      $resultado[$key]["Modulo"] =ID_MODULO_VENTA;
      $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] =convertirFechaES($resultado[$key]["FechaVencimiento"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesNotaEntrada"] =$this->sDetalleNotaEntrada->ConsultarDetallesComprobanteVenta($item, false);
    }
    return $resultado;
  }

  function ConsultarDocumentosReferenciaCompra($data)
  {
    $data["IdNotaEntrada"] = $data["IdNotaEntrada"];
    $resultado = $this->mDocumentoReferenciaNotaEntrada->ConsultarDocumentosReferenciaCompra($data);
    foreach ($resultado as $key => $item) {
      $resultado[$key]["IdComprobante"] =$item["IdComprobanteCompra"];
      $resultado[$key]["Modulo"] =ID_MODULO_COMPRA;
      $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] =convertirFechaES($resultado[$key]["FechaVencimiento"]);
      // $resultado[$key]["DetallesComprobanteVenta"] =[];
      $resultado[$key]["DetallesNotaEntrada"] =$this->sDetalleNotaEntrada->ConsultarDetallesComprobanteCompra($item, false);
    }
    return $resultado;
  }

}
