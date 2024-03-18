<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDocumentoSalidaZofra extends MY_Service {

        public $DocumentoSalidaZofra = array();

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
          $this->load->model('Inventario/mDocumentoSalidaZofra');
          $this->load->model('Catalogo/mMercaderia');

          $this->DocumentoSalidaZofra = $this->mDocumentoSalidaZofra->DocumentoSalidaZofra;
        }

        function Cargar()
        {

        }

        function ObtenerDocumentoSalidaZofraPorIdDocumentoSalidaZofra($data)
        {
          $resultado = $this->mDocumentoSalidaZofra->ObtenerDocumentoSalidaZofraPorIdDocumentoSalidaZofra($data);
          return $resultado;
        }

        function AgregarDocumentoSalidaZofra($data)
        {
          $data["NumeroDocumentoSalidaZofra"] = $data["NumeroDocumento"].'/'.$data["SerieDocumento"];
          $resultado = $this->ObtenerDocumentoSalidaZofraPorNumeroDocumentoSalidaZofra($data);

          if(count($resultado) > 0)
          {
            $resultado[0]["NumeroDocumentoSalidaZofra"] = $data["NumeroDocumentoSalidaZofra"];
            $resultado[0]["FechaEmisionDocumentoSalidaZofra"] = $data["FechaEmision"];
            $response = $this->ActualizarDocumentoSalidaZofra($resultado[0]);
            return $response;
          }
          else {
            $data["IdDocumentoSalidaZofra"] = "";
            $data["FechaEmisionDocumentoSalidaZofra"] = $data["FechaEmision"];
            $response = $this->InsertarDocumentoSalidaZofra($data);
            return $response;
          }
        }

        function AgregarDocumentoSalidaZofraInventarioInicial($data)
        {
          // $data["NumeroDocumentoSalidaZofra"] = $data["NumeroDocumento"].'/'.$data["SerieDocumento"];
          $resultado = $this->ObtenerDocumentoSalidaZofraPorNumeroDocumentoSalidaZofra($data);

          if(count($resultado) > 0)
          {
            $resultado[0]["NumeroDocumentoSalidaZofra"] = $data["NumeroDocumentoSalidaZofra"];
            $resultado[0]["FechaEmisionDocumentoSalidaZofra"] = $data["FechaEmisionDocumentoSalidaZofra"];
            $response = $this->ActualizarDocumentoSalidaZofra($resultado[0]);
            return $response;
          }
          else {
            $data["IdDocumentoSalidaZofra"] = "";
            $response = $this->InsertarDocumentoSalidaZofra($data);
            return $response;
          }
        }

        function InsertarDocumentoSalidaZofra($data)
        {
          try {

            $resultadoValidacion = "";
            if($resultadoValidacion == "")
            {
              $resultado= $this->mDocumentoSalidaZofra->InsertarDocumentoSalidaZofra($data);
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

        function ActualizarDocumentoSalidaZofra($data)
        {
          try {
            $resultadoValidacion = "";
            if($resultadoValidacion == "")
            {
              $resultado=$this->mDocumentoSalidaZofra->ActualizarDocumentoSalidaZofra($data);

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

        function BorrarDocumentoSalidaZofra($data)
        {
          $this->mDocumentoSalidaZofra->BorrarDocumentoSalidaZofra($data);

          return "";
        }

        function ObtenerDocumentoSalidaZofraPorNumeroDocumentoSalidaZofra($data)
        {
          $resultado = $this->mDocumentoSalidaZofra->ObtenerDocumentoSalidaZofraPorNumeroDocumentoSalidaZofra($data);
          return $resultado;
        }

}
