<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sVentaGeneral extends MY_Service
{

  public $VentaGeneral = array();

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
    $this->load->service('Configuracion/General/sConstanteSistema');
  }


  function GenerarReportePDF($data)
  {
    $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
    $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
    $data["TiposDocumento"] = ($data["TiposDocumento"] > 0) ? "'" . implode("','", $data["TiposDocumento"]) . "'" : "";
    $ParametroHoraReporte = $this->sConstanteSistema->ObtenerParametroHoraReporte();
    if ($ParametroHoraReporte == 1) {
      $data["FechaInicial"] = $data["FechaInicial"] . " " . convertToTime($data["HoraInicio"]);
      $data["FechaFinal"] = $data["FechaFinal"] . " " . convertToTime($data["HoraFinal"]);
    }

    $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
    return $resultado;
  }

  function GenerarReporteEXCEL($data)
  {
    $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
    $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
    $data["TiposDocumento"] = ($data["TiposDocumento"] > 0) ? "'" . implode("','", $data["TiposDocumento"]) . "'" : "";
    $ParametroHoraReporte = $this->sConstanteSistema->ObtenerParametroHoraReporte();
    if ($ParametroHoraReporte == 1) {
      $data["FechaInicial"] = $data["FechaInicial"] . " " . convertToTime($data["HoraInicio"]);
      $data["FechaFinal"] = $data["FechaFinal"] . " " . convertToTime($data["HoraFinal"]);
    }

    $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
    return $resultado;
  }

  function GenerarReportePANTALLA($data)
  {
    $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
    $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
    $data["TiposDocumento"] = ($data["TiposDocumento"] > 0) ? "'" . implode("','", $data["TiposDocumento"]) . "'" : "";
    $ParametroHoraReporte = $this->sConstanteSistema->ObtenerParametroHoraReporte();
    if ($ParametroHoraReporte == 1) {
      $data["FechaInicial"] = $data["FechaInicial"] . " " . convertToTime($data["HoraInicio"]);
      $data["FechaFinal"] = $data["FechaFinal"] . " " . convertToTime($data["HoraFinal"]);
    }

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

    $data['IdParametroSistema'] = NOMBRE_ARCHIVO_REPORTE_RESUMIDO;

    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $rucempresa . '-' . $ValorParametroSistema;
    }
  }

  function ObtenerNombreArchivoJasper()
  {
    $data['IdParametroSistema'] = NOMBRE_ARCHIVO_JASPER_RESUMIDO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
}
