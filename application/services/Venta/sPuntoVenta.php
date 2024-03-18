<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sPuntoVenta extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();
  }

  function CargarPuntoVenta()
  {
    $parametro['IdTipoDocumento'] = "%";
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $resultado = parent::Cargar($parametro);
    return $resultado;
  }


}
