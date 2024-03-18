<?php

$config['const_sisem_seguridad']=array();

defined('ID_ROL_CLIENTE')       OR define('ID_ROL_CLIENTE',21);
defined('ID_ROL_PROVEEDOR')       OR define('ID_ROL_PROVEEDOR',22);
defined('ID_ROL_ADMINISTRADOR')       OR define('ID_ROL_ADMINISTRADOR',3);
defined('ID_ROL_VENDEDOR')       OR define('ID_ROL_VENDEDOR',4);
defined('ID_ROL_CAJERO')       OR define('ID_ROL_CAJERO',24);

defined('TIPO_ACCESO_OPCION_PUBLICO') OR define('TIPO_ACCESO_OPCION_PUBLICO',0);
defined('TIPO_ACCESO_OPCION_PRIVADO') OR define('TIPO_ACCESO_OPCION_PRIVADO',1);
defined('TIPO_ACCESO_OPCION_PROTEGIDO') OR define('TIPO_ACCESO_OPCION_PROTEGIDO',2);

defined('ESTADO_OPCION_USUARIO_HABILITADO') OR define('ESTADO_OPCION_USUARIO_HABILITADO','1');
defined('ESTADO_OPCION_USUARIO_INHABILITADO') OR define('ESTADO_OPCION_USUARIO_INHABILITADO','0');
defined('NOMBRE_DISPOSITIVO') OR define('NOMBRE_DISPOSITIVO', $_SERVER['HTTP_USER_AGENT']);
defined('PARAMETRO_MODULOS_SISTEMA') OR define('PARAMETRO_MODULOS_SISTEMA',serialize(
  array(
    array('IdModuloSistema' => 1, 'NombreModuloSistema' => "CATALOGO",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 2,'NombreModuloSistema' => "VENTA",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 3,'NombreModuloSistema' => "COMPROBANTE ELECTRONICO",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 4,'NombreModuloSistema' => "INVENTARIO",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 5,'NombreModuloSistema' => "COMPRA",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 6,'NombreModuloSistema' => "CONFIGURACION",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 7,'NombreModuloSistema' => "SEGURIDAD",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 8,'NombreModuloSistema' => "CAJA",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 9,'NombreModuloSistema' => "CUENTAS POR COBRAR",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 10,'NombreModuloSistema' => "CUENTAS POR PAGAR",'IndicadorEstado' => "A"),
    array('IdModuloSistema' => 11,'NombreModuloSistema' => "AYUDA",'IndicadorEstado' => "A")
  )
));
