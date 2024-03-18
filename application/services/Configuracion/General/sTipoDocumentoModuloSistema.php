<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoDocumentoModuloSistema extends MY_Service {

        public $TipoDocumento = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoDocumentoModuloSistema');
              $this->TipoDocumentoModuloSistema = $this->mTipoDocumentoModuloSistema->TipoDocumentoModuloSistema;
        }

        function ListarTiposDocumentoModuloSistema()
        {
          $resultado = $this->mTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistema();
          return $resultado;
        }

        function ListarTiposDocumentoModuloSistemaPorIdModulo($data, $excluir)
        {
          $resultado = $this->mTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($data, $excluir);
          return $resultado;
        }

        function ConsultarModuloSistema($data)
        {
          $resultado = $this->mTipoDocumentoModuloSistema->ConsultarModuloSistema($data);
          return $resultado;
        }

        function ValidarTipoDocumentoModuloSistema($data)
        {
          $resultado = 0;
          foreach ($data["ModulosSistema"] as $key=>$value) {
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

        function InsertarActualizarTipoDocumentoModuloSistema($data)
        {
          foreach ($data["ModulosSistema"] as $key=>$value) {
            if ($value['Seleccionado'] == "true" and $value['IdTipoDocumentoModuloSistema'] == "" )
            {
              $value["IdTipoDocumento"] = $data["IdTipoDocumento"];
              $resultado = $this->mTipoDocumentoModuloSistema->InsertarTipoDocumentoModuloSistema($value);
              $data["ModulosSistema"][$key]["IdTipoDocumentoModuloSistema"] = $resultado;
            }
            else if($value['Seleccionado'] == "false" and $value['IdTipoDocumentoModuloSistema'] != "" )
            {
              $value["IdTipoDocumento"] = $data["IdTipoDocumento"];
              $resultado = $this->mTipoDocumentoModuloSistema->BorrarTipoDocumentoModuloSistema($value);
              $data["ModulosSistema"][$key]["IdTipoDocumentoModuloSistema"] = $resultado;
            }
          }
          return $data["ModulosSistema"];
        }
}
