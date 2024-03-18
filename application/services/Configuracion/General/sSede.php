<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sSede extends MY_Service {

        public $Sede = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mSede');
              $this->Sede = $this->mSede->Sede;
        }

        function ListarSedes()
        {
          $resultado = $this->mSede->ListarSedes();
          return $resultado;
        }

        function ListarSedeTiposAgencia()
        {
          $resultado = $this->mSede->ListarSedeTiposAgencia();
          return $resultado;
        }

        function ListarSedesTipoAlmacen()
        {
          $resultado = $this->mSede->ListarSedesTipoAlmacen();
          foreach($resultado  as $key1 =>$value1)
          {
            $resultado[$key1]["Seleccionado"] = false;
            $resultado[$key1]["IdAccesoUsuarioAlmacen"] = "";
          }
          return $resultado;
        }

        function ValidarCodigoSede($data)
        {
          $codigo=$data["CodigoSede"];
          if ($codigo == "")
          {
            return "Debe completar el Código";
          }
          else if (!is_numeric($codigo))
          {
            return "Debe ingresar un valor numeríco en el código";
          }
          else if (strlen($codigo)!=4)
          {
            return "El código debe ser de 4 dígitos";
          }
          else
          {
            return "";
          }
        }

        function ValidarNombreSede($data)
        {
          $nombre=$data["NombreSede"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarDireccion($data)
        {
          $direccion=$data["Direccion"];
          if ($direccion == "")
          {
            return "Debe completar la Direccion";
          }
          else
          {
            return "";
          }
        }

        function ValidarSede($data)
        {
          $codigo = $this->ValidarCodigoSede($data);
          $nombre = $this->ValidarNombreSede($data);
          $direccion = $this->ValidarDireccion($data);
          if ($codigo !="")
          {
            return $codigo;
          }
          else if ($nombre !="")
          {
            return $nombre;
          }
          else if ($direccion!="")
          {
            return $direccion;
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoSedeParaInsertar($data)
        {
          $resultado = $this->mSede->ObtenerCodigoSedeParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoSedeParaActualizar($data)
        {
          $resultado = $this->mSede->ObtenerCodigoSedeParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarSede($data)
        {
          $data["CodigoSede"] = trim($data["CodigoSede"]);
          $data["NombreSede"] = trim($data["NombreSede"]);
          $data["Direccion"] = trim($data["Direccion"]);
          $resultado1 = $this -> ValidarSede($data);
          $resultado2 = $this -> ValidarExistenciaCodigoSedeParaInsertar($data);

          if ($resultado1!="")
          {
            return $resultado1;
          }
          else if ($resultado2!="")
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mSede->InsertarSede($data);
            return($resultado);
          }
        }

        function ActualizarSede($data)
        {
          $data["CodigoSede"] = trim($data["CodigoSede"]);
          $data["NombreSede"] = trim($data["NombreSede"]);
          $data["Direccion"] = trim($data["Direccion"]);
          $resultado1 = $this -> ValidarSede($data);
          $resultado2 = $this -> ValidarExistenciaCodigoSedeParaActualizar($data);

          if ($resultado1!="")
          {
            return $resultado1;
          }
          else if ($resultado2!="")
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mSede->ActualizarSede($data);
            return "";
          }
        }

        function ValidarExistenciaSedeEnEmpleado($data)
        {
          $resultado = $this->mSede->ConsultarExistenciaSedeEnEmpleado($data);
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

        function BorrarSede($data)
        {
          $resultado1= $this -> ValidarExistenciaSedeEnEmpleado($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mSede->BorrarSede($data);
            return "";
          }
        }
}
