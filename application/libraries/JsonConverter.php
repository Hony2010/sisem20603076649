<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "JSONH/JSONH.class.php";

class JsonConverter
{

  public $CI;

  function __construct()
  {
    //parent::__construct();
    /*Additional code which you want to run automatically in every function call */
    if (!isset($this->CI)) {
      $this->CI = &get_instance();
    }

    // $this->CI->load->service('Configuracion/General/sEmpresa');
  }

  public function Convertir($data)
  {    
    $JSONH = new JSONH();
    $response = JSONH::stringify($data,JSON_HEX_AMP+JSON_HEX_APOS+JSON_HEX_QUOT);
    return $response;
  }

  function Decodificar($data, $isArray = false)
  {
    $JSONH = new JSONH();
    $response = JSONH::unpack($data, $isArray);
    return $response;
  }
  ////
  public function ObtenerDataArchivoJSON($url_archivo)
  {
    $archivo = $url_archivo;
    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
    if (file_exists($archivo)) {
      //echo "El fichero $archivo existe";
    } else {
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        //die("No se ha podido crear el archivo.");
        return "No se ha podido crear el archivo json.";
      }

      fclose($nuevo_archivo);
      //echo "El fichero $archivo no existe";
    }

    $datos_clientes = file_get_contents($archivo);
    $json_clientes = json_decode($datos_clientes, true);
    $json_clientes = $this->Decodificar($json_clientes, true);

    if ($json_clientes === null && json_last_error() !== JSON_ERROR_NONE) {
      return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
    } else if ($json_clientes == null) {
      $json_clientes = array();
    }

