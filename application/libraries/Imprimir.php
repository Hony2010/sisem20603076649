<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imprimir {

        public function ImprimirPDF($data)
        {
          $printer = $data["impresora"];
          $archivo = $data["archivo"];
          if($ph = printer_open($printer))
          {
           // Get file contents
           $fh = fopen($archivo, "rb");
           $content = fread($fh, filesize($archivo));
           fclose($fh);

           // Set print mode to RAW and send PDF to printer

           printer_set_option($ph, PRINTER_MODE, "RAW");
           printer_write($ph, $content);
           printer_close($ph);
          }
          else "Couldn't connect...";

          return "";
        }

        public function ObtenerImpresoraPredeterminada()
        {
          //print_r(printer_list(PRINTER_ENUM_DEFAULT));
          return "PDFCreator";
        }

}
