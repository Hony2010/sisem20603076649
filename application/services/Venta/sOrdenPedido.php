<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sOrdenPedido extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();
  }

  function CargarOrdenPedido()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_ORDEN_PEDIDO;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();

    $resultado = parent::Cargar($parametro);
    return $resultado;
  }


}
