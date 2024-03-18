<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCorrelativoResumenComprobanteFisicoContingencia extends MY_Service {

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('FacturacionElectronica/mCorrelativoResumenComprobanteFisicoContingencia');
              $this->CorrelativoResumenComprobanteFisicoContingencia = $this->mCorrelativoResumenComprobanteFisicoContingencia->CorrelativoResumenComprobanteFisicoContingencia;
        }

        function ObtenerNuevoCorrelativoResumenComprobanteFisicoContingencia($data)
        {
          $correlativoresumencomprobantefisicocontingencia = $this->mCorrelativoResumenComprobanteFisicoContingencia->ObtenerCorrelativoResumenComprobanteFisicoContingencia($data);

          if($correlativoresumencomprobantefisicocontingencia!=null)
          {
            return $correlativoresumencomprobantefisicocontingencia->UltimoComprobanteFisicoContingencia;
          }
          else {
              $data["UltimoComprobanteFisicoContingencia"] =1;
              $resultado=$this->InsertarCorrelativoResumenComprobanteFisicoContingencia($data);
              return $data["UltimoComprobanteFisicoContingencia"];
          }
        }

        function IncrementarCorrelativoResumenComprobanteFisicoContingencia($data)
        {
          $correlativoresumencomprobantefisicocontingencia = $this->mCorrelativoResumenComprobanteFisicoContingencia->ObtenerCorrelativoResumenComprobanteFisicoContingencia($data);

          if ($correlativoresumencomprobantefisicocontingencia != null)
          {
            $data["UltimoComprobanteFisicoContingencia"]=$correlativoresumencomprobantefisicocontingencia->UltimoComprobanteFisicoContingencia + 1;
            $resultado=$this->ActualizarCorrelativoResumenComprobanteFisicoContingencia($data);
          }
          else {
            // $data["UltimoComprobanteFisicoContingencia"]=1+1;
            $data["UltimoComprobanteFisicoContingencia"]=1;
            $resultado=$this->InsertarCorrelativoResumenComprobanteFisicoContingencia($data);
          }
          return $data["UltimoComprobanteFisicoContingencia"];
        }

        function InsertarCorrelativoResumenComprobanteFisicoContingencia($data)
        {
          $resultado = $this->mCorrelativoResumenComprobanteFisicoContingencia->InsertarCorrelativoResumenComprobanteFisicoContingencia($data);
          return $resultado;
        }

        function ActualizarCorrelativoResumenComprobanteFisicoContingencia($data)
        {
          $this->mCorrelativoResumenComprobanteFisicoContingencia->ActualizarCorrelativoResumenComprobanteFisicoContingencia($data);
          return "";
        }


}
