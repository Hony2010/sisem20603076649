<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAccesoUsuario extends MY_Service {

        public $AccesoUsuario = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('herencia');
              $this->load->model('Seguridad/mAccesoUsuario');
          	  $this->load->service('Seguridad/sAccesoRol');
              $this->AccesoUsuario = $this->mAccesoUsuario->AccesoUsuario;
        }

        function Cargar() {

        }


        function InsertarAccesosUsuario($data) {
            foreach ($data as $value) {
              $validar_accesorol = $this->sAccesoRol->ValidarAccesoRol($value);
              if(count($validar_accesorol)>0)
              {
                $resultado = $this->InsertarAccesoUsuario($value);
              }
              else {
                $insertar_accesorol = $this->sAccesoRol->InsertarAccesoRol($value);
                if($insertar_accesorol)
                {
                  $value["IdAccesoRol"] = $insertar_accesorol;
                  $value["EstadoOpcionUsuario"] = $value["EstadoOpcionRol"];
                  $resultado = $this->InsertarAccesoUsuario($value);
                }
              }

            }

            return "";
        }

        function ActualizarAccesosUsuario($data) {
          try {
            foreach ($data as $value) {
              $validar_accesousuario = $this->mAccesoUsuario->ValidarAccesoUsuario($value);

              if(count($validar_accesousuario)>0)
              {
                if($value["IdAccesoRol"] == "")
                {
                    throw new Exception("Su Rol elegido no tiene permisos. Revise Acceso Rol.", 1);
                }

                if ($value["IdAccesoUsuario"] == "") {
                  $value["IdAccesoUsuario"] = $validar_accesousuario[0]["IdAccesoUsuario"];
                }

                $resultado = $this->ActualizarAccesoUsuario($value);
              }
              else {
                $resultado = $this->InsertarAccesoUsuario($value);
              }

            }
            return "";
          } catch (Exception $e) {
            return $e->getMessage();
          }

        }

        function InsertarAccesoUsuario($data) {
            $resultado = $this->mAccesoUsuario->InsertarAccesoUsuario($data);
            return $resultado;
        }

        function ActualizarAccesoUsuario($data) {
            $resultado = $this->mAccesoUsuario->ActualizarAccesoUsuario($data);
            return $resultado;
        }

        function BorrarAccesosPorUsuario($data) {
            $resultado = $this->mAccesoUsuario->BorrarAccesosPorUsuario($data);
            return $resultado;
        }

        function ObtenerOpcionesUsuarioPorIdModuloSistema($data) {
            $resultado = $this->mAccesoUsuario->ObtenerOpcionesUsuarioPorIdModuloSistema($data);
            return $resultado;
        }

        function ObtenerAccesosUsuarioPorIdOpcionSistemaPorIdUsuarioPorIdRol($data) {
            $resultado = $this->mAccesoUsuario->ObtenerAccesosUsuarioPorIdOpcionSistemaPorIdUsuarioPorIdRol($data);
            return $resultado;
        }

}
