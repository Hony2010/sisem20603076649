<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMotivoNotaVenta extends MY_Service {

        public $MotivoNotaVenta = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mMotivoNotaVenta');
              $this->MotivoNotaVenta = $this->mMotivoNotaVenta->MotivoNotaVenta;
        }

        function ListarMotivoNotasVenta()
        {
          $resultado = $this->mMotivoNotaVenta->ListarMotivoNotasVenta();
          return $resultado;
        }

        function ValidarNombreMotivoNotaVenta($data)
        {
          $nombre=$data["NombreMotivoNotaVenta"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarMotivoNotaVenta($data)
        {
          $nombre= $this->ValidarNombreMotivoNotaVenta($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarMotivoNotaVenta($data)
        {
          $resultado = $this -> ValidarMotivoNotaVenta($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mMotivoNotaVenta->InsertarMotivoNotaVenta($data);
            return $resultado ;
          }
        }

        function ActualizarMotivoNotaVenta($data)
        {
          $resultado = $this -> ValidarMotivoNotaVenta($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mMotivoNotaVenta->ActualizarMotivoNotaVenta($data);
            return "";
          }
        }

        function ValidarExistenciaMotivoNotaVentaEnComprobanteVenta($data)
        {
          $resultado = $this->mMotivoNotaVenta->ConsultarMotivoNotaVentaEnComprobanteVenta($data);
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

        function BorrarMotivoNotaVenta($data)
        {
          $resultado1= $this -> ValidarExistenciaMotivoNotaVentaEnComprobanteVenta($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mMotivoNotaVenta->BorrarMotivoNotaVenta($data);
            return "";
          }
        }
}
