<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sEntidadSistema extends MY_Service {

        public $EntidadSistema = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mEntidadSistema');
              $this->EntidadSistema = $this->mEntidadSistema->EntidadSistema;
        }
        function ListarEntidadesSistema($data)
        {
          $resultado = $this->mEntidadSistema->ListarEntidadesSistema($data);
          return $resultado;
        }

        function ValidarEntidadSistema($data)
        {
          $nombre=$data["NombreEntidadSistema"];
          if ($nombre == "")
          {
            return "Debe ingresar primero el nombre de Entidad Sistema";
          }
          else
          {
            return "";
          }
        }

        function InsertarEntidadSistema($data)
        {
          $resultado = $this -> ValidarEntidadSistema($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mEntidadSistema->InsertarEntidadSistema($data);
            return $resultado;
          }
        }

        function ActualizarEntidadSistema($data)
        {
          $resultado = $this -> ValidarEntidadSistema($data);
          if ($resultado != "")
          {
            return ($resultado);
          }
          else
          {
            $this->mEntidadSistema->ActualizarEntidadSistema($data);
            return "";
          }
        }

        function ValidarExistenciaEntidadSistemaEnParametroSistema($data)
        {
          $resultado = $this->mEntidadSistema->ConsultarModuloSistemaEnParametroSistema($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque la Sub familia tiene movimientos en Parametro Sistema";
          }
          else
          {
            return"";
          }
        }

        function BorrarEntidadSistema($data)
        {
          $resultado = $this -> ValidarExistenciaEntidadSistemaEnParametroSistema($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $this->mEntidadSistema->BorrarEntidadSistema($data);
            return "";
          }
        }

        function ConsultarEntidadSistema($data)
        {
          $resultado=$this->mEntidadSistema->ConsultarEntidadSistema($data);
          return $resultado;
        }
}
