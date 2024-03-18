<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sStockProductoMarca extends MY_Service {

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
                $this->load->model('Reporte/mDescargarArchivo');
                $this->load->service("Inventario/sMovimientoAlmacen");
                $this->load->service("Configuracion/General/sEmpresa");
                $this->load->service('Configuracion/General/sParametroSistema');

          }

          function GenerarReportePDF($data)
          {
            $data["FechaDeterminada"] = convertToDate($data['Fecha'] == 0 ? $data["FechaActual"] : $data["FechaDeterminada"]);
            $data["NombreArchivoJasper"] = $data['Fecha'] == 0 ? $this->ObtenerNombreArchivoJasper() : $this->ObtenerSegundoNombreArchivoJasper();
            $data["TiposDocumento"] = ($data["TiposDocumento"] > 0) ? "'".implode("','", $data["TiposDocumento"])."'" : "";
            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($data)
          {
            $data["FechaDeterminada"] = convertToDate($data['Fecha'] == 0 ? $data["FechaActual"] : $data["FechaDeterminada"]);
            $data["NombreArchivoJasper"] = $data['Fecha'] == 0 ? $this->ObtenerNombreArchivoJasper() : $this->ObtenerSegundoNombreArchivoJasper();
            $data["TiposDocumento"] = ($data["TiposDocumento"] > 0) ? "'".implode("','", $data["TiposDocumento"])."'" : "";
            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $data["FechaDeterminada"] = convertToDate($data['Fecha'] == 0 ? $data["FechaActual"] : $data["FechaDeterminada"]);
            $data["NombreArchivoJasper"] = $data['Fecha'] == 0 ? $this->ObtenerNombreArchivoJasper() : $this->ObtenerSegundoNombreArchivoJasper();
            $data["TiposDocumento"] = ($data["TiposDocumento"] > 0) ? "'".implode("','", $data["TiposDocumento"])."'" : "";
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

            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_STOCK_PRODUCTO_MARCA;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_STOCK_PRODUCTO_MARCA;
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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_STOCK_PRODUCTO_MARCA_FECHA_DETERMINADA;
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
