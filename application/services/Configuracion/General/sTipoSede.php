<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoSede extends MY_Service {

        public $TipoSede = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoSede');
              $this->TipoSede = $this->mTipoSede->TipoSede;
        }

        function ListarTiposSede()
        {
          $resultado = $this->mTipoSede->ListarTiposSede();
          return $resultado ;
        }

        function ValidarNombreTipoSede($data)
        {
          $nombre=$data["NombreTipoSede"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoSede($data)
        {
          $nombre = $this->ValidarNombreTipoSede($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoSede($data)
        {
          $data["NombreTipoSede"] = trim($data["NombreTipoSede"]);
          $resultado = $this -> ValidarTipoSede($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoSede->InsertarTipoSede($data);
            return $resultado ;
          }
        }

        function ActualizarTipoSede($data)
        {
          $data["NombreTipoSede"] = trim($data["NombreTipoSede"]);
          $resultado = $this -> ValidarTipoSede($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoSede->ActualizarTipoSede($data);
            return "";
          }
        }

        function ValidarExistenciaTipoSedeEnSede($data)
        {
          $resultado = $this->mTipoSede->ConsultarTipoSedeEnSede($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Sede";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoSede($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoSedeEnSede($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mTipoSede->BorrarTipoSede($data);
            return "";
          }
        }

        function ListarTipoSedeParaSede()
        {
          $resultado = $this->mTipoSede->ListarTipoSedeParaSede();
          return $resultado;
        }
}
