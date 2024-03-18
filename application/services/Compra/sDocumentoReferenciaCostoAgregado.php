<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDocumentoReferenciaCostoAgregado extends MY_Service {

  public $DocumentoReferenciaCostoAgregado = array();
  // public $DetalleDocumentoReferenciaCostoAgregado = array();

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
    $this->load->model('Compra/mDocumentoReferenciaCostoAgregado');

    $this->DocumentoReferenciaCostoAgregado = $this->mDocumentoReferenciaCostoAgregado->DocumentoReferenciaCostoAgregado;
  }

  function ConsultarDocumentosReferenciaCostoAgregado($data)
  {
    $data["IdComprobanteCostoAgregado"] = $data["IdComprobanteCompra"];
    $resultado = $this->mDocumentoReferenciaCostoAgregado->ConsultarDocumentosReferenciaCostoAgregado($data);
    foreach ($resultado as $key => $item) {
      $documento = explode('-',$item["Documento"]);      
      $resultado[$key]["SerieDocumento"] = $documento[1];
      $resultado[$key]["NumeroDocumento"] = $documento[2];
      $resultado[$key]["RazonSocial"] = $item["NombreProveedor"];
      $resultado[$key]["Porcentaje"] = ($item["PorcentajeDistribucion"] * 100)." %";
    }
    return $resultado;
  }
  
  function ObtenerMovimientoAlmacenPorCostoAgregadoComprobanteCompra($data)
  {
    $resultado = $this->mDocumentoReferenciaCostoAgregado->ObtenerMovimientoAlmacenPorCostoAgregadoComprobanteCompra($data);
    return $resultado;
  }

  function InsertarDocumentoReferenciaCostoAgregado($data)
  {
    try {
      $nueva_data = $this->ParsearDataDocumentoReferenciaCostoAgregado($data);
      
      $resultado= $this->mDocumentoReferenciaCostoAgregado->InsertarDocumentoReferenciaCostoAgregado($nueva_data);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function BorrarDocumentoReferenciaPorIdComprobanteCostoAgregado($data)
  {
    $resultado = $this->mDocumentoReferenciaCostoAgregado->BorrarDocumentoReferenciaPorIdComprobanteCostoAgregado($data);
    return $resultado;
  }

  function AnularDocumentosReferenciaPorIdComprobanteCostoAgregado($data)
  {
    $data["IndicadorEstado"] = ESTADO_ANULADO;
    $data["IdComprobanteCostoAgregado"] = $data["IdComprobanteCompra"];
    $resultado = $this->mDocumentoReferenciaCostoAgregado->ActualizarDocumentosReferenciaPorIdComprobanteCostoAgregado($data);
    return $resultado;
  }

  function ActivarDocumentosReferenciaPorIdComprobanteCostoAgregado($data)
  {
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdComprobanteCostoAgregado"] = $data["IdComprobanteCompra"];
    $resultado = $this->mDocumentoReferenciaCostoAgregado->ActualizarDocumentosReferenciaPorIdComprobanteCostoAgregado($data);
    return $resultado;
  }

  function ActualizarDocumentoReferenciaCostoAgregado($data)
  {
    try {
      $resultado= $this->mDocumentoReferenciaCostoAgregado->InsertarDocumentoReferenciaCostoAgregado($data);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  // function BorrarDocumentoReferenciaCostoAgregado($data)
  // {
  //   //regla : validar que al eliminar el comprobante no tenga facturacion electronica enviado.
  //   // $resultado =[];// $this->sComprobanteElectronico->ObtenerComprobanteElectronicoPorIdDocumentoReferenciaCostoAgregado($data);
  //   $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
  //   $resultado = $this->mDocumentoReferenciaCostoAgregado->ObtenerDocumentosReferenciaCostoAgregadoByComprobante($data);

  //   if(count($resultado) >0)
  //   {
  //     foreach ($resultado as $key => $value) {
  //       // code...
  //       $this->mDocumentoReferenciaCostoAgregado->BorrarDocumentoReferenciaCostoAgregado($value);
  //     }
  //   }

  //   return "";
  // }

  function ParsearDataDocumentoReferenciaCostoAgregado($data)
  {
    $otra_data = $data;
    $otra_data["IdDocumentoReferenciaCostoAgregado"] = "";
    // $otra_data["IdDetalleComprobanteCompra"] = $data["IdTipoDocumento"];
    $otra_data["Documento"] = $data["Documento"];
    $otra_data["SerieDocumentoReferenciaCostoAgregado"] = $data["SerieDocumento"];
    $otra_data["NumeroDocumentoReferenciaCostoAgregado"] = $data["NumeroDocumento"];
    $otra_data["FechaDocumentoReferenciaCostoAgregado"] = convertToDate($data["FechaEmision"]);
    $otra_data["TotalDocumentoReferenciaCostoAgregado"] = $data["Total"];    
    return $otra_data;
  }

}
