<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoPersona extends MY_Service {

        public $TipoPersona = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoPersona');
              $this->TipoPersona = $this->mTipoPersona->TipoPersona;
        }

        function ListarTiposPersona()
        {
          $resultado = $this->mTipoPersona->ListarTiposPersona();
          return $resultado;
        }

        function ValidarNombreTipoPersona($data)
        {
          $nombre=$data["NombreTipoPersona"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoPersona($data)
        {
          $nombre= $this->ValidarNombreTipoPersona($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoPersona($data)
        {
          $resultado = $this -> ValidarTipoPersona($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoPersona->InsertarTipoPersona($data);
            return $resultado ;
          }
        }

        function ActualizarTipoPersona($data)
        {
          $resultado = $this -> ValidarTipoPersona($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoPersona->ActualizarTipoPersona($data);
            return "";
          }
        }

        function ValidarExistenciaTipoPersona($data)
        {
          $resultado = $this->mTipoPersona->ConsultarTipoPersonaEnPersona($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Persona";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoPersona($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoPersona($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mTipoPersona->BorrarTipoPersona($data);
            return "";
          }
        }
}
