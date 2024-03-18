<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sModuloSistema extends MY_Service {

        public $TipoExistencia = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mModuloSistema');
              $this->ModuloSistema = $this->mModuloSistema->ModuloSistema;
        }

        function ListarModulosSistema()
        {
          $resultado = $this->mModuloSistema->ListarModulosSistema();
          return $resultado;
        }
        function ListarModulosSistemaParaTipoDocumento()
        {
          $resultado = $this->mModuloSistema->ListarModulosSistemaParaTipoDocumento();
          return $resultado;
        }

        function ValidarNombreModuloSistema($data)
        {
          $nombre=$data["NombreModuloSistema"];
          if ($nombre == "")
          {
            return "Debe completar el registro";
          }
          else
          {
            return "";
          }
        }

        function ValidarModuloSistema($data)
        {
          $resultado= $this->ValidarNombreModuloSistema($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            return "";
          }
        }

        function InsertarModuloSistema($data)
        {
          $resultado = $this -> ValidarModuloSistema($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mModuloSistema->InsertarModuloSistema($data);
            return $resultado;
          }
        }

        function ActualizarParametroSistema($data)
        {
          $resultado = $this -> ValidarModuloSistema($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mModuloSistema->ActualizarParametroSistema($data);
            return "";
          }
        }

        function ValidarExistenciaModuloSistemaEnEntidadSistema($data)
        {
          $resultado = $this->mModuloSistema->ConsultarModuloSistemaEnEntidadSistema($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Entidad Sistema";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaModuloSistemaEnParametroSistema($data)
        {
          $resultado = $this->mModuloSistema->ConsultarModuloSistemaEnParametroSistema($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Parametro Sistema";
          }
          else
          {
            return "";
          }
        }

        function BorrarModuloSistema($data)
        {
          $resultado1= $this -> ValidarExistenciaModuloSistemaEnEntidadSistema($data);
          $resultado2= $this -> ValidarExistenciaModuloSistemaEnParametroSistema($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2 != "")
          {
            return $resultado2;
          }
          else
          {
            $this->mModuloSistema->BorrarModuloSistema($data);
            return "";
          }
        }

        function ConsultarModuloSistema($data)
        {
          $resultado = $this->mModuloSistema->ConsultarModuloSistema($data);
          return $resultado;
        }
}
