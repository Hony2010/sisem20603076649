<?php
//NO OFUSCAR
//configuracion de hosting
$config['SISEM_DOCUMENT_ROOT']="VAR_SISEM_ROOT";
$config['APP_NAME']="VAR_SISEM_APP_NAME";
$config["HOST_NAME"]="VAR_SISEM_HOST_NAME";
$config["HOST_PORT"]="VAR_SISEM_HOST_PORT";
$config["EXTEND_APP_NAME"]="";

//configuracion de carpeta
$config['XAMPP_PATH']=dirname($config['SISEM_DOCUMENT_ROOT'])."/";
$config['TOMCAT_PATH']=$config['XAMPP_PATH']."tomcat/";
$config['APP_PATH']=$config['XAMPP_PATH']."htdocs/".$config['APP_NAME']."/";

//configuracion de base de datos
$config["DATABASE_NAME"]="VAR_SISEM_DATABASE_NAME";
$config["DATABASE_HOST_NAME"]="VAR_SISEM_DATABASE_HOST_NAME";
$config["DATABASE_PORT"]="VAR_SISEM_DATABASE_PORT";
$config["DATABASE_USER"]="VAR_SISEM_DATABASE_USER";
$config["DATABASE_PASS"]="VAR_SISEM_DATABASE_PASS";

//configuracion de servidor remoto
$config["PARAMETRO_SERVIDOR_CLIENTE"]=VAR_SISEM_PARAMETRO_SERVIDOR;/*PARA EL SERVIDOR =1 ,CLIENTE=0*/
$config["NOMBRE_SERVIDOR"]='VAR_SISEM_NOMBRE_SERVIDOR';
$config["PUERTO_SERVIDOR"]='VAR_SISEM_PUERTO_SERVIDOR';


$config["APP_BASE_URL"]='http://'.$config["HOST_NAME"].':'.$config["HOST_PORT"].'/'.$config['APP_NAME'].'/'.$config["EXTEND_APP_NAME"];
$config['base_url'] = $config["APP_BASE_URL"];
