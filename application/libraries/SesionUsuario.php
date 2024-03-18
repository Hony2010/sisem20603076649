<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SesionUsuario
{

  public $CI;

  function __construct()
  {
    if (!isset($this->CI)) {
      $this->CI = &get_instance();
    }
  }

  function obtener_sesion_nombre_usuario()
  {
    $nombreUsuario = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["NombreUsuario"];
    return $nombreUsuario;
  }

  function obtener_sesion_nombre_sede()
  {
    $nombreSede = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["NombreSede"];
    return $nombreSede;
  }

  function obtener_sesion_id_sede()
  {
    $idSede = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IdSede"];
    return $idSede;
  }

  function obtener_sesion_codigo_sede()
  {
    $codigoSede = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["CodigoSede"];
    return $codigoSede;
  }

  function obtener_alias_usuario()
  {
    $alias = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["AliasUsuarioVenta"];
    return $alias;
  }

  function obtener_sesion_id_usuario()
  {
    $idUsuario = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IdUsuario"];
    return $idUsuario;
  }

  function obtener_sesion_id_rol()
  {
    $idUsuario = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IdRol"];
    return $idUsuario;
  }

  function obtener_sesion_vista_venta_usuario()
  {
    $indicadorvista = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IndicadorVerTodasVentas"];
    return $indicadorvista;
  }

  function obtener_sesion_vista_combo_venta_usuario()
  {
    $indicadorvistacombo = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IndicadorVerComboVentas"];
    return $indicadorvistacombo;
  }

  function obtener_sesion_turno_usuario()
  {
    $nombreUsuario = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["Turno"];
    return $nombreUsuario;
  }

  function obtener_sesion_zona()
  {
    $nombreUsuario = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["NombreZona"];
    return $nombreUsuario;
  }

  function obtener_sesion_indicador_vista_previa_impresion()
  {
    $nombreUsuario = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IndicadorVistaPreviaImpresion"];
    return $nombreUsuario;
  }

  function obtener_sesion_parametro_caja()
  {
    $nombreUsuario = $this->CI->session->userdata("Parametro_" . LICENCIA_EMPRESA_RUC)["ParametroCaja"];
    return $nombreUsuario;
  }

  function obtener_sesion_indicador_vista_precio_minimo() {
    $resultado = $this->CI->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)["IndicadorVistaPrecioMinimo"];
    
    return $resultado;
  }
}
