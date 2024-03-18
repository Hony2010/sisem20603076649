<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sResumenComprobanteFisicoContingencia extends MY_Service {

        public $ResumenComprobanteFisicoContingencia = array();

        public function __construct()
        {
          parent::__construct();
          $this->load->database();
          $this->load->library('shared');
          $this->load->library('sesionusuario');
          $this->load->library('mapper');
          $this->load->library('herencia');
          $this->load->library('reporter');
          $this->load->library('imprimir');
          $this->load->helper("date");
          $this->load->model("Base");
          $this->load->model('FacturacionElectronica/mResumenComprobanteFisicoContingencia');

          $this->ResumenComprobanteFisicoContingencia = $this->mResumenComprobanteFisicoContingencia->ResumenComprobanteFisicoContingencia;
        }

        function InsertarResumenComprobanteFisicoContingencia($data)
        {
          $validar = $this->ValidarResumenComprobanteFisicoContingencia($data);
          if(count($validar) > 0)
          {
            $data["IdResumenComprobanteFisicoContingencia"] = $validar[0]["IdResumenComprobanteFisicoContingencia"];
            $resultado = $this->ActualizarResumenComprobanteFisicoContingencia($data);
          }
          else {
            // code...
            $resultado=$this->mResumenComprobanteFisicoContingencia->InsertarResumenComprobanteFisicoContingencia($data);
          }
          return $resultado;
        }

        function ValidarResumenComprobanteFisicoContingencia($data)
        {
          $resultado=$this->mResumenComprobanteFisicoContingencia->ValidarResumenComprobanteFisicoContingencia($data);
          return $resultado;
        }

        function ActualizarResumenComprobanteFisicoContingencia($data)
        {
          $resultado=$this->mResumenComprobanteFisicoContingencia->ActualizarResumenComprobanteFisicoContingencia($data);
          return $resultado;
        }

        function BorrarResumenComprobanteFisicoContingencia($data)
        {
          $resultado = $this->mResumenComprobanteFisicoContingencia->ObtenerResumenesComprobanteFisicoContingenciaByComprobante($data);

          return $resultado;
        }


}
