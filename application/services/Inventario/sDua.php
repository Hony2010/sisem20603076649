<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDua extends MY_Service {

        public $Dua = array();

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
          $this->load->model('Inventario/mDua');
          $this->load->model('Catalogo/mMercaderia');

          $this->Dua = $this->mDua->Dua;
        }

        function Cargar()
        {

        }

        function ObtenerDuaPorIdDua($data)
        {
          $resultado = $this->mDua->ObtenerDuaPorIdDua($data);
          return $resultado;
        }

        function AgregarDua($data)
        {
          $fecha = explode("-", $data["FechaEmision"]);
          $data["NumeroDua"] = $data["SerieDocumento"].'-'.$fecha[0].'-'.$data["NumeroDocumento"];

          $resultado = $this->ObtenerDuaPorNumeroDua($data);
          if(count($resultado) > 0)
          {
            $resultado[0]["NumeroDua"] = $data["NumeroDua"];
            $resultado[0]["FechaEmisionDua"] = $data["FechaEmision"];
            $response = $this->ActualizarDua($resultado[0]);
            return $response;
          }
          else {
            $data["IdDua"] = "";
            $data["FechaEmisionDua"] = $data["FechaEmision"];
            $response = $this->InsertarDua($data);
            return $response;
          }
        }

        function AgregarDuaInventarioInicial($data)
        {
          // $fecha = explode("-", $data["FechaEmision"]);
          // $data["NumeroDua"] = $data["SerieDocumento"].'-'.$fecha[0].'-'.$data["NumeroDocumento"];
          $resultado = $this->ObtenerDuaPorNumeroDua($data);
          if(count($resultado) > 0)
          {
            $resultado[0]["NumeroDua"] = $data["NumeroDua"];
            $resultado[0]["FechaEmisionDua"] = $data["FechaEmisionDua"];
            $response = $this->ActualizarDua($resultado[0]);
            return $response;
          }
          else {
            $data["IdDua"] = "";
            // $data["FechaEmisionDua"] = $data["FechaEmision"];
            $response = $this->InsertarDua($data);
            return $response;
          }
        }

        function InsertarDua($data)
        {
          try {

            $resultadoValidacion = "";
            if($resultadoValidacion == "")
            {
              $resultado= $this->mDua->InsertarDua($data);
              return $resultado;
            }
            else
            {
              $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
              return $resultado;
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarDua($data)
        {
          try {
            $resultadoValidacion = "";
            if($resultadoValidacion == "")
            {
              $resultado=$this->mDua->ActualizarDua($data);

              return $resultado;
            }
            else
            {
              throw new Exception(nl2br($resultadoValidacion));
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function BorrarDua($data)
        {
          $this->mDua->BorrarDua($data);

          return "";
        }

        function ObtenerDuaPorNumeroDua($data)
        {
          $resultado = $this->mDua->ObtenerDuaPorNumeroDua($data);
          return $resultado;
        }

}
