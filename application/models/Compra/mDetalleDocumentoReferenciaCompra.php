<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleDocumentoReferenciaCompra extends CI_Model {

        public $DetalleDocumentoReferenciaCompra = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DetalleDocumentoReferenciaCompra = $this->Base->Construir("DetalleDocumentoReferenciaCompra");
        }

        function ConsultarDetallesDocumentoReferencia($data)
        {
          $id=$data["IdComprobanteNota"];
          $query = $this->db->query("SELECT DDRC.*
                FROM DetalleDocumentoReferenciaCompra DDRC
                WHERE DDRC.IdComprobanteNota = '$id' AND DDRC.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDetalleDocumentoReferenciaCompra($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DetalleDocumentoReferenciaCompra);
          $this->db->insert('DetalleDocumentoReferenciaCompra', $resultado);
          $resultado["IdDetalleDocumentoReferenciaCompra"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDetalleDocumentoReferenciaCompra($data)
        {
          $id=$data["IdDetalleDocumentoReferencia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DetalleDocumentoReferenciaCompra);
          $this->db->where('IdDetalleDocumentoReferencia', $id);
          $this->db->update('DetalleDocumentoReferenciaCompra', $resultado);

          return $resultado;
        }

        function BorrarDetalleDocumentoReferenciaCompra($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDetalleDocumentoReferenciaCompra($data);
        }

        function BorrarDetallesDocumentoReferenciaCompraPorIdNota($data)
        {
          $id=$data["IdComprobanteNota"];
          $estado=ESTADO_ELIMINADO;
          $query = $this->db->query("UPDATE DetalleDocumentoReferenciaCompra DDRC
                SET DDRC.IndicadorEstado = '$estado'
                WHERE DDRC.IdComprobanteNota = '$id'");
          return $data;
        }
 }
