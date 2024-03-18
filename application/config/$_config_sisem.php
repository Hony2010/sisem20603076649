<?php
//NO OFUSCAR
//configuracion de hosting
$config['SISEM_DOCUMENT_ROOT']="C:\\xampp\\htdocs";
$config['APP_NAME']="sisem";
$config["HOST_NAME"]="localhost";
$config["HOST_PORT"]="8099";
$config["EXTEND_APP_NAME"]="";

//configuracion de carpeta
$config['XAMPP_PATH']=dirname($config['SISEM_DOCUMENT_ROOT'])."/";
$config['TOMCAT_PATH']=$config['XAMPP_PATH']."tomcat/";
$config['APP_PATH']=$config['XAMPP_PATH']."htdocs/".$config['APP_NAME']."/";

//configuracion de base de datos
$config["DATABASE_NAME"]="sistemacomercialprueba";
$config["DATABASE_HOST_NAME"]="192.168.0.4";
$config["DATABASE_PORT"]="3306";
$config["DATABASE_USER"]="root";
$config["DATABASE_PASS"]="root";

//configuracion de servidor remoto
$config["PARAMETRO_SERVIDOR_CLIENTE"]=1;/*PARA EL SERVIDOR =1 ,CLIENTE=0*/
$config['APP_NAME_SERVER']="sisem";
$config["NOMBRE_SERVIDOR"]='localhost';
$config["PUERTO_SERVIDOR"]='8099';

$config["APP_BASE_URL"]='http://'.$config["HOST_NAME"].':'.$config["HOST_PORT"].'/'.$config['APP_NAME'].'/'.$config["EXTEND_APP_NAME"];
$config['base_url'] = $config["APP_BASE_URL"];

$config['USUARIO_SOL'] = "MODDATOS";
