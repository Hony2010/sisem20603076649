<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sRegimenTributario extends MY_Service {

        public $RegimenTributario = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mRegimenTributario');
              $this->RegimenTributario = $this->mRegimenTributario->RegimenTributario;
        }

        function ListarRegimenesTributario()
        {
          $resultado = $this->mRegimenTributario->ListarRegimenesTributario();
          return $resultado;
        }

        function ValidarNombreRegimenTributario($data)
        {
          $nombre=$data["NombreRegimenTributario"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarNombreAbreviado($data)
        {
          $abreviatura=$data["NombreAbreviado"];
          if ($abreviatura == "")
          {
            return "Debe completar la Abreviatura";
          }
          else
          {
            return "";
          }
        }

        function ValidarRegimenTributario($data)
        {
          $nombre= $this->ValidarNombreRegimenTributario($data);
          $abreviatura = $this->ValidarNombreAbreviado($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else if ($abreviatura !="")
          {
            return $abreviatura;
          }
          else
          {
            return "";
          }
        }

        function InsertarRegimenTributario($data)
        {
          $data["NombreRegimenTributario"] = trim($data["NombreRegimenTributario"]);
          $data["NombreAbreviado"] = trim($data["NombreAbreviado"]);
          $resultado = $this -> ValidarRegimenTributario($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mRegimenTributario->InsertarRegimenTributario($data);
            return $resultado;
          }
        }

        function ActualizarRegimenTributario($data)
        {
          $data["NombreRegimenTributario"] = trim($data["NombreRegimenTributario"]);
          $data["NombreAbreviado"] = trim($data["NombreAbreviado"]);
          $resultado = $this -> ValidarRegimenTributario($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mRegimenTributario->ActualizarRegimenTributario($data);
            return "";
          }
        }

        function ValidarExistenciaRegimenTributarioEnEmpresa($data)
        {
          $resultado = $this->mRegimenTributario->ConsultarRegimenTributarioEnEmpresa($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Empresa";
          }
          else
          {
            return "";
          }
        }

        function BorrarRegimenTributario($data)
        {
          $resultado1= $this -> ValidarExistenciaRegimenTributarioEnEmpresa($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mRegimenTributario->BorrarRegimenTributario($data);
            return "";
          }
        }
}
