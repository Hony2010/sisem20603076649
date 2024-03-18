<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coleccion {

  public function super_unique($array, $key)
  {
    $temp_array = array();
    
    foreach ($array as &$v) {
      if (!isset($temp_array[$v[$key]]))
      $temp_array[$v[$key]] =& $v;
    }
    $array = array_values($temp_array);
    return $array;
  }
}
