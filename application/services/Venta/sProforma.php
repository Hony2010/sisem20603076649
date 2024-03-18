<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'services\Venta\sComprobanteVenta.php');

class sProforma extends sComprobanteVenta
{
  public function __construct()
  {

    parent::__construct();
  }

  function CargarProforma()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_PROFORMA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);

    return $resultado;
  }

  /*****/
  function InsertarProforma($data)
  {
    $resultado = parent::InsertarComprobanteVenta($data);
    if (is_array($resultado)) {

    } 
    return $resultado;
  }

  function ActualizarProforma($data)
  {
    $resultado = parent::ActualizarComprobanteVenta($data);
    if (is_array($resultado)) {
      
    } 
    return $resultado;
  }

  function BorrarProforma($data)
  {
    $resultado = parent::BorrarComprobanteVenta($data);
    if (is_array($resultado)) {

    }
    return $resultado;
  }

  function AnularProforma($data)
  {
    $resultado = parent::AnularComprobanteVenta($data);
    if (is_array($resultado)) {

    }
    return $resultado;
  }

}
