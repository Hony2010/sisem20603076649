<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sReporteMovimientoDocumentoDua extends MY_Service {

                public function __construct()
          {
                parent::__construct();
                $this->load->database();
                $this->load->model("Base");
                $this->load->library('shared');
                $this->load->library('mapper');
                $this->load->library('jsonconverter');
                $this->load->helper("date");
                $this->load->model('Reporte/mReporteBasePDF');
                $this->load->model('Reporte/mReporteBaseEXCEL');
                $this->load->model('Reporte/mReporteBasePANTALLA');
                $this->load->model('Reporte/mDescargarArchivo');
                $this->load->service("Inventario/sMovimientoDocumentoDua");
                $this->load->service("Configuracion/General/sEmpresa");
                $this->load->service('Configuracion/General/sParametroSistema');
                $this->load->model('Inventario/mDuaProducto');

          }

          function GenerarJsonDuaProductoPorFiltros($data)
          {
            $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
            $data["FechaFinal"] = convertToDate($data["FechaFinal"]);
            $resultado = $this->mDuaProducto->ListarDuasPruductoPorFiltros($data);
            $response = array();
            foreach ($resultado as $key => $value) {
              $nueva_fila = Array (
                "IdDuaProducto" => $value["IdDuaProducto"],
                "IdDua" => $value["IdDua"],
                "NumeroItemDua" => $value["NumeroItemDua"],
                "NumeroDua" => $value["NumeroDua"],
                "FechaEmisionDua" => convertirFechaES($value["FechaEmisionDua"])
              );
              array_push($response, $nueva_fila);
            }
            $url = DIR_ROOT_ASSETS.'/data/mercaderia/duaproducto.json';
            $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $response);
            // return $response;
          }

          function GenerarReportePDF($data)
          {
            $data["FechaInicial"] = convertToDate($data['FechaInicial']);
            $data["FechaFinal"] = convertToDate($data['FechaFinal']);
            $data["FechaMovimientoInicial"] = convertToDate($data['FechaMovimientoInicial']);
            $data["FechaMovimientoFinal"] = convertToDate($data['FechaMovimientoFinal']);
            if ($data['Saldos'] == 1) {
              $resultadoalamcen= $this->sMovimientoDocumentoDua->CalcularCantidadesProductoPorMovimientoDocumentoDua($data);
            }
            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReporteEXCEL($data)
          {
            $data["FechaInicial"] = convertToDate($data['FechaInicial']);
            $data["FechaFinal"] = convertToDate($data['FechaFinal']);
            $data["FechaMovimientoInicial"] = convertToDate($data['FechaMovimientoInicial']);
            $data["FechaMovimientoFinal"] = convertToDate($data['FechaMovimientoFinal']);
            if ($data['Saldos'] == 1) {
              $resultadoalamcen= $this->sMovimientoDocumentoDua->CalcularCantidadesProductoPorMovimientoDocumentoDua($data);
            }
            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $data["FechaInicial"] = convertToDate($data['FechaInicial']);
            $data["FechaFinal"] = convertToDate($data['FechaFinal']);
            $data["FechaMovimientoInicial"] = convertToDate($data['FechaMovimientoInicial']);
            $data["FechaMovimientoFinal"] = convertToDate($data['FechaMovimientoFinal']);
            if ($data['Saldos'] == 1) {
              $resultadoalamcen= $this->sMovimientoDocumentoDua->CalcularCantidadesProductoPorMovimientoDocumentoDua($data);
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

            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_MOVIMIENTO_DOCUMENTO_DUA;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_MOVIMIENTO_DOCUMENTO_DUA;
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
