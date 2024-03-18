<?php
require_once(RUTA_LIBRERIA_REPORTES_TOMCAT_JAVA_INC);//ruta del Java.inc
require(APP_PATH_XAMPP_REAL.'application/libraries/JasperReport/php-jru/php-jru.php');//solo indica la ruta de php-jru.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporter {

        public $RutaReporte;
        public $RutaPDF;
        public $_parametros;
        public $NombreImpresora;
        public $CI;

        function __construct()
        {
            if (!isset($this->CI))
            {
                $this->CI =& get_instance();
            }

            $this->CI->load->helper('download');
            //$this->_rutareporte =$data["rutareporte"];
        }

        protected function Conectar()
        {
          $conexion=new JdbcConnection(NOMBRE_DRIVER_JBDC_MYSQL,RUTA_BD_JBDC_MYSQL,USUARIO_BD_JBDC_MYSQL,CLAVE_BD_JBDC_MYSQL);
          return $conexion;
        }

        public function SetearParametros($parametros)
        {
          $this->_parametros = new java('java.util.HashMap');

          if (count($parametros) > 0)
          {
            foreach ($parametros as $key => $item)
            {
              $this->_parametros->put($key,$item);
            }
            return "";
          }
          else
          {
            return "Consulta mal realizado";
          }
        }

        public function GenerarReporteComoPDF()
        {
          try
          {
            $jru = new PJRU();
            $conexion = $this->Conectar();
            $resultado =$jru->runReportToPdfFile($this->RutaReporte,$this->RutaPDF,$this->_parametros, $conexion->getConnection());

            if ($resultado == false)
            {
              throw new Exception($jru->errorTrace);
            }

            return $resultado;
          }
          catch (Exception $e)
          {
            throw new Exception($e);
          }
        }

        public function Imprimir($NombreImpresora=null) {
          try {
            $jru = new PJRU();
            $conexion = $this->Conectar();

            if ($NombreImpresora==null) {
              $resultado = $jru->getPrinterNameDefault();
              if ($resultado === false) throw new Exception($jru->errorTrace);
              if ($resultado === "") throw new Exception("No se encontró impresora predeterminada, favor de revisar");
              $this->NombreImpresora = $resultado;
            }
            else {
              $this->NombreImpresora = $NombreImpresora;
            }
            
            $jru->setPrinterName($this->NombreImpresora);

            $resultado =$jru->printReport($this->RutaReporte,$this->_parametros,$conexion->getConnection());

            if ($resultado == false) {
              echo $jru->errorTrace;
              throw new Exception($jru->errorTrace);
            }

            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e);
          }
        }

        public function ExportarReporteComoPDF() {
          try {
            $resultado = $this->GenerarReporteComoPDF();
          }
          catch (Exception $e) {
            throw new Exception($e);
          }
        }
}
