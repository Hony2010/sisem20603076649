<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDocumentoReferenciaCompra extends CI_Model {

        public $DocumentoReferenciaCompra = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DocumentoReferenciaCompra = $this->Base->Construir("DocumentoReferenciaCompra");
        }

        function ListarDocumentosReferencia($data)
        {
          $id=$data["IdDocumentoReferencia"];
          $query = $this->db->query("Select * from DocumentoReferenciaCompra
                                     where IdDocumentoReferencia = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDocumentosReferenciaByComprobante($data)
        {
          $id=$data["IdComprobanteNota"];
          $query = $this->db->query("Select * from DocumentoReferenciaCompra
                                    where IdComprobanteNota = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDocumentoReferenciaPorComprobanteYNota($data)
        {
          $id=$data["IdComprobanteNota"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("Select * from DocumentoReferenciaCompra
                                    where IdComprobanteNota = '$id' AND IdComprobanteCompra = '$comprobante' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarDocumentosReferencia($data)
        {
          $id=$data["IdComprobanteNota"];
          $query = $this->db->query("Select
                                    CC.*, TC.NombreTipoCompra,
                                    TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda, TD.CodigoTipoDocumento,
                                    FP.NombreFormaPago, U.AliasUsuarioVenta,
                                    Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                                    MND.SimboloMoneda
                                    from documentoreferenciacompra DRC
                                    inner join comprobantecompra CC on CC.IdComprobanteCompra = DRC.IdComprobanteCompra
                                    Inner Join TipoCompra As TC on TC.IdTipoCompra = CC.IdTipoCompra
                                    Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                                    Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                                    Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                                    Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                                    Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                                    Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                                    where DRC.IdComprobanteNota = '$id' AND DRC.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarDocumentosReferenciaNotaComprobanteCompra($data)
        {
          $id=$data["IdComprobanteCompra"];
          // $query = $this->db->query("select CC.IdComprobanteCompra, CC.IdAsignacionSede, DCC.*
          //     from comprobantecompra CC
          //     inner join detallecomprobantecompra DCC on DCC.IdComprobanteCompra = CC.IdComprobanteCompra
          //     where CC.IdComprobanteCompra = '$id' and DCC.IndicadorEstado = 'A' and CC.IndicadorEstado = 'A'");
          $query = $this->db->query("select DRC.*, CC.*, DCC.*
                  from comprobantecompra CC
                  inner join documentoreferenciacompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                  inner join detallecomprobantecompra DCC on DCC.IdComprobanteCompra = DRC.IdComprobanteNota
                  where DRC.IdComprobanteNota = '$id' and DCC.IndicadorEstado = 'A' and DRC.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
                  from comprobantecompra CC
                  inner join documentoreferenciacompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                  inner join documentoreferencianotaentrada DRNE on DRC.IdComprobanteCompra = DRNE.IdComprobanteCompra
                  inner join movimientoalmacen MA on  DRNE.IdNotaEntrada = MA.IdNotaEntrada
                  WHERE MA.IdProducto = '$id' And MA.IdAsignacionSede = '$sede' And DRC.IdComprobanteNota = '$comprobante' And
                  ((CC.IndicadorEstado='A') And (DRC.IndicadorEstado='A'))");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDocumentoReferenciaCompra($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoReferenciaCompra);
          $this->db->insert('DocumentoReferenciaCompra', $resultado);
          $resultado["IdDocumentoReferencia"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDocumentoReferenciaCompra($data)
        {
          $id=$data["IdDocumentoReferencia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoReferenciaCompra);
          $this->db->where('IdDocumentoReferencia', $id);
          $this->db->update('DocumentoReferenciaCompra', $resultado);

          return $resultado;
        }

        function BorrarDocumentoReferenciaCompra($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDocumentoReferenciaCompra($data);
        }

 }
