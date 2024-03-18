<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleDocumentoReferenciaCompra extends MY_Service {

        public $DetalleDocumentoReferenciaCompra = array();

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
          $this->load->model('Compra/mDetalleDocumentoReferenciaCompra');
          $this->load->service('Compra/sDetalleComprobanteCompra');

          $this->DetalleDocumentoReferenciaCompra = $this->mDetalleDocumentoReferenciaCompra->DetalleDocumentoReferenciaCompra;
        }

        function ConsultarDetallesDocumentoReferencia($data)
        {
          $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
          $resultado = $this->mDetalleDocumentoReferenciaCompra->ConsultarDetallesDocumentoReferencia($data);
          return $resultado;
        }

        function InsertarDetalleDocumentoReferenciaCompra($data)
        {
          try {
            $nueva_data = $this->ParsearDataDetalleDocumentoReferenciaCompra($data);

            $resultado= $this->mDetalleDocumentoReferenciaCompra->InsertarDetalleDocumentoReferenciaCompra($nueva_data);

            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarDetalleDocumentoReferenciaCompra($data)
        {
          try {
            // $data["FechaEmision"]=convertToDate($data["FechaEmision"]);
            // $data["FechaVencimiento"]=convertToDate($data["FechaVencimiento"]);

            $resultado=$this->mDetalleDocumentoReferenciaCompra->ActualizarDetalleDocumentoReferenciaCompra($data);
            // $IdDocumentoReferencia =$data["IdDocumentoReferencia"];
            // $resultado["DetallesDetalleDocumentoReferenciaCompra"] = $this->sDetalleDetalleDocumentoReferenciaCompra->ActualizarDetallesDetalleDocumentoReferenciaCompra($IdDocumentoReferencia, $data["DetallesDetalleDocumentoReferenciaCompra"]);
            // $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
            // $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function BorrarDetalleDocumentoReferenciaCompra($data)
        {
          //regla : validar que al eliminar el comprobante no tenga facturacion electronica enviado.
          // $resultado =[];// $this->sComprobanteElectronico->ObtenerComprobanteElectronicoPorIdDocumentoReferencia($data);
          $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
          $resultado = $this->mDetalleDocumentoReferenciaCompra->ObtenerDocumentosReferenciaByComprobante($data);

          if(count($resultado) >0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              $this->mDetalleDocumentoReferenciaCompra->BorrarDetalleDocumentoReferenciaCompra($value);
            }
          }
          return "";
        }

        function ParsearDataDetalleDocumentoReferenciaCompra($data)
        {
          $otra_data = $data;
          $otra_data["IdDetalleDocumentoReferenciaCompra"] = "";
          $otra_data["IdComprobanteNota"] = $data["IdComprobanteNota"];
          $otra_data["IdComprobanteCompra"] = $data["IdComprobanteCompra"];
          $otra_data["IdDetalleComprobanteCompra"] = $data["IdDetalleReferencia"];
          $otra_data["SerieDocumentoReferencia"] = "";
          $otra_data["NumeroDocumentoReferencia"] = "";
          $otra_data["FechaDocumentoReferencia"] = "";
          $otra_data["SaldoDetalleDocumentoReferencia"] = $data["CostoItem"];
          
          return $otra_data;
        }

        function BorrarDetallesDocumentoReferenciaCompraPorIdNota($data)
        {
          // $data["IdComprobanteNota"] = $data["IdComprobanteCompra"];
          $resultado = $this->mDetalleDocumentoReferenciaCompra->BorrarDetallesDocumentoReferenciaCompraPorIdNota($data);
          return $resultado;
        }

}
