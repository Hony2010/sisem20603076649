<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shared {

  public $CI;

  function __construct()
  {
    //parent::__construct();
    /*Additional code which you want to run automatically in every function call */
    if (!isset($this->CI))
    {
        $this->CI =& get_instance();
    }
    
    $this->CI->load->model("Base");

  }

  public function now()
  {
    return date("Y-m-d H:i:s");
  }

  public function today()
  {
    return date("Y-m-d");
  }

  public function upload_file($field,$config)
  {
    $config['allowed_types']        = PARAMETRO_TIPO_IMAGEN;
    $config['max_size']             = 1500;
    $config['max_width']            = 1920;
    $config['max_height']           = 1920;

    $upload_path=$config['upload_path'];
    $_file = $_FILES[$field];
    //print_r($_file['name']);
    $_filenames = str_replace(" ", "_", $_file['name']);

    if (is_file($upload_path.$_filenames))
      unlink($upload_path.$_filenames);

    //printf($upload_path.$_filenames);
    if (!is_dir($upload_path))
      mkdir($upload_path,0777);

        //mkdir($upload_path,0777);

    $this->CI->load->library('upload');
    $this->CI->upload->initialize($config);

    if(!$this->CI->upload->do_upload($field))
    {
        $error = array('error' => $this->CI->upload->display_errors());
        return $error;
    }
    else
    {
        $data = array('upload_data' => $this->CI->upload->data());
        return $data;
    }
  }

  function obtener_primer_dia_mes()
  {
    $hoy = $this->CI->Base->ObtenerFechaServidor("Y-m-d");
    $fecha = new DateTime($hoy);
    $fecha->modify('first day of this month');
    return $fecha->format('d/m/Y');//$fecha->format('d-m-Y'); // imprime por ejemplo: 01/12/2018
  }

  function obtener_ultimo_dia_mes()
  {
    $hoy = $this->CI->Base->ObtenerFechaServidor("Y-m-d");
    $fecha = new DateTime($hoy);
    $fecha->modify('last day of this month');
    return $fecha->format('d/m/Y');//$fecha->format('d-m-Y'); // imprime por ejemplo: 31/12/2012
  }

  function rellenar_ceros($valor, $longitud){
    $res = str_pad($valor, $longitud, '0', STR_PAD_LEFT);
    return $res;
  }

  function QuitarTildes($cadena)
  {
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return $texto;
  }

  function ValidarNuloNumericoYCero($data) {
    if ($data == null) {
      return true;
    }
    else{
      if( is_numeric($data) && $data ==0) {
        return true;
      }
      else {
        return false;
      }
    }

  }

  
function GetDeviceName() {
  if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
   $ip = $_SERVER["HTTP_CLIENT_IP"];
  }
  elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
   $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  }
  else {
   $ip = $_SERVER["REMOTE_ADDR"];
  }
  
  $host = gethostbyaddr($ip);
  return $host;
}

}
