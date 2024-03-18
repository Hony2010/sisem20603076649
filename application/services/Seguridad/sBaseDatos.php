<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sBaseDatos extends MY_Service {
  
      public function __construct() {
            parent::__construct();
            $this->load->database();
            $this->load->model("Base");
            $this->load->dbutil();
            $this->load->helper('download');
            $this->load->library('shared');
            $this->load->library('mapper');
            $this->load->library('json');
            $this->load->library('emailer');
            $this->load->service('Configuracion/General/sParametroSistema');
      }

      function GenerarBackup($data) {              
        $comando=RUTA_TAREA_GENERA_BACKUP_BASE_BATOS;
        $carpeta=RUTA_CARPETA_ASSETS_BASE_DATOS;
        $nombrearchivo=date("Ymd_his")."_".DATABASE_NAME.EXTENSION_SQL;
        $ruta=$carpeta."/".$nombrearchivo;
        $parametros=DATABASE_NAME." ".USUARIO_BD_JBDC_MYSQL." ".CLAVE_BD_JBDC_MYSQL." ".DATABASE_PORT." ".$ruta;
        $mensaje = shell_exec($comando." ".$parametros);        
        $nombrearchivozip=$nombrearchivo.EXTENSION_ZIP;
        $rutazip=APP_PATH_URL."assets/data/basedatos/".$nombrearchivozip;
        $resultado["resultado"]=$mensaje;
        $resultado["ruta"]=$rutazip;
        $resultado["nombre"]=$nombrearchivozip;
        return $resultado;
      }

      function DescargarBackup($data) {
        $rutazip = $data["ruta"];
        $nombrearchivozip=basename($rutazip);
        $path = file_get_contents($rutazip);
        force_download($nombrearchivozip, $path);
      }

      function EnviarCorreoConBackupBaseDatos($data) {
        
      try {
        $rutazip = $data["ruta"];        
        $data_documento["IdEmpresa"] = ID_EMPRESA;
        $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_documento)[0];        
        $resultado = array();
        $data["logo_empresa"] = "";        
        $data["nombre_empresa"]= $DatosEmpresa["RazonSocial"];
        $data["titulo"] = "ENVIO DE COPIA SEGURIDAD BASE DATOS ".DATABASE_NAME;
        $data["mensaje"] = "Mediante este medio se hace el envío de copia de seguridad de la base datos correspondiente.<br><br>";          
        $alias_destinatario = $DatosEmpresa["RazonSocial"];
        $email_destinatario = $data["Email"];
        $nombrearchivozip=basename($rutazip);
        $adjunto[0]['archivo'] =RUTA_CARPETA_ASSETS_BASE_DATOS."\\".$nombrearchivozip;        
        $titulo= "ENVIO DE COPIA SEGURIDAD BASE DATOS ".DATABASE_NAME;
        $mensaje = $this->load->view('.Master/view_contacto_solicitud',$data,true);
     
        $resultado = $this->emailer->send_mail($titulo, $mensaje, $email_destinatario ,$alias_destinatario,$adjunto);

        if($resultado) {
          $response["title"] = "<strong>Éxito.</strong>";
          $response["type"] = "success";
          $response["clase"] = "notify-success";
          $response["message"] = "El email fue enviado.";
          return $this->json->json_response($response);
        }
        else {
          $response["title"] = "<strong>Error!</strong>";
          $response["type"] = "danger";
          $response["clase"] = "notify-danger";
          $response["message"] = "Ocurrio un error al enviar email.";
          return  $this->json->json_response($response);
        }

        } catch (Exception $e) {
          $response["title"] = "<strong>Error!</strong>";
          $response["type"] = "danger";
          $response["clase"] = "notify-danger";
          $response["message"] = $e->getMessage();
          return $this->json->json_response($response);
        }
	  }
}