<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleDocumentoReferencia extends MY_Service {

        public $DetalleDocumentoReferencia = array();

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
          $this->load->model('Venta/mDetalleDocumentoReferencia');
          $this->load->service('Venta/sDetalleComprobanteVenta');

          $this->DetalleDocumentoReferencia = $this->mDetalleDocumentoReferencia->DetalleDocumentoReferencia;
        }

        function ConsultarDetallesDocumentoReferencia($data)
        {
          $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
          $resultado = $this->mDetalleDocumentoReferencia->ConsultarDetallesDocumentoReferencia($data);
          return $resultado;
        }

        function InsertarDetalleDocumentoReferencia($data)
        {
          try {
            $nueva_data = $this->ParsearDataDetalleDocumentoReferencia($data);

            $resultado= $this->mDetalleDocumentoReferencia->InsertarDetalleDocumentoReferencia($nueva_data);

            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarDetalleDocumentoReferencia($data)
        {
          try {
            // $data["FechaEmision"]=convertToDate($data["FechaEmision"]);
            // $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);

            $resultado=$this->mDetalleDocumentoReferencia->ActualizarDetalleDocumentoReferencia($data);
            // $IdDocumentoReferencia =$data["IdDocumentoReferencia"];
            // $resultado["DetallesDetalleDocumentoReferencia"] = $this->sDetalleDetalleDocumentoReferencia->ActualizarDetallesDetalleDocumentoReferencia($IdDocumentoReferencia, $data["DetallesDetalleDocumentoReferencia"]);
            // $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
            // $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function BorrarDetalleDocumentoReferencia($data)
        {
          //regla : validar que al eliminar el comprobante no tenga facturacion electronica enviado.
          // $resultado =[];// $this->sComprobanteElectronico->ObtenerComprobanteElectronicoPorIdDocumentoReferencia($data);
          $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
          $resultado = $this->mDetalleDocumentoReferencia->ObtenerDocumentosReferenciaByComprobante($data);

          if(count($resultado) >0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              $this->mDetalleDocumentoReferencia->BorrarDetalleDocumentoReferencia($value);
            }
          }
          return "";
        }

        function ParsearDataDetalleDocumentoReferencia($data)
        {
          $otra_data = $data;
          $otra_data["IdDetalleDocumentoReferencia"] = "";
          $otra_data["IdComprobanteNota"] = $data["IdComprobanteNota"];
          $otra_data["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
          $otra_data["IdDetalleComprobanteVenta"] = $data["IdDetalleReferencia"];
          $otra_data["SerieDocumentoReferencia"] = "";
          $otra_data["NumeroDocumentoReferencia"] = "";
          $otra_data["FechaDocumentoReferencia"] = "";
          $otra_data["SaldoDetalleDocumentoReferencia"] = $data["SubTotal"];
          
          return $otra_data;
        }

        function BorrarDetallesDocumentoReferenciaPorIdNota($data)
        {
          // $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
          $resultado = $this->mDetalleDocumentoReferencia->BorrarDetallesDocumentoReferenciaPorIdNota($data);
          return $resultado;
        }

}
