<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoActivo extends MY_Service {

        public $TipoActivo = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoActivo');
              $this->TipoActivo = $this->mTipoActivo->TipoActivo;
        }

        function ListarTiposActivo()
        {
          $resultado = $this->mTipoActivo->ListarTiposActivo();
          return $resultado;
        }

        function ValidarNombreTipoActivo($data)
        {
          $nombre=$data["NombreTipoActivo"];
          if ($nombre == "")
          {
            return "Debe completar el registro";
          }
          else
          {
            return "";
          }
        }

        function ValidarCuentaContables($data)
        {
          $cuenta=$data["CuentaContable"];
          if ($cuenta == "")
          {
            return "Debe completar el registro";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoActivo($data)
        {
          $resultado1= $this->ValidarNombreTipoActivo($data);

          if ($resultado1!="")
          {
            return $resultado1;
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoActivo($data)
        {
          $data["NombreTipoActivo"] = trim($data["NombreTipoActivo"]);
          $data["CuentaContable"] = trim($data["CuentaContable"]);
          $resultado = $this -> ValidarTipoActivo($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoActivo->InsertarTipoActivo($data);
            return $resultado;
          }
        }

        function ActualizarTipoActivo($data)
        {
          $data["NombreTipoActivo"] = trim($data["NombreTipoActivo"]);
          $data["CuentaContable"] = trim($data["CuentaContable"]);
          $resultado = $this -> ValidarTipoActivo($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $resultado = $this->mTipoActivo->ActualizarTipoActivo($data);
            return "";
          }
        }

        function ValidarExistenciaTipoActivoEnActivoFijo($data)
        {
          $resultado = $this->mTipoActivo->ConsultarTipoActivoEnActivoFijo($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Activo Fijo";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoActivo($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoActivoEnActivoFijo($data);
          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mTipoActivo->BorrarTipoActivo($data);
            return "";
          }
        }
}
