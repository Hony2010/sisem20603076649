<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoDocumentoElectronico extends MY_Service {

        public $TipoDocumentoElectronico = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/FacturacionElectronica/mTipoDocumentoElectronico');
              $this->TipoDocumentoElectronico = $this->mTipoDocumentoElectronico->TipoDocumentoElectronico;
        }

        function ListarTiposDocumentoElectronico()
        {
          $resultado = $this->mTipoDocumentoElectronico->ListarTiposDocumentoElectronico();
          return $resultado;
        }

        function ValidarCodigoTipoDocumentoElectronico($data)
        {
          $codigo=$data["CodigoTipoDocumentoElectronico"];
          if ($codigo == "")
          {
            return "Debe completar el Código";
          }
          else if (!is_numeric($codigo))
          {
            return "Debe ingresar un valor numeríco en el código";
          }
          else if (strlen($codigo)>2)
          {
            return "El código debe tener como máximo 2 caracteres";
          }
          else
          {
            return "";
          }
        }

        function ValidarNombreTipoDocumentoElectronico($data)
        {
          $nombre=$data["NombreTipoDocumentoElectronico"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoDocumentoElectronico($data)
        {
          $codigo= $this->ValidarCodigoTipoDocumentoElectronico($data);
          $nombre= $this->ValidarNombreTipoDocumentoElectronico($data);
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

        function ValidarExistenciaCodigoTipoDocumentoElectronicoParaInsertar($data)
        {
          $resultado = $this->mTipoDocumentoElectronico->ObtenerCodigoTipoDocumentoElectronicoParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoTipoDocumentoElectronicoParaActualizar($data)
        {
          $resultado = $this->mTipoDocumentoElectronico->ObtenerCodigoTipoDocumentoElectronicoParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoDocumentoElectronico($data)
        {
          $data["CodigoTipoDocumentoElectronico"] = trim($data["CodigoTipoDocumentoElectronico"]);
          $data["NombreTipoDocumentoElectronico"] = trim($data["NombreTipoDocumentoElectronico"]);
          $resultado1 = $this->ValidarTipoDocumentoElectronico($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoDocumentoElectronicoParaInsertar($data);

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
            $resultado = $this->mTipoDocumentoElectronico->InsertarTipoDocumentoElectronico($data);
            return $resultado;
          }
        }

        function ActualizarTipoDocumentoElectronico($data)
        {
          $data["CodigoTipoDocumentoElectronico"] = trim($data["CodigoTipoDocumentoElectronico"]);
          $data["NombreTipoDocumentoElectronico"] = trim($data["NombreTipoDocumentoElectronico"]);
          $resultado1 = $this->ValidarTipoDocumentoElectronico($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoDocumentoElectronicoParaActualizar($data);

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
            $resultado = $this->mTipoDocumentoElectronico->ActualizarTipoDocumentoElectronico($data);
            return "";
          }
        }

        function ValidarExistenciaTipoDocumentoElectronicoEnComprobanteVenta($data)
        {
          $resultado = $this->mTipoDocumentoElectronico->ConsultarTipoDocumentoElectronicoEnComprobanteVenta($data);
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

        function BorrarTipoDocumentoElectronico($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoDocumentoElectronicoEnComprobanteVenta($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mTipoDocumentoElectronico->BorrarTipoDocumentoElectronico($data);
            return "";
          }
        }


        function ObtenerTipoDocumentoElectronico($data)
        {
          $resultado = $this->mTipoDocumentoElectronico->ObtenerTipoDocumentoElectronico($data);
          return $resultado;
        }
}
