<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMedioPago extends CI_Model {

        public $MedioPago = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->MedioPago = $this->Base->Construir("MedioPago");
        }

        function ListarMediosPago()
        {
          $this->db->select("*")
          ->from('MedioPago')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdMedioPago');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarMedioPago($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->MedioPago);
          $this->db->insert('MedioPago', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarMedioPago($data)
        {
          $id=$data["IdMedioPago"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->MedioPago);
          $this->db->where('IdMedioPago', $id);
          $this->db->update('MedioPago', $resultado);
        }

        function BorrarMedioPago($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMedioPago($data);
        }

        function ConsultarMedioPagoEnVenta($data)
        {
          $id=$data["IdMedioPago"];
          $this->db->select("Venta.*")
          ->from('Venta')
          ->where("IndicadorEstado='A' AND IdMedioPago = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoMedioPagoParaInsertar($data)
        {
          $codigo=$data["CodigoMedioPago"];
          $query = $this->db->query("Select *
                                     From MedioPago
                                     Where CodigoMedioPago = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoMedioPagoParaActualizar($data)
        {
          $id=$data["IdMedioPago"];
          $codigo=$data["CodigoMedioPago"];
          $query = $this->db->query("Select *
                                     From MedioPago
                                     Where (IdMedioPago > '$id' Or IdMedioPago < '$id' ) and CodigoMedioPago = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
