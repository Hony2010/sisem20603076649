<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoDocumentoIdentidad extends MY_Service {

        public $TipoDocumentoIdentidad = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoDocumentoIdentidad');
              $this->TipoDocumentoIdentidad = $this->mTipoDocumentoIdentidad->TipoDocumentoIdentidad;
        }

        function ListarTiposDocumentoIdentidad()
        {
          $resultado = $this->mTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();
          return $resultado;
        }

        function ValidarCodigoDocumentoIdentidad($data)
        {
          $codigo=$data["CodigoDocumentoIdentidad"];
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

        function ValidarNombreTipoDocumentoIdentidad($data)
        {
          $nombre=$data["NombreTipoDocumentoIdentidad"];
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

        function ValidarTipoDocumentoIdentidad($data)
        {
          $codigo= $this->ValidarCodigoDocumentoIdentidad($data);
          $nombre= $this->ValidarNombreTipoDocumentoIdentidad($data);
          $abreviatura = $this->ValidarNombreAbreviado($data);
          if ($codigo!="")
          {
            return $codigo;
          }
          else if ($nombre !="")
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

        function ValidarExistenciaCodigoTipoDocumentoIdentidadParaInsertar($data)
        {
          $resultado = $this->mTipoDocumentoIdentidad->ObtenerCodigoTipoDocumentoIdentidadParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoTipoDocumentoIdentidadParaActualizar($data)
        {
          $resultado = $this->mTipoDocumentoIdentidad->ObtenerCodigoTipoDocumentoIdentidadParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoDocumentoIdentidad($data)
        {
          $data["CodigoDocumentoIdentidad"] = trim($data["CodigoDocumentoIdentidad"]);
          $data["NombreTipoDocumentoIdentidad"] = trim($data["NombreTipoDocumentoIdentidad"]);
          $data["NombreAbreviado"] = trim($data["NombreAbreviado"]);
          $resultado1 = $this->ValidarTipoDocumentoIdentidad($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoDocumentoIdentidadParaInsertar($data);

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
            $resultado = $this->mTipoDocumentoIdentidad->InsertarTipoDocumentoIdentidad($data);
            return $resultado;
          }
        }

        function ActualizarTipoDocumentoIdentidad($data)
        {
          $data["CodigoDocumentoIdentidad"] = trim($data["CodigoDocumentoIdentidad"]);
          $data["NombreTipoDocumentoIdentidad"] = trim($data["NombreTipoDocumentoIdentidad"]);
          $data["NombreAbreviado"] = trim($data["NombreAbreviado"]);
          $resultado1 = $this->ValidarTipoDocumentoIdentidad($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoDocumentoIdentidadParaActualizar($data);

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
            $resultado = $this->mTipoDocumentoIdentidad->ActualizarTipoDocumentoIdentidad($data);
            return "";
          }
        }

        function ValidarExistenciaTipoDocumentoEnPersona($data)
        {
          $resultado = $this->mTipoDocumentoIdentidad->ConsultarTipoDocumentoIdentidadEnPersona($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Persona";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoDocumentoIdentidad($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoDocumentoEnPersona($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mTipoDocumentoIdentidad->BorrarTipoDocumentoIdentidad($data);
            return "";
          }
        }
}
