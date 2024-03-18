<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sReporteDocumentosPorPagar extends MY_Service {

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->helper("date");
    $this->load->model('Reporte/mReporteBasePDF');
    $this->load->model('Reporte/mReporteBaseEXCEL');
    $this->load->model('Reporte/mReporteBasePANTALLA');
    $this->load->model('Reporte/mDescargarArchivo');
    $this->load->service("Configuracion/General/sEmpresa");
    $this->load->service('Configuracion/General/sParametroSistema');
  }


  function Cargar()
  {
    $filtro["FechaInicio"] = $this->obtener_primer_dia_mes();
    $filtro["FechaFinal"] = $this->obtener_ultimo_dia_mes();
    $filtro["NombreArchivoReporte"] = $this->ObtenerNombreArchivoReporte();
    $filtro["NombreArchivoJasper"] = $this->ObtenerNombreArchivoJasper();
    $filtro["IndicadorRadioBtnProveedor"] = "0";
    $filtro["IdProveedor"] = "";
    $data = array(
      "Filtro" => $filtro
      );

    return $data;
  }

  function GenerarReportePDF($data)
  {
    $data["FechaInicial"] = convertToDate($data["FechaInicio"]);
    $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
    $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
    return $resultado;
  }

  function GenerarReporteEXCEL($data)
  {
    $data["FechaInicial"] = convertToDate($data["FechaInicio"]);
    $data["FechaFinal"] = convertToDate($data["FechaFinal"]);

    $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
    return $resultado;
  }

  function GenerarReportePANTALLA($data)
  {
    $data["FechaInicial"] = convertToDate($data["FechaInicio"]);
    $data["FechaFinal"] = convertToDate($data["FechaFinal"]);

    $resultado = $this->mReporteBasePANTALLA->ReporteBasePANTALLA($data);
    return $resultado;
  }

  function DescargarArchivo($data)
  {
    $resultado = $this->mDescargarArchivo->DescargarArchivo($data);
    return $resultado;
  }

  function obtener_primer_dia_mes()
  {
    $resultado = $this->shared->obtener_primer_dia_mes();
    return $resultado;
  }

  function obtener_ultimo_dia_mes()
  {
    $resultado = $this->shared->obtener_ultimo_dia_mes();
    return $resultado;
  }

  private function ObtenerRucEmpresa()
  {
    $data["IdEmpresa"] = $this->sEmpresa->ObtenerIdEmpresa();
    $Empresas = $this->sEmpresa->ListarEmpresas($data);
    $ruc = $Empresas[0]['CodigoEmpresa'];
    return $ruc;
  }

  function ObtenerNombreArchivoReporte()
  {
    $rucempresa = $this->ObtenerRucEmpresa();

    $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_DOCUMENTOS_POR_PAGAR;

    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $rucempresa.'-'.$ValorParametroSistema ;
    }
  }

  function ObtenerNombreArchivoJasper()
  {
    $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_DOCUMENTOS_POR_PAGAR;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

}
