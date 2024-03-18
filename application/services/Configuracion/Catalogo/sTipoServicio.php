<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoServicio extends MY_Service {

        public $TipoServicio = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoServicio');
              $this->TipoServicio = $this->mTipoServicio->TipoServicio;
        }

        function ListarTiposServicio()
        {
          $resultado = $this->mTipoServicio->ListarTiposServicio();
          return $resultado;
        }

        function ValidarTipoServicio($data)
        {
          $nombre=$data["NombreTipoServicio"];
          if ($nombre == "")
          {
            return "Debe completar el Nombre";
          }
          else
          {
            return "";
          }
        }

        function InsertarTipoServicio($data)
        {
          $data["NombreTipoServicio"] = trim($data["NombreTipoServicio"]);
          $resultado = $this -> ValidarTipoServicio($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $resultado=$this->mTipoServicio->InsertarTipoServicio($data);
            return $resultado;
          }
        }

        function ActualizarTipoServicio($data)
        {
          $data["NombreTipoServicio"] = trim($data["NombreTipoServicio"]);
          $resultado = $this -> ValidarTipoServicio($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $this->mTipoServicio->ActualizarTipoServicio($data);
            return "";
          }
        }

        function ValidarExistenciaTipoServicioEnServicio($data)
        {
          $resultado = $this->mTipoServicio->ConsultarTipoServicioEnServicio($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Servicio";
          }
          else
          {
            return "";
          }
        }

        function BorrarTipoServicio($data)
        {
          $resultado1= $this -> ValidarExistenciaTipoServicioEnServicio($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mTipoServicio->BorrarTipoServicio($data);
            return "";
          }
        }
}
