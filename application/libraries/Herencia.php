<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Herencia {

  public function Heredar($origen,$destino)
  {
    $resultado = $destino;

    foreach($origen as $key=>$value)
    {
      if(!array_key_exists($key,$destino))
      {
            $resultado[$key] = $origen[$key];
      }
    }

    return $resultado;
  }

}
