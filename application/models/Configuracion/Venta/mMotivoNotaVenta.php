<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMotivoNotaVenta extends CI_Model {

        public $MotivoNotaVenta = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MotivoNotaVenta = $this->Base->Construir("MotivoNotaVenta");
        }

        function ListarMotivoNotasVenta()
        {
          $this->db->select("*")
          ->from('MotivoNotaVenta')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdMotivoNotaVenta');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarMotivoNotaVenta($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->MotivoNotaVenta);
          $this->db->insert('MotivoNotaVenta', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarMotivoNotaVenta($data)
        {
          $id=$data["IdMotivoNotaVenta"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->MotivoNotaVenta);
          $this->db->where('IdMotivoNotaVenta', $id);
          $this->db->update('MotivoNotaVenta', $resultado);
        }

        function BorrarMotivoNotaVenta($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMotivoNotaVenta($data);
        }

        function ConsultarMotivoNotaVentaEnComprobanteVenta($data)
        {
          $id=$data["IdMotivoNotaVenta"];
          $this->db->select("ComprobanteVenta.*")
          ->from('ComprobanteVenta')
          ->where("IndicadorEstado='A' AND IdMotivoNotaVenta = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }
