<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDocumentoReferencia extends CI_Model {

        public $DocumentoReferencia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DocumentoReferencia = $this->Base->Construir("DocumentoReferencia");
        }

        function ListarDocumentosReferencia($data)
        {
          $id=$data["IdDocumentoReferencia"];
          $query = $this->db->query("Select * from DocumentoReferencia
                                     where IdDocumentoReferencia = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarDocumentoReferenciaPorNota($data)
        {
          $nota=$data["IdComprobanteNota"];
          $venta=$data["IdComprobanteVenta"];
          $query = $this->db->query("Select * from DocumentoReferencia
                                     where IdComprobanteNota = '$nota' AND IdComprobanteVenta = '$venta' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDocumentosReferenciaByComprobante($data)
        {
          $id=$data["IdComprobanteNota"];
          $query = $this->db->query("Select * from DocumentoReferencia
                                    where IdComprobanteNota = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDocumentoReferenciaPorComprobanteYNota($data)
        {
          $id=$data["IdComprobanteNota"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("Select * from DocumentoReferencia
                                    where IdComprobanteNota = '$id' AND IdComprobanteVenta = '$comprobante' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarDocumentosReferencia($data)
        {
          $id=$data["IdComprobanteNota"];
          $query = $this->db->query("Select
              CV.*, TV.NombreTipoVenta,
              TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda, TD.CodigoTipoDocumento,
              FP.NombreFormaPago, U.AliasUsuarioVenta,
              Per.RazonSocial,Per.NumeroDocumentoIdentidad,
              MND.SimboloMoneda
              from documentoreferencia DR
              inner join comprobanteventa CV on CV.IdComprobanteVenta = DR.IdComprobanteVenta
              Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
              Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
              Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
              Inner Join Cliente As C on C.IdPersona = CV.IdCliente
              Inner Join Persona As Per on Per.IdPersona = C.IdPersona
              Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
              Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
              where DR.idcomprobantenota= '$id' AND DR.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDocumentoReferencia($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoReferencia);
          $this->db->insert('DocumentoReferencia', $resultado);
          $resultado["IdDocumentoReferencia"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDocumentoReferencia($data)
        {
          $id=$data["IdDocumentoReferencia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoReferencia);
          $this->db->where('IdDocumentoReferencia', $id);
          $this->db->update('DocumentoReferencia', $resultado);

          return $resultado;
        }

        function BorrarDocumentoReferencia($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDocumentoReferencia($data);
        }

 }
