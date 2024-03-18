<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sComanda extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();    
  }

  function CargarComanda()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_COMANDA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);
    $resultado["NuevaComanda"] = $resultado;
    return $resultado;
  }


}
