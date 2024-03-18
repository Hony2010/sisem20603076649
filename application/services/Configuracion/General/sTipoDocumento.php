<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoDocumento extends MY_Service {

        public $TipoDocumento = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoDocumento');
              $this->load->service('Configuracion/General/sTipoDocumentoModuloSistema');
              $this->TipoDocumento = $this->mTipoDocumento->TipoDocumento;
        }

        function ListarTiposDocumento()
        {
          $resultado = $this->mTipoDocumento->ListarTiposDocumento();
          return $resultado;
        }

        function ValidarCodigoTipoDocumento($data)
        {
          $codigo=$data["CodigoTipoDocumento"];
          if ($codigo == "")
          {
            return "Debe completar el Código";
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

        function ValidarNombreTipoDocumento($data)
        {
          $nombre=$data["NombreTipoDocumento"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoDocumento($data)
        {
          $codigo= $this->ValidarCodigoTipoDocumento($data);
          $nombre= $this->ValidarNombreTipoDocumento($data);
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

        function ValidarExistenciaCodigoTipoDocumentoParaInsertar($data)
        {
          $resultado = $this->mTipoDocumento->ObtenerCodigoTipoDocumentoParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoTipoDocumentoParaActualizar($data)
        {
          $resultado = $this->mTipoDocumento->ObtenerCodigoTipoDocumentoParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaNombreAbreviadoTipoDocumentoParaInsertar($data)
        {
          $resultado = $this->mTipoDocumento->ObtenerNombreAbreviadoTipoDocumentoParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Esta Abreviación ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaNombreAbreviadoTipoDocumentoParaActualizar($data)
        {
          $resultado = $this->mTipoDocumento->ObtenerNombreAbreviadoTipoDocumentoParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este Abreviación ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoDocumento($data)
        {
          $data["CodigoTipoDocumento"] = trim($data["CodigoTipoDocumento"]);
          $data["NombreTipoDocumento"] = trim($data["NombreTipoDocumento"]);
          $resultado1 = $this->ValidarTipoDocumento($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoDocumentoParaInsertar($data);
          $resultado3 = $this->ValidarExistenciaNombreAbreviadoTipoDocumentoParaInsertar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2 != "")
          {
            return $resultado2;
          }
          else if ($resultado3 != "")
          {
            return $resultado3;
          }
          else
          {
            $resultado = $this->mTipoDocumento->InsertarTipoDocumento($data);
            return (int)$resultado;
          }
        }

        function ActualizarTipoDocumento($data)
        {
          $data["CodigoTipoDocumento"] = trim($data["CodigoTipoDocumento"]);
          $data["NombreTipoDocumento"] = trim($data["NombreTipoDocumento"]);
          $resultado1 = $this->ValidarTipoDocumento($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoDocumentoParaActualizar($data);
          $resultado3 = $this->ValidarExistenciaNombreAbreviadoTipoDocumentoParaActualizar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2 != "")
          {
            return $resultado2;
          }
          else if ($resultado3 != "")
          {
            return $resultado3;
          }
          else
          {
            $resultado = $this->mTipoDocumento->ActualizarTipoDocumento($data);
            return "";
          }
        }

        function ValidarExistenciaTipoDocumentoEnComprobanteVenta($data)
        {
          $resultado = $this->mTipoDocumento->ConsultarTipoDocumentoEnComprobanteVenta($data);
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

        function BorrarTipoDocumento($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoDocumentoEnComprobanteVenta($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mTipoDocumento->BorrarTipoDocumento($data);
            return "";
          }
        }


        function ObtenerTipoDocumento($data)
        {
          $resultado = $this->mTipoDocumento->ObtenerTipoDocumento($data);
          return $resultado;
        }

        function ObtenerTipoDocumentoPorId($data)
        {
          $resultado = $this->mTipoDocumento->ObtenerTipoDocumentoPorId($data);
          return $resultado;
        }

        function ConsultarTiposDocumentoPorIndicadorDocumentoReporteCompra()
        {
          $resultado = $this->mTipoDocumento->ConsultarTiposDocumentoPorIndicadorDocumentoReporteCompra();
          return $resultado;
        }
}
