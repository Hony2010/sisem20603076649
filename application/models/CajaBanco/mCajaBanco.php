<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCajaBanco extends CI_Model {

        public $CajaBanco = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->CajaBanco = $this->Base->Construir("CajaBanco");
        }

        function ListarCajasBanco()
        {
          $this->db->select("*")
          ->from('CajaBanco')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdCajaBanco');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarCajaBanco($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->CajaBanco);
          $this->db->insert('CajaBanco', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarCajaBanco($data)
        {
          $id=$data["IdCajaBanco"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->CajaBanco);
          $this->db->where('IdCajaBanco', $id);
          $this->db->update('CajaBanco', $resultado);
        }

        function BorrarCajaBanco($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarCajaBanco($data);
        }

        function ConsultarCajaBancoEnVenta($data)
        {
          $id=$data["IdCajaBanco"];
          $this->db->select("Venta.*")
          ->from('Venta')
          ->where("IndicadorEstado='A' AND IdCajaBanco = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function Consultar($data)
        {
          $id=$data["CodigoCajaBanco"];
          $this->db->select("Venta.*")
          ->from('Venta')
          ->where("IndicadorEstado='A' AND IdCajaBanco = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoCajaBancoParaInsertar($data)
        {
          $codigo=$data["CodigoCajaBanco"];
          $query = $this->db->query("Select *
                                     From CajaBanco
                                     Where CodigoCajaBanco = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoCajaBancoParaActualizar($data)
        {
          $id=$data["IdCajaBanco"];
          $codigo=$data["CodigoCajaBanco"];
          $query = $this->db->query("Select *
                                     From CajaBanco
                                     Where (IdCajaBanco > '$id' Or IdCajaBanco < '$id' ) and CodigoCajaBanco = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
