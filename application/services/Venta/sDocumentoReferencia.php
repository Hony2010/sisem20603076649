<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDocumentoReferencia extends MY_Service {

        public $DocumentoReferencia = array();
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
          $this->load->service("Catalogo/sCliente");
          $this->load->service('Configuracion/General/sParametroSistema');
          $this->load->service("Configuracion/General/sTipoCambio");
          $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
          $this->load->service("Configuracion/General/sFormaPago");
          $this->load->service("Configuracion/General/sMoneda");
          $this->load->service("Configuracion/Venta/sTipoTarjeta");
          $this->load->model("Base");
          $this->load->model('Venta/mDocumentoReferencia');
          $this->load->service('Venta/sDetalleComprobanteVenta');

          $this->DocumentoReferencia = $this->mDocumentoReferencia->DocumentoReferencia;
        }

        function ConsultarDocumentosReferencia($data)
        {
          $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
          $resultado = $this->mDocumentoReferencia->ConsultarDocumentosReferencia($data);
          foreach ($resultado as $key => $item) {
            $resultado[$key]["FechaEmision"] =convertirFechaES($resultado[$key]["FechaEmision"]);
            $resultado[$key]["FechaVencimiento"] =convertirFechaES($resultado[$key]["FechaVencimiento"]);
            // $resultado[$key]["DetallesComprobanteVenta"] =[];
            $resultado[$key]["DiferenciaSaldo"] = $resultado[$key]["Total"] - $resultado[$key]["SaldoNotaCredito"];
            $resultado[$key]["DetallesComprobanteVenta"] =$this->sDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($item);
          }
          return $resultado;
        }

        function ConsultarDocumentoReferenciaPorNota($data)
        {
          $resultado = $this->mDocumentoReferencia->ConsultarDocumentoReferenciaPorNota($data);

          return $resultado;
        }

        function ObtenerDocumentoReferenciaPorComprobanteYNota($data)
        {
          $resultado = $this->mDocumentoReferencia->ObtenerDocumentoReferenciaPorComprobanteYNota($data);
          return $resultado;
        }

        function ObtenerDocumentosReferenciaByComprobante($data)
        {
          $resultado = $this->mDocumentoReferencia->ObtenerDocumentosReferenciaByComprobante($data);
          return $resultado;
        }

        function InsertarDocumentoReferencia($data)
        {
          try {
            $nueva_data = $this->ParsearDataDocumentoReferencia($data);

            $resultado= $this->mDocumentoReferencia->InsertarDocumentoReferencia($nueva_data);

            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarDocumentoReferencia($data)
        {
          try {
            $resultado=$this->mDocumentoReferencia->ActualizarDocumentoReferencia($data);
            
            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function BorrarDocumentoReferencia($data)
        {
          //regla : validar que al eliminar el comprobante no tenga facturacion electronica enviado.
          // $resultado =[];// $this->sComprobanteElectronico->ObtenerComprobanteElectronicoPorIdDocumentoReferencia($data);
          $data["IdComprobanteNota"] = $data["IdComprobanteVenta"];
          $resultado = $this->mDocumentoReferencia->ObtenerDocumentosReferenciaByComprobante($data);

          if(count($resultado) >0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              $this->mDocumentoReferencia->BorrarDocumentoReferencia($value);
            }
          }

          return "";
        }

        function ParsearDataDocumentoReferencia($data)
        {
          $otra_data = $data;
            $otra_data["IdDocumentoReferencia"] = "";
            // $otra_data["CodigoTipoDocumentoReferencia"] = $data["IdTipoDocumento"];
            $otra_data["CodigoTipoDocumentoReferencia"] = $data["CodigoTipoDocumento"];
            $otra_data["SerieDocumentoReferencia"] = $data["SerieDocumento"];
            $otra_data["NumeroDocumentoReferencia"] = $data["NumeroDocumento"];
            $otra_data["FechaDocumentoReferencia"] = convertToDate($data["FechaEmision"]);
            $otra_data["TotalDocumentoReferencia"] = $data["Total"];
            $otra_data["NombreAbreviadoDocumentoReferencia"] = $data["NombreAbreviado"];

            return $otra_data;
        }

        function ActualizarSaldosEnDocumentoReferencia($data)
        {
          $resultado = $this->ObtenerDocumentoReferenciaPorComprobanteYNota($data);
          
          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $value["TotalNota"] = $data["Total"];
              $this->ActualizarDocumentoReferencia($value);
            }
          }
          return $data;
        }

}
