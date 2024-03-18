<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDescargarArchivo extends CI_Model {

        public $DescargarArchivo = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
        }

        function DescargarArchivo($SalidaReporte)
        {
          if (file_exists($SalidaReporte))
          {
            // header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($SalidaReporte).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Content-Length: ' . filesize($SalidaReporte));
            ob_end_clean();
            readfile($SalidaReporte);
            unlink($SalidaReporte); //evita que salga doble reporte
            exit;
          }
        }
    }
