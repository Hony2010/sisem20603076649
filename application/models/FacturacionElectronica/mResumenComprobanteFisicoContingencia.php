<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mResumenComprobanteFisicoContingencia extends CI_Model {

        public $ResumenComprobanteFisicoContingencia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->ResumenComprobanteFisicoContingencia = $this->Base->Construir("ResumenComprobanteFisicoContingencia");
        }

        function InsertarResumenComprobanteFisicoContingencia($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->ResumenComprobanteFisicoContingencia);
          $this->db->insert('ResumenComprobanteFisicoContingencia', $resultado);
          $resultado["IdResumenComprobanteFisicoContingencia"] = $this->db->insert_id();
          return($resultado);
        }

        function ValidarResumenComprobanteFisicoContingencia($data)
        {
          $id=$data["IdComprobanteVenta"];

          $query = $this->db->query("select *
            from ResumenComprobanteFisicoContingencia
            where IdComprobanteVenta = '$id'");
          $resultado = $query->result_array();
          return $resultado;
        }


        function ActualizarResumenComprobanteFisicoContingencia($data)
        {
          $id=$data["IdResumenComprobanteFisicoContingencia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->ResumenComprobanteFisicoContingencia);
          $this->db->where('IdResumenComprobanteFisicoContingencia', $id);
          $this->db->update('ResumenComprobanteFisicoContingencia', $resultado);

          return $resultado;
        }

        function BorrarResumenComprobanteFisicoContingencia($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarResumenComprobanteFisicoContingencia($data);
        }

 }
