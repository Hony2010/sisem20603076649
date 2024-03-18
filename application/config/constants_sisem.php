<?php

$config['const_sisem']=array();

defined('SISEM_DOCUMENT_ROOT') OR define('SISEM_DOCUMENT_ROOT',config_item("SISEM_DOCUMENT_ROOT"));//20449458436
defined('APP_NAME') OR define('APP_NAME',config_item('APP_NAME'));//20449458436
defined('HOST_NAME') OR define('HOST_NAME',config_item("HOST_NAME"));
defined('HOST_PORT') OR define('HOST_PORT',config_item("HOST_PORT"));
defined('EXTEND_APP_NAME') OR define('EXTEND_APP_NAME',config_item("EXTEND_APP_NAME"));
defined('APP_NAME_SERVER') OR define('APP_NAME_SERVER',config_item('APP_NAME_SERVER'));//20449458436
defined('USUARIO_SOL') OR define('USUARIO_SOL',config_item('USUARIO_SOL'));//20449458436

defined('DATABASE_NAME') OR define('DATABASE_NAME',config_item("DATABASE_NAME"));//20449458436
defined('DATABASE_HOST_NAME') OR define('DATABASE_HOST_NAME',config_item("DATABASE_HOST_NAME"));//localhost
defined('DATABASE_PORT') OR define('DATABASE_PORT',config_item("DATABASE_PORT"));//localhost
defined('NOMBRE_DRIVER_JBDC_MYSQL') OR define('NOMBRE_DRIVER_JBDC_MYSQL',"com.mysql.jdbc.Driver");
defined('USUARIO_BD_JBDC_MYSQL') OR define('USUARIO_BD_JBDC_MYSQL',config_item("DATABASE_USER"));
defined('CLAVE_BD_JBDC_MYSQL') OR define('CLAVE_BD_JBDC_MYSQL',config_item("DATABASE_PASS"));
defined('RUTA_BD_JBDC_MYSQL') OR define('RUTA_BD_JBDC_MYSQL',"jdbc:mysql://".DATABASE_HOST_NAME.":".DATABASE_PORT."/".DATABASE_NAME);

defined('PARAMETRO_SERVIDOR_CLIENTE') OR define('PARAMETRO_SERVIDOR_CLIENTE',config_item("PARAMETRO_SERVIDOR_CLIENTE"));
defined('NOMBRE_SERVIDOR') OR define('NOMBRE_SERVIDOR',config_item("NOMBRE_SERVIDOR"));
defined('PUERTO_SERVIDOR') OR define('PUERTO_SERVIDOR',config_item("PUERTO_SERVIDOR"));

if(PARAMETRO_SERVIDOR_CLIENTE == 1) {
  /*PARA EL SERVIDOR*/
  defined('XAMPP_PATH') OR define('XAMPP_PATH',dirname(SISEM_DOCUMENT_ROOT)."/");//$_SERVER["DOCUMENT_ROOT"]
  // defined('TOMCAT_PATH') OR define('TOMCAT_PATH',XAMPP_PATH."tomcat/");
  defined('APP_PATH') OR define('APP_PATH',XAMPP_PATH."htdocs/".APP_NAME."/");
  defined('BASE_PATH') OR define('BASE_PATH',APP_PATH);
  defined('APP_PATH_URL') OR define('APP_PATH_URL',"http://".HOST_NAME.":".HOST_PORT."/".APP_NAME."/");//$_SERVER["HTTP_HOST"]
  // defined('APP_SITE_URL_SERVER') OR define('APP_SITE_URL_SERVER',APP_BASE_URL."/index.php");
  defined('XAMPP_PATH_CLIENTE') OR define('XAMPP_PATH_CLIENTE',XAMPP_PATH);
  defined('APP_PATH_CLIENTE') OR define('APP_PATH_CLIENTE',APP_PATH);
}
else {
  /*PARA EL CLIENTE*/
  defined('SERVER_NAME') OR define('SERVER_NAME',"\\\\".NOMBRE_SERVIDOR."\\xampp");
  defined('APP_PATH_SERVER_URL') OR define('APP_PATH_SERVER_URL',"http://".NOMBRE_SERVIDOR.":".PUERTO_SERVIDOR."/".APP_NAME_SERVER."/");
  defined('APP_PATH_SERVER_REAL') OR define('APP_PATH_SERVER_REAL',"http://".NOMBRE_SERVIDOR.":".PUERTO_SERVIDOR."/");
  defined('XAMPP_PATH') OR define('XAMPP_PATH',SERVER_NAME."\\");
  defined('XAMPP_PATH_CLIENTE') OR define('XAMPP_PATH_CLIENTE',dirname(SISEM_DOCUMENT_ROOT)."/");
  // defined('TOMCAT_PATH') OR define('TOMCAT_PATH',XAMPP_PATH."tomcat\\");
  defined('APP_PATH') OR define('APP_PATH',XAMPP_PATH."htdocs\\".APP_NAME_SERVER."\\");
  defined('APP_PATH_CLIENTE') OR define('APP_PATH_CLIENTE',XAMPP_PATH_CLIENTE."htdocs/".APP_NAME."/");
  defined('BASE_PATH') OR define('BASE_PATH',APP_PATH);
  defined('APP_PATH_URL') OR define('APP_PATH_URL',"http://".NOMBRE_SERVIDOR.":".PUERTO_SERVIDOR."/".APP_NAME_SERVER."/");
  defined('APP_SITE_URL_SERVER') OR define('APP_SITE_URL_SERVER',APP_PATH_URL."index.php");
}

defined('XAMPP_PATH_REAL') OR define('XAMPP_PATH_REAL',dirname(SISEM_DOCUMENT_ROOT)."/");//dirname($_SERVER["DOCUMENT_ROOT"])
defined('APP_PATH_XAMPP_REAL') OR define('APP_PATH_XAMPP_REAL',XAMPP_PATH_REAL."htdocs/".APP_NAME."/");
defined('APP_PATH_HOST') OR define('APP_PATH_HOST',APP_PATH_URL.'Index.php/');
defined('TOMCAT_PATH') OR define('TOMCAT_PATH',XAMPP_PATH_REAL."tomcat/");

defined('APP_PATH_REAL') OR define('APP_PATH_REAL',"http://".HOST_NAME.":".HOST_PORT."/");//$_SERVER["HTTP_HOST"]
defined('RUTA_LIBRERIA_REPORTES_TOMCAT_JAVA_INC') OR define('RUTA_LIBRERIA_REPORTES_TOMCAT_JAVA_INC',TOMCAT_PATH."webapps/JavaBridge/java/Java.inc");//TOMCAT_PATH
defined('RUTA_IMAGENES') OR define('RUTA_IMAGENES',"http://".XAMPP_PATH.APP_NAME."/"."assets/img/");
defined('RUTA_CARPETA_REPORTES') OR define('RUTA_CARPETA_REPORTES',APP_PATH_CLIENTE.'application/libraries/JasperReport/jasper/');//APP_PATH_CLIENTE
defined('RUTA_CARPETA_REPORTES_GENERADOS_PDF') OR define('RUTA_CARPETA_REPORTES_GENERADOS_PDF',APP_PATH."assets/reportes/Venta/");
defined('DIR_ROOT_ASSETS')       OR define('DIR_ROOT_ASSETS',APP_PATH."assets");
defined('DIR_ROOT_ASSETS_CLIENTE')       OR define('DIR_ROOT_ASSETS_CLIENTE',APP_PATH_CLIENTE."assets");
