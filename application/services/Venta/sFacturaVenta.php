<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sFacturaVenta extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();
  }

  function CargarVenta()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);
    
    $resultado["IndicadorBoletaViaje"] = ($resultado["ParametroTransporte"] == 1) ? 1 : 0;
    return $resultado;
  }


}
