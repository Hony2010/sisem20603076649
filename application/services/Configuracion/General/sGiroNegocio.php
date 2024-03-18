<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sGiroNegocio extends MY_Service {

        public $GiroNegocio = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mGiroNegocio');
              $this->GiroNegocio = $this->mGiroNegocio->GiroNegocio;
        }

        function ListarGirosNegocio()
        {
          $resultado = $this->mGiroNegocio->ListarGirosNegocio();
          return $resultado;
        }

        function ValidarNombreGiroNegocio($data)
        {
          $nombre=$data["NombreGiroNegocio"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarGiroNegocio($data)
        {
          $nombre = $this->ValidarNombreGiroNegocio($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarGiroNegocio($data)
        {
          $data["NombreGiroNegocio"] = trim($data["NombreGiroNegocio"]);
          $resultado = $this -> ValidarGiroNegocio($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mGiroNegocio->InsertarGiroNegocio($data);
            return $resultado;
          }
        }

        function ActualizarGiroNegocio($data)
        {
          $data["NombreGiroNegocio"] = trim($data["NombreGiroNegocio"]);
          $resultado = $this -> ValidarGiroNegocio($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mGiroNegocio->ActualizarGiroNegocio($data);
            return "";
          }
        }

        function ValidarGiroNegocioEnEmpresa($data)
        {
          $resultado = $this->mGiroNegocio->ConsultarGiroNegocioEnEmpresa($data);
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

        function BorrarGiroNegocio($data)
        {
          $resultado1= $this -> ValidarGiroNegocio($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mGiroNegocio->BorrarGiroNegocio($data);
            return "";
          }
        }
}
