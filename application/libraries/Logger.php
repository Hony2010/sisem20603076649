<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logger {

        public function EscribirLog($encabezado = null, $cuerpo = null, $pie = null) {
          $texto_log = "[".date("Y-m-d H:i:s.u")." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['SERVER_NAME']." - $encabezado ] ";
          $texto_log .= $cuerpo;
          $texto_log .= " (".$_SERVER['SERVER_ADDR']." - ".$pie." - ".$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'].")";
          $arch = fopen(APP_PATH."assets/data/facturacionelectronica/error/error.log", "a+");
        	fwrite($arch, $texto_log."\n");
        	fclose($arch);
        }

        public function CrearLog($data) {
          $now = DateTime::createFromFormat('U.u', microtime(true));
          $fecha = (String) $now->format("Y-m-d H:i:s.u");
          $texto_log = "[".$fecha." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['SERVER_NAME']." - ".$data["header"]." ] ";
          $texto_log .= $data["body"];
          $texto_log .= " (".$_SERVER['SERVER_ADDR']." - ".$data["footer"]." - ".$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'].")";
          $arch = fopen($data["url"].$data["name"], "a+");
        	fwrite($arch, $texto_log."\n");
        	fclose($arch);
        }

}
