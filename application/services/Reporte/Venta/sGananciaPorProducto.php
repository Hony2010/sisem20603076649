<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sGananciaPorProducto extends MY_Service {

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

          function NombreJasper()
          {
            $parametro = $this->sConstanteSistema->ObtenerParametroPrecioCompra();
            $parametro2 = $this->ObtenerParametroGanancia();

            if ($parametro == 1) {
              return $this->ObtenerNombreArchivoJasperPorPrecioCompra();
            } else {
              if ($parametro2 == 0) {
                return $this->ObtenerNombreArchivoJasper();
              } else if ($parametro2 == 1) {
                return $this->ObtenerNombreArchivoJasperPorPrecio();
              } else {
                return $this->ObtenerNombreArchivoJasper();
              }
            }
          }

          function GenerarReportePDF($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
            $data["NombreArchivoJasper"] = $this->NombreJasper();
            if ($data['Costos'] == 1) {
              $resultadoalamcen= $this->sMovimientoAlmacen->CalcularProductosPorMovimientoAlmacen($data);
            }
            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
            $data["NombreArchivoJasper"] = $this->NombreJasper();
            if ($data['Costos'] == 1) {
              $resultadoalamcen= $this->sMovimientoAlmacen->CalcularProductosPorMovimientoAlmacen($data);
            }
            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
            $data["NombreArchivoJasper"] = $this->NombreJasper();
            if ($data['Costos'] == 1) {
              $resultadoalamcen= $this->sMovimientoAlmacen->CalcularProductosPorMovimientoAlmacen($data);
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

            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_GANANCIA_POR_PRODUCTO;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_GANANCIA_POR_PRODUCTO;
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

          function ObtenerParametroGanancia()
          {
            $data['IdParametroSistema']= ID_GANANCIA_PRODUCTO_PRECIO_VENTA;
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

          function ObtenerNombreArchivoJasperPorPrecio()
          {
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_GANANCIA_POR_PRODUCTO_PRECIO_VENTA;
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

          function ObtenerNombreArchivoJasperPorPrecioCompra()
          {
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_GANANCIA_POR_PRODUCTO_PRECIO_COMPRA;
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
