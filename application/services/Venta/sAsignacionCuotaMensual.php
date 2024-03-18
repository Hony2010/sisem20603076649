<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAsignacionCuotaMensual extends MY_Service {

  public $AsignacionCuotaMensual = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Base");
    $this->load->model('Venta/mAsignacionCuotaMensual');
    $this->AsignacionCuotaMensual = $this->mAsignacionCuotaMensual->AsignacionCuotaMensual;
  }

  function Cargar()
  {
    
  }

  /**INICIO B. FUNC. */
  function InsertarAsignacionCuotaMensual($data)
  {
    $resultado = $this->mAsignacionCuotaMensual->InsertarAsignacionCuotaMensual($data);
    return $resultado;
  }

  function ActualizarAsignacionCuotaMensual($data)
  {
    $resultado = $this->mAsignacionCuotaMensual->ActualizarAsignacionCuotaMensual($data);
    return $resultado;
  }

  function BorrarAsignacionCuotaMensual($data)
  {
    $resultado = $this->mAsignacionCuotaMensual->BorrarAsignacionCuotaMensual($data);
    return $resultado;
  }
  /**FIN B. FUNC. */

  function AgregarAsignacionCuotaMensual($data)
  {
    $resultado = $this->mAsignacionCuotaMensual->ObtenerAsignacionCuotaMensualPorIdUsuario($data);
    if(count($resultado) > 0)
    {
      $response = $this->ActualizarAsignacionCuotaMensual($data);
      return $response;
    }
    else
    {
      $data["IdAsignacionCuotaMensual"] = "";
      $response = $this->InsertarAsignacionCuotaMensual($data);
      return $response;
    }
  }

  //INSERTAR CUOTA MENSUAL POR USUARIO
  function AgregarAsignacionesCuotaMensual($data)
  {
    foreach ($data as $key => $value) {
      $data[$key] = $this->AgregarAsignacionCuotaMensual($value);
    }
    return $data;
  }

  //CONSULTAR CUOTAS MENSUALES POR USUARIO
  function ConsultarAsignacionesCuotaMensual($data)
  {
    $resultado = $this->mAsignacionCuotaMensual->ConsultarAsignacionesCuotaMensual($data);
    return $resultado;
  }

  //CONCULTA PARA EL REPORTE QUE SE DARA
  function ConsultarAsignacionesCuotaMensualParaReporte($data)
  {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    $resultado = $this->mAsignacionCuotaMensual->ConsultarAsignacionesCuotaMensual($data);
    $Porcentaje = (float) $this->sConstanteSistema->ObtenerParametroPorcentajePorFactura();
    foreach ($resultado as $key => $value) {
      $value["FechaInicio"] = $data["FechaInicio"];
      $value["FechaFin"] = $data["FechaFin"];
      $TotalComprobantes = $this->mAsignacionCuotaMensual->TotalComprobantesPorUsuario($value);
      $TotalFacturas = $this->mAsignacionCuotaMensual->TotalComprobantesPorUsuarioYFactura($value);      
      $TotalBoletas = $this->mAsignacionCuotaMensual->TotalComprobantesPorUsuarioYBoleta($value);
      
      // print_r($value);
      $resultado[$key]["TotalComprobantes"] = $TotalComprobantes[0]["Total"];
      $resultado[$key]["PorcentajeComprobante"] = $TotalComprobantes[0]["Total"] * $Porcentaje;

      if (count($TotalFacturas) > 0) {
        $resultado[$key]["TotalFacturas"] = $TotalFacturas[0]["Total"];
        $resultado[$key]["PorcentajeFactura"] = $TotalFacturas[0]["Total"] * $Porcentaje;
        $resultado[$key]["MaximoNumeroDocumentoFactura"] = $TotalFacturas[0]["MaximoNumeroDocumento"];
        $resultado[$key]["MinimoNumeroDocumentoFactura"] = $TotalFacturas[0]["MinimoNumeroDocumento"];
      }
      else {
        $TotalFacturas = $this->mAsignacionCuotaMensual->ObtenerUltimoNumeroDocumentoFactura($value);      
        $resultado[$key]["TotalFacturas"] = 0;
        $resultado[$key]["PorcentajeFactura"] = 0;
        $resultado[$key]["MaximoNumeroDocumentoFactura"] = $TotalFacturas[0]["MaximoNumeroDocumento"];
        $resultado[$key]["MinimoNumeroDocumentoFactura"] =  $TotalFacturas[0]["MaximoNumeroDocumento"];
      }
      
      if(count($TotalBoletas) > 0) {
        $resultado[$key]["TotalBoletas"] = $TotalBoletas[0]["Total"];
        $resultado[$key]["PorcentajeBoleta"] = $TotalBoletas[0]["Total"] * $Porcentaje;
        $resultado[$key]["MaximoNumeroDocumentoBoleta"] = $TotalBoletas[0]["MaximoNumeroDocumento"];
        $resultado[$key]["MinimoNumeroDocumentoBoleta"] = $TotalBoletas[0]["MinimoNumeroDocumento"];
      }
      else {
        $TotalBoletas = $this->mAsignacionCuotaMensual->ObtenerUltimoNumeroDocumentoBoleta($value);      
        $resultado[$key]["TotalBoletas"] = 0;
        $resultado[$key]["PorcentajeBoleta"] = 0;
        $resultado[$key]["MaximoNumeroDocumentoBoleta"] = $TotalBoletas[0]["MaximoNumeroDocumento"];
        $resultado[$key]["MinimoNumeroDocumentoBoleta"] = $TotalBoletas[0]["MaximoNumeroDocumento"];
      }
      
    }
    // exit;
    return $resultado;
  }
}
