<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sProductosPorFamilia extends MY_Service {

          public $ProductosPorFamilia = array();

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
                $this->load->service('Configuracion/General/sParametroSistema');
          }

          function GenerarReportePDF($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_PF"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_PF"]);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_PF"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_PF"];

            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_PF"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_PF"]);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_PF"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_PF"];

            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_PF"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_PF"]);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_PF"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_PF"];

            $resultado = $this->mReporteBasePANTALLA->ReporteBasePANTALLA($data);
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

          function ObtenerNombreArchivoReporte()
          {
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_PRODUCTOS_POR_FAMILIA;

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

          function ObtenerNombreArchivoJasper()
          {
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_PRODUCTOS_POR_FAMILIA;
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
