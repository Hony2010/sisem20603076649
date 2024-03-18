<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sTicket extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();    
  }

  function CargarTicket()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_TICKET;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);
    $resultado["NuevaTicket"] = $resultado;
    return $resultado;
  }


}
