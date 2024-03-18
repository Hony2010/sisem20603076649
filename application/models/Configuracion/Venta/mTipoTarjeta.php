<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoTarjeta extends CI_Model {

        public $TipoTarjeta = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoTarjeta = $this->Base->Construir("TipoTarjeta");
        }

        function ListarTiposTarjeta()
        {
          $this->db->select("*")
          ->from('TipoTarjeta')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdTipoTarjeta');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoTarjeta($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoTarjeta);
          $this->db->insert('TipoTarjeta', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoTarjeta($data)
        {
          $id=$data["IdTipoTarjeta"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoTarjeta);
          $this->db->where('IdTipoTarjeta', $id);
          $this->db->update('TipoTarjeta', $resultado);
        }

        function BorrarTipoTarjeta($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoTarjeta($data);
        }

        function ConsultarTipoTarjetaEnComprobanteVenta($data)
        {
          $id=$data["IdTipoTarjeta"];
          $this->db->select("ComprobanteVenta.*")
          ->from('ComprobanteVenta')
          ->where("IndicadorEstado='A' AND IdTipoTarjeta = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }
