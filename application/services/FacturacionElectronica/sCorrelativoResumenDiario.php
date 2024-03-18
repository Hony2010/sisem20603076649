<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCorrelativoResumenDiario extends MY_Service {

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('FacturacionElectronica/mCorrelativoResumenDiario');
              $this->CorrelativoResumenDiario = $this->mCorrelativoResumenDiario->CorrelativoResumenDiario;
        }

        function ObtenerNuevoCorrelativoResumenDiario($data)
        {
          $correlativoResumenDiario = $this->mCorrelativoResumenDiario->ObtenerCorrelativoResumenDiario($data);

          if($correlativoResumenDiario!=null)
          {
            return $correlativoResumenDiario->UltimoNumeroResumenDiario;
          }
          else {
              $data["UltimoNumeroResumenDiario"] =1;
              $resultado=$this->InsertarCorrelativoResumenDiario($data);
              return $data["UltimoNumeroResumenDiario"];
          }
        }

        function IncrementarCorrelativoResumenDiario($data)
        {
          $correlativoResumenDiario = $this->mCorrelativoResumenDiario->ObtenerCorrelativoResumenDiario($data);

          if ($correlativoResumenDiario != null)
          {
            $data["UltimoNumeroResumenDiario"]=$correlativoResumenDiario->UltimoNumeroResumenDiario + 1;
            $resultado=$this->ActualizarCorrelativoResumenDiario($data);
          }
          else {
            $data["UltimoNumeroResumenDiario"]=1+1;
            $resultado=$this->InsertarCorrelativoResumenDiario($data);
          }

          return $data["UltimoNumeroResumenDiario"];
        }

        function InsertarCorrelativoResumenDiario($data)
        {
          $resultado = $this->mCorrelativoResumenDiario->InsertarCorrelativoResumenDiario($data);
          return $resultado;
        }

        function ActualizarCorrelativoResumenDiario($data)
        {
          $this->mCorrelativoResumenDiario->ActualizarCorrelativoResumenDiario($data);
          return "";
        }


}
