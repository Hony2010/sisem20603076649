<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleDocumentoReferencia extends CI_Model {

        public $DetalleDocumentoReferencia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DetalleDocumentoReferencia = $this->Base->Construir("DetalleDocumentoReferencia");
        }

        function ConsultarDetallesDocumentoReferencia($data)
        {
          $id=$data["IdComprobanteNota"];
          $query = $this->db->query("SELECT DDRC.*
                FROM DetalleDocumentoReferencia DDRC
                WHERE DDRC.IdComprobanteNota = '$id' AND DDRC.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDetalleDocumentoReferencia($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DetalleDocumentoReferencia);
          $this->db->insert('DetalleDocumentoReferencia', $resultado);
          $resultado["IdDetalleDocumentoReferencia"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDetalleDocumentoReferencia($data)
        {
          $id=$data["IdDetalleDocumentoReferencia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DetalleDocumentoReferencia);
          $this->db->where('IdDetalleDocumentoReferencia', $id);
          $this->db->update('DetalleDocumentoReferencia', $resultado);

          return $resultado;
        }

        function BorrarDetalleDocumentoReferencia($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDetalleDocumentoReferencia($data);
        }

        function BorrarDetallesDocumentoReferenciaPorIdNota($data)
        {
          $id=$data["IdComprobanteNota"];
          $estado=ESTADO_ELIMINADO;
          $query = $this->db->query("
            UPDATE DetalleDocumentoReferencia
            SET IndicadorEstado = '$estado'
            WHERE IdComprobanteNota = '$id'");
          return $data;
        }
 }
