<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sBoletaTipoTVenta extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();
  }

  function CargarBoleta()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_BOLETA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $parametro['IdSubTipoDocumento'] = ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_T;
    $resultado = parent::Cargar($parametro);
    return $resultado;
  }


}
