<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mReporteBasePANTALLA extends CI_Model {

        public $ReporteBasePANTALLA = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
        }

      function ReporteBasePANTALLA($data)
      {

        $filename= $data["NombreArchivoReporte"].'.pdf';

        require_once(RUTA_LIBRERIA_REPORTES_TOMCAT_JAVA_INC);//ruta del Java.inc
        require(APP_PATH.'application/libraries/JasperReport/php-jru/php-jru.php');//solo indica la ruta de php-jru.php
        $jru=new PJRU();
        $Reporte=APP_PATH.'application/libraries/JasperReport/jasper/'.$data['NombreArchivoJasper'].'.jasper';// es la ubicacion del JASPER
        $SalidaReporte=APP_PATH."assets/reportes/".$filename; //la ruta debe ser donde se encuentra este archivo PHP
        //se genera el reporte segun la ruta que esta configurada en el int explorer
        $Parametro=new java('java.util.HashMap');

        foreach ($data as $key => $item)
        {
          if (count($data) > 0)
          {
            $Parametro -> put($key,$item);
          }
          else
          {
            return "Consulta mal realizado";
          }
        }

        $Conexion=new JdbcConnection("com.mysql.jdbc.Driver",RUTA_BD_JBDC_MYSQL,USUARIO_BD_JBDC_MYSQL,CLAVE_BD_JBDC_MYSQL);
        $jru->runReportToPdfFile($Reporte,$SalidaReporte,$Parametro, $Conexion->getConnection());

      }
    }
