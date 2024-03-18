<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCasilleroGenero extends MY_Service {

        public $CasilleroGenero = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Catalogo/mCasilleroGenero');
              $this->CasilleroGenero = $this->mCasilleroGenero->CasilleroGenero;
        }

        function ListarCasillerosGenero($data) {
            $resultado = $this->mCasilleroGenero->ListarCasillerosGenero($data);
            return $resultado;
        }
        
        function ObtenerCasilleroGenero($data) {
            $resultado = $this->mCasilleroGenero->ObtenerCasilleroGenero($data);
            return $resultado;
        }
                
        function InsertarCasilleroGenero($data) {
            $resultadoValidacion=$this->ValidarCasilleroGenero($data);
            if ($resultadoValidacion=="") {
                $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
                $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
                $data["IndicadorEstado"] = ESTADO_ACTIVO;
                $resultado = $this->mCasilleroGenero->InsertarCasilleroGenero($data);
                return $resultado;
            }
            else {
                return $resultadoValidacion;
            }
        }

        function ActualizarCasilleroGenero($data) {
            $resultadoValidacion=$this->ValidarCasilleroGenero($data);
            if ($resultadoValidacion=="") {
                $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
                $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
                $this->mCasilleroGenero->ActualizarCasilleroGenero($data);
                return "";
            }
            else {
                return $resultadoValidacion;
            }
        }

        function BorrarCasilleroGenero($data) {
            $resultado = "";//$this->ValidarExistenciCasilleroEnMercaderia($data);
            if ($resultado != "") {
                return $resultado;
            }
            else {
                $data["IndicadorEstado"]=ESTADO_ELIMINADO;
                $this->ActualizarCasilleroGenero($data);
                return "";
            }
        }

        function ValidarCasilleroGenero($data) {
            $resultado = "";
            return $resultado;            
        }

        function MarcarCasilleroGenero($data) {
            if ($data["IdGeneroAnterior"] == "" && $data["IdCasilleroAnterior"] == "") {
                //no hace nada
            }
            else {
                if($data["IdCasilleroAnterior"]==$data["IdCasillero"] && $data["IdGeneroAnterior"]==$data["IdGenero"]) {
                    //no hace nada
                }
                else {
                    //Desmarcamos el casillero anterior
                    $dataCasilleroGeneroAnterior["IdCasillero"]=$data["IdCasilleroAnterior"];
                    $dataCasilleroGeneroAnterior["IdGenero"]=$data["IdGeneroAnterior"];                    
                    $resultadoCasilleroGeneroAnterior =$this->ObtenerCasilleroGenero($dataCasilleroGeneroAnterior);
                    $resultadoCasilleroGeneroAnterior[0]["IndicadorCasilleroDisponible"] = ESTADO_CASILLERO_DISPONIBLE;
                    $this->ActualizarCasilleroGenero($resultadoCasilleroGeneroAnterior[0]);
                }                                
            }

            if ($data["IdGenero"] == "" && $data["IdCasillero"] == "") {
                //no hace nada
            }
            else {
                if($data["IdCasilleroAnterior"]==$data["IdCasillero"] && $data["IdGeneroAnterior"]==$data["IdGenero"]) {
                    //no hace nada
                }
                else {
                    //Marcamos el casillero 
                    $dataCasilleroGenero["IdCasillero"]=$data["IdCasillero"];
                    $dataCasilleroGenero["IdGenero"]=$data["IdGenero"];                    
                    $resultadoCasilleroGenero = $this->ObtenerCasilleroGenero($dataCasilleroGenero);
                    $resultadoCasilleroGenero[0]["IndicadorCasilleroDisponible"] = ESTADO_CASILLERO_OCUPADO;
                    $this->ActualizarCasilleroGenero($resultadoCasilleroGenero[0]);                    
                }
            }

            return "";
        }

        function LiberarCasilleroGenero($data) {
             
            if ($data["IdGenero"] == "" && $data["IdCasillero"] == "") {
                //no hace nada
            }
            else {            
                $dataCasilleroGenero["IdCasillero"]=$data["IdCasillero"];
                $dataCasilleroGenero["IdGenero"]=$data["IdGenero"];                    
                $resultadoCasilleroGenero = $this->ObtenerCasilleroGenero($dataCasilleroGenero);
                $resultadoCasilleroGenero[0]["IndicadorCasilleroDisponible"] = ESTADO_CASILLERO_DISPONIBLE;
                $this->ActualizarCasilleroGenero($resultadoCasilleroGenero[0]);                    
            }
            
            return "";
        }
}
