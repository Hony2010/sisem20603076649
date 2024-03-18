<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCorrelativoComunicacionBaja extends MY_Service {

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('FacturacionElectronica/mCorrelativoComunicacionBaja');
              $this->CorrelativoComunicacionBaja = $this->mCorrelativoComunicacionBaja->CorrelativoComunicacionBaja;
        }

        function ObtenerNuevoCorrelativoComunicacionBaja($data)
        {
          $correlativocomunicacionbaja = $this->mCorrelativoComunicacionBaja->ObtenerCorrelativoComunicacionBaja($data);

          if($correlativocomunicacionbaja!=null)
          {
            return $correlativocomunicacionbaja->UltimoNumeroComunicacionBaja;
          }
          else {
              $data["UltimoNumeroComunicacionBaja"] =1;
              $resultado=$this->InsertarCorrelativoComunicacionBaja($data);
              return $data["UltimoNumeroComunicacionBaja"];
          }
        }

        function IncrementarCorrelativoComunicacionBaja($data)
        {
          $correlativocomunicacionbaja = $this->mCorrelativoComunicacionBaja->ObtenerCorrelativoComunicacionBaja($data);

          if ($correlativocomunicacionbaja != null)
          {
            $data["UltimoNumeroComunicacionBaja"]=$correlativocomunicacionbaja->UltimoNumeroComunicacionBaja + 1;
            $resultado=$this->ActualizarCorrelativoComunicacionBaja($data);
          }
          else {
            $data["UltimoNumeroComunicacionBaja"]=1+1;
            $resultado=$this->InsertarCorrelativoComunicacionBaja($data);
          }

          return $data["UltimoNumeroComunicacionBaja"];
        }

        function InsertarCorrelativoComunicacionBaja($data)
        {
          $resultado = $this->mCorrelativoComunicacionBaja->InsertarCorrelativoComunicacionBaja($data);
          return $resultado;
        }

        function ActualizarCorrelativoComunicacionBaja($data)
        {
          $this->mCorrelativoComunicacionBaja->ActualizarCorrelativoComunicacionBaja($data);
          return "";
        }


}
