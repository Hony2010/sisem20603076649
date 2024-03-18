<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoTarjeta extends MY_Service {

        public $TipoTarjeta = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mTipoTarjeta');
              $this->TipoTarjeta = $this->mTipoTarjeta->TipoTarjeta;
        }

        function ListarTiposTarjeta()
        {
          $resultado = $this->mTipoTarjeta->ListarTiposTarjeta();
          return $resultado;
        }

        function ValidarNombreTipoTarjeta($data)
        {
          $nombre=$data["NombreTarjeta"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoTarjeta($data)
        {
          $nombre= $this->ValidarNombreTipoTarjeta($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoTarjeta($data)
        {
          $resultado = $this -> ValidarTipoTarjeta($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoTarjeta->InsertarTipoTarjeta($data);
            return $resultado ;
          }
        }

        function ActualizarTipoTarjeta($data)
        {
          $resultado = $this -> ValidarTipoTarjeta($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoTarjeta->ActualizarTipoTarjeta($data);
            return "";
          }
        }

        function ValidarExistenciaTipoTarjetaEnComprobanteVenta($data)
        {
          $resultado = $this->mTipoTarjeta->ConsultarTipoTarjetaEnComprobanteVenta($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Venta";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoTarjeta($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoTarjetaEnComprobanteVenta($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mTipoTarjeta->BorrarTipoTarjeta($data);
            return "";
          }
        }
}
