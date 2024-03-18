<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sFamiliasMasVendidos extends MY_Service {

          public $FamiliasMasVendidos = array();

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
                $this->load->model('Venta/mDetalleComprobanteVenta');//para contarcantidad de productos vendidos
                $this->load->service('Configuracion/General/sParametroSistema');

          }
          function TotalProductosVendido()
          {
            $resultado = $this->mDetalleComprobanteVenta->TotalProductosVendido();
            return $resultado[0]['total'];
          }

          function CantidadFilas($data)
          {
            $CantidadFila = $this->TotalProductosVendido();

            if ($data['CantidadFilas_Familia'] == '0')
            {
              return $data['CantidadFilas_Familia'] = 10;
            }
            else
            {
              if ($data['CantidadFilas_Familia'] == '1')
              {
                return $data['CantidadFilas_Familia']= (int)$CantidadFila;
              }
              else
              {
                  return "No hay valor en Cantidad de Productos a Mostrar";
              }
            }
          }

          function GenerarReportePDF($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_Familia"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_Familia"]);
            $data['CantidadFila'] = $this->CantidadFilas($data);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_Familia"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_Familia"];

            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_Familia"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_Familia"]);
            $data['CantidadFila'] = $this->CantidadFilas($data);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_Familia"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_Familia"];

            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicio_Familia"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal_Familia"]);
            $data['CantidadFila'] = $this->CantidadFilas($data);
            $data["NombreArchivoReporte"] = $data["NombreArchivoReporte_Familia"];
            $data["NombreArchivoJasper"] = $data["NombreArchivoJasper_Familia"];

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_FAMILIA_MAS_VENDIDO;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_FAMILIA_MAS_VENDIDO;
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
