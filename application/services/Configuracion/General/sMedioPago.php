<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMedioPago extends MY_Service {

        public $MedioPago = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mMedioPago');
              $this->MedioPago = $this->mMedioPago->MedioPago;
        }

        function ListarMediosPago()
        {
          $resultado = $this->mMedioPago->ListarMediosPago();
          return $resultado;
        }

        function ValidarCodigoMedioPago($data)
        {
          $codigo=$data["CodigoMedioPago"];
          if ($codigo == "")
          {
            return "Debe completar el Código";
          }
          else if (!is_numeric($codigo))
          {
            return "Debe ingresar un valor numeríco en el código";
          }
          else if (strlen($codigo)>3)
          {
            return "El código debe tener como máximo 3 caracteres";
          }
          else
          {
            return "";
          }
        }

        function ValidarNombreMedioPago($data)
        {
          $nombre=$data["NombreMedioPago"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarMedioPago($data)
        {
          $codigo= $this->ValidarCodigoMedioPago($data);
          $nombre= $this->ValidarNombreMedioPago($data);
          if ($codigo!="")
          {
            return $codigo;
          }
          else if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoMedioPagoParaInsertar($data)
        {
          $resultado = $this->mMedioPago->ObtenerCodigoMedioPagoParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoMedioPagoParaActualizar($data)
        {
          $resultado = $this->mMedioPago->ObtenerCodigoMedioPagoParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarMedioPago($data)
        {
          $data["CodigoMedioPago"] = trim($data["CodigoMedioPago"]);
          $data["NombreMedioPago"] = trim($data["NombreMedioPago"]);
          $resultado1 = $this->ValidarMedioPago($data);
          $resultado2 = $this->ValidarExistenciaCodigoMedioPagoParaInsertar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2)
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mMedioPago->InsertarMedioPago($data);
            return $resultado;
          }
        }

        function ActualizarMedioPago($data)
        {
          $data["CodigoMedioPago"] = trim($data["CodigoMedioPago"]);
          $data["NombreMedioPago"] = trim($data["NombreMedioPago"]);
          $resultado1 = $this->ValidarMedioPago($data);
          $resultado2 = $this->ValidarExistenciaCodigoMedioPagoParaActualizar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2)
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mMedioPago->ActualizarMedioPago($data);
            return "";
          }
        }


        function BorrarMedioPago($data)
        {
            $resultado = $this->mMedioPago->BorrarMedioPago($data);
            return "";
        }
}
