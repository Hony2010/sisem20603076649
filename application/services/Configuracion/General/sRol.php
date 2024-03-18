<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sRol extends MY_Service {

        public $Rol = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mRol');
              $this->Rol = $this->mRol->Rol;
        }

        function ListarRoles()
        {
          $resultado = $this->mRol->ListarRoles();
          return $resultado;
        }

        function ValidarNombreRol($data)
        {
          $nombre=$data["NombreRol"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarRol($data)
        {
          $nombre= $this->ValidarNombreRol($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarRol($data)
        {
          $resultado = $this -> ValidarRol($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mRol->InsertarRol($data);
            return $resultado;
          }
        }

        function ActualizarRol($data)
        {
          $resultado = $this -> ValidarRol($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mRol->ActualizarRol($data);
            return "";
          }
        }

        function ValidarExistenciaRol($data)
        {
          $resultado = $this->mRol->ConsultarRolEnEmpleado($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Empleado";
          }
          else
          {
            return "";
          }
        }

        function BorrarRol($data)
        {
          $resultado1= $this -> ValidarExistenciaRol($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mRol->BorrarRol($data);
            return "";
          }
        }
}
