<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sBoletaViajeVenta extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();
  }

  function CargarBoletaViaje()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_BOLETA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $parametro['IdTipoVenta'] = TIPO_VENTA_SERVICIOS;

    $resultado = parent::Cargar($parametro);
    $resultado["IndicadorBoletaViaje"] = 1;
    return $resultado;
  }


}
