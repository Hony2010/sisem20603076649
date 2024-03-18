<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoExistencia extends MY_Service {

        public $TipoExistencia = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoExistencia');
              $this->TipoExistencia = $this->mTipoExistencia->TipoExistencia;
        }

        function ListarTiposExistencia()
        {
          $resultado = $this->mTipoExistencia->ListarTiposExistencia();
          return $resultado;
        }

        function ValidarCodigoTipoExistencia($data)
        {
          $codigo=$data["CodigoTipoExistencia"];
          if ($codigo == "")
          {
            return "Debe completar el registro";
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

        function ValidarNombreTipoExistencia($data)
        {
          $descripcion=$data["NombreTipoExistencia"];
          if ($descripcion == "")
          {
            return "Debe completar el registro";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoExistencia($data)
        {
          $resultado1= $this->ValidarCodigoTipoExistencia($data);
          $resultado2= $this->ValidarNombreTipoExistencia($data);

          if ($resultado1!="")
          {
            return $resultado1;
          }
          else if ($resultado2 !="")
          {
            return $resultado2;
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoTipoExistenciaParaInsertar($data)
        {
          $resultado = $this->mTipoExistencia->ObtenerCodigoTipoExistenciaParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarExistenciaCodigoTipoExistenciaParaActualizar($data)
        {
          $resultado = $this->mTipoExistencia->ObtenerCodigoTipoExistenciaParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoExistencia($data)
        {
          $data["CodigoTipoExistencia"] = trim($data["CodigoTipoExistencia"]);
          $data["NombreTipoExistencia"] = trim($data["NombreTipoExistencia"]);
          $resultado1 = $this->ValidarTipoExistencia($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoExistenciaParaInsertar($data);

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
            $resultado = $this->mTipoExistencia->InsertarTipoExistencia($data);
            return $resultado;
          }
        }

        function ActualizarTipoExistencia($data)
        {
          $data["CodigoTipoExistencia"] = trim($data["CodigoTipoExistencia"]);
          $data["NombreTipoExistencia"] = trim($data["NombreTipoExistencia"]);
          $resultado1 = $this->ValidarTipoExistencia($data);
          $resultado2 = $this->ValidarExistenciaCodigoTipoExistenciaParaActualizar($data);

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
            $resultado = $this->mTipoExistencia->ActualizarTipoExistencia($data);
            return "";
          }
        }

        function ValidarExistenciaTipoExistenciaEnMercaderia($data)
        {
          $resultado = $this->mTipoExistencia->ConsultarTipoExistenciaEnMercaderia($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Mercadería";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoExistencia($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoExistenciaEnMercaderia($data);
          if ($resultado1 != "")
          {
            return($resultado1);
          }
          else
          {
            $this->mTipoExistencia->BorrarTipoExistencia($data);
            return "";
          }
        }
}
