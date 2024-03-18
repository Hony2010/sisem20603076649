<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAsignacionSede extends MY_Service {

        public $TipoDocumento = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mAsignacionSede');
              $this->AsignacionSede = $this->mAsignacionSede->AsignacionSede;
        }

        function ListarAsignacionesSede()
        {
          $resultado = $this->mAsignacionSede->ListarAsignacionesSede();
          return $resultado;
        }

        function ConsultarTipoSede($data)
        {
          $resultado = $this->mAsignacionSede->ConsultarTipoSede($data);
          return $resultado;
        }

        function ConsultarAsignacionSede($data)
        {
          $resultado = $this->mAsignacionSede->ConsultarAsignacionSede($data);
          return $resultado;
        }

        function ValidarAsignacionSede($data)
        {
          $resultado = 0;
          foreach ($data["TiposSede"] as $key=>$value) {
            if ($value['Seleccionado'] == 'true') {
              $resultado++;
            }
          }

          if ($resultado == 0)
          {
            return "Debe Seleccionar uno o más Modulos de Sistema";
          }
          else
          {
            return "";
          }
        }

        function InsertarActualizarAsignacionSede($data)
        {
          foreach ($data["TiposSede"] as $key=>$value) {
            if ($value['Seleccionado'] == "true" and $value['IdAsignacionSede'] == "" )
            {
              $value["IdSede"] = $data["IdSede"];
              $resultado = $this->mAsignacionSede->InsertarAsignacionSede($value);
              $data["TiposSede"][$key]["IdAsignacionSede"] = $resultado;
            }
            else if($value['Seleccionado'] == "false" and $value['IdAsignacionSede'] != "" )
            {
              $value["IdSede"] = $data["IdSede"];
              $resultado = $this->mAsignacionSede->BorrarAsignacionSede($value);
              $data["TiposSede"][$key]["IdAsignacionSede"] = $resultado;
            }
          }
          return $data["TiposSede"];
        }

        function ObtenerAsignacionSedePorIdSedeYIdTipoSede($data) {
          $resultado = $this->mAsignacionSede->ObtenerAsignacionSedePorIdSedeYIdTipoSede($data);
          return $resultado;          
        }

        function ObtenerAsignacionSedeTipoAlmacenPorIdSede($data) {
          $data["IdTipoSede"] = ID_TIPO_SEDE_ALMACEN;
          $resultado = $this->ObtenerAsignacionSedePorIdSedeYIdTipoSede($data);
          return $resultado;
        }

        function ConsultarAsignacionesSedesPorIdTipoSedeAlmacen() {
          $data["IdTipoSede"] = ID_TIPO_SEDE_ALMACEN;
          $resultado = $this->mAsignacionSede->ConsultarAsignacionesSedesPorIdTipoSede($data);
          return $resultado;
        }

        function ObtenerAsignacionSedeTipoAlmacenPorNombreSede($data) {
          $data["IdTipoSede"] = ID_TIPO_SEDE_ALMACEN;
          $resultado = $this->mAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorNombreSede($data);
          return $resultado;
        }

}
