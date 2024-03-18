<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sGrupoParametro extends MY_Service {

        public $GrupoParametro = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mGrupoParametro');
              $this->GrupoParametro = $this->mGrupoParametro->GrupoParametro;
        }

        function ListarGruposParametro()
        {
          $resultado = $this->mGrupoParametro->ListarGruposParametro();
          return $resultado;
        }

        function ValidarNombreGrupoParametro($data)
        {
          $nombre=$data["NombreGrupoParametro"];
          if ($nombre == "")
          {
            return "Debe completar el registro";
          }
          else
          {
            return "";
          }
        }

        function ValidarGrupoParametro($data)
        {
          $resultado= $this->ValidarNombreGrupoParametro($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            return "";
          }
        }

        function InsertarGrupoParametro($data)
        {
          $resultado = $this -> ValidarGrupoParametro($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mGrupoParametro->InsertarGrupoParametro($data);
            return $resultado;
          }
        }

        function ActualizarGrupoParametro($data)
        {
          $resultado = $this -> ValidarGrupoParametro($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mGrupoParametro->ActualizarGrupoParametro($data);
            return "";
          }
        }

        function ValidarExistenciaGrupoParametroEnParametroSistema($data)
        {
          $resultado = $this->mGrupoParametro->ConsultarGrupoParametroEnParametroSistema($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Parametros del Sistema";
          }
          else
          {
            return "";
          }
        }

        function BorrarGrupoParametro($data)
        {
          $resultado= $this -> ValidarExistenciaGrupoParametroEnParametroSistema($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $this->mGrupoParametro->BorrarGrupoParametro($data);
            return "";
          }
        }

        function ConsultarGrupoParametro($data)
        {
          $resultado=$this->mGrupoParametro->ConsultarGrupoParametro($data);
          return $resultado;
        }
}
