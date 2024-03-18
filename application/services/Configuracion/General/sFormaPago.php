<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sFormaPago extends MY_Service {

        public $FormaPago = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mFormaPago');
              $this->FormaPago = $this->mFormaPago->FormaPago;
        }

        function ListarFormasPago()
        {
          $resultado = $this->mFormaPago->ListarFormasPago();
          return $resultado;
        }

        function ValidarNombreFormaPago($data)
        {
          $nombre=$data["NombreFormaPago"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarFormaPago($data)
        {
          $nombre= $this->ValidarNombreFormaPago($data);
          if ($nombre !="")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarFormaPago($data)
        {
          $data["NombreFormaPago"] = trim($data["NombreFormaPago"]);
          $resultado = $this -> ValidarFormaPago($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mFormaPago->InsertarFormaPago($data);
            return $resultado;
          }
        }

        function ActualizarFormaPago($data)
        {
          $data["NombreFormaPago"] = trim($data["NombreFormaPago"]);
          $resultado = $this -> ValidarFormaPago($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mFormaPago->ActualizarFormaPago($data);
            return "";
          }
        }

        function ValidarExistenciaFormaPagoEnComprobanteVenta($data)
        {
          $resultado = $this->mFormaPago->ConsultarFormaPagoEnComprobanteVenta($data);
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

        function BorrarFormaPago($data)
        {
          $resultado1= $this -> ValidarExistenciaFormaPagoEnComprobanteVenta($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mFormaPago->BorrarFormaPago($data);
            return "";
          }
        }
}
