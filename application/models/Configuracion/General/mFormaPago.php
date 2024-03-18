<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mFormaPago extends CI_Model {

        public $FormaPago = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->FormaPago = $this->Base->Construir("FormaPago");
        }

        function ListarFormasPago()
        {
          $this->db->select("*")
          ->from('FormaPago')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdFormaPago');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarFormaPago($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->FormaPago);
          $this->db->insert('FormaPago', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarFormaPago($data)
        {
          $id=$data["IdFormaPago"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->FormaPago);
          $this->db->where('IdFormaPago', $id);
          $this->db->update('FormaPago', $resultado);
        }

        function BorrarFormaPago($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarFormaPago($data);
        }
        function ConsultarFormaPagoEnComprobanteVenta($data)
        {
          $id=$data["IdFormaPago"];
          $this->db->select("*")
          ->from('ComprobanteVenta')
          ->where("IdFormaPago = '$id' AND IndicadorEstado = 'A' ");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }
