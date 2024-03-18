<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sGenero extends MY_Service {

        public $Genero = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Catalogo/mGenero');
              $this->Genero = $this->mGenero->Genero;
        }

        function ListarGeneros() {
            $resultado = $this->mGenero->ListarGeneros();
            return $resultado;
        }
        
        function ObtenerGenero($data) {
            $resultado = $this->mGenero->ObtenerGenero($data);
            return $resultado;
        }
                
        function InsertarGenero($data) {
            $resultadoValidacion=$this->ValidarGenero($data);
            if ($resultadoValidacion=="") {
                $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
                $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
                $data["IndicadorEstado"] = ESTADO_ACTIVO;
                $resultado = $this->mGenero->InsertarGenero($data);
                return $resultado;
            }
            else {
                return $resultadoValidacion;
            }
        }

        function ActualizarGenero($data) {
            $resultadoValidacion=$this->ValidarGenero($data);
            if ($resultadoValidacion=="") {
                $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
                $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
                $this->mGenero->ActualizarGenero($data);
                return "";
            }
            else {
                return $resultadoValidacion;
            }
        }

        function BorrarGenero($data) {
            $resultado = "";//$this->ValidarExistenciCasilleroEnMercaderia($data);
            if ($resultado != "") {
                return $resultado;
            }
            else {
                $data["IndicadorEstado"]=ESTADO_ELIMINADO;
                $this->ActualizarGenero($data);
                return "";
            }
        }

        function ValidarGenero($data) {
            $resultado = $this->ObtenerGenero($data);
            
            if (count($resultado) > 0) {   //Si existe duplicidad
                if ( $data["IdGenero"] != $resultado[0]["IdGenero"]) { //Si es Id es de otro
                    return "Ya existe un nombre de genero, cambie a otro nombre";
                }
            }

            return "";            
        }

}