    return $json_clientes;
  }

  public function InsertarNuevaFilaEnArchivoJSON($url_archivo, $nueva_fila)
  {
    $archivo = $url_archivo;
    $fila = $nueva_fila;
    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
    if (file_exists($archivo)) {
      //echo "El fichero $archivo existe";
    } else {
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        //die("No se ha podido crear el archivo.");
        return "No se ha podido crear el archivo json.";
      }

      fclose($nuevo_archivo);
      //echo "El fichero $archivo no existe";
    }

    $datos_clientes = file_get_contents($archivo);
    $json_clientes = json_decode($datos_clientes, true);
    $json_clientes = $this->Decodificar($json_clientes, true);

    if ($json_clientes === null && json_last_error() !== JSON_ERROR_NONE) {
      return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
    } else if ($json_clientes == null) {
      $json_clientes = array();
    }

    if ($nueva_fila != null) {
      array_push($json_clientes, $fila);
    }

    //Creamos el JSON
    // $json_string = json_encode($json_clientes);
    $json_string = $this->Convertir($json_clientes);
    if ($json_string === false) {
      return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
    }

    $resultado = file_put_contents($archivo, $json_string);

    if ($resultado === false) {
      return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
    }

    return $fila;
  }

  public function ActualizarFilaEnArchivoJSON($url_archivo, $fila_a_cambiar, $id_attribute)
  {
    $archivo = $url_archivo;
    $fila = $fila_a_cambiar;
    $id_atributo = $id_attribute;
    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
    if (!file_exists($archivo)) {
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        die("No se ha podido crear el archivo.");
      }
      fclose($nuevo_archivo);
    }

    $datos_clientes = file_get_contents($archivo);
    $json_clientes = json_decode($datos_clientes, true);
    $json_clientes = $this->Decodificar($json_clientes, true);

    // print_r($json_clientes);
    // exit;
    if ($json_clientes === null && json_last_error() !== JSON_ERROR_NONE) {
      return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
    } else if ($json_clientes == null) {
      // return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
      $json_clientes = array();
    } else {

      $posicion = null;
      foreach ($json_clientes as $key => $value) {
        if ($json_clientes[$key][$id_atributo] == $fila[$id_atributo]) {
          $json_clientes[$key] = $fila;
          //$posicion = $key;
        }
      }
      //array_splice($json_clientes, $posicion, 1);
      //unset($json_clientes[$posicion]);
    }

    //Creamos el JSON
    // $json_string = json_encode($json_clientes);
    $json_string = $this->Convertir($json_clientes);
    // print_r($json_string);exit;
    if ($json_string === false) {
      return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
    }

    $resultado = file_put_contents($archivo, $json_string);

    if ($resultado === false) {
      return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
    }

    return $fila;
  }

  public function EliminarFilaEnArchivoJSON($url_archivo, $fila_a_eliminar, $id_attribute)
  {
    $archivo = $url_archivo;
    $fila = $fila_a_eliminar;
    $id_atributo = $id_attribute;
    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
    if (!file_exists($archivo)) {
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        die("No se ha podido crear el archivo.");
      }
      fclose($nuevo_archivo);
    }

    $datos_clientes = file_get_contents($archivo);
    $json_clientes = json_decode($datos_clientes, true);
    // print_r($json_clientes);
    // exit;
    $json_clientes = $this->Decodificar($json_clientes, true);

    if ($json_clientes === null && json_last_error() !== JSON_ERROR_NONE) {
      return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
    } else if ($json_clientes == null) {
      return "El array se encuentra vacio."; //$json_clientes = Array();
    } else {
      $posicion = null;
      foreach ($json_clientes as $key => $value) {
        if ($json_clientes[$key][$id_atributo] == $fila[$id_atributo]) {
          $posicion = $key;
        }
        // if($objeto[$key][$id_atributo] == $fila[$id_atributo])
        // {
        // 	$posicion = $key;
        // }
      }

      if (is_numeric($posicion)) {
        array_splice($json_clientes, $posicion, 1);
      }
      //unset($json_clientes[$posicion]);
    }

    //Creamos el JSON
    // $json_string = json_encode($json_clientes);
    $json_string = $this->Convertir($json_clientes);
    if ($json_string === false) {
      return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
    }

    $resultado = file_put_contents($archivo, $json_string);

    if ($resultado === false) {
      return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
    }

    return $fila;
  }

  //PARA MANEJAR VARIAS FILAS EN JSON
  public function EliminarFilasEnArchivoJSON($url_archivo, $fila_a_eliminar, $id_attribute)
  {
    $archivo = $url_archivo;
    $fila = $fila_a_eliminar;
    $id_atributo = $id_attribute;
    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
    if (!file_exists($archivo)) {
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        die("No se ha podido crear el archivo.");
      }
      fclose($nuevo_archivo);
    }

    $datos_clientes = file_get_contents($archivo);
    $json_clientes = json_decode($datos_clientes, true);
    // print_r($json_clientes);
    // exit;
    $json_clientes = $this->Decodificar($json_clientes, true);

    if ($json_clientes === null && json_last_error() !== JSON_ERROR_NONE) {
      return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
    } else if ($json_clientes == null) {
      return "El array se encuentra vacio."; //$json_clientes = Array();
    } else {
      // $posicion = null;
      $posiciones = array();
      foreach ($json_clientes as $key => $value) {
        if ($json_clientes[$key][$id_atributo] == $fila[$id_atributo]) {
          // $posicion = $key;
          array_push($posiciones, $key);
        }
      }

      //BORRANDO POSICIONES
      foreach ($posiciones as $key => $value) {
        if (is_numeric($value)) {
          // array_splice($json_clientes, $value, 1);
          unset($json_clientes[$value]);
        }
      }

      $json_clientes = array_values($json_clientes);
      //unset($json_clientes[$posicion]);
    }

    //Creamos el JSON
    // $json_string = json_encode($json_clientes);
    $json_string = $this->Convertir($json_clientes);
    if ($json_string === false) {
      return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
    }

    $resultado = file_put_contents($archivo, $json_string);

    if ($resultado === false) {
      return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
    }

    return $fila;
  }


  //OTRA FUNCION
  function CrearArchivoJSONData($url_archivo, $data)
  {
    $archivo = $url_archivo;
    $data_archivo = $data;
    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
    if (!file_exists($archivo)) {
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        die("No se ha podido crear el archivo.");
      }
      fclose($nuevo_archivo);
    } else {
      unlink($archivo);
      $nuevo_archivo = fopen($archivo, "w+");
      if ($nuevo_archivo == false) {
        die("No se ha podido crear el archivo.");
      }
      fclose($nuevo_archivo);
      // code...
    }

    //Creamos el JSON Convertido
    $json_string = $this->Convertir($data_archivo);
    if ($json_string === false) {
      return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
    }

    $resultado = file_put_contents($archivo, $json_string);

    if ($resultado === false) {
      return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
    }
    // print_r($json_string);
    // exit;
    // $this->gzCompressFile($archivo);
    return $data_archivo;
  }


  /**
   * GZIPs a file on disk (appending .gz to the name)
   *
   * From http://stackoverflow.com/questions/6073397/how-do-you-create-a-gz-file-using-php
   * Based on function by Kioob at:
   * http://www.php.net/manual/en/function.gzwrite.php#34955
   *
   * @param string $source Path to file that should be compressed
   * @param integer $level GZIP compression level (default: 9)
   * @return string New filename (with .gz appended) if success, or false if operation fails
   */
  function gzCompressFile($source, $level = 9)
  {
    $dest = $source . '.gz';
    $mode = 'wb' . $level;
    $error = false;
    if ($fp_out = gzopen($dest, $mode)) {
      if ($fp_in = fopen($source, 'rb')) {
        while (!feof($fp_in))
          gzwrite($fp_out, fread($fp_in, 1024 * 512));
        fclose($fp_in);
      } else {
        $error = true;
      }
      gzclose($fp_out);
    } else {
      $error = true;
    }
    if ($error)
      return false;
    else
      return $dest;
  }
}
