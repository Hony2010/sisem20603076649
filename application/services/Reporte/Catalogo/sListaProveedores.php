<?php

  if (! defined ('BASEPATH')) exit ('No direct script access allowed');

  class sListaProveedores extends MY_Service {

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

          function GenerarReporteEXCEL($data) {
            $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
            return $resultado;
          }

          function GenerarReportePDF($data) {
            $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
            return $resultado;
          }

          function GenerarReportePANTALLA($data)
          {
            $resultado = $this->mReporteBasePANTALLA->ReporteBasePANTALLA($data);
            return $resultado;
          }

          function DescargarArchivo($data)
          {
            $resultado = $this->mDescargarArchivo->DescargarArchivo($data);
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

            $data['IdParametroSistema']= NOMBRE_ARCHIVO_REPORTE_LISTA_PROVEEDORES;

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
            $data['IdParametroSistema']= NOMBRE_ARCHIVO_JASPER_LISTA_PROVEEDORES;
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
