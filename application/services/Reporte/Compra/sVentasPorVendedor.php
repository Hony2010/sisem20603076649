<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sVentasPorVendedor extends MY_Service {

          public $VentasPorVendedor = array();

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

          function IndicarValorCliente($data)
          {
            if ($data['NumeroDocumentoIdentidad_Vendedor'] == '0')
            {
              return $data['NumeroDocumentoIdentidad_Vendedor'] = '%';
            }
            else
            {
              if ($data['NumeroDocumentoIdentidad_Vendedor'] == '1')
              {
                return $data['NumeroDocumentoIdentidad_Vendedor'] = $data['TextoVendedor_Vendedor'];
              }
              else
              {
                  return "No hay valor en ordenado por";
              }
            }
          }

          function GenerarReportePDF($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_Vendedor"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_Vendedor"]);
            $data['Vendedor'] = $this->IndicarValorCliente($data);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_Vendedor"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_Vendedor"];

            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_Vendedor"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_Vendedor"]);
            $data['Vendedor'] = $this->IndicarValorCliente($data);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_Vendedor"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_Vendedor"];

            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_Vendedor"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_Vendedor"]);
            $data['Vendedor'] = $this->IndicarValorCliente($data);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_Vendedor"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_Vendedor"];

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_VENTAS_POR_VENDEDOR;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_VENTAS_POR_VENDEDOR;
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
