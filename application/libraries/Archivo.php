<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archivo {

        public $CI;

        function __construct()
        {
            //parent::__construct();
            /*Additional code which you want to run automatically in every function call */
            if (!isset($this->CI))
            {
                $this->CI =& get_instance();
            }

            $this->CI->load->library('logger');

        }

        public function CopiarArchivo($url_origen, $url_destino)
        {
          try {
            if (!file_exists($url_origen)) {
              throw new Exception;
            }
            chmod ($url_origen,0777);
            copy($url_origen, $url_destino);
          } catch (Exception $e) {
            $this->CI->logger->EscribirLog("COPIA DE ARCHIVO FALLIDA", $e->getMessage()." | ".$e->getTraceAsString(), $e->getFile());
            return false;
          }
        }

        public function MoverArchivo($url_origen, $url_destino)
        {
          try {
            if (!file_exists($url_origen)) {
              throw new Exception;
            }
            chmod ($url_origen,0777);
            $this->CopiarArchivo($url_origen, $url_destino);
            $this->EliminarArchivo($url_origen);
          } catch (Exception $e) {
            $this->CI->logger->EscribirLog("MOVER ARCHIVO FALLIDO", $e->getMessage()." | ".$e->getTraceAsString(), $e->getFile());
            return false;
          }
        }

        public function EliminarArchivo($url_origen)
        {
          try {
            if (!file_exists($url_origen)) {
              throw new Exception;
            }
            chmod ($url_origen,0777);
            unlink($url_origen);
            return true;
          } catch (Exception $e) {
            $this->CI->logger->EscribirLog("ELIMINAR DE ARCHIVO FALLIDA", $e->getMessage()." | ".$e->getTraceAsString(), $e->getFile());
            return false;
          }
        }

}
