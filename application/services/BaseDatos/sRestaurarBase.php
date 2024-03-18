<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sRestaurarBase extends MY_Service {

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->dbutil();
              $this->load->library('shared');
              $this->load->library('mapper');
              // $this->load->model('RestaurarBase/mRestaurarBase');
              // $this->RestaurarBase = $this->mRestaurarBase->RestaurarBase;
        }

        function Inicializar()
        {
          $restaurar["NombreArchivo"] = "";
          return $restaurar;
        }

        function RestaurarBase($data)
        {
          $filename = $data["NombreArchivo"];

          // Connect to MySQL server
          $mysqli = mysqli_connect(DATABASE_HOST_NAME, USUARIO_BD_JBDC_MYSQL, CLAVE_BD_JBDC_MYSQL) or die('Error connecting to MySQL server: ' . mysqli_error());

          // $mysqli->select_db(DATABASE_NAME) or die('Error selecting MySQL database: ' . mysqli_error());

          // Temporary variable, used to store current query
          $templine = '';
          // Read in entire file
          $lines = file($filename);

          // Loop through each line
          foreach ($lines as $line)
          {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
            continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            // print_r(substr(trim($line), -1, 1) == ';');exit;
            if (substr(trim($line), -1, 1) == ';')
            {
              // Perform the query
              mysqli_query($mysqli,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error() . '<br /><br />');
                // Reset temp variable to empty
                $templine = '';
              }
            }
            echo "todas las tablas importadas";
          }

          function generarbackupSQL()
          {
            $prefs = array(
              'tables'        => array(),   // Listado de tablas.
              'ignore'        => array(),                     // Listado de tablas a omitir
              'format'        => 'sql',                       // gzip, zip, txt
              // 'filename'      => 'nuevo.zip',                // Nombre del fichero - SOLAMENTE PARA FICHEROS ZIP
              'add_drop'      => TRUE,                        // Si agregar la sentencia DROP TABLE al backup
              'add_insert'    => TRUE,                        // Si agregar la sentencia INSERT al backup
              'newline'       => "\n"                         // Salto de línea
            );
            $backup = $this->dbutil->backup($prefs);

            //Cargamos el helper file y generamos un fichero
            //Esta parte la usamos solo si deseamos guardarlo en servidor
            $this->load->helper('file');
            write_file('bk/nuevo.sql', $backup);

            //Cargamos el helper download y forzamos la descarga
            $this->load->helper('download');
            force_download('nuevo.sql', $backup);
          }


}
