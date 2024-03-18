<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sConceptoNotaCreditoDebito extends MY_Service {

        public $ConceptoNotaCreditoDebito = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Venta/mConceptoNotaCreditoDebito');
              $this->ConceptoNotaCreditoDebito = $this->mConceptoNotaCreditoDebito->ConceptoNotaCreditoDebito;
        }

        function ListarConceptosNotaCredito()
        {
          $data["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTACREDITO;
          $resultado = $this->mConceptoNotaCreditoDebito->ListarConceptosNotaCredito($data);
          return $resultado;
        }

        function ObtenerConceptoPorMotivoNotaCredito($data)
        {
          $data["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTACREDITO;
          $resultado = $this->mConceptoNotaCreditoDebito->ObtenerConceptoPorMotivoNotaCredito($data);
          return $resultado;
        }

        function ListarConceptosNotaDebito()
        {
          $data["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTADEBITO;
          $resultado = $this->mConceptoNotaCreditoDebito->ListarConceptosNotaDebito($data);
          return $resultado;
        }

        function ObtenerConceptoPorMotivoNotaDebito($data)
        {
          $data["IdTipoDocumento"] = ID_TIPODOCUMENTO_NOTADEBITO;
          $resultado = $this->mConceptoNotaCreditoDebito->ObtenerConceptoPorMotivoNotaDebito($data);
          return $resultado;
        }

}
