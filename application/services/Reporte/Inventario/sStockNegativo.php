<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sStockNegativo extends MY_Service {

          public $VentaDetallado = array();

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
                $this->load->service("Configuracion/General/sEmpresa");
                $this->load->service('Configuracion/General/sParametroSistema');

          }

          function GenerarReportePDF($input)
          {
            $data["IdAsignacionSede"] = $input["IdAsignacionSede"];
            $data["IdProducto"] = $input["IdProducto"];
            $data["Fecha"] = convertToDate($input['Fecha_StockNegativo'] == 0 ? $input["FechaHoy_StockNegativo"] : $input["FechaDeterminada_StockNegativo"]);
            $data["FechaDeterminada"] = $input["FechaDeterminada_StockNegativo"];
            $data["Orden"] = $input["Orden"];
            $data["NombreArchivoReporte"] = $input["NombreArchivoReporte_StockNegativo"];
            $data["NombreArchivoJasper"] = $input['Fecha_StockNegativo'] == 0 ? $this->ObtenerSegundoNombreArchivoJasper() : $this->ObtenerNombreArchivoJasper();
            if ($input['Saldos'] == 1) {
              $resultadoalamcen= $this->sMovimientoAlmacen->CalcularCantidadesProductoPorMovimientoAlmacen($data);
            }
            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($input)
          {
            $data["IdAsignacionSede"] = $input["IdAsignacionSede"];
            $data["IdProducto"] = $input["IdProducto"];
            $data["Fecha"] = convertToDate($input['Fecha_StockNegativo'] == 0 ? $input["FechaHoy_StockNegativo"] : $input["FechaDeterminada_StockNegativo"]);
            $data["FechaDeterminada"] = $input["FechaDeterminada_StockNegativo"];
            $data["Orden"] = $input["Orden"];
            $data["NombreArchivoReporte"] = $input["NombreArchivoReporte_StockNegativo"];
            $data["NombreArchivoJasper"] = $input['Fecha_StockNegativo'] == 0 ? $this->ObtenerSegundoNombreArchivoJasper() : $this->ObtenerNombreArchivoJasper();
            if ($input['Saldos'] == 1) {
              $resultadoalamcen= $this->sMovimientoAlmacen->CalcularCantidadesProductoPorMovimientoAlmacen($data);
            }
            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($input)
          {
            $data["IdAsignacionSede"] = $input["IdAsignacionSede"];
            $data["IdProducto"] = $input["IdProducto"];
            $data["Fecha"] = convertToDate($input['Fecha_StockNegativo'] == 0 ? $input["FechaHoy_StockNegativo"] : $input["FechaDeterminada_StockNegativo"]);
            $data["FechaDeterminada"] = $input["FechaDeterminada_StockNegativo"];
            $data["Orden"] = $input["Orden"];
            $data["NombreArchivoReporte"] = $input["NombreArchivoReporte_StockNegativo"];
            $data["NombreArchivoJasper"] = $input['Fecha_StockNegativo'] == 0 ? $this->ObtenerSegundoNombreArchivoJasper() : $this->ObtenerNombreArchivoJasper();
            if ($input['Saldos'] == 1) {
              $resultadoalamcen= $this->sMovimientoAlmacen->CalcularCantidadesProductoPorMovimientoAlmacen($data);
            }
            $resultado = $this->mReporteBasePANTALLA->ReporteBasePANTALLA($data);
            return $resultado;
          }

          function DescargarArchivo($data)
          {
            $resultado = $this->mDescargarArchivo->DescargarArchivo($data);
            return $resultado;
          }

          function obtener_fecha_hoy()
          {
            $resultado = $this->shared->now();
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

            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_STOCK_NEGATIVO;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_STOCK_NEGATIVO;
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

          function ObtenerSegundoNombreArchivoJasper()
          {
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_STOCK_NEGATIVO;
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
