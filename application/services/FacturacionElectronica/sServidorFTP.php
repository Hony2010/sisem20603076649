<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sServidorFTP extends MY_Service {

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('sesionusuario');
              $this->load->library('ftp');
              $this->load->helper("date");
          		$this->load->service('Configuracion/General/sEmpresa');
              $this->load->service('Configuracion/General/sParametroSistema');

        }

        public function SubirArchivoFTP($data)
    		{
          try {
            $data_empresa["IdEmpresa"] = ID_EMPRESA;
            $DatosEmpresa = $this->sEmpresa->ListarEmpresas($data_empresa)[0];

            $data_parametro['IdParametroSistema']= ID_RAIZ_FTP;
            $Parametro = (array)$this->sParametroSistema->ObtenerParametroSistemaPorId($data_parametro)[0];

            $ruta_archivo = $data["RutaArchivo"];
            $archivo = $data["NombreArchivo"];
            $rutaFTP = $Parametro["ValorParametroSistema"];
            $carpeta = $DatosEmpresa["CodigoEmpresa"];
            //FTP configuration
            $ftp_config['hostname'] = $DatosEmpresa["HostFTP"];//"sisemperu.com";//'ftp.example.com';
            $ftp_config['username'] = $DatosEmpresa["UsuarioFTP"];//"desarrollo@sisemperu.com";//'ftp_username';
            $ftp_config['password'] = $DatosEmpresa["ClaveFTP"];//"desarrollo";//'ftp_password';
            $ftp_config['port'] = $DatosEmpresa["PuertoFTP"];//21;
            $ftp_config['debug'] = TRUE;

            //Connect to the remote server
            $this->ftp->connect($ftp_config);
            // echo $rutaFTP;
            // echo $carpeta;
            // exit;
            $validar_carpeta = $this->ValidarCarpetaFTP($rutaFTP, $carpeta);

            $envio["msg"] = "";
            
            if($validar_carpeta)
            {
              $ruta_carpeta = "$rutaFTP/$carpeta/";
              $borrar = $this->BorrarArchivoFTP($ruta_carpeta, $archivo);
              if($borrar)
              {
                $resultado = $this->ftp->upload($ruta_archivo, $ruta_carpeta.$archivo);
                if($resultado)
                {
                  $envio["msg"] = "Exitoso Envio";
                }
                else {
                  $envio["Error"] = "Ocurrio un error al tratar de subir el archivo.";
                  $envio["msg"] = "Ocurrio un error al tratar de subir el archivo.";
                }
              }
              else {
                $envio["Error"] = "Ocurrio un error al tratar de borrar el archivo.";
                $envio["msg"] = "Ocurrio un error al tratar de borrar el archivo.";
              }
            }
            else {
              $envio["Error"] = "Ocurrio un error al tratar de validar la carpeta.";
              $envio["msg"] = "Ocurrio un error al tratar de validar la carpeta.";
            }
            //Close FTP connection
            $this->ftp->close();
            return $envio;
          } catch (Exception $e) {

          }
    		}

        public function ValidarCarpetaFTP($raiz, $carpeta)
        {
          /*Aqui validamos si existe directorio de empresa*/

          $carpetas = $this->ftp->list_files($raiz);

          if(!in_array("$raiz/$carpeta", $carpetas))
          {
            // $this->ftp->chmod($rutaFTP.'/example/', 0777);
            $creacion_carpeta = $this->ftp->mkdir("$raiz/$carpeta/", DIR_WRITE_MODE);
            if($creacion_carpeta)
            {
              return true;
            }
            else {
              return false;
            }
          }
          else {
            // code...
            return true;
          }

        }

        public function BorrarArchivoFTP($ruta, $archivo)
        {
          /*Aqui validamos si existe directorio de empresa*/
          $carpetas = $this->ftp->list_files($ruta);
          // echo $ruta."-".$archivo;
          // print_r($carpetas);
          // exit;
          if(in_array($archivo, $carpetas))
          {
            $this->ftp->chmod($ruta, 0777);
            $delete= $this->ftp->delete_file($ruta.$archivo);
            if($delete)
            {
              return true;
            }
            else {
              return false;
            }
          }
          else {
            return true;
          }
        }

}
