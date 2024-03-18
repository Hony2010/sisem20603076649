<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCasillero extends MY_Service {

        public $Casillero = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Catalogo/mCasillero');
              $this->Casillero = $this->mCasillero->Casillero;
        }

        function ListarCasilleros() {
            $resultado = $this->mCasillero->ListarCasilleros();
            return $resultado;
        }
        
        function ObtenerCasillero($data) {
            $resultado = $this->mCasillero->ObtenerCasillero($data);
            return $resultado;
        }
                
        function InsertarCasillero($data) {
            $resultadoValidacion=$this->ValidarCasillero($data);
            if ($resultadoValidacion=="") {
                $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
                $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
                $data["IndicadorEstado"] = ESTADO_ACTIVO;
                $resultado = $this->mCasillero->InsertarCasillero($data);
                return $resultado;
            }
            else {
                return $resultadoValidacion;
            }
        }

        function ActualizarCasillero($data) {
            $resultadoValidacion=$this->ValidarCasillero($data);
            if ($resultadoValidacion=="") {
                $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
                $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
                $this->mCasillero->ActualizarCasillero($data);
                return "";
            }
            else {
                return $resultadoValidacion;
            }
        }

        function BorrarCasillero($data) {
            $resultado = "";//$this->ValidarExistenciCasilleroEnMercaderia($data);
            if ($resultado != "") {
                return $resultado;
            }
            else {
                $data["IndicadorEstado"]=ESTADO_ELIMINADO;
                $this->ActualizarCasillero($data);
                return "";
            }
        }

        function ValidarCasillero($data) {
            $resultado = $this->ObtenerCasillero($data);
            
            if (count($resultado) > 0) {   //Si existe duplicidad
                if ( $data["IdCasillero"] != $resultado[0]["IdCasillero"]) { //Si es Id es de otro
                    return "Ya existe un nombre de casillero, cambie a otro nombre";
                }
            }

            return "";            
        }

}
